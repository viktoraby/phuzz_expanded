<?php
/***
*
*Found actions: 1
*Found functions:1
*Extracted functions:1
*Total parameter names extracted: 1
*Overview: {'ajax_save': {'ncbw_optin_save'}}
*
***/

/** Function ajax_save() called by wp_ajax hooks: {'ncbw_optin_save'} **/
/** Parameters found in function ajax_save(): {"post": ["status", "profile", "diagnostic", "extensions"]} **/
function ajax_save() {
			check_ajax_referer( 'ncbw_optin_nonce', 'nonce' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'Unauthorized', 403 );
			}

			$status = isset( $_POST['status'] ) && $_POST['status'] === 'yes' ? 'yes' : 'no';

			$data = array(
				'profile'    => ! empty( $_POST['profile'] )    && '1' === $_POST['profile'],
				'diagnostic' => ! empty( $_POST['diagnostic'] ) && '1' === $_POST['diagnostic'],
				'extensions' => ! empty( $_POST['extensions'] ) && '1' === $_POST['extensions'],
			);

			update_option( self::OPTION_KEY, array(
				'status'    => $status,
				'timestamp' => time(),
				'data'      => $data,
			), false );

			if ( 'yes' === $status ) {
				$this->send_data( $data );
			}

			wp_send_json_success();
		}


