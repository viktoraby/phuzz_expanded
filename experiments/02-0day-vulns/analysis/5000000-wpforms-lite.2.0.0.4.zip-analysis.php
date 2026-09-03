<?php
/***
*
*Found actions: 93
*Found functions:80
*Extracted functions:80
*Total parameter names extracted: 44
*Overview: {'get_embed_page_url_ajax': {'wpforms_admin_form_embed_wizard_embed_page_url'}, 'wpforms_ajax_search_pages_for_dropdown': {'wpforms_ajax_search_pages_for_dropdown'}, 'ajax_update_lite_connect_enabled_setting': {'wpforms_update_lite_connect_enabled_setting'}, 'install': {'wpforms_icon_choices_install'}, 'handle_update': {'wpforms_setup_wizard_screen_update'}, 'send_contact_form_ajax': {'wpforms_challenge_send_contact_form'}, 'get_chart_dataset_data': {'wpforms_payments_overview_refresh_chart_dataset_data'}, 'wpforms_new_form': {'wpforms_new_form'}, 'ajax_payment_refund': {'wpforms_paypal_commerce_payments_refund', 'wpforms_square_payments_refund'}, 'get_form': {'wpforms_get_ai_form'}, 'generate_url': {'wpforms_connect_url'}, 'dismiss_notice': {'wpforms_paypal_commerce_dismiss_domain_notice'}, 'get_search_result_pages_ajax': {'wpforms_admin_form_embed_wizard_search_pages_choicesjs'}, 'wpforms_verify_ssl': {'wpforms_verify_ssl'}, 'notification_from_email_validate': {'wpforms_builder_notification_from_email_validate'}, 'ajax_check_restricted_email': {'wpforms_restricted_email', 'nopriv_wpforms_restricted_email'}, 'use_form': {'wpforms_use_ai_form'}, 'ajax_sanitize_default_email': {'wpforms_sanitize_default_email'}, 'wpforms_deactivate_addon': {'wpforms_deactivate_addon'}, 'process_ajax': {'wpforms_provider_ajax_$this->slug', 'wpforms_builder_provider_ajax_{$this->core->slug}'}, 'get_record': {'wpforms_get_log_record'}, 'delete_tags': {'wpforms_admin_forms_overview_delete_tags'}, 'wpforms_builder_increase_next_field_id': {'wpforms_builder_increase_next_field_id'}, 'settings_cta_dismiss': {'wpforms_lite_settings_upgrade'}, 'wpforms_builder_dynamic_source': {'wpforms_builder_dynamic_source'}, 'save_order': {'wpforms_admin_forms_overview_save_columns_order'}, 'rate_response': {'wpforms_rate_ai_response'}, 'fetch_revisions_list': {'wpforms_get_form_revisions'}, 'integrations_tab_disconnect': {'wpforms_settings_provider_disconnect_$this->slug'}, 'ajax_toggle_write': {'wpforms_ai_mcp_toggle_write'}, 'wpforms_update_form_template': {'wpforms_update_form_template'}, 'integrations_tab_add': {'wpforms_settings_provider_add_$this->slug'}, 'ajax_check_plugin_status': {'wpforms_page_check_{$plugin}_status'}, 'ajax_disconnect': {'wpforms_settings_provider_disconnect_{$this->core->slug}'}, 'process': {'nopriv_wpforms_connect_process', 'wpforms_ai_form_editor_process'}, 'ajax_dismiss': {'wpforms_education_dismiss'}, 'ajax_remove_user_template': {'wpforms_user_template_remove'}, 'single_checkout_create_order_ajax': {'wpforms_paypal_commerce_create_order', 'nopriv_wpforms_paypal_commerce_create_order'}, 'captcha_field_callback': {'wpforms_update_field_captcha'}, 'preview': {'wpforms_divi_preview'}, 'ajax_get_form_selector_options': {'wpforms_admin_get_form_selector_options'}, 'dismiss': {'wpforms_dismiss_ai_form', 'wpforms_notification_dismiss', 'wpforms_woocommerce_dismiss'}, 'save_widget_meta_ajax': {'wpforms_{$widget_slug}_save_widget_meta'}, 'wpforms_activate_addon': {'wpforms_activate_addon'}, 'ajax_single_payment_cancel': {'wpforms_stripe_payments_cancel'}, 'wpforms_recreate_tables': {'wpforms_recreate_tables'}, 'save_chart_preference_settings': {'wpforms_payments_overview_save_chart_preference_settings'}, 'ajax_lite_connect_finalize': {'wpforms_lite_connect_finalize'}, 'mark_panel_viewed': {'wpforms_mark_panel_viewed'}, 'wpforms_save_form': {'wpforms_save_form'}, 'dismiss_ajax': {'wpforms_notice_dismiss'}, 'ajax_sanitize_restricted_rules': {'wpforms_sanitize_restricted_rules'}, 'wpforms_builder_dynamic_choices': {'wpforms_builder_dynamic_choices'}, 'dismiss_callback': {'wpforms_education_pointers_dismiss'}, 'engagement_callback': {'wpforms_education_pointers_engagement'}, 'ajax_submit': {'wpforms_submit', 'nopriv_wpforms_submit'}, 'field_new': {'wpforms_new_field_{$this->type}'}, 'ajax_save_favorites': {'wpforms_templates_favorite'}, 'connect': {'wpforms_square_create_webhook'}, 'save_internal_information_checkbox': {'wpforms_builder_save_internal_information_checkbox'}, 'ajax_handle_auth': {'wpforms_constant_contact_popup_auth'}, 'save_tags': {'wpforms_admin_forms_overview_save_tags'}, 'handle_snapshot_ajax': {'wpforms_analytics_snapshot', 'nopriv_wpforms_analytics_snapshot'}, 'import_form': {'wpforms_import_form_{$this->slug}'}, 'get_choices': {'wpforms_get_ai_choices'}, 'maybe_watch_install': {'wpforms_install_addon'}, 'ajax_payments_cancel': {'wpforms_paypal_commerce_payments_cancel', 'wpforms_square_payments_cancel'}, 'create_subscription_order_ajax': {'nopriv_wpforms_paypal_commerce_create_subscription', 'wpforms_paypal_commerce_create_subscription'}, 'refresh_connection_manual': {'wpforms_square_refresh_connection'}, 'save_challenge_option_ajax': {'wpforms_challenge_save_option'}, 'ajax_get_templates_batch': {'wpforms_get_templates_batch'}, 'wpforms_install_addon': {'wpforms_install_addon'}, 'ajax_start_migration': {'wpforms_constant_contact_migration_prompt'}, 'get_field_preview': {'wpforms_get_ai_form_field_preview'}, 'load_panel_content': {'wpforms_builder_load_panel'}, 'ajax_connect': {'wpforms_settings_provider_add_{$this->core->slug}'}, 'subscription_processor_create_ajax': {'nopriv_wpforms_paypal_commerce_subscription_processor_create', 'wpforms_paypal_commerce_subscription_processor_create'}, 'reset': {'wpforms_ai_form_editor_reset'}, 'ajax_get_token': {'wpforms_get_token', 'nopriv_wpforms_get_token'}, 'ajax_single_payment_refund': {'wpforms_stripe_payments_refund'}}
*
***/

/** Function get_embed_page_url_ajax() called by wp_ajax hooks: {'wpforms_admin_form_embed_wizard_embed_page_url'} **/
/** Parameters found in function get_embed_page_url_ajax(): {"post": ["pageId"]} **/
function get_embed_page_url_ajax() {

		// Run a security check.
		check_admin_referer( 'wpforms_admin_form_embed_wizard_nonce' );

		// Check for permissions.
		if ( ! wpforms_current_user_can( 'edit_forms' ) ) {
			wp_send_json_error( esc_html__( 'You are not allowed to perform this action.', 'wpforms-lite' ) );
		}

		$page_id = ! empty( $_POST['pageId'] ) ? absint( $_POST['pageId'] ) : 0;
		$meta    = $this->prepare_meta_data( $page_id );

		$this->set_meta( $meta );

		// Update challenge option to properly continue challenge on the embed page.
		$this->update_challenge_option( $meta );

		wp_send_json_success( $meta['url'] );
	}


/** Function wpforms_ajax_search_pages_for_dropdown() called by wp_ajax hooks: {'wpforms_ajax_search_pages_for_dropdown'} **/
/** Parameters found in function wpforms_ajax_search_pages_for_dropdown(): {"get": ["search"]} **/
function wpforms_ajax_search_pages_for_dropdown() {

	// Run a security check.
	if ( ! check_ajax_referer( 'wpforms-builder', 'nonce', false ) ) {
		wp_send_json_error( esc_html__( 'Your session expired. Please reload the builder.', 'wpforms-lite' ) );
	}

	if ( ! array_key_exists( 'search', $_GET ) ) {
		wp_send_json_error( esc_html__( 'Incorrect usage of this operation.', 'wpforms-lite' ) );
	}

	$search              = sanitize_text_field( wp_unslash( $_GET['search'] ) );
	$result_pages        = [];
	$previous_page_label = esc_html__( 'Back to Previous Page (Referrer) ', 'wpforms-lite' );

	if ( stripos( strtolower( $previous_page_label ), $search ) !== false ) {
		$result_pages[] = [
			'value' => 'previous_page',
			'label' => $previous_page_label,
		];
	}

	$result_pages += wpforms_search_pages_for_dropdown( $search );

	if ( empty( $result_pages ) ) {
		wp_send_json_success( [] );
	}

	wp_send_json_success( $result_pages );
}


/** Function ajax_update_lite_connect_enabled_setting() called by wp_ajax hooks: {'wpforms_update_lite_connect_enabled_setting'} **/
/** Parameters found in function ajax_update_lite_connect_enabled_setting(): {"post": ["value"]} **/
function ajax_update_lite_connect_enabled_setting() {

		$this->ajax_checks();

		$slug = LiteConnectClass::SETTINGS_SLUG;

		$settings          = get_option( 'wpforms_settings', [] );
		$settings[ $slug ] = ! empty( $_POST['value'] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

		wpforms_update_settings( $settings );

		if ( ! $settings[ $slug ] ) {
			wp_send_json_success( '' );
		}

		// Reset generate key attempts counter.
		update_option( API::GENERATE_KEY_ATTEMPT_COUNTER_OPTION, 0 );

		// We have to start requesting site keys in ajax, turning on the LC functionality.
		// First, the request to the API server will be sent.
		// Second, the server will respond to our callback URL /wpforms/auth/key/nonce, and the site key will be stored in the DB.
		// Third, we have to get access via a separate HTTP request.
		( new LiteConnectIntegration() )->update_keys(); // First request here.

		wp_send_json_success( $this->get_lite_connect_entries_since_info() );
	}


/** Function install() called by wp_ajax hooks: {'wpforms_icon_choices_install'} **/
/** No params detected :-/ **/


/** Function handle_update() called by wp_ajax hooks: {'wpforms_setup_wizard_screen_update'} **/
/** Parameters found in function handle_update(): {"post": ["consent"]} **/
function handle_update(): void {

		if ( check_ajax_referer( self::NONCE_ACTION, 'nonce', false ) === false ) {
			wp_send_json_error(
				[ 'message' => __( 'Your session has expired. Please reload the page and try again.', 'wpforms-lite' ) ],
				403
			);
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => __( 'You are not allowed to run the Setup Wizard.', 'wpforms-lite' ) ], 403 );
		}

		// Every launch path honors the kill switch; the AJAX twin must too, or a site
		// with the wizard switched off still writes settings, mints a wizard token and
		// hands the browser off to the SPA.
		if ( SetupWizard::is_disabled() ) {
			wp_send_json_error( [ 'message' => __( 'The Setup Wizard is disabled on this site.', 'wpforms-lite' ) ], 403 );
		}

		if ( ! $this->bridge->is_spa_reachable() ) {
			wp_send_json_error( [ 'message' => __( 'The Setup Wizard is temporarily unavailable. Please try again in a few minutes.', 'wpforms-lite' ) ], 503 );
		}

		$is_consent_given = (bool) absint( $_POST['consent'] ?? 0 );

		$state           = $this->state_manager->get_state();
		$wizard_settings = (array) ( $state['wizard_settings'] ?? [] );

		// The marker records that this run's first screen was rendered locally and
		// acted on. It travels to the SPA inside wizard_settings on every hydrate,
		// so it survives refreshes and resume handshakes without a session.
		$this->state_manager->save_wizard_settings(
			array_merge(
				$wizard_settings,
				[
					'lite-connect-enabled'   => $is_consent_given,
					'first_screen_completed' => true,
				]
			)
		);

		wp_send_json_success(
			[
				'handoff' => $this->bridge->get_handoff_data( $this->get_exit_url(), $this->get_restart_url() ),
			]
		);
	}


/** Function send_contact_form_ajax() called by wp_ajax hooks: {'wpforms_challenge_send_contact_form'} **/
/** Parameters found in function send_contact_form_ajax(): {"post": ["contact_data"]} **/
function send_contact_form_ajax() {

		check_admin_referer( 'wpforms_challenge_ajax_nonce' );

		$url     = 'https://wpforms.com/wpforms-challenge-feedback/';
		$message = ! empty( $_POST['contact_data']['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['contact_data']['message'] ) ) : '';
		$email   = '';

		if (
			( ! empty( $_POST['contact_data']['contact_me'] ) && $_POST['contact_data']['contact_me'] === 'true' )
			|| wpforms()->is_pro()
		) {
			$current_user = wp_get_current_user();
			$email        = $current_user->user_email;
			$this->set_challenge_option( [ 'feedback_contact_me' => true ] );
		}

		if ( empty( $message ) && empty( $email ) ) {
			wp_send_json_error();
		}

		$data = [
			'body' => [
				'wpforms' => [
					'id'     => 296355,
					'submit' => 'wpforms-submit',
					'fields' => [
						2 => $message,
						3 => $email,
						4 => $this->get_challenge_license_type(),
						5 => wpforms()->version,
						6 => wpforms_get_license_key(),
					],
				],
			],
		];

		$response = wp_remote_post( $url, $data );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error();
		}

		$this->set_challenge_option( [ 'feedback_sent' => true ] );
		wp_send_json_success();
	}


/** Function get_chart_dataset_data() called by wp_ajax hooks: {'wpforms_payments_overview_refresh_chart_dataset_data'} **/
/** Parameters found in function get_chart_dataset_data(): {"post": ["report", "dates"]} **/
function get_chart_dataset_data() {

		// Run a security check.
		check_ajax_referer( 'wpforms_payments_overview_nonce' );

		// Check for permissions.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( 'You are not allowed to perform this action.', 'wpforms-lite' ) );
		}

		$report   = ! empty( $_POST['report'] ) ? sanitize_text_field( wp_unslash( $_POST['report'] ) ) : null;
		$dates    = ! empty( $_POST['dates'] ) ? sanitize_text_field( wp_unslash( $_POST['dates'] ) ) : null;
		$fallback = [
			'data'    => [],
			'reports' => [],
		];

		// If the report type or dates for the timespan are missing, leave early.
		if ( ! $report || ! $dates ) {
			wp_send_json_error( $fallback );
		}

		// Validates and creates date objects of given timespan string.
		$timespans = Datepicker::process_string_timespan( $dates );

		// If the timespan is not validated, leave early.
		if ( ! $timespans ) {
			wp_send_json_error( $fallback );
		}

		// Extract start and end timespans in local (site) and UTC timezones.
		list( $start_date, $end_date, $utc_start_date, $utc_end_date ) = $timespans;

		// Payment table name.
		$this->table_name = wpforms()->obj( 'payment' )->table_name;

		// Get the stat cards.
		$this->stat_cards = Chart::stat_cards();

		// Get the payments in the given timespan.
		$results = $this->get_payments_in_timespan( $utc_start_date, $utc_end_date, $report );

		// In case the database's results were empty, leave early.
		if ( $report === Chart::ACTIVE_REPORT && empty( $results ) ) {
			wp_send_json_error( $fallback );
		}

		// Process the results and return the data.
		// The first element of the array is the total number of entries, the second is the data.
		list( , $data ) = ChartHelper::process_chart_dataset_data( $results, $start_date, $end_date );

		// Sends the JSON response back to the Ajax request, indicating success.
		wp_send_json_success(
			[
				'data'    => $data,
				'reports' => $this->get_payments_summary_in_timespan( $start_date, $end_date ),
			]
		);
	}


