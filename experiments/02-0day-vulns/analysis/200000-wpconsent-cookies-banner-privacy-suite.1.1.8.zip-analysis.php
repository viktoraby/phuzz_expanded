<?php
/***
*
*Found actions: 31
*Found functions:31
*Extracted functions:31
*Total parameter names extracted: 24
*Overview: {'ajax_scan_page': {'wpconsent_scan_page'}, 'ajax_deactivate': {'wpconsent_inspector_deactivate'}, 'wpconsent_ajax_manage_service': {'wpconsent_manage_service'}, 'wpconsent_ajax_add_category': {'wpconsent_add_category'}, 'wpconsent_ajax_delete_service': {'wpconsent_delete_service'}, 'ajax_add_cookie': {'wpconsent_inspector_add_cookie'}, 'generate_url': {'wpconsent_connect_url'}, 'process': {'nopriv_wpconsent_connect_process'}, 'ajax_dismiss_cookie': {'wpconsent_inspector_dismiss_cookie'}, 'wpconsent_ajax_delete_category': {'wpconsent_delete_category'}, 'wpconsent_ajax_generate_cookie_policy': {'wpconsent_generate_cookie_policy'}, 'wpconsent_ajax_search_pages': {'wpconsent_search_pages'}, 'wpconsent_ajax_edit_category': {'wpconsent_edit_category'}, 'wpconsent_ajax_save_banner_layout': {'wpconsent_save_banner_layout'}, 'wpconsent_ajax_get_services': {'wpconsent_get_services'}, 'wpconsent_ajax_save_usage_tracking': {'wpconsent_save_usage_tracking'}, 'wpconsent_ajax_verify_ssl': {'wpconsent_verify_ssl'}, 'wpconsent_ajax_manage_cookie': {'wpconsent_manage_cookie'}, 'wpconsent_ajax_auto_configure': {'wpconsent_auto_configure'}, 'dismiss': {'wpconsent_notification_dismiss'}, 'dismiss_ajax': {'wpconsent_notice_dismiss'}, 'ajax_dismiss_suggestion': {'wpconsent_dismiss_suggestion'}, 'wpconsent_ajax_reset_to_defaults': {'wpconsent_reset_to_defaults'}, 'ajax_install_plugin': {'wpconsent_install_plugin'}, 'ajax_save_for_review': {'wpconsent_inspector_save_for_review'}, 'ajax_scan_website': {'wpconsent_scan_website'}, 'wpconsent_ajax_complete_onboarding': {'wpconsent_complete_onboarding'}, 'wpconsent_ajax_search_content': {'wpconsent_search_content'}, 'wpconsent_ajax_delete_cookie': {'wpconsent_delete_cookie'}, 'ajax_toggle_setting': {'wpconsent_toggle_setting'}, 'wpconsent_ajax_save_scanner_items': {'wpconsent_save_scanner_items'}}
*
***/

