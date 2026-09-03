<?php
/***
*
*Found actions: 62
*Found functions:41
*Extracted functions:37
*Total parameter names extracted: 21
*Overview: {'sbi_deactivate_addon': {'sbi_deactivate_addon'}, 'delete_temp_user_ajax_call': {'sbi_delete_temp_user'}, 'sbi_retry_db': {'sbi_retry_db'}, 'dismiss_upgrade_notice': {'sbi_dismiss_upgrade_notice'}, 'sbi_clear_cache': {'sbi_clear_cache'}, 'InstagramFeed\\Builder\\SBI_Feed_Builder': {'sbi_dismiss_onboarding', 'sbi_other_plugins_modal'}, 'handle_unused_feed_usage': {'sbi_reset_unused_feed_usage'}, 'sbi_import_settings_json': {'sbi_import_settings_json'}, 'sbi_process_submitted_resize_ids': {'sbi_resized_images_submit', 'nopriv_sbi_resized_images_submit'}, 'sbi_get_next_post_set': {'sbi_load_more_clicked', 'nopriv_sbi_load_more_clicked'}, 'sbi_activate_license': {'sbi_activate_license'}, 'review_notice_consent': {'sbi_review_notice_consent_update'}, 'sbi_check_license': {'sbi_check_license'}, 'process_wizard_data': {'sbi_feed_saver_manager_process_wizard'}, 'InstagramFeed\\Builder\\SBI_Source': {'sbi_source_builder_update', 'sbi_source_get_page', 'sbi_source_builder_update_multiple'}, 'InstagramFeed\\Builder\\SBI_Feed_Saver_Manager': {'sbi_feed_saver_manager_builder_update', 'sbi_feed_saver_manager_retrieve_comments', 'sbi_feed_saver_manager_clear_single_feed_cache', 'sbi_feed_saver_manager_duplicate_feed', 'sbi_feed_saver_manager_recache_feed', 'sbi_feed_saver_manager_get_locations_page', 'sbi_feed_saver_manager_fly_preview', 'sbi_feed_saver_manager_importer', 'sbi_feed_saver_manager_get_feed_list_page', 'sbi_feed_saver_manager_delete_source', 'sbi_update_personal_account', 'sbi_feed_saver_manager_get_feed_settings', 'sbi_feed_saver_manager_clear_comments_cache', 'sbi_feed_saver_manager_delete_feeds'}, 'sbi_reset_log': {'sbi_reset_log'}, 'sbi_activate_addon': {'sbi_activate_addon'}, 'InstagramFeed\\Admin\\SBI_Upgrader': {'nopriv_sbi_run_one_click_upgrade', 'sbi_maybe_upgrade_redirect'}, 'sbi_recheck_connection': {'sbi_recheck_connection'}, 'dismiss_wizard': {'sbi_feed_saver_manager_dismiss_wizard'}, 'sbi_deactivate_license': {'sbi_deactivate_license'}, 'sbi_dpa_reset': {'sbi_dpa_reset'}, 'disable_instagram_oembed_from_instagram': {'disable_instagram_oembed_from_instagram'}, 'sbi_clear_image_resize_cache': {'sbi_clear_image_resize_cache'}, 'disable_facebook_oembed_from_instagram': {'disable_facebook_oembed_from_instagram'}, 'create_temp_user_ajax_call': {'sbi_create_temp_user'}, 'sbi_save_settings': {'sbi_save_settings'}, 'sbi_test_connection': {'sbi_test_connection'}, 'sbi_export_settings_json': {'sbi_export_settings_json'}, 'sbi_clear_error_log': {'sbi_clear_error_log'}, 'get_api_calls_handler': {'sbi_get_api_calls_handler'}, 'dismiss': {'sbi_dashboard_notification_dismiss'}, 'preview': {'sb_instagramfeed_divi_preview'}, 'install_plugin': {'am_recommended_block_install'}, 'sbi_do_locator': {'sbi_do_locator', 'nopriv_sbi_do_locator'}, 'ajax_record_session': {'sbi_smash_usage_record_session'}, 'ajax_record_event': {'sbi_smash_usage_record_event'}, 'handle_ajax': {'sb_feature_suggestion', 'sb_deactivation_feedback'}, 'sbi_install_addon': {'sbi_install_addon'}, 'sbi_dismiss_critical_notice': {'sbi_dismiss_critical_notice'}}
*
***/

/** Function sbi_deactivate_addon() called by wp_ajax hooks: {'sbi_deactivate_addon'} **/
/** Parameters found in function sbi_deactivate_addon(): {"post": ["type", "plugin"]} **/
function sbi_deactivate_addon()
{

	// Run a security check.
	check_ajax_referer('sbi-admin', 'nonce');

	// Check for permissions.
	if (!current_user_can('activate_plugins')) {
		wp_send_json_error();
	}

	$type = 'addon';
	if (!empty($_POST['type'])) {
		$type = sanitize_key($_POST['type']);
	}

	if (isset($_POST['plugin'])) {
		deactivate_plugins(preg_replace('/[^a-z-_\/]/', '', wp_unslash(str_replace('.php', '', $_POST['plugin']))) . '.php');

		if ('plugin' === $type) {
			wp_send_json_success(esc_html__('Plugin deactivated.', 'instagram-feed'));
		} else {
			wp_send_json_success(esc_html__('Addon deactivated.', 'instagram-feed'));
		}
	}

	wp_send_json_error(esc_html__('Could not deactivate the addon. Please deactivate from the Plugins page.', 'instagram-feed'));
}


/** Function delete_temp_user_ajax_call() called by wp_ajax hooks: {'sbi_delete_temp_user'} **/
/** Parameters found in function delete_temp_user_ajax_call(): {"post": ["userId"]} **/
function delete_temp_user_ajax_call()
	{
		check_ajax_referer('sbi-admin', 'nonce');
		if (!sbi_current_user_can('manage_instagram_feed_options')) {
			wp_send_json_error();
		}

		if (!isset($_POST['userId'])) {
			wp_send_json_error();
		}

		$user_id = absint($_POST['userId']);
		$return = self::delete_temporary_user($user_id);
		echo wp_json_encode($return);
		wp_die();
	}


/** Function sbi_retry_db() called by wp_ajax hooks: {'sbi_retry_db'} **/
/** No params detected :-/ **/


/** Function dismiss_upgrade_notice() called by wp_ajax hooks: {'sbi_dismiss_upgrade_notice'} **/
/** No params detected :-/ **/


