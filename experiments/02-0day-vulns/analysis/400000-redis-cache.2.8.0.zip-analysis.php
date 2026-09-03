<?php
/***
*
*Found actions: 2
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 2
*Overview: {'ajax_dismiss_notice': {'roc_dismiss_notice'}, 'ajax_flush_cache': {'roc_flush_cache'}}
*
***/

/** Function ajax_dismiss_notice() called by wp_ajax hooks: {'roc_dismiss_notice'} **/
/** Parameters found in function ajax_dismiss_notice(): {"post": ["notice"]} **/
function ajax_dismiss_notice() {
        if ( isset( $_POST['notice'] ) ) {
            check_ajax_referer( 'roc_dismiss_notice' );

            $notice = sprintf(
                'roc_dismissed_%s',
                sanitize_key( $_POST['notice'] )
            );

            update_user_meta( get_current_user_id(), $notice, '1' );
        }

        wp_die();
    }


/** Function ajax_flush_cache() called by wp_ajax hooks: {'roc_flush_cache'} **/
/** Parameters found in function ajax_flush_cache(): {"post": ["nonce"]} **/
function ajax_flush_cache() {
        if ( ! wp_verify_nonce( $_POST['nonce'] ?? '' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
            wp_die( esc_html__( 'Invalid Nonce.', 'redis-cache' ) );
        } else if ( wp_cache_flush() ) {
            wp_die( esc_html__( 'Object cache flushed.', 'redis-cache' ) );
        } else {
            wp_die( esc_html__( 'Object cache could not be flushed.', 'redis-cache' ) );
        }
    }


