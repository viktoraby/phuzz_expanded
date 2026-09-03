<?php
/***
*
*Found actions: 7
*Found functions:7
*Extracted functions:7
*Total parameter names extracted: 6
*Overview: {'gosmtp_generate_app_password': {'gosmtp_generate_app_password'}, 'gosmtp_save_test_status': {'gosmtp_save_test_status'}, 'gosmtp_close_update_notice': {'gosmtp_close_update_notice'}, 'gosmtp_test_mcp_connection': {'gosmtp_test_mcp_connection'}, 'gosmtp_install_mcp_adapter': {'gosmtp_install_mcp_adapter'}, 'gosmtp_save_ai_abilities': {'gosmtp_save_ai_abilities'}, 'gosmtp_test_mail': {'gosmtp_test_mail'}}
*
***/

/** Function gosmtp_generate_app_password() called by wp_ajax hooks: {'gosmtp_generate_app_password'} **/
/** No params detected :-/ **/


/** Function gosmtp_save_test_status() called by wp_ajax hooks: {'gosmtp_save_test_status'} **/
/** Parameters found in function gosmtp_save_test_status(): {"post": ["ok", "message"]} **/
function gosmtp_save_test_status(){

	if(!current_user_can('manage_options')){
		wp_send_json_error(__('You do not have permission to do that.', 'gosmtp'));
	}

	check_admin_referer('gosmtp_ajax', 'gosmtp_nonce');

	$ok      = !empty($_POST['ok']);
	$message = !empty($_POST['message']) ? sanitize_text_field(wp_unslash($_POST['message'])) : '';

	\GOSMTP\Abilities::save_test_connection_status($ok, $message);

	wp_send_json_success();
}


