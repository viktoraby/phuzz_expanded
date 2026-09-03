<?php
/***
*
*Found actions: 24
*Found functions:24
*Extracted functions:24
*Total parameter names extracted: 19
*Overview: {'update_post_smtp_pro_option_office365_callback': {'update_post_smtp_pro_option_office365'}, 'dismiss_version_notify': {'dismiss_version_notify'}, 'update_post_smtp_pro_option_callback': {'update_post_smtp_pro_option'}, 'resend_email': {'ps-resend-email'}, 'dismiss_notice_ajax': {'dismiss_smtp_conflict_notice'}, 'delete_logs_ajax': {'ps-delete-email-logs'}, '_toggle_debug_mode': {'fs_toggle_debug_mode'}, 'migrate_logs': {'ps-migrate-logs'}, 'export_log_ajax': {'ps-export-email-logs'}, 'discard_less_secure_notification': {'ps-discard-less-secure-notification'}, 'post_smtp_log_trash_all': {'post_smtp_log_trash_all'}, 'ajax_get_gmail_auth_url': {'ps_get_gmail_auth_url'}, 'delete_lock_file': {'delete_lock_file'}, 'test_mail': {'ps-mail-test'}, 'dismiss_donation_notify': {'dismiss_donation_notify'}, 'send_test_notification_ajax': {'postman_send_test_notification'}, 'save_wizard': {'ps-save-wizard'}, 'post_smtp_save_widget_meta_ajax': {'post_smtp_dash_widget_lite_save_widget_meta'}, 'view_log_ajax': {'ps-view-log'}, 'post_user_feedback': {'post_user_feedback'}, 'ajax_get_office365_auth_url': {'ps_get_office365_auth_url'}, 'dismiss_notice_ajax_callback': {'fs_dismiss_notice_action_{$ajax_action_suffix}'}, 'dismiss_update_notice': {'ps-db-update-notice-dismiss'}, 'get_logs_ajax': {'ps-get-email-logs'}}
*
***/

/** Function update_post_smtp_pro_option_office365_callback() called by wp_ajax hooks: {'update_post_smtp_pro_option_office365'} **/
/** Parameters found in function update_post_smtp_pro_option_office365_callback(): {"post": ["_wpnonce", "enabled"]} **/
function update_post_smtp_pro_option_office365_callback() {

        // Capability check: Only allow admins
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => 'Unauthorized.' ) );
            return;
        }

        // Nonce check for CSRF protection
        if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update_post_smtp_pro_option' ) ) {
            wp_send_json_error( array( 'message' => 'Invalid or missing nonce.' ) );
            return;
        }

        if ( ! isset( $_POST['enabled'] ) ) {
            wp_send_json_error( array( 'message' => 'Invalid request.' ) );
            return;
        }

        $options = get_option( 'post_smtp_pro', [] );
        if ( ! isset( $options['extensions'] ) ) {
            $options['extensions'] = [];
        }

        $enabled_value = sanitize_text_field( $_POST['enabled'] );

        // Check version requirement for Office 365 one-click
        if ( ! empty( $enabled_value ) ) {
            $required_pro_version = '1.5.0';
            if ( defined( 'POST_SMTP_PRO_VERSION' ) ) {
                $current_pro_version = POST_SMTP_PRO_VERSION;
                if ( version_compare( $current_pro_version, $required_pro_version, '<' ) ) {
                    wp_send_json_error( array( 
                        'message' => sprintf( 
                            __( 'Post SMTP Pro version %1$s or higher is required for Office 365 One-Click Setup. Current version: %2$s', 'post-smtp' ), 
                            $required_pro_version, 
                            $current_pro_version 
                        )
                    ) );
                    return;
                }
            } else {
                wp_send_json_error( array( 'message' => 'Post SMTP Pro version could not be determined.' ) );
                return;
            }
        }

        // Remove existing Office 365 related extensions
        $options['extensions'] = array_diff( $options['extensions'], ['microsoft-365', 'microsoft-one-click'] );

        if ( ! empty( $enabled_value ) ) {
            // If one-click is enabled, add both microsoft-365 and microsoft-one-click
            $options['extensions'][] = 'microsoft-365';
            $options['extensions'][] = 'microsoft-one-click';
        } else {
            // If one-click is disabled, only add microsoft-365
            $options['extensions'][] = 'microsoft-365';
        }

        // Remove duplicates
        $options['extensions'] = array_unique( $options['extensions'] );

        update_option( 'post_smtp_pro', $options );

        // Check for Office 365 OAuth token and user email
        $office365_oauth = get_option( 'postman_office365_oauth' );
        $has_access_token = false;
        $has_email = false;
        $user_email = '';

        if ( $office365_oauth && is_array( $office365_oauth ) ) {
            if ( isset( $office365_oauth['access_token'] ) && ! empty( $office365_oauth['access_token'] ) ) {
                $has_access_token = true;
            }

            if ( isset( $office365_oauth['user_email'] ) && ! empty( $office365_oauth['user_email'] ) ) {
                $has_email = true;
                $user_email = sanitize_email( $office365_oauth['user_email'] );
            }
        }

        wp_send_json_success( array( 
            'message' => 'Option updated successfully!',
            'has_access_token' => $has_access_token,
            'has_email' => $has_email,
            'user_email' => $user_email,
        ) );
    }


