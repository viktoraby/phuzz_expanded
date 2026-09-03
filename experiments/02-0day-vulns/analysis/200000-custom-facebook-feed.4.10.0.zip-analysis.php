<?php
/***
*
*Found actions: 59
*Found functions:41
*Extracted functions:37
*Total parameter names extracted: 19
*Overview: {'cff_install_addon': {'cff_install_addon'}, 'handle_unused_feed_usage': {'cff_reset_unused_feed_usage'}, 'dismiss_upgrade_notice': {'cff_dismiss_upgrade_notice'}, 'cff_feed_locator': {'nopriv_feed_locator', 'feed_locator'}, 'cff_review_notice_dismiss': {'cff_review_notice_dismiss'}, 'cff_activate_extension_license': {'cff_activate_extension_license'}, 'process_wizard_data': {'cff_feed_saver_manager_process_wizard'}, 'create_temp_user_ajax_call': {'cff_create_temp_user'}, 'preview': {'sb_facebookfeed_divi_preview'}, 'cff_clear_image_resize_cache': {'cff_clear_image_resize_cache'}, 'CustomFacebookFeed\\Admin\\CFF_Upgrader': {'nopriv_cff_run_one_click_upgrade', 'cff_maybe_upgrade_redirect'}, 'dismiss_critical_notice': {'cff_dismiss_critical_notice'}, 'cff_dismiss_custom_cssjs_notice': {'cff_dismiss_custom_cssjs_notice'}, 'cff_import_settings_json': {'cff_import_settings_json'}, 'cff_activate_license': {'cff_activate_license'}, 'CustomFacebookFeed\\Builder\\CFF_Feed_Saver_Manager': {'cff_feed_saver_manager_fly_preview', 'cff_feed_saver_manager_importer', 'cff_feed_saver_manager_delete_feeds', 'cff_feed_saver_manager_builder_update', 'cff_feed_saver_manager_get_locations_page', 'cff_feed_saver_manager_duplicate_feed', 'cff_feed_saver_manager_clear_single_feed_cache', 'cff_feed_saver_manager_delete_source', 'cff_feed_saver_manager_retrieve_comments', 'cff_feed_saver_manager_get_feed_list_page', 'cff_feed_saver_manager_get_feed_settings'}, 'cff_deactivate_extension_license': {'cff_deactivate_extension_license'}, 'review_notice_consent': {'cff_review_notice_consent_update'}, 'disable_instagram_oembed': {'disable_instagram_oembed'}, 'handle_ajax': {'sb_deactivation_feedback', 'sb_feature_suggestion'}, 'dismiss_wizard': {'cff_feed_saver_manager_dismiss_wizard'}, 'ajax_record_event': {'cff_smash_usage_record_event'}, 'CustomFacebookFeed\\Builder\\CFF_Feed_Builder': {'sb_other_plugins_modal', 'cff_dismiss_onboarding'}, 'cff_oembed_disable': {'cff_oembed_disable'}, 'CustomFacebookFeed\\Builder\\CFF_Source': {'cff_source_builder_update_multiple', 'cff_source_get_page', 'cff_source_builder_update', 'cff_source_get_featured_post_preview', 'cff_source_get_playlist_post_preview'}, 'cff_save_settings': {'cff_save_settings'}, 'ajax_record_session': {'cff_smash_usage_record_session'}, 'cff_deactivate_addon': {'cff_deactivate_addon'}, 'cff_export_settings_json': {'cff_export_settings_json'}, 'cff_test_connection': {'cff_test_connection'}, 'dismiss': {'cff_dashboard_notification_dismiss'}, 'oembed_connect_init': {'cff_oembed_connect_init'}, 'delete_temp_user_ajax_call': {'cff_delete_temp_user'}, 'install_plugin': {'am_recommended_block_install'}, 'cff_dpa_reset': {'cff_dpa_reset'}, 'disable_facebook_oembed': {'disable_facebook_oembed'}, 'cff_lite_dismiss': {'cff_lite_dismiss'}, 'cff_activate_addon': {'cff_activate_addon'}, 'cff_clear_cache': {'cff_clear_cache'}, 'cff_ppca_token_check_flag': {'cff_ppca_token_check_flag'}, 'cff_deactivate_license': {'cff_deactivate_license'}}
*
***/

