<?php
/***
*
*Found actions: 2
*Found functions:1
*Extracted functions:1
*Total parameter names extracted: 1
*Overview: {'vbp_clear_patterns_cache': {'vbp_clear_patterns_cache', 'clear_patterns_cache'}}
*
***/

/** Function vbp_clear_patterns_cache() called by wp_ajax hooks: {'vbp_clear_patterns_cache', 'clear_patterns_cache'} **/
/** Parameters found in function vbp_clear_patterns_cache(): {"post": ["vbp_clear_patterns_cache_nonce"]} **/
function vbp_clear_patterns_cache( $test_mode = false ) {
	// nonce を検証する.
	if ( false === $test_mode ) {
		if ( ! isset( $_POST['vbp_clear_patterns_cache_nonce'] ) || ! wp_verify_nonce( $_POST['vbp_clear_patterns_cache_nonce'], 'vbp_clear_patterns_cache_action' ) ) {
			wp_send_json_error( array( 'message' => 'Nonce verification failed.' ), 403 );
		}
	}
	// 設定画面と同じ権限でアクセス制限.
	if ( is_user_logged_in() && current_user_can( 'edit_theme_options' ) ) {
		vbp_delete_patterns_cache_data();

		if ( false === $test_mode ) {
			wp_send_json_success( array( 'message' => 'Cache cleared.' ) );
		}
	} elseif ( false === $test_mode ) {
		// アクセスが拒否された場合の処理.
		wp_send_json_error( array( 'message' => 'Unauthorized.' ), 403 );
	}
}


