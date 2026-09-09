<?php
/***
*
*Found actions: 4
*Found functions:4
*Extracted functions:4
*Total parameter names extracted: 1
*Overview: {'fileorganizer_ajax_handler': {'fileorganizer_file_folder_manager'}, 'fileorganizer_close_update_notice': {'fileorganizer_close_update_notice'}, 'fileorganizer_hide_promo': {'fileorganizer_hide_promo'}, 'fileorganizer_switch_theme': {'fileorganizer_switch_theme'}}
*
***/

/** Function fileorganizer_ajax_handler() called by wp_ajax hooks: {'fileorganizer_file_folder_manager'} **/
/** No params detected :-/ **/


/** Function fileorganizer_close_update_notice() called by wp_ajax hooks: {'fileorganizer_close_update_notice'} **/
/** Parameters found in function fileorganizer_close_update_notice(): {"get": ["security"]} **/
function fileorganizer_close_update_notice(){

	if(!wp_verify_nonce($_GET['security'], 'fileorganizer_promo_nonce')){
		wp_send_json_error('Security Check failed!');
	}

	if(!current_user_can('manage_options')){
		wp_send_json_error('You don\'t have privilege to close this notice!');
	}

	$plugin_update_notice = get_option('softaculous_plugin_update_notice', []);
	$available_update_list = get_site_transient('update_plugins');
	$to_update_plugins = apply_filters('softaculous_plugin_update_notice', []);

	if(empty($available_update_list) || empty($available_update_list->response)){
		return;
	}

	foreach($to_update_plugins as $plugin_path => $plugin_name){
		if(isset($available_update_list->response[$plugin_path])){
			$plugin_update_notice[$plugin_path] = $available_update_list->response[$plugin_path]->new_version;
		}
	}

	update_option('softaculous_plugin_update_notice', $plugin_update_notice);
}


/** Function fileorganizer_hide_promo() called by wp_ajax hooks: {'fileorganizer_hide_promo'} **/
/** No params detected :-/ **/


/** Function fileorganizer_switch_theme() called by wp_ajax hooks: {'fileorganizer_switch_theme'} **/
/** No params detected :-/ **/


