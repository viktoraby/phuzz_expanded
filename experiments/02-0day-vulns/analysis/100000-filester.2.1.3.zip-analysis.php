<?php
/***
*
*Found actions: 12
*Found functions:12
*Extracted functions:12
*Total parameter names extracted: 9
*Overview: {'njt_fs_save_review': {'njt_fs_save_review'}, 'getArrRoleRestrictions': {'get_role_restrictions'}, 'ajax_set_notification': {'njt_{$this->pluginPrefix}_cross_notification'}, 'fsConnector': {'fs_connector'}, 'yay_recommended_upgrade_plugin': {'yay_recommended_upgrade_plugin'}, 'ajax_install_plugin': {'njt_{$this->pluginPrefix}_cross_install'}, 'yay_recommended_activate_plugin': {'yay_recommended_activate_plugin'}, 'njt_fs_saveSetting': {'njt_fs_save_setting'}, 'njt_fs_saveSettingRestrictions': {'njt_fs_save_setting_restrictions'}, 'ajax_hide_cross': {'njt_{$this->pluginPrefix}_cross_hide'}, 'selectorThemes': {'selector_themes'}, 'yay_recommended_get_plugin_data': {'yay_recommended_get_plugin_data'}}
*
***/

/** Function njt_fs_save_review() called by wp_ajax hooks: {'njt_fs_save_review'} **/
/** Parameters found in function njt_fs_save_review(): {"post": ["nonce", "field"]} **/
function njt_fs_save_review()
    {
        if ( isset( $_POST ) ) {
            $nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : null;
            $field = isset( $_POST['field'] ) ? sanitize_text_field( $_POST['field'] ) : null;

            if ( ! wp_verify_nonce( $nonce, 'njt-fs-review' ) ) {
				wp_send_json_error( array( 'status' => 'Wrong nonce validate!' ) );
				exit();
            }
            
            if ($field == 'later'){
                update_option('njt_fs_review', time() + 3*60*60*24); //After 3 days show
            } else if ($field == 'alreadyDid'){
                update_option('njt_fs_review', 0);
            }
            wp_send_json_success();
        }
        wp_send_json_error( array( 'message' => 'Update fail!' ) );
    }


/** Function getArrRoleRestrictions() called by wp_ajax hooks: {'get_role_restrictions'} **/
/** Parameters found in function getArrRoleRestrictions(): {"post": ["nonce", "valueUserRole"]} **/
function getArrRoleRestrictions()
    {
        if(!wp_verify_nonce( $_POST['nonce'] ,'njt-fs-file-manager-admin')) wp_die();
        check_ajax_referer('njt-fs-file-manager-admin', 'nonce', true);
        $valueUserRole = filter_var($_POST['valueUserRole']) ? sanitize_text_field ($_POST['valueUserRole']) : '';
        $arrRestrictions = !empty($this->options['njt_fs_file_manager_settings']['list_user_role_restrictions']) ? $this->options['njt_fs_file_manager_settings']['list_user_role_restrictions'] : array();
        $dataArrRoleRestrictions = array (
            'disable_operations' => implode(",", !empty($arrRestrictions[$valueUserRole]['list_user_restrictions_alow_access']) ? $arrRestrictions[$valueUserRole]['list_user_restrictions_alow_access'] : array()),
            'private_folder_access' => !empty($arrRestrictions[$valueUserRole]['private_folder_access']) ? str_replace("\\\\", "/", trim($arrRestrictions[$valueUserRole]['private_folder_access'])) : '',
            'private_url_folder_access' => !empty($arrRestrictions[$valueUserRole]['private_url_folder_access']) ? str_replace("\\\\", "/", trim($arrRestrictions[$valueUserRole]['private_url_folder_access'])) : '',
            'hide_paths' => implode(',', !empty($arrRestrictions[$valueUserRole]['hide_paths']) ? $arrRestrictions[$valueUserRole]['hide_paths'] : array()),
            'lock_files' => implode(',', !empty($arrRestrictions[$valueUserRole]['lock_files']) ? $arrRestrictions[$valueUserRole]['lock_files'] : array()),
            'can_upload_mime' => implode(',', !empty($arrRestrictions[$valueUserRole]['can_upload_mime']) ? $arrRestrictions[$valueUserRole]['can_upload_mime'] : array())
        );
        wp_send_json_success($dataArrRoleRestrictions);
        wp_die();
    }


