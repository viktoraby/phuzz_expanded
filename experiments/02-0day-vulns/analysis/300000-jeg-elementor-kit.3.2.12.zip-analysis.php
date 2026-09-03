<?php
/***
*
*Found actions: 29
*Found functions:28
*Extracted functions:28
*Total parameter names extracted: 21
*Overview: {'_toggle_debug_mode': {'fs_toggle_debug_mode'}, 'clone_element': {'jkit_clone_element'}, 'custom_term_option': {'jeg_get_custom_term_option'}, 'dismiss_notice_ajax_callback': {'fs_dismiss_notice_action_{$ajax_action_suffix}'}, 'find_taxonomy': {'jkit_find_taxonomy'}, 'author_option': {'jeg_get_author_option'}, 'get_lazy_menu': {'nopriv_load_menu', 'load_menu'}, 'find_ajax_tag': {'jeg_find_tag'}, 'find_posts': {'jkit_find_posts_object'}, 'update_sequence': {'jkit_update_sequence'}, 'cpt_option': {'jeg_get_cpt_option'}, 'tag_option': {'jeg_get_tag_option'}, 'find_ajax_author': {'jeg_find_author'}, 'post_option': {'jeg_get_post_option'}, 'find_ajax_post': {'jeg_find_post'}, 'detail_element': {'jkit_detail_element'}, 'find_ajax_post_tag': {'jeg_find_post_tag'}, 'close_banner_upgrade': {'jkit_notice_banner_upgrade_close'}, 'create_element': {'jkit_create_element'}, 'find_ajax_cpt': {'jeg_find_cpt'}, 'find_ajax_custom_term': {'jeg_find_custom_term'}, 'review': {'jkit_notice_banner_review'}, 'close': {'jkit_notice_banner_close'}, 'find_author': {'jkit_find_author'}, 'find_ajax_category': {'jeg_find_category'}, 'delete_element': {'jkit_delete_element'}, 'update_element': {'jkit_update_element'}, 'category_option': {'jeg_get_category_option'}}
*
***/

/** Function _toggle_debug_mode() called by wp_ajax hooks: {'fs_toggle_debug_mode'} **/
/** No params detected :-/ **/


/** Function clone_element() called by wp_ajax hooks: {'jkit_clone_element'} **/
/** Parameters found in function clone_element(): {"post": ["page"]} **/
function clone_element() {
		if ( $this->is_nonce_valid( 'dashboard' ) && current_user_can( 'edit_theme_options' ) ) {
			$data      = jeg_sanitize_array( $_POST );
			$post_type = sanitize_key( $data['type'] );

			if ( jkit_is_free_header_footer_template_limit_reached( $post_type ) ) {
				wp_send_json_error(
					array(
						'code'         => 'jkit_template_limit_reached',
						'show_pricing' => true,
						'message'      => esc_html__( 'Upgrade to Pro to create more header or footer templates.', 'jeg-elementor-kit' ),
					)
				);
			}

			$post_id = $this->duplicate_element( $data['id'] );

			$published = jkit_get_element_data( $post_type )['publish'];
			$keys      = jkit_extract_ids( $published );
			$keys      = jkit_remove_array( $post_id, $keys );
			array_unshift( $keys, $post_id );
			$this->update_post_sequence( $keys );

			$element = apply_filters( 'jkit_element_data_clone', jkit_get_element_data( $post_type ), $post_type, $_POST['page'] );
			wp_send_json_success( $element );
		}
	}


/** Function custom_term_option() called by wp_ajax hooks: {'jeg_get_custom_term_option'} **/
/** Parameters found in function custom_term_option(): {"request": ["nonce", "value", "slug"]} **/
function custom_term_option() {
		if ( isset( $_REQUEST['nonce'], $_REQUEST['value'] ) && wp_verify_nonce( sanitize_key( $_REQUEST['nonce'] ), 'jeg_find_custom_term' ) ) {
			$value = sanitize_text_field( wp_unslash( $_REQUEST['value'] ) );
			wp_send_json_success( jeg_get_custom_term_option( $value, $_REQUEST['slug'] ) );
		}
	}


