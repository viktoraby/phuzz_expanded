<?php
/***
*
*Found actions: 1
*Found functions:1
*Extracted functions:1
*Total parameter names extracted: 1
*Overview: {'process_frontend_request': {'wc_stripe_admin_request'}}
*
***/

/** Function process_frontend_request() called by wp_ajax hooks: {'wc_stripe_admin_request'} **/
/** Parameters found in function process_frontend_request(): {"get": ["path"]} **/
function process_frontend_request() {
		if ( isset( $_GET['path'] ) ) {
			global $wp;
			$wp->set_query_var( 'rest_route', sanitize_text_field( $_GET['path'] ) );
			rest_api_loaded();
		}
	}