/** Function ajax_scan_page() called by wp_ajax hooks: {'wpconsent_scan_page'} **/
/** Parameters found in function ajax_scan_page(): {"post": ["page_id", "email", "request_id", "is_final", "scanned_pages", "total_pages"]} **/
function ajax_scan_page() {
		check_ajax_referer( 'wpconsent_admin', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		$page_id    = isset( $_POST['page_id'] ) ? absint( $_POST['page_id'] ) : 0;
		$email      = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		$request_id = isset( $_POST['request_id'] ) ? sanitize_text_field( wp_unslash( $_POST['request_id'] ) ) : '';
		$is_final   = isset( $_POST['is_final'] ) ? (bool) $_POST['is_final'] : false;

		if ( empty( $request_id ) ) {
			wp_send_json_error( array( 'message' => __( 'No request ID provided for scanning.', 'wpconsent-cookies-banner-privacy-suite' ) ) );
		}

		// Get the URL to scan - either homepage or page permalink
		$url = '';
		if ( $page_id === 0 ) {
			// If page_id is 0, scan the homepage
			$url = home_url( '/' );
		} else {
			// Get the permalink for the page ID
			$url = get_permalink( $page_id );
			if ( ! $url || is_wp_error( $url ) ) {
				wp_send_json_error( array( 'message' => __( 'Could not get permalink for the provided page ID.', 'wpconsent-cookies-banner-privacy-suite' ) ) );
			}
		}

		$current_scan = get_option(
			'wpconsent_scanner_' . $request_id,
			array(
				'scripts'         => array(),
				'services_needed' => array(),
			)
		);

		$this->aggregated_scripts  = $current_scan['scripts'];
		$this->aggregated_services = $current_scan['services_needed'];

		$response = $this->perform_scan( $url );

		// Check if we have an error from the scanner.
		if ( isset( $response['error'] ) && $response['error'] ) {
			$response['message'] = $response['error_message'];
		} else {
			// Aggregate results.
			$this->aggregate_scan_results( $response );

			// Save the aggregated results to temp option.
			update_option(
				'wpconsent_scanner_' . $request_id,
				array(
					'scripts'         => $this->aggregated_scripts,
					'services_needed' => $this->aggregated_services,
				)
			);

			// If this is the final scan, save the aggregated results.
			if ( $is_final ) {
				// Return the aggregated, deduplicated results to the client so consumers (e.g. the onboarding wizard) render the full set from all scanned pages, not just the last page.
				$response = $this->get_aggregated_results();
				// Sync services_needed with the full aggregated list so save_email() subscribes the user with every detected service. array_values() re-indexes because aggregate_scan_results() runs array_unique() which preserves sparse keys, and save_email() JSON-encodes this for the subscribe endpoint — sparse keys would serialize as an object instead of an array.
				$this->services_needed  = array_values( $response['services_needed'] );
				$response['services_needed'] = $this->services_needed;
				$response['message']    = $this->get_message( $response );

				$scanned_pages = isset( $_POST['scanned_pages'] ) ? absint( $_POST['scanned_pages'] ) : 0;
				$total_pages   = isset( $_POST['total_pages'] ) ? absint( $_POST['total_pages'] ) : 0;

				// Add page count information to the message.
				$response['message'] .= ' ' . sprintf(
					/* translators: %1$d: number of scanned pages, %2$d: total number of pages */
					__( '(%1$d of %2$d pages scanned)', 'wpconsent-cookies-banner-privacy-suite' ),
					$scanned_pages,
					$total_pages
				);

				$this->save_scan_data( $response );
				// Delete the temp option.
				delete_option( 'wpconsent_scanner_' . $request_id );
			} else {
				$response['message'] = $this->get_message( $response );
			}

			// Save email if provided.
			if ( ! empty( $email ) ) {
				$this->save_email( $email );
			}
		}

		wp_send_json_success( $response );
	}


/** Function ajax_deactivate() called by wp_ajax hooks: {'wpconsent_inspector_deactivate'} **/
/** No params detected :-/ **/


/** Function wpconsent_ajax_manage_service() called by wp_ajax hooks: {'wpconsent_manage_service'} **/
/** Parameters found in function wpconsent_ajax_manage_service(): {"post": ["post_id", "service_name", "service_description", "service_category", "service_url"]} **/
function wpconsent_ajax_manage_service() {
	check_admin_referer( 'wpconsent_manage_service', 'wpconsent_manage_service_nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error(
			array(
				'message' => esc_html__( 'You do not have permission to perform this action.', 'wpconsent-cookies-banner-privacy-suite' ),
			)
		);
	}

	// Get and sanitize all input fields.
	$post_id             = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
	$service_name        = isset( $_POST['service_name'] ) ? sanitize_text_field( wp_unslash( $_POST['service_name'] ) ) : '';
	$service_description = isset( $_POST['service_description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['service_description'] ) ) : '';
	$service_category    = isset( $_POST['service_category'] ) ? intval( $_POST['service_category'] ) : 0;
	$service_url         = isset( $_POST['service_url'] ) ? sanitize_text_field( wp_unslash( $_POST['service_url'] ) ) : '';

	// Validate required fields.
	if ( empty( $service_name ) || empty( $service_category ) ) {
		wp_send_json_error( array(
			'message' => esc_html__( 'Service name and category are required.', 'wpconsent-cookies-banner-privacy-suite' ),
		) );
	}

	$service_url = esc_url( $service_url );

	if ( $post_id ) {
		// Update existing service.
		$post_id         = wpconsent()->cookies->update_service( $post_id, $service_name, $service_description, $service_url, $service_category );
		$success_message = esc_html__( 'Service updated successfully.', 'wpconsent-cookies-banner-privacy-suite' );
		$error_message   = esc_html__( 'Failed to update service.', 'wpconsent-cookies-banner-privacy-suite' );
	} else {
		// Add new service.
		$result          = wpconsent()->cookies->add_service( $service_name, $service_category, $service_description, $service_url );
		$post_id         = $result;
		$success_message = esc_html__( 'Service added successfully.', 'wpconsent-cookies-banner-privacy-suite' );
		$error_message   = esc_html__( 'Failed to add Service.', 'wpconsent-cookies-banner-privacy-suite' );
	}

	if ( $post_id ) {
		wp_send_json_success( array(
			'id'          => $post_id,
			'name'        => $service_name,
			'description' => $service_description,
			'category_id' => $service_category,
			'message'     => $success_message,
			'service_url' => $service_url,
		) );
	} else {
		wp_send_json_error( array(
			'message' => $error_message,
		) );
	}
}


/** Function wpconsent_ajax_add_category() called by wp_ajax hooks: {'wpconsent_add_category'} **/
/** Parameters found in function wpconsent_ajax_add_category(): {"post": ["category_name", "category_description"]} **/
function wpconsent_ajax_add_category() {
	check_admin_referer( 'wpconsent_add_category', 'wpconsent_add_category_nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error(
			array(
				'message' => esc_html__( 'You do not have permission to perform this action.', 'wpconsent-cookies-banner-privacy-suite' ),
			)
		);
	}

	$category_name        = isset( $_POST['category_name'] ) ? sanitize_text_field( wp_unslash( $_POST['category_name'] ) ) : '';
	$category_description = isset( $_POST['category_description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['category_description'] ) ) : '';

	$category_id = wpconsent()->cookies->add_category( $category_name, $category_description );

	if ( $category_id ) {
		wp_send_json_success( array(
			'id'          => $category_id,
			'name'        => $category_name,
			'description' => $category_description,
		) );
	} else {
		wp_send_json_error( array(
			'message' => esc_html__( 'There was an error adding the category.', 'wpconsent-cookies-banner-privacy-suite' ),
		) );
	}
}


/** Function wpconsent_ajax_delete_service() called by wp_ajax hooks: {'wpconsent_delete_service'} **/
/** Parameters found in function wpconsent_ajax_delete_service(): {"post": ["service_id"]} **/
function wpconsent_ajax_delete_service() {
	check_ajax_referer( 'wpconsent_admin', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error(
			array(
				'message' => esc_html__( 'You do not have permission to perform this action.', 'wpconsent-cookies-banner-privacy-suite' ),
			)
		);
	}

	$service_id = isset( $_POST['service_id'] ) ? intval( $_POST['service_id'] ) : 0;

	if ( empty( $service_id ) ) {
		wp_send_json_error( array(
			'message' => esc_html__( 'Service ID is required.', 'wpconsent-cookies-banner-privacy-suite' ),
		) );
	}

	$deleted = wpconsent()->cookies->delete_service( $service_id );

	if ( $deleted ) {
		wp_send_json_success( array(
			'message' => esc_html__( 'Service deleted successfully.', 'wpconsent-cookies-banner-privacy-suite' ),
		) );
	} else {
		wp_send_json_error( array(
			'message' => esc_html__( 'Failed to delete service.', 'wpconsent-cookies-banner-privacy-suite' ),
		) );
	}
}


/** Function ajax_add_cookie() called by wp_ajax hooks: {'wpconsent_inspector_add_cookie'} **/
/** Parameters found in function ajax_add_cookie(): {"post": ["cookie_id", "cookie_name", "cookie_description", "cookie_category", "cookie_duration", "cookie_service", "dismiss"]} **/
function ajax_add_cookie() {
		check_ajax_referer( self::NONCE_ACTION, 'nonce' );
		$this->require_permission();

		$cookie_id          = isset( $_POST['cookie_id'] ) ? sanitize_text_field( wp_unslash( $_POST['cookie_id'] ) ) : '';
		$cookie_name        = isset( $_POST['cookie_name'] ) ? sanitize_text_field( wp_unslash( $_POST['cookie_name'] ) ) : '';
		$cookie_description = isset( $_POST['cookie_description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['cookie_description'] ) ) : '';
		$cookie_category    = isset( $_POST['cookie_category'] ) ? intval( $_POST['cookie_category'] ) : 0;
		$cookie_duration    = isset( $_POST['cookie_duration'] ) ? sanitize_text_field( wp_unslash( $_POST['cookie_duration'] ) ) : '';
		$cookie_service     = isset( $_POST['cookie_service'] ) ? intval( $_POST['cookie_service'] ) : 0;
		$dismiss            = isset( $_POST['dismiss'] ) && 'true' === sanitize_text_field( wp_unslash( $_POST['dismiss'] ) );

		if ( empty( $cookie_id ) || empty( $cookie_name ) || empty( $cookie_category ) ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'Cookie ID, name, and category are required.', 'wpconsent-cookies-banner-privacy-suite' ),
			) );
		}

		// Services are child terms, so prefer service over category when assigning.
		$term_id = $cookie_service ? $cookie_service : $cookie_category;

		$post_id = wpconsent()->cookies->add_cookie( $cookie_id, $cookie_name, $cookie_description, $term_id, $cookie_duration );

		if ( is_wp_error( $post_id ) ) {
			wp_send_json_error( array(
				'message' => $post_id->get_error_message(),
			) );
		}

		// Remove from pending queue if requested.
		if ( $dismiss ) {
			$this->dismiss_pending_cookie( $cookie_id );
		}

		wp_send_json_success( array(
			'post_id'   => $post_id,
			'cookie_id' => $cookie_id,
			'name'      => $cookie_name,
			'category'  => $cookie_category,
			'service'   => $cookie_service,
		) );
	}


/** Function generate_url() called by wp_ajax hooks: {'wpconsent_connect_url'} **/
/** Parameters found in function generate_url(): {"post": ["key"]} **/
function generate_url() {

		// Run a security check.
		check_ajax_referer( 'wpconsent_admin' );

		// Check for permissions.
		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'You are not allowed to install plugins.', 'wpconsent-cookies-banner-privacy-suite' ) ) );
		}

		$key = ! empty( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : '';

		if ( empty( $key ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Please enter your license key to connect.', 'wpconsent-cookies-banner-privacy-suite' ) ) );
		}

		if ( class_exists( 'WPConsent_Premium' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Only the Lite version can be upgraded.', 'wpconsent-cookies-banner-privacy-suite' ) ) );
		}

		// Verify pro version is not installed.
		$active = activate_plugin( 'wpconsent-premium/wpconsent-premium.php', false, false, true );

		if ( ! is_wp_error( $active ) ) {

			update_option( 'wpconsent_install', 1 ); // Run install routines.
			// Deactivate Lite.
			$plugin = plugin_basename( WPCONSENT_FILE );

			deactivate_plugins( $plugin );

			do_action( 'wpconsent_plugin_deactivated', $plugin );

			wp_send_json_success(
				array(
					'message' => esc_html__( 'WPConsent Pro is installed but not activated.', 'wpconsent-cookies-banner-privacy-suite' ),
					'reload'  => true,
				)
			);
		}

		// Generate URL.
		$oth        = hash( 'sha512', wp_rand() );
		$hashed_oth = hash_hmac( 'sha512', $oth, wp_salt() );

		update_option( 'wpconsent_connect_token', $oth );
		update_option( 'wpconsent_connect', $key );

		$version  = WPCONSENT_VERSION;
		$endpoint = admin_url( 'admin-ajax.php' );
		$redirect = admin_url( 'admin.php?page=wpconsent-cookies' );
		$url      = add_query_arg(
			array(
				'key'      => $key,
				'oth'      => $hashed_oth,
				'endpoint' => $endpoint,
				'version'  => $version,
				'siteurl'  => admin_url(),
				'homeurl'  => home_url(),
				'redirect' => rawurldecode( base64_encode( $redirect ) ), // phpcs:ignore
				'v'        => 2,
				'php'      => phpversion(),
				'wp'       => get_bloginfo( 'version' ),
			),
			'https://upgrade.wpconsent.com/'
		);

		wp_send_json_success(
			array(
				'url'      => $url,
				'back_url' => add_query_arg(
					array(
						'action' => 'wpconsent_connect',
						'oth'    => $oth,
					),
					$endpoint
				),
			)
		);
	}


/** Function process() called by wp_ajax hooks: {'nopriv_wpconsent_connect_process'} **/
/** No params detected :-/ **/


/** Function ajax_dismiss_cookie() called by wp_ajax hooks: {'wpconsent_inspector_dismiss_cookie'} **/
/** Parameters found in function ajax_dismiss_cookie(): {"post": ["cookie_name"]} **/
function ajax_dismiss_cookie() {
		check_ajax_referer( self::NONCE_ACTION, 'nonce' );
		$this->require_permission();

		$cookie_name = isset( $_POST['cookie_name'] ) ? sanitize_text_field( wp_unslash( $_POST['cookie_name'] ) ) : '';

		if ( empty( $cookie_name ) ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'Cookie name is required.', 'wpconsent-cookies-banner-privacy-suite' ),
			) );
		}

		$remaining = $this->dismiss_pending_cookie( $cookie_name );

		wp_send_json_success( array(
			'remaining' => $remaining,
		) );
	}


