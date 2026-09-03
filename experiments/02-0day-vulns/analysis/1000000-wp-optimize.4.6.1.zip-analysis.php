<?php
/***
*
*Found actions: 5
*Found functions:4
*Extracted functions:4
*Total parameter names extracted: 2
*Overview: {'handle_ajax_requests': {'wp_optimize_ajax'}, 'updraft_smush_ajax': {'updraft_smush_ajax'}, 'updraft_central_ajax_handler': {'updraft_central_ajax'}, 'wp_ajax_updraftcentral_receivepublickey': {'updraftcentral_receivepublickey', 'nopriv_updraftcentral_receivepublickey'}}
*
***/

/** Function handle_ajax_requests() called by wp_ajax hooks: {'wp_optimize_ajax'} **/
/** No params detected :-/ **/


/** Function updraft_smush_ajax() called by wp_ajax hooks: {'updraft_smush_ajax'} **/
/** No params detected :-/ **/


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


/** Function wp_ajax_updraftcentral_receivepublickey() called by wp_ajax hooks: {'updraftcentral_receivepublickey', 'nopriv_updraftcentral_receivepublickey'} **/
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


