<?php
/***
*
*Found actions: 21
*Found functions:20
*Extracted functions:20
*Total parameter names extracted: 19
*Overview: {'ignore_tour': {'rl-ignore-tour'}, 'delete_term': {'rl-folders-delete-term'}, 'add_term': {'rl-folders-add-term'}, 'deactivate_plugin': {'rl-deactivate-plugin'}, 'get_counters': {'rl-folders-get-counters'}, 'move_attachments': {'rl-folders-move-attachments'}, 'get_menu_content': {'rl-get-menu-content'}, 'rename_term': {'rl-folders-rename-term'}, 'ajax_query_media': {'rl_remote_library_query'}, 'ajax_save_attachment_compat': {'save-attachment-compat'}, 'post_get_galleries': {'rl-post-get-galleries'}, 'get_gallery_page': {'nopriv_rl-get-gallery-page-content', 'rl-get-gallery-page-content'}, 'load_old_taxonomies': {'rl-folders-load-old-taxonomies'}, 'dismiss_notice': {'rl_dismiss_notice'}, 'move_term': {'rl-folders-move-term'}, 'ajax_get_addons_feed': {'rl-get-addons-feed'}, 'post_gallery_preview': {'rl-post-gallery-preview'}, 'get_gallery_preview_content': {'rl-get-preview-content'}, 'select_term': {'rl-folders-select-term'}, 'ajax_upload_image': {'rl_upload_image'}}
*
***/

/** Function ignore_tour() called by wp_ajax hooks: {'rl-ignore-tour'} **/
/** Parameters found in function ignore_tour(): {"post": ["rl_nonce"]} **/
function ignore_tour() {
		if ( isset( $_POST['rl_nonce'] ) && ctype_alnum( $_POST['rl_nonce'] ) && wp_verify_nonce( $_POST['rl_nonce'], 'rl-ignore-tour' ) !== false )
			delete_transient( 'rl_active_tour' );

		exit;
	}


/** Function delete_term() called by wp_ajax hooks: {'rl-folders-delete-term'} **/
/** Parameters found in function delete_term(): {"post": ["term_id", "nonce", "children"]} **/
function delete_term() {
		// check rate limiting (30 requests per minute for destructive operations)
		if ( ! Responsive_Lightbox()->check_rate_limit( 'rl_delete_term', 30, 60 ) ) {
			wp_send_json_error( __( 'Rate limit exceeded. Please try again later.', 'responsive-lightbox' ) );
		}

		// no data?
		if ( ! isset( $_POST['term_id'], $_POST['nonce'], $_POST['children'] ) )
			wp_send_json_error();

		// invalid nonce?
		if ( ! ctype_alnum( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'rl-folders-ajax-library-nonce' ) )
			wp_send_json_error();

		// sanitize term id
		$term_id = (int) $_POST['term_id'];

		if ( $term_id <= 0 )
			wp_send_json_error();

		// get active taxonomy
		$taxonomy = $this->get_active_taxonomy();

		$remove_children = (int) $_POST['children'];

		// delete children?
		if ( $remove_children === 1 ) {
			// get term children
			$children = get_term_children( $term_id, $taxonomy );

			// found any children?
			if ( ! empty( $children ) && ! is_wp_error( $children ) ) {
				// reverse array to delete terms with no children first
				foreach ( array_reverse( $children ) as $child_id ) {
					// delete child
					wp_delete_term( $child_id, $taxonomy );
				}
			}
		}

		// delete parent
		if ( is_wp_error( wp_delete_term( $term_id, $taxonomy ) ) )
			wp_send_json_error();
		else
			wp_send_json_success( $this->get_folders( $taxonomy ) );
	}


/** Function add_term() called by wp_ajax hooks: {'rl-folders-add-term'} **/
/** Parameters found in function add_term(): {"post": ["parent_id", "name", "nonce"]} **/
function add_term() {
		// no data?
		if ( ! isset( $_POST['parent_id'], $_POST['name'], $_POST['nonce'] ) )
			wp_send_json_error();

		// invalid nonce?
		if ( ! ctype_alnum( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'rl-folders-ajax-library-nonce' ) )
			wp_send_json_error();

		// get active taxonomy
		$taxonomy = $this->get_active_taxonomy();

		// prepare data
		$original_slug = $slug = sanitize_title( $_POST['name'] );
		$name = sanitize_text_field( $_POST['name'] );
		$parent_id = (int) $_POST['parent_id'];

		// empty name?
		if ( $original_slug === '' || $name === '' )
			wp_send_json_error();

		// get all term slugs
		$terms = get_terms(
			[
				'taxonomy'		=> $taxonomy,
				'hide_empty'	=> false,
				'number'		=> 0,
				'fields'		=> 'id=>slug',
				'hierarchical'	=> true
			]
		);

		// any terms?
		if ( ! is_wp_error( $terms ) && is_array( $terms ) && ! empty( $terms ) ) {
			$i = 2;

			// slug already exists? create unique one
			while ( in_array( $slug, $terms, true ) ) {
				$slug = $original_slug . '-' . $i ++;
			}
		}

		// add new term, name is sanitized inside wp_insert_term with sanitize_term function
		$term = wp_insert_term(
			$name,
			$taxonomy,
			[
				'parent'	=> $parent_id,
				'slug'		=> $slug
			]
		);

		// error?
		if ( is_wp_error( $term ) )
			wp_send_json_error();

		$term = get_term( $term['term_id'], $taxonomy );

		// error?
		if ( is_wp_error( $term ) )
			wp_send_json_error();
		else {
			wp_send_json_success(
				[
					'name'		=> $term->name,
					'term_id'	=> $term->term_id,
					'url'		=> admin_url( 'upload.php?mode=' . $this->mode . '&' . $taxonomy . '=' . $term->term_id ),
					'select'	=> $this->get_folders( $taxonomy, $term->term_id )
				]
			);
		}
	}


