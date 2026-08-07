<?php
/***
*
*Found actions: 9
*Found functions:9
*Extracted functions:8
*Total parameter names extracted: 12
*Overview: {'form_id_for_emailkit': {'check_built_template'}, 'ask_me_later_message': {'wpmet_rating_ask_me_later_message'}, 'dismiss_ajax_call': {'wpmet-notices'}, 'never_show_message': {'wpmet_rating_never_show_message'}, '\\MetForm\\Utils\\Util': {'metform_admin_action'}, 'mf_setting_data_save': {'metform_admin_settings'}, 'metform_admin_action': {'mf_admin_action'}, 'metform_onboard_plugins': {'mf_onboard_plugins'}, 'handle_feedback': {'metform_deactivation_feedback'}}
*
***/

/** Function form_id_for_emailkit() called by wp_ajax hooks: {'check_built_template'} **/
/** Parameters found in function form_id_for_emailkit(): {"get": ["rest_nonce"]} **/
function form_id_for_emailkit( ){

         if ( ! isset($_GET['rest_nonce']) || ! wp_verify_nonce( $_GET['rest_nonce'], 'metform_emailkit_nonce' ) ) {
            return [
                'status' => 'fail',
                'message' => [__('Nonce mismatch.', 'metform')]
            ];
        }

        if (!is_user_logged_in() || !current_user_can('publish_posts')) {
            return [
                'status' => 'fail',
                'message' => [__('Access denied.', 'metform')]
            ];
        }

        $template_ids = get_posts([
            'post_type'      => 'emailkit',
            'meta_query'     => [
                [
                    'key' => 'emailkit_template_type',
                    'value' => 'metform_form_',
                    'compare' => 'LIKE'
                ]
            ],
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'orderby'        => 'ID',
            'order'          => 'ASC'
        ]);

        if(!empty($template_ids)){

            $template_id = $template_ids[0];
            $template_type = get_post_meta($template_id, 'emailkit_template_type', true);
            $form_id = str_replace('metform_form_', '', $template_type);

            wp_send_json_success( $form_id );
        }

        return false;
    }


/** Function ask_me_later_message() called by wp_ajax hooks: {'wpmet_rating_ask_me_later_message'} **/
/** Parameters found in function ask_me_later_message(): {"request": ["_wpnonce"], "post": ["plugin_name"]} **/
function ask_me_later_message() {
			if(empty( $_REQUEST['_wpnonce'] ) || !isset($_POST['plugin_name']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])),'wpmet_rating_never_show_message')){
				return;
			}
			$plugin_name = sanitize_text_field(wp_unslash($_POST['plugin_name']));
			if(get_option($plugin_name . '_ask_me_later') == false) {
				add_option($plugin_name . '_ask_me_later', 'yes');
			} else {
				add_option($plugin_name . '_never_show', 'yes');
			}
		}


/** Function dismiss_ajax_call() called by wp_ajax hooks: {'wpmet-notices'} **/
/** Parameters found in function dismiss_ajax_call(): {"request": ["_wpnonce"], "post": ["notice_id", "dismissible", "expired_time"]} **/
function dismiss_ajax_call() {
        if( !current_user_can( 'manage_options' ) || empty( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])),'wpmet-notices')){
            wp_send_json_error();
        }
        
		$notice_id   = ( isset( $_POST['notice_id'] ) ) ? sanitize_text_field(wp_unslash($_POST['notice_id'])) : '';
		$dismissible = ( isset( $_POST['dismissible'] ) ) ? sanitize_text_field(wp_unslash($_POST['dismissible'])) : '';
		$expired_time = ( isset( $_POST['expired_time'] ) ) ? sanitize_text_field(wp_unslash($_POST['expired_time'])) : '';

		if ( ! empty( $notice_id ) && ( strlen( $notice_id ) <= 100 ) ) {
			if ( 'user' === $dismissible ) {
				update_user_meta( get_current_user_id(), $notice_id, true );
			} else {
				set_transient( $notice_id, true, $expired_time );
			}

            if( $notice_id == 'metform-edit_with_emailkit_banner' ) {
                $counter = get_option('metform-edit_with_emailkit_banner_dismissed_'.get_current_user_id(), 0);
                update_option('metform-edit_with_emailkit_banner_dismissed_'.get_current_user_id(), $counter+1);
            }

			wp_send_json_success();
		}

		wp_send_json_error();
	}


/** Function never_show_message() called by wp_ajax hooks: {'wpmet_rating_never_show_message'} **/
/** Parameters found in function never_show_message(): {"request": ["_wpnonce"], "post": ["plugin_name"]} **/
function never_show_message() {
			if(empty( $_REQUEST['_wpnonce'] ) || !isset($_POST['plugin_name']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])),'wpmet_rating_never_show_message')){
				return;
			}
			$plugin_name = sanitize_text_field(wp_unslash($_POST['plugin_name']));
			add_option($plugin_name . '_never_show', 'yes');
		}


/** Function \MetForm\Utils\Util() called by wp_ajax hooks: {'metform_admin_action'} **/
/** No function found :-/ **/


