<?php
/***
*
*Found actions: 8
*Found functions:7
*Extracted functions:7
*Total parameter names extracted: 3
*Overview: {'dismiss': {'pdfemb_admin_settings_topbar_upgrade', 'pdfemb_admin_settings_getstarted_dismiss'}, 'dismiss_review': {'wppdf_dismiss_review'}, 'install_partner': {'pdfemb_partners_install'}, 'open': {'pdfemb_admin_settings_getstarted_open'}, 'deactivate_partner': {'pdfemb_partners_deactivate'}, 'activate_partner': {'pdfemb_partners_activate'}, 'defer_review': {'wppdf_defer_review'}}
*
***/

/** Function dismiss() called by wp_ajax hooks: {'pdfemb_admin_settings_topbar_upgrade', 'pdfemb_admin_settings_getstarted_dismiss'} **/
/** No params detected :-/ **/


/** Function dismiss_review() called by wp_ajax hooks: {'wppdf_dismiss_review'} **/
/** No params detected :-/ **/


/** Function install_partner() called by wp_ajax hooks: {'pdfemb_partners_install'} **/
/** Parameters found in function install_partner(): {"post": ["download_url"]} **/
function install_partner(): void {

		check_admin_referer( 'pdfemb-install-partner', 'nonce' );

		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_send_json_error( [ 'error' => esc_html__( 'Insufficient permissions.', 'pdf-embedder' ) ] );
		}

		// Install the addon.
		if ( isset( $_POST['download_url'] ) ) {
			$download_url = esc_url_raw( wp_unslash( $_POST['download_url'] ) );

			// Restrict the download URL to the known partner list to prevent arbitrary-zip install.
			if ( ! in_array( $download_url, array_column( $this->get_plugins(), 'url' ), true ) ) {
				wp_send_json_error( [ 'error' => esc_html__( 'Unsupported plugin.', 'pdf-embedder' ) ] );
			}

			// Set the current screen to avoid undefined notices.
			set_current_screen();

			// Prepare variables.
			$method = '';
			$url    = add_query_arg( 'page', Admin::SLUG, admin_url( 'options-general.php' ) );
			$url    = esc_url( $url );

			// Start output bufferring to catch the filesystem form if credentials are needed.
			ob_start();
			$creds = request_filesystem_credentials( $url, $method, false, false, null );

			if ( $creds === false ) {
				$form = ob_get_clean();
				echo wp_json_encode( [ 'form' => $form ] );
				die;
			}

			// If we are not authenticated, make it happen now.
			if ( ! WP_Filesystem( $creds ) ) {
				ob_start();
				request_filesystem_credentials( $url, $method, true, false, null );
				$form = ob_get_clean();
				echo wp_json_encode( [ 'form' => $form ] );
				die;
			}

			// We do not need any extra credentials if we have gotten this far, so let's install the plugin.
			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			require_once plugin_dir_path( PDFEMB_PLUGIN_FILE ) . 'deprecated/install_skin.php';

			// Create the plugin upgrader with our custom skin.
			$installer = new Plugin_Upgrader( new WPPDF_Skin() );

			$installer->install( $download_url );

			// Flush the cache and return the newly installed plugin basename.
			wp_cache_flush();

			if ( $installer->plugin_info() ) {
				$plugin_basename = $installer->plugin_info();

				wp_send_json_success( [ 'plugin' => $plugin_basename ] );

				die();
			}
		}

		// Send back a response.
		echo wp_json_encode( true );
		die;
	}


/** Function open() called by wp_ajax hooks: {'pdfemb_admin_settings_getstarted_open'} **/
/** No params detected :-/ **/


/** Function deactivate_partner() called by wp_ajax hooks: {'pdfemb_partners_deactivate'} **/
/** Parameters found in function deactivate_partner(): {"post": ["basename"]} **/
function deactivate_partner(): void {

		check_admin_referer( 'pdfemb-deactivate-partner', 'nonce' );

		// WP has no `deactivate_plugins` capability — `activate_plugins` gates both activate and deactivate.
		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error( [ 'error' => esc_html__( 'Insufficient permissions.', 'pdf-embedder' ) ] );
		}

		// Deactivate the addon.
		if ( isset( $_POST['basename'] ) ) {
			deactivate_plugins( wp_unslash( $_POST['basename'] ) );  // @codingStandardsIgnoreLine
		}

		echo wp_json_encode( true );
		die;
	}


/** Function activate_partner() called by wp_ajax hooks: {'pdfemb_partners_activate'} **/
/** Parameters found in function activate_partner(): {"post": ["basename"]} **/
function activate_partner(): void {

		check_admin_referer( 'pdfemb-activate-partner', 'nonce' );

		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error( [ 'error' => esc_html__( 'Insufficient permissions.', 'pdf-embedder' ) ] );
		}

		// Activate the addon.
		if ( isset( $_POST['basename'] ) ) {
			$activate = activate_plugin( wp_unslash( $_POST['basename'] ) );  // @codingStandardsIgnoreLine

			if ( is_wp_error( $activate ) ) {
				echo wp_json_encode( [ 'error' => $activate->get_error_message() ] );
				die;
			}
		}

		echo wp_json_encode( true );
		die;
	}


/** Function defer_review() called by wp_ajax hooks: {'wppdf_defer_review'} **/
/** No params detected :-/ **/


