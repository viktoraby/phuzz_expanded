<?php
/***
*
*Found actions: 24
*Found functions:24
*Extracted functions:24
*Total parameter names extracted: 21
*Overview: {'wpcode_set_preview_css': {'wpcode_set_preview_css'}, 'delete_auth': {'wpcode_library_delete_auth'}, 'store_auth_key': {'wpcode_library_store_auth'}, 'wpcode_ajax_remove_pack': {'wpcode_remove_pack'}, 'wpcode_save_preview_css': {'wpcode_save_preview_css'}, 'wpcode_clear_preview_css': {'wpcode_clear_preview_css'}, 'generate_url': {'wpcode_connect_url'}, 'wpcode_generate_snippet': {'wpcode_generate_snippet'}, 'wpcode_ajax_install_pack': {'wpcode_install_pack'}, 'import_snippet': {'wpcode_import_snippet_{$this->slug}'}, 'wpcode_verify_ssl': {'wpcode_verify_ssl'}, 'wpcode_save_editor_height': {'wpcode_save_editor_height'}, 'wpcode_sync_snippet': {'wpcode_sync_snippet'}, 'dismiss': {'wpcode_notification_dismiss'}, 'wpcode_ajax_add_pack_option': {'wpcode_add_pack_option'}, 'wpcode_search_terms': {'wpcode_search_terms'}, 'dismiss_ajax': {'wpcode_notice_dismiss'}, 'wpcode_ajax_toggle_pack_option': {'wpcode_toggle_pack_option'}, 'wpcode_update_snippet_status': {'wpcode_update_snippet_status'}, 'install_plugin': {'wpcode_install_plugin'}, 'process': {'nopriv_wpcode_connect_process'}, 'wpcode_get_shortcode_locations': {'wpcode_get_shortcode_locations'}, 'wpcode_save_generated_snippet': {'wpcode_save_generated_snippet'}, 'wpcode_filter_snippets_by_type': {'wpcode_filter_snippets_by_type'}}
*
***/

