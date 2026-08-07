<?php
/***
*
*Found actions: 3
*Found functions:3
*Extracted functions:3
*Total parameter names extracted: 3
*Overview: {'edit_agent_enabled_status': {'edit_agent_enabled_status'}, 'clarity_dismiss_update_notice': {'clarity_dismiss_update_notice'}, 'edit_clarity_project_id': {'edit_clarity_project_id'}}
*
***/

/** Function edit_agent_enabled_status() called by wp_ajax hooks: {'edit_agent_enabled_status'} **/
/** Parameters found in function edit_agent_enabled_status(): {"post": ["new_value", "nonce"]} **/
function edit_agent_enabled_status()
{
    $new_value = $_POST['new_value'];
    $nonce = $_POST['nonce'];
    if (!wp_verify_nonce($nonce, "wp_ajax_edit_clarity_project_id")) {
        die(json_encode(
                array(
                    'success' => false,
                    'message' => 'Invalid nonce.',
                )
            ));
    }
    // only admins are allowed to edit the Clarity project id
    if (!current_user_can('manage_options')) {
        die(json_encode(
                array(
                    'success' => false,
                    'message' => 'User must be WordPress admin.'
                )
            ));
    } else {
        update_option(
            'BAOauthSuccess', /* option */
            $new_value /* value */
            /* autoload */
        );
        die(json_encode(
                array(
                    'success' => true,
                    'message' => 'Agent enabled status updated successfully.'
                )
            ));
    }
}


/** Function clarity_dismiss_update_notice() called by wp_ajax hooks: {'clarity_dismiss_update_notice'} **/
/** Parameters found in function clarity_dismiss_update_notice(): {"post": ["nonce"]} **/
function clarity_dismiss_update_notice()
{
    if (! current_user_can('update_plugins') || ! isset($_POST['nonce']) || ! wp_verify_nonce($_POST['nonce'], 'clarity_dismiss_update_notice')) {
        wp_die('', '', array('response' => 403));
    }

    $plugin_slug = 'microsoft-clarity/clarity.php';
    $updates = get_site_transient('update_plugins');
    $new_version = isset($updates->response[$plugin_slug]->new_version) ? $updates->response[$plugin_slug]->new_version : '';
    if ($new_version !== '') {
        update_option('clarity_dismissed_update_version', $new_version);
    }
    wp_die();
}


/** Function edit_clarity_project_id() called by wp_ajax hooks: {'edit_clarity_project_id'} **/
/** Parameters found in function edit_clarity_project_id(): {"post": ["new_value", "nonce"]} **/
function edit_clarity_project_id()
{
    $new_value = $_POST['new_value'];
    $nonce = $_POST['nonce'];
    if (!wp_verify_nonce($nonce, "wp_ajax_edit_clarity_project_id")) {
        die(json_encode(
                array(
                    'success' => false,
                    'message' => 'Invalid nonce.',
                )
            ));
    }
    // only admins are allowed to edit the Clarity project id
    if (!current_user_can('manage_options')) {
        die(json_encode(
                array(
                    'success' => false,
                    'message' => 'User must be WordPress admin.'
                )
            ));
    } else {
        update_option(
            'clarity_project_id', /* option */
            $new_value /* value */
            /* autoload */
        );
        die(json_encode(
                array(
                    'success' => true,
                    'message' => 'Clarity project updated successfully.'
                )
            ));
    }
}


