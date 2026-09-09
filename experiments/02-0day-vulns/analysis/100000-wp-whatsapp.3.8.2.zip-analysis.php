<?php
/***
*
*Found actions: 24
*Found functions:18
*Extracted functions:18
*Total parameter names extracted: 13
*Overview: {'ajax_set_notification': {'njt_{$this->pluginPrefix}_cross_notification', 'yay_notification_{$this->pluginPrefix}_cross'}, 'save_analytics_setting': {'njt_wa_save_analytics_setting'}, 'runRestore': {'njt_wa_restore'}, 'ajax_get_account': {'njt_wa_get_account'}, 'ajax_upgrade_plugin': {'yay_recommended_upgrade_plugin'}, 'set_account_position': {'njt_wa_set_account_position'}, 'load_accounts_ajax': {'njt_wa_load_accounts_ajax'}, 'save_url_setting': {'njt_wa_save_url_setting'}, 'ajax_install_plugin': {'yay_media_{$this->pluginPrefix}_cross_install', 'yay_notification_{$this->pluginPrefix}_cross_install', 'njt_{$this->pluginPrefix}_cross_install', 'yay_dashboard_{$this->pluginPrefix}_cross_install'}, 'save_review': {'{$this->pluginPrefix}_save_review'}, 'save_display_setting': {'njt_wa_save_display_setting'}, 'save_woocommerce_setting': {'njt_wa_save_woocommerce_setting'}, 'ajax_hide_cross': {'njt_{$this->pluginPrefix}_cross_hide', 'yay_media_{$this->pluginPrefix}_cross_hide', 'yay_notification_{$this->pluginPrefix}_cross_hide'}, 'set_account_status': {'njt_wa_set_account_status'}, 'ajax_get_plugin_data': {'yay_recommended_get_plugin_data'}, 'ajax_activate_plugin': {'yay_recommended_activate_plugin'}, 'save_user_role_setting': {'njt_wa_save_user_role_setting'}, 'save_design_setting': {'njt_wa_save_design_setting'}}
*
***/

/** Function ajax_set_notification() called by wp_ajax hooks: {'njt_{$this->pluginPrefix}_cross_notification', 'yay_notification_{$this->pluginPrefix}_cross'} **/
/** No params detected :-/ **/


/** Function save_analytics_setting() called by wp_ajax hooks: {'njt_wa_save_analytics_setting'} **/
/** Parameters found in function save_analytics_setting(): {"post": ["enabledGoogle", "enabledFacebook", "enabledGoogleGA4"]} **/
function save_analytics_setting() {
		check_ajax_referer( 'njt-wa-nonce', 'nonce', true );

		$new_input = array();

		$new_input                     = Fields::getAnalyticsSetting();
		$new_input['enabledGoogle']    = isset( $_POST['enabledGoogle'] ) ? 'ON' : 'OFF';
		$new_input['enabledFacebook']  = isset( $_POST['enabledFacebook'] ) ? 'ON' : 'OFF';
		$new_input['enabledGoogleGA4'] = isset( $_POST['enabledGoogleGA4'] ) ? 'ON' : 'OFF';

		update_option( 'nta_wa_analytics', $new_input );
		wp_send_json_success();
	}


/** Function runRestore() called by wp_ajax hooks: {'njt_wa_restore'} **/
/** No params detected :-/ **/


/** Function ajax_get_account() called by wp_ajax hooks: {'njt_wa_get_account'} **/
/** Parameters found in function ajax_get_account(): {"post": ["id"]} **/
function ajax_get_account() {
		check_ajax_referer( 'njt-wa-gutenberg', 'nonce', true );
		$id        = sanitize_text_field( $_POST['id'] );
		$metaInfo  = get_post_meta( $id, 'nta_wa_account_info', true );
		$metaStyle = get_post_meta( $id, 'nta_wa_button_styles', true );
		wp_send_json(
			array(
				'imageUrl'    => get_the_post_thumbnail_url( $id ),
				'buttonTitle' => $buttonName,
				'metaInfo'    => $metaInfo,
				'metaStyle'   => $metaStyle,
			)
		);
	}


