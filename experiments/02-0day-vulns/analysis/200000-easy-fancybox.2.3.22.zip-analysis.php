<?php
/***
*
*Found actions: 2
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 2
*Overview: {'process_efb_review_action': {'efb-review-action'}, 'process_efb_optin_action': {'efb-optin-action'}}
*
***/

/** Function process_efb_review_action() called by wp_ajax hooks: {'efb-review-action'} **/
/** Parameters found in function process_efb_review_action(): {"post": ["rate_action"]} **/
function process_efb_review_action() {
		check_admin_referer( 'efb_review_action_nonce', '_n' );
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$rate_action            = isset( $_POST['rate_action'] ) ? sanitize_text_field( wp_unslash( $_POST['rate_action'] ) ) : '';
		$current_date           = new DateTimeImmutable( gmdate( 'Y-m-d' ) );
		$current_date_as_string = $current_date->format( 'Y-m-d' );
		update_option( 'efb_last_review_interaction', $current_date_as_string );

		if ( 'done' === $rate_action ) {
			update_option( 'efb_plugin_rated', 'true' );
		}

		exit;
	}


/** Function process_efb_optin_action() called by wp_ajax hooks: {'efb-optin-action'} **/
/** Parameters found in function process_efb_optin_action(): {"post": ["optin_action"]} **/
function process_efb_optin_action() {
		check_admin_referer( 'efb_optin_action_nonce', '_n' );
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$optin_action           = isset( $_POST['optin_action'] )
			? sanitize_text_field( wp_unslash( $_POST['optin_action'] ) )
			: '';
		$current_date           = new DateTimeImmutable( gmdate( 'Y-m-d' ) );
		$current_date_as_string = $current_date->format( 'Y-m-d' );

		update_option( 'efb_last_optin_interaction', $current_date_as_string );

		if ( 'do-optin' === $optin_action ) {
			update_option( 'efb_opted_in', 'true' );
			$current_user = wp_get_current_user();
			$first        = esc_html( $current_user->user_firstname );
			$last         = esc_html( $current_user->user_lastname );
			$email        = esc_html( $current_user->user_email );

			$url = add_query_arg(
				array(
					'first' => $first,
					'last'  => $last,
					'email' => $email,
				),
				'https://h2776ox0tf.execute-api.us-east-1.amazonaws.com/EasyFancyboxMailchimpAPI/'
			);

			$response = wp_remote_post( $url, array( 'method' => 'GET' ) );

			wp_send_json_success(
				array(
					'response' => $response['body'],
					'email'    => $email,
				)
			);
		}

		exit;
	}


