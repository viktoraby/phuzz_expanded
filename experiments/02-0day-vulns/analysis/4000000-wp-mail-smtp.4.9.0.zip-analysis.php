<?php
/***
*
*Found actions: 30
*Found functions:29
*Extracted functions:29
*Total parameter names extracted: 19
*Overview: {'remove_oauth_connection': {'wp_mail_smtp_vue_remove_oauth_connection'}, 'get_partner_plugins_info': {'wp_mail_smtp_vue_get_partner_plugins_info'}, 'ajax_dismiss': {'wp_mail_smtp_email_sending_errors_dismiss', 'wp_mail_smtp_activelayer_wc_dismiss'}, 'notice_bar_ajax_dismiss': {'wp_mail_smtp_notice_bar_dismiss'}, 'ajax_snippet_install_url': {'wp_mail_smtp_get_wpcode_snippet_install_url'}, 'update_settings': {'wp_mail_smtp_vue_update_settings'}, 'upgrade_plugin': {'wp_mail_smtp_vue_upgrade_plugin'}, 'email_domain_check_test': {'health-check-email-domain_check_test'}, 'process_ajax_delete_all_debug_events': {'wp_mail_smtp_delete_all_debug_events'}, 'review_dismiss': {'wp_mail_smtp_review_dismiss'}, 'install_plugin': {'wp_mail_smtp_vue_install_plugin'}, 'wizard_steps_started': {'wp_mail_smtp_vue_wizard_steps_started'}, 'get_oauth_url': {'wp_mail_smtp_vue_get_oauth_url'}, 'ajax_generate_url': {'wp_mail_smtp_connect_url'}, 'ajax_disconnect': {'wp_mail_smtp_sendlayer_disconnect'}, 'process_ajax': {'wp_mail_smtp_ajax'}, 'process_ajax_debug_event_preview': {'wp_mail_smtp_debug_event_preview'}, 'check_mailer_configuration': {'wp_mail_smtp_vue_check_mailer_configuration'}, 'init_migrations_ajax_handler': {'nopriv_wp_mail_smtp_init_migrations'}, 'get_settings': {'wp_mail_smtp_vue_get_settings'}, 'subscribe_to_newsletter': {'wp_mail_smtp_vue_subscribe_to_newsletter'}, 'dismiss': {'wp_mail_smtp_notification_dismiss'}, 'import_settings': {'wp_mail_smtp_vue_import_settings'}, 'process': {'nopriv_wp_mail_smtp_connect_process'}, 'send_feedback': {'wp_mail_smtp_vue_send_feedback'}, 'ajax_check_plugin_status': {'wp_mail_smtp_page_check_{$plugin}_status'}, 'get_connected_data': {'wp_mail_smtp_vue_get_connected_data'}, 'ajax_init_connect': {'wp_mail_smtp_sendlayer_connect'}, 'ajax_snippet_code': {'wp_mail_smtp_get_wpcode_snippet_code'}}
*
***/

/** Function remove_oauth_connection() called by wp_ajax hooks: {'wp_mail_smtp_vue_remove_oauth_connection'} **/
/** Parameters found in function remove_oauth_connection(): {"post": ["mailer"]} **/
function remove_oauth_connection() { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		check_ajax_referer( 'wpms-admin-nonce', 'nonce' );

		if ( ! current_user_can( wp_mail_smtp()->get_capability_manage_global_options() ) ) {
			wp_send_json_error();
		}

		$mailer = ! empty( $_POST['mailer'] ) ? sanitize_text_field( wp_unslash( $_POST['mailer'] ) ) : '';

		if ( empty( $mailer ) ) {
			wp_send_json_error();
		}

		$options = Options::init();
		$old_opt = $options->get_all_raw();

		/*
		 * Since Gmail mailer uses the same settings array for both the custom app and One-Click Setup,
		 * we need to make sure we don't remove the wrong settings.
		 */
		if ( $mailer === 'gmail' ) {
			unset( $old_opt[ $mailer ]['access_token'] );
			unset( $old_opt[ $mailer ]['refresh_token'] );
			unset( $old_opt[ $mailer ]['user_details'] );
			unset( $old_opt[ $mailer ]['auth_code'] );
		} else {
			foreach ( $old_opt[ $mailer ] as $key => $value ) {
				// Unset everything except Client ID, Client Secret and Domain (for Zoho).
				if ( ! in_array( $key, [ 'domain', 'client_id', 'client_secret' ], true ) ) {
					unset( $old_opt[ $mailer ][ $key ] );
				}
			}
		}

		$options->set( $old_opt );

		wp_send_json_success();
	}


/** Function get_partner_plugins_info() called by wp_ajax hooks: {'wp_mail_smtp_vue_get_partner_plugins_info'} **/
/** No params detected :-/ **/