/** Function ajax_upgrade_plugin() called by wp_ajax hooks: {'yay_recommended_upgrade_plugin'} **/
/** Parameters found in function ajax_upgrade_plugin(): {"post": ["plugin", "nonce", "type"]} **/
function ajax_upgrade_plugin() : void
    {
        try {
            if (!\current_user_can('install_plugins')) {
                wp_send_json_error(['mess' => \__('Permission denied', 'yaycommerce')]);
            }
            require_once \ABSPATH . 'wp-admin/includes/plugin-install.php';
            require_once \ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
            require_once \ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php';
            require_once \ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';
            if (!isset($_POST['plugin'])) {
                wp_send_json_error(['mess' => 'Missing plugin']);
            }
            $nonce = isset($_POST['nonce']) ? \sanitize_text_field($_POST['nonce']) : '';
            if (!wp_verify_nonce($nonce, 'yay_recommended_nonce')) {
                wp_send_json_error(['mess' => \__('Nonce is invalid', 'yaycommerce')]);
            }
            $plugin = \sanitize_text_field($_POST['plugin']);
            $type = isset($_POST['type']) ? \sanitize_text_field($_POST['type']) : 'install';
            $skin = new \WP_Ajax_Upgrader_Skin();
            $upgrader = new \Plugin_Upgrader($skin);
            if ('install' === $type) {
                $result = $upgrader->install($plugin);
                if (is_wp_error($result)) {
                    wp_send_json_error(['mess' => $result->get_error_message()]);
                }
                $args = ['slug' => $upgrader->result['destination_name'], 'fields' => ['short_description' => \true, 'icons' => \true, 'banners' => \false, 'reviews' => \false, 'sections' => \false]];
                $plugin_data = plugins_api('plugin_information', $args);
                if ($plugin_data && !is_wp_error($plugin_data)) {
                    $install_status = install_plugin_install_status($plugin_data);
                    $active_plugin = activate_plugin($install_status['file']);
                    if (is_wp_error($active_plugin)) {
                        wp_send_json_error(['mess' => $active_plugin->get_error_message()]);
                    }
                    wp_send_json_success(['mess' => \__('Install success', 'yaycommerce')]);
                } else {
                    wp_send_json_error(['mess' => 'Error']);
                }
            } else {
                $is_active = is_plugin_active($plugin);
                $result = $upgrader->upgrade($plugin);
                if (is_wp_error($result)) {
                    wp_send_json_error(['mess' => $result->get_error_message()]);
                }
                activate_plugin($plugin);
                wp_send_json_success(['mess' => \__('Update success', 'yaycommerce'), 'active' => $is_active]);
            }
        } catch (\Exception $ex) {
            wp_send_json_error(['mess' => \__('Error exception.', 'yaycommerce'), ['error' => $ex]]);
        } catch (\Error $ex) {
            wp_send_json_error(['mess' => \__('Error.', 'yaycommerce'), ['error' => $ex]]);
        }
    }


/** Function set_account_position() called by wp_ajax hooks: {'njt_wa_set_account_position'} **/
/** Parameters found in function set_account_position(): {"post": ["positions", "type"]} **/
function set_account_position() {
		check_ajax_referer( 'njt-wa-nonce', 'nonce', true );

		$positions = Helper::sanitize_array( $_POST['positions'] );
		$type      = sanitize_text_field( $_POST['type'] );

		foreach ( $positions as $index => $id ) {
			update_post_meta( $id, "nta_wa_{$type}", $index );
		}

		wp_send_json_success();
	}


/** Function load_accounts_ajax() called by wp_ajax hooks: {'njt_wa_load_accounts_ajax'} **/
/** No params detected :-/ **/


/** Function save_url_setting() called by wp_ajax hooks: {'njt_wa_save_url_setting'} **/
/** Parameters found in function save_url_setting(): {"post": ["openInNewTab", "onDesktop", "onMobile"]} **/
function save_url_setting() {
		check_ajax_referer( 'njt-wa-nonce', 'nonce', true );

		$new_input = array();

		$new_input                 = Fields::getURLSettings();
		$new_input['openInNewTab'] = isset( $_POST['openInNewTab'] ) ? 'ON' : 'OFF';
		$new_input['onDesktop']    = sanitize_text_field( $_POST['onDesktop'] );
		$new_input['onMobile']     = sanitize_text_field( $_POST['onMobile'] );

		update_option( 'nta_wa_url', $new_input );
		wp_send_json_success();
	}


