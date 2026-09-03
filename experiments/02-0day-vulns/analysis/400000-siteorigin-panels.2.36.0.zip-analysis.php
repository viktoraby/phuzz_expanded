<?php
/***
*
*Found actions: 15
*Found functions:15
*Extracted functions:15
*Total parameter names extracted: 19
*Overview: {'action_live_editor_preview': {'so_panels_live_editor_preview'}, 'action_builder_content_json': {'so_panels_builder_content_json'}, 'layout_block_preview': {'so_panels_layout_block_preview'}, 'installer_status_ajax': {'so_installer_status'}, 'action_widget_form': {'so_panels_widget_form'}, 'dismiss_admin_post_notice': {'so_panels_dismiss_post_notice'}, 'action_get_prebuilt_layouts': {'so_panels_layouts_query'}, 'action_directory_enable': {'so_panels_directory_enable'}, 'action_export_layout': {'so_panels_export_layout'}, 'dismiss_notice': {'so_installer_dismiss'}, 'action_import_layout': {'so_panels_import_layout'}, 'action_get_prebuilt_layout': {'so_panels_get_layout'}, 'action_style_form': {'so_panels_style_form'}, 'action_builder_content': {'so_panels_builder_content'}, 'manage_product': {'siteorigin_installer_manage'}}
*
***/

/** Function action_live_editor_preview() called by wp_ajax hooks: {'so_panels_live_editor_preview'} **/
/** Parameters found in function action_live_editor_preview(): {"request": ["_panelsnonce"]} **/
function action_live_editor_preview() {
		if ( empty( $_REQUEST['_panelsnonce'] ) || ! wp_verify_nonce( $_REQUEST['_panelsnonce'], 'live-editor-preview' ) ) {
			wp_die();
		}

		include plugin_dir_path( __FILE__ ) . '../tpl/live-editor-preview.php';

		exit();
	}


/** Function action_builder_content_json() called by wp_ajax hooks: {'so_panels_builder_content_json'} **/
/** Parameters found in function action_builder_content_json(): {"get": ["_panelsnonce"], "post": ["post_id", "panels_data"]} **/
function action_builder_content_json() {
		header( 'content-type: application/json' );
		$return = array( 'post_content' => '', 'preview' => '', 'sanitized_panels_data' => '' );

		if ( ! wp_verify_nonce( $_GET['_panelsnonce'], 'panels_action' ) ) {
			wp_die();
		}

		if ( ! empty( $_POST['post_id'] ) ) {
			// This is a post so ensure the user is able to edit it.
			if ( ! current_user_can( 'edit_post', $_POST['post_id'] ) ) {
				wp_die();
			}
			$old_panels_data = get_post_meta( $_POST['post_id'], 'panels_data', true );
		} else {
			// This isn't a post, add default data to skip post speciifc checks.
			$old_panels_data = array();
			$_POST['post_id'] = 0;
		}

		if ( empty( $_POST['panels_data'] ) ) {
			echo wp_json_encode( $return );
			wp_die();
		}

		$panels_data = json_decode( wp_unslash( $_POST['panels_data'] ), true );
		if ( json_last_error() !== JSON_ERROR_NONE ) {
			echo wp_json_encode( $return );
			wp_die();
		}

		$panels_data['widgets'] = $this->process_raw_widgets(
			$panels_data['widgets'],
			! empty( $old_panels_data['widgets'] ) ? $old_panels_data['widgets'] : false,
			false
		);
		$panels_data = SiteOrigin_Panels_Styles_Admin::single()->sanitize_all( $panels_data );
		$return['sanitized_panels_data'] = $panels_data;

		// Create a version of the builder data for post content.
		SiteOrigin_Panels_Post_Content_Filters::add_filters();
		$GLOBALS[ 'SITEORIGIN_PANELS_POST_CONTENT_RENDER' ] = true;
		$return['post_content'] = self::render_and_restore_post_globals( (int) $_POST['post_id'], false, $panels_data );
		SiteOrigin_Panels_Post_Content_Filters::remove_filters();
		unset( $GLOBALS[ 'SITEORIGIN_PANELS_POST_CONTENT_RENDER' ] );

		$return['preview'] = $this->generate_panels_preview( (int) $_POST['post_id'], $panels_data );

		echo wp_json_encode( $return );

		wp_die();
	}


