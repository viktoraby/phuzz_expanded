<?php
/***
*
*Found actions: 56
*Found functions:54
*Extracted functions:54
*Total parameter names extracted: 27
*Overview: {'update_library': {'astra-sites-update-library'}, 'verify_required_plugins': {'astra-sites-verify-required-plugins'}, 'track_onboarding_step': {'astra_sites_track_onboarding_step'}, 'import_cart_abandonment_recovery': {'astra-sites-import-cart-abandonment-recovery'}, 'hide_notices': {'ast_block_templates_hide_notices'}, 'api_request': {'ast_block_templates_data_option'}, 'dismiss_ai_promotion': {'astra-sites-dismiss-ai-promotion'}, 'import_blocks': {'astra-sites-import-blocks'}, 'generate_ai_content': {'ast-block-templates-regenerate'}, 'import_latepoint': {'astra-sites-import-latepoint'}, 'save_auto_open_setting': {'ast_block_templates_save_auto_open_setting'}, 'ajax_sites_requests_count': {'ast-block-templates-get-sites-request-count'}, 'get_all_categories_and_tags': {'astra-sites-get-all-categories-and-tags'}, 'import_template_kit': {'ast_block_templates_import_template_kit'}, 'get_latest_credit_details': {'zip_ai_get_latest_credit_details'}, 'import_block_categories': {'astra-sites-import-block-categories'}, 'ajax_import_sites': {'ast-block-templates-import-sites'}, 'sites_requests_count': {'astra-sites-get-sites-request-count'}, 'get_all_sites': {'astra-sites-get-all-sites'}, 'toggle_assistant_status_ajax': {'zip_ai_toggle_assistant_status_ajax'}, 'import_all_categories_and_tags': {'astra-sites-import-all-categories-and-tags'}, 'get_color_palette': {'ast_block_templates_color_palette'}, 'download_selected_image': {'astra-sites-download-image'}, 'import_block': {'ast_block_templates_import_block'}, 'get_fresh_credit_details': {'zip_ai_get_fresh_credit_details'}, 'ajax_import_blocks': {'ast-block-templates-import-blocks'}, 'handle_dismiss_notice_ajax': {'dismiss_gs_notice'}, 'import_cartflows': {'astra-sites-import-cartflows'}, 'template_importer': {'ast_block_templates_kit_importer', 'ast_block_templates_importer'}, 'reset_business_details': {'ast-block-templates-reset-business-details'}, 'activate_plugin': {'ast_block_templates_activate_plugin'}, 'import_all_categories': {'astra-sites-import-all-categories'}, 'sse_import': {'astra-wxr-import'}, 'import_prepare_xml': {'astra-sites-import_prepare_xml'}, 'import_page_builders': {'astra-sites-import-page-builders'}, 'import_sureforms': {'ast_block_templates_import_sureforms'}, 'import_wpforms': {'ast_block_templates_import_wpforms', 'astra-sites-import-wpforms'}, 'blocks_requests_count': {'astra-sites-get-blocks-request-count'}, 'enable_other_builders': {'astra-sites-show-other-builders'}, 'send_plugin_deactivate_feedback': {'uds_plugin_deactivate_feedback'}, 'bsf_analytics_optin_status': {'astra_sites_usage_optin_status'}, 'save_page_builder_on_ajax': {'astra-sites-change-page-builder'}, 'zipwp_insert_image': {'zipwp_images_insert_image'}, 'import_sites': {'astra-sites-import-sites'}, 'verify_authenticity': {'zip_ai_verify_authenticity'}, 'skip_spectra_pro_onboarding': {'ast_skip_zip_ai_onboarding'}, 'dismiss_woopayments_notice': {'dismiss_woopayments_notice'}, 'sync_via_ajax': {'ast-block-templates-check-sync-library-status'}, 'disabler_ajax': {'zip_ai_disabler_ajax'}, 'dismiss_notice': {'astra-notice-dismiss'}, 'update_library_complete': {'astra-sites-update-library-complete'}, 'save_user_details': {'ast-block-templates-ai-content'}, 'get_all_categories': {'astra-sites-get-all-categories'}, 'set_woopayments_analytics': {'astra_sites_set_woopayments_analytics'}}
*
***/

/** Function update_library() called by wp_ajax hooks: {'astra-sites-update-library'} **/
/** No params detected :-/ **/


/** Function verify_required_plugins() called by wp_ajax hooks: {'astra-sites-verify-required-plugins'} **/
/** Parameters found in function verify_required_plugins(): {"post": ["required_plugins"]} **/
function verify_required_plugins() {
			// Verify Nonce.
			check_ajax_referer( 'astra-sites', '_ajax_nonce' );

			if ( ! current_user_can( 'activate_plugins' ) ) {
				wp_send_json_error(
					array(
						'message' => __( 'Permission denied: You do not have permission to verify plugins.', 'astra-sites' ),
					)
				);
				return;
			}

			// Include plugin.php for is_plugin_active check.
			if ( ! function_exists( 'is_plugin_active' ) ) {
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- JSON data needs to be decoded first.
			$required_plugins_json = isset( $_POST['required_plugins'] ) ? wp_unslash( $_POST['required_plugins'] ) : '';
			$required_plugins      = ! empty( $required_plugins_json ) ? json_decode( $required_plugins_json, true ) : array();

			if ( empty( $required_plugins ) || ! is_array( $required_plugins ) ) {
				// No plugins to verify - that's fine.
				wp_send_json_success(
					array(
						'message' => __( 'No plugins to verify.', 'astra-sites' ),
						'verified' => true,
					)
				);
				return;
			}

			$missing       = array();
			$not_activated = array();

			// Flush the options cache so is_plugin_active() reads the DB rather than
			// a potentially stale object-cache entry left over from the activation
			// requests that just completed in separate PHP processes.
			wp_cache_delete( 'active_plugins', 'options' );
			wp_cache_delete( 'alloptions', 'options' );

			foreach ( $required_plugins as $plugin ) {
				if ( ! is_array( $plugin ) || empty( $plugin['init'] ) ) {
					continue;
				}

				$plugin_init = sanitize_text_field( $plugin['init'] );

				// Reject path traversal attempts.
				if ( false !== strpos( $plugin_init, '..' ) || false !== strpos( $plugin_init, '\\' ) ) {
					Astra_Sites_Importer_Log::add( 'Plugin verification blocked - invalid path: ' . $plugin_init );
					continue;
				}

				// Check if a pro equivalent of this lite plugin is active.
				$pro_plugin = Astra_Sites::get_instance()->pro_plugin_exist( $plugin_init );
				if ( is_array( $pro_plugin ) && is_plugin_active( $pro_plugin['init'] ) ) {
					continue;
				}

				$plugin_file = WP_PLUGIN_DIR . '/' . $plugin_init;

				if ( ! file_exists( $plugin_file ) ) {
					$missing[] = $plugin;
					Astra_Sites_Importer_Log::add( 'Plugin verification failed - missing: ' . $plugin_init );
				} elseif ( ! is_plugin_active( $plugin_init ) ) {
					$not_activated[] = $plugin;
					Astra_Sites_Importer_Log::add( 'Plugin verification failed - not activated: ' . $plugin_init );
				}
			}

			if ( ! empty( $missing ) || ! empty( $not_activated ) ) {
				Astra_Sites_Importer_Log::add( 'Plugin verification failed. Missing: ' . count( $missing ) . ', Not activated: ' . count( $not_activated ) );
				wp_send_json_error(
					array(
						'message'       => __( 'Some required plugins are not ready.', 'astra-sites' ),
						'missing'       => $missing,
						'not_activated' => $not_activated,
						'verified'      => false,
					)
				);
				return;
			}

			Astra_Sites_Importer_Log::add( 'All required plugins verified successfully.' );
			wp_send_json_success(
				array(
					'message'  => __( 'All plugins verified.', 'astra-sites' ),
					'verified' => true,
				)
			);
		}


/** Function track_onboarding_step() called by wp_ajax hooks: {'astra_sites_track_onboarding_step'} **/
/** Parameters found in function track_onboarding_step(): {"post": ["step_visited", "step_status"]} **/
function track_onboarding_step() {
			// Verify Nonce.
			check_ajax_referer( 'wp_rest', '_ajax_nonce' );

			if ( ! current_user_can( 'customize' ) ) {
				wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'astra-sites' ) );
			}

			if ( empty( $_POST ) || ! isset( $_POST['step_visited'] ) ) {
				wp_send_json_error( esc_html__( 'Missing required parameter.', 'astra-sites' ) );
			}

			$step = sanitize_text_field( wp_unslash( $_POST['step_visited'] ) );

			// Valid steps
			$valid_steps = array(
				'welcome',
				'page-builder',
				'template-listing',
				'template-preview',
				'features',
				'survey',
				'import'
			);

			// Validate step key
			if ( ! in_array( $step, $valid_steps, true ) ) {
				wp_send_json_error( esc_html__( 'Invalid step.', 'astra-sites' ) );
			}

			$step_status = isset( $_POST['step_status'] ) ? sanitize_text_field( $_POST['step_status'] ) : 'yes';

			// Validate step status.
			if ( ! in_array( $step_status, array( 'yes', 'skipped' ), true ) ) {
				$step_status = 'yes';
			}

			if( ! class_exists( 'Astra_Sites_Page' ) ) {
				require_once ASTRA_SITES_DIR . 'inc/classes/class-astra-sites-page.php';
			}
			// Get existing tracked steps
			$steps_visited = Astra_Sites_Page::get_instance()->get_setting( 'steps_visited' );

			// Initialize if not array
			if ( ! is_array( $steps_visited ) ) {
				$steps_visited = array();
			}

			// Check if step is already tracked or status is being updated to skipped.
			$existing_status = isset( $steps_visited[ $step ] ) ? $steps_visited[ $step ] : null;
			if ( null === $existing_status || ( 'skipped' === $step_status && 'skipped' !== $existing_status ) ) {
				$steps_visited[ $step ] = $step_status;

				Astra_Sites_Page::get_instance()->update_settings( [
					'steps_visited' => $steps_visited,
				] );

				/**
				 * Fires when an onboarding step is visited for the first time or its status changes.
				 *
				 * @since 4.5.2
				 * @param string $step The step identifier.
				 */
				do_action( 'astra_sites_onboarding_step_visited', $step );
			}

			wp_send_json_success();
		}


