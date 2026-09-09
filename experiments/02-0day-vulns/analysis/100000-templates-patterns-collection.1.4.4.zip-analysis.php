<?php
/***
*
*Found actions: 11
*Found functions:9
*Extracted functions:9
*Total parameter names extracted: 8
*Overview: {'mark_onboarding_done': {'mark_onboarding_done', 'nopriv_mark_onboarding_done'}, 'dismiss_promotion': {'tisdk_update_option'}, 'external_get_logs': {'tpc_get_logs'}, 'dismiss_license_notice': {'themeisle_sdk_dismiss_license_notice'}, 'dismiss': {'themeisle_sdk_dismiss_notice'}, 'skip_subscribe': {'nopriv_skip_subscribe', 'skip_subscribe'}, 'ThemeisleSDK\\Modules\\Notification::dismiss': {'themeisle_sdk_dismiss_notice'}, 'disable_notification_ajax': {'themeisle_sdk_dismiss_black_friday_notice'}, 'dismiss_new_tc_notice': {'dismiss_new_tc_notice'}}
*
***/

/** Function mark_onboarding_done() called by wp_ajax hooks: {'mark_onboarding_done', 'nopriv_mark_onboarding_done'} **/
/** Parameters found in function mark_onboarding_done(): {"request": ["nonce"]} **/
function mark_onboarding_done() {
		$response = array(
			'success' => false,
			'code'    => 'ti__ob_not_allowed',
			'message' => 'Not allowed!',
		);
		if ( ! isset( $_REQUEST['nonce'] ) ) {
			$this->ensure_ajax_response( $response );
			return;
		}

		if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'onboarding_done_nonce' ) ) {
			$this->ensure_ajax_response( $response );
			return;
		}

		unset( $response['code'] );
		unset( $response['message'] );

		update_option( 'tpc_onboarding_done', 'yes' );
		$this->ensure_ajax_response( $response );
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


/** Function external_get_logs() called by wp_ajax hooks: {'tpc_get_logs'} **/
/** Parameters found in function external_get_logs(): {"post": ["nonce"]} **/
function external_get_logs() {

		$nonce = $_POST['nonce'];

		if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
			wp_die( __( 'Nonce verification failed', 'templates-patterns-collection' ) );
		}

		$data = get_transient( Logger::$log_transient_name );

		if ( ! empty( $data ) ) {
			echo $data;
			wp_die();
		}

		wp_die( __( 'No logs found', 'templates-patterns-collection' ) );
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


/** Function skip_subscribe() called by wp_ajax hooks: {'nopriv_skip_subscribe', 'skip_subscribe'} **/
/** Parameters found in function skip_subscribe(): {"request": ["nonce", "isTempSkip"]} **/
function skip_subscribe() {
		$response = array(
			'success' => false,
			'code'    => 'ti__ob_not_allowed',
			'message' => 'Not allowed!',
		);
		if ( ! isset( $_REQUEST['nonce'] ) ) {
			$this->ensure_ajax_response( $response );
			return;
		}

		if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'skip_subscribe_nonce' ) ) {
			$this->ensure_ajax_response( $response );
			return;
		}

		unset( $response['code'] );
		unset( $response['message'] );
		$response['success'] = true;
		if ( isset( $_REQUEST['isTempSkip'] ) ) {
			set_transient( $this->skip_email_subscribe_namespace, 'yes', 7 * DAY_IN_SECONDS );
			$this->ensure_ajax_response( $response );
			return;
		}

		update_option( $this->skip_email_subscribe_namespace, 'yes' );
		$this->ensure_ajax_response( $response );
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


/** Function disable_notification_ajax() called by wp_ajax hooks: {'themeisle_sdk_dismiss_black_friday_notice'} **/
/** No params detected :-/ **/


/** Function dismiss_new_tc_notice() called by wp_ajax hooks: {'dismiss_new_tc_notice'} **/
/** Parameters found in function dismiss_new_tc_notice(): {"request": ["nonce"]} **/
function dismiss_new_tc_notice() {
		$response = array(
			'success' => false,
			'code'    => 'ti__ob_not_allowed',
			'message' => 'Not allowed!',
		);

		if ( ! isset( $_REQUEST['nonce'] ) ) {
			$this->ensure_ajax_response( $response );
			return;
		}

		if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'dismiss_new_tc_notice' ) ) {
			$this->ensure_ajax_response( $response );
			return;
		}

		unset( $response['code'] );
		unset( $response['message'] );

		update_option( self::TC_NEW_NOTICE_DISMISSED, 'yes' );
		$this->ensure_ajax_response( $response );
	}