/** Function cff_install_addon() called by wp_ajax hooks: {'cff_install_addon'} **/
/** Parameters found in function cff_install_addon(): {"post": ["plugin", "type"]} **/
function cff_install_addon()
{

	// Run a security check.
	check_ajax_referer('cff-admin', 'nonce');

	// Check for permissions.
	if (! current_user_can('install_plugins')) {
		wp_send_json_error();
	}

	$error = esc_html__('Could not install addon. Please download from smashballoon.com and install manually.', 'custom-facebook-feed');

	if (empty($_POST['plugin'])) {
		wp_send_json_error($error);
	}

	// Set the current screen to avoid undefined notices.
	set_current_screen('cff-about');

	// Prepare variables.
	$url = esc_url_raw(
		add_query_arg(
			array(
				'page' => 'cff-about',
			),
			admin_url('admin.php')
		)
	);

	$creds = request_filesystem_credentials($url, '', false, false, null);

	// Check for file system permissions.
	if (false === $creds) {
		wp_send_json_error($error);
	}

	if (! WP_Filesystem($creds)) {
		wp_send_json_error($error);
	}

	/*
	 * We do not need any extra credentials if we have gotten this far, so let's install the plugin.
	 */

	// require_once CFF_PLUGIN_DIR . 'admin/class-install-skin.php';

	// Do not allow WordPress to search/download translations, as this will break JS output.
	remove_action('upgrader_process_complete', array( 'Language_Pack_Upgrader', 'async_upgrade' ), 20);

	// Create the plugin upgrader with our custom skin.
	$installer = new CustomFacebookFeed\Helpers\PluginSilentUpgrader(new CustomFacebookFeed\Admin\CFF_Install_Skin());

	// Error check.
	if (! method_exists($installer, 'install') || empty($_POST['plugin'])) {
		wp_send_json_error($error);
	}

	$installer->install( $_POST['plugin'] ); // phpcs:ignore

	// Flush the cache and return the newly installed plugin basename.
	wp_cache_flush();

	$plugin_basename = $installer->plugin_info();

	if ($plugin_basename) {
		$type = 'addon';
		if (! empty($_POST['type'])) {
			$type = sanitize_key($_POST['type']);
		}

		// Activate the plugin silently.
		$activated = activate_plugin($plugin_basename);

		if (! is_wp_error($activated)) {
			wp_send_json_success(
				array(
					'msg'          => 'plugin' === $type ? esc_html__('Plugin installed & activated.', 'custom-facebook-feed') : esc_html__('Addon installed & activated.', 'custom-facebook-feed'),
					'is_activated' => true,
					'basename'     => $plugin_basename,
				)
			);
		} else {
			wp_send_json_success(
				array(
					'msg'          => 'plugin' === $type ? esc_html__('Plugin installed.', 'custom-facebook-feed') : esc_html__('Addon installed.', 'custom-facebook-feed'),
					'is_activated' => false,
					'basename'     => $plugin_basename,
				)
			);
		}
	}

	wp_send_json_error($error);
}


/** Function handle_unused_feed_usage() called by wp_ajax hooks: {'cff_reset_unused_feed_usage'} **/
/** No params detected :-/ **/


/** Function dismiss_upgrade_notice() called by wp_ajax hooks: {'cff_dismiss_upgrade_notice'} **/
/** No params detected :-/ **/


/** Function cff_feed_locator() called by wp_ajax hooks: {'nopriv_feed_locator', 'feed_locator'} **/
/** Parameters found in function cff_feed_locator(): {"post": ["feedLocatorData", "locator_nonce"]} **/
function cff_feed_locator()
	{

			$feed_locator_data_array = isset($_POST['feedLocatorData']) && !empty($_POST['feedLocatorData']) && is_array($_POST['feedLocatorData']) ? $_POST['feedLocatorData'] : false;
		if ($feed_locator_data_array != false) :
			foreach ($feed_locator_data_array as $single_feed_locator) {
				$can_do_background_tasks = false;

				$cap = current_user_can('manage_custom_facebook_feed_options') ? 'manage_custom_facebook_feed_options' : 'manage_options';
				$cap = apply_filters('cff_settings_pages_capability', $cap);
				if (current_user_can($cap)) {
					$can_do_background_tasks = true;
				} else {
					$nonce = isset($_POST['locator_nonce']) ? sanitize_text_field(wp_unslash($_POST['locator_nonce'])) : '';
					if (isset($single_feed_locator['postID']) && wp_verify_nonce($nonce, esc_attr('cff-locator-nonce-' . $single_feed_locator['postID']))) {
						$can_do_background_tasks = true;
					}
				}

				if ($can_do_background_tasks) {
					$feed_details = array(
						'feed_id' => $single_feed_locator['feedID'],
						'atts' =>  $single_feed_locator['shortCodeAtts'],
						'location' => array(
							'post_id' => $single_feed_locator['postID'],
							'html' => $single_feed_locator['location']
						)
					);
					$locator = new CFF_Feed_Locator($feed_details);
					$locator->add_or_update_entry();
				}
			}
		endif;
		die();
	}


/** Function cff_review_notice_dismiss() called by wp_ajax hooks: {'cff_review_notice_dismiss'} **/
/** No params detected :-/ **/


/** Function cff_activate_extension_license() called by wp_ajax hooks: {'cff_activate_extension_license'} **/
/** Parameters found in function cff_activate_extension_license(): {"post": ["license_key", "extension_name", "extension_item_name"]} **/
function cff_activate_extension_license()
	{
		// Security Checks
		check_ajax_referer('cff_admin_nonce', 'nonce');

		$cap = current_user_can('manage_custom_facebook_feed_options') ? 'manage_custom_facebook_feed_options' : 'manage_options';
		$cap = apply_filters('cff_settings_pages_capability', $cap);
		if (! current_user_can($cap)) {
			wp_send_json_error(); // This auto-dies.
		}

		// do the form validation to check if license_key is not empty
		if (empty($_POST[ 'license_key' ])) {
			$response = new CFF_Response(false, array(
				'message' => __('License key required!', 'custom-facebook-feed'),
			));
			$response->send();
		}
		$license_key = sanitize_text_field($_POST[ 'license_key' ]);
		$extension_name = sanitize_text_field($_POST[ 'extension_name' ]);
		$extension_item_name = sanitize_text_field($_POST[ 'extension_item_name' ]);

		// make the remote api call and get license data
		$cff_license_data = $this->get_license_data($license_key, 'activate_license', $extension_item_name);
		// update the licnese key only when the license status is activated
		update_option('cff_license_key_' . $extension_name, $license_key);
		// update the license status
		update_option('cff_license_status_' . $extension_name, $cff_license_data['license']);

		// Send ajax response back to client end
		$data = array(
			'licenseStatus' => $cff_license_data['license'],
			'licenseData' => $cff_license_data
		);
		$response = new CFF_Response(true, $data);
		$response->send();
	}


