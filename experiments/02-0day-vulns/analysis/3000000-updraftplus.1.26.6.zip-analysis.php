<?php
/***
*
*Found actions: 17
*Found functions:13
*Extracted functions:13
*Total parameter names extracted: 6
*Overview: {'wp_ajax_updraftcentral_receivepublickey': {'updraftcentral_receivepublickey', 'nopriv_updraftcentral_receivepublickey'}, 'updraftplus_user_notice_ajax': {'updraftplus_user_notice_ajax'}, 'updraft_ajaxrestore': {'nopriv_updraft_ajaxrestore_continue', 'updraft_ajaxrestore_continue', 'nopriv_updraft_ajaxrestore', 'updraft_ajaxrestore'}, 'updraft_ajax_savesettings': {'updraft_savesettings'}, 'wp_ajax_dashboard_widgets_high_priority': {'dashboard-widgets'}, 'updraft_ajax_handler': {'updraft_ajax'}, 'updraft_download_backup': {'updraft_download_backup'}, 'plupload_action2': {'plupload_action2'}, 'plupload_action': {'plupload_action'}, 'updraft_ajax_importsettings': {'updraft_importsettings'}, 'updraft_central_ajax_handler': {'updraft_central_ajax'}, 'updraftplus_dash_notice_ajax': {'updraftplus_dash_notice_ajax'}, 'wp_ajax_dashboard_widgets_low_priority': {'dashboard-widgets'}}
*
***/

/** Function wp_ajax_updraftcentral_receivepublickey() called by wp_ajax hooks: {'updraftcentral_receivepublickey', 'nopriv_updraftcentral_receivepublickey'} **/
/** No params detected :-/ **/


/** Function updraftplus_user_notice_ajax() called by wp_ajax hooks: {'updraftplus_user_notice_ajax'} **/
/** No params detected :-/ **/


/** Function updraft_ajaxrestore() called by wp_ajax hooks: {'nopriv_updraft_ajaxrestore_continue', 'updraft_ajaxrestore_continue', 'nopriv_updraft_ajaxrestore', 'updraft_ajaxrestore'} **/
/** No params detected :-/ **/


/** Function updraft_ajax_savesettings() called by wp_ajax hooks: {'updraft_savesettings'} **/
/** Parameters found in function updraft_ajax_savesettings(): {"post": ["subaction"]} **/
function updraft_ajax_savesettings() {
		try {
			$post_nonce = UpdraftPlus_Manipulation_Functions::fetch_superglobal('post', 'nonce');
			if (empty($_POST) || empty($_POST['subaction']) || 'savesettings' != $_POST['subaction'] || !isset($post_nonce) || !is_user_logged_in() || !UpdraftPlus_Options::user_can_manage() || !wp_verify_nonce($post_nonce, 'updraftplus-settings-nonce')) die('Security check');
			$post_settings = UpdraftPlus_Manipulation_Functions::fetch_superglobal('post', 'settings');
			if (empty($post_settings) || !is_string($post_settings)) die('Invalid data');
	
			parse_str(stripslashes($post_settings), $posted_settings);
			// We now have $posted_settings as an array
			$updraftplus_version = UpdraftPlus_Manipulation_Functions::fetch_superglobal('post', 'updraftplus_version');
			if (!empty($updraftplus_version)) $posted_settings['updraftplus_version'] = $updraftplus_version;
			
			echo json_encode($this->save_settings($posted_settings));
		} catch (Exception $e) {
			$log_message = 'PHP Fatal Exception error ('.get_class($e).') has occurred during save settings. Error Message: '.$e->getMessage().' (Code: '.$e->getCode().', line '.$e->getLine().' in '.$e->getFile().')';
			UpdraftPlus_Manipulation_Functions::error_log($log_message);
			echo json_encode(array(
				'fatal_error' => true,
				'fatal_error_message' => $log_message
			));
		} catch (Error $e) { // phpcs:ignore PHPCompatibility.Classes.NewClasses.errorFound -- The Error class does not exist in PHP below 5.6.
			$log_message = 'PHP Fatal error ('.get_class($e).') has occurred during save settings. Error Message: '.$e->getMessage().' (Code: '.$e->getCode().', line '.$e->getLine().' in '.$e->getFile().')';
			UpdraftPlus_Manipulation_Functions::error_log($log_message);
			echo json_encode(array(
				'fatal_error' => true,
				'fatal_error_message' => $log_message
			));
		}
		die;
	}


