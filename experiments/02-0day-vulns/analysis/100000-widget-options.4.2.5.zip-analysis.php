<?php
/***
*
*Found actions: 23
*Found functions:23
*Extracted functions:22
*Total parameter names extracted: 10
*Overview: {'widgetopts_acf_get_field_groups': {'widgetopts_acf_get_field_groups'}, 'widgetopts_get_pages': {'widgetopts_get_pages'}, 'widgetopts_ajax_hide_rating': {'widgetopts_hideRating'}, 'ajax_run_migration': {'widgetopts_run_migration'}, 'ajax_get_snippets': {'widgetopts_get_snippets'}, 'widgetopts_ajax_page_search': {'widgetopts_ajax_page_search'}, 'widgetopts_ajax_roles_search_block': {'widgetopts_ajax_roles_search_block'}, 'widgetopts_ajax_taxonomy_search': {'widgetopts_ajax_taxonomy_search'}, 'ajax_migration_delete': {'widgetopts_migration_delete'}, 'widgetopts_get_types': {'widgetopts_get_types'}, 'ajax_migration_migrate': {'widgetopts_migration_migrate'}, 'widgetopts_get_users': {'widgetopts_get_users'}, 'ajax_migration': {'widgetopts_migrator'}, 'widgetopts_get_legacy_data': {'widgetopts_get_legacy_data'}, 'widgetopts_get_settings_ajax': {'widgetopts_get_settings_ajax'}, 'widgetopts_get_taxonomies': {'widgetopts_get_taxonomies'}, 'ajax_dismiss_migration_notice': {'widgetopts_dismiss_migration_notice'}, 'ajax_migration_scan': {'widgetopts_migration_scan'}, 'widgetopts_ajax_validate_expression': {'widgetopts_ajax_validate_expression'}, 'widgetopts_get_terms': {'widgetopts_get_terms'}, 'widgetopts_ajax_save_settings': {'widgetopts_ajax_settings'}, 'widgetopts_get_snippets_ajax': {'widgetopts_get_snippets_ajax'}, 'ajax_dismiss_legacy_migration_required_notice': {'widgetopts_dismiss_legacy_migration_required_notice'}}
*
***/

/** Function widgetopts_acf_get_field_groups() called by wp_ajax hooks: {'widgetopts_acf_get_field_groups'} **/
/** No params detected :-/ **/


/** Function widgetopts_get_pages() called by wp_ajax hooks: {'widgetopts_get_pages'} **/
/** No params detected :-/ **/


/** Function widgetopts_ajax_hide_rating() called by wp_ajax hooks: {'widgetopts_hideRating'} **/
/** Parameters found in function widgetopts_ajax_hide_rating(): {"post": ["nonce"]} **/
function widgetopts_ajax_hide_rating()
	{
		if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'widgetopts_ajax_nonce')) {
			wp_send_json_error('Invalid nonce or session.', 403);
			exit;
		}

		if (!current_user_can('update_plugins')) {
			wp_send_json_error('You do not have permission to update plugins.', 403);
			exit;
		}

		$updated = update_option('widgetopts_RatingDiv', 'yes');

		if ($updated) {
			wp_send_json_success(array('message' => 'Rating visibility updated successfully.'));
		} else {
			wp_send_json_error('Failed to update the option.', 500);
		}
		exit;
	}


/** Function ajax_run_migration() called by wp_ajax hooks: {'widgetopts_run_migration'} **/
/** No params detected :-/ **/


/** Function ajax_get_snippets() called by wp_ajax hooks: {'widgetopts_get_snippets'} **/
/** No params detected :-/ **/


/** Function widgetopts_ajax_page_search() called by wp_ajax hooks: {'widgetopts_ajax_page_search'} **/
/** Parameters found in function widgetopts_ajax_page_search(): {"post": ["term"]} **/
function widgetopts_ajax_page_search()
{
    if (!current_user_can('edit_posts')) {
        wp_send_json_error('You do not have permission to search pages.', 403);
        exit;
    }

    global $wp_version;

    $response = [
        'results' => [],
        'pagination' => ['more' => false]
    ];

    if (!empty($_POST['term'])) {
        $args = array(
            'post_type'     => 'page',
            'post_status'   => 'publish',
            's' => $_POST['term'],
        );

        $is_6_3_and_above = version_compare($wp_version, '6.3', '>=');
        if ($is_6_3_and_above) {
            $args['cache_results'] = apply_filters('cache_widgetopts_ajax_page_search', true);
        }

        $query = new WP_Query($args);
        while ($query->have_posts()) {
            $query->the_post();
            $response['results'][] = [
                'id' => get_the_ID(),
                'text' => get_the_title()
            ];
        }
    }

    echo json_encode($response);
    die();
}


