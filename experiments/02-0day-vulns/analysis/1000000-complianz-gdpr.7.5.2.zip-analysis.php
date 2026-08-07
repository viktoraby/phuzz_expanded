<?php
/***
*
*Found actions: 11
*Found functions:10
*Extracted functions:10
*Total parameter names extracted: 8
*Overview: {'process_ajax_activate_license': {'rsp_upgrade_activate_license'}, 'process_ajax_package_information': {'rsp_upgrade_package_information'}, 'cmplz_rest_api_fallback': {'cmplz_rest_api_fallback'}, 'process_ajax_activate_plugin': {'rsp_upgrade_activate_plugin'}, 'store_console_errors': {'cmplz_store_console_errors'}, 'process_ajax_install_plugin': {'rsp_upgrade_install_plugin'}, 'amp_endpoint': {'cmplz_amp_endpoint', 'nopriv_cmplz_amp_endpoint'}, 'dismiss_warning': {'cmplz_dismiss_admin_notice'}, 'dismiss_review_notice_callback': {'cmplz_dismiss_review_notice'}, 'process_ajax_destination_clear': {'rsp_upgrade_destination_clear'}}
*
***/

/** Function process_ajax_activate_license() called by wp_ajax hooks: {'rsp_upgrade_activate_license'} **/
/** Parameters found in function process_ajax_activate_license(): {"get": ["token", "license", "item_id"]} **/
function process_ajax_activate_license() {
			$error    = false;
			$response = array(
				'success' => false,
				'message' => '',
			);

			if ( ! current_user_can( 'activate_plugins' ) ) {
				$error = true;
			}

			if ( ! $error && isset( $_GET['token'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['token'] ) ), 'upgrade_to_pro_nonce' ) && isset( $_GET['license'] ) && isset( $_GET['item_id'] ) ) {
				$license  = sanitize_title( wp_unslash( $_GET['license'] ) );
				$item_id  = (int) $_GET['item_id'];
				$response = $this->validate( $license, $item_id );
				update_site_option( $this->prefix . 'auto_installed_license', $license );
			}

			wp_send_json( $response );
		}


/** Function process_ajax_package_information() called by wp_ajax hooks: {'rsp_upgrade_package_information'} **/
/** Parameters found in function process_ajax_package_information(): {"get": ["token", "license", "item_id"]} **/
function process_ajax_package_information() {
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return false;
			}

			if ( isset( $_GET['token'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['token'] ) ), 'upgrade_to_pro_nonce' ) && isset( $_GET['license'] ) && isset( $_GET['item_id'] ) ) {
				$api = $this->api_request();
				if ( $api && isset( $api->download_link ) ) {
					$response = array(
						'success'       => true,
						'download_link' => $api->download_link,
					);
				} else {
					$response = array(
						'success'       => false,
						'download_link' => '',
					);
				}
				wp_send_json( $response );

			}
		}


/** Function cmplz_rest_api_fallback() called by wp_ajax hooks: {'cmplz_rest_api_fallback'} **/
/** No params detected :-/ **/


/** Function process_ajax_activate_plugin() called by wp_ajax hooks: {'rsp_upgrade_activate_plugin'} **/
/** Parameters found in function process_ajax_activate_plugin(): {"get": ["token", "plugin"]} **/
function process_ajax_activate_plugin() {
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}

			if ( isset( $_GET['token'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['token'] ) ), 'upgrade_to_pro_nonce' ) && isset( $_GET['plugin'] ) ) {
				$networkwide = is_multisite();
				$result      = activate_plugin( $this->slug, '', $networkwide );
				if ( ! is_wp_error( $result ) ) {
					$response = array(
						'success' => true,
					);
				} else {
					$response = array(
						'success' => false,
					);
				}
				wp_send_json( $response );
			}
		}


