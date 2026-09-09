<?php
/***
*
*Found actions: 74
*Found functions:56
*Extracted functions:49
*Total parameter names extracted: 32
*Overview: {'get_playlist_post_preview': {'sbi_source_get_playlist_post_preview'}, 'ajax_record_session': {'sby_smash_usage_record_session'}, 'ajax_activate_license': {'sby_license_activation'}, 'SmashBalloon\\YouTubeFeed\\Builder\\SBY_Source': {'sbi_source_builder_update_multiple', 'sbi_source_get_page', 'sbi_source_get_playlist_post_preview', 'sbi_source_get_featured_post_preview', 'sbi_source_builder_update'}, 'dismiss': {'sby_dashboard_notification_dismiss'}, 'sby_process_wp_posts': {'nopriv_sby_check_wp_submit', 'sby_check_wp_submit'}, 'sby_install_addon': {'sby_install_other_plugins'}, 'ajax_verify_api_key': {'verify_api_key'}, 'handle_settings_update': {'sby_update_settings'}, 'check_license': {'sby_check_license'}, 'sby_activate_addon': {'sby_activate_other_plugins'}, 'ajax_remove_account': {'remove_connected_account'}, 'ajax_install_wpconsent': {'sby_install_wpconsent'}, 'sby_delete_connected_account': {'sby_ca_after_remove_clicked'}, 'handle_ajax': {'sb_feature_suggestion', 'sb_deactivation_feedback'}, 'ajax_activate_addon': {'sby_activate_addon'}, 'get_page': {'sbi_source_get_page'}, 'sby_dismiss_api_key_notice': {'sby_dismiss_api_key_notice'}, 'sby_get_next_post_set': {'sby_load_more_clicked', 'nopriv_sby_load_more_clicked'}, 'SmashBalloon\\YouTubeFeed\\Builder\\SBY_Feed_Saver_Manager': {'sby_feed_saver_manager_fly_preview', 'sby_feed_refresh', 'sby_feed_saver_manager_get_feed_list_page', 'sby_feed_saver_clear_comments_cache', 'sby_feed_saver_manager_clear_single_feed_cache', 'sby_feed_handle_saver_manager_fly_preview', 'sby_feed_saver_manager_builder_update', 'sby_feed_saver_manager_delete_feeds', 'sby_dismiss_onboarding', 'sby_feed_saver_manager_duplicate_feed'}, 'get_featured_post_preview': {'sbi_source_get_featured_post_preview'}, 'builder_update_multiple': {'sbi_source_builder_update_multiple'}, 'review_notice_consent': {'sby_review_notice_consent_update'}, 'sby_dismiss_connect_warning_notice': {'sby_dismiss_connect_warning_button'}, 'recheck_license_upgrade': {'sby_recheck_license_upgrade'}, 'preview': {'sb_youtubefeed_divi_preview'}, 'on_feed_saved': {'sby_feed_saver_manager_builder_update'}, 'sby_recheck_connection': {'sby_recheck_connection'}, 'ajax_clear_cache': {'sby_clear_cache'}, 'Smashballoon\\Customizer\\Feed_Saver_Manager': {'sbc_feed_saver_manager_fly_preview'}, 'SmashBalloon\\YouTubeFeed\\Admin\\SBY_Upgrader': {'nopriv_sby_run_one_click_upgrade', 'sby_maybe_upgrade_redirect'}, 'hide_frontend_license_error': {'sby_hide_frontend_license_error'}, 'sby_get_live_retrieve': {'nopriv_sby_live_retrieve', 'sby_live_retrieve'}, 'ajax_all_video_action': {'sby_all_videos_action'}, 'manual_access_token': {'sby_manual_access_token'}, 'SmashBalloon\\YouTubeFeed\\Builder\\SBI_Feed_Builder': {'sbi_dismiss_onboarding'}, 'sby_delete_wp_posts': {'sby_delete_wp_posts'}, 'sby_dismiss_at_warning_notice': {'sby_dismiss_at_warning_notice'}, 'test_connection': {'sby_check_connection'}, 'sbspf_account_search': {'sbspf_account_search'}, 'ajax_query_single_videos': {'sby_get_single_videos'}, 'builder_update': {'sbi_source_builder_update'}, 'dismiss_upgrade_notice': {'sby_dismiss_upgrade_notice'}, 'sby_single_videos_upsell_modal': {'sby_single_videos_upsell_modal'}, 'dismiss_license_notice': {'sby_dismiss_license_notice'}, 'ajax_install_addon': {'sby_install_addon'}, 'ajax_dismiss_wizard': {'sby_dismiss_wizard'}, 'ajax_handle_file_import': {'sby_do_feed_import'}, 'ajax_deactivate_license': {'sby_license_deactivation'}, 'sby_process_access_token': {'sby_process_access_token'}, 'ajax_activate_wpconsent': {'sby_activate_wpconsent'}, 'sby_do_import_batch': {'sby_do_import_batch'}, 'ajax_process_wizard': {'sby_process_wizard'}, 'install_plugin': {'am_recommended_block_install'}, 'sby_api_key': {'sby_add_api_key'}, 'sby_other_plugins_modal': {'sby_other_plugins_modal'}}
*
***/

/** Function get_playlist_post_preview() called by wp_ajax hooks: {'sbi_source_get_playlist_post_preview'} **/
/** No function found :-/ **/


/** Function ajax_record_session() called by wp_ajax hooks: {'sby_smash_usage_record_session'} **/
/** Parameters found in function ajax_record_session(): {"post": ["duration_seconds"]} **/
function ajax_record_session() {
		check_ajax_referer( 'sby_smash_usage_record_session', 'nonce' );
		// Same capability the plugin's own pages gate on: a user holding only
		// the custom manage_youtube_feed_options cap sees the admin pages and
		// gets the script enqueued, so manage_options alone would silently
		// drop every session they generate.
		$can = function_exists( 'sby_current_user_can' ) ? sby_current_user_can( 'manage_youtube_feed_options' ) : current_user_can( 'manage_options' );
		if ( ! $can || ! Config::is_enabled() ) {
			wp_send_json_error();
		}
		$seconds = isset( $_POST['duration_seconds'] ) ? (int) $_POST['duration_seconds'] : 0;
		EventRecorder::record_session_duration( $seconds );
		wp_send_json_success();
	}


/** Function ajax_activate_license() called by wp_ajax hooks: {'sby_license_activation'} **/
/** Parameters found in function ajax_activate_license(): {"post": ["license_key"]} **/
function ajax_activate_license() {
		check_ajax_referer( 'sby-admin', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error(); // This auto-dies.
		}

		$license_key = sanitize_text_field($_POST['license_key']);

		$response = $this->sby_activate_license($license_key);

		if ( $response === true ) {
			wp_send_json_success( [
				'licenseStatus' => $this->get_license_status(),
				'licenseData'   => $this->get_license_data()
			] );
		}

		wp_send_json_error();
	}


/** Function SmashBalloon\YouTubeFeed\Builder\SBY_Source() called by wp_ajax hooks: {'sbi_source_builder_update_multiple', 'sbi_source_get_page', 'sbi_source_get_playlist_post_preview', 'sbi_source_get_featured_post_preview', 'sbi_source_builder_update'} **/
/** No function found :-/ **/


/** Function dismiss() called by wp_ajax hooks: {'sby_dashboard_notification_dismiss'} **/
/** Parameters found in function dismiss(): {"get": ["sby_ignore_rating_notice_nag", "sby_ignore_new_user_sale_notice", "sby_ignore_bfcm_sale_notice", "sby_dismiss", "_wpnonce"]} **/
function dismiss() {
		// Bail before doing any work unless a dismissal parameter is actually present. This
		// hook runs on every single admin_init, so the capability and nonce machinery below
		// should not fire on the overwhelming majority of requests that are not a dismissal —
		// including the anonymous admin-ajax.php bootstrap. (SMASH-1799)
		if ( ! isset( $_GET['sby_ignore_rating_notice_nag'] )
		     && ! isset( $_GET['sby_ignore_new_user_sale_notice'] )
		     && ! isset( $_GET['sby_ignore_bfcm_sale_notice'] )
		     && ! isset( $_GET['sby_dismiss'] ) ) {
			return;
		}

		// admin-ajax.php fires admin_init during bootstrap before the action dispatch, so this
		// callback is reachable unauthenticated with is_admin() returning true — the option and
		// user meta writes below need their own capability check. (SMASH-1799)
		if ( ! sby_current_user_can( 'manage_youtube_feed_options' ) ) {
			return;
		}

		// The dismissal triggers are plain GET links, so without a nonce any third-party
		// page could dismiss these notices for a logged-in admin (CSRF). The nonce is
		// added to the links generated in output(). (SMASH-1799)
		$nonce = isset( $_GET['_wpnonce'] ) ? sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'sby-newuser-dismiss' ) ) {
			return;
		}

		global $current_user;
		$user_id = $current_user->ID;
		$sby_statuses_option = get_option( 'sby_statuses', array() );

		if ( isset( $_GET['sby_ignore_rating_notice_nag'] ) ) {
			if ( (int)$_GET['sby_ignore_rating_notice_nag'] === 1 ) {
				update_option( 'sby_rating_notice', 'dismissed', false );
				$sby_statuses_option['rating_notice_dismissed'] = sby_get_current_time();
				update_option( 'sby_statuses', $sby_statuses_option, false );

			} elseif ( $_GET['sby_ignore_rating_notice_nag'] === 'later' ) {
				set_transient( 'feeds_for_youtube_rating_notice_waiting', 'waiting', 2 * WEEK_IN_SECONDS );
				update_option( 'sby_rating_notice', 'pending', false );
			}
		}

		if ( isset( $_GET['sby_ignore_new_user_sale_notice'] ) ) {
			$response = sanitize_text_field( $_GET['sby_ignore_new_user_sale_notice'] );
			if ( $response === 'always' ) {
				update_user_meta( $user_id, 'sby_ignore_new_user_sale_notice', 'always' );

				$current_month_number = (int)date('n', sby_get_current_time() );
				$not_early_in_the_year = ($current_month_number > 5);

				if ( $not_early_in_the_year ) {
					update_user_meta( $user_id, 'sby_ignore_bfcm_sale_notice', date( 'Y', sby_get_current_time() ) );
				}

			}
		}

		if ( isset( $_GET['sby_ignore_bfcm_sale_notice'] ) ) {
			$response = sanitize_text_field( $_GET['sby_ignore_bfcm_sale_notice'] );
			if ( $response === 'always' ) {
				update_user_meta( $user_id, 'sby_ignore_bfcm_sale_notice', 'always' );
			} elseif ( $response === date( 'Y', sby_get_current_time() ) ) {
				update_user_meta( $user_id, 'sby_ignore_bfcm_sale_notice', date( 'Y', sby_get_current_time() ) );
			}
			update_user_meta( $user_id, 'sby_ignore_new_user_sale_notice', 'always' );
		}

		if ( isset( $_GET['sby_dismiss'] ) ) {
			if ( $_GET['sby_dismiss'] === 'review' ) {
				update_option( 'sby_rating_notice', 'dismissed', false );
				$sby_statuses_option['rating_notice_dismissed'] = sby_get_current_time();
				update_option( 'sby_statuses', $sby_statuses_option, false );
			} elseif ( $_GET['sby_dismiss'] === 'discount' ) {
				update_user_meta( $user_id, 'sby_ignore_new_user_sale_notice', 'always' );

				$current_month_number = (int)date('n', sby_get_current_time() );
				$not_early_in_the_year = ($current_month_number > 5);

				if ( $not_early_in_the_year ) {
					update_user_meta( $user_id, 'sby_ignore_bfcm_sale_notice', date( 'Y', sby_get_current_time() ) );
				}
			}
			update_user_meta( $user_id, 'sby_ignore_new_user_sale_notice', 'always' );
		}
	}


