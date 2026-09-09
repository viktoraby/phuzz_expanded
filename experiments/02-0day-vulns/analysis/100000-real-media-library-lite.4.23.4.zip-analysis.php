<?php
/***
*
*Found actions: 2
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 1
*Overview: {'admin_enqueue_scripts': {'tb_load_editor'}, 'ajax_get_attachment_by_url': {'get-attachment-by-url'}}
*
***/

/** Function admin_enqueue_scripts() called by wp_ajax hooks: {'tb_load_editor'} **/
/** No params detected :-/ **/


/** Function ajax_get_attachment_by_url() called by wp_ajax hooks: {'get-attachment-by-url'} **/
/** Parameters found in function ajax_get_attachment_by_url(): {"request": ["url", "id"]} **/
function ajax_get_attachment_by_url()
    {
        if (!isset($_REQUEST['url'])) {
            \wp_send_json_error();
        }
        $id = \attachment_url_to_postid(\esc_url_raw(\wp_unslash($_REQUEST['url'])));
        if (!$id) {
            \wp_send_json_error();
        }
        $_REQUEST['id'] = $id;
        \wp_ajax_get_attachment();
        die;
    }