/** Function mf_setting_data_save() called by wp_ajax hooks: {'metform_admin_settings'} **/
/** Parameters found in function mf_setting_data_save(): {"server": ["HTTP_X_WP_NONCE"], "post": ["form_data", "fields_in_form"]} **/
function mf_setting_data_save(){
        
        if ( ! current_user_can( 'manage_options' ) || ! isset( $_SERVER['HTTP_X_WP_NONCE'] ) || ! wp_verify_nonce( $_SERVER['HTTP_X_WP_NONCE'], 'wp_rest' ) ) {
            
            wp_send_json_error('You are not allowed to do this.');
            wp_die();
        }

        $request = isset($_POST['form_data']) ? $_POST['form_data'] : [];
        $fields_in_form = isset($_POST['fields_in_form']) ? $_POST['fields_in_form'] : [];

        if (empty($request)) {
            wp_send_json_error('No data provided');
            wp_die();
        }

        //get existing settings
        $settings = get_option($this->key_settings_option, []);

        $checkboxes = array('mf_save_progress', 'mf_field_name_show', 'mf_paypal_sandbox', 'mf_enable_entry_file_delete', 'mf_stripe_sandbox', 'mf_enable_form_analytics');

        //if checkbox is in the submitted form but not set, unset it from settings that was set previously.
        foreach ($checkboxes as $key) {
            if (in_array($key, $fields_in_form) && !isset($request[$key])) {
                if ($key === 'mf_enable_form_analytics') {
                    $request[$key] = '0';
                    continue;
                }

                if (isset($settings[$key])) {
                    unset($settings[$key]);
                }
            }
        }

        $settings = is_array($request) ? array_merge($settings, $request) : $settings;
        $status = \MetForm\Core\Forms\Action::instance()->store( -1, $settings);
        
        wp_send_json_success($status);
        exit;
    }


/** Function metform_admin_action() called by wp_ajax hooks: {'mf_admin_action'} **/
/** Parameters found in function metform_admin_action(): {"post": ["nonce", "user_data", "settings", "our_plugins"]} **/
function metform_admin_action() {
		// Check for nonce security
		if (!isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['nonce'])), 'ajax-nonce' ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( isset( $_POST['user_data'] ) ) {
			$this->utils->save_option( 'user_data', empty( $_POST['user_data'] ) ? [] : sanitize_text_field(wp_unslash($_POST['user_data'])));
		}

		if ( isset( $_POST['settings'] ) ) {
			//phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- Sanitized using map_deep
			$this->utils->save_settings( map_deep($_POST['settings'],function($data){
				return sanitize_text_field(wp_unslash($data));
			}));
		}


		do_action( 'metform/admin/after_save' );

		$response = array(
			'message' => self::plugin_activate_message( 'setup_configurations' )
		);

		$plugins = !empty($_POST['our_plugins']) && is_array($_POST['our_plugins']) ? $_POST['our_plugins'] : [];
		if($plugins) {
			$total_plugins = count($plugins);
			$total_steps   = 1 + $total_plugins;
			$percentage = ($total_steps > 0) ? (1 / $total_steps) * 100 : 100;
			$percentage = round($percentage);

			$response['progress'] = $percentage;
			$response['plugins'] = $plugins;
		}

		wp_send_json($response);

		wp_die(); // this is required to terminate immediately and return a proper response
	}


/** Function metform_onboard_plugins() called by wp_ajax hooks: {'mf_onboard_plugins'} **/
/** Parameters found in function metform_onboard_plugins(): {"post": ["nonce", "plugin_slug"]} **/
function metform_onboard_plugins() {
		// Check for nonce security
		if (!isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['nonce'])), 'ajax-nonce' ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$plugin_slug = isset( $_POST['plugin_slug'] ) ? sanitize_text_field(wp_unslash($_POST['plugin_slug'])) : '';
		if ( isset( $plugin_slug ) && current_user_can('install_plugins') ) {
			$status = \MetForm\Core\Integrations\Onboard\Classes\Plugin_Installer::single_install_and_activate( $plugin_slug );
			if ( is_wp_error( $status ) ) {
				wp_send_json_error( array( 'status' => false ) );
			} else {
				wp_send_json_success(
					array(
						'message' => self::plugin_activate_message( $plugin_slug )
					)
				);
			}
		}
	}


/** Function handle_feedback() called by wp_ajax hooks: {'metform_deactivation_feedback'} **/
/** Parameters found in function handle_feedback(): {"post": ["reason", "reason_key", "reason_label", "feedback"]} **/
function handle_feedback() {
		$this->verify_request();

		$selected_reason = isset( $_POST['reason'] )
			? sanitize_text_field( wp_unslash( $_POST['reason'] ) )
			: '';

		$data = array(
			'plugin_slug'    => 'metform',
			'plugin_name'    => 'MetForm',
			'plugin_version' => Plugin::instance()->version(),
			'user'           => array(
				'email' => $this->get_user_email(),
			),
			'feedback'       => array(
				'reason_key'   => isset( $_POST['reason_key'] ) ? sanitize_text_field( wp_unslash( $_POST['reason_key'] ) ) : 'other',
				'reason_label' => isset( $_POST['reason_label'] ) ? sanitize_text_field( wp_unslash( $_POST['reason_label'] ) ) : $selected_reason,
				'message'      => isset( $_POST['feedback'] )? sanitize_textarea_field( wp_unslash( $_POST['feedback'] ) ) : '',
			),
			'usage'          => array(
				'user_type'      => $this->get_user_type(),
				'active_days'    => $this->get_days_active(),
			),
			'environment'    => array(
				'multisite_status'   => is_multisite(),
				'wp_version'         => get_bloginfo( 'version' ),
				'php_version'        => PHP_VERSION,
				'elementor_version'  => defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '',
				'site_url'           => get_site_url(),
			),
		);

		$response = $this->send_feedback_data( $data );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( array( 'message' => $response->get_error_message() ) );
		}

		$response_code = (int) wp_remote_retrieve_response_code( $response );
		if ( $response_code < 200 || $response_code >= 300 ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Failed to submit feedback.', 'metform' ),
					'code'    => $response_code,
				)
			);
		}

		wp_send_json_success(
			array( 'message' => esc_html__( 'Thank you for your feedback!', 'metform' ) )
		);
	}


