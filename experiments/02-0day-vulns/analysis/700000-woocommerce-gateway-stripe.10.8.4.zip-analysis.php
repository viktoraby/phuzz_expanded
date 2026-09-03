<?php
/***
*
*Found actions: 2
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 2
*Overview: {'create_and_confirm_setup_intent_ajax': {'wc_stripe_create_and_confirm_setup_intent'}, 'ajax_get_oauth_url': {'wc_stripe_get_oauth_url'}}
*
***/

/** Function create_and_confirm_setup_intent_ajax() called by wp_ajax hooks: {'wc_stripe_create_and_confirm_setup_intent'} **/
/** Parameters found in function create_and_confirm_setup_intent_ajax(): {"post": ["update_all_subscription_payment_methods"]} **/
function create_and_confirm_setup_intent_ajax() {
		$wc_add_payment_method_rate_limit_id = 'add_payment_method_' . get_current_user_id();

		try {
			// similar rate limiter is present in WC Core, but it's executed on page submission (and not on AJAX calls).
			if ( WC_Rate_Limiter::retried_too_soon( $wc_add_payment_method_rate_limit_id ) ) {
				throw new WC_Stripe_Exception( 'Failed to save payment method.', __( 'You cannot add a new payment method so soon after the previous one.', 'woocommerce-gateway-stripe' ) );
			}

			$is_nonce_valid = check_ajax_referer( 'wc_stripe_create_and_confirm_setup_intent_nonce', false, false );
			if ( ! $is_nonce_valid ) {
				throw new WC_Stripe_Exception( 'Invalid nonce.', __( 'Unable to verify your request. Please refresh the page and try again.', 'woocommerce-gateway-stripe' ) );
			}

			/**
			 * Filter to validate captcha for create and confirm setup intent requests.
			 * Can be used by third-party plugins to add captcha validation.
			 *
			 * @since 10.1.0
			 * @param bool $is_captcha_valid True if the captcha is valid, false otherwise. Default is true.
			 */
			$is_captcha_valid = apply_filters( 'wc_stripe_is_valid_create_and_confirm_setup_intent_captcha', true );
			if ( ! $is_captcha_valid ) {
				throw new WC_Stripe_Exception( 'captcha_invalid', __( 'Captcha verification failed. Please try again.', 'woocommerce-gateway-stripe' ) );
			}

			$payment_method = sanitize_text_field( wp_unslash( $_POST['wc-stripe-payment-method'] ?? '' ) );
			$payment_type   = sanitize_text_field( wp_unslash( $_POST['wc-stripe-payment-type'] ?? WC_Stripe_Payment_Methods::CARD ) );
			if ( ! $payment_method ) {
				throw new WC_Stripe_Exception( 'Payment method missing from request.', __( "We're not able to add this payment method. Please refresh the page and try again.", 'woocommerce-gateway-stripe' ) );
			}

			// Determine the customer managing the payment methods, create one if we don't have one already.
			$user = wp_get_current_user();
			// This page is only accessible to logged in users.
			if ( ! $user->ID ) {
				throw new WC_Stripe_Exception( 'User not found.', __( "We're not able to add this payment method. Please refresh the page and try again.", 'woocommerce-gateway-stripe' ) );
			}
			$customer = new WC_Stripe_Customer( $user->ID );

			// Manually create the payment information array to create & confirm the setup intent.
			$payment_information = [
				'payment_method'        => $payment_method,
				'customer'              => $customer->update_or_create_customer( [], WC_Stripe_Customer::CUSTOMER_CONTEXT_ADD_PAYMENT_METHOD ),
				'selected_payment_type' => $payment_type,
				'return_url'            => wc_get_account_endpoint_url( 'payment-methods' ),
				'use_stripe_sdk'        => 'true', // We want the user to complete the next steps via the JS elements. ref https://docs.stripe.com/api/setup_intents/create#create_setup_intent-use_stripe_sdk
			];

			// If the user has requested to update all their subscription payment methods, add a query arg to the return URL so we can handle that request upon return.
			if ( ! empty( $_POST['update_all_subscription_payment_methods'] ) ) {
				$payment_information['return_url'] = add_query_arg( "wc-stripe-{$payment_type}-update-all-subscription-payment-methods", 'true', $payment_information['return_url'] );
			}

			$setup_intent = $this->create_and_confirm_setup_intent( $payment_information );

			if ( empty( $setup_intent->status ) || ! in_array( $setup_intent->status, WC_Stripe_Intent_Status::SUCCESSFUL_SETUP_INTENT_STATUSES, true ) ) {
				throw new WC_Stripe_Exception( 'Response from Stripe: ' . print_r( $setup_intent, true ), __( 'There was an error adding this payment method. Please refresh the page and try again', 'woocommerce-gateway-stripe' ) );
			}

			wp_send_json_success(
				[
					'status'        => $setup_intent->status,
					'id'            => $setup_intent->id,
					'client_secret' => $setup_intent->client_secret,
					'next_action'   => $setup_intent->next_action,
					'payment_type'  => $payment_type,
					'return_url'    => rawurlencode( $payment_information['return_url'] ),
				],
				200
			);
		} catch ( WC_Stripe_Exception $e ) {
			WC_Stripe_Logger::error( 'Failed to create and confirm setup intent.', [ 'error_message' => $e->getMessage() ] );

			/**
			 * Filter the rate limit delay after a failure adding a payment method.
			 *
			 * @since 9.7.0
			 *
			 * @param int $rate_limit_delay The rate limit delay in seconds.
			 * @param WC_Stripe_Exception $e The exception that occurred.
			 */
			$rate_limit_delay = apply_filters( 'wc_stripe_add_payment_method_on_error_rate_limit_delay', 10, $e );

			WC_Rate_Limiter::set_rate_limit( $wc_add_payment_method_rate_limit_id, $rate_limit_delay );

			// Send back error so it can be displayed to the customer.
			wp_send_json_error(
				[
					'error' => [
						'message' => $e->getLocalizedMessage(),
					],
				]
			);
		}
	}


/** Function ajax_get_oauth_url() called by wp_ajax hooks: {'wc_stripe_get_oauth_url'} **/
/** Parameters found in function ajax_get_oauth_url(): {"post": ["mode"]} **/
function ajax_get_oauth_url() {
		// Check nonce and capabilities
		if ( ! check_ajax_referer( 'wc_stripe_get_oauth_url', 'nonce', false ) ||
			! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( [ 'message' => __( 'You do not have permission to do this.', 'woocommerce-gateway-stripe' ) ] );
			return;
		}

		$mode = isset( $_POST['mode'] ) ? sanitize_text_field( wp_unslash( $_POST['mode'] ) ) : 'test';
		if ( ! in_array( $mode, [ 'live', 'test' ], true ) ) {
			$mode = 'test';
		}

		$oauth_url = woocommerce_gateway_stripe()->connect->get_oauth_url( '', $mode );

		if ( is_wp_error( $oauth_url ) ) {
			wp_send_json_error( [ 'message' => $oauth_url->get_error_message() ] );
			return;
		}

		wp_send_json_success( [ 'oauth_url' => $oauth_url ] );
	}