/** Function wp_ajax_dashboard_widgets_high_priority() called by wp_ajax hooks: {'dashboard-widgets'} **/
/** No params detected :-/ **/


/** Function updraft_ajax_handler() called by wp_ajax hooks: {'updraft_ajax'} **/
/** Parameters found in function updraft_ajax_handler(): {"request": ["curl"]} **/
function updraft_ajax_handler() {
		$request_nonce = UpdraftPlus_Manipulation_Functions::fetch_superglobal('request', 'nonce');
		$subaction = UpdraftPlus_Manipulation_Functions::fetch_superglobal('request', 'subaction');

		$nonce = empty($request_nonce) ? '' : $request_nonce;

		if (!wp_verify_nonce($nonce, 'updraftplus-credentialtest-nonce') || empty($subaction)) die('Security check');

		// Mitigation in case the nonce leaked to an unauthorised user
		if ('dismissautobackup' == $subaction) {
			if (!current_user_can('update_plugins') && !current_user_can('update_themes')) return;
		} elseif ('dismissexpiry' == $subaction || 'dismissdashnotice' == $subaction) {
			if (!current_user_can('update_plugins')) return;
		} else {
			if (!UpdraftPlus_Options::user_can_manage()) return;
		}
		
		// All others use _POST
		$data_in_get = array('get_log', 'get_fragment');
		
		// UpdraftPlus_WPAdmin_Commands extends UpdraftPlus_Commands - i.e. all commands are in there
		if (!class_exists('UpdraftPlus_WPAdmin_Commands')) updraft_try_include_file('includes/class-wpadmin-commands.php', 'include_once');
		$commands = new UpdraftPlus_WPAdmin_Commands($this);
		
		if (method_exists($commands, $subaction)) {

			$data = in_array($subaction, $data_in_get) ? $_GET : $_POST;
			
			// Undo WP's slashing of GET/POST data
			$data = UpdraftPlus_Manipulation_Functions::wp_unslash($data);
			
			// TODO: Once all commands come through here and through updraft_send_command(), the data should always come from this attribute (once updraft_send_command() is modified appropriately).
			if (isset($data['action_data'])) $data = $data['action_data'];
			try {
				$results = call_user_func(array($commands, $subaction), $data);
			} catch (Exception $e) {
				$log_message = 'PHP Fatal Exception error ('.get_class($e).') has occurred during '.$subaction.' subaction. Error Message: '.$e->getMessage().' (Code: '.$e->getCode().', line '.$e->getLine().' in '.$e->getFile().')';
				UpdraftPlus_Manipulation_Functions::error_log($log_message);
				echo json_encode(array(
					'fatal_error' => true,
					'fatal_error_message' => $log_message
				));
				die;
			} catch (Error $e) { // phpcs:ignore PHPCompatibility.Classes.NewClasses.errorFound -- The Error class does not exist in PHP below 5.6.
				$log_message = 'PHP Fatal error ('.get_class($e).') has occurred during '.$subaction.' subaction. Error Message: '.$e->getMessage().' (Code: '.$e->getCode().', line '.$e->getLine().' in '.$e->getFile().')';
				UpdraftPlus_Manipulation_Functions::error_log($log_message);
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
				echo $results; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped --the escaping should take place at the time the caller of this ajax handler receives a response from its request possibly after calling wp_remote_*() or JS jQuery.ajax()
			} else {
				echo json_encode($results);
			}
			die;
		}
		
		// Below are all the commands not ported over into class-commands.php or class-wpadmin-commands.php
		$request_subsubaction = UpdraftPlus_Manipulation_Functions::fetch_superglobal('request', 'subsubaction');
		if ('activejobs_list' == $subaction) {
			try {
				// N.B. Also called from autobackup.php
				// TODO: This should go into UpdraftPlus_Commands, once the add-ons have been ported to use updraft_send_command()
				echo json_encode($this->get_activejobs_list(UpdraftPlus_Manipulation_Functions::wp_unslash($_GET)));
			} catch (Exception $e) {
				$log_message = 'PHP Fatal Exception error ('.get_class($e).') has occurred during get active job list. Error Message: '.$e->getMessage().' (Code: '.$e->getCode().', line '.$e->getLine().' in '.$e->getFile().')';
				UpdraftPlus_Manipulation_Functions::error_log($log_message);
				echo json_encode(array(
					'fatal_error' => true,
					'fatal_error_message' => $log_message
				));
			} catch (Error $e) { // phpcs:ignore PHPCompatibility.Classes.NewClasses.errorFound -- The Error class does not exist in PHP below 5.6.
				$log_message = 'PHP Fatal error ('.get_class($e).') has occurred during get active job list. Error Message: '.$e->getMessage().' (Code: '.$e->getCode().', line '.$e->getLine().' in '.$e->getFile().')';
				UpdraftPlus_Manipulation_Functions::error_log($log_message);
				echo json_encode(array(
					'fatal_error' => true,
					'fatal_error_message' => $log_message
				));
			}
			
		} elseif ('httpget' == $subaction) {
			try {
				// httpget
				$curl = empty($_REQUEST['curl']) ? false : true;
				$request_uri = UpdraftPlus_Manipulation_Functions::fetch_superglobal('request', 'uri');
				echo $this->http_get(UpdraftPlus_Manipulation_Functions::wp_unslash($request_uri), $curl); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- It's not HTML content; the output is in JSON format which can't be escaped
			} catch (Error $e) { // phpcs:ignore PHPCompatibility.Classes.NewClasses.errorFound -- The Error class does not exist in PHP below 5.6.
				$log_message = 'PHP Fatal error ('.get_class($e).') has occurred during http get. Error Message: '.$e->getMessage().' (Code: '.$e->getCode().', line '.$e->getLine().' in '.$e->getFile().')';
				UpdraftPlus_Manipulation_Functions::error_log($log_message);
				echo json_encode(array(
					'fatal_error' => true,
					'fatal_error_message' => $log_message
				));
			} catch (Exception $e) {
				$log_message = 'PHP Fatal Exception error ('.get_class($e).') has occurred during http get. Error Message: '.$e->getMessage().' (Code: '.$e->getCode().', line '.$e->getLine().' in '.$e->getFile().')';
				UpdraftPlus_Manipulation_Functions::error_log($log_message);
				echo json_encode(array(
					'fatal_error' => true,
					'fatal_error_message' => $log_message
				));
			}
			 
		} elseif ('doaction' == $subaction && !empty($request_subsubaction) && 'updraft_' == substr($request_subsubaction, 0, 8)) {
			$subsubaction = $request_subsubaction;
			try {
					// These generally echo and die - they will need further work to port to one of the command classes. Some may already have equivalents in UpdraftPlus_Commands, if they are used from UpdraftCentral.
				do_action(UpdraftPlus_Manipulation_Functions::wp_unslash($subsubaction), $_REQUEST);
			} catch (Exception $e) {
				$log_message = 'PHP Fatal Exception error ('.get_class($e).') has occurred during doaction subaction with '.$subsubaction.' subsubaction. Error Message: '.$e->getMessage().' (Code: '.$e->getCode().', line '.$e->getLine().' in '.$e->getFile().')';
				UpdraftPlus_Manipulation_Functions::error_log($log_message);
				echo json_encode(array(
					'fatal_error' => true,
					'fatal_error_message' => $log_message
				));
				die;
			} catch (Error $e) { // phpcs:ignore PHPCompatibility.Classes.NewClasses.errorFound -- The Error class does not exist in PHP below 5.6.
				$log_message = 'PHP Fatal error ('.get_class($e).') has occurred during doaction subaction with '.$subsubaction.' subsubaction. Error Message: '.$e->getMessage().' (Code: '.$e->getCode().', line '.$e->getLine().' in '.$e->getFile().')';
				UpdraftPlus_Manipulation_Functions::error_log($log_message);
				echo json_encode(array(
					'fatal_error' => true,
					'fatal_error_message' => $log_message
				));
				die;
			}
		}
		
		die;

	}