/** Function wpcode_set_preview_css() called by wp_ajax hooks: {'wpcode_set_preview_css'} **/
/** Parameters found in function wpcode_set_preview_css(): {"post": ["snippet_id", "css", "code_type", "source_code"]} **/
function wpcode_set_preview_css() {
	// Check nonce.
	check_ajax_referer( 'wpcode_admin', 'nonce' );

	// Check permissions.
	if ( ! current_user_can( 'wpcode_edit_snippets' ) ) { //phpcs:ignore
		wp_send_json_error( array( 'message' => __( 'You do not have permission to edit snippets.', 'insert-headers-and-footers' ) ) );
	}

	$snippet_id  = isset( $_POST['snippet_id'] ) ? absint( $_POST['snippet_id'] ) : 0;
	$css         = isset( $_POST['css'] ) ? wp_unslash( $_POST['css'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$code_type   = isset( $_POST['code_type'] ) ? sanitize_text_field( wp_unslash( $_POST['code_type'] ) ) : 'css';
	$source_code = isset( $_POST['source_code'] ) ? wp_unslash( $_POST['source_code'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

	if ( ! $snippet_id ) {
		wp_send_json_error( array( 'message' => __( 'Invalid snippet ID.', 'insert-headers-and-footers' ) ) );
	}

	$user_id = get_current_user_id();

	// For SCSS, store both the compiled CSS (for preview) and source code (for saving).
	if ( 'scss' === $code_type ) {
		// Store compiled CSS for preview frame.
		$compiled_transient_key = "wpcode_preview_css_{$user_id}_{$snippet_id}";
		set_transient( $compiled_transient_key, $css, HOUR_IN_SECONDS );

		// Store source SCSS for saving later.
		$source_transient_key = "wpcode_preview_scss_source_{$user_id}_{$snippet_id}";
		set_transient( $source_transient_key, $source_code, HOUR_IN_SECONDS );
	} else {
		// Store the CSS in the same transient used by the preview frame.
		$transient_key = "wpcode_preview_css_{$user_id}_{$snippet_id}";
		set_transient( $transient_key, $css, HOUR_IN_SECONDS );
	}

	wp_send_json_success( array( 'message' => __( 'Preview CSS stored.', 'insert-headers-and-footers' ) ) );
}


/** Function delete_auth() called by wp_ajax hooks: {'wpcode_library_delete_auth'} **/
/** No params detected :-/ **/


/** Function store_auth_key() called by wp_ajax hooks: {'wpcode_library_store_auth'} **/
/** Parameters found in function store_auth_key(): {"post": ["key", "username", "origin", "deploy_snippet_id", "webhook_secret", "client_id"]} **/
function store_auth_key() {
		check_ajax_referer( 'wpcode_admin' );

		if ( ! current_user_can( 'wpcode_activate_snippets' ) ) {
			wp_send_json_error( esc_html__( 'You do not have permissions to connect WPCode to the library.', 'insert-headers-and-footers' ) );
		}

		$key               = ! empty( $_POST['key'] ) ? sanitize_key( $_POST['key'] ) : false;
		$username          = ! empty( $_POST['username'] ) ? sanitize_user( wp_unslash( $_POST['username'] ) ) : false;
		$origin            = ! empty( $_POST['origin'] ) ? esc_url_raw( wp_unslash( $_POST['origin'] ) ) : false;
		$deploy_snippet_id = ! empty( $_POST['deploy_snippet_id'] ) ? sanitize_key( $_POST['deploy_snippet_id'] ) : false;
		$webhook_secret    = ! empty( $_POST['webhook_secret'] ) ? sanitize_key( $_POST['webhook_secret'] ) : '';
		$client_id         = ! empty( $_POST['client_id'] ) ? sanitize_key( $_POST['client_id'] ) : false;

		if ( ! $key || $this->library_url !== $origin ) {
			wp_send_json_error();
		}

		$this->save_auth_data( $key, $username, $webhook_secret, $client_id );

		if ( ! empty( $deploy_snippet_id ) ) {
			// If we have a snippet id from the deployment process, set that as a transient to show a notice, so they can pick up where they started.
			set_transient( 'wpcode_deploy_snippet_id', $deploy_snippet_id, HOUR_IN_SECONDS );
		}

		// Reset the auth data.
		unset( $this->auth_data );
		unset( $this->auth_key );
		unset( $this->has_auth );

		do_action( 'wpcode_library_api_auth_connected' );

		wp_send_json_success(
			array(
				'title' => __( 'Authentication successfully completed', 'insert-headers-and-footers' ),
				'text'  => __( 'Reloading page, please wait.', 'insert-headers-and-footers' ),
			)
		);
	}


/** Function wpcode_ajax_remove_pack() called by wp_ajax hooks: {'wpcode_remove_pack'} **/
/** Parameters found in function wpcode_ajax_remove_pack(): {"post": ["slug"]} **/
function wpcode_ajax_remove_pack() {
	check_ajax_referer( 'wpcode_packs', 'nonce' );

	if ( ! current_user_can( 'wpcode_edit_snippets' ) ) {
		wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'insert-headers-and-footers' ) ), 403 );
	}

	$slug = isset( $_POST['slug'] ) ? sanitize_key( wp_unslash( $_POST['slug'] ) ) : '';
	if ( '' === $slug ) {
		wp_send_json_error( array( 'message' => __( 'Pack slug is required.', 'insert-headers-and-footers' ) ), 400 );
	}

	$deleted = WPCode_Packs::get_instance()->remove_pack( $slug );

	wp_send_json_success(
		array(
			'deleted_count' => $deleted,
			'redirect_url'  => add_query_arg(
				array(
					'page' => 'wpcode-snippet-manager',
					'tab'  => 'packs',
				),
				admin_url( 'admin.php' )
			),
		)
	);
}


/** Function wpcode_save_preview_css() called by wp_ajax hooks: {'wpcode_save_preview_css'} **/
/** Parameters found in function wpcode_save_preview_css(): {"post": ["snippet_id", "css", "code_type", "source_code"]} **/
function wpcode_save_preview_css() {
	// Check nonce.
	check_ajax_referer( 'wpcode_admin', 'nonce' );

	$snippet_id = isset( $_POST['snippet_id'] ) ? absint( $_POST['snippet_id'] ) : 0;

	// Check permissions against the specific snippet's code type.
	if ( ! $snippet_id || ! current_user_can( 'edit_post', $snippet_id ) ) {
		wp_send_json_error( array( 'message' => __( 'You do not have permission to edit this snippet.', 'insert-headers-and-footers' ) ) );
	}

	$css         = isset( $_POST['css'] ) ? wp_unslash( $_POST['css'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$code_type   = isset( $_POST['code_type'] ) ? sanitize_text_field( wp_unslash( $_POST['code_type'] ) ) : '';
	$source_code = isset( $_POST['source_code'] ) ? wp_unslash( $_POST['source_code'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

	// Strip script/event-handler HTML from the stored code for authors without unfiltered_html.
	if ( ! current_user_can( 'unfiltered_html' ) ) {
		$css         = wp_kses_post( $css );
		$source_code = wp_kses_post( $source_code );
	}

	// Get the snippet.
	$snippet = wpcode_get_snippet( $snippet_id );

	if ( ! $snippet || ! $snippet->get_id() ) {
		wp_send_json_error( array( 'message' => __( 'Snippet not found.', 'insert-headers-and-footers' ) ) );
	}

	// Preserve existing "Compress Output" option explicitly without mutating the property to a false value.
	$compress_enabled = get_post_meta( $snippet_id, '_wpcode_compress_output', true );

	// For SCSS snippets, save the source code and compiled CSS.
	if ( 'scss' === $code_type || 'scss' === $snippet->get_code_type() ) {
		// Use source code if provided, otherwise use the compiled CSS as source (shouldn't happen).
		$snippet->code = ! empty( $source_code ) ? $source_code : $css;
		// Store the compiled CSS.
		$snippet->compiled_code = $css;
	} else {
		// For CSS snippets, just save the CSS directly.
		$snippet->code = $css;
	}

	// Re-apply compress_output flag only if it was previously enabled.
	if ( $compress_enabled ) {
		$snippet->compress_output = true;
	}
	$result = $snippet->save();

	if ( ! $result ) {
		wp_send_json_error( array( 'message' => __( 'Failed to save snippet.', 'insert-headers-and-footers' ) ) );
	}

	// Clear the preview transients.
	$user_id       = get_current_user_id();
	$transient_key = "wpcode_preview_css_{$user_id}_{$snippet_id}";
	delete_transient( $transient_key );

	// Also clear SCSS source transient if it exists.
	$source_transient_key = "wpcode_preview_scss_source_{$user_id}_{$snippet_id}";
	delete_transient( $source_transient_key );

	// Return success with redirect URL.
	wp_send_json_success(
		array(
			'message' => __( 'Snippet saved successfully.', 'insert-headers-and-footers' ),
			'url'     => add_query_arg(
				array(
					'page'       => 'wpcode-snippet-manager',
					'snippet_id' => $snippet_id,
					'message'    => 1,
				),
				admin_url( 'admin.php' )
			),
		)
	);
}


/** Function wpcode_clear_preview_css() called by wp_ajax hooks: {'wpcode_clear_preview_css'} **/
/** Parameters found in function wpcode_clear_preview_css(): {"post": ["snippet_id"]} **/
function wpcode_clear_preview_css() {
	// Check nonce.
	check_ajax_referer( 'wpcode_admin', 'nonce' );

	// Check permissions.
	if ( ! current_user_can( 'wpcode_edit_snippets' ) ) { //phpcs:ignore
		wp_send_json_error( array( 'message' => __( 'You do not have permission.', 'insert-headers-and-footers' ) ) );
	}

	$snippet_id = isset( $_POST['snippet_id'] ) ? absint( $_POST['snippet_id'] ) : 0;

	if ( ! $snippet_id ) {
		wp_send_json_error( array( 'message' => __( 'Invalid snippet ID.', 'insert-headers-and-footers' ) ) );
	}

	// Clear the preview transients.
	$user_id       = get_current_user_id();
	$transient_key = "wpcode_preview_css_{$user_id}_{$snippet_id}";
	delete_transient( $transient_key );

	// Also clear SCSS source transient if it exists.
	$source_transient_key = "wpcode_preview_scss_source_{$user_id}_{$snippet_id}";
	delete_transient( $source_transient_key );

	wp_send_json_success(
		array(
			'message' => __( 'Preview cleared.', 'insert-headers-and-footers' ),
		)
	);
}


/** Function generate_url() called by wp_ajax hooks: {'wpcode_connect_url'} **/
/** Parameters found in function generate_url(): {"post": ["key"]} **/
function generate_url() {

		// Run a security check.
		check_ajax_referer( 'wpcode_admin' );

		// Check for permissions.
		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'You are not allowed to install plugins.', 'insert-headers-and-footers' ) ) );
		}

		$key = ! empty( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : '';

		if ( empty( $key ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Please enter your license key to connect.', 'insert-headers-and-footers' ) ) );
		}

		if ( class_exists( 'WPCode_Premium' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Only the Lite version can be upgraded.', 'insert-headers-and-footers' ) ) );
		}

		// Verify pro version is not installed.
		$active = activate_plugin( 'wpcode-premium/wpcode.php', false, false, true );

		if ( ! is_wp_error( $active ) ) {

			update_option( 'wpcode_install', 1 ); // Run install routines.
			// Deactivate Lite.
			$plugin = plugin_basename( WPCODE_FILE );

			deactivate_plugins( $plugin );

			do_action( 'wpcode_plugin_deactivated', $plugin );

			wp_send_json_success(
				array(
					'message' => esc_html__( 'WPCode Pro is installed but not activated.', 'insert-headers-and-footers' ),
					'reload'  => true,
				)
			);
		}

		// Generate URL.
		$oth        = hash( 'sha512', wp_rand() );
		$hashed_oth = hash_hmac( 'sha512', $oth, wp_salt() );

		update_option( 'wpcode_connect_token', $oth );
		update_option( 'wpcode_connect', $key );

		$version  = WPCODE_VERSION;
		$endpoint = admin_url( 'admin-ajax.php' );
		$redirect = admin_url( 'admin.php?page=wpcode-settings' );
		$url      = add_query_arg(
			array(
				'key'      => $key,
				'oth'      => $hashed_oth,
				'endpoint' => $endpoint,
				'version'  => $version,
				'siteurl'  => admin_url(),
				'homeurl'  => home_url(),
				'redirect' => rawurldecode( base64_encode( $redirect ) ), // phpcs:ignore
				'v'        => 2,
				'php'      => phpversion(),
				'wp'       => get_bloginfo( 'version' ),
			),
			'https://upgrade.wpcode.com/'
		);

		wp_send_json_success(
			array(
				'url'      => $url,
				'back_url' => add_query_arg(
					array(
						'action' => 'wpcode_connect',
						'oth'    => $oth,
					),
					$endpoint
				),
			)
		);
	}


/** Function wpcode_generate_snippet() called by wp_ajax hooks: {'wpcode_generate_snippet'} **/
/** Parameters found in function wpcode_generate_snippet(): {"post": ["type"]} **/
function wpcode_generate_snippet() {

	check_ajax_referer( 'wpcode_generate', 'nonce' );

	if ( ! current_user_can( 'wpcode_edit_snippets' ) ) { //phpcs:ignore
		wp_send_json_error();
	}

	$generator_type = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';

	$generator = wpcode()->generator()->get_type( $generator_type );

	if ( ! $generator ) {
		wp_send_json_error();
	}

	$snippet_code = $generator->process_form_data( $_POST );

	wp_send_json( $snippet_code );
}


/** Function wpcode_ajax_install_pack() called by wp_ajax hooks: {'wpcode_install_pack'} **/
/** Parameters found in function wpcode_ajax_install_pack(): {"post": ["slug"]} **/
function wpcode_ajax_install_pack() {
	check_ajax_referer( 'wpcode_packs', 'nonce' );

	if ( ! current_user_can( 'wpcode_edit_snippets' ) ) {
		wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'insert-headers-and-footers' ) ), 403 );
	}

	if ( ! wpcode()->library_auth->has_auth() ) {
		wp_send_json_error(
			array(
				'message' => __( 'Please connect to the WPCode Library to install packs.', 'insert-headers-and-footers' ),
				'code'    => 'not_connected',
			),
			403
		);
	}

	$slug = isset( $_POST['slug'] ) ? sanitize_key( wp_unslash( $_POST['slug'] ) ) : '';
	if ( '' === $slug ) {
		wp_send_json_error( array( 'message' => __( 'Pack slug is required.', 'insert-headers-and-footers' ) ), 400 );
	}

	$result = WPCode_Packs::get_instance()->install_pack( $slug );

	if ( ! $result['success'] ) {
		wp_send_json_error(
			array(
				'message' => __( 'Pack install failed.', 'insert-headers-and-footers' ),
			),
			500
		);
	}

	wp_send_json_success(
		array(
			'result'       => $result,
			'redirect_url' => add_query_arg(
				array(
					'page'   => 'wpcode-snippet-manager',
					'tab'    => 'packs',
					'pack' => $slug,
				),
				admin_url( 'admin.php' )
			),
		)
	);
}


/** Function import_snippet() called by wp_ajax hooks: {'wpcode_import_snippet_{$this->slug}'} **/
/** Parameters found in function import_snippet(): {"post": ["snippet_id"]} **/
function import_snippet() {
		// Run a security check.
		check_ajax_referer( 'wpcode_admin' );

		if ( ! current_user_can( 'wpcode_edit_snippets' ) ) {
			wp_send_json_error();
		}

		if ( ! function_exists( '\Code_Snippets\get_snippets' ) ) {
			wp_send_json_error();
		}

		$id = isset( $_POST['snippet_id'] ) ? absint( $_POST['snippet_id'] ) : 0;

		// Grab a snippet from Code Snippets.
		$snippets = \Code_Snippets\get_snippets( array( $id ) );

		if ( empty( $snippets ) || empty( $snippets[0] ) ) {
			wp_send_json_error(
				array(
					'error' => true,
					'name'  => esc_html__( 'Unknown Snippet', 'insert-headers-and-footers' ),
					'msg'   => esc_html__( 'The snippet you are trying to import does not exist.', 'insert-headers-and-footers' ),
				)
			);
		}

		// If we got so far we have a snippet to process.
		$snippet = $snippets[0];

		// Create a new snippet from the snippet data array.
		$new_snippet = new WPCode_Snippet( $this->get_snippet_data( $snippet ) );

		$new_snippet->save();

		if ( ! empty( $new_snippet->get_id() ) ) {
			$title = $new_snippet->get_title();
			wp_send_json_success(
				array(
					'name' => '' !== $title ? $title : '(no title)',
					'edit' => esc_url_raw(
						add_query_arg(
							array(
								'page'       => 'wpcode-snippet-manager',
								'snippet_id' => $new_snippet->get_id(),
							),
							admin_url( 'admin.php' )
						)
					),
				)
			);
		}
	}


/** Function wpcode_verify_ssl() called by wp_ajax hooks: {'wpcode_verify_ssl'} **/
/** No params detected :-/ **/


/** Function wpcode_save_editor_height() called by wp_ajax hooks: {'wpcode_save_editor_height'} **/
/** Parameters found in function wpcode_save_editor_height(): {"post": ["height"]} **/
function wpcode_save_editor_height() {
	check_ajax_referer( 'wpcode_admin' );

	// If the current user can't edit snippets they should not be trying this.
	if ( ! current_user_can( 'wpcode_edit_snippets' ) ) { //phpcs:ignore
		wp_send_json_error();
	}

	$height = isset( $_POST['height'] ) ? absint( $_POST['height'] ) : false;

	if ( false !== $height ) {
		wpcode()->settings->update_option( 'editor_height', $height );

		wp_send_json_success();
	}

	wp_send_json_error();
}


/** Function wpcode_sync_snippet() called by wp_ajax hooks: {'wpcode_sync_snippet'} **/
/** Parameters found in function wpcode_sync_snippet(): {"post": ["snippet_id", "library_id"]} **/
function wpcode_sync_snippet() {

	// Check nonce.
	if ( ! check_ajax_referer( 'wpcode_admin', 'nonce', false ) ) {
		wp_send_json_error( array( 'message' => __( 'Security check failed.', 'insert-headers-and-footers' ) ) );
	}

	// Check permissions.
	if ( ! current_user_can( 'wpcode_edit_snippets' ) ) { //phpcs:ignore
		wp_send_json_error( array( 'message' => __( 'You do not have permission to edit snippets.', 'insert-headers-and-footers' ) ) );
	}

	$snippet_id = isset( $_POST['snippet_id'] ) ? absint( $_POST['snippet_id'] ) : 0;
	$library_id = isset( $_POST['library_id'] ) ? absint( $_POST['library_id'] ) : 0;

	if ( ! $snippet_id || ! $library_id ) {
		wp_send_json_error( array( 'message' => __( 'Invalid snippet ID.', 'insert-headers-and-footers' ) ) );
	}

	$result = wpcode()->library->update_snippet_from_library( $snippet_id, $library_id );

	if ( ! $result ) {
		wp_send_json_error( array( 'message' => __( 'Failed to update snippet from library.', 'insert-headers-and-footers' ) ) );
	}

	// No need to update the option as we're checking for updates on the fly.

	wp_send_json_success(
		array(
			'message' => __( 'Snippet successfully updated.', 'insert-headers-and-footers' ),
			'version' => isset( $result['version'] ) ? $result['version'] : '',
		)
	);
}


/** Function dismiss() called by wp_ajax hooks: {'wpcode_notification_dismiss'} **/
/** Parameters found in function dismiss(): {"post": ["id"]} **/
function dismiss() {
		// Run a security check.
		check_ajax_referer( 'wpcode_admin', 'nonce' );

		// Check for access and required param.
		if ( ! $this->has_access() || empty( $_POST['id'] ) ) {
			wp_send_json_error();
		}

		$id     = sanitize_text_field( wp_unslash( $_POST['id'] ) );
		$option = $this->get_option();

		// Dismiss all notifications and add them to dissmiss array.
		if ( 'all' === $id ) {
			if ( is_array( $option['feed'] ) && ! empty( $option['feed'] ) ) {
				foreach ( $option['feed'] as $key => $notification ) {
					array_unshift( $option['dismissed'], $notification );
					unset( $option['feed'][ $key ] );
				}
			}
			if ( is_array( $option['events'] ) && ! empty( $option['events'] ) ) {
				foreach ( $option['events'] as $key => $notification ) {
					array_unshift( $option['dismissed'], $notification );
					unset( $option['events'][ $key ] );
				}
			}
		}

		$type = is_numeric( $id ) ? 'feed' : 'events';

		// Remove notification and add in dismissed array.
		if ( is_array( $option[ $type ] ) && ! empty( $option[ $type ] ) ) {
			foreach ( $option[ $type ] as $key => $notification ) {
				if ( $notification['id'] == $id ) { // phpcs:ignore WordPress.PHP.StrictComparisons
					// Add notification to dismissed array.
					array_unshift( $option['dismissed'], $notification );
					// Remove notification from feed or events.
					unset( $option[ $type ][ $key ] );
					break;
				}
			}
		}

		update_option( self::$option_name, $option, false );

		wp_send_json_success();
	}


/** Function wpcode_ajax_add_pack_option() called by wp_ajax hooks: {'wpcode_add_pack_option'} **/
/** Parameters found in function wpcode_ajax_add_pack_option(): {"post": ["slug", "library_id"]} **/
function wpcode_ajax_add_pack_option() {
	check_ajax_referer( 'wpcode_packs', 'nonce' );

	if ( ! current_user_can( 'wpcode_edit_snippets' ) ) {
		wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'insert-headers-and-footers' ) ), 403 );
	}

	if ( ! wpcode()->library_auth->has_auth() ) {
		wp_send_json_error(
			array(
				'message' => __( 'Please connect to the WPCode Library to add pack snippets.', 'insert-headers-and-footers' ),
				'code'    => 'not_connected',
			),
			403
		);
	}

	$slug       = isset( $_POST['slug'] ) ? sanitize_key( wp_unslash( $_POST['slug'] ) ) : '';
	$library_id = isset( $_POST['library_id'] ) ? (int) $_POST['library_id'] : 0;

	if ( '' === $slug || $library_id <= 0 ) {
		wp_send_json_error( array( 'message' => __( 'Invalid request.', 'insert-headers-and-footers' ) ), 400 );
	}

	$packs = WPCode_Packs::get_instance();
	$created = $packs->add_option( $slug, $library_id );
	if ( ! $created ) {
		// The most common reason for failure is the snippet already being present
		// (e.g. another pack installed it, or a concurrent add). Say so clearly
		// and let the UI refresh to the installed state instead of erroring.
		if ( $packs->find_local_snippet_by_library_id( $library_id ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'This snippet is already installed.', 'insert-headers-and-footers' ),
					'code'    => 'already_installed',
				),
				409
			);
		}
		wp_send_json_error( array( 'message' => __( 'Could not add option.', 'insert-headers-and-footers' ) ), 500 );
	}

	wp_send_json_success( array( 'snippet_id' => $created->get_id() ) );
}


