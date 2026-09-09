<?php
/***
*
*Found actions: 1
*Found functions:1
*Extracted functions:1
*Total parameter names extracted: 1
*Overview: {'dismiss_pointers': {'manage_pointer_dismissed'}}
*
***/

/** Function dismiss_pointers() called by wp_ajax hooks: {'manage_pointer_dismissed'} **/
/** Parameters found in function dismiss_pointers(): {"post": ["nonce", "data"]} **/
function dismiss_pointers() {
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'manage-pointer-dismissed' ) ) {
			wp_send_json_error( [ 'message' => 'Invalid nonce' ] );
		}

		$pointer = sanitize_text_field( wp_unslash( $_POST['data']['pointer'] ?? null ) );

		if ( empty( $pointer ) ) {
			wp_send_json_error( [ 'message' => 'The pointer id must be provided' ] );
		}

		$pointer = explode( ',', $pointer );

		$user_dismissed_meta = get_user_meta( get_current_user_id(), self::DISMISSED_POINTERS_META_KEY, true );

		if ( ! $user_dismissed_meta ) {
			$user_dismissed_meta = [];
		}

		foreach ( $pointer as $item ) {
			$user_dismissed_meta[ $item ] = true;
		}

		update_user_meta( get_current_user_id(), self::DISMISSED_POINTERS_META_KEY, $user_dismissed_meta );

		wp_send_json_success( [] );
	}


