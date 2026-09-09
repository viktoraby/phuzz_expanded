<?php
/***
*
*Found actions: 48
*Found functions:40
*Extracted functions:35
*Total parameter names extracted: 24
*Overview: {'ajax_recheck_ssl': {'jetpack-recheck-ssl'}, 'wp_ajax_videopress_get_upload_token': {'videopress-get-upload-token'}, 'wp_ajax_videopress_get_upload_jwt': {'videopress-get-upload-jwt'}, 'validate_password_ajax': {'validate_password_ajax', 'nopriv_validate_password_ajax'}, 'handle_optout_request': {'nopriv_privacy_optout', 'privacy_optout'}, 'ajax_delete_service': {'sharing_delete_service'}, 'ajax_save_payment_button': {'customize-jetpack-simple-payments-button-save'}, 'wp_ajax_videopress_get_playback_jwt': {'nopriv_videopress-get-playback-jwt', 'videopress-get-playback-jwt'}, '\\handle_file_download': {'jetpack_unauth_file_download'}, 'ajax_check_api_key': {'customize-contact-info-api-key'}, 'track_newsletter_category_creation': {'add-tag'}, 'handle_optout_markup': {'privacy_optout_markup', 'nopriv_privacy_optout_markup'}, 'create_post_by_email_address': {'jetpack_post_by_email_enable'}, 'ajax_get_connection': {'jetpack_get_external_connection'}, 'ajax_tracks': {'jetpack_tracks'}, 'plugin_edit_ajax': {'edit-theme-plugin-file'}, 'ajax_save_services': {'sharing_save_services'}, 'ajax_delete_payment_button': {'customize-jetpack-simple-payments-button-delete'}, 'get_attachment_comments': {'get_attachment_comments', 'nopriv_get_attachment_comments'}, 'regenerate_post_by_email_address': {'jetpack_post_by_email_regenerate'}, 'create_new_form': {'create_new_form'}, 'delete_post_by_email_address': {'jetpack_post_by_email_disable'}, 'wp_ajax_jitm_dismiss': {'jitm_dismiss'}, 'ajax_local_testing_suite': {'health-check-jetpack-connection-health'}, 'download_feedback_as_csv': {'feedback_export'}, 'ajax_update_widget_token_id': {'wpcom_instagram_widget_update_widget_token_id'}, 'ajax_request': {'grunion-contact-form', 'nopriv_grunion-contact-form'}, 'post_attachment_comment': {'post_attachment_comment', 'nopriv_post_attachment_comment'}, 'export_to_gdrive': {'grunion_export_to_gdrive'}, 'remote_request_handlers': {'nopriv_{$action}'}, '\\accept_tos': {'jetpack_accept_tos'}, 'ajax_delete_connection': {'jetpack_delete_external_connection'}, 'handle_set_consent_request': {'gdpr_set_consent', 'nopriv_gdpr_set_consent'}, 'ajax_new_service': {'sharing_new_service'}, 'theme_edit_ajax': {'edit-theme-plugin-file'}, 'wp_ajax_update_transcoding_status': {'videopress-update-transcoding-status'}, 'ajax_dismiss_handler': {'jetpack-protect-dismiss-multisite-banner'}, 'ajax_sidebar_state': {'sidebar_state'}, 'ajax_save_options': {'sharing_save_options'}, 'ajax_get_payment_buttons': {'customize-jetpack-simple-payments-buttons-get'}}
*
***/

/** Function ajax_recheck_ssl() called by wp_ajax hooks: {'jetpack-recheck-ssl'} **/
/** No params detected :-/ **/


/** Function wp_ajax_videopress_get_upload_token() called by wp_ajax hooks: {'videopress-get-upload-token'} **/
/** No params detected :-/ **/


/** Function wp_ajax_videopress_get_upload_jwt() called by wp_ajax hooks: {'videopress-get-upload-jwt'} **/
/** No params detected :-/ **/


/** Function validate_password_ajax() called by wp_ajax hooks: {'validate_password_ajax', 'nopriv_validate_password_ajax'} **/
/** Parameters found in function validate_password_ajax(): {"post": ["password", "nonce", "user_specific"]} **/
function validate_password_ajax(): void {
		// phpcs:disable WordPress.Security.NonceVerification
		if ( ! isset( $_POST['password'] ) ) {
			$this->send_json_error( __( 'No password provided.', 'jetpack-account-protection' ) );
			return;
		}

		if ( ! isset( $_POST['nonce'] ) || ! $this->verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'validate_password_nonce' ) ) {
			$this->send_json_error( __( 'Invalid nonce.', 'jetpack-account-protection' ) );
			return;
		}

		$user_specific = false;
		if ( isset( $_POST['user_specific'] ) ) {
			$user_specific = filter_var( sanitize_text_field( wp_unslash( $_POST['user_specific'] ) ), FILTER_VALIDATE_BOOLEAN );
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$password = wp_unslash( $_POST['password'] );
		// phpcs:enable WordPress.Security.NonceVerification
		$state = $this->validation_service->get_validation_state( $password, $user_specific );

		$this->send_json_success( array( 'state' => $state ) );
	}


/** Function handle_optout_request() called by wp_ajax hooks: {'nopriv_privacy_optout', 'privacy_optout'} **/
/** Parameters found in function handle_optout_request(): {"post": ["optout"]} **/
function handle_optout_request() {
		check_ajax_referer( 'ccpa_optout', 'security' );

		$optout = isset( $_POST['optout'] ) && 'true' === $_POST['optout'];
		$optout ? self::set_optout_cookie() : self::set_optin_cookie();

		// @phan-suppress-next-line PhanTypeMismatchArgumentProbablyReal -- It takes null, but its phpdoc only says int.
		wp_send_json_success( $optout, null, JSON_UNESCAPED_SLASHES );
	}


