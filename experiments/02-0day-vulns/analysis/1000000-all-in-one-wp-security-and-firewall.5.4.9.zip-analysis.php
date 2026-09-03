<?php
/***
*
*Found actions: 9
*Found functions:7
*Extracted functions:7
*Total parameter names extracted: 7
*Overview: {'handle_ajax_requests': {'aios_ajax'}, 'shared_ajax': {'simbatfa_shared_ajax'}, 'get_antibot_keys': {'nopriv_get_antibot_keys'}, 'ajax': {'tfa_frontend'}, 'wp_ajax_updraftcentral_receivepublickey': {'nopriv_updraftcentral_receivepublickey', 'updraftcentral_receivepublickey'}, 'tfaInitLogin': {'simbatfa-init-otp', 'nopriv_simbatfa-init-otp'}, 'updraft_central_ajax_handler': {'updraft_central_ajax'}}
*
***/

/** Function handle_ajax_requests() called by wp_ajax hooks: {'aios_ajax'} **/
/** No params detected :-/ **/


/** Function shared_ajax() called by wp_ajax hooks: {'simbatfa_shared_ajax'} **/
/** Parameters found in function shared_ajax(): {"post": ["subaction", "nonce", "device_id"]} **/
function shared_ajax() {

		if (empty($_POST['subaction']) || empty($_POST['nonce']) || !is_user_logged_in() || !wp_verify_nonce($_POST['nonce'], 'tfa_shared_nonce')) die('Security check (3).');

		global $current_user;

		$subaction = $_POST['subaction'];

		if ('refreshotp' == $subaction) {

			$code = $this->get_controller('totp')->get_current_code($current_user->ID);

			if (false === $code) die(json_encode(array('code' => '')));

			die(json_encode(array('code' => $code)));

		} elseif ('untrust_device' == $subaction && isset($_POST['device_id'])) {
			$this->untrust_device(stripslashes($_POST['device_id']));
			ob_start();
			$this->include_template('trusted-devices-inner-box.php', array('trusted_devices' => $this->user_get_trusted_devices()));
			echo json_encode(array('trusted_list' => ob_get_clean()));
		}

		exit;

	}


/** Function get_antibot_keys() called by wp_ajax hooks: {'nopriv_get_antibot_keys'} **/
/** Parameters found in function get_antibot_keys(): {"post": ["nonce"]} **/
function get_antibot_keys() {
		global $aio_wp_security;
		
		$response = array(
			'status' => 'success',
			'data' => array(),
		);

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- PCP warning. It is the nonce.
		$nonce = empty($_POST['nonce']) ? '' : sanitize_key(wp_unslash($_POST['nonce']));
		if (!wp_verify_nonce($nonce, 'wp-security-ajax-nonce')) {
			$response['status'] = false;
			$response['error_code'] = 'invalid_nonce';
			$response['error_message'] = 'Invalid nonce (wp-security-ajax-nonce) provided for this action.';
		} else {
			$key_map_arr = AIOWPSecurity_Comment::generate_antibot_keys(true);
			$response['data'] = $key_map_arr[0];
			if ('1' == $aio_wp_security->configs->get_value('aiowps_spambot_detect_usecookies')) {
				AIOWPSecurity_Comment::insert_antibot_keys_in_cookie();
			}
		}
		
		echo wp_json_encode($response);
		exit;
	}


/** Function ajax() called by wp_ajax hooks: {'tfa_frontend'} **/
/** Parameters found in function ajax(): {"post": ["subaction", "nonce", "settings"]} **/
function ajax() {
		$totp_controller = $this->mother->get_controller('totp');
		global $current_user;
		
		$return_array = array();
		
		if (empty($_POST) || empty($_POST['subaction']) || !isset($_POST['nonce']) || !is_user_logged_in() || !wp_verify_nonce($_POST['nonce'], 'tfa_frontend_nonce')) die('Security check');
		
		if ('savesettings' == $_POST['subaction']) {
			if (empty($_POST['settings']) || !is_string($_POST['settings'])) die;
			
			parse_str(stripslashes($_POST['settings']), $posted_settings);
			
			if (isset($posted_settings['tfa_algorithm_type'])) {
				$old_algorithm = $totp_controller->get_user_otp_algorithm($current_user->ID);
		
				if ($old_algorithm != $posted_settings['tfa_algorithm_type'])
					$totp_controller->changeUserAlgorithmTo($current_user->ID, $posted_settings['tfa_algorithm_type']);
				
				//Re-fetch the algorithm type, url and private string
				$variables = $this->tfa_fetch_assort_vars();
				
				$return_array['qr'] = $totp_controller->tfa_qr_code_url($variables['algorithm_type'], $variables['url'], $variables['tfa_priv_key']);
				$return_array['al_type_disp'] = $this->tfa_algorithm_info($variables['algorithm_type']);
			}
			
			if (isset($posted_settings['tfa_enable_tfa'])) {
			
				$allow_enable_or_disable = false;
			
				if (empty($posted_settings['require_current']) || !$posted_settings['tfa_enable_tfa']) {
					$allow_enable_or_disable = true;
				} else {
				
					if (!isset($posted_settings['tfa_enable_current']) || '' == $posted_settings['tfa_enable_current']) {
						$return_array['message'] = __('To enable TFA, you must enter the current code.', 'all-in-one-wp-security-and-firewall');
						$return_array['error'] = 'code_absent';
					} else {
						// Third parameter: don't allow emergency codes
						if ($totp_controller->check_code_for_user($current_user->ID, $posted_settings['tfa_enable_current'], false)) {
							$allow_enable_or_disable = true;
						} else {
							$return_array['error'] = 'code_wrong';
							$return_array['message'] = apply_filters('simba_tfa_message_code_incorrect', __('The TFA code you entered was incorrect.', 'all-in-one-wp-security-and-firewall'));
						}
					}
				
				}
				
				if ($allow_enable_or_disable) $this->mother->change_tfa_enabled_status($current_user->ID, $posted_settings['tfa_enable_tfa']);
			}
			
			$return_array['result'] = 'saved';
			
			echo json_encode($return_array);
		}
		
		die;
	}


