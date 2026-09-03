<?php
/***
*
*Found actions: 51
*Found functions:45
*Extracted functions:37
*Total parameter names extracted: 19
*Overview: {'ajax_search_site': {'AjaxSearchSite'}, 'ajax_get_all_roles': {'AjaxGetAllRoles'}, 'dismiss_melapress_survey': {'dismiss_melapress_survey'}, 'store_slack_api_key_ajax': {'wsal_store_slack_api_key'}, '\\WSAL\\Writers\\CSV_Writer': {'wsal_export_csv_results'}, 'ajax_get_all_cpts': {'AjaxGetAllCPT'}, 'ajax_run_cleanup': {'AjaxRunCleanup'}, 'save_settings_ajax': {'notifications_data_save'}, 'wsal_download_failed_login_log': {'wsal_download_failed_login_log'}, 'ajax_retrieve_events_manually': {'retrieve_events_manually'}, 'dismiss_notice_addon_available': {'wsal_dismiss_notice_addon_available'}, 'store_old_cliet_data': {'mainwp_clients_add_client'}, 'send_daily_summary': {'send_daily_summary_now'}, 'dismiss_learndash_update_notice': {'dismiss_learndash_update_notice'}, 'dismiss_missing_aws_sdk_nudge': {'wsal_dismiss_missing_aws_sdk_nudge'}, 'ajax_disable_custom_field': {'AjaxDisableCustomField'}, 'dismiss_yearly_survey': {'dismiss_yearly_survey'}, 'dismiss_helper_plugin_needed_nudge': {'wsal_dismiss_helper_plugin_needed_nudge'}, 'dismiss_feature_highlight_notice': {'wsal_dismiss_feature_highlight_notice'}, 'send_test_email': {'wsal_send_notifications_test_email'}, 'store_twilio_api_key_ajax': {'wsal_store_twilio_api_key'}, 'on_destroy_user_session': {'destroy-sessions'}, 'ajax_check_security_token': {'AjaxCheckSecurityToken'}, 'run_addon_install': {'wsal_run_addon_install'}, 'take_melapress_survey': {'take_melapress_survey'}, 'ajax_disable_by_code': {'AjaxDisableByCode'}, 'dismiss_black_friday': {'dismiss_black_friday'}, 'setup_check_security_token': {'setup_check_security_token'}, 'process_report_download': {'wsal_report_download'}, 'ajax_dismiss_notice': {'AjaxDismissNotice'}, 'AjaxInspector': {'AjaxInspector'}, 'dismiss_setup_modal': {'wsal_dismiss_setup_modal'}, 'ajax_get_all_severities': {'wsal_ajax_get_all_severities'}, 'ajax_get_all_event_ids': {'wsal_ajax_get_all_event_ids'}, 'dismiss_wp_pointer': {'wsal_dismiss_wp_pointer'}, 'purge_activity': {'wsal_purge_activity'}, 'ajax_switch_db': {'AjaxSwitchDB'}, 'ajax_get_all_object_types': {'wsal_ajax_get_all_object_types'}, 'ajax_get_all_event_types': {'wsal_ajax_get_all_event_types'}, 'dismiss_upgrade_notice': {'wsal_dismiss_upgrade_notice'}, 'manage_options': {'wsal_settings_get_posts_titles', 'wsal_settings_get_ips', 'wsal_settings_get_posts', 'wsal_settings_get_posts_ids', 'wsal_settings_get_sites', 'wsal_settings_get_users', 'wsal_settings_get_roles'}, 'reset_settings': {'wsal_reset_settings'}, 'ajax_get_all_users': {'AjaxGetAllUsers'}, 'dismiss_ebook_notice': {'wsal_dismiss_ebook_notice'}, 'ajax_get_all_stati': {'AjaxGetAllStatuses'}}
*
***/

/** Function ajax_search_site() called by wp_ajax hooks: {'AjaxSearchSite'} **/
/** Parameters found in function ajax_search_site(): {"post": ["search"]} **/
function ajax_search_site() {
		if ( ! Settings_Helper::current_user_can( 'view' ) ) {
			die( 'Access Denied.' );
		}

		// Verify nonce.
		\check_ajax_referer( 'wsal_auditlog_viewer_nonce', 'nonce' );

		$search = \sanitize_text_field( \wp_unslash( $_POST['search'] ?? '' ) );

		if ( empty( $search ) ) {
			die( 'Search parameter expected.' );
		}
		$grp1 = array();
		$grp2 = array();

		foreach ( WP_Helper::get_sites() as $site ) {
			if ( stripos( $site->blogname, $search ) !== false ) {
				$grp1[] = $site;
			} elseif ( stripos( $site->domain, $search ) !== false ) {
				$grp2[] = $site;
			}
		}
		die( json_encode( array_slice( $grp1 + $grp2, 0, 7 ) ) ); // phpcs:ignore
	}


/** Function ajax_get_all_roles() called by wp_ajax hooks: {'AjaxGetAllRoles'} **/
/** No params detected :-/ **/


/** Function dismiss_melapress_survey() called by wp_ajax hooks: {'dismiss_melapress_survey'} **/
/** No params detected :-/ **/


