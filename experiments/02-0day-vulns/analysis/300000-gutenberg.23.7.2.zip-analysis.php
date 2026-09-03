<?php
/***
*
*Found actions: 3
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 1
*Overview: {'gutenberg_block_quick_edit_for_active_lock': {'inline-save'}, 'gutenberg_block_core_form_send_email': {'nopriv_wp_block_form_email_submit', 'wp_block_form_email_submit'}}
*
***/

/** Function gutenberg_block_quick_edit_for_active_lock() called by wp_ajax hooks: {'inline-save'} **/
/** Parameters found in function gutenberg_block_quick_edit_for_active_lock(): {"post": ["post_ID"]} **/
function gutenberg_block_quick_edit_for_active_lock() {
		check_ajax_referer( 'inlineeditnonce', '_inline_edit' );

		$post_id = isset( $_POST['post_ID'] ) ? (int) $_POST['post_ID'] : 0;
		if ( ! $post_id ) {
			return;
		}

		$post = get_post( $post_id );
		if ( ! $post || wp_is_post_type_collaboration_disabled( $post->post_type ) ) {
			return;
		}

		$lock_user = gutenberg_get_active_edit_lock_user( $post_id );
		if ( ! $lock_user ) {
			/*
			 * Core creates a lock during inline save. Prevent that specific write
			 * so a later Quick Edit is not mistaken for an active editor session.
			 */
			add_filter(
				'update_post_metadata',
				static function ( $check, $object_id, $meta_key ) use ( $post_id ) {
					if ( $post_id === (int) $object_id && '_edit_lock' === $meta_key ) {
						return false;
					}

					return $check;
				},
				10,
				3
			);
			return;
		}

		if ( get_current_user_id() !== $lock_user ) {
			// Core handles locks owned by another user.
			return;
		}

		wp_die( esc_html__( 'Quick Edit is disabled: You are currently editing this post in another tab or window.', 'gutenberg' ) );
	}


/** Function gutenberg_block_core_form_send_email() called by wp_ajax hooks: {'nopriv_wp_block_form_email_submit', 'wp_block_form_email_submit'} **/
/** No params detected :-/ **/


