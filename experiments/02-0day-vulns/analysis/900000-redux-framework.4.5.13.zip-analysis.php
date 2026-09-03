<?php
/***
*
*Found actions: 7
*Found functions:6
*Extracted functions:6
*Total parameter names extracted: 5
*Overview: {'get_icons': {'redux_get_icons'}, 'ajax': {'redux_custom_fonts', 'redux_hide_admin_notice'}, 'google_fonts_update': {'redux_update_google_fonts'}, 'redux_delete_widget_area_area': {'redux_delete_widget_area'}, 'parse_ajax': {'redux_color_schemes'}, 'timer': {'redux_custom_font_timer'}}
*
***/

/** Function get_icons() called by wp_ajax hooks: {'redux_get_icons'} **/
/** Parameters found in function get_icons(): {"post": ["nonce", "icon_set", "select_text", "data"]} **/
function get_icons() {
			if ( ! is_user_logged_in() && ! is_admin() && ! current_user_can( $this->parent->args['page_permissions'] ) ) {
				wp_send_json_error( array( 'error' => esc_html__( 'Error: You do not have permission to perform this action.', 'redux-framework' ) ) );
			}

			$nonce       = ( ! empty( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
			$icon_set    = ( ! empty( $_POST['icon_set'] ) ) ? sanitize_text_field( wp_unslash( $_POST['icon_set'] ) ) : '';
			$select_text = ( ! empty( $_POST['select_text'] ) ) ? sanitize_text_field( wp_unslash( $_POST['select_text'] ) ) : '';

			if ( ! wp_verify_nonce( $nonce, 'redux_icon_nonce' ) ) {
				wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'redux-framework' ) ) );
			}

			ob_start();

			$class = '';

			if ( 'font-awesome' === $icon_set ) {
				require_once Redux_Core::$dir . 'inc/lib/font-awesome-6-free.php';

				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				$icon_lists = apply_filters( 'redux/extensions/icon_select/fontawesome/icons', redux_icon_select_fa_6_free() );
			} elseif ( 'dashicons' === $icon_set ) {
				require_once Redux_Core::$dir . 'inc/lib/dashicons.php';

				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				$icon_lists = apply_filters( 'redux/extensions/icon_select/dashicons/icons', redux_get_dashicons() );
			} elseif ( 'elusive' === $icon_set ) {
				require_once Redux_Core::$dir . 'inc/lib/elusive-icons.php';

				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				$icon_lists = apply_filters( 'redux/extensions/icon_select/elusive/icons', redux_get_font_icons() );
			} else {
				$data  = ( ! empty( $_POST['data'] ) ) ? ( wp_unslash( $_POST['data'] ) ) : ''; // phpcs:ignore WordPress.Security
				$data  = json_decode( rawurldecode( $data ), true );
				$icons = '';

				foreach ( $data as $arr_data ) {
					if ( $arr_data['title'] === $select_text ) {
						$icons = $arr_data['icons'];

						$class = ( ! empty( $arr_data['class'] ) ? $arr_data['class'] . ' ' : '' );
					}
				}

				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				$icon_lists = apply_filters( 'redux/extensions/icon_select/custom/icons', $icons );
			}

			if ( ! empty( $icon_lists ) ) {
				foreach ( $icon_lists as $list ) {

					/**
					 * Icon output.
					 * Custom output for icon libraries that support different standards.
					 *
					 * @param string $default Original output.
					 * @param string $title   Title of the icon.
					 * @param string $class   Class of the icon.
					 * @param string $icon_Set Selected Icon set.
					 *
					 * @since 4.4.2
					 */
					echo apply_filters( 'redux/extension/icon_select/' . $this->parent->args['opt_name'] . '/output', '<i title="' . esc_attr( $list ) . '" class="' . esc_attr( $class . ' ' . $list ) . '" /></i>', $list, $class, $select_text ); // phpcs:ignore WordPress.NamingConventions.ValidHookName, WordPress.Security.EscapeOutput
				}
			} else {
				echo '<div class="redux-error-text">' . esc_html__( 'No data available.', 'redux-framework' ) . '</div>';
			}

			$content = ob_get_clean();

			wp_send_json_success( array( 'content' => $content ) );
		}


