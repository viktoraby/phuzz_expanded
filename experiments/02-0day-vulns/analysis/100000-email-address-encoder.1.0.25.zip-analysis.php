<?php
/***
*
*Found actions: 1
*Found functions:1
*Extracted functions:1
*Total parameter names extracted: 1
*Overview: {'eae_dismiss_notice': {'eae_dismiss_notice'}}
*
***/

/** Function eae_dismiss_notice() called by wp_ajax hooks: {'eae_dismiss_notice'} **/
/** Parameters found in function eae_dismiss_notice(): {"post": ["notice"]} **/
function eae_dismiss_notice() {
    $notice = sprintf(
        'eae_dismissed_%s',
        sanitize_key( $_POST[ 'notice' ] )
    );

    update_user_meta( get_current_user_id(), $notice, '1' );

    wp_die();
}