/** Function ajax_dismiss() called by wp_ajax hooks: {'wp_mail_smtp_email_sending_errors_dismiss', 'wp_mail_smtp_activelayer_wc_dismiss'} **/
/** Parameters found in function ajax_dismiss(): {"post": ["connection_id"]} **/
function ajax_dismiss() {

		if ( ! check_ajax_referer( 'wp-mail-smtp-admin', 'nonce', false ) ) {
			wp_send_json_error( esc_html__( 'Security check failed. Please reload the page and try again.', 'wp-mail-smtp' ) );
		}

		if ( ! current_user_can( wp_mail_smtp()->get_capability_manage_options() ) ) {
			wp_send_json_error( esc_html__( 'You don\'t have permission to perform this action.', 'wp-mail-smtp' ) );
		}

		$connection_id = isset( $_POST['connection_id'] ) ? sanitize_key( wp_unslash( $_POST['connection_id'] ) ) : '';

		if ( empty( $connection_id ) ) {
			wp_send_json_error( esc_html__( 'Missing connection identifier.', 'wp-mail-smtp' ) );
		}

		EmailSendingDebug::clear( $connection_id );

		wp_send_json_success();
	}


/** Function notice_bar_ajax_dismiss() called by wp_ajax hooks: {'wp_mail_smtp_notice_bar_dismiss'} **/
/** No params detected :-/ **/


