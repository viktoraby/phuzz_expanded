<?php
/***
*
*Found actions: 114
*Found functions:113
*Extracted functions:111
*Total parameter names extracted: 70
*Overview: {'capture_last_used_report': {'monsterinsights_vue_capture_last_used_report'}, 'generate_connect_url': {'monsterinsights_connect_url'}, 'update_measurement_protocol_secret': {'monsterinsights_update_measurement_protocol_secret'}, 'monsterinsights_report_error': {'monsterinsights_report_error'}, 'maybe_verify': {'monsterinsights_maybe_verify'}, 'get_filters': {'monsterinsights_get_report_filters'}, 'update_funnel': {'monsterinsights_overview_report_update_funnel_filter'}, 'ajax_start_indexing': {'monsterinsights_sharedcount_start_indexing'}, 'get_funnels': {'monsterinsights_overview_report_get_funnel_filters'}, 'update_settings_bulk': {'monsterinsights_vue_update_settings_bulk'}, 'dismiss_first_time_notice': {'monsterinsights_vue_dismiss_first_time_notice'}, 'update_popular_posts_theme_setting': {'monsterinsights_vue_popular_posts_update_theme_setting'}, 'get_categories': {'monsterinsights_vue_get_categories'}, 'ajax_get_themes': {'monsterinsights_get_popular_posts_themes'}, 'install_and_activate_wpforms': {'monsterinsights_onboarding_wpforms_install'}, 'delete_funnel': {'monsterinsights_overview_report_delete_funnel_filter'}, 'delete_filter': {'monsterinsights_delete_report_filter'}, 'get_taxonomy_terms': {'monsterinsights_get_terms'}, 'monsterinsights_dismiss_ai_insights_addon_notice_ajax': {'monsterinsights_dismiss_ai_insights_addon_notice'}, 'delete_categories': {'monsterinsights_vue_delete_categories'}, 'monsterinsights_ai_charlie_load_chat': {'monsterinsights_ai_charlie_load_chat'}, 'review_dismiss': {'monsterinsights_review_dismiss'}, 'restore_notes': {'monsterinsights_vue_restore_notes'}, 'monsterinsights_ai_charlie_get_chats': {'monsterinsights_ai_charlie_get_chats'}, 'get_eea_compliance': {'monsterinsights_vue_get_eea_compliance'}, 'maybe_delete': {'monsterinsights_maybe_delete'}, 'mark_notice_closed': {'monsterinsights_mark_notice_closed'}, 'save_note': {'monsterinsights_vue_save_note'}, 'dismiss': {'monsterinsights_notification_dismiss'}, 'get_license': {'monsterinsights_vue_get_license'}, 'monsterinsights_ai_charlie_pin_chat': {'monsterinsights_ai_charlie_pin_chat'}, 'get_notice_status': {'monsterinsights_vue_notice_status'}, 'rauthenticate': {'nopriv_monsterinsights_rauthenticate'}, 'ajax_generate_setup_wizard_url': {'monsterinsights_generate_setup_wizard_url'}, 'monsterinsights_ajax_activate_addon': {'monsterinsights_activate_addon'}, 'monsterinsights_get_floatbar': {'monsterinsights_get_floatbar'}, 'dismiss_charitablewp_notice': {'monsterinsights_dismiss_charitablewp_notice'}, 'process': {'nopriv_monsterinsights_connect_process'}, 'save_filter': {'monsterinsights_save_report_filter'}, 'get_user_included_metrics': {'monsterinsights_vue_get_user_included_metrics'}, 'trash_notes': {'monsterinsights_vue_trash_notes'}, 'update_filter': {'monsterinsights_update_report_filter'}, 'monsterinsights_ai_charlie_delete_chat': {'monsterinsights_ai_charlie_delete_chat'}, 'delete_all_funnels': {'monsterinsights_overview_report_delete_all_funnel_filters'}, 'monsterinsights_check_plugin_funnelkit_funnelkit_stripe_woo_gateway_configured': {'monsterinsights_funnelkit_stripe_woo_gateway_configured'}, 'monsterinsights_ajax_dismiss_wpconsent_notice': {'monsterinsights_dismiss_wpconsent_notice'}, 'monsterinsights_ajax_deactivate_addon': {'monsterinsights_deactivate_addon'}, 'monsterinsights_ajax_get_backfill_cache': {'monsterinsights_get_backfill_cache'}, 'monsterinsights_ai_charlie_get_saved_chats': {'monsterinsights_ai_charlie_get_saved_chats'}, 'import_notes_from_ga4': {'monsterinsights_vue_import_notes'}, 'monsterinsights_ajax_install_addon': {'monsterinsights_install_addon'}, 'manual_check': {'monsterinsights_manual_product_feed_check'}, 'save_category': {'monsterinsights_vue_save_category'}, 'monsterinsights_get_aiseo_cta_status': {'monsterinsights_get_aiseo_cta_status'}, 'test_check_tracking_code': {'health-check-monsterinsights-test_tracking_code'}, 'get_post_types': {'monsterinsights_get_post_types'}, 'save_notification': {'monsterinsights_notification_save'}, 'dismiss_promo': {'monsterinsights_vue_dismiss_promo'}, 'monsterinsights_dismiss_tracking_notice': {'monsterinsights_dismiss_tracking_notice'}, 'monsterinsights_user_journey_demo_report_ajax': {'monsterinsights_user_journey_report'}, 'test_check_connection': {'health-check-monsterinsights-test_connection'}, 'monsterinsights_ajax_dismiss_notice': {'monsterinsights_ajax_dismiss_notice'}, 'ajax_button_click_track': {'monsterinsights_vue_setup_checklist_click_track'}, 'get_settings': {'monsterinsights_vue_get_settings'}, 'monsterinsights_handle_get_plugin_info': {'nopriv_monsterinsights_get_plugin_info'}, 'get_note': {'monsterinsights_vue_get_note'}, 'maybe_add_notifications': {'monsterinsights_vue_get_notifications'}, 'get_install_errors': {'monsterinsights_onboarding_get_errors'}, 'ajax_get_index_progress': {'monsterinsights_sharedcount_get_index_progress'}, 'handle_request': {'monsterinsights_get_overview_data'}, 'update_settings': {'monsterinsights_vue_update_settings'}, 'get_ajax_output': {'nopriv_monsterinsights_popular_posts_get_widget_output', 'monsterinsights_popular_posts_get_widget_output'}, 'onboarding_maybe_authenticate': {'nopriv_onboarding_monsterinsights_maybe_authenticate'}, 'monsterinsights_ai_charlie_save_chat': {'monsterinsights_ai_charlie_save_chat'}, 'handle_relay_mp_token_push': {'nopriv_monsterinsights_push_mp_token'}, '__return_false': {'monsterinsights_user_journey_report_filter_params'}, 'remind_me': {'monsterinsights_notification_remind_me'}, 'update_included_metrics': {'monsterinsights_vue_update_included_metrics'}, 'check_eea_compliance': {'monsterinsights_vue_check_eea_compliance'}, 'ajax_get_notifications': {'monsterinsights_vue_get_notifications'}, 'update_manual_v4': {'monsterinsights_update_manual_v4'}, 'monsterinsights_dismiss_ads_addon_notice_ajax': {'monsterinsights_dismiss_ads_addon_notice'}, 'handle_settings_import': {'monsterinsights_handle_settings_import'}, 'monsterinsights_ajax_dismiss_seoboost_cta': {'monsterinsights_vue_dismiss_seoboost_cta'}, 'monsterinsights_mark_floatbar_hidden': {'monsterinsights_hide_floatbar'}, 'ajax_optimize': {'monsterinsights_gutenberg_headline_analyzer_optimize'}, 'export_notes_to_ga4': {'monsterinsights_vue_export_notes'}, 'monsterinsights_mark_admin_menu_tooltip_hidden': {'monsterinsights_hide_admin_menu_tooltip'}, 'delete_notes': {'monsterinsights_vue_delete_notes'}, 'get_notes': {'monsterinsights_vue_get_notes'}, 'get_profile': {'monsterinsights_vue_get_profile'}, 'ajax_get_setup_checklist': {'monsterinsights_vue_get_setup_checklist'}, 'onboarding_get_install_errors': {'nopriv_onboarding_monsterinsights_onboarding_get_errors'}, 'maybe_reauthenticate': {'monsterinsights_maybe_reauthenticate'}, 'install_plugin': {'monsterinsights_vue_install_plugin'}, 'monsterinsights_vue_dismiss_aiseo_cta': {'monsterinsights_vue_dismiss_aiseo_cta'}, 'is_installed': {'nopriv_monsterinsights_is_installed'}, 'save_funnel': {'monsterinsights_overview_report_save_funnel_filter'}, 'mark_read': {'monsterinsights_notification_mark_read'}, 'get_report_data': {'monsterinsights_vue_get_report_data'}, 'maybe_authenticate': {'monsterinsights_maybe_authenticate'}, 'get_overview_bundle': {'monsterinsights_vue_get_overview_bundle'}, 'MonsterInsights_API_Token': {'monsterinsights_get_bearer_token'}, 'check_popular_posts_report': {'monsterinsights_vue_grab_popular_posts_report'}, 'save_widget_state': {'monsterinsights_save_widget_state'}, 'dismiss_notice': {'monsterinsights_vue_notice_dismiss'}, 'get_posts': {'monsterinsights_get_posts'}, 'get_result': {'monsterinsights_gutenberg_headline_analyzer_get_results'}, 'empty_cache': {'monsterinsights_popular_posts_empty_cache'}, 'monsterinsights_get_seo_boost_cta_status': {'monsterinsights_get_seo_boost_cta_status'}, 'send_test_email': {'monsterinsights_send_test_email'}, 'monsterinsights_ajax_backfill_cache': {'monsterinsights_backfill_cache'}, 'get_addons': {'monsterinsights_vue_get_addons'}}
*
***/

/** Function capture_last_used_report() called by wp_ajax hooks: {'monsterinsights_vue_capture_last_used_report'} **/
/** Parameters found in function capture_last_used_report(): {"post": ["report"]} **/
function capture_last_used_report() {
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_save_settings' ) || empty( $_POST['report'] ) ) {
			return;
		}

		$report = sanitize_text_field( wp_unslash( $_POST['report'] ) );
		update_option( 'monsterinsights_last_visited_report_name', $report );
		update_option( 'monsterinsights_last_visited_report_date', time() );

		wp_send_json_success();
	}


/** Function generate_connect_url() called by wp_ajax hooks: {'monsterinsights_connect_url'} **/
/** Parameters found in function generate_connect_url(): {"post": ["key", "network"]} **/
function generate_connect_url() {
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		// Check for permissions.
		if ( ! monsterinsights_can_install_plugins() ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Oops! You are not allowed to install plugins. Please contact your site administrator.', 'google-analytics-for-wordpress' ) ) );
		}

		if ( monsterinsights_is_dev_url( home_url() ) ) {
			wp_send_json_success( array(
				'url' => 'https://www.monsterinsights.com/docs/go-lite-pro/#manual-upgrade',
			) );
		}
		$key = ! empty( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification

		if ( empty( $key ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Please enter your license key to connect.', 'google-analytics-for-wordpress' ),
				)
			);
		}

		// Verify pro version is not installed.
		$active = activate_plugin( 'google-analytics-premium/googleanalytics-premium.php', false, false, true );
		if ( ! is_wp_error( $active ) ) {
			// Deactivate plugin.
			deactivate_plugins( plugin_basename( MONSTERINSIGHTS_PLUGIN_FILE ), false, false );
			wp_send_json_error( array(
				'message' => esc_html__( 'You already have MonsterInsights Pro installed.', 'google-analytics-for-wordpress' ),
				'reload'  => true,
			) );
		}

		// Network?
		$network = ! empty( $_POST['network'] ) && $_POST['network']; // phpcs:ignore

		$url_data = self::generate_connect_url_data( $key, $network );
		if ( empty( $url_data ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Please enter your license key to connect.', 'google-analytics-for-wordpress' ),
				)
			);
		}

		wp_send_json_success( array(
			'url' => $url_data['url'],
		) );
	}


/** Function update_measurement_protocol_secret() called by wp_ajax hooks: {'monsterinsights_update_measurement_protocol_secret'} **/
/** Parameters found in function update_measurement_protocol_secret(): {"request": ["isnetwork", "value"]} **/
function update_measurement_protocol_secret() {
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		if ( ! empty( $_REQUEST['isnetwork'] ) && sanitize_text_field( wp_unslash( $_REQUEST['isnetwork'] ) ) ) {
			if ( ! current_user_can( 'manage_network_options' ) ) {
				wp_send_json_error( array(
					'error' => esc_html__( 'You do not have permission to update network settings.', 'google-analytics-for-wordpress' ),
				) );
			}
			define( 'WP_NETWORK_ADMIN', true );
		}

		$value = empty( $_REQUEST['value'] ) ? '' : sanitize_text_field( wp_unslash( $_REQUEST['value'] ) );

		$auth = MonsterInsights()->auth;

		if ( is_network_admin() ) {
			$auth->set_network_measurement_protocol_secret( $value );
		} else {
			$auth->set_measurement_protocol_secret( $value );
		}

		// Send API request to Relay
		// TODO: Remove when token automation API is ready
		$api = new MonsterInsights_API_Request( 'auth/mp-token/', 'POST' );
		$api->set_additional_data( array(
			'mp_token' => $value,
		) );

		// Even if there's an error from Relay, we can still return a successful json
		// payload because we can try again with Relay token push in the future
		$data   = array();
		$result = $api->request();
		if ( is_wp_error( $result ) ) {
			// Just need to output the error in the response for debugging purpose
			$data['error'] = array(
				'message' => $result->get_error_message(),
				'code'    => $result->get_error_code(),
			);
		}

		wp_send_json_success( $data );
	}


/** Function monsterinsights_report_error() called by wp_ajax hooks: {'monsterinsights_report_error'} **/
/** Parameters found in function monsterinsights_report_error(): {"post": ["error_code", "current_screen"]} **/
function monsterinsights_report_error() {
	check_ajax_referer( 'mi-admin-nonce', 'nonce' );
	if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
		return;
	}
	if ( ! isset( $_POST['error_code'] ) || ! isset( $_POST['current_screen'] ) ) {
		return;
	}
	$error_code     = sanitize_text_field( wp_unslash( $_POST['error_code'] ) );
	$current_screen = sanitize_text_field( wp_unslash( $_POST['current_screen'] ) );
	$last_plugin_error = array(
		'code' => $error_code,
		'screen' => $current_screen,
		'date' => time()
	);
	update_option( 'monsterinsights_last_plugin_error', $last_plugin_error );
	wp_send_json_success();
}


