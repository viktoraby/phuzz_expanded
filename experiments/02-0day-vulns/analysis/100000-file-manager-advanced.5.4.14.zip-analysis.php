<?php
/***
*
*Found actions: 8
*Found functions:7
*Extracted functions:7
*Total parameter names extracted: 7
*Overview: {'_toggle_debug_mode': {'fs_toggle_debug_mode'}, 'fma_save_php_file': {'fma_save_php_file'}, 'fma_load_fma_ui': {'fma_load_fma_ui'}, 'dismiss_notice_ajax_callback': {'fs_dismiss_notice_action_{$ajax_action_suffix}'}, 'fma_review_ajax': {'fma_review_ajax'}, 'fma_debug_php': {'fma_debug_php'}, 'request_post_smtp_ajax': {'nopriv_post_smtp_request', 'post_smtp_request'}}
*
***/

/** Function _toggle_debug_mode() called by wp_ajax hooks: {'fs_toggle_debug_mode'} **/
/** No params detected :-/ **/


/** Function fma_save_php_file() called by wp_ajax hooks: {'fma_save_php_file'} **/
/** Parameters found in function fma_save_php_file(): {"post": ["nonce", "php_code", "file_hash", "filename"]} **/
function fma_save_php_file()
	{
		if (!class_fma_permissions::user_has_file_manager_access()) {
			wp_send_json_error(array('message' => __('You do not have permission to access the file manager.', 'file-manager-advanced')));
			return;
		}

		if (!class_fma_permissions::has_unrestricted_filesystem_access()) {
			wp_send_json_error(array('message' => __('You do not have permission to edit PHP files.', 'file-manager-advanced')));
			return;
		}

		// Check nonce for security
		if (!wp_verify_nonce($_POST['nonce'], 'fmaskey')) {
			wp_send_json_error(array('message' => __('Security check failed', 'file-manager-advanced')));
			return;
		}

		// Get the PHP code and file info from POST data.
		// Keep php_code slashed here; connector applies wp_unslash once on put content.
		$php_code = isset($_POST['php_code']) ? $_POST['php_code'] : '';
		$file_hash = sanitize_text_field($_POST['file_hash']);
		$filename = sanitize_text_field(wp_unslash($_POST['filename']));

		// Skip validation since this is called when user chooses "Save Anyway"
		// The validation was already done and user explicitly chose to save with errors

		try {
			// Store original POST data
			$original_post = $_POST;

			// Set up POST data for elFinder save operation
			$_POST = array(
				'cmd' => 'put',
				'target' => $file_hash,
				'content' => $php_code,
				'action' => 'fma_load_fma_ui',
				'_fmakey' => wp_create_nonce('fmaskey')
			);

			// Use elFinder connector to save the file
			if (!class_exists('class_fma_connector')) {
				include_once 'class_fma_connector.php';
			}

			if (class_exists('class_fma_connector')) {
				$fma_connector = new class_fma_connector();

				// Capture elFinder output
				ob_start();
				$fma_connector->fma_local_file_system();
				$elfinder_response = ob_get_clean();

				// Restore original POST data
				$_POST = $original_post;

				// Parse elFinder response
				$response_data = json_decode($elfinder_response, true);

				if ($response_data && isset($response_data['changed']) && !empty($response_data['changed'])) {
					wp_send_json_success(array(
						'message' => __('File saved successfully', 'file-manager-advanced'),
						'elfinder_response' => $response_data
					));
				} else if ($response_data && !isset($response_data['error'])) {
					// Sometimes elFinder doesn't return 'changed' but save is successful
					wp_send_json_success(array(
						'message' => __('File saved successfully', 'file-manager-advanced'),
						'elfinder_response' => $response_data
					));
				} else {
					$error_message = '';
					if ($response_data && isset($response_data['error'])) {
						$error_message = $response_data['error'];
					} else {
						$error_message = __('Failed to save file through elFinder', 'file-manager-advanced');
					}

					wp_send_json_error(array(
						'message' => $error_message,
						'elfinder_response' => $elfinder_response
					));
				}
			} else {
				// Restore original POST data
				$_POST = $original_post;

				wp_send_json_error(array(
					'message' => __('elFinder connector class not found', 'file-manager-advanced')
				));
			}

		} catch (Exception $e) {
			// Restore original POST data in case of exception
			$_POST = $original_post;

			wp_send_json_error(array(
				'message' => sprintf(__('Error saving file: %s', 'file-manager-advanced'), $e->getMessage()),
				'exception' => $e->getMessage()
			));
		}
	}


