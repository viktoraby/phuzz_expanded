<?php
/***
*
*Found actions: 13
*Found functions:13
*Extracted functions:13
*Total parameter names extracted: 11
*Overview: {'dismiss': {'nextgen_notification_dismiss'}, 'deactivate_am_plugin': {'nextgen_deactivate_am_plugin'}, 'ajax_set_post_thumbnail': {'ngg_set_post_thumbnail'}, 'mark_admin_menu_tooltip_hidden': {'ngg_hide_admin_menu_tooltip'}, 'install_am_plugin': {'nextgen_install_am_plugin'}, 'ngg_ajax_operation': {'ngg_ajax_operation'}, 'createNewThumb': {'createNewThumb'}, 'install_recommended_plugins': {'install_recommended_plugins'}, 'save_selected_addons': {'save_selected_addons'}, 'ngg_plugin_verify_license_key': {'ngg_plugin_verify_license_key'}, 'ngg_rotateImage': {'rotateImage'}, 'save_onboarding_data': {'save_onboarding_data'}, 'activate_am_plugin': {'nextgen_activate_am_plugin'}}
*
***/

/** Function dismiss() called by wp_ajax hooks: {'nextgen_notification_dismiss'} **/
/** No params detected :-/ **/


/** Function deactivate_am_plugin() called by wp_ajax hooks: {'nextgen_deactivate_am_plugin'} **/
/** Parameters found in function deactivate_am_plugin(): {"post": ["basename"]} **/
function deactivate_am_plugin() {
		// Run a security check first.
		check_admin_referer( 'nextgen-deactivate-partner', 'nonce' );

		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to deactivate plugins.', 'nggallery' ) ] );
		}

		// Deactivate the addon.
		if ( isset( $_POST['basename'] ) ) {
			$basename_raw = sanitize_text_field( wp_unslash( $_POST['basename'] ) ); // Sanitize at ingestion.
			// CSRF-bypass hardening: same allowlist as activate_am_plugin() to prevent toggling unrelated plugins.
			if ( ! in_array( $basename_raw, $this->get_am_plugin_basenames(), true ) ) {
				wp_send_json_error( [ 'message' => esc_html__( 'Plugin is not in the allowed list.', 'nggallery' ) ] );
			}
			deactivate_plugins( $basename_raw );
		}

		echo wp_json_encode( true );
		die;
	}


/** Function ajax_set_post_thumbnail() called by wp_ajax hooks: {'ngg_set_post_thumbnail'} **/
/** Parameters found in function ajax_set_post_thumbnail(): {"request": ["nonce", "post_id", "thumbnail_id"]} **/
function ajax_set_post_thumbnail() {
		// This function does the following:
		// 1) Check if the user is logged in and has permission to edit the post
		// 2) Get the thumbnail id from the POST request. The thumbnail id is actually the NGG image id
		// 3)]

		global $post_ID;

		// check for correct capability
		if ( ! is_user_logged_in() ) {
			die( '-1' );
		}

		// Sanitize + unslash nonce prior to verification per WP standards (defense-in-depth).
		if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'ngg_set_post_thumbnails' ) ) {
			die( '-1' );
		}

		// get the post id as global variable, otherwise the ajax_nonce failed later
		// wp_unslash added before intval() per WP input handling standards (strip magic-quote slashes before cast).
		$post_ID = isset( $_REQUEST['post_id'] ) ? intval( wp_unslash( $_REQUEST['post_id'] ) ) : 0;

		if ( ! current_user_can( 'edit_post', $post_ID ) ) {
			die( '-1' );
		}

		// wp_unslash added before intval() per WP input handling standards (strip magic-quote slashes before cast).
		$thumbnail_id = isset( $_REQUEST['thumbnail_id'] ) ? intval( wp_unslash( $_REQUEST['thumbnail_id'] ) ) : 0;

		// delete the image
		if ( $thumbnail_id == '-1' ) {
			delete_post_meta( $post_ID, '_thumbnail_id' );
			die( '1' );
		}

		// Scope NGG image selection: edit_post alone lets an author attach arbitrary NGG images (incl. private/admin-only galleries) as featured image. Require the NGG Attach Interface cap so only users granted gallery-attach rights may pick NGG images. Removal branch above is not gated since it only clears post meta on a post the user already owns.
		if ( ! \Imagely\NGG\Util\Security::is_allowed( 'NextGEN Attach Interface' ) ) {
			die( '-1' );
		}

		$attachment_id = StorageManager::get_instance()->set_post_thumbnail( $post_ID, $thumbnail_id, TRUE );
		if ( $attachment_id ) {
			die( strval( $attachment_id ) );
		}
		die( strval( 0 ) );
	}


