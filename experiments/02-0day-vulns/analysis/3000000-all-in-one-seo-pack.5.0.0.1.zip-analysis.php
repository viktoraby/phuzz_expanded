<?php
/***
*
*Found actions: 8
*Found functions:6
*Extracted functions:5
*Total parameter names extracted: 2
*Overview: {'dismissActiveMenuTooltips': {'aioseo-dismiss-active-menu-tooltip'}, 'reauthenticate': {'nopriv_aioseo_rauthenticate'}, 'process': {'nopriv_aioseo_connect_process'}, 'dismissNotice': {'aioseo-dismiss-conflicting-plugins-notice', 'aioseo-dismiss-review-plugin-cta', 'aioseo-dismiss-deprecated-wordpress-notice'}, 'isInstalled': {'nopriv_aioseo_is_installed'}, 'deactivateConflictingPlugins': {'aioseo-deactivate-conflicting-plugins-notice'}}
*
***/

/** Function dismissActiveMenuTooltips() called by wp_ajax hooks: {'aioseo-dismiss-active-menu-tooltip'} **/
/** No function found :-/ **/


/** Function reauthenticate() called by wp_ajax hooks: {'nopriv_aioseo_rauthenticate'} **/
/** Parameters found in function reauthenticate(): {"request": ["tt"]} **/
function reauthenticate() {
		foreach ( [ 'key', 'token', 'tt' ] as $arg ) {
			if ( empty( $_REQUEST[ $arg ] ) ) {
				wp_send_json_error( [
					'error'   => 'authenticate_missing_arg',
					'message' => 'Authentication request missing parameter: ' . $arg,
					'version' => aioseo()->version,
					'pro'     => aioseo()->pro
				] );
			}
		}

		$trustToken = ! empty( $_REQUEST['tt'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['tt'] ) ) : '';
		if ( ! aioseo()->searchStatistics->api->trustToken->validate( $trustToken ) ) {
			wp_send_json_error( [
				'error'   => 'authenticate_invalid_tt',
				'message' => 'Invalid TT sent',
				'version' => aioseo()->version,
				'pro'     => aioseo()->pro
			] );
		}

		// If the trust token is validated, send a success response to trigger the regular auth process.
		wp_send_json_success();
	}


/** Function process() called by wp_ajax hooks: {'nopriv_aioseo_connect_process'} **/
/** No params detected :-/ **/


/** Function dismissNotice() called by wp_ajax hooks: {'aioseo-dismiss-conflicting-plugins-notice', 'aioseo-dismiss-review-plugin-cta', 'aioseo-dismiss-deprecated-wordpress-notice'} **/
/** Parameters found in function dismissNotice(): {"post": ["action"]} **/
function dismissNotice() {
		// Early exit if we're not on a aioseo-dismiss-conflicting-plugins-notice action.
		if ( ! isset( $_POST['action'] ) || 'aioseo-dismiss-conflicting-plugins-notice' !== $_POST['action'] ) {
			return wp_send_json_error( 'invalid-action' );
		}

		check_ajax_referer( 'aioseo-dismiss-conflicting-plugins', 'nonce' );

		$userId = get_current_user_id();
		update_user_meta( $userId, '_aioseo_conflicting_plugins_dismissed', true );

		return wp_send_json_success();
	}


/** Function isInstalled() called by wp_ajax hooks: {'nopriv_aioseo_is_installed'} **/
/** No params detected :-/ **/


/** Function deactivateConflictingPlugins() called by wp_ajax hooks: {'aioseo-deactivate-conflicting-plugins-notice'} **/
/** No params detected :-/ **/


