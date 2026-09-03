<?php
/***
*
*Found actions: 125
*Found functions:102
*Extracted functions:98
*Total parameter names extracted: 12
*Overview: {'save_quiz': {'forminator_save_quiz_nowrong', 'forminator_save_quiz_knowledge'}, 'load_privacy_settings': {'forminator_load_privacy_settings_popup'}, 'approve_user': {'forminator_approve_user_popup'}, 'fetch_pdfs': {'forminator_fetch_pdfs'}, 'create_paypal_order': {'forminator_pp_create_order', 'nopriv_forminator_pp_create_order'}, 'fetch_report': {'forminator_fetch_report'}, 'save_captcha': {'forminator_save_captcha_popup'}, 'save_pagination_listings': {'forminator_save_pagination_listings_popup'}, 'load_currency': {'forminator_load_currency_popup'}, 'load_turnstile_preview': {'forminator_load_turnstile_preview'}, 'check_stripe_checkout_session_status': {'forminator_check_stripe_checkout_session_status', 'nopriv_forminator_check_stripe_checkout_session_status'}, 'get_custom_sequence_settings': {'forminator_get_custom_sequence_settings'}, 'save_template': {'forminator_save_template'}, 'resend_notification_email': {'forminator_resend_notification_email'}, 'load_captcha': {'forminator_load_captcha_popup'}, 'dismiss_admin_notice': {'forminator_dismiss_notice'}, 'update_report_status': {'forminator_report_update_status'}, 'forminator_share_feedback': {'forminator_share_feedback'}, 'save_payments': {'forminator_save_payments_settings_popup'}, 'load_hcaptcha_preview': {'forminator_load_hcaptcha_preview'}, 'save_import_form_cf7': {'forminator_save_import_form_cf7_popup'}, 'save_poll_form': {'forminator_save_poll'}, 'load_import': {'forminator_load_import_poll_popup', 'forminator_load_import_form_popup', 'forminator_load_import_quiz_popup'}, 'delete_poll_submissions': {'forminator_delete_poll_submissions'}, 'resend_activation_link': {'forminator_resend_activation_link'}, 'delete_unconfirmed_user': {'forminator_delete_unconfirmed_user_popup'}, 'paypal_settings_modal': {'forminator_paypal_settings_modal'}, 'load_export': {'forminator_load_export_form_popup', 'forminator_load_export_quiz_popup', 'forminator_load_export_poll_popup'}, 'get_cloud_templates': {'forminator_get_cloud_templates'}, 'promote_remind_later': {'forminator_promote_remind_later'}, 'load_uninstall_form': {'forminator_load_uninstall_settings_popup'}, 'load_import_form_cf7': {'forminator_load_import_form_cf7_popup'}, 'deactivate': {'forminator_addon_deactivate'}, 'refresh_email_lists': {'forminator_refresh_email_lists'}, 'toggle_usage_tracking': {'forminator_usage_tracking'}, 'later_notice': {'forminator_later_notification'}, 'filter_report_data': {'forminator_filter_report_data'}, 'load_pagination_listings': {'forminator_load_pagination_listings_popup'}, 'set_encryption_key': {'forminator_set_encryption_key'}, 'load_import_form_ninja': {'forminator_load_import_form_ninja_popup'}, 'reset_tracking_data': {'forminator_reset_tracking_data'}, 'addons_page_actions': {'$action'}, 'save_uninstall_form': {'forminator_save_uninstall_settings_popup'}, 'save_accessibility_settings': {'forminator_save_accessibility_settings_popup'}, 'save_import_form_gravity': {'forminator_save_import_form_gravity_popup'}, 'search_emails': {'forminator_builder_search_emails'}, 'rename_template': {'forminator_rename_template'}, 'dismiss_notice': {'forminator_dismiss_notification'}, 'get_avatar': {'forminator_get_avatar'}, 'delete_pdf': {'forminator_delete_pdf'}, 'get_preset_templates': {'forminator_preset_templates'}, 'Forminator_Poll_Front': {'forminator_load_poll', 'nopriv_forminator_load_poll'}, 'save_appearance_preset': {'forminator_save_appearance_preset'}, 'submit_email_draft_link': {'nopriv_forminator_email_draft_link', 'forminator_email_draft_link'}, 'create_module_from_template': {'forminator_create_module_from_template'}, 'save_currency': {'forminator_save_currency_popup'}, 'load_pagination_entries': {'forminator_load_pagination_entries_popup'}, 'clear_exports': {'forminator_clear_exports_popup'}, 'load_import_form_gravity': {'forminator_load_import_form_gravity_popup'}, 'Forminator_CForm_Front': {'nopriv_forminator_load_form', 'forminator_update_live_preview', 'forminator_load_form'}, 'update_payment_amount': {'nopriv_forminator_update_payment_amount', 'forminator_update_payment_amount'}, 'duplicate_template': {'forminator_duplicate_template'}, 'Forminator_QForm_Front': {'forminator_reload_quiz', 'nopriv_forminator_reload_quiz', 'forminator_load_quiz', 'nopriv_forminator_load_quiz'}, 'apply_appearance_preset': {'forminator_apply_appearance_preset'}, 'deactivate_for_module': {'forminator_addon_deactivate_for_module'}, 'search_users': {'forminator_search_users'}, 'save_import_form_ninja': {'forminator_save_import_form_ninja_popup'}, 'resend_draft_email': {'forminator_resend_draft_email'}, 'load_google_fonts': {'forminator_load_google_fonts'}, 'submit_deactivation_survey': {'forminator_deactivation_survey'}, 'stripe_oauth_disconnect': {'forminator_stripe_oauth_disconnect'}, 'load_recaptcha_preview': {'forminator_load_recaptcha_preview'}, 'load_email_form': {'forminator_load_email_settings_popup'}, 'dismiss_welcome': {'nopriv_forminator_dismiss_welcome', 'forminator_dismiss_welcome'}, 'ajax_group_interests': {'forminator_mailchimp_get_group_interests'}, 'save_privacy_settings': {'forminator_save_privacy_settings_popup'}, 'settings': {'forminator_addon_settings'}, 'delete_template': {'forminator_delete_template'}, 'save_pdf': {'forminator_save_pdf'}, 'save_permissions': {'forminator_save_permissions'}, 'fallback_email': {'nopriv_forminator_2fa_fallback_email', 'forminator_2fa_fallback_email'}, 'save_dashboard_settings': {'forminator_save_dashboard_settings_popup'}, 'get_module_addons': {'forminator_addon_get_module_addons'}, 'module_search': {'forminator_module_search'}, 'multiple_file_upload': {'nopriv_forminator_multiple_file_upload', 'forminator_multiple_file_upload'}, 'paypal_update_page': {'forminator_paypal_update_page'}, 'save_import': {'forminator_save_import_poll_popup', 'forminator_save_import_quiz_popup', 'forminator_save_import_form_popup'}, 'preview_module': {'forminator_load_preview_polls_popup', 'forminator_load_preview_quizzes_popup', 'forminator_load_preview_cforms_popup'}, 'hubspot_support_request': {'forminator_hubspot_support_request'}, 'save_pagination_entries': {'forminator_save_pagination_entries_popup'}, 'disconnect_hub': {'forminator_disconnect_hub'}, 'module_settings': {'forminator_addon_module_settings'}, 'create_appearance_preset': {'forminator_create_appearance_preset'}, 'save_report': {'forminator_save_report'}, 'stripe_oauth_init': {'forminator_stripe_oauth_init'}, 'load_exports': {'forminator_load_exports_popup'}, 'get_addons': {'forminator_addon_get_addons'}, 'delete_appearance_preset': {'forminator_delete_appearance_preset'}, 'save_builder': {'forminator_save_builder'}, 'paypal_disconnect': {'forminator_disconnect_paypal'}, 'revert_builder': {'forminator_revert_builder'}, 'get_nonce': {'nopriv_forminator_get_nonce', 'forminator_get_nonce'}}
*
***/