/** Function mark_admin_menu_tooltip_hidden() called by wp_ajax hooks: {'ngg_hide_admin_menu_tooltip'} **/
/** No params detected :-/ **/


/** Function install_am_plugin() called by wp_ajax hooks: {'nextgen_install_am_plugin'} **/
/** Parameters found in function install_am_plugin(): {"post": ["download_url"]} **/
function install_am_plugin() {

		check_admin_referer( 'nextgen-install-partner', 'nonce' );

		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to install plugins.', 'nggallery' ) ] );
		}

		// Install the addon.
		if ( isset( $_POST['download_url'] ) ) {

			$download_url = esc_url_raw( wp_unslash( $_POST['download_url'] ) );

			// Host+path allowlist: restrict installable package source to specific distribution paths so a future
			// arbitrary file upload, open redirect, or media attachment on a broad host (wordpress.org, imagely.com)
			// cannot be coerced into installing an attacker-controlled plugin zip via admin CSRF.
			$parsed_dl_url = wp_parse_url( $download_url );
			$dl_host       = isset( $parsed_dl_url['host'] ) ? strtolower( $parsed_dl_url['host'] ) : '';
			$dl_scheme     = isset( $parsed_dl_url['scheme'] ) ? strtolower( $parsed_dl_url['scheme'] ) : '';
			$dl_path       = isset( $parsed_dl_url['path'] ) ? $parsed_dl_url['path'] : '';

			// Tuples of [host, path-prefix-regex]. Path must end in .zip after the .zip-only suffix check below.
			$allowed_sources = [
				[ 'downloads.wordpress.org', '#^/plugin/[A-Za-z0-9._-]+\.zip$#' ],
				[ 'www.imagely.com', '#^/(downloads|dl)/[A-Za-z0-9._/-]+\.zip$#' ],
				[ 'imagely.com', '#^/(downloads|dl)/[A-Za-z0-9._/-]+\.zip$#' ],
			];

			$source_ok = false;
			if ( 'https' === $dl_scheme ) {
				foreach ( $allowed_sources as $allowed ) {
					if ( $dl_host === $allowed[0] && preg_match( $allowed[1], $dl_path ) ) {
						$source_ok = true;
						break;
					}
				}
			}
			if ( ! $source_ok ) {
				wp_send_json_error( [ 'message' => esc_html__( 'Download URL is not from an allowed host.', 'nggallery' ) ] );
			}

			global $hook_suffix;

			// Set the current screen to avoid undefined notices.
			set_current_screen();

			$method = '';
			$url    = esc_url_raw( admin_url( 'admin.php?page=nextgen-gallery-about-us' ) );

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

			// Create the plugin upgrader with our custom skin.
			$skin      = new \Imagely\NGG\Util\Installer_Skin();
			$installer = new \Plugin_Upgrader( $skin );
			$installer->install( $download_url );

			// Flush the cache and return the newly installed plugin basename.
			wp_cache_flush();

			if ( $installer->plugin_info() ) {
				$plugin_basename = $installer->plugin_info();

				// Activate only basenames registered in get_am_plugins(); same gate sibling activate/deactivate use.
				if ( ! in_array( $plugin_basename, $this->get_am_plugin_basenames(), true ) ) {
					wp_send_json_error(
						[
							'message' => esc_html__( 'Installed plugin is not in the partner allowlist; activation skipped.', 'nggallery' ),
							'plugin'  => $plugin_basename,
						]
					);
				}

				$active = activate_plugin( $plugin_basename, false, false, true );

				wp_send_json_success( [ 'plugin' => $plugin_basename ] );

				die();
			}
		}

		// Send back a response.
		echo wp_json_encode( true );
		die;
	}


