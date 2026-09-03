<?php
/***
*
*Found actions: 10
*Found functions:10
*Extracted functions:10
*Total parameter names extracted: 2
*Overview: {'loginizer_close_update_notice': {'loginizer_close_update_notice'}, 'loginizer_dismiss_newsletter': {'loginizer_dismiss_newsletter'}, 'loginizer_social_order': {'loginizer_social_order'}, 'loginizer_failed_login_export': {'loginizer_failed_login_export'}, 'loginizer_dismiss_backuply': {'loginizer_dismiss_backuply'}, 'loginizer_export': {'loginizer_export'}, 'loginizer_dismiss_csrf': {'loginizer_dismiss_csrf'}, 'loginizer_dismiss_softwp_alert': {'loginizer_dismiss_softwp_alert'}, 'loginizer_dismiss_license_alert': {'loginizer_dismiss_license_alert'}, 'loginizer_dismiss_social_alert': {'loginizer_dismiss_social_alert'}}
*
***/

/** Function loginizer_close_update_notice() called by wp_ajax hooks: {'loginizer_close_update_notice'} **/
/** Parameters found in function loginizer_close_update_notice(): {"get": ["security"]} **/
function loginizer_close_update_notice(){

	if(!wp_verify_nonce($_GET['security'], 'loginizer_promo_nonce')){
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


/** Function loginizer_dismiss_newsletter() called by wp_ajax hooks: {'loginizer_dismiss_newsletter'} **/
/** No params detected :-/ **/


/** Function loginizer_social_order() called by wp_ajax hooks: {'loginizer_social_order'} **/
/** Parameters found in function loginizer_social_order(): {"post": ["order"]} **/
function loginizer_social_order(){
	
	// Some AJAX security
	check_ajax_referer('loginizer_social_nonce', 'security');

	if(!current_user_can('manage_options')){
		wp_die(__('Sorry, but you do not have permissions to change settings.', 'loginizer'));
	}
	
	$order = map_deep(map_deep($_POST['order'], 'wp_unslash'), 'sanitize_text_field');

	update_option('loginizer_social_order', array_flip($order));
	
	wp_send_json_success();
	
}


/** Function loginizer_failed_login_export() called by wp_ajax hooks: {'loginizer_failed_login_export'} **/
/** No params detected :-/ **/


/** Function loginizer_dismiss_backuply() called by wp_ajax hooks: {'loginizer_dismiss_backuply'} **/
/** No params detected :-/ **/


/** Function loginizer_export() called by wp_ajax hooks: {'loginizer_export'} **/
/** No params detected :-/ **/


/** Function loginizer_dismiss_csrf() called by wp_ajax hooks: {'loginizer_dismiss_csrf'} **/
/** No params detected :-/ **/


/** Function loginizer_dismiss_softwp_alert() called by wp_ajax hooks: {'loginizer_dismiss_softwp_alert'} **/
/** No params detected :-/ **/


/** Function loginizer_dismiss_license_alert() called by wp_ajax hooks: {'loginizer_dismiss_license_alert'} **/
/** No params detected :-/ **/


/** Function loginizer_dismiss_social_alert() called by wp_ajax hooks: {'loginizer_dismiss_social_alert'} **/
/** No params detected :-/ **/


