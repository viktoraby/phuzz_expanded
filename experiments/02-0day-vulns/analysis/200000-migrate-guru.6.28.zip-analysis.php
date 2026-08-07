<?php
/***
*
*Found actions: 4
*Found functions:4
*Extracted functions:4
*Total parameter names extracted: 2
*Overview: {'bvAdmExecuteWithUser': {'bvadm'}, 'ajaxInitiateMigration': {'mg_initiate_migration'}, 'ajaxValidateKey': {'mg_validate_key'}, 'bvAdmExecuteWithoutUser': {'nopriv_bvadm'}}
*
***/

/** Function bvAdmExecuteWithUser() called by wp_ajax hooks: {'bvadm'} **/
/** No params detected :-/ **/


/** Function ajaxInitiateMigration() called by wp_ajax hooks: {'mg_initiate_migration'} **/
/** Parameters found in function ajaxInitiateMigration(): {"post": ["nonce", "email", "destination_key", "source_key", "site_info"]} **/
function ajaxInitiateMigration() {
		// Verify nonce
		if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'mg_onboarding_nonce')) {
			wp_send_json_error(array('message' => 'Security check failed.'));
			return;
		}

		// Get form data
		$email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
		$destination_key = isset($_POST['destination_key']) ? sanitize_text_field(wp_unslash($_POST['destination_key'])) : '';
		$source_key = isset($_POST['source_key']) ? sanitize_text_field(wp_unslash($_POST['source_key'])) : '';
		$site_info = isset($_POST['site_info']) ? array_map('sanitize_text_field', wp_unslash($_POST['site_info'])) : array();

		// Validate email
		if (empty($email) || !is_email($email)) {
			wp_send_json_error(array('message' => 'Valid email address is required.'));
			return;
		}

		// Validate keys
		if (empty($destination_key) || empty($source_key)) {
			wp_send_json_error(array('message' => 'Migration keys are required.'));
			return;
		}

		// Check if destination key matches source key (same site)
		if ($destination_key === $source_key) {
			wp_send_json_error(array('message' => 'You cannot use the same site key. Please enter the migration key from your destination site (the site you are migrating to).'));
			return;
		}

		$validation_result = $this->validate_api_key($destination_key);
		if (is_wp_error($validation_result)) {
			wp_send_json_error(array('message' => $validation_result->get_error_message()));
			return;
		}

		// Prepare data for API call
		$api_data = array(
			'email' => $email,
			'bv_dest_migration_key' => $destination_key,
			'bv_src_migration_key' => $source_key
		);

		// Add site info to API data
		$api_data = array_merge($api_data, $site_info);

		// Make API call to key_based_migration endpoint
		$api_url = $this->bvinfo->appUrl() . '/migration/execute';

		// Use WordPress HTTP API directly if bvapi not available
		if ($this->bvapi && method_exists($this->bvapi, 'http_request')) {
			$response = $this->bvapi->http_request($api_url, $api_data);
		} else {
			// Fallback to wp_remote_post
			$response = wp_remote_post($api_url, array(
				'method' => 'POST',
				'timeout' => 15,
				'body' => $api_data
			));
		}

		// Check response
		if (is_wp_error($response)) {
			wp_send_json_error(array(
				'message' => 'Failed to connect to migration service: ' . $response->get_error_message()
			));
			return;
		}

		$response_code = wp_remote_retrieve_response_code($response);
		$response_body = wp_remote_retrieve_body($response);
		$response_data = json_decode($response_body, true);

		if ($response_code === 200 && isset($response_data['message'])) {
			$success_data = array(
				'message' => $response_data['message']
			);
			// Include URL if present in response
			if (isset($response_data['url'])) {
				$success_data['url'] = esc_url_raw($response_data['url']);
			}
			wp_send_json_success($success_data);
		} else {
			// Handle both direct message and nested error.message structure
			$error_message = 'Migration initiation failed. Please try again.';
			
			if (isset($response_data['error']['message'])) {
				// Rails render_bad_request format: { "error": { "message": "..." } }
				$error_message = $response_data['error']['message'];
			} elseif (isset($response_data['message'])) {
				// Direct message format: { "message": "..." }
				$error_message = $response_data['message'];
			} elseif (isset($response_data['error']) && is_string($response_data['error'])) {
				// Simple error string: { "error": "..." }
				$error_message = $response_data['error'];
			}
			
			wp_send_json_error(array(
				'message' => $error_message
			));
		}
	}


/** Function ajaxValidateKey() called by wp_ajax hooks: {'mg_validate_key'} **/
/** Parameters found in function ajaxValidateKey(): {"post": ["nonce", "destination_key", "source_key"]} **/
function ajaxValidateKey() {
		// Verify nonce
		if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'mg_onboarding_nonce')) {
			wp_send_json_error(array('message' => 'Security check failed.'));
			return;
		}

		// Get keys
		$destination_key = isset($_POST['destination_key']) ? sanitize_text_field(wp_unslash($_POST['destination_key'])) : '';
		$source_key = isset($_POST['source_key']) ? sanitize_text_field(wp_unslash($_POST['source_key'])) : '';

		if (empty($destination_key) || empty($source_key)) {
			wp_send_json_error(array('message' => 'Migration keys are required.'));
			return;
		}

		// Check if destination key matches source key (same site)
		if ($destination_key === $source_key) {
			wp_send_json_error(array('message' => 'You cannot use the same site key. Please enter the migration key from your destination site (the site you are migrating to).'));
			return;
		}

		$validation_result = $this->validate_api_key($destination_key);
		if (is_wp_error($validation_result)) {
			wp_send_json_error(array('message' => $validation_result->get_error_message()));
			return;
		}

		// Success
		wp_send_json_success(array(
			'message' => 'Keys validated successfully.',
			'destination_key' => $destination_key
		));
	}


/** Function bvAdmExecuteWithoutUser() called by wp_ajax hooks: {'nopriv_bvadm'} **/
/** No params detected :-/ **/