/** Function sby_process_wp_posts() called by wp_ajax hooks: {'nopriv_sby_check_wp_submit', 'sby_check_wp_submit'} **/
/** Parameters found in function sby_process_wp_posts(): {"post": ["feed_id", "atts", "location", "post_id", "offset", "posts", "cache_all"]} **/
function sby_process_wp_posts() {
		// Check nonce for security
		check_ajax_referer( 'sby_nonce', 'nonce' );

		// The 'sby' substring test that used to sit here was never a security control — any
		// value containing those three letters passed. The real gate is now in
		// sby_add_or_update_wp_posts(): the transient name is derived from the resolved
		// settings and the caller must match it, so here we only require a value to compare
		// against later (SMASH-1799).
		$feed_id = isset( $_POST['feed_id'] ) ? sanitize_text_field( wp_unslash( $_POST['feed_id'] ) ) : '';

		if ( '' === $feed_id ) {
			wp_send_json_error( '', 400 );
		}

		$atts_raw = isset( $_POST['atts'] ) ? json_decode( stripslashes( $_POST['atts'] ), true ) : array();
		if ( is_array( $atts_raw ) ) {
			$atts = array_map( 'sanitize_text_field', $atts_raw );

			// SBY_Settings inherits a broad wp_parse_args( $atts, $db ) merge, so a caller could
			// override behavioral settings that have nothing to do with naming a feed. Confine
			// the payload to the keys that resolve the feed's type/terms plus the ones baked
			// into the transient name (includewords/excludewords/num), so the derived name still
			// matches what the rendered shortcode produced (SMASH-1799).
			$atts = array_intersect_key(
				$atts,
				array_flip( array( 'feed', 'channel', 'playlist', 'search', 'customsearch', 'usecustomsearch', 'live', 'favorites', 'single', 'id', 'type', 'includewords', 'excludewords', 'num' ) )
			);
		} else {
			$atts = array();
		}
		$location = isset( $_POST['location'] ) && in_array( $_POST['location'], array( 'header', 'footer', 'sidebar', 'content' ), true ) ? sanitize_text_field( $_POST['location'] ) : 'unknown';
		$post_id = isset( $_POST['post_id'] ) && $_POST['post_id'] !== 'unknown' ? (int)$_POST['post_id'] : 'unknown';
		$feed_details = array(
			'feed_id' => $feed_id,
			'atts' => $atts,
			'location' => array(
				'post_id' => $post_id,
				'html' => $location
			)
		);

		$offset = isset( $_POST['offset'] ) ? (int)$_POST['offset'] : 0;
		$vid_ids = isset( $_POST['posts'] ) && is_array( $_POST['posts'] ) ? $_POST['posts'] : array();

		if ( ! empty( $vid_ids ) ) {
			// The shipped JS only ever sends plain video ids here (mostRecentlyLoadedPosts is
			// filled from data-video-id attributes) — the richer post structures that
			// sby_process_post_set_caching() also accepts come from the server-side cache and
			// cron paths, never from this request. Each id sent to SBY_YT_Details_Query is spent
			// against the site's own API key / OAuth token, so require the real YouTube id shape
			// before spending quota on it (SMASH-1799).
			$vid_ids = array_values( array_filter( $vid_ids, 'is_string' ) );
			$vid_ids = array_map( 'sanitize_text_field', array_map( 'wp_unslash', $vid_ids ) );
			$vid_ids = array_values( array_filter( $vid_ids, function ( $vid_id ) {
				return (bool) preg_match( '/^[A-Za-z0-9_-]{11}$/', $vid_id );
			} ) );

			// Cap the fan-out: YouTube's videos.list endpoint takes at most 50 ids per call and
			// one reveal batch in the shipped JS is never larger than a page of items, so 50 is
			// a hard ceiling no legitimate request reaches while a forged request can no longer
			// spend unbounded quota. A constant is used instead of the feed's `num` setting
			// because the caller controls the atts that `num` resolves from (SMASH-1799).
			$vid_ids = array_slice( $vid_ids, 0, 50 );
		}

		$cache_all = isset( $_POST['cache_all'] ) ? $_POST['cache_all'] === 'true' : false;

		$info = $this->sby_add_or_update_wp_posts( $vid_ids, $feed_id, $atts, $offset, $cache_all );

		// Feed_Locator::add_or_update_entry() writes a row, and should_clear_old_locations() can
		// trigger delete_old_locations() — both state changes on an endpoint registered nopriv.
		// This used to run BEFORE the transient name was validated, so an unauthenticated caller
		// could file arbitrary feed_id/atts rows in the locator table (and trip the delete sweep)
		// even though the caching work below then failed the hash_equals gate and returned 403.
		// Recording the location only after that gate has passed means the row can only describe
		// a feed the resolved settings actually own. (SMASH-1799)
		$this->sby_do_background_tasks( $feed_details );

		echo wp_json_encode( $info );

		die();
	}


/** Function sby_install_addon() called by wp_ajax hooks: {'sby_install_other_plugins'} **/
/** Parameters found in function sby_install_addon(): {"post": ["plugin", "type"]} **/
function sby_install_addon() {
		require_once trailingslashit( SBY_PLUGIN_DIR ) . 'inc/PluginSilentUpgrader.php';
		require_once trailingslashit( SBY_PLUGIN_DIR ) . 'inc/PluginSilentUpgraderSkin.php';
		// Run a security check.
		check_ajax_referer( 'sby-admin', 'nonce' );

		// Check for permissions.
		if ( ! sby_current_user_can( 'manage_youtube_feed_options' ) ) {
			wp_send_json_error();
		}

		$error = esc_html__( 'Could not install addon. Please download from wpforms.com and install manually.', 'feeds-for-youtube' );

		if ( empty( $_POST['plugin'] ) ) {
			wp_send_json_error( $error );
		}

		// Set the current screen to avoid undefined notices.
		set_current_screen( 'youtube-feeds-pro' );

		// Prepare variables.
		$url = esc_url_raw(
			add_query_arg(
				array(
					'page' => 'sby-feed-builder',
				),
				admin_url( 'admin.php' )
			)
		);

		$creds = request_filesystem_credentials( $url, '', false, false, null );

		// Check for file system permissions.
		if ( false === $creds ) {
			wp_send_json_error( $error );
		}

		if ( ! WP_Filesystem( $creds ) ) {
			wp_send_json_error( $error );
		}

		/*
		 * We do not need any extra credentials if we have gotten this far, so let's install the plugin.
		 */
		require_once SBY_PLUGIN_DIR . 'inc/class-install-skin.php';

		// Do not allow WordPress to search/download translations, as this will break JS output.
		remove_action( 'upgrader_process_complete', array( 'Language_Pack_Upgrader', 'async_upgrade' ), 20 );

		// Create the plugin upgrader with our custom skin.
		$installer = new \SmashBalloon\YouTubeFeed\PluginSilentUpgrader( new \SmashBalloon\YouTubeFeed\SBY_Install_Skin() );

		// Error check.
		if ( ! method_exists( $installer, 'install' ) || empty( $_POST['plugin'] ) ) {
			wp_send_json_error( $error );
		}

		$installer->install( $_POST['plugin'] ); // phpcs:ignore

		// Flush the cache and return the newly installed plugin basename.
		wp_cache_flush();

		$plugin_basename = $installer->plugin_info();

		if ( $plugin_basename ) {

			$type = 'addon';
			if ( ! empty( $_POST['type'] ) ) {
				$type = sanitize_key( $_POST['type'] );
			}

			// Activate the plugin silently.
			$activated = activate_plugin( $plugin_basename );

			if ( ! is_wp_error( $activated ) ) {
				wp_send_json_success(
					array(
						'msg'          => 'plugin' === $type ? esc_html__( 'Plugin installed & activated.', 'feeds-for-youtube' ) : esc_html__( 'Addon installed & activated.', 'feeds-for-youtube' ),
						'is_activated' => true,
						'basename'     => $plugin_basename,
					)
				);
			} else {
				wp_send_json_success(
					array(
						'msg'          => 'plugin' === $type ? esc_html__( 'Plugin installed.', 'feeds-for-youtube' ) : esc_html__( 'Addon installed.', 'feeds-for-youtube' ),
						'is_activated' => false,
						'basename'     => $plugin_basename,
					)
				);
			}
		}

		wp_send_json_error( $error );
	}