/** Function updraft_download_backup() called by wp_ajax hooks: {'updraft_download_backup'} **/
/** Parameters found in function updraft_download_backup(): {"request": ["timestamp", "type"]} **/
function updraft_download_backup() {
		
		if (!UpdraftPlus_Options::user_can_manage()) die('Unauthorised.');
		
		try {
			$global_wp_nonce = UpdraftPlus_Manipulation_Functions::fetch_superglobal('request', '_wpnonce');
			if (empty($global_wp_nonce) || !wp_verify_nonce($global_wp_nonce, 'updraftplus_download')) die('Unauthorised.');
	
			if (empty($_REQUEST['timestamp']) || !is_numeric($_REQUEST['timestamp']) || empty($_REQUEST['type'])) die;
			$findex = UpdraftPlus_Manipulation_Functions::fetch_superglobal('request', 'findex');
			$findexes = empty($findex) ? array(0) : $findex;
			$stage = UpdraftPlus_Manipulation_Functions::fetch_superglobal('request', 'stage', '', false, 'string', 'sanitize_text_field');
			$file_path = UpdraftPlus_Manipulation_Functions::fetch_superglobal('request', 'filepath', '', false, 'string', null);
			
			$type = UpdraftPlus_Manipulation_Functions::fetch_superglobal('request', 'type', '', false, 'string', null);
			// This call may not actually return, depending upon what mode it is called in
			$result = $this->do_updraft_download_backup($findexes, $type, (int) $_REQUEST['timestamp'], $stage, false, $file_path);
			
			// In theory, if a response was already sent, then Connection: close has been issued, and a Content-Length. However, in https://updraftplus.com/forums/topic/pclzip_err_bad_format-10-invalid-archive-structure/ a browser ignores both of these, and then picks up the second output and complains.
			if (empty($result['already_closed'])) echo json_encode($result);
		} catch (Exception $e) {
			$log_message = 'PHP Fatal Exception error ('.get_class($e).') has occurred during download backup. Error Message: '.$e->getMessage().' (Code: '.$e->getCode().', line '.$e->getLine().' in '.$e->getFile().')';
			UpdraftPlus_Manipulation_Functions::error_log($log_message);
			echo json_encode(array(
				'fatal_error' => true,
				'fatal_error_message' => $log_message
			));
		} catch (Error $e) { // phpcs:ignore PHPCompatibility.Classes.NewClasses.errorFound -- The Error class does not exist in PHP below 5.6.
			$log_message = 'PHP Fatal error ('.get_class($e).') has occurred during download backup. Error Message: '.$e->getMessage().' (Code: '.$e->getCode().', line '.$e->getLine().' in '.$e->getFile().')';
			UpdraftPlus_Manipulation_Functions::error_log($log_message);
			echo json_encode(array(
				'fatal_error' => true,
				'fatal_error_message' => $log_message
			));
		}
		die();
	}


