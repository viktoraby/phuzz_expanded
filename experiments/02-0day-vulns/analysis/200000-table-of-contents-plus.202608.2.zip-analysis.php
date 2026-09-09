<?php
/***
*
*Found actions: 1
*Found functions:1
*Extracted functions:1
*Total parameter names extracted: 1
*Overview: {'dismissNotice': {'table-of-contents-plus-dismiss-review-plugin-cta'}}
*
***/

/** Function dismissNotice() called by wp_ajax hooks: {'table-of-contents-plus-dismiss-review-plugin-cta'} **/
/** Parameters found in function dismissNotice(): {"post": ["action", "delay", "relay"]} **/
function dismissNotice() {
		// Early exit if we're not on a table-of-contents-plus-dismiss-review-plugin-cta action.
		if ( ! isset( $_POST['action'] ) || 'table-of-contents-plus-dismiss-review-plugin-cta' !== $_POST['action'] ) {
			return;
		}

		check_ajax_referer( 'table-of-contents-plus-dismiss-review', 'nonce' );
		$delay = isset( $_POST['delay'] ) ? 'true' === sanitize_text_field( wp_unslash( $_POST['delay'] ) ) : false;
		$relay = isset( $_POST['relay'] ) ? 'true' === sanitize_text_field( wp_unslash( $_POST['relay'] ) ) : false;

		if ( ! $delay ) {
			update_user_meta( get_current_user_id(), '_aioseo_table_of_contents_plugin_review_dismissed', $relay ? '4' : '3' );

			wp_send_json_success();

			return;
		}

		update_user_meta( get_current_user_id(), '_aioseo_table_of_contents_plugin_review_dismissed', strtotime( '+1 week' ) );

		wp_send_json_success();
	}


