<?php
/***
*
*Found actions: 34
*Found functions:28
*Extracted functions:27
*Total parameter names extracted: 7
*Overview: {'sync_products': {'wc_facebook_sync_products'}, 'handle_set_product_sync_bulk_action_prompt': {'facebook_for_woocommerce_set_product_sync_bulk_action_prompt'}, 'ajax_fb_background_check_queue': {'ajax_fb_background_check_queue'}, 'handle_set_excluded_terms_prompt': {'facebook_for_woocommerce_set_excluded_terms_prompt'}, 'product_set_banner_closed': {'wc_facebook_product_set_banner_closed'}, 'sync_navigation_menu': {'wc_facebook_sync_navigation_menu'}, 'ajax_render_enhanced_catalog_attributes_field': {'wc_facebook_enhanced_catalog_attributes'}, 'ajax_get_facebook_product_data': {'get_facebook_product_data'}, 'ajax_reset_all_fb_products': {'ajax_reset_all_fb_products'}, 'ajax_sync_facebook_attributes': {'sync_facebook_attributes'}, 'opt_out_of_sync_clicked': {'nopriv_wc_facebook_opt_out_of_sync', 'wc_facebook_opt_out_of_sync'}, 'reset_upcoming_version_banners': {'wc_banner_close_action', 'nopriv_wc_banner_close_action'}, 'get_sync_status': {'wc_facebook_get_sync_status'}, 'reset_plugin_updated_successfully_banner': {'wc_banner_post_update_close_action', 'nopriv_wc_banner_post_update_close_action'}, 'dismiss_banner': {'dismiss_fb_unmapped_attribute_banner'}, 'handle_connection_test_response': {'nopriv_{$this->identifier}_test'}, 'sync_coupons': {'wc_facebook_sync_coupons'}, 'sync_all_clicked': {'wc_facebook_sync_all_products', 'nopriv_wc_facebook_sync_all_products'}, 'ajax_test_banner': {'test_fb_banner'}, 'ajax_display_test_result': {'ajax_display_test_result'}, 'ajax_fb_toggle_visibility': {'ajax_fb_toggle_visibility'}, 'ajax_check_feed_upload_status': {'ajax_check_feed_upload_status'}, 'handle_dismiss_disabled_notice_ajax': {'wc_facebook_dismiss_disabled_notice'}, 'sync_shipping_profiles': {'wc_facebook_sync_shipping_profiles'}, 'ajax_dismiss_notice': {'wc_facebook_dismiss_notice', 'fb_dismiss_attribute_notice'}, 'ajax_woo_adv_bulk_edit_compat': {'wpmelon_adv_bulk_edit'}, 'reset_plugin_updated_successfully_but_master_sync_off_banner': {'nopriv_wc_banner_post_update__master_sync_off_close_action', 'wc_banner_post_update__master_sync_off_close_action'}, 'ajax_dismiss_unmapped_attributes_banner': {'fb_dismiss_unmapped_attributes_banner'}}
*
***/

/** Function sync_products() called by wp_ajax hooks: {'wc_facebook_sync_products'} **/
/** No params detected :-/ **/


