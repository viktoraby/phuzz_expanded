<?php
/***
*
*Found actions: 52
*Found functions:32
*Extracted functions:29
*Total parameter names extracted: 19
*Overview: {'ctf_test_connection': {'ctf_test_connection'}, 'ctf_recheck_connection': {'ctf_recheck_connection'}, 'ctf_clear_cache_admin': {'ctf_clear_cache_admin'}, 'ctf_dpa_reset': {'ctf_dpa_reset'}, 'TwitterFeed\\Builder\\CTF_Feed_Builder': {'ctf_other_plugins_modal', 'ctf_dismiss_onboarding'}, 'ctf_activate_license': {'ctf_activate_license'}, 'ctf_activate_addon': {'ctf_activate_addon'}, 'install_plugin': {'am_recommended_block_install'}, 'ctf_clear_twittercard_cache': {'ctf_clear_twittercard_cache'}, 'ctf_install_addon': {'ctf_install_addon'}, 'review_notice_consent': {'ctf_review_notice_consent_update'}, 'ctf_import_settings_json': {'ctf_import_settings_json'}, 'ajax_record_session': {'ctf_smash_usage_record_session'}, 'ctf_check_license': {'ctf_check_license'}, 'ctf_do_locator': {'ctf_do_locator', 'nopriv_ctf_do_locator'}, 'ctf_clear_cache_settings': {'ctf_clear_cache_settings'}, 'ctf_deactivate_addon': {'ctf_deactivate_addon'}, 'ctf_get_more_posts': {'nopriv_ctf_get_more_posts', 'ctf_get_more_posts'}, 'TwitterFeed\\Admin\\CTF_Upgrader': {'nopriv_ctf_run_one_click_upgrade', 'ctf_maybe_upgrade_redirect'}, 'ctf_dismiss_license_notice': {'ctf_dismiss_license_notice'}, 'ctf_clear_image_resize_cache': {'ctf_clear_image_resize_cache'}, 'dismiss': {'ctf_dashboard_notification_dismiss'}, 'ctf_save_settings': {'ctf_save_settings'}, 'ctf_deactivate_license': {'ctf_deactivate_license'}, 'ctf_background_processing': {'nopriv_ctf_background_processing', 'ctf_background_processing'}, 'ajax_record_event': {'ctf_smash_usage_record_event'}, 'TwitterFeed\\Builder\\CTF_Feed_Saver_Manager': {'ctf_feed_saver_manager_get_locations_page', 'ctf_feed_saver_manager_get_feed_list_page', 'ctf_feed_saver_manager_get_feed_settings', 'ctf_feed_saver_manager_builder_update', 'ctf_feed_saver_manager_check_twitter_list_by_id', 'ctf_feed_saver_manager_fly_preview', 'ctf_feed_saver_manager_search_username_lists', 'ctf_feed_saver_manager_connect_manual_account', 'ctf_feed_saver_manager_duplicate_feed', 'ctf_feed_saver_manager_clear_single_feed_cache', 'ctf_feed_saver_manager_delete_feeds', 'ctf_feed_saver_manager_importer', 'ctf_feed_saver_manager_delete_account', 'ctf_feed_saver_manager_retrieve_comments', 'ctf_feed_saver_manager_recache_feed'}, 'ctf_export_settings_json': {'ctf_export_settings_json'}, 'ctf_clear_persistent_cache': {'ctf_clear_persistent_cache'}, 'ctf_lite_dismiss': {'ctf_lite_dismiss'}, 'dismiss_upgrade_notice': {'ctf_dismiss_upgrade_notice'}, 'handle_ajax': {'sb_deactivation_feedback', 'sb_feature_suggestion'}}
*
***/

/** Function ctf_test_connection() called by wp_ajax hooks: {'ctf_test_connection'} **/
/** No params detected :-/ **/


/** Function ctf_recheck_connection() called by wp_ajax hooks: {'ctf_recheck_connection'} **/
/** Parameters found in function ctf_recheck_connection(): {"post": ["license_key", "item_name", "option_name"]} **/
function ctf_recheck_connection() {
		check_ajax_referer( 'ctf_admin_nonce', 'nonce' );

		$cap = ctf_get_manage_options_cap();
		if ( ! current_user_can( $cap ) ) {
			wp_send_json_error(); // This auto-dies.
		}

		// Do the form validation
		$license_key = isset( $_POST['license_key'] ) ? sanitize_text_field( $_POST['license_key'] ) : '';
		$item_name = isset( $_POST['item_name'] ) ? sanitize_text_field( $_POST['item_name'] ) : '';
		$option_name = isset( $_POST['option_name'] ) ? sanitize_text_field( $_POST['option_name'] ) : '';
		if ( empty( $license_key ) || empty( $item_name ) ) {
			new CTF_Response( false, array() );
		}

		// make the remote license check API call
		$ctf_license_data = $this->get_license_data( $license_key, 'check_license', $item_name );

		// update options data
		$license_changed = $this->update_recheck_license_data( $ctf_license_data, $item_name, $option_name );

		// send AJAX response back
		new CTF_Response( true, array(
			'license' => $ctf_license_data['license'],
			'licenseChanged' => $license_changed
		) );
	}


