<?php
/***
*
*Found actions: 27
*Found functions:22
*Extracted functions:21
*Total parameter names extracted: 22
*Overview: {'ajax_import_data': {'ep_elementor_demo_importer_data_import'}, 'countdown_end': {'nopriv_element_pack_countdown_end', 'element_pack_countdown_end'}, 'element_pack_ajax_login': {'nopriv_element_pack_ajax_login'}, 'rc_sdk_dismiss_biggopti': {'bdt_rc_sdk_dismiss_biggopti'}, 'element_pack_settings_save': {'element_pack_settings_save'}, 'get_import_progress': {'bdt_element_pack_template_import_progress'}, 'rc_sdk_insights': {'bdt_rc_sdk_insights'}, 'contact_form': {'nopriv_element_pack_contact_form', 'element_pack_contact_form'}, 'ep_setup_wizard_nonce': {'ep_setup_wizard_import_template', 'ep_setup_wizard_import_bundle_runner', 'ep_setup_wizard_import_bundle'}, 'dismiss': {'element_pack_biggopti'}, 'install_plugins': {'ep_setup_wizard_install_plugins'}, 'sync_demo_with_server': {'ep_elementor_demo_importer_data_sync_demo_with_server'}, 'element_pack_ajax_search': {'element_pack_search', 'nopriv_element_pack_search'}, 'element_pack_do_register_user': {'nopriv_element_pack_ajax_register'}, 'getSelectInputData': {'elementpack_dynamic_select_input_data'}, 'send_report': {'ep_elementor_demo_importer_send_report'}, 'sync_now': {'bdt_element_pack_template_library_making_syncing'}, 'ajax_get_plugins': {'ep_get_plugins'}, 'get_layouts': {'bdt_element_pack_template_library_get_layouts'}, 'ajax_fetch_api_biggopti': {'ep_fetch_api_biggopti'}, 'demo_tab_ajax_loading_demo': {'ep_elementor_demo_importer_data_loading'}, 'install_plugin_ajax': {'ep_install_plugin'}}
*
***/

/** Function ajax_import_data() called by wp_ajax hooks: {'ep_elementor_demo_importer_data_import'} **/
/** Parameters found in function ajax_import_data(): {"request": ["demo_url", "demo_id", "page_title", "default_page_title", "demo_import_type"]} **/
function ajax_import_data() {

        $this->verify_ajax_access();

        // Nonce and capability are verified by verify_ajax_access() above.
        // phpcs:disable WordPress.Security.NonceVerification.Recommended -- Verified in verify_ajax_access().
        if ( isset( $_REQUEST ) ) {
            $demo_url         = isset($_REQUEST['demo_url']) ? esc_url_raw(wp_unslash($_REQUEST['demo_url'])) : '';
            $demo_id          = isset($_REQUEST['demo_id']) ? absint($_REQUEST['demo_id']) : 0;
            $page_title       = isset($_REQUEST['page_title']) ? sanitize_text_field(wp_unslash($_REQUEST['page_title'])) : '';
            $defaultPageTitle = isset($_REQUEST['default_page_title']) ? sanitize_text_field(wp_unslash($_REQUEST['default_page_title'])) : '';
            $importType       = isset($_REQUEST['demo_import_type']) ? sanitize_text_field(wp_unslash($_REQUEST['demo_import_type'])) : '';
            // phpcs:enable WordPress.Security.NonceVerification.Recommended

            $response_data = $this->templates_get_content_remote_request( $demo_url );
            $sourceData    = "";
            if ( is_array( $response_data ) ) {
                $manager    = new Source_Local();
                $sourceData = $manager->import_template( 'test.json', $demo_url );
            }

            if ( ! is_array( $response_data ) || ! is_array( $sourceData ) ) {
                echo wp_json_encode(
                    array(
                        'success' => false,
                        'id'      => '',
                        'edittxt' => esc_html__( 'Fail to upload. Try again.', 'bdthemes-element-pack-lite' )
                    )
                );
                wp_die();
            }

            if ( is_array( $sourceData ) && count( $sourceData ) == 1 && isset( $sourceData[0]['template_id'] ) && $sourceData[0]['template_id'] > 1 ) {
                $template_id = $sourceData[0]['template_id'];
                if ( $importType == 'page' ) {
                    $metaData = get_post_meta( $template_id );
                    if ( isset( $metaData['_elementor_data'] ) && isset( $metaData['_elementor_data'][0]  ) ) {

                        $_elementor_data          = wp_slash( $metaData['_elementor_data'][0] );

                        $defaulttitle = ( ! empty( $page_title ) ) ? $page_title : $defaultPageTitle;

                        $args = [
                            'post_type'    => 'page',
                            'post_status'  => empty( $page_title ) ? 'draft' : 'publish',
                            'post_title'   => ! empty( $page_title ) ? $page_title : $defaulttitle,
                            'post_content' => '',
                        ];

                        $new_post_id = wp_insert_post( $args );
                        update_post_meta( $new_post_id, '_elementor_data', $_elementor_data );
                        if(isset($metaData['_elementor_page_settings']) && isset($metaData['_elementor_page_settings'][0])){
                            $_elementor_page_settings = is_serialized( $metaData['_elementor_page_settings'][0] )
                                ? unserialize( $metaData['_elementor_page_settings'][0], array( 'allowed_classes' => false ) )
                                : $metaData['_elementor_page_settings'][0];
                            update_post_meta( $new_post_id, '_elementor_page_settings', $_elementor_page_settings );
                        }
                        update_post_meta( $new_post_id, '_elementor_template_type', $response_data['type'] );
                        update_post_meta( $new_post_id, '_elementor_edit_mode', 'builder' );

                        if ( $new_post_id && ! is_wp_error( $new_post_id ) ) {
                            update_post_meta( $new_post_id, '_wp_page_template', ! empty( $response_data['page_template'] ) ? $response_data['page_template'] : 'elementor_header_footer' );
                        }

                        echo wp_json_encode(
                            array(
                                'success' => true,
                                'id'      => $new_post_id,
                                'edittxt' => ( $importType == 'page' ) ? esc_html__( 'Edit Page', 'bdthemes-element-pack-lite' ) : esc_html__( 'Edit Template', 'bdthemes-element-pack-lite' )
                            )
                        );
                        wp_die();
                    }
                } else {

                    echo wp_json_encode(
                        array(
                            'success' => true,
                            'id'      => $template_id,
                            'edittxt' => esc_html__( 'Edit Template', 'bdthemes-element-pack-lite' )
                        )
                    );
                    wp_die();
                }
            }
        }

        echo wp_json_encode(
            array(
                'success' => false,
                'id'      => '',
                'edittxt' => esc_html__( 'Fail to upload. Try again', 'bdthemes-element-pack-lite' )
            )
        );
        wp_die();
    }