/** Function wpconsent_ajax_delete_category() called by wp_ajax hooks: {'wpconsent_delete_category'} **/
/** Parameters found in function wpconsent_ajax_delete_category(): {"post": ["category_id"]} **/
function wpconsent_ajax_delete_category() {
	check_ajax_referer( 'wpconsent_admin', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error(
			array(
				'message' => esc_html__( 'You do not have permission to perform this action.', 'wpconsent-cookies-banner-privacy-suite' ),
			)
		);
	}

	$category_id = isset( $_POST['category_id'] ) ? intval( $_POST['category_id'] ) : 0;

	// Error if category ID is not set.
	if ( empty( $category_id ) ) {
		wp_send_json_error( array(
			'message' => esc_html__( 'Category ID is required.', 'wpconsent-cookies-banner-privacy-suite' ),
		) );
	}

	$deleted = wpconsent()->cookies->delete_category( $category_id );

	if ( $deleted ) {
		wp_send_json_success();
	} else {
		wp_send_json_error( array(
			'message' => esc_html__( 'There was an error deleting the category.', 'wpconsent-cookies-banner-privacy-suite' ),
		) );
	}
}


/** Function wpconsent_ajax_generate_cookie_policy() called by wp_ajax hooks: {'wpconsent_generate_cookie_policy'} **/
/** No params detected :-/ **/


