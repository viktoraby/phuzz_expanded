<?php
/***
*
*Found actions: 2
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 1
*Overview: {'perflab_dismiss_wp_pointer_wrapper': {'dismiss-wp-pointer'}, 'perflab_aea_enqueued_ajax_blocking_assets_test': {'health-check-enqueued-blocking-assets-test'}}
*
***/

/** Function perflab_dismiss_wp_pointer_wrapper() called by wp_ajax hooks: {'dismiss-wp-pointer'} **/
/** Parameters found in function perflab_dismiss_wp_pointer_wrapper(): {"post": ["pointer"]} **/
function perflab_dismiss_wp_pointer_wrapper(): void {
	if (
		isset( $_POST['pointer'] )
		&&
		! in_array( $_POST['pointer'], array_keys( perflab_get_admin_pointers() ), true )
	) {
		// Another plugin's pointer, do nothing.
		return;
	}
	check_ajax_referer( 'dismiss_pointer' );
}


/** Function perflab_aea_enqueued_ajax_blocking_assets_test() called by wp_ajax hooks: {'health-check-enqueued-blocking-assets-test'} **/
/** No params detected :-/ **/