/** Function process_wizard_data() called by wp_ajax hooks: {'cff_feed_saver_manager_process_wizard'} **/
/** Parameters found in function process_wizard_data(): {"post": ["data"]} **/
function process_wizard_data()
	{
		if (! isset($_POST['data'])) {
			wp_send_json_error();
		}

		check_ajax_referer('cff-admin', 'nonce');
		$cap = current_user_can('manage_custom_facebook_feed_options') ? 'manage_custom_facebook_feed_options' : 'manage_options';
		$cap = apply_filters('cff_settings_pages_capability', $cap);
		if (! current_user_can($cap)) {
			wp_send_json_error(); // This auto-dies.
		}

		$cff_settings = get_option('cff_style_settings', array());

		$onboarding_data = sanitize_text_field(stripslashes($_POST['data']));
		$onboarding_data  = json_decode($onboarding_data, true);
		foreach ($onboarding_data as $single_data) {
			if ($single_data['type'] === 'settings') {
				if (isset($single_data['value'])) {
					if ($single_data['id'] === 'cff_locale') {
						update_option('cff_locale', $single_data['value']);
					} else {
						$cff_settings[$single_data['id']] = $single_data['value'];
					}
				} else {
					$cff_settings[$single_data['id']] = $single_data['id'] === 'cff_disable_resize' ? false : true;
				}
			}
			if ($single_data['type'] === 'install_plugins' && current_user_can('install_plugins')) {
				$plugins = explode(',', $single_data['id']);
				foreach ($plugins as $plugin_name) {
					@CFF_Onboarding_wizard::install_single_plugin($plugin_name);
				}
			}
		}
		update_option('cff_style_settings', $cff_settings);

		// Deleting Redirect Data for 3rd plugins
		$this->disable_installed_plugins_redirect();

		wp_die();
	}


/** Function create_temp_user_ajax_call() called by wp_ajax hooks: {'cff_create_temp_user'} **/
/** No params detected :-/ **/


/** Function preview() called by wp_ajax hooks: {'sb_facebookfeed_divi_preview'} **/
/** No params detected :-/ **/


/** Function cff_clear_image_resize_cache() called by wp_ajax hooks: {'cff_clear_image_resize_cache'} **/
/** No params detected :-/ **/


/** Function CustomFacebookFeed\Admin\CFF_Upgrader() called by wp_ajax hooks: {'nopriv_cff_run_one_click_upgrade', 'cff_maybe_upgrade_redirect'} **/
/** No function found :-/ **/


/** Function dismiss_critical_notice() called by wp_ajax hooks: {'cff_dismiss_critical_notice'} **/
/** No params detected :-/ **/


/** Function cff_dismiss_custom_cssjs_notice() called by wp_ajax hooks: {'cff_dismiss_custom_cssjs_notice'} **/
/** No params detected :-/ **/


/** Function cff_import_settings_json() called by wp_ajax hooks: {'cff_import_settings_json'} **/
/** Parameters found in function cff_import_settings_json(): {"files": ["file"]} **/
function cff_import_settings_json()
	{
		// Security Checks
		check_ajax_referer('cff_admin_nonce', 'nonce');

		$cap = current_user_can('manage_custom_facebook_feed_options') ? 'manage_custom_facebook_feed_options' : 'manage_options';
		$cap = apply_filters('cff_settings_pages_capability', $cap);
		if (! current_user_can($cap)) {
			wp_send_json_error(); // This auto-dies.
		}

		$filename = $_FILES['file']['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		if ('json' !== $ext) {
			$response = new CFF_Response(false, []);
			$response->send();
		}
		$imported_settings = file_get_contents($_FILES["file"]["tmp_name"]);
		// check if the file is empty
		if (empty($imported_settings)) {
			$response = new CFF_Response(false, []);
			$response->send();
		}
		$feed_return = CFF_Feed_Saver_Manager::import_feed($imported_settings);
		// check if there's error while importing
		if (! $feed_return['success']) {
			$response = new CFF_Response(false, []);
			$response->send();
		}
		// Once new feed has imported lets export all the feeds to update in front end
		$exported_feeds = CFF_Db::feeds_query();
		$feeds = array();
		foreach ($exported_feeds as $feed_id => $feed) {
			$feeds[] = array(
				'id' => $feed['id'],
				'name' => $feed['feed_name']
			);
		}

		$response = new CFF_Response(true, array(
			'feeds' => $feeds
		));
		$response->send();
	}