/** Function ajax_set_notification() called by wp_ajax hooks: {'njt_{$this->pluginPrefix}_cross_notification'} **/
/** No params detected :-/ **/


/** Function fsConnector() called by wp_ajax hooks: {'fs_connector'} **/
/** No params detected :-/ **/


/** Function yay_recommended_upgrade_plugin() called by wp_ajax hooks: {'yay_recommended_upgrade_plugin'} **/
/** Parameters found in function yay_recommended_upgrade_plugin(): {"post": ["plugin", "nonce", "type"]} **/
function yay_recommended_upgrade_plugin() {
			try {
				require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
				require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
				require_once ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php';
				require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';
				if ( isset( $_POST['plugin'] ) ) {
					$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
					if ( ! wp_verify_nonce( $nonce, 'yay_recommended_nonce' ) ) {
						wp_send_json_error( array( 'mess' => __( 'Nonce is invalid', 'filebird' ) ) );
					}
					$plugin   = sanitize_text_field( $_POST['plugin'] );
					$type     = isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : 'install';
					$skin     = new \WP_Ajax_Upgrader_Skin();
					$upgrader = new \Plugin_Upgrader( $skin );
					if ( 'install' === $type ) {
						$result = $upgrader->install( $plugin );
						if ( is_wp_error( $result ) ) {
							wp_send_json_error(
								array(
									'mess' => $result->get_error_message(),
								)
							);
						}
						$args        = array(
							'slug'   => $upgrader->result['destination_name'],
							'fields' => array(
								'short_description' => true,
								'icons'             => true,
								'banners'           => false,
								'added'             => false,
								'reviews'           => false,
								'sections'          => false,
								'requires'          => false,
								'rating'            => false,
								'ratings'           => false,
								'downloaded'        => false,
								'last_updated'      => false,
								'tags'              => false,
								'compatibility'     => false,
								'homepage'          => false,
								'donate_link'       => false,
							),
						);
						$plugin_data = plugins_api( 'plugin_information', $args );
						if ( $plugin_data && ! is_wp_error( $plugin_data ) ) {
							$install_status = install_plugin_install_status( $plugin_data );
							$active_plugin  = activate_plugin( $install_status['file'] );
							if ( is_wp_error( $active_plugin ) ) {
								wp_send_json_error(
									array(
										'mess' => $active_plugin->get_error_message(),
									)
								);
							} else {
								wp_send_json_success(
									array(
										'mess' => __( 'Install success', 'filebird' ),
									)
								);
							}
						} else {
							wp_send_json_error(
								array(
									'mess' => 'Error',
								)
							);
						}
					} else {
						$is_active = is_plugin_active( $plugin );
						$result    = $upgrader->upgrade( $plugin );
						if ( is_wp_error( $result ) ) {
							wp_send_json_error(
								array(
									'mess' => $result->get_error_message(),
								)
							);
						} else {
							activate_plugin( $plugin );
							wp_send_json_success(
								array(
									'mess'   => __( 'Update success', 'filebird' ),
									'active' => $is_active,
								)
							);
						}
					}
				}
			} catch ( \Exception $ex ) {
				wp_send_json_error(
					array(
						'mess' => __( 'Error exception.', 'filebird' ),
						array(
							'error' => $ex,
						),
					)
				);
			} catch ( \Error $ex ) {
				wp_send_json_error(
					array(
						'mess' => __( 'Error.', 'filebird' ),
						array(
							'error' => $ex,
						),
					)
				);
			}
		}