/** Function ngg_ajax_operation() called by wp_ajax hooks: {'ngg_ajax_operation'} **/
/** Parameters found in function ngg_ajax_operation(): {"post": ["_wpnonce", "image", "operation"]} **/
function ngg_ajax_operation() {

	// if nonce is not correct it returns -1.
	// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized,WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- check_ajax_referer handles nonce verification internally
	check_ajax_referer( 'ngg-ajax', 'nonce' );

	// check for correct capability.
	if ( ! is_user_logged_in() ) {
		die( '-1' );
	}

	if ( ! wp_verify_nonce( isset( $_POST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) : '', 'ngg-ajax' ) ) {
		die( '-1' );
	}

	// check for correct NextGEN capability.
 // phpcs:ignore WordPress.WP.Capabilities.Unknown
	if ( ! current_user_can( 'NextGEN Upload images' ) && ! current_user_can( 'NextGEN Manage gallery' ) ) {
		die( '-1' );
	}

	// include the ngg function.
	include_once __DIR__ . '/functions.php';

	// Get the image id.
	if ( isset( $_POST['image'] ) ) {
		$id = (int) sanitize_text_field( wp_unslash( $_POST['image'] ) );

		if ( ! ngg_ajax_user_can_edit_image( $id ) ) {
			die( '-1' );
		}

		// let's get the image data.
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized,WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- $_POST['image'] is sanitized on line 36
		$picture = nggdb::find_image( $id );
		// what do you want to do ?
		$operation = isset( $_POST['operation'] ) ? sanitize_text_field( wp_unslash( $_POST['operation'] ) ) : '';

		// Bounds the dynamic ngg_ajax_<operation> hook to addon-registered op names.
		$allowed_dynamic_operations = (array) apply_filters(
			'ngg_ajax_operation_allowlist',
			[]
		);

		switch ( $operation ) {
			case 'create_thumbnail':
				$result = nggAdmin::create_thumbnail( $picture );
				break;
			case 'resize_image':
				$result = nggAdmin::resize_image( $picture );
				break;
			case 'rotate_cw':
				$result = nggAdmin::rotate_image( $picture, 'CW' );
				nggAdmin::create_thumbnail( $picture );
				break;
			case 'rotate_ccw':
				$result = nggAdmin::rotate_image( $picture, 'CCW' );
				nggAdmin::create_thumbnail( $picture );
				break;
			case 'set_watermark':
				$result = nggAdmin::set_watermark( $picture );
				break;
			case 'recover_image':
				$result = nggAdmin::recover_image( $id ) ? '1' : '0';
				break;
			case 'import_metadata':
				$result = \Imagely\NGG\DataMappers\Image::get_instance()->reimport_metadata( $id ) ? '1' : '0';
				break;
			case 'get_image_ids':
				$result = nggAdmin::get_image_ids( $id );
				break;

			// This will read the EXIF and then write it with the Orientation tag reset.
			case 'strip_orientation_tag':
				$storage     = \Imagely\NGG\DataStorage\Manager::get_instance();
				$image_path  = $storage->get_image_abspath( $id );
				$backup_path = $image_path . '_backup';
				// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
				$exif_abspath = @file_exists( $backup_path ) ? $backup_path : $image_path;
				// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
				$exif_iptc = @\Imagely\NGG\DataStorage\EXIFWriter::read_metadata( $exif_abspath );
				foreach ( $storage->get_image_sizes( $id ) as $size ) {
					if ( $size === 'backup' ) {
						continue;
					}
					// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
					@\Imagely\NGG\DataStorage\EXIFWriter::write_metadata( $storage->get_image_abspath( $id, $size ), $exif_iptc );
				}
				$result = '1';
				break;
			default:
				if ( $operation !== '' && in_array( $operation, $allowed_dynamic_operations, true ) ) {
					do_action( 'ngg_ajax_' . $operation, $id );
				}
				die( '-1' );
		}
		// A success should return a '1'.
// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $result contains safe response data for AJAX
		die( $result );
	}   // The script should never stop here.
	die( '0' );
}


