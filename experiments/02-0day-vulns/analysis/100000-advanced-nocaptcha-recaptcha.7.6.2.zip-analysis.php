<?php
/***
*
*Found actions: 9
*Found functions:7
*Extracted functions:6
*Total parameter names extracted: 4
*Overview: {'c4wp_nocaptcha_plugin_notice_ignore': {'c4wp_nocaptcha_plugin_notice_ignore'}, 'c4wp_ajax_verify': {'nopriv_c4wp_ajax_verify', 'c4wp_ajax_verify'}, 'dismiss_owner_notice': {'c4wp_dismiss_owner_notice'}, 'C4WP_Settings': {'c4wp_nocaptcha_plugin_notice_ignore'}, 'dismiss_update_notice': {'c4wp_dismiss_update_notice'}, 'c4wp_validate_secret_key': {'c4wp_validate_secret_key', 'nopriv_c4wp_validate_secret_key'}, 'c4wp_reset_captcha_config': {'c4wp_reset_captcha_config'}}
*
***/

/** Function c4wp_nocaptcha_plugin_notice_ignore() called by wp_ajax hooks: {'c4wp_nocaptcha_plugin_notice_ignore'} **/
/** No params detected :-/ **/


/** Function c4wp_ajax_verify() called by wp_ajax hooks: {'nopriv_c4wp_ajax_verify', 'c4wp_ajax_verify'} **/
/** Parameters found in function c4wp_ajax_verify(): {"server": ["REMOTE_ADDR"]} **/
function c4wp_ajax_verify() {
			$method     = C4WP_Method_Loader::get_currently_selected_method( true, true );
			$url        = apply_filters( 'c4wp_google_verify_url', sprintf( $method::get_verify_url() ) );
			$remoteip   = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
			$secret_key = trim( C4WP_Functions::c4wp_get_secret_key() );
			$verify     = false;

			// Grab POSTed data.
			$posted   = filter_input_array( INPUT_POST );
			$nonce    = sanitize_text_field( $posted['nonce'] );
			$response = sanitize_text_field( $posted['response'] );

			// Check nonce.
			if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'c4wp_verify_nonce' ) ) {
				wp_send_json_error( esc_html__( 'Nonce Verification Failed.', 'advanced-nocaptcha-recaptcha' ) );
			}

			$verify = self::verify();

			if ( ! $verify ) {
				wp_send_json_error();
			} else {
				
				$token = wp_generate_password( 32, false, false );
				set_transient( 'c4wp_auth_' . $token, true, 300 );
				wp_send_json_success( array( 'token' => $token ) );

			}
		}


/** Function dismiss_owner_notice() called by wp_ajax hooks: {'c4wp_dismiss_owner_notice'} **/
/** Parameters found in function dismiss_owner_notice(): {"post": ["nonce"]} **/
function dismiss_owner_notice() {
		// Grab POSTed data.
		$nonce = isset( $_POST['nonce'] ) ? sanitize_key( wp_unslash( $_POST['nonce'] ) ) : false;
		// Check nonce.
		if ( ! current_user_can( 'manage_options' ) || empty( $nonce ) || ! $nonce || ! wp_verify_nonce( $nonce, C4WP_PREFIX . 'dismiss_owner_notice_nonce' ) ) {
			wp_send_json_error( esc_html__( 'Nonce Verification Failed.', 'advanced-nocaptcha-recaptcha' ) );
		}

		update_site_option( C4WP_PREFIX . 'owner_notice_dismissed', true );

		wp_send_json_success( esc_html__( 'Complete.', 'advanced-nocaptcha-recaptcha' ) );
	}


/** Function C4WP_Settings() called by wp_ajax hooks: {'c4wp_nocaptcha_plugin_notice_ignore'} **/
/** No function found :-/ **/


/** Function dismiss_update_notice() called by wp_ajax hooks: {'c4wp_dismiss_update_notice'} **/
/** Parameters found in function dismiss_update_notice(): {"post": ["nonce"]} **/
function dismiss_update_notice() {
		// Grab POSTed data.
		$nonce = isset( $_POST['nonce'] ) ? sanitize_key( wp_unslash( $_POST['nonce'] ) ) : false;
		// Check nonce.
		if ( ! current_user_can( 'manage_options' ) || empty( $nonce ) || ! $nonce || ! wp_verify_nonce( $nonce, C4WP_PREFIX . 'dismiss_update_notice_nonce' ) ) {
			wp_send_json_error( esc_html__( 'Nonce Verification Failed.', 'advanced-nocaptcha-recaptcha' ) );
		}

		delete_site_option( C4WP_PREFIX . 'update_notice_needed' );

		wp_send_json_success( esc_html__( 'Complete.', 'advanced-nocaptcha-recaptcha' ) );
	}


/** Function c4wp_validate_secret_key() called by wp_ajax hooks: {'c4wp_validate_secret_key', 'nopriv_c4wp_validate_secret_key'} **/
/** Parameters found in function c4wp_validate_secret_key(): {"server": ["REMOTE_ADDR"]} **/
function c4wp_validate_secret_key() {
			$verify = false;

			// Grab POSTed data.
			$posted   = filter_input_array( INPUT_POST );
			$nonce    = sanitize_text_field( $posted['nonce'] );
			$response = sanitize_text_field( $posted['response'] );
			$secret   = sanitize_text_field( $posted['secret'] );
			$method   = sanitize_text_field( $posted['method'] );
			$remoteip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';

			// Check nonce.
			if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'c4wp_validate_secret_key_nonce' ) ) {
				wp_send_json_error( esc_html__( 'Nonce Verification Failed.', 'advanced-nocaptcha-recaptcha' ) );
			}

			$url = '';

			$captcha_methods = array(
				'v2_checkbox',
				'v2_invisible',
				'v3',
			);

			if ( in_array( $method, $captcha_methods, true ) ) {
				$method = 'captcha';
			}

			if ( method_exists( C4WP_Method_Loader::$methods[ $method ], 'get_verify_url' ) ) {
				$url = C4WP_Method_Loader::$methods[ $method ]::get_verify_url();
			}

			// make a POST request to the Google reCAPTCHA Server.
			$request = wp_remote_post(
				$url,
				array(
					'timeout' => 10,
					'body'    => array(
						'secret'   => $secret,
						'response' => $response,
						'remoteip' => $remoteip,
					),
				)
			);

			// get the request response body.
			$request_body = wp_remote_retrieve_body( $request );

			if ( ! $request_body ) {
				$verify = false;
			}

			$result = json_decode( $request_body, true );

			if ( isset( $result['success'] ) && true === $result['success'] ) {
				$verify = true;
			}

			if ( ! $verify ) {
				wp_send_json_error();
			} else {
				wp_send_json_success();
			}
		}


/** Function c4wp_reset_captcha_config() called by wp_ajax hooks: {'c4wp_reset_captcha_config'} **/
/** No params detected :-/ **/


