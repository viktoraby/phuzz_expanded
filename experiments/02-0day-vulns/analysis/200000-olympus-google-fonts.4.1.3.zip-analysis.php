<?php
/***
*
*Found actions: 4
*Found functions:4
*Extracted functions:4
*Total parameter names extracted: 1
*Overview: {'dismiss_guide': {'ogf_dismiss_guide'}, 'ajax_customizer_clear_cache': {'customizer_clear_cache'}, 'dismiss_notice': {'ogf_dismiss_notice'}, 'ajax_customizer_reset': {'customizer_reset'}}
*
***/

/** Function dismiss_guide() called by wp_ajax hooks: {'ogf_dismiss_guide'} **/
/** No params detected :-/ **/


/** Function ajax_customizer_clear_cache() called by wp_ajax hooks: {'customizer_clear_cache'} **/
/** No params detected :-/ **/


/** Function dismiss_notice() called by wp_ajax hooks: {'ogf_dismiss_notice'} **/
/** Parameters found in function dismiss_notice(): {"post": ["type"]} **/
function dismiss_notice() {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'insufficient_permissions' );
			}

			// phpcs:ignore WordPress.Security.NonceVerification.Missing -- AJAX handler for dismissing notices, safe operation.
			if ( isset( $_POST['type'] ) ) {
				// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Same as above.
				$type = sanitize_text_field( wp_unslash( $_POST['type'] ) );
				update_option( 'dismissed-' . $type, true );
			}
		}


/** Function ajax_customizer_reset() called by wp_ajax hooks: {'customizer_reset'} **/
/** No params detected :-/ **/