/** Function deactivate_plugin() called by wp_ajax hooks: {'rl-deactivate-plugin'} **/
/** Parameters found in function deactivate_plugin(): {"post": ["nonce", "option_id", "other"]} **/
function deactivate_plugin() {
		// no nonce?
		if ( ! isset( $_POST['nonce'] ) )
			return;

		if ( ! ctype_alnum( $_POST['nonce'] ) )
			return;

		// check permissions
		if ( ! current_user_can( 'install_plugins' ) || wp_verify_nonce( $_POST['nonce'], 'rl-deactivate-plugin' ) === false )
			return;

		if ( isset( $_POST['option_id'] ) ) {
			$option_id = (int) $_POST['option_id'];
			$other = sanitize_text_field( $_POST['other'] );

			// avoid fake submissions
			if ( $option_id === 6 && $other === '' )
				wp_send_json_success();

			wp_remote_post(
				'https://www.dfactory.co/wp-json/api/v1/forms/',
				[
					'timeout'	=> 5,
					'blocking'	=> true,
					'headers'	=> [],
					'body'		=> [
						'id'		=> 13,
						'option'	=> $option_id,
						'other'		=> $other
					]
				]
			);

			wp_send_json_success();
		}

		wp_send_json_error();
	}


/** Function get_counters() called by wp_ajax hooks: {'rl-folders-get-counters'} **/
/** Parameters found in function get_counters(): {"post": ["nonce"]} **/
function get_counters() {
		global $wpdb;

		// invalid capability?
		if ( ! current_user_can( 'upload_files' ) )
			wp_send_json_error();

		// invalid nonce?
		if ( ! isset( $_POST['nonce'] ) || ! ctype_alnum( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'rl-folders-ajax-library-nonce' ) )
			wp_send_json_error();

		// get active taxonomy
		$taxonomy = $this->get_active_taxonomy();

		// taxonomy does not exist?
		if ( ! taxonomy_exists( $taxonomy ) )
			wp_send_json_error();

		$counter_statuses = apply_filters( 'rl_folders_counter_post_statuses', [ 'inherit' ], $taxonomy );
		$counter_statuses = array_values(
			array_filter(
				array_map( 'sanitize_key', (array) $counter_statuses ),
				static function( $status ) {
					return $status !== '';
				}
			)
		);

		if ( empty( $counter_statuses ) )
			$counter_statuses = [ 'inherit' ];

		$counters = [
			'all' => (int) apply_filters( 'rl_count_attachments', 0 ),
			'0' => 0
		];

		$term_ids = get_terms(
			[
				'taxonomy' => $taxonomy,
				'hide_empty' => false,
				'fields' => 'ids'
			]
		);

		if ( ! is_wp_error( $term_ids ) && ! empty( $term_ids ) ) {
			foreach ( $term_ids as $term_id ) {
				$counters[(string) (int) $term_id] = 0;
			}
		}

		$status_placeholders = implode( ', ', array_fill( 0, count( $counter_statuses ), '%s' ) );
		$query_values = $counter_statuses;
		$query_values[] = $taxonomy;

		$term_counts = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT tt.term_id AS term_id, COUNT(DISTINCT p.ID) AS term_count
				FROM {$wpdb->posts} p
				INNER JOIN {$wpdb->term_relationships} tr ON tr.object_id = p.ID
				INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
				WHERE p.post_type = 'attachment'
					AND p.post_status IN (" . $status_placeholders . ")
					AND tt.taxonomy = %s
				GROUP BY tt.term_id",
				$query_values
			),
			ARRAY_A
		);

		if ( ! empty( $term_counts ) ) {
			foreach ( $term_counts as $term_count ) {
				$term_id = isset( $term_count['term_id'] ) ? (int) $term_count['term_id'] : 0;

				if ( $term_id > 0 )
					$counters[(string) $term_id] = isset( $term_count['term_count'] ) ? (int) $term_count['term_count'] : 0;
			}
		}

		// root folder query (attachments without any folder term assigned)
		$root_query = new WP_Query(
			apply_filters(
				'rl_root_folder_query_args',
				[
					'rl_folders_root' => true,
					'posts_per_page' => 1,
					'post_type' => 'attachment',
					'post_status' => $counter_statuses,
					'fields' => 'ids',
					'no_found_rows' => false,
					'tax_query' => [
						[
							'relation' => 'AND',
							[
								'taxonomy' => $taxonomy,
								'field' => 'id',
								'terms' => 0,
								'include_children' => false,
								'operator' => 'NOT EXISTS'
							]
						]
					]
				]
			)
		);

		$counters['0'] = (int) $root_query->found_posts;

		wp_send_json_success(
			[
				'counters' => $counters
			]
		);
	}


