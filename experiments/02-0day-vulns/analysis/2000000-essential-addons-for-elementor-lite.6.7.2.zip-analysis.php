<?php
/***
*
*Found actions: 47
*Found functions:33
*Extracted functions:33
*Total parameter names extracted: 32
*Overview: {'ajax_skip': {'eael_thinkrank_skip'}, 'ajax_dismiss_banner': {'eael_thinkrank_dismiss'}, 'clear_cache_files': {'eael_clear_cache_files_with_ajax'}, 'templately_promo_status': {'templately_promo_status'}, 'save_settings': {'eael_save_settings_with_ajax'}, 'eael_checkout_cart_qty_update': {'eael_checkout_cart_qty_update', 'nopriv_eael_checkout_cart_qty_update'}, 'eael_ajax_verify_otp': {'eael_lr_verify_otp', 'nopriv_eael_lr_verify_otp'}, 'facebook_feed_render_items': {'nopriv_facebook_feed_load_more', 'facebook_feed_load_more'}, 'get_compare_table': {'nopriv_eael_product_grid', 'eael_product_grid'}, 'ajax_never_show': {'eael_thinkrank_never_show'}, 'woo_checkout_update_order_review': {'woo_checkout_update_order_review', 'nopriv_woo_checkout_update_order_review'}, 'eael_product_add_to_cart': {'eael_product_add_to_cart', 'nopriv_eael_product_add_to_cart'}, 'save_setup_wizard_data': {'save_setup_wizard_data'}, 'save_eael_elements_data': {'save_eael_elements_data'}, 'eael_woo_pagination_ajax': {'woo_product_pagination', 'nopriv_woo_product_pagination'}, 'eael_admin_promotion': {'eael_admin_promotion'}, 'select2_ajax_posts_filter_autocomplete': {'eael_select2_search_post'}, 'ajax_auto_active_even_not_installed': {'wpdeveloper_auto_active_even_not_installed'}, 'ajax_eael_product_gallery': {'eael_product_gallery', 'nopriv_eael_product_gallery'}, 'ajax_deactivate_plugin': {'wpdeveloper_deactivate_plugin'}, 'eael_woo_pagination_product_ajax': {'nopriv_woo_product_pagination_product', 'woo_product_pagination_product'}, 'ajax_install_plugin': {'wpdeveloper_install_plugin'}, 'select2_ajax_get_posts_value_titles': {'eael_select2_get_title'}, 'eael_ajax_send_otp': {'nopriv_eael_lr_send_otp', 'eael_lr_send_otp'}, 'eael_clear_widget_cache_data': {'eael_clear_widget_cache_data'}, 'eael_get_token': {'eael_get_token', 'nopriv_eael_get_token'}, 'ajax_activate_plugin': {'wpdeveloper_activate_plugin'}, 'ajax_load_more': {'load_more', 'nopriv_load_more'}, 'enable_wpins_process': {'enable_wpins_process'}, 'ajax_snooze_banner': {'eael_thinkrank_snooze'}, 'eael_ajax_add_to_cart': {'nopriv_eael_ajax_add_to_cart', 'eael_ajax_add_to_cart'}, 'eael_product_quickview_popup': {'eael_product_quickview_popup', 'nopriv_eael_product_quickview_popup'}, 'ajax_upgrade_plugin': {'wpdeveloper_upgrade_plugin'}}
*
***/

/** Function ajax_skip() called by wp_ajax hooks: {'eael_thinkrank_skip'} **/
/** No params detected :-/ **/


/** Function ajax_dismiss_banner() called by wp_ajax hooks: {'eael_thinkrank_dismiss'} **/
/** No params detected :-/ **/


/** Function clear_cache_files() called by wp_ajax hooks: {'eael_clear_cache_files_with_ajax'} **/
/** Parameters found in function clear_cache_files(): {"request": ["posts"], "post": ["posts"]} **/
function clear_cache_files() {
		check_ajax_referer( 'essential-addons-elementor', 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'you are not allowed to do this action', 'essential-addons-for-elementor-lite' ) );
		}

		if ( isset( $_REQUEST['posts'] ) ) {
			if ( ! empty( $_POST['posts'] ) ) {
				foreach ( json_decode( sanitize_text_field( wp_unslash( $_POST['posts'] ) ) ) as $post ) {
					$this->remove_files( 'post-' . $post );
				}
			}
		} else {
			// clear cache files
			$this->empty_dir( EAEL_ASSET_PATH );
			if ( $this->is_activate_elementor() ) {
				\Elementor\Plugin::$instance->files_manager->clear_cache();
			}
		}

		// Purge All LS Cache
		// phpcs:disable
		do_action( 'litespeed_purge_all', '3rd Essential Addons for Elementor' );
		// phpcs:enable

		// After clear the cache hook
		do_action( 'eael_after_clear_cache_files' );

		wp_send_json( true );
	}


/** Function templately_promo_status() called by wp_ajax hooks: {'templately_promo_status'} **/
/** No params detected :-/ **/


/** Function save_settings() called by wp_ajax hooks: {'eael_save_settings_with_ajax'} **/
/** Parameters found in function save_settings(): {"post": ["is_login_register"]} **/
function save_settings() {
		check_ajax_referer( 'essential-addons-elementor', 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'you are not allowed to do this action', 'essential-addons-for-elementor-lite' ) );
		}

		$settings = array_map( 'sanitize_text_field', $_POST );
		unset( $settings['action'], $settings['security'] );
		$settings['embedpress']     = true;
		$settings['career-page']    = true;
		$settings['better-payment'] = true;

		if ( ! empty( $_POST['is_login_register'] ) ) {
			// Saving Login | Register Related Data
			if ( isset( $settings['recaptchaSiteKey'] ) ) {
				update_option( 'eael_recaptcha_sitekey', sanitize_text_field( $settings['recaptchaSiteKey'] ) );
			}
			if ( isset( $settings['recaptchaSiteSecret'] ) ) {
				update_option( 'eael_recaptcha_secret', sanitize_text_field( $settings['recaptchaSiteSecret'] ) );
			}
			if ( isset( $settings['recaptchaLanguage'] ) ) {
				update_option( 'eael_recaptcha_language', sanitize_text_field( $settings['recaptchaLanguage'] ) );
			}

			//reCAPTCHA V3
			if ( isset( $settings['recaptchaSiteKeyV3'] ) ) {
				update_option( 'eael_recaptcha_sitekey_v3', sanitize_text_field( $settings['recaptchaSiteKeyV3'] ) );
			}
			if ( isset( $settings['recaptchaSiteSecretV3'] ) ) {
				update_option( 'eael_recaptcha_secret_v3', sanitize_text_field( $settings['recaptchaSiteSecretV3'] ) );
			}
			if ( isset( $settings['recaptchaLanguageV3'] ) ) {
				update_option( 'eael_recaptcha_language_v3', sanitize_text_field( $settings['recaptchaLanguageV3'] ) );
			}

			//pro settings
			if ( isset( $settings['gClientId'] ) ) {
				update_option( 'eael_g_client_id', sanitize_text_field( $settings['gClientId'] ) );
			}
			if ( isset( $settings['fbAppId'] ) ) {
				update_option( 'eael_fb_app_id', sanitize_text_field( $settings['fbAppId'] ) );
			}
			if ( isset( $settings['fbAppSecret'] ) ) {
				update_option( 'eael_fb_app_secret', sanitize_text_field( $settings['fbAppSecret'] ) );
			}

			wp_send_json_success( [ 'message' => __( 'Login | Register Settings updated', 'essential-addons-for-elementor-lite' ) ] );
		}

		//Login-register data
		if ( isset( $settings['lr_recaptcha_sitekey'] ) ) {
			update_option( 'eael_recaptcha_sitekey', sanitize_text_field( $settings['lr_recaptcha_sitekey'] ) );
		}
		if ( isset( $settings['lr_recaptcha_secret'] ) ) {
			update_option( 'eael_recaptcha_secret', sanitize_text_field( $settings['lr_recaptcha_secret'] ) );
		}
		if ( isset( $settings['lr_recaptcha_language'] ) ) {
			update_option( 'eael_recaptcha_language', sanitize_text_field( $settings['lr_recaptcha_language'] ) );
		}

		//Cloudflare Turnstile
		if ( isset( $settings['lr_cloudflare_turnstile_sitekey'] ) ) {
			update_option( 'eael_cloudflare_turnstile_sitekey', sanitize_text_field( $settings['lr_cloudflare_turnstile_sitekey'] ) );
		}
		if ( isset( $settings['lr_cloudflare_turnstile_secretkey'] ) ) {
			update_option( 'eael_cloudflare_turnstile_secretkey', sanitize_text_field( $settings['lr_cloudflare_turnstile_secretkey'] ) );
		}

		//reCAPTCHA v3
		if ( isset( $settings['lr_recaptcha_sitekey_v3'] ) ) {
			update_option( 'eael_recaptcha_sitekey_v3', sanitize_text_field( $settings['lr_recaptcha_sitekey_v3'] ) );
		}
		if ( isset( $settings['lr_recaptcha_secret_v3'] ) ) {
			update_option( 'eael_recaptcha_secret_v3', sanitize_text_field( $settings['lr_recaptcha_secret_v3'] ) );
		}
		if ( isset( $settings['lr_recaptcha_language_v3'] ) ) {
			update_option( 'eael_recaptcha_language_v3', sanitize_text_field( $settings['lr_recaptcha_language_v3'] ) );
		}

		if ( isset( $settings['lr_recaptcha_badge_hide'] ) ) {
			update_option( 'eael_recaptcha_badge_hide', sanitize_text_field( $settings['lr_recaptcha_badge_hide'] ) );
		}

		if ( isset( $settings['lr_custom_profile_fields'] ) ) {
			update_option( 'eael_custom_profile_fields', sanitize_text_field( $settings['lr_custom_profile_fields'] ) );
		}

		if ( isset( $settings['lr_custom_profile_fields_text'] ) ) {
			update_option( 'eael_custom_profile_fields_text', sanitize_text_field( $settings['lr_custom_profile_fields_text'] ) );
		}

		if ( isset( $settings['lr_custom_profile_fields_img'] ) ) {
			update_option( 'eael_custom_profile_fields_img', sanitize_text_field( $settings['lr_custom_profile_fields_img'] ) );
		}

		//pro settings
		if ( isset( $settings['lr_g_client_id'] ) ) {
			update_option( 'eael_g_client_id', sanitize_text_field( $settings['lr_g_client_id'] ) );
		}
		if ( isset( $settings['lr_fb_app_id'] ) ) {
			update_option( 'eael_fb_app_id', sanitize_text_field( $settings['lr_fb_app_id'] ) );
		}
		if ( isset( $settings['lr_fb_app_secret'] ) ) {
			update_option( 'eael_fb_app_secret', sanitize_text_field( $settings['lr_fb_app_secret'] ) );
		}

		// Business Reviews : Saving Google Place Api Key
		if ( isset( $settings['br_google_place_api_key'] ) ) {
			update_option( 'eael_br_google_place_api_key', sanitize_text_field( $settings['br_google_place_api_key'] ) );
		}







		// Saving Google Map Api Key
		if ( isset( $settings['google-map-api'] ) ) {
			update_option( 'eael_save_google_map_api', sanitize_text_field( $settings['google-map-api'] ) );
		}

		// Saving Mailchimp Api Key
		if ( isset( $settings['mailchimp-api'] ) ) {
			update_option( 'eael_save_mailchimp_api', sanitize_text_field( $settings['mailchimp-api'] ) );
		}

		// Saving Mailchimp Api Key for EA Login | Register Form
		if ( isset( $settings['lr_mailchimp_api_key'] ) ) {
			update_option( 'eael_lr_mailchimp_api_key', sanitize_text_field( $settings['lr_mailchimp_api_key'] ) );
		}

		// Saving TYpeForm token
		if ( isset( $settings['typeform-personal-token'] ) ) {
			update_option( 'eael_save_typeform_personal_token', sanitize_text_field( $settings['typeform-personal-token'] ) );
		}

		// Saving Duplicator Settings
		if ( isset( $settings['post-duplicator-post-type'] ) ) {
			update_option( 'eael_save_post_duplicator_post_type', sanitize_text_field( $settings['post-duplicator-post-type'] ) );
		}

		// Saving Woo Acount Dashboard Settings
		if ( isset( $settings['woo-account-dashboard-custom-tabs'] ) ) {
			update_option( 'eael_woo_ac_dashboard_custom_tabs', sanitize_text_field( $settings['woo-account-dashboard-custom-tabs'] ) );
		}

		// save js print method
		if ( isset( $settings['eael-js-print-method'] ) ) {
			update_option( 'eael_js_print_method', sanitize_text_field( $settings['eael-js-print-method'] ) );
		}

		if ( ! empty( $settings['elements'] ) ) {
			$defaults    = array_fill_keys( array_keys( array_merge( $this->registered_elements, $this->registered_extensions ) ), false );
			$elements    = array_merge( $defaults, array_fill_keys( array_keys( array_intersect_key( $settings, $defaults ) ), true ) );
			$el_disable  = get_option( 'elementor_disabled_elements', [] );
			$new_disable = [];

			foreach ( $el_disable as $element ) {
				$el_new_name = Elements_Manager::replace_widget_name();
				$el_new_name = $el_new_name[ $element ] ?? $element;
				$el_new_name = substr( $el_new_name, 5 );
				if ( in_array( $el_new_name, $elements ) && $elements[ $el_new_name ] === true ) {
					continue;
				}

				$new_disable[] = $element;
			}

			// update new settings
			$updated = update_option( 'eael_save_settings', $elements );
			update_option( 'elementor_disabled_elements', $new_disable );

			// clear assets files
			$this->empty_dir( EAEL_ASSET_PATH );
		}

		do_action( 'eael/admin/after_save_settings', $settings );

		wp_send_json_success( true );
	}


