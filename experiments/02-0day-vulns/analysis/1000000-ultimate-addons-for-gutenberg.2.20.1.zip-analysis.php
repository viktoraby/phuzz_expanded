<?php
/***
*
*Found actions: 59
*Found functions:49
*Extracted functions:47
*Total parameter names extracted: 33
*Overview: {'render_masonry_pagination': {'nopriv_uag_load_image_gallery_masonry', 'uag_load_image_gallery_masonry'}, 'masonry_pagination': {'nopriv_uagb_get_posts', 'uagb_get_posts'}, 'track_first_pattern_imported': {'ast_block_templates_import_block'}, 'template_importer': {'ast_block_templates_kit_importer', 'ast_block_templates_importer'}, 'api_request': {'ast_block_templates_data_option'}, 'handle_migration_action_ajax': {'uag_migrate'}, 'skip_spectra_pro_onboarding': {'ast_skip_zip_ai_onboarding'}, 'sync_via_ajax': {'ast-block-templates-check-sync-library-status'}, 'ajax_import_blocks': {'ast-block-templates-import-blocks'}, 'get_fresh_credit_details': {'get_fresh_credit_details'}, 'activate_plugin': {'ast_block_templates_activate_plugin'}, 'verify_authenticity': {'verify_zip_ai_authenticity'}, 'process_forms': {'uagb_process_forms', 'nopriv_uagb_process_forms'}, 'uag_global_sidebar_enabled': {'uag_global_sidebar_enabled'}, 'generate_ai_content': {'ast-block-templates-regenerate'}, 'wp_ajax_install_plugin': {'uagb_recommended_plugin_install'}, 'dismiss_notice': {'astra-notice-dismiss'}, 'send_plugin_deactivate_feedback': {'uds_plugin_deactivate_feedback'}, 'sureforms_plugin_activator': {'uagb_sureforms'}, 'check_migration_status': {'check_migration_status'}, 'get_taxonomy': {'uagb_get_taxonomy'}, 'wp_ajax_install_theme': {'uagb_recommended_theme_install'}, 'import_template_kit': {'ast_block_templates_import_template_kit'}, 'surecart_plugin_activator': {'uagb_surecart'}, 'disabler_ajax': {'zip_ai_disabler_ajax'}, 'reset_business_details': {'ast-block-templates-reset-business-details'}, 'import_block': {'ast_block_templates_import_block'}, 'uag_global_block_styles': {'uag_global_block_styles'}, 'gf_shortcode': {'nopriv_uagb_gf_shortcode', 'uagb_gf_shortcode'}, 'ajax_import_sites': {'ast-block-templates-import-sites'}, 'hide_notices': {'ast_block_templates_hide_notices'}, 'uagb_activate_addon': {'uagb_recommended_plugin_activate'}, 'get_latest_credit_details': {'get_latest_credit_details'}, 'save_user_details': {'ast-block-templates-ai-content'}, 'post_grid_pagination_ajax_callback': {'uagb_post_pagination_grid', 'nopriv_uagb_post_pagination_grid'}, 'cf7_shortcode': {'uagb_cf7_shortcode', 'nopriv_uagb_cf7_shortcode'}, 'render_grid_pagination': {'nopriv_uag_load_image_gallery_grid_pagination', 'uag_load_image_gallery_grid_pagination'}, 'track_first_template_imported': {'ast_block_templates_import_template_kit', 'ast_block_templates_importer'}, 'track_design_library_opened': {'uagb_track_design_library_opened'}, 'import_wpforms': {'ast_block_templates_import_wpforms'}, 'post_pagination': {'uagb_post_pagination', 'nopriv_uagb_post_pagination'}, 'get_color_palette': {'ast_block_templates_color_palette'}, 'ajax_sites_requests_count': {'ast-block-templates-get-sites-request-count'}, 'confirm_svg_upload': {'uagb_svg_confirmation'}, 'uag_global_update_allowed_block': {'uag_global_update_allowed_block'}, 'toggle_assistant_status_ajax': {'zip_ai_toggle_assistant_status_ajax'}, 'forms_recaptcha': {'uagb_forms_recaptcha'}, 'update_popup_status': {'uag_update_popup_status'}, 'zipwp_insert_image': {'zipwp_images_insert_image'}}
*
***/

/** Function render_masonry_pagination() called by wp_ajax hooks: {'nopriv_uag_load_image_gallery_masonry', 'uag_load_image_gallery_masonry'} **/
/** Parameters found in function render_masonry_pagination(): {"post": ["attr", "page_number"]} **/
function render_masonry_pagination() {
			check_ajax_referer( 'uagb_image_gallery_masonry_ajax_nonce', 'nonce' );
			$media_atts = array();
			// sanitizing $attr elements in later stage.
			$attr = isset( $_POST['attr'] ) ? json_decode( wp_unslash( $_POST['attr'] ), true ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			if ( ! is_array( $attr ) ) {
				$attr = array();
			}
			$attr['gridPageNumber']     = isset( $_POST['page_number'] ) ? sanitize_text_field( $_POST['page_number'] ) : '';
			$media_atts                 = $this->required_atts( $attr );
			$media_atts['mediaGallery'] = json_decode( $media_atts['mediaGallery'], true );
			$media                      = $this->get_gallery_images( $media_atts, 'paginated' );
			if ( ! $media ) {
				wp_send_json_error();
			}
			foreach ( $attr as $key => $attribute ) {
				$attr[ $key ] = ( 'false' === $attribute ) ? false : ( ( 'true' === $attribute ) ? true : $attribute );
			}
			$htmlArray = $this->render_media_markup( $media, $attr );
			wp_send_json_success( $htmlArray );
		}


/** Function masonry_pagination() called by wp_ajax hooks: {'nopriv_uagb_get_posts', 'uagb_get_posts'} **/
/** Parameters found in function masonry_pagination(): {"post": ["attr", "page_number"]} **/
function masonry_pagination() {

			check_ajax_referer( 'uagb_masonry_ajax_nonce', 'nonce' );

			$post_attribute_array = array();
			// $_POST['attr'] is sanitized in later stage.
			$attr = isset( $_POST['attr'] ) ? json_decode( wp_unslash( $_POST['attr'] ), true ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			if ( ! is_array( $attr ) ) {
				$attr = array();
			}

			$attr['paged'] = isset( $_POST['page_number'] ) ? sanitize_text_field( $_POST['page_number'] ) : '';

			$post_attribute_array = $this->required_attribute_for_query( $attr );

			$query = UAGB_Helper::get_query( $post_attribute_array, 'masonry' );

			foreach ( $attr as $key => $attribute ) {
				$attr[ $key ] = ( 'false' === $attribute ) ? false : ( ( 'true' === $attribute ) ? true : $attribute );
			}

			ob_start();
			$this->posts_articles_markup( $query, $attr );
			$html = ob_get_clean();

			wp_send_json_success( $html );
		}


/** Function track_first_pattern_imported() called by wp_ajax hooks: {'ast_block_templates_import_block'} **/
/** No params detected :-/ **/


/** Function template_importer() called by wp_ajax hooks: {'ast_block_templates_kit_importer', 'ast_block_templates_importer'} **/
/** Parameters found in function template_importer(): {"request": ["id"]} **/
function template_importer() {

		if ( ! current_user_can( 'manage_ast_block_templates' ) ) {
			wp_send_json_error( __( 'You are not allowed to perform this action', 'ultimate-addons-for-gutenberg' ) );
		}
		// Verify Nonce.
		check_ajax_referer( 'ast-block-templates-ajax-nonce', '_ajax_nonce' );

		$block_id   = isset( $_REQUEST['id'] ) ? absint( $_REQUEST['id'] ) : '';
		$block_data = get_option( 'ast-block-templates_data-' . $block_id );

		$api_uri = null !== $block_data ? $block_data->{'astra-page-api-url'} : '';

		// Early return.
		if ( '' == $api_uri ) {
			wp_send_json_error( __( 'Something wrong', 'ultimate-addons-for-gutenberg' ) );
		}

		$api_args = apply_filters(
			'ast_block_templates_api_args',
			array(
				'timeout' => 15,
			)
		);

		$request_params = apply_filters(
			'ast_block_templates_api_params',
			array(
				'_fields' => 'original_content',
			)
		);

		$demo_api_uri = add_query_arg( $request_params, $api_uri );

		// API Call.
		$response = wp_safe_remote_get( $demo_api_uri, $api_args );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( $response->get_error_message() );
		} elseif ( 'error' !== $response['response']['message'] ) {
			wp_send_json_error( wp_json_encode( $response['response'] ) );
		}


		if ( wp_remote_retrieve_response_code( $response ) !== 200 ) {
			wp_send_json_error( wp_remote_retrieve_body( $response ) );
		}

		$data = json_decode( wp_remote_retrieve_body( $response ), true );

		// Flush the object when import is successful.
		delete_option( 'ast-block-templates_data-' . $block_id );

		wp_send_json_success( $data['original_content'] );
	}


/** Function api_request() called by wp_ajax hooks: {'ast_block_templates_data_option'} **/
/** Parameters found in function api_request(): {"request": ["id", "type"]} **/
function api_request() {

		if ( ! current_user_can( 'manage_ast_block_templates' ) ) {
			wp_send_json_error( __( 'You are not allowed to perform this action', 'ultimate-addons-for-gutenberg' ) );
		}

		// Verify Nonce.
		check_ajax_referer( 'ast-block-templates-ajax-nonce', '_ajax_nonce' );
		$block_id     = isset( $_REQUEST['id'] ) ? absint( $_REQUEST['id'] ) : '';
		$block_type   = isset( $_REQUEST['type'] ) ? sanitize_text_field( $_REQUEST['type'] ) : '';

		if ( 'site-pages' === $block_type ) {
			// Use this for premium pages.
			$request_params = apply_filters(
				'astra_sites_api_params',
				array(
					'purchase_key' => '',
					'site_url'     => site_url(),
				)
			);

			$complete_url = add_query_arg( $request_params, trailingslashit( AST_BLOCK_TEMPLATES_LIBRARY_URL . 'wp-json/wp/v2/' . $block_type . '/' . $block_id ) );
		} else {
			$complete_url = AST_BLOCK_TEMPLATES_LIBRARY_URL . 'wp-json/wp/v2/' . $block_type . '/' . $block_id . '/?site_url=' . site_url();
		}
		$response = wp_safe_remote_get( $complete_url );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( __( 'Something went wrong', 'ultimate-addons-for-gutenberg' ) );
		}

		if ( 200 !== $response['response']['code'] ) {
			wp_send_json_error( __( 'Something went wrong', 'ultimate-addons-for-gutenberg' ) );
		}

		$body = json_decode( wp_remote_retrieve_body( $response ) );
		// Create a dynamic option name to save the block data.
		update_option( 'ast-block-templates_data-' . $block_id, $body );
		wp_send_json_success( $body );
	}