/** Function dismiss_version_notify() called by wp_ajax hooks: {'dismiss_version_notify'} **/
/** No params detected :-/ **/


/** Function update_post_smtp_pro_option_callback() called by wp_ajax hooks: {'update_post_smtp_pro_option'} **/
/** Parameters found in function update_post_smtp_pro_option_callback(): {"post": ["_wpnonce", "enabled"]} **/
function update_post_smtp_pro_option_callback() {

        // Capability check: Only allow admins
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => 'Unauthorized.' ) );
            return;
        }

        // Nonce check for CSRF protection
        if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update_post_smtp_pro_option' ) ) {
            wp_send_json_error( array( 'message' => 'Invalid or missing nonce.' ) );
            return;
        }

        if ( ! isset( $_POST['enabled'] ) ) {
            wp_send_json_error( array( 'message' => 'Invalid request.' ) );
            return;
        }

        $options = get_option( 'post_smtp_pro', [] );
        if ( ! isset( $options['extensions'] ) ) {
            $options['extensions'] = [];
        }

        $enabled_value = sanitize_text_field( $_POST['enabled'] );

        if ( ! empty( $enabled_value ) ) {
            if ( ! in_array( $enabled_value, $options['extensions'] ) ) {
                $options['extensions'][] = $enabled_value;
            }
        } else {
            $options['extensions'] = array_diff( $options['extensions'], ['gmail-oneclick'] );
        }

        update_option( 'post_smtp_pro', $options );

        wp_send_json_success( array( 'message' => 'Option updated successfully!' ) );
    }


/** Function resend_email() called by wp_ajax hooks: {'ps-resend-email'} **/
/** No params detected :-/ **/


/** Function dismiss_notice_ajax() called by wp_ajax hooks: {'dismiss_smtp_conflict_notice'} **/
/** Parameters found in function dismiss_notice_ajax(): {"post": ["nonce", "notice_id"]} **/
function dismiss_notice_ajax() {
        // Verify nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'postman_smtp_conflict_nonce' ) ) {
            wp_send_json_error( array( 'message' => 'Security check failed' ) );
        }

        // Check user capability
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => 'Insufficient permissions' ) );
        }

        // Validate notice ID
        if ( ! isset( $_POST['notice_id'] ) ) {
            wp_send_json_error( array( 'message' => 'Notice ID is required' ) );
        }

        $notice_id = sanitize_key( $_POST['notice_id'] );
        if ( empty( $notice_id ) ) {
            wp_send_json_error( array( 'message' => 'Invalid notice ID' ) );
        }

        $dismissed_notices = get_option( self::DISMISSED_NOTICES_OPTION, array() );
        
        // Store dismissal with current timestamp (7 days expiration)
        $dismissed_notices[ $notice_id ] = time();
        
        if ( update_option( self::DISMISSED_NOTICES_OPTION, $dismissed_notices ) ) {
            wp_send_json_success( array( 'message' => 'Notice dismissed successfully' ) );
        } else {
            wp_send_json_error( array( 'message' => 'Failed to save dismissal' ) );
        }
    }


/** Function delete_logs_ajax() called by wp_ajax hooks: {'ps-delete-email-logs'} **/
/** Parameters found in function delete_logs_ajax(): {"post": ["security", "action", "selected"]} **/
function delete_logs_ajax() {

		if( !wp_verify_nonce( $_POST['security'], 'security' ) ) {

            return;

        }

        // Check if user has permission to manage email logs
        if ( ! current_user_can( Postman::MANAGE_POSTMAN_CAPABILITY_LOGS ) ) {
            wp_send_json_error( __( 'Sorry, you are not allowed to delete email logs.', 'post-smtp' ) );
            return;
        }
		
		if( isset( $_POST['action'] ) && $_POST['action'] == 'ps-delete-email-logs' ) {

			$args = array();
            $deleted_all = false;

			//Delete all
			if( !isset( $_POST['selected'] ) ) {

				$args = array( -1 );
                $deleted_all = true;

			}
			//Delete selected
			else {

				$args = wp_parse_id_list( $_POST['selected'] );

			}

			$email_query_log = new PostmanEmailQueryLog();
			$delete = $email_query_log->delete_logs( $args );

			if( $delete ) {

                /**
                 * Fires after deleting logs
                 * 
                 * @param array $args
                 * @since 2.5.0
                 * @version 1.0.0
                 */
                do_action( 'postman_delete_logs_successfully', $args );

				$response = array(
					'success' => true,
					'message' => __( 'Logs deleted successfully', 'post-smtp' ),
                    'deleted_all' => $deleted_all
				);

			}
			else {

				$response = array(
					'success' => false,
					'message' => __( 'Error deleting logs', 'post-smtp' ),
                    'deleted_all' => $deleted_all
				);

			}

			wp_send_json( $response );

		}

	}


/** Function _toggle_debug_mode() called by wp_ajax hooks: {'fs_toggle_debug_mode'} **/
/** No params detected :-/ **/


