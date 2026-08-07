<?php
/***
*
*Found actions: 32
*Found functions:28
*Extracted functions:27
*Total parameter names extracted: 26
*Overview: {'fetch_subscription': {'srfm_fetch_subscription'}, 'ajax_delete_log': {'srfm_delete_payment_log'}, 'pointer_dismissed': {'sureforms_dismiss_pointer'}, 'create_payment_intent': {'srfm_create_payment_intent', 'nopriv_srfm_create_payment_intent'}, 'wp_ajax_install_plugin': {'sureforms_recommended_plugin_install'}, 'pointer_accepted_cta': {'sureforms_accept_cta'}, 'ajax_cancel_subscription': {'srfm_frontend_cancel_subscription', 'srfm_stripe_cancel_subscription'}, 'send_plugin_deactivate_feedback': {'uds_plugin_deactivate_feedback'}, 'dismiss_notice': {'astra-notice-dismiss'}, 'fetch_payments': {'srfm_fetch_payments_transactions'}, 'ajax_add_note': {'srfm_add_payment_note'}, 'track_ai_widget_usage': {'srfm_ai_widget_usage'}, 'required_plugin_activate': {'sureforms_recommended_plugin_activate'}, 'download_export_file': {'srfm_download_export'}, 'fetch_forms_list': {'srfm_fetch_forms_list'}, 'pointer_should_show': {'should_show_pointer'}, 'ajax_bulk_delete_payments': {'srfm_bulk_delete_payments'}, 'create_subscription_intent': {'srfm_create_subscription_intent', 'nopriv_srfm_create_subscription_intent'}, 'srfm_global_sidebar_enabled': {'srfm_global_sidebar_enabled'}, 'ajax_delete_note': {'srfm_delete_payment_note'}, 'srfm_global_update_allowed_block': {'srfm_global_update_allowed_block'}, 'generate_data_for_suretriggers_integration': {'sureforms_integration'}, 'fetch_single_payment': {'srfm_fetch_single_payment'}, 'field_unique_validation': {'nopriv_validation_ajax_action', 'validation_ajax_action'}, 'handle_dismiss': {'srfm_editor_nudge_dismiss'}, 'ajax_refund_payment': {'srfm_refund_payment'}, 'ajax_pause_subscription': {'srfm_stripe_pause_subscription'}, 'handle_notice_response': {'srfm_notice_response'}}
*
***/

/** Function fetch_subscription() called by wp_ajax hooks: {'srfm_fetch_subscription'} **/
/** Parameters found in function fetch_subscription(): {"post": ["nonce", "subscription_id"]} **/
function fetch_subscription() {
		// Verify nonce for security.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'srfm_payment_admin_nonce' ) ) {
			wp_send_json_error( [ 'message' => __( 'Security verification failed.', 'sureforms' ) ] );
		}

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => __( 'Insufficient permissions.', 'sureforms' ) ] );
		}

		// Validate subscription ID - could be main subscription record ID or subscription_id.
		$subscription_id = isset( $_POST['subscription_id'] ) ? sanitize_text_field( wp_unslash( $_POST['subscription_id'] ) ) : '';
		if ( empty( $subscription_id ) ) {
			wp_send_json_error( [ 'message' => __( 'Subscription ID is required.', 'sureforms' ) ] );
		}

		try {
			// Check if the ID is a payment record ID first.
			if ( is_numeric( $subscription_id ) ) {
				$payment_record = Payments::get( absint( $subscription_id ) );
				if ( $payment_record && 'subscription' === ( $payment_record['type'] ?? '' ) ) {
					// This is a main subscription record ID, get the Stripe subscription ID.
					$stripe_subscription_id = $payment_record['subscription_id'] ?? '';
					if ( empty( $stripe_subscription_id ) ) {
						wp_send_json_error( [ 'message' => __( 'Stripe subscription ID not found in payment record.', 'sureforms' ) ] );
					}
					$main_subscription = $payment_record;
				} else {
					wp_send_json_error( [ 'message' => __( 'Invalid subscription record.', 'sureforms' ) ] );
				}
			} else {
				// This should be a Stripe subscription ID, get the main subscription record.
				$main_subscription = Payments::get_main_subscription_record( $subscription_id );
				if ( ! $main_subscription ) {
					wp_send_json_error( [ 'message' => __( 'Subscription not found.', 'sureforms' ) ] );
				}
				$stripe_subscription_id = $subscription_id;
			}

			// Get all related billing transactions for this subscription.
			$billing_payments = Payments::get_subscription_related_payments( $stripe_subscription_id );

			// Transform main subscription data for frontend.
			$subscription_data = $this->transform_payment_for_frontend( $main_subscription );

			// Transform billing payments for frontend.
			$billing_data = [];
			foreach ( $billing_payments as $payment ) {
				$billing_data[] = $this->transform_payment_for_frontend( $payment );
			}

			// Add subscription-specific fields.
			$subscription_data['stripe_subscription_id'] = $stripe_subscription_id;
			$subscription_data['interval']               = $this->get_subscription_interval( $main_subscription );
			$subscription_data['next_payment_date']      = $this->get_next_payment_date( $main_subscription );
			$subscription_data['amount_per_cycle']       = $subscription_data['total_amount']; // Use total_amount as cycle amount.

			// Combine data.
			$response_data = [
				'subscription' => $subscription_data,
				'payments'     => $billing_data,
			];

			/**
			 * Filter subscription details response data.
			 *
			 * Allows payment gateways (PayPal, Stripe, etc.) to add gateway-specific
			 * subscription fields to the response data sent to the frontend.
			 *
			 * @since 2.0.0
			 * @param array $response_data Response data containing subscription and payments.
			 * @param array $args          Additional arguments including the main subscription record.
			 */
			$response_data = apply_filters( 'srfm_subscription_details_response', $response_data, [ 'subscription' => $main_subscription ] );

			wp_send_json_success( $response_data );

		} catch ( \Exception $e ) {
			wp_send_json_error( [ 'message' => __( 'Unable to load subscription details. Please try again.', 'sureforms' ) ] );
		}
	}


/** Function ajax_delete_log() called by wp_ajax hooks: {'srfm_delete_payment_log'} **/
/** Parameters found in function ajax_delete_log(): {"post": ["nonce", "payment_id", "log_index"]} **/
function ajax_delete_log() {
		// Verify nonce for security.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'srfm_payment_admin_nonce' ) ) {
			wp_send_json_error( [ 'message' => __( 'Security verification failed.', 'sureforms' ) ] );
		}

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => __( 'Insufficient permissions.', 'sureforms' ) ] );
		}

		// Validate and sanitize inputs.
		$payment_id = isset( $_POST['payment_id'] ) ? absint( $_POST['payment_id'] ) : 0;
		$log_index  = isset( $_POST['log_index'] ) ? absint( $_POST['log_index'] ) : -1;

		if ( empty( $payment_id ) ) {
			wp_send_json_error( [ 'message' => __( 'Payment ID is required.', 'sureforms' ) ] );
		}

		if ( $log_index < 0 ) {
			wp_send_json_error( [ 'message' => __( 'Invalid log index.', 'sureforms' ) ] );
		}

		try {
			// Delete the log entry.
			$updated_logs = $this->delete_payment_log( $payment_id, $log_index );

			if ( false === $updated_logs ) {
				wp_send_json_error( [ 'message' => __( 'Failed to delete log entry.', 'sureforms' ) ] );
			}

			wp_send_json_success( [ 'logs' => $updated_logs ] );

		} catch ( \Exception $e ) {
			wp_send_json_error( [ 'message' => __( 'Unable to delete log. Please try again.', 'sureforms' ) ] );
		}
	}


/** Function pointer_dismissed() called by wp_ajax hooks: {'sureforms_dismiss_pointer'} **/
/** Parameters found in function pointer_dismissed(): {"post": ["pointer_nonce"]} **/
function pointer_dismissed() {
		// Security: Check user capability.
		if ( ! Helper::current_user_can() ) {
			wp_send_json_error( [ 'message' => __( 'Unauthorized user.', 'sureforms' ) ], 403 );
		}
		// Security: Nonce check.
		if ( empty( $_POST['pointer_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['pointer_nonce'] ) ), 'sureforms_pointer_action' ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid nonce.', 'sureforms' ) ], 403 );
		}
		// Use Helper to update srfm_options key.
		Helper::update_srfm_option( 'pointer_popup_dismissed', time() );

		wp_send_json_success();
	}


