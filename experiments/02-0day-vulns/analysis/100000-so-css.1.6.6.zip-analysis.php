<?php
/***
*
*Found actions: 7
*Found functions:7
*Extracted functions:7
*Total parameter names extracted: 7
*Overview: {'admin_action_hide_getting_started': {'socss_hide_getting_started'}, 'manage_product': {'siteorigin_installer_manage'}, 'admin_action_save_css': {'socss_save_css'}, 'installer_status_ajax': {'so_installer_status'}, 'admin_action_get_revisions_list': {'socss_get_revisions_list'}, 'admin_action_get_post_css': {'socss_get_post_css'}, 'dismiss_notice': {'so_installer_dismiss'}}
*
***/

/** Function admin_action_hide_getting_started() called by wp_ajax hooks: {'socss_hide_getting_started'} **/
/** Parameters found in function admin_action_hide_getting_started(): {"get": ["_wpnonce"]} **/
function admin_action_hide_getting_started() {
		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'hide' ) ) {
			return;
		}

		$user = wp_get_current_user();

		if ( ! empty( $user ) ) {
			update_user_meta( $user->ID, 'socss_hide_gs', true );
		}
	}


/** Function manage_product() called by wp_ajax hooks: {'siteorigin_installer_manage'} **/
/** Parameters found in function manage_product(): {"post": ["slug", "task", "type", "version"]} **/
function manage_product() {
			check_ajax_referer( 'siteorigin-installer-manage' );

			if (
				empty( $_POST['slug'] ) ||
				empty( $_POST['task'] ) ||
				empty( $_POST['type'] )
			) {
				die();
			}

			// SO Premium won't have a version.
			if (
				empty( $_POST['version'] ) &&
				$_POST['slug'] != 'siteorigin-premium'
			) {
				die();
			}

			$slug = sanitize_file_name( $_POST['slug'] );

			$product_url = 'https://wordpress.org/' . urlencode( $_POST['type'] ) . '/download/' . urlencode( $slug ) . '.' . urlencode( $_POST['version'] ) . '.zip';
			// check_ajax_referer( 'so_installer_manage' );
			if ( ! class_exists( 'WP_Upgrader' ) ) {
				require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			}
			$upgrader = new WP_Upgrader();
			if ( $_POST['type'] == 'plugins' ) {
				if ( $_POST['task'] == 'install' || $_POST['task'] == 'update' ) {
					$upgrader->run( array(
						'package' => esc_url( $product_url ),
						'destination' => WP_PLUGIN_DIR,
						'clear_destination' => true,
						'abort_if_destination_exists' => false,
						'hook_extra' => array(
							'type' => 'plugin',
							'action' => 'install',
						),
					) );

					$clear = true;
				} elseif (
					$_POST['task'] == 'activate' &&
					! is_wp_error( validate_plugin( $slug . '/' . $slug . '.php' ) )
				) {
					activate_plugin( $slug . '/' . $slug . '.php' );
					$clear = true;
				}
			} elseif ( $_POST['type'] == 'themes' ) {
				if ( $_POST['task'] == 'install' || $_POST['task'] == 'update' ) {
					$upgrader->run( array(
						'package' => esc_url( $product_url ),
						'destination' => get_theme_root(),
						'clear_destination' => true,
						'clear_working' => true,
						'abort_if_destination_exists' => false,
					) );
					$clear = true;
				} elseif ( $_POST['task'] == 'activate' ) {
					switch_theme( $slug );
					$clear = true;
				}
			}

			if ( ! empty( $clear ) ) {
				delete_transient( 'siteorigin_installer_product_data' );
			}
			die();
		}


/** Function admin_action_save_css() called by wp_ajax hooks: {'socss_save_css'} **/
/** Parameters found in function admin_action_save_css(): {"get": ["_wpnonce"], "post": ["css"]} **/
function admin_action_save_css() {
		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'so-css-ajax' ) ) {
			wp_die(
				esc_html__( 'The supplied nonce is invalid.', 'so-css' ),
				esc_html__( 'Invalid nonce.', 'so-css' ),
				403
			);
		}

		if ( current_user_can( 'edit_theme_options' ) && isset( $_POST['css'] ) ) {
			// Sanitize CSS input. Should keep most tags, apart from script and style tags.
			$custom_css = self::sanitize_css( stripslashes( $_POST['css'] ) );

			if ( empty( $this->css_file ) ) {
				$current = $this->get_custom_css( $this->theme );
				$this->save_custom_css( $custom_css, $this->theme );
			} else {
				$current = $this->css_file;
			}

			// If this has changed, then add a revision.
			if ( $current != $custom_css ) {
				$this->add_custom_css_revision( $custom_css, $this->theme );
				$this->save_custom_css_file( $custom_css, $this->theme );

				// Output the full revisions list.
				$this->custom_css_revisions_list( $this->theme );
			}
		}
		wp_die();
	}


/** Function installer_status_ajax() called by wp_ajax hooks: {'so_installer_status'} **/
/** Parameters found in function installer_status_ajax(): {"post": ["status"]} **/
function installer_status_ajax () {
			check_ajax_referer( 'siteorigin_installer_status', 'nonce' );
			update_option( 'siteorigin_installer', rest_sanitize_boolean( $_POST['status'] ) );
			die();
		}


/** Function admin_action_get_revisions_list() called by wp_ajax hooks: {'socss_get_revisions_list'} **/
/** Parameters found in function admin_action_get_revisions_list(): {"get": ["_wpnonce"]} **/
function admin_action_get_revisions_list() {
		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'get_revisions_list' ) ) {
			wp_die(
				esc_html__( 'The supplied nonce is invalid.', 'so-css' ),
				esc_html__( 'Invalid nonce.', 'so-css' ),
				403
			);
		}

		$post_id = filter_input( INPUT_GET, 'postId', FILTER_VALIDATE_INT );

		$this->custom_css_revisions_list( $this->theme, $post_id );

		wp_die();
	}


/** Function admin_action_get_post_css() called by wp_ajax hooks: {'socss_get_post_css'} **/
/** Parameters found in function admin_action_get_post_css(): {"get": ["_wpnonce"]} **/
function admin_action_get_post_css() {
		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'get_post_css' ) ) {
			wp_die(
				esc_html__( 'The supplied nonce is invalid.', 'so-css' ),
				esc_html__( 'Invalid nonce.', 'so-css' ),
				403
			);
		}

		$post_id = filter_input( INPUT_GET, 'postId', FILTER_VALIDATE_INT );

		$current = $this->get_custom_css( $this->theme, $post_id );

		$url = empty( $post_id ) ? home_url() : set_url_scheme( get_permalink( $post_id ) );

		wp_send_json( array( 'css' => empty( $current ) ? '' : $current, 'postUrl' => $url ) );
	}


/** Function dismiss_notice() called by wp_ajax hooks: {'so_installer_dismiss'} **/
/** No params detected :-/ **/