/** Function wpforms_new_form() called by wp_ajax hooks: {'wpforms_new_form'} **/
/** Parameters found in function wpforms_new_form(): {"post": ["title", "template", "category", "subcategory"]} **/
function wpforms_new_form() { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

	check_ajax_referer( 'wpforms-builder', 'nonce' );

	// An unlicensed Pro install is limited to a single form. The old redirect target (the builder
	// license overlay) is deprecated, so the limit is reported as a regular error message instead.
	if ( wpforms()->is_pro() && empty( wpforms_get_license_type() ) && wp_count_posts( 'wpforms' )->publish >= 1 ) {
		wp_send_json_error(
			[
				'error_type' => 'license_required',
				'message'    => esc_html__( 'A WPForms license key is required. To create more forms, please verify your WPForms license.', 'wpforms-lite' ),
			]
		);
	}

	if ( empty( $_POST['title'] ) ) {
		wp_send_json_error(
			[
				'error_type' => 'missing_form_title',
				'message'    => esc_html__( 'No Form Name Provided', 'wpforms-lite' ),
			]
		);
	}

	$form_title    = sanitize_text_field( wp_unslash( $_POST['title'] ) );
	$form_template = empty( $_POST['template'] ) ? 'blank' : sanitize_text_field( wp_unslash( $_POST['template'] ) );
	$category      = empty( $_POST['category'] ) ? 'all' : sanitize_text_field( wp_unslash( $_POST['category'] ) );
	$subcategory   = empty( $_POST['subcategory'] ) ? 'all' : sanitize_text_field( wp_unslash( $_POST['subcategory'] ) );

	if ( ! wpforms()->obj( 'builder_templates' )->is_valid_template( $form_template ) ) {
		wp_send_json_error(
			[
				'error_type' => 'invalid_template',
				'message'    => esc_html__( 'The template you selected is currently not available, but you can try again later. If you continue to have trouble, please reach out to support.', 'wpforms-lite' ),
			]
		);
	}

	$title_query  = new WP_Query(
		[
			'post_type'              => 'wpforms',
			'title'                  => $form_title,
			'posts_per_page'         => 1,
			'fields'                 => 'ids',
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'no_found_rows'          => true,
		]
	);
	$title_exists = $title_query->post_count > 0;
	$form_id      = wpforms()->obj( 'form' )->add(
		$form_title,
		[],
		[
			'template'    => $form_template,
			'category'    => $category,
			'subcategory' => $subcategory,
		]
	);

	if ( $title_exists ) {

		// Skip creating a revision for this action.
		remove_action( 'post_updated', 'wp_save_post_revision' );

		wp_update_post(
			[
				'ID'         => $form_id,
				'post_title' => $form_title . ' (ID #' . $form_id . ')',
			]
		);

		// Restore the initial revisions state.
		add_action( 'post_updated', 'wp_save_post_revision' );
	}

	if ( ! $form_id ) {
		wp_send_json_error(
			[
				'error_type' => 'cant_create_form',
				'message'    => esc_html__( 'Error Creating Form', 'wpforms-lite' ),
			]
		);
	}

	if ( wpforms_current_user_can( 'edit_form_single', $form_id ) ) {
		wp_send_json_success(
			[
				'id'       => $form_id,
				'redirect' => add_query_arg(
					[
						'view'    => 'fields',
						'form_id' => $form_id,
						'newform' => '1',
					],
					admin_url( 'admin.php?page=wpforms-builder' )
				),
			]
		);
	}

	if ( wpforms_current_user_can( 'view_forms' ) ) {
		wp_send_json_success( [ 'redirect' => admin_url( 'admin.php?page=wpforms-overview' ) ] );
	}

	wp_send_json_success( [ 'redirect' => admin_url() ] );
}


/** Function ajax_payment_refund() called by wp_ajax hooks: {'wpforms_paypal_commerce_payments_refund', 'wpforms_square_payments_refund'} **/
/** No params detected :-/ **/


/** Function get_form() called by wp_ajax hooks: {'wpforms_get_ai_form'} **/
/** No params detected :-/ **/