/** Function migrate_logs() called by wp_ajax hooks: {'ps-migrate-logs'} **/
/** Parameters found in function migrate_logs(): {"post": ["security", "action"]} **/
function migrate_logs() {

        if( wp_verify_nonce( $_POST['security'], 'ps-migrate-logs' ) ) {

            if( isset( $_POST['action'] ) && $_POST['action'] == 'ps-migrate-logs' ) {
    
                if( $this->have_old_logs ) {

                    $this->log( 'Info: `migrate_logs` Have old logs' );
    
                    $old_logs = $this->get_old_logs();
        
                    if( $old_logs && !empty( $old_logs )) {
    
                        //Migrating Logs
                        foreach( $old_logs as $ID => $log ) {

                            $this->log( 'Info: `migrate_logs` Remove extra keys if contains: ' . print_r( array_keys( $log ), true ) );

                            $log = $this->remove_extra_keys( $log );

                            $this->log( 'Info: `migrate_logs` Migrating Log: ' . print_r( array_keys( $log ), true ) );
                
                            $result = PostmanEmailLogs::get_instance()->save( $log );
            
                            if( $result ) {

                                $this->log( 'Info: `migrate_logs` Log migrated' );
    
                                $result = wp_update_post( 
                                    array( 
                                        'ID'        =>  $ID,
                                        'pinged'    =>  1 
                                    )
                                );
    
                            }
                            else {

                                $this->log( 'Error: `migrate_logs` Log not migrated: ID: ' . $ID . print_r( array_keys( $log ), true ) );

                            }
    
                        }

                        //If all migrated
                        if( $this->get_migrated_count() ==  wp_count_posts( 'postman_sent_mail' )->private ) {

                            $this->log( 'Info: `migrate_logs` All logs migrated' );

                            delete_option( 'ps_migrate_logs' );
    
                            wp_send_json_success( 
                                array(
                                    'migrated'  =>  $this->get_migrated_count(),
                                    'total'     =>  wp_count_posts( 'postman_sent_mail' )->private
                                ), 
                                200 
                            );  

                        }

                        $this->log( 'Info: `migrate_logs` Logs migrated: ' . $this->get_migrated_count(). ' Out of ' . wp_count_posts( 'postman_sent_mail' )->private );
    
                        wp_send_json_success( 
                            array( 
                                'migrated'  =>   $this->get_migrated_count()
                            ), 
                            200 
                        );
    
                    }
        
        
                }
    
            }

        }

    }


/** Function export_log_ajax() called by wp_ajax hooks: {'ps-export-email-logs'} **/
/** Parameters found in function export_log_ajax(): {"post": ["security", "action", "selected"]} **/
function export_log_ajax() {

		if( !wp_verify_nonce( $_POST['security'], 'security' ) ) {

            return;

        }

        // Check if user has permission to export email logs
        if ( ! current_user_can( Postman::MANAGE_POSTMAN_CAPABILITY_LOGS ) ) {
            wp_send_json_error( __( 'Sorry, you are not allowed to export email logs.', 'post-smtp' ) );
            return;
        }

		if( isset( $_POST['action'] ) && $_POST['action'] == 'ps-export-email-logs' ) {

			$args = array();

			//Export all
			if( !isset( $_POST['selected'] ) ) {

				$args = array( -1 );

			}
			//Export selected
			else {

				$args = wp_parse_id_list( $_POST['selected'] );

			}

			$email_query_log = new PostmanEmailQueryLog();
			$logs = $email_query_log->get_all_logs( $args );
            $csv_headers = array(
                'solution',
                'response',
                'from_header',
                'to_header',
                'cc_header',
                'bcc_header',
                'reply_to_header',
                'transport_uri',
                'original_to',
                'original_subject',
                'original_message',
                'original_headers',
                'session_transcript',
                'delivery_time'
            );
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="email-logs.csv"');

            $fp = fopen('php://output', 'wb');

            $headers = $csv_headers;

            fputcsv($fp, $headers);

            $date_format = get_option( 'date_format' );
	        $time_format = get_option( 'time_format' );

            foreach ( $logs as $log ) {

                $data[0] = $log->solution;
                $data[1] = $log->success == 1 ? 'Sent' : $log->success;
                $data[2] = $log->from_header;
                $data[3] = $log->to_header;
                $data[4] = $log->cc_header;
                $data[5] = $log->bcc_header;
                $data[6] = $log->reply_to_header;
                $data[7] = $log->transport_uri;
                $data[8] = $log->original_to;
                $data[9] = $log->original_subject;
                $data[10] = $log->original_message;
                $data[11] = $log->original_headers;
                $data[12] = $log->session_transcript;
                $data[13] = date( "$date_format $time_format", $log->time );
                
                fputcsv($fp, $data);

            }

            fclose($fp);

            exit();

		}

	}