/** Function wpconsent_ajax_search_pages() called by wp_ajax hooks: {'wpconsent_search_pages'} **/
/** Parameters found in function wpconsent_ajax_search_pages(): {"post": ["search"]} **/
function wpconsent_ajax_search_pages() {
	check_ajax_referer( 'wpconsent_admin', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error(
			array(
				'message' => esc_html__( 'You do not have permission to perform this action.', 'wpconsent-cookies-banner-privacy-suite' ),
			)
		);
	}

	$search = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '';

	// Query pages with search term and order by relevance.
	$pages = get_posts( array(
		's'              => $search,
		'post_type'      => 'page',
		'posts_per_page' => 10,
		'orderby'        => 'relevance',
	) );

	// Let's format the pages to match the choices.js format.
	$pages = array_map( function ( $page ) {
		return array(
			'value' => $page->ID,
			'label' => $page->post_title,
		);
	}, $pages );

	wp_send_json_success( $pages );
}


/** Function wpconsent_ajax_edit_category() called by wp_ajax hooks: {'wpconsent_edit_category'} **/
/** Parameters found in function wpconsent_ajax_edit_category(): {"post": ["category_id", "category_name", "category_description"]} **/
function wpconsent_ajax_edit_category() {
	check_admin_referer( 'wpconsent_add_category', 'wpconsent_add_category_nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error(
			array(
				'message' => esc_html__( 'You do not have permission to perform this action.', 'wpconsent-cookies-banner-privacy-suite' ),
			)
		);
	}

	$category_id          = isset( $_POST['category_id'] ) ? intval( $_POST['category_id'] ) : 0;
	$category_name        = isset( $_POST['category_name'] ) ? sanitize_text_field( wp_unslash( $_POST['category_name'] ) ) : '';
	$category_description = isset( $_POST['category_description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['category_description'] ) ) : '';

	// Error if category ID is not set.
	if ( empty( $category_id ) ) {
		wp_send_json_error( array(
			'message' => esc_html__( 'Category ID is required.', 'wpconsent-cookies-banner-privacy-suite' ),
		) );
	}

	$updated = wpconsent()->cookies->update_category( $category_id, $category_name, $category_description );

	if ( $updated ) {
		wp_send_json_success( array(
			'id'          => $category_id,
			'name'        => $category_name,
			'description' => $category_description,
		) );
	} else {
		wp_send_json_error( array( 'message' => esc_html__( 'There was an error updating the category.', 'wpconsent-cookies-banner-privacy-suite' ) ) );
	}
}


/** Function wpconsent_ajax_save_banner_layout() called by wp_ajax hooks: {'wpconsent_save_banner_layout'} **/
/** Parameters found in function wpconsent_ajax_save_banner_layout(): {"post": ["banner_layout"]} **/
function wpconsent_ajax_save_banner_layout() {
	check_ajax_referer( 'wpconsent_banner_layout', 'wpconsent_banner_layout_nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error(
			array(
				'message' => esc_html__( 'You do not have permission to perform this action.', 'wpconsent-cookies-banner-privacy-suite' ),
			)
		);
	}

	$banner_layout = isset( $_POST['banner_layout'] ) ? sanitize_text_field( wp_unslash( $_POST['banner_layout'] ) ) : false;

	if ( $banner_layout ) {
		$position_input = 'banner_' . $banner_layout . '_position';
		$position       = isset( $_POST[ $position_input ] ) ? sanitize_text_field( wp_unslash( $_POST[ $position_input ] ) ) : false;

		$settings = array(
			'banner_layout'         => $banner_layout,
			'banner_position'       => $position,
			'enable_consent_banner' => 1,
			'onboarding_completed'  => 1,
		);
	}

	if ( ! empty( $settings ) ) {
		wpconsent()->settings->bulk_update_options( $settings );
	}

	wp_send_json_success(
		array(
			'message'  => esc_html__( 'Banner layout saved successfully.', 'wpconsent-cookies-banner-privacy-suite' ),
			'redirect' => admin_url( 'admin.php?page=wpconsent' ),
		)
	);
}


