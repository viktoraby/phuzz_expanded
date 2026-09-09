<?php
/***
*
*Found actions: 1
*Found functions:1
*Extracted functions:1
*Total parameter names extracted: 1
*Overview: {'ajax_register_public_preview': {'public-post-preview'}}
*
***/

/** Function ajax_register_public_preview() called by wp_ajax hooks: {'public-post-preview'} **/
/** Parameters found in function ajax_register_public_preview(): {"post": ["post_ID", "checked"]} **/
function ajax_register_public_preview() {
		if ( ! isset( $_POST['post_ID'], $_POST['checked'] ) ) {
			wp_send_json_error( 'incomplete_data' );
		}

		$preview_post_id = (int) $_POST['post_ID'];
		$checked         = (string) $_POST['checked'];

		check_ajax_referer( 'public-post-preview_' . $preview_post_id );

		$post = get_post( $preview_post_id );

		if ( ! current_user_can( 'edit_post', $preview_post_id ) ) {
			wp_send_json_error( 'cannot_edit' );
		}

		if ( in_array( $post->post_status, self::get_published_statuses(), true ) ) {
			wp_send_json_error( 'invalid_post_status' );
		}

		$preview_post_ids = self::get_preview_post_ids();

		if ( 'false' === $checked && in_array( $preview_post_id, $preview_post_ids, true ) ) {
			$preview_post_ids = array_diff( $preview_post_ids, (array) $preview_post_id );
		} elseif ( 'true' === $checked && ! in_array( $preview_post_id, $preview_post_ids, true ) ) {
			$preview_post_ids = array_merge( $preview_post_ids, (array) $preview_post_id );
		} else {
			wp_send_json_error( 'unknown_status' );
		}

		$ret = self::set_preview_post_ids( $preview_post_ids );

		if ( ! $ret ) {
			wp_send_json_error( 'not_saved' );
		}

		$data = null;
		if ( 'true' === $checked ) {
			$data = array( 'preview_url' => self::get_preview_link( $post ) );
		}

		wp_send_json_success( $data );
	}


