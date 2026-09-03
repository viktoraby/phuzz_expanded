<?php
/***
*
*Found actions: 4
*Found functions:4
*Extracted functions:4
*Total parameter names extracted: 1
*Overview: {'dismiss_notice_ajax_callback': {'fs_dismiss_notice_action_{$ajax_action_suffix}'}, 'onAjaxExportTemplate': {'unitecreator_elementor_export_template'}, '_toggle_debug_mode': {'fs_toggle_debug_mode'}, 'onAjaxImportLayout': {'unitecreator_elementor_import_template'}}
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


/** Function onAjaxExportTemplate() called by wp_ajax hooks: {'unitecreator_elementor_export_template'} **/
/** No params detected :-/ **/


/** Function _toggle_debug_mode() called by wp_ajax hooks: {'fs_toggle_debug_mode'} **/
/** No params detected :-/ **/


/** Function onAjaxImportLayout() called by wp_ajax hooks: {'unitecreator_elementor_import_template'} **/
/** No params detected :-/ **/