/** Function wpcode_search_terms() called by wp_ajax hooks: {'wpcode_search_terms'} **/
/** Parameters found in function wpcode_search_terms(): {"get": ["term"]} **/
function wpcode_search_terms() {
	check_ajax_referer( 'wpcode_admin' );

	if ( ! current_user_can( 'wpcode_edit_snippets' ) ) { //phpcs:ignore
		wp_send_json_error();
	}

	$term = isset( $_GET['term'] ) ? sanitize_text_field( wp_unslash( $_GET['term'] ) ) : '';

	$public_taxonomies = get_taxonomies(
		array(
			'public' => true,
		)
	);

	$terms = get_terms(
		array(
			'search'     => $term,
			'taxonomy'   => $public_taxonomies,
			'hide_empty' => false,
		)
	);

	$results = array();

	foreach ( $terms as $term ) {
		$results[] = array(
			'id'   => $term->term_id,
			'text' => $term->name,
		);
	}

	wp_send_json(
		array(
			'results' => $results,
		)
	);
}


/** Function dismiss_ajax() called by wp_ajax hooks: {'wpcode_notice_dismiss'} **/
/** No params detected :-/ **/


/** Function wpcode_ajax_toggle_pack_option() called by wp_ajax hooks: {'wpcode_toggle_pack_option'} **/
/** Parameters found in function wpcode_ajax_toggle_pack_option(): {"post": ["slug", "library_id", "active"]} **/
function wpcode_ajax_toggle_pack_option() {
	check_ajax_referer( 'wpcode_packs', 'nonce' );

	if ( ! current_user_can( 'wpcode_activate_snippets' ) ) {
		wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'insert-headers-and-footers' ) ), 403 );
	}

	$slug       = isset( $_POST['slug'] ) ? sanitize_key( wp_unslash( $_POST['slug'] ) ) : '';
	$library_id = isset( $_POST['library_id'] ) ? (int) $_POST['library_id'] : 0;
	$active     = isset( $_POST['active'] ) && '1' === $_POST['active'];

	if ( '' === $slug || $library_id <= 0 ) {
		wp_send_json_error( array( 'message' => __( 'Invalid request.', 'insert-headers-and-footers' ) ), 400 );
	}

	$state = WPCode_Packs::get_instance()->toggle_option( $slug, $library_id, $active );
	if ( null === $state ) {
		wp_send_json_error( array( 'message' => __( 'Option not found.', 'insert-headers-and-footers' ) ), 404 );
	}

	// Return the real resulting state — activation can be refused if the snippet
	// errors, in which case it stays inactive.
	wp_send_json_success(
		array(
			'active'             => $state,
			'activation_refused' => $active && ! $state,
		)
	);
}