/** Function store_slack_api_key_ajax() called by wp_ajax hooks: {'wsal_store_slack_api_key'} **/
/** Parameters found in function store_slack_api_key_ajax(): {"post": ["_wpnonce", "slack_auth"]} **/
function store_slack_api_key_ajax() {
			if ( \wp_doing_ajax() ) {
				if ( isset( $_POST['_wpnonce'] ) ) {
					$nonce_check = \wp_verify_nonce( \sanitize_text_field( \wp_unslash( $_POST['_wpnonce'] ) ), self::NONCE_NAME );
					if ( ! $nonce_check ) {
						\wp_send_json_error( new \WP_Error( 500, \esc_html__( 'Nonce checking failed', 'wp-security-audit-log' ) ), 400 );
					}
				} else {
					\wp_send_json_error( new \WP_Error( 500, \esc_html__( 'Nonce is not provided', 'wp-security-audit-log' ) ), 400 );
				}
			} else {
				\wp_send_json_error( new \WP_Error( 500, \esc_html__( 'Not allowed', 'wp-security-audit-log' ) ), 400 );
			}

			if ( \current_user_can( 'manage_options' ) ) {

				$slack_token = \sanitize_text_field( \wp_unslash( $_POST['slack_auth'] ?? '' ) );
				$options     = Notifications::get_global_notifications_setting();

				/**
				 * If the submitted token matches the masked stored value, the user did not change it.
				 * Keep the existing DB value and return success.
				 */
				if ( Credential_Settings_Helper::is_submitted_credential_unchanged( $slack_token, $options['slack_notification_auth_token'] ?? '' ) ) {
					\wp_send_json_success();
				}

				$result = Credential_Settings_Helper::validate_and_save_slack(
					$slack_token,
					$options
				);

				if ( false !== $result ) {
					Notifications::set_global_notifications_setting( $result );
					\wp_send_json_success();
				}

				$slack_cred_error_message = \__( 'Invalid Slack credentials: No token provided or the provided one is invalid. Please check and provide the details again.', 'wp-security-audit-log' );

				$concat_slack_error = \esc_html( $slack_cred_error_message . Slack_API::get_slack_error() );

				\wp_send_json_error( $concat_slack_error );
			}
			\wp_send_json_error( new \WP_Error( 500, \esc_html__( 'Not allowed', 'wp-security-audit-log' ) ), 400 );
		}


/** Function \WSAL\Writers\CSV_Writer() called by wp_ajax hooks: {'wsal_export_csv_results'} **/
/** No function found :-/ **/


/** Function ajax_get_all_cpts() called by wp_ajax hooks: {'AjaxGetAllCPT'} **/
/** No params detected :-/ **/


/** Function ajax_run_cleanup() called by wp_ajax hooks: {'AjaxRunCleanup'} **/
/** Parameters found in function ajax_run_cleanup(): {"request": ["nonce"]} **/
function ajax_run_cleanup() {
		// Verify nonce.
		if ( ! isset( $_REQUEST['nonce'] ) || false === wp_verify_nonce( \sanitize_text_field( \wp_unslash( $_REQUEST['nonce'] ) ), 'wsal-run-cleanup' ) ) {
			wp_send_json_error( esc_html__( 'Insecure request.', 'wp-security-audit-log' ) );
		}

		if ( ! Settings_Helper::current_user_can( 'edit' ) ) {
			die( 'Access Denied.' );
		}

		$now       = time();
		$max_sdate = Plugin_Settings_Helper::get_pruning_date(); // Pruning date.
		$archiving = Settings_Helper::is_archiving_set_and_enabled();

		// phpcs:disable
		// phpcs:enable

		// Calculate limit timestamp.
		$max_stamp = $now - ( strtotime( $max_sdate ) - $now );

		$items = array();

		if ( $archiving ) {
			$connection_name = Settings_Helper::get_option_value( 'archive-connection' );

			$wsal_db = Connection::get_connection( $connection_name );

			$items = Delete_Records::delete( array(), 0, array( 'created_on <= %s' => intval( $max_stamp ) ), $wsal_db );
		}

		$main_items = Delete_Records::delete( array(), 0, array( 'created_on <= %s' => intval( $max_stamp ) ) );

		if ( $archiving ) {
			$archiving_args = array(
				'page' => 'wsal-ext-settings',
				'tab'  => 'archiving',
			);
			$archiving_url  = add_query_arg( $archiving_args, \network_admin_url( 'admin.php' ) );
			wp_safe_redirect( $archiving_url );
		} else {
			if ( ( is_array( $items ) && count( $items ) ) || ( is_array( $main_items ) && count( $main_items ) ) ) {
				$redirect_args = array(
					'tab'     => 'audit-log',
					'pruning' => 1,
				);
			} else {
				$redirect_args = array(
					'tab'     => 'audit-log',
					'pruning' => 0,
				);
			}
			wp_safe_redirect( add_query_arg( $redirect_args, $this->get_url() ) );
		}
		exit;
	}


/** Function save_settings_ajax() called by wp_ajax hooks: {'notifications_data_save'} **/
/** No params detected :-/ **/


/** Function wsal_download_failed_login_log() called by wp_ajax hooks: {'wsal_download_failed_login_log'} **/
/** No params detected :-/ **/


