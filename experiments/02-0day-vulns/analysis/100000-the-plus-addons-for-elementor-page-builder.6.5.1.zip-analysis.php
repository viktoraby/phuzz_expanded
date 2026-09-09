<?php
/***
*
*Found actions: 35
*Found functions:33
*Extracted functions:31
*Total parameter names extracted: 24
*Overview: {'theplus_act_license_notice_dismiss': {'theplus_actlicense_notice_dismiss'}, 'theplus_smart_perf_clear_cache': {'smart_perf_clear_cache'}, 'theplus_widget_recipes_notice_dismiss': {'theplus_widget_recipes_notice_dismiss'}, 'change_current_template_title': {'change_current_template_title'}, 'tpae_check_plugin_status': {'check_plugin_status'}, 'theplus_backend_clear_cache': {'backend_clear_cache'}, 'theplus_current_page_clear_cache': {'plus_purge_current_clear'}, 'tpae_handle_enable_widget': {'tpae_handle_enable_widget'}, 'tpae_update_popup_dismiss': {'tpae_update_popup_dismiss'}, 'tp_dynamic_tag_nonce': {'tp_mark_dynamic_tag_seen'}, 'tp_install_promotions_plugin': {'tp_install_promotions_plugin'}, 'tp_dont_show_again': {'tp_dont_show_again'}, 'tpae_live_paste': {'tpae_live_paste'}, 'tpae_nxt_ext_pnotice_dismiss': {'tpae_nxt_ext_pnotice_dismiss'}, 'tpae_elementor_ajax_call': {'tpae_elementor_ajax_call'}, 'theplus_expired_license_notice_dismiss': {'theplus_explicense_notice_dismiss'}, 'theplus_whats_new_notice_dismiss': {'theplus_whats_new_notice_dismiss'}, 'theplus_nexter_extension_dismiss_promo': {'theplus_nexter_extension_dismiss_promo'}, 'tpae_form_submission': {'nopriv_tpae_form_submission', 'tpae_form_submission'}, 'L_theplus_more_post_ajax': {'nopriv_L_theplus_more_post', 'L_theplus_more_post'}, 'theplus_blocks_dismiss_promo': {'theplus_blocks_dismiss_promo'}, 'theplus_join_community_notice_dismiss': {'theplus_joincom_notice_dismiss'}, 'tpae_dismiss_dynamic_notice': {'tpae_dismiss_dynamic_notice'}, 'tpae_dashboard_ajax_call': {'tpae_dashboard_ajax_call'}, 'theplus_pro_promo_notice_dismiss': {'theplus_pro_dismiss_promo'}, 'theplus_ask_review_notice_dismiss': {'theplus_askreview_notice_dismiss'}, 'tpae_template_create': {'tpae_template_create'}, 'tpae_pluginfeatures_notice_dismiss': {'tpae_pluginfeatures_notice_dismiss'}, 'theplus_removed_widgets_notice_dismiss': {'theplus_removed_widgets_notice_dismiss'}, 'tpae_cross_copy_paste_media_import': {'plus_cross_cp_import'}, 'tp_install_wdkit': {'tp_install_wdkit'}, 'tpae_create_page': {'tpae_create_page'}, 'tpae_install_wdkit': {'tpae_install_wdkit'}}
*
***/

/** Function theplus_act_license_notice_dismiss() called by wp_ajax hooks: {'theplus_actlicense_notice_dismiss'} **/
/** Parameters found in function theplus_act_license_notice_dismiss(): {"post": ["security", "type"]} **/
function theplus_act_license_notice_dismiss() {
			$get_security = ! empty( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '';

			if ( ! isset( $get_security ) || empty( $get_security ) || ! wp_verify_nonce( $get_security, 'tpae-activate-license' ) ) {
				die( esc_html__( 'Security checked!', 'tpebl' ) );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'You are not allowed to do this action', 'tpebl' ) );
				wp_die();
			}

			$get_type = ! empty( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';

			update_option( 'tpae_activate_license_notice', true, false );

			wp_send_json_success();
		}


/** Function theplus_smart_perf_clear_cache() called by wp_ajax hooks: {'smart_perf_clear_cache'} **/
/** No params detected :-/ **/


/** Function theplus_widget_recipes_notice_dismiss() called by wp_ajax hooks: {'theplus_widget_recipes_notice_dismiss'} **/
/** Parameters found in function theplus_widget_recipes_notice_dismiss(): {"post": ["security"]} **/
function theplus_widget_recipes_notice_dismiss() {
			$get_security = ! empty( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '';

			if ( empty( $get_security ) || ! wp_verify_nonce( $get_security, 'tpae-widget-recipes' ) ) {
				wp_send_json_error( __( 'Security check failed', 'tpebl' ) );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'You are not allowed to do this action', 'tpebl' ) );
			}

			update_option( self::OPTION, true, false );

			wp_send_json_success();
		}


/** Function change_current_template_title() called by wp_ajax hooks: {'change_current_template_title'} **/
/** Parameters found in function change_current_template_title(): {"post": ["id", "updated_title"]} **/
function change_current_template_title() {

			check_ajax_referer( 'live_editor', 'security' );

			if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( array( 'content' => __( 'Insufficient permissions.', 'tpebl' ) ) );
			}

			$id            = ! empty( $_POST['id'] ) ? absint( wp_unslash( $_POST['id'] ) ) : 0;
			$updated_title = ! empty( $_POST['updated_title'] ) ? sanitize_text_field( wp_unslash( $_POST['updated_title'] ) ) : '';

			if ( $id <= 0 || ! current_user_can( 'edit_post', $id ) ) {
				wp_send_json_error( array( 'content' => __( 'Invalid template.', 'tpebl' ) ) );
			}

			wp_update_post(
				array(
					'ID'         => $id,
					'post_title' => $updated_title,
				)
			);

			wp_send_json_success(
				array(
					'ID'         => $id,
					'post_title' => $updated_title,
				)
			);
		}