/** Function discard_less_secure_notification() called by wp_ajax hooks: {'ps-discard-less-secure-notification'} **/
/** Parameters found in function discard_less_secure_notification(): {"post": ["_wp_nonce"]} **/
function discard_less_secure_notification() {

			if( !wp_verify_nonce( $_POST['_wp_nonce'], 'less-secure-security' ) ) {
				die( 'Not Secure.' );
			}

			$result = update_option( 'ps_hide_less_secure', 1 );
			
			if( $result ) {
				wp_send_json_success( 
					array( 'message' => 'Success' ),
					200 
				);
			}

			wp_send_json_error( 
				array( 'message' => 'Something went wrong' ),
				500 
			);

		}


/** Function post_smtp_log_trash_all() called by wp_ajax hooks: {'post_smtp_log_trash_all'} **/
/** No params detected :-/ **/


/** Function ajax_get_gmail_auth_url() called by wp_ajax hooks: {'ps_get_gmail_auth_url'} **/
/** Parameters found in function ajax_get_gmail_auth_url(): {"post": ["nonce"]} **/
function ajax_get_gmail_auth_url() {

        // Capability check: Only allow administrators.
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => 'Unauthorized.' ), 403 );
        }

        // Nonce check for CSRF protection.
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ps_get_office365_auth_url' ) ) {
            wp_send_json_error( array( 'message' => 'Invalid or missing nonce.' ), 400 );
        }

        if ( ! function_exists( 'post_smtp_get_office365_auth_url' ) ) {
            wp_send_json_error( array( 'message' => 'Office 365 One-Click is not available.' ), 500 );
        }

        $auth_url = post_smtp_get_office365_auth_url();

        if ( empty( $auth_url ) ) {
            wp_send_json_error( array( 'message' => 'Failed to generate Office 365 auth URL.' ), 500 );
            if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ps_get_gmail_auth_url' ) ) {
                wp_send_json_error( array( 'message' => 'Invalid or missing nonce.' ), 400 );
            }

            if ( ! function_exists( 'post_smtp_get_gmail_auth_url' ) ) {
                wp_send_json_error( array( 'message' => 'Gmail One-Click is not available.' ), 500 );
            }

            $auth_url = post_smtp_get_gmail_auth_url();

            if ( empty( $auth_url ) ) {
                wp_send_json_error( array( 'message' => 'Failed to generate Gmail auth URL.' ), 500 );
            }

            wp_send_json_success( array( 'auth_url' => esc_url_raw( $auth_url ) ) );
        }
    }


/** Function delete_lock_file() called by wp_ajax hooks: {'delete_lock_file'} **/
/** No params detected :-/ **/


/** Function test_mail() called by wp_ajax hooks: {'ps-mail-test'} **/
/** Parameters found in function test_mail(): {"post": ["email", "socket", "apikey", "from"], "server": ["REMOTE_ADDR"]} **/
function test_mail() {
        check_admin_referer( 'post-smtp', 'security' );

        $email  = sanitize_email( $_POST['email'] ?? '' );
        $socket = sanitize_text_field( $_POST['socket'] ?? '' );
        $apikey = sanitize_text_field( $_POST['apikey'] ?? '' );
        $from_email = sanitize_email( $_POST['from'] ?? '' );
        $args = array(
            'method'  => WP_REST_Server::READABLE,
            'headers' => array(
                'X-MT-Token-WP' => $this->x_mt_token_wp,
                'IP'            => $_SERVER['REMOTE_ADDR'],
                'Site-URL'      => get_site_url(),
            ),
        );

        if ( !empty( $apikey ) ) {
            $args['headers']['API-KEY'] = $apikey;
        }
   
        if ( $this->requires_test_api_verification( $socket ) ) {
            $this->handle_sockets_check( $from_email, $args, $socket  );
        } 
        // else {
        //     $this->handle_legacy_test( $email, $args );
        // }
    }


/** Function dismiss_donation_notify() called by wp_ajax hooks: {'dismiss_donation_notify'} **/
/** No params detected :-/ **/


