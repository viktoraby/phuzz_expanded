<?php
/***
*
*Found actions: 19
*Found functions:19
*Extracted functions:19
*Total parameter names extracted: 12
*Overview: {'ajax_get_user_data': {'cookiebot_get_user_data'}, 'ajax_store_scan_details': {'cookiebot_store_scan_details'}, 'ajax_delete_auth_token': {'cookiebot_delete_auth_token'}, 'send_uninstall_survey': {'cb_submit_survey'}, 'ajax_get_auth_token': {'cookiebot_get_auth_token'}, 'ajax_set_gcm_enabled': {'cookiebot_set_gcm_enabled'}, 'ajax_dismiss_banner': {'cookiebot_dismiss_banner'}, 'ajax_store_cbid': {'cookiebot_store_cbid'}, 'ajax_get_scan_details': {'cookiebot_get_scan_details'}, 'ajax_store_onboarding_status': {'cookiebot_store_onboarding_status'}, 'ajax_process_auth_code': {'cookiebot_process_auth_code'}, 'ajax_store_configuration': {'cookiebot_store_configuration'}, 'ajax_update_scan_id': {'cookiebot_update_scan_id'}, 'ajax_install_plugin': {'cookiebot_install_ppg'}, 'ajax_get_cbid': {'cookiebot_get_cbid'}, 'ajax_set_auto_blocking_mode': {'cookiebot_set_auto_blocking_mode'}, 'ajax_post_user_data': {'cookiebot_post_user_data'}, 'ajax_activate_plugin': {'cookiebot_activate_ppg'}, 'ajax_set_banner_enabled': {'cookiebot_set_banner_enabled'}}
*
***/

/** Function ajax_get_user_data() called by wp_ajax hooks: {'cookiebot_get_user_data'} **/
/** No params detected :-/ **/


