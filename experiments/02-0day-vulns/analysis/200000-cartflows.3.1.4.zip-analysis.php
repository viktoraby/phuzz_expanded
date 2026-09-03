<?php
/***
*
*Found actions: 28
*Found functions:24
*Extracted functions:23
*Total parameter names extracted: 17
*Overview: {'ajax_save_course_template': {'cartflows_save_tutor_course_template'}, 'woocommerce_user_login': {'nopriv_wcf_woocommerce_login'}, 'dismiss_script_migration_complete_notice': {'cartflows_dismiss_script_migration_complete_notice'}, 'ajax_accept': {'cartflows_pointer_accept'}, 'json_search_flows': {'wcf_json_search_flows'}, 'snooze_script_migration_notice': {'cartflows_snooze_script_migration'}, 'wp_ajax_install_plugin': {'cartflows_install_plugin'}, 'ajax_dismiss': {'cartflows_pointer_dismiss'}, 'ajax_should_show': {'cartflows_pointer_should_show'}, 'order_checkout_form_shortcode': {'wpcf_order_checkout_form_shortcode'}, 'remove_coupon': {'nopriv_wcf_woo_remove_coupon', 'wcf_woo_remove_coupon'}, 'disable_weekly_report_email_notice': {'cartflows_disable_weekly_report_email_notice'}, 'dismiss_notice': {'astra-notice-dismiss'}, 'send_plugin_deactivate_feedback': {'uds_plugin_deactivate_feedback'}, 'get_suretriggers_data': {'cartflows_get_suretriggers_data'}, 'order_detail_form_shortcode': {'wpcf_order_detail_form_shortcode'}, 'wcf_woo_remove_cart_product': {'nopriv_wcf_woo_remove_cart_product', 'wcf_woo_remove_cart_product'}, 'ignore_gb_notice': {'cartflows_ignore_gutenberg_notice'}, 'dismiss_new_ui_notice': {'cartflows_dismiss_new_ui_notice'}, 'check_email_exists': {'nopriv_wcf_check_email_exists'}, 'apply_coupon': {'nopriv_wcf_woo_apply_coupon', 'wcf_woo_apply_coupon'}, 'upload_checkout_file': {'wcf_upload_checkout_file', 'nopriv_wcf_upload_checkout_file'}, 'optin_form_shortcode': {'wpcf_optin_form_shortcode'}, 'fetch_whats_new_data': {'cartflows_fetch_whats_new_data'}}
*
***/

/** Function ajax_save_course_template() called by wp_ajax hooks: {'cartflows_save_tutor_course_template'} **/
/** Parameters found in function ajax_save_course_template(): {"post": ["nonce", "course_id", "template_id"]} **/
function ajax_save_course_template() {

		if (
		empty( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), self::NONCE_NAME ) ) {
			wp_send_json_error(
				array( 'message' => 'Invalid nonce.' ),
				403
			);
		}

		$course_id = isset( $_POST['course_id'] ) ? absint( $_POST['course_id'] ) : 0;

		if ( ! $course_id ) {
			wp_send_json_error(
				array( 'message' => 'Invalid course ID.' ),
				400
			);
		}

		if ( ! current_user_can( 'edit_post', $course_id ) ) {
			wp_send_json_error(
				array( 'message' => 'Permission denied.' ),
				403
			);
		}

		$template_raw = isset( $_POST['template_id'] ) ? sanitize_text_field( wp_unslash( $_POST['template_id'] ) ) : 'none';

		$template = $this->validate_template_id( $template_raw );

		update_post_meta( $course_id, self::META_KEY, $template );

		wp_send_json_success(
			array(
				'template' => $template,
				'message'  => 'Template saved.',
			)
		);
	}


/** Function woocommerce_user_login() called by wp_ajax hooks: {'nopriv_wcf_woocommerce_login'} **/
/** Parameters found in function woocommerce_user_login(): {"post": ["email", "password"]} **/
function woocommerce_user_login() {

		check_ajax_referer( 'woocommerce-login', 'security' );

		$response = array(
			'success' => false,
		);

		// wp_signon() accepts username or email via user_login; sanitize_email() would drop usernames.
		$user_login = isset( $_POST['email'] ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';
		$password   = isset( $_POST['password'] ) ? wp_unslash( $_POST['password'] ) : ''; // phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		$creds = array(
			'user_login'    => $user_login,
			'user_password' => $password,
			'remember'      => false,
		);

		$user = wp_signon( $creds, false );

		if ( ! is_wp_error( $user ) ) {
			$response = array(
				'success' => true,
			);
		} else {
			// Mirror WC's process_login() so security plugins see the failure.
			do_action( 'woocommerce_login_failed' );
			// Generic error to prevent user enumeration.
			$response['error'] = __( 'Invalid username or password.', 'cartflows' );
		}

		wp_send_json_success( $response );
	}


