<?php
/***
*
*Found actions: 6
*Found functions:6
*Extracted functions:6
*Total parameter names extracted: 5
*Overview: {'ajax_local_testing_suite': {'health-check-jetpack-connection-health'}, 'plugin_edit_ajax': {'edit-theme-plugin-file'}, 'handle_proxy': {'boost_proxy_ig'}, 'theme_edit_ajax': {'edit-theme-plugin-file'}, 'ajax_tracks': {'jetpack_tracks'}, 'handle_css_proxy': {'boost_proxy_css'}}
*
***/

/** Function ajax_local_testing_suite() called by wp_ajax hooks: {'health-check-jetpack-connection-health'} **/
/** No params detected :-/ **/


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


/** Function handle_proxy() called by wp_ajax hooks: {'boost_proxy_ig'} **/
/** Parameters found in function handle_proxy(): {"post": ["nonce", "proxy_url"]} **/
function handle_proxy() {
		// Verify valid nonce.
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), self::NONCE_ACTION ) ) {
			wp_send_json_error( 'bad nonce', 400, JSON_UNESCAPED_SLASHES );
		}

		// Make sure currently logged in as admin.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'not admin', 400, JSON_UNESCAPED_SLASHES );
		}

		// Validate URL and fetch.
		$proxy_url = filter_var( wp_unslash( $_POST['proxy_url'] ?? '' ), FILTER_VALIDATE_URL );
		if ( ! wp_http_validate_url( $proxy_url ) ) {
			wp_send_json_error( 'Invalid URL', 400, JSON_UNESCAPED_SLASHES );
		}

		$photon_url = Image_CDN_Core::cdn_url( $proxy_url );
		if ( ! Image_CDN_Core::is_cdn_url( $proxy_url ) ) {
			wp_send_json_error( 'Failed to proxy the image.', 400, JSON_UNESCAPED_SLASHES );
		}

		$response = wp_safe_remote_get( $photon_url );
		if ( is_wp_error( $response ) ) {
			wp_send_json_error( 'error', 400, JSON_UNESCAPED_SLASHES );
		}

		// @phan-suppress-next-line PhanTypeMismatchArgumentProbablyReal -- It takes null, but its phpdoc only says int.
		wp_send_json_success( iterator_to_array( wp_remote_retrieve_headers( $response ) ), null, JSON_UNESCAPED_SLASHES );
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


/** Function handle_css_proxy() called by wp_ajax hooks: {'boost_proxy_css'} **/
/** Parameters found in function handle_css_proxy(): {"post": ["nonce", "proxy_url"]} **/
function handle_css_proxy() {

		// Verify valid nonce.
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), self::NONCE_ACTION ) ) {
			wp_die( '', 400 );
		}

		// Make sure currently logged in as admin.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( '', 400 );
		}

		// Reject any request made when not generating.
		if ( ! ( new Critical_CSS_State() )->is_requesting() ) {
			wp_die( '', 400 );
		}

		// Validate URL and fetch.
		$proxy_url = filter_var( wp_unslash( $_POST['proxy_url'] ?? '' ), FILTER_VALIDATE_URL );
		if ( ! wp_http_validate_url( $proxy_url ) ) {
			die( 'Invalid URL' );
		}

		$url_path = wp_parse_url( $proxy_url, PHP_URL_PATH );
		if ( ! $url_path || substr( strtolower( $url_path ), -4 ) !== '.css' ) {
			wp_die( 'Invalid CSS file URL', 400 );
		}

		$css = $this->get_proxied_css( $proxy_url );

		if ( $css ) {
			$this->serve_proxied_css( $css );
			die( 0 );
		}
	}


