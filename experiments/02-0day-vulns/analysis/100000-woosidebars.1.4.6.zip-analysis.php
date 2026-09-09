<?php
/***
*
*Found actions: 2
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 2
*Overview: {'enable_custom_post_sidebars': {'woosidebars-post-enable'}, 'ajax_toggle_advanced_items': {'woosidebars-toggle-advanced-items'}}
*
***/

/** Function enable_custom_post_sidebars() called by wp_ajax hooks: {'woosidebars-post-enable'} **/
/** Parameters found in function enable_custom_post_sidebars(): {"get": ["post_id"]} **/
function enable_custom_post_sidebars () {
		if( ! is_admin() ) die;
		if( ! current_user_can( 'edit_posts' ) ) wp_die( __( 'You do not have sufficient permissions to access this page.', 'woosidebars' ) );
		if( ! check_admin_referer( 'woosidebars-post-enable' ) ) wp_die( __( 'You have taken too long. Please go back and retry.', 'woosidebars' ) );

		$post_id = isset( $_GET['post_id'] ) && (int)$_GET['post_id'] ? (int)$_GET['post_id'] : '';

		if( ! $post_id ) die;

		$post = get_post( $post_id );
		if( ! $post ) die;

		$meta = get_post_meta( $post->ID, '_enable_sidebar', true );

		if ( $meta == 'yes' ) {
			update_post_meta($post->ID, '_enable_sidebar', 'no' );
		} else {
			update_post_meta($post->ID, '_enable_sidebar', 'yes' );
		}

		$sendback = remove_query_arg( array( 'trashed', 'untrashed', 'deleted', 'ids' ), wp_get_referer() );
		wp_safe_redirect( esc_url( $sendback ) );
	}


/** Function ajax_toggle_advanced_items() called by wp_ajax hooks: {'woosidebars-toggle-advanced-items'} **/
/** Parameters found in function ajax_toggle_advanced_items(): {"post": ["woosidebars_advanced_noonce", "new_status"]} **/
function ajax_toggle_advanced_items () {
		//Add nonce security to the request
		if ( ( ! isset( $_POST['woosidebars_advanced_noonce'] ) || ! isset( $_POST['new_status'] ) ) || ! wp_verify_nonce( $_POST['woosidebars_advanced_noonce'], 'woosidebars_advanced_noonce' ) ) {
			die();
		}

		$response = set_user_setting( 'woosidebarsshowadvanced', $_POST['new_status'] );

		echo $response;
		die(); // WordPress may print out a spurious zero without this can be particularly bad if using JSON
	}