/** Function layout_block_preview() called by wp_ajax hooks: {'so_panels_layout_block_preview'} **/
/** Parameters found in function layout_block_preview(): {"post": ["panelsData"], "request": ["_panelsnonce"]} **/
function layout_block_preview() {
		if ( empty( $_POST['panelsData'] ) || empty( $_REQUEST['_panelsnonce'] ) || ! wp_verify_nonce( $_REQUEST['_panelsnonce'], 'layout-block-preview' ) ) {
			wp_die();
		}

		$panels_data = json_decode( wp_unslash( $_POST['panelsData'] ), true );
		if ( json_last_error() !== JSON_ERROR_NONE ) {
			wp_die();
		}

		$builder_id = 'gbp' . uniqid();
		$panels_data['widgets'] = SiteOrigin_Panels_Admin::single()->process_raw_widgets( $panels_data['widgets'], false, true, true );
		$panels_data = SiteOrigin_Panels_Styles_Admin::single()->sanitize_all( $panels_data );
		$sowb_active = class_exists( 'SiteOrigin_Widgets_Bundle' );

		if ( $sowb_active ) {
			// We need this to get our widgets bundle to add it's styles inline for previews.
			add_filter( 'siteorigin_widgets_is_preview', '__return_true' );
		}
		$rendered_layout = self::render_and_restore_post_globals( $builder_id, true, $panels_data, $layout_data );
		ob_start();

		// Need to explicitly call `siteorigin_widget_print_styles` because Gutenberg previews don't render a full version of the front end,
		// so neither the `wp_head` nor the `wp_footer` actions are called, which usually trigger `siteorigin_widget_print_styles`.
		if ( $sowb_active ) {
			siteorigin_widget_print_styles();
		}
		?>
		<style>@import url('<?php echo esc_url( SiteOrigin_Panels::front_css_url() ); ?>');</style>
		<?php
		echo SiteOrigin_Panels_Renderer::single()->print_inline_css( true );
		$rendered_layout .= ob_get_clean();
		echo $rendered_layout;
		wp_die();
	}


/** Function installer_status_ajax() called by wp_ajax hooks: {'so_installer_status'} **/
/** Parameters found in function installer_status_ajax(): {"post": ["status"]} **/
function installer_status_ajax () {
			check_ajax_referer( 'siteorigin_installer_status', 'nonce' );
			update_option( 'siteorigin_installer', rest_sanitize_boolean( $_POST['status'] ) );
			die();
		}


/** Function action_widget_form() called by wp_ajax hooks: {'so_panels_widget_form'} **/
/** Parameters found in function action_widget_form(): {"request": ["_panelsnonce", "widget", "raw"]} **/
function action_widget_form() {
		if ( empty( $_REQUEST['_panelsnonce'] ) || ! wp_verify_nonce( $_REQUEST['_panelsnonce'], 'panels_action' ) ) {
			wp_die(
				__( 'The supplied nonce is invalid.', 'siteorigin-panels' ),
				__( 'Invalid nonce.', 'siteorigin-panels' ),
				403
			);
		}

		if ( empty( $_REQUEST['widget'] ) ) {
			wp_die(
				__( 'Please specify the type of widget form to be rendered.', 'siteorigin-panels' ),
				__( 'Missing widget type.', 'siteorigin-panels' ),
				400
			);
		}

		$request = array_map( 'stripslashes_deep', $_REQUEST );

		$widget_class = sanitize_text_field( $request['widget'] );
		$widget_class = apply_filters( 'siteorigin_panels_widget_class', $widget_class );
		$instance = ! empty( $request['instance'] ) ? json_decode( $request['instance'], true ) : array();
		$form = $this->render_form( $widget_class, $instance, $_REQUEST['raw'] == 'true' );
		$form = apply_filters( 'siteorigin_panels_ajax_widget_form', $form, $widget_class, $instance );

		echo $form;
		wp_die();
	}