/** Function maybe_verify() called by wp_ajax hooks: {'monsterinsights_maybe_verify'} **/
/** Parameters found in function maybe_verify(): {"request": ["isnetwork"]} **/
function maybe_verify() {

		// Check nonce
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		// current user can verify
		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			$message = sprintf(
				/* translators: %1$s: Opening link tag, %2$s: Closing link tag. */
				__( 'You don\'t have the correct user permissions to verify the MonsterInsights license you are trying to use. Please check with your site administrator that your role is included in the MonsterInsights permissions settings. %1$sClick here for more information%2$s.', 'google-analytics-for-wordpress' ),
				'<a target="_blank" rel="noopener" href="' . monsterinsights_get_url( 'notice', 'cannot-save-settings', 'https://www.monsterinsights.com/docs/how-to-allow-user-roles-to-access-the-monsterinsights-reports-and-settings/' ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		}

		if ( ! empty( $_REQUEST['isnetwork'] ) && filter_var(wp_unslash($_REQUEST['isnetwork']), FILTER_VALIDATE_BOOL) ) {
			if ( ! current_user_can( 'manage_network_options' ) ) {
				wp_send_json_error( array(
					'error' => esc_html__( 'You do not have permission to update network settings.', 'google-analytics-for-wordpress' ),
				) );
			}
			define( 'WP_NETWORK_ADMIN', true );
		}

		// we have an auth to verify
		if ( $this->is_network_admin() && ! MonsterInsights()->auth->is_network_authed() ) {
			$message = sprintf(
				/* translators: %1$s: Opening link tag, %2$s: Closing link tag. */
				__( 'Please enter a valid license within the MonsterInsights settings panel. You can check your license by logging into your MonsterInsights account by %1$sclicking here%2$s.', 'google-analytics-for-wordpress' ),
				'<a target="_blank" rel="noopener" href="' . monsterinsights_get_url( 'notice', 'cannot-verify-license', 'https://www.monsterinsights.com/my-account/' ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		} else if ( ! $this->is_network_admin() && ! MonsterInsights()->auth->is_authed() ) {
			$message = sprintf(
				/* translators: %1$s: Opening link tag, %2$s: Closing link tag. */
				__( 'Please enter a valid license within the MonsterInsights settings panel. You can check your license by logging into your MonsterInsights account by %1$sclicking here%2$s.', 'google-analytics-for-wordpress' ),
				'<a target="_blank" rel="noopener" href="' . monsterinsights_get_url( 'notice', 'cannot-verify-license', 'https://www.monsterinsights.com/my-account/' ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		}

		if ( monsterinsights_is_pro_version() ) {
			$valid = is_network_admin() ? MonsterInsights()->license->is_network_licensed() : MonsterInsights()->license->is_site_licensed();
			if ( ! $valid ) {
				$message = sprintf(
					/* translators: %1$s: Opening link tag, %2$s: Closing link tag. */
					__( 'Please enter a valid license within the MonsterInsights settings panel. You can check your license by logging into your MonsterInsights account by %1$sclicking here%2$s.', 'google-analytics-for-wordpress' ),
					'<a target="_blank" rel="noopener" href="' . monsterinsights_get_url( 'notice', 'cannot-verify-license', 'https://www.monsterinsights.com/my-account/' ) . '">',
					'</a>'
				);
				wp_send_json_error( array( 'message' => $message ) );
			}
		}

		$worked = $this->verify_auth();
		if ( $worked && ! is_wp_error( $worked ) ) {
			wp_send_json_success( array( 'message' => __( "Successfully verified.", 'google-analytics-for-wordpress' ) ) );
		} else {
			$message = sprintf(
				/* translators: %1$s: Opening support link tag, %2$s: Closing support link tag. */
				__( 'Oops! There has been an error while trying to verify your license. Please try again or contact our support team by %1$sclicking here%2$s.', 'google-analytics-for-wordpress' ),
				'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'cannot-verify-license', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		}
	}


/** Function get_filters() called by wp_ajax hooks: {'monsterinsights_get_report_filters'} **/
/** No params detected :-/ **/


/** Function update_funnel() called by wp_ajax hooks: {'monsterinsights_overview_report_update_funnel_filter'} **/
/** Parameters found in function update_funnel(): {"post": ["funnel_id", "funnel"]} **/
function update_funnel() {
		$this->verify_request( 'monsterinsights_save_settings' );

		$funnel_id   = isset( $_POST['funnel_id'] ) ? sanitize_text_field( wp_unslash( $_POST['funnel_id'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified in verify_request() above.
		$funnel_json = isset( $_POST['funnel'] ) ? wp_unslash( $_POST['funnel'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified in verify_request() above.

		if ( empty( $funnel_id ) || empty( $funnel_json ) ) {
			wp_send_json_error( array(
				'message' => __( 'Missing required funnel data.', 'monsterinsights' ),
			) );
			return;
		}

		$funnels = $this->get_all_funnels();

		if ( ! isset( $funnels[ $funnel_id ] ) ) {
			wp_send_json_error( array(
				'message' => __( 'Funnel not found.', 'monsterinsights' ),
			) );
			return;
		}

		$funnel_data = json_decode( $funnel_json, true );

		if ( ! is_array( $funnel_data ) ) {
			wp_send_json_error( array(
				'message' => __( 'Invalid funnel data.', 'monsterinsights' ),
			) );
			return;
		}

		$sanitized = $this->sanitize_funnel_data( $funnel_data );

		$funnels[ $funnel_id ] = array_merge(
			$funnels[ $funnel_id ],
			$sanitized
		);

		$this->save_all_funnels( $funnels );

		wp_send_json_success( $funnels[ $funnel_id ] );
	}


/** Function ajax_start_indexing() called by wp_ajax hooks: {'monsterinsights_sharedcount_start_indexing'} **/
/** No params detected :-/ **/


/** Function get_funnels() called by wp_ajax hooks: {'monsterinsights_overview_report_get_funnel_filters'} **/
/** No params detected :-/ **/


/** Function update_settings_bulk() called by wp_ajax hooks: {'monsterinsights_vue_update_settings_bulk'} **/
/** Parameters found in function update_settings_bulk(): {"post": ["settings"]} **/
function update_settings_bulk() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		if ( isset( $_POST['settings'] ) ) {
			$settings = json_decode( sanitize_text_field( wp_unslash( $_POST['settings'] ) ), true );
			foreach ( $settings as $setting => $value ) {
				// Skip admin-only settings for non-admin users.
				if ( monsterinsights_is_admin_only_setting( $setting ) && ! current_user_can( 'manage_options' ) ) {
					continue;
				}
				$value = $this->handle_sanitization( $setting, $value );
				monsterinsights_update_option( $setting, $value );
				do_action( 'monsterinsights_after_update_settings', $setting, $value );
			}
		}

		wp_send_json_success();

	}


/** Function dismiss_first_time_notice() called by wp_ajax hooks: {'monsterinsights_vue_dismiss_first_time_notice'} **/
/** No params detected :-/ **/


/** Function update_popular_posts_theme_setting() called by wp_ajax hooks: {'monsterinsights_vue_popular_posts_update_theme_setting'} **/
/** Parameters found in function update_popular_posts_theme_setting(): {"post": ["type", "theme", "object", "key", "value"]} **/
function update_popular_posts_theme_setting() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		if ( ! empty( $_POST['type'] ) && ! empty( $_POST['theme'] ) && ! empty( $_POST['object'] ) && ! empty( $_POST['key'] ) && ! empty( $_POST['value'] ) ) {
			$settings_key = 'monsterinsights_popular_posts_theme_settings';
			$type         = sanitize_text_field( wp_unslash( $_POST['type'] ) ); // Type of Popular Posts instance: inline/widget/products.
			$theme        = sanitize_text_field( wp_unslash( $_POST['theme'] ) );
			$object       = sanitize_text_field( wp_unslash( $_POST['object'] ) ); // Style object like title, label, background, etc.
			$key          = sanitize_text_field( wp_unslash( $_POST['key'] ) ); // Style key for the object like color, font size, etc.
			$value        = sanitize_text_field( wp_unslash( $_POST['value'] ) ); // Value of custom style like 12px or #fff.
			$settings     = get_option( $settings_key, array() );

			if ( ! isset( $settings[ $type ] ) ) {
				$settings[ $type ] = array();
			}
			if ( ! isset( $settings[ $type ][ $theme ] ) ) {
				$settings[ $type ][ $theme ] = array();
			}

			if ( ! isset( $settings[ $type ][ $theme ][ $object ] ) ) {
				$settings[ $type ][ $theme ][ $object ] = array();
			}

			$settings[ $type ][ $theme ][ $object ][ $key ] = $value;

			update_option( $settings_key, $settings );

			wp_send_json_success();
		}

		wp_send_json_error();

	}


/** Function get_categories() called by wp_ajax hooks: {'monsterinsights_vue_get_categories'} **/
/** Parameters found in function get_categories(): {"post": ["params"]} **/
function get_categories() {
		check_ajax_referer('mi-admin-nonce', 'nonce');

		if ( ! current_user_can( 'monsterinsights_view_dashboard' ) && ! current_user_can( 'monsterinsights_save_settings' ) ) {
			wp_send_json(
				array(
					'published' => false,
					'message' => __( "You don't have permission to view notes categories.", 'google-analytics-for-wordpress' ),
				)
			);
		}

		$params = !empty($_POST['params']) ? json_decode(html_entity_decode(wp_unslash($_POST['params'])), true) : [];

		$args = wp_parse_args($params, array(
			'per_page' => -1,
			'page' => 1,
			'orderby' => 'name',
			'order' => 'asc',
		));

		$total = intval($this->db->get_categories($args, true));

		if ($total) {
			$items = $this->db->get_categories($args);
		} else {
			$items = array();
		}

		wp_send_json(
			array(
				'items' => $items,
				'pagination' => array(
					'total' => $total,
					'pages' => ceil($total / $args['per_page']),
					'page'  => $args['page'],
					'per_page' => $args['per_page'],
				),
			)
		);
	}


/** Function ajax_get_themes() called by wp_ajax hooks: {'monsterinsights_get_popular_posts_themes'} **/
/** Parameters found in function ajax_get_themes(): {"post": ["type"]} **/
function ajax_get_themes() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		$type = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : 'inline';

		wp_send_json_success( $this->get_themes_by_type( $type, false ) );

	}


/** Function install_and_activate_wpforms() called by wp_ajax hooks: {'monsterinsights_onboarding_wpforms_install'} **/
/** No params detected :-/ **/


/** Function delete_funnel() called by wp_ajax hooks: {'monsterinsights_overview_report_delete_funnel_filter'} **/
/** Parameters found in function delete_funnel(): {"post": ["funnel_id"]} **/
function delete_funnel() {
		$this->verify_request( 'monsterinsights_save_settings' );

		$funnel_id = isset( $_POST['funnel_id'] ) ? sanitize_text_field( wp_unslash( $_POST['funnel_id'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified in verify_request() above.

		if ( empty( $funnel_id ) ) {
			wp_send_json_error( array(
				'message' => __( 'No funnel ID provided.', 'monsterinsights' ),
			) );
			return;
		}

		$funnels = $this->get_all_funnels();

		if ( ! isset( $funnels[ $funnel_id ] ) ) {
			wp_send_json_error( array(
				'message' => __( 'Funnel not found.', 'monsterinsights' ),
			) );
			return;
		}

		unset( $funnels[ $funnel_id ] );

		$this->save_all_funnels( $funnels );

		wp_send_json_success( array(
			'message' => __( 'Funnel deleted successfully.', 'monsterinsights' ),
		) );
	}


/** Function delete_filter() called by wp_ajax hooks: {'monsterinsights_delete_report_filter'} **/
/** Parameters found in function delete_filter(): {"post": ["filter_id"]} **/
function delete_filter() {
		$this->verify_request( 'monsterinsights_save_settings' );

		$filter_id = isset( $_POST['filter_id'] ) ? sanitize_text_field( wp_unslash( $_POST['filter_id'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified in verify_request() above.

		if ( empty( $filter_id ) ) {
			wp_send_json_error( array(
				'message' => __( 'No filter ID provided.', 'monsterinsights' ),
			) );
			return;
		}

		$filters = $this->get_all_filters();

		if ( ! isset( $filters[ $filter_id ] ) ) {
			wp_send_json_error( array(
				'message' => __( 'Filter not found.', 'monsterinsights' ),
			) );
			return;
		}

		unset( $filters[ $filter_id ] );

		$this->save_all_filters( $filters );

		wp_send_json_success( array(
			'message' => __( 'Filter deleted successfully.', 'monsterinsights' ),
		) );
	}


/** Function get_taxonomy_terms() called by wp_ajax hooks: {'monsterinsights_get_terms'} **/
/** Parameters found in function get_taxonomy_terms(): {"post": ["keyword", "taxonomy"]} **/
function get_taxonomy_terms() {

		// Run a security check first.
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		$keyword  = isset( $_POST['keyword'] ) ? sanitize_text_field( wp_unslash( $_POST['keyword'] ) ) : '';
		$taxonomy = isset( $_POST['taxonomy'] ) ? sanitize_text_field( wp_unslash( $_POST['taxonomy'] ) ) : 'category';

		$args = array(
			'taxonomy'   => array( $taxonomy ),
			'hide_empty' => false,
			'name__like' => $keyword,
		);

		$terms = get_terms( $args );
		$array = array();

		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$array[] = array(
					'id'   => esc_attr( $term->term_id ),
					'text' => esc_attr( $term->name ),
				);
			}
		}

		wp_send_json_success( $array );
	}


/** Function monsterinsights_dismiss_ai_insights_addon_notice_ajax() called by wp_ajax hooks: {'monsterinsights_dismiss_ai_insights_addon_notice'} **/
/** No params detected :-/ **/


/** Function delete_categories() called by wp_ajax hooks: {'monsterinsights_vue_delete_categories'} **/
/** Parameters found in function delete_categories(): {"post": ["ids"]} **/
function delete_categories() {
		check_ajax_referer('mi-admin-nonce', 'nonce');

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			wp_send_json(
				array(
					'published' => false,
					'message' => __( "You don't have permission to update notes.", 'google-analytics-for-wordpress' ),
				)
			);
		}

		$ids = !empty($_POST['ids']) ? json_decode(html_entity_decode(wp_unslash($_POST['ids']))) : [];

		if (empty($ids)) {
			wp_send_json(
				array(
					'success' => false,
					'message' => __('Please choose a category to delete!', 'google-analytics-for-wordpress'),
				)
			);
		}

		foreach ($ids as $id) {
			$this->db->delete_category($id);
		}

		wp_send_json(
			array(
				'success' => true,
				'message' => '',
			)
		);
	}


/** Function monsterinsights_ai_charlie_load_chat() called by wp_ajax hooks: {'monsterinsights_ai_charlie_load_chat'} **/
/** Parameters found in function monsterinsights_ai_charlie_load_chat(): {"post": ["chat_id"]} **/
function monsterinsights_ai_charlie_load_chat() {
	check_ajax_referer( 'mi-admin-nonce', 'nonce' );

	if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
		wp_send_json_error( array( 'message' => __( 'You do not have permission.', 'google-analytics-for-wordpress' ) ) );
	}

	$chat_id = isset( $_POST['chat_id'] ) ? sanitize_text_field( wp_unslash( $_POST['chat_id'] ) ) : '';

	if ( empty( $chat_id ) ) {
		wp_send_json_error( array( 'message' => __( 'Missing chat ID.', 'google-analytics-for-wordpress' ) ) );
	}

	$group = monsterinsights_ai_charlie_cache_group();
	$key   = 'chat_' . $chat_id;
	$data  = monsterinsights_cache_get( $key, $group );

	if ( false === $data ) {
		wp_send_json_error( array( 'message' => __( 'Chat not found.', 'google-analytics-for-wordpress' ) ) );
	}

	wp_send_json_success( array( 'chat' => $data ) );
}


/** Function review_dismiss() called by wp_ajax hooks: {'monsterinsights_review_dismiss'} **/
/** No params detected :-/ **/


/** Function restore_notes() called by wp_ajax hooks: {'monsterinsights_vue_restore_notes'} **/
/** Parameters found in function restore_notes(): {"post": ["ids"]} **/
function restore_notes() {
		check_ajax_referer('mi-admin-nonce', 'nonce');

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			wp_send_json(
				array(
					'published' => false,
					'message' => __( "You don't have permission to update notes.", 'google-analytics-for-wordpress' ),
				)
			);
		}

		$ids = !empty($_POST['ids']) ? json_decode(html_entity_decode(wp_unslash($_POST['ids']))) : [];

		if (empty($ids)) {
			wp_send_json(
				array(
					'success' => false,
					'message' => __('Please choose a site note(s) to restore!', 'google-analytics-for-wordpress'),
				)
			);
		}

		$blocked = false;
		foreach ($ids as $id) {
			if ( is_wp_error( $this->db->restore_note($id) ) ) {
				$blocked = true;
			}
		}

		if ( $blocked ) {
			wp_send_json(
				array(
					'success' => false,
					'message' => __( "You don't have permission to restore one or more of these notes.", 'google-analytics-for-wordpress' ),
				)
			);
		}

		wp_send_json(
			array(
				'success' => true,
				'message' => '',
			)
		);
	}


/** Function monsterinsights_ai_charlie_get_chats() called by wp_ajax hooks: {'monsterinsights_ai_charlie_get_chats'} **/
/** Parameters found in function monsterinsights_ai_charlie_get_chats(): {"post": ["page"]} **/
function monsterinsights_ai_charlie_get_chats() {
	check_ajax_referer( 'mi-admin-nonce', 'nonce' );

	if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
		wp_send_json_error( array( 'message' => __( 'You do not have permission.', 'google-analytics-for-wordpress' ) ) );
	}

	global $wpdb;

	$per_page = 20;
	$page     = isset( $_POST['page'] ) ? max( 1, (int) $_POST['page'] ) : 1; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce verified above via check_ajax_referer.
	$offset   = ( $page - 1 ) * $per_page;

	$cache_table = new MonsterInsights_Cache_Table();
	$table       = $cache_table->get_table_name();
	$group       = monsterinsights_ai_charlie_cache_group();

	// Fetch one extra row to detect whether a next page exists without a COUNT(*) query.
	$rows = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT cache_value FROM {$table} WHERE cache_group = %s AND expires_at > NOW() ORDER BY expires_at DESC LIMIT %d OFFSET %d", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- table name from MonsterInsights_Cache_Table, not user input.
			$group,
			$per_page + 1,
			$offset
		)
	);

	$has_more = count( $rows ) > $per_page;
	if ( $has_more ) {
		array_pop( $rows );
	}

	// Rows arrive ordered by expires_at DESC, which acts as a last-saved
	// timestamp because every save renews the cache TTL. That gives us a
	// "most recently used" ordering across all pages — matches typical chat
	// history UX (ChatGPT/Claude). We deliberately don't re-sort here by
	// created_at: a per-page usort would only reshuffle within a page and
	// break chronology across page boundaries.
	$chats = array();
	foreach ( $rows as $row ) {
		$data = maybe_unserialize( $row->cache_value );
		if ( ! is_array( $data ) ) {
			continue;
		}
		$chats[] = array(
			'id'         => isset( $data['id'] ) ? $data['id'] : '',
			'title'      => isset( $data['title'] ) ? $data['title'] : '',
			'preview'    => isset( $data['preview'] ) ? $data['preview'] : '',
			'pinned'     => ! empty( $data['pinned'] ),
			'created_at' => isset( $data['created_at'] ) ? $data['created_at'] : 0,
			'updated_at' => isset( $data['updated_at'] ) ? $data['updated_at'] : 0,
		);
	}

	wp_send_json_success( array(
		'chats'    => $chats,
		'has_more' => $has_more,
	) );
}


/** Function get_eea_compliance() called by wp_ajax hooks: {'monsterinsights_vue_get_eea_compliance'} **/
/** No params detected :-/ **/


/** Function maybe_delete() called by wp_ajax hooks: {'monsterinsights_maybe_delete'} **/
/** Parameters found in function maybe_delete(): {"request": ["isnetwork", "forcedelete"]} **/
function maybe_delete() {

		// Check nonce
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		$url = monsterinsights_get_onboarding_url();

		// current user can delete
		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			$message = sprintf(
				/* translators: %1$s: Opening link tag, %2$s: Closing link tag. */
				__( 'You don\'t have the correct WordPress user permissions to deauthenticate into MonsterInsights. Please check with your site administrator that your role is included in the MonsterInsights permissions settings. %1$sClick here for more information%2$s.', 'google-analytics-for-wordpress' ),
				'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'cannot-save-settings', 'https://www.monsterinsights.com/docs/how-to-allow-user-roles-to-access-the-monsterinsights-reports-and-settings/' ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		}

		if ( ! empty( $_REQUEST['isnetwork'] ) && filter_var(wp_unslash($_REQUEST['isnetwork']), FILTER_VALIDATE_BOOL) ) {
			if ( ! current_user_can( 'manage_network_options' ) ) {
				wp_send_json_error( array(
					'error' => esc_html__( 'You do not have permission to update network settings.', 'google-analytics-for-wordpress' ),
				) );
			}
			define( 'WP_NETWORK_ADMIN', true );
		}

		// we have an auth to delete
		if ( $this->is_network_admin() && ! MonsterInsights()->auth->is_network_authed() ) {
			$message = sprintf(
				/* translators: %1$s: Opening wizard link tag, %2$s: Closing wizard link tag, %3$s: Opening support link tag, %4$s: Closing support link tag. */
				__( 'Could not disconnect as you are not currently authenticated properly. Please try to authenticate again with our MonsterInsights %1$ssetup wizard%2$s.  If you are still having problems, please %3$scontact our support%4$s team.', 'google-analytics-for-wordpress' ),
				'<a href="' . esc_url( $url ) . '">',
				'</a>',
				'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'cannot-de-authenticate-license', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		} else if ( ! $this->is_network_admin() && ! MonsterInsights()->auth->is_authed() ) {
			$message = sprintf(
				/* translators: %1$s: Opening wizard link tag, %2$s: Closing wizard link tag, %3$s: Opening support link tag, %4$s: Closing support link tag. */
				__( 'Could not disconnect as you are not currently authenticated properly. Please try to authenticate again with our MonsterInsights %1$ssetup wizard%2$s.  If you are still having problems, please %3$scontact our support%4$s team.', 'google-analytics-for-wordpress' ),
				'<a href="' . esc_url( $url ) . '">',
				'</a>',
				'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'cannot-de-authenticate-license', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		}

		if ( monsterinsights_is_pro_version() ) {
			$valid = is_network_admin() ? MonsterInsights()->license->is_network_licensed() : MonsterInsights()->license->is_site_licensed();
			if ( ! $valid ) {
				$message = sprintf(
					/* translators: %1$s: Opening wizard link tag, %2$s: Closing wizard link tag, %3$s: Opening support link tag, %4$s: Closing support link tag. */
					__( 'Could not disconnect your account, as you are not currently authenticated properly. Please try to authenticate again with our %1$sMonsterInsights setup wizard%2$s.  If you are still having problems, please %3$scontact our support%4$s team.', 'google-analytics-for-wordpress' ),
					'<a href="' . esc_url( $url ) . '">',
					'</a>',
					'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'cannot-de-authenticate-license', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
					'</a>'
				);
				wp_send_json_error( array( 'message' => $message ) );
			}
		}

		$force = ! empty( $_REQUEST['forcedelete'] ) && wp_unslash( $_REQUEST['forcedelete'] ) === 'true';

		$worked = $this->delete_auth( $force );
		if ( $worked && ! is_wp_error( $worked ) ) {
			wp_send_json_success( array( 'message' => __( "Successfully deauthenticated.", 'google-analytics-for-wordpress' ) ) );
		} else {
			if ( $force ) {
				wp_send_json_success( array( 'message' => __( "Successfully force deauthenticated.", 'google-analytics-for-wordpress' ) ) );
			} else {
				$message = sprintf(
					/* translators: %1$s: Opening support link tag, %2$s: Closing support link tag. */
					__( 'Oops! There has been an error while trying to deauthenticate. Please try again. If the issue persists, please %1$scontact our support%2$s team.', 'google-analytics-for-wordpress' ),
					'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'cannot-de-authenticate-license', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
					'</a>'
				);
				wp_send_json_error( array( 'message' => $message ) );
			}
		}
	}


/** Function mark_notice_closed() called by wp_ajax hooks: {'monsterinsights_mark_notice_closed'} **/
/** No params detected :-/ **/


/** Function save_note() called by wp_ajax hooks: {'monsterinsights_vue_save_note'} **/
/** Parameters found in function save_note(): {"post": ["note"]} **/
function save_note() {
		check_ajax_referer('mi-admin-nonce', 'nonce');

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			wp_send_json(
				array(
					'published' => false,
					'message' => __( "You don't have permission to update notes.", 'google-analytics-for-wordpress' ),
				)
			);
		}

		$note = !empty($_POST['note']) ? json_decode(html_entity_decode(wp_unslash($_POST['note']))) : [];

		$note_details = array(
			'note' => sanitize_text_field($note->note_title),
			'category' => intval(is_object($note->category) && isset($note->category->id) && intval($note->category->id) ? $note->category->id : 0),
			'date' => $note->note_date_ymd,
			'medias' => !empty($note->medias) ? array_values(array_keys((array) $note->medias)) : [],
			'important' => isset($note->important) ? $note->important : false,
		);

		if ($note->id) {
			// Update Site Note.
			$note_details['id'] = $note->id;
		}

		$note_id = $this->db->create($note_details);

		if (is_wp_error($note_id)) {
			wp_send_json(
				array(
					'published' => false,
					'message' => $note_id->get_error_message(),
				)
			);
		}

		wp_send_json(
			array(
				'published' => true,
				'message' => '',
				'id' => $note_id,
			)
		);
	}


/** Function dismiss() called by wp_ajax hooks: {'monsterinsights_notification_dismiss'} **/
/** No params detected :-/ **/


/** Function get_license() called by wp_ajax hooks: {'monsterinsights_vue_get_license'} **/
/** No params detected :-/ **/


/** Function monsterinsights_ai_charlie_pin_chat() called by wp_ajax hooks: {'monsterinsights_ai_charlie_pin_chat'} **/
/** Parameters found in function monsterinsights_ai_charlie_pin_chat(): {"post": ["chat_id", "pinned"]} **/
function monsterinsights_ai_charlie_pin_chat() {
	check_ajax_referer( 'mi-admin-nonce', 'nonce' );

	if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
		wp_send_json_error( array( 'message' => __( 'You do not have permission.', 'google-analytics-for-wordpress' ) ) );
	}

	$chat_id = isset( $_POST['chat_id'] ) ? sanitize_text_field( wp_unslash( $_POST['chat_id'] ) ) : '';
	$pinned  = isset( $_POST['pinned'] ) ? (bool) absint( $_POST['pinned'] ) : false;

	if ( empty( $chat_id ) ) {
		wp_send_json_error( array( 'message' => __( 'Missing chat ID.', 'google-analytics-for-wordpress' ) ) );
	}

	$group = monsterinsights_ai_charlie_cache_group();
	$key   = 'chat_' . $chat_id;
	$data  = monsterinsights_cache_get( $key, $group );

	if ( ! is_array( $data ) ) {
		wp_send_json_error( array( 'message' => __( 'Chat not found.', 'google-analytics-for-wordpress' ) ) );
	}

	$data['pinned']     = $pinned;
	$data['updated_at'] = time();

	monsterinsights_cache_set( $key, $data, $group, MONSTERINSIGHTS_AI_CHARLIE_CACHE_EXPIRY );

	// Keep user meta pinned list in sync.
	$user_id    = get_current_user_id();
	$pinned_ids = monsterinsights_ai_charlie_get_pinned_ids( $user_id );

	if ( $pinned ) {
		if ( ! in_array( $chat_id, $pinned_ids, true ) ) {
			if ( count( $pinned_ids ) >= MONSTERINSIGHTS_AI_CHARLIE_MAX_PINNED ) {
				wp_send_json_error( array( 'message' => __( 'You have reached the maximum number of saved conversations.', 'google-analytics-for-wordpress' ) ) );
				return;
			}
			$pinned_ids[] = $chat_id;
		}
	} else {
		$pinned_ids = array_values( array_filter( $pinned_ids, function( $id ) use ( $chat_id ) {
			return $id !== $chat_id;
		} ) );
	}

	update_user_meta( $user_id, MONSTERINSIGHTS_AI_CHARLIE_PINNED_META_KEY, $pinned_ids );

	wp_send_json_success( array( 'pinned' => $pinned ) );
}


/** Function get_notice_status() called by wp_ajax hooks: {'monsterinsights_vue_notice_status'} **/
/** Parameters found in function get_notice_status(): {"post": ["notice"]} **/
function get_notice_status() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		$notice_id = empty( $_POST['notice'] ) ? false : sanitize_text_field( wp_unslash( $_POST['notice'] ) );
		if ( ! $notice_id ) {
			wp_send_json_error();
		}
		$is_dismissed = MonsterInsights()->notices->is_dismissed( $notice_id );

		wp_send_json_success( array(
			'dismissed' => $is_dismissed,
		) );
	}


/** Function rauthenticate() called by wp_ajax hooks: {'nopriv_monsterinsights_rauthenticate'} **/
/** Parameters found in function rauthenticate(): {"request": ["v4", "network", "tt"]} **/
function rauthenticate() {
		// Check for missing params
		$reqd_args = array( 'key', 'token', 'miview', 'a', 'w', 'p', 'tt', 'network' );

		if ( empty( $_REQUEST['v4'] ) ) {
			$this->send_missing_args_error( 'v4' );
		}

		foreach ( $reqd_args as $arg ) {
			if ( empty( $_REQUEST[ $arg ] ) ) {
				$this->send_missing_args_error( $arg );
			}
		}

		if ( ! empty( $_REQUEST['network'] ) && 'network' === $_REQUEST['network'] && current_user_can( 'manage_network_options' ) ) {
			define( 'WP_NETWORK_ADMIN', true );
		}

		if ( ! $this->validate_tt( $_REQUEST['tt'] ) ) { // phpcs:ignore
			wp_send_json_error(
				array(
					'error'   => 'authenticate_invalid_tt',
					'message' => 'Invalid TT sent',
					'version' => MONSTERINSIGHTS_VERSION,
					'pro'     => monsterinsights_is_pro_version(),
				)
			);
		}

		// If the tt is validated, send a success response to trigger the regular auth process.
		wp_send_json_success();
	}


/** Function ajax_generate_setup_wizard_url() called by wp_ajax hooks: {'monsterinsights_generate_setup_wizard_url'} **/
/** No params detected :-/ **/


/** Function monsterinsights_ajax_activate_addon() called by wp_ajax hooks: {'monsterinsights_activate_addon'} **/
/** Parameters found in function monsterinsights_ajax_activate_addon(): {"post": ["plugin", "isnetwork"]} **/
function monsterinsights_ajax_activate_addon() {

	// Run a security check first.
	check_ajax_referer( 'monsterinsights-activate', 'nonce' );

	if ( ! current_user_can( 'activate_plugins' ) ) {
		wp_send_json( array(
			'error' => esc_html__( 'You are not allowed to activate plugins', 'google-analytics-for-wordpress' ),
		) );
	}

	// Activate the addon.
	if ( isset( $_POST['plugin'] ) ) {
		$plugin = esc_attr( $_POST['plugin'] );

		// $_POST['isnetwork'] arrives as a string, so a literal "false" (sent by the
		// frontend when not in network admin) is still truthy in PHP. Left unchecked
		// that forces network-wide activation, which on a single site writes the plugin
		// to active_sitewide_plugins and never loads it — activate_plugin() returns no
		// error, so the caller sees success while the plugin stays inactive. Coerce to
		// a real boolean so single-site activation takes the normal path.
		$is_network = isset( $_POST['isnetwork'] ) && filter_var( wp_unslash( $_POST['isnetwork'] ), FILTER_VALIDATE_BOOLEAN );

		if ( $is_network ) {
			$activate = activate_plugin( $plugin, null, true );
		} else {
			$activate = activate_plugin( $plugin );
		}

		/* Restrict thirt-party redirections on activation */
		if ( "userfeedback-lite/userfeedback.php" === $plugin ) {
			delete_transient( '_userfeedback_activation_redirect' );
		}

		if ( is_wp_error( $activate ) ) {
			echo wp_json_encode( array( 'error' => $activate->get_error_message() ) );
			wp_die();
		}

		do_action( 'monsterinsights_after_ajax_activate_addon', sanitize_text_field( $_POST['plugin'] ) );

		// Flush report caches so the newly activated addon's data is fetched fresh.
		monsterinsights_cache_flush_group( 'reports' );
		monsterinsights_cache_flush_group( 'overview' );
		monsterinsights_flag_flush_cache_registry();

		// FunnelKit Stripe Woo Payment Gateway activation.
		if ( 'funnelkit-stripe-woo-payment-gateway/funnelkit-stripe-woo-payment-gateway.php' === $plugin ) {
			monsterinsights_activate_plugin_funnelkit_stripe_woo_gateway();
		}
	}

	echo wp_json_encode( true );
	wp_die();
}


/** Function monsterinsights_get_floatbar() called by wp_ajax hooks: {'monsterinsights_get_floatbar'} **/
/** No params detected :-/ **/


/** Function dismiss_charitablewp_notice() called by wp_ajax hooks: {'monsterinsights_dismiss_charitablewp_notice'} **/
/** No params detected :-/ **/


/** Function process() called by wp_ajax hooks: {'nopriv_monsterinsights_connect_process'} **/
/** Parameters found in function process(): {"request": ["oth", "file"]} **/
function process() {
		$error = sprintf(
			/* translators: %1$s: Opening link tag, %2$s: Closing link tag. */
			esc_html__( 'Oops! We could not automatically install an upgrade. Please install manually by visiting %1$smonsterinsights.com%2$s.', 'google-analytics-for-wordpress' ),
			'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'could-not-upgrade', 'https://www.monsterinsights.com/' ) . '">',
			'</a>'
		);

		// verify params present (oth & download link).
		$post_oth = ! empty( $_REQUEST['oth'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['oth'] ) ) : '';
		$post_url = ! empty( $_REQUEST['file'] ) ? sanitize_url( wp_unslash( $_REQUEST['file'] ) ) : '';
		$license  = get_option( 'monsterinsights_connect', false );
		$network  = ! empty( $license['network'] ) ? (bool) $license['network'] : false;
		if ( empty( $post_oth ) || empty( $post_url ) ) {
			wp_send_json_error( $error );
		}
		// Verify oth.
		$oth = get_option( 'monsterinsights_connect_token' );
		if ( empty( $oth ) ) {
			wp_send_json_error( $error );
		}
		if ( hash_hmac( 'sha512', $oth, wp_salt() ) !== $post_oth ) {
			wp_send_json_error( $error );
		}
		// Delete so cannot replay.
		delete_option( 'monsterinsights_connect_token' );
		// Set the current screen to avoid undefined notices.
		set_current_screen( 'insights_page_monsterinsights_settings' );
		// Prepare variables.
		$url = esc_url_raw(
			add_query_arg(
				array(
					'page' => 'monsterinsights-settings',
				),
				admin_url( 'admin.php' )
			)
		);
		// Verify pro not activated.
		if ( monsterinsights_is_pro_version() ) {
			wp_send_json_success( esc_html__( 'Plugin installed & activated.', 'google-analytics-for-wordpress' ) );
		}
		// Verify pro not installed.
		$active = activate_plugin( 'google-analytics-premium/googleanalytics-premium.php', $url, $network, true );
		if ( ! is_wp_error( $active ) ) {
			deactivate_plugins( plugin_basename( MONSTERINSIGHTS_PLUGIN_FILE ), false, $network );
			wp_send_json_success( esc_html__( 'Plugin installed & activated.', 'google-analytics-for-wordpress' ) );
		}
		$creds = request_filesystem_credentials( $url, '', false, false, null );
		// Check for file system permissions.
		if ( false === $creds ) {
			wp_send_json_error( $error );
		}
		if ( ! WP_Filesystem( $creds ) ) {
			wp_send_json_error( $error );
		}
		// We do not need any extra credentials if we have gotten this far, so let's install the plugin.
		monsterinsights_require_upgrader();
		// Do not allow WordPress to search/download translations, as this will break JS output.
		remove_action( 'upgrader_process_complete', array( 'Language_Pack_Upgrader', 'async_upgrade' ), 20 );
		// Create the plugin upgrader with our custom skin.
		$installer = new MonsterInsights_Plugin_Upgrader( new MonsterInsights_Skin() );
		// Error check.
		if ( ! method_exists( $installer, 'install' ) ) {
			wp_send_json_error( $error );
		}

		// Check license key.
		if ( empty( $license['key'] ) ) {
			wp_send_json_error( new WP_Error( '403', esc_html__( 'You are not licensed.', 'google-analytics-for-wordpress' ) ) );
		}

		$installer->install( $post_url ); // phpcs:ignore
		// Flush the cache and return the newly installed plugin basename.
		wp_cache_flush();

		if ( $installer->plugin_info() ) {
			$plugin_basename = $installer->plugin_info();

			// Check this before deactivating plugin.
			$is_authed = MonsterInsights()->auth->is_authed();

			// Deactivate the lite version first.
			deactivate_plugins( plugin_basename( MONSTERINSIGHTS_PLUGIN_FILE ), false, $network );

			// Activate the plugin silently.
			$activated = activate_plugin( $plugin_basename, '', $network, true );
			if ( ! is_wp_error( $activated ) ) {
				// Pro upgrade successful.
				$over_time = get_option( 'monsterinsights_over_time', array() );

				if ( empty( $over_time['installed_pro'] ) ) {
					$over_time['installed_pro'] = time();
					if ( $is_authed ) {
						$over_time['connected_upgrade'] = time();
					}
					update_option( 'monsterinsights_over_time', $over_time );
				}

				wp_send_json_success( esc_html__( 'Plugin installed & activated.', 'google-analytics-for-wordpress' ) );
			} else {
				// Reactivate the lite plugin if pro activation failed.
				activate_plugin( plugin_basename( MONSTERINSIGHTS_PLUGIN_FILE ), '', $network, true );
				wp_send_json_error( esc_html__( 'Please activate MonsterInsights Pro from your WordPress plugins page.', 'google-analytics-for-wordpress' ) );
			}
		}
		wp_send_json_error( $error );
	}


/** Function save_filter() called by wp_ajax hooks: {'monsterinsights_save_report_filter'} **/
/** Parameters found in function save_filter(): {"post": ["filter"]} **/
function save_filter() {
		$this->verify_request( 'monsterinsights_save_settings' );

		$filter_json = isset( $_POST['filter'] ) ? wp_unslash( $_POST['filter'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified in verify_request() above.

		if ( empty( $filter_json ) ) {
			wp_send_json_error( array(
				'message' => __( 'No filter data provided.', 'monsterinsights' ),
			) );
			return;
		}

		$filter_data = json_decode( $filter_json, true );

		if ( ! is_array( $filter_data ) || empty( $filter_data['name'] ) ) {
			wp_send_json_error( array(
				'message' => __( 'Invalid filter data.', 'monsterinsights' ),
			) );
			return;
		}

		$sanitized = $this->sanitize_filter_data( $filter_data );
		$filter_id = $this->generate_filter_id();

		$new_filter = array_merge(
			array( 'id' => $filter_id ),
			$sanitized
		);

		$filters = $this->get_all_filters();
		$filters[ $filter_id ] = $new_filter;

		$this->save_all_filters( $filters );

		wp_send_json_success( $new_filter );
	}


/** Function get_user_included_metrics() called by wp_ajax hooks: {'monsterinsights_vue_get_user_included_metrics'} **/
/** No params detected :-/ **/


/** Function trash_notes() called by wp_ajax hooks: {'monsterinsights_vue_trash_notes'} **/
/** Parameters found in function trash_notes(): {"post": ["ids"]} **/
function trash_notes() {
		check_ajax_referer('mi-admin-nonce', 'nonce');

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			wp_send_json(
				array(
					'published' => false,
					'message' => __( "You don't have permission to update notes.", 'google-analytics-for-wordpress' ),
				)
			);
		}

		$ids = !empty($_POST['ids']) ? json_decode(html_entity_decode(wp_unslash($_POST['ids']))) : [];

		if (empty($ids)) {
			wp_send_json(
				array(
					'success' => false,
					'message' => __('Please choose a site note to trash!', 'google-analytics-for-wordpress'),
				)
			);
		}

		$blocked = false;
		foreach ($ids as $id) {
			if ( is_wp_error( $this->db->trash_note($id) ) ) {
				$blocked = true;
			}
		}

		if ( $blocked ) {
			wp_send_json(
				array(
					'success' => false,
					'message' => __( "You don't have permission to trash one or more of these notes.", 'google-analytics-for-wordpress' ),
				)
			);
		}

		wp_send_json(
			array(
				'success' => true,
				'message' => '',
			)
		);
	}


/** Function update_filter() called by wp_ajax hooks: {'monsterinsights_update_report_filter'} **/
/** Parameters found in function update_filter(): {"post": ["filter_id", "filter"]} **/
function update_filter() {
		$this->verify_request( 'monsterinsights_save_settings' );

		$filter_id   = isset( $_POST['filter_id'] ) ? sanitize_text_field( wp_unslash( $_POST['filter_id'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified in verify_request() above.
		$filter_json = isset( $_POST['filter'] ) ? wp_unslash( $_POST['filter'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified in verify_request() above.

		if ( empty( $filter_id ) || empty( $filter_json ) ) {
			wp_send_json_error( array(
				'message' => __( 'Missing required filter data.', 'monsterinsights' ),
			) );
			return;
		}

		$filters = $this->get_all_filters();

		if ( ! isset( $filters[ $filter_id ] ) ) {
			wp_send_json_error( array(
				'message' => __( 'Filter not found.', 'monsterinsights' ),
			) );
			return;
		}

		$filter_data = json_decode( $filter_json, true );

		if ( ! is_array( $filter_data ) ) {
			wp_send_json_error( array(
				'message' => __( 'Invalid filter data.', 'monsterinsights' ),
			) );
			return;
		}

		$sanitized = $this->sanitize_filter_data( $filter_data );

		$filters[ $filter_id ] = array_merge(
			$filters[ $filter_id ],
			$sanitized
		);

		$this->save_all_filters( $filters );

		wp_send_json_success( $filters[ $filter_id ] );
	}


/** Function monsterinsights_ai_charlie_delete_chat() called by wp_ajax hooks: {'monsterinsights_ai_charlie_delete_chat'} **/
/** Parameters found in function monsterinsights_ai_charlie_delete_chat(): {"post": ["chat_id"]} **/
function monsterinsights_ai_charlie_delete_chat() {
	check_ajax_referer( 'mi-admin-nonce', 'nonce' );

	if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
		wp_send_json_error( array( 'message' => __( 'You do not have permission.', 'google-analytics-for-wordpress' ) ) );
	}

	$chat_id = isset( $_POST['chat_id'] ) ? sanitize_text_field( wp_unslash( $_POST['chat_id'] ) ) : '';

	if ( empty( $chat_id ) ) {
		wp_send_json_error( array( 'message' => __( 'Missing chat ID.', 'google-analytics-for-wordpress' ) ) );
	}

	$group = monsterinsights_ai_charlie_cache_group();
	$key   = 'chat_' . $chat_id;

	monsterinsights_cache_delete( $key, $group );

	// Remove from pinned user meta if present.
	$user_id    = get_current_user_id();
	$pinned_ids = monsterinsights_ai_charlie_get_pinned_ids( $user_id );
	$pinned_ids = array_values( array_filter( $pinned_ids, function( $id ) use ( $chat_id ) {
		return $id !== $chat_id;
	} ) );
	update_user_meta( $user_id, MONSTERINSIGHTS_AI_CHARLIE_PINNED_META_KEY, $pinned_ids );

	wp_send_json_success();
}


/** Function delete_all_funnels() called by wp_ajax hooks: {'monsterinsights_overview_report_delete_all_funnel_filters'} **/
/** No params detected :-/ **/


/** Function monsterinsights_check_plugin_funnelkit_funnelkit_stripe_woo_gateway_configured() called by wp_ajax hooks: {'monsterinsights_funnelkit_stripe_woo_gateway_configured'} **/
/** No params detected :-/ **/


/** Function monsterinsights_ajax_dismiss_wpconsent_notice() called by wp_ajax hooks: {'monsterinsights_dismiss_wpconsent_notice'} **/
/** No params detected :-/ **/


/** Function monsterinsights_ajax_deactivate_addon() called by wp_ajax hooks: {'monsterinsights_deactivate_addon'} **/
/** Parameters found in function monsterinsights_ajax_deactivate_addon(): {"post": ["plugin", "isnetwork"]} **/
function monsterinsights_ajax_deactivate_addon() {

	// Run a security check first.
	check_ajax_referer( 'monsterinsights-deactivate', 'nonce' );

	if ( ! current_user_can( 'deactivate_plugins' ) ) {
		wp_send_json( array(
			'error' => esc_html__( 'You are not allowed to deactivate plugins', 'google-analytics-for-wordpress' ),
		) );
	}

	// Deactivate the addon.
	if ( isset( $_POST['plugin'] ) ) {
		if ( isset( $_POST['isnetwork'] ) && $_POST['isnetwork'] ) {
			$deactivate = deactivate_plugins( $_POST['plugin'], false, true );
		} else {
			$deactivate = deactivate_plugins( $_POST['plugin'] );
		}
	}

	do_action( 'monsterinsights_after_ajax_deactivate_addon', sanitize_text_field( $_POST['plugin'] ) );

	echo wp_json_encode( true );
	wp_die();
}


/** Function monsterinsights_ajax_get_backfill_cache() called by wp_ajax hooks: {'monsterinsights_get_backfill_cache'} **/
/** Parameters found in function monsterinsights_ajax_get_backfill_cache(): {"post": ["cache_group", "cache_key", "selected_metrics", "active_tab", "compare", "api_filters"]} **/
function monsterinsights_ajax_get_backfill_cache() {
	check_ajax_referer( 'mi-admin-nonce', 'nonce' );

	if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
		wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'google-analytics-for-wordpress' ) ) );
	}

	$allowed_groups = monsterinsights_backfill_cache_allowed_groups();

	$cache_group = ! empty( $_POST['cache_group'] ) ? sanitize_text_field( wp_unslash( $_POST['cache_group'] ) ) : '';
	$cache_key   = ! empty( $_POST['cache_key'] ) ? sanitize_text_field( wp_unslash( $_POST['cache_key'] ) ) : '';

	if ( empty( $cache_group ) || empty( $cache_key ) ) {
		wp_send_json_error( array( 'message' => __( 'Missing required cache parameters.', 'google-analytics-for-wordpress' ) ) );
	}

	if ( ! in_array( $cache_group, $allowed_groups, true ) ) {
		wp_send_json_error( array( 'message' => __( 'Invalid cache group.', 'google-analytics-for-wordpress' ) ) );
	}

	// Extract additional parameters for sample data filtering
	$extra_params = array();
	if ( ! empty( $_POST['selected_metrics'] ) ) {
		$metrics_raw = wp_unslash( $_POST['selected_metrics'] );
		$extra_params['selected_metrics'] = is_string( $metrics_raw ) ? json_decode( $metrics_raw, true ) : $metrics_raw;
	}
	if ( ! empty( $_POST['active_tab'] ) ) {
		$extra_params['active_tab'] = sanitize_text_field( wp_unslash( $_POST['active_tab'] ) );
	}
	if ( isset( $_POST['compare'] ) ) {
		$extra_params['compare'] = filter_var( wp_unslash( $_POST['compare'] ), FILTER_VALIDATE_BOOLEAN );
	}
	if ( ! empty( $_POST['api_filters'] ) ) {
		$api_filters_raw = wp_unslash( $_POST['api_filters'] );
		if ( is_string( $api_filters_raw ) ) {
			$decoded = json_decode( $api_filters_raw, true );
			if ( is_array( $decoded ) ) {
				$extra_params['api_filters'] = $decoded;
			}
		} elseif ( is_array( $api_filters_raw ) ) {
			$extra_params['api_filters'] = $api_filters_raw;
		}
	}

	/**
	 * Filter to intercept backfill cache requests with sample data.
	 *
	 * When sample data mode is enabled via _monsterinsights-utils plugin,
	 * this filter returns sample data instead of fetching from cache/API.
	 *
	 * @since 9.11.0
	 *
	 * @param mixed  $data         The cached data (null to continue normal flow).
	 * @param string $cache_key    The cache key identifier.
	 * @param string $cache_group  The cache group (e.g., 'overview').
	 * @param array  $extra_params Additional parameters (selected_metrics, active_tab, compare, api_filters).
	 */
	$sample_data = apply_filters( 'monsterinsights_get_backfill_cache', null, $cache_key, $cache_group, $extra_params );

	if ( null !== $sample_data ) {
		wp_send_json_success( $sample_data );
	}

	$data = monsterinsights_cache_get( $cache_key, $cache_group );

	if ( false === $data ) {
		wp_send_json_error( array( 'message' => __( 'Cache miss.', 'google-analytics-for-wordpress' ) ) );
	}

	wp_send_json_success( $data );
}


/** Function monsterinsights_ai_charlie_get_saved_chats() called by wp_ajax hooks: {'monsterinsights_ai_charlie_get_saved_chats'} **/
/** No params detected :-/ **/


/** Function import_notes_from_ga4() called by wp_ajax hooks: {'monsterinsights_vue_import_notes'} **/
/** Parameters found in function import_notes_from_ga4(): {"post": ["action"]} **/
function import_notes_from_ga4() {
		if (
			! isset( $_POST['action'] ) ||
			'monsterinsights_vue_import_notes' !== $_POST['action']
		) {
			return;
		}

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			wp_die(
				esc_html__(
					'You do not have sufficient permissions to access this page.',
					'google-analytics-for-wordpress'
				)
			);
		}

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		// Check if user is authenticated.
		if (
			! ( MonsterInsights()->auth->is_authed() || MonsterInsights()->auth->is_network_authed() )
		) {
			wp_send_json_error(
				array(
					'message' => esc_html__(
						'You must be properly authenticated with MonsterInsights to import annotations.',
						'google-analytics-for-wordpress'
					),
				)
			);
		}

		// Prepare API request options.
		$api_options = array();

		// Add network flag if needed.
		if (
			! MonsterInsights()->auth->is_authed() &&
			MonsterInsights()->auth->is_network_authed()
		) {
			$api_options['network'] = true;
		}

		// Create API request for GET method.
		$api = new MonsterInsights_API_Request(
			'analytics/reports/annotations/',
			$api_options,
			'GET'
		);
		
		// Make the API request.
		$response = $api->request();

		if ( is_wp_error( $response ) ) {
			wp_send_json_error(
				array(
					'message' => $response->get_error_message(),
				)
			);
		}
		// Check if response contains annotations data. "Nothing to import" is a
		// completed sync, not an error — persist the flag so the front-end stops
		// re-running the import on every page load.
		if (
			empty( $response ) ||
			! isset( $response['data']['annotations'] ) ||
			empty( $response['data']['annotations'] )
		) {
			monsterinsights_update_option( 'site_notes_import_synced', 1 );
			wp_send_json_success(
				array(
					'message' => __(
						'No annotations found to import.',
						'google-analytics-for-wordpress'
					),
				)
			);
		}

		$imported_count = 0;
		$errors         = array();
		$skipped_count  = 0;

		// Process each annotation and create site notes.
		foreach ( $response['data']['annotations'] as $annotation ) {
			// Check if annotation already exists by GA4 ID.
			$ga4_annotation_id = isset( $annotation['id'] ) ? sanitize_text_field( $annotation['id'] ) : '';
			
			if ( ! empty( $ga4_annotation_id ) && $this->annotation_exists( $ga4_annotation_id ) ) {
				$skipped_count++;
				continue;
			}

			// Prepare note details based on annotation data.
			$note_details = array(
				'note'      => isset( $annotation['title'] ) ? sanitize_text_field( $annotation['title'] ) : '',
				'category'  => 0, // Default category, can be mapped later if needed.
				'date'      => $this->format_annotation_date( $annotation['annotationDate'] ),
				'medias'    => array(),
				'important' => false, // GA4 doesn't have important flag, default to false
			);

			// Skip if note is empty.
			if ( empty( $note_details['note'] ) ) {
				$errors[] = sprintf(
					/* translators: %s: annotation ID */
					__(
						'Skipped annotation with empty title (ID: %s)',
						'google-analytics-for-wordpress'
					),
					$ga4_annotation_id ?: 'unknown'
				);
				continue;
			}

			// Skip if a note with the same title + date already exists locally
			// (created manually, or by a prior sync that didn't link the GA4 id).
			// This stops the import from re-creating duplicates of notes it can't
			// match by `_ga4_annotation_id` alone.
			$existing_note_id = $this->find_note_by_title_date( $note_details['note'], $note_details['date'] );
			if ( $existing_note_id ) {
				if ( ! empty( $ga4_annotation_id ) && '' === (string) get_post_meta( $existing_note_id, '_ga4_annotation_id', true ) ) {
					update_post_meta( $existing_note_id, '_ga4_annotation_id', $ga4_annotation_id );
				}
				$skipped_count++;
				continue;
			}

			// Create the note using the existing create_note method.
			$note_id = $this->create_note( $note_details );

			if ( is_wp_error( $note_id ) ) {
				$errors[] = sprintf(
					/* translators: %1$s: annotation title, %2$s: error message */
					__(
						'Failed to import annotation "%1$s": %2$s',
						'google-analytics-for-wordpress'
					),
					$note_details['note'],
					$note_id->get_error_message()
				);
			} else {
				// Store the GA4 annotation ID as post meta for future duplicate checking.
				if ( ! empty( $ga4_annotation_id ) ) {
					update_post_meta( $note_id, '_ga4_annotation_id', $ga4_annotation_id );
				}
				$imported_count++;
			}
		}

		// Prepare response message.
		$message = sprintf(
			/* translators: %d: number of annotations successfully imported */
			__(
				'Successfully imported %d annotations.',
				'google-analytics-for-wordpress'
			),
			$imported_count
		);

		if ( $skipped_count > 0 ) {
			$message .= ' ' . sprintf(
				/* translators: %d: number of annotations skipped */
				__( '%d annotations were skipped (already exist).', 'google-analytics-for-wordpress' ),
				$skipped_count
			);
		}

		if ( ! empty( $errors ) ) {
			$message .= ' ' . sprintf(
				/* translators: %d: number of annotations that failed to import */
				__(
					'%d annotations could not be imported.',
					'google-analytics-for-wordpress'
				),
				count( $errors )
			);
		}

		monsterinsights_update_option( 'site_notes_import_synced', 1 );

		// Return success response.
		wp_send_json_success(
			array(
				'message'        => $message,
				'imported_count' => $imported_count,
				'skipped_count'  => $skipped_count,
				'error_count'    => count( $errors ),
				'errors'         => $errors,
				'data'           => $response,
			)
		);
	}


/** Function monsterinsights_ajax_install_addon() called by wp_ajax hooks: {'monsterinsights_install_addon'} **/
/** Parameters found in function monsterinsights_ajax_install_addon(): {"post": ["plugin"]} **/
function monsterinsights_ajax_install_addon() {

	// Run a security check first.
	check_ajax_referer( 'monsterinsights-install', 'nonce' );

	if ( ! monsterinsights_can_install_plugins() ) {
		wp_send_json( array(
			'error' => esc_html__( 'You are not allowed to install plugins', 'google-analytics-for-wordpress' ),
		) );
	}

	// Install the addon.
	if ( isset( $_POST['plugin'] ) ) {
		$download_url = $_POST['plugin'];
		global $hook_suffix;

		// Set the current screen to avoid undefined notices.
		set_current_screen();

		// Prepare variables.
		$method = '';
		$url    = add_query_arg(
			array(
				'page' => 'monsterinsights-settings'
			),
			admin_url( 'admin.php' )
		);
		$url    = esc_url( $url );

		// Start output bufferring to catch the filesystem form if credentials are needed.
		ob_start();
		if ( false === ( $creds = request_filesystem_credentials( $url, $method, false, false, null ) ) ) {
			$form = ob_get_clean();
			echo wp_json_encode( array( 'form' => $form ) );
			wp_die();
		}

		// If we are not authenticated, make it happen now.
		if ( ! WP_Filesystem( $creds ) ) {
			ob_start();
			request_filesystem_credentials( $url, $method, true, false, null );
			$form = ob_get_clean();
			echo wp_json_encode( array( 'form' => $form ) );
			wp_die();
		}

		// We do not need any extra credentials if we have gotten this far, so let's install the plugin.
		monsterinsights_require_upgrader( false );

		// Create the plugin upgrader with our custom skin.
		$installer = new Plugin_Upgrader( $skin = new MonsterInsights_Skin() );
		$installer->install( $download_url );

		// Flush the cache and return the newly installed plugin basename.
		wp_cache_flush();
		if ( $installer->plugin_info() ) {
			$plugin_basename = $installer->plugin_info();
			echo wp_json_encode( array( 'plugin' => $plugin_basename ) );
			wp_die();
		}
	}

	// Send back a response.
	echo wp_json_encode( true );
	wp_die();

}


/** Function manual_check() called by wp_ajax hooks: {'monsterinsights_manual_product_feed_check'} **/
/** No params detected :-/ **/


/** Function save_category() called by wp_ajax hooks: {'monsterinsights_vue_save_category'} **/
/** Parameters found in function save_category(): {"post": ["category"]} **/
function save_category() {
		check_ajax_referer('mi-admin-nonce', 'nonce');

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			wp_send_json(
				array(
					'published' => false,
					'message' => __( "You don't have permission to update categories.", 'google-analytics-for-wordpress' ),
				)
			);
		}

		$category = !empty($_POST['category']) ? json_decode(html_entity_decode(wp_unslash($_POST['category']))) : [];

		if (empty($category->name)) {
			wp_send_json(
				array(
					'published' => false,
					'message' => __('Please add a category name', 'google-analytics-for-wordpress'),
				)
			);
		}

		if (200 < mb_strlen($category->name)) {
			wp_send_json(
				array(
					'published' => false,
					'message' => __('You can\'t exceed the 200 characters length for each site note.', 'google-analytics-for-wordpress'),
				)
			);
		}

		$category_id = $this->db->create_category(array(
			'id' => $category->id,
			'name' => $category->name,
			'background_color' => sanitize_hex_color($category->background_color),
		));

		if (is_wp_error($category_id)) {
			wp_send_json(
				array(
					'published' => false,
					'message' => $category_id->get_error_message(),
				)
			);
		}

		wp_send_json(
			array(
				'published' => true,
				'message' => '',
				'id' => $category_id,
			)
		);
	}


/** Function monsterinsights_get_aiseo_cta_status() called by wp_ajax hooks: {'monsterinsights_get_aiseo_cta_status'} **/
/** No params detected :-/ **/


/** Function test_check_tracking_code() called by wp_ajax hooks: {'health-check-monsterinsights-test_tracking_code'} **/
/** No params detected :-/ **/


/** Function get_post_types() called by wp_ajax hooks: {'monsterinsights_get_post_types'} **/
/** No params detected :-/ **/


/** Function save_notification() called by wp_ajax hooks: {'monsterinsights_notification_save'} **/
/** Parameters found in function save_notification(): {"post": ["id"]} **/
function save_notification() {
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! $this->has_access() || empty( $_POST['id'] ) ) {
			wp_send_json_error();
		}

		$id     = sanitize_text_field( wp_unslash( $_POST['id'] ) );
		$option = $this->get_option();
		$saved  = null;

		// Search in feed, events, and dismissed.
		foreach ( array( 'feed', 'events', 'dismissed' ) as $type ) {
			if ( ! is_array( $option[ $type ] ) ) {
				continue;
			}
			foreach ( $option[ $type ] as $key => $notification ) {
				if ( $notification['id'] == $id ) { // phpcs:ignore WordPress.PHP.StrictComparisons
					$current_saved                     = ! empty( $notification['saved'] );
					$option[ $type ][ $key ]['saved']   = ! $current_saved;
					$saved                             = ! $current_saved;
					break 2;
				}
			}
		}

		if ( null === $saved ) {
			wp_send_json_error();
		}

		update_option( $this->option_name, $option, false );

		wp_send_json_success( array( 'saved' => $saved ) );
	}


/** Function dismiss_promo() called by wp_ajax hooks: {'monsterinsights_vue_dismiss_promo'} **/
/** Parameters found in function dismiss_promo(): {"post": ["promo_id"]} **/
function dismiss_promo() {
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		// Gate on the same capability that controls who can see these promos. The write
		// only touches the current user's own meta, but this keeps parity with the view gate.
		if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'You do not have permission to do this.', 'google-analytics-for-wordpress' ) ) );

			return;
		}

		$promo_id = isset( $_POST['promo_id'] ) ? sanitize_key( wp_unslash( $_POST['promo_id'] ) ) : '';

		if ( empty( $promo_id ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Missing promo id.', 'google-analytics-for-wordpress' ) ) );

			return;
		}

		$dismissed = monsterinsights_get_dismissed_promos();

		if ( ! in_array( $promo_id, $dismissed, true ) ) {
			$dismissed[] = $promo_id;
			update_user_meta( get_current_user_id(), 'monsterinsights_dismissed_promos', $dismissed );
		}

		wp_send_json_success( $dismissed );
	}


/** Function monsterinsights_dismiss_tracking_notice() called by wp_ajax hooks: {'monsterinsights_dismiss_tracking_notice'} **/
/** No params detected :-/ **/


/** Function monsterinsights_user_journey_demo_report_ajax() called by wp_ajax hooks: {'monsterinsights_user_journey_report'} **/
/** No params detected :-/ **/


/** Function test_check_connection() called by wp_ajax hooks: {'health-check-monsterinsights-test_connection'} **/
/** No params detected :-/ **/


/** Function monsterinsights_ajax_dismiss_notice() called by wp_ajax hooks: {'monsterinsights_ajax_dismiss_notice'} **/
/** Parameters found in function monsterinsights_ajax_dismiss_notice(): {"post": ["notice"]} **/
function monsterinsights_ajax_dismiss_notice() {

	// Run a security check first.
	check_ajax_referer( 'monsterinsights-dismiss-notice', 'nonce' );

	if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
		echo wp_json_encode( false );
		wp_die();
	}

	// Deactivate the notice
	if ( isset( $_POST['notice'] ) ) {
		// Init the notice class and mark notice as deactivated
		MonsterInsights()->notices->dismiss( sanitize_key( wp_unslash( $_POST['notice'] ) ) );

		// Return true
		echo wp_json_encode( true );
		wp_die();
	}

	// If here, an error occurred
	echo wp_json_encode( false );
	wp_die();

}


/** Function ajax_button_click_track() called by wp_ajax hooks: {'monsterinsights_vue_setup_checklist_click_track'} **/
/** Parameters found in function ajax_button_click_track(): {"post": ["button_key"]} **/
function ajax_button_click_track() {
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! isset( $_POST['button_key'] ) ) {
			wp_send_json_error();
		}

		$button_key = sanitize_text_field( wp_unslash( $_POST['button_key'] ) );

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		$default_checklist = $this->default_checklist();

		$checklist = get_option( 'monsterinsights_setup_checklist', array() );

		if ( ! $checklist ) {
			$checklist = $default_checklist;
		}

		switch ( $button_key ) {
			case 'settings_dismiss':
				$checklist['settings']['dismiss'] = true;
				break;

			default:
				$checklist[ $button_key ] = true;
				break;
		}

		update_option( 'monsterinsights_setup_checklist', $checklist );

		wp_send_json_success();
	}


/** Function get_settings() called by wp_ajax hooks: {'monsterinsights_vue_get_settings'} **/
/** No params detected :-/ **/


/** Function monsterinsights_handle_get_plugin_info() called by wp_ajax hooks: {'nopriv_monsterinsights_get_plugin_info'} **/
/** Parameters found in function monsterinsights_handle_get_plugin_info(): {"request": ["key"]} **/
function monsterinsights_handle_get_plugin_info() {

	$auth = MonsterInsights()->auth;

	//  Authenticate with public key
	$key = !empty($_REQUEST['key']) ? sanitize_text_field($_REQUEST['key']) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	$site_key = is_network_admin() ? $auth->get_network_key() : $auth->get_key();

	if ( !hash_equals( $site_key, $key ) ) {
		wp_send_json_error([
			'error'     => __( 'Invalid site key.', 'google-analytics-for-wordpress' )
		], 401);
	}

	$v4 = is_network_admin() ? $auth->get_network_v4_id() :  $auth->get_v4_id();
	$has_secret = is_network_admin() ?
		!empty( $auth->get_network_measurement_protocol_secret() ) :
		!empty( $auth->get_measurement_protocol_secret() );

	wp_send_json([
		'v4'                => $v4,
		'has_mp_secret'     => $has_secret,
		'plugin_version'    => MonsterInsights()->version
	]);
}


/** Function get_note() called by wp_ajax hooks: {'monsterinsights_vue_get_note'} **/
/** Parameters found in function get_note(): {"post": ["id"]} **/
function get_note() {
		check_ajax_referer('mi-admin-nonce', 'nonce');

		if ( ! current_user_can( 'monsterinsights_view_dashboard' ) && ! current_user_can( 'monsterinsights_save_settings' ) ) {
			wp_send_json(
				array(
					'published' => false,
					'message' => __( "You don't have permission to view notes.", 'google-analytics-for-wordpress' ),
				)
			);
		}

		$id = !empty($_POST['id']) ? intval($_POST['id']) : null;
		$item = $this->db->get($id);

		if (is_wp_error($item)) {
			wp_send_json(
				array(
					'success' => false,
					'message' => $item->get_error_message(),
				)
			);
		}

		wp_send_json($item);
	}


/** Function maybe_add_notifications() called by wp_ajax hooks: {'monsterinsights_vue_get_notifications'} **/
/** No params detected :-/ **/


/** Function get_install_errors() called by wp_ajax hooks: {'monsterinsights_onboarding_get_errors'} **/
/** No params detected :-/ **/


/** Function ajax_get_index_progress() called by wp_ajax hooks: {'monsterinsights_sharedcount_get_index_progress'} **/
/** No params detected :-/ **/


/** Function handle_request() called by wp_ajax hooks: {'monsterinsights_get_overview_data'} **/
/** Parameters found in function handle_request(): {"post": ["date_range"]} **/
function handle_request() {
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to view this data.', 'google-analytics-for-wordpress' ) ) );
		}

		$date_range = ! empty( $_POST['date_range'] ) ? json_decode( stripslashes( $_POST['date_range'] ), true ) : array();

		if ( empty( $date_range['start'] ) || empty( $date_range['end'] ) ) {
			$date_range = $this->get_default_date_range( 30 );
		} else {
			$end_date = strtotime( $date_range['end'] );
			$today    = strtotime( 'today' );
			if ( $end_date > $today ) {
				$date_range['end'] = gmdate( 'Y-m-d', $today );
			}
		}

		$api          = new MonsterInsights_API_Overview();
		$api_response = $api->get_overview( $date_range['start'], $date_range['end'] );

		if ( is_wp_error( $api_response ) ) {
			wp_send_json_error( array( 'message' => $api_response->get_error_message() ) );
		}

		if ( $api_response instanceof MonsterInsights_API_Error ) {
			wp_send_json_error( array( 'message' => $api_response->get_error_message() ) );
		}

		$overview_data = isset( $api_response[ MonsterInsights_API_Overview::QUERY_ID ] )
			? $api_response[ MonsterInsights_API_Overview::QUERY_ID ]
			: array();

		wp_send_json_success( array(
			'date_range' => $date_range,
			'overview'   => $overview_data,
		) );
	}


