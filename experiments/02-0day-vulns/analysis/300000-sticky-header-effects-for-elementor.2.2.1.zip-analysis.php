<?php
/***
*
*Found actions: 7
*Found functions:6
*Extracted functions:6
*Total parameter names extracted: 2
*Overview: {'she_check_plugin_status': {'she_check_plugin_status'}, 'dismiss': {'she_dismiss_pro_launch_notice', 'she_dismiss_join_community_notice'}, 'she_nexter_extension_dismiss_promo': {'she_nexter_extension_dismiss_promo'}, 'she_install_wdkit': {'she_install_wdkit'}, 'she_dashboard_ajax_call': {'she_dashboard_ajax_call'}, 'she_design_scratch': {'she_insert_entry'}}
*
***/

/** Function she_check_plugin_status() called by wp_ajax hooks: {'she_check_plugin_status'} **/
/** No params detected :-/ **/


/** Function dismiss() called by wp_ajax hooks: {'she_dismiss_pro_launch_notice', 'she_dismiss_join_community_notice'} **/
/** Parameters found in function dismiss(): {"post": ["security"]} **/
function dismiss() {
			$security = ! empty( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '';

			if ( empty( $security ) || ! wp_verify_nonce( $security, 'she_join_community_notice' ) ) {
				wp_send_json_error( __( 'Security check failed.', 'she-header' ) );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'You are not allowed to do this action', 'she-header' ) );
			}

			update_option( 'she_join_community_notice', true );

			wp_send_json_success();
		}


/** Function she_nexter_extension_dismiss_promo() called by wp_ajax hooks: {'she_nexter_extension_dismiss_promo'} **/
/** No params detected :-/ **/


/** Function she_install_wdkit() called by wp_ajax hooks: {'she_install_wdkit'} **/
/** No params detected :-/ **/


/** Function she_dashboard_ajax_call() called by wp_ajax hooks: {'she_dashboard_ajax_call'} **/
/** Parameters found in function she_dashboard_ajax_call(): {"post": ["type"]} **/
function she_dashboard_ajax_call() {

			if ( ! check_ajax_referer( 'she-db-nonce', 'nonce', false ) ) {

				$response = $this->she_set_response( false, 'Permission denied.', 'You do not have permission to perform this action.' );

				wp_send_json( $response );
				wp_die();
			}

			if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
				$response = $this->she_set_response( false, 'Invalid Permission.', 'Something went wrong.' );

				wp_send_json( $response );
				wp_die();
			}

			$type = isset( $_POST['type'] ) ? strtolower( sanitize_text_field( wp_unslash( $_POST['type'] ) ) ) : false;
			if ( ! $type ) {
				$response = $this->she_set_response( false, 'Invalid type.', 'Something went wrong.' );

				wp_send_json( $response );
				wp_die();
			}

			switch ( $type ) {
				case 'shed_onload_data':
					$response = $this->shed_onload_data();
					break;
				case 'she_plugin_install':
					$response = $this->she_plugin_install();
					break;
				case 'she_theme_install':
					$response = $this->she_theme_install();
					break;
				case 'she_activate_theme':
					$response = $this->she_activate_theme();
					break;
				case 'she_prev_version':
					$response = $this->she_prev_version();
					break;
				case 'she_rollback_check':
					$response = $this->she_rollback_check();
					break;
				case 'she_api_call':
					$response = $this->she_api_call();
					break;
				case 'she_create_page':
					$response = $this->she_create_page();
					break;
				case 'she_onboarding_setup':
					$response = $this->she_onboarding_setup();
					break;
				case 'she_user_meta_data':
					$response = $this->she_user_meta_data();
					break;
				case 'she_analytics_consent':
					$response = $this->she_analytics_consent();
					break;
				default:
					$response = $this->she_set_response( false, 'Invalid type.', 'Something went wrong.' );
					break;
			}

			wp_send_json( $response );
			wp_die();
		}


/** Function she_design_scratch() called by wp_ajax hooks: {'she_insert_entry'} **/
/** No params detected :-/ **/