/** Function eael_checkout_cart_qty_update() called by wp_ajax hooks: {'eael_checkout_cart_qty_update', 'nopriv_eael_checkout_cart_qty_update'} **/
/** Parameters found in function eael_checkout_cart_qty_update(): {"post": ["nonce", "cart_item_key", "quantity"]} **/
function eael_checkout_cart_qty_update() {
        if ( empty( $_POST['nonce'] ) || ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'essential-addons-elementor' ) ) ) {
            die( esc_html__( 'Permission Denied!', 'essential-addons-for-elementor-lite' ) );
        }

        $cart_item_key = !empty( $_POST['cart_item_key'] ) ? sanitize_text_field( wp_unslash( $_POST['cart_item_key'] ) ) : '';
		$cart_item = WC()->cart->get_cart_item( $cart_item_key );
		$cart_item_quantity = !empty( $_POST['quantity']) ? absint( wp_unslash( $_POST['quantity'] ) ) :0;
		$cart_item_quantity = apply_filters( 'woocommerce_stock_amount_cart_item', 
		apply_filters( 'woocommerce_stock_amount', 
		preg_replace( 
			"/[^0-9\.]/", 
			'', 
			filter_var($cart_item_quantity, FILTER_SANITIZE_NUMBER_INT)
			) 
		), $cart_item_key );

		$passed_validation  = apply_filters( 'woocommerce_update_cart_validation', true, $cart_item_key, $cart_item, $cart_item_quantity );
		if ( $passed_validation ) {
			WC()->cart->set_quantity( $cart_item_key, $cart_item_quantity, true );
			wp_send_json_success(
                array(
                    'message' => __( 'Quantity updated successfully.', 'essential-addons-for-elementor-lite' ),
                    // 'cart_item_key' => $cart_item_key,
                    'cart_item_quantity' => $cart_item_quantity,
                    'cart_item_subtotal' => WC()->cart->get_product_subtotal( $cart_item['data'], $cart_item_quantity ),
                    'cart_subtotal' => WC()->cart->get_cart_subtotal(),
                    'cart_total' => WC()->cart->get_cart_total()
                )
            );
		} else {
    		wp_send_json_error(
                array(
                    'message' => __( 'Quantity update failed.', 'essential-addons-for-elementor-lite' ),
                )
            );
        }

		die();
	}


/** Function eael_ajax_verify_otp() called by wp_ajax hooks: {'eael_lr_verify_otp', 'nopriv_eael_lr_verify_otp'} **/
/** Parameters found in function eael_ajax_verify_otp(): {"post": ["otp_token", "otp_code"]} **/
function eael_ajax_verify_otp() {
		check_ajax_referer( 'eael_lr_otp', '_eael_otp_nonce' );

		$token = ! empty( $_POST['otp_token'] ) ? sanitize_text_field( wp_unslash( $_POST['otp_token'] ) ) : '';
		$code  = ! empty( $_POST['otp_code'] ) ? preg_replace( '/[^0-9]/', '', wp_unslash( $_POST['otp_code'] ) ) : '';

		if ( empty( $token ) || empty( $code ) ) {
			wp_send_json_error( [ 'message' => __( 'Please enter the verification code.', 'essential-addons-for-elementor-lite' ) ] );
		}

		$session = get_transient( self::$otp_transient_prefix . $token );
		if ( empty( $session ) || ! is_array( $session ) ) {
			wp_send_json_error( [ 'message' => __( 'Your verification code has expired. Please request a new one.', 'essential-addons-for-elementor-lite' ) ] );
		}

		if ( (int) $session['attempts'] >= 5 ) {
			delete_transient( self::$otp_transient_prefix . $token );
			wp_send_json_error( [ 'message' => __( 'Too many invalid attempts. Please request a new code.', 'essential-addons-for-elementor-lite' ) ] );
		}

		$expected = $this->eael_otp_hash_code( $code, $token );
		if ( ! hash_equals( (string) $session['code_hash'], $expected ) ) {
			$session['attempts'] = (int) $session['attempts'] + 1;
			set_transient( self::$otp_transient_prefix . $token, $session, (int) $session['expiry'] );
			wp_send_json_error( [
				'message'  => __( 'Invalid verification code. Please try again.', 'essential-addons-for-elementor-lite' ),
				'attempts' => $session['attempts'],
			] );
		}

		// Code valid: consume the session immediately.
		delete_transient( self::$otp_transient_prefix . $token );

		$settings = $this->lr_get_widget_settings( (int) $session['page_id'], $session['widget_id'] );
		if ( empty( $settings ) ) {
			wp_send_json_error( [ 'message' => __( 'Widget settings could not be loaded.', 'essential-addons-for-elementor-lite' ) ] );
		}

		if ( 'register' === $session['flow'] ) {
			$result = $this->eael_otp_finalize_register( $session, $settings );
		} else {
			$result = $this->eael_otp_finalize_login( $session, $settings );
		}

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( [ 'message' => $result->get_error_message() ] );
		}

		wp_send_json_success( $result );
	}


