<?php
/***
*
*Found actions: 7
*Found functions:7
*Extracted functions:6
*Total parameter names extracted: 5
*Overview: {'wp_ajax_install_plugin': {'stackable_useful_plugins_install'}, 'ajax_update_disable_blocks': {'stackable_update_disable_blocks_v2'}, 'stackable_ajax_dismiss_notice': {'stackable_dismiss_notice'}, 'do_plugin_activate': {'stackable_useful_plugins_activate'}, 'check_cimo_status': {'stackable_check_cimo_status'}, 'stackable_news_feed_ajax': {'stackable_news_feed_ajax'}, 'stackable_notice_gutenberg_plugin_ignore': {'stackable_notice_gutenberg_plugin_ignore'}}
*
***/

/** Function wp_ajax_install_plugin() called by wp_ajax hooks: {'stackable_useful_plugins_install'} **/
/** No function found :-/ **/


/** Function ajax_update_disable_blocks() called by wp_ajax hooks: {'stackable_update_disable_blocks_v2'} **/
/** Parameters found in function ajax_update_disable_blocks(): {"post": ["nonce", "disabledBlocks"]} **/
function ajax_update_disable_blocks() {
			$nonce = isset( $_POST['nonce'] ) ? sanitize_key( $_POST['nonce'] ) : '';

			if ( ! wp_verify_nonce( $nonce, 'stackable_disable_blocks' ) ) {
				wp_send_json_error( __( 'Security error, please refresh the page and try again.', STACKABLE_I18N ) );
			}

			$disabled_blocks = isset( $_POST['disabledBlocks'] ) ? $_POST['disabledBlocks'] : array();
			update_option( 'stackable_v2_disabled_blocks', $disabled_blocks, 'no' );
			wp_send_json_success();
		}


/** Function stackable_ajax_dismiss_notice() called by wp_ajax hooks: {'stackable_dismiss_notice'} **/
/** Parameters found in function stackable_ajax_dismiss_notice(): {"post": ["id", "nonce"]} **/
function stackable_ajax_dismiss_notice() {
        if ( empty( $_POST['id'] ) || empty( $_POST['nonce'] ) ) { // Input var: okay.
            wp_die();
        }

        // Security check.
        if ( ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'stackable_dismiss_notice' ) ) { // Input var: okay.
            wp_die();
        }

		$id = sanitize_text_field( wp_unslash( $_POST['id'] ) ); // Input var: okay.
		stackable_add_in_notification_dismissed( $id );

        wp_die();
    }


/** Function do_plugin_activate() called by wp_ajax hooks: {'stackable_useful_plugins_activate'} **/
/** Parameters found in function do_plugin_activate(): {"post": ["slug", "full_slug"]} **/
function do_plugin_activate() {
			$slug = isset( $_POST['slug'] ) ? sanitize_text_field( $_POST['slug'] ) : '';
			$full_slug = isset( $_POST['full_slug'] ) ? sanitize_text_field( $_POST['full_slug'] ) : '';
			if ( ! $slug || ! $full_slug ) {
				wp_send_json_error( array( 'status' => 'error', 'message' => __( 'Invalid slug.', STACKABLE_I18N ) ), 400 );
			}

			if ( ! check_ajax_referer( 'stk_activate_useful_plugin', 'nonce', false ) ) {
				wp_send_json_error( array( 'status' => 'error', 'message' => __( 'Security check failed.', STACKABLE_I18N ) ), 403 );
				return;
			}

			if ( ! current_user_can( 'activate_plugins' ) ) {
				wp_send_json_error( array( 'status' => 'error', 'message' => __( 'Insufficient permissions.', STACKABLE_I18N ) ), 403 );
				return;
			}

			// Clear the plugins cache to ensure newly installed plugins are recognized (avoids activation errors due to outdated plugin cache)
			wp_clean_plugins_cache();

			if ( ! function_exists( 'activate_plugin' ) ) {
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}

			$result = activate_plugin( $full_slug, '', false, true );

			if ( is_wp_error( $result ) ) {
				wp_send_json_error( array( 'status' => 'error', 'message' => __( 'Failed to activate plugin.', STACKABLE_I18N ) ), 500 );
				return;
			}

			wp_send_json_success( array( 'status' => 'success', 'message' => __( 'Successfully activated plugin.', STACKABLE_I18N ) ), 200 );
		}


