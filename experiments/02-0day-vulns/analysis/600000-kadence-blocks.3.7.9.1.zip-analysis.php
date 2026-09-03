<?php
/***
*
*Found actions: 18
*Found functions:15
*Extracted functions:15
*Total parameter names extracted: 15
*Overview: {'prebuilt_templates_data_reload_ajax_callback': {'kadence_import_reload_prebuilt_templates_data'}, 'process_data_ajax_callback': {'kadence_import_process_data'}, 'prebuilt_data_reload_ajax_callback': {'kadence_import_reload_prebuilt_data'}, 'process_image_data_ajax_callback': {'kadence_import_process_image_data'}, 'process_ajax': {'nopriv_kb_process_ajax_submit', 'kb_process_ajax_submit', 'kb_process_advanced_form_submit', 'nopriv_kb_process_advanced_form_submit'}, 'process_subscribe_ajax_callback': {'kadence_subscribe_process_data'}, 'prebuilt_templates_data_ajax_callback': {'kadence_import_get_prebuilt_templates_data'}, 'prebuilt_pages_data_ajax_callback': {'kadence_import_get_prebuilt_pages_data'}, 'process_pattern_ajax_callback': {'kadence_import_process_pattern'}, 'prebuilt_pages_data_reload_ajax_callback': {'kadence_import_reload_prebuilt_pages_data'}, 'ajax_blocks_save_config': {'kadence_blocks_save_config'}, 'ajax_default_editor_width': {'kadence_post_default_width'}, 'ajax_blocks_activate_deactivate': {'kadence_blocks_activate_deactivate'}, 'prebuilt_data_ajax_callback': {'kadence_import_get_prebuilt_data'}, 'prebuilt_connection_info_ajax_callback': {'kadence_import_get_new_connection_data'}}
*
***/

/** Function prebuilt_templates_data_reload_ajax_callback() called by wp_ajax hooks: {'kadence_import_reload_prebuilt_templates_data'} **/
/** Parameters found in function prebuilt_templates_data_reload_ajax_callback(): {"post": ["product_id", "product_slug"]} **/
function prebuilt_templates_data_reload_ajax_callback() {

		// Verify if the AJAX call is valid (checks nonce and current_user_can).
		$this->verify_ajax_call();
		$this->local_template_data_path = '';
		$this->api_key                  = $this->get_stored_license_key();
		$this->api_email                = $this->get_stored_license_email();
		$this->product_id               = empty( $_POST['product_id'] ) ? '' : sanitize_text_field( $_POST['product_id'] );
		$this->product_slug             = empty( $_POST['product_slug'] ) ? '' : sanitize_text_field( $_POST['product_slug'] );
		$this->package                  = 'templates';
		$this->url                      = $this->remote_templates_url;
		$this->key                      = 'blocks';

		// $removed = $this->delete_block_library_folder();
		// if ( ! $removed ) {
		// wp_send_json_error( 'failed_to_flush' );
		// }
		// Do you have the data?
		$get_data = $this->get_template_data( true );

		if ( ! $get_data ) {
			// Send JSON Error response to the AJAX call.
			wp_send_json( esc_html__( 'No library data', 'kadence-blocks' ) );
		} else {
			wp_send_json( $get_data );
		}
		die;
	}


/** Function process_data_ajax_callback() called by wp_ajax hooks: {'kadence_import_process_data'} **/
/** Parameters found in function process_data_ajax_callback(): {"post": ["import_content", "import_library", "import_type", "import_item_id", "import_style", "package", "url", "key"]} **/
function process_data_ajax_callback() {
		// Verify if the AJAX call is valid (checks nonce and current_user_can).
		$this->verify_ajax_call();

		if ( ! current_user_can( 'upload_files' ) ) {
			wp_send_json_error( esc_html__( 'You do not have permission to upload files.', 'kadence-blocks' ) );
		}

		$data           = empty( $_POST['import_content'] ) ? '' : stripslashes( $_POST['import_content'] );
		$import_library = empty( $_POST['import_library'] ) ? 'standard' : sanitize_text_field( $_POST['import_library'] );
		$import_type    = empty( $_POST['import_type'] ) ? 'pattern' : sanitize_text_field( $_POST['import_type'] );
		$import_id      = empty( $_POST['import_item_id'] ) ? '' : sanitize_text_field( $_POST['import_item_id'] );
		$import_style   = empty( $_POST['import_style'] ) ? 'normal' : sanitize_text_field( $_POST['import_style'] );
		$this->api_key  = $this->get_stored_license_key();
		$this->package  = empty( $_POST['package'] ) ? 'section' : sanitize_text_field( $_POST['package'] );
		$this->url      = $this->resolve_library_url( empty( $_POST['url'] ) ? '' : sanitize_text_field( $_POST['url'] ), '/wp-json/kadence-cloud/v1/get/', $this->remote_url );
		$this->key      = isset( $_POST['key'] ) && ! empty( $_POST['key'] ) ? sanitize_text_field( $_POST['key'] ) : 'section';
		$data           = $this->process_content( $data, $import_library, $import_type, $import_id, $import_style );
		if ( ! $data ) {
			// Send JSON Error response to the AJAX call.
			wp_send_json( esc_html__( 'No data', 'kadence-blocks' ) );
		} else {
			wp_send_json( $data );
		}
		die;
	}