/** Function send_test_notification_ajax() called by wp_ajax hooks: {'postman_send_test_notification'} **/
/** Parameters found in function send_test_notification_ajax(): {"post": ["service"]} **/
function send_test_notification_ajax() {
		check_ajax_referer( 'postman_test_notification_nonce', 'nonce' );

		if ( ! current_user_can( Postman::MANAGE_POSTMAN_CAPABILITY_NAME ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Unauthorized.', 'post-smtp' ),
				),
				401
			);
		}

		// Get service from AJAX request, fallback to saved service
		$notification_service = isset( $_POST['service'] ) ? sanitize_text_field( $_POST['service'] ) : $this->options->getNotificationService();
		
		// Don't send if service is 'none' or empty
		if ( empty( $notification_service ) || 'none' === $notification_service ) {
			wp_send_json_error(
				array(
					'message' => __( 'No notification service selected. Please select a notification service first.', 'post-smtp' ),
				)
			);
		}

		// Prepare test message
		$subject = __( 'Test Notification – Post SMTP', 'post-smtp' );
		$message = __( 'This is a test notification message from Post SMTP.', 'post-smtp' ) . "\n\n";
		$message .= __( 'Your notification configuration is working correctly', 'post-smtp' ) . ' ✅';

		// Get the appropriate notifier based on selected service
		switch ( $notification_service ) {
			case 'default':
				$notifier = new PostmanMailNotify();
				break;
			case 'pushover':
				$notifier = new PostmanPushoverNotify();
				break;
			case 'slack':
				$notifier = new PostmanSlackNotify();
				break;
			case 'webhook_alerts':
				$notifier = new PostmanWebhookAlertsNotify();
				break;
			case 'twilio':
				// Check if Twilio extension is loaded
				if ( ! class_exists( 'PostSMTPTwilio\Notify' ) ) {
					wp_send_json_error(
						array(
							'message' => __( 'Twilio extension is not loaded.', 'post-smtp' ),
						)
					);
				}
				$notifier = new \PostSMTPTwilio\Notify();
				break;
			case 'microsoft-teams':
				// Check if Microsoft Teams extension is loaded
				if ( ! class_exists( 'PSP_MSTeam_Notification' ) ) {
					wp_send_json_error(
						array(
							'message' => __( 'Microsoft Teams extension is not loaded.', 'post-smtp' ),
						)
					);
				}
				$notifier = new \PSP_MSTeam_Notification();
				break;
			default:
				$notifier = apply_filters( 'post_smtp_notifier', null, $notification_service );
				if ( ! $notifier ) {
					wp_send_json_error(
						array(
							'message' => __( 'Notification service not supported.', 'post-smtp' ),
						)
					);
				}
		}

		// Validate required credentials based on service
		$is_valid = $this->validate_notification_config( $notification_service );

		if ( ! $is_valid['valid'] ) {
			wp_send_json_error(
				array(
					'message' => $is_valid['message'],
				)
			);
		}

		// Don't send test notification for email (default) service
		if ( 'default' === $notification_service ) {
			wp_send_json_error(
				array(
					'message' => __( 'Test notification is not available for Admin Email service.', 'post-smtp' ),
				)
			);
		}

		// Send test notification for other services
		try {
			// Check if notification was sent successfully
			$sent = $this->send_notification_and_check_result( $notifier, $notification_service, $message );
			
			if ( $sent['success'] ) {
				// Use custom success message if provided, otherwise use default
				$success_message = ! empty( $sent['message'] ) ? $sent['message'] : __( 'Test notification sent successfully!', 'post-smtp' );
				wp_send_json_success(
					array(
						'message' => $success_message,
					)
				);
			} else {
				wp_send_json_error(
					array(
						'message' => $sent['message'],
					)
				);
			}
		} catch ( Exception $e ) {
			wp_send_json_error(
				array(
					'message' => sprintf( __( 'Error sending test notification: %s', 'post-smtp' ), $e->getMessage() ),
				)
			);
		}
	}