/** Function check_cimo_status() called by wp_ajax hooks: {'stackable_check_cimo_status'} **/
/** Parameters found in function check_cimo_status(): {"post": ["user_action"]} **/
function check_cimo_status() {
			// Verify nonce
			if ( ! check_ajax_referer( 'stackable_cimo_status', 'nonce', false ) ) {
				wp_send_json_error( array( 'status' => 'error', 'message' => __( 'Security check failed.', STACKABLE_I18N ) ), 403 );
				return;
			}

			$action = isset( $_POST['user_action'] ) ? sanitize_text_field( $_POST['user_action'] ) : '';
			$response = array(
				'status' => 'activated',
				'action' => ''
			);

			if ( ! $action || ( $action !== 'install' && $action !== 'activate' ) ) {
				wp_send_json_error( array( 'status' => 'error', 'message' => __( 'Invalid request action.', STACKABLE_I18N ) ), 400 );
				return;
			}

			if ( ( $action === 'install' && ! current_user_can( 'install_plugins' ) ) ||
				( $action === 'activate' && ! current_user_can( 'activate_plugins' ) ) ) {
				wp_send_json_error( array( 'status' => 'error', 'message' => __( 'Insufficient permissions.', STACKABLE_I18N ) ), 403 );
				return;
			}

			$plugin_config = self::$PLUGINS['cimo-image-optimizer'];
			$premium_full_slug = isset( $plugin_config['premium_full_slug'] ) ? $plugin_config['premium_full_slug'] : null;
			$full_slug = $plugin_config['full_slug'];

			// Clear plugin cache to ensure we get the most current status
			wp_clean_plugins_cache();

			// Check premium version first
			$is_premium_installed = $premium_full_slug && self::is_plugin_installed( $premium_full_slug );
			$is_premium_activated = $premium_full_slug && self::is_plugin_activated( $premium_full_slug );
			$is_regular_installed = self::is_plugin_installed( $full_slug );
			$is_regular_activated = self::is_plugin_activated( $full_slug );

			// Determine which version to use (premium takes precedence)
			$full_slug_to_use = null;
			if ( $is_premium_activated || $is_premium_installed ) {
				$full_slug_to_use = $premium_full_slug;
				$response['status'] = $is_premium_activated ? 'activated' : 'installed';
			} else if ( $is_regular_activated || $is_regular_installed ) {
				$full_slug_to_use = $full_slug;
				$response['status'] = $is_regular_activated ? 'activated' : 'installed';
			} else {
				$response['status'] = 'not_installed';
			}

			// If plugin is installed but not activated, provide activation link
			if ( $response['status'] === 'installed' && $full_slug_to_use ) {
				$response['action'] = $action === 'install' ? html_entity_decode( wp_nonce_url(
					add_query_arg(
						[
							'action' => 'activate',
							'plugin' => $full_slug_to_use,
						],
						admin_url( 'plugins.php' )
					),
					'activate-plugin_' . $full_slug_to_use
				) ) : '';
			}

			wp_send_json_success( $response );
		}


/** Function stackable_news_feed_ajax() called by wp_ajax hooks: {'stackable_news_feed_ajax'} **/
/** Parameters found in function stackable_news_feed_ajax(): {"post": ["nonce"]} **/
function stackable_news_feed_ajax() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_key( $_POST['nonce'] ) : '';

		if ( ! wp_verify_nonce( $nonce, 'stackable_news_feed' ) ) {
			wp_send_json_error( __( 'Security error, please refresh the page and try again.', STACKABLE_I18N ) );
		}

		wp_send_json_success( stackable_news_feed_links() );
	}


/** Function stackable_notice_gutenberg_plugin_ignore() called by wp_ajax hooks: {'stackable_notice_gutenberg_plugin_ignore'} **/
/** No params detected :-/ **/