/** Function ajax_verify_api_key() called by wp_ajax hooks: {'verify_api_key'} **/
/** Parameters found in function ajax_verify_api_key(): {"post": ["api_key"]} **/
function ajax_verify_api_key() {
		Util::ajaxPreflightChecks();

		$settings = $this->settings->get_settings();
		$api_key = sanitize_text_field($_POST['api_key']);

		if ( empty( $api_key ) ) {
			$this->respond_to_api_update($settings, $api_key);
		}

		$this->connect->set_url(null, null, ['username' => 'smashballoon'], $api_key);
		$response = wp_remote_get($this->connect->get_url(), $this->connect->get_args());

		$this->api_verification_data->response = $response['response'];
		$this->api_verification_data->data = json_decode($response['body']);
		$this->api_verification_data->status = $response['response']['code'] === 200;

		$this->respond_to_api_update($settings, $api_key);

		wp_send_json_error();
	}


/** Function handle_settings_update() called by wp_ajax hooks: {'sby_update_settings'} **/
/** Parameters found in function handle_settings_update(): {"post": ["nonce", "custom_css", "custom_js"]} **/
function handle_settings_update() {
		check_ajax_referer( 'sby-admin', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		$sanitization_skip = [
			'custom_js'
		];

		unset( $_POST['nonce'] );

		if ( ! current_user_can( 'unfiltered_html' ) ) {
			unset($_POST['custom_css']);
			unset($_POST['custom_js']);
		}

		$update_array = $_POST;

		$this->handle_single_video_settings( $update_array );

		foreach ( $_POST as $item => $value ) {
			if ( ! in_array( $item, $sanitization_skip ) ) {
				$update_array[ $item ] = sanitize_text_field( $value );
			}
		}

		wp_clear_scheduled_hook('sby_feed_update');

		if ( $this->settings->update_settings( $update_array ) ) {
			wp_send_json_success();
		} else {
			wp_send_json_error();
		}
	}


/** Function check_license() called by wp_ajax hooks: {'sby_check_license'} **/
/** No params detected :-/ **/


/** Function sby_activate_addon() called by wp_ajax hooks: {'sby_activate_other_plugins'} **/
/** Parameters found in function sby_activate_addon(): {"post": ["plugin", "type"]} **/
function sby_activate_addon() {
		require_once trailingslashit( SBY_PLUGIN_DIR ) . 'inc/PluginSilentUpgrader.php';
		require_once trailingslashit( SBY_PLUGIN_DIR ) . 'inc/PluginSilentUpgraderSkin.php';
		require_once SBY_PLUGIN_DIR . 'inc/class-install-skin.php';
		// Run a security check.
		check_ajax_referer( 'sby-admin', 'nonce' );

		// Check for permissions.
		if ( ! sby_current_user_can( 'manage_youtube_feed_options' ) ) {
			wp_send_json_error();
		}

		if ( isset( $_POST['plugin'] ) ) {
			$type = 'addon';
			if ( ! empty( $_POST['type'] ) ) {
				$type = sanitize_key( $_POST['type'] );
			}
			$activate = activate_plugins( $_POST['plugin'] );
			if ( ! is_wp_error( $activate ) ) {
				if ( 'plugin' === $type ) {
					wp_send_json_success( esc_html__( 'Plugin activated.', 'feeds-for-youtube' ) );
				} else {
					wp_send_json_success( esc_html__( 'Addon activated.', 'feeds-for-youtube' ) );
				}
			}
		}

		wp_send_json_error( esc_html__( 'Could not activate addon. Please activate from the Plugins page.', 'feeds-for-youtube' ) );
	}


/** Function ajax_remove_account() called by wp_ajax hooks: {'remove_connected_account'} **/
/** Parameters found in function ajax_remove_account(): {"post": ["account_id"]} **/
function ajax_remove_account() {
		Util::ajaxPreflightChecks();

		$account  = trim( sanitize_text_field( $_POST['account_id'] ) );
		$settings = $this->settings->get_settings();
		$connected_accounts = $settings['connected_accounts'];
		if ( ! empty( $account ) && ! empty( $connected_accounts ) && array_key_exists( $account,
				$connected_accounts ) ) {
			unset( $connected_accounts[ $account ] );
			$settings['connected_accounts'] = $connected_accounts;
			$this->settings->update_settings( $settings );
		}
	}


/** Function ajax_install_wpconsent() called by wp_ajax hooks: {'sby_install_wpconsent'} **/
/** No params detected :-/ **/


/** Function sby_delete_connected_account() called by wp_ajax hooks: {'sby_ca_after_remove_clicked'} **/
/** Parameters found in function sby_delete_connected_account(): {"post": ["sbspf_nonce", "account_id"]} **/
function sby_delete_connected_account() {
		if ( ! isset( $_POST['sbspf_nonce'] ) || ! isset( $_POST['account_id']) ) return;
		$nonce = $_POST['sbspf_nonce'];
		if ( ! wp_verify_nonce( $nonce, 'sbspf_nonce' ) ) {
			die ( 'You did not do this the right way!' );
		}

		global $sby_settings;

		$account_id = sanitize_text_field( $_POST['account_id'] );
		$to_save = array();

		foreach ( $sby_settings['connected_accounts'] as $connected_account ) {
			if ( (string)$connected_account['channel_id'] !== (string)$account_id ) {
				$to_save[ $connected_account['channel_id'] ] = $connected_account;
			}
		}

		$sby_settings['connected_accounts'] = $to_save;
		update_option( 'sby_settings', $sby_settings );

		echo wp_json_encode( array( 'success' => true ) );

		die();
	}


/** Function handle_ajax() called by wp_ajax hooks: {'sb_feature_suggestion', 'sb_deactivation_feedback'} **/
/** Parameters found in function handle_ajax(): {"post": ["plugin_slug", "reason_id", "details"]} **/
function handle_ajax()
    {
        check_ajax_referer('sb_deactivation_feedback', 'nonce');
        if (!current_user_can('activate_plugins')) {
            wp_send_json_error('Unauthorized', 403);
        }
        $slug = isset($_POST['plugin_slug']) ? sanitize_key($_POST['plugin_slug']) : '';
        $reason_id = isset($_POST['reason_id']) ? sanitize_key($_POST['reason_id']) : '';
        $details = isset($_POST['details']) ? sanitize_textarea_field($_POST['details']) : '';
        $config = FeedbackManager::get_config($slug);
        if (!$config) {
            wp_send_json_error('Unknown plugin', 400);
        }
        // Data for the sb-feedback-api REST endpoint.
        // Field mapping: reason_id -> reason, details -> comment.
        $api_data = ['plugin_slug' => $slug, 'plugin_name' => $config['plugin_name'], 'plugin_version' => $config['plugin_version'], 'reason' => $reason_id, 'comment' => $details, 'wp_version' => get_bloginfo('version'), 'php_version' => phpversion(), 'site_url' => home_url()];
        // Determine API endpoint: use config override, or auto-detect from slug.
        $endpoint = !empty($config['api_endpoint']) ? $config['api_endpoint'] : ApiClient::get_endpoint_for_slug($slug);
        // Send to API.
        ApiClient::send($endpoint, $api_data);
        // Extended data for the action hook (includes additional metadata).
        $hook_data = array_merge($api_data, ['locale' => get_locale(), 'multisite' => is_multisite() ? 'yes' : 'no', 'timestamp' => current_time('mysql', \true)]);
        /**
         * Fires after deactivation feedback is collected.
         *
         * @param array  $data   Feedback data.
         * @param array  $config Plugin configuration.
         */
        do_action('sb_feedback_deactivation_submitted', $hook_data, $config);
        // Always succeed — deactivation should never be blocked.
        wp_send_json_success();
    }


/** Function ajax_activate_addon() called by wp_ajax hooks: {'sby_activate_addon'} **/
/** Parameters found in function ajax_activate_addon(): {"post": ["plugin", "type"]} **/
function ajax_activate_addon() {

		Util::ajaxPreflightChecks();

		// Check for permissions.
		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error();
		}

		if ( isset( $_POST['plugin'] ) ) {

			$type = 'addon';
			if ( ! empty( $_POST['type'] ) ) {
				$type = sanitize_key( $_POST['type'] );
			}

			$activate = activate_plugins( preg_replace( '/[^a-z-_\/]/', '', wp_unslash( str_replace( '.php', '', $_POST['plugin'] ) ) ) . '.php' );

			if ( ! is_wp_error( $activate ) ) {
				if ( 'plugin' === $type ) {
					wp_send_json_success( esc_html__( 'Plugin activated.', 'feeds-for-youtube' ) );
				} else {
					wp_send_json_success( esc_html__( 'Addon activated.', 'feeds-for-youtube' ) );
				}
			}
		}

		wp_send_json_error( esc_html__( 'Could not activate addon. Please activate from the Plugins page.', 'feeds-for-youtube' ) );
	}