/** Function facebook_feed_render_items() called by wp_ajax hooks: {'nopriv_facebook_feed_load_more', 'facebook_feed_load_more'} **/
/** Parameters found in function facebook_feed_render_items(): {"request": ["action"], "post": ["page", "post_id", "widget_id"]} **/
function facebook_feed_render_items( $settings = [] ) {
		// check if ajax request
		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'facebook_feed_load_more' ) { //phpcs:ignore WordPress.Security.NonceVerification.Missing
			$ajax = wp_doing_ajax();
			// check ajax referer
			check_ajax_referer( 'essential-addons-elementor', 'security' );

			// init vars
			$page = !empty( $_POST['page'] ) ? intval( wp_unslash( $_POST['page'] ), 10 ) : 0;
			if ( ! empty( $_POST['post_id'] ) ) {
				$post_id = intval( wp_unslash( $_POST['post_id'] ), 10 );
			} else {
				$err_msg = __( 'Post ID is missing', 'essential-addons-for-elementor-lite' );
				if ( $ajax ) {
					wp_send_json_error( $err_msg );
				}

				return false;
			}
			if ( ! empty( $_POST['widget_id'] ) ) {
				$widget_id = sanitize_text_field( wp_unslash( $_POST['widget_id'] ) );
			} else {
				$err_msg = __( 'Widget ID is missing', 'essential-addons-for-elementor-lite' );
				if ( $ajax ) {
					wp_send_json_error( $err_msg );
				}

				return false;
			}
			$settings = HelperClass::eael_get_widget_settings( $post_id, $widget_id );

		} else {
			// init vars
			$page     = 0;
			$settings = ! empty( $settings ) ? $settings : $this->get_settings_for_display();
		}

		$html    = '';
		$page_id = $settings['eael_facebook_feed_page_id'];
		$token   = $settings['eael_facebook_feed_access_token'];
		$source    = $settings['eael_facebook_feed_data_source'];
        $display_comment = isset( $settings['eael_facebook_feed_comments'] ) ? $settings['eael_facebook_feed_comments'] : '';

		if ( empty( $page_id ) || empty( $token ) ) {
			return;
		}

		$key = sprintf(
			'eael_facebook_feed_%s',
			md5(
				sanitize_text_field( wp_unslash( $source . $page_id . $token ) ) .
				absint( $settings['eael_facebook_feed_cache_limit'] )
			)
		);
		$facebook_data = get_transient( $key );

		if ( $facebook_data == false ) {
			$facebook_data = wp_remote_retrieve_body( wp_remote_get( $this->get_url($page_id, $token, $source, $display_comment), [
				'timeout' => 70,
			] ) );
			$facebook_data = json_decode( $facebook_data, true );
			if ( isset( $facebook_data['data'] ) ) {
				set_transient( $key, $facebook_data, ( $settings['eael_facebook_feed_cache_limit'] * MINUTE_IN_SECONDS ) );
			}
		}

		if ( ! isset( $facebook_data['data'] ) ) {
			return;
		}
		$facebook_data = $facebook_data['data'];

		switch ( $settings['eael_facebook_feed_sort_by'] ) {
			case 'least-recent':
				$facebook_data = array_reverse( $facebook_data );
				break;
		}
		$items = array_splice( $facebook_data, ( $page * $settings['eael_facebook_feed_image_count']['size'] ), $settings['eael_facebook_feed_image_count']['size'] );
		$bg_style = isset( $settings['eael_facebook_feed_image_render_type'] ) && $settings['eael_facebook_feed_image_render_type'] == 'cover' ? "background-size: cover;background-position: center;background-repeat: no-repeat;" : "background-size: 100% 100%;background-repeat: no-repeat;";
		foreach ( $items as $item ) {
			$t        = 'eael_facebook_feed_message_max_length'; // short it
			$limit    = isset( $settings[ $t ] ) && isset( $settings[ $t ]['size'] ) ? $settings[ $t ]['size'] : null;
			$message  = wp_trim_words( ( isset( $item['message'] ) ? $item['message'] : ( isset( $item['story'] ) ? $item['story'] : '' ) ), $limit, '...' );
			$photo    = ( isset( $item['full_picture'] ) ? esc_url( $item['full_picture'] ) : '' );
			$likes    = ( isset( $item['reactions'] ) ? $item['reactions']['summary']['total_count'] : 0 );
			$comments = ( isset( $item['comments'] ) ? $item['comments']['summary']['total_count'] : 0 );

			if ( empty( $photo ) ) {
				$photo = isset( $item['attachments']['data'][0]['media']['image']['src'] ) ? esc_url( $item['attachments']['data'][0]['media']['image']['src'] ) : $photo;
			}

			if ( $settings['eael_facebook_feed_layout'] == 'card' ) {
				$item_form_name  = ! empty( $item['from']['name'] ) ? $item['from']['name'] : '';
				$current_page_id = ! empty( $item['from']['id'] ) ? $item['from']['id'] : $page_id;

				$html .= '<div class="eael-facebook-feed-item">
                    <div class="eael-facebook-feed-item-inner">
                        <header class="eael-facebook-feed-item-header clearfix">
                            <div class="eael-facebook-feed-item-user clearfix">
                                <a href="https://www.facebook.com/' . $current_page_id . '" target="' . ( $settings['eael_facebook_feed_link_target'] == 'yes' ? '_blank' : '_self' ) . '"><img src="https://graph.facebook.com/v4.0/' . $current_page_id . '/picture" alt="' . eael_neutralize_shortcodes( esc_attr( $item_form_name ) ) . '" class="eael-facebook-feed-avatar"></a>
                                <a href="https://www.facebook.com/' . $current_page_id . '" target="' . ( $settings['eael_facebook_feed_link_target'] == 'yes' ? '_blank' : '_self' ) . '"><p class="eael-facebook-feed-username">' . eael_neutralize_shortcodes( esc_html( $item_form_name ) ) . '</p></a>
                            </div>';

				if ( $settings['eael_facebook_feed_date'] ) {
					$html .= '<a href="' . esc_url( $item['permalink_url'] ) . '" target="' . ( $settings['eael_facebook_feed_link_target'] ? '_blank' : '_self' ) . '" class="eael-facebook-feed-post-time"><i class="far fa-clock" aria-hidden="true"></i> ' . date_i18n( get_option('date_format'), strtotime( $item['created_time'] ) ) . '</a>';
				}
				$html .= '</header>';

				if ( $settings['eael_facebook_feed_message'] && ! empty( $message ) ) {
					$html .= '<div class="eael-facebook-feed-item-content">
                                        <p class="eael-facebook-feed-message">' . eael_neutralize_shortcodes( wp_kses_post( $this->eael_str_check( $message ) ) ) . '</p>
                                    </div>';
				}

				if ( ! empty( $photo ) || isset( $item['attachments']['data'] ) ) {
					$html .= '<div class="eael-facebook-feed-preview-wrap">';
					if ( $item['status_type'] == 'shared_story' ) {

						if ( isset( $settings['eael_facebook_feed_is_show_preview_thumbnail'] ) && 'yes' == $settings['eael_facebook_feed_is_show_preview_thumbnail'] ) {

							$html .= '<a href="' . esc_url( $item['permalink_url'] ) . '" target="' . ( $settings['eael_facebook_feed_link_target'] == 'yes' ? '_blank' : '_self' ) . '" class="eael-facebook-feed-preview-img">';
							if ( !empty($item['attachments']['data'][0]['media_type']) && $item['attachments']['data'][0]['media_type'] == 'video' ) {
								$html .= '<div class="eael-facebook-feed-img-container" style="background:url(' . esc_url( $photo ) . ');' . esc_attr( $bg_style ) . '">
								<img class="eael-facebook-feed-img" src="' . esc_url( $photo ) . '"></div>
	                                                    <div class="eael-facebook-feed-preview-overlay"><i class="far fa-play-circle" aria-hidden="true"></i></div>';
							} else {
								$html .= '<div class="eael-facebook-feed-img-container" style="background:url(' . esc_url( $photo ) . ');' . esc_attr( $bg_style ) . '">
								<img class="eael-facebook-feed-img" src="' . esc_url( $photo ) . '"></div>';
							}
							$html .= '</a>';
						}

						$html .= '<div class="eael-facebook-feed-url-preview">';
						if ( isset( $settings['eael_facebook_feed_is_show_preview_host'] ) && 'yes' == $settings['eael_facebook_feed_is_show_preview_host'] && !empty($item['attachments']['data'][0]['unshimmed_url']) ) {
							$html .= '<p class="eael-facebook-feed-url-host">' . eael_neutralize_shortcodes( esc_html( wp_parse_url( $item['attachments']['data'][0]['unshimmed_url'] )['host'] ) ) . '</p>';
						}
						if ( isset( $settings['eael_facebook_feed_is_show_preview_title'] ) && 'yes' == $settings['eael_facebook_feed_is_show_preview_title'] ) {
							$html .= '<h2 class="eael-facebook-feed-url-title">' . eael_neutralize_shortcodes( esc_html( $item['attachments']['data'][0]['title'] ?? '' ) ) . '</h2>';
						}

						if ( isset( $settings['eael_facebook_feed_is_show_preview_description'] ) && 'yes' == $settings['eael_facebook_feed_is_show_preview_description'] ) {
							$description = isset( $item['attachments']['data'][0]['description'] ) ? $item['attachments']['data'][0]['description'] : '';
							$html        .= '<p class="eael-facebook-feed-url-description">' . eael_neutralize_shortcodes( wp_kses( $description, HelperClass::eael_allowed_tags() ) ) . '</p>';
						}
						$html .= '</div>';

					} else if ( $item['status_type'] == 'added_video' ) {
						if ( isset( $settings['eael_facebook_feed_is_show_preview_thumbnail'] ) && 'yes' == $settings['eael_facebook_feed_is_show_preview_thumbnail'] ) {

							$html .= '<a href="' . esc_url( $item['permalink_url'] ) . '" target="' . ( $settings['eael_facebook_feed_link_target'] == 'yes' ? '_blank' : '_self' ) . '" class="eael-facebook-feed-preview-img">
	                                                <div class="eael-facebook-feed-img-container" style="background:url(' . esc_url( $photo ) . '); ' . esc_attr( $bg_style ) . '">
	                                                    <img class="eael-facebook-feed-img" src="' . esc_url( $photo ) . '">
	                                                </div>
	                                                <div class="eael-facebook-feed-preview-overlay"><i class="far fa-play-circle" aria-hidden="true"></i></div>
	                                            </a>';
						}
					} else {
						if ( isset( $settings['eael_facebook_feed_is_show_preview_thumbnail'] ) && 'yes' == $settings['eael_facebook_feed_is_show_preview_thumbnail'] ) {

							$html .= '<a href="' . esc_url( $item['permalink_url'] ) . '" target="' . ( $settings['eael_facebook_feed_link_target'] == 'yes' ? '_blank' : '_self' ) . '" class="eael-facebook-feed-preview-img">
	                                                <div class="eael-facebook-feed-img-container" style="background:url(' . esc_url( $photo ) . '); ' . esc_attr( $bg_style ) . '">
	                                                    <img class="eael-facebook-feed-img" src="' . esc_url( $photo ) . '">
	                                                </div>
	                                            </a>';

						}
					}
					$html .= '</div>';
				}


				if ( $settings['eael_facebook_feed_likes'] || $settings['eael_facebook_feed_comments'] ) {
					$html .= '<footer class="eael-facebook-feed-item-footer">
                                <div class="clearfix">';
					if ( $settings['eael_facebook_feed_likes'] ) {
						$html .= '<span class="eael-facebook-feed-post-likes"><i class="far fa-thumbs-up" aria-hidden="true"></i> ' . esc_html( $likes ) . '</span>';
					}
					if ( $settings['eael_facebook_feed_comments'] ) {
						$html .= '<span class="eael-facebook-feed-post-comments"><i class="far fa-comments" aria-hidden="true"></i> ' . esc_html( $comments ) . '</span>';
					}
					$html .= '</div>
                            </footer>';
				}
				$html .= '</div>
                </div>';
			} else {
				$html .= '<a href="' . esc_url( $item['permalink_url'] ) . '" target="' . ( $settings['eael_facebook_feed_link_target'] ? '_blank' : '_self' ) . '" class="eael-facebook-feed-item">
                    <div class="eael-facebook-feed-item-inner">
                    	<div class="eael-facebook-feed-img-container" style="background:url(' . ( empty( $photo ) ? EAEL_PLUGIN_URL . 'assets/front-end/img/flexia-preview.jpg' : esc_url( $photo ) ) . '); ' . esc_attr( $bg_style ) . '">
                            <img class="eael-facebook-feed-img" src="' . ( empty( $photo ) ? EAEL_PLUGIN_URL . 'assets/front-end/img/flexia-preview.jpg' : esc_url( $photo ) ) . '">
                        </div>';

				if ( $settings['eael_facebook_feed_likes'] || $settings['eael_facebook_feed_comments'] ) {
					$html .= '<div class="eael-facebook-feed-item-overlay">
                                        <div class="eael-facebook-feed-item-overlay-inner">
                                            <div class="eael-facebook-feed-meta">';
					if ( $settings['eael_facebook_feed_likes'] ) {
						$html .= '<span class="eael-facebook-feed-post-likes"><i class="far fa-thumbs-up" aria-hidden="true"></i> ' . esc_html( $likes ) . '</span>';
					}
					if ( $settings['eael_facebook_feed_comments'] ) {
						$html .= '<span class="eael-facebook-feed-post-comments"><i class="far fa-comments" aria-hidden="true"></i> ' . esc_html( $comments ) . '</span>';
					}
					$html .= '</div>
                                        </div>
                                    </div>';
				}
				$html .= '</div>
                </a>';
			}
		}

		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'facebook_feed_load_more' ) { //phpcs:ignore WordPress.Security.NonceVerification.Missing
			$data = [
				'num_pages' => ceil( count( $facebook_data ) / $settings['eael_facebook_feed_image_count']['size'] ),
				'html'      => $html,
			];
			while ( ob_get_status() ) {
				ob_end_clean();
			}
			wp_send_json( $data );
			wp_die();

		}

		return $html;
	}


/** Function get_compare_table() called by wp_ajax hooks: {'nopriv_eael_product_grid', 'eael_product_grid'} **/
/** Parameters found in function get_compare_table(): {"post": ["page_id", "widget_id", "product_id", "product_ids", "remove_product", "nonce"]} **/
function get_compare_table() {
		$ajax      = wp_doing_ajax();
		$page_id   = 0;
		$widget_id = 0;

		if ( ! empty( $_POST['page_id'] ) ) {
			$page_id = intval( $_POST['page_id'], 10 );
		} else {
			$err_msg = __( 'Page ID is missing', 'essential-addons-for-elementor-lite' );
		}
		if ( ! empty( $_POST['widget_id'] ) ) {
			$widget_id = sanitize_text_field( wp_unslash( $_POST['widget_id'] ) );
		} else {
			$err_msg = __( 'Widget ID is missing', 'essential-addons-for-elementor-lite' );
		}
		if ( ! empty( $_POST['product_id'] ) ) {
			$product_id = sanitize_text_field( wp_unslash( $_POST['product_id'] ) );
		} else {
			$err_msg = __( 'Product ID is missing', 'essential-addons-for-elementor-lite' );
		}

        if (!empty($_POST['product_ids'])) {
            $product_ids = json_decode(sanitize_text_field( wp_unslash($_POST['product_ids']) ));
		}

        if (empty($product_ids)) {
            $product_ids = [];
        }

		if ( ! empty( $product_id ) ) {
			$p_exist = ! empty( $product_ids ) && is_array( $product_ids );
			if ( ! empty( $_POST['remove_product'] ) && $p_exist ) {
			    $product_ids = array_filter($product_ids, function ($id) use ($product_id){
                    return $id != intval( $product_id );
			    });
			} else {
			    $product_ids[] = intval( $product_id );
			}
		}


		if ( ! empty( $err_msg ) ) {
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}

			return false;
		}
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'eael_product_grid' ) ) {
			if ( $ajax ) {
				wp_send_json_error( __( 'Security token did not match', 'essential-addons-for-elementor-lite' ) );
			}

			return false;
		}
		$product_ids = array_values(array_unique($product_ids));

		$ds          = $this->eael_get_widget_settings( $page_id, $widget_id );
		$products    = self::static_get_products_list( $product_ids, $ds );
		$fields      = self::static_fields( $product_ids, $ds );
		ob_start();
		self::render_compare_table( compact( 'products', 'fields', 'ds' ) );
		$table = ob_get_clean();
		wp_send_json_success( [ 'compare_table' => $table, 'product_ids' => $product_ids ] );

		return null;
	}


