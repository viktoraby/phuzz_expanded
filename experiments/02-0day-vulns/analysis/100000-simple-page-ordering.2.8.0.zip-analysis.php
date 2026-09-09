<?php
/***
*
*Found actions: 2
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 2
*Overview: {'ajax_reset_simple_page_ordering': {'reset_simple_page_ordering'}, 'ajax_simple_page_ordering': {'simple_page_ordering'}}
*
***/

/** Function ajax_reset_simple_page_ordering() called by wp_ajax hooks: {'reset_simple_page_ordering'} **/
/** Parameters found in function ajax_reset_simple_page_ordering(): {"post": ["_wpnonce", "post_type"]} **/
function ajax_reset_simple_page_ordering() {
			global $wpdb;

			$nonce = isset( $_POST['_wpnonce'] ) ? sanitize_key( wp_unslash( $_POST['_wpnonce'] ) ) : '';

			if ( ! wp_verify_nonce( $nonce, 'simple-page-ordering-nonce' ) ) {
				die( -1 );
			}

			// check and make sure we have what we need
			$post_type = isset( $_POST['post_type'] ) ? sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) : '';

			if ( empty( $post_type ) ) {
				die( -1 );
			}

			// does user have the right to manage these post objects?
			if ( ! self::check_edit_others_caps( $post_type ) ) {
				die( -1 );
			}

			// reset the order of all posts of given post type
			$wpdb->update( 'wp_posts', array( 'menu_order' => 0 ), array( 'post_type' => $post_type ), array( '%d' ), array( '%s' ) );

			die( 0 );
		}


/** Function ajax_simple_page_ordering() called by wp_ajax hooks: {'simple_page_ordering'} **/
/** Parameters found in function ajax_simple_page_ordering(): {"post": ["id", "previd", "nextid", "_wpnonce", "start", "excluded"]} **/
function ajax_simple_page_ordering() {
			// check and make sure we have what we need
			if ( empty( $_POST['id'] ) || ( ! isset( $_POST['previd'] ) && ! isset( $_POST['nextid'] ) ) ) {
				die( - 1 );
			}

			$nonce = isset( $_POST['_wpnonce'] ) ? sanitize_key( wp_unslash( $_POST['_wpnonce'] ) ) : '';

			if ( ! wp_verify_nonce( $nonce, 'simple-page-ordering-nonce' ) ) {
				die( -1 );
			}

			$post_id  = empty( $_POST['id'] ) ? false : (int) $_POST['id'];
			$previd   = empty( $_POST['previd'] ) ? false : (int) $_POST['previd'];
			$nextid   = empty( $_POST['nextid'] ) ? false : (int) $_POST['nextid'];
			$start    = empty( $_POST['start'] ) ? 1 : (int) $_POST['start'];
			$excluded = empty( $_POST['excluded'] ) ? array( $_POST['id'] ) : array_filter( (array) json_decode( $_POST['excluded'] ), 'intval' );

			// real post?
			$post = empty( $post_id ) ? false : get_post( (int) $post_id );
			if ( ! $post ) {
				die( - 1 );
			}

			// does user have the right to manage these post objects?
			if ( ! self::check_edit_others_caps( $post->post_type ) ) {
				die( - 1 );
			}

			$result = self::page_ordering( $post_id, $previd, $nextid, $start, $excluded );

			if ( is_wp_error( $result ) ) {
				die( -1 );
			}

			die( wp_json_encode( $result ) );
		}


