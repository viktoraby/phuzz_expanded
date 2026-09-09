<?php
/***
*
*Found actions: 30
*Found functions:30
*Extracted functions:30
*Total parameter names extracted: 16
*Overview: {'get_modal_albums_upgrade': {'modula_modal-albums_upgrade'}, 'wp_core_gallery_import': {'modula_importer_wp_core_gallery_import'}, 'dismiss_edit_notice': {'modula-edit-notice'}, 'ajax_import_images': {'modula_ajax_import_images'}, 'ajax_opt_out': {'wpchill_telemetry_opt_out'}, 'add_images_to_gallery_callback': {'add_images_to_gallery'}, 'get_modal_video_upgrade': {'modula_modal-video_upgrade'}, 'get_modal_instagram_upgrade': {'modula_modal-instagram_upgrade'}, 'get_modal_content_galleries_upgrade': {'modula_modal-content-galleries_upgrade'}, 'ajax_create_from_remote_prefix': {'modula_bound_gallery_create_from_remote_prefix'}, 'ajax_opt_in': {'wpchill_telemetry_opt_in'}, 'modula_lbu_notice': {'modula_lbu_notice'}, 'modula_shortcode_editor': {'modula_shortcode_editor'}, 'get_modal_gallery_defaults_upgrade': {'modula_modal-gallery-defaults_upgrade'}, 'modula_remember_tab_save': {'modula_remember_tab'}, 'get_modal_bound_gallery_upgrade': {'modula_modal-bound-gallery_upgrade'}, 'ajax': {'epsilon_modula_review'}, 'modula_elementor_ajax_search': {'modula_elementor_ajax_search'}, 'check_hover_effect': {'modula_check_hover_effect'}, 'get_modal_albums_defaults_upgrade': {'modula_modal-albums-defaults_upgrade'}, 'get_gallery': {'modula_get_gallery'}, 'modula_uninstall_plugin': {'modula_uninstall_plugin'}, 'get_jsconfig': {'modula_get_jsconfig'}, 'autocomplete_url': {'modula_autocomplete'}, 'ajax_restore_exclusion': {'modula_bound_gallery_restore_exclusion'}, 'get_modal_proofing_upgrade': {'modula_modal-image-proofing_upgrade'}, 'ajax_create_from_media_folder': {'modula_bound_gallery_create_from_media_folder'}, 'ajax_dismiss_consent': {'wpchill_telemetry_dismiss_consent'}, 'get_modal_bulk_editor_upgrade': {'modula_modal-bulk-editor_upgrade'}, 'get_modal_licenses_upgrade': {'modula_modal-image-licensing_upgrade'}}
*
***/

/** Function get_modal_albums_upgrade() called by wp_ajax hooks: {'modula_modal-albums_upgrade'} **/
/** No params detected :-/ **/


