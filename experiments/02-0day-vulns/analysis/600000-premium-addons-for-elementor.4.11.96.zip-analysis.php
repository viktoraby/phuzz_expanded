<?php
/***
*
*Found actions: 62
*Found functions:49
*Extracted functions:49
*Total parameter names extracted: 37
*Overview: {'pa_get_editor_template': {'pa_get_editor_template'}, 'pa_save_menu_item_settings': {'pa_save_menu_item_settings'}, 'clear_site_cursor_settings': {'pa_clear_site_cursor_settings'}, 'get_tiktok_token': {'get_tiktok_token'}, 'get_posts_list': {'premium_update_filter'}, 'get_shape_divider_svg': {'get_shape_divider_svg'}, 'pa_save_global_btn': {'pa_save_global_btn'}, 'insert_inner_template': {'premium_inner_template'}, 'pa_clear_cached_assets': {'pa_clear_cached_assets'}, 'pa_update_mc_qty': {'pa_update_mc_qty', 'nopriv_pa_update_mc_qty'}, 'add_product_to_cart': {'nopriv_premium_woo_add_cart_product', 'premium_woo_add_cart_product'}, 'pa_delete_cart_items': {'nopriv_pa_delete_cart_items', 'pa_delete_cart_items'}, 'pa_scan_widgets_usage': {'pa_scan_widgets_usage'}, 'pa_save_mega_item_content': {'pa_save_mega_item_content'}, 'get_templates': {'premium_get_templates'}, 'get_search_results': {'premium_get_search_results', 'nopriv_premium_get_search_results'}, 'get_pa_element_data': {'get_pa_element_data'}, 'pa_disable_elementor_mc_template': {'pa_disable_elementor_mc_template'}, 'pa_get_menu_item_settings': {'pa_get_menu_item_settings'}, 'handle_pa_woo_cta_actions': {'handle_pa_woo_cta_actions', 'nopriv_handle_pa_woo_cta_actions'}, 'pa_enable_oauth_connect': {'pa_enable_oauth_connect'}, 'get_woo_products': {'get_woo_products', 'nopriv_get_woo_products'}, 'dismiss_admin_notice': {'pa_dismiss_admin_notice'}, 'subscribe_newsletter': {'subscribe_newsletter'}, 'get_woo_product_quick_view': {'get_woo_product_qv', 'nopriv_get_woo_product_qv'}, 'pa_check_unused_widgets': {'pa_check_unused_widgets'}, 'pa_disable_oauth_connect': {'pa_disable_oauth_connect'}, 'pa_save_ai_abilities': {'pa_save_ai_abilities'}, 'check_temp_validity': {'check_temp_validity'}, 'get_shape_svgs': {'pa_get_shape_svgs'}, 'insert_cf_form': {'insert_cf_form'}, 'get_pinterest_token': {'get_pinterest_token'}, 'reset_admin_notice': {'pa_reset_admin_notice'}, 'pa_delete_cart_item': {'nopriv_pa_delete_cart_item', 'pa_delete_cart_item'}, 'pa_apply_coupon': {'pa_apply_coupon', 'nopriv_pa_apply_coupon'}, 'pa_remove_coupon': {'pa_remove_coupon', 'nopriv_pa_remove_coupon'}, 'pa_save_elements_settings': {'pa_save_elements_settings'}, 'pa_disable_unused_widgets': {'pa_disable_unused_widgets'}, 'handle_live_editor': {'handle_live_editor'}, 'get_template_content': {'nopriv_get_elementor_template_content', 'get_elementor_template_content'}, 'get_pinterest_boards': {'get_pinterest_boards'}, 'get_posts_query': {'pa_get_posts', 'nopriv_pa_get_posts'}, 'get_related_tax': {'premium_update_tax'}, 'pa_hide_unused_widgets_dialog': {'pa_hide_unused_widgets_dialog'}, 'send': {'pa_send_element_feedback', 'pa_handle_feedback_action'}, 'get_acf_options': {'pa_acf_options'}, 'update_template_title': {'update_template_title'}, 'pa_save_additional_settings': {'pa_save_additional_settings'}, 'cross_cp_fetch_content_data': {'premium_cross_cp_import'}}
*
***/

/** Function pa_get_editor_template() called by wp_ajax hooks: {'pa_get_editor_template'} **/
/** Parameters found in function pa_get_editor_template(): {"post": ["tempTitle", "type"]} **/
function pa_get_editor_template() {

			check_ajax_referer( 'pa-live-editor', 'security' );

			if ( ! isset( $_POST['tempTitle'] ) ) { // template title.
				wp_send_json_error( 'Template title not found', 404 );
			}

			$temp_title = sanitize_text_field( wp_unslash( $_POST['tempTitle'] ) );
			$temp_type  = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : false;

			$decoded_title = html_entity_decode( $temp_title );

			$args = array(
				'post_type'        => 'elementor_library',
				'post_status'      => 'publish',
				'posts_per_page'   => 1,
				'title'            => $decoded_title,
				'suppress_filters' => true,
			);

			$query = new \WP_Query( $args );

			$post_id = '';

			if ( $query->have_posts() ) {
				$post_id = $query->post->ID;

				$edit_url = get_admin_url() . '/post.php?post=' . $post_id . '&action=elementor';

				$result = array(
					'url'         => $edit_url,
					'id'          => $post_id,
					'title'       => $temp_title,
					'newFunction' => 'hello',
				);

				wp_reset_postdata();

				wp_send_json_success( $result );

			} else {
				wp_send_json_error( 'Template not found.....' . $decoded_title, 404 );
			}
		}


/** Function pa_save_menu_item_settings() called by wp_ajax hooks: {'pa_save_menu_item_settings'} **/
/** Parameters found in function pa_save_menu_item_settings(): {"post": ["settings"]} **/
function pa_save_menu_item_settings() {

		check_ajax_referer( 'pa-menu-nonce', 'security' );

		if ( ! self::check_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'User is not authorized!' );
		}

		if ( ! isset( $_POST['settings'] ) ) {
			wp_send_json_error( 'Settings are not set!' );
		}

		$settings = array_map(
			function ( $setting ) {
				return htmlspecialchars( $setting, ENT_QUOTES );
			},
			wp_unslash( $_POST['settings'] ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		);

		update_post_meta( $settings['item_id'], 'pa_megamenu_item_meta', wp_json_encode( $settings, JSON_UNESCAPED_UNICODE ) );

		wp_send_json_success( $settings );
	}


