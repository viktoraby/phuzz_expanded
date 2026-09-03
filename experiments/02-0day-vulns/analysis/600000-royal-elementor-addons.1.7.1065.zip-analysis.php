<?php
/***
*
*Found actions: 100
*Found functions:69
*Extracted functions:69
*Total parameter names extracted: 67
*Overview: {'count_wishlist_items': {'count_wishlist_items', 'nopriv_count_wishlist_items'}, 'wpr_save_mega_menu_settings': {'wpr_save_mega_menu_settings'}, 'wpr_rating_maybe_later': {'wpr_rating_maybe_later'}, 'dismiss_notice_ajax_callback': {'fs_dismiss_notice_action_{$ajax_action_suffix}'}, 'get_dependent_terms': {'nopriv_wpr_get_dependent_terms', 'wpr_get_dependent_terms'}, 'wpr_sale_remind_me_later': {'wpr_sale_remind_me_later'}, 'wpr_rating_already_rated': {'wpr_rating_already_rated'}, 'count_compare_items': {'nopriv_count_compare_items', 'count_compare_items'}, 'wpr_grid_filters_ajax': {'nopriv_wpr_grid_filters_ajax', 'wpr_grid_filters_ajax'}, 'wpr_form_builder_mailchimp': {'nopriv_wpr_form_builder_mailchimp', 'wpr_form_builder_mailchimp'}, 'render_library_templates_pages_grid_items': {'render_library_templates_pages_grid_items'}, 'render_library_templates_blocks': {'render_library_templates_blocks'}, 'wpr_get_filtered_count_posts': {'nopriv_wpr_get_filtered_count_posts', 'wpr_get_filtered_count_posts'}, 'data_fetch': {'nopriv_wpr_data_fetch'}, 'handle_file_upload': {'nopriv_wpr_addons_upload_file', 'wpr_addons_upload_file'}, 'wpr_likes_init': {'wpr_likes_init', 'nopriv_wpr_likes_init'}, 'update_mini_wishlist': {'update_mini_wishlist', 'nopriv_update_mini_wishlist'}, 'wpr_backend_widget_search_query_results': {'wpr_backend_widget_search_query_results'}, 'wpr_filter_grid_media': {'nopriv_wpr_filter_grid_media', 'wpr_filter_grid_media'}, 'wpr_backend_freepro_search_query_results': {'wpr_backend_freepro_search_query_results'}, 'wpr_get_filtered_count_products': {'nopriv_wpr_get_filtered_count_products', 'wpr_get_filtered_count_products'}, 'add_cart_single_product_ajax': {'wpr_addons_add_cart_single_product', 'nopriv_wpr_addons_add_cart_single_product'}, 'wpr_pro_features_dismiss_notice': {'wpr_pro_features_dismiss_notice'}, 'send_email': {'nopriv_wpr_form_builder_email'}, 'wpr_verify_recaptcha': {'wpr_verify_recaptcha', 'nopriv_wpr_verify_recaptcha'}, 'wpr_get_media_filtered_count': {'nopriv_wpr_get_media_filtered_count', 'wpr_get_media_filtered_count'}, 'wpr_rating_dismiss_notice': {'wpr_rating_dismiss_notice'}, 'wpr_load_more_instagram_posts_function': {'wpr_load_more_instagram_posts', 'nopriv_wpr_load_more_instagram_posts'}, 'check_product_in_compare': {'nopriv_check_product_in_compare', 'check_product_in_compare'}, 'check_product_in_wishlist_grid': {'check_product_in_wishlist_grid', 'nopriv_check_product_in_wishlist_grid'}, 'ajax_ppc_verify_password': {'nopriv_wpr_ppc_verify_password', 'wpr_ppc_verify_password'}, 'check_product_in_wishlist': {'check_product_in_wishlist', 'nopriv_check_product_in_wishlist'}, 'send_webhook': {'wpr_form_builder_webhook', 'nopriv_wpr_form_builder_webhook'}, 'wpr_install_activate_backup_plugin': {'wpr_install_activate_backup_plugin'}, 'add_to_compare': {'nopriv_add_to_compare', 'add_to_compare'}, 'update_mini_compare': {'update_mini_compare', 'nopriv_update_mini_compare'}, 'wpr_search_query_results': {'wpr_search_query_results'}, 'wpr_create_mega_menu_template': {'wpr_create_mega_menu_template'}, 'wpr_plugin_update_dismiss_notice': {'wpr_plugin_update_dismiss_notice'}, 'wpr_load_more_tweets_function': {'wpr_load_more_tweets', 'nopriv_wpr_load_more_tweets'}, 'remove_from_wishlist': {'remove_from_wishlist', 'nopriv_remove_from_wishlist'}, 'wpr_get_page_content': {'wpr_get_page_content', 'nopriv_wpr_get_page_content'}, 'wpr_import_library_template': {'wpr_import_library_template'}, 'wpr_rating_need_help': {'wpr_rating_need_help'}, 'wpr_fix_royal_compatibility': {'wpr_fix_royal_compatibility'}, 'wpr_save_template_conditions': {'wpr_save_template_conditions'}, 'add_to_submissions': {'nopriv_wpr_form_builder_submissions'}, 'ajax_mailchimp_subscribe': {'nopriv_mailchimp_subscribe', 'mailchimp_subscribe'}, 'render_library_templates_popups': {'render_library_templates_popups'}, 'get_custom_meta_keys': {'nopriv_wpr_get_custom_meta_keys'}, 'wpr_update_form_action_meta': {'wpr_update_form_action_meta', 'nopriv_wpr_update_form_action_meta'}, 'wpr_delete_template': {'wpr_delete_template'}, 'wpr_activate_required_theme': {'wpr_activate_required_theme'}, 'wpr_import_templates_kit': {'wpr_import_templates_kit'}, 'wpr_final_settings_setup': {'wpr_final_settings_setup'}, 'wpr_create_template': {'wpr_create_template'}, 'remove_from_compare': {'nopriv_remove_from_compare', 'remove_from_compare'}, 'wpr_activate_required_plugins': {'wpr_activate_required_plugins'}, 'render_library_templates_pages': {'render_library_templates_pages'}, '_toggle_debug_mode': {'fs_toggle_debug_mode'}, 'wpr_set_pending_template': {'wpr_set_pending_template'}, 'wpr_plugin_sale_dismiss_notice': {'wpr_plugin_sale_dismiss_notice'}, 'wpr_dismiss_backup_popup': {'wpr_dismiss_backup_popup'}, 'wpr_reset_previous_import': {'wpr_reset_previous_import'}, 'check_product_in_compare_grid': {'check_product_in_compare_grid', 'nopriv_check_product_in_compare_grid'}, 'render_library_templates_sections': {'render_library_templates_sections'}, 'wpr_install_news_magazine_x_theme': {'wpr_install_news_magazine_x_theme'}, 'add_to_wishlist': {'add_to_wishlist', 'nopriv_add_to_wishlist'}, 'wpr_woo_grid_filters_ajax': {'nopriv_wpr_woo_grid_filters_ajax', 'wpr_woo_grid_filters_ajax'}}
*
***/

/** Function count_wishlist_items() called by wp_ajax hooks: {'count_wishlist_items', 'nopriv_count_wishlist_items'} **/
/** Parameters found in function count_wishlist_items(): {"post": ["nonce", "element_addcart_simple_txt", "element_addcart_grouped_txt", "element_addcart_variable_txt"]} **/
function count_wishlist_items() {
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'wpr-addons-js' ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Security check failed.', 'wpr-addons' ) ) );
        }
        $user_id = get_current_user_id();
        
        if ($user_id > 0) {
            $wishlist = get_user_meta($user_id, 'wpr_wishlist', true);
            if (!$wishlist) {
                $wishlist = array();
            }
        } else {
            $wishlist = $this->get_wishlist_from_cookie();
        }

        $product_data = [];

       $product_data['wishlist_count'] = sizeof($wishlist);
       $wishlist_product_array = [];

        $settings = [
            'element_addcart_simple_txt' => isset($_POST['element_addcart_simple_txt']) ? wp_kses_post( wp_unslash( $_POST['element_addcart_simple_txt'] ) ) : '', 
            'element_addcart_grouped_txt' => isset($_POST['element_addcart_grouped_txt']) ? wp_kses_post( wp_unslash( $_POST['element_addcart_grouped_txt'] ) ) : '', 
            'element_addcart_variable_txt' => isset($_POST['element_addcart_variable_txt']) ? wp_kses_post( wp_unslash( $_POST['element_addcart_variable_txt'] ) ) : ( isset($_POST['element_addcart_grouped_txt']) ? wp_kses_post( wp_unslash( $_POST['element_addcart_grouped_txt'] ) ) : '' )
        ];
            
        foreach ($wishlist as $product_id) {
            $product = wc_get_product($product_id);

            if ($product) {
                $wishlist_product_array[] = [
                    'product_id' => $product_id,
                    'product_image' => $product->get_image(),
                    'product_title' => $product->get_title(),
                    'product_url' => $product->get_permalink(),
                    'product_price' => $product->get_price_html(),
                    'product_stock' => $product->get_stock_status() == 'instock' ? esc_html__('In Stock', 'wpr-addons') : esc_html__('Out of Stock', 'wpr-addons'),
                    'product_atc' => $this->render_product_add_to_cart($settings, $product)
                ];
            }
        }

        $product_data['wishlist_items'] = $wishlist_product_array;

       wp_send_json($product_data);

       wp_die();
    }


/** Function wpr_save_mega_menu_settings() called by wp_ajax hooks: {'wpr_save_mega_menu_settings'} **/
/** Parameters found in function wpr_save_mega_menu_settings(): {"post": ["nonce", "item_id", "item_settings"]} **/
function wpr_save_mega_menu_settings() {

    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'wpr-mega-menu-js' ) || ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( [ 'message' => 'Invalid request.' ] );
        return;
    }

    if ( ! isset( $_POST['item_id'] ) || ! isset( $_POST['item_settings'] ) || ! is_array( $_POST['item_settings'] ) ) {
        wp_send_json_error( [ 'message' => 'Missing item_id or item_settings.' ] );
        return;
    }

    $item_id = absint( $_POST['item_id'] );
    $item_post = get_post( $item_id );
    if ( ! $item_post || $item_post->post_type !== 'nav_menu_item' ) {
        wp_send_json_error( [ 'message' => 'Invalid menu item.' ] );
        return;
    }

    $allowed_keys = wpr_mega_menu_allowed_settings_keys();
    $raw         = wp_unslash( $_POST['item_settings'] );
    $sanitized   = [];
    foreach ( $allowed_keys as $key ) {
        if ( array_key_exists( $key, $raw ) ) {
            $sanitized[ $key ] = wpr_sanitize_mega_menu_setting( $raw[ $key ], $key );
        }
    }

    update_post_meta( $item_id, 'wpr-mega-menu-settings', $sanitized );
    wp_send_json_success( $sanitized );
}


