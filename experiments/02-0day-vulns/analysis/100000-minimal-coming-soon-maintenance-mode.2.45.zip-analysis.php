<?php
/***
*
*Found actions: 6
*Found functions:6
*Extracted functions:6
*Total parameter names extracted: 1
*Overview: {'csmm_welcome_hide': {'csmm_welcome_hide'}, 'csmm_rate_hide': {'csmm_rate_hide'}, 'csmm_subscribe_hide': {'csmm_subscribe_hide'}, 'csmm_olduser_hide': {'csmm_olduser_hide'}, 'csmm_ajax_support': {'signals_csmm_support'}, 'csmm_dismiss_pointer_ajax': {'csmm_dismiss_pointer'}}
*
***/

/** Function csmm_welcome_hide() called by wp_ajax hooks: {'csmm_welcome_hide'} **/
/** No params detected :-/ **/


/** Function csmm_rate_hide() called by wp_ajax hooks: {'csmm_rate_hide'} **/
/** No params detected :-/ **/


/** Function csmm_subscribe_hide() called by wp_ajax hooks: {'csmm_subscribe_hide'} **/
/** No params detected :-/ **/


/** Function csmm_olduser_hide() called by wp_ajax hooks: {'csmm_olduser_hide'} **/
/** No params detected :-/ **/


/** Function csmm_ajax_support() called by wp_ajax hooks: {'signals_csmm_support'} **/
/** No params detected :-/ **/


/** Function csmm_dismiss_pointer_ajax() called by wp_ajax hooks: {'csmm_dismiss_pointer'} **/
/** Parameters found in function csmm_dismiss_pointer_ajax(): {"post": ["pointer"]} **/
function csmm_dismiss_pointer_ajax()
{
    check_ajax_referer('csmm_dismiss_pointer');

    if(!isset($_POST['pointer'])){
        wp_send_json_error();
    }

    $disabled_pointers = get_option(CSMM_POINTERS);
    $pointer = trim(sanitize_key(wp_unslash($_POST['pointer'])));

    $disabled_pointers[$pointer] = true;
    update_option(CSMM_POINTERS, $disabled_pointers);

    wp_send_json_success();
}