/** Function move_attachments() called by wp_ajax hooks: {'rl-folders-move-attachments'} **/
/** Parameters found in function move_attachments(): {"post": ["attachment_ids", "old_term_id", "new_term_id", "nonce"]} **/
function move_attachments() {
		// no data?
		if ( ! isset( $_POST['attachment_ids'], $_POST['old_term_id'], $_POST['new_term_id'], $_POST['nonce'] ) )
			wp_send_json_error();

		// invalid nonce?
		if ( ! ctype_alnum( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'rl-folders-ajax-library-nonce' ) )
			wp_send_json_error();

		// not empty attachment ids array?
		if ( empty( $_POST['attachment_ids'] ) || ! is_array( $_POST['attachment_ids'] ) )
			wp_send_json_error();

		// prepare data
		$ids = $all_terms = [];
		$attachments = [
			'success'		=> [],
			'failure'		=> [],
			'duplicated'	=> []
		];

		// filter unwanted data
		$ids = array_unique( array_filter( array_map( 'intval', $_POST['attachment_ids'] ) ) );

		// no ids?
		if ( empty( $ids ) )
			wp_send_json_error();

		// prepare term ids
		$old_term_id = (int) $_POST['old_term_id'];
		$new_term_id = (int) $_POST['new_term_id'];

		// get active taxonomy
		$taxonomy = $this->get_active_taxonomy();

		// moving to root folder?
		if ( $new_term_id === 0 ) {
			foreach ( $ids as $id ) {
				// get attachment term ids
				$all_terms[$id] = wp_get_object_terms( $id, $taxonomy, [ 'fields' => 'ids' ] );

				// remove all terms assigned to attachment
				if ( ! is_wp_error( wp_set_object_terms( $id, null, $taxonomy, false ) ) )
					$attachments['success'][] = $id;
				else
					$attachments['failure'][] = $id;
			}
		} else {
			foreach ( $ids as $id ) {
				// get attachment term ids
				$terms = wp_get_object_terms( $id, $taxonomy, [ 'fields' => 'ids' ] );

				// got terms?
				if ( ! is_wp_error( $terms ) ) {
					// save existing term (attachment already assigned to this term)
					if ( in_array( $new_term_id, $terms, true ) )
						$attachments['duplicated'][] = $id;

					// update attachment's term
					if ( ! is_wp_error( wp_set_object_terms( $id, $new_term_id, $taxonomy, false ) ) )
						$attachments['success'][] = $id;
					else
						$attachments['failure'][] = $id;
				}
			}
		}

		if ( empty( $attachments['success'] ) )
			wp_send_json_error();
		else {
			wp_send_json_success(
				[
					'attachments'	=> $attachments,
					'terms'			=> $all_terms
				]
			);
		}
	}


/** Function get_menu_content() called by wp_ajax hooks: {'rl-get-menu-content'} **/
/** Parameters found in function get_menu_content(): {"post": ["post_id", "tab", "menu_item", "nonce"]} **/
function get_menu_content() {
		if ( ! isset( $_POST['post_id'], $_POST['tab'], $_POST['menu_item'], $_POST['nonce'] ) || ! check_ajax_referer( 'rl-gallery', 'nonce', false ) )
			wp_send_json_error();

		// check tab
		$tab = isset( $_POST['tab'] ) ? sanitize_key( $_POST['tab'] ) : '';

		if ( ! array_key_exists( $tab, $this->tabs ) )
			wp_send_json_error();

		// get post id
		$post_id = (int) $_POST['post_id'];

		if ( ! current_user_can( 'edit_post', $post_id ) )
			wp_send_json_error();

		// check menu item
		$menu_item = sanitize_key( $_POST['menu_item'] );

		// get selected menu item
		$menu_item = ! empty( $menu_item ) && in_array( $menu_item, array_keys( $this->tabs[$tab]['menu_items'] ) ) ? $menu_item : key( $this->tabs[$tab]['menu_items'] );

		$content = Responsive_Lightbox()->gallery_api->render_menu_content( $post_id, $tab, $menu_item );
		wp_send_json_success( $content );
	}


/** Function rename_term() called by wp_ajax hooks: {'rl-folders-rename-term'} **/
/** Parameters found in function rename_term(): {"post": ["term_id", "name", "nonce"]} **/
function rename_term() {
		// no data?
		if ( ! isset( $_POST['term_id'], $_POST['name'], $_POST['nonce'] ) )
			wp_send_json_error();

		// invalid nonce?
		if ( ! ctype_alnum( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'rl-folders-ajax-library-nonce' ) )
			wp_send_json_error();

		// sanitize term id
		$term_id = (int) $_POST['term_id'];

		if ( $term_id <= 0 )
			wp_send_json_error();

		// get active taxonomy
		$taxonomy = $this->get_active_taxonomy();

		// update term, name is sanitized inside wp_update_term with sanitize_term function
		$update = wp_update_term( $term_id, $taxonomy, [ 'name' => $_POST['name'] ] );

		// error?
		if ( is_wp_error( $update ) )
			wp_send_json_error();

		$term = get_term( $term_id, $taxonomy );

		// error?
		if ( is_wp_error( $term ) )
			wp_send_json_error();
		else {
			wp_send_json_success(
				[
					'name'		=> $term->name,
					'select'	=> $this->get_folders( $taxonomy, $term_id )
				]
			);
		}
	}