/** Function ajax_retrieve_events_manually() called by wp_ajax hooks: {'retrieve_events_manually'} **/
/** Parameters found in function ajax_retrieve_events_manually(): {"post": ["nonce"]} **/
function ajax_retrieve_events_manually() {
			if ( ! isset( $_POST['nonce'] ) || false === \wp_verify_nonce( \sanitize_text_field( \wp_unslash( $_POST['nonce'] ) ), 'wsal-notifications-script-nonce' ) ) {
				\wp_send_json_error( new \WP_Error( 'invalid_nonce', \esc_html__( 'Insecure request.', 'wp-security-audit-log' ) ), 403 );
			}

			if ( ! Settings_Helper::current_user_can( 'edit' ) ) {
				\wp_send_json_error( new \WP_Error( 'access_denied', \esc_html__( 'Access denied.', 'wp-security-audit-log' ) ), 403 );
			}

			self::retrieve_events_manually();

			\wp_send_json_success();
		}


/** Function dismiss_notice_addon_available() called by wp_ajax hooks: {'wsal_dismiss_notice_addon_available'} **/
/** No function found :-/ **/


/** Function store_old_cliet_data() called by wp_ajax hooks: {'mainwp_clients_add_client'} **/
/** Parameters found in function store_old_cliet_data(): {"post": ["selected_sites", "client_fields"]} **/
function store_old_cliet_data() {
			$selected_sites = ( isset( $_POST['selected_sites'] ) && is_array( $_POST['selected_sites'] ) ) ? array_map( 'sanitize_text_field', \wp_unslash( $_POST['selected_sites'] ) ) : array(); //phpcs:ignore WordPress.Security.NonceVerification.Missing
			$client_fields  = isset( $_POST['client_fields'] ) ? \wp_unslash( $_POST['client_fields'] ) : array(); //phpcs:ignore WordPress.Security.NonceVerification.Missing,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

			$client_id = isset( $client_fields['client_id'] ) ? intval( $client_fields['client_id'] ) : 0;

			self::$new_sites = array_unique( $selected_sites );

			self::$new_sites = \array_map(
				function ( $site ) {
					return (int) $site;
				},
				self::$new_sites
			);

			if ( $client_id ) {
				self::$old_client_data = MainWP_DB_Client::instance()->get_wp_client_by( 'client_id', $client_id );

				self::$old_sites = \array_keys( MainWP_DB_Client::instance()->get_websites_by_client_ids( array( $client_id ) ) );
			}
		}


/** Function send_daily_summary() called by wp_ajax hooks: {'send_daily_summary_now'} **/
/** Parameters found in function send_daily_summary(): {"request": ["_wpnonce", "weekly"]} **/
function send_daily_summary() {
			if ( ! \current_user_can( 'manage_options' ) ) {
				\wp_die(
					\esc_html__(
						'You do not have sufficient permissions to access this page.',
						'wp-security-audit-log'
					)
				);
			}

			if ( isset( $_REQUEST['_wpnonce'] ) ) {
				$nonce_check = \wp_verify_nonce( \sanitize_text_field( \wp_unslash( $_REQUEST['_wpnonce'] ) ), self::BUILT_IN_SEND_NOW_NONCE_NAME );
				if ( ! $nonce_check ) {
					\wp_send_json_error( new \WP_Error( 500, \esc_html__( 'Nonce checking failed', 'wp-security-audit-log' ) ), 400 );
				}
			} else {
				\wp_send_json_error( new \WP_Error( 500, \esc_html__( 'Nonce is not provided', 'wp-security-audit-log' ) ), 400 );
			}

			$report = Notification_Helper::get_report( true );

			if ( ! $report ) {
				\wp_send_json_error( __( 'Daily report is empty', 'wp-security-audit-log' ) );
			}

			// Summary email address.
			$summary_emails = Settings_Helper::get_option_value( self::BUILT_IN_NOTIFICATIONS_SETTINGS_NAME, array() );

			$email_from = 'daily_email_address';

			if ( isset( $_REQUEST['weekly'] ) && 1 === (int) $_REQUEST['weekly'] ) {
				$email_from = 'weekly_email_address';
			}

			if ( isset( $summary_emails[ $email_from ] ) ) {
				$summary_emails = $summary_emails[ $email_from ];
			}

			$summary_emails = explode( ',', $summary_emails );
			$result         = false;
			if ( $summary_emails && isset( $report['subject'] ) && isset( $report['body'] ) ) {
				foreach ( $summary_emails as $email ) {
					$result = Notification_Helper::send_notification_email( $email, $report['subject'], $report['body'] );
				}
			}

			if ( $result ) {
				\wp_send_json_success();
			} else {
				\wp_send_json_error( __( 'The plugin failed to send the email. Is your email set up correctly? Please consult with your website administrator or contact us to assist you with the troubleshooting.', 'wp-security-audit-log' ) );
			}

			exit();
		}


/** Function dismiss_learndash_update_notice() called by wp_ajax hooks: {'dismiss_learndash_update_notice'} **/
/** No params detected :-/ **/


/** Function dismiss_missing_aws_sdk_nudge() called by wp_ajax hooks: {'wsal_dismiss_missing_aws_sdk_nudge'} **/
/** No function found :-/ **/


