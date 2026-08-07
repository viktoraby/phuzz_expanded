<?php
/***
*
*Found actions: 10
*Found functions:7
*Extracted functions:7
*Total parameter names extracted: 1
*Overview: {'ajax_get_posts': {'rwmb_get_posts', 'nopriv_rwmb_get_posts'}, 'ajax_delete_file': {'rwmb_delete_file'}, 'ajax_get_terms': {'nopriv_rwmb_get_terms', 'rwmb_get_terms'}, 'get_feed': {'mb_dashboard_feed'}, 'ajax_get_embed': {'rwmb_get_embed'}, 'ajax_get_users': {'nopriv_rwmb_get_users', 'rwmb_get_users'}, 'handle_plugin_action': {'mb_dashboard_plugin_action'}}
*
***/

/** Function ajax_get_posts() called by wp_ajax hooks: {'rwmb_get_posts', 'nopriv_rwmb_get_posts'} **/
/** No params detected :-/ **/


/** Function ajax_delete_file() called by wp_ajax hooks: {'rwmb_delete_file'} **/
/** No params detected :-/ **/


/** Function ajax_get_terms() called by wp_ajax hooks: {'nopriv_rwmb_get_terms', 'rwmb_get_terms'} **/
/** No params detected :-/ **/


/** Function get_feed() called by wp_ajax hooks: {'mb_dashboard_feed'} **/
/** No params detected :-/ **/


/** Function ajax_get_embed() called by wp_ajax hooks: {'rwmb_get_embed'} **/
/** No params detected :-/ **/


/** Function ajax_get_users() called by wp_ajax hooks: {'nopriv_rwmb_get_users', 'rwmb_get_users'} **/
/** No params detected :-/ **/


/** Function handle_plugin_action() called by wp_ajax hooks: {'mb_dashboard_plugin_action'} **/
/** Parameters found in function handle_plugin_action(): {"get": ["mb_plugin", "mb_action"]} **/
function handle_plugin_action(): void {
		check_ajax_referer( 'plugin' );

		$plugin = isset( $_GET['mb_plugin'] ) ? sanitize_text_field( wp_unslash( $_GET['mb_plugin'] ) ) : '';
		$action = isset( $_GET['mb_action'] ) ? sanitize_text_field( wp_unslash( $_GET['mb_action'] ) ) : '';

		if ( ! $plugin || ! $action || ! in_array( $action, [ 'install', 'activate' ], true ) ) {
			wp_send_json_error();
		}

		if ( $action === 'install' ) {
			$this->install_plugin( $plugin );
			$this->activate_plugin( $plugin );
		} elseif ( $action === 'activate' ) {
			$this->activate_plugin( $plugin );
		}

		wp_send_json_success();
	}