/** Function tpae_check_plugin_status() called by wp_ajax hooks: {'check_plugin_status'} **/
/** No params detected :-/ **/


/** Function theplus_backend_clear_cache() called by wp_ajax hooks: {'backend_clear_cache'} **/
/** No params detected :-/ **/


/** Function theplus_current_page_clear_cache() called by wp_ajax hooks: {'plus_purge_current_clear'} **/
/** Parameters found in function theplus_current_page_clear_cache(): {"post": ["plus_name"]} **/
function theplus_current_page_clear_cache() {
		check_ajax_referer( 'theplus-addons', 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'Insufficient permissions.' ) );
			wp_die();
		}

		$plus_name = '';
		if ( isset( $_POST['plus_name'] ) && ! empty( $_POST['plus_name'] ) ) {
			/**
			 * This value becomes part of a filename that is then deleted, so it has to stay a
			 * single path segment. `sanitize_text_field()` preserves `/`, `\` and `..`;
			 * `sanitize_file_name()` strips the separators a traversal needs.
			 *
			 * @since 6.5.0
			 */
			$plus_name = sanitize_file_name( wp_unslash( $_POST['plus_name'] ) );
		}
		if ( $plus_name == 'theplus-all' ) {
			// All clear cache files
			l_theplus_library()->remove_dir_files( L_THEPLUS_ASSET_PATH );
			update_option( 'tp_save_update_at', strtotime( 'now' ), false );
		} else {
			// Current Page cache files
			l_theplus_library()->remove_current_page_dir_files( L_THEPLUS_ASSET_PATH, $plus_name );
		}
		wp_send_json( true );
	}


/** Function tpae_handle_enable_widget() called by wp_ajax hooks: {'tpae_handle_enable_widget'} **/
/** Parameters found in function tpae_handle_enable_widget(): {"post": ["widget_id"]} **/
function tpae_handle_enable_widget() {

			check_ajax_referer('tpae_widgets_enable', 'security');

			if ( ! current_user_can('manage_options') ) {
				wp_send_json(
					$this->tpae_set_response(false, 'Invalid Permission.', 'Something went wrong.')
				);
				wp_die();
			}
			
			if ( empty($_POST['widget_id']) ) {
				wp_send_json(
					$this->tpae_set_response(false, 'Invalid Widget ID', 'Something went wrong.')
				);
				wp_die();
			}

			$widget_id = sanitize_key($_POST['widget_id']);

			$plus_options = get_option('theplus_options', array());

			if ( empty($plus_options['check_elements']) || ! is_array($plus_options['check_elements']) ) {
				$plus_options['check_elements'] = array();
			}

			if ( ! in_array($widget_id, $plus_options['check_elements'], true) ) {

				$plus_options['check_elements'][] = $widget_id;
				update_option('theplus_options', $plus_options);

				wp_send_json(
					$this->tpae_set_response(true, 'Widget activated successfully', '')
				);
			}

			wp_send_json(
				$this->tpae_set_response(false, 'Widget already active', '')
			);
		}


/** Function tpae_update_popup_dismiss() called by wp_ajax hooks: {'tpae_update_popup_dismiss'} **/
/** Parameters found in function tpae_update_popup_dismiss(): {"post": ["security", "type"]} **/
function tpae_update_popup_dismiss() {
			$get_security = ! empty( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '';

			if ( ! isset( $get_security ) || empty( $get_security ) || ! wp_verify_nonce( $get_security, 'tpae_updatepopup_nonce' ) ) {
				wp_die( 'Security checked!' );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'You are not allowed to do this action', 'tpebl' ) );
			}

			$get_type = ! empty( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';

			// update_option( 'tp_update_popup_dismiss', true );
			update_option( 'tpae_menu_notification', TPAE_MENU_NOTIFICETIONS );

			update_option( 'tpae_whats_new_notification', TPAE_WHATS_NEW_NOTIFICETIONS );

			wp_send_json_success();
		}


/** Function tp_dynamic_tag_nonce() called by wp_ajax hooks: {'tp_mark_dynamic_tag_seen'} **/
/** No function found :-/ **/


/** Function tp_install_promotions_plugin() called by wp_ajax hooks: {'tp_install_promotions_plugin'} **/
/** Parameters found in function tp_install_promotions_plugin(): {"post": ["plugin_type"]} **/
function tp_install_promotions_plugin(){

			check_ajax_referer( 'tp_nxt_install', 'security' );

			if ( ! current_user_can( 'install_plugins' ) ) {
				$response = $this->tpae_response('Invalid Permission.', 'Something went wrong.',false );

				wp_send_json( $response );
				wp_die();
			}

			$plugin_type = isset( $_POST['plugin_type'] ) ? sanitize_key( wp_unslash( $_POST['plugin_type'] ) ) : '';

			$allowed = array(
				'nexter_ext' => array(
					'tp_slug'            => 'nexter-extension',
					'tp_plugin_basename' => 'nexter-extension/nexter-extension.php',
				),
				'tp_woo'     => array(
					'tp_slug'            => 'woocommerce',
					'tp_plugin_basename' => 'woocommerce/woocommerce.php',
				),
			);

			if ( ! isset( $allowed[ $plugin_type ] ) ) {
				wp_send_json( $this->tpae_response( 'Invalid Plugin Type', 'The requested plugin is not allowed.', false ) );
				wp_die();
			}

			$type = $allowed[ $plugin_type ];

			$tp_response = apply_filters( 'tpae_plugin_install', $type );

			$response = $this->tpae_response( 'Success', '', true, $tp_response );

			wp_send_json( $response );
			wp_die();
		}


/** Function tp_dont_show_again() called by wp_ajax hooks: {'tp_dont_show_again'} **/
/** No params detected :-/ **/