/** Function ajax_install_plugin() called by wp_ajax hooks: {'yay_media_{$this->pluginPrefix}_cross_install', 'yay_notification_{$this->pluginPrefix}_cross_install', 'njt_{$this->pluginPrefix}_cross_install', 'yay_dashboard_{$this->pluginPrefix}_cross_install'} **/
/** No params detected :-/ **/


/** Function save_review() called by wp_ajax hooks: {'{$this->pluginPrefix}_save_review'} **/
/** Parameters found in function save_review(): {"post": ["field"]} **/
function save_review() {
			check_ajax_referer( 'njt_wa_review_nonce', 'nonce', true );

			$field = isset( $_POST['field'] ) ? sanitize_text_field( $_POST['field'] ) : '';

			if ( 'later' === $field ) {
				$this->need_update_option( 3 );
			} elseif ( 'alreadyDid' === $field || 'rateNow' === $field ) {
				update_option( "{$this->pluginPrefix}_review", 1 );
			}
			wp_send_json_success();
		}


/** Function save_display_setting() called by wp_ajax hooks: {'njt_wa_save_display_setting'} **/
/** Parameters found in function save_display_setting(): {"post": ["excludePages", "includePages", "includePosts", "displayCondition", "showOnDesktop", "showOnMobile", "time_symbols"]} **/
function save_display_setting() {
		check_ajax_referer( 'njt-wa-nonce', 'nonce', true );
		$new_input = array();

		$excludePages = Helper::sanitize_array( $_POST['excludePages'] );
		$includePages = Helper::sanitize_array( $_POST['includePages'] );
		$includePosts = Helper::sanitize_array( $_POST['includePosts'] );

		$new_input                     = Fields::getWidgetDisplay();
		$new_input['displayCondition'] = sanitize_text_field( $_POST['displayCondition'] );
		$new_input['excludePages']     = empty( $excludePages ) ? array() : $excludePages;
		$new_input['includePages']     = empty( $includePages ) ? array() : $includePages;
		$new_input['includePosts']     = empty( $includePosts ) ? array() : $includePosts;
		$new_input['showOnDesktop']    = isset( $_POST['showOnDesktop'] ) ? 'ON' : 'OFF';
		$new_input['showOnMobile']     = isset( $_POST['showOnMobile'] ) ? 'ON' : 'OFF';

		$time_symbols              = Helper::sanitize_array( $_POST['time_symbols'] );
		$new_input['time_symbols'] = wp_unslash( $time_symbols['hourSymbol'] ) . ':' . wp_unslash( $time_symbols['minSymbol'] );

		update_option( 'nta_wa_widget_display', $new_input );
		wp_send_json_success();
	}


/** Function save_woocommerce_setting() called by wp_ajax hooks: {'njt_wa_save_woocommerce_setting'} **/
/** Parameters found in function save_woocommerce_setting(): {"post": ["position", "isShow"]} **/
function save_woocommerce_setting() {
		check_ajax_referer( 'njt-wa-nonce', 'nonce', true );

		$new_input = array();

		$new_input             = Fields::getWoocommerceSetting();
		$new_input['position'] = sanitize_text_field( $_POST['position'] );
		$new_input['isShow']   = isset( $_POST['isShow'] ) ? 'ON' : 'OFF';

		update_option( 'nta_wa_woocommerce', $new_input );
		wp_send_json_success();
	}


/** Function ajax_hide_cross() called by wp_ajax hooks: {'njt_{$this->pluginPrefix}_cross_hide', 'yay_media_{$this->pluginPrefix}_cross_hide', 'yay_notification_{$this->pluginPrefix}_cross_hide'} **/
/** No params detected :-/ **/


