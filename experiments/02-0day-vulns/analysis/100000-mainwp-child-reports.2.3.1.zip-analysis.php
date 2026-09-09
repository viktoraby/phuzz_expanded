<?php
/***
*
*Found actions: 7
*Found functions:7
*Extracted functions:7
*Total parameter names extracted: 1
*Overview: {'get_ips': {'mainwp_stream_get_ips'}, 'get_actions': {'mainwp_stream_get_actions'}, 'ajax_filters': {'wp_mainwp_stream_filters'}, 'wp_ajax_reset': {'wp_mainwp_stream_reset'}, 'enable_live_update': {'mainwp_stream_enable_live_update'}, 'get_users': {'mainwp_stream_get_users'}, 'ajax_uninstall': {'wp_mainwp_stream_uninstall'}}
*
***/

/** Function get_ips() called by wp_ajax hooks: {'mainwp_stream_get_ips'} **/
/** No params detected :-/ **/


/** Function get_actions() called by wp_ajax hooks: {'mainwp_stream_get_actions'} **/
/** Parameters found in function get_actions(): {"post": ["action_nonce"]} **/
function get_actions() {

		if ( ! isset( $_POST['action_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['action_nonce'] ), 'settings_nonce' ) ) {
			wp_die( 'Invalid request!' );
		}

		$connector_name    = wp_mainwp_stream_filter_input( INPUT_POST, 'connector' );
		$stream_connectors = wp_mainwp_stream_get_instance()->connectors;
		if ( ! empty( $connector_name ) ) {
			if ( isset( $stream_connectors->connectors[ $connector_name ] ) ) {
				$connector = $stream_connectors->connectors[ $connector_name ];
				if ( method_exists( $connector, 'get_action_labels' ) ) {
					$actions = $connector->get_action_labels();
				}
			}
		} else {
			$actions = $stream_connectors->term_labels['stream_action'];
		}
		ksort( $actions );
		wp_send_json_success( $actions );
	}


/** Function ajax_filters() called by wp_ajax hooks: {'wp_mainwp_stream_filters'} **/
/** No params detected :-/ **/


/** Function wp_ajax_reset() called by wp_ajax hooks: {'wp_mainwp_stream_reset'} **/
/** No params detected :-/ **/


/** Function enable_live_update() called by wp_ajax hooks: {'mainwp_stream_enable_live_update'} **/
/** No params detected :-/ **/


/** Function get_users() called by wp_ajax hooks: {'mainwp_stream_get_users'} **/
/** No params detected :-/ **/


/** Function ajax_uninstall() called by wp_ajax hooks: {'wp_mainwp_stream_uninstall'} **/
/** No params detected :-/ **/