/** Function prebuilt_data_reload_ajax_callback() called by wp_ajax hooks: {'kadence_import_reload_prebuilt_data'} **/
/** Parameters found in function prebuilt_data_reload_ajax_callback(): {"post": ["product_id", "product_slug", "package", "url", "key", "is_template"]} **/
function prebuilt_data_reload_ajax_callback() {

		// Verify if the AJAX call is valid (checks nonce and current_user_can).
		$this->verify_ajax_call();
		$this->local_template_data_path = '';
		$this->api_key                  = $this->get_stored_license_key();
		$this->api_email                = $this->get_stored_license_email();
		$this->product_id               = empty( $_POST['product_id'] ) ? '' : sanitize_text_field( $_POST['product_id'] );
		$this->product_slug             = empty( $_POST['product_slug'] ) ? '' : sanitize_text_field( $_POST['product_slug'] );
		$this->package                  = empty( $_POST['package'] ) ? 'section' : sanitize_text_field( $_POST['package'] );
		$this->url                      = $this->resolve_library_url( empty( $_POST['url'] ) ? '' : sanitize_text_field( $_POST['url'] ), '/wp-json/kadence-cloud/v1/get/', $this->remote_url );
		$this->key                      = empty( $_POST['key'] ) ? 'section' : sanitize_text_field( $_POST['key'] );
		$this->is_template              = isset( $_POST['is_template'] ) && ! empty( $_POST['is_template'] ) ? true : false;
		if ( empty( $this->url ) ) {
			wp_send_json( esc_html__( 'No library data', 'kadence-blocks' ) );
		}

		// $removed = $this->delete_block_library_folder();
		// if ( ! $removed ) {
		// wp_send_json_error( 'failed_to_flush' );
		// }
		// Do you have the data?
		$get_data = $this->get_template_data( true );

		if ( ! $get_data ) {
			// Send JSON Error response to the AJAX call.
			wp_send_json( esc_html__( 'No library data', 'kadence-blocks' ) );
		} else {
			wp_send_json( $get_data );
		}
		die;
	}


/** Function process_image_data_ajax_callback() called by wp_ajax hooks: {'kadence_import_process_image_data'} **/
/** Parameters found in function process_image_data_ajax_callback(): {"post": ["import_content", "image_library"]} **/
function process_image_data_ajax_callback() {
		// Verify if the AJAX call is valid (checks nonce and current_user_can).
		$this->verify_ajax_call();

		// Require upload capability to prevent contributors from uploading to media library via this endpoint.
		if ( ! current_user_can( 'upload_files' ) ) {
			wp_send_json_error( esc_html__( 'You do not have permission to upload files.', 'kadence-blocks' ) );
		}

		$content       = empty( $_POST['import_content'] ) ? '' : stripslashes( $_POST['import_content'] );
		$image_library = empty( $_POST['image_library'] ) ? '' : json_decode( $_POST['image_library'], true );
		$data          = $this->process_image_content( $content, $image_library );
		if ( ! $data ) {
			// Send JSON Error response to the AJAX call.
			wp_send_json( esc_html__( 'No data', 'kadence-blocks' ) );
		} else {
			wp_send_json( $data );
		}
		die;
	}