/** Function wpcode_update_snippet_status() called by wp_ajax hooks: {'wpcode_update_snippet_status'} **/
/** Parameters found in function wpcode_update_snippet_status(): {"post": ["snippet_id", "active"]} **/
function wpcode_update_snippet_status() {
	check_ajax_referer( 'wpcode_admin' );

	if ( empty( $_POST['snippet_id'] ) ) {
		return;
	}
	$snippet_id = absint( $_POST['snippet_id'] );
	$active     = isset( $_POST['active'] ) && 'true' === $_POST['active'];

	$snippet = wpcode_get_snippet( $snippet_id );

	if ( ! current_user_can( 'wpcode_activate_snippets', $snippet ) ) { //phpcs:ignore
		wpcode()->error->add_error(
			array(
				'message' => __( 'You are not allowed to change snippet status, please contact your webmaster.', 'insert-headers-and-footers' ),
				'type'    => 'permissions',
			)
		);
		$active = false;
	} elseif ( $active ) {
		$snippet->activate();
	} else {
		$snippet->deactivate();
	}

	if ( ! isset( $snippet->active ) || $active !== $snippet->active ) {
		$error_message = sprintf(
		// Translators: %2$s is the action that they were trying to perform, either activated or deactivated. %1$s is the error message why the action failed.
			__( 'Snippet not %2$s, the following error was encountered: %1$s', 'insert-headers-and-footers' ),
			'<code>' . wpcode()->error->get_last_error_message() . '</code>',
			$active ? _x( 'activated', 'Snippet status change', 'insert-headers-and-footers' ) : _x( 'deactivated', 'Snippet status change', 'insert-headers-and-footers' )
		);
		// We failed to activate it, so it's an error.
		wp_send_json_error(
			array(
				'message' => $error_message,
			)
		);
	}
	exit;
}