/** Function dismiss_notice_ajax_callback() called by wp_ajax hooks: {'fs_dismiss_notice_action_{$ajax_action_suffix}'} **/
/** Parameters found in function dismiss_notice_ajax_callback(): {"post": ["message_id"]} **/
function dismiss_notice_ajax_callback() {
            check_admin_referer( 'fs_dismiss_notice_action' );

            if ( ! is_numeric( $_POST['message_id'] ) ) {
                $this->_sticky_storage->remove( $_POST['message_id'] );
            }

            wp_die();
        }


/** Function find_taxonomy() called by wp_ajax hooks: {'jkit_find_taxonomy'} **/
/** Parameters found in function find_taxonomy(): {"post": ["query", "slug"]} **/
function find_taxonomy() {
		if ( $this->is_nonce_valid( 'dashboard' ) && current_user_can( 'edit_theme_options' ) ) {
			$result = array();
			$query  = sanitize_text_field( wp_unslash( $_POST['query'] ) );
			$slug   = isset( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';

			if ( '' !== $query ) {
				$args = array(
					'name__like' => $query,
				);

				if ( $slug ) {
					$args['taxonomy'] = $slug;
				}

				$terms = get_terms( $args );

				foreach ( $terms as $key => $term ) {
					$label = '';

					if ( ! $slug ) {
						$taxonomy = get_taxonomy( $term->taxonomy );
						$label    = ' - ' . $taxonomy->label;
					}

					$result[] = array(
						'value' => $term->term_id,
						'text'  => $term->name . $label,
					);
				}
			}

			wp_send_json_success( $result );
		}

		wp_send_json_error();
	}


/** Function author_option() called by wp_ajax hooks: {'jeg_get_author_option'} **/
/** Parameters found in function author_option(): {"request": ["nonce", "value"]} **/
function author_option() {
		if ( isset( $_REQUEST['nonce'], $_REQUEST['value'] ) && wp_verify_nonce( sanitize_key( $_REQUEST['nonce'] ), 'jeg_find_author' ) ) {
			$value = sanitize_text_field( wp_unslash( $_REQUEST['value'] ) );
			wp_send_json_success( jeg_get_author_option( $value ) );
		}
	}


/** Function get_lazy_menu() called by wp_ajax hooks: {'nopriv_load_menu', 'load_menu'} **/
/** Parameters found in function get_lazy_menu(): {"post": ["menu", "nonce"]} **/
function get_lazy_menu() {
		if ( current_user_can( 'edit_theme_options' ) && isset( $_POST['menu'], $_POST['nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['nonce'] ), $this->nonce ) ) {
			$menu = sanitize_text_field( wp_unslash( $_POST['menu'] ) );

			$segments = apply_filters( 'jeg_custom_menu_segment', array() );
			$segments = $this->prepare_segments( $segments );

			$value  = get_post_meta( $menu, $this->get_meta_name(), true );
			$fields = apply_filters( 'jeg_custom_menu_field', array(), $value );
			$fields = $this->prepare_fields( $fields );

			wp_send_json_success(
				array(
					'segments' => $segments,
					'fields'   => $fields,
				)
			);
		}
	}


/** Function find_ajax_tag() called by wp_ajax hooks: {'jeg_find_tag'} **/
/** Parameters found in function find_ajax_tag(): {"request": ["nonce", "query"]} **/
function find_ajax_tag() {
		if ( isset( $_REQUEST['nonce'], $_REQUEST['query'] ) && wp_verify_nonce( sanitize_key( $_REQUEST['nonce'] ), 'jeg_find_tag' ) ) {
			$query = sanitize_text_field( wp_unslash( $_REQUEST['query'] ) );

			$args = array(
				'taxonomy'   => array( 'post_tag' ),
				'orderby'    => 'id',
				'order'      => 'ASC',
				'hide_empty' => true,
				'fields'     => 'all',
				'name__like' => urldecode( $query ),
			);

			$terms = get_terms( $args );

			$result = array();

			if ( count( $terms ) > 0 ) {
				foreach ( $terms as $term ) {
					$result[] = array(
						'value' => $term->term_id,
						'text'  => $term->name,
					);
				}
			}

			wp_send_json_success( $result );
		}
	}


/** Function find_posts() called by wp_ajax hooks: {'jkit_find_posts_object'} **/
/** Parameters found in function find_posts(): {"request": ["query", "slug"]} **/
function find_posts() {
		if ( $this->is_nonce_valid( 'dashboard' ) && current_user_can( 'edit_theme_options' ) ) {
			$query = sanitize_text_field( wp_unslash( $_REQUEST['query'] ) );

			add_filter(
				'posts_where',
				function ( $where ) use ( $query ) {
					global $wpdb;
					$where .= $wpdb->prepare( "AND {$wpdb->posts}.post_title LIKE %s", '%' . $wpdb->esc_like( $query ) . '%' );

					return $where;
				}
			);

			$args = array(
				'posts_per_page' => '15',
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC',
			);

			if ( isset( $_REQUEST['slug'] ) && $_REQUEST['slug'] ) {
				$args['post_type'] = array( sanitize_text_field( wp_unslash( $_REQUEST['slug'] ) ) );
			} else {
				$args['post_type'] = jkit_get_public_post_type_array();
			}

			$query = new \WP_Query( $args );

			$result = array();

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();

					$post_type = get_post_type_object( get_post_type() );
					$result[]  = array(
						'value' => get_the_ID(),
						'text'  => get_the_title() . ' - ' . $post_type->labels->singular_name,
					);
				}
			}

			wp_reset_postdata();
			wp_send_json_success( $result );
		}
		wp_send_json_error();
	}


/** Function update_sequence() called by wp_ajax hooks: {'jkit_update_sequence'} **/
/** No params detected :-/ **/


/** Function cpt_option() called by wp_ajax hooks: {'jeg_get_cpt_option'} **/
/** Parameters found in function cpt_option(): {"request": ["nonce", "value"]} **/
function cpt_option() {
		if ( isset( $_REQUEST['nonce'], $_REQUEST['value'] ) && wp_verify_nonce( sanitize_key( $_REQUEST['nonce'] ), 'jeg_find_cpt' ) ) {
			$value = sanitize_text_field( wp_unslash( $_REQUEST['value'] ) );
			wp_send_json_success( jeg_get_post_option( $value ) );
		}
	}


/** Function tag_option() called by wp_ajax hooks: {'jeg_get_tag_option'} **/
/** Parameters found in function tag_option(): {"request": ["nonce", "value"]} **/
function tag_option() {
		if ( isset( $_REQUEST['nonce'], $_REQUEST['value'] ) && wp_verify_nonce( sanitize_key( $_REQUEST['nonce'] ), 'jeg_find_tag' ) ) {
			$value = sanitize_text_field( wp_unslash( $_REQUEST['value'] ) );
			wp_send_json_success( jeg_get_tag_option( $value ) );
		}
	}


/** Function find_ajax_author() called by wp_ajax hooks: {'jeg_find_author'} **/
/** Parameters found in function find_ajax_author(): {"request": ["nonce", "query"]} **/
function find_ajax_author() {
		if ( isset( $_REQUEST['nonce'], $_REQUEST['query'] ) && wp_verify_nonce( sanitize_key( $_REQUEST['nonce'] ), 'jeg_find_author' ) ) {
			$query = sanitize_text_field( wp_unslash( $_REQUEST['query'] ) );

			$users = new \WP_User_Query(
				array(
					'search'         => "*{$query}*",
					'search_columns' => array(
						'user_login',
						'user_nicename',
						'user_email',
						'user_url',
					),
				)
			);

			$users_found = $users->get_results();

			$result = array();

			if ( count( $users_found ) > 0 ) {
				foreach ( $users_found as $user ) {
					$result[] = array(
						'value' => $user->ID,
						'text'  => $user->display_name,
					);
				}
			}

			wp_send_json_success( $result );
		}
	}


/** Function post_option() called by wp_ajax hooks: {'jeg_get_post_option'} **/
/** Parameters found in function post_option(): {"request": ["nonce", "value"]} **/
function post_option() {
		if ( isset( $_REQUEST['nonce'], $_REQUEST['value'] ) && wp_verify_nonce( sanitize_key( $_REQUEST['nonce'] ), 'jeg_find_post' ) ) {
			$value = sanitize_text_field( wp_unslash( $_REQUEST['value'] ) );
			wp_send_json_success( jeg_get_post_option( $value ) );
		}
	}


/** Function find_ajax_post() called by wp_ajax hooks: {'jeg_find_post'} **/
/** Parameters found in function find_ajax_post(): {"request": ["nonce", "query"]} **/
function find_ajax_post() {
		if ( isset( $_REQUEST['nonce'], $_REQUEST['query'] ) && wp_verify_nonce( sanitize_key( $_REQUEST['nonce'] ), 'jeg_find_post' ) ) {
			$query = sanitize_text_field( wp_unslash( $_REQUEST['query'] ) );

			add_filter(
				'posts_where',
				function ( $where ) use ( $query ) {
					global $wpdb;
					$where .= $wpdb->prepare( "AND {$wpdb->posts}.post_title LIKE %s", '%' . $wpdb->esc_like( $query ) . '%' );

					return $where;
				}
			);

			$query = new \WP_Query(
				array(
					'post_type'      => array_keys( jeg_exclude_post_type() ),
					'posts_per_page' => '15',
					'post_status'    => 'publish',
					'orderby'        => 'date',
					'order'          => 'DESC',
				)
			);

			$result = array();

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();

					$result[] = array(
						'value' => get_the_ID(),
						'text'  => get_the_title(),
					);
				}
			}

			wp_reset_postdata();
			wp_send_json_success( $result );
		}
	}