/** Function ctf_clear_cache_admin() called by wp_ajax hooks: {'ctf_clear_cache_admin'} **/
/** No params detected :-/ **/


/** Function ctf_dpa_reset() called by wp_ajax hooks: {'ctf_dpa_reset'} **/
/** No params detected :-/ **/


/** Function TwitterFeed\Builder\CTF_Feed_Builder() called by wp_ajax hooks: {'ctf_other_plugins_modal', 'ctf_dismiss_onboarding'} **/
/** No function found :-/ **/


/** Function ctf_activate_license() called by wp_ajax hooks: {'ctf_activate_license'} **/
/** Parameters found in function ctf_activate_license(): {"post": ["license_key"]} **/
function ctf_activate_license() {
		check_ajax_referer( 'ctf_admin_nonce', 'nonce' );

		$cap = ctf_get_manage_options_cap();
		if ( ! current_user_can( $cap ) ) {
			wp_send_json_error(); // This auto-dies.
		}

		// do the form validation to check if license_key is not empty
		if ( empty( $_POST[ 'license_key' ] ) ) {
			new CTF_Response( false, array(
				'message' => __( 'License key required!', 'custom-twitter-feeds' ),
			) );
		}
		$license_key = sanitize_text_field( $_POST[ 'license_key' ] );
		// make the remote api call and get license data
		$ctf_license_data = $this->get_license_data( $license_key, 'activate_license', CTF_PRODUCT_NAME );

		// update the license data
		if( !empty( $ctf_license_data ) ) {
			update_option( 'ctf_license_data', $ctf_license_data );
		}
		// update the licnese key only when the license status is activated
		update_option( 'ctf_license_key', $license_key );
		// update the license status
		update_option( 'ctf_license_status', $ctf_license_data['license'] );

		// Check if there is any error in the license key then handle it
		$ctf_license_data = $this->get_license_error_message( $ctf_license_data );


		// Send ajax response back to client end
		$data = array(
			'licenseStatus' => $ctf_license_data['license'],
			'licenseData' => $ctf_license_data
		);
		new CTF_Response( true, $data );
	}


/** Function ctf_activate_addon() called by wp_ajax hooks: {'ctf_activate_addon'} **/
/** Parameters found in function ctf_activate_addon(): {"post": ["plugin", "type"]} **/
function ctf_activate_addon() {

	require_once trailingslashit( CTF_PLUGIN_DIR ) . 'inc/Admin/PluginSilentUpgrader.php';
	require_once trailingslashit( CTF_PLUGIN_DIR ) . 'inc/Admin/PluginSilentUpgraderSkin.php';
	require_once trailingslashit( CTF_PLUGIN_DIR ) . 'inc/Admin/class-install-skin.php';
	// Run a security check.
	check_ajax_referer( 'ctf-admin', 'nonce' );
	// Check for permissions.
	if ( ! current_user_can( 'manage_options' ) ) {
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
				wp_send_json_success( esc_html__( 'Plugin activated.', 'custom-twitter-feeds' ) );
			} else {
				wp_send_json_success( esc_html__( 'Addon activated.', 'custom-twitter-feeds' ) );
			}
		}
	}

	wp_send_json_error( esc_html__( 'Could not activate addon. Please activate from the Plugins page.', 'custom-twitter-feeds' ) );
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


/** Function ctf_clear_twittercard_cache() called by wp_ajax hooks: {'ctf_clear_twittercard_cache'} **/
/** No params detected :-/ **/