/** Function clear_site_cursor_settings() called by wp_ajax hooks: {'pa_clear_site_cursor_settings'} **/
/** No params detected :-/ **/


/** Function get_tiktok_token() called by wp_ajax hooks: {'get_tiktok_token'} **/
/** No params detected :-/ **/


/** Function get_posts_list() called by wp_ajax hooks: {'premium_update_filter'} **/
/** Parameters found in function get_posts_list(): {"post": ["post_type"]} **/
function get_posts_list() {

		check_ajax_referer( 'pa-blog-widget-nonce', 'nonce' );

		$post_type = isset( $_POST['post_type'] ) ? wp_unslash( $_POST['post_type'] ) : '';

		$post_type = array_map( 'sanitize_text_field', $post_type );

		if ( empty( $post_type ) ) {
			wp_send_json_error( __( 'Empty Post Type.', 'premium-addons-for-elementor' ) );
		}

		$args = array(
			'post_type'              => $post_type,
			'posts_per_page'         => -1,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
		);

		// Exclude the premium-grid and loop-items templates for 'elementor_library' source.
		if ( in_array( 'elementor_library', $post_type, true ) ) {
			$args['meta_query'] = array(
				array(
					'key'     => '_elementor_template_type',
					'value'   => array( 'premium-grid', 'loop-item' ),
					'compare' => 'NOT IN',
				),
			);
		}

		$list = get_posts( $args );

		$options = array();

		if ( ! empty( $list ) && ! is_wp_error( $list ) ) {

			foreach ( $list as $post ) {
				$key             = in_array( 'elementor_library', $post_type, true ) ? $post->post_title : $post->ID;
				$options[ $key ] = $post->post_title;
			}
		}

		wp_send_json_success( wp_json_encode( $options ) );
	}


/** Function get_shape_divider_svg() called by wp_ajax hooks: {'get_shape_divider_svg'} **/
/** Parameters found in function get_shape_divider_svg(): {"post": ["shape"]} **/
function get_shape_divider_svg() {

		check_ajax_referer( 'pa-shape-nonce', 'nonce' );

		if ( ! isset( $_POST['shape'] ) ) {
			wp_send_json_error( 'No shape selected' );
		}

		$shape = sanitize_text_field( wp_unslash( $_POST['shape'] ) );

		$svg_shape = Helper_Functions::get_svg_shapes( $shape );

		if ( empty( $svg_shape ) ) {
			wp_send_json_error( 'Invalid shape' );
		}

		wp_send_json_success( array( 'shape' => $svg_shape ) );
	}


/** Function pa_save_global_btn() called by wp_ajax hooks: {'pa_save_global_btn'} **/
/** Parameters found in function pa_save_global_btn(): {"post": ["isGlobalOn"]} **/
function pa_save_global_btn() {

		check_ajax_referer( 'pa-settings-tab', 'security' );

		if ( ! self::check_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'You are not allowed to do this action', 'premium-addons-for-elementor' ) );
		}

		if ( ! isset( $_POST['isGlobalOn'] ) ) {
			wp_send_json_error();
		}

		$global_btn_value = sanitize_text_field( wp_unslash( $_POST['isGlobalOn'] ) );

		update_option( 'pa_global_btn_value', $global_btn_value );

		wp_send_json_success();
	}


/** Function insert_inner_template() called by wp_ajax hooks: {'premium_inner_template'} **/
/** Parameters found in function insert_inner_template(): {"request": ["template", "withMedia", "title"]} **/
function insert_inner_template() {

			check_ajax_referer( 'pa-templates-nonce', 'nonce' );

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_send_json_error();
			}

			$template_id = isset( $_REQUEST['template'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['template'] ) ) : false;

			if ( ! $template_id ) {
				wp_send_json_error();
			}

			$source       = $this->sources['premium-api'];
			$insert_media = isset( $_REQUEST['withMedia'] )
				? filter_var( wp_unslash( $_REQUEST['withMedia'] ), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE ) ?? true
				: true;

			if ( ! $source ) {
				wp_send_json_error();
			}

			$template_data = $source->get_item( $template_id, $insert_media );

			if ( ! empty( $template_data['content'] ) ) {

				$template_title = isset( $_REQUEST['title'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['title'] ) ) : 'Template' . $template_id;

				wp_insert_post(
					array(
						'post_type'   => 'elementor_library',
						'post_title'  => $template_title,
						'post_status' => 'publish',
						'meta_input'  => array(
							'_elementor_data'          => $template_data['content'],
							'_elementor_edit_mode'     => 'builder',
							'_elementor_template_type' => 'section',
						),
					)
				);
			}

			wp_send_json_success();
		}


/** Function pa_clear_cached_assets() called by wp_ajax hooks: {'pa_clear_cached_assets'} **/
/** Parameters found in function pa_clear_cached_assets(): {"post": ["id"]} **/
function pa_clear_cached_assets() {

		check_ajax_referer( 'pa-generate-nonce', 'security' );

		$post_id = isset( $_POST['id'] ) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : '';
		$this->clear_dynamic_assets_data( $post_id );

		wp_send_json_success( 'Cached Assets Cleared' );
	}


/** Function pa_update_mc_qty() called by wp_ajax hooks: {'pa_update_mc_qty', 'nopriv_pa_update_mc_qty'} **/
/** Parameters found in function pa_update_mc_qty(): {"post": ["itemKey", "quantity"]} **/
function pa_update_mc_qty() {

		check_ajax_referer( 'pa-mini-cart-nonce', 'nonce' );

		if ( ! isset( $_POST['itemKey'] ) || ! isset( $_POST['quantity'] ) ) {
			return;
		}

		$item_key = sanitize_text_field( wp_unslash( $_POST['itemKey'] ) );
		$quantity = absint( wp_unslash( $_POST['quantity'] ) );

		if ( $quantity > 0 && WC()->cart->get_cart_item( $_POST['itemKey'] ) ) {
			WC()->cart->set_quantity( $_POST['itemKey'], $_POST['quantity'], true );
		}

		\WC_AJAX::get_refreshed_fragments();

		wp_send_json_success();
	}