/** Function handle_migration_action_ajax() called by wp_ajax hooks: {'uag_migrate'} **/
/** No params detected :-/ **/


/** Function skip_spectra_pro_onboarding() called by wp_ajax hooks: {'ast_skip_zip_ai_onboarding'} **/
/** No params detected :-/ **/


/** Function sync_via_ajax() called by wp_ajax hooks: {'ast-block-templates-check-sync-library-status'} **/
/** No params detected :-/ **/


/** Function ajax_import_blocks() called by wp_ajax hooks: {'ast-block-templates-import-blocks'} **/
/** Parameters found in function ajax_import_blocks(): {"post": ["page_no", "total"]} **/
function ajax_import_blocks() {

		if ( ! current_user_can( 'manage_ast_block_templates' ) ) {
			wp_send_json_error( __( 'You are not allowed to perform this action', 'ultimate-addons-for-gutenberg' ) );
		}
		// Verify Nonce.
		check_ajax_referer( 'ast-block-templates-ajax-nonce', '_ajax_nonce' );

		$page_no = isset( $_POST['page_no'] ) ? absint( $_POST['page_no'] ) : '';
		if ( $page_no ) {
			$this->import_blocks( $page_no );

			$data = array(
				'message' => 'Success imported sites for page ' . $page_no,
				'status'  => true,
				'data'    => array(),
			);

			if ( isset( $_POST['total'] ) && $_POST['total'] === $_POST['page_no'] ) {
				$data['data']['allBlocks'] = Plugin::instance()->get_all_blocks();
				$data['data']['categories'] = Helper::instance()->get_block_template_category();
			}

			wp_send_json_success(
				$data
			);
		}

		wp_send_json_error(
			array(
				'message' => 'Failed imported blocks for page ' . $page_no,
				'status'  => false,
				'data'    => '',
			)
		);
	}


/** Function get_fresh_credit_details() called by wp_ajax hooks: {'get_fresh_credit_details'} **/
/** No params detected :-/ **/


/** Function activate_plugin() called by wp_ajax hooks: {'ast_block_templates_activate_plugin'} **/
/** Parameters found in function activate_plugin(): {"post": ["init"]} **/
function activate_plugin() {

		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error( __( 'You are not allowed to perform this action.', 'ultimate-addons-for-gutenberg' ) );
		}
		// Verify Nonce.
		check_ajax_referer( 'ast-block-templates-ajax-nonce', 'security' );

		wp_clean_plugins_cache();

		$plugin_init = ( isset( $_POST['init'] ) ) ? sanitize_text_field( $_POST['init'] ) : '';

		$activate = activate_plugin( $plugin_init, '', false, true );

		if ( is_wp_error( $activate ) ) {
			wp_send_json_error( $activate->get_error_message() );
		}

		wp_send_json_success(
			array(
				'message' => 'Plugin activated successfully.',
			)
		);
	}


/** Function verify_authenticity() called by wp_ajax hooks: {'verify_zip_ai_authenticity'} **/
/** No params detected :-/ **/


