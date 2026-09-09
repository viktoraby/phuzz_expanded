<?php
/***
*
*Found actions: 11
*Found functions:10
*Extracted functions:7
*Total parameter names extracted: 4
*Overview: {'dismiss_suggestion_ajax': {'matomo_dismiss_suggestion'}, '<div class=': {'matomo_minimum_requirements_notice_dismissed'}, 'on_dismiss_notification': {'mtm_dismiss_whats_new'}, 'matomo-referral-notice-dismiss': {'matomo_referral_dismiss_admin_notice'}, 'generate_tracking_code': {'matomo_generate_tracking_code'}, 'on_cart_updated_safe': {'woocommerce_update_shipping_method', 'nopriv_woocommerce_update_shipping_method'}, 'is_marketplace_active': {'matomo_is_marketplace_active'}, 'matomo-systemreport-notice-dismiss': {'matomo_system_report_error_dismissed'}, 'remove_cron_error_ajax': {'mtm_remove_cron_error'}, 'activate_marketplace_plugin': {'matomo_activate_marketplace'}}
*
***/

/** Function dismiss_suggestion_ajax() called by wp_ajax hooks: {'matomo_dismiss_suggestion'} **/
/** Parameters found in function dismiss_suggestion_ajax(): {"request": ["suggestion"]} **/
function dismiss_suggestion_ajax() {
		check_ajax_referer( self::DISMISS_SUGGESTION_NONCE );

		if ( ! empty( $_REQUEST['suggestion'] ) ) {
			$suggestion_id = sanitize_text_field( wp_unslash( $_REQUEST['suggestion'] ) );
			$this->dismiss_suggestion( $suggestion_id );
		}

		wp_send_json( [ 'ok' => true ] );
	}


/** Function <div class=() called by wp_ajax hooks: {'matomo_minimum_requirements_notice_dismissed'} **/
/** No function found :-/ **/


/** Function on_dismiss_notification() called by wp_ajax hooks: {'mtm_dismiss_whats_new'} **/
/** Parameters found in function on_dismiss_notification(): {"post": ["matomo_notification"]} **/
function on_dismiss_notification() {
		check_ajax_referer( self::NONCE_NAME );

		if ( empty( $_POST['matomo_notification'] ) ) {
			wp_send_json( false );
			return;
		}

		$notifications   = $this->get_current_notifications();
		$notification_id = sanitize_text_field( wp_unslash( $_POST['matomo_notification'] ) );
		if ( ! isset( $notifications[ $notification_id ] ) ) {
			wp_send_json( false );
			return;
		}

		$statuses                     = $this->get_notification_statuses();
		$statuses[ $notification_id ] = self::STATUS_DISMISSED;
		$this->save_notification_statuses( $statuses );

		wp_send_json( true );
	}


/** Function matomo-referral-notice-dismiss() called by wp_ajax hooks: {'matomo_referral_dismiss_admin_notice'} **/
/** No function found :-/ **/


