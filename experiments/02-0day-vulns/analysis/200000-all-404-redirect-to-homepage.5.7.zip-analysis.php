<?php
/***
*
*Found actions: 4
*Found functions:4
*Extracted functions:4
*Total parameter names extracted: 2
*Overview: {'P404REDIRECT_HideMsg': {'P404REDIRECT_HideMsg'}, 'handle_delete_previous_404_image': {'delete_previous_404_image'}, 'P404REDIRECT_HideAlert': {'P404REDIRECT_HideAlert'}, 'handle_send_test_404_email': {'send_test_404_email'}}
*
***/

/** Function P404REDIRECT_HideMsg() called by wp_ajax hooks: {'P404REDIRECT_HideMsg'} **/
/** No params detected :-/ **/


/** Function handle_delete_previous_404_image() called by wp_ajax hooks: {'delete_previous_404_image'} **/
/** Parameters found in function handle_delete_previous_404_image(): {"post": ["nonce", "image_id", "option_name"]} **/
function handle_delete_previous_404_image()
{
	// Check nonce for security
	if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'delete_404_image_nonce')) {
		wp_die('Security check failed');
	}

	// Check user permissions
	if (!current_user_can('manage_options')) {
		wp_die('Insufficient permissions');
	}

	$image_id = isset($_POST['image_id']) ? intval($_POST['image_id']) : 0;
	$option_name = isset($_POST['option_name']) ? sanitize_text_field($_POST['option_name']) : '';

	if ($image_id) {
		// Get current options (use the plugin's real option key/helper)
		$options = P404REDIRECT_get_my_options();

		// Clear the specific option if it matches the image being deleted
		if ($option_name !== '' && isset($options[$option_name]) && $options[$option_name] == $image_id) {
			$options[$option_name] = '';
			P404REDIRECT_update_my_options($options);
		}

		// Delete the attachment
		$deleted = wp_delete_attachment($image_id, true);

		if ($deleted) {
			wp_send_json_success('Image deleted successfully and option cleared');
		} else {
			wp_send_json_error('Failed to delete image');
		}
	} else {
		wp_send_json_error('Invalid image ID');
	}
}


/** Function P404REDIRECT_HideAlert() called by wp_ajax hooks: {'P404REDIRECT_HideAlert'} **/
/** No params detected :-/ **/


/** Function handle_send_test_404_email() called by wp_ajax hooks: {'send_test_404_email'} **/
/** Parameters found in function handle_send_test_404_email(): {"post": ["nonce", "email"]} **/
function handle_send_test_404_email()
{
	// Check nonce for security
	if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'test_404_email_nonce')) {
		wp_send_json_error('Security check failed');
	}

	// Check user permissions
	if (!current_user_can('manage_options')) {
		wp_send_json_error('Insufficient permissions');
	}

	$email = sanitize_email($_POST['email']);
	if (!is_email($email)) {
		wp_send_json_error('Invalid email address');
	}

	// Check if we have enough redirects
	$total_redirects = P404REDIRECT_read_option_value('links', 0);
	if ($total_redirects < 5) {
		wp_send_json_error('Not enough redirect data available. Need at least 5 redirects to send test email.');
	}

	try {
		// Generate test email with actual data
		$test_stats = p404_generate_test_email_stats();
		$email_content = p404_generate_email_content('weekly', $test_stats);
		$subject = p404_get_email_subject('weekly', $test_stats['total_errors']);

		// Set up HTML email
		add_filter('wp_mail_content_type', 'p404_set_html_content_type');

		// Send the email
		$sent = wp_mail($email, $subject, $email_content);

		// Remove filter after sending
		remove_filter('wp_mail_content_type', 'p404_set_html_content_type');

		if ($sent) {
			wp_send_json_success('Test email sent successfully to ' . $email);
		} else {
			wp_send_json_error('Failed to send email. Please check your mail configuration.');
		}
	} catch (Exception $e) {
		wp_send_json_error('Error generating email: ' . $e->getMessage());
	}
}