/** Function save_wizard() called by wp_ajax hooks: {'ps-save-wizard'} **/
/** Parameters found in function save_wizard(): {"post": ["FormData", "action"]} **/
function save_wizard() {

        $form_data = array();
        if ( isset( $_POST['FormData'] ) ) {
            parse_str( wp_unslash( $_POST['FormData'] ), $form_data );
        }
        $response = false;

        if( 
            isset( $_POST['action'] )
            &&
            'ps-save-wizard' == $_POST['action'] 
            &&
            isset( $form_data['security'] )
            &&
            wp_verify_nonce( $form_data['security'], 'post-smtp' )
        ) {

            if( isset( $form_data['postman_options'] ) && !empty( $form_data['postman_options'] ) ) {
                
                $sanitized = post_smtp_sanitize_array( $form_data['postman_options'] );
                
                $options = get_option( PostmanOptions::POSTMAN_OPTIONS );
                $_options = $options;
                $options = $options ? $options : array();
                
                //for the checkboxes
                $sanitized['prevent_sender_email_override'] = isset( $sanitized['prevent_sender_email_override'] ) ? 1 : '';
                $sanitized['prevent_sender_name_override'] = isset( $sanitized['prevent_sender_name_override'] ) ? 1 : '';
                
                //Envelop Email Address
                $sanitized['envelope_sender'] = isset( $sanitized['sender_email'] ) ? $sanitized['sender_email'] : '';

                //Encode API Keys
                $sanitized['office365_app_id'] = isset( $sanitized['office365_app_id'] ) ? $sanitized['office365_app_id'] : '';
                $sanitized['office365_app_password'] = isset( $sanitized['office365_app_password'] ) ? $sanitized['office365_app_password'] : '';
                $sanitized[PostmanOptions::SENDINBLUE_API_KEY] = isset( $sanitized[PostmanOptions::SENDINBLUE_API_KEY] ) ? $sanitized[PostmanOptions::SENDINBLUE_API_KEY] : '';
                $sanitized[PostmanOptions::MAILTRAP_API_KEY] = isset( $sanitized[PostmanOptions::MAILTRAP_API_KEY] ) ? $sanitized[PostmanOptions::MAILTRAP_API_KEY] : '';
                $sanitized['sparkpost_api_key'] = isset( $sanitized['sparkpost_api_key'] ) ? $sanitized['sparkpost_api_key'] : '';
                $sanitized['postmark_api_key'] = isset( $sanitized['postmark_api_key'] ) ? $sanitized['postmark_api_key'] : '';
                $sanitized['mailgun_api_key'] = isset( $sanitized['mailgun_api_key'] ) ? $sanitized['mailgun_api_key'] : '';
                $sanitized[PostmanOptions::SENDGRID_API_KEY] = isset( $sanitized[PostmanOptions::SENDGRID_API_KEY] ) ? $sanitized[PostmanOptions::SENDGRID_API_KEY] : '';
                $sanitized['sendgrid_region']  = isset( $sanitized['sendgrid_region'] ) ? $sanitized['sendgrid_region'] : '';
                $sanitized['resend_api_key']  = isset( $sanitized['resend_api_key'] ) ? $sanitized['resend_api_key'] : '';
                $sanitized[PostmanOptions::EMAILIT_API_KEY]  = isset( $sanitized[PostmanOptions::EMAILIT_API_KEY] ) ? $sanitized[PostmanOptions::EMAILIT_API_KEY] : '';
                $sanitized[PostmanOptions::MAILEROO_API_KEY]  = isset( $sanitized[PostmanOptions::MAILEROO_API_KEY] ) ? $sanitized[PostmanOptions::MAILEROO_API_KEY] : '';
                $sanitized[PostmanOptions::SWEEGO_API_KEY]  = isset( $sanitized[PostmanOptions::SWEEGO_API_KEY] ) ? $sanitized[PostmanOptions::SWEEGO_API_KEY] : '';
                $sanitized['mandrill_api_key'] = isset( $sanitized['mandrill_api_key'] ) ? $sanitized['mandrill_api_key'] : '';
                $sanitized[PostmanOptions::MAILERSEND_API_KEY] = isset( $sanitized[PostmanOptions::MAILERSEND_API_KEY] ) ? $sanitized[PostmanOptions::MAILERSEND_API_KEY] : '';
                $sanitized['elasticemail_api_key'] = isset( $sanitized['elasticemail_api_key'] ) ? $sanitized['elasticemail_api_key'] : '';
                $sanitized[PostmanOptions::MAILJET_API_KEY] = isset( $sanitized[PostmanOptions::MAILJET_API_KEY] ) ? $sanitized[PostmanOptions::MAILJET_API_KEY] : '';
                $sanitized[PostmanOptions::MAILJET_SECRET_KEY] = isset( $sanitized[PostmanOptions::MAILJET_SECRET_KEY] ) ? $sanitized[PostmanOptions::MAILJET_SECRET_KEY] : '';
                $sanitized['basic_auth_password'] = isset( $sanitized['basic_auth_password'] ) ? $sanitized['basic_auth_password'] : '';
                $sanitized['ses_access_key_id'] = isset( $sanitized['ses_access_key_id'] ) ? $sanitized['ses_access_key_id'] : '';
                $sanitized['ses_secret_access_key'] = isset( $sanitized['ses_secret_access_key'] ) ? $sanitized['ses_secret_access_key'] : '';
                $sanitized['ses_region'] = isset( $sanitized['ses_region'] ) ? $sanitized['ses_region'] : '';
                $sanitized['enc_type'] = 'tls';
                $sanitized['auth_type'] = 'login';
                $sanitized['slack_token'] = base64_decode( isset( $options['slack_token'] ) ? $options['slack_token'] : '' );
                $sanitized['pushover_user'] = base64_decode( isset( $options['pushover_user'] ) ? $options['pushover_user'] : '' );
                $sanitized['pushover_token'] = base64_decode( isset( $options['pushover_token'] ) ? $options['pushover_token'] : '' );
                foreach( $sanitized as $key => $value ) {
                    $options[$key] = $value;
                }
                
                if( $options == $_options ) {
                    $response = true;
                } else {
                    $response = update_option( PostmanOptions::POSTMAN_OPTIONS , $options );
                    do_action( 'post_smtp_wizard_configuration_saved' );
                }
                
            }
            
        }

        // Prevent redirection after a normal settings save (see PostmanSession::ACTION).
        if ( class_exists( 'PostmanSession' ) ) {
            PostmanSession::getInstance()->unsetAction();
        } else {
            delete_transient( 'action' );
        }

        wp_send_json( array(), 200 );

    }


/** Function post_smtp_save_widget_meta_ajax() called by wp_ajax hooks: {'post_smtp_dash_widget_lite_save_widget_meta'} **/
/** Parameters found in function post_smtp_save_widget_meta_ajax(): {"post": ["meta", "value"]} **/
function post_smtp_save_widget_meta_ajax() {

			check_admin_referer( 'post_smtp_dash_widget_lite_nonce' );

			// if ( ! current_user_can( post_smtp()->get_capability_manage_options() ) ) {
				// wp_send_json_error();
			// }

			$meta  = ! empty( $_POST['meta'] ) ? sanitize_key( $_POST['meta'] ) : '';
			$value = ! empty( $_POST['value'] ) ? sanitize_key( $_POST['value'] ) : 0;

			$this->post_smtp_widget_meta( 'set', $meta, $value );

			wp_send_json_success();
		}


