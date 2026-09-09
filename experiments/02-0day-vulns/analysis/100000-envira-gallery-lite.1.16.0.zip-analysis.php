<?php
/***
*
*Found actions: 30
*Found functions:30
*Extracted functions:30
*Total parameter names extracted: 25
*Overview: {'envira_gallery_ajax_sort_images': {'envira_gallery_sort_images'}, 'envira_gallery_ajax_dismiss_notice': {'envira_gallery_ajax_dismiss_notice'}, 'envira_gallery_ajax_save_bulk_meta': {'envira_gallery_save_bulk_meta'}, 'envira_connect': {'envira_connect'}, 'envira_gallery_ajax_change_type': {'envira_gallery_change_type'}, 'save_onboarding_data': {'save_onboarding_data'}, 'envira_gallery_move_media': {'envira_gallery_move_media'}, 'envira_hide_admin_menu_tooltip_callback': {'envira_hide_admin_menu_tooltip'}, 'envira_gallery_ajax_set_user_setting': {'envira_gallery_set_user_setting'}, 'envira_gallery_ajax_activate_addon': {'envira_gallery_activate_addon'}, 'save_selected_addons': {'save_selected_addons'}, 'envira_gallery_ajax_dismiss_topbar': {'envira_gallery_ajax_dismiss_topbar'}, 'dismiss': {'envira_notification_dismiss'}, 'envira_gallery_ajax_remove_images': {'envira_gallery_remove_images'}, 'envira_activate_partner': {'envira_activate_partner'}, 'envira_gallery_ajax_load_image': {'envira_gallery_load_image'}, 'envira_gallery_ajax_deactivate_addon': {'envira_gallery_deactivate_addon'}, 'envira_redirect_to_add_new_gallery_callback': {'envira_redirect_to_add_new_gallery'}, 'envira_gallery_editor_get_galleries': {'envira_gallery_editor_get_galleries'}, 'envira_gallery_ajax_insert_images': {'envira_gallery_insert_images'}, 'dismiss_review': {'envira_dismiss_review'}, 'envira_gallery_ajax_save_meta': {'envira_gallery_save_meta'}, 'envira_deactivate_partner': {'envira_deactivate_partner'}, 'install_recommended_plugins': {'install_recommended_plugins'}, 'envira_install_partner': {'envira_install_partner'}, 'envira_gallery_ajax_remove_image': {'envira_gallery_remove_image'}, 'envira_gallery_ajax_refresh': {'envira_gallery_refresh'}, 'envira_gallery_get_attachment_links': {'envira_gallery_get_attachment_links'}, 'envira_gallery_ajax_install_addon': {'envira_gallery_install_addon'}, 'envira_gallery_ajax_load_gallery_data': {'envira_gallery_load_gallery_data'}}
*
***/

/** Function envira_gallery_ajax_sort_images() called by wp_ajax hooks: {'envira_gallery_sort_images'} **/
/** Parameters found in function envira_gallery_ajax_sort_images(): {"post": ["post_id", "order"]} **/
function envira_gallery_ajax_sort_images() {

	// Run a security check first.
	check_ajax_referer( 'envira-gallery-sort', 'nonce' );

	// Get the Envira Gallery ID.
	$post_id = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;

	if ( null === $post_id ) {
		wp_send_json_error( [ 'message' => esc_html__( 'Invalid Post ID.', 'envira-gallery-lite' ) ] );
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}

	// Prepare variables.
	$order        = isset( $_POST['order'] ) ? array_filter( array_map( 'absint', explode( ',', sanitize_text_field( wp_unslash( $_POST['order'] ) ) ) ) ) : [];
	$gallery_data = get_post_meta( $post_id, '_eg_gallery_data', true );

	$existing_ids = isset( $gallery_data['gallery'] ) ? array_keys( $gallery_data['gallery'] ) : [];
	$order        = array_filter(
		$order,
		function ( $id ) use ( $existing_ids ) {
			return in_array( $id, $existing_ids, true );
		}
	);

	// Guard: if every submitted ID was filtered out but the gallery has images,
	// something went wrong (race condition, stale tab, bad request) — abort rather
	// than silently save an empty gallery and delete all images.
	if ( empty( $order ) && ! empty( $existing_ids ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'No valid image IDs submitted for sorting.', 'envira-gallery-lite' ) ] );
	}

	// Copy the gallery config, removing the images
	// Stops config from getting lost when sorting + not clicking Publish/Update.
	$new_order = $gallery_data;
	unset( $new_order['gallery'] );
	$new_order['gallery'] = [];

	// Loop through the order and generate a new array based on order received.
	foreach ( $order as $id ) {
		$new_order['gallery'][ $id ] = $gallery_data['gallery'][ $id ];
	}

	// Update the gallery data.
	update_post_meta( $post_id, '_eg_gallery_data', $new_order );

	// Flush the gallery cache.
	Envira_Gallery_Common::get_instance()->flush_gallery_caches( $post_id );

	echo wp_json_encode( true );
	die;
}


/** Function envira_gallery_ajax_dismiss_notice() called by wp_ajax hooks: {'envira_gallery_ajax_dismiss_notice'} **/
/** Parameters found in function envira_gallery_ajax_dismiss_notice(): {"post": ["notice"]} **/
function envira_gallery_ajax_dismiss_notice() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-dismiss-notice', 'nonce' );

	if ( ! current_user_can( 'edit_dashboard' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to dismiss notices.', 'envira-gallery-lite' ) ] );
	}

	// Deactivate the notice.
	if ( isset( $_POST['notice'] ) ) {
		// Init the notice class and mark notice as deactivated.
		$notice = Envira_Gallery_Notice_Admin::get_instance();
		$notice->dismiss( sanitize_text_field( wp_unslash( $_POST['notice'] ) ) );

		echo wp_json_encode( true );
		die;
	}

	// If here, an error occured.
	echo wp_json_encode( false );
	die;
}