/** Function ajax_delete_service() called by wp_ajax hooks: {'sharing_delete_service'} **/
/** Parameters found in function ajax_delete_service(): {"post": ["_wpnonce", "service"]} **/
function ajax_delete_service() {
		if (
			isset( $_POST['_wpnonce'] )
			&& isset( $_POST['service'] )
			&& wp_verify_nonce(
				sanitize_key( wp_unslash( $_POST['_wpnonce'] ) ),
				'sharing-options_' . sanitize_text_field( wp_unslash( $_POST['service'] ) )
			)
		) {
			$sharer = new Sharing_Service();
			$sharer->delete_service( sanitize_text_field( wp_unslash( $_POST['service'] ) ) );
		}
	}


/** Function ajax_save_payment_button() called by wp_ajax hooks: {'customize-jetpack-simple-payments-button-save'} **/
/** Parameters found in function ajax_save_payment_button(): {"post": ["params"]} **/
function ajax_save_payment_button() {
			if ( ! check_ajax_referer( 'customize-jetpack-simple-payments', 'customize-jetpack-simple-payments-nonce', false ) ) {
				wp_send_json_error( 'bad_nonce', 400, JSON_UNESCAPED_SLASHES );
			}

			if ( ! current_user_can( 'customize' ) ) {
				wp_send_json_error( 'customize_not_allowed', 403, JSON_UNESCAPED_SLASHES );
			}

			$post_type_object = get_post_type_object( Simple_Payments::$post_type_product );
			if ( ! current_user_can( $post_type_object->cap->create_posts ) || ! current_user_can( $post_type_object->cap->publish_posts ) ) {
				wp_send_json_error( 'insufficient_post_permissions', 403, JSON_UNESCAPED_SLASHES );
			}

			if ( empty( $_POST['params'] ) || ! is_array( $_POST['params'] ) ) {
				wp_send_json_error( 'missing_params', 400, JSON_UNESCAPED_SLASHES );
			}

			$params = wp_unslash( $_POST['params'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Manually validated by validate_ajax_params().
			$errors = $this->validate_ajax_params( $params );
			if ( ! empty( $errors->errors ) ) {
				// @phan-suppress-next-line PhanTypeMismatchArgumentProbablyReal -- It takes null, but its phpdoc only says int.
				wp_send_json_error( $errors, null, JSON_UNESCAPED_SLASHES );
			}

			$product_post_id = isset( $params['product_post_id'] ) ? (int) $params['product_post_id'] : 0;

			$product_post = array(
				'ID'            => $product_post_id,
				'post_type'     => Simple_Payments::$post_type_product,
				'post_status'   => 'publish',
				'post_title'    => $params['post_title'],
				'post_content'  => $params['post_content'],
				'_thumbnail_id' => ! empty( $params['image_id'] ) ? $params['image_id'] : -1,
				'meta_input'    => array(
					'spay_currency' => $params['currency'],
					'spay_price'    => $params['price'],
					'spay_multiple' => isset( $params['multiple'] ) ? (int) $params['multiple'] : 0,
					'spay_email'    => is_email( $params['email'] ),
				),
			);

			if ( empty( $product_post_id ) ) {
				$product_post_id = wp_insert_post( $product_post );
			} else {
				$product_post_id = wp_update_post( $product_post );
			}

			if ( ! $product_post_id || is_wp_error( $product_post_id ) ) {
				// @phan-suppress-next-line PhanTypeMismatchArgumentProbablyReal -- It takes null, but its phpdoc only says int.
				wp_send_json_error( $product_post_id, null, JSON_UNESCAPED_SLASHES );
			}

			$tracks_properties = array(
				'id'       => $product_post_id,
				'currency' => $params['currency'],
				'price'    => $params['price'],
			);
			if ( 0 === $product_post['ID'] ) {
				$this->record_event( 'created', 'create', $tracks_properties );
			} else {
				$this->record_event( 'updated', 'update', $tracks_properties );
			}

			wp_send_json_success(
				array(
					'product_post_id'    => $product_post_id,
					'product_post_title' => $params['post_title'],
				),
				null, // @phan-suppress-current-line PhanTypeMismatchArgumentProbablyReal -- It takes null, but its phpdoc only says int.
				JSON_UNESCAPED_SLASHES
			);
		}


/** Function wp_ajax_videopress_get_playback_jwt() called by wp_ajax hooks: {'nopriv_videopress-get-playback-jwt', 'videopress-get-playback-jwt'} **/
/** No params detected :-/ **/


/** Function \handle_file_download() called by wp_ajax hooks: {'jetpack_unauth_file_download'} **/
/** No function found :-/ **/


/** Function ajax_check_api_key() called by wp_ajax hooks: {'customize-contact-info-api-key'} **/
/** Parameters found in function ajax_check_api_key(): {"post": ["apikey"]} **/
function ajax_check_api_key() {
			if ( isset( $_POST['apikey'] ) ) {
				if ( check_ajax_referer( 'customize_contact_info_api_key' ) && current_user_can( 'customize' ) ) {
					$apikey                     = wp_kses( wp_unslash( $_POST['apikey'] ), array() );
					$default_instance           = $this->defaults();
					$default_instance['apikey'] = $apikey;
					// @phan-suppress-next-line PhanTypeMismatchArgumentProbablyReal -- It takes null, but its phpdoc only says int.
					wp_send_json( array( 'result' => esc_html( $this->has_good_map( $default_instance ) ) ), null, JSON_UNESCAPED_SLASHES );
				}
			} else {
				wp_die();
			}
		}


/** Function track_newsletter_category_creation() called by wp_ajax hooks: {'add-tag'} **/
/** Parameters found in function track_newsletter_category_creation(): {"post": ["_wp_http_referer", "parent"]} **/
function track_newsletter_category_creation() {

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		if ( empty( $_POST['_wp_http_referer'] ) ) {
			return;
		}

		if ( strpos( sanitize_url( wp_unslash( $_POST['_wp_http_referer'] ) ), 'referer=newsletter-categories' ) > -1 ) {

			$parent = filter_var( empty( $_POST['parent'] ) ? 0 : wp_unslash( $_POST['parent'] ), FILTER_SANITIZE_NUMBER_INT );

			$is_child_category = $parent > 0;

			$tracking = new Automattic\Jetpack\Tracking();
			$tracking->tracks_record_event(
				wp_get_current_user(),
				'jetpack_newsletter_add_category',
				array(
					'is_child_category' => $is_child_category,
				)
			);
		}
	}


/** Function handle_optout_markup() called by wp_ajax hooks: {'privacy_optout_markup', 'nopriv_privacy_optout_markup'} **/
/** No params detected :-/ **/


/** Function create_post_by_email_address() called by wp_ajax hooks: {'jetpack_post_by_email_enable'} **/
/** No function found :-/ **/


/** Function ajax_get_connection() called by wp_ajax hooks: {'jetpack_get_external_connection'} **/
/** Parameters found in function ajax_get_connection(): {"request": ["service"]} **/
function ajax_get_connection() {
		if ( ! isset( $_REQUEST['service'] ) ) {
			// @phan-suppress-next-line PhanTypeMismatchArgumentProbablyReal -- It takes null, but its phpdoc only says int.
			wp_send_json( array( 'isConnected' => 'false' ), null, JSON_UNESCAPED_SLASHES );
		}

		$service = sanitize_text_field( wp_unslash( $_REQUEST['service'] ) );
		check_ajax_referer( 'jetpack_get_external_connection_' . $service );

		$connection_data = self::get_connection_data( $service );
		wp_send_json(
			array(
				'accountName'  => $connection_data['account_name'],
				'isConnected'  => $connection_data['is_connected'],
				'profileImage' => $connection_data['profile_image'],
			),
			null, // @phan-suppress-current-line PhanTypeMismatchArgumentProbablyReal -- It takes null, but its phpdoc only says int.
			JSON_UNESCAPED_SLASHES
		);
	}


/** Function ajax_tracks() called by wp_ajax hooks: {'jetpack_tracks'} **/
/** Parameters found in function ajax_tracks(): {"request": ["tracksNonce", "tracksEventName", "tracksEventType", "tracksEventProp"]} **/
function ajax_tracks() {
		// Check for nonce.
		if (
			empty( $_REQUEST['tracksNonce'] )
			|| ! wp_verify_nonce( $_REQUEST['tracksNonce'], 'jp-tracks-ajax-nonce' ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- WP core doesn't pre-sanitize nonces either.
		) {
			wp_send_json_error(
				__( 'You aren’t authorized to do that.', 'jetpack-connection' ),
				403,
				JSON_UNESCAPED_SLASHES
			);
		}

		if ( ! isset( $_REQUEST['tracksEventName'] ) || ! isset( $_REQUEST['tracksEventType'] ) ) {
			wp_send_json_error(
				__( 'No valid event name or type.', 'jetpack-connection' ),
				403,
				JSON_UNESCAPED_SLASHES
			);
		}

		$tracks_data = array();
		if ( 'click' === $_REQUEST['tracksEventType'] && isset( $_REQUEST['tracksEventProp'] ) ) {
			if ( is_array( $_REQUEST['tracksEventProp'] ) ) {
				$tracks_data = array_map( 'filter_var', wp_unslash( $_REQUEST['tracksEventProp'] ) );
			} else {
				$tracks_data = array( 'clicked' => filter_var( wp_unslash( $_REQUEST['tracksEventProp'] ) ) );
			}
		}

		$this->record_user_event( filter_var( wp_unslash( $_REQUEST['tracksEventName'] ) ), $tracks_data, null, false );

		wp_send_json_success( null, null, JSON_UNESCAPED_SLASHES );
	}


/** Function plugin_edit_ajax() called by wp_ajax hooks: {'edit-theme-plugin-file'} **/
/** Parameters found in function plugin_edit_ajax(): {"post": ["file", "newcontent", "nonce", "plugin"]} **/
function plugin_edit_ajax() {
		// This validation is based on wp_edit_theme_plugin_file().
		if ( empty( $_POST['file'] ) ) {
			return;
		}

		$file = wp_unslash( $_POST['file'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Validated manually just after.
		if ( 0 !== validate_file( $file ) ) {
			return;
		}

		if ( ! isset( $_POST['newcontent'] ) ) {
			return;
		}

		if ( ! isset( $_POST['nonce'] ) ) {
			return;
		}

		if ( empty( $_POST['plugin'] ) ) {
			return;
		}

		$plugin = wp_unslash( $_POST['plugin'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Validated manually just after.
		if ( ! current_user_can( 'edit_plugins' ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['nonce'], 'edit-plugin_' . $file ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- WP core doesn't pre-sanitize nonces either.
			return;
		}
		$plugins = get_plugins();
		if ( ! array_key_exists( $plugin, $plugins ) ) {
			return;
		}

		if ( 0 !== validate_file( $file, get_plugin_files( $plugin ) ) ) {
			return;
		}

		$real_file = WP_PLUGIN_DIR . '/' . $file;

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_is_writable
		if ( ! is_writable( $real_file ) ) {
			return;
		}

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
		$file_pointer = fopen( $real_file, 'w+' );
		if ( false === $file_pointer ) {
			return;
		}
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
		fclose( $file_pointer );
		/**
		 * This action is documented already in this file
		 */
		do_action( 'jetpack_edited_plugin', $plugin, $plugins[ $plugin ] );
	}


/** Function ajax_save_services() called by wp_ajax hooks: {'sharing_save_services'} **/
/** Parameters found in function ajax_save_services(): {"post": ["_wpnonce", "hidden", "visible"]} **/
function ajax_save_services() {
		if (
			isset( $_POST['_wpnonce'] )
			&& wp_verify_nonce( sanitize_key( wp_unslash( $_POST['_wpnonce'] ) ), 'sharing-options' )
			&& isset( $_POST['hidden'] )
			&& isset( $_POST['visible'] )
		) {
			$sharer = new Sharing_Service();

			$sharer->set_blog_services(
				explode( ',', sanitize_text_field( wp_unslash( $_POST['visible'] ) ) ),
				explode( ',', sanitize_text_field( wp_unslash( $_POST['hidden'] ) ) )
			);
			die( 0 );
		}
	}


/** Function ajax_delete_payment_button() called by wp_ajax hooks: {'customize-jetpack-simple-payments-button-delete'} **/
/** Parameters found in function ajax_delete_payment_button(): {"post": ["params"]} **/
function ajax_delete_payment_button() {
			if ( ! check_ajax_referer( 'customize-jetpack-simple-payments', 'customize-jetpack-simple-payments-nonce', false ) ) {
				wp_send_json_error( 'bad_nonce', 400, JSON_UNESCAPED_SLASHES );
			}

			if ( ! current_user_can( 'customize' ) ) {
				wp_send_json_error( 'customize_not_allowed', 403, JSON_UNESCAPED_SLASHES );
			}

			if ( empty( $_POST['params'] ) || ! is_array( $_POST['params'] ) ) {
				wp_send_json_error( 'missing_params', 400, JSON_UNESCAPED_SLASHES );
			}

			$params         = wp_unslash( $_POST['params'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Manually validated just below.
			$illegal_params = array_diff( array_keys( $params ), array( 'product_post_id' ) );
			if ( ! empty( $illegal_params ) ) {
				wp_send_json_error( 'illegal_params', 400, JSON_UNESCAPED_SLASHES );
			}

			$product_id   = (int) $params['product_post_id'];
			$product_post = get_post( $product_id );

			$return = array( 'status' => $product_post->post_status );

			wp_delete_post( $product_id, true );
			$status = get_post_status( $product_id );
			if ( false === $status ) {
				$return['status'] = 'deleted';
			}

			$this->record_event( 'deleted', 'delete', array( 'id' => $product_id ) );

			// @phan-suppress-next-line PhanTypeMismatchArgumentProbablyReal -- It takes null, but its phpdoc only says int.
			wp_send_json_success( $return, null, JSON_UNESCAPED_SLASHES );
		}


/** Function get_attachment_comments() called by wp_ajax hooks: {'get_attachment_comments', 'nopriv_get_attachment_comments'} **/
/** Parameters found in function get_attachment_comments(): {"request": ["id", "offset"]} **/
function get_attachment_comments() {
		if ( ! headers_sent() ) {
			header( 'Content-type: text/javascript' );
		}

		/**
		 * Allows for the checking of privileges of the blog user before comments
		 * are packaged as JSON and sent back from the get_attachment_comments
		 * AJAX endpoint
		 *
		 * @module carousel
		 *
		 * @since 1.6.0
		 */
		do_action( 'jp_carousel_check_blog_user_privileges' );

		// phpcs:disable WordPress.Security.NonceVerification.Recommended -- we do not need to verify the nonce for this public request for publicly accessible data (as checked below).
		$attachment_id = ( isset( $_REQUEST['id'] ) ) ? (int) $_REQUEST['id'] : 0;
		$offset        = ( isset( $_REQUEST['offset'] ) ) ? (int) $_REQUEST['offset'] : 0;
		// phpcs:enable

		if ( ! $attachment_id ) {
			wp_send_json_error(
				__( 'Missing attachment ID.', 'jetpack' ),
				403,
				JSON_UNESCAPED_SLASHES
			);
			return;
		}

		$attachment_post = get_post( $attachment_id );
		// If we have no info about that attachment, bail.
		if ( ! ( $attachment_post instanceof WP_Post ) ) {
			wp_send_json_error(
				__( 'Missing attachment info.', 'jetpack' ),
				403,
				JSON_UNESCAPED_SLASHES
			);
			return;
		}

		// This AJAX call should only be used to fetch comments of attachments.
		if ( 'attachment' !== $attachment_post->post_type ) {
			wp_send_json_error(
				__( 'You aren’t authorized to do that.', 'jetpack' ),
				403,
				JSON_UNESCAPED_SLASHES
			);
			return;
		}

		$parent_post = get_post_parent( $attachment_id );

		/*
		 * If we have no info about that parent post, no extra checks.
		 * The attachment doesn't have a parent post, so is public.
		 * If we have a parent post, let's ensure the user has access to it.
		 */
		if ( $parent_post instanceof WP_Post ) {
			/*
			 * Fetch info about user making the request.
			 * If we have no info, bail.
			 * Even logged out users should get a WP_User user with id 0.
			 */
			$current_user = wp_get_current_user();
			if ( ! ( $current_user instanceof WP_User ) ) {
				wp_send_json_error(
					__( 'Missing user info.', 'jetpack' ),
					403,
					JSON_UNESCAPED_SLASHES
				);
				return;
			}

			/*
			 * If a post is private / draft
			 * and the current user doesn't have access to it,
			 * bail.
			 */
			if (
				'publish' !== $parent_post->post_status
				&& ! current_user_can( 'read_post', $parent_post->ID )
			) {
				wp_send_json_error(
					__( 'You aren’t authorized to do that.', 'jetpack' ),
					403,
					JSON_UNESCAPED_SLASHES
				);
				return;
			}
		}

		if ( $offset < 1 ) {
			$offset = 0;
		}

		$comments = get_comments(
			array(
				'status'  => 'approve',
				'order'   => ( 'asc' === get_option( 'comment_order' ) ) ? 'ASC' : 'DESC',
				'number'  => 10,
				'offset'  => $offset,
				'post_id' => $attachment_id,
			)
		);

		$out = array();

		// Can't just send the results, they contain the commenter's email address.
		foreach ( $comments as $comment ) {
			$avatar = get_avatar( $comment->comment_author_email, 64 );
			if ( ! $avatar ) {
				$avatar = '';
			}
			$out[] = array(
				'id'              => $comment->comment_ID,
				'parent_id'       => $comment->comment_parent,
				'author_markup'   => get_comment_author_link( $comment->comment_ID ),
				'gravatar_markup' => $avatar,
				'date_gmt'        => $comment->comment_date_gmt,
				'content'         => wpautop( $comment->comment_content ),
			);
		}

		wp_send_json( $out, null, JSON_UNESCAPED_SLASHES );
	}


/** Function regenerate_post_by_email_address() called by wp_ajax hooks: {'jetpack_post_by_email_regenerate'} **/
/** No function found :-/ **/


/** Function create_new_form() called by wp_ajax hooks: {'create_new_form'} **/
/** Parameters found in function create_new_form(): {"post": ["newFormNonce", "pattern"]} **/
function create_new_form() {
		if ( ! isset( $_POST['newFormNonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['newFormNonce'] ) ), 'create_new_form' ) ) {
			wp_send_json_error(
				__( 'Invalid nonce', 'jetpack-forms' ),
				403,
				JSON_UNESCAPED_SLASHES
			);
		}

		if ( ! current_user_can( 'edit_pages' ) ) {
			wp_send_json_error(
				__( 'You do not have permission to create pages', 'jetpack-forms' ),
				403,
				JSON_UNESCAPED_SLASHES
			);
		}

		$pattern_name = isset( $_POST['pattern'] ) ? sanitize_text_field( wp_unslash( $_POST['pattern'] ) ) : null;

		if ( $pattern_name && WP_Block_Patterns_Registry::get_instance()->is_registered( $pattern_name ) ) {
			$pattern         = WP_Block_Patterns_Registry::get_instance()->get_registered( $pattern_name );
			$pattern_content = $pattern['content'] ?? '';
		}

		// If no pattern found or specified, use a default form block
		if ( empty( $pattern_content ) ) {
			$pattern_content = '<!-- wp:jetpack/contact-form -->
														<div class="wp-block-jetpack-contact-form"></div>
													<!-- /wp:jetpack/contact-form -->';
		}

		$post_id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_title'   => '',
				'post_content' => $pattern_content,
			)
		);

		if ( is_wp_error( $post_id ) ) {
			wp_send_json_error(
				$post_id->get_error_message(),
				500,
				JSON_UNESCAPED_SLASHES
			);
		} else {
			wp_send_json(
				array(
					'post_url' => admin_url( 'post.php?post=' . intval( $post_id ) . '&action=edit' ),
				),
				null, // @phan-suppress-current-line PhanTypeMismatchArgumentProbablyReal -- It takes null, but its phpdoc only says int.
				JSON_UNESCAPED_SLASHES
			);
		}
	}


/** Function delete_post_by_email_address() called by wp_ajax hooks: {'jetpack_post_by_email_disable'} **/
/** No function found :-/ **/


/** Function wp_ajax_jitm_dismiss() called by wp_ajax hooks: {'jitm_dismiss'} **/
/** Parameters found in function wp_ajax_jitm_dismiss(): {"request": ["id", "feature_class"]} **/
function wp_ajax_jitm_dismiss() {
		check_ajax_referer( 'jitm_dismiss' );
		$jitm = \Automattic\Jetpack\JITMS\JITM::get_instance();
		if ( isset( $_REQUEST['id'] ) && isset( $_REQUEST['feature_class'] ) ) {
			$jitm->dismiss( sanitize_text_field( wp_unslash( $_REQUEST['id'] ) ), sanitize_text_field( wp_unslash( $_REQUEST['feature_class'] ) ) );
		}
		wp_die();
	}


/** Function ajax_local_testing_suite() called by wp_ajax hooks: {'health-check-jetpack-connection-health'} **/
/** No params detected :-/ **/


/** Function download_feedback_as_csv() called by wp_ajax hooks: {'feedback_export'} **/
/** No params detected :-/ **/


/** Function ajax_update_widget_token_id() called by wp_ajax hooks: {'wpcom_instagram_widget_update_widget_token_id'} **/
/** Parameters found in function ajax_update_widget_token_id(): {"post": ["keyring_id", "instagram_widget_id"]} **/
function ajax_update_widget_token_id() {
		if ( ! check_ajax_referer( 'instagram-widget-save-token', 'savetoken', false ) ) {
			wp_send_json_error( array( 'message' => 'bad_nonce' ), 403, JSON_UNESCAPED_SLASHES );
		}

		if ( ! current_user_can( 'customize' ) ) {
			wp_send_json_error( array( 'message' => 'not_authorized' ), 403, JSON_UNESCAPED_SLASHES );
		}

		$token_id  = ! empty( $_POST['keyring_id'] ) ? (int) $_POST['keyring_id'] : null;
		$widget_id = ! empty( $_POST['instagram_widget_id'] ) ? (int) $_POST['instagram_widget_id'] : null;

		// For Simple sites check if the token is valid.
		// (For Atomic sites, this check is done via the api: wpcom/v2/instagram/<token_id>).
		if ( defined( 'IS_WPCOM' ) && IS_WPCOM ) {
			$token = Keyring::init()->get_token_store()->get_token(
				array(
					'type' => 'access',
					'id'   => $token_id,
				)
			);
			if ( get_current_user_id() !== (int) $token->meta['user_id'] ) {
				return wp_send_json_error( array( 'message' => 'not_authorized' ), 403, JSON_UNESCAPED_SLASHES );
			}
		}

		$this->update_widget_token_id( $token_id, $widget_id );
		$this->update_widget_token_legacy_status( false );

		return wp_send_json_success( null, 200, JSON_UNESCAPED_SLASHES );
	}


/** Function ajax_request() called by wp_ajax hooks: {'grunion-contact-form', 'nopriv_grunion-contact-form'} **/
/** Parameters found in function ajax_request(): {"server": ["HTTP_ACCEPT"]} **/
function ajax_request() {
		$submission_result = self::process_form_submission();
		$accepts_json      = isset( $_SERVER['HTTP_ACCEPT'] ) && false !== strpos( strtolower( sanitize_text_field( wp_unslash( $_SERVER['HTTP_ACCEPT'] ) ) ), 'application/json' );
		$is_system_error   = Form_Submission_Error::is_system_error( $submission_result );

		if ( ! $submission_result || $is_system_error ) {
			$error_code    = $is_system_error ? $submission_result->get_error_code() : 'unknown';
			$error_details = $is_system_error ? $submission_result->get_error_message() : null;

			/**
			 * Action when we want to log a jetpack_forms event.
			 *
			 * @since 6.3.0
			 *
			 * @param string $log_message The log message.
			 * @param string $error_code The error code (optional).
			 * @param string $error_details The error details (optional).
			 */
			do_action( 'jetpack_forms_log', 'submission_failed', $error_code, $error_details );

			// Use a specific error message for invalid JWT tokens
			$error_message = ( 'invalid_jwt' === $error_code )
				? __( 'An error occurred. Please reload the page and try again — data entered may be lost.', 'jetpack-forms' )
				: __( 'An error occurred. Please try again later.', 'jetpack-forms' );

			$accepts_json && wp_send_json_error(
				array(
					'error' => $error_message,
					'code'  => $error_code,
				),
				500,
				JSON_UNESCAPED_SLASHES
			);

			// Non-JSON request, output the error message directly.
			header( 'HTTP/1.1 500 Server Error', true, 500 );
			echo '<div class="form-error"><ul class="form-errors"><li class="form-error-message">';
			echo esc_html( $error_message );
			echo '</li></ul></div>';

			die();
		} elseif ( is_wp_error( $submission_result ) ) {
			do_action( 'jetpack_forms_log', $submission_result->get_error_message() );

			$accepts_json && wp_send_json_error(
				array(
					'error' => $submission_result->get_error_message(),
				),
				400,
				JSON_UNESCAPED_SLASHES
			);

			// Non-JSON request, output the error message directly.
			header( 'HTTP/1.1 400 Bad Request', true, 403 );
			echo '<div class="form-error"><ul class="form-errors"><li class="form-error-message">';
			echo esc_html( $submission_result->get_error_message() );
			echo '</li></ul></div>';

			die();
		}

		// Success case.
		echo '<h4>' . esc_html__( 'Your message has been sent', 'jetpack-forms' ) . '</h4>' . wp_kses(
			$submission_result,
			array(
				'br'         => array(),
				'blockquote' => array( 'class' => array() ),
				'p'          => array(),
			)
		);
		die();
	}


/** Function post_attachment_comment() called by wp_ajax hooks: {'post_attachment_comment', 'nopriv_post_attachment_comment'} **/
/** Parameters found in function post_attachment_comment(): {"post": ["nonce", "blog_id", "id", "comment", "author", "email", "url"]} **/
function post_attachment_comment() {
		if ( ! headers_sent() ) {
			header( 'Content-type: text/javascript' );
		}

		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'carousel_nonce' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- WP Core doesn't unslash or sanitize nonces either
			die( wp_json_encode( array( 'error' => __( 'Nonce verification failed.', 'jetpack' ) ), JSON_UNESCAPED_SLASHES ) );
		}

		$_blog_id = isset( $_POST['blog_id'] ) ? (int) $_POST['blog_id'] : 0;
		$_post_id = isset( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$comment  = isset( $_POST['comment'] ) ? filter_var( wp_unslash( $_POST['comment'] ) ) : null;

		if ( empty( $_blog_id ) ) {
			die( wp_json_encode( array( 'error' => __( 'Missing target blog ID.', 'jetpack' ) ), JSON_UNESCAPED_SLASHES ) );
		}

		if ( empty( $_post_id ) ) {
			die( wp_json_encode( array( 'error' => __( 'Missing target post ID.', 'jetpack' ) ), JSON_UNESCAPED_SLASHES ) );
		}

		if ( empty( $comment ) ) {
			die( wp_json_encode( array( 'error' => __( 'No comment text was submitted.', 'jetpack' ) ), JSON_UNESCAPED_SLASHES ) );
		}

		// Used in context like NewDash.
		$switched = false;
		if ( is_multisite() && get_current_blog_id() !== $_blog_id ) {
			switch_to_blog( $_blog_id );
			$switched = true;
		}

		/** This action is documented in modules/carousel/jetpack-carousel.php */
		do_action( 'jp_carousel_check_blog_user_privileges' );

		if ( ! comments_open( $_post_id ) ) {
			if ( $switched ) {
				restore_current_blog();
			}
			die( wp_json_encode( array( 'error' => __( 'Comments on this post are closed.', 'jetpack' ) ), JSON_UNESCAPED_SLASHES ) );
		}

		if ( is_user_logged_in() ) {
			$user         = wp_get_current_user();
			$user_id      = $user->ID;
			$display_name = $user->display_name;
			$email        = $user->user_email;
			$url          = $user->user_url;

			if ( empty( $user_id ) ) {
				if ( $switched ) {
					restore_current_blog();
				}
				die( wp_json_encode( array( 'error' => __( 'Sorry, but we could not authenticate your request.', 'jetpack' ) ), JSON_UNESCAPED_SLASHES ) );
			}
		} else {
			$user_id      = 0;
			$display_name = isset( $_POST['author'] ) ? sanitize_text_field( wp_unslash( $_POST['author'] ) ) : null;
			$email        = null;
			if ( isset( $_POST['email'] ) && is_string( $_POST['email'] ) ) {
				$email = wp_unslash( $_POST['email'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Checked or sanitized below.
			}
			$url = isset( $_POST['url'] ) && is_string( $_POST['url'] ) ? esc_url_raw( wp_unslash( $_POST['url'] ) ) : null;

			if ( get_option( 'require_name_email' ) ) {
				if ( empty( $display_name ) ) {
					if ( $switched ) {
						restore_current_blog();
					}
					die( wp_json_encode( array( 'error' => __( 'Please provide your name.', 'jetpack' ) ), JSON_UNESCAPED_SLASHES ) );
				}

				if ( empty( $email ) ) {
					if ( $switched ) {
						restore_current_blog();
					}
					die( wp_json_encode( array( 'error' => __( 'Please provide an email address.', 'jetpack' ) ), JSON_UNESCAPED_SLASHES ) );
				}

				if ( ! is_email( $email ) ) {
					if ( $switched ) {
						restore_current_blog();
					}
					die( wp_json_encode( array( 'error' => __( 'Please provide a valid email address.', 'jetpack' ) ), JSON_UNESCAPED_SLASHES ) );
				}
			} else {
				$email = $email !== null ? sanitize_email( $email ) : null;
			}
		}

		$comment_data = array(
			'comment_content'      => $comment,
			'comment_post_ID'      => $_post_id,
			'comment_author'       => $display_name,
			'comment_author_email' => $email,
			'comment_author_url'   => $url,
			'comment_approved'     => 0,
			'comment_type'         => 'comment',
		);

		if ( ! empty( $user_id ) ) {
			$comment_data['user_id'] = $user_id;
		}

		// Note: wp_new_comment() sanitizes and validates the values (too).
		$comment_id = wp_new_comment( $comment_data );

		/**
		 * Fires before adding a new comment to the database via the get_attachment_comments ajax endpoint.
		 *
		 * @module carousel
		 *
		 * @since 1.6.0
		 */
		do_action( 'jp_carousel_post_attachment_comment' );
		$comment_status = wp_get_comment_status( $comment_id );

		if ( $switched ) {
			restore_current_blog();
		}

		die(
			wp_json_encode(
				array(
					'comment_id'     => $comment_id,
					'comment_status' => $comment_status,
				),
				JSON_UNESCAPED_SLASHES
			)
		);
	}


/** Function export_to_gdrive() called by wp_ajax hooks: {'grunion_export_to_gdrive'} **/
/** No params detected :-/ **/


/** Function remote_request_handlers() called by wp_ajax hooks: {'nopriv_{$action}'} **/
/** No params detected :-/ **/


/** Function \accept_tos() called by wp_ajax hooks: {'jetpack_accept_tos'} **/
/** No function found :-/ **/


/** Function ajax_delete_connection() called by wp_ajax hooks: {'jetpack_delete_external_connection'} **/
/** Parameters found in function ajax_delete_connection(): {"request": ["service"]} **/
function ajax_delete_connection() {
		if ( ! isset( $_REQUEST['service'] ) ) {
			// @phan-suppress-next-line PhanTypeMismatchArgumentProbablyReal -- It takes null, but its phpdoc only says int.
			wp_send_json( array( 'deleted' => 'false' ), null, JSON_UNESCAPED_SLASHES );
		}

		$service = sanitize_text_field( wp_unslash( $_REQUEST['service'] ) );
		check_ajax_referer( 'jetpack_delete_external_connection_' . $service );

		$is_deleted = self::delete_connection( $service );
		// @phan-suppress-next-line PhanTypeMismatchArgumentProbablyReal -- It takes null, but its phpdoc only says int.
		wp_send_json( array( 'deleted' => $is_deleted ), null, JSON_UNESCAPED_SLASHES );
	}


/** Function handle_set_consent_request() called by wp_ajax hooks: {'gdpr_set_consent', 'nopriv_gdpr_set_consent'} **/
/** Parameters found in function handle_set_consent_request(): {"post": ["consent"]} **/
function handle_set_consent_request() {

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		if ( ! isset( $_POST['consent'] ) ) {
			// @phan-suppress-next-line PhanTypeMismatchArgumentProbablyReal -- It takes null, but its phpdoc only says int.
			wp_send_json_error( null, null, JSON_UNESCAPED_SLASHES );
		}

		// TODO: Is there better sanitizing we can do here?
		$consent = trim( wp_unslash( $_POST['consent'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		setcookie( self::COOKIE_NAME, $consent, time() + YEAR_IN_SECONDS, '/', self::get_cookie_domain(), is_ssl(), false ); // phpcs:ignore Jetpack.Functions.SetCookie -- Client side CMP needs to be able to read this value.

		// @phan-suppress-next-line PhanTypeMismatchArgumentProbablyReal -- It takes null, but its phpdoc only says int.
		wp_send_json_success( true, null, JSON_UNESCAPED_SLASHES );

		// phpcs:enable WordPress.Security.NonceVerification.Missing
	}


/** Function ajax_new_service() called by wp_ajax hooks: {'sharing_new_service'} **/
/** Parameters found in function ajax_new_service(): {"post": ["_wpnonce", "sharing_name", "sharing_url", "sharing_icon"]} **/
function ajax_new_service() {
		if (
			isset( $_POST['_wpnonce'] )
			&& isset( $_POST['sharing_name'] )
			&& isset( $_POST['sharing_url'] )
			&& isset( $_POST['sharing_icon'] )
			&& wp_verify_nonce( sanitize_key( wp_unslash( $_POST['_wpnonce'] ) ), 'sharing-new_service' )
		) {
			$sharer  = new Sharing_Service();
			$service = $sharer->new_service(
				sanitize_text_field( wp_unslash( $_POST['sharing_name'] ) ),
				esc_url_raw( wp_unslash( $_POST['sharing_url'] ) ),
				esc_url_raw( wp_unslash( $_POST['sharing_icon'] ) )
			);

			if ( $service ) {
				$this->output_service( $service->get_id(), $service );
				echo '<!--->';
				$service->button_style = 'icon-text';
				$this->output_preview( $service );

				die( 0 );
			}
		}

		// Fail
		die( '1' );
	}


/** Function theme_edit_ajax() called by wp_ajax hooks: {'edit-theme-plugin-file'} **/
/** Parameters found in function theme_edit_ajax(): {"post": ["theme", "file", "newcontent", "nonce"]} **/
function theme_edit_ajax() {
		// This validation is based on wp_edit_theme_plugin_file().
		if ( empty( $_POST['theme'] ) ) {
			return;
		}

		if ( empty( $_POST['file'] ) ) {
			return;
		}
		$file = wp_unslash( $_POST['file'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Validated manually just after.
		if ( 0 !== validate_file( $file ) ) {
			return;
		}

		if ( ! isset( $_POST['newcontent'] ) ) {
			return;
		}

		if ( ! isset( $_POST['nonce'] ) ) {
			return;
		}

		$stylesheet = wp_unslash( $_POST['theme'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Validated manually just after.
		if ( 0 !== validate_file( $stylesheet ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_themes' ) ) {
			return;
		}

		$theme = wp_get_theme( $stylesheet );
		if ( ! $theme->exists() ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['nonce'], 'edit-theme_' . $stylesheet . '_' . $file ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- WP core doesn't pre-sanitize nonces either.
			return;
		}

		if ( $theme->errors() && 'theme_no_stylesheet' === $theme->errors()->get_error_code() ) {
			return;
		}

		$editable_extensions = wp_get_theme_file_editable_extensions( $theme );

		$allowed_files = array();
		foreach ( $editable_extensions as $type ) {
			switch ( $type ) {
				case 'php':
					$allowed_files = array_merge( $allowed_files, $theme->get_files( 'php', -1 ) );
					break;
				case 'css':
					$style_files                = $theme->get_files( 'css', -1 );
					$allowed_files['style.css'] = $style_files['style.css'];
					$allowed_files              = array_merge( $allowed_files, $style_files );
					break;
				default:
					$allowed_files = array_merge( $allowed_files, $theme->get_files( $type, -1 ) );
					break;
			}
		}

		$real_file = $theme->get_stylesheet_directory() . '/' . $file;
		if ( 0 !== validate_file( $real_file, $allowed_files ) ) {
			return;
		}

		// Ensure file is real.
		if ( ! is_file( $real_file ) ) {
			return;
		}

		// Ensure file extension is allowed.
		$extension = null;
		if ( preg_match( '/\.([^.]+)$/', $real_file, $matches ) ) {
			$extension = strtolower( $matches[1] );
			if ( ! in_array( $extension, $editable_extensions, true ) ) {
				return;
			}
		}

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_is_writable
		if ( ! is_writable( $real_file ) ) {
			return;
		}

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
		$file_pointer = fopen( $real_file, 'w+' );
		if ( false === $file_pointer ) {
			return;
		}
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
		fclose( $file_pointer );

		$theme_data = array(
			'name'    => $theme->get( 'Name' ),
			'version' => $theme->get( 'Version' ),
			'uri'     => $theme->get( 'ThemeURI' ),
		);

		/**
		 * This action is documented already in this file.
		 */
		do_action( 'jetpack_edited_theme', $stylesheet, $theme_data );
	}


/** Function wp_ajax_update_transcoding_status() called by wp_ajax hooks: {'videopress-update-transcoding-status'} **/
/** Parameters found in function wp_ajax_update_transcoding_status(): {"post": ["post_id"]} **/
function wp_ajax_update_transcoding_status() {
		if ( ! isset( $_POST['post_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Informational AJAX response.
			// @phan-suppress-next-line PhanTypeMismatchArgumentProbablyReal -- It takes null, but its phpdoc only says int.
			wp_send_json_error( array( 'message' => __( 'A valid post_id is required.', 'jetpack-videopress-pkg' ) ), null, JSON_UNESCAPED_SLASHES );
			return;
		}

		$post_id = (int) $_POST['post_id']; // phpcs:ignore WordPress.Security.NonceVerification.Missing

		if ( ! videopress_update_meta_data( $post_id ) ) {
			// @phan-suppress-next-line PhanTypeMismatchArgumentProbablyReal -- It takes null, but its phpdoc only says int.
			wp_send_json_error( array( 'message' => __( 'That post does not have a VideoPress video associated to it.', 'jetpack-videopress-pkg' ) ), null, JSON_UNESCAPED_SLASHES );
			return;
		}

		wp_send_json_success(
			array(
				'message' => __( 'Status updated', 'jetpack-videopress-pkg' ),
				'status'  => videopress_get_transcoding_status( $post_id ),
			),
			null, // @phan-suppress-current-line PhanTypeMismatchArgumentProbablyReal -- It takes null, but its phpdoc only says int.
			JSON_UNESCAPED_SLASHES
		);
	}


/** Function ajax_dismiss_handler() called by wp_ajax hooks: {'jetpack-protect-dismiss-multisite-banner'} **/
/** No params detected :-/ **/


/** Function ajax_sidebar_state() called by wp_ajax hooks: {'sidebar_state'} **/
/** Parameters found in function ajax_sidebar_state(): {"request": ["expanded"]} **/
function ajax_sidebar_state() {
		$expanded = isset( $_REQUEST['expanded'] ) ? filter_var( wp_unslash( $_REQUEST['expanded'] ), FILTER_VALIDATE_BOOLEAN ) : false; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		Client::wpcom_json_api_request_as_user(
			'/me/preferences',
			'2',
			array(
				'method' => 'POST',
			),
			array( 'calypso_preferences' => (object) array( 'sidebarCollapsed' => ! $expanded ) ),
			'wpcom'
		);

		wp_die();
	}


/** Function ajax_save_options() called by wp_ajax hooks: {'sharing_save_options'} **/
/** Parameters found in function ajax_save_options(): {"post": ["_wpnonce", "service"]} **/
function ajax_save_options() {
		if (
			isset( $_POST['_wpnonce'] )
			&& isset( $_POST['service'] )
			&& wp_verify_nonce(
				sanitize_key( wp_unslash( $_POST['_wpnonce'] ) ),
				'sharing-options_' . sanitize_text_field( wp_unslash( $_POST['service'] ) )
			)
		) {
			$sharer  = new Sharing_Service();
			$service = $sharer->get_service( sanitize_text_field( wp_unslash( $_POST['service'] ) ) );

			if ( $service && $service instanceof Sharing_Advanced_Source ) {
				$service->update_options( $_POST );

				$sharer->set_service( sanitize_text_field( wp_unslash( $_POST['service'] ) ), $service );
			}

			$this->output_service( $service->get_id(), $service, true );
			echo '<!--->';
			$service->button_style = 'icon-text';
			$this->output_preview( $service );
			die( 0 );
		}
	}


/** Function ajax_get_payment_buttons() called by wp_ajax hooks: {'customize-jetpack-simple-payments-buttons-get'} **/
/** No params detected :-/ **/


