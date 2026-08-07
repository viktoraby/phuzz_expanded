<?php
/***
*
*Found actions: 16
*Found functions:14
*Extracted functions:14
*Total parameter names extracted: 5
*Overview: {'get_chatway_status': {'get_chatway_status'}, 'update_chaty_view': {'update_chaty_view', 'nopriv_update_chaty_view'}, 'get_chaty_settings': {'get_chaty_settings'}, 'chaty_plugin_deactivate': {'chaty_plugin_deactivate'}, 'hide_chaty_cta': {'hide_chaty_cta'}, 'update_popup_status': {'chaty_update_popup_status'}, 'chaty_front_form_save_data': {'chaty_front_form_save_data', 'nopriv_chaty_front_form_save_data'}, 'remove_chaty_widget': {'remove_chaty_widget'}, 'update_status': {'chaty_update_status'}, 'change_chaty_widget_status': {'change_chaty_widget_status'}, 'rename_chaty_widget': {'rename_chaty_widget'}, 'choose_social_handler': {'choose_social'}, 'update_channel_setting': {'update_channel_setting'}, 'wcp_admin_send_message_to_owner': {'wcp_admin_send_message_to_owner'}}
*
***/

/** Function get_chatway_status() called by wp_ajax hooks: {'get_chatway_status'} **/
/** No params detected :-/ **/


/** Function update_chaty_view() called by wp_ajax hooks: {'update_chaty_view', 'nopriv_update_chaty_view'} **/
/** Parameters found in function update_chaty_view(): {"post": ["token"]} **/
function update_chaty_view() {
        if(isset($_POST['token'])) {
            $token = sanitize_text_field($_POST['token']);
            if(wp_verify_nonce($token, "update_chaty_view")) {
                if(!get_option("chaty_views")) {
                    add_option("chaty_views", 1);
                }
            }
        }
    }


/** Function get_chaty_settings() called by wp_ajax hooks: {'get_chaty_settings'} **/
/** No params detected :-/ **/


/** Function chaty_plugin_deactivate() called by wp_ajax hooks: {'chaty_plugin_deactivate'} **/
/** No params detected :-/ **/


/** Function hide_chaty_cta() called by wp_ajax hooks: {'hide_chaty_cta'} **/
/** No params detected :-/ **/


/** Function update_popup_status() called by wp_ajax hooks: {'chaty_update_popup_status'} **/
/** Parameters found in function update_popup_status(): {"request": ["nonce"]} **/
function update_popup_status()
    {
        if (!empty($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], 'chaty_update_popup_status')) {
            update_option("chaty_intro_popup", "hide");
        }

        echo esc_attr("1");
        die;

    }


/** Function chaty_front_form_save_data() called by wp_ajax hooks: {'chaty_front_form_save_data', 'nopriv_chaty_front_form_save_data'} **/
/** No params detected :-/ **/


/** Function remove_chaty_widget() called by wp_ajax hooks: {'remove_chaty_widget'} **/
/** No params detected :-/ **/


/** Function update_status() called by wp_ajax hooks: {'chaty_update_status'} **/
/** Parameters found in function update_status(): {"request": ["nonce", "status", "email"]} **/
function update_status() { 
        if(!empty($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], 'chaty_update_nonce')) {
            $status = sanitize_text_field($_REQUEST['status']);
            $email = sanitize_text_field($_REQUEST['email']);
            if($status == 1) {
                update_option(self::$update_message_option, -1);
                $url = 'https://premioapps.com/premio/signup/email.php';
                $apiParams = [
                    'plugin' => 'chaty',
                    'email'  => $email,
                ];

                // Signup Email for Chaty
                $apiResponse = wp_safe_remote_post($url, ['body' => $apiParams, 'timeout' => 15, 'sslverify' => true]);

                if (is_wp_error($apiResponse)) {
                    wp_safe_remote_post($url, ['body' => $apiParams, 'timeout' => 15, 'sslverify' => false]);
                }
            } else {
                $next_date = date('Y-m-d', strtotime('+7 days'));
                $next_signup_date = get_option(self::$next_signup_date);
                if($next_signup_date === false) {
                    add_option(self::$next_signup_date, $next_date);
                } else {
                    update_option(self::$update_message_option, -1);
                }
            }
        }
        echo "1";
        die;
    }


/** Function change_chaty_widget_status() called by wp_ajax hooks: {'change_chaty_widget_status'} **/
/** No params detected :-/ **/


/** Function rename_chaty_widget() called by wp_ajax hooks: {'rename_chaty_widget'} **/
/** Parameters found in function rename_chaty_widget(): {"post": ["widget_index", "widget_nonce", "widget_title"]} **/
function rename_chaty_widget() {
        if (current_user_can('manage_options')) {
            $widget_index = sanitize_text_field($_POST['widget_index']);
            $widget_nonce = sanitize_text_field($_POST['widget_nonce']);
            $widget_title = sanitize_text_field($_POST['widget_title']);
            if (isset($widget_index) && !empty($widget_index) && !empty($widget_nonce) && wp_verify_nonce($widget_nonce, "chaty_remove_" . $widget_index)) {

                $index = $widget_index;
                $index = trim($index, "_");

                if(empty($index)) {
                    update_option("cht_widget_title", $widget_title);
                } else {
                    update_option("cht_widget_title_" . $index, $widget_title);
                }

                echo esc_url(admin_url("admin.php?page=chaty-app"));
                exit;
            }
        }
    }


/** Function choose_social_handler() called by wp_ajax hooks: {'choose_social'} **/
/** No params detected :-/ **/


/** Function update_channel_setting() called by wp_ajax hooks: {'update_channel_setting'} **/
/** Parameters found in function update_channel_setting(): {"request": ["nonce"]} **/
function update_channel_setting()
    {
        if (!empty($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], "Contact_Us-settings")) {
            update_option("chaty_contact_us_setting", "hide");
        }

        echo esc_attr("1");
        die;

    }


/** Function wcp_admin_send_message_to_owner() called by wp_ajax hooks: {'wcp_admin_send_message_to_owner'} **/
/** No params detected :-/ **/