/** Function plupload_action2() called by wp_ajax hooks: {'plupload_action2'} **/
/** Parameters found in function plupload_action2(): {"post": ["chunks"]} **/
function plupload_action2() {

		if (function_exists('set_time_limit')) @set_time_limit(UPDRAFTPLUS_SET_TIME_LIMIT);// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- Silenced to suppress errors that may arise because of the function.
		global $updraftplus;

		if (!UpdraftPlus_Options::user_can_manage()) return;
		check_ajax_referer('updraft-uploader');

		$updraft_dir = $updraftplus->backups_dir_location();
		if (!is_writable($updraft_dir)) exit;

		add_filter('upload_dir', array($this, 'upload_dir'));
		add_filter('sanitize_file_name', array($this, 'sanitize_file_name'));
		// handle file upload

		$farray = array('test_form' => true, 'action' => 'plupload_action2');

		$farray['test_type'] = false;
		$farray['ext'] = 'crypt';
		$farray['type'] = 'application/octet-stream';

		if (isset($_POST['chunks'])) {
			// $farray['ext'] = 'zip';
			// $farray['type'] = 'application/zip';
		} else {
			$farray['unique_filename_callback'] = array($this, 'unique_filename_callback');
		}
		$file = UpdraftPlus_Manipulation_Functions::fetch_superglobal('files', 'async-upload', array(), false, 'array');
		$status = wp_handle_upload($file, $farray);
		remove_filter('upload_dir', array($this, 'upload_dir'));
		remove_filter('sanitize_file_name', array($this, 'sanitize_file_name'));

		if (isset($status['error'])) die('ERROR: '.wp_kses_post($status['error']));

		// If this was the chunk, then we should instead be concatenating onto the final file
		$chunk = UpdraftPlus_Manipulation_Functions::fetch_superglobal('post', 'chunk');
		if (isset($_POST['chunks']) && isset($chunk) && preg_match('/^[0-9]+$/', $chunk)) {
			$post_name = UpdraftPlus_Manipulation_Functions::fetch_superglobal('post', 'name');
			$final_file = basename($post_name);
			rename($status['file'], $updraft_dir.'/'.$final_file.'.'.$chunk.'.zip.tmp');
			$status['file'] = $updraft_dir.'/'.$final_file.'.'.$chunk.'.zip.tmp';
		}

		if (!isset($_POST['chunks']) || (isset($chunk) && $chunk == $_POST['chunks']-1)) {
			if (!preg_match('/^backup_([\-0-9]{15})_.*_([0-9a-f]{12})-db([0-9]+)?\.(gz\.crypt)$/i', $final_file)) {

				@unlink($status['file']);// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- Silenced to suppress errors that may arise if the file doesn't exist.
				echo 'ERROR:'.wp_kses_post(__('Bad filename format - this does not look like an encrypted database file created by UpdraftPlus', 'updraftplus'));
				exit;
			}
			
			// Final chunk? If so, then stich it all back together
			if (isset($chunk) && $chunk == $_POST['chunks']-1 && isset($final_file)) {
				if ($wh = fopen($updraft_dir.'/'.$final_file, 'wb')) {
					for ($i=0; $i<$_POST['chunks']; $i++) {
						$rf = $updraft_dir.'/'.$final_file.'.'.$i.'.zip.tmp';
						if ($rh = fopen($rf, 'rb')) {
							while ($line = fread($rh, 524288)) {
								fwrite($wh, $line);
							}
							fclose($rh);
							@unlink($rf);// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- Silenced to suppress errors that may arise if the file doesn't exist.
						}
					}
					fclose($wh);
				}
			}
			
		}

		// send the uploaded file url in response
		if (isset($final_file)) echo 'OK:'.wp_kses_post($final_file);
		exit;
	}