/** Function process_ajax() called by wp_ajax hooks: {'nopriv_kb_process_ajax_submit', 'kb_process_ajax_submit', 'kb_process_advanced_form_submit', 'nopriv_kb_process_advanced_form_submit'} **/
/** Parameters found in function process_ajax(): {"post": ["_kb_adv_form_id", "_kb_adv_form_post_id", "recaptcha_response"]} **/
function process_ajax() {
		$final_data = array();

		if ( isset( $_POST['_kb_adv_form_id'] ) && ! empty( $_POST['_kb_adv_form_id'] ) && isset( $_POST['_kb_adv_form_post_id'] ) && ! empty( $_POST['_kb_adv_form_post_id'] ) ) {
			$this->start_buffer();
			// Nonce verification isn't used as it's not a login form but can be enabled with a filter. Note that caching the page will cause the nonce to fail after a cetain amount of time.
			if ( apply_filters( 'kadence_blocks_form_verify_nonce', false ) && ! check_ajax_referer( 'kb_form_nonce', '_kb_form_verify', false ) ) {
				$this->process_bail( __( 'Submission rejected, invalid security token. Reload the page and try again.', 'kadence-blocks' ), __( 'Token invalid', 'kadence-blocks' ) );
			}
			$post_id = sanitize_text_field( wp_unslash( $_POST['_kb_adv_form_post_id'] ) );

			$form_args = $this->get_form( $post_id );
			$messages  = $this->get_messages( $form_args['attributes'] );

			// Check Recaptcha.
			if ( self::$captcha_attrs !== false ) {
				$captcha_settings = new Kadence_Blocks_Form_Captcha_Settings( self::$captcha_attrs );

				if ( $captcha_settings->is_valid ) {
					$captcha_verify = new Kadence_Blocks_Form_Captcha_Verify( $captcha_settings );

					$valid = false;
					switch ( $captcha_settings->service ) {
						case 'googlev3':
							$token = ! empty( $_POST['recaptcha_response'] ) ? $_POST['recaptcha_response'] : '';
							$valid = $captcha_verify->verify_google( $token );
							break;
						case 'googlev2':
							$token = ! empty( $_POST['g-recaptcha-response'] ) ? $_POST['g-recaptcha-response'] : '';
							$valid = $captcha_verify->verify_google( $token );
							break;
						case 'turnstile':
							$token = ! empty( $_POST['cf-turnstile-response'] ) ? $_POST['cf-turnstile-response'] : '';
							$valid = $captcha_verify->verify_turnstile( $token );
							break;
						case 'hcaptcha':
							$token = ! empty( $_POST['h-captcha-response'] ) ? $_POST['h-captcha-response'] : '';
							$valid = $captcha_verify->verify_hcaptcha( $token );
							break;
					}

					if ( ! $valid ) {
						$this->process_bail( $messages['recaptchaerror'], __( 'CAPTCHA Failed', 'kadence-blocks' ) );
					}

					unset( $_POST['recaptcha_response'], $_POST['g-recaptcha-response'], $_POST['cf-turnstile-response'], $_POST['h-captcha-response'] );
				}
			}

			$processed_fields = apply_filters( 'kadence_blocks_advanced_form_processed_fields', $this->process_fields( $form_args['fields'] ) );

			$form_args = apply_filters( 'kadence_blocks_advanced_form_form_args', $form_args, $processed_fields, $post_id );

			$submission_rejected = apply_filters( 'kadence_blocks_advanced_form_submission_reject', false, $form_args, $processed_fields, $post_id );
			if ( $submission_rejected ) {
				$rejection_message = apply_filters(
					'kadence_blocks_advanced_form_submission_reject_message',
					__( 'Submission rejected.', 'kadence-blocks' ),
					$form_args,
					$processed_fields,
					$post_id
				);
				$this->process_bail( $rejection_message, $rejection_message );
			}

			do_action( 'kadence_blocks_advanced_form_submission', $form_args, $processed_fields, $post_id );

			$submission_results = $this->after_submit_actions( $form_args, $processed_fields, $post_id );

			if ( self::$redirect ) {
				$final_data['redirect'] = self::$redirect;
			}
			if ( isset( $form_args['attributes']['submitHide'] ) && true == $form_args['attributes']['submitHide'] ) {
				$final_data['hide'] = true;
			}

			$success = isset( $submission_results['success'] ) && $submission_results['success'];
			$final_data['submissionResults'] = $submission_results;

			$success  = apply_filters( 'kadence_blocks_advanced_form_submission_success', $success, $form_args, $processed_fields, $post_id, $submission_results );
			$messages = apply_filters( 'kadence_blocks_advanced_form_submission_messages', $messages, $form_args, $processed_fields, $post_id, $submission_results );

			if ( ! $success ) {
				$this->process_bail( $messages['error'], __( 'Third Party Failed', 'kadence-blocks' ) );
			} else {
				$final_data['html'] = '<div class="kb-adv-form-message kb-adv-form-success">' . $messages['success'] . '</div>';
				$this->send_json( $final_data );
			}
		} else {
			$this->process_bail( __( 'Submission failed', 'kadence-blocks' ), __( 'No Data', 'kadence-blocks' ) );
		}
	}


