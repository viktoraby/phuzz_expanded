<?php
/***
*
*Found actions: 10
*Found functions:10
*Extracted functions:10
*Total parameter names extracted: 10
*Overview: {'prepare_columns_wp_ajax_inline_save': {'inline-save'}, 'crop_image': {'tsf_crop_image'}, 'get_post_parent_slugs': {'tsf_get_post_parent_slugs'}, 'update_counter_type': {'tsf_update_counter'}, 'get_term_parent_slugs': {'tsf_get_term_parent_slugs'}, 'get_author_slug': {'tsf_get_author_slug'}, 'prepare_columns_wp_ajax_inline_save_tax': {'inline-save-tax'}, 'dismiss_notice': {'tsf_dismiss_notice'}, 'get_post_data': {'tsf_update_post_data'}, 'prepare_columns_wp_ajax_add_tag': {'add-tag'}}
*
***/

/** Function prepare_columns_wp_ajax_inline_save() called by wp_ajax hooks: {'inline-save'} **/
/** Parameters found in function prepare_columns_wp_ajax_inline_save(): {"post": ["post_ID", "post_type"]} **/
function prepare_columns_wp_ajax_inline_save() {

		if (
			   ! \check_ajax_referer( 'inlineeditnonce', '_inline_edit', false )
			|| empty( $_POST['post_ID'] )
			|| empty( $_POST['post_type'] )
			|| ! \current_user_can(
				'page' === $_POST['post_type'] ? 'edit_page' : 'edit_post',
				(int) $_POST['post_ID'],
			)
		) return;

		$this->init_columns_ajax();
	}


/** Function crop_image() called by wp_ajax hooks: {'tsf_crop_image'} **/
/** Parameters found in function crop_image(): {"post": ["id", "context", "cropDetails"]} **/
function crop_image() {

		Helper\Headers::clean_response_header();

		// phpcs:disable WordPress.Security.NonceVerification -- check_ajax_capability_referer does this.
		Utils::check_ajax_capability_referer( 'upload_files' );

		if ( ! isset( $_POST['id'], $_POST['context'], $_POST['cropDetails'] ) )
			\wp_send_json_error( [ 'message' => \esc_js( \__( 'Invalid request.', 'autodescription' ) ) ] );

		$attachment_id = \absint( $_POST['id'] );

		if ( ! $attachment_id || 'attachment' !== \get_post_type( $attachment_id ) || ! \wp_attachment_is_image( $attachment_id ) )
			\wp_send_json_error( [ 'message' => \esc_js( \__( 'Image could not be processed.', 'default' ) ) ] );

		$context = str_replace( '_', '-', \sanitize_key( $_POST['context'] ) );
		$data    = array_map( 'absint', $_POST['cropDetails'] );
		$cropped = \wp_crop_image( $attachment_id, $data['x1'], $data['y1'], $data['width'], $data['height'], $data['dst_width'], $data['dst_height'] );

		if ( ! $cropped || \is_wp_error( $cropped ) )
			\wp_send_json_error( [ 'message' => \esc_js( \__( 'Image could not be processed.', 'default' ) ) ] );

		switch ( $context ) {
			case 'tsf-image':
				/**
				 * Fires before a cropped image is saved.
				 *
				 * Allows to add filters to modify the way a cropped image is saved.
				 *
				 * @since 5.0.0 WordPress Core
				 *
				 * @param string $context       The Customizer control requesting the cropped image.
				 * @param int    $attachment_id The attachment ID of the original image.
				 * @param string $cropped       Path to the cropped image file.
				 */
				\do_action( 'wp_ajax_crop_image_pre_save', $context, $attachment_id, $cropped );

				/** This filter is documented in wp-admin/includes/class-custom-image-header.php */
				$cropped = \apply_filters( 'wp_create_file_in_uploads', $cropped, $attachment_id ); // For replication.

				$parent_url       = \wp_get_attachment_url( $attachment_id );
				$parent_basename  = \wp_basename( $parent_url );
				$cropped_basename = \wp_basename( $cropped );
				$url              = str_replace( $parent_basename, $cropped_basename, $parent_url );

				// phpcs:ignore WordPress.PHP.NoSilencedErrors -- See https://core.trac.wordpress.org/ticket/42480
				$size       = \function_exists( 'wp_getimagesize' ) ? \wp_getimagesize( $cropped ) : @getimagesize( $cropped );
				$image_type = $size ? $size['mime'] : 'image/jpeg';

				// Get the original image's post to pre-populate the cropped image.
				$original_attachment  = \get_post( $attachment_id );
				$sanitized_post_title = \sanitize_file_name( $original_attachment->post_title );
				/**
				 * Check if the original image has a title other than the "filename" default,
				 * meaning the image had a title when originally uploaded or its title was edited.
				 */
				$use_original_title = \strlen( trim( $original_attachment->post_title ) )
					&& ( $parent_basename !== $sanitized_post_title )
					&& ( pathinfo( $parent_basename, \PATHINFO_FILENAME ) !== $sanitized_post_title );

				$use_original_description = \strlen( trim( $original_attachment->post_content ) );

				$attachment = [
					'post_title'     => $use_original_title ? $original_attachment->post_title : $cropped_basename,
					'post_content'   => $use_original_description ? $original_attachment->post_content : $url,
					'post_mime_type' => $image_type,
					'guid'           => $url,
					'context'        => $context,
				];

				// Copy the image caption attribute (post_excerpt field) from the original image.
				if ( \strlen( trim( $original_attachment->post_excerpt ) ) )
					$attachment['post_excerpt'] = $original_attachment->post_excerpt;

				// Copy the image alt text attribute from the original image.
				if ( \strlen( trim( $original_attachment->_wp_attachment_image_alt ) ) )
					$attachment['meta_input'] = [
						'_wp_attachment_image_alt' => \wp_slash( $original_attachment->_wp_attachment_image_alt ),
					];

				$attachment_id = \wp_insert_attachment( $attachment, $cropped );
				$metadata      = \wp_generate_attachment_metadata( $attachment_id, $cropped );

				/**
				 * @since 5.0.0 WordPress Core
				 * @see wp_generate_attachment_metadata()
				 * @param array $metadata Attachment metadata.
				 */
				$metadata = \apply_filters( 'wp_ajax_cropped_attachment_metadata', $metadata );
				\wp_update_attachment_metadata( $attachment_id, $metadata );

				/**
				 * @since 5.0.0 WordPress Core
				 * @param int    $attachment_id The attachment ID of the cropped image.
				 * @param string $context       The Customizer control requesting the cropped image.
				 */
				$attachment_id = \apply_filters( 'wp_ajax_cropped_attachment_id', $attachment_id, $context );
				break;

			default:
				\wp_send_json_error( [ 'message' => \esc_js( \__( 'Image could not be processed.', 'default' ) ) ] );
		}

		\wp_send_json_success( \wp_prepare_attachment_for_js( $attachment_id ) );

		// phpcs:enable WordPress.Security.NonceVerification
	}


