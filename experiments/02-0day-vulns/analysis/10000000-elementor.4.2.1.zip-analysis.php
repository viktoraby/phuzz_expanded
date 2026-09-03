<?php
/***
*
*Found actions: 18
*Found functions:17
*Extracted functions:17
*Total parameter names extracted: 4
*Overview: {'ajax_reset_api_data': {'elementor_reset_library'}, 'handle_click_tracking': {'elementor_send_clicks', 'nopriv_elementor_send_clicks'}, 'ajax_elementor_clear_cache': {'elementor_clear_cache'}, 'set_product_images_ajax': {'elementor-ai-set-product-images'}, 'get_product_images_ajax': {'elementor-ai-get-product-images'}, 'ajax_get_admin_page_data': {'elementor_element_manager_get_admin_app_data'}, 'ajax_save_disabled_elements': {'elementor_element_manager_save_disabled_elements'}, 'ajax_set_admin_notice_viewed': {'elementor_set_admin_notice_viewed'}, 'ajax_elementor_recreate_kit': {'elementor_recreate_kit'}, 'ajax_elementor_deactivate_feedback': {'elementor_deactivate_feedback'}, 'ajax_get_widgets_usage': {'elementor_element_manager_get_widgets_usage'}, 'ajax_elementor_replace_url': {'elementor_replace_url'}, 'download_file': {'elementor_system_info_download_file'}, 'handle_direct_actions': {'elementor_library_direct_actions'}, 'handle_ajax_request': {'elementor_ajax'}, 'get_images_details': {'elementor_get_images_details'}, 'js_log': {'elementor_js_log'}}
*
***/

/** Function ajax_reset_api_data() called by wp_ajax hooks: {'elementor_reset_library'} **/
/** No params detected :-/ **/


/** Function handle_click_tracking() called by wp_ajax hooks: {'elementor_send_clicks', 'nopriv_elementor_send_clicks'} **/
/** No params detected :-/ **/


/** Function ajax_elementor_clear_cache() called by wp_ajax hooks: {'elementor_clear_cache'} **/
/** No params detected :-/ **/


/** Function set_product_images_ajax() called by wp_ajax hooks: {'elementor-ai-set-product-images'} **/
/** Parameters found in function set_product_images_ajax(): {"post": ["productId", "image_url", "image_to_add", "image_to_remove", "is_product_gallery"]} **/
function set_product_images_ajax() {
		check_ajax_referer( 'elementor-ai-unify-product-images_nonce', 'nonce' );

		$product_id = isset( $_POST['productId'] ) ? sanitize_text_field( wp_unslash( $_POST['productId'] ) ) : '';
		$image_url = isset( $_POST['image_url'] ) ? sanitize_text_field( wp_unslash( $_POST['image_url'] ) ) : '';
		$image_to_add = isset( $_POST['image_to_add'] ) ? intval( wp_unslash( $_POST['image_to_add'] ) ) : null;
		$image_to_remove = isset( $_POST['image_to_remove'] ) ? intval( wp_unslash( $_POST['image_to_remove'] ) ) : null;
		$is_product_gallery = isset( $_POST['is_product_gallery'] ) && sanitize_text_field( wp_unslash( $_POST['is_product_gallery'] ) ) === 'true';

		if ( ! $product_id || ! $image_url ) {
			throw new \Exception( 'Product ID and Image URL are required' );
		}

		$product = wc_get_product( $product_id );
		if ( ! $product ) {
			throw new \Exception( 'Product not found' );
		}

		$attachment_id = $this->get_attachment_id_by_url( $image_url );
		if ( is_wp_error( $attachment_id ) ) {
			throw new \Exception( 'Image upload failed' );
		}

		if ( $is_product_gallery ) {
			$this->update_product_gallery( $product, $image_to_remove, $image_to_add );
		} else {
			$product->set_image_id( $attachment_id );
			$product->save();
		}

		wp_send_json_success( [
			'message' => __( 'Image added successfully', 'elementor' ),
		] );
	}


/** Function get_product_images_ajax() called by wp_ajax hooks: {'elementor-ai-get-product-images'} **/
/** Parameters found in function get_product_images_ajax(): {"post": ["post_ids", "is_galley_only"]} **/
function get_product_images_ajax() {
		check_ajax_referer( 'elementor-ai-unify-product-images_nonce', 'nonce' );

		$post_ids = isset( $_POST['post_ids'] ) ? array_map( 'intval', $_POST['post_ids'] ) : [];
		$is_galley_only = isset( $_POST['is_galley_only'] ) && sanitize_text_field( wp_unslash( $_POST['is_galley_only'] ) );

		$image_ids = [];

		foreach ( $post_ids as $post_id ) {
			if ( $is_galley_only ) {
				$product = wc_get_product( $post_id );
				$gallery_image_ids = $product->get_gallery_image_ids();
				foreach ( $gallery_image_ids as $image_id ) {
					$image_ids[] = [
						'productId' => $post_id,
						'id'   => $image_id,
						'image_url' => wp_get_attachment_url( $image_id ),
					];
				}
				continue;
			}

			$image_id = get_post_thumbnail_id( $post_id );

			if ( ! $image_id ) {
				$product = wc_get_product( $post_id );
				$gallery_image_ids = $product->get_gallery_image_ids();
				if ( ! empty( $gallery_image_ids ) ) {
					$image_id = $gallery_image_ids[0];
				}
			}

			$image_ids[] = [
				'productId' => $post_id,
				'id' => $image_id ? $image_id : 'No Image',
				'image_url' => $image_id ? wp_get_attachment_url( $image_id ) : 'No Image',
			];
		}

		wp_send_json_success( [ 'product_images' => array_slice( $image_ids, 0, 10 ) ] );

		wp_die();
	}