/** Function ajax_snippet_install_url() called by wp_ajax hooks: {'wp_mail_smtp_get_wpcode_snippet_install_url'} **/
/** Parameters found in function ajax_snippet_install_url(): {"post": ["library_id"]} **/
function ajax_snippet_install_url() {

		$this->verify_ajax();

		// Nonce verified in verify_ajax().
		$library_id = isset( $_POST['library_id'] ) ? absint( wp_unslash( $_POST['library_id'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing

		if ( empty( $library_id ) ) {
			wp_send_json_error();
		}

		$snippet = ( new SnippetsProvider( ( new RegisterLibrary() )->get_username() ) )->get_snippet( $library_id );

		if ( empty( $snippet ) || empty( $snippet['install'] ) ) {
			wp_send_json_error();
		}

		wp_send_json_success( [ 'install_url' => $snippet['install'] ] );
	}


/** Function update_settings() called by wp_ajax hooks: {'wp_mail_smtp_vue_update_settings'} **/
/** Parameters found in function update_settings(): {"post": ["overwrite", "value"]} **/
function update_settings() {

		check_ajax_referer( 'wpms-admin-nonce', 'nonce' );

		if ( ! current_user_can( wp_mail_smtp()->get_capability_manage_global_options() ) ) {
			wp_send_json_error();
		}

		$options   = Options::init();
		$overwrite = ! empty( $_POST['overwrite'] );

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$value = isset( $_POST['value'] ) ? wp_slash( json_decode( wp_unslash( $_POST['value'] ), true ) ) : [];

		// Cancel summary report email task if summary report email was disabled.
		if (
			! SummaryReportEmail::is_disabled() &&
			isset( $value['general'][ SummaryReportEmail::SETTINGS_SLUG ] ) &&
			$value['general'][ SummaryReportEmail::SETTINGS_SLUG ] === true
		) {
			( new SummaryReportEmailTask() )->cancel();
		}

		/**
		 * Before updating settings in Setup Wizard.
		 *
		 * @since 3.3.0
		 *
		 * @param array $post POST data.
		 */
		do_action( 'wp_mail_smtp_admin_setup_wizard_update_settings', $value );

		$options->set( $value, false, $overwrite );

		wp_send_json_success();
	}


/** Function upgrade_plugin() called by wp_ajax hooks: {'wp_mail_smtp_vue_upgrade_plugin'} **/
/** Parameters found in function upgrade_plugin(): {"post": ["license_key"]} **/
function upgrade_plugin() {

		check_ajax_referer( 'wpms-admin-nonce', 'nonce' );

		if ( wp_mail_smtp()->is_pro() ) {
			wp_send_json_success( esc_html__( 'You are already using the WP Mail SMTP PRO version. Please refresh this page and verify your license key.', 'wp-mail-smtp' ) );
		}

		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_send_json_error( esc_html__( 'You don\'t have the permission to perform this action.', 'wp-mail-smtp' ) );
		}

		$license_key = ! empty( $_POST['license_key'] ) ? sanitize_key( $_POST['license_key'] ) : '';

		if ( empty( $license_key ) ) {
			wp_send_json_error( esc_html__( 'Please enter a valid license key!', 'wp-mail-smtp' ) );
		}

		$url = Connect::generate_url(
			$license_key,
			'',
			add_query_arg( 'upgrade-redirect', '1', self::get_site_url() ) . '#/step/license'
		);

		if ( empty( $url ) ) {
			wp_send_json_error( esc_html__( 'Upgrade functionality not available!', 'wp-mail-smtp' ) );
		}

		wp_send_json_success( [ 'redirect_url' => $url ] );
	}


/** Function email_domain_check_test() called by wp_ajax hooks: {'health-check-email-domain_check_test'} **/
/** No params detected :-/ **/


/** Function process_ajax_delete_all_debug_events() called by wp_ajax hooks: {'wp_mail_smtp_delete_all_debug_events'} **/
/** Parameters found in function process_ajax_delete_all_debug_events(): {"post": ["nonce"]} **/
function process_ajax_delete_all_debug_events() {

		if (
			empty( $_POST['nonce'] ) ||
			! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'wp_mail_smtp_debug_events' )
		) {
			wp_send_json_error( esc_html__( 'Access rejected.', 'wp-mail-smtp' ) );
		}

		if ( ! current_user_can( wp_mail_smtp()->get_capability_manage_options() ) ) {
			wp_send_json_error( esc_html__( 'You don\'t have the capability to perform this action.', 'wp-mail-smtp' ) );
		}

		if ( ! self::is_valid_db() ) {
			wp_send_json_error( esc_html__( 'For some reason the database table was not installed correctly. Please contact plugin support team to diagnose and fix the issue.', 'wp-mail-smtp' ) );
		}

		global $wpdb;

		$table = self::get_table_name();

		$sql = "TRUNCATE TABLE `$table`;";

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$result = $wpdb->query( $sql );

		if ( $result !== false ) {
			wp_send_json_success( esc_html__( 'All debug event entries were deleted successfully.', 'wp-mail-smtp' ) );
		}

		wp_send_json_error(
			sprintf( /* translators: %s - WPDB error message. */
				esc_html__( 'There was an issue while trying to delete all debug event entries. Error message: %s', 'wp-mail-smtp' ),
				$wpdb->last_error
			)
		);
	}


/** Function review_dismiss() called by wp_ajax hooks: {'wp_mail_smtp_review_dismiss'} **/
/** No params detected :-/ **/


/** Function install_plugin() called by wp_ajax hooks: {'wp_mail_smtp_vue_install_plugin'} **/
/** Parameters found in function install_plugin(): {"post": ["slug"]} **/
function install_plugin() { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded

		check_ajax_referer( 'wpms-admin-nonce', 'nonce' );

		// Check for permissions.
		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_send_json_error( esc_html__( 'Could not install the plugin. You don\'t have permission to install plugins.', 'wp-mail-smtp' ) );
		}

		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error( esc_html__( 'Could not install the plugin. You don\'t have permission to activate plugins.', 'wp-mail-smtp' ) );
		}

		$slug = ! empty( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';

		if ( empty( $slug ) ) {
			wp_send_json_error( esc_html__( 'Could not install the plugin. Plugin slug is missing.', 'wp-mail-smtp' ) );
		}

		if ( ! in_array( $slug, wp_list_pluck( $this->get_partner_plugins(), 'slug' ), true ) ) {
			wp_send_json_error( esc_html__( 'Could not install the plugin. Plugin is not whitelisted.', 'wp-mail-smtp' ) );
		}

		$url = esc_url_raw( WP::admin_url( 'admin.php?page=' . Area::SLUG . '-setup-wizard' ) );

		/*
		 * The `request_filesystem_credentials` function will output a credentials form in case of failure.
		 * We don't want that, since it will break AJAX response. So just hide output with a buffer.
		 */
		ob_start();
		// phpcs:ignore WPForms.Formatting.EmptyLineAfterAssigmentVariables.AddEmptyLine
		$creds = request_filesystem_credentials( $url, '', false, false, null );
		ob_end_clean();

		// Check for file system permissions.
		if ( false === $creds ) {
			wp_send_json_error( esc_html__( 'Could not install the plugin. Don\'t have file permission.', 'wp-mail-smtp' ) );
		}

		if ( ! WP_Filesystem( $creds ) ) {
			wp_send_json_error( esc_html__( 'Could not install the plugin. Don\'t have file permission.', 'wp-mail-smtp' ) );
		}

		// Do not allow WordPress to search/download translations, as this will break JS output.
		remove_action( 'upgrader_process_complete', [ 'Language_Pack_Upgrader', 'async_upgrade' ], 20 );

		// Import the plugin upgrader.
		Helpers::include_plugin_upgrader();

		// Create the plugin upgrader with our custom skin.
		$installer = new Plugin_Upgrader( new PluginsInstallSkin() );

		// Error check.
		if ( ! method_exists( $installer, 'install' ) || empty( $slug ) ) {
			wp_send_json_error( esc_html__( 'Could not install the plugin. WP Plugin installer initialization failed.', 'wp-mail-smtp' ) );
		}

		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		$api = plugins_api(
			'plugin_information',
			[
				'slug'   => $slug,
				'fields' => [
					'short_description' => false,
					'sections'          => false,
					'requires'          => false,
					'rating'            => false,
					'ratings'           => false,
					'downloaded'        => false,
					'last_updated'      => false,
					'added'             => false,
					'tags'              => false,
					'compatibility'     => false,
					'homepage'          => false,
					'donate_link'       => false,
				],
			]
		);

		if ( is_wp_error( $api ) ) {
			wp_send_json_error( $api->get_error_message() );
		}

		$installer->install( $api->download_link );

		// Flush the cache and return the newly installed plugin basename.
		wp_cache_flush();

		if ( $installer->plugin_info() ) {
			$plugin_basename = $installer->plugin_info();

			// Disable the WPForms redirect after plugin activation.
			if ( $slug === 'wpforms-lite' ) {
				update_option( 'wpforms_activation_redirect', true );
				add_option( 'wpforms_installation_source', 'wp-mail-smtp-setup-wizard' );
			}

			// Disable the AIOSEO redirect after plugin activation.
			if ( $slug === 'all-in-one-seo-pack' ) {
				update_option( 'aioseo_activation_redirect', true );
			}

			// Activate the plugin silently.
			$activated = activate_plugin( $plugin_basename );

			// Disable the RafflePress redirect after plugin activation.
			if ( $slug === 'rafflepress' ) {
				delete_transient( '_rafflepress_welcome_screen_activation_redirect' );
			}

			// Disable the MonsterInsights redirect after plugin activation.
			if ( $slug === 'google-analytics-for-wordpress' ) {
				delete_transient( '_monsterinsights_activation_redirect' );
			}

			// Disable the SeedProd redirect after the plugin activation.
			if ( $slug === 'coming-soon' ) {
				delete_transient( '_seedprod_welcome_screen_activation_redirect' );
			}

			if ( ! is_wp_error( $activated ) ) {
				wp_send_json_success(
					[
						'slug'         => $slug,
						'is_installed' => true,
						'is_activated' => true,
					]
				);
			} else {
				wp_send_json_success(
					[
						'slug'         => $slug,
						'is_installed' => true,
						'is_activated' => false,
					]
				);
			}
		}

		wp_send_json_error( esc_html__( 'Could not install the plugin. WP Plugin installer could not retrieve plugin information.', 'wp-mail-smtp' ) );
	}