/** Function create_payment_intent() called by wp_ajax hooks: {'srfm_create_payment_intent', 'nopriv_srfm_create_payment_intent'} **/
/** Parameters found in function create_payment_intent(): {"post": ["token", "form_id", "amount", "currency", "description", "block_id", "customer_email", "customer_name"]} **/
function create_payment_intent() {
		// Verify submit token.
		$token   = isset( $_POST['token'] ) ? sanitize_text_field( wp_unslash( $_POST['token'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- HMAC token verification replaces nonce.
		$form_id = isset( $_POST['form_id'] ) && is_numeric( $_POST['form_id'] ) ? absint( $_POST['form_id'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( ! Submit_Token::verify( $token, $form_id ) ) {
			wp_send_json_error( __( 'Security verification failed. Please refresh the page and try again.', 'sureforms' ) );
		}

		// phpcs:disable WordPress.Security.NonceVerification.Missing -- Verified via Submit_Token::verify() above.
		$amount         = intval( $_POST['amount'] ?? 0 );
		$currency       = sanitize_text_field( wp_unslash( $_POST['currency'] ?? 'usd' ) );
		$description    = sanitize_text_field( wp_unslash( $_POST['description'] ?? 'SureForms Payment' ) );
		$block_id       = sanitize_text_field( wp_unslash( $_POST['block_id'] ?? '' ) );
		$customer_email = sanitize_email( wp_unslash( $_POST['customer_email'] ?? '' ) );
		$customer_name  = sanitize_text_field( wp_unslash( $_POST['customer_name'] ?? '' ) );
		$form_id        = isset( $_POST['form_id'] ) && is_numeric( $_POST['form_id'] ) ? absint( $_POST['form_id'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		if ( $amount <= 0 ) {
			wp_send_json_error( __( 'Invalid payment amount.', 'sureforms' ) );
		}

		$amount_processed_with_currency = Stripe_Helper::amount_from_stripe_format( $amount, $currency );
		// Validate payment amount against stored form configuration.
		if ( $form_id <= 0 || empty( $block_id ) ) {
			wp_send_json_error( __( 'Invalid form configuration.', 'sureforms' ) );
		}

		// BOTH MODE: pass 'one-time' so the validator uses the correct per-type amount config.
		$validation_result = Payment_Helper::validate_payment_amount( $amount_processed_with_currency, $currency, $form_id, $block_id, 'one-time' );
		if ( ! $validation_result['valid'] ) {
			wp_send_json_error( $validation_result['message'] );
		}

		// Validate customer email (required for one-time payments).
		if ( empty( $customer_email ) || ! is_email( $customer_email ) ) {
			wp_send_json_error( __( 'Valid customer email is required for payments.', 'sureforms' ) );
		}

		try {
			// Validate Stripe connection.
			if ( ! Stripe_Helper::is_stripe_connected() ) {
				throw new \Exception( __( 'Stripe is not connected.', 'sureforms' ) );
			}

			$secret_key = Stripe_Helper::get_stripe_secret_key();

			if ( empty( $secret_key ) ) {
				throw new \Exception( __( 'Stripe secret key not found.', 'sureforms' ) );
			}

			// Create or get customer ID for logged-in users.
			$customer_id = null;
			if ( is_user_logged_in() ) {
				$customer_id = $this->get_or_create_stripe_customer(
					[
						'email' => $customer_email,
						'name'  => $customer_name,
					]
				);
			}

			$license_key = Stripe_Helper::get_license_key();

			// Create payment intent with confirm: true for immediate processing.
			$payment_intent_data = [
				'secret_key'           => $secret_key,
				'amount'               => $amount,
				'currency'             => strtolower( $currency ),
				'description'          => $description,
				'confirm'              => false, // Will be confirmed by frontend.
				'receipt_email'        => $customer_email,
				'license_key'          => $license_key,
				// One-time payments use manual capture; methods that don't support it (Bacs, Link, Cash App, BNPL) make
				// Stripe reject the deferred Elements session in live mode, and an automatic-payment-methods intent can't
				// be confirmed by the card-scoped client Element. Pin to card so the client Element, this payload, and the
				// middleware intent all agree (Apple/Google Pay are still surfaced through 'card').
				'payment_method_types' => [ 'card' ],
				'metadata'             => [
					'source'          => 'SureForms',
					'block_id'        => $block_id,
					'original_amount' => $amount,
					'receipt_email'   => $customer_email,
					'customer_name'   => $customer_name,
				],
			];

			// Add customer ID to payment intent data if user is logged in.
			if ( ! empty( $customer_id ) ) {
				$payment_intent_data['customer'] = $customer_id;
			}

			$payment_intent_data = apply_filters(
				'srfm_create_payment_intent_data',
				$payment_intent_data,
				$customer_id
			);

			$payment_intent_data = wp_json_encode( $payment_intent_data );
			$payment_intent_data = is_string( $payment_intent_data ) ? $payment_intent_data : '';
			$payment_intent_data = base64_encode( $payment_intent_data ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode

			$payment_intent = wp_remote_post(
				Stripe_Helper::middle_ware_base_url() . 'payment-intent/create',
				[
					'body'    => $payment_intent_data,
					'headers' => [
						'Content-Type' => 'application/json',
					],
				]
			);

			if ( is_wp_error( $payment_intent ) ) {
				throw new \Exception( Payment_Helper::get_error_message_by_key( 'failed_to_create_payment' ) );
			}

			$payment_intent = json_decode( wp_remote_retrieve_body( $payment_intent ), true );
			$payment_intent = is_array( $payment_intent ) ? $payment_intent : [];

			// Check if we have an error from Stripe API (verify both status and code).
			if ( isset( $payment_intent['status'] ) && 'error' === $payment_intent['status'] && isset( $payment_intent['code'] ) && ! empty( $payment_intent['code'] ) ) {
				// Handle amount_too_small error with custom message.
				if ( 'amount_too_small' === $payment_intent['code'] ) {
					// Format the amount for display.
					$currency_symbol  = Stripe_Helper::get_currency_symbol( $currency );
					$display_amount   = $amount_processed_with_currency;
					$formatted_amount = $currency_symbol . number_format( $display_amount, 2 );

					throw new \Exception(
						sprintf(
							/* translators: %s: formatted payment amount */
							__( 'The payment amount (%s) is below the minimum allowed. Stripe only processes amounts above 50¢.', 'sureforms' ),
							$formatted_amount
						)
					);
				}

				// For other error codes, use the message from Stripe API if available.
				if ( isset( $payment_intent['message'] ) && ! empty( $payment_intent['message'] ) ) {
					throw new \Exception( $payment_intent['message'] );
				}

				// Fallback if we have error code but no message.
				throw new \Exception( Payment_Helper::get_error_message_by_key( 'failed_to_create_payment' ) );
			}

			if ( ! isset( $payment_intent['client_secret'] ) || empty( $payment_intent['client_secret'] ) || ! isset( $payment_intent['id'] ) || empty( $payment_intent['id'] ) ) {
				throw new \Exception( Payment_Helper::get_error_message_by_key( 'failed_to_create_payment' ) );
			}

			// Store payment intent metadata in transient for verification.
			// active_type binds this intent to the one-time flow so a tampered
			// submission cannot replay it through the subscription submit path.
			Payment_Helper::store_payment_intent_metadata(
				$block_id,
				$payment_intent['id'],
				[
					'form_id'     => $form_id,
					'block_id'    => $block_id,
					'amount'      => $amount_processed_with_currency,
					'currency'    => strtolower( $currency ),
					'active_type' => 'one-time',
				]
			);

			wp_send_json_success(
				[
					'client_secret'     => $payment_intent['client_secret'],
					'payment_intent_id' => $payment_intent['id'],
					'customer_id'       => $customer_id,
				]
			);
		} catch ( \Exception $e ) {
			$error_message = $e->getMessage();
			$error_message = empty( $error_message ) ? Payment_Helper::get_error_message_by_key( 'failed_to_create_payment' ) : $error_message;
			wp_send_json_error( $error_message );
		}
	}


/** Function wp_ajax_install_plugin() called by wp_ajax hooks: {'sureforms_recommended_plugin_install'} **/
/** No function found :-/ **/


/** Function pointer_accepted_cta() called by wp_ajax hooks: {'sureforms_accept_cta'} **/
/** Parameters found in function pointer_accepted_cta(): {"post": ["pointer_nonce"]} **/
function pointer_accepted_cta() {
		// Security: Check user capability.
		if ( ! Helper::current_user_can() ) {
			wp_send_json_error( [ 'message' => __( 'Unauthorized user.', 'sureforms' ) ], 403 );
		}
		// Security: Nonce check.
		if ( empty( $_POST['pointer_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['pointer_nonce'] ) ), 'sureforms_pointer_action' ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid nonce.', 'sureforms' ) ], 403 );
		}
		// Use Helper to update srfm_options key.
		Helper::update_srfm_option( 'pointer_popup_accepted', time() );

		wp_send_json_success();
	}


/** Function ajax_cancel_subscription() called by wp_ajax hooks: {'srfm_frontend_cancel_subscription', 'srfm_stripe_cancel_subscription'} **/
/** Parameters found in function ajax_cancel_subscription(): {"post": ["nonce", "payment_id"]} **/
function ajax_cancel_subscription() {
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ?? '' ) ), 'srfm_frontend_payment_nonce' ) ) {
			wp_send_json_error( __( 'Security check failed.', 'sureforms' ) );
		}

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( __( 'You must be logged in.', 'sureforms' ) );
		}

		$payment_id = isset( $_POST['payment_id'] ) ? absint( $_POST['payment_id'] ) : 0;

		if ( empty( $payment_id ) ) {
			wp_send_json_error( __( 'Invalid payment data.', 'sureforms' ) );
		}

		$payment = Payments::get( $payment_id );
		if ( ! $payment || ! $this->user_owns_payment( $payment, get_current_user_id() ) ) {
			wp_send_json_error( __( 'Payment not found.', 'sureforms' ) );
		}

		$type = isset( $payment['type'] ) ? strval( $payment['type'] ) : '';
		if ( 'subscription' !== $type || empty( $payment['subscription_id'] ) ) {
			wp_send_json_error( __( 'This payment is not a subscription.', 'sureforms' ) );
		}

		/**
		 * Filter to process subscription cancellation. Gateways hook into this.
		 *
		 * @since 2.8.0
		 * @param array<string,mixed> $result  Default result.
		 * @param array<string,mixed> $payment Payment record.
		 */
		$result = apply_filters(
			'srfm_process_subscription_cancellation',
			[
				'success' => false,
				'message' => __( 'Cancellation not supported for this gateway.', 'sureforms' ),
			],
			$payment
		);

		if ( ! empty( $result['success'] ) ) {
			wp_send_json_success( [ 'message' => isset( $result['message'] ) && is_scalar( $result['message'] ) ? strval( $result['message'] ) : __( 'Subscription cancelled successfully.', 'sureforms' ) ] );
		} else {
			wp_send_json_error( isset( $result['message'] ) && is_scalar( $result['message'] ) ? strval( $result['message'] ) : __( 'Failed to cancel subscription.', 'sureforms' ) );
		}
	}


/** Function send_plugin_deactivate_feedback() called by wp_ajax hooks: {'uds_plugin_deactivate_feedback'} **/
/** Parameters found in function send_plugin_deactivate_feedback(): {"post": ["reason", "feedback", "referer", "version", "source"]} **/
function send_plugin_deactivate_feedback() {

			$response_data = array( 'message' => __( 'Sorry, you are not allowed to do this operation.' ) );

			/**
			 * Check permission
			 */
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( $response_data );
			}

			/**
			 * Nonce verification
			 */
			if ( ! check_ajax_referer( 'uds_plugin_deactivate_feedback', 'security', false ) ) {
				$response_data = array( 'message' => __( 'Nonce validation failed' ) );
				wp_send_json_error( $response_data );
			}

			$feedback_data = array(
				'reason'      => isset( $_POST['reason'] ) ? sanitize_text_field( wp_unslash( $_POST['reason'] ) ) : '',
				'feedback'    => isset( $_POST['feedback'] ) ? sanitize_text_field( wp_unslash( $_POST['feedback'] ) ) : '',
				'domain_name' => isset( $_POST['referer'] ) ? sanitize_text_field( wp_unslash( $_POST['referer'] ) ) : '',
				'version'     => isset( $_POST['version'] ) ? sanitize_text_field( wp_unslash( $_POST['version'] ) ) : '',
				'plugin'      => isset( $_POST['source'] ) ? sanitize_text_field( wp_unslash( $_POST['source'] ) ) : '',
			);

			$api_args = array(
				'body'    => wp_json_encode( $feedback_data ),
				'headers' => BSF_Analytics_Helper::get_api_headers(),
				'timeout' => 15, //phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout
			);

			$target_url = BSF_Analytics_Helper::get_api_url() . self::$feedback_api_endpoint;

			$response = wp_safe_remote_post( $target_url, $api_args );

			$has_errors = BSF_Analytics_Helper::is_api_error( $response );

			if ( $has_errors['error'] ) {
				wp_send_json_error(
					array(
						'success' => false,
						'message' => $has_errors['error_message'],
					)
				);
			}

			wp_send_json_success();
		}


/** Function dismiss_notice() called by wp_ajax hooks: {'astra-notice-dismiss'} **/
/** Parameters found in function dismiss_notice(): {"post": ["notice_id", "repeat_notice_after"]} **/
function dismiss_notice() {
			check_ajax_referer( 'astra-notices', 'nonce' );

			$notice_id           = ( isset( $_POST['notice_id'] ) ) ? sanitize_key( wp_unslash( $_POST['notice_id'] ) ) : '';
			$repeat_notice_after = ( isset( $_POST['repeat_notice_after'] ) ) ? absint( $_POST['repeat_notice_after'] ) : 0;
			$notice              = $this->get_notice_by_id( $notice_id );
			$capability          = isset( $notice['capability'] ) ? $notice['capability'] : 'manage_options';

			$has_cap = current_user_can( $capability );

			/**
			 * Filters whether the current user passes the capability check for notice dismissal.
			 *
			 * Both the legacy and new filter names are fired for backward compatibility.
			 * Filters can only restrict access (return false), never grant it — if the
			 * underlying current_user_can() check fails, filters cannot override to true.
			 */
			$cap_check = apply_filters( 'astra_notices_user_cap_check', $has_cap );
			$cap_check = apply_filters( 'bsf_admin_notices_user_cap_check', $cap_check );

			if ( ! $has_cap || ! $cap_check ) {
				wp_send_json_error( esc_html__( 'Permission denied.', 'astra-notices' ) );
			}

			$allowed_notices = get_option( 'allowed_astra_notices', array() ); // Get allowed notices.

			// Define restricted user meta keys using the dynamic table prefix.
			global $wpdb;
			$wp_default_meta_keys = array(
				$wpdb->prefix . 'capabilities',
				$wpdb->prefix . 'user_level',
				$wpdb->prefix . 'user-settings',
				'account_status',
				'session_tokens',
			);

			// if $notice_id does not start with astra-notices-id and notice_id is not from the allowed notices, then return.
			if ( 0 !== strpos( $notice_id, 'astra-notices-id-' ) && ( ! in_array( $notice_id, $allowed_notices, true ) ) ) {
				wp_send_json_error( esc_html__( 'Invalid notice ID.', 'astra-notices' ) );
			}

			// Valid inputs?
			if ( ! empty( $notice_id ) ) {

				if ( in_array( $notice_id, $wp_default_meta_keys, true ) ) {
					wp_send_json_error( esc_html__( 'Invalid notice ID.', 'astra-notices' ) );
				}

				if ( ! empty( $repeat_notice_after ) ) {
					set_transient( $notice_id, true, $repeat_notice_after );
				} else {
					update_user_meta( get_current_user_id(), $notice_id, 'notice-dismissed' );
				}

				wp_send_json_success();
			}

			wp_send_json_error();
		}


/** Function fetch_payments() called by wp_ajax hooks: {'srfm_fetch_payments_transactions'} **/
/** Parameters found in function fetch_payments(): {"post": ["nonce", "search", "status", "form_id", "payment_mode", "date_from", "date_to", "page", "per_page"]} **/
function fetch_payments() {
		// Verify nonce for security.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'srfm_payment_admin_nonce' ) ) {
			wp_send_json_error( [ 'message' => __( 'Security verification failed.', 'sureforms' ) ] );
		}

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => __( 'Insufficient permissions.', 'sureforms' ) ] );
		}

		try {
			// Sanitize input parameters.
			$search       = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '';
			$status       = isset( $_POST['status'] ) ? sanitize_text_field( wp_unslash( $_POST['status'] ) ) : '';
			$form_id      = isset( $_POST['form_id'] ) ? absint( $_POST['form_id'] ) : 0;
			$payment_mode = isset( $_POST['payment_mode'] ) ? sanitize_text_field( wp_unslash( $_POST['payment_mode'] ) ) : '';
			$date_from    = isset( $_POST['date_from'] ) ? sanitize_text_field( wp_unslash( $_POST['date_from'] ) ) : '';
			$date_to      = isset( $_POST['date_to'] ) ? sanitize_text_field( wp_unslash( $_POST['date_to'] ) ) : '';
			$page         = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
			$per_page     = isset( $_POST['per_page'] ) ? absint( $_POST['per_page'] ) : 20;

			// Validate pagination parameters.
			$page     = max( 1, $page );
			$per_page = max( 1, min( 100, $per_page ) ); // Limit to 100 records per page.
			$offset   = ( $page - 1 ) * $per_page;

			// Validate date format if provided.
			if ( ! empty( $date_from ) && ! $this->validate_date( $date_from ) ) {
				wp_send_json_error( [ 'message' => __( 'Invalid date format for date_from.', 'sureforms' ) ] );
			}

			if ( ! empty( $date_to ) && ! $this->validate_date( $date_to ) ) {
				wp_send_json_error( [ 'message' => __( 'Invalid date format for date_to.', 'sureforms' ) ] );
			}

			// Get total count for pagination.
			$total_count = $this->get_payments_count( $search, $status, $date_from, $date_to, $form_id, $payment_mode );

			if ( 0 === $total_count && empty( $search ) && empty( $status ) && empty( $date_from ) && empty( $date_to ) && empty( $form_id ) && empty( $payment_mode ) ) {
				wp_send_json_success(
					[
						'payments'              => [],
						'total'                 => 0,
						'transactions_is_empty' => 'with_no_filter',
					]
				);
			}

			if ( 0 === $total_count && ( ! empty( $search ) || ! empty( $status ) || ! empty( $date_from ) || ! empty( $date_to ) || ! empty( $form_id ) || ! empty( $payment_mode ) ) ) {
				wp_send_json_success(
					[
						'payments'              => [],
						'total'                 => 0,
						'transactions_is_empty' => 'with_filter',
					]
				);
			}

			// Get payments data from database.
			$payments = $this->get_payments_data( $search, $status, $date_from, $date_to, $per_page, $offset, $form_id, $payment_mode );

			wp_send_json_success(
				[
					'payments'              => $payments,
					'total'                 => $total_count,
					'page'                  => $page,
					'per_page'              => $per_page,
					'total_pages'           => ceil( $total_count / $per_page ),
					'transactions_is_empty' => false,
				]
			);

		} catch ( \Exception $e ) {
			wp_send_json_error( [ 'message' => __( 'Unable to load payments. Please refresh the page.', 'sureforms' ) ] );
		}
	}