/** Function wp_core_gallery_import() called by wp_ajax hooks: {'modula_importer_wp_core_gallery_import'} **/
/** Parameters found in function wp_core_gallery_import(): {"post": ["id", "gallery_title"]} **/
function wp_core_gallery_import( $galery_atts = array() ) {

		global $wpdb;
		$modula_importer = Modula_Importer::get_instance();

		// Set max execution time so we don't timeout
		ini_set( 'max_execution_time', 0 );
		set_time_limit( 0 );

		// If no gallery ID, get from AJAX request
		if ( empty( $galery_atts ) ) {

			// Run a security check first.
			check_ajax_referer( 'modula-importer', 'nonce' );

			if ( ! isset( $_POST['id'] ) ) {
				$this->modula_import_result( false, esc_html__( 'No gallery was selected', 'modula-best-grid-gallery' ) );
			}
			// Reinitialize as array, it may be string
			$galery_atts = array();
			$galery_atts = json_decode( stripslashes( $_POST['id'] ), true );
		}

		// Get page with gallery
		$post          = get_post( $galery_atts['id'] );
		$content       = $post->post_content;
		$search_string = $galery_atts['shortcode'];
		$result        = preg_match_all( $search_string, $content, $matches );

		if ( $result && $result > 0 ) {
			foreach ( $matches[0] as $sc ) {
				$modula_images     = array();
				$pattern           = '/ids\s*=\s*["\']([^"\']+)["\']/';

				$result            = preg_match( $pattern, $sc, $gallery_ids );
				$image_ids         = $modula_importer->prepare_images( 'wp_core', $gallery_ids[1] );
				$gallery_image_ids = $gallery_ids[0];

				foreach ( $image_ids as $image ) {
					$img = get_post( $image );
					if ( $img ) {
						// Build Modula Gallery modula-images metadata
						$modula_images[] = array(
							'id'          => absint( $image ),
							'alt'         => sanitize_text_field( get_post_meta( $image, '_wp_attachment_image_alt', true ) ),
							'title'       => sanitize_text_field( $img->post_title ),
							'description' => wp_filter_post_kses( $img->post_content ),
							'halign'      => 'center',
							'valign'      => 'middle',
							'link'        => '',
							'target'      => '',
							'width'       => 2,
							'height'      => 2,
							'filters'     => '',
						);
					}
				}

				if ( count( $modula_images ) == 0 ) {
					$this->modula_import_result( false, esc_html__( 'No images found in gallery. Skipping gallery...', 'modula-best-grid-gallery' ) );
				}

				// Get Modula Gallery defaults, used to set modula-settings metadata
				$modula_settings = Modula_CPT_Fields_Helper::get_defaults();

				// Create Modula CPT
				$modula_gallery_id = wp_insert_post(
					array(
						'post_type'   => 'modula-gallery',
						'post_status' => 'publish',
						'post_title'  => isset( $_POST['gallery_title'] ) ? sanitize_text_field( $_POST['gallery_title'] ) : '',
					)
				);

				// Attach meta modula-settings to Modula CPT
				update_post_meta( $modula_gallery_id, 'modula-settings', $modula_settings );

				// Attach meta modula-images to Modula CPT
				update_post_meta( $modula_gallery_id, 'modula-images', $modula_images );

				$wp_core_shortcode = $galery_atts['shortcode'];
				$modula_shortcode  = '[modula id="' . $modula_gallery_id . '"]';

				// Replace Gallery PhotoBlocks shortcode with Modula Shortcode in Posts, Pages and CPTs
				$sql = $wpdb->prepare(
					'UPDATE ' . $wpdb->prefix . "posts SET post_content = REPLACE(post_content, '%s', '%s')",
					$wp_core_shortcode,
					$modula_shortcode
				);
				$wpdb->query( $sql );
			}
		}

		$this->modula_import_result( true, wp_kses_post( '<i class="imported-check dashicons dashicons-yes"></i>' ) );
	}


/** Function dismiss_edit_notice() called by wp_ajax hooks: {'modula-edit-notice'} **/
/** No params detected :-/ **/


/** Function ajax_import_images() called by wp_ajax hooks: {'modula_ajax_import_images'} **/
/** Parameters found in function ajax_import_images(): {"post": ["id", "source", "chunk"]} **/
function ajax_import_images() {

		ini_set( 'max_execution_time', 0 );
		set_time_limit( 0 );

		check_ajax_referer( 'modula-importer', 'nonce' );

		// Exit if no id
		if ( ! isset( $_POST['id'] ) ) {
			return false;
		}

		// Exit if no source
		if ( ! isset( $_POST['source'] ) ) {
			return false;
		}

		$source          = sanitize_text_field( wp_unslash( $_POST['source'] ) );
		$response        = array();
		$chunk           = isset( $_POST['chunk'] ) ? absint( $_POST['chunk'] ) : 0;
		$modula_importer = Modula_Importer::get_instance();

		// wp_core sends JSON {id, shortcode}; other sources send a plain gallery ID.
		if ( 'wp_core' === $source ) {
			$galery_atts = json_decode( stripslashes( $_POST['id'] ), true );
			$pattern     = '/ids\s*=\s*["\']([^"\']+)["\']/';
			preg_match( $pattern, $galery_atts['shortcode'], $gallery_ids );
			$images_ids = isset( $gallery_ids[1] ) ? $gallery_ids[1] : '';
			$gallery_id = absint( $galery_atts['id'] );
			$images     = $modula_importer->prepare_images( $source, $images_ids );
		} else {
			$gallery_id = $_POST['id'];
			$images     = $modula_importer->prepare_images( $source, $gallery_id );
		}

		// Initialize $images variable
		$attachments = array();
		// we slice the images in chunks so that the AJAX will do the rest
		$images = array_slice( $images, $chunk, 5 );

		if ( is_array( $images ) && count( $images ) > 0 ) {

			$response['attachments'] = apply_filters( 'modula_migrate_attachments_' . $source, array(), $images, $gallery_id );

			// If array smaller than 5 we reached the end of the array
			if ( count( $images ) < 5 ) {
				$response['end_of_array'] = 'end_of_array';
			}

		} else {
			// If there are no images in the array we reached the end of it
			$response['end_of_array'] = 'end_of_array';
		}

		echo json_encode( $response );
		wp_die();

	}