/** Function handle_set_product_sync_bulk_action_prompt() called by wp_ajax hooks: {'facebook_for_woocommerce_set_product_sync_bulk_action_prompt'} **/
/** Parameters found in function handle_set_product_sync_bulk_action_prompt(): {"post": ["products", "toggle"]} **/
function handle_set_product_sync_bulk_action_prompt() {
		check_ajax_referer( 'set-product-sync-bulk-action-prompt', 'security' );

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$product_ids = isset( $_POST['products'] ) ? array_map( 'absint', (array) wp_unslash( $_POST['products'] ) ) : array();
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$toggle = isset( $_POST['toggle'] ) ? (string) sanitize_text_field( wp_unslash( $_POST['toggle'] ) ) : '';

		if ( ! empty( $product_ids ) && ! empty( $toggle ) && 'facebook_include' === $toggle ) {

			$has_excluded_term = false;

			foreach ( $product_ids as $product_id ) {
				$product = wc_get_product( $product_id );

				if ( $product instanceof \WC_Product && ! facebook_for_woocommerce()->get_product_sync_validator( $product )->passes_product_terms_check() ) {
					$has_excluded_term = true;
					break;
				}
			}

			// show modal if there's at least one product that belongs to an excluded term
			if ( $has_excluded_term ) {
				ob_start();

				?>
				<a
					id="facebook-for-woocommerce-go-to-settings"
					class="button button-large"
					href="<?php echo esc_url( add_query_arg( 'tab', Product_Sync::ID, facebook_for_woocommerce()->get_settings_url() ) ); ?>"
				><?php esc_html_e( 'Go to Settings', 'facebook-for-woocommerce' ); ?></a>
				<button
					id="facebook-for-woocommerce-cancel-sync"
					class="button button-large button-primary"
					onclick="jQuery( '.modal-close' ).trigger( 'click' )"
				><?php esc_html_e( 'Cancel', 'facebook-for-woocommerce' ); ?></button>
				<?php

				$buttons = ob_get_clean();

				wp_send_json_error(
					array(
						'message' => __( 'One or more of the selected products belongs to a category or tag that is excluded from the Facebook catalog sync. To sync these products to Facebook, please remove the category or tag exclusion from the plugin settings.', 'facebook-for-woocommerce' ),
						'buttons' => $buttons,
					)
				);
			}
		} else {
			wp_send_json_success();
		}
	}


/** Function ajax_fb_background_check_queue() called by wp_ajax hooks: {'ajax_fb_background_check_queue'} **/
/** Parameters found in function ajax_fb_background_check_queue(): {"post": ["request_time"]} **/
function ajax_fb_background_check_queue() {
		WC_Facebookcommerce_Utils::check_woo_ajax_permissions( 'background check queue', true );
		check_ajax_referer( 'wc_facebook_settings_jsx' );
		$request_time = null;
		if ( isset( $_POST['request_time'] ) ) {
			$request_time = esc_js( sanitize_text_field( wp_unslash( $_POST['request_time'] ) ) );
		}
		if ( $this->facebook_for_woocommerce->get_connection_handler()->get_access_token() ) {
			if ( isset( $this->background_processor ) ) {
				$is_processing = $this->background_processor->handle_cron_healthcheck();
				$remaining     = $this->background_processor->get_item_count();
				$response      = array(
					'connected'    => true,
					'background'   => true,
					'processing'   => $is_processing,
					'remaining'    => $remaining,
					'request_time' => $request_time,
				);
			} else {
				$response = array(
					'connected'  => true,
					'background' => false,
				);
			}
		} else {
			$response = array(
				'connected'  => false,
				'background' => false,
			);
		}
		printf( wp_json_encode( $response ) );
		wp_die();
	}