/** Function ctf_install_addon() called by wp_ajax hooks: {'ctf_install_addon'} **/
/** Parameters found in function ctf_install_addon(): {"post": ["plugin", "type"]} **/
function ctf_install_addon() {
	require_once trailingslashit( CTF_PLUGIN_DIR ) . 'inc/Admin/PluginSilentUpgrader.php';
	require_once trailingslashit( CTF_PLUGIN_DIR ) . 'inc/Admin/PluginSilentUpgraderSkin.php';
	// Run a security check.
	check_ajax_referer( 'ctf-admin', 'nonce' );

	// Check for permissions.
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error();
	}

	$error = esc_html__( 'Could not install addon. Please download from wpforms.com and install manually.', 'custom-twitter-feeds' );

	if ( empty( $_POST['plugin'] ) ) {
		wp_send_json_error( $error );
	}

	// Set the current screen to avoid undefined notices.
	set_current_screen( 'custom-twitter-feeds' );

	// Prepare variables.
	$url = esc_url_raw(
		add_query_arg(
			array(
				'page' => 'custom-twitter-feeds',
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

	require_once CTF_PLUGIN_DIR . 'inc/Admin/class-install-skin.php';

	// Do not allow WordPress to search/download translations, as this will break JS output.
	remove_action( 'upgrader_process_complete', array( 'Language_Pack_Upgrader', 'async_upgrade' ), 20 );

	// Create the plugin upgrader with our custom skin.
	$installer = new CTF\Helpers\PluginSilentUpgrader( new CTF_Install_Skin() );

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
					'msg'          => 'plugin' === $type ? esc_html__( 'Plugin installed & activated.', 'custom-twitter-feeds' ) : esc_html__( 'Addon installed & activated.', 'custom-twitter-feeds' ),
					'is_activated' => true,
					'basename'     => $plugin_basename,
				)
			);
		} else {
			wp_send_json_success(
				array(
					'msg'          => 'plugin' === $type ? esc_html__( 'Plugin installed.', 'custom-twitter-feeds' ) : esc_html__( 'Addon installed.', 'custom-twitter-feeds' ),
					'is_activated' => false,
					'basename'     => $plugin_basename,
				)
			);
		}
	}

	wp_send_json_error( $error );
}


/** Function review_notice_consent() called by wp_ajax hooks: {'ctf_review_notice_consent_update'} **/
/** Parameters found in function review_notice_consent(): {"post": ["consent"]} **/
function review_notice_consent() {
		//Security Checks
		check_ajax_referer( 'ctf-admin', 'ctf_nonce' );
		$cap = ctf_get_manage_options_cap();

		if ( ! current_user_can( $cap ) ) {
			wp_send_json_error(); // This auto-dies.
		}

		$consent = isset( $_POST[ 'consent' ] ) ? sanitize_text_field( $_POST[ 'consent' ] ) : '';

		update_option( 'ctf_review_consent', $consent );

		if ( $consent == 'no' ) {
			$ctf_statuses_option = get_option( 'ctf_statuses', array() );
			update_option( 'ctf_rating_notice', 'dismissed', false );
			$ctf_statuses_option['rating_notice_dismissed'] = ctf_get_current_time();
			update_option( 'ctf_statuses', $ctf_statuses_option, false );
		}
		wp_die();
	}


/** Function ctf_import_settings_json() called by wp_ajax hooks: {'ctf_import_settings_json'} **/
/** Parameters found in function ctf_import_settings_json(): {"files": ["file"]} **/
function ctf_import_settings_json() {
		check_ajax_referer( 'ctf_admin_nonce', 'nonce' );

		$cap = ctf_get_manage_options_cap();
		if ( ! current_user_can( $cap ) ) {
			wp_send_json_error(); // This auto-dies.
		}

		$filename = $_FILES['file']['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		if ( 'json' !== $ext ) {
			new CTF_Response( false, [] );
		}
		$imported_settings = file_get_contents( $_FILES["file"]["tmp_name"] );
		// check if the file is empty
		if ( empty( $imported_settings ) ) {
			new CTF_Response( false, [] );
		}
		$feed_return = \TwitterFeed\Builder\CTF_Feed_Saver_Manager::import_feed( $imported_settings );
		// check if there's error while importing
		if ( ! $feed_return['success'] ) {
			new CTF_Response( false, [] );
		}
		// Once new feed has imported lets export all the feeds to update in front end
		$exported_feeds = \TwitterFeed\Builder\CTF_Db::feeds_query();
		$feeds = array();
		foreach( $exported_feeds as $feed_id => $feed ) {
			$feeds[] = array(
				'id' => $feed['id'],
				'name' => $feed['feed_name']
			);
		}

		new CTF_Response( true, array(
			'feeds' => $feeds
		) );
	}


/** Function ajax_record_session() called by wp_ajax hooks: {'ctf_smash_usage_record_session'} **/
/** Parameters found in function ajax_record_session(): {"post": ["duration_seconds"]} **/
function ajax_record_session() {
		check_ajax_referer( 'ctf_smash_usage_record_session', 'nonce' );
		if ( ( ! current_user_can( 'manage_twitter_feed_options' ) && ! current_user_can( 'manage_options' )) || ! Config::is_enabled() ) {
			wp_send_json_error();
		}
		$seconds = isset( $_POST['duration_seconds'] ) ? (int) $_POST['duration_seconds'] : 0;
		EventRecorder::record_session_duration( $seconds );
		wp_send_json_success();
	}


/** Function ctf_check_license() called by wp_ajax hooks: {'ctf_check_license'} **/
/** No params detected :-/ **/