/** Function view_log_ajax() called by wp_ajax hooks: {'ps-view-log'} **/
/** Parameters found in function view_log_ajax(): {"post": ["security", "action", "id", "type"]} **/
function view_log_ajax() {

		if( !wp_verify_nonce( $_POST['security'], 'security' ) ) {

            return;

        }

        // Check if user has permission to view email logs
        if ( ! current_user_can( Postman::MANAGE_POSTMAN_CAPABILITY_LOGS ) ) {
            wp_send_json_error( __( 'Sorry, you are not allowed to view email logs.', 'post-smtp' ) );
            return;
        }

		if( isset( $_POST['action'] ) && $_POST['action'] == 'ps-view-log' ) {

			$id   = sanitize_text_field( $_POST['id'] );
			$type = array( sanitize_text_field( $_POST['type'] ) );
			$type = $type[0] == 'original_message' ? '' : $type;

			$email_query_log = new PostmanEmailQueryLog();
			$log             = $email_query_log->get_log( $id, $type );

			// When viewing the rendered message (original_message), we still want
			// reply_to_header available for the desktop popup details table.
			if ( empty( $type ) && ! isset( $log['reply_to_header'] ) ) {
				$extra = $email_query_log->get_log(
					$id,
					array(
						'reply_to_header',
					)
				);

				if ( is_array( $extra ) && isset( $extra['reply_to_header'] ) ) {
					$log['reply_to_header'] = $extra['reply_to_header'];
				}
			}
            $_log = $log;

            //Escape HTML
            foreach( $_log as $key => $value ) {

                if( $key == 'original_message') {

                    $log['log_url'] = admin_url( "admin.php?page=postman_email_log&view=log&log_id={$id}" );

                }
                else {
                
                    $log[$key] = esc_html( $value );
                
                }

            }

			if( isset( $log['time'] ) ) {

				//WordPress Date, Time Format
				$date_format = get_option( 'date_format' );
				$time_format = get_option( 'time_format' );
				
				$log['time'] = date( "{$date_format} {$time_format}", $log['time'] );

			}

			if( $log ) {

                /**
                 * Fires before viewing logs
                 * 
                 * @param array $log
                 * @param string $type
                 * @since 2.5.9
                 * @version 1.0.0
                 */
                $log = apply_filters( 'post_smtp_before_view_log', $log, $type );

				$response = array(
					'success' => true,
					'data' => $log,
				);

			}
			else {

				$response = array(
					'success' => false,
					'message' => __( 'Error Viewing', 'post-smtp' )
				);

			}

			wp_send_json( $response );

		}

	}


/** Function post_user_feedback() called by wp_ajax hooks: {'post_user_feedback'} **/
/** Parameters found in function post_user_feedback(): {"post": ["reason", "other_input", "support"]} **/
function post_user_feedback() {
		if ( ! check_ajax_referer() ) {
			die( 'security error' );
		}

		$payload = array(
			'reason' => sanitize_text_field( $_POST['reason'] ),
			'other_input' => isset( $_POST['other_input'] ) ? sanitize_text_field( $_POST['other_input'] ) : '',
		);

		if ( isset( $_POST['support'] ) ) {
			$payload['support']['email'] = sanitize_email( $_POST['support']['email'] );
			$payload['support']['title'] = sanitize_text_field( $_POST['support']['title'] );
			$payload['support']['text'] = sanitize_textarea_field( $_POST['support']['text'] );
		}

		$args = array(
			'body' => $payload,
			'timeout' => 20,
		);
		$result = wp_remote_post( 'https://postmansmtp.com/feedback', $args );
		die( 'success' );
	}


/** Function ajax_get_office365_auth_url() called by wp_ajax hooks: {'ps_get_office365_auth_url'} **/
/** Parameters found in function ajax_get_office365_auth_url(): {"post": ["nonce"]} **/
function ajax_get_office365_auth_url() {

        // Capability check: Only allow administrators.
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => 'Unauthorized.' ), 403 );
        }

        // Nonce check for CSRF protection.
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ps_get_office365_auth_url' ) ) {
            wp_send_json_error( array( 'message' => 'Invalid or missing nonce.' ), 400 );
        }

        if ( ! function_exists( 'post_smtp_get_office365_auth_url' ) ) {
            wp_send_json_error( array( 'message' => 'Office 365 One-Click is not available.' ), 500 );
        }

        $auth_url = post_smtp_get_office365_auth_url();

        if ( empty( $auth_url ) ) {
            wp_send_json_error( array( 'message' => 'Failed to generate Office 365 auth URL.' ), 500 );
        }

        wp_send_json_success( array( 'auth_url' => esc_url_raw( $auth_url ) ) );
    }


/** Function dismiss_notice_ajax_callback() called by wp_ajax hooks: {'fs_dismiss_notice_action_{$ajax_action_suffix}'} **/
/** Parameters found in function dismiss_notice_ajax_callback(): {"post": ["message_id"]} **/
function dismiss_notice_ajax_callback() {
            check_admin_referer( 'fs_dismiss_notice_action' );

            if ( ! is_numeric( $_POST['message_id'] ) ) {
                $this->_sticky_storage->remove( $_POST['message_id'] );
            }

            wp_die();
        }