/** Function import_cart_abandonment_recovery() called by wp_ajax hooks: {'astra-sites-import-cart-abandonment-recovery'} **/
/** No params detected :-/ **/


/** Function hide_notices() called by wp_ajax hooks: {'ast_block_templates_hide_notices'} **/
/** Parameters found in function hide_notices(): {"request": ["notice_type"]} **/
function hide_notices() {

		if ( ! current_user_can( 'manage_ast_block_templates' ) ) {
			wp_send_json_error( __( 'You are not allowed to perform this action', 'astra-sites' ) );
		}

		check_ajax_referer( 'ast-block-templates-ajax-nonce', '_ajax_nonce' );

		$notice_type = isset( $_REQUEST['notice_type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['notice_type'] ) ) : '';

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


/** Function api_request() called by wp_ajax hooks: {'ast_block_templates_data_option'} **/
/** Parameters found in function api_request(): {"post": ["url"]} **/
function api_request() {

			// Verify Nonce.
			check_ajax_referer( 'astra-sites', '_ajax_nonce' );

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_send_json_error();
			}

			$url = isset( $_POST['url'] ) ? sanitize_text_field( $_POST['url'] ) : '';

			if ( empty( $url ) ) {
				wp_send_json_error(
					array(
						'message' => __( 'Provided API URL is empty! Please try again!', 'astra-sites' ),
						'code'    => 'Error',
					)
				);
			}

			$api_args = apply_filters(
				'astra_sites_api_params', array(
					'template_status' => '',
					'version' => ASTRA_SITES_VER,
				)
			);

			/**
			 * Filter to modify the API Request URL.
			 *
			 * @param string $api_url The complete API request URL.
			 *
			 * @since 4.4.43
			 */
			$api_url = apply_filters( 'astra_sites_api_request_' . $url, trailingslashit( self::get_instance()->get_api_domain() ) . 'wp-json/wp/v2/' . $url );
			$api_url = add_query_arg( $api_args, $api_url );

			if ( ! astra_sites_is_valid_url( $api_url ) ) {
				wp_send_json_error(
					array(
						/* Translators: %s is API URL. */
						'message' => sprintf( __( 'Invalid API Request URL - %s', 'astra-sites' ), $api_url ),
						'code'    => 'Error',
					)
				);
			}

			Astra_Sites_Error_Handler::get_instance()->start_error_handler();

			$api_args = apply_filters(
				'astra_sites_api_args', array(
					'timeout' => 15,
				)
			);

			$request = wp_safe_remote_get( $api_url, $api_args );

			Astra_Sites_Error_Handler::get_instance()->stop_error_handler();

			if ( is_wp_error( $request ) ) {
				$wp_error_code = $request->get_error_code();
				switch ( $wp_error_code ) {
					case 'http_request_not_executed':
						/* translators: %s Error Message */
						$message = sprintf( __( 'API Request could not be performed - %s', 'astra-sites' ), $request->get_error_message() );
						break;
					case 'http_request_failed':
					default:
						/* translators: %s Error Message */
						$message = sprintf( __( 'API Request has failed - %s', 'astra-sites' ), $request->get_error_message() );
						break;
				}

				wp_send_json_error(
					array(
						'message'       => $request->get_error_message(),
						'code'          => 'WP_Error',
						'response_code' => $wp_error_code,
					)
				);
			}

			$code      = (int) wp_remote_retrieve_response_code( $request );
			$demo_data = json_decode( wp_remote_retrieve_body( $request ), true );

			if ( 200 === $code ) {
				$class_list = isset( $demo_data['class_list'] ) ? $demo_data['class_list'] : array();
				if ( in_array( 'astra-site-page-builder-gutenberg', $class_list, true ) ) {
					$spectra_blocks_version = isset( $demo_data['spectra-blocks-ver'] ) ? $demo_data['spectra-blocks-ver'] : array();
					$spectra_blocks_ver     = empty( $spectra_blocks_version ) || ! in_array( 'spectra-blocks-ver-v3', $class_list, true ) ? 'v2' : 'v3';
					update_option( 'astra_sites_current_spectra_blocks_ver', $spectra_blocks_ver );

					// For v3 templates, replace the `ultimate-addons-for-gutenberg` entry in
					// required-plugins with `spectra-blocks` before the data is saved and sent.
					// This ensures every downstream step — plugin status check, install, activate,
					// and verify — works with the correct plugin without additional overrides.
					if (
						'v3' === $spectra_blocks_ver &&
						isset( $demo_data['required-plugins'] ) &&
						is_array( $demo_data['required-plugins'] )
					) {
						$demo_data['required-plugins'] = array_map(
							function ( $plugin ) {
								if ( isset( $plugin['init'] ) && 'ultimate-addons-for-gutenberg/ultimate-addons-for-gutenberg.php' === $plugin['init'] ) {
									$plugin['init'] = 'spectra-blocks/spectra-blocks.php';
									$plugin['slug'] = 'spectra-blocks';
									$plugin['name'] = 'Spectra Blocks';
								}
								return $plugin;
							},
							$demo_data['required-plugins']
						);
					}
				} else {
					delete_option( 'astra_sites_current_spectra_blocks_ver' );
				}

				Astra_Sites_File_System::get_instance()->update_demo_data( $demo_data );
				update_option( 'astra_sites_current_import_template_type', 'classic' );

				wp_send_json_success( $demo_data );
			}

			$message       = wp_remote_retrieve_body( $request );
			$response_code = $code;

			if ( 200 !== $code && is_array( $demo_data ) && isset( $demo_data['code'] ) ) {
				$message = $demo_data['message'];
			}

			if ( 500 === $code ) {
				$message = __( 'Internal Server Error.', 'astra-sites' );
			}

			if ( 200 !== $code && false !== strpos( $message, 'Cloudflare' ) ) {
				$ip = Astra_Sites_Helper::get_client_ip();
				/* translators: %s IP address. */
				$message = sprintf( __( 'Client IP: %1$s </br> Error code: %2$s', 'astra-sites' ), $ip, $code );
				$code    = 'Cloudflare';
			}

			wp_send_json_error(
				array(
					'message'       => $message,
					'code'          => $code,
					'response_code' => $response_code,
				)
			);
		}


/** Function dismiss_ai_promotion() called by wp_ajax hooks: {'astra-sites-dismiss-ai-promotion'} **/
/** Parameters found in function dismiss_ai_promotion(): {"request": ["dismiss_ai_promotion"]} **/
function dismiss_ai_promotion() {

			check_ajax_referer( 'astra-sites', '_ajax_nonce' );

			// Only admins can save settings.
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'You are not allowed to perform this action', 'astra-sites' ) );
			}

			// Stored Settings.
			$stored_data = $this->get_settings();

			// New settings.
			$new_data = array(
				'dismiss_ai_promotion' => ( isset( $_REQUEST['dismiss_ai_promotion'] ) ) ? sanitize_key( $_REQUEST['dismiss_ai_promotion'] ) : false,
			);

			// Merge settings.
			$data = wp_parse_args( $new_data, $stored_data );

			// Update settings.
			update_option( 'astra_sites_settings', $data, 'no' );
			wp_send_json_success( __( 'Notice Dismissed!', 'astra-sites' ) );
		}


/** Function import_blocks() called by wp_ajax hooks: {'astra-sites-import-blocks'} **/
/** No params detected :-/ **/


