<?php
/***
*
*Found actions: 31
*Found functions:24
*Extracted functions:21
*Total parameter names extracted: 6
*Overview: {'Breeze_Configuration': {'breeze_check_cdn_url', 'breeze_reset_default', 'breeze_purge_opcache', 'breeze_purge_database'}, 'update_options_for_varnish': {'save_settings_tab_varnish'}, 'clear_breeze_cache': {'shortpixel_ai_handle_page_action'}, 'regenerate_breeze_api_key': {'refresh_api_token_key'}, 'update_options_for_inherit': {'save_settings_tab_inherit'}, 'breeze_check_the_files_permission': {'breeze_file_permission_check'}, 'import_json_settings': {'breeze_import_json'}, 'update_options_for_advanced': {'save_settings_tab_advanced'}, 'Breeze_One_Click_Optimization': {'breeze_restore_settings', 'breeze_apply_optimization', 'breeze_hide_optimization_notice', 'breeze_check_compatibility'}, 'update_options_for_faq': {'save_settings_tab_faq'}, 'wp_ajax_breeze_purge_file': {'breeze_purge_varnish'}, 'update_options_for_file': {'save_settings_tab_file'}, 'update_options_for_basic': {'save_settings_tab_basic'}, 'update_options_for_cdn': {'save_settings_tab_cdn'}, 'update_options_for_preload': {'save_settings_tab_preload'}, 'update_options_for_tools': {'save_settings_tab_tools'}, 'update_options_for_heartbeat': {'save_settings_tab_heartbeat'}, 'export_json_settings': {'breeze_export_json'}, 'update_options_for_database': {'save_settings_tab_database'}, 'compatibility_warning_close': {'compatibility_warning_close'}, 'store_update_count': {'store_update_count'}, 'breeze_woocs_fetch_currency': {'breeze_woocs_currency_get', 'nopriv_breeze_woocs_currency_get'}, 'breeze_option_tab_display': {'breeze_load_options_tab'}, 'breeze_dismiss_cache_notice': {'breeze_dismiss_cache_notice'}}
*
***/

/** Function Breeze_Configuration() called by wp_ajax hooks: {'breeze_check_cdn_url', 'breeze_reset_default', 'breeze_purge_opcache', 'breeze_purge_database'} **/
/** No function found :-/ **/


/** Function update_options_for_varnish() called by wp_ajax hooks: {'save_settings_tab_varnish'} **/
/** No params detected :-/ **/


/** Function clear_breeze_cache() called by wp_ajax hooks: {'shortpixel_ai_handle_page_action'} **/
/** No params detected :-/ **/


/** Function regenerate_breeze_api_key() called by wp_ajax hooks: {'refresh_api_token_key'} **/
/** No params detected :-/ **/


/** Function update_options_for_inherit() called by wp_ajax hooks: {'save_settings_tab_inherit'} **/
/** No params detected :-/ **/


/** Function breeze_check_the_files_permission() called by wp_ajax hooks: {'breeze_file_permission_check'} **/
/** No params detected :-/ **/