/** Function update_settings() called by wp_ajax hooks: {'monsterinsights_vue_update_settings'} **/
/** Parameters found in function update_settings(): {"post": ["setting", "value"]} **/
function update_settings() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		if ( isset( $_POST['setting'] ) ) {
			$setting = sanitize_text_field( wp_unslash( $_POST['setting'] ) );

			// Prevent non-admin users from modifying access-control settings.
			if ( monsterinsights_is_admin_only_setting( $setting ) && ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( array(
					'message' => esc_html__( 'You do not have permission to update this setting.', 'google-analytics-for-wordpress' ),
				) );
			}

			if ( isset( $_POST['value'] ) ) {
				$value = $this->handle_sanitization( $setting, $_POST['value'] ); // phpcs:ignore
				monsterinsights_update_option( $setting, $value );
				do_action( 'monsterinsights_after_update_settings', $setting, $value );
			} else {
				monsterinsights_update_option( $setting, false );
				do_action( 'monsterinsights_after_update_settings', $setting, false );
			}
		}

		wp_send_json_success();

	}


/** Function get_ajax_output() called by wp_ajax hooks: {'nopriv_monsterinsights_popular_posts_get_widget_output', 'monsterinsights_popular_posts_get_widget_output'} **/
/** Parameters found in function get_ajax_output(): {"post": ["data"]} **/
function get_ajax_output() {
		check_ajax_referer( 'mi-popular-posts' );

		if ( empty( $_POST['data'] ) || ! is_array( $_POST['data'] ) ) {
			return;
		}

		$html         = array();
		$widgets_args = $_POST['data']; // phpcs:ignore

		foreach ( $widgets_args as $args ) {
			$args = json_decode( sanitize_text_field( wp_unslash( $args ) ), true );
			if ( ! empty( $args['type'] ) ) {
				$type            = ucfirst( $args['type'] );
				$widget_function = function_exists( 'MonsterInsights_Popular_Posts_' . $type ) ? call_user_func( 'MonsterInsights_Popular_Posts_' . $type ) : false;
				if ( $widget_function ) {
					$html[] = $widget_function->get_rendered_html( $args );
				}
			}
		}

		wp_send_json( $html );
	}


