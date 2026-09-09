<?php
/***
*
*Found actions: 1
*Found functions:1
*Extracted functions:1
*Total parameter names extracted: 1
*Overview: {'ajax_dismiss_notice': {'tinvwl_admin_dismiss_notice'}}
*
***/

/** Function ajax_dismiss_notice() called by wp_ajax hooks: {'tinvwl_admin_dismiss_notice'} **/
/** Parameters found in function ajax_dismiss_notice(): {"request": ["tinvwl_type"]} **/
function ajax_dismiss_notice(): void {
		check_admin_referer( 'tinvwl_admin_dismiss_notice', 'nonce' ) && isset( $_REQUEST['tinvwl_type'] ) ?
			$this->update_notice_status( sanitize_key( $_REQUEST['tinvwl_type'] ) ) : wp_die();
	}