/** Function tpae_live_paste() called by wp_ajax hooks: {'tpae_live_paste'} **/
/** Parameters found in function tpae_live_paste(): {"post": ["type"]} **/
function tpae_live_paste() {

			if ( ! check_ajax_referer( 'plus_cross_cp_import', 'nonce', false ) ) {

				$response = $this->tpae_set_response( false, 'Invalid nonce.', 'The security check failed. Please refresh the page and try again.' );

				wp_send_json( $response );
				wp_die();
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				$response = $this->tpae_set_response( false, 'Insufficient permissions.', 'You do not have permission to perform this action.' );
				wp_send_json( $response );
				wp_die();
			}


			$type = isset( $_POST['type'] ) ? strtolower( sanitize_text_field( wp_unslash( $_POST['type'] ) ) ) : false;

			switch ( $type ) {
				case 'tpae_enable_widget':
					$response = $this->tpae_enable_widget();
					break;
			}

			wp_send_json( $response );
			wp_die();
		}


/** Function tpae_nxt_ext_pnotice_dismiss() called by wp_ajax hooks: {'tpae_nxt_ext_pnotice_dismiss'} **/
/** Parameters found in function tpae_nxt_ext_pnotice_dismiss(): {"post": ["security", "type"]} **/
function tpae_nxt_ext_pnotice_dismiss() {
			$get_security = ! empty( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '';

			if ( ! isset( $get_security ) || empty( $get_security ) || ! wp_verify_nonce( $get_security, 'tpae-nxt-ext-pnotice' ) ) {
				die( 'Security checked!' );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'You are not allowed to do this action', 'tpebl' ) );
				wp_die();
			}

			$get_type = ! empty( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';

			update_option( 'tpae_nxt_ext_pnotice', true, false );

			wp_send_json_success();
		}


/** Function tpae_elementor_ajax_call() called by wp_ajax hooks: {'tpae_elementor_ajax_call'} **/
/** No params detected :-/ **/


/** Function theplus_expired_license_notice_dismiss() called by wp_ajax hooks: {'theplus_explicense_notice_dismiss'} **/
/** Parameters found in function theplus_expired_license_notice_dismiss(): {"post": ["security", "type"]} **/
function theplus_expired_license_notice_dismiss() {
			$get_security = ! empty( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '';

			if ( ! isset( $get_security ) || empty( $get_security ) || ! wp_verify_nonce( $get_security, 'tpae-expired-license' ) ) {
				die( 'Security checked!' );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'You are not allowed to do this action', 'tpebl' ) );
				wp_die();
			}

			$get_type = ! empty( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';

			if ( 'tpae_expired_license_notice' === $get_type ) {
				update_option( 'tpae_expired_license_notice', true, false );
			} elseif ( 'tpae_expired_license_week_notice' === $get_type ) {
				update_option( 'tpae_expired_license_week_notice', true, false );
			} elseif ( 'tpae_expired_license_month_notice' === $get_type ) {
				update_option( 'tpae_expired_license_month_notice', true, false );
			}

			wp_send_json_success();
		}


/** Function theplus_whats_new_notice_dismiss() called by wp_ajax hooks: {'theplus_whats_new_notice_dismiss'} **/
/** Parameters found in function theplus_whats_new_notice_dismiss(): {"post": ["security"]} **/
function theplus_whats_new_notice_dismiss() {
			$get_security = ! empty( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '';

			if ( empty( $get_security ) || ! wp_verify_nonce( $get_security, 'tpae-whats-new' ) ) {
				wp_send_json_error( __( 'Security check failed', 'tpebl' ) );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'You are not allowed to do this action', 'tpebl' ) );
			}

			update_option( self::OPTION, $this->tp_release(), false );

			wp_send_json_success();
		}


/** Function theplus_nexter_extension_dismiss_promo() called by wp_ajax hooks: {'theplus_nexter_extension_dismiss_promo'} **/
/** Parameters found in function theplus_nexter_extension_dismiss_promo(): {"post": ["security", "type"]} **/
function theplus_nexter_extension_dismiss_promo() {
			$get_security = ! empty( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '';

			if ( ! isset( $get_security ) || empty( $get_security ) || ! wp_verify_nonce( $get_security, 'theplus-nexter-extension' ) ) {
				die( 'Security checked!' );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'You are not allowed to do this action', 'tpebl' ) );
				wp_die();
			}

			$get_type = ! empty( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';

			update_option( 'tpae_nexter_extension_notice', true, false );

			wp_send_json_success();
		}


