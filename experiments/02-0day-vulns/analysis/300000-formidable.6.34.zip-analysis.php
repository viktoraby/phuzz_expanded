<?php
/***
*
*Found actions: 91
*Found functions:76
*Extracted functions:75
*Total parameter names extracted: 5
*Overview: {'FrmTransLiteSubscriptionsController::cancel_subscription': {'frm_trans_cancel'}, 'FrmSettingsController::page_search': {'frm_page_search'}, 'FrmFormsController::ajax_trash': {'frm_forms_trash'}, 'process_events': {'nopriv_frm_square_process_events', 'nopriv_frm_paypal_process_events', 'frm_paypal_process_events', 'frm_square_process_events'}, 'FrmStylesController::rename_style': {'frm_rename_style'}, 'FrmXMLController::install_template': {'frm_install_template'}, 'FrmFormsController::create_page_with_shortcode': {'frm_create_page_with_shortcode'}, 'FrmWelcomeTourController::ajax_mark_checklist_step_as_completed': {'frm_mark_checklist_step_as_completed'}, 'FrmPayPalLiteConnectHelper::handle_render_seller_status': {'frm_paypal_render_seller_status'}, 'FrmStrpLiteAuth::update_intent_ajax': {'frm_strp_amount', 'nopriv_frm_strp_amount'}, 'FrmFieldsController::import_options': {'frm_import_options'}, 'FrmPayPalLiteAppController::handle_disconnect': {'frm_paypal_disconnect'}, 'FrmPayPalLiteAppController::get_amount': {'nopriv_frm_paypal_get_amount', 'frm_paypal_get_amount'}, 'FrmStylesController::load_css': {'nopriv_frmpro_load_css', 'frmpro_load_css'}, 'FrmAddonsController::ajax_install_addon': {'frm_install_addon'}, 'FrmXMLController::csv': {'nopriv_frm_entries_csv', 'frm_entries_csv'}, 'FrmStrpLiteLinkController::handle_return_url': {'nopriv_frmstrplinkreturn', 'frmstrplinkreturn'}, 'FrmStrpLiteConnectHelper::verify': {'nopriv_frm_strp_lite_verify'}, 'FrmTransLitePaymentsController::refund_payment': {'frm_trans_refund'}, 'FrmAppController::small_screen_proceed': {'frm_small_screen_proceed'}, 'FrmAddonsController::ajax_deactivate_addon': {'frm_deactivate_addon'}, 'FrmPayPalLiteAppController::handle_oauth': {'frm_paypal_oauth'}, 'FrmSettingsController::settings_cta_dismiss': {'frm_lite_settings_upgrade'}, 'FrmEmailStylesController::ajax_preview': {'frm_email_style_preview'}, 'FrmFieldsController::destroy': {'frm_delete_field'}, 'FrmPayPalLiteActionsController::maybe_modify_new_action_post_data': {'frm_add_form_action'}, 'FrmAddon::activate': {'frm_addon_activate'}, 'FrmAddonsController::ajax_uninstall_addon': {'frm_uninstall_addon'}, 'FrmFormsController::rename_form': {'frm_rename_form'}, 'FrmInboxController::dismiss_message': {'frm_inbox_dismiss'}, 'process_connect_events': {'frm_strp_process_events', 'nopriv_frm_strp_process_events'}, 'FrmAddonsController::ajax_activate_addon': {'frm_activate_addon'}, 'FrmDashboardController::ajax_requests': {'dashboard_ajax_action'}, 'FrmPayPalLiteAppController::report_error': {'nopriv_frm_paypal_report_error', 'frm_paypal_report_error'}, 'FrmAppController::uninstall': {'frm_uninstall'}, 'FrmEmailStylesController::ajax_send_test_email': {'frm_send_test_email'}, 'FrmFormActionsController::add_form_action': {'frm_add_form_action'}, 'FrmFieldsController::create': {'frm_insert_field'}, 'FrmOnboardingWizardController::setup_usage_data': {'frm_onboarding_setup_usage_data'}, 'FrmFormsController::get_shortcode_opts': {'frm_get_shortcode_opts'}, 'FrmFormsController::get_page_dropdown': {'get_page_dropdown'}, 'FrmSquareLiteConnectHelper::verify': {'nopriv_frm_square_lite_verify'}, 'FrmFormMigratorsHelper::dismiss_migrator': {'frm_dismiss_migrator'}, 'FrmInstallPlugin::ajax_check_plugin_activation': {'frm_check_plugin_activation'}, 'ajax_check_plugin_status': {'frm_smtp_page_check_plugin_status'}, 'FrmXMLController::export_xml': {'frm_export_xml'}, 'FrmFieldsController::load_field': {'frm_load_field'}, 'FrmPayPalLiteConnectHelper::verify': {'nopriv_frm_paypal_lite_verify'}, 'FrmAppController::ajax_install': {'frm_install'}, 'FrmApplicationsController::get_applications_data': {'frm_get_applications_data'}, 'FrmFormTemplatesController::ajax_create_template': {'frm_create_template'}, 'FrmFieldsController::duplicate': {'frm_duplicate_field'}, 'FrmStylesController::reset_styling': {'frm_settings_reset'}, 'FrmSettingsController::load_settings_tab': {'frm_settings_tab'}, 'FrmUsageController::ajax_track_flows': {'frm_track_flows'}, 'FrmFormActionsController::fill_action': {'frm_form_action_fill'}, 'FrmInstallPlugin::ajax_install_plugin': {'frm_install_plugin'}, 'FrmAppController::deauthorize': {'frm_deauthorize'}, 'FrmFormsController::route': {'frm_save_form'}, 'FrmStylesController::load_saved_css': {'nopriv_frmpro_css', 'frmpro_css'}, 'FrmWelcomeTourController::ajax_dismiss_welcome_tour': {'frm_dismiss_welcome_tour'}, 'FrmPayPalLiteAppController::create_order': {'nopriv_frm_paypal_create_order', 'frm_paypal_create_order'}, 'FrmOnboardingWizardController::ajax_consent_tracking': {'frm_onboarding_consent_tracking'}, 'FrmSquareLiteAppController::handle_oauth': {'frm_square_oauth'}, 'FrmFormsController::preview': {'frm_forms_preview', 'nopriv_frm_forms_preview'}, 'FrmFormsController::get_email_html': {'frm_get_default_html'}, 'FrmSquareLiteAppController::handle_disconnect': {'frm_square_disconnect'}, 'FrmSalesApi::dismiss_banner': {'frm_sale_banner_dismiss'}, 'FrmAddon::deactivate': {'frm_addon_deactivate'}, 'FrmFormTemplatesController::ajax_get_free_templates': {'frm_get_free_templates'}, 'FrmPayPalLiteAppController::create_subscription': {'frm_paypal_create_subscription', 'nopriv_frm_paypal_create_subscription'}, 'FrmSquareLiteAppController::verify_buyer': {'frm_verify_buyer', 'nopriv_frm_verify_buyer'}, 'FrmAppController::dismiss_review': {'frm_dismiss_review'}, 'FrmStylesController::change_styling': {'frm_change_styling'}, 'FrmFormTemplatesController::ajax_add_or_remove_favorite': {'frm_add_or_remove_favorite_template'}, 'FrmFormsController::build_new_form': {'frm_install_form'}}
*
***/

