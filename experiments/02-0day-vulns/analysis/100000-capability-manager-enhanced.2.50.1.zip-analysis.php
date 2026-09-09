<?php
/***
*
*Found actions: 12
*Found functions:9
*Extracted functions:9
*Total parameter names extracted: 8
*Overview: {'ajaxUpdateAdminFeatureSettings': {'ppc_update_admin_feature_settings'}, 'handleRolesAjax': {'pp-roles-delete-role', 'pp-roles-hide-role', 'pp-roles-unhide-role', 'pp-roles-add-role'}, 'frontendElementNewEntryAjaxHandler': {'ppc_submit_frontend_element_by_ajax'}, 'profileElementUpdateAjaxHandler': {'ppc_update_profile_features_element_by_ajax'}, 'saveDashboardFeature': {'save_dashboard_feature_by_ajax'}, 'frontendFeaturesDeleteItemAjaxHandler': {'ppc_delete_frontend_feature_item_by_ajax'}, 'setProfileFeaturesRoleAccess': {'ppc_set_profile_features_role'}, 'adminNoticeAjaxHandler': {'ppc_admin_notice_action'}, 'searchTestUsers': {'ppc_search_test_user_by_ajax'}}
*
***/

/** Function ajaxUpdateAdminFeatureSettings() called by wp_ajax hooks: {'ppc_update_admin_feature_settings'} **/
/** Parameters found in function ajaxUpdateAdminFeatureSettings(): {"post": ["nonce", "hide_submenu"]} **/
function ajaxUpdateAdminFeatureSettings() {

        $response['status']  = 'error';
        $response['message'] = __('An error occured!', 'capability-manager-enhanced');
        $response['content'] = '';

        // Verify nonce and capabilities
        if (empty($_POST['nonce']) || !wp_verify_nonce(sanitize_key($_POST['nonce']), 'pp-capabilities-admin-features')) {
            $response['message'] =  __('Security check failed', 'capability-manager-enhanced');
        } elseif (!current_user_can('manage_capabilities_admin_features')) {
            $response['message'] =  __('Permission denied', 'capability-manager-enhanced');
        } else {
            $hide_submenu      = !empty($_POST['hide_submenu']) ? (int)($_POST['hide_submenu']) : 0;

            $admin_feature_settings = (array) get_option('ppc_admin_features_settings', []);
            $admin_feature_settings['hide_submenu'] = $hide_submenu;

            update_option('ppc_admin_features_settings', $admin_feature_settings);

            $response['status']  = 'success';
            $response['message'] = __('Settings updated successfully.', 'capability-manager-enhanced');
        }

        wp_send_json($response);
    }


/** Function handleRolesAjax() called by wp_ajax hooks: {'pp-roles-delete-role', 'pp-roles-hide-role', 'pp-roles-unhide-role', 'pp-roles-add-role'} **/
/** No params detected :-/ **/