/** Function get_post_parent_slugs() called by wp_ajax hooks: {'tsf_get_post_parent_slugs'} **/
/** Parameters found in function get_post_parent_slugs(): {"post": ["post_id"]} **/
function get_post_parent_slugs() {

		Helper\Headers::clean_response_header();

		// phpcs:disable WordPress.Security.NonceVerification -- check_ajax_capability_referer() does this.
		Utils::check_ajax_capability_referer( 'edit_posts' );

		if ( ! isset( $_POST['post_id'] ) )
			\wp_send_json_error( 'invalid_request' );

		$post_id = \absint( $_POST['post_id'] );

		if ( ! $post_id )
			\wp_send_json_error( 'invalid_object_id' );

		$post_type_object = \get_post_type_object( \get_post( $post_id )->post_type ?? '' );

		// This prevents snooping around special post types instated by individuals with an underdeveloped sense of logic.
		if (
			   empty( $post_type_object )
			|| ! \current_user_can( $post_type_object->cap->edit_posts )
		) {
			\wp_send_json_error( 'missing_capabilities' );
		}

		$parent_post_slugs = [];

		foreach ( Data\Post::get_post_parents( $post_id, true ) as $parent_post ) {
			// We write it like this instead of [ id => slug ] to prevent reordering numericals via JSON.parse.
			$parent_post_slugs[] = [
				'id'   => $parent_post->ID,
				'slug' => $parent_post->post_name,
			];
		}

		\wp_send_json_success( $parent_post_slugs );
		// phpcs:enable WordPress.Security.NonceVerification
	}