/** Function get_page() called by wp_ajax hooks: {'sbi_source_get_page'} **/
/** Parameters found in function get_page(): {"post": ["page"]} **/
function get_page() {
		if ( ! check_ajax_referer( 'sbi_admin_nonce' , 'nonce', false ) && ! check_ajax_referer( 'sby-admin' , 'nonce', false ) ) {
			wp_send_json_error();
		}
		if ( ! sby_current_user_can( 'manage_instagram_feed_options' ) ) {
			wp_send_json_error();
		}

		$args        = array( 'page' => $_POST['page'] );
		$source_data = SBI_Db::source_query( $args );

		echo json_encode( $source_data );

		wp_die();
	}


/** Function sby_dismiss_api_key_notice() called by wp_ajax hooks: {'sby_dismiss_api_key_notice'} **/
/** No params detected :-/ **/


/** Function sby_get_next_post_set() called by wp_ajax hooks: {'sby_load_more_clicked', 'nopriv_sby_load_more_clicked'} **/
/** Parameters found in function sby_get_next_post_set(): {"post": ["feed_id", "atts", "offset", "location", "post_id"]} **/
function sby_get_next_post_set() {
		// Check nonce for security
		check_ajax_referer( 'sby_nonce', 'nonce' );

		// The old check was strpos( $_POST['feed_id'], 'sby' ) === false, a substring test that is
		// never a security control — the hash_equals() gate below is the real one, so this only
		// needs to reject an empty value before the name is derived. (SMASH-1799)
		$feed_id = isset( $_POST['feed_id'] ) ? sanitize_text_field( wp_unslash( $_POST['feed_id'] ) ) : '';

		if ( '' === $feed_id ) {
			wp_send_json_error( '', 400 );
		}

		$atts_raw = isset( $_POST['atts'] ) ? json_decode( stripslashes( $_POST['atts'] ), true ) : array();
		if ( is_array( $atts_raw ) ) {
			$atts = array_map( 'sanitize_text_field', $atts_raw );
		} else {
			$atts = array();
		}

		$offset = isset( $_POST['offset'] ) ? (int)$_POST['offset'] : 0;

		$database_settings = sby_get_database_settings();
		$youtube_feed_settings = sby_is_pro() ? new SBY_Settings_Pro( $atts, $database_settings ) : new SBY_Settings( $atts, $database_settings );

		if ( empty( $database_settings['connected_accounts'] ) && empty( $database_settings['api_key'] ) ) {
			die( 'error no connected account' );
		}

		$youtube_feed_settings->set_feed_type_and_terms();
		$youtube_feed_settings->set_transient_name();
		$transient_name = $youtube_feed_settings->get_transient_name();

		// Same defect as the live-retrieve handler: the derived name was overwritten with the
		// caller's (SMASH-1799). Keep the derived name and require the caller to match it.
		// The gate sits HERE, immediately after the name is derived, rather than further down
		// where it was first added: sby_do_background_tasks() below writes a feed-locator row
		// and can trigger delete_old_locations(), and on an endpoint registered nopriv that
		// state change must not happen for a caller who is about to be handed a 403.
		if ( ! hash_equals( $transient_name, (string) $feed_id ) ) {
			wp_send_json_error( '', 403 );
		}

		$location = isset( $_POST['location'] ) && in_array( $_POST['location'], array( 'header', 'footer', 'sidebar', 'content' ), true ) ? sanitize_text_field( $_POST['location'] ) : 'unknown';
		$post_id = isset( $_POST['post_id'] ) && $_POST['post_id'] !== 'unknown' ? (int)$_POST['post_id'] : 'unknown';
		$feed_details = array(
			'feed_id' => $feed_id,
			'atts' => $atts,
			'location' => array(
				'post_id' => $post_id,
				'html' => $location
			)
		);

		$this->sby_do_background_tasks( $feed_details );

		$settings = $youtube_feed_settings->get_settings();

		$feed_type_and_terms = $youtube_feed_settings->get_feed_type_and_terms();

		$youtube_feed = sby_is_pro() ?  new SBY_Feed_Pro( $transient_name ) : new SBY_Feed( $transient_name );

		if ( $settings['caching_type'] === 'permanent' && empty( $settings['doingModerationMode'] ) ) {
			$youtube_feed->add_report( 'trying to use permanent cache' );
			$youtube_feed->maybe_set_post_data_from_backup();
		} elseif ( $settings['caching_type'] === 'background' ) {
			$youtube_feed->add_report( 'background caching used' );
			if ( $youtube_feed->regular_cache_exists() ) {
				$youtube_feed->add_report( 'setting posts from cache' );
				$youtube_feed->set_post_data_from_cache();
			}

			if ( $youtube_feed->need_posts( $settings['num'], $offset ) && $youtube_feed->can_get_more_posts() ) {
				while ( $youtube_feed->need_posts( $settings['num'], $offset ) && $youtube_feed->can_get_more_posts() ) {
					$youtube_feed->add_remote_posts( $settings, $feed_type_and_terms, $youtube_feed_settings->get_connected_accounts_in_feed() );
				}

				if ( $youtube_feed->need_to_start_cron_job() ) {
					$youtube_feed->add_report( 'needed to start cron job' );
					$to_cache = array(
						'atts' => $atts,
						'last_requested' => time(),
					);

					$youtube_feed->set_cron_cache( $to_cache, $youtube_feed_settings->get_cache_time_in_seconds() );

				} else {
					$youtube_feed->add_report( 'updating last requested and adding to cache' );
					$to_cache = array(
						'last_requested' => time(),
					);

					$youtube_feed->set_cron_cache( $to_cache, $youtube_feed_settings->get_cache_time_in_seconds(), $settings['backup_cache_enabled'] );
				}
			}

		} elseif ( $youtube_feed->regular_cache_exists() ) {
			$youtube_feed->add_report( 'regular cache exists' );
			$youtube_feed->set_post_data_from_cache();

			if ( $youtube_feed->need_posts( $settings['num'], $offset ) && $youtube_feed->can_get_more_posts() ) {
				while ( $youtube_feed->need_posts( $settings['num'], $offset ) && $youtube_feed->can_get_more_posts() ) {
					$youtube_feed->add_remote_posts( $settings, $feed_type_and_terms, $youtube_feed_settings->get_connected_accounts_in_feed() );
				}

				$youtube_feed->add_report( 'adding to cache' );
				$youtube_feed->cache_feed_data( $youtube_feed_settings->get_cache_time_in_seconds(), $settings['backup_cache_enabled'] );
			}


		} else {
			$youtube_feed->add_report( 'no feed cache found' );

			while ( $youtube_feed->need_posts( $settings['num'], $offset ) && $youtube_feed->can_get_more_posts() ) {
				$youtube_feed->add_remote_posts( $settings, $feed_type_and_terms, $youtube_feed_settings->get_connected_accounts_in_feed() );
			}

			if ( $youtube_feed->should_use_backup() ) {
				$youtube_feed->add_report( 'trying to use a backup cache' );
				$youtube_feed->maybe_set_post_data_from_backup();
			} else {
				$youtube_feed->add_report( 'transient gone, adding to cache' );
				$youtube_feed->cache_feed_data( $youtube_feed_settings->get_cache_time_in_seconds(), $settings['backup_cache_enabled'] );
			}
		}

		$settings['feed_avatars'] = array();
		if ( $youtube_feed->need_avatars( $settings ) ) {
			$youtube_feed->set_up_feed_avatars( $youtube_feed_settings->get_connected_accounts_in_feed(), $feed_type_and_terms );
			$settings['feed_avatars'] = $youtube_feed->get_channel_id_avatars();
		}

		$feed_status = array( 'shouldPaginate' => $youtube_feed->should_use_pagination( $settings, $offset ) );

		$feed_status['cacheAll'] = $youtube_feed->do_page_cache_all();

		$return_html = $youtube_feed->get_the_items_html( $settings, $offset, $youtube_feed_settings->get_feed_type_and_terms(), $youtube_feed_settings->get_connected_accounts_in_feed() );

		$post_data = $youtube_feed->get_post_data();
		if ( ($youtube_feed->are_posts_with_no_details() || $youtube_feed->successful_video_api_request_made())
		     && ! empty( $post_data ) ) {
			if ( $settings['storage_process'] === 'page' ) {
				foreach ( $post_data as $post ) {
					$wp_post            = new SBY_WP_Post( $post, $transient_name );
					$sby_video_settings = SBY_CPT::get_sby_cpt_settings();
					$wp_post->update_post( $sby_video_settings['post_status'] );
				}
			} elseif ( $settings['storage_process'] === 'background' ) {
				$feed_status['checkWPPosts'] = true;
				$feed_status['cacheAll']     = true;
			}
		}

		/*if ( $settings['disable_js_image_loading'] || $settings['imageres'] !== 'auto' ) {
			global $sby_posts_manager;
			$post_data = array_slice( $youtube_feed->get_post_data(), $offset, $settings['minnum'] );

			if ( ! $sby_posts_manager->image_resizing_disabled() ) {
				$image_ids = array();
				foreach ( $post_data as $post ) {
					$image_ids[] = SBY_Parse::get_post_id( $post );
				}
				$resized_images = SBY_Feed::get_resized_images_source_set( $image_ids, 0, $feed_id );

				$youtube_feed->set_resized_images( $resized_images );
			}
		}*/

		$return = array(
			'html' => $return_html,
			'feedStatus' => $feed_status,
			'report' => $youtube_feed->get_report(),
			'resizedImages' => array()
			//'resizedImages' => SBY_Feed::get_resized_images_source_set( $youtube_feed->get_image_ids_post_set(), 0, $feed_id )
		);

		//SBY_Feed::update_last_requested( $youtube_feed->get_image_ids_post_set() );

		echo wp_json_encode( $return );

		global $sby_posts_manager;

		$sby_posts_manager->update_successful_ajax_test();

		die();
	}