/** Function sbi_clear_cache() called by wp_ajax hooks: {'sbi_clear_cache'} **/
/** Parameters found in function sbi_clear_cache(): {"post": ["model"]} **/
function sbi_clear_cache()
	{
		check_ajax_referer('sbi-admin', 'nonce');

		if (!sbi_current_user_can('manage_instagram_feed_options')) {
			wp_send_json_error();
		}

		// Get the updated cron schedule interval and time settings from user input and update the database
		$model = isset($_POST['model']) ? sanitize_text_field($_POST['model']) : null;
		if ($model !== null) {
			$model = (array)json_decode(stripslashes($model));
			$feeds = (array)$model['feeds'];
		}

		// Now get the updated cron schedule interval and time values
		$sbi_settings = get_option('sb_instagram_settings', array());

		$sbi_cache_cron_interval = $sbi_settings['sbi_cache_cron_interval'];
		$sbi_cache_cron_time = $sbi_settings['sbi_cache_cron_time'];
		$sbi_cache_cron_am_pm = $sbi_settings['sbi_cache_cron_am_pm'];

		// Clear the stored caches in the database
		$this->clear_stored_caches();

		delete_option('sbi_cron_report');
		SB_Instagram_Cron_Updater::start_cron_job($sbi_cache_cron_interval, $sbi_cache_cron_time, $sbi_cache_cron_am_pm);

		global $sb_instagram_posts_manager;
		$sb_instagram_posts_manager->add_action_log('Saved settings on the configure tab.');
		$sb_instagram_posts_manager->clear_api_request_delays();

		$response = new SBI_Response(true, array(
			'cronNextCheck' => $this->get_cron_next_check()
		));
		$response->send();
	}


/** Function InstagramFeed\Builder\SBI_Feed_Builder() called by wp_ajax hooks: {'sbi_dismiss_onboarding', 'sbi_other_plugins_modal'} **/
/** No function found :-/ **/


/** Function handle_unused_feed_usage() called by wp_ajax hooks: {'sbi_reset_unused_feed_usage'} **/
/** No params detected :-/ **/


/** Function sbi_import_settings_json() called by wp_ajax hooks: {'sbi_import_settings_json'} **/
/** Parameters found in function sbi_import_settings_json(): {"files": ["file"]} **/
function sbi_import_settings_json()
	{
		check_ajax_referer('sbi-admin', 'nonce');

		if (!sbi_current_user_can('manage_instagram_feed_options')) {
			wp_send_json_error();
		}

		$filename = $_FILES['file']['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		if ('json' !== $ext) {
			$response = new SBI_Response(false, []);
			$response->send();
		}
		$imported_settings = file_get_contents($_FILES["file"]["tmp_name"]);
		// check if the file is empty
		if (empty($imported_settings)) {
			$response = new SBI_Response(false, []);
			$response->send();
		}
		$feed_return = SBI_Feed_Saver_Manager::import_feed($imported_settings);
		// check if there's error while importing
		if (!$feed_return['success']) {
			$response = new SBI_Response(false, []);
			$response->send();
		}
		// Once new feed has imported lets export all the feeds to update in front end
		$exported_feeds = SBI_Db::feeds_query();
		$feeds = array();
		foreach ($exported_feeds as $feed_id => $feed) {
			$feeds[] = array(
				'id' => $feed['id'],
				'name' => $feed['feed_name']
			);
		}

		$response = new SBI_Response(true, array(
			'feeds' => $feeds
		));
		$response->send();
	}


/** Function sbi_process_submitted_resize_ids() called by wp_ajax hooks: {'sbi_resized_images_submit', 'nopriv_sbi_resized_images_submit'} **/
/** Parameters found in function sbi_process_submitted_resize_ids(): {"post": ["feed_id", "post_id", "atts", "locator_nonce", "needs_resizing", "offset", "cache_all", "location"]} **/
function sbi_process_submitted_resize_ids()
{
	if (empty($_POST['feed_id']) || !preg_match('/^(sbi|\\*)/', $_POST['feed_id'])) {
		wp_send_json_error('invalid feed ID');
	}

	$feed_id = sanitize_text_field($_POST['feed_id']);
	$post_id = isset($_POST['post_id']) && $_POST['post_id'] !== 'unknown' ? intval($_POST['post_id']) : 'unknown';

	$atts_raw = isset($_POST['atts']) ? json_decode(wp_unslash($_POST['atts']), true) : array();
	$atts = is_array($atts_raw) ? SB_Instagram_Settings::sanitize_raw_atts($atts_raw) : array();

	$database_settings = sbi_get_database_settings();
	$instagram_feed_settings = new SB_Instagram_Settings($atts, $database_settings);

	$instagram_feed_settings->set_feed_type_and_terms();
	$instagram_feed_settings->set_transient_name();
	$transient_name = $instagram_feed_settings->get_transient_name();
	$settings = $instagram_feed_settings->get_settings();

	$nonce = isset($_POST['locator_nonce']) ? sanitize_text_field(wp_unslash($_POST['locator_nonce'])) : '';
	if (!wp_verify_nonce($nonce, 'sbi-locator-nonce-' . $post_id . '-' . $transient_name) || $transient_name !== $feed_id) {
		wp_send_json_error('nonce check failed, details do not match');
	}

	$images_need_resizing_raw = isset($_POST['needs_resizing']) ? $_POST['needs_resizing'] : array();
	$images_need_resizing = is_array($images_need_resizing_raw) ? array_map('sbi_sanitize_instagram_ids', $images_need_resizing_raw) : array();

	$offset = isset($_POST['offset']) ? (int)$_POST['offset'] : 0;
	$cache_all = isset($_POST['cache_all']) && $_POST['cache_all'] === 'true';
	if ($cache_all) {
		$settings['cache_all'] = true;
	}


	$location = isset($_POST['location']) && in_array($_POST['location'], array('header', 'footer', 'sidebar', 'content'), true) ? sanitize_text_field($_POST['location']) : 'unknown';
	$feed_details = array(
		'feed_id' => $transient_name,
		'atts' => $atts,
		'location' => array(
			'post_id' => $post_id,
			'html' => $location
		)
	);

	sbi_do_background_tasks($feed_details);
	sbi_resize_posts_by_id($images_need_resizing, $transient_name, $settings);
	sbi_delete_image_cache($transient_name);

	global $sb_instagram_posts_manager;
	if (!$sb_instagram_posts_manager->image_resizing_disabled($transient_name)) {
		$num = $settings['minnum'] * 2 + 5;
		wp_send_json_success(SB_Instagram_Feed::get_resized_images_source_set($num, $offset - (int)$settings['minnum'], $feed_id, false));
	}

	wp_send_json_success('resizing success');
}