/** Function tpae_form_submission() called by wp_ajax hooks: {'nopriv_tpae_form_submission', 'tpae_form_submission'} **/
/** Parameters found in function tpae_form_submission(): {"post": ["email_data", "form_data", "form_fields"]} **/
function tpae_form_submission() {
			$result['success'] = 0;

			ob_start();

			$email_data = isset( $_POST['email_data'] ) ? sanitize_text_field( wp_unslash( $_POST['email_data'] ) ) : '';
			if ( empty( $email_data ) ) {
				ob_end_clean();
				wp_send_json_error( array( 'message' => 'Missing form data.' ) );
				wp_die();
			}

			$email_data = L_tp_plus_simple_decrypt( $email_data, 'dy' );
			$email_data = is_string( $email_data ) ? json_decode( $email_data, true ) : null;
			if ( ! is_array( $email_data ) ) {
				ob_end_clean();
				wp_send_json_error( array( 'message' => 'Invalid form data.' ) );
				wp_die();
			}

			$nonce = isset( $email_data['nonce'] ) ? sanitize_text_field( $email_data['nonce'] ) : '';
			if ( ! wp_verify_nonce( $nonce, 'tp-form-nonce' ) ) {
				$result['message'] = 'Nonce verification failed.';
				wp_send_json( $result );
			}

			/*
			 * Checked here deliberately: after the envelope and nonce have been validated, so
			 * malformed traffic cannot consume a real visitor's allowance, and before any mail
			 * is prepared, so a throttled request costs nothing.
			 */
			if ( ! $this->tpae_within_rate_limit() ) {
				ob_end_clean();
				$result['message'] = 'Too many submissions. Please try again later.';
				wp_send_json( $result );
			}

			$form_data_raw = isset( $_POST['form_data'] ) ? wp_unslash( $_POST['form_data'] ) : '';
			$form_data     = is_string( $form_data_raw ) ? json_decode( $form_data_raw, true ) : null;
			if ( ! is_array( $form_data ) ) {
				$form_data = array();
			}

			$form_fields_raw = isset( $_POST['form_fields'] ) ? wp_unslash( $_POST['form_fields'] ) : '';
			$form_fields     = is_string( $form_fields_raw ) ? json_decode( $form_fields_raw, true ) : null;

			if ( ! isset( $form_fields ) || ! is_array( $form_fields ) ) {
				$result['message'] = 'form_fields data is missing or not an array.';
				wp_send_json( $result );
			}

			foreach ( $form_data as $key => $value ) {
				if ( is_array( $value ) ) {
					$form_fields[ $key ] = implode( ', ', array_map( 'sanitize_text_field', $value ) );
				} else {
					$form_fields[ $key ] = sanitize_text_field( $value );
				}
			}

			foreach ( $form_data as $key => $value ) {
				if ( strpos( $key, 'required' ) !== false && 'yes' === $value && empty( $form_data[ str_replace( '_required', '', $key ) ] ) ) {
					$result['message'] = ucfirst( str_replace( '_required', '', $key ) ) . ' is required.';
					wp_send_json( $result );
				}
			}

			$redirection = ! empty( $email_data['redirection'] ) ? $email_data['redirection'] : null;

			$redirection_url         = isset( $redirection['url'] ) ? esc_url_raw( $redirection['url'] ) : '';
			$is_external_redirection = isset( $redirection['is_external'] ) ? $redirection['is_external'] : false;

			// Block dangerous schemes and bound same-site redirects to the current host.
			if ( '' !== $redirection_url ) {
				$scheme = strtolower( (string) wp_parse_url( $redirection_url, PHP_URL_SCHEME ) );
				if ( ! in_array( $scheme, array( 'http', 'https' ), true ) ) {
					$redirection_url = '';
				} elseif ( empty( $is_external_redirection ) ) {
					$redirection_url = wp_validate_redirect( $redirection_url, '' );
				}
			}

			$email_sent = false;

			$email_subject = ! empty( $email_data['email_subject'] ) ? sanitize_text_field( $email_data['email_subject'] ) : '';

			if ( ! empty( $email_data ) && ! empty( $email_subject ) ) {
				$email_settings = $this->tpae_prepare_email_settings( $email_data, $form_data, $form_fields );
				$email_sent     = $this->tpae_send_email( $email_settings );
			}

			$result = array(
				'success' => 1,
				'data'    => array(
					'email_sent'  => $email_sent,
					'redirection' => array(
						'url'         => $redirection_url,
						'is_external' => $is_external_redirection,
					),
				),
				'message' => 'Email sent successfully.',
			);

			wp_send_json( $result );
		}