/** Function wpr_rating_maybe_later() called by wp_ajax hooks: {'wpr_rating_maybe_later'} **/
/** Parameters found in function wpr_rating_maybe_later(): {"post": ["nonce"]} **/
function wpr_rating_maybe_later() {
		$nonce = $_POST['nonce'];

		if ( !wp_verify_nonce( $nonce, 'wpr-plugin-notice-js')  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

        update_option( 'wpr_maybe_later_time', strtotime('now') );
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


/** Function get_dependent_terms() called by wp_ajax hooks: {'nopriv_wpr_get_dependent_terms', 'wpr_get_dependent_terms'} **/
/** Parameters found in function get_dependent_terms(): {"post": ["taxonomy", "parent_term", "related_taxonomy", "tax_array", "parent_terms"]} **/
function get_dependent_terms() {

		if ( empty($_POST['taxonomy']) || empty($_POST['parent_term']) ) {
			wp_send_json_error('Missing data');
		}

		$taxonomy    = sanitize_text_field($_POST['taxonomy']);
		$parent_raw  = sanitize_text_field($_POST['parent_term']);

		// Determine if parent_term is ID or slug
		if ( is_numeric($parent_raw) ) {
			$related_term = get_term(intval($parent_raw));
		} else {
			// Optional: detect the related taxonomy (requires an extra POST param or default)
			$related_taxonomy = sanitize_text_field($_POST['related_taxonomy'] ?? '');
			if ( empty($related_taxonomy) ) {
				wp_send_json_error('Missing related taxonomy for slug');
			}
			$related_term = get_term_by('slug', $parent_raw, $related_taxonomy);
		}

		if ( ! $related_term || is_wp_error($related_term) ) {
			wp_send_json_error('Invalid parent term');
		}

		$related_taxonomy = $related_term->taxonomy;
		$tax_array = [];

		if ( isset($_POST['tax_array']) ) {
			$related_taxonomies = $_POST['tax_array'];
			$related_terms = $_POST['parent_terms'];

			// Add relation AND
			$tax_array['relation'] = 'AND';

			foreach ( $related_taxonomies as $index => $tax ) {
				if ( isset($related_terms[$index]) && $related_terms[$index] !== '' ) {
					$tax_array[] = [
						'taxonomy' => sanitize_text_field($tax),
						'field'    => 'term_id',
						'terms'    => intval($related_terms[$index]),
					];
				}
			}
		} else {
			$tax_array[] = [
					'taxonomy' => $related_taxonomy,
					'field'    => 'term_id',
					'terms'    => $related_term->term_id,
			];
		}

		// Get all posts with the related term
		$posts = get_posts([
			'post_type'      => 'any',
			'posts_per_page' => -1,
			'tax_query'      => $tax_array,
			'fields' => 'ids',
		]);

		if ( empty($posts) ) {
			wp_send_json_success([]);
		}

		// Get all terms from the target taxonomy used in these posts
		$terms = wp_get_object_terms($posts, $taxonomy, [
			'hide_empty' => true,
		]);

		$options = [];
		foreach ( $terms as $term ) {
			$options[] = [
				'id' => $term->term_id,
				'name' => $term->name,
				// 'posts' => $posts,
				// 'related_tax' => $related_taxonomy,
				// 'related_term' => $related_term->slug,
				// 'taxonomy' => $taxonomy,
			];
		}

		wp_send_json_success($options);
	}


/** Function wpr_sale_remind_me_later() called by wp_ajax hooks: {'wpr_sale_remind_me_later'} **/
/** Parameters found in function wpr_sale_remind_me_later(): {"post": ["nonce"]} **/
function wpr_sale_remind_me_later() {
		$nonce = $_POST['nonce'];

		if ( !wp_verify_nonce( $nonce, 'wpr-plugin-notice-js')  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

        update_option( 'wpr_sale_remind_me_later', absint(intval(strtotime('now'))) );
    }


/** Function wpr_rating_already_rated() called by wp_ajax hooks: {'wpr_rating_already_rated'} **/
/** Parameters found in function wpr_rating_already_rated(): {"post": ["nonce"]} **/
function wpr_rating_already_rated() {
		$nonce = $_POST['nonce'];

		if ( !wp_verify_nonce( $nonce, 'wpr-plugin-notice-js')  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

        update_option( 'wpr_rating_already_rated' , true );
    }


/** Function count_compare_items() called by wp_ajax hooks: {'nopriv_count_compare_items', 'count_compare_items'} **/
/** Parameters found in function count_compare_items(): {"post": ["nonce"]} **/
function count_compare_items() {
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'wpr-addons-js' ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Security check failed.', 'wpr-addons' ) ) );
        }
        $user_id = get_current_user_id();
        
        if ($user_id > 0) {
            $compare = get_user_meta($user_id, 'wpr_compare', true);
            if (!$compare) {
                $compare = array();
            }
        } else {
            $compare = $this->get_compare_from_cookie();
        }

        $product_data = [];

       $product_data['compare_count'] = sizeof($compare);
       $product_data['compare_table'] = $this->compare_table();

       wp_send_json($product_data);

       wp_die();
    }


/** Function wpr_grid_filters_ajax() called by wp_ajax hooks: {'nopriv_wpr_grid_filters_ajax', 'wpr_grid_filters_ajax'} **/
/** Parameters found in function wpr_grid_filters_ajax(): {"post": ["nonce", "grid_settings"]} **/
function wpr_grid_filters_ajax() {
		$nonce = $_POST['nonce'];

		if (!isset($nonce) || !wp_verify_nonce($nonce, 'wpr-addons-js')) {
			wp_send_json_error(array(
				'message' => esc_html__('Security check failed.', 'wpr-addons'),
			));
		}

		$settings = isset( $_POST['grid_settings'] ) && is_array( $_POST['grid_settings'] ) ? $_POST['grid_settings'] : array();
		if ( ! WPR_Grid_Helpers::is_allowed_grid_query_post_type( $settings ) ) {
			wp_send_json_error(array(
				'message' => esc_html__('Security check failed.', 'wpr-addons'),
			));
		}

		$start = microtime(true);
		// Get Settings
	
		// Create a unique cache key based on the settings
		$cache_key = 'wpr_grid_filters_' . md5(serialize(WPR_Grid_Helpers::get_main_query_args($settings, [])));
		// wp_send_json_success($cache_key);
		// wp_die();
	
		// Try to get cached data
		// $cached_data = get_transient($cache_key);
		
		// if ($cached_data !== false) {
		// 	$end = microtime(true);
		// 	$duration = round(($end - $start) * 1000, 2); // in ms
		// 	wp_send_json_success([
		// 		'output' => $cached_data,
		// 		'duration' => $duration
		// 	]);
		// 	wp_die();
		// }
	
		// Start output buffering to capture the HTML output
		ob_start();
	
		// Get Posts
		$posts = new \WP_Query(WPR_Grid_Helpers::get_main_query_args($settings, []));
	
		// Loop: Start
		if ($posts->have_posts()) :
	
			while ($posts->have_posts()) : $posts->the_post();
	
				// Post Class
				$post_class = implode(' ', get_post_class('wpr-grid-item elementor-clearfix', get_the_ID()));
	
				// Grid Item
				echo '<article class="' . esc_attr($post_class) . '">';
	
				// Password Protected Form
				WPR_Grid_Helpers::render_password_protected_input($settings);
	
				// Inner Wrapper
				echo '<div class="wpr-grid-item-inner">';
	
				// Content: Above Media
				WPR_Grid_Helpers::get_elements_by_location('above', $settings, get_the_ID());
	
				// Media
				if (has_post_thumbnail()) {
					echo '<div class="wpr-grid-media-wrap' . esc_attr(WPR_Grid_Helpers::get_image_effect_class($settings)) . '" data-overlay-link="' . esc_attr($settings['overlay_post_link']) . '">';
					// Post Thumbnail
					WPR_Grid_Helpers::render_post_thumbnail($settings, get_the_ID());
	
					// Media Hover
					echo '<div class="wpr-grid-media-hover wpr-animation-wrap">';
					// Media Overlay
					WPR_Grid_Helpers::render_media_overlay($settings);
	
					// Content: Over Media
					WPR_Grid_Helpers::get_elements_by_location('over', $settings, get_the_ID());
	
					echo '</div>';
					echo '</div>';
				}
	
				// Content: Below Media
				WPR_Grid_Helpers::get_elements_by_location('below', $settings, get_the_ID());
	
				echo '</div>'; // End .wpr-grid-item-inner
	
				echo '</article>'; // End .wpr-grid-item
	
			endwhile;
	
			// reset
			wp_reset_postdata();
	
		// Loop: End
		else :

			if ( 'dynamic' === $settings['query_selection'] || 'current' === $settings['query_selection'] ) {
				echo '<h2>'. esc_html($settings['query_not_found_text']) .'</h2>';
			}
			
		endif;

		// Get the buffered content
		$output = ob_get_clean();
	
		// Cache the output
		// set_transient($cache_key, $output, HOUR_IN_SECONDS);
	
		// Return the output
		$end = microtime(true);
		$duration = round(($end - $start) * 1000, 2); // in ms
		wp_send_json_success([
			'output' => $output,
			'duration' => $duration,
			'found_posts' => $posts->found_posts,
			'post_count' => $posts->post_count,
		]);

		wp_die();
	}


/** Function wpr_form_builder_mailchimp() called by wp_ajax hooks: {'nopriv_wpr_form_builder_mailchimp', 'wpr_form_builder_mailchimp'} **/
/** Parameters found in function wpr_form_builder_mailchimp(): {"post": ["nonce", "listId", "form_data"]} **/
function wpr_form_builder_mailchimp() {

        $nonce = $_POST['nonce'];

        if ( !wp_verify_nonce( $nonce, 'wpr-addons-js' ) ) {
            return; // Get out of here, the nonce is rotten!
        }
		
		// API Key
        $api_key = get_option('wpr_mailchimp_api_key') ? get_option('wpr_mailchimp_api_key') : '';
        
        // Validate API key format
        if (!preg_match('/^[0-9a-f]{32}-[a-z]{2}[0-9]{1,2}$/', $api_key)) {
            wp_send_json_error('Invalid API key format');
            return;
        }

        $api_parts = explode('-', $api_key);
        if (count($api_parts) !== 2) {
            wp_send_json_error('Invalid API key format');
            return;
        }

        // Validate datacenter suffix
        $api_key_suffix = $api_parts[1];
        if (!preg_match('/^[a-z]{2}[0-9]{1,2}$/', $api_key_suffix)) {
            wp_send_json_error('Invalid API datacenter');
            return;
        }

        // List ID with sanitization
        $list_id = isset($_POST['listId']) ? sanitize_text_field(wp_unslash($_POST['listId'])) : '';

        // Get Available Fields (PHPCS - fields are sanitized later on input)
        $fields = isset($_POST['form_data']) ? $_POST['form_data'] : []; // phpcs:ignore

        $group_ids = isset($fields['group_id']) ? array_map('sanitize_text_field', array_map('trim', explode(',', wp_unslash($fields['group_id'])))) : [];

        // Merge Additional Fields
        $merge_fields = [
            'FNAME' => !empty( $fields['first_name_field'] ) ? sanitize_text_field($fields['first_name_field']) : '',
            'LNAME' => !empty( $fields['last_name_field'] ) ? sanitize_text_field($fields['last_name_field']) : '',
			'PHONE' => !empty ( $fields['phone_field'] ) ? sanitize_text_field($fields['phone_field']) : '',
			'BIRTHDAY' => !empty ( $fields['birthday_field'] ) ? sanitize_text_field($fields['birthday_field']) : '',
		];

		$requiredKeys = ['address_field', 'country_field', 'city_field', 'state_field', 'zip_field'];
		
		if ( !empty(array_intersect_key($fields, array_flip($requiredKeys))) ) {
			$merge_fields = array_merge($merge_fields, [
				'ADDRESS' => [
					'addr1' => !empty ( $fields['address_field'] ) ? sanitize_text_field($fields['address_field']) : 'none',
					'country' =>  !empty ( $fields['country_field'] ) ? sanitize_text_field($fields['country_field']) : 'none',
					'city' => !empty ( $fields['city_field'] ) ? sanitize_text_field($fields['city_field']) : 'none',
					'state' => !empty ( $fields['state_field'] ) ? sanitize_text_field($fields['state_field']) : 'none',
					'zip' =>!empty ( $fields['zip_field'] ) ? sanitize_text_field($fields['zip_field']) : 'none',
				]
			]);
		}

        // API URL
        $api_url = 'https://'. $api_key_suffix .'.api.mailchimp.com/3.0/lists/'. $list_id .'/members/'. md5(strtolower(sanitize_text_field($fields['email_field'])));
		
		$api_body = [
			'email_address' => sanitize_text_field($fields[ 'email_field' ]),
			'status' => 'subscribed',
			'merge_fields' => $merge_fields
		];
			
		if ( !empty($group_ids) ) {
			$api_body['interests'] = self::group_ids_to_interests_array($group_ids);
		}

        // API Args
        $api_args = [
			'method' => 'PUT',
			'headers' => [
				'Content-Type' => 'application/json',
				'Authorization' => 'apikey '. $api_key,
			],
			'body' => json_encode($api_body),
        ];

        // Send Request
        $request = wp_remote_post( $api_url, $api_args );

		if ( ! is_wp_error($request) ) {
			$request = json_decode( wp_remote_retrieve_body($request) );

			// Set Status
			if ( ! empty($request) ) {
				if ($request->status == 'subscribed') {

					wp_send_json_success(array(
						'action' => 'wpr_form_builder_mailchimp',
						'status' => 'success',
						'message' => 'Mailchimp subscription was successful',
						'request' => $request
					));

				} else {
					wp_send_json_error([ 
						'action' => 'wpr_form_builder_mailchimp',
						'status' => 'error',
						'message' => 'Mailchimp subscription failed',
						'request' => $request
					]);
				}
			}
		} else {

			wp_send_json_error([ 
				'action' => 'wpr_form_builder_mailchimp',
				'status' => 'error',
				'message' => 'Mailchimp subscription failed',
				'request' => $request
			]);
		}
	}


/** Function render_library_templates_pages_grid_items() called by wp_ajax hooks: {'render_library_templates_pages_grid_items'} **/
/** Parameters found in function render_library_templates_pages_grid_items(): {"post": ["page"]} **/
function render_library_templates_pages_grid_items() {
		$kits = WPR_Templates_Data::get_available_kits_for_pages();

		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$kits_per_page = 10; // should be set to 10
		
		$start = ($page - 1) * $kits_per_page;
		$end = $start + $kits_per_page;
		
		$kits = array_slice($kits, $start, $kits_per_page, true);
		
		foreach( $kits as $kit => $data ) :
			
			foreach( $data['pages'] as $key => $page ) :

				$template_title = $data['name'] .' - '. str_replace('-', ' ', ucwords($page));
				$template_price = ('pro' === $data['price'] && (!defined('WPR_ADDONS_PRO_VERSION') || !wpr_fs()->can_use_premium_code()) ) ? 'pro' : 'free';
				$template_class = 'pro' === $template_price ? ' wpr-tplib-pro-wrap' : '';
				$preview_url = $data['preview'][$key];

		?>

			<div class="wpr-tplib-template-wrap<?php echo esc_attr($template_class); ?>" style="position:absolute;top:9999999999px;" data-title="<?php echo esc_attr(strtolower($template_title)); ?>" data-price="<?php echo esc_attr($template_price); ?>">
				<div class="wpr-tplib-template" data-slug="<?php echo esc_attr($page); ?>" data-kit="<?php echo esc_attr($kit); ?>" data-preview-type="iframe" data-preview-url="<?php echo esc_attr($preview_url); ?>">
					<div class="wpr-tplib-template-media">
						<img class="lazy" src="<?php echo esc_url(WPR_ADDONS_ASSETS_URL .'img/icon-256x256.png'); ?>" data-src="<?php echo esc_url('https://royal-elementor-addons.com/library/templates-kit/'. $kit .'/'. $page .'.jpg'); ?>">
						<div class="wpr-tplib-template-media-overlay">
							<i class="eicon-eye"></i>
						</div>
					</div>
					<div class="wpr-tplib-template-footer elementor-clearfix">
						<h3>
							<span><?php echo esc_html($data['name']) ?></span>
							<span><?php echo '&nbsp;- '. esc_html(ucwords($page)); ?></span>
						</h3>

						<?php if ( 'pro' === $data['price'] && (!defined('WPR_ADDONS_PRO_VERSION') || !wpr_fs()->can_use_premium_code()) ) : ?>
							<span class="wpr-tplib-insert-template wpr-tplib-insert-pro"><i class="eicon-star"></i> <span><?php esc_html_e( 'Go Pro', 'wpr-addons' ); ?></span></span>
						<?php else : ?>
							<span class="wpr-tplib-insert-template"><i class="eicon-file-download"></i> <span><?php esc_html_e( 'Insert', 'wpr-addons' ); ?></span></span>
						<?php endif; ?>
					</div>
				</div>
			</div>

		<?php endforeach;

		endforeach;

		wp_die();
	}


/** Function render_library_templates_blocks() called by wp_ajax hooks: {'render_library_templates_blocks'} **/
/** No params detected :-/ **/


/** Function wpr_get_filtered_count_posts() called by wp_ajax hooks: {'nopriv_wpr_get_filtered_count_posts', 'wpr_get_filtered_count_posts'} **/
/** Parameters found in function wpr_get_filtered_count_posts(): {"post": ["nonce", "grid_settings", "wpr_url_params"]} **/
function wpr_get_filtered_count_posts() {
		$nonce = $_POST['nonce'];

		if (!isset($nonce) || !wp_verify_nonce($nonce, 'wpr-addons-js')) {
			wp_send_json_error(array(
				'message' => esc_html__('Security check failed.', 'wpr-addons'),
			));
		}

		if ( ! empty( $_POST['grid_settings'] ) && ! WPR_Grid_Helpers::is_allowed_grid_query_post_type( $_POST['grid_settings'] ) ) {
			wp_send_json_error(array(
				'message' => esc_html__('Security check failed.', 'wpr-addons'),
			));
		}

		if ( isset($_POST['wpr_url_params']) ) {
			$results = [];
		
			// Loop through each set of parameters
			foreach ($_POST['wpr_url_params'] as $params) {
				$query_args = WPR_Grid_Helpers::get_main_query_args($_POST['grid_settings'], $params);
				$query = new \WP_Query($query_args);
		
				// Add the count of found posts to the results array
				$results[] = [
					'found_posts' => $query->found_posts,
					'post_count' => $query->post_count,
				];
		
				wp_reset_postdata();
			}
		
			// Send the array of results
			wp_send_json_success($results);
		} else if ( isset($_POST['grid_settings']) ) {
			$settings = $_POST['grid_settings'];
			$page_count =  WPR_Grid_Helpers::get_max_num_pages( $settings );
		
			wp_send_json_success([
				'page_count' => $page_count,
			]);
		}
		
		wp_die();
	}


/** Function data_fetch() called by wp_ajax hooks: {'nopriv_wpr_data_fetch'} **/
/** Parameters found in function data_fetch(): {"post": ["nonce", "wpr_show_attachments", "wpr_category", "wpr_option_post_type", "wpr_query_type", "wpr_taxonomy_type", "wpr_exclude_without_thumb", "wpr_show_ps_pt", "wpr_number_of_results", "wpr_keyword", "wpr_search_results_offset", "wpr_meta_query", "wpr_meta_keys", "ajax_search_img_size", "wpr_show_ajax_thumbnail", "wpr_ajax_search_link_target", "ajax_search_link_target", "ajax_search_image_size", "wpr_show_description", "wpr_number_of_words", "wpr_show_product_price", "wpr_show_view_result_btn", "wpr_view_result_text", "wpr_no_results"]} **/
function data_fetch() {

        $nonce = $_POST['nonce'];

        if ( !wp_verify_nonce( $nonce, 'wpr-addons-js' ) ) {
            return; // Get out of here, the nonce is rotten!
        }

		$all_post_types = [];

        if ( sanitize_text_field($_POST['wpr_show_attachments']) == 'yes' ) {
            $all_post_types = ['attachment'];
        }

        foreach(  Utilities::get_custom_types_of( 'post', false ) as $key=>$value ) {
            array_push($all_post_types, $key);
        }
        
        $tax_query = '';

        if ( $_POST['wpr_category'] != false && $_POST['wpr_category'] != '' ) {   
                $tax_query = array(
                    array(
                        'taxonomy' => $_POST['wpr_option_post_type'],
                        'field'    => 'term_id',
                        'terms'    => sanitize_text_field($_POST['wpr_category']),
                    ),
                );

            // if ( isset($_POST['wpr_option_post_type']) && !empty($_POST['wpr_option_post_type']) ) {
            //     $tax_query = array(
            //         array(
            //             'taxonomy' => $_POST['wpr_option_post_type'],
            //             'field'    => 'term_id',
            //             'terms'    => sanitize_text_field($_POST['wpr_category']),
            //         ),
            //     );
            // } else {
            //     $tax_query = array(
            //         array(
            //             'taxonomy' => $_POST['wpr_query_type'] == 'product' ? $_POST['wpr_query_type'] . '_cat' : 'category',
            //             'field'    => 'term_id',
            //             'terms'    => sanitize_text_field($_POST['wpr_category']),
            //         ),
            //     );
            // }
        } else if ( $_POST['wpr_category'] == 0 && $_POST['wpr_query_type'] != 'all' ) {
            if ( !empty($_POST['wpr_option_post_type']) ) {
                $tax_query = array(
                    array(
                        'taxonomy' => $_POST['wpr_option_post_type'],
                        'field'    => 'term_id',
                        'terms'    => sanitize_text_field($_POST['wpr_category']),
                    ),
                );
            } else { 
                // Get the string from the POST data
                $taxonomy_type_string = $_POST['wpr_taxonomy_type'];
            
                // Check if the string contains spaces
                if (strpos($taxonomy_type_string, ' ') !== false) {
                    // Split the string into an array based on spaces
                    $taxonomy_types = explode(' ', $taxonomy_type_string);
            
                
                    $tax_query = [
                        'relation' => 'OR'
                    ];
                    
                    foreach( $taxonomy_types as $taxonomy_type ) {
                        array_push($tax_query, [
                            'taxonomy' => $taxonomy_type,
                            'operator'    => 'EXISTS'
                        ]);
                    }
                } else {
                    // If there are no spaces, leave it as a single-item array
                    $taxonomy_types = $taxonomy_type_string;

                    $tax_query = array(
                        array(
                            'taxonomy' => $_POST['wpr_taxonomy_type'],
                            'operator'    => 'EXISTS',
                        ),
                    );
                }
            } 
        }

        $can_view_protected_posts = current_user_can('read_private_posts');
        $meta_query = [];

        if ( 'yes' === sanitize_text_field( $_POST['wpr_exclude_without_thumb'] ) ) {
            $meta_query[] = [
                'key' => '_thumbnail_id',
            ];
        }
        
        if ( ( 'yes' !== sanitize_text_field($_POST['wpr_show_ps_pt'] ) ) || !$can_view_protected_posts ) {
            $args =
                [
                    'posts_per_page' => sanitize_text_field($_POST['wpr_number_of_results']), 
                    's' => sanitize_text_field( $_POST['wpr_keyword'] ),
                    'post_type' => $_POST['wpr_query_type'] === 'all' || (!defined('WPR_ADDONS_PRO_VERSION') || !wpr_fs()->can_use_premium_code())  ? $all_post_types : array( sanitize_text_field($_POST['wpr_query_type']) ),
                    'offset' => sanitize_text_field($_POST['wpr_search_results_offset']),
                    'meta_query' => $meta_query ?: '',
                    'tax_query' => $tax_query,
                    'post_status' => in_array('attachment', $all_post_types) ? ['publish', 'inherit'] : 'publish',
                    'post_password' => ''
                ];
        } else {
            $args =
                [
                    'posts_per_page' => sanitize_text_field($_POST['wpr_number_of_results']), 
                    's' => sanitize_text_field( $_POST['wpr_keyword'] ),
                    'post_type' => $_POST['wpr_query_type'] === 'all' || (!defined('WPR_ADDONS_PRO_VERSION') || !wpr_fs()->can_use_premium_code())  ? $all_post_types : array( sanitize_text_field($_POST['wpr_query_type']) ),
                    'offset' => sanitize_text_field($_POST['wpr_search_results_offset']),
                    'meta_query' => $meta_query ?: '',
                    'tax_query' => $tax_query,
                    'post_status' => in_array('attachment', $all_post_types) ? ['publish', 'inherit'] : 'publish',
                ];
        }

        $the_query = new \WP_Query( $args );

        // Fallback: search selected public meta keys only (never key-less / never _* keys).
        if ( ! $the_query->have_posts() && 'yes' === sanitize_text_field( wp_unslash( $_POST['wpr_meta_query'] ?? '' ) ) ) {
            $keyword = sanitize_text_field( wp_unslash( $_POST['wpr_keyword'] ?? '' ) );
            $meta_keys = self::sanitize_search_meta_keys( wp_unslash( $_POST['wpr_meta_keys'] ?? '' ) );

            if ( '' !== $keyword && mb_strlen( $keyword ) >= 3 && ! empty( $meta_keys ) ) {
                $meta_query_or = [ 'relation' => 'OR' ];

                foreach ( $meta_keys as $meta_key ) {
                    $meta_query_or[] = [
                        'key'     => $meta_key,
                        'value'   => $keyword,
                        'compare' => 'LIKE',
                    ];
                }

                if ( 'yes' === sanitize_text_field( wp_unslash( $_POST['wpr_exclude_without_thumb'] ?? '' ) ) ) {
                    $meta_query_or = [
                        'relation' => 'AND',
                        [ 'key' => '_thumbnail_id' ],
                        $meta_query_or,
                    ];
                }

                $args['s'] = '';
                $args['meta_query'] = $meta_query_or;

                $the_query = new \WP_Query( $args );
            }
        }

        if( $the_query->have_posts() ) :
            $number_of_queried_posts = $the_query->found_posts;
            $post_count = 0;

                while( $the_query->have_posts() ) : $the_query->the_post();

                // if ( ( !has_post_thumbnail() && 'yes' === sanitize_text_field($_POST['wpr_exclude_without_thumb'])) ) : 
                //     continue;
                // endif;

                ob_start();
                // the_post_thumbnail(sanitize_text_field($_POST['ajax_search_img_size']));
                the_post_thumbnail('medium');
                $post_thumb = ob_get_clean();
                ?>

                <li data-number-of-results="<?php echo esc_attr( (string) $the_query->found_posts ); ?>">
                    
                    <?php if ( !post_password_required() || $can_view_protected_posts ) : ?>

                        <?php if ( 'yes' === sanitize_text_field($_POST['wpr_show_ajax_thumbnail']) ) :
                            if ( has_post_thumbnail() ) :
                                echo '<a class="wpr-ajax-img-wrap" target="' . esc_attr( sanitize_text_field( wp_unslash( $_POST['wpr_ajax_search_link_target'] ) ) ) . '" href="' . esc_url( get_the_permalink() ) . '">' . wp_kses_post( $post_thumb ) . '</a>';
                                // echo '<a class="wpr-ajax-img-wrap" target="'. sanitize_text_field($_POST['ajax_search_link_target']) .'" href="'. esc_url( get_the_permalink() ) .'">'.  '<img src="'. Group_Control_Image_Size::get_attachment_image_src( get_post_thumbnail_id(), 'ajax_search_image', [$_POST['ajax_search_image_size']] ) .'"/>' .'</a>';
                            else :
                                echo '<a class="wpr-ajax-img-wrap" target="' . esc_attr( sanitize_text_field( wp_unslash( $_POST['wpr_ajax_search_link_target'] ) ) ) . '" href="' . esc_url( get_the_permalink() ) . '"><img src="' . esc_url( Utils::get_placeholder_image_src() ) . '" alt=""></a>';
                            endif ;
                        endif ; ?>

                        <div class="wpr-ajax-search-content">
                            <a target="<?php echo esc_attr( sanitize_text_field( wp_unslash( $_POST['wpr_ajax_search_link_target'] ) ) ); ?>" class="wpr-ajax-title" href="<?php echo esc_url( get_the_permalink() ); ?>"><?php the_title(); ?></a>

                            <?php if ( sanitize_text_field($_POST['wpr_show_description']) == 'yes' ) : ?>
                                <p class="wpr-ajax-desc"><a target="<?php echo esc_attr( sanitize_text_field( wp_unslash( $_POST['wpr_ajax_search_link_target'] ) ) ); ?>" href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo esc_html( wp_trim_words( get_the_content(), (int) sanitize_text_field( wp_unslash( $_POST['wpr_number_of_words'] ) ) ) ); ?></a></p>
                            <?php endif; ?>

                            <?php if ( 'yes' === sanitize_text_field($_POST['wpr_show_product_price']) && 
                                    get_post_type() === 'product' && 
                                    class_exists('WooCommerce') ) :
                                $product = wc_get_product(get_the_ID());
                                if ($product) {
                                    $price_html = '<div class="wpr-search-product-price">'. $product->get_price_html() .'</div>';

                                    echo wp_kses_post( $price_html );
                                }
                            endif; ?>

                            <?php if ( sanitize_text_field($_POST['wpr_show_view_result_btn']) ) : ?>
                                <a target="<?php echo esc_attr( sanitize_text_field( wp_unslash( $_POST['wpr_ajax_search_link_target'] ) ) ); ?>" class="wpr-view-result" href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo esc_html( sanitize_text_field( wp_unslash( $_POST['wpr_view_result_text'] ) ) ); ?></a>
                            <?php endif; ?>
                        </div>
                    
                    <?php else: ?>

                        <?php if ( 'yes' === sanitize_text_field($_POST['wpr_show_ajax_thumbnail']) ) :
                            echo '<a class="wpr-ajax-img-wrap" target="' . esc_attr( sanitize_text_field( wp_unslash( $_POST['wpr_ajax_search_link_target'] ) ) ) . '" href="' . esc_url( get_the_permalink() ) . '"><img src="' . esc_url( Utils::get_placeholder_image_src() ) . '" alt=""></a>';
                        endif ; ?>

                        <div class="wpr-ajax-search-content">
                            <a target="<?php echo esc_attr( sanitize_text_field( wp_unslash( $_POST['wpr_ajax_search_link_target'] ) ) ); ?>" class="wpr-ajax-title" href="<?php echo esc_url( get_the_permalink() ); ?>"><?php the_title(); ?></a>
                        </div>
                    
                    <?php endif; ?>

                </li>
                <?php 
                $post_count++;
                endwhile;

            wp_reset_postdata();
            
        else :
            if (0 < sanitize_text_field($_POST['wpr_search_results_offset'])) {
            } else {
                echo '<p class="wpr-no-results">' . esc_html( sanitize_text_field( wp_unslash( $_POST['wpr_no_results'] ) ) ) . '</p>';
            }

        endif;
        
        die();
    }


/** Function handle_file_upload() called by wp_ajax hooks: {'nopriv_wpr_addons_upload_file', 'wpr_addons_upload_file'} **/
/** Parameters found in function handle_file_upload(): {"post": ["wpr_addons_nonce", "upload_token", "form_field_id", "triggering_event"], "files": ["uploaded_file"]} **/
function handle_file_upload() {
		// Public nonce — first line of defence only.
		if ( ! isset( $_POST['wpr_addons_nonce'] ) || ! wp_verify_nonce( $_POST['wpr_addons_nonce'], 'wpr-addons-js' ) ) {
			wp_send_json_error( ['message' => esc_html__( 'Security check failed.', 'wpr-addons' )], 403 );
		}

		// Per-render HMAC token: bound to post_id, field_id, allowed types, max size, expiry.
		$token         = isset( $_POST['upload_token'] ) ? sanitize_text_field( wp_unslash( $_POST['upload_token'] ) ) : '';
		$form_field_id = isset( $_POST['form_field_id'] ) ? sanitize_text_field( wp_unslash( $_POST['form_field_id'] ) ) : '';

		$payload = self::verify_upload_token( $token );
		if ( ! is_array( $payload ) || $form_field_id === '' || ! hash_equals( (string) ( $payload['f'] ?? '' ), $form_field_id ) ) {
			wp_send_json_error( ['message' => esc_html__( 'Permission denied.', 'wpr-addons' )], 403 );
		}

		// Verify the bound post is still viewable (skip for theme-builder/non-singular contexts where p=0).
		$bound_post_id = isset( $payload['p'] ) ? (int) $payload['p'] : 0;
		if ( $bound_post_id > 0 ) {
			$status = get_post_status( $bound_post_id );
			if ( $status && ! in_array( $status, ['publish', 'private'], true ) ) {
				wp_send_json_error( ['message' => esc_html__( 'Permission denied.', 'wpr-addons' )], 403 );
			}
		}

		// Per-IP rate limit.
		$ip_key = 'wpr_upload_rl_' . md5( (string) Utilities::get_client_ip() );
		$rate   = (int) get_transient( $ip_key );
		if ( $rate >= self::RATE_LIMIT_PER_IP ) {
			wp_send_json_error( ['message' => esc_html__( 'Too many requests.', 'wpr-addons' )], 429 );
		}
		set_transient( $ip_key, $rate + 1, self::RATE_LIMIT_WINDOW );

		// Server-trusted constraints from the signed token (ignore any client overrides).
		$max_file_size = isset( $payload['s'] ) && (float) $payload['s'] > 0
			? (float) $payload['s']
			: ( wp_max_upload_size() / pow( 1024, 2 ) ); // MB
		$allowed_file_types = isset( $payload['t'] ) ? (string) $payload['t'] : '';

		if ( ! isset( $_FILES['uploaded_file'] ) ) {
			if ( isset( $_POST['triggering_event'] ) && 'click' === $_POST['triggering_event'] ) {
				$upload_dir  = wp_upload_dir();
				$upload_path = $upload_dir['basedir'] . '/wpr-addons/forms';
				wp_mkdir_p( $upload_path );
				$this->harden_upload_dir( $upload_path );
			}
			wp_send_json_error( ['message' => esc_html__( 'No file was uploaded.', 'wpr-addons' )] );
		}

		$file = $_FILES['uploaded_file'];

		if ( ! empty( $file['error'] ) || empty( $file['tmp_name'] ) || ! is_uploaded_file( $file['tmp_name'] ) ) {
			wp_send_json_error( ['message' => esc_html__( 'Upload error.', 'wpr-addons' )] );
		}

		if ( $file['size'] > $max_file_size * 1024 * 1024 ) {
			wp_send_json_error([
				'cause'   => 'filesize',
				'sizes'   => [ $max_file_size * 1024 * 1024, $file['size'] ],
				'message' => 'File size exceeds the allowed limit.'
			]);
		}

		if ( ! $this->file_validity( $file, $allowed_file_types ) ) {
			wp_send_json_error([
				'cause'   => 'filetype',
				'message' => esc_html__( 'File type is not valid.', 'wpr-addons' )
			]);
		}

		// Validation-only round-trip (no move).
		if ( ! isset( $_POST['triggering_event'] ) || 'click' !== $_POST['triggering_event'] ) {
			wp_send_json_success( ['message' => esc_html__( 'File validation passed', 'wpr-addons' )] );
		}

		$upload_dir  = wp_upload_dir();
		$upload_path = $upload_dir['basedir'] . '/wpr-addons/forms';
		wp_mkdir_p( $upload_path );
		$this->harden_upload_dir( $upload_path );

		$safe_name = sanitize_file_name( $file['name'] );
		if ( $safe_name === '' ) {
			wp_send_json_error( ['message' => esc_html__( 'Invalid filename.', 'wpr-addons' )] );
		}
		$filename = wp_unique_filename( $upload_path, $safe_name );

		if ( move_uploaded_file( $file['tmp_name'], $upload_path . '/' . $filename ) ) {
			@chmod( $upload_path . '/' . $filename, 0644 );
			wp_send_json_success( ['url' => $upload_dir['baseurl'] . '/wpr-addons/forms/' . $filename] );
		}

		wp_send_json_error( ['message' => esc_html__( 'Failed to upload the file.', 'wpr-addons' )] );
	}


/** Function wpr_likes_init() called by wp_ajax hooks: {'wpr_likes_init', 'nopriv_wpr_likes_init'} **/
/** Parameters found in function wpr_likes_init(): {"request": ["nonce", "disabled", "post_id"]} **/
function wpr_likes_init() {
		// Security
		$nonce = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( wp_unslash($_REQUEST['nonce']) ) : 0;

		if ( ! wp_verify_nonce( $nonce, 'wpr-post-likes-nonce' ) ) {
			exit( esc_html__( 'Not permitted', 'wpr-addons' ) );
		}

		// Test if javascript is disabled
		$js_disabled = ( isset( $_REQUEST['disabled'] ) && $_REQUEST['disabled'] == true ) ? true : false;

		// Base variables
		$post_id = ( isset( $_REQUEST['post_id'] ) && is_numeric( $_REQUEST['post_id'] ) ) ? absint($_REQUEST['post_id']) : '';

		$post_users = NULL;
		$like_count = 0;

		// Init
		if ( $post_id != '' ) {
			// Likes Count
			$count = get_post_meta( $post_id, '_post_like_count', true );
			$count = ( isset( $count ) && is_numeric( $count ) ) ? $count : 0;

			// Like the Post
			if ( ! $this->already_liked( $post_id ) ) {
				// Logged-in User
				if ( is_user_logged_in() ) {
					$user_id = get_current_user_id();
					$post_users = $this->get_user_likes( $user_id, $post_id );

					// Get Like Count
					$user_like_count = get_user_option( '_user_like_count', $user_id );
					$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;

					// Update Like Count
					update_user_option( $user_id, '_user_like_count', ++$user_like_count );

					// Update Post
					if ( $post_users ) {
						update_post_meta( $post_id, '_user_liked', $post_users );
					}

				// Anonymous User
				} else {
					$post_users = $this->get_IP_likes( $this->get_IP(), $post_id );

					// Update Post
					if ( $post_users ) {
						update_post_meta( $post_id, '_user_IP', $post_users );
					}
				}

				// Send to JS
				$like_count = ++$count;
				$response['status'] = 'liked';

			// Unlike the Post
			} else {
				// Logged-in User
				if ( is_user_logged_in() ) {
					$user_id = get_current_user_id();
					$post_users = $this->get_user_likes( $user_id, $post_id );

					// Get Like Count
					$user_like_count = get_user_option( '_user_like_count', $user_id );
					$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;

					// Update Like Count
					if ( $user_like_count > 0 ) {
						update_user_option( $user_id, '_user_like_count', --$user_like_count );
					}

					// Update Post
					if ( $post_users ) {	
						$uid_key = array_search( $user_id, $post_users );
						unset( $post_users[$uid_key] );
						update_post_meta( $post_id, '_user_liked', $post_users );
					}

				// Anonymous User
				} else {
					$post_users = $this->get_IP_likes( $this->get_IP(), $post_id );

					// Update Post
					if ( $post_users ) {

						unset( $post_users[ array_search( $this->get_IP(), $post_users ) ] );
						update_post_meta( $post_id, '_user_IP', $post_users );
					}
				}

				// Send to JS
				$like_count = ( $count > 0 ) ? --$count : 0;
				$response['status'] = 'unliked';
			}

			// Update Post
			update_post_meta( $post_id, '_post_like_count', $like_count );
			update_post_meta( $post_id, '_post_like_modified', date( 'Y-m-d H:i:s' ) );

			// Send to JS
			$response['count'] = $this->get_like_count( $like_count );

			// JavaScript Disabled
			if ( $js_disabled == true ) {
				wp_redirect( get_permalink( $post_id ) );
				exit();
			} else {
				wp_send_json( $response );
			}
		}
	}


/** Function update_mini_wishlist() called by wp_ajax hooks: {'update_mini_wishlist', 'nopriv_update_mini_wishlist'} **/
/** Parameters found in function update_mini_wishlist(): {"post": ["nonce", "product_id"]} **/
function update_mini_wishlist() {
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'wpr-addons-js' ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Security check failed.', 'wpr-addons' ) ) );
        }
        if ( ! isset( $_POST['product_id'] ) ) {
            return;
        }
        $product_id = absint( $_POST['product_id'] );
        $user_id = get_current_user_id();

        
        if ($user_id > 0) {
            $wishlist = get_user_meta($user_id, 'wpr_wishlist', true);
            if (!$wishlist) {
                $wishlist = array();
            }
        } else {
            $wishlist = $this->get_wishlist_from_cookie();
        }

        $product = wc_get_product( $product_id );
        $product_data = [];
        if ( $product ) {
            $product_data['product_url'] = $product->get_permalink();
            $product_data['product_image'] = $product->get_image();
            $product_data['product_title'] = $product->get_title();
            $product_data['product_price'] = $product->get_price_html();
            $product_data['product_id'] = $product->get_id();
            $product_data['wishlist_count'] = sizeof($wishlist);
        }

       wp_send_json($product_data);

       wp_die();
    }


/** Function wpr_backend_widget_search_query_results() called by wp_ajax hooks: {'wpr_backend_widget_search_query_results'} **/
/** Parameters found in function wpr_backend_widget_search_query_results(): {"server": ["SERVER_NAME"], "post": ["search_query"]} **/
function wpr_backend_widget_search_query_results() {
    // Freemius OptIn
    if ( ! ( wpr_fs()->is_registered() && wpr_fs()->is_tracking_allowed()  || wpr_fs()->is_pending_activation() ) ) {
        return;
    }

    if ( strpos($_SERVER['SERVER_NAME'],'instawp') || strpos($_SERVER['SERVER_NAME'],'tastewp') ) {
		return;
	}

    $search_query = isset($_POST['search_query']) ? sanitize_text_field(wp_unslash($_POST['search_query'])) : '';

    wp_remote_post( 'http://reastats.kinsta.cloud/wp-json/backend-widget-search/data', [
        'body' => [
            'search_query' => $search_query
        ]
    ] );
}


/** Function wpr_filter_grid_media() called by wp_ajax hooks: {'nopriv_wpr_filter_grid_media', 'wpr_filter_grid_media'} **/
/** Parameters found in function wpr_filter_grid_media(): {"post": ["nonce", "grid_settings"]} **/
function wpr_filter_grid_media() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'wpr-addons-js' ) ) {
			wp_send_json_error( [
				'message' => esc_html__( 'Security check failed.', 'wpr-addons' ),
			] );
		}

		$settings = isset( $_POST['grid_settings'] ) && is_array( $_POST['grid_settings'] ) ? $_POST['grid_settings'] : [];

		$start = microtime( true );

		ob_start();

		$posts = self::render_posts_loop( $settings );

		if ( 0 === (int) $posts->found_posts && ! empty( $settings['query_not_found_text'] ) ) {
			echo '<h2>' . esc_html( $settings['query_not_found_text'] ) . '</h2>';
		}

		$output = ob_get_clean();

		$end = microtime( true );
		$duration = round( ( $end - $start ) * 1000, 2 );

		wp_send_json_success( [
			'output' => $output,
			'duration' => $duration,
			'found_posts' => $posts->found_posts,
			'post_count' => $posts->post_count,
		] );

		wp_die();
	}


