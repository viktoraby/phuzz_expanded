<?php
/***
*
*Found actions: 7
*Found functions:7
*Extracted functions:7
*Total parameter names extracted: 5
*Overview: {'update_menu_order': {'update-menu-order'}, 'dismiss_notices': {'scporder_dismiss_notices'}, 'update_menu_order_tags': {'update-menu-order-tags'}, 'scpo_ajax_reset_order': {'scpo_reset_order'}, 'scpo_ajax_set_position': {'scpo_set_position'}, 'refresh_nonce': {'scpo_refresh_nonce'}, 'ajax': {'epsilon_simple_review'}}
*
***/

/** Function update_menu_order() called by wp_ajax hooks: {'update-menu-order'} **/
/** Parameters found in function update_menu_order(): {"post": ["order"]} **/
function update_menu_order(): void {
		global $wpdb;

		check_ajax_referer( 'scporder_nonce_action', 'nonce' );

		if ( ! $this->scporder_user_can_reorder() ) {
			wp_send_json_error( [ 'message' => __( 'Permission denied.', 'simple-custom-post-order' ) ], 403 );
		}

		$order = isset( $_POST['order'] ) ? sanitize_text_field( wp_unslash( $_POST['order'] ) ) : '';
		parse_str( $order, $data );

		if ( ! is_array( $data ) || empty( $data ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid data.', 'simple-custom-post-order' ) ] );
		}

		// Collect all IDs first
		$id_arr = [];
		foreach ( $data as $values ) {
			if ( is_array( $values ) ) {
				foreach ( $values as $id ) {
					$id_arr[] = absint( $id );
				}
			}
		}

		// Object-level authorization (defense against forged IDs / IDOR).
		// scporder_user_can_reorder() only gates *access* to this endpoint; it
		// does not prove the caller may edit the specific posts they submitted.
		// Require every submitted ID to be an enabled sortable post type that the
		// current user can actually edit, so a user holding the broad reorder
		// capability cannot reshuffle menu_order for posts/pages they cannot edit.
		$objects = $this->get_scporder_options_objects();
		foreach ( $id_arr as $id ) {
			if ( ! $this->scporder_user_can_edit_post( $id, $objects ) ) {
				wp_send_json_error( [ 'message' => __( 'Permission denied.', 'simple-custom-post-order' ) ], 403 );
			}
		}

		// Get current menu_order values
		$menu_order_arr = [];
		foreach ( $id_arr as $id ) {
			$menu_order = $wpdb->get_var(
				$wpdb->prepare( "SELECT menu_order FROM $wpdb->posts WHERE ID = %d", $id )
			);
			if ( null !== $menu_order ) {
				$menu_order_arr[] = (int) $menu_order;
			}
		}

		sort( $menu_order_arr );

		// Update posts and collect IDs for cache invalidation
		$updated_ids = [];
		$position = 0;
		foreach ( $data as $values ) {
			if ( is_array( $values ) ) {
				foreach ( $values as $id ) {
					$id = absint( $id );
					if ( isset( $menu_order_arr[ $position ] ) ) {
						$wpdb->update(
							$wpdb->posts,
							[ 'menu_order' => $menu_order_arr[ $position ] ],
							[ 'ID' => $id ],
							[ '%d' ],
							[ '%d' ]
						);
						$updated_ids[] = $id;
					}
					$position++;
				}
			}
		}

		// Targeted cache invalidation - only for posts we actually changed
		$this->invalidate_order_cache( $wpdb->posts, $updated_ids );

		do_action( 'scp_update_menu_order' );

		wp_send_json_success( [ 'message' => __( 'Order updated.', 'simple-custom-post-order' ) ] );
	}


/** Function dismiss_notices() called by wp_ajax hooks: {'scporder_dismiss_notices'} **/
/** No params detected :-/ **/


/** Function update_menu_order_tags() called by wp_ajax hooks: {'update-menu-order-tags'} **/
/** Parameters found in function update_menu_order_tags(): {"post": ["order"]} **/
function update_menu_order_tags(): void {
		global $wpdb;

		check_ajax_referer( 'scporder_nonce_action', 'nonce' );

		if ( ! $this->scporder_user_can_reorder() ) {
			wp_send_json_error( [ 'message' => __( 'Permission denied.', 'simple-custom-post-order' ) ], 403 );
		}

		$order = isset( $_POST['order'] ) ? sanitize_text_field( wp_unslash( $_POST['order'] ) ) : '';
		parse_str( $order, $data );

		if ( ! is_array( $data ) || empty( $data ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid data.', 'simple-custom-post-order' ) ] );
		}

		// Collect all IDs first
		$id_arr = [];
		foreach ( $data as $values ) {
			if ( is_array( $values ) ) {
				foreach ( $values as $id ) {
					$id_arr[] = absint( $id );
				}
			}
		}

		// Object-level authorization (defense against forged IDs / IDOR).
		// Require every submitted term to belong to an enabled sortable taxonomy
		// the current user is allowed to manage, so the broad reorder capability
		// alone cannot reshuffle term_order for taxonomies outside their reach.
		$tags = $this->get_scporder_options_tags();
		foreach ( $id_arr as $id ) {
			if ( ! $this->scporder_user_can_edit_term( $id, $tags ) ) {
				wp_send_json_error( [ 'message' => __( 'Permission denied.', 'simple-custom-post-order' ) ], 403 );
			}
		}

		// Get current term_order values
		$term_order_arr = [];
		foreach ( $id_arr as $id ) {
			$term_order = $wpdb->get_var(
				$wpdb->prepare( "SELECT term_order FROM $wpdb->terms WHERE term_id = %d", $id )
			);
			if ( null !== $term_order ) {
				$term_order_arr[] = (int) $term_order;
			}
		}

		sort( $term_order_arr );

		// Update terms and collect IDs for cache invalidation
		$updated_ids = [];
		$position = 0;
		foreach ( $data as $values ) {
			if ( is_array( $values ) ) {
				foreach ( $values as $id ) {
					$id = absint( $id );
					if ( isset( $term_order_arr[ $position ] ) ) {
						$wpdb->update(
							$wpdb->terms,
							[ 'term_order' => $term_order_arr[ $position ] ],
							[ 'term_id' => $id ],
							[ '%d' ],
							[ '%d' ]
						);
						$updated_ids[] = $id;
					}
					$position++;
				}
			}
		}

		// Targeted cache invalidation - only for terms we actually changed. The
		// helper resolves each term's taxonomy first: clean_term_cache() reads a
		// bare ID list as term_taxonomy_ids, which busts the wrong entries on any
		// site where term_id and term_taxonomy_id have drifted apart.
		$this->invalidate_order_cache( $wpdb->terms, $updated_ids );

		do_action( 'scp_update_menu_order_tags' );

		wp_send_json_success( [ 'message' => __( 'Order updated.', 'simple-custom-post-order' ) ] );
	}


/** Function scpo_ajax_reset_order() called by wp_ajax hooks: {'scpo_reset_order'} **/
/** Parameters found in function scpo_ajax_reset_order(): {"post": ["action", "items"]} **/
function scpo_ajax_reset_order(): void {
		global $wpdb;

		if ( ! isset( $_POST['action'] ) || 'scpo_reset_order' !== $_POST['action'] ) {
			return;
		}

		check_ajax_referer( 'scpo-reset-order', 'scpo_security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => __( 'Permission denied.', 'simple-custom-post-order' ) ], 403 );
		}

		$items = isset( $_POST['items'] ) && is_array( $_POST['items'] )
			? array_map( 'sanitize_key', $_POST['items'] )
			: [];

		if ( empty( $items ) ) {
			wp_send_json_error( [ 'message' => __( 'No items selected.', 'simple-custom-post-order' ) ] );
		}

		// Build proper IN clause with individual placeholders
		$placeholders = implode( ', ', array_fill( 0, count( $items ), '%s' ) );
		$query = $wpdb->prepare(
			"UPDATE $wpdb->posts SET `menu_order` = 0 WHERE `post_type` IN ($placeholders)",
			$items
		);
		$result = $wpdb->query( $query );

		$scpo_options = get_option( 'scporder_options' );

		if ( false !== $scpo_options && isset( $scpo_options['objects'] ) ) {
			$scpo_options['objects'] = array_diff( $scpo_options['objects'], $items );
			update_option( 'scporder_options', $scpo_options );
		}

		if ( false !== $result ) {
			wp_send_json_success( [ 'message' => __( 'Items have been reset.', 'simple-custom-post-order' ) ] );
		} else {
			wp_send_json_error( [ 'message' => __( 'Failed to reset items.', 'simple-custom-post-order' ) ] );
		}
	}


/** Function scpo_ajax_set_position() called by wp_ajax hooks: {'scpo_set_position'} **/
/** Parameters found in function scpo_ajax_set_position(): {"post": ["id", "position"]} **/
function scpo_ajax_set_position(): void {
		check_ajax_referer( 'scporder_nonce_action', 'nonce' );

		if ( ! $this->scporder_user_can_reorder() ) {
			wp_send_json_error( [ 'message' => __( 'Permission denied.', 'simple-custom-post-order' ) ], 403 );
		}

		$post_id  = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;
		$position = isset( $_POST['position'] ) ? absint( $_POST['position'] ) : 0;
		$post     = $post_id ? get_post( $post_id ) : null;

		if ( ! $post || ! in_array( $post->post_type, $this->get_scporder_options_objects(), true ) || is_post_type_hierarchical( $post->post_type ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid item.', 'simple-custom-post-order' ) ] );
		}

		// Object-level authorization: holding the reorder capability is not enough,
		// the user must be able to edit this specific post (defense against IDOR).
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			wp_send_json_error( [ 'message' => __( 'Permission denied.', 'simple-custom-post-order' ) ], 403 );
		}

		global $wpdb;
		$ids = array_map(
			'intval',
			$wpdb->get_col(
				$wpdb->prepare(
					"SELECT ID FROM $wpdb->posts
					WHERE post_type = %s AND post_status IN ('publish','pending','draft','private','future')
					ORDER BY menu_order ASC, post_date DESC",
					$post->post_type
				)
			)
		);

		// Pull the post out, then splice it in at the requested 1-based position.
		$ids    = array_values( array_diff( $ids, [ $post_id ] ) );
		$target = max( 1, min( $position, count( $ids ) + 1 ) ) - 1;
		array_splice( $ids, $target, 0, [ $post_id ] );

		foreach ( $ids as $i => $id ) {
			$wpdb->update( $wpdb->posts, [ 'menu_order' => $i + 1 ], [ 'ID' => (int) $id ] );
			clean_post_cache( (int) $id );
		}

		do_action( 'scp_update_menu_order' );
		wp_send_json_success( [ 'message' => __( 'Order updated.', 'simple-custom-post-order' ) ] );
	}


/** Function refresh_nonce() called by wp_ajax hooks: {'scpo_refresh_nonce'} **/
/** No params detected :-/ **/


/** Function ajax() called by wp_ajax hooks: {'epsilon_simple_review'} **/
/** Parameters found in function ajax(): {"post": ["check"]} **/
function ajax(): void {
		check_ajax_referer( 'epsilon-simple-review', 'security' );

		if ( ! isset( $_POST['check'] ) ) {
			wp_die( 'ok' );
		}

		$check = sanitize_text_field( wp_unslash( $_POST['check'] ) );
		$time  = get_option( 'simple-rate-time' );

		if ( 'epsilon-rate' === $check ) {
			$time = time() + YEAR_IN_SECONDS * 5;
		} elseif ( 'epsilon-later' === $check ) {
			$time = time() + WEEK_IN_SECONDS;
		} elseif ( 'epsilon-no-rate' === $check ) {
			$time = time() + YEAR_IN_SECONDS * 5;
		}

		update_option( 'simple-rate-time', $time );
		wp_die( 'ok' );
	}


