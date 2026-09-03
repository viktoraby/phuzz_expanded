<?php
/***
*
*Found actions: 8
*Found functions:8
*Extracted functions:7
*Total parameter names extracted: 1
*Overview: {'wp_ajax_install_plugin': {'wpsc_install_plugin'}, 'wpsc_dismiss_notice_on_activation': {'wpsc_activate_boost'}, 'wpsc_ajax_activate_boost': {'wpsc_activate_boost'}, 'wpsc_admin_bar_delete_cache_ajax': {'ajax-delete-cache'}, 'wpsc_dismiss_boost_notice_ajax_handler': {'wpsc_dismiss_boost_notice'}, 'wpsc_hide_boost_banner': {'wpsc-hide-boost-banner'}, 'wpsc_ajax_get_preload_status': {'wpsc_get_preload_status'}, 'wpsc_dismiss_indexhtml_warning': {'wpsc-index-dismiss'}}
*
***/

/** Function wp_ajax_install_plugin() called by wp_ajax hooks: {'wpsc_install_plugin'} **/
/** No function found :-/ **/


/** Function wpsc_dismiss_notice_on_activation() called by wp_ajax hooks: {'wpsc_activate_boost'} **/
/** No params detected :-/ **/


/** Function wpsc_ajax_activate_boost() called by wp_ajax hooks: {'wpsc_activate_boost'} **/
/** Parameters found in function wpsc_ajax_activate_boost(): {"post": ["source"]} **/
function wpsc_ajax_activate_boost() {
	check_ajax_referer( 'activate-boost' );

	if ( ! isset( $_POST['source'] ) ) {
		wp_send_json_error( 'no source specified', null, JSON_UNESCAPED_SLASHES );
	}

	$source = sanitize_text_field( wp_unslash( $_POST['source'] ) );
	$result = activate_plugin( 'jetpack-boost/jetpack-boost.php' );
	if ( is_wp_error( $result ) ) {
		wp_send_json_error( $result->get_error_message(), null, JSON_UNESCAPED_SLASHES );
	}

	wpsc_notify_migration_to_boost( $source );

	wp_send_json_success( null, null, JSON_UNESCAPED_SLASHES );
}


/** Function wpsc_admin_bar_delete_cache_ajax() called by wp_ajax hooks: {'ajax-delete-cache'} **/
/** No params detected :-/ **/


/** Function wpsc_dismiss_boost_notice_ajax_handler() called by wp_ajax hooks: {'wpsc_dismiss_boost_notice'} **/
/** No params detected :-/ **/


/** Function wpsc_hide_boost_banner() called by wp_ajax hooks: {'wpsc-hide-boost-banner'} **/
/** No params detected :-/ **/


/** Function wpsc_ajax_get_preload_status() called by wp_ajax hooks: {'wpsc_get_preload_status'} **/
/** No params detected :-/ **/


/** Function wpsc_dismiss_indexhtml_warning() called by wp_ajax hooks: {'wpsc-index-dismiss'} **/
/** No params detected :-/ **/