/** Function ajax_disable_custom_field() called by wp_ajax hooks: {'AjaxDisableCustomField'} **/
/** Parameters found in function ajax_disable_custom_field(): {"post": ["notice", "object_type"]} **/
function ajax_disable_custom_field() {
			if ( ! Settings_Helper::current_user_can( 'edit' ) ) {
				\wp_send_json_error(
					array(
						'message' => \esc_html__( 'Error: You do not have sufficient permissions to disable this custom field.', 'wp-security-audit-log' ),
					),
					403
				);
			}

			$notice = \sanitize_text_field( \wp_unslash( $_POST['notice'] ?? '' ) );

			$nonce_is_valid = \check_ajax_referer( 'disable-custom-nonce' . $notice, 'disable_nonce', false );

			if ( false === $nonce_is_valid ) {
				\wp_send_json_error(
					array(
						'message' => \esc_html__( 'Error: Invalid nonce.', 'wp-security-audit-log' ),
					),
					403
				);
			}

			$notice = \str_replace( array( "'", '"', ',' ), '', $notice );

			// Validate object type.
			$object_type_post = \sanitize_text_field( \wp_unslash( $_POST['object_type'] ?? '' ) );
			$object_type      = ( 'user' === $object_type_post ) ? 'user' : 'post';
			$excluded_meta    = array();

			// Get current excluded meta fields.
			if ( 'post' === $object_type ) {
				$excluded_meta = Settings_Helper::get_excluded_post_meta_fields();
			} elseif ( 'user' === $object_type ) {
				$excluded_meta = Settings_Helper::get_excluded_user_meta_fields();
			}

			// Add the new field to the array.
			if ( ! \in_array( $notice, $excluded_meta, true ) ) {
				$excluded_meta[] = $notice;
			}

			// Save updated excluded meta fields.
			if ( 'post' === $object_type ) {
				Settings_Helper::set_excluded_post_meta_fields( $excluded_meta );
			} elseif ( 'user' === $object_type ) {
				Settings_Helper::set_excluded_user_meta_fields( $excluded_meta );
			}

			/**
			 * Build response messages. These will end up inside <p> tags on the JS side.
			 */
			// Message 1: "Custom field [FIELD_NAME] is no longer being monitored.".
			/* translators: %s: Custom field name */
			$message_1 = \esc_html__( 'Custom field %s is no longer being monitored.', 'wp-security-audit-log' );
			$p1_parts  = \explode( '%s', $message_1, 2 );
			$p1_parts  = ( 2 === count( $p1_parts ) ) ? $p1_parts : array( $message_1, '' );

			// Message 2: "Enable monitoring again from the [TAB_NAME] tab in settings.".
			/* translators: %s: Settings tab name */
			$message_2 = \esc_html__( 'Enable the monitoring of this custom field again from the %s tab in the plugin settings.', 'wp-security-audit-log' );
			$p2_parts  = \explode( '%s', $message_2, 2 );
			$p2_parts  = ( 2 === count( $p2_parts ) ) ? $p2_parts : array( $message_2, '' );

			// Exclude object settings link.
			$exclude_objects_link = \add_query_arg(
				array(
					'page' => 'wsal-settings',
					'tab'  => 'exclude-objects',
				),
				\network_admin_url( 'admin.php' )
			);

			\wp_send_json_success(
				array(
					'notice'       => $notice,
					'settings_url' => \esc_url_raw( $exclude_objects_link ),
					'tab_label'    => \esc_html__( 'Excluded Objects', 'wp-security-audit-log' ),
					'p1_parts'     => $p1_parts,
					'p2_parts'     => $p2_parts,
				)
			);
		}


/** Function dismiss_yearly_survey() called by wp_ajax hooks: {'dismiss_yearly_survey'} **/
/** No params detected :-/ **/


/** Function dismiss_helper_plugin_needed_nudge() called by wp_ajax hooks: {'wsal_dismiss_helper_plugin_needed_nudge'} **/
/** No function found :-/ **/


/** Function dismiss_feature_highlight_notice() called by wp_ajax hooks: {'wsal_dismiss_feature_highlight_notice'} **/
/** Parameters found in function dismiss_feature_highlight_notice(): {"post": ["nonce"]} **/
function dismiss_feature_highlight_notice() {
			if ( ! Settings_Helper::current_user_can( 'edit' ) ) {
				\wp_send_json_error();
			}

			if ( ! \wp_verify_nonce( \sanitize_text_field( \wp_unslash( $_POST['nonce'] ?? '' ) ), 'dismiss_feature_highlight_notice' ) ) {
				\wp_send_json_error( \esc_html__( 'nonce is not provided or incorrect', 'wp-security-audit-log' ) );
			}

			Settings_Helper::delete_option_value( Abstract_Migration::FEATURE_HIGHLIGHT_NOTICE );
			\wp_send_json_success();
		}


/** Function send_test_email() called by wp_ajax hooks: {'wsal_send_notifications_test_email'} **/
/** No function found :-/ **/