/** Function detail_element() called by wp_ajax hooks: {'jkit_detail_element'} **/
/** No params detected :-/ **/


/** Function find_ajax_post_tag() called by wp_ajax hooks: {'jeg_find_post_tag'} **/
/** Parameters found in function find_ajax_post_tag(): {"request": ["nonce", "query"]} **/
function find_ajax_post_tag() {
		if ( isset( $_REQUEST['nonce'], $_REQUEST['query'] ) && wp_verify_nonce( sanitize_key( $_REQUEST['nonce'] ), 'jeg_find_post_tag' ) ) {
			$query = sanitize_text_field( wp_unslash( $_REQUEST['query'] ) );

			$args = array(
				'taxonomy'   => array( 'post_tag' ),
				'orderby'    => 'id',
				'order'      => 'ASC',
				'hide_empty' => true,
				'fields'     => 'all',
				'name__like' => $query,
			);

			$terms = get_terms( $args );

			$result = array();

			if ( count( $terms ) > 0 ) {
				foreach ( $terms as $term ) {
					$result[] = array(
						'value' => $term->term_id,
						'text'  => $term->name,
					);
				}
			}

			wp_send_json( $result );
		}
	}


/** Function close_banner_upgrade() called by wp_ajax hooks: {'jkit_notice_banner_upgrade_close'} **/
/** No params detected :-/ **/