/** Function frontendElementNewEntryAjaxHandler() called by wp_ajax hooks: {'ppc_submit_frontend_element_by_ajax'} **/
/** Parameters found in function frontendElementNewEntryAjaxHandler(): {"post": ["custom_label", "custom_element_selector", "custom_element_styles", "custom_element_bodyclass", "element_pages", "element_post_types", "security", "item_id"]} **/
function frontendElementNewEntryAjaxHandler()
    {
        $response['status']  = 'error';
        $response['message'] = esc_html__('An error occured!', 'capability-manager-enhanced');
        $response['content'] = '';

        $custom_label   = isset($_POST['custom_label']) ? sanitize_text_field($_POST['custom_label']) : '';
        $custom_element_selector = isset($_POST['custom_element_selector']) ? stripslashes_deep(sanitize_textarea_field($_POST['custom_element_selector'])) : '';
        $custom_element_styles = isset($_POST['custom_element_styles']) ? stripslashes_deep(sanitize_textarea_field($_POST['custom_element_styles'])) : '';
        $custom_element_bodyclass = isset($_POST['custom_element_bodyclass']) ? sanitize_textarea_field($_POST['custom_element_bodyclass']) : '';
        $element_pages  = (isset($_POST['element_pages']) && is_array($_POST['element_pages'])) ? array_map('sanitize_text_field', $_POST['element_pages']) : [];
        $element_post_types  = (isset($_POST['element_post_types']) && is_array($_POST['element_post_types'])) ? array_map('sanitize_text_field', $_POST['element_post_types']) : [];
        $security       = isset($_POST['security']) ? sanitize_key($_POST['security']) : '';
        $item_id        = isset($_POST['item_id']) ? sanitize_key($_POST['item_id']) : '';

        if ((!is_multisite() || !is_super_admin()) && !current_user_can('administrator') && !current_user_can('manage_capabilities_frontend_features')) {
            $response['message'] = esc_html__('You do not have permission to manage frontend features.', 'capability-manager-enhanced');
        } elseif (!wp_verify_nonce($security, 'frontend-element-nonce')) {
            $response['message'] = esc_html__('Invalid action. Reload this page and try again.', 'capability-manager-enhanced');
        } elseif (empty(trim($custom_label)) || (empty(trim($custom_element_selector)) && empty(trim($custom_element_styles)) && empty(trim($custom_element_bodyclass)))) {
            $response['message'] = esc_html__('All fields are required.', 'capability-manager-enhanced');
        } elseif (empty($element_pages) && empty($element_post_types)) {
            $response['message'] = esc_html__('Load on page types is required.', 'capability-manager-enhanced');
        } else {
            $element_id       = (!empty($item_id)) ? $item_id : uniqid(true);
            $data             = PP_Capabilities_Frontend_Features_Data::getFrontendElements();

            $custom_element = [
                'selector'  => $custom_element_selector,
                'styles'    => $custom_element_styles,
                'bodyclass' => $custom_element_bodyclass,
            ];

            $data[$element_id] = [
                'element_id'    => $element_id,
                'label'         => $custom_label,
                'elements'      => $custom_element,
                'pages'         => $element_pages,
                'post_types'    => $element_post_types
            ];

            update_option('capsman_frontend_features_elements', $data);

            $function_args = [
                'disabled_frontend_items' => [$element_id],
                'section_array'           => $data[$element_id],
                'section_slug'            => 'frontendelements',
                'section_id'              => $element_id,
                'sn'                      => time(),
                'additional_class'        => 'ppc-menu-overlay-item'
            ];

            $response['content'] = PP_Capabilities_Frontend_Features_UI::do_pp_capabilities_frontend_features_frontendelements_tr($function_args, false);
            if ($item_id) {
                $response['message'] = esc_html__('Frontend element item updated. Save changes to enable for role.', 'capability-manager-enhanced');
            } else {
                $response['message'] = esc_html__('New frontend element added. Save changes to enable for role.', 'capability-manager-enhanced');
            }
            $response['status']  = 'success';
        }

        wp_send_json($response);
    }


/** Function profileElementUpdateAjaxHandler() called by wp_ajax hooks: {'ppc_update_profile_features_element_by_ajax'} **/
/** Parameters found in function profileElementUpdateAjaxHandler(): {"post": ["security", "page_elements"]} **/
function profileElementUpdateAjaxHandler()
    {
        $response['status']  = 'error';
        $response['message'] = __('An error occured!', 'capability-manager-enhanced');
        $response['content'] = '';
        $redirect_url = admin_url('admin.php?page=pp-capabilities-profile-features');

        $security       = isset($_POST['security']) ? sanitize_key($_POST['security']) : false;
        $page_elements  = isset($_POST['page_elements']) ? $_POST['page_elements'] : [];// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

        if (!$security || !wp_verify_nonce($security, 'ppc-profile-edit-action') || !pp_capabilities_feature_enabled('profile-features')) {
            $response['redirect'] = $redirect_url;
        } else {
            // Check if the current role is enabled for profile features editing
            $profile_feature_role = get_option("capsman_profile_features_elements_testing_role", 'subscriber');
            $temporary_role = self::getTemporaryProfileFeaturesRole();

            if (empty($temporary_role) || $temporary_role !== $profile_feature_role) {
                $response['status']  = 'error';
                $response['message'] = __('This role is not enabled for profile feature editing.', 'capability-manager-enhanced');
                $response['redirect'] = $redirect_url;
                wp_send_json($response);
            }

            $response['status']  = 'success';
            $profile_features_elements = self::elementsLayout();
            $profile_element_updated = (array) get_option("capsman_profile_features_updated", []);
            $profile_element_updated[$profile_feature_role] = 1;
            $role_profile_features_elements = [];
            foreach ($page_elements as $key => $data) {
                $new_element_key  = sanitize_key($key);
                $new_element_data = [
                    'label'        => sanitize_text_field($data['label']),
                    'elements'     => sanitize_text_field($data['elements']),
                    'element_type' => sanitize_key($data['element_type'])
                ];
                $role_profile_features_elements[$new_element_key] = $new_element_data;
            }
            $profile_features_elements[$profile_feature_role] = $role_profile_features_elements;
            update_option('capsman_profile_features_elements', $profile_features_elements, false);
            update_option('capsman_profile_features_updated', $profile_element_updated, false);
            delete_option('capsman_profile_features_elements_testing_role');
            delete_option('capsman_profile_features_role');
            $cookie_name = defined('PPC_TEST_USER_COOKIE_NAME') ? PPC_TEST_USER_COOKIE_NAME : 'ppc_test_user_tester_' . COOKIEHASH;
            if (isset($_COOKIE[$cookie_name]) && !empty($_COOKIE[$cookie_name])) {
                $user = wp_get_current_user();
                $redirect_url = add_query_arg(
                    [
                        'ppc_test_user'   => base64_encode(get_current_user_id()),
                        'profile_feature_action' => 1,
                        'ppc_return_back' => 1,
                        '_wpnonce'        => wp_create_nonce('ppc-test-user')
                    ],
                    home_url()
                );
                $response['redirect'] = $redirect_url;
            } else {
                $response['redirect'] = $redirect_url;
            }
        }
        wp_send_json($response);
    }