/** Function dismiss_update_notice() called by wp_ajax hooks: {'ps-db-update-notice-dismiss'} **/
/** Parameters found in function dismiss_update_notice(): {"post": ["action", "security"]} **/
function dismiss_update_notice() {

        if( isset( $_POST['action'] ) && $_POST['action'] == 'ps-db-update-notice-dismiss' && wp_verify_nonce( $_POST['security'], 'ps-migrate-logs' ) ) {

            set_transient( 'ps_dismiss_update_notice', 1, WEEK_IN_SECONDS );

        }

    }


/** Function get_logs_ajax() called by wp_ajax hooks: {'ps-get-email-logs'} **/
/** Parameters found in function get_logs_ajax(): {"get": ["security", "action", "start", "length", "search", "order", "status", "site_id", "columns", "from", "to", "filter_by", "draw"]} **/
function get_logs_ajax() {
        if( !wp_verify_nonce( $_GET['security'], 'security' ) ) {

            return;

        }

        // Check if user has permission to view email logs
        if ( ! current_user_can( Postman::MANAGE_POSTMAN_CAPABILITY_LOGS ) ) {
            wp_send_json_error( __( 'Sorry, you are not allowed to access email logs.', 'post-smtp' ) );
            return;
        }
  
        if( isset( $_GET['action'] ) && $_GET['action'] == 'ps-get-email-logs' ) {

            $logs_query = new PostmanEmailQueryLog;

            $query = array();
            $query['start'] = sanitize_text_field( $_GET['start'] );
            $query['end'] = sanitize_text_field( $_GET['length'] );
            $query['search'] = sanitize_text_field( $_GET['search']['value'] );
            $query['order'] = sanitize_text_field( $_GET['order'][0]['dir'] );
            $query['status'] = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : '';
            
			//MainWP | Get Sites
            if( isset( $_GET['site_id'] ) ) {

                $query['site_id'] = sanitize_text_field( $_GET['site_id'] );

            }

            //Column Name
            $query['order_by'] = sanitize_text_field( $_GET['columns'][$_GET['order'][0]['column']]['data'] );

            //Date Filter :)
            if( isset( $_GET['from'] ) && !empty( $_GET['from'] ) ) {

                $query['from'] = strtotime( sanitize_text_field( $_GET['from'] ) );

            }
       
            if( isset( $_GET['to'] ) && !empty( $_GET['to'] ) ) {

                $query['to'] = strtotime( sanitize_text_field( $_GET['to'] ) ) + 86400;

            }

            if( isset( $_GET['filter_by'] ) && !empty( $_GET['filter_by'] ) ) {

                $query['filter_by'] = sanitize_text_field( $_GET['filter_by'] );

            }

            $data = $logs_query->get_logs( $query );
            //WordPress Date, Time Format
            $date_format = get_option( 'date_format' );
		    $time_format = get_option( 'time_format' );
            $search = array(
                '<',
                '>',
                '"',
                "'"
            );
            $replace = array(
                '&lt;',
                '&gt;',
                '&quot;',
                '&#039;'
            );
   
            //Lets manage the Date format :)
            foreach( $data as $row ) {

                $row->time = date( "{$date_format} {$time_format}", $row->time );

                if( $row->success == 1 ) {

                    $row->success = '<span title="Success">Success</span>';

                } else if( $row->success == 'Sent ( ** Fallback ** )' ){

                    $row->success = '<span title="Sent ( ** Fallback ** )">Success</span><a href="#" class="ps-status-log ps-popup-btn">View details</a>';
                    
               }
                elseif( $row->success == 'In Queue' ) {

                    $row->success = '<span title="In Queue">In Queue</span>';

                }
                else {

                    $row->success = '<span title="'.str_replace( $search, $replace, $row->success ).'">Failed</span><a href="#" class="ps-status-log ps-popup-btn">View details</a>';
                    
                }
                
        
                $row->actions = '';
				
				/**
                 * Filter the row data
                 * 
                 * @since 2.5.0
                 * @version 1.0.0
                 */
                $row = apply_filters( 'ps_email_logs_row', $row );

                //Escape HTML for safe output.
                $row->original_subject = esc_html( $row->original_subject );
                if ( isset( $row->event_type ) ) {
                    $row->event_type = esc_html( $row->event_type );
                }

            }

            $total_rows = $logs_query->get_total_row_count();
            $total_rows = ( is_array( $total_rows ) && !empty( $total_rows ) ) ? $total_rows[0] : '';
            $total_rows = isset( $total_rows->count ) ? (int)$total_rows->count : '';

            $filtered_rows = $logs_query->get_filtered_rows_count();
            $filtered_rows = ( is_array( $filtered_rows ) && !empty( $filtered_rows ) ) ? $filtered_rows[0] : '';
            $filtered_rows = isset( $filtered_rows->count ) ? (int)$filtered_rows->count : '';

            $logs['data'] = $data;
            $logs['recordsTotal'] = $total_rows;
            $logs['recordsFiltered'] = $filtered_rows;
            $logs['draw'] = sanitize_text_field( $_GET['draw'] );

            echo json_encode( $logs );
            die;

        }

    }


