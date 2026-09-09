<?php
/***
*
*Found actions: 12
*Found functions:12
*Extracted functions:12
*Total parameter names extracted: 4
*Overview: {'disable_notification_ajax': {'themeisle_sdk_dismiss_black_friday_notice'}, 'ajax_handler_install_components': {'wfactory-480-intall-component'}, 'dismiss_promotion': {'tisdk_update_option'}, 'wbcr_dan_ajax_restore_notice': {'wbcr-dan-restore-notice'}, 'dan_ajax_disabled_adminbar_menus': {'wdan-disable-adminbar-menus'}, 'dismiss': {'themeisle_sdk_dismiss_notice'}, 'dismiss_license_notice': {'themeisle_sdk_dismiss_license_notice'}, 'ajax_handler_install_creativemotion_plugins': {'wfactory-480-creativemotion-install-plugin'}, 'ThemeisleSDK\\Modules\\Notification::dismiss': {'themeisle_sdk_dismiss_notice'}, 'wbcr_dan_ajax_hide_notices': {'wbcr-dan-hide-notices'}, 'ajax_handler_prepare_component': {'wfactory-480-prepare-component'}, 'subsribe_widget_ajax_handler': {'wbcr-clearfy-subscribe-for-{$this->plugin->getPluginName()}'}}
*
***/

/** Function disable_notification_ajax() called by wp_ajax hooks: {'themeisle_sdk_dismiss_black_friday_notice'} **/
/** No params detected :-/ **/


/** Function ajax_handler_install_components() called by wp_ajax hooks: {'wfactory-480-intall-component'} **/
/** No params detected :-/ **/


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


/** Function wbcr_dan_ajax_restore_notice() called by wp_ajax hooks: {'wbcr-dan-restore-notice'} **/
/** No params detected :-/ **/


/** Function dan_ajax_disabled_adminbar_menus() called by wp_ajax hooks: {'wdan-disable-adminbar-menus'} **/
/** No params detected :-/ **/


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


/** Function ajax_handler_install_creativemotion_plugins() called by wp_ajax hooks: {'wfactory-480-creativemotion-install-plugin'} **/
/** No params detected :-/ **/


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


/** Function wbcr_dan_ajax_hide_notices() called by wp_ajax hooks: {'wbcr-dan-hide-notices'} **/
/** No params detected :-/ **/


/** Function ajax_handler_prepare_component() called by wp_ajax hooks: {'wfactory-480-prepare-component'} **/
/** No params detected :-/ **/


/** Function subsribe_widget_ajax_handler() called by wp_ajax hooks: {'wbcr-clearfy-subscribe-for-{$this->plugin->getPluginName()}'} **/
/** No params detected :-/ **/


