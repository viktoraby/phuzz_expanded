<?php
/***
*
*Found actions: 2
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 2
*Overview: {'ajax_get_title_by_ids': {'contentviews_elementor_get_title'}, 'ajax_search_by_title': {'contentviews_elementor_search_post'}}
*
***/

/** Function ajax_get_title_by_ids() called by wp_ajax hooks: {'contentviews_elementor_get_title'} **/
/** Parameters found in function ajax_get_title_by_ids(): {"post": ["id", "post_type"]} **/
function ajax_get_title_by_ids() {
			check_ajax_referer( 'el_search_action', 'secure_nonce' );
			if ( !current_user_can( 'edit_posts' ) ) {
				wp_send_json_error( 'Unauthorized' );
			}
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- just check here, sanitized below
			if ( empty( $_POST[ 'id' ] ) || empty( array_filter( (array) wp_unslash( $_POST[ 'id' ] ) ) ) ) {
				wp_send_json_error( [] );
			}

			$ids		 = array_map( 'intval', (array) wp_unslash( $_POST[ 'id' ] ) );
			$post_types	 = empty( $_POST[ 'post_type' ] ) ? 'post' : array_map( 'sanitize_key', explode( ',', sanitize_text_field( wp_unslash( $_POST[ 'post_type' ] ) ) ) );

			$post_info	 = get_posts( [
				'post_type'	 => $post_types,
				'include'	 => $ids,
				'post_status' => ['publish', 'inherit'],
			] );
			$response	 = wp_list_pluck( $post_info, 'post_title', 'ID' );

			if ( !empty( $response ) ) {
				wp_send_json_success( [ 'results' => $response ] );
			} else {
				wp_send_json_error( [] );
			}
		}


/** Function ajax_search_by_title() called by wp_ajax hooks: {'contentviews_elementor_search_post'} **/
/** Parameters found in function ajax_search_by_title(): {"post": ["post_type", "term"]} **/
function ajax_search_by_title() {
			check_ajax_referer( 'el_search_action', 'secure_nonce' );
			if ( !current_user_can( 'edit_posts' ) ) {
				wp_send_json_error( 'Unauthorized' );
			}

			$post_type	 = !empty( $_POST[ 'post_type' ] ) ? sanitize_text_field( wp_unslash( $_POST[ 'post_type' ] ) ) : 'post';
			$search		 = !empty( $_POST[ 'term' ] ) ? sanitize_text_field( wp_unslash( $_POST[ 'term' ] ) ) : '';

			$post_list	 = self::query_by_title( $post_type, $search );

			$results = [];
			foreach ( $post_list as $key => $title ) {
				$results[] = [ 'id' => $key, 'text' => $title ];
			}

			wp_send_json( [ 'results' => $results ] );
		}