/** Function add_product_to_cart() called by wp_ajax hooks: {'nopriv_premium_woo_add_cart_product', 'premium_woo_add_cart_product'} **/
/** Parameters found in function add_product_to_cart(): {"post": ["product_id", "variation_id", "quantity"]} **/
function add_product_to_cart() {

		check_ajax_referer( 'pa-woo-cta-nonce', 'nonce' );

		$product_id   = isset( $_POST['product_id'] ) ? sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) : 0;
		$variation_id = isset( $_POST['variation_id'] ) ? sanitize_text_field( wp_unslash( $_POST['variation_id'] ) ) : 0;
		$quantity     = isset( $_POST['quantity'] ) ? sanitize_text_field( wp_unslash( $_POST['quantity'] ) ) : 0;

		if ( $variation_id ) {
			WC()->cart->add_to_cart( $product_id, $quantity, $variation_id );
		} else {
			WC()->cart->add_to_cart( $product_id, $quantity );
		}
		die();
	}


/** Function pa_delete_cart_items() called by wp_ajax hooks: {'nopriv_pa_delete_cart_items', 'pa_delete_cart_items'} **/
/** No params detected :-/ **/


/** Function pa_scan_widgets_usage() called by wp_ajax hooks: {'pa_scan_widgets_usage'} **/
/** Parameters found in function pa_scan_widgets_usage(): {"post": ["offset"]} **/
function pa_scan_widgets_usage() {

		check_ajax_referer( 'pa-disable-unused', 'security' );

		if ( ! self::check_user_can( 'install_plugins' ) ) {
			wp_send_json_error( __( 'You are not allowed to do this action', 'premium-addons-for-elementor' ) );
		}

		$offset = isset( $_POST['offset'] ) ? absint( wp_unslash( $_POST['offset'] ) ) : 0;

		$progress = self::scan_widgets_usage( $offset );

		if ( ! $progress ) {
			wp_send_json_error( __( 'Elementor usage data is not available on this site.', 'premium-addons-for-elementor' ) );
		}

		wp_send_json_success( $progress );
	}


/** Function pa_save_mega_item_content() called by wp_ajax hooks: {'pa_save_mega_item_content'} **/
/** Parameters found in function pa_save_mega_item_content(): {"post": ["template_id", "menu_item_id"]} **/
function pa_save_mega_item_content() {

		check_ajax_referer( 'pa-live-editor', 'security' );

		if ( ! self::check_user_can( 'edit_theme_options' ) ) {
			wp_send_json_error( 'Insufficient user permission' );
		}

		if ( ! isset( $_POST['template_id'] ) ) {
			wp_send_json_error( 'template id is not set!' );
		}

		if ( ! isset( $_POST['menu_item_id'] ) ) {
			wp_send_json_error( 'item id is not set!' );
		}

		$item_id = sanitize_text_field( wp_unslash( $_POST['menu_item_id'] ) );
		$temp_id = sanitize_text_field( wp_unslash( $_POST['template_id'] ) );

		update_post_meta( $item_id, 'pa_mega_content_temp', $temp_id );

		wp_send_json_success( 'Item Mega Content Saved' );
	}


/** Function get_templates() called by wp_ajax hooks: {'premium_get_templates'} **/
/** Parameters found in function get_templates(): {"get": ["tab"]} **/
function get_templates() {

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_send_json_error();
			}

			$tab     = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : '';
			$tabs    = $this->get_template_tabs();
			$sources = $tabs[ $tab ]['sources'];

			if ( 'premium_container' === $tab ) {
				$tab = 'premium_section';
			}

			$result = array(
				'templates'  => array(),
				'categories' => array(),
				'keywords'   => array(),
			);

			foreach ( $sources as $source_slug ) {

				$source = isset( $this->sources[ $source_slug ] ) ? $this->sources[ $source_slug ] : false;

				if ( $source ) {
					$result['templates']  = array_merge( $result['templates'], $source->get_items( $tab ) );
					$result['categories'] = array_merge( $result['categories'], $source->get_categories( $tab ) );
					$result['keywords']   = array_merge( $result['keywords'], $source->get_keywords( $tab ) );
				}
			}

			$all_cats = array(
				array(
					'slug'  => '',
					'title' => __( 'All', 'premium-addons-for-elementor' ),
				),
			);

			if ( ! empty( $result['categories'] ) ) {
				$result['categories'] = array_merge( $all_cats, $result['categories'] );
			}

			wp_send_json_success( $result );
		}


/** Function get_search_results() called by wp_ajax hooks: {'premium_get_search_results', 'nopriv_premium_get_search_results'} **/
/** No params detected :-/ **/


/** Function get_pa_element_data() called by wp_ajax hooks: {'get_pa_element_data'} **/
/** Parameters found in function get_pa_element_data(): {"get": ["element"]} **/
function get_pa_element_data() {

			if ( ! isset( $_GET['element'] ) ) {
				wp_send_json_error();
			}

			$key = isset( $_GET['element'] ) ? sanitize_text_field( wp_unslash( $_GET['element'] ) ) : '';

			$info = Admin_Helper::get_info_by_key( $key );

			if ( ! $info ) {
				wp_send_json_error();
			}

			$url = add_query_arg(
				array(
					'page'   => 'premium-addons',
					'search' => $info['title'],
					'#tab'   => 'elements',
				),
				esc_url( admin_url( 'admin.php' ) )
			);

			$demo_link = strstr( $info['demo'], '/?', true );

			$demo_link = Helper_Functions::get_campaign_link( $demo_link, 'template-link', 'wp-editor', 'template-issues' );

			$data = array(
				'name'      => $info['title'],
				'widgetURL' => $demo_link,
				'url'       => $url,
			);

			wp_send_json_success( $data );
		}


/** Function pa_disable_elementor_mc_template() called by wp_ajax hooks: {'pa_disable_elementor_mc_template'} **/
/** No params detected :-/ **/


/** Function pa_get_menu_item_settings() called by wp_ajax hooks: {'pa_get_menu_item_settings'} **/
/** Parameters found in function pa_get_menu_item_settings(): {"post": ["item_id"]} **/
function pa_get_menu_item_settings() {

		check_ajax_referer( 'pa-menu-nonce', 'security' );

		if ( ! self::check_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'User is not authorized!' );
		}

		if ( ! isset( $_POST['item_id'] ) ) {
			wp_send_json_error( 'Settings are not set!' );
		}

		$item_id       = sanitize_text_field( wp_unslash( $_POST['item_id'] ) );
		$item_settings = json_decode( get_post_meta( $item_id, 'pa_megamenu_item_meta', true ) );

		wp_send_json_success( $item_settings );
	}


