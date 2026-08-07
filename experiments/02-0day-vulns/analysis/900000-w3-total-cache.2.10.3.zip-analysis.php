<?php
/***
*
*Found actions: 14
*Found functions:14
*Extracted functions:14
*Total parameter names extracted: 1
*Overview: {'wp_ajax_w3tc_forums_api': {'w3tc_forums_api'}, 'ajax_revert_all': {'w3tc_imageservice_revertall'}, 'wp_ajax_w3tc_ajax': {'w3tc_ajax'}, 'ajax_dismiss_license_notice': {'w3tc_dismiss_license_notice'}, 'ajax_recheck_license': {'w3tc_recheck_license'}, 'w3tc_ajax_ustats_access_log_test': {'ustats_access_log_test'}, 'ajax_get_postmeta': {'w3tc_imageservice_postmeta'}, 'ajax_submit': {'w3tc_imageservice_submit'}, 'ajax_revert': {'w3tc_imageservice_revert'}, 'ajax_get_counts': {'w3tc_imageservice_counts'}, 'ajax_get_usage': {'w3tc_imageservice_usage'}, 'action_widget_latest_ajax': {'w3tc_widget_latest_ajax'}, 'action_widget_latest_news_ajax': {'w3tc_widget_latest_news_ajax'}, 'ajax_convert_all': {'w3tc_imageservice_all'}}
*
***/

/** Function wp_ajax_w3tc_forums_api() called by wp_ajax hooks: {'w3tc_forums_api'} **/
/** No params detected :-/ **/


/** Function ajax_revert_all() called by wp_ajax hooks: {'w3tc_imageservice_revertall'} **/
/** No params detected :-/ **/


/** Function wp_ajax_w3tc_ajax() called by wp_ajax hooks: {'w3tc_ajax'} **/
/** No params detected :-/ **/


/** Function ajax_dismiss_license_notice() called by wp_ajax hooks: {'w3tc_dismiss_license_notice'} **/
/** Parameters found in function ajax_dismiss_license_notice(): {"post": ["notice_id"]} **/
function ajax_dismiss_license_notice() {
		/**
		 * Per-action nonce. Legacy 'w3tc' accepted as back-compat
		 * fallback via Util_Nonce::verify_ajax. The cap-check below remains
		 * the authoritative authorisation gate.
		 */
		Util_Nonce::verify_ajax( 'w3tc_dismiss_license_notice' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'Unauthorized' ) );
		}

		$notice_id = isset( $_POST['notice_id'] ) ? sanitize_key( wp_unslash( $_POST['notice_id'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verified above via Util_Nonce::verify_ajax().

		if ( empty( $notice_id ) ) {
			wp_send_json_error( array( 'message' => 'Invalid notice ID' ) );
		}

		$user_id           = get_current_user_id();
		$dismissed_notices = get_user_meta( $user_id, self::NOTICE_DISMISSED_META_KEY, true );

		if ( ! is_array( $dismissed_notices ) ) {
			$dismissed_notices = array();
		}

		$dismissed_notices[ $notice_id ] = time();

		update_user_meta( $user_id, self::NOTICE_DISMISSED_META_KEY, $dismissed_notices );

		wp_send_json_success( array( 'message' => 'Notice dismissed' ) );
	}


/** Function ajax_recheck_license() called by wp_ajax hooks: {'w3tc_recheck_license'} **/
/** No params detected :-/ **/


/** Function w3tc_ajax_ustats_access_log_test() called by wp_ajax hooks: {'ustats_access_log_test'} **/
/** No params detected :-/ **/


/** Function ajax_get_postmeta() called by wp_ajax hooks: {'w3tc_imageservice_postmeta'} **/
/** No params detected :-/ **/


/** Function ajax_submit() called by wp_ajax hooks: {'w3tc_imageservice_submit'} **/
/** No params detected :-/ **/


/** Function ajax_revert() called by wp_ajax hooks: {'w3tc_imageservice_revert'} **/
/** No params detected :-/ **/


/** Function ajax_get_counts() called by wp_ajax hooks: {'w3tc_imageservice_counts'} **/
/** No params detected :-/ **/


/** Function ajax_get_usage() called by wp_ajax hooks: {'w3tc_imageservice_usage'} **/
/** No params detected :-/ **/


/** Function action_widget_latest_ajax() called by wp_ajax hooks: {'w3tc_widget_latest_ajax'} **/
/** No params detected :-/ **/


/** Function action_widget_latest_news_ajax() called by wp_ajax hooks: {'w3tc_widget_latest_news_ajax'} **/
/** No params detected :-/ **/


/** Function ajax_convert_all() called by wp_ajax hooks: {'w3tc_imageservice_all'} **/
/** No params detected :-/ **/