/** Function wpconsent_ajax_get_services() called by wp_ajax hooks: {'wpconsent_get_services'} **/
/** Parameters found in function wpconsent_ajax_get_services(): {"post": ["category_id"]} **/
function wpconsent_ajax_get_services() {
	// Nonce check.
	check_ajax_referer( 'wpconsent_admin', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error(
			array(
				'message' => esc_html__( 'You do not have permission to perform this action.', 'wpconsent-cookies-banner-privacy-suite' ),
			)
		);
	}

	$services_options = array(
		'0' => esc_html__( 'No service', 'wpconsent-cookies-banner-privacy-suite' ),
	);

	$category_id = isset( $_POST['category_id'] ) ? intval( $_POST['category_id'] ) : 0;

	$services_for_category = wpconsent()->cookies->get_services_by_category( $category_id );

	if ( ! empty( $services_for_category ) ) {
		foreach ( $services_for_category as $service ) {
			$services_options[ $service['id'] ] = $service['name'];
		}
	}

	// Let's build options for the select field.
	$options = '';

	foreach ( $services_options as $value => $label ) {
		$options .= '<option value="' . esc_attr( $value ) . '">' . esc_html( $label ) . '</option>';
	}

	wp_send_json_success( $options );

}


/** Function wpconsent_ajax_save_usage_tracking() called by wp_ajax hooks: {'wpconsent_save_usage_tracking'} **/
/** Parameters found in function wpconsent_ajax_save_usage_tracking(): {"post": ["usage_tracking"]} **/
function wpconsent_ajax_save_usage_tracking() {
	check_ajax_referer( 'wpconsent_admin', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error(
			array(
				'message' => esc_html__( 'You do not have permission to perform this action.', 'wpconsent-cookies-banner-privacy-suite' ),
			)
		);
	}

	$usage_tracking = isset( $_POST['usage_tracking'] ) ? intval( $_POST['usage_tracking'] ) : 0;

	// Save the usage tracking preference.
	wpconsent()->settings->update_option( 'usage_tracking', $usage_tracking );

	wp_send_json_success(
		array(
			'message' => esc_html__( 'Usage tracking preference saved successfully.', 'wpconsent-cookies-banner-privacy-suite' ),
		)
	);
}


/** Function wpconsent_ajax_verify_ssl() called by wp_ajax hooks: {'wpconsent_verify_ssl'} **/
/** No params detected :-/ **/


/** Function wpconsent_ajax_manage_cookie() called by wp_ajax hooks: {'wpconsent_manage_cookie'} **/
/** Parameters found in function wpconsent_ajax_manage_cookie(): {"post": ["post_id", "cookie_id", "cookie_name", "cookie_description", "cookie_category", "cookie_duration", "cookie_service"]} **/
function wpconsent_ajax_manage_cookie() {
	check_admin_referer( 'wpconsent_manage_cookie', 'wpconsent_manage_cookie_nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error(
			array(
				'message' => esc_html__( 'You do not have permission to perform this action.', 'wpconsent-cookies-banner-privacy-suite' ),
			)
		);
	}

	// Get and sanitize all input fields
	$post_id            = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
	$cookie_id          = isset( $_POST['cookie_id'] ) ? sanitize_text_field( wp_unslash( $_POST['cookie_id'] ) ) : 0;
	$cookie_name        = isset( $_POST['cookie_name'] ) ? sanitize_text_field( wp_unslash( $_POST['cookie_name'] ) ) : '';
	$cookie_description = isset( $_POST['cookie_description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['cookie_description'] ) ) : '';
	$cookie_category    = isset( $_POST['cookie_category'] ) ? intval( $_POST['cookie_category'] ) : 0;
	$cookie_duration    = isset( $_POST['cookie_duration'] ) ? sanitize_text_field( wp_unslash( $_POST['cookie_duration'] ) ) : 0;
	$cookie_service     = isset( $_POST['cookie_service'] ) ? intval( $_POST['cookie_service'] ) : '';

	// Validate required fields
	if ( empty( $cookie_name ) || empty( $cookie_category ) ) {
		wp_send_json_error( array(
			'message' => esc_html__( 'Cookie name and category are required.', 'wpconsent-cookies-banner-privacy-suite' ),
		) );
	}
	if ( 0 !== $cookie_service ) {
		$cookie_category = $cookie_service;
	}

	if ( $post_id ) {
		// Update existing cookie
		$post_id         = wpconsent()->cookies->update_cookie( $post_id, $cookie_id, $cookie_name, $cookie_description, $cookie_category, $cookie_duration );
		$success_message = esc_html__( 'Cookie updated successfully.', 'wpconsent-cookies-banner-privacy-suite' );
		$error_message   = esc_html__( 'Failed to update cookie.', 'wpconsent-cookies-banner-privacy-suite' );
	} else {
		// Add new cookie
		$result          = wpconsent()->cookies->add_cookie( $cookie_id, $cookie_name, $cookie_description, $cookie_category, $cookie_duration );
		$post_id         = $result;
		$success_message = esc_html__( 'Cookie added successfully.', 'wpconsent-cookies-banner-privacy-suite' );
		$error_message   = esc_html__( 'Failed to add cookie.', 'wpconsent-cookies-banner-privacy-suite' );
	}

	if ( $post_id ) {
		wp_send_json_success( array(
			'id'          => $post_id,
			'name'        => $cookie_name,
			'description' => $cookie_description,
			'category_id' => $cookie_category,
			'message'     => $success_message,
			'duration'    => $cookie_duration,
			'cookie_id'   => $cookie_id,
		) );
	} else {
		wp_send_json_error( array(
			'message' => $error_message,
		) );
	}
}


/** Function wpconsent_ajax_auto_configure() called by wp_ajax hooks: {'wpconsent_auto_configure'} **/
/** Parameters found in function wpconsent_ajax_auto_configure(): {"post": ["scanner_service", "script_blocking"]} **/
function wpconsent_ajax_auto_configure() {
	check_ajax_referer( 'wpconsent_scan_website', 'wpconsent_scan_website_nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error(
			array(
				'message' => esc_html__( 'You do not have permission to perform this action.', 'wpconsent-cookies-banner-privacy-suite' ),
			)
		);
	}

	$services        = isset( $_POST['scanner_service'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['scanner_service'] ) ) : array();
	$script_blocking = isset( $_POST['script_blocking'] ) ? 1 : 0;

	wpconsent()->scanner->auto_configure_services( $services );

	// Let's mark the scan as configured.
	wpconsent()->scanner->mark_scan_as_configured();

	// Clear cached service detection data.
	wpconsent()->cookies->clear_cookies_cache();

	// Let's update script blocking.
	wpconsent()->settings->bulk_update_options(
		array(
			'enable_script_blocking' => $script_blocking,
		)
	);

	wp_send_json_success( array(
		'message' => esc_html__( 'Services and cookies added successfully.', 'wpconsent-cookies-banner-privacy-suite' ),
	) );

}


