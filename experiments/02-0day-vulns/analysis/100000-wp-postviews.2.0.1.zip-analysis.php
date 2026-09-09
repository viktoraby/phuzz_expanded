<?php
/***
*
*Found actions: 2
*Found functions:1
*Extracted functions:1
*Total parameter names extracted: 1
*Overview: {'ajax_increment': {'wp_postviews', 'nopriv_wp_postviews'}}
*
***/

/** Function ajax_increment() called by wp_ajax hooks: {'wp_postviews', 'nopriv_wp_postviews'} **/
/** Parameters found in function ajax_increment(): {"post": ["postviews_id"]} **/
function ajax_increment() {
		check_ajax_referer( self::AJAX_NONCE, '_ajax_nonce' );

		if ( ! self::using_ajax() ) {
			return;
		}

		if ( empty( $_POST['postviews_id'] ) ) {
			return;
		}

		$post_id = (int) sanitize_key( wp_unslash( $_POST['postviews_id'] ) );

		$post_views = self::record( $post_id );

		if ( ! is_int( $post_views ) ) {
			return;
		}

		wp_send_json_success( array( 'views' => $post_views ) );
	}