/** Function dismiss_script_migration_complete_notice() called by wp_ajax hooks: {'cartflows_dismiss_script_migration_complete_notice'} **/
/** No params detected :-/ **/


/** Function ajax_accept() called by wp_ajax hooks: {'cartflows_pointer_accept'} **/
/** Parameters found in function ajax_accept(): {"post": ["nonce"]} **/
function ajax_accept() {
			// Security: Check capability.
			if ( ! current_user_can( $this->get_capability() ) ) {
				wp_send_json_error( array( 'message' => __( 'Unauthorized user.', 'cartflows' ) ), 403 );
			}

			// Security: Verify nonce.
			if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'cartflows_pointer_nonce' ) ) {
				wp_send_json_error( array( 'message' => __( 'Invalid nonce.', 'cartflows' ) ), 403 );
			}

			$this->update_pointer_data( 'accepted', time() );
			wp_send_json_success();
		}


/** Function json_search_flows() called by wp_ajax hooks: {'wcf_json_search_flows'} **/
/** Parameters found in function json_search_flows(): {"post": ["security", "term"]} **/
function json_search_flows() {

		if ( ! current_user_can( 'cartflows_manage_flows_steps' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'cartflows' ) ) );
		}

		if ( isset( $_POST['security'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), 'wcf_json_search_flows' ) ) {

			global $wpdb;

			$term = isset( $_POST['term'] ) ? (string) urldecode( sanitize_text_field( wp_unslash( $_POST['term'] ) ) ) : '';

			if ( empty( $term ) ) {
				die();
			}

			$posts = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT *
								FROM {$wpdb->prefix}posts
								WHERE post_type = %s
								AND post_title LIKE %s
								AND post_status = %s",
					CARTFLOWS_FLOW_POST_TYPE,
					$wpdb->esc_like( $term ) . '%',
					'publish'
				)
			); // db call ok; no-cache ok.

			$flows_found = array();

			if ( $posts ) {
				foreach ( $posts as $post ) {
					$flows_found[ $post->ID ] = get_the_title( $post->ID );
				}
			}

			wp_send_json( $flows_found );

		}
	}


/** Function snooze_script_migration_notice() called by wp_ajax hooks: {'cartflows_snooze_script_migration'} **/
/** No params detected :-/ **/


/** Function wp_ajax_install_plugin() called by wp_ajax hooks: {'cartflows_install_plugin'} **/
/** No function found :-/ **/


/** Function ajax_dismiss() called by wp_ajax hooks: {'cartflows_pointer_dismiss'} **/
/** Parameters found in function ajax_dismiss(): {"post": ["nonce"]} **/
function ajax_dismiss() {
			// Security: Check capability.
			if ( ! current_user_can( $this->get_capability() ) ) {
				wp_send_json_error( array( 'message' => __( 'Unauthorized user.', 'cartflows' ) ), 403 );
			}

			// Security: Verify nonce.
			if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'cartflows_pointer_nonce' ) ) {
				wp_send_json_error( array( 'message' => __( 'Invalid nonce.', 'cartflows' ) ), 403 );
			}

			$this->update_pointer_data( 'dismissed', time() );
			wp_send_json_success();
		}


/** Function ajax_should_show() called by wp_ajax hooks: {'cartflows_pointer_should_show'} **/
/** Parameters found in function ajax_should_show(): {"post": ["nonce"]} **/
function ajax_should_show() {
			// Security: Check capability.
			if ( ! current_user_can( $this->get_capability() ) ) {
				wp_send_json_error( array( 'message' => __( 'Unauthorized user.', 'cartflows' ) ), 403 );
			}

			// Security: Verify nonce.
			if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'cartflows_pointer_nonce' ) ) {
				wp_send_json_error( array( 'message' => __( 'Invalid nonce.', 'cartflows' ) ), 403 );
			}

			wp_send_json(
				array(
					'show'        => true,
					'title'       => $this->config['title'],
					'content'     => $this->config['content'],
					'button_text' => $this->config['button_text'],
					'button_url'  => $this->config['button_url'],
					'dismiss'     => $this->config['dismiss_text'],
				)
			);
		}