/** Function dismiss() called by wp_ajax hooks: {'wpconsent_notification_dismiss'} **/
/** Parameters found in function dismiss(): {"post": ["id"]} **/
function dismiss() {
		// Run a security check.
		check_ajax_referer( 'wpconsent_admin', 'nonce' );

		// Check for access and required param.
		if ( ! $this->has_access() || empty( $_POST['id'] ) ) {
			wp_send_json_error();
		}

		$id     = sanitize_text_field( wp_unslash( $_POST['id'] ) );
		$option = $this->get_option();

		// Dismiss all notifications and add them to dissmiss array.
		if ( 'all' === $id ) {
			if ( is_array( $option['feed'] ) && ! empty( $option['feed'] ) ) {
				foreach ( $option['feed'] as $key => $notification ) {
					array_unshift( $option['dismissed'], $notification );
					unset( $option['feed'][ $key ] );
				}
			}
			if ( is_array( $option['events'] ) && ! empty( $option['events'] ) ) {
				foreach ( $option['events'] as $key => $notification ) {
					array_unshift( $option['dismissed'], $notification );
					unset( $option['events'][ $key ] );
				}
			}
		}

		$type = is_numeric( $id ) ? 'feed' : 'events';

		// Remove notification and add in dismissed array.
		if ( is_array( $option[ $type ] ) && ! empty( $option[ $type ] ) ) {
			foreach ( $option[ $type ] as $key => $notification ) {
				if ( $notification['id'] == $id ) { // phpcs:ignore WordPress.PHP.StrictComparisons
					// Add notification to dismissed array.
					array_unshift( $option['dismissed'], $notification );
					// Remove notification from feed or events.
					unset( $option[ $type ][ $key ] );
					break;
				}
			}
		}

		update_option( self::$option_name, $option, false );

		wp_send_json_success();
	}


/** Function dismiss_ajax() called by wp_ajax hooks: {'wpconsent_notice_dismiss'} **/
/** No params detected :-/ **/


/** Function ajax_dismiss_suggestion() called by wp_ajax hooks: {'wpconsent_dismiss_suggestion'} **/
/** Parameters found in function ajax_dismiss_suggestion(): {"post": ["suggestion"]} **/
function ajax_dismiss_suggestion() {
		check_ajax_referer( 'wpconsent_admin', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'You do not have permission to perform this action.', 'wpconsent-cookies-banner-privacy-suite' ),
			) );
		}

		$suggestion = isset( $_POST['suggestion'] ) ? sanitize_text_field( wp_unslash( $_POST['suggestion'] ) ) : '';

		$allowed = array( 'geolocation', 'iab_tcf', 'do_not_sell' );

		if ( ! in_array( $suggestion, $allowed, true ) ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'Invalid suggestion.', 'wpconsent-cookies-banner-privacy-suite' ),
			) );
		}

		$user_id   = get_current_user_id();
		$dismissed = get_user_meta( $user_id, 'wpconsent_dismissed_suggestions', true );
		if ( ! is_array( $dismissed ) ) {
			$dismissed = array();
		}

		if ( ! in_array( $suggestion, $dismissed, true ) ) {
			$dismissed[] = $suggestion;
			update_user_meta( $user_id, 'wpconsent_dismissed_suggestions', $dismissed );
		}

		wp_send_json_success();
	}


/** Function wpconsent_ajax_reset_to_defaults() called by wp_ajax hooks: {'wpconsent_reset_to_defaults'} **/
/** No params detected :-/ **/