/** Function sbi_get_next_post_set() called by wp_ajax hooks: {'sbi_load_more_clicked', 'nopriv_sbi_load_more_clicked'} **/
/** Parameters found in function sbi_get_next_post_set(): {"post": ["feed_id", "post_id", "atts", "locator_nonce", "location", "offset", "page"]} **/
function sbi_get_next_post_set()
{
	if (empty($_POST['feed_id']) || !preg_match('/^(sbi|\*)/', $_POST['feed_id'])) {
		wp_send_json_error('invalid feed ID');
	}

	$feed_id = sanitize_text_field(wp_unslash($_POST['feed_id']));
	$post_id = isset($_POST['post_id']) && $_POST['post_id'] !== 'unknown' ? intval($_POST['post_id']) : 'unknown';

	$atts_raw = isset($_POST['atts']) ? json_decode(stripslashes($_POST['atts']), true) : array();
	$atts = is_array($atts_raw) ? SB_Instagram_Settings::sanitize_raw_atts($atts_raw) : array();

	$database_settings = sbi_get_database_settings();
	$instagram_feed_settings = new SB_Instagram_Settings($atts, $database_settings);

	$instagram_feed_settings->set_feed_type_and_terms();
	$instagram_feed_settings->set_transient_name();
	$transient_name = $instagram_feed_settings->get_transient_name();

	$nonce = isset($_POST['locator_nonce']) ? sanitize_text_field(wp_unslash($_POST['locator_nonce'])) : '';
	if (!wp_verify_nonce($nonce, 'sbi-locator-nonce-' . $post_id . '-' . $transient_name) || $transient_name !== $feed_id) {
		wp_send_json_error('nonce check failed, details do not match');
	}

	$location = isset($_POST['location']) && in_array($_POST['location'], array('header', 'footer', 'sidebar', 'content'), true) ? sanitize_text_field(wp_unslash($_POST['location'])) : 'unknown';
	$feed_details = array(
		'feed_id' => $transient_name,
		'atts' => $atts,
		'location' => array(
			'post_id' => $post_id,
			'html' => $location
		)
	);

	sbi_do_background_tasks($feed_details);

	$settings = $instagram_feed_settings->get_settings();
	$offset = isset($_POST['offset']) ? (int)$_POST['offset'] : 0;
	$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;

	$feed_type_and_terms = $instagram_feed_settings->get_feed_type_and_terms();

	$instagram_feed = new SB_Instagram_Feed($transient_name);
	$instagram_feed->set_cache($instagram_feed_settings->get_cache_time_in_seconds(), $settings);

	if ($settings['caching_type'] === 'background') {
		$instagram_feed->add_report('background caching used');
		if ($instagram_feed->regular_cache_exists()) {
			$instagram_feed->add_report('setting posts from cache');
			$instagram_feed->set_post_data_from_cache();
		}

		if ($instagram_feed->need_posts($settings['minnum'], $offset, $page) && $instagram_feed->can_get_more_posts()) {
			while ($instagram_feed->need_posts($settings['minnum'], $offset, $page) && $instagram_feed->can_get_more_posts()) {
				$instagram_feed->add_remote_posts($settings, $feed_type_and_terms, $instagram_feed_settings->get_connected_accounts_in_feed());
			}

			$normal_method = true;
			if ($instagram_feed->need_to_start_cron_job()) {
				$instagram_feed->add_report('needed to start cron job');
				$to_cache = array(
					'atts' => $atts,
					'last_requested' => time(),
				);
				$normal_method = false;
			} else {
				$instagram_feed->add_report('updating last requested and adding to cache');
				$to_cache = array(
					'last_requested' => time(),
				);
			}

			if ($normal_method) {
				$instagram_feed->set_cron_cache($to_cache, $instagram_feed_settings->get_cache_time_in_seconds(), $settings['backup_cache_enabled']);
			} else {
				$instagram_feed->set_cron_cache($to_cache, $instagram_feed_settings->get_cache_time_in_seconds());
			}
		}
	} elseif ($instagram_feed->regular_cache_exists()) {
		$instagram_feed->add_report('regular cache exists');
		$instagram_feed->set_post_data_from_cache();

		if ($instagram_feed->need_posts($settings['minnum'], $offset, $page) && $instagram_feed->can_get_more_posts()) {
			while ($instagram_feed->need_posts($settings['minnum'], $offset, $page) && $instagram_feed->can_get_more_posts()) {
				$instagram_feed->add_remote_posts($settings, $feed_type_and_terms, $instagram_feed_settings->get_connected_accounts_in_feed());
			}

			$instagram_feed->add_report('adding to cache');
			$instagram_feed->cache_feed_data($instagram_feed_settings->get_cache_time_in_seconds(), $settings['backup_cache_enabled']);
		}
	} else {
		$instagram_feed->add_report('no feed cache found');

		while ($instagram_feed->need_posts($settings['num'], $offset) && $instagram_feed->can_get_more_posts()) {
			$instagram_feed->add_remote_posts($settings, $feed_type_and_terms, $instagram_feed_settings->get_connected_accounts_in_feed());
		}

		if ($instagram_feed->should_use_backup()) {
			$instagram_feed->add_report('trying to use a backup cache');
			$instagram_feed->maybe_set_post_data_from_backup();
		} else {
			$instagram_feed->add_report('transient gone, adding to cache');
			$instagram_feed->cache_feed_data($instagram_feed_settings->get_cache_time_in_seconds(), $settings['backup_cache_enabled']);
		}
	}

	if ($settings['disable_js_image_loading'] || $settings['imageres'] !== 'auto') {
		global $sb_instagram_posts_manager;
		$post_data = array_slice($instagram_feed->get_post_data(), $offset, $settings['minnum']);

		if (!$sb_instagram_posts_manager->image_resizing_disabled()) {
			$image_ids = array();
			foreach ($post_data as $post) {
				$image_ids[] = SB_Instagram_Parse::get_post_id($post);
			}
			$resized_images = SB_Instagram_Feed::get_resized_images_source_set($image_ids, 0, $feed_id);

			$instagram_feed->set_resized_images($resized_images);
		}
	}

	$feed_status = array('shouldPaginate' => $instagram_feed->should_use_pagination($settings, $offset));

	$return = array(
		'html' => $instagram_feed->get_the_items_html($settings, $offset, $instagram_feed_settings->get_feed_type_and_terms(), $instagram_feed_settings->get_connected_accounts_in_feed()),
		'feedStatus' => $feed_status,
		'report' => $instagram_feed->get_report(),
		'resizedImages' => SB_Instagram_Feed::get_resized_images_source_set($instagram_feed->get_image_ids_post_set(), 1, $feed_id)
	);

	wp_send_json_success($return);
}