/** Function envira_gallery_ajax_save_bulk_meta() called by wp_ajax hooks: {'envira_gallery_save_bulk_meta'} **/
/** Parameters found in function envira_gallery_ajax_save_bulk_meta(): {"post": ["post_id", "image_ids", "meta"]} **/
function envira_gallery_ajax_save_bulk_meta() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-save-meta', 'nonce' );

	// Get the Envira Gallery ID.
	$post_id = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;

	if ( null === $post_id ) {
		wp_send_json_error( [ 'message' => esc_html__( 'Invalid Post ID.', 'envira-gallery-lite' ) ] );
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}

	// Prepare variables.
	$image_ids = isset( $_POST['image_ids'] ) ? array_map( 'absint', (array) wp_unslash( $_POST['image_ids'] ) ) : array();
	$image_ids = array_filter( $image_ids );

	// Allowlist: only accept known meta keys to prevent arbitrary data storage (blocklist approach misses unknown keys).
	$raw_meta = isset($_POST['meta']) ? (array) wp_unslash($_POST['meta']) : array(); // @codingStandardsIgnoreLine - Array
	$meta     = array_intersect_key(
		$raw_meta,
		array(
			'title'           => true,
			'alt'             => true,
			'link'            => true,
			'link_new_window' => true,
			'caption'         => true,
		)
	); // Allowlist: discard any keys not in this set.

	// Sanitize each allowed field at point of ingestion to prevent stored XSS.
	if ( isset( $meta['title'] ) ) {
		$meta['title'] = wp_kses_post( $meta['title'] ); // Allow safe HTML (bold, italic, etc.) but strip script/event tags.
	}
	if ( isset( $meta['alt'] ) ) {
		$meta['alt'] = sanitize_text_field( $meta['alt'] ); // Plain text only — alt attributes never need HTML.
	}
	if ( isset( $meta['link'] ) ) {
		$meta['link'] = esc_url_raw( $meta['link'] ); // Sanitize URL for storage; esc_url() is for output only.
	}
	if ( isset( $meta['link_new_window'] ) ) {
		$meta['link_new_window'] = (bool) $meta['link_new_window'] ? '1' : '0'; // Cast to boolean string to prevent arbitrary value storage.
	}
	if ( isset( $meta['caption'] ) ) {
		$meta['caption'] = wp_kses_post( $meta['caption'] ); // Allow safe HTML but strip script/event tags.
	}

	// Check the required variables exist.
	if ( empty( $post_id ) ) {
		wp_send_json_error();
	}
	if ( empty( $image_ids ) || ! is_array( $image_ids ) ) {
		wp_send_json_error();
	}
	if ( empty( $meta ) || ! is_array( $meta ) ) {
		wp_send_json_error();
	}

	// Get gallery.
	$gallery_data = get_post_meta( $post_id, '_eg_gallery_data', true );
	if ( empty( $gallery_data ) || ! is_array( $gallery_data ) ) {
		wp_send_json_error();
	}

	// Iterate through gallery images, updating the metadata.
	foreach ( $image_ids as $image_id ) {
		// If the image isn't in the gallery, something went wrong - so skip this image.
		if ( ! isset( $gallery_data['gallery'][ $image_id ] ) ) {
			continue;
		}

		// Update image metadata. Values are already sanitized at ingestion above; assign directly.
		if ( isset( $meta['title'] ) && ! empty( $meta['title'] ) ) {
			$gallery_data['gallery'][ $image_id ]['title'] = $meta['title']; // Already sanitized via wp_kses_post() at ingestion.
		}

		if ( isset( $meta['alt'] ) && ! empty( $meta['alt'] ) ) {
			// $meta['alt'] already sanitize_text_field()'d above; esc_attr() at render handles encoding.
			$gallery_data['gallery'][ $image_id ]['alt'] = trim( $meta['alt'] );
		}

		if ( isset( $meta['link'] ) && ! empty( $meta['link'] ) ) {
			// $meta['link'] already esc_url_raw()'d above; esc_url() at render handles encoding.
			$gallery_data['gallery'][ $image_id ]['link'] = $meta['link'];
		}

		if ( isset( $meta['link_new_window'] ) ) {
			// Use isset() not empty(): empty('0') is true, which would prevent saving '0' (disabled).
			// Value already cast to '0'/'1' at ingestion above.
			$gallery_data['gallery'][ $image_id ]['link_new_window'] = $meta['link_new_window'];
		}

		if ( isset( $meta['caption'] ) && ! empty( $meta['caption'] ) ) {
			$gallery_data['gallery'][ $image_id ]['caption'] = $meta['caption']; // Already sanitized via wp_kses_post() at ingestion.
		}

		// Allow filtering of meta before saving.
		$gallery_data = apply_filters( 'envira_gallery_ajax_save_bulk_meta', $gallery_data, $meta, $image_id, $post_id );
	}

	// Update the gallery data.
	update_post_meta( $post_id, '_eg_gallery_data', $gallery_data );

	// Flush the gallery cache.
	Envira_Gallery_Common::get_instance()->flush_gallery_caches( $post_id );

	// Done.
	wp_send_json_success();
	die;
}


/** Function envira_connect() called by wp_ajax hooks: {'envira_connect'} **/
/** Parameters found in function envira_connect(): {"post": ["key", "onboarding"]} **/
function envira_connect() {
	// Run a security check.
	check_ajax_referer( 'envira_gallery_connect' );

	if ( ! current_user_can( 'install_plugins' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to install plugins.', 'envira-gallery-lite' ) ] );
	}

	$key = ! empty( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : '';
	if ( empty( $key ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'Please enter your license key to connect.', 'envira-gallery-lite' ) ] );
	}

	$valid_key = envira_api_remote_request( 'verify-key', [ 'tgm-lite-key' => $key ] );

	// If it returns false, send back a generic error message and return.
	if ( ! $valid_key ) {
		wp_send_json_error( [ 'message' => esc_html__( 'There was an error connecting to the remote key API. Please try again later.', 'envira-gallery-lite' ) ] );
	}

	// If an error is returned, set the error and return.
	if ( ! empty( $valid_key->error ) ) {
		wp_send_json_error( [ 'message' => wp_kses_post( $valid_key->error ) ] );
	}

	$option = get_option( 'envira_gallery' );

	if ( ! is_array( $option ) ) {
		$option = [];
	}

	$option['key']         = $key;
	$option['type']        = isset( $valid_key->type ) ? $valid_key->type : $option['type'];
	$option['is_expired']  = false;
	$option['is_disabled'] = false;
	$option['is_invalid']  = false;

	update_option( 'envira_gallery', $option );

	// Install addons from onboarding.

	$onboarding = ! empty( $_POST['onboarding'] ) && sanitize_text_field( wp_unslash( $_POST['onboarding'] ) );

	if ( $onboarding ) {
		( new OnboardingWizard() )->install_selected_addons( $key );
	}

	$pro_path = trailingslashit( WP_PLUGIN_DIR ) . '/envira-gallery/envira-gallery.php';

	if ( file_exists( $pro_path ) ) {

		$active = activate_plugin( 'envira-gallery/envira-gallery.php', false, false, true );

		// Deactivate Lite.
		$plugin = plugin_basename( ENVIRA_LITE_FILE );

		deactivate_plugins( $plugin );

		do_action( 'envira_lite_plugin_deactivated', $plugin );

		wp_send_json_success(
			[
				'message' => esc_html__( 'Congratulations! This site is now receiving automatic updates.', 'envira-gallery-lite' ),
				'reload'  => true,
			]
		);
	}

	if ( ! isset( $valid_key->download_url ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'There was an error connecting to the remote key API. Please try again later.', 'envira-gallery-lite' ) ] );
	}

	$download_url = esc_url_raw( $valid_key->download_url );

	if ( ! envira_gallery_is_valid_plugin_download_url( $download_url ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'Invalid plugin URL. Must be an HTTPS .zip address.', 'envira-gallery-lite' ) ] );
	}

	// Start output bufferring to catch the filesystem form if credentials are needed.
	ob_start();

	$method = '';
	$url    = esc_url( admin_url( 'edit.php?post_type=envira&page=envira-gallery-lite-about-us' ) );

	$creds = request_filesystem_credentials( $url, $method, false, false, null );
	if ( false === $creds ) {
		$form = ob_get_clean();
		echo wp_json_encode( [ 'form' => $form ] );
		die;
	}
	// If we are not authenticated, make it happen now.
	if ( ! WP_Filesystem( $creds ) ) {
		ob_start();
		request_filesystem_credentials( $url, $method, true, false, null );
		$form = ob_get_clean();
		echo wp_json_encode( [ 'form' => $form ] );
		die;
	}
	// We do not need any extra credentials if we have gotten this far, so let's install the plugin.
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	require_once plugin_dir_path( ENVIRA_LITE_FILE ) . 'includes/global/Installer_Skin.php';

	// Create the plugin upgrader with our custom skin.
	$skin      = new Envira_Lite_Installer_Skin();
	$installer = new Plugin_Upgrader( $skin );
	$installer->install( $download_url );

	// Flush the cache and return the newly installed plugin basename.
	wp_cache_flush();

	if ( $installer->plugin_info() ) {

		$plugin_basename = $installer->plugin_info();

		$active = activate_plugin( $plugin_basename, false, false, true );

		// Deactivate Lite.
		$plugin = plugin_basename( ENVIRA_LITE_FILE );

		deactivate_plugins( $plugin );

		do_action( 'envira_lite_plugin_deactivated', $plugin );

		wp_send_json_success(
			[
				'message' => esc_html__( 'Congratulations! This site is now receiving automatic updates.', 'envira-gallery-lite' ),
				'reload'  => true,
			]
		);
	}
}


