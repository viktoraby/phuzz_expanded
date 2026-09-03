<?php
/***
*
*Found actions: 12
*Found functions:12
*Extracted functions:12
*Total parameter names extracted: 9
*Overview: {'ajax_get_taxonomies': {'su_generator_get_taxonomies'}, 'ajax_search_users': {'su_generator_search_users'}, 'ajax_remove_preset': {'su_generator_remove_preset'}, 'ajax_get_preset': {'su_generator_get_preset'}, 'dismiss_notice_ajax_callback': {'fs_dismiss_notice_action_{$ajax_action_suffix}'}, 'preview': {'su_generator_preview'}, 'ajax_search_posts': {'su_generator_search_posts'}, 'ajax_get_terms': {'su_generator_get_terms'}, 'ajax_get_icons': {'su_generator_get_icons'}, '_toggle_debug_mode': {'fs_toggle_debug_mode'}, 'settings': {'su_generator_settings'}, 'ajax_add_preset': {'su_generator_add_preset'}}
*
***/

/** Function ajax_get_taxonomies() called by wp_ajax hooks: {'su_generator_get_taxonomies'} **/
/** No params detected :-/ **/


/** Function ajax_search_users() called by wp_ajax hooks: {'su_generator_search_users'} **/
/** Parameters found in function ajax_search_users(): {"request": ["ids", "search"]} **/
function ajax_search_users()
	{
		self::access();

		$ids = array();

		if (isset($_REQUEST['ids'])) {
			$ids = is_array($_REQUEST['ids'])
				? $_REQUEST['ids']
				: explode(',', (string) wp_unslash($_REQUEST['ids']));

			$ids = array_filter(array_map('absint', $ids));
		}

		$args = array(
			'fields' => 'all',
			'number' => 20,
		);

		if (!empty($ids)) {
			$args['include'] = $ids;
			$args['number'] = count($ids);
			$args['orderby'] = 'include';
		} else {
			$search = isset($_REQUEST['search'])
				? sanitize_text_field(wp_unslash($_REQUEST['search']))
				: '';

			if (strlen($search) < 2) {
				wp_send_json_success(array('results' => array()));
			}

			$args['search'] = '*' . $search . '*';
			$args['search_columns'] = array(
				'user_login',
				'user_nicename',
				'display_name',
				'user_email',
			);
		}

		$results = array();

		foreach (get_users($args) as $user) {
			$results[] = self::format_user_search_result($user);
		}

		wp_send_json_success(array('results' => $results));
	}


/** Function ajax_remove_preset() called by wp_ajax hooks: {'su_generator_remove_preset'} **/
/** Parameters found in function ajax_remove_preset(): {"post": ["id", "shortcode", "nonce"]} **/
function ajax_remove_preset()
	{
		self::access();
		// Check incoming data
		if (empty($_POST['id']))
			return;
		if (empty($_POST['shortcode']))
			return;
		// Check Nonce
		if (
			empty($_POST['nonce']) ||
			!is_string($_POST['nonce']) ||
			!wp_verify_nonce($_POST['nonce'], 'su_generator_preset')
		) {
			return;
		}
		// Clean-up incoming data
		$id = sanitize_key($_POST['id']);
		$shortcode = sanitize_key($_POST['shortcode']);
		// Prepare option name
		$option = 'su_presets_' . $shortcode;
		// Get the existing presets
		$current = get_option($option);
		// Check that preset is exists
		if (!is_array($current) || empty($current[$id]))
			return;
		// Remove preset
		unset($current[$id]);
		// Save updated option
		update_option($option, $current);
		// Clear cache
		delete_transient('su/generator/settings/' . $shortcode);
	}


/** Function ajax_get_preset() called by wp_ajax hooks: {'su_generator_get_preset'} **/
/** Parameters found in function ajax_get_preset(): {"get": ["id", "shortcode", "nonce"]} **/
function ajax_get_preset()
	{
		self::access();
		// Check incoming data
		if (empty($_GET['id']))
			return;
		if (empty($_GET['shortcode']))
			return;
		// Check Nonce
		if (
			empty($_GET['nonce']) ||
			!is_string($_GET['nonce']) ||
			!wp_verify_nonce($_GET['nonce'], 'su_generator_preset')
		) {
			return;
		}
		// Clean-up incoming data
		$id = sanitize_key($_GET['id']);
		$shortcode = sanitize_key($_GET['shortcode']);
		// Default data
		$data = array();
		// Get the existing presets
		$presets = get_option('su_presets_' . $shortcode);
		// Check that preset is exists
		if (is_array($presets) && isset($presets[$id]['settings']))
			$data = $presets[$id]['settings'];
		// Print results
		die(json_encode($data));
	}


/** Function dismiss_notice_ajax_callback() called by wp_ajax hooks: {'fs_dismiss_notice_action_{$ajax_action_suffix}'} **/
/** Parameters found in function dismiss_notice_ajax_callback(): {"post": ["message_id"]} **/
function dismiss_notice_ajax_callback() {
            check_admin_referer( 'fs_dismiss_notice_action' );

            if ( ! is_numeric( $_POST['message_id'] ) ) {
                $this->_sticky_storage->remove( $_POST['message_id'] );
            }

            wp_die();
        }


