<?php
/***
*
*Found actions: 10
*Found functions:10
*Extracted functions:10
*Total parameter names extracted: 5
*Overview: {'ajax_display_preview': {'yarpp_display_preview'}, 'ajax_display': {'yarpp_display'}, 'ajax_display_exclude_terms': {'yarpp_display_exclude_terms'}, 'ajax_pro_set_display_types': {'yarpp_pro_set_display_types'}, 'ajax_optin_enable': {'yarpp_optin_enable'}, 'ajax_optin_disable': {'yarpp_optin_disable'}, 'ajax_switch': {'yarpp_switch'}, 'ajax_optin_data': {'yarpp_optin_data'}, 'ajax_clear_cache': {'yarpp_clear_cache'}, 'ajax_display_demo': {'yarpp_display_demo'}}
*
***/

/** Function ajax_display_preview() called by wp_ajax hooks: {'yarpp_display_preview'} **/
/** Parameters found in function ajax_display_preview(): {"post": ["size"]} **/
function ajax_display_preview() {
		check_ajax_referer( 'yarpp_display_preview' );

		if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array(
				'message' => 'Not allowed',
			), 405 );
		}

		header( 'HTTP/1.1 200' );
		header( 'Content-Type: text/html; charset=UTF-8' );

		$defaults = array(
			'domain'        => 'website',
			'limit'         => yarpp_get_option( 'limit' ),
			'template'      => yarpp_get_option( 'template' ),
			'order'         => yarpp_get_option( 'order' ),
			'promote_yarpp' => yarpp_get_option( 'promote_yarpp' ),
			'show_excerpt'  => yarpp_get_option( 'show_excerpt' ),
			'thumbnails_default'  => yarpp_get_option( 'thumbnails_default' ),
		);

		$allowed = array(
			'limit',
			'template',
			'order',
			'promote_yarpp',
			'thumbnails_heading',
			'thumbnails_default',
			'before_title',
			'after_title',
			'show_excerpt',
			'excerpt_length',
			'before_post',
			'after_post',
			'before_related',
			'after_related',
			'size',
		);

		$args = array_intersect_key( $_POST, array_flip( $allowed ) );
		$args = array_merge( $defaults, $args );

		foreach ( $args as $key => $value ) {
			$args[$key] = wp_unslash($value);
		}

		$return = $this->core->display_demo_related( $args, false );

		$size = isset( $_POST['size'] ) ? sanitize_text_field( $_POST['size'] ) : 'thumbnail';

		$load_styles = file_get_contents( plugin_dir_path(YARPP_MAIN_FILE) . '/style/related.css' );

		if ( 'thumbnails' === $args['template'] ) {
			$load_styles .= file_get_contents( plugin_dir_path(YARPP_MAIN_FILE) . '/style/styles_thumbnails.css' );
		}
		if ( ! in_array( $args['template'], array( 'builtin', 'list' ), true ) ) {
			$load_styles .= yarpp_thumbnail_inline_css( yarpp_get_image_sizes( $size ) );
		}

		wp_send_json(
			array(
				'styles' => $load_styles,
				'html'   => $return,
				'code'   => htmlspecialchars( $return ),
			)
		);
	}


/** Function ajax_display() called by wp_ajax hooks: {'yarpp_display'} **/
/** Parameters found in function ajax_display(): {"request": ["ID", "domain", "refresh"]} **/
function ajax_display() {
		check_ajax_referer( 'yarpp_display' );

		if ( ! isset( $_REQUEST['ID'] ) ) {
			return;
		}

		$args = array(
			'domain' => isset( $_REQUEST['domain'] ) ? $_REQUEST['domain'] : 'website',
		);
		if ( isset( $_REQUEST['refresh'] ) && $this->core->cache instanceof YARPP_Cache ) {
			$this->core->cache->clear( $_REQUEST['ID'] );
		}
		$return = $this->core->display_related( absint( $_REQUEST['ID'] ), $args, false );

		header( 'HTTP/1.1 200' );
		header( 'Content-Type: text/html; charset=UTF-8' );
		echo $return;

		die();
	}