/** Function envira_gallery_ajax_change_type() called by wp_ajax hooks: {'envira_gallery_change_type'} **/
/** Parameters found in function envira_gallery_ajax_change_type(): {"post": ["post_id", "type"]} **/
function envira_gallery_ajax_change_type() {
	// Run a security check first.
	check_ajax_referer( 'envira-gallery-change-type', 'nonce' );

	// Prepare variables.
	$post_id = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}
	$post = get_post( $post_id );
	// Sanitize at ingestion; strips tags and control characters.
	$type = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : null;

	// Validate against registered types; prevents arbitrary hook names and reflected values.
	$instance = Envira_Gallery_Metaboxes::get_instance();
	// Keys are the type slugs ('default', addon slugs, etc.).
	$valid_types = array_keys( $instance->get_envira_types( $post ) );
	// Strict comparison; prevents type-juggling bypass.
	if ( ! in_array( $type, $valid_types, true ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'Invalid gallery type.', 'envira-gallery-lite' ) ] );
	}

	// Retrieve the data for the type selected.
	ob_start();
	$instance = Envira_Gallery_Metaboxes::get_instance();
	$instance->images_display( $type, $post );
	$html = ob_get_clean();

	// Send back the response.
	echo wp_json_encode(
		[
			'type' => $type,
			'html' => $html,
		]
	);
	die;
}


/** Function save_onboarding_data() called by wp_ajax hooks: {'save_onboarding_data'} **/
/** Parameters found in function save_onboarding_data(): {"post": ["nonce", "eow"]} **/
function save_onboarding_data() {

		// Verify nonce — do NOT sanitize before wp_verify_nonce; sanitize_text_field() can corrupt the hash and cause false failures.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['nonce'] ), 'enviraOnboardingCheck' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- nonces must not be sanitized before wp_verify_nonce.
			wp_send_json_error( 'Invalid nonce' );
			wp_die();
		}

		// check if the current user can manage options.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'You do not have permission to save data' );
			wp_die();
		}

		if ( ! empty( $_POST['eow'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce verified via wp_verify_nonce() above; wp_die() is called on failure so execution never reaches here without a valid nonce.
			// Sanitize data and merge to existing data.
			$onboarding_data = get_option( 'envira_onboarding_data', [] );

			// Capture the previous user type before it is overwritten.
			$previous_user_type = isset( $onboarding_data['_user_type'] ) ? $onboarding_data['_user_type'] : '';

			$onboarding_data = $this->sanitize_and_assign( '_usage_tracking', 'sanitize_text_field', $onboarding_data );
			$onboarding_data = $this->sanitize_and_assign( '_email_address', 'sanitize_email', $onboarding_data );
			$onboarding_data = $this->sanitize_and_assign( '_email_opt_in', 'sanitize_text_field', $onboarding_data );
			$onboarding_data = $this->sanitize_and_assign( '_user_type', 'sanitize_text_field', $onboarding_data );

			$updated = update_option( 'envira_onboarding_data', $onboarding_data );

			if ( $updated ) {
				// Send data to Drip.
				$this->save_to_drip( $onboarding_data, $previous_user_type );
			}

			wp_send_json_success( 'Data saved successfully' );
			wp_die();
		}

		wp_send_json_error( 'Something went wrong. Please try again.' );
		wp_die();
	}


/** Function envira_gallery_move_media() called by wp_ajax hooks: {'envira_gallery_move_media'} **/
/** Parameters found in function envira_gallery_move_media(): {"post": ["from_gallery_id", "to_gallery_id", "image_ids"]} **/
function envira_gallery_move_media() {

	// Check nonce.
	check_admin_referer( 'envira-gallery-move-media', 'nonce' );

	// Get the Envira Gallery ID.
	// absint() always returns an integer; default to 0 and test for falsiness (null check was never true).
	$from_gallery_id = isset( $_POST['from_gallery_id'] ) ? absint( wp_unslash( $_POST['from_gallery_id'] ) ) : 0;

	if ( ! $from_gallery_id ) {
		wp_send_json_error( [ 'message' => esc_html__( 'Invalid From Gallery ID.', 'envira-gallery-lite' ) ] );
	}

	if ( ! current_user_can( 'edit_post', $from_gallery_id ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}

	// Get POSTed fields.
	$to_gallery_id = isset( $_POST['to_gallery_id'] ) ? absint( $_POST['to_gallery_id'] ) : null;
	$image_ids       = isset($_POST['image_ids']) ? wp_unslash($_POST['image_ids']) : array(); // @codingStandardsIgnoreLine

	if ( ! $from_gallery_id ) {
		wp_send_json_error( __( 'The From Gallery ID has not been specified.', 'envira-gallery-lite' ) );
	}
	if ( ! $to_gallery_id ) {
		wp_send_json_error( __( 'The To Gallery ID has not been specified.', 'envira-gallery-lite' ) );
	}

	if ( ! current_user_can( 'edit_post', $to_gallery_id ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}

	// Guard against a non-array POST value; would cause TypeError in PHP 8.
	$raw_ids = isset( $_POST['image_ids'] ) ? wp_unslash( $_POST['image_ids'] ) : []; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized below via absint().
	if ( ! is_array( $raw_ids ) || empty( $raw_ids ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'No images were selected to be moved between Galleries.', 'envira-gallery-lite' ) ] );
	}

	// Cast each ID to a non-negative integer and discard zeros; array_values re-indexes after filter.
	$image_ids = array_values( array_filter( array_map( 'absint', $raw_ids ) ) );
	if ( empty( $image_ids ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'No valid image IDs provided.', 'envira-gallery-lite' ) ] );
	}

	// Get from and to Galleries.
	$from_gallery = Envira_Gallery::get_instance()->get_gallery( $from_gallery_id );
	$to_gallery   = Envira_Gallery::get_instance()->get_gallery( $to_gallery_id );

	// get_gallery() returns false when a post has no _eg_gallery_data meta; guard before use.
	if ( ! is_array( $from_gallery ) || ! isset( $from_gallery['gallery'] ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'Source gallery data not found.', 'envira-gallery-lite' ) ] );
	}
	if ( ! is_array( $to_gallery ) || ! isset( $to_gallery['gallery'] ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'Destination gallery data not found.', 'envira-gallery-lite' ) ] );
	}

	// Move each image from the source gallery to the destination gallery.
	foreach ( $image_ids as $image_id ) {
		// Use array_key_exists rather than isset so null-valued entries still count as present.
		if ( ! array_key_exists( $image_id, $from_gallery['gallery'] ) ) {
			continue;
		}

		// Copy the image to $to_gallery.
		$to_gallery['gallery'][ $image_id ] = $from_gallery['gallery'][ $image_id ];

		// Remove the image from $from_gallery.
		unset( $from_gallery['gallery'][ $image_id ] );
	}

	// Save both Galleries.
	update_post_meta( $from_gallery_id, '_eg_gallery_data', $from_gallery );
	update_post_meta( $to_gallery_id, '_eg_gallery_data', $to_gallery );

	// Return success.
	wp_send_json_success();
}


/** Function envira_hide_admin_menu_tooltip_callback() called by wp_ajax hooks: {'envira_hide_admin_menu_tooltip'} **/
/** No params detected :-/ **/