/** Function cff_activate_license() called by wp_ajax hooks: {'cff_activate_license'} **/
/** Parameters found in function cff_activate_license(): {"post": ["license_key"]} **/
function cff_activate_license()
	{
		// Security Checks
		check_ajax_referer('cff_admin_nonce', 'nonce');

		$cap = current_user_can('manage_custom_facebook_feed_options') ? 'manage_custom_facebook_feed_options' : 'manage_options';
		$cap = apply_filters('cff_settings_pages_capability', $cap);
		if (! current_user_can($cap)) {
			wp_send_json_error(); // This auto-dies.
		}

		// do the form validation to check if license_key is not empty
		if (empty($_POST[ 'license_key' ])) {
			$response = new CFF_Response(false, array(
				'message' => __('License key required!', 'custom-facebook-feed'),
			));
			$response->send();
		}
		$license_key = sanitize_text_field($_POST[ 'license_key' ]);
		// make the remote api call and get license data
		$cff_license_data = $this->get_license_data($license_key, 'activate_license', WPW_SL_ITEM_NAME);
		// update the license data
		if (!empty($cff_license_data)) {
			update_option('cff_license_data', $cff_license_data);
		}
		// update the licnese key only when the license status is activated
		update_option('cff_license_key', $license_key);
		// update the license status
		update_option('cff_license_status', $cff_license_data['license']);

		// Check if there is any error in the license key then handle it
		$cff_license_data = CFF_Global_Settings::get_license_error_message($cff_license_data);

		// Send ajax response back to client end
		$data = array(
			'licenseStatus' => $cff_license_data['license'],
			'licenseData' => $cff_license_data
		);
		$response = new CFF_Response(true, $data);
		$response->send();
	}


/** Function CustomFacebookFeed\Builder\CFF_Feed_Saver_Manager() called by wp_ajax hooks: {'cff_feed_saver_manager_fly_preview', 'cff_feed_saver_manager_importer', 'cff_feed_saver_manager_delete_feeds', 'cff_feed_saver_manager_builder_update', 'cff_feed_saver_manager_get_locations_page', 'cff_feed_saver_manager_duplicate_feed', 'cff_feed_saver_manager_clear_single_feed_cache', 'cff_feed_saver_manager_delete_source', 'cff_feed_saver_manager_retrieve_comments', 'cff_feed_saver_manager_get_feed_list_page', 'cff_feed_saver_manager_get_feed_settings'} **/
/** No function found :-/ **/


/** Function cff_deactivate_extension_license() called by wp_ajax hooks: {'cff_deactivate_extension_license'} **/
/** Parameters found in function cff_deactivate_extension_license(): {"post": ["extension_name", "extension_item_name"]} **/
function cff_deactivate_extension_license()
	{
		// Security Checks
		check_ajax_referer('cff_admin_nonce', 'nonce');

		$cap = current_user_can('manage_custom_facebook_feed_options') ? 'manage_custom_facebook_feed_options' : 'manage_options';
		$cap = apply_filters('cff_settings_pages_capability', $cap);
		if (! current_user_can($cap)) {
			wp_send_json_error(); // This auto-dies.
		}

		$extension_name = sanitize_text_field($_POST[ 'extension_name' ]);
		$extension_item_name = sanitize_text_field($_POST[ 'extension_item_name' ]);
		$license_key = get_option('cff_license_key_' . $extension_name);
		$license_status = get_option('cff_license_status_' . $extension_name);

		$cff_license_data = $this->get_license_data($license_key, 'deactivate_license', $extension_item_name);

		if (! $cff_license_data['success']) {
			$response = new CFF_Response(false, array());
			$response->send();
		}

		// remove the license keys and update license key status
		if ($cff_license_data['license'] == 'deactivated') {
			delete_option('cff_license_status_' . $extension_name);
			$data = array(
				'licenseStatus' => $cff_license_data['license']
			);
			$response = new CFF_Response(true, $data);
			$response->send();
		}
	}


/** Function review_notice_consent() called by wp_ajax hooks: {'cff_review_notice_consent_update'} **/
/** Parameters found in function review_notice_consent(): {"post": ["consent"]} **/
function review_notice_consent()
	{
		// Security Checks
		check_ajax_referer('cff_nonce', 'cff_nonce');
		$cap = current_user_can('manage_custom_facebook_feed_options') ? 'manage_custom_facebook_feed_options' : 'manage_options';

		$cap = apply_filters('cff_settings_pages_capability', $cap);
		if (! current_user_can($cap)) {
			wp_send_json_error(); // This auto-dies.
		}

		$consent = isset($_POST[ 'consent' ]) ? sanitize_text_field($_POST[ 'consent' ]) : '';

		update_option('cff_review_consent', $consent);

		if ($consent == 'no') {
			$cff_statuses_option = get_option('cff_statuses', array());
			update_option('cff_rating_notice', 'dismissed', false);
			$cff_statuses_option['rating_notice_dismissed'] = cff_get_current_time();
			update_option('cff_statuses', $cff_statuses_option, false);
		}
		wp_die();
	}


/** Function disable_instagram_oembed() called by wp_ajax hooks: {'disable_instagram_oembed'} **/
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


/** Function dismiss_wizard() called by wp_ajax hooks: {'cff_feed_saver_manager_dismiss_wizard'} **/
/** No params detected :-/ **/


