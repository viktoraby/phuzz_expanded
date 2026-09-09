<?php
/***
*
*Found actions: 3
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 1
*Overview: {'wpcf7cf_dismiss_notice': {'wpcf7cf_dismiss_notice'}, 'cf7mls_validation_callback': {'nopriv_cf7mls_validation', 'cf7mls_validation'}}
*
***/

/** Function wpcf7cf_dismiss_notice() called by wp_ajax hooks: {'wpcf7cf_dismiss_notice'} **/
/** Parameters found in function wpcf7cf_dismiss_notice(): {"post": ["nonce", "noticeId"]} **/
function wpcf7cf_dismiss_notice() {

    // check nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'wpcf7cf_dismiss_notice' ) ) {
        wp_send_json_error( array( 'message' => __( 'Nonce verification failed', 'cf7-conditional-fields' ) ) );
    }

    $notice_id = sanitize_text_field($_POST['noticeId'] ?? '');
    $notice_suffix = $notice_id ? '_'.$notice_id : $notice_id;

    $settings = wpcf7cf_get_settings();
    $settings['notice_dismissed'.$notice_suffix] = true;
    wpcf7cf_set_options($settings);
}


/** Function cf7mls_validation_callback() called by wp_ajax hooks: {'nopriv_cf7mls_validation', 'cf7mls_validation'} **/
/** No params detected :-/ **/