/** Function preview() called by wp_ajax hooks: {'su_generator_preview'} **/
/** Parameters found in function preview(): {"post": ["nonce", "shortcode"]} **/
function preview()
	{
		// Check nonce
		if (
			empty($_POST['nonce']) ||
			!wp_verify_nonce($_POST['nonce'], 'su_generator_preview')
		) {
			return;
		}
		// Check authentication
		self::access();
		// Output results
		do_action('su/generator/preview/before');
		$shortcode = wp_unslash($_POST['shortcode']);
		echo '<h5>' . __('Preview', 'shortcodes-ultimate') . '</h5>';
		echo apply_filters('su/generator/preview/output', do_shortcode($shortcode), $shortcode);
		echo '<div style="clear:both"></div>';
		do_action('su/generator/preview/after');
		die();
	}


/** Function ajax_search_posts() called by wp_ajax hooks: {'su_generator_search_posts'} **/
/** Parameters found in function ajax_search_posts(): {"request": ["ids", "search"]} **/
function ajax_search_posts()
	{
		self::access();

		$ids = array();

		if (isset($_REQUEST['ids'])) {
			$ids = is_array($_REQUEST['ids'])
				? $_REQUEST['ids']
				: explode(',', (string) wp_unslash($_REQUEST['ids']));

			$ids = array_filter(array_map('absint', $ids));
		}

		$args = array(
			'no_found_rows'          => true,
			'post_status'            => 'publish',
			'post_type'              => self::get_searchable_post_types(),
			'posts_per_page'         => 20,
			'suppress_filters'       => false,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		);

		if (!empty($ids)) {
			$args['orderby'] = 'post__in';
			$args['post__in'] = $ids;
			$args['posts_per_page'] = count($ids);
		} else {
			$search = isset($_REQUEST['search'])
				? sanitize_text_field(wp_unslash($_REQUEST['search']))
				: '';

			if (strlen($search) < 2) {
				wp_send_json_success(array('results' => array()));
			}

			$args['s'] = $search;
		}

		$query = new WP_Query($args);
		$results = array();

		foreach ($query->posts as $post) {
			$results[] = self::format_post_search_result($post);
		}

		wp_send_json_success(array('results' => $results));
	}


/** Function ajax_get_terms() called by wp_ajax hooks: {'su_generator_get_terms'} **/
/** Parameters found in function ajax_get_terms(): {"request": ["tax", "class", "multiple", "size", "noselect"]} **/
function ajax_get_terms()
	{
		self::access();
		$args = array();
		if (isset($_REQUEST['tax']))
			$args['options'] = (array) self::get_terms(sanitize_key($_REQUEST['tax']));
		if (isset($_REQUEST['class']))
			$args['class'] = (string) sanitize_key($_REQUEST['class']);
		if (isset($_REQUEST['multiple']))
			$args['multiple'] = (bool) sanitize_key($_REQUEST['multiple']);
		if (isset($_REQUEST['size']))
			$args['size'] = (int) sanitize_key($_REQUEST['size']);
		if (isset($_REQUEST['noselect']))
			$args['noselect'] = (bool) sanitize_key($_REQUEST['noselect']);
		die(su_html_dropdown($args));
	}


/** Function ajax_get_icons() called by wp_ajax hooks: {'su_generator_get_icons'} **/
/** No params detected :-/ **/


/** Function _toggle_debug_mode() called by wp_ajax hooks: {'fs_toggle_debug_mode'} **/
/** No params detected :-/ **/