/** Function process_subscribe_ajax_callback() called by wp_ajax hooks: {'kadence_subscribe_process_data'} **/
/** Parameters found in function process_subscribe_ajax_callback(): {"post": ["email"]} **/
function process_subscribe_ajax_callback() {
		// Verify if the AJAX call is valid (checks nonce and current_user_can).
		$this->verify_ajax_call();
		$email = empty( $_POST['email'] ) ? '' : sanitize_text_field( $_POST['email'] );
		// Do you have the data?
		if ( $email && is_email( $email ) && filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			list( $user, $domain )            = explode( '@', $email );
			list( $pre_domain, $post_domain ) = explode( '.', $domain );
			$spell_issue_domains              = [ 'gmaiil', 'gmai', 'gmaill' ];
			$spell_issue_domain_ends          = [ 'local', 'comm', 'orgg', 'cmm' ];
			if ( in_array( $pre_domain, $spell_issue_domain_ends, true ) ) {
				return wp_send_json( 'emailDomainPreError' );
			}
			if ( in_array( $post_domain, $spell_issue_domain_ends, true ) ) {
				return wp_send_json( 'emailDomainPostError' );
			}
			$args = [
				'email' => $email,
				'tag'   => 'wire',
			];
			// Get the response.
			$api_url  = add_query_arg( $args, 'https://www.kadencewp.com/kadence-blocks/wp-json/kadence-subscribe/v1/subscribe/' );
			$response = wp_safe_remote_get(
				$api_url,
				[
					'timeout' => 20,
				]
			);
			// Early exit if there was an error.
			if ( is_wp_error( $response ) ) {
				return wp_send_json( 'retryError' );
			}
			// Get the CSS from our response.
			$contents = wp_remote_retrieve_body( $response );
			// Early exit if there was an error.
			if ( is_wp_error( $contents ) ) {
				return wp_send_json( 'retryError' );
			}
			if ( ! $contents ) {
				// Send JSON Error response to the AJAX call.
				wp_send_json( 'retryError' );
			} else {
				wp_send_json( $contents );
			}
		}
		// Send JSON Error response to the AJAX call.
		wp_send_json( 'emailError' );
		die;
	}


/** Function prebuilt_templates_data_ajax_callback() called by wp_ajax hooks: {'kadence_import_get_prebuilt_templates_data'} **/
/** Parameters found in function prebuilt_templates_data_ajax_callback(): {"post": ["product_id", "product_slug"]} **/
function prebuilt_templates_data_ajax_callback() {
		// Verify if the AJAX call is valid (checks nonce and current_user_can).
		$this->verify_ajax_call();
		$this->local_template_data_path = '';
		$this->api_key                  = $this->get_stored_license_key();
		$this->api_email                = $this->get_stored_license_email();
		$this->product_id               = empty( $_POST['product_id'] ) ? '' : sanitize_text_field( $_POST['product_id'] );
		$this->product_slug             = empty( $_POST['product_slug'] ) ? '' : sanitize_text_field( $_POST['product_slug'] );
		$this->package                  = 'templates';
		$this->url                      = $this->remote_templates_url;
		$this->key                      = 'blocks';
		// Do you have the data?
		$get_data = $this->get_template_data();
		if ( ! $get_data ) {
			// Send JSON Error response to the AJAX call.
			wp_send_json( esc_html__( 'No library data', 'kadence-blocks' ) );
		} else {
			wp_send_json( $get_data );
		}
		die;
	}


