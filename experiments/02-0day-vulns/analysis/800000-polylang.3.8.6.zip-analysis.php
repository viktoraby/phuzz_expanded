<?php
/***
*
*Found actions: 9
*Found functions:9
*Extracted functions:9
*Total parameter names extracted: 9
*Overview: {'term_lang_choice': {'term_lang_choice'}, 'ajax_terms_not_translated': {'pll_terms_not_translated'}, 'ajax_update_post_rows': {'pll_update_post_rows'}, 'inline_edit_post': {'inline-save'}, 'deactivate_license': {'pll_deactivate_license'}, 'ajax_update_term_rows': {'pll_update_term_rows'}, 'ajax_posts_not_translated': {'pll_posts_not_translated'}, 'save_options': {'pll_save_options'}, 'post_lang_choice': {'post_lang_choice'}}
*
***/

/** Function term_lang_choice() called by wp_ajax hooks: {'term_lang_choice'} **/
/** Parameters found in function term_lang_choice(): {"post": ["taxonomy", "post_type", "lang", "term_id"]} **/
function term_lang_choice() {
		check_ajax_referer( 'pll_language', '_pll_nonce' );

		if ( ! isset( $_POST['taxonomy'], $_POST['post_type'], $_POST['lang'] ) ) {
			wp_die( 0 );
		}

		$lang      = $this->model->get_language( sanitize_key( $_POST['lang'] ) );
		$taxonomy  = sanitize_key( $_POST['taxonomy'] );
		$post_type = sanitize_key( $_POST['post_type'] );

		if ( empty( $lang ) || ! post_type_exists( $post_type ) || ! taxonomy_exists( $taxonomy ) ) {
			wp_die( 0 );
		}

		if ( ! empty( $_POST['term_id'] ) ) {
			$term = get_term( (int) $_POST['term_id'], $taxonomy ); // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
			$term = $term instanceof WP_Term ? $term : null;
		}

		ob_start();
		include __DIR__ . '/view-translations-term.php';
		$x = new WP_Ajax_Response( array( 'what' => 'translations', 'data' => ob_get_contents() ) );
		ob_end_clean();

		// Parent dropdown list ( only for hierarchical taxonomies )
		// $args copied from edit_tags.php except echo
		if ( is_taxonomy_hierarchical( $taxonomy ) ) {
			$args = array(
				'hide_empty'       => 0,
				'hide_if_empty'    => false,
				'taxonomy'         => $taxonomy,
				'name'             => 'parent',
				'orderby'          => 'name',
				'hierarchical'     => true,
				'show_option_none' => __( 'None', 'polylang' ),
				'echo'             => 0,
			);
			$x->Add( array( 'what' => 'parent', 'data' => wp_dropdown_categories( $args ) ) );
		}

		// Tag cloud
		// Tests copied from edit_tags.php
		else {
			$tax = get_taxonomy( $taxonomy );
			if ( ! empty( $tax ) && ! is_null( $tax->labels->popular_items ) ) {
				$args = array( 'taxonomy' => $taxonomy, 'echo' => false );
				if ( current_user_can( $tax->cap->edit_terms ) ) {
					$args = array_merge( $args, array( 'link' => 'edit' ) );
				}

				$tag_cloud = wp_tag_cloud( $args );

				if ( ! empty( $tag_cloud ) ) {
					/** @phpstan-var non-falsy-string $tag_cloud */
					$html = sprintf( '<div class="tagcloud"><h2>%1$s</h2>%2$s</div>', esc_html( $tax->labels->popular_items ), $tag_cloud );
					$x->Add( array( 'what' => 'tag_cloud', 'data' => $html ) );
				}
			}
		}

		// Flag
		$x->Add( array( 'what' => 'flag', 'data' => empty( $lang->flag ) ? esc_html( $lang->slug ) : $lang->flag ) );

		$x->send();
	}