/** Function FrmTransLiteSubscriptionsController::cancel_subscription() called by wp_ajax hooks: {'frm_trans_cancel'} **/
/** No params detected :-/ **/


/** Function FrmSettingsController::page_search() called by wp_ajax hooks: {'frm_page_search'} **/
/** No params detected :-/ **/


/** Function FrmFormsController::ajax_trash() called by wp_ajax hooks: {'frm_forms_trash'} **/
/** No params detected :-/ **/


/** Function process_events() called by wp_ajax hooks: {'nopriv_frm_square_process_events', 'nopriv_frm_paypal_process_events', 'frm_paypal_process_events', 'frm_square_process_events'} **/
/** No params detected :-/ **/


/** Function FrmStylesController::rename_style() called by wp_ajax hooks: {'frm_rename_style'} **/
/** No params detected :-/ **/


/** Function FrmXMLController::install_template() called by wp_ajax hooks: {'frm_install_template'} **/
/** No params detected :-/ **/


/** Function FrmFormsController::create_page_with_shortcode() called by wp_ajax hooks: {'frm_create_page_with_shortcode'} **/
/** No params detected :-/ **/


/** Function FrmWelcomeTourController::ajax_mark_checklist_step_as_completed() called by wp_ajax hooks: {'frm_mark_checklist_step_as_completed'} **/
/** No params detected :-/ **/


