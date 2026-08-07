<?php
/***
*
*Found actions: 3
*Found functions:3
*Extracted functions:3
*Total parameter names extracted: 2
*Overview: {'wp_headers_and_footers_log_download': {'wpheadersandfooters_log_download'}, 'ajax_resend_verification_email': {'wpb_sdk_resend_verification_email'}, 'ajax_dismiss_verification_notice': {'wpb_sdk_dismiss_verification_notice'}}
*
***/

/** Function wp_headers_and_footers_log_download() called by wp_ajax hooks: {'wpheadersandfooters_log_download'} **/
/** No params detected :-/ **/


/** Function ajax_resend_verification_email() called by wp_ajax hooks: {'wpb_sdk_resend_verification_email'} **/
/** Parameters found in function ajax_resend_verification_email(): {"post": ["slug"]} **/
function ajax_resend_verification_email() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error(
				array( 'message' => __( 'You do not have permission to perform this action.', 'wpbrigade-sdk' ) ),
				403
			);
		}

		check_ajax_referer( 'wpb_sdk_resend_verification_email', 'nonce' );

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verified above.
		$slug = isset( $_POST['slug'] ) ? sanitize_key( wp_unslash( (string) $_POST['slug'] ) ) : '';
		if ( '' === $slug ) {
			wp_send_json_error( array( 'message' => __( 'Invalid product.', 'wpbrigade-sdk' ) ), 400 );
		}

		$verify_user_id = function_exists( 'wpb_sdk_resolve_optin_verification_user_id' )
			? (int) wpb_sdk_resolve_optin_verification_user_id( $slug )
			: 0;
		if ( $verify_user_id > 0 && (int) get_current_user_id() !== $verify_user_id ) {
			wp_die();
		}

		if (
			! function_exists( 'wpb_sdk_dispatch_verification_email' )
			|| ! wpb_sdk_dispatch_verification_email( $slug, 0, true )
		) {
			wp_send_json_error(
				array(
					'message' => __( 'Please wait a moment before requesting another verification email.', 'wpbrigade-sdk' ),
				),
				429
			);
		}

		if ( function_exists( 'wpb_sdk_clear_verification_notice_dismissed' ) ) {
			wpb_sdk_clear_verification_notice_dismissed( $slug, (int) get_current_user_id() );
		}

		$email = function_exists( 'wpb_sdk_get_optin_verification_user_email' )
			? wpb_sdk_get_optin_verification_user_email( $slug )
			: '';
		$copy  = self::verification_notice_copy( $slug, $email, true );

		wp_send_json_success(
			array(
				'message'        => __( 'Verification email sent. Please check your inbox.', 'wpbrigade-sdk' ),
				'notice_message' => $copy['message'],
				'show_button'    => $copy['show_button'],
			)
		);
	}


/** Function ajax_dismiss_verification_notice() called by wp_ajax hooks: {'wpb_sdk_dismiss_verification_notice'} **/
/** Parameters found in function ajax_dismiss_verification_notice(): {"post": ["slug"]} **/
function ajax_dismiss_verification_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( -1, '', array( 'response' => 403 ) );
		}

		check_ajax_referer( 'wpb_sdk_dismiss_verification_notice', 'nonce' );

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verified above.
		$slug = isset( $_POST['slug'] ) ? sanitize_key( wp_unslash( (string) $_POST['slug'] ) ) : '';
		if ( '' === $slug ) {
			wp_die( -1 );
		}

		$user_id = (int) get_current_user_id();
		if ( $user_id < 1 ) {
			wp_die( -1 );
		}

		if ( function_exists( 'wpb_sdk_dismiss_verification_notice' ) ) {
			wpb_sdk_dismiss_verification_notice( $slug, $user_id );
		}
		wp_die();
	}