/** Function order_checkout_form_shortcode() called by wp_ajax hooks: {'wpcf_order_checkout_form_shortcode'} **/
/** Parameters found in function order_checkout_form_shortcode(): {"post": ["id", "layout", "orderBumpSkin", "orderBumpCheckboxArrow", "orderBumpCheckboxArrowAnimation", "sectionposition", "productOptionsSkin", "productOptionsImages", "productOptionsSectionTitleText", "PreSkipText", "PreOrderText", "PreProductTitleText", "preSubTitleText", "preTitleText", "PreProductDescText", "inputSkins", "enableNote", "noteText", "stepOneTitleText", "stepOneSubTitleText", "stepTwoTitleText", "stepTwoSubTitleText", "offerButtonTitleText", "offerButtonSubTitleText"]} **/
function order_checkout_form_shortcode() {
		check_ajax_referer( 'wpcf_ajax_nonce', 'nonce' );

		if ( ! current_user_can( 'cartflows_manage_flows_steps' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'cartflows' ) ) );
		}

		add_filter(
			'cartflows_show_demo_checkout',
			function() {
				return true;
			}
		);

		if ( isset( $_POST['id'] ) ) {
			$checkout_id = intval( $_POST['id'] );
		}

		$store_checkout = intval( \Cartflows_Helper::get_global_setting( '_cartflows_store_checkout' ) );

		$flow_id = wcf()->utils->get_flow_id_from_step_id( $checkout_id );

		if ( ! wcf()->flow->is_flow_testmode( $flow_id ) && ( $store_checkout !== $flow_id ) ) {

			$products = wcf()->utils->get_selected_checkout_products( $checkout_id );

			if ( ! is_array( $products ) || empty( $products[0]['product'] ) ) {
				wc_clear_notices();
				wc_add_notice( __( 'No product is selected. Please select products from the checkout meta settings to continue.', 'cartflows' ), 'error' );
			}
		}
		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form' );

		add_action( 'woocommerce_checkout_order_review', array( Cartflows_Checkout_Markup::get_instance(), 'display_custom_coupon_field' ) );

		$attributes['layout']                          = isset( $_POST['layout'] ) ? sanitize_title( wp_unslash( $_POST['layout'] ) ) : '';
		$attributes['orderBumpSkin']                   = isset( $_POST['orderBumpSkin'] ) ? sanitize_title( wp_unslash( $_POST['orderBumpSkin'] ) ) : '';
		$attributes['orderBumpCheckboxArrow']          = isset( $_POST['orderBumpCheckboxArrow'] ) ? sanitize_title( wp_unslash( $_POST['orderBumpCheckboxArrow'] ) ) : '';
		$attributes['orderBumpCheckboxArrowAnimation'] = isset( $_POST['orderBumpCheckboxArrowAnimation'] ) ? sanitize_title( wp_unslash( $_POST['orderBumpCheckboxArrowAnimation'] ) ) : '';
		$attributes['sectionposition']                 = isset( $_POST['sectionposition'] ) ? sanitize_title( wp_unslash( $_POST['sectionposition'] ) ) : '';
		$attributes['productOptionsSkin']              = isset( $_POST['productOptionsSkin'] ) ? sanitize_title( wp_unslash( $_POST['productOptionsSkin'] ) ) : '';
		$attributes['productOptionsImages']            = isset( $_POST['productOptionsImages'] ) ? sanitize_title( wp_unslash( $_POST['productOptionsImages'] ) ) : '';
		$attributes['productOptionsSectionTitleText']  = isset( $_POST['productOptionsSectionTitleText'] ) ? sanitize_text_field( wp_unslash( $_POST['productOptionsSectionTitleText'] ) ) : '';
		$attributes['PreSkipText']                     = isset( $_POST['PreSkipText'] ) ? sanitize_title( wp_unslash( $_POST['PreSkipText'] ) ) : '';
		$attributes['PreOrderText']                    = isset( $_POST['PreOrderText'] ) ? sanitize_title( wp_unslash( $_POST['PreOrderText'] ) ) : '';
		$attributes['PreProductTitleText']             = isset( $_POST['PreProductTitleText'] ) ? sanitize_title( wp_unslash( $_POST['PreProductTitleText'] ) ) : '';
		$attributes['preSubTitleText']                 = isset( $_POST['preSubTitleText'] ) ? sanitize_title( wp_unslash( $_POST['preSubTitleText'] ) ) : '';
		$attributes['preTitleText']                    = isset( $_POST['preTitleText'] ) ? sanitize_title( wp_unslash( $_POST['preTitleText'] ) ) : '';
		$attributes['PreProductDescText']              = isset( $_POST['PreProductDescText'] ) ? sanitize_title( wp_unslash( $_POST['PreProductDescText'] ) ) : '';
		$attributes['inputSkins']                      = isset( $_POST['inputSkins'] ) ? sanitize_title( wp_unslash( $_POST['inputSkins'] ) ) : '';
		$attributes['enableNote']                      = isset( $_POST['enableNote'] ) ? sanitize_title( wp_unslash( $_POST['enableNote'] ) ) : '';
		$attributes['noteText']                        = isset( $_POST['noteText'] ) ? sanitize_text_field( wp_unslash( $_POST['noteText'] ) ) : '';
		$attributes['stepOneTitleText']                = isset( $_POST['stepOneTitleText'] ) ? sanitize_text_field( wp_unslash( $_POST['stepOneTitleText'] ) ) : '';
		$attributes['stepOneSubTitleText']             = isset( $_POST['stepOneSubTitleText'] ) ? sanitize_text_field( wp_unslash( $_POST['stepOneSubTitleText'] ) ) : '';
		$attributes['stepTwoTitleText']                = isset( $_POST['stepTwoTitleText'] ) ? sanitize_text_field( wp_unslash( $_POST['stepTwoTitleText'] ) ) : '';
		$attributes['stepTwoSubTitleText']             = isset( $_POST['stepTwoSubTitleText'] ) ? sanitize_text_field( wp_unslash( $_POST['stepTwoSubTitleText'] ) ) : '';
		$attributes['offerButtonTitleText']            = isset( $_POST['offerButtonTitleText'] ) ? sanitize_text_field( wp_unslash( $_POST['offerButtonTitleText'] ) ) : '';
		$attributes['offerButtonSubTitleText']         = isset( $_POST['offerButtonSubTitleText'] ) ? sanitize_text_field( wp_unslash( $_POST['offerButtonSubTitleText'] ) ) : '';

		$checkout_fields = array(
			// Input Fields.
			array(
				'filter_slug'  => 'wcf-fields-skins',
				'setting_name' => 'inputSkins',
			),
			array(
				'filter_slug'  => 'wcf-checkout-layout',
				'setting_name' => 'layout',
			),
		);

		if ( isset( $checkout_fields ) && is_array( $checkout_fields ) ) {

			foreach ( $checkout_fields as $key => $field ) {

				$setting_name = $field['setting_name'];

				if ( '' !== $attributes[ $setting_name ] ) {

					add_filter(
						'cartflows_checkout_meta_' . $field['filter_slug'],
						function ( $value ) use ( $setting_name, $attributes ) {

							$value = $attributes[ $setting_name ];

							return $value;
						},
						10,
						1
					);
				}
			}
		}
		do_action( 'cartflows_gutenberg_before_checkout_shortcode', $checkout_id );

		do_action( 'cartflows_gutenberg_checkout_options_filters', $attributes );

		do_action( 'cartflows_gutenberg_before_checkout_shortcode' );

		$data['html'] = do_shortcode( '[cartflows_checkout]' );

		wp_send_json_success( $data );
	}