/** Function store_twilio_api_key_ajax() called by wp_ajax hooks: {'wsal_store_twilio_api_key'} **/
/** Parameters found in function store_twilio_api_key_ajax(): {"post": ["_wpnonce", "twilio_sid", "twilio_auth", "twilio_id"]} **/
function store_twilio_api_key_ajax() {
			if ( \wp_doing_ajax() ) {
				if ( isset( $_POST['_wpnonce'] ) ) {
					$nonce_check = \wp_verify_nonce( \sanitize_text_field( \wp_unslash( $_POST['_wpnonce'] ) ), self::NONCE_NAME );
					if ( ! $nonce_check ) {
						\wp_send_json_error( new \WP_Error( 500, \esc_html__( 'Nonce checking failed', 'wp-security-audit-log' ) ), 400 );
					}
				} else {
					\wp_send_json_error( new \WP_Error( 500, \esc_html__( 'Nonce is not provided', 'wp-security-audit-log' ) ), 400 );
				}
			} else {
				\wp_send_json_error( new \WP_Error( 500, \esc_html__( 'Not allowed', 'wp-security-audit-log' ) ), 400 );
			}

			if ( \current_user_can( 'manage_options' ) ) {

				$twilio_sid   = \sanitize_text_field( \wp_unslash( $_POST['twilio_sid'] ?? '' ) );
				$twilio_auth  = \sanitize_text_field( \wp_unslash( $_POST['twilio_auth'] ?? '' ) );
				$twilio_phone = \sanitize_text_field( \wp_unslash( $_POST['twilio_id'] ?? '' ) );
				$options      = Notifications::get_global_notifications_setting();

				$sid_masked   = Credential_Settings_Helper::is_submitted_credential_unchanged( $twilio_sid, $options['twilio_notification_account_sid'] ?? '' );
				$auth_masked  = Credential_Settings_Helper::is_submitted_credential_unchanged( $twilio_auth, $options['twilio_notification_auth_token'] ?? '' );
				$phone_masked = Credential_Settings_Helper::is_submitted_credential_unchanged( $twilio_phone, $options['twilio_notification_phone_number'] ?? '' );

				/**
				 * If all three fields are unchanged, the user changed nothing.
				 * Keep the existing DB values and return success immediately.
				 */
				if ( $sid_masked && $auth_masked && $phone_masked ) {
					\wp_send_json_success();
				}

				/**
				 * If some fields are masked and others are new, substitute the
				 * stored decrypted values for the masked fields so validation
				 * can run against a complete set of credentials.
				 */
				if ( $sid_masked ) {
					$twilio_sid = $options['twilio_notification_account_sid'] ?? '';
				}

				if ( $auth_masked ) {
					$twilio_auth = $options['twilio_notification_auth_token'] ?? '';
				}

				if ( $phone_masked ) {
					$twilio_phone = $options['twilio_notification_phone_number'] ?? '';
				}

				$result = Credential_Settings_Helper::validate_and_save_twilio(
					$twilio_sid,
					$twilio_auth,
					$twilio_phone,
					$options
				);

				if ( false !== $result ) {
					Notifications::set_global_notifications_setting( $result );
					\wp_send_json_success();
				}

				$twilio_cred_error_message = \__( 'Invalid Twilio credentials: No keys and numbers provided or the provided ones are invalid. Please check and provide the details again.', 'wp-security-audit-log' );

				$concat_twilio_error = \esc_html( $twilio_cred_error_message . Twilio_API::get_twilio_error() );

				\wp_send_json_error( $concat_twilio_error );
			}
			\wp_send_json_error( new \WP_Error( 500, \esc_html__( 'Not allowed', 'wp-security-audit-log' ) ), 400 );
		}