/** Function L_theplus_more_post_ajax() called by wp_ajax hooks: {'nopriv_L_theplus_more_post', 'L_theplus_more_post'} **/
/** Parameters found in function L_theplus_more_post_ajax(): {"post": ["loadattr", "paged", "offset"]} **/
function L_theplus_more_post_ajax() {
			global $post;
			ob_start();

			$load_attr = isset( $_POST['loadattr'] ) ? sanitize_text_field( wp_unslash( $_POST['loadattr'] ) ) : '';
			if ( empty( $load_attr ) ) {
				ob_get_contents();
				exit;
				ob_end_clean();
			}

			$load_attr = L_tp_plus_simple_decrypt( $load_attr, 'dy' );
			$load_attr = json_decode( $load_attr, true );
			if ( ! is_array( $load_attr ) ) {
				ob_get_contents();
				exit;
				ob_end_clean();
			}

			$nonce = ( isset( $load_attr['theplus_nonce'] ) ) ? wp_unslash( $load_attr['theplus_nonce'] ) : '';
			if ( ! wp_verify_nonce( $nonce, 'theplus-addons' ) ) {
				die( 'Security checked!' );
			}

			$paged  = isset( $_POST['paged'] ) ? intval( wp_unslash( $_POST['paged'] ) ) : '';
			$offset = isset( $_POST['offset'] ) ? intval( wp_unslash( $_POST['offset'] ) ) : '';
			$layout = isset( $load_attr['layout'] ) ? sanitize_text_field( wp_unslash( $load_attr['layout'] ) ) : '';

			$category  = isset( $load_attr['category'] ) ? wp_unslash( $load_attr['category'] ) : '';
			$post_type = isset( $load_attr['post_type'] ) ? sanitize_key( $load_attr['post_type'] ) : '';

			$tp_pto = $post_type ? get_post_type_object( $post_type ) : null;
			if ( ! $tp_pto || empty( $tp_pto->public ) ) {
				wp_die( '', '', array( 'response' => 400 ) );
			}

			$post_load = isset( $load_attr['load'] ) ? sanitize_text_field( wp_unslash( $load_attr['load'] ) ) : '';
			$order_by  = isset( $load_attr['order_by'] ) ? sanitize_text_field( wp_unslash( $load_attr['order_by'] ) ) : '';
			$post_tags = isset( $load_attr['post_tags'] ) ? wp_unslash( $load_attr['post_tags'] ) : '';

			$display_post = isset( $load_attr['display_post'] ) && intval( $load_attr['display_post'] ) ? wp_unslash( $load_attr['display_post'] ) : 4;
			$post_authors = isset( $load_attr['post_authors'] ) ? wp_unslash( $load_attr['post_authors'] ) : '';
			$post_order   = isset( $load_attr['post_order'] ) ? sanitize_text_field( wp_unslash( $load_attr['post_order'] ) ) : '';

			$include_posts = isset( $load_attr['include_posts'] ) ? sanitize_text_field( wp_unslash( $load_attr['include_posts'] ) ) : '';
			$exclude_posts = isset( $load_attr['exclude_posts'] ) ? sanitize_text_field( wp_unslash( $load_attr['exclude_posts'] ) ) : '';

			$desktop_column = isset( $load_attr['desktop-column'] ) && intval( $load_attr['desktop-column'] ) ? wp_unslash( $load_attr['desktop-column'] ) : '';
			$tablet_column  = isset( $load_attr['tablet-column'] ) && intval( $load_attr['tablet-column'] ) ? wp_unslash( $load_attr['tablet-column'] ) : '';
			$mobile_column  = isset( $load_attr['mobile-column'] ) && intval( $load_attr['mobile-column'] ) ? wp_unslash( $load_attr['mobile-column'] ) : '';

			$style        = isset( $load_attr['style'] ) ? sanitize_text_field( wp_unslash( $load_attr['style'] ) ) : '';
			$style_layout = isset( $load_attr['style_layout'] ) ? sanitize_text_field( wp_unslash( $load_attr['style_layout'] ) ) : '';

			$filter_category  = isset( $load_attr['filter_category'] ) ? wp_unslash( $load_attr['filter_category'] ) : '';
			$animated_columns = isset( $load_attr['animated_columns'] ) ? sanitize_text_field( wp_unslash( $load_attr['animated_columns'] ) ) : '';
			$post_load_more   = isset( $load_attr['post_load_more'] ) && intval( $load_attr['post_load_more'] ) ? wp_unslash( $load_attr['post_load_more'] ) : '';

			$metro_column = isset( $load_attr['metro_column'] ) ? wp_unslash( $load_attr['metro_column'] ) : '';
			$metro_style  = isset( $load_attr['metro_style'] ) ? wp_unslash( $load_attr['metro_style'] ) : '';

			$texonomy_category = isset( $load_attr['texonomy_category'] ) ? sanitize_text_field( wp_unslash( $load_attr['texonomy_category'] ) ) : '';

			$tablet_metro_column = isset( $load_attr['tablet_metro_column'] ) ? wp_unslash( $load_attr['tablet_metro_column'] ) : '';
			$tablet_metro_style  = isset( $load_attr['tablet_metro_style'] ) ? wp_unslash( $load_attr['tablet_metro_style'] ) : '';

			$responsive_tablet_metro = isset( $load_attr['responsive_tablet_metro'] ) ? wp_unslash( $load_attr['responsive_tablet_metro'] ) : '';

			$post_title_tag     = isset( $load_attr['post_title_tag'] ) ? wp_unslash( $load_attr['post_title_tag'] ) : '';
			$display_post_title = isset( $load_attr['display_post_title'] ) ? wp_unslash( $load_attr['display_post_title'] ) : '';

			$author_prefix = isset( $load_attr['author_prefix'] ) ? wp_unslash( $load_attr['author_prefix'] ) : '';
			$feature_image = isset( $load_attr['feature_image'] ) ? wp_unslash( $load_attr['feature_image'] ) : '';

			$dpc_all = isset( $load_attr['dpc_all'] ) ? wp_unslash( $load_attr['dpc_all'] ) : '';

			$display_excerpt   = isset( $load_attr['display_excerpt'] ) ? wp_unslash( $load_attr['display_excerpt'] ) : '';
			$display_post_meta = isset( $load_attr['display_post_meta'] ) ? wp_unslash( $load_attr['display_post_meta'] ) : '';

			$post_excerpt_count  = isset( $load_attr['post_excerpt_count'] ) ? wp_unslash( $load_attr['post_excerpt_count'] ) : '';
			$post_meta_tag_style = isset( $load_attr['post_meta_tag_style'] ) ? wp_unslash( $load_attr['post_meta_tag_style'] ) : '';
			$post_category_style = isset( $load_attr['post_category_style'] ) ? wp_unslash( $load_attr['post_category_style'] ) : '';

			$title_desc_word_break = isset( $load_attr['title_desc_word_break'] ) ? wp_unslash( $load_attr['title_desc_word_break'] ) : '';
			$display_post_category = isset( $load_attr['display_post_category'] ) ? wp_unslash( $load_attr['display_post_category'] ) : '';

			if ( 'blogs' === $post_load ) {
                $content_html = isset( $load_attr['content_html'] ) ? wp_unslash( $load_attr['content_html'] ) : '';
            }

			$desktop_class = '';
			$tablet_class  = '';
			$mobile_class  = '';

			if ( 'carousel' !== $layout && 'metro' !== $layout ) {
				$desktop_class = 'tp-col-lg-' . esc_attr( $desktop_column );
				$tablet_class  = 'tp-col-md-' . esc_attr( $tablet_column );
				$mobile_class  = 'tp-col-sm-' . esc_attr( $mobile_column );
				$mobile_class .= ' tp-col-' . esc_attr( $mobile_column );
			}

			$j = 1;

			$args = array(
				'post_type'      => $post_type,
				'posts_per_page' => $post_load_more,
				'offset'         => $offset,
				'orderby'        => $order_by,
				'post_status'    => 'publish',
				'order'          => $post_order,
			);

			/**
			 * `$texonomy_category` used to be interpolated straight in as an array *key*, which
			 * would normally allow injecting arbitrary WP_Query arguments. It was not exploitable
			 * — `$load_attr` is AES-256-GCM decrypted, so the value can only be what a page author
			 * configured — but the safety of `'post_status' => 'publish'` depended entirely on it
			 * appearing later in the array literal than the dynamic key.
			 *
			 * Now the key must name something legitimate ('cat' from the blog listing, or a real
			 * taxonomy as passed by the Pro widgets) and may never overwrite an argument already
			 * set above, so the guarantee no longer rests on literal ordering.
			 *
			 * @since 6.5.0
			 */
			if ( '' !== $texonomy_category
				&& ! empty( $category )
				&& ( 'cat' === $texonomy_category || taxonomy_exists( $texonomy_category ) )
				&& ! array_key_exists( $texonomy_category, $args )
			) {
				$args[ $texonomy_category ] = $category;
			}

			if ( '' !== $exclude_posts ) {
				$exclude_posts        = explode( ',', $exclude_posts );
				$args['post__not_in'] = $exclude_posts;
			}
			if ( '' !== $include_posts ) {
				$include_posts    = explode( ',', $include_posts );
				$args['post__in'] = $include_posts;
			}

			if ( '' !== $post_tags && 'post' === $post_type ) {
				$post_tags         = explode( ',', $post_tags );
				$args['tax_query'] = array(
					'relation' => 'AND',
					array(
						'taxonomy'         => 'post_tag',
						'terms'            => $post_tags,
						'field'            => 'term_id',
						'operator'         => 'IN',
						'include_children' => true,
					),
				);
			}

			if ( '' !== $post_authors && 'post' === $post_type ) {
				$args['author'] = $post_authors;
			}

			$ji = ( $post_load_more * $paged ) - $post_load_more + $display_post + 1;
			$ij = '';

			$tablet_metro_class = '';
			$tablet_ij          = '';

			$loop = new WP_Query( $args );
			if ( $loop->have_posts() ) :
				while ( $loop->have_posts() ) {
					$loop->the_post();

					if ( 'blogs' === $post_load ) {
						include L_THEPLUS_WSTYLES . 'blog/ajax-load-post/blog-style.php';
					}
					++$ji;
				}

				$content = ob_get_contents();

				ob_end_clean();
				endif;
			wp_reset_postdata();

			echo $content;

			exit;
			ob_end_clean();
		}


