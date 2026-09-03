<?php
/***
*
*Found actions: 9
*Found functions:9
*Extracted functions:9
*Total parameter names extracted: 8
*Overview: {'resend_email_code_profile_callback': {'resend_email_code_profile'}, 'process_ajax_destination_clear': {'rsp_upgrade_destination_clear'}, 'rsssl_resend_verification_email': {'rsssl_resend_verification_email'}, 'rsssl_handle_force_confirm_email': {'rsssl_force_confirm_email'}, 'start_email_validation_callback': {'change_method_to_email'}, 'process_ajax_activate_license': {'rsp_upgrade_activate_license'}, 'process_ajax_install_plugin': {'rsp_upgrade_install_plugin'}, 'process_ajax_activate_plugin': {'rsp_upgrade_activate_plugin'}, 'process_ajax_package_information': {'rsp_upgrade_package_information'}}
*
***/

/** Function resend_email_code_profile_callback() called by wp_ajax hooks: {'resend_email_code_profile'} **/
/** Parameters found in function resend_email_code_profile_callback(): {"post": ["login_nonce"]} **/
function resend_email_code_profile_callback(): void
        {
            // Check for nonce (make sure your nonce name and action match what you output to the page)
            if ( ! isset( $_POST['login_nonce'] )
                || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['login_nonce'] ) ), 'update_user_two_fa_settings' ) ) {
                wp_send_json_error( array( 'message' => __( 'Invalid nonce.', 'really-simple-ssl' ) ), 403 );
            }

            // Ensure the user is logged in.
            if ( ! is_user_logged_in() ) {
                wp_send_json_error( array( 'message' => __( 'User not logged in.', 'really-simple-ssl' ) ), 401 );
            }

            // Get the user ID.
            $user_id = get_current_user_id();
            $user = get_user_by( 'ID', $user_id );
            Rsssl_Two_Factor_Email::get_instance()->generate_and_email_token($user, true);
            wp_send_json_success( array( 'message' => __('Verification code re-sent', 'really-simple-ssl') ), 200 );
        }


/** Function process_ajax_destination_clear() called by wp_ajax hooks: {'rsp_upgrade_destination_clear'} **/
/** Parameters found in function process_ajax_destination_clear(): {"get": ["token", "plugin"]} **/
function process_ajax_destination_clear()
        {
            $error = false;
            $response = [
                'success' => false,
            ];

            if ( !rsssl_user_can_manage() ) {
                $error = true;
            }

            if ( !isset($_GET['token']) || !wp_verify_nonce($_GET['token'], 'upgrade_to_pro_nonce')) {
                $error = true;
            }

            if ( ! current_user_can( 'activate_plugins' ) ) {
                $error = true;
            }

            if (empty($this->slug)) {
                $error = true;
                $response['message'] = esc_html__('Unknown plugin encountered.', 'really-simple-ssl');
            }

            if (!$error) {
                if ( defined( $this->plugin_constant ) ) {
                    deactivate_plugins( $this->slug );
                }

                $file = trailingslashit( WP_CONTENT_DIR ) . 'plugins/' . $this->slug;
                if ( file_exists( $file ) ) {
                    $dir     = dirname( $file );
                    $new_dir = $dir . '_' . time();
                    set_transient( 'rsssl_upgrade_dir', $new_dir, WEEK_IN_SECONDS );
                    rename( $dir, $new_dir );
                    //prevent uninstalling code by previous plugin
                    unlink( trailingslashit( $new_dir ) . 'uninstall.php' );
                }
            }

            if ( !$error && file_exists($file ) ) {
                $error = true;
                $response = [
                    'success' => false,
                    'message' => __("Could not rename folder!", "really-simple-ssl"),
                ];
            }

            if ( !$error && isset($_GET['plugin']) ) {
                if ( !file_exists(WP_PLUGIN_DIR . '/' . $this->slug) ) {
                    $response = [
                        'success' => true,
                    ];
                }
            }

            $response = json_encode($response);
            header("Content-Type: application/json");
            echo $response;
            exit;
        }


/** Function rsssl_resend_verification_email() called by wp_ajax hooks: {'rsssl_resend_verification_email'} **/
/** Parameters found in function rsssl_resend_verification_email(): {"post": ["rsssl_resend_email_nonce"]} **/
function rsssl_resend_verification_email() {
        if ( ! rsssl_user_can_manage() ) {
            return;
        }

        if ( ! isset( $_POST['rsssl_resend_email_nonce'] ) || ! wp_verify_nonce( $_POST['rsssl_resend_email_nonce'], 'rsssl_resend_verification_email_nonce' ) ) {
            wp_die();
        }

        $mailer = new rsssl_mailer();
        $mailer->send_verification_mail();

        wp_send_json_success();
    }