/** Function widgetopts_ajax_roles_search_block() called by wp_ajax hooks: {'widgetopts_ajax_roles_search_block'} **/
/** Parameters found in function widgetopts_ajax_roles_search_block(): {"post": ["term"]} **/
function widgetopts_ajax_roles_search_block()
{
	widgetopts_verify_gutenberg_ajax();
	$response = [
		'results' => [],
		'pagination' => ['more' => false]
	];

	$term = isset($_POST['term']) && !empty($_POST['term']) ? $_POST['term'] : '';

	$roles = get_editable_roles();
	if (!empty($roles)) {
		foreach ($roles as $role_name => $role_info) {
			// if ((!empty($term) && stristr($role_name, $term) !== false) || stristr($role_name, $role_info['name']) !== false) {
			$response['results'][] = [
				'id' => $role_name,
				'text' => $role_info['name']
			];
			// }
		}
	}

	wp_send_json_success($response);
	die;
}


/** Function widgetopts_ajax_taxonomy_search() called by wp_ajax hooks: {'widgetopts_ajax_taxonomy_search'} **/
/** Parameters found in function widgetopts_ajax_taxonomy_search(): {"post": ["term", "taxonomy"]} **/
function widgetopts_ajax_taxonomy_search()
{
    if (!current_user_can('edit_posts')) {
        wp_send_json_error('You do not have permission to search taxonomies.', 403);
        exit;
    }

    $response = [
        'results' => [],
        'pagination' => ['more' => false]
    ];

    if (!empty($_POST['term']) && $_POST['taxonomy']) {
        $args = array(
            'taxonomy'      => array($_POST['taxonomy']),
            'fields'        => 'all',
            'name__like'    => $_POST['term'],
            'hide_empty' => false
        );

        $terms = get_terms($args);
        foreach ($terms as $term) {
            $response['results'][] = [
                'id' => $term->term_id,
                'text' => $term->name
            ];
        }
    }

    echo json_encode($response);
    die();
}


/** Function ajax_migration_delete() called by wp_ajax hooks: {'widgetopts_migration_delete'} **/
/** Parameters found in function ajax_migration_delete(): {"post": ["hash"]} **/
function ajax_migration_delete() {
        check_ajax_referer('widgetopts_migration_page', 'nonce');
        if (!current_user_can(WIDGETOPTS_MIGRATION_PERMISSIONS)) {
            wp_send_json_error(array('message' => __('Permission denied.', 'widget-options')));
        }

        $hash = isset($_POST['hash']) ? sanitize_text_field($_POST['hash']) : '';
        if (empty($hash)) {
            wp_send_json_error(array('message' => __('No hash provided.', 'widget-options')));
        }

        require_once WIDGETOPTS_PLUGIN_DIR . 'includes/snippets/class-snippets-migration.php';
        $results = WidgetOpts_Snippets_Migration::delete_legacy_code($hash);

        $remaining_items = WidgetOpts_Snippets_Migration::collect_all_logic_codes_with_locations();
        self::set_migration_required_state(!empty($remaining_items));

        wp_send_json_success(array('results' => $results));
    }


/** Function widgetopts_get_types() called by wp_ajax hooks: {'widgetopts_get_types'} **/
/** No params detected :-/ **/