/** Function onboarding_maybe_authenticate() called by wp_ajax hooks: {'nopriv_onboarding_monsterinsights_maybe_authenticate'} **/
/** Parameters found in function onboarding_maybe_authenticate(): {"request": ["nonce", "isnetwork"]} **/
function onboarding_maybe_authenticate() {
		if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'onboarding' ) ) { //phpcs:ignore
			wp_send_json_error( array( 'message' => 'Nonce not valid' ) );
		}
		if ( ! empty( $_REQUEST['isnetwork'] ) && $_REQUEST['isnetwork'] ) { // phpcs:ignore
			if ( ! current_user_can( 'manage_network_options' ) ) {
				wp_send_json_error( array(
					'error' => esc_html__( 'You do not have permission to update network settings.', 'google-analytics-for-wordpress' ),
				) );
			}
			define( 'WP_NETWORK_ADMIN', true );
		}

		// Only for Pro users, require a license key to be entered first so we can link to things.
		if ( monsterinsights_is_pro_version() ) {
			$valid = is_network_admin() ? MonsterInsights()->license->is_network_licensed() : MonsterInsights()->license->is_site_licensed();
			if ( ! $valid ) {
				wp_send_json_error( array( 'message' => __( 'Cannot authenticate. Please enter a valid, active license key for MonsterInsights Pro into the settings page.', 'google-analytics-for-wordpress' ) ) );
			}
		}

		if ( ! $this->is_network_admin() && MonsterInsights()->auth->is_authed() ) {
			$message = sprintf(
				/* translators: %1$s: Opening support link tag, %2$s: Closing support link tag. */
				__( 'Oops! There has been an error authenticating. Please try again in a few minutes. If the problem persists, please %1$scontact our support%2$s team.', 'google-analytics-for-wordpress' ),
				'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'error-authenticating', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		} else if ( $this->is_network_admin() && MonsterInsights()->auth->is_network_authed() ) {
			$message = sprintf(
				/* translators: %1$s: Opening support link tag, %2$s: Closing support link tag. */
				__( 'Oops! There has been an error authenticating. Please try again in a few minutes. If the problem persists, please %1$scontact our support%2$s team.', 'google-analytics-for-wordpress' ),
				'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'error-authenticating', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		}

		$sitei = $this->get_sitei();

		$site_type = monsterinsights_get_option( 'site_type' );

		$auth_request_args = array(
			'tt'        => $this->get_tt(),
			'sitei'     => $sitei,
			'miversion' => MONSTERINSIGHTS_VERSION,
			'ajaxurl'   => admin_url( 'admin-ajax.php' ),
			'network'   => is_network_admin() ? 'network' : 'site',
			'siteurl'   => is_network_admin() ? network_admin_url() : home_url(),
			'return'    => is_network_admin() ? network_admin_url( 'admin.php?page=monsterinsights_network' ) : admin_url( 'admin.php?page=monsterinsights_settings' ),
			'testurl'   => 'https://' . monsterinsights_get_api_url() . 'test/',
			'site_type' => $site_type ?? 'business',
		);
		$auth_request_args = apply_filters( 'monsterinsights_auth_request_body', $auth_request_args );

		$siteurl = add_query_arg( $auth_request_args, $this->get_route( 'https://' . monsterinsights_get_api_url() . 'auth/new/{type}' ) );

		if ( monsterinsights_is_pro_version() ) {
			$key     = is_network_admin() ? MonsterInsights()->license->get_network_license_key() : MonsterInsights()->license->get_site_license_key();
			$siteurl = add_query_arg( 'license', $key, $siteurl );
		}

		$siteurl = apply_filters( 'monsterinsights_maybe_authenticate_siteurl', $siteurl );
		wp_send_json_success( array( 'redirect' => $siteurl ) );
	}


/** Function monsterinsights_ai_charlie_save_chat() called by wp_ajax hooks: {'monsterinsights_ai_charlie_save_chat'} **/
/** Parameters found in function monsterinsights_ai_charlie_save_chat(): {"post": ["chat"]} **/
function monsterinsights_ai_charlie_save_chat() {
	check_ajax_referer( 'mi-admin-nonce', 'nonce' );

	if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
		wp_send_json_error( array( 'message' => __( 'You do not have permission.', 'google-analytics-for-wordpress' ) ) );
	}

	$raw = isset( $_POST['chat'] ) ? wp_unslash( $_POST['chat'] ) : '';
	$chat = json_decode( $raw, true );

	if ( empty( $chat ) || empty( $chat['id'] ) || empty( $chat['messages'] ) ) {
		wp_send_json_error( array( 'message' => __( 'Invalid chat data.', 'google-analytics-for-wordpress' ) ) );
	}

	$chat_id = sanitize_text_field( $chat['id'] );
	$group   = monsterinsights_ai_charlie_cache_group();
	$key     = 'chat_' . $chat_id;

	// Sanitize each message in the array.
	$sanitized_messages = array();
	foreach ( $chat['messages'] as $msg ) {
		if ( ! is_array( $msg ) || ! isset( $msg['text'] ) ) {
			continue;
		}
		$sanitized_msg = array(
			'id'       => isset( $msg['id'] ) ? absint( $msg['id'] ) : 0,
			'type'     => isset( $msg['type'] ) ? sanitize_text_field( $msg['type'] ) : '',
			'text'     => wp_kses_post( $msg['text'] ),
			'format'   => isset( $msg['format'] ) ? sanitize_text_field( $msg['format'] ) : '',
			'feedback' => isset( $msg['feedback'] ) ? sanitize_text_field( $msg['feedback'] ) : null,
		);

		if ( isset( $msg['runId'] ) ) {
			$sanitized_msg['runId'] = sanitize_text_field( $msg['runId'] );
		}

		if ( ! empty( $msg['insights'] ) && is_array( $msg['insights'] ) ) {
			$sanitized_insights = array();
			foreach ( $msg['insights'] as $insight ) {
				if ( ! is_array( $insight ) ) {
					continue;
				}
				$sanitized_insights[] = array(
					'label'  => isset( $insight['label'] ) ? sanitize_text_field( $insight['label'] ) : '',
					'action' => isset( $insight['action'] ) ? sanitize_text_field( $insight['action'] ) : '',
				);
			}
			$sanitized_msg['insights'] = $sanitized_insights;
		}

		$sanitized_messages[] = $sanitized_msg;
	}

	// Preserve pinned state from existing record if not provided.
	$existing = monsterinsights_cache_get( $key, $group );
	$pinned   = false;
	if ( isset( $chat['pinned'] ) ) {
		$pinned = (bool) $chat['pinned'];
	} elseif ( is_array( $existing ) && ! empty( $existing['pinned'] ) ) {
		$pinned = true;
	}

	$data = array(
		'id'         => $chat_id,
		'title'      => isset( $chat['title'] ) ? sanitize_text_field( $chat['title'] ) : '',
		'preview'    => isset( $chat['preview'] ) ? sanitize_text_field( $chat['preview'] ) : '',
		'messages'   => $sanitized_messages,
		'pinned'     => $pinned,
		'created_at' => isset( $chat['created_at'] ) ? absint( $chat['created_at'] ) : time(),
		'updated_at' => time(),
	);

	$result = monsterinsights_cache_set( $key, $data, $group, MONSTERINSIGHTS_AI_CHARLIE_CACHE_EXPIRY );

	if ( false === $result ) {
		wp_send_json_error( array( 'message' => __( 'Failed to save chat.', 'google-analytics-for-wordpress' ) ) );
	}

	wp_send_json_success( array( 'id' => $chat_id ) );
}


/** Function handle_relay_mp_token_push() called by wp_ajax hooks: {'nopriv_monsterinsights_push_mp_token'} **/
/** Parameters found in function handle_relay_mp_token_push(): {"post": ["mp_token", "timestamp", "signature"]} **/
function handle_relay_mp_token_push() {
		$mp_token  = sanitize_text_field( wp_unslash( $_POST['mp_token'] ) ); // phpcs:ignore
		$timestamp = (int) sanitize_text_field( wp_unslash( $_POST['timestamp'] ) ); // phpcs:ignore
		$signature = sanitize_text_field( wp_unslash( $_POST['signature'] ) ); // phpcs:ignore

		// check if expired
		if ( time() > $timestamp + 1000 ) {
			wp_send_json_error( new WP_Error( 'monsterinsights_mp_token_timestamp_expired' ) );
		}

		// Check hashed signature
		$auth = MonsterInsights()->auth;

		$is_network = is_multisite();
		$public_key = $is_network
			? $auth->get_network_key()
			: $auth->get_key();

		if ( empty( $public_key ) ) {
			wp_send_json_error( new WP_Error( 'monsterinsights_mp_token_no_public_key' ) );
		}

		$hashed_data = array(
			'mp_token'  => !empty($_POST['mp_token']) ? sanitize_text_field( wp_unslash( $_POST['mp_token'] ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification.Missing
			'timestamp' => $timestamp,
		);

		// These `hash_` functions are polyfilled by WP in wp-includes/compat.php
		$expected_signature = hash_hmac( 'md5', http_build_query( $hashed_data ), $public_key );
		if ( ! hash_equals( $signature, $expected_signature ) ) {
			wp_send_json_error( new WP_Error( 'monsterinsights_mp_token_invalid_signature' ) );
		}

		// Save measurement protocol token
		if ( $is_network ) {
			$auth->set_network_measurement_protocol_secret( $mp_token );
		} else {
			$auth->set_measurement_protocol_secret( $mp_token );
		}
		wp_send_json_success();
	}


/** Function __return_false() called by wp_ajax hooks: {'monsterinsights_user_journey_report_filter_params'} **/
/** No function found :-/ **/


/** Function remind_me() called by wp_ajax hooks: {'monsterinsights_notification_remind_me'} **/
/** Parameters found in function remind_me(): {"post": ["id"]} **/
function remind_me() {
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! $this->has_access() || empty( $_POST['id'] ) ) {
			wp_send_json_error();
		}

		$id      = sanitize_text_field( wp_unslash( $_POST['id'] ) );
		$user_id = get_current_user_id();

		$snoozed = get_user_meta( $user_id, 'monsterinsights_notifications_snoozed', true );
		if ( ! is_array( $snoozed ) ) {
			$snoozed = array();
		}

		if ( ! in_array( $id, $snoozed, true ) ) {
			$snoozed[] = $id;
		}

		update_user_meta( $user_id, 'monsterinsights_notifications_snoozed', $snoozed );

		wp_send_json_success();
	}


/** Function update_included_metrics() called by wp_ajax hooks: {'monsterinsights_vue_update_included_metrics'} **/
/** Parameters found in function update_included_metrics(): {"post": ["selected_metrics"]} **/
function update_included_metrics() {
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );
		if ( isset( $_POST['selected_metrics'] ) ) {
			$selected_metrics = sanitize_text_field( wp_unslash( $_POST['selected_metrics'] ) );
			update_user_meta( get_current_user_id(), 'monsterinsights_included_metrics', $selected_metrics );

			// Note: No cache flushing needed since metrics are part of cache key (since 9.11.0)
			// Different metrics = different cache key, so old cache entries expire naturally
		}
		wp_send_json_success();
	}


/** Function check_eea_compliance() called by wp_ajax hooks: {'monsterinsights_vue_check_eea_compliance'} **/
/** No params detected :-/ **/


/** Function ajax_get_notifications() called by wp_ajax hooks: {'monsterinsights_vue_get_notifications'} **/
/** No params detected :-/ **/


/** Function update_manual_v4() called by wp_ajax hooks: {'monsterinsights_update_manual_v4'} **/
/** Parameters found in function update_manual_v4(): {"post": ["manual_v4_code"], "request": ["isnetwork"]} **/
function update_manual_v4() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		$manual_v4_code = isset( $_POST['manual_v4_code'] ) ? sanitize_text_field( wp_unslash( $_POST['manual_v4_code'] ) ) : '';
		$manual_v4_code = monsterinsights_is_valid_v4_id( $manual_v4_code ); // Also sanitizes the string.

		if ( ! empty( $_REQUEST['isnetwork'] ) && sanitize_text_field( wp_unslash( $_REQUEST['isnetwork'] ) ) ) {
			if ( ! current_user_can( 'manage_network_options' ) ) {
				wp_send_json_error( array(
					'error' => esc_html__( 'You do not have permission to update network settings.', 'google-analytics-for-wordpress' ),
				) );
			}
			define( 'WP_NETWORK_ADMIN', true );
		}
		$manual_v4_code_old = is_network_admin() ? MonsterInsights()->auth->get_network_manual_v4_id() : MonsterInsights()->auth->get_manual_v4_id();

		if ( $manual_v4_code && $manual_v4_code_old && $manual_v4_code_old === $manual_v4_code ) {
			// Same code we had before
			// Do nothing.
			wp_send_json_success();
		} else if ( $manual_v4_code && $manual_v4_code_old && $manual_v4_code_old !== $manual_v4_code ) {
			// Different UA code.
			if ( is_network_admin() ) {
				MonsterInsights()->auth->set_network_manual_v4_id( $manual_v4_code );
			} else {
				MonsterInsights()->auth->set_manual_v4_id( $manual_v4_code );
			}
		} else if ( $manual_v4_code && empty( $manual_v4_code_old ) ) {
			// Move to manual.
			if ( is_network_admin() ) {
				MonsterInsights()->auth->set_network_manual_v4_id( $manual_v4_code );
			} else {
				MonsterInsights()->auth->set_manual_v4_id( $manual_v4_code );
			}
		} else if ( empty( $manual_v4_code ) && $manual_v4_code_old ) {
			// Deleted manual.
			if ( is_network_admin() ) {
				MonsterInsights()->auth->delete_network_manual_v4_id();
			} else {
				MonsterInsights()->auth->delete_manual_v4_id();
			}
		} else if ( isset( $_POST['manual_v4_code'] ) && empty( $manual_v4_code ) ) {
			wp_send_json_error( array(
				'v4_error' => 1,
				'error'    => sprintf(
					/* translators: %1$s: Opening link tag, %2$s: Closing link tag. */
					__( 'Oops! Please enter a valid Google Analytics 4 Measurement ID. %1$sLearn how to find your Measurement ID%2$s.', 'google-analytics-for-wordpress' ),
					'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'invalid-manual-gav4-code', 'https://www.monsterinsights.com/docs/how-to-set-up-dual-tracking/' ) . '">',
					'</a>'
				),
			) );
		}

		wp_send_json_success();
	}


