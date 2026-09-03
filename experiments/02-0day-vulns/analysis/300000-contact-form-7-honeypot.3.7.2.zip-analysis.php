<?php
/***
*
*Found actions: 7
*Found functions:6
*Extracted functions:6
*Total parameter names extracted: 3
*Overview: {'ajax_cf7_ai_generate_form': {'cf7_ai_generate_form'}, 'ajax_cf7_ai_refresh_usage': {'cf7_ai_refresh_usage'}, '_toggle_debug_mode': {'fs_toggle_debug_mode'}, 'dismiss_notice_ajax_callback': {'fs_dismiss_notice_action_{$ajax_action_suffix}'}, 'honeypot4cf7_dismiss_notice': {'honeypot4cf7_dismiss_notice'}, 'fetch_app_settings': {'cf7apps_fetch_settings', 'nopriv_cf7apps_fetch_settings'}}
*
***/

/** Function ajax_cf7_ai_generate_form() called by wp_ajax hooks: {'cf7_ai_generate_form'} **/
/** Parameters found in function ajax_cf7_ai_generate_form(): {"post": ["prompt", "template"]} **/
function ajax_cf7_ai_generate_form() {
			check_ajax_referer( 'cf7_ai_form_generator', 'nonce' );

			if ( ! current_user_can( 'wpcf7_edit_contact_forms' ) ) {
				wp_send_json_error(
					array(
						'message' => __( 'Permission denied.', 'cf7apps' ),
					),
					403
				);
			}

			if ( ! self::is_integration_enabled() ) {
				wp_send_json_error(
					array(
						'message' => __( 'AI Form Generator is disabled.', 'cf7apps' ),
					),
					400
				);
			}

			if ( ! CF7Apps_Cf7Ai_Client::mw_base_ok() ) {
				wp_send_json_error(
					array(
						'message' => __( 'AI middleware base URL is not set. Define CF7APPS_AI_MIDDLEWARE_BASE_URL in wp-config.php.', 'cf7apps' ),
					),
					400
				);
			}

			$prompt   = isset( $_POST['prompt'] ) ? sanitize_textarea_field( wp_unslash( $_POST['prompt'] ) ) : '';
			$template = isset( $_POST['template'] ) ? sanitize_key( wp_unslash( $_POST['template'] ) ) : '';
	
			$combined = trim( $prompt );
			if ( '' === $combined && $template ) {
				foreach ( self::get_template_definitions() as $id => $def ) {
					if ( $id === $template ) {
						$combined = $def['prompt'];
						break;
					}
				}
			}

			if ( '' === trim( $combined ) ) {
				wp_send_json_error(
					array(
						'message' => __( 'Please enter form details or select a template to continue.', 'cf7apps' ),
					),
					400
				);
			}	

			$form_result = CF7Apps_Cf7Ai_Client::cf7_tags_from_ai( $combined );
			if ( is_wp_error( $form_result ) ) {
				$payload = array(
					'message' => $form_result->get_error_message(),
				);
			if ( 'no_middleware_credits' === $form_result->get_error_code() ) {
					$error_data = $form_result->get_error_data();
					$limit      = is_array( $error_data ) && isset( $error_data['credits_limit'] )
						? $error_data['credits_limit']
						: null;
					$reset_at   = is_array( $error_data ) && isset( $error_data['credits_reset_at'] )
						? $error_data['credits_reset_at']
						: null;
					$period_started = is_array( $error_data ) && isset( $error_data['credits_period_started'] )
						? $error_data['credits_period_started']
						: null;
					$mw_message = is_array( $error_data ) && ! empty( $error_data['credits_message'] )
						? $error_data['credits_message']
						: $form_result->get_error_message();
					$payload['code']  = 'no_middleware_credits';
					$payload['usage'] = self::mark_middleware_exhausted( $limit, $reset_at, $mw_message, $period_started );
				}
				wp_send_json_error( $payload, 400 );
			}
			
			$form_tags = is_array( $form_result ) ? ( $form_result['form_tags'] ?? '' ) : (string) $form_result;
			$remaining = is_array( $form_result ) && array_key_exists( 'credits_remaining', $form_result )
				? $form_result['credits_remaining']
				: null;
			$limit     = is_array( $form_result ) && array_key_exists( 'credits_limit', $form_result )
				? $form_result['credits_limit']
				: null;
			$reset_at  = is_array( $form_result ) && array_key_exists( 'credits_reset_at', $form_result )
				? $form_result['credits_reset_at']
				: null;
			$period_started = is_array( $form_result ) && array_key_exists( 'credits_period_started', $form_result )
				? $form_result['credits_period_started']
				: null;
			$message   = is_array( $form_result ) && ! empty( $form_result['credits_message'] )
				? $form_result['credits_message']
				: null;

			// Reveal after first success; values come only from middleware (no local counter).
			$usage = self::store_middleware_usage( $remaining, $limit, true, $reset_at, $message, $period_started );

			wp_send_json_success(
				array(
					'form_tags'     => $form_tags,
					'credit_source' => 'middleware',
					'usage'         => $usage,
				)
			);
		}


/** Function ajax_cf7_ai_refresh_usage() called by wp_ajax hooks: {'cf7_ai_refresh_usage'} **/
/** No params detected :-/ **/