/** Function ajax_migration_migrate() called by wp_ajax hooks: {'widgetopts_migration_migrate'} **/
/** Parameters found in function ajax_migration_migrate(): {"post": ["items"]} **/
function ajax_migration_migrate() {
        check_ajax_referer('widgetopts_migration_page', 'nonce');
        if (!current_user_can(WIDGETOPTS_MIGRATION_PERMISSIONS)) {
            wp_send_json_error(array('message' => __('Permission denied.', 'widget-options')));
        }

        $raw = isset($_POST['items']) ? $_POST['items'] : array();
        if (empty($raw) || !is_array($raw)) {
            wp_send_json_error(array('message' => __('No items provided.', 'widget-options')));
        }

        // Sanitize
        $items = array();
        foreach ($raw as $entry) {
            $items[] = array(
                'hash'  => sanitize_text_field($entry['hash']),
                'title' => sanitize_text_field($entry['title']),
            );
        }

        require_once WIDGETOPTS_PLUGIN_DIR . 'includes/snippets/class-snippets-migration.php';
        $results = WidgetOpts_Snippets_Migration::migrate_selected($items);

        $remaining_items = WidgetOpts_Snippets_Migration::collect_all_logic_codes_with_locations();
        self::set_migration_required_state(!empty($remaining_items));

        wp_send_json_success(array('results' => $results));
    }


/** Function widgetopts_get_users() called by wp_ajax hooks: {'widgetopts_get_users'} **/
/** No params detected :-/ **/


/** Function ajax_migration() called by wp_ajax hooks: {'widgetopts_migrator'} **/
/** No function found :-/ **/


/** Function widgetopts_get_legacy_data() called by wp_ajax hooks: {'widgetopts_get_legacy_data'} **/
/** Parameters found in function widgetopts_get_legacy_data(): {"post": ["id_base"]} **/
function widgetopts_get_legacy_data()
{
	widgetopts_verify_gutenberg_ajax();

	if (isset($_POST['id_base'])) {
		wp_send_json_success(array());
		die;
	}
	$settings = array();
	$settings = get_option('widget_' + sanitize_key($_POST['id_base']));

	if (false === $settings) {
		return $settings;
	}

	if (!is_array($settings) && !($settings instanceof ArrayObject || $settings instanceof ArrayIterator)) {
		$settings = array();
	}

	wp_send_json_success($settings);
	die;
}


/** Function widgetopts_get_settings_ajax() called by wp_ajax hooks: {'widgetopts_get_settings_ajax'} **/
/** No params detected :-/ **/


/** Function widgetopts_get_taxonomies() called by wp_ajax hooks: {'widgetopts_get_taxonomies'} **/
/** No params detected :-/ **/


/** Function ajax_dismiss_migration_notice() called by wp_ajax hooks: {'widgetopts_dismiss_migration_notice'} **/
/** No params detected :-/ **/


/** Function ajax_migration_scan() called by wp_ajax hooks: {'widgetopts_migration_scan'} **/
/** No params detected :-/ **/


/** Function widgetopts_ajax_validate_expression() called by wp_ajax hooks: {'widgetopts_ajax_validate_expression'} **/
/** Parameters found in function widgetopts_ajax_validate_expression(): {"post": ["nonce", "expression"]} **/
function widgetopts_ajax_validate_expression()
{
	if (!current_user_can('manage_options')) {
		wp_send_json_error('You do not have permission to validate expressions.', 403);
		exit;
	}
	
	if (!wp_verify_nonce($_POST['nonce'], 'widgetopts-expression-nonce')) {
		echo json_encode(['response' => 'failed', 'message' => 'Security check failed. Please refresh the page and try again.']);
		die();
	}

	if (!isset($_POST['expression']) || empty(trim($_POST['expression']))) {
		echo json_encode(['response' => 'success', 'message' => 'Expression is empty, but this will be considered as valid.', 'valid' => true]);
		die();
	}

	$expression = sanitize_text_field($_POST['expression']);
	$result = widgetopts_validate_expression($expression);

	echo json_encode(['response' => 'success', 'message' => $result['message'], 'valid' => $result['valid']]);
	die();
}


/** Function widgetopts_get_terms() called by wp_ajax hooks: {'widgetopts_get_terms'} **/
/** No params detected :-/ **/