/** Function set_account_status() called by wp_ajax hooks: {'njt_wa_set_account_status'} **/
/** Parameters found in function set_account_status(): {"post": ["accountId", "type", "status"]} **/
function set_account_status() {
		check_ajax_referer( 'njt-wa-nonce', 'nonce', true );
		$id     = sanitize_text_field( $_POST['accountId'] );
		$type   = sanitize_text_field( $_POST['type'] );
		$status = sanitize_text_field( $_POST['status'] );

		$wg_position = get_post_meta( $id, 'nta_wa_widget_position', true );
		$wc_position = get_post_meta( $id, 'nta_wa_wc_position', true );

		if ( '' === $wg_position ) {
			update_post_meta( $id, 'nta_wa_widget_position', 0 );
		}

		if ( '' === $wc_position ) {
			update_post_meta( $id, 'nta_wa_wc_position', 0 );
		}

		update_post_meta( $id, "nta_wa_{$type}", $status );
		wp_send_json_success();
	}


/** Function ajax_get_plugin_data() called by wp_ajax hooks: {'yay_recommended_get_plugin_data'} **/
/** Parameters found in function ajax_get_plugin_data(): {"post": ["tab", "nonce"]} **/
function ajax_get_plugin_data() : void
    {
        try {
            if (!\current_user_can('install_plugins')) {
                wp_send_json_error(['mess' => \__('Permission denied', 'yaycommerce')]);
            }
            if (!isset($_POST['tab'])) {
                wp_send_json_error(['mess' => 'Missing tab']);
            }
            $nonce = isset($_POST['nonce']) ? \sanitize_text_field($_POST['nonce']) : '';
            if (!wp_verify_nonce($nonce, 'yay_recommended_nonce')) {
                wp_send_json_error(['mess' => \__('Nonce is invalid', 'yaycommerce')]);
            }
            require_once \ABSPATH . 'wp-admin/includes/plugin-install.php';
            $tab = \sanitize_text_field($_POST['tab']);
            $recommended_data = \apply_filters('yay_recommended_plugins_excluded', self::get_other_plugins());
            $recommended_plugins = [];
            foreach ($recommended_data as $key => $plugin) {
                if (\in_array($tab, $plugin['type'], \true) || 'all' === $tab) {
                    $recommended_plugins[$key] = $plugin;
                }
            }
            \ob_start();
            include __DIR__ . '/../../views/recommended-plugins-page.php';
            $html = \ob_get_clean();
            wp_send_json_success(['mess' => \__('Get data success', 'yaycommerce'), 'html' => $html]);
        } catch (\Exception $ex) {
            wp_send_json_error(['mess' => \__('Error exception.', 'yaycommerce'), ['error' => $ex]]);
        } catch (\Error $ex) {
            wp_send_json_error(['mess' => \__('Error.', 'yaycommerce'), ['error' => $ex]]);
        }
    }


/** Function ajax_activate_plugin() called by wp_ajax hooks: {'yay_recommended_activate_plugin'} **/
/** Parameters found in function ajax_activate_plugin(): {"post": ["file", "nonce"]} **/
function ajax_activate_plugin() : void
    {
        try {
            if (!\current_user_can('activate_plugins')) {
                wp_send_json_error(['mess' => \__('Permission denied', 'yaycommerce')]);
            }
            if (!isset($_POST['file'])) {
                wp_send_json_error(['mess' => 'Missing file']);
            }
            $nonce = isset($_POST['nonce']) ? \sanitize_text_field($_POST['nonce']) : '';
            if (!wp_verify_nonce($nonce, 'yay_recommended_nonce')) {
                wp_send_json_error(['mess' => \__('Nonce is invalid', 'yaycommerce')]);
            }
            $file = \sanitize_text_field($_POST['file']);
            $result = activate_plugin($file);
            if (is_wp_error($result)) {
                wp_send_json_error(['mess' => $result->get_error_message()]);
            }
            wp_send_json_success(['mess' => \__('Activate success', 'yaycommerce')]);
        } catch (\Exception $ex) {
            wp_send_json_error(['mess' => \__('Error exception.', 'yaycommerce'), ['error' => $ex]]);
        } catch (\Error $ex) {
            wp_send_json_error(['mess' => \__('Error.', 'yaycommerce'), ['error' => $ex]]);
        }
    }