/** Function _toggle_debug_mode() called by wp_ajax hooks: {'fs_toggle_debug_mode'} **/
/** No params detected :-/ **/


/** Function dismiss_notice_ajax_callback() called by wp_ajax hooks: {'fs_dismiss_notice_action_{$ajax_action_suffix}'} **/
/** Parameters found in function dismiss_notice_ajax_callback(): {"post": ["message_id"]} **/
function dismiss_notice_ajax_callback() {
            check_admin_referer( 'fs_dismiss_notice_action' );

            if ( ! is_numeric( $_POST['message_id'] ) ) {
                $this->_sticky_storage->remove( $_POST['message_id'] );
            }

            wp_die();
        }


/** Function honeypot4cf7_dismiss_notice() called by wp_ajax hooks: {'honeypot4cf7_dismiss_notice'} **/
/** No params detected :-/ **/


/** Function fetch_app_settings() called by wp_ajax hooks: {'cf7apps_fetch_settings', 'nopriv_cf7apps_fetch_settings'} **/
/** Parameters found in function fetch_app_settings(): {"post": ["formId"]} **/
function fetch_app_settings() {
            if ( $this->get_option( 'is_enabled' ) ) {

                if ( isset( $_POST['formId'] ) ) {
                    $form_id             = intval( wp_unslash( $_POST['formId'] ) );
                    $global_app_enabled  = $this->get_option( 'global_settings' ); // from global app settings.
                    $form_settings      = $this->get_individual_option( $form_id );
                    $default_settings   = $this->get_default_settings();
                    
                    // If global settings enabled: Individual settings override, otherwise global applies. If global disabled: Form uses Individual settings only.
                    if ( $global_app_enabled ) {
                        // Check if form has custom redirection settings (excluding enable_redirection)
                        $has_custom_settings = ! empty( $form_settings ) && (
                            isset( $form_settings['redirection_type'] ) ||
                            isset( $form_settings['post_type'] ) ||
                            isset( $form_settings['external_url'] ) ||
                            isset( $form_settings['new_tab'] )
                        );
                        
                        if ( $has_custom_settings ) {
                            // Form has custom settings, check if form explicitly disabled redirection
                            if ( isset( $form_settings['enable_redirection'] ) && ! $form_settings['enable_redirection'] ) {
                                // Form toggle is OFF, skip redirection
                                wp_send_json_error(
                                    array(
                                        'message' => __( 'Redirection is not enabled for this form.', 'cf7apps' ),
                                    ),
                                    '403'
                                );
                            } else {
                                // Use form's custom settings (Individual settings override)
                                $settings = wp_parse_args( $form_settings, $default_settings );
                            }
                        } else {
                            // Form has no custom settings - use global settings
                            $global_settings = $this->get_option( null );
                            
                            if ( empty( $global_settings ) ) {
                                wp_send_json_error(
                                    array(
                                        'message' => __( 'Redirection settings not found.', 'cf7apps' ),
                                    ),
                                    '404'
                                );
                            }
                            
                            // Use global settings
                            $settings = wp_parse_args( $global_settings, $default_settings );
                            
                            // Check if form has explicitly disabled redirection (even without custom settings)
                            if ( isset( $form_settings['enable_redirection'] ) && ! $form_settings['enable_redirection'] ) {
                                // Form toggle is explicitly disabled - skip redirection
                                wp_send_json_error(
                                    array(
                                        'message' => __( 'Redirection is not enabled for this form.', 'cf7apps' ),
                                    ),
                                    '403'
                                );
                            }
                            
                            // Default to enabled when using global settings
                            $settings['enable_redirection'] = true;
                        }
                    } else {
                        // Global settings not enabled, check form's enable_redirection toggle
                        $enable_redirection = isset( $form_settings['enable_redirection'] ) 
                            ? $form_settings['enable_redirection'] 
                            : $default_settings['enable_redirection'];
                        
                        // If redirection is not enabled for this form, return error
                        if ( ! $enable_redirection ) {
                            wp_send_json_error(
                                array(
                                    'message' => __( 'Redirection is not enabled for this form.', 'cf7apps' ),
                                ),
                                '403'
                            );
                        }
                        
                        // Use form settings only
                        $settings = wp_parse_args( $form_settings, $default_settings );
                    }

                    if ( $settings ) {
                        $redirect_type = $settings['redirection_type'];
                        $redirect_to   = $settings[ $redirect_type ];
                        $new_tab       = $settings['new_tab'];

                        if ( 'post_type' === $redirect_type ) {
                            $redirect_to = get_permalink( $redirect_to );
                        }

                        wp_send_json_success(
                            array(
                                'redirectTo' => $redirect_to,
                                'newTab'     => $new_tab,
                                'isEnabled' => true,
                            ),
                            200
                        );
                    }
                }
                wp_send_json_error(
                    array(
                        'message' => __( 'Form ID is required.', 'cf7apps' ),
                    ),
                    '404'
                );
            }

            wp_send_json_error(
                array(
                    'message' => __( 'Redirection app is not enabled.', 'cf7apps' ),
                ),
                '403'
            );
        }