/** Function wizard_steps_started() called by wp_ajax hooks: {'wp_mail_smtp_vue_wizard_steps_started'} **/
/** No params detected :-/ **/


/** Function get_oauth_url() called by wp_ajax hooks: {'wp_mail_smtp_vue_get_oauth_url'} **/
/** Parameters found in function get_oauth_url(): {"post": ["mailer", "settings"]} **/
function get_oauth_url() { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		check_ajax_referer( 'wpms-admin-nonce', 'nonce' );

		if ( ! current_user_can( wp_mail_smtp()->get_capability_manage_global_options() ) ) {
			wp_send_json_error();
		}

		$data   = [];
		$mailer = ! empty( $_POST['mailer'] ) ? sanitize_text_field( wp_unslash( $_POST['mailer'] ) ) : '';

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$settings = isset( $_POST['settings'] ) ? wp_slash( json_decode( wp_unslash( $_POST['settings'] ), true ) ) : [];

		if ( empty( $mailer ) ) {
			wp_send_json_error();
		}

		$settings = array_merge( $settings, [ 'is_setup_wizard_auth' => true ] );

		$options = Options::init();
		$options->set( [ $mailer => $settings ], false, false );

		switch ( $mailer ) {
			case 'gmail':
				$auth = wp_mail_smtp()->get_providers()->get_auth( 'gmail' );

				if ( $auth->is_clients_saved() && $auth->is_auth_required() ) {
					$data['oauth_url'] = $auth->get_auth_url();
				}
				break;
		}

		$data = apply_filters( 'wp_mail_smtp_admin_setup_wizard_get_oauth_url', $data, $mailer );

		wp_send_json_success( array_merge( [ 'mailer' => $mailer ], $data ) );
	}