/** Function countdown_end() called by wp_ajax hooks: {'nopriv_element_pack_countdown_end', 'element_pack_countdown_end'} **/
/** Parameters found in function countdown_end(): {"post": ["nonce", "endTime"]} **/
function countdown_end(){
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'element-pack-site' ) ) {
			wp_die( 'Security check failed' );
		}

		$wp_current_time = current_time( 'timestamp' ) - ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
		$end_time = isset($_POST['endTime']) ? (int) $_POST['endTime'] : 0;

		if( $wp_current_time > $end_time ){
			echo "ended";
		}
		wp_die();

	}


/** Function element_pack_ajax_login() called by wp_ajax hooks: {'nopriv_element_pack_ajax_login'} **/
/** Parameters found in function element_pack_ajax_login(): {"post": ["user_login", "pwd", "user_password", "rememberme"]} **/
function element_pack_ajax_login() {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-login-nonce', 'bdt-user-login-sc');

        // Nonce is checked, get the POST data and sign user on
        $access_info                  = [];
        $access_info['user_login']    = !empty($_POST['user_login']) ? sanitize_text_field(wp_unslash($_POST['user_login'])) : "";
        // The password is deliberately passed through untouched: unslashing or
        // sanitizing it would corrupt legitimate credentials. This mirrors how
        // wp_signon() reads $_POST['pwd'] in wp-login.php.
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Raw password required by wp_signon().
        $access_info['user_password'] = !empty($_POST['user_password']) ? $_POST['user_password'] : "";
        $access_info['remember']      = !empty($_POST['rememberme']) ? true : false;
        $user_signon                  = wp_signon($access_info, false);

        if (!is_wp_error($user_signon)) {
            echo wp_json_encode(
                [
                    'loggedin' => true,
                    'message'  => esc_html_x('Login successful, Redirecting...', 'User Login and Register', 'bdthemes-element-pack-lite')
                ]
            );
        } else {
            echo wp_json_encode(
                [
                    'loggedin' => false,
                    'message'  => esc_html_x('Oops! Wrong username or password!', 'User Login and Register', 'bdthemes-element-pack-lite')
                ]
            );
        }

        die();
    }


/** Function rc_sdk_dismiss_biggopti() called by wp_ajax hooks: {'bdt_rc_sdk_dismiss_biggopti'} **/
/** Parameters found in function rc_sdk_dismiss_biggopti(): {"post": ["nonce", "rc_name"]} **/
function rc_sdk_dismiss_biggopti() {
			$nonce   = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
			$rc_name = isset( $_POST['rc_name'] ) ? sanitize_text_field( wp_unslash( $_POST['rc_name'] ) ) : '';

			if ( ! wp_verify_nonce( $nonce, 'rc_sdk' ) ) {
				wp_send_json( array(
					'status'  => 'error',
					'title'   => 'Error',
					'message' => 'Nonce verification failed',
				) );
				wp_die();
			}

			if ( !current_user_can('manage_options') ) {
				wp_send_json(array(
					'status'	=> 'error',
					'title'		=> 'Error',
					'message'	=> 'Denied, you don\'t have right permission',
				));
				wp_die();
			}

			set_transient( 'dismissed_biggopti_' . $rc_name, true, 30 * DAY_IN_SECONDS );

			wp_send_json( array(
				'status'  => 'success',
				'title'   => 'Success',
				'message' => 'Success.',
			) );
			wp_die();
		}


/** Function element_pack_settings_save() called by wp_ajax hooks: {'element_pack_settings_save'} **/
/** Parameters found in function element_pack_settings_save(): {"post": ["id"]} **/
function element_pack_settings_save() {

            if (!check_ajax_referer('element-pack-settings-save-nonce')) {
                wp_send_json_error();
            }

            if (!current_user_can('manage_options')) {
                return;
            }

            $moudle_id = isset($_POST['id']) ? sanitize_text_field(wp_unslash($_POST['id'])) : '';

            unset($_POST['id']);

            // Only ever write options inside this plugin's own namespace. Without
            // this the option name was fully attacker-chosen, letting a request
            // overwrite arbitrary core options (default_role, siteurl, ...).
            if ('' === $moudle_id || 0 !== strpos($moudle_id, 'element_pack')) {
                wp_send_json_error();
            }

            if (isset($_POST[$moudle_id])) {
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized on the next statement.
                $raw_value = wp_unslash($_POST[$moudle_id]);

                // Route the value through the registered per-field sanitizers
                // instead of storing raw request data.
                $value = is_array($raw_value)
                    ? $this->sanitize_options($raw_value)
                    : sanitize_text_field($raw_value);

                update_option($moudle_id, $value);
            }

            if (element_pack_is_asset_optimization_enabled()) {
                $optimize_assets = new Asset_Minifier();
                $optimize_assets->minifyCss();
                $optimize_assets->minifyJs();
                update_option('element-pack-minified-asset-manager-version', time());
            } else {
                delete_option('element-pack-minified-asset-manager-version');
            }

            wp_send_json_success();
        }