/** Function wpr_backend_freepro_search_query_results() called by wp_ajax hooks: {'wpr_backend_freepro_search_query_results'} **/
/** Parameters found in function wpr_backend_freepro_search_query_results(): {"server": ["SERVER_NAME"], "post": ["search_query"]} **/
function wpr_backend_freepro_search_query_results() {
    // Freemius OptIn
    if ( ! ( wpr_fs()->is_registered() && wpr_fs()->is_tracking_allowed()  || wpr_fs()->is_pending_activation() ) ) {
        return;
    }

    if ( strpos($_SERVER['SERVER_NAME'],'instawp') || strpos($_SERVER['SERVER_NAME'],'tastewp') ) {
		return;
	}
    
    $search_query = isset($_POST['search_query']) ? sanitize_text_field(wp_unslash($_POST['search_query'])) : '';

    wp_remote_post( 'http://reastats.kinsta.cloud/wp-json/backend-freepro-search/data', [
        'body' => [
            'search_query' => $search_query
        ]
    ] );
}


/** Function wpr_get_filtered_count_products() called by wp_ajax hooks: {'nopriv_wpr_get_filtered_count_products', 'wpr_get_filtered_count_products'} **/
/** Parameters found in function wpr_get_filtered_count_products(): {"post": ["nonce", "wpr_url_params", "grid_settings"]} **/
function wpr_get_filtered_count_products() {
		$nonce = $_POST['nonce'];

		if (!isset($nonce) || !wp_verify_nonce($nonce, 'wpr-addons-js')) {
			wp_send_json_error(array(
				'message' => esc_html__('Security check failed.', 'wpr-addons'),
			));
		}
		
		if ( isset($_POST['wpr_url_params']) ) {
			$results = [];

			// Loop through each set of parameters
			foreach ($_POST['wpr_url_params'] as $params) {
				$query_args = WPR_Woo_Grid_Helpers::get_main_query_args($_POST['grid_settings'], $params);
				$query = new \WP_Query($query_args);
		
				// Add the count of found posts to the results array
				$results[] = [
					'found_posts' => $query->found_posts,
					'post_count' => $query->post_count,
					'params' => $params
				];
		
				wp_reset_postdata();
			}

			// Send the array of results
			wp_send_json_success($results);
		} else {
			$settings = $_POST['grid_settings'];
			$page_count = $this->get_max_num_pages( $settings );

			wp_reset_postdata();
		
			wp_send_json_success([
				'page_count' => $page_count,
			]);
		}

		wp_die();
	}