/** Function envira_gallery_ajax_set_user_setting() called by wp_ajax hooks: {'envira_gallery_set_user_setting'} **/
/** Parameters found in function envira_gallery_ajax_set_user_setting(): {"post": ["name", "value"]} **/
function envira_gallery_ajax_set_user_setting() {

	// Run a security check first.
	check_ajax_referer( 'envira-gallery-set-user-setting', 'nonce' );

	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}

	// Allowlist map: only known settings and their permitted values are accepted.
	// Prevents arbitrary key/value injection into user meta via set_user_setting().
	$allowed_settings = [
		// Only setting used by this plugin.
		'envira_gallery_image_view' => [ 'grid', 'list' ],
	];

	// Prepare variables.
	// Sanitize key at ingestion.
	$name = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	// Sanitize value at ingestion.
	$value = isset( $_POST['value'] ) ? sanitize_text_field( wp_unslash( $_POST['value'] ) ) : '';

	// Reject unknown keys or invalid values — strict comparison prevents type-juggling bypass.
	if ( ! array_key_exists( $name, $allowed_settings ) || ! in_array( $value, $allowed_settings[ $name ], true ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'Invalid setting name or value.', 'envira-gallery-lite' ) ] );
	}

	// Set user setting.
	set_user_setting( $name, $value );

	// Send back the response.
	wp_send_json_success();
	die();
}