/** Function ajax_never_show() called by wp_ajax hooks: {'eael_thinkrank_never_show'} **/
/** No params detected :-/ **/


/** Function woo_checkout_update_order_review() called by wp_ajax hooks: {'woo_checkout_update_order_review', 'nopriv_woo_checkout_update_order_review'} **/
/** Parameters found in function woo_checkout_update_order_review(): {"post": ["orderReviewData", "shippingData", "post_data", "woocommerce_checkout_update_totals"]} **/
function woo_checkout_update_order_review() {
		check_ajax_referer( 'essential-addons-elementor', 'security' );

		// phpcs:disable
		$setting       = $_POST['orderReviewData'];
        $shipping_data = empty ( $_POST['shippingData'] ) ? WC()->session->get('chosen_shipping_methods') : [wc_clean( $_POST['shippingData'] )];
		//Mondial Relay plugin integration
		do_action( 'eael_mondialrelay_order_after_shipping' );
        
        WC()->session->set( 'chosen_shipping_methods', $shipping_data );

        // Required for Avatax (and standard WC) to trigger calculation
		if ( isset( $_POST['post_data'] ) ) {
			$_POST['woocommerce_checkout_update_totals'] = 1;
			do_action( 'woocommerce_checkout_update_order_review', $_POST['post_data'] );
		}
        // Force is_checkout() to be true so plugins like Avatax display the correct message
        add_filter( 'woocommerce_is_checkout', '__return_true' );

		ob_start();
		AllTraits::checkout_order_review_default( $setting );
		$woo_checkout_update_order_review = ob_get_clean();
		// phpcs:enable
		wp_send_json(
			array(
				'order_review' => $woo_checkout_update_order_review,
			)
		);
	}


/** Function eael_product_add_to_cart() called by wp_ajax hooks: {'eael_product_add_to_cart', 'nopriv_eael_product_add_to_cart'} **/
/** Parameters found in function eael_product_add_to_cart(): {"post": ["cart_item_data", "product_data"]} **/
function eael_product_add_to_cart() {
		check_ajax_referer( 'essential-addons-elementor', 'security' );

		$ajax       = wp_doing_ajax();
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		$cart_items = isset( $_POST['cart_item_data'] ) ? $_POST['cart_item_data'] : [];
		$variation  = [];
		if ( ! empty( $cart_items ) ) {
			foreach ( $cart_items as $key => $value ) {
				if ( preg_match( "/^attribute*/", $value['name'] ) ) {
					$variation[ $value['name'] ] = sanitize_text_field( wp_unslash( $value['value'] ) );
				}
			}
		}

		if ( isset( $_POST['product_data'] ) ) {
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			foreach ( $_POST['product_data'] as $item ) {
				$product_id   = isset( $item['product_id'] ) ? sanitize_text_field( wp_unslash( $item['product_id'] ) ) : 0;
				$variation_id = isset( $item['variation_id'] ) ? sanitize_text_field( wp_unslash( $item['variation_id'] ) ) : 0;
				$quantity     = isset( $item['quantity'] ) ? sanitize_text_field( wp_unslash( $item['quantity'] ) ) : 0;

				if ( $variation_id ) {
					WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );
				} else {
					WC()->cart->add_to_cart( $product_id, $quantity );
				}
			}
		}
		wp_send_json_success();
	}


/** Function save_setup_wizard_data() called by wp_ajax hooks: {'save_setup_wizard_data'} **/
/** Parameters found in function save_setup_wizard_data(): {"post": ["fields"]} **/
function save_setup_wizard_data() {

		check_ajax_referer( 'essential-addons-elementor', 'security' );

		if ( !current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'you are not allowed to do this action', 'essential-addons-for-elementor-lite' ) );
		}

		if ( !isset( $_POST[ 'fields' ] ) ) {
			return;
		}

		wp_parse_str( $_POST[ 'fields' ], $fields ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash

		if ( isset( $fields[ 'eael_user_email_address' ] ) && intval( $fields[ 'eael_user_email_address' ] ) == 1 ) {
			$this->wpins_process();
		}
		update_option( 'eael_setup_wizard', 'complete' );
		if ( $this->save_element_list( $fields ) ) {
			wp_send_json_success( [ 'redirect_url' => esc_url( admin_url( 'admin.php?page=eael-settings' ) ) ] );
		}
		wp_send_json_error();
	}


/** Function save_eael_elements_data() called by wp_ajax hooks: {'save_eael_elements_data'} **/
/** Parameters found in function save_eael_elements_data(): {"post": ["fields"]} **/
function save_eael_elements_data() {
		check_ajax_referer( 'essential-addons-elementor', 'security' );

		if ( !current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'you are not allowed to do this action', 'essential-addons-for-elementor-lite' ) );
		}

		if ( !isset( $_POST[ 'fields' ] ) ) {
			return;
		}

		wp_parse_str( $_POST[ 'fields' ], $fields ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash

		if ( $this->save_element_list( $fields ) ) {
			wp_send_json_success();
		}
		wp_send_json_error();
	}


/** Function eael_woo_pagination_ajax() called by wp_ajax hooks: {'woo_product_pagination', 'nopriv_woo_product_pagination'} **/
/** Parameters found in function eael_woo_pagination_ajax(): {"post": ["page_id", "widget_id", "number", "limit"], "request": ["args", "template_name"]} **/
function eael_woo_pagination_ajax() {

		check_ajax_referer( 'essential-addons-elementor', 'security' );

		if ( ! empty( $_POST['page_id'] ) ) {
			$page_id = intval( $_POST['page_id'], 10 );
		} else {
			$err_msg = __( 'Page ID is missing', 'essential-addons-for-elementor-lite' );
			wp_send_json_error( $err_msg );
		}

		if ( ! empty( $_POST['widget_id'] ) ) {
			$widget_id = sanitize_text_field( wp_unslash( $_POST['widget_id'] ) );
		} else {
			$err_msg = __( 'Widget ID is missing', 'essential-addons-for-elementor-lite' );
			wp_send_json_error( $err_msg );
		}

		$settings = HelperClass::eael_get_widget_settings( $page_id, $widget_id );

		if ( empty( $settings ) ) {
			wp_send_json_error( [ 'message' => __( 'Widget settings are not found. Did you save the widget before using load more??', 'essential-addons-for-elementor-lite' ) ] );
		}

		$settings['eael_page_id'] = $page_id;
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		wp_parse_str( $_REQUEST['args'], $args );
		$args = $this->eael_sanitize_query_args( $args );

		if ( isset( $args['date_query']['relation'] ) ) {
			$args['date_query']['relation'] = HelperClass::eael_sanitize_relation( $args['date_query']['relation'] );
		}

		$paginationNumber          = ! empty( $_POST['number'] ) ? absint( $_POST['number'] ) : 1;
		$paginationLimit           = ! empty( $_POST['limit'] ) ? absint( $_POST['limit'] ) : 10;
		$pagination_Count          = intval( $args['total_post'] );
		$pagination_Paginationlist = ceil( $pagination_Count / $paginationLimit );
		$last                      = ceil( $pagination_Paginationlist );
		$paginationprev            = $paginationNumber - 1;
		$paginationnext            = $paginationNumber + 1;

		if ( $paginationNumber > 1 ) {
			$paginationprev;
		}
		if ( $paginationNumber < $last ) {
			$paginationnext;
		}

		$adjacents                    = "2";
		$next_label                   = sanitize_text_field( $settings['pagination_next_label'] );
		$prev_label                   = sanitize_text_field( $settings['pagination_prev_label'] );
		$settings['eael_widget_name'] = ! empty( $_REQUEST['template_name'] ) ? realpath( sanitize_file_name( wp_unslash( $_REQUEST['template_name'] ) ) ) : '';
		$setPagination                = "";

		if ( $pagination_Paginationlist > 0 ) {

			$setPagination .= "<ul class='page-numbers'>";

			if ( 1 < $paginationNumber ) {
				$setPagination .= "<li class='pagitext'><a href='javascript:void(0);' class='page-numbers'   data-pnumber='" . esc_attr( $paginationprev ) . "' >" . esc_html( $prev_label ) . "</a></li>";
			}

			if ( $pagination_Paginationlist < 7 + ( $adjacents * 2 ) ) {

				for ( $pagination = 1; $pagination <= $pagination_Paginationlist; $pagination ++ ) {
					$active        = ( $paginationNumber == $pagination ) ? 'current' : '';
					$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", esc_attr( $active ), esc_html( $pagination ) );
				}

			} else if ( $pagination_Paginationlist > 5 + ( $adjacents * 2 ) ) {

				if ( $paginationNumber < 1 + ( $adjacents * 2 ) ) {
					for ( $pagination = 1; $pagination <= 4 + ( $adjacents * 2 ); $pagination ++ ) {

						$active        = ( $paginationNumber == $pagination ) ? 'current' : '';
						$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", esc_attr( $active ), esc_html( $pagination ) );
					}
					$setPagination .= "<li class='pagitext dots'>...</li>";
					$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", esc_attr( $active ), esc_html( $pagination ) );

				} elseif ( $pagination_Paginationlist - ( $adjacents * 2 ) > $paginationNumber && $paginationNumber > ( $adjacents * 2 ) ) {
					$active        = '';
					$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", esc_attr( $active ), 1 );
					$setPagination .= "<li class='pagitext dots'>...</li>";
					for ( $pagination = $paginationNumber - $adjacents; $pagination <= $paginationNumber + $adjacents; $pagination ++ ) {
						$active        = ( $paginationNumber == $pagination ) ? 'current' : '';
						$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", esc_attr( $active ), esc_html( $pagination ) );
					}

					$setPagination .= "<li class='pagitext dots'>...</li>";
					$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", esc_attr( $active ), esc_html( $last ) );

				} else {
					$active        = '';
					$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", esc_attr( $active ), 1 );
					$setPagination .= "<li class='pagitext dots'>...</li>";
					for ( $pagination = $last - ( 2 + ( $adjacents * 2 ) ); $pagination <= $last; $pagination ++ ) {
						$active        = ( $paginationNumber == $pagination ) ? 'current' : '';
						$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", esc_attr( $active ), esc_html( $pagination ) );
					}
				}

			} else {
				for ( $pagination = 1; $pagination <= $pagination_Paginationlist; $pagination ++ ) {
					$active        = ( $paginationNumber == $pagination ) ? 'current' : '';
					$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", esc_attr( $active ), esc_html( $pagination ) );
				}

			}

			if ( $paginationNumber < $pagination_Paginationlist ) {
				$setPagination .= "<li class='pagitext'><a href='javascript:void(0);' class='page-numbers' data-pnumber='" . esc_attr( $paginationnext ) . "' > " . esc_html( $next_label ) . " </a></li>";
			}

			$setPagination .= "</ul>";
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $setPagination;
		wp_die();
	}


/** Function eael_admin_promotion() called by wp_ajax hooks: {'eael_admin_promotion'} **/
/** No params detected :-/ **/


/** Function select2_ajax_posts_filter_autocomplete() called by wp_ajax hooks: {'eael_select2_search_post'} **/
/** Parameters found in function select2_ajax_posts_filter_autocomplete(): {"post": ["nonce", "post_type", "source_name", "term"]} **/
function select2_ajax_posts_filter_autocomplete() {
		if ( empty( $_POST['nonce'] ) ||  ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'eael_select2' ) ) {
			wp_send_json_error( ['error'=> esc_html__( 'Unauthorized', 'essential-addons-for-elementor-lite' )] );
		}
		$post_type   = 'post';
		$source_name = 'post_type';

		if ( ! empty( $_POST['post_type'] ) ) {
			$post_type = sanitize_text_field( wp_unslash( $_POST['post_type'] ) );
		}

		if ( ! empty( $_POST['source_name'] ) ) {
			$source_name = sanitize_text_field( wp_unslash( $_POST['source_name'] ) );
		}

		$search  = ! empty( $_POST['term'] ) ? sanitize_text_field( wp_unslash( $_POST['term'] ) ) : '';
		$results = $post_list = [];
		switch ( $source_name ) {
			case 'taxonomy':
				$args = [
					'hide_empty' => false,
					'orderby'    => 'name',
					'order'      => 'ASC',
					'search'     => $search
				];

				if ( $post_type !== 'all' ) {
					$args['taxonomy'] = $post_type;
				}

				$post_list = wp_list_pluck( get_terms( $args ), 'name', 'term_id' );
				break;
			case 'user':
				if ( ! current_user_can( 'list_users' ) ) {
					$post_list = [];
					break;
				}

				$users = [];

				foreach ( get_users( [ 'search' => "*{$search}*" ] ) as $user ) {
					$user_id           = $user->ID;
					$user_name         = $user->display_name;
					$users[ $user_id ] = $user_name;
				}

				$post_list = $users;
				break;
			default:
				$post_list = HelperClass::get_query_post_list( $post_type, 10, $search );
		}

		if ( ! empty( $post_list ) ) {
			foreach ( $post_list as $key => $item ) {
				$results[] = [ 'text' => $item, 'id' => $key ];
			}
		}

		wp_send_json( [ 'results' => $results ] );
	}