/** Function ajax_terms_not_translated() called by wp_ajax hooks: {'pll_terms_not_translated'} **/
/** Parameters found in function ajax_terms_not_translated(): {"get": ["term", "post_type", "taxonomy", "term_language", "translation_language", "term_id"]} **/
function ajax_terms_not_translated() {
		check_ajax_referer( 'pll_language', '_pll_nonce' );

		if ( ! isset( $_GET['term'], $_GET['post_type'], $_GET['taxonomy'], $_GET['term_language'], $_GET['translation_language'] ) ) {
			wp_die( 0 );
		}

		/** @var string */
		$s = wp_unslash( $_GET['term'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$post_type = sanitize_key( $_GET['post_type'] );
		$taxonomy  = sanitize_key( $_GET['taxonomy'] );

		if ( ! post_type_exists( $post_type ) || ! taxonomy_exists( $taxonomy ) ) {
			wp_die( 0 );
		}

		$term_language = $this->model->get_language( sanitize_key( $_GET['term_language'] ) );
		$translation_language = $this->model->get_language( sanitize_key( $_GET['translation_language'] ) );

		$terms  = array();
		$return = array();

		// Add current translation in list.
		// Not in add term as term_id is not set.
		if ( isset( $_GET['term_id'] ) && 'undefined' !== $_GET['term_id'] && $term_id = $this->model->term->get_translation( (int) $_GET['term_id'], $translation_language ) ) {
			$terms = array( get_term( $term_id, $taxonomy ) );
		}

		// It is more efficient to use one common query for all languages as soon as there are more than 2.
		$all_terms = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => false, 'lang' => '', 'name__like' => $s ) );
		if ( is_array( $all_terms ) ) {
			foreach ( $all_terms as $term ) {
				$lang = $this->model->term->get_language( $term->term_id );

				if ( $lang && $lang->slug == $translation_language->slug && ! $this->model->term->get_translation( $term->term_id, $term_language ) ) {
					$terms[] = $term;
				}
			}
		}

		// Format the ajax response.
		foreach ( $terms as $term ) {
			if ( ! $term instanceof WP_Term ) {
				continue;
			}

			$parents_list = get_term_parents_list(
				$term->term_id,
				$term->taxonomy,
				array(
					'separator' => ' > ',
					'link'      => false,
				)
			);

			if ( ! is_string( $parents_list ) ) {
				continue;
			}

			$return[] = array(
				'id'    => $term->term_id,
				'value' => rtrim( $parents_list, ' >' ), // Trim the separator added at the end by WP.
				'link'  => $this->links->get_edit_term_link_html( $term, $post_type ),
			);
		}

		wp_die( wp_json_encode( $return ) );
	}


