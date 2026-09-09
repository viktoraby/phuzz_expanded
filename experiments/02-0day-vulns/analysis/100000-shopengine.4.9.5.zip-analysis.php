<?php
/***
*
*Found actions: 12
*Found functions:10
*Extracted functions:10
*Total parameter names extracted: 8
*Overview: {'handle_feedback': {'shopengine_deactivation_feedback'}, 'never_show_message': {'shopengine_rating_never_show_message'}, 'handle_dismiss': {'shopengine_dismiss_auto_install_notice'}, 'handle_confirm': {'shopengine_confirm_auto_install_notice'}, 'ask_me_later_message': {'shopengine_rating_ask_me_later_message'}, 'shopengine_admin_action': {'shopengine_admin_action'}, 'dismiss_ajax_call': {'wpmet-notices', 'shopengine-notices'}, 'add_attribute_by_ajax': {'shopengine_add_new_attribute'}, 'dismiss': {'shopengine-notices'}, 'swatch_image_on_loop_products': {'nopriv_shopengine_swatch_image_on_loop_products', 'shopengine_swatch_image_on_loop_products'}}
*
***/

/** Function handle_feedback() called by wp_ajax hooks: {'shopengine_deactivation_feedback'} **/
/** Parameters found in function handle_feedback(): {"post": ["reason", "reason_key", "reason_label", "feedback"]} **/
function handle_feedback()
	{
		$this->verify_request();

		$selected_reason = isset($_POST['reason'])
			? sanitize_text_field(wp_unslash($_POST['reason']))
			: '';

		$data = array(
			'plugin_slug'    => 'shopengine',
			'plugin_name'    => 'ShopEngine',
			'plugin_version' => \ShopEngine::version(),
			'user'           => array(
				'email' => $this->get_user_email(),
			),
			'feedback'       => array(
				'reason_key'   => isset($_POST['reason_key']) ? sanitize_text_field(wp_unslash($_POST['reason_key'])) : 'other',
				'reason_label' => isset($_POST['reason_label']) ? sanitize_text_field(wp_unslash($_POST['reason_label'])) : $selected_reason,
				'message'      => isset($_POST['feedback']) ? sanitize_textarea_field(wp_unslash($_POST['feedback'])) : '',
			),
			'usage'          => array(
				'user_type'      => $this->get_user_type(),
				'active_days'    => $this->get_days_active(),
			),
			'environment'    => array(
				'multisite_status'   => is_multisite(),
				'wp_version'         => get_bloginfo('version'),
				'php_version'        => PHP_VERSION,
				'elementor_version'  => defined('ELEMENTOR_VERSION') ? ELEMENTOR_VERSION : '',
				'site_url'           => get_site_url(),
			),
		);

		$response = $this->send_feedback_data($data);

		if (is_wp_error($response)) {
			wp_send_json_error(array('message' => $response->get_error_message()));
		}

		$response_code = (int) wp_remote_retrieve_response_code($response);
		if ($response_code < 200 || $response_code >= 300) {
			wp_send_json_error(
				array(
					'message' => esc_html__('Failed to submit feedback.', 'shopengine'),
					'code'    => $response_code,
				)
			);
		}

		wp_send_json_success(
			array('message' => esc_html__('Thank you for your feedback!', 'shopengine'))
		);
	}


/** Function never_show_message() called by wp_ajax hooks: {'shopengine_rating_never_show_message'} **/
/** Parameters found in function never_show_message(): {"post": ["nonce", "plugin_name"]} **/
function never_show_message()
	{
		if (empty($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'shopengine_rating')) {
			return false;
		}

		$plugin_name = isset($_POST['plugin_name']) ? sanitize_key($_POST['plugin_name']) : '';
		add_option($plugin_name . '_never_show', 'yes');
	}


/** Function handle_dismiss() called by wp_ajax hooks: {'shopengine_dismiss_auto_install_notice'} **/
/** No params detected :-/ **/


/** Function handle_confirm() called by wp_ajax hooks: {'shopengine_confirm_auto_install_notice'} **/
/** No params detected :-/ **/