/** Function ajax_query_media() called by wp_ajax hooks: {'rl_remote_library_query'} **/
/** No params detected :-/ **/


/** Function ajax_save_attachment_compat() called by wp_ajax hooks: {'save-attachment-compat'} **/
/** Parameters found in function ajax_save_attachment_compat(): {"request": ["id", "attachments"]} **/
function ajax_save_attachment_compat() {
		// no attachment id?
		if ( ! isset( $_REQUEST['id'] ) )
			wp_send_json_error();

		$id = (int) $_REQUEST['id'];

		// invalid id?
		if ( $id <= 0 )
			wp_send_json_error();

		// no valid data?
		if ( empty( $_REQUEST['attachments'][$id] ) || ! is_array( $_REQUEST['attachments'][$id] ) )
			wp_send_json_error();

		// no sanitization like in wordpress core: wp_ajax_save_attachment_compat() function
		$attachment_data = $_REQUEST['attachments'][$id];

		// check nonce
		check_ajax_referer( 'update-post_' . $id, 'nonce' );

		if ( ! current_user_can( 'edit_post', $id ) )
			wp_send_json_error();

		// get post
		$post = get_post( $id, ARRAY_A );

		if ( empty( $post ) || $post['post_type'] !== 'attachment' )
			wp_send_json_error();

		// update attachment data if needed
		$post = apply_filters( 'attachment_fields_to_save', $post, $attachment_data );

		if ( isset( $post['errors'] ) )
			wp_send_json_error();

		// update attachment
		wp_update_post( $post );

		// get active taxonomy
		$taxonomy = $this->get_active_taxonomy();

		// first if needed?
		if ( isset( $attachment_data[$taxonomy] ) )
			wp_set_object_terms( $id, (int) reset( array_map( 'trim', $attachment_data[$taxonomy] ) ), $taxonomy, false );
		elseif ( isset( $_REQUEST[$taxonomy . '_term'] ) )
			wp_set_object_terms( $id, (int) $_REQUEST[$taxonomy . '_term'], $taxonomy, false );
		else
			wp_set_object_terms( $id, '', $taxonomy, false );

		// check media tags
		if ( isset( $attachment_data['rl_media_tag'] ) && is_string( $attachment_data['rl_media_tag'] ) ) {
			$media_tags = explode( ',', $attachment_data['rl_media_tag'] );

			if ( ! empty( $media_tags ) && is_array( $media_tags ) )
				$media_tags = array_filter( array_map( 'sanitize_title', $media_tags ) );

			// any media tags?
			if ( ! empty( $media_tags ) )
				wp_set_object_terms( $id, $media_tags, 'rl_media_tag', false );
			else
				wp_set_object_terms( $id, '', 'rl_media_tag', false );
		}

		// get attachment data
		$attachment = wp_prepare_attachment_for_js( $id );

		// invalid attachment?
		if ( ! $attachment )
			wp_send_json_error();

		// finally send success
		wp_send_json_success( $attachment );
	}


