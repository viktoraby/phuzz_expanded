<?php
/***
*
*Found actions: 3
*Found functions:3
*Extracted functions:3
*Total parameter names extracted: 2
*Overview: {'dismiss_pointers': {'ea11y_pointer_dismissed'}, 'handle_deactivation_feedback': {'ea11y_deactivation_feedback'}, 'ajax_a11y_install_elementor_set_admin_notice_viewed': {'a11y_install_elementor_set_admin_notice_viewed'}}
*
***/

/** Function dismiss_pointers() called by wp_ajax hooks: {'ea11y_pointer_dismissed'} **/
/** Parameters found in function dismiss_pointers(): {"post": ["nonce", "data"]} **/
function dismiss_pointers() {
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ea11y-pointer-dismissed' ) ) {
			wp_send_json_error( [ 'message' => 'Invalid nonce' ] );
		}

		$pointer = sanitize_text_field( $_POST['data']['pointer'] ) ?? null;

		if ( empty( $pointer ) ) {
			wp_send_json_error( [ 'message' => 'The pointer id must be provided' ] );
		}

		$pointer = explode( ',', $pointer );

		$user_dismissed_meta = get_user_meta( get_current_user_id(), self::DISMISSED_POINTERS_META_KEY, true );

		if ( ! $user_dismissed_meta ) {
			$user_dismissed_meta = [];
		}

		foreach ( $pointer as $item ) {
			$user_dismissed_meta[ $item ] = true;
		}

		update_user_meta( get_current_user_id(), self::DISMISSED_POINTERS_META_KEY, $user_dismissed_meta );

		wp_send_json_success( [] );
	}


/** Function handle_deactivation_feedback() called by wp_ajax hooks: {'ea11y_deactivation_feedback'} **/
/** Parameters found in function handle_deactivation_feedback(): {"post": ["nonce", "reason", "additional_data"]} **/
function handle_deactivation_feedback(): void {
		// Verify nonce
		$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ?? '' ) );

		if ( ! wp_verify_nonce( $nonce, 'ea11y_deactivation_feedback' ) ) {
			wp_send_json_error( esc_html__( 'Security check failed', 'pojo-accessibility' ) );
			return;
		}

		// Check user capabilities
		if ( ! Utils::user_is_admin() ) {
			wp_send_json_error( esc_html__( 'Insufficient permissions', 'pojo-accessibility' ) );
			return;
		}

		$reason = sanitize_text_field( wp_unslash( $_POST['reason'] ?? '' ) );
		$additional_data = '';

		// Safely handle additional_data if it exists
		if ( isset( $_POST['additional_data'] ) ) {
			$additional_data = sanitize_textarea_field( wp_unslash( $_POST['additional_data'] ) );
		}

		if ( empty( $reason ) ) {
			wp_send_json_success( [ 'message' => 'No reason provided' ] );
			return;
		}

		// Send feedback to external service
		$feedback_sent = $this->send_feedback_to_service( $reason, $additional_data );

		if ( $feedback_sent ) {
			wp_send_json_success( [ 'message' => 'Feedback sent successfully' ] );
		} else {
			// Still return success to not block deactivation, but log the error
			Logger::error( 'Failed to send deactivation feedback to service' );
			wp_send_json_success( [ 'message' => 'Feedback logged locally' ] );
		}
	}


/** Function ajax_a11y_install_elementor_set_admin_notice_viewed() called by wp_ajax hooks: {'a11y_install_elementor_set_admin_notice_viewed'} **/
/** No params detected :-/ **/