/** Function save_user_role_setting() called by wp_ajax hooks: {'njt_wa_save_user_role_setting'} **/
/** Parameters found in function save_user_role_setting(): {"post": ["userRole"]} **/
function save_user_role_setting() {
		check_ajax_referer( 'njt-wa-nonce', 'nonce', true );

		$new_input = array( 'administrator' => true );

		if ( isset( $_POST['userRole'] ) ) {
			$userRole = Helper::sanitize_array( $_POST['userRole'] );
			foreach ( $userRole as $role ) {
				$new_input[ $role ] = true;
				// Add capability to user role
				$role = get_role( $role );
				$role->add_cap( 'nta_wa_manage' );
			}
		}
		$old_settings     = Fields::getUserRoleSettings();
		$roles_remove_cap = array_diff_key( $old_settings, $new_input );
		foreach ( $roles_remove_cap as $role => $value ) {
			$role = get_role( $role );
			$role->remove_cap( 'nta_wa_manage' );
		}
		update_option( 'nta_wa_user_role', $new_input );

		wp_send_json_success();
	}


/** Function save_design_setting() called by wp_ajax hooks: {'njt_wa_save_design_setting'} **/
/** Parameters found in function save_design_setting(): {"post": ["title", "textColor", "titleSize", "accountNameSize", "descriptionTextSize", "regularTextSize", "backgroundColor", "description", "responseText", "scrollHeight", "isShowScroll", "isShowResponseText", "btnLabel", "btnPosition", "btnLabelWidth", "btnLeftDistance", "btnRightDistance", "btnBottomDistance", "isShowBtnLabel", "isShowGDPR", "gdprContent"]} **/
function save_design_setting() {
		check_ajax_referer( 'njt-wa-nonce', 'nonce', true );

		$new_input = array();

		$new_input                        = Fields::getWidgetStyles();
		$new_input['title']               = sanitize_text_field( wp_unslash( $_POST['title'] ) );
		$new_input['textColor']           = sanitize_hex_color( $_POST['textColor'] );
		$new_input['titleSize']           = sanitize_text_field( $_POST['titleSize'] );
		$new_input['accountNameSize']     = sanitize_text_field( $_POST['accountNameSize'] );
		$new_input['descriptionTextSize'] = sanitize_text_field( $_POST['descriptionTextSize'] );
		$new_input['regularTextSize']     = sanitize_text_field( $_POST['regularTextSize'] );
		$new_input['backgroundColor']     = sanitize_hex_color( $_POST['backgroundColor'] );
		$new_input['description']         = wp_kses_post( wp_unslash( $_POST['description'] ) );
		$new_input['responseText']        = wp_kses_post( wp_unslash( $_POST['responseText'] ) );
		$new_input['scrollHeight']        = sanitize_text_field( $_POST['scrollHeight'] );
		$new_input['isShowScroll']        = isset( $_POST['isShowScroll'] ) ? 'ON' : 'OFF';
		$new_input['isShowResponseText']  = isset( $_POST['isShowResponseText'] ) ? 'ON' : 'OFF';
		$new_input['btnLabel']            = wp_kses_post( wp_unslash( $_POST['btnLabel'] ) ); // It can be an html tag
		$new_input['btnPosition']         = sanitize_text_field( $_POST['btnPosition'] );
		$new_input['btnLabelWidth']       = sanitize_text_field( $_POST['btnLabelWidth'] );
		$new_input['btnLeftDistance']     = sanitize_text_field( $_POST['btnLeftDistance'] );
		$new_input['btnRightDistance']    = sanitize_text_field( $_POST['btnRightDistance'] );
		$new_input['btnBottomDistance']   = sanitize_text_field( $_POST['btnBottomDistance'] );
		$new_input['isShowBtnLabel']      = isset( $_POST['isShowBtnLabel'] ) ? 'ON' : 'OFF';

		$new_input['isShowGDPR']  = isset( $_POST['isShowGDPR'] ) ? 'ON' : 'OFF';
		$new_input['gdprContent'] = wp_kses_post( wp_unslash( $_POST['gdprContent'] ) );

		update_option( 'nta_wa_widget_styles', $new_input );
		wp_send_json_success();
	}


