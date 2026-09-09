<?php
/***
*
*Found actions: 9
*Found functions:8
*Extracted functions:8
*Total parameter names extracted: 2
*Overview: {'relevanssi_update_counts': {'relevanssi_update_counts', 'nopriv_relevanssi_update_counts'}, 'relevanssi_admin_search': {'relevanssi_admin_search'}, 'relevanssi_list_categories': {'relevanssi_list_categories'}, 'relevanssi_truncate_index_ajax_wrapper': {'relevanssi_truncate_index'}, 'relevanssi_count_missing_posts_ajax_wrapper': {'relevanssi_count_missing_posts'}, 'relevanssi_list_custom_fields': {'relevanssi_list_custom_fields'}, 'relevanssi_index_posts_ajax_wrapper': {'relevanssi_index_posts'}, 'relevanssi_count_posts_ajax_wrapper': {'relevanssi_count_posts'}}
*
***/

/** Function relevanssi_update_counts() called by wp_ajax hooks: {'relevanssi_update_counts', 'nopriv_relevanssi_update_counts'} **/
/** No params detected :-/ **/


/** Function relevanssi_admin_search() called by wp_ajax hooks: {'relevanssi_admin_search'} **/
/** Parameters found in function relevanssi_admin_search(): {"post": ["args", "posts_per_page", "post_types", "offset", "s"]} **/
function relevanssi_admin_search() {
	check_ajax_referer( 'relevanssi_admin_search_nonce', 'security' );
	/**
	 * Filters the capability required to access Relevanssi admin search page.
	 *
	 * @param string $capability The capability required. Default 'edit_posts'.
	 */
	if ( ! current_user_can( apply_filters( 'relevanssi_admin_search_capability', 'edit_posts' ) ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'relevanssi' ) );
	}

	$args = array();
	if ( isset( $_POST['args'] ) ) {
		parse_str( $_POST['args'], $args );
		$args = wp_slash( $args );
	}
	if ( isset( $_POST['posts_per_page'] ) ) {
		$posts_per_page = intval( $_POST['posts_per_page'] );
		if ( $posts_per_page > 0 ) {
			$args['posts_per_page'] = $posts_per_page;
		}
	}
	if ( isset( $_POST['post_types'] ) ) {
		$post_type          = $_POST['post_types'];
		$args['post_types'] = $post_type;
	}
	if ( isset( $_POST['offset'] ) ) {
		$offset = intval( $_POST['offset'] );
		if ( $offset > 0 ) {
			$args['offset'] = $offset;
		}
	}
	if ( isset( $_POST['s'] ) ) {
		$args['s'] = $_POST['s'];
	}

	$query = new WP_Query();
	$query->parse_query( $args );
	$query->set( 'relevanssi_admin_search', true );
	$query = apply_filters( 'relevanssi_modify_wp_query', $query );
	relevanssi_do_query( $query );

	$results = relevanssi_admin_search_debugging_info( $query );

	// Take the posts array and create a string out of it.
	$offset = 0;
	if ( isset( $query->query_vars['offset'] ) ) {
		$offset = $query->query_vars['offset'];
	}
	$results .= relevanssi_admin_search_format_posts( $query->posts, $query->found_posts, $offset, $args['s'] );

	echo wp_json_encode( $results );
	wp_die();
}


/** Function relevanssi_list_categories() called by wp_ajax hooks: {'relevanssi_list_categories'} **/
/** No params detected :-/ **/


/** Function relevanssi_truncate_index_ajax_wrapper() called by wp_ajax hooks: {'relevanssi_truncate_index'} **/
/** No params detected :-/ **/


/** Function relevanssi_count_missing_posts_ajax_wrapper() called by wp_ajax hooks: {'relevanssi_count_missing_posts'} **/
/** No params detected :-/ **/


/** Function relevanssi_list_custom_fields() called by wp_ajax hooks: {'relevanssi_list_custom_fields'} **/
/** No params detected :-/ **/


/** Function relevanssi_index_posts_ajax_wrapper() called by wp_ajax hooks: {'relevanssi_index_posts'} **/
/** Parameters found in function relevanssi_index_posts_ajax_wrapper(): {"post": ["completed", "total", "offset", "limit", "extend"]} **/
function relevanssi_index_posts_ajax_wrapper() {
	check_ajax_referer( 'relevanssi_indexing_nonce', 'security' );
	relevanssi_current_user_can_access_options();

	$completed = absint( $_POST['completed'] );
	$total     = absint( $_POST['total'] );
	$offset    = absint( $_POST['offset'] );
	$limit     = absint( $_POST['limit'] );
	$extend    = strval( $_POST['extend'] );

	if ( 'true' === $extend ) {
		$extend = true;
	}

	if ( $limit < 1 ) {
		$limit = 1;
	}

	$response = array();

	$is_ajax = true;
	$verbose = false;
	if ( $extend ) {
		$offset = true;
	}

	$indexing_response = relevanssi_build_index( $offset, $verbose, $limit, $is_ajax );

	if ( $indexing_response['indexing_complete'] ) {
		$response['completed']   = 'done';
		$response['percentage']  = 100;
		$completed              += $indexing_response['indexed'];
		$response['total_posts'] = $completed;
		$processed               = $total;
	} else {
		$completed            += $indexing_response['indexed'];
		$response['completed'] = $completed;

		if ( true === $offset ) {
			$processed = $completed;
		} else {
			$offset    = $offset + $limit;
			$processed = $offset;
		}

		if ( $total > 0 ) {
			$response['percentage'] = $processed / $total * 100;
		} else {
			$response['percentage'] = 0;
		}
	}

	$response['feedback'] = sprintf(
		// translators: Number of posts indexed on this go, total number of posts indexed so far, number of posts processed on this go, total number of posts to process.
		_n(
			'Indexed %1$d post (total %2$d), processed %3$d / %4$d.',
			'Indexed %1$d posts (total %2$d), processed %3$d / %4$d.',
			$indexing_response['indexed'],
			'relevanssi'
		),
		$indexing_response['indexed'],
		$completed,
		$processed,
		$total
	) . "\n";
	$response['offset'] = $offset;

	echo wp_json_encode( $response );
	wp_die();
}


/** Function relevanssi_count_posts_ajax_wrapper() called by wp_ajax hooks: {'relevanssi_count_posts'} **/
/** No params detected :-/ **/