/** Function ajax_display_exclude_terms() called by wp_ajax hooks: {'yarpp_display_exclude_terms'} **/
/** Parameters found in function ajax_display_exclude_terms(): {"request": ["taxonomy", "offset"]} **/
function ajax_display_exclude_terms() {
		check_ajax_referer( 'yarpp_display_exclude_terms' );

		if ( ! isset( $_REQUEST['taxonomy'] ) ) {
			return;
		}

		$taxonomy = (string) $_REQUEST['taxonomy'];

		header( 'HTTP/1.1 200' );
		header( 'Content-Type: text/html; charset=UTF-8' );

		$exclude_tt_ids   = wp_parse_id_list( $this->core->get_option( 'exclude' ) );
		$exclude_term_ids = $this->get_term_ids_from_tt_ids( $taxonomy, $exclude_tt_ids );
		// if ('category' === $taxonomy) $exclude .= ','.get_option('default_category');

		$terms = get_terms(
			$taxonomy,
			array(
				'exclude'      => $exclude_term_ids,
				'hide_empty'   => false,
				'hierarchical' => false,
				'number'       => 100,
				'offset'       => $_REQUEST['offset'],
			)
		);

		if ( ! count( $terms ) ) {
			echo ':('; // no more :(
			exit;
		}

		foreach ( $terms as $term ) {
			echo "<span><input type='checkbox' name='exclude[{$term->term_taxonomy_id}]' id='exclude_{$term->term_taxonomy_id}' value='true' /> <label for='exclude_{$term->term_taxonomy_id}'>" . esc_html( $term->name ) . '</label></span> ';
		}
		exit;
	}


/** Function ajax_pro_set_display_types() called by wp_ajax hooks: {'yarpp_pro_set_display_types'} **/
/** Parameters found in function ajax_pro_set_display_types(): {"request": ["_wpnonce", "types"]} **/
function ajax_pro_set_display_types() {
		// Verify nonce
		if ( ! isset($_REQUEST['_wpnonce']) || ! wp_verify_nonce($_REQUEST['_wpnonce'], 'yarpp_pro_set_display_types') ) {
			wp_send_json_error(array( 'message' => 'Invalid nonce' ), 403);
		}

		// Verify user capabilities
		if ( ! current_user_can('manage_options') ) {
			wp_send_json_error(array( 'message' => 'Access denied' ), 403);
		}

		// Sanitize the 'types' parameter
		$types = ( isset($_REQUEST['types']) && is_array($_REQUEST['types']) )
			? array_map('sanitize_text_field', $_REQUEST['types'])
			: array();

		// Update the option
		$yarpp_pro                            = get_option('yarpp_pro');
		$yarpp_pro['auto_display_post_types'] = $types;
		update_option('yarpp_pro', $yarpp_pro);

		wp_send_json_success('ok');
	}


/** Function ajax_optin_enable() called by wp_ajax hooks: {'yarpp_optin_enable'} **/
/** No params detected :-/ **/


/** Function ajax_optin_disable() called by wp_ajax hooks: {'yarpp_optin_disable'} **/
/** No params detected :-/ **/


/** Function ajax_switch() called by wp_ajax hooks: {'yarpp_switch'} **/
/** Parameters found in function ajax_switch(): {"get": ["go"]} **/
function ajax_switch() {
		check_ajax_referer( 'yarpp_switch' );

		if ( ! is_admin() ||
		! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( ! isset( $_GET['go'] ) || trim( $_GET['go'] ) === '' ) {
			die();
		}

		$switch = htmlentities( $_GET['go'] );

		function switchYarppPro( $status ) {
			$yarppPro = get_option( 'yarpp_pro' );
			$yarpp    = get_option( 'yarpp' );

			if ( $status ) {
				$yarppPro['optin'] = (bool) $yarpp['optin'];
				$yarpp['optin']    = false;
			} else {
				$yarpp['optin'] = (bool) $yarppPro['optin'];
			}

			$yarppPro['active'] = $status;
			update_option( 'yarpp', $yarpp );
			update_option( 'yarpp_pro', $yarppPro );

			header( 'HTTP/1.1 200' );
			header( 'Content-Type: text/plain; charset=UTF-8' );
			die( 'ok' );
		}

		switch ( $switch ) {
			case 'basic':
				switchYarppPro( 0 );
				break;
			case 'pro':
				switchYarppPro( 1 );
				break;
		}
	}


/** Function ajax_optin_data() called by wp_ajax hooks: {'yarpp_optin_data'} **/
/** No params detected :-/ **/


/** Function ajax_clear_cache() called by wp_ajax hooks: {'yarpp_clear_cache'} **/
/** No params detected :-/ **/


/** Function ajax_display_demo() called by wp_ajax hooks: {'yarpp_display_demo'} **/
/** No params detected :-/ **/