/** Function store_console_errors() called by wp_ajax hooks: {'cmplz_store_console_errors'} **/
/** Parameters found in function store_console_errors(): {"get": ["nonce"]} **/
function store_console_errors() {
			if ( ! cmplz_user_can_manage() ) {
				return;
			}

			if ( ! $this->site_needs_cookie_warning() ) {
				return;
			}

			/**
			 * Limit to one request each two minutes.
			 */

			$checked_count = (int) get_transient( 'cmplz_checked_for_js_count' );
			if ( $checked_count > 5 ) {
				return;
			}

			set_transient( 'cmplz_checked_for_js_count', $checked_count + 1, 5 * MINUTE_IN_SECONDS );
			$success = false;
			if ( isset( $_GET['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['nonce'] ) ), 'cmplz-detect-errors' ) ) {
				if ( isset( $_POST['no-errors'] ) ) {
					update_option( 'cmplz_detected_console_errors', false );
					$success = true;
				} else {
					$errors = array();
					foreach ( $_POST as $key => $value ) {
						// Sanitize each value.
						$sanitized_value = sanitize_text_field( $value );

						if ( count( $errors ) > 0 && strpos( $sanitized_value, 'runReadyTrigger' ) === false ) {
							$errors[] = explode( ',', str_replace( site_url(), '', $sanitized_value ) );
							if ( isset( $errors[1] ) && $errors[1] > 1 ) {
								update_option( 'cmplz_detected_console_errors', $errors );
							}
							$success = true;
						}
					}
				}
			}

			wp_send_json( array( 'success' => $success ) );
		}


/** Function process_ajax_install_plugin() called by wp_ajax hooks: {'rsp_upgrade_install_plugin'} **/
/** Parameters found in function process_ajax_install_plugin(): {"get": ["token"]} **/
function process_ajax_install_plugin() {
			$message = '';

			if ( ! current_user_can( 'install_plugins' ) ) {
				return array(
					'success' => false,
					'message' => $message,
				);
			}

			if ( isset( $_GET['token'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['token'] ) ), 'upgrade_to_pro_nonce' ) ) {

				$api = $this->api_request();
				if ( ! $api || empty( $api->download_link ) ) {
					$response = array(
						'success' => false,
						'message' => __( 'Failed to gather package information', 'complianz-gdpr' ),
					);
					wp_send_json( $response );
				}

				$download_link = esc_url_raw( $api->download_link );
				require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
				include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

				$skin     = new WP_Ajax_Upgrader_Skin();
				$upgrader = new Plugin_Upgrader( $skin );
				$result   = $upgrader->install( $download_link );

				if ( $result ) {
					$response = array(
						'success' => true,
					);
				} else {
					if ( is_wp_error( $result ) ) {
						$message = $result->get_error_message();
					}
					$response = array(
						'success' => false,
						'message' => $message,
					);
				}

				wp_send_json( $response );
			}
		}


/** Function amp_endpoint() called by wp_ajax hooks: {'cmplz_amp_endpoint', 'nopriv_cmplz_amp_endpoint'} **/
/** No params detected :-/ **/


/** Function dismiss_warning() called by wp_ajax hooks: {'cmplz_dismiss_admin_notice'} **/
/** Parameters found in function dismiss_warning(): {"post": ["id"]} **/
function dismiss_warning() {
			$error = false;

			if ( ! cmplz_user_can_manage() ) {
				$error = true;
			}

			if ( ! isset( $_POST['id'] ) ) {
				$error = true;
			}

			if ( ! $error ) {
				$warning_id         = sanitize_title( $_POST['id'] );
				$dismissed_warnings = get_option( 'cmplz_dismissed_warnings', array() );
				if ( ! in_array( $warning_id, $dismissed_warnings ) ) {
					$dismissed_warnings[] = $warning_id;
				}
				update_option( 'cmplz_dismissed_warnings', $dismissed_warnings, false );
				delete_transient( 'complianz_warnings' );
				delete_transient( 'complianz_warnings_admin_notices' );
			}

			$out = array(
				'success' => ! $error,
			);

			die( json_encode( $out ) );
		}


/** Function dismiss_review_notice_callback() called by wp_ajax hooks: {'cmplz_dismiss_review_notice'} **/
/** Parameters found in function dismiss_review_notice_callback(): {"post": ["type"]} **/
function dismiss_review_notice_callback() {
			$type = isset( $_POST['type'] ) ? $_POST['type'] : false;

			if ( $type === 'dismiss' ) {
				update_option( 'cmplz_review_notice_shown', true, false );
			}
			if ( $type === 'later' ) {
				// Reset activation timestamp, notice will show again in one month.
				update_option( 'cmplz_activation_time', time(), false );
			}

			wp_die(); // this is required to terminate immediately and return a proper response
		}


/** Function process_ajax_destination_clear() called by wp_ajax hooks: {'rsp_upgrade_destination_clear'} **/
/** Parameters found in function process_ajax_destination_clear(): {"get": ["token", "plugin"]} **/
function process_ajax_destination_clear() {
			$error    = false;
			$response = array(
				'success' => false,
			);

			if ( ! current_user_can( 'activate_plugins' ) ) {
				$error = true;
			}

			if ( ! isset( $_GET['token'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['token'] ) ), 'upgrade_to_pro_nonce' ) ) {
				$error = true;
			}

			if ( ! $error ) {
				if ( defined( $this->plugin_constant ) ) {
					deactivate_plugins( $this->slug );
				}

				$file = trailingslashit( WP_CONTENT_DIR ) . 'plugins/' . $this->slug;
				if ( file_exists( $file ) ) {
					$dir     = dirname( $file );
					$new_dir = $dir . '_' . time();
					set_transient( 'cmplz_upgrade_dir', $new_dir, WEEK_IN_SECONDS );
					rename( $dir, $new_dir );
					// prevent uninstalling code by previous plugin.
					wp_delete_file( trailingslashit( $new_dir ) . 'uninstall.php' );
				}
			}

			if ( ! $error && file_exists( $file ) ) {
				$error    = true;
				$response = array(
					'success' => false,
					'message' => __( 'Could not rename folder!', 'complianz-gdpr' ),
				);
			}

			if ( ! $error && isset( $_GET['token'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['token'] ) ), 'upgrade_to_pro_nonce' ) && isset( $_GET['plugin'] ) ) {
				if ( ! file_exists( WP_PLUGIN_DIR . '/' . $this->slug ) ) {
					$response = array(
						'success' => true,
					);
				}
			}

			wp_send_json( $response );
		}