/** Function rsssl_handle_force_confirm_email() called by wp_ajax hooks: {'rsssl_force_confirm_email'} **/
/** Parameters found in function rsssl_handle_force_confirm_email(): {"post": ["rsssl_force_email_action_nonce"]} **/
function rsssl_handle_force_confirm_email(): void {
		if ( ! rsssl_user_can_manage() ) {
			return;
		}

		if ( ! isset( $_POST['rsssl_force_email_action_nonce'] ) || ! wp_verify_nonce( $_POST['rsssl_force_email_action_nonce'], 'rsssl_force_confirm_email_nonce' ) ) {
			wp_die();
		}

		update_option( 'rsssl_email_verification_status', 'completed', false );
		wp_send_json_success();
	}


/** Function start_email_validation_callback() called by wp_ajax hooks: {'change_method_to_email'} **/
/** No params detected :-/ **/


/** Function process_ajax_activate_license() called by wp_ajax hooks: {'rsp_upgrade_activate_license'} **/
/** Parameters found in function process_ajax_activate_license(): {"get": ["token", "license", "item_id"]} **/
function process_ajax_activate_license()
        {
            $error = false;
            $response = [
                'success' => false,
                'message' => '',
            ];

            if ( !rsssl_user_can_manage() ) {
                $error = true;
            }

            if (!$error && isset($_GET['token']) && wp_verify_nonce($_GET['token'], 'upgrade_to_pro_nonce') && isset($_GET['license']) && isset($_GET['item_id']) ) {
                $license  = sanitize_title($_GET['license']);
                $item_id = (int) $_GET['item_id'];
                $response = $this->validate($license, $item_id);
                update_site_option($this->prefix.'auto_installed_license', $license);
            }

            $response = json_encode($response);
            header("Content-Type: application/json");
            echo $response;
            exit;
        }


/** Function process_ajax_install_plugin() called by wp_ajax hooks: {'rsp_upgrade_install_plugin'} **/
/** Parameters found in function process_ajax_install_plugin(): {"get": ["token", "download_link"]} **/
function process_ajax_install_plugin()
        {
            $message = '';

            if ( !rsssl_user_can_manage() ) {
                return [
                    'success' => false,
                    'message' => $message,
                ];
            }

            if ( isset($_GET['token']) && wp_verify_nonce($_GET['token'], 'upgrade_to_pro_nonce') && isset($_GET['download_link']) ) {

                $download_link = esc_url_raw($_GET['download_link']);
                require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
                include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

                $skin     = new WP_Ajax_Upgrader_Skin();
                $upgrader = new Plugin_Upgrader( $skin );
                $result   = $upgrader->install( $download_link );

                if ( $result ) {
                    $response = [
                        'success' => true,
                    ];
                } else {
                    if ( is_wp_error($result) ){
                        $message = $result->get_error_message();
                    }
                    $response = [
                        'success' => false,
                        'message' => $message,
                    ];
                }

                $response = json_encode($response);
                header("Content-Type: application/json");
                echo $response;
                exit;
            }
        }


/** Function process_ajax_activate_plugin() called by wp_ajax hooks: {'rsp_upgrade_activate_plugin'} **/
/** Parameters found in function process_ajax_activate_plugin(): {"get": ["token", "plugin"]} **/
function process_ajax_activate_plugin()
        {
            if ( !rsssl_user_can_manage() ) {
                return;
            }

            if ( isset($_GET['token']) && wp_verify_nonce($_GET['token'], 'upgrade_to_pro_nonce') && isset($_GET['plugin']) ) {
                $networkwide = is_multisite() && rsssl_is_networkwide_active();
                $result = activate_plugin( $this->slug, '', $networkwide  );
                if ( !is_wp_error($result) ) {
                    $response = [
                        'success' => true,
                    ];
                } else {
                    $response = [
                        'success' => false,
                    ];
                }
                $response = json_encode($response);
                header("Content-Type: application/json");
                echo $response;
                exit;
            }
        }


/** Function process_ajax_package_information() called by wp_ajax hooks: {'rsp_upgrade_package_information'} **/
/** Parameters found in function process_ajax_package_information(): {"get": ["token", "license", "item_id"]} **/
function process_ajax_package_information()
        {
            if ( !rsssl_user_can_manage() ) {
                return false;
            }

            if ( isset($_GET['token']) && wp_verify_nonce($_GET['token'], 'upgrade_to_pro_nonce') && isset($_GET['license']) && isset($_GET['item_id']) ) {
                $api = $this->api_request();
                if ( $api && isset($api->download_link) ) {
                    $response = [
                        'success' => true,
                        'download_link' => $api->download_link,
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'download_link' => "",
                    ];
                }
                $response = json_encode($response);
                header("Content-Type: application/json");
                echo $response;
                exit;

            }
        }


