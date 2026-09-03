<?php
/***
*
*Found actions: 9
*Found functions:8
*Extracted functions:8
*Total parameter names extracted: 8
*Overview: {'disable_notification_ajax': {'dismiss_themeisle_event_notice_otter', 'themeisle_sdk_dismiss_black_friday_notice'}, 'dismiss_promotion': {'tisdk_update_option'}, 'ThemeisleSDK\\Modules\\Notification::dismiss': {'themeisle_sdk_dismiss_notice'}, 'dismiss_dashboard_notice': {'dismiss_otter_notice'}, 'dismiss': {'themeisle_sdk_dismiss_notice'}, 'remove_welcome_notice': {'otter_animation_dismiss_welcome_notice'}, 'export_submissions': {'otter_form_submissions'}, 'dismiss_license_notice': {'themeisle_sdk_dismiss_license_notice'}}
*
***/

/** Function disable_notification_ajax() called by wp_ajax hooks: {'dismiss_themeisle_event_notice_otter', 'themeisle_sdk_dismiss_black_friday_notice'} **/
/** Parameters found in function disable_notification_ajax(): {"post": ["nonce"]} **/
function disable_notification_ajax() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['nonce'] ) ), 'dismiss_themeisle_event_notice_otter' ) ) {
			wp_die( esc_html( __( 'Invalid nonce! Refresh the page and try again.', 'otter-blocks' ) ) );
		}

		// We record the time and the plugin of the dismissed notification.
		update_option( $this->wp_option_dismiss_notification_key_base . $this->active, 'otter_' . $this->active . '_' . current_time( 'Y_m_d' ) );
		wp_die( 'success' );
	}


/** Function dismiss_promotion() called by wp_ajax hooks: {'tisdk_update_option'} **/
/** Parameters found in function dismiss_promotion(): {"post": ["nonce", "value"]} **/
function dismiss_promotion() {
		if ( ! isset( $_POST['nonce'] ) || ! isset( $_POST['value'] ) ) {
			$response = array(
				'success' => false,
				'message' => 'Missing nonce or value.',
			);
			wp_send_json( $response );
			wp_die();
		}

		$nonce = sanitize_text_field( $_POST['nonce'] );
		$value = sanitize_text_field( $_POST['value'] );

		if ( ! wp_verify_nonce( $nonce, 'tisdk_update_option' ) ) {
			$response = array(
				'success' => false,
				'message' => 'Invalid nonce.',
			);
			wp_send_json( $response );
			wp_die();
		}

		$options = get_option( $this->option_main );
		$options = json_decode( $options, true );

		$options[ $value ] = time();

		update_option( $this->option_main, wp_json_encode( $options ) );

		$response = array(
			'success' => true,
		);

		wp_send_json( $response );
		wp_die();
	}


/** Function ThemeisleSDK\Modules\Notification::dismiss() called by wp_ajax hooks: {'themeisle_sdk_dismiss_notice'} **/
/** Parameters found in function ThemeisleSDK\Modules\Notification::dismiss(): {"post": ["id", "confirm"]} **/
function dismiss() {
		check_ajax_referer( (string) __CLASS__, 'nonce' );

		$id      = isset( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : '';
		$confirm = isset( $_POST['confirm'] ) ? sanitize_text_field( $_POST['confirm'] ) : 'no';

		if ( empty( $id ) ) {
			wp_send_json( [] );
		}
		self::setup_notifications();
		$ids = wp_list_pluck( self::$notifications, 'id' );
		if ( ! in_array( $id, $ids, true ) ) {
			wp_send_json( [] );
		}
		self::set_last_active_notification_timestamp();
		update_option( $id, $confirm );
		do_action( $id . '_process_confirm', $confirm );
		wp_send_json( [] );
	}


/** Function dismiss_dashboard_notice() called by wp_ajax hooks: {'dismiss_otter_notice'} **/
/** Parameters found in function dismiss_dashboard_notice(): {"post": ["nonce"]} **/
function dismiss_dashboard_notice() {
		if ( ! isset( $_POST['nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'dismiss_otter_notice' ) ) {
			return;
		}

		$notifications                     = get_option( 'themeisle_blocks_settings_notifications', array() );
		$notifications['dashboard_upsell'] = true;
		update_option( 'themeisle_blocks_settings_notifications', $notifications );
		wp_die();
	}


/** Function dismiss() called by wp_ajax hooks: {'themeisle_sdk_dismiss_notice'} **/
/** Parameters found in function dismiss(): {"post": ["id", "confirm"]} **/
function dismiss() {
		check_ajax_referer( (string) __CLASS__, 'nonce' );

		$id      = isset( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : '';
		$confirm = isset( $_POST['confirm'] ) ? sanitize_text_field( $_POST['confirm'] ) : 'no';

		if ( empty( $id ) ) {
			wp_send_json( [] );
		}
		self::setup_notifications();
		$ids = wp_list_pluck( self::$notifications, 'id' );
		if ( ! in_array( $id, $ids, true ) ) {
			wp_send_json( [] );
		}
		self::set_last_active_notification_timestamp();
		update_option( $id, $confirm );
		do_action( $id . '_process_confirm', $confirm );
		wp_send_json( [] );
	}


/** Function remove_welcome_notice() called by wp_ajax hooks: {'otter_animation_dismiss_welcome_notice'} **/
/** Parameters found in function remove_welcome_notice(): {"post": ["nonce"]} **/
function remove_welcome_notice() {
		if ( ! isset( $_POST['nonce'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'otter_animation_dismiss_welcome_notice' ) ) {
			return;
		}
		update_option( 'otter_animation_dismiss_welcome_notice', true );
		wp_die();
	}


/** Function export_submissions() called by wp_ajax hooks: {'otter_form_submissions'} **/
/** Parameters found in function export_submissions(): {"post": ["_nonce"]} **/
function export_submissions() {
		if ( ! Pro::is_pro_active() ) {
			wp_die( esc_html( __( 'Exporting submissions requires Otter Pro.', 'otter-blocks' ) ) );
		}

		$nonce = isset( $_POST['_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_nonce'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! wp_verify_nonce( $nonce, 'otter_form_export_submissions' ) ) {
			wp_die( esc_html( __( 'Invalid nonce.', 'otter-blocks' ) ) );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html( __( 'You are not allowed to export submissions.', 'otter-blocks' ) ) );
		}

		// Export submissions.
		require_once ABSPATH . 'wp-admin/includes/export.php';
		ob_start();
		export_wp( array( 'content' => Form_Submissions::FORM_RECORD_TYPE ) );
		$export = ob_get_clean();

		echo ent2ncr( $export ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		wp_die();
	}


/** Function dismiss_license_notice() called by wp_ajax hooks: {'themeisle_sdk_dismiss_license_notice'} **/
/** Parameters found in function dismiss_license_notice(): {"post": ["nonce", "notice_id"]} **/
function dismiss_license_notice() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'themeisle_sdk_dismiss_license_notice' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Insufficient permissions' );
		}

		$notice_id = isset( $_POST['notice_id'] ) ? sanitize_text_field( $_POST['notice_id'] ) : '';

		if ( empty( $notice_id ) ) {
			wp_send_json_error( 'Missing notice ID' );
		}

		// Save the dismissal option.
		$dismiss_option_key = $notice_id . '_dismissed';
		update_option( $dismiss_option_key, true );

		wp_send_json_success();
	}