/** Function ctf_do_locator() called by wp_ajax hooks: {'ctf_do_locator', 'nopriv_ctf_do_locator'} **/
/** Parameters found in function ctf_do_locator(): {"post": ["feed_id", "atts", "location", "post_id"]} **/
function ctf_do_locator() {
	if ( ! isset( $_POST['feed_id'] ) || strpos( $_POST['feed_id'], 'ctf' ) === false ) {
		die( 'invalid feed ID');
	}

	$feed_id = sanitize_text_field( $_POST['feed_id'] );

	$atts_raw = isset( $_POST['atts'] ) ? json_decode( stripslashes( $_POST['atts'] ), true ) : array();
	if ( is_array( $atts_raw ) ) {
		array_map( 'sanitize_text_field', $atts_raw );
	} else {
		$atts_raw = array();
	}
	$atts = $atts_raw; // now sanitized

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

	ctf_do_background_tasks( $feed_details );

	wp_die( 'locating success' );
}


/** Function ctf_clear_cache_settings() called by wp_ajax hooks: {'ctf_clear_cache_settings'} **/
/** Parameters found in function ctf_clear_cache_settings(): {"post": ["model"]} **/
function ctf_clear_cache_settings() {
		check_ajax_referer( 'ctf_admin_nonce', 'nonce' );

		$cap = ctf_get_manage_options_cap();
		if ( ! current_user_can( $cap ) ) {
			wp_send_json_error(); // This auto-dies.
		}

		// Get the updated cron schedule interval and time settings from user input and update the database
		$model = isset( $_POST[ 'model' ] ) ? sanitize_text_field( $_POST['model'] ) : null;
		if ( $model !== null ) {
			$model = (array) \json_decode( \stripslashes( $model ) );
			$feeds = (array) $model['feeds'];
			$ctf_options = wp_parse_args( get_option( 'ctf_options' ), $this->default_settings_options() );

			$cron_clear_cache = isset($ctf_options['cron_cache_clear']) ? $ctf_options['cron_cache_clear'] : 'no';
			ctf_clear_cache_sql();

			/*
			$cache_time = isset($feeds['cache_time']) ? (int)$feeds['cache_time'] : 1;
			$cache_time_unit = isset($feeds['cache_time_unit']) ? (int)$feeds['cache_time_unit'] : 3600;
			if ($cron_clear_cache == 'no') {
				wp_clear_scheduled_hook('ctf_cron_job');
			}
			elseif ($cron_clear_cache == 'yes') {
				//Clear the existing cron event
				wp_clear_scheduled_hook('ctf_cron_job');
				//Set the event schedule based on what the caching time is set to
				if ($cache_time_unit == 3600 && $cache_time > 5) {
					$ctf_cron_schedule = 'twicedaily';
				}
				elseif ($cache_time_unit == 86400) {
					$ctf_cron_schedule = 'daily';
				}
				else {
					$ctf_cron_schedule = 'hourly';
				}

				wp_schedule_event(time() , $ctf_cron_schedule, 'ctf_cron_job');
			}
			*/

			// Clear the stored caches.
			$cache_handler = new CTF_Cache_Handler();
			$cache_handler->clear_all_cache();

			$ctf_cache_cron_interval = $ctf_options['ctf_cache_cron_interval'];
			$ctf_cache_cron_time     = $ctf_options['ctf_cache_cron_time'];
			$ctf_cache_cron_am_pm    = $ctf_options['ctf_cache_cron_am_pm'];

			SB_Twitter_Cron_Updater::start_cron_job( $ctf_cache_cron_interval, $ctf_cache_cron_time, $ctf_cache_cron_am_pm );
		}



		new CTF_Response( true, array(
			'cronNextCheck' => $this->get_cron_next_check()
		) );
	}


/** Function ctf_deactivate_addon() called by wp_ajax hooks: {'ctf_deactivate_addon'} **/
/** Parameters found in function ctf_deactivate_addon(): {"post": ["type", "plugin"]} **/
function ctf_deactivate_addon() {
	require_once trailingslashit( CTF_PLUGIN_DIR ) . 'inc/Admin/PluginSilentUpgrader.php';
	require_once trailingslashit( CTF_PLUGIN_DIR ) . 'inc/Admin/PluginSilentUpgraderSkin.php';
	require_once trailingslashit( CTF_PLUGIN_DIR ) . 'inc/Admin/class-install-skin.php';
	// Run a security check.
	check_ajax_referer( 'ctf-admin', 'nonce' );

	// Check for permissions.
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error();
	}

	$type = 'addon';
	if ( ! empty( $_POST['type'] ) ) {
		$type = sanitize_key( $_POST['type'] );
	}

	if ( isset( $_POST['plugin'] ) ) {
		deactivate_plugins( $_POST['plugin'] );

		if ( 'plugin' === $type ) {
			wp_send_json_success( esc_html__( 'Plugin deactivated.', 'custom-twitter-feeds' ) );
		} else {
			wp_send_json_success( esc_html__( 'Addon deactivated.', 'custom-twitter-feeds' ) );
		}
	}

	wp_send_json_error( esc_html__( 'Could not deactivate the addon. Please deactivate from the Plugins page.', 'custom-twitter-feeds' ) );
}