/** Function SmashBalloon\YouTubeFeed\Builder\SBY_Feed_Saver_Manager() called by wp_ajax hooks: {'sby_feed_saver_manager_fly_preview', 'sby_feed_refresh', 'sby_feed_saver_manager_get_feed_list_page', 'sby_feed_saver_clear_comments_cache', 'sby_feed_saver_manager_clear_single_feed_cache', 'sby_feed_handle_saver_manager_fly_preview', 'sby_feed_saver_manager_builder_update', 'sby_feed_saver_manager_delete_feeds', 'sby_dismiss_onboarding', 'sby_feed_saver_manager_duplicate_feed'} **/
/** No function found :-/ **/


/** Function get_featured_post_preview() called by wp_ajax hooks: {'sbi_source_get_featured_post_preview'} **/
/** No function found :-/ **/


/** Function builder_update_multiple() called by wp_ajax hooks: {'sbi_source_builder_update_multiple'} **/
/** Parameters found in function builder_update_multiple(): {"post": ["sourcesList"]} **/
function builder_update_multiple() {
		if ( ! check_ajax_referer( 'sbi_admin_nonce' , 'nonce', false ) && ! check_ajax_referer( 'sby-admin' , 'nonce', false ) ) {
			wp_send_json_error();
		}
		if ( ! sby_current_user_can( 'manage_instagram_feed_options' ) ) {
			wp_send_json_error();
		}

		if ( isset( $_POST['sourcesList'] ) && ! empty( $_POST['sourcesList'] ) && is_array( $_POST['sourcesList'] ) ) {
			foreach ( $_POST['sourcesList'] as $single_source ) :
				$source_data = array(
					'access_token' => sanitize_text_field( $single_source['access_token'] ),
					'id'           => sanitize_text_field( $single_source['id'] ),
					'type'         => sanitize_text_field( $single_source['type'] ),
					'username'     => isset( $single_source['username'] ) ? sanitize_text_field( $single_source['username'] ) : '',
				);

				if ( $single_source['type'] === 'business' ) {
					$source_data['privilege'] = 'tagged';
				}

				if ( ! empty( $single_source['name'] ) ) {
					$source_data['name'] = sanitize_text_field( $single_source['name'] );
				}

				self::process_connecting_source_data( $source_data );
			endforeach;
		}
		echo json_encode( SBI_Feed_Builder::get_source_list() );
		wp_die();
	}


/** Function review_notice_consent() called by wp_ajax hooks: {'sby_review_notice_consent_update'} **/
/** Parameters found in function review_notice_consent(): {"post": ["consent"]} **/
function review_notice_consent() {
		//Security Checks
		check_ajax_referer( 'sby-admin', 'sby_nonce' );
		$cap = current_user_can( 'manage_youtube_feed_options' ) ? 'manage_youtube_feed_options' : 'manage_options';

		$cap = apply_filters( 'sby_settings_pages_capability', $cap );
		if ( ! current_user_can( $cap ) ) {
			wp_send_json_error(); // This auto-dies.
		}

		$consent = isset( $_POST[ 'consent' ] ) ? sanitize_text_field( $_POST[ 'consent' ] ) : '';

		update_option( 'sby_review_consent', $consent );

		if ( $consent == 'no' ) {
			$sby_statuses_option = get_option( 'sby_statuses', array() );
			update_option( 'sby_rating_notice', 'dismissed', false );
			$sby_statuses_option['rating_notice_dismissed'] = sby_get_current_time();
			update_option( 'sby_statuses', $sby_statuses_option, false );
		}
		wp_die();
	}


/** Function sby_dismiss_connect_warning_notice() called by wp_ajax hooks: {'sby_dismiss_connect_warning_button'} **/
/** No params detected :-/ **/


/** Function recheck_license_upgrade() called by wp_ajax hooks: {'sby_recheck_license_upgrade'} **/
/** No params detected :-/ **/


/** Function preview() called by wp_ajax hooks: {'sb_youtubefeed_divi_preview'} **/
/** No params detected :-/ **/


/** Function on_feed_saved() called by wp_ajax hooks: {'sby_feed_saver_manager_builder_update'} **/
/** Parameters found in function on_feed_saved(): {"post": ["new_insert"]} **/
function on_feed_saved() {
		if ( ! $this->verify_admin_ajax_request() ) {
			return;
		}
		// Nonce verified above (non-dying), so reading the request field is safe.
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$is_new_feed = ! empty( $_POST['new_insert'] );
		EventRecorder::record( $is_new_feed ? 'feed_created' : 'feed_saved' );
	}


/** Function sby_recheck_connection() called by wp_ajax hooks: {'sby_recheck_connection'} **/
/** Parameters found in function sby_recheck_connection(): {"post": ["license_key"]} **/
function sby_recheck_connection() {
		// Security: Verify nonce and capability
		check_ajax_referer( 'sby-admin', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'Permission denied.' ) );
		}

		delete_option("sby_islicence_upgraded");
		delete_option("sby_upgraded_info");

		// Do the form validation
		$license_key = isset( $_POST['license_key'] ) ? sanitize_text_field( $_POST['license_key'] ) : '';
		if ( empty( $license_key ) ) {
			wp_send_json_error();
		}

		// make the remote license check API call
		$sby_license_data = Util::sby_check_license( $license_key );

		// update options data
		$license_changed = Util::update_recheck_license_data( $sby_license_data );

		if (
			isset($sby_license_data->success, $sby_license_data->license)
			&& $sby_license_data->success === true
			&& $sby_license_data->license === 'valid'
		) {
			SBY_Upgrader::check_license_upgraded($sby_license_data, $license_key);
		}

		// send AJAX response back
		wp_send_json_success(
			array(
				'license'        => $sby_license_data->license,
				'licenseChanged' => $license_changed,
				'isLicenseUpgraded'   => get_option('sby_islicence_upgraded'),
				'licenseUpgradedInfo' => get_option('sby_upgraded_info')
			)
		);
	}


/** Function ajax_clear_cache() called by wp_ajax hooks: {'sby_clear_cache'} **/
/** No params detected :-/ **/


/** Function Smashballoon\Customizer\Feed_Saver_Manager() called by wp_ajax hooks: {'sbc_feed_saver_manager_fly_preview'} **/
/** No function found :-/ **/


/** Function SmashBalloon\YouTubeFeed\Admin\SBY_Upgrader() called by wp_ajax hooks: {'nopriv_sby_run_one_click_upgrade', 'sby_maybe_upgrade_redirect'} **/
/** No function found :-/ **/


/** Function hide_frontend_license_error() called by wp_ajax hooks: {'sby_hide_frontend_license_error'} **/
/** No params detected :-/ **/


/** Function sby_get_live_retrieve() called by wp_ajax hooks: {'nopriv_sby_live_retrieve', 'sby_live_retrieve'} **/
/** Parameters found in function sby_get_live_retrieve(): {"post": ["feed_id", "video_id", "atts"]} **/
function sby_get_live_retrieve() {
		// Check nonce for security
		check_ajax_referer( 'sby_nonce', 'nonce' );

		// The substring test below was never a security control; the real gate is that the
		// transient name must be DERIVED from the resolved settings and match what the
		// caller named (SMASH-1799). video_id is tightened to a single real YouTube id so
		// one request cannot stage a whole replacement post set through the comma split in
		// SBY_Settings_Pro::set_feed_type_and_terms().
		$feed_id  = isset( $_POST['feed_id'] ) ? sanitize_text_field( wp_unslash( $_POST['feed_id'] ) ) : '';
		$video_id = isset( $_POST['video_id'] ) ? sanitize_text_field( wp_unslash( $_POST['video_id'] ) ) : '';

		if ( '' === $feed_id || ! preg_match( '/^[A-Za-z0-9_-]{11}$/', $video_id ) ) {
			wp_send_json_error( '', 400 );
		}

		$atts_raw = isset( $_POST['atts'] ) ? json_decode( stripslashes( $_POST['atts'] ), true ) : array();
		if ( is_array( $atts_raw ) ) {
			$atts = array_map( 'sanitize_text_field', $atts_raw );
		} else {
			$atts = array();
		}

		$offset = 0;

		$database_settings = sby_get_database_settings();

		if ( empty( $database_settings['connected_accounts'] ) && empty( $database_settings['api_key'] ) ) {
			die( 'error no connected account' );
		}

		// The gate has to be derived from the atts EXACTLY as the front end had them, before
		// the type=single override below. data-feedid is published from the live feed's own
		// settings (templates/feed.php), so deriving after the override would compare a
		// single-video name (sby_S!<id>#<num>) against a live name and could never match —
		// which would 403 every legitimate request rather than only the forged ones. Derive
		// and compare first, then apply the override on a separate settings object used only
		// for the fetch, keeping the write target the name the caller has now proven.
		// (SMASH-1799)
		$check_settings = sby_is_pro() ? new SBY_Settings_Pro( $atts, $database_settings ) : new SBY_Settings( $atts, $database_settings );
		$check_settings->set_feed_type_and_terms();
		// Was set_transient_name( $feed_id ), which stored the caller's value verbatim and
		// made the comparison below tautological — it compared the value against the copy of
		// itself just stored, so it could never fail and the caller chose which feed cache to
		// overwrite. Deriving the name from the resolved feed type and terms is what makes the
		// check real: any atts value that redirects the fetch changes the derived name and
		// fails here, so the write can only land on the cache the resolved settings own.
		$check_settings->set_transient_name();
		$transient_name = $check_settings->get_transient_name();

		if ( ! hash_equals( $transient_name, $feed_id ) ) {
			wp_send_json_error( '', 403 );
		}

		// Only now redirect the fetch at the single video the caller asked to reveal. The
		// write still lands on $transient_name, i.e. the live feed's own cache.
		if ( isset( $atts['live'] ) ) {
			unset( $atts['live'] );
		}
		$atts['type'] = 'single';
		$atts['single'] = $video_id;

		$youtube_feed_settings = sby_is_pro() ? new SBY_Settings_Pro( $atts, $database_settings ) : new SBY_Settings( $atts, $database_settings );
		$youtube_feed_settings->set_feed_type_and_terms();
		$youtube_feed_settings->set_transient_name( $transient_name );

		$settings = $youtube_feed_settings->get_settings();

		$feed_type_and_terms = $youtube_feed_settings->get_feed_type_and_terms();

		$youtube_feed = sby_is_pro() ?  new SBY_Feed_Pro( $transient_name ) : new SBY_Feed( $transient_name );
		$youtube_feed->add_remote_posts( $settings, $feed_type_and_terms, $youtube_feed_settings->get_connected_accounts_in_feed() );
		if ( $database_settings['caching_type'] === 'background' ) {
			$to_cache = array(
				'atts' => $atts,
				'last_requested' => time(),
			);
			$youtube_feed->set_cron_cache( $to_cache, $youtube_feed_settings->get_cache_time_in_seconds() );
		} else {
			$youtube_feed->cache_feed_data( $youtube_feed_settings->get_cache_time_in_seconds(), $settings['backup_cache_enabled'] );
		}

		$feed_status = array( 'shouldPaginate' => $youtube_feed->should_use_pagination( $settings, $offset ) );

		$feed_status['cacheAll'] = $youtube_feed->do_page_cache_all();

		$return_html = $youtube_feed->get_the_items_html( $settings, $offset, $youtube_feed_settings->get_feed_type_and_terms(), $youtube_feed_settings->get_connected_accounts_in_feed() );
		$post_data = $youtube_feed->get_post_data();
		if ( ($youtube_feed->are_posts_with_no_details() || $youtube_feed->successful_video_api_request_made())
		     && ! empty( $post_data ) ) {
			if ( $settings['storage_process'] === 'page' ) {
				foreach ( $youtube_feed->get_post_data() as $post ) {
					$wp_post            = new SBY_WP_Post( $post, $transient_name );
					$sby_video_settings = SBY_CPT::get_sby_cpt_settings();
					$wp_post->update_post( $sby_video_settings['post_status'] );
				}
			} elseif ( $settings['storage_process'] === 'background' ) {
				$feed_status['checkWPPosts'] = true;
				$feed_status['cacheAll']     = true;
			}
		}

		$return = array(
			'html' => $return_html,
			'feedStatus' => $feed_status,
			'report' => $youtube_feed->get_report(),
			'resizedImages' => array()
			//'resizedImages' => SBY_Feed::get_resized_images_source_set( $youtube_feed->get_image_ids_post_set(), 0, $feed_id )
		);

		//SBY_Feed::update_last_requested( $youtube_feed->get_image_ids_post_set() );

		echo wp_json_encode( $return );

		global $sby_posts_manager;

		$sby_posts_manager->update_successful_ajax_test();

		die();
	}