/** Function ajax_add_note() called by wp_ajax hooks: {'srfm_add_payment_note'} **/
/** Parameters found in function ajax_add_note(): {"post": ["nonce", "payment_id", "note_text"]} **/
function ajax_add_note() {
		// Verify nonce for security.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'srfm_payment_admin_nonce' ) ) {
			wp_send_json_error( [ 'message' => __( 'Security verification failed.', 'sureforms' ) ] );
		}

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => __( 'Insufficient permissions.', 'sureforms' ) ] );
		}

		// Validate and sanitize inputs.
		$payment_id = isset( $_POST['payment_id'] ) ? absint( $_POST['payment_id'] ) : 0;
		$note_text  = isset( $_POST['note_text'] ) ? sanitize_textarea_field( wp_unslash( $_POST['note_text'] ) ) : '';

		if ( empty( $payment_id ) ) {
			wp_send_json_error( [ 'message' => __( 'Payment ID is required.', 'sureforms' ) ] );
		}

		if ( empty( trim( $note_text ) ) ) {
			wp_send_json_error( [ 'message' => __( 'Note text cannot be empty.', 'sureforms' ) ] );
		}

		try {
			// Add the note.
			$updated_notes = $this->add_payment_note( $payment_id, $note_text );

			if ( false === $updated_notes ) {
				wp_send_json_error( [ 'message' => __( 'Failed to add note.', 'sureforms' ) ] );
			}

			wp_send_json_success( [ 'notes' => $this->get_formatted_notes( $updated_notes ) ] );

		} catch ( \Exception $e ) {
			wp_send_json_error( [ 'message' => __( 'Unable to add note. Please try again.', 'sureforms' ) ] );
		}
	}