/** Function dismiss_admin_post_notice() called by wp_ajax hooks: {'so_panels_dismiss_post_notice'} **/
/** No params detected :-/ **/


/** Function action_get_prebuilt_layouts() called by wp_ajax hooks: {'so_panels_layouts_query'} **/
/** Parameters found in function action_get_prebuilt_layouts(): {"request": ["_panelsnonce", "type", "search", "page"]} **/
function action_get_prebuilt_layouts() {
		if ( empty( $_REQUEST['_panelsnonce'] ) || ! wp_verify_nonce( $_REQUEST['_panelsnonce'], 'panels_action' ) ) {
			wp_die( __( 'Invalid request.', 'siteorigin-panels' ), 403 );
		}

		// Get any layouts that the current user could edit.
		header( 'content-type: application/json' );

		$type = ! empty( $_REQUEST['type'] ) ? sanitize_key( $_REQUEST['type'] ) : 'directory-siteorigin';
		$search = ! empty( $_REQUEST['search'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['search'] ) ) : '';
		$page_num = ! empty( $_REQUEST['page'] ) ? intval( $_REQUEST['page'] ) : 1;

		$return = array(
			'title' => '',
			'items' => array(),
		);

		if ( $type == 'prebuilt' ) {
			$return['title'] = __( 'Theme Defined Layouts', 'siteorigin-panels' );

			// This is for theme bundled prebuilt directories
			$layouts = apply_filters( 'siteorigin_panels_prebuilt_layouts', array() );

			foreach ( $layouts as $id => $vals ) {
				if ( ! empty( $search ) && strpos( strtolower( $vals['name'] ), $search ) === false ) {
					continue;
				}

				$return['items'][] = array(
					'title'       => esc_html( $vals['name'] ),
					'id'          => esc_html( $id ),
					'type'        => 'prebuilt',
					'description' => isset( $vals['description'] ) ? esc_html( $vals['description'] ) : '',
					'screenshot'  => ! empty( $vals['screenshot'] ) ? esc_url( $vals['screenshot'] ) : '',
				);
			}

			$return['max_num_pages'] = 1;
		} elseif ( substr( $type, 0, 10 ) == 'directory-' ) {
			$directory_id = str_replace( 'directory-', '', $type );
			// If the user isn't searching, check if we have a cached version of the results.
			if ( empty( $search ) ) {
				$cache = get_transient( 'siteorigin_panels_layouts_directory_cache_' . $directory_id .'_page_' . $page_num );
				if ( ! empty( $cache ) ) {
					$return = $cache;
				}
			}

			if ( empty( $return['items'] ) ) {
				$return['title'] = __( 'Layouts Directory', 'siteorigin-panels' );

				// This is a query of the prebuilt layout directory
				$query = array(
					'search' => $search,
					'page' => $page_num,
				);
				$directories = $this->get_directories();
				$directory = ! empty( $directories[ $directory_id ] ) ? $directories[ $directory_id ] : false;

				if ( empty( $directory ) ) {
					return false;
				}
				$url = add_query_arg( $query, $directory[ 'url' ] . 'wp-admin/admin-ajax.php?action=query_layouts' );

				if ( ! empty( $directory[ 'args' ] ) && is_array( $directory[ 'args' ] ) ) {
					$url = add_query_arg( $directory[ 'args' ], $url );
				}

				$url = apply_filters( 'siteorigin_panels_layouts_directory_url', $url );
				$response = wp_remote_get( esc_url_raw( $url ) );

				if (
					! is_wp_error( $response ) &&
					is_array( $response ) &&
					$response['response']['code'] == 200
				) {
					$results = json_decode( $response['body'], true );

					if ( ! empty( $results ) && ! empty( $results['items'] ) ) {
						foreach ( $results['items'] as $item ) {
							$item['id'] = esc_html( $item['slug'] );
							$item['type'] = esc_html( $type );

							// Always process category and niche classes for filtering.
							$item['access'] = ! empty( $item['access'] ) ? esc_html( $item['access'] ) : '';
							
							// Convert category and niche names to CSS class format.
							if ( ! empty( $item['category'] ) ) {
								$item['category'] = 'so-' . sanitize_title( $item['category'] );
							}
							
							if ( ! empty( $item['niches'] ) ) {
								$niche_names = json_decode( $item['niches'] );
								if ( is_array( $niche_names ) ) {
									$formatted_niches = array_map( function( $niche ) {
										return 'so-' . sanitize_title( $niche );
									}, $niche_names );
									$item['niches'] = ' ' . implode( ' ', $formatted_niches );
								}
							}
							
							// Set the CSS class to be the category + niches.
							$item['class'] = trim( $item['category'] . ( ! empty( $item['niches'] ) ? $item['niches'] : '' ) );

							if ( empty( $item['screenshot'] ) && ! empty( $item['preview'] ) ) {
								$preview_url = add_query_arg( 'screenshot', 'true', $item[ 'preview' ] );
								$item['screenshot'] = esc_url( 'https://s.wordpress.com/mshots/v1/' . urlencode( $preview_url ) . '?w=700' );
							}

							$return['items'][] = $item;
						}

						if ( ! empty( $results['niches'] ) ) {
							// Convert the categories and niches to the expected format for filtering.
							// The layout-viewer returns them as slug => name, but we need to format them.
							// so the filter buttons work with the CSS classes
							$return['niches'] = $this->format_filter_terms( $results['niches'] );
							$return['categories'] = $this->format_filter_terms( $results['categories'] );
						}
					}

					$return['max_num_pages'] = $results['max_num_pages'];

					// If the user isn't searching, cache the results.
					if ( empty( $search ) ) {
						set_transient( 'siteorigin_panels_layouts_directory_cache_' . $directory_id .'_page_' . $page_num, $return, 86400 );
					}
				}
			}
			$no_search_title = true;
		} elseif ( strpos( $type, 'clone_' ) !== false ) {
			$post_type = str_replace( 'clone_', '', $type );
			$post_types_editable_by_user = SiteOrigin_Panels_Admin_Layouts::single()->post_types();

			// Can the user edit posts from this post type?
			if (
				empty( $post_type ) ||
				empty( $post_types_editable_by_user ) ||
				! in_array(
					$post_type,
					$post_types_editable_by_user
				)
			) {
				return;
			}

			$post_type = get_post_type_object( $post_type );
			if ( empty( $post_type ) ) {
				return;
			}

			$return['title'] = sprintf( __( 'Clone %s', 'siteorigin-panels' ), esc_html( $post_type->labels->singular_name ) );

			global $wpdb;
			$user_can_read_private = ( $post_type->name == 'post' && current_user_can( 'read_private_posts' ) || ( $post_type->name == 'page' && current_user_can( 'read_private_pages' ) ) );
			$include_private = $user_can_read_private ? "OR posts.post_status = 'private' " : '';

			// Select only the posts with the given post type that also have panels_data
			$results = $wpdb->get_results( "
				SELECT SQL_CALC_FOUND_ROWS DISTINCT ID, post_title, meta.meta_value
				FROM {$wpdb->posts} AS posts
				JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
				WHERE
					posts.post_type = '" . esc_sql( $post_type->name ) . "'
					AND meta.meta_key = 'panels_data'
					" . ( ! empty( $search ) ? 'AND posts.post_title LIKE "%' . esc_sql( $search ) . '%"' : '' ) . "
					AND ( posts.post_status = 'publish' OR posts.post_status = 'draft' " . $include_private . ')
				ORDER BY post_date DESC
				LIMIT 16 OFFSET ' . (int) ( $page_num - 1 ) * 16 );
			$total_posts = $wpdb->get_var( 'SELECT FOUND_ROWS();' );

			foreach ( $results as $result ) {
				$thumbnail = get_the_post_thumbnail_url( (int) $result->ID, array( 400, 300 ) );
				$return['items'][] = array(
					'id'         => (int) $result->ID,
					'title'      => esc_html( $result->post_title ),
					'type'       => esc_html( $type ),
					'screenshot' => ! empty( $thumbnail ) ? esc_url( $thumbnail ) : '',
				);
			}

			$return['max_num_pages'] = ceil( $total_posts / 16 );
		} else {
			// An invalid type. Display an error message.
		}

		// Add the search part to the title
		if ( ! empty( $search ) && empty( $no_search_title ) ) {
			$return['title'] .= __( ' - Results For:', 'siteorigin-panels' ) . ' <em>' . esc_html( $search ) . '</em>';
		}

		$return = apply_filters( 'siteorigin_panels_layouts_result', $return, $type );
		
		echo wp_json_encode( $return );

		wp_die();
	}


/** Function action_directory_enable() called by wp_ajax hooks: {'so_panels_directory_enable'} **/
/** Parameters found in function action_directory_enable(): {"request": ["_panelsnonce"]} **/
function action_directory_enable() {
		if ( empty( $_REQUEST['_panelsnonce'] ) || ! wp_verify_nonce( $_REQUEST['_panelsnonce'], 'panels_action' ) ) {
			wp_die();
		}
		$user = get_current_user_id();
		update_user_meta( $user, 'so_panels_directory_enabled', true );
		wp_die();
	}


/** Function action_export_layout() called by wp_ajax hooks: {'so_panels_export_layout'} **/
/** Parameters found in function action_export_layout(): {"request": ["_panelsnonce"], "post": ["panels_export_data"]} **/
function action_export_layout() {
		if ( empty( $_REQUEST['_panelsnonce'] ) || ! wp_verify_nonce( $_REQUEST['_panelsnonce'], 'panels_action' ) ) {
			wp_die();
		}

		$export_data = wp_unslash( $_POST['panels_export_data'] );
		$decoded_export_data = $this->decode_panels_data( $export_data );

		if ( ! empty( $decoded_export_data['name'] ) ) {
			$decoded_export_data['id'] = sanitize_title_with_dashes( $decoded_export_data['name'] );
			$filename = sanitize_file_name( $decoded_export_data['id'] );
		} else {
			$filename = 'layout-' . date( 'dmY' );
		}

		header( 'content-type: application/json' );
		header( "Content-Disposition: attachment; filename=$filename.json" );

		echo wp_json_encode( $decoded_export_data );

		wp_die();
	}


/** Function dismiss_notice() called by wp_ajax hooks: {'so_installer_dismiss'} **/
/** No params detected :-/ **/


/** Function action_import_layout() called by wp_ajax hooks: {'so_panels_import_layout'} **/
/** Parameters found in function action_import_layout(): {"request": ["_panelsnonce"], "files": ["panels_import_data"]} **/
function action_import_layout() {
		if (
			empty( $_REQUEST['_panelsnonce'] ) ||
			! wp_verify_nonce( $_REQUEST['_panelsnonce'], 'panels_action' )
		) {
			wp_die();
		}

		// Ensure there wasn't an error during upload.
		if (
			empty( $_FILES['panels_import_data']['tmp_name'] ) ||
			! file_exists( $_FILES['panels_import_data']['tmp_name'] )
		) {
			wp_die();
		}

		$json = file_get_contents( $_FILES['panels_import_data']['tmp_name'] );
		$panels_data = $this->decode_panels_data( $json, $_FILES['panels_import_data']['tmp_name'] );

		header( 'content-type:application/json' );
		$panels_data = apply_filters( 'siteorigin_panels_data', $panels_data, false );

		// A filter may return something other than an array.
		if ( ! is_array( $panels_data ) ) {
			wp_send_json_error();
		}

		if ( empty( $panels_data['widgets'] ) ) {
			$panels_data['widgets'] = array();
		}

		$panels_data['widgets'] = SiteOrigin_Panels_Admin::single()->process_raw_widgets( $panels_data['widgets'], array(), true, true );

		if ( ! empty( $panels_data['widgets'] ) ) {
			$panels_data['widgets'] = $this->close_all_containers( $panels_data['widgets'] );
		}

		echo wp_json_encode( $panels_data );
		wp_die();
	}


/** Function action_get_prebuilt_layout() called by wp_ajax hooks: {'so_panels_get_layout'} **/
/** Parameters found in function action_get_prebuilt_layout(): {"request": ["_panelsnonce", "type", "lid"]} **/
function action_get_prebuilt_layout() {
		if (
			empty( $_REQUEST['_panelsnonce'] ) ||
			! wp_verify_nonce( $_REQUEST['_panelsnonce'], 'panels_action' )
		) {
			wp_die();
		}

		$type = isset( $_REQUEST['type'] ) ? sanitize_key( $_REQUEST['type'] ) : '';
		$layout_id = isset( $_REQUEST['lid'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['lid'] ) ) : '';

		if ( empty( $type ) ) {
			wp_die();
		}

		if ( empty( $layout_id ) ) {
			wp_die();
		}

		header( 'content-type: application/json' );
		$panels_data = array();
		$raw_panels_data = false;

		if ( $type == 'prebuilt' ) {
			$layouts = apply_filters( 'siteorigin_panels_prebuilt_layouts', array() );

			if (
				! is_numeric( $layout_id ) &&
				empty( $layouts[ $layout_id ] )
			) {
				wp_send_json_error( array(
					'error'   => true,
					'message' => __( 'Missing layout ID or no such layout exists', 'siteorigin-panels' ),
				) );
			}

			$layout = $layouts[ $layout_id ];

			// Fix the format of this layout
			if ( ! empty( $layout[ 'filename' ] ) ) {
				$filename = $layout[ 'filename' ];
				// Only accept filenames that end with .json
				if ( substr( $filename, -5, 5 ) === '.json' && file_exists( $filename ) ) {
					$panels_data = json_decode( file_get_contents( $filename ), true );
					$layout[ 'widgets' ] = ! empty( $panels_data[ 'widgets' ] ) ? $panels_data[ 'widgets' ] : array();
					$layout[ 'grids' ] = ! empty( $panels_data[ 'grids' ] ) ? $panels_data[ 'grids' ] : array();
					$layout[ 'grid_cells' ] = ! empty( $panels_data[ 'grid_cells' ] ) ? $panels_data[ 'grid_cells' ] : array();
				}
			}

			// A theme or plugin could use this to change the data in the layout
			$panels_data = apply_filters( 'siteorigin_panels_prebuilt_layout', $layout, $layout_id );

			// Remove all the layout specific attributes
			if ( isset( $panels_data['name'] ) ) {
				unset( $panels_data['name'] );
			}

			if ( isset( $panels_data['screenshot'] ) ) {
				unset( $panels_data['screenshot'] );
			}

			if ( isset( $panels_data['filename'] ) ) {
				unset( $panels_data['filename'] );
			}

			$raw_panels_data = true;
		} elseif ( substr( $type, 0, 10 ) == 'directory-' ) {
			$directory_id = str_replace( 'directory-', '', $type );

			$directories = $this->get_directories();
			$directory = ! empty( $directories[ $directory_id ] ) ? $directories[ $directory_id ] : false;

			if ( ! empty( $directory ) ) {
				$url = $directory[ 'url' ] . 'layout/' . urlencode( $layout_id ) . '/?action=download';

				if ( ! empty( $directory[ 'args' ] ) && is_array( $directory[ 'args' ] ) ) {
					$url = add_query_arg( $directory[ 'args' ], $url );
				}

				$response = wp_remote_get( $url );

				if ( is_wp_error( $response ) ) {
					wp_send_json_error( array(
						'error'   => true,
						'message' => 'WordPress error: ' . $response->get_error_message(),
					) );
				} elseif ( $response['response']['code'] == 200 ) {
					$panels_data = json_decode( $response['body'], true );
				} else {
					wp_send_json_error( array(
						'error'   => true,
						'message' => 'HTTP Error ' . $response['response']['code'] . ': There was a problem fetching the layout. Please try again later.',
					) );
				}
			}
			$raw_panels_data = true;
		} elseif ( current_user_can( 'edit_post', $layout_id ) ) {
			$panels_data = get_post_meta( $layout_id, 'panels_data', true );

			// Clear id and timestamp for SO widgets to prevent 'newer content version' notification in widget forms.
			foreach ( $panels_data['widgets'] as &$widget ) {
				unset( $widget['_sow_form_id'] );
				unset( $widget['_sow_form_timestamp'] );
			}
		}

		if ( $raw_panels_data ) {
			// This panels_data is flagged as raw, so it needs to be processed.
			$panels_data = apply_filters( 'siteorigin_panels_data', $panels_data, false );
			$panels_data['widgets'] = SiteOrigin_Panels_Admin::single()->process_raw_widgets( $panels_data['widgets'], array(), true, true );
		}

		if ( ! empty( $panels_data['widgets'] ) ) {
			$panels_data['widgets'] = $this->close_all_containers( $panels_data['widgets'] );
		}

		wp_send_json_success( $panels_data );
	}


/** Function action_style_form() called by wp_ajax hooks: {'so_panels_style_form'} **/
/** Parameters found in function action_style_form(): {"request": ["_panelsnonce", "type", "postId", "style"], "post": ["args"]} **/
function action_style_form() {
		if ( empty( $_REQUEST['_panelsnonce'] ) || ! wp_verify_nonce( $_REQUEST['_panelsnonce'], 'panels_action' ) ) {
			wp_die(
				__( 'The supplied nonce is invalid.', 'siteorigin-panels' ),
				__( 'Invalid nonce.', 'siteorigin-panels' ),
				403
			);
		}

		$type = isset( $_REQUEST['type'] ) ? sanitize_key( $_REQUEST['type'] ) : '';

		if ( ! in_array( $type, array( 'row', 'cell', 'widget' ) ) ) {
			wp_die(
				__( 'Please specify the type of style form to be rendered.', 'siteorigin-panels' ),
				__( 'Missing style form type.', 'siteorigin-panels' ),
				400
			);
		}

		$post_id = empty( $_REQUEST['postId'] ) ? 0 : (int) $_REQUEST['postId'];
		$args = ! empty( $_POST['args'] ) ? json_decode( stripslashes( $_POST['args'] ), true ) : array();

		$current = apply_filters(
			'siteorigin_panels_general_current_styles',
			isset( $_REQUEST['style'] ) ? wp_unslash( $_REQUEST['style'] ) : array(),
			$post_id,
			$type,
			$args
		);

		$current = apply_filters(
			'siteorigin_panels_general_current_styles_' . $type,
			$current,
			$post_id,
			$args
		);

		switch ( $type ) {
			case 'row':
				$this->render_styles_fields( 'row', '<h3>' . __( 'Row Styles', 'siteorigin-panels' ) . '</h3>', '', $current, $post_id, $args );
				break;

			case 'cell':
				$cell_number = isset( $args['index'] ) ? ' ' . ( (int) $args['index'] + 1 ) : '';
				$this->render_styles_fields( 'cell', '<h3>' . sprintf( __( 'Column%s Styles', 'siteorigin-panels' ), $cell_number ) . '</h3>', '', $current, $post_id, $args );
				break;

			case 'widget':
				$this->render_styles_fields( 'widget', '<h3>' . __( 'Widget Styles', 'siteorigin-panels' ) . '</h3>', '', $current, $post_id, $args );
				break;
		}

		wp_die();
	}


/** Function action_builder_content() called by wp_ajax hooks: {'so_panels_builder_content'} **/
/** Parameters found in function action_builder_content(): {"get": ["_panelsnonce"], "post": ["post_id", "panels_data"]} **/
function action_builder_content() {
		header( 'content-type: text/html' );

		if ( ! wp_verify_nonce( $_GET['_panelsnonce'], 'panels_action' ) ) {
			wp_die();
		}

		if ( ! current_user_can( 'edit_post', $_POST['post_id'] ) ) {
			wp_die();
		}

		if ( empty( $_POST['post_id'] ) || empty( $_POST['panels_data'] ) ) {
			wp_die();
		}

		$old_panels_data = get_post_meta( $_POST['post_id'], 'panels_data', true );
		$panels_data = json_decode( wp_unslash( $_POST['panels_data'] ), true );
		if ( json_last_error() !== JSON_ERROR_NONE ) {
			wp_die();
		}

		$panels_data['widgets'] = $this->process_raw_widgets(
			$panels_data['widgets'],
			! empty( $old_panels_data['widgets'] ) ? $old_panels_data['widgets'] : false,
			false
		);
		$panels_data = SiteOrigin_Panels_Styles_Admin::single()->sanitize_all( $panels_data );

		// Create a version of the builder data for post content.
		SiteOrigin_Panels_Post_Content_Filters::add_filters();
		$GLOBALS[ 'SITEORIGIN_PANELS_POST_CONTENT_RENDER' ] = true;
		echo self::render_and_restore_post_globals( (int) $_POST['post_id'], false, $panels_data );
		SiteOrigin_Panels_Post_Content_Filters::remove_filters();
		unset( $GLOBALS[ 'SITEORIGIN_PANELS_POST_CONTENT_RENDER' ] );

		wp_die();
	}


/** Function manage_product() called by wp_ajax hooks: {'siteorigin_installer_manage'} **/
/** Parameters found in function manage_product(): {"post": ["slug", "task", "type", "version"]} **/
function manage_product() {
			check_ajax_referer( 'siteorigin-installer-manage' );

			if (
				empty( $_POST['slug'] ) ||
				empty( $_POST['task'] ) ||
				empty( $_POST['type'] )
			) {
				die();
			}

			$task = sanitize_key( $_POST['task'] );
			$type = sanitize_key( $_POST['type'] );

			$capability = '';
			if ( $type === 'plugins' ) {
				if ( $task === 'install' ) {
					$capability = 'install_plugins';
				} elseif ( $task === 'update' ) {
					$capability = 'update_plugins';
				} elseif ( $task === 'activate' ) {
					$capability = 'activate_plugins';
				}
			} elseif ( $type === 'themes' ) {
				if ( $task === 'install' ) {
					$capability = 'install_themes';
				} elseif ( $task === 'update' ) {
					$capability = 'update_themes';
				} elseif ( $task === 'activate' ) {
					$capability = 'switch_themes';
				}
			}

			if ( empty( $capability ) || ! current_user_can( $capability ) ) {
				die();
			}

			// SO Premium won't have a version.
			if (
				empty( $_POST['version'] ) &&
				$_POST['slug'] != 'siteorigin-premium'
			) {
				die();
			}

			$slug = sanitize_file_name( $_POST['slug'] );

			$product_url = 'https://wordpress.org/' . urlencode( $type ) . '/download/' . urlencode( $slug ) . '.' . urlencode( $_POST['version'] ) . '.zip';
			// check_ajax_referer( 'so_installer_manage' );
			if ( ! class_exists( 'WP_Upgrader' ) ) {
				require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			}
			$upgrader = new WP_Upgrader();
			if ( $type === 'plugins' ) {
				if ( $task === 'install' || $task === 'update' ) {
					$upgrader->run( array(
						'package' => esc_url( $product_url ),
						'destination' => WP_PLUGIN_DIR,
						'clear_destination' => true,
						'abort_if_destination_exists' => false,
						'hook_extra' => array(
							'type' => 'plugin',
							'action' => 'install',
						),
					) );

					$clear = true;
				} elseif (
					$task === 'activate' &&
					! is_wp_error( validate_plugin( $slug . '/' . $slug . '.php' ) )
				) {
					activate_plugin( $slug . '/' . $slug . '.php' );
					$clear = true;
				}
			} elseif ( $type === 'themes' ) {
				if ( $task === 'install' || $task === 'update' ) {
					$upgrader->run( array(
						'package' => esc_url( $product_url ),
						'destination' => get_theme_root(),
						'clear_destination' => true,
						'clear_working' => true,
						'abort_if_destination_exists' => false,
					) );
					$clear = true;
				} elseif ( $task === 'activate' ) {
					switch_theme( $slug );
					$clear = true;
				}
			}

			if ( ! empty( $clear ) ) {
				delete_transient( 'siteorigin_installer_product_data' );
			}
			die();
		}