/** Function import_json_settings() called by wp_ajax hooks: {'breeze_import_json'} **/
/** Parameters found in function import_json_settings(): {"files": ["breeze_import_file"], "post": ["network_level"]} **/
function import_json_settings() {
		breeze_is_restricted_access();
		check_ajax_referer( '_breeze_import_settings', 'security' );

		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( new WP_Error( 'authority_issue', __( 'Only administrator can import settings', 'breeze' ) ) );

		}

		set_as_network_screen();

		if ( isset( $_FILES['breeze_import_file'] ) ) {
			$allowed_extension = array( 'json' );
			$temp              = explode( '.', $_FILES['breeze_import_file']['name'] );
			$extension         = strtolower( end( $temp ) );

			if ( ! in_array( $extension, $allowed_extension, true ) ) {
				wp_send_json_error( new WP_Error( 'ext_err', __( 'The provided file is not a JSON', 'breeze' ) ) );
			}

			if ( 'application/json' !== $_FILES['breeze_import_file']['type'] ) {
				wp_send_json_error( new WP_Error( 'format_err', __( 'The provided file is not a JSON file.', 'breeze' ) ) );
			}

			$get_file_content = file_get_contents( $_FILES['breeze_import_file']['tmp_name'] );
			$json             = json_decode( trim( $get_file_content ), true );

			if ( json_last_error() === JSON_ERROR_NONE ) {
				if (
					isset( $json['breeze_basic_settings'] ) &&
					isset( $json['breeze_advanced_settings'] ) &&
					isset( $json['breeze_cdn_integration'] )
				) {
					$level = '';
					if ( is_multisite() ) {
						$level = ( isset( $_POST['network_level'] ) ) ? trim( $_POST['network_level'] ) : '';

						// Map `network_level` to a scope the current user can act on:
						//   - 'network'             -> Super Admins only
						//   - numeric (other blog)  -> Super Admins, or the own
						//                              blog id for site administrators
						//   - anything else         -> the current site
						$current_blog_id = (int) get_current_blog_id();
						if ( 'network' === $level ) {
							if ( ! breeze_user_can_manage_network() ) {
								wp_send_json_error(
									new WP_Error(
										'authority_issue',
										__( 'Only Network (Super) Administrators can import settings at the network level.', 'breeze' )
									)
								);
							}
						} elseif ( is_numeric( $level ) ) {
							$target_blog_id = (int) $level;
							if ( $target_blog_id !== $current_blog_id && ! breeze_user_can_manage_network() ) {
								wp_send_json_error(
									new WP_Error(
										'authority_issue',
										__( 'You can only import settings for the site you administer.', 'breeze' )
									)
								);
							}
							$level = (string) $target_blog_id;
						} else {
							$level = '';
						}
					}
					if ( ! isset( $json['breeze_file_settings'] ) && ! isset( $json['breeze_preload_settings'] ) ) {
						$action = self::replace_options_old_to_new( $json, $level );
					} else {
						$action = $this->replace_options( $json, $level );
					}

					if ( false === $action ) {
						wp_send_json_error( new WP_Error( 'option_read', __( 'Could not read the options from the provided JSON file', 'breeze' ) ) );
					} elseif ( true !== $action ) {
						wp_send_json_error( new WP_Error( 'error_meta', $action ) );
					}

					wp_send_json_success( __( "Settings imported successfully. \nPage will reload", 'breeze' ) );
				}
				wp_send_json_error( new WP_Error( 'incorrect_content', __( 'The JSON content is not valid', 'breeze' ) ) );

			} else {
				wp_send_json_error( new WP_Error( 'invalid_file', __( 'The JSON file is not valid', 'breeze' ) . ': ' . json_last_error_msg() ) );

			}
		} else {
			wp_send_json_error( new WP_Error( 'file_not_set', __( 'The JSON file is missing', 'breeze' ) ) );
		}
	}


/** Function update_options_for_advanced() called by wp_ajax hooks: {'save_settings_tab_advanced'} **/
/** No params detected :-/ **/


/** Function Breeze_One_Click_Optimization() called by wp_ajax hooks: {'breeze_restore_settings', 'breeze_apply_optimization', 'breeze_hide_optimization_notice', 'breeze_check_compatibility'} **/
/** No function found :-/ **/


/** Function update_options_for_faq() called by wp_ajax hooks: {'save_settings_tab_faq'} **/
/** No params detected :-/ **/


/** Function wp_ajax_breeze_purge_file() called by wp_ajax hooks: {'breeze_purge_varnish'} **/
/** No function found :-/ **/


/** Function update_options_for_file() called by wp_ajax hooks: {'save_settings_tab_file'} **/
/** No params detected :-/ **/


/** Function update_options_for_basic() called by wp_ajax hooks: {'save_settings_tab_basic'} **/
/** No params detected :-/ **/


/** Function update_options_for_cdn() called by wp_ajax hooks: {'save_settings_tab_cdn'} **/
/** No params detected :-/ **/


/** Function update_options_for_preload() called by wp_ajax hooks: {'save_settings_tab_preload'} **/
/** No params detected :-/ **/


/** Function update_options_for_tools() called by wp_ajax hooks: {'save_settings_tab_tools'} **/
/** No params detected :-/ **/


/** Function update_options_for_heartbeat() called by wp_ajax hooks: {'save_settings_tab_heartbeat'} **/
/** No params detected :-/ **/


