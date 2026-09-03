<?php
/***
*
*Found actions: 2
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 1
*Overview: {'constant_contact_dismiss': {'om_constant_contact_dismiss'}, 'validate_please_connect_notice_dismiss': {'dismiss-wp-pointer'}}
*
***/

/** Function constant_contact_dismiss() called by wp_ajax hooks: {'om_constant_contact_dismiss'} **/
/** No params detected :-/ **/


/** Function validate_please_connect_notice_dismiss() called by wp_ajax hooks: {'dismiss-wp-pointer'} **/
/** Parameters found in function validate_please_connect_notice_dismiss(): {"post": ["pointer"]} **/
function validate_please_connect_notice_dismiss() {
		if ( isset( $_POST['pointer'] ) && 'omapi_please_connect_notice' !== $_POST['pointer'] ) {
			return;
		}

		check_ajax_referer( 'dismiss_pointer' );
	}