/** Function createNewThumb() called by wp_ajax hooks: {'createNewThumb'} **/
/** Parameters found in function createNewThumb(): {"post": ["nonce", "id", "x", "rr", "y", "w", "h"]} **/
function createNewThumb() {

	// check for correct capability.
	if ( ! is_user_logged_in() ) {
		die( '-1' );
	}

	// check for correct NextGEN capability.
 // phpcs:ignore WordPress.WP.Capabilities.Unknown
	if ( ! current_user_can( 'NextGEN Manage gallery' ) ) {
		die( '-1' );
	}

	if ( ! wp_verify_nonce( isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '', 'ngg_update_thumbnail' ) ) {
		die( '-1' );
	}

	$id = (int) ( isset( $_POST['id'] ) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : 0 );

	if ( ! ngg_ajax_user_can_edit_image( $id ) ) {
		die( '-1' );
	}

	$x          = round( ( isset( $_POST['x'] ) ? floatval( sanitize_text_field( wp_unslash( $_POST['x'] ) ) ) : 0 ) * ( isset( $_POST['rr'] ) ? floatval( sanitize_text_field( wp_unslash( $_POST['rr'] ) ) ) : 1 ), 0 );
	$y          = round( ( isset( $_POST['y'] ) ? floatval( sanitize_text_field( wp_unslash( $_POST['y'] ) ) ) : 0 ) * ( isset( $_POST['rr'] ) ? floatval( sanitize_text_field( wp_unslash( $_POST['rr'] ) ) ) : 1 ), 0 );
	$w          = round( ( isset( $_POST['w'] ) ? floatval( sanitize_text_field( wp_unslash( $_POST['w'] ) ) ) : 0 ) * ( isset( $_POST['rr'] ) ? floatval( sanitize_text_field( wp_unslash( $_POST['rr'] ) ) ) : 1 ), 0 );
	$h          = round( ( isset( $_POST['h'] ) ? floatval( sanitize_text_field( wp_unslash( $_POST['h'] ) ) ) : 0 ) * ( isset( $_POST['rr'] ) ? floatval( sanitize_text_field( wp_unslash( $_POST['rr'] ) ) ) : 1 ), 0 );
	$crop_frame = [
		'x'      => $x,
		'y'      => $y,
		'width'  => $w,
		'height' => $h,
	];

	$storage = \Imagely\NGG\DataStorage\Manager::get_instance();

	// XXX NextGEN Legacy wasn't handling watermarks or reflections at this stage, so we're forcefully disabling them to maintain compatibility.
	$params = [
		'watermark'  => false,
		'reflection' => false,
		'crop'       => true,
		'crop_frame' => $crop_frame,
	];
	$result = $storage->generate_thumbnail( $id, $params );

	if ( $result ) {
		echo 'OK';
	} else {
		header( 'HTTP/1.1 500 Internal Server Error' );
		echo 'KO';
	}

	exit();
}


/** Function install_recommended_plugins() called by wp_ajax hooks: {'install_recommended_plugins'} **/
/** Parameters found in function install_recommended_plugins(): {"post": ["nonce", "plugins"]} **/
function install_recommended_plugins() {
		// check for nonce nextgen-galleryOnboardingCheck.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'nextgen-galleryOnboardingCheck' ) ) {
			wp_send_json_error( 'Invalid nonce' );
			wp_die();
		}

		// check if the current user can manage options.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'You do not have permission to install plugins' );
			wp_die();
		}

		if ( ! empty( $_POST['plugins'] ) ) {
			// Sanitize data, plugins is a string delimited by comma.

			$plugins = explode( ',', sanitize_text_field( wp_unslash( $_POST['plugins'] ) ) );
			// Allowlist: only slugs returned by get_recommended_plugins() may be installed via this endpoint; prevents arbitrary wp.org plugin install from attacker-supplied slug.
			$allowed_slugs = array_keys( $this->get_recommended_plugins() );
			// Install the plugins.
			foreach ( $plugins as $plugin ) {
				$plugin = sanitize_key( $plugin ); // Enforce slug charset (a-z0-9_-) so crafted values cannot break out of the downloads.wordpress.org URL path.
				if ( ! in_array( $plugin, $allowed_slugs, true ) ) {
					continue; // Reject any slug not in the recommended allowlist.
				}
				if ( '' !== $this->is_recommended_plugin_installed( $plugin ) ) {
					continue; // Skip the plugin if it is already installed.
				}
				// Generate the plugin URL by slug (slug now validated against allowlist above).
				$url = 'https://downloads.wordpress.org/plugin/' . $plugin . '.zip';
				$this->install_helper( $url );

			}
		}
		wp_send_json_success( 'Installed the recommended plugins successfully.' );
		wp_die();
	}


/** Function save_selected_addons() called by wp_ajax hooks: {'save_selected_addons'} **/
/** Parameters found in function save_selected_addons(): {"post": ["nonce", "addons"]} **/
function save_selected_addons() {
		// check for nonce nextgen-galleryOnboardingCheck.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'nextgen-galleryOnboardingCheck' ) ) {
			wp_send_json_error( 'Invalid nonce' );
			wp_die();
		}

		// check if the current user can manage options.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'You do not have permission to save data' );
			wp_die();
		}

		if ( ! empty( $_POST['addons'] ) ) {

			$addons = explode( ',', sanitize_text_field( wp_unslash( $_POST['addons'] ) ) );

			// Sanitize data and merge to existing data.
			$onboarding_data = get_option( 'ngg_onboarding_data' );
			if ( empty( $onboarding_data ) ) {
				$onboarding_data = [];
			}

			// Save addons as _addons key.
			$onboarding_data['_addons'] = $addons;

			$updated = update_option( 'ngg_onboarding_data', $onboarding_data );

			wp_send_json_success( 'Features saved successfully' );
			wp_die();
		}

		wp_send_json_error( 'Something went wrong. Please try again.' );
		wp_die();
	}