/** Function install_plugin() called by wp_ajax hooks: {'wpcode_install_plugin'} **/
/** Parameters found in function install_plugin(): {"post": ["slug"]} **/
function install_plugin() {
		check_ajax_referer( 'wpcode_admin' );

		// If the current user can't install plugins they should not be trying this.
		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_send_json_error();
		}

		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/template.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-screen.php';
		require_once ABSPATH . 'wp-admin/includes/screen.php';

		$allowed_plugins = self::all_plugins();
		$slug            = isset( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';

		if ( ! array_key_exists( $slug, $allowed_plugins ) ) {
			wp_send_json_error();
		}

		$plugin_info = $allowed_plugins[ $slug ];

		// Set the current screen to avoid undefined notices.
		set_current_screen( 'toplevel_page_wpcode' );
		// Do not allow WordPress to search/download translations, as this will break JS output.
		remove_action( 'upgrader_process_complete', array( 'Language_Pack_Upgrader', 'async_upgrade' ), 20 );

		// Let's check if the plugin is already installed.
		if ( is_plugin_active( $plugin_info['slug'] ) ) {
			wp_send_json_success(
				array(
					'msg' => esc_html__( 'Plugin already installed and activated.', 'insert-headers-and-footers' ),
				)
			);
		}

		// Check if the Pro plugin is available first.
		if ( $this->is_plugin_installed( $plugin_info['pro_slug'] ) ) {
			activate_plugin( $plugin_info['pro_slug'] );
			wp_send_json_success(
				array(
					'msg' => esc_html__( 'Plugin activated.', 'insert-headers-and-footers' ),
				)
			);
		}

		if ( $this->is_plugin_installed( $plugin_info['slug'] ) ) {
			activate_plugin( $plugin_info['slug'] );
			wp_send_json_success(
				array(
					'msg' => esc_html__( 'Plugin activated.', 'insert-headers-and-footers' ),
				)
			);
		}

		wpcode_require_upgrader();

		// Create the plugin upgrader with our custom skin.
		$installer = new Plugin_Upgrader( new WPCode_Skin() );

		$installer->install( $allowed_plugins[ $slug ]['url'] );

		// Flush the cache and return the newly installed plugin basename.
		wp_cache_flush();

		$plugin_basename = $installer->plugin_info();
		if ( ! $plugin_basename ) {
			wp_send_json_error();
		}

		// Activate the plugin silently.
		$activated = activate_plugin( $plugin_basename );

		if ( is_wp_error( $activated ) ) {
			wp_send_json_error();
		}

		wp_send_json_success(
			array(
				'message' => esc_html__( 'Plugin installed and activated.', 'insert-headers-and-footers' ),
			)
		);
	}