/** Function remove_coupon() called by wp_ajax hooks: {'nopriv_wcf_woo_remove_coupon', 'wcf_woo_remove_coupon'} **/
/** Parameters found in function remove_coupon(): {"post": ["coupon_code"]} **/
function remove_coupon() {
		check_ajax_referer( 'wcf-remove-coupon', 'security' );
		$coupon = isset( $_POST['coupon_code'] ) ? sanitize_text_field( wp_unslash( $_POST['coupon_code'] ) ) : false;

		if ( empty( $coupon ) ) {
			echo "<div class='woocommerce-error'>" . esc_html__( 'Sorry there was a problem removing this coupon.', 'cartflows' ) . '</div>';
		} else {
			WC()->cart->remove_coupon( $coupon );
			echo "<div class='woocommerce-error'>" . esc_html__( 'Coupon has been removed.', 'cartflows' ) . '</div>';
		}
		wc_print_notices();
		wp_die();
	}


/** Function disable_weekly_report_email_notice() called by wp_ajax hooks: {'cartflows_disable_weekly_report_email_notice'} **/
/** No params detected :-/ **/


/** Function dismiss_notice() called by wp_ajax hooks: {'astra-notice-dismiss'} **/
/** Parameters found in function dismiss_notice(): {"post": ["notice_id", "repeat_notice_after"]} **/
function dismiss_notice() {
			check_ajax_referer( 'astra-notices', 'nonce' );

			$notice_id           = ( isset( $_POST['notice_id'] ) ) ? sanitize_key( wp_unslash( $_POST['notice_id'] ) ) : '';
			$repeat_notice_after = ( isset( $_POST['repeat_notice_after'] ) ) ? absint( wp_unslash( $_POST['repeat_notice_after'] ) ) : 0;
			$notice              = $this->get_notice_by_id( $notice_id );
			$capability          = isset( $notice['capability'] ) ? $notice['capability'] : 'manage_options';

			$has_cap = current_user_can( $capability );

			/**
			 * Filters whether the current user passes the capability check for notice dismissal.
			 *
			 * Both the legacy and new filter names are fired for backward compatibility.
			 * Filters can only restrict access (return false), never grant it — if the
			 * underlying current_user_can() check fails, filters cannot override to true.
			 */
			$cap_check = apply_filters( 'astra_notices_user_cap_check', $has_cap );
			$cap_check = apply_filters( 'bsf_admin_notices_user_cap_check', $cap_check );

			if ( ! $has_cap || ! $cap_check ) {
				wp_send_json_error( esc_html__( 'Permission denied.', 'astra-notices' ) );
			}

			$allowed_notices = get_option( 'astra_notices_allowed', array() ); // Get allowed notices.

			// Define restricted user meta keys using the dynamic table prefix.
			global $wpdb;
			$wp_default_meta_keys = array(
				$wpdb->prefix . 'capabilities',
				$wpdb->prefix . 'user_level',
				$wpdb->prefix . 'user-settings',
				'account_status',
				'session_tokens',
			);

			// if $notice_id does not start with astra-notices-id and notice_id is not from the allowed notices, then return.
			if ( 0 !== strpos( $notice_id, 'astra-notices-id-' ) && ( ! in_array( $notice_id, $allowed_notices, true ) ) ) {
				wp_send_json_error( esc_html__( 'Invalid notice ID.', 'astra-notices' ) );
			}

			// Valid inputs?
			if ( ! empty( $notice_id ) ) {

				if ( in_array( $notice_id, $wp_default_meta_keys, true ) ) {
					wp_send_json_error( esc_html__( 'Invalid notice ID.', 'astra-notices' ) );
				}

				if ( ! empty( $repeat_notice_after ) ) {
					set_transient( $notice_id, true, $repeat_notice_after );
				} else {
					update_user_meta( get_current_user_id(), $notice_id, 'notice-dismissed' );
				}

				wp_send_json_success();
			}

			wp_send_json_error();
		}