/** Function ajax_opt_out() called by wp_ajax hooks: {'wpchill_telemetry_opt_out'} **/
/** No params detected :-/ **/


/** Function add_images_to_gallery_callback() called by wp_ajax hooks: {'add_images_to_gallery'} **/
/** Parameters found in function add_images_to_gallery_callback(): {"post": ["selected", "gallery_id"]} **/
function add_images_to_gallery_callback() {
		// Verifică nonce-ul pentru securitate
		check_ajax_referer( 'modula-ajax-save', 'nonce' );

		$post_images = isset( $_POST['selected'] ) ? json_decode( stripslashes( $_POST['selected'] ), true ) : array();
		$gallery_id  = isset( $_POST['gallery_id'] ) ? intval( $_POST['gallery_id'] ) : 0;
		$data        = array(
			'old_images' => array(),
			'counter'    => array(
				'added'   => 0,
				'skipped' => 0,
			),
		);

		if ( empty( $post_images ) || $gallery_id <= 0 ) {
			if ( empty( $post_images ) ) {
				wp_send_json_error( esc_html__( 'No images were selected.', 'modula-best-grid-gallery' ) );
			} elseif ( $gallery_id <= 0 ) {
				wp_send_json_error( esc_html__( 'You must select a gallery where the images should be added.', 'modula-best-grid-gallery' ) );
			}

			die();
		}

		$gallery_post = get_post( $gallery_id );

		if ( ! $gallery_post || 'modula-gallery' !== $gallery_post->post_type ) {
			wp_send_json_error( esc_html__( 'Selected ID is not a Modula gallery', 'modula-best-grid-gallery' ) );
			die();
		}

		if ( ! current_user_can( 'edit_post', $gallery_id ) ) {
			wp_send_json_error( esc_html__( 'You are not allowed to edit this gallery.', 'modula-best-grid-gallery' ) );
			die();
		}

		$data['old_images'] = get_post_meta( $gallery_id, 'modula-images', true );

		if ( ! is_array( $data['old_images'] ) ) {
			$data['old_images'] = array();
		}
		$current_images = array_column( $data['old_images'], 'id' );
		if ( is_array( $post_images ) ) {
			foreach ( $post_images as $image ) {
				if ( ! isset( $image['id'] ) || in_array( $image['id'], $current_images ) ) {
					++$data['counter']['skipped'];
					continue;
				}

				if ( ! isset( $image['type'] ) || ( isset( $image['type'] ) && 'image' !== $image['type'] ) ) {
					++$data['counter']['skipped'];
					continue;
				}

				$data['old_images'][] = Modula_Admin_Helpers::sanitize_image( $image );
				++$data['counter']['added'];
			}
		}

		// Determine singular/plural for 'image'
		$image_text   = ( $data['counter']['added'] === 1 ) ? __( 'image was', 'modula-best-grid-gallery' ) : __( 'images were', 'modula-best-grid-gallery' );
		$skipped_text = ( $data['counter']['skipped'] === 1 ) ? __( 'image was', 'modula-best-grid-gallery' ) : __( 'images were', 'modula-best-grid-gallery' );

		// Construct the response message
		$message = apply_filters(
			'modula_grid_add_images_to_gallery_message',
			sprintf(
				esc_html__( '%1$d %2$s added, and %3$d %4$s skipped (already added in the gallery or incorrect extension).', 'modula-best-grid-gallery' ),
				absint( $data['counter']['added'] ),
				esc_html( $image_text ),
				absint( $data['counter']['skipped'] ),
				esc_html( $skipped_text ),
			),
			$post_images,
			$gallery_id,
			$current_images
		);

		$data = apply_filters( 'modula_grid_add_images_to_gallery', $data, $post_images, $gallery_id, $current_images );
		update_post_meta( $gallery_id, 'modula-images', $data['old_images'] );

		$notice = array(
			'title'   => esc_html__( 'Images added to Modula Gallery', 'modula-best-grid-gallery' ),
			'message' => $message,
			'status'  => 'success',
			'timed'   => 5000,
		);

		Modula_Notifications::add_notification( 'media-add-notice', $notice );

		wp_send_json_success( esc_html( $message ) );
		die();
	}


