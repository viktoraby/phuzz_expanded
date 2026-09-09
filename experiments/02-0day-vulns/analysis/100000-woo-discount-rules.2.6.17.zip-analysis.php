<?php
/***
*
*Found actions: 6
*Found functions:4
*Extracted functions:3
*Total parameter names extracted: 1
*Overview: {'ajax': {'wdr_admin_statistics'}, 'awdr_get_discount_of_a_product': {'awdr_get_product_discount', 'nopriv_awdr_get_product_discount'}, 'version': {'awdr_switch_version'}, 'wdr_ajax_requests': {'wdr_ajax', 'nopriv_wdr_ajax'}}
*
***/

/** Function ajax() called by wp_ajax hooks: {'wdr_admin_statistics'} **/
/** Parameters found in function ajax(): {"request": ["method"]} **/
function ajax() {
        $method = isset( $_REQUEST['method'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['method'] ) ) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $method = "ajax_{$method}";

        if ( method_exists( $this, $method ) ) {
            $this->$method();
        }
    }


/** Function awdr_get_discount_of_a_product() called by wp_ajax hooks: {'awdr_get_product_discount', 'nopriv_awdr_get_product_discount'} **/
/** No params detected :-/ **/


/** Function version() called by wp_ajax hooks: {'awdr_switch_version'} **/
/** No function found :-/ **/


/** Function wdr_ajax_requests() called by wp_ajax hooks: {'wdr_ajax', 'nopriv_wdr_ajax'} **/
/** No params detected :-/ **/