/** Function export_json_settings() called by wp_ajax hooks: {'breeze_export_json'} **/
/** Parameters found in function export_json_settings(): {"get": ["network_level"]} **/
function export_json_settings() {
		check_ajax_referer( '_breeze_export_json', 'security' );
		breeze_is_restricted_access();
		$level = '';
		if ( is_multisite() ) {
			$level = ( isset( $_GET['network_level'] ) ) ? trim( wp_unslash( $_GET['network_level'] ) ) : '';

			// Network-level and cross-site exports are limited to Super Admins.
			$current_blog_id = (int) get_current_blog_id();
			if ( 'network' === $level ) {
				if ( ! breeze_user_can_manage_network() ) {
					wp_send_json_error(
						new WP_Error(
							'authority_issue',
							__( 'Only Network (Super) Administrators can export settings at the network level.', 'breeze' )
						)
					);
				}
			} elseif ( is_numeric( $level ) ) {
				$target_blog_id = (int) $level;
				if ( $target_blog_id !== $current_blog_id && ! breeze_user_can_manage_network() ) {
					wp_send_json_error(
						new WP_Error(
							'authority_issue',
							__( 'You can only export settings for the site you administer.', 'breeze' )
						)
					);
				}
				$level = (string) $target_blog_id;
			} else {
				$level = '';
			}
		}
		$response = self::read_options( $level );

		header( 'Content-disposition: attachment; filename=breeze-export-settings-' . date_i18n( 'd-m-Y' ) . '.json' );
		header( 'Content-type: application/json' );

		wp_send_json( $response );
	}


/** Function update_options_for_database() called by wp_ajax hooks: {'save_settings_tab_database'} **/
/** No params detected :-/ **/


/** Function compatibility_warning_close() called by wp_ajax hooks: {'compatibility_warning_close'} **/
/** No params detected :-/ **/


/** Function store_update_count() called by wp_ajax hooks: {'store_update_count'} **/
/** Parameters found in function store_update_count(): {"post": ["nonce", "count"]} **/
function store_update_count()
	{
		// Nonce security.
		if (! isset($_POST['nonce']) || ! wp_verify_nonce($_POST['nonce'], 'breeze_store_update_count_nonce')) {
			wp_send_json_error('Invalid nonce');
		}

		// Check user capability.
		if (! current_user_can('update_plugins')) {
			wp_send_json_error('Unauthorized user');
		}
		if (isset($_POST['count'])) {
			$count              = intval($_POST['count']);
			$total_plugin_count = get_option('plugins_to_be_updated_count', 0);
			$total_plugin_count = intval($total_plugin_count);
			if (0 === $total_plugin_count) {
				update_option('plugins_to_be_updated_count', $count, 'no');
			}
		}
		echo 'count stored';
		wp_die();
	}


/** Function breeze_woocs_fetch_currency() called by wp_ajax hooks: {'breeze_woocs_currency_get', 'nopriv_breeze_woocs_currency_get'} **/
/** Parameters found in function breeze_woocs_fetch_currency(): {"post": ["nonce"]} **/
function breeze_woocs_fetch_currency() {
		if ( ! wp_verify_nonce( $_POST['nonce'], 'breeze_woocs_nonce' ) ) {
			wp_send_json_error( 'Invalid security token' );

			return;
		}

		// phpcs:ignore WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
		global $WOOCS;

		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		if ( ! class_exists( 'WOOCS_STARTER' ) ) {
			return;
		}
		// phpcs:ignore WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
		wp_send_json_success( $WOOCS->get_woocommerce_currency() );
	}


/** Function breeze_option_tab_display() called by wp_ajax hooks: {'breeze_load_options_tab'} **/
/** Parameters found in function breeze_option_tab_display(): {"get": ["request_tab"]} **/
function breeze_option_tab_display() {
		$accepted_tabs = array(
			'basic',
			'file',
			'preload',
			'advanced',
			'database',
			'cdn',
			'tools',
			'faq',
			'varnish',
			'heartbeat',
			'one-click-optimization',
		);

		$requested_tab = ( isset( $_GET['request_tab'] ) ? $_GET['request_tab'] : 'basic' );

		if ( ! in_array( $requested_tab, $accepted_tabs, true ) || true === breeze_is_restricted_access( true ) ) {
			die( '<h3>The requested tab does not exist</h3>' );
		}
		ob_start();
		Breeze_Admin::render( $requested_tab );
		$html_tab_data = ob_get_contents();
		ob_end_clean();

		// Output trusted admin HTML content from plugin's own render function
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $html_tab_data;
		wp_die();
	}


/** Function breeze_dismiss_cache_notice() called by wp_ajax hooks: {'breeze_dismiss_cache_notice'} **/
/** No params detected :-/ **/