/** Function FrmPayPalLiteConnectHelper::handle_render_seller_status() called by wp_ajax hooks: {'frm_paypal_render_seller_status'} **/
/** No params detected :-/ **/


/** Function FrmStrpLiteAuth::update_intent_ajax() called by wp_ajax hooks: {'frm_strp_amount', 'nopriv_frm_strp_amount'} **/
/** Parameters found in function FrmStrpLiteAuth::update_intent_ajax(): {"post": ["form"]} **/
function update_intent_ajax() {
		check_ajax_referer( 'frm_strp_ajax', 'nonce' );

		if ( empty( $_POST['form'] ) ) {
			wp_die();
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$form = json_decode( stripslashes( $_POST['form'] ), true );

		if ( ! is_array( $form ) ) {
			wp_die();
		}

		self::format_form_data( $form );

		$form_id = absint( $form['form_id'] );
		$intents = $form[ 'frmintent' . $form_id ] ?? array();

		if ( ! $intents ) {
			wp_die();
		}

		if ( is_array( $intents ) ) {
			foreach ( $intents as $k => $intent ) {
				if ( is_array( $intent ) && isset( $intent[ $k ] ) ) {
					$intents[ $k ] = $intent[ $k ];
				}
			}
		} else {
			$intents = array( $intents );
		}

		self::update_intent_pricing( $form_id, $intents, $form );

		wp_die();
	}


/** Function FrmFieldsController::import_options() called by wp_ajax hooks: {'frm_import_options'} **/
/** No params detected :-/ **/


/** Function FrmPayPalLiteAppController::handle_disconnect() called by wp_ajax hooks: {'frm_paypal_disconnect'} **/
/** No params detected :-/ **/


/** Function FrmPayPalLiteAppController::get_amount() called by wp_ajax hooks: {'nopriv_frm_paypal_get_amount', 'frm_paypal_get_amount'} **/
/** No params detected :-/ **/


/** Function FrmStylesController::load_css() called by wp_ajax hooks: {'nopriv_frmpro_load_css', 'frmpro_load_css'} **/
/** No params detected :-/ **/


/** Function FrmAddonsController::ajax_install_addon() called by wp_ajax hooks: {'frm_install_addon'} **/
/** No params detected :-/ **/


/** Function FrmXMLController::csv() called by wp_ajax hooks: {'nopriv_frm_entries_csv', 'frm_entries_csv'} **/
/** Parameters found in function FrmXMLController::csv(): {"request": ["s"]} **/
function csv( $form_id = false, $search = '', $fid = '' ) {
		FrmAppHelper::permission_check( 'frm_view_entries' );

		if ( ! $form_id ) {
			$form_id = FrmAppHelper::get_param( 'form', '', 'get', 'sanitize_text_field' );
			$search  = FrmAppHelper::get_param( isset( $_REQUEST['s'] ) ? 's' : 'search', '', 'get', 'sanitize_text_field' );
			$fid     = FrmAppHelper::get_param( 'fid', '', 'get', 'sanitize_text_field' );
		}

		// Remove time limit to execute this function.
		if ( function_exists( 'set_time_limit' ) ) {
			set_time_limit( 0 );
		}

		$mem_limit = str_replace( 'M', '', ini_get( 'memory_limit' ) );

		if ( (int) $mem_limit < 256 ) {
			wp_raise_memory_limit();
		}

		global $wpdb;

		$form = FrmForm::getOne( $form_id );

		if ( ! $form ) {
			esc_html_e( 'Form not found.', 'formidable' );
			wp_die();
		}

		$form_id   = $form->id;
		$form_cols = self::get_fields_for_csv_export( $form_id, $form );
		$item_id   = FrmAppHelper::get_param( 'item_id', 0, 'get', 'sanitize_text_field' );

		if ( $item_id ) {
			$item_id = explode( ',', $item_id );
		}

		$query = array(
			'form_id' => $form_id,
		);

		if ( $item_id ) {
			$query['id'] = $item_id;
		}

		/**
		 * Allows the query to be changed for fetching the entry ids to include in the export
		 *
		 * $query is the array of options to be filtered. It includes form_id, and maybe id (array of entry ids),
		 * and the search query. This should return an array, but it can be handled as a string as well.
		 */
		$query = apply_filters( 'frm_csv_where', $query, compact( 'form_id', 'search', 'fid', 'item_id' ) );

		$entry_ids = FrmDb::get_col( $wpdb->prefix . 'frm_items it', $query );
		unset( $query );

		if ( $entry_ids ) {
			FrmCSVExportHelper::generate_csv( compact( 'form', 'entry_ids', 'form_cols' ) );
		} else {
			esc_html_e( 'There are no entries for that form.', 'formidable' );
		}

		wp_die();
	}


/** Function FrmStrpLiteLinkController::handle_return_url() called by wp_ajax hooks: {'nopriv_frmstrplinkreturn', 'frmstrplinkreturn'} **/
/** No params detected :-/ **/


/** Function FrmStrpLiteConnectHelper::verify() called by wp_ajax hooks: {'nopriv_frm_strp_lite_verify'} **/
/** No params detected :-/ **/


/** Function FrmTransLitePaymentsController::refund_payment() called by wp_ajax hooks: {'frm_trans_refund'} **/
/** No params detected :-/ **/


/** Function FrmAppController::small_screen_proceed() called by wp_ajax hooks: {'frm_small_screen_proceed'} **/
/** No params detected :-/ **/


/** Function FrmAddonsController::ajax_deactivate_addon() called by wp_ajax hooks: {'frm_deactivate_addon'} **/
/** No params detected :-/ **/


/** Function FrmPayPalLiteAppController::handle_oauth() called by wp_ajax hooks: {'frm_paypal_oauth'} **/
/** No params detected :-/ **/


/** Function FrmSettingsController::settings_cta_dismiss() called by wp_ajax hooks: {'frm_lite_settings_upgrade'} **/
/** No params detected :-/ **/


/** Function FrmEmailStylesController::ajax_preview() called by wp_ajax hooks: {'frm_email_style_preview'} **/
/** No params detected :-/ **/


/** Function FrmFieldsController::destroy() called by wp_ajax hooks: {'frm_delete_field'} **/
/** No params detected :-/ **/


/** Function FrmPayPalLiteActionsController::maybe_modify_new_action_post_data() called by wp_ajax hooks: {'frm_add_form_action'} **/
/** Parameters found in function FrmPayPalLiteActionsController::maybe_modify_new_action_post_data(): {"post": ["type"]} **/
function maybe_modify_new_action_post_data() {
		$action_type = FrmAppHelper::get_param( 'type', '', 'post', 'sanitize_text_field' );

		if ( 'paypal-legacy' === $action_type ) {
			$_POST['type'] = 'paypal';
			return;
		}

		if ( ! in_array( $action_type, array( 'paypal', 'stripe', 'square' ), true ) ) {
			return;
		}

		$_POST['type'] = 'payment';

		add_filter(
			'frm_form_payment_action_settings',
			/**
			 * @param WP_Post $action_settings
			 *
			 * @return WP_Post
			 */
			function ( $action_settings ) use ( $action_type ) {
				return self::set_gateway_as_default( $action_settings, $action_type );
			}
		);
	}


/** Function FrmAddon::activate() called by wp_ajax hooks: {'frm_addon_activate'} **/
/** No params detected :-/ **/


/** Function FrmAddonsController::ajax_uninstall_addon() called by wp_ajax hooks: {'frm_uninstall_addon'} **/
/** No params detected :-/ **/


/** Function FrmFormsController::rename_form() called by wp_ajax hooks: {'frm_rename_form'} **/
/** No params detected :-/ **/


/** Function FrmInboxController::dismiss_message() called by wp_ajax hooks: {'frm_inbox_dismiss'} **/
/** No params detected :-/ **/


/** Function process_connect_events() called by wp_ajax hooks: {'frm_strp_process_events', 'nopriv_frm_strp_process_events'} **/
/** No params detected :-/ **/


/** Function FrmAddonsController::ajax_activate_addon() called by wp_ajax hooks: {'frm_activate_addon'} **/
/** No params detected :-/ **/


/** Function FrmDashboardController::ajax_requests() called by wp_ajax hooks: {'dashboard_ajax_action'} **/
/** No params detected :-/ **/


/** Function FrmPayPalLiteAppController::report_error() called by wp_ajax hooks: {'nopriv_frm_paypal_report_error', 'frm_paypal_report_error'} **/
/** No params detected :-/ **/


/** Function FrmAppController::uninstall() called by wp_ajax hooks: {'frm_uninstall'} **/
/** No params detected :-/ **/


/** Function FrmEmailStylesController::ajax_send_test_email() called by wp_ajax hooks: {'frm_send_test_email'} **/
/** No params detected :-/ **/


/** Function FrmFormActionsController::add_form_action() called by wp_ajax hooks: {'frm_add_form_action'} **/
/** No params detected :-/ **/


/** Function FrmFieldsController::create() called by wp_ajax hooks: {'frm_insert_field'} **/
/** No params detected :-/ **/


/** Function FrmOnboardingWizardController::setup_usage_data() called by wp_ajax hooks: {'frm_onboarding_setup_usage_data'} **/
/** No params detected :-/ **/


/** Function FrmFormsController::get_shortcode_opts() called by wp_ajax hooks: {'frm_get_shortcode_opts'} **/
/** No params detected :-/ **/


/** Function FrmFormsController::get_page_dropdown() called by wp_ajax hooks: {'get_page_dropdown'} **/
/** No params detected :-/ **/


/** Function FrmSquareLiteConnectHelper::verify() called by wp_ajax hooks: {'nopriv_frm_square_lite_verify'} **/
/** No params detected :-/ **/


/** Function FrmFormMigratorsHelper::dismiss_migrator() called by wp_ajax hooks: {'frm_dismiss_migrator'} **/
/** No params detected :-/ **/


/** Function FrmInstallPlugin::ajax_check_plugin_activation() called by wp_ajax hooks: {'frm_check_plugin_activation'} **/
/** No params detected :-/ **/


/** Function ajax_check_plugin_status() called by wp_ajax hooks: {'frm_smtp_page_check_plugin_status'} **/
/** No function found :-/ **/


/** Function FrmXMLController::export_xml() called by wp_ajax hooks: {'frm_export_xml'} **/
/** No params detected :-/ **/


/** Function FrmFieldsController::load_field() called by wp_ajax hooks: {'frm_load_field'} **/
/** Parameters found in function FrmFieldsController::load_field(): {"post": ["field"], "get": ["page"]} **/
function load_field() {
		FrmAppHelper::permission_check( 'frm_edit_forms' );
		check_ajax_referer( 'frm_ajax', 'nonce' );

		// Javascript may be included in some field settings.
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$fields = isset( $_POST['field'] ) ? wp_unslash( $_POST['field'] ) : array();

		if ( ! $fields ) {
			wp_die();
		}

		$_GET['page'] = 'formidable';

		$values     = array(
			'id'         => FrmAppHelper::get_post_param( 'form_id', '', 'absint' ),
			'doing_ajax' => true,
		);
		$field_html = array();

		foreach ( $fields as $field ) {
			$field = htmlspecialchars_decode( nl2br( $field ) );
			$field = json_decode( $field );

			if ( ! isset( $field->id ) || ! is_numeric( $field->id ) ) {
				// This field may have already been loaded
				continue;
			}

			if ( ! isset( $field->value ) ) {
				$field->value = '';
			}
			$field->field_options = json_decode( json_encode( $field->field_options ), true );
			$field->options       = json_decode( json_encode( $field->options ), true );
			$field->default_value = json_decode( json_encode( $field->default_value ), true );

			ob_start();
			self::load_single_field( $field, $values );
			$field_html[ absint( $field->id ) ] = ob_get_clean();
		}//end foreach

		echo json_encode( $field_html );

		wp_die();
	}


/** Function FrmPayPalLiteConnectHelper::verify() called by wp_ajax hooks: {'nopriv_frm_paypal_lite_verify'} **/
/** No params detected :-/ **/


/** Function FrmAppController::ajax_install() called by wp_ajax hooks: {'frm_install'} **/
/** No params detected :-/ **/


/** Function FrmApplicationsController::get_applications_data() called by wp_ajax hooks: {'frm_get_applications_data'} **/
/** No params detected :-/ **/


/** Function FrmFormTemplatesController::ajax_create_template() called by wp_ajax hooks: {'frm_create_template'} **/
/** No params detected :-/ **/


/** Function FrmFieldsController::duplicate() called by wp_ajax hooks: {'frm_duplicate_field'} **/
/** No params detected :-/ **/


/** Function FrmStylesController::reset_styling() called by wp_ajax hooks: {'frm_settings_reset'} **/
/** No params detected :-/ **/


/** Function FrmSettingsController::load_settings_tab() called by wp_ajax hooks: {'frm_settings_tab'} **/
/** No params detected :-/ **/


/** Function FrmUsageController::ajax_track_flows() called by wp_ajax hooks: {'frm_track_flows'} **/
/** No params detected :-/ **/


/** Function FrmFormActionsController::fill_action() called by wp_ajax hooks: {'frm_form_action_fill'} **/
/** No params detected :-/ **/


/** Function FrmInstallPlugin::ajax_install_plugin() called by wp_ajax hooks: {'frm_install_plugin'} **/
/** No params detected :-/ **/


/** Function FrmAppController::deauthorize() called by wp_ajax hooks: {'frm_deauthorize'} **/
/** No params detected :-/ **/


/** Function FrmFormsController::route() called by wp_ajax hooks: {'frm_save_form'} **/
/** No params detected :-/ **/


/** Function FrmStylesController::load_saved_css() called by wp_ajax hooks: {'nopriv_frmpro_css', 'frmpro_css'} **/
/** No params detected :-/ **/


/** Function FrmWelcomeTourController::ajax_dismiss_welcome_tour() called by wp_ajax hooks: {'frm_dismiss_welcome_tour'} **/
/** No params detected :-/ **/


/** Function FrmPayPalLiteAppController::create_order() called by wp_ajax hooks: {'nopriv_frm_paypal_create_order', 'frm_paypal_create_order'} **/
/** No params detected :-/ **/


/** Function FrmOnboardingWizardController::ajax_consent_tracking() called by wp_ajax hooks: {'frm_onboarding_consent_tracking'} **/
/** No params detected :-/ **/


/** Function FrmSquareLiteAppController::handle_oauth() called by wp_ajax hooks: {'frm_square_oauth'} **/
/** No params detected :-/ **/


/** Function FrmFormsController::preview() called by wp_ajax hooks: {'frm_forms_preview', 'nopriv_frm_forms_preview'} **/
/** No params detected :-/ **/


/** Function FrmFormsController::get_email_html() called by wp_ajax hooks: {'frm_get_default_html'} **/
/** No params detected :-/ **/


/** Function FrmSquareLiteAppController::handle_disconnect() called by wp_ajax hooks: {'frm_square_disconnect'} **/
/** No params detected :-/ **/


/** Function FrmSalesApi::dismiss_banner() called by wp_ajax hooks: {'frm_sale_banner_dismiss'} **/
/** No params detected :-/ **/


/** Function FrmAddon::deactivate() called by wp_ajax hooks: {'frm_addon_deactivate'} **/
/** No params detected :-/ **/


/** Function FrmFormTemplatesController::ajax_get_free_templates() called by wp_ajax hooks: {'frm_get_free_templates'} **/
/** No params detected :-/ **/


/** Function FrmPayPalLiteAppController::create_subscription() called by wp_ajax hooks: {'frm_paypal_create_subscription', 'nopriv_frm_paypal_create_subscription'} **/
/** No params detected :-/ **/


/** Function FrmSquareLiteAppController::verify_buyer() called by wp_ajax hooks: {'frm_verify_buyer', 'nopriv_frm_verify_buyer'} **/
/** No params detected :-/ **/


/** Function FrmAppController::dismiss_review() called by wp_ajax hooks: {'frm_dismiss_review'} **/
/** No params detected :-/ **/


/** Function FrmStylesController::change_styling() called by wp_ajax hooks: {'frm_change_styling'} **/
/** No params detected :-/ **/


/** Function FrmFormTemplatesController::ajax_add_or_remove_favorite() called by wp_ajax hooks: {'frm_add_or_remove_favorite_template'} **/
/** No params detected :-/ **/


/** Function FrmFormsController::build_new_form() called by wp_ajax hooks: {'frm_install_form'} **/
/** No params detected :-/ **/