/** Function process_forms() called by wp_ajax hooks: {'uagb_process_forms', 'nopriv_uagb_process_forms'} **/
/** Parameters found in function process_forms(): {"post": ["post_id", "block_id", "captcha_response", "form_data"], "server": ["REMOTE_ADDR"]} **/
function process_forms() {
			check_ajax_referer( 'uagb_forms_ajax_nonce', 'nonce' );

			$options = array(
				'recaptcha_site_key_v2'   => \UAGB_Admin_Helper::get_admin_settings_option( 'uag_recaptcha_site_key_v2', '' ),
				'recaptcha_site_key_v3'   => \UAGB_Admin_Helper::get_admin_settings_option( 'uag_recaptcha_site_key_v3', '' ),
				'recaptcha_secret_key_v2' => \UAGB_Admin_Helper::get_admin_settings_option( 'uag_recaptcha_secret_key_v2', '' ),
				'recaptcha_secret_key_v3' => \UAGB_Admin_Helper::get_admin_settings_option( 'uag_recaptcha_secret_key_v3', '' ),
			);

			if ( empty( $_POST['post_id'] ) || empty( $_POST['block_id'] ) ) {
				wp_send_json_error( 400 );
			}
			$current_block_attributes = false;
			$block_id                 = sanitize_text_field( $_POST['block_id'] );

			$post_content = get_post_field( 'post_content', sanitize_text_field( $_POST['post_id'] ) );

			if ( has_block( 'uagb/forms', $post_content ) || has_block( 'core/block', $post_content ) ) {
				$blocks = parse_blocks( $post_content );
				if ( ! empty( $blocks ) && is_array( $blocks ) ) {
					$current_block_attributes = $this->recursive_inner_forms( $blocks, $block_id );
				}
			}
			if ( function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() ) {
				$wp_query_args        = array(
					'post_status' => array( 'publish' ),
					'post_type'   => array( 'wp_template', 'wp_template_part' ),
				);
				$template_query       = new WP_Query( $wp_query_args );
				$template_query_posts = $template_query->posts;
				if ( ! empty( $template_query_posts ) && is_array( $template_query_posts ) ) {
					foreach ( $template_query_posts as $post ) {
						if ( ! function_exists( '_build_block_template_result_from_post' ) ) {
							continue;
						}
						$template = _build_block_template_result_from_post( $post );
						if ( is_wp_error( $template ) ) {
							continue;
						}
						$template_post_content = $template->content . ( ! empty( $post_content ) ? $post_content : '' );
						$template_content      = parse_blocks( $template_post_content );
						if ( get_template() === $template->theme && ! empty( $template_content ) && is_array( $template_content ) ) {
							$current_block_attributes = $this->recursive_inner_forms( $template_content, $block_id );
							if ( is_array( $current_block_attributes ) && $current_block_attributes['block_id'] === $block_id ) {
								break;
							}
						}
					}
				}
			}

			$widget_content = get_option( 'widget_block' );
			if ( ! empty( $widget_content ) && is_array( $widget_content ) && empty( $current_block_attributes ) ) {
				foreach ( $widget_content as $value ) {
					if ( ! is_array( $value ) || empty( $value['content'] ) ) {
						continue;
					}
					if ( has_block( 'uagb/forms', $value['content'] ) ) {
						$current_block_attributes = $this->recursive_inner_forms( parse_blocks( $value['content'] ), $block_id );
						if ( is_array( $current_block_attributes ) && $current_block_attributes['block_id'] === $block_id ) {
							break;
						}
					}
				}
			}

			// Check for $current_block_attributes is not set and check for Advanced Hooks.
			if ( empty( $current_block_attributes ) && defined( 'ASTRA_ADVANCED_HOOKS_POST_TYPE' ) ) {

				$option = array(
					'location'  => 'ast-advanced-hook-location',
					'exclusion' => 'ast-advanced-hook-exclusion',
					'users'     => 'ast-advanced-hook-users',
				);

				$result = Astra_Target_Rules_Fields::get_instance()->get_posts_by_conditions( ASTRA_ADVANCED_HOOKS_POST_TYPE, $option );

				if ( ! empty( $result ) && is_array( $result ) ) {
					$post_ids = array_keys( $result );

					foreach ( $post_ids as $post_id ) {

						$custom_post = get_post( $post_id );

						if ( ! $custom_post instanceof WP_Post ) {
							continue;
						}

						$post_content = $custom_post->post_content;
						if ( has_block( 'uagb/forms', $post_content ) ) {
							$blocks = parse_blocks( $post_content );
							if ( ! empty( $blocks ) && is_array( $blocks ) ) {
								$current_block_attributes = $this->recursive_inner_forms( $blocks, $block_id );
								if ( is_array( $current_block_attributes ) && $current_block_attributes['block_id'] === $block_id ) {
									break;
								}
							}
						}
					}
				}
			}

			if ( empty( $current_block_attributes ) ) {
				wp_send_json_error( 400 );
			}
			$admin_email = get_option( 'admin_email' );
			if ( is_array( $current_block_attributes ) ) {
				if ( isset( $current_block_attributes['afterSubmitToEmail'] ) && empty( trim( $current_block_attributes['afterSubmitToEmail'] ) ) && is_string( $admin_email ) ) {
					$current_block_attributes['afterSubmitToEmail'] = sanitize_email( $admin_email );
				}
				if ( ! isset( $current_block_attributes['reCaptchaType'] ) ) {
					$current_block_attributes['reCaptchaType'] = 'v2';
				}
				// bail if recaptcha is enabled and recaptchaType is not set.
				if ( ! empty( $current_block_attributes['reCaptchaEnable'] ) && empty( $current_block_attributes['reCaptchaType'] ) ) {
					wp_send_json_error( 400 );
				}

				if ( 'v2' === $current_block_attributes['reCaptchaType'] ) {

					$google_recaptcha_site_key   = $options['recaptcha_site_key_v2'];
					$google_recaptcha_secret_key = $options['recaptcha_secret_key_v2'];

				} elseif ( 'v3' === $current_block_attributes['reCaptchaType'] ) {

					$google_recaptcha_site_key   = $options['recaptcha_site_key_v3'];
					$google_recaptcha_secret_key = $options['recaptcha_secret_key_v3'];

				}

				if ( ! empty( $current_block_attributes['reCaptchaEnable'] ) && ! empty( $google_recaptcha_secret_key ) && ! empty( $google_recaptcha_site_key ) ) {

					// Google recaptcha secret key verification starts.
					$google_recaptcha = isset( $_POST['captcha_response'] ) ? sanitize_text_field( $_POST['captcha_response'] ) : '';
					$remoteip         = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( $_SERVER['REMOTE_ADDR'] ) : '';

					// calling google recaptcha api.
					$google_url = 'https://www.google.com/recaptcha/api/siteverify';

					$errors = new WP_Error();

					if ( empty( $google_recaptcha ) || empty( $remoteip ) ) {

						$errors->add( 'invalid_api', __( 'Please try logging in again to verify that you are not a robot.', 'ultimate-addons-for-gutenberg' ) );
						return $errors;

					} else {
						$google_response = wp_safe_remote_get(
							add_query_arg(
								array(
									'secret'   => $google_recaptcha_secret_key,
									'response' => $google_recaptcha,
									'remoteip' => $remoteip,
								),
								$google_url
							)
						);
						if ( is_wp_error( $google_response ) ) {

							$errors->add( 'invalid_recaptcha', __( 'Please try logging in again to verify that you are not a robot.', 'ultimate-addons-for-gutenberg' ) );
							return $errors;

						} else {
							$google_response        = wp_remote_retrieve_body( $google_response );
							$decode_google_response = json_decode( $google_response );

							if ( false === $decode_google_response->success ) {
								wp_send_json_error( 400 );
							}
						}
					}
				}
			}

			if ( empty( $google_recaptcha_secret_key ) && ! empty( $google_recaptcha_site_key ) ) {
				wp_send_json_error( 400 );
			}
			if ( ! empty( $google_recaptcha_secret_key ) && empty( $google_recaptcha_site_key ) ) {
				wp_send_json_error( 400 );
			}
			// sanitizing form_data elements in later stage.
			$form_data = isset( $_POST['form_data'] ) ? json_decode( wp_unslash( $_POST['form_data'] ), true ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			if ( ! is_array( $form_data ) ) {
				$form_data = array();
			}

			$body  = '';
			$body .= '<div style="border: 50px solid #f6f6f6;">';
			$body .= '<div style="padding: 15px;">';

			foreach ( $form_data as $key => $value ) {

				if ( $key ) {

					if ( is_array( $value ) && stripos( wp_json_encode( $value ), '+' ) !== false ) {

						$val   = implode( '', $value );
						$body .= '<p><strong>' . str_replace( '_', ' ', ucwords( esc_html( $key ) ) ) . '</strong> - ' . esc_html( $val ) . '</p>';

					} elseif ( is_array( $value ) ) {

						$val   = implode( ', ', $value );
						$body .= '<p><strong>' . str_replace( '_', ' ', ucwords( esc_html( $key ) ) ) . '</strong> - ' . esc_html( $val ) . '</p>';

					} else {
						$body .= '<p><strong>' . str_replace( '_', ' ', ucwords( esc_html( $key ) ) ) . '</strong> - ' . esc_html( $value ) . '</p>';
					}
				}
			}
			$body .= '<p style="text-align:center;">This e-mail was sent from a ' . get_bloginfo( 'name' ) . ' ( ' . site_url() . ' )</p>';
			$body .= '</div>';
			$body .= '</div>';
			if ( is_array( $current_block_attributes ) ) {
				$this->send_email( $body, $form_data, $current_block_attributes );
			}

		}


/** Function uag_global_sidebar_enabled() called by wp_ajax hooks: {'uag_global_sidebar_enabled'} **/
/** Parameters found in function uag_global_sidebar_enabled(): {"post": ["enableQuickActionSidebar"]} **/
function uag_global_sidebar_enabled() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		if ( ! check_ajax_referer( 'uagb_ajax_nonce', 'security', false ) ) {
			wp_send_json_error();
		}

		if ( ! empty( $_POST['enableQuickActionSidebar'] ) ) {
			$spectra_enable_quick_action_sidebar = ( 'enabled' === $_POST['enableQuickActionSidebar'] ? 'enabled' : 'disabled' );
			\UAGB_Admin_Helper::update_admin_settings_option( 'uag_enable_quick_action_sidebar', $spectra_enable_quick_action_sidebar );
			wp_send_json_success();
		}
		wp_send_json_error();
	}


/** Function generate_ai_content() called by wp_ajax hooks: {'ast-block-templates-regenerate'} **/
/** Parameters found in function generate_ai_content(): {"post": ["category", "regenerate", "block_type", "is_last_category"]} **/
function generate_ai_content() {
		// Verify Nonce.
		check_ajax_referer( 'ast-block-templates-ai-content', 'security' );

		$details = Importer_Helper::get_business_details();

		$language = 'en';
		if ( isset( $details['language'] ) ) {
			$language = is_array( $details['language'] ) ? $details['language']['code'] : $details['language'];
		}

		$post_data = array(
			'business_name' => isset( $details['business_name'] ) ? sanitize_text_field( $details['business_name'] ) : '',
			'business_description' => isset( $details['business_description'] ) ? sanitize_text_field( $details['business_description'] ) : '',
			'business_category' => isset( $details['business_category'] ) ? sanitize_text_field( $details['business_category'] ) : '',
			'category' => isset( $_POST['category'] ) ? intval( $_POST['category'] ) : '',
			'token' => isset( $details['token'] ) ? $details['token'] : '',
			'regenerate' => isset( $_POST['regenerate'] ) ? filter_var( $_POST['regenerate'], FILTER_VALIDATE_BOOLEAN ) : false,
			'block_type' => isset( $_POST['block_type'] ) ? sanitize_text_field( $_POST['block_type'] ) : 'block',
			'is_last_category' => isset( $_POST['is_last_category'] ) ? filter_var( $_POST['is_last_category'], FILTER_VALIDATE_BOOLEAN ) : false,
			'language_slug' => $language,
			'language_name' => isset( $details['language']['name'] ) ? sanitize_text_field( $details['language']['name'] ) : '',
		);

		$category_content = get_option( 'ast-templates-ai-content', array() );

		if ( ! $post_data['regenerate'] ) {
			if ( isset( $category_content[ $post_data['category'] ] ) ) {
				wp_send_json_success(
					array(
						'data' => $category_content,
						'status'  => true,
						'extra' => 'from club saved already',
						'single' => $category_content[ $post_data['category'] ],
						'spec_credit_details'   => Plugin::instance()->get_spec_credit_details(),
					)
				);
			}
		}

		$this->get_ai_content( $post_data, $category_content );
	}


/** Function wp_ajax_install_plugin() called by wp_ajax hooks: {'uagb_recommended_plugin_install'} **/
/** No function found :-/ **/