/** Function create_element() called by wp_ajax hooks: {'jkit_create_element'} **/
/** Parameters found in function create_element(): {"post": ["type", "data", "page"]} **/
function create_element() {
		if ( $this->is_nonce_valid( 'dashboard' ) && current_user_can( 'edit_theme_options' ) ) {
			$post_type = sanitize_key( $_POST['type'] );

			if ( jkit_is_free_header_footer_template_limit_reached( $post_type ) ) {
				wp_send_json_error(
					array(
						'code'         => 'jkit_template_limit_reached',
						'show_pricing' => true,
						'message'      => esc_html__( 'Upgrade to Pro to create more header or footer templates.', 'jeg-elementor-kit' ),
					)
				);
			}

			$published = jkit_get_element_data( $post_type )['publish'];
			$keys      = jkit_extract_ids( $published );
			$data      = jeg_sanitize_array( $_POST['data'] );
			$condition = isset( $data['condition'] ) ? $data['condition'] : '';
			$post_args = array(
				'post_title'  => $data['option']['title'],
				'post_type'   => $post_type,
				'post_status' => 'publish',
				'meta_input'  => array(
					'_elementor_edit_mode'     => 'builder',
					'_elementor_template_type' => 'page',
					'_elementor_data'          => json_encode( array() ),
					'_wp_page_template'        => 'elementor_canvas',
				),
			);
			$meta      = null;

			if ( isset( $post_type ) && 'jkit-template' === $post_type ) {
				$page                                          = sanitize_key( $_POST['page'] );
				$post_args['meta_input']['_wp_page_template']  = 'elementor_header_footer';
				$post_args['meta_input']['jkit-template-type'] = $page;
				$meta                                          = $page;
			}

			$post_id = wp_insert_post( $post_args );

			update_post_meta( $post_id, sanitize_key( Dashboard::$jkit_condition ), $condition );
			array_unshift( $keys, $post_id );
			$this->update_post_sequence( $keys );

			$element = jkit_get_element_data( $post_type, $meta );
			wp_send_json_success( $element );
		}
		wp_send_json_error();
	}