/** Function theplus_blocks_dismiss_promo() called by wp_ajax hooks: {'theplus_blocks_dismiss_promo'} **/
/** Parameters found in function theplus_blocks_dismiss_promo(): {"post": ["security", "type"]} **/
function theplus_blocks_dismiss_promo() {
			$get_security = ! empty( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '';

			if ( ! isset( $get_security ) || empty( $get_security ) || ! wp_verify_nonce( $get_security, 'theplus-addons-tpag-blocks' ) ) {
				die( 'Security checked!' );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'You are not allowed to do this action', 'tpebl' ) );
				wp_die();
			}

			$get_type = ! empty( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';

			update_option( 'tpae_nexter_block_notice', true, false );

			wp_send_json_success();
		}


/** Function theplus_join_community_notice_dismiss() called by wp_ajax hooks: {'theplus_joincom_notice_dismiss'} **/
/** Parameters found in function theplus_join_community_notice_dismiss(): {"post": ["security", "type"]} **/
function theplus_join_community_notice_dismiss() {
			$get_security = ! empty( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '';

			if ( ! isset( $get_security ) || empty( $get_security ) || ! wp_verify_nonce( $get_security, 'tpae-join-community' ) ) {
				die( 'Security checked!' );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'You are not allowed to do this action', 'tpebl' ) );
				wp_die();
			}

			$get_type = ! empty( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';

			update_option( 'tpae_join_community_notice', true, false );

			wp_send_json_success();
		}


/** Function tpae_dismiss_dynamic_notice() called by wp_ajax hooks: {'tpae_dismiss_dynamic_notice'} **/
/** No function found :-/ **/


/** Function tpae_dashboard_ajax_call() called by wp_ajax hooks: {'tpae_dashboard_ajax_call'} **/
/** Parameters found in function tpae_dashboard_ajax_call(): {"post": ["type"]} **/
function tpae_dashboard_ajax_call() {

			if ( ! check_ajax_referer( 'tpae-db-nonce', 'nonce', false ) ) {

				$response = $this->tpae_set_response( false, 'Invalid nonce.', 'The security check failed. Please refresh the page and try again.' );

				wp_send_json( $response );
				wp_die();
			}

			if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
				$response = $this->tpae_set_response( false, 'Invalid Permission.', 'Something went wrong.' );

				wp_send_json( $response );
				wp_die();
			}

			$type = isset( $_POST['type'] ) ? strtolower( sanitize_text_field( wp_unslash( $_POST['type'] ) ) ) : false;
			if ( ! $type ) {
				$response = $this->tpae_set_response( false, 'Invalid type.', 'Something went wrong.' );

				wp_send_json( $response );
				wp_die();
			}

			switch ( $type ) {
				case 'tpae_onload_data':
					$response = $this->tpae_onload_data();
					break;
				case 'tpae_set_widget_list':
					$response = $this->tpae_set_widget_list();
					break;
				case 'tpae_get_scan_widgets':
					$response = $this->tpae_get_elements_status_scan();
					break;
				case 'tpae_get_scan_extension':
					$response = $this->tpae_get_extension_status_scan();
					break;
				case 'tpae_set_extra_options':
					$response = $this->tpae_set_extra_options();
					break;
				case 'tpae_get_custom_css_js':
					$response = $this->tpae_get_custom_css_js();
					break;
				case 'tpae_set_custom_css_js':
					$response = $this->tpae_set_custom_css_js();
					break;
				case 'tpae_set_listing_data':
					$response = $this->tpae_set_listing_data();
					break;
				case 'tpae_prev_version':
					$response = $this->tpae_prev_version();
					break;
				case 'tpae_rollback_check':
					$response = $this->tpae_rollback_check();
					break;
				case 'tpae_performance_manage':
					$response = $this->tpae_performance_manage();
					break;
				case 'tpae_plugin_install':
					$response = $this->tpae_plugin_install();
					break;
				case 'tpae_theme_install':
					$response = $this->tpae_theme_install();
					break;
				case 'tpae_api_call':
					$response = $this->tpae_api_call();
					break;
				case 'tpae_transient_manage':
					$response = $this->tpae_transient_manage();
					break;
				case 'tpae_wp_option_manage':
					$response = $this->tpae_wp_option_manage();
					break;
				case 'tpae_update_wdk_widget':
					$response = apply_filters( 'wdk_widget_ajax_call', 'wdk_update_widget' );
					break;
				case 'tpae_license_manage':
					$response = apply_filters( 'tpaep_licence_ajax_call', 'tpaep_license_manage' );
					break;
				case 'set_whitelabel':
					$response = apply_filters( 'tpaep_dashboard_ajax_call', 'tpaep_set_whitelabel' );
					break;
				case 'tpae_widgets_setting_data':
					$response = $this->tpae_widgets_setting_data();

					break;
				case 'tpae_onboarding_setup':
					$response = $this->tpae_onboarding_setup();
					break;
				case 'tpae_user_meta_data':
					$response = $this->tpae_user_meta_data();
					break;
				case 'tpae_analytics_consent':
					$response = $this->tpae_analytics_consent();
					break;
				case 'tpae_whats_new_close':
					$response = $this->tpae_whats_new_close();
					break;
			}

			wp_send_json( $response );
			wp_die();
		}