/** Function dismiss_notice() called by wp_ajax hooks: {'astra-notice-dismiss'} **/
/** Parameters found in function dismiss_notice(): {"post": ["notice_id", "repeat_notice_after"]} **/
function dismiss_notice() {
			check_ajax_referer( 'astra-notices', 'nonce' );

			$notice_id           = ( isset( $_POST['notice_id'] ) ) ? sanitize_key( wp_unslash( $_POST['notice_id'] ) ) : '';
			$repeat_notice_after = ( isset( $_POST['repeat_notice_after'] ) ) ? absint( $_POST['repeat_notice_after'] ) : 0;
			$notice              = $this->get_notice_by_id( $notice_id );
			$capability          = isset( $notice['capability'] ) ? $notice['capability'] : 'manage_options';

			$has_cap = current_user_can( $capability );

			/**
			 * Filters whether the current user passes the capability check for notice dismissal.
			 *
			 * Both the legacy and new filter names are fired for backward compatibility.
			 * Filters can only restrict access (return false), never grant it — if the
			 * underlying current_user_can() check fails, filters cannot override to true.
			 */
			$cap_check = apply_filters( 'astra_notices_user_cap_check', $has_cap );
			$cap_check = apply_filters( 'bsf_admin_notices_user_cap_check', $cap_check );

			if ( ! $has_cap || ! $cap_check ) {
				wp_send_json_error( esc_html__( 'Permission denied.', 'ultimate-addons-for-gutenberg' ) );
			}

			$allowed_notices = get_option( 'allowed_astra_notices', array() ); // Get allowed notices.

			// Define restricted user meta keys using the dynamic table prefix.
			global $wpdb;
			$wp_default_meta_keys = array(
				$wpdb->prefix . 'capabilities',
				$wpdb->prefix . 'user_level',
				$wpdb->prefix . 'user-settings',
				'account_status',
				'session_tokens',
			);

			// if $notice_id does not start with astra-notices-id and notice_id is not from the allowed notices, then return.
			if ( 0 !== strpos( $notice_id, 'astra-notices-id-' ) && ( ! in_array( $notice_id, $allowed_notices, true ) ) ) {
				wp_send_json_error( esc_html__( 'Invalid notice ID.', 'ultimate-addons-for-gutenberg' ) );
			}

			// Valid inputs?
			if ( ! empty( $notice_id ) ) {

				if ( in_array( $notice_id, $wp_default_meta_keys, true ) ) {
					wp_send_json_error( esc_html__( 'Invalid notice ID.', 'ultimate-addons-for-gutenberg' ) );
				}

				if ( ! empty( $repeat_notice_after ) ) {
					set_transient( $notice_id, true, $repeat_notice_after );
				} else {
					update_user_meta( get_current_user_id(), $notice_id, 'notice-dismissed' );
				}

				wp_send_json_success();
			}

			wp_send_json_error();
		}


/** Function send_plugin_deactivate_feedback() called by wp_ajax hooks: {'uds_plugin_deactivate_feedback'} **/
/** Parameters found in function send_plugin_deactivate_feedback(): {"post": ["reason", "feedback", "referer", "version", "source"]} **/
function send_plugin_deactivate_feedback() {

			$response_data = array( 'message' => __( 'Sorry, you are not allowed to do this operation.', 'ultimate-addons-for-gutenberg' ) );

			/**
			 * Check permission
			 */
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( $response_data );
			}

			/**
			 * Nonce verification
			 */
			if ( ! check_ajax_referer( 'uds_plugin_deactivate_feedback', 'security', false ) ) {
				$response_data = array( 'message' => __( 'Nonce validation failed', 'ultimate-addons-for-gutenberg' ) );
				wp_send_json_error( $response_data );
			}

			$feedback_data = array(
				'reason'      => isset( $_POST['reason'] ) ? sanitize_text_field( wp_unslash( $_POST['reason'] ) ) : '',
				'feedback'    => isset( $_POST['feedback'] ) ? sanitize_text_field( wp_unslash( $_POST['feedback'] ) ) : '',
				'domain_name' => isset( $_POST['referer'] ) ? sanitize_text_field( wp_unslash( $_POST['referer'] ) ) : '',
				'version'     => isset( $_POST['version'] ) ? sanitize_text_field( wp_unslash( $_POST['version'] ) ) : '',
				'plugin'      => isset( $_POST['source'] ) ? sanitize_text_field( wp_unslash( $_POST['source'] ) ) : '',
			);

			$api_args = array(
				'body'    => wp_json_encode( $feedback_data ),
				'headers' => BSF_Analytics_Helper::get_api_headers(),
				'timeout' => 15, //phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout
			);

			$target_url = BSF_Analytics_Helper::get_api_url() . self::$feedback_api_endpoint;

			$response = wp_safe_remote_post( $target_url, $api_args );

			$has_errors = BSF_Analytics_Helper::is_api_error( $response );

			if ( $has_errors['error'] ) {
				wp_send_json_error(
					array(
						'success' => false,
						'message' => $has_errors['error_message'],
					)
				);
			}

			wp_send_json_success();
		}


/** Function sureforms_plugin_activator() called by wp_ajax hooks: {'uagb_sureforms'} **/
/** No params detected :-/ **/


/** Function check_migration_status() called by wp_ajax hooks: {'check_migration_status'} **/
/** Parameters found in function check_migration_status(): {"post": ["nonce"]} **/
function check_migration_status() {
		// Check user capability first.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error(
				array(
					'status' => 'fail',
					'type'   => 'error',
					'msg'    => 'Unauthorized access',
				)
			);
			return;
		}

		// Sanitize and check if the nonce is valid.
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
		if ( ! wp_verify_nonce( $nonce, 'check_migration_status_nonce' ) ) {
			wp_send_json_error(
				array(
					'status' => 'fail',
					'type'   => 'error',
					'msg'    => 'Invalid nonce',
				)
			);
			return;
		}
	
		$migration_complete     = get_option( 'uag_migration_complete', 'no' );
		$migration_needs_reload = get_transient( 'uag_migration_needs_reload' ) ? 'yes' : 'no';
	
		// If migration is complete and reload is needed, delete the transient to avoid repeated reloads.
		if ( 'yes' === $migration_complete && 'yes' === $migration_needs_reload ) {
			delete_transient( 'uag_migration_needs_reload' );
		}
	
		// Check if the migration status retrieval failed.
		if ( 'fail' === $migration_complete ) {
			wp_send_json_error(
				array(
					'status' => 'fail',
					'type'   => 'error',
					'msg'    => "We couldn't catch current tasks, please try again",
				)
			);
		} else {
			wp_send_json_success(
				array(
					'complete' => $migration_complete,
					'reload'   => $migration_needs_reload,
				)
			);
		}
	}


/** Function get_taxonomy() called by wp_ajax hooks: {'uagb_get_taxonomy'} **/
/** No params detected :-/ **/


/** Function wp_ajax_install_theme() called by wp_ajax hooks: {'uagb_recommended_theme_install'} **/
/** No function found :-/ **/