/** Function ajax() called by wp_ajax hooks: {'redux_custom_fonts', 'redux_hide_admin_notice'} **/
/** Parameters found in function ajax(): {"post": ["id", "nonce"]} **/
function ajax() {
			global $current_user;

			$core = $this->core();

			if ( ! is_user_logged_in() && ! is_admin() && ! current_user_can( $core->args['page_permissions'] ) ) {
				wp_die( esc_html__( 'You do not have permission to perform this action.', 'redux-framework' ) );
			}

			if ( isset( $_POST['id'] ) ) {
				// Get the notice id.
				$id = explode( '&', sanitize_text_field( wp_unslash( $_POST['id'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification
				$id = $id[0];

				// Get the user id.
				$userid = $current_user->ID;

				if ( ! isset( $_POST['nonce'] ) || ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['nonce'] ) ), $id . $userid . 'nonce' ) ) ) {
					die( 0 );
				} else {
					// Add the dismissed request to the user meta.
					update_user_meta( $userid, 'ignore_' . $id, true );
				}
			}
		}


/** Function google_fonts_update() called by wp_ajax hooks: {'redux_update_google_fonts'} **/
/** No params detected :-/ **/


/** Function redux_delete_widget_area_area() called by wp_ajax hooks: {'redux_delete_widget_area'} **/
/** Parameters found in function redux_delete_widget_area_area(): {"post": ["_wpnonce", "name"]} **/
function redux_delete_widget_area_area() {
			if ( ! is_user_logged_in() && ! is_admin() && ! current_user_can( $this->parent->args['page_permissions'] ) ) {
				wp_die( esc_html__( 'You do not have permission to perform this action.', 'redux-framework' ) );
			}

			if ( isset( $_POST ) && isset( $_POST['_wpnonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['_wpnonce'] ) ), 'delete-redux-widget_area-nonce' ) ) {
				if ( isset( $_POST['name'] ) && ! empty( sanitize_text_field( wp_unslash( $_POST['name'] ) ) ) ) {
					$name               = sanitize_text_field( wp_unslash( $_POST['name'] ) );
					$this->widget_areas = $this->get_widget_areas();
					$key                = array_search( $name, $this->widget_areas, true );

					if ( $key >= 0 ) {
						unset( $this->widget_areas[ $key ] );
						$this->save_widget_areas();
					}

					echo 'widget_area-deleted';
				}
			}

			die();
		}


/** Function parse_ajax() called by wp_ajax hooks: {'redux_color_schemes'} **/
/** Parameters found in function parse_ajax(): {"request": ["nonce", "type"]} **/
function parse_ajax() {
			if ( ! is_user_logged_in() && ! is_admin() && ! current_user_can( $this->parent->args['page_permissions'] ) ) {
				wp_die( esc_html__( 'You do not have permission to perform this action.', 'redux-framework' ) );
			}

			$opt_name = $this->parent->args['opt_name'];
			$parent   = $this->parent;

			$nonce = isset( $_REQUEST['nonce'] ) ? sanitize_key( wp_unslash( $_REQUEST['nonce'] ) ) : '';
			if ( ! wp_verify_nonce( $nonce, 'redux_' . $opt_name . '_color_schemes' ) ) {
				wp_die( esc_html__( 'Invalid Security Credentials. Please reload the page and try again.', 'redux-framework' ) );
			}

			$type = isset( $_REQUEST['type'] ) ? sanitize_key( wp_unslash( $_REQUEST['type'] ) ) : '';
			if ( ! in_array( $type, array( 'save', 'delete', 'update', 'export', 'import' ), true ) ) {
				wp_die( esc_html__( 'Invalid request.', 'redux-framework' ) );
			}

			if ( 'save' === $type ) {
				$this->save_scheme( $parent );
			} elseif ( 'delete' === $type ) {
				$this->delete_scheme( $parent );
			} elseif ( 'update' === $type ) {
				$this->get_scheme_html( $parent );
			} elseif ( 'export' === $type ) {
				$this->download_schemes();
			} elseif ( 'import' === $type ) {
				$this->import_schemes();
			}
		}


/** Function timer() called by wp_ajax hooks: {'redux_custom_font_timer'} **/
/** Parameters found in function timer(): {"post": ["nonce"]} **/
function timer() {
			if ( ! is_user_logged_in() && ! is_admin() && ! current_user_can( $this->parent->args['page_permissions'] ) ) {
				wp_die( esc_html__( 'You do not have permission to perform this action.', 'redux-framework' ), 403 );
			}

			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['nonce'] ) ), 'redux_custom_fonts' ) ) {
				die( 0 );
			}

			$name = get_option( 'redux_custom_font_current' );

			if ( ! empty( $name ) ) {
				echo esc_html( $name );
			}

			die();
		}