/** Function send_plugin_deactivate_feedback() called by wp_ajax hooks: {'uds_plugin_deactivate_feedback'} **/
/** Parameters found in function send_plugin_deactivate_feedback(): {"post": ["reason", "feedback", "referer", "version", "source"]} **/
function send_plugin_deactivate_feedback() {

			$response_data = array( 'message' => __( 'Sorry, you are not allowed to do this operation.' ) );

			/**
			 * Check permission
			 */
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( $response_data );
			}

			/**
			 * Nonce verification
			 */
			if ( ! check_ajax_referer( 'uds_plugin_deactivate_feedback', 'security', false ) ) {
				$response_data = array( 'message' => __( 'Nonce validation failed' ) );
				wp_send_json_error( $response_data );
			}

			$feedback_data = array(
				'reason'      => isset( $_POST['reason'] ) ? sanitize_text_field( wp_unslash( $_POST['reason'] ) ) : '',
				'feedback'    => isset( $_POST['feedback'] ) ? sanitize_text_field( wp_unslash( $_POST['feedback'] ) ) : '',
				'domain_name' => isset( $_POST['referer'] ) ? sanitize_text_field( wp_unslash( $_POST['referer'] ) ) : '',
				'version'     => isset( $_POST['version'] ) ? sanitize_text_field( wp_unslash( $_POST['version'] ) ) : '',
				'plugin'      => isset( $_POST['source'] ) ? sanitize_text_field( wp_unslash( $_POST['source'] ) ) : '',
			);

			$api_args = array(
				'body'    => wp_json_encode( $feedback_data ),
				'headers' => BSF_Analytics_Helper::get_api_headers(),
				'timeout' => 15, //phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout
			);

			$target_url = BSF_Analytics_Helper::get_api_url() . self::$feedback_api_endpoint;

			$response = wp_safe_remote_post( $target_url, $api_args );

			$has_errors = BSF_Analytics_Helper::is_api_error( $response );

			if ( $has_errors['error'] ) {
				wp_send_json_error(
					array(
						'success' => false,
						'message' => $has_errors['error_message'],
					)
				);
			}

			wp_send_json_success();
		}