/** Function ngg_plugin_verify_license_key() called by wp_ajax hooks: {'ngg_plugin_verify_license_key'} **/
/** Parameters found in function ngg_plugin_verify_license_key(): {"post": ["nonce"]} **/
function ngg_plugin_verify_license_key() {
		// Capability guard: handler installs/activates plugins and writes license options; restrict to site admins, matching sibling onboarding handlers (save_onboarding_data, install_recommended_plugins).
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'You do not have permission to verify license keys' );
			wp_die();
		}

		if (
			! isset( $_POST['nextgen-gallery-license-key'], $_POST['nonce'] )
			|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'nextgen-galleryOnboardingCheck' )
		) {
			wp_send_json_error( 'Invalid Request', \WP_Http::FORBIDDEN );
			wp_die();
		}

		$license_key = isset( $_POST['nextgen-gallery-license-key'] ) ? sanitize_text_field( wp_unslash( $_POST['nextgen-gallery-license-key'] ) ) : null;

		if ( empty( $license_key ) ) {
			wp_send_json_error( 'License key is required' );
			wp_die();
		}

		// Verify license with external server using shared helper
		$verification_result = \Imagely\NGG\Util\LicenseHelper::verify_license_with_server( $license_key );

		if ( is_wp_error( $verification_result ) ) {
			wp_send_json_error( $verification_result->get_error_message() );
			wp_die();
		}

		$product = $verification_result['level'];

		// Check if the product is already active
		$current_level = \Imagely\NGG\Util\LicenseHelper::get_license_type();
		if ( $current_level === $product ) {
			wp_send_json_success( 'Congratulations! This site is now receiving automatic updates.' );
			wp_die();
		}

		// Check if the product is installed but not activated
		$plugin_basenames = [
			'pro'     => 'nextgen-gallery-pro/nggallery-pro.php',
			'plus'    => 'nextgen-gallery-plus/nggallery-plus.php',
			'starter' => 'nextgen-gallery-starter/nggallery-starter.php',
		];

		if ( \Imagely\NGG\Util\LicenseHelper::is_product_installed( $product ) ) {
			// Plugin is installed but not active - just activate it
			$plugin_basename = $plugin_basenames[ $product ];
			$activate        = activate_plugin( $plugin_basename, false, false, true );

			if ( is_wp_error( $activate ) ) {
				wp_send_json_error( $activate->get_error_message() );
				wp_die();
			}

			wp_send_json_success( 'Congratulations! This site is now receiving automatic updates.' );
			wp_die();
		}

		// Get download URL using shared helper
		$download_url = \Imagely\NGG\Util\LicenseHelper::get_download_url( $license_key, $product );

		if ( is_wp_error( $download_url ) ) {
			wp_send_json_error( $download_url->get_error_message() );
			wp_die();
		}

		// Install and activate Pro plugin (non-silent mode for AJAX, with activation)
		// Pro plugins should be activated immediately since user has valid license
		$install_result = \Imagely\NGG\Util\LicenseHelper::install_plugin( $download_url, false, true );

		if ( is_wp_error( $install_result ) ) {
			wp_send_json_error( $install_result->get_error_message() );
			wp_die();
		}

		wp_send_json_success( 'Congratulations! This site is now receiving automatic updates.' );
		wp_die();
	}