/** Function ajax_store_scan_details() called by wp_ajax hooks: {'cookiebot_store_scan_details'} **/
/** Parameters found in function ajax_store_scan_details(): {"post": ["scan_id", "scan_status"]} **/
function ajax_store_scan_details() {
		if ( ! check_ajax_referer( 'cookiebot-account', 'nonce', false ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized', 401 );
			return;
		}

		$scan_id     = isset( $_POST['scan_id'] ) ? sanitize_text_field( wp_unslash( $_POST['scan_id'] ) ) : '';
		$scan_status = isset( $_POST['scan_status'] ) ? sanitize_text_field( wp_unslash( $_POST['scan_status'] ) ) : '';

		update_option( 'cookiebot-scan-id', $scan_id );
		update_option( 'cookiebot-scan-status', $scan_status );

		wp_send_json_success( array( 'message' => 'Scan details stored successfully' ) );
	}


/** Function ajax_delete_auth_token() called by wp_ajax hooks: {'cookiebot_delete_auth_token'} **/
/** No params detected :-/ **/


/** Function send_uninstall_survey() called by wp_ajax hooks: {'cb_submit_survey'} **/
/** Parameters found in function send_uninstall_survey(): {"post": ["reason_id", "survey_check", "reason_text", "reason_info", "reason_debug"], "server": ["SERVER_SOFTWARE"]} **/
function send_uninstall_survey() {
		global $wpdb;
		if ( ! check_ajax_referer( 'cookiebot_survey_nonce', 'survey_nonce', false ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( 'Sorry you are not allowed to do this.', 'cookiebot' ), 401 );
		}
		if ( ! isset( $_POST['reason_id'] ) ) {
			wp_send_json_error( esc_html__( 'Please select one option', 'cookiebot' ), 400 );
		}
		$data = array(
			'survey_check'   => isset( $_POST['survey_check'] ) ? sanitize_text_field( wp_unslash( $_POST['survey_check'] ) ) : '',
			'reason_slug'    => sanitize_text_field( wp_unslash( $_POST['reason_id'] ) ),
			'reason_detail'  => ! empty( $_POST['reason_text'] ) ? sanitize_text_field( wp_unslash( $_POST['reason_text'] ) ) : null,
			'comments'       => ! empty( $_POST['reason_info'] ) ? sanitize_text_field( wp_unslash( $_POST['reason_info'] ) ) : null,
			'date'           => gmdate( 'M d, Y h:i:s A' ),
			'server'         => ! empty( $_SERVER['SERVER_SOFTWARE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ) : null,
			'php_version'    => phpversion(),
			'mysql_version'  => $wpdb->db_version(),
			'wp_version'     => get_bloginfo( 'version' ),
			'locale'         => get_locale(),
			'plugin_version' => Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION,
			'is_multisite'   => is_multisite(),
		);

		if ( ! empty( $_POST['reason_debug'] ) && rest_sanitize_boolean( $_POST['reason_debug'] ) === true ) {
			$support            = new Support_Page();
			$data['debug_info'] = wp_json_encode( $support->prepare_debug_data() );
		}

		wp_remote_post(
			$this->api_url,
			array(
				'headers'     => array(
					'Content-Type' => 'application/json; charset=utf-8',
				),
				'method'      => 'POST',
				'timeout'     => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => false,
				'body'        => wp_json_encode( $data ),
				'cookies'     => array(),
			)
		);

		wp_send_json_success( null, 200 );
	}


/** Function ajax_get_auth_token() called by wp_ajax hooks: {'cookiebot_get_auth_token'} **/
/** No params detected :-/ **/


/** Function ajax_set_gcm_enabled() called by wp_ajax hooks: {'cookiebot_set_gcm_enabled'} **/
/** Parameters found in function ajax_set_gcm_enabled(): {"post": ["value"]} **/
function ajax_set_gcm_enabled() {
		if ( ! check_ajax_referer( 'cookiebot-account', 'nonce', false ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized', 401 );
			return;
		}

		$value = isset( $_POST['value'] ) ? sanitize_text_field( wp_unslash( $_POST['value'] ) ) : '';

		// Save option value
		update_option( 'cookiebot-gcm', $value );
		wp_send_json_success();
	}


/** Function ajax_dismiss_banner() called by wp_ajax hooks: {'cookiebot_dismiss_banner'} **/
/** No params detected :-/ **/


/** Function ajax_store_cbid() called by wp_ajax hooks: {'cookiebot_store_cbid'} **/
/** Parameters found in function ajax_store_cbid(): {"post": ["cbid"]} **/
function ajax_store_cbid() {
		if ( ! check_ajax_referer( 'cookiebot-account', 'nonce', false ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized', 401 );
		}

		$cbid = isset( $_POST['cbid'] ) ? sanitize_text_field( wp_unslash( $_POST['cbid'] ) ) : '';

		if ( empty( $cbid ) ) {
			wp_send_json_error( 'CBID is required', 400 );
		}

		// Store with consistent name
		update_option( 'cookiebot-cbid', $cbid );

		wp_send_json_success();
	}


/** Function ajax_get_scan_details() called by wp_ajax hooks: {'cookiebot_get_scan_details'} **/
/** No params detected :-/ **/


/** Function ajax_store_onboarding_status() called by wp_ajax hooks: {'cookiebot_store_onboarding_status'} **/
/** Parameters found in function ajax_store_onboarding_status(): {"post": ["onboarded"]} **/
function ajax_store_onboarding_status() {
		if ( ! check_ajax_referer( 'cookiebot-account', 'nonce', false ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized', 401 );
			return;
		}

		$onboarded = isset( $_POST['onboarded'] ) ? (bool) $_POST['onboarded'] : false;
		update_option( 'cookiebot-uc-onboarded-via-signup', $onboarded );

		wp_send_json_success( array( 'message' => 'Onboarding status stored successfully' ) );
	}


/** Function ajax_process_auth_code() called by wp_ajax hooks: {'cookiebot_process_auth_code'} **/
/** Parameters found in function ajax_process_auth_code(): {"post": ["code"]} **/
function ajax_process_auth_code() {
		if ( ! check_ajax_referer( 'cookiebot-account', 'nonce', false ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized', 401 );
			return;
		}

		$code = isset( $_POST['code'] ) ? sanitize_text_field( wp_unslash( $_POST['code'] ) ) : '';

		if ( empty( $code ) ) {
			wp_send_json_error( 'No code provided', 400 );
			return;
		}

		// Use POST request with code as query parameter
		// phpcs:ignore
		$api_url = 'https://api.ea.prod.usercentrics.cloud/v1/auth/auth0/exchange?code=' . urlencode( $code );

		$response = wp_remote_post(
			$api_url,
			array(
				'timeout' => 45,
				'headers' => array(
					'Content-Type' => 'application/json',
					'Accept'       => 'application/json',
				),
				'body'    => '',
			)
		);

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			wp_send_json_error( 'Error: ' . $error_message, 500 );
			return;
		}

		$status = wp_remote_retrieve_response_code( $response );
		$body   = wp_remote_retrieve_body( $response );

		$data  = json_decode( $body, true );
		$token = $data['token'];

		update_option( 'cookiebot-auth-token', $token );
	}


/** Function ajax_store_configuration() called by wp_ajax hooks: {'cookiebot_store_configuration'} **/
/** Parameters found in function ajax_store_configuration(): {"post": ["configuration"]} **/
function ajax_store_configuration() {
		if ( ! check_ajax_referer( 'cookiebot-account', 'nonce', false ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized', 401 );
			return;
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- JSON payload validated by json_decode() below.
		$configuration = isset( $_POST['configuration'] ) ? wp_unslash( $_POST['configuration'] ) : '';

		if ( empty( $configuration ) ) {
			wp_send_json_error( 'Configuration data is required', 400 );
			return;
		}

		$data = json_decode( $configuration, true );
		if ( json_last_error() !== JSON_ERROR_NONE ) {
			wp_send_json_error( 'Invalid configuration data format', 400 );
			return;
		}

		update_option( 'cookiebot-configuration', $data );
		wp_send_json_success( array( 'message' => 'Configuration stored successfully' ) );
	}


/** Function ajax_update_scan_id() called by wp_ajax hooks: {'cookiebot_update_scan_id'} **/
/** Parameters found in function ajax_update_scan_id(): {"post": ["scan_id"]} **/
function ajax_update_scan_id() {
		if ( ! check_ajax_referer( 'cookiebot-account', 'nonce', false ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized', 401 );
		}

		$scan_id = isset( $_POST['scan_id'] ) ? sanitize_text_field( wp_unslash( $_POST['scan_id'] ) ) : '';

		if ( empty( $scan_id ) ) {
			wp_send_json_error( 'Scan ID is required', 400 );
		}

		update_option( 'cookiebot-scan-id', $scan_id );
		wp_send_json_success();
	}


/** Function ajax_install_plugin() called by wp_ajax hooks: {'cookiebot_install_ppg'} **/
/** No params detected :-/ **/


/** Function ajax_get_cbid() called by wp_ajax hooks: {'cookiebot_get_cbid'} **/
/** No params detected :-/ **/


/** Function ajax_set_auto_blocking_mode() called by wp_ajax hooks: {'cookiebot_set_auto_blocking_mode'} **/
/** Parameters found in function ajax_set_auto_blocking_mode(): {"post": ["value"]} **/
function ajax_set_auto_blocking_mode() {
		if ( ! check_ajax_referer( 'cookiebot-account', 'nonce', false ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized', 401 );
			return;
		}

		$value = isset( $_POST['value'] ) ? sanitize_text_field( wp_unslash( $_POST['value'] ) ) : '';

		// Save option value
		update_option( 'cookiebot-uc-auto-blocking-mode', $value );
		wp_send_json_success();
	}


/** Function ajax_post_user_data() called by wp_ajax hooks: {'cookiebot_post_user_data'} **/
/** Parameters found in function ajax_post_user_data(): {"post": ["data"]} **/
function ajax_post_user_data() {
		if ( ! check_ajax_referer( 'cookiebot-account', 'nonce', false ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized', 401 );
			return;
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- JSON payload validated by json_decode() below.
		$raw_data = isset( $_POST['data'] ) ? wp_unslash( $_POST['data'] ) : '';

		if ( empty( $raw_data ) ) {
			wp_send_json_error( 'No data provided', 400 );
			return;
		}

		$data = json_decode( $raw_data, true );
		if ( json_last_error() !== JSON_ERROR_NONE ) {
			wp_send_json_error( 'Invalid user data format', 400 );
			return;
		}
		update_option( 'cookiebot-user-data', $data );

		wp_send_json_success( array( 'message' => 'User data stored successfully' ) );
	}


/** Function ajax_activate_plugin() called by wp_ajax hooks: {'cookiebot_activate_ppg'} **/
/** No params detected :-/ **/


/** Function ajax_set_banner_enabled() called by wp_ajax hooks: {'cookiebot_set_banner_enabled'} **/
/** Parameters found in function ajax_set_banner_enabled(): {"post": ["value"]} **/
function ajax_set_banner_enabled() {
		if ( ! check_ajax_referer( 'cookiebot-account', 'nonce', false ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized', 401 );
			return;
		}

		$value = isset( $_POST['value'] ) ? sanitize_text_field( wp_unslash( $_POST['value'] ) ) : '';

		// Save option value
		update_option( 'cookiebot-banner-enabled', $value );
		wp_send_json_success();
	}