/** Function envira_gallery_ajax_activate_addon() called by wp_ajax hooks: {'envira_gallery_activate_addon'} **/
/** Parameters found in function envira_gallery_ajax_activate_addon(): {"post": ["plugin"]} **/
function envira_gallery_ajax_activate_addon() {
	// Run a security check first.
	check_admin_referer( 'envira-gallery-activate', 'nonce' );

	if ( ! current_user_can( 'activate_plugins' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to activate plugins.', 'envira-gallery-lite' ) ] );
	}

	// Activate the addon.
	if ( isset( $_POST['plugin'] ) ) {
		// Sanitize at ingestion; strips tags and control characters.
		$plugin = sanitize_text_field( wp_unslash( $_POST['plugin'] ) );

		// Reject path traversal sequences (e.g. "../") to prevent escaping the plugins directory.
		if ( 0 !== validate_file( $plugin ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid plugin path.', 'envira-gallery-lite' ) ] );
		}

		// Restrict to the Envira addon namespace; prevents activating arbitrary installed plugins.
		if ( 0 !== strpos( $plugin, 'envira-' ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Not an Envira addon.', 'envira-gallery-lite' ) ] );
		}

		// Confirm the plugin is actually installed; prevents targeting guessed-but-absent basenames.
		if ( ! array_key_exists( $plugin, get_plugins() ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Plugin not found.', 'envira-gallery-lite' ) ] );
		}

		// Reject any basename not present in the allowed addon list BEFORE activating to prevent
		// a non-permitted plugin from being silently activated before the check fires.
		if ( ! in_array( $plugin, envira_gallery_get_allowed_addon_plugins(), true ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Plugin not permitted.', 'envira-gallery-lite' ) ] );
		}

		$activate = activate_plugin( $plugin );

		if ( is_wp_error( $activate ) ) {
			echo wp_json_encode( [ 'error' => $activate->get_error_message() ] );
			die;
		}
	}

	echo wp_json_encode( true );
	die;
}


/** Function save_selected_addons() called by wp_ajax hooks: {'save_selected_addons'} **/
/** Parameters found in function save_selected_addons(): {"post": ["nonce", "addons"]} **/
function save_selected_addons() {
		// Verify nonce — do NOT sanitize before wp_verify_nonce; sanitize_text_field() can corrupt the hash and cause false failures.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['nonce'] ), 'enviraOnboardingCheck' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- nonces must not be sanitized before wp_verify_nonce.
			wp_send_json_error( 'Invalid nonce' );
			wp_die();
		}

		// check if the current user can manage options.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'You do not have permission to save data' );
			wp_die();
		}

		if ( ! empty( $_POST['addons'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce verified via wp_verify_nonce() above; wp_die() is called on failure so execution never reaches here without a valid nonce.

			$addons = explode( ',', sanitize_text_field( wp_unslash( $_POST['addons'] ) ) );

			// Sanitize data and merge to existing data.
			$onboarding_data = get_option( 'envira_onboarding_data' );
			if ( empty( $onboarding_data ) ) {
				$onboarding_data = [];
			}

			// Check if $addons has albums addon, then add tags addon too.
			if ( in_array( 'envira-albums', $addons, true ) ) {
				$addons[] = 'envira-tags';
			}

			// Save addons as _addons key.
			$onboarding_data['_addons'] = $addons;

			$updated = update_option( 'envira_onboarding_data', $onboarding_data );

			wp_send_json_success( 'Addons saved successfully' );
			wp_die();
		}

		wp_send_json_error( 'Something went wrong. Please try again.' );
		wp_die();
	}


/** Function envira_gallery_ajax_dismiss_topbar() called by wp_ajax hooks: {'envira_gallery_ajax_dismiss_topbar'} **/
/** No params detected :-/ **/


/** Function dismiss() called by wp_ajax hooks: {'envira_notification_dismiss'} **/
/** No params detected :-/ **/


/** Function envira_gallery_ajax_remove_images() called by wp_ajax hooks: {'envira_gallery_remove_images'} **/
/** Parameters found in function envira_gallery_ajax_remove_images(): {"post": ["post_id", "attachment_ids"]} **/
function envira_gallery_ajax_remove_images() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-remove-image', 'nonce' );

	// Get the Envira Gallery ID.
	$post_id = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;

	if ( null === $post_id ) {
		wp_send_json_error( [ 'message' => esc_html__( 'Invalid Post ID.', 'envira-gallery-lite' ) ] );
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}

	// Prepare variables.
	$attach_ids   = isset( $_POST['attachment_ids'] ) ? array_map( 'absint', wp_unslash( (array) $_POST['attachment_ids'] ) ) : [];
	$gallery_data = get_post_meta( $post_id, '_eg_gallery_data', true );
	$in_gallery   = get_post_meta( $post_id, '_eg_in_gallery', true );

	foreach ( (array) $attach_ids as $attach_id ) {
		$has_gallery = get_post_meta( $attach_id, '_eg_has_gallery', true );

		// Unset the image from the gallery, in_gallery and has_gallery checkers.
		unset( $gallery_data['gallery'][ $attach_id ] );

		$key = array_search( $attach_id, (array) $in_gallery, true );
		if ( false !== $key ) {
			unset( $in_gallery[ $key ] );
		}

		$key = array_search( $post_id, (array) $has_gallery, true );
		if ( false !== $key ) {
			unset( $has_gallery[ $key ] );
		}

		// Update the attachment data.
		update_post_meta( $attach_id, '_eg_has_gallery', $has_gallery );
	}

	// Update the gallery data.
	update_post_meta( $post_id, '_eg_gallery_data', $gallery_data );
	update_post_meta( $post_id, '_eg_in_gallery', $in_gallery );

	// Run hook before finishing the reponse.
	do_action( 'envira_gallery_ajax_remove_images', $post_id );

	// Flush the gallery cache.
	Envira_Gallery_Common::get_instance()->flush_gallery_caches( $post_id );

	echo wp_json_encode( true );
	die;
}


/** Function envira_activate_partner() called by wp_ajax hooks: {'envira_activate_partner'} **/
/** Parameters found in function envira_activate_partner(): {"post": ["basename"]} **/
function envira_activate_partner() {
	// Run a security check first.
	check_admin_referer( 'envira-gallery-activate-partner', 'nonce' );

	if ( ! current_user_can( 'activate_plugins' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to activate plugins.', 'envira-gallery-lite' ) ] );
	}

	// Activate the addon.
	if ( isset( $_POST['basename'] ) ) {
		// Sanitize; strips tags and control characters.
		$plugin = sanitize_text_field( wp_unslash( $_POST['basename'] ) );

		// Reject path traversal (e.g. "../") and absolute paths.
		if ( 0 !== validate_file( $plugin ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid plugin path.', 'envira-gallery-lite' ) ] );
		}

		// Restrict to the Envira namespace; prevents targeting arbitrary plugins.
		if ( 0 !== strpos( $plugin, 'envira-' ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Not an Envira addon.', 'envira-gallery-lite' ) ] );
		}

		// Confirm the plugin is installed before attempting activation.
		if ( ! array_key_exists( $plugin, get_plugins() ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Plugin not found.', 'envira-gallery-lite' ) ] );
		}

		$activate = activate_plugin( $plugin );

		if ( is_wp_error( $activate ) ) {
			echo wp_json_encode( [ 'error' => $activate->get_error_message() ] );
			die;
		}
	}

	echo wp_json_encode( true );
	die;
}


/** Function envira_gallery_ajax_load_image() called by wp_ajax hooks: {'envira_gallery_load_image'} **/
/** Parameters found in function envira_gallery_ajax_load_image(): {"post": ["id", "post_id"]} **/
function envira_gallery_ajax_load_image() {

	// Run a security check first.
	check_ajax_referer( 'envira-gallery-load-image', 'nonce' );

	// Prepare variables.
	$id      = isset( $_POST['id'] ) ? absint( wp_unslash( $_POST['id'] ) ) : null;
	$post_id = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}

	$attachment = get_post( $id );
	if ( ! $attachment || ( 'attachment' !== $attachment->post_type ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'Invalid attachment.', 'envira-gallery-lite' ) ] );
	}
	if ( (int) $attachment->post_author !== get_current_user_id() && ! current_user_can( 'edit_others_posts' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You do not have permission to use this attachment.', 'envira-gallery-lite' ) ] );
	}

	// Set post meta to show that this image is attached to one or more Envira galleries.
	$has_gallery = get_post_meta( $id, '_eg_has_gallery', true );
	if ( empty( $has_gallery ) ) {
		$has_gallery = [];
	}

	$has_gallery[] = $post_id;
	update_post_meta( $id, '_eg_has_gallery', $has_gallery );

	// Set post meta to show that this image is attached to a gallery on this page.
	$in_gallery = get_post_meta( $post_id, '_eg_in_gallery', true );
	if ( empty( $in_gallery ) ) {
		$in_gallery = [];
	}

	$in_gallery[] = $id;
	update_post_meta( $post_id, '_eg_in_gallery', $in_gallery );

	// Set data and order of image in gallery.
	$gallery_data = get_post_meta( $post_id, '_eg_gallery_data', true );
	if ( empty( $gallery_data ) ) {
		$gallery_data = [];
	}

	// If no gallery ID has been set, set it now.
	if ( empty( $gallery_data['id'] ) ) {
		$gallery_data['id'] = $post_id;
	}

	// Set data and update the meta information.
	$gallery_data = envira_gallery_ajax_prepare_gallery_data( $gallery_data, $id );
	update_post_meta( $post_id, '_eg_gallery_data', $gallery_data );

	// Run hook before building out the item.
	do_action( 'envira_gallery_ajax_load_image', $id, $post_id );

	// Build out the individual HTML output for the gallery image that has just been uploaded.
	$html = Envira_Gallery_Metaboxes::get_instance()->get_gallery_item( $id, $gallery_data['gallery'][ $id ], $post_id );

	// Allow addons to filter the HTML output.
	$html = apply_filters( 'envira_gallery_ajax_get_gallery_item_html', $html, $gallery_data, $id, $post_id );

	// Flush the gallery cache.
	Envira_Gallery_Common::get_instance()->flush_gallery_caches( $post_id );

	echo wp_json_encode( $html );
	die;
}


/** Function envira_gallery_ajax_deactivate_addon() called by wp_ajax hooks: {'envira_gallery_deactivate_addon'} **/
/** Parameters found in function envira_gallery_ajax_deactivate_addon(): {"post": ["plugin"]} **/
function envira_gallery_ajax_deactivate_addon() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-deactivate', 'nonce' );

	if ( ! current_user_can( 'activate_plugins' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to deactivate plugins.', 'envira-gallery-lite' ) ] );
	}

	// Deactivate the addon.
	if ( isset( $_POST['plugin'] ) ) {
		// Sanitize at ingestion; strips tags and control characters.
		$plugin = sanitize_text_field( wp_unslash( $_POST['plugin'] ) );

		// Reject path traversal sequences (e.g. "../") to prevent escaping the plugins directory.
		if ( 0 !== validate_file( $plugin ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid plugin path.', 'envira-gallery-lite' ) ] );
		}

		// Restrict to the Envira addon namespace; prevents deactivating arbitrary installed plugins.
		if ( 0 !== strpos( $plugin, 'envira-' ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Not an Envira addon.', 'envira-gallery-lite' ) ] );
		}

		// Confirm the plugin is currently active; prevents operating on guessed-but-absent basenames.
		if ( ! is_plugin_active( $plugin ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Plugin not active.', 'envira-gallery-lite' ) ] );
		}

		deactivate_plugins( $plugin );
	}

	echo wp_json_encode( true );
	die;
}


/** Function envira_redirect_to_add_new_gallery_callback() called by wp_ajax hooks: {'envira_redirect_to_add_new_gallery'} **/
/** No params detected :-/ **/


/** Function envira_gallery_editor_get_galleries() called by wp_ajax hooks: {'envira_gallery_editor_get_galleries'} **/
/** Parameters found in function envira_gallery_editor_get_galleries(): {"post": ["search", "search_terms", "prepend_ids"]} **/
function envira_gallery_editor_get_galleries() {

	// Check nonce.
	check_admin_referer( 'envira-gallery-editor-get-galleries', 'nonce' );

	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}

	// Get POSTed fields.
	$search       = isset($_POST['search']) ? (bool) wp_unslash($_POST['search']) : false; // @codingStandardsIgnoreLine
	$search_terms = isset($_POST['search_terms']) ? sanitize_text_field(wp_unslash($_POST['search_terms'])) : ''; // @codingStandardsIgnoreLine
	$prepend_ids  = isset($_POST['prepend_ids']) ? stripslashes_deep(wp_unslash($_POST['prepend_ids'])) : array(); // @codingStandardsIgnoreLine
	$results      = [];

	// Get galleries.
	$instance  = Envira_Gallery_Lite::get_instance();
	$galleries = $instance->get_galleries( false, true, ( $search ? $search_terms : '' ) );

	// Build array of just the data we need.
	foreach ( (array) $galleries as $gallery ) {
		// Get the thumbnail of the first image.
		if ( isset( $gallery['gallery'] ) && ! empty( $gallery['gallery'] ) ) {
			// Get the first image.
			reset( $gallery['gallery'] );
			$key       = key( $gallery['gallery'] );
			$thumbnail = wp_get_attachment_image_src( $key, 'thumbnail' );
		}

		if ( ! empty( $gallery['config']['title'] ) ) {
			$gallery_title = wp_specialchars_decode( $gallery['config']['title'], ENT_QUOTES );
		} else {
			$gallery_title = false;
		}

		// Check to make sure variables are there.
		$gallery_id          = false;
		$gallery_config_slug = false;

		if ( isset( $gallery['id'] ) ) {
			$gallery_id = $gallery['id'];
		}
		if ( isset( $gallery['config']['slug'] ) ) {
			$gallery_config_slug = $gallery['config']['slug'];
		}

		// Add gallery to results.
		$results[] = [
			'id'        => $gallery_id,
			'slug'      => $gallery_config_slug,
			'title'     => $gallery_title,
			'thumbnail' => ( ( isset( $thumbnail ) && is_array( $thumbnail ) ) ? $thumbnail[0] : '' ),
			'action'    => 'gallery', // Tells the editor modal whether this is a Gallery or Album for the shortcode output.
		];
	}

	// If any prepended Gallery IDs were specified, get them now.
	// These will typically be a Defaults Gallery, which wouldn't be included in the above get_galleries() call.
	if ( is_array( $prepend_ids ) && count( $prepend_ids ) > 0 ) {
		$prepend_results = [];

		// Get each Gallery.
		foreach ( $prepend_ids as $gallery_id ) {
			// Get gallery.
			$gallery = get_post_meta( $gallery_id, '_eg_gallery_data', true );

			// Get gallery first image.
			if ( isset( $gallery['gallery'] ) && ! empty( $gallery['gallery'] ) ) {
				// Get the first image.
				reset( $gallery['gallery'] );
				$key       = key( $gallery['gallery'] );
				$thumbnail = wp_get_attachment_image_src( $key, 'thumbnail' );
			}

			// Add gallery to results.
			$prepend_results[] = [
				'id'        => $gallery['id'],
				'slug'      => $gallery['config']['slug'],
				'title'     => wp_specialchars_decode( $gallery['config']['title'], ENT_QUOTES ),
				'thumbnail' => ( ( isset( $thumbnail ) && is_array( $thumbnail ) ) ? $thumbnail[0] : '' ),
				'action'    => 'gallery', // Tells the editor modal whether this is a Gallery or Album for the shortcode output.
			];
		}

		// Add to results.
		if ( is_array( $prepend_results ) && count( $prepend_results ) > 0 ) {
			$results = array_merge( $prepend_results, $results );
		}
	}

	// Return galleries.
	wp_send_json_success( $results );
}


/** Function envira_gallery_ajax_insert_images() called by wp_ajax hooks: {'envira_gallery_insert_images'} **/
/** Parameters found in function envira_gallery_ajax_insert_images(): {"post": ["post_id", "images"]} **/
function envira_gallery_ajax_insert_images() {

	// Run a security check first.
	check_ajax_referer( 'envira-gallery-insert-images', 'nonce' );

	// Get the Envira Gallery ID.
	$post_id = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;

	if ( null === $post_id ) {
		wp_send_json_error( [ 'message' => esc_html__( 'Invalid Post ID.', 'envira-gallery-lite' ) ] );
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}

	// Prepare variables.
	$images = [];

	if ( isset( $_POST['images'] ) ) {
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Raw JSON blob is decoded, then every field inside the loop below is individually sanitized (absint/esc_url_raw/sanitize_text_field/wp_kses_post) before use.
		$images = json_decode( wp_unslash( $_POST['images'] ), true );
		if ( ! is_array( $images ) ) {
			$images = [];
		}
		foreach ( $images as $i => $image ) {
			$images[ $i ] = [
				'id'      => isset( $image['id'] ) ? absint( $image['id'] ) : 0,
				'src'     => isset( $image['src'] ) ? esc_url_raw( $image['src'] ) : '',
				'title'   => isset( $image['title'] ) ? sanitize_text_field( $image['title'] ) : '',
				'alt'     => isset( $image['alt'] ) ? sanitize_text_field( $image['alt'] ) : '',
				'caption' => isset( $image['caption'] ) ? wp_kses_post( $image['caption'] ) : '',
				'link'    => isset( $image['link'] ) ? esc_url_raw( $image['link'] ) : '',
				'url'     => isset( $image['url'] ) ? esc_url_raw( $image['url'] ) : '',
			];
		}
	}

	// Grab and update any gallery data if necessary.
	$in_gallery = get_post_meta( $post_id, '_eg_in_gallery', true );
	if ( empty( $in_gallery ) ) {
		$in_gallery = [];
	}

	// Set data and order of image in gallery.
	$gallery_data = get_post_meta( $post_id, '_eg_gallery_data', true );
	if ( empty( $gallery_data ) ) {
		$gallery_data = [];
	}

	// If no gallery ID has been set, set it now.
	if ( empty( $gallery_data['id'] ) ) {
		$gallery_data['id'] = $post_id;
	}

	// Loop through the images and add them to the gallery.
	foreach ( (array) $images as $i => $image ) {

		// If the image is already in the gallery, lets skip it since we don't want to override the image metadata settings.
		if ( in_array( $image['id'], $in_gallery, true ) ) {
			continue;
		}

		$att = get_post( $image['id'] );
		if (
			! $att || 'attachment' !== $att->post_type
			|| ( (int) $att->post_author !== get_current_user_id() && ! current_user_can( 'edit_others_posts' ) )
		) {
			continue;
		}

		// Update the attachment image post meta first.
		$has_gallery = get_post_meta( $image['id'], '_eg_has_gallery', true );
		if ( empty( $has_gallery ) ) {
			$has_gallery = [];
		}

		$has_gallery[] = $post_id;
		update_post_meta( $image['id'], '_eg_has_gallery', $has_gallery );

		// Now add the image to the gallery for this particular post.
		$in_gallery[] = $image['id'];
		$gallery_data = envira_gallery_ajax_prepare_gallery_data( $gallery_data, $image['id'], $image );
	}

	// Update the gallery data.
	update_post_meta( $post_id, '_eg_in_gallery', $in_gallery );
	update_post_meta( $post_id, '_eg_gallery_data', $gallery_data );

	// Run hook before finishing.
	do_action( 'envira_gallery_ajax_insert_images', $images, $post_id );

	// Flush the gallery cache.
	Envira_Gallery_Common::get_instance()->flush_gallery_caches( $post_id );

	// Return a HTML string comprising of all gallery images, so the UI can be updated.
	$html = '';
	foreach ( (array) $gallery_data['gallery'] as $id => $data ) {
		$html .= Envira_Gallery_Metaboxes::get_instance()->get_gallery_item( $id, $data, $post_id );
	}

	// Output JSON and exit.
	echo wp_json_encode( [ 'success' => $html ] );
	die;
}


/** Function dismiss_review() called by wp_ajax hooks: {'envira_dismiss_review'} **/
/** No params detected :-/ **/


/** Function envira_gallery_ajax_save_meta() called by wp_ajax hooks: {'envira_gallery_save_meta'} **/
/** Parameters found in function envira_gallery_ajax_save_meta(): {"post": ["post_id", "attach_id", "meta"]} **/
function envira_gallery_ajax_save_meta() {
	// Run a security check first.
	check_ajax_referer( 'envira-gallery-save-meta', 'nonce' );

	// Get the Envira Gallery ID.
	$post_id = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;

	if ( null === $post_id ) {
		wp_send_json_error( [ 'message' => esc_html__( 'Invalid Post ID.', 'envira-gallery-lite' ) ] );
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}

	// Prepare variables.
	$attach_id    = isset( $_POST['attach_id'] ) ? absint( wp_unslash( $_POST['attach_id'] ) ) : null;
	$raw_meta     = isset( $_POST['meta'] ) ? wp_unslash( (array) $_POST['meta'] ) : []; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Array is allowlisted to known keys below, then each value is individually sanitized (wp_kses/sanitize_text_field/esc_url_raw/wp_kses_post) before save.
	$meta         = array_intersect_key( $raw_meta, array_flip( [ 'src', 'title', 'alt', 'caption', 'link', 'link_new_window' ] ) );
	$gallery_data = get_post_meta( $post_id, '_eg_gallery_data', true );

	// Prevent invalid data from being saved.
	if ( ! $post_id || ! $attach_id || empty( $meta['src'] ) ) {
		wp_send_json_error( 'Unable to save' );
	}

	$wp_kses_allowed_html = [
		'a'      => [
			'href'                => [],
			'target'              => [],
			'class'               => [],
			'title'               => [],
			'data-status'         => [],
			'data-envira-tooltip' => [],
			'data-id'             => [],
		],
		'br'     => [],
		'img'    => [
			'src'   => [],
			'class' => [],
			'alt'   => [],
		],
		'div'    => [
			'class' => [],
		],
		'li'     => [
			'id'                              => [],
			'class'                           => [],
			'data-envira-gallery-image'       => [],
			'data-envira-gallery-image-model' => [],
		],
		'em'     => [],
		'span'   => [
			'class' => [],
		],
		'strong' => [],
	];

	if ( isset( $meta['title'] ) ) {
		$gallery_data['gallery'][ $attach_id ]['title'] = trim( wp_kses( $meta['title'], $wp_kses_allowed_html ) );
	}

	if ( isset( $meta['alt'] ) ) {
		// sanitize_text_field() strips HTML tags without encoding; esc_attr() at render handles encoding.
		$gallery_data['gallery'][ $attach_id ]['alt'] = sanitize_text_field( trim( $meta['alt'] ) );
	}

	if ( isset( $meta['link'] ) ) {
		// Use esc_url_raw() (no HTML-encoding) so esc_url() at render doesn't double-encode &amp;.
		$gallery_data['gallery'][ $attach_id ]['link'] = esc_url_raw( $meta['link'] );
	}

	if ( isset( $meta['link_new_window'] ) ) {
		$gallery_data['gallery'][ $attach_id ]['link_new_window'] = in_array( (string) $meta['link_new_window'], [ '0', '1' ], true ) ? $meta['link_new_window'] : '0';
	}

	if ( isset( $meta['caption'] ) ) {
		$gallery_data['gallery'][ $attach_id ]['caption'] = wp_kses_post( $meta['caption'] );
	}

	// Allow filtering of meta before saving.
	$gallery_data = apply_filters( 'envira_gallery_ajax_save_meta', $gallery_data, $meta, $attach_id, $post_id );

	// Update the gallery data.
	update_post_meta( $post_id, '_eg_gallery_data', $gallery_data );

	// Flush the gallery cache.
	Envira_Gallery_Common::get_instance()->flush_gallery_caches( $post_id );

	// Done.
	wp_send_json_success();
	die;
}


/** Function envira_deactivate_partner() called by wp_ajax hooks: {'envira_deactivate_partner'} **/
/** Parameters found in function envira_deactivate_partner(): {"post": ["basename"]} **/
function envira_deactivate_partner() {
	// Run a security check first.
	check_admin_referer( 'envira-gallery-deactivate-partner', 'nonce' );

	if ( ! current_user_can( 'activate_plugins' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to deactivate plugins.', 'envira-gallery-lite' ) ] );
	}

	// Deactivate the addon.
	if ( isset( $_POST['basename'] ) ) {
		// Sanitize; was wp_unslash() only before — no sanitization at all.
		$plugin = sanitize_text_field( wp_unslash( $_POST['basename'] ) );

		// Reject path traversal and absolute paths.
		if ( 0 !== validate_file( $plugin ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid plugin path.', 'envira-gallery-lite' ) ] );
		}

		// Restrict to the Envira namespace; prevents deactivating arbitrary plugins.
		if ( 0 !== strpos( $plugin, 'envira-' ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Not an Envira addon.', 'envira-gallery-lite' ) ] );
		}

		// Confirm the plugin is currently active before deactivating.
		if ( ! is_plugin_active( $plugin ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Plugin not active.', 'envira-gallery-lite' ) ] );
		}

		deactivate_plugins( $plugin );
	}

	echo wp_json_encode( true );
	die;
}


/** Function install_recommended_plugins() called by wp_ajax hooks: {'install_recommended_plugins'} **/
/** Parameters found in function install_recommended_plugins(): {"post": ["nonce", "plugins"]} **/
function install_recommended_plugins() {
		// Verify nonce — do NOT sanitize before wp_verify_nonce; sanitize_text_field() can corrupt the hash and cause false failures.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['nonce'] ), 'enviraOnboardingCheck' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- nonces must not be sanitized before wp_verify_nonce.
			wp_send_json_error( 'Invalid nonce' );
			wp_die();
		}

		// check if the current user can manage options.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'You do not have permission to install plugins' );
			wp_die();
		}

		if ( ! empty( $_POST['plugins'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce verified via wp_verify_nonce() above; wp_die() is called on failure so execution never reaches here without a valid nonce.
			// Sanitize data, plugins is a string delimited by comma.

			$plugins = explode( ',', sanitize_text_field( wp_unslash( $_POST['plugins'] ) ) );
			// Install the plugins.
			foreach ( $plugins as $plugin ) {
				if ( '' !== $this->is_recommended_plugin_installed( $plugin ) ) {
					continue; // Skip the plugin if it is already installed.
				}
				// Generate the plugin URL by slug.
				$url = 'https://downloads.wordpress.org/plugin/' . $plugin . '.zip';
				$this->install_helper( $url );
			}
			wp_send_json_success( 'Installed the recommended plugins successfully.' );
			wp_die();
		}

		wp_send_json_error( 'Something went wrong. Please try again.' );
		wp_die();
	}


/** Function envira_install_partner() called by wp_ajax hooks: {'envira_install_partner'} **/
/** Parameters found in function envira_install_partner(): {"post": ["download_url"]} **/
function envira_install_partner() {

	check_admin_referer( 'envira-gallery-install-partner', 'nonce' );

	if ( ! current_user_can( 'install_plugins' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to install plugins.', 'envira-gallery-lite' ) ] );
	}

	// Install the addon.
	if ( isset( $_POST['download_url'] ) ) {

		$download_url = esc_url_raw( wp_unslash( $_POST['download_url'] ) ); // Sanitize URL at ingestion.

		// Validate URL structure (HTTPS + .zip). No domain allowlist: the caller
		// already holds install_plugins (admin-level) and can install from any host
		// via native WP UI. Internal-IP SSRF is blocked by WP_HTTP::block_request().
		if ( ! envira_gallery_is_valid_plugin_download_url( $download_url ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid plugin URL. Must be an HTTPS .zip address.', 'envira-gallery-lite' ) ] );
		}

		global $hook_suffix;

		// Set the current screen to avoid undefined notices.
		set_current_screen();

		$method = '';
		$url    = esc_url( admin_url( 'edit.php?post_type=envira&page=envira-gallery-lite-about-us' ) );

		// Start output bufferring to catch the filesystem form if credentials are needed.
		ob_start();
		$creds = request_filesystem_credentials( $url, $method, false, false, null );
		if ( false === $creds ) {
			$form = ob_get_clean();
			echo wp_json_encode( [ 'form' => $form ] );
			die;
		}

		// If we are not authenticated, make it happen now.
		if ( ! WP_Filesystem( $creds ) ) {
			ob_start();
			request_filesystem_credentials( $url, $method, true, false, null );
			$form = ob_get_clean();
			echo wp_json_encode( [ 'form' => $form ] );
			die;
		}

		// We do not need any extra credentials if we have gotten this far, so let's install the plugin.
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once plugin_dir_path( ENVIRA_LITE_FILE ) . 'includes/global/Installer_Skin.php';

		// Create the plugin upgrader with our custom skin.
		$skin      = new Envira_Lite_Installer_Skin();
		$installer = new Plugin_Upgrader( $skin );
		$installer->install( $download_url );

		// Flush the cache and return the newly installed plugin basename.
		wp_cache_flush();

		if ( $installer->plugin_info() ) {
			$plugin_basename = $installer->plugin_info();

			$active = activate_plugin( $plugin_basename, false, false, true );

			wp_send_json_success( [ 'plugin' => $plugin_basename ] );

			die();
		}
	}

	// Send back a response.
	echo wp_json_encode( true );
	die;
}


/** Function envira_gallery_ajax_remove_image() called by wp_ajax hooks: {'envira_gallery_remove_image'} **/
/** Parameters found in function envira_gallery_ajax_remove_image(): {"post": ["post_id", "attachment_id"]} **/
function envira_gallery_ajax_remove_image() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-remove-image', 'nonce' );

	// Prepare variables.
	$post_id = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}
	$attach_id    = isset( $_POST['attachment_id'] ) ? absint( wp_unslash( $_POST['attachment_id'] ) ) : null;
	$gallery_data = get_post_meta( $post_id, '_eg_gallery_data', true );
	$in_gallery   = get_post_meta( $post_id, '_eg_in_gallery', true );
	$has_gallery  = get_post_meta( $attach_id, '_eg_has_gallery', true );

	// Unset the image from the gallery, in_gallery and has_gallery checkers.
	unset( $gallery_data['gallery'][ $attach_id ] );
	$key = array_search( $attach_id, (array) $in_gallery, true );
	if ( false !== $key ) {
		unset( $in_gallery[ $key ] );
	}
	$key = array_search( $post_id, (array) $has_gallery, true );

	if ( false !== $key ) {
		unset( $has_gallery[ $key ] );
	}

	// Update the gallery data.
	update_post_meta( $post_id, '_eg_gallery_data', $gallery_data );
	update_post_meta( $post_id, '_eg_in_gallery', $in_gallery );
	update_post_meta( $attach_id, '_eg_has_gallery', $has_gallery );

	// Run hook before finishing the reponse.
	do_action( 'envira_gallery_ajax_remove_image', $attach_id, $post_id );

	// Flush the gallery cache.
	Envira_Gallery_Common::get_instance()->flush_gallery_caches( $post_id );

	echo wp_json_encode( true );
	die;
}


/** Function envira_gallery_ajax_refresh() called by wp_ajax hooks: {'envira_gallery_refresh'} **/
/** Parameters found in function envira_gallery_ajax_refresh(): {"post": ["post_id"]} **/
function envira_gallery_ajax_refresh() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-refresh', 'nonce' );

	// Prepare variables.
	$post_id = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}
	$gallery = '';

	// Grab all gallery data.
	$gallery_data = get_post_meta( $post_id, '_eg_gallery_data', true );

	// If there are no gallery items, don't do anything.
	if ( empty( $gallery_data ) || empty( $gallery_data['gallery'] ) ) {
		echo wp_json_encode( [ 'error' => true ] );
		die;
	}

	// Loop through the data and build out the gallery view.
	foreach ( (array) $gallery_data['gallery'] as $id => $data ) {
		$gallery .= Envira_Gallery_Metaboxes::get_instance()->get_gallery_item( $id, $data, $post_id );
	}

	echo wp_json_encode( [ 'success' => $gallery ] );
	die;
}


/** Function envira_gallery_get_attachment_links() called by wp_ajax hooks: {'envira_gallery_get_attachment_links'} **/
/** Parameters found in function envira_gallery_get_attachment_links(): {"post": ["attachment_id"]} **/
function envira_gallery_get_attachment_links() {

	// Check nonce.
	check_ajax_referer( 'envira-gallery-save-meta', 'nonce' );

	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}

	// Get required inputs.
	$attachment_id = isset( $_POST['attachment_id'] ) ? absint( wp_unslash( $_POST['attachment_id'] ) ) : null;

	if ( ! $attachment_id ) {
		wp_send_json_error( [ 'message' => esc_html__( 'Invalid attachment ID.', 'envira-gallery-lite' ) ] );
	}

	// Verify the attachment exists and is a valid attachment.
	$attachment = get_post( $attachment_id );
	if ( ! $attachment || 'attachment' !== $attachment->post_type ) {
		wp_send_json_error( [ 'message' => esc_html__( 'Invalid attachment.', 'envira-gallery-lite' ) ] );
	}

	if ( ! current_user_can( 'upload_files' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to access attachments.', 'envira-gallery-lite' ) ] );
	}

	// Return the attachment's links.
	wp_send_json_success(
		[
			'media_link'      => wp_get_attachment_url( $attachment_id ),
			'attachment_page' => get_attachment_link( $attachment_id ),
		]
	);
}


/** Function envira_gallery_ajax_install_addon() called by wp_ajax hooks: {'envira_gallery_install_addon'} **/
/** Parameters found in function envira_gallery_ajax_install_addon(): {"post": ["plugin"]} **/
function envira_gallery_ajax_install_addon() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-install', 'nonce' );

	if ( ! current_user_can( 'install_plugins' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to install plugins.', 'envira-gallery-lite' ) ] );
	}

	// Install the addon.
	if ( isset( $_POST['plugin'] ) ) {
		$download_url = esc_url_raw( wp_unslash( $_POST['plugin'] ) ); // Sanitize URL at ingestion.

		// Validate URL structure (HTTPS + .zip). No domain allowlist: the caller
		// already holds install_plugins (admin-level) and can install from any host
		// via native WP UI. Internal-IP SSRF is blocked by WP_HTTP::block_request().
		if ( ! envira_gallery_is_valid_plugin_download_url( $download_url ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid plugin URL. Must be an HTTPS .zip address.', 'envira-gallery-lite' ) ] );
		}

		global $hook_suffix;

		// Set the current screen to avoid undefined notices.
		set_current_screen();

		$method = '';
		$url    = esc_url( admin_url( 'edit.php?post_type=envira&page=envira-gallery-lite-addons' ) );

		// Start output bufferring to catch the filesystem form if credentials are needed.
		ob_start();
		$creds = request_filesystem_credentials( $url, $method, false, false, null );
		if ( false === $creds ) {
			$form = ob_get_clean();
			echo wp_json_encode( [ 'form' => $form ] );
			die;
		}

		// If we are not authenticated, make it happen now.
		if ( ! WP_Filesystem( $creds ) ) {
			ob_start();
			request_filesystem_credentials( $url, $method, true, false, null );
			$form = ob_get_clean();
			echo wp_json_encode( [ 'form' => $form ] );
			die;
		}

		// We do not need any extra credentials if we have gotten this far, so let's install the plugin.
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once plugin_dir_path( Envira_Gallery::get_instance()->file ) . 'includes/admin/skin.php';

		// Create the plugin upgrader with our custom skin.
		$skin      = new Envira_Gallery_Skin();
		$installer = new Plugin_Upgrader( $skin );
		$installer->install( $download_url );

		// Flush the cache and return the newly installed plugin basename.
		wp_cache_flush();
		if ( $installer->plugin_info() ) {
			$plugin_basename = $installer->plugin_info();
			echo wp_json_encode( [ 'plugin' => $plugin_basename ] );
			die;
		}
	}

	// Send back a response.
	echo wp_json_encode( true );
	die;
}


/** Function envira_gallery_ajax_load_gallery_data() called by wp_ajax hooks: {'envira_gallery_load_gallery_data'} **/
/** Parameters found in function envira_gallery_ajax_load_gallery_data(): {"post": ["post_id"]} **/
function envira_gallery_ajax_load_gallery_data() {
	check_admin_referer( 'envira-gallery-load-gallery', 'nonce' );
	$gallery_id = isset( $_POST['post_id'] ) ? absint( sanitize_key( $_POST['post_id'] ) ) : null;

	if ( ! current_user_can( 'edit_post', $gallery_id ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}

	$gallery_data = get_post_meta( $gallery_id, '_eg_gallery_data', true );

	// Send back the gallery data.
	echo wp_json_encode( $gallery_data );
	die;
}