/** Function generate_ai_content() called by wp_ajax hooks: {'ast-block-templates-regenerate'} **/
/** Parameters found in function generate_ai_content(): {"post": ["category", "regenerate", "block_type", "is_last_category"]} **/
function generate_ai_content() {
		// Verify Nonce.
		check_ajax_referer( 'ast-block-templates-ai-content', 'security' );

		if ( ! current_user_can( 'manage_ast_block_templates' ) ) {
			wp_send_json_error( array( 'message' => __( 'Sorry, you are not allowed to perform this action.', 'astra-sites' ) ), 403 );
		}

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
			'regenerate' => isset( $_POST['regenerate'] ) ? wp_validate_boolean( sanitize_text_field( wp_unslash( $_POST['regenerate'] ) ) ) : false,
			'block_type' => isset( $_POST['block_type'] ) ? sanitize_text_field( wp_unslash( $_POST['block_type'] ) ) : 'block',
			'is_last_category' => isset( $_POST['is_last_category'] ) ? wp_validate_boolean( sanitize_text_field( wp_unslash( $_POST['is_last_category'] ) ) ) : false,
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


/** Function import_latepoint() called by wp_ajax hooks: {'astra-sites-import-latepoint'} **/
/** No params detected :-/ **/


/** Function save_auto_open_setting() called by wp_ajax hooks: {'ast_block_templates_save_auto_open_setting'} **/
/** Parameters found in function save_auto_open_setting(): {"post": ["nonce", "auto_open"]} **/
function save_auto_open_setting() {
		// Verify nonce.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ast-block-templates-ajax-nonce' ) ) {
			wp_send_json_error( array( 'message' => 'Security check failed' ) );
		}

		// Check user capabilities.
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( array( 'message' => 'Insufficient permissions' ) );
		}

		// Get and validate the value from POST.
		$raw = isset( $_POST['auto_open'] ) ? sanitize_text_field( wp_unslash( $_POST['auto_open'] ) ) : null;
		if ( null === $raw ) {
			$auto_open = null;
		} else {
			$auto_open = wp_validate_boolean( $raw );
		}

		if ( null === $auto_open ) {
			wp_send_json_error( array( 'message' => 'Invalid value send!' ) );
		}

		// Save the user meta.
		$user_id = get_current_user_id();
		$result  = update_user_meta( $user_id, 'ast_block_templates_auto_open_design_library', (int) $auto_open );

		// Send response based on the actual saved value.
		if ( false === $result ) {
			wp_send_json_error( array( 'message' => 'No change in value!' ) );
		}

		// Check what was actually saved.
		$saved_value = (bool) get_user_meta( $user_id, 'ast_block_templates_auto_open_design_library', true );
		if ( $saved_value === $auto_open ) {
			wp_send_json_success(
				array(
					'message' => 'Setting saved successfully',
					'value'   => $saved_value,
				)
			);
		}
 
		wp_send_json_error(
			array(
				'message'        => 'Failed to save setting - value mismatch',
				'saved_value'    => $saved_value,
				'expected_value' => $auto_open,
			)
		);
	}


/** Function ajax_sites_requests_count() called by wp_ajax hooks: {'ast-block-templates-get-sites-request-count'} **/
/** No params detected :-/ **/


/** Function get_all_categories_and_tags() called by wp_ajax hooks: {'astra-sites-get-all-categories-and-tags'} **/
/** No params detected :-/ **/