/** Function ajax_install_plugin() called by wp_ajax hooks: {'wpconsent_install_plugin'} **/
/** Parameters found in function ajax_install_plugin(): {"post": ["slug"]} **/
function ajax_install_plugin() {
		check_ajax_referer( 'wpconsent_admin' );

		if ( ! current_user_can( 'install_plugins' ) || ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'You do not have permission to install plugins. Please ask your site administrator to install it for you.', 'wpconsent-cookies-banner-privacy-suite' ),
				)
			);
		}

		if ( ! wp_is_file_mod_allowed( 'install_plugins' ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Plugin installation is not allowed on this site. Please ask your site administrator to install it for you.', 'wpconsent-cookies-banner-privacy-suite' ),
				)
			);
		}

		$slug = isset( $_POST['slug'] ) ? sanitize_key( wp_unslash( $_POST['slug'] ) ) : '';

		if ( ! array_key_exists( $slug, $this->get_all_plugins() ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Invalid plugin.', 'wpconsent-cookies-banner-privacy-suite' ),
				)
			);
		}

		$plugin_info = $this->get_all_plugins()[ $slug ];

		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/template.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-screen.php';
		require_once ABSPATH . 'wp-admin/includes/screen.php';
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		// Set the current screen to avoid undefined notices.
		set_current_screen( 'toplevel_page_wpconsent' );

		// Do not allow WordPress to search/download translations, as this will break JS output.
		remove_action( 'upgrader_process_complete', array( 'Language_Pack_Upgrader', 'async_upgrade' ), 20 );

		// Already active (either version).
		if ( is_plugin_active( $plugin_info['slug'] ) || is_plugin_active( $plugin_info['pro_slug'] ) ) {
			$this->track_widget_install();
			wp_send_json_success(
				array(
					'message' => esc_html__( 'Plugin already active.', 'wpconsent-cookies-banner-privacy-suite' ),
				)
			);
		}

		$installed_plugins = array_keys( get_plugins() );

		// Pro version installed but not active — activate it.
		if ( in_array( $plugin_info['pro_slug'], $installed_plugins, true ) ) {
			$this->activate_and_respond( $plugin_info['pro_slug'] );
		}

		// Lite version installed but not active — activate it.
		if ( in_array( $plugin_info['slug'], $installed_plugins, true ) ) {
			$this->activate_and_respond( $plugin_info['slug'] );
		}

		// Not installed — download from wordpress.org and activate.
		wpconsent_require_upgrader();

		$installer = new Plugin_Upgrader( new WPConsent_Skin() );
		$installer->install( 'https://downloads.wordpress.org/plugin/' . $slug . '.zip' );

		wp_cache_delete( 'plugins', 'plugins' );

		$plugin_basename = $installer->plugin_info();
		if ( ! $plugin_basename ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Plugin installation failed. Please try again.', 'wpconsent-cookies-banner-privacy-suite' ),
				)
			);
		}

		$activated = activate_plugin( $plugin_basename );
		if ( is_wp_error( $activated ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Plugin installed but could not be activated.', 'wpconsent-cookies-banner-privacy-suite' ),
				)
			);
		}

		$this->track_widget_install();
		wp_send_json_success(
			array(
				'message' => esc_html__( 'Plugin installed and activated.', 'wpconsent-cookies-banner-privacy-suite' ),
			)
		);
	}