/** Function ajax_auto_active_even_not_installed() called by wp_ajax hooks: {'wpdeveloper_auto_active_even_not_installed'} **/
/** Parameters found in function ajax_auto_active_even_not_installed(): {"post": ["basename"]} **/
function ajax_auto_active_even_not_installed() {
		check_ajax_referer( 'essential-addons-elementor', 'security' );

		if ( !empty( $_POST['basename'] ) && $this->get_local_plugin_data( sanitize_text_field( wp_unslash( $_POST['basename'] ) ) ) === false ) {
			$this->ajax_install_plugin();
		} else {
			$this->ajax_activate_plugin();
		}
	}


/** Function ajax_eael_product_gallery() called by wp_ajax hooks: {'eael_product_gallery', 'nopriv_eael_product_gallery'} **/
/** Parameters found in function ajax_eael_product_gallery(): {"post": ["args", "nonce", "page_id", "widget_id"], "request": ["page", "taxonomy", "template_info"]} **/
function ajax_eael_product_gallery() {

		$ajax = wp_doing_ajax();

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.NonceVerification.Missing
		wp_parse_str( $_POST['args'], $args );
		$args                = $this->eael_sanitize_query_args( $args );
		$args['post_status'] = 'publish';
		$args['offset']      = $args['offset'] ?? 0;

		if ( isset( $args['date_query']['relation'] ) ) {
			$args['date_query']['relation'] = HelperClass::eael_sanitize_relation( $args['date_query']['relation'] );
		}

		if ( empty( $_POST['nonce'] ) ) {
			$err_msg = __( 'Insecure form submitted without security token', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}

			return false;
		}

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'eael_product_gallery' ) ) {
			$err_msg = __( 'Security token did not match', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}

			return false;
		}

		if ( ! empty( $_POST['page_id'] ) ) {
			$page_id = intval( $_POST['page_id'], 10 );
		} else {
			$err_msg = __( 'Page ID is missing', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}

			return false;
		}

		if ( ! empty( $_POST['widget_id'] ) ) {
			$widget_id = sanitize_text_field( wp_unslash( $_POST['widget_id'] ) );
		} else {
			$err_msg = __( 'Widget ID is missing', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}

			return false;
		}

		$settings = HelperClass::eael_get_widget_settings( $page_id, $widget_id );
		if ( empty( $settings ) ) {
			wp_send_json_error( [ 'message' => __( 'Widget settings are not found. Did you save the widget before using load more??', 'essential-addons-for-elementor-lite' ) ] );
		}

		if ( $widget_id == '' && $page_id == '' ) {
			wp_send_json_error();
		}

		$settings['eael_widget_id'] = $widget_id;
		$settings['eael_page_id']   = $page_id;
		$page = !empty(  $_REQUEST['page'] ) ? absint( $_REQUEST['page'] ) : 1;
		$args['offset']             = (int) $args['offset'] + ( ( (int) $page - 1 ) * (int) $args['posts_per_page'] );

		if ( isset( $_REQUEST['taxonomy'] ) && isset( $_REQUEST['taxonomy']['taxonomy'] ) && $_REQUEST['taxonomy']['taxonomy'] != 'all' ) {
			$args['tax_query'] = [
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				$this->sanitize_taxonomy_data( $_REQUEST['taxonomy'] ),
			];

			$relation = isset( $settings['relation_cats_tags'] ) ? $settings['relation_cats_tags'] : 'OR';
			if ( 'and' === strtolower( $relation ) ) {
				if ( 'product_cat' === $_REQUEST['taxonomy']['taxonomy'] && ! empty( $settings['eael_product_gallery_tags'] ) ) {
					$args['tax_query'][] = [
						'taxonomy' => 'product_tag',
						'field'    => 'term_id',
						'terms'    => $settings['eael_product_gallery_tags'],
						'operator' => 'IN',
					];
				}
				if ( 'product_tag' === $_REQUEST['taxonomy']['taxonomy'] && ! empty( $settings['eael_product_gallery_categories'] ) ) {
					$args['tax_query'][] = [
						'taxonomy' => 'product_cat',
						'field'    => 'term_id',
						'terms'    => $settings['eael_product_gallery_categories'],
						'operator' => 'IN',
					];
				}
			}

			$args['tax_query'] = $this->eael_terms_query_multiple( $args['tax_query'], $relation );

			if ( $settings[ 'eael_product_gallery_product_filter' ] == 'featured-products' ) {
				$args[ 'tax_query' ][] = [
					'relation' => 'AND',
					[
						'taxonomy' => 'product_visibility',
						'field'    => 'name',
						'terms'    => 'featured',
					],
					[
						'taxonomy' => 'product_visibility',
						'field'    => 'name',
						'terms'    => [ 'exclude-from-search', 'exclude-from-catalog' ],
						'operator' => 'NOT IN',
					],
				];
			}


		}

		//phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitize, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, 	WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$template_info = $this->eael_sanitize_template_param( $_REQUEST['template_info'] );

		if ( $template_info ) {

			if ( $template_info['dir'] === 'theme' ) {
				$dir_path = $this->retrive_theme_path();
			} else if ( $template_info['dir'] === 'pro' ) {
				$dir_path = sprintf( "%sincludes", EAEL_PRO_PLUGIN_PATH );
			} else {
				$dir_path = sprintf( "%sincludes", EAEL_PLUGIN_PATH );
			}

			$file_path = realpath( sprintf(
				'%s/Template/%s/%s',
				$dir_path,
				$template_info['name'],
				$template_info['file_name']
			) );

			if ( ! $file_path || 0 !== strpos( $file_path, realpath( $dir_path ) ) ) {
				wp_send_json_error( 'Invalid template', 'invalid_template', '400' );
			}

			$html = '';
			if ( $file_path ) {
				// Convert args to WC_Product_Query format for product gallery
				$wc_args = $this->convert_pagination_args_to_wc_product_query( $args, $settings );
				$wc_query = new \WC_Product_Query( $wc_args );
				$products = $wc_query->get_products();

				// Handle WC_Product_Query results
				if ( is_object( $products ) && isset( $products->products ) ) {
					$product_objects = $products->products;
					$found_posts = $products->total;
					$max_num_pages = $products->max_num_pages;
				} else {
					$product_objects = $products;
					$found_posts = count( $products );
					$max_num_pages = 1;
				}

				if ( ! empty( $product_objects ) ) {

					do_action( 'eael_woo_before_product_loop' );

					// Iterate through WC_Product objects
					foreach ( $product_objects as $product ) {
						global $post;
						$post = get_post( $product->get_id() );
						setup_postdata( $post );
						$html .= HelperClass::include_with_variable( $file_path, [ 'settings' => $settings ] );
					}

					do_action( 'eael_woo_after_product_loop' );

					$html .= '<div class="eael-max-page" style="display:none;">'. $max_num_pages . '</div>';

					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo $html;
					wp_reset_postdata();
				}
			}
		}
		wp_die();
	}


/** Function ajax_deactivate_plugin() called by wp_ajax hooks: {'wpdeveloper_deactivate_plugin'} **/
/** Parameters found in function ajax_deactivate_plugin(): {"post": ["basename"]} **/
function ajax_deactivate_plugin() {
		check_ajax_referer( 'essential-addons-elementor', 'security' );

		//check user capabilities
		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error( __( 'you are not allowed to do this action', 'essential-addons-for-elementor-lite' ) );
		}

		$basename = isset( $_POST['basename'] ) ? sanitize_text_field( wp_unslash( $_POST['basename'] ) ) : '';
		deactivate_plugins( $basename, true );

		wp_send_json_success( __( 'Plugin is deactivated successfully!', 'essential-addons-for-elementor-lite' ) );
	}