/** Function post_get_galleries() called by wp_ajax hooks: {'rl-post-get-galleries'} **/
/** Parameters found in function post_get_galleries(): {"post": ["post_id", "search", "nonce", "page", "category"]} **/
function post_get_galleries() {
		// check rate limiting (60 requests per minute)
		if ( ! Responsive_Lightbox()->check_rate_limit( 'rl_post_get_galleries', 60, 60 ) ) {
			wp_send_json_error( __( 'Rate limit exceeded. Please try again later.', 'responsive-lightbox' ) );
		}

		// check data
		if ( ! isset( $_POST['post_id'], $_POST['search'], $_POST['nonce'], $_POST['page'] ) || ! check_ajax_referer( 'rl-gallery-post', 'nonce', false ) )
			wp_send_json_error();

		// check page
		$page = preg_replace( '/[^a-z-.]/i', '', $_POST['page'] );

		// check page
		if ( ! in_array( $page, [ 'widgets.php', 'customize.php', 'post.php', 'post-new.php' ], true ) )
			wp_send_json_error();

		// check edit_post capability
		if ( ( $page === 'post.php' || $page === 'post-new.php' ) && ! current_user_can( 'edit_post', (int) $_POST['post_id'] ) )
			wp_send_json_error();

		// check edit_theme_options capability
		if ( ( $page === 'widgets.php' || $page === 'customize.php' ) && ! current_user_can( 'edit_theme_options' ) )
			wp_send_json_error();

		$args = array(
			'post_type'			=> 'rl_gallery',
			'post_status'		=> 'publish',
			'nopaging'			=> true,
			'posts_per_page'	=> -1,
			'orderby'			=> 'title',
			'order'				=> 'ASC',
			'suppress_filters'	=> false,
			'no_found_rows'		=> true,
			'cache_results'		=> false
		);

		// check category
		$category = isset( $_POST['category'] ) ? (int) $_POST['category'] : 0;

		// specific category?
		if ( ! empty( $category ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy'			=> 'rl_category',
					'field'				=> 'term_id',
					'operator'			=> 'IN',
					'include_children'	=> false,
					'terms'				=> $category
				)
			);
		}

		$search = wp_unslash( trim( $_POST['search'] ) );

		if ( $search !== '' )
			$args['s'] = $search;

		// get galleries
		$query = new WP_Query( $args );

		$html = '';
		$ids = [];

		// any galleries?
		if ( ! empty( $query->posts ) ) {
			foreach ( $query->posts as $gallery ) {
				// save gallery id
				$ids[] = (int) $gallery->ID;

				// get featured image
				$featured = $this->get_featured_image_src( $gallery->ID );

				if ( is_array( $featured ) && array_key_exists( 'url', $featured ) )
					$featured_image = $featured['url'];
				else
					$featured_image = '';

				// get title
				$title = $gallery->post_title !== '' ? $gallery->post_title : esc_html__( '(no title)', 'responsive-gallery' );

				$html .= '
				<li tabindex="0" role="checkbox" aria-label="' . esc_attr( $title ) . '" aria-checked="true" data-id="' . (int) $gallery->ID . '" class="attachment selection">
					<div class="attachment-preview js--select-attachment type-image ' . ( ! empty( $featured['thumbnail_orientation'] ) ? esc_attr( $featured['thumbnail_orientation'] ) : 'landscape' ) . '">
						<div class="thumbnail">
							<div class="centered" data-full-src="' . esc_url( $featured_image ) . '">
								' . $this->get_featured_image( $gallery->ID, 'thumbnail' ) . '
							</div>
							<div class="filename">
								<div>' . esc_html( $title ) . '</div>
							</div>
						</div>
					</div>
					<button type="button" class="button-link check"><span class="media-modal-icon"></span><span class="screen-reader-text">' . esc_html__( 'Deselect', 'responsive-lightbox' ) . '</span></button>
				</li>';
			}
		}

		// send galleries content
		wp_send_json_success(
			[
				'galleries'	=> $ids,
				'html'		=> $html
			]
		);
	}


/** Function get_gallery_page() called by wp_ajax hooks: {'nopriv_rl-get-gallery-page-content', 'rl-get-gallery-page-content'} **/
/** Parameters found in function get_gallery_page(): {"get": ["rl_page"], "post": ["page", "preview", "gallery_id", "gallery_no"]} **/
function get_gallery_page( $args ) {
		// check whether is it valid gallery ajax request
		if ( $this->gallery_ajax_verified() ) {
			// check rate limiting (60 requests per minute)
			if ( ! Responsive_Lightbox()->check_rate_limit( 'rl_get_gallery_page', 60, 60 ) ) {
				wp_send_json_error( __( 'Rate limit exceeded. Please try again later.', 'responsive-lightbox' ) );
			}

			// cast page number
			$_GET['rl_page'] = (int) $_POST['page'];

			// check preview
			$preview = ( $_POST['preview'] === 'true' );

			echo $this->gallery_shortcode(
				[
					'id'			=> (int) $_POST['gallery_id'],
					'gallery_no'	=> (int) $_POST['gallery_no'],
					'preview'		=> $preview
				]
			);
		}

		exit;
	}