/** Function ctf_get_more_posts() called by wp_ajax hooks: {'nopriv_ctf_get_more_posts', 'ctf_get_more_posts'} **/
/** Parameters found in function ctf_get_more_posts(): {"post": ["shortcode_data", "last_id_data", "num_needed", "ids_to_remove", "v2feed", "persistent_index", "feed_id", "location", "post_id"]} **/
function ctf_get_more_posts() {
    $shortcode_data = json_decode( str_replace( '\"', '"', sanitize_text_field( $_POST['shortcode_data'] ) ), true ); // necessary to unescape quotes
    $last_id_data = isset( $_POST['last_id_data'] ) ? sanitize_text_field( $_POST['last_id_data'] ) : '';
    $num_needed = isset( $_POST['num_needed'] ) ? (int)$_POST['num_needed'] : 0;
    $ids_to_remove = isset( $_POST['ids_to_remove'] ) ? $_POST['ids_to_remove'] : array();
    $feed           = isset( $_POST['v2feed'] ) ? sanitize_key( $_POST['v2feed'] ) : '';
	if ( empty( $shortcode_data ) ) {
		$shortcode_data = array();
	}
	$shortcode_data['feed'] = $feed;

    $is_pagination = empty( $last_id_data ) ? 0 : 1;
    $persistent_index = isset( $_POST['persistent_index'] ) ? sanitize_text_field( $_POST['persistent_index'] ) : '';

    $twitter_feed = TwitterFeed\CtfFeed::init( $shortcode_data, $last_id_data, $num_needed, $ids_to_remove, $persistent_index );

	if ( ! CTF_DOING_SMASH_TWITTER && ! $twitter_feed->feed_options['persistentcache'] ) {
		$twitter_feed->maybeCacheTweets();
	}

	$atts = $shortcode_data;
	$feed_id = isset( $_POST['feed_id'] ) ? sanitize_text_field( $_POST['feed_id'] ) : 'unknown';
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

	ctf_do_background_tasks( $feed_details );

    echo $twitter_feed->getItemSetHtml( $is_pagination );

    die();
}


/** Function TwitterFeed\Admin\CTF_Upgrader() called by wp_ajax hooks: {'nopriv_ctf_run_one_click_upgrade', 'ctf_maybe_upgrade_redirect'} **/
/** No function found :-/ **/


/** Function ctf_dismiss_license_notice() called by wp_ajax hooks: {'ctf_dismiss_license_notice'} **/
/** No params detected :-/ **/


/** Function ctf_clear_image_resize_cache() called by wp_ajax hooks: {'ctf_clear_image_resize_cache'} **/
/** No params detected :-/ **/


/** Function dismiss() called by wp_ajax hooks: {'ctf_dashboard_notification_dismiss'} **/
/** Parameters found in function dismiss(): {"get": ["ctf_ignore_rating_notice_nag", "ctf_ignore_new_user_sale_notice", "ctf_ignore_bfcm_sale_notice", "ctf_dismiss"]} **/
function dismiss() {
		global $current_user;
		$user_id = $current_user->ID;
		$ctf_statuses_option = get_option( 'ctf_statuses', array() );

		if ( isset( $_GET['ctf_ignore_rating_notice_nag'] ) ) {
			if ( (int)$_GET['ctf_ignore_rating_notice_nag'] === 1 ) {
				update_option( 'ctf_rating_notice', 'dismissed', false );
				$ctf_statuses_option['rating_notice_dismissed'] = ctf_get_current_time();
				update_option( 'ctf_statuses', $ctf_statuses_option, false );

			} elseif ( $_GET['ctf_ignore_rating_notice_nag'] === 'later' ) {
				set_transient( 'custom_twitter_feeds_rating_notice_waiting', 'waiting', 2 * WEEK_IN_SECONDS );
				update_option( 'ctf_rating_notice', 'pending', false );
			}
		}

		if ( isset( $_GET['ctf_ignore_new_user_sale_notice'] ) ) {
			$response = sanitize_text_field( $_GET['ctf_ignore_new_user_sale_notice'] );
			if ( $response === 'always' ) {
				update_user_meta( $user_id, 'ctf_ignore_new_user_sale_notice', 'always' );

				$current_month_number = (int)date('n', ctf_get_current_time() );
				$not_early_in_the_year = ($current_month_number > 5);

				if ( $not_early_in_the_year ) {
					update_user_meta( $user_id, 'ctf_ignore_bfcm_sale_notice', date( 'Y', ctf_get_current_time() ) );
				}

			}
		}

		if ( isset( $_GET['ctf_ignore_bfcm_sale_notice'] ) ) {
			$response = sanitize_text_field( $_GET['ctf_ignore_bfcm_sale_notice'] );
			if ( $response === 'always' ) {
				update_user_meta( $user_id, 'ctf_ignore_bfcm_sale_notice', 'always' );
			} elseif ( $response === date( 'Y', ctf_get_current_time() ) ) {
				update_user_meta( $user_id, 'ctf_ignore_bfcm_sale_notice', date( 'Y', ctf_get_current_time() ) );
			}
			update_user_meta( $user_id, 'ctf_ignore_new_user_sale_notice', 'always' );
		}

		if ( isset( $_GET['ctf_dismiss'] ) ) {
			if ( $_GET['ctf_dismiss'] === 'review' ) {
				update_option( 'ctf_rating_notice', 'dismissed', false );
				$ctf_statuses_option['rating_notice_dismissed'] = ctf_get_current_time();
				update_option( 'ctf_statuses', $ctf_statuses_option, false );
			} elseif ( $_GET['ctf_dismiss'] === 'discount' ) {
				update_user_meta( $user_id, 'ctf_ignore_new_user_sale_notice', 'always' );

				$current_month_number = (int)date('n', ctf_get_current_time() );
				$not_early_in_the_year = ($current_month_number > 5);

				if ( $not_early_in_the_year ) {
					update_user_meta( $user_id, 'ctf_ignore_bfcm_sale_notice', date( 'Y', ctf_get_current_time() ) );
				}
			}
			update_user_meta( $user_id, 'ctf_ignore_new_user_sale_notice', 'always' );
		}
	}