/** Function gosmtp_close_update_notice() called by wp_ajax hooks: {'gosmtp_close_update_notice'} **/
/** Parameters found in function gosmtp_close_update_notice(): {"get": ["security"]} **/
function gosmtp_close_update_notice(){

	if(!wp_verify_nonce($_GET['security'], 'gosmtp_promo_nonce')){
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


/** Function gosmtp_test_mcp_connection() called by wp_ajax hooks: {'gosmtp_test_mcp_connection'} **/
/** Parameters found in function gosmtp_test_mcp_connection(): {"post": ["username", "password"]} **/
function gosmtp_test_mcp_connection(){

	if(!current_user_can('manage_options')){
		wp_send_json_error(__('You do not have permission to test the connection.', 'gosmtp'));
	}

	check_admin_referer('gosmtp_ajax', 'gosmtp_nonce');

	$start  = microtime(true);
	$url    = trailingslashit(home_url()) . ltrim(\GOSMTP\Abilities::$ABILITIES_ENDPOINT, '/');

	$username = isset($_POST['username']) ? sanitize_text_field($_POST['username']) : '';
	$password = isset($_POST['password']) ? sanitize_text_field($_POST['password']) : '';

	$response = wp_remote_get($url, [
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => 'Basic ' . base64_encode($username . ':' . $password),
		],
	]);

	$elapsed = round((microtime(true) - $start) * 1000);

	if(is_wp_error($response)){
		$message = sprintf(__('Could not reach the abilities endpoint (%s).', 'gosmtp'), $response->get_error_message());
		\GOSMTP\Abilities::save_test_connection_status(false, $message);
		wp_send_json_error(['message' => $message]);
	}

	$code = wp_remote_retrieve_response_code($response);
	$body = json_decode(wp_remote_retrieve_body($response), true);

	if($code >= 200 && $code < 300 && is_array($body)){
		$gosmtp_abilities = 0;
		foreach($body as $ability){
			$name = isset($ability['name']) ? $ability['name'] : (isset($ability['id']) ? $ability['id'] : '');
			if(is_string($name) && strpos($name, 'gosmtp-') === 0){
				$gosmtp_abilities++;
			}
		}

		$message = sprintf(__('Authenticated with your Application Password and discovered %1$d GoSMTP abilities in %2$dms. Your site is ready to connect an AI client below.', 'gosmtp'), $gosmtp_abilities, $elapsed);

		\GOSMTP\Abilities::save_test_connection_status(true, $message);

		wp_send_json_success([
			'message'    => $message,
			'abilities'  => $gosmtp_abilities,
			'elapsed_ms' => $elapsed,
		]);
	}

	if($code === 401 || $code === 403){
		$message = __('Your Application Password was rejected — it may have been revoked. Generate a new one and test again.', 'gosmtp');
		\GOSMTP\Abilities::save_test_connection_status(false, $message);
		wp_send_json_error(['message' => $message]);
	}

	$message = sprintf(__('The abilities endpoint responded with status %1$d. Check the MCP Adapter is active and try again.', 'gosmtp'), $code);
	\GOSMTP\Abilities::save_test_connection_status(false, $message);
	wp_send_json_error(['message' => $message]);
}


/** Function gosmtp_install_mcp_adapter() called by wp_ajax hooks: {'gosmtp_install_mcp_adapter'} **/
/** Parameters found in function gosmtp_install_mcp_adapter(): {"post": ["adapter_action"]} **/
function gosmtp_install_mcp_adapter(){

	if(!current_user_can('manage_options')){
		wp_send_json_error(__('You do not have permission to do that.', 'gosmtp'));
	}

	check_admin_referer('gosmtp_ajax', 'gosmtp_nonce');

	$options = get_option('gosmtp_options', []);

	if(empty($options['ai_abilities']['enabled'])){
		wp_send_json_error(__( 'The MCP Adapter can not be installed because the **Enable AI Abilities** checkbox in the right sidebar is turned off. Please check it and try again.', 'gosmtp' ));
	}


	$requested_action = !empty($_POST['adapter_action']) ? sanitize_key(wp_unslash($_POST['adapter_action'])) : 'install';

	require_once ABSPATH . 'wp-admin/includes/plugin.php';

	$installed_file = \GOSMTP\Abilities::get_installed_mcp_adapter_file();

	// Update path -> fetch the latest release and re-install over the existing plugin.
	if($requested_action === 'update'){

		if(empty($installed_file)){
			wp_send_json_error(__('The MCP Adapter is not installed yet, so there is nothing to update.', 'gosmtp'));
		}

		$release = \GOSMTP\Abilities::get_mcp_adapter_release();
		if(empty($release['download_url'])){
			wp_send_json_error(__('Could not resolve the MCP Adapter download URL. Please try again in a moment.', 'gosmtp'));
		}

		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/misc.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		$skin = new \WP_Ajax_Upgrader_Skin();
		$upgrader = new \Plugin_Upgrader($skin);
		// Plugin_Upgrader::install() replaces an already-present plugin folder.
		$result   = $upgrader->install($release['download_url'], ['overwrite_package' => true]);
		
		if(is_wp_error($result)){
			wp_send_json_error($result->get_error_message());
		}

		if(false === $result || !$upgrader->plugin_info()){
			$errors = method_exists($skin, 'get_errors') ? $skin->get_errors() : new \WP_Error();
			$message = is_wp_error($errors) && $errors->get_error_message() ? $errors->get_error_message() : __('The MCP Adapter could not be updated.', 'gosmtp');
			wp_send_json_error($message);
		}

		$plugin_file = $upgrader->plugin_info() ?: $installed_file;
		$activated   = activate_plugin($plugin_file);

		if(is_wp_error($activated)){
			wp_send_json_error($activated->get_error_message());
		}

		wp_send_json_success([
			'message' => sprintf(__('MCP Adapter updated to version %s and activated.', 'gosmtp'), $release['version']),
			'state'   => 'active',
			'version' => $release['version'],
		]);
	}

	// Installed but inactive -> just activate.
	if(!empty($installed_file)){

		$activated = activate_plugin($installed_file);
		
		if(is_wp_error($activated)){
			wp_send_json_error($activated->get_error_message());
		}

		wp_send_json_success([
			'message' => __('MCP Adapter activated.', 'gosmtp'),
			'state'   => 'active',
		]);
	}

	// Nothing installed yet -> fetch the release and run the upgrader.
	if($requested_action !== 'install'){
		wp_send_json_error(__('Invalid adapter action.', 'gosmtp'));
	}

	$release = \GOSMTP\Abilities::get_mcp_adapter_release();
	if(empty($release['download_url'])){
		wp_send_json_error(__('Could not resolve the MCP Adapter download URL. Please try again in a moment.', 'gosmtp'));
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/misc.php';
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

	$skin     = new \WP_Ajax_Upgrader_Skin();
	$upgrader = new \Plugin_Upgrader($skin);
	$result   = $upgrader->install($release['download_url']);

	if(is_wp_error($result)){
		wp_send_json_error($result->get_error_message());
	}

	if(false === $result || !$upgrader->plugin_info()){
		$errors = method_exists($skin, 'get_errors') ? $skin->get_errors() : new \WP_Error();
		$message = is_wp_error($errors) && $errors->get_error_message() ? $errors->get_error_message() : __('The MCP Adapter could not be installed.', 'gosmtp');
		wp_send_json_error($message);
	}

	$plugin_file = $upgrader->plugin_info();
	$activated    = activate_plugin($plugin_file);

	if(is_wp_error($activated)){
		wp_send_json_error($activated->get_error_message());
	}

	wp_send_json_success([
		'message' => sprintf(__('MCP Adapter %s installed and activated.', 'gosmtp'), $release['version']),
		'state'   => 'active',
		'version' => $release['version'],
	]);
}


/** Function gosmtp_save_ai_abilities() called by wp_ajax hooks: {'gosmtp_save_ai_abilities'} **/
/** Parameters found in function gosmtp_save_ai_abilities(): {"post": ["enabled"]} **/
function gosmtp_save_ai_abilities(){

	if(!current_user_can('manage_options')){
		wp_send_json_error(__('You do not have permission to do that.', 'gosmtp'));
	}

	check_ajax_referer('gosmtp_ajax', 'nonce');

	$options = get_option('gosmtp_options', []);
	$options['ai_abilities']['enabled'] = !empty($_POST['enabled']) ? 1 : 0;

	update_option('gosmtp_options', $options);

	wp_send_json_success([
		'message' => __('Settings Updated Successfully', 'gosmtp')
	]);
}


/** Function gosmtp_test_mail() called by wp_ajax hooks: {'gosmtp_test_mail'} **/
/** Parameters found in function gosmtp_test_mail(): {"get": ["debug"]} **/
function gosmtp_test_mail(){
	
	global $phpmailer;

	if(!current_user_can('manage_options')){
		wp_send_json_error(__('You do not have required access to do this action', 'gosmtp'));
	}

	// Check nonce
	check_admin_referer( 'gosmtp_ajax' , 'gosmtp_nonce' );

	$to = gosmtp_optpost('reciever_test_email');
	$subject = gosmtp_optpost('smtp_test_subject');
	$body = gosmtp_optpost('smtp_test_message');
	$use_template = gosmtp_optpost('use_html_template');
	
	// TODO: send debug param
	if(isset($_GET['debug'])){
		// show wp_mail() errors
		add_action( 'wp_mail_failed', function( $wp_error ){
			echo "<pre>";
			print_r($wp_error);
			echo "</pre>";
		}, 10, 1 );
	}
	
	$msg = array();
	$headers = [];
	
	// TODO check for mailer
	if(!get_option('gosmtp_options')){
		$msg['error'] = __('You have not configured SMTP settings yet !', 'gosmtp');
	}else{
		
		if(!empty($use_template) && function_exists('gosmtp_pro_test_html_template')){
			$body = gosmtp_pro_test_html_template();
			$headers = ['Content-Type: text/html; charset=UTF-8'];
		}

		$result = wp_mail($to, $subject, $body, $headers);

		if(!$result){
			$msg['error'] = __('Unable to send mail !', 'gosmtp').(empty($phpmailer->ErrorInfo) ? '' : ' '.__('Error : ', 'gosmtp').$phpmailer->ErrorInfo);
		}else{
			$msg['response'] = __('Message sent successfully !', 'gosmtp');
		}
	}
	
	gosmtp_json_output($msg);
}


