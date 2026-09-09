<?php
/***
*
*Found actions: 2
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 1
*Overview: {'ajax_unlock': {'wps-limit-login-unlock'}, 'wpslimitlogin_rated': {'wpslimitlogin_rated'}}
*
***/

/** Function ajax_unlock() called by wp_ajax hooks: {'wps-limit-login-unlock'} **/
/** Parameters found in function ajax_unlock(): {"post": ["ip", "username"]} **/
function ajax_unlock() {
		check_ajax_referer( 'wps-limit-login-unlock', 'nonce' );
		$ip = ( isset( $_POST['ip'] ) ) ? $_POST['ip'] : '';

		$lockouts = (array) $this->get_option( 'wps_limit_login_lockouts' );

		if ( isset( $lockouts[ $ip ] ) ) {
			unset( $lockouts[ $ip ] );
			$this->update_option( 'wps_limit_login_lockouts', $lockouts );
		}

		//save to log
		$user_login = ( isset( $_POST['username'] ) ) ? $_POST['username'] : '';
		$log        = $this->get_option( 'wps_limit_login_logged' );

		if ( isset( $log[ $ip ][ $user_login ] ) ) {
			if ( ! is_array( $log[ $ip ][ $user_login ] ) ) {
				$log[ $ip ][ $user_login ] = array(
					'counter' => $log[ $ip ][ $user_login ],
				);
			}
			$log[ $ip ][ $user_login ]['unlocked'] = true;

			$this->update_option( 'wps_limit_login_logged', $log );
		}

		wp_send_json_success();
	}


/** Function wpslimitlogin_rated() called by wp_ajax hooks: {'wpslimitlogin_rated'} **/
/** No params detected :-/ **/