/** Function fma_load_fma_ui() called by wp_ajax hooks: {'fma_load_fma_ui'} **/
/** Parameters found in function fma_load_fma_ui(): {"request": ["_fmakey"]} **/
function fma_load_fma_ui()
	{
		// Clear any buffered output so file/download streams stay binary-clean.
		while (ob_get_level()) {
			ob_end_clean();
		}

		class_fma_permissions::verify_ajax_access();

		$fmakey = isset($_REQUEST['_fmakey']) ? sanitize_text_field(wp_unslash($_REQUEST['_fmakey'])) : '';
		if ('' === $fmakey || !wp_verify_nonce($fmakey, 'fmaskey')) {
			wp_die(esc_html__('Security check failed', 'file-manager-advanced'), esc_html__('Forbidden', 'file-manager-advanced'), array('response' => 403));
		}

		include 'class_fma_connector.php';
		$fma_connector = new class_fma_connector();
		$fma_connector->fma_local_file_system();
	}


/** Function dismiss_notice_ajax_callback() called by wp_ajax hooks: {'fs_dismiss_notice_action_{$ajax_action_suffix}'} **/
/** Parameters found in function dismiss_notice_ajax_callback(): {"post": ["message_id"]} **/
function dismiss_notice_ajax_callback() {
            check_admin_referer( 'fs_dismiss_notice_action' );

            if ( ! is_numeric( $_POST['message_id'] ) ) {
                $this->_sticky_storage->remove( $_POST['message_id'] );
            }

            wp_die();
        }


/** Function fma_review_ajax() called by wp_ajax hooks: {'fma_review_ajax'} **/
/** Parameters found in function fma_review_ajax(): {"request": ["nonce"], "post": ["task"]} **/
function fma_review_ajax()
	{
		$nonce = $_REQUEST['nonce'];
		if (!wp_verify_nonce($nonce, 'afm_review')) {
			die(__('Security check', 'file-manager-advanced'));
		} else {
			$task = sanitize_text_field($_POST['task']);
			$done = update_option('fma_hide_review_section', $task);
			if ($done) {
				echo '1';
			} else {
				echo '0';
			}
			die;
		}
	}


/** Function fma_debug_php() called by wp_ajax hooks: {'fma_debug_php'} **/
/** Parameters found in function fma_debug_php(): {"post": ["nonce", "php_code", "filename"]} **/
function fma_debug_php()
	{
		class_fma_permissions::verify_ajax_access();

		// Check nonce for security
		if (!wp_verify_nonce($_POST['nonce'], 'fmaskey')) {
			wp_die(__('Security check failed', 'file-manager-advanced'));
		}

		// Get the PHP code from POST data
		$php_code = wp_unslash($_POST['php_code']);
		$filename = sanitize_text_field($_POST['filename']);

		// Load the debug analyzer
		require_once FMAFILEPATH . 'application/library/php-parser/src/FMA_PhpDebugAnalyzer.php';

		// Analyze PHP code for debug information
		$debug_result = FMA_PhpDebugAnalyzer::analyze($php_code, $filename);

		// Return JSON response
		wp_send_json($debug_result);
	}


/** Function request_post_smtp_ajax() called by wp_ajax hooks: {'nopriv_post_smtp_request', 'post_smtp_request'} **/
/** Parameters found in function request_post_smtp_ajax(): {"post": ["nonce", "status", "grant_consent"]} **/
function request_post_smtp_ajax()
        {
            if (!isset($_POST['nonce'])) {
                wp_send_json_error(array('message' => 'No nonce provided'));
            }

            if (!wp_verify_nonce($_POST['nonce'], 'post_smtp_request_nonce')) {
                wp_send_json_error(array('message' => 'Security check failed'));
            }

            if (!current_user_can('manage_options')) {
                wp_send_json_error(array('message' => 'Insufficient permissions'));
            }

            // Check if status is provided
            if (!isset($_POST['status'])) {
                wp_send_json_error(array('message' => 'No status provided'));
            }

            // Handle consent grant request
            if (isset($_POST['grant_consent']) && $_POST['grant_consent'] === '1') {
                self::grant_data_consent();
            }

            // Only send data to external server if admin has given consent
            if (!self::has_data_consent()) {
                wp_send_json_success(array(
                    'message' => __('Action completed (data sharing skipped — no consent)', 'file-manager-advanced'),
                    'consent' => false
                ));
                return;
            }

            $site_url = get_bloginfo('url');
            $status = sanitize_text_field($_POST['status']);
            $plugin_slug = $this->slug;
            $secret_key = self::get_site_secret_key();

            $response = wp_remote_post("https://connect.postmansmtp.com/wp-json/update/v1/update?site_url={$site_url}&status={$status}&plugin_slug={$plugin_slug}", array(
                'method' => 'POST',
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Secret-Key' => $secret_key
                )
            ));

            if (is_wp_error($response)) {
                wp_send_json_error(array(
                    'message' => 'Failed to send request: ' . $response->get_error_message()
                ));
            } else {
                wp_send_json_success(array(
                    'message' => __('Request sent successfully', 'file-manager-advanced')
                ));
            }
        }


