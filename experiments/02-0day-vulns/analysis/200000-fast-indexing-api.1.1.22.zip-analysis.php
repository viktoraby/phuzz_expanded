<?php
/***
*
*Found actions: 2
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 1
*Overview: {'ajax_rm_giapi': {'rm_giapi'}, 'ajax_get_limits': {'rm_giapi_limits'}}
*
***/

/** Function ajax_rm_giapi() called by wp_ajax hooks: {'rm_giapi'} **/
/** Parameters found in function ajax_rm_giapi(): {"post": ["api_action"]} **/
function ajax_rm_giapi() {
		check_ajax_referer( 'giapi-console' );

		if ( ! current_user_can( apply_filters( 'rank_math/indexing_api/capability', 'manage_options' ) ) ) {
			die( '0' );
		}

		$url_input = $this->get_input_urls();
		$action    = sanitize_title( wp_unslash( $_POST['api_action'] ) );
		header( 'Content-type: application/json' );

		$result = $this->send_to_api( $url_input, $action, true );
		wp_send_json( $result );
		exit();
	}


/** Function ajax_get_limits() called by wp_ajax hooks: {'rm_giapi_limits'} **/
/** No params detected :-/ **/