/** Function prebuilt_pages_data_ajax_callback() called by wp_ajax hooks: {'kadence_import_get_prebuilt_pages_data'} **/
/** Parameters found in function prebuilt_pages_data_ajax_callback(): {"post": ["product_id", "product_slug"]} **/
function prebuilt_pages_data_ajax_callback() {
		// Verify if the AJAX call is valid (checks nonce and current_user_can).
		$this->verify_ajax_call();
		$this->local_pages_data_path = '';
		$this->api_key               = $this->get_stored_license_key();
		$this->api_email             = $this->get_stored_license_email();
		$this->product_id            = empty( $_POST['product_id'] ) ? '' : sanitize_text_field( $_POST['product_id'] );
		$this->product_slug          = empty( $_POST['product_slug'] ) ? '' : sanitize_text_field( $_POST['product_slug'] );
		$this->package               = 'pages';
		$this->url                   = $this->remote_pages_url;
		$this->key                   = 'pages';
		// Do you have the data?
		$get_data = $this->get_template_data();
		if ( ! $get_data ) {
			// Send JSON Error response to the AJAX call.
			wp_send_json( esc_html__( 'No library data', 'kadence-blocks' ) );
		} else {
			wp_send_json( $get_data );
		}
		die;
	}


/** Function process_pattern_ajax_callback() called by wp_ajax hooks: {'kadence_import_process_pattern'} **/
/** Parameters found in function process_pattern_ajax_callback(): {"post": ["import_content"]} **/
function process_pattern_ajax_callback() {
		// Verify if the AJAX call is valid (checks nonce and current_user_can).
		$this->verify_ajax_call();

		if ( ! current_user_can( 'upload_files' ) ) {
			wp_send_json_error( esc_html__( 'You do not have permission to upload files.', 'kadence-blocks' ) );
		}

		$data = empty( $_POST['import_content'] ) ? '' : stripslashes( $_POST['import_content'] );
		$data = $this->process_pattern_content( $data );
		if ( ! $data ) {
			// Send JSON Error response to the AJAX call.
			wp_send_json( esc_html__( 'No data', 'kadence-blocks' ) );
		} else {
			wp_send_json( $data );
		}
		die;
	}


/** Function prebuilt_pages_data_reload_ajax_callback() called by wp_ajax hooks: {'kadence_import_reload_prebuilt_pages_data'} **/
/** Parameters found in function prebuilt_pages_data_reload_ajax_callback(): {"post": ["product_id", "product_slug"]} **/
function prebuilt_pages_data_reload_ajax_callback() {

		// Verify if the AJAX call is valid (checks nonce and current_user_can).
		$this->verify_ajax_call();
		$this->local_pages_data_path = '';
		$this->api_key               = $this->get_stored_license_key();
		$this->api_email             = $this->get_stored_license_email();
		$this->product_id            = empty( $_POST['product_id'] ) ? '' : sanitize_text_field( $_POST['product_id'] );
		$this->product_slug          = empty( $_POST['product_slug'] ) ? '' : sanitize_text_field( $_POST['product_slug'] );
		$this->package               = 'pages';
		$this->url                   = $this->remote_pages_url;
		$this->key                   = 'pages';

		// $removed = $this->delete_block_library_folder();
		// if ( ! $removed ) {
		// wp_send_json_error( 'failed_to_flush' );
		// }
		// Do you have the data?
		$get_data = $this->get_template_data( true );

		if ( ! $get_data ) {
			// Send JSON Error response to the AJAX call.
			wp_send_json( esc_html__( 'No library data', 'kadence-blocks' ) );
		} else {
			wp_send_json( $get_data );
		}
		die;
	}