/** Function sbi_activate_license() called by wp_ajax hooks: {'sbi_activate_license'} **/
/** Parameters found in function sbi_activate_license(): {"post": ["license_key"]} **/
function sbi_activate_license()
	{
		check_ajax_referer('sbi-admin', 'nonce');

		if (!sbi_current_user_can('manage_instagram_feed_options')) {
			wp_send_json_error();
		}
		// do the form validation to check if license_key is not empty
		if (empty($_POST['license_key'])) {
			$response = new SBI_Response(false, array(
				'message' => __('License key required!', 'instagram-feed'),
			));
			$response->send();
		}
		$license_key = sanitize_key($_POST['license_key']);
		// make the remote api call and get license data
		$sbi_license_data = $this->get_license_data($license_key, 'activate_license', SBI_PLUGIN_NAME);

		// update the license data
		if (!empty($sbi_license_data)) {
			update_option('sbi_license_data', $sbi_license_data);
		}
		// update the licnese key only when the license status is activated
		update_option('sbi_license_key', $license_key);
		// update the license status
		update_option('sbi_license_status', $sbi_license_data['license']);

		// Check if there is any error in the license key then handle it
		$sbi_license_data = $this->get_license_error_message($sbi_license_data);

		// Send ajax response back to client end
		$data = array(
			'licenseStatus' => $sbi_license_data['license'],
			'licenseData' => $sbi_license_data
		);
		$response = new SBI_Response(true, $data);
		$response->send();
	}


/** Function review_notice_consent() called by wp_ajax hooks: {'sbi_review_notice_consent_update'} **/
/** Parameters found in function review_notice_consent(): {"post": ["consent"]} **/
function review_notice_consent()
	{
		// Security Checks
		check_ajax_referer('sbi_nonce', 'sbi_nonce');
		$cap = current_user_can('manage_instagram_feed_options') ? 'manage_instagram_feed_options' : 'manage_options';

		$cap = apply_filters('sbi_settings_pages_capability', $cap);
		if (!current_user_can($cap)) {
			wp_send_json_error(); // This auto-dies.
		}

		$consent = isset($_POST['consent']) ? sanitize_text_field($_POST['consent']) : '';

		update_option('sbi_review_consent', $consent);

		if ($consent == 'no') {
			$sbi_statuses_option = get_option('sbi_statuses', array());
			update_option('sbi_rating_notice', 'dismissed', false);
			$sbi_statuses_option['rating_notice_dismissed'] = sbi_get_current_time();
			update_option('sbi_statuses', $sbi_statuses_option, false);

			// remove the rating notice step 1 and step 2 from global notices.
			global $sbi_notices;
			$sbi_notices->remove_notice('review_step_1');
			$sbi_notices->remove_notice('review_step_2');
			$sbi_notices->remove_notice('review_step_1_all_pages');
			$sbi_notices->remove_notice('review_step_2_all_pages');
		} elseif ($consent == 'yes') {
			global $sbi_notices;
			$sbi_notices->remove_notice('review_step_1');
			$sbi_notices->remove_notice('review_step_1_all_pages');
		}
		wp_die();
	}


/** Function sbi_check_license() called by wp_ajax hooks: {'sbi_check_license'} **/
/** No params detected :-/ **/


/** Function process_wizard_data() called by wp_ajax hooks: {'sbi_feed_saver_manager_process_wizard'} **/
/** Parameters found in function process_wizard_data(): {"post": ["data"]} **/
function process_wizard_data()
	{
		if (!isset($_POST['data'])) {
			wp_send_json_error();
		}

		check_ajax_referer('sbi-admin', 'nonce');
		if (!sbi_current_user_can('manage_instagram_feed_options')) {
			wp_send_json_error();
		}

		$sbi_settings = get_option('sb_instagram_settings', array());

		$onboarding_data = sanitize_text_field(stripslashes($_POST['data']));
		$onboarding_data = json_decode($onboarding_data, true);
		foreach ($onboarding_data as $single_data) {
			if ($single_data['type'] === 'settings') {
				$sbi_settings[$single_data['id']] = $single_data['id'] === 'sb_instagram_disable_resize' ? false : true;
			}
			if ($single_data['type'] === 'install_plugins' && current_user_can('install_plugins')) {
				$plugins = explode(',', $single_data['id']);
				// Deleting Redirect Data for 3rd plugins
				// $this->disable_installed_plugins_redirect();
				foreach ($plugins as $plugin_name) {
					@SBI_Onboarding_wizard::install_single_plugin($plugin_name);
					$this->disable_installed_plugins_redirect();
				}
			}
		}
		update_option('sb_instagram_settings', $sbi_settings);


		wp_die();
	}


/** Function InstagramFeed\Builder\SBI_Source() called by wp_ajax hooks: {'sbi_source_builder_update', 'sbi_source_get_page', 'sbi_source_builder_update_multiple'} **/
/** No function found :-/ **/


/** Function InstagramFeed\Builder\SBI_Feed_Saver_Manager() called by wp_ajax hooks: {'sbi_feed_saver_manager_builder_update', 'sbi_feed_saver_manager_retrieve_comments', 'sbi_feed_saver_manager_clear_single_feed_cache', 'sbi_feed_saver_manager_duplicate_feed', 'sbi_feed_saver_manager_recache_feed', 'sbi_feed_saver_manager_get_locations_page', 'sbi_feed_saver_manager_fly_preview', 'sbi_feed_saver_manager_importer', 'sbi_feed_saver_manager_get_feed_list_page', 'sbi_feed_saver_manager_delete_source', 'sbi_update_personal_account', 'sbi_feed_saver_manager_get_feed_settings', 'sbi_feed_saver_manager_clear_comments_cache', 'sbi_feed_saver_manager_delete_feeds'} **/
/** No function found :-/ **/


/** Function sbi_reset_log() called by wp_ajax hooks: {'sbi_reset_log'} **/
/** No params detected :-/ **/


/** Function sbi_activate_addon() called by wp_ajax hooks: {'sbi_activate_addon'} **/
/** Parameters found in function sbi_activate_addon(): {"post": ["plugin", "type"]} **/
function sbi_activate_addon()
{
	// Run a security check.
	check_ajax_referer('sbi-admin', 'nonce');

	// Check for permissions.
	if (!current_user_can('activate_plugins')) {
		wp_send_json_error();
	}

	if (isset($_POST['plugin'])) {
		$type = 'addon';
		if (!empty($_POST['type'])) {
			$type = sanitize_key($_POST['type']);
		}

		$activate = activate_plugins(preg_replace('/[^a-z-_\/]/', '', wp_unslash(str_replace('.php', '', $_POST['plugin']))) . '.php');

		if (!is_wp_error($activate)) {
			if ('plugin' === $type) {
				wp_send_json_success(esc_html__('Plugin activated.', 'instagram-feed'));
			} else {
				wp_send_json_success(esc_html__('Addon activated.', 'instagram-feed'));
			}
		}
	}

	wp_send_json_error(esc_html__('Could not activate addon. Please activate from the Plugins page.', 'instagram-feed'));
}