/** Function update_counter_type() called by wp_ajax hooks: {'tsf_update_counter'} **/
/** Parameters found in function update_counter_type(): {"post": ["val"]} **/
function update_counter_type() {

		Helper\Headers::clean_response_header();

		// phpcs:disable WordPress.Security.NonceVerification -- check_ajax_capability_referer() does this.
		Utils::check_ajax_capability_referer( 'edit_posts' );

		/**
		 * Count up, reset to 0 if needed. We have 4 options: 0, 1, 2, 3
		 * $_POST['val'] already contains updated number.
		 */
		if ( isset( $_POST['val'] ) ) {
			$value = (int) $_POST['val'];
		} else {
			$value = Data\Plugin\User::get_meta_item( 'counter_type' ) + 1;
		}
		$value = \absint( $value );

		if ( $value > 3 )
			$value = 0;

		// Update the option and get results of action.
		Data\Plugin\User::update_single_meta_item( Query::get_current_user_id(), 'counter_type', $value );

		\wp_send_json_success();
		// phpcs:enable WordPress.Security.NonceVerification
	}


/** Function get_term_parent_slugs() called by wp_ajax hooks: {'tsf_get_term_parent_slugs'} **/
/** Parameters found in function get_term_parent_slugs(): {"post": ["term_id", "taxonomy"]} **/
function get_term_parent_slugs() {

		Helper\Headers::clean_response_header();

		// phpcs:disable WordPress.Security.NonceVerification -- check_ajax_capability_referer() does this.
		Utils::check_ajax_capability_referer( 'edit_posts' );

		if ( ! isset( $_POST['term_id'], $_POST['taxonomy'] ) )
			\wp_send_json_error( 'invalid_request' );

		$term_id = \absint( $_POST['term_id'] );

		if ( ! $term_id )
			\wp_send_json_error( 'invalid_object_id' );

		$parent_term_slugs = [];

		foreach ( Data\Term::get_term_parents( $term_id, $_POST['taxonomy'], true ) as $parent_term ) {
			// We write it like this instead of [ id => slug ] to prevent reordering numericals via JSON.parse.
			$parent_term_slugs[] = [
				'id'   => $parent_term->term_id,
				'slug' => $parent_term->slug,
			];
		}

		\wp_send_json_success( $parent_term_slugs );
		// phpcs:enable WordPress.Security.NonceVerification
	}


/** Function get_author_slug() called by wp_ajax hooks: {'tsf_get_author_slug'} **/
/** Parameters found in function get_author_slug(): {"post": ["author_id"]} **/
function get_author_slug() {

		Helper\Headers::clean_response_header();

		// phpcs:disable WordPress.Security.NonceVerification -- check_ajax_capability_referer() does this.
		Utils::check_ajax_capability_referer( 'edit_posts' );

		if ( ! isset( $_POST['author_id'] ) )
			\wp_send_json_error( 'invalid_request' );

		$author_id = \absint( $_POST['author_id'] );

		if ( ! $author_id )
			\wp_send_json_error( 'invalid_object_id' );

		// Send a sequential array of "slugs" for consistency with other slug fetchers.
		\wp_send_json_success( [
			[
				'id'   => $author_id,
				'slug' => Data\User::get_userdata( $author_id, 'user_nicename' ),
			],
		] );
		// phpcs:enable WordPress.Security.NonceVerification
	}


/** Function prepare_columns_wp_ajax_inline_save_tax() called by wp_ajax hooks: {'inline-save-tax'} **/
/** Parameters found in function prepare_columns_wp_ajax_inline_save_tax(): {"post": ["tax_ID"]} **/
function prepare_columns_wp_ajax_inline_save_tax() {

		if (
			   ! \check_ajax_referer( 'taxinlineeditnonce', '_inline_edit', false )
			|| empty( $_POST['tax_ID'] )
			|| ! \current_user_can( 'edit_term', (int) $_POST['tax_ID'] )
		) return;

		$this->init_columns_ajax();
	}


/** Function dismiss_notice() called by wp_ajax hooks: {'tsf_dismiss_notice'} **/
/** Parameters found in function dismiss_notice(): {"post": ["tsf_dismiss_key"]} **/
function dismiss_notice() {

		Helper\Headers::clean_response_header();

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- We require the POST data to find locally stored nonces.
		$key = $_POST['tsf_dismiss_key'] ?? '';

		if ( ! $key )
			\wp_send_json_error( null, 400 );

		$notices = Data\Plugin::get_site_cache( 'persistent_notices' ) ?? [];

		if ( empty( $notices[ $key ]['conditions']['capability'] ) ) {
			// Notice was deleted already elsewhere, or key was faulty. Either way, ignore--should be self-resolving.
			\wp_send_json_error( null, 409 );
		}

		if (
			   ! \current_user_can( $notices[ $key ]['conditions']['capability'] )
			|| ! \check_ajax_referer( Admin\Notice\Persistent::_get_dismiss_nonce_action( $key ), 'tsf_dismiss_nonce', false )
		) {
			\wp_die( -1, 403 );
		}

		Admin\Notice\Persistent::clear_notice( $key );
		\wp_send_json_success( null, 200 );
	}