/** Function handle_set_excluded_terms_prompt() called by wp_ajax hooks: {'facebook_for_woocommerce_set_excluded_terms_prompt'} **/
/** Parameters found in function handle_set_excluded_terms_prompt(): {"post": ["categories", "tags"]} **/
function handle_set_excluded_terms_prompt() {
		check_ajax_referer( 'set-excluded-terms-prompt', 'security' );

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$posted_categories = isset( $_POST['categories'] ) ? wc_clean( wp_unslash( $_POST['categories'] ) ) : array();
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$posted_tags = isset( $_POST['tags'] ) ? wc_clean( wp_unslash( $_POST['tags'] ) ) : array();

		$new_category_ids = array();
		$new_tag_ids      = array();

		if ( ! empty( $posted_categories ) ) {
			foreach ( $posted_categories as $posted_category_id ) {
				$new_category_ids[] = sanitize_text_field( $posted_category_id );
			}
		}

		if ( ! empty( $posted_tags ) ) {
			foreach ( $posted_tags as $posted_tag_id ) {
				$new_tag_ids[] = sanitize_text_field( $posted_tag_id );
			}
		}

		$products = $this->get_products_to_be_excluded( $new_category_ids, $new_tag_ids );
		if ( ! empty( $products ) ) {

			ob_start();

			?>
			<button
				id="facebook-for-woocommerce-confirm-settings-change"
				class="button button-large button-primary facebook-for-woocommerce-confirm-settings-change"
			><?php esc_html_e( 'Exclude Products', 'facebook-for-woocommerce' ); ?></button>

			<button
				id="facebook-for-woocommerce-cancel-settings-change"
				class="button button-large button-primary"
				onclick="jQuery( '.modal-close' ).trigger( 'click' )"
			><?php esc_html_e( 'Cancel', 'facebook-for-woocommerce' ); ?></button>
			<?php

			$buttons = ob_get_clean();

			wp_send_json_error(
				array(
					'message' => sprintf(
					/* translators: Placeholder %s - <br/> tags */
						__( 'The categories and/or tags that you have selected to exclude from sync contain products that are currently synced to Facebook.%sTo exclude these products from the Facebook sync, click Exclude Products. To review the category / tag exclusion settings, click Cancel.', 'facebook-for-woocommerce' ),
						'<br/><br/>'
					),
					'buttons' => $buttons,
				)
			);

		} else {

			// the modal should not be displayed
			wp_send_json_success();
		}
	}


/** Function product_set_banner_closed() called by wp_ajax hooks: {'wc_facebook_product_set_banner_closed'} **/
/** No params detected :-/ **/


/** Function sync_navigation_menu() called by wp_ajax hooks: {'wc_facebook_sync_navigation_menu'} **/
/** No params detected :-/ **/


/** Function ajax_render_enhanced_catalog_attributes_field() called by wp_ajax hooks: {'wc_facebook_enhanced_catalog_attributes'} **/
/** No params detected :-/ **/


/** Function ajax_get_facebook_product_data() called by wp_ajax hooks: {'get_facebook_product_data'} **/
/** No function found :-/ **/


/** Function ajax_reset_all_fb_products() called by wp_ajax hooks: {'ajax_reset_all_fb_products'} **/
/** No params detected :-/ **/


/** Function ajax_sync_facebook_attributes() called by wp_ajax hooks: {'sync_facebook_attributes'} **/
/** Parameters found in function ajax_sync_facebook_attributes(): {"post": ["product_id"]} **/
function ajax_sync_facebook_attributes() {
		check_ajax_referer( 'sync_facebook_attributes', 'nonce' );

		$product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;
		if ( $product_id ) {
			$synced_fields = $this->sync_product_attributes( $product_id );
			wp_send_json_success( $synced_fields );
		}
		wp_send_json_error( 'Invalid product ID' );
	}


/** Function opt_out_of_sync_clicked() called by wp_ajax hooks: {'nopriv_wc_facebook_opt_out_of_sync', 'wc_facebook_opt_out_of_sync'} **/
/** No params detected :-/ **/


/** Function reset_upcoming_version_banners() called by wp_ajax hooks: {'wc_banner_close_action', 'nopriv_wc_banner_close_action'} **/
/** No params detected :-/ **/


/** Function get_sync_status() called by wp_ajax hooks: {'wc_facebook_get_sync_status'} **/
/** No params detected :-/ **/


/** Function reset_plugin_updated_successfully_banner() called by wp_ajax hooks: {'wc_banner_post_update_close_action', 'nopriv_wc_banner_post_update_close_action'} **/
/** No params detected :-/ **/


/** Function dismiss_banner() called by wp_ajax hooks: {'dismiss_fb_unmapped_attribute_banner'} **/
/** No params detected :-/ **/


/** Function handle_connection_test_response() called by wp_ajax hooks: {'nopriv_{$this->identifier}_test'} **/
/** No params detected :-/ **/