/** Function get_suretriggers_data() called by wp_ajax hooks: {'cartflows_get_suretriggers_data'} **/
/** No params detected :-/ **/


/** Function order_detail_form_shortcode() called by wp_ajax hooks: {'wpcf_order_detail_form_shortcode'} **/
/** Parameters found in function order_detail_form_shortcode(): {"post": ["thanyouText", "layout", "id"]} **/
function order_detail_form_shortcode() {

		check_ajax_referer( 'wpcf_ajax_nonce', 'nonce' );

		if ( ! current_user_can( 'cartflows_manage_flows_steps' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'cartflows' ) ) );
		}

		add_filter(
			'cartflows_show_demo_order_details',
			function() {
				return true;
			}
		);

		if ( ! empty( $_POST['thanyouText'] ) ) {

			add_filter(
				'cartflows_thankyou_meta_wcf-tq-text',
				function( $text ) {
					check_ajax_referer( 'wpcf_ajax_nonce', 'nonce' );

					$text = isset( $_POST['thanyouText'] ) ? sanitize_text_field( wp_unslash( $_POST['thanyouText'] ) ) : '';

					return $text;
				},
				10,
				1
			);
		}

		add_filter(
			'cartflows_thankyou_meta_wcf-tq-layout',
			function( $layout ) {
				check_ajax_referer( 'wpcf_ajax_nonce', 'nonce' );

				$layout = isset( $_POST['layout'] ) ? sanitize_title( wp_unslash( $_POST['layout'] ) ) : '';
				return $layout;
			},
			10,
			1
		);

		$thankyou_id          = isset( $_POST['id'] ) ? intval( wp_unslash( $_POST['id'] ) ) : 0;
		$data['html']         = do_shortcode( '[cartflows_order_details]' );
		$data['thankyouText'] = wcf()->options->get_thankyou_meta_value( $thankyou_id, 'wcf-tq-text' );
		$data['layout']       = wcf()->options->get_thankyou_meta_value( $thankyou_id, 'wcf-tq-layout' );
		
		wp_send_json_success( $data );
	}


/** Function wcf_woo_remove_cart_product() called by wp_ajax hooks: {'nopriv_wcf_woo_remove_cart_product', 'wcf_woo_remove_cart_product'} **/
/** Parameters found in function wcf_woo_remove_cart_product(): {"post": ["p_key", "p_id"]} **/
function wcf_woo_remove_cart_product() {
		check_ajax_referer( 'wcf-remove-cart-product', 'security' );
		$product_key   = isset( $_POST['p_key'] ) ? sanitize_text_field( wp_unslash( $_POST['p_key'] ) ) : false;
		$product_id    = isset( $_POST['p_id'] ) ? sanitize_text_field( wp_unslash( $_POST['p_id'] ) ) : '';
		$product_title = get_the_title( $product_id );

		$needs_shipping = false;
		$is_order_bump  = false;
		$order_bump_id  = '';

		// Check if the product is an order bump before removing it.
		if ( ! empty( $product_key ) ) {
			$cart_item = WC()->cart->get_cart_item( $product_key );
			if ( isset( $cart_item['cartflows_bump'] ) && $cart_item['cartflows_bump'] ) {
				$is_order_bump = true;
				$order_bump_id = isset( $cart_item['ob_id'] ) ? $cart_item['ob_id'] : '';
			}
			
			WC()->cart->remove_cart_item( $product_key );
			$msg = "<div class='woocommerce-message'>" . $product_title . __( ' has been removed.', 'cartflows' ) . '</div>';
		} else {
			$msg = "<div class='woocommerce-message'>" . __( 'Sorry there was a problem removing ', 'cartflows' ) . $product_title;
		}

		foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
			if ( $values['data']->needs_shipping() ) {
				$needs_shipping = true;
				break;
			}
		}

		$response = array(
			'need_shipping' => $needs_shipping,
			'msg'           => $msg,
			'is_order_bump' => $is_order_bump,
			'order_bump_id' => $order_bump_id,
		);

		echo wp_json_encode( $response );
		wp_die();
	}