/** Function ajax_generate_url() called by wp_ajax hooks: {'wp_mail_smtp_connect_url'} **/
/** Parameters found in function ajax_generate_url(): {"post": ["key"]} **/
function ajax_generate_url() { //phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		// Run a security check.
		check_ajax_referer( 'wp-mail-smtp-connect', 'nonce' );

		// Check for permissions.
		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_send_json_error(
				[
					'message' => esc_html__( 'You are not allowed to install plugins.', 'wp-mail-smtp' ),
				]
			);
		}

		$key = ! empty( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : '';

		if ( empty( $key ) ) {
			wp_send_json_error(
				[
					'message' => esc_html__( 'Please enter your license key to connect.', 'wp-mail-smtp' ),
				]
			);
		}

		if ( wp_mail_smtp()->is_pro() ) {
			wp_send_json_error(
				[
					'message' => esc_html__( 'Only the Lite version can be upgraded.', 'wp-mail-smtp' ),
				]
			);
		}

		// Verify pro version is not installed.
		$active = activate_plugin( 'wp-mail-smtp-pro/wp_mail_smtp.php', false, false, true );

		if ( ! is_wp_error( $active ) ) {

			// Deactivate Lite.
			deactivate_plugins( plugin_basename( WPMS_PLUGIN_FILE ) );

			wp_send_json_success(
				[
					'message' => esc_html__( 'WP Mail SMTP Pro was already installed, but was not active. We activated it for you.', 'wp-mail-smtp' ),
					'reload'  => true,
				]
			);
		}

		$url = self::generate_url( $key );

		if ( empty( $url ) ) {
			wp_send_json_error(
				[
					'message' => esc_html__( 'There was an error while generating an upgrade URL. Please try again.', 'wp-mail-smtp' ),
				]
			);
		}

		wp_send_json_success( [ 'url' => $url ] );
	}


/** Function ajax_disconnect() called by wp_ajax hooks: {'wp_mail_smtp_sendlayer_disconnect'} **/
/** No params detected :-/ **/


/** Function process_ajax() called by wp_ajax hooks: {'wp_mail_smtp_ajax'} **/
/** Parameters found in function process_ajax(): {"post": ["task"]} **/
function process_ajax() {

		$data = [];

		// Only admins can fire these ajax requests.
		if ( ! current_user_can( wp_mail_smtp()->get_capability_manage_options() ) ) {
			wp_send_json_error( $data );
		}

		// Verify nonce for all AJAX requests.
		check_ajax_referer( 'wp-mail-smtp-admin', 'nonce' );

		if ( empty( $_POST['task'] ) ) {
			wp_send_json_error( $data );
		}

		$task = sanitize_key( $_POST['task'] );

		switch ( $task ) {
			case 'pro_banner_dismiss':
				update_user_meta( get_current_user_id(), 'wp_mail_smtp_pro_banner_dismissed', true );
				$data['message'] = esc_html__( 'WP Mail SMTP Pro related message was successfully dismissed.', 'wp-mail-smtp' );
				break;

			case 'about_plugin_install':
				Pages\AboutTab::ajax_plugin_install();
				break;

			case 'about_plugin_activate':
				Pages\AboutTab::ajax_plugin_activate();
				break;

			case 'notice_dismiss':
				$dismissal_response = $this->dismiss_notice_via_ajax();

				if ( empty( $dismissal_response ) ) {
					break;
				}

				$data['message'] = $dismissal_response;
				break;

			case 'email_test_tab_removal_notice_dismiss':
				update_user_meta( get_current_user_id(), 'wp_mail_smtp_email_test_tab_removal_notice_dismissed', true );
				break;

			default:
				// Allow custom tasks data processing being added here.
				$data = apply_filters( 'wp_mail_smtp_admin_process_ajax_' . $task . '_data', $data );
		}

		// Final ability to rewrite all the data, just in case.
		$data = (array) apply_filters( 'wp_mail_smtp_admin_process_ajax_data', $data, $task );

		if ( empty( $data ) ) {
			wp_send_json_error( $data );
		}

		wp_send_json_success( $data );
	}


/** Function process_ajax_debug_event_preview() called by wp_ajax hooks: {'wp_mail_smtp_debug_event_preview'} **/
/** Parameters found in function process_ajax_debug_event_preview(): {"post": ["nonce", "id"]} **/
function process_ajax_debug_event_preview() {

		if (
			empty( $_POST['nonce'] ) ||
			! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'wp_mail_smtp_debug_events' )
		) {
			wp_send_json_error( esc_html__( 'Access rejected.', 'wp-mail-smtp' ) );
		}

		if ( ! current_user_can( wp_mail_smtp()->get_capability_manage_options() ) ) {
			wp_send_json_error( esc_html__( 'You don\'t have the capability to perform this action.', 'wp-mail-smtp' ) );
		}

		if ( ! self::is_valid_db() ) {
			wp_send_json_error( esc_html__( 'For some reason the database table was not installed correctly. Please contact plugin support team to diagnose and fix the issue.', 'wp-mail-smtp' ) );
		}

		$event_id = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : false;

		if ( empty( $event_id ) ) {
			wp_send_json_error( esc_html__( 'No Debug Event ID provided!', 'wp-mail-smtp' ) );
		}

		$event = new Event( $event_id );

		wp_send_json_success(
			[
				'title'   => $event->get_title(),
				'content' => $event->get_details_html(),
			]
		);
	}