/** Function load_old_taxonomies() called by wp_ajax hooks: {'rl-folders-load-old-taxonomies'} **/
/** Parameters found in function load_old_taxonomies(): {"post": ["taxonomies", "nonce"]} **/
function load_old_taxonomies() {
		// check capability
		if ( ! current_user_can( 'manage_options' ) )
			wp_send_json_error( [ 'message' => __( 'You do not have permission to perform this action.', 'responsive-lightbox' ) ] );

		// no data?
		if ( ! isset( $_POST['taxonomies'], $_POST['nonce'] ) )
			wp_send_json_error( [ 'message' => __( 'Missing required data.', 'responsive-lightbox' ) ] );

		// invalid taxonomies format?
		if ( ! is_array( $_POST['taxonomies'] ) )
			wp_send_json_error( [ 'message' => __( 'Invalid data format.', 'responsive-lightbox' ) ] );

		// invalid nonce?
		if ( ! wp_verify_nonce( $_POST['nonce'], 'rl-folders-ajax-taxonomies-nonce' ) )
			wp_send_json_error( [ 'message' => __( 'Security check failed. Please refresh the page and try again.', 'responsive-lightbox' ) ] );

		// validate taxonomies are strings
		$taxonomies_input = array_filter( $_POST['taxonomies'], 'is_string' );
		$taxonomies_input = array_map( 'sanitize_key', $taxonomies_input );

		// get taxonomies used by attachments (DB) and currently registered
		$db_taxonomies = $this->get_taxonomies();
		$db_taxonomies = is_array( $db_taxonomies ) ? $db_taxonomies : [];

		$registered_taxonomies = get_taxonomies(
			[
				'object_type'	=> [ 'attachment' ],
				'hierarchical'	=> true,
				'_builtin'		=> false
			],
			'names',
			'and'
		);
		$registered_taxonomies = is_array( $registered_taxonomies ) ? $registered_taxonomies : [];

		// combine DB-discovered and currently registered taxonomies
		$fields = array_values( array_unique( array_merge( $db_taxonomies, $registered_taxonomies ) ) );

		// any results?
		if ( ! empty( $fields ) ) {
			// remove main taxonomy
			if ( ( $key = array_search( 'rl_media_folder', $fields, true ) ) !== false )
				unset( $fields[$key] );

			// remove media tags
			if ( ( $key = array_search( 'rl_media_tag', $fields, true ) ) !== false )
				unset( $fields[$key] );

			// remove polylang taxonomy if present
			if ( ( $key = array_search( 'language', $fields, true ) ) !== false )
				unset( $fields[$key] );

			// sanitize and normalize
			$fields = array_map( 'sanitize_key', $fields );
			$fields = array_filter( $fields, 'is_string' );
			$fields = array_values( array_unique( $fields ) );
		}
		
		// save discovered taxonomies to options (merge with existing, preserve on scan failure)
		$options = get_option( 'responsive_lightbox_folders', [] );
		$options = is_array( $options ) ? $options : [];
		$existing = isset( $options['custom_taxonomies'] ) && is_array( $options['custom_taxonomies'] ) ? $options['custom_taxonomies'] : [];
		
		if ( ! empty( $fields ) ) {
			$options['custom_taxonomies'] = array_values( array_unique( array_merge( $existing, $fields ) ) );
			update_option( 'responsive_lightbox_folders', $options );
		}
		// Note: We don't clear custom_taxonomies when scan returns nothing to avoid
		// wiping stored taxonomies on transient DB failures or when no new taxonomies exist

		// send taxonomies with counts and message
		$count = count( $fields );
		$new_taxonomies = array_values( array_diff( $fields, $taxonomies_input ) );
		$count_new = count( $new_taxonomies );

		if ( $count > 0 ) {
			if ( $count_new > 0 ) {
				$message = sprintf(
					/* translators: 1: total taxonomies, 2: newly added taxonomies */
					__( '%1$d custom taxonomies available. %2$d new added to the list.', 'responsive-lightbox' ),
					$count,
					$count_new
				);
			} else {
				$message = sprintf(
					_n(
						'%d custom taxonomy available (registered or previously loaded). No new taxonomies were added.',
						'%d custom taxonomies available (registered or previously loaded). No new taxonomies were added.',
						$count,
						'responsive-lightbox'
					),
					$count
				);
			}
		} else {
			$message = __( 'No custom taxonomies found.', 'responsive-lightbox' );
		}
			
		wp_send_json_success( [ 
			'taxonomies'	=> array_values( $fields ),
			'count'			=> $count,
			'count_new'		=> $count_new,
			'message'		=> $message
		] );
	}


/** Function dismiss_notice() called by wp_ajax hooks: {'rl_dismiss_notice'} **/
/** Parameters found in function dismiss_notice(): {"request": ["nonce", "notice_action"]} **/
function dismiss_notice() {
		if ( ! current_user_can( 'install_plugins' ) )
			return;

		if ( isset( $_REQUEST['nonce'] ) && ctype_alnum( $_REQUEST['nonce'] ) && wp_verify_nonce( $_REQUEST['nonce'], 'rl_dismiss_notice' ) ) {
			$notice_action = isset( $_REQUEST['notice_action'] ) ? sanitize_key( $_REQUEST['notice_action'] ) : '';

			if ( empty( $notice_action ) || $notice_action === 'hide' )
				$notice_action = 'hide';

			switch ( $notice_action ) {
				// delay notice
				case 'delay':
					// set delay period to 1 week from now
					$this->options['settings'] = array_merge( $this->options['settings'], [ 'update_delay_date' => time() + 2 * WEEK_IN_SECONDS ] );
					update_option( 'responsive_lightbox_settings', $this->options['settings'] );
					break;

				// hide notice
				default:
					$this->options['settings'] = array_merge( $this->options['settings'], [ 'update_notice' => false ] );
					$this->options['settings'] = array_merge( $this->options['settings'], [ 'update_delay_date' => 0 ] );

					update_option( 'responsive_lightbox_settings', $this->options['settings'] );
			}
		}

		exit;
	}


/** Function move_term() called by wp_ajax hooks: {'rl-folders-move-term'} **/
/** Parameters found in function move_term(): {"post": ["parent_id", "term_id", "nonce"]} **/
function move_term() {
		// no data?
		if ( ! isset( $_POST['parent_id'], $_POST['term_id'], $_POST['nonce'] ) )
			wp_send_json_error();

		// invalid nonce?
		if ( ! ctype_alnum( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'rl-folders-ajax-library-nonce' ) )
			wp_send_json_error();

		// get active taxonomy
		$taxonomy = $this->get_active_taxonomy();

		if ( ! $this->is_term_drag_and_drop_enabled() )
			wp_send_json_error( [ 'message' => __( 'You do not have permission to move folders.', 'responsive-lightbox' ) ] );

		// update term
		$update = wp_update_term( (int) $_POST['term_id'], $taxonomy, [ 'parent' => (int) $_POST['parent_id'] ] );

		// error?
		if ( is_wp_error( $update ) )
			wp_send_json_error();
		else
			wp_send_json_success( $this->get_folders( $taxonomy ) );
	}