/** Function eael_woo_pagination_product_ajax() called by wp_ajax hooks: {'nopriv_woo_product_pagination_product', 'woo_product_pagination_product'} **/
/** Parameters found in function eael_woo_pagination_product_ajax(): {"post": ["page_id", "widget_id", "number", "limit"], "request": ["args", "templateInfo"]} **/
function eael_woo_pagination_product_ajax() {

		check_ajax_referer( 'essential-addons-elementor', 'security' );

		do_action( 'eael_before_woo_pagination_product_ajax_start', $_REQUEST );

		if ( ! empty( $_POST['page_id'] ) ) {
			$page_id = intval( $_POST['page_id'], 10 );
		} else {
			$err_msg = __( 'Page ID is missing', 'essential-addons-for-elementor-lite' );
			wp_send_json_error( $err_msg );
		}

		if ( ! empty( $_POST['widget_id'] ) ) {
			$widget_id = sanitize_text_field( wp_unslash( $_POST['widget_id'] ) );
		} else {
			$err_msg = __( 'Widget ID is missing', 'essential-addons-for-elementor-lite' );
			wp_send_json_error( $err_msg );
		}

		$settings = HelperClass::eael_get_widget_settings( $page_id, $widget_id );
		if ( empty( $settings ) ) {
			wp_send_json_error( [ 'message' => __( 'Widget settings are not found. Did you save the widget before using load more??', 'essential-addons-for-elementor-lite' ) ] );
		}
		$settings['eael_page_id']   = $page_id;
		$settings['eael_widget_id'] = $widget_id;
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		wp_parse_str( $_REQUEST['args'], $args );
		$args = $this->eael_sanitize_query_args( $args );

		// Convert WP_Query args to WC_Product_Query args if needed
		$wc_args = $this->convert_pagination_args_to_wc_product_query( $args, $settings );

		if ( isset( $wc_args['date_query']['relation'] ) ) {
			$wc_args['date_query']['relation'] = HelperClass::eael_sanitize_relation( $wc_args['date_query']['relation'] );
		}

		$paginationNumber = ! empty( $_POST['number'] ) ? absint( $_POST['number'] ) : 1;
		$paginationLimit  = ! empty( $_POST['limit'] ) ? absint( $_POST['limit'] ) : 10;

		$wc_args['limit'] = $paginationLimit;
		$wc_args['page'] = $paginationNumber;

		// Calculate offset for WC_Product_Query
		if ( $paginationNumber == "1" ) {
			$paginationOffsetValue = "0";
		} else {
			$paginationOffsetValue = ( $paginationNumber - 1 ) * $paginationLimit;
			$wc_args['offset'] = $paginationOffsetValue;
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		$template_info = $this->eael_sanitize_template_param( $_REQUEST['templateInfo'] );

		$this->set_widget_name( $template_info['name'] );
		$template = realpath( $this->get_template( $template_info['file_name'] ) );

		ob_start();

		// Use WC_Product_Query for product queries
		$wc_query = new \WC_Product_Query( $wc_args );
		$products = $wc_query->get_products();

		// Handle WC_Product_Query results
		if ( is_object( $products ) && isset( $products->products ) ) {
			$product_objects = $products->products;
		} else {
			$product_objects = $products;
		}

		if ( ! empty( $product_objects ) ) {
			if ( isset( $template_info['name'] ) && $template_info['name'] === 'eicon-woocommerce' && boolval( $settings['show_add_to_cart_custom_text'] ) ){
				$add_to_cart_text = [
					'add_to_cart_simple_product_button_text'   => $settings['add_to_cart_simple_product_button_text'],
					'add_to_cart_variable_product_button_text' => $settings['add_to_cart_variable_product_button_text'],
					'add_to_cart_grouped_product_button_text'  => $settings['add_to_cart_grouped_product_button_text'],
					'add_to_cart_external_product_button_text' => $settings['add_to_cart_external_product_button_text'],
					'add_to_cart_default_product_button_text'  => $settings['add_to_cart_default_product_button_text'],
				];
				$this->change_add_woo_checkout_update_order_reviewto_cart_text( $add_to_cart_text );
			}

			// Iterate through WC_Product objects
			foreach ( $product_objects as $product ) {
				global $post;
				$post = get_post( $product->get_id() );
				setup_postdata( $post );
				include( $template );
			}
			wp_reset_postdata();
		}
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo ob_get_clean();
		wp_die();
	}


/** Function ajax_install_plugin() called by wp_ajax hooks: {'wpdeveloper_install_plugin'} **/
/** Parameters found in function ajax_install_plugin(): {"post": ["slug", "promotype"]} **/
function ajax_install_plugin()
    {
        check_ajax_referer('essential-addons-elementor', 'security');

        if(!current_user_can( 'install_plugins' )) {
            wp_send_json_error(__('you are not allowed to do this action', 'essential-addons-for-elementor-lite'));
        }

	    $slug   = isset( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';
	    $result = $this->install_plugin( $slug );

        if ( isset( $_POST['promotype'], $_POST['slug'] ) ) {
            $promotype = sanitize_text_field( wp_unslash( $_POST['promotype'] ) );
            $slug      = sanitize_text_field( wp_unslash( $_POST['slug'] ) );
        
            $remote_urls = [
                'quick-setup' => [
                    'essential-blocks' => 'https://essential-addons.com/essential-blocks-install-quick-setup',
                    'templately'       => 'https://essential-addons.com/templately-install-quick-setup',
                ]
            ];
        
            if ( isset( $remote_urls[ $promotype ][ $slug ] ) ) {
                wp_remote_get( $remote_urls[ $promotype ][ $slug ] );
            }

			// ThinkRank schedules a one-time redirect to its own setup wizard on
			// activation. When installed from Quick Setup the user must stay in
			// EA's wizard, so consume that flag before it can hijack the next
			// admin page load.
			if ( 'quick-setup' === $promotype && 'thinkrank' === $slug && ! is_wp_error( $result ) ) {
				delete_transient( 'thinkrank_setup_wizard_redirect' );
			}
        }

	    if ( is_wp_error( $result ) ) {
		    wp_send_json_error( $result->get_error_message() );
	    }

        wp_send_json_success(__('Plugin is installed successfully!', 'essential-addons-for-elementor-lite'));
    }


/** Function select2_ajax_get_posts_value_titles() called by wp_ajax hooks: {'eael_select2_get_title'} **/
/** Parameters found in function select2_ajax_get_posts_value_titles(): {"post": ["nonce", "id", "source_name", "post_type"]} **/
function select2_ajax_get_posts_value_titles() {

		if ( empty( $_POST['nonce'] ) ||  ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'eael_select2' ) ) {
			wp_send_json_error( ['error'=> esc_html__( 'Unauthorized', 'essential-addons-for-elementor-lite' )] );
		}

		if ( empty( $_POST['id'] ) ) {
			wp_send_json_error( [] );
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
		if ( empty( array_filter( $_POST['id'] ) ) ) {
			wp_send_json_error( [] );
		}
		$ids         = array_map( 'intval', $_POST['id'] );
		$source_name = ! empty( $_POST['source_name'] ) ? sanitize_text_field( wp_unslash( $_POST['source_name'] ) ) : '';

		switch ( $source_name ) {
			case 'taxonomy':
				$args = [
					'hide_empty' => false,
					'orderby'    => 'name',
					'order'      => 'ASC',
					'include'    => implode( ',', $ids ),
				];

				if ( ! empty( $_POST['post_type'] ) && $_POST['post_type'] !== 'all' ) {
					$args['taxonomy'] = sanitize_text_field( wp_unslash( $_POST['post_type'] ) );
				}

				$response = wp_list_pluck( get_terms( $args ), 'name', 'term_id' );
				break;
			case 'user':
				$users = [];

				foreach ( get_users( [ 'include' => $ids ] ) as $user ) {
					$user_id           = $user->ID;
					$user_name         = $user->display_name;
					$users[ $user_id ] = $user_name;
				}

				$response = $users;
				break;
			default:
				$post_info = get_posts( [
					'post_type' => ! empty( $_POST['post_type'] ) ? sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) : 'post',
					'include'   => implode( ',', $ids )
				] );
				$response  = wp_list_pluck( $post_info, 'post_title', 'ID' );
		}

		if ( ! empty( $response ) ) {
			wp_send_json_success( [ 'results' => $response ] );
		} else {
			wp_send_json_error( [] );
		}
	}


/** Function eael_ajax_send_otp() called by wp_ajax hooks: {'nopriv_eael_lr_send_otp', 'eael_lr_send_otp'} **/
/** Parameters found in function eael_ajax_send_otp(): {"post": ["otp_token"]} **/
function eael_ajax_send_otp() {
		check_ajax_referer( 'eael_lr_otp', '_eael_otp_nonce' );

		$token = ! empty( $_POST['otp_token'] ) ? sanitize_text_field( wp_unslash( $_POST['otp_token'] ) ) : '';
		if ( empty( $token ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid OTP session.', 'essential-addons-for-elementor-lite' ) ] );
		}

		$session = get_transient( self::$otp_transient_prefix . $token );
		if ( empty( $session ) || ! is_array( $session ) ) {
			wp_send_json_error( [ 'message' => __( 'Your verification session has expired. Please start over.', 'essential-addons-for-elementor-lite' ) ] );
		}

		$elapsed = time() - (int) $session['last_sent'];
		if ( $elapsed < (int) $session['cooldown'] ) {
			wp_send_json_error( [
				'message'   => sprintf(
					/* translators: %d seconds remaining */
					__( 'Please wait %d seconds before requesting a new code.', 'essential-addons-for-elementor-lite' ),
					(int) $session['cooldown'] - $elapsed
				),
				'cooldown' => (int) $session['cooldown'] - $elapsed,
			] );
		}

		$settings = $this->lr_get_widget_settings( (int) $session['page_id'], $session['widget_id'] );
		if ( empty( $settings ) ) {
			wp_send_json_error( [ 'message' => __( 'Widget settings could not be loaded.', 'essential-addons-for-elementor-lite' ) ] );
		}

		$code                  = $this->eael_otp_generate_code();
		$session['code_hash']  = $this->eael_otp_hash_code( $code, $token );
		$session['last_sent']  = time();
		$session['attempts']   = 0;

		set_transient( self::$otp_transient_prefix . $token, $session, (int) $session['expiry'] );

		$direct_url = ! empty( $session['direct_url'] ) ? $session['direct_url'] : '';
		$sent       = $this->eael_otp_send_email( $settings, $session['email'], $session['flow'], $code, $direct_url );

		if ( ! $sent ) {
			wp_send_json_error( [ 'message' => __( 'We could not send the verification email. Please try again later.', 'essential-addons-for-elementor-lite' ) ] );
		}

		wp_send_json_success( [
			'message'  => __( 'A new verification code has been sent.', 'essential-addons-for-elementor-lite' ),
			'cooldown' => (int) $session['cooldown'],
		] );
	}


/** Function eael_clear_widget_cache_data() called by wp_ajax hooks: {'eael_clear_widget_cache_data'} **/
/** Parameters found in function eael_clear_widget_cache_data(): {"post": ["ac_name", "hastag", "c_key", "c_secret", "widget_id", "page_permalink"]} **/
function eael_clear_widget_cache_data() {
		global $wpdb;

		check_ajax_referer( 'essential-addons-elementor', 'security' );

		$ac_name     = ! empty( $_POST['ac_name'] ) ? sanitize_text_field( wp_unslash( $_POST['ac_name'] ) ) : '';
		$hastag      = ! empty( $_POST['hastag'] ) ? sanitize_text_field( wp_unslash( $_POST['hastag'] ) ) : '';
		$c_key       = ! empty( $_POST['c_key'] ) ? sanitize_text_field( wp_unslash( $_POST['c_key'] ) ) : '';
		$c_secret    = ! empty( $_POST['c_secret'] ) ? sanitize_text_field( wp_unslash( $_POST['c_secret'] ) ) : '';
		$widget_id   = ! empty( $_POST['widget_id'] ) ? sanitize_text_field( wp_unslash( $_POST['widget_id'] ) ) : '';
		$permalink   = ! empty( $_POST['page_permalink'] ) ?  sanitize_text_field( wp_unslash( $_POST['page_permalink'] ) ) : '';
        $page_id     = url_to_postid($permalink);
        
        $settings = $this->eael_get_widget_settings($page_id, $widget_id);
        $twitter_v2 = ! empty( $settings['eael_twitter_api_v2'] ) && 'yes' === $settings['eael_twitter_api_v2'] ? true : false;

		$key_pattern = '_transient_' . $ac_name . '%' . md5( $hastag . $c_key . $c_secret ) . '_tf_cache';
        
        if( $twitter_v2 ){
            $bearer_token = $settings['eael_twitter_feed_bearer_token'];
            $key_pattern = '_transient_' . $ac_name . '%' . md5( $hastag . $c_key . $c_secret . $bearer_token ) . '_tf_cache';
        }

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$results = $wpdb->get_results( $wpdb->prepare(
			"SELECT `option_name` AS `name`
			FROM {$wpdb->options}
			WHERE `option_name` LIKE %s
			ORDER BY `option_name`",
			$key_pattern
		) );

		foreach ( $results as $transient ) {
			$cache_key = substr( $transient->name, 11 );
			delete_transient( $cache_key );
		}

		wp_send_json_success();
	}


/** Function eael_get_token() called by wp_ajax hooks: {'eael_get_token', 'nopriv_eael_get_token'} **/
/** No params detected :-/ **/


/** Function ajax_activate_plugin() called by wp_ajax hooks: {'wpdeveloper_activate_plugin'} **/
/** Parameters found in function ajax_activate_plugin(): {"post": ["basename"]} **/
function ajax_activate_plugin()
    {
        check_ajax_referer('essential-addons-elementor', 'security');

        //check user capabilities
        if(!current_user_can( 'activate_plugins' )) {
            wp_send_json_error(__('you are not allowed to do this action', 'essential-addons-for-elementor-lite'));
        }

	    $basename = isset( $_POST['basename'] ) ? sanitize_text_field( wp_unslash( $_POST['basename'] ) ) : '';
	    // Not silent — see install_plugin(): a silent activation never fires the
	    // plugin's own activation hook.
	    $result   = activate_plugin( $basename, '', false, false );

	    if ( is_wp_error( $result ) ) {
		    wp_send_json_error( $result->get_error_message() );
	    }

        if ($result === false) {
            wp_send_json_error(__('Plugin couldn\'t be activated.', 'essential-addons-for-elementor-lite'));
        }
        wp_send_json_success(__('Plugin is activated successfully!', 'essential-addons-for-elementor-lite'));
    }


/** Function ajax_load_more() called by wp_ajax hooks: {'load_more', 'nopriv_load_more'} **/
/** Parameters found in function ajax_load_more(): {"post": ["args", "nonce", "page_id", "widget_id", "exclude_ids", "active_term_id", "active_taxonomy"], "request": ["class", "page", "taxonomy", "post__not_in", "template_info"]} **/
function ajax_load_more() {
		$ajax = wp_doing_ajax();
		//phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
		do_action( 'eael_before_ajax_load_more', $_REQUEST );

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.NonceVerification.Missing
		wp_parse_str( $_POST['args'], $args );

		// $args comes from the client; coerce ID-list query vars (author__not_in, post__in, …)
		// to int arrays before they reach WP_Query, else a string payload injects raw SQL.
		$args = $this->eael_sanitize_query_args( $args );

		// NOTE: $args comes from client ($_POST['args']); strip visibility-widening keys (e.g., post_status, perm) to prevent nopriv access to non-public content.
		unset( $args['post_status'], $args['perm'], $args['suppress_filters'] );
		$args['post_status'] = 'publish';

		if ( isset( $args['post__in'] ) ) {
			// Cap to a sane bound to prevent abuse.
			$args['post__in'] = array_slice( $args['post__in'], 0, 1000 );
		}
		if ( isset( $args['post__not_in'] ) ) {
			$args['post__not_in'] = array_slice( $args['post__not_in'], 0, 1000 );
		}

		if ( isset( $args['date_query']['relation'] ) ) {
			$args['date_query']['relation'] = HelperClass::eael_sanitize_relation( $args['date_query']['relation'] );
		}

		if ( empty( $_POST['nonce'] ) ) {
			$err_msg = __( 'Insecure form submitted without security token', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}

			return false;
		}

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'load_more' ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'essential-addons-elementor' ) ) {
			$err_msg = __( 'Security token did not match', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}

			return false;
		}

		if ( ! empty( $_POST['page_id'] ) ) {
			$page_id = intval( $_POST['page_id'], 10 );
		} else {
			$err_msg = __( 'Page ID is missing', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}

			return false;
		}

		if ( ! empty( $_POST['widget_id'] ) ) {
			$widget_id = sanitize_text_field( wp_unslash( $_POST['widget_id'] ) );
		} else {
			$err_msg = __( 'Widget ID is missing', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}

			return false;
		}

		$settings = HelperClass::eael_get_widget_settings( $page_id, $widget_id );

		if ( empty( $settings ) ) {
			wp_send_json_error( [ 'message' => __( 'Widget settings are not found. Did you save the widget before using load more??', 'essential-addons-for-elementor-lite' ) ] );
		}

		$settings['eael_widget_id'] = $widget_id;
		$settings['eael_page_id']   = $page_id;
		$html                       = '';
		$class                      = !empty( $_REQUEST['class'] ) ? '\\' . str_replace( '\\\\', '\\', sanitize_text_field( wp_unslash( $_REQUEST['class'] ) ) ) : '';
		$page = !empty(  $_REQUEST['page'] ) ? absint( $_REQUEST['page'] ) : 1;
		$args['offset']             = (int) $args['offset'] + ( ( (int) $page - 1 ) * (int) $args['posts_per_page'] );

		if ( isset( $_REQUEST['taxonomy'] ) && isset( $_REQUEST['taxonomy']['taxonomy'] ) && $_REQUEST['taxonomy']['taxonomy'] != 'all' ) {
			$args['tax_query'] = [
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotValidated
				$this->sanitize_taxonomy_data( $_REQUEST['taxonomy'] ),
			];

			$relation = isset( $settings['relation_cats_tags'] ) ? $settings['relation_cats_tags'] : 'OR';
			$args['tax_query'] = $this->eael_terms_query_multiple( $args['tax_query'], $relation );
		}

		if ( $class == '\Essential_Addons_Elementor\Elements\Post_Grid' ) {
			$eael_lang_suffix = HelperClass::eael_lang_suffix( $page_id );
			$settings['read_more_button_text']       = get_transient( 'eael_post_grid_read_more_button_text_' . $widget_id . $eael_lang_suffix );
			$settings['excerpt_expanison_indicator'] = get_transient( 'eael_post_grid_excerpt_expanison_indicator_' . $widget_id . $eael_lang_suffix );

			if ( $settings['orderby'] === 'rand' && ! empty( $_REQUEST['post__not_in'] ) ) {
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				$post__not_in = $_REQUEST['post__not_in'];
				if ( ! empty( $args['post__not_in'] ) ) {
					$post__not_in = array_merge( $post__not_in, $args['post__not_in'] );
				}
				$args['post__not_in'] = array_map( 'intval', array_unique( $post__not_in ) );
				unset( $args['offset'] );
			}
		}
		if ( $class === '\Essential_Addons_Elementor\Elements\Product_Grid' ) {
			do_action( 'eael_woo_before_product_loop', $settings['eael_product_grid_style_preset'] );
		}
		if ( $class === '\Essential_Addons_Elementor\Elements\Woo_Product_List' ) {
			do_action( 'eael/woo-product-list/before-product-loop' );
		}
		// ensure control name compatibility to old code if it is post block
		if ( $class === '\Essential_Addons_Elementor\Pro\Elements\Post_Block' ) {
			$settings ['post_block_hover_animation']    = $settings['eael_post_block_hover_animation'];
			$settings ['show_read_more_button']         = $settings['eael_show_read_more_button'];
			$settings ['eael_post_block_bg_hover_icon'] = ( isset( $settings['__fa4_migrated']['eael_post_block_bg_hover_icon_new'] ) || empty( $settings['eael_post_block_bg_hover_icon'] ) ) ? $settings['eael_post_block_bg_hover_icon_new']['value'] : $settings['eael_post_block_bg_hover_icon'];
			$settings ['expanison_indicator']           = $settings['excerpt_expanison_indicator'];
		}
		if ( $class === '\Essential_Addons_Elementor\Elements\Post_Timeline' ) {
			$settings ['expanison_indicator'] = $settings['excerpt_expanison_indicator'];
		}
		if ( $class === '\Essential_Addons_Elementor\Pro\Elements\Dynamic_Filterable_Gallery' ) {
			$settings['eael_section_fg_zoom_icon'] = ( isset( $settings['__fa4_migrated']['eael_section_fg_zoom_icon_new'] ) || empty( $settings['eael_section_fg_zoom_icon'] ) ? $settings['eael_section_fg_zoom_icon_new']['value'] : $settings['eael_section_fg_zoom_icon'] );
			$settings['eael_section_fg_link_icon'] = ( isset( $settings['__fa4_migrated']['eael_section_fg_link_icon_new'] ) || empty( $settings['eael_section_fg_link_icon'] ) ? $settings['eael_section_fg_link_icon_new']['value'] : $settings['eael_section_fg_link_icon'] );
			$settings['show_load_more_text']       = $settings['eael_fg_loadmore_btn_text'];
			$settings['layout_mode']               = isset( $settings['layout_mode'] ) ? $settings['layout_mode'] : 'masonry';

			// NOTE: Use server-trusted whitelists; ACF gallery needs broader post_status/type for attachments (inherit), but never 'any'—it would expose private/draft posts to unauthenticated users.
			//NOTE: The inherit status is intentionally allowed to ensure access to attachments, since the same attachment may be associated with both private and public pages.
			$dfg_safe_post_status = [ 'publish', 'inherit' ];
			$dfg_safe_post_types = [ 'attachment' ];
			if ( ! empty( $settings['post_type'] ) && is_string( $settings['post_type'] ) ) {
				if ( 'by_id' === $settings['post_type'] ) {
					// NOTE: Manual selection allows all public post types (to include ACF gallery parents) but excludes internal types (e.g., revision, oembed_cache, nav_menu_item) that 'any' would expose.
					$dfg_safe_post_types = array_values( array_unique( array_merge(
						$dfg_safe_post_types,
						(array) get_post_types( [ 'public' => true ] )
					) ) );
				} else {
					$dfg_safe_post_types[] = sanitize_key( $settings['post_type'] );
				}
			}

			if ( ! empty( $args['fetch_acf_image'] ) && 'yes' === $args['fetch_acf_image'] && ! empty( $args['post__in'] ) ) {
				// SECURITY: previously set to 'any'/'any' which let unauthenticated
				// callers read drafts/private posts by passing arbitrary post__in IDs.
				$args['post_status'] = $dfg_safe_post_status;
				$args['post_type']   = $dfg_safe_post_types;
			}

			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$exclude_ids = json_decode( html_entity_decode( stripslashes ( $_POST['exclude_ids'] ) ) );
			$args['post__not_in'] = ( !empty( $_POST['exclude_ids'] ) ) ? array_map( 'intval', array_unique( (array) $exclude_ids ) ) : array();
			$active_term_id = ( !empty( $_POST['active_term_id'] ) ) ? intval( $_POST['active_term_id'] ) : 0;
			$active_taxonomy = ( !empty( $_POST['active_taxonomy'] ) ) ? sanitize_text_field( wp_unslash( $_POST['active_taxonomy'] ) ) : '';

			// Check if this is a hybrid/combined query with ACF gallery
			// Also check settings for hybrid query flag as backup (in case args encoding failed)
			$is_hybrid_query = ( ! empty( $args['eael_dfg_enable_combined_query'] ) && 'yes' === $args['eael_dfg_enable_combined_query'] )
				|| ( ! empty( $settings['eael_dfg_enable_combined_query'] ) && 'yes' === $settings['eael_dfg_enable_combined_query'] && 'yes' === $settings['fetch_acf_image_gallery'] );

			if ( $is_hybrid_query && class_exists( 'ACF' ) && ! empty( $settings['eael_acf_gallery_keys'] ) ) {
				// Build taxonomy map for ACF gallery attachments
				$taxonomy_map = $this->build_dfg_acf_taxonomy_map( $args, $settings, $active_term_id, $active_taxonomy );

				// Store globally for templates
				global $eael_dfg_attachment_taxonomy_map;
				$eael_dfg_attachment_taxonomy_map = $taxonomy_map['taxonomy_map'];

				// Update args with the filtered post IDs
				if ( ! empty( $taxonomy_map['post_ids'] ) ) {
					// post_ids here are server-derived (parent post IDs + their ACF
					// attachment IDs from build_dfg_acf_taxonomy_map), so they are
					// trustworthy. Still constrain post_type/post_status to the
					// whitelist so a stale entry can't leak non-public content.
					$args['post__in']    = $taxonomy_map['post_ids'];
					$args['post_type']   = $dfg_safe_post_types;
					$args['post_status'] = $dfg_safe_post_status;
					$args['tax_query']   = [];
					$args['orderby']     = 'post__in';
				}

				// Apply exclusions
				if ( ! empty( $args['post__not_in'] ) && ! empty( $args['post__in'] ) ) {
					$args['post__in'] = array_values( array_diff( $args['post__in'], array_unique( $args['post__not_in'] ) ) );
				}
			} else {
				// Standard ACF gallery handling (non-hybrid)
				if ( ! empty( $args['fetch_acf_image'] ) && 'yes' === $args['fetch_acf_image'] && ! empty( $args['post__in'] ) ) {
					// SECURITY: same rationale as above — never use 'any'.
					$args['post_status'] = $dfg_safe_post_status;
					$args['post_type']   = $dfg_safe_post_types;
				}

				if ( ! empty( $args['post__not_in'] ) && ! empty( $args['post__in'] ) ) {
					$args['post__in'] = array_diff( $args['post__in'], array_unique( $args['post__not_in'] ) );
				}

				if( 0 < $active_term_id &&
					!empty( $active_taxonomy ) &&
					!empty($args['tax_query'])
				) {
					foreach ($args['tax_query'] as $key => $taxonomy) {
						if (isset($taxonomy['taxonomy']) && $taxonomy['taxonomy'] === $active_taxonomy) {
							$args['tax_query'][$key]['terms'] = [$active_term_id];
						}
					}
				}
			}
		}

		$link_settings = [
			'image_link_nofollow'         => ! empty( $settings['image_link_nofollow'] ) ? 'rel="nofollow"' : '',
			'image_link_target_blank'     => ! empty( $settings['image_link_target_blank'] ) ? 'target="_blank"' : '',
			'title_link_nofollow'         => ! empty( $settings['title_link_nofollow'] ) ? 'rel="nofollow"' : '',
			'title_link_target_blank'     => ! empty( $settings['title_link_target_blank'] ) ? 'target="_blank"' : '',
			'read_more_link_nofollow'     => ! empty( $settings['read_more_link_nofollow'] ) ? 'rel="nofollow"' : '',
			'read_more_link_target_blank' => ! empty( $settings['read_more_link_target_blank'] ) ? 'target="_blank"' : '',
		];

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		$template_info = $this->eael_sanitize_template_param( $_REQUEST['template_info'] );

		if ( $template_info ) {

			if ( $template_info['dir'] === 'theme' ) {
				$dir_path = $this->retrive_theme_path();
			} else if ( $template_info['dir'] === 'pro' ) {
				$dir_path = sprintf( "%sincludes", EAEL_PRO_PLUGIN_PATH );
			} else {
				$dir_path = sprintf( "%sincludes", EAEL_PLUGIN_PATH );
			}

			$file_path = realpath( sprintf(
				'%s/Template/%s/%s',
				$dir_path,
				$template_info['name'],
				$template_info['file_name']
			) );

			if ( ! $file_path || 0 !== strpos( $file_path, realpath( $dir_path ) ) ) {
				wp_send_json_error( 'Invalid template', 'invalid_template', 400 );
			}

			if ( $file_path ) {
				// wp_send_json( $args );
				$query = new \WP_Query( $args );
				$found_posts = $query->found_posts;
				$iterator = 0;

				if ( $query->have_posts() ) {
					if ( $class === '\Essential_Addons_Elementor\Elements\Product_Grid' && boolval( $settings['show_add_to_cart_custom_text'] ) ) {

						$add_to_cart_text = [
							'add_to_cart_simple_product_button_text'   => $settings['add_to_cart_simple_product_button_text'],
							'add_to_cart_variable_product_button_text' => $settings['add_to_cart_variable_product_button_text'],
							'add_to_cart_grouped_product_button_text'  => $settings['add_to_cart_grouped_product_button_text'],
							'add_to_cart_external_product_button_text' => $settings['add_to_cart_external_product_button_text'],
							'add_to_cart_default_product_button_text'  => $settings['add_to_cart_default_product_button_text'],
						];
						$this->change_add_woo_checkout_update_order_reviewto_cart_text( $add_to_cart_text );
					}

					// Handle custom add to cart text for Woo_Product_List
					if ( $class === '\Essential_Addons_Elementor\Elements\Woo_Product_List' && boolval( $settings['eael_product_list_content_footer_add_to_cart_custom_text_show'] ) ) {
						$add_to_cart_text = [
							'add_to_cart_simple_product_button_text'   => $settings['eael_product_list_content_footer_add_to_cart_simple_text'],
							'add_to_cart_variable_product_button_text' => $settings['eael_product_list_content_footer_add_to_cart_variable_text'],
							'add_to_cart_grouped_product_button_text'  => $settings['eael_product_list_content_footer_add_to_cart_grouped_text'],
							'add_to_cart_external_product_button_text' => $settings['eael_product_list_content_footer_add_to_cart_external_text'],
							'add_to_cart_default_product_button_text'  => $settings['eael_product_list_content_footer_add_to_cart_default_text'],
						];
						$this->change_add_woo_checkout_update_order_reviewto_cart_text( $add_to_cart_text );
					}

					if ( $class === '\Essential_Addons_Elementor\Pro\Elements\Dynamic_Filterable_Gallery' ) {
						$html .= "<div class='found_posts' style='display: none;'>{$found_posts}</div>";
					}

					while ( $query->have_posts() ) {
						$query->the_post();

						$html .= HelperClass::include_with_variable( $file_path, [
							'settings'      => $settings,
							'link_settings' => $link_settings,
							'iterator'      => $iterator
						] );
						$iterator ++;
					}
				} else {
					$html .= '<p class="no-posts-found">' . esc_html__( 'No posts found!', 'essential-addons-for-elementor-lite' ) . '</p>';
				}
			}
		}

		if ( $class === '\Essential_Addons_Elementor\Elements\Product_Grid' ) {
			do_action( 'eael_woo_after_product_loop', $settings['eael_product_grid_style_preset'] );
		}
		if ( $class === '\Essential_Addons_Elementor\Elements\Woo_Product_List' ) {
			do_action( 'eael/woo-product-list/after-product-loop' );
		}
		while ( ob_get_status() ) {
			ob_end_clean();
		}
		if ( function_exists( 'gzencode' ) ) {
			$response = gzencode( wp_json_encode( $html ) );

			header( 'Content-Type: application/json; charset=utf-8' );
			header( 'Content-Encoding: gzip' );
			header( 'Content-Length: ' . strlen( $response ) );

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $response;
		} else {
			echo wp_kses_post( $html );
		}
		wp_die();
	}