/** Function on_destroy_user_session() called by wp_ajax hooks: {'destroy-sessions'} **/
/** Parameters found in function on_destroy_user_session(): {"post": ["user_id", "nonce"]} **/
function on_destroy_user_session() {

			if ( ! isset( $_POST['user_id'] ) ) {
				return;
			}

			$user = \get_userdata( (int) $_POST['user_id'] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

			if ( $user ) {
				if ( ! \current_user_can( 'edit_user', $user->ID ) ) {
					$user = false;
				} elseif ( isset( $_POST['nonce'] ) && ! \wp_verify_nonce( $_POST['nonce'], 'update-user_' . $user->ID ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
					$user = false;
				}
			}

			if ( $user ) {
				// Destroy all the session of the same user from user profile page.

				Alert_Manager::trigger_event(
					1006,
					array(
						'TargetUserID' => $user->ID,
					)
				);
			}
		}


/** Function ajax_check_security_token() called by wp_ajax hooks: {'AjaxCheckSecurityToken'} **/
/** Parameters found in function ajax_check_security_token(): {"post": ["nonce", "token", "type"]} **/
function ajax_check_security_token() {
		if ( ! Settings_Helper::current_user_can( 'edit' ) ) {
			echo wp_json_encode(
				array(
					'success' => false,
					'message' => esc_html__( 'Access Denied.', 'wp-security-audit-log' ),
				)
			);
			die();
		}

		$nonce = isset( $_POST['nonce'] ) ? \sanitize_text_field( \wp_unslash( $_POST['nonce'] ) ) : false;
		$token = isset( $_POST['token'] ) ? \sanitize_text_field( \wp_unslash( $_POST['token'] ) ) : false;

		if ( empty( $nonce ) || ! \wp_verify_nonce( $nonce, 'wsal-exclude-nonce' ) ) {
			echo wp_json_encode(
				array(
					'success' => false,
					'message' => esc_html__( 'Nonce verification failed.', 'wp-security-audit-log' ),
				)
			);
			die();
		}

		if ( empty( $token ) ) {
			echo wp_json_encode(
				array(
					'success' => false,
					'message' => esc_html__( 'Invalid input.', 'wp-security-audit-log' ),
				)
			);
			die();
		}

		$input_type = isset( $_POST['type'] ) ? \sanitize_text_field( \wp_unslash( $_POST['type'] ) ) : false;

		echo wp_json_encode(
			array(
				'success'   => true,
				'token'     => $token,
				'tokenType' => esc_html( Plugin_Settings_Helper::get_token_type( $token, $input_type ) ),
			)
		);
		die();
	}


/** Function run_addon_install() called by wp_ajax hooks: {'wsal_run_addon_install'} **/
/** Parameters found in function run_addon_install(): {"post": ["addon_for", "plugin_url", "plugin_slug"]} **/
function run_addon_install() {
			\check_ajax_referer( 'wsal-install-addon' );

			// Verify user has permission to install and activate plugins.
			if ( ! \current_user_can( 'install_plugins' ) || ! \current_user_can( 'activate_plugins' ) ) {
				\wp_send_json_error(
					array(
						'message' => esc_html__( 'You do not have permission to install plugins.', 'wp-security-audit-log' ),
					)
				);
			}

			$predefined_plugins = Plugins_Helper::get_installable_plugins();

			// Setup empties to avoid errors.
			$plugin_zip  = '';
			$plugin_slug = '';

			if ( ! ( isset( $_POST['addon_for'] ) && is_array( $predefined_plugins ) ) ) {
				// no 'addon_for' passed, check for a zip and slug.
				$plugin_zip  = ( isset( $_POST['plugin_url'] ) ) ? esc_url_raw( wp_unslash( $_POST['plugin_url'] ) ) : '';
				$plugin_slug = ( isset( $_POST['plugin_slug'] ) ) ? sanitize_textarea_field( wp_unslash( $_POST['plugin_slug'] ) ) : '';
			} else {
				/*
				 * Key POSTed as an 'addon_for', try get the zip and slug.
				 *
				 * @since 4.0.2
				 */
				$addon = sanitize_text_field( wp_unslash( $_POST['addon_for'] ) );
				$addon = apply_filters( 'wsal_modify_predefined_plugin_slug', $addon );

				foreach ( $predefined_plugins as $plugin ) {
					if ( strtolower( $plugin['addon_for'] ) === $addon ) {
						$plugin_zip  = $plugin['plugin_url'];
						$plugin_slug = $plugin['plugin_slug'];
					}
				}
			}

			// validate that the plugin is in the allowed list, or it is our helper plugin with external libraries.
			$valid                      = false;
			$helper_plugin_installation = 'wsal-external-libraries/wsal-external-libraries.php' === $plugin_slug;
			if ( $helper_plugin_installation ) {
				$valid = true;
			} else {
				foreach ( $predefined_plugins as $plugin ) {
					// if we have a valid plugin then break.
					if ( $valid ) {
						break;
					}

					$valid = $plugin_zip === $plugin['plugin_url'] && $plugin_slug === $plugin['plugin_slug'];
				}
			}

			// bail early if we didn't get a valid url and slug to install.
			if ( ! $valid ) {
				\wp_send_json_error(
					array(
						'message' => esc_html__( 'Tried to install a zip or slug that was not in the allowed list', 'wp-security-audit-log' ),
					)
				);
			}

			// Check if the plugin is installed.
			if ( self::is_plugin_installed( $plugin_slug ) ) {
				// If plugin is installed but not active, activate it.
				if ( ! WP_Helper::is_plugin_active( $plugin_zip ) ) {
					self::run_activate( $plugin_slug );
					self::activate( $plugin_zip );
					$result = 'activated';
				} else {
					$result = 'already_installed';
				}
			} else {
				// No plugin found or plugin not present to be activated, so lets install it.
				self::install_plugin( $plugin_zip );
				self::run_activate( $plugin_slug );
				self::activate( $plugin_zip );
				$result = 'success';
			}

			// If we're installing our helper plugin, we also need to delete the nudge to install the helper plugin.
			if ( $helper_plugin_installation ) {
				\WSAL\Helpers\Settings_Helper::delete_option_value( 'show-helper-plugin-needed-nudge' );
			}

			\wp_send_json( $result );
		}


/** Function take_melapress_survey() called by wp_ajax hooks: {'take_melapress_survey'} **/
/** No params detected :-/ **/


/** Function ajax_disable_by_code() called by wp_ajax hooks: {'AjaxDisableByCode'} **/
/** Parameters found in function ajax_disable_by_code(): {"post": ["disable_nonce", "code"]} **/
function ajax_disable_by_code() {
			// Die if user does not have permission to disable.
			if ( ! Settings_Helper::current_user_can( 'edit' ) ) {
				echo '<p>' . esc_html__( 'Error: You do not have sufficient permissions to disable this alert.', 'wp-security-audit-log' ) . '</p>';
				die();
			}

			$disable_nonce = ( isset( $_POST['disable_nonce'] ) ) ? \sanitize_text_field( \wp_unslash( $_POST['disable_nonce'] ) ) : null;
			$code          = ( isset( $_POST['code'] ) ) ? \sanitize_text_field( \wp_unslash( $_POST['code'] ) ) : null;

			if ( ! isset( $disable_nonce ) || ! \wp_verify_nonce( $disable_nonce, 'disable-alert-nonce' . $code ) ) {
				die();
			}

			$s_alerts = Settings_Helper::get_option_value( 'disabled-alerts' );

			if ( is_string( $s_alerts ) ) {
				$s_alerts = \array_filter( \array_map( 'trim', \explode( ',', $s_alerts ) ) );
			}

			if ( isset( $s_alerts ) && '' !== $s_alerts ) {
				$s_alerts[] = (int) esc_html( $code );

				Settings_Helper::set_disabled_alerts( $s_alerts );
			}

			echo wp_sprintf(
				// translators: Alert code which is no longer monitored.
				'<p>' . esc_html__( 'Alert %1$s is no longer being monitored. %2$s', 'wp-security-audit-log' ) . '</p>',
				\esc_html( $code ),
				'<br />' . esc_html__( 'You can enable this alert again from the Enable/Disable Alerts node in the plugin menu.', 'wp-security-audit-log' )
			);
			die;
		}


/** Function dismiss_black_friday() called by wp_ajax hooks: {'dismiss_black_friday'} **/
/** No params detected :-/ **/


/** Function setup_check_security_token() called by wp_ajax hooks: {'setup_check_security_token'} **/
/** Parameters found in function setup_check_security_token(): {"post": ["nonce", "token"]} **/
function setup_check_security_token() {
			if ( ! Settings_Helper::current_user_can( 'edit' ) ) {
				echo wp_json_encode(
					array(
						'success' => false,
						'message' => esc_html__( 'Access Denied.', 'wp-security-audit-log' ),
					)
				);
				die();
			}

			$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : false;
			$token = isset( $_POST['token'] ) ? sanitize_text_field( wp_unslash( $_POST['token'] ) ) : false;

			if ( empty( $nonce ) || ! \wp_verify_nonce( $nonce, 'wsal-verify-wizard-page' ) ) {
				echo \wp_json_encode(
					array(
						'success' => false,
						'message' => esc_html__( 'Nonce verification failed.', 'wp-security-audit-log' ),
					)
				);
				die();
			}

			if ( empty( $token ) ) {
				echo wp_json_encode(
					array(
						'success' => false,
						'message' => esc_html__( 'Invalid input.', 'wp-security-audit-log' ),
					)
				);
				die();
			}

			echo \wp_json_encode(
				array(
					'success'   => true,
					'token'     => $token,
					'tokenType' => esc_html( Plugin_Settings_Helper::get_token_type( $token ) ),
				)
			);
			die();
		}


/** Function process_report_download() called by wp_ajax hooks: {'wsal_report_download'} **/
/** No function found :-/ **/


/** Function ajax_dismiss_notice() called by wp_ajax hooks: {'AjaxDismissNotice'} **/
/** No params detected :-/ **/


/** Function AjaxInspector() called by wp_ajax hooks: {'AjaxInspector'} **/
/** Parameters found in function AjaxInspector(): {"get": ["occurrence"]} **/
function AjaxInspector() {
		if ( ! Settings_Helper::current_user_can( 'view' ) ) {
			die( 'Access Denied.' );
		}

		// Verify nonce.
		\check_ajax_referer( 'wsal_auditlog_viewer_nonce', 'nonce' );

		$occurrence_id = (int) \wp_unslash( $_GET['occurrence'] ?? 0 );

		if ( empty( $occurrence_id ) ) {
			die( 'Occurrence parameter expected.' );
		}

		$wsal_db = Connection::get_connection();


		$alert_meta = Occurrences_Entity::get_meta_array( $occurrence_id, array(), $wsal_db );

		unset( $alert_meta['ReportText'] );

		echo '<div class="event-content-wrapper">';

		foreach ( $alert_meta as $item => $value ) {
			if ( $value ) {
				if ( is_array( $value ) || is_object( $value ) ) {
					$value = var_export( $value, true );
				}
				if ( 'severity' === mb_strtolower( $item ) ) {
					$value .= ' (' . ( Constants::get_severity_by_code( (int) $value ) ['text'] ?? '' ) . ')';
				}
				echo '<strong>' . \esc_html( $item ) . ':</strong> <span style="opacity: 0.7;"><pre style="display:inline">' . \esc_html( $value ) . '</pre></span></br>';
			}
		}

		$occurrence         = (array) Occurrences_Entity::load( 'id = %d', array( $occurrence_id ), $wsal_db );
		$inspected_alert_id = (int) ( $occurrence['alert_id'] ?? 0 );

		\do_action( 'wsal_inspector_after_meta', $inspected_alert_id );

		echo '</div>';
		wp_die();
	}


/** Function dismiss_setup_modal() called by wp_ajax hooks: {'wsal_dismiss_setup_modal'} **/
/** No params detected :-/ **/


/** Function ajax_get_all_severities() called by wp_ajax hooks: {'wsal_ajax_get_all_severities'} **/
/** No params detected :-/ **/


/** Function ajax_get_all_event_ids() called by wp_ajax hooks: {'wsal_ajax_get_all_event_ids'} **/
/** No params detected :-/ **/


/** Function dismiss_wp_pointer() called by wp_ajax hooks: {'wsal_dismiss_wp_pointer'} **/
/** Parameters found in function dismiss_wp_pointer(): {"post": ["pointer"]} **/
function dismiss_wp_pointer() {
		if ( ! Settings_Helper::current_user_can( 'view' ) ) {
			wp_die( 0 );
		}

		// Verify nonce.
		\check_ajax_referer( 'wsal_dismiss_wp_pointer', 'nonce' );

		if ( isset( $_POST['pointer'] ) ) {
			$pointer = sanitize_text_field( wp_unslash( $_POST['pointer'] ) );

			if ( sanitize_key( $pointer ) !== $pointer ) {
				wp_die( 0 );
			}

			$dismissed = array_filter( explode( ',', (string) Settings_Helper::get_option_value( 'dismissed-privacy-notice', true ) ) );

			if ( in_array( $pointer, $dismissed, true ) ) {
				wp_die( 0 );
			}

			$dismissed[] = $pointer;
			$dismissed   = implode( ',', $dismissed );

			Settings_Helper::set_option_value( 'dismissed-privacy-notice', $dismissed );
			wp_die( 1 );
		}
	}


/** Function purge_activity() called by wp_ajax hooks: {'wsal_purge_activity'} **/
/** No params detected :-/ **/


/** Function ajax_switch_db() called by wp_ajax hooks: {'AjaxSwitchDB'} **/
/** No function found :-/ **/


/** Function ajax_get_all_object_types() called by wp_ajax hooks: {'wsal_ajax_get_all_object_types'} **/
/** No params detected :-/ **/


/** Function ajax_get_all_event_types() called by wp_ajax hooks: {'wsal_ajax_get_all_event_types'} **/
/** No params detected :-/ **/


/** Function dismiss_upgrade_notice() called by wp_ajax hooks: {'wsal_dismiss_upgrade_notice'} **/
/** Parameters found in function dismiss_upgrade_notice(): {"post": ["nonce"]} **/
function dismiss_upgrade_notice() {
			if ( ! Settings_Helper::current_user_can( 'edit' ) ) {
				\wp_send_json_error();
			}

			if ( ! array_key_exists( 'nonce', $_POST ) || ! wp_verify_nonce( \sanitize_text_field( \wp_unslash( $_POST['nonce'] ) ), 'dismiss_upgrade_notice' ) ) {
				\wp_send_json_error( \esc_html_e( 'nonce is not provided or incorrect', 'wp-security-audit-log' ) );
			}

			Settings_Helper::delete_option_value( Abstract_Migration::UPGRADE_NOTICE );
			\wp_send_json_success();
		}


/** Function manage_options() called by wp_ajax hooks: {'wsal_settings_get_posts_titles', 'wsal_settings_get_ips', 'wsal_settings_get_posts', 'wsal_settings_get_posts_ids', 'wsal_settings_get_sites', 'wsal_settings_get_users', 'wsal_settings_get_roles'} **/
/** No function found :-/ **/


/** Function reset_settings() called by wp_ajax hooks: {'wsal_reset_settings'} **/
/** Parameters found in function reset_settings(): {"post": ["nonce"]} **/
function reset_settings() {
		// Die if user does not have permission to change settings.
		if ( ! Settings_Helper::current_user_can( 'edit' ) ) {
			wp_send_json_error( esc_html__( 'Access Denied.', 'wp-security-audit-log' ) );
		}

		// Verify nonce.
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( \sanitize_text_field( \wp_unslash( $_POST['nonce'] ) ), 'wsal-reset-settings' ) ) {
			wp_send_json_error( esc_html__( 'Nonce Verification Failed.', 'wp-security-audit-log' ) );
		}

		// Delete all settings.
		Settings_Helper::delete_all_settings();

		// Log settings reset event.
		Alert_Manager::trigger_event( 6006 );
		wp_send_json_success( esc_html__( 'Plugin settings have been reset.', 'wp-security-audit-log' ) );
	}


/** Function ajax_get_all_users() called by wp_ajax hooks: {'AjaxGetAllUsers'} **/
/** No params detected :-/ **/


/** Function dismiss_ebook_notice() called by wp_ajax hooks: {'wsal_dismiss_ebook_notice'} **/
/** Parameters found in function dismiss_ebook_notice(): {"post": ["nonce"]} **/
function dismiss_ebook_notice() {
			if ( ! Settings_Helper::current_user_can( 'edit' ) ) {
				\wp_send_json_error();
			}

			if ( ! array_key_exists( 'nonce', $_POST ) || ! wp_verify_nonce( \sanitize_text_field( \wp_unslash( $_POST['nonce'] ) ), 'dismiss_ebook_notice' ) ) {
				\wp_send_json_error( \esc_html_e( 'nonce is not provided or incorrect', 'wp-security-audit-log' ) );
			}

			Settings_Helper::set_option_value( self::EBOOK_NOTICE, true );
			\wp_send_json_success();
		}


/** Function ajax_get_all_stati() called by wp_ajax hooks: {'AjaxGetAllStatuses'} **/
/** No params detected :-/ **/