/** Function ajax_record_event() called by wp_ajax hooks: {'cff_smash_usage_record_event'} **/
/** Parameters found in function ajax_record_event(): {"post": ["event_name"]} **/
function ajax_record_event() {
		check_ajax_referer( 'cff_smash_usage_record_event', 'nonce' );
		if ( ! current_user_can( 'manage_custom_facebook_feed_options' ) && ! current_user_can( 'manage_options' ) ) {
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


/** Function CustomFacebookFeed\Builder\CFF_Feed_Builder() called by wp_ajax hooks: {'sb_other_plugins_modal', 'cff_dismiss_onboarding'} **/
/** No function found :-/ **/


/** Function cff_oembed_disable() called by wp_ajax hooks: {'cff_oembed_disable'} **/
/** No params detected :-/ **/


/** Function CustomFacebookFeed\Builder\CFF_Source() called by wp_ajax hooks: {'cff_source_builder_update_multiple', 'cff_source_get_page', 'cff_source_builder_update', 'cff_source_get_featured_post_preview', 'cff_source_get_playlist_post_preview'} **/
/** No function found :-/ **/


/** Function cff_save_settings() called by wp_ajax hooks: {'cff_save_settings'} **/
/** Parameters found in function cff_save_settings(): {"post": ["cff_license_key", "extensions_license_key"]} **/
function cff_save_settings()
	{
		// Security Checks
		check_ajax_referer('cff_admin_nonce', 'nonce');

		$cap = current_user_can('manage_custom_facebook_feed_options') ? 'manage_custom_facebook_feed_options' : 'manage_options';
		$cap = apply_filters('cff_settings_pages_capability', $cap);
		if (! current_user_can($cap)) {
			wp_send_json_error(); // This auto-dies.
		}

		$data = $_POST;
		$model = isset($data[ 'model' ]) ? $data['model'] : null;

		// return if the model is null
		if (null === $model) {
			return;
		}

		// get the cff license key and extensions license key
		$cff_license_key = sanitize_text_field($_POST['cff_license_key']);
		$extensions_license_key = json_decode(stripslashes($_POST['extensions_license_key']));

		// Only update the cff_license_key value when it's inactive
		if (get_option('cff_license_status') == 'inactive') {
			if (empty($cff_license_key) || strlen($cff_license_key) < 1) {
				delete_option('cff_license_key');
			} else {
				update_option('cff_license_key', $cff_license_key);
			}
		}

		// Only update the extension license key when it's not activated
		if (count($extensions_license_key) > 0) {
			foreach ($extensions_license_key as $extension => $license) {
				// if license is not valid then allow to update or remove license keys
				if (! get_option('cff_license_status_' . $extension) || 'valid' != get_option('cff_license_status_' . $extension)) {
					// if license status is not valid then either delete or update
					if (empty($license) || strlen($license) < 1) {
						delete_option('cff_license_key_' . $extension);
					} else {
						update_option('cff_license_key_' . $extension, $license_key);
					}
				}
			}
		}

		$model = (array) \json_decode(\stripslashes($model));
		$general = (array) $model['general'];
		$feeds = (array) $model['feeds'];
		$translation = (array) $model['translation'];
		$advanced = (array) $model['advanced'];

		// Get the values and sanitize
		$cff_locale 							= sanitize_text_field($feeds['selectedLocale']);
		$cff_style_settings = get_option( 'cff_style_settings', array() );
		$cff_style_settings[ 'cff_timezone' ] 	= sanitize_text_field($feeds['selectedTimezone']);
		$cff_style_settings[ 'cff_custom_css' ] = $feeds['customCSS'];
		$cff_style_settings[ 'cff_custom_js' ] 	= $feeds['customJS'];
		$cff_style_settings[ 'gdpr' ] 			= sanitize_text_field($feeds['gdpr']);
		$cachingType 							= sanitize_text_field($feeds['cachingType']);
		$cronInterval 							= sanitize_text_field($feeds['cronInterval']);
		$cronTime 								= sanitize_text_field($feeds['cronTime']);
		$cronAmPm 								= sanitize_text_field($feeds['cronAmPm']);
		$cacheTime 								= sanitize_text_field($feeds['cacheTime']);
		$cacheTimeUnit 							= sanitize_text_field($feeds['cacheTimeUnit']);

		// Save general settings data
		update_option('cff_preserve_settings', $general['preserveSettings']);

		// Save feeds settings data
		update_option('cff_locale', $cff_locale);

		// Caching option for the Pro version
		if (CFF_Utils::cff_is_pro_version()) {
			update_option('cff_caching_type', $cachingType);
			update_option('cff_cache_cron_interval', $cronInterval);
			update_option('cff_cache_cron_time', $cronTime);
			update_option('cff_cache_cron_am_pm', $cronAmPm);
		}

		// Caching options for the Free version
		if (! CFF_Utils::cff_is_pro_version()) {
			// cff_caching_type should be 'page' for the free version
			update_option('cff_caching_type', 'page');
			update_option('cff_cache_time', (int) $cacheTime);
			update_option('cff_cache_time_unit', $cacheTimeUnit);
		}

		// Save translation settings data
		foreach ($translation as $key => $val) {
			$cff_style_settings[ $key ] = $val;
		}

		// Save advanced settings data
		$cff_ajax = sanitize_text_field($advanced['cff_ajax']);

		foreach ($advanced as $key => $val) {
			if ($key == 'cff_disable_resize' || $key == 'disable_admin_notice') {
				$cff_style_settings[ $key ] = !$val;
			} else {
				$cff_style_settings[ $key ] = $val;
			}
		}

		if ( isset( $advanced['usage_tracking'] ) ) {
			$tracking_enabled = (bool) $advanced['usage_tracking'];
			$usage_tracking   = get_option(
				'cff_usage_tracking',
				array(
					'enabled'   => SmashTrackingConfig::DEFAULT_ENABLED,
					'last_send' => 0,
				)
			);
			$last_send        = is_array( $usage_tracking ) && isset( $usage_tracking['last_send'] ) ? $usage_tracking['last_send'] : 0;
			update_option(
				'cff_usage_tracking',
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

		update_option('cff_ajax', $cff_ajax);

		// Update the cff_style_settings option that contains data for translation and advanced tabs
		update_option('cff_style_settings', $cff_style_settings);

		// clear cron caches
		$this->cff_clear_cache();

		$response = new CFF_Response(true, array(
			'cronNextCheck' => $this->get_cron_next_check()
		));
		$response->send();
	}


/** Function ajax_record_session() called by wp_ajax hooks: {'cff_smash_usage_record_session'} **/
/** Parameters found in function ajax_record_session(): {"post": ["duration_seconds"]} **/
function ajax_record_session() {
		check_ajax_referer( 'cff_smash_usage_record_session', 'nonce' );
		if ( ( ! current_user_can( 'manage_custom_facebook_feed_options' ) && ! current_user_can( 'manage_options' )) || ! Config::is_enabled() ) {
			wp_send_json_error();
		}
		$seconds = isset( $_POST['duration_seconds'] ) ? (int) $_POST['duration_seconds'] : 0;
		EventRecorder::record_session_duration( $seconds );
		wp_send_json_success();
	}


/** Function cff_deactivate_addon() called by wp_ajax hooks: {'cff_deactivate_addon'} **/
/** Parameters found in function cff_deactivate_addon(): {"post": ["type", "plugin"]} **/
function cff_deactivate_addon()
{

	// Run a security check.
	check_ajax_referer('cff-admin', 'nonce');

	// Check for permissions.
	if (! current_user_can('deactivate_plugins')) {
		wp_send_json_error();
	}

	$type = 'addon';
	if (! empty($_POST['type'])) {
		$type = sanitize_key(wp_unslash($_POST['type']));
	}

	if (isset($_POST['plugin'])) {
		deactivate_plugins(wp_unslash($_POST['plugin']));

		if ('plugin' === $type) {
			wp_send_json_success(esc_html__('Plugin deactivated.', 'custom-facebook-feed'));
		} else {
			wp_send_json_success(esc_html__('Addon deactivated.', 'custom-facebook-feed'));
		}
	}

	wp_send_json_error(esc_html__('Could not deactivate the addon. Please deactivate from the Plugins page.', 'custom-facebook-feed'));
}


/** Function cff_export_settings_json() called by wp_ajax hooks: {'cff_export_settings_json'} **/
/** Parameters found in function cff_export_settings_json(): {"get": ["feed_id"]} **/
function cff_export_settings_json()
	{
		// Security Checks
		if (check_ajax_referer('cff_admin_nonce', 'nonce', false) || check_ajax_referer('cff-admin', 'nonce', false)) {
			$cap = current_user_can('manage_custom_facebook_feed_options') ? 'manage_custom_facebook_feed_options' : 'manage_options';
			$cap = apply_filters('cff_settings_pages_capability', $cap);
			if (! current_user_can($cap)) {
				wp_send_json_error(); // This auto-dies.
			}

			if (! isset($_GET['feed_id'])) {
				return;
			}
			$feed_id = filter_var($_GET['feed_id'], FILTER_SANITIZE_NUMBER_INT);
			$feed = CFF_Feed_Saver_Manager::get_export_json($feed_id);
			$feed_info = CFF_Db::feeds_query(array('id' => $feed_id));
			$feed_name = strtolower($feed_info[0]['feed_name']);
			$filename = 'cff-feed-' . $feed_name . '.json';
			// create a new empty file in the php memory
			$file  = fopen('php://memory', 'w');
			fwrite($file, $feed);
			fseek($file, 0);
			header('Content-type: application/json');
			header('Content-disposition: attachment; filename = "' . $filename . '";');
			fpassthru($file);
		}
		exit;
	}


/** Function cff_test_connection() called by wp_ajax hooks: {'cff_test_connection'} **/
/** No params detected :-/ **/


/** Function dismiss() called by wp_ajax hooks: {'cff_dashboard_notification_dismiss'} **/
/** Parameters found in function dismiss(): {"get": ["cff_ignore_rating_notice_nag", "cff_nonce", "cff_ignore_new_user_sale_notice", "cff_ignore_bfcm_sale_notice", "cff_dismiss"]} **/
function dismiss()
	{
		global $current_user;
		$cap = current_user_can('manage_custom_facebook_feed_options') ? 'manage_custom_facebook_feed_options' : 'manage_options';
		$cap = apply_filters('cff_settings_pages_capability', $cap);
		if (!current_user_can($cap)) {
			return;
		}
		$user_id = $current_user->ID;
		$cff_statuses_option = get_option('cff_statuses', array());
		if (isset($_GET['cff_ignore_rating_notice_nag'])) {
			$rating_ignore = false;
			if (isset($_GET['cff_nonce']) && wp_verify_nonce($_GET['cff_nonce'], 'cff-review')) {
				$rating_ignore = isset($_GET['cff_ignore_rating_notice_nag']) ? sanitize_text_field($_GET['cff_ignore_rating_notice_nag']) : false;
			}

			if (1 === (int)$rating_ignore) {
				update_option('cff_rating_notice', 'dismissed', false);
				$cff_statuses_option['rating_notice_dismissed'] = cff_get_current_time();
				update_option('cff_statuses', $cff_statuses_option, false);
			} elseif ('later' === $rating_ignore) {
				set_transient('custom_facebook_rating_notice_waiting', 'waiting', 2 * WEEK_IN_SECONDS);
				update_option('cff_rating_notice', 'pending', false);
			}
		}

		if (isset($_GET['cff_ignore_new_user_sale_notice'])) {
			$new_user_ignore = false;
			if (isset($_GET['cff_nonce']) && wp_verify_nonce($_GET['cff_nonce'], 'cff-discount')) {
				$new_user_ignore = isset($_GET['cff_ignore_new_user_sale_notice']) ? sanitize_text_field($_GET['cff_ignore_new_user_sale_notice']) : false;
			}

			if ('always' === $new_user_ignore) {
				update_user_meta($user_id, 'cff_ignore_new_user_sale_notice', 'always');

				$current_month_number = (int)date('n', cff_get_current_time());
				$not_early_in_the_year = ($current_month_number > 5);

				if ($not_early_in_the_year) {
					update_user_meta($user_id, 'cff_ignore_bfcm_sale_notice', date('Y', cff_get_current_time()));
				}
			}
		}

		if (isset($_GET['cff_ignore_bfcm_sale_notice'])) {
			$bfcm_ignore = false;
			if (isset($_GET['cff_nonce']) && wp_verify_nonce($_GET['cff_nonce'], 'cff-bfcm')) {
				$bfcm_ignore = isset($_GET['cff_ignore_bfcm_sale_notice']) ? sanitize_text_field($_GET['cff_ignore_bfcm_sale_notice']) : false;
			}

			if ('always' === $bfcm_ignore) {
				update_user_meta($user_id, 'cff_ignore_bfcm_sale_notice', 'always');
			} elseif (date('Y', cff_get_current_time()) === $bfcm_ignore) {
				update_user_meta($user_id, 'cff_ignore_bfcm_sale_notice', date('Y', cff_get_current_time()));
			}
			update_user_meta($user_id, 'cff_ignore_new_user_sale_notice', 'always');
		}

		if (isset($_GET['cff_dismiss'])) {
			$notice_dismiss = false;
			if (isset($_GET['cff_nonce']) && wp_verify_nonce($_GET['cff_nonce'], 'cff-review')) {
				$notice_dismiss = sanitize_text_field($_GET['cff_dismiss']);
			}

			if ('review' === $notice_dismiss) {
				update_option('cff_rating_notice', 'dismissed', false);
				$cff_statuses_option['rating_notice_dismissed'] = cff_get_current_time();
				update_option('cff_statuses', $cff_statuses_option, false);
			} elseif ('discount' === $notice_dismiss) {
				update_user_meta($user_id, 'cff_ignore_new_user_sale_notice', 'always');

				$current_month_number = (int)date('n', cff_get_current_time());
				$not_early_in_the_year = ($current_month_number > 5);

				if ($not_early_in_the_year) {
					update_user_meta($user_id, 'cff_ignore_bfcm_sale_notice', date('Y', cff_get_current_time()));
				}
			}
			update_user_meta($user_id, 'cff_ignore_new_user_sale_notice', 'always');
		}
	}


/** Function oembed_connect_init() called by wp_ajax hooks: {'cff_oembed_connect_init'} **/
/** No params detected :-/ **/


/** Function delete_temp_user_ajax_call() called by wp_ajax hooks: {'cff_delete_temp_user'} **/
/** Parameters found in function delete_temp_user_ajax_call(): {"post": ["userId"]} **/
function delete_temp_user_ajax_call()
	{
		check_ajax_referer('cff-admin', 'nonce');
		$cap = current_user_can('manage_custom_facebook_feed_options') ? 'manage_custom_facebook_feed_options' : 'manage_options';
		$cap = apply_filters('cff_settings_pages_capability', $cap);
		if (!current_user_can($cap)) {
			wp_send_json_error(); // This auto-dies.
		}

		if (!isset($_POST['userId'])) {
			wp_send_json_error();
		}

		$user_id = sanitize_key($_POST['userId']);
		$return = CFF_Support_Tool::delete_temporary_user($user_id);
		echo wp_json_encode($return);
		wp_die();
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


/** Function cff_dpa_reset() called by wp_ajax hooks: {'cff_dpa_reset'} **/
/** No params detected :-/ **/


/** Function disable_facebook_oembed() called by wp_ajax hooks: {'disable_facebook_oembed'} **/
/** No params detected :-/ **/


/** Function cff_lite_dismiss() called by wp_ajax hooks: {'cff_lite_dismiss'} **/
/** No params detected :-/ **/


/** Function cff_activate_addon() called by wp_ajax hooks: {'cff_activate_addon'} **/
/** Parameters found in function cff_activate_addon(): {"post": ["plugin", "type"]} **/
function cff_activate_addon()
{

	// Run a security check.
	check_ajax_referer('cff-admin', 'nonce');

	// Check for permissions.
	if (! current_user_can('activate_plugins')) {
		wp_send_json_error();
	}

	if (isset($_POST['plugin'])) {
		$type = 'addon';
		if (! empty($_POST['type'])) {
			$type = sanitize_key(wp_unslash($_POST['type']));
		}

		$activate = activate_plugins($_POST['plugin']);

		if (! is_wp_error($activate)) {
			if ('plugin' === $type) {
				wp_send_json_success(esc_html__('Plugin activated.', 'custom-facebook-feed'));
			} else {
				wp_send_json_success(esc_html__('Addon activated.', 'custom-facebook-feed'));
			}
		}
	}

	wp_send_json_error(esc_html__('Could not activate addon. Please activate from the Plugins page.', 'custom-facebook-feed'));
}


/** Function cff_clear_cache() called by wp_ajax hooks: {'cff_clear_cache'} **/
/** Parameters found in function cff_clear_cache(): {"post": ["model"]} **/
function cff_clear_cache()
	{
		// Security Checks
		check_ajax_referer('cff_admin_nonce', 'nonce');

		$cap = current_user_can('manage_custom_facebook_feed_options') ? 'manage_custom_facebook_feed_options' : 'manage_options';
		$cap = apply_filters('cff_settings_pages_capability', $cap);
		if (! current_user_can($cap)) {
			wp_send_json_error(); // This auto-dies.
		}


		// Get the settings model data
		$model = isset($_POST[ 'model' ]) ? $_POST['model'] : null;

		// Caching clear option for the free version
		if (!CFF_Utils::cff_is_pro_version()) {
			if ($model !== null) {
				$model = (array) \json_decode(\stripslashes($model));
				$feeds = (array) $model['feeds'];
			}

			$cff_cache_time = sanitize_text_field($feeds['cacheTime']);
			$cff_cache_time_unit = sanitize_text_field($feeds['cacheTimeUnit']);

			update_option('cff_cache_time', (int) $cff_cache_time);
			update_option('cff_cache_time_unit', $cff_cache_time_unit);

			// Clear the existing cron event
			wp_clear_scheduled_hook('cff_cron_job');

			// Set the event schedule based on what the caching time is set to
			$cff_cron_schedule = 'hourly';
			if ($cff_cache_time_unit == 'hours' && $cff_cache_time > 5) {
				$cff_cron_schedule = 'twicedaily';
			}
			if ($cff_cache_time_unit == 'days') {
				$cff_cron_schedule = 'daily';
			}

			wp_schedule_event(time(), $cff_cron_schedule, 'cff_cron_job');

			$this->clear_stored_caches();

			$response = new CFF_Response(true, array());
			$response->send();
		}

		// Get the updated cron schedule interval and time settings from user input and update the database
		if ($model !== null) {
			$model = (array) \json_decode(\stripslashes($model));
			$feeds = (array) $model['feeds'];
			update_option('cff_cache_cron_interval', sanitize_text_field($feeds['cronInterval']));
			update_option('cff_cache_cron_time', sanitize_text_field($feeds['cronTime']));
			update_option('cff_cache_cron_am_pm', sanitize_text_field($feeds['cronAmPm']));
		}

		// Now get the updated cron schedule interval and time values
		$cff_cache_cron_interval_val = get_option('cff_cache_cron_interval', '12hours');
		$cff_cache_cron_time_val = get_option('cff_cache_cron_time', '1');
		$cff_cache_cron_am_pm_val = get_option('cff_cache_cron_am_pm', 'am');

		// Default Timezone
		$defaults = array(
			'cff_timezone' => 'America/Chicago',
			'cff_load_more' => true,
			'cff_num_mobile' => ''
		);
		$style_options = get_option('cff_style_settings', $defaults);
		$cff_timezone = $style_options[ 'cff_timezone' ];

		// Clear the stored caches in the database
		$this->clear_stored_caches();

		// Clear the existing cron event
		wp_clear_scheduled_hook('cff_cache_cron');
		switch ($cff_cache_cron_interval_val) {
			case "30mins":
				$cff_cron_schedule = '30mins';
				break;
			case "1hour":
				$cff_cron_schedule = 'hourly';
				break;
			case "12hours":
				$cff_cron_schedule = 'twicedaily';
				break;
			default:
				$cff_cron_schedule = 'daily';
		}

		// If the 30mins or 1hour are selected then use the current time and set it to start at the next 30mins/hour
		$cff_cache_cron_time_unix = strtotime($cff_cache_cron_time_val . $cff_cache_cron_am_pm_val . ' ' . $cff_timezone);
		if ($cff_cache_cron_interval_val == '30mins' || $cff_cache_cron_interval_val == '1hour') {
			$cff_cache_cron_time_unix = time();
		}

		CFF_Group_Posts::group_reschedule_event($cff_cache_cron_time_unix, $cff_cron_schedule);
		wp_schedule_event($cff_cache_cron_time_unix, $cff_cron_schedule, 'cff_cache_cron');

		$response = new CFF_Response(true, array(
			'cronNextCheck' => $this->get_cron_next_check()
		));
		$response->send();
	}


/** Function cff_ppca_token_check_flag() called by wp_ajax hooks: {'cff_ppca_token_check_flag'} **/
/** No params detected :-/ **/


/** Function cff_deactivate_license() called by wp_ajax hooks: {'cff_deactivate_license'} **/
/** No params detected :-/ **/