/** Function widgetopts_ajax_save_settings() called by wp_ajax hooks: {'widgetopts_ajax_settings'} **/
/** Parameters found in function widgetopts_ajax_save_settings(): {"post": ["method", "nonce", "module", "data"]} **/
function widgetopts_ajax_save_settings()
{
	$response = array('errors' => array());

	if (!isset($_POST['method'])) return;
	if (!isset($_POST['nonce'])) return;

	if (!wp_verify_nonce($_POST['nonce'], 'widgetopts-settings-nonce')) {
		return;
	}

	if (!current_user_can('manage_options')) {
		wp_send_json_error('You do not have permission to manage settings.', 403);
		exit;
	}

	switch ($_POST['method']) {
		case 'activate':
		case 'deactivate':
			if (!isset($_POST['module'])) return;

			//update options
			update_option('widgetopts_tabmodule-' . sanitize_text_field($_POST['module']), sanitize_text_field($_POST['method']));

			//update global variable
			widgetopts_update_option(sanitize_text_field($_POST['module']), sanitize_text_field($_POST['method']));
			break;

		case 'save':
			$response['messages'] = array(__('Settings saved successfully.', 'widget-options'));
			if (!isset($_POST['data'])) return;
			parse_str($_POST['data']['--widgetopts-form-serialized-data'], $params);
			$sanitized = widgetopts_sanitize_array($params);
			update_option('widgetopts_tabmodule-settings', maybe_serialize($sanitized));

			//reset options
			widgetopts_update_option('settings', $sanitized);
			break;

		case 'deactivate_license':
			global $extended_license;
			$license = sanitize_text_field($_POST['data']['license-data']);
			if (!empty($license)) {

				if (isset($_POST['data']['shortname'])) {
					$item_shortname = sanitize_text_field($_POST['data']['shortname']);
				} else {
					$item_shortname = 'widgetopts_' . preg_replace('/[^a-zA-Z0-9_\s]/', '', str_replace(' ', '_', strtolower(WIDGETOPTS_PLUGIN_NAME)));
				}
				switch ($item_shortname) {
					case 'widgetopts_sliding_widget_options':
						global $widgetopts_sliding_license;
						$data = $widgetopts_sliding_license->deactivate_license($license);
						break;

					default:
						$data = $extended_license->deactivate_license($license);
						break;
				}

				$response['button'] = sanitize_text_field($_POST['data']['button']);
				if ($data == 'deactivated') {

					$optiondata = get_option('widgetopts_license_keys');
					$name = str_replace('widgetopts-license-btn-', '', sanitize_text_field($_POST['data']['button']));
					$optiondata[$name] = '';
					update_option('widgetopts_license_keys', $optiondata);

					//remove license key on option
					delete_option($item_shortname . '_license_key');

					$response['messages'] = array(__('Successfully Deactivated.', 'widget-options'));
				} else {
					$response['messages'] = array(__('Deactivation Failed.', 'widget-options'));
				}
				$response['success'] = $data;
			}

			break;
		case 'delete_widgetopts_update_transient':
			update_option('widgetopts_upgrade', 0);
			break;

		default:
			# code...
			break;
	}
	$response['source'] 	= 'WIDGETOPTS_Response';
	$response['response'] 	= 'success';
	$response['closeModal'] = true;
	$response 				= (object) $response;

	//let devs do there action
	do_action('widget_options_before_ajax_print', sanitize_text_field($_POST['method']));

	echo json_encode($response);
	die();
}


/** Function widgetopts_get_snippets_ajax() called by wp_ajax hooks: {'widgetopts_get_snippets_ajax'} **/
/** Parameters found in function widgetopts_get_snippets_ajax(): {"post": ["search"]} **/
function widgetopts_get_snippets_ajax()
{
	widgetopts_verify_gutenberg_ajax();

	$search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';

	$snippets = array();
	if (class_exists('WidgetOpts_Snippets_CPT')) {
		$all_snippets = WidgetOpts_Snippets_CPT::get_all_snippets($search);
		foreach ($all_snippets as $snippet) {
			$snippets[] = array(
				'id' => $snippet['id'],
				'title' => $snippet['title'],
				'description' => $snippet['description']
			);
		}
	}

	// Return data with admin info for manage snippets link
	wp_send_json_success(array(
		'snippets' => $snippets,
		'can_manage' => current_user_can('manage_options'),
		'manage_url' => admin_url('edit.php?post_type=widgetopts_snippet'),
		'migration_url' => admin_url('options-general.php?page=widgetopts_migration')
	));
	die;
}


/** Function ajax_dismiss_legacy_migration_required_notice() called by wp_ajax hooks: {'widgetopts_dismiss_legacy_migration_required_notice'} **/
/** No params detected :-/ **/