/** Function get_modal_video_upgrade() called by wp_ajax hooks: {'modula_modal-video_upgrade'} **/
/** No params detected :-/ **/


/** Function get_modal_instagram_upgrade() called by wp_ajax hooks: {'modula_modal-instagram_upgrade'} **/
/** No params detected :-/ **/


/** Function get_modal_content_galleries_upgrade() called by wp_ajax hooks: {'modula_modal-content-galleries_upgrade'} **/
/** No params detected :-/ **/


/** Function ajax_create_from_remote_prefix() called by wp_ajax hooks: {'modula_bound_gallery_create_from_remote_prefix'} **/
/** Parameters found in function ajax_create_from_remote_prefix(): {"post": ["target_id"]} **/
function ajax_create_from_remote_prefix() {
		check_ajax_referer( 'modula_bound_gallery_create', 'nonce' );

		if ( ! Bound_Gallery::is_entitled() ) {
			self::send_pro_required();
		}

		$target_id = isset( $_POST['target_id'] ) ? sanitize_text_field( wp_unslash( $_POST['target_id'] ) ) : '';
		$parsed    = Bound_Gallery::parse_remote_prefix_target( $target_id );
		if ( null === $parsed ) {
			wp_send_json_error(
				array(
					'code'    => 'modula_bound_gallery_invalid_prefix',
					'message' => __( 'A remote prefix is required.', 'modula-best-grid-gallery' ),
				),
				400
			);
		}

		self::send_create_result(
			Bound_Gallery::create_from_remote_prefix( $parsed['connection_id'], $parsed['prefix'] )
		);
	}


/** Function ajax_opt_in() called by wp_ajax hooks: {'wpchill_telemetry_opt_in'} **/
/** No params detected :-/ **/


/** Function modula_lbu_notice() called by wp_ajax hooks: {'modula_lbu_notice'} **/
/** Parameters found in function modula_lbu_notice(): {"post": ["nonce"]} **/
function modula_lbu_notice() {

		$nonce = '';

		if ( isset( $_POST['nonce'] ) ) {
			$nonce = $_POST['nonce'];
		}

		if ( ! wp_verify_nonce( $nonce, 'modula-ajax-save' ) ) {
			wp_send_json_error();
			die();
		}

		$modula_checks               = get_option( 'modula-checks', array() );
		$modula_checks['lbu_notice'] = '1';

		update_option( 'modula-checks', $modula_checks );
		wp_die();
	}


/** Function modula_shortcode_editor() called by wp_ajax hooks: {'modula_shortcode_editor'} **/
/** Parameters found in function modula_shortcode_editor(): {"request": ["nonce"]} **/
function modula_shortcode_editor() {
		// Check user capability
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'modula-best-grid-gallery' ) );
		}

		// Verify nonce
		$nonce = '';
		if ( isset( $_REQUEST['nonce'] ) ) {
			$nonce = sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) );
		}

		if ( ! wp_verify_nonce( $nonce, 'modula-ajax-save' ) ) {
			wp_die( esc_html__( 'Security check failed.', 'modula-best-grid-gallery' ) );
		}

		$css_path  = MODULA_URL . 'assets/css/admin/edit.css';
		$admin_url = admin_url();
		$galleries = Modula_Helper::get_galleries();
		include 'tinymce-galleries.php';
		wp_die();
	}


/** Function get_modal_gallery_defaults_upgrade() called by wp_ajax hooks: {'modula_modal-gallery-defaults_upgrade'} **/
/** No params detected :-/ **/