/** Function ajax_blocks_save_config() called by wp_ajax hooks: {'kadence_blocks_save_config'} **/
/** Parameters found in function ajax_blocks_save_config(): {"post": ["kt_block", "config"]} **/
function ajax_blocks_save_config() {
		if ( ! check_ajax_referer( 'kadence-blocks-manage', 'wpnonce' ) ) {
			wp_send_json_error( __( 'Invalid nonce', 'kadence-blocks' ) );
		}
		if ( ! isset( $_POST['kt_block'] ) && ! isset( $_POST['config'] ) ) {
			return wp_send_json_error( __( 'Missing Content', 'kadence-blocks' ) );
		}
		// Get settings.
		$current_settings = get_option( 'kt_blocks_config_blocks' );
		$new_settings     = wp_unslash( $_POST['config'] );
		if ( ! is_array( $new_settings ) ) {
			return wp_send_json_error( __( 'Nothing to Save', 'kadence-blocks' ) );
		}
		foreach ( $new_settings as $block_key => $settings ) {
			foreach ( $settings as $attribute_key => $value ) {
				if ( is_array( $value ) ) {
					foreach ( $value as $array_attribute_index => $array_value ) {
						if ( is_array( $array_value ) ) {
							foreach ( $array_value as $array_attribute_key => $array_attribute_value ) {
								$array_attribute_value = sanitize_text_field( $array_attribute_value );
								if ( is_numeric( $array_attribute_value ) ) {
									$array_attribute_value = floatval( $array_attribute_value );
								}
								if ( 'true' === $array_attribute_value ) {
									$array_attribute_value = true;
								}
								if ( 'false' === $array_attribute_value ) {
									$array_attribute_value = false;
								}
								$new_settings[ $block_key ][ $attribute_key ][ $array_attribute_index ][ $array_attribute_key ] = $array_attribute_value;
							}
						} else {
							$array_value = sanitize_text_field( $array_value );
							if ( is_numeric( $array_value ) ) {
								$array_value = floatval( $array_value );
							}
							$new_settings[ $block_key ][ $attribute_key ][ $array_attribute_index ] = $array_value;
						}
					}
				} else {
					$value = sanitize_text_field( $value );
					if ( is_numeric( $value ) ) {
						$value = floatval( $value );
					}
					if ( 'true' === $value ) {
						$value = true;
					}
					if ( 'false' === $value ) {
						$value = false;
					}
					$new_settings[ $block_key ][ $attribute_key ] = $value;
				}
			}
		}
		$block = sanitize_text_field( wp_unslash( $_POST['kt_block'] ) );

		if ( ! is_array( $current_settings ) ) {
			$current_settings = [];
		}
		$current_settings[ $block ] = $new_settings[ $block ];
		update_option( 'kt_blocks_config_blocks', $current_settings );
		return wp_send_json_success();
	}


/** Function ajax_default_editor_width() called by wp_ajax hooks: {'kadence_post_default_width'} **/
/** Parameters found in function ajax_default_editor_width(): {"post": ["post_default"]} **/
function ajax_default_editor_width() {
		if ( ! check_ajax_referer( 'kadence-blocks-manage', 'wpnonce' ) ) {
			wp_send_json_error();
		}
		if ( ! isset( $_POST['post_default'] ) ) {
			return wp_send_json_error();
		}
		// Get variables.
		$editor_widths = get_option( 'kt_blocks_editor_width' );
		$default       = sanitize_text_field( wp_unslash( $_POST['post_default'] ) );

		if ( ! is_array( $editor_widths ) ) {
			$editor_widths = [];
		}
		$editor_widths['post_default'] = $default;

		update_option( 'kt_blocks_editor_width', $editor_widths );
		return wp_send_json_success();
	}


/** Function ajax_blocks_activate_deactivate() called by wp_ajax hooks: {'kadence_blocks_activate_deactivate'} **/
/** Parameters found in function ajax_blocks_activate_deactivate(): {"post": ["kt_block"]} **/
function ajax_blocks_activate_deactivate() {
		if ( ! check_ajax_referer( 'kadence-blocks-manage', 'wpnonce' ) ) {
			wp_send_json_error();
		}
		if ( ! isset( $_POST['kt_block'] ) ) {
			return wp_send_json_error();
		}
		// Get variables.
		$unregistered_blocks = get_option( 'kt_blocks_unregistered_blocks' );
		$block               = sanitize_text_field( wp_unslash( $_POST['kt_block'] ) );

		if ( ! is_array( $unregistered_blocks ) ) {
			$unregistered_blocks = [];
		}

		// If current block is in the array - remove it.
		if ( in_array( $block, $unregistered_blocks ) ) {
			$index = array_search( $block, $unregistered_blocks );
			if ( false !== $index ) {
				unset( $unregistered_blocks[ $index ] );
			}
			// if current block is not in the array - add it.
		} else {
			array_push( $unregistered_blocks, $block );
		}

		update_option( 'kt_blocks_unregistered_blocks', $unregistered_blocks );
		return wp_send_json_success();
	}