/** Function ajax_save_for_review() called by wp_ajax hooks: {'wpconsent_inspector_save_for_review'} **/
/** Parameters found in function ajax_save_for_review(): {"post": ["cookies", "pages_count"]} **/
function ajax_save_for_review() {
		check_ajax_referer( self::NONCE_ACTION, 'nonce' );
		$this->require_permission();

		// Individual fields are sanitized after json_decode below.
		$cookies_json = isset( $_POST['cookies'] ) ? wp_unslash( $_POST['cookies'] ) : '[]'; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$cookies      = json_decode( $cookies_json, true );

		if ( ! is_array( $cookies ) || empty( $cookies ) ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'No cookies to save.', 'wpconsent-cookies-banner-privacy-suite' ),
			) );
		}

		$sanitized = array();
		foreach ( $cookies as $cookie ) {
			$entry = array(
				'name'         => isset( $cookie['name'] ) ? sanitize_text_field( $cookie['name'] ) : '',
				'value'        => isset( $cookie['value'] ) ? sanitize_text_field( $cookie['value'] ) : '',
				'pages'        => ( isset( $cookie['pages'] ) && is_array( $cookie['pages'] ) ) ? array_map( 'esc_url_raw', $cookie['pages'] ) : array(),
				'consentState' => isset( $cookie['consentState'] ) ? sanitize_text_field( $cookie['consentState'] ) : 'pre-consent',
				'duration'     => isset( $cookie['duration'] ) ? sanitize_text_field( $cookie['duration'] ) : '',
			);

			if ( ! empty( $cookie['suggestedPattern'] ) ) {
				$entry['suggestedPattern'] = sanitize_text_field( $cookie['suggestedPattern'] );
				$entry['scriptUrl']        = ! empty( $cookie['scriptUrl'] ) ? esc_url_raw( $cookie['scriptUrl'] ) : '';
			}

			if ( ! empty( $cookie['inlineScript'] ) ) {
				// Cap at 10kb to prevent bloat from minified scripts.
				$inline               = sanitize_textarea_field( wp_unslash( $cookie['inlineScript'] ) );
				$entry['inlineScript'] = substr( $inline, 0, 10240 );
			}

			$sanitized[] = $entry;
		}

		$pages_count = isset( $_POST['pages_count'] ) ? absint( $_POST['pages_count'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing

		update_option( $this->get_pending_option(), array(
			'cookies'     => $sanitized,
			'pages_count' => $pages_count,
		) );

		delete_transient( $this->get_transient_key() );

		wp_send_json_success( array(
			'count'      => count( $sanitized ),
			'review_url' => $this->get_review_url(),
		) );
	}


/** Function ajax_scan_website() called by wp_ajax hooks: {'wpconsent_scan_website'} **/
/** Parameters found in function ajax_scan_website(): {"post": ["email"]} **/
function ajax_scan_website() {
		check_ajax_referer( 'wpconsent_admin', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

		// Reset aggregated results.
		$this->aggregated_scripts  = array();
		$this->aggregated_services = array();

		// Scan homepage.
		$response = $this->perform_scan( home_url( '/' ) );

		// Check if we have an error from the scanner.
		if ( isset( $response['error'] ) && $response['error'] ) {
			$response['message'] = $response['error_message'];
		} else {
			$response['message'] = $this->get_message( $response );
			$this->save_scan_data( $response );

			if ( ! empty( $email ) ) {
				$this->save_email( $email );
			}
		}

		wp_send_json_success( $response );
	}


/** Function wpconsent_ajax_complete_onboarding() called by wp_ajax hooks: {'wpconsent_complete_onboarding'} **/
/** No params detected :-/ **/


/** Function wpconsent_ajax_search_content() called by wp_ajax hooks: {'wpconsent_search_content'} **/
/** Parameters found in function wpconsent_ajax_search_content(): {"post": ["search"]} **/
function wpconsent_ajax_search_content() {
	check_ajax_referer( 'wpconsent_admin', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error(
			array(
				'message' => esc_html__( 'You do not have permission to perform this action.', 'wpconsent-cookies-banner-privacy-suite' ),
			)
		);
	}

	$search = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '';

	// Query both posts and pages with search term and order by relevance.
	$posts = get_posts(
		array(
			's'              => $search,
			'post_type'      => array( 'post', 'page' ),
			'posts_per_page' => 10,
			'post_status'    => 'publish',
			'orderby'        => 'relevance',
		)
	);

	// Format the results to match the choices.js format.
	$results = array();
	foreach ( $posts as $post ) {
		$results[] = array(
			'value' => $post->ID,
			'label' => sprintf( '%1$s (#%2$d)', $post->post_title, $post->ID ),
			'url'   => get_permalink( $post->ID ),
		);
	}

	wp_send_json_success( $results );
}


/** Function wpconsent_ajax_delete_cookie() called by wp_ajax hooks: {'wpconsent_delete_cookie'} **/
/** Parameters found in function wpconsent_ajax_delete_cookie(): {"post": ["cookie_id"]} **/
function wpconsent_ajax_delete_cookie() {
	check_ajax_referer( 'wpconsent_admin', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error(
			array(
				'message' => esc_html__( 'You do not have permission to perform this action.', 'wpconsent-cookies-banner-privacy-suite' ),
			)
		);
	}

	$cookie_id = isset( $_POST['cookie_id'] ) ? intval( $_POST['cookie_id'] ) : 0;

	if ( empty( $cookie_id ) ) {
		wp_send_json_error( array(
			'message' => esc_html__( 'Cookie ID is required.', 'wpconsent-cookies-banner-privacy-suite' ),
		) );
	}

	$deleted = wpconsent()->cookies->delete_cookie( $cookie_id );

	if ( $deleted ) {
		wp_send_json_success( array(
			'message' => esc_html__( 'Cookie deleted successfully.', 'wpconsent-cookies-banner-privacy-suite' ),
		) );
	} else {
		wp_send_json_error( array(
			'message' => esc_html__( 'Failed to delete cookie.', 'wpconsent-cookies-banner-privacy-suite' ),
		) );
	}
}


/** Function ajax_toggle_setting() called by wp_ajax hooks: {'wpconsent_toggle_setting'} **/
/** Parameters found in function ajax_toggle_setting(): {"post": ["setting"]} **/
function ajax_toggle_setting() {
		check_ajax_referer( 'wpconsent_admin', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'You do not have permission to perform this action.', 'wpconsent-cookies-banner-privacy-suite' ),
			) );
		}

		$setting = isset( $_POST['setting'] ) ? sanitize_text_field( wp_unslash( $_POST['setting'] ) ) : '';

		if ( ! in_array( $setting, $this->get_toggleable_settings(), true ) ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'Invalid setting.', 'wpconsent-cookies-banner-privacy-suite' ),
			) );
		}

		wpconsent()->settings->update_option( $setting, true );

		wp_send_json_success( array(
			'setting' => $setting,
			'value'   => true,
		) );
	}


/** Function wpconsent_ajax_save_scanner_items() called by wp_ajax hooks: {'wpconsent_save_scanner_items'} **/
/** Parameters found in function wpconsent_ajax_save_scanner_items(): {"post": ["items"]} **/
function wpconsent_ajax_save_scanner_items() {
	check_ajax_referer( 'wpconsent_admin', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error(
			array(
				'message' => esc_html__( 'You do not have permission to perform this action.', 'wpconsent-cookies-banner-privacy-suite' ),
			)
		);
	}

	$items = isset( $_POST['items'] ) ? array_map( 'intval', wp_unslash( $_POST['items'] ) ) : array();

	// Remove duplicates and ensure all values are integers.
	$unique_items = array_unique( $items );

	// Save the items using WPConsent settings API.
	wpconsent()->settings->update_option( 'manual_scan_pages', $unique_items );

	wp_send_json_success(
		array(
			'message' => esc_html__( 'Items saved successfully.', 'wpconsent-cookies-banner-privacy-suite' ),
			'items'   => $unique_items,
		)
	);
}