/** Function ajax_install_plugin() called by wp_ajax hooks: {'njt_{$this->pluginPrefix}_cross_install'} **/
/** No params detected :-/ **/


/** Function yay_recommended_activate_plugin() called by wp_ajax hooks: {'yay_recommended_activate_plugin'} **/
/** Parameters found in function yay_recommended_activate_plugin(): {"post": ["file", "nonce"]} **/
function yay_recommended_activate_plugin() {
			try {
				if ( isset( $_POST['file'] ) ) {
					$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
					if ( ! wp_verify_nonce( $nonce, 'yay_recommended_nonce' ) ) {
						wp_send_json_error( array( 'mess' => __( 'Nonce is invalid', 'filebird' ) ) );
					}
					$file   = sanitize_text_field( $_POST['file'] );
					$result = activate_plugin( $file );

					if ( is_wp_error( $result ) ) {
						wp_send_json_error(
							array(
								'mess' => $result->get_error_message(),
							)
						);
					}
					wp_send_json_success(
						array(
							'mess' => __( 'Activate success', 'filebird' ),
						)
					);
				}
			} catch ( \Exception $ex ) {
				wp_send_json_error(
					array(
						'mess' => __( 'Error exception.', 'filebird' ),
						array(
							'error' => $ex,
						),
					)
				);
			} catch ( \Error $ex ) {
				wp_send_json_error(
					array(
						'mess' => __( 'Error.', 'filebird' ),
						array(
							'error' => $ex,
						),
					)
				);
			}
		}


/** Function njt_fs_saveSetting() called by wp_ajax hooks: {'njt_fs_save_setting'} **/
/** Parameters found in function njt_fs_saveSetting(): {"post": ["nonce", "root_folder_path", "root_folder_url", "list_user_alow_access", "upload_max_size", "fm_locale", "enable_htaccess", "enable_trash", "enable_sensitive_protection"]} **/
function njt_fs_saveSetting()
    {
        if( ! wp_verify_nonce( $_POST['nonce'] ,'njt-fs-file-manager-admin')) wp_die();
        check_ajax_referer('njt-fs-file-manager-admin', 'nonce', true);

        if (!current_user_can('manage_options')) {
            wp_die();
        }

        $root_folder_path = !empty($_POST['root_folder_path']) ? str_replace("\\\\", "/", trim(sanitize_text_field($_POST['root_folder_path']))) : '';
        $root_folder_url = !empty($_POST['root_folder_url']) ? str_replace("\\\\", "/", trim(sanitize_url($_POST['root_folder_url']))) : site_url();
        $list_user_alow_access = !empty($_POST['list_user_alow_access']) ? explode(',', sanitize_text_field($_POST['list_user_alow_access'])) : array();
        $upload_max_size = !empty($_POST['upload_max_size']) ? sanitize_text_field(trim($_POST['upload_max_size'])) : 0;
        $fm_locale = !empty($_POST['fm_locale']) ? sanitize_text_field($_POST['fm_locale']) : 'en';
        $enable_htaccess =  isset($_POST['enable_htaccess']) && $_POST['enable_htaccess'] == 'true' ? 1 : 0;
        $enable_trash = isset($_POST['enable_trash']) && $_POST['enable_trash'] == 'true' ? 1 : 0;
        $enable_sensitive_protection = isset($_POST['enable_sensitive_protection']) && $_POST['enable_sensitive_protection'] == 'true' ? 1 : 0;
        //save options
        $this->options['njt_fs_file_manager_settings']['root_folder_path'] = $root_folder_path;
        $this->options['njt_fs_file_manager_settings']['root_folder_url'] = $root_folder_url;
        $this->options['njt_fs_file_manager_settings']['list_user_alow_access'] = $list_user_alow_access;
        $this->options['njt_fs_file_manager_settings']['upload_max_size'] = $upload_max_size;
        $this->options['njt_fs_file_manager_settings']['fm_locale'] = $fm_locale;
        $this->options['njt_fs_file_manager_settings']['enable_htaccess'] = $enable_htaccess;
        $this->options['njt_fs_file_manager_settings']['enable_trash'] = $enable_trash;
        $this->options['njt_fs_file_manager_settings']['enable_sensitive_protection'] = $enable_sensitive_protection;
        //update options
        update_option('njt_fs_settings', $this->options);
        wp_send_json_success(get_option('njt_fs_settings'));
        wp_die();
    }


