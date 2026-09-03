<?php
/***
*
*Found actions: 2
*Found functions:1
*Extracted functions:1
*Total parameter names extracted: 1
*Overview: {'cookieadmin_ajax_handler': {'nopriv_cookieadmin_ajax_handler', 'cookieadmin_ajax_handler'}}
*
***/

/** Function cookieadmin_ajax_handler() called by wp_ajax hooks: {'nopriv_cookieadmin_ajax_handler', 'cookieadmin_ajax_handler'} **/
/** Parameters found in function cookieadmin_ajax_handler(): {"request": ["cookieadmin_act"]} **/
function cookieadmin_ajax_handler(){
	
	$cookieadmin_fn = (!empty($_REQUEST['cookieadmin_act']) ? sanitize_text_field(wp_unslash($_REQUEST['cookieadmin_act'])) : '');
	
	if(empty($cookieadmin_fn)){
		wp_send_json_error(array('message' => 'Action not posted'));
	}
	
	// Define a whitelist of allowed functions
	$user_allowed_actions = array();
	
	$admin_allowed_actions = array(
		'scan_cookies' => '\CookieAdmin\Admin\Scan::scan_cookies_ajax',
		'cookieadmin-edit-cookie' => '\CookieAdmin\Admin\Scan::edit_cookies',
		'cookieadmin-delete-cookie' => '\CookieAdmin\Admin\Scan::delete_cookies',
		'close-update-notice' => '\CookieAdmin\Admin::close_plugin_update_notice',
		'close-notice' => '\CookieAdmin\Admin::close_notices',
		'install_recommended_plugin' => '\CookieAdmin\Admin\Dashboard::install_recommended_plugin',
		'activate_recommended_plugin' => '\CookieAdmin\Admin\Dashboard::activate_recommended_plugin',
	);
	
	$general_actions = array(
		'categorize_cookies' => 'cookieadmin_categorize_cookies',
	);
	
	if(array_key_exists($cookieadmin_fn, $user_allowed_actions)){
		
		check_ajax_referer('cookieadmin_js_nonce', 'cookieadmin_security');
		header_remove('Set-Cookie');
		call_user_func('\CookieAdmin\Enduser::'.$user_allowed_actions[$cookieadmin_fn]);
		
	}elseif(array_key_exists($cookieadmin_fn, $admin_allowed_actions)){
		
		check_ajax_referer('cookieadmin_admin_js_nonce', 'cookieadmin_security');
	 
		if(!current_user_can('administrator')){
			wp_send_json_error(array('message' => 'Sorry, but you do not have permissions to perform this action'));
		}
		
		// TODO: Need to test this throughly
		call_user_func($admin_allowed_actions[$cookieadmin_fn]);
		
	}elseif(array_key_exists($cookieadmin_fn, $general_actions)){
		
		check_ajax_referer('cookieadmin_js_nonce', 'cookieadmin_security');
		header_remove('Set-Cookie');
		call_user_func($general_actions[$cookieadmin_fn]);
		
	}else{
		wp_send_json_error(array('message' => 'Unauthorized action'));
	}
	
}