/** Function save_quiz() called by wp_ajax hooks: {'forminator_save_quiz_nowrong', 'forminator_save_quiz_knowledge'} **/
/** Parameters found in function save_quiz(): {"post": ["data"]} **/
function save_quiz() {
		if ( ! forminator_is_user_allowed( 'forminator-quiz' ) ) {
			wp_send_json_error( esc_html__( 'Invalid request, you are not allowed to do that action.', 'forminator' ) );
		}

		forminator_validate_ajax( 'forminator_save_quiz', false, 'forminator-quiz' );

		$submitted_data = $this->get_post_data();

		$quiz_data = array();
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Verified in forminator_validate_ajax.
		if ( ! empty( $_POST['data'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput -- Sanitized in Forminator_Core::sanitize_array.
			$quiz_data = Forminator_Core::sanitize_array( json_decode( wp_unslash( $_POST['data'] ), true ) );
		}

		$id       = isset( $submitted_data['form_id'] ) ? intval( $submitted_data['form_id'] ) : 0;
		$settings = isset( $quiz_data['settings'] ) ? $quiz_data['settings'] : array();
		$title    = isset( $submitted_data['quiz_title'] ) ? $submitted_data['quiz_title'] : $submitted_data['formName'];
		$status   = isset( $submitted_data['status'] ) ? $submitted_data['status'] : '';
		$version  = isset( $submitted_data['version'] ) ? $submitted_data['version'] : '1.0';
		$template = new stdClass();
		if ( 'temp' === $status ) {
			$res = Forminator_Base_Form_Model::save_temp_settings( $id, $quiz_data );
			if ( $res ) {
				wp_send_json_success( $id );
			} else {
				wp_send_json_error( $id );
			}
		}

		$template->type = isset( $submitted_data['action'] ) ? $submitted_data['action'] : '';
		// Check if results exist.
		if ( isset( $quiz_data['results'] ) && is_array( $quiz_data['results'] ) ) {
			$template->results = $quiz_data['results'];
		}

		// Check if answers exist.
		if ( isset( $quiz_data['questions'] ) ) {
			$template->questions = $quiz_data['questions'];
		}

		if ( isset( $quiz_data['settings'] ) ) {
			$settings = $quiz_data['settings'];
		}
		$settings['version'] = $version;
		$template->settings  = $settings;

		if ( isset( $quiz_data['notifications'] ) ) {
			$template->notifications = $quiz_data['notifications'];
		}

		// Server-side validation for personality and question titles.
		if ( 'forminator_save_quiz_nowrong' === $template->type && isset( $template->results ) ) {
			foreach ( $template->results as $result ) {
				if ( empty( $result['title'] ) ) {
					wp_send_json_error( esc_html__( 'Personality cannot be empty. Please enter a valid personality.', 'forminator' ) );
				}
			}
		}

		if ( isset( $template->questions ) ) {
			foreach ( $template->questions as $question ) {
				$question_title = isset( $question['title'] ) ? trim( (string) $question['title'] ) : '';

				if ( '' === $question_title ) {
					wp_send_json_error( esc_html__( 'Question cannot be empty. Please enter a valid question.', 'forminator' ) );
				}

				$answers = isset( $question['answers'] ) ? $question['answers'] : array();
				foreach ( $answers as $answer ) {
					$answer_title = isset( $answer['title'] ) ? trim( (string) $answer['title'] ) : '';

					if ( '' === $answer_title && empty( $answer['image'] ) ) {
						wp_send_json_error( esc_html__( 'Answer cannot be empty. Please enter a valid answer.', 'forminator' ) );
					}
				}
			}
		}

		$id = Forminator_Quiz_Admin::update( $id, $title, $status, $template );
		if ( is_wp_error( $id ) ) {
			wp_send_json_error( $id->get_error_message() );
		} else {
			wp_send_json_success( $id );
		}
	}


/** Function load_privacy_settings() called by wp_ajax hooks: {'forminator_load_privacy_settings_popup'} **/
/** No params detected :-/ **/


/** Function approve_user() called by wp_ajax hooks: {'forminator_approve_user_popup'} **/
/** No params detected :-/ **/


/** Function fetch_pdfs() called by wp_ajax hooks: {'forminator_fetch_pdfs'} **/
/** No params detected :-/ **/


/** Function create_paypal_order() called by wp_ajax hooks: {'forminator_pp_create_order', 'nopriv_forminator_pp_create_order'} **/
/** No params detected :-/ **/


/** Function fetch_report() called by wp_ajax hooks: {'forminator_fetch_report'} **/
/** No params detected :-/ **/


/** Function save_captcha() called by wp_ajax hooks: {'forminator_save_captcha_popup'} **/
/** No params detected :-/ **/


/** Function save_pagination_listings() called by wp_ajax hooks: {'forminator_save_pagination_listings_popup'} **/
/** No params detected :-/ **/


/** Function load_currency() called by wp_ajax hooks: {'forminator_load_currency_popup'} **/
/** No params detected :-/ **/


/** Function load_turnstile_preview() called by wp_ajax hooks: {'forminator_load_turnstile_preview'} **/
/** No params detected :-/ **/


/** Function check_stripe_checkout_session_status() called by wp_ajax hooks: {'forminator_check_stripe_checkout_session_status', 'nopriv_forminator_check_stripe_checkout_session_status'} **/
/** No params detected :-/ **/


/** Function get_custom_sequence_settings() called by wp_ajax hooks: {'forminator_get_custom_sequence_settings'} **/
/** No params detected :-/ **/


/** Function save_template() called by wp_ajax hooks: {'forminator_save_template'} **/
/** No params detected :-/ **/


/** Function resend_notification_email() called by wp_ajax hooks: {'forminator_resend_notification_email'} **/
/** No params detected :-/ **/


/** Function load_captcha() called by wp_ajax hooks: {'forminator_load_captcha_popup'} **/
/** No params detected :-/ **/


/** Function dismiss_admin_notice() called by wp_ajax hooks: {'forminator_dismiss_notice'} **/
/** No params detected :-/ **/


/** Function update_report_status() called by wp_ajax hooks: {'forminator_report_update_status'} **/
/** No params detected :-/ **/


/** Function forminator_share_feedback() called by wp_ajax hooks: {'forminator_share_feedback'} **/
/** No params detected :-/ **/


/** Function save_payments() called by wp_ajax hooks: {'forminator_save_payments_settings_popup'} **/
/** No params detected :-/ **/


/** Function load_hcaptcha_preview() called by wp_ajax hooks: {'forminator_load_hcaptcha_preview'} **/
/** No params detected :-/ **/


/** Function save_import_form_cf7() called by wp_ajax hooks: {'forminator_save_import_form_cf7_popup'} **/
/** No params detected :-/ **/


/** Function save_poll_form() called by wp_ajax hooks: {'forminator_save_poll'} **/
/** Parameters found in function save_poll_form(): {"post": ["data"]} **/
function save_poll_form() {
		if ( ! forminator_is_user_allowed( 'forminator-poll' ) ) {
			wp_send_json_error( esc_html__( 'Invalid request, you are not allowed to do that action.', 'forminator' ) );
		}

		forminator_validate_ajax( 'forminator_save_poll', false, 'forminator-poll' );

		$submitted_data = $this->get_post_data();
		$poll_data      = array();
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Verified in forminator_validate_ajax.
		if ( ! empty( $_POST['data'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput -- Sanitized in Forminator_Core::sanitize_array.
			$poll_data = Forminator_Core::sanitize_array( json_decode( wp_unslash( $_POST['data'] ), true ) );
		}

		$settings = isset( $poll_data['settings'] ) ? $poll_data['settings'] : array();
		$id       = isset( $submitted_data['form_id'] ) ? intval( $submitted_data['form_id'] ) : 0;
		$title    = $submitted_data['formName'];
		$status   = isset( $submitted_data['status'] ) ? $submitted_data['status'] : '';
		$version  = isset( $submitted_data['version'] ) ? $submitted_data['version'] : '1.0';
		$template = new stdClass();

		if ( 'temp' === $status ) {
			$res = Forminator_Base_Form_Model::save_temp_settings( $id, $poll_data );
			if ( $res ) {
				wp_send_json_success( $id );
			} else {
				wp_send_json_error( $id );
			}
		}

		if ( isset( $poll_data['answers'] ) ) {
			$template->answers = $poll_data['answers'];

			foreach ( $template->answers as $answer ) {
				$answer_title = isset( $answer['title'] ) ? trim( (string) $answer['title'] ) : '';

				if ( '' === $answer_title ) {
					wp_send_json_error( esc_html__( 'Poll answers cannot be empty! Please add answers to your poll.', 'forminator' ) );
				}
			}
		}

		$settings['version'] = $version;
		$template->settings  = $settings;

		$id = Forminator_Poll_Admin::update( $id, $title, $status, $template );
		if ( is_wp_error( $id ) ) {
			wp_send_json_error( $id->get_error_message() );
		} else {
			wp_send_json_success( $id );
		}
	}


/** Function load_import() called by wp_ajax hooks: {'forminator_load_import_poll_popup', 'forminator_load_import_form_popup', 'forminator_load_import_quiz_popup'} **/
/** No params detected :-/ **/


/** Function delete_poll_submissions() called by wp_ajax hooks: {'forminator_delete_poll_submissions'} **/
/** No params detected :-/ **/


/** Function resend_activation_link() called by wp_ajax hooks: {'forminator_resend_activation_link'} **/
/** No params detected :-/ **/


/** Function delete_unconfirmed_user() called by wp_ajax hooks: {'forminator_delete_unconfirmed_user_popup'} **/
/** No params detected :-/ **/


/** Function paypal_settings_modal() called by wp_ajax hooks: {'forminator_paypal_settings_modal'} **/
/** No params detected :-/ **/


/** Function load_export() called by wp_ajax hooks: {'forminator_load_export_form_popup', 'forminator_load_export_quiz_popup', 'forminator_load_export_poll_popup'} **/
/** No params detected :-/ **/


/** Function get_cloud_templates() called by wp_ajax hooks: {'forminator_get_cloud_templates'} **/
/** No params detected :-/ **/


/** Function promote_remind_later() called by wp_ajax hooks: {'forminator_promote_remind_later'} **/
/** No params detected :-/ **/


/** Function load_uninstall_form() called by wp_ajax hooks: {'forminator_load_uninstall_settings_popup'} **/
/** No params detected :-/ **/


/** Function load_import_form_cf7() called by wp_ajax hooks: {'forminator_load_import_form_cf7_popup'} **/
/** No params detected :-/ **/


/** Function deactivate() called by wp_ajax hooks: {'forminator_addon_deactivate'} **/
/** No params detected :-/ **/


/** Function refresh_email_lists() called by wp_ajax hooks: {'forminator_refresh_email_lists'} **/
/** No params detected :-/ **/


/** Function toggle_usage_tracking() called by wp_ajax hooks: {'forminator_usage_tracking'} **/
/** No params detected :-/ **/


/** Function later_notice() called by wp_ajax hooks: {'forminator_later_notification'} **/
/** No params detected :-/ **/


/** Function filter_report_data() called by wp_ajax hooks: {'forminator_filter_report_data'} **/
/** No params detected :-/ **/


/** Function load_pagination_listings() called by wp_ajax hooks: {'forminator_load_pagination_listings_popup'} **/
/** No params detected :-/ **/


/** Function set_encryption_key() called by wp_ajax hooks: {'forminator_set_encryption_key'} **/
/** No params detected :-/ **/


/** Function load_import_form_ninja() called by wp_ajax hooks: {'forminator_load_import_form_ninja_popup'} **/
/** No params detected :-/ **/


/** Function reset_tracking_data() called by wp_ajax hooks: {'forminator_reset_tracking_data'} **/
/** No params detected :-/ **/


/** Function addons_page_actions() called by wp_ajax hooks: {'$action'} **/
/** No params detected :-/ **/


/** Function save_uninstall_form() called by wp_ajax hooks: {'forminator_save_uninstall_settings_popup'} **/
/** No params detected :-/ **/


/** Function save_accessibility_settings() called by wp_ajax hooks: {'forminator_save_accessibility_settings_popup'} **/
/** No params detected :-/ **/


/** Function save_import_form_gravity() called by wp_ajax hooks: {'forminator_save_import_form_gravity_popup'} **/
/** No params detected :-/ **/


/** Function search_emails() called by wp_ajax hooks: {'forminator_builder_search_emails'} **/
/** No params detected :-/ **/


/** Function rename_template() called by wp_ajax hooks: {'forminator_rename_template'} **/
/** No params detected :-/ **/


/** Function dismiss_notice() called by wp_ajax hooks: {'forminator_dismiss_notification'} **/
/** No params detected :-/ **/


/** Function get_avatar() called by wp_ajax hooks: {'forminator_get_avatar'} **/
/** No params detected :-/ **/


/** Function delete_pdf() called by wp_ajax hooks: {'forminator_delete_pdf'} **/
/** No params detected :-/ **/


/** Function get_preset_templates() called by wp_ajax hooks: {'forminator_preset_templates'} **/
/** No params detected :-/ **/


/** Function Forminator_Poll_Front() called by wp_ajax hooks: {'forminator_load_poll', 'nopriv_forminator_load_poll'} **/
/** No function found :-/ **/


/** Function save_appearance_preset() called by wp_ajax hooks: {'forminator_save_appearance_preset'} **/
/** Parameters found in function save_appearance_preset(): {"post": ["settings"]} **/
function save_appearance_preset() {
		forminator_validate_ajax( 'forminator_appearance_preset', false, 'forminator-settings' );

		$id = Forminator_Core::sanitize_text_field( 'presetId' );
		if ( ! $id ) {
			wp_send_json_error( esc_html__( 'Appearance preset id doesn\'t exist', 'forminator' ) );
		}

		$settings = array();
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Verified in forminator_validate_ajax.
		if ( ! empty( $_POST['settings'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput -- Sanitized in Forminator_Core::sanitize_array.
			$settings = Forminator_Core::sanitize_array( json_decode( wp_unslash( $_POST['settings'] ), true ) );
		}

		self::save_preset( $id, $settings );

		wp_send_json_success( esc_html__( 'The preset has been successfully updated.', 'forminator' ) );
	}


/** Function submit_email_draft_link() called by wp_ajax hooks: {'nopriv_forminator_email_draft_link', 'forminator_email_draft_link'} **/
/** No params detected :-/ **/


/** Function create_module_from_template() called by wp_ajax hooks: {'forminator_create_module_from_template'} **/
/** No params detected :-/ **/


/** Function save_currency() called by wp_ajax hooks: {'forminator_save_currency_popup'} **/
/** No params detected :-/ **/


/** Function load_pagination_entries() called by wp_ajax hooks: {'forminator_load_pagination_entries_popup'} **/
/** No params detected :-/ **/


/** Function clear_exports() called by wp_ajax hooks: {'forminator_clear_exports_popup'} **/
/** No params detected :-/ **/


/** Function load_import_form_gravity() called by wp_ajax hooks: {'forminator_load_import_form_gravity_popup'} **/
/** No params detected :-/ **/


/** Function Forminator_CForm_Front() called by wp_ajax hooks: {'nopriv_forminator_load_form', 'forminator_update_live_preview', 'forminator_load_form'} **/
/** No function found :-/ **/


/** Function update_payment_amount() called by wp_ajax hooks: {'nopriv_forminator_update_payment_amount', 'forminator_update_payment_amount'} **/
/** No params detected :-/ **/


/** Function duplicate_template() called by wp_ajax hooks: {'forminator_duplicate_template'} **/
/** No params detected :-/ **/


/** Function Forminator_QForm_Front() called by wp_ajax hooks: {'forminator_reload_quiz', 'nopriv_forminator_reload_quiz', 'forminator_load_quiz', 'nopriv_forminator_load_quiz'} **/
/** No function found :-/ **/


/** Function apply_appearance_preset() called by wp_ajax hooks: {'forminator_apply_appearance_preset'} **/
/** Parameters found in function apply_appearance_preset(): {"post": ["settings"]} **/
function apply_appearance_preset() {
		forminator_validate_ajax( 'forminator_apply_preset', false, 'forminator-cform' );

		$preset_id = Forminator_Core::sanitize_text_field( 'preset_id' );
		$ids       = filter_input( INPUT_POST, 'ids', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY );
		$edit_form = filter_input( INPUT_POST, 'edit_form', FILTER_VALIDATE_BOOLEAN );
		$count     = 0;

		$new_settings = Forminator_Settings_Page::get_preset( $preset_id );
		if ( $ids ) {
			foreach ( $ids as $form_id ) {
				$form_model = Forminator_Base_Form_Model::get_model( $form_id );
				if ( ! $form_model ) {
					continue;
				}

				$form_model->settings = self::merge_appearance_settings( $form_model->settings, $new_settings );

				if ( $edit_form ) {
					// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput -- Sanitized in Forminator_Core::sanitize_array.
					$settings     = isset( $_POST['settings'] ) ? Forminator_Core::sanitize_array( $_POST['settings'] ) : array();
					$old_settings = json_decode( wp_unslash( $settings ), true );
					if ( $old_settings ) {
						$form_model->settings = self::merge_appearance_settings( $old_settings, $new_settings );
					}
					wp_send_json_success( $form_model->settings );
				}

				// Save data.
				$result = $form_model->save();
				if ( ! is_wp_error( $result ) ) {
					// Regenerare module css file.
					Forminator_Render_Form::regenerate_css_file( $form_id );

					++$count;
				}
			}
		}

		if ( $edit_form ) {
			wp_send_json_error( esc_html__( 'Something went wrong.', 'forminator' ) );
		}
		/* translators: %s: Form count */
		$success = sprintf( esc_html__( 'Preset successfully applied to %d form(s).', 'forminator' ), $count );
		wp_send_json_success( $success );
	}


/** Function deactivate_for_module() called by wp_ajax hooks: {'forminator_addon_deactivate_for_module'} **/
/** No params detected :-/ **/


/** Function search_users() called by wp_ajax hooks: {'forminator_search_users'} **/
/** No params detected :-/ **/


/** Function save_import_form_ninja() called by wp_ajax hooks: {'forminator_save_import_form_ninja_popup'} **/
/** No params detected :-/ **/


/** Function resend_draft_email() called by wp_ajax hooks: {'forminator_resend_draft_email'} **/
/** No params detected :-/ **/


/** Function load_google_fonts() called by wp_ajax hooks: {'forminator_load_google_fonts'} **/
/** Parameters found in function load_google_fonts(): {"post": ["data"]} **/
function load_google_fonts() {
		forminator_validate_ajax( 'forminator_load_google_fonts', false, 'forminator' );

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Verified in forminator_validate_ajax.
		$is_object = isset( $_POST['data']['isObject'] ) ? sanitize_text_field( wp_unslash( $_POST['data']['isObject'] ) ) : false;

		$fonts = forminator_get_font_families( $is_object );
		wp_send_json_success( $fonts );
	}


/** Function submit_deactivation_survey() called by wp_ajax hooks: {'forminator_deactivation_survey'} **/
/** No params detected :-/ **/


/** Function stripe_oauth_disconnect() called by wp_ajax hooks: {'forminator_stripe_oauth_disconnect'} **/
/** Parameters found in function stripe_oauth_disconnect(): {"post": ["mode"]} **/
function stripe_oauth_disconnect() {
		// Validate nonce + capabilities.
		forminator_validate_ajax( 'forminator_stripe_oauth', false, 'forminator-settings' );

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Verified in forminator_validate_ajax above.
		$mode = isset( $_POST['mode'] ) ? sanitize_text_field( wp_unslash( $_POST['mode'] ) ) : '';
		if ( ! in_array( $mode, array( 'live', 'test' ), true ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Invalid Stripe connection mode.', 'forminator' ),
				)
			);
		}

		if ( ! Forminator_Gateway_Stripe::is_mode_configured( $mode ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'This Stripe mode is not connected.', 'forminator' ),
				)
			);
		}

		Forminator_Stripe_Connect::get_instance()->disconnect( $mode );

		$success_text = 'live' === $mode
			? esc_html__( 'Live Stripe account disconnected successfully.', 'forminator' )
			: esc_html__( 'Test Stripe account disconnected successfully.', 'forminator' );

		$data = array(
			'notifications' => array(
				array(
					'type'     => 'success',
					'text'     => $success_text,
					'duration' => 4000,
				),
			),
		);

		ob_start();
		/* @noinspection PhpIncludeInspection */
		include forminator_plugin_dir() . 'admin/views/settings/payments/section-stripe.php';
		$data['html'] = ob_get_clean();

		wp_send_json_success( $data );
	}


/** Function load_recaptcha_preview() called by wp_ajax hooks: {'forminator_load_recaptcha_preview'} **/
/** No params detected :-/ **/


/** Function load_email_form() called by wp_ajax hooks: {'forminator_load_email_settings_popup'} **/
/** No params detected :-/ **/


/** Function dismiss_welcome() called by wp_ajax hooks: {'nopriv_forminator_dismiss_welcome', 'forminator_dismiss_welcome'} **/
/** No params detected :-/ **/


/** Function ajax_group_interests() called by wp_ajax hooks: {'forminator_mailchimp_get_group_interests'} **/
/** Parameters found in function ajax_group_interests(): {"post": ["data"]} **/
function ajax_group_interests() {
		forminator_validate_ajax( 'forminator_mailchimp_interests', false, 'forminator-integrations' );
		$html = '';
		// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput -- The nonce is verified before and sanitized in Forminator_Core::sanitize_array.
		$post_data = isset( $_POST['data'] ) ? Forminator_Core::sanitize_array( $_POST['data'], 'data' ) : array();
		$data      = array();
		wp_parse_str( $post_data, $data );
		$module_id   = $data['module_id'] ?? '';
		$module_type = $data['module_type'] ?? '';
		if ( $module_id ) {
			$module_settings_instance = $this->get_addon_settings( $module_id, $module_type );
			$html                     = $module_settings_instance->get_group_interests( $data );
		}

		wp_send_json_success( $html );
	}


/** Function save_privacy_settings() called by wp_ajax hooks: {'forminator_save_privacy_settings_popup'} **/
/** No params detected :-/ **/


/** Function settings() called by wp_ajax hooks: {'forminator_addon_settings'} **/
/** No params detected :-/ **/


/** Function delete_template() called by wp_ajax hooks: {'forminator_delete_template'} **/
/** No params detected :-/ **/


/** Function save_pdf() called by wp_ajax hooks: {'forminator_save_pdf'} **/
/** No params detected :-/ **/


/** Function save_permissions() called by wp_ajax hooks: {'forminator_save_permissions'} **/
/** No params detected :-/ **/


/** Function fallback_email() called by wp_ajax hooks: {'nopriv_forminator_2fa_fallback_email', 'forminator_2fa_fallback_email'} **/
/** No params detected :-/ **/


/** Function save_dashboard_settings() called by wp_ajax hooks: {'forminator_save_dashboard_settings_popup'} **/
/** No params detected :-/ **/


/** Function get_module_addons() called by wp_ajax hooks: {'forminator_addon_get_module_addons'} **/
/** No params detected :-/ **/


/** Function module_search() called by wp_ajax hooks: {'forminator_module_search'} **/
/** No params detected :-/ **/


/** Function multiple_file_upload() called by wp_ajax hooks: {'nopriv_forminator_multiple_file_upload', 'forminator_multiple_file_upload'} **/
/** No params detected :-/ **/


/** Function paypal_update_page() called by wp_ajax hooks: {'forminator_paypal_update_page'} **/
/** No params detected :-/ **/


/** Function save_import() called by wp_ajax hooks: {'forminator_save_import_poll_popup', 'forminator_save_import_quiz_popup', 'forminator_save_import_form_popup'} **/
/** Parameters found in function save_import(): {"post": ["importable"]} **/
function save_import() {
		if ( ! Forminator::is_import_export_feature_enabled() ) {
			wp_send_json_error( esc_html__( 'Import Export Feature disabled.', 'forminator' ) );
		}
		if ( empty( $_POST['importable'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			wp_send_json_error( esc_html__( 'Import text can not be empty.', 'forminator' ) );
		}
		$current_action = current_action();
		$slug           = str_replace( array( 'wp_ajax_forminator_save_import_', '_popup' ), '', $current_action );
		// Validate nonce.
		forminator_validate_ajax( 'forminator_save_import_' . $slug, false, 'forminator-settings' );

		// Modify recipients if replace all recipients checkbox has been checked.
		$change_recipients = 'checked' === Forminator_Core::sanitize_text_field( 'change_recipients' );

		$save_to_cloud = 'checked' === Forminator_Core::sanitize_text_field( 'save_to_cloud' );

		// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput
		$json  = wp_unslash( $_POST['importable'] );
		$model = $this->import_json( $json, $slug, $change_recipients );

		$return_url = admin_url( 'admin.php?page=forminator-' . forminator_get_prefix( $slug, 'c' ) );

		if ( $save_to_cloud && ! forminator_cloud_templates_disabled() && forminator_is_site_connected_to_hub() ) {
			Forminator_Template_API::create_template( $model->name, wp_json_encode( $model->to_exportable_data() ) );
		}
		/**
		 * Fires after form import
		 *
		 * @since 1.27.0
		 *
		 * @param string $slug Module type.
		 */
		do_action( 'forminator_after_form_import', $slug );

		wp_send_json_success(
			array(
				'id'  => $model->id,
				'url' => $return_url,
			)
		);
	}


/** Function preview_module() called by wp_ajax hooks: {'forminator_load_preview_polls_popup', 'forminator_load_preview_quizzes_popup', 'forminator_load_preview_cforms_popup'} **/
/** Parameters found in function preview_module(): {"post": ["data"]} **/
function preview_module() {
		$current_action = current_action();
		$slug           = str_replace( array( 'wp_ajax_forminator_load_preview_', '_popup' ), '', $current_action );
		if ( 'cforms' === $slug ) {
			$slug = 'form';
		} elseif ( 'polls' === $slug ) {
			$slug = 'poll';
		} elseif ( 'quizzes' === $slug ) {
			$slug = 'quiz';
		}

		// Validate nonce.
		forminator_validate_ajax( 'forminator_popup_preview_' . $slug, false, 'forminator' );

		$preview_data = false;
		// force -1 for preview.
		$form_id = filter_input( INPUT_POST, 'id', FILTER_VALIDATE_INT );
		$form_id = $form_id ? $form_id : - 1;

		// Check if preview data set.
		if ( ! empty( $_POST['data'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Verified in forminator_validate_ajax.
			// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput -- Sanitized in Forminator_Core::sanitize_array.
			$data = Forminator_Core::sanitize_array( $_POST['data'], 'data' );

			if ( ! is_array( $data ) ) {
				$data = json_decode( $data, true );
			}
			$function     = 'forminator_data_to_model_' . $slug;
			$preview_data = $function( $data );
		}

		$html = forminator_preview( $form_id, $slug, true, $preview_data );

		wp_send_json_success( $html );
	}


/** Function hubspot_support_request() called by wp_ajax hooks: {'forminator_hubspot_support_request'} **/
/** No params detected :-/ **/


/** Function save_pagination_entries() called by wp_ajax hooks: {'forminator_save_pagination_entries_popup'} **/
/** No function found :-/ **/


/** Function disconnect_hub() called by wp_ajax hooks: {'forminator_disconnect_hub'} **/
/** No params detected :-/ **/


/** Function module_settings() called by wp_ajax hooks: {'forminator_addon_module_settings'} **/
/** No params detected :-/ **/


/** Function create_appearance_preset() called by wp_ajax hooks: {'forminator_create_appearance_preset'} **/
/** No params detected :-/ **/


/** Function save_report() called by wp_ajax hooks: {'forminator_save_report'} **/
/** Parameters found in function save_report(): {"post": ["reports"]} **/
function save_report() {
		forminator_validate_ajax( 'forminator-save', 'nonce', 'forminator-reports' );

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Verified in forminator_validate_ajax.
		if ( empty( $_POST['reports'] ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Something went wrong.', 'forminator' ),
				)
			);
		}

		$report_id    = Forminator_Core::sanitize_text_field( 'report_id' );
		$status       = Forminator_Core::sanitize_text_field( 'status' );
		$reports_data = Forminator_Core::sanitize_array( $_POST['reports'] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput

		$reports_data['report_status'] = $status;

		if ( 0 !== (int) $report_id ) {
			$result = Forminator_Form_Reports_Model::get_instance()->report_update( $report_id, $reports_data, $status );
		} else {
			$result                    = Forminator_Form_Reports_Model::get_instance()->report_save( $reports_data, $status );
			$reports_data['report_id'] = $result;
		}

		if ( is_wp_error( $result ) ) {
			wp_send_json_error();
		}

		/**
		 * Fires after report save
		 *
		 * @since 1.27.0
		 *
		 * @param array $reports_data Report data.
		 */

		do_action( 'forminator_after_notification_update', $reports_data );

		wp_send_json_success( $result );
	}


/** Function stripe_oauth_init() called by wp_ajax hooks: {'forminator_stripe_oauth_init'} **/
/** Parameters found in function stripe_oauth_init(): {"post": ["mode"]} **/
function stripe_oauth_init() {
		// Validate nonce + capabilities.
		forminator_validate_ajax( 'forminator_stripe_oauth', false, 'forminator-settings' );

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Verified in forminator_validate_ajax above.
		$mode = isset( $_POST['mode'] ) ? sanitize_text_field( wp_unslash( $_POST['mode'] ) ) : '';
		if ( ! in_array( $mode, array( 'live', 'test' ), true ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Invalid Stripe connection mode.', 'forminator' ),
				)
			);
		}

		$url = Forminator_Stripe_Connect::get_instance()->get_oauth_url( $mode );

		if ( is_wp_error( $url ) ) {
			wp_send_json_error(
				array(
					'message' => $url->get_error_message(),
				)
			);
		}

		wp_send_json_success(
			array(
				'url' => esc_url_raw( $url ),
			)
		);
	}


/** Function load_exports() called by wp_ajax hooks: {'forminator_load_exports_popup'} **/
/** No params detected :-/ **/


/** Function get_addons() called by wp_ajax hooks: {'forminator_addon_get_addons'} **/
/** No params detected :-/ **/


/** Function delete_appearance_preset() called by wp_ajax hooks: {'forminator_delete_appearance_preset'} **/
/** No params detected :-/ **/


/** Function save_builder() called by wp_ajax hooks: {'forminator_save_builder'} **/
/** Parameters found in function save_builder(): {"post": ["data"]} **/
function save_builder() {
		if ( ! forminator_is_user_allowed( 'forminator-cform' ) ) {
			wp_send_json_error( esc_html__( 'Invalid request, you are not allowed to do that action.', 'forminator' ) );
		}

		forminator_validate_ajax( 'forminator_save_builder_fields', false, 'forminator-cform' );

		$submitted_data = $this->get_post_data();
		$form_data      = array();
		$fields         = array();
		$id             = isset( $submitted_data['form_id'] ) ? intval( $submitted_data['form_id'] ) : 0;
		$title          = $submitted_data['formName'];
		$status         = isset( $submitted_data['status'] ) ? $submitted_data['status'] : '';
		$version        = isset( $submitted_data['version'] ) ? $submitted_data['version'] : '1.0';
		$template       = new stdClass();

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Verified in forminator_validate_ajax.
		if ( ! empty( $_POST['data'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput -- Sanitized in Forminator_Core::sanitize_array.
			$form_data = Forminator_Core::sanitize_array( json_decode( wp_unslash( $_POST['data'] ), true ) );
		}

		$form_model = Forminator_Base_Form_Model::get_model( $id );

		if ( ! is_object( $form_model ) ) {
			wp_send_json_error( esc_html__( 'Form model doesn\'t exist', 'forminator' ) );
		}

		if ( 'temp' === $status ) {
			$res = Forminator_Base_Form_Model::save_temp_settings( $id, $form_data );
			if ( $res ) {
				wp_send_json_success( $id );
			} else {
				wp_send_json_error( $id );
			}
		}

		if ( empty( $status ) ) {
			$status = $form_model->status;
		}

		// we need to empty fields cause we will send new data.
		$form_model->clear_fields();

		// Build the fields.
		if ( isset( $form_data ) ) {
			$fields = $form_data['wrappers'];
			unset( $form_data['wrappers'] );
		}
		$template->fields = $fields;

		if ( isset( $form_data['integrationConditions'] ) ) {
			$template->integration_conditions = $form_data['integrationConditions'];
		}

		if ( isset( $form_data['behaviorArray'] ) ) {
			$template->behaviors = $form_data['behaviorArray'];
		}

		if ( isset( $form_data['notifications'] ) ) {
			$template->notifications = $form_data['notifications'];
		}

		// Sanitize settings.
		$settings          = $form_data['settings'];
		$activation_method = ! empty( $settings['activation-method'] ) ? $settings['activation-method'] : '';
		if ( 'manual' === $activation_method ) {
			$settings['automatic-login'] = '';
		}

		if ( isset( $settings['fields-style'] ) && 'custom' === $settings['fields-style'] ) {
			$settings['spacing'] = ! empty( $settings['spacing'] ) ? $settings['spacing'] : 0;
		}

		$settings['version'] = $version;
		$template->settings  = $settings;

		$id = Forminator_Custom_Form_Admin::update( $id, $title, $status, $template );
		if ( is_wp_error( $id ) ) {
			wp_send_json_error( $id );
		} else {
			$response = array(
				'id'                        => $id,
				'reload_subscription_plans' => false,
			);

			if (
				class_exists( 'Forminator_Stripe_Subscription' )
				&& method_exists( 'Forminator_Stripe_Subscription', 'did_recreate_subscription_product' )
			) {
				$response['reload_subscription_plans'] = Forminator_Stripe_Subscription::get_instance()->did_recreate_subscription_product();
			}

			wp_send_json_success( $response );
		}
	}


/** Function paypal_disconnect() called by wp_ajax hooks: {'forminator_disconnect_paypal'} **/
/** No params detected :-/ **/


/** Function revert_builder() called by wp_ajax hooks: {'forminator_revert_builder'} **/
/** No params detected :-/ **/


/** Function get_nonce() called by wp_ajax hooks: {'nopriv_forminator_get_nonce', 'forminator_get_nonce'} **/
/** No params detected :-/ **/