/** Function theplus_pro_promo_notice_dismiss() called by wp_ajax hooks: {'theplus_pro_dismiss_promo'} **/
/** Parameters found in function theplus_pro_promo_notice_dismiss(): {"post": ["security", "type"]} **/
function theplus_pro_promo_notice_dismiss() {
			$get_security = ! empty( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '';

			if ( ! isset( $get_security ) || empty( $get_security ) || ! wp_verify_nonce( $get_security, 'tpae-pro-notice' ) ) {
				die( 'Security checked!' );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'You are not allowed to do this action', 'tpebl' ) );
				wp_die();
			}

			$get_type = ! empty( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';

			update_option( 'tpae_pro_promo_notice', true, false );

			wp_send_json_success();
		}


/** Function theplus_ask_review_notice_dismiss() called by wp_ajax hooks: {'theplus_askreview_notice_dismiss'} **/
/** Parameters found in function theplus_ask_review_notice_dismiss(): {"post": ["security", "type"]} **/
function theplus_ask_review_notice_dismiss() {
			$get_security = ! empty( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '';

			if ( ! isset( $get_security ) || empty( $get_security ) || ! wp_verify_nonce( $get_security, 'tpae-ask-review' ) ) {
				die( esc_html__( 'Security checked!', 'tpebl' ) );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'You are not allowed to do this action', 'tpebl' ) );
				wp_die();
			}

			$get_type = ! empty( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';

			if ( 'tpae_review_notice_later' === $get_type ) {
				$saved_time = current_time( 'mysql' );
				update_option( 'tpae_review_show_later', $saved_time, false );
			} elseif ( 'tpae_ask_review_notice' === $get_type ) {
				update_option( 'tpae_ask_review_notice', true, false );
			}

			wp_send_json_success();
		}


/** Function tpae_template_create() called by wp_ajax hooks: {'tpae_template_create'} **/
/** Parameters found in function tpae_template_create(): {"post": ["security"]} **/
function tpae_template_create() {

			/** Security checked wp nonce*/
			$nonce = ! empty( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '';
			if ( ! isset( $nonce ) || empty( $nonce ) || ! wp_verify_nonce( $nonce, 'live_editor' ) ) {
				die( 'Security checked!' );
			}

			/** Security checked user login*/
			if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( array( 'content' => __( 'Insufficient permissions.', 'tpebl' ) ) );
			}

			$uniq       = uniqid();
			$rand_num   = wp_rand( 1, 1000 );
			$post_name  = 'tp-create-template-' . sanitize_text_field( wp_unslash( $uniq ) );
			$post_title = '';

			$args = array(
				'post_type'              => 'elementor_library',
				'post_status'            => 'publish',
				'name'                   => $post_name,
				'posts_per_page'         => 1,
				'update_post_term_cache' => false,
				'update_post_meta_cache' => false,
			);
			$post = get_posts( $args );

			if ( empty( $post ) ) {
				$post_title = 'TP Template ' . $rand_num;

				$params = array(
					'post_content' => '',
					'post_type'    => 'elementor_library',
					'post_title'   => $post_title,
					'post_name'    => $post_name,
					'post_status'  => 'publish',
					'meta_input'   => array(
						'_elementor_edit_mode'     => 'builder',
						'_elementor_template_type' => 'page',
						'_wp_page_template'        => 'elementor_canvas',
					),
				);

				$post_id = wp_insert_post( $params );

				if ( ! current_user_can( 'edit_post', $post_id ) ) {
					wp_delete_post( $post_id, true );
					wp_send_json_error( array( 'content' => __( 'Permission denied.', 'tpebl' ) ) );
				}

			} else {
				$post_id    = $post[0]->ID;
				$post_title = $post[0]->post_title;
			}

			$temp_url = get_admin_url() . '/post.php?post=' . $post_id . '&action=elementor';

			$result = array(
				'url'   => $temp_url,
				'id'    => $post_id,
				'title' => $post_title,
			);

			wp_send_json_success( $result );
		}


