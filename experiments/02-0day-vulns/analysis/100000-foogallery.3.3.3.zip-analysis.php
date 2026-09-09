<?php
/***
*
*Found actions: 28
*Found functions:28
*Extracted functions:28
*Total parameter names extracted: 12
*Overview: {'ajax_import_galleries': {'foogallery_gallery_import'}, 'ajax_load_datasource_content': {'foogallery_load_datasource_content'}, 'ajax_generate_export': {'foogallery_gallery_export'}, 'ajax_refresh_galleries': {'foogallery_elementor_refresh_galleries'}, 'admin_notice_dismiss': {'foogallery_autoptimize_dismiss'}, 'ajax_add_taxonomy': {'foogallery_attachment_modal_taxonomy_add'}, 'ajax_thumb_generation_test': {'foogallery_thumb_generation_test'}, 'ajax_remove_override': {'foogallery_remove_alternate_img'}, 'ajax_gallery_preview': {'foogallery_preview'}, 'ajax_clear_gallery_thumb_cache': {'foogallery_clear_gallery_thumb_cache'}, 'ajax_save_gallery_details': {'foogallery_save_gallery_details'}, 'ajax_clear_all_caches': {'foogallery_clear_html_cache'}, 'ajax_uninstall': {'foogallery_uninstall'}, 'ajax_clear_attachment_thumb_cache': {'foogallery_clear_attachment_thumb_cache'}, 'ajax_create_gallery_page': {'foogallery_create_gallery_page'}, 'ajax_clear_css_optimizations': {'foogallery_clear_css_optimizations'}, 'dismiss_notice_ajax_callback': {'fs_dismiss_notice_action_{$ajax_action_suffix}'}, 'ajax_apply_retina_defaults': {'foogallery_apply_retina_defaults'}, '_toggle_debug_mode': {'fs_toggle_debug_mode'}, 'admin_fooconvert_notice_dismiss': {'foogallery_admin_fooconvert_notice_dismiss'}, 'ajax_galleries_html': {'foogallery_load_galleries'}, 'ajax_clear_thumbnail_cache': {'foogallery_clear_thumbnail_cache'}, 'create_demo_galleries': {'foogallery_admin_import_demos'}, 'admin_rating_notice_dismiss': {'foogallery_admin_rating_notice_dismiss'}, 'ajax_save_modal': {'foogallery_attachment_modal_save'}, 'ajax_get_gallery_details': {'foogallery_get_gallery_details'}, 'ajax_get_gallery_info': {'foogallery_tinymce_load_info'}, 'ajax_open_modal': {'foogallery_attachment_modal_open'}}
*
***/

/** Function ajax_import_galleries() called by wp_ajax hooks: {'foogallery_gallery_import'} **/
/** Parameters found in function ajax_import_galleries(): {"post": ["mode"]} **/
function ajax_import_galleries() {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error(
					array( 'message' => __( 'You do not have permission to import galleries.', 'foogallery' ) ),
					403
				);
			}

			check_ajax_referer( 'foogallery_gallery_import' );

			$mode = 'start';
			if ( isset( $_POST['mode'] ) ) {
				$mode = sanitize_key( wp_unslash( $_POST['mode'] ) );
			}

			if ( 'status' === $mode ) {
				$this->ajax_import_status();
			} elseif ( 'delete' === $mode ) {
				$this->ajax_import_delete();
			} elseif ( 'step' === $mode ) {
				$this->ajax_import_step();
			} else {
				$this->ajax_import_start();
			}
		}


/** Function ajax_load_datasource_content() called by wp_ajax hooks: {'foogallery_load_datasource_content'} **/
/** No params detected :-/ **/


