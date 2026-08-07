<?php
/***
*
*Found actions: 1
*Found functions:1
*Extracted functions:1
*Total parameter names extracted: 1
*Overview: {'handle_ajax': {'post_type_switcher'}}
*
***/

/** Function handle_ajax() called by wp_ajax hooks: {'post_type_switcher'} **/
/** Parameters found in function handle_ajax(): {"get": ["pts_post_type", "post_id"]} **/
function handle_ajax() {

		// Bail if missing data
		if (
			   empty( $_GET['pts_post_type'] )
			|| empty( $_GET['pts-nonce-select'] )
			|| empty( $_GET['post_id'] )
		) {
			return wp_die( esc_html__( 'Missing data.', 'post-type-switcher' ) );
		}

		// Post type information
		$post_id          = absint( $_GET['post_id'] );
		$post_type        = sanitize_key( $_GET['pts_post_type'] );
		$post_type_object = get_post_type_object( $post_type );

		// Bail if nonce is invalid, or user cannot edit or publish
		if (
			! wp_verify_nonce( $_GET['pts-nonce-select'], 'post-type-selector' )
			||
			! current_user_can( 'edit_post', $post_id )
			||
			! current_user_can( $post_type_object->cap->publish_posts )
		) {
			wp_die( esc_html__( 'Sorry, you are not allowed to edit this post.', 'post-type-switcher' ) );
			return;
		}

		// Retrieve the original post type for later use
		$original_post_type = get_post_type( $post_id );

		// Update the post type
		$this->set_post_type( $post_id, $post_type );

		/**
		 * Allow actions after post type switch
		 *
		 * @since 1.0.0
		 *
		 * @param $updated_post_type string The new post type
		 * @param $post_type The old post type
		 * @param $post_id The post ID
		 */
		do_action( 'post_type_after_switch', $post_type, $original_post_type, $post_id );

		// Redirect
		wp_safe_redirect( get_edit_post_link( $post_id, 'raw' ) );
		exit;
	}