/** Function handle_pa_woo_cta_actions() called by wp_ajax hooks: {'handle_pa_woo_cta_actions', 'nopriv_handle_pa_woo_cta_actions'} **/
/** Parameters found in function handle_pa_woo_cta_actions(): {"post": ["product_id", "ajaxAction", "quantity", "attributes"]} **/
function handle_pa_woo_cta_actions() {

		check_ajax_referer( 'pa-woo-cta-nonce', 'security' );

		$product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;

		if ( ! $product_id ) {
			wp_send_json_error( 'Product ID is missing.' );
			return;
		}

		$ajax_action = isset( $_POST['ajaxAction'] ) ? sanitize_text_field( wp_unslash( $_POST['ajaxAction'] ) ) : '';
		$quantity    = isset( $_POST['quantity'] ) ? intval( $_POST['quantity'] ) : 1;
		$attributes  = isset( $_POST['attributes'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['attributes'] ) ) : array();

		if ( 'custom_add_to_cart' === $ajax_action ) {
			// Handle Add to Cart logic.
			$this->custom_add_to_cart( $product_id );
		} elseif ( 'custom_add_to_wishlist' === $ajax_action ) {
			// Handle Add to Wishlist logic.
			$this->custom_add_to_wishlist( $product_id );
		} else {
			$this->custom_add_to_compare( $product_id );
		}
	}


/** Function pa_enable_oauth_connect() called by wp_ajax hooks: {'pa_enable_oauth_connect'} **/
/** No params detected :-/ **/


/** Function get_woo_products() called by wp_ajax hooks: {'get_woo_products', 'nopriv_get_woo_products'} **/
/** Parameters found in function get_woo_products(): {"post": ["pageID", "elemID", "skin"]} **/
function get_woo_products() {

		check_ajax_referer( 'pa-woo-products-nonce', 'nonce' );

		if ( ! isset( $_POST['pageID'] ) || ! isset( $_POST['elemID'] ) || ! isset( $_POST['skin'] ) ) {
			return;
		}

		$post_id   = sanitize_text_field( wp_unslash( $_POST['pageID'] ) );
		$widget_id = sanitize_text_field( wp_unslash( $_POST['elemID'] ) );
		$style_id  = sanitize_text_field( wp_unslash( $_POST['skin'] ) );

		$elementor = Plugin::$instance;
		$meta      = $elementor->documents->get( $post_id )->get_elements_data();

		$widget_data = $this->find_element_recursive( $meta, $widget_id );

		$data = array(
			'message'    => __( 'Saved', 'premium-addons-for-elementor' ),
			'ID'         => '',
			'skin_id'    => '',
			'html'       => '',
			'pagination' => '',
		);

		if ( null !== $widget_data ) {

			// Restore default values.
			$widget = $elementor->elements_manager->create_element_instance( $widget_data );

			// Return data and call your function according to your need for ajax call.
			// You will have access to settings variable as well as some widget functions.
			$skin = TemplateBlocks\Skin_Init::get_instance( $style_id );

			// Here you will just need posts based on ajax request to attache in layout.
			$html = $skin->inner_render( $style_id, $widget, true );

			$pagination = $skin->page_render( $style_id, $widget );

			$data['ID']         = $widget->get_id();
			$data['skin_id']    = $widget->get_current_skin_id();
			$data['html']       = $html;
			$data['pagination'] = $pagination;
		}

		wp_send_json_success( $data );
	}


/** Function dismiss_admin_notice() called by wp_ajax hooks: {'pa_dismiss_admin_notice'} **/
/** Parameters found in function dismiss_admin_notice(): {"post": ["notice"]} **/
function dismiss_admin_notice() {

		check_ajax_referer( 'pa-notice-nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		$key = isset( $_POST['notice'] ) ? sanitize_text_field( wp_unslash( $_POST['notice'] ) ) : '';

		if ( ! empty( $key ) && in_array( $key, self::$notices, true ) ) {

			// Make sure new features notices will not appear again.
			if ( false !== strpos( $key, 'not' ) ) {
				update_option( $key, '1', true );
			} else {
				// Was set_transient( 'pa-review', ... ), a key nothing ever read —
				// the review notice reappeared immediately after dismissal.
				update_option( self::REVIEW_OPTION, (string) ( time() + 20 * DAY_IN_SECONDS ), true );
			}

			wp_send_json_success();

		} else {

			wp_send_json_error();

		}
	}


/** Function subscribe_newsletter() called by wp_ajax hooks: {'subscribe_newsletter'} **/
/** Parameters found in function subscribe_newsletter(): {"post": ["email"]} **/
function subscribe_newsletter() {

		check_ajax_referer( 'pa-settings-tab', 'security' );

		if ( ! self::check_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

		$result = self::subscribe_newsletter_request( $email );

		wp_send_json_success( $result['response'] );
	}


/** Function get_woo_product_quick_view() called by wp_ajax hooks: {'get_woo_product_qv', 'nopriv_get_woo_product_qv'} **/
/** Parameters found in function get_woo_product_quick_view(): {"request": ["product_id"], "post": ["pageID", "elemID"]} **/
function get_woo_product_quick_view() {

		check_ajax_referer( 'pa-woo-qv-nonce', 'security' );

		if ( ! isset( $_REQUEST['product_id'] ) ) {
			die();
		}

		$post_id   = isset( $_POST['pageID'] ) ? sanitize_text_field( wp_unslash( $_POST['pageID'] ) ) : 0;
		$widget_id = isset( $_POST['elemID'] ) ? sanitize_text_field( wp_unslash( $_POST['elemID'] ) ) : 0;

		$elementor = Plugin::$instance;
		$meta      = $elementor->documents->get( $post_id )->get_elements_data();

		$widget_data = $this->find_element_recursive( $meta, $widget_id );

		if ( null === $widget_data ) {

			wp_send_json_error( 'Widget settings not found.' );

		}

		// Restore default values.
		$widget = $elementor->elements_manager->create_element_instance( $widget_data );

		$settings = $widget->get_settings();

		$this->quick_view_content_actions();

		$product_id = intval( $_REQUEST['product_id'] );

		// set the main wp query for the product.
		wp( 'p=' . $product_id . '&post_type=product' );

		ob_start();

		// load content template.
		include PREMIUM_ADDONS_PATH . 'modules/woocommerce/templates/quick-view-product.php';

		echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		die();
	}


/** Function pa_check_unused_widgets() called by wp_ajax hooks: {'pa_check_unused_widgets'} **/
/** No params detected :-/ **/


/** Function pa_disable_oauth_connect() called by wp_ajax hooks: {'pa_disable_oauth_connect'} **/
/** No params detected :-/ **/


/** Function pa_save_ai_abilities() called by wp_ajax hooks: {'pa_save_ai_abilities'} **/
/** Parameters found in function pa_save_ai_abilities(): {"post": ["disabled", "third_party"]} **/
function pa_save_ai_abilities() {

		check_ajax_referer( 'pa-settings-tab', 'security' );

		if ( ! self::check_user_can( 'manage_options' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'You are not allowed to do this action.', 'premium-addons-for-elementor' ),
				)
			);
		}

		if ( ! isset( $_POST['disabled'] ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'No AI ability settings were provided.', 'premium-addons-for-elementor' ),
				)
			);
		}

		$disabled_abilities = wp_unslash( $_POST['disabled'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Decoded values are sanitized and catalog-whitelisted before storage.
		$disabled           = is_array( $disabled_abilities ) ? $disabled_abilities : json_decode( $disabled_abilities, true );

		if ( ! is_array( $disabled ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'The AI ability settings could not be saved.', 'premium-addons-for-elementor' ),
				)
			);
		}

		$third_party = isset( $_POST['third_party'] ) ? ( '1' === sanitize_text_field( wp_unslash( $_POST['third_party'] ) ) ) : null;

		$disabled = self::save_ai_abilities_settings( $disabled, $third_party );

		wp_send_json_success(
			array(
				'message'            => __( 'AI ability settings saved.', 'premium-addons-for-elementor' ),
				'disabled_abilities' => $disabled,
			)
		);
	}