/** Function ajax_generate_export() called by wp_ajax hooks: {'foogallery_gallery_export'} **/
/** Parameters found in function ajax_generate_export(): {"post": ["galleries"]} **/
function ajax_generate_export() {
			if ( check_admin_referer( 'foogallery_gallery_export' ) ) {
				if ( ! current_user_can( 'manage_options' ) ) {
					wp_send_json_error( array(
						'message' => __( 'You do not have permission to export galleries.', 'foogallery' ),
					), 403 );
				}

				if ( isset( $_POST['galleries'] ) ) {
					$galleries = array_map( 'sanitize_text_field', wp_unslash( $_POST['galleries'] ) );
					echo foogallery_generate_export_json( $galleries ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON output
				}
			}
			wp_die( '', '', array( 'response' => null ) );
		}


/** Function ajax_refresh_galleries() called by wp_ajax hooks: {'foogallery_elementor_refresh_galleries'} **/
/** No params detected :-/ **/


/** Function admin_notice_dismiss() called by wp_ajax hooks: {'foogallery_autoptimize_dismiss'} **/
/** No params detected :-/ **/


/** Function ajax_add_taxonomy() called by wp_ajax hooks: {'foogallery_attachment_modal_taxonomy_add'} **/
/** Parameters found in function ajax_add_taxonomy(): {"post": ["img_id", "taxonomy", "term"]} **/
function ajax_add_taxonomy() {
			if ( ! check_ajax_referer( 'foogallery_attachment_modal_taxonomies', 'nonce', false ) ) {
				wp_send_json_error(
					array( 'message' => __( 'Invalid security token.', 'foogallery' ) ),
					403
				);
			}

			$img_id = isset( $_POST['img_id'] ) ? absint( wp_unslash( $_POST['img_id'] ) ) : 0;
			$taxonomy = isset( $_POST['taxonomy'] ) ? sanitize_key( wp_unslash( $_POST['taxonomy'] ) ) : '';
			$term = isset( $_POST['term'] ) ? sanitize_text_field( wp_unslash( $_POST['term'] ) ) : '';

			if ( ! $img_id || '' === $taxonomy || '' === $term ) {
				wp_send_json_error(
					array( 'message' => __( 'Invalid data.', 'foogallery' ) ),
					400
				);
			}

			if ( ! taxonomy_exists( $taxonomy ) ) {
				wp_send_json_error(
					array( 'message' => __( 'Invalid taxonomy.', 'foogallery' ) ),
					400
				);
			}

			if ( ! current_user_can( 'edit_post', $img_id ) ) {
				wp_send_json_error(
					array( 'message' => __( 'Insufficient permissions.', 'foogallery' ) ),
					403
				);
			}

			$tax = get_taxonomy( $taxonomy );
			if ( $tax && ! empty( $tax->cap->assign_terms ) && ! current_user_can( $tax->cap->assign_terms ) ) {
				wp_send_json_error(
					array( 'message' => __( 'Insufficient permissions.', 'foogallery' ) ),
					403
				);
			}

			$new_term = wp_insert_term( $term, $taxonomy );
			if ( is_wp_error( $new_term ) ) {
				wp_send_json_error(
					array( 'message' => __( 'Could not add new taxonomy term!', 'foogallery' ) ),
					400
				);
			}

			if ( isset( $new_term['term_id'] ) ) {
				$new_term_obj = get_term( $new_term['term_id'] );
				wp_send_json_success(
					array(
						'id' => $new_term_obj->term_id,
						'name' => $new_term_obj->name,
					)
				);
			}

			wp_send_json_error(
				array( 'message' => __( 'Invalid data.', 'foogallery' ) ),
				400
			);
		}


/** Function ajax_thumb_generation_test() called by wp_ajax hooks: {'foogallery_thumb_generation_test'} **/
/** No params detected :-/ **/


/** Function ajax_remove_override() called by wp_ajax hooks: {'foogallery_remove_alternate_img'} **/
/** Parameters found in function ajax_remove_override(): {"post": ["img_id"]} **/
function ajax_remove_override() {

            if ( ! check_ajax_referer( 'foogallery-modal-nonce', 'nonce', false ) ) {
                wp_send_json_error(
                    array( 'message' => __( 'Invalid security token.', 'foogallery' ) ),
                    403
                );
            }

            $img_id = isset( $_POST['img_id'] ) ? absint( wp_unslash( $_POST['img_id'] ) ) : 0;

            if ( ! $img_id ) {
                wp_send_json_error(
                    array( 'message' => __( 'Invalid attachment data.', 'foogallery' ) ),
                    400
                );
            }

            if ( ! current_user_can( 'edit_post', $img_id ) ) {
                wp_send_json_error(
                    array( 'message' => __( 'Insufficient permissions.', 'foogallery' ) ),
                    403
                );
            }

            delete_post_meta( $img_id, '_foogallery_override_thumbnail' );

            wp_send_json_success();
        }


/** Function ajax_gallery_preview() called by wp_ajax hooks: {'foogallery_preview'} **/
/** No params detected :-/ **/


/** Function ajax_clear_gallery_thumb_cache() called by wp_ajax hooks: {'foogallery_clear_gallery_thumb_cache'} **/
/** Parameters found in function ajax_clear_gallery_thumb_cache(): {"post": ["foogallery_id"]} **/
function ajax_clear_gallery_thumb_cache() {
			if ( ! check_ajax_referer( 'foogallery_clear_gallery_thumb_cache', 'foogallery_clear_gallery_thumb_cache_nonce', false ) ) {
				wp_send_json_error(
					array( 'message' => __( 'Invalid security token.', 'foogallery' ) ),
					403
				);
			}

			$foogallery_id = isset( $_POST['foogallery_id'] ) ? absint( $_POST['foogallery_id'] ) : 0;
			if ( ! $foogallery_id ) {
				wp_send_json_error(
					array( 'message' => __( 'Invalid gallery ID.', 'foogallery' ) ),
					400
				);
			}

			if ( ! current_user_can( 'edit_post', $foogallery_id ) ) {
				wp_send_json_error(
					array( 'message' => __( 'Insufficient permissions.', 'foogallery' ) ),
					403
				);
			}

			$foogallery = FooGallery::get_by_id( $foogallery_id );
			if ( ! $foogallery ) {
				wp_send_json_error(
					array( 'message' => __( 'Gallery not found.', 'foogallery' ) ),
					404
				);
			}

			if ( self::clear_gallery_thumbnail_cache( $foogallery ) ) {
				wp_send_json_success(
					array( 'message' => __( 'The thumbnail cache has been cleared!', 'foogallery' ) )
				);
			}

			wp_send_json_success(
				array( 'message' => __( 'There was no thumbnail cache to clear.', 'foogallery' ) )
			);
		}


/** Function ajax_save_gallery_details() called by wp_ajax hooks: {'foogallery_save_gallery_details'} **/
/** No params detected :-/ **/


/** Function ajax_clear_all_caches() called by wp_ajax hooks: {'foogallery_clear_html_cache'} **/
/** No params detected :-/ **/


/** Function ajax_uninstall() called by wp_ajax hooks: {'foogallery_uninstall'} **/
/** Parameters found in function ajax_uninstall(): {"post": ["confirmation"]} **/
function ajax_uninstall() {
			if ( ! check_ajax_referer( 'foogallery_uninstall', '_wpnonce', false ) ) {
				wp_send_json_error(
					array( 'message' => __( 'Invalid security token.', 'foogallery' ) ),
					403
				);
			}

			if ( ! current_user_can( 'install_plugins' ) ) {
				wp_send_json_error(
					array( 'message' => __( 'Insufficient permissions.', 'foogallery' ) ),
					403
				);
			}

			$confirmation = '';
			if ( isset( $_POST['confirmation'] ) ) {
				$confirmation = sanitize_text_field( wp_unslash( $_POST['confirmation'] ) );
				$confirmation = trim( $confirmation );
			}

			if ( '' === $confirmation ) {
				wp_send_json_success(
					array( 'html' => $this->get_uninstall_markup( true ) )
				);
			}

			if ( 'UNINSTALL' !== $confirmation ) {
				wp_send_json_error(
					array(
						'html'    => $this->get_uninstall_markup(
							true,
							__( 'Type UNINSTALL exactly to confirm the full uninstall.', 'foogallery' )
						),
						'message' => __( 'Type UNINSTALL exactly to confirm the full uninstall.', 'foogallery' ),
					),
					400
				);
			}

			foogallery_uninstall();

			wp_send_json_success(
				array( 'html' => $this->get_uninstall_success_markup() )
			);
		}


/** Function ajax_clear_attachment_thumb_cache() called by wp_ajax hooks: {'foogallery_clear_attachment_thumb_cache'} **/
/** Parameters found in function ajax_clear_attachment_thumb_cache(): {"post": ["attachment_id"]} **/
function ajax_clear_attachment_thumb_cache() {
			if ( ! check_ajax_referer( 'foogallery_clear_attachment_thumb_cache', 'foogallery_clear_attachment_thumb_cache_nonce', false ) ) {
				wp_send_json_error(
					array( 'message' => __( 'Invalid security token.', 'foogallery' ) ),
					403
				);
			}

			$attachment_id = isset( $_POST['attachment_id'] ) ? absint( $_POST['attachment_id'] ) : 0;
			if ( ! $attachment_id ) {
				wp_send_json_error(
					array( 'message' => __( 'Invalid attachment ID.', 'foogallery' ) ),
					400
				);
			}

			if ( ! current_user_can( 'edit_post', $attachment_id ) ) {
				wp_send_json_error(
					array( 'message' => __( 'Insufficient permissions.', 'foogallery' ) ),
					403
				);
			}

			$attachment = get_post( $attachment_id );
			if ( ! $attachment || 'attachment' !== $attachment->post_type ) {
				wp_send_json_error(
					array( 'message' => __( 'Attachment not found.', 'foogallery' ) ),
					404
				);
			}

			$engine = foogallery_thumb_active_engine();

			if ( $engine->has_local_cache() ) {
				$url = wp_get_attachment_image_url( $attachment_id, 'full' );

				ob_start();

				$engine->clear_local_cache_for_file( $url );

				ob_end_clean();

				wp_send_json_success(
					array( 'message' => __( 'The thumbnail cache has been cleared!', 'foogallery' ) )
				);
			}

			wp_send_json_success(
				array( 'message' => __( 'There was no thumbnail cache to clear.', 'foogallery' ) )
			);
		}


/** Function ajax_create_gallery_page() called by wp_ajax hooks: {'foogallery_create_gallery_page'} **/
/** Parameters found in function ajax_create_gallery_page(): {"post": ["foogallery_id"]} **/
function ajax_create_gallery_page() {
			if ( ! check_ajax_referer( 'foogallery_create_gallery_page', 'foogallery_create_gallery_page_nonce', false ) ) {
				wp_send_json_error(
					array( 'message' => __( 'Invalid security token.', 'foogallery' ) ),
					403
				);
			}

			$foogallery_id = isset( $_POST['foogallery_id'] ) ? absint( $_POST['foogallery_id'] ) : 0;
			if ( ! $foogallery_id ) {
				wp_send_json_error(
					array( 'message' => __( 'Invalid gallery ID.', 'foogallery' ) ),
					400
				);
			}

			// Check if the user can edit the current foogallery.
			if ( ! current_user_can( 'edit_post', $foogallery_id ) ) {
				wp_send_json_error(
					array( 'message' => __( 'Insufficient permissions.', 'foogallery' ) ),
					403
				);
			}

			// Check if the user can create draft pages.
			if ( ! current_user_can( 'edit_pages' ) ) {
				wp_send_json_error(
					array( 'message' => __( 'Insufficient permissions.', 'foogallery' ) ),
					403
				);
			}

			$foogallery = FooGallery::get_by_id( $foogallery_id );
			if ( ! $foogallery ) {
				wp_send_json_error(
					array( 'message' => __( 'Gallery not found.', 'foogallery' ) ),
					404
				);
			}

			$content = apply_filters( 'foogallery_create_gallery_page_content', $foogallery->shortcode(), $foogallery );

			$post = apply_filters( 'foogallery_create_gallery_page_arguments', array(
				'post_content' => $content,
				'post_title'   => $foogallery->name,
				'post_status'  => 'draft',
				'post_type'    => 'page',
			) );

			$page_id = wp_insert_post( $post, true );
			if ( is_wp_error( $page_id ) ) {
				wp_send_json_error(
					array( 'message' => $page_id->get_error_message() ),
					500
				);
			}

			wp_send_json_success(
				array( 'page_id' => $page_id )
			);
		}


/** Function ajax_clear_css_optimizations() called by wp_ajax hooks: {'foogallery_clear_css_optimizations'} **/
/** No params detected :-/ **/


/** Function dismiss_notice_ajax_callback() called by wp_ajax hooks: {'fs_dismiss_notice_action_{$ajax_action_suffix}'} **/
/** Parameters found in function dismiss_notice_ajax_callback(): {"post": ["message_id"]} **/
function dismiss_notice_ajax_callback() {
            check_admin_referer( 'fs_dismiss_notice_action' );

            if ( ! is_numeric( $_POST['message_id'] ) ) {
                $this->_sticky_storage->remove( $_POST['message_id'] );
            }

            wp_die();
        }


/** Function ajax_apply_retina_defaults() called by wp_ajax hooks: {'foogallery_apply_retina_defaults'} **/
/** Parameters found in function ajax_apply_retina_defaults(): {"post": ["defaults"]} **/
function ajax_apply_retina_defaults() {
			if ( ! check_ajax_referer( 'foogallery_apply_retina_defaults', '_wpnonce', false ) ) {
				wp_send_json_error(
					array( 'message' => __( 'Invalid security token.', 'foogallery' ) ),
					403
				);
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error(
					array( 'message' => __( 'Insufficient permissions.', 'foogallery' ) ),
					403
				);
			}

			$defaults = isset( $_POST['defaults'] ) ? sanitize_text_field( wp_unslash( $_POST['defaults'] ) ) : '';

			//extract the settings using a regex
			$regex = '/foogallery\[default_retina_support\|(?<setting>.+?)\]/';

			preg_match_all( $regex, $defaults, $matches );

			$gallery_retina_settings = array();

			if ( isset( $matches[1] ) ) {
				foreach ( $matches[1] as $match ) {
					$gallery_retina_settings[ $match ] = 'true';
				}
			}

			//go through all galleries and update the retina settings
			$galleries = foogallery_get_all_galleries();
			$gallery_update_count = 0;
			foreach ( $galleries as $gallery ) {
				update_post_meta( $gallery->ID, FOOGALLERY_META_RETINA, $gallery_retina_settings );
				$gallery_update_count++;
			}

			/* translators: %s: number of galleries updated. */
			$message = sprintf(
				/* translators: %s: Value inserted at runtime. */
				_n(
					'1 gallery successfully updated to use the default retina settings.',
					'%s galleries successfully updated to use the default retina settings.',
					$gallery_update_count,
					'foogallery'
				),
				absint( $gallery_update_count )
			);

			wp_send_json_success( array( 'html' => esc_html( $message ) ) );
		}


/** Function _toggle_debug_mode() called by wp_ajax hooks: {'fs_toggle_debug_mode'} **/
/** No params detected :-/ **/


/** Function admin_fooconvert_notice_dismiss() called by wp_ajax hooks: {'foogallery_admin_fooconvert_notice_dismiss'} **/
/** No params detected :-/ **/


/** Function ajax_galleries_html() called by wp_ajax hooks: {'foogallery_load_galleries'} **/
/** No params detected :-/ **/


/** Function ajax_clear_thumbnail_cache() called by wp_ajax hooks: {'foogallery_clear_thumbnail_cache'} **/
/** No params detected :-/ **/


/** Function create_demo_galleries() called by wp_ajax hooks: {'foogallery_admin_import_demos'} **/
/** No params detected :-/ **/


/** Function admin_rating_notice_dismiss() called by wp_ajax hooks: {'foogallery_admin_rating_notice_dismiss'} **/
/** No params detected :-/ **/


/** Function ajax_save_modal() called by wp_ajax hooks: {'foogallery_attachment_modal_save'} **/
/** Parameters found in function ajax_save_modal(): {"post": ["foogallery", "img_id"]} **/
function ajax_save_modal() {
			$foogallery = isset( $_POST['foogallery'] ) ? wp_unslash( $_POST['foogallery'] ) : array();

			if ( ! is_array( $foogallery ) || empty( $foogallery ) ) {
				wp_send_json_error(
					array( 'message' => __( 'Invalid attachment data.', 'foogallery' ) ),
					400
				);
			}

			if ( ! check_ajax_referer( 'foogallery-modal-nonce', 'nonce', false ) ) {
				wp_send_json_error(
					array( 'message' => __( 'Invalid security token.', 'foogallery' ) ),
					403
				);
			}

			$img_id = isset( $_POST['img_id'] ) ? absint( wp_unslash( $_POST['img_id'] ) ) : 0;

			if ( ! $img_id ) {
				wp_send_json_error(
					array( 'message' => __( 'Invalid attachment data.', 'foogallery' ) ),
					400
				);
			}

			if ( ! current_user_can( 'edit_post', $img_id ) ) {
				wp_send_json_error(
					array( 'message' => __( 'Insufficient permissions.', 'foogallery' ) ),
					403
				);
			}

			$attachment_post = get_post( $img_id );
			if ( ! is_a( $attachment_post, 'WP_Post' ) || 'attachment' !== $attachment_post->post_type ) {
				wp_send_json_error(
					array( 'message' => __( 'Invalid attachment data.', 'foogallery' ) ),
					400
				);
			}

			do_action( 'foogallery_attachment_save_data', $img_id, $foogallery );
			wp_send_json_success();
		}


/** Function ajax_get_gallery_details() called by wp_ajax hooks: {'foogallery_get_gallery_details'} **/
/** No params detected :-/ **/


/** Function ajax_get_gallery_info() called by wp_ajax hooks: {'foogallery_tinymce_load_info'} **/
/** No params detected :-/ **/


/** Function ajax_open_modal() called by wp_ajax hooks: {'foogallery_attachment_modal_open'} **/
/** Parameters found in function ajax_open_modal(): {"post": ["img_id", "gallery_id"]} **/
function ajax_open_modal() {
			if ( ! check_ajax_referer( 'foogallery_attachment_modal_open', 'nonce', false ) ) {
				wp_send_json_error(
					array( 'message' => __( 'Invalid security token.', 'foogallery' ) ),
					403
				);
			}

			$img_id = isset( $_POST['img_id'] ) ? absint( wp_unslash( $_POST['img_id'] ) ) : 0;
			$gallery_id = isset( $_POST['gallery_id'] ) ? absint( wp_unslash( $_POST['gallery_id'] ) ) : 0;

			if ( ! $img_id || ! $gallery_id ) {
				wp_send_json_error(
					array( 'message' => __( 'Invalid attachment data.', 'foogallery' ) ),
					400
				);
			}

			if ( ! current_user_can( 'edit_post', $img_id ) ) {
				wp_send_json_error(
					array( 'message' => __( 'Insufficient permissions.', 'foogallery' ) ),
					403
				);
			}

			if ( ! current_user_can( 'edit_post', $gallery_id ) ) {
				wp_send_json_error(
					array( 'message' => __( 'Insufficient permissions.', 'foogallery' ) ),
					403
				);
			}

			$modal_data = $this->build_modal_data( $_POST );
			$image_alt  = ! empty( $modal_data['image_alt'] ) ? $modal_data['image_alt'] : $modal_data['img_title'];
			if ( empty( $image_alt ) ) {
				$image_alt = __( 'Attachment preview', 'foogallery' );
			}

			ob_start() ?>

			<div class="foogallery-image-edit-main" data-img_id="<?php echo esc_attr( $modal_data['img_id'] ); ?>" data-gallery_id="<?php echo esc_attr( $modal_data['gallery_id'] ); ?>">
				<?php do_action( 'foogallery_attachment_modal_before_tab_container', $modal_data ); ?>
                <div class="foogallery-image-edit-view">
                    <?php

                    do_action( 'foogallery_attachment_modal_before_thumbnail', $modal_data );

                    if ( $modal_data['image_attributes'] ) { ?>
                        <img src="<?php echo esc_url( $modal_data['image_attributes'][0] ); ?>" width="<?php echo esc_attr( $modal_data['image_attributes'][1] ); ?>" height="<?php echo esc_attr( $modal_data['image_attributes'][2] ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" />
                    <?php } ?>
                </div>
                <div class="foogallery-image-edit-button">
                    <a target="_blank" href="<?php echo esc_url( get_admin_url().'post.php?post='.$modal_data['img_id'].'&action=edit' );?>" class="button"><?php esc_html_e('Edit Image', 'foogallery'); ?></a>
                    <a target="_blank" href="<?php echo esc_url( $modal_data['img_path'] );?>" class="button"><?php esc_html_e('Open Full Size Image', 'foogallery'); ?></a>
                </div>
			</div>

			<div class="foogallery-image-edit-meta">

				<?php do_action( 'foogallery_attachment_modal_before_tabs', $modal_data ); ?>

				<div class="tabset">
					<?php do_action( 'foogallery_attachment_modal_tabs_view', $modal_data ); ?>
				</div>
				<div class="tab-panels">
					<form id="foogallery_attachment_modal_save_form" method="post" enctype="multipart/form-data">
						<input type="hidden" name="action" value="foogallery_attachment_modal_save">
						<input type="hidden" name="nonce" value="<?php echo esc_attr( $modal_data['nonce'] ); ?>">
						<input type="hidden" name="img_id" value="<?php echo esc_attr( $modal_data['img_id'] ); ?>">
						<?php do_action( 'foogallery_attachment_modal_tab_content', $modal_data ); ?>
					</form>
				</div>

				<?php do_action( 'foogallery_attachment_modal_after_tabs', $modal_data ); ?>
				
			</div>
            <?php

            do_action( 'foogallery_attachment_modal_after_tab_container', $modal_data );
				
			wp_send_json( array(
                'html' => ob_get_clean(),
                'prev_slide' => $modal_data['prev_slide'],
                'next_slide' => $modal_data['next_slide'],
                'next_img_id' => $modal_data['next_img_id'],
                'prev_img_id' => $modal_data['prev_img_id'],
                'override_thumbnail' => $modal_data['foogallery_override_thumbnail'],
                'current_tab' => $modal_data['current_tab']
            ) );
		}