/** Function prebuilt_data_ajax_callback() called by wp_ajax hooks: {'kadence_import_get_prebuilt_data'} **/
/** Parameters found in function prebuilt_data_ajax_callback(): {"post": ["product_id", "product_slug", "package", "url", "key", "is_template"]} **/
function prebuilt_data_ajax_callback() {
		// Verify if the AJAX call is valid (checks nonce and current_user_can).
		$this->verify_ajax_call();
		$this->local_template_data_path = '';
		$this->api_key                  = $this->get_stored_license_key();
		$this->api_email                = $this->get_stored_license_email();
		$this->product_id               = empty( $_POST['product_id'] ) ? '' : sanitize_text_field( $_POST['product_id'] );
		$this->product_slug             = empty( $_POST['product_slug'] ) ? '' : sanitize_text_field( $_POST['product_slug'] );
		$this->package                  = empty( $_POST['package'] ) ? 'section' : sanitize_text_field( $_POST['package'] );
		$this->url                      = $this->resolve_library_url( empty( $_POST['url'] ) ? '' : sanitize_text_field( $_POST['url'] ), '/wp-json/kadence-cloud/v1/get/', $this->remote_url );
		$this->key                      = isset( $_POST['key'] ) && ! empty( $_POST['key'] ) ? sanitize_text_field( $_POST['key'] ) : 'section';
		$this->is_template              = isset( $_POST['is_template'] ) && ! empty( $_POST['is_template'] ) ? true : false;
		if ( empty( $this->url ) ) {
			wp_send_json( esc_html__( 'No library data', 'kadence-blocks' ) );
		}
		// Do you have the data?
		$get_data = $this->get_template_data();
		if ( ! $get_data ) {
			// Send JSON Error response to the AJAX call.
			wp_send_json( esc_html__( 'No library data', 'kadence-blocks' ) );
		} else {
			wp_send_json( $get_data );
		}
		die;
	}


/** Function prebuilt_connection_info_ajax_callback() called by wp_ajax hooks: {'kadence_import_get_new_connection_data'} **/
/** Parameters found in function prebuilt_connection_info_ajax_callback(): {"post": ["package", "url", "key"]} **/
function prebuilt_connection_info_ajax_callback() {
		// Verify if the AJAX call is valid (checks nonce and current_user_can).
		$this->verify_ajax_call();
		$this->local_template_data_path = '';
		$this->api_key                  = $this->get_stored_license_key();
		$this->api_email                = $this->get_stored_license_email();
		$this->package                  = empty( $_POST['package'] ) ? 'section' : sanitize_text_field( $_POST['package'] );
		$this->url                      = $this->resolve_connection_url( empty( $_POST['url'] ) ? '' : sanitize_text_field( $_POST['url'] ), '/wp-json/kadence-cloud/v1/info/' );
		$this->key                      = empty( $_POST['key'] ) ? 'section' : sanitize_text_field( $_POST['key'] );
		if ( empty( $this->url ) ) {
			wp_send_json( esc_html__( 'No Connection data', 'kadence-blocks' ) );
		}
		// Do you have the data?
		$get_data = $this->get_connection_data();
		if ( ! $get_data ) {
			// Send JSON Error response to the AJAX call.
			wp_send_json( esc_html__( 'No Connection data', 'kadence-blocks' ) );
		} elseif ( isset( $get_data['error'] ) && $get_data['error'] ) {
			wp_send_json( ( ! empty( $get_data['message'] ) ? $get_data['message'] : esc_html__( 'No Connection data available', 'kadence-blocks' ) ) );
		} else {
			// Sanitize the connection data.
			$temp_data = json_decode( $get_data, true );
			if ( ! is_array( $temp_data ) ) {
				wp_send_json( $temp_data );
			}
			$final_data            = [];
			$final_data['name']    = ! empty( $temp_data['name'] ) ? sanitize_text_field( $temp_data['name'] ) : '';
			$final_data['slug']    = ! empty( $temp_data['slug'] ) ? sanitize_text_field( $temp_data['slug'] ) : '';
			$final_data['refresh'] = ! empty( $temp_data['refresh'] ) ? sanitize_text_field( $temp_data['refresh'] ) : '';
			$final_data['expires'] = ! empty( $temp_data['expires'] ) ? sanitize_text_field( $temp_data['expires'] ) : '';
			$final_data['pages']     = ! empty( $temp_data['pages'] ) ? sanitize_text_field( $temp_data['pages'] ) : '';

			if ( ! empty( $final_data['name'] ) ) {
				wp_send_json( $final_data );
			}
			wp_send_json( esc_html__( 'No Connection data available', 'kadence-blocks' ) );
		}
		die;
	}


