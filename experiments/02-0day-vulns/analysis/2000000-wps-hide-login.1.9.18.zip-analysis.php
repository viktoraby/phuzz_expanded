<?php
/***
*
*Found actions: 1
*Found functions:1
*Extracted functions:1
*Total parameter names extracted: 1
*Overview: {'dismiss_admin_notice': {'dismiss_admin_notice'}}
*
***/

/** Function dismiss_admin_notice() called by wp_ajax hooks: {'dismiss_admin_notice'} **/
/** Parameters found in function dismiss_admin_notice(): {"post": ["option_name", "dismissible_length"]} **/
function dismiss_admin_notice() {
		check_ajax_referer( 'wps-hide-login-dismissible-notice' );

		$option_name        = sanitize_text_field( $_POST['option_name'] );
		$dismissible_length = sanitize_text_field( $_POST['dismissible_length'] );
		$transient          = 0;
		if ( 'forever' != $dismissible_length ) {
			// If $dismissible_length is not an integer default to 1
			$dismissible_length = ( 0 == absint( $dismissible_length ) ) ? 1 : $dismissible_length;
			$transient          = absint( $dismissible_length ) * DAY_IN_SECONDS;
			$dismissible_length = strtotime( absint( $dismissible_length ) . ' days' );
		}
		set_site_transient( $option_name, $dismissible_length, $transient );
		wp_die();
	}