/** Function modula_remember_tab_save() called by wp_ajax hooks: {'modula_remember_tab'} **/
/** Parameters found in function modula_remember_tab_save(): {"post": ["nonce", "id"]} **/
function modula_remember_tab_save()
	{

		$nonce = $_POST['nonce'];
		if (! wp_verify_nonce($nonce, 'modula-ajax-save')) {
			wp_send_json(array('status' => 'failed'));
		}

		$id = isset($_POST['id']) ? absint($_POST['id']) : 0;

		// Check if post exists and is modula-gallery CPT
		if (! get_post_type($id) || 'modula-gallery' !== get_post_type($id)) {
			wp_send_json(array('status' => 'failed'));
		}

		wp_send_json(array('status' => 'ok'));
	}


/** Function get_modal_bound_gallery_upgrade() called by wp_ajax hooks: {'modula_modal-bound-gallery_upgrade'} **/
/** No params detected :-/ **/


/** Function ajax() called by wp_ajax hooks: {'epsilon_modula_review'} **/
/** Parameters found in function ajax(): {"post": ["check"]} **/
function ajax() {

		check_ajax_referer( 'epsilon-modula-review', 'security' );

		if ( ! isset( $_POST['check'] ) ) {
			wp_die( 'ok' );
		}

		$time = get_option( 'modula-rate-time' );

		if ( 'epsilon-rate' === $_POST['check'] || 'epsilon-no-rate' === $_POST['check'] ) {
			$time = time() + YEAR_IN_SECONDS * 5;
		} else {
			$time = time() + WEEK_IN_SECONDS;
		}

		update_option( 'modula-rate-time', $time );
		wp_die( 'ok' );
	}


/** Function modula_elementor_ajax_search() called by wp_ajax hooks: {'modula_elementor_ajax_search'} **/
/** Parameters found in function modula_elementor_ajax_search(): {"post": ["action", "nonce", "s"]} **/
function modula_elementor_ajax_search()
	{

		if (! isset($_POST['action']) || 'modula_elementor_ajax_search' !== $_POST['action']) {
			wp_send_json_error();
		}

		$nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
		if (! wp_verify_nonce($nonce, 'modula-ajax-save')) {
			wp_send_json_error();
		}

		if (! current_user_can('edit_posts')) {
			wp_send_json_error();
		}

		if (isset($_POST['s']) && '' !== $_POST['s']) {
			$args = array(
				'post_type'      => 'modula-gallery',
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
				's'              => sanitize_text_field(wp_unslash($_POST['s'])),
			);

			$query = new \WP_Query($args);

			if ($query->have_posts()) {
				$galleries = $query->posts;
				wp_send_json_success($galleries);
			}

			wp_send_json_success();
		}

		wp_send_json_error();
	}


/** Function check_hover_effect() called by wp_ajax hooks: {'modula_check_hover_effect'} **/
/** Parameters found in function check_hover_effect(): {"post": ["nonce", "effect"]} **/
function check_hover_effect( $effect ) {
		if ( ! isset( $_POST['nonce'] ) ) {
			wp_send_json_error( 'no nonce' );
			die();
		}

		if ( ! wp_verify_nonce( $_POST['nonce'], 'modula_nonce' ) ) {//phpcs:ignore
			wp_send_json_error();
			die();
		}

		if ( isset( $_POST['effect'] ) ) {
			$effect = $_POST['effect']; //phpcs:ignore
		}

		// Legacy preset slugs are gone; composable hover uses v2 `hover_builder`. Respond with all slots enabled for the block UI.
		if ( ! is_string( $effect ) || '' === $effect ) {
			wp_send_json(
				array(
					'title'       => true,
					'description' => true,
					'social'      => true,
					'scripts'     => false,
				)
			);
			die();
		}

		$effect_check = Modula_Helper::hover_effects_elements( $effect );

		wp_send_json( $effect_check );

		die();
	}


/** Function get_modal_albums_defaults_upgrade() called by wp_ajax hooks: {'modula_modal-albums-defaults_upgrade'} **/
/** No params detected :-/ **/


/** Function get_gallery() called by wp_ajax hooks: {'modula_get_gallery'} **/
/** Parameters found in function get_gallery(): {"get": ["nonce", "term"]} **/
function get_gallery() {

		$nonce = '';
		if ( isset( $_GET['nonce'] ) ) {
			$nonce = $_GET['nonce'];
		}

		if ( ! wp_verify_nonce( $nonce, 'modula_nonce' ) ) {
			die();
		}

		$suggestions = array();
		$term        = isset( $_GET['term'] ) ? sanitize_text_field( wp_unslash( $_GET['term'] ) ) : '';

		$loop = new WP_Query(
			array(
				'p'              => $term,
				'post_type'      => 'modula-gallery',
				'posts_per_page' => -1,
			)
		);
		while ( $loop->have_posts() ) {
			$loop->the_post();
			$suggestion['label'] = get_the_title();
			$suggestion['value'] = get_the_ID();
			$suggestions[]       = $suggestion;
		}

		wp_send_json( $suggestions );
	}