/** Function check_temp_validity() called by wp_ajax hooks: {'check_temp_validity'} **/
/** Parameters found in function check_temp_validity(): {"post": ["templateID", "tempType"]} **/
function check_temp_validity() {

			check_ajax_referer( 'pa-live-editor', 'security' );

			if ( ! isset( $_POST['templateID'] ) ) {
				wp_send_json_error( 'template ID is not set' );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'Insufficient user permission' );
			}

			$temp_id   = isset( $_POST['templateID'] ) ? sanitize_text_field( wp_unslash( $_POST['templateID'] ) ) : '';
			$temp_type = isset( $_POST['tempType'] ) ? sanitize_text_field( wp_unslash( $_POST['tempType'] ) ) : '';

			if ( 'loop' === $temp_type ) {
				/** @var LoopDocument $document */
				$template_content = PluginPro::elementor()->documents->get( $temp_id );

			} else {
				$template_content = Helper_Functions::render_elementor_template( $temp_id, true );

			}

			if ( empty( $template_content ) || ! isset( $template_content ) ) {

				$res = wp_delete_post( $temp_id, true );

				if ( ! is_wp_error( $res ) ) {
					$res = 'Template Deleted.';
				}
			} else {
				$res = 'Template Has Content.';
			}

			wp_send_json_success( $res );
		}


/** Function get_shape_svgs() called by wp_ajax hooks: {'pa_get_shape_svgs'} **/
/** No params detected :-/ **/


/** Function insert_cf_form() called by wp_ajax hooks: {'insert_cf_form'} **/
/** Parameters found in function insert_cf_form(): {"get": ["preset"]} **/
function insert_cf_form() {

			check_ajax_referer( 'pa-editor', 'security' );

			if ( ! isset( $_GET['preset'] ) ) {
				wp_send_json_error();
			}

			$preset = sanitize_text_field( wp_unslash( $_GET['preset'] ) );

			$current_user = wp_get_current_user();

			$props = array(
				'form'                => self::get_cf_form_body( $preset ),
				'mail'                => array(
					'active'             => 1,
					'subject'            => '[_site_title] "[your-subject]"',
					'sender'             => '[_site_title]',
					'recipient'          => '[_site_admin_email]',
					'body'               => 'From: [your-name] [your-email]' . PHP_EOL .
							'Subject: [your-subject]' . PHP_EOL . PHP_EOL .
							'Message Body:' . PHP_EOL . '[your-message]' . PHP_EOL . PHP_EOL .
							'--' . PHP_EOL .
							'This e-mail was sent from a contact form on [_site_title] ([_site_url])',
					'additional_headers' => 'Reply-To: [your-email]',
					'attachments'        => '',
					'use_html'           => '',
					'exclude_blank'      => '',
				),
				'mail_2'              => array(
					'active'             => '',
					'subject'            => '[_site_title] "[your-subject]"',
					'sender'             => '[_site_title]',
					'recipient'          => '[your-email]',
					'body'               => 'Message Body:' . PHP_EOL . '[your-message]' . PHP_EOL . PHP_EOL .
							'--' . PHP_EOL .
							'This e-mail was sent from a contact form on [_site_title] ([_site_url])',
					'additional_headers' => 'Reply-To: [_site_admin_email]',
					'attachments'        => '',
					'use_html'           => '',
					'exclude_blank'      => '',
				),
				'messages'            => array(
					'mail_sent_ok'             => 'Thank you for your message. It has been sent.',
					'mail_sent_ng'             => 'There was an error trying to send your message. Please try again later.',
					'validation_error'         => 'One or more fields have an error. Please check and try again.',
					'spam'                     => 'There was an error trying to send your message. Please try again later.',
					'accept_terms'             => 'You must accept the terms and conditions before sending your message.',
					'invalid_required'         => 'Please fill out this field.',
					'invalid_too_long'         => 'This field has a too long input.',
					'invalid_too_short'        => 'This field has a too short input.',
					'upload_failed'            => 'There was an unknown error uploading the file.',
					'upload_file_type_invalid' => 'You are not allowed to upload files of this type.',
					'upload_file_too_large'    => 'The uploaded file is too large.',
					'upload_failed_php_error'  => 'There was an error uploading the file.',
					'invalid_date'             => 'Please enter a date in YYYY-MM-DD format.',
					'date_too_early'           => 'This field has a too early date.',
					'date_too_late'            => 'This field has a too late date.',
					'invalid_number'           => 'Please enter a number.',
					'number_too_small'         => 'This field has a too small number.',
					'number_too_large'         => 'This field has a too large number.',
					'quiz_answer_not_correct'  => 'The answer to the quiz is incorrect.',
					'invalid_email'            => 'Please enter an email address.',
					'invalid_url'              => 'Please enter a URL.',
					'invalid_tel'              => 'Please enter a telephone number.',
				),
				'additional_settings' => '',
			);

			$post_content = implode( "\n", wpcf7_array_flatten( $props ) );

			$args = array(
				'post_status'  => 'publish',
				'post_type'    => 'wpcf7_contact_form',
				'post_content' => $post_content,
				'post_author'  => $current_user->ID,
				'post_title'   => sprintf(
					__( 'Form | %s', 'premium-addons-for-elementor' ),
					gmdate( 'Y-m-d H:i' )
				),
			);

			$post_id = wp_insert_post( $args );

			foreach ( $props as $prop => $value ) {
				update_post_meta(
					$post_id,
					'_' . $prop,
					wpcf7_normalize_newline_deep( $value )
				);
			}

			$form_id = wpcf7_generate_contact_form_hash( $post_id );

			add_post_meta( $post_id, '_hash', $form_id, true );

			wp_send_json_success( substr( $form_id, 0, 7 ) );
		}