/** Function get_post_data() called by wp_ajax hooks: {'tsf_update_post_data'} **/
/** Parameters found in function get_post_data(): {"post": ["post_id", "get"]} **/
function get_post_data() {

		Helper\Headers::clean_response_header();

		// phpcs:disable WordPress.Security.NonceVerification -- check_ajax_capability_referer() does this.
		$post_id = \absint( $_POST['post_id'] ?? 0 );

		Utils::check_ajax_capability_referer( 'edit_post', $post_id );

		$_get_defaults = [
			'seobar'          => false,
			'metadescription' => false,
			'ogdescription'   => false,
			'twdescription'   => false,
			'imageurl'        => false,
		];

		// Only get what's indexed in the defaults and set as "true".
		$get = array_keys(
			array_filter(
				array_intersect_key(
					array_merge(
						$_get_defaults,
						(array) ( $_POST['get'] ?? [] ),
					),
					$_get_defaults,
				)
			)
		);

		$generator_args = [ 'id' => $post_id ];

		$data = [];

		foreach ( $get as $g ) switch ( $g ) {
			case 'seobar':
				$data[ $g ] = Admin\SEOBar\Builder::generate_bar( $generator_args );
				break;

			case 'metadescription':
			case 'ogdescription':
			case 'twdescription':
				switch ( $g ) {
					case 'metadescription':
						if ( Query::is_static_front_page( $post_id ) ) {
							$data[ $g ] = Sanitize::metadata_content( Data\Plugin::get_option( 'homepage_description' ) )
									   ?: Meta\Description::get_generated_description( $generator_args );
						} else {
							$data[ $g ] = Meta\Description::get_generated_description( $generator_args );
						}
						break;
					case 'ogdescription':
						if ( Query::is_static_front_page( $post_id ) ) {
							$data[ $g ] = Sanitize::metadata_content( Data\Plugin::get_option( 'homepage_description' ) )
									   ?: Meta\Open_Graph::get_generated_description( $generator_args );
						} else {
							$data[ $g ] = Meta\Open_Graph::get_generated_description( $generator_args );
						}
						break;
					case 'twdescription':
						if ( Query::is_static_front_page( $post_id ) ) {
							$data[ $g ] = Sanitize::metadata_content( Data\Plugin::get_option( 'homepage_description' ) )
									   ?: Meta\Twitter::get_generated_description( $generator_args );
						} else {
							$data[ $g ] = Meta\Twitter::get_generated_description( $generator_args );
						}
				}

				$data[ $g ] = \esc_html( $data[ $g ] );
				break;

			case 'imageurl':
				if ( Query::is_static_front_page( $post_id ) ) {
					$data[ $g ] = \sanitize_url( Data\Plugin::get_option( 'homepage_social_image_url' ), [ 'https', 'http' ] )
							   ?: Meta\Image::get_first_generated_image_url( $generator_args, 'social' );
				} else {
					$data[ $g ] = Meta\Image::get_first_generated_image_url( $generator_args, 'social' );
				}
		}

		\wp_send_json_success( [
			'data'      => $data,
			'processed' => $get,
		] );
		// phpcs:enable WordPress.Security.NonceVerification
	}


/** Function prepare_columns_wp_ajax_add_tag() called by wp_ajax hooks: {'add-tag'} **/
/** Parameters found in function prepare_columns_wp_ajax_add_tag(): {"post": ["taxonomy"]} **/
function prepare_columns_wp_ajax_add_tag() {

		if (
			   ! \check_ajax_referer( 'add-tag', '_wpnonce_add-tag', false )
			|| empty( $_POST['taxonomy'] )
		) return;

		$taxonomy   = stripslashes( $_POST['taxonomy'] );
		$tax_object = $taxonomy ? \get_taxonomy( $taxonomy ) : false;

		if ( $tax_object && \current_user_can( $tax_object->cap->edit_terms ) )
			$this->init_columns_ajax();
	}