/** Function InstagramFeed\Admin\SBI_Upgrader() called by wp_ajax hooks: {'nopriv_sbi_run_one_click_upgrade', 'sbi_maybe_upgrade_redirect'} **/
/** No function found :-/ **/


/** Function sbi_recheck_connection() called by wp_ajax hooks: {'sbi_recheck_connection'} **/
/** Parameters found in function sbi_recheck_connection(): {"post": ["license_key", "item_name", "option_name"]} **/
function sbi_recheck_connection()
	{
		check_ajax_referer('sbi-admin', 'nonce');

		if (!sbi_current_user_can('manage_instagram_feed_options')) {
			wp_send_json_error();
		}
		// Do the form validation
		$license_key = isset($_POST['license_key']) ? sanitize_key($_POST['license_key']) : '';
		$item_name = isset($_POST['item_name']) ? sanitize_text_field($_POST['item_name']) : '';
		$option_name = isset($_POST['option_name']) ? sanitize_text_field($_POST['option_name']) : '';
		if (empty($license_key) || empty($item_name)) {
			$response = new SBI_Response(false, array());
			$response->send();
		}

		// make the remote license check API call
		$sbi_license_data = $this->get_license_data($license_key, 'check_license', $item_name);

		// update options data
		$license_changed = $this->update_recheck_license_data($sbi_license_data, $item_name, $option_name);

		// send AJAX response back
		$response = new SBI_Response(true, array(
			'license' => $sbi_license_data['license'],
			'licenseChanged' => $license_changed
		));
		$response->send();
	}


/** Function dismiss_wizard() called by wp_ajax hooks: {'sbi_feed_saver_manager_dismiss_wizard'} **/
/** No params detected :-/ **/


/** Function sbi_deactivate_license() called by wp_ajax hooks: {'sbi_deactivate_license'} **/
/** No params detected :-/ **/


/** Function sbi_dpa_reset() called by wp_ajax hooks: {'sbi_dpa_reset'} **/
/** No params detected :-/ **/


/** Function disable_instagram_oembed_from_instagram() called by wp_ajax hooks: {'disable_instagram_oembed_from_instagram'} **/
/** No params detected :-/ **/


/** Function sbi_clear_image_resize_cache() called by wp_ajax hooks: {'sbi_clear_image_resize_cache'} **/
/** No params detected :-/ **/


/** Function disable_facebook_oembed_from_instagram() called by wp_ajax hooks: {'disable_facebook_oembed_from_instagram'} **/
/** No params detected :-/ **/


/** Function create_temp_user_ajax_call() called by wp_ajax hooks: {'sbi_create_temp_user'} **/
/** No params detected :-/ **/