/** Function get_pinterest_token() called by wp_ajax hooks: {'get_pinterest_token'} **/
/** No params detected :-/ **/


/** Function reset_admin_notice() called by wp_ajax hooks: {'pa_reset_admin_notice'} **/
/** Parameters found in function reset_admin_notice(): {"post": ["notice"]} **/
function reset_admin_notice() {

		check_ajax_referer( 'pa-notice-nonce', 'nonce' );

		if ( ! Admin_Helper::check_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		$key = isset( $_POST['notice'] ) ? sanitize_text_field( wp_unslash( $_POST['notice'] ) ) : '';

		if ( ! empty( $key ) && in_array( $key, self::$notices, true ) ) {

			update_option( self::REVIEW_OPTION, (string) ( time() + WEEK_IN_SECONDS ), true );

			wp_send_json_success();

		} else {

			wp_send_json_error();

		}
	}


/** Function pa_delete_cart_item() called by wp_ajax hooks: {'nopriv_pa_delete_cart_item', 'pa_delete_cart_item'} **/
/** Parameters found in function pa_delete_cart_item(): {"post": ["itemKey"]} **/
function pa_delete_cart_item() {

		check_ajax_referer( 'pa-mini-cart-nonce', 'nonce' );

		if ( ! isset( $_POST['itemKey'] ) ) {
			return;
		}

		$item_key = sanitize_text_field( $_POST['itemKey'] );

		if ( WC()->cart->get_cart_item( $_POST['itemKey'] ) ) {
			WC()->cart->remove_cart_item( $_POST['itemKey'] );
		}

		\WC_AJAX::get_refreshed_fragments();

		wp_send_json_success();
	}


/** Function pa_apply_coupon() called by wp_ajax hooks: {'pa_apply_coupon', 'nopriv_pa_apply_coupon'} **/
/** Parameters found in function pa_apply_coupon(): {"post": ["couponCode"]} **/
function pa_apply_coupon() {

		check_ajax_referer( 'pa-mini-cart-nonce', 'nonce' );

		if ( empty( $_POST['couponCode'] ) ) {
			return;
		}

		$coupon_code = sanitize_text_field( $_POST['couponCode'] );

		$coupon = new \WC_Coupon( $coupon_code );

		if ( $coupon->is_valid() ) {

			if ( ! WC()->cart->has_discount( $coupon_code ) ) {

				WC()->cart->apply_coupon( $coupon_code );

				wp_send_json_success( 'Coupon was applied successfully.' );

				\WC_AJAX::get_refreshed_fragments();

			} else {
				wp_send_json_error( 'This code was already applied.', 409 );
			}
		} else {
			wp_send_json_error( 'Invalid Coupon!', 422 );
		}
	}


/** Function pa_remove_coupon() called by wp_ajax hooks: {'pa_remove_coupon', 'nopriv_pa_remove_coupon'} **/
/** Parameters found in function pa_remove_coupon(): {"post": ["couponCode"]} **/
function pa_remove_coupon() {
		check_ajax_referer( 'pa-mini-cart-nonce', 'nonce' );

		if ( ! isset( $_POST['couponCode'] ) ) {
			wp_send_json_error( 'No coupon code provided.', 400 );
		}

		$coupon_code = sanitize_text_field( $_POST['couponCode'] );

		if ( WC()->cart->has_discount( $coupon_code ) ) {

			WC()->cart->remove_coupon( $coupon_code );

			\WC_AJAX::get_refreshed_fragments();

		} else {
			wp_send_json_error( 'Coupon not found or already removed.', 404 );
		}
	}


/** Function pa_save_elements_settings() called by wp_ajax hooks: {'pa_save_elements_settings'} **/
/** Parameters found in function pa_save_elements_settings(): {"post": ["fields"]} **/
function pa_save_elements_settings() {

		check_ajax_referer( 'pa-settings-tab', 'security' );

		if ( ! self::check_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'You are not allowed to do this action', 'premium-addons-for-elementor' ) );
		}

		if ( ! isset( $_POST['fields'] ) ) {
			return;
		}

		parse_str( sanitize_text_field( wp_unslash( $_POST['fields'] ) ), $settings );

		$defaults = self::get_default_keys();

		// Full-replace semantics: a key present in the posted form is enabled,
		// every other known key is disabled.
		$enabled_map = array();
		foreach ( $defaults as $key => $value ) {
			$enabled_map[ $key ] = isset( $settings[ $key ] );
		}

		self::persist_elements_settings( $enabled_map );

		// Save the global addons only if it's the second run.
		$is_second_run = get_option( 'pa_complete_wizard' ) ? false : true;
		if ( $is_second_run ) {
			self::update_global_addons_option( $settings );
		} else {
			update_option( 'pa_complete_wizard', false );
		}

		wp_send_json_success();
	}


/** Function pa_disable_unused_widgets() called by wp_ajax hooks: {'pa_disable_unused_widgets'} **/
/** No params detected :-/ **/