/** Function njt_fs_saveSettingRestrictions() called by wp_ajax hooks: {'njt_fs_save_setting_restrictions'} **/
/** Parameters found in function njt_fs_saveSettingRestrictions(): {"post": ["nonce", "njt_fs_list_user_restrictions", "list_user_restrictions_alow_access", "private_folder_access", "private_url_folder_access", "hide_paths", "lock_files", "can_upload_mime"]} **/
function njt_fs_saveSettingRestrictions() {
        if( ! wp_verify_nonce( $_POST['nonce'] ,'njt-fs-file-manager-admin')) wp_die();
        check_ajax_referer('njt-fs-file-manager-admin', 'nonce', true);

        if (!current_user_can('manage_options')) {
            wp_die();
        }

        if(! $_POST['njt_fs_list_user_restrictions']) wp_die();

        $njt_fs_list_user_restrictions = sanitize_text_field($_POST['njt_fs_list_user_restrictions']);
        $list_user_restrictions_alow_access = !empty($_POST['list_user_restrictions_alow_access']) ? explode(',', sanitize_text_field($_POST['list_user_restrictions_alow_access'])) : array();
        $private_folder_access = !empty($_POST['private_folder_access']) ? str_replace("\\\\", "/", trim(sanitize_text_field($_POST['private_folder_access']))) : '';
        $private_url_folder_access = !empty($_POST['private_url_folder_access']) ? str_replace("\\\\", "/", trim(sanitize_text_field($_POST['private_url_folder_access']))) : '';
        $hide_paths = !empty($_POST['hide_paths']) ? explode('|', preg_replace('/\s+/', '', sanitize_text_field($_POST['hide_paths']))) : array();
        $lock_files = !empty($_POST['lock_files']) ? explode('|', preg_replace('/\s+/', '', sanitize_text_field($_POST['lock_files']))) : array();

        $can_upload_mime = !empty($_POST['can_upload_mime']) ? explode(',', preg_replace('/\s+/', '', sanitize_text_field($_POST['can_upload_mime']))) : array();

        $can_upload_mime = array_filter($can_upload_mime, function($item) {
            $helper = new \FileManagerHelper();
            $listFileCanNotUpload = $helper->listFileCanNotUpload();
            return !in_array($item, $listFileCanNotUpload);
        });

        //save options
        $this->options['njt_fs_file_manager_settings']['list_user_role_restrictions'][$njt_fs_list_user_restrictions]['list_user_restrictions_alow_access'] = $list_user_restrictions_alow_access;
        $this->options['njt_fs_file_manager_settings']['list_user_role_restrictions'][$njt_fs_list_user_restrictions]['private_folder_access'] = $private_folder_access;
        $this->options['njt_fs_file_manager_settings']['list_user_role_restrictions'][$njt_fs_list_user_restrictions]['private_url_folder_access'] = $private_url_folder_access;
        $this->options['njt_fs_file_manager_settings']['list_user_role_restrictions'][$njt_fs_list_user_restrictions]['hide_paths'] = $hide_paths;
        $this->options['njt_fs_file_manager_settings']['list_user_role_restrictions'][$njt_fs_list_user_restrictions]['lock_files'] = $lock_files;
        $this->options['njt_fs_file_manager_settings']['list_user_role_restrictions'][$njt_fs_list_user_restrictions]['can_upload_mime'] = $can_upload_mime;
        //update options
        update_option('njt_fs_settings', $this->options);
        wp_send_json_success(get_option('njt_fs_settings'));
        wp_die();
    }