/** Function ajax_update_post_rows() called by wp_ajax hooks: {'pll_update_post_rows'} **/
/** Parameters found in function ajax_update_post_rows(): {"post": ["post_type", "post_id", "screen", "translations"]} **/
function ajax_update_post_rows(): void {
		check_ajax_referer( 'inlineeditnonce', '_pll_nonce' );

		if ( ! isset( $_POST['post_type'], $_POST['post_id'], $_POST['screen'] ) ) {
			wp_die( 0 );
		}

		if ( ! is_numeric( $_POST['post_id'] ) ) {
			wp_die( 0 );
		}

		$post_type_object = get_post_type_object( sanitize_key( $_POST['post_type'] ) );

		if ( empty( $post_type_object ) || ! $this->model->is_translated_post_type( $post_type_object->name ) ) {
			wp_die( 0 );
		}

		$wp_list_table = _get_list_table( 'WP_Posts_List_Table', array( 'screen' => sanitize_key( $_POST['screen'] ) ) );

		if ( empty( $wp_list_table ) ) {
			wp_die( 0 );
		}

		$response = new WP_Ajax_Response();

		// Collect old translations.
		if ( ! empty( $_POST['translations'] ) && is_string( $_POST['translations'] ) ) {
			$translations = explode( ',', $_POST['translations'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			$translations = array_filter( $translations, 'is_numeric' );
			$translations = array_map( 'intval', $translations );
			$translations = array_filter( $translations );
		} else {
			$translations = array();
		}

		$translations = array_merge( $translations, $this->model->post->get_translations( (int) $_POST['post_id'] ) );
		$translations = array_unique( $translations );

		if ( empty( $translations ) ) { // May occur if the modified post has no language.
			$response->send();
		}

		/** @var WP_Post[] */
		$posts = ( new WP_Query() )->query(
			array(
				// Do not add `post_status`, as this allows `WP_Query` to handle user capabilities to read private posts.
				'post__in'       => $translations,
				'post_type'      => $post_type_object->name,
				'posts_per_page' => count( $translations ),
				'no_found_rows'  => true,
				'orderby'        => 'ID',
				'lang'           => '',
			)
		);

		foreach ( $posts as $post ) {
			if ( $post_type_object->hierarchical ) {
				$level = count( get_ancestors( $post->ID, $post->post_type, 'post_type' ) );
			} else {
				$level = 0;
			}

			ob_start();
			$wp_list_table->single_row( $post, $level );
			$data = (string) ob_get_clean();

			$response->add(
				array(
					'what'         => 'row',
					'data'         => $data,
					'supplemental' => array( 'post_id' => $post->ID ),
				)
			);
		}

		$response->send();
	}


/** Function inline_edit_post() called by wp_ajax hooks: {'inline-save'} **/
/** Parameters found in function inline_edit_post(): {"post": ["post_ID", "inline_lang_choice"], "request": ["_inline_edit"]} **/
function inline_edit_post() {
		if ( ! isset( $_POST['post_ID'], $_POST['inline_lang_choice'], $_REQUEST['_inline_edit'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_REQUEST['_inline_edit'], 'inlineeditnonce' ) ) {
			return;
		}

		$language = $this->model->get_language( sanitize_key( $_POST['inline_lang_choice'] ) );

		if ( empty( $language ) ) {
			return;
		}

		$user = Capabilities::get_user();
		$user->can_translate_or_die( $language );

		$post_id = (int) $_POST['post_ID'];

		if ( ! $post_id || ! $user->has_cap( 'edit_post', $post_id ) ) {
			return;
		}

		$this->model->post->set_language( $post_id, $language );
	}


/** Function deactivate_license() called by wp_ajax hooks: {'pll_deactivate_license'} **/
/** No params detected :-/ **/


/** Function ajax_update_term_rows() called by wp_ajax hooks: {'pll_update_term_rows'} **/
/** Parameters found in function ajax_update_term_rows(): {"post": ["taxonomy", "term_id", "screen", "translations"]} **/
function ajax_update_term_rows(): void {
		check_ajax_referer( 'pll_language', '_pll_nonce' );

		if ( ! isset( $_POST['taxonomy'], $_POST['term_id'], $_POST['screen'] ) ) {
			wp_die( 0 );
		}

		if ( ! is_numeric( $_POST['term_id'] ) ) {
			wp_die( 0 );
		}

		$taxonomy_object = get_taxonomy( sanitize_key( $_POST['taxonomy'] ) );

		if ( empty( $taxonomy_object ) || ! $this->model->is_translated_taxonomy( $taxonomy_object->name ) ) {
			wp_die( 0 );
		}

		$wp_list_table = _get_list_table( 'WP_Terms_List_Table', array( 'screen' => sanitize_key( $_POST['screen'] ) ) );

		if ( empty( $wp_list_table ) ) {
			wp_die( 0 );
		}

		$response = new WP_Ajax_Response();

		// Collect old translations.
		if ( ! empty( $_POST['translations'] ) && is_string( $_POST['translations'] ) ) {
			$translations = explode( ',', $_POST['translations'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			$translations = array_filter( $translations, 'is_numeric' );
			$translations = array_map( 'intval', $translations );
			$translations = array_filter( $translations );
		} else {
			$translations = array();
		}

		$translations = array_merge( $translations, $this->model->term->get_translations( (int) $_POST['term_id'] ) );
		$translations = array_unique( $translations );

		if ( empty( $translations ) ) { // May occur if the modfied term has no language.
			$response->send();
		}

		/** @var WP_Term[] */
		$terms = ( new WP_Term_Query() )->query(
			array(
				'include'    => $translations,
				'taxonomy'   => $taxonomy_object->name,
				'hide_empty' => false,
				'orderby'   => 'term_id',
				'lang'       => '',
			)
		);

		foreach ( $terms as $term ) {
			if ( $taxonomy_object->hierarchical ) {
				$level = count( get_ancestors( $term->term_id, $taxonomy_object->name, 'taxonomy' ) );
			} else {
				$level = 0;
			}

			ob_start();
			$wp_list_table->single_row( $term, $level );
			$data = (string) ob_get_clean();

			$response->add(
				array(
					'what'         => 'row',
					'data'         => $data,
					'supplemental' => array( 'term_id' => $term->term_id ),
				)
			);
		}

		$response->send();
	}


/** Function ajax_posts_not_translated() called by wp_ajax hooks: {'pll_posts_not_translated'} **/
/** Parameters found in function ajax_posts_not_translated(): {"get": ["post_type", "post_language", "translation_language", "term", "pll_post_id"]} **/
function ajax_posts_not_translated() {
		check_ajax_referer( 'pll_language', '_pll_nonce' );

		if ( ! isset( $_GET['post_type'], $_GET['post_language'], $_GET['translation_language'], $_GET['term'], $_GET['pll_post_id'] ) ) {
			wp_die( 0 );
		}

		$post_type = sanitize_key( $_GET['post_type'] );

		if ( ! post_type_exists( $post_type ) ) {
			wp_die( 0 );
		}

		$term = wp_unslash( $_GET['term'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput

		$post_language = $this->model->get_language( sanitize_key( $_GET['post_language'] ) );
		$translation_language = $this->model->get_language( sanitize_key( $_GET['translation_language'] ) );

		$return = array();

		$untranslated_posts = $this->model->post->get_untranslated( $post_type, $post_language, $translation_language, $term );

		// format output
		foreach ( $untranslated_posts as $post ) {
			$return[] = array(
				'id'    => $post->ID,
				'value' => $post->post_title,
				'link'  => $this->links->get_edit_post_link_html( $post ),
			);
		}

		// Add current translation in list
		if ( $post_id = $this->model->post->get_translation( (int) $_GET['pll_post_id'], $translation_language ) ) {
			$post = get_post( $post_id );

			if ( ! empty( $post ) ) {
				array_unshift(
					$return,
					array(
						'id'    => $post_id,
						'value' => $post->post_title,
						'link'  => $this->links->get_edit_post_link_html( $post ),
					)
				);
			}
		}

		wp_die( wp_json_encode( $return ) );
	}


/** Function save_options() called by wp_ajax hooks: {'pll_save_options'} **/
/** Parameters found in function save_options(): {"post": ["module", "licenses"]} **/
function save_options() {
		check_ajax_referer( 'pll_options', '_pll_nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( -1 );
		}

		if ( isset( $_POST['module'] ) && $this->module === $_POST['module'] && ! empty( $_POST['licenses'] ) ) {
			$x = new WP_Ajax_Response();
			foreach ( $this->items as $item ) {
				if ( ! empty( $_POST['licenses'][ $item->id ] ) ) {
					$updated_item = $item->activate_license( sanitize_key( $_POST['licenses'][ $item->id ] ) );
					$x->Add( array( 'what' => 'license-update', 'data' => $item->id, 'supplemental' => array( 'html' => $this->get_row( $updated_item ) ) ) );
				}
			}

			// Updated message
			pll_add_notice( new WP_Error( 'settings_updated', __( 'Settings saved.', 'polylang' ), 'success' ) );
			ob_start();
			settings_errors( 'polylang' );
			$x->Add( array( 'what' => 'success', 'data' => ob_get_clean() ) );
			$x->send();
		}
	}


/** Function post_lang_choice() called by wp_ajax hooks: {'post_lang_choice'} **/
/** Parameters found in function post_lang_choice(): {"post": ["post_id", "lang", "post_type", "taxonomies"]} **/
function post_lang_choice() {
		check_ajax_referer( 'pll_language', '_pll_nonce' );

		if ( ! isset( $_POST['post_id'], $_POST['lang'], $_POST['post_type'] ) ) {
			wp_die( 'The request is missing the parameter "post_type", "lang" and/or "post_id".' );
		}

		global $post_ID; // Obliged to use the global variable for wp_popular_terms_checklist().
		$post_ID = (int) $_POST['post_id'];
		$post    = get_post( $post_ID );

		if ( ! $post instanceof WP_Post ) {
			wp_die( esc_html( "Invalid post ID {$post_ID}." ) );
		}

		$lang_slug = sanitize_key( $_POST['lang'] );
		$lang      = $this->model->get_language( $lang_slug );

		if ( empty( $lang ) ) {
			wp_die( esc_html( "{$lang_slug} is not a valid language code." ) );
		}

		Capabilities::get_user()->can_translate_or_die( $lang );

		$post_type_object = get_post_type_object( $post->post_type );

		if ( empty( $post_type_object ) ) {
			wp_die( esc_html( "{$post->post_type} is not a valid post type." ) );
		}

		if ( ! current_user_can( $post_type_object->cap->edit_post, $post->ID ) ) {
			wp_die( 'You are not allowed to edit this post.' );
		}

		$this->model->post->set_language( $post->ID, $lang );

		ob_start();
		if ( 'attachment' === $post->post_type ) {
			include __DIR__ . '/view-translations-media.php';
		} else {
			include __DIR__ . '/view-translations-post.php';
		}
		$x = new WP_Ajax_Response( array( 'what' => 'translations', 'data' => ob_get_contents() ) );
		ob_end_clean();

		// Categories
		if ( isset( $_POST['taxonomies'] ) ) { // Not set for pages
			$supplemental = array();

			foreach ( array_map( 'sanitize_key', $_POST['taxonomies'] ) as $taxname ) {
				$taxonomy = get_taxonomy( $taxname );

				if ( ! empty( $taxonomy ) ) {
					ob_start();
					$popular_ids = wp_popular_terms_checklist( $taxonomy->name );
					$supplemental['populars'] = ob_get_contents();
					ob_end_clean();

					ob_start();
					// Use $post->ID to remember checked terms in case we come back to the original language
					wp_terms_checklist( $post->ID, array( 'taxonomy' => $taxonomy->name, 'popular_cats' => $popular_ids ) );
					$supplemental['all'] = ob_get_contents();
					ob_end_clean();

					$supplemental['dropdown'] = wp_dropdown_categories(
						array(
							'taxonomy'         => $taxonomy->name,
							'hide_empty'       => 0,
							'name'             => 'new' . $taxonomy->name . '_parent',
							'orderby'          => 'name',
							'hierarchical'     => 1,
							'show_option_none' => '&mdash; ' . $taxonomy->labels->parent_item . ' &mdash;',
							'echo'             => 0,
						)
					);

					$x->Add( array( 'what' => 'taxonomy', 'data' => $taxonomy->name, 'supplemental' => $supplemental ) );
				}
			}
		}

		// Parent dropdown list ( only for hierarchical post types )
		if ( in_array( $post->post_type, get_post_types( array( 'hierarchical' => true ) ) ) ) {
			// Args and filter from 'page_attributes_meta_box' in wp-admin/includes/meta-boxes.php of WP 4.2.1
			$dropdown_args = array(
				'post_type'        => $post->post_type,
				'exclude_tree'     => $post->ID,
				'selected'         => $post->post_parent,
				'name'             => 'parent_id',
				'show_option_none' => __( '(no parent)', 'polylang' ),
				'sort_column'      => 'menu_order, post_title',
				'echo'             => 0,
			);

			/** This filter is documented in wp-admin/includes/meta-boxes.php */
			$dropdown_args = (array) apply_filters( 'page_attributes_dropdown_pages_args', $dropdown_args, $post ); // Since WP 3.3.
			$dropdown_args['echo'] = 0; // Make sure to not print it.

			/** @var string $data */
			$data = wp_dropdown_pages( $dropdown_args ); // phpcs:ignore WordPress.Security.EscapeOutput

			$x->Add( array( 'what' => 'pages', 'data' => $data ) );
		}

		// Flag
		$x->Add( array( 'what' => 'flag', 'data' => empty( $lang->flag ) ? esc_html( $lang->slug ) : $lang->flag ) );

		// Sample permalink
		$x->Add( array( 'what' => 'permalink', 'data' => get_sample_permalink_html( $post->ID ) ) );

		$x->send();
	}