/** Function ctf_save_settings() called by wp_ajax hooks: {'ctf_save_settings'} **/
/** Parameters found in function ctf_save_settings(): {"post": ["ctf_license_key"]} **/
function ctf_save_settings() {
		check_ajax_referer( 'ctf_admin_nonce', 'nonce' );

		$cap = ctf_get_manage_options_cap();
		if ( ! current_user_can( $cap ) ) {
			wp_send_json_error(); // This auto-dies.
		}

		$data = $_POST;
		$model = isset( $data[ 'model' ] ) ? $data['model'] : null;

		// return if the model is null
		if ( null === $model ) {
			return;
		}

		// get the ctf license key and extensions license key
		$ctf_license_key = sanitize_text_field( $_POST['ctf_license_key'] );

		// Only update the ctf_license_key value when it's inactive
		if ( get_option( 'ctf_license_status') == 'inactive' ) {
			if ( empty( $ctf_license_key ) || strlen( $ctf_license_key ) < 1 ) {
				delete_option( 'ctf_license_key' );
				delete_option( 'ctf_license_data' );
				delete_option( 'ctf_license_status' );
			} else {
				update_option( 'ctf_license_key', $ctf_license_key );
			}
		} else {
			$license_key = trim( get_option( 'ctf_license_key' ) );

			if ( empty( $ctf_license_key ) && ! empty( $license_key ) ) {
				$ctf_license_data = $this->get_license_data( $license_key, 'deactivate_license', CTF_PRODUCT_NAME );

				delete_option( 'ctf_license_key' );
				delete_option( 'ctf_license_data' );
				delete_option( 'ctf_license_status' );
			}
		}

		$model = (array) \json_decode( \stripslashes( $model ) );

		$general = (array) $model['general'];
		$feeds = (array) $model['feeds'];
		$translation = (array) $model['translation'];
		$advanced = (array) $model['advanced'];

		// Get the values and sanitize
		$ctf_settings = get_option( 'ctf_options', array() );
		/**
		 * General Tab
		 */
		$ctf_settings['preserve_settings']       = $general['preserveSettings'];
		$ctf_settings[ CTF_SITE_ACCESS_TOKEN_KEY ]       = $general['siteKey'];

		// Save translation settings data
		foreach( $translation as $key => $val ) {
			$ctf_settings[ $key ] = $val;
		}

		/**
		 * Feeds Tab
		 */
		if ( current_user_can( 'unfiltered_html' ) ) {
			$ctf_settings['custom_css'] = $feeds['customCSS'];
			$ctf_settings['custom_js']  = $feeds['customJS'];
		}
		$ctf_settings['gdpr'] 			        = sanitize_text_field( $feeds['gdpr'] );
		$ctf_settings['ctf_caching_type']    	= sanitize_text_field( $feeds['cachingType'] );
		$ctf_settings['cache_time']    			= sanitize_text_field( $feeds['cacheTime'] );
		$ctf_settings['cache_time_unit']        = sanitize_text_field( $feeds['cacheTimeUnit'] );

		$ctf_settings['ctf_cache_cron_interval'] = sanitize_text_field( $feeds['cronInterval'] );
		$ctf_settings['ctf_cache_cron_time']     = sanitize_text_field( $feeds['cronTime'] );
		$ctf_settings['ctf_cache_cron_am_pm']    = sanitize_text_field( $feeds['cronAmPm'] );
		/**
		 * Advanced Tab
		 */
		$ctf_settings['rebranding'] 				= (bool)$advanced['rebranding'];
		$ctf_settings['resizing'] 				= (bool)$advanced['resizing'];
		$ctf_settings['persistentcache'] 		= (bool)$advanced['persistentcache'];
		$ctf_settings['ajax_theme'] 			= (bool)$advanced['ajax_theme'];
		$ctf_settings['headenqueue'] 			= (bool)$advanced['headenqueue'];
		$ctf_settings['customtemplates'] 		= (bool)$advanced['customtemplates'];
		$ctf_settings['creditctf'] 				= (bool)$advanced['creditctf'];
		$ctf_settings['autores'] 				= (bool)$advanced['autores'];
		$ctf_settings['customtemplates'] 		= (bool)$advanced['customtemplates'];
		$ctf_settings['disableintents'] 		= !(bool)$advanced['enableintents'];
		$ctf_settings['request_method'] 		= sanitize_text_field($advanced['request_method']);
		$ctf_settings['cron_cache_clear'] 		= sanitize_text_field($advanced['cron_cache_clear']);
		$ctf_settings['sslonly'] 				= (bool)$advanced['sslonly'];
		$ctf_settings['curlcards'] 				= (bool)$advanced['curlcards'];


		if ( isset( $advanced['usage_tracking'] ) ) {
			$tracking_enabled = (bool) $advanced['usage_tracking'];
			$usage_tracking   = get_option(
				'ctf_usage_tracking',
				array(
					'enabled'   => SmashTrackingConfig::DEFAULT_ENABLED,
					'last_send' => 0,
				)
			);
			$last_send        = is_array( $usage_tracking ) && isset( $usage_tracking['last_send'] ) ? $usage_tracking['last_send'] : 0;
			update_option(
				'ctf_usage_tracking',
				array(
					'enabled'   => $tracking_enabled,
					'last_send' => $last_send,
				),
				false
			);
			if ( ! $tracking_enabled ) {
				wp_clear_scheduled_hook( SmashTrackingConfig::CRON_HOOK );
			}
		}

		// Update the ctf_style_settings option that contains data for translation and advanced tabs
		update_option( 'ctf_options', $ctf_settings );

		// clear cron caches
		SB_Twitter_Cron_Updater::start_cron_job( $ctf_settings['ctf_cache_cron_interval'], $ctf_settings['ctf_cache_cron_time'], $ctf_settings['ctf_cache_cron_am_pm']  );

		// Clear the stored caches.
		$cache_handler = new CTF_Cache_Handler();
		$cache_handler->clear_persistent_cache();

		new CTF_Response( true, array(
			'cronNextCheck' => $this->get_cron_next_check()
		) );
	}