/** Function add_cart_single_product_ajax() called by wp_ajax hooks: {'wpr_addons_add_cart_single_product', 'nopriv_wpr_addons_add_cart_single_product'} **/
/** No params detected :-/ **/


/** Function wpr_pro_features_dismiss_notice() called by wp_ajax hooks: {'wpr_pro_features_dismiss_notice'} **/
/** Parameters found in function wpr_pro_features_dismiss_notice(): {"post": ["nonce"]} **/
function wpr_pro_features_dismiss_notice() {
		$nonce = $_POST['nonce'];

		if ( !wp_verify_nonce( $nonce, 'wpr-plugin-notice-js')  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

        add_option( 'wpr_pro_features_dismiss_notice', true );
        return 'responsetext';
    }


/** Function send_email() called by wp_ajax hooks: {'nopriv_wpr_form_builder_email'} **/
/** Parameters found in function send_email(): {"post": ["nonce", "form_content", "wpr_form_id"], "server": ["HTTP_USER_AGENT"]} **/
function send_email() {

        $nonce = $_POST['nonce'];

        if ( !wp_verify_nonce( $nonce, 'wpr-addons-js' ) ) {
            return; // Get out of here, the nonce is rotten!
        }
        
        $message_body = [];

		foreach ($_POST['form_content'] as $field) {
			if ($field[0] === 'email') {
				if (!is_email($field[1])) {
					// The field is an email, but it is not a valid email address
					// Take action or abort function execution here
					wp_send_json_error(array(
						'action' => 'wpr_form_builder_email',
						'message' => esc_html__('Email provided is invalid', 'wpr-addons'),
						'status' => 'error'
					));
				}
			}
		}
		
		// Rest of your function code here (if needed)
		$content_type = get_option('wpr_email_content_type_'. $_POST['wpr_form_id']);

		$line_break = 'html' === $content_type ? '<br>' : "\n";
    
		$email_fields = trim(get_option('wpr_email_fields_' . $_POST['wpr_form_id']));
		
		if ( $email_fields === '[all-fields]' || str_contains($email_fields, '[all-fields]') ) {

			// Function to replace the shortcode with the form value
			$replace_shortcode_with_value = function ($matches) {
				$field_id = $matches[1];
				foreach ($_POST['form_content'] as $key => $value) {
					$key_parts = explode('-', $key);
					$last_part = end($key_parts);
					if ($last_part === $field_id) {
						return is_array($value[1]) ? implode("\n", $value[1]) : $value[1];
					}
				}
				return ''; // Return an empty string if the field id is not found
			};

			// Function to replace the shortcode with the form value
			$all_fields_content = [];

            foreach ($_POST['form_content'] as $key => $value) {
				$value[2] = wp_unslash($value[2]);
                $all_fields_content[] = is_array($value[1]) ? trim($value[2]) . ': ' . implode("\n", $value[1]) : trim($value[2]) . ': ' . $value[1];
            }
            $all_fields_content = implode("\n", $all_fields_content);
	
			// Process user input to replace shortcodes
            $processed_message = str_replace('[all-fields]', $all_fields_content, $email_fields);

			$processed_message = preg_replace_callback(
				'/\[id="([^"]+)"\]/',
				$replace_shortcode_with_value,
				$processed_message
			);
		} else {

			// Function to replace the shortcode with the form value
			$replace_shortcode_with_value = function ($matches) {
				$field_id = $matches[1];
				foreach ($_POST['form_content'] as $key => $value) {
					$key_parts = explode('-', $key);
					$last_part = end($key_parts);
					if ($last_part === $field_id) {
						return is_array($value[1]) ? trim($value[2]) . ': ' . implode("\n", $value[1]) : trim($value[2]) . ': ' . $value[1];
					}
				}
				return ''; // Return an empty string if the field id is not found
			};
	
			// Process user input to replace shortcodes
			$processed_message = preg_replace_callback(
				'/\[id="([^"]+)"\]/',
				$replace_shortcode_with_value,
				$email_fields
			);
		}
		
        $meta_keys = get_option('wpr_meta_keys_'. $_POST['wpr_form_id']);
        $meta_fields = [];

		foreach ( $meta_keys as $metadata_type ) {
			switch ( $metadata_type ) {
				case 'date':
					$meta_fields['date'] = [
						'title' => esc_html__( 'Date', 'wpr-addons' ),
						'value' => date_i18n( get_option( 'date_format' ) ),
					];
					break;

				case 'time':
					$meta_fields['time'] = [
						'title' => esc_html__( 'Time', 'wpr-addons' ),
						'value' => date_i18n( get_option( 'time_format' ) ),
					];
					break;

				case 'page_url':
					$meta_fields['page_url'] = [
						'title' => esc_html__( 'Page URL', 'wpr-addons' ),
						// 'value' => get_option('wpr_referrer_'. $_POST['wpr_form_id']) ? esc_url_raw( wp_unslash( get_option('wpr_referrer_'. $_POST['wpr_form_id']) ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification.Missing
						'value' => get_option('wpr_referrer_'. $_POST['wpr_form_id']) ? get_option('wpr_referrer_'. $_POST['wpr_form_id']) : '', // phpcs:ignore WordPress.Security.NonceVerification.Missing
					];
					break;

				case 'page_title':
					$meta_fields['page_title'] = [
						'title' => esc_html__( 'Page Title', 'wpr-addons' ),
						// 'value' => get_option('wpr_referrer_title_'. $_POST['wpr_form_id']) ? sanitize_text_field( wp_unslash( get_option('wpr_referrer_title_'. $_POST['wpr_form_id']) ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification.Missing
						'value' => get_option('wpr_referrer_title_'. $_POST['wpr_form_id']) ? get_option('wpr_referrer_title_'. $_POST['wpr_form_id']) : '', // phpcs:ignore WordPress.Security.NonceVerification.Missing
					];
					break;

				case 'user_agent':
					$meta_fields['user_agent'] = [
						'title' => esc_html__( 'User Agent', 'wpr-addons' ),
						'value' => isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_textarea_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '',
					];
					break;

				case 'remote_ip':
					$meta_fields['remote_ip'] = [
						'title' => esc_html__( 'Remote IP', 'wpr-addons' ),
						'value' => Utilities::get_client_ip(),
					];
					break;
                    
				case 'credit':
					$meta_fields['credit'] = [
						'title' => esc_html__( 'Powered by', 'wpr-addons' ),
						'value' => esc_html__( 'Royal Addons', 'wpr-addons' ), // is it necessary ?
					];
					break;
			}
		}

        $email_meta = [];

        foreach( $meta_fields as $key => $value ) {
            $email_meta[] = $value['title'] . ': ' . $value['value'];
        }

        $to = get_option('wpr_email_to_'. $_POST['wpr_form_id']);

		$to = preg_replace_callback(
			'/\[id="(\w+)"\]/',
			function ($matches) {
				return $this->get_field_value($matches[1]);
			},
			$to
		);

        $subject = get_option('wpr_email_subject_'. $_POST['wpr_form_id']);

		$subject = preg_replace_callback(
			'/\[id="(\w+)"\]/',
			function ($matches) {
				return $this->get_field_value($matches[1]);
			},
			$subject
		);

		if ( $processed_message ) {
			$message_body[] = $processed_message;
		}
		
		// Prepare the message body with correct line breaks
		if ($content_type === 'html') {
			// Replace all instances of \n with <br> in each message body item
			foreach ($message_body as &$item) {
				$item = nl2br($item);
			}
			unset($item); // Break the reference with the last element
		}

        $body = implode($line_break, $message_body) . $line_break . '-----' . $line_break . implode($line_break, $email_meta);

		$cc_header = '';
		if ( !empty( get_option('wpr_cc_header_'. $_POST['wpr_form_id']) ) ) {
			$cc_header = 'Cc: ' . get_option('wpr_cc_header_'. $_POST['wpr_form_id']);

			$cc_header = preg_replace_callback(
				'/\[id="(\w+)"\]/',
				function ($matches) {
					return $this->get_field_value($matches[1]);
				},
				$cc_header
			);
		}

		$bcc_header = '';
		if ( !empty( get_option('wpr_bcc_header_'. $_POST['wpr_form_id']) ) ) {
			$bcc_header = 'Bcc: ' . get_option('wpr_bcc_header_'. $_POST['wpr_form_id']);

			$bcc_header = preg_replace_callback(
				'/\[id="(\w+)"\]/',
				function ($matches) {
					return $this->get_field_value($matches[1]);
				},
				$bcc_header
			);
		}
		
		if ( !empty( get_option('wpr_reply_to_'. $_POST['wpr_form_id']) ) && !empty(get_option('wpr_email_from_name_'. $_POST['wpr_form_id'])) && !empty(get_option('wpr_email_from_'. $_POST['wpr_form_id'])) ) {
			
			preg_match_all('/id="([^"]+)"/', get_option('wpr_reply_to_'. $_POST['wpr_form_id']), $matche);
			$reply_to_field_id = $matche[1];
			
			preg_match_all('/id="([^"]+)"/', get_option('wpr_email_from_name_'. $_POST['wpr_form_id']), $matche);
			$email_from_name_field_id = $matche[1];
			
			preg_match_all('/id="([^"]+)"/', get_option('wpr_email_from_'. $_POST['wpr_form_id']), $matche);
			$email_from_field_id = $matche[1];

			foreach ( $_POST['form_content'] as $key => $value ) {
				$key_parts = explode('-', $key);
				$last_part = end($key_parts);

				if (in_array($last_part, $reply_to_field_id)) {
					$reply_to_address = $value[1];
				}

				if (in_array($last_part, $email_from_name_field_id)) {
					$email_from_name = $value[1];
				}

				if (in_array($last_part, $email_from_field_id)) {
					$email_from_mail = $value[1];
				}
			}

			if ( !isset($reply_to_address) || empty($reply_to_address) ) {
				$reply_to_address = get_option('wpr_reply_to_'. $_POST['wpr_form_id']);
			}

			if ( !isset($email_from_name) || empty($email_from_name) ) {
				$email_from_name = get_option('wpr_email_from_name_'. $_POST['wpr_form_id']);
			}

			if ( !isset($email_from_mail) || empty($email_from_mail) ) {
				$email_from_mail = get_option('wpr_email_from_'. $_POST['wpr_form_id']);
			}
			
			$reply_to = 'Reply-To: ' . $reply_to_address;
		}
		
		$email_from = sprintf( 'From: %s <%s>' . "\r\n", $email_from_name, $email_from_mail );
		
		$headers = array('Content-Type: text/'. $content_type .'; charset=UTF-8', $email_from, $cc_header, $bcc_header, $reply_to);
      
        // Send email using wp_mail() function
        $sent = wp_mail( $to, $subject, $body, $headers);

        if ( $sent ) {
			wp_send_json_success(array(
				'action' => 'wpr_form_builder_email',
				'message' => esc_html__('Message sent successfully', 'wpr-addons'),
				'status' => 'success',
				'details' => json_encode($message_body)
			));
        } else {
			wp_send_json_error(array(
				'action' => 'wpr_form_builder_email',
				'message' => esc_html__('Message could not be sent', 'wpr-addons'),
				'status' => 'error',
				'details' => json_encode($message_body)
			));
        }
    }


/** Function wpr_verify_recaptcha() called by wp_ajax hooks: {'wpr_verify_recaptcha', 'nopriv_wpr_verify_recaptcha'} **/
/** Parameters found in function wpr_verify_recaptcha(): {"post": ["recaptcha_version"]} **/
function wpr_verify_recaptcha() {
		$recaptcha_response = isset( $_POST['g-recaptcha-response'] ) ? sanitize_text_field( wp_unslash( $_POST['g-recaptcha-response'] ) ) : '';
		$recaptcha_version  = isset( $_POST['recaptcha_version'] ) ? sanitize_text_field( wp_unslash( $_POST['recaptcha_version'] ) ) : 'v3';

		if ( empty( $recaptcha_response ) ) {
			wp_send_json_error( [ 'message' => 'Recaptcha Error' ] );
			return;
		}

		if ( 'v2' === $recaptcha_version ) {
			$is_valid = $this->check_recaptcha_v2( $recaptcha_response );
			if ( $is_valid ) {
				wp_send_json_success( [ 'message' => 'Recaptcha Success' ] );
			} else {
				wp_send_json_error( [ 'message' => 'Recaptcha Error' ] );
			}
			return;
		}

		// v3
		$result = $this->check_recaptcha_v3( $recaptcha_response );
		$score  = isset( $result['score'] ) ? $result['score'] : 0;
		$score_threshold = (float) get_option( 'wpr_recaptcha_v3_score', 0.5 );

		if ( $result['success'] && $score >= $score_threshold ) {
			wp_send_json_success( [
				'message' => 'Recaptcha Success',
				'score'   => $score,
			] );
		} else {
			wp_send_json_error( [
				'message' => 'Recaptcha Error',
				'score'   => $score,
			] );
		}
	}


/** Function wpr_get_media_filtered_count() called by wp_ajax hooks: {'nopriv_wpr_get_media_filtered_count', 'wpr_get_media_filtered_count'} **/
/** Parameters found in function wpr_get_media_filtered_count(): {"post": ["nonce", "grid_settings"]} **/
function wpr_get_media_filtered_count() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'wpr-addons-js' ) ) {
			wp_send_json_error( [
				'message' => esc_html__( 'Security check failed.', 'wpr-addons' ),
			] );
		}

		if ( isset( $_POST['grid_settings'] ) ) {
			$settings = $_POST['grid_settings'];
			self::get_max_num_pages( $settings );
		}

		wp_die();
	}