/** Function track_ai_widget_usage() called by wp_ajax hooks: {'srfm_ai_widget_usage'} **/
/** No params detected :-/ **/


/** Function required_plugin_activate() called by wp_ajax hooks: {'sureforms_recommended_plugin_activate'} **/
/** Parameters found in function required_plugin_activate(): {"post": ["init", "slug"]} **/
function required_plugin_activate() {

		$response_data = [ 'message' => $this->get_error_msg( 'permission' ) ];

		if ( ! Helper::current_user_can() ) {
			wp_send_json_error( $response_data );
		}

		if ( empty( $_POST ) ) {
			$response_data = [ 'message' => $this->get_error_msg( 'invalid' ) ];
			wp_send_json_error( $response_data );
		}

		/**
		 * Nonce verification.
		 */
		if ( ! check_ajax_referer( 'sf_plugin_manager_nonce', 'security', false ) ) {
			$response_data = [ 'message' => $this->get_error_msg( 'nonce' ) ];
			wp_send_json_error( $response_data );
		}

		if ( ! isset( $_POST['init'] ) || ! sanitize_text_field( wp_unslash( $_POST['init'] ) ) ) {
			wp_send_json_error(
				[
					'success' => false,
					'message' => __( 'No plugin specified', 'sureforms' ),
				]
			);
		}

		$plugin_init = isset( $_POST['init'] ) ? sanitize_text_field( wp_unslash( $_POST['init'] ) ) : '';

		$plugin_slug = isset( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';

		$activate = activate_plugin( $plugin_init, '', false, true );

		if ( is_wp_error( $activate ) ) {
			wp_send_json_error(
				[
					'success' => false,
					'message' => $activate->get_error_message(),
				]
			);
		}

		if ( class_exists( 'BSF_UTM_Analytics' ) && is_callable( 'BSF_UTM_Analytics::update_referer' ) ) {
			$plugin_slug = pathinfo( $plugin_slug, PATHINFO_FILENAME );
			BSF_UTM_Analytics::update_referer( 'sureforms', $plugin_slug );
		}

		wp_send_json_success(
			[
				'success' => true,
				'message' => __( 'Plugin Successfully Activated', 'sureforms' ),
			]
		);
	}


/** Function download_export_file() called by wp_ajax hooks: {'srfm_download_export'} **/
/** Parameters found in function download_export_file(): {"get": ["_wpnonce", "file"]} **/
function download_export_file() {
		// Check user permissions.
		if ( ! Helper::current_user_can() ) {
			wp_die( esc_html__( 'You do not have permission to access this file.', 'sureforms' ) );
		}

		// Verify nonce for security.
		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'srfm_download_export' ) ) {
			wp_die( esc_html__( 'Security check failed.', 'sureforms' ) );
		}

		// Get and sanitize the file parameter.
		$file = isset( $_GET['file'] ) ? sanitize_file_name( wp_unslash( $_GET['file'] ) ) : '';

		if ( empty( $file ) ) {
			wp_die( esc_html__( 'Invalid file request.', 'sureforms' ) );
		}

		// Build the full file path.
		$temp_dir = wp_normalize_path( trailingslashit( get_temp_dir() ) );
		$filepath = $temp_dir . $file;

		// Security check: ensure the file is in the temp directory.
		if ( strpos( wp_normalize_path( $filepath ), $temp_dir ) !== 0 ) {
			wp_die( esc_html__( 'Invalid file path.', 'sureforms' ) );
		}

		// Check if file exists.
		if ( ! file_exists( $filepath ) ) {
			wp_die( esc_html__( 'File not found.', 'sureforms' ) );
		}

		// Get file info.
		$file_size = filesize( $filepath );
		$file_info = pathinfo( $filepath );

		// Determine content type and filename based on file extension.
		$content_type = 'application/octet-stream';
		$filename     = $file_info['basename'];
		if ( isset( $file_info['extension'] ) ) {
			if ( 'csv' === $file_info['extension'] ) {
				$content_type = 'text/csv';
			} elseif ( 'zip' === $file_info['extension'] ) {
				$content_type = 'application/zip';
				/**
				 * Filter the user-facing filename used when serving an exported ZIP archive.
				 *
				 * @since 2.9.0
				 *
				 * @param string             $filename  Default ZIP filename.
				 * @param array<string,mixed> $file_info pathinfo() result for the file being served.
				 */
				$filename = (string) apply_filters( 'srfm_export_zip_filename', 'SureForms Entries.zip', $file_info );
			}
		}

		// Set headers for download.
		header( 'Content-Type: ' . $content_type );
		header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
		header( 'Content-Length: ' . $file_size );
		header( 'Cache-Control: private, max-age=0, must-revalidate' );
		header( 'Pragma: public' );

		// Clear output buffers.
		if ( ob_get_level() ) {
			ob_end_clean();
		}

		// Output the file.
		readfile( $filepath ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_readfile, WordPress.WP.AlternativeFunctions.file_system_operations_readfile -- Direct file output is required to stream the download.

		// Clean up the temporary file.
		wp_delete_file( $filepath );

		exit;
	}


/** Function fetch_forms_list() called by wp_ajax hooks: {'srfm_fetch_forms_list'} **/
/** Parameters found in function fetch_forms_list(): {"post": ["nonce"]} **/
function fetch_forms_list() {
		// Verify nonce for security.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'srfm_payment_admin_nonce' ) ) {
			wp_send_json_error( [ 'message' => __( 'Security verification failed.', 'sureforms' ) ] );
		}

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => __( 'Insufficient permissions.', 'sureforms' ) ] );
		}

		try {
			// Get all published forms.
			$all_forms = \SRFM\Inc\Helper::get_sureforms();

			// Get form IDs that have payments.
			$forms_with_payments = \SRFM\Inc\Database\Tables\Payments::get_all_forms_with_payments();

			// Filter forms to only include those with payments.
			$forms_list = [];
			foreach ( $all_forms as $form_id => $form_title ) {
				// Only include forms that have payment records.
				if ( in_array( (int) $form_id, $forms_with_payments, true ) ) {
					$forms_list[] = [
						'id'    => $form_id,
						/* translators: %d: Form ID */
						'title' => ! empty( $form_title ) ? $form_title : sprintf( __( 'Form - #%d', 'sureforms' ), $form_id ),
					];
				}
			}

			wp_send_json_success( [ 'forms' => $forms_list ] );

		} catch ( \Exception $e ) {
			wp_send_json_error( [ 'message' => __( 'Unable to load forms. Please try again.', 'sureforms' ) ] );
		}
	}


