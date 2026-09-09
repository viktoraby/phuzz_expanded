<?php
/***
*
*Found actions: 2
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 2
*Overview: {'ajax_push_to_production': {'angie_push_to_production'}, 'ajax_toggle_status': {'angie_toggle_snippet_status'}}
*
***/

/** Function ajax_push_to_production() called by wp_ajax hooks: {'angie_push_to_production'} **/
/** Parameters found in function ajax_push_to_production(): {"post": ["post_id"]} **/
function ajax_push_to_production() {
		check_ajax_referer( 'angie_push_to_production', 'nonce' );

		if ( ! Module::current_user_can_manage_snippets() ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Insufficient permissions', 'angie' ) ] );
		}

		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
		if ( ! $post_id ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid post ID', 'angie' ) ] );
		}

		$post = get_post( $post_id );
		if ( ! $post || Module::CPT_NAME !== $post->post_type ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid snippet', 'angie' ) ] );
		}

		$timestamps = Dev_Mode_Manager::get_snippet_environment_timestamps( $post_id );
		$dev_time = $timestamps['dev'];

		if ( $dev_time > 0 ) {
			$success = Dev_Mode_Manager::push_snippet_to_production( $post_id );
			$success_message = esc_html__( 'Successfully pushed to production', 'angie' );
		} else {
			$success = Dev_Mode_Manager::push_snippet_to_dev( $post_id );
			$success_message = esc_html__( 'Successfully published to test', 'angie' );
		}

		if ( ! $success ) {
			wp_send_json_error( [ 'message' => esc_html__( 'No files to deploy', 'angie' ) ] );
		}

		wp_send_json_success(
			[
				'message' => $success_message,
			]
		);
	}


/** Function ajax_toggle_status() called by wp_ajax hooks: {'angie_toggle_snippet_status'} **/
/** Parameters found in function ajax_toggle_status(): {"post": ["post_id"]} **/
function ajax_toggle_status() {
		check_ajax_referer( 'angie_toggle_snippet_status', 'nonce' );

		if ( ! Module::current_user_can_manage_snippets() ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Insufficient permissions', 'angie' ) ] );
		}

		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
		if ( ! $post_id ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid post ID', 'angie' ) ] );
		}

		$post = get_post( $post_id );
		if ( ! $post || Module::CPT_NAME !== $post->post_type ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid snippet', 'angie' ) ] );
		}

		$new_status = 'publish' === $post->post_status ? 'draft' : 'publish';

		$updated = wp_update_post(
			[
				'ID' => $post_id,
				'post_status' => $new_status,
			],
			true
		);

		if ( is_wp_error( $updated ) ) {
			wp_send_json_error( [ 'message' => $updated->get_error_message() ] );
		}

		wp_send_json_success(
			[
				'status'  => $new_status,
				'message' => esc_html__( 'Status updated successfully', 'angie' ),
			]
		);
	}