/** Function process() called by wp_ajax hooks: {'nopriv_wpcode_connect_process'} **/
/** No params detected :-/ **/


/** Function wpcode_get_shortcode_locations() called by wp_ajax hooks: {'wpcode_get_shortcode_locations'} **/
/** Parameters found in function wpcode_get_shortcode_locations(): {"post": ["snippet_id", "page"]} **/
function wpcode_get_shortcode_locations() {
	check_ajax_referer( 'wpcode_admin', '_wpnonce' );

	if ( ! current_user_can( 'wpcode_edit_snippets' ) ) { //phpcs:ignore
		wp_send_json_error();
	}

	if ( empty( $_POST['snippet_id'] ) ) {
		wp_send_json_error( 'No snippet ID provided' );
	}

	$snippet_id = absint( $_POST['snippet_id'] );
	$snippet    = wpcode_get_snippet( $snippet_id );

	$page     = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
	$per_page = 100;

	$post_types = array_merge(
		get_post_types( array( 'public' => true ) ),
		array(
			'wp_template',
			'wp_template_part',
			'wpcode',
		)
	);

	// Add post types to params.
	$params = $post_types;

	global $wpdb;

	$search_terms   = array();
	$search_terms[] = '[wpcode';
	if ( $snippet->get_custom_shortcode() ) {
		$search_terms[] = '[' . $snippet->get_custom_shortcode();
	}

	$like_clauses = array();
	foreach ( $search_terms as $term ) {
		$like_clauses[] = 'post_content LIKE %s';
		$params[]       = '%' . $wpdb->esc_like( $term ) . '%';
	}
	$where_like = implode( ' OR ', $like_clauses );

	$offset = ( $page - 1 ) * $per_page;

	$post_type_placeholders = implode( ', ', array_fill( 0, count( $post_types ), '%s' ) );

	$params[] = $per_page;
	$params[] = $offset;

	// Build query structure with sprintf, escaping prepare() placeholders with %%.
	$query = sprintf(
		"SELECT ID FROM {$wpdb->posts} WHERE post_type IN (%s) AND post_status != 'trash' AND (%s) LIMIT %%d OFFSET %%d",
		$post_type_placeholders,
		$where_like
	);

	$candidate_ids = $wpdb->get_col( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery
		$wpdb->prepare( $query, $params ) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	);
	$candidate_ids = array_unique( $candidate_ids );

	$matching_posts = array();

	if ( ! empty( $candidate_ids ) ) {
		$candidate_posts = get_posts(
			array(
				'post_type'      => array_values( $post_types ),
				'post_status'    => 'any',
				'posts_per_page' => - 1,
				'include'        => $candidate_ids,
			)
		);

		foreach ( $candidate_posts as $post ) {
			$content = $post->post_content;

			if ( has_shortcode( $content, 'wpcode' ) && preg_match( '/\[wpcode[^\]]*id\s*=\s*["\']\s*' . preg_quote( $snippet_id, '/' ) . '\s*["\'][^\]]*\]/', $content ) ) {
				$matching_posts[] = $post;
				continue;
			}

			if ( $snippet->get_custom_shortcode() && has_shortcode( $content, $snippet->get_custom_shortcode() ) && preg_match( '/\[' . preg_quote( $snippet->get_custom_shortcode(), '/' ) . '[^\]]*\]/', $content ) ) {
				$matching_posts[] = $post;
			}
		}
	}

	if ( empty( $matching_posts ) ) {
		if ( 1 === $page ) {
			$html = '<ul><li>' . esc_html__( 'This shortcode is not used in any content yet.', 'insert-headers-and-footers' ) . '</li></ul>';
		} else {
			$html = '';
		}
	} else {
		$html = '<ul>';
		foreach ( $matching_posts as $post ) {
			if ( 'wpcode' === $post->post_type ) {
				$edit_link       = esc_url( add_query_arg( 'snippet_id', $post->ID, admin_url( 'admin.php?page=wpcode-snippet-manager' ) ) );
				$post_type_label = esc_html__( 'WPCode Snippet', 'insert-headers-and-footers' );
			} else {
				$edit_link       = get_edit_post_link( $post->ID );
				$post_type_obj   = get_post_type_object( $post->post_type );
				$post_type_label = $post_type_obj ? $post_type_obj->labels->singular_name : $post->post_type;
			}

			$html .= sprintf(
				'<li><a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a> <span class="wpcode-post-type">(%3$s)</span></li>',
				esc_url( $edit_link ),
				// Translators: %d is the post id.
				esc_html( empty( $post->post_title ) ? sprintf( esc_html__( 'Untitled (#%d)', 'insert-headers-and-footers' ), $post->ID ) : $post->post_title ),
				esc_html( $post_type_label )
			);
		}
		$html .= '</ul>';
	}

	$has_more = count( $candidate_ids ) >= $per_page;

	wp_send_json_success(
		array(
			'html'     => $html,
			'has_more' => $has_more,
			'page'     => $page,
		)
	);
}