/** Function ajax_all_video_action() called by wp_ajax hooks: {'sby_all_videos_action'} **/
/** Parameters found in function ajax_all_video_action(): {"post": ["channel", "perform"]} **/
function ajax_all_video_action() {
		Util::ajaxPreflightChecks();
		$meta_query = [
			'relation' => 'OR'
		];
		$exploded_channels = explode(',', $_POST['channel']);
		if ( is_array( $exploded_channels ) ) {
			foreach ( $exploded_channels as $channel_id ) {
				$meta_query[] = [
					'key'     => 'sby_channel_title',
					'value'   => esc_sql( $channel_id ),
					'compare' => '=',
				];
			}
		} else {
			$meta_query[] = [
				'key'     => 'sby_channel_title',
				'value'   => esc_sql( $exploded_channels ),
				'compare' => '=',
			];
		}

		$args = array(
			'post_type' => SBY_CPT,
			'meta_key' => 'sby_channel_title',
			'posts_per_page' => -1,
			'meta_query' => $meta_query
		);

		$query = new \WP_Query($args);

		if($_POST['perform'] === 'publish') {
			foreach ( $query->get_posts() as $post ) {
				$postData = [ 'ID' => $post->ID, 'post_status' => 'publish' ];
				wp_update_post( $postData );
			}
		}

		if($_POST['perform'] === 'delete') {
			foreach ( $query->get_posts() as $post ) {
				wp_delete_post( $post->ID, true );
			}
		}

		wp_send_json_success();
	}


/** Function manual_access_token() called by wp_ajax hooks: {'sby_manual_access_token'} **/
/** Parameters found in function manual_access_token(): {"post": ["sby_access_token"]} **/
function manual_access_token() {
		check_ajax_referer( 'sby-admin' , 'nonce');

		if ( ! sby_current_user_can( 'manage_youtube_feed_options' ) ) {
			wp_send_json_error();
		}

		if ( isset( $_POST['sby_access_token'] ) ) {
			$return = sby_attempt_connection();
			wp_send_json_success($return);
		}
	}


/** Function SmashBalloon\YouTubeFeed\Builder\SBI_Feed_Builder() called by wp_ajax hooks: {'sbi_dismiss_onboarding'} **/
/** No function found :-/ **/


/** Function sby_delete_wp_posts() called by wp_ajax hooks: {'sby_delete_wp_posts'} **/
/** Parameters found in function sby_delete_wp_posts(): {"post": ["sbspf_nonce"]} **/
function sby_delete_wp_posts() {
		if ( ! isset( $_POST['sbspf_nonce'] ) ) return;
		$nonce = $_POST['sbspf_nonce'];
		if ( ! wp_verify_nonce( $nonce, 'sbspf_nonce' ) ) {
			die ( 'You did not do this the right way!' );
		}

		sby_clear_wp_posts();

		sby_clear_cache();

		echo '{}';

		die();
	}


/** Function sby_dismiss_at_warning_notice() called by wp_ajax hooks: {'sby_dismiss_at_warning_notice'} **/
/** No params detected :-/ **/


/** Function test_connection() called by wp_ajax hooks: {'sby_check_connection'} **/
/** No params detected :-/ **/


/** Function sbspf_account_search() called by wp_ajax hooks: {'sbspf_account_search'} **/
/** Parameters found in function sbspf_account_search(): {"post": ["sbspf_nonce", "term"]} **/
function sbspf_account_search() {
		if ( ! isset( $_POST['sbspf_nonce'] ) || ! isset( $_POST['term']) ) return;
		$nonce = $_POST['sbspf_nonce'];
		if ( ! wp_verify_nonce( $nonce, 'sbspf_nonce' ) ) {
			die ( 'You did not do this the right way!' );
		}

		global $sby_settings;

		$term = sanitize_text_field( $_POST['term'] );
		$params = array(
			'q' => $term,
			'type' => 'channel'
		);

		$connected_account_for_term = array();
		foreach ( $sby_settings['connected_accounts'] as $connected_account ) {
			$connected_account_for_term = $connected_account;
		}
		if ( $connected_account_for_term['expires'] < time() + 5 ) {
			$new_token_data = SBY_API_Connect::refresh_token( '', $connected_account_for_term['refresh_token'], '' );

			if ( isset( $new_token_data['access_token'] ) ) {
				$connected_account_for_term['access_token'] = $new_token_data['access_token'];
				$connected_accounts_for_feed[ $term ]['access_token'] = $new_token_data['access_token'];
				$connected_account_for_term['expires'] = $new_token_data['expires_in'] + time();
				$connected_accounts_for_feed[ $term ]['expires'] = $new_token_data['expires_in'] + time();

				sby_update_or_connect_account( $connected_account_for_term );

			}
		}

		$search = new SBY_API_Connect( $connected_account_for_term, 'search', $params );

		$search->connect();


		echo wp_json_encode( $search->get_data() );

		die();
	}


/** Function ajax_query_single_videos() called by wp_ajax hooks: {'sby_get_single_videos'} **/
/** Parameters found in function ajax_query_single_videos(): {"post": ["page"]} **/
function ajax_query_single_videos() {
		Util::ajaxPreflightChecks();
		$page = esc_sql($_POST['page']);

		wp_send_json_success(['total_result' => $this->single_videos_total(), 'results' => $this->query_single_videos($page)]);
	}


/** Function builder_update() called by wp_ajax hooks: {'sbi_source_builder_update'} **/
/** No params detected :-/ **/


/** Function dismiss_upgrade_notice() called by wp_ajax hooks: {'sby_dismiss_upgrade_notice'} **/
/** No params detected :-/ **/


/** Function sby_single_videos_upsell_modal() called by wp_ajax hooks: {'sby_single_videos_upsell_modal'} **/
/** No params detected :-/ **/


/** Function dismiss_license_notice() called by wp_ajax hooks: {'sby_dismiss_license_notice'} **/
/** No params detected :-/ **/


