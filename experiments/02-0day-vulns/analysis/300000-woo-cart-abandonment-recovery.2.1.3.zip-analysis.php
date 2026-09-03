<?php
/***
*
*Found actions: 19
*Found functions:17
*Extracted functions:16
*Total parameter names extracted: 9
*Overview: {'complete_onboarding': {'wcar_complete_onboarding'}, 'ajax_export_templates': {'wcf_ca_export_email_templates'}, 'update_ui_switch_option': {'wcar_switch_to_new_ui'}, 'fetch_whats_new': {'cart_abandonment_fetch_whats_new'}, 'send_preview_email': {'wcf_ca_preview_email_send'}, 'update_email_toggle_button': {'activate_email_templates'}, 'dismiss_notice': {'astra-notice-dismiss'}, 'send_plugin_deactivate_feedback': {'uds_plugin_deactivate_feedback'}, 'skip_cart_tracking_by_gdpr': {'cartflows_skip_cart_tracking_gdpr', 'nopriv_cartflows_skip_cart_tracking_gdpr'}, 'update_db_ajax': {'wcar_update_db'}, 'ajax_import_templates': {'wcf_ca_import_email_templates'}, 'delete_used_and_expired_coupons': {'wcf_ca_delete_garbage_coupons'}, 'save_new_ui_option': {'wcf_ca_save_new_ui_option'}, 'disable_weekly_report_email_notice': {'wcar_disable_weekly_report_email_notice'}, 'wp_ajax_install_plugin': {'cart_abandonment_install_plugin'}, 'save_cart_abandonment_data': {'nopriv_cartflows_save_cart_abandonment_data', 'cartflows_save_cart_abandonment_data'}, 'cart_abandonment_activate_plugin': {'cart_abandonment_activate_plugin'}}
*
***/

/** Function complete_onboarding() called by wp_ajax hooks: {'wcar_complete_onboarding'} **/
/** Parameters found in function complete_onboarding(): {"get": ["nonce"]} **/
function complete_onboarding(): void {
		if ( ! isset( $_GET['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['nonce'] ) ), 'wcar_onboarding_nonce' ) ) {
			// Verify the nonce, if it fails, return an error.
			wp_send_json_error( [ 'message' => esc_html__( 'Nonce verification failed.', 'woo-cart-abandonment-recovery' ) ] );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'woo-cart-abandonment-recovery' ) );
		}

		$raw_input       = file_get_contents( 'php://input' ); // phpcs:ignore WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsRemoteFile
		$onboarding_data = is_string( $raw_input ) ? json_decode( $raw_input, true ) : null;

		if ( empty( $onboarding_data ) || ! is_array( $onboarding_data ) ) {
			Wcar_Onboarding::get_instance()->set_onboarding_status( true );
			update_option( 'wcar_onboarding_skipped', true );
			wp_send_json_success();
		}

		$plugin_slugs = $onboarding_data['plugins'];

		$user_details_data = [
			'first_name' => $onboarding_data['userDetails']['user_detail_firstname'],
			'last_name'  => $onboarding_data['userDetails']['user_detail_lastname'],
			'email'      => $onboarding_data['userDetails']['user_detail_email'],
		];

		if ( ! empty( $user_details_data ) && ! empty( $user_details_data['email'] ) ) {
			$encoded_body = wp_json_encode( $user_details_data );
			wp_remote_post(
				WCAR_ONBOARDING_USER_SUB_WORKFLOW_URL,
				[
					'body'    => $encoded_body,
					'headers' => [
						'Content-Type' => 'application/json',
					],
				]
			);
		}
		
		// Convert analytics optin value to 'yes' if it's truthy, otherwise keep as is.
		$analytics_optin_value = $onboarding_data['userDetails']['wcar_usage_optin'];
		
		if ( true === $analytics_optin_value ) {
			$analytics_optin_value = 'yes';
		}
		
		wcf_ca()->helper->save_meta_fields( 'wcar_usage_optin', $analytics_optin_value );

		$installable_plugin_slugs = [];
		if ( ! empty( $plugin_slugs ) && is_array( $plugin_slugs ) ) {
			foreach ( $plugin_slugs as $slug => $should_install ) {
				if ( filter_var( $should_install, FILTER_VALIDATE_BOOLEAN ) ) {
					$installable_plugin_slugs[] = sanitize_key( $slug );
				}
			}
		}

		// Add plugin installation logic in complete_onboarding() method after mapped_data loop.
		if ( ! empty( $installable_plugin_slugs ) ) {
			$this->install_plugin( $installable_plugin_slugs );
		}

		Wcar_Onboarding::get_instance()->set_onboarding_status( true );

		wp_send_json_success();
	}