/** Function handle_live_editor() called by wp_ajax hooks: {'handle_live_editor'} **/
/** Parameters found in function handle_live_editor(): {"post": ["key", "type"]} **/
function handle_live_editor() {

			check_ajax_referer( 'pa-live-editor', 'security' );

			if ( ! isset( $_POST['key'] ) ) { // Widget ID ( + Control ID in case of repeater items ).
				wp_send_json_error();
			}

			$post_name  = 'pa-dynamic-temp-' . sanitize_text_field( wp_unslash( $_POST['key'] ) );
			$temp_type  = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : false;
			$meta_input = array(
				'_elementor_edit_mode'     => 'builder',
				'_elementor_template_type' => 'page',
				'_wp_page_template'        => 'elementor_canvas',
			);

			if ( 'loop' === $temp_type ) {
				$meta_input = array(
					'_elementor_edit_mode'     => 'builder',
					'_elementor_template_type' => 'loop-item',
				);
			} elseif ( 'grid' === $temp_type ) {
				$meta_input = array(
					'_elementor_edit_mode'     => 'builder',
					'_elementor_template_type' => 'premium-grid',
				);
			}

			$post_title = '';
			$args       = array(
				'post_type'              => 'elementor_library',
				'name'                   => $post_name,
				'post_status'            => 'publish',
				'update_post_term_cache' => false,
				'update_post_meta_cache' => false,
				'posts_per_page'         => 1,
			);

			$post = get_posts( $args );

			if ( empty( $post ) ) { // create a new one.

				$key        = sanitize_text_field( wp_unslash( $_POST['key'] ) );
				$post_title = 'PA Template | #' . substr( md5( $key ), 0, 4 );

				$params = array(
					'post_content' => '',
					'post_type'    => 'elementor_library',
					'post_title'   => $post_title,
					'post_name'    => $post_name,
					'post_status'  => 'publish',
					'meta_input'   => $meta_input,
				);

				$post_id = wp_insert_post( $params );

			} else { // edit post.
				$post_id    = $post[0]->ID;
				$post_title = $post[0]->post_title;
			}

			$edit_url = get_admin_url() . '/post.php?post=' . $post_id . '&action=elementor';

			$result = array(
				'url'   => $edit_url,
				'id'    => $post_id,
				'title' => $post_title,
			);

			wp_send_json_success( $result );
		}


/** Function get_template_content() called by wp_ajax hooks: {'nopriv_get_elementor_template_content', 'get_elementor_template_content'} **/
/** Parameters found in function get_template_content(): {"get": ["templateID", "is_id"]} **/
function get_template_content() {

		$template = isset( $_GET['templateID'] ) ? sanitize_text_field( wp_unslash( $_GET['templateID'] ) ) : '';
		$is_ID    = isset( $_GET['is_id'] ) ? filter_var( $_GET['is_id'], FILTER_VALIDATE_BOOLEAN ) : false;

		if ( empty( $template ) ) {
			wp_send_json_error( 'Empty Template ID' );
		}

		// Get the post object to check status and author
		$post = get_post( $template );

		if ( ! $post ) {
			wp_send_json_error( 'Invalid Template ID' );
		}

		// Check if post is published or user has permission to view
		if ( 'publish' !== $post->post_status ) {
			$current_user_id = get_current_user_id();
			$is_author       = ( $current_user_id === (int) $post->post_author );
			$is_admin        = current_user_can( 'manage_options' );

			if ( ! $is_admin && ! $is_author ) {
				wp_send_json_error( 'Permission denied' );
			}
		}

		$template_content = Helper_Functions::render_elementor_template( $template, $is_ID );

		if ( empty( $template_content ) || ! isset( $template_content ) ) {
			wp_send_json_error( 'Empty Content' );
		}

		$data = array(
			'template_content' => $template_content,
		);

		wp_send_json_success( $data );
	}


/** Function get_pinterest_boards() called by wp_ajax hooks: {'get_pinterest_boards'} **/
/** Parameters found in function get_pinterest_boards(): {"get": ["token"]} **/
function get_pinterest_boards() {

		check_ajax_referer( 'pa-blog-widget-nonce', 'nonce' );

		if ( ! isset( $_GET['token'] ) ) {
			wp_send_json_error();
		}

		$token = sanitize_text_field( wp_unslash( $_GET['token'] ) );

		$transient_name = 'pa_pinterest_boards_' . substr( $token, 0, 15 );

		$body = get_transient( $transient_name );

		if ( false === $body ) {

			$api_url = 'https://api.pinterest.com/v5/boards?page_size=60';

			$response = wp_remote_get(
				$api_url,
				array(
					'headers' => array(
						'Authorization' => 'Bearer ' . $token,
					),
				)
			);

			$body = wp_remote_retrieve_body( $response );
			$body = json_decode( $body, true );

			set_transient( $transient_name, $body, 30 * MINUTE_IN_SECONDS );

		}

		$boards = array();

		foreach ( $body['items'] as $index => $board ) {
			$boards[ $board['id'] ] = $board['name'];
		}

		wp_send_json_success( wp_json_encode( $boards ) );
	}


/** Function get_posts_query() called by wp_ajax hooks: {'pa_get_posts', 'nopriv_pa_get_posts'} **/
/** No params detected :-/ **/


/** Function get_related_tax() called by wp_ajax hooks: {'premium_update_tax'} **/
/** Parameters found in function get_related_tax(): {"post": ["post_type"]} **/
function get_related_tax() {

		check_ajax_referer( 'pa-blog-widget-nonce', 'nonce' );

		$post_type = isset( $_POST['post_type'] ) ? sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) : '';

		if ( empty( $post_type ) ) {
			wp_send_json_error( __( 'Empty Post Type.', 'premium-addons-for-elementor' ) );
		}

		$taxonomy = Query_Helper::get_taxnomies( $post_type );

		$related_tax = array();

		if ( ! empty( $taxonomy ) ) {

			foreach ( $taxonomy as $index => $tax ) {
				$related_tax[ $index ] = $tax->label;
			}
		}

		wp_send_json_success( wp_json_encode( $related_tax ) );
	}


/** Function pa_hide_unused_widgets_dialog() called by wp_ajax hooks: {'pa_hide_unused_widgets_dialog'} **/
/** No params detected :-/ **/