/** Function ajax_install_addon() called by wp_ajax hooks: {'sby_install_addon'} **/
/** Parameters found in function ajax_install_addon(): {"post": ["plugin", "type"]} **/
function ajax_install_addon() {

		// Run a security check.
		Util::ajaxPreflightChecks();

		// Check for permissions.
		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_send_json_error();
		}

		$error = esc_html__( 'Could not install addon. Please download from wpforms.com and install manually.', 'feeds-for-youtube' );

		if ( empty( $_POST['plugin'] ) ) {
			wp_send_json_error( $error );
		}

		// Only install plugins from the .org repo
		if ( strpos( $_POST['plugin'], 'https://downloads.wordpress.org/plugin/' ) !== 0 ) {
			wp_send_json_error( $error );
		}

		// Set the current screen to avoid undefined notices.
		set_current_screen( 'youtube-feed-about' );

		// Prepare variables.
		$url = esc_url_raw(
			add_query_arg(
				array(
					'page' => 'youtube-feed-about',
				),
				admin_url( 'admin.php' )
			)
		);

		$creds = request_filesystem_credentials( $url, '', false, '', null );

		// Check for file system permissions.
		if ( false === $creds ) {
			wp_send_json_error( $error );
		}

		if ( ! WP_Filesystem( $creds ) ) {
			wp_send_json_error( $error );
		}

		/*
		 * We do not need any extra credentials if we have gotten this far, so let's install the plugin.
		 */

		// Do not allow WordPress to search/download translations, as this will break JS output.
		remove_action( 'upgrader_process_complete', array( 'Language_Pack_Upgrader', 'async_upgrade' ), 20 );

		if(!class_exists("Plugin_Upgrader")) {
			include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			include_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';
			include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader-skin.php';
		}

		// Create the plugin upgrader with our custom skin.
		$installer = new \Plugin_Upgrader( new \WP_Upgrader_Skin() );

		// Error check.
		if ( empty( $_POST['plugin'] ) ) {
			wp_send_json_error( $error );
		}

		$installer->install( esc_url_raw( wp_unslash( $_POST['plugin'] ) ) );

		// Flush the cache and return the newly installed plugin basename.
		wp_cache_flush();

		$plugin_basename = $installer->plugin_info();

		if ( $plugin_basename ) {

			$type = 'addon';
			if ( ! empty( $_POST['type'] ) ) {
				$type = sanitize_key( $_POST['type'] );
			}

			// Activate the plugin silently.
			$activated = activate_plugin( $plugin_basename );

			if ( ! is_wp_error( $activated ) ) {
				wp_send_json_success(
					array(
						'msg'          => 'plugin' === $type ? esc_html__( 'Plugin installed & activated.', 'feeds-for-youtube' ) : esc_html__( 'Addon installed & activated.', 'feeds-for-youtube' ),
						'is_activated' => true,
						'basename'     => $plugin_basename,
					)
				);
			} else {
				wp_send_json_success(
					array(
						'msg'          => 'plugin' === $type ? esc_html__( 'Plugin installed.', 'feeds-for-youtube' ) : esc_html__( 'Addon installed.', 'feeds-for-youtube' ),
						'is_activated' => false,
						'basename'     => $plugin_basename,
					)
				);
			}
		}

		wp_send_json_error( $error );
	}


/** Function ajax_dismiss_wizard() called by wp_ajax hooks: {'sby_dismiss_wizard'} **/
/** No params detected :-/ **/


/** Function ajax_handle_file_import() called by wp_ajax hooks: {'sby_do_feed_import'} **/
/** Parameters found in function ajax_handle_file_import(): {"files": ["feedFile"]} **/
function ajax_handle_file_import() {
		Util::ajaxPreflightChecks();

		$filename = $_FILES['feedFile']['name'];
		$ext      = pathinfo( $filename, PATHINFO_EXTENSION );

		if ( 'json' !== $ext ) {
			wp_send_json_error(['message' => __('Unsupported file type.', 'feeds-for-youtube'), 'success' => false]);
		}

		$imported_settings = file_get_contents( $_FILES['feedFile']['tmp_name'] );

		if ( empty($imported_settings) ) {
			wp_send_json_error(['message' => __('Could not parse file contents.', 'feeds-for-youtube'), 'success' => false]);
		}

		$decoded_settings = json_decode($imported_settings, true);

		$result = $this->saver_manager->import_feed($imported_settings, $decoded_settings['feedName']);

		wp_send_json_success($result);
	}


/** Function ajax_deactivate_license() called by wp_ajax hooks: {'sby_license_deactivation'} **/
/** No params detected :-/ **/


/** Function sby_process_access_token() called by wp_ajax hooks: {'sby_process_access_token'} **/
/** Parameters found in function sby_process_access_token(): {"post": ["sbspf_nonce", "sby_access_token"]} **/
function sby_process_access_token() {
		if ( ! isset( $_POST['sbspf_nonce'] ) || ! isset( $_POST['sby_access_token'] ) ) return;
		$nonce = $_POST['sbspf_nonce'];
		if ( ! wp_verify_nonce( $nonce, 'sbspf_nonce' ) ) {
			die ( 'You did not do this the right way!' );
		}

		$account = sby_attempt_connection();

		if ( $account ) {
			global $sby_settings;

			$options = $sby_settings;
			$username = $account['username'] ? $account['username'] : $account['channel_id'];
			if ( isset( $account['local_avatar'] ) && $account['local_avatar'] && isset( $options['favorlocal'] ) && $options['favorlocal' ] === 'on' ) {
				$upload = wp_upload_dir();
				$resized_url = trailingslashit( $upload['baseurl'] ) . trailingslashit( SBY_UPLOADS_NAME );
				$profile_picture = '<img class="sbspf_ca_avatar" alt="'.esc_attr( $username ).' channel avatar" src="'.$resized_url . $account['username'].'.jpg" />'; //Could add placeholder avatar image
			} else {
				$profile_picture = $account['profile_picture'] ? '<img class="sbspf_ca_avatar" alt="'.esc_attr( $username ).' channel avatar" src="'.$account['profile_picture'].'" />' : ''; //Could add placeholder avatar image
			}

			$text_domain = SBY_TEXT_DOMAIN;
			$slug = SBY_SLUG;
			ob_start();
			include_once trailingslashit( SBY_PLUGIN_DIR ) . 'inc/Admin/templates/single-connected-account.php';
			if ( sby_notice_not_dismissed() ) {
				include_once trailingslashit( SBY_PLUGIN_DIR ) . 'inc/Admin/templates/modal.php';
				echo '<span class="sby_account_just_added"></span>';
			}

			$html = ob_get_contents();
			ob_get_clean();

			$return = array(
				'account_id' => $account['channel_id'],
				'html' => $html
			);
		} else {
			$return = array(
				'error' => __( 'Could not connect your account. Please check to make sure this is a valid access token for the Smash Balloon YouTube App.'),
				'html' => ''
			);
		}

		echo wp_json_encode( $return );

		die();
	}


/** Function ajax_activate_wpconsent() called by wp_ajax hooks: {'sby_activate_wpconsent'} **/
/** No params detected :-/ **/


/** Function sby_do_import_batch() called by wp_ajax hooks: {'sby_do_import_batch'} **/
/** Parameters found in function sby_do_import_batch(): {"post": ["page", "channel", "needed", "playlist"]} **/
function sby_do_import_batch() {

		Util::ajaxPreflightChecks();

		$next_page = isset( $_POST['page'] ) ? sanitize_text_field( $_POST['page'] ) : '';
		$channel = isset( $_POST['channel'] ) ? sanitize_text_field( $_POST['channel'] ) : '';
		$needed = isset( $_POST['needed'] ) ? sanitize_text_field( $_POST['needed'] ) : 1;
		$playlist = isset( $_POST['playlist'] ) ? sanitize_text_field( $_POST['playlist'] ) : '';

		if ( empty( $channel ) && empty( $playlist ) ) {
			die();
		}

		$params = array(
			'num' => (int) $needed
		);

		if ( ! empty( $next_page ) ) {
			$params['nextPageToken'] = $next_page;
		}

		if ( strpos( $channel, 'UC' ) === 0 ) {
			$params['channel_id'] = $channel;
		} else {
			$params['channel_name'] = $channel;
		}

		if ( empty( $playlist ) ) {
			$connection = new SBY_API_Connect_Pro( sby_get_first_connected_account(), 'channels', $params );
			$connection->connect();
			$channel_data = $connection->get_data();

			$playlist = isset( $channel_data['items'][0]['contentDetails']['relatedPlaylists']['uploads'] ) ? $channel_data['items'][0]['contentDetails']['relatedPlaylists']['uploads'] : false;
		}

		$return = array();
		if ( $playlist ) {
			$return['playlist'] = $playlist;
			$params['playlist_id'] = $playlist;
			$playlist_connection = new SBY_API_Connect_Pro( sby_get_first_connected_account(), 'playlistItems', $params );
			$playlist_connection->connect();
			$data = $playlist_connection->get_data();

			$posts = isset( $data['items'] ) ? $data['items'] : array();

			AdminAjaxService::sby_process_post_set_caching( $posts, 'sby_importer' );

			$return['num_retrieved'] = count( $posts );

			$return['next'] = $playlist_connection->get_next_page();

			echo wp_json_encode( $return );
		}

		die();
	}


/** Function ajax_process_wizard() called by wp_ajax hooks: {'sby_process_wizard'} **/
/** Parameters found in function ajax_process_wizard(): {"post": ["data"]} **/
function ajax_process_wizard(){

		Util::ajaxPreflightChecks();

		if( ! isset( $_POST['data'] ) ){
			wp_send_json_error();
		}

		// If in the future we need to support settings.
		// $sby_settings = get_option( 'sby_settings', array() );

		$onboarding_data = sanitize_text_field( stripslashes( $_POST['data'] ) );
		$onboarding_data  = json_decode( $onboarding_data, true);

		foreach (array_keys($onboarding_data) as $key) {
	
		// If in the future we need to support settings.
		// if( !empty($key) && 'settings' === $key ){
		// }

			if( $key && 'plugins' === $key && !empty($onboarding_data[$key]) && current_user_can( 'install_plugins' ) ){
				foreach ($onboarding_data[$key] as $single) {
					if ( ! empty( $single['file'] ) && file_exists( WP_PLUGIN_DIR . '/' . $single['file'] ) ) {
						activate_plugin( $single['file'] );
					} elseif(!empty($single['url']) && !empty($single['slug'])) {
						@self::install_single_plugin($single['url']);
					}

					if ( ! empty( $single['slug'] ) ) {
						$this->disable_installed_plugins_redirect($single['slug']);
					}
				}
			}

		}
		
		// If in the future we need to support settings.
		//update_option( 'sby_settings', $sby_settings );

		wp_send_json_success();
	}