/** Function ask_me_later_message() called by wp_ajax hooks: {'shopengine_rating_ask_me_later_message'} **/
/** Parameters found in function ask_me_later_message(): {"post": ["nonce", "plugin_name"]} **/
function ask_me_later_message()
	{
		if (empty($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'shopengine_rating')) {
			return false;
		}

		$plugin_name = isset($_POST['plugin_name']) ? sanitize_key($_POST['plugin_name']) : '';
		if (get_option($plugin_name . '_ask_me_later') == false) {
			add_option($plugin_name . '_ask_me_later', 'yes');
		} else {
			add_option($plugin_name . '_never_show', 'yes');
		}
	}


/** Function shopengine_admin_action() called by wp_ajax hooks: {'shopengine_admin_action'} **/
/** Parameters found in function shopengine_admin_action(): {"post": ["nonce", "settings"]} **/
function shopengine_admin_action() {
		
		$status = '';
		
		// Check for nonce security
		if (!isset($_POST['nonce']) || ! wp_verify_nonce(  sanitize_text_field( wp_unslash($_POST['nonce']) ) ) ) {
			return;
		}
		
		// manage capability check
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
	
		if ( isset( $_POST['settings'] ) ) {
			$status = self::save_settings( empty( $_POST['settings'] ) ? array() : map_deep( wp_unslash( $_POST['settings'] ) , 'sanitize_text_field' )  ); 
		}
		
		if(trim($status)){
			wp_send_json_success();
		}else{
			wp_send_json_error();
		}

		exit; 
	}


/** Function dismiss_ajax_call() called by wp_ajax hooks: {'wpmet-notices', 'shopengine-notices'} **/
/** Parameters found in function dismiss_ajax_call(): {"post": ["nonce", "notice_id", "dismissible", "expired_time"]} **/
function dismiss_ajax_call()
	{
		if (empty($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'shopengine-notices')) {
			return false;
		}

		$notice_id    = (isset($_POST['notice_id'])) ? sanitize_text_field(wp_unslash($_POST['notice_id'])) : '';
		$dismissible  = (isset($_POST['dismissible'])) ? sanitize_text_field(wp_unslash($_POST['dismissible'])) : '';
		$expired_time = (isset($_POST['expired_time'])) ? sanitize_text_field(wp_unslash($_POST['expired_time'])) : '';

		if (!empty($notice_id)) {
			if ('user' === $dismissible) {
				update_user_meta(get_current_user_id(), $notice_id, true);
			} else {
				set_transient($notice_id, true, $expired_time);
			}

			wp_send_json_success();
		}

		wp_send_json_error();
	}


/** Function add_attribute_by_ajax() called by wp_ajax hooks: {'shopengine_add_new_attribute'} **/
/** Parameters found in function add_attribute_by_ajax(): {"post": ["nonce", "taxonomy", "type", "name", "slug", "swatch"]} **/
function add_attribute_by_ajax() {

		$nonce  = isset($_POST['nonce']) ? sanitize_key($_POST['nonce']) : '';
		$tax    = isset($_POST['taxonomy']) ? sanitize_key($_POST['taxonomy']) : '';
		$type   = isset($_POST['type']) ? sanitize_key($_POST['type']) : '';
		$name   = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
		$slug   = isset($_POST['slug']) ? sanitize_key($_POST['slug']) : '';
		$swatch = isset($_POST['swatch']) ? sanitize_text_field(wp_unslash($_POST['swatch'])) : '';

		if(!wp_verify_nonce($nonce, 'shopengine_nonce_add_attribute')) {
			wp_send_json_error(esc_html__('Request denied', 'shopengine'));
		}

		if(empty($name) || empty($swatch) || empty($tax) || empty($type)) {
			wp_send_json_error(esc_html__('Insufficient data', 'shopengine'));
		}

		if(!taxonomy_exists($tax)) {
			wp_send_json_error(esc_html__('Taxonomy is not exists', 'shopengine'));
		}

		if(term_exists($name, $tax)) {
			wp_send_json_error(esc_html__('Term already exists', 'shopengine'));
		}

		$term = wp_insert_term($name, $tax, ['slug' => $slug]);

		if(is_wp_error($term)) {

			wp_send_json_error($term->get_error_message());

		} else {
			$term = get_term_by('id', $term['term_id'], $tax);
			update_term_meta($term->term_id, $type, $swatch);
		}

		wp_send_json_success(
			[
				'msg'  => esc_html__('Successfully added', 'shopengine'),
				'id'   => $term->term_id,
				'slug' => $term->slug,
				'name' => $term->name,
			]
		);
	}