/** Function monsterinsights_dismiss_ads_addon_notice_ajax() called by wp_ajax hooks: {'monsterinsights_dismiss_ads_addon_notice'} **/
/** No params detected :-/ **/


/** Function handle_settings_import() called by wp_ajax hooks: {'monsterinsights_handle_settings_import'} **/
/** Parameters found in function handle_settings_import(): {"files": ["import_file"]} **/
function handle_settings_import() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		if ( ! isset( $_FILES['import_file'] ) ) {
			return;
		}

		$import_file = $_FILES['import_file']; // phpcs:ignore

		$extension = explode( '.', sanitize_text_field( wp_unslash( $import_file['name'] ) ) ); // phpcs:ignore
		$extension = end( $extension );

		if ( 'json' !== $extension ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'Please upload a valid .json file', 'google-analytics-for-wordpress' ),
			) );
		}

		$file = file_get_contents( $import_file['tmp_name'] );

		if ( empty( $file ) ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'Please select a valid file to upload.', 'google-analytics-for-wordpress' ),
			) );
		}

		// Retrieve the settings from the file and convert the json object to an array.
		$new_settings = json_decode( wp_json_encode( json_decode( $file ) ), true );
		$settings     = monsterinsights_get_options();
		$exclude      = array(
			'analytics_profile',
			'analytics_profile_code',
			'analytics_profile_name',
			'oauth_version',
			'cron_last_run',
			'monsterinsights_oauth_status',
		);

		if ( ! empty( $new_settings['site_notes'] ) ) {
			$this->import_site_notes( $new_settings['site_notes'] );
		}
		unset( $new_settings['site_notes'] );

		foreach ( $exclude as $e ) {
			if ( ! empty( $new_settings[ $e ] ) ) {
				unset( $new_settings[ $e ] );
			}
		}

		foreach ( $exclude as $e ) {
			if ( ! empty( $settings[ $e ] ) ) {
				$new_settings[ $e ] = $settings[ $e ];
			}
		}

		// Prevent non-admin users from importing access-control settings.
		if ( ! current_user_can( 'manage_options' ) ) {
			$admin_only_settings = monsterinsights_get_admin_only_settings();
			foreach ( $admin_only_settings as $admin_setting ) {
				unset( $new_settings[ $admin_setting ] );
				if ( isset( $settings[ $admin_setting ] ) ) {
					$new_settings[ $admin_setting ] = $settings[ $admin_setting ];
				}
			}
		}

		global $monsterinsights_settings;
		$monsterinsights_settings = $new_settings;

		update_option( monsterinsights_get_option_name(), $new_settings );

		wp_send_json_success( $new_settings );

	}


/** Function monsterinsights_ajax_dismiss_seoboost_cta() called by wp_ajax hooks: {'monsterinsights_vue_dismiss_seoboost_cta'} **/
/** No params detected :-/ **/


/** Function monsterinsights_mark_floatbar_hidden() called by wp_ajax hooks: {'monsterinsights_hide_floatbar'} **/
/** No params detected :-/ **/


/** Function ajax_optimize() called by wp_ajax hooks: {'monsterinsights_gutenberg_headline_analyzer_optimize'} **/
/** Parameters found in function ajax_optimize(): {"post": ["headline", "context", "type"]} **/
function ajax_optimize() {
		// Reuse the Gutenberg headline nonce — already exposed as monsterinsights_gutenberg_tool_vars.nonce.
		check_ajax_referer( 'monsterinsights_gutenberg_headline_nonce', '_ajax_nonce' );

		// Match the token gate: get_token() requires monsterinsights_view_dashboard,
		// so gating on edit_posts let lower roles (Authors/Contributors) through to a
		// misleading non-retryable auth error. Keep both gates on the same capability.
		if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
			wp_send_json_error( $this->ai_error( 'auth', '', false ), 403 );
		}

		// Defense in depth: admin-ajax bypasses JS-only gating, so re-assert the Pro/license requirement here.
		if ( ! monsterinsights_is_pro_version() || ! MonsterInsights()->license->license_can( 'plus' ) ) {
			wp_send_json_error( $this->ai_error( 'upgrade_required', '', false ), 403 );
		}

		$headline = isset( $_POST['headline'] ) ? sanitize_text_field( wp_unslash( $_POST['headline'] ) ) : '';
		$context  = isset( $_POST['context'] ) ? sanitize_textarea_field( wp_unslash( $_POST['context'] ) ) : '';
		$type_raw = isset( $_POST['type'] ) ? sanitize_key( wp_unslash( $_POST['type'] ) ) : 'title';
		$type     = in_array( $type_raw, array( 'title', 'heading' ), true ) ? $type_raw : 'title';

		// Clamp inputs so an authenticated user can't amplify outbound cost (token spend, 30s timeout).
		$headline = mb_substr( $headline, 0, 500 );
		$context  = mb_substr( $context, 0, 5000 );

		if ( '' === $headline ) {
			wp_send_json_error( $this->ai_error( 'validation', __( 'Headline is required.', 'google-analytics-for-wordpress' ), false ), 400 );
		}

		$payload = array(
			'headline' => $headline,
			'context'  => $context,
			'type'     => $type,
		);

		$ai_response = $this->call_ai_suggestions( $payload, false );
		if ( is_wp_error( $ai_response ) ) {
			wp_send_json_error( $this->wp_error_to_ai_error( $ai_response ) );
		}

		// One transparent retry with a fresh token if the AI service rejects auth.
		if ( 401 === $ai_response['status'] ) {
			$ai_response = $this->call_ai_suggestions( $payload, true );
			if ( is_wp_error( $ai_response ) ) {
				wp_send_json_error( $this->wp_error_to_ai_error( $ai_response ) );
			}
		}

		if ( $ai_response['status'] >= 400 ) {
			wp_send_json_error( $this->ai_response_error( $ai_response['status'], $ai_response['body'], $ai_response['response'] ) );
		}

		$body        = $ai_response['body'];
		$suggestions = ( isset( $body['suggestions'] ) && is_array( $body['suggestions'] ) ) ? $body['suggestions'] : array();

		foreach ( $suggestions as $i => $suggestion ) {
			// Upstream contract violation — drop anything that isn't a suggestion object.
			if ( ! is_array( $suggestion ) ) {
				unset( $suggestions[ $i ] );
				continue;
			}
			$text     = isset( $suggestion['headline'] ) ? (string) $suggestion['headline'] : '';
			$headline = wp_strip_all_tags( $text );
			// Build the outbound shape explicitly — never forward unknown AI-service
			// fields into authenticated editor DOM, even if the upstream contract changes.
			$suggestions[ $i ] = array(
				'headline'  => $headline,
				'reasoning' => isset( $suggestion['reasoning'] )
					? wp_strip_all_tags( (string) $suggestion['reasoning'] )
					: '',
				'score'     => $this->score_value_for( $headline ),
			);
		}

		// Highest score first so the editor surfaces the strongest headline at the top.
		usort(
			$suggestions,
			static function ( $a, $b ) {
				return $b['score'] <=> $a['score'];
			}
		);

		wp_send_json_success(
			array(
				// Re-index so dropped entries can't turn the JSON array into an object.
				'suggestions' => array_values( $suggestions ),
			)
		);
	}


/** Function export_notes_to_ga4() called by wp_ajax hooks: {'monsterinsights_vue_export_notes'} **/
/** Parameters found in function export_notes_to_ga4(): {"post": ["action", "annotations"]} **/
function export_notes_to_ga4() {
		if (
			! isset( $_POST['action'] ) ||
			'monsterinsights_vue_export_notes' !== $_POST['action']
		) {
			return;
		}

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			wp_die(
				esc_html__(
					'You do not have sufficient permissions to access this page.',
					'google-analytics-for-wordpress'
				)
			);
		}

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		$annotations = isset( $_POST['annotations'] ) ? json_decode( wp_unslash( $_POST['annotations'] ), true ) : array();
		if ( empty( $annotations ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'No annotations data provided.', 'google-analytics-for-wordpress' ),
				)
			);
		}

		// Check if user is authenticated.
		if (
			! ( MonsterInsights()->auth->is_authed() || MonsterInsights()->auth->is_network_authed() )
		) {
			wp_send_json_error(
				array(
					'message' => __( 'You must be properly authenticated with MonsterInsights to export annotations.', 'google-analytics-for-wordpress' ),
				)
			);
		}

		// Prepare API request options.
		$api_options = array();

		// Add network flag if needed.
		if (
			! MonsterInsights()->auth->is_authed() &&
			MonsterInsights()->auth->is_network_authed()
		) {
			$api_options['network'] = true;
		}

		// Create API request.
		$api = new MonsterInsights_API_Request( 'analytics/reports/annotations/', $api_options, 'POST' );

		// Set additional data with annotations.
		$api->set_additional_data(
			array(
				'annotations' => $annotations,
				'source'      => 'site-notes-export',
			)
		);

		// Make the API request.
		$response = $api->request();
		if ( is_wp_error( $response ) ) {
			wp_send_json_error(
				array(
					'message' => $response->get_error_message(),
				)
			);
		}

		// Update post meta with GA4 annotation IDs if response is successful
		if ( isset( $response['success'] ) && $response['success'] && isset( $response['created'] ) && is_array( $response['created'] ) ) {
			foreach ( $response['created'] as $created_annotation ) {
				if ( ! isset( $created_annotation['annotation'] ) || ! isset( $created_annotation['annotation']['id'] ) ) {
					continue;
				}

				$ga4_annotation_id = $created_annotation['annotation']['id'];
				$ga4_title = isset( $created_annotation['annotation']['title'] ) ? $created_annotation['annotation']['title'] : '';
				$ga4_date = isset( $created_annotation['annotation']['annotationDate'] ) ? $created_annotation['annotation']['annotationDate'] : array();
				// Find matching annotation in the original annotations array
				foreach ( $annotations as $annotation ) {
					$annotation_title = isset( $annotation['title'] ) ? $annotation['title'] : '';
					$annotation_date = isset( $annotation['annotation_date'] ) ? $annotation['annotation_date'] : '';
					$annotation_id = isset( $annotation['id'] ) ? $annotation['id'] : 0;

					// Format GA4 date to match annotation date format
					$ga4_formatted_date = '';
					if ( is_array( $ga4_date ) && isset( $ga4_date['year'] ) && isset( $ga4_date['month'] ) && isset( $ga4_date['day'] ) ) {
						$ga4_formatted_date = sprintf( '%04d-%02d-%02d', $ga4_date['year'], $ga4_date['month'], $ga4_date['day'] );
					}

					// Match by title and date
					if ( $annotation_title === $ga4_title && $annotation_date === $ga4_formatted_date && $annotation_id > 0 ) {
						update_post_meta( $annotation_id, '_ga4_annotation_id', $ga4_annotation_id );
						break;
					}
				}
			}
		}

		monsterinsights_update_option( 'site_notes_export_synced', 1 );

		// Return success response.
		wp_send_json_success(
			array(
				'message' => __( 'Annotations exported successfully.', 'google-analytics-for-wordpress' ),
				'data'    => $response,
			)
		);
	}


/** Function monsterinsights_mark_admin_menu_tooltip_hidden() called by wp_ajax hooks: {'monsterinsights_hide_admin_menu_tooltip'} **/
/** No params detected :-/ **/


/** Function delete_notes() called by wp_ajax hooks: {'monsterinsights_vue_delete_notes'} **/
/** Parameters found in function delete_notes(): {"post": ["ids"]} **/
function delete_notes() {
		check_ajax_referer('mi-admin-nonce', 'nonce');

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			wp_send_json(
				array(
					'published' => false,
					'message' => __( "You don't have permission to update notes.", 'google-analytics-for-wordpress' ),
				)
			);
		}

		$ids = !empty($_POST['ids']) ? json_decode(html_entity_decode(wp_unslash($_POST['ids']))) : [];

		if (empty($ids)) {
			wp_send_json(
				array(
					'success' => false,
					'message' => __('Please choose a site note(s) to delete!', 'google-analytics-for-wordpress'),
				)
			);
		}

		$blocked = false;
		foreach ($ids as $id) {
			if ( is_wp_error( $this->db->delete_note($id) ) ) {
				$blocked = true;
			}
		}

		if ( $blocked ) {
			wp_send_json(
				array(
					'success' => false,
					'message' => __( "You don't have permission to delete one or more of these notes.", 'google-analytics-for-wordpress' ),
				)
			);
		}

		wp_send_json(
			array(
				'success' => true,
				'message' => '',
			)
		);
	}


/** Function get_notes() called by wp_ajax hooks: {'monsterinsights_vue_get_notes'} **/
/** Parameters found in function get_notes(): {"post": ["params"]} **/
function get_notes() {
		check_ajax_referer('mi-admin-nonce', 'nonce');

		if ( ! current_user_can( 'monsterinsights_view_dashboard' ) && ! current_user_can( 'monsterinsights_save_settings' ) ) {
			wp_send_json(
				array(
					'published' => false,
					'message' => __( "You don't have permission to view notes.", 'google-analytics-for-wordpress' ),
				)
			);
		}

		$params = !empty($_POST['params']) ? json_decode(html_entity_decode(wp_unslash($_POST['params'])), true) : [];

		$output = $this->prepare_notes($params);

		$num_posts = wp_count_posts('monsterinsights_note', 'readable');

		if ($num_posts) {
			$output['status_filters'] = array(
				array(
					'status' => 'all',
					'count'  => array_sum((array) $num_posts) - $num_posts->trash,
				),
			);

			foreach ($num_posts as $status => $count) {
				if (0 >= $count) {
					continue;
				}

				$output['status_filters'][] = array(
					'status' => $status,
					'count'  => $count,
				);
			}
		}

		wp_send_json($output);
	}


/** Function get_profile() called by wp_ajax hooks: {'monsterinsights_vue_get_profile'} **/
/** No params detected :-/ **/


/** Function ajax_get_setup_checklist() called by wp_ajax hooks: {'monsterinsights_vue_get_setup_checklist'} **/
/** No params detected :-/ **/


/** Function onboarding_get_install_errors() called by wp_ajax hooks: {'nopriv_onboarding_monsterinsights_onboarding_get_errors'} **/
/** No params detected :-/ **/