/** Function send() called by wp_ajax hooks: {'pa_send_element_feedback', 'pa_handle_feedback_action'} **/
/** Parameters found in function send(): {"post": ["data"]} **/
function send() {

		check_ajax_referer( 'pa-feedback-nonce', 'nonce' );

		$response = array( 'success' => false );

		if ( ! isset( $_POST['data'] ) || ! is_array( $_POST['data'] ) ) {
			wp_send_json_error( 'Invalid data format' );
		}

		$data = array_map( 'sanitize_text_field', wp_unslash( $_POST['data'] ) );

		$reason      = '';
		$suggestions = null;
		$anonymous   = false;

		if ( isset( $data['feedback'] ) ) {
			$reason      = $data['feedback'];
			$suggestions = isset( $data['suggestions'] ) ? $data['suggestions'] : null;
			$anonymous   = isset( $data['anonymous'] ) ? (bool) $data['anonymous'] : false;
		}

		if ( ! is_string( $reason ) || empty( $reason ) ) {
			return false;
		}

		$wordpress = self::collect_wordpress_data( true );

		$wordpress['deactivated_plugin']['uninstall_reason']  = $reason;
		$wordpress['deactivated_plugin']['uninstall_details'] = '';

		if ( ! empty( $suggestions ) ) {
			$wordpress['deactivated_plugin']['uninstall_details'] .= $suggestions;
		}

		if ( ! $anonymous ) {
			$wordpress['deactivated_plugin']['uninstall_details'] .= ( empty( $wordpress['deactivated_plugin']['uninstall_details'] ) ? '' : PHP_EOL . PHP_EOL ) . 'Domain: ' . self::get_site_domain();

			$wordpress['used_widgets'] = array(
				'widgets' => self::get_usage_count(),
			);
		}

		$body = array(
			'user'      => self::collect_user_data( $anonymous ),
			'wordpress' => $wordpress,
		);

		$api_url = 'https://feedbackpa.leap13.com/wp-json/feedback/v2/add';

		$response = wp_safe_remote_request(
			$api_url,
			array(
				'headers'     => array(
					'Content-Type' => 'application/json',
				),
				'body'        => wp_json_encode( $body ),
				'timeout'     => 20,
				'method'      => 'POST',
				'httpversion' => '1.1',
			)
		);

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( 'REQUEST ERR' );

		}

		if ( ! isset( $response['response'] ) || ! is_array( $response['response'] ) ) {
			wp_send_json_error( 'REQUEST UNKNOWN' );

		}

		if ( ! isset( $response['body'] ) ) {
			wp_send_json_error( 'REQUEST PAYLOAD EMPTY' );

		}

		wp_send_json_success( ( $response['body'] ) );
	}


/** Function get_acf_options() called by wp_ajax hooks: {'pa_acf_options'} **/
/** Parameters found in function get_acf_options(): {"post": ["query_options"]} **/
function get_acf_options() {

		check_ajax_referer( 'pa-blog-widget-nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Insufficient user permission' );
		}

		$query_options = isset( $_POST['query_options'] ) && is_array( $_POST['query_options'] )
			? array_map( 'sanitize_text_field', wp_unslash( $_POST['query_options'] ) )
			: array();

		$query = new \WP_Query(
			array(
				'post_type'      => 'acf-field',
				'posts_per_page' => -1,
			)
		);

		$results = ACF_Helper::format_acf_query_result( $query->posts, $query_options );

		wp_send_json_success( wp_json_encode( $results ) );
	}


/** Function update_template_title() called by wp_ajax hooks: {'update_template_title'} **/
/** Parameters found in function update_template_title(): {"post": ["title", "id"]} **/
function update_template_title() {

			check_ajax_referer( 'pa-live-editor', 'security' );

			if ( ! isset( $_POST['title'] ) || ! isset( $_POST['id'] ) ) {
				wp_send_json_error( 'Post has no title.' );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'Insufficient user permission' );
			}

			$res = wp_update_post(
				array(
					'ID'         => sanitize_text_field( wp_unslash( $_POST['id'] ) ),
					'post_title' => sanitize_text_field( wp_unslash( $_POST['title'] ) ),
				)
			);

			wp_send_json_success( $res );
		}


/** Function pa_save_additional_settings() called by wp_ajax hooks: {'pa_save_additional_settings'} **/
/** Parameters found in function pa_save_additional_settings(): {"post": ["fields"]} **/
function pa_save_additional_settings() {

		check_ajax_referer( 'pa-settings-tab', 'security' );

		if ( ! self::check_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'You are not allowed to do this action', 'premium-addons-for-elementor' ) );
		}

		if ( ! isset( $_POST['fields'] ) ) {
			return;
		}

		parse_str( sanitize_text_field( wp_unslash( $_POST['fields'] ) ), $settings );

		// Pass the full whitelist as a change set; the service whitelists,
		// type-sanitizes and persists. Absent checkbox keys resolve to 0.
		$changes = array(
			'premium-map-api'             => $settings['premium-map-api'] ?? '',
			'premium-youtube-api'         => $settings['premium-youtube-api'] ?? '',
			'premium-map-disable-api'     => $settings['premium-map-disable-api'] ?? '',
			'premium-map-cluster'         => $settings['premium-map-cluster'] ?? '',
			'premium-wp-optimize-exclude' => $settings['premium-wp-optimize-exclude'] ?? '',
			'premium-map-locale'          => $settings['premium-map-locale'] ?? '',
			'is-beta-tester'              => $settings['is-beta-tester'] ?? '',
		);

		self::update_integrations_settings( $changes );

		wp_send_json_success( $settings );
	}


/** Function cross_cp_fetch_content_data() called by wp_ajax hooks: {'premium_cross_cp_import'} **/
/** Parameters found in function cross_cp_fetch_content_data(): {"post": ["copy_content"]} **/
function cross_cp_fetch_content_data() {

			check_ajax_referer( 'premium_cross_cp_import', 'nonce' );

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_send_json_error(
					__( 'Not a valid user', 'premium-addons-for-elementor' ),
					403
				);
			}

			$media_import = isset( $_POST['copy_content'] ) ? wp_unslash( $_POST['copy_content'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

			if ( empty( $media_import ) ) {
				wp_send_json_error( __( 'Empty Content.', 'premium-addons-for-elementor' ) );
			}

			$media_import = array( json_decode( $media_import, true ) );
			$media_import = self::cross_cp_import_elements_ids( $media_import );
			$media_import = self::cross_cp_import_copy_content( $media_import );

			wp_send_json_success( $media_import );
		}