/** Function wpr_rating_dismiss_notice() called by wp_ajax hooks: {'wpr_rating_dismiss_notice'} **/
/** Parameters found in function wpr_rating_dismiss_notice(): {"post": ["nonce"]} **/
function wpr_rating_dismiss_notice() {
		$nonce = $_POST['nonce'];

		if ( !wp_verify_nonce( $nonce, 'wpr-plugin-notice-js')  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

        update_option( 'wpr_rating_dismiss_notice', true );
    }


/** Function wpr_load_more_instagram_posts_function() called by wp_ajax hooks: {'wpr_load_more_instagram_posts', 'nopriv_wpr_load_more_instagram_posts'} **/
/** Parameters found in function wpr_load_more_instagram_posts_function(): {"post": ["nonce", "wpr_load_more_settings", "wpr_insta_feed_widget_id", "next_post_index"]} **/
function wpr_load_more_instagram_posts_function() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'wpr-addons-js' ) ) {
			wp_die();
		}

		if ( empty( $_POST['wpr_load_more_settings'] ) || ! is_array( $_POST['wpr_load_more_settings'] ) ) {
			wp_die();
		}

		$settings = wp_unslash( $_POST['wpr_load_more_settings'] );
		$widget_id = isset( $_POST['wpr_insta_feed_widget_id'] ) ? sanitize_text_field( wp_unslash( $_POST['wpr_insta_feed_widget_id'] ) ) : '';
		$next_post_index = isset( $_POST['next_post_index'] ) ? absint( wp_unslash( $_POST['next_post_index'] ) ) : 0;

		$instagram_token = '';
		if ( $widget_id ) {
			$instagram_token = get_transient( 'wpr_instagram_access_token'. $widget_id );
			if ( empty( $instagram_token ) ) {
				$instagram_token = get_option( 'wpr_instagram_access_token_to_compare'. $widget_id );
			}
		}

		if ( empty( $instagram_token ) ) {
			wp_die();
		}

        foreach ( $this->call_instagram_api( $instagram_token, $settings, $next_post_index ) as $key => $result ) : ?>
            <?php 
				if ( $key >= 6 && (!defined('WPR_ADDONS_PRO_VERSION') || !wpr_fs()->can_use_premium_code()) ) {
					break;
				}
				if ($key < $next_post_index) :
					continue; 
				endif;
			?>

            <div class="wpr-insta-feed-content-wrap wpr-insta-col-12">
                <figure>
                    <?php
                        // Content: Above Media
                        $this->get_elements_by_location( 'above', $settings, $result );
                    ?>
                    <div class="wpr-insta-feed-media-wrap <?php echo esc_attr($this->get_image_effect_class( $settings )) ?>" data-overlay-link="<?php echo esc_attr( $settings['overlay_post_link'] ) ?>">
                    <?php if ( 'CAROUSEL_ALBUM' == $result->media_type || 'IMAGE' == $result->media_type ) : ?>
                        <div class="wpr-insta-feed-image-wrap" data-src="<?php echo esc_url( $result->media_url ); ?>">
                            <img src="<?php echo esc_url( $result->media_url ); ?>" alt="">
                        </div>
                    <?php else : ?>
                        <div class="wpr-insta-feed-image-wrap" data-src="<?php echo esc_url( $result->thumbnail_url ); ?>">
                            <img class="wpr-insta-feed-thumb" src="<?php echo esc_url( $result->thumbnail_url ); ?>" alt="">
                        </div>
                    <?php endif ; ?>
                        <div class="wpr-insta-feed-media-hover wpr-animation-wrap">
                            <?php
                                // Media Overlay
                                $this->render_media_overlay( $settings, $result );

                                // Content: Over Media
                                $this->get_elements_by_location( 'over', $settings, $result );
                            ?>
                        </div>
                    </div>
                    <?php
                        // Content: Below Media
                        $this->get_elements_by_location( 'below', $settings, $result );
                    ?>
                </figure>
            </div>
        <?php endforeach;
        
        wp_die();
    }


/** Function check_product_in_compare() called by wp_ajax hooks: {'nopriv_check_product_in_compare', 'check_product_in_compare'} **/
/** Parameters found in function check_product_in_compare(): {"post": ["product_id"]} **/
function check_product_in_compare() {
		$user_id = get_current_user_id();

        if ( !isset( $_POST['product_id'] ) ) {
            return;
        }

		if ($user_id > 0) {
			$compare = get_user_meta( $user_id, 'wpr_compare', true );
		
			if ( ! $compare ) {
				$compare = array();
			}
	
		} else {
			$compare = $this->get_compare_from_cookie();
		}
        
        wp_send_json(in_array( $_POST['product_id'], $compare ));

       wp_die();
    }


/** Function check_product_in_wishlist_grid() called by wp_ajax hooks: {'check_product_in_wishlist_grid', 'nopriv_check_product_in_wishlist_grid'} **/
/** No params detected :-/ **/


/** Function ajax_ppc_verify_password() called by wp_ajax hooks: {'nopriv_wpr_ppc_verify_password', 'wpr_ppc_verify_password'} **/
/** Parameters found in function ajax_ppc_verify_password(): {"post": ["widget_id", "password", "post_id"]} **/
function ajax_ppc_verify_password() {
		check_ajax_referer( 'wpr_ppc_verify', 'nonce' );

		$widget_id = isset( $_POST['widget_id'] ) ? sanitize_text_field( wp_unslash( $_POST['widget_id'] ) ) : '';
		$password = isset( $_POST['password'] ) ? sanitize_text_field( wp_unslash( $_POST['password'] ) ) : '';
		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;

		if ( empty( $widget_id ) || empty( $password ) || empty( $post_id ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid request.', 'wpr-addons' ) ] );
		}

		// Get Elementor data for the post
		$elementor_data = get_post_meta( $post_id, '_elementor_data', true );
		if ( empty( $elementor_data ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid request.', 'wpr-addons' ) ] );
		}

		$elements = json_decode( $elementor_data, true );
		if ( ! is_array( $elements ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid request.', 'wpr-addons' ) ] );
		}

		// Find widget in Elementor elements tree
		$widget_data = self::find_widget_in_elements( $elements, $widget_id );
		if ( ! $widget_data || empty( $widget_data['settings']['set_password'] ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid request.', 'wpr-addons' ) ] );
		}

		$stored_password = $widget_data['settings']['set_password'];

		if ( $password !== $stored_password ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Incorrect password. Please try again.', 'wpr-addons' ) ] );
		}

		// Set cookie
		$cookie_name = 'wpr_ppc_' . md5( $stored_password . $widget_id );
		$secure = is_ssl();
		setcookie( $cookie_name, md5( $stored_password ), time() + DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN, $secure, true );

		// Render protected content
		$settings = $widget_data['settings'];
		$content_html = '';

		if ( isset( $settings['content_type'] ) && 'template' === $settings['content_type'] && ! empty( $settings['protected_template'] ) ) {
			$template_id = $settings['protected_template'];

			if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
				$default_language_code = apply_filters( 'wpml_default_language', null );
				if ( ICL_LANGUAGE_CODE !== $default_language_code ) {
					$template_id = icl_object_id( $template_id, 'elementor_library', false, ICL_LANGUAGE_CODE );
				}
			}

			$type = get_post_meta( $post_id, '_wpr_template_type', true ) || get_post_meta( $template_id, '_elementor_template_type', true );
			$has_css = 'internal' === get_option( 'elementor_css_print_method' ) || '' !== $type;
			$content_html = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template_id, $has_css );
		} else {
			$content_html = isset( $settings['protected_content'] ) ? $settings['protected_content'] : '';
		}

		wp_send_json_success( [
			'message' => esc_html__( 'Password verified.', 'wpr-addons' ),
			'content' => $content_html,
		] );
	}


/** Function check_product_in_wishlist() called by wp_ajax hooks: {'check_product_in_wishlist', 'nopriv_check_product_in_wishlist'} **/
/** Parameters found in function check_product_in_wishlist(): {"post": ["product_id"]} **/
function check_product_in_wishlist() {
		$user_id = get_current_user_id();

        if ( !isset( $_POST['product_id'] ) ) {
            return;
        }

		if ($user_id > 0) {
			$wishlist = get_user_meta( $user_id, 'wpr_wishlist', true );
		
			if ( ! $wishlist ) {
				$wishlist = array();
			}
	
		} else {
			$wishlist = $this->get_wishlist_from_cookie();
		}
        
        wp_send_json(in_array( $_POST['product_id'], $wishlist ));

       wp_die();
    }


/** Function send_webhook() called by wp_ajax hooks: {'wpr_form_builder_webhook', 'nopriv_wpr_form_builder_webhook'} **/
/** Parameters found in function send_webhook(): {"post": ["nonce", "wpr_form_id", "form_content", "form_name"]} **/
function send_webhook() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'wpr-addons-js' ) ) {
			return; // Get out of here, the nonce is rotten!
		}

		$form_id = isset( $_POST['wpr_form_id'] ) ? sanitize_text_field( wp_unslash( $_POST['wpr_form_id'] ) ) : '';
		if ( '' === $form_id || ! preg_match( '/^[A-Za-z0-9_-]+$/', $form_id ) ) {
			wp_send_json_error( [
				'action'  => 'wpr_form_builder_webhook',
				'message' => esc_html__( 'Invalid form ID.', 'wpr-addons' ),
				'status'  => 'error',
			] );
		}

		$webhook_url = get_option( 'wpr_webhook_url_' . $form_id, '' );
		$webhook_url = Utilities::wpr_validate_webhook_url( $webhook_url );

		if ( is_wp_error( $webhook_url ) || '' === $webhook_url ) {
			wp_send_json_error( [
				'action'  => 'wpr_form_builder_webhook',
				'message' => esc_html__( 'Webhook error', 'wpr-addons' ),
				'status'  => 'error',
				'details' => is_wp_error( $webhook_url ) ? $webhook_url->get_error_message() : esc_html__( 'Webhook URL is missing or invalid.', 'wpr-addons' ),
			] );
		}

		$message_body = [];
		$form_content = isset( $_POST['form_content'] ) && is_array( $_POST['form_content'] ) ? wp_unslash( $_POST['form_content'] ) : []; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized per-field below.

		foreach ( $form_content as $key => $value ) {
			if ( ! is_array( $value ) ) {
				continue;
			}

			$field_key   = isset( $value[2] ) && '' !== $value[2] ? sanitize_text_field( $value[2] ) : sanitize_text_field( (string) $key );
			$field_value = isset( $value[1] ) ? $value[1] : '';

			if ( is_array( $field_value ) ) {
				$message_body[ $field_key ] = implode( "\n", array_map( 'sanitize_text_field', $field_value ) );
			} else {
				$message_body[ $field_key ] = sanitize_text_field( $field_value );
			}
		}

		$message_body['form_id']   = $form_id;
		$message_body['form_name'] = isset( $_POST['form_name'] ) ? sanitize_text_field( wp_unslash( $_POST['form_name'] ) ) : '';

		$args = [
			'body'        => $message_body,
			'timeout'     => 15,
			'redirection' => 3,
		];

		// wp_safe_remote_post rejects unsafe/private URLs and re-validates redirects.
		$response      = wp_safe_remote_post( $webhook_url, $args );
		$response_code = (int) wp_remote_retrieve_response_code( $response );
		$is_success    = ! is_wp_error( $response ) && $response_code >= 200 && $response_code < 300;

		if ( ! $is_success ) {
			wp_send_json_error( [
				'action'  => 'wpr_form_builder_webhook',
				'message' => esc_html__( 'Webhook error', 'wpr-addons' ),
				'status'  => 'error',
				'details' => wp_json_encode( $message_body ),
			] );
		}

		wp_send_json_success( [
			'action'  => 'wpr_form_builder_webhook',
			'message' => esc_html__( 'Webhook success', 'wpr-addons' ),
			'status'  => 'success',
			'details' => wp_json_encode( $message_body ),
		] );
	}


/** Function wpr_install_activate_backup_plugin() called by wp_ajax hooks: {'wpr_install_activate_backup_plugin'} **/
/** Parameters found in function wpr_install_activate_backup_plugin(): {"post": ["nonce", "pending_edit_url", "pending_template_name"]} **/
function wpr_install_activate_backup_plugin() {
		$nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';

		if ( !wp_verify_nonce( $nonce, 'wpr-plugin-options-js' ) || !current_user_can( 'install_plugins' ) ) {
			wp_send_json_error( ['message' => 'Unauthorized'] );
		}

		// Save pending template data as transient EARLY (before any success returns).
		if ( isset($_POST['pending_edit_url']) && !empty($_POST['pending_edit_url']) ) {
			$template_data = array(
				'url'  => sanitize_url( $_POST['pending_edit_url'] ),
				'name' => isset( $_POST['pending_template_name'] ) ? sanitize_text_field( $_POST['pending_template_name'] ) : '',
			);
			set_transient( 'wpr_pending_template_edit', $template_data, 5 * MINUTE_IN_SECONDS );
		}

		$plugin_slug = 'royal-backup-reset';
		$plugin_file = 'royal-backup-reset/royal-backup-reset.php';

		// Check if plugin is already active
		if ( is_plugin_active( $plugin_file ) ) {
			wp_send_json_success( ['status' => 'active', 'message' => 'Plugin is already active'] );
		}

		// Check if plugin is installed but not active
		if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_file ) ) {
			$result = activate_plugin( $plugin_file );
			if ( is_wp_error( $result ) ) {
				wp_send_json_error( ['message' => $result->get_error_message()] );
			}
			wp_send_json_success( ['status' => 'activated', 'message' => 'Plugin activated successfully'] );
		}

		// Plugin needs to be installed
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		$api = plugins_api( 'plugin_information', [
			'slug' => $plugin_slug,
			'fields' => ['sections' => false],
		] );

		if ( is_wp_error( $api ) ) {
			wp_send_json_error( ['message' => $api->get_error_message()] );
		}

		$skin = new \WP_Ajax_Upgrader_Skin();
		$upgrader = new \Plugin_Upgrader( $skin );
		$result = $upgrader->install( $api->download_link );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( ['message' => $result->get_error_message()] );
		}

		if ( $result === false ) {
			wp_send_json_error( ['message' => 'Plugin installation failed'] );
		}

		// Activate the plugin
		$activate_result = activate_plugin( $plugin_file );
		if ( is_wp_error( $activate_result ) ) {
			wp_send_json_error( ['message' => $activate_result->get_error_message()] );
		}

		wp_send_json_success( ['status' => 'installed', 'message' => 'Plugin installed and activated successfully'] );
	}