/** Function plupload_action() called by wp_ajax hooks: {'plupload_action'} **/
/** Parameters found in function plupload_action(): {"post": ["chunks", "chunk"]} **/
function plupload_action() {

		global $updraftplus;
		if (function_exists('set_time_limit')) @set_time_limit(UPDRAFTPLUS_SET_TIME_LIMIT);// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- Silenced to suppress errors that may arise because of the function.

		if (!UpdraftPlus_Options::user_can_manage()) return;
		check_ajax_referer('updraft-uploader');

		$updraft_dir = $updraftplus->backups_dir_location();
		if (!@UpdraftPlus_Filesystem_Functions::really_is_writable($updraft_dir)) {// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- Silenced to suppress errors that may arise because of the method.
			echo json_encode(
				array(
					'e' => sprintf(
						/* translators: %s: Backup directory path */
						__("Backup directory (%s) is not writable, or does not exist.", 'updraftplus'),
						$updraft_dir
					).' '.__('You will find more information about this in the Settings section.', 'updraftplus')
				)
			);
			exit;
		}
		
		add_filter('upload_dir', array($this, 'upload_dir'));
		add_filter('sanitize_file_name', array($this, 'sanitize_file_name'));
		// handle file upload

		$farray = array('test_form' => true, 'action' => 'plupload_action');

		$farray['test_type'] = false;
		$farray['ext'] = 'x-gzip';
		$farray['type'] = 'application/octet-stream';

		if (!isset($_POST['chunks'])) {
			$farray['unique_filename_callback'] = array($this, 'unique_filename_callback');
		}
		$file = UpdraftPlus_Manipulation_Functions::fetch_superglobal('files', 'async-upload', array(), false, 'array');
		$status = wp_handle_upload($file, $farray);
		remove_filter('upload_dir', array($this, 'upload_dir'));
		remove_filter('sanitize_file_name', array($this, 'sanitize_file_name'));

		if (isset($status['error'])) {
			echo json_encode(array('e' => $status['error']));
			exit;
		}

		// If this was the chunk, then we should instead be concatenating onto the final file
		$chunk = UpdraftPlus_Manipulation_Functions::fetch_superglobal('post', 'chunk');
		if (isset($_POST['chunks']) && isset($chunk) && preg_match('/^[0-9]+$/', $chunk)) {
			$post_name = UpdraftPlus_Manipulation_Functions::fetch_superglobal('post', 'name');
			$final_file = basename($post_name);
			
			if (!rename($status['file'], $updraft_dir.'/'.$final_file.'.'.$chunk.'.zip.tmp')) {
				@unlink($status['file']);// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- Silenced to suppress errors that may arise if the file doesn't exist.
				echo json_encode(
					array(
						'e' => sprintf(
							/* translators: %s: Error message */
							__('Error: %s', 'updraftplus'),
							__('This file could not be uploaded', 'updraftplus')
						)
					)
				);
				exit;
			}
			
			$status['file'] = $updraft_dir.'/'.$final_file.'.'.$chunk.'.zip.tmp';

		}

		$response = array();
		if (!isset($_POST['chunks']) || (isset($chunk) && preg_match('/^[0-9]+$/', $chunk) && $chunk == $_POST['chunks']-1) && isset($final_file)) {
			if (!preg_match('/^log\.[a-f0-9]{12}\.txt/i', $final_file) && !preg_match('/^backup_([\-0-9]{15})_.*_([0-9a-f]{12})-([\-a-z]+)([0-9]+)?(\.(zip|gz|gz\.crypt))?$/i', $final_file, $matches)) {
				$accept = apply_filters('updraftplus_accept_archivename', array());
				if (is_array($accept)) {
					foreach ($accept as $acc) {
						if (preg_match('/'.$acc['pattern'].'/i', $final_file)) {
							/* translators: %s: Backup tool name */
							$response['dm'] = sprintf(__('This backup was created by %s, and can be imported.', 'updraftplus'), $acc['desc']);
						}
					}
				}
				if (empty($response['dm'])) {
					if (isset($status['file'])) @unlink($status['file']);// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- Silenced to suppress errors that may arise if the file doesn't exist.
					echo json_encode(
						array(
							'e' => sprintf(
								/* translators: %s: Error message */
								__('Error: %s', 'updraftplus'),
								__('Bad filename format - this does not look like a file created by UpdraftPlus', 'updraftplus')
							)
						)
					);
					exit;
				}
			} else {
				$backupable_entities = $updraftplus->get_backupable_file_entities(true);
				$type = isset($matches[3]) ? $matches[3] : '';
				if (!preg_match('/^log\.[a-f0-9]{12}\.txt/', $final_file) && 'db' != $type && !isset($backupable_entities[$type])) {
					if (isset($status['file'])) @unlink($status['file']);// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- Silenced to suppress errors that may arise because of the function.
					echo json_encode(
						array(
							'e' => sprintf(
								/* translators: %s: Error message */
								__('Error: %s', 'updraftplus'),
								sprintf(
									/* translators: %s: Object type */
									__('This looks like a file created by UpdraftPlus, but this install does not know about this type of object: %s.', 'updraftplus'),
									htmlspecialchars($type)
								).' '.
								__('Perhaps you need to install an add-on?', 'updraftplus')
							)
						)
					);
					exit;
				}
			}
			
			// Final chunk? If so, then stich it all back together
			if (isset($_POST['chunk']) && $_POST['chunk'] == $_POST['chunks']-1 && !empty($final_file)) {
				if ($wh = fopen($updraft_dir.'/'.$final_file, 'wb')) {
					for ($i = 0; $i < $_POST['chunks']; $i++) {
						$rf = $updraft_dir.'/'.$final_file.'.'.$i.'.zip.tmp';
						if ($rh = fopen($rf, 'rb+')) {

							// April 1st 2020 - Due to a bug during uploads to Dropbox some backups had string "null" appended to the end which caused warnings, this removes the string "null" from these backups
							fseek($rh, -4, SEEK_END);
							$data = fgets($rh, 5);
							
							if ("null" === $data) {
								ftruncate($rh, filesize($rf) - 4);
							}

							fseek($rh, 0, SEEK_SET);
							
							while ($line = fread($rh, 524288)) {
								fwrite($wh, $line);
							}
							fclose($rh);
							@unlink($rf);// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- Silenced to suppress errors that may arise if the file doesn't exist.
						}
					}
					fclose($wh);
					$status['file'] = $updraft_dir.'/'.$final_file;
					if ('.tar' == substr($final_file, -4, 4)) {
						if (file_exists($status['file'].'.gz')) unlink($status['file'].'.gz');
						if (file_exists($status['file'].'.bz2')) unlink($status['file'].'.bz2');
					} elseif ('.tar.gz' == substr($final_file, -7, 7)) {
						if (file_exists(substr($status['file'], 0, strlen($status['file'])-3))) unlink(substr($status['file'], 0, strlen($status['file'])-3));
						if (file_exists(substr($status['file'], 0, strlen($status['file'])-3).'.bz2')) unlink(substr($status['file'], 0, strlen($status['file'])-3).'.bz2');
					} elseif ('.tar.bz2' == substr($final_file, -8, 8)) {
						if (file_exists(substr($status['file'], 0, strlen($status['file'])-4))) unlink(substr($status['file'], 0, strlen($status['file'])-4));
						if (file_exists(substr($status['file'], 0, strlen($status['file'])-4).'.gz')) unlink(substr($status['file'], 0, strlen($status['file'])-3).'.gz');
					}
				}
			}
			
		}

		// send the uploaded file url in response
		$response['m'] = $status['url'];
		echo json_encode($response);
		exit;
	}