/** Function saveDashboardFeature() called by wp_ajax hooks: {'save_dashboard_feature_by_ajax'} **/
/** Parameters found in function saveDashboardFeature(): {"post": ["nonce", "feature", "new_state", "network_sync"]} **/
function saveDashboardFeature()
    {
        if ((!is_multisite() || !is_super_admin()) && !current_user_can('administrator') && !current_user_can('manage_capabilities_dashboard')) {
            wp_send_json( __('No permission!', 'capability-manager-enhanced'), 403 );
            return false;
        }

        if (
            ! wp_verify_nonce(
                sanitize_key( $_POST['nonce'] ),
                'pp-capabilities-dashboard-nonce'
            )
        ) {
            wp_send_json( __('Invalid nonce token!', 'capability-manager-enhanced'), 400 );
        }

        if( empty( $_POST['feature'] ) || ! $_POST['feature'] ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
            wp_send_json( __('Error: wrong data', 'capability-manager-enhanced'), 400 );
            return false;
        }

        $feature = sanitize_key(wp_unslash($_POST['feature']));
        $dashboard_options = pp_capabilities_dashboard_options();

        if (!isset($dashboard_options[$feature])) {
            wp_send_json(__('Error: wrong data', 'capability-manager-enhanced'), 400);
            return false;
        }

        $capsman_dashboard_features_status = !empty(get_option('capsman_dashboard_features_status')) ? (array)get_option('capsman_dashboard_features_status') : [];

        $feature_status = !empty($_POST['new_state']) ? 'on' : 'off';
        $capsman_dashboard_features_status[$feature]['status'] = $feature_status;
        update_option('capsman_dashboard_features_status', $capsman_dashboard_features_status, false);
        do_action('pp_capabilities_dashboard_feature_updated', $feature, $feature_status);

        $network_sync = !empty($_POST['network_sync'])
            && is_multisite()
            && is_super_admin()
            && is_main_site();

        if ($network_sync && function_exists('pp_capabilities_queue_dashboard_feature_sync')) {
            pp_capabilities_queue_dashboard_feature_sync($feature, $feature_status);

            wp_send_json([
                'message' => __('Changes saved. Feature status synchronization has been queued for all network sites.', 'capability-manager-enhanced'),
                'network_sync' => true,
            ], 200);
        }

        wp_send_json([
            'message' => __('Changes saved!', 'capability-manager-enhanced'),
            'network_sync' => false,
        ], 200);
    }