/** Function ajax_get_admin_page_data() called by wp_ajax hooks: {'elementor_element_manager_get_admin_app_data'} **/
/** No params detected :-/ **/


/** Function ajax_save_disabled_elements() called by wp_ajax hooks: {'elementor_element_manager_save_disabled_elements'} **/
/** No params detected :-/ **/


/** Function ajax_set_admin_notice_viewed() called by wp_ajax hooks: {'elementor_set_admin_notice_viewed'} **/
/** No params detected :-/ **/


/** Function ajax_elementor_recreate_kit() called by wp_ajax hooks: {'elementor_recreate_kit'} **/
/** No params detected :-/ **/


/** Function ajax_elementor_deactivate_feedback() called by wp_ajax hooks: {'elementor_deactivate_feedback'} **/
/** No params detected :-/ **/


/** Function ajax_get_widgets_usage() called by wp_ajax hooks: {'elementor_element_manager_get_widgets_usage'} **/
/** No params detected :-/ **/


/** Function ajax_elementor_replace_url() called by wp_ajax hooks: {'elementor_replace_url'} **/
/** No params detected :-/ **/


/** Function download_file() called by wp_ajax hooks: {'elementor_system_info_download_file'} **/
/** No params detected :-/ **/


/** Function handle_direct_actions() called by wp_ajax hooks: {'elementor_library_direct_actions'} **/
/** No params detected :-/ **/


/** Function handle_ajax_request() called by wp_ajax hooks: {'elementor_ajax'} **/
/** Parameters found in function handle_ajax_request(): {"request": ["editor_post_id", "actions"]} **/
function handle_ajax_request() {
		if ( ! $this->verify_request_nonce() ) {
			$this->add_response_data( false, esc_html__( 'Token Expired.', 'elementor' ) )
				->send_error( Exceptions::UNAUTHORIZED );
		}

		$editor_post_id = 0;

		if ( ! empty( $_REQUEST['editor_post_id'] ) ) {
			$editor_post_id = absint( $_REQUEST['editor_post_id'] );

			Plugin::$instance->db->switch_to_post( $editor_post_id );
		}

		/**
		 * Register ajax actions.
		 *
		 * Fires when an ajax request is received and verified.
		 *
		 * Used to register new ajax action handles.
		 *
		 * @since 2.0.0
		 *
		 * @param self $this An instance of ajax manager.
		 */
		do_action( 'elementor/ajax/register_actions', $this );

		if ( ! empty( $_REQUEST['actions'] ) ) {
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, each action should sanitize its own data.
			$this->requests = json_decode( wp_unslash( $_REQUEST['actions'] ), true );
		}

		foreach ( $this->requests as $id => $action_data ) {
			$this->current_action_id = $id;

			if ( ! isset( $this->ajax_actions[ $action_data['action'] ] ) ) {
				$this->add_response_data( false, esc_html__( 'Action not found.', 'elementor' ), Exceptions::BAD_REQUEST );

				continue;
			}

			if ( $editor_post_id ) {
				$action_data['data']['editor_post_id'] = $editor_post_id;
			}

			try {
				$data = $action_data['data'] ?? [];
				$results = call_user_func( $this->ajax_actions[ $action_data['action'] ]['callback'], $data, $this );

				if ( false === $results ) {
					$this->add_response_data( false );
				} else {
					$this->add_response_data( true, $results );
				}
			} catch ( \Exception $e ) {
				$this->add_response_data( false, $e->getMessage(), $e->getCode() );
			}
		}

		$this->current_action_id = null;

		$this->send_success();
	}


/** Function get_images_details() called by wp_ajax hooks: {'elementor_get_images_details'} **/
/** No params detected :-/ **/


/** Function js_log() called by wp_ajax hooks: {'elementor_js_log'} **/
/** Parameters found in function js_log(): {"post": ["data"]} **/
function js_log() {
		/** @var Module $ajax */
		$ajax = Plugin::$instance->common->get_component( 'ajax' );

		// PHPCS ignore is added throughout this method because nonce verification happens in the $ajax->verify_request_nonce() method.
		if ( ! $ajax->verify_request_nonce() || empty( $_POST['data'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			wp_send_json_error();
		}

		if ( ! current_user_can( Editor::EDITING_CAPABILITY ) ) {
			wp_send_json_error( 'Permission denied' );
		}

		// PHPCS - See comment above.
		$data = Utils::get_super_global_value( $_POST, 'data' ) ?? []; // phpcs:ignore WordPress.Security.NonceVerification.Missing

		array_walk_recursive( $data, function( &$value ) {
			$value = sanitize_text_field( $value );
		} );

		// PHPCS - See comment above.
		foreach ( $data as $error ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$error['type'] = Logger_Interface::LEVEL_ERROR;

			if ( ! empty( $error['customFields'] ) ) {
				$error['meta'] = $error['customFields'];
			}

			$item = new JS( $error );
			$this->get_logger()->log( $item );
		}

		wp_send_json_success();
	}