/** Function wpcode_save_generated_snippet() called by wp_ajax hooks: {'wpcode_save_generated_snippet'} **/
/** Parameters found in function wpcode_save_generated_snippet(): {"post": ["type", "snippet_id"]} **/
function wpcode_save_generated_snippet() {

	check_ajax_referer( 'wpcode_generate', 'nonce' );

	// If the current user can't edit snippets they should not be trying this.
	if ( ! current_user_can( 'wpcode_edit_snippets' ) ) { //phpcs:ignore
		wp_send_json_error();
	}

	$generator_type = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';
	$generator      = wpcode()->generator()->get_type( $generator_type );
	// If a snippet id is passed, let's attempt to update it.
	$snippet_id = isset( $_POST['snippet_id'] ) ? absint( $_POST['snippet_id'] ) : '';

	if ( ! $generator ) {
		wp_send_json_error();
	}

	$snippet_code = $generator->process_form_data( $_POST );

	$snippet_data = array(
		// Translators: this an auto-generated title for when a snippet is saved from the generator.
		'title'          => sprintf( __( 'Generated Snippet %s', 'insert-headers-and-footers' ), $generator->get_title() ),
		'code'           => $snippet_code,
		'code_type'      => $generator->get_code_type(),
		'tags'           => $generator->get_tags(),
		'location'       => $generator->get_location(),
		'generator'      => $generator->get_name(),
		'generator_data' => $generator->get_generator_data(),
		'auto_insert'    => $generator->get_auto_insert(),
	);

	// If a snippet id is passed, let's attempt to update the snippet.
	if ( ! empty( $snippet_id ) ) {
		$snippet = new WPCode_Snippet( $snippet_id );
		// Let's make sure this is an id for a snippet.
		if ( null !== $snippet->get_post_data() ) {
			$snippet_data['id']     = $snippet_id;
			$snippet_data['active'] = false;
			// Don't change the title of an existing snippet.
			unset( $snippet_data['title'] );
		}
	}

	$new_snippet = new WPCode_Snippet( $snippet_data );

	$new_snippet_id = $new_snippet->save();

	wp_send_json_success(
		array(
			'url' => add_query_arg(
				array(
					'page'       => 'wpcode-snippet-manager',
					'snippet_id' => $new_snippet_id,
				),
				admin_url( 'admin.php' )
			),
		)
	);
}