/** Function frontendFeaturesDeleteItemAjaxHandler() called by wp_ajax hooks: {'ppc_delete_frontend_feature_item_by_ajax'} **/
/** Parameters found in function frontendFeaturesDeleteItemAjaxHandler(): {"post": ["item_id", "security"]} **/
function frontendFeaturesDeleteItemAjaxHandler()
    {
        $response = [];
        $response['status']  = 'error';
        $response['message'] = esc_html__('An error occured!', 'capability-manager-enhanced');
        $response['content'] = '';

        $item_id      = isset($_POST['item_id']) ? sanitize_key($_POST['item_id']) : '';
        $security     = isset($_POST['security']) ? sanitize_key($_POST['security']) : '';

        if ((!is_multisite() || !is_super_admin()) && !current_user_can('administrator') && !current_user_can('manage_capabilities_frontend_features')) {
            $response['message'] = esc_html__('You do not have permission to manage frontend features.', 'capability-manager-enhanced');
        } elseif (!wp_verify_nonce($security, 'frontend-delete' . $item_id .'-nonce')) {
            $response['message'] = esc_html__('Invalid action. Reload this page and try again.', 'capability-manager-enhanced');
        } elseif (empty(trim($item_id))) {
            $response['message'] = esc_html__('Invalid request!.', 'capability-manager-enhanced');
        } else {
            $item_deleted = false;

            $data       = PP_Capabilities_Frontend_Features_Data::getFrontendElements();
            $option_key = 'capsman_frontend_features_elements';

            if (is_array($data) && array_key_exists($item_id, $data)) {
                unset($data[$item_id]);
                update_option($option_key, $data);
                $item_deleted = true;
            }
            
            if ($item_deleted) {
                $response['status']  = 'success';
                $response['message'] = esc_html__('Selected item deleted successfully', 'capability-manager-enhanced');
            }
        }

        wp_send_json($response);
    }


/** Function setProfileFeaturesRoleAccess() called by wp_ajax hooks: {'ppc_set_profile_features_role'} **/
/** Parameters found in function setProfileFeaturesRoleAccess(): {"post": ["security", "role", "enabled"]} **/
function setProfileFeaturesRoleAccess()
    {
        $response['status']  = 'error';
        $response['message'] = __('An error occurred!', 'capability-manager-enhanced');

        $security = isset($_POST['security']) ? sanitize_key($_POST['security']) : false;
        $role = isset($_POST['role']) ? sanitize_key($_POST['role']) : '';
        $enabled = isset($_POST['enabled']) ? (int) $_POST['enabled'] : 0;

        if (!$security || !wp_verify_nonce($security, 'pp-capabilities-profile-features') || !pp_capabilities_feature_enabled('profile-features') || !current_user_can('manage_capabilities_profile_features')) {
            wp_send_json($response);
        }

        if (!$enabled) {
            delete_option('capsman_profile_features_role');
            $response['status']  = 'success';
            $response['message'] = __('Profile features access disabled.', 'capability-manager-enhanced');
            wp_send_json($response);
        }

        if (empty($role) || !get_role($role)) {
            $response['message'] = __('Invalid role provided.', 'capability-manager-enhanced');
            wp_send_json($response);
        }

        $expires = (int) current_time('timestamp') + (5 * MINUTE_IN_SECONDS);
        update_option('capsman_profile_features_role', ['role' => $role, 'expires' => $expires], false);

        $response['status']  = 'success';
        $response['message'] = __('Profile features access enabled for this role.', 'capability-manager-enhanced');

        wp_send_json($response);
    }


/** Function adminNoticeAjaxHandler() called by wp_ajax hooks: {'ppc_admin_notice_action'} **/
/** Parameters found in function adminNoticeAjaxHandler(): {"post": ["nonce", "action_type", "action_option", "notice_id"]} **/
function adminNoticeAjaxHandler()
        {
            $response['status']  = 'error';
            $response['message'] = esc_html__('An error occured!', 'capability-manager-enhanced');
            $response['content'] = '';

            $nonce   = isset($_POST['nonce']) ? sanitize_key($_POST['nonce']) : '';
            $action_type = isset($_POST['action_type']) ? sanitize_text_field($_POST['action_type']) : '';
            $action_option = isset($_POST['action_option']) ? sanitize_text_field($_POST['action_option']) : '';
            $notice_id = isset($_POST['notice_id']) ? sanitize_text_field($_POST['notice_id']) : '';

            if (!$this->canSeeAdminToolbar()) {
                $response['message'] = esc_html__('You do not have permission to manage admin notices.', 'capability-manager-enhanced');
            } elseif (!wp_verify_nonce($nonce, 'ppc-admin-notices-action')) {
                $response['message'] = esc_html__('Invalid action. Reload this page and try again.', 'capability-manager-enhanced');
            } elseif (empty($action_type) || empty($action_option) && empty($notice_id)) {
                $response['message'] = esc_html__('Invalid form.', 'capability-manager-enhanced');
            } else {

                $admin_notice_data = (array) get_option('cme_admin_notice_data', []);

                // remove current notice from both whitelist and blacklist if present
                if (!empty($admin_notice_data['whitelist_notices'])) {
                    $admin_notice_data['whitelist_notices'] = array_values(array_diff($admin_notice_data['whitelist_notices'], [$notice_id]));
                }
                if (!empty($admin_notice_data['blacklist_notices'])) {
                    $admin_notice_data['blacklist_notices'] = array_values(array_diff($admin_notice_data['blacklist_notices'], [$notice_id]));
                }

                // add notice to whitelist/blacklist if action is default and not undo action
                if ($action_option == 'default') {
                    if ($action_type == 'whitelist') {
                        $admin_notice_data['whitelist_notices'][] = $notice_id;
                    } else {
                        $admin_notice_data['blacklist_notices'][] = $notice_id;
                    }
                }

                update_option('cme_admin_notice_data', $admin_notice_data);

                $response['message'] = esc_html__('Admin notice status updated successfully.', 'capability-manager-enhanced');
                $response['status']  = 'success';
            }

            wp_send_json($response);
        }