/** Function maybe_reauthenticate() called by wp_ajax hooks: {'monsterinsights_maybe_reauthenticate'} **/
/** Parameters found in function maybe_reauthenticate(): {"request": ["isnetwork"]} **/
function maybe_reauthenticate() {

		// Check nonce
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		$url = monsterinsights_get_onboarding_url();

		// current user can authenticate
		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			$message = sprintf(
				/* translators: %1$s: Opening link tag, %2$s: Closing link tag. */
				__( 'You don\'t have the correct WordPress user permissions to re-authenticate into MonsterInsights. Please check with your site administrator that your role is included in the MonsterInsights permissions settings. %1$sClick here for more information%2$s.', 'google-analytics-for-wordpress' ),
				'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'cannot-save-settings', 'https://www.monsterinsights.com/docs/how-to-allow-user-roles-to-access-the-monsterinsights-reports-and-settings/' ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		}

		if ( ! empty( $_REQUEST['isnetwork'] ) && filter_var(wp_unslash($_REQUEST['isnetwork']), FILTER_VALIDATE_BOOLEAN) ) {
			if ( ! current_user_can( 'manage_network_options' ) ) {
				wp_send_json_error( array(
					'error' => esc_html__( 'You do not have permission to update network settings.', 'google-analytics-for-wordpress' ),
				) );
			}
			define( 'WP_NETWORK_ADMIN', true );
		}

		// Only for Pro users, require a license key to be entered first so we can link to things.
		if ( monsterinsights_is_pro_version() ) {
			$valid = is_network_admin() ? MonsterInsights()->license->is_network_licensed() : MonsterInsights()->license->is_site_licensed();
			if ( monsterinsights_is_pro_version() && ! $valid ) {
				wp_send_json_error( array( 'message' => __( "Your license key for MonsterInsights is invalid. The key no longer exists or the user associated with the key has been deleted. Please use a different key.", 'google-analytics-for-wordpress' ) ) );
			}
		}

		// we do have a current auth
		if ( ! $this->is_network_admin() && ! MonsterInsights()->auth->is_authed() ) {
			$message = sprintf(
				/* translators: %1$s: Opening wizard link tag, %2$s: Closing wizard link tag, %3$s: Opening support link tag, %4$s: Closing support link tag. */
				__( 'Oops! There was a problem while re-authenticating. Please try to complete the MonsterInsights %1$ssetup wizard%2$s again. If the problem persists, please %3$scontact our support%4$s team.', 'google-analytics-for-wordpress' ),
				'<a href="' . esc_url( $url ) . '">',
				'</a>',
				'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'cannot-re-authenticate', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		} else if ( $this->is_network_admin() && ! MonsterInsights()->auth->is_network_authed() ) {
			$message = sprintf(
				/* translators: %1$s: Opening wizard link tag, %2$s: Closing wizard link tag, %3$s: Opening support link tag, %4$s: Closing support link tag. */
				__( 'Oops! There was a problem while re-authenticating. Please try to complete the MonsterInsights %1$ssetup wizard%2$s again. If the problem persists, please %3$scontact our support%4$s team.', 'google-analytics-for-wordpress' ),
				'<a href="' . esc_url( $url ) . '">',
				'</a>',
				'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'cannot-re-authenticate', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		}

		$site_type = monsterinsights_get_option( 'site_type' );

		$auth_request_args = array(
			'tt'        => $this->get_tt(),
			'sitei'     => $this->get_sitei(),
			'miversion' => MONSTERINSIGHTS_VERSION,
			'ajaxurl'   => admin_url( 'admin-ajax.php' ),
			'network'   => is_network_admin() ? 'network' : 'site',
			'siteurl'   => is_network_admin() ? network_admin_url() : home_url(),
			'key'       => is_network_admin() ? MonsterInsights()->auth->get_network_key() : MonsterInsights()->auth->get_key(),
			'token'     => is_network_admin() ? MonsterInsights()->auth->get_network_token() : MonsterInsights()->auth->get_token(),
			'return'    => is_network_admin() ? network_admin_url( 'admin.php?page=monsterinsights_network' ) : admin_url( 'admin.php?page=monsterinsights_settings' ),
			'testurl'   => 'https://' . monsterinsights_get_api_url() . 'test/',
			'site_type' => $site_type ?: 'business',
		);

		$auth_request_args = apply_filters('monsterinsights_auth_request_body', $auth_request_args);

		$siteurl = add_query_arg( $auth_request_args, $this->get_route( 'https://' . monsterinsights_get_api_url() . 'auth/reauth/{type}' ) );

		if ( monsterinsights_is_pro_version() ) {
			$key     = is_network_admin() ? MonsterInsights()->license->get_network_license_key() : MonsterInsights()->license->get_site_license_key();
			$siteurl = add_query_arg( 'license', $key, $siteurl );
		}

		$siteurl = apply_filters( 'monsterinsights_maybe_authenticate_siteurl', $siteurl );

		wp_send_json_success( array( 'redirect' => $siteurl ) );
	}


/** Function install_plugin() called by wp_ajax hooks: {'monsterinsights_vue_install_plugin'} **/
/** No params detected :-/ **/


/** Function monsterinsights_vue_dismiss_aiseo_cta() called by wp_ajax hooks: {'monsterinsights_vue_dismiss_aiseo_cta'} **/
/** No params detected :-/ **/


/** Function is_installed() called by wp_ajax hooks: {'nopriv_monsterinsights_is_installed'} **/
/** No params detected :-/ **/


/** Function save_funnel() called by wp_ajax hooks: {'monsterinsights_overview_report_save_funnel_filter'} **/
/** Parameters found in function save_funnel(): {"post": ["funnel"]} **/
function save_funnel() {
		$this->verify_request( 'monsterinsights_save_settings' );

		$funnel_json = isset( $_POST['funnel'] ) ? wp_unslash( $_POST['funnel'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified in verify_request() above.

		if ( empty( $funnel_json ) ) {
			wp_send_json_error( array(
				'message' => __( 'No funnel data provided.', 'monsterinsights' ),
			) );
			return;
		}

		$funnel_data = json_decode( $funnel_json, true );

		if ( ! is_array( $funnel_data ) || empty( $funnel_data['name'] ) ) {
			wp_send_json_error( array(
				'message' => __( 'Invalid funnel data.', 'monsterinsights' ),
			) );
			return;
		}

		$sanitized = $this->sanitize_funnel_data( $funnel_data );
		$funnel_id = $this->generate_funnel_id();

		$new_funnel = array_merge(
			array( 'id' => $funnel_id ),
			$sanitized
		);

		$funnels = $this->get_all_funnels();
		$funnels[ $funnel_id ] = $new_funnel;

		$this->save_all_funnels( $funnels );

		wp_send_json_success( $new_funnel );
	}


/** Function mark_read() called by wp_ajax hooks: {'monsterinsights_notification_mark_read'} **/
/** Parameters found in function mark_read(): {"post": ["id"]} **/
function mark_read() {
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! $this->has_access() || empty( $_POST['id'] ) ) {
			wp_send_json_error();
		}

		$id     = sanitize_text_field( wp_unslash( $_POST['id'] ) );
		$option = $this->get_option();

		if ( 'all' === $id ) {
			// Mark all feed and events as read.
			if ( is_array( $option['feed'] ) ) {
				foreach ( $option['feed'] as $key => $notification ) {
					$option['feed'][ $key ]['read'] = true;
				}
			}
			if ( is_array( $option['events'] ) ) {
				foreach ( $option['events'] as $key => $notification ) {
					$option['events'][ $key ]['read'] = true;
				}
			}
		} else {
			// Mark single notification as read — search all arrays for safety.
			foreach ( array( 'feed', 'events', 'dismissed' ) as $type ) {
				if ( ! is_array( $option[ $type ] ) ) {
					continue;
				}
				foreach ( $option[ $type ] as $key => $notification ) {
					if ( $notification['id'] == $id ) { // phpcs:ignore WordPress.PHP.StrictComparisons
						$option[ $type ][ $key ]['read'] = true;
						break 2;
					}
				}
			}
		}

		update_option( $this->option_name, $option, false );

		wp_send_json_success();
	}


/** Function get_report_data() called by wp_ajax hooks: {'monsterinsights_vue_get_report_data'} **/
/** Parameters found in function get_report_data(): {"request": ["isnetwork"], "post": ["report", "start", "end", "compare_report", "compare_start", "compare_end"]} **/
function get_report_data() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
			$message = sprintf(
				/* translators: %1$s: Opening link tag, %2$s: Closing link tag. */
				esc_html__( 'Oops! You do not have permissions to view MonsterInsights reporting. Please check with your site administrator that your role is included in the MonsterInsights permissions settings. %1$sClick here for more information%2$s.', 'google-analytics-for-wordpress' ),
				'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'cannot-view-reports', 'https://www.monsterinsights.com/docs/how-to-allow-user-roles-to-access-the-monsterinsights-reports-and-settings/' ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		}

		if ( ! empty( $_REQUEST['isnetwork'] ) && wp_unslash( $_REQUEST['isnetwork'] ) && current_user_can( 'manage_network_options' ) ) {
			define( 'WP_NETWORK_ADMIN', true );
		}
		$settings_page    = admin_url( 'admin.php?page=monsterinsights_settings' );
		$reactivation_url = monsterinsights_get_url( 'admin-notices', 'expired-license', "https://www.monsterinsights.com/my-account/" );
		$learn_more_link  = esc_url( 'https://www.monsterinsights.com/docs/faq/#licensedplugin' );

		// Only for Pro users, require a license key to be entered first so we can link to things.
		if ( monsterinsights_is_pro_version() ) {
			if ( ! MonsterInsights()->license->is_site_licensed() && ! MonsterInsights()->license->is_network_licensed() ) {
				$message = sprintf(
					/* translators: %1$s: Opening support link tag, %2$s: Closing support link tag. */
					esc_html__( 'Oops! You cannot view MonsterInsights reports because you are not licensed. Please try again in a few minutes. If the issue continues, please %1$scontact our support%2$s team.', 'google-analytics-for-wordpress' ),
					'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'cannot-view-reports', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
					'</a>'
				);
				wp_send_json_error( array(
					'message' => $message,
					'footer'  => '<a href="' . $settings_page . '">' . __( 'Add your license', 'google-analytics-for-wordpress' ) . '</a>',
				) );
			} else if ( MonsterInsights()->license->is_site_licensed() && ! MonsterInsights()->license->site_license_has_error() ) {
				// Good to go: site licensed.
			} else if ( MonsterInsights()->license->is_network_licensed() && ! MonsterInsights()->license->network_license_has_error() ) {
				// Good to go: network licensed.
			} else {
				$message = sprintf(
					/* translators: %1$s: Opening support link tag, %2$s: Closing support link tag. */
					esc_html__( 'Oops! We had a problem due to a license key error. Please try again in a few minutes. If the problem persists, please %1$scontact our support%2$s team.', 'google-analytics-for-wordpress' ),
					'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'cannot-view-reports', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
					'</a>'
				);
				wp_send_json_error( array( 'message' => $message ) );
			}
		}

		// We do not have a current auth.
		$site_auth = MonsterInsights()->auth->get_viewname();
		$ms_auth   = is_multisite() && MonsterInsights()->auth->get_network_viewname();
		if ( ! $site_auth && ! $ms_auth ) {
			// `monsterinsights_get_onboarding_url()` already builds the correct
			// network-admin return URL when `is_network_admin()` is true, so a
			// separate multisite fallback is no longer needed.
			$url = monsterinsights_get_onboarding_url();

			$message = sprintf(
				/* translators: %1$s: Opening wizard link tag, %2$s: Closing wizard link tag. */
				esc_html__( 'You need to authenticate into MonsterInsights before viewing reports. Please run our %1$ssetup wizard%2$s.', 'google-analytics-for-wordpress' ),
				'<a href="' . esc_url( $url ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		}

		$report_name = isset( $_POST['report'] ) ? sanitize_text_field( wp_unslash( $_POST['report'] ) ) : '';

		if ( empty( $report_name ) ) {
			$message = sprintf(
				/* translators: %1$s: Opening support link tag, %2$s: Closing support link tag. */
				esc_html__( 'Oops! We ran into a problem displaying this report. Please %1$scontact our support%2$s team if this issue persists.', 'google-analytics-for-wordpress' ),
				'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'cannot-display-reports', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		}

		$report = MonsterInsights()->reporting->get_report( $report_name );

		$isnetwork = ! empty( $_REQUEST['isnetwork'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['isnetwork'] ) ) : '';
		$start     = ! empty( $_POST['start'] ) ? sanitize_text_field( wp_unslash( $_POST['start'] ) ) : $report->default_start_date();
		$end       = ! empty( $_POST['end'] ) ? sanitize_text_field( wp_unslash( $_POST['end'] ) ) : $report->default_end_date();

		$args = array(
			'start' => $start,
			'end'   => $end,
		);

		// User want to show compare report.
		if ( isset( $_POST['compare_report'] ) ) {
			$args['compare_start'] = ! empty( $_POST['compare_start'] ) ? sanitize_text_field( wp_unslash( $_POST['compare_start'] ) ) : $report->default_compare_start_date();
			$args['compare_end']   = ! empty( $_POST['compare_end'] ) ? sanitize_text_field( wp_unslash( $_POST['compare_end'] ) ) : $report->default_compare_end_date();
		}

		if ( $isnetwork ) {
			$args['network'] = true;
		}
		$args['included_metrics'] = get_user_meta( get_current_user_id(), 'monsterinsights_included_metrics', true ) ?? 'sessions,pageviews';

		if ( monsterinsights_is_pro_version() && ! MonsterInsights()->license->license_can( $report->level ) ) {
			$data = array(
				'success' => false,
				'error'   => 'license_level',
			);
		} else {
			$data = apply_filters( 'monsterinsights_vue_reports_data', $report->get_data( $args ), $report_name, $report );
		}
		if ( ! empty( $data['success'] ) ) {
			if ( empty( $data['data'] ) ) {
				wp_send_json_success( new stdclass() );
			} else {
				wp_send_json_success( $data['data'] );
			}
		} else if ( isset( $data['success'] ) && false === $data['success'] && ! empty( $data['error'] ) ) {
			// Use a custom handler for invalid_grant errors.
			if ( strpos( $data['error'], 'invalid_grant' ) > 0 ) {
				wp_send_json_error(
					array(
						'message' => 'invalid_grant',
						'footer'  => '',
					)
				);
			}

			wp_send_json_error(
				array(
					'message' => $data['error'],
					'footer'  => isset( $data['data']['footer'] ) ? $data['data']['footer'] : '',
					'type'    => isset( $data['data']['type'] ) ? $data['data']['type'] : '',
				)
			);
		}

		$message = sprintf(
			/* translators: %1$s: Opening support link tag, %2$s: Closing support link tag. */
			esc_html__( 'Oops! We encountered an error while generating your reports. Please wait a few minutes and try again. If the issue persists, please %1$scontact our support%2$s team.', 'google-analytics-for-wordpress' ),
			'<a href="' . monsterinsights_get_url( 'notice', 'error-generating-reports', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
			'</a>'
		);
		wp_send_json_error( array( 'message' => $message ) );
	}


/** Function maybe_authenticate() called by wp_ajax hooks: {'monsterinsights_maybe_authenticate'} **/
/** Parameters found in function maybe_authenticate(): {"request": ["isnetwork"]} **/
function maybe_authenticate() {
		// Check nonce
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );
		// current user can authenticate
		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			$message = sprintf(
				/* translators: %1$s: Opening link tag, %2$s: Closing link tag. */
				__( 'You don\'t have the correct WordPress user permissions to authenticate into MonsterInsights. Please check with your site administrator that your role is included in the MonsterInsights permissions settings. %1$sClick here for more information%2$s.', 'google-analytics-for-wordpress' ),
				'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'cannot-save-settings', 'https://www.monsterinsights.com/docs/how-to-allow-user-roles-to-access-the-monsterinsights-reports-and-settings/' ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		}

		if ( ! empty( $_REQUEST['isnetwork'] ) && $_REQUEST['isnetwork'] ) { // phpcs:ignore
			if ( ! current_user_can( 'manage_network_options' ) ) {
				wp_send_json_error( array(
					'error' => esc_html__( 'You do not have permission to update network settings.', 'google-analytics-for-wordpress' ),
				) );
			}
			define( 'WP_NETWORK_ADMIN', true );
		}

		// Only for Pro users, require a license key to be entered first so we can link to things.
		if ( monsterinsights_is_pro_version() ) {
			$valid = is_network_admin() ? MonsterInsights()->license->is_network_licensed() : MonsterInsights()->license->is_site_licensed();
			if ( ! $valid ) {
				wp_send_json_error( array( 'message' => __( "Cannot authenticate. Please enter a valid, active license key for MonsterInsights Pro into the settings page.", 'google-analytics-for-wordpress' ) ) );
			}
		}

		// we do not have a current auth
		if ( ! $this->is_network_admin() && MonsterInsights()->auth->is_authed() ) {
			$message = sprintf(
				/* translators: %1$s: Opening support link tag, %2$s: Closing support link tag. */
				__( 'Oops! There has been an error authenticating. Please try again in a few minutes. If the problem persists, please %1$scontact our support%2$s team.', 'google-analytics-for-wordpress' ),
				'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'error-authenticating', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		} else if ( $this->is_network_admin() && MonsterInsights()->auth->is_network_authed() ) {
			$message = sprintf(
				/* translators: %1$s: Opening support link tag, %2$s: Closing support link tag. */
				__( 'Oops! There has been an error authenticating. Please try again in a few minutes. If the problem persists, please %1$scontact our support%2$s team.', 'google-analytics-for-wordpress' ),
				'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'error-authenticating', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		}

		$sitei = $this->get_sitei();

		$site_type = monsterinsights_get_option( 'site_type' );

		$auth_request_args = array(
			'tt'        => $this->get_tt(),
			'sitei'     => $sitei,
			'miversion' => MONSTERINSIGHTS_VERSION,
			'ajaxurl'   => admin_url( 'admin-ajax.php' ),
			'network'   => is_network_admin() ? 'network' : 'site',
			'siteurl'   => is_network_admin() ? network_admin_url() : home_url(),
			'return'    => is_network_admin() ? network_admin_url( 'admin.php?page=monsterinsights_network' ) : admin_url( 'admin.php?page=monsterinsights_settings' ),
			'testurl'   => 'https://' . monsterinsights_get_api_url() . 'test/',
			'site_type' => $site_type ?: 'business',
		);

		$auth_request_args = apply_filters('monsterinsights_auth_request_body', $auth_request_args);

		$siteurl = add_query_arg($auth_request_args, $this->get_route( 'https://' . monsterinsights_get_api_url() . 'auth/new/{type}' ) );

		if ( monsterinsights_is_pro_version() ) {
			$key     = is_network_admin() ? MonsterInsights()->license->get_network_license_key() : MonsterInsights()->license->get_site_license_key();
			$siteurl = add_query_arg( 'license', $key, $siteurl );
		}

		$siteurl = apply_filters( 'monsterinsights_maybe_authenticate_siteurl', $siteurl );
		wp_send_json_success( array( 'redirect' => $siteurl ) );
	}


/** Function get_overview_bundle() called by wp_ajax hooks: {'monsterinsights_vue_get_overview_bundle'} **/
/** Parameters found in function get_overview_bundle(): {"request": ["isnetwork"], "post": ["start", "end", "compare_report", "compare_start", "compare_end"]} **/
function get_overview_bundle() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		// Check user permissions.
		if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
			$message = sprintf(
				/* translators: %1$s: Opening link tag, %2$s: Closing link tag. */
				esc_html__( 'Oops! You do not have permissions to view MonsterInsights reporting. Please check with your site administrator that your role is included in the MonsterInsights permissions settings. %1$sClick here for more information%2$s.', 'google-analytics-for-wordpress' ),
				'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'cannot-view-reports', 'https://www.monsterinsights.com/docs/how-to-allow-user-roles-to-access-the-monsterinsights-reports-and-settings/' ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		}

		if ( ! empty( $_REQUEST['isnetwork'] ) && wp_unslash( $_REQUEST['isnetwork'] ) && current_user_can( 'manage_network_options' ) ) {
			define( 'WP_NETWORK_ADMIN', true );
		}

		$settings_page = admin_url( 'admin.php?page=monsterinsights_settings' );

		// Only for Pro users, require a license key to be entered first so we can link to things.
		if ( monsterinsights_is_pro_version() ) {
			if ( ! MonsterInsights()->license->is_site_licensed() && ! MonsterInsights()->license->is_network_licensed() ) {
				$message = sprintf(
					/* translators: %1$s: Opening support link tag, %2$s: Closing support link tag. */
					esc_html__( 'Oops! You cannot view MonsterInsights reports because you are not licensed. Please try again in a few minutes. If the issue continues, please %1$scontact our support%2$s team.', 'google-analytics-for-wordpress' ),
					'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'cannot-view-reports', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
					'</a>'
				);
				wp_send_json_error( array(
					'message' => $message,
					'footer'  => '<a href="' . $settings_page . '">' . __( 'Add your license', 'google-analytics-for-wordpress' ) . '</a>',
				) );
			} else if ( MonsterInsights()->license->is_site_licensed() && ! MonsterInsights()->license->site_license_has_error() ) {
				// Good to go: site licensed.
			} else if ( MonsterInsights()->license->is_network_licensed() && ! MonsterInsights()->license->network_license_has_error() ) {
				// Good to go: network licensed.
			} else {
				$message = sprintf(
					/* translators: %1$s: Opening support link tag, %2$s: Closing support link tag. */
					esc_html__( 'Oops! We had a problem due to a license key error. Please try again in a few minutes. If the problem persists, please %1$scontact our support%2$s team.', 'google-analytics-for-wordpress' ),
					'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'cannot-view-reports', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
					'</a>'
				);
				wp_send_json_error( array( 'message' => $message ) );
			}
		}

		// We do not have a current auth.
		$site_auth = MonsterInsights()->auth->get_viewname();
		$ms_auth   = is_multisite() && MonsterInsights()->auth->get_network_viewname();
		if ( ! $site_auth && ! $ms_auth ) {
			// `monsterinsights_get_onboarding_url()` already builds the correct
			// network-admin return URL when `is_network_admin()` is true, so a
			// separate multisite fallback is no longer needed.
			$url = monsterinsights_get_onboarding_url();

			$message = sprintf(
				/* translators: %1$s: Opening wizard link tag, %2$s: Closing wizard link tag. */
				esc_html__( 'You need to authenticate into MonsterInsights before viewing reports. Please run our %1$ssetup wizard%2$s.', 'google-analytics-for-wordpress' ),
				'<a href="' . esc_url( $url ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		}

		// Get user included metrics for cache key generation.
		$user_included_metrics = get_user_meta( get_current_user_id(), 'monsterinsights_included_metrics', true );
		if ( false === $user_included_metrics || empty( $user_included_metrics ) ) {
			$user_included_metrics = 'pageviews,sessions';
		}

		// Auto-trim to max 5 metrics for legacy users who had 7 metrics before update.
		if ( ! empty( $user_included_metrics ) && is_string( $user_included_metrics ) ) {
			$metrics_array = array_filter( array_map( 'trim', explode( ',', $user_included_metrics ) ) );

			if ( count( $metrics_array ) > 5 ) {
				// Keep only first 5 metrics.
				$metrics_array = array_slice( $metrics_array, 0, 5 );
				$user_included_metrics = implode( ',', $metrics_array );

				// Update user meta to persist the corrected value.
				update_user_meta( get_current_user_id(), 'monsterinsights_included_metrics', $user_included_metrics );
			}
		}

		$user_included_metrics = $this->remove_premium_metrics( $user_included_metrics );

		// Get overview report for date defaults.
		$overview_report = MonsterInsights()->reporting->get_report( 'overview' );

		$isnetwork = ! empty( $_REQUEST['isnetwork'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['isnetwork'] ) ) : '';
		$start     = ! empty( $_POST['start'] ) ? sanitize_text_field( wp_unslash( $_POST['start'] ) ) : $overview_report->default_start_date();
		$end       = ! empty( $_POST['end'] ) ? sanitize_text_field( wp_unslash( $_POST['end'] ) ) : $overview_report->default_end_date();

		// Generate cache key components.
		$network_id = $isnetwork ? get_current_network_id() : 0;
		$metrics_hash = md5( $user_included_metrics );

		// Handle compare mode hash.
		$compare_hash = 'none';
		if ( isset( $_POST['compare_report'] ) ) {
			$compare_start = ! empty( $_POST['compare_start'] ) ? sanitize_text_field( wp_unslash( $_POST['compare_start'] ) ) : $overview_report->default_compare_start_date();
			$compare_end   = ! empty( $_POST['compare_end'] ) ? sanitize_text_field( wp_unslash( $_POST['compare_end'] ) ) : $overview_report->default_compare_end_date();
			$compare_hash = md5( $compare_start . '_' . $compare_end );
		}

		// Generate bundle cache key.
		$cache_key = sprintf(
			'bundle_overview_%s_%s_%s_%s_%s',
			$start,
			$end,
			$compare_hash,
			$network_id,
			$metrics_hash
		);

		// Check cache for bundled data.
		$cached_bundle = monsterinsights_cache_get( $cache_key, 'reports' );
		if ( false !== $cached_bundle ) {
			wp_send_json_success( $cached_bundle );
			return;
		}

		// Cache miss - fetch all data.
		$args = array(
			'start' => $start,
			'end'   => $end,
		);

		// User want to show compare report.
		if ( isset( $_POST['compare_report'] ) ) {
			$args['compare_start'] = ! empty( $_POST['compare_start'] ) ? sanitize_text_field( wp_unslash( $_POST['compare_start'] ) ) : $overview_report->default_compare_start_date();
			$args['compare_end']   = ! empty( $_POST['compare_end'] ) ? sanitize_text_field( wp_unslash( $_POST['compare_end'] ) ) : $overview_report->default_compare_end_date();
		}

		if ( $isnetwork ) {
			$args['network'] = true;
		}
		$args['included_metrics'] = $user_included_metrics;

		if ( monsterinsights_is_pro_version() && ! MonsterInsights()->license->license_can( $overview_report->level ) ) {
			$overview_data = array(
				'success' => false,
				'error'   => 'license_level',
			);
		} else {
			$overview_data = apply_filters( 'monsterinsights_vue_reports_data', $overview_report->get_data( $args ), 'overview', $overview_report );
		}

		// Handle overview report errors.
		if ( empty( $overview_data['success'] ) ) {
			if ( isset( $overview_data['success'] ) && false === $overview_data['success'] && ! empty( $overview_data['error'] ) ) {
				// Use a custom handler for invalid_grant errors.
				if ( strpos( $overview_data['error'], 'invalid_grant' ) > 0 ) {
					wp_send_json_error(
						array(
							'message' => 'invalid_grant',
							'footer'  => '',
						)
					);
				}

				wp_send_json_error(
					array(
						'message' => $overview_data['error'],
						'footer'  => isset( $overview_data['data']['footer'] ) ? $overview_data['data']['footer'] : '',
						'type'    => isset( $overview_data['data']['type'] ) ? $overview_data['data']['type'] : '',
					)
				);
			}

			/* translators: support link tag starts with url and Support link tag ends. */
			$message = sprintf(
				esc_html__( 'Oops! We encountered an error while generating your reports. Please wait a few minutes and try again. If the issue persists, please %1$scontact our support%2$s team.', 'google-analytics-for-wordpress' ),
				'<a href="' . monsterinsights_get_url( 'notice', 'error-generating-reports', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		}

		// 3. Get site_summary report.
		$site_summary_report = MonsterInsights()->reporting->get_report( 'site_summary' );

		if ( monsterinsights_is_pro_version() && ! MonsterInsights()->license->license_can( $site_summary_report->level ) ) {
			$site_summary_data = array(
				'success' => false,
				'error'   => 'license_level',
			);
		} else {
			$site_summary_data = apply_filters( 'monsterinsights_vue_reports_data', $site_summary_report->get_data( $args ), 'site_summary', $site_summary_report );
		}

		// Handle site_summary report errors.
		if ( empty( $site_summary_data['success'] ) ) {
			if ( isset( $site_summary_data['success'] ) && false === $site_summary_data['success'] && ! empty( $site_summary_data['error'] ) ) {
				// Use a custom handler for invalid_grant errors.
				if ( strpos( $site_summary_data['error'], 'invalid_grant' ) > 0 ) {
					wp_send_json_error(
						array(
							'message' => 'invalid_grant',
							'footer'  => '',
						)
					);
				}

				wp_send_json_error(
					array(
						'message' => $site_summary_data['error'],
						'footer'  => isset( $site_summary_data['data']['footer'] ) ? $site_summary_data['data']['footer'] : '',
						'type'    => isset( $site_summary_data['data']['type'] ) ? $site_summary_data['data']['type'] : '',
					)
				);
			}

			/* translators: support link tag starts with url and Support link tag ends. */
			$message = sprintf(
				esc_html__( 'Oops! We encountered an error while generating your reports. Please wait a few minutes and try again. If the issue persists, please %1$scontact our support%2$s team.', 'google-analytics-for-wordpress' ),
				'<a href="' . monsterinsights_get_url( 'notice', 'error-generating-reports', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		}

		// Prepare bundled data.
		$bundle_data = array(
			'user_metrics'  => $user_included_metrics,
			'overview'      => ! empty( $overview_data['data'] ) ? $overview_data['data'] : new stdClass(),
			'site_summary'  => ! empty( $site_summary_data['data'] ) ? $site_summary_data['data'] : new stdClass(),
		);

		// Cache the bundle for 1 hour (3600 seconds).
		monsterinsights_cache_set( $cache_key, $bundle_data, 'reports', 3600 );

		// Return bundled data.
		wp_send_json_success( $bundle_data );
	}


/** Function MonsterInsights_API_Token() called by wp_ajax hooks: {'monsterinsights_get_bearer_token'} **/
/** No function found :-/ **/


/** Function check_popular_posts_report() called by wp_ajax hooks: {'monsterinsights_vue_grab_popular_posts_report'} **/
/** Parameters found in function check_popular_posts_report(): {"request": ["isnetwork"], "post": ["start", "end"]} **/
function check_popular_posts_report() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
			$message = sprintf(
				/* translators: %1$s: Opening link tag, %2$s: Closing link tag. */
				esc_html__( 'Oops! You do not have permissions to view or access Popular Posts. Please check with your site administrator that your role is included in the MonsterInsights permissions settings. %1$sClick here for more information%2$s.', 'google-analytics-for-wordpress' ),
				'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'cannot-view-dashboard', 'https://www.monsterinsights.com/docs/how-to-allow-user-roles-to-access-the-monsterinsights-reports-and-settings/' ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		}

		if ( ! empty( $_REQUEST['isnetwork'] ) && wp_unslash( $_REQUEST['isnetwork'] ) && current_user_can( 'manage_network_options' ) ) {
			define( 'WP_NETWORK_ADMIN', true );
		}
		$settings_page = admin_url( 'admin.php?page=monsterinsights_settings' );

		// Only for Pro users, require a license key to be entered first so we can link to things.
		if ( monsterinsights_is_pro_version() ) {
			if ( ! MonsterInsights()->license->is_site_licensed() && ! MonsterInsights()->license->is_network_licensed() ) {
				$url = admin_url( 'admin.php?page=monsterinsights_settings#/' );

				// Check for MS dashboard
				if ( is_network_admin() ) {
					$url = network_admin_url( 'admin.php?page=monsterinsights_settings#/' );
				}
				$message = sprintf(
					/* translators: %1$s: Opening settings page link tag, %2$s: Closing settings page link tag. */
					esc_html__( 'Oops! We could not find a valid license key for MonsterInsights. Please %1$senter a valid license key%2$s to view this report.', 'google-analytics-for-wordpress' ),
					'<a href="' . esc_url( $url ) . '">',
					'</a>'
				);
				wp_send_json_error( array(
					'message' => $message,
					'footer'  => '<a href="' . $settings_page . '">' . __( 'Add your license', 'google-analytics-for-wordpress' ) . '</a>',
				) );
			} else if ( MonsterInsights()->license->is_site_licensed() && ! MonsterInsights()->license->site_license_has_error() ) {
				// Good to go: site licensed.
			} else if ( MonsterInsights()->license->is_network_licensed() && ! MonsterInsights()->license->network_license_has_error() ) {
				// Good to go: network licensed.
			} else {
				$message = sprintf(
					/* translators: %1$s: Opening account link tag, %2$s: Closing account link tag. */
					esc_html__( 'Oops! We could not find a valid license key. Please enter a valid license key to view this report. You can find your license by logging into your %1$sMonsterInsights account%2$s.', 'google-analytics-for-wordpress' ),
					'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'license-errors', 'https://www.monsterinsights.com/my-account/licenses/' ) . '">',
					'</a>'
				);
				wp_send_json_error( array( 'message' => $message ) );
			}
		}

		// We do not have a current auth.
		$site_auth = MonsterInsights()->auth->get_viewname();
		$ms_auth   = is_multisite() && MonsterInsights()->auth->get_network_viewname();
		if ( ! $site_auth && ! $ms_auth ) {
			$url = admin_url( 'admin.php?page=monsterinsights_settings#/' );

			// Check for MS dashboard
			if ( is_network_admin() ) {
				$url = network_admin_url( 'admin.php?page=monsterinsights_settings#/' );
			}
			$message = sprintf(
				/* translators: %1$s: Opening wizard link tag, %2$s: Closing wizard link tag. */
				esc_html__( 'You need to authenticate into MonsterInsights before viewing reports. Please complete the setup by going through our %1$ssetup wizard%2$s.', 'google-analytics-for-wordpress' ),
				'<a href="' . esc_url( $url ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		}

		$report_name = 'popularposts';

		if ( empty( $report_name ) ) {
			/* translators: support link tag starts with url and Support link tag ends. */
			$message = sprintf(
				esc_html__( 'Oops! We encountered an error while generating your reports. Please wait a few minutes and try again. If the issue persists, please %1$scontact our support%2$s team.', 'google-analytics-for-wordpress' ),
				'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'cannot-generate-reports', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		}

		$report = MonsterInsights()->reporting->get_report( $report_name );

		$isnetwork = ! empty( $_REQUEST['isnetwork'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['isnetwork'] ) ) : '';
		$start     = ! empty( $_POST['start'] ) ? sanitize_text_field( wp_unslash( $_POST['start'] ) ) : $report->default_start_date();
		$end       = ! empty( $_POST['end'] ) ? sanitize_text_field( wp_unslash( $_POST['end'] ) ) : $report->default_end_date();

		$args = array(
			'start' => $start,
			'end'   => $end,
		);

		if ( $isnetwork ) {
			$args['network'] = true;
		}

		if ( monsterinsights_is_pro_version() && ! MonsterInsights()->license->license_can( $report->level ) ) {
			$data = array(
				'success' => false,
				'error'   => 'license_level',
			);
		} else {
			$data = apply_filters( 'monsterinsights_vue_reports_data', $report->get_data( $args ), $report_name, $report );
		}

		if ( ! empty( $data['success'] ) && ! empty( $data['data'] ) ) {
			wp_send_json_success( $data['data'] );
		} else if ( isset( $data['success'] ) && false === $data['success'] && ! empty( $data['error'] ) ) {
			// Use a custom handler for invalid_grant errors.
			if ( strpos( $data['error'], 'invalid_grant' ) > 0 ) {
				wp_send_json_error(
					array(
						'message' => 'invalid_grant',
						'footer'  => '',
					)
				);
			}

			wp_send_json_error(
				array(
					'message' => $data['error'],
					'footer'  => isset( $data['data']['footer'] ) ? $data['data']['footer'] : '',
				)
			);
		}

		/* translators: support link tag starts with url and Support link tag ends. */
		$message = sprintf(
			__( 'Oops! We encountered an error while generating your reports. Please wait a few minutes and try again. If the issue persists, please %1$scontact our support%2$s team.', 'google-analytics-for-wordpress' ),
			'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'cannot-generate-reports', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
			'</a>'
		);
		wp_send_json_error( array( 'message' => $message ) );
	}


/** Function save_widget_state() called by wp_ajax hooks: {'monsterinsights_save_widget_state'} **/
/** Parameters found in function save_widget_state(): {"post": ["reports", "width", "interval", "compact"]} **/
function save_widget_state() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		$default         = self::$default_options;
		$current_options = $this->get_options();

		$reports = $default['reports'];
		if ( isset( $_POST['reports'] ) ) {
			$reports = json_decode( sanitize_text_field( wp_unslash( $_POST['reports'] ) ), true );
		}

		$options = array(
			'width'       => ! empty( $_POST['width'] ) ? sanitize_text_field( wp_unslash( $_POST['width'] ) ) : $default['width'],
			'interval'    => ! empty( $_POST['interval'] ) ? absint( wp_unslash( $_POST['interval'] ) ) : $default['interval'],
			'compact'     => ! empty( $_POST['compact'] ) ? 'true' === sanitize_text_field( wp_unslash( $_POST['compact'] ) ) : $default['compact'],
			'reports'     => $reports,
			'notice30day' => $current_options['notice30day'],
		);

		array_walk( $options, 'sanitize_text_field' );
		update_user_meta( get_current_user_id(), 'monsterinsights_user_preferences', $options );

		wp_send_json_success();

	}


/** Function dismiss_notice() called by wp_ajax hooks: {'monsterinsights_vue_notice_dismiss'} **/
/** Parameters found in function dismiss_notice(): {"post": ["notice"]} **/
function dismiss_notice() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			wp_send_json_error();
		}

		$notice_id = empty( $_POST['notice'] ) ? false : sanitize_text_field( wp_unslash( $_POST['notice'] ) );
		if ( ! $notice_id ) {
			wp_send_json_error();
		}
		MonsterInsights()->notices->dismiss( $notice_id );

		wp_send_json_success();
	}


/** Function get_posts() called by wp_ajax hooks: {'monsterinsights_get_posts'} **/
/** Parameters found in function get_posts(): {"post": ["post_type", "keyword", "numberposts"]} **/
function get_posts() {

		// Run a security check first.
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
			wp_send_json_error();
		}

		$post_type = isset( $_POST['post_type'] ) ? sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) : 'any';

		$already_added = monsterinsights_get_option('popular_posts_inline_curated', []);
		$exclude = array();
		if( is_array( $already_added ) && !empty( $already_added ) ){
			foreach ( $already_added as $key => $value ) {
				$exclude[$value['id']] = $value['id'];
			}
		}

		$exclude = array_unique(array_values($exclude));

		$args = array(
			's'              => isset( $_POST['keyword'] ) ? sanitize_text_field( wp_unslash( $_POST['keyword'] ) ) : '',
			'post_type'      => $post_type,
			'posts_per_page' => isset( $_POST['numberposts'] ) ? sanitize_text_field( wp_unslash( $_POST['numberposts'] ) ) : 25,
			'orderby'        => 'post_title',
			'order'          => 'ASC',
			'post__not_in'   => $exclude,
		);

		$array = array();
		$posts = get_posts( $args );

		if ( in_array( $post_type, array( 'page', 'any' ), true ) ) {
			$homepage = get_option( 'page_on_front' );
			if ( ! $homepage ) {
				$array[] = array(
					'id'    => - 1,
					'title' => __( 'Homepage', 'google-analytics-for-wordpress' ),
				);
			}
		}

		if ( $posts ) {
			foreach ( $posts as $post ) {
				$array[] = array(
					'id'    => $post->ID,
					'title' => $post->post_title,
				);
			}
		}

		wp_send_json_success( $array );
	}


/** Function get_result() called by wp_ajax hooks: {'monsterinsights_gutenberg_headline_analyzer_get_results'} **/
/** Parameters found in function get_result(): {"request": ["q"]} **/
function get_result() {

		// csrf check
		if ( check_ajax_referer( 'monsterinsights_gutenberg_headline_nonce', false, false ) === false ) {
			$content = self::output_template( 'results-error.php' );
			wp_send_json_error(
				array(
					'html' => $content
				)
			);
		}

		// get whether or not the website is up
		$result = $this->get_headline_scores();

		if ( ! empty( $result->err ) ) {
			$content = self::output_template( 'results-error.php', $result );
			wp_send_json_error(
				array( 'html' => $content, 'analysed' => false )
			);
		} else {
			if(!isset($_REQUEST['q'])){
				wp_send_json_error(
					array( 'html' => '', 'analysed' => false )
				);
			}
			$q = (isset($_REQUEST['q'])) ? sanitize_text_field($_REQUEST['q']) : '';
			// send the response
			wp_send_json_success(
				array(
					'result'   => $result,
					'analysed' => ! $result->err,
					'sentence' => ucwords( wp_unslash( $q ) ),
					'score'    => ( isset( $result->score ) && ! empty( $result->score ) ) ? $result->score : 0
				)
			);

		}
	}


/** Function empty_cache() called by wp_ajax hooks: {'monsterinsights_popular_posts_empty_cache'} **/
/** No params detected :-/ **/


/** Function monsterinsights_get_seo_boost_cta_status() called by wp_ajax hooks: {'monsterinsights_get_seo_boost_cta_status'} **/
/** No params detected :-/ **/


/** Function send_test_email() called by wp_ajax hooks: {'monsterinsights_send_test_email'} **/
/** No params detected :-/ **/


/** Function monsterinsights_ajax_backfill_cache() called by wp_ajax hooks: {'monsterinsights_backfill_cache'} **/
/** Parameters found in function monsterinsights_ajax_backfill_cache(): {"post": ["cache_group", "cache_key", "data", "ttl"]} **/
function monsterinsights_ajax_backfill_cache() {
	check_ajax_referer( 'mi-admin-nonce', 'nonce' );

	if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
		wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'google-analytics-for-wordpress' ) ) );
	}

	$allowed_groups = monsterinsights_backfill_cache_allowed_groups();

	$cache_group = ! empty( $_POST['cache_group'] ) ? sanitize_text_field( wp_unslash( $_POST['cache_group'] ) ) : '';
	$cache_key   = ! empty( $_POST['cache_key'] ) ? sanitize_text_field( wp_unslash( $_POST['cache_key'] ) ) : '';

	if ( empty( $cache_group ) || empty( $cache_key ) ) {
		wp_send_json_error( array( 'message' => __( 'Missing required cache parameters.', 'google-analytics-for-wordpress' ) ) );
	}

	if ( ! in_array( $cache_group, $allowed_groups, true ) ) {
		wp_send_json_error( array( 'message' => __( 'Invalid cache group.', 'google-analytics-for-wordpress' ) ) );
	}

	$raw_data = ! empty( $_POST['data'] ) ? wp_unslash( $_POST['data'] ) : '';
	if ( strlen( $raw_data ) > 500000 ) {
		wp_send_json_error( array( 'message' => __( 'Data payload too large.', 'google-analytics-for-wordpress' ) ) );
	}

	$data = ! empty( $raw_data ) ? json_decode( $raw_data, true ) : null;
	$ttl  = ! empty( $_POST['ttl'] ) ? absint( $_POST['ttl'] ) : HOUR_IN_SECONDS;

	if ( $data === null && $raw_data !== '' ) {
		wp_send_json_error( array( 'message' => __( 'Invalid JSON in data parameter.', 'google-analytics-for-wordpress' ) ) );
	}

	if ( $data === null ) {
		wp_send_json_error( array( 'message' => __( 'Missing required cache parameters.', 'google-analytics-for-wordpress' ) ) );
	}

	$stored = monsterinsights_cache_set( $cache_key, $data, $cache_group, $ttl );

	// Report an actual storage failure instead of masking it as success. A
	// silent failure here makes the client register the cache key even though
	// nothing was stored, so every later read misses and reports re-fetch
	// forever with no visible error.
	if ( ! $stored ) {
		wp_send_json_error( array( 'message' => __( 'Unable to store cache data.', 'google-analytics-for-wordpress' ) ) );
	}

	wp_send_json_success();
}


/** Function get_addons() called by wp_ajax hooks: {'monsterinsights_vue_get_addons'} **/
/** Parameters found in function get_addons(): {"post": ["network"]} **/
function get_addons( $is_onboarding ) {
		global $current_user;

		if ( true !== $is_onboarding ) {
			check_ajax_referer( 'mi-admin-nonce', 'nonce' );
			if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
				return;
			}
		}

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if ( isset( $_POST['network'] ) && intval( wp_unslash( $_POST['network'] ) ) > 0 && current_user_can( 'manage_network_options' ) ) {
			define( 'WP_NETWORK_ADMIN', true );
		}

		$addons_data       = monsterinsights_get_addons();
		$parsed_addons     = array();
		$installed_plugins = get_plugins();

		if ( ! is_array( $addons_data ) ) {
			$addons_data = array();
		}

		foreach ( $addons_data as $addons_type => $addons ) {
			foreach ( $addons as $addon ) {
				$slug = 'monsterinsights-' . $addon->slug;
				if ( 'monsterinsights-ecommerce' === $slug && 'm' === $slug[0] ) {
					$addon = $this->get_addon( $installed_plugins, $addons_type, $addon, $slug );
					if ( empty( $addon->installed ) ) {
						$slug  = 'ga-ecommerce';
						$addon = $this->get_addon( $installed_plugins, $addons_type, $addon, $slug );
					}
				} else {
					$addon = $this->get_addon( $installed_plugins, $addons_type, $addon, $slug );
				}
				$parsed_addons[ $addon->slug ] = $addon;
			}
		}

		// Include data about the plugins needed by some addons ( WooCommerce, EDD, Google AMP, CookieBot, etc ).
		// WooCommerce.
		$parsed_addons['woocommerce'] = array(
			'active' => class_exists( 'WooCommerce' ),
		);
		// Edd.
		$parsed_addons['easy_digital_downloads'] = array(
			'active'    => class_exists( 'Easy_Digital_Downloads' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-edd.png',
			'title'     => 'Easy Digital Downloads',
			'excerpt'   => __( 'The best WordPress eCommerce plugin for selling digital downloads. Start selling eBooks, software, music, digital art, and more within minutes. Accept payments, manage subscriptions, advanced access control, and more.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'easy-digital-downloads/easy-digital-downloads.php', $installed_plugins ) || array_key_exists( 'easy-digital-downloads-pro/easy-digital-downloads.php', $installed_plugins ),
			'basename'  => array_key_exists( 'easy-digital-downloads-pro/easy-digital-downloads.php', $installed_plugins ) ? 'easy-digital-downloads-pro/easy-digital-downloads.php' : 'easy-digital-downloads/easy-digital-downloads.php',
			'slug'      => 'easy-digital-downloads',
			'settings'  => admin_url( 'edit.php?post_type=download' ),
		);
		// Ajax activation works only if the key is the same with the slug. keeping the older one for backwards compatibility
		$parsed_addons['easy-digital-downloads'] = $parsed_addons['easy_digital_downloads'];
		// MemberPress.
		$parsed_addons['memberpress'] = array(
			'active' => defined( 'MEPR_VERSION' ) && version_compare( MEPR_VERSION, '1.3.43', '>' ),
		);
		// MemberMouse.
		$parsed_addons['membermouse'] = array(
			'active' => class_exists( 'MemberMouse' ),
		);
		// LifterLMS.
		$parsed_addons['lifterlms'] = array(
			'active' => function_exists( 'LLMS' ) && version_compare( LLMS()->version, '3.32.0', '>=' ),
		);
		// Restrict Content Pro.
		$parsed_addons['rcp'] = array(
			'active' => class_exists( 'Restrict_Content_Pro' ) && version_compare( RCP_PLUGIN_VERSION, '3.5.4', '>=' ),
		);
		// GiveWP.
		$parsed_addons['givewp'] = array(
			'active' => function_exists( 'Give' ),
		);

		// Charitable WP.
		$parsed_addons['charitable'] = array(
			'active'    => class_exists( 'Charitable' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-charitable.png',
			'title'     => 'Charitable',
			'excerpt'   => __('Top-rated WordPress donation and fundraising plugin. Over 10,000+ non-profit organizations and website owners use Charitable to create fundraising campaigns and raise more money online.', 'google-analytics-for-wordpress'),
			'installed' => array_key_exists('charitable/charitable.php', $installed_plugins),
			'basename'  => 'charitable/charitable.php',
			'slug'      => 'charitable',
			'settings'  => admin_url('admin.php?page=charitable'),
		);

		// WPCode
		$parsed_addons['wpcode'] = array(
			'active'    => function_exists( 'WPCode' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-wpcode.png',
			'title'     => 'WPCode',
			'excerpt'   => __('Future proof your WordPress customizations with the most popular code snippet management plugin for WordPress. Trusted by over 1,500,000+ websites for easily adding code to WordPress right from the admin area.', 'google-analytics-for-wordpress'),
			'installed' => array_key_exists('insert-headers-and-footers/ihaf.php', $installed_plugins),
			'basename'  => 'insert-headers-and-footers/ihaf.php',
			'slug'      => 'insert-headers-and-footers',
			'settings'  => admin_url('admin.php?page=wpcode-settings'),
		);
		// WP Consent
		$parsed_addons['wpconsent'] = array(
			'active'    => defined( 'WPCONSENT_VERSION' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-wpconsent.png',
			'title'     => 'WP Consent',
			'excerpt'   => __( 'WP Consent is the easiest way to add a cookie consent banner to your WordPress website.' ),
			'installed' => array_key_exists( 'wpconsent-cookies-banner-privacy-suite/wpconsent.php', $installed_plugins ) || array_key_exists( 'wpconsent-premium/wpconsent-premium.php', $installed_plugins ),
			'basename'  => array_key_exists( 'wpconsent-premium/wpconsent-premium.php', $installed_plugins ) ? 'wpconsent-premium/wpconsent-premium.php' : 'wpconsent-cookies-banner-privacy-suite/wpconsent.php',
			'slug'      => 'wpconsent-cookies-banner-privacy-suite',
			'settings'  => admin_url( 'admin.php?page=wpconsent' ),
		);

		// Duplicator
		$parsed_addons['duplicator'] = array(
			'active'    => defined( 'DUPLICATOR_VERSION' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-duplicator.png',
			'title'     => 'Duplicator',
			'excerpt'   => __('Leading WordPress backup & site migration plugin. Over 1,500,000+ smart website owners use Duplicator to make reliable and secure WordPress backups to protect their websites. It also makes website migration really easy.', 'google-analytics-for-wordpress'),
			'installed' => array_key_exists('duplicator/duplicator.php', $installed_plugins),
			'basename'  => 'duplicator/duplicator.php',
			'slug'      => 'duplicator',
			'settings'  => admin_url('admin.php?page=duplicator-settings'),
		);

		// WishList Member.
		$parsed_addons['wishlist_member'] = array(
			'active' => function_exists( 'wishlistmember_instance' ),
		);
		// GiveWP Analytics.
		$parsed_addons['givewp_google_analytics'] = array(
			'active' => function_exists( 'Give_Google_Analytics' ),
		);
		// Cookiebot.
		$parsed_addons['cookiebot'] = array(
			'active' => function_exists( 'monsterinsights_is_cookiebot_active' ) && monsterinsights_is_cookiebot_active(),
		);
		// Cookie Notice.
		$parsed_addons['cookie_notice'] = array(
			'active' => class_exists( 'Cookie_Notice' ),
		);
		// Complianz.
		$parsed_addons['complianz'] = array(
			'active' => defined( 'cmplz_plugin' ) || defined( 'cmplz_premium' ) || defined( 'cmplz_free' ),
		);
		// Cookie Yes
		$parsed_addons['cookie_yes'] = array(
			'active' => defined( 'CLI_SETTINGS_FIELD' ),
		);
		// Consent Manager
		$parsed_addons['consentmanager'] = array(
			'active' => class_exists( 'ConsentManagerMain' ),
		);
		// Google AMP.
		$parsed_addons['google_amp'] = array(
			'active' => defined( 'AMP__FILE__' ),
		);
		// Yoast SEO.
		$parsed_addons['yoast_seo'] = array(
			'active' => defined( 'WPSEO_VERSION' ),
		);
		// EasyAffiliate.
		$parsed_addons['easy_affiliate'] = array(
			'active' => defined( 'ESAF_EDITION' ),
		);

		// AffiliateWP
		$parsed_addons['affiliate-wp'] = array(
			'active'    => class_exists('AffiliateWP_Core_Requirements_Check'),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-affiliate-wp.png',
			'title'     => 'AffiliateWP',
			'excerpt'   => __('The #1 affiliate management plugin for WordPress. Easily create an affiliate program for your eCommerce store or membership site within minutes and start growing your sales with the power of referral marketing.', 'google-analytics-for-wordpress'),
			'installed' => array_key_exists('affiliate-wp/affiliate-wp.php', $installed_plugins),
			'basename'  => 'affiliate-wp/affiliate-wp.php',
			'slug'      => 'affiliate-wp',
			'settings'  => admin_url('admin.php?page=affiliate-wp-settings'),
			'redirect'  => 'https://affiliatewp.com',
		);

		// WP Simple Pay
		$parsed_addons['wpsimplepay'] = array(
			'active'    => defined('SIMPLE_PAY_VERSION'),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-wp-simple-pay.png',
			'title'     => 'WP Simple Pay',
			'excerpt'   => __('The #1 Stripe payments plugin for WordPress. Start accepting one-time and recurring payments on your WordPress site without setting up a shopping cart. No code required.', 'google-analytics-for-wordpress'),
			'installed' => array_key_exists('stripe/stripe-checkout.php', $installed_plugins),
			'basename'  => 'stripe/stripe-checkout.php',
			'slug'      => 'stripe',
			'settings'  => admin_url('edit.php?post_type=simple-pay&page=simpay_settings'),
		);

		// Sugar Calendar
		$parsed_addons['sugarcalendar'] = array(
			'active'    => class_exists('Sugar_Calendar\\Requirements_Check'),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-sugar-calendar.png',
			'title'     => 'Sugar Calendar',
			'excerpt'   => __('A simple & powerful event calendar plugin for WordPress that comes with all the event management features including payments, scheduling, timezones, ticketing, recurring events, and more.', 'google-analytics-for-wordpress'),
			'installed' => array_key_exists('sugar-calendar-lite/sugar-calendar-lite.php', $installed_plugins),
			'basename'  => 'sugar-calendar-lite/sugar-calendar-lite.php',
			'slug'      => 'sugar-calendar-lite',
			'settings'  => admin_url('admin.php?page=sugar-calendar'),
		);

		// WPForms.
		$parsed_addons['wpforms-lite'] = array(
			'active'    => function_exists( 'wpforms' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-wpforms.png',
			'title'     => 'WPForms',
			'excerpt'   => __( 'The best drag & drop WordPress form builder. Easily create beautiful contact forms, surveys, payment forms, and more with our 1000+ form templates. Trusted by over 6 million websites as the best forms plugin.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'wpforms-lite/wpforms.php', $installed_plugins ) || array_key_exists( 'wpforms/wpforms.php', $installed_plugins ),
			'basename'  => 'wpforms-lite/wpforms.php',
			'slug'      => 'wpforms-lite',
			'settings'  => admin_url( 'admin.php?page=wpforms-overview' ),
		);

		// UserFeedback.
		$parsed_addons['userfeedback-lite'] = array(
			'active'    => function_exists( 'userfeedback' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugin-userfeedback.png',
			'title'     => 'UserFeedback',
			'excerpt'   => __( 'Ask visitors questions about how they use your website or what features can make you more money.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'userfeedback-lite/userfeedback.php', $installed_plugins ) || array_key_exists( 'userfeedback/userfeedback.php', $installed_plugins ),
			'basename'  => 'userfeedback-lite/userfeedback.php',
			'slug'      => 'userfeedback-lite',
			'settings'  => admin_url( 'admin.php?page=userfeedback_settings' ),
		);

		// RewardsWP.
		$parsed_addons['rewardswp'] = array(
			'active'    => function_exists( 'am_rewardswp' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-rewardswp.png',
			'title'     => 'RewardsWP',
			'excerpt'   => __( 'Build a powerful loyalty rewards program for your WooCommerce store and turn one-time buyers into loyal customers.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'rewardswp/rewardswp.php', $installed_plugins ) || array_key_exists( 'rewardswp-pro/rewardswp.php', $installed_plugins ),
			'basename'  => array_key_exists( 'rewardswp-pro/rewardswp.php', $installed_plugins ) ? 'rewardswp-pro/rewardswp.php' : 'rewardswp/rewardswp.php',
			'slug'      => 'rewardswp',
			'settings'  => admin_url( 'admin.php?page=rewardswp&setup=1' ),
			'store'     => admin_url( 'admin.php?page=rewardswp' ),
		);

		// AIOSEO.
		$parsed_addons['aioseo'] = array(
			'active'    => function_exists( 'aioseo' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-all-in-one-seo.png',
			'title'     => 'AIOSEO',
			'excerpt'   => __( 'The original WordPress SEO plugin and toolkit that improves your website’s search rankings. Comes with all the SEO features like Local SEO, WooCommerce SEO, sitemaps, SEO optimizer, schema, and more.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'all-in-one-seo-pack/all_in_one_seo_pack.php', $installed_plugins ) || array_key_exists( 'all-in-one-seo-pack-pro/all_in_one_seo_pack.php', $installed_plugins ),
			'basename'  => ( monsterinsights_is_installed_aioseo_pro() ) ? 'all-in-one-seo-pack-pro/all_in_one_seo_pack.php' : 'all-in-one-seo-pack/all_in_one_seo_pack.php',
			'slug'      => 'all-in-one-seo-pack',
			'settings'  => admin_url( 'admin.php?page=aioseo' ),
		);

		// FunnelKit Stripe Woo Payment Gateway.
		$parsed_addons['funnelkit-stripe-woo-payment-gateway'] = array(
			'active'    => class_exists( 'FKWCS_Gateway_Stripe' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-funnelkit-stripe-woo-payment-gateway.png',
			'title'     => 'Stripe Payment Gateway for WooCommerce',
			'excerpt'   => __( 'Stripe Payment Gateway for WooCommerce is an integrated solution that lets you accept payments on your online store for web and mobile.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'funnelkit-stripe-woo-payment-gateway/funnelkit-stripe-woo-payment-gateway.php', $installed_plugins ),
			'basename'  => 'funnelkit-stripe-woo-payment-gateway/funnelkit-stripe-woo-payment-gateway.php',
			'slug'      => 'funnelkit-stripe-woo-payment-gateway',
			'settings'  => admin_url( 'admin.php?page=wc-settings&tab=fkwcs_api_settings' ),
		);

		// Use the plugin dir name as the array key since AJAX activation in add-ons page won't work.
		$parsed_addons['all-in-one-seo-pack'] = $parsed_addons['aioseo'];

		// WP Mail Smtp.
		$parsed_addons['wp-mail-smtp'] = array(
			'active'    => function_exists( 'wp_mail_smtp' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-smtp.png',
			'title'     => 'WP Mail SMTP',
			'excerpt'   => __( 'Improve your WordPress email deliverability and make sure that your website emails reach user’s inbox with the #1 SMTP plugin for WordPress. Over 3 million websites use it to fix WordPress email issues.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'wp-mail-smtp/wp_mail_smtp.php', $installed_plugins ),
			'basename'  => 'wp-mail-smtp/wp_mail_smtp.php',
			'slug'      => 'wp-mail-smtp',
		);
		// SeedProd.
		$parsed_addons['coming-soon'] = array(
			'active'    => defined( 'SEEDPROD_VERSION' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-seedprod.png',
			'title'     => 'SeedProd',
			'excerpt'   => __( 'The fastest drag & drop landing page builder for WordPress. Create custom landing pages without writing code, connect them with your CRM, collect subscribers, and grow your audience. Trusted by 1 million sites.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'coming-soon/coming-soon.php', $installed_plugins ),
			'basename'  => 'coming-soon/coming-soon.php',
			'slug'      => 'coming-soon',
			'settings'  => admin_url( 'admin.php?page=seedprod_lite' ),
		);
		// RafflePress
		$parsed_addons['rafflepress'] = array(
			'active'    => defined('RAFFLEPRESS_VERSION'),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-rafflepress.png',
			'title'     => 'RafflePress',
			'excerpt'   => __( 'Turn your website visitors into brand ambassadors! Easily grow your email list, website traffic, and social media followers with the most powerful giveaways & contests plugin for WordPress.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'rafflepress/rafflepress.php', $installed_plugins ),
			'basename'  => 'rafflepress/rafflepress.php',
			'slug'      => 'rafflepress',
			'settings'  => admin_url( 'admin.php?page=rafflepress_lite' ),
		);
		// TrustPulse
		$parsed_addons['trustpulse'] = array(
			'active'    => defined('TRUSTPULSE_PLUGIN_VERSION'),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-trustpulse.png',
			'title'     => 'TrustPulse',
			'excerpt'   => __( 'Boost your sales and conversions by up to 15% with real-time social proof notifications. TrustPulse helps you show live user activity and purchases to help convince other users to purchase.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'trustpulse-api/trustpulse.php', $installed_plugins ),
			'basename'  => 'trustpulse-api/trustpulse.php',
			'slug'      => 'trustpulse-api',
		);

		$parsed_addons['trustpulse-api'] = $parsed_addons['trustpulse'];

		// SearchWP
		$parsed_addons['searchwp'] = array(
			'active'    => defined('SEARCHWP_LIVE_SEARCH_VERSION'),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-searchwp.png',
			'title'     => 'SearchWP',
			'excerpt'   => __('The most advanced WordPress search plugin. Customize your WordPress search algorithm, reorder search results, track search metrics, and everything you need to leverage search to grow your business.', 'google-analytics-for-wordpress'),
			'installed' => array_key_exists('searchwp-live-ajax-search/searchwp-live-ajax-search.php', $installed_plugins),
			'basename'  => 'searchwp-live-ajax-search/searchwp-live-ajax-search.php',
			'slug'      => 'searchwp-live-ajax-search',
			'settings'  => admin_url('admin.php?page=searchwp-live-search'),
		);

		// Smash Balloon (Instagram)
		$parsed_addons['instagram-feed'] = array(
			'active'    => defined( 'SBIVER' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-sb-instagram.png',
			'title'     => 'Smash Balloon Instagram Feeds',
			'excerpt'   => __( 'Easily display Instagram content on your WordPress site without writing any code. Comes with multiple templates, ability to show content from multiple accounts, hashtags, and more. Trusted by 1 million websites.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'instagram-feed/instagram-feed.php', $installed_plugins ),
			'basename'  => 'instagram-feed/instagram-feed.php',
			'slug'      => 'instagram-feed',
			'settings'  => admin_url( 'admin.php?page=sb-instagram-feed' ),
		);

		$parsed_addons['smash-balloon-instagram'] = $parsed_addons['instagram-feed'];

		// Smash Balloon (Facebook)
		$parsed_addons['custom-facebook-feed'] = array(
			'active'    => defined( 'CFFVER' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-sb-facebook.png',
			'title'     => 'Smash Balloon Facebook Feeds',
			'excerpt'   => __( 'Easily display Facebook content on your WordPress site without writing any code. Comes with multiple templates, ability to embed albums, group content, reviews, live videos, comments, and reactions.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'custom-facebook-feed/custom-facebook-feed.php', $installed_plugins ),
			'basename'  => 'custom-facebook-feed/custom-facebook-feed.php',
			'slug'      => 'custom-facebook-feed',
			'settings'  => admin_url( 'admin.php?page=cff-feed-builder' ),
		);

		$parsed_addons['smash-balloon-facebook'] = $parsed_addons['custom-facebook-feed'];

		// Smash Balloon (YouTube)
		$parsed_addons['smash-balloon-youtube'] = array(
			'active'    => defined('SBYVER'),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-sb-youtube.png',
			'title'     => 'Smash Balloon YouTube Feeds',
			'excerpt'   => __('Easily display YouTube videos on your WordPress site without writing any code. Comes with multiple layouts, ability to embed live streams, video filtering, ability to combine multiple channel videos, and more.', 'google-analytics-for-wordpress'),
			'installed' => array_key_exists('feeds-for-youtube/youtube-feed.php', $installed_plugins),
			'basename'  => 'feeds-for-youtube/youtube-feed.php',
			'slug'      => 'feeds-for-youtube',
			'settings'  => admin_url('admin.php?page=sby-feed-builder'),
		);

		// In Year in Review we need the exact slug for the key in order to make the AJAX activation work.
		$parsed_addons['feeds-for-youtube'] = $parsed_addons['smash-balloon-youtube'];

		// Smash Balloon (Twitter)
		$parsed_addons['smash-balloon-twitter'] = array(
			'active'    => defined('CTF_VERSION'),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-sb-twitter.png',
			'title'     => 'Smash Balloon Twitter Feeds',
			'excerpt'   => __('Easily display Twitter content in WordPress without writing any code. Comes with multiple layouts, ability to combine multiple Twitter feeds, Twitter card support, tweet moderation, and more.', 'google-analytics-for-wordpress'),
			'installed' => array_key_exists('custom-twitter-feeds/custom-twitter-feed.php', $installed_plugins),
			'basename'  => 'custom-twitter-feeds/custom-twitter-feed.php',
			'slug'      => 'custom-twitter-feeds',
			'settings'  => admin_url('admin.php?page=ctf-feed-builder'),
		);

		// We need the key of the addon to be exactly the slug. We should deprecate `smash-balloon-twitter` next version.
		$parsed_addons['custom-twitter-feeds'] = $parsed_addons['smash-balloon-twitter'];

		// PushEngage
		$parsed_addons['pushengage'] = array(
			'active'    => defined('PUSHENGAGE_VERSION'),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-pushengage.png',
			'title'     => 'PushEngage',
			'excerpt'   => __('Connect with your visitors after they leave your website with the leading web push notification software. Over 10,000+ businesses worldwide use PushEngage to send 15 billion notifications each month.', 'google-analytics-for-wordpress'),
			'installed' => array_key_exists( 'pushengage/main.php', $installed_plugins ),
			'basename'  => 'pushengage/main.php',
			'slug'      => 'pushengage',
		);

		// Uncanny Automator
		$parsed_addons['uncanny-automator'] = array(
			'active'    => function_exists('automator_get_recipe_id'),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-uncanny-automator.png',
			'title'     => 'Uncanny Automator',
			'excerpt'   => __('Automate everything with the #1 no-code Automation tool for WordPress.', 'google-analytics-for-wordpress'),
			'installed' => array_key_exists('uncanny-automator/uncanny-automator.php', $installed_plugins),
			'basename'  => 'uncanny-automator/uncanny-automator.php',
			'slug'      => 'uncanny-automator',
			'setup_complete' => class_exists( 'Uncanny_Automator\Api_Server' ) ? !! \Uncanny_Automator\Api_Server::is_automator_connected() : false,
			'wizard_url'     => admin_url( 'edit.php?post_type=uo-recipe&page=uncanny-automator-setup-wizard' ),
			'recipe_url'     => admin_url( 'post-new.php?post_type=uo-recipe' ),
		);

		// Pretty Links
		$parsed_addons['pretty-link'] = array(
			'active'    => class_exists( 'PrliBaseController' ),
			'icon'      => '',
			'title'     => 'Pretty Links',
			'excerpt'   => __( 'Pretty Links helps you shrink, beautify, track, manage and share any URL on or off of your WordPress website. Create links that look how you want using your own domain name!', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'pretty-link/pretty-link.php', $installed_plugins ),
			'basename'  => 'pretty-link/pretty-link.php',
			'slug'      => 'pretty-link',
			'settings'  => admin_url( 'edit.php?post_type=pretty-link' ),
		);
		// SearchWP
		$parsed_addons['searchwp-live-ajax-search'] = array(
			'active'    => defined( 'SEARCHWP_LIVE_SEARCH_VERSION' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugin-searchwp.svg',
			'title'     => 'SearchWP',
			'excerpt'   => __( 'The most advanced WordPress search plugin. Customize your WordPress search algorithm, reorder search results, track search metrics, and everything you need to leverage search to grow your business.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'searchwp-live-ajax-search/searchwp-live-ajax-search.php', $installed_plugins ),
			'basename'  => 'searchwp-live-ajax-search/searchwp-live-ajax-search.php',
			'slug'      => 'searchwp-live-ajax-search',
			'settings'  => admin_url( 'admin.php?page=searchwp-live-search' ),
		);
		// Sugar Calendar Lite
		$parsed_addons['sugar-calendar-lite'] = array(
			'active'    => class_exists( 'Sugar_Calendar\\Requirements_Check' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugin-sugar-calendar.svg',
			'title'     => 'Sugar Calendar',
			'excerpt'   => __( 'A simple & powerful event calendar plugin for WordPress that comes with all the event management features including payments, scheduling, timezones, ticketing, recurring events, and more.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'sugar-calendar-lite/sugar-calendar-lite.php', $installed_plugins ),
			'basename'  => 'sugar-calendar-lite/sugar-calendar-lite.php',
			'slug'      => 'sugar-calendar-lite',
			'settings'  => admin_url( 'admin.php?page=sugar-calendar' ),
		);
		// Charitable
		$parsed_addons['charitable'] = array(
			'active'    => class_exists( 'Charitable' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-charitable.png',
			'title'     => 'WP Charitable',
			'excerpt'   => __( 'Top-rated WordPress donation and fundraising plugin. Over 10,000+ non-profit organizations and website owners use Charitable to create fundraising campaigns and raise more money online.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'charitable/charitable.php', $installed_plugins ),
			'basename'  => 'charitable/charitable.php',
			'slug'      => 'charitable',
			'settings'  => admin_url( 'admin.php?page=charitable&install=true' ),
		);
		// WPCode
		$parsed_addons['insert-headers-and-footers'] = array(
			'active'    => class_exists( 'WPCode' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugin-wpcode.svg',
			'title'     => 'WPCode',
			'excerpt'   => __( 'Future proof your WordPress customizations with the most popular code snippet management plugin for WordPress. Trusted by over 1,500,000+ websites for easily adding code to WordPress right from the admin area.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'insert-headers-and-footers/ihaf.php', $installed_plugins ),
			'basename'  => 'insert-headers-and-footers/ihaf.php',
			'slug'      => 'insert-headers-and-footers',
			'settings'  => admin_url( 'admin.php?page=wpcode' ),
		);
		// Duplicator
		$parsed_addons['duplicator'] = array(
			'active'    => class_exists( 'DuplicatorPhpVersionCheck' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-duplicator.png',
			'title'     => 'Duplicator',
			'excerpt'   => __( 'Leading WordPress backup & site migration plugin. Over 1,500,000+ smart website owners use Duplicator to make reliable and secure WordPress backups to protect their websites. It also makes website migration really easy.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'duplicator/duplicator.php', $installed_plugins ),
			'basename'  => 'duplicator/duplicator.php',
			'slug'      => 'duplicator',
			'settings'  => admin_url( 'admin.php?page=duplicator' ),
		);
		// WP Simple Pay
		$parsed_addons['stripe'] = array(
			'active'    => defined( 'SIMPLE_PAY_MAIN_FILE' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugin-simple-pay.svg',
			'title'     => 'WP Simple Pay',
			'excerpt'   => __( 'Start accepting one-time and recurring payments on your WordPress site without setting up a shopping cart. No code required.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'stripe/stripe-checkout.php', $installed_plugins ),
			'basename'  => 'stripe/stripe-checkout.php',
			'slug'      => 'stripe',
			'settings'  => admin_url( 'edit.php?post_type=simple-pay&page=simpay_settings&tab=general' ),
		);
		if ( function_exists( 'WC' ) ) {
			// Advanced Coupons
			$parsed_addons['advancedcoupons'] = array(
				'active'    => class_exists( 'ACFWF' ),
				'icon'      => '',
				'title'     => 'Advanced Coupons',
				'excerpt'   => __( 'Advanced Coupons for WooCommerce (Free Version) gives WooCommerce store owners extra coupon features so they can market their stores better.', 'google-analytics-for-wordpress' ),
				'installed' => array_key_exists( 'advanced-coupons-for-woocommerce-free/advanced-coupons-for-woocommerce-free.php', $installed_plugins ),
				'basename'  => 'advanced-coupons-for-woocommerce-free/advanced-coupons-for-woocommerce-free.php',
				'slug'      => 'advanced-coupons-for-woocommerce-free',
				'settings'  => admin_url( 'edit.php?post_type=shop_coupon&acfw' ),
			);
		}

		// UserFeedback.
		$parsed_addons['userfeedback-lite'] = array(
			'active'    => function_exists( 'userfeedback' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-userfeedback.png',
			'title'     => 'UserFeedback',
			'excerpt'   => __( 'See what your analytics software isn’t telling you with powerful UserFeedback surveys.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'userfeedback-lite/userfeedback.php', $installed_plugins ) || array_key_exists( 'userfeedback/userfeedback.php', $installed_plugins ),
			'basename'  => 'userfeedback-lite/userfeedback.php',
			'slug'      => 'userfeedback-lite',
			'settings'  => admin_url( 'admin.php?page=userfeedback_onboarding' ),
			'surveys'  => admin_url( 'admin.php?page=userfeedback_surveys' ),
			'setup_complete'  => (get_option('userfeedback_onboarding_complete', 0) == 1),
		);

		// RewardsWP.
		$parsed_addons['rewardswp'] = array(
			'active'         => function_exists( 'am_rewardswp' ),
			'icon'           => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-rewardswp.png',
			'title'          => 'RewardsWP',
			'excerpt'        => __( 'Build a powerful loyalty rewards program for your WooCommerce store and turn one-time buyers into loyal customers.', 'google-analytics-for-wordpress' ),
			'installed'      => array_key_exists( 'rewardswp/rewardswp.php', $installed_plugins ) || array_key_exists( 'rewardswp-pro/rewardswp.php', $installed_plugins ),
			'basename'       => array_key_exists( 'rewardswp-pro/rewardswp.php', $installed_plugins ) ? 'rewardswp-pro/rewardswp.php' : 'rewardswp/rewardswp.php',
			'slug'           => 'rewardswp',
			'settings'       => admin_url( 'admin.php?page=rewardswp&setup=1' ),
			'store'          => admin_url( 'admin.php?page=rewardswp' ),
			'setup_complete' => ( get_option( 'rewardswp_onboarding_complete', 0 ) == 1 ),
		);

		// Gravity Forms.
		$parsed_addons['gravity_forms'] = array(
			'active' => class_exists( 'GFCommon' ),
		);
		// Formidable Forms.
		$parsed_addons['formidable_forms'] = array(
			'active' => class_exists( 'FrmHooksController' ),
		);

		// OptinMonster.
		$parsed_addons['optinmonster'] = array(
			'active'    => class_exists( 'OMAPI' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-om.png',
			'title'     => 'OptinMonster',
			'excerpt'   => __( 'Instantly get more subscribers, leads, and sales with the #1 conversion optimization toolkit. Create high converting popups, announcement bars, spin a wheel, and more with smart targeting and personalization.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'optinmonster/optin-monster-wp-api.php', $installed_plugins ),
			'basename'  => 'optinmonster/optin-monster-wp-api.php',
			'slug'      => 'optinmonster',
			'settings'  => admin_url( 'admin.php?page=optin-monster-dashboard' ),
		);

		// Manual UA Addon.
		if ( ! isset( $parsed_addons['manual_ua'] ) ) {
			$parsed_addons['manual_ua'] = array(
				'active' => class_exists( 'MonsterInsights_Manual_UA' ),
			);
		}

		// Universally. Promoted contextually across reports/settings (GH-3374);
		// installed/active state drives whether those prompts render.
		$parsed_addons['universally'] = array(
			'active'    => defined( 'UNIVERSALLY_VERSION' ),
			'icon'      => '',
			'title'     => 'Universally',
			'excerpt'   => __( 'Automatically translate your website into 110+ languages with AI to reach a global audience and grow international traffic.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'universally-language-translation-multilingual-tool/universally.php', $installed_plugins ),
			'basename'  => 'universally-language-translation-multilingual-tool/universally.php',
			'slug'      => 'universally-language-translation-multilingual-tool',
			// Universally's own settings screen (top-level menu slug `universally_settings`),
			// so the Setup Checklist "installed" state links there instead of the MI Addons page.
			'settings'  => admin_url( 'admin.php?page=universally_settings' ),
		);

		$parsed_addons = apply_filters('monsterinsights_parsed_addons', $parsed_addons);

		if ( true !== $is_onboarding ) {
			wp_send_json( $parsed_addons );
		}

		return $parsed_addons;
	}