/** Function ngg_rotateImage() called by wp_ajax hooks: {'rotateImage'} **/
/** Parameters found in function ngg_rotateImage(): {"post": ["nonce", "id", "ra"]} **/
function ngg_rotateImage() {

	// check for correct capability.
	if ( ! is_user_logged_in() ) {
		die( '-1' );
	}

	if ( ! wp_verify_nonce( isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '', 'ngg-rotate-image' ) ) {
		die( '-1' );
	}

	// check for correct NextGEN capability.
 // phpcs:ignore WordPress.WP.Capabilities.Unknown
	if ( ! current_user_can( 'NextGEN Manage gallery' ) ) {
		die( '-1' );
	}

	require_once dirname( __DIR__ ) . '/ngg-config.php';

	// include the ngg function.
	include_once __DIR__ . '/functions.php';

	$id = (int) ( isset( $_POST['id'] ) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : 0 );

	if ( ! ngg_ajax_user_can_edit_image( $id ) ) {
		die( '-1' );
	}

	$result = '-1';

	$ra = isset( $_POST['ra'] ) ? sanitize_text_field( wp_unslash( $_POST['ra'] ) ) : '';
	switch ( $ra ) {
		case 'cw':
			$result = nggAdmin::rotate_image( $id, 'CW' );
			break;
		case 'ccw':
			$result = nggAdmin::rotate_image( $id, 'CCW' );
			break;
		case 'fv':
			// Note: H/V have been inverted here to make it more intuitive.
			$result = nggAdmin::rotate_image( $id, 0, 'H' );
			break;
		case 'fh':
			// Note: H/V have been inverted here to make it more intuitive.
			$result = nggAdmin::rotate_image( $id, 0, 'V' );
			break;
	}

	// recreate the thumbnail.
	nggAdmin::create_thumbnail( $id );

	if ( $result == 1 ) {
		die( '1' );
	}

	header( 'HTTP/1.1 500 Internal Server Error' );
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $result contains safe error response data
	die( $result );
}


/** Function save_onboarding_data() called by wp_ajax hooks: {'save_onboarding_data'} **/
/** Parameters found in function save_onboarding_data(): {"post": ["nonce", "eow"]} **/
function save_onboarding_data() {

		// check for nonce nextgen-galleryOnboardingCheck.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'nextgen-galleryOnboardingCheck' ) ) {
			wp_send_json_error( 'Invalid nonce' );
			wp_die();
		}

		// check if the current user can manage options.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'You do not have permission to save data' );
			wp_die();
		}

		if ( ! empty( $_POST['eow'] ) ) {
			// Sanitize data and merge to existing data.
			$onboarding_data = get_option( 'ngg_onboarding_data', [] );

			$onboarding_data = $this->sanitize_and_assign( '_usage_tracking', 'sanitize_text_field', $onboarding_data );
			$onboarding_data = $this->sanitize_and_assign( '_email_address', 'sanitize_email', $onboarding_data );
			$onboarding_data = $this->sanitize_and_assign( '_email_opt_in', 'sanitize_text_field', $onboarding_data );
			$onboarding_data = $this->sanitize_and_assign( '_user_type', 'sanitize_text_field', $onboarding_data );
			$onboarding_data = $this->sanitize_and_assign( '_others', 'sanitize_text_field', $onboarding_data );

			$stats_sent     = isset( $onboarding_data['usage_stats_init'] ) ? $onboarding_data['usage_stats_init'] : false;
			$usage_tracking = filter_var( $onboarding_data['_usage_tracking'], FILTER_VALIDATE_BOOLEAN );

			if ( $usage_tracking && ! $stats_sent ) {
				// Send usage tracking on onboarding settings save.
				( new UsageTracking() )->send_checkin( true );
				$onboarding_data['usage_stats_init'] = true;
			}

			update_option( 'ngg_onboarding_data', $onboarding_data );

			// Send data to Drip.
			$this->save_to_drip( $onboarding_data );

			wp_send_json_success( 'Data saved successfully' );
			wp_die();
		}

		wp_send_json_error( 'Something went wrong. Please try again.' );
		wp_die();
	}


/** Function activate_am_plugin() called by wp_ajax hooks: {'nextgen_activate_am_plugin'} **/
/** Parameters found in function activate_am_plugin(): {"post": ["basename"]} **/
function activate_am_plugin() {
		// Run a security check first.
		check_admin_referer( 'nextgen-activate-partner', 'nonce' );

		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to activate plugins.', 'nggallery' ) ] );
		}

		// Activate the addon.
		if ( isset( $_POST['basename'] ) ) {
			$basename_raw = sanitize_text_field( wp_unslash( $_POST['basename'] ) ); // Ingestion-point sanitize before any comparison/use.
			// CSRF-bypass hardening: restrict activation to the partner plugin allowlist so a stolen nonce can't activate an unrelated installed plugin.
			if ( ! in_array( $basename_raw, $this->get_am_plugin_basenames(), true ) ) {
				wp_send_json_error( [ 'message' => esc_html__( 'Plugin is not in the allowed list.', 'nggallery' ) ] );
			}
			$activate = activate_plugin( $basename_raw );

			if ( is_wp_error( $activate ) ) {
				echo wp_json_encode( [ 'error' => $activate->get_error_message() ] );
				die;
			}
		}

		echo wp_json_encode( true );
		die;
	}