/** Function ajax_export_templates() called by wp_ajax hooks: {'wcf_ca_export_email_templates'} **/
/** Parameters found in function ajax_export_templates(): {"post": ["ids"]} **/
function ajax_export_templates() {
		check_ajax_referer( WCF_EMAIL_TEMPLATES_NONCE, '_wpnonce' );

		$ids = isset( $_POST['ids'] ) ? wp_unslash( $_POST['ids'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$ids = is_array( $ids ) ? array_map( 'absint', $ids ) : array_map( 'absint', array( $ids ) );

		if ( empty( $ids ) ) {
			$templates = $this->get_all_templates();
		} else {
			$templates = $this->get_templates_by_ids( $ids );
		}

		$data = $this->generate_export_array( $templates );

		wp_send_json( $data );
	}


/** Function update_ui_switch_option() called by wp_ajax hooks: {'wcar_switch_to_new_ui'} **/
/** Parameters found in function update_ui_switch_option(): {"post": ["action_type"]} **/
function update_ui_switch_option(): void {

		// Check user capability.
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( __( 'Insufficient permissions', 'woo-cart-abandonment-recovery' ) );
		}

		// Check is the data is available in POST.
		if ( empty( $_POST ) ) {
			$response_data = [ 'message' => __( 'No post data found', 'woo-cart-abandonment-recovery' ) ];
			wp_send_json_error( $response_data );
		}

		// Nonce verification.
		if ( ! check_ajax_referer( 'wcar_ui_switch_nonce', 'nonce', false ) ) {
			$response_data = [ 'message' => __( 'Invalid Nonce.', 'woo-cart-abandonment-recovery' ) ];
			wp_send_json_error( $response_data );
		}

		// Find the notice action.
		$action = isset( $_POST['action_type'] ) ? sanitize_text_field( wp_unslash( $_POST['action_type'] ) ) : '';

		// Button action.
		if ( empty( $action ) ) {
			$response_data = [ 'message' => __( 'Button action not found.', 'woo-cart-abandonment-recovery' ) ];
			wp_send_json_error( $response_data );
		}

		if ( 'new-ui' === $action ) {
			// User wants to switch to new UI - set the persistent option.
			update_option( 'cartflows_ca_use_new_ui', true );
			wp_send_json_success(
				[
					'message'     => __( 'Switched to new UI', 'woo-cart-abandonment-recovery' ),
					'redirect_to' => admin_url( 'admin.php?page=woo-cart-abandonment-recovery' ),
				] 
			);
		} elseif ( 'dismiss' === $action ) {
			// User dismissed the notice - don't change UI preference, just hide notice.
			// We could add a separate option to track notice dismissal if needed.
			wp_send_json_success(
				[
					'message' => __( 'Notice dismissed', 'woo-cart-abandonment-recovery' ),
					'reload'  => true,
				] 
			);
		}

		wp_send_json_error( [ 'message' => __( 'Invalid action', 'woo-cart-abandonment-recovery' ) ] );
	}


/** Function fetch_whats_new() called by wp_ajax hooks: {'cart_abandonment_fetch_whats_new'} **/
/** Parameters found in function fetch_whats_new(): {"get": ["nonce"]} **/
function fetch_whats_new(): void {
		if ( ! isset( $_GET['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_GET['nonce'] ), 'cart_abandonment_fetch_whats_new' ) ) {
			// Verify the nonce, if it fails, return an error.
			wp_send_json_error( [ 'message' => __( 'Nonce verification failed.', 'woo-cart-abandonment-recovery' ) ] );
		}

		// Fetch the RSS feed from the URL. This saves us from the CORS issue.
		$feed = wp_remote_retrieve_body( wp_remote_get( 'https://cartflows.com/product/cart-abandonment/feed/' ) ); // phpcs:ignore -- This is a valid use case cannot use VIP rules here.

		echo $feed; // phpcs:ignore -- Cannot sanitize the XML data as it is not in our control here.
		exit;
	}


/** Function send_preview_email() called by wp_ajax hooks: {'wcf_ca_preview_email_send'} **/
/** No params detected :-/ **/


/** Function update_email_toggle_button() called by wp_ajax hooks: {'activate_email_templates'} **/
/** No params detected :-/ **/


/** Function dismiss_notice() called by wp_ajax hooks: {'astra-notice-dismiss'} **/
/** Parameters found in function dismiss_notice(): {"post": ["notice_id", "repeat_notice_after"]} **/
function dismiss_notice() {
			check_ajax_referer( 'astra-notices', 'nonce' );

			$notice_id           = ( isset( $_POST['notice_id'] ) ) ? sanitize_key( wp_unslash( $_POST['notice_id'] ) ) : '';
			$repeat_notice_after = ( isset( $_POST['repeat_notice_after'] ) ) ? absint( $_POST['repeat_notice_after'] ) : '';
			$notice              = $this->get_notice_by_id( $notice_id );
			$capability          = isset( $notice['capability'] ) ? $notice['capability'] : 'manage_options';

			if ( ! apply_filters( 'astra_notices_user_cap_check', current_user_can( $capability ) ) ) {
				return;
			}

			$allowed_notices = get_option( 'allowed_astra_notices', array() ); // Get allowed notices.

			// Define restricted user meta keys.
			$wp_default_meta_keys = array(
				'wp_capabilities',
				'wp_user_level',
				'wp_user-settings',
				'account_status',
				'session_tokens',
			);

			// if $notice_id does not start with astra-notices-id and notice_id is not from the allowed notices, then return.
			if ( strpos( $notice_id, 'astra-notices-id-' ) !== 0 && ( ! in_array( $notice_id, $allowed_notices, true ) ) ) {
				return;
			}

			// Valid inputs?
			if ( ! empty( $notice_id ) ) {

				if ( in_array( $notice_id, $wp_default_meta_keys, true ) ) {
					wp_send_json_error( esc_html__( 'Invalid notice ID.', 'woo-cart-abandonment-recovery' ) );
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

			$response_data = array( 'message' => __( 'Sorry, you are not allowed to do this operation.', 'woo-cart-abandonment-recovery' ) );

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
				$response_data = array( 'message' => __( 'Nonce validation failed', 'woo-cart-abandonment-recovery' ) );
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


/** Function skip_cart_tracking_by_gdpr() called by wp_ajax hooks: {'cartflows_skip_cart_tracking_gdpr', 'nopriv_cartflows_skip_cart_tracking_gdpr'} **/
/** No params detected :-/ **/


/** Function update_db_ajax() called by wp_ajax hooks: {'wcar_update_db'} **/
/** No params detected :-/ **/


/** Function ajax_import_templates() called by wp_ajax hooks: {'wcf_ca_import_email_templates'} **/
/** Parameters found in function ajax_import_templates(): {"post": ["templates"]} **/
function ajax_import_templates() {
		// Verifies the nonce for security purposes.
		check_ajax_referer( WCF_EMAIL_TEMPLATES_NONCE, '_wpnonce' );

		// Retrieves and sanitizes the template data from the POST request. No need to further sanitization as we are doing later before importing the code.
		$json = isset( $_POST['templates'] ) ? wp_unslash( $_POST['templates'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		// Checks if the template data is empty.
		if ( empty( $json ) ) {
			// Sends a JSON error response if no data is found.
			wp_send_json_error();
		}

		// Decodes the JSON string into a PHP array.
		$templates = json_decode( $json, true );
		if ( empty( $templates ) ) {
			wp_send_json_error();
		}

		if ( isset( $templates['template_name'] ) ) {
			$templates = array( $templates );
		} elseif ( ! is_array( $templates ) ) {
			wp_send_json_error();
		}

		$this->insert_templates( $templates );

		wp_send_json_success();
	}


/** Function delete_used_and_expired_coupons() called by wp_ajax hooks: {'wcf_ca_delete_garbage_coupons'} **/
/** No params detected :-/ **/


/** Function save_new_ui_option() called by wp_ajax hooks: {'wcf_ca_save_new_ui_option'} **/
/** Parameters found in function save_new_ui_option(): {"post": ["value"]} **/
function save_new_ui_option() {

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( __( 'Permission denied.', 'woo-cart-abandonment-recovery' ) );
		}

		if ( ! check_ajax_referer( 'wcf_ca_new_ui_nonce', 'security' ) ) {
			wp_send_json_error( __( 'Security check failed.', 'woo-cart-abandonment-recovery' ) );
		}

		if ( ! isset( $_POST['value'] ) ) {
			wp_send_json_error( __( 'Required data missing.', 'woo-cart-abandonment-recovery' ) );
		}

		$value = sanitize_text_field( wp_unslash( $_POST['value'] ) );
		
		if ( ! in_array( $value, [ 'on', '' ], true ) ) {
			wp_send_json_error( __( 'Invalid value provided.', 'woo-cart-abandonment-recovery' ) );
		}

		// Save the option.
		$result = update_option( 'cartflows_ca_use_new_ui', $value );

		if ( $result ) {
			wp_send_json_success( __( 'Setting saved successfully.', 'woo-cart-abandonment-recovery' ) );
		} else {
			wp_send_json_error( __( 'Failed to save setting.', 'woo-cart-abandonment-recovery' ) );
		}
	}


/** Function disable_weekly_report_email_notice() called by wp_ajax hooks: {'wcar_disable_weekly_report_email_notice'} **/
/** No params detected :-/ **/


/** Function wp_ajax_install_plugin() called by wp_ajax hooks: {'cart_abandonment_install_plugin'} **/
/** No function found :-/ **/


/** Function save_cart_abandonment_data() called by wp_ajax hooks: {'nopriv_cartflows_save_cart_abandonment_data', 'cartflows_save_cart_abandonment_data'} **/
/** No params detected :-/ **/


/** Function cart_abandonment_activate_plugin() called by wp_ajax hooks: {'cart_abandonment_activate_plugin'} **/
/** Parameters found in function cart_abandonment_activate_plugin(): {"post": ["security", "init"]} **/
function cart_abandonment_activate_plugin(): void {
		if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['security'] ), 'cart_abandonment_activate_plugin_nonce' ) ) {
			wp_send_json_error( [ 'message' => 'Nonce verification failed.' ] );
		}
		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error( [ 'message' => 'You do not have permission to activate plugins.' ] );
		}
		$plugin_slug = isset( $_POST['init'] ) ? sanitize_text_field( $_POST['init'] ) : '';
		if ( empty( $plugin_slug ) ) {
			wp_send_json_error( [ 'message' => 'Invalid plugin slug.' ] );
		}
		$activation_result = activate_plugin( $plugin_slug );
		if ( is_wp_error( $activation_result ) ) {
			wp_send_json_error( [ 'message' => 'Plugin activation failed: ' . $activation_result->get_error_message() ] );
		}
		wp_send_json_success( [ 'message' => 'Plugin activated successfully.' ] );
	}