/** Function searchTestUsers() called by wp_ajax hooks: {'ppc_search_test_user_by_ajax'} **/
/** Parameters found in function searchTestUsers(): {"post": ["security", "search_text"]} **/
function searchTestUsers() {

        $response['status']  = 'error';
        $response['message'] = __('No results found.', 'capability-manager-enhanced');
        $response['content'] = '';

        $security       = isset($_POST['security']) ? sanitize_key($_POST['security']) : false;
        $search_text    = isset($_POST['search_text']) ? sanitize_key($_POST['search_text']) : '';
        if (!$security
            || !wp_verify_nonce($security, 'ppc-test-user-admin-bar-action')
            || !current_user_can('manage_capabilities_user_testing')
        ) {
            $response['message'] = __('Permission denied.', 'capability-manager-enhanced');
        } else {
            $response['status']  = 'success';
            $excluded_roles = (array) get_option('cme_test_user_excluded_roles', []);

            $roles = wp_roles()->roles;
            $editable = function_exists('get_editable_roles') ?
                            array_keys(get_editable_roles()) :
                            array_keys(apply_filters('editable_roles', $roles));

            $included_roles = [];
            foreach ($editable as $role_name) {
                if (!in_array($role_name, $excluded_roles)) {
                    $included_roles[] = $role_name;
                }
            }

            if (!empty($included_roles)) {
                $user_args = ['number' => 10];

                if (!empty(trim($search_text))) {
                    $user_args['search'] = '*' . trim($search_text) . '*';
                }

                if (!empty(array_filter($included_roles))) {
                    $user_args['role__in'] = $included_roles;

                    $user_query = new WP_User_Query($user_args);

                    if ( ! empty( $user_query->results ) ) {
                        $role_users = [];
                        $user_lists = [];
                        foreach ( $user_query->results as $user ) {
                            if (PP_Capabilities_Test_User::canTestUser($user)) {
                                foreach ($user->roles as $role) {
                                    if (!in_array($user, $user_lists)) {
                                        if (isset($role_users[$role])) {
                                            $role_users[$role] = array_merge($role_users[$role], [$user]);
                                            $user_lists[] = $user;
                                        } else {
                                            $role_users[$role] = [$user];
                                            $user_lists[] = $user;
                                        }
                                    }
                                }
                            }
                        }

                        if (!empty($role_users)) {
                            $response_content = '';
                            foreach ($role_users as $role_slug => $user_lists) {
                                $role_name = !empty($roles[$role_slug]['name']) ? translate_user_role($roles[$role_slug]['name']) : $role_slug;
                                $response_content .= '<h2>'. $role_name .'</h2>';
                                foreach ($user_lists as $user) {
                                    $link = add_query_arg(
                                        [
                                            'ppc_test_user' => base64_encode($user->ID),
                                            '_wpnonce'      => wp_create_nonce('ppc-test-user')
                                        ],
                                        admin_url('users.php')
                                    );
                                    $response_content .= '
                                        <p class="result">
                                            <a href="' . esc_url($link) . '">' . esc_html($user->display_name) . '</a>
                                        </p>
                                    ';
                                }
                            }
                            $response['content'] = $response_content;
                        }
                    }
                }
            }
        }

        wp_send_json($response);
    }