/** Function dismiss() called by wp_ajax hooks: {'shopengine-notices'} **/
/** Parameters found in function dismiss(): {"post": ["shopengine_nonce", "id", "time", "meta", "is_required"]} **/
function dismiss() {

		if(empty($_POST['shopengine_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['shopengine_nonce'])), 'shopengine_nonce')) {
			wp_send_json_error();
		}

		$id   = ( isset( $_POST['id'] ) ) ? sanitize_key($_POST['id']) : '';
		$time = ( isset( $_POST['time'] ) ) ? sanitize_text_field(wp_unslash($_POST['time'])) : '';
		$meta = ( isset( $_POST['meta'] ) ) ? sanitize_key($_POST['meta']) : '';
		$is_required = ( isset( $_POST['is_required'] ) ) ? sanitize_key($_POST['is_required']) : 0;

		if($is_required == 1){
			return;
		}

		// Valid inputs?
		if ( ! empty( $id ) ) {

			if ( 'user' === $meta ) {
				update_user_meta( get_current_user_id(), $id, true );
			} else {
				set_transient( $id, true, $time );
			}

			wp_send_json_success();
		}

		wp_send_json_error();
	}


/** Function swatch_image_on_loop_products() called by wp_ajax hooks: {'nopriv_shopengine_swatch_image_on_loop_products', 'shopengine_swatch_image_on_loop_products'} **/
/** Parameters found in function swatch_image_on_loop_products(): {"post": ["nonce", "selectedData", "productId"]} **/
function swatch_image_on_loop_products(){

		$nonce  = isset($_POST['nonce']) ? sanitize_key($_POST['nonce']) : '';

		$selectedData = isset($_POST['selectedData']) ? map_deep( wp_unslash( $_POST['selectedData'] ) , 'sanitize_text_field' ) : '';
		$product_id = isset($_POST['productId']) ? sanitize_text_field( wp_unslash($_POST['productId']) ) : '';

		if(!wp_verify_nonce($nonce, 'shopengine_swatch_image_on_loop_products')) {
			wp_send_json_error(esc_html__('Request denied', 'shopengine'));
		} 

		$product = wc_get_product($product_id);
		$variations = $product->get_available_variations();		

		if(!empty($variations)){
			foreach($variations as $variation){
				$attributes = $variation['attributes'];
				$variation_match_found = false;

				foreach( $attributes as $attr_key => $value ){
					
					$current_attr_key = Helper::get_tax_attribute(ltrim($attr_key, 'attribute_'));
					
					if(isset($current_attr_key->attribute_type) && $current_attr_key->attribute_type === 'shopengine_color'){

						$attribute_match_found = false;

						// Variation has given value
						if(!empty($value)){
							foreach($selectedData as $selectedDataItem){
								if($selectedDataItem[0] === $attr_key && $selectedDataItem[1] === $value){
									$attribute_match_found = true;
								}
							}

							if(!$attribute_match_found){
								$variation_match_found = false;
								break;
							}
							$variation_match_found = true;
						}
						
					}
				}

				if($variation_match_found){
					wp_send_json_success( array(
						'variation_img_html' => Helper::get_product_thumbnail_by_image_id( $variation['image_id'], $product ),
					) );
					wp_die();
				}
				
			}
		}

		wp_send_json_error(esc_html__('No image found', 'shopengine'));
		wp_die();
	}