/** Function tpae_pluginfeatures_notice_dismiss() called by wp_ajax hooks: {'tpae_pluginfeatures_notice_dismiss'} **/
/** Parameters found in function tpae_pluginfeatures_notice_dismiss(): {"post": ["security", "type"]} **/
function tpae_pluginfeatures_notice_dismiss() {
			$get_security = ! empty( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '';

			if ( ! $get_security || ! wp_verify_nonce( $get_security, 'tpae-pluginfeatures-banner' ) ) {
				wp_send_json_error( 'Security check failed!' );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'You are not allowed to perform this action', 'tpebl' ) );
			}

			$get_type = ! empty( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';

			update_option( 'tpae_pluginfeatures_notice_dismissed', true, false );

			wp_send_json_success();
		}


/** Function theplus_removed_widgets_notice_dismiss() called by wp_ajax hooks: {'theplus_removed_widgets_notice_dismiss'} **/
/** Parameters found in function theplus_removed_widgets_notice_dismiss(): {"post": ["security"]} **/
function theplus_removed_widgets_notice_dismiss() {
			$get_security = ! empty( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '';

			if ( ! isset( $get_security ) || empty( $get_security ) || ! wp_verify_nonce( $get_security, 'tpae-removed-widgets' ) ) {
				die( esc_html__( 'Security checked!', 'tpebl' ) );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'You are not allowed to do this action', 'tpebl' ) );
			}

			update_option( 'tpae_removed_widgets_notice', true );

			wp_send_json_success();
		}


/** Function tpae_cross_copy_paste_media_import() called by wp_ajax hooks: {'plus_cross_cp_import'} **/
/** Parameters found in function tpae_cross_copy_paste_media_import(): {"post": ["copy_content"]} **/
function tpae_cross_copy_paste_media_import() {

			check_ajax_referer( 'plus_cross_cp_import', 'nonce' );

			if ( ! current_user_can( 'edit_posts' ) || ! current_user_can( 'upload_files' ) ) {
				wp_send_json_error( __( 'Not a Valid', 'tpebl' ), 403 );
			}

			$media_import = isset( $_POST['copy_content'] ) ? wp_unslash( $_POST['copy_content'] ) : '';

			if ( empty( $media_import ) ) {
				wp_send_json_error( __( 'Empty Content.', 'tpebl' ) );
			}

			$decoded = json_decode( $media_import, true );
			if ( ! is_array( $decoded ) ) {
				wp_send_json_error( __( 'Invalid payload.', 'tpebl' ) );
			}

			$media_import = array( $decoded );
			$media_import = self::tp_elements_id_change( $media_import );
			$media_import = self::tp_import_media_copy_content( $media_import );

			wp_send_json_success( $media_import );
		}


/** Function tp_install_wdkit() called by wp_ajax hooks: {'tp_install_wdkit'} **/
/** No params detected :-/ **/


/** Function tpae_create_page() called by wp_ajax hooks: {'tpae_create_page'} **/
/** Parameters found in function tpae_create_page(): {"post": ["post_type", "page_type", "page_name"]} **/
function tpae_create_page() {

			check_ajax_referer( 'tp_nxt_install', 'security' );

			$post_type = isset( $_POST['post_type'] ) ? sanitize_key( $_POST['post_type'] ) : 'elementor_library';
			$page_type = isset( $_POST['page_type'] ) ? sanitize_key( wp_unslash( $_POST['page_type'] ) ) : 'tp_header';
			$page_name = isset( $_POST['page_name'] ) ? sanitize_text_field( wp_unslash( $_POST['page_name'] ) ) : 'theplus-addon';

			$allowed_post_types = array( 'elementor_library', 'nxt_builder' );
			if ( ! in_array( $post_type, $allowed_post_types, true ) ) {
				$response = $this->tpae_response( 'Invalid Post Type', 'The selected post type is not allowed.', false );

				wp_send_json( $response );
				wp_die();
			}

			$allowed_page_types = apply_filters(
				'tpae_create_page_allowed_page_types',
				array( 'header', 'singular', 'archives', 'archive', 'single-page', 'product' )
			);
			if ( ! in_array( $page_type, $allowed_page_types, true ) ) {
				$response = $this->tpae_response( 'Invalid Page Type', 'The selected page type is not allowed.', false );

				wp_send_json( $response );
				wp_die();
			}

			$post_type_object = get_post_type_object( $post_type );
			if ( ! $post_type_object ) {
				$response = $this->tpae_response( 'Post Type Not Found', 'The requested post type does not exist.', false );

				wp_send_json( $response );
				wp_die();
			}

			if ( ! current_user_can( $post_type_object->cap->create_posts ) ) {
				$response = $this->tpae_response( 'Permission Denied', 'You do not have permission to create this content.', false );

				wp_send_json( $response );
				wp_die();
			}

			$post_id = wp_insert_post( 
				array(
					'post_type'   => $post_type,
					'post_title'  => $page_name,
					'post_status' => 'draft',
				) 
			);

			if ( is_wp_error( $post_id ) ) {
				$response = $this->tpae_response( 'Creation Failed', 'Failed to create the post. Please try again.', false );

				wp_send_json( $response );
				wp_die();
			}

			if ( $post_type === 'nxt_builder' ) {
				update_post_meta( $post_id, 'template_type', $page_type );
				update_post_meta( $post_id, 'nxt-hooks-layout-sections', $page_type );
			} elseif ( $post_type === 'elementor_library' ) {
				update_post_meta( $post_id, '_elementor_template_type', $page_type );
			}

			$elementor_edit_url = admin_url( 'post.php?post=' . $post_id . '&action=elementor' );

			$response = $this->tpae_response(
				'Page Created Successfully', 'Your template has been created successfully.',
				true,
				array(
					'post_id'  => $post_id,
					'edit_url' => $elementor_edit_url,
				)
			);

			wp_send_json( $response );
			wp_die();
		}


/** Function tpae_install_wdkit() called by wp_ajax hooks: {'tpae_install_wdkit'} **/
/** No params detected :-/ **/