/** Function pointer_should_show() called by wp_ajax hooks: {'should_show_pointer'} **/
/** Parameters found in function pointer_should_show(): {"post": ["pointer_nonce"]} **/
function pointer_should_show() {
		// Security: Check user capability.
		if ( ! Helper::current_user_can() ) {
			wp_send_json_error( [ 'message' => __( 'Unauthorized user.', 'sureforms' ) ], 403 );
		}
		// Security: Nonce check.
		if ( empty( $_POST['pointer_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['pointer_nonce'] ) ), 'sureforms_pointer_action' ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid nonce.', 'sureforms' ) ], 403 );
		}

		$content_markup = sprintf(
			/* translators: 1: opening span, 2: opening strong (inline), 3: closing strong, 4: closing span, 5: opening strong (block), 6: closing strong */
			__( '%1$sGet started by %2$sbuilding your first form%3$s.%4$s%5$sExperience the power of our intuitive AI Form Builder%6$s', 'sureforms' ),
			'<span>',
			'<strong>',
			'</strong>',
			'</span><br/>',
			'<strong style="font-size:1.1em;">',
			'</strong>'
		);
		wp_send_json(
			[
				'show'        => true,
				'title'       => esc_html( __( 'SureForms is waiting for you!', 'sureforms' ) ),
				'content'     => wp_kses_post( $content_markup ),
				'button_text' => esc_html( __( 'Build My First Form', 'sureforms' ) ),
				'dismiss'     => esc_html( __( 'Dismiss', 'sureforms' ) ),
				'button_url'  => admin_url( 'admin.php?page=add-new-form' ),
			]
		);
	}


/** Function ajax_bulk_delete_payments() called by wp_ajax hooks: {'srfm_bulk_delete_payments'} **/
/** Parameters found in function ajax_bulk_delete_payments(): {"post": ["nonce", "payment_ids"]} **/
function ajax_bulk_delete_payments() {
		// Verify nonce for security.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'srfm_payment_admin_nonce' ) ) {
			wp_send_json_error( [ 'message' => __( 'Security verification failed.', 'sureforms' ) ] );
		}

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => __( 'Insufficient permissions.', 'sureforms' ) ] );
		}

		// Get and validate payment IDs.
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized below after JSON decode.
		$payment_ids_raw = isset( $_POST['payment_ids'] ) ? wp_unslash( $_POST['payment_ids'] ) : [];

		// Handle JSON string or array format.
		if ( is_string( $payment_ids_raw ) ) {
			// Decode JSON string.
			$payment_ids = json_decode( $payment_ids_raw, true );

			// Check if JSON decode was successful.
			if ( json_last_error() !== JSON_ERROR_NONE ) {
				wp_send_json_error( [ 'message' => __( 'Invalid JSON format for payment IDs.', 'sureforms' ) ] );
			}
		} else {
			$payment_ids = $payment_ids_raw;
		}

		// Ensure it's an array.
		if ( ! is_array( $payment_ids ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid payment IDs format.', 'sureforms' ) ] );
		}

		// Sanitize: Convert to integers and remove invalid values.
		// This handles both string numbers ("169") and actual integers.
		$payment_ids = array_map( 'absint', $payment_ids );
		$payment_ids = array_filter(
			$payment_ids,
			static function ( $id ) {
				return $id > 0;
			}
		);

		// Re-index array to ensure sequential keys.
		$payment_ids = array_values( $payment_ids );

		// Check if array is empty after sanitization.
		if ( empty( $payment_ids ) ) {
			wp_send_json_error( [ 'message' => __( 'No valid payment IDs provided.', 'sureforms' ) ] );
		}

		// Limit bulk operations to prevent timeout (max 100 at once).
		if ( count( $payment_ids ) > 100 ) {
			wp_send_json_error(
				[
					'message' => __( 'Cannot delete more than 100 payments at once. Select fewer payments.', 'sureforms' ),
				]
			);
		}

		try {
			$deleted_count = 0;
			$failed_ids    = [];

			// Delete each payment with proper error handling.
			foreach ( $payment_ids as $payment_id ) {
				// Verify payment exists before attempting delete.
				$payment = Payments::get( $payment_id );

				if ( ! $payment ) {
					$failed_ids[] = $payment_id;
					continue;
				}

				// Attempt deletion.
				$result = Payments::delete( $payment_id );

				if ( $result ) {
					$deleted_count++;
				} else {
					$failed_ids[] = $payment_id;
				}
			}

			// Prepare response message.
			if ( count( $payment_ids ) === $deleted_count ) {
				// All deleted successfully.
				wp_send_json_success(
					[
						'message'       => sprintf(
							/* translators: %d: number of payments deleted */
							_n(
								'%d payment deleted successfully.',
								'%d payments deleted successfully.',
								$deleted_count,
								'sureforms'
							),
							$deleted_count
						),
						'deleted_count' => $deleted_count,
					]
				);
			} elseif ( $deleted_count > 0 ) {
				// Partial success.
				wp_send_json_success(
					[
						'message'       => sprintf(
							/* translators: 1: number deleted, 2: number failed */
							__( '%1$d payment(s) deleted successfully. %2$d failed.', 'sureforms' ),
							$deleted_count,
							count( $failed_ids )
						),
						'deleted_count' => $deleted_count,
						'failed_count'  => count( $failed_ids ),
						'partial'       => true,
					]
				);
			} else {
				// All failed.
				wp_send_json_error(
					[
						'message'      => __( 'Failed to delete payments. Please try again.', 'sureforms' ),
						'failed_count' => count( $failed_ids ),
					]
				);
			}
		} catch ( \Exception $e ) {
			wp_send_json_error(
				[
					'message' => __( 'Unable to delete payments. Please try again.', 'sureforms' ),
				]
			);
		}
	}