/** Function generate_tracking_code() called by wp_ajax hooks: {'matomo_generate_tracking_code'} **/
/** Parameters found in function generate_tracking_code(): {"post": ["track_datacfasync", "track_content", "track_heartbeat", "limit_cookies", "limit_cookies_visitor", "limit_cookies_session", "limit_cookies_referral", "cookie_consent", "force_post", "track_across_alias", "track_across", "track_crossdomain_linking", "track_jserrors", "disable_cookies", "set_link_classes", "set_download_classes", "add_download_extensions", "track_api_endpoint", "force_protocol", "track_js_endpoint", "set_download_extensions"]} **/
function generate_tracking_code() {
		check_ajax_referer( self::NONCE_NAME_GENERATE_TRACKING_CODE_AJAX );

		$blog_id = get_current_blog_id();
		$idsite  = Site::get_matomo_site_id( $blog_id );

		// phpcs complains if reading from $_POST is not done this way
		$overrides = [
			'track_datacfasync'         => isset( $_POST['track_datacfasync'] ) ? ( boolval( wp_unslash( $_POST['track_datacfasync'] ) ) ) : false,
			'track_content'             => isset( $_POST['track_content'] ) ? ( sanitize_text_field( wp_unslash( $_POST['track_content'] ) ) ) : '',
			'track_heartbeat'           => isset( $_POST['track_heartbeat'] ) ? ( intval( wp_unslash( $_POST['track_heartbeat'] ) ) ) : 0,
			'limit_cookies'             => isset( $_POST['limit_cookies'] ) ? ( boolval( wp_unslash( $_POST['limit_cookies'] ) ) ) : false,
			'limit_cookies_visitor'     => isset( $_POST['limit_cookies_visitor'] ) ? ( intval( wp_unslash( $_POST['limit_cookies_visitor'] ) ) ) : 0,
			'limit_cookies_session'     => isset( $_POST['limit_cookies_session'] ) ? ( intval( wp_unslash( $_POST['limit_cookies_session'] ) ) ) : 0,
			'limit_cookies_referral'    => isset( $_POST['limit_cookies_referral'] ) ? ( intval( wp_unslash( $_POST['limit_cookies_referral'] ) ) ) : 0,
			'cookie_consent'            => isset( $_POST['cookie_consent'] ) ? ( sanitize_text_field( wp_unslash( $_POST['cookie_consent'] ) ) ) : '',
			'force_post'                => isset( $_POST['force_post'] ) ? ( wp_unslash( boolval( $_POST['force_post'] ) ) ) : false,
			'track_across_alias'        => isset( $_POST['track_across_alias'] ) ? ( boolval( wp_unslash( $_POST['track_across_alias'] ) ) ) : false,
			'track_across'              => isset( $_POST['track_across'] ) ? ( boolval( wp_unslash( $_POST['track_across'] ) ) ) : false,
			'track_crossdomain_linking' => isset( $_POST['track_crossdomain_linking'] ) ? ( boolval( wp_unslash( $_POST['track_crossdomain_linking'] ) ) ) : false,
			'track_jserrors'            => isset( $_POST['track_jserrors'] ) ? ( boolval( wp_unslash( $_POST['track_jserrors'] ) ) ) : false,
			'disable_cookies'           => isset( $_POST['disable_cookies'] ) ? ( boolval( wp_unslash( $_POST['disable_cookies'] ) ) ) : false,
			'set_link_classes'          => isset( $_POST['set_link_classes'] ) ? ( sanitize_text_field( wp_unslash( $_POST['set_link_classes'] ) ) ) : '',
			'set_download_classes'      => isset( $_POST['set_download_classes'] ) ? ( sanitize_text_field( wp_unslash( $_POST['set_download_classes'] ) ) ) : '',
			'add_download_extensions'   => isset( $_POST['add_download_extensions'] ) ? ( sanitize_text_field( wp_unslash( $_POST['add_download_extensions'] ) ) ) : '',
			'track_api_endpoint'        => isset( $_POST['track_api_endpoint'] ) ? ( sanitize_text_field( wp_unslash( $_POST['track_api_endpoint'] ) ) ) : '',
			'force_protocol'            => isset( $_POST['force_protocol'] ) ? ( boolval( wp_unslash( $_POST['force_protocol'] ) ) ) : false,
			'track_js_endpoint'         => isset( $_POST['track_js_endpoint'] ) ? ( sanitize_text_field( wp_unslash( $_POST['track_js_endpoint'] ) ) ) : '',
			'set_download_extensions'   => isset( $_POST['set_download_extensions'] ) ? ( sanitize_text_field( wp_unslash( $_POST['set_download_extensions'] ) ) ) : '',
		];

		$generator     = new TrackingCodeGenerator( \WpMatomo::$settings, new GeneratorOptions( \WpMatomo::$settings, $overrides ) );
		$tracking_code = $generator->prepare_tracking_code( $idsite );

		wp_send_json( $tracking_code );
	}


/** Function on_cart_updated_safe() called by wp_ajax hooks: {'woocommerce_update_shipping_method', 'nopriv_woocommerce_update_shipping_method'} **/
/** No params detected :-/ **/


/** Function is_marketplace_active() called by wp_ajax hooks: {'matomo_is_marketplace_active'} **/
/** No params detected :-/ **/


/** Function matomo-systemreport-notice-dismiss() called by wp_ajax hooks: {'matomo_system_report_error_dismissed'} **/
/** No function found :-/ **/


/** Function remove_cron_error_ajax() called by wp_ajax hooks: {'mtm_remove_cron_error'} **/
/** Parameters found in function remove_cron_error_ajax(): {"post": ["matomo_job_id"]} **/
function remove_cron_error_ajax() {
		check_ajax_referer( 'matomo-scheduled-task-errors' );

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		if ( empty( $_POST['matomo_job_id'] ) ) {
			wp_send_json( false );
			return;
		}

		// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$job_id = wp_unslash( $_POST['matomo_job_id'] );
		$this->remove_task_errors( [ $job_id ] );

		wp_send_json( true );
	}


/** Function activate_marketplace_plugin() called by wp_ajax hooks: {'matomo_activate_marketplace'} **/
/** No params detected :-/ **/