/** Function find_ajax_cpt() called by wp_ajax hooks: {'jeg_find_cpt'} **/
/** Parameters found in function find_ajax_cpt(): {"request": ["nonce", "query", "slug"]} **/
function find_ajax_cpt() {
		if ( isset( $_REQUEST['nonce'], $_REQUEST['query'] ) && wp_verify_nonce( sanitize_key( $_REQUEST['nonce'] ), 'jeg_find_cpt' ) ) {
			$query = sanitize_text_field( wp_unslash( $_REQUEST['query'] ) );

			add_filter(
				'posts_where',
				function ( $where ) use ( $query ) {
					global $wpdb;
					$where .= $wpdb->prepare( "AND {$wpdb->posts}.post_title LIKE %s", '%' . $wpdb->esc_like( $query ) . '%' );

					return $where;
				}
			);

			$query = new \WP_Query(
				array(
					'post_type'      => array( $_REQUEST['slug'] ),
					'posts_per_page' => '15',
					'post_status'    => 'publish',
					'orderby'        => 'date',
					'order'          => 'DESC',
				)
			);

			$result = array();

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();

					$result[] = array(
						'value' => get_the_ID(),
						'text'  => get_the_title(),
					);
				}
			}

			wp_reset_postdata();
			wp_send_json_success( $result );
		}
	}


/** Function find_ajax_custom_term() called by wp_ajax hooks: {'jeg_find_custom_term'} **/
/** Parameters found in function find_ajax_custom_term(): {"request": ["nonce", "query", "slug"]} **/
function find_ajax_custom_term() {
		if ( isset( $_REQUEST['nonce'], $_REQUEST['query'] ) && wp_verify_nonce( sanitize_key( $_REQUEST['nonce'] ), 'jeg_find_custom_term' ) ) {
			$query = sanitize_text_field( wp_unslash( $_REQUEST['query'] ) );

			$args = array(
				'taxonomy'   => array( $_REQUEST['slug'] ),
				'orderby'    => 'id',
				'order'      => 'ASC',
				'hide_empty' => true,
				'fields'     => 'all',
				'name__like' => urldecode( $query ),
			);

			$terms = get_terms( $args );

			$result = array();

			if ( count( $terms ) > 0 ) {
				foreach ( $terms as $term ) {
					$result[] = array(
						'value' => $term->term_id,
						'text'  => $term->name,
					);
				}
			}

			wp_send_json_success( $result );
		}
	}