/** Function ignore_gb_notice() called by wp_ajax hooks: {'cartflows_ignore_gutenberg_notice'} **/
/** No params detected :-/ **/


/** Function dismiss_new_ui_notice() called by wp_ajax hooks: {'cartflows_dismiss_new_ui_notice'} **/
/** No params detected :-/ **/


/** Function check_email_exists() called by wp_ajax hooks: {'nopriv_wcf_check_email_exists'} **/
/** Parameters found in function check_email_exists(): {"post": ["email_address"]} **/
function check_email_exists() {

		check_ajax_referer( 'check-email-exist', 'security' );

		$email_address = isset( $_POST['email_address'] ) ? sanitize_email( wp_unslash( $_POST['email_address'] ) ) : false;

		$is_login_allowed = 'yes' === get_option( 'woocommerce_enable_checkout_login_reminder' );

		// Security: Only check email existence when the checkout login UX needs it.
		// Otherwise the boolean would leak registered emails for no UX benefit.
		$is_exist = $is_login_allowed && email_exists( $email_address );

		$response = array(
			'success'          => boolval( $is_exist ),
			'is_login_allowed' => $is_login_allowed,
			'msg'              => $is_exist ? __( 'Email Exist.', 'cartflows' ) : __( 'Email not exist', 'cartflows' ),
		);

		wp_send_json_success( $response );
	}


/** Function apply_coupon() called by wp_ajax hooks: {'nopriv_wcf_woo_apply_coupon', 'wcf_woo_apply_coupon'} **/
/** Parameters found in function apply_coupon(): {"post": ["coupon_code"]} **/
function apply_coupon() {
		$response = '';

		if ( ! check_ajax_referer( 'wcf-apply-coupon', 'security', false ) ) {
			$response_data = array(
				'status' => false,
				'error'  => __( 'Nonce validation failed', 'cartflows' ),
			);
			wp_send_json_error( $response_data );
		}

		// Update the billing email before adding a coupon required for coupon conditions.
		$this->update_billing_email();

		ob_start();

		if ( ! empty( $_POST['coupon_code'] ) ) {
			$result = WC()->cart->add_discount( sanitize_text_field( wp_unslash( $_POST['coupon_code'] ) ) );
		} else {
			wc_add_notice( WC_Coupon::get_generic_coupon_error( WC_Coupon::E_WC_COUPON_PLEASE_ENTER ), 'error' );
		}

		$response = array(
			'status' => $result,
			'msg'    => wc_print_notices( true ),
		);

		ob_clean(); // Clearing the uncessary echo HTML.
		wp_send_json( $response );

		die();
	}


/** Function upload_checkout_file() called by wp_ajax hooks: {'wcf_upload_checkout_file', 'nopriv_wcf_upload_checkout_file'} **/
/** Parameters found in function upload_checkout_file(): {"files": ["wcf_checkout_file"]} **/
function upload_checkout_file() {

		if ( ! check_ajax_referer( 'wcf-file-upload', 'security', false ) ) {
			wp_send_json_error(
				array( 'error' => __( 'Nonce validation failed.', 'cartflows' ) )
			);
		}

		if ( empty( $_FILES['wcf_checkout_file']['tmp_name'] ) ) {
			wp_send_json_error(
				array( 'error' => __( 'No file uploaded.', 'cartflows' ) )
			);
		}

		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		$file         = $_FILES['wcf_checkout_file']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput, WordPress.Security.NonceVerification.Missing
		$file['name'] = sanitize_file_name( $file['name'] );
		$file['ext']  = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );

		$master_allowed = Cartflows_Helper::get_allowed_file_extensions();

		$restrictions = $this->get_field_restrictions();

		$allowed_extensions = empty( $restrictions['extensions'] )
			? $master_allowed
			: array_values( array_intersect( $master_allowed, $restrictions['extensions'] ) );

		if ( ! in_array( $file['ext'], $allowed_extensions, true ) ) {
			wp_send_json_error( array( 'error' => __( 'File type is not allowed.', 'cartflows' ) ) );
		}

		if ( (int) $file['size'] > $restrictions['max_size'] ) {
			wp_send_json_error( array( 'error' => __( 'File size exceeds the allowed limit.', 'cartflows' ) ) );
		}

		$check = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'], wp_get_mime_types() );

		if ( empty( $check['ext'] ) || $check['ext'] !== $file['ext'] ) {
			wp_send_json_error( array( 'error' => __( 'Invalid or corrupted file.', 'cartflows' ) ) );
		}

		$result = $this->move_uploaded_file( $file );

		wp_send_json_success(
			array(
				'success'  => true,
				'url'      => esc_url_raw( $result['url'] ),
				'filename' => sanitize_file_name( basename( $result['file'] ) ),
			)
		);
	}