/** Function ctf_deactivate_license() called by wp_ajax hooks: {'ctf_deactivate_license'} **/
/** No params detected :-/ **/


/** Function ctf_background_processing() called by wp_ajax hooks: {'nopriv_ctf_background_processing', 'ctf_background_processing'} **/
/** Parameters found in function ctf_background_processing(): {"post": ["feed_id", "atts", "location", "post_id"]} **/
function ctf_background_processing() {

	if ( ! isset( $_POST['feed_id'] ) ) {
		return;
	}
	$feed_id = sanitize_text_field( $_POST['feed_id'] );

	$atts_raw = isset( $_POST['atts'] ) ? json_decode( stripslashes( $_POST['atts'] ), true ) : array();
	if ( is_array( $atts_raw ) ) {
		array_map( 'sanitize_text_field', $atts_raw );
	} else {
		$atts_raw = array();
	}
	$atts = $atts_raw; // now sanitized

	$location     = isset( $_POST['location'] ) && in_array( $_POST['location'], array( 'header', 'footer', 'sidebar', 'content' ), true ) ? sanitize_text_field( $_POST['location'] ) : 'unknown';
	$post_id      = isset( $_POST['post_id'] ) && $_POST['post_id'] !== 'unknown' ? (int) $_POST['post_id'] : 'unknown';
	$feed_details = array(
		'feed_id'  => $feed_id,
		'atts'     => $atts,
		'location' => array(
			'post_id' => $post_id,
			'html'    => $location,
		),
	);

	ctf_do_background_tasks( $feed_details );



	//$twitter_feed_settings = new CTF_Settings_Pro( $atts );
	//$twitter_feed_settings->set_feed_type_and_terms();
	//$tw_settings = $twitter_feed_settings->get_settings();
	$twitter_feed_object = CtfFeed::init( $atts, '', 0, array(), 0 );
	$tw_settings = $twitter_feed_object->feed_options;

	$twitter_feed = new CTF_Feed( $feed_id );
	$twitter_feed->set_cache( $tw_settings['cache_time'], $tw_settings );
	$posts = array();
	if ( $twitter_feed->regular_cache_exists() ) {
		$twitter_feed->set_post_data_from_cache();
		$posts = $twitter_feed->get_post_data();
	}

	$twitter_return['resizing'] = 'success';

	echo wp_json_encode( $twitter_return );

	wp_die();
}