/** Function ajax_get_addons_feed() called by wp_ajax hooks: {'rl-get-addons-feed'} **/
/** Parameters found in function ajax_get_addons_feed(): {"post": ["nonce"]} **/
function ajax_get_addons_feed() {
		$rl = Responsive_Lightbox();
		$capability = apply_filters( 'rl_lightbox_settings_capability', $rl->options['capabilities']['active'] ? 'edit_lightbox_settings' : 'manage_options' );

		if ( ! current_user_can( $capability ) )
			wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to access this data.', 'responsive-lightbox' ) ] );

		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'rl_addons_feed' ) )
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid request. Please reload the page and try again.', 'responsive-lightbox' ) ] );

		$addons_html = self::get_addons_feed_html();

		if ( $addons_html === '' )
			wp_send_json_error( [ 'message' => esc_html__( 'There was an error retrieving the extensions list from the server. Please try again later.', 'responsive-lightbox' ) ] );

		wp_send_json_success( [ 'html' => $addons_html ] );
	}


/** Function post_gallery_preview() called by wp_ajax hooks: {'rl-post-gallery-preview'} **/
/** Parameters found in function post_gallery_preview(): {"post": ["post_id", "gallery_id", "nonce", "page"]} **/
function post_gallery_preview() {
		// check data
		if ( ! isset( $_POST['post_id'], $_POST['gallery_id'], $_POST['nonce'], $_POST['page'] ) || ! check_ajax_referer( 'rl-gallery-post', 'nonce', false ) )
			wp_send_json_error();

		// check page
		$page = preg_replace( '/[^a-z-.]/i', '', $_POST['page'] );

		// check page
		if ( ! in_array( $page, [ 'widgets.php', 'customize.php', 'post.php', 'post-new.php' ], true ) )
			wp_send_json_error();

		// check edit_post capability
		if ( ( $page === 'post.php' || $page === 'post-new.php' ) && ! current_user_can( 'edit_post', (int) $_POST['post_id'] ) )
			wp_send_json_error();

		// check edit_theme_options capability
		if ( ( $page === 'widgets.php' || $page === 'customize.php' ) && ! current_user_can( 'edit_theme_options' ) )
			wp_send_json_error();

		// parse gallery id
		$gallery_id = (int) $_POST['gallery_id'];

		// get gallery data
		$data = get_post_meta( $gallery_id, '_rl_images', true );

		// prepare data
		$attachments = $exclude = [];
		$html = '';

		// get images
		$images = $this->get_gallery_images(
			$gallery_id,
			[
				'exclude'	=> true,
				'limit'		=> 20
			]
		);

		// get number of images
		$images_count = (int) get_post_meta( $gallery_id, '_rl_images_count', true );

		if ( ! empty( $images ) ) {
			foreach ( $images as $image ) {
				$html .= '
				<li tabindex="0" role="checkbox" aria-label="' . esc_attr( $image['title'] ) . '" aria-checked="true" data-id="' . esc_attr( $image['id'] ) . '" class="attachment selection selected rl-status-active">
					<div class="attachment-preview js--select-attachment type-image ' . esc_attr( $image['thumbnail_orientation'] ). '">
						<div class="thumbnail">
							<div class="centered">
								<img src="' . esc_url( $image['thumbnail_url'] ) . '" draggable="false" alt="" />
							</div>
						</div>
					</div>
				</li>';
			}
		}

		// send attachments content
		wp_send_json_success(
			array(
				'attachments'	=> $html,
				'count'			=> esc_html( sprintf( _n( '%s image', '%s images', $images_count, 'responsive-lightbox' ), $images_count ) ),
				'edit_url'		=> current_user_can( 'edit_post', $gallery_id ) ? esc_url_raw( admin_url( 'post.php?post=' . $gallery_id . '&action=edit' ) ) : ''
			)
		);
	}