/** Function wp_ajax_updraftcentral_receivepublickey() called by wp_ajax hooks: {'nopriv_updraftcentral_receivepublickey', 'updraftcentral_receivepublickey'} **/
/** Parameters found in function wp_ajax_updraftcentral_receivepublickey(): {"get": ["_wpnonce", "public_key", "updraft_key_index"]} **/
function wp_ajax_updraftcentral_receivepublickey() {
		global $updraftcentral_host_plugin;
	
		// The actual nonce check is done in the method below
		if (empty($_GET['_wpnonce']) || empty($_GET['public_key']) || !isset($_GET['updraft_key_index'])) die;
		
		$result = $this->receive_public_key();
		if (!is_array($result) || empty($result['responsetype'])) die;

		?>
		<html>
			<head>
				<title>UpdraftCentral</title>
				<style>
					body {text-align: center;font-family: Helvetica,Arial,Lucida,sans-serif;background-color: #A64C1A;color: #FFF;height: 100%;width: 100%;margin: 0;padding: 0;}#main {height: 100%;width: 100%;display: table;}#wrapper {display: table-cell;height: 100%;vertical-align: middle;}h1 {margin-bottom: 5px;}h2 {margin-top: 0;font-size: 22px;color: #FFF;}#btn-close {color: #FFF;font-size: 20px;font-weight: 500;padding: .3em 1em;line-height: 1.7em !important;background-color: transparent;background-size: cover;background-position: 50%;background-repeat: no-repeat;border: 2px solid;border-radius: 3px;-webkit-transition-duration: .2s;transition-duration: .2s;-webkit-transition-property: all !important;transition-property: all !important;text-decoration: none;}#btn-close:hover {background-color: #DE6726;}
				</style>
			</head>
			<body>
				<div id="main">
					<div id="wrapper"><img src="<?php echo esc_url(UPDRAFTCENTRAL_CLIENT_URL).'/images/ud-logo.png'; ?>" width="60" /> <h1><?php $updraftcentral_host_plugin->retrieve_show_message('updraftcentral_connection', true); ?></h1><h2><?php echo esc_html(network_site_url()); ?></h2><p>
		<?php
		if ('ok' == $result['responsetype']) {
			$updraftcentral_host_plugin->retrieve_show_message('updraftcentral_connection_successful', true);
		} else {
			?>
			<strong><span id="udc-connect-failed">
				<?php $updraftcentral_host_plugin->retrieve_show_message('updraftcentral_connection_failed', true); ?>
			</span></strong><br>
			<?php
			switch ($result['code']) {
				case 'unknown_key':
					$updraftcentral_host_plugin->retrieve_show_message('unknown_key', true);
					break;
				case 'not_logged_in':
					echo esc_html($updraftcentral_host_plugin->retrieve_show_message('not_logged_in')).' ';
					$updraftcentral_host_plugin->retrieve_show_message('must_visit_url', true);
					break;
				case 'nonce_failure':
					$updraftcentral_host_plugin->retrieve_show_message('security_check', true);
					$updraftcentral_host_plugin->retrieve_show_message('must_visit_link', true);
					break;
				case 'already_have':
					$updraftcentral_host_plugin->retrieve_show_message('connection_already_made', true);
					break;
				case 'insufficient_privilege':
					$updraftcentral_host_plugin->retrieve_show_message('insufficient_privilege', true);
					break;
				default:
					echo esc_html(print_r($result, true));
					break;
			}
		}
		?>
		</p>
		<p><a id="btn-close" href="<?php echo esc_url($this->get_current_clean_url()); ?>" onclick="window.close();"><?php $updraftcentral_host_plugin->retrieve_show_message('close', true); ?></a>
		</p></div></div>
		<?php
		die;
	}


/** Function tfaInitLogin() called by wp_ajax hooks: {'simbatfa-init-otp', 'nopriv_simbatfa-init-otp'} **/
/** Parameters found in function tfaInitLogin(): {"post": ["user"], "cookie": ["simbatfa_trust_token"]} **/
function tfaInitLogin() {

		if (empty($_POST['user'])) die('Security check (2).');

		if (defined('TWO_FACTOR_DISABLE') && TWO_FACTOR_DISABLE) {
			$res = array('result' => false, 'user_can_trust' => false);
		} else {

			if (!function_exists('sanitize_user')) require_once ABSPATH.WPINC.'/formatting.php';

			// WP's password-checking sanitizes the supplied user, so we must do the same to check if TFA is enabled for them
			$auth_info = array('log' => sanitize_user(stripslashes((string)$_POST['user'])));

			if (!empty($_COOKIE['simbatfa_trust_token'])) $auth_info['trust_token'] = (string) $_COOKIE['simbatfa_trust_token'];

			$res = $this->pre_auth($auth_info, 'array');
		}

		$results = array(
			'jsonstarter' => 'justhere',
			'status' => $res['result'],
		);

		if (!empty($res['user_can_trust'])) {
			$results['user_can_trust'] = 1;
			if (!empty($res['user_already_trusted'])) $results['user_already_trusted'] = 1;
		}


		if (!empty($this->output_buffering)) {
			if (!empty($this->logged)) {
				$results['php_output'] = $this->logged;
			}
			restore_error_handler();
			$buffered = ob_get_clean();
			if ($buffered) $results['extra_output'] = $buffered;
		}

		$results = apply_filters('simbatfa_check_tfa_requirements_ajax_response', $results);

		echo json_encode($results);

		exit;
	}


/** Function updraft_central_ajax_handler() called by wp_ajax hooks: {'updraft_central_ajax'} **/
/** Parameters found in function updraft_central_ajax_handler(): {"request": ["nonce", "subaction"]} **/
function updraft_central_ajax_handler() {
		global $updraftcentral_main;

		$nonce = empty($_REQUEST['nonce']) ? '' : $_REQUEST['nonce'];
		if (empty($nonce) || !wp_verify_nonce($nonce, 'updraftcentral-request-nonce') || !$this->current_user_can_ajax() || empty($_REQUEST['subaction'])) die('Security check');

		if (is_a($updraftcentral_main, 'UpdraftCentral_Main')) {

			$subaction = $_REQUEST['subaction'];
			if ($this->is_action_whitelisted($subaction) && is_callable(array($updraftcentral_main, $subaction))) {

				// Undo WP's slashing of POST data
				$data = $this->wp_unslash($_POST);

				// TODO: Once all commands come through here and through updraft_send_command(), the data should always come from this attribute (once updraft_send_command() is modified appropriately).
				if (isset($data['action_data'])) $data = $data['action_data'];
				try {
					
					$results = call_user_func(array($updraftcentral_main, $subaction), $data);
				} catch (Exception $e) {
					$log_message = 'PHP Fatal Exception error ('.get_class($e).') has occurred during '.$subaction.' subaction. Error Message: '.$e->getMessage().' (Code: '.$e->getCode().', line '.$e->getLine().' in '.$e->getFile().')';
					error_log($log_message);
					echo json_encode(array(
						'fatal_error' => true,
						'fatal_error_message' => $log_message
					));
					die;
				// @codingStandardsIgnoreLine
				} catch (Error $e) {
					$log_message = 'PHP Fatal error ('.get_class($e).') has occurred during '.$subaction.' subaction. Error Message: '.$e->getMessage().' (Code: '.$e->getCode().', line '.$e->getLine().' in '.$e->getFile().')';
					error_log($log_message);
					echo json_encode(array(
						'fatal_error' => true,
						'fatal_error_message' => $log_message
					));
					die;
				}
				if (is_wp_error($results)) {
					$results = array(
						'result' => false,
						'error_code' => $results->get_error_code(),
						'error_message' => $results->get_error_message(),
						'error_data' => $results->get_error_data(),
					);
				}

				if (is_string($results)) {
					// A handful of legacy methods, and some which are directly the source for iframes, for which JSON is not appropriate.
					echo $results; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- We don't escape here to avoid double escaping and keep the HTML code needed by the receiver of this request. 
				} else {
					echo json_encode($results);
				}
				die;
			}
		}
		die;
	}