/** Function settings() called by wp_ajax hooks: {'su_generator_settings'} **/
/** Parameters found in function settings(): {"request": ["shortcode"]} **/
function settings()
	{
		self::access();
		// Param check
		if (empty($_REQUEST['shortcode']))
			wp_die(__('Shortcode not specified', 'shortcodes-ultimate'));
		// Request queried shortcode
		$shortcode = self::normalize_shortcode(su_get_shortcode(sanitize_key($_REQUEST['shortcode'])));
		// Call custom callback
		if (
			isset($shortcode['generator_callback']) &&
			is_callable($shortcode['generator_callback'])
		) {
			call_user_func($shortcode['generator_callback'], $shortcode);
			exit;
		}
		// Prepare skip-if-default option
		$skip = (get_option('su_option_skip') === 'on') ? ' su-generator-skip' : '';
		// Prepare actions
		$actions = apply_filters('su/generator/actions', array(
			'insert' => '<a href="javascript:void(0);" class="button button-primary button-large su-generator-insert"><i class="sui sui-check"></i> ' . __('Insert shortcode', 'shortcodes-ultimate') . '</a>',
			'copy'   => '<button type="button" class="button button-large su-generator-copy" data-label="' . esc_attr__( 'Copy shortcode', 'shortcodes-ultimate' ) . '" data-copied-label="' . esc_attr__( 'Copied', 'shortcodes-ultimate' ) . '" aria-label="' . esc_attr__( 'Copy shortcode', 'shortcodes-ultimate' ) . '"><i class="sui sui-copy" aria-hidden="true"></i><span class="su-generator-copy-label">' . esc_html__( 'Copy shortcode', 'shortcodes-ultimate' ) . '</span></button>',
			'reset'  => '<button type="button" class="button button-large su-generator-reset" aria-label="' . esc_attr__( 'Reset Settings', 'shortcodes-ultimate' ) . '"><i class="sui sui-undo" aria-hidden="true"></i>' . esc_html__( 'Reset Settings', 'shortcodes-ultimate' ) . '</button>',
		));
		$return = '<div class="su-generator-settings-body">';
		$return .= '<div class="su-generator-settings-fields">';
		$tabs = self::get_generator_tabs($shortcode);
		$first_tab = empty($tabs) ? '' : key($tabs);
		$tab_panels = array();

		foreach ($tabs as $tab_id => $tab) {
			$tab_panels[$tab_id] = '';
		}

		// Shortcode header
		$return .= '<div id="su-generator-breadcrumbs">';
		$return .= apply_filters('su/generator/breadcrumbs', '<a href="javascript:void(0);" class="su-generator-home" title="' . __('Click to return to the shortcodes list', 'shortcodes-ultimate') . '">' . __('All shortcodes', 'shortcodes-ultimate') . '</a> &rarr; <span>' . $shortcode['name'] . '</span> <small class="alignright">' . $shortcode['desc'] . '</small><div class="su-generator-clear"></div>');
		$return .= '</div>';
		// Shortcode note
		if (isset($shortcode['note'])) {
			$return .= '<div class="su-generator-note"><i class="sui sui-info-circle"></i><div class="su-generator-note-content">' . wpautop($shortcode['note']) . '</div></div>';
		}
		// Shortcode CTA
		if (isset($shortcode['generator_cta'])) {
			$return .= '<div class="su-generator-cta"><div class="su-generator-cta-content">' . $shortcode['generator_cta'] . '</div></div>';
		}
		// Shortcode has atts
		if (isset($shortcode['atts']) && count($shortcode['atts'])) {
			// Loop through shortcode parameters
			foreach ($shortcode['atts'] as $attr_name => $attr_info) {

				if (empty($tabs)) {
					$return .= self::render_generator_attribute($attr_name, $attr_info, $skip);
					continue;
				}

				$tab_id = isset($attr_info['tab']) ? sanitize_key($attr_info['tab']) : $first_tab;

				if (!isset($tab_panels[$tab_id])) {
					$tab_id = $first_tab;
				}

				$tab_panels[$tab_id] .= self::render_generator_attribute($attr_name, $attr_info, $skip);

			}
		}
		// Single shortcode (not closed)
		if ($shortcode['type'] == 'single')
			$return .= '<input type="hidden" name="su-generator-content" id="su-generator-content" value="false" />';
		// Wrapping shortcode
		else {

			if (empty($tabs)) {
				$return .= self::render_generator_content_field($shortcode);
			} else {
				$tab_panels[$first_tab] .= self::render_generator_content_field($shortcode);
			}

		}
		$return .= self::render_generator_tabs($tabs);
		$return .= self::render_generator_tab_panels($tabs, $tab_panels);
		$return .= '</div>';
		$return .= '<div class="su-generator-preview-panel"><div id="su-generator-preview"></div></div>';
		$return .= '</div>';
		$return .= '<div class="su-generator-actions su-generator-clearfix">' . implode(' ', array_values($actions)) . '</div>';
		echo $return;
		exit;
	}


/** Function ajax_add_preset() called by wp_ajax hooks: {'su_generator_add_preset'} **/
/** Parameters found in function ajax_add_preset(): {"post": ["id", "name", "settings", "shortcode", "nonce"]} **/
function ajax_add_preset()
	{
		self::access();
		// Check incoming data
		if (empty($_POST['id']))
			return;
		if (empty($_POST['name']))
			return;
		if (empty($_POST['settings']))
			return;
		if (empty($_POST['shortcode']))
			return;
		// Check Nonce
		if (
			empty($_POST['nonce']) ||
			!is_string($_POST['nonce']) ||
			!wp_verify_nonce($_POST['nonce'], 'su_generator_preset')
		) {
			return;
		}
		// Clean-up incoming data
		$id = sanitize_key($_POST['id']);
		$name = sanitize_text_field($_POST['name']);
		$shortcode = sanitize_key($_POST['shortcode']);
		// Validate and sanitize settings
		$settings = is_array($_POST['settings']) ? stripslashes_deep($_POST['settings']) : array();
		$settings = array_map('wp_kses_post', $settings);
		// Prepare option name
		$option = 'su_presets_' . $shortcode;
		// Get the existing presets
		$current = get_option($option);
		// Create array with new preset
		$new = array(
			'id' => $id,
			'name' => $name,
			'settings' => $settings,
		);
		// Add new array to the option value
		if (!is_array($current))
			$current = array();
		$current[$id] = $new;
		// Save updated option
		update_option($option, $current);
		// Clear cache
		delete_transient('su/generator/settings/' . $shortcode);
	}