/** Function create_subscription_intent() called by wp_ajax hooks: {'srfm_create_subscription_intent', 'nopriv_srfm_create_subscription_intent'} **/
/** Parameters found in function create_subscription_intent(): {"post": ["token", "form_id", "amount", "currency", "description", "block_id", "interval", "plan_name", "customer_email", "customer_name"]} **/
function create_subscription_intent() {
		// Verify submit token.
		$token   = isset( $_POST['token'] ) ? sanitize_text_field( wp_unslash( $_POST['token'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- HMAC token verification replaces nonce.
		$form_id = isset( $_POST['form_id'] ) && is_numeric( $_POST['form_id'] ) ? absint( $_POST['form_id'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( ! Submit_Token::verify( $token, $form_id ) ) {
			wp_send_json_error( __( 'Security verification failed. Please refresh the page and try again.', 'sureforms' ) );
		}

		// phpcs:disable WordPress.Security.NonceVerification.Missing -- Verified via Submit_Token::verify() above.

		// Validate required fields like simple-stripe-subscriptions.
		$required_fields = [ 'amount', 'currency', 'description', 'block_id', 'interval', 'plan_name' ];
		foreach ( $required_fields as $field ) {
			if ( empty( $_POST[ $field ] ) ) {
				/* translators: %s: Field name */
				wp_send_json_error( sprintf( __( 'Missing required field: %s', 'sureforms' ), $field ) );
			}
		}

		$amount      = intval( $_POST['amount'] ?? 0 );
		$currency    = sanitize_text_field( wp_unslash( $_POST['currency'] ?? 'usd' ) );
		$description = sanitize_text_field( wp_unslash( $_POST['description'] ?? 'SureForms Subscription' ) );
		$block_id    = sanitize_text_field( wp_unslash( $_POST['block_id'] ?? '' ) );

		$subscription_interval = sanitize_text_field( wp_unslash( $_POST['interval'] ?? 'month' ) );
		$plan_name             = sanitize_text_field( wp_unslash( $_POST['plan_name'] ?? 'Subscription Plan' ) );
		$customer_email        = sanitize_email( wp_unslash( $_POST['customer_email'] ?? '' ) );
		$customer_name         = sanitize_text_field( wp_unslash( $_POST['customer_name'] ?? '' ) );
		$form_id               = isset( $_POST['form_id'] ) && is_numeric( $_POST['form_id'] ) ? absint( $_POST['form_id'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing

		// phpcs:enable WordPress.Security.NonceVerification.Missing

		// Validate customer email (required for all subscriptions).
		if ( empty( $customer_email ) || ! is_email( $customer_email ) ) {
			wp_send_json_error( __( 'Valid customer email is required for subscriptions.', 'sureforms' ) );
		}

		// Validate customer name (required for subscriptions).
		if ( empty( $customer_name ) ) {
			wp_send_json_error( __( 'Customer name is required for subscriptions.', 'sureforms' ) );
		}

		$amount_processed_with_currency = Stripe_Helper::amount_from_stripe_format( $amount, $currency );
		// Validate payment amount against stored form configuration.
		if ( $form_id <= 0 || empty( $block_id ) ) {
			wp_send_json_error( __( 'Invalid form configuration.', 'sureforms' ) );
		}

		// BOTH MODE: pass 'subscription' so the validator uses the correct per-type amount config.
		$validation_result = Payment_Helper::validate_payment_amount( $amount_processed_with_currency, $currency, $form_id, $block_id, 'subscription' );
		if ( ! $validation_result['valid'] ) {
			wp_send_json_error( $validation_result['message'] );
		}

		// Validate amount like simple-stripe-subscriptions.
		if ( $amount <= 0 ) {
			wp_send_json_error( __( 'Amount must be greater than 0', 'sureforms' ) );
		}

		// Validate interval like simple-stripe-subscriptions.
		// BOTH MODE: 'quarter' is a valid editor option but was missing from the allow-list,
		// causing Quarterly subscriptions to be rejected at submit time.
		$valid_intervals = [ 'day', 'week', 'month', 'quarter', 'year' ];
		if ( ! in_array( $subscription_interval, $valid_intervals, true ) ) {
			wp_send_json_error( __( 'Invalid billing interval', 'sureforms' ) );
		}

		// Reject when the submitted interval does not match what the admin saved in
		// the form's stored block config. Admin picks a single interval in the editor;
		// the end user has no chooser. So a divergence here is always tampering — the
		// data attribute the server itself rendered has been altered before submit.
		$stored_block_config = Field_Validation::get_or_migrate_block_config_for_legacy_form( $form_id );
		if ( is_array( $stored_block_config ) && isset( $stored_block_config[ $block_id ] ) && is_array( $stored_block_config[ $block_id ] ) ) {
			$stored_interval = $stored_block_config[ $block_id ]['subscription_interval'] ?? '';
			if ( ! empty( $stored_interval ) && $stored_interval !== $subscription_interval ) {
				wp_send_json_error( __( 'Billing interval does not match the form configuration.', 'sureforms' ) );
			}
		}

		try {
			// Validate Stripe connection.
			if ( ! Stripe_Helper::is_stripe_connected() ) {
				throw new \Exception( __( 'Stripe is not connected.', 'sureforms' ) );
			}

			$secret_key = Stripe_Helper::get_stripe_secret_key();

			if ( empty( $secret_key ) ) {
				throw new \Exception( __( 'Stripe secret key not found.', 'sureforms' ) );
			}

			// Get or create Stripe customer for subscriptions.
			$customer_id = $this->get_or_create_stripe_customer(
				[
					'email' => $customer_email,
					'name'  => $customer_name,
				]
			);
			if ( ! $customer_id ) {
				throw new \Exception( __( 'Failed to create customer for subscription.', 'sureforms' ) );
			}

			$license_key = Stripe_Helper::get_license_key();
			// Prepare subscription data for middleware.
			$subscription_data = apply_filters(
				'srfm_create_subscription_data',
				[
					'secret_key'  => $secret_key,
					'customer_id' => $customer_id,
					'amount'      => $amount,
					'currency'    => strtolower( $currency ),
					'description' => $description,
					'interval'    => $subscription_interval,
					'license_key' => $license_key,
					'block_id'    => $block_id,
					'plan_name'   => $plan_name,
					'metadata'    => [
						'source'           => 'SureForms',
						'block_id'         => $block_id,
						'original_amount'  => $amount,
						'billing_interval' => $subscription_interval,
					],
				]
			);

			$endpoint = Stripe_Helper::middle_ware_base_url() . 'subscription/create';

			$subscription_data_body = wp_json_encode( $subscription_data );
			$subscription_data_body = is_string( $subscription_data_body ) ? $subscription_data_body : '';
			$subscription_data_body = base64_encode( $subscription_data_body ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode

			if ( empty( $subscription_data_body ) ) {
				throw new \Exception( __( 'Failed to create subscription through middleware.', 'sureforms' ) );
			}

			// Call middleware subscription creation endpoint.
			$subscription_response = wp_remote_post(
				$endpoint,
				[
					'body'    => $subscription_data_body,
					'headers' => [
						'Content-Type' => 'application/json',
					],
					'timeout' => 60, // Subscription creation can take longer.
				]
			);

			if ( is_wp_error( $subscription_response ) ) {
				throw new \Exception( __( 'Failed to create subscription through middleware.', 'sureforms' ) );
			}

			$response_body = wp_remote_retrieve_body( $subscription_response );
			if ( empty( $response_body ) ) {
				throw new \Exception( __( 'Empty response from subscription creation.', 'sureforms' ) );
			}

			$subscription = json_decode( $response_body, true );
			if ( json_last_error() !== JSON_ERROR_NONE ) {
				throw new \Exception( __( 'Invalid JSON response from subscription creation.', 'sureforms' ) );
			}

			if ( ! is_array( $subscription ) ) {
				wp_send_json_error( __( 'Invalid subscription data.', 'sureforms' ) );
			}

			if ( 'error' === $subscription['status'] ) {
				wp_send_json_error( isset( $subscription['message'] ) && ! empty( $subscription['message'] ) ? $subscription['message'] : __( 'Invalid subscription data.', 'sureforms' ) );
			}

			$payment_intent_id = isset( $subscription['setup_intent']['id'] ) && ! empty( $subscription['setup_intent']['id'] ) ? $subscription['setup_intent']['id'] : '';
			$subscription_id   = isset( $subscription['subscription_data']['id'] ) && ! empty( $subscription['subscription_data']['id'] ) ? $subscription['subscription_data']['id'] : '';
			$client_secret     = isset( $subscription['client_secret'] ) && ! empty( $subscription['client_secret'] ) ? $subscription['client_secret'] : '';
			if ( empty( $client_secret ) || empty( $subscription_id ) || empty( $payment_intent_id ) ) {
				throw new \Exception( __( 'Failed to create subscription.', 'sureforms' ) );
			}

			// Store subscription metadata in transient for verification.
			// active_type binds this intent to the subscription flow so a tampered
			// submission cannot replay it through the one-time submit path.
			Payment_Helper::store_payment_intent_metadata(
				$block_id,
				$payment_intent_id,
				[
					'form_id'         => $form_id,
					'block_id'        => $block_id,
					'amount'          => $amount_processed_with_currency,
					'currency'        => strtolower( $currency ),
					'subscription_id' => $subscription_id,
					'active_type'     => 'subscription',
				]
			);

			$response = [
				'type'              => 'subscription',
				'client_secret'     => $client_secret,
				'subscription_id'   => $subscription_id,
				'customer_id'       => $customer_id,
				'payment_intent_id' => $payment_intent_id,
				'amount'            => Stripe_Helper::amount_from_stripe_format( $amount, $currency ),
				'interval'          => $subscription_interval,
			];

			wp_send_json_success( $response );

		} catch ( \Exception $e ) {
			/* translators: %s: Error message */
			wp_send_json_error( sprintf( __( 'Unexpected error: %s', 'sureforms' ), $e->getMessage() ) );
		}
	}


/** Function srfm_global_sidebar_enabled() called by wp_ajax hooks: {'srfm_global_sidebar_enabled'} **/
/** Parameters found in function srfm_global_sidebar_enabled(): {"post": ["enableQuickActionSidebar"]} **/
function srfm_global_sidebar_enabled() {
		if ( ! Helper::current_user_can() ) {
			wp_send_json_error();
		}

		if ( ! check_ajax_referer( 'srfm_ajax_nonce', 'security', false ) ) {
			wp_send_json_error();
		}

		if ( ! empty( $_POST['enableQuickActionSidebar'] ) ) {
			$srfm_enable_quick_action_sidebar = ( 'enabled' === $_POST['enableQuickActionSidebar'] ? 'enabled' : 'disabled' );
			Helper::update_admin_settings_option( 'srfm_enable_quick_action_sidebar', $srfm_enable_quick_action_sidebar );
			wp_send_json_success();
		}
		wp_send_json_error();
	}


/** Function ajax_delete_note() called by wp_ajax hooks: {'srfm_delete_payment_note'} **/
/** Parameters found in function ajax_delete_note(): {"post": ["nonce", "payment_id", "note_index"]} **/
function ajax_delete_note() {
		// Verify nonce for security.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'srfm_payment_admin_nonce' ) ) {
			wp_send_json_error( [ 'message' => __( 'Security verification failed.', 'sureforms' ) ] );
		}

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => __( 'Insufficient permissions.', 'sureforms' ) ] );
		}

		// Validate and sanitize inputs.
		$payment_id = isset( $_POST['payment_id'] ) ? absint( $_POST['payment_id'] ) : 0;
		$note_index = isset( $_POST['note_index'] ) ? absint( $_POST['note_index'] ) : -1;

		if ( empty( $payment_id ) ) {
			wp_send_json_error( [ 'message' => __( 'Payment ID is required.', 'sureforms' ) ] );
		}

		if ( $note_index < 0 ) {
			wp_send_json_error( [ 'message' => __( 'Invalid note index.', 'sureforms' ) ] );
		}

		try {
			// Delete the note.
			$updated_notes = $this->delete_payment_note( $payment_id, $note_index );

			if ( false === $updated_notes ) {
				wp_send_json_error( [ 'message' => __( 'Failed to delete note.', 'sureforms' ) ] );
			}

			wp_send_json_success( [ 'notes' => $this->get_formatted_notes( $updated_notes ) ] );

		} catch ( \Exception $e ) {
			wp_send_json_error( [ 'message' => __( 'Unable to delete note. Please try again.', 'sureforms' ) ] );
		}
	}


/** Function srfm_global_update_allowed_block() called by wp_ajax hooks: {'srfm_global_update_allowed_block'} **/
/** Parameters found in function srfm_global_update_allowed_block(): {"post": ["defaultAllowedQuickSidebarBlocks"]} **/
function srfm_global_update_allowed_block() {
		if ( ! Helper::current_user_can() ) {
			wp_send_json_error();
		}

		if ( ! check_ajax_referer( 'srfm_ajax_nonce', 'security', false ) ) {
			wp_send_json_error();
		}

		if ( ! empty( $_POST['defaultAllowedQuickSidebarBlocks'] ) ) {
			$srfm_default_allowed_quick_sidebar_blocks = json_decode( sanitize_text_field( wp_unslash( $_POST['defaultAllowedQuickSidebarBlocks'] ) ), true );
			Helper::update_admin_settings_option( 'srfm_quick_sidebar_allowed_blocks', $srfm_default_allowed_quick_sidebar_blocks );
			wp_send_json_success();
		}
		wp_send_json_error();
	}


/** Function generate_data_for_suretriggers_integration() called by wp_ajax hooks: {'sureforms_integration'} **/
/** Parameters found in function generate_data_for_suretriggers_integration(): {"post": ["formId"]} **/
function generate_data_for_suretriggers_integration() {
		if ( ! Helper::current_user_can() ) {
			wp_send_json_error( [ 'message' => __( 'You do not have permission to access this page.', 'sureforms' ) ] );
		}

		if ( ! check_ajax_referer( 'suretriggers_nonce', 'security', false ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid nonce.', 'sureforms' ) ] );
		}

		if ( empty( $_POST['formId'] ) ) {
			wp_send_json_error( [ 'message' => __( 'Form ID is required.', 'sureforms' ) ] );
		}

		if ( ! Helper::is_suretriggers_ready() ) {
			wp_send_json_error(
				[
					'code'    => 'invalid_secret_key',
					'message' => __( 'OttoKit is not configured properly.', 'sureforms' ),
				]
			);
		}

		$form_id = Helper::get_integer_value( sanitize_text_field( wp_unslash( $_POST['formId'] ) ) );
		$form    = get_post( $form_id );

		if ( is_null( $form ) || SRFM_FORMS_POST_TYPE !== $form->post_type ) {
			wp_send_json_error( [ 'message' => __( 'Invalid form ID.', 'sureforms' ) ] );
		}

		// Translators: %s: Form ID.
		$form_name = ! empty( $form->post_title ) ? $form->post_title : sprintf( __( 'SureForms id: %s', 'sureforms' ), $form_id );
		$api_url   = apply_filters( 'suretriggers_get_iframe_url', SRFM_SURETRIGGERS_INTEGRATION_BASE_URL ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- SureTriggers' own filter; the name must match SureTriggers exactly to integrate.

		// This is the format of data required by SureTriggers for adding iframe in target id.
		$body = [
			'client_id'           => 'SureForms',
			'st_embed_url'        => $api_url,
			'embedded_identifier' => $form_id,
			'target'              => 'suretriggers-iframe-wrapper', // div where we want SureTriggers to add iframe should have this target id.
			'event'               => [
				'label'       => __( 'Form Submitted', 'sureforms' ),
				'value'       => 'sureforms_form_submitted',
				'description' => __( 'Runs when a form is submitted', 'sureforms' ),
			],
			'summary'             => $form_name,
			'selected_options'    => [
				'form_id' => [
					'value' => $form_id,
					'label' => $form_name,
				],
			],
			'integration'         => 'SureForms',
			'sample_response'     => [
				'form_id'   => $form_id,
				'to_emails' => [
					'dev-email@wpengine.local',
				],
				'form_name' => $form_name,
				'data'      => $this->get_form_fields( $form_id ),
			],
		];

		// Adding entry_id in body sample response if do_not_store_entries is not enabled.
		$compliance           = get_post_meta( $form_id, '_srfm_compliance', true );
		$do_not_store_entries = is_array( $compliance ) && isset( $compliance[0]['do_not_store_entries'] )
		? $compliance[0]['do_not_store_entries']
		: null;

		if ( ! $do_not_store_entries ) {
			$body['sample_response']['entry_id'] = 12;
		}

		wp_send_json_success(
			[
				'message' => 'success',
				'data'    => apply_filters( 'srfm_suretriggers_integration_data_filter', $body, $form_id ),
			]
		);
	}


/** Function fetch_single_payment() called by wp_ajax hooks: {'srfm_fetch_single_payment'} **/
/** Parameters found in function fetch_single_payment(): {"post": ["nonce", "payment_id"]} **/
function fetch_single_payment() {
		// Verify nonce for security.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'srfm_payment_admin_nonce' ) ) {
			wp_send_json_error( [ 'message' => __( 'Security verification failed.', 'sureforms' ) ] );
		}

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => __( 'Insufficient permissions.', 'sureforms' ) ] );
		}

		// Validate payment ID.
		$payment_id = isset( $_POST['payment_id'] ) ? absint( $_POST['payment_id'] ) : 0;
		if ( empty( $payment_id ) ) {
			wp_send_json_error( [ 'message' => __( 'Payment ID is required.', 'sureforms' ) ] );
		}

		try {
			// Get single payment from database.
			$payment = Payments::get( $payment_id );

			if ( ! $payment ) {
				wp_send_json_error( [ 'message' => __( 'Payment not found.', 'sureforms' ) ] );
			}

			// Transform payment data for frontend.
			$payment_data = $this->transform_payment_for_frontend( $payment );

			wp_send_json_success( $payment_data );

		} catch ( \Exception $e ) {
			wp_send_json_error( [ 'message' => __( 'Unable to load payment details. Please try again.', 'sureforms' ) ] );
		}
	}


/** Function field_unique_validation() called by wp_ajax hooks: {'nopriv_validation_ajax_action', 'validation_ajax_action'} **/
/** Parameters found in function field_unique_validation(): {"post": ["token", "id"]} **/
function field_unique_validation() {
		$token   = isset( $_POST['token'] ) ? sanitize_text_field( wp_unslash( $_POST['token'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- HMAC token verification replaces nonce.
		$form_id = isset( $_POST['id'] ) ? absint( wp_unslash( $_POST['id'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing

		if ( ! Submit_Token::verify( $token, $form_id ) ) {
			wp_send_json_error( [ 'error' => __( 'Security verification failed. Please refresh the page and try again.', 'sureforms' ) ] );
		}

		if ( ! $form_id ) {
			wp_send_json_error( [ 'error' => __( 'Invalid form ID.', 'sureforms' ) ] );
		}

		// Validate the form exists and is published to prevent cross-form probing.
		if ( 'publish' !== get_post_status( $form_id ) || 'sureforms_form' !== get_post_type( $form_id ) ) {
			wp_send_json_error( [ 'error' => __( 'Invalid form.', 'sureforms' ) ] );
		}

		// Rate limit: 10 requests per minute per IP per form.
		if ( $this->is_unique_validation_rate_limited( $form_id ) ) {
			wp_send_json_error( [ 'error' => __( 'Too many requests. Please try again shortly.', 'sureforms' ) ], 429 );
		}

		// Extract and validate field values from POST data.
		$skip_keys  = [ 'action', 'token', 'id' ];
		$duplicates = [];

		foreach ( $_POST as $raw_key => $raw_value ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- HMAC token verified above.
			if ( in_array( $raw_key, $skip_keys, true ) ) {
				continue;
			}

			$field_key = str_replace( '_', ' ', sanitize_text_field( $raw_key ) );
			$value     = sanitize_text_field( wp_unslash( $raw_value ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- HMAC token verified above.

			// Only process SureForms field keys (they contain -lbl- in the name).
			if ( false === strpos( $field_key, '-lbl-' ) ) {
				continue;
			}

			if ( '' === $value ) {
				continue;
			}

			// Single optimized query per field instead of loading all entries.
			if ( Entries::has_duplicate_field_value( $form_id, $field_key, $value ) ) {
				$duplicates[] = [ $field_key => 'not unique' ];
			}
		}

		wp_send_json( [ 'data' => $duplicates ] );
	}


/** Function handle_dismiss() called by wp_ajax hooks: {'srfm_editor_nudge_dismiss'} **/
/** Parameters found in function handle_dismiss(): {"post": ["post_id"]} **/
function handle_dismiss() {
		if ( ! Helper::current_user_can( 'manage_options' ) ) {
			wp_send_json_error(
				[ 'message' => __( 'You are not allowed to perform this action.', 'sureforms' ) ],
				403
			);
		}

		if ( ! check_ajax_referer( self::NONCE_ACTION, 'nonce', false ) ) {
			wp_send_json_error(
				[ 'message' => __( 'Invalid security token.', 'sureforms' ) ],
				400
			);
		}

		$post_id = isset( $_POST['post_id'] )
			? Helper::get_integer_value( sanitize_text_field( wp_unslash( $_POST['post_id'] ) ) )
			: 0;

		$post = $post_id > 0 ? get_post( $post_id ) : null;
		if ( ! $post ) {
			wp_send_json_error(
				[ 'message' => __( 'Invalid post.', 'sureforms' ) ],
				400
			);
		}

		// The nudge never surfaces on the SureForms form CPT, so dismissals
		// for it are never legitimate — reject before touching post meta.
		if ( SRFM_FORMS_POST_TYPE === $post->post_type ) {
			wp_send_json_error(
				[ 'message' => __( 'Invalid post.', 'sureforms' ) ],
				400
			);
		}

		// Granular per-post authorization — `manage_options` alone is not
		// enough; the requesting user must also be able to edit THIS post.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			wp_send_json_error(
				[ 'message' => __( 'You cannot edit this post.', 'sureforms' ) ],
				403
			);
		}

		update_post_meta( $post_id, self::DISMISS_META_KEY, time() );

		wp_send_json_success();
	}


/** Function ajax_refund_payment() called by wp_ajax hooks: {'srfm_refund_payment'} **/
/** Parameters found in function ajax_refund_payment(): {"post": ["nonce", "payment_id", "transaction_id", "refund_amount", "refund_notes"]} **/
function ajax_refund_payment() {
		// Verify nonce for security.
		if (
			! wp_verify_nonce(
				sanitize_text_field( wp_unslash( $_POST['nonce'] ?? '' ) ),
				'srfm_payment_admin_nonce'
			)
		) {
			wp_send_json_error( __( 'Invalid nonce.', 'sureforms' ) );
		}

		// Check if user has permission to refund payments.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'Insufficient permissions.', 'sureforms' ) );
		}

		// Get and validate input parameters.
		$payment_id     = isset( $_POST['payment_id'] ) ? absint( $_POST['payment_id'] ) : 0;
		$transaction_id = isset( $_POST['transaction_id'] ) ? sanitize_text_field( wp_unslash( $_POST['transaction_id'] ) ) : '';
		$refund_amount  = isset( $_POST['refund_amount'] ) ? absint( $_POST['refund_amount'] ) : 0;
		$refund_notes   = isset( $_POST['refund_notes'] ) ? sanitize_textarea_field( wp_unslash( $_POST['refund_notes'] ) ) : '';

		// Validate required parameters.
		if ( empty( $payment_id ) || empty( $transaction_id ) || $refund_amount <= 0 ) {
			wp_send_json_error( __( 'Invalid payment data.', 'sureforms' ) );
		}

		try {
			// Get payment from database.
			$payment = Payments::get( $payment_id );
			if ( ! $payment ) {
				wp_send_json_error( __( 'Payment not found.', 'sureforms' ) );
			}

			// Get payment gateway (stripe, paypal, etc.).
			$gateway = isset( $payment['gateway'] ) && is_string( $payment['gateway'] ) ? $payment['gateway'] : '';

			if ( empty( $gateway ) ) {
				wp_send_json_error( __( 'Payment gateway not found.', 'sureforms' ) );
			}

			/**
			 * Filter: srfm_process_transaction_refund
			 *
			 * Allows payment gateways to process refunds for their transactions.
			 * Gateway handlers should hook into this filter and check if the gateway matches theirs.
			 *
			 * @since 2.0.0
			 *
			 * @param array<string,mixed> $refund_result {
			 *     Refund result array. Gateway handlers should return success/error status.
			 *
			 *     @type bool   $success Whether the refund was successful.
			 *     @type string $message Success or error message.
			 *     @type array  $data    Optional. Additional data like refund_id, status, etc.
			 * }
			 * @param array<string,mixed> $refund_args {
			 *     Arguments passed to gateway refund handlers.
			 *
			 *     @type array  $payment        Full payment record from database.
			 *     @type int    $payment_id     Payment record ID.
			 *     @type string $transaction_id Transaction/charge ID from gateway.
			 *     @type int    $refund_amount  Refund amount in smallest currency unit (cents for USD).
			 *     @type string $refund_notes   Optional refund notes/reason.
			 *     @type string $gateway        Payment gateway identifier (stripe, paypal, etc.).
			 * }
			 */
			$refund_result = apply_filters(
				'srfm_process_transaction_refund',
				[
					'success' => false,
					'message' => sprintf(
						/* translators: %s: payment gateway name */
						__( 'Refund processing is not supported for %s gateway.', 'sureforms' ),
						ucfirst( $gateway )
					),
					'data'    => [],
				],
				[
					'payment'        => $payment,
					'payment_id'     => $payment_id,
					'transaction_id' => $transaction_id,
					'refund_amount'  => $refund_amount,
					'refund_notes'   => $refund_notes,
					'gateway'        => $gateway,
				]
			);

			// Check if refund was successful.
			if ( ! empty( $refund_result['success'] ) && true === $refund_result['success'] ) {
				$result_data = isset( $refund_result['data'] ) && is_array( $refund_result['data'] ) ? $refund_result['data'] : [];
				wp_send_json_success(
					[
						'message'   => ! empty( $refund_result['message'] ) ? $refund_result['message'] : __( 'Payment refunded successfully.', 'sureforms' ),
						'refund_id' => ! empty( $result_data['refund_id'] ) ? $result_data['refund_id'] : '',
						'status'    => ! empty( $result_data['status'] ) ? $result_data['status'] : 'processed',
					]
				);
			} else {
				// Refund failed - return error message.
				wp_send_json_error(
					! empty( $refund_result['message'] )
						? $refund_result['message']
						: __( 'Failed to process refund. Please try again.', 'sureforms' )
				);
			}
		} catch ( \Exception $e ) {
			wp_send_json_error( __( 'Unable to process refund. Please try again.', 'sureforms' ) );
		}
	}


/** Function ajax_pause_subscription() called by wp_ajax hooks: {'srfm_stripe_pause_subscription'} **/
/** Parameters found in function ajax_pause_subscription(): {"post": ["payment_id", "nonce"]} **/
function ajax_pause_subscription() {
		// Security checks.
		if ( ! isset( $_POST['payment_id'] ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Missing payment ID.', 'sureforms' ) ] );
		}

		// Verify nonce.
		if (
			! wp_verify_nonce(
				sanitize_text_field( wp_unslash( $_POST['nonce'] ?? '' ) ),
				'srfm_payment_admin_nonce'
			)
		) {
			wp_send_json_error( __( 'Invalid nonce.', 'sureforms' ) );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to perform this action.', 'sureforms' ) ] );
		}

		$payment_id = absint( $_POST['payment_id'] );

		// Get payment record.
		$payment = Payments::get( $payment_id );
		if ( ! $payment ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Payment not found in the database.', 'sureforms' ) ] );
		}

		$this->payment_mode = ! empty( $payment['mode'] ) && is_string( $payment['mode'] ) ? $payment['mode'] : 'test';

		// Validate it's a subscription payment.
		if ( empty( $payment['type'] ) || 'subscription' !== $payment['type'] ) {
			wp_send_json_error( [ 'message' => esc_html__( 'This is not a subscription payment.', 'sureforms' ) ] );
		}

		if ( empty( $payment['subscription_id'] ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Subscription ID not found.', 'sureforms' ) ] );
		}

		// Pause the subscription.
		$pause_result = $this->pause_subscription( $payment['subscription_id'] );
		if ( ! $pause_result ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Subscription pause failed.', 'sureforms' ) ] );
		}

		// Get current logs and add pause log entry.
		$current_logs = Helper::get_array_value( $payment['log'] );

		// Build log messages array.
		$log_messages = [
			sprintf(
				/* translators: %s: Stripe subscription ID */
				__( 'Subscription ID: %s', 'sureforms' ),
				$payment['subscription_id']
			),
			sprintf(
				/* translators: %s: payment gateway name */
				__( 'Payment Gateway: %s', 'sureforms' ),
				'Stripe'
			),
			sprintf(
				/* translators: %s: subscription status */
				__( 'Subscription Status: %s', 'sureforms' ),
				__( 'Paused', 'sureforms' )
			),
			sprintf(
				/* translators: %s: user display name */
				__( 'Paused by: %s', 'sureforms' ),
				wp_get_current_user()->display_name
			),
			__( 'Note: The subscription billing has been paused. No charges will occur until the subscription is resumed.', 'sureforms' ),
		];

		// Create new log entry.
		$new_log        = [
			'title'      => __( 'Subscription Paused', 'sureforms' ),
			'created_at' => current_time( 'mysql' ),
			'messages'   => $log_messages,
		];
		$current_logs[] = $new_log;

		// Update database status to paused with log.
		$updated = Payments::update(
			$payment_id,
			[
				'subscription_status' => 'paused',
				'log'                 => $current_logs,
			]
		);
		if ( ! $updated ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Failed to update subscription status in database.', 'sureforms' ) ] );
		}

		wp_send_json_success( [ 'message' => esc_html__( 'Subscription paused successfully!', 'sureforms' ) ] );
	}


/** Function handle_notice_response() called by wp_ajax hooks: {'srfm_notice_response'} **/
/** Parameters found in function handle_notice_response(): {"post": ["notice_id", "button"]} **/
function handle_notice_response() {
		if ( ! check_ajax_referer( 'srfm_notice_response', 'nonce', false ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid nonce.', 'sureforms' ) ], 403 );
		}

		if ( ! Helper::current_user_can() ) {
			wp_send_json_error( [ 'message' => __( 'Unauthorized user.', 'sureforms' ) ], 403 );
		}

		$notice_id = isset( $_POST['notice_id'] ) ? sanitize_text_field( wp_unslash( $_POST['notice_id'] ) ) : '';
		$button    = isset( $_POST['button'] ) ? sanitize_text_field( wp_unslash( $_POST['button'] ) ) : '';

		$valid = [
			'srfm-getting-started-notice' => [
				'go_to_dashboard' => 'getting_started_notice_cta',
				'maybe_later'     => 'getting_started_notice_snooze',
				'dismissed'       => 'getting_started_notice_dismiss',
			],
			'srfm-plugin-review-notice'   => [
				'rate_sureforms' => 'rating_notice_cta',
				'maybe_later'    => 'rating_notice_snooze',
				'dismissed'      => 'rating_notice_dismiss',
			],
		];

		if ( ! isset( $valid[ $notice_id ][ $button ] ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid parameters.', 'sureforms' ) ], 400 );
		}

		$event_name = $valid[ $notice_id ][ $button ];
		Analytics::events()->track( $event_name, $button );

		wp_send_json_success();
	}