/** Function get_gallery_preview_content() called by wp_ajax hooks: {'rl-get-preview-content'} **/
/** Parameters found in function get_gallery_preview_content(): {"post": ["post_id", "menu_item", "nonce", "preview_type", "query", "excluded"]} **/
function get_gallery_preview_content() {
		// check rate limiting (60 requests per minute)
		if ( ! Responsive_Lightbox()->check_rate_limit( 'rl_get_gallery_preview_content', 60, 60 ) ) {
			wp_send_json_error( __( 'Rate limit exceeded. Please try again later.', 'responsive-lightbox' ) );
		}

		// initial checks
		if ( ! isset( $_POST['post_id'], $_POST['menu_item'], $_POST['nonce'], $_POST['preview_type'] ) || ! check_ajax_referer( 'rl-gallery', 'nonce', false ) )
			wp_send_json_error();

		// cast gallery ID
		$post_id = (int) $_POST['post_id'];

		// check user privileges
		if ( ! current_user_can( 'edit_post', $post_id ) || ! current_user_can( 'upload_files' ) )
			wp_send_json_error();

		// get query args
		$args = ! empty( $_POST['query'] ) ? wp_unslash( $_POST['query'] ) : [];

		// check orderby
		if ( array_key_exists( 'orderby', $args ) ) {
			$args['post_orderby'] = $args['orderby'];

			unset( $args['orderby'] );
		}

		// check order
		if ( array_key_exists( 'order', $args ) ) {
			$args['post_order'] = $args['order'];

			unset( $args['order'] );
		}

		// check preview type
		$preview_type = sanitize_key( $_POST['preview_type'] );

		// check preview type
		if ( ! in_array( $preview_type, [ 'page', 'update' ], true ) )
			$args['preview_type'] = 'page';
		else
			$args['preview_type'] = $preview_type;

		// check menu item
		$menu_item = sanitize_key( $_POST['menu_item'] );

		// Resolve menu item against available image fields to avoid stale/disabled sources.
		$image_fields = ( isset( $this->fields['images'] ) && is_array( $this->fields['images'] ) ) ? $this->fields['images'] : [];
		if ( ! empty( $menu_item ) && isset( $image_fields[$menu_item] ) && is_array( $image_fields[$menu_item] ) )
			$menu_item = $this->menu_item = $menu_item;
		elseif ( isset( $image_fields['media'] ) )
			$menu_item = $this->menu_item = 'media';
		else {
			$fallback_menu_item = key( $image_fields );
			$menu_item = $this->menu_item = is_string( $fallback_menu_item ) ? $fallback_menu_item : '';
		}

		if ( $menu_item === '' )
			wp_send_json_error();

		$preview_args = isset( $image_fields[$menu_item]['attachments']['preview'] ) && is_array( $image_fields[$menu_item]['attachments']['preview'] ) ? $image_fields[$menu_item]['attachments']['preview'] : [];
		$has_preview_pagination = ! empty( $preview_args['pagination'] );

		if ( $has_preview_pagination ) {
			if ( isset( $args['preview_page'] ) )
				$args['preview_page'] = (int) $args['preview_page'];
			else
				$args['preview_page'] = 1;
		}

		// get images
		$images = $this->get_gallery_images( $post_id, $args );

		// prepare JSON array
		$data = [];

		if ( $menu_item === 'remote_library' ) {
			// get main instance
			$rl = Responsive_Lightbox();

			$response_data = [];

			// single provider?
			if ( $args['media_provider'] !== 'all' ) {
				// get provider
				$provider = $rl->providers[$args['media_provider']];

				// add response data arguments if needed
				if ( ! empty( $provider['response_args'] ) ) {
					$response = $provider['instance']->get_response_data();

					foreach ( $provider['response_args'] as $arg ) {
						if ( array_key_exists( $arg, $response ) )
							$response_data[$provider['slug']][$arg] = base64_encode( wp_json_encode( $response[$arg] ) );
					}
				}
			} else {
				// get active providers
				$providers = $rl->remote_library->get_active_providers();

				if ( ! empty( $providers ) ) {
					foreach ( $providers as $provider ) {
						// get provider
						$provider = $rl->providers[$provider];

						// add response data arguments if needed
						if ( ! empty( $provider['response_args'] ) ) {
							$response = $provider['instance']->get_response_data();

							foreach ( $provider['response_args'] as $arg ) {
								if ( array_key_exists( $arg, $response ) )
									$response_data[$provider['slug']][$arg] = base64_encode( wp_json_encode( $response[$arg] ) );
							}
						}
					}
				}
			}

			$data['response_data'] = $response_data;
		}

		// parse excluded images
		$excluded = ! empty( $_POST['excluded'] ) && is_array( $_POST['excluded'] ) ? array_map( 'intval', $_POST['excluded'] ) : [];

		// get excluded images
		if ( ! empty( $excluded ) )
			$excluded = array_unique( array_filter( $excluded ) );

		// get media item template
		$media_item_template = $this->get_media_item_template( $preview_args );

		// build html
		$html = '';

		// any images?
		if ( ! empty( $images ) ) {
			foreach ( $images as $image ) {
				// get image content html
				$html .= $this->get_gallery_preview_image_content( $image, 'images', $menu_item, 'attachments', $media_item_template, $excluded, $image['id'] );
			}
		}

		$data['images'] = $html;

		if ( $has_preview_pagination )
			$data['pagination'] = $this->get_preview_pagination( $args['preview_page'] );

		// send JSON
		wp_send_json_success( $data );
	}


/** Function select_term() called by wp_ajax hooks: {'rl-folders-select-term'} **/
/** Parameters found in function select_term(): {"post": ["term_id"]} **/
function select_term() {
		check_ajax_referer( 'rl-folders-ajax-library-nonce', 'nonce' );

		if ( ! current_user_can( 'upload_files' ) )
			wp_send_json_error( 'No permission.' );

		$term_id = isset( $_POST['term_id'] ) ? sanitize_key( $_POST['term_id'] ) : 'all';

		update_user_option( get_current_user_id(), 'rl_folders_selected_term', $term_id );

		wp_send_json_success();
	}


/** Function ajax_upload_image() called by wp_ajax hooks: {'rl_upload_image'} **/
/** No params detected :-/ **/


