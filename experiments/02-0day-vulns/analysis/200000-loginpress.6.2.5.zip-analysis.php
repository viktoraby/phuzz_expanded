<?php
/***
*
*Found actions: 4
*Found functions:4
*Extracted functions:4
*Total parameter names extracted: 3
*Overview: {'remote_get_notice_ajax': {'rdn_fetch_notifications'}, 'loginpress_handle_notification_dismiss': {'dismiss_notification'}, 'ajax_dismiss_verification_notice': {'wpb_sdk_dismiss_verification_notice'}, 'ajax_resend_verification_email': {'wpb_sdk_resend_verification_email'}}
*
***/

/** Function remote_get_notice_ajax() called by wp_ajax hooks: {'rdn_fetch_notifications'} **/
/** Parameters found in function remote_get_notice_ajax(): {"post": ["notices"]} **/
function remote_get_notice_ajax() {
			check_ajax_referer( 'rdn_fetch_notifications', 'nonce' );

			// Transient set for 1 week.
			set_transient( 'loginpress_rdn_fetch_notifications', 'rdn_fetch_notifications', 604800 );

			if ( isset( $_POST['notices'] ) ) {
				$notices = sanitize_text_field( wp_unslash( $_POST['notices'] ) );
			} else {
				echo 'No notice ID';
				die();
			}

			if ( ! is_array( $notices ) ) {
				$notices = array( $notices );
			}

			foreach ( $notices as $notice_id ) {

				$notification = $this->get_notification( $notice_id );
				if ( false === $notification ) {
					continue;
				}
				$rn = $this->remote_get_notification( $notification );

				if ( is_wp_error( $rn ) ) {
					echo esc_html( $rn->get_error_message() );
				} else {
					// Intentional JSON output for AJAX response.
					echo wp_json_encode( $rn ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
			}

			die();
		}


/** Function loginpress_handle_notification_dismiss() called by wp_ajax hooks: {'dismiss_notification'} **/
/** No params detected :-/ **/


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