/** Function modula_uninstall_plugin() called by wp_ajax hooks: {'modula_uninstall_plugin'} **/
/** Parameters found in function modula_uninstall_plugin(): {"post": ["options"]} **/
function modula_uninstall_plugin() {

		global $wpdb;
		check_ajax_referer( 'modula_uninstall_plugin', 'security' );

		$uninstall_option = isset( $_POST['options'] ) ? $_POST['options'] : false;

		// Delete options
		if ( '1' == $uninstall_option['delete_options'] ) {
			// filter for options to be added by Modula's add-ons
			$options_array = apply_filters( 'modula_uninstall_db_options', array( 'modula_troubleshooting_option', 'modula-checks', 'modula_version', 'widget_modula_gallery_widget', 'modula-rate-time', 'modula_modern_beta', 'modula_beta_gallery_migrated' ) );

			foreach ( $options_array as $db_option ) {
				delete_option( $db_option );
			}
		}

		// Delete transients
		if ( '1' == $uninstall_option['delete_transients'] ) {
			// filter for transients to be added by Modula's add-ons
			$transients_array = apply_filters( 'modula_uninstall_transients', array( 'modula_all_extensions', 'modula-galleries', 'modula_pro_licensed_extensions' ) );

			foreach ( $transients_array as $db_transient ) {
				delete_transient( $db_transient );
			}
		}

		// Delete custom post type
		if ( '1' == $uninstall_option['delete_cpt'] ) {

			// filter for post types, mainly for Modula Albums
			$post_types = apply_filters( 'modula_uninstall_post_types', array( 'modula-gallery' ) );
			$galleries  = get_posts(
				array(
					'post_type'      => $post_types,
					'posts_per_page' => -1,
					'fields'         => 'ids',
				)
			);

			if ( is_array( $galleries ) && ! empty( $galleries ) ) {
				$id_in = implode( ',', $galleries );

				$sql      = $wpdb->prepare( "DELETE FROM  $wpdb->posts WHERE ID IN ( $id_in )" );
				$sql_meta = $wpdb->prepare( "DELETE FROM  $wpdb->postmeta WHERE post_id IN ( $id_in )" );
				$wpdb->query( $sql );
				$wpdb->query( $sql_meta );
			}
		}

		do_action( 'modula_uninstall' );

		require_once MODULA_PATH . 'includes/features/telemetry/wpchill-telemetry-uninstall.php';

		deactivate_plugins( MODULA_FILE );
		wp_die();
	}


/** Function get_jsconfig() called by wp_ajax hooks: {'modula_get_jsconfig'} **/
/** Parameters found in function get_jsconfig(): {"post": ["nonce", "settings"]} **/
function get_jsconfig() {
		if ( ! isset( $_POST['nonce'] ) ) {
			wp_send_json_error( 'no nonce' );
			die();
		}

		if ( ! wp_verify_nonce( $_POST['nonce'], 'modula_nonce' ) ) {//phpcs:ignore
			wp_send_json_error();
			die();
		}

		if ( isset( $_POST['settings'] ) ) {
			$settings = $_POST['settings']; //phpcs:ignore
		}

		$type = 'creative-gallery';
		if ( isset( $settings['type'] ) ) {
			$type = $settings['type'];
		} else {
			$settings['type'] = 'creative-gallery';
		}

		$in_view          = false;
		$inview_permitted = apply_filters( 'modula_loading_inview_grids', array( 'custom-grid', 'creative-gallery', 'grid', 'polaroid' ), $settings );
		if ( isset( $settings['inView'] ) && '1' == $settings['inView'] && in_array( $type, $inview_permitted, true ) ) {
			$in_view = true;
		}

		$js_config = Modula_Shortcode::get_jsconfig( $settings, $type, $in_view ); //phpcs:ignore
		wp_send_json( $js_config );

		die();
	}


