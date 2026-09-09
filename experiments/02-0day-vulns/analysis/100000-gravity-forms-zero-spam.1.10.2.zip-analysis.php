<?php
/***
*
*Found actions: 2
*Found functions:1
*Extracted functions:1
*Total parameter names extracted: 1
*Overview: {'handle_ajax': {'gf_zero_spam_token', 'nopriv_gf_zero_spam_token'}}
*
***/

/** Function handle_ajax() called by wp_ajax hooks: {'gf_zero_spam_token', 'nopriv_gf_zero_spam_token'} **/
/** Parameters found in function handle_ajax(): {"post": ["form_id"]} **/
function handle_ajax() {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Public endpoint; no nonce needed.
		$form_id = isset( $_POST['form_id'] ) ? absint( $_POST['form_id'] ) : 0;
		$result  = $this->handle_token_request( $form_id );

		nocache_headers();

		if ( is_wp_error( $result ) ) {
			$error_data = $result->get_error_data();
			$status     = is_array( $error_data ) && isset( $error_data['status'] ) ? (int) $error_data['status'] : 500;

			wp_send_json_error( $result->get_error_message(), $status );
		}

		wp_send_json( $result );
	}