/** Function install_plugin() called by wp_ajax hooks: {'am_recommended_block_install'} **/
/** Parameters found in function install_plugin(): {"request": ["nonce", "plugin"]} **/
function install_plugin()
    {
        include_once \ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        include_once \ABSPATH . 'wp-admin/includes/plugin-install.php';
        if (!current_user_can('install_plugins')) {
            $error = new WP_Error('no_permission', 'You do not have permission to install plugins.');
            wp_send_json_error($error);
        }
        if (empty($_REQUEST['nonce']) || !wp_verify_nonce(sanitize_text_field($_REQUEST['nonce']), 'am_recommended_block_install')) {
            $error = new WP_Error('nonce_failure', 'The nonce was not valid.');
            wp_send_json_error($error);
        }
        if (empty($_REQUEST['plugin'])) {
            $error = new WP_Error('missing_file', 'The plugin file was not specified.');
            wp_send_json_error($error);
        }
        $plugin_file = sanitize_text_field($_REQUEST['plugin']);
        $slug = strtok($plugin_file, '/');
        $plugin_dir = \WP_PLUGIN_DIR . '/' . $slug;
        $plugin_path = \WP_PLUGIN_DIR . '/' . $plugin_file;
        // Buffer all output during install + activate. Activate especially
        // runs the target plugin's main file (which can echo on load), and
        // PHP notices / deprecation warnings can also leak into the response
        // body and break JSON.parse on the client.
        ob_start();
        if (!is_dir($plugin_dir)) {
            $api = plugins_api('plugin_information', ['slug' => $slug, 'fields' => ['short_description' => \false, 'sections' => \false, 'requires' => \false, 'rating' => \false, 'ratings' => \false, 'downloaded' => \false, 'last_updated' => \false, 'added' => \false, 'tags' => \false, 'compatibility' => \false, 'homepage' => \false, 'donate_link' => \false]]);
            // WP_Ajax_Upgrader_Skin suppresses the streaming HTML output that
            // Plugin_Installer_Skin emits during install. Without this swap the
            // AJAX response is HTML+JSON concatenated, which breaks JSON.parse
            // in the client and surfaces as "Error. Please try again." even
            // though the install itself succeeded.
            require_once \ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php';
            $skin = new \WP_Ajax_Upgrader_Skin();
            $upgrader = new Plugin_Upgrader($skin);
            $install = $upgrader->install($api->download_link);
            if ($install !== \true) {
                ob_end_clean();
                $error = new WP_Error('failed_install', 'The plugin install failed.');
                wp_send_json_error($error);
            }
        }
        if (file_exists($plugin_path)) {
            $activated = activate_plugin($plugin_path);
            if (is_wp_error($activated)) {
                ob_end_clean();
                wp_send_json_error($activated);
            }
            $this->disable_installed_plugins_redirect();
            ob_end_clean();
            wp_send_json_success();
        } else {
            ob_end_clean();
            $error = new WP_Error('failed_activation', 'The plugin activation failed.');
            wp_send_json_error($error);
        }
    }


/** Function sby_api_key() called by wp_ajax hooks: {'sby_add_api_key'} **/
/** Parameters found in function sby_api_key(): {"post": ["api"]} **/
function sby_api_key() {
		check_ajax_referer( 'sby-admin' , 'nonce');

		if ( ! sby_current_user_can( 'manage_youtube_feed_options' ) ) {
			wp_send_json_error();
		}
		// get the settings
		$database_settings = sby_get_database_settings();
		// validate the api key
		$api_key = sanitize_text_field( $_POST['api'] );
		$database_settings['api_key'] = $api_key;
		// update the settings
		update_option( 'sby_settings', $database_settings );

		wp_send_json_success();
	}


/** Function sby_other_plugins_modal() called by wp_ajax hooks: {'sby_other_plugins_modal'} **/
/** Parameters found in function sby_other_plugins_modal(): {"post": ["plugin"]} **/
function sby_other_plugins_modal() {
		check_ajax_referer( 'sby-admin' , 'nonce');

		if ( ! sby_current_user_can( 'manage_youtube_feed_options' ) ) {
			wp_send_json_error();
		}

		$plugin = isset( $_POST['plugin'] ) ? sanitize_key( $_POST['plugin'] ) : '';
		$sb_other_plugins = sby_get_installed_plugin_info();
		$plugin = isset( $sb_other_plugins[ $plugin ] ) ? $sb_other_plugins[ $plugin ] : false;
		if ( ! $plugin ) {
			wp_send_json_error();
		}

		// Build the content for modals
		$output = '<div class="sby-fb-popup-inside sby-install-plugin-modal">
		<div class="sby-ip-popup-cls"><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M14 1.41L12.59 0L7 5.59L1.41 0L0 1.41L5.59 7L0 12.59L1.41 14L7 8.41L12.59 14L14 12.59L8.41 7L14 1.41Z" fill="#141B38"></path>
		</svg></div>
		<div class="sby-install-plugin-body sby-fb-fs">
		<div class="sby-install-plugin-header">
		<div class="sb-plugin-image">'. $plugin['svgIcon'] .'</div>
		<div class="sb-plugin-name">
		<h3>'. $plugin['name'] .'<span>Free</span></h3>
		<p><span class="sb-author-logo">
		<svg width="13" height="17" viewBox="0 0 13 17" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path fill-rule="evenodd" clip-rule="evenodd" d="M5.72226 4.70098C4.60111 4.19717 3.43332 3.44477 2.34321 3.09454C2.73052 4.01824 3.05742 5.00234 3.3957 5.97507C2.72098 6.48209 1.93286 6.8757 1.17991 7.30453C1.82065 7.93788 2.72809 8.3045 3.45109 8.85558C2.87196 9.57021 1.73414 10.3129 1.45689 10.9606C2.65579 10.8103 4.05285 10.5668 5.16832 10.5174C5.41343 11.7495 5.53984 13.1002 5.88845 14.2288C6.40758 12.7353 6.87695 11.192 7.49488 9.79727C8.44849 10.1917 9.61069 10.6726 10.5416 10.9052C9.88842 9.98881 9.29237 9.01536 8.71356 8.02465C9.57007 7.40396 10.4364 6.79309 11.2617 6.14122C10.0952 6.03375 8.88647 5.96834 7.66107 5.91968C7.46633 4.65567 7.5175 3.14579 7.21791 1.98667C6.76462 2.93671 6.2297 3.80508 5.72226 4.70098ZM6.27621 15.1705C6.12214 15.8299 6.62974 16.1004 6.55318 16.5C6.052 16.3273 5.67498 16.2386 5.00213 16.3338C5.02318 15.8194 5.48587 15.7466 5.3899 15.1151C-1.78016 14.3 -1.79456 1.34382 5.3345 0.546422C14.2483 -0.450627 14.528 14.9414 6.27621 15.1705Z" fill="#FE544F"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M7.21769 1.98657C7.51728 3.1457 7.46611 4.65557 7.66084 5.91955C8.88625 5.96824 10.0949 6.03362 11.2615 6.14113C10.4362 6.79299 9.56984 7.40386 8.71334 8.02454C9.29215 9.01527 9.8882 9.98869 10.5414 10.9051C9.61046 10.6725 8.44827 10.1916 7.49466 9.79716C6.87673 11.1919 6.40736 12.7352 5.88823 14.2287C5.53962 13.1001 5.41321 11.7494 5.16809 10.5173C4.05262 10.5667 2.65558 10.8102 1.45666 10.9605C1.73392 10.3128 2.87174 9.57012 3.45087 8.85547C2.72786 8.30438 1.82043 7.93778 1.17969 7.30443C1.93264 6.8756 2.72074 6.482 3.39547 5.97494C3.05719 5.00224 2.73031 4.01814 2.34299 3.09445C3.43308 3.44467 4.60089 4.19707 5.72204 4.70088C6.22947 3.80499 6.7644 2.93662 7.21769 1.98657Z" fill="white"></path>
		</svg>
		</span>
		<span class="sb-author-name">'. $plugin['author'] .'</span>
		</p></div></div>
		<div class="sby-install-plugin-content">
		<p>'. $plugin['description'] .'</p>';

		$plugin_install_data = array(
			'step' => 'install',
			'action' => 'sby_install_other_plugins',
			'plugin' => $plugin['plugin'],
			'download_plugin' => $plugin['download_plugin'],
		);
		if ( ! $plugin['installed'] ) {
			$output .= sprintf(
				"<button class='sby-install-plugin-btn sbc-btn-orange' id='sby_install_op_plugin' data-plugin-atts='%s'>%s</button></div></div></div>",
				wp_json_encode( $plugin_install_data ),
				__('Install', 'feeds-for-youtube')
			);
		}
		if ( $plugin['installed'] && ! $plugin['activated'] ) {
			$plugin_install_data['step'] = 'activate';
			$plugin_install_data['action'] = 'sby_activate_other_plugins';
			$output .= sprintf(
				"<button class='sby-install-plugin-btn sb-ot-installed sbc-btn-orange' id='sby_install_op_plugin' data-plugin-atts='%s'>%s</button></div></div></div>",
				wp_json_encode( $plugin_install_data ),
				__('Activate', 'feeds-for-youtube')
			);
		}
		if ( $plugin['installed'] && $plugin['activated'] ) {
			$output .= sprintf(
				"<button class='sby-install-plugin-btn sby-btn-orange' id='sby_install_op_plugin' disabled='disabled'>%s</button></div></div></div>",
				__('Plugin installed & activated', 'feeds-for-youtube')
			);
		}

		wp_send_json_success($output, true);
		wp_die();
	}