/** Function autocomplete_url() called by wp_ajax hooks: {'modula_autocomplete'} **/
/** Parameters found in function autocomplete_url(): {"get": ["nonce", "term"]} **/
function autocomplete_url() {
		$nonce = $_GET['nonce'];

		if ( ! wp_verify_nonce( $nonce, 'modula-ajax-save' ) ) {
			die();
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( 'You are not authorized to access this page.', 'modula-best-grid-gallery' ) );
			die();
		}

		$suggestions = array();
		$term        = sanitize_text_field( $_GET['term'] );

		$loop = new WP_Query( 's=' . $term );
		while ( $loop->have_posts() ) {
			$loop->the_post();
			$suggestion['label'] = get_the_title();
			$suggestion['type']  = get_post_type();
			$suggestion['value'] = get_permalink();
			$suggestions[]       = $suggestion;
		}

		echo wp_json_encode( $suggestions );
		exit();
	}


/** Function ajax_restore_exclusion() called by wp_ajax hooks: {'modula_bound_gallery_restore_exclusion'} **/
/** Parameters found in function ajax_restore_exclusion(): {"post": ["gallery_id", "attachment_id"]} **/
function ajax_restore_exclusion() {
		check_ajax_referer( 'modula_bound_gallery_restore', 'nonce' );

		if ( ! Bound_Gallery::is_entitled() ) {
			self::send_pro_required();
		}

		$gallery_id    = isset( $_POST['gallery_id'] ) ? absint( wp_unslash( $_POST['gallery_id'] ) ) : 0;
		$attachment_id = isset( $_POST['attachment_id'] ) ? absint( wp_unslash( $_POST['attachment_id'] ) ) : 0;

		if ( $gallery_id < 1 || ! current_user_can( 'edit_post', $gallery_id ) ) {
			wp_send_json_error(
				array(
					'code'    => 'modula_bound_gallery_forbidden',
					'message' => __( 'You are not allowed to edit this gallery.', 'modula-best-grid-gallery' ),
				),
				403
			);
		}

		if ( ! Bound_Gallery::is_bound( $gallery_id ) ) {
			wp_send_json_error(
				array(
					'code'    => 'modula_bound_gallery_not_bound',
					'message' => __( 'This gallery is not bound.', 'modula-best-grid-gallery' ),
				),
				400
			);
		}

		if ( ! Bound_Gallery::restore_attachment( $gallery_id, $attachment_id ) ) {
			wp_send_json_error(
				array(
					'code'    => 'modula_bound_gallery_restore_failed',
					'message' => __( 'Could not restore that attachment.', 'modula-best-grid-gallery' ),
				),
				400
			);
		}

		wp_send_json_success(
			array(
				'galleryId'    => $gallery_id,
				'attachmentId' => $attachment_id,
				'hiddenItems'  => Bound_Gallery::get_hidden_from_gallery_items( $gallery_id ),
			)
		);
	}


/** Function get_modal_proofing_upgrade() called by wp_ajax hooks: {'modula_modal-image-proofing_upgrade'} **/
/** No params detected :-/ **/


/** Function ajax_create_from_media_folder() called by wp_ajax hooks: {'modula_bound_gallery_create_from_media_folder'} **/
/** Parameters found in function ajax_create_from_media_folder(): {"post": ["folder_id"]} **/
function ajax_create_from_media_folder() {
		check_ajax_referer( 'modula_bound_gallery_create', 'nonce' );

		if ( ! Bound_Gallery::is_entitled() ) {
			self::send_pro_required();
		}

		$folder_id = isset( $_POST['folder_id'] ) ? absint( wp_unslash( $_POST['folder_id'] ) ) : 0;
		self::send_create_result( Bound_Gallery::create_from_media_folder( $folder_id ) );
	}


/** Function ajax_dismiss_consent() called by wp_ajax hooks: {'wpchill_telemetry_dismiss_consent'} **/
/** No params detected :-/ **/


/** Function get_modal_bulk_editor_upgrade() called by wp_ajax hooks: {'modula_modal-bulk-editor_upgrade'} **/
/** No params detected :-/ **/


/** Function get_modal_licenses_upgrade() called by wp_ajax hooks: {'modula_modal-image-licensing_upgrade'} **/
/** No params detected :-/ **/