/** Function sync_coupons() called by wp_ajax hooks: {'wc_facebook_sync_coupons'} **/
/** No params detected :-/ **/


/** Function sync_all_clicked() called by wp_ajax hooks: {'wc_facebook_sync_all_products', 'nopriv_wc_facebook_sync_all_products'} **/
/** No params detected :-/ **/


/** Function ajax_test_banner() called by wp_ajax hooks: {'test_fb_banner'} **/
/** Parameters found in function ajax_test_banner(): {"post": ["attribute"]} **/
function ajax_test_banner() {
		check_ajax_referer( 'test_fb_banner', 'nonce' );

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( -1 );
		}

		$attribute_name = isset( $_POST['attribute'] ) ? sanitize_text_field( wp_unslash( $_POST['attribute'] ) ) : 'escobar';
		$this->test_banner( $attribute_name );

		wp_send_json_success( 'Banner queued for: ' . $attribute_name );
	}


/** Function ajax_display_test_result() called by wp_ajax hooks: {'ajax_display_test_result'} **/
/** No params detected :-/ **/


/** Function ajax_fb_toggle_visibility() called by wp_ajax hooks: {'ajax_fb_toggle_visibility'} **/
/** No params detected :-/ **/


/** Function ajax_check_feed_upload_status() called by wp_ajax hooks: {'ajax_check_feed_upload_status'} **/
/** No params detected :-/ **/


/** Function handle_dismiss_disabled_notice_ajax() called by wp_ajax hooks: {'wc_facebook_dismiss_disabled_notice'} **/
/** Parameters found in function handle_dismiss_disabled_notice_ajax(): {"post": ["signature"]} **/
function handle_dismiss_disabled_notice_ajax() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( [ 'message' => __( 'Insufficient permissions.', 'facebook-for-woocommerce' ) ], 403 );
		}

		check_ajax_referer( 'wc_facebook_dismiss_disabled_notice', 'nonce' );

		$signature = isset( $_POST['signature'] ) ? sanitize_text_field( wp_unslash( $_POST['signature'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( '' === $signature ) {
			wp_send_json_error( [ 'message' => __( 'Missing signature.', 'facebook-for-woocommerce' ) ], 400 );
		}

		update_user_meta( get_current_user_id(), self::DISABLED_NOTICE_DISMISSED_META_KEY, $signature );
		wp_send_json_success();
	}


/** Function sync_shipping_profiles() called by wp_ajax hooks: {'wc_facebook_sync_shipping_profiles'} **/
/** No params detected :-/ **/


/** Function ajax_dismiss_notice() called by wp_ajax hooks: {'wc_facebook_dismiss_notice', 'fb_dismiss_attribute_notice'} **/
/** No params detected :-/ **/


/** Function ajax_woo_adv_bulk_edit_compat() called by wp_ajax hooks: {'wpmelon_adv_bulk_edit'} **/
/** Parameters found in function ajax_woo_adv_bulk_edit_compat(): {"post": ["type"]} **/
function ajax_woo_adv_bulk_edit_compat( string $import_id ): void {
		if ( ! WC_Facebookcommerce_Utils::check_woo_ajax_permissions( 'adv bulk edit', false ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$type = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';

		if ( strpos( $type, 'product' ) !== false && strpos( $type, 'load' ) === false ) {
			$this->display_out_of_sync_message( 'advanced bulk edit' );
		}
	}


/** Function reset_plugin_updated_successfully_but_master_sync_off_banner() called by wp_ajax hooks: {'nopriv_wc_banner_post_update__master_sync_off_close_action', 'wc_banner_post_update__master_sync_off_close_action'} **/
/** No params detected :-/ **/


/** Function ajax_dismiss_unmapped_attributes_banner() called by wp_ajax hooks: {'fb_dismiss_unmapped_attributes_banner'} **/
/** No params detected :-/ **/