/** Function enable_wpins_process() called by wp_ajax hooks: {'enable_wpins_process'} **/
/** Parameters found in function enable_wpins_process(): {"post": ["fields"]} **/
function enable_wpins_process() {

		check_ajax_referer( 'essential-addons-elementor', 'security' );

		if ( !current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'you are not allowed to do this action', 'essential-addons-for-elementor-lite' ) );
		}

		if ( !isset( $_POST[ 'fields' ] ) ) {
			return;
		}

		wp_parse_str( $_POST[ 'fields' ], $fields ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash

		$this->wpins_process();

		wp_send_json_success();
	}


/** Function ajax_snooze_banner() called by wp_ajax hooks: {'eael_thinkrank_snooze'} **/
/** No params detected :-/ **/


/** Function eael_ajax_add_to_cart() called by wp_ajax hooks: {'nopriv_eael_ajax_add_to_cart', 'eael_ajax_add_to_cart'} **/
/** Parameters found in function eael_ajax_add_to_cart(): {"post": ["product_id", "product_type", "quantity", "variation_id"]} **/
function eael_ajax_add_to_cart() {
		check_ajax_referer( 'eael-ajax-add-to-cart', 'nonce' );

		if ( ! function_exists( 'WC' ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'WooCommerce is not active.', 'essential-addons-for-elementor-lite' ) ] );
		}

		// phpcs:disable WordPress.Security.NonceVerification.Missing -- nonce already checked above
		$product_id   = isset( $_POST['product_id'] )   ? absint( $_POST['product_id'] )   : 0;
		$product_type = isset( $_POST['product_type'] ) ? sanitize_key( $_POST['product_type'] ) : 'simple';
		// phpcs:enable

		if ( ! $product_id ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid product.', 'essential-addons-for-elementor-lite' ) ] );
		}

		$added = false;

		if ( 'grouped' === $product_type ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			$quantities = isset( $_POST['quantity'] ) && is_array( $_POST['quantity'] ) ? $_POST['quantity'] : [];
			foreach ( $quantities as $child_id => $qty ) {
				$child_id = absint( $child_id );
				$qty      = absint( $qty );
				if ( $child_id > 0 && $qty > 0 ) {
					WC()->cart->add_to_cart( $child_id, $qty );
					$added = true;
				}
			}
		} elseif ( 'variable' === $product_type ) {
			// phpcs:disable WordPress.Security.NonceVerification.Missing
			$variation_id = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
			$quantity     = isset( $_POST['quantity'] )     ? absint( $_POST['quantity'] )     : 1;
			$attributes   = [];
			foreach ( $_POST as $key => $value ) {
				if ( strpos( $key, 'attribute_' ) === 0 ) {
					$attributes[ sanitize_key( $key ) ] = sanitize_text_field( wp_unslash( $value ) );
				}
			}
			// phpcs:enable
			if ( $variation_id ) {
				$added = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $attributes );
			}
		} else {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			$quantity = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;
			$added    = WC()->cart->add_to_cart( $product_id, $quantity );
		}

		if ( false === $added ) {
			ob_start();
			wc_print_notices();
			$notices_html = ob_get_clean();
			wp_send_json_error( [ 'notices' => $notices_html ] );
		}

		WC()->cart->calculate_totals();
		$fragments = apply_filters( 'woocommerce_add_to_cart_fragments', [] );

		ob_start();
		wc_print_notices();
		$notices_html = ob_get_clean();

		wp_send_json_success( [
			'fragments'  => $fragments,
			'cart_hash'  => WC()->cart->get_cart_hash(),
			'cart_count' => WC()->cart->get_cart_contents_count(),
			'notices'    => $notices_html,
		] );
	}


