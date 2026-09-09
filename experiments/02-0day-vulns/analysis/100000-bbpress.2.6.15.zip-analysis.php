<?php
/***
*
*Found actions: 4
*Found functions:4
*Extracted functions:4
*Total parameter names extracted: 2
*Overview: {'process_callback': {'bbp_converter_process'}, 'suggest_topic': {'bbp_suggest_topic'}, 'suggest_user': {'bbp_suggest_user'}, 'bbp_setup_converter': {'bbp_converter_process'}}
*
***/

/** Function process_callback() called by wp_ajax hooks: {'bbp_converter_process'} **/
/** No params detected :-/ **/


/** Function suggest_topic() called by wp_ajax hooks: {'bbp_suggest_topic'} **/
/** Parameters found in function suggest_topic(): {"request": ["q"]} **/
function suggest_topic() {

		// Do some very basic request checking
		$request = ! empty( $_REQUEST['q'] )
			? trim( $_REQUEST['q'] )
			: '';

		// Bail early if empty request
		if ( empty( $request ) ) {
			wp_die();
		}

		// Bail if user cannot moderate
		if ( ! current_user_can( 'moderate' ) ) {
			wp_die();
		}

		// Check the ajax nonce
		check_ajax_referer( 'bbp_suggest_topic_nonce' );

		// Allow the maximum number of results to be filtered
		$number = (int) apply_filters( 'bbp_suggest_topic_count', 10 );

		// Try to get some topics
		$topics = get_posts( array(
			's'              => bbp_db()->esc_like( $_REQUEST['q'] ),
			'post_type'      => bbp_get_topic_post_type(),
			'posts_per_page' => $number,

			// Performance
			'nopaging'               => true,
			'suppress_filters'       => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'ignore_sticky_posts'    => true,
			'no_found_rows'          => true
		) );

		// If we found some topics, loop through and display them
		if ( ! empty( $topics ) ) {
			foreach ( (array) $topics as $post ) {
				printf( esc_html__( '%1$s - %2$s', 'bbpress' ), bbp_get_topic_id( $post->ID ), bbp_get_topic_title( $post->ID ) . "\n" );
			}
		}
		die();
	}


/** Function suggest_user() called by wp_ajax hooks: {'bbp_suggest_user'} **/
/** Parameters found in function suggest_user(): {"request": ["q"]} **/
function suggest_user() {

		// Do some very basic request checking
		$request = ! empty( $_REQUEST['q'] )
			? trim( $_REQUEST['q'] )
			: '';

		// Bail early if empty request
		if ( empty( $request ) ) {
			wp_die();
		}

		// Bail if user cannot moderate
		if ( ! current_user_can( 'moderate' ) ) {
			wp_die();
		}

		// Check the ajax nonce
		check_ajax_referer( 'bbp_suggest_user_nonce' );

		// Fields to retrieve & search by
		$fields = $search = array( 'ID', 'user_nicename' );

		// Keymasters & Super-Mods can also search by email
		if ( current_user_can( 'keep_gate' ) || bbp_allow_super_mods() ) {

			// Add user_email to searchable columns
			array_push( $search, 'user_email' );

			// Unstrict to also allow some email characters
			$strict = false;

		// Strict sanitizing if not Keymaster or Super-Mod
		} else {
			$strict = true;
		}

		// Sanitize the request value (possibly not strictly)
		$suggest = sanitize_user( $request, $strict );

		// Bail if searching for invalid user string
		if ( empty( $suggest ) ) {
			wp_die();
		}

		// These single characters should not trigger a user query
		$disallowed_single_chars = array( '@', '.', '_', '-', '+', '!', '#', '$', '%', '&', '\\', '*', '+', '/', '=', '?', '^', '`', '{', '|', '}', '~' );

		// Bail if request is only for the above single characters
		if ( in_array( $suggest, $disallowed_single_chars, true ) ) {
			wp_die();
		}

		// Allow the maximum number of results to be filtered
		$number = (int) apply_filters( 'bbp_suggest_user_count', 10 );

		// Query database for users based on above criteria
		$users_query = new WP_User_Query( array(
			'search'         => '*' . bbp_db()->esc_like( $suggest ) . '*',
			'fields'         => $fields,
			'search_columns' => $search,
			'orderby'        => 'ID',
			'number'         => $number,
			'count_total'    => false
		) );

		// If we found some users, loop through and output them to the AJAX
		if ( ! empty( $users_query->results ) ) {
			foreach ( (array) $users_query->results as $user ) {
				printf( esc_html__( '%1$s - %2$s', 'bbpress' ), bbp_get_user_id( $user->ID ), bbp_get_user_nicename( $user->ID, array( 'force' => $user->user_nicename ) ) . "\n" );
			}
		}
		die();
	}


/** Function bbp_setup_converter() called by wp_ajax hooks: {'bbp_converter_process'} **/
/** No params detected :-/ **/