/** Function review() called by wp_ajax hooks: {'jkit_notice_banner_review'} **/
/** No params detected :-/ **/


/** Function close() called by wp_ajax hooks: {'jkit_notice_banner_close'} **/
/** No params detected :-/ **/


/** Function find_author() called by wp_ajax hooks: {'jkit_find_author'} **/
/** Parameters found in function find_author(): {"post": ["value"]} **/
function find_author() {
		if ( $this->is_nonce_valid( 'dashboard' ) && current_user_can( 'edit_theme_options' ) ) {
			$values = '';

			if ( isset( $_POST['value'] ) && $_POST['value'] ) {
				$values = sanitize_text_field( wp_unslash( $_POST['value'] ) );
			}

			wp_send_json_success( $values );
		}
		wp_send_json_error();
	}


/** Function find_ajax_category() called by wp_ajax hooks: {'jeg_find_category'} **/
/** Parameters found in function find_ajax_category(): {"request": ["nonce", "query"]} **/
function find_ajax_category() {
		if ( isset( $_REQUEST['nonce'], $_REQUEST['query'] ) && wp_verify_nonce( sanitize_key( $_REQUEST['nonce'] ), 'jeg_find_category' ) ) {
			$query = sanitize_text_field( wp_unslash( $_REQUEST['query'] ) );

			$args = array(
				'taxonomy'   => array( 'category' ),
				'orderby'    => 'id',
				'order'      => 'ASC',
				'hide_empty' => true,
				'fields'     => 'all',
				'name__like' => urldecode( $query ),
				'number'     => 50,
			);

			$terms = get_terms( $args );

			$result = array();

			if ( count( $terms ) > 0 ) {
				foreach ( $terms as $term ) {
					$result[] = array(
						'value' => $term->term_id,
						'text'  => $term->name,
					);
				}
			}

			wp_send_json_success( $result );
		}
	}


/** Function delete_element() called by wp_ajax hooks: {'jkit_delete_element'} **/
/** No params detected :-/ **/


/** Function update_element() called by wp_ajax hooks: {'jkit_update_element'} **/
/** Parameters found in function update_element(): {"post": ["id"]} **/
function update_element() {
		if ( $this->is_nonce_valid( 'dashboard' ) && current_user_can( 'edit_theme_options' ) ) {
			$data      = jeg_sanitize_array( $_POST )['data'];
			$condition = isset( $data['condition'] ) ? $data['condition'] : '';
			$post_id   = sanitize_post_field( 'post_id', $_POST['id'], $_POST['id'] );
			wp_update_post(
				array(
					'ID'         => $post_id,
					'post_title' => $data['option']['title'],
				)
			);

			update_post_meta( $post_id, sanitize_key( Dashboard::$jkit_condition ), $condition );
			wp_send_json_success( $condition );
		}
		wp_send_json_error();
	}


/** Function category_option() called by wp_ajax hooks: {'jeg_get_category_option'} **/
/** Parameters found in function category_option(): {"request": ["nonce", "value"]} **/
function category_option() {
		if ( isset( $_REQUEST['nonce'], $_REQUEST['value'] ) && wp_verify_nonce( sanitize_key( $_REQUEST['nonce'] ), 'jeg_find_category' ) ) {
			$value = sanitize_text_field( wp_unslash( $_REQUEST['value'] ) );
			wp_send_json_success( jeg_get_category_option( $value ) );
		}
	}


