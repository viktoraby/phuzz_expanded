<?php
/***
*
*Found actions: 3
*Found functions:3
*Extracted functions:3
*Total parameter names extracted: 3
*Overview: {'hicpo_update_menu_order': {'update-menu-order'}, 'hicpo_update_menu_order_sites': {'update-menu-order-sites'}, 'hicpo_update_menu_order_tags': {'update-menu-order-tags'}}
*
***/

/** Function hicpo_update_menu_order() called by wp_ajax hooks: {'update-menu-order'} **/
/** Parameters found in function hicpo_update_menu_order(): {"post": ["order"]} **/
function hicpo_update_menu_order() {

		check_ajax_referer( 'hicpojs-ajax-nonce', 'nonce' );
		if ( ! current_user_can( 'hicpo_update_menu_order' ) ) {
			wp_send_json_error( [ 'message' => __( 'You are not allowed to reorder posts.', 'intuitive-custom-post-order' ) ], 403 );
		}
		if ( ! isset( $_POST['order'] ) ) {
			wp_send_json_error( [ 'message' => __( 'Missing order payload.', 'intuitive-custom-post-order' ) ], 400 );
		}

		$order = sanitize_text_field( wp_unslash( $_POST['order'] ) );
		parse_str( $order, $data );
		if ( ! is_array( $data ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid order payload.', 'intuitive-custom-post-order' ) ], 400 );
		}
		
		// get objects per now page
		$id_arr = [];
		foreach ( $data as $key => $values ) {
			foreach ( $values as $position => $id ) {
				$id_arr[] = absint( $id );
			}
		}

		global $wpdb;

		// get menu_order of objects per now page
		$menu_order_arr = [];
		foreach ( $id_arr as $key => $id ) {
			$results = $wpdb->get_results( $wpdb->prepare(
				"SELECT menu_order FROM $wpdb->posts WHERE ID = %d",
				$id
			) );
			foreach ( $results as $result ) {
				$menu_order_arr[] = $result->menu_order;
			}
		}

		// maintains key association = no
		sort( $menu_order_arr );

		foreach ( $data as $key => $values ) {
			foreach ( $values as $position => $id ) {
				$wpdb->update(
					$wpdb->posts,
					[ 'menu_order' => isset( $menu_order_arr[ $position ] ) ? (int) $menu_order_arr[ $position ] : 0 ],
					[ 'ID' => absint( $id ) ],
					[ '%d' ],
					[ '%d' ]
				);
			}
		}

		// same number check
		$last_id   = end( $id_arr );
		$post_type = $last_id ? get_post_type( $last_id ) : 'post';
		$sort_ids  = [];

		$query = $wpdb->prepare(
			"
			SELECT COUNT(menu_order) AS mo_count, post_type, menu_order FROM $wpdb->posts
			WHERE post_type = %s AND post_status IN ('publish', 'pending', 'draft', 'private', 'future')
			AND menu_order > 0 GROUP BY post_type, menu_order HAVING (mo_count) > 1
			",
			$post_type
		);
		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- $query is prepared.
		$results = $wpdb->get_results( $query );
		if ( count( $results ) > 0 ) {
			// menu_order refresh
			$query = $wpdb->prepare(
				"
			SELECT ID, menu_order FROM $wpdb->posts
			WHERE post_type = %s AND post_status IN ('publish', 'pending', 'draft', 'private', 'future')
			AND menu_order > 0 ORDER BY menu_order
			",
				$post_type
			);
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- $query is prepared.
			$results = $wpdb->get_results( $query );
			foreach ( $results as $key => $result ) {
				$view_posi = array_search( $result->ID, $id_arr, true );
				if ( false === $view_posi ) {
					$view_posi = 999;
				}
				$sort_key = ( $result->menu_order * 1000 ) + $view_posi;
				$sort_ids[ $sort_key ] = $result->ID;
			}
			ksort( $sort_ids );

			$order_no = 0;
			foreach ( $sort_ids as $key => $pid ) {
				$order_no++;
				$wpdb->update(
					$wpdb->posts,
					[ 'menu_order' => $order_no ],
					[ 'ID' => absint( $pid ) ],
					[ '%d' ],
					[ '%d' ]
				);
			}
			wp_send_json_success( [ 'message' => __( 'Order updated.', 'intuitive-custom-post-order' ) ] );
		}
	}


/** Function hicpo_update_menu_order_sites() called by wp_ajax hooks: {'update-menu-order-sites'} **/
/** Parameters found in function hicpo_update_menu_order_sites(): {"post": ["order"]} **/
function hicpo_update_menu_order_sites() {
		check_ajax_referer( 'hicpojs-ajax-nonce', 'nonce' );
		if ( ! current_user_can( 'hicpo_update_menu_order_sites' ) ) {
			wp_send_json_error( [ 'message' => __( 'You are not allowed to reorder sites.', 'intuitive-custom-post-order' ) ], 403 );
		}
		if ( ! isset( $_POST['order'] ) ) {
			wp_send_json_error( [ 'message' => __( 'Missing order payload.', 'intuitive-custom-post-order' ) ], 400 );
		}

		$order = sanitize_text_field( wp_unslash( $_POST['order'] ) );
		parse_str( $order, $data );
		if ( ! is_array( $data ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid order payload.', 'intuitive-custom-post-order' ) ], 400 );
		}

		$id_arr = [];
		foreach ( $data as $key => $values ) {
			foreach ( $values as $position => $id ) {
				$id_arr[] = absint( $id );
			}
		}

		global $wpdb;

		foreach ( $data as $key => $values ) {
			foreach ( $values as $position => $id ) {
				$wpdb->update(
					$wpdb->blogs,
					[ 'menu_order' => (int) $position + 1 ],
					[ 'blog_id' => absint( $id ) ],
					[ '%d' ],
					[ '%d' ]
				);
			}
		}
		wp_send_json_success( [ 'message' => __( 'Order updated.', 'intuitive-custom-post-order' ) ] );
	}


/** Function hicpo_update_menu_order_tags() called by wp_ajax hooks: {'update-menu-order-tags'} **/
/** Parameters found in function hicpo_update_menu_order_tags(): {"post": ["order"]} **/
function hicpo_update_menu_order_tags() {
		check_ajax_referer( 'hicpojs-ajax-nonce', 'nonce' );
		if ( ! current_user_can( 'hicpo_update_menu_order' ) ) {
			wp_send_json_error( [ 'message' => __( 'You are not allowed to reorder terms.', 'intuitive-custom-post-order' ) ], 403 );
		}
		if ( ! isset( $_POST['order'] ) ) {
			wp_send_json_error( [ 'message' => __( 'Missing order payload.', 'intuitive-custom-post-order' ) ], 400 );
		}

		$order = sanitize_text_field( wp_unslash( $_POST['order'] ) );
		parse_str( $order, $data );
		if ( ! is_array( $data ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid order payload.', 'intuitive-custom-post-order' ) ], 400 );
		}
		
		$id_arr = [];
		foreach ( $data as $key => $values ) {
			foreach ( $values as $position => $id ) {
				$id_arr[] = absint( $id );
			}
		}

		global $wpdb;

		$menu_order_arr = [];
		foreach ( $id_arr as $key => $id ) {
			$results = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT term_order FROM $wpdb->terms WHERE term_id = %d",
					$id
				)
			);
			foreach ( $results as $result ) {
				$menu_order_arr[] = $result->term_order;
			}
		}
		sort( $menu_order_arr );

		foreach ( $data as $key => $values ) {
			foreach ( $values as $position => $id ) {
				$wpdb->update(
					$wpdb->terms,
					[ 'term_order' => isset( $menu_order_arr[ $position ] ) ? (int) $menu_order_arr[ $position ] : 0 ],
					[ 'term_id' => absint( $id ) ],
					[ '%d' ],
					[ '%d' ]
				);
			}
		}

		// same number check
		$last_id = end( $id_arr );
		$taxonomy = '';
		if ( $last_id ) {
			$term = get_term( $last_id );
			if ( ! is_wp_error( $term ) && $term && isset( $term->taxonomy ) ) {
				$taxonomy = $term->taxonomy;
			}
		}
		if ( '' === $taxonomy ) {
			wp_send_json_success( [ 'message' => __( 'Order updated.', 'intuitive-custom-post-order' ) ] );
		}
		$query = $wpdb->prepare(
			"
			SELECT COUNT(term_order) AS to_count, term_order
			FROM $wpdb->terms AS terms
			INNER JOIN $wpdb->term_taxonomy AS term_taxonomy ON ( terms.term_id = term_taxonomy.term_id )
			WHERE term_taxonomy.taxonomy = %s GROUP BY taxonomy, term_order HAVING (to_count) > 1
			",
			$taxonomy
		);
		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- $query is prepared.
		$results = $wpdb->get_results( $query );

		if ( count( $results ) > 0 ) {
			// term_order refresh
			$query = $wpdb->prepare(
				"
				SELECT terms.term_id, term_order
				FROM $wpdb->terms AS terms
				INNER JOIN $wpdb->term_taxonomy AS term_taxonomy ON ( terms.term_id = term_taxonomy.term_id )
				WHERE term_taxonomy.taxonomy = %s
				ORDER BY term_order ASC
				",
				$taxonomy
			);
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- $query is prepared.
			$results = $wpdb->get_results( $query );

			$sort_ids = [];
			foreach ( $results as $key => $result ) {
				$view_posi = array_search( $result->term_id, $id_arr, true );
				if ( false === $view_posi ) {
					$view_posi = 999;
				}
				$sort_key = ( $result->term_order * 1000 ) + $view_posi;
				$sort_ids[ $sort_key ] = $result->term_id;
			}
			ksort( $sort_ids );
			$order_no = 0;
			foreach ( $sort_ids as $key => $tid ) {
				$order_no++;
				$wpdb->update(
					$wpdb->terms,
					[ 'term_order' => $order_no ],
					[ 'term_id' => absint( $tid ) ],
					[ '%d' ],
					[ '%d' ]
				);
			}
		}
		wp_send_json_success( [ 'message' => __( 'Order updated.', 'intuitive-custom-post-order' ) ] );
	}


