<?php
/***
*
*Found actions: 6
*Found functions:6
*Extracted functions:6
*Total parameter names extracted: 2
*Overview: {'dismiss_notice_ajax_callback': {'fs_dismiss_notice_action_{$ajax_action_suffix}'}, 'ajax_dismiss_auto_link_notice': {'foobox_dismiss_auto_link_notice'}, 'ajax_enable_auto_link_images': {'foobox_enable_auto_link_images'}, 'ajax_dismiss_default_link_notice': {'foobox_dismiss_default_link_notice'}, '_toggle_debug_mode': {'fs_toggle_debug_mode'}, 'ajax_set_default_image_link_type': {'foobox_set_default_image_link_type'}}
*
***/

/** Function dismiss_notice_ajax_callback() called by wp_ajax hooks: {'fs_dismiss_notice_action_{$ajax_action_suffix}'} **/
/** Parameters found in function dismiss_notice_ajax_callback(): {"post": ["message_id"]} **/
function dismiss_notice_ajax_callback() {
            check_admin_referer( 'fs_dismiss_notice_action' );

            if ( ! is_numeric( $_POST['message_id'] ) ) {
                $this->_sticky_storage->remove( $_POST['message_id'] );
            }

            wp_die();
        }


/** Function ajax_dismiss_auto_link_notice() called by wp_ajax hooks: {'foobox_dismiss_auto_link_notice'} **/
/** No params detected :-/ **/


/** Function ajax_enable_auto_link_images() called by wp_ajax hooks: {'foobox_enable_auto_link_images'} **/
/** No params detected :-/ **/


/** Function ajax_dismiss_default_link_notice() called by wp_ajax hooks: {'foobox_dismiss_default_link_notice'} **/
/** No params detected :-/ **/


/** Function _toggle_debug_mode() called by wp_ajax hooks: {'fs_toggle_debug_mode'} **/
/** No params detected :-/ **/


/** Function ajax_set_default_image_link_type() called by wp_ajax hooks: {'foobox_set_default_image_link_type'} **/
/** Parameters found in function ajax_set_default_image_link_type(): {"post": ["value"]} **/
function ajax_set_default_image_link_type() {
                if ( !current_user_can( 'manage_options' ) ) {
                    wp_send_json_error( array(
                        'message' => __( 'Insufficient permissions.', 'foobox-image-lightbox' ),
                    ), 403 );
                }
                check_ajax_referer( 'foobox_set_default_link_type', 'nonce' );
                // Update the option; allow overriding the value param for future extensibility.
                $value = ( isset( $_POST['value'] ) ? sanitize_text_field( wp_unslash( $_POST['value'] ) ) : 'file' );
                if ( 'delete' === $value ) {
                    delete_option( 'image_default_link_type' );
                    $current = get_option( 'image_default_link_type', 'none' );
                    wp_send_json_success( array(
                        'current' => $current,
                    ) );
                }
                if ( !in_array( $value, array('file', 'post', 'none'), true ) ) {
                    $value = 'file';
                }
                update_option( 'image_default_link_type', $value );
                wp_send_json_success( array(
                    'current' => $value,
                ) );
            }


