<?php
/***
*
*Found actions: 2
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 1
*Overview: {'dismiss_review_prompt': {'klaviyo_dismiss_review_prompt'}, 'handle_feedback_response': {'klaviyo_handle_feedback_response'}}
*
***/

/** Function dismiss_review_prompt() called by wp_ajax hooks: {'klaviyo_dismiss_review_prompt'} **/
/** No params detected :-/ **/


/** Function handle_feedback_response() called by wp_ajax hooks: {'klaviyo_handle_feedback_response'} **/
/** Parameters found in function handle_feedback_response(): {"post": ["nonce", "response"]} **/
function handle_feedback_response() {
			if (!current_user_can('manage_options')) {
				wp_die();
			}

			$nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
			if (!wp_verify_nonce($nonce, 'klaviyo_feedback_nonce')) {
				wp_die(esc_html__('Security check failed', 'woocommerce-klaviyo'));
			}

			if (isset($_POST['response']) && in_array($_POST['response'], array( 'great', 'feedback' ))) {
				update_option('klaviyo_feedback_response', sanitize_text_field($_POST['response']));
			}

			wp_die();
		}