/** Function eael_product_quickview_popup() called by wp_ajax hooks: {'eael_product_quickview_popup', 'nopriv_eael_product_quickview_popup'} **/
/** Parameters found in function eael_product_quickview_popup(): {"post": ["widget_id", "product_id", "page_id"]} **/
function eael_product_quickview_popup() {
		//check nonce
		check_ajax_referer( 'essential-addons-elementor', 'security' );
		$widget_id  = ! empty( $_POST['widget_id'] ) ? sanitize_key( wp_unslash( $_POST['widget_id'] ) ) : '';
		$product_id = ! empty( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
		$page_id    = ! empty( $_POST['page_id'] ) ? absint( $_POST['page_id'] ) : 0;

		if ( $widget_id == '' && $product_id == '' && $page_id == '' ) {
			wp_send_json_error();
		}

		global $post, $product;
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
		$product = wc_get_product( $product_id );
		$post    = get_post( $product_id );

		// SECURITY FIX: Verify product exists and is visible
		if ( ! $product || ! $product->is_visible() ) {
			wp_send_json_error( __( 'Product not found or not accessible', 'essential-addons-for-elementor-lite' ) );
		}

		// Also verify post status for non-admin users
		$post = get_post( $product_id );
		if ( ! current_user_can( 'edit_post', $product_id ) && $post->post_status !== 'publish' ) {
			wp_send_json_error( __( 'Product not found or not accessible', 'essential-addons-for-elementor-lite' ) );
		}

		// Block password-protected products for users who cannot edit them
		if ( ! current_user_can( 'edit_post', $product_id ) && post_password_required( $post ) ) {
			wp_send_json_error( __( 'Product not found or not accessible', 'essential-addons-for-elementor-lite' ) );
		}

		setup_postdata( $post );

		$settings = $this->eael_get_widget_settings( $page_id, $widget_id );
		ob_start();
		HelperClass::eael_product_quick_view( $product, $settings, $widget_id );
		$data = ob_get_clean();
		wp_reset_postdata();

		wp_send_json_success( $data );
	}


/** Function ajax_upgrade_plugin() called by wp_ajax hooks: {'wpdeveloper_upgrade_plugin'} **/
/** Parameters found in function ajax_upgrade_plugin(): {"post": ["basename"]} **/
function ajax_upgrade_plugin()
    {
        check_ajax_referer('essential-addons-elementor', 'security');
        //check user capabilities
        if(!current_user_can( 'update_plugins' )) {
            wp_send_json_error(__('you are not allowed to do this action', 'essential-addons-for-elementor-lite'));
        }

	    $basename = isset( $_POST['basename'] ) ? sanitize_text_field( wp_unslash( $_POST['basename'] ) ) : '';
	    $result   = $this->upgrade_plugin( $basename );

        if (is_wp_error($result)) {
            wp_send_json_error($result->get_error_message());
        }

        wp_send_json_success(__('Plugin is updated successfully!', 'essential-addons-for-elementor-lite'));
    }