/** Function check_mailer_configuration() called by wp_ajax hooks: {'wp_mail_smtp_vue_check_mailer_configuration'} **/
/** No params detected :-/ **/


/** Function init_migrations_ajax_handler() called by wp_ajax hooks: {'nopriv_wp_mail_smtp_init_migrations'} **/
/** Parameters found in function init_migrations_ajax_handler(): {"post": ["secret"]} **/
function init_migrations_ajax_handler() {

		// Verify the secret hash since this is a nopriv endpoint.
		$secret   = ! empty( $_POST['secret'] ) ? sanitize_text_field( wp_unslash( $_POST['secret'] ) ) : '';
		$expected = wp_hash( 'wp_mail_smtp_init_migrations' . wp_salt() );

		if ( ! hash_equals( $expected, $secret ) ) {
			wp_send_json_error();
		}

		$this->init_migrations();

		wp_send_json_success();
	}


/** Function get_settings() called by wp_ajax hooks: {'wp_mail_smtp_vue_get_settings'} **/
/** No params detected :-/ **/


/** Function subscribe_to_newsletter() called by wp_ajax hooks: {'wp_mail_smtp_vue_subscribe_to_newsletter'} **/
/** Parameters found in function subscribe_to_newsletter(): {"post": ["email"]} **/
function subscribe_to_newsletter() {

		check_ajax_referer( 'wpms-admin-nonce', 'nonce' );

		if ( ! current_user_can( wp_mail_smtp()->get_capability_manage_global_options() ) ) {
			wp_send_json_error( esc_html__( 'You don\'t have the permission to perform this action.', 'wp-mail-smtp' ) );
		}

		$email = ! empty( $_POST['email'] ) ? filter_var( wp_unslash( $_POST['email'] ), FILTER_VALIDATE_EMAIL ) : '';

		if ( empty( $email ) ) {
			wp_send_json_error();
		}

		$body = [
			'email' => base64_encode( $email ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
		];

		$wpforms_version_type = $this->get_wpforms_version_type();

		if ( ! empty( $wpforms_version_type ) ) {
			$body['wpforms_version_type'] = $wpforms_version_type;
		}

		wp_remote_post(
			'https://connect.wpmailsmtp.com/subscribe/drip/',
			[
				'user-agent' => Helpers::get_default_user_agent(),
				'body' => $body,
			]
		);

		wp_send_json_success();
	}


/** Function dismiss() called by wp_ajax hooks: {'wp_mail_smtp_notification_dismiss'} **/
/** Parameters found in function dismiss(): {"post": ["id"]} **/
function dismiss() {

		// Run a security check.
		check_ajax_referer( 'wp-mail-smtp-admin', 'nonce' );

		// Check for access and required param.
		if ( ! current_user_can( wp_mail_smtp()->get_capability_manage_options() ) || empty( $_POST['id'] ) ) {
			wp_send_json_error();
		}

		$id     = sanitize_text_field( wp_unslash( $_POST['id'] ) );
		$option = $this->get_option();
		$type   = is_numeric( $id ) ? 'feed' : 'events';

		$option['dismissed'][] = $id;
		$option['dismissed']   = array_unique( $option['dismissed'] );

		// Remove notification.
		if ( is_array( $option[ $type ] ) && ! empty( $option[ $type ] ) ) {
			foreach ( $option[ $type ] as $key => $notification ) {
				if ( $notification['id'] == $id ) { // phpcs:ignore WordPress.PHP.StrictComparisons
					unset( $option[ $type ][ $key ] );
					break;
				}
			}
		}

		update_option( self::OPTION_KEY, $option );

		wp_send_json_success();
	}


/** Function import_settings() called by wp_ajax hooks: {'wp_mail_smtp_vue_import_settings'} **/
/** Parameters found in function import_settings(): {"post": ["value"]} **/
function import_settings() {

		check_ajax_referer( 'wpms-admin-nonce', 'nonce' );

		if ( ! current_user_can( wp_mail_smtp()->get_capability_manage_global_options() ) ) {
			wp_send_json_error( esc_html__( 'You don\'t have permission to change options for this WP site!', 'wp-mail-smtp' ) );
		}

		$other_plugin = ! empty( $_POST['value'] ) ? sanitize_text_field( wp_unslash( $_POST['value'] ) ) : '';

		if ( empty( $other_plugin ) ) {
			wp_send_json_error();
		}

		$other_plugin_settings = ( new PluginImportDataRetriever( $other_plugin ) )->get();

		if ( empty( $other_plugin_settings ) ) {
			wp_send_json_error();
		}

		$options = Options::init();

		$options->set( $other_plugin_settings, false, false );

		wp_send_json_success();
	}


/** Function process() called by wp_ajax hooks: {'nopriv_wp_mail_smtp_connect_process'} **/
/** No params detected :-/ **/


/** Function send_feedback() called by wp_ajax hooks: {'wp_mail_smtp_vue_send_feedback'} **/
/** Parameters found in function send_feedback(): {"post": ["data"]} **/
function send_feedback() {

		check_ajax_referer( 'wpms-admin-nonce', 'nonce' );

		if ( ! current_user_can( wp_mail_smtp()->get_capability_manage_global_options() ) ) {
			wp_send_json_error();
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$data = ! empty( $_POST['data'] ) ? json_decode( wp_unslash( $_POST['data'] ), true ) : [];

		$feedback   = ! empty( $data['feedback'] ) ? sanitize_textarea_field( $data['feedback'] ) : '';
		$permission = ! empty( $data['permission'] );

		wp_remote_post(
			'https://wpmailsmtp.com/wizard-feedback/',
			[
				'user-agent' => Helpers::get_default_user_agent(),
				'body' => [
					'wpforms' => [
						'id'     => 87892,
						'fields' => [
							'1' => $feedback,
							'2' => $permission ? wp_get_current_user()->user_email : '',
							'3' => wp_mail_smtp()->get_license_type(),
							'4' => WPMS_PLUGIN_VER,
						],
					],
				],
			]
		);

		wp_send_json_success();
	}


/** Function ajax_check_plugin_status() called by wp_ajax hooks: {'wp_mail_smtp_page_check_{$plugin}_status'} **/
/** No params detected :-/ **/


/** Function get_connected_data() called by wp_ajax hooks: {'wp_mail_smtp_vue_get_connected_data'} **/
/** Parameters found in function get_connected_data(): {"post": ["mailer"]} **/
function get_connected_data() { // phpcs:ignore Generic.Metrics.NestingLevel.MaxExceeded

		check_ajax_referer( 'wpms-admin-nonce', 'nonce' );

		if ( ! current_user_can( wp_mail_smtp()->get_capability_manage_global_options() ) ) {
			wp_send_json_error();
		}

		$data   = [];
		$mailer = ! empty( $_POST['mailer'] ) ? sanitize_text_field( wp_unslash( $_POST['mailer'] ) ) : '';

		if ( empty( $mailer ) ) {
			wp_send_json_error();
		}

		switch ( $mailer ) {
			case 'gmail':
				$auth = wp_mail_smtp()->get_providers()->get_auth( 'gmail' );

				if ( $auth->is_clients_saved() && ! $auth->is_auth_required() ) {
					$user_info               = $auth->get_user_info();
					$data['connected_email'] = $user_info['email'];
				}
				break;
		}

		wp_send_json_success( array_merge( [ 'mailer' => $mailer ], $data ) );
	}


/** Function ajax_init_connect() called by wp_ajax hooks: {'wp_mail_smtp_sendlayer_connect'} **/
/** Parameters found in function ajax_init_connect(): {"post": ["return_url", "connection_id", "connect_args"]} **/
function ajax_init_connect() { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded

		// Verify nonce.
		check_ajax_referer( 'wp-mail-smtp-sendlayer-connect', 'nonce' );

		// Check for permissions.
		if ( ! current_user_can( wp_mail_smtp()->get_capability_manage_global_options() ) ) {
			wp_send_json_error(
				[
					'message'    => esc_html__( 'You do not have permission to perform this action.', 'wp-mail-smtp' ),
					'error_code' => 'plugin.init_connect.permission_denied',
				]
			);
		}

		// Accept the return URL from the initiator page (settings, wizard, additional connection).
		// This is where the user ends up after the flow completes.
		if ( empty( $_POST['return_url'] ) ) {
			wp_send_json_error(
				[
					'message'    => $this->get_generic_error_message(),
					'error_code' => 'plugin.init_connect.missing_return_url',
				]
			);
		}

		// Validate it's a local URL to prevent open redirect.
		$return_url = wp_validate_redirect(
			esc_url_raw( wp_unslash( $_POST['return_url'] ) ),
			false
		);

		if ( ! $return_url ) {
			wp_send_json_error(
				[
					'message'    => $this->get_generic_error_message(),
					'error_code' => 'plugin.init_connect.invalid_return_url',
				]
			);
		}

		$connection_id = ! empty( $_POST['connection_id'] ) ? sanitize_key( $_POST['connection_id'] ) : '';

		// Optional mode flag. Currently only `backup_mailer` is supported — the
		// return handler will create a new additional connection and assign it
		// as the backup connection in one OAuth round-trip.
		$mode          = ! empty( $_POST['connect_args']['mode'] ) ? sanitize_key( $_POST['connect_args']['mode'] ) : '';
		$allowed_modes = [ 'backup_mailer' ];
		$mode          = in_array( $mode, $allowed_modes, true ) ? $mode : '';

		// Build the redirect URL on the general settings page.
		// The auth handler always fires here; return_url is the final clean destination.
		$redirect_args = [
			'wp_mail_smtp_sendlayer_quick_connect_auth_complete' => 1,
			'nonce'      => wp_create_nonce( 'wp_mail_smtp_sendlayer_quick_connect' ),
			'return_url' => rawurlencode( $return_url ),
		];

		if ( ! empty( $connection_id ) ) {
			$redirect_args['connection_id'] = $connection_id;
		}

		if ( ! empty( $mode ) ) {
			$redirect_args['mode'] = $mode;
		}

		$redirect_url = add_query_arg( $redirect_args, wp_mail_smtp()->get_admin()->get_admin_page_url() );

		// Generate a challenge secret for HMAC-based ownership verification.
		$challenge_secret = bin2hex( random_bytes( 16 ) );

		set_transient( 'wp_mail_smtp_sendlayer_quick_connect_challenge_secret', $challenge_secret, 300 );

		// POST to the marketing site start-session endpoint.
		$response = wp_remote_post(
			$this->get_marketing_site_url() . '/wp-json/smtp-plugin-connect/v1/start-session',
			[
				'timeout' => 30,
				'body'    => [
					'site_url'         => $this->get_site_url(),
					'return_url'       => $redirect_url,
					'challenge_secret' => $challenge_secret,
					'source'           => 'wpms',
				],
			]
		);

		if ( is_wp_error( $response ) ) {
			$error_code = 'plugin.init_connect.start_session_request_to_site.connection_failed';

			DebugEvents::add(
				'SendLayer Quick Connect: ' . $error_code . ' — ' . $response->get_error_message()
			);

			wp_send_json_error(
				[
					'message'    => $this->get_generic_error_message(),
					'error_code' => $error_code,
				]
			);
		}

		$response_code = wp_remote_retrieve_response_code( $response );
		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

		// Non-200 response.
		if ( $response_code !== 200 ) {
			// Expected error — site returned a parseable error code.
			$error_code = ! empty( $response_body['code'] )
				? $this->sanitize_error_code( $response_body['code'] )
				: 'plugin.init_connect.start_session_request_to_site.unexpected_error_response';

			DebugEvents::add(
				'SendLayer Quick Connect: ' . $error_code . ' — HTTP ' . $response_code
			);

			wp_send_json_error(
				[
					'message'    => $this->get_generic_error_message(),
					'error_code' => $error_code,
				]
			);
		}

		// Unexpected success — 200 but missing expected data.
		if ( empty( $response_body['session_id'] ) ) {
			$error_code = 'plugin.init_connect.start_session_request_to_site.unexpected_success_response';

			DebugEvents::add(
				'SendLayer Quick Connect: ' . $error_code . ' — missing session_id'
			);

			wp_send_json_error(
				[
					'message'    => $this->get_generic_error_message(),
					'error_code' => $error_code,
				]
			);
		}

		$session_id = sanitize_text_field( $response_body['session_id'] );

		$redirect_url = add_query_arg( 'session', $session_id, $this->get_marketing_site_url() . '/smtp-plugin-connect' );

		// Add UTM parameters to the redirect URL for tracking which button initiated the flow.
		$utm_content = ! empty( $_POST['connect_args']['utm_content'] ) ? sanitize_text_field( wp_unslash( $_POST['connect_args']['utm_content'] ) ) : 'Quick Connect';

		// phpcs:ignore WordPress.Arrays.ArrayDeclarationSpacing.AssociativeArrayFound
		$redirect_url = wp_mail_smtp()->get_utm_url( $redirect_url, [ 'source' => 'wpmailsmtpplugin', 'medium' => 'WordPress', 'content' => $utm_content ] );

		wp_send_json_success(
			[
				'redirect_url' => $redirect_url,
			]
		);
	}


/** Function ajax_snippet_code() called by wp_ajax hooks: {'wp_mail_smtp_get_wpcode_snippet_code'} **/
/** Parameters found in function ajax_snippet_code(): {"post": ["library_id"]} **/
function ajax_snippet_code() {

		$this->verify_ajax();

		// Nonce verified in verify_ajax().
		$library_id = isset( $_POST['library_id'] ) ? absint( wp_unslash( $_POST['library_id'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing

		if ( empty( $library_id ) ) {
			wp_send_json_error();
		}

		$snippet = ( new SnippetsProvider( ( new RegisterLibrary() )->get_username() ) )->get_snippet( $library_id );

		if ( empty( $snippet ) || empty( $snippet['code'] ) ) {
			wp_send_json_error();
		}

		// The provider already normalized + sanitized these fields.
		wp_send_json_success(
			[
				'title'     => $snippet['title'],
				'note'      => $snippet['note'],
				'code'      => $snippet['code'],
				'code_type' => $snippet['code_type'],
			]
		);
	}