/** Function add_to_compare() called by wp_ajax hooks: {'nopriv_add_to_compare', 'add_to_compare'} **/
/** Parameters found in function add_to_compare(): {"post": ["nonce", "product_id"]} **/
function add_to_compare() {
        $nonce = $_POST['nonce'];

        if ( ! wp_verify_nonce( $nonce, 'wpr-addons-js' ) || ! isset( $_POST['product_id'] ) ) {
            return;
        }

        $product_id = intval( $_POST['product_id'] );
        $user_id = get_current_user_id();

        // $compare = get_user_meta( get_current_user_id(), 'wpr_compare', true );
        // if ( ! $compare ) {
        //     $compare = array();
        // }
        // if ( in_array( $product_id, $compare ) ) {
        //     wp_send_json_error( array( 'message' => esc_html__('Product is already in Compare.', 'wpr-addons') ) );
        //     return;
        // }
        // $compare[] = $product_id;
        // update_user_meta( get_current_user_id(), 'wpr_compare', $compare );
        
        
        // NEW CODE
        if ($user_id > 0) {
            $compare = get_user_meta($user_id, 'wpr_compare', true);
            if (!$compare) {
                $compare = array();
            }
        } else {
            $compare = $this->get_compare_from_cookie();
        }
    
        if (in_array($product_id, $compare)) {
            wp_send_json_error(array('message' => esc_html__('Product is already in compare.', 'wpr-addons')));
            return;
        }
    
        $compare[] = $product_id;
    
        if ($user_id > 0) {
            update_user_meta($user_id, 'wpr_compare', $compare);
        } else {
            $this->set_compare_to_cookie($compare);
        }

        wp_send_json_success();
    }


/** Function update_mini_compare() called by wp_ajax hooks: {'update_mini_compare', 'nopriv_update_mini_compare'} **/
/** Parameters found in function update_mini_compare(): {"post": ["product_id"]} **/
function update_mini_compare() {
        if ( ! isset( $_POST['product_id'] ) ) {
            return;
        }
        
        $product_id = intval( $_POST['product_id'] );
        $user_id = get_current_user_id();

        
        if ($user_id > 0) {
            $compare = get_user_meta($user_id, 'wpr_compare', true);
            if (!$compare) {
                $compare = array();
            }
        } else {
            $compare = $this->get_compare_from_cookie();
        }

        $product = wc_get_product( $product_id );
        $product_data = [];
        if ( $product ) {
            $product_data['product_url'] = $product->get_permalink();
            $product_data['product_image'] = $product->get_image();
            $product_data['product_title'] = $product->get_title();
            $product_data['product_price'] = $product->get_price_html();
            $product_data['product_id'] = $product->get_id();
            $product_data['compare_count'] = sizeof($compare);
        }

       wp_send_json($product_data);

       wp_die();
    }


/** Function wpr_search_query_results() called by wp_ajax hooks: {'wpr_search_query_results'} **/
/** Parameters found in function wpr_search_query_results(): {"server": ["SERVER_NAME"], "post": ["search_query"]} **/
function wpr_search_query_results() {
    // Freemius OptIn
    if ( ! ( wpr_fs()->is_registered() && wpr_fs()->is_tracking_allowed()  || wpr_fs()->is_pending_activation() ) ) {
        return;
    }

    if ( strpos($_SERVER['SERVER_NAME'],'instawp') || strpos($_SERVER['SERVER_NAME'],'tastewp') ) {
		return;
	}
    
    $search_query = isset($_POST['search_query']) ? sanitize_text_field(wp_unslash($_POST['search_query'])) : '';

    wp_remote_post( 'http://reastats.kinsta.cloud/wp-json/templates-kit-search/data', [
        'body' => [
            'search_query' => $search_query
        ]
    ] );
}


/** Function wpr_create_mega_menu_template() called by wp_ajax hooks: {'wpr_create_mega_menu_template'} **/
/** Parameters found in function wpr_create_mega_menu_template(): {"post": ["nonce", "item_id"]} **/
function wpr_create_mega_menu_template() {

    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'wpr-mega-menu-js' ) || ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( [ 'message' => 'Invalid request.' ] );
        return;
    }

    if ( ! isset( $_POST['item_id'] ) ) {
        wp_send_json_error( [ 'message' => 'Missing item_id.' ] );
        return;
    }

    $menu_item_id = absint( $_POST['item_id'] );
    $menu_item = get_post( $menu_item_id );
    if ( ! $menu_item || $menu_item->post_type !== 'nav_menu_item' ) {
        wp_send_json_error( [ 'message' => 'Invalid menu item.' ] );
        return;
    }

    $mega_menu_id = get_post_meta( $menu_item_id, 'wpr-mega-menu-item', true );

    if ( ! $mega_menu_id ) {

        $mega_menu_id = wp_insert_post( array(
            'post_title'  => 'wpr-mega-menu-item-' . $menu_item_id,
            'post_status' => 'publish',
            'post_type'   => 'wpr_mega_menu',
        ) );

        update_post_meta( $menu_item_id, 'wpr-mega-menu-item', $mega_menu_id );

    }

    $edit_link = add_query_arg(
        array(
            'post' => $mega_menu_id,
            'action' => 'elementor',
        ),
        admin_url( 'post.php' )
    );

    wp_send_json([
        'data' => [
            'edit_link' => $edit_link
        ]
    ]);
}


/** Function wpr_plugin_update_dismiss_notice() called by wp_ajax hooks: {'wpr_plugin_update_dismiss_notice'} **/
/** Parameters found in function wpr_plugin_update_dismiss_notice(): {"post": ["nonce"]} **/
function wpr_plugin_update_dismiss_notice() {
		$nonce = $_POST['nonce'];

		if ( !wp_verify_nonce( $nonce, 'wpr-plugin-notice-js')  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

        add_option( 'wpr_plugin_update_dismiss_notice', true );
    }


/** Function wpr_load_more_tweets_function() called by wp_ajax hooks: {'wpr_load_more_tweets', 'nopriv_wpr_load_more_tweets'} **/
/** Parameters found in function wpr_load_more_tweets_function(): {"post": ["nonce", "wpr_load_more_settings", "next_post_index"]} **/
function wpr_load_more_tweets_function() {

        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'wpr-addons-js' ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Security check failed.', 'wpr-addons' ) ) );
        }

        if ( empty( $_POST['wpr_load_more_settings'] ) || ! is_array( $_POST['wpr_load_more_settings'] ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Invalid request.', 'wpr-addons' ) ) );
        }

        $settings = wp_unslash( $_POST['wpr_load_more_settings'] );

        // Consumer key/secret must never be sent from frontend; use server-stored options or skip this flow.
        $consumer_key    = isset( $settings['twitter_feed_consumer_key'] ) ? $settings['twitter_feed_consumer_key'] : '';
        $consumer_secret = isset( $settings['twitter_feed_consumer_secret'] ) ? $settings['twitter_feed_consumer_secret'] : '';
        if ( '' === $consumer_key || '' === $consumer_secret ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Twitter API credentials are not configured for load more.', 'wpr-addons' ) ) );
        }

				$credentials = base64_encode( $consumer_key . ':' . $consumer_secret );

				add_filter('https_ssl_verify', '__return_false');

				$response = wp_remote_post('https://api.twitter.com/oauth2/token', [
					'method' => 'POST',
					'httpversion' => '1.1',
					'blocking' => true,
					'headers' => [
						'Authorization' => 'Basic ' . $credentials,
						'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
					],
					'body' => ['grant_type' => 'client_credentials'],
				]);

				$body = json_decode(wp_remote_retrieve_body($response));

				if ($body) {
					$token = $body->access_token;
				}

        add_filter('https_ssl_verify', '__return_false');
  
        $response = [];
        $items_array = [];

        foreach ($settings['twitter_accounts'] as $key=>$value) {
          
            $response[$key] = wp_remote_get('https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=' . $value['twitter_feed_account_name'] . '&count='. ($settings['number_of_posts'] + $_POST['next_post_index']) .'&tweet_mode=extended', [
              'httpversion' => '1.1',
              'blocking' => true,
              'headers' => [
                'Authorization' => "Bearer $token",
              ],
            ]);
    
            if ( is_wp_error( $response[$key] ) ) {
              return $response[$key];
            }
        
            if ( ! empty( $response[$key]['response'] ) && $response[$key]['response']['code'] == 200 ) {
              $items_array[] = json_decode( wp_remote_retrieve_body( $response[$key] ), true );
            }
          } 
            foreach ( $items_array as $key=>$items ) :
    
            if ($settings['twitter_feed_hashtag_name']) {
              $hashtag_names = explode(',', str_replace(' ', '', $settings['twitter_feed_hashtag_name']));
        
              foreach ($items as $key => $item) {
                $match = false;
        
                if ($item['entities']['hashtags']) {
                  foreach ($item['entities']['hashtags'] as $tag) {
                    if (in_array($tag['text'], $hashtag_names)) {
                      $match = true;
                    }
                  }
                }
        
                if ($match == false) {
                  unset($items[$key]);
                }
              }
            }
        
                  foreach ( $items as $key=>$item) :
                
                    if ( $key >= 6 && (!defined('WPR_ADDONS_PRO_VERSION') || !wpr_fs()->can_use_premium_code()) ) {
                      break;
                    }
      
                    if ($key < $_POST['next_post_index']) :
                      continue; 
                    endif;
                          
                  $banner_placeholder = WPR_ADDONS_ASSETS_URL . 'img/placeholder.png';
                  $banner = $item['user']['profile_banner_url'] ? $item['user']['profile_banner_url'] : $banner_placeholder;
                  ?>
                          <div class="wpr-tweet">
                                  <img src="<?php echo $banner ?>" alt="">
                                  <article class="media">
          
                                      <?php 
                                          // Content: Above Media
                                          echo $this->get_elements_by_location( 'above', $settings, $item );
                                      ?>
                                      
                                  </article>
                          </div>
                  <?php
          
                  endforeach;
              endforeach;

        die();
    }


/** Function remove_from_wishlist() called by wp_ajax hooks: {'remove_from_wishlist', 'nopriv_remove_from_wishlist'} **/
/** Parameters found in function remove_from_wishlist(): {"post": ["nonce", "product_id"]} **/
function remove_from_wishlist() {
        $nonce = $_POST['nonce'];

        if ( ! wp_verify_nonce($nonce, 'wpr-addons-js') || ! isset( $_POST['product_id'] ) ) {
            return;
        }
        $product_id = intval( $_POST['product_id'] );
        $user_id = get_current_user_id();

        if ($user_id > 0) {
            $wishlist = get_user_meta($user_id, 'wpr_wishlist', true);
            if (!$wishlist) {
                $wishlist = array();
            }
        } else {
            $wishlist = $this->get_wishlist_from_cookie();
        }
    
        $wishlist = array_diff($wishlist, array($product_id));
    
        if ($user_id > 0) {
            update_user_meta($user_id, 'wpr_wishlist', $wishlist);
        } else {
            $this->set_wishlist_to_cookie($wishlist);
        }

        wp_send_json_success();
    }


/** Function wpr_get_page_content() called by wp_ajax hooks: {'wpr_get_page_content', 'nopriv_wpr_get_page_content'} **/
/** Parameters found in function wpr_get_page_content(): {"post": ["wpr_compare_page_id", "nonce"]} **/
function wpr_get_page_content($request) {

        $page_id = $_POST['wpr_compare_page_id'];

        if ( post_password_required($page_id) || 'publish' !== get_post_status($page_id) ) {
            wp_send_json_error(array(
                'message' => esc_html__('Security check failed.', 'wpr-addons'),
            ));
        }

		if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wpr-addons-js')) {
			wp_send_json_error(array(
				'message' => esc_html__('Security check failed.', 'wpr-addons'),
			));
		}

        // $page_id = $request->get_param('id');
        
        // Check if the page was created with Elementor
        if (\Elementor\Plugin::$instance->db->is_built_with_elementor($page_id)) {
            Utilities::enqueue_inner_template_assets( $page_id );
            $content = \Elementor\Plugin::$instance->frontend->get_builder_content($page_id);
            wp_send_json_success(array('content' => $content, 'page_url' => get_page_link( $page_id )));
            // return new WP_REST_Response(array('content' => $content), 200);
        } else {
            $page = get_post($page_id);  
            if ($page) {
                $content = apply_filters('the_content', $page->post_content);
                wp_send_json_success(array('content' => $content));
                // return new WP_REST_Response(array('content' => $content), 200);
            } else {
                wp_send_json_error(array('message' => 'Page not found'));
                // return new WP_Error('page_not_found', 'Page not found', array('status' => 404));
            }
        }
        wp_die();
    }


