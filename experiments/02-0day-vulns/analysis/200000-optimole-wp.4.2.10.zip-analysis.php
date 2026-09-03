<?php
/***
*
*Found actions: 8
*Found functions:8
*Extracted functions:8
*Total parameter names extracted: 8
*Overview: {'dismiss_promotion': {'tisdk_update_option'}, 'disable_notification_ajax': {'themeisle_sdk_dismiss_black_friday_notice'}, 'replace_file': {'optml_replace_file'}, 'dismiss_notice': {'optml_dismiss_conflict_notice'}, 'ThemeisleSDK\\Modules\\Notification::dismiss': {'themeisle_sdk_dismiss_notice'}, 'dismiss_license_notice': {'themeisle_sdk_dismiss_license_notice'}, 'dismiss': {'themeisle_sdk_dismiss_notice'}, 'fetch_logs_ajax_handler': {'optml_fetch_logs'}}
*
***/

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


/** Function disable_notification_ajax() called by wp_ajax hooks: {'themeisle_sdk_dismiss_black_friday_notice'} **/
/** No params detected :-/ **/


/** Function replace_file() called by wp_ajax hooks: {'optml_replace_file'} **/
/** Parameters found in function replace_file(): {"post": ["attachment_id"], "files": ["file"]} **/
function replace_file() {
		if ( ! check_ajax_referer( 'optml_replace_media_nonce', 'optml_replace_nonce', false ) ) {
			wp_send_json_error( __( 'Security check failed', 'optimole-wp' ) );
		}

		$id = absint( $_POST['attachment_id'] ?? 0 );

		if ( ! $id ) {
			wp_send_json_error( __( 'Invalid attachment ID', 'optimole-wp' ) );
		}

		if ( get_post_type( $id ) !== 'attachment' ) {
			wp_send_json_error( __( 'Invalid attachment ID', 'optimole-wp' ) );
		}

		if ( ! current_user_can( 'edit_post', $id ) ) {
			wp_send_json_error( __( 'You are not allowed to replace this file', 'optimole-wp' ) );
		}

		if ( ! isset( $_FILES['file'] ) ) {
			wp_send_json_error( __( 'No file uploaded', 'optimole-wp' ) );
		}

		$file_info = wp_check_filetype_and_ext( $_FILES['file']['tmp_name'], $_FILES['file']['name'] );

		if ( empty( $file_info['type'] ) ) {
			wp_send_json_error( __( 'Could not determine uploaded file type', 'optimole-wp' ) );
		}

		$original_mime = get_post_mime_type( $id );
		if ( $file_info['type'] !== $original_mime ) {
			wp_send_json_error( __( 'The uploaded file type does not match the original file type.', 'optimole-wp' ) );
		}

		$replacer = new Optml_Attachment_Replace( $id, $_FILES['file'] );

		$replaced = $replacer->replace();

		$is_error = is_wp_error( $replaced );

		$response = [
			'success' => ! $is_error,
			'message' => $is_error ? $replaced->get_error_message() : __( 'File replaced successfully', 'optimole-wp' ),
		];

		wp_send_json( $response );
	}


/** Function dismiss_notice() called by wp_ajax hooks: {'optml_dismiss_conflict_notice'} **/
/** Parameters found in function dismiss_notice(): {"post": ["nonce"]} **/
function dismiss_notice() {
		if ( ! isset( $_POST['nonce'] ) ) {
			$response = [
				'success' => false,
				'message' => 'Missing nonce or value.',
			];
			wp_send_json( $response );
		}

		$nonce = sanitize_text_field( $_POST['nonce'] );

		if ( ! wp_verify_nonce( $nonce, 'optml_dismiss_conflict_notice' ) ) {
			$response = [
				'success' => false,
				'message' => 'Invalid nonce.',
			];
			wp_send_json( $response );
		}

		$this->dismiss_conflicting_plugins();

		$response = [
			'success' => true,
		];

		wp_send_json( $response );
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


/** Function fetch_logs_ajax_handler() called by wp_ajax hooks: {'optml_fetch_logs'} **/
/** Parameters found in function fetch_logs_ajax_handler(): {"request": ["nonce", "type", "lines"]} **/
function fetch_logs_ajax_handler() {
		// Check for nonce security
		$nonce = $_REQUEST['nonce'];
		if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
			wp_die( __( 'Security check failed', 'optimole-wp' ) );
		}

		// Check for necessary parameters
		if ( ! isset( $_REQUEST['type'] ) || ! in_array( $_REQUEST['type'], array_keys( $this->log_filenames ), true ) ) {
			wp_die( __( 'Invalid log type', 'optimole-wp' ) );
		}

		$lines = 10000;

		if ( isset( $_REQUEST['lines'] ) ) {
			$lines = intval( $_REQUEST['lines'] );
		}

		// Get log type
		$type = $_REQUEST['type'];

		// Fetch the logs
		$logs = $this->get_log( $type, $lines, self::LOG_SEPARATOR );

		// Return the logs
		echo $logs;

		wp_die();
	}


