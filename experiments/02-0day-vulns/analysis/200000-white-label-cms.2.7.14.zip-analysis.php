<?php
/***
*
*Found actions: 5
*Found functions:4
*Extracted functions:4
*Total parameter names extracted: 4
*Overview: {'store_preview': {'wlcms_save_login_preview_settings', 'wlcms_save_dashboard_preview_settings'}, 'hide_vum_dashboard': {'hide_vum_dashboard'}, 'search_pages': {'wlcms_search_pages'}, 'search_initial_pages': {'wlcms_inital_search'}}
*
***/

/** Function store_preview() called by wp_ajax hooks: {'wlcms_save_login_preview_settings', 'wlcms_save_dashboard_preview_settings'} **/
/** No params detected :-/ **/


/** Function hide_vum_dashboard() called by wp_ajax hooks: {'hide_vum_dashboard'} **/
/** Parameters found in function hide_vum_dashboard(): {"request": ["nonce"], "post": ["key"]} **/
function hide_vum_dashboard()
    {
        if (!wp_verify_nonce($_REQUEST['nonce'], "vum_hide_dashboard_nonce")) {
            exit("No naughty business please");
        }
        $user_id = get_current_user_id();
        if ($user_id == 0) {
            return false;
        }

        $key = sanitize_text_field($_POST['key']);
        update_user_meta($user_id, 'vum_hide_dashboard' . $key, 1);
        echo json_encode(array('type' => 'success'));
        exit;
    }


/** Function search_pages() called by wp_ajax hooks: {'wlcms_search_pages'} **/
/** Parameters found in function search_pages(): {"get": ["q"]} **/
function search_pages()
    {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(null, 403);
        }

        $q = isset($_GET['q']) ? $_GET['q'] : '';
        wp_send_json([
            'results' => wlcms_search_pages($q),
            'pagination' => ['more' => false]
        ]);
        wp_die();
    }


/** Function search_initial_pages() called by wp_ajax hooks: {'wlcms_inital_search'} **/
/** Parameters found in function search_initial_pages(): {"get": ["q"]} **/
function search_initial_pages()
    {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(null, 403);
        }

        $ids = [isset($_GET['q']) ? (int) $_GET['q'] : 0];
        $data = wlcms_get_pages_by_ids($ids);

        wp_send_json([
            'initials' => reset($data)
        ]);
        wp_die();
    }


