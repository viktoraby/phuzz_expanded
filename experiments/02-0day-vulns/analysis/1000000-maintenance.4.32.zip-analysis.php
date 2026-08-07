<?php
/***
*
*Found actions: 2
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 2
*Overview: {'ajax_action': {'mtnc_action'}, 'ajax_dismiss_notice': {'maintenance_dismiss_notice'}}
*
***/

/** Function ajax_action() called by wp_ajax hooks: {'mtnc_action'} **/
/** Parameters found in function ajax_action(): {"request": ["mtnc_action", "extra_data", "theme_global", "theme", "theme_id", "theme_new", "theme_name"]} **/
function ajax_action()
    {
        check_ajax_referer('mtnc_action');
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('You are not allowed to run this action.', 'maintenance'));
        }

        $options = $this->get_options();

        $action = trim(wp_unslash($_REQUEST['mtnc_action'] ?? '')); //phpcs:ignore
        $extra_data = wp_unslash($_REQUEST['extra_data'] ?? ''); //phpcs:ignore
        if (is_string($extra_data)) {
            parse_str($extra_data, $extra_data);
        } elseif (is_array($extra_data)) {
            $extra_data = array_slice($extra_data, 0, 20);
        } else {
            $extra_data = false;
        }

        switch ($action) {
            case 'save':
                $new_options = array(
                    'theme_global' => $options['theme_global'],
                    'themes' => $options['themes'],
                    'status' => @absint($extra_data['mtnc_status']),
                    'mode' => 'layout',
                    'mtnc_page' => 0,
                    //SEO
                    'title' => strip_tags($extra_data['title']),
                    'analytics' => strip_tags($this->convert_ga($extra_data['analytics'])),
                    //Access
                    'per_url_settings' => wp_strip_all_tags($extra_data['per_url_settings']),
                    'per_url_enable_disable' => wp_strip_all_tags($extra_data['per_url_enable_disable']),
                    'login_button' => @absint($extra_data['login_button']),
                    'wplogin_button_tooltip' => wp_strip_all_tags($extra_data['wplogin_button_tooltip']),
                );

                $new_options['theme_global'] = sanitize_text_field(wp_unslash($_REQUEST['theme_global'] ?? ''));

                //theme JSON will get broken by sanitization through wp_kses_post or normal functions, fields will be escaped when rendering on front-end
                $theme = $_REQUEST['theme']; //phpcs:ignore
                $theme_id = sanitize_text_field(wp_unslash($_REQUEST['theme_id'] ?? ''));
                $theme_new = sanitize_text_field(wp_unslash($_REQUEST['theme_new'] ?? ''));
                $theme_name = sanitize_text_field(wp_unslash($_REQUEST['theme_name'] ?? ''));

                if (isset($_REQUEST['theme_new']) && $theme_new == true) {
                    $theme_id = md5(time() . $theme_name);
                }

                $new_options['themes'][$theme_id] = json_decode(stripslashes($theme));
                $new_options['themes'][$theme_id]->name = $theme_name;

                //Design General
                if (isset($extra_data['theme_thumbnail'])) {
                    $new_options['themes'][$theme_id]->theme_thumbnail = wp_strip_all_tags($extra_data['theme_thumbnail']);
                }
                if (isset($extra_data['theme_status'])) {
                    $new_options['themes'][$theme_id]->theme_status = wp_strip_all_tags($extra_data['theme_status']);
                }

                $new_options['themes'][$theme_id]->body_font_size = wp_strip_all_tags($extra_data['body_font_size']);
                $new_options['themes'][$theme_id]->body_font_color = wp_strip_all_tags($extra_data['body_font_color']);
                $new_options['themes'][$theme_id]->body_font = wp_strip_all_tags($extra_data['body_font']);
                $new_options['themes'][$theme_id]->body_link_color = wp_strip_all_tags($extra_data['body_link_color']);
                $new_options['themes'][$theme_id]->body_link_hover_color = wp_strip_all_tags($extra_data['body_link_hover_color']);

                //Design General
                $new_options['themes'][$theme_id]->background_type = wp_strip_all_tags($extra_data['background_type']);
                $new_options['themes'][$theme_id]->background_video = wp_strip_all_tags($extra_data['background_video']);
                $new_options['themes'][$theme_id]->background_video_fallback = isset($extra_data['background_video_fallback']) ? wp_strip_all_tags($extra_data['background_video_fallback']) : '';
                $new_options['themes'][$theme_id]->background_video_filter = wp_strip_all_tags($extra_data['background_video_filter']);
                $new_options['themes'][$theme_id]->background_size_opt = wp_strip_all_tags($extra_data['background_size_opt']);
                $new_options['themes'][$theme_id]->background_position = wp_strip_all_tags($extra_data['background_position']);
                $new_options['themes'][$theme_id]->background_image_filter = wp_strip_all_tags($extra_data['background_image_filter']);
                $new_options['themes'][$theme_id]->background_image = wp_strip_all_tags($extra_data['background_image']);
                $new_options['themes'][$theme_id]->background_blur = (int)$extra_data['background_blur'];
                $new_options['themes'][$theme_id]->login_background_color = wp_strip_all_tags($extra_data['login_background_color']);


                $new_options['themes'][$theme_id]->preloader_background_image = wp_strip_all_tags($extra_data['preloader_background_image']);

                $new_options['themes'][$theme_id]->background_color = wp_strip_all_tags($extra_data['background_color']);
                $new_options['themes'][$theme_id]->content_overlay = isset($extra_data['content_overlay']) ? wp_strip_all_tags($extra_data['content_overlay']) : '';
                $new_options['themes'][$theme_id]->content_width = (int) $extra_data['content_width'];
                $new_options['themes'][$theme_id]->content_overlay_color = wp_strip_all_tags($extra_data['content_overlay_color']);
                $new_options['themes'][$theme_id]->content_overlay_shadow_color = wp_strip_all_tags($extra_data['content_overlay_shadow_color']);
                $new_options['themes'][$theme_id]->content_position = wp_strip_all_tags($extra_data['content_position']);
                $new_options['themes'][$theme_id]->modules_spacing = (int) $extra_data['modules_spacing'];
                $new_options['themes'][$theme_id]->custom_css = wp_strip_all_tags($extra_data['custom_css']);


                $this->update_options('options', $new_options);
                wp_send_json_success(array('theme_id' => $theme_id, 'theme' => $new_options['themes'][$theme_id]));
                break;
        }
    }


/** Function ajax_dismiss_notice() called by wp_ajax hooks: {'maintenance_dismiss_notice'} **/
/** Parameters found in function ajax_dismiss_notice(): {"get": ["notice_name"]} **/
function ajax_dismiss_notice()
    {
        check_ajax_referer('maintenance_dismiss_notice');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('You are not allowed to run this action.');
        }

        $notice_name = trim(sanitize_text_field(wp_unslash($_GET['notice_name'] ?? '')));
        if (!$this->dismiss_notice($notice_name)) {
            wp_send_json_error('Notice is already dismissed.');
        } else {
            wp_send_json_success();
        }
    }


