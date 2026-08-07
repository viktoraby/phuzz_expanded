<?php
/***
*
*Found actions: 10
*Found functions:4
*Extracted functions:1
*Total parameter names extracted: 1
*Overview: {'Moove_GDPR_Controller': {'nopriv_moove_gdpr_remove_php_cookies', 'moove_gdpr_remove_php_cookies', 'moove_gdpr_localize_scripts', 'nopriv_moove_gdpr_localize_scripts', 'nopriv_moove_gdpr_get_scripts', 'moove_hide_language_notice', 'moove_gdpr_get_scripts'}, 'Moove_GDPR_License_Manager': {'gdpr_msba_bulk_activate'}, 'gdpr_cc_dismiss_review_notice': {'gdpr_cc_dismiss_review_notice'}, 'Moove_GDPR_Updater': {'moove_hide_update_notice'}}
*
***/

/** Function Moove_GDPR_Controller() called by wp_ajax hooks: {'nopriv_moove_gdpr_remove_php_cookies', 'moove_gdpr_remove_php_cookies', 'moove_gdpr_localize_scripts', 'nopriv_moove_gdpr_localize_scripts', 'nopriv_moove_gdpr_get_scripts', 'moove_hide_language_notice', 'moove_gdpr_get_scripts'} **/
/** No function found :-/ **/


/** Function Moove_GDPR_License_Manager() called by wp_ajax hooks: {'gdpr_msba_bulk_activate'} **/
/** No function found :-/ **/


/** Function gdpr_cc_dismiss_review_notice() called by wp_ajax hooks: {'gdpr_cc_dismiss_review_notice'} **/
/** Parameters found in function gdpr_cc_dismiss_review_notice(): {"post": ["nonce", "type"]} **/
function gdpr_cc_dismiss_review_notice() {
		$nonce    = isset( $_POST['nonce'] ) ? sanitize_key( wp_unslash( $_POST['nonce'] ) ) : false;
		$type     = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';
		$response = array(
			'success' => false,
			'message' => 'Invalid request!',
		);
		if ( $nonce && wp_verify_nonce( $nonce, 'gdpr_cc_dismiss_nonce_field' ) && current_user_can( apply_filters( 'gdpr_options_page_cap', 'manage_options' ) ) ) :
			$user = wp_get_current_user();
			if ( $user && isset( $user->ID ) ) :
				$dismiss_3m = update_user_meta( $user->ID, 'gdpr_cc_dismiss_stamp' . $type, strtotime( '+3 months' ) );

				$response = array(
					'success' => true,
					'message' => '',
				);
			endif;
		endif;
		echo json_encode( $response ); // phpcs:ignore
		die();
	}


/** Function Moove_GDPR_Updater() called by wp_ajax hooks: {'moove_hide_update_notice'} **/
/** No function found :-/ **/