/** Function optin_form_shortcode() called by wp_ajax hooks: {'wpcf_optin_form_shortcode'} **/
/** Parameters found in function optin_form_shortcode(): {"post": ["id", "input_skins"]} **/
function optin_form_shortcode() {

		check_ajax_referer( 'wpcf_ajax_nonce', 'nonce' );

		if ( ! current_user_can( 'cartflows_manage_flows_steps' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'cartflows' ) ) );
		}

		add_filter(
			'cartflows_show_demo_optin_form',
			function() {
				return true;
			}
		);

		if ( isset( $_POST['id'] ) ) {
			$optin_id = intval( $_POST['id'] );
		}

		$products = wcf()->options->get_optin_meta_value( $optin_id, 'wcf-optin-product' );
		if ( is_array( $products ) && count( $products ) < 1 ) {
			wc_clear_notices();
			wc_add_notice( __( 'No product is selected. Please select a Simple, Virtual and Free product from the meta settings.', 'cartflows' ), 'error' );
		}

		$attributes['input_skins'] = isset( $_POST['input_skins'] ) ? sanitize_title( wp_unslash( $_POST['input_skins'] ) ) : '';

		$optin_fields = array(

			// Input Fields.
			array(
				'filter_slug'  => 'wcf-input-fields-skins',
				'setting_name' => 'input_skins',
			),
		);

		if ( isset( $optin_fields ) && is_array( $optin_fields ) ) {

			foreach ( $optin_fields as $key => $field ) {

				$setting_name = $field['setting_name'];

				add_filter(
					'cartflows_optin_meta_' . $field['filter_slug'],
					function ( $value ) use ( $setting_name, $attributes ) {

						$value = $attributes[ $setting_name ];

						return $value;
					},
					10,
					1
				);
			}
		}

		do_action( 'cartflows_gutenberg_optin_options_filters', $attributes );

		add_filter( 'woocommerce_cart_needs_payment', '__return_false' );
		add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );
		add_filter( 'woocommerce_cart_needs_shipping_address', '__return_false' );

		$data['html']       = do_shortcode( '[cartflows_optin]' );
		$data['buttonText'] = wcf()->options->get_optin_meta_value( $optin_id, 'wcf-submit-button-text' );
		wp_send_json_success( $data );
	}


/** Function fetch_whats_new_data() called by wp_ajax hooks: {'cartflows_fetch_whats_new_data'} **/
/** Parameters found in function fetch_whats_new_data(): {"get": ["nonce"]} **/
function fetch_whats_new_data() {
		if ( ! isset( $_GET['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_GET['nonce'] ), 'cartflows_fetch_whats_new_data' ) ) {
			// Verify the nonce, if it fails, return an error.
			wp_send_json_error( array( 'message' => __( 'Nonce verification failed.', 'cartflows' ) ) );
		}

		// Security: Require admin capability to access RSS feed proxy.
		if ( ! current_user_can( 'cartflows_manage_flows_steps' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'cartflows' ) ) );
		}

		// Fetch the RSS feed from the URL. This saves us from the CORS issue.
		$feed = wp_remote_retrieve_body( wp_safe_remote_get( 'https://cartflows.com/product/cartflows/feed/' ) ); // phpcs:ignore -- This is a valid use case cannot use VIP rules here.

		// Security: Set proper content type header and strip script tags to prevent XSS.
		echo $feed; // phpcs:ignore -- RSS feed content sanitized via wp_kses_post.
		exit;
	}