/** Function generate_url() called by wp_ajax hooks: {'wpforms_connect_url'} **/
/** Parameters found in function generate_url(): {"post": ["key"]} **/
function generate_url() {

		// Run a security check.
	 	check_ajax_referer( 'wpforms-admin', 'nonce' );

		// Check for permissions.
		if ( ! current_user_can( 'install_plugins' ) ) {
		 	wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to install plugins.', 'wpforms-lite' ) ] );
		}

		$current_plugin = plugin_basename( WPFORMS_PLUGIN_FILE );
		$is_pro         = wpforms()->is_pro();

		// Local development environment.
		if ( $current_plugin === self::PRO_PLUGIN && ! $is_pro ) {
			wp_send_json_error( [ 'message' => esc_html__( 'There must be a non-developer Lite version installed to upgrade.', 'wpforms-lite' ) ] );
		}

		$key = ! empty( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification

		// Empty license key.
		if ( empty( $key ) ) {
		 	wp_send_json_error( [ 'message' => esc_html__( 'Please enter your license key to connect.', 'wpforms-lite' ) ] );
		}

		// Whether it is the pro version.
		if ( $is_pro ) {
		 	wp_send_json_error( [ 'message' => esc_html__( 'Only the Lite version can be upgraded.', 'wpforms-lite' ) ] );
		}

		// Verify pro version is not installed.
		$active = activate_plugin( self::PRO_PLUGIN, false, false, true );

		if ( ! is_wp_error( $active ) ) {

			// Deactivate Lite.
		 	deactivate_plugins( $current_plugin );

			 // phpcs:ignore WPForms.Comments.PHPDocHooks.RequiredHookDocumentation, WPForms.PHP.ValidateHooks.InvalidHookName
			do_action( 'wpforms_plugin_deactivated', $current_plugin );

		 	wp_send_json_success(
				[
					'message' => esc_html__( 'WPForms Pro is installed but not activated.', 'wpforms-lite' ),
					'reload'  => true,
				]
			);
		}

		// Generate URL.
		$oth        = hash( 'sha512', wp_rand() );
		$hashed_oth = hash_hmac( 'sha512', $oth, wp_salt() );

	 	update_option( 'wpforms_connect_token', $oth );
	 	update_option( 'wpforms_connect', $key );

		$version  = WPFORMS_VERSION;
		$endpoint = admin_url( 'admin-ajax.php' );
		$redirect = admin_url( 'admin.php?page=wpforms-settings' );
		$url      = add_query_arg(
			[
				'key'      => $key,
				'oth'      => $hashed_oth,
				'endpoint' => $endpoint,
				'version'  => $version,
				'siteurl'  => admin_url(),
				'homeurl'  => site_url(),
				'redirect' => rawurldecode( base64_encode( $redirect ) ), // phpcs:ignore
				'v'        => 2,
			],
			'https://upgrade.wpforms.com'
		);

	 	wp_send_json_success(
			[
				'url'      => $url,
				'back_url' => add_query_arg(
					[
						'action' => 'wpforms_connect',
						'oth'    => $hashed_oth,
					],
					$endpoint
				),
			]
		);
	}


/** Function dismiss_notice() called by wp_ajax hooks: {'wpforms_paypal_commerce_dismiss_domain_notice'} **/
/** No params detected :-/ **/


/** Function get_search_result_pages_ajax() called by wp_ajax hooks: {'wpforms_admin_form_embed_wizard_search_pages_choicesjs'} **/
/** Parameters found in function get_search_result_pages_ajax(): {"get": ["search"]} **/
function get_search_result_pages_ajax() {

		// Run a security check.
		if ( ! check_ajax_referer( 'wpforms_admin_form_embed_wizard_nonce', false, false ) ) {
			wp_send_json_error(
				[
					'msg' => esc_html__( 'Your session expired. Please reload the builder.', 'wpforms-lite' ),
				]
			);
		}

		// Check for permissions.
		if ( ! wpforms_current_user_can( 'edit_forms' ) ) {
			wp_send_json_error( esc_html__( 'You are not allowed to perform this action.', 'wpforms-lite' ) );
		}

		if ( ! array_key_exists( 'search', $_GET ) ) {
			wp_send_json_error(
				[
					'msg' => esc_html__( 'Incorrect usage of this operation.', 'wpforms-lite' ),
				]
			);
		}

		$result_pages = wpforms_search_pages_for_dropdown(
			sanitize_text_field( wp_unslash( $_GET['search'] ) ),
			[
				'count'       => self::MAX_SEARCH_RESULTS_DROPDOWN_PAGES_COUNT,
				'post_status' => self::POST_STATUSES_OF_DROPDOWN_PAGES,
			]
		);

		if ( empty( $result_pages ) ) {
			wp_send_json_error( [] );
		}

		wp_send_json_success( $result_pages );
	}


/** Function wpforms_verify_ssl() called by wp_ajax hooks: {'wpforms_verify_ssl'} **/
/** No params detected :-/ **/


/** Function notification_from_email_validate() called by wp_ajax hooks: {'wpforms_builder_notification_from_email_validate'} **/
/** Parameters found in function notification_from_email_validate(): {"post": ["email"]} **/
function notification_from_email_validate() {

		check_ajax_referer( 'wpforms-builder', 'nonce' );

		// Before checking if $_POST['email'] is valid email, we need to check if smart tag is used and return its value.
		$email = ! empty( $_POST['email'] ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';
		$email = $email ? sanitize_email( wpforms_process_smart_tags( $email, [], [], '', 'smtp-notification-validation' ) ) : '';

		if ( ! is_email( $email ) ) {
			wp_send_json_error(
				sprintf(
					'<div class="wpforms-alert wpforms-alert-warning wpforms-alert-warning-wide">%s</div>',
					__( 'Please enter a valid email address. Your notifications won\'t be sent if the field is not filled in correctly.', 'wpforms-lite' )
				)
			);
		}

		if ( ! $this->email_domain_matches_site_domain( $email ) ) {
			wp_send_json_error( $this->get_warning_message() );
		}

		wp_send_json_success();
	}


/** Function ajax_check_restricted_email() called by wp_ajax hooks: {'wpforms_restricted_email', 'nopriv_wpforms_restricted_email'} **/
/** No params detected :-/ **/


/** Function use_form() called by wp_ajax hooks: {'wpforms_use_ai_form'} **/
/** No params detected :-/ **/


/** Function ajax_sanitize_default_email() called by wp_ajax hooks: {'wpforms_sanitize_default_email'} **/
/** No params detected :-/ **/


/** Function wpforms_deactivate_addon() called by wp_ajax hooks: {'wpforms_deactivate_addon'} **/
/** Parameters found in function wpforms_deactivate_addon(): {"post": ["type", "plugin"]} **/
function wpforms_deactivate_addon() {

	// Run a security check.
	check_ajax_referer( 'wpforms-admin', 'nonce' );

	// Check for permissions.
	if ( ! current_user_can( 'deactivate_plugins' ) ) {
		wp_send_json_error( esc_html__( 'Plugin deactivation is disabled for you on this site.', 'wpforms-lite' ) );
	}

	$type = empty( $_POST['type'] ) ? 'addon' : sanitize_key( $_POST['type'] );

	if ( isset( $_POST['plugin'] ) ) {
		$plugin = sanitize_text_field( wp_unslash( $_POST['plugin'] ) );

		deactivate_plugins( $plugin );

		/**
		 * Fire after the plugin deactivating via the WPForms installer.
		 *
		 * @since 1.6.3
		 *
		 * @param string $plugin Plugin deactivated.
		 */
		do_action( 'wpforms_plugin_deactivated', $plugin );

		if ( $type === 'plugin' ) {
			wp_send_json_success( esc_html__( 'Plugin deactivated.', 'wpforms-lite' ) );
		} else {
			wp_send_json_success( esc_html__( 'Addon deactivated.', 'wpforms-lite' ) );
		}
	}

	wp_send_json_error( esc_html__( 'Could not deactivate the addon. Please deactivate from the Plugins page.', 'wpforms-lite' ) );
}


/** Function process_ajax() called by wp_ajax hooks: {'wpforms_provider_ajax_$this->slug', 'wpforms_builder_provider_ajax_{$this->core->slug}'} **/
/** Parameters found in function process_ajax(): {"post": ["name", "task", "id", "connection_id", "account_id", "list_id", "data", "form_id"]} **/
function process_ajax() { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		// Run a security check.
		check_ajax_referer( 'wpforms-builder', 'nonce' );

		// Check for permissions.
		if ( ! wpforms_current_user_can( 'edit_forms' ) ) {
			wp_send_json_error(
				[
					'error' => esc_html__( 'You do not have permission', 'wpforms-lite' ),
				]
			);
		}

		$name          = ! empty( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
		$task          = ! empty( $_POST['task'] ) ? sanitize_text_field( wp_unslash( $_POST['task'] ) ) : '';
		$id            = ! empty( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;
		$connection_id = ! empty( $_POST['connection_id'] ) ? sanitize_text_field( wp_unslash( $_POST['connection_id'] ) ) : '';
		$account_id    = ! empty( $_POST['account_id'] ) ? sanitize_text_field( wp_unslash( $_POST['account_id'] ) ) : '';
		$list_id       = ! empty( $_POST['list_id'] ) ? sanitize_text_field( wp_unslash( $_POST['list_id'] ) ) : '';
		$data          = ! empty( $_POST['data'] ) ? array_map( 'sanitize_text_field', wp_parse_args( wp_unslash( $_POST['data'] ) ) ) : []; //phpcs:ignore

		// The `edit_forms` check above is form-agnostic - re-check ownership of this specific form. See #18350.
		if ( ! $id || ! wpforms_current_user_can( 'edit_form_single', $id ) ) {
			wp_send_json_error(
				[
					'error' => esc_html__( 'You do not have permission', 'wpforms-lite' ),
				]
			);
		}

		/*
		 * Create a new connection.
		 */
		if ( $task === 'new_connection' ) {

			$connection = $this->output_connection(
				'',
				[
					'connection_name' => $name,
				],
				$id
			);

			wp_send_json_success(
				[
					'html' => $connection,
				]
			);
		}

		/*
		 * Create a new Provider account.
		 */
		if ( $task === 'new_account' ) {

			$auth = $this->api_auth( $data, $id );

			if ( is_wp_error( $auth ) ) {

				wp_send_json_error(
					[
						'error' => $auth->get_error_message(),
					]
				);

			} else {

				$accounts = $this->output_accounts(
					$connection_id,
					[
						'account_id' => $auth,
					]
				);

				wp_send_json_success(
					[
						'html' => $accounts,
					]
				);
			}
		}

		/*
		 * Select/Toggle Provider accounts.
		 */
		if ( $task === 'select_account' ) {

			$lists = $this->output_lists(
				$connection_id,
				[
					'account_id' => $account_id,
				]
			);

			if ( is_wp_error( $lists ) ) {

				wp_send_json_error(
					[
						'error' => $lists->get_error_message(),
					]
				);

			} else {

				wp_send_json_success(
					[
						'html' => $lists,
					]
				);
			}
		}

		/*
		 * Select/Toggle Provider account lists.
		 */
		if ( $task === 'select_list' ) {

			$fields = $this->output_fields(
				$connection_id,
				[
					'account_id' => $account_id,
					'list_id'    => $list_id,
				],
				$id
			);

			if ( is_wp_error( $fields ) ) {

				wp_send_json_error(
					[
						'error' => $fields->get_error_message(),
					]
				);

			} else {

				$groups = $this->output_groups(
					$connection_id,
					[
						'account_id' => $account_id,
						'list_id'    => $list_id,
					]
				);

				$conditionals = $this->output_conditionals(
					$connection_id,
					[
						'account_id' => $account_id,
						'list_id'    => $list_id,
					],
					[
						'id' => absint( $_POST['form_id'] ), //phpcs:ignore
					]
				);

				$options = $this->output_options(
					$connection_id,
					[
						'account_id' => $account_id,
						'list_id'    => $list_id,
					]
				);

				wp_send_json_success(
					[
						'html' => $groups . $fields . $conditionals . $options,
					]
				);
			}
		}

		die();
	}


/** Function get_record() called by wp_ajax hooks: {'wpforms_get_log_record'} **/
/** No params detected :-/ **/


/** Function delete_tags() called by wp_ajax hooks: {'wpforms_admin_forms_overview_delete_tags'} **/
/** No params detected :-/ **/


/** Function wpforms_builder_increase_next_field_id() called by wp_ajax hooks: {'wpforms_builder_increase_next_field_id'} **/
/** Parameters found in function wpforms_builder_increase_next_field_id(): {"post": ["form_id", "field_id"]} **/
function wpforms_builder_increase_next_field_id() {

	// Run a security check.
	check_ajax_referer( 'wpforms-builder', 'nonce' );

	// Check for permissions.
	if ( ! wpforms_current_user_can( 'edit_forms' ) ) {
		wp_send_json_error();
	}

	// Check for required items.
	if ( empty( $_POST['form_id'] ) ) {
		wp_send_json_error();
	}

	$args = [];

	// In the case of duplicating the Layout field that contains a bunch of fields,
	// we need to set the next `field_id` to the desired value which is passed via POST argument.
	if ( ! empty( $_POST['field_id'] ) ) {
		$args['field_id'] = sanitize_text_field( wp_unslash( $_POST['field_id'] ) );
	}

	wpforms()->obj( 'form' )->next_field_id( absint( $_POST['form_id'] ), $args );

	wp_send_json_success();
}


/** Function settings_cta_dismiss() called by wp_ajax hooks: {'wpforms_lite_settings_upgrade'} **/
/** Parameters found in function settings_cta_dismiss(): {"post": ["nonce"]} **/
function settings_cta_dismiss() {

		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'wpforms_settings_cta_dismiss' ) ) {
			wp_send_json_error( esc_html__( 'Security check failed. Please try again.', 'wpforms-lite' ) );
		}

		if ( ! wpforms_current_user_can() ) {
			wp_send_json_error();
		}

		update_option( 'wpforms_lite_settings_upgrade', time() );

		wp_send_json_success();
	}


/** Function wpforms_builder_dynamic_source() called by wp_ajax hooks: {'wpforms_builder_dynamic_source'} **/
/** Parameters found in function wpforms_builder_dynamic_source(): {"post": ["field_id", "form_id", "type", "source"]} **/
function wpforms_builder_dynamic_source() {

	// Run a security check.
	check_ajax_referer( 'wpforms-builder', 'nonce' );

	// Check for permissions.
	if ( ! wpforms_current_user_can( 'edit_forms' ) ) {
		wp_send_json_error();
	}

	// Check for required items.
	if ( ! isset( $_POST['field_id'] ) || empty( $_POST['form_id'] ) || empty( $_POST['type'] ) || empty( $_POST['source'] ) ) {
		wp_send_json_error();
	}

	$type        = sanitize_key( $_POST['type'] );
	$source      = sanitize_key( $_POST['source'] );
	$id          = sanitize_text_field( wp_unslash( $_POST['field_id'] ) );
	$form_id     = absint( $_POST['form_id'] );
	$items       = [];
	$total       = 0;
	$source_name = '';
	$type_name   = '';

	if ( $type === 'post_type' ) {
		$type_name = esc_html__( 'post type', 'wpforms-lite' );
		$args      = [
			'post_type'      => $source,
			'posts_per_page' => 20,
			'orderby'        => 'title',
			'order'          => 'ASC',
		];

		/**
		 * Filters the arguments used to query the post type for dynamic choices.
		 *
		 * @since 1.4.1
		 *
		 * @param array $args    Arguments used to query the post's type for dynamic choices.
		 * @param array $field   Field.
		 * @param int   $form_id Form ID.
		 */
		$args  = (array) apply_filters( 'wpforms_dynamic_choice_post_type_args', $args, [ 'id' => $id ], $form_id );
		$posts = wpforms_get_hierarchical_object( $args, true );
		$total = wp_count_posts( $source );
		$total = $total->publish;
		$pt    = get_post_type_object( $source );

		if ( $pt !== null ) {
			$source_name = $pt->labels->name;
		}

		foreach ( $posts as $post ) {
			$items[] = esc_html( wpforms_get_post_title( $post ) );
		}
	} elseif ( $type === 'taxonomy' ) {
		$type_name = esc_html__( 'taxonomy', 'wpforms-lite' );
		$args      = [
			'taxonomy'   => $source,
			'hide_empty' => false,
			'number'     => 20,
		];

		/**
		 * Filters the arguments used to query the taxonomy for dynamic choices.
		 *
		 * @since 1.4.1
		 *
		 * @param array $args    Arguments used to query the post's type for dynamic choices.
		 * @param array $field   Field.
		 * @param int   $form_id Form ID.
		 */
		$args        = apply_filters( 'wpforms_dynamic_choice_taxonomy_args', $args, [ 'id' => $id ], $form_id );
		$terms       = wpforms_get_hierarchical_object( $args, true );
		$total       = wp_count_terms( $source );
		$tax         = get_taxonomy( $source );
		$source_name = $tax->labels->name;

		foreach ( $terms as $term ) {
			$items[] = esc_html( wpforms_get_term_name( $term ) );
		}
	}

	if ( empty( $items ) ) {
		$items = [];
	}

	wp_send_json_success(
		[
			'items'       => $items,
			'source'      => $source,
			'source_name' => $source_name,
			'total'       => $total,
			'type'        => $type,
			'type_name'   => $type_name,
		]
	);
}


/** Function save_order() called by wp_ajax hooks: {'wpforms_admin_forms_overview_save_columns_order'} **/
/** No params detected :-/ **/


/** Function rate_response() called by wp_ajax hooks: {'wpforms_rate_ai_response'} **/
/** No params detected :-/ **/


/** Function fetch_revisions_list() called by wp_ajax hooks: {'wpforms_get_form_revisions'} **/
/** No params detected :-/ **/


/** Function integrations_tab_disconnect() called by wp_ajax hooks: {'wpforms_settings_provider_disconnect_$this->slug'} **/
/** Parameters found in function integrations_tab_disconnect(): {"post": ["provider", "key"]} **/
function integrations_tab_disconnect() {

		// Run a security check.
		check_ajax_referer( 'wpforms-admin', 'nonce' );

		// Check for permissions.
		if ( ! wpforms_current_user_can() ) {
			wp_send_json_error(
				[
					'error' => esc_html__( 'You do not have permission', 'wpforms-lite' ),
				]
			);
		}

		if ( empty( $_POST['provider'] ) || empty( $_POST['key'] ) ) {
			wp_send_json_error(
				[
					'error' => esc_html__( 'Missing data', 'wpforms-lite' ),
				]
			);
		}

		$providers = wpforms_get_providers_options();

		if ( ! empty( $providers[ $_POST['provider'] ][ $_POST['key'] ] ) ) {

			unset( $providers[ $_POST['provider'] ][ $_POST['key'] ] );
			update_option( 'wpforms_providers', $providers );
			wp_send_json_success();

		} else {
			wp_send_json_error(
				[
					'error' => esc_html__( 'Connection missing', 'wpforms-lite' ),
				]
			);
		}
	}


/** Function ajax_toggle_write() called by wp_ajax hooks: {'wpforms_ai_mcp_toggle_write'} **/
/** Parameters found in function ajax_toggle_write(): {"post": ["enabled"]} **/
function ajax_toggle_write() {

		check_ajax_referer( 'wpforms_ai_mcp_toggle', 'nonce' );

		if ( ! wpforms_current_user_can() ) {
			wp_send_json_error(
				[ 'message' => esc_html__( 'You do not have permission to change this setting.', 'wpforms-lite' ) ],
				403
			);

			return;
		}

		$raw     = isset( $_POST['enabled'] ) ? sanitize_text_field( wp_unslash( $_POST['enabled'] ) ) : '';
		$enabled = rest_sanitize_boolean( $raw );

		$settings                      = (array) get_option( 'wpforms_settings', [] );
		$settings[ self::SETTING_KEY ] = $enabled ? '1' : '';

		// Persist through the canonical helper so the wpforms_update_settings filter and
		// wpforms_settings_updated action run, keeping us in line with every other settings write.
		wpforms_update_settings( $settings );

		wp_send_json_success( [ 'enabled' => $enabled ] );
	}


/** Function wpforms_update_form_template() called by wp_ajax hooks: {'wpforms_update_form_template'} **/
/** Parameters found in function wpforms_update_form_template(): {"post": ["form_id", "template", "category", "subcategory", "title"]} **/
function wpforms_update_form_template() { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded

	// Run a security check.
	check_ajax_referer( 'wpforms-builder', 'nonce' );

	// Check for permissions.
	if ( ! wpforms_current_user_can( 'edit_forms' ) ) {
		wp_send_json_error(
			[
				'error_type' => 'permissions_denied',
				'message'    => esc_html__( 'You are not allowed to perform this action.', 'wpforms-lite' ),
			]
		);
	}

	// Check for form ID.
	if ( empty( $_POST['form_id'] ) ) {
		wp_send_json_error(
			[
				'error_type' => 'invalid_form_id',
				'message'    => esc_html__( 'No Form ID Provided', 'wpforms-lite' ),
			]
		);
	}

	// Set initial variables.
	$form_id       = absint( $_POST['form_id'] );
	$form_template = empty( $_POST['template'] ) ? 'blank' : sanitize_text_field( wp_unslash( $_POST['template'] ) );
	$category      = empty( $_POST['category'] ) ? 'all' : sanitize_text_field( wp_unslash( $_POST['category'] ) );
	$subcategory   = empty( $_POST['subcategory'] ) ? 'all' : sanitize_text_field( wp_unslash( $_POST['subcategory'] ) );

	// Check for valid template.
	if ( ! wpforms()->obj( 'builder_templates' )->is_valid_template( $form_template ) ) {
		wp_send_json_error(
			[
				'error_type' => 'invalid_template',
				'message'    => esc_html__( 'The template you selected is currently not available, but you can try again later. If you continue to have trouble, please reach out to support.', 'wpforms-lite' ),
			]
		);
	}

	// Get current form data.
	$data = wpforms()->obj( 'form' )->get(
		$form_id,
		[
			'content_only' => true,
		]
	);

	// Get the cached data from the form template JSON.
	$template_data = wpforms()->obj( 'builder_templates' )->get_template( $form_template );

	// If the template title is set, use it. Otherwise, clear the form title.
	$template_title = ! empty( $template_data['name'] ) ? $template_data['name'] : '';

	// If the form title is set, use it. Otherwise, use the template title.
	$form_title = ! empty( $_POST['title'] ) ? sanitize_text_field( wp_unslash( $_POST['title'] ) ) : $template_title;

	// Check if the current form title is equal to the previous template name.
	// If so, set the form title equal to the new template name.
	$prev_template_slug = $data['meta']['template'] ?? '';
	$prev_template      = wpforms()->obj( 'builder_templates' )->get_template( $prev_template_slug );
	$form_title         = isset( $prev_template['name'] ) && $prev_template['name'] === $form_title ? $template_title : $form_title;

	// If these template titles are empty, use the form title.
	$form_pages_title          = $template_title ? $template_title : $form_title;
	$form_conversational_title = ! empty( $template_data['data']['settings']['conversational_forms_title'] ) ? $template_data['data']['settings']['conversational_forms_title'] : $form_title;

	// If these template slugs are empty, use the form title.
	$form_conversational_slug = ! empty( $template_data['data']['settings']['conversational_forms_page_slug'] ) ? $template_data['data']['settings']['conversational_forms_page_slug'] : $form_title;
	$form_pages_slug          = ! empty( $template_data['data']['settings']['form_pages_page_slug'] ) ? $template_data['data']['settings']['form_pages_page_slug'] : $form_title;

	// Loop over notifications.
	$notifications = $template_data['data']['settings']['notifications'] ?? [];

	foreach ( $notifications as $key => $notification ) {
		// If the subject is empty, set it to an empty string.
		$notification_subject = ! empty( $notification['subject'] ) ? sanitize_text_field( $notification['subject'] ) : '';

		$data['settings']['notifications'][ $key ]['subject'] = $notification_subject;
	}

	// Loop over confirmations.
	$confirmations = $template_data['data']['settings']['confirmations'] ?? [];

	foreach ( $confirmations as $key => $confirmation ) {

		// If the message is empty, set it to an empty string.
		$confirmation_message = ! empty( $confirmation['message'] ) ? wp_kses_post( $confirmation['message'] ) : '';

		$data['settings']['confirmations'][ $key ]['message'] = $confirmation_message;
	}

	// Set updated form titles.
	$data['settings']['form_title']                 = sanitize_text_field( $form_title );
	$data['settings']['form_pages_title']           = sanitize_text_field( $form_pages_title );
	$data['settings']['conversational_forms_title'] = sanitize_text_field( $form_conversational_title );

	// Set updated form slugs.
	$data['settings']['form_pages_page_slug']           = sanitize_title( $form_pages_slug );
	$data['settings']['conversational_forms_page_slug'] = sanitize_title( $form_conversational_slug );

	// Try to update the form.
	$updated = (bool) wpforms()->obj( 'form' )->update(
		$form_id,
		$data,
		[
			'template'    => $form_template,
			'category'    => $category,
			'subcategory' => $subcategory,
		]
	);

	// If the form was updated, return the form ID and redirect to the form builder.
	if ( $updated ) {
		wp_send_json_success(
			[
				'id'       => $form_id,
				'redirect' => add_query_arg(
					[
						'view'    => 'fields',
						'form_id' => $form_id,
					],
					admin_url( 'admin.php?page=wpforms-builder' )
				),
			]
		);
	}

	// Otherwise, return an error.
	wp_send_json_error(
		[
			'error_type' => 'cant_update',
			'message'    => esc_html__( 'Error Updating Template', 'wpforms-lite' ),
		]
	);
}


/** Function integrations_tab_add() called by wp_ajax hooks: {'wpforms_settings_provider_add_$this->slug'} **/
/** Parameters found in function integrations_tab_add(): {"post": ["provider", "data"]} **/
function integrations_tab_add() {

		if ( $_POST['provider'] !== $this->slug ) { //phpcs:ignore
			return;
		}

		// Run a security check.
		check_ajax_referer( 'wpforms-admin', 'nonce' );

		// Check for permissions.
		if ( ! wpforms_current_user_can() ) {
			wp_send_json_error(
				[
					'error' => esc_html__( 'You do not have permission', 'wpforms-lite' ),
				]
			);
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
		$args = $_POST['data'] ?? [];

		if ( empty( $args ) ) {
			wp_send_json_error(
				[
					'error' => esc_html__( 'Missing data', 'wpforms-lite' ),
				]
			);
		}

		$data = wp_parse_args( $args );
		$auth = $this->api_auth( $data );

		if ( is_wp_error( $auth ) ) {

			wp_send_json_error(
				[
					'error'     => esc_html__( 'Could not connect to the provider.', 'wpforms-lite' ),
					'error_msg' => $auth->get_error_message(),
				]
			);

		} else {

			$account  = '<li class="wpforms-clear">';
			$account .= '<span class="label">' . sanitize_text_field( $data['label'] ) . '</span>';
			$account .= '<span class="date">';
			$account .= esc_html(
				sprintf( /* translators: %1$s - Connection date. */
					__( 'Connected on: %1$s', 'wpforms-lite' ),
					wpforms_date_format( time(), '', true )
				)
			);
			$account .= '</span>';
			$account .= '<span class="remove"><a href="#" data-provider="' . $this->slug . '" data-key="' . esc_attr( $auth ) . '">' . esc_html__( 'Disconnect', 'wpforms-lite' ) . '</a></span>';
			$account .= '</li>';

			wp_send_json_success(
				[
					'html' => $account,
				]
			);
		}
	}


/** Function ajax_check_plugin_status() called by wp_ajax hooks: {'wpforms_page_check_{$plugin}_status'} **/
/** No params detected :-/ **/


/** Function ajax_disconnect() called by wp_ajax hooks: {'wpforms_settings_provider_disconnect_{$this->core->slug}'} **/
/** Parameters found in function ajax_disconnect(): {"post": ["provider", "key"]} **/
function ajax_disconnect() {

		// Run a security check.
		if ( ! check_ajax_referer( 'wpforms-admin', 'nonce', false ) ) {
			wp_send_json_error(
				[
					'error_msg' => esc_html__( 'Your session expired. Please reload the page.', 'wpforms-lite' ),
				]
			);
		}

		// Check for permissions.
		if ( ! wpforms_current_user_can() ) {
			wp_send_json_error(
				[
					'error_msg' => esc_html__( 'You do not have permission.', 'wpforms-lite' ),
				]
			);
		}

		if ( empty( $_POST['provider'] ) || empty( $_POST['key'] ) ) {
			wp_send_json_error(
				[
					'error_msg' => esc_html__( 'Missing data.', 'wpforms-lite' ),
				]
			);
		}

		$providers = wpforms_get_providers_options();

		if ( ! empty( $providers[ $_POST['provider'] ][ $_POST['key'] ] ) ) {

			unset( $providers[ $_POST['provider'] ][ $_POST['key'] ] );
			update_option( 'wpforms_providers', $providers );
			wp_send_json_success();

		} else {
			wp_send_json_error(
				[
					'error_msg' => esc_html__( 'Connection missing.', 'wpforms-lite' ),
				]
			);
		}
	}


/** Function process() called by wp_ajax hooks: {'nopriv_wpforms_connect_process', 'wpforms_ai_form_editor_process'} **/
/** Parameters found in function process(): {"post": ["__amp_form_verify", "page_url", "page_title", "page_id", "url_referer", "wpforms"]} **/
function process( $entry ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded, Generic.Metrics.NestingLevel.MaxExceeded

		$this->errors = [];
		$this->fields = [];

		$form_id = absint( $entry['id'] );
		$form    = wpforms()->obj( 'form' )->get( $form_id );

		// Validate form is real and active (published).
		if ( ! $form || $form->post_status !== 'publish' ) {
			$this->errors[ $form_id ]['header'] = esc_html__( 'Invalid form.', 'wpforms-lite' );

			return;
		}

		/**
		 * Filter form data obtained during the form process.
		 *
		 * @since 1.5.3
		 *
		 * @param array $form_data Form data.
		 * @param array $entry     Form entry.
		 */
		$this->form_data = (array) apply_filters( 'wpforms_process_before_form_data', wpforms_decode( $form->post_content ), $entry );

		if ( ! empty( $this->form_data['settings']['ajax_submit'] ) && ! $this->is_valid_ajax_submit_action() ) {
			wpforms_log(
				'Attempt to submit corrupted post data.',
				wp_unslash( $_POST ),
				[
					'type'    => [ 'error', 'entry' ],
					'form_id' => $this->form_data['id'],
				]
			);

			$this->errors[ $form_id ]['header'] = esc_html__( 'Attempt to submit corrupted post data.', 'wpforms-lite' );

			/**
			 * Fires when corrupted form submission is detected.
			 *
			 * @since 1.9.8
			 *
			 * @param array $form_data Form data.
			 */
			do_action( 'wpforms_process_submission_corrupted', $this->form_data );

			return;
		}

		$store_spam_entries = ! empty( $this->form_data['settings']['store_spam_entries'] );

		/**
		 * Check the modern Anti-Spam (v3) protection.
		 *
		 * Run as early as possible to remove the honeypot field from the entry to prevent unnecessary field processing.
		 * Bail early if the form is marked as spam and storing spam entries is disabled.
		 *
		 * Important! We should check first on modern Anti-Spam because it is skipped in case $store_spam_entries === true.
		 */
		// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		/** @noinspection NotOptimalIfConditionsInspection */
		if ( ! $this->modern_anti_spam_check( $entry ) && ! $store_spam_entries ) {
			return;
		}

		if ( ! isset( $this->form_data['fields'], $this->form_data['id'] ) ) {
			$error_id = uniqid( '', true );

			// Logs missing form data.
			wpforms_log(
				/* translators: %s - error unique ID. */
				sprintf( esc_html__( 'Missing form data on form submission process %s', 'wpforms-lite' ), $error_id ),
				esc_html__( 'Form data is not an array in `\WPForms_Process::process()`. It might be caused by incorrect data returned by `wpforms_process_before_form_data` filter. Verify whether you have a custom code using this filter and debug value it is returning.', 'wpforms-lite' ),
				[
					'type'    => [ 'error', 'entry' ],
					'form_id' => $form_id,
				]
			);

			$error_messages[] = esc_html__( 'Your form has not been submitted because data is missing from the entry.', 'wpforms-lite' );

			if ( wpforms_setting( 'logs-enable' ) && wpforms_current_user_can( wpforms_get_capability_manage_options() ) ) {
				$error_messages[] = sprintf(
					wp_kses( /* translators: %s - URL to the WForms Logs admin page. */
						__( 'Check the WPForms &raquo; Tools &raquo; <a href="%s">Logs</a> for more details.', 'wpforms-lite' ),
						[ 'a' => [ 'href' => [] ] ]
					),
					esc_url(
						add_query_arg(
							[
								'page' => 'wpforms-tool',
								'view' => 'logs',
							],
							admin_url( 'admin.php' )
						)
					)
				);

				/* translators: %s - error unique ID. */
				$error_messages[] = sprintf( esc_html__( 'Error ID: %s.', 'wpforms-lite' ), $error_id );
			}

			$errors[ $form_id ]['header'] = implode( '<br>', $error_messages );
			$this->errors                 = $errors;

			return;
		}

		/**
		 * Filter form entry before processing.
		 * Data are not validated or cleaned yet, so use them with caution.
		 *
		 * @since 1.4.0
		 *
		 * @param array $entry     Form submission raw data ($_POST).
		 * @param array $form_data Form data.
		 */
		$entry = apply_filters( 'wpforms_process_before_filter', $entry, $this->form_data );

		/**
		 * Pre-process hook.
		 *
		 * @since 1.4.0
		 *
		 * @param array $entry     Form submission raw data ($_POST).
		 * @param array $form_data Form data.
		 */
		do_action( 'wpforms_process_before', $entry, $this->form_data );

		/**
		 * Pre-process hook by form ID.
		 *
		 * @since 1.4.0
		 *
		 * @param array $entry     Form submission raw data ($_POST).
		 * @param array $form_data Form data.
		 */
		do_action( "wpforms_process_before_{$form_id}", $entry, $this->form_data );

		// Validate fields.
		foreach ( $this->form_data['fields'] as $field_properties ) {

			$field_id     = $field_properties['id'];
			$field_type   = $field_properties['type'];
			$field_submit = $entry['fields'][ $field_id ] ?? '';

			/**
			 * Field type validation hook.
			 *
			 * @since 1.4.0
			 *
			 * @param string|int $field_id     Field ID as a numeric string.
			 * @param mixed      $field_submit Submitted field value (raw data).
			 * @param array      $form_data    Form data.
			 */
			do_action( "wpforms_process_validate_{$field_type}", $field_id, $field_submit, $this->form_data );
		}

		// Check if combined upload size exceeds allowed maximum.
		$this->validate_combined_upload_size( $form );

		/**
		 * Filter initial errors.
		 * Don't proceed if there are any errors thus far.
		 * We provide a filter so that other features, such as conditional logic, can adjust blocking errors.
		 *
		 * @since 1.4.0
		 *
		 * @param array $errors     List of errors.
		 * @param array $form_data  Form data.
		 */
		$errors = apply_filters( 'wpforms_process_initial_errors', $this->errors, $this->form_data );

		if ( isset( $_POST['__amp_form_verify'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			if ( empty( $errors[ $form_id ] ) ) {
				wp_send_json( [], 200 );
			} else {
				$verify_errors = [];

				foreach ( $errors[ $form_id ] as $field_id => $error_fields ) {
					$field            = $this->form_data['fields'][ $field_id ];
					$field_properties = wpforms()->obj( 'frontend' )->get_field_properties( $field, $this->form_data );

					if ( is_string( $error_fields ) ) {
						$name = '';

						if ( $field['type'] === 'checkbox' || $field['type'] === 'radio' || $field['type'] === 'select' ) {
							$first = current( $field_properties['inputs'] );
							$name  = $first['attr']['name'];
						} elseif ( isset( $field_properties['inputs']['primary']['attr']['name'] ) ) {
							$name = $field_properties['inputs']['primary']['attr']['name'];
						}

						$verify_errors[] = [
							'name'    => $name,
							'message' => $error_fields,
						];
					} else {
						foreach ( $error_fields as $error_field => $error_message ) {
							$name = $field_properties['inputs'][ $error_field ]['attr']['name'] ?? '';

							$verify_errors[] = [
								'name'    => $name,
								'message' => $error_message,
							];
						}
					}
				}

				wp_send_json(
					[
						'verifyErrors' => $verify_errors,
					],
					400
				);
			}

			return;
		}

		if ( ! empty( $errors[ $form_id ] ) ) {

			if ( empty( $errors[ $form_id ]['header'] ) && empty( $errors[ $form_id ]['footer'] ) ) {
				$errors[ $form_id ]['header'] = esc_html__( 'Form has not been submitted, please see the errors below.', 'wpforms-lite' );
			}

			$this->errors = $errors;

			return;
		}

		// If a logged-in user fails the nonce check, we want to log the entry, disable the errors and fail silently.
		// Please note that logs may be disabled, and in this case, nothing will be logged or reported.
		if (
			is_user_logged_in() &&
			( empty( $entry['nonce'] ) || ! wp_verify_nonce( $entry['nonce'], "wpforms::form_{$form_id}" ) )
		) {
			// Logs XSS attempt depending on log levels set.
			wpforms_log(
				'Cross-site scripting attempt ' . uniqid( '', true ),
				[ true, $entry ],
				[
					'type'    => [ 'security' ],
					'form_id' => $this->form_data['id'],
				]
			);

			$this->errors[ $this->form_data['id'] ]['footer_styled'] = esc_html__( 'The form could not be submitted due to a security issue.', 'wpforms-lite' );

			return;
		}

		// Format fields.
		foreach ( (array) $this->form_data['fields'] as $field_properties ) {

			$field_id     = $field_properties['id'];
			$field_type   = $field_properties['type'];
			$field_submit = $entry['fields'][ $field_id ] ?? '';

			/**
			 * Format field by type.
			 *
			 * @since 1.4.0
			 *
			 * @param string $field_id     Field ID.
			 * @param string $field_submit Submitted field value.
			 * @param array  $form_data    Form data and settings.
			 */
			do_action( "wpforms_process_format_{$field_type}", $field_id, $field_submit, $this->form_data );
		}

		$honeypot = wpforms()->obj( 'honeypot' )->validate( $this->form_data, $this->fields, $entry );

		// If we trigger the honey pot, we want to log the entry, disable the errors, and fail silently.
		if ( $honeypot ) {

			$this->log_spam_entry( $entry, $honeypot );

			// Fail silently.
			return;
		}

		$token = wpforms()->obj( 'token' )->validate( $this->form_data, $this->fields, $entry );

		// If spam - return early.
		// For antispam, we want to make sure that we have a value, we are not using AMP, and the value is an error string.
		if ( $token && is_string( $token ) && ! wpforms_is_amp() ) {
			$this->errors[ $this->form_data['id'] ]['header'] = $token;

			$this->log_spam_entry( $entry, $token );

			return;
		}

		// Detect direct POST requests when the AJAX submission is enabled.
		$this->direct_post_request_check( $entry );

		$is_pro = wpforms()->is_pro();

		if ( ! $this->is_bypass_spam_check( $entry ) ) {
			// Store spam entries detected by filtering.
			if ( $is_pro && ! empty( $this->form_data['settings']['anti_spam']['filtering_store_spam'] ) ) {
				$this->country_filter_check( $entry, $form_id );
				$this->keyword_filter_check( $entry, $form_id );
			}

			// Check if the form was submitted too quickly.
			$this->time_limit_check();

			// Check for spam.
			$this->process_spam_check( $entry );
		}

		// Convert spam errors to form errors if spam entries are not stored.
		if ( ! $store_spam_entries && ! empty( $this->spam_errors ) ) {
			$this->errors = $this->spam_errors;
		}

		// Store spam reason.
		if ( $this->spam_reason ) {
			$this->form_data['spam_reason'] = $this->spam_reason;
		}

		// Pass the form creation date into the form data.
		$this->form_data['created'] = $form->post_date;

		/**
		 * Format form data after all fields have been processed.
		 * This hook is for internal purposes and should not be leveraged.
		 *
		 * @since 1.4.0
		 *
		 * @param array $form_data Form data and settings.
		 */
		do_action( 'wpforms_process_format_after', $this->form_data );

		/**
		 * Filter fields before processing.
		 * Process hooks/filter - this is where most addons should hook
		 * because at this point we have completed all field validations and formatted the data.
		 *
		 * @since 1.4.0
		 *
		 * @param array $fields    Form fields.
		 * @param array $entry     Form submission raw data ($_POST).
		 * @param array $form_data Form data and settings.
		 */
		$this->fields = apply_filters( 'wpforms_process_filter', $this->fields, $entry, $this->form_data );
		/**
		 * Process form fields.
		 *
		 * @since 1.4.0
		 *
		 * @param array $fields    Form fields.
		 * @param array $entry     Form submission raw data ($_POST).
		 * @param array $form_data Form data and settings.
		 */
		do_action( 'wpforms_process', $this->fields, $entry, $this->form_data );

		/**
		 * Process form fields by form ID.
		 *
		 * @since 1.4.0
		 *
		 * @param array $fields    Form fields.
		 * @param array $entry     Form submission raw data ($_POST).
		 * @param array $form_data Form data and settings.
		 */
		do_action( "wpforms_process_{$form_id}", $this->fields, $entry, $this->form_data );

		/**
		 * Filter fields after processing.
		 *
		 * @since 1.4.0
		 *
		 * @param array $fields    Form fields.
		 * @param array $entry     Form submission raw data ($_POST).
		 * @param array $form_data Form data and settings.
		 */
		$this->fields = apply_filters( 'wpforms_process_after_filter', $this->fields, $entry, $this->form_data );

		// One last error check - don't proceed if there are any errors.
		if ( ! empty( $this->errors[ $form_id ] ) ) {

			if ( empty( $this->errors[ $form_id ]['header'] ) && empty( $this->errors[ $form_id ]['footer'] ) ) {
				$this->errors[ $form_id ]['header'] = esc_html__( 'Form has not been submitted, please see the errors below.', 'wpforms-lite' );
			}

			return;
		}

		// Set raw post data.
		$this->form_data['post_data_raw'] = [
			'page_url' => isset( $_POST['page_url'] ) ? esc_url_raw( wp_unslash( $_POST['page_url'] ) ) : '',
		];

		// Success - add entry to a database.
		$this->entry_id = $this->entry_save( $this->fields, $entry, $this->form_data['id'], $this->form_data );

		// Add payment to a database.
		$payment_id = $this->payment_save( $entry );

		$this->form_data['entry_meta'] = [
			'page_url'    => isset( $_POST['page_url'] ) ? esc_url_raw( wp_unslash( $_POST['page_url'] ) ) : '',
			'page_title'  => isset( $_POST['page_title'] ) ? sanitize_text_field( wp_unslash( $_POST['page_title'] ) ) : '',
			'page_id'     => isset( $_POST['page_id'] ) ? absint( $_POST['page_id'] ) : '',
			'url_referer' => isset( $_POST['url_referer'] ) ? esc_url_raw( wp_unslash( $_POST['url_referer'] ) ) : '',
			'user_id'     => get_current_user_id(),
		];

		// Save meta data.
		$this->save_meta( $this->entry_id, $this->form_data['id'] );

		/**
		 * Runs right after adding an entry to the database.
		 *
		 * @since 1.7.7
		 * @since 1.8.2 Added Payment ID param.
		 *
		 * @param array $fields     Fields data.
		 * @param array $entry      User submitted data.
		 * @param array $form_data  Form data.
		 * @param int   $entry_id   Entry ID.
		 * @param int   $payment_id Payment ID.
		 */
		do_action( 'wpforms_process_entry_saved', $this->fields, $entry, $this->form_data, $this->entry_id, $payment_id );

		// Fire the logic to send notification emails.
		$this->entry_email( $this->fields, $entry, $this->form_data, $this->entry_id, 'entry' );

		// Pass completed and formatted fields in POST.
		$_POST['wpforms']['complete'] = $this->fields;

		// Pass entry ID in POST.
		$_POST['wpforms']['entry_id'] = $this->entry_id;

		// Logs entry depending on log levels set.
		if ( $is_pro ) {
			wpforms_log(
				$this->entry_id ? "Entry {$this->entry_id}" : 'Entry',
				$this->fields,
				[
					'type'    => [ 'entry' ],
					'parent'  => $this->entry_id,
					'form_id' => $this->form_data['id'],
				]
			);
		}

		// Mark the submission as spam if one of the spam checks failed and spam entries are stored.
		$marked_as_spam = $this->spam_reason && $store_spam_entries;

		// Proceed if the entry is not marked as spam.
		if ( ! $marked_as_spam ) {
			$this->process_complete( $form_id, $this->form_data, $this->fields, $entry, $this->entry_id );
		} else {
			/**
			 * Fires in the case the entry was marked as spam during the form submission.
			 *
			 * @since 1.9.8.1
			 *
			 * @param int $entry_id Entry ID.
			 * @param int $form_id  Form ID.
			 */
			do_action( 'wpforms_process_anti_spam_entry_marked_as_spam', $this->entry_id, $form_id );
		}

		$this->entry_confirmation_redirect( $this->form_data );
	}


/** Function ajax_dismiss() called by wp_ajax hooks: {'wpforms_education_dismiss'} **/
/** Parameters found in function ajax_dismiss(): {"post": ["section"]} **/
function ajax_dismiss() {

		// Run a security check.
		check_ajax_referer( 'wpforms-education', 'nonce' );

		// Section is the identifier of the education feature.
		// For example, in Builder/DidYouKnow feature used 'builder-did-you-know-notifications'
		// and 'builder-did-you-know-confirmations'.
		$section = ! empty( $_POST['section'] ) ? sanitize_key( $_POST['section'] ) : '';

		if ( empty( $section ) ) {
			wp_send_json_error(
				[ 'error' => esc_html__( 'Please specify a section.', 'wpforms-lite' ) ]
			);
		}

		// Check for permissions.
		if ( ! $this->current_user_can() ) {
			wp_send_json_error(
				[ 'error' => esc_html__( 'You do not have permission to perform this action.', 'wpforms-lite' ) ]
			);
		}

		$user_id   = get_current_user_id();
		$dismissed = get_user_meta( $user_id, 'wpforms_dismissed', true );

		if ( empty( $dismissed ) ) {
			$dismissed = [];
		}

		$dismissed[ 'edu-' . $section ] = time();

		update_user_meta( $user_id, 'wpforms_dismissed', $dismissed );
		wp_send_json_success();
	}


/** Function ajax_remove_user_template() called by wp_ajax hooks: {'wpforms_user_template_remove'} **/
/** Parameters found in function ajax_remove_user_template(): {"post": ["template"]} **/
function ajax_remove_user_template(): void {

		// Run a security check.
		check_ajax_referer( 'wpforms-form-templates', 'nonce' );

		$template_id = isset( $_POST['template'] ) ? absint( $_POST['template'] ) : 0;

		if ( ! $template_id ) {
			wp_send_json_error();
		}

		// Check for permissions for the specific template.
		if ( ! wpforms_current_user_can( 'delete_form_single', $template_id ) ) {
			wp_send_json_error( esc_html__( 'You do not have permission to delete this template.', 'wpforms-lite' ) );
		}

		// Verify the post exists and is a template.
		$template = get_post( $template_id );

		if ( ! $template || $template->post_type !== 'wpforms-template' ) {
			wp_send_json_error( esc_html__( 'Template not found.', 'wpforms-lite' ) );
		}

		// Delete the template.
		$result = wp_delete_post( $template_id, true );

		if ( ! $result ) {
			wp_send_json_error( esc_html__( 'Failed to delete the template.', 'wpforms-lite' ) );
		}

		wp_send_json_success();
	}


/** Function single_checkout_create_order_ajax() called by wp_ajax hooks: {'wpforms_paypal_commerce_create_order', 'nopriv_wpforms_paypal_commerce_create_order'} **/
/** Parameters found in function single_checkout_create_order_ajax(): {"post": ["nonce", "wpforms", "total"]} **/
function single_checkout_create_order_ajax(): void {

		if (
			! isset( $_POST['nonce'] ) ||
			! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'wpforms-paypal-commerce-create-order' )
		) {
			wp_send_json_error( esc_html__( 'You are not allowed to perform this action.', 'wpforms-lite' ) );
		}

		$this->form_id = isset( $_POST['wpforms']['id'] ) ? absint( $_POST['wpforms']['id'] ) : 0;

		if ( empty( $this->form_id ) || ! isset( $_POST['wpforms'], $_POST['total'] ) ) {
			wp_send_json_error( esc_html__( 'Something went wrong. Please contact site administrator.', 'wpforms-lite' ) );
		}

		$this->connection = Connection::get();
		$this->form_data  = wpforms()->obj( 'form' )->get( $this->form_id, [ 'content_only' => true ] );
		$order_data       = $this->prepare_single_order_data();

		if ( ! $this->is_form_ok() ) {
			wp_send_json_error( $this->errors );
		}

		$error_title = esc_html__( 'This order cannot be created because there was an error with the create order API call.', 'wpforms-lite' );

		$api = PayPalCommerce::get_api( $this->connection );

		if ( is_null( $api ) ) {
			wp_send_json_error( $error_title );
		}

		$order_response = $api->create_order( $order_data );

		if ( $order_response->has_errors() ) {
			$order_response_message = $order_response->get_response_message();
			$error_description      = $this->get_order_error_description( $order_response_message );

			$this->log_errors( $error_title, $order_response_message );

			wp_send_json_error( $error_description ?? $error_title ); // phpcs:ignore Universal.Operators.DisallowShortTernary.Found
		}

		$order = $order_response->get_body();

		wp_send_json_success( $order );
	}


/** Function captcha_field_callback() called by wp_ajax hooks: {'wpforms_update_field_captcha'} **/
/** Parameters found in function captcha_field_callback(): {"post": ["id"]} **/
function captcha_field_callback() {

		// Run a security check.
		check_ajax_referer( 'wpforms-builder', 'nonce' );

		// Check for form ID.
		if ( empty( $_POST['id'] ) ) {
			wp_send_json_error( esc_html__( 'No form ID found.', 'wpforms-lite' ) );
		}

		$form_id = absint( $_POST['id'] );

		// Check for permissions.
		if ( ! wpforms_current_user_can( 'edit_form_single', $form_id ) ) {
			wp_send_json_error( esc_html__( 'You do not have permission.', 'wpforms-lite' ) );
		}

		// Get an actual form data.
		$form_data = wpforms()->obj( 'form' )->get( $form_id, [ 'content_only' => true ] );

		// Check that CAPTCHA is configured in the settings.
		$captcha_settings = wpforms_get_captcha_settings();
		$captcha_name     = $this->get_captcha_name( $captcha_settings );

		if ( empty( $form_data ) || empty( $captcha_name ) ) {
			wp_send_json_error( esc_html__( 'Something wrong. Please try again later.', 'wpforms-lite' ) );
		}

		// Prepare a result array.
		$data = $this->get_captcha_result_mockup( $captcha_settings );

		if ( empty( $captcha_settings['site_key'] ) || empty( $captcha_settings['secret_key'] ) ) {

			// If CAPTCHA is not configured in the WPForms plugin settings.
			$data['current'] = 'not_configured';

		} elseif ( ! isset( $form_data['settings']['recaptcha'] ) || $form_data['settings']['recaptcha'] !== '1' ) {

			// If CAPTCHA is configured in WPForms plugin settings, but wasn't set in form settings.
			$data['current'] = 'configured_not_enabled';

		} else {

			// If CAPTCHA is configured in WPForms plugin and form settings.
			$data['current'] = 'configured_enabled';
		}

		wp_send_json_success( $data );
	}


/** Function preview() called by wp_ajax hooks: {'wpforms_divi_preview'} **/
/** No params detected :-/ **/


/** Function ajax_get_form_selector_options() called by wp_ajax hooks: {'wpforms_admin_get_form_selector_options'} **/
/** No params detected :-/ **/


/** Function dismiss() called by wp_ajax hooks: {'wpforms_dismiss_ai_form', 'wpforms_notification_dismiss', 'wpforms_woocommerce_dismiss'} **/
/** Parameters found in function dismiss(): {"post": ["id"]} **/
function dismiss() {

		// Check for required param, security and access.
		if (
			empty( $_POST['id'] ) ||
			! check_ajax_referer( 'wpforms-admin', 'nonce', false ) ||
			! $this->has_access()
		) {
			wp_send_json_error();
		}

		$id     = sanitize_key( $_POST['id'] );
		$type   = is_numeric( $id ) ? 'feed' : 'events';
		$option = $this->get_option();

		$option['dismissed'][] = $id;
		$option['dismissed']   = array_unique( $option['dismissed'] );

		// Remove notification.
		if ( is_array( $option[ $type ] ) && ! empty( $option[ $type ] ) ) {
			foreach ( $option[ $type ] as $key => $notification ) {
				if ( (string) $notification['id'] === (string) $id ) {
					unset( $option[ $type ][ $key ] );

					break;
				}
			}
		}

		update_option( 'wpforms_notifications', $option );

		wp_send_json_success();
	}


/** Function save_widget_meta_ajax() called by wp_ajax hooks: {'wpforms_{$widget_slug}_save_widget_meta'} **/
/** Parameters found in function save_widget_meta_ajax(): {"post": ["meta", "value"]} **/
function save_widget_meta_ajax() {

		check_ajax_referer( 'wpforms_' . static::SLUG . '_nonce' );

		$meta  = ! empty( $_POST['meta'] ) ? sanitize_key( $_POST['meta'] ) : '';
		$value = ! empty( $_POST['value'] ) ? absint( $_POST['value'] ) : 0;

		$this->widget_meta( 'set', $meta, $value );

		exit();
	}


/** Function wpforms_activate_addon() called by wp_ajax hooks: {'wpforms_activate_addon'} **/
/** Parameters found in function wpforms_activate_addon(): {"post": ["type", "plugin"]} **/
function wpforms_activate_addon() {

	// Run a security check.
	check_ajax_referer( 'wpforms-admin', 'nonce' );

	// Check for permissions.
	if ( ! current_user_can( 'activate_plugins' ) ) {
		wp_send_json_error( esc_html__( 'Plugin activation is disabled for you on this site.', 'wpforms-lite' ) );
	}

	$success_messages = [
		'plugin' => __( 'Plugin activated.', 'wpforms-lite' ),
		'addon'  => __( 'Addon activated.', 'wpforms-lite' ),
	];
	$error_messages   = [
		'plugin' => __( 'Could not activate the plugin. Please activate it on the Plugins page.', 'wpforms-lite' ),
		'addon'  => __( 'Could not activate the addon. Please activate it on the Plugins page.', 'wpforms-lite' ),
	];

	$type            = ! empty( $_POST['type'] ) ? sanitize_key( $_POST['type'] ) : 'addon';
	$success_message = $success_messages[ $type ];
	$error_message   = $error_messages[ $type ];

	if ( isset( $_POST['plugin'] ) ) {
		$plugin   = sanitize_text_field( wp_unslash( $_POST['plugin'] ) );
		$activate = wpforms_activate_plugin( $plugin );

		/**
		 * Fire after the plugin activating via the WPForms installer.
		 *
		 * @since 1.6.3.1
		 *
		 * @param string $plugin Path to the plugin file relative to the plugins' directory.
		 */
		do_action( 'wpforms_plugin_activated', $plugin );

		if ( $activate === null ) {
			wp_send_json_success( wp_kses_post( $success_message ) );
		}

		$error_message = $activate->get_error_message();
	}

	wp_send_json_error( wp_kses_post( $error_message ) );
}


/** Function ajax_single_payment_cancel() called by wp_ajax hooks: {'wpforms_stripe_payments_cancel'} **/
/** Parameters found in function ajax_single_payment_cancel(): {"post": ["payment_id"]} **/
function ajax_single_payment_cancel() {

		if ( ! isset( $_POST['payment_id'] ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Payment ID not provided.', 'wpforms-lite' ) ] );
		}

		if ( ! wpforms_current_user_can( wpforms_get_capability_manage_options() ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to perform this action.', 'wpforms-lite' ) ] );
		}

		$this->check_payment_collection_type();
		check_ajax_referer( 'wpforms-admin', 'nonce' );

		$payment_id = (int) $_POST['payment_id'];
		$payment_db = wpforms()->obj( 'payment' )->get( $payment_id );

		if ( empty( $payment_db ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Subscription not found in the database.', 'wpforms-lite' ) ] );
		}

		$cancel = $this->payment_intents->cancel_subscription( $payment_db->subscription_id );

		if ( ! $cancel ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Subscription cancellation failed.', 'wpforms-lite' ) ] );
		}

		if ( UpdateHelpers::cancel_subscription( $payment_db->id, 'Stripe subscription cancelled from the WPForms plugin interface.' ) ) {
			wp_send_json_success( [ 'message' => esc_html__( 'Subscription cancelled.', 'wpforms-lite' ) ] );
		}

		wp_send_json_error( [ 'message' => esc_html__( 'Updating subscription in the database failed.', 'wpforms-lite' ) ] );
	}


/** Function wpforms_recreate_tables() called by wp_ajax hooks: {'wpforms_recreate_tables'} **/
/** No params detected :-/ **/


/** Function save_chart_preference_settings() called by wp_ajax hooks: {'wpforms_payments_overview_save_chart_preference_settings'} **/
/** Parameters found in function save_chart_preference_settings(): {"post": ["graphStyle"]} **/
function save_chart_preference_settings() {

		// Run a security check.
		check_ajax_referer( 'wpforms_payments_overview_nonce' );

		// Check for permissions.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( 'You are not allowed to perform this action.', 'wpforms-lite' ) );
		}

		$graph_style = isset( $_POST['graphStyle'] ) ? absint( $_POST['graphStyle'] ) : 2; // Line.

		update_user_meta( get_current_user_id(), 'wpforms_dash_widget_graph_style', $graph_style );

		exit();
	}


/** Function ajax_lite_connect_finalize() called by wp_ajax hooks: {'wpforms_lite_connect_finalize'} **/
/** No params detected :-/ **/


/** Function mark_panel_viewed() called by wp_ajax hooks: {'wpforms_mark_panel_viewed'} **/
/** No params detected :-/ **/


/** Function wpforms_save_form() called by wp_ajax hooks: {'wpforms_save_form'} **/
/** Parameters found in function wpforms_save_form(): {"post": ["data", "ai_revision_source"]} **/
function wpforms_save_form() {

	// Run a security check.
	if ( ! check_ajax_referer( 'wpforms-builder', 'nonce', false ) ) {
		wp_send_json_error( esc_html__( 'Your session expired. Please reload the builder.', 'wpforms-lite' ) );
	}

	// Check for permissions.
	if ( ! wpforms_current_user_can( 'edit_forms' ) ) {
		wp_send_json_error( esc_html__( 'You are not allowed to perform this action.', 'wpforms-lite' ) );
	}

	// Check for form data.
	if ( empty( $_POST['data'] ) ) {
		wp_send_json_error( esc_html__( 'Something went wrong while performing this action.', 'wpforms-lite' ) );
	}

	// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$form_post = json_decode( wp_unslash( $_POST['data'] ), false );

	$data = wpforms_prepare_form_data( $form_post );
	$data = wpforms_sanitize_form_data( $data );

	// Process fields data.
	$data['fields'] = wpforms()->obj( 'builder_save_form' )->process_fields( $data['fields'], $data );

	// Get form tags.
	$form_tags = isset( $data['settings']['form_tags_json'] ) ? json_decode( wp_unslash( $data['settings']['form_tags_json'] ), true ) : [];

	// Clear unnecessary data.
	unset( $data['settings']['form_tags_json'] );

	// Store tags labels in the form settings.
	$data['settings']['form_tags'] = wp_list_pluck( $form_tags, 'label' );

	// Track AI auto-save revisions so the Revisions panel can label them later.
	// The source key identifies which AI feature triggered the checkpoint
	// ('smart_edit', 'choices', etc.) — empty string means the save is not AI-driven.
	// phpcs:ignore WordPress.Security.NonceVerification.Missing
	$ai_revision_source = isset( $_POST['ai_revision_source'] )
		? sanitize_key( wp_unslash( $_POST['ai_revision_source'] ) )
		: '';

	$prev_latest_revision_id = $ai_revision_source
		? wpforms_ai_revision_tracking_start( (int) $data['id'] )
		: 0;

	// Update form data. Initialize $form_id so _finalize() always receives a defined value
	// even if update() throws before assigning it.
	$form_id = 0;

	try {
		$form_id = (int) wpforms()->obj( 'form' )->update( $data['id'], $data, [ 'context' => 'save_form' ] );
	} finally {
		if ( $ai_revision_source ) {
			wpforms_ai_revision_tracking_finalize( $form_id, $prev_latest_revision_id, $ai_revision_source );
		}
	}

	/**
	 * Fires after updating form data.
	 *
	 * @since 1.4.0
	 *
	 * @param int   $form_id Form ID.
	 * @param array $data    Form data.
	 */
	do_action( 'wpforms_builder_save_form', $form_id, $data );

	if ( ! $form_id ) {
		wp_send_json_error( esc_html__( 'Something went wrong while saving the form.', 'wpforms-lite' ) );
	}

	// Update form tags.
	wp_set_post_terms(
		$form_id,
		wpforms()->obj( 'forms_tags_ajax' )->get_processed_tags( $form_tags ),
		WPForms_Form_Handler::TAGS_TAXONOMY
	);

	$response_data = [
		'form_name' => esc_html( $data['settings']['form_title'] ),
		'form_desc' => $data['settings']['form_desc'],
		'redirect'  => admin_url( 'admin.php?page=wpforms-overview' ),
	];

	/**
	 * Allows filtering ajax response data after the form was saved.
	 *
	 * @since 1.5.1
	 *
	 * @param array $response_data The data to be sent in the response.
	 * @param int   $form_id       Form ID.
	 * @param array $data          Form data.
	 */
	$response_data = apply_filters(
		'wpforms_builder_save_form_response_data',
		$response_data,
		$form_id,
		$data
	);

	wp_send_json_success( $response_data );
}


/** Function dismiss_ajax() called by wp_ajax hooks: {'wpforms_notice_dismiss'} **/
/** No params detected :-/ **/


/** Function ajax_sanitize_restricted_rules() called by wp_ajax hooks: {'wpforms_sanitize_restricted_rules'} **/
/** No params detected :-/ **/


/** Function wpforms_builder_dynamic_choices() called by wp_ajax hooks: {'wpforms_builder_dynamic_choices'} **/
/** Parameters found in function wpforms_builder_dynamic_choices(): {"post": ["field_id", "type"]} **/
function wpforms_builder_dynamic_choices() {

	// Run a security check.
	check_ajax_referer( 'wpforms-builder', 'nonce' );

	// Check for permissions.
	if ( ! wpforms_current_user_can( 'edit_forms' ) ) {
		wp_send_json_error();
	}

	// Check for valid/required items.
	if ( ! isset( $_POST['field_id'] ) || empty( $_POST['type'] ) || ! in_array( $_POST['type'], [ 'post_type', 'taxonomy' ], true ) ) {
		wp_send_json_error();
	}

	$type = sanitize_key( $_POST['type'] );
	$id   = sanitize_text_field( wp_unslash( $_POST['field_id'] ) );

	// Fetch the option row HTML to be returned to the builder.
	$field      = new WPForms_Field_Select( false );
	$field_args = [
		'id'              => $id,
		'dynamic_choices' => $type,
	];
	$option_row = $field->field_option( 'dynamic_choices_source', $field_args, [], false );

	wp_send_json_success(
		[
			'markup' => $option_row,
		]
	);
}


/** Function dismiss_callback() called by wp_ajax hooks: {'wpforms_education_pointers_dismiss'} **/
/** No params detected :-/ **/


/** Function engagement_callback() called by wp_ajax hooks: {'wpforms_education_pointers_engagement'} **/
/** No params detected :-/ **/


/** Function ajax_submit() called by wp_ajax hooks: {'wpforms_submit', 'nopriv_wpforms_submit'} **/
/** Parameters found in function ajax_submit(): {"post": ["wpforms"]} **/
function ajax_submit() { // phpcs:ignore WPForms.PHP.HooksMethod.InvalidPlaceForAddingHooks

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$form_id = isset( $_POST['wpforms']['id'] ) ? absint( $_POST['wpforms']['id'] ) : 0;

		if ( empty( $form_id ) ) {
			wp_send_json_error();
		}

		if ( isset( $_POST['wpforms']['post_id'] ) ) {
			// We don't have a global $post when processing ajax requests.
			// Therefore, it's necessary to set a global $post manually for compatibility with functions used in smart tag processing.
			global $post;
			// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			$post = WP_Post::get_instance( absint( $_POST['wpforms']['post_id'] ) );
		}
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		add_filter( 'wp_redirect', [ $this, 'ajax_process_redirect' ], 999 );

		// phpcs:ignore WPForms.Comments.PHPDocHooks.RequiredHookDocumentation, WPForms.PHP.ValidateHooks.InvalidHookName
		do_action( 'wpforms_ajax_submit_before_processing', $form_id );

		// If redirect happens in listen(), ajax_process_redirect() gets executed because of the filter on `wp_redirect`.
		// The code, that is below listen(), runs only if no redirect happened.
		$this->listen();

		$form_data = $this->form_data;

		if ( empty( $form_data ) ) {
			$form_data = wpforms()->obj( 'form' )->get( $form_id, [ 'content_only' => true ] );

			// phpcs:ignore WPForms.Comments.PHPDocHooks.RequiredHookDocumentation, WPForms.PHP.ValidateHooks.InvalidHookName
			$form_data = apply_filters( 'wpforms_frontend_form_data', $form_data );
		}

		if ( ! empty( $this->errors[ $form_id ] ) ) {
			$this->ajax_process_errors( $form_id, $form_data );
			wp_send_json_error();
		}

		ob_start();

		wpforms()->obj( 'frontend' )->confirmation( $form_data );

		// phpcs:ignore WPForms.Comments.PHPDocHooks.RequiredHookDocumentation, WPForms.PHP.ValidateHooks.InvalidHookName
		$response = apply_filters( 'wpforms_ajax_submit_success_response', [ 'confirmation' => ob_get_clean() ], $form_id, $form_data );

		// phpcs:ignore WPForms.Comments.PHPDocHooks.RequiredHookDocumentation, WPForms.PHP.ValidateHooks.InvalidHookName
		do_action( 'wpforms_ajax_submit_completed', $form_id, $response );

		wp_send_json_success( $response );
	}


/** Function field_new() called by wp_ajax hooks: {'wpforms_new_field_{$this->type}'} **/
/** Parameters found in function field_new(): {"post": ["id", "type", "defaults"]} **/
function field_new(): void {

		// Run a security check.
		if ( ! check_ajax_referer( 'wpforms-builder', 'nonce', false ) ) {
			wp_send_json_error( esc_html__( 'Your session expired. Please reload the builder.', 'wpforms-lite' ) );
		}

		// Check for permissions.
		if ( ! wpforms_current_user_can( 'edit_forms' ) ) {
			wp_send_json_error( esc_html__( 'You are not allowed to perform this action.', 'wpforms-lite' ) );
		}

		// Check for form ID.
		if ( empty( $_POST['id'] ) ) {
			wp_send_json_error( esc_html__( 'No form ID found', 'wpforms-lite' ) );
		}

		// Check for a field type to add.
		if ( empty( $_POST['type'] ) ) {
			wp_send_json_error( esc_html__( 'No field type found', 'wpforms-lite' ) );
		}

		// Grab field data.
		$field_args = ! empty( $_POST['defaults'] ) && is_array( $_POST['defaults'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['defaults'] ) ) : [];
		$field_type = sanitize_key( $_POST['type'] );
		$form_obj   = wpforms()->obj( 'form' );
		$field_id   = $form_obj ? $form_obj->next_field_id( absint( $_POST['id'] ) ) : false;
		$field      = [
			'id'          => $field_id,
			'type'        => $field_type,
			'label'       => $this->name,
			'description' => '',
		];
		$field      = wp_parse_args( $field_args, $field );

		/**
		 * Allow the default field settings to be filtered.
		 *
		 * @since 1.0.8
		 *
		 * @param array $field Default field settings.
		 */
		$field = (array) apply_filters( 'wpforms_field_new_default', $field );

		/**
		 * Filter whether the field should be required by default.
		 *
		 * @since 1.0.8
		 *
		 * @param string $field_required Required attribute value.
		 * @param array  $field          Field settings.
		 */
		$field_required = (string) apply_filters( 'wpforms_field_new_required', '', $field );

		// Field types that default to the required.
		if ( ! empty( $field_required ) ) {
			$field['required'] = '1';
		}

		$preview = $this->get_new_field_preview_html( $field );
		$options = $this->get_new_field_options_html( $field );

		// Prepare to return compiled results.
		wp_send_json_success(
			[
				'form_id' => absint( $_POST['id'] ),
				'field'   => $field,
				'preview' => $preview,
				'options' => $options,
			]
		);
	}


/** Function ajax_save_favorites() called by wp_ajax hooks: {'wpforms_templates_favorite'} **/
/** No params detected :-/ **/


/** Function connect() called by wp_ajax hooks: {'wpforms_square_create_webhook'} **/
/** No params detected :-/ **/


/** Function save_internal_information_checkbox() called by wp_ajax hooks: {'wpforms_builder_save_internal_information_checkbox'} **/
/** Parameters found in function save_internal_information_checkbox(): {"post": ["formId", "name", "checked"]} **/
function save_internal_information_checkbox(): void {

		$form_id = isset( $_POST['formId'] ) ? absint( $_POST['formId'] ) : 0;

		// Run several checks: required items, security, permissions.
		if (
			! $form_id ||
			! isset( $_POST['name'], $_POST['checked'] ) ||
			! check_ajax_referer( 'wpforms-builder', 'nonce', false ) ||
			! wpforms_current_user_can( 'edit_forms', $form_id )
		) {
			wp_send_json_error();
		}

		$checked   = (int) $_POST['checked'];
		$name      = sanitize_text_field( wp_unslash( $_POST['name'] ) );
		$post_meta = get_post_meta( $form_id, self::CHECKBOX_META_KEY, true );
		$post_meta = ! empty( $post_meta ) ? (array) $post_meta : [];

		if ( $checked ) {
			$post_meta[ $name ] = $checked;
		} else {
			unset( $post_meta[ $name ] );
		}

		update_post_meta( $form_id, self::CHECKBOX_META_KEY, $post_meta );

		wp_send_json_success();
	}


/** Function ajax_handle_auth() called by wp_ajax hooks: {'wpforms_constant_contact_popup_auth'} **/
/** No params detected :-/ **/


/** Function save_tags() called by wp_ajax hooks: {'wpforms_admin_forms_overview_save_tags'} **/
/** No params detected :-/ **/


/** Function handle_snapshot_ajax() called by wp_ajax hooks: {'wpforms_analytics_snapshot', 'nopriv_wpforms_analytics_snapshot'} **/
/** No params detected :-/ **/


/** Function import_form() called by wp_ajax hooks: {'wpforms_import_form_{$this->slug}'} **/
/** Parameters found in function import_form(): {"post": ["analyze", "form_id"]} **/
function import_form() { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded, Generic.Metrics.NestingLevel.MaxExceeded

		// Run a security check.
		check_ajax_referer( 'wpforms-admin', 'nonce' );

		// Check for permissions.
		if ( ! wpforms_current_user_can( 'create_forms' ) ) {
			wp_send_json_error();
		}

		// Define some basic information.
		$analyze  = isset( $_POST['analyze'] );
		$cf7_id   = ! empty( $_POST['form_id'] ) ? (int) $_POST['form_id'] : 0;
		$cf7_form = $this->get_form( $cf7_id );

		if ( ! $cf7_form ) {
			wp_send_json_error(
				[
					'error' => true,
					'name'  => esc_html__( 'Unknown Form', 'wpforms-lite' ),
					'msg'   => esc_html__( 'The form you are trying to import does not exist.', 'wpforms-lite' ),
				]
			);
		}

		$cf7_form_name      = $cf7_form->title();
		$cf7_fields         = $cf7_form->scan_form_tags();
		$cf7_properties     = $cf7_form->get_properties();
		$cf7_recaptcha      = false;
		$fields_pro_plain   = [ 'url', 'tel', 'date' ];
		$fields_pro_omit    = [ 'file' ];
		$fields_unsupported = [ 'quiz', 'hidden' ];
		$upgrade_plain      = [];
		$upgrade_omit       = [];
		$unsupported        = [];
		$form               = [
			'id'       => '',
			'field_id' => '',
			'fields'   => [],
			'settings' => [
				'form_title'             => $cf7_form_name,
				'form_desc'              => '',
				'submit_text'            => esc_html__( 'Submit', 'wpforms-lite' ),
				'submit_text_processing' => esc_html__( 'Sending', 'wpforms-lite' ),
				'antispam_v3'            => '1',
				'notification_enable'    => '1',
				'notifications'          => [
					1 => [
						'notification_name' => esc_html__( 'Notification 1', 'wpforms-lite' ),
						'email'             => '{admin_email}',
						'subject'           => sprintf( /* translators: %s - form name. */
							esc_html__( 'New Entry: %s', 'wpforms-lite' ),
							$cf7_form_name
						),
						'sender_name'       => get_bloginfo( 'name' ),
						'sender_address'    => '{admin_email}',
						'replyto'           => '',
						'message'           => '{all_fields}',
					],
				],
				'confirmations'          => [
					1 => [
						'type'           => 'message',
						'message'        => esc_html__( 'Thanks for contacting us! We will be in touch with you shortly.', 'wpforms-lite' ),
						'message_scroll' => '1',
					],
				],
				'import_form_id'         => $cf7_id,
			],
		];

		// If the form does not contain fields, bail.
		if ( empty( $cf7_fields ) ) {
			wp_send_json_success(
				[
					'error' => true,
					'name'  => sanitize_text_field( $cf7_form_name ),
					'msg'   => esc_html__( 'No form fields found.', 'wpforms-lite' ),
				]
			);
		}

		// Convert fields.
		foreach ( $cf7_fields as $cf7_field ) {
			if ( ! $cf7_field instanceof WPCF7_FormTag ) {
				continue;
			}

			// Try to determine field label to use.
			$label = $this->get_field_label( $cf7_properties['form'], $cf7_field->type, $cf7_field->name );

			// Next, check if the field is unsupported.
			// If supported, make note and then continue to the next field.
			if ( in_array( $cf7_field->basetype, $fields_unsupported, true ) ) {
				$unsupported[] = $label;

				continue;
			}

			// Now check if this installation is Lite.
			// If it is Lite, and it's a field type not included, make a note then continue to the next field.
			if ( in_array( $cf7_field->basetype, $fields_pro_plain, true ) && ! wpforms()->is_pro() ) {
				$upgrade_plain[] = $label;
			}
			if ( in_array( $cf7_field->basetype, $fields_pro_omit, true ) && ! wpforms()->is_pro() ) {
				$upgrade_omit[] = $label;

				continue;
			}

			// Determine the next field ID to assign.
			if ( empty( $form['fields'] ) ) {
				$field_id = 1;
			} else {
				$field_id = (int) max( array_keys( $form['fields'] ) ) + 1;
			}

			switch ( $cf7_field->basetype ) {
				// Plain text, email, URL, number, and textarea fields.
				case 'text':
				case 'email':
				case 'url':
				case 'number':
				case 'textarea':
					$type = $cf7_field->basetype;

					if ( $type === 'url' && ! wpforms()->is_pro() ) {
						$type = 'text';
					}

					$form['fields'][ $field_id ] = [
						'id'            => $field_id,
						'type'          => $type,
						'label'         => $label,
						'size'          => 'medium',
						'required'      => $cf7_field->is_required() ? '1' : '',
						'placeholder'   => $this->get_field_placeholder_default( $cf7_field ),
						'default_value' => $this->get_field_placeholder_default( $cf7_field, 'default' ),
						'cf7_name'      => $cf7_field->name,
					];
					break;

				// Phone number field.
				case 'tel':
					$form['fields'][ $field_id ] = [
						'id'            => $field_id,
						'type'          => 'phone',
						'label'         => $label,
						'format'        => 'international',
						'size'          => 'medium',
						'required'      => $cf7_field->is_required() ? '1' : '',
						'placeholder'   => $this->get_field_placeholder_default( $cf7_field ),
						'default_value' => $this->get_field_placeholder_default( $cf7_field, 'default' ),
						'cf7_name'      => $cf7_field->name,
					];
					break;

				// Date field.
				case 'date':
					$type = wpforms()->is_pro() ? 'date-time' : 'text';

					$form['fields'][ $field_id ] = [
						'id'               => $field_id,
						'type'             => $type,
						'label'            => $label,
						'format'           => 'date',
						'size'             => 'medium',
						'required'         => $cf7_field->is_required() ? '1' : '',
						'date_placeholder' => '',
						'date_format'      => 'm/d/Y',
						'date_type'        => 'datepicker',
						'time_format'      => 'g:i A',
						'time_interval'    => 30,
						'cf7_name'         => $cf7_field->name,
					];
					break;

				// Select, radio, and checkbox fields.
				case 'select':
				case 'radio':
				case 'checkbox':
					$choices = [];
					$options = (array) $cf7_field->labels;

					foreach ( $options as $option ) {
						$choices[] = [
							'label' => $option,
							'value' => '',
						];
					}

					$form['fields'][ $field_id ] = [
						'id'       => $field_id,
						'type'     => $cf7_field->basetype,
						'label'    => $label,
						'choices'  => $choices,
						'size'     => 'medium',
						'required' => $cf7_field->is_required() ? '1' : '',
						'cf7_name' => $cf7_field->name,
					];

					if (
						$cf7_field->basetype === 'select' &&
						$cf7_field->has_option( 'include_blank' )
					) {
						$form['fields'][ $field_id ]['placeholder'] = '---';
					}
					break;

				// File upload field.
				case 'file':
					$extensions = '';
					$max_size   = '';
					$file_types = $cf7_field->get_option( 'filetypes' );
					$limit      = $cf7_field->get_option( 'limit' );

					if ( ! empty( $file_types[0] ) ) {
						$extensions = implode( ',', explode( '|', strtolower( preg_replace( '/[^A-Za-z0-9|]/', '', strtolower( $file_types[0] ) ) ) ) );
					}

					if ( ! empty( $limit[0] ) ) {
						$limit = $limit[0];
						$mb    = ( strpos( $limit, 'm' ) !== false );
						$kb    = ( strpos( $limit, 'kb' ) !== false );
						$limit = (int) preg_replace( '/[^0-9]/', '', $limit );

						if ( $mb ) {
							$max_size = $limit;
						} elseif ( $kb ) {
							$max_size = round( $limit / 1024, 1 );
						} else {
							$max_size = round( $limit / 1048576, 1 );
						}
					}

					$form['fields'][ $field_id ] = [
						'id'         => $field_id,
						'type'       => 'file-upload',
						'label'      => $label,
						'size'       => 'medium',
						'extensions' => $extensions,
						'max_size'   => $max_size,
						'required'   => $cf7_field->is_required() ? '1' : '',
						'cf7_name'   => $cf7_field->name,
					];
					break;

				// Acceptance field.
				case 'acceptance':
					$form['fields'][ $field_id ] = [
						'id'         => $field_id,
						'type'       => 'checkbox',
						'label'      => esc_html__( 'Acceptance Field', 'wpforms-lite' ),
						'choices'    => [
							1 => [
								'label' => $label,
								'value' => '',
							],
						],
						'size'       => 'medium',
						'required'   => '1',
						'label_hide' => '1',
						'cf7_name'   => $cf7_field->name,
					];
					break;

				// ReCAPTCHA field.
				case 'recaptcha':
					$cf7_recaptcha = true;
			}
		}

		// If we are only analyzing the form, we can stop here and return the
		// details about this form.
		if ( $analyze ) {
			wp_send_json_success(
				[
					'name'          => $cf7_form_name,
					'upgrade_plain' => $upgrade_plain,
					'upgrade_omit'  => $upgrade_omit,
				]
			);
		}

		// Settings.
		// Confirmation message.
		if ( ! empty( $cf7_properties['messages']['mail_sent_ok'] ) ) {
			$form['settings']['confirmation_message'] = $cf7_properties['messages']['mail_sent_ok'];
		}
		// ReCAPTCHA.
		if ( $cf7_recaptcha ) {
			// If the user has already defined v2 reCAPTCHA keys in the WPForms
			// settings, use those.
			$site_key   = wpforms_setting( 'recaptcha-site-key', '' );
			$secret_key = wpforms_setting( 'recaptcha-secret-key', '' );
			$type       = wpforms_setting( 'recaptcha-type', 'v2' );

			// Try to abstract keys from CF7.
			if ( empty( $site_key ) || empty( $secret_key ) ) {
				$cf7_settings = get_option( 'wpcf7' );

				if (
					! empty( $cf7_settings['recaptcha'] ) &&
					is_array( $cf7_settings['recaptcha'] )
				) {
					foreach ( $cf7_settings['recaptcha'] as $key => $val ) {
						if ( ! empty( $key ) && ! empty( $val ) ) {
							$site_key   = $key;
							$secret_key = $val;
						}
					}
					$wpforms_settings                         = get_option( 'wpforms_settings', [] );
					$wpforms_settings['recaptcha-site-key']   = $site_key;
					$wpforms_settings['recaptcha-secret-key'] = $secret_key;

					update_option( 'wpforms_settings', $wpforms_settings );
				}
			}

			// Don't enable reCAPTCHA if user had configured invisible reCAPTCHA.
			if (
				$type === 'v2' &&
				! empty( $site_key ) &&
				! empty( $secret_key )
			) {
				$form['settings']['recaptcha'] = '1';
			}
		}

		// Setup email notifications.
		if ( ! empty( $cf7_properties['mail']['subject'] ) ) {
			$form['settings']['notifications'][1]['subject'] = $this->get_smarttags( $cf7_properties['mail']['subject'], $form['fields'] );
		}

		if ( ! empty( $cf7_properties['mail']['recipient'] ) ) {
			$form['settings']['notifications'][1]['email'] = $this->get_smarttags( $cf7_properties['mail']['recipient'], $form['fields'] );
		}

		if ( ! empty( $cf7_properties['mail']['body'] ) ) {
			$form['settings']['notifications'][1]['message'] = $this->get_smarttags( $cf7_properties['mail']['body'], $form['fields'] );
		}

		if ( ! empty( $cf7_properties['mail']['additional_headers'] ) ) {
			$form['settings']['notifications'][1]['replyto'] = $this->get_replyto( $cf7_properties['mail']['additional_headers'], $form['fields'] );
		}

		if ( ! empty( $cf7_properties['mail']['sender'] ) ) {
			$sender = $this->get_sender_details( $cf7_properties['mail']['sender'], $form['fields'] );

			if ( $sender ) {
				$form['settings']['notifications'][1]['sender_name']    = $sender['name'];
				$form['settings']['notifications'][1]['sender_address'] = $sender['address'];
			}
		}

		if ( ! empty( $cf7_properties['mail_2'] ) && (int) $cf7_properties['mail_2']['active'] === 1 ) {
			// Check if a secondary notification is enabled, if so set defaults
			// and set it up.
			$form['settings']['notifications'][2] = [
				'notification_name' => esc_html__( 'Notification 2', 'wpforms-lite' ),
				'email'             => '{admin_email}',
				'subject'           => sprintf( /* translators: %s - form name. */
					esc_html__( 'New Entry: %s', 'wpforms-lite' ),
					$cf7_form_name
				),
				'sender_name'       => get_bloginfo( 'name' ),
				'sender_address'    => '{admin_email}',
				'replyto'           => '',
				'message'           => '{all_fields}',
			];

			if ( ! empty( $cf7_properties['mail_2']['subject'] ) ) {
				$form['settings']['notifications'][2]['subject'] = $this->get_smarttags( $cf7_properties['mail_2']['subject'], $form['fields'] );
			}

			if ( ! empty( $cf7_properties['mail_2']['recipient'] ) ) {
				$form['settings']['notifications'][2]['email'] = $this->get_smarttags( $cf7_properties['mail_2']['recipient'], $form['fields'] );
			}

			if ( ! empty( $cf7_properties['mail_2']['body'] ) ) {
				$form['settings']['notifications'][2]['message'] = $this->get_smarttags( $cf7_properties['mail_2']['body'], $form['fields'] );
			}

			if ( ! empty( $cf7_properties['mail_2']['additional_headers'] ) ) {
				$form['settings']['notifications'][2]['replyto'] = $this->get_replyto( $cf7_properties['mail_2']['additional_headers'], $form['fields'] );
			}

			if ( ! empty( $cf7_properties['mail_2']['sender'] ) ) {
				$sender = $this->get_sender_details( $cf7_properties['mail_2']['sender'], $form['fields'] );

				if ( $sender ) {
					$form['settings']['notifications'][2]['sender_name']    = $sender['name'];
					$form['settings']['notifications'][2]['sender_address'] = $sender['address'];
				}
			}
		}

		$this->add_form( $form, $unsupported, $upgrade_plain, $upgrade_omit );
	}


/** Function get_choices() called by wp_ajax hooks: {'wpforms_get_ai_choices'} **/
/** No params detected :-/ **/


/** Function maybe_watch_install() called by wp_ajax hooks: {'wpforms_install_addon'} **/
/** Parameters found in function maybe_watch_install(): {"post": ["plugin"]} **/
function maybe_watch_install() {

		if ( ! check_ajax_referer( 'wpforms-admin', 'nonce', false ) || ! wpforms_can_install( 'plugin' ) ) {
			return;
		}

		$plugin_url = isset( $_POST['plugin'] ) ? esc_url_raw( wp_unslash( $_POST['plugin'] ) ) : '';

		$this->is_own_install = $plugin_url === Helper::INSTALL_ZIP_URL;
	}


/** Function ajax_payments_cancel() called by wp_ajax hooks: {'wpforms_paypal_commerce_payments_cancel', 'wpforms_square_payments_cancel'} **/
/** No params detected :-/ **/


/** Function create_subscription_order_ajax() called by wp_ajax hooks: {'nopriv_wpforms_paypal_commerce_create_subscription', 'wpforms_paypal_commerce_create_subscription'} **/
/** Parameters found in function create_subscription_order_ajax(): {"post": ["nonce", "wpforms", "total"]} **/
function create_subscription_order_ajax(): void {

		if (
			! isset( $_POST['nonce'] ) ||
			! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'wpforms-paypal-commerce-create-subscription' )
		) {
			wp_send_json_error( esc_html__( 'You are not allowed to perform this action.', 'wpforms-lite' ) );
		}

		$this->form_id = isset( $_POST['wpforms']['id'] ) ? absint( $_POST['wpforms']['id'] ) : 0;

		if ( empty( $this->form_id ) || ! isset( $_POST['wpforms'], $_POST['total'] ) ) {
			wp_send_json_error( esc_html__( 'Something went wrong. Please contact site administrator.', 'wpforms-lite' ) );
		}

		$this->connection  = Connection::get();
		$this->form_data   = wpforms()->obj( 'form' )->get( $this->form_id, [ 'content_only' => true ] );
		$subscription_data = $this->prepare_subscription_order_data();

		if ( ! $this->is_form_ok() ) {
			wp_send_json_error( $this->errors );
		}

		$error_title = esc_html__( 'This subscription cannot be created because there was an error with the create subscription API call.', 'wpforms-lite' );
		$api         = PayPalCommerce::get_api( $this->connection );

		if ( is_null( $api ) ) {
			wp_send_json_error( $error_title );
		}

		$subscription_response = $api->create_subscription( $subscription_data );

		if ( $subscription_response->has_errors() ) {
			$subscription_response_message = $subscription_response->get_response_message();
			$error_description             = $this->get_order_error_description( $subscription_response_message );

			$this->log_errors( $error_title, $subscription_response_message );

			wp_send_json_error( $error_description ?? $error_title );
		}

		$subscription = $subscription_response->get_body();

		wp_send_json_success( $subscription );
	}


/** Function refresh_connection_manual() called by wp_ajax hooks: {'wpforms_square_refresh_connection'} **/
/** Parameters found in function refresh_connection_manual(): {"post": ["mode"]} **/
function refresh_connection_manual() {

		// Security and permissions check.
		if (
			! check_ajax_referer( 'wpforms-admin', 'nonce', false ) ||
			! wpforms_current_user_can()
		) {
			wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'wpforms-lite' ) );
		}

		$error_general = esc_html__( 'Something went wrong while performing a refresh tokens request', 'wpforms-lite' );

		// Required data check.
		if ( empty( $_POST['mode'] ) ) {
			wp_send_json_error( $error_general );
		}

		$mode       = sanitize_key( $_POST['mode'] );
		$connection = Connection::get( $mode );

		// Connection check.
		if ( ! $connection || ! $connection->is_configured() ) {
			wp_send_json_error( $error_general );
		}

		// Try to refresh connection.
		$connection = $this->try_refresh_connection( $connection );

		if ( is_wp_error( $connection ) ) {
			$error_specific = $connection->get_error_message();
			$error_message  = empty( $error_specific ) ? $error_general : $error_general . ': ' . $error_specific;

			wp_send_json_error( $error_message );
		}

		$this->prepare_locations( $mode );

		wp_send_json_success();
	}


/** Function save_challenge_option_ajax() called by wp_ajax hooks: {'wpforms_challenge_save_option'} **/
/** Parameters found in function save_challenge_option_ajax(): {"post": ["option_data"]} **/
function save_challenge_option_ajax() {

		check_admin_referer( 'wpforms_challenge_ajax_nonce' );

		if ( empty( $_POST['option_data'] ) ) {
			wp_send_json_error();
		}

		$schema = $this->get_challenge_option_schema();
		$query  = [];

		foreach ( $schema as $key => $value ) {
			if ( isset( $_POST['option_data'][ $key ] ) ) {
				$query[ $key ] = sanitize_text_field( wp_unslash( $_POST['option_data'][ $key ] ) );
			}
		}

		if ( empty( $query ) ) {
			wp_send_json_error();
		}

		if ( ! empty( $query['status'] ) && $query['status'] === 'started' ) {
			$query['started_date_gmt'] = current_time( 'mysql', true );
		}

		if ( ! empty( $query['status'] ) && in_array( $query['status'], [ 'completed', 'canceled', 'skipped' ], true ) ) {
			$query['finished_date_gmt'] = current_time( 'mysql', true );
		}

		if ( ! empty( $query['status'] ) && $query['status'] === 'skipped' ) {
			$query['started_date_gmt']  = current_time( 'mysql', true );
			$query['finished_date_gmt'] = $query['started_date_gmt'];
		}

		$this->set_challenge_option( $query );

		wp_send_json_success();
	}


/** Function ajax_get_templates_batch() called by wp_ajax hooks: {'wpforms_get_templates_batch'} **/
/** No params detected :-/ **/


/** Function wpforms_install_addon() called by wp_ajax hooks: {'wpforms_install_addon'} **/
/** Parameters found in function wpforms_install_addon(): {"post": ["type", "plugin", "args"]} **/
function wpforms_install_addon() { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

	// Run a security check.
	check_ajax_referer( 'wpforms-admin', 'nonce' );

	$generic_error = esc_html__( 'There was an error while performing your request.', 'wpforms-lite' );
	$type          = ! empty( $_POST['type'] ) ? sanitize_key( $_POST['type'] ) : 'addon';

	// Check if new installations are allowed.
	if ( ! wpforms_can_install( $type ) ) {
		wp_send_json_error( $generic_error );
	}

	$error = $type === 'plugin'
		? esc_html__( 'Could not install the plugin. Please download and install it manually.', 'wpforms-lite' )
		: sprintf(
			wp_kses( /* translators: %1$s - addon download URL, %2$s - link to a manual installation guide, %3$s - link to contact support. */
				__( 'Could not install the addon. Please <a href="%1$s" target="_blank" rel="noopener noreferrer">download it from wpforms.com</a> and <a href="%2$s" target="_blank" rel="noopener noreferrer">install it manually</a>, or <a href="%3$s" target="_blank" rel="noopener noreferrer">contact support</a> for assistance.', 'wpforms-lite' ),
				[
					'a' => [
						'href'   => true,
						'target' => true,
						'rel'    => true,
					],
				]
			),
			esc_url( wpforms_utm_link( 'https://wpforms.com/account/licenses/', 'Licenses', 'Addons Error' ) ),
			esc_url( wpforms_utm_link( 'https://wpforms.com/docs/how-to-manually-install-addons-in-wpforms/', 'Addons Doc', 'Addons Error' ) ),
			esc_url( wpforms_utm_link( 'https://wpforms.com/contact/', 'Contact', 'Addons Error' ) )
		);

	$plugin_url = ! empty( $_POST['plugin'] ) ? esc_url_raw( wp_unslash( $_POST['plugin'] ) ) : '';

	if ( empty( $plugin_url ) ) {
		wp_send_json_error( $error );
	}

	$args_str = ! empty( $_POST['args'] ) ? sanitize_text_field( wp_unslash( $_POST['args'] ) ) : '';
	$args     = json_decode( $args_str, true ) ?? [];

	// Set the current screen to avoid undefined notices.
	set_current_screen( 'wpforms_page_wpforms-settings' );

	// Prepare variables.
	$url = esc_url_raw(
		add_query_arg(
			[
				'page' => 'wpforms-addons',
			],
			admin_url( 'admin.php' )
		)
	);

	ob_start();
	$creds = request_filesystem_credentials( $url, '', false, false );

	// Hide the filesystem credentials form.
	ob_end_clean();

	// Check for file system permissions.
	if ( $creds === false ) {
		wp_send_json_error( $error );
	}

	if ( ! WP_Filesystem( $creds ) ) {
		wp_send_json_error( $error );
	}

	/*
	 * We do not need any extra credentials if we have gotten this far, so let's install the plugin.
	 */

	// Do not allow WordPress to search/download translations, as this will break JS output.
	remove_action( 'upgrader_process_complete', [ 'Language_Pack_Upgrader', 'async_upgrade' ], 20 );

	// Create the plugin upgrader with our custom skin.
	$installer = new WPForms\Helpers\PluginSilentUpgrader( new WP_Ajax_Upgrader_Skin() );

	// Error check.
	if ( ! method_exists( $installer, 'install' ) ) {
		wp_send_json_error( $error );
	}

	$installer->install( $plugin_url, $args );

	// Flush the cache and return the newly installed plugin basename.
	wp_cache_flush();

	$plugin_basename = $installer->plugin_info();

	if ( empty( $plugin_basename ) ) {
		wp_send_json_error( $error );
	}

	$result = [
		'msg'          => $generic_error,
		'is_activated' => false,
		'basename'     => $plugin_basename,
	];

	// Check for permissions.
	if ( ! current_user_can( 'activate_plugins' ) ) {
		$result['msg'] = $type === 'plugin' ? esc_html__( 'Plugin installed.', 'wpforms-lite' ) : esc_html__( 'Addon installed.', 'wpforms-lite' );

		wp_send_json_success( $result );
	}

	// Activate the plugin silently.
	$activated = activate_plugin( $plugin_basename );

	if ( ! is_wp_error( $activated ) ) {

		/**
		 * Fire after the plugin activating via the WPForms installer.
		 *
		 * @since 1.7.0
		 *
		 * @param string $plugin_basename Path to the plugin file relative to the plugins' directory.
		 */
		do_action( 'wpforms_plugin_activated', $plugin_basename );

		$result['is_activated'] = true;
		$result['msg']          = $type === 'plugin' ? esc_html__( 'Plugin installed & activated.', 'wpforms-lite' ) : esc_html__( 'Addon installed & activated.', 'wpforms-lite' );

		wp_send_json_success( $result );
	}

	// Fallback error just in case.
	wp_send_json_error( $result );
}


/** Function ajax_start_migration() called by wp_ajax hooks: {'wpforms_constant_contact_migration_prompt'} **/
/** No params detected :-/ **/


/** Function get_field_preview() called by wp_ajax hooks: {'wpforms_get_ai_form_field_preview'} **/
/** No params detected :-/ **/


/** Function load_panel_content() called by wp_ajax hooks: {'wpforms_builder_load_panel'} **/
/** No params detected :-/ **/


/** Function ajax_connect() called by wp_ajax hooks: {'wpforms_settings_provider_add_{$this->core->slug}'} **/
/** Parameters found in function ajax_connect(): {"post": ["data"]} **/
function ajax_connect() {

		// Run a security check.
		if ( ! check_ajax_referer( 'wpforms-admin', 'nonce', false ) ) {
			wp_send_json_error(
				[
					'error_msg' => esc_html__( 'Your session expired. Please reload the page.', 'wpforms-lite' ),
				]
			);
		}

		// Check for permissions.
		if ( ! wpforms_current_user_can() ) {
			wp_send_json_error(
				[
					'error_msg' => esc_html__( 'You do not have permissions.', 'wpforms-lite' ),
				]
			);
		}

		if ( empty( $_POST['data'] ) ) {
			wp_send_json_error(
				[
					'error_msg' => esc_html__( 'Missing required data in payload.', 'wpforms-lite' ),
				]
			);
		}
	}


/** Function subscription_processor_create_ajax() called by wp_ajax hooks: {'nopriv_wpforms_paypal_commerce_subscription_processor_create', 'wpforms_paypal_commerce_subscription_processor_create'} **/
/** Parameters found in function subscription_processor_create_ajax(): {"post": ["nonce", "wpforms", "total"]} **/
function subscription_processor_create_ajax(): void {

		if (
			! isset( $_POST['nonce'] ) ||
			! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'wpforms-paypal-commerce-create-subscription' )
		) {
			wp_send_json_error( esc_html__( 'You are not allowed to perform this action.', 'wpforms-lite' ) );
		}

		$this->form_id = isset( $_POST['wpforms']['id'] ) ? absint( $_POST['wpforms']['id'] ) : 0;

		if ( empty( $this->form_id ) || ! isset( $_POST['wpforms'], $_POST['total'] ) ) {
			wp_send_json_error( esc_html__( 'Something went wrong. Please contact site administrator.', 'wpforms-lite' ) );
		}

		$this->connection = Connection::get();
		$this->form_data  = wpforms()->obj( 'form' )->get( $this->form_id, [ 'content_only' => true ] );
		$order_data       = $this->prepare_order_data();

		if ( ! $this->is_form_ok() ) {
			wp_send_json_error( $this->errors );
		}

		$error_title = esc_html__( 'This subscription cannot be created because there was an error with the create subscription API call.', 'wpforms-lite' );

		$api = PayPalCommerce::get_api( $this->connection );

		if ( is_null( $api ) ) {
			wp_send_json_error( $error_title );
		}

		$order_response = $api->subscription_processor_create( $order_data );

		if ( $order_response->has_errors() ) {
			$order_response_message = $order_response->get_response_message();
			$error_description      = $this->get_order_error_description( $order_response_message );

			$this->log_errors( $error_title, $order_response_message );

			wp_send_json_error( $error_description ?? $error_title );
		}

		$order = $order_response->get_body();

		wp_send_json_success( $order );
	}


/** Function reset() called by wp_ajax hooks: {'wpforms_ai_form_editor_reset'} **/
/** No params detected :-/ **/


/** Function ajax_get_token() called by wp_ajax hooks: {'wpforms_get_token', 'nopriv_wpforms_get_token'} **/
/** No params detected :-/ **/


/** Function ajax_single_payment_refund() called by wp_ajax hooks: {'wpforms_stripe_payments_refund'} **/
/** Parameters found in function ajax_single_payment_refund(): {"post": ["payment_id"]} **/
function ajax_single_payment_refund() {

		if ( ! isset( $_POST['payment_id'] ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Missing payment ID.', 'wpforms-lite' ) ] );
		}

		if ( ! wpforms_current_user_can( wpforms_get_capability_manage_options() ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to perform this action.', 'wpforms-lite' ) ] );
		}

		$this->check_payment_collection_type();
		check_ajax_referer( 'wpforms-admin', 'nonce' );

		$payment_id = (int) $_POST['payment_id'];
		$payment_db = wpforms()->obj( 'payment' )->get( $payment_id );

		if ( empty( $payment_db ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Payment not found in the database.', 'wpforms-lite' ) ] );
		}

		$args = [
			'metadata' => [
				'refunded_by' => 'wpforms_dashboard',
			],
			'reason'   => 'requested_by_customer',
		];

		$refund = $this->payment_intents->refund_payment( $payment_db->transaction_id, $args );

		if ( ! $refund ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Refund failed.', 'wpforms-lite' ) ] );
		}

		if ( $payment_db->status === 'partrefund' ) {

			$already_refunded = wpforms()->obj( 'payment_meta' )->get_single( $payment_db->id, 'refunded_amount' );
			$amount_to_log    = $payment_db->total_amount - $already_refunded;
		} else {
			$amount_to_log = $payment_db->total_amount;
		}

		$log = sprintf(
			'Stripe payment refunded from the WPForms plugin interface. Refunded amount: %1$s.',
			wpforms_format_amount( wpforms_sanitize_amount( $amount_to_log ), true )
		);

		if ( UpdateHelpers::refund_payment( $payment_db, $payment_db->total_amount, $log ) ) {
			wp_send_json_success( [ 'message' => esc_html__( 'Refund successful.', 'wpforms-lite' ) ] );
		}

		wp_send_json_error( [ 'message' => esc_html__( 'Saving refund in the database failed.', 'wpforms-lite' ) ] );
	}