/** Function import_template_kit() called by wp_ajax hooks: {'ast_block_templates_import_template_kit'} **/
/** Parameters found in function import_template_kit(): {"request": ["content"]} **/
function import_template_kit() {

		if ( ! current_user_can( 'manage_ast_block_templates' ) ) {
			wp_send_json_error( __( 'You are not allowed to perform this action', 'ultimate-addons-for-gutenberg' ) );
		}
		// Verify Nonce.
		check_ajax_referer( 'ast-block-templates-ajax-nonce', '_ajax_nonce' );

		// Allow the SVG tags in batch update process.
		add_filter( 'wp_kses_allowed_html', array( $this, 'allowed_tags_and_attributes' ), 10, 2 );

		$ids_mapping = get_option( 'ast_block_templates_wpforms_ids_mapping', array() );

		// Post content.
		$content = isset( $_REQUEST['content'] ) ? stripslashes( $_REQUEST['content'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		// Empty mapping? Then return.
		if ( ! empty( $ids_mapping ) ) {
			// Replace ID's.
			foreach ( $ids_mapping as $old_id => $new_id ) {
				$content = str_replace( '[wpforms id="' . $old_id, '[wpforms id="' . $new_id, $content );
				$content = str_replace( '{"formId":"' . $old_id . '"}', '{"formId":"' . $new_id . '"}', $content );
			}
		}

		// # Tweak
		// Gutenberg break block markup from render. Because the '&' is updated in database with '&amp;' and it
		// expects as 'u0026amp;'. So, Converted '&amp;' with 'u0026amp;'.
		//
		// @todo This affect for normal page content too. Detect only Gutenberg pages and process only on it.
		// $content = str_replace( '&amp;', "\u0026amp;", $content );
		$content = $this->get_content( $content );

		// Update content.
		wp_send_json_success( $content );
	}


/** Function surecart_plugin_activator() called by wp_ajax hooks: {'uagb_surecart'} **/
/** No params detected :-/ **/


/** Function disabler_ajax() called by wp_ajax hooks: {'zip_ai_disabler_ajax'} **/
/** Parameters found in function disabler_ajax(): {"post": ["disable_zip_ai_assistant"]} **/
function disabler_ajax() {
		// If the current user does not have the required capability, then abandon ship.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		// Verify the nonce.
		check_ajax_referer( 'zip_ai_admin_nonce', 'nonce' );

		// Have a variable to check if the module was disabled.
		$is_module_disabled = false;

		// Check if the Zip AI Assistant was requested to be disabled.
		if ( ! empty( $_POST['disable_zip_ai_assistant'] ) ) {
			$is_module_disabled = Module::disable( 'ai_assistant' );
		}

		// Send the status based on whether the Zip AI Library is enabled or not.
		if ( $is_module_disabled ) {
			wp_send_json_success();
		} else {
			wp_send_json_error();
		}
	}


/** Function reset_business_details() called by wp_ajax hooks: {'ast-block-templates-reset-business-details'} **/
/** No params detected :-/ **/


/** Function import_block() called by wp_ajax hooks: {'ast_block_templates_import_block'} **/
/** Parameters found in function import_block(): {"request": ["content", "category", "id", "style", "block_type", "disableAI"]} **/
function import_block() {
		
		if ( ! current_user_can( 'manage_ast_block_templates' ) ) {
			wp_send_json_error( __( 'You are not allowed to perform this action', 'ultimate-addons-for-gutenberg' ) );
		}
		// Verify Nonce.
		check_ajax_referer( 'ast-block-templates-ajax-nonce', '_ajax_nonce' );

		// Allow the SVG tags in batch update process.
		add_filter( 'wp_kses_allowed_html', array( $this, 'allowed_tags_and_attributes' ), 10, 2 );

		$ids_mapping = get_option( 'ast_block_templates_wpforms_ids_mapping', array() );

		// Post content.
		$content = isset( $_REQUEST['content'] ) ? stripslashes( $_REQUEST['content'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$category = isset( $_REQUEST['category'] ) ? intval( $_REQUEST['category'] ) : '';

		$block_id = isset( $_REQUEST['id'] ) ? absint( $_REQUEST['id'] ) : '';

		// Empty mapping? Then return.
		if ( ! empty( $ids_mapping ) ) {
			// Replace ID's.
			foreach ( $ids_mapping as $old_id => $new_id ) {
				$content = str_replace( '[wpforms id="' . $old_id, '[wpforms id="' . $new_id, $content );
				$content = str_replace( '{"formId":"' . $old_id . '"}', '{"formId":"' . $new_id . '"}', $content );
			}
		}

		$style = isset( $_REQUEST['style'] ) ? sanitize_text_field( $_REQUEST['style'] ) : 'style-1';
		$block_type = isset( $_REQUEST['block_type'] ) ? sanitize_text_field( $_REQUEST['block_type'] ) : 'block';

		$color_palettes = array();
		$is_astra_theme = class_exists( 'Astra_Global_Palette' );

		if ( 'block' === $block_type ) {
			$mapping_palette = array(
				'style-1' => array( 0, 1, 2, 3, 5, 5, 6, 7, 8 ),
				'style-2' => array( 0, 1, 2, 3, 4, 5, 6, 7, 8 ),
				'style-3' => array( 3, 2, 5, 4, 0, 1, 6, 7, 8 ),
			);

			$color_palettes = ! $is_astra_theme ? $this->get_block_palette_colors()[ $style ]['colors'] : $color_palettes;
		} else {
			$mapping_palette = array(
				'style-1' => array( 0, 1, 2, 3, 4, 5, 6, 7, 8 ),
				'style-2' => array( 0, 1, 5, 4, 3, 2, 6, 7, 8 ),
			);

			$color_palettes = ! $is_astra_theme ? $this->get_page_palette_colors()[ $style ]['colors'] : $color_palettes;
		}

		if ( ! $is_astra_theme ) {
			for ( $i = 0; $i < 9; $i++ ) {
				$target = $color_palettes[ $i ];
				$content = str_replace( 'var(\u002d\u002dast-global-color-' . $i . ')', $target, $content );
				$content = str_replace( 'var(--ast-global-color-' . $i . ')', $target, $content );
			}
		} else {
			for ( $i = 0; $i < 9; $i++ ) {
				$target = $mapping_palette[ $style ][ $i ];
				$content = str_replace( 'var(\u002d\u002dast-global-color-' . $i . ')', 'var(\u002d\u002dast-global-color-temp-' . $target . ')', $content );
				$content = str_replace( 'var(--ast-global-color-' . $i . ')', 'var(--ast-global-color-temp-' . $target . ')', $content );
			}
			$content = str_replace( 'var(\u002d\u002dast-global-color-temp-', 'var(\u002d\u002dast-global-color-', $content );
			$content = str_replace( 'var(--ast-global-color-temp-', 'var(--ast-global-color-', $content );
		}

		$disable_ai = isset( $_REQUEST['disableAI'] ) ? 'true' === $_REQUEST['disableAI'] : false;

		if ( ! $disable_ai && ! empty( Importer_Helper::get_business_details( 'business_description' ) ) ) {
			$category_content = get_option( 'ast-templates-ai-content', array() );
			$dynamic_content = ( isset( $category_content[ $category ] ) ) ? $category_content[ $category ] : array();
			$content = $this->replace( $content, $dynamic_content );
		} else {
			$content = $this->maybe_import_images( $content );
		}

		// Flush the object when import is successful.
		delete_option( 'ast-block-templates_data-' . $block_id );

		// Update content.
		wp_send_json_success( $content );

	}


/** Function uag_global_block_styles() called by wp_ajax hooks: {'uag_global_block_styles'} **/
/** Parameters found in function uag_global_block_styles(): {"post": ["spectraGlobalStyles", "bulkUpdateStyles", "attributes", "blockName", "postId", "globalBlockStyleId", "globalBlockStylesFontFamilies"]} **/
function uag_global_block_styles() {
		// Check if gbs enabled or not.
		if ( 'enabled' !== \UAGB_Admin_Helper::get_admin_settings_option( 'uag_enable_gbs_extension', 'enabled' ) ) {
			wp_send_json_error();
		}


		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		if ( ! check_ajax_referer( 'uagb_ajax_nonce', 'security', false ) ) {
			wp_send_json_error();
		}

		$response_data = array( 'messsage' => __( 'No post data found!', 'ultimate-addons-for-gutenberg' ) );

		if ( empty( $_POST['spectraGlobalStyles'] ) ) {
			wp_send_json_error( $response_data );
		}

		$global_block_styles = json_decode( wp_unslash( sanitize_text_field( $_POST['spectraGlobalStyles'] ) ), true );

		if ( ! empty( $_POST['bulkUpdateStyles'] ) && 'no' !== $_POST['bulkUpdateStyles'] ) {
			update_option( 'spectra_global_block_styles', $global_block_styles );
			wp_send_json_success( $global_block_styles );
		}

		if ( empty( $_POST ) || empty( $_POST['attributes'] ) || empty( $_POST['blockName'] ) || empty( $_POST['postId'] ) || empty( $_POST['spectraGlobalStyles'] ) || ! is_array( $global_block_styles ) ) {
			wp_send_json_error( $response_data );
		}

		$global_block_styles = is_array( $global_block_styles ) ? $global_block_styles : array();
		$block_attr          = array();

		$post_id = sanitize_text_field( $_POST['postId'] );
		// Not sanitizing this array because $_POST['attributes'] is a very large array of different types of attributes.
		foreach ( $global_block_styles as $key => $style ) {
			if ( ! empty( $_POST['globalBlockStyleId'] ) && ! empty( $style['value'] ) && $style['value'] === $_POST['globalBlockStyleId'] ) {
				$block_attr = $style['attributes'];

				if ( ! $block_attr ) {
					wp_send_json_error( $response_data );
					break;
				}

				$_block_slug = str_replace( 'uagb/', '', sanitize_text_field( $_POST['blockName'] ) );
				$_block_css  = UAGB_Block_Module::get_frontend_css( $_block_slug, $block_attr, $block_attr['block_id'], true );

				$desktop = '';
				$tablet  = '';
				$mobile  = '';

				$tab_styling_css = '';
				$mob_styling_css = '';
				$desktop        .= $_block_css['desktop'];
				$tablet         .= $_block_css['tablet'];
				$mobile         .= $_block_css['mobile'];
				if ( ! empty( $tablet ) ) {
					$tab_styling_css .= '@media only screen and (max-width: ' . UAGB_TABLET_BREAKPOINT . 'px) {';
					$tab_styling_css .= $tablet;
					$tab_styling_css .= '}';
				}

				if ( ! empty( $mobile ) ) {
					$mob_styling_css .= '@media only screen and (max-width: ' . UAGB_MOBILE_BREAKPOINT . 'px) {';
					$mob_styling_css .= $mobile;
					$mob_styling_css .= '}';
				}
				$_block_css                                    = $desktop . $tab_styling_css . $mob_styling_css;
				$global_block_styles[ $key ]['frontendStyles'] = $_block_css;
				$gbs_stored                                    = get_option( 'spectra_global_block_styles', array() );
				$gbs_stored_key_value                          = is_array( $gbs_stored ) && isset( $gbs_stored[ $key ] ) ? $gbs_stored[ $key ] : array();

				if ( ! empty( $gbs_stored_key_value['post_ids'] ) ) {
					$global_block_styles[ $key ]['post_ids'] = array_merge( $global_block_styles[ $key ]['post_ids'], $gbs_stored_key_value['post_ids'] );
				}

				// For FSE template slug.
				if ( ! empty( $gbs_stored_key_value['page_template_slugs'] ) ) {
					$global_block_styles[ $key ]['page_template_slugs'] = array_merge( $global_block_styles[ $key ]['page_template_slugs'], $gbs_stored_key_value['page_template_slugs'] );
				}

				// For global styles (  widget and customize area ).
				if ( ! empty( $gbs_stored_key_value['styleForGlobal'] ) ) {
					$global_block_styles[ $key ]['styleForGlobal'] = array_merge( $global_block_styles[ $key ]['styleForGlobal'], $gbs_stored_key_value['styleForGlobal'] );
				}

				update_option( 'spectra_global_block_styles', $global_block_styles );

				if ( ! empty( $global_block_styles[ $key ]['post_ids'] ) && is_array( $global_block_styles[ $key ]['post_ids'] ) ) {
					foreach ( $global_block_styles[ $key ]['post_ids'] as $post_id ) {
						UAGB_Helper::delete_page_assets( $post_id );
					}
				}
			}
		}

		$spectra_gbs_google_fonts = get_option( 'spectra_gbs_google_fonts', array() );

		// Global Font Families.
		$font_families = array();
		foreach ( $block_attr as $name => $attribute ) {
			if ( false !== strpos( $name, 'Family' ) && '' !== $attribute ) {

				$font_families[] = $attribute;
			}
		}

		if ( isset( $block_attr['globalBlockStyleId'] ) && is_array( $spectra_gbs_google_fonts ) ) {
			$spectra_gbs_google_fonts[ $block_attr['globalBlockStyleId'] ] = $font_families;
			if ( isset( $spectra_gbs_google_fonts[ $block_attr['globalBlockStyleId'] ] ) && is_array( $spectra_gbs_google_fonts[ $block_attr['globalBlockStyleId'] ] ) ) {
				$spectra_gbs_google_fonts[ $block_attr['globalBlockStyleId'] ] = array_unique( $spectra_gbs_google_fonts[ $block_attr['globalBlockStyleId'] ] );
			}
		}

		update_option( 'spectra_gbs_google_fonts', $spectra_gbs_google_fonts );

		if ( ! empty( $_POST['globalBlockStylesFontFamilies'] ) ) {
			$spectra_gbs_google_fonts_editor = json_decode( wp_unslash( sanitize_text_field( $_POST['globalBlockStylesFontFamilies'] ) ), true );
			update_option( 'spectra_gbs_google_fonts_editor', $spectra_gbs_google_fonts_editor );
		}

		wp_send_json_success( $global_block_styles );
	}


/** Function gf_shortcode() called by wp_ajax hooks: {'nopriv_uagb_gf_shortcode', 'uagb_gf_shortcode'} **/
/** Parameters found in function gf_shortcode(): {"post": ["formId"]} **/
function gf_shortcode() {

		check_ajax_referer( 'uagb_ajax_nonce', 'nonce' );

		$id = isset( $_POST['formId'] ) ? intval( $_POST['formId'] ) : 0;

		if ( $id && 0 !== $id && -1 !== $id ) {
			$data['html'] = do_shortcode( '[gravityforms id="' . $id . '" ajax="true"]' );
		} else {
			$data['html'] = '<p>' . __( 'Please select a valid Gravity Form.', 'ultimate-addons-for-gutenberg' ) . '</p>';
		}
		wp_send_json_success( $data );
	}


/** Function ajax_import_sites() called by wp_ajax hooks: {'ast-block-templates-import-sites'} **/
/** Parameters found in function ajax_import_sites(): {"post": ["page_no", "total"]} **/
function ajax_import_sites() {

		if ( ! current_user_can( 'manage_ast_block_templates' ) ) {
			wp_send_json_error( __( 'You are not allowed to perform this action', 'ultimate-addons-for-gutenberg' ) );
		}
		// Verify Nonce.
		check_ajax_referer( 'ast-block-templates-ajax-nonce', '_ajax_nonce' );

		$page_no = isset( $_POST['page_no'] ) ? absint( $_POST['page_no'] ) : '';
		if ( $page_no ) {
			$this->import_sites( $page_no );
			$data = array(
				'message' => 'Success imported sites for page ' . $page_no,
				'status'  => true,
				'data'    => array(),
			);

			if ( isset( $_POST['total'] ) && $_POST['total'] === $_POST['page_no'] ) {
				$data['data']['allBlocks'] = Plugin::instance()->get_all_blocks();
				$data['data']['categories'] = Helper::instance()->get_block_template_category();
				$data['data']['allSites'] = Plugin::instance()->get_all_sites();
			}

			wp_send_json_success(
				$data
			);
		}

		wp_send_json_error(
			array(
				'message' => 'Failed imported sites for page ' . $page_no,
				'status'  => false,
				'data'    => '',
			)
		);
	}


/** Function hide_notices() called by wp_ajax hooks: {'ast_block_templates_hide_notices'} **/
/** Parameters found in function hide_notices(): {"request": ["notice_type"]} **/
function hide_notices() {

		if ( ! current_user_can( 'manage_ast_block_templates' ) ) {
			wp_send_json_error( __( 'You are not allowed to perform this action', 'ultimate-addons-for-gutenberg' ) );
		}

		check_ajax_referer( 'ast-block-templates-ajax-nonce', '_ajax_nonce' );

		$notice_type = isset( $_REQUEST['notice_type'] ) ? sanitize_text_field( $_REQUEST['notice_type'] ) : '';

		if ( ! empty( $notice_type ) ) {

			switch ( $notice_type ) {
				case 'personalize-ai':
					set_transient( 'ast_block_templates_hide_personalize_ai_notice', true, 30 * DAY_IN_SECONDS );
					break;
				case 'build-page-ai':
					set_transient( 'ast_block_templates_hide_build_page_ai_notice', true, 30 * DAY_IN_SECONDS );
					break;
				case 'credit-warning':
					set_transient( 'ast_block_templates_hide_credit_warning_notice', true, 30 * DAY_IN_SECONDS );
					break;
				case 'credit-danger':
					set_transient( 'ast_block_templates_hide_credit_danger_notice', true, 30 * DAY_IN_SECONDS );
					break;

				default:
					break;
			}
		}

		wp_send_json_success(
			array(
				'status' => true,
			)
		);
	}


/** Function uagb_activate_addon() called by wp_ajax hooks: {'uagb_recommended_plugin_activate'} **/
/** Parameters found in function uagb_activate_addon(): {"post": ["plugin", "type", "slug"]} **/
function uagb_activate_addon() {

		// Run a security check.
		check_ajax_referer( 'updates', 'nonce' );

		if ( isset( $_POST['plugin'] ) ) {

			$type = '';
			if ( ! empty( $_POST['type'] ) ) {
				$type = sanitize_key( wp_unslash( $_POST['type'] ) );
			}

			$plugin = sanitize_text_field( wp_unslash( $_POST['plugin'] ) );

			if ( 'plugin' === $type ) {

				// Check for permissions.
				if ( ! current_user_can( 'activate_plugins' ) ) {
					wp_send_json_error( esc_html__( 'Plugin activation is disabled for you on this site.', 'ultimate-addons-for-gutenberg' ) );
				}
				if ( isset( $_POST['slug'] ) ) {
					$slug = sanitize_key( wp_unslash( $_POST['slug'] ) );
					if ( class_exists( '\BSF_UTM_Analytics\Inc\Utils' ) && is_callable( '\BSF_UTM_Analytics\Inc\Utils::update_referer' ) ) {
						// If the plugin is found and the update_referer function is callable, update the referer with the corresponding product slug.
						\BSF_UTM_Analytics\Inc\Utils::update_referer( 'ultimate-addons-for-gutenberg', $slug );
					}
				}

				$activate = activate_plugins( $plugin );

				if ( ! is_wp_error( $activate ) ) {

					do_action( 'uagb_plugin_activated', $plugin );

					wp_send_json_success( esc_html__( 'Plugin Activated.', 'ultimate-addons-for-gutenberg' ) );
				}
			}

			if ( 'theme' === $type ) {

				if ( isset( $_POST['slug'] ) ) {
					$slug = sanitize_key( wp_unslash( $_POST['slug'] ) );

					// Check for permissions.
					if ( ! ( current_user_can( 'switch_themes' ) ) ) {
						wp_send_json_error( esc_html__( 'Theme activation is disabled for you on this site.', 'ultimate-addons-for-gutenberg' ) );
					}

					if ( class_exists( '\BSF_UTM_Analytics\Inc\Utils' ) && is_callable( '\BSF_UTM_Analytics\Inc\Utils::update_referer' ) ) {
						// If the theme is found and the update_referer function is callable, update the referer with the corresponding product slug.
						\BSF_UTM_Analytics\Inc\Utils::update_referer( 'ultimate-addons-for-gutenberg', $slug );
					}

					$activate = switch_theme( $slug );

					if ( ! is_wp_error( $activate ) ) {

						do_action( 'uagb_theme_activated', $plugin );

						wp_send_json_success( esc_html__( 'Theme Activated.', 'ultimate-addons-for-gutenberg' ) );
					}
				}
			}
		}

		if ( isset( $type ) ) { 
			if ( 'plugin' === $type ) {
				wp_send_json_error( esc_html__( 'Could not activate plugin. Please activate from the Plugins page.', 'ultimate-addons-for-gutenberg' ) );
			} elseif ( 'theme' === $type ) {
				wp_send_json_error( esc_html__( 'Could not activate theme. Please activate from the Themes page.', 'ultimate-addons-for-gutenberg' ) );
			}
		}
	}


/** Function get_latest_credit_details() called by wp_ajax hooks: {'get_latest_credit_details'} **/
/** No params detected :-/ **/


/** Function save_user_details() called by wp_ajax hooks: {'ast-block-templates-ai-content'} **/
/** Parameters found in function save_user_details(): {"post": ["image_keyword", "images", "social_profiles", "save_only", "language", "business_name", "business_desc", "business_category", "business_address", "business_phone", "business_email"]} **/
function save_user_details() {

		// Verify Nonce.
		check_ajax_referer( 'ast-block-templates-ai-content', 'security' );

		$keywords = isset( $_POST['image_keyword'] ) ? json_decode( wp_unslash( $_POST['image_keyword'] ), true ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$images = isset( $_POST['images'] ) ? json_decode( wp_unslash( $_POST['images'] ), true ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$social_profiles = isset( $_POST['social_profiles'] ) ? json_decode( wp_unslash( $_POST['social_profiles'] ), true ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$save_only = isset( $_POST['save_only'] ) ? filter_var( $_POST['save_only'], FILTER_VALIDATE_BOOLEAN ) : false;

		foreach ( $keywords as $key => $keyword ) {
			$keywords[ $key ] = sanitize_text_field( wp_unslash( $keyword ) );
		}

		if ( ! empty( $images ) ) {
			foreach ( $images as $key => $image ) {
				foreach ( $image as $j => $image_attr ) {
					switch ( $image_attr ) {
						case 'author_name':
						case 'orientation':
						case 'author_url':
						case 'description':
						case 'engine':
						case 'id':
							$images[ $key ][ $image_attr ] = sanitize_text_field( wp_unslash( $image_attr ) );
							break;

						case 'engine_url':
						case 'author_url':
						case 'optimized_url':
						case 'url':
							$images[ $key ][ $image_attr ] = esc_url_raw( wp_unslash( $image_attr ) );
							break;
					}
				}
			}
		}

		$language = isset( $_POST['language'] ) ? json_decode( wp_unslash( $_POST['language'] ), true ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		$business_details = get_option( 'zipwp_user_business_details', array() );
		$business_details['business_name'] = isset( $_POST['business_name'] ) ? sanitize_text_field( wp_unslash( $_POST['business_name'] ) ) : '';
		$business_details['business_description'] = isset( $_POST['business_desc'] ) ? sanitize_text_field( wp_unslash( $_POST['business_desc'] ) ) : '';
		$business_details['business_category'] = isset( $_POST['business_category'] ) ? sanitize_text_field( wp_unslash( $_POST['business_category'] ) ) : '';
		$business_details['images'] = $images;
		$business_details['image_keyword'] = $keywords;
		$business_details['business_address'] = ( isset( $_POST['business_address'] ) ) ? sanitize_text_field( wp_unslash( $_POST['business_address'] ) ) : '';
		$business_details['business_phone']   = ( isset( $_POST['business_phone'] ) ) ? sanitize_text_field( wp_unslash( $_POST['business_phone'] ) ) : '';
		$business_details['business_email']   = ( isset( $_POST['business_email'] ) ) ? sanitize_email( wp_unslash( $_POST['business_email'] ) ) : '';
		$business_details['social_profiles']  = $social_profiles;
		$business_details['language']  = $language;

		update_option( 'ast-block-templates-show-onboarding', 'no' );
		update_option( 'zipwp_user_business_details', $business_details );

		if ( ! $save_only ) {
			delete_option( 'ast-templates-ai-content' );
		}

		// Schedule event to download images in background.
		wp_schedule_single_event( time() + 1, 'ast_templates_download_selected_images' );

		wp_send_json_success(
			array(
				'status'  => true,
				'images' => $images,
			)
		);
	}


/** Function post_grid_pagination_ajax_callback() called by wp_ajax hooks: {'uagb_post_pagination_grid', 'nopriv_uagb_post_pagination_grid'} **/
/** Parameters found in function post_grid_pagination_ajax_callback(): {"post": ["attr", "page_number"]} **/
function post_grid_pagination_ajax_callback() {
			check_ajax_referer( 'uagb_grid_ajax_nonce', 'nonce' );

			if ( isset( $_POST['attr'] ) ) {
				// SECURITY: First decode JSON, then sanitize individual values.
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized after json_decode via required_attribute_for_query.
				$attr = json_decode( wp_unslash( $_POST['attr'] ), true );

				// Validate JSON decode was successful.
				if ( ! is_array( $attr ) ) {
					wp_send_json_error( 'Invalid JSON data received.' );
				}

				$attr['paged'] = isset( $_POST['page_number'] ) ? absint( $_POST['page_number'] ) : 1;
				$html          = $this->post_grid_callback( $attr );
				wp_send_json_success( $html );

			}

			wp_send_json_error( ' Something went wrong, failed to load pagination data! ' );
		}


/** Function cf7_shortcode() called by wp_ajax hooks: {'uagb_cf7_shortcode', 'nopriv_uagb_cf7_shortcode'} **/
/** Parameters found in function cf7_shortcode(): {"post": ["formId"]} **/
function cf7_shortcode() {

		check_ajax_referer( 'uagb_ajax_nonce', 'nonce' );

		$id = isset( $_POST['formId'] ) ? intval( $_POST['formId'] ) : 0;

		if ( $id && 0 !== $id && -1 !== $id ) {
			$data['html'] = do_shortcode( '[contact-form-7 id="' . $id . '" ajax="true"]' );
		} else {
			$data['html'] = '<p>' . __( 'Please select a valid Contact Form 7.', 'ultimate-addons-for-gutenberg' ) . '</p>';
		}
		wp_send_json_success( $data );
	}


/** Function render_grid_pagination() called by wp_ajax hooks: {'nopriv_uag_load_image_gallery_grid_pagination', 'uag_load_image_gallery_grid_pagination'} **/
/** Parameters found in function render_grid_pagination(): {"post": ["attr", "page_number"]} **/
function render_grid_pagination() {
			check_ajax_referer( 'uagb_image_gallery_grid_pagination_ajax_nonce', 'nonce' );
			$media_atts = array();
			// sanitizing $attr elements in later stage.
			$attr = isset( $_POST['attr'] ) ? json_decode( wp_unslash( $_POST['attr'] ), true ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			if ( ! is_array( $attr ) ) {
				$attr = array();
			}
			$attr['gridPageNumber']     = isset( $_POST['page_number'] ) ? sanitize_text_field( $_POST['page_number'] ) : '';
			$media_atts                 = $this->required_atts( $attr );
			$media_atts['mediaGallery'] = json_decode( $media_atts['mediaGallery'], true );
			$media                      = $this->get_gallery_images( $media_atts, 'paginated' );
			if ( ! $media ) {
				wp_send_json_error();
			}
			foreach ( $attr as $key => $attribute ) {
				$attr[ $key ] = ( 'false' === $attribute ) ? false : ( ( 'true' === $attribute ) ? true : $attribute );
			}
			$htmlArray = $this->render_media_markup( $media, $attr );
			wp_send_json_success( $htmlArray );
		}


/** Function track_first_template_imported() called by wp_ajax hooks: {'ast_block_templates_import_template_kit', 'ast_block_templates_importer'} **/
/** No params detected :-/ **/


/** Function track_design_library_opened() called by wp_ajax hooks: {'uagb_track_design_library_opened'} **/
/** No params detected :-/ **/


/** Function import_wpforms() called by wp_ajax hooks: {'ast_block_templates_import_wpforms'} **/
/** Parameters found in function import_wpforms(): {"request": ["id"]} **/
function import_wpforms( $wpforms_url = '' ) {

		if ( ! current_user_can( 'manage_ast_block_templates' ) ) {
			wp_send_json_error( __( 'You are not allowed to perform this action', 'ultimate-addons-for-gutenberg' ) );
		}
		// Verify Nonce.
		check_ajax_referer( 'ast-block-templates-ajax-nonce', '_ajax_nonce' );

		$block_id   = isset( $_REQUEST['id'] ) ? absint( $_REQUEST['id'] ) : '';
		$block_data = get_option( 'ast-block-templates_data-' . $block_id );

		$block_data  = null !== $block_data ? $block_data : '';
		$wpforms_url = '';

		if ( 'astra-blocks' === $block_data->{'type'} ) {
			$wpforms_url = $block_data->{'post-meta'}->{'astra-site-wpforms-path'};
		}

		if ( 'site-pages' === $block_data->{'type'} ) {
			$wpforms_url = $block_data->{'astra-site-wpforms-path'};
		}

		$ids_mapping = array();

		if ( ! empty( $wpforms_url ) && function_exists( 'wpforms_encode' ) ) {

			// Download JSON file.
			$file_path = $this->download_file( $wpforms_url );

			if ( $file_path['success'] ) {
				if ( isset( $file_path['data']['file'] ) ) {

					$ext = strtolower( pathinfo( $file_path['data']['file'], PATHINFO_EXTENSION ) );

					if ( 'json' === $ext ) {
						/** 
						 * 
						 * Retrieves the contents of a file using the specified file system.
						 *
						 * @var \WP_Filesystem_Base $filesystem 
						 * */
						$filesystem = Helper::instance()->ast_block_templates_get_filesystem();
						$file_content = $filesystem->get_contents( $file_path['data']['file'] );
						$forms = json_decode( $file_content ? $file_content : '', true );

						if ( ! empty( $forms ) ) {

							foreach ( $forms as $form ) {
								$title = ! empty( $form['settings']['form_title'] ) ? $form['settings']['form_title'] : '';
								$desc  = ! empty( $form['settings']['form_desc'] ) ? $form['settings']['form_desc'] : '';

								$new_id = post_exists( $title, '', '', 'wpforms' );

								if ( ! $new_id ) {
									$new_id = wp_insert_post(
										array(
											'post_title'   => $title,
											'post_status'  => 'publish',
											'post_type'    => 'wpforms',
											'post_excerpt' => $desc,
										)
									);

									Helper::instance()->ast_block_templates_log( 'Imported Form ' . $title );
								}

								if ( $new_id ) {

									// ID mapping.
									$ids_mapping[ $form['id'] ] = $new_id;

									$form['id'] = $new_id;
									wp_update_post(
										array(
											'ID' => $new_id,
											'post_content' => wpforms_encode( $form ),
										)
									);
								}
							}
						}
					}
				}
			} else {
				wp_send_json_error( $file_path );
			}
		} else {
			wp_send_json_error( __( 'Something went wrong', 'ultimate-addons-for-gutenberg' ) );
		}

		update_option( 'ast_block_templates_wpforms_ids_mapping', $ids_mapping );

		wp_send_json_success( $ids_mapping );
	}


/** Function post_pagination() called by wp_ajax hooks: {'uagb_post_pagination', 'nopriv_uagb_post_pagination'} **/
/** Parameters found in function post_pagination(): {"post": ["attributes"]} **/
function post_pagination() {

			check_ajax_referer( 'uagb_ajax_nonce', 'nonce' );

			$post_attribute_array = array();

			if ( isset( $_POST['attributes'] ) ) {

				// $_POST['attributes'] is sanitized in later stage.
				$attr = isset( $_POST['attributes'] ) ? json_decode( wp_unslash( $_POST['attributes'] ), true ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				if ( ! is_array( $attr ) ) {
					$attr = array();
				}

				$post_attribute_array = $this->required_attribute_for_query( $attr );

				$query = UAGB_Helper::get_query( $post_attribute_array, 'grid' );

				$pagination_markup = $this->render_pagination( $query, $attr );

				wp_send_json_success( $pagination_markup );
			}

			wp_send_json_error( ' No attributes received' );
		}


/** Function get_color_palette() called by wp_ajax hooks: {'ast_block_templates_color_palette'} **/
/** No params detected :-/ **/


/** Function ajax_sites_requests_count() called by wp_ajax hooks: {'ast-block-templates-get-sites-request-count'} **/
/** No params detected :-/ **/


/** Function confirm_svg_upload() called by wp_ajax hooks: {'uagb_svg_confirmation'} **/
/** Parameters found in function confirm_svg_upload(): {"post": ["confirmation"]} **/
function confirm_svg_upload() {
		check_ajax_referer( 'uagb_confirm_svg_nonce', 'svg_nonce' );
		if ( empty( $_POST['confirmation'] ) || 'yes' !== sanitize_text_field( $_POST['confirmation'] ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid request', 'ultimate-addons-for-gutenberg' ) ) );
		}

		update_option( 'spectra_svg_confirmation', 'yes' );
		wp_send_json_success();
	}


/** Function uag_global_update_allowed_block() called by wp_ajax hooks: {'uag_global_update_allowed_block'} **/
/** Parameters found in function uag_global_update_allowed_block(): {"post": ["defaultAllowedQuickSidebarBlocks"]} **/
function uag_global_update_allowed_block() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		if ( ! check_ajax_referer( 'uagb_ajax_nonce', 'security', false ) ) {
			wp_send_json_error();
		}

		if ( ! empty( $_POST['defaultAllowedQuickSidebarBlocks'] ) ) {
			$spectra_default_allowed_quick_sidebar_blocks = json_decode( wp_unslash( sanitize_text_field( $_POST['defaultAllowedQuickSidebarBlocks'] ) ), true );
			\UAGB_Admin_Helper::update_admin_settings_option( 'uagb_quick_sidebar_allowed_blocks', $spectra_default_allowed_quick_sidebar_blocks );
			wp_send_json_success();
		}
		wp_send_json_error();
	}


/** Function toggle_assistant_status_ajax() called by wp_ajax hooks: {'zip_ai_toggle_assistant_status_ajax'} **/
/** Parameters found in function toggle_assistant_status_ajax(): {"post": ["enable_zip_chat"]} **/
function toggle_assistant_status_ajax() {
		// If the current user does not have the required capability, then abandon ship.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		// Verify the nonce.
		check_ajax_referer( 'zip_ai_admin_nonce', 'nonce' );

		// If the Zip options is not an array, then send an error.
		if ( empty( $_POST['enable_zip_chat'] ) || ! is_string( $_POST['enable_zip_chat'] ) ) {
			wp_send_json_error();
		}

		// Create a variable to check if the assistant module was toggled.
		$is_module_toggled = false;

		// Update the enabled status.
		if ( 'enabled' === sanitize_text_field( wp_unslash( $_POST['enable_zip_chat'] ) ) ) {
			$is_module_toggled = Module::enable( 'ai_assistant' );
		} else {
			$is_module_toggled = Module::disable( 'ai_assistant' );
		}

		// Send the status based on whether the module was toggled.
		if ( $is_module_toggled ) {
			wp_send_json_success();
		} else {
			wp_send_json_error();
		}
	}


/** Function forms_recaptcha() called by wp_ajax hooks: {'uagb_forms_recaptcha'} **/
/** Parameters found in function forms_recaptcha(): {"post": ["value"]} **/
function forms_recaptcha() {

		$response_data = array(
			'messsage' => __( 'User is not authenticated!', 'ultimate-addons-for-gutenberg' ),
		);

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( $response_data );
		}

		check_ajax_referer( 'uagb_ajax_nonce', 'nonce' );
		// security validation done in later stage.
		$value = isset( $_POST['value'] ) ? json_decode( wp_unslash( $_POST['value'] ), true ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		if ( ! is_array( $value ) ) {
			$value = array();
		}

		\UAGB_Admin_Helper::update_admin_settings_option( 'uag_recaptcha_secret_key_v2', sanitize_text_field( $value['reCaptchaSecretKeyV2'] ) );
		\UAGB_Admin_Helper::update_admin_settings_option( 'uag_recaptcha_secret_key_v3', sanitize_text_field( $value['reCaptchaSecretKeyV3'] ) );
		\UAGB_Admin_Helper::update_admin_settings_option( 'uag_recaptcha_site_key_v2', sanitize_text_field( $value['reCaptchaSiteKeyV2'] ) );
		\UAGB_Admin_Helper::update_admin_settings_option( 'uag_recaptcha_site_key_v3', sanitize_text_field( $value['reCaptchaSiteKeyV3'] ) );

		$response_data = array(
			'messsage' => __( 'Successfully saved data!', 'ultimate-addons-for-gutenberg' ),
		);
		wp_send_json_success( $response_data );

	}


/** Function update_popup_status() called by wp_ajax hooks: {'uag_update_popup_status'} **/
/** Parameters found in function update_popup_status(): {"post": ["enabled", "post_id"]} **/
function update_popup_status() {
		check_ajax_referer( 'uagb_popup_builder_admin_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		if ( ! isset( $_POST['enabled'] ) || ! isset( $_POST['post_id'] ) ) {
			wp_send_json_error();
		}

		$enabled  = rest_sanitize_boolean( sanitize_text_field( $_POST['enabled'] ) );
		$popup_id = sanitize_text_field( $_POST['post_id'] );

		update_post_meta( $popup_id, 'spectra-popup-enabled', $enabled );

		wp_send_json_success();
	}


/** Function zipwp_insert_image() called by wp_ajax hooks: {'zipwp_images_insert_image'} **/
/** Parameters found in function zipwp_insert_image(): {"post": ["url", "name", "description", "id"]} **/
function zipwp_insert_image(): void {
		// Verify Nonce.
		check_ajax_referer( 'zipwp-images', '_ajax_nonce' );

		if ( ! current_user_can( 'upload_files' ) ) {
			wp_send_json_error( __( 'You are not allowed to perform this action', 'ultimate-addons-for-gutenberg' ) );
		}

		$url      = isset( $_POST['url'] ) ? sanitize_url( $_POST['url'] ) : ''; // phpcs:ignore -- We need to remove this ignore once the WPCS has released this issue fix - https://github.com/WordPress/WordPress-Coding-Standards/issues/2189.
		$name     = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : false;
		$desc     = isset( $_POST['description'] ) ? sanitize_text_field( wp_unslash( $_POST['description'] ) ) : '';
		$photo_id = isset( $_POST['id'] ) ? sanitize_key( wp_unslash( $_POST['id'] ) ) : 0;

		// For unsplash images, photo_id can be alphanumeric.
		if ( strpos( $url, 'unsplash.com' ) === false ) {
			$photo_id = absint( $photo_id );
		}

		if ( 0 === $photo_id ) {
			wp_send_json_error( __( 'Need to send photo ID', 'ultimate-addons-for-gutenberg' ) );
		}

		if ( empty( $url ) ) {
			wp_send_json_error( __( 'Need to send URL of the image to be downloaded', 'ultimate-addons-for-gutenberg' ) );
		}

		$image  = '';
		$result = array();

		$name  = pathinfo( (string) $name, PATHINFO_FILENAME ) . '-' . $photo_id . '.jpg';
		$image = $this->create_image_from_url( $url, $name, (string) $photo_id, $desc );

		if ( empty( $image ) ) {
			wp_send_json_error( __( 'Could not download the image.', 'ultimate-addons-for-gutenberg' ) );
		}

		$image                    = intval( $image );
		$result['attachmentData'] = wp_prepare_attachment_for_js( $image );

		if ( did_action( 'elementor/loaded' ) ) {
			$result['data'] = $this->get_attachment_data( $image );
		}

		// Save downloaded image reference to an option.
		if ( 0 !== $photo_id ) {
			$saved_images = get_option( 'zipwp-images-saved-images', array() );

			if ( empty( $saved_images ) ) {
				$saved_images = array();
			}

			$saved_images[] = $photo_id;
			update_option( 'zipwp-images-saved-images', $saved_images, false );
		}

		$result['updated-saved-images'] = get_option( 'zipwp-images-saved-images', array() );

		wp_send_json_success( $result );
	}