/** Function wpcode_filter_snippets_by_type() called by wp_ajax hooks: {'wpcode_filter_snippets_by_type'} **/
/** Parameters found in function wpcode_filter_snippets_by_type(): {"post": ["snippet_type", "location", "s"], "get": ["type", "location", "s"]} **/
function wpcode_filter_snippets_by_type() {

	check_ajax_referer( 'wpcode_admin' );

	// If the current user can't edit snippets they should not be trying this.
	if ( ! current_user_can( 'wpcode_edit_snippets' ) ) { //phpcs:ignore
		wp_send_json_error();
	}

	if ( ! isset( $_POST['snippet_type'] ) ) {
		wp_send_json_error();
	}

	require_once WPCODE_PLUGIN_PATH . 'includes/admin/pages/class-wpcode-code-snippets-table.php';

	$snippet_type = isset( $_POST['snippet_type'] ) ? sanitize_text_field( wp_unslash( $_POST['snippet_type'] ) ) : '';
	$location     = isset( $_POST['location'] ) ? sanitize_text_field( wp_unslash( $_POST['location'] ) ) : '';
	$search_term  = isset( $_POST['s'] ) ? sanitize_text_field( wp_unslash( $_POST['s'] ) ) : '';

	$screen_id      = 'toplevel_page_wpcode';
	$current_screen = convert_to_screen( $screen_id );
	set_current_screen( $screen_id );

	$snippets_table = new WPCode_Code_Snippets_Table();

	// Used screen object to set up table.
	$snippets_table->screen = $current_screen;

	$_GET['type']     = $snippet_type;
	$_GET['location'] = $location;
	$_GET['s']        = $search_term;

	$snippets_table->prepare_items();
	$count = $snippets_table->get_total_items();

	// Output table HTML.
	ob_start();
	?>
	<input type="hidden" name="page" value="wpcode"/>
	<?php
	$snippets_table->search_box( __( 'Search Snippets', 'insert-headers-and-footers' ), 'wpcode_snippet_search' );
	$snippets_table->views();
	$snippets_table->display();

	$table_html = ob_get_clean();

	// Send success response.
	wp_send_json_success(
		array(
			'html'  => $table_html,
			'count' => $count,
		)
	);
}


