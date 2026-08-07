<?php
/***
*
*Found actions: 1
*Found functions:1
*Extracted functions:1
*Total parameter names extracted: 1
*Overview: {'wp_ajax_install_content_embed': {'content_embed_install'}}
*
***/

/** Function wp_ajax_install_content_embed() called by wp_ajax hooks: {'content_embed_install'} **/
/** Parameters found in function wp_ajax_install_content_embed(): {"request": ["_wpnonce"]} **/
function wp_ajax_install_content_embed() {
		$nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( ( $_REQUEST['_wpnonce'] ) ) ) : '';

		if ( ! current_user_can( 'install_plugins' ) || ! wp_verify_nonce( $nonce, self::INSTALL_ARG ) ) {
			$status['errorCode']    = 'PERMISSIONS_ERROR';
			$status['errorMessage'] = 'User does not have permission to install or activate plugins.';
			return wp_send_json_error( $status, 403 );
		}

		$active_or_installed = self::is_content_embed_active_installed();
		$activated           = $active_or_installed['active'];
		$installed           = $active_or_installed['installed'];

		if ( $activated || $installed ) {
			$status['errorCode']    = $activated ? 'ALREADY_ACTIVATED' : 'ALREADY_INSTALLED';
			$status['errorMessage'] = 'Content embed already installed or activated';
			wp_send_json_error( $status );
		}

		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		$skin     = new \WP_Ajax_Upgrader_Skin();
		$upgrader = new \Plugin_Upgrader( $skin );
		$result   = $upgrader->install( self::CONTENT_EMBED_LINK );

		if ( is_wp_error( $result ) ) {
			$status['errorCode']    = 'GENERIC_ERROR';
			$status['errorMessage'] = $result->get_error_message();
			wp_send_json_error( $status );
		}

		if ( is_wp_error( $skin->result ) ) {
			$status['errorCode']    = 'GENERIC_ERROR';
			$status['errorMessage'] = $skin->result->get_error_message();
			wp_send_json_error( $status );
		}

		if ( $skin->get_errors()->has_errors() ) {
			$status['errorCode']    = 'GENERIC_ERROR';
			$status['errorMessage'] = $skin->get_error_messages();
			wp_send_json_error( $status );
		}

		if ( is_null( $result ) ) {
			global $wp_filesystem;
			$status['errorCode']    = 'FILESYSTEM_ERROR';
			$status['errorMessage'] = 'Unable to connect to the filesystem. Please confirm your credentials.';

			// Pass through the error from WP_Filesystem if one was raised.
			if ( $wp_filesystem instanceof \WP_Filesystem_Base && is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->has_errors() ) {
				$status['errorMessage'] = esc_html( $wp_filesystem->errors->get_error_message() );
			}
			wp_send_json_error( $status );
		}

		$plugin_info = $upgrader->plugin_info();
		if ( ! $plugin_info ) {
			return wp_send_json_error(
				array(
					'errorCode'    => 'INFO_FETCH_ERROR',
					'errorMessage' => 'Plugin installation failed, could not retrieve plugin info',
				),
				500
			);
		}

		$status = array(
			'message'     => 'Plugin installed and activated successfully',
			'plugin_info' => $plugin_info,
			'activated'   => false,
		);

		if ( current_user_can( 'activate_plugins' ) ) {
			$activation_response = activate_plugin( $plugin_info );
			if ( is_wp_error( $activation_response ) ) {
				$status['errorCode']    = 'ACTIVATION_ERROR';
				$status['errorMessage'] = $activation_response->get_error_message();
			} else {
				$status['activated'] = true;
			}
		}

		update_option( self::INSTALL_OPTION, true );
		return wp_send_json_success( $status );
	}


