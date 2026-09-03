<?php
/***
*
*Found actions: 1
*Found functions:1
*Extracted functions:1
*Total parameter names extracted: 1
*Overview: {'ajax_toggle_auto_update': {'skb_toggle_auto_update'}}
*
***/

/** Function ajax_toggle_auto_update() called by wp_ajax hooks: {'skb_toggle_auto_update'} **/
/** Parameters found in function ajax_toggle_auto_update(): {"post": ["enable"]} **/
function ajax_toggle_auto_update()
        {
            check_ajax_referer('skb_admin_nonce', 'nonce');

            if (!current_user_can('update_plugins')) {
                wp_send_json_error('Unauthorized');
            }

            $enable = isset($_POST['enable']) && $_POST['enable'] === 'true';
            $auto_updates = (array) get_site_option('auto_update' . '_plugins', array());

            if ($enable) {
                if (!in_array(SKB_CIFE_PLUGIN_BASE, $auto_updates)) {
                    $auto_updates[] = SKB_CIFE_PLUGIN_BASE;
                }
            } else {
                $auto_updates = array_diff($auto_updates, array(SKB_CIFE_PLUGIN_BASE));
            }

            update_site_option('auto_update' . '_plugins', $auto_updates);
            wp_send_json_success();
        }