/** Function wpr_import_library_template() called by wp_ajax hooks: {'wpr_import_library_template'} **/
/** Parameters found in function wpr_import_library_template(): {"post": ["nonce", "slug", "kit", "section"]} **/
function wpr_import_library_template() {

		$nonce = $_POST['nonce'];

		if ( !wp_verify_nonce( $nonce, 'wpr-addons-library-frontend-js')  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

        $source = new WPR_Library_Source();
		$slug = isset($_POST['slug']) ? sanitize_text_field(wp_unslash($_POST['slug'])) : '';
		$kit = isset($_POST['kit']) ? sanitize_text_field(wp_unslash($_POST['kit'])) : '';
		$section = isset($_POST['section']) ? sanitize_text_field(wp_unslash($_POST['section'])) : '';

        $data = $source->get_data([
        	'template_id' => $slug,
			'kit_id' => $kit,
			'section_id' => $section
        ]);
        
		$template = '' !== $kit ? $kit : $slug;
		if ( $this->vts($slug) ) {
			echo json_encode($data);
		}
	}


/** Function wpr_rating_need_help() called by wp_ajax hooks: {'wpr_rating_need_help'} **/
/** Parameters found in function wpr_rating_need_help(): {"post": ["nonce"]} **/
function wpr_rating_need_help() {
		$nonce = $_POST['nonce'];

		if ( !wp_verify_nonce( $nonce, 'wpr-plugin-notice-js')  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

        // Reset Activation Time if user Needs Help
        update_option( 'royal_elementor_addons_activation_time', strtotime('now') );
    }


/** Function wpr_fix_royal_compatibility() called by wp_ajax hooks: {'wpr_fix_royal_compatibility'} **/
/** Parameters found in function wpr_fix_royal_compatibility(): {"post": ["nonce"]} **/
function wpr_fix_royal_compatibility() {

    $nonce = $_POST['nonce'];

    if ( !wp_verify_nonce( $nonce, 'wpr-templates-kit-js' )  || !current_user_can( 'manage_options' ) ) {
      exit; // Get out of here, the nonce is rotten!
    }

    // Get currently active plugins
    $active_plugins = (array) get_option( 'active_plugins', array() );
    $active_plugins = array_values($active_plugins);
    $required_plugins = [
        'elementor/elementor.php',
        'elementor-pro/elementor-pro.php',
        'royal-elementor-addons/wpr-addons.php',
        'royal-elementor-addons-pro/wpr-addons-pro.php',
        'wpr-addons-pro/wpr-addons-pro.php',
        'royal-backup-reset/royal-backup-reset.php',
        'contact-form-7/wp-contact-form-7.php',
        'woocommerce/woocommerce.php',
        'really-simple-ssl/rlrsssl-really-simple-ssl.php',
        'wp-mail-smtp/wp_mail_smtp.php',
        'updraftplus/updraftplus.php',
        'temporary-login-without-password/temporary-login-without-password.php',
        'wp-reset/wp-reset.php'
    ];

    // Deactivate Extra Import Plugins
    foreach ( $active_plugins as $key => $value ) {
        if ( ! in_array($value, $required_plugins) ) {
            $active_key = array_search($value, $active_plugins);;
            unset($active_plugins[$active_key]);
        }
    }

    // Set Active Plugins
    update_option( 'active_plugins', array_values($active_plugins) );

    // Get Current Theme
    $theme = get_option('stylesheet');

    // Activate Royal Elementor Kit Theme
    if ( 'ashe-pro-premium' !== $theme && 'bard-pro-premium' !== $theme
        && 'vayne-pro-premium' !== $theme && 'kayn-pro-premium' !== $theme ) {
        switch_theme( 'royal-elementor-kit' );
        set_transient( 'royal-elementor-kit_activation_notice', true );
    }
}


/** Function wpr_save_template_conditions() called by wp_ajax hooks: {'wpr_save_template_conditions'} **/
/** Parameters found in function wpr_save_template_conditions(): {"post": ["nonce", "template", "wpr_header_conditions", "wpr_header_show_on_canvas", "wpr_footer_conditions", "wpr_footer_show_on_canvas", "wpr_archive_conditions", "wpr_single_conditions", "wpr_product_archive_conditions", "wpr_product_single_conditions", "wpr_popup_conditions"]} **/
function wpr_save_template_conditions() {

		$nonce = $_POST['nonce'];

		if ( !wp_verify_nonce( $nonce, 'wpr-plugin-options-js')  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

		$template = isset($_POST['template']) ? sanitize_text_field(wp_unslash($_POST['template'])): false;

		// Header
		if ( isset($_POST['wpr_header_conditions']) ) {
			update_option( 'wpr_header_conditions', $this->sanitize_conditions($_POST['wpr_header_conditions']) );  // phpcs:ignore

			$wpr_header_show_on_canvas = isset($_POST['wpr_header_show_on_canvas']) ? sanitize_text_field(wp_unslash($_POST['wpr_header_show_on_canvas'])): false;
			if ( $wpr_header_show_on_canvas && $template ) {
				update_post_meta( Utilities::get_template_id($template), 'wpr_header_show_on_canvas', $wpr_header_show_on_canvas );
			}
		}

		// Footer
		if ( isset($_POST['wpr_footer_conditions']) ) {
			update_option( 'wpr_footer_conditions', $this->sanitize_conditions($_POST['wpr_footer_conditions']) );  // phpcs:ignore

			$wpr_footer_show_on_canvas = isset($_POST['wpr_footer_show_on_canvas']) ? sanitize_text_field(wp_unslash($_POST['wpr_footer_show_on_canvas'])): false;
			if ( $wpr_footer_show_on_canvas && $template ) {
				update_post_meta( Utilities::get_template_id($template), 'wpr_footer_show_on_canvas', $wpr_footer_show_on_canvas );
			}
		}

		// Archive
		if ( isset($_POST['wpr_archive_conditions']) ) {
			update_option( 'wpr_archive_conditions', $this->sanitize_conditions($_POST['wpr_archive_conditions']) );  // phpcs:ignore
		}

		// Single
		if ( isset($_POST['wpr_single_conditions']) ) {
			update_option( 'wpr_single_conditions', $this->sanitize_conditions($_POST['wpr_single_conditions']) );  // phpcs:ignore
		}

		// Product Archive
		if ( isset($_POST['wpr_product_archive_conditions']) ) {
			update_option( 'wpr_product_archive_conditions', $this->sanitize_conditions($_POST['wpr_product_archive_conditions']) );  // phpcs:ignore
		}

		// Product Single
		if ( isset($_POST['wpr_product_single_conditions']) ) {
			update_option( 'wpr_product_single_conditions', $this->sanitize_conditions($_POST['wpr_product_single_conditions']) );  // phpcs:ignore
		}

		// Popup
		if ( isset($_POST['wpr_popup_conditions']) ) {
			update_option( 'wpr_popup_conditions', $this->sanitize_conditions($_POST['wpr_popup_conditions']) );  // phpcs:ignore
		}
	}


/** Function add_to_submissions() called by wp_ajax hooks: {'nopriv_wpr_form_builder_submissions'} **/
/** Parameters found in function add_to_submissions(): {"post": ["nonce", "form_content", "form_name", "form_id", "form_page", "form_page_id"], "server": ["HTTP_USER_AGENT"]} **/
function add_to_submissions() {

        $nonce = $_POST['nonce'];

        if ( !wp_verify_nonce( $nonce, 'wpr-addons-js' ) || !wpr_fs()->can_use_premium_code() ) {
            return; // Get out of here, the nonce is rotten!
        }

        $new = [
            'post_status' => 'publish',
            'post_type' => 'wpr_submissions'
        ];
        
        $post_id = wp_insert_post( $new );

        foreach ($_POST['form_content'] as $key => $value ) {
            // update_post_meta($post_id, $key, [$value[0], $value[1], $value[2]]);

            $type  = sanitize_key( $value[0] );
            $label = $this->wpr_sanitize_form_field( $type, $value[1] );
            $input = sanitize_text_field( $value[2] );

            update_post_meta(
                $post_id,
                sanitize_key( $key ),
                [ $type, $label, $input ]
            );
        }

        $sanitized_form_name = sanitize_text_field($_POST['form_name']);
        $sanitized_form_id = sanitize_text_field($_POST['form_id']);
        $sanitized_form_page = sanitize_text_field($_POST['form_page']);
        $sanitized_form_page_id = sanitize_text_field($_POST['form_page_id']);
    
        update_post_meta($post_id, 'wpr_form_name', $sanitized_form_name);
        update_post_meta($post_id, 'wpr_form_id', $sanitized_form_id);
        update_post_meta($post_id, 'wpr_form_page', $sanitized_form_page);
        update_post_meta($post_id, 'wpr_form_page_id', $sanitized_form_page_id);
        update_post_meta($post_id, 'wpr_user_agent', sanitize_textarea_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ));
        update_post_meta($post_id, 'wpr_user_ip', Utilities::get_client_ip());

        // One-time secret for updating submission action meta (see WPR_Actions_Status); not guessable per post_id.
        $submission_action_secret = '';
        if ( $post_id ) {
            $submission_action_secret = bin2hex( random_bytes( 32 ) );
            update_post_meta( $post_id, '_wpr_submission_action_secret', $submission_action_secret );
        }
        
        if( $post_id ) {
            wp_send_json_success(array(
                'action' => 'wpr_form_builder_submissions',
                'post_id' => $post_id,
                'submission_secret' => $submission_action_secret,
                'message' => esc_html__('Submission created successfully', 'wpr-addons'),
				'status' => 'success',
                'content' => $_POST['form_content']
            ));
        } else {
            wp_send_json_success(array(
                'action' => 'wpr_form_builder_submissions',
                'post_id' => $post_id,
                'message' => esc_html__('Submit action failed', 'wpr-addons'),
				'status' => 'error'
            ));
        }
    }


/** Function ajax_mailchimp_subscribe() called by wp_ajax hooks: {'nopriv_mailchimp_subscribe', 'mailchimp_subscribe'} **/
/** Parameters found in function ajax_mailchimp_subscribe(): {"post": ["listId", "fields"]} **/
function ajax_mailchimp_subscribe() {
		// API Key
        $api_key = !empty(get_option('wpr_mailchimp_api_key')) && false != get_option('wpr_mailchimp_api_key') ? get_option('wpr_mailchimp_api_key') : ''; // GOGA

        $api_key_sufix = explode( '-', $api_key )[1];

        // List ID
        $list_id = isset($_POST['listId']) ? sanitize_text_field(wp_unslash($_POST['listId'])) : '';

        // Get Available Fileds (PHPCS - fields are sanitized later on input)
		$available_fields = isset($_POST['fields']) ? $_POST['fields'] : []; // phpcs:ignore
        wp_parse_str( $available_fields, $fields );

        // Merge Additional Fields
        $merge_fields = array(
            'FNAME' => !empty( $fields['wpr_mailchimp_firstname'] ) ? sanitize_text_field($fields['wpr_mailchimp_firstname']) : '',
            'LNAME' => !empty( $fields['wpr_mailchimp_lastname'] ) ? sanitize_text_field($fields['wpr_mailchimp_lastname']) : '',
			'PHONE' => !empty ( $fields['wpr_mailchimp_phone_number'] ) ? sanitize_text_field($fields['wpr_mailchimp_phone_number']) : '',
        );

        // API URL
        $api_url = 'https://'. $api_key_sufix .'.api.mailchimp.com/3.0/lists/'. $list_id .'/members/'. md5(strtolower(sanitize_text_field($fields['wpr_mailchimp_email'])));

        // API Args
        $api_args = [
			'method' => 'PUT',
			'headers' => [
				'Content-Type' => 'application/json',
				'Authorization' => 'apikey '. $api_key,
			],
			'body' => json_encode([
				'email_address' => sanitize_text_field($fields[ 'wpr_mailchimp_email' ]),
				'status' => 'subscribed',
				'merge_fields' => $merge_fields,
			]),
        ];

        // Send Request
        $request = wp_remote_post( $api_url, $api_args );

		if ( ! is_wp_error($request) ) {
			$request = json_decode( wp_remote_retrieve_body($request) );

			// Set Status
			if ( ! empty($request) ) {
				if ($request->status == 'subscribed') {
					wp_send_json([ 'status' => 'subscribed' ]);
				} else {
					wp_send_json([ 'status' => $request->title ]);
				}
			}
		}
	}


/** Function render_library_templates_popups() called by wp_ajax hooks: {'render_library_templates_popups'} **/
/** No params detected :-/ **/


/** Function get_custom_meta_keys() called by wp_ajax hooks: {'nopriv_wpr_get_custom_meta_keys'} **/
/** No params detected :-/ **/


/** Function wpr_update_form_action_meta() called by wp_ajax hooks: {'wpr_update_form_action_meta', 'nopriv_wpr_update_form_action_meta'} **/
/** Parameters found in function wpr_update_form_action_meta(): {"post": ["nonce", "post_id", "submission_secret", "custom_token", "action_name", "status", "message"], "cookie": ["wpr_guest_token"]} **/
function wpr_update_form_action_meta() {
        $nonce = $_POST['nonce'];

        if ( !wp_verify_nonce( $nonce, 'wpr-addons-js' ) ) {
          return; // Get out of here, the nonce is rotten!
        }

        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $submission_secret = isset( $_POST['submission_secret'] ) ? sanitize_text_field( wp_unslash( $_POST['submission_secret'] ) ) : '';

        if ( ! $post_id || get_post_type( $post_id ) !== 'wpr_submissions' ) {
            wp_send_json_error( 'Invalid post' );
        }

        $stored_secret = get_post_meta( $post_id, '_wpr_submission_action_secret', true );
        if ( ! is_string( $stored_secret ) || $stored_secret === '' || ! hash_equals( $stored_secret, $submission_secret ) ) {
            wp_send_json_error( 'Invalid submission secret.' );
        }

        // Validate custom token
        // $custom_token = $_POST['custom_token'];
        
        // if ( is_user_logged_in() ) {
        //     // For logged-in users, validate against their user ID
        //     $user_id = get_current_user_id();
        //     $stored_token = get_transient( 'wpr_custom_token_' . $user_id );
        // } else {
        //     // For non-logged-in users, use the guest token from the cookie
        //     if ( isset( $_COOKIE['wpr_guest_token'] ) ) {
        //         $guest_id = sanitize_text_field( $_COOKIE['wpr_guest_token'] );
        //         $stored_token = get_transient( 'wpr_custom_guest_token_' . $guest_id );
        //     } else {
        //         wp_send_json_error( 'Invalid token.' );
        //         return;
        //     }
        // }
    
        // if ( ! $stored_token || $custom_token !== $stored_token ) {
        //     wp_send_json_error( 'Invalid token.' );
        //     return;
        // }

        $action_name = isset($_POST['action_name']) ? sanitize_text_field($_POST['action_name']) : '';
        $status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';
        $message = isset($_POST['message']) ? sanitize_text_field($_POST['message']) : '';

        $meta_value = [
            'status' => $status,
            'message' => $message
        ];

        $actions_whitelist = [
            'wpr_form_builder_email',
            'wpr_form_builder_submissions',
            'wpr_form_builder_mailchimp',
            'wpr_form_builder_webhook'
        ];

        if ($post_id && $action_name && $status && in_array($action_name, $actions_whitelist)) {
            update_post_meta($post_id, '_action_' . $action_name, $meta_value);
            wp_send_json_success('Post meta updated successfully');
        } else {
            wp_send_json_error('Invalid data provided');
        }
    }


/** Function wpr_delete_template() called by wp_ajax hooks: {'wpr_delete_template'} **/
/** Parameters found in function wpr_delete_template(): {"post": ["nonce", "template_slug", "template_library"]} **/
function wpr_delete_template() {

		$nonce = $_POST['nonce'];

		if ( !wp_verify_nonce( $nonce, 'delete_post-' . $_POST['template_slug'] )  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

		$template_slug = isset($_POST['template_slug']) ? sanitize_text_field(wp_unslash($_POST['template_slug'])): '';
		$template_library = isset($_POST['template_library']) ? sanitize_text_field(wp_unslash($_POST['template_library'])): '';

		$post = get_page_by_path( $template_slug, OBJECT, $template_library );

		if ( get_post_type($post->ID) == 'wpr_templates' || get_post_type($post->ID) == 'elementor_library' ) {
			wp_delete_post( $post->ID, true );
		}
	}


/** Function wpr_activate_required_theme() called by wp_ajax hooks: {'wpr_activate_required_theme'} **/
/** Parameters found in function wpr_activate_required_theme(): {"post": ["nonce"]} **/
function wpr_activate_required_theme() {

    $nonce = $_POST['nonce'];

    if ( !wp_verify_nonce( $nonce, 'wpr-templates-kit-js' )  || !current_user_can( 'manage_options' ) ) {
      exit; // Get out of here, the nonce is rotten!
    }
    
    // Get Current Theme
    $theme = get_option('stylesheet');

    // Activate Royal Elementor Kit Theme
    if ( 'ashe-pro-premium' !== $theme && 'bard-pro-premium' !== $theme
        && 'vayne-pro-premium' !== $theme && 'kayn-pro-premium' !== $theme ) {
        switch_theme( 'royal-elementor-kit' );
        set_transient( 'royal-elementor-kit_activation_notice', true );
    }

    // TODO: maybe return back  - 'ashe' !== $theme && 'bard' !== $theme && 
}


/** Function wpr_import_templates_kit() called by wp_ajax hooks: {'wpr_import_templates_kit'} **/
/** Parameters found in function wpr_import_templates_kit(): {"post": ["nonce", "wpr_templates_kit", "wpr_templates_kit_single"]} **/
function wpr_import_templates_kit() {

    $nonce = $_POST['nonce'];

    if ( !wp_verify_nonce( $nonce, 'wpr-templates-kit-js' )  || !current_user_can( 'manage_options' ) ) {
      exit; // Get out of here, the nonce is rotten!
    }

    /**
    ** Add .xml and .svg files as supported format in the uploader.
    */
    function wpr_custom_upload_mimes( $mimes ) {
        // Allow SVG files.
        $mimes['svg']  = 'image/svg+xml';
        $mimes['svgz'] = 'image/svg+xml';
    
        // Allow XML files.
        $mimes['xml'] = 'text/xml';
    
        // Allow JSON files.
        $mimes['json'] = 'application/json';
    
        return $mimes;
    }
    
    add_filter( 'upload_mimes', 'wpr_custom_upload_mimes', 99 );
    
    /**
    * Sanitize SVG files on upload.
    */
    function wpr_sanitize_svg_on_upload($file) {
        if ($file['type'] === 'image/svg+xml') {
            $file_content = file_get_contents($file['tmp_name']);
            $sanitized_content = wpr_sanitize_svg($file_content);
            file_put_contents($file['tmp_name'], $sanitized_content);
        }
        return $file;
    }
    
    add_filter('wp_handle_upload_prefilter', 'wpr_sanitize_svg_on_upload');
     
     /**
     * Sanitize SVG content.
     *
     * @param string $svg_content The SVG content to sanitize.
     * @return string The sanitized SVG content.
     */
    function wpr_sanitize_svg($svg_content) {
        $dom = new DOMDocument();
        // Use LIBXML_NONET to prevent XXE via external entity resolution; avoid LIBXML_NOENT and LIBXML_DTDLOAD.
        $dom->loadXML($svg_content, LIBXML_NONET);
     
        // Remove scripts
        $scripts = $dom->getElementsByTagName('script');
        while ($scripts->length > 0) {
            $scripts->item(0)->parentNode->removeChild($scripts->item(0));
        }
     
        // Remove external entities
        // Additional sanitization can be added here as needed
     
        return $dom->saveXML();
    }

    // Temp Define Importers
    if ( ! defined('WP_LOAD_IMPORTERS') ) {
        define('WP_LOAD_IMPORTERS', true);
    }

    // Include if Class Does NOT Exist
    if ( ! class_exists( 'WP_Import' ) ) {
        $class_wp_importer = WPR_ADDONS_PATH .'admin/import/class-wordpress-importer.php';
        if ( file_exists( $class_wp_importer ) ) {
            require $class_wp_importer;
        }
    }

    if ( class_exists( 'WP_Import' ) ) {
        $kit = isset($_POST['wpr_templates_kit']) ? sanitize_file_name(wp_unslash($_POST['wpr_templates_kit'])) : '';
        $file = isset($_POST['wpr_templates_kit_single']) ? sanitize_file_name(wp_unslash($_POST['wpr_templates_kit_single'])) : '';

        // Tmp
        update_option( 'wpr-import-kit-id', $kit );

        // Disable Extra Image Sizes
        add_filter( 'intermediate_image_sizes_advanced', [new Utilities, 'disable_extra_image_sizes'], 10, 3 );

        // No Limit for Execution
        set_time_limit(0);

        // Download Import File
        $local_file_path = download_template( $kit, $file );

        // Prepare for Import
        $wp_import = new WP_Import( $local_file_path, ['fetch_attachments' => true] );

        // Pre Register Custom Post Types
        wpr_register_cpt_before_import( $kit );

        // Import
        ob_start();
            $wp_import->run();
        ob_end_clean();

        // Delete Import File
        unlink( $local_file_path );

        // Send to JS
        echo esc_html(serialize( $wp_import ));
    }

}


/** Function wpr_final_settings_setup() called by wp_ajax hooks: {'wpr_final_settings_setup'} **/
/** Parameters found in function wpr_final_settings_setup(): {"post": ["nonce"]} **/
function wpr_final_settings_setup() {

    $nonce = $_POST['nonce'];

    if ( !wp_verify_nonce( $nonce, 'wpr-templates-kit-js' )  || !current_user_can( 'manage_options' ) ) {
      exit; // Get out of here, the nonce is rotten!
    }
    
    $kit = !empty(get_option('wpr-import-kit-id')) ? esc_html(get_option('wpr-import-kit-id')) : '';

    // Elementor Site Settings
    import_elementor_site_settings($kit);

    // Setup WPR Templates
    setup_wpr_templates($kit);

    // Fix Elementor Images
    wpr_fix_elementor_images();

    // Track Kit
    wpr_track_imported_kit( $kit );

    // Clear DB
    delete_option('wpr-import-kit-id');

    // Delete Hello World Post
    $post = get_page_by_path('hello-world', OBJECT, 'post');
    if ( $post ) {
        wp_delete_post($post->ID,true);
    }

    // Regenerate Extra Image Sizes
    Utilities::regenerate_extra_image_sizes();
}


/** Function wpr_create_template() called by wp_ajax hooks: {'wpr_create_template'} **/
/** Parameters found in function wpr_create_template(): {"post": ["nonce", "user_template_type", "user_template_library", "user_template_title", "user_template_slug"]} **/
function wpr_create_template() {

		$nonce = $_POST['nonce'];

		if ( !wp_verify_nonce( $nonce, 'wpr-plugin-options-js')  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

		$user_template_type = isset($_POST['user_template_type']) ? sanitize_text_field(wp_unslash($_POST['user_template_type'])): false;
		$user_template_library = isset($_POST['user_template_library']) ? sanitize_text_field(wp_unslash($_POST['user_template_library'])): false;
		$user_template_title = isset($_POST['user_template_title']) ? sanitize_text_field(wp_unslash($_POST['user_template_title'])): false;
		$user_template_slug = isset($_POST['user_template_slug']) ? sanitize_text_field(wp_unslash($_POST['user_template_slug'])): false;
		
		$check_post_type =( $user_template_library == 'wpr_templates' || $user_template_library == 'elementor_library' );

		if ( $user_template_title && $check_post_type ) {
			// Create
			$template_id = wp_insert_post(array (
				'post_type' 	=> $user_template_library,
				'post_title' 	=> $user_template_title,
				'post_name' 	=> $user_template_slug,
				'post_content' 	=> '',
				'post_status' 	=> 'publish'
			));

			// Set Types
			if ( 'wpr_templates' === $_POST['user_template_library'] ) {

				wp_set_object_terms( $template_id, [$user_template_type, 'user'], 'wpr_template_type' );

				if ( 'popup' === $_POST['user_template_type'] ) {
					update_post_meta( $template_id, '_elementor_template_type', 'wpr-popups' );
				} else {
					if ( 'header' === $_POST['user_template_type'] ) {
						update_post_meta( $template_id, '_elementor_template_type', 'wpr-theme-builder-header' );
					} elseif ( 'footer' === $_POST['user_template_type'] ) {
						update_post_meta( $template_id, '_elementor_template_type', 'wpr-theme-builder-footer' );
					} else {
						update_post_meta( $template_id, '_elementor_template_type', 'wpr-theme-builder' );
					}

					update_post_meta( $template_id, '_wpr_template_type', $user_template_type );
				}
			} else {
				update_post_meta( $template_id, '_elementor_template_type', 'page' );
			}

			// Set Canvas Template
			update_post_meta( $template_id, '_wp_page_template', 'elementor_canvas' ); //tmp - maybe set for wpr_templates only

			// Send ID to JS
			echo esc_html($template_id);
		}
	}


/** Function remove_from_compare() called by wp_ajax hooks: {'nopriv_remove_from_compare', 'remove_from_compare'} **/
/** Parameters found in function remove_from_compare(): {"post": ["nonce", "product_id"]} **/
function remove_from_compare() {
        $nonce = $_POST['nonce'];

        if ( ! wp_verify_nonce( $nonce, 'wpr-addons-js' ) || ! isset( $_POST['product_id'] ) ) {
            return;
        }
        
        $product_id = intval( $_POST['product_id'] );
        
        $user_id = get_current_user_id();

        if ($user_id > 0) {
            $compare = get_user_meta($user_id, 'wpr_compare', true);
            if (!$compare) {
                $compare = array();
            }
        } else {
            $compare = $this->get_compare_from_cookie();
        }
    
        $compare = array_diff($compare, array($product_id));
    
        if ($user_id > 0) {
            update_user_meta($user_id, 'wpr_compare', $compare);
        } else {
            $this->set_compare_to_cookie($compare);
        }

        // $compare = get_user_meta( get_current_user_id(), 'wpr_compare', true );
        // if ( ! $compare ) {
        //     $compare = array();
        // }
        // $compare = array_diff( $compare, array( $product_id ) );
        // update_user_meta( get_current_user_id(), 'wpr_compare', $compare );

        wp_send_json_success();
    }


/** Function wpr_activate_required_plugins() called by wp_ajax hooks: {'wpr_activate_required_plugins'} **/
/** Parameters found in function wpr_activate_required_plugins(): {"post": ["nonce", "plugin"]} **/
function wpr_activate_required_plugins() {

    $nonce = $_POST['nonce'];

    if ( !wp_verify_nonce( $nonce, 'wpr-templates-kit-js')  || !current_user_can( 'manage_options' ) ) {
      exit; // Get out of here, the nonce is rotten!
    }

    if ( isset($_POST['plugin']) ) {
        if ( 'contact-form-7' == $_POST['plugin'] ) {
            if ( !is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
                activate_plugin( 'contact-form-7/wp-contact-form-7.php' );
            }
        } elseif ( 'woocommerce' == $_POST['plugin'] ) {
            if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
                activate_plugin( 'woocommerce/woocommerce.php' );
            }
        } elseif ( 'media-library-assistant' == $_POST['plugin'] ) {
            if ( !is_plugin_active( 'media-library-assistant/index.php' ) ) {
                activate_plugin( 'media-library-assistant/index.php' );
            }
        } elseif ( 'royal-backup-reset' == $_POST['plugin'] ) {
            if ( !is_plugin_active( 'royal-backup-reset/royal-backup-reset.php' ) ) {
                activate_plugin( 'royal-backup-reset/royal-backup-reset.php' );
            }
        }
    }
}


/** Function render_library_templates_pages() called by wp_ajax hooks: {'render_library_templates_pages'} **/
/** No params detected :-/ **/


/** Function _toggle_debug_mode() called by wp_ajax hooks: {'fs_toggle_debug_mode'} **/
/** No params detected :-/ **/


/** Function wpr_set_pending_template() called by wp_ajax hooks: {'wpr_set_pending_template'} **/
/** Parameters found in function wpr_set_pending_template(): {"post": ["nonce", "pending_edit_url", "pending_template_name"]} **/
function wpr_set_pending_template() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';

		if ( ! wp_verify_nonce( $nonce, 'wpr-plugin-options-js' ) || ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( [ 'message' => 'Unauthorized' ] );
		}

		if ( isset( $_POST['pending_edit_url'] ) && ! empty( $_POST['pending_edit_url'] ) ) {
			$template_data = array(
				'url'  => sanitize_url( $_POST['pending_edit_url'] ),
				'name' => isset( $_POST['pending_template_name'] ) ? sanitize_text_field( $_POST['pending_template_name'] ) : '',
			);
			set_transient( 'wpr_pending_template_edit', $template_data, 5 * MINUTE_IN_SECONDS );
		}

		wp_send_json_success();
	}


/** Function wpr_plugin_sale_dismiss_notice() called by wp_ajax hooks: {'wpr_plugin_sale_dismiss_notice'} **/
/** Parameters found in function wpr_plugin_sale_dismiss_notice(): {"post": ["nonce"]} **/
function wpr_plugin_sale_dismiss_notice() {
		$nonce = $_POST['nonce'];

		if ( !wp_verify_nonce( $nonce, 'wpr-plugin-notice-js')  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}
        
        add_option( 'wpr_plugin_sale_dismiss_notice', true );
    }


/** Function wpr_dismiss_backup_popup() called by wp_ajax hooks: {'wpr_dismiss_backup_popup'} **/
/** Parameters found in function wpr_dismiss_backup_popup(): {"post": ["nonce"]} **/
function wpr_dismiss_backup_popup() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';

		if ( ! wp_verify_nonce( $nonce, 'wpr-plugin-options-js' ) || ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( [ 'message' => 'Unauthorized' ] );
		}

		$user_id = get_current_user_id();
		update_user_meta( $user_id, 'wpr_dismiss_backup_popup', '1' );

		wp_send_json_success();
	}


/** Function wpr_reset_previous_import() called by wp_ajax hooks: {'wpr_reset_previous_import'} **/
/** Parameters found in function wpr_reset_previous_import(): {"post": ["nonce"]} **/
function wpr_reset_previous_import() {

    $nonce = $_POST['nonce'];

    if ( !wp_verify_nonce( $nonce, 'wpr-templates-kit-js' )  || !current_user_can( 'manage_options' ) ) {
      exit; // Get out of here, the nonce is rotten!
    }
    
    $args = [
        'post_type' => [
            'page',
            'post',
            'product',
            'wpr_mega_menu',
            'wpr_templates',
            'elementor_library',
            'attachment',

            'acf-post-type',
            'acf-taxonomy',
            'acf-field-group',

            'wpr_portfolio'
        ],
        'post_status' => 'any',
        'posts_per_page' => '-1',
        'meta_key' => '_wpr_demo_import_item'
    ];

    $imported_items = new WP_Query ( $args );

    if ( $imported_items->have_posts() ) {
        while ( $imported_items->have_posts() ) {
            $imported_items->the_post();
        
            // Dont Delete Elementor Kit
            if ( 'Default Kit' == get_the_title() ) {
                continue;
            }

            // Delete Posts
            wp_delete_post( get_the_ID(), true );
        }

        // Reset
        wp_reset_query();

        $imported_terms = get_terms([
            'meta_key' => '_wpr_demo_import_item',
            'posts_per_page' => -1,
            'hide_empty' => false,
        ]);

        if ( !empty($imported_terms) ) {
            foreach( $imported_terms as $imported_term ) {
                // Delete Terms
                wp_delete_term( $imported_term->term_id, $imported_term->taxonomy );
            }
        }

        wp_send_json_success( esc_html__('Previous Import Files have been successfully Reset.', 'wpr-addons') );
    } else {
        wp_send_json_success( esc_html__('There is no Data for Reset.', 'wpr-addons') );
    }
}


/** Function check_product_in_compare_grid() called by wp_ajax hooks: {'check_product_in_compare_grid', 'nopriv_check_product_in_compare_grid'} **/
/** No params detected :-/ **/


/** Function render_library_templates_sections() called by wp_ajax hooks: {'render_library_templates_sections'} **/
/** No params detected :-/ **/


/** Function wpr_install_news_magazine_x_theme() called by wp_ajax hooks: {'wpr_install_news_magazine_x_theme'} **/
/** Parameters found in function wpr_install_news_magazine_x_theme(): {"post": ["nonce", "theme"]} **/
function wpr_install_news_magazine_x_theme() {
    $nonce = $_POST['nonce'];

    if ( !wp_verify_nonce( $nonce, 'wpr-templates-kit-js' )  || !current_user_can( 'manage_options' ) ) {
      exit; // Get out of here, the nonce is rotten!
    }
    
    if ( !current_user_can('switch_themes') ) {
        wp_send_json_error('Permission denied');
    }

    $theme = sanitize_text_field($_POST['theme']);
    switch_theme($theme);

    // Deactivate Elementor and Royal Elementor Addons
    if ( is_plugin_active( 'elementor/elementor.php' ) ) {
        deactivate_plugins( 'elementor/elementor.php' );
    }

    if ( is_plugin_active( 'royal-elementor-addons/wpr-addons.php' ) ) {
        deactivate_plugins( 'royal-elementor-addons/wpr-addons.php' );
    }

    if ( is_plugin_active( 'royal-elementor-addons-pro/wpr-addons-pro.php' ) ) {
        deactivate_plugins( 'royal-elementor-addons-pro/wpr-addons-pro.php' );
    }

    wp_send_json_success();
}


/** Function add_to_wishlist() called by wp_ajax hooks: {'add_to_wishlist', 'nopriv_add_to_wishlist'} **/
/** Parameters found in function add_to_wishlist(): {"post": ["nonce", "product_id"]} **/
function add_to_wishlist() {
        $nonce = $_POST['nonce'];

        if ( ! wp_verify_nonce($nonce, 'wpr-addons-js') || ! isset( $_POST['product_id'] ) ) {
            return;
        }
        
        $product_id = intval( $_POST['product_id'] );
        $user_id = get_current_user_id();
        
        // NEW CODE
        if ($user_id > 0) {
            $wishlist = get_user_meta( get_current_user_id(), 'wpr_wishlist', true );
            $wishlist = get_user_meta($user_id, 'wpr_wishlist', true);
            if (!$wishlist) {
                $wishlist = array();
            }
        } else {
            $wishlist = $this->get_wishlist_from_cookie();
        }
    
        if (in_array($product_id, $wishlist)) {
            wp_send_json_error(array('message' => esc_html__('Product is already in wishlist.', 'wpr-addons')));
            return;
        }
    
        $wishlist[] = $product_id;
    
        if ($user_id > 0) {
            update_user_meta($user_id, 'wpr_wishlist', $wishlist);
        } else {
            $this->set_wishlist_to_cookie($wishlist);
        }

        wp_send_json_success();
    }


/** Function wpr_woo_grid_filters_ajax() called by wp_ajax hooks: {'nopriv_wpr_woo_grid_filters_ajax', 'wpr_woo_grid_filters_ajax'} **/
/** Parameters found in function wpr_woo_grid_filters_ajax(): {"post": ["nonce", "grid_settings"]} **/
function wpr_woo_grid_filters_ajax() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'wpr-addons-js' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Security check failed.', 'wpr-addons' ) ) );
		}
		if ( ! isset( $_POST['grid_settings'] ) || ! is_array( $_POST['grid_settings'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid request.', 'wpr-addons' ) ) );
		}
		$start = microtime(true);
		// Get Settings
		$settings = wp_unslash( $_POST['grid_settings'] );

		ob_start();

		// Get Posts
		$posts = new \WP_Query( WPR_Woo_Grid_Helpers::get_main_query_args($settings, []) );

		// Loop: Start
		if ( $posts->have_posts() ) :

		while ( $posts->have_posts() ) : $posts->the_post();

			// Post Class
			$post_class = implode( ' ', get_post_class( 'wpr-grid-item elementor-clearfix', get_the_ID() ) );

			// Grid Item
			echo '<article class="'. esc_attr( $post_class ) .'">';

			// Password Protected Form
			WPR_Woo_Grid_Helpers::render_password_protected_input( $settings );

			// Inner Wrapper
			echo '<div class="wpr-grid-item-inner">';

			// Content: Above Media
			WPR_Woo_Grid_Helpers::get_elements_by_location( 'above', $settings, get_the_ID() );

			// Media
			if ( has_post_thumbnail() ) {
				echo '<div class="wpr-grid-media-wrap'. esc_attr(WPR_Woo_Grid_Helpers::get_image_effect_class( $settings )) .' " data-overlay-link="'. esc_attr( $settings['overlay_post_link'] ) .'">';
					// Post Thumbnail
					if ( !empty($settings) ) {
						WPR_Woo_Grid_Helpers::render_product_thumbnail( $settings, get_the_ID() );
					}

					// Media Hover
					echo '<div class="wpr-grid-media-hover wpr-animation-wrap">';
						// Media Overlay
						WPR_Woo_Grid_Helpers::render_media_overlay( $settings );

						// Content: Over Media
						WPR_Woo_Grid_Helpers::get_elements_by_location( 'over', $settings, get_the_ID() );

					echo '</div>';
				echo '</div>';
			}

			// Content: Below Media
			WPR_Woo_Grid_Helpers::get_elements_by_location( 'below', $settings, get_the_ID() );

			echo '</div>'; // End .wpr-grid-item-inner

			echo '</article>'; // End .wpr-grid-item

		endwhile;

		// reset
		wp_reset_postdata();

		// Loop: End
		else :

			if ( 'dynamic' === $settings['query_selection'] || 'current' === $settings['query_selection'] ) {
				echo '<h2>'. esc_html($settings['query_not_found_text']) .'</h2>';
			}

		endif;

		// Get the buffered content
		$output = ob_get_clean();
	
		// Return the output
		$end = microtime(true);
		$duration = round(($end - $start) * 1000, 2); // in ms
		wp_send_json_success([
			'output' => $output,
			'duration' => $duration,
			'found_posts' => $posts->found_posts,
			'post_count' => $posts->post_count
		]);

		wp_die();
	}