/** Function get_import_progress() called by wp_ajax hooks: {'bdt_element_pack_template_import_progress'} **/
/** Parameters found in function get_import_progress(): {"request": ["import_id"]} **/
function get_import_progress() {
        check_ajax_referer( 'ep-template-library-progress', 'nonce' );

        // Mirrors the capability the import itself requires.
        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Access Denied', 'bdthemes-element-pack-lite' ) ], 403 );
        }

        $import_id = isset( $_REQUEST['import_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['import_id'] ) ) : '';
        $state     = Import_Progress::read( $import_id );

        if ( ! is_array( $state ) ) {
            // The import request hasn't written its first update yet.
            wp_send_json_success( [ 'stage' => 'pending', 'percent' => 0 ] );
        }

        wp_send_json_success( $state );
    }


/** Function rc_sdk_insights() called by wp_ajax hooks: {'bdt_rc_sdk_insights'} **/
/** Parameters found in function rc_sdk_insights(): {"post": ["button_val", "nonce", "allow_name", "date_name"]} **/
function rc_sdk_insights() {
			$sanitized_status = isset( $_POST['button_val'] ) ? sanitize_text_field( wp_unslash( $_POST['button_val'] ) ) : '';
			$nonce            = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
			$allow_name       = isset( $_POST['allow_name'] ) ? sanitize_key( wp_unslash( $_POST['allow_name'] ) ) : '';
			$date_name        = isset( $_POST['date_name'] ) ? sanitize_key( wp_unslash( $_POST['date_name'] ) ) : '';

			// Only this collector's own options may be written, never an arbitrary one.
			if ( ! preg_match( '/^rc_allow_[a-z0-9_]+$/', $allow_name ) || ! preg_match( '/^rc_date_[a-z0-9_]+$/', $date_name ) ) {
				wp_send_json( array(
					'status'  => 'error',
					'title'   => 'Error',
					'message' => 'Invalid option name',
				) );
				wp_die();
			}

			if ( ! wp_verify_nonce( $nonce, 'rc_sdk' ) ) {
				wp_send_json( array(
					'status'  => 'error',
					'title'   => 'Error',
					'message' => 'Nonce verification failed',
				) );
				wp_die();
			}

			if ( ! current_user_can('manage_options') ) {
				wp_send_json(array(
					'status'	=> 'error',
					'title'		=> 'Error',
					'message'	=> 'Denied, you don\'t have right permission',
				));
				wp_die();
			}

			if ( 'disallow' == $sanitized_status ) {
				update_option( $allow_name, 'disallow' );
			}

			if ( $sanitized_status == 'skip' ) {
				update_option( $allow_name, 'skip' );
				/**
				 * Next schedule date for attempt
				 */
				update_option( $date_name, gmdate( 'Y-m-d', strtotime( "+1 month" ) ) );
			} elseif ( $sanitized_status == 'yes' ) {
				update_option( $allow_name, 'yes' );
			}

			wp_send_json( array(
				'status'  => 'success',
				'title'   => 'Success',
				'message' => 'Success.',
				'action'  => $sanitized_status,
			) );
			wp_die();
		}


/** Function contact_form() called by wp_ajax hooks: {'nopriv_element_pack_contact_form', 'element_pack_contact_form'} **/
/** Parameters found in function contact_form(): {"server": ["REQUEST_METHOD"], "request": ["_wpnonce", "page_id", "widget_id"], "post": ["custom_success_message"]} **/
function contact_form() {

        $email               = get_bloginfo('admin_email');
        $error_empty         = esc_html__('Please fill in all the required fields.', 'bdthemes-element-pack-lite');
        $error_noemail       = esc_html__('Please enter a valid e-mail.', 'bdthemes-element-pack-lite');
        $error_same_as_admin = esc_html__('You can not use this e-mail due to security issues.', 'bdthemes-element-pack-lite');
        $error_spam_email    = esc_html__('You are trying to send e-mail by banned e-mail. Multiple tries can ban you permanently!', 'bdthemes-element-pack-lite');
        $result              = esc_html__('Unknown error! Please check your settings.', 'bdthemes-element-pack-lite');
        $ep_api_settings     = get_option('element_pack_api_settings');
        $api_settings        = get_option('element_pack_api_settings');;

        if (!empty($ep_api_settings['contact_form_email'])) {
            $email = $ep_api_settings['contact_form_email'];
        }

        if ( isset( $_SERVER['REQUEST_METHOD'] ) && $_SERVER['REQUEST_METHOD'] == 'POST' ) {

            if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce( sanitize_key( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'simpleContactForm')) {
                $result = esc_html__('Security check failed!', 'bdthemes-element-pack-lite');
                echo '<span class="bdt-text-warning">' . esc_html($result) . '</span>';
                wp_die();
            }

            $post_id   = isset( $_REQUEST['page_id'] ) ? absint($_REQUEST['page_id']) : 0;
            $widget_id = isset( $_REQUEST['widget_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['widget_id'] ) ) : 0;

            $error = false;

            // this part fetches everything that has been POSTed, sanitizes them and lets us use them as $form_data['subject']
            foreach ($_POST as $field => $value) {
                if (is_email($value)) {
                    $value = sanitize_email($value);
                } elseif (in_array($field, ['name', 'subject', 'contact'])) {
                    // Use sanitize_text_field for single-line fields to prevent header injection
                    $value = sanitize_text_field($value);
                } else {
                    $value = sanitize_textarea_field($value);
                }

                $form_data[$field] = wp_strip_all_tags($value);
            }

            // Get widget settings to check if contact number is required
            $widget_settings = $this->get_widget_settings($post_id, $widget_id);
            $contact_required = isset($widget_settings['contact_number_required']) && $widget_settings['contact_number_required'] === 'yes';

            // Read the Receive IP Information toggle from the stored widget settings rather
            // than a hidden form field, so a submitter cannot suppress their own IP by
            // tampering with the POST. Widgets saved before this control existed carry no
            // value, so default to including it and keep the previous behaviour.
            $receive_ip_information = true;
            if (is_array($widget_settings) && isset($widget_settings['receive_ip_information'])) {
                $receive_ip_information = ('yes' === $widget_settings['receive_ip_information']);
            }

            foreach ($form_data as $key => $value) {
                $value = trim($value);
                // Skip validation for contact field if it's optional and empty
                if ($key === 'contact' && !$contact_required && empty($value)) {
                    continue;
                }
                if (empty($value)) {
                    $error  = true;
                    $result = $error_empty;
                }
            }
       
            // Success message part
            if (!empty($_POST['custom_success_message'])) { 
                $custom_message = sanitize_text_field( wp_unslash( $_POST['custom_success_message'] ) );

                $placeholders = ['[name]', '[email]'];
                $replacements = [esc_html($form_data['name']), esc_html($form_data['email'])];
                $custom_message = str_replace($placeholders, $replacements, $custom_message);

                $success = (strlen($custom_message) <= 255) 
                    ? $custom_message 
                    : esc_html__('Invalid length of custom success message.', 'bdthemes-element-pack-lite');
            } else {
                $success = sprintf(
                    /* translators: %s: The name submitted in the contact form */
                    esc_html__('Hi, %s. We got your e-mail. We\'ll reply to you very soon. Thanks for being with us...', 'bdthemes-element-pack-lite'),
                    esc_html($form_data['name'])
                );
            }


            // and if the e-mail is not valid, switch $error to TRUE and set the result text to the shortcode attribute named 'error_noemail'
            if (!is_email($form_data['email'])) {
                $error  = true;
                $result = $error_noemail;
            }

            /**
             * Stop spamming
             */
            if (!$error) {
                $admin_email = get_option('admin_email');
                if ( $this->are_emails_same( wp_kses_post( trim( $form_data['email'] ) ), $admin_email ) || $admin_email == wp_kses_post(trim($form_data['email'])) || $email == wp_kses_post(trim($form_data['email']))) {
                    $error  = true;
                    $result = $error_same_as_admin;
                } else {
                    if (isset($api_settings['contact_form_spam_email'])) {
                        $spam_email_list = $api_settings['contact_form_spam_email'];
                        $final_spam_list = explode(',', $spam_email_list);
                        foreach ($final_spam_list as $spam_email) {
                            if (trim($form_data['email']) == trim($spam_email)) {
                                $error  = true;
                                $result = $error_spam_email;
                                break;
                            }
                        }
                    }
                }
            }

            /** Recaptcha*/
            if ($this->is_recaptcha_required($post_id, $widget_id, 'show_recaptcha')) {
                if (!empty($ep_api_settings['recaptcha_site_key']) and !empty($ep_api_settings['recaptcha_secret_key'])) {
                    if (!$this->is_valid_captcha()) {
                        $error  = true;
                        $result = esc_html__("reCAPTCHA is invalid!", "bdthemes-element-pack-lite");
                    }
                } else {
                    $error  = true;
                    $result = esc_html__("reCAPTCHA API keys are not set properly! Go to the Element Pack API settings page to configure them.", "bdthemes-element-pack-lite");
                }
            }


            $contact_number  = isset($form_data['contact']) ? esc_attr($form_data['contact']) : '';
            $contact_subject = isset($form_data['subject']) ? esc_attr($form_data['subject']) : '';

            // but if $error is still FALSE, put together the POSTed variables and send the e-mail!
            if ($error == false) {
                // get the website's name and puts it in front of the subject
                $email_subject = "[" . get_bloginfo('name') . "] " . $contact_subject;
                // get the message from the form and add the IP address of the user below it
                $email_message = $this->message_html($form_data['message'], $form_data['name'], $form_data['email'], $contact_number, $receive_ip_information);
                // set the e-mail headers with the user's name, e-mail address and character encoding
                // Explicitly remove newlines to prevent header injection
                $safe_name = str_replace(["\r", "\n"], '', $form_data['name']);
                $headers = "Reply-To: " . $safe_name . " <" . $form_data['email'] . ">\n";
                $headers .= "Content-Type: text/html; charset=UTF-8\n";
                $headers .= "Content-Transfer-Encoding: 8bit\n";
                // send the e-mail with the shortcode attribute named 'email' and the POSTed data
                wp_mail($email, html_entity_decode($email_subject), $email_message, $headers);
                // and set the result text to the shortcode attribute named 'success'
                $result = $success;
                // ...and switch the $sent variable to TRUE
                $sent = true;
            }


            $redirect_url = (isset($form_data['redirect-url']) && !empty($form_data['redirect-url'])) ? esc_url($form_data['redirect-url']) : 'no';
            $is_external  = (isset($form_data['is-external']) && !empty($form_data['is-external'])) ? esc_attr($form_data['is-external']) : 'no';

            $reset_status = (isset($form_data['reset-after-submit']) && ($form_data['reset-after-submit'] == 'yes')) ? 'yes' : 'no';

            if ($error == false) {
                echo '<span class="bdt-text-success" data-resetstatus="' . esc_html($reset_status) . '"  data-redirect="' . wp_kses_post($redirect_url) . '" data-external="' . esc_attr($is_external) . '">' . esc_html($result) . '</span>';
                // wp_redirect( $form_data['redirect_url'] );
            } else {
                echo '<span class="bdt-text-warning">' . esc_html($result) . '</span>';
            }
        }

        die;
    }


/** Function ep_setup_wizard_nonce() called by wp_ajax hooks: {'ep_setup_wizard_import_template', 'ep_setup_wizard_import_bundle_runner', 'ep_setup_wizard_import_bundle'} **/
/** No function found :-/ **/


/** Function dismiss() called by wp_ajax hooks: {'element_pack_biggopti'} **/
/** Parameters found in function dismiss(): {"post": ["_wpnonce", "id", "time", "meta"]} **/
function dismiss() {
		$nonce = (isset($_POST['_wpnonce'])) ? sanitize_text_field(wp_unslash($_POST['_wpnonce'])) : '';
		$id   = (isset($_POST['id'])) ? sanitize_text_field(wp_unslash($_POST['id'])) : '';
		$time = (isset($_POST['time'])) ? sanitize_text_field( wp_unslash( $_POST['time'] ) ) : '';
		$meta = (isset($_POST['meta'])) ? sanitize_text_field( wp_unslash( $_POST['meta'] ) ) : '';

		if ( ! wp_verify_nonce($nonce, 'element-pack') ) {
			wp_send_json_error();
		}

		if ( ! current_user_can('manage_options') ) {
			wp_send_json_error();
		}

		/**
		 * Valid inputs?
		 */
		if (!empty($id)) {
			// Handle regular biggopti
			if ('user' === $meta) {
				update_user_meta(get_current_user_id(), $id, true);
			} else {
				// Store in transient for backward compatibility
				set_transient($id, true, $time);
				
				// Also store in options table for persistence
				$dismissals_option = get_option('bdt_biggopti_dismissals', []);
				$dismissals_option[$id] = [
					'dismissed_at' => time(),
					'expires_at' => time() + intval($time),
				];
				update_option('bdt_biggopti_dismissals', $dismissals_option, false);
			}

			wp_send_json_success();
		}

		wp_send_json_error();
	}


/** Function install_plugins() called by wp_ajax hooks: {'ep_setup_wizard_install_plugins'} **/
/** Parameters found in function install_plugins(): {"post": ["plugins"]} **/
function install_plugins() {
		check_ajax_referer( 'ep_setup_wizard_nonce', 'nonce' );

		// Installing and activating are separate capabilities; this endpoint does both.
		if ( ! current_user_can( 'install_plugins' ) || ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error( array( 'message' => 'Unauthorized' ) );
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Each entry is sanitized through sanitize_plugin_basename() below.
		$raw_slugs = isset( $_POST['plugins'] ) ? wp_unslash( $_POST['plugins'] ) : array();

		if ( empty( $raw_slugs ) || ! is_array( $raw_slugs ) ) {
			wp_send_json_error( array( 'message' => 'Invalid plugins array' ) );
		}

		$plugin_slugs = array();
		foreach ( $raw_slugs as $raw_slug ) {
			$clean_slug = $this->sanitize_plugin_basename( $raw_slug );

			if ( '' !== $clean_slug ) {
				$plugin_slugs[] = $clean_slug;
			}
		}

		if ( empty( $plugin_slugs ) ) {
			wp_send_json_error( array( 'message' => 'Invalid plugins array' ) );
		}

		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader-skin.php';
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		// Replace new \Plugin_Installer_Skin with new Quiet_Upgrader_Skin when output needs to be suppressed.
		$skin = new Quiet_Upgrader_Skin();
		// $skin     = new \Plugin_Installer_Skin( array( 'api' => $api ) );
		$upgrader = new \Plugin_Upgrader( $skin );

		// $upgrader = new \Plugin_Upgrader();

        $installedPlugins = get_plugins();
		$results = array();

		foreach ( $plugin_slugs as $plugin_slug ) {
            // skip when the plugin is already active
            if (is_plugin_active($plugin_slug)) {
                $results[] = array(
                    'slug'    => $plugin_slug,
                    'success' => true,
                    'message' => 'Installed and activated successfully',
                );
                continue;
            }

            // Download the plugin if the plugin is not installed
            if (!isset($installedPlugins[$plugin_slug])) {
                $slug = explode('/', $plugin_slug)[0];
                $api = plugins_api( 'plugin_information', array( 'slug' => $slug ) );

                if ( is_wp_error( $api ) ) {
                    $results[] = array(
                        'slug'    => $plugin_slug,
                        'success' => false,
                        'message' => $api->get_error_message(),
                    );
                    continue;
                }

                $result = $upgrader->install( $api->download_link );
                if ( is_wp_error( $result ) ) {
                    $results[] = array(
                        'slug'    => $plugin_slug,
                        'success' => false,
                        'message' => $result->get_error_message(),
                    );
                    continue;
                }
            }

            // active the plugin
            if ( is_plugin_inactive($plugin_slug) ) {
                // validate_plugin() confirms the file is a real plugin inside
                // WP_PLUGIN_DIR before we hand it to activate_plugin().
                $is_valid_plugin = validate_plugin( $plugin_slug );

                if ( is_wp_error( $is_valid_plugin ) ) {
                    $results[] = array(
                        'slug'    => $plugin_slug,
                        'success' => false,
                        'message' => $is_valid_plugin->get_error_message(),
                    );
                    continue;
                }

                $activation_result = activate_plugin( $plugin_slug );
                if ( is_wp_error( $activation_result ) ) {
                    $results[] = array(
                        'slug'    => $plugin_slug,
                        'success' => false,
                        'message' => $activation_result->get_error_message(),
                    );
                    continue;
                }

                $results[] = array(
                    'slug'    => $plugin_slug,
                    'success' => true,
                    'message' => 'Installed and activated successfully',
                );
            }
		}

		ob_clean();
		wp_send_json_success( array( 'results' => $results ) );
		wp_die();
	}


/** Function sync_demo_with_server() called by wp_ajax hooks: {'ep_elementor_demo_importer_data_sync_demo_with_server'} **/
/** No params detected :-/ **/


/** Function element_pack_ajax_search() called by wp_ajax hooks: {'element_pack_search', 'nopriv_element_pack_search'} **/
/** Parameters found in function element_pack_ajax_search(): {"request": ["nonce", "s"], "post": ["settings"]} **/
function element_pack_ajax_search() {
		global $post;

		if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'element-pack-site' ) ) {
			die( wp_json_encode( array( 'results' => array() ) ) );
		}

		$result       = array( 'results' => array() );
		$search_input = isset( $_REQUEST['s'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) : '';
		$settings     = isset( $_POST['settings'] ) ? array_map( 'sanitize_text_field', wp_unslash( (array) $_POST['settings'] ) ) : [];

		if ( strlen( $search_input ) >= 3 ) {

			$allowed_post_types = get_post_types( array( 'public' => true ) );
			$requested_type = isset( $settings['post_type'] ) ? $settings['post_type'] : 'post';
			$post_type = isset( $allowed_post_types[ $requested_type ] ) ? $requested_type : 'post';

			$response_limit = isset( $settings['per_page'] ) ? absint( $settings['per_page'] ) : 5;
			if ( $response_limit < 1 ) {
				$response_limit = 5;
			}
			if ( $response_limit > 50 ) {
				$response_limit = 50;
			}

			/*
			 * Fetch many more matches than we return: core search orders by date/relevance and
			 * can omit an exact title match from the first N rows. We rank by title client-side
			 * and slice — full-site search shows more rows so the same post appears "on top" there.
			 */
			$fetch_pool = min( 200, max( 80, $response_limit * 25 ) );

			$query_args = [
				'post_type'      => $post_type,
				's'              => sanitize_text_field( $search_input ),
				'posts_per_page' => $fetch_pool,
				'post_status'    => 'publish',
			];

			/**
			 * Set Authors
			 */
			$include_users = [];
			$exclude_users = [];
			if ( ! empty( $settings['include_author_ids'] ) ) {
				if ( in_array( 'authors', $settings['include_by'] ) ) {
					$include_users = wp_parse_id_list( $settings['include_author_ids'] );
				}
			}
			if ( ! empty( $settings['exclude_author_ids'] ) ) {
				if ( in_array( 'authors', $settings['exclude_by'] ) ) {
					$exclude_users = wp_parse_id_list( $settings['exclude_author_ids'] );
					$include_users = array_diff( $include_users, $exclude_users );
				}
			}
			if ( ! empty( $include_users ) ) {
				$query_args['author__in'] = $include_users;
			}

			if ( ! empty( $exclude_users ) ) {
				$query_args['author__not_in'] = $exclude_users;
			}

			/**
			 * Set Taxonomy
			 */

			$include_terms = [];
			$exclude_terms = [];
			$terms_query   = [];

			if ( ! empty( $settings['include_term_ids'] ) ) {
				if ( in_array( 'terms', $settings['include_by'] ) ) {
					$include_terms = wp_parse_id_list( $settings['include_term_ids'] );
				}
			}
			if ( ! empty( $settings['exclude_term_ids'] ) ) {
				if ( in_array( 'terms', $settings['exclude_by'] ) ) {
					$exclude_terms = wp_parse_id_list( $settings['exclude_term_ids'] );
					$include_terms = array_diff( $include_terms, $exclude_terms );
				}
			}

			if ( ! empty( $include_terms ) ) {
				$tax_terms_map = $this->mapGroupControlQuery( $include_terms );
				foreach ( $tax_terms_map as $tax => $terms ) {
					$terms_query[] = [ 
						'taxonomy' => $tax,
						'field'    => 'term_id',
						'terms'    => $terms,
						'operator' => 'IN',
					];
				}
			}

			if ( ! empty( $exclude_terms ) ) {
				$tax_terms_map = $this->mapGroupControlQuery( $exclude_terms );
				foreach ( $tax_terms_map as $tax => $terms ) {
					$terms_query[] = [ 
						'taxonomy' => $tax,
						'field'    => 'term_id',
						'terms'    => $terms,
						'operator' => 'NOT IN',
					];
				}
			}

			if ( ! empty( $terms_query ) ) {
				$query_args['tax_query']             = $terms_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- User-configurable Elementor query control.
				$query_args['tax_query']['relation'] = 'AND';
			}

			$query_posts = get_posts( $query_args );
			if ( ! empty( $query_posts ) ) {
				$needle_norm = $this->normalize_search_compare_string( $search_input );
				$needle_rank = $this->normalize_search_for_title_ranking( $needle_norm );

				usort(
					$query_posts,
					function ( $a, $b ) use ( $needle_norm, $needle_rank ) {
						$ra = $this->get_ajax_search_title_rank( $a, $needle_norm, $needle_rank );
						$rb = $this->get_ajax_search_title_rank( $b, $needle_norm, $needle_rank );
						return $ra <=> $rb;
					}
				);

				$query_posts = array_slice( $query_posts, 0, $response_limit );

				foreach ( $query_posts as $post ) {
					$content = ! empty( $post->post_excerpt ) ? wp_strip_all_tags( strip_shortcodes( $post->post_excerpt ) ) : wp_strip_all_tags( strip_shortcodes( $post->post_content ) );
					if ( strlen( $content ) > 180 ) {
						$content = substr( $content, 0, 179 ) . '...';
					}
					$result['results'][] = array(
						'title' => get_the_title(),
						'text'  => $content,
						'url'   => get_permalink( $post->ID ),
					);
				}
			}
		}

		die( wp_json_encode( $result ) );
	}


/** Function element_pack_do_register_user() called by wp_ajax hooks: {'nopriv_element_pack_ajax_register'} **/
/** Parameters found in function element_pack_do_register_user(): {"server": ["REQUEST_METHOD"], "request": ["page_id", "widget_id", "user_terms", "email", "password", "is_password_required", "first_name", "last_name"]} **/
function element_pack_do_register_user() {
			
			check_ajax_referer( 'ajax-login-nonce', 'security' );
			
			if ( isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST' ) {
				if ( ! get_option( 'users_can_register' ) ) {
					// Registration closed, display error
					echo wp_json_encode(
						[
							'registered' => false,
							'message'    => __( 'Registering new users is currently not allowed.', 'bdthemes-element-pack-lite' )
						] );
				} else {
					
					$post_id   = isset($_REQUEST['page_id']) ? (int) $_REQUEST['page_id'] : 0;
					$widget_id = isset($_REQUEST['widget_id']) ? sanitize_text_field( wp_unslash( $_REQUEST['widget_id'] ) ) : 0;

					$terms = isset($_REQUEST['user_terms']) ? sanitize_text_field( wp_unslash( $_REQUEST['user_terms'] ) ) : '';
					
					$settings = $this->get_widget_settings( $post_id, $widget_id );
					
					$email                = isset($_REQUEST['email']) ? sanitize_email( wp_unslash( $_REQUEST['email'] ) ) : '';
					// The password is intentionally left unfiltered — sanitizing it would
					// silently change what the user typed. wp_insert_user() hashes it.
					// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Raw password required by wp_insert_user().
					$password             = isset($_REQUEST['password']) ? wp_unslash( $_REQUEST['password'] ) : NULL  ;
					$is_password_required = isset($_REQUEST['is_password_required']) ? sanitize_text_field( wp_unslash( $_REQUEST['is_password_required'] ) ) : '';
					$first_name           = isset($_REQUEST['first_name']) ? sanitize_text_field( wp_unslash( $_REQUEST['first_name'] ) ) : '';
					$last_name            = isset($_REQUEST['last_name']) ? sanitize_text_field( wp_unslash( $_REQUEST['last_name'] ) ) : '';
					
					$result = $this->element_pack_register_user( $email, $password, $is_password_required, $first_name, $last_name, $terms );
					
					if ( is_wp_error( $result ) ) {
						// Parse errors into a string and append as parameter to redirect
						$errors = $result->get_error_message();
						echo wp_json_encode( [ 'registered' => false, 'message' => $errors ] );
					} else {
						// Success
						/* translators: %s: Site name */
						$message = sprintf( __( 'You have successfully registered to <strong>%s</strong>.', 'bdthemes-element-pack-lite' ), get_bloginfo( 'name' ) );
						
						
						if ( isset( $settings['auto_login_after_register'] ) && $settings['auto_login_after_register'] == 'yes' ) {
							$user = get_user_by( 'email', $email );
							wp_set_current_user( $user->ID );
							wp_set_auth_cookie( $user->ID );
							
							// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- WordPress core hook, fired deliberately.
							do_action( 'wp_login', $user->user_login, $user );
						}

						echo wp_json_encode( [ 'registered' => true, 'message' => $message ] );
						die();
						
					}
				}
				exit;
			}
		}


/** Function getSelectInputData() called by wp_ajax hooks: {'elementpack_dynamic_select_input_data'} **/
/** Parameters found in function getSelectInputData(): {"post": ["security", "query", "post_type", "field_type"]} **/
function getSelectInputData() {
        $nonce = isset($_POST['security']) ? sanitize_text_field(wp_unslash($_POST['security'])) : '';

        try {
            if (!wp_verify_nonce($nonce, 'ep_dynamic_select')) {
                throw new Exception('Invalid request');
            }

            if (!current_user_can('edit_posts')) {
                throw new Exception('Unauthorized request');
            }

            $query = isset($_POST['query']) ? sanitize_text_field(wp_unslash($_POST['query'])) : '';
            $post_type = isset($_POST['post_type']) ? sanitize_text_field(wp_unslash($_POST['post_type'])) : '';
            $field_type = isset($_POST['field_type']) ? array_map('sanitize_text_field', (array) wp_unslash($_POST['field_type'])) : '';

            // Initialize ACF_Global
            $acf_global = new ACF_Global();

            if ($query == 'terms') {
                $data = $this->getTerms();
            } else if ($query == 'authors') {
                $data = $this->getAuthors();
            } else if ($query == 'authors_role') {
                $data = $this->getAuthorRoles();
            } else if ($query == 'only_post') {
                $data = $this->getOnlyPosts($post_type);
            } else if ($query == 'elementor_template') {
                $data = $this->getElementorTemplates();
            } else if ($query == 'anywhere_template') {
                $data = $this->getAnywhereTemplates();
            } elseif ($query == 'elementor_dynamic_loop_template') {
                $data = $this->getDynamicTemplates();
            } elseif ($query == 'bbpress_single_forum') {
                $data = $this->getDynamicBbPressForumIDs();
            } elseif ($query == 'bbpress_single_topic') {
                $data = $this->getDynamicBbPressTopicIDs();
            } elseif ($query == 'bbpress_single_reply') {
                $data = $this->getDynamicBbPressReplyIDs();
            } elseif ($query == 'bbpress_single_topic_terms') {
                $data = $this->getbbPressTerms();
            } elseif ($query == 'acf') {
                $data = $acf_global->getAcfFields($field_type);
            } elseif ($query == 'comments') {
                $data = $this->getComments();
            } else {
                $data = $this->getPosts();
            }

            wp_send_json_success($data);
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }

        die();
    }


/** Function send_report() called by wp_ajax hooks: {'ep_elementor_demo_importer_send_report'} **/
/** Parameters found in function send_report(): {"request": ["demo_id", "demo_json_url"]} **/
function send_report(){
        $this->verify_ajax_access();
        // Nonce and capability are verified by verify_ajax_access() above.
        // phpcs:disable WordPress.Security.NonceVerification.Recommended -- Verified in verify_ajax_access().
        if(isset($_REQUEST['demo_id']) && $_REQUEST['demo_id'] > 0 && isset($_REQUEST['demo_json_url'])){
            $demo_id        = absint($_REQUEST['demo_id']);
            $demo_json_url  = esc_url_raw(wp_unslash($_REQUEST['demo_json_url']));
            // phpcs:enable WordPress.Security.NonceVerification.Recommended
            $json_url       = 'Demo ID:' . $demo_id;
            $demo_url       = $demo_json_url;
            $demo_title     = 'No Demo Title';

            $postTable      = $this->table_post;
            $resultData = $this->wpdb->get_row(
                // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table name is internal ($wpdb->prefix based); demo_id is bound via prepare().
                $this->wpdb->prepare("SELECT * FROM {$postTable} WHERE demo_id = %d", $demo_id)
            );

            if($resultData){
                $json_url       = $resultData->json_url;
                $demo_url       = $resultData->demo_url;
                $demo_title     = $resultData->title;
            }

            $data['json_url']   = $json_url;
            $data['demo_title'] = $demo_title;
            $data['demo_url']   = $demo_url;
            $userInfo = wp_get_current_user();
            $data['display_name'] = $userInfo->data->display_name;
            $data['user_email'] = $userInfo->data->user_email;
            $data['site_url'] = site_url();

            if($this->sendMail($data)){
                echo wp_json_encode(
                    array(
                        'success' => true,
                        'data'    => array(),
                    )
                );
                wp_die();
            };
        }

        echo wp_json_encode(
            array(
                'success' => false,
                'data'    => array(),
            )
        );
        wp_die();
    }


/** Function sync_now() called by wp_ajax hooks: {'bdt_element_pack_template_library_making_syncing'} **/
/** No params detected :-/ **/


/** Function ajax_get_plugins() called by wp_ajax hooks: {'ep_get_plugins'} **/
/** No params detected :-/ **/


/** Function get_layouts() called by wp_ajax hooks: {'bdt_element_pack_template_library_get_layouts'} **/
/** Parameters found in function get_layouts(): {"request": ["tab", "term_slug"]} **/
function get_layouts()
    {

        check_ajax_referer( 'ep-template-library', 'nonce' );

        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Access Denied', 'bdthemes-element-pack-lite' ) ), 403 );
        }

        isset($_REQUEST['tab']) || exit();

        $tab = empty($_REQUEST['tab']) ? 'bdt_elementpack_page' : sanitize_key( wp_unslash( $_REQUEST['tab'] ) );

        if($tab == 'bdt_elementpack_block'){
            $this->demoType = 2;
        }elseif($tab == 'bdt_elementpack_header'){
            $this->demoType = 3;
        }elseif($tab == 'bdt_elementpack_footer'){
            $this->demoType = 4;
        }else{
            $this->demoType = 1;
        }

        $this->termSlug = 'demo_term_all';
        if(isset($_REQUEST['term_slug']) && !empty($_REQUEST['term_slug'])){
            $this->termSlug = sanitize_text_field(wp_unslash($_REQUEST['term_slug']));
        }

        $this->perPage = 500000;

        wp_send_json([
            'data' => [
                'categories' => $this->getCategoriesItems(),
                'templates'  => $this->getElementorLibraryData(),
            ],
        ], 200);

    }


/** Function ajax_fetch_api_biggopti() called by wp_ajax hooks: {'ep_fetch_api_biggopti'} **/
/** Parameters found in function ajax_fetch_api_biggopti(): {"post": ["_wpnonce", "current_url"]} **/
function ajax_fetch_api_biggopti() {
		$nonce = isset($_POST['_wpnonce']) ? sanitize_text_field(wp_unslash($_POST['_wpnonce'])) : '';
		if (!wp_verify_nonce($nonce, 'element-pack')) {
			wp_send_json_error([ 'message' => 'invalid_nonce' ]);
		}

		if (!current_user_can('manage_options')) {
			wp_send_json_error([ 'message' => 'forbidden' ]);
		}

		// Skip biggopti execution for extended license holders
		if (class_exists('\ElementPack\Base\Element_Pack_Base') && $this->has_extended_license()) {
			wp_send_json_success(['html' => '']);
		}

		// Don't show biggopties on plugin/theme install and upload pages
		$current_url = isset($_POST['current_url']) ? sanitize_text_field(wp_unslash($_POST['current_url'])) : '';
		
		if (!empty($current_url)) {
			$excluded_patterns = [
				'plugin-install.php',
				'theme-install.php',
				'action=upload-plugin',
				'action=upload-theme'
			];
			
			foreach ($excluded_patterns as $pattern) {
				if (strpos($current_url, $pattern) !== false) {
					wp_send_json_success([ 'html' => '' ]);
				}
			}
		}

		$biggopties = $this->get_api_biggopties_data();
		$grouped_biggopties = [];

		if (is_array($biggopties)) {
			foreach ($biggopties as $index => $biggopti) {
				if ($this->should_show_biggopti($biggopti)) {
					$display_id = isset($biggopti->display_id) ? $biggopti->display_id : 'default-' . $index;
					if (!isset($grouped_biggopties[$display_id])) {
						$grouped_biggopties[$display_id] = $biggopti;
					}
				}
			}
		}

		// Build biggopties using the same pipeline as synchronous rendering
		foreach ($grouped_biggopties as $display_id => $biggopti) {
			$biggopti_id = isset($biggopti->id) ? $display_id : $biggopti->id;

			self::add_biggopti([
				'id' => 'api-biggopti-' . $biggopti_id,
				'type' => isset($biggopti->type) ? $biggopti->type : 'info',
				'category' => isset($biggopti->category) ? $biggopti->category : 'regular',
				'dismissible' => true,
				'html_message' => $this->render_api_biggopti($biggopti),
				'dismissible-meta' => 'transient',
				'dismissible-time' => isset($biggopti->end_date) ? max((new \DateTime($biggopti->end_date, new \DateTimeZone('UTC')))->getTimestamp() - time(), 0) : WEEK_IN_SECONDS,
			]);
		}

		ob_start();
		$this->show_biggopties();
		$markup = ob_get_clean();

		wp_send_json_success([ 'html' => $markup ]);
	}


/** Function demo_tab_ajax_loading_demo() called by wp_ajax hooks: {'ep_elementor_demo_importer_data_loading'} **/
/** Parameters found in function demo_tab_ajax_loading_demo(): {"request": ["s", "term_slug", "demo_type", "sort_By_title", "sort_By_date", "paged"]} **/
function demo_tab_ajax_loading_demo() {
        $this->verify_ajax_access();
        // Nonce and capability are verified by verify_ajax_access() above.
        // phpcs:disable WordPress.Security.NonceVerification.Recommended -- Verified in verify_ajax_access().
        $this->searchVal      = isset($_REQUEST['s']) ? sanitize_text_field(wp_unslash($_REQUEST['s'])) : '';
        $this->termSlug       = isset($_REQUEST['term_slug']) ? sanitize_text_field(wp_unslash($_REQUEST['term_slug'])) : '';
        $this->demoType       = isset($_REQUEST['demo_type']) ? sanitize_text_field(wp_unslash($_REQUEST['demo_type'])) : '';
        $this->sortByTitle    = isset($_REQUEST['sort_By_title']) ? sanitize_text_field(wp_unslash($_REQUEST['sort_By_title'])) : '';
        $this->sortByDate     = isset($_REQUEST['sort_By_date']) ? sanitize_text_field(wp_unslash($_REQUEST['sort_By_date'])) : '';
        $paged                = isset($_REQUEST['paged']) ? intval($_REQUEST['paged']) : 0;
        // phpcs:enable WordPress.Security.NonceVerification.Recommended

        $filterData = $this->getData($paged);
        ob_start();
        $this->loadHtmlItems( $filterData );
        $paged   = $paged + 1;

        $html = ob_get_contents();
        ob_end_clean();
        echo wp_json_encode(
            array(
                'success'   => true,
                'data'      => $html,
                'paged'     => $paged,
                'total_page'=> $this->totalPage
            )
        );
        wp_die();
    }


/** Function install_plugin_ajax() called by wp_ajax hooks: {'ep_install_plugin'} **/
/** Parameters found in function install_plugin_ajax(): {"post": ["nonce", "plugin_slug"]} **/
function install_plugin_ajax() {
		// Check nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ep_install_plugin_nonce' ) ) {
			wp_send_json_error(['message' => __('Security check failed', 'bdthemes-element-pack-lite')]);
		}

		// Check user capability
		if (!current_user_can('install_plugins')) {
			wp_send_json_error(['message' => __('You do not have permission to install plugins', 'bdthemes-element-pack-lite')]);
		}

		$plugin_slug = isset($_POST['plugin_slug']) ? sanitize_key(wp_unslash($_POST['plugin_slug'])) : '';

		if (empty($plugin_slug)) {
			wp_send_json_error(['message' => __('Plugin slug is required', 'bdthemes-element-pack-lite')]);
		}

		// Include necessary WordPress files
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php';

		// Get plugin information
		$api = plugins_api('plugin_information', [
			'slug' => $plugin_slug,
			'fields' => [
				'sections' => false,
			],
		]);

		if (is_wp_error($api)) {
			wp_send_json_error(['message' => __('Plugin not found: ', 'bdthemes-element-pack-lite') . $api->get_error_message()]);
		}

		// Install the plugin
		$skin = new \WP_Ajax_Upgrader_Skin();
		$upgrader = new \Plugin_Upgrader($skin);
		$result = $upgrader->install($api->download_link);

		if (is_wp_error($result)) {
			wp_send_json_error(['message' => __('Installation failed: ', 'bdthemes-element-pack-lite') . $result->get_error_message()]);
		} elseif ($skin->get_errors()->has_errors()) {
			wp_send_json_error(['message' => __('Installation failed: ', 'bdthemes-element-pack-lite') . $skin->get_error_messages()]);
		} elseif (is_null($result)) {
			wp_send_json_error(['message' => __('Installation failed: Unable to connect to filesystem', 'bdthemes-element-pack-lite')]);
		}

		// Get installation status
		$install_status = install_plugin_install_status($api);
		
		wp_send_json_success([
			'message' => __('Plugin installed successfully!', 'bdthemes-element-pack-lite'),
			'plugin_file' => $install_status['file'],
			'plugin_name' => $api->name
		]);
	}