/** Function sbi_save_settings() called by wp_ajax hooks: {'sbi_save_settings'} **/
/** Parameters found in function sbi_save_settings(): {"post": ["sbi_license_key"]} **/
function sbi_save_settings()
	{
		check_ajax_referer('sbi-admin', 'nonce');

		if (!sbi_current_user_can('manage_instagram_feed_options')) {
			wp_send_json_error();
		}
		$data = $_POST;
		$model = isset($data['model']) ? $data['model'] : null;

		// return if the model is null
		if (null === $model) {
			return;
		}

		// get the sbi license key and extensions license key
		$sbi_license_key = sanitize_text_field($_POST['sbi_license_key']);

		// Only update the sbi_license_key value when it's inactive
		if (get_option('sbi_license_status') == 'inactive') {
			if (empty($sbi_license_key) || strlen($sbi_license_key) < 1) {
				delete_option('sbi_license_key');
				delete_option('sbi_license_data');
				delete_option('sbi_license_status');
			} else {
				update_option('sbi_license_key', $sbi_license_key);
			}
		} else {
			$license_key = sanitize_key(trim(get_option('sbi_license_key', '')));

			if (empty($sbi_license_key) && !empty($license_key)) {
				$sbi_license_data = $this->get_license_data($license_key, 'deactivate_license', SBI_PLUGIN_NAME);

				delete_option('sbi_license_key');
				delete_option('sbi_license_data');
				delete_option('sbi_license_status');
			}
		}

		$model = (array)json_decode(stripslashes($model));

		$general = (array)$model['general'];
		$feeds = (array)$model['feeds'];
		$advanced = (array)$model['advanced'];

		// Get the values and sanitize
		$sbi_settings = get_option('sb_instagram_settings', array());

		/**
		 * General Tab
		 */
		$sbi_settings['sb_instagram_preserve_settings'] = $general['preserveSettings'];

		/**
		 * Feeds Tab
		 */
		if (current_user_can('unfiltered_html')) {
			$sbi_settings['sb_instagram_custom_css'] = $feeds['customCSS'];
			$sbi_settings['sb_instagram_custom_js'] = $feeds['customJS'];
		}

		$sbi_settings['gdpr'] = sanitize_text_field($feeds['gdpr']);
		$sbi_settings['sbi_cache_cron_interval'] = sanitize_text_field($feeds['cronInterval']);
		$sbi_settings['sbi_cache_cron_time'] = sanitize_text_field($feeds['cronTime']);
		$sbi_settings['sbi_cache_cron_am_pm'] = sanitize_text_field($feeds['cronAmPm']);

		/**
		 * Advanced Tab
		 */
		$sbi_settings['sb_instagram_ajax_theme'] = sanitize_text_field($advanced['sbi_ajax']);
		$sbi_settings['sb_instagram_disable_resize'] = !(bool)$advanced['sbi_enable_resize'];
		$sbi_settings['image_format'] = sanitize_text_field($advanced['image_format']);
		$sbi_settings['sb_ajax_initial'] = (bool)$advanced['sb_ajax_initial'];
		$sbi_settings['enqueue_js_in_head'] = (bool)$advanced['sbi_enqueue_js_in_head'];
		$sbi_settings['enqueue_css_in_shortcode'] = (bool)$advanced['sbi_enqueue_css_in_shortcode'];
		$sbi_settings['disable_js_image_loading'] = !(bool)$advanced['sbi_enable_js_image_loading'];
		$sbi_settings['disable_admin_notice'] = !(bool)$advanced['enable_admin_notice'];
		$sbi_settings['enable_email_report'] = (bool)$advanced['enable_email_report'];
		$sbi_settings['enqueue_legacy_css'] = (bool)$advanced['enqueue_legacy_css'];

		$sbi_settings['email_notification'] = sanitize_text_field($advanced['email_notification']);
		$sbi_settings['email_notification_addresses'] = sanitize_text_field($advanced['email_notification_addresses']);

		if ( isset( $advanced['usage_tracking'] ) ) {
			$tracking_enabled = (bool) $advanced['usage_tracking'];
			$usage_tracking   = get_option(
				'sbi_usage_tracking',
				array(
					'enabled'   => SmashTrackingConfig::DEFAULT_ENABLED,
					'last_send' => 0,
				)
			);
			$last_send        = is_array( $usage_tracking ) && isset( $usage_tracking['last_send'] ) ? $usage_tracking['last_send'] : 0;
			update_option(
				'sbi_usage_tracking',
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

		// Update the sbi_style_settings option that contains data for translation and advanced tabs
		update_option('sb_instagram_settings', $sbi_settings);

		// clear cron caches
		$this->sbi_clear_cache();

		$response = new SBI_Response(true, array(
			'cronNextCheck' => $this->get_cron_next_check()
		));
		$response->send();
	}


/** Function sbi_test_connection() called by wp_ajax hooks: {'sbi_test_connection'} **/
/** No params detected :-/ **/


/** Function sbi_export_settings_json() called by wp_ajax hooks: {'sbi_export_settings_json'} **/
/** Parameters found in function sbi_export_settings_json(): {"get": ["feed_id"]} **/
function sbi_export_settings_json()
	{
		if (!check_ajax_referer('sbi-admin', 'nonce', false)) {
			wp_send_json_error();
		}

		if (!sbi_current_user_can('manage_instagram_feed_options')) {
			wp_send_json_error(); // This auto-dies.
		}

		if (!isset($_GET['feed_id'])) {
			return;
		}
		$feed_id = filter_var($_GET['feed_id'], FILTER_SANITIZE_NUMBER_INT);
		$feed = SBI_Feed_Saver_Manager::get_export_json($feed_id);
		$feed_info = SBI_Db::feeds_query(array('id' => $feed_id));
		$feed_name = strtolower($feed_info[0]['feed_name']);
		$filename = 'sbi-feed-' . $feed_name . '.json';
		// create a new empty file in the php memory
		$file = fopen('php://memory', 'w');
		fwrite($file, $feed);
		fseek($file, 0);
		header('Content-type: application/json');
		header('Content-disposition: attachment; filename = "' . $filename . '";');
		fpassthru($file);
		exit;
	}


/** Function sbi_clear_error_log() called by wp_ajax hooks: {'sbi_clear_error_log'} **/
/** No params detected :-/ **/


/** Function get_api_calls_handler() called by wp_ajax hooks: {'sbi_get_api_calls_handler'} **/
/** Parameters found in function get_api_calls_handler(): {"post": ["user_id", "ajax_action", "account_type", "connect_type", "media_fields", "post_limit"]} **/
function get_api_calls_handler()
	{
		check_ajax_referer('sbi-admin', 'nonce');

		$user_role = self::$plugin . self::$role;
		if (!sbi_current_user_can($user_role)) {
			wp_send_json_error(__('You don\'t have enough permission to perform this API call.', 'instagram-feed'));
		}

		$user_id = isset($_POST['user_id']) ? sanitize_text_field($_POST['user_id']) : false;
		$ajax_action = isset($_POST['ajax_action']) ? sanitize_text_field($_POST['ajax_action']) : 'user_info';
		$account_type = isset($_POST['account_type']) ? sanitize_text_field($_POST['account_type']) : 'basic';
		$connect_type = isset($_POST['connect_type']) ? sanitize_text_field($_POST['connect_type']) : 'personal';

		if (!$user_id) {
			wp_send_json_error(__('User ID is required', 'instagram-feed'));
		}

		$connected_accounts = SB_Instagram_Connected_Account::get_all_connected_accounts();

		$access_token = '';
		foreach ($connected_accounts as $connected_account) {
			if ((string)$connected_account['id'] === $user_id) {
				$access_token = $connected_account['access_token'];
				break;
			}
		}

		if (empty($access_token)) {
			wp_send_json_error(__('Access Token is required', 'instagram-feed'));
		}

		switch ($ajax_action) {
			case 'user_info':
				$api_response = $this->get_account_info(array(
					'user_id' => $user_id,
					'access_token' => $access_token,
					'account_type' => $account_type,
					'connect_type' => $connect_type,
				));
				break;

			case 'media':
				$media_fields = isset($_POST['media_fields']) ? sanitize_text_field($_POST['media_fields']) : 'media_url,thumbnail_url,caption,id,media_type,timestamp,username,permalink';
				$post_limit = isset($_POST['post_limit']) ? absint($_POST['post_limit']) : 10;
				$api_response = $this->get_media(array(
					'user_id' => $user_id,
					'access_token' => $access_token,
					'account_type' => $account_type,
					'media_fields' => $media_fields,
					'post_limit' => $post_limit,
				));
				break;

			default:
				wp_send_json_error(__('Invalid API action', 'instagram-feed'));
		}

		if (is_wp_error($api_response)) {
			wp_send_json_error($api_response);
		} else {
			$api_response = sanitize_text_field(wp_remote_retrieve_body($api_response));
			$api_response = json_decode($api_response, true);

			if (isset($api_response['error'])) {
				wp_send_json_error($api_response['error']);
			}

			// responses have next pagination data that includes access token so we need to remove it.
			if (isset($api_response['paging']['next'])) {
				$api_response['paging']['next'] = !empty($api_response['paging']['next']) ? true : false;
			}

			wp_send_json_success([
				'api_response' => $api_response,
				'user_id' => $user_id,
			]);
		}
	}


/** Function dismiss() called by wp_ajax hooks: {'sbi_dashboard_notification_dismiss'} **/
/** Parameters found in function dismiss(): {"get": ["sbi_ignore_rating_notice_nag", "sbi_nonce", "sbi_ignore_new_user_sale_notice", "sbi_ignore_bfcm_sale_notice", "sbi_dismiss"]} **/
function dismiss()
	{
		global $current_user;
		$user_id = $current_user->ID;
		$sbi_statuses_option = get_option('sbi_statuses', array());

		// TODO: Remove at 7.0.0
		global $sbi_notices;
		$discount_notice = $sbi_notices->get_notice('discount');
		if ($discount_notice && !isset($sbi_statuses_option['preexisting_discount_notice_check'])) {
			update_user_meta($user_id, 'sbi_ignore_new_user_sale_notice', 'always');
			update_user_meta($user_id, 'sb_notice_discount_dismissed', true);
			$sbi_notices->remove_notice('discount');
		}
		$sbi_statuses_option['preexisting_discount_notice_check'] = true;
		update_option('sbi_statuses', $sbi_statuses_option);

		// TODO:: Remove at 7.0.0
		$rating_notice_found = false;
		$rating_notices = array(
			'review_step_1',
			'review_step_1_all_pages',
			'review_step_2',
			'review_step_2_all_pages',
		);

		foreach ($rating_notices as $rating_notice) {
			$notice = $sbi_notices->get_notice($rating_notice);
			if ($notice) {
				$rating_notice_found = $notice;
				break;
			}
		}

		$sbi_rating_notice_option = get_option('sbi_rating_notice', false);
		$sbi_rating_notice_waiting = get_transient('instagram_feed_rating_notice_waiting');

		if (!empty($rating_notice_found) && $sbi_rating_notice_option === false && $sbi_rating_notice_waiting === false) {
			foreach ($rating_notices as $rating_notice) {
				$sbi_notices->remove_notice($rating_notice);
			}

			update_option('sbi_rating_notice', 'dismissed', false);
			$sbi_statuses_option['rating_notice_dismissed'] = sbi_get_current_time();
			update_option('sbi_statuses', $sbi_statuses_option, false);

			update_user_meta($user_id, 'sbi_ignore_new_user_sale_notice', 'always');
			update_user_meta($user_id, 'sb_notice_discount_dismissed', true);
		}

		if (isset($_GET['sbi_ignore_rating_notice_nag'])) {
			$rating_ignore = false;
			if (isset($_GET['sbi_nonce']) && wp_verify_nonce($_GET['sbi_nonce'], 'sbi-review')) {
				$rating_ignore = isset($_GET['sbi_ignore_rating_notice_nag']) ? sanitize_text_field($_GET['sbi_ignore_rating_notice_nag']) : false;
			}
			if (1 === (int)$rating_ignore) {
				update_option('sbi_rating_notice', 'dismissed', false);
				$sbi_statuses_option['rating_notice_dismissed'] = sbi_get_current_time();
				update_option('sbi_statuses', $sbi_statuses_option, false);

				$sbi_notices->remove_notice('review_step_2');
				$sbi_notices->remove_notice('review_step_2_all_pages');
			} elseif ('later' === $rating_ignore) {
				set_transient('instagram_feed_rating_notice_waiting', 'waiting', 2 * WEEK_IN_SECONDS);
				delete_option('sbi_review_consent');
				update_option('sbi_rating_notice', 'pending', false);

				$sbi_notices->remove_notice('review_step_2');
				$sbi_notices->remove_notice('review_step_2_all_pages');
			}
		}

		if (isset($_GET['sbi_ignore_new_user_sale_notice'])) {
			$new_user_ignore = false;
			if (isset($_GET['sbi_nonce']) && wp_verify_nonce($_GET['sbi_nonce'], 'sbi-discount')) {
				$new_user_ignore = isset($_GET['sbi_ignore_new_user_sale_notice']) ? sanitize_text_field($_GET['sbi_ignore_new_user_sale_notice']) : false;
			}
			if ('always' === $new_user_ignore) {
				update_user_meta($user_id, 'sbi_ignore_new_user_sale_notice', 'always');
				update_user_meta($user_id, 'sb_notice_discount_dismissed', true);

				$current_month_number = (int)date('n', sbi_get_current_time());
				$not_early_in_the_year = ($current_month_number > 5);

				if ($not_early_in_the_year) {
					update_user_meta($user_id, 'sbi_ignore_bfcm_sale_notice', date('Y', sbi_get_current_time()));
				}

				$sbi_notices->remove_notice('discount');
			}
		}

		if (isset($_GET['sbi_ignore_bfcm_sale_notice'])) {
			$bfcm_ignore = false;
			if (isset($_GET['sbi_nonce']) && wp_verify_nonce($_GET['sbi_nonce'], 'sbi-bfcm')) {
				$bfcm_ignore = isset($_GET['sbi_ignore_bfcm_sale_notice']) ? sanitize_text_field($_GET['sbi_ignore_bfcm_sale_notice']) : false;
			}
			if ('always' === $bfcm_ignore) {
				update_user_meta($user_id, 'sbi_ignore_bfcm_sale_notice', 'always');
			} elseif (date('Y', sbi_get_current_time()) === $bfcm_ignore) {
				update_user_meta($user_id, 'sbi_ignore_bfcm_sale_notice', date('Y', sbi_get_current_time()));
			}
			if ($bfcm_ignore) {
				update_user_meta($user_id, 'sbi_ignore_new_user_sale_notice', 'always');
				update_user_meta($user_id, 'sb_notice_discount_dismissed', true);

				$sbi_notices->remove_notice('discount');
			}
		}

		if (isset($_GET['sbi_dismiss'])) {
			$review_nonce = isset($_GET['sbi_nonce']) ? wp_verify_nonce($_GET['sbi_nonce'], 'sbi-review') : false;
			$discount_nonce = isset($_GET['sbi_nonce']) ? wp_verify_nonce($_GET['sbi_nonce'], 'sbi-discount') : false;
			$notice_dismiss = ($review_nonce || $discount_nonce) ? sanitize_text_field($_GET['sbi_dismiss']) : false;

			if ('review' === $notice_dismiss) {
				update_option('sbi_rating_notice', 'dismissed', false);
				$sbi_statuses_option['rating_notice_dismissed'] = sbi_get_current_time();
				update_option('sbi_statuses', $sbi_statuses_option, false);

				update_user_meta($user_id, 'sbi_ignore_new_user_sale_notice', 'always');
				update_user_meta($user_id, 'sb_notice_discount_dismissed', true);

				$sbi_notices->remove_notice('review_step_1');
				$sbi_notices->remove_notice('review_step_1_all_pages');
				$sbi_notices->remove_notice('review_step_2');
				$sbi_notices->remove_notice('review_step_2_all_pages');
			} elseif ('discount' === $notice_dismiss) {
				update_user_meta($user_id, 'sbi_ignore_new_user_sale_notice', 'always');
				update_user_meta($user_id, 'sb_notice_discount_dismissed', true);

				$current_month_number = (int)date('n', sbi_get_current_time());
				$not_early_in_the_year = ($current_month_number > 5);

				if ($not_early_in_the_year) {
					update_user_meta($user_id, 'sbi_ignore_bfcm_sale_notice', date('Y', sbi_get_current_time()));
				}

				$sbi_notices->remove_notice('discount');
			}
		}
	}


/** Function preview() called by wp_ajax hooks: {'sb_instagramfeed_divi_preview'} **/
/** No params detected :-/ **/


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


/** Function sbi_do_locator() called by wp_ajax hooks: {'sbi_do_locator', 'nopriv_sbi_do_locator'} **/
/** Parameters found in function sbi_do_locator(): {"post": ["feed_id", "post_id", "atts", "locator_nonce", "location"]} **/
function sbi_do_locator()
{
	if (!isset($_POST['feed_id']) || !preg_match('/^(sbi|\\*)/', $_POST['feed_id'])) {
		wp_send_json_error('invalid feed ID');
	}

	$feed_id = sanitize_text_field(wp_unslash($_POST['feed_id']));
	$post_id = isset($_POST['post_id']) && $_POST['post_id'] !== 'unknown' ? intval($_POST['post_id']) : 'unknown';

	$atts_raw = isset($_POST['atts']) ? json_decode(wp_unslash($_POST['atts']), true) : array();
	$atts = is_array($atts_raw) ? SB_Instagram_Settings::sanitize_raw_atts($atts_raw) : array();

	$database_settings = sbi_get_database_settings();
	$instagram_feed_settings = new SB_Instagram_Settings($atts, $database_settings);

	$instagram_feed_settings->set_feed_type_and_terms();
	$instagram_feed_settings->set_transient_name();
	$transient_name = $instagram_feed_settings->get_transient_name();

	$nonce = isset($_POST['locator_nonce']) ? sanitize_text_field(wp_unslash($_POST['locator_nonce'])) : '';
	if (!wp_verify_nonce($nonce, 'sbi-locator-nonce-' . $post_id . '-' . $transient_name)) {
		wp_send_json_error('nonce check failed');
	}

	$location = isset($_POST['location']) && in_array($_POST['location'], array('header', 'footer', 'sidebar', 'content'), true) ? sanitize_text_field($_POST['location']) : 'unknown';
	$feed_details = array(
		'feed_id' => $feed_id,
		'atts' => $atts,
		'location' => array(
			'post_id' => $post_id,
			'html' => $location
		)
	);

	sbi_do_background_tasks($feed_details);
	wp_send_json_success('locating success');
}


/** Function ajax_record_session() called by wp_ajax hooks: {'sbi_smash_usage_record_session'} **/
/** Parameters found in function ajax_record_session(): {"post": ["duration_seconds"]} **/
function ajax_record_session() {
		check_ajax_referer( 'sbi_smash_usage_record_session', 'nonce' );
		if ( ( ! current_user_can( 'manage_instagram_feed_options' ) && ! current_user_can( 'manage_options' )) || ! Config::is_enabled() ) {
			wp_send_json_error();
		}
		$seconds = isset( $_POST['duration_seconds'] ) ? (int) $_POST['duration_seconds'] : 0;
		EventRecorder::record_session_duration( $seconds );
		wp_send_json_success();
	}


/** Function ajax_record_event() called by wp_ajax hooks: {'sbi_smash_usage_record_event'} **/
/** Parameters found in function ajax_record_event(): {"post": ["event_name"]} **/
function ajax_record_event() {
		check_ajax_referer( 'sbi_smash_usage_record_event', 'nonce' );
		if ( ! current_user_can( 'manage_instagram_feed_options' ) && ! current_user_can( 'manage_options' ) ) {
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


/** Function sbi_install_addon() called by wp_ajax hooks: {'sbi_install_addon'} **/
/** Parameters found in function sbi_install_addon(): {"post": ["plugin", "type", "referrer"]} **/
function sbi_install_addon()
{
	// Run a security check.
	check_ajax_referer('sbi-admin', 'nonce');

	// Check for permissions.
	if (!current_user_can('install_plugins')) {
		wp_send_json_error();
	}

	$error = esc_html__('Could not install addon. Please download from wpforms.com and install manually.', 'instagram-feed');

	if (empty($_POST['plugin'])) {
		wp_send_json_error($error);
	}

	// Only install plugins from the .org repo
	if (strpos($_POST['plugin'], 'https://downloads.wordpress.org/plugin/') !== 0) {
		wp_send_json_error($error);
	}

	// Set the current screen to avoid undefined notices.
	set_current_screen('sbi-about-us');

	// Prepare variables.
	$url = esc_url_raw(
		add_query_arg(
			array(
				'page' => 'sbi-about-us',
			),
			admin_url('admin.php')
		)
	);

	$creds = request_filesystem_credentials($url, '', false, false, null);

	// Check for file system permissions.
	if (false === $creds) {
		wp_send_json_error($error);
	}

	if (!WP_Filesystem($creds)) {
		wp_send_json_error($error);
	}

	/*
	 * We do not need any extra credentials if we have gotten this far, so let's install the plugin.
	 */

	require_once SBI_PLUGIN_DIR . 'inc/admin/class-install-skin.php';

	// Do not allow WordPress to search/download translations, as this will break JS output.
	remove_action('upgrader_process_complete', array('Language_Pack_Upgrader', 'async_upgrade'), 20);

	// Create the plugin upgrader with our custom skin.
	$installer = new Sbi\Helpers\PluginSilentUpgrader(new Sbi_Install_Skin());

	// Error check.
	if (!method_exists($installer, 'install') || empty($_POST['plugin'])) {
		wp_send_json_error($error);
	}

	$installer->install(esc_url_raw(wp_unslash($_POST['plugin'])));

	// Flush the cache and return the newly installed plugin basename.
	wp_cache_flush();

	$plugin_basename = $installer->plugin_info();

	if ($plugin_basename) {
		$type = 'addon';
		if (!empty($_POST['type'])) {
			$type = sanitize_key($_POST['type']);
		}

		$referrer = '';
		if (!empty($_POST['referrer'])) {
			$referrer = sanitize_key($_POST['referrer']);
		}

		// Activate the plugin silently.
		$activated = activate_plugin($plugin_basename);

		if (!is_wp_error($activated)) {
			if ($plugin_basename === 'custom-facebook-feed/custom-facebook-feed.php' && $referrer === 'oembeds') {
				delete_option('cff_plugin_do_activation_redirect');
			}
			wp_send_json_success(
				array(
					'msg' => 'plugin' === $type ? esc_html__('Plugin installed & activated.', 'instagram-feed') : esc_html__('Addon installed & activated.', 'instagram-feed'),
					'is_activated' => true,
					'basename' => $plugin_basename,
				)
			);
		} else {
			wp_send_json_success(
				array(
					'msg' => 'plugin' === $type ? esc_html__('Plugin installed.', 'instagram-feed') : esc_html__('Addon installed.', 'instagram-feed'),
					'is_activated' => false,
					'basename' => $plugin_basename,
				)
			);
		}
	}

	wp_send_json_error($error);
}


/** Function sbi_dismiss_critical_notice() called by wp_ajax hooks: {'sbi_dismiss_critical_notice'} **/
/** No params detected :-/ **/