/** Function updraft_ajax_importsettings() called by wp_ajax hooks: {'updraft_importsettings'} **/
/** Parameters found in function updraft_ajax_importsettings(): {"post": ["subaction", "settings"]} **/
function updraft_ajax_importsettings() {
		try {
			$post_nonce = UpdraftPlus_Manipulation_Functions::fetch_superglobal('post', 'nonce');
			if (empty($_POST) || empty($_POST['subaction']) || 'importsettings' != $_POST['subaction'] || !isset($post_nonce) || !is_user_logged_in() || !UpdraftPlus_Options::user_can_manage() || !wp_verify_nonce($post_nonce, 'updraftplus-settings-nonce')) die('Security check');
			 
			if (empty($_POST['settings']) || !is_string($_POST['settings'])) die('Invalid data');
	
			$this->import_settings($_POST);
		} catch (Exception $e) {
			$log_message = 'PHP Fatal Exception error ('.get_class($e).') has occurred during import settings. Error Message: '.$e->getMessage().' (Code: '.$e->getCode().', line '.$e->getLine().' in '.$e->getFile().')';
			UpdraftPlus_Manipulation_Functions::error_log($log_message);
			echo json_encode(array(
				'fatal_error' => true,
				'fatal_error_message' => $log_message
			));
		} catch (Error $e) { // phpcs:ignore PHPCompatibility.Classes.NewClasses.errorFound -- The Error class does not exist in PHP below 5.6.
			$log_message = 'PHP Fatal error ('.get_class($e).') has occurred during import settings. Error Message: '.$e->getMessage().' (Code: '.$e->getCode().', line '.$e->getLine().' in '.$e->getFile().')';
			UpdraftPlus_Manipulation_Functions::error_log($log_message);
			echo json_encode(array(
				'fatal_error' => true,
				'fatal_error_message' => $log_message
			));
		}
	}


/** Function updraft_central_ajax_handler() called by wp_ajax hooks: {'updraft_central_ajax'} **/
/** No params detected :-/ **/


/** Function updraftplus_dash_notice_ajax() called by wp_ajax hooks: {'updraftplus_dash_notice_ajax'} **/
/** No params detected :-/ **/


/** Function wp_ajax_dashboard_widgets_low_priority() called by wp_ajax hooks: {'dashboard-widgets'} **/
/** No params detected :-/ **/