/** Function ajax_record_event() called by wp_ajax hooks: {'ctf_smash_usage_record_event'} **/
/** Parameters found in function ajax_record_event(): {"post": ["event_name"]} **/
function ajax_record_event() {
		check_ajax_referer( 'ctf_smash_usage_record_event', 'nonce' );
		if ( ! current_user_can( 'manage_twitter_feed_options' ) && ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}
		$event_name = isset( $_POST['event_name'] ) ? sanitize_text_field( wp_unslash( $_POST['event_name'] ) ) : '';
		$allowed    = array(
			'setup_wizard_started',
			'setup_wizard_step_completed',
			'setup_wizard_completed',
			'setup_wizard_abandoned',
			'upgrade_prompt_shown',
		);
		if ( '' !== $event_name && in_array( $event_name, $allowed, true ) ) {
			EventRecorder::record( $event_name );
		}
		wp_send_json_success();
	}


/** Function TwitterFeed\Builder\CTF_Feed_Saver_Manager() called by wp_ajax hooks: {'ctf_feed_saver_manager_get_locations_page', 'ctf_feed_saver_manager_get_feed_list_page', 'ctf_feed_saver_manager_get_feed_settings', 'ctf_feed_saver_manager_builder_update', 'ctf_feed_saver_manager_check_twitter_list_by_id', 'ctf_feed_saver_manager_fly_preview', 'ctf_feed_saver_manager_search_username_lists', 'ctf_feed_saver_manager_connect_manual_account', 'ctf_feed_saver_manager_duplicate_feed', 'ctf_feed_saver_manager_clear_single_feed_cache', 'ctf_feed_saver_manager_delete_feeds', 'ctf_feed_saver_manager_importer', 'ctf_feed_saver_manager_delete_account', 'ctf_feed_saver_manager_retrieve_comments', 'ctf_feed_saver_manager_recache_feed'} **/
/** No function found :-/ **/


/** Function ctf_export_settings_json() called by wp_ajax hooks: {'ctf_export_settings_json'} **/
/** Parameters found in function ctf_export_settings_json(): {"get": ["feed_id"]} **/
function ctf_export_settings_json() {
		\TwitterFeed\Builder\CTF_Feed_Builder::check_privilege();
		if ( ! isset( $_GET['feed_id'] ) ) {
			return;
		}
		$feed_id = filter_var( $_GET['feed_id'], FILTER_SANITIZE_NUMBER_INT );
		$feed = \TwitterFeed\Builder\CTF_Feed_Saver_Manager::get_export_json( $feed_id );
		$feed_info = \TwitterFeed\Builder\CTF_Db::feeds_query( array('id' => $feed_id) );
		$feed_name = strtolower( $feed_info[0]['feed_name'] );
		$filename = 'ctf-feed-' . $feed_name . '.json';
		// create a new empty file in the php memory
		$file  = fopen( 'php://memory', 'w' );
		fwrite( $file, $feed );
		fseek( $file, 0 );
		header( 'Content-type: application/json' );
		header( 'Content-disposition: attachment; filename = "' . $filename . '";' );
		fpassthru( $file );
		exit;
	}


/** Function ctf_clear_persistent_cache() called by wp_ajax hooks: {'ctf_clear_persistent_cache'} **/
/** No params detected :-/ **/


/** Function ctf_lite_dismiss() called by wp_ajax hooks: {'ctf_lite_dismiss'} **/
/** Parameters found in function ctf_lite_dismiss(): {"post": ["ctf_nonce"]} **/
function ctf_lite_dismiss() {
	$cap = ctf_get_manage_options_cap();

	if ( ! current_user_can( $cap ) ) {
		wp_send_json_error(); // This auto-dies.
	}

	$nonce = isset( $_POST['ctf_nonce'] ) ? sanitize_text_field( $_POST['ctf_nonce'] ) : '';

	if ( ! wp_verify_nonce( $nonce, 'ctf-smash-balloon' ) ) {
		die ( 'You did not do this the right way!' );
	}

	set_transient( 'twitter_feed_dismiss_lite', 'dismiss', 1 * WEEK_IN_SECONDS );

	die();
}


/** Function dismiss_upgrade_notice() called by wp_ajax hooks: {'ctf_dismiss_upgrade_notice'} **/
/** No params detected :-/ **/


/** Function handle_ajax() called by wp_ajax hooks: {'sb_deactivation_feedback', 'sb_feature_suggestion'} **/
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


