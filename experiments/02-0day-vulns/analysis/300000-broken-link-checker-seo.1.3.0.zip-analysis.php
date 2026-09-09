<?php
/***
*
*Found actions: 2
*Found functions:1
*Extracted functions:1
*Total parameter names extracted: 1
*Overview: {'dismissNotice': {'aioseo-blc-dismiss-review-plugin-cta', 'aioseo-blc-dismiss-not-connected'}}
*
***/

/** Function dismissNotice() called by wp_ajax hooks: {'aioseo-blc-dismiss-review-plugin-cta', 'aioseo-blc-dismiss-not-connected'} **/
/** Parameters found in function dismissNotice(): {"post": ["action"]} **/
function dismissNotice() {
		if ( ! isset( $_POST['action'] ) || 'aioseo-blc-dismiss-not-connected' !== $_POST['action'] ) {
			return;
		}

		if ( ! current_user_can( 'edit_posts' ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		check_ajax_referer( 'aioseo-blc-dismiss-not-connected', 'nonce' );
		update_user_meta( get_current_user_id(), '_aioseo_blc_not_connected', strtotime( '+1 week' ) );

		wp_send_json_success();
	}