/** Function import_template_kit() called by wp_ajax hooks: {'ast_block_templates_import_template_kit'} **/
/** Parameters found in function import_template_kit(): {"request": ["content"]} **/
function import_template_kit() {

		if ( ! current_user_can( 'manage_ast_block_templates' ) ) {
			wp_send_json_error( __( 'You are not allowed to perform this action', 'astra-sites' ) );
		}
		// Verify Nonce.
		check_ajax_referer( 'ast-block-templates-ajax-nonce', '_ajax_nonce' );

		// Allow the SVG tags in batch update process.
		add_filter( 'wp_kses_allowed_html', array( $this, 'allowed_tags_and_attributes' ), 10, 2 );

		$ids_mapping = get_option( 'ast_block_templates_wpforms_ids_mapping', array() );

		// Gutenberg serialized block markup cannot be pre-sanitized with wp_kses_post() because that would
		// strip the <!-- wp:block {...} --> comment delimiters that parse_blocks() requires. Do NOT use
		// sanitize_post_field( 'post_content', ..., 'raw' ) here — the 'raw' context is a no-op in WP core
		// (returns the value unchanged), so it provides no actual sanitization. This handler never writes
		// to the DB — it returns processed content to the browser via wp_send_json_success(). The content
		// is sanitized by WordPress core (wp_kses_post()) when the user saves the post.
		$raw_content = isset( $_REQUEST['content'] ) ? wp_unslash( $_REQUEST['content'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$content     = force_balance_tags( (string) ( is_array( $raw_content ) ? implode( '', $raw_content ) : $raw_content ) );

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

		/**
		 * Fires after a template kit is successfully imported.
		 *
		 * @since 2.4.21
		 *
		 * @param string $content The imported content after processing (e.g., hotlink replacement).
		 */
		do_action( 'ast_block_templates_after_kit_import', $content );

		// Update content.
		wp_send_json_success( $content );
	}


/** Function get_latest_credit_details() called by wp_ajax hooks: {'zip_ai_get_latest_credit_details'} **/
/** No params detected :-/ **/


/** Function import_block_categories() called by wp_ajax hooks: {'astra-sites-import-block-categories'} **/
/** No params detected :-/ **/


/** Function ajax_import_sites() called by wp_ajax hooks: {'ast-block-templates-import-sites'} **/
/** Parameters found in function ajax_import_sites(): {"post": ["page_no", "total"]} **/
function ajax_import_sites() {

		if ( ! current_user_can( 'manage_ast_block_templates' ) ) {
			wp_send_json_error( __( 'You are not allowed to perform this action', 'astra-sites' ) );
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

			if ( isset( $_POST['total'] ) && absint( $_POST['total'] ) === $page_no ) {
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


/** Function sites_requests_count() called by wp_ajax hooks: {'astra-sites-get-sites-request-count'} **/
/** No params detected :-/ **/


/** Function get_all_sites() called by wp_ajax hooks: {'astra-sites-get-all-sites'} **/
/** No params detected :-/ **/


/** Function toggle_assistant_status_ajax() called by wp_ajax hooks: {'zip_ai_toggle_assistant_status_ajax'} **/
/** Parameters found in function toggle_assistant_status_ajax(): {"post": ["enable_zip_chat"]} **/
function toggle_assistant_status_ajax() {
		// If the current user does not have the required capability, then abandon ship.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Insufficient permissions', 'astra-sites' ) ), 403 );
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


/** Function import_all_categories_and_tags() called by wp_ajax hooks: {'astra-sites-import-all-categories-and-tags'} **/
/** No params detected :-/ **/


/** Function get_color_palette() called by wp_ajax hooks: {'ast_block_templates_color_palette'} **/
/** No params detected :-/ **/


/** Function download_selected_image() called by wp_ajax hooks: {'astra-sites-download-image'} **/
/** Parameters found in function download_selected_image(): {"post": ["index"]} **/
function download_selected_image() {
		Helper::verify_ajax_request( 'manage_options', __( 'You do not have permission to do this action.', 'astra-sites' ) );

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verification is done in verify_ajax_request() called above.
		$index  = isset( $_POST['index'] ) ? sanitize_text_field( wp_unslash( $_POST['index'] ) ) : '';
		$images = Ai_Builder_ZipWP_Integration::get_business_details( 'images' );

		if ( empty( $images ) || ! is_array( $images ) ) {
			Helper::error_response(
				array(
					'data'   => __( 'Image not downloaded!', 'astra-sites' ),
					'status' => true,
				)
			);
			return;
		}

		$image = $images[ $index ];

		if ( empty( $image ) || ! is_array( $image ) ) {
			Helper::error_response(
				array(
					'data'   => __( 'Image not downloaded!', 'astra-sites' ),
					'status' => true,
				)
			);
			return;
		}

		$prepare_image = array(
			'id'          => $image['id'],
			'url'         => $image['url'],
			'description' => $image['description'],
		);

		Ai_Builder_Importer_Log::add( 'Downloading Image ' . $image['url'], 'info', array( 'image_index' => $index ) );

		if ( class_exists( 'STImporter\Importer\ST_Importer_Helper' ) ) {
			$id = ST_Importer_Helper::download_image( $prepare_image );
			Ai_Builder_Importer_Log::add(
				'Downloaded Image attachment id: ' . $id,
				'success',
				array(
					'attachment_id' => $id,
					'image_url'     => $image['url'],
				)
			);

			Helper::success_response(
				array(
					'data'   => __( 'Image downloaded successfully!', 'astra-sites' ),
					'status' => true,
				)
			);
			return;
		}

		Helper::error_response(
			array(
				'data'   => __( 'Required function not found!', 'astra-sites' ),
				'status' => false,
			)
		);
	}


/** Function import_block() called by wp_ajax hooks: {'ast_block_templates_import_block'} **/
/** Parameters found in function import_block(): {"request": ["content", "category", "id", "style", "block_type", "disableAI"]} **/
function import_block() {
		
		if ( ! current_user_can( 'manage_ast_block_templates' ) ) {
			wp_send_json_error( __( 'You are not allowed to perform this action', 'astra-sites' ) );
		}
		// Verify Nonce.
		check_ajax_referer( 'ast-block-templates-ajax-nonce', '_ajax_nonce' );

		// Allow the SVG tags in batch update process.
		add_filter( 'wp_kses_allowed_html', array( $this, 'allowed_tags_and_attributes' ), 10, 2 );

		$ids_mapping = get_option( 'ast_block_templates_wpforms_ids_mapping', array() );

		// Gutenberg serialized block markup cannot be pre-sanitized with wp_kses_post() because that would
		// strip the <!-- wp:block {...} --> comment delimiters that parse_blocks() requires. Do NOT use
		// sanitize_post_field( 'post_content', ..., 'raw' ) here — the 'raw' context is a no-op in WP core
		// (returns the value unchanged), so it provides no actual sanitization. This handler never writes
		// to the DB — it returns processed content to the browser via wp_send_json_success(). The content
		// is sanitized by WordPress core (wp_kses_post()) when the user saves the post.
		$raw_content = isset( $_REQUEST['content'] ) ? wp_unslash( $_REQUEST['content'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$content     = force_balance_tags( (string) ( is_array( $raw_content ) ? implode( '', $raw_content ) : $raw_content ) );
		$category = isset( $_REQUEST['category'] ) ? intval( $_REQUEST['category'] ) : '';

		// Fix invalid escaped single quotes.
		$content = str_replace( "\\'", "'", $content );

		$block_id = isset( $_REQUEST['id'] ) ? absint( $_REQUEST['id'] ) : '';

		// Empty mapping? Then return.
		if ( ! empty( $ids_mapping ) ) {
			// Replace ID's.
			foreach ( $ids_mapping as $old_id => $new_id ) {
				$content = str_replace( '[wpforms id="' . $old_id, '[wpforms id="' . $new_id, $content );
				$content = str_replace( '[wpforms id=' . $old_id, '[wpforms id=' . $new_id, $content );
				$content = str_replace( "[wpforms id='" . $old_id, "[wpforms id='" . $new_id, $content );
				$content = str_replace( '{"formId":"' . $old_id . '"}', '{"formId":"' . $new_id . '"}', $content );
			}
		}

		// SureForms ID's mapping.
		$sureforms_ids_mapping = get_option( 'ast_block_templates_sureforms_ids_mapping', array() );
		if ( ! empty( $sureforms_ids_mapping ) ) {
			// Replace ID's.
			foreach ( $sureforms_ids_mapping as $old_id => $new_id ) {
				$content = str_replace( '[sureforms id="' . $old_id, '[sureforms id="' . $new_id, $content );
				$content = str_replace( '[sureforms id=' . $old_id, '[sureforms id=' . $new_id, $content );
				$content = str_replace( "[sureforms id='" . $old_id, "[sureforms id='" . $new_id, $content );
				$content = str_replace( '<!-- wp:srfm/form {"id":' . $old_id . '}', '<!-- wp:srfm/form {"id":' . $new_id . '}', $content );
			}
		}

		$style = isset( $_REQUEST['style'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['style'] ) ) : 'style-1';
		$block_type = isset( $_REQUEST['block_type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['block_type'] ) ) : 'block';
		$adaptive_mode = $this->get_adaptive_mode();

		$color_palettes = array();
		$is_astra_theme = class_exists( 'Astra_Global_Palette' );

		// If Astra 4.8.0+ color compatibility is enabled, remap the palette indexes.
		// Swap color positions: 4 ↔ 5 and 6 ↔ 7 to match the new palette order.
		$reorganize = class_exists( '\Astra_Dynamic_CSS' ) && is_callable( '\Astra_Dynamic_CSS::astra_4_8_0_compatibility' ) && \Astra_Dynamic_CSS::astra_4_8_0_compatibility();

		if ( 'block' === $block_type ) {
			$mapping_palette = array(
				'style-1' => $reorganize ? array( 0, 1, 2, 3, 4, 5, 7, 6, 8 ) : array( 0, 1, 2, 3, 5, 4, 6, 7, 8 ),
				'style-2' => $reorganize ? array( 0, 1, 2, 3, 5, 4, 7, 6, 8 ) : array( 0, 1, 2, 3, 4, 5, 6, 7, 8 ),
				'style-3' => $reorganize ? array( 3, 2, 4, 5, 0, 1, 7, 0, 8 ) : array( 3, 2, 5, 4, 0, 1, 6, 0, 8 ),
			);
		} else {
			$mapping_palette = array(
				'style-1' => $reorganize ? array( 0, 1, 2, 3, 5, 4, 7, 6, 8 ) : array( 0, 1, 2, 3, 4, 5, 6, 7, 8 ),
				'style-2' => $reorganize ? array( 0, 1, 4, 5, 3, 2, 7, 6, 8 ) : array( 0, 1, 5, 4, 3, 2, 6, 7, 8 ),
			);
		}
		$color_palettes = $this->get_block_palette_colors()[ $style ]['colors'];
		
		if ( ! $adaptive_mode ) {
			for ( $i = 0; $i < 9; $i++ ) {
				$target = $color_palettes[ $i ];
				$content = str_replace( 'var(\u002d\u002dast-global-color-' . $i . ')', $target, $content );
				$content = str_replace( 'var(--ast-global-color-' . $i . ')', $target, $content );
				$content = str_replace( 'var:preset|color|ast-global-color-' . $i, $target, $content );
				$content = str_replace( 'ast-global-color-' . $i, $target, $content );
			}

			$blocks = parse_blocks( $content );
			// Recursively update the button colors.
			$this->walk_blocks_and_update_buttons( $blocks );
			$content = serialize_blocks( $blocks );

			// Add border radius properties after inheritFromTheme is set to false.
			if ( $content && is_string( $content ) ) {
				$border_properties = ',"btnBorderBottomLeftRadius":4,"btnBorderBottomRightRadius":4,"btnBorderTopLeftRadius":4,"btnBorderTopRightRadius":4';
				$content = preg_replace_callback(
					'/("inheritFromTheme"\s*:\s*false)/', function( $matches ) use ( $border_properties ) {
						return $matches[1] . $border_properties;
					}, $content 
				);
			}
		} else {
			for ( $i = 0; $i < 9; $i++ ) {
				$target = $mapping_palette[ $style ][ $i ];
				$content = str_replace( 'var(\u002d\u002dast-global-color-' . $i . ')', 'var(\u002d\u002dast-global-color-temp-' . $target . ')', $content );
				$content = str_replace( 'var(--ast-global-color-' . $i . ')', 'var(--ast-global-color-temp-' . $target . ')', $content );
				$content = str_replace( 'ast-global-color-' . $i, 'ast-global-color-temp-' . $target, $content );
			}
			$content = str_replace( 'var(\u002d\u002dast-global-color-temp-', 'var(\u002d\u002dast-global-color-', $content );
			$content = str_replace( 'var(--ast-global-color-temp-', 'var(--ast-global-color-', $content );
			$content = str_replace( 'ast-global-color-temp-', 'ast-global-color-', $content );
		}

		$disable_ai = isset( $_REQUEST['disableAI'] ) ? 'true' === sanitize_text_field( wp_unslash( $_REQUEST['disableAI'] ) ) : false;

		if ( ! $disable_ai && ! empty( Importer_Helper::get_business_details( 'business_description' ) ) ) {
			$category_content = get_option( 'ast-templates-ai-content', array() );
			$dynamic_content = ( isset( $category_content[ $category ] ) ) ? $category_content[ $category ] : array();
			$content = $this->replace( $content ?? '', $dynamic_content );
		} else {
			$content = $this->maybe_import_images( $content ?? '' );
		}

		// Flush the object when import is successful.
		delete_option( 'ast-block-templates_data-' . $block_id );

		/**
		 * Fires after a block is successfully imported.
		 *
		 * @since 2.4.21
		 *
		 * @param int|string $block_id   The ID of the imported block.
		 * @param string     $block_type The type of the block (e.g., 'block' or 'site').
		 * @param int|string $category   The category ID of the imported block.
		 */
		do_action( 'ast_block_templates_after_block_import', $block_id, $block_type, $category );

		// Update content.
		wp_send_json_success( $content );

	}


/** Function get_fresh_credit_details() called by wp_ajax hooks: {'zip_ai_get_fresh_credit_details'} **/
/** No params detected :-/ **/


/** Function ajax_import_blocks() called by wp_ajax hooks: {'ast-block-templates-import-blocks'} **/
/** Parameters found in function ajax_import_blocks(): {"post": ["page_no", "total"]} **/
function ajax_import_blocks() {

		if ( ! current_user_can( 'manage_ast_block_templates' ) ) {
			wp_send_json_error( __( 'You are not allowed to perform this action', 'astra-sites' ) );
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

			if ( isset( $_POST['total'] ) && absint( $_POST['total'] ) === $page_no ) {
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


/** Function handle_dismiss_notice_ajax() called by wp_ajax hooks: {'dismiss_gs_notice'} **/
/** Parameters found in function handle_dismiss_notice_ajax(): {"post": ["_security", "notice_id"]} **/
function handle_dismiss_notice_ajax() {
		// Verify nonce for security.
		if ( ! wp_verify_nonce( sanitize_text_field( $_POST['_security'] ?? '' ), 'gs_dismiss_woopayments_notice' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Security verification failed.', 'astra-sites' ),
				)
			);
		}

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Insufficient permissions.', 'astra-sites' ),
				)
			);
		}

		// Get notice ID.
		$notice_id = isset( $_POST['notice_id'] ) ? sanitize_text_field( wp_unslash( $_POST['notice_id'] ) ) : '';

		if ( empty( $notice_id ) ) {
			wp_send_json_error( 'Notice ID is required' );
		}

		$show_after = 14 * 24 * 60 * 60; // 2 weeks in seconds.

		// Set a transient that expires in 2 weeks (1209600 seconds) - to dismiss the notice.
		set_transient( 'getting_started_notice_dismissed', true, $show_after );

		wp_send_json_success( 'Notice dismissed successfully' );
	}


/** Function import_cartflows() called by wp_ajax hooks: {'astra-sites-import-cartflows'} **/
/** No params detected :-/ **/


/** Function template_importer() called by wp_ajax hooks: {'ast_block_templates_kit_importer', 'ast_block_templates_importer'} **/
/** Parameters found in function template_importer(): {"request": ["id"]} **/
function template_importer() {

		if ( ! current_user_can( 'manage_ast_block_templates' ) ) {
			wp_send_json_error( __( 'You are not allowed to perform this action', 'astra-sites' ) );
		}
		// Verify Nonce.
		check_ajax_referer( 'ast-block-templates-ajax-nonce', '_ajax_nonce' );

		$block_id   = isset( $_REQUEST['id'] ) ? absint( $_REQUEST['id'] ) : '';
		$block_data = get_option( 'ast-block-templates_data-' . $block_id );

		$api_uri = null !== $block_data ? $block_data->{'astra-page-api-url'} : '';

		// Early return.
		if ( '' == $api_uri ) {
			wp_send_json_error( __( 'Something wrong', 'astra-sites' ) );
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


/** Function reset_business_details() called by wp_ajax hooks: {'ast-block-templates-reset-business-details'} **/
/** No params detected :-/ **/


/** Function activate_plugin() called by wp_ajax hooks: {'ast_block_templates_activate_plugin'} **/
/** Parameters found in function activate_plugin(): {"post": ["init"]} **/
function activate_plugin() {

		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error( __( 'You are not allowed to perform this action.', 'astra-sites' ) );
		}

		check_ajax_referer( 'ast-block-templates-ajax-nonce', 'security' );

		$plugin_init = ( isset( $_POST['init'] ) ) ? sanitize_text_field( wp_unslash( $_POST['init'] ) ) : '';

		if ( empty( $plugin_init ) ) {
			wp_send_json_error( __( 'Plugin slug is missing.', 'astra-sites' ) );
		}

		/**
		 * Plugins the library is allowed to activate on the user's behalf.
		 *
		 * @since 2.4.31
		 * @param array $plugins List of plugin basenames.
		 */
		$allowed_plugins = apply_filters(
			'ast_block_templates_installable_plugins',
			array(
				'ultimate-addons-for-gutenberg/ultimate-addons-for-gutenberg.php',
				'spectra-blocks/spectra-blocks.php',
				'wpforms-lite/wpforms.php',
				'sureforms/sureforms.php',
			)
		);

		if ( ! in_array( $plugin_init, $allowed_plugins, true ) ) {
			wp_send_json_error( __( 'This plugin is not allowed to be activated.', 'astra-sites' ) );
		}

		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		// Nothing to do when the plugin is already active.
		if ( is_plugin_active( $plugin_init ) ) {
			wp_send_json_success(
				array(
					'message' => 'Plugin already active.',
				)
			);
		}

		wp_clean_plugins_cache();

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


/** Function import_all_categories() called by wp_ajax hooks: {'astra-sites-import-all-categories'} **/
/** No params detected :-/ **/


/** Function sse_import() called by wp_ajax hooks: {'astra-wxr-import'} **/
/** Parameters found in function sse_import(): {"request": ["xml_id"]} **/
function sse_import( $xml_url = '' ) {
		// Log import start.
		ST_Importer_Log::add(
			'info',
			'WXR import process started',
			array(
				'xml_url_provided' => ! empty( $xml_url ),
			)
		);

		if ( wp_doing_ajax() ) {

			// Verify Nonce.
			check_ajax_referer( 'astra-sites', '_ajax_nonce' );

			if ( ! current_user_can( 'manage_options' ) ) {
				// Log permission error.
				ST_Importer_Log::add(
					'error',
					'Permission denied: User lacks manage_options capability',
					array(
						'user_id'   => get_current_user_id(),
						'user_caps' => current_user_can( 'manage_options' ) ? 'true' : 'false',
					)
				);

				wp_send_json_error(
					array(
						'error' => __( "Permission denied: You don't have sufficient permissions to perform this action. Please contact your site administrator.", 'astra-sites' ),
					)
				);
			}

			// Start the event stream.
			header( 'Content-Type: text/event-stream, charset=UTF-8' );
			header( 'Cache-Control: no-cache' );
			header( 'Connection: keep-alive' );
			// Turn off PHP output compression.
			$previous = error_reporting( error_reporting() ^ E_WARNING ); //phpcs:ignore WordPress.PHP.DevelopmentFunctions, WordPress.PHP.DiscouragedPHPFunctions -- 3rd party library.
			ini_set( 'output_buffering', 'off' ); //phpcs:ignore WordPress.PHP.IniSet.Risky, Generic.PHP.ForbiddenFunctions.FoundWithAlternative -- 3rd party library.
			ini_set( 'zlib.output_compression', false ); //phpcs:ignore WordPress.PHP.IniSet.Risky, Generic.PHP.ForbiddenFunctions.FoundWithAlternative -- 3rd party library.
			error_reporting( $previous ); //phpcs:ignore WordPress.PHP.DevelopmentFunctions, WordPress.PHP.DiscouragedPHPFunctions -- 3rd party library.

			if ( $GLOBALS['is_nginx'] ) {
				// Setting this header instructs Nginx to disable fastcgi_buffering
				// and disable gzip for this request.
				header( 'X-Accel-Buffering: no' );
				header( 'Content-Encoding: none' );
			}

			// 2KB padding for IE.
			echo esc_html( ':' . str_repeat( ' ', 2048 ) . "\n\n" );
		}

		$xml_id = isset( $_REQUEST['xml_id'] ) ? absint( $_REQUEST['xml_id'] ) : '';
		if ( ! empty( $xml_id ) ) {
			$xml_url = get_attached_file( $xml_id );
		}

		// Check for existing progress to prevent duplicate content.
		if ( $this->is_wxr_import_in_progress() ) {
			return;
		}

		// Enhanced XML file validation.
		if ( empty( $xml_url ) ) {
			// Log validation error - empty XML URL.
			ST_Importer_Log::add(
				'fatal',
				'XML file URL is empty or not provided',
				array(
					'xml_url' => $xml_url,
					'xml_id'  => isset( $_REQUEST['xml_id'] ) ? absint( $_REQUEST['xml_id'] ) : '',
				)
			);

			$this->wxr_import_transient_cleanup();
			$this->emit_sse_message(
				array(
					'action' => 'complete',
					'error'  => __( 'Template content file not found or inaccessible. The import process cannot continue without the content file. Please try importing again.', 'astra-sites' ),
				)
			);
			if ( wp_doing_ajax() ) {
				exit;
			}
			return;
		}

		if ( ! file_exists( $xml_url ) ) {
			// Log validation error - file does not exist.
			ST_Importer_Log::add(
				'fatal',
				'XML file does not exist on server',
				array(
					'xml_url' => $xml_url,
				)
			);

			$this->wxr_import_transient_cleanup();
			$this->emit_sse_message(
				array(
					'action' => 'complete',
					'error'  => __( 'Template content file does not exist on the server. The file may have been deleted or moved. Please try re-importing the template.', 'astra-sites' ),
				)
			);
			if ( wp_doing_ajax() ) {
				exit;
			}
			return;
		}

		if ( ! is_readable( $xml_url ) ) {
			// Log validation error - file not readable.
			ST_Importer_Log::add(
				'fatal',
				'XML file is not readable due to file permission issues',
				array(
					'xml_url'    => $xml_url,
					'file_perms' => substr( sprintf( '%o', fileperms( $xml_url ) ), -4 ),
				)
			);

			$this->wxr_import_transient_cleanup();
			$this->emit_sse_message(
				array(
					'action' => 'complete',
					'error'  => __( 'Template content file cannot be read due to file permission issues. Please contact your hosting provider to fix file permissions.', 'astra-sites' ),
				)
			);
			if ( wp_doing_ajax() ) {
				exit;
			}
			return;
		}

		// Time to run the import!
		if ( function_exists( 'set_time_limit' ) ) {
			\set_time_limit( 0 ); // phpcs:ignore Generic.PHP.ForbiddenFunctions.FoundWithAlternative -- Required for long-running import process.
		}

		// Ensure we're not buffered.
		wp_ob_end_flush_all();
		flush();

		do_action( 'astra_sites_before_sse_import' );

		// Enable default GD library.
		add_filter( 'wp_image_editors', array( $this, 'enable_wp_image_editor_gd' ) );

		// Change GUID image URL.
		add_filter( 'wxr_importer.pre_process.post', array( $this, 'fix_image_duplicate_issue' ), 10, 4 );

		// Are we allowed to create users?
		add_filter( 'wxr_importer.pre_process.user', '__return_null' );

		// Keep track of our progress.
		add_action( 'wxr_importer.processed.post', array( $this, 'imported_post' ), 10, 2 );
		add_action( 'wxr_importer.process_failed.post', array( $this, 'imported_post' ), 10, 2 );
		add_action( 'wxr_importer.process_already_imported.post', array( $this, 'already_imported_post' ), 10, 2 );
		add_action( 'wxr_importer.process_skipped.post', array( $this, 'already_imported_post' ), 10, 2 );
		add_action( 'wxr_importer.processed.comment', array( $this, 'imported_comment' ) );
		add_action( 'wxr_importer.process_already_imported.comment', array( $this, 'imported_comment' ) );
		add_action( 'wxr_importer.processed.term', array( $this, 'imported_term' ) );
		add_action( 'wxr_importer.process_failed.term', array( $this, 'imported_term' ) );
		add_action( 'wxr_importer.process_already_imported.term', array( $this, 'imported_term' ) );
		add_action( 'wxr_importer.processed.user', array( $this, 'imported_user' ) );
		add_action( 'wxr_importer.process_failed.user', array( $this, 'imported_user' ) );

		// Keep track of our progress.
		add_action( 'wxr_importer.processed.post', array( $this, 'track_post' ), 10, 2 );
		add_action( 'wxr_importer.processed.term', array( $this, 'track_term' ) );

		// Remove content_save_pr filter to avoid unwanted content filtering. Replicating the same from super admin.
		$has_content_filter = has_filter( 'content_save_pre', 'wp_filter_post_kses' );
		if (
			is_multisite() &&
			current_user_can( 'activate_plugins' ) &&
			$has_content_filter
		) {
			remove_filter( 'content_save_pre', 'wp_filter_post_kses' );
		}

		// Flush once more.
		flush();

		/**
		 * Importer instance.
		 *
		 * @var \WXR_Importer $importer Importer instance.
		 */
		$importer = $this->get_importer();

		// Log import progress started.
		ST_Importer_Log::add(
			'info',
			'Starting WXR file import process',
			array(
				'xml_url'   => $xml_url,
				'file_size' => filesize( $xml_url ),
			)
		);

		// Pre-scan the WXR to collect valid post IDs for orphaned attachment filtering.
		// Skip for AI imports -- their WXR files are dynamically generated and won't have orphans.
		if ( 'ai' !== get_option( 'astra_sites_current_import_template_type' ) ) {
			$this->wxr_post_ids = $this->prescan_wxr_post_ids( $xml_url );
		}

		try {
			$response = $importer->import( $xml_url );
		} catch ( \Exception $e ) {
			// Log exception during import.
			ST_Importer_Log::add(
				'error',
				'Exception caught during WXR import: ' . $e->getMessage(),
				array(
					'exception_message' => $e->getMessage(),
					'exception_code'    => $e->getCode(),
					'xml_url'           => $xml_url,
				)
			);

			$this->wxr_import_transient_cleanup();
			$this->emit_sse_message(
				array(
					'action'    => 'complete',
					'error'     => $this->get_contextual_import_error_message( $e->getMessage() ),
					'technical' => $e->getMessage(),
				)
			);
			if ( wp_doing_ajax() ) {
				exit;
			}
			return;
		} catch ( \Error $e ) {
			// Log fatal error during import.
			ST_Importer_Log::add(
				'fatal',
				'Fatal error occurred during WXR import: ' . $e->getMessage(),
				array(
					'error_message' => $e->getMessage(),
					'error_code'    => $e->getCode(),
					'xml_url'       => $xml_url,
				)
			);

			$this->wxr_import_transient_cleanup();
			$this->emit_sse_message(
				array(
					'action'    => 'complete',
					'error'     => __( 'A fatal error occurred during import, likely due to server resource limitations. Try a different template, or contact your hosting provider to increase server resources.', 'astra-sites' ),
					'technical' => $e->getMessage(),
				)
			);
			if ( wp_doing_ajax() ) {
				exit;
			}
			return;
		}

		// Let the browser know we're done.
		$complete = array(
			'action' => 'complete',
			'error'  => false,
		);
		if ( is_wp_error( $response ) ) {
			// Log WP_Error response.
			ST_Importer_Log::add(
				'error',
				'WP_Error returned from WXR importer: ' . $response->get_error_message(),
				array(
					'error_message' => $response->get_error_message(),
					'error_code'    => $response->get_error_code(),
					'xml_url'       => $xml_url,
				)
			);

			$complete['error']     = $this->get_contextual_import_error_message( $response->get_error_message() );
			$complete['technical'] = $response->get_error_message();
		} else {
			// Log successful import completion.
			ST_Importer_Log::add(
				'success',
				'WXR import completed successfully',
				array(
					'xml_url' => $xml_url,
				)
			);
		}

		// Restore the content filter.
		if ( is_multisite() && $has_content_filter ) {
			add_filter( 'content_save_pre', 'wp_filter_post_kses' );
		}

		$this->emit_sse_message( $complete );
		if ( wp_doing_ajax() ) {
			exit;
		}
	}


/** Function import_prepare_xml() called by wp_ajax hooks: {'astra-sites-import_prepare_xml'} **/
/** No params detected :-/ **/


/** Function import_page_builders() called by wp_ajax hooks: {'astra-sites-import-page-builders'} **/
/** No params detected :-/ **/


/** Function import_sureforms() called by wp_ajax hooks: {'ast_block_templates_import_sureforms'} **/
/** Parameters found in function import_sureforms(): {"request": ["id"]} **/
function import_sureforms() {
		if ( ! current_user_can( 'manage_ast_block_templates' ) ) {
			wp_send_json_error( __( 'You are not allowed to perform this action', 'astra-sites' ) );
		}
		// Verify Nonce.
		check_ajax_referer( 'ast-block-templates-ajax-nonce', '_ajax_nonce' );

		$block_id   = isset( $_REQUEST['id'] ) ? absint( $_REQUEST['id'] ) : '';
		$block_data = get_option( 'ast-block-templates_data-' . $block_id );
		$block_data = null !== $block_data ? $block_data : '';

		$sureforms_url = '';
		if ( 'astra-blocks' === $block_data->{'type'} ) {
			$sureforms_url = $block_data->{'post-meta'}->{'astra-site-sureforms-path'};
		}

		if ( 'site-pages' === $block_data->{'type'} ) {
			$sureforms_url = $block_data->{'astra-site-sureforms-path'};
		}

		if ( empty( $sureforms_url ) ) {
			wp_send_json_error( __( 'Empty SureForms URL', 'astra-sites' ) );
		}

		// Download JSON file.
		$file_path = $this->download_file( $sureforms_url );

		if ( ! $file_path['success'] ) {
			wp_send_json_error(
				array(
					'message' => $file_path['data'],
					'url'     => $sureforms_url,
				)
			);
		}

		$ids_mapping = array();
		if ( isset( $file_path['data']['file'] ) ) {
			$ext = strtolower( pathinfo( $file_path['data']['file'], PATHINFO_EXTENSION ) );
			if ( 'json' === $ext ) {
				/**
				 *
				 * Retrieves the contents of a file using the specified file system.
				 *
				 * @var \WP_Filesystem_Base|null $filesystem
				 * */
				$filesystem   = Helper::instance()->ast_block_templates_get_filesystem();
				$file_content = $filesystem ? $filesystem->get_contents( $file_path['data']['file'] ) : '';

				// Apply the form color for sureforms.
				$adaptive_mode = $this->get_adaptive_mode();
				if ( ! $adaptive_mode && $file_content ) {
					$color_palettes = $this->get_block_palette_colors()['style-1']['colors'];
					for ( $i = 0; $i < 9; $i++ ) {
						$target = $color_palettes[ $i ];
						$file_content = str_replace( 'var(--ast-global-color-' . $i . ')', $target, $file_content );
					}
				}
				$forms = json_decode( $file_content ? $file_content : '', true );

				if ( ! empty( $forms ) && defined( 'SRFM_VER' ) && class_exists( '\SRFM\Inc\Export' ) && is_callable( '\SRFM\Inc\Export::get_instance' ) ) {

					/**
					 * Instance of SureForms Export class.
					 *
					 * @var \SRFM\Inc\Export $import_instance
					 */
					$import_instance = \SRFM\Inc\Export::get_instance();

					if ( is_object( $import_instance ) && is_callable( array( $import_instance, 'import_forms_with_meta' ) ) ) {
						$response = \SRFM\Inc\Export::get_instance()->import_forms_with_meta( $forms, 'publish' );
						if ( is_wp_error( $response ) ) {
							wp_send_json_error( $response->get_error_message() );
						}

						if ( is_array( $response ) && ! empty( $response ) ) {
							$ids_mapping = $response;
						}
					}
				}
			}
		}

		update_option( 'ast_block_templates_sureforms_ids_mapping', $ids_mapping );

		wp_send_json_success( $ids_mapping );
	}


/** Function import_wpforms() called by wp_ajax hooks: {'ast_block_templates_import_wpforms', 'astra-sites-import-wpforms'} **/
/** Parameters found in function import_wpforms(): {"request": ["screen", "id"]} **/
function import_wpforms( $wpforms_url = '' ) {
			Astra_Sites_Importer_Log::add( '---' . PHP_EOL );
			Astra_Sites_Importer_Log::add( 'Starting WP Forms import process' );

			if ( ! defined( 'WP_CLI' ) && wp_doing_ajax() ) {
				// Verify Nonce.
				check_ajax_referer( 'astra-sites', '_ajax_nonce' );

				if ( ! current_user_can( 'customize' ) ) {
					wp_send_json_error( __( "Permission denied: You don't have the required capability to import forms. Please contact your site administrator.", 'astra-sites' ) );
				}
			}

			Astra_Sites_Helper::extend_time_limit();

			try {
				$screen = ( isset( $_REQUEST['screen'] ) ) ? sanitize_text_field( $_REQUEST['screen'] ) : '';
				$id = ( isset( $_REQUEST['id'] ) ) ? absint( $_REQUEST['id'] ) : '';

				// Get WPForms URL with enhanced error handling.
				if ( 'elementor' === $screen ) {
					if ( empty( $id ) ) {
						Astra_Sites_Helper::error_response(
							sprintf(
								// translators: %s is Elementor plugin name.
								__( 'WPForms import failed: Template ID is missing. Unable to proceed with %s import.', 'astra-sites' ),
								'Elementor'
							)
						);
						return;
					}
					$wpforms_url = astra_sites_get_wp_forms_url( $id );
					Astra_Sites_Importer_Log::add( 'Retrieved WPForms URL for Elementor template: ' . $wpforms_url );
				} else {
					$wpforms_url = astra_get_site_data( 'astra-site-wpforms-path' );
				}

				$ids_mapping = array();

				$forms = $this->download_and_validate_import_data( $wpforms_url );
				if ( is_wp_error( $forms ) ) {
					Astra_Sites_Helper::error_response(
						sprintf(
							// translators: %s is the error message.
							__( 'WPForms import failed: %s', 'astra-sites' ),
							$forms->get_error_message()
						)
					);
					return;
				}

				if ( empty( $forms ) ) {
					Astra_Sites_Helper::success_response(
						array(
							'message' => __( 'No WP Forms to import.', 'astra-sites' ),
							'file'    => $wpforms_url,
						)
					);
					return;
				}

				// Ensure WPForms plugin is loaded.
				$result = $this->ensure_plugin_loaded(
					'wpforms-lite/wpforms.php',
					'WPForms',
					function() {
						return function_exists( 'wpforms_encode' );
					}
				);

				if ( is_wp_error( $result ) ) {
					Astra_Sites_Helper::error_response( $result->get_error_message() );
					return;
				}

				// Process forms with error handling.
				foreach ( $forms as $form ) {
					if ( ! is_array( $form ) ) {
						continue; // Skip invalid form data.
					}

					$title = ! empty( $form['settings']['form_title'] ) ? sanitize_text_field( $form['settings']['form_title'] ) : '';
					$desc  = ! empty( $form['settings']['form_desc'] ) ? sanitize_textarea_field( $form['settings']['form_desc'] ) : '';

					if ( empty( $title ) ) {
						continue; // Skip forms without titles.
					}

					$new_id = post_exists( $title );

					if ( ! $new_id ) {
						try {
							$new_id = wp_insert_post(
								array(
									'post_title'   => $title,
									'post_status'  => 'publish',
									'post_type'    => 'wpforms',
									'post_excerpt' => $desc,
								)
							);

							if ( is_wp_error( $new_id ) ) {
								astra_sites_error_log( 'WPForms import error: ' . $new_id->get_error_message() );
								continue; // Skip this form and continue with others.
							}

							if ( defined( 'WP_CLI' ) ) {
								WP_CLI::line( 'Imported Form ' . $title );
							}

							// Set meta for tracking the post..
							update_post_meta( $new_id, '_astra_sites_imported_wp_forms', true );
							Astra_Sites_Importer_Log::add( 'Successfully inserted WP Form ID ' . $new_id . ' - ' . $title, 'success' );

						} catch ( Exception $e ) {
							astra_sites_error_log( 'WPForms post creation error: ' . $e->getMessage() );
							continue; // Skip this form and continue with others.
						}
					}

					if ( $new_id && ! empty( $form['id'] ) ) {
						// ID mapping..
						$ids_mapping[ $form['id'] ] = $new_id;

						$form['id'] = $new_id;

						try {
							$encoded_form = wpforms_encode( $form );
							wp_update_post(
								array(
									'ID' => $new_id,
									'post_content' => $encoded_form,
								)
							);
						} catch ( Exception $e ) {
							astra_sites_error_log( 'WPForms content update error: ' . $e->getMessage() );
							// Continue even if content update fails.
						}
					}
				}

				// Save ID mapping.
				update_option( 'astra_sites_wpforms_ids_mapping', $ids_mapping, 'no' );
				Astra_Sites_Importer_Log::add( 'WPForms ID mappings saved: ' . count( $ids_mapping ) . ' mappings' );

				Astra_Sites_Helper::success_response(
					array(
						'message' => __( 'WP Forms Imported.', 'astra-sites' ),
						'mapping' => $ids_mapping,
					)
				);
				return;
			} catch ( \Exception $e ) {
				// Catch any unexpected errors.
				astra_sites_error_log( 'WPForms import error: ' . $e->getMessage() );

				Astra_Sites_Helper::error_response(
					sprintf(
						// translators: %s is exception error message.
						__( 'WPForms import failed: Unexpected error - %s', 'astra-sites' ),
						$e->getMessage()
					)
				);
				return;
			} catch ( \Error $e ) {
				Astra_Sites_Helper::error_response(
					sprintf(
						// translators: %s: Fatal error message.
						__( 'WPForms import failed: Fatal Error: %s', 'astra-sites' ),
						$e->getMessage()
					)
				);
				return;
			}
		}


/** Function blocks_requests_count() called by wp_ajax hooks: {'astra-sites-get-blocks-request-count'} **/
/** No params detected :-/ **/


/** Function enable_other_builders() called by wp_ajax hooks: {'astra-sites-show-other-builders'} **/
/** No params detected :-/ **/


/** Function send_plugin_deactivate_feedback() called by wp_ajax hooks: {'uds_plugin_deactivate_feedback'} **/
/** Parameters found in function send_plugin_deactivate_feedback(): {"post": ["reason", "feedback", "referer", "version", "source"]} **/
function send_plugin_deactivate_feedback() {

			$response_data = array( 'message' => __( 'Sorry, you are not allowed to do this operation.', 'astra-sites' ) );

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
				$response_data = array( 'message' => __( 'Nonce validation failed', 'astra-sites' ) );
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


/** Function bsf_analytics_optin_status() called by wp_ajax hooks: {'astra_sites_usage_optin_status'} **/
/** Parameters found in function bsf_analytics_optin_status(): {"post": ["bsfUsageTracking"]} **/
function bsf_analytics_optin_status() {
			// Verify Nonce.
			check_ajax_referer( 'astra-sites', '_ajax_nonce' );

			if ( ! current_user_can( 'customize' ) ) {
				wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'astra-sites' ) );
			}

			if ( empty( $_POST ) || ! isset( $_POST['bsfUsageTracking'] ) ) {
				wp_send_json_error( esc_html__( 'Missing required parameter.', 'astra-sites' ) );
			}

			$opt_in = filter_input( INPUT_POST, 'bsfUsageTracking', FILTER_VALIDATE_BOOLEAN ) ? 'yes' : 'no';

			update_site_option( 'astra_sites_usage_optin', $opt_in );

			wp_send_json_success( esc_html__( 'Usage tracking updated successfully.', 'astra-sites' ) );
		}


/** Function save_page_builder_on_ajax() called by wp_ajax hooks: {'astra-sites-change-page-builder'} **/
/** Parameters found in function save_page_builder_on_ajax(): {"request": ["page_builder"]} **/
function save_page_builder_on_ajax() {

			check_ajax_referer( 'astra-sites', '_ajax_nonce' );

			// Only admins can save settings.
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error();
			}

			// Stored Settings.
			$stored_data = $this->get_settings();

			// New settings.
			$new_data = array(
				'page_builder' => ( isset( $_REQUEST['page_builder'] ) ) ? sanitize_key( $_REQUEST['page_builder'] ) : '',
			);

			// Merge settings.
			$data = wp_parse_args( $new_data, $stored_data );

			// Update settings.
			update_option( 'astra_sites_settings', $data, 'no' );

			$sites = $this->get_sites_by_page_builder( $new_data['page_builder'] );

			wp_send_json_success( $sites );
		}


/** Function zipwp_insert_image() called by wp_ajax hooks: {'zipwp_images_insert_image'} **/
/** Parameters found in function zipwp_insert_image(): {"post": ["url", "name", "description", "id"]} **/
function zipwp_insert_image(): void {
		// Verify Nonce.
		check_ajax_referer( 'zipwp-images', '_ajax_nonce' );

		if ( ! current_user_can( 'upload_files' ) ) {
			wp_send_json_error( __( 'You are not allowed to perform this action', 'astra-sites' ) );
		}

		$url      = isset( $_POST['url'] ) ? esc_url_raw( wp_unslash( $_POST['url'] ) ) : '';
		$name     = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : false;
		$desc     = isset( $_POST['description'] ) ? sanitize_text_field( wp_unslash( $_POST['description'] ) ) : '';
		$photo_id = isset( $_POST['id'] ) ? sanitize_key( wp_unslash( $_POST['id'] ) ) : 0;

		// For unsplash images, photo_id can be alphanumeric.
		if ( strpos( $url, 'unsplash.com' ) === false ) {
			$photo_id = absint( $photo_id );
		}

		if ( 0 === $photo_id ) {
			wp_send_json_error( __( 'Need to send photo ID', 'astra-sites' ) );
		}

		if ( empty( $url ) ) {
			wp_send_json_error( __( 'Need to send URL of the image to be downloaded', 'astra-sites' ) );
		}

		$image  = '';
		$result = array();

		$name  = pathinfo( (string) $name, PATHINFO_FILENAME ) . '-' . $photo_id . '.jpg';
		$image = $this->create_image_from_url( $url, $name, (string) $photo_id, $desc );

		if ( empty( $image ) ) {
			wp_send_json_error( __( 'Could not download the image.', 'astra-sites' ) );
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


/** Function import_sites() called by wp_ajax hooks: {'astra-sites-import-sites'} **/
/** No params detected :-/ **/


/** Function verify_authenticity() called by wp_ajax hooks: {'zip_ai_verify_authenticity'} **/
/** No params detected :-/ **/


/** Function skip_spectra_pro_onboarding() called by wp_ajax hooks: {'ast_skip_zip_ai_onboarding'} **/
/** No params detected :-/ **/


/** Function dismiss_woopayments_notice() called by wp_ajax hooks: {'dismiss_woopayments_notice'} **/
/** Parameters found in function dismiss_woopayments_notice(): {"post": ["_security", "notice_id"]} **/
function dismiss_woopayments_notice() {
			// Verify nonce.
			if ( ! isset( $_POST['_security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_security'] ) ), 'woopayments_nonce' ) ) {
				wp_send_json_error( __( 'Invalid nonce', 'astra-sites' ) );
			}

			// Get notice ID.
			$notice_id = isset( $_POST['notice_id'] ) ? sanitize_text_field( wp_unslash( $_POST['notice_id'] ) ) : '';

			if ( empty( $notice_id ) ) {
				wp_send_json_error( __( 'Notice ID is required', 'astra-sites' ) );
			}

			$show_after = 72 * 60 * 60; // 72 hours in seconds.

			// Set a transient that expires in 72 hours (259200 seconds) - to dismiss the notice.
			set_transient( 'astra_sites_woopayments_notice_dismissed', true, $show_after );

			if ( class_exists( 'Astra_Sites_Page' ) ) {
				// Update the setting to track dismissed count.
				$dismissed_count = Astra_Sites_Page::get_instance()->get_setting( 'woopayments_banner_dismissed_count', 0 );
				Astra_Sites_Page::get_instance()->update_settings(
					array(
						'woopayments_banner_dismissed_count' => $dismissed_count + 1,
					)
				);
			}

			/**
			 * Fires after the WooPayments banner is dismissed.
			 *
			 * @since 1.2.74
			 */
			do_action( 'astra_sites_woopayments_banner_dismissed' );

			wp_send_json_success( 'Notice dismissed successfully' );
		}


/** Function sync_via_ajax() called by wp_ajax hooks: {'ast-block-templates-check-sync-library-status'} **/
/** No params detected :-/ **/


/** Function disabler_ajax() called by wp_ajax hooks: {'zip_ai_disabler_ajax'} **/
/** Parameters found in function disabler_ajax(): {"post": ["disable_zip_ai_assistant"]} **/
function disabler_ajax() {
		// If the current user does not have the required capability, then abandon ship.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Insufficient permissions', 'astra-sites' ) ), 403 );
		}

		// Verify the nonce.
		check_ajax_referer( 'zip_ai_admin_nonce', 'nonce' );

		// Have a variable to check if the module was disabled.
		$is_module_disabled = false;

		// Check if the Zip AI Assistant was requested to be disabled.
		if ( ! empty( sanitize_text_field( wp_unslash( $_POST['disable_zip_ai_assistant'] ?? '' ) ) ) ) {
			$is_module_disabled = Module::disable( 'ai_assistant' );
		}

		// Send the status based on whether the Zip AI Library is enabled or not.
		if ( $is_module_disabled ) {
			wp_send_json_success();
		} else {
			wp_send_json_error();
		}
	}


/** Function dismiss_notice() called by wp_ajax hooks: {'astra-notice-dismiss'} **/
/** Parameters found in function dismiss_notice(): {"post": ["notice_id", "repeat_notice_after"]} **/
function dismiss_notice() {
			check_ajax_referer( 'astra-notices', 'nonce' );

			$notice_id           = ( isset( $_POST['notice_id'] ) ) ? sanitize_key( wp_unslash( $_POST['notice_id'] ) ) : '';
			$repeat_notice_after = ( isset( $_POST['repeat_notice_after'] ) ) ? absint( wp_unslash( $_POST['repeat_notice_after'] ) ) : 0;
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
				wp_send_json_error( esc_html__( 'Permission denied.', 'astra-sites' ) );
			}

			$allowed_notices = get_option( 'astra_notices_allowed', array() ); // Get allowed notices.

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
				wp_send_json_error( esc_html__( 'Invalid notice ID.', 'astra-sites' ) );
			}

			// Valid inputs?
			if ( ! empty( $notice_id ) ) {

				if ( in_array( $notice_id, $wp_default_meta_keys, true ) ) {
					wp_send_json_error( esc_html__( 'Invalid notice ID.', 'astra-sites' ) );
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


/** Function update_library_complete() called by wp_ajax hooks: {'astra-sites-update-library-complete'} **/
/** No params detected :-/ **/


/** Function save_user_details() called by wp_ajax hooks: {'ast-block-templates-ai-content'} **/
/** No params detected :-/ **/


/** Function get_all_categories() called by wp_ajax hooks: {'astra-sites-get-all-categories'} **/
/** No params detected :-/ **/


/** Function set_woopayments_analytics() called by wp_ajax hooks: {'astra_sites_set_woopayments_analytics'} **/
/** Parameters found in function set_woopayments_analytics(): {"post": ["nonce", "source"]} **/
function set_woopayments_analytics() {
			// Verify user has permission to modify settings.
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'astra-sites' ) ) );
				exit;
			}

			// Verify nonce.
			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'woopayments_nonce' ) ) {
				wp_send_json_error( array( 'message' => __( 'Invalid nonce', 'astra-sites' ) ) );
				exit;
			}

			$source = isset( $_POST['source'] ) ? sanitize_text_field( wp_unslash( $_POST['source'] ) ) : '';
			if ( ! in_array( $source, array( 'banner', 'onboarding' ), true ) ) {
				wp_send_json_error( array( 'message' => __( 'Invalid source', 'astra-sites' ) ) );
				exit;
			}

			$key = "woopayments_{$source}_clicked";
			Astra_Sites_Page::get_instance()->update_settings( array( $key => true ) );

			// Track as a one-time event.
			self::$events->track(
				$key,
				ASTRA_SITES_VER,
				array( 'source' => $source )
			);

			wp_send_json_success( array( 'message' => 'WooPayments analytics updated!' ) );
			exit;
		}