/** Function ajax_hide_cross() called by wp_ajax hooks: {'njt_{$this->pluginPrefix}_cross_hide'} **/
/** Parameters found in function ajax_hide_cross(): {"post": ["type"]} **/
function ajax_hide_cross(){
            check_ajax_referer("njt_{$this->pluginPrefix}_cross_nonce", 'nonce', true);
            
            $type = sanitize_text_field($_POST['type']);
            $time = time() + (30 * 60 * 60 * 24); // hide 30 days
            
            update_option("njt_{$type}_{$this->pluginPrefix}_cross", $time); 
            wp_send_json_success();
        }


/** Function selectorThemes() called by wp_ajax hooks: {'selector_themes'} **/
/** Parameters found in function selectorThemes(): {"post": ["nonce", "themesValue"]} **/
function selectorThemes()
    {
        if( ! wp_verify_nonce( $_POST['nonce'] ,'njt-fs-file-manager-admin')) wp_die();
        check_ajax_referer('njt-fs-file-manager-admin', 'nonce', true);
        
        $themesValue = sanitize_text_field ($_POST['themesValue']);
        $selectorThemes = get_option('njt_fs_selector_themes', array());
        if (!is_array($selectorThemes)) {
            $selectorThemes = array();
        }
        if (empty($selectorThemes[$this->userRole])) {
            $selectorThemes[$this->userRole] = array('themesValue' => 'Default');
            update_option('njt_fs_selector_themes', $selectorThemes);
        }

        if ($selectorThemes[$this->userRole]['themesValue'] != $themesValue) {
            $selectorThemes[$this->userRole]['themesValue'] = $themesValue;
            update_option('njt_fs_selector_themes', $selectorThemes);
        }
        $selected_themes = $selectorThemes;
        $linkThemes = plugins_url('/lib/themes/' . $selected_themes[$this->userRole]['themesValue'] . '/css/theme.css', __FILE__);
        wp_send_json_success($linkThemes);
        wp_die();
    }


/** Function yay_recommended_get_plugin_data() called by wp_ajax hooks: {'yay_recommended_get_plugin_data'} **/
/** Parameters found in function yay_recommended_get_plugin_data(): {"post": ["tab", "nonce"]} **/
function yay_recommended_get_plugin_data() {
			try {
				if ( isset( $_POST['tab'] ) ) {
					$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
					if ( ! wp_verify_nonce( $nonce, 'yay_recommended_nonce' ) ) {
						wp_send_json_error( array( 'mess' => __( 'Nonce is invalid', 'filebird' ) ) );
					}
					$tab                 = sanitize_text_field( $_POST['tab'] );
					$recommendedPlugins = array();
					$recommended_data    = apply_filters( 'yay_recommended_plugins_excluded', $this->recommended_plugin );
					foreach ( $recommended_data as $key => $plugin ) {
						if ( in_array( $tab, $plugin['type'] ) || 'all' === $tab ) {
							$recommendedPlugins[ $key ] = $plugin;
						}
					}
					ob_start();
					$path = plugin_dir_path( __FILE__ ) . 'views/content.php';
					include $path;
					$html = ob_get_contents();
					ob_end_clean();
					wp_send_json_success(
						array(
							'mess' => __( 'Get data success', 'filebird' ),
							'html' => $html,
						)
					);
				}
			} catch ( \Exception $ex ) {
				wp_send_json_error(
					array(
						'mess' => __( 'Error exception.', 'filebird' ),
						array(
							'error' => $ex,
						),
					)
				);
			} catch ( \Error $ex ) {
				wp_send_json_error(
					array(
						'mess' => __( 'Error.', 'filebird' ),
						array(
							'error' => $ex,
						),
					)
				);
			}
		}


