<?php
/***
*
*Found actions: 79
*Found functions:79
*Extracted functions:66
*Total parameter names extracted: 48
*Overview: {'handle_license_activation': {'seedprod_lite_v2_save_api_key'}, 'seedprod_lite_v2_get_saved_templates': {'seedprod_lite_v2_get_saved_templates'}, 'seedprod_lite_run_one_click_upgrade': {'nopriv_seedprod_lite_run_one_click_upgrade'}, 'seedprod_lite_v2_review_dismiss': {'seedprod_v2_review_dismiss'}, 'seedprod_lite_get_widget_wpresults': {'seedprod_lite_get_widget_wpresults'}, 'seedprod_lite_get_rafflepress': {'seedprod_lite_get_rafflepress'}, 'seedprod_lite_get_woocommerce_product_attribute_terms': {'seedprod_lite_get_woocommerce_product_attribute_terms'}, 'seedprod_lite_install_addon': {'seedprod_lite_install_addon'}, 'seedprod_lite_get_post_custom_keys_array': {'seedprod_lite_get_post_custom_keys_array'}, 'seedprod_lite_get_stockimages': {'seedprod_lite_get_stockimages'}, 'seedprod_lite_plugin_nonce': {'seedprod_lite_plugin_nonce'}, 'seedprod_lite_get_mypaykit_code': {'seedprod_lite_get_mypaykit_code'}, 'seedprod_lite_get_woocommerce_product_attributes': {'seedprod_lite_get_woocommerce_product_attributes'}, 'seedprod_lite_slug_exists': {'seedprod_lite_slug_exists'}, 'seedprod_lite_delete_archived_lpages': {'seedprod_lite_delete_archived_lpages'}, 'seedprod_lite_get_rafflepress_code': {'seedprod_lite_get_rafflepress_code'}, 'seedprod_lite_v2_get_subscribers_datatable': {'seedprod_lite_v2_get_subscribers_datatable'}, 'seedprod_lite_get_lpage_list': {'seedprod_lite_get_lpage_list'}, 'seedprod_lite_v2_install_plugin': {'seedprod_lite_v2_install_plugin'}, 'seedprod_lite_v2_deactivate_plugin': {'seedprod_lite_v2_deactivate_plugin'}, 'seedprod_lite_v2_deactivate_api_key': {'seedprod_lite_v2_deactivate_api_key'}, 'seedprod_lite_v2_delete_lpage': {'seedprod_lite_v2_delete_lpage'}, 'seedprod_lite_v2_save_app_settings': {'seedprod_lite_v2_save_app_settings'}, 'seedprod_lite_v2_trash_lpage': {'seedprod_lite_v2_trash_lpage'}, 'seedprod_lite_get_woocommerce_product_taxonomy': {'seedprod_lite_get_woocommerce_product_taxonomy'}, 'seedprod_lite_v2_export_subscribers': {'seedprod_lite_v2_export_subscribers'}, 'seedprod_lite_lpage_datatable': {'seedprod_lite_lpage_datatable'}, 'dismiss': {'seedprod_lite_notification_dismiss'}, 'seedprod_lite_deactivate_addon': {'seedprod_lite_deactivate_addon'}, 'seedprod_lite_v2_check_wizard_availability': {'seedprod_lite_v2_check_wizard_availability'}, 'seedprod_lite_get_envira_galleries': {'seedprod_lite_get_envira_galleries'}, 'seedprod_lite_get_wpforms': {'seedprod_lite_get_wpforms'}, 'seedprod_lite_get_plugins_list': {'seedprod_lite_get_plugins_list'}, 'seedprod_lite_v2_get_redirect_url': {'seedprod_lite_v2_get_redirect_url'}, 'seedprod_lite_save_app_settings': {'seedprod_lite_save_app_settings'}, 'seedprod_lite_v2_save_settings': {'seedprod_lite_v2_save_settings'}, 'seedprod_lite_save_lpage': {'seedprod_lite_save_lpage'}, 'seedprod_lite_v2_duplicate_lpage': {'seedprod_lite_v2_duplicate_lpage'}, 'seedprod_lite_v2_remove_post': {'seedprod_lite_v2_remove_post'}, 'seedprod_lite_get_namespaced_custom_css': {'seedprod_lite_get_namespaced_custom_css'}, 'seedprod_lite_v2_toggle_favorite_template': {'seedprod_lite_v2_toggle_favorite_template'}, 'seedprod_lite_activate_addon': {'seedprod_lite_activate_addon'}, 'seedprod_lite_get_widget_wpforms': {'seedprod_lite_get_widget_wpforms'}, 'seedprod_lite_v2_get_templates': {'seedprod_lite_v2_get_templates'}, 'seedprod_lite_v2_delete_subscribers': {'seedprod_lite_v2_delete_subscribers'}, 'seedprod_lite_save_template': {'seedprod_lite_save_template'}, 'seedprod_lite_save_api_key': {'seedprod_lite_save_api_key'}, 'seedprod_lite_v2_get_plugins_list': {'seedprod_lite_v2_get_plugins_list'}, 'seedprod_lite_update_subscriber_count': {'seedprod_lite_update_subscriber_count'}, 'seedprod_lite_get_revisisons': {'seedprod_lite_get_revisions'}, 'seedprod_lite_unarchive_selected_lpages': {'seedprod_lite_unarchive_selected_lpages'}, 'seedprod_lite_get_edd_downloads': {'seedprod_lite_get_edd_downloads'}, 'seedprod_lite_v2_create_page_from_template': {'seedprod_lite_v2_create_page_from_template'}, 'seedprod_lite_duplicate_lpage': {'seedprod_lite_duplicate_lpage'}, 'seedprod_lite_v2_subscribe_free_templates': {'seedprod_lite_v2_subscribe_free_templates'}, 'seedprod_lite_v2_delete_saved_template': {'seedprod_lite_v2_delete_saved_template'}, 'seedprod_lite_archive_selected_lpages': {'seedprod_lite_archive_selected_lpages'}, 'seedprod_lite_v2_dismiss_setup_wizard': {'seedprod_lite_v2_dismiss_setup_wizard'}, 'seedprod_lite_v2_get_favorite_templates': {'seedprod_lite_v2_get_favorite_templates'}, 'seedprod_lite_upgrade_license': {'seedprod_lite_upgrade_license'}, 'seedprod_lite_get_mypaykit': {'seedprod_lite_get_mypaykit'}, 'seedprod_lite_get_utc_offset': {'seedprod_lite_get_utc_offset'}, 'seedprod_lite_v2_export_landing_pages': {'seedprod_lite_v2_export_landing_pages'}, 'seedprod_lite_dismiss_settings_lite_cta': {'seedprod_lite_dismiss_settings_lite_cta'}, 'seedprod_lite_v2_complete_setup_wizard': {'seedprod_lite_v2_complete_setup_wizard'}, 'seedprod_lite_v2_bulk_action_lpages': {'seedprod_lite_v2_bulk_action_lpages'}, 'seedprod_upgrade_license': {'seedprod_upgrade_license'}, 'seedprod_lite_template_subscribe': {'seedprod_lite_template_subscribe'}, 'seedprod_lite_v2_activate_plugin': {'seedprod_lite_v2_activate_plugin'}, 'seedprod_lite_get_wpform': {'seedprod_lite_get_wpform'}, 'seedprod_lite_get_edd_download_taxonomy': {'seedprod_lite_get_edd_download_taxonomy'}, 'seedprod_lite_v2_restore_lpage': {'seedprod_lite_v2_restore_lpage'}, 'seedprod_lite_save_settings': {'seedprod_lite_save_settings'}, 'seedprod_lite_subscribers_datatable': {'seedprod_lite_subscribers_datatable'}, 'seedprod_lite_get_woocommerce_products': {'seedprod_lite_get_woocommerce_products'}, 'seedprod_lite_dismiss_upsell': {'seedprod_lite_dismiss_upsell'}, 'seedprod_lite_v2_import_landing_pages': {'seedprod_lite_v2_import_landing_pages'}, 'seedprod_lite_v2_install_addon_setup': {'seedprod_lite_v2_install_addon_setup'}, 'seedprod_lite_import_cross_site_paste': {'seedprod_lite_import_cross_site_paste'}}
*
***/

/** Function handle_license_activation() called by wp_ajax hooks: {'seedprod_lite_v2_save_api_key'} **/
/** No params detected :-/ **/


/** Function seedprod_lite_v2_get_saved_templates() called by wp_ajax hooks: {'seedprod_lite_v2_get_saved_templates'} **/
/** No params detected :-/ **/


/** Function seedprod_lite_run_one_click_upgrade() called by wp_ajax hooks: {'nopriv_seedprod_lite_run_one_click_upgrade'} **/
/** Parameters found in function seedprod_lite_run_one_click_upgrade(): {"request": ["oth", "file"]} **/
function seedprod_lite_run_one_click_upgrade() {
	$error = esc_html__( 'Could not install upgrade. Please download from seedprod.com and install manually.', 'coming-soon' );

	// verify params present (oth & download link).
	$post_oth = ! empty( $_REQUEST['oth'] ) ? sanitize_text_field( $_REQUEST['oth'] ) : '';
	$post_url = ! empty( $_REQUEST['file'] ) ? $_REQUEST['file'] : '';
	if ( empty( $post_oth ) || empty( $post_url ) ) {
		wp_send_json_error( $error );
	}
	// Verify oth.
	$oth = get_option( 'seedprod_one_click_upgrade' );
	if ( empty( $oth ) ) {
		wp_send_json_error( $error );
	}
	if ( hash_hmac( 'sha512', $oth, wp_salt() ) !== $post_oth ) {
		wp_send_json_error( $error );
	}
	// Delete so cannot replay.
	delete_option( 'seedprod_one_click_upgrade' );
	// Set the current screen to avoid undefined notices.
	set_current_screen( 'insights_page_seedprod_settings' );
	// Prepare variables.
	$url = esc_url_raw(
		add_query_arg(
			array(
				'page' => 'seedprod-settings',
			),
			admin_url( 'admin.php' )
		)
	);
	// Verify pro not activated.
	if ( is_plugin_active( 'seedprod-coming-soon-pro-5/seedprod-coming-soon-pro-5.php' ) ) {
		deactivate_plugins( plugin_basename( 'coming-soon/coming-soon.php' ) );
		wp_send_json_success( esc_html__( 'Plugin installed & activated.', 'coming-soon' ) );
	}
	// Verify pro not installed.
	$active = activate_plugin( 'seedprod-coming-soon-pro-5/seedprod-coming-soon-pro-5.php', $url, false, true );
	if ( ! is_wp_error( $active ) ) {
		deactivate_plugins( plugin_basename( 'coming-soon/coming-soon.php' ) );
		wp_send_json_success( esc_html__( 'Plugin installed & activated.', 'coming-soon' ) );
	}

	$creds = request_filesystem_credentials( $url, '', false, false, null );
	// Check for file system permissions.
	if ( false === $creds ) {
		wp_send_json_error( $error );
	}
	if ( ! WP_Filesystem( $creds ) ) {
		wp_send_json_error( $error );
	}
	// We do not need any extra credentials if we have gotten this far, so let's install the plugin.
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

	if ( version_compare( $wp_version, '5.3.0' ) >= 0 ) {
		require_once SEEDPROD_PLUGIN_PATH . 'app/includes/skin53.php';
	} else {
		require_once SEEDPROD_PLUGIN_PATH . 'app/includes/skin.php';
	}
	// Do not allow WordPress to search/download translations, as this will break JS output.
	remove_action( 'upgrader_process_complete', array( 'Language_Pack_Upgrader', 'async_upgrade' ), 20 );
	// Create the plugin upgrader with our custom skin.
	$installer = new Plugin_Upgrader( $skin = new SeedProd_Skin() );
	// Error check.
	if ( ! method_exists( $installer, 'install' ) ) {
		wp_send_json_error( $error );
	}

	// Check license key.
	$license_key = seedprod_lite_get_api_key();
	if ( empty( $license_key ) ) {
		wp_send_json_error( new WP_Error( '403', esc_html__( 'You are not licensed.', 'coming-soon' ) ) );
	}

	$license = seedprod_lite_save_api_key( $license_key );
	if ( empty( $license['body']->download_link ) ) {
		wp_send_json_error();
	}

    $installer->install($license['body']->download_link); // phpcs:ignore
	// Flush the cache and return the newly installed plugin basename.
	wp_cache_flush();
	if ( $installer->plugin_info() ) {
		$plugin_basename = $installer->plugin_info();

		// Deactivate the lite version first.
		deactivate_plugins( plugin_basename( 'coming-soon/coming-soon.php' ) );

		// Activate the plugin silently.
		$activated = activate_plugin( $plugin_basename, '', false, true );
		if ( ! is_wp_error( $activated ) ) {
			wp_send_json_success( esc_html__( 'Plugin installed & activated.', 'coming-soon' ) );
		} else {
			// Reactivate the lite plugin if pro activation failed.
			activate_plugin( plugin_basename( 'coming-soon/coming-soon.php' ), '', false, true );
			wp_send_json_error( esc_html__( 'Pro version installed but needs to be activated from the Plugins page inside your WordPress admin.', 'coming-soon' ) );
		}
	}
	wp_send_json_error( $error );
}


/** Function seedprod_lite_v2_review_dismiss() called by wp_ajax hooks: {'seedprod_v2_review_dismiss'} **/
/** Parameters found in function seedprod_lite_v2_review_dismiss(): {"post": ["permanent"]} **/
function seedprod_lite_v2_review_dismiss() {
	// Verify nonce for security.
	check_ajax_referer( 'seedprod_review_dismiss', 'nonce' );

	// Security check - verify user capability.
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die();
	}

	// Check if this is a permanent dismissal (sanitize input).
	$permanent = isset( $_POST['permanent'] ) && 'true' === sanitize_text_field( wp_unslash( $_POST['permanent'] ) );

	if ( $permanent ) {
		// User already reviewed - don't ask again.
		update_option( 'seedprod_hide_review', true );
	} else {
		// Temporary dismissal - ask again later.
		$review              = get_option( 'seedprod_review', array() );
		$review['time']      = time();
		$review['dismissed'] = true;
		update_option( 'seedprod_review', $review );
	}

	wp_die();
}


/** Function seedprod_lite_get_widget_wpresults() called by wp_ajax hooks: {'seedprod_lite_get_widget_wpresults'} **/
/** No function found :-/ **/


/** Function seedprod_lite_get_rafflepress() called by wp_ajax hooks: {'seedprod_lite_get_rafflepress'} **/
/** No params detected :-/ **/


/** Function seedprod_lite_get_woocommerce_product_attribute_terms() called by wp_ajax hooks: {'seedprod_lite_get_woocommerce_product_attribute_terms'} **/
/** No function found :-/ **/


/** Function seedprod_lite_install_addon() called by wp_ajax hooks: {'seedprod_lite_install_addon'} **/
/** Parameters found in function seedprod_lite_install_addon(): {"post": ["plugin", "referrer"]} **/
function seedprod_lite_install_addon() {
	// Run a security check.
	check_ajax_referer( 'seedprod_lite_install_addon', 'nonce' );

	// Check for permissions.
	if ( ! current_user_can( 'install_plugins' ) ) {
		wp_send_json_error();
	}

	// Install the addon.
	if ( isset( $_POST['plugin'] ) ) {
		$download_url = sanitize_text_field( wp_unslash( $_POST['plugin'] ) );

		global $hook_suffix;

		// Set the current screen to avoid undefined notices.
		set_current_screen();

		// Prepare variables.
		$method = '';
		$url    = add_query_arg(
			array(
				'page' => 'seedprod_lite',
			),
			admin_url( 'admin.php' )
		);
		$url    = esc_url( $url );

		// Start output bufferring to catch the filesystem form if credentials are needed.
		ob_start();
		$creds = request_filesystem_credentials( $url, $method, false, false, null );
		if ( false === $creds ) {
			$form = ob_get_clean();
			echo wp_json_encode( array( 'form' => $form ) );
			wp_die();
		}

		// If we are not authenticated, make it happen now.
		if ( ! WP_Filesystem( $creds ) ) {
			ob_start();
			request_filesystem_credentials( $url, $method, true, false, null );
			$form = ob_get_clean();
			echo wp_json_encode( array( 'form' => $form ) );
			wp_die();
		}

		// We do not need any extra credentials if we have gotten this far, so let's install the plugin.
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		global $wp_version;
		if ( version_compare( $wp_version, '5.3.0' ) >= 0 ) {
			require_once SEEDPROD_PLUGIN_PATH . 'app/includes/skin53.php';
		} else {
			require_once SEEDPROD_PLUGIN_PATH . 'app/includes/skin.php';
		}

		// Create the plugin upgrader with our custom skin.
		$installer = new Plugin_Upgrader( new SeedProd_Skin() );
		$installer->install( $download_url );

		// Set referrer if one exists.
		if ( ! empty( $_POST['referrer'] ) ) {
			$referrer = sanitize_text_field( wp_unslash( $_POST['referrer'] ) );
			update_option( 'optinmonster_referred_by', $referrer );
		}

		// Flush the cache and return the newly installed plugin basename.
		wp_cache_flush();
		if ( $installer->plugin_info() ) {
			$plugin_basename = $installer->plugin_info();
			echo wp_json_encode( array( 'plugin' => $plugin_basename ) );
			wp_die();
		}
	}

	// Send back a response.
	echo wp_json_encode( true );
	wp_die();
}


/** Function seedprod_lite_get_post_custom_keys_array() called by wp_ajax hooks: {'seedprod_lite_get_post_custom_keys_array'} **/
/** Parameters found in function seedprod_lite_get_post_custom_keys_array(): {"post": ["id"]} **/
function seedprod_lite_get_post_custom_keys_array() {
	if ( check_ajax_referer( 'seedprod_nonce' ) ) {
		if ( ! current_user_can( apply_filters( 'seedprod_builder_preview_render_capability', 'edit_others_posts' ) ) ) {
			wp_send_json_error();
		}

		$id = isset( $_POST['id'] ) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : '';

		$custom_keys = array();
		$custom_keys = get_post_custom_keys( $id );

		// Fetch the keys from the current post.
		// Notes: Not accurate because of how our theme builder works.

		// $args = array(
		// 'posts_per_page' => 1,
		// 'post_type'      => 'post',
		// );

		// $the_query   = new WP_Query( $args );
		// $custom_keys = array();

		// if ( $the_query->have_posts() ) {
		// while ( $the_query->have_posts() ) {
		// $the_query->the_post();
		// $custom_keys = get_post_custom_keys();
		// }
		// }

		/* Restore original Post Data */
		wp_reset_postdata();

		$options = array(
			'' => esc_html__( 'Select Key', 'coming-soon' ),
		);

		if ( ! empty( $custom_keys ) ) {
			foreach ( $custom_keys as $custom_key ) {
				if ( '_' !== substr( $custom_key, 0, 1 ) ) {
					$options[ $custom_key ] = $custom_key;
				}
			}
		}

		echo wp_kses( wp_json_encode( $options ), 'post' );
		exit;
	}
	exit;
}


/** Function seedprod_lite_get_stockimages() called by wp_ajax hooks: {'seedprod_lite_get_stockimages'} **/
/** No function found :-/ **/


/** Function seedprod_lite_plugin_nonce() called by wp_ajax hooks: {'seedprod_lite_plugin_nonce'} **/
/** Parameters found in function seedprod_lite_plugin_nonce(): {"post": ["plugin"]} **/
function seedprod_lite_plugin_nonce() {
	check_ajax_referer( 'seedprod_lite_plugin_nonce', 'nonce' );

	if ( ! current_user_can( 'install_plugins' ) ) {
		wp_send_json_error();
	}

	$plugin = ! empty( $_POST['plugin'] ) ? sanitize_text_field( wp_unslash( $_POST['plugin'] ) ) : null;

	$install_plugin_nonce = wp_create_nonce( 'install-plugin_' . sanitize_text_field( $plugin ) );

	wp_send_json( $install_plugin_nonce );
}


/** Function seedprod_lite_get_mypaykit_code() called by wp_ajax hooks: {'seedprod_lite_get_mypaykit_code'} **/
/** No params detected :-/ **/


/** Function seedprod_lite_get_woocommerce_product_attributes() called by wp_ajax hooks: {'seedprod_lite_get_woocommerce_product_attributes'} **/
/** No function found :-/ **/


/** Function seedprod_lite_slug_exists() called by wp_ajax hooks: {'seedprod_lite_slug_exists'} **/
/** Parameters found in function seedprod_lite_slug_exists(): {"post": ["post_name"]} **/
function seedprod_lite_slug_exists() {
	if ( check_ajax_referer( 'seedprod_lite_slug_exists' ) ) {
		if ( ! current_user_can( apply_filters( 'seedprod_lpage_capability', 'edit_others_posts' ) ) ) {
			wp_send_json_error();
		}
		$post_name = isset( $_POST['post_name'] ) ? sanitize_text_field( wp_unslash( $_POST['post_name'] ) ) : '';
		global $wpdb;
		$tablename = $wpdb->prefix . 'posts';
		$sql       = "SELECT post_name FROM $tablename";
		$sql      .= ' WHERE post_name = %s';
		$safe_sql  = $wpdb->prepare( $sql, $post_name ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$result    = $wpdb->get_var( $safe_sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		if ( empty( $result ) ) {
			wp_send_json_success();
		} else {
			wp_send_json_error();
		}
	}
}


/** Function seedprod_lite_delete_archived_lpages() called by wp_ajax hooks: {'seedprod_lite_delete_archived_lpages'} **/
/** Parameters found in function seedprod_lite_delete_archived_lpages(): {"get": ["ids"]} **/
function seedprod_lite_delete_archived_lpages() {
	if ( check_ajax_referer( 'seedprod_lite_delete_archived_lpages' ) ) {
		if ( current_user_can( apply_filters( 'seedprod_archive_pages_capability', 'list_users' ) ) ) {
			if ( ! empty( $_GET['ids'] ) ) {
				$ids = array_map( 'intval', explode( ',', sanitize_text_field( wp_unslash( $_GET['ids'] ) ) ) );
				foreach ( $ids as $v ) {
					wp_delete_post( $v );
				}

				wp_send_json( array( 'status' => true ) );
			}
		}
	}
}


/** Function seedprod_lite_get_rafflepress_code() called by wp_ajax hooks: {'seedprod_lite_get_rafflepress_code'} **/
/** No params detected :-/ **/


/** Function seedprod_lite_v2_get_subscribers_datatable() called by wp_ajax hooks: {'seedprod_lite_v2_get_subscribers_datatable'} **/
/** Parameters found in function seedprod_lite_v2_get_subscribers_datatable(): {"post": ["page", "per_page", "search", "page_filter", "sort_by", "sort_order"]} **/
function seedprod_lite_v2_get_subscribers_datatable() {
	if ( check_ajax_referer( 'seedprod_v2_nonce', 'nonce', false ) ) {
		if ( ! current_user_can( apply_filters( 'seedprod_subscriber_capability', 'list_users' ) ) ) {
			wp_send_json_error( __( 'You do not have permission to view subscribers.', 'coming-soon' ) );
		}

		global $wpdb;
		$tablename = $wpdb->prefix . 'csp3_subscribers';

		// Get parameters.
		$page        = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
		$per_page    = isset( $_POST['per_page'] ) ? absint( $_POST['per_page'] ) : 25;
		$search      = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '';
		$page_filter = isset( $_POST['page_filter'] ) ? sanitize_text_field( wp_unslash( $_POST['page_filter'] ) ) : 'all';
		$sort_by     = isset( $_POST['sort_by'] ) ? sanitize_text_field( wp_unslash( $_POST['sort_by'] ) ) : 'created';
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Validated against allowed values (ASC/DESC) immediately after.
		$sort_order = isset( $_POST['sort_order'] ) && in_array( strtoupper( wp_unslash( $_POST['sort_order'] ) ), array( 'ASC', 'DESC' ), true ) ? strtoupper( wp_unslash( $_POST['sort_order'] ) ) : 'DESC';

		// Build query.
		$where_clauses = array( '1=1' );

		if ( ! empty( $search ) ) {
			$where_clauses[] = $wpdb->prepare( '(email LIKE %s OR fname LIKE %s OR lname LIKE %s)', '%' . $wpdb->esc_like( $search ) . '%', '%' . $wpdb->esc_like( $search ) . '%', '%' . $wpdb->esc_like( $search ) . '%' );
		}

		if ( 'all' !== $page_filter && ! empty( $page_filter ) ) {
			$where_clauses[] = $wpdb->prepare( 'page_uuid = %s', $page_filter );
		}

		$where_sql = implode( ' AND ', $where_clauses );

		// Get total count.
		$total_sql = "SELECT COUNT(*) FROM $tablename WHERE $where_sql";
		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- $where_sql built with wpdb->prepare() calls above, custom table query.
		$total_items = $wpdb->get_var( $total_sql );

		// Get subscribers.
		$offset = ( $page - 1 ) * $per_page;

		// Validate sort column.
		$allowed_sort_columns = array( 'email', 'fname', 'lname', 'created', 'page_id' );
		if ( ! in_array( $sort_by, $allowed_sort_columns, true ) ) {
			$sort_by = 'created';
		}

		$sql = "SELECT *, UNIX_TIMESTAMP(created) as created_timestamp
				FROM $tablename
				WHERE $where_sql
				ORDER BY $sort_by $sort_order
				LIMIT %d OFFSET %d";

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- $where_sql built with wpdb->prepare() calls above, $sort_by validated against allowed columns.
		$safe_sql = $wpdb->prepare( $sql, $per_page, $offset );
		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Query prepared above, custom table, transient data.
		$results = $wpdb->get_results( $safe_sql );

		// Format results.
		$subscribers = array();
		foreach ( $results as $row ) {
			$subscribers[] = array(
				'id'        => $row->id,
				'email'     => $row->email,
				'name'      => trim( $row->fname . ' ' . $row->lname ),
				'fname'     => $row->fname,
				'lname'     => $row->lname,
				'created'   => get_date_from_gmt( gmdate( 'Y-m-d H:i:s', $row->created_timestamp ), get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) ),
				'page_id'   => $row->page_id,
				'page_uuid' => $row->page_uuid,
			);
		}

		// Get pages for filter dropdown.
		$pages = seedprod_lite_v2_get_subscriber_pages();

		// Get chart data for last 7 days.
		$chart_data = seedprod_lite_v2_get_subscriber_chart_data( $page_filter );

		wp_send_json_success(
			array(
				'subscribers'  => $subscribers,
				'total'        => $total_items,
				'pages'        => ceil( $total_items / $per_page ),
				'current_page' => $page,
				'per_page'     => $per_page,
				'chart_data'   => $chart_data,
				'page_list'    => $pages,
			)
		);
	}
	wp_send_json_error();
}


/** Function seedprod_lite_get_lpage_list() called by wp_ajax hooks: {'seedprod_lite_get_lpage_list'} **/
/** No params detected :-/ **/


/** Function seedprod_lite_v2_install_plugin() called by wp_ajax hooks: {'seedprod_lite_v2_install_plugin'} **/
/** Parameters found in function seedprod_lite_v2_install_plugin(): {"post": ["plugin_id"]} **/
function seedprod_lite_v2_install_plugin() {
	// Clean any output that might have been generated.
	if ( ob_get_length() ) {
		ob_clean();
	}

	// Start output buffering to catch any unexpected output.
	ob_start();

	// Suppress error display for this request.
	$display_errors = ini_get( 'display_errors' );
	@ini_set( 'display_errors', 0 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged,WordPress.PHP.IniSet.display_errors_Blacklisted -- Temporarily suppress errors during plugin installation.

	// Security check.
	check_ajax_referer( 'seedprod_nonce' );

	// Check permissions.
	if ( ! current_user_can( 'install_plugins' ) ) {
		ob_clean();
		wp_send_json_error( __( 'Permission denied.', 'coming-soon' ) );
	}

	// Get plugin ID (not URL for security).
	if ( ! isset( $_POST['plugin_id'] ) ) {
		ob_clean();
		wp_send_json_error( __( 'Plugin ID required.', 'coming-soon' ) );
	}

	$plugin_id = sanitize_key( wp_unslash( $_POST['plugin_id'] ) );

	// Get the download URL from server-side mapping.
	$download_url = seedprod_lite_v2_get_plugin_download_url( $plugin_id );

	if ( ! $download_url ) {
		ob_clean();
		@ini_set( 'display_errors', $display_errors ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged,WordPress.PHP.IniSet.display_errors_Blacklisted -- Restore error display setting after plugin installation.
		wp_send_json_error( __( 'Invalid plugin.', 'coming-soon' ) );
	}

	// Set current screen.
	set_current_screen();

	// Prepare variables.
	$method = '';
	$url    = add_query_arg(
		array( 'page' => 'seedprod_lite' ),
		admin_url( 'admin.php' )
	);

	// Handle filesystem credentials (nested output buffering).
	$creds = request_filesystem_credentials( $url, $method, false, false, null );
	if ( false === $creds ) {
		$form = ob_get_contents();
		ob_clean();
		@ini_set( 'display_errors', $display_errors ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged,WordPress.PHP.IniSet.display_errors_Blacklisted -- Restore error display setting after plugin installation.
		wp_send_json_error( array( 'form' => $form ) );
	}

	// Check filesystem authentication.
	if ( ! WP_Filesystem( $creds ) ) {
		request_filesystem_credentials( $url, $method, true, false, null );
		$form = ob_get_contents();
		ob_clean();
		@ini_set( 'display_errors', $display_errors ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged,WordPress.PHP.IniSet.display_errors_Blacklisted -- Restore error display setting after plugin installation.
		wp_send_json_error( array( 'form' => $form ) );
	}

	// Include required files.
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

	// Load appropriate skin based on WordPress version.
	global $wp_version;
	if ( version_compare( $wp_version, '5.3.0' ) >= 0 ) {
		require_once SEEDPROD_PLUGIN_PATH . 'admin/includes/skin53.php';
	} else {
		require_once SEEDPROD_PLUGIN_PATH . 'admin/includes/skin.php';
	}

	// Temporarily disable translation updates during plugin installation.
	add_filter( 'auto_update_translation', '__return_false' );
	remove_action( 'upgrader_process_complete', array( 'Language_Pack_Upgrader', 'async_upgrade' ), 20 );

	// Install plugin.
	$installer = new Plugin_Upgrader( new SeedProd_Skin() );
	$result    = $installer->install( $download_url );

	// Re-enable translation updates.
	remove_filter( 'auto_update_translation', '__return_false' );
	add_action( 'upgrader_process_complete', array( 'Language_Pack_Upgrader', 'async_upgrade' ), 20 );

	// Clear cache.
	wp_cache_flush();

	if ( $installer->plugin_info() ) {
		// Verify the installed plugin is what we expected.
		$plugin_file = $installer->plugin_info();
		$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_file );

		// Validate plugin is from our allowed list.
		if ( ! seedprod_lite_v2_verify_installed_plugin( $plugin_id, $plugin_data ) ) {
			// Remove potentially malicious plugin.
			deactivate_plugins( $plugin_file );
			delete_plugins( array( $plugin_file ) );
			ob_clean();
			@ini_set( 'display_errors', $display_errors ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged,WordPress.PHP.IniSet.display_errors_Blacklisted -- Restore error display setting after plugin installation.
			wp_send_json_error( __( 'Plugin verification failed. The installed plugin was removed.', 'coming-soon' ) );
		}

		// Clear any output before sending success.
		ob_clean();
		@ini_set( 'display_errors', $display_errors ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged,WordPress.PHP.IniSet.display_errors_Blacklisted -- Restore error display setting after plugin installation.
		wp_send_json_success(
			array(
				'plugin'  => $installer->plugin_info(),
				'message' => __( 'Plugin installed successfully.', 'coming-soon' ),
			)
		);
	} else {
		// Clear any output before sending error.
		ob_clean();
		@ini_set( 'display_errors', $display_errors ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged,WordPress.PHP.IniSet.display_errors_Blacklisted -- Restore error display setting after plugin installation.
		wp_send_json_error( __( 'Plugin installation failed.', 'coming-soon' ) );
	}
}


/** Function seedprod_lite_v2_deactivate_plugin() called by wp_ajax hooks: {'seedprod_lite_v2_deactivate_plugin'} **/
/** Parameters found in function seedprod_lite_v2_deactivate_plugin(): {"post": ["plugin"]} **/
function seedprod_lite_v2_deactivate_plugin() {
	// Clean any output that might have been generated.
	if ( ob_get_length() ) {
		ob_clean();
	}

	// Start output buffering to catch any unexpected output.
	ob_start();

	// Security check.
	check_ajax_referer( 'seedprod_nonce' );

	// Check permissions.
	if ( ! current_user_can( 'deactivate_plugins' ) ) {
		ob_clean();
		wp_send_json_error( __( 'Permission denied.', 'coming-soon' ) );
	}

	// Get plugin slug.
	if ( ! isset( $_POST['plugin'] ) ) {
		ob_clean();
		wp_send_json_error( __( 'Plugin slug required.', 'coming-soon' ) );
	}

	$plugin = sanitize_text_field( wp_unslash( $_POST['plugin'] ) );
	deactivate_plugins( $plugin );

	// Clear any output before sending response.
	ob_clean();
	wp_send_json_success( __( 'Plugin deactivated.', 'coming-soon' ) );
}


/** Function seedprod_lite_v2_deactivate_api_key() called by wp_ajax hooks: {'seedprod_lite_v2_deactivate_api_key'} **/
/** No function found :-/ **/


/** Function seedprod_lite_v2_delete_lpage() called by wp_ajax hooks: {'seedprod_lite_v2_delete_lpage'} **/
/** Parameters found in function seedprod_lite_v2_delete_lpage(): {"post": ["id"]} **/
function seedprod_lite_v2_delete_lpage() {
	// Check nonce.
	if ( ! check_ajax_referer( 'seedprod_v2_nonce', 'nonce', false ) ) {
		wp_send_json_error( __( 'Security check failed.', 'coming-soon' ) );
	}

	// Check permissions.
	if ( ! current_user_can( apply_filters( 'seedprod_lpage_capability', 'edit_others_posts' ) ) ) {
		wp_send_json_error( __( 'You do not have permission to delete pages.', 'coming-soon' ) );
	}

	// Get the page ID.
	$id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;

	if ( ! $id ) {
		wp_send_json_error( __( 'Invalid page ID.', 'coming-soon' ) );
	}

	// Delete the post permanently.
	$result = wp_delete_post( $id, true );

	if ( ! $result ) {
		wp_send_json_error( __( 'Failed to delete page.', 'coming-soon' ) );
	}

	wp_send_json_success(
		array(
			'message' => __( 'Page deleted permanently.', 'coming-soon' ),
		)
	);
}


/** Function seedprod_lite_v2_save_app_settings() called by wp_ajax hooks: {'seedprod_lite_v2_save_app_settings'} **/
/** Parameters found in function seedprod_lite_v2_save_app_settings(): {"post": ["app_settings"]} **/
function seedprod_lite_v2_save_app_settings() {
	if ( ! check_ajax_referer( 'seedprod_settings_save', '_wpnonce', false ) ) {
		wp_send_json_error( array( 'message' => __( 'Security check failed.', 'coming-soon' ) ) );
	}

	if ( ! current_user_can( apply_filters( 'seedprod_save_app_settings_capability', 'manage_options' ) ) ) {
		wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'coming-soon' ) ) );
	}

	if ( ! empty( $_POST['app_settings'] ) ) {
		$app_settings = wp_unslash( $_POST['app_settings'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		// Get existing settings first.
		$existing_settings_json = get_option( 'seedprod_app_settings' );
		$existing_settings      = ! empty( $existing_settings_json ) ? json_decode( $existing_settings_json, true ) : array();

		// Merge with existing settings to preserve any settings not in this form.
		$new_app_settings = is_array( $existing_settings ) ? $existing_settings : array();

		// Edit Button - properly handle boolean values from JavaScript.
		$new_app_settings['disable_seedprod_button'] = isset( $app_settings['disable_seedprod_button'] ) &&
			( true === $app_settings['disable_seedprod_button'] || 'true' === $app_settings['disable_seedprod_button'] || '1' === $app_settings['disable_seedprod_button'] || 1 === $app_settings['disable_seedprod_button'] );

		// Usage Tracking - properly handle boolean values from JavaScript.
		$new_app_settings['enable_usage_tracking'] = isset( $app_settings['enable_usage_tracking'] ) &&
			( true === $app_settings['enable_usage_tracking'] || 'true' === $app_settings['enable_usage_tracking'] || '1' === $app_settings['enable_usage_tracking'] || 1 === $app_settings['enable_usage_tracking'] );
		update_option( 'seedprod_allow_usage_tracking', $new_app_settings['enable_usage_tracking'] );

		// Notifications - properly handle boolean values from JavaScript.
		$new_app_settings['disable_seedprod_notification'] = isset( $app_settings['disable_seedprod_notification'] ) &&
			( true === $app_settings['disable_seedprod_notification'] || 'true' === $app_settings['disable_seedprod_notification'] || '1' === $app_settings['disable_seedprod_notification'] || 1 === $app_settings['disable_seedprod_notification'] );

		// API Keys (Pro only).
		$is_lite_view = seedprod_lite_v2_is_lite_view();
		if ( ! $is_lite_view ) {
			$new_app_settings['facebook_g_app_id']     = isset( $app_settings['facebook_g_app_id'] ) ? sanitize_text_field( $app_settings['facebook_g_app_id'] ) : '';
			$new_app_settings['google_places_app_key'] = isset( $app_settings['google_places_app_key'] ) ? sanitize_text_field( $app_settings['google_places_app_key'] ) : '';
			$new_app_settings['yelp_app_api_key']      = isset( $app_settings['yelp_app_api_key'] ) ? sanitize_text_field( $app_settings['yelp_app_api_key'] ) : '';
		}

		// Save settings.
		$app_settings_json = wp_json_encode( $new_app_settings );
		update_option( 'seedprod_app_settings', $app_settings_json );

		wp_send_json_success(
			array(
				'message' => __( 'Settings saved successfully!', 'coming-soon' ),
			)
		);
	} else {
		wp_send_json_error(
			array(
				'message' => __( 'No settings data received.', 'coming-soon' ),
			)
		);
	}
}


/** Function seedprod_lite_v2_trash_lpage() called by wp_ajax hooks: {'seedprod_lite_v2_trash_lpage'} **/
/** Parameters found in function seedprod_lite_v2_trash_lpage(): {"post": ["id"]} **/
function seedprod_lite_v2_trash_lpage() {
	// Check nonce.
	if ( ! check_ajax_referer( 'seedprod_v2_nonce', 'nonce', false ) ) {
		wp_send_json_error( __( 'Security check failed.', 'coming-soon' ) );
	}

	// Check permissions.
	if ( ! current_user_can( apply_filters( 'seedprod_lpage_capability', 'edit_others_posts' ) ) ) {
		wp_send_json_error( __( 'You do not have permission to trash pages.', 'coming-soon' ) );
	}

	// Get the page ID.
	$id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;

	if ( ! $id ) {
		wp_send_json_error( __( 'Invalid page ID.', 'coming-soon' ) );
	}

	// Trash the post.
	$result = wp_trash_post( $id );

	if ( ! $result ) {
		wp_send_json_error( __( 'Failed to trash page.', 'coming-soon' ) );
	}

	wp_send_json_success(
		array(
			'message' => __( 'Page moved to trash.', 'coming-soon' ),
		)
	);
}


/** Function seedprod_lite_get_woocommerce_product_taxonomy() called by wp_ajax hooks: {'seedprod_lite_get_woocommerce_product_taxonomy'} **/
/** No function found :-/ **/


/** Function seedprod_lite_v2_export_subscribers() called by wp_ajax hooks: {'seedprod_lite_v2_export_subscribers'} **/
/** Parameters found in function seedprod_lite_v2_export_subscribers(): {"post": ["page_filter"]} **/
function seedprod_lite_v2_export_subscribers() {
	if ( check_ajax_referer( 'seedprod_v2_nonce', 'nonce', false ) ) {
		if ( ! current_user_can( 'export' ) ) {
			wp_send_json_error( __( 'You do not have permission to export subscribers.', 'coming-soon' ) );
		}

		$page_filter = isset( $_POST['page_filter'] ) ? sanitize_text_field( wp_unslash( $_POST['page_filter'] ) ) : 'all';

		global $wpdb;
		$tablename = $wpdb->prefix . 'csp3_subscribers';

		// Build query.
		$where_sql = '1=1';
		if ( 'all' !== $page_filter && ! empty( $page_filter ) ) {
			$where_sql = $wpdb->prepare( 'page_uuid = %s', $page_filter );
		}

		// Get subscribers.
		$sql = "SELECT email, fname, lname, created, page_id, page_uuid FROM $tablename WHERE $where_sql ORDER BY created DESC";
		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- $where_sql built with wpdb->prepare() above, custom table export.
		$results = $wpdb->get_results( $sql );

		// Generate CSV content.
		$csv_data   = array();
		$csv_data[] = array( 'Email', 'First Name', 'Last Name', 'Date', 'Page ID' );

		foreach ( $results as $row ) {
			$csv_data[] = array(
				$row->email,
				$row->fname,
				$row->lname,
				$row->created,
				$row->page_id,
			);
		}

		// Convert to CSV string.
		$output = '';
		foreach ( $csv_data as $row ) {
			$output .= '"' . implode( '","', array_map( 'esc_attr', $row ) ) . '"' . "\n";
		}

		$filename = 'subscribers-' . gmdate( 'Y-m-d-His' ) . '.csv';

		wp_send_json_success(
			array(
				'csv'      => $output,
				'filename' => $filename,
				/* translators: %d: number of subscribers exported */
				'message'  => sprintf( _n( '%d subscriber exported.', '%d subscribers exported.', count( $results ), 'coming-soon' ), count( $results ) ),
			)
		);
	}
	wp_send_json_error();
}


/** Function seedprod_lite_lpage_datatable() called by wp_ajax hooks: {'seedprod_lite_lpage_datatable'} **/
/** Parameters found in function seedprod_lite_lpage_datatable(): {"get": ["current_page", "filter", "s", "orderby", "order"], "post": ["s"]} **/
function seedprod_lite_lpage_datatable() {
	if ( check_ajax_referer( 'seedprod_nonce' ) ) {
		if ( ! current_user_can( apply_filters( 'seedprod_lpage_capability', 'edit_others_posts' ) ) ) {
			wp_send_json_error();
		}
		$data         = array( '' );
		$current_page = 1;
		if ( ! empty( absint( $_GET['current_page'] ) ) ) {
			$current_page = absint( $_GET['current_page'] );
		}
		$per_page = 10;

		$filter = null;
		if ( ! empty( $_GET['filter'] ) ) {
			$filter = sanitize_text_field( wp_unslash( $_GET['filter'] ) );
			if ( 'all' == $filter ) {
				$filter = null;
			}
		}

		if ( ! empty( $_GET['s'] ) ) {
			$filter = null;
		}

		if ( ! empty( $filter ) ) {
			$post_status_compare = '=';
			if ( 'published' == $filter ) {
				$post_status = 'publish';
			}
			if ( 'drafts' == $filter ) {
				$post_status = 'draft';
			}
			if ( 'scheduled' == $filter ) {
				$post_status = 'future';
			}
			if ( 'archived' == $filter ) {
				$post_status = 'trash';
			}
		} else {
			$post_status_compare = '!=';
			$post_status         = 'trash';
		}
		$post_status_statement = ' post_status ' . $post_status_compare . ' %s ';

		if ( ! empty( $_GET['s'] ) ) {
			$search_term = '%' . trim( sanitize_text_field( wp_unslash( $_GET['s'] ) ) ) . '%';
		}

		$order_by           = 'id';
		$order_by_direction = 'DESC';
		if ( ! empty( $_GET['orderby'] ) ) {
			$orderby = sanitize_text_field( wp_unslash( $_GET['orderby'] ) );
			if ( 'date' == $orderby ) {
				$order_by = 'post_modified';
			}

			if ( 'name' == $orderby ) {
				$order_by = 'post_title';
			}

			$direction = ! empty( $_GET['order'] ) ? sanitize_text_field( wp_unslash( $_GET['order'] ) ) : null;
			if ( 'desc' == $direction ) {
				$order_by_direction = 'DESC';
			} else {
				$order_by_direction = 'ASC';
			}
		}
		$order_by_statement = 'ORDER BY ' . $order_by . ' ' . $order_by_direction;

		$offset = 0;
		if ( empty( $_POST['s'] ) ) {
			$offset = ( $current_page - 1 ) * $per_page;
		}

		// Get records.
		global $wpdb;
		$tablename      = $wpdb->prefix . 'posts';
		$meta_tablename = $wpdb->prefix . 'postmeta';

		if ( empty( $_GET['s'] ) ) {
			$sql      = 'SELECT * FROM ' . $tablename . ' p LEFT JOIN ' . $meta_tablename . " pm ON (pm.post_id = p.ID) WHERE post_type = 'page' AND meta_key = '_seedprod_page' AND " . $post_status_statement . ' ' . $order_by_statement . ' LIMIT %d OFFSET %d';
			$safe_sql = $wpdb->prepare( $sql, $post_status, $per_page, $offset ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		} else {
			$sql      = 'SELECT * FROM ' . $tablename . ' p LEFT JOIN ' . $meta_tablename . " pm ON (pm.post_id = p.ID) WHERE post_type = 'page' AND meta_key = '_seedprod_page' AND " . $post_status_statement . ' AND post_title LIKE %s ' . $order_by_statement . ' LIMIT %d OFFSET %d';
			$safe_sql = $wpdb->prepare( $sql, $post_status, $search_term, $per_page, $offset ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		}

		$results = $wpdb->get_results( $safe_sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		$login_page_id = get_option( 'seedprod_login_page_id' );
		$data          = array();
		foreach ( $results as $v ) {
			// Skip row to prevent current Login Page post from displaying here.
			if ( $v->ID === $login_page_id ) {
				continue; }

			// Format Date.
			// $modified_at = date(get_option('date_format').' '.get_option('time_format'), strtotime($v->post_modified));

			$modified_at = gmdate( get_option( 'date_format' ), strtotime( $v->post_modified ) );

			$posted_at = gmdate( get_option( 'date_format' ), strtotime( $v->post_date ) );

			$url = get_permalink( $v->ID );

			if ( 'publish' == $v->post_status ) {
				$status = 'Published';
			}
			if ( 'draft' == $v->post_status ) {
				$status = 'Draft';
			}
			if ( 'future' == $v->post_status ) {
				$status = 'Scheduled';
			}
			if ( 'trash' == $v->post_status ) {
				$status = 'Trash';
			}

			// Load Data.
			$data[] = array(
				'id'          => $v->ID,
				'name'        => $v->post_title,
				'status'      => $status,
				'post_status' => $v->post_status,
				'url'         => $url,
				'modified_at' => $modified_at,
				'posted_at'   => $posted_at,
			);
		}

		$totalitems = seedprod_lite_lpage_get_data_total( $filter );
		$views      = seedprod_lite_lpage_get_views( $filter );

		$response = array(
			'rows'        => $data,
			'totalitems'  => $totalitems,
			'totalpages'  => ceil( $totalitems / 10 ),
			'currentpage' => $current_page,
			'views'       => $views,
		);

		wp_send_json( $response );
	}
}


/** Function dismiss() called by wp_ajax hooks: {'seedprod_lite_notification_dismiss'} **/
/** Parameters found in function dismiss(): {"post": ["id"]} **/
function dismiss() {

			// Run a security check.
			check_ajax_referer( 'seedprod_lite_notification_dismiss', '_wpnonce' );

			// Check for access and required param.
			if ( ! $this->has_access() || empty( $_POST['id'] ) ) {
				wp_send_json_error();
			}

			$id     = sanitize_text_field( wp_unslash( $_POST['id'] ) );
			$option = $this->get_option();
			$type   = is_numeric( $id ) ? 'feed' : 'events';

			$option['dismissed'][] = $id;
			$option['dismissed']   = array_unique( $option['dismissed'] );

			// Remove notification.
			if ( is_array( $option[ $type ] ) && ! empty( $option[ $type ] ) ) {
				foreach ( $option[ $type ] as $key => $notification ) {
					if ( $notification['id'] == $id ) { // phpcs:ignore WordPress.PHP.StrictComparisons
						unset( $option[ $type ][ $key ] );
						break;
					}
				}
			}

			update_option( $this->option_name, $option );

			wp_send_json_success();
		}


/** Function seedprod_lite_deactivate_addon() called by wp_ajax hooks: {'seedprod_lite_deactivate_addon'} **/
/** Parameters found in function seedprod_lite_deactivate_addon(): {"post": ["type", "plugin"]} **/
function seedprod_lite_deactivate_addon() {
	// Run a security check.
	check_ajax_referer( 'seedprod_lite_deactivate_addon', 'nonce' );

	// Check for permissions.
	if ( ! current_user_can( 'activate_plugins' ) ) {
		wp_send_json_error();
	}

	$type = 'addon';
	if ( ! empty( $_POST['type'] ) ) {
		$type = sanitize_key( wp_unslash( $_POST['type'] ) );
	}

	if ( isset( $_POST['plugin'] ) ) {
		$plugin = sanitize_text_field( wp_unslash( $_POST['plugin'] ) );
		deactivate_plugins( $plugin );

		if ( 'plugin' === $type ) {
			wp_send_json_success( esc_html__( 'Plugin deactivated.', 'coming-soon' ) );
		} else {
			wp_send_json_success( esc_html__( 'Addon deactivated.', 'coming-soon' ) );
		}
	}

	wp_send_json_error( esc_html__( 'Could not deactivate the addon. Please deactivate from the Plugins page.', 'coming-soon' ) );
}


/** Function seedprod_lite_v2_check_wizard_availability() called by wp_ajax hooks: {'seedprod_lite_v2_check_wizard_availability'} **/
/** No params detected :-/ **/


/** Function seedprod_lite_get_envira_galleries() called by wp_ajax hooks: {'seedprod_lite_get_envira_galleries'} **/
/** No params detected :-/ **/


/** Function seedprod_lite_get_wpforms() called by wp_ajax hooks: {'seedprod_lite_get_wpforms'} **/
/** No params detected :-/ **/


/** Function seedprod_lite_get_plugins_list() called by wp_ajax hooks: {'seedprod_lite_get_plugins_list'} **/
/** No params detected :-/ **/


/** Function seedprod_lite_v2_get_redirect_url() called by wp_ajax hooks: {'seedprod_lite_v2_get_redirect_url'} **/
/** Parameters found in function seedprod_lite_v2_get_redirect_url(): {"post": ["post_id"]} **/
function seedprod_lite_v2_get_redirect_url() {
	// Verify nonce.
	check_ajax_referer( 'seedprod_gutenberg_nonce', 'nonce' );

	// Get post ID.
	$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verified above.

	if ( ! $post_id ) {
		wp_send_json_error( array( 'message' => __( 'Invalid post ID', 'coming-soon' ) ) );
	}

	// Check user can edit this post.
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		wp_send_json_error( array( 'message' => __( 'Permission denied', 'coming-soon' ) ) );
	}

	// Check if theme builder is enabled first
	$theme_enabled = get_option( 'seedprod_theme_enabled' );

	// Check if page already has SeedProd meta.
	$is_seedprod_page        = get_post_meta( $post_id, '_seedprod_page', true );
	$is_edited_with_seedprod = get_post_meta( $post_id, '_seedprod_edited_with_seedprod', true );

	// IMPORTANT: Validate post_content_filtered actually contains SeedProd JSON.
	// Don't just check if it's not empty - other plugins can use this field!
	$post_content_filtered = get_post_field( 'post_content_filtered', $post_id );
	$has_seedprod_content  = false;
	if ( ! empty( $post_content_filtered ) ) {
		// Verify it's actually SeedProd JSON by checking for template_id marker.
		$decoded = json_decode( $post_content_filtered, true );
		if ( is_array( $decoded ) && isset( $decoded['template_id'] ) ) {
			$has_seedprod_content = true;
		}
	}

	// Handle both string '1' and boolean true.
	$already_seedprod = ( '1' === $is_seedprod_page || true === $is_seedprod_page ) ||
						( '1' === $is_edited_with_seedprod || true === $is_edited_with_seedprod ) ||
						$has_seedprod_content;

	// If already a SeedProd page and theme builder is enabled, ensure proper meta.
	if ( $already_seedprod && ! empty( $theme_enabled ) ) {
		// Get the post to check for new WordPress content.
		$post = get_post( $post_id );

		// Check if there's WordPress content that needs to be merged.
		if ( ! empty( $post->post_content ) ) {
			// Load existing SeedProd template.
			$existing_data = json_decode( $post->post_content_filtered, true );

			// Count total blocks to see if we need to merge content.
			$total_blocks = 0;
			if ( ! empty( $existing_data['document']['sections'] ) && is_array( $existing_data['document']['sections'] ) ) {
				foreach ( $existing_data['document']['sections'] as $section ) {
					if ( isset( $section['rows'] ) && is_array( $section['rows'] ) ) {
						foreach ( $section['rows'] as $row ) {
							if ( isset( $row['cols'] ) && is_array( $row['cols'] ) ) {
								foreach ( $row['cols'] as $col ) {
									if ( isset( $col['blocks'] ) && is_array( $col['blocks'] ) ) {
										$total_blocks += count( $col['blocks'] );
									}
								}
							}
						}
					}
				}
			}

			// Only merge if there are no blocks yet.
			if ( 0 === $total_blocks ) {
				// Load basic template if sections are empty.
				if ( empty( $existing_data['document']['sections'] ) ) {
					require_once SEEDPROD_PLUGIN_PATH . 'resources/data-templates/basic-page.php';
					$basic_data                            = json_decode( $seedprod_basic_lpage, true );
					$existing_data['document']['sections'] = $basic_data['document']['sections'];
				}

				// Create an HTML block with the existing content.
				$html_block = array(
					'id'       => 'sp-' . wp_generate_password( 6, false ),
					'type'     => 'htmlblock',
					'settings' => array(
						'html_content' => $post->post_content,
					),
				);

				// Add the HTML block to the first section's first row.
				if ( isset( $existing_data['document']['sections'][0]['rows'][0] ) ) {
					if ( ! isset( $existing_data['document']['sections'][0]['rows'][0]['cols'][0] ) ) {
						$existing_data['document']['sections'][0]['rows'][0]['cols'][0] = array(
							'id'       => 'sp-' . wp_generate_password( 6, false ),
							'blocks'   => array(),
							'settings' => array(),
						);
					}
					$existing_data['document']['sections'][0]['rows'][0]['cols'][0]['blocks'][] = $html_block;

					// Save updated template data.
					// Use wp_slash to prevent wp_update_post from stripping slashes that wp_json_encode adds.
					wp_update_post(
						array(
							'ID'                    => $post_id,
							'post_content_filtered' => wp_slash( wp_json_encode( $existing_data ) ),
						)
					);
				}
			}
		}

		// Make sure it's marked as a theme page, not a landing page.
		update_post_meta( $post_id, '_seedprod_edited_with_seedprod', '1' );
		delete_post_meta( $post_id, '_seedprod_page' );

		$redirect_url = admin_url() . 'admin.php?page=seedprod_lite_builder&id=' . $post_id . '#/setup/' . $post_id;

		wp_send_json_success(
			array(
				'redirect_url' => $redirect_url,
				'reason'       => 'existing_seedprod_page_with_theme_enabled',
				'theme_status' => $theme_enabled,
			)
		);
	}

	// If already a SeedProd page (and theme NOT enabled), go directly to builder.
	if ( $already_seedprod ) {
		$redirect_url = admin_url() . 'admin.php?page=seedprod_lite_builder&id=' . $post_id . '#/setup/' . $post_id;

		wp_send_json_success(
			array(
				'redirect_url' => $redirect_url,
				'reason'       => 'existing_seedprod_page',
				'meta'         => array(
					'is_seedprod_page'        => $is_seedprod_page,
					'is_edited_with_seedprod' => $is_edited_with_seedprod,
					'has_content_filtered'    => $has_seedprod_content,
				),
			)
		);
	}

	// PRIORITY CHECK: If NOT already a SeedProd page, check if this is the WordPress homepage
	// or blog page with an existing SeedProd theme template. If so, redirect to that template.
	$special_page_template_id = seedprod_lite_v2_find_special_page_template( $post_id );
	if ( null !== $special_page_template_id ) {
		// Found an existing special page template - redirect to edit it.
		$redirect_url = admin_url() . 'admin.php?page=seedprod_lite_builder&id=' . $special_page_template_id . '#/setup/' . $special_page_template_id;

		wp_send_json_success(
			array(
				'redirect_url' => $redirect_url,
				'reason'       => 'special_page_template_exists',
				'template_id'  => $special_page_template_id,
				'page_id'      => $post_id,
				'message'      => __( 'Redirecting to page template', 'coming-soon' ),
			)
		);
	}

	// New page - check if theme builder is enabled.
	if ( ! empty( $theme_enabled ) ) {
		// Theme builder is enabled - seed the page with basic template.
		// This replicates the logic from builder.php lines 189-197.

		// Get the post to preserve title and copy content.
		$post = get_post( $post_id );

		// Load basic template (same as builder.php does).
		require_once SEEDPROD_PLUGIN_PATH . 'resources/data-templates/basic-page.php';
		$settings                            = json_decode( $seedprod_basic_lpage, true );
		$settings['page_type']               = 'post'; // Set to 'post' for theme pages.
		$settings['from_edit_with_seedprod'] = true;

		// Preserve the existing title and slug.
		$settings['post_title'] = $post->post_title;
		$settings['post_name']  = $post->post_name;

		// Copy existing WordPress content if it exists.
		// This uses the same logic as lpage.php lines 1075-1084.
		if ( ! empty( $post->post_content ) ) {
			// Load the special current_content template (decode as array to match $settings structure).
			$current_content                  = $post->post_content;
			$settings['document']['sections'] = json_decode( $seedprod_current_content, true );

			// Set the text block content (strip HTML comments with s modifier to handle multiline comments).
			$clean_content = preg_replace( '/<!--(.*?)-->/s', '', $current_content );

			// Verify the structure exists before setting content.
			if ( isset( $settings['document']['sections'][0]['rows'][0]['cols'][0]['blocks'][0]['settings'] ) ) {
				$settings['document']['sections'][0]['rows'][0]['cols'][0]['blocks'][0]['settings']['txt'] = $clean_content;
			}
		}

		// Save template data to post_content_filtered.
		// Use wp_slash to prevent wp_update_post from stripping slashes that wp_json_encode adds.
		wp_update_post(
			array(
				'ID'                    => $post_id,
				'post_content_filtered' => wp_slash( wp_json_encode( $settings ) ),
			)
		);

		// Mark as theme page.
		update_post_meta( $post_id, '_seedprod_edited_with_seedprod', '1' );
		delete_post_meta( $post_id, '_seedprod_page' );

		// Redirect to builder (no from=post needed since we pre-seeded).
		$redirect_url = admin_url() . 'admin.php?page=seedprod_lite_builder&id=' . $post_id . '#/setup/' . $post_id;

		wp_send_json_success(
			array(
				'redirect_url' => $redirect_url,
				'reason'       => 'theme_builder_enabled',
				'theme_status' => $theme_enabled,
				'seeded'       => true,
			)
		);
	} else {
		// Theme builder NOT enabled - send to template picker for landing page.
		$nonce        = wp_create_nonce( 'seedprod_nonce' );
		$redirect_url = admin_url() . 'admin.php?page=seedprod_lite_template&_wpnonce=' . $nonce . '&from=post&id=' . $post_id . '#/template/' . $post_id;

		wp_send_json_success(
			array(
				'redirect_url' => $redirect_url,
				'reason'       => 'create_landing_page',
				'theme_status' => false,
			)
		);
	}
}


/** Function seedprod_lite_save_app_settings() called by wp_ajax hooks: {'seedprod_lite_save_app_settings'} **/
/** Parameters found in function seedprod_lite_save_app_settings(): {"post": ["app_settings"]} **/
function seedprod_lite_save_app_settings() {
	if ( check_ajax_referer( 'seedprod_lite_save_app_settings' ) ) {
		if ( ! current_user_can( apply_filters( 'seedprod_save_app_settings_capability', 'manage_options' ) ) ) {
			wp_send_json_error( null, 400 );
		}
		if ( ! empty( $_POST['app_settings'] ) ) {

			$app_settings = wp_unslash( $_POST['app_settings'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			// security: create new settings array so we make sure we only set/allow our settings.
			$new_app_settings = array();

			// Edit Button.
			if ( isset( $app_settings['disable_seedprod_button'] ) && 'true' === $app_settings['disable_seedprod_button'] ) {
				$new_app_settings['disable_seedprod_button'] = true;
				update_option( 'seedprod_allow_usage_tracking', true );
			} else {
				$new_app_settings['disable_seedprod_button'] = false;
				update_option( 'seedprod_allow_usage_tracking', false );
			}

			// Usage Tracking.
			if ( isset( $app_settings['enable_usage_tracking'] ) && 'true' === $app_settings['enable_usage_tracking'] ) {
				$new_app_settings['enable_usage_tracking'] = true;
				update_option( 'seedprod_allow_usage_tracking', true );
			} else {
				$new_app_settings['enable_usage_tracking'] = false;
				update_option( 'seedprod_allow_usage_tracking', false );
			}

			// Edit notification.
			if ( isset( $app_settings['disable_seedprod_notification'] ) && 'true' === $app_settings['disable_seedprod_notification'] ) {
				$new_app_settings['disable_seedprod_notification'] = true;
			} else {
				$new_app_settings['disable_seedprod_notification'] = false;
			}

			// Facebook ID.
			$new_app_settings['facebook_g_app_id']     = sanitize_text_field( $app_settings['facebook_g_app_id'] );
			$new_app_settings['google_places_app_key'] = sanitize_text_field( $app_settings['google_places_app_key'] );
			$new_app_settings['yelp_app_api_key']      = sanitize_text_field( $app_settings['yelp_app_api_key'] );
			$app_settings_encode                       = wp_json_encode( $new_app_settings );

			update_option( 'seedprod_app_settings', $app_settings_encode );
			$response = array(
				'status' => 'true',
				'msg'    => __( 'App Settings Updated', 'coming-soon' ),
			);

		} else {
			$response = array(
				'status' => 'false',
				'msg'    => __( 'Error Updating App Settings', 'coming-soon' ),
			);
		}
			// Send response.
			wp_send_json( $response );
			exit;

	}
}


/** Function seedprod_lite_v2_save_settings() called by wp_ajax hooks: {'seedprod_lite_v2_save_settings'} **/
/** Parameters found in function seedprod_lite_v2_save_settings(): {"post": ["settings"]} **/
function seedprod_lite_v2_save_settings() {
	// Verify nonce.
	if ( ! check_ajax_referer( 'seedprod_nonce', '_wpnonce', false ) ) {
		wp_send_json_error( 'Invalid nonce' );
	}

	// Check permissions.
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( 'Insufficient permissions' );
	}

	// Get and sanitize settings.
	$settings_json = isset( $_POST['settings'] ) ? stripslashes( wp_unslash( $_POST['settings'] ) ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- JSON string validated after json_decode.
	$new_settings  = json_decode( $settings_json, true );

	if ( ! is_array( $new_settings ) ) {
		wp_send_json_error( 'Invalid settings format' );
	}

	// Get existing settings (stored as JSON string).
	$existing_settings_json = get_option( 'seedprod_settings' );
	if ( ! empty( $existing_settings_json ) ) {
		$existing_settings = json_decode( $existing_settings_json, true );
		if ( ! is_array( $existing_settings ) ) {
			$existing_settings = array();
		}
	} else {
		$existing_settings = array();
	}

	// Update only the mode settings.
	$mode_keys = array(
		'enable_coming_soon_mode',
		'enable_maintenance_mode',
		'enable_login_mode',
		'enable_404_mode',
	);

	// Map settings to their page ID options.
	$settings_to_page_options = array(
		'enable_coming_soon_mode' => 'seedprod_coming_soon_page_id',
		'enable_maintenance_mode' => 'seedprod_maintenance_mode_page_id',
		'enable_login_mode'       => 'seedprod_login_page_id',
		'enable_404_mode'         => 'seedprod_404_page_id',
	);

	foreach ( $mode_keys as $key ) {
		if ( isset( $new_settings[ $key ] ) ) {
			$new_value = (bool) $new_settings[ $key ];

			// Update the setting.
			$existing_settings[ $key ] = $new_value;

			// Always ensure page post_status matches the setting.
			if ( isset( $settings_to_page_options[ $key ] ) ) {
				$page_id = get_option( $settings_to_page_options[ $key ] );

				if ( $page_id ) {
					$page = get_post( $page_id );
					if ( $page ) {
						$expected_status = $new_value ? 'publish' : 'draft';
						// Only update if status doesn't match to avoid unnecessary DB writes.
						if ( $page->post_status !== $expected_status ) {
							wp_update_post(
								array(
									'ID'          => $page_id,
									'post_status' => $expected_status,
								)
							);
						}
					}
				}
			}
		}
	}

	// Ensure Coming Soon and Maintenance are mutually exclusive.
	if ( ! empty( $existing_settings['enable_coming_soon_mode'] ) && ! empty( $existing_settings['enable_maintenance_mode'] ) ) {
		// If both are true, disable the one that wasn't just enabled.
		if ( isset( $new_settings['enable_coming_soon_mode'] ) && $new_settings['enable_coming_soon_mode'] ) {
			$existing_settings['enable_maintenance_mode'] = false;
			// Set maintenance page to draft since we're disabling it.
			$mm_page_id = get_option( 'seedprod_maintenance_mode_page_id' );
			if ( $mm_page_id && get_post( $mm_page_id ) ) {
				wp_update_post(
					array(
						'ID'          => $mm_page_id,
						'post_status' => 'draft',
					)
				);
			}
		} else {
			$existing_settings['enable_coming_soon_mode'] = false;
			// Set coming soon page to draft since we're disabling it.
			$cs_page_id = get_option( 'seedprod_coming_soon_page_id' );
			if ( $cs_page_id && get_post( $cs_page_id ) ) {
				wp_update_post(
					array(
						'ID'          => $cs_page_id,
						'post_status' => 'draft',
					)
				);
			}
		}
	}

	// Save settings as JSON string (to match existing format).
	$result = update_option( 'seedprod_settings', wp_json_encode( $existing_settings ) );

	if ( false !== $result ) {
		wp_send_json_success(
			array(
				'message'  => __( 'Settings saved successfully', 'coming-soon' ),
				'settings' => $existing_settings,
			)
		);
	} else {
		wp_send_json_error( 'Failed to save settings' );
	}
}


/** Function seedprod_lite_save_lpage() called by wp_ajax hooks: {'seedprod_lite_save_lpage'} **/
/** No params detected :-/ **/


/** Function seedprod_lite_v2_duplicate_lpage() called by wp_ajax hooks: {'seedprod_lite_v2_duplicate_lpage'} **/
/** Parameters found in function seedprod_lite_v2_duplicate_lpage(): {"post": ["id"]} **/
function seedprod_lite_v2_duplicate_lpage() {
	// Check nonce using V2 nonce.
	if ( ! check_ajax_referer( 'seedprod_v2_nonce', 'nonce', false ) ) {
		wp_send_json_error( __( 'Security check failed.', 'coming-soon' ) );
	}

	// Check permissions.
	if ( ! current_user_can( apply_filters( 'seedprod_lpage_capability', 'edit_others_posts' ) ) ) {
		wp_send_json_error( __( 'You do not have permission to duplicate pages.', 'coming-soon' ) );
	}

	// Get the page ID.
	$id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;

	if ( ! $id ) {
		wp_send_json_error( __( 'Invalid page ID.', 'coming-soon' ) );
	}

	// Get the original post.
	$post = get_post( $id );

	if ( ! $post ) {
		wp_send_json_error( __( 'Page not found.', 'coming-soon' ) );
	}

	// Get the page content.
	$json = $post->post_content_filtered;

	// Create the duplicate post.
	$args = array(
		'comment_status' => 'closed',
		'ping_status'    => 'closed',
		'post_content'   => $post->post_content,
		'post_status'    => 'draft',
		'post_title'     => $post->post_title . ' - Copy',
		'post_type'      => 'page',
		'post_name'      => '',
		'meta_input'     => array(
			'_seedprod_page'      => true,
			'_seedprod_page_uuid' => wp_generate_uuid4(),
		),
	);

	// Insert the new post.
	$new_post_id = wp_insert_post( $args, true );

	if ( is_wp_error( $new_post_id ) ) {
		wp_send_json_error( $new_post_id->get_error_message() );
	}

	// Reinsert JSON content to avoid slash issues.
	global $wpdb;
	$tablename = $wpdb->prefix . 'posts';
	$wpdb->update(
		$tablename,
		array(
			'post_content_filtered' => $json,
		),
		array( 'ID' => $new_post_id ),
		array( '%s' ),
		array( '%d' )
	);

	// Copy additional meta fields if they exist.
	$meta_to_copy = array(
		'_seedprod_page_type',
		'_seedprod_page_template_id',
		'_seedprod_page_template_type',
	);

	foreach ( $meta_to_copy as $meta_key ) {
		$meta_value = get_post_meta( $id, $meta_key, true );
		if ( ! empty( $meta_value ) ) {
			update_post_meta( $new_post_id, $meta_key, $meta_value );
		}
	}

	// Get the new post details for the response.
	$new_post = get_post( $new_post_id );

	wp_send_json_success(
		array(
			'message' => __( 'Page duplicated successfully.', 'coming-soon' ),
			'page'    => array(
				'id'    => $new_post_id,
				'title' => $new_post->post_title,
				'url'   => get_preview_post_link( $new_post_id ),
			),
		)
	);
}


/** Function seedprod_lite_v2_remove_post() called by wp_ajax hooks: {'seedprod_lite_v2_remove_post'} **/
/** No params detected :-/ **/


/** Function seedprod_lite_get_namespaced_custom_css() called by wp_ajax hooks: {'seedprod_lite_get_namespaced_custom_css'} **/
/** Parameters found in function seedprod_lite_get_namespaced_custom_css(): {"post": ["css"]} **/
function seedprod_lite_get_namespaced_custom_css() {
	if ( check_ajax_referer( 'seedprod_lite_get_namespaced_custom_css' ) ) {
		if ( ! current_user_can( apply_filters( 'seedprod_lpage_capability', 'edit_others_posts' ) ) ) {
			wp_send_json_error();
		}
		if ( ! empty( $_POST['css'] ) ) {
			$css = sanitize_text_field( wp_unslash( $_POST['css'] ) );
			require_once SEEDPROD_PLUGIN_PATH . 'app/includes/seedprod_lessc.inc.php';
			$less  = new seedprod_lessc();
			$style = $less->parse( '.sp-html {' . $css . '}' );
			// echo $style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '';
			exit();
		}
	}
}


/** Function seedprod_lite_v2_toggle_favorite_template() called by wp_ajax hooks: {'seedprod_lite_v2_toggle_favorite_template'} **/
/** Parameters found in function seedprod_lite_v2_toggle_favorite_template(): {"post": ["template_id", "method"]} **/
function seedprod_lite_v2_toggle_favorite_template() {
	check_ajax_referer( 'seedprod_v2_nonce' );

	$template_id = isset( $_POST['template_id'] ) ? sanitize_text_field( wp_unslash( $_POST['template_id'] ) ) : '';
	$method      = isset( $_POST['method'] ) ? sanitize_text_field( wp_unslash( $_POST['method'] ) ) : '';

	if ( empty( $template_id ) ) {
		wp_send_json_error( __( 'Invalid template ID', 'coming-soon' ) );
	}

	// Determine method if not provided.
	if ( empty( $method ) ) {
		// Check current favorites from API to determine method.
		$all_templates         = seedprod_lite_v2_fetch_templates_from_api();
		$is_currently_favorite = false;

		foreach ( $all_templates as $template ) {
			// phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
			if ( $template['id'] == $template_id && isset( $template['is_favorite'] ) && $template['is_favorite'] ) {
				$is_currently_favorite = true;
				break;
			}
		}

		$method = $is_currently_favorite ? 'detach' : 'attach';
	}

	// Get tokens for API call.
	$api_token  = get_option( 'seedprod_api_token', '' );
	$site_token = get_option( 'seedprod_token', '' );

	// Call SeedProd API to update favorite status.
	$api_url = SEEDPROD_API_URL . 'template-update';

	$body = array(
		'template_id' => $template_id,
		'method'      => $method,
		'api_token'   => $api_token,
		'site_token'  => $site_token,
	);

	$args = array(
		'body'    => $body,
		'timeout' => 10,
	);

	$response = wp_remote_post( $api_url, $args );

	if ( is_wp_error( $response ) ) {
		wp_send_json_error( __( 'Failed to update favorite status', 'coming-soon' ) );
	}

	$is_favorite = ( 'attach' === $method );

	wp_send_json_success( array( 'is_favorite' => $is_favorite ) );
}


/** Function seedprod_lite_activate_addon() called by wp_ajax hooks: {'seedprod_lite_activate_addon'} **/
/** Parameters found in function seedprod_lite_activate_addon(): {"post": ["plugin", "type"]} **/
function seedprod_lite_activate_addon() {
	// Run a security check.
	if ( check_ajax_referer( 'seedprod_lite_activate_addon', 'nonce' ) ) {
		// Check for permissions.
		if ( ! current_user_can( 'activate_plugin' ) ) {
			wp_send_json_error( esc_html__( 'Could not activate addon. Please check user permissions.', 'coming-soon' ) );
		}

		if ( isset( $_POST['plugin'] ) ) {
			$type = 'addon';
			if ( ! empty( $_POST['type'] ) ) {
				$type = sanitize_key( wp_unslash( $_POST['type'] ) );
			}

			$plugin   = sanitize_text_field( wp_unslash( $_POST['plugin'] ) );
			$activate = activate_plugin( $plugin, '', false, true );

			if ( ! is_wp_error( $activate ) ) {
				if ( 'plugin' === $type ) {
					wp_send_json_success( esc_html__( 'Plugin activated.', 'coming-soon' ) );
				} else {
					wp_send_json_success( esc_html__( 'Addon activated.', 'coming-soon' ) );
				}
			}
		}

		wp_send_json_error( esc_html__( 'Could not activate addon. Please activate from the Plugins page.', 'coming-soon' ) );
	}

	wp_send_json_error( esc_html__( 'Could not activate addon. Please refresh page and try again.', 'coming-soon' ) );
}


/** Function seedprod_lite_get_widget_wpforms() called by wp_ajax hooks: {'seedprod_lite_get_widget_wpforms'} **/
/** No function found :-/ **/


/** Function seedprod_lite_v2_get_templates() called by wp_ajax hooks: {'seedprod_lite_v2_get_templates'} **/
/** Parameters found in function seedprod_lite_v2_get_templates(): {"post": ["filter", "search", "category", "is_lite_view", "free_subscribed"]} **/
function seedprod_lite_v2_get_templates() {
	// Check if request is valid.
	check_ajax_referer( 'seedprod_v2_nonce' );

	$filter   = isset( $_POST['filter'] ) ? sanitize_text_field( wp_unslash( $_POST['filter'] ) ) : 'all';
	$search   = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '';
	$category = isset( $_POST['category'] ) ? sanitize_text_field( wp_unslash( $_POST['category'] ) ) : '';

	// Get Lite view status from AJAX request.
	$is_lite_view    = isset( $_POST['is_lite_view'] ) && 'true' === $_POST['is_lite_view'];
	$free_subscribed = isset( $_POST['free_subscribed'] ) && 'true' === $_POST['free_subscribed'] ? '1' : '0';

	// Always fetch fresh data from API to ensure favorites are current.
	// (Caching removed temporarily to fix favorites issue).
	$templates = seedprod_lite_v2_fetch_templates_from_api( $search, $category, $is_lite_view, $free_subscribed );

	wp_send_json_success( array_values( $templates ) );
}


/** Function seedprod_lite_v2_delete_subscribers() called by wp_ajax hooks: {'seedprod_lite_v2_delete_subscribers'} **/
/** Parameters found in function seedprod_lite_v2_delete_subscribers(): {"post": ["ids"]} **/
function seedprod_lite_v2_delete_subscribers() {
	if ( check_ajax_referer( 'seedprod_v2_nonce', 'nonce', false ) ) {
		if ( ! current_user_can( apply_filters( 'seedprod_delete_subscriber_capability', 'list_users' ) ) ) {
			wp_send_json_error( __( 'You do not have permission to delete subscribers.', 'coming-soon' ) );
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Validated as integers with array_map('intval') below.
		$ids = isset( $_POST['ids'] ) ? wp_unslash( $_POST['ids'] ) : array();

		if ( empty( $ids ) ) {
			wp_send_json_error( __( 'No subscribers selected.', 'coming-soon' ) );
		}

		// Ensure all IDs are integers.
		$ids = array_map( 'intval', $ids );

		global $wpdb;
		$tablename = $wpdb->prefix . 'csp3_subscribers';

		// Build placeholders.
		$placeholders = array_fill( 0, count( $ids ), '%d' );
		$format       = implode( ', ', $placeholders );

		// Delete subscribers.
		// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- $format is placeholders string, custom table delete operation.
		$sql    = $wpdb->prepare( "DELETE FROM $tablename WHERE id IN ($format)", $ids );
		$result = $wpdb->query( $sql );
		// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching

		if ( false !== $result ) {
			wp_send_json_success(
				array(
					'deleted' => $result,
					/* translators: %d: number of subscribers deleted */
					'message' => sprintf( _n( '%d subscriber deleted.', '%d subscribers deleted.', $result, 'coming-soon' ), $result ),
				)
			);
		} else {
			wp_send_json_error( __( 'Failed to delete subscribers.', 'coming-soon' ) );
		}
	}
	wp_send_json_error();
}


/** Function seedprod_lite_save_template() called by wp_ajax hooks: {'seedprod_lite_save_template'} **/
/** Parameters found in function seedprod_lite_save_template(): {"post": ["lpage_id", "lpage_template_id", "lpage_type", "lpage_name", "lpage_slug"]} **/
function seedprod_lite_save_template() {
	// get template code and set name and slug.
	if ( check_ajax_referer( 'seedprod_nonce' ) ) {
		if ( ! current_user_can( apply_filters( 'seedprod_lpage_capability', 'edit_others_posts' ) ) ) {
			wp_send_json_error();
		}
		$_POST = stripslashes_deep( $_POST );

		$status   = false;
		$lpage_id = null;

		if ( empty( absint( $_POST['lpage_id'] ) ) ) {
			// shouldn't get here.
			$response = array(
				'status' => $status,
				'id'     => $lpage_id,
				'code'   => '',
			);

			wp_send_json( $response, 403 );
		} else {
			$lpage_id    = absint( $_POST['lpage_id'] );
			$template_id = isset( $_POST['lpage_template_id'] ) ? absint( $_POST['lpage_template_id'] ) : null;

			if ( 99999 != $template_id ) {
				$template_code = seedprod_lite_get_template_code( $template_id );
			}

			// merge in template code to settings.
			global $wpdb;
			$tablename               = $wpdb->prefix . 'posts';
			$sql                     = "SELECT * FROM $tablename WHERE id = %d"; // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$safe_sql                = $wpdb->prepare( $sql, $lpage_id ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$lpage                   = $wpdb->get_row( $safe_sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$settings                = json_decode( $lpage->post_content_filtered, true );
			$settings['template_id'] = $template_id;
			if ( 99999 != $template_id ) {
				unset( $settings['document'] );
				$template_code_merge = json_decode( $template_code, true );
				if ( is_array( $template_code_merge ) ) {
					$settings = $settings + $template_code_merge;
				}
			}
			// TODO pull in current pages content if any exists, make sure sections is empty before adding.
			if ( ! empty( $_POST['lpage_type'] ) && 'post' == $_POST['lpage_type'] ) {
				if ( ! empty( $lpage->post_content ) ) {
					require_once SEEDPROD_PLUGIN_PATH . 'resources/data-templates/basic-page.php';
					$current_content = $lpage->post_content;
					// if(empty($settings['document']['sections'])){
						$settings['document']['sections'] = json_decode( $seedprod_current_content );
						$settings['document']['sections'][0]->rows[0]->cols[0]->blocks[0]->settings->txt = preg_replace( '/<!--(.*?)-->/', '', $current_content );
					// }
				}
			}

			$settings['page_type'] = sanitize_text_field( wp_unslash( $_POST['lpage_type'] ) );

			// set post type to landong page if they do not have the theme builder.
			$theme_enabled = get_option( 'seedprod_theme_enabled' );
			$theme_builder = seedprod_lite_cu( 'themebuilder' );
			if ( 'post' == $settings['page_type'] && empty( $theme_builder ) ) {
				$settings['page_type'] = 'lp';
			}
			if ( 'post' == $settings['page_type'] && ! empty( $theme_builder ) && empty( $theme_enabled ) ) {
				$settings['page_type'] = 'lp';
			}

			// save settings.
			// $r = wp_update_post(
			// array(
			// 'ID' => $lpage_id,
			// 'post_title'=>sanitize_text_field($_POST['lpage_name']),
			// 'post_content_filtered'=> json_encode($settings),
			// 'post_name' => sanitize_title($_POST['lpage_slug']),
			// )
			// );

			global $wpdb;
			$tablename = $wpdb->prefix . 'posts';
			$r         = $wpdb->update(
				$tablename,
				array(
					'post_title'            => isset( $_POST['lpage_name'] ) ? sanitize_text_field( wp_unslash( $_POST['lpage_name'] ) ) : '',
					'post_content_filtered' => wp_json_encode( $settings ),
					'post_name'             => isset( $_POST['lpage_slug'] ) ? sanitize_title( wp_unslash( $_POST['lpage_slug'] ) ) : '',
				),
				array( 'ID' => $lpage_id ),
				array(
					'%s',
					'%s',
					'%s',
				),
				array( '%d' )
			);

			$status = 'updated';
		}

		$response = array(
			'status' => $status,
			'id'     => $lpage_id,
			'code'   => $template_code,
		);

		wp_send_json( $response );
	}
}


/** Function seedprod_lite_save_api_key() called by wp_ajax hooks: {'seedprod_lite_save_api_key'} **/
/** Parameters found in function seedprod_lite_save_api_key(): {"post": ["api_key"]} **/
function seedprod_lite_save_api_key( $api_key = null ) {
	if ( check_ajax_referer( 'seedprod_nonce', '_wpnonce', false ) || ! empty( $api_key ) ) {
		if ( ! current_user_can( apply_filters( 'seedprod_license_capability', 'manage_options' ) ) ) {
			wp_send_json_error();
		}

		if ( empty( $api_key ) ) {
			$api_key = isset( $_POST['api_key'] ) ? sanitize_text_field( wp_unslash( $_POST['api_key'] ) ) : null;
		}

		if ( defined( 'SEEDPROD_LOCAL_JS' ) ) {
			$slug = 'seedprod-coming-soon-pro-5/seedprod-coming-soon-pro-5.php';
		} else {
			$slug = SEEDPROD_SLUG;
		}

			// Get token and generate one if one does not exist.
		$token = get_option( 'seedprod_token' );
		if ( empty( $token ) ) {
			$token = strtolower( wp_generate_password( 32, false, false ) );
			update_option( 'seedprod_token', $token );
		}

			// Validate the API key.
		$data = array(
			'action'            => 'info',
			'license_key'       => $api_key,
			'token'             => $token,
			'wp_version'        => get_bloginfo( 'version' ),
			'domain'            => home_url(),
			'installed_version' => SEEDPROD_VERSION,
			'slug'              => $slug,
		);

		if ( empty( $data['license_key'] ) ) {
			$response = array(
				'status' => 'false',
				'msg'    => __( 'License Key is Required.', 'coming-soon' ),
			);
			wp_send_json( $response );
			exit;
		}

		$headers = array();

		// Build the headers of the request.
		$headers = wp_parse_args(
			$headers,
			array(
				'Accept' => 'application/json',
			)
		);

		$url      = SEEDPROD_API_URL . 'update';
		$response = wp_remote_post(
			$url,
			array(
				'body'    => $data,
				'headers' => $headers,
			)
		);

		$status_code = wp_remote_retrieve_response_code( $response );

		if ( is_wp_error( $response ) ) {
			$response = array(
				'status' => 'false',
				'ip'     => seedprod_lite_get_ip(),
				'msg'    => $response->get_error_message(),
			);
			wp_send_json( $response );
		}

		if ( 200 !== $status_code ) {
			$response = array(
				'status' => 'false',
				'ip'     => seedprod_lite_get_ip(),
				'msg'    => $response['response']['message'],
			);
			wp_send_json( $response );
		}

		$body = wp_remote_retrieve_body( $response );

		if ( ! empty( $body ) ) {
			$body = json_decode( $body );
		}

		if ( ! empty( $body->valid ) && true === $body->valid ) {
				// Store API key.
			update_option( 'seedprod_user_id', $body->user_id );
			update_option( 'seedprod_api_token', $body->api_token );
			update_option( 'seedprod_api_key', $data['license_key'] );
			update_option( 'seedprod_api_message', $body->message );
			update_option( 'seedprod_license_name', $body->license_name );
			update_option( 'seedprod_a', true );
			update_option( 'seedprod_per', $body->per );
			$response = array(
				'status'       => 'true',
				/* translators: 1. License name.*/
				'license_name' => sprintf( __( 'You currently have the <strong>%s</strong> license.', 'coming-soon' ), $body->license_name ),
				'msg'          => $body->message,
				'body'         => $body,
			);
		} elseif ( isset( $body->valid ) && false === $body->valid ) {
			$api_msg = __( 'Invalid License Key.', 'coming-soon' );
			if ( 'Unauthenticated.' !== $body->message ) {
				$api_msg = $body->message;
			}
			update_option( 'seedprod_license_name', '' );
			update_option( 'seedprod_api_token', '' );
			update_option( 'seedprod_api_key', '' );
			update_option( 'seedprod_api_message', $api_msg );
			update_option( 'seedprod_a', false );
			update_option( 'seedprod_per', '' );
			$response = array(
				'status'       => 'false',
				'license_name' => '',
				'msg'          => $api_msg,
				'body'         => $body,
			);
		}

			// Send response.
		if ( ! empty( $_POST['api_key'] ) ) {
			wp_send_json( $response );
			exit;
		} else {
			return $response;
		}
	}
}


/** Function seedprod_lite_v2_get_plugins_list() called by wp_ajax hooks: {'seedprod_lite_v2_get_plugins_list'} **/
/** No params detected :-/ **/


/** Function seedprod_lite_update_subscriber_count() called by wp_ajax hooks: {'seedprod_lite_update_subscriber_count'} **/
/** No function found :-/ **/


/** Function seedprod_lite_get_revisisons() called by wp_ajax hooks: {'seedprod_lite_get_revisions'} **/
/** Parameters found in function seedprod_lite_get_revisisons(): {"post": ["lpage_id"]} **/
function seedprod_lite_get_revisisons() {
	if ( ! current_user_can( apply_filters( 'seedprod_get_revisions_capability', 'edit_others_posts' ) ) ) {
		wp_send_json_error();
	}

	$lpage_id  = isset( $_POST['lpage_id'] ) ? absint( wp_unslash( $_POST['lpage_id'] ) ) : null; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	$revisions = wp_get_post_revisions( $lpage_id, array( 'numberposts' => 50 ) );
	foreach ( $revisions as $k => $v ) {
		$v->time_ago           = human_time_diff( strtotime( $v->post_date_gmt ) );
		$v->post_date_formated = gmdate( 'M j \a\t ' . get_option( 'time_format' ), strtotime( $v->post_date ) );
		$authordata            = get_userdata( $v->post_author );
		$v->author_name        = $authordata->data->user_nicename;
		$v->author_email       = md5( $authordata->data->user_email );
		unset( $v->post_content );
		if ( empty( $v->post_content_filtered ) ) {
			unset( $revisions[ $k ] );
		}

		// $created_at = date(get_option('date_format').' '.get_option('time_format'), strtotime($v->post_date));
	}
	$revisions = array_values( $revisions );

	$response = array(
		'id'        => $lpage_id,
		'revisions' => $revisions,
	);

	wp_send_json( $response );
}


/** Function seedprod_lite_unarchive_selected_lpages() called by wp_ajax hooks: {'seedprod_lite_unarchive_selected_lpages'} **/
/** Parameters found in function seedprod_lite_unarchive_selected_lpages(): {"get": ["ids"]} **/
function seedprod_lite_unarchive_selected_lpages( $ids ) {
	if ( check_ajax_referer( 'seedprod_lite_unarchive_selected_lpages' ) ) {
		if ( current_user_can( apply_filters( 'seedprod_unarchive_pages_capability', 'list_users' ) ) ) {
			if ( ! empty( $_GET['ids'] ) ) {
				$ids = array_map( 'intval', explode( ',', sanitize_text_field( wp_unslash( $_GET['ids'] ) ) ) );
				foreach ( $ids as $v ) {
					wp_untrash_post( $v );
				}

				wp_send_json( array( 'status' => true ) );
			}
		}
	}
}


/** Function seedprod_lite_get_edd_downloads() called by wp_ajax hooks: {'seedprod_lite_get_edd_downloads'} **/
/** No function found :-/ **/


/** Function seedprod_lite_v2_create_page_from_template() called by wp_ajax hooks: {'seedprod_lite_v2_create_page_from_template'} **/
/** Parameters found in function seedprod_lite_v2_create_page_from_template(): {"post": ["template_id", "page_name", "page_slug", "page_type", "page_id"]} **/
function seedprod_lite_v2_create_page_from_template() {
	check_ajax_referer( 'seedprod_v2_nonce' );

	if ( ! current_user_can( apply_filters( 'seedprod_lpage_capability', 'edit_others_posts' ) ) ) {
		wp_send_json_error( __( 'You do not have permission to create pages.', 'coming-soon' ) );
	}

	$template_id = isset( $_POST['template_id'] ) ? sanitize_text_field( wp_unslash( $_POST['template_id'] ) ) : '';
	$page_name   = isset( $_POST['page_name'] ) ? sanitize_text_field( wp_unslash( $_POST['page_name'] ) ) : '';
	$page_slug   = isset( $_POST['page_slug'] ) ? sanitize_text_field( wp_unslash( $_POST['page_slug'] ) ) : '';
	$page_type   = isset( $_POST['page_type'] ) ? sanitize_text_field( wp_unslash( $_POST['page_type'] ) ) : 'lp';
	$page_id     = isset( $_POST['page_id'] ) ? absint( $_POST['page_id'] ) : 0;

	if ( 'lite' === SEEDPROD_BUILD && in_array( $page_type, array( 'header', 'footer', 'part', 'page' ), true ) ) {
		wp_send_json_error( __( 'Theme templates are not available in this version.', 'coming-soon' ) );
	}

	// Override slug and name for special pages to match old flow requirements.
	// These hardcoded slugs are critical for system identification.
	if ( 'cs' === $page_type ) {
		$page_slug = 'sp-cs';
		$page_name = $page_slug; // Old flow uses slug as name initially.
	} elseif ( 'mm' === $page_type ) {
		$page_slug = 'sp-mm';
		$page_name = $page_slug;
	} elseif ( 'p404' === $page_type ) {
		$page_slug = 'sp-p404';
		$page_name = $page_slug;
	} elseif ( 'loginp' === $page_type ) {
		$page_slug = 'sp-login';
		$page_name = $page_slug;
	}

	if ( empty( $template_id ) || empty( $page_name ) ) {
		wp_send_json_error( __( 'Missing required fields', 'coming-soon' ) );
	}

	// Check if we're updating an existing page (edge case: page without template).
	if ( $page_id > 0 ) {
		// Verify the page exists.
		$existing_page = get_post( $page_id );
		if ( ! $existing_page ) {
			wp_send_json_error( __( 'Page not found', 'coming-soon' ) );
		}

		// Load existing settings from the page.
		$existing_settings = json_decode( $existing_page->post_content_filtered, true );
		if ( ! is_array( $existing_settings ) ) {
			// If no valid settings, load defaults.
			require_once SEEDPROD_PLUGIN_PATH . 'resources/data-templates/basic-page.php';
			$settings = json_decode( $seedprod_basic_lpage, true );
		} else {
			$settings = $existing_settings;
		}

		// Get page type from meta if not already set.
		if ( empty( $page_type ) || 'lp' === $page_type ) {
			$stored_page_type = get_post_meta( $page_id, '_seedprod_page_template_type', true );
			if ( $stored_page_type ) {
				$page_type = $stored_page_type;
			}
		}
	} else {
		// Load basic page defaults first (matching old flow which always loads basic-page.php).
		require_once SEEDPROD_PLUGIN_PATH . 'resources/data-templates/basic-page.php';
		$settings = json_decode( $seedprod_basic_lpage, true );
	}

	// Override with our specific settings.
	$settings['template_id'] = absint( $template_id ); // Convert to integer to match old flow.
	$settings['page_type']   = $page_type;
	$settings['is_new']      = true; // Mark as new page (matching old flow).

	// Set no_conflict_mode for special page types (matching old lpage.php logic).
	if ( in_array( $page_type, array( 'cs', 'mm', 'p404', 'loginp' ), true ) ) {
		$settings['no_conflict_mode'] = true;
	}

	// Set default template for template parts (matching old flow line 158).
	$template_parts = array( 'header', 'footer', 'part', 'page' );
	if ( in_array( $page_type, $template_parts, true ) ) {
		// Template ID 71 is the default blank template for theme parts.
		$settings['template_id'] = 71;
	}

	// Get template code from API if not blank template.
	if ( 'blank' !== $template_id && '99999' !== $template_id ) {
		// Use our V2 function to get template code.
		$template_code_json = seedprod_lite_v2_get_template_code( $template_id );

		if ( $template_code_json ) {
			$template_data = json_decode( $template_code_json, true );

			if ( ! empty( $template_data ) && is_array( $template_data ) ) {
				if ( isset( $template_data['document'] ) && is_array( $template_data['document'] ) ) {
					unset( $settings['document'] );
				} elseif ( array_key_exists( 'document', $template_data ) ) {
					unset( $template_data['document'] );
				}
				$settings = array_merge( $settings, $template_data );

				// Restore critical page settings that template data must not override.
				// Saved templates contain their original page's full settings (including page_type),
				// so without this the CS/MM activation modal never appears because page_type gets
				// overwritten (e.g. 'cs' → 'lp') causing close_conditions() to skip the prompt.
				$settings['page_type'] = $page_type;
				$settings['is_new']    = true;
				if ( in_array( $page_type, array( 'cs', 'mm', 'p404', 'loginp' ), true ) ) {
					$settings['no_conflict_mode'] = true;
				}
			}
		}
	}

	// Determine post type based on page type (matching lpage.php logic).
	$post_type = 'page';
	// seedprod cpt types - these should be created as 'seedprod' post type.
	// Note: 'loginp' intentionally excluded - login pages use regular 'page' post type
	// so they work with any slug without needing custom rewrite rules.
	$cpt_types = array(
		'cs',
		'mm',
		'p404',
		'header',
		'footer',
		'part',
		'page',
	);

	if ( in_array( $page_type, $cpt_types ) ) {
		$post_type = 'seedprod';
	}

	// Create the page with proper post_content_filtered (matching old flow structure).
	$encoded_settings = wp_json_encode( $settings );

	if ( $page_id > 0 ) {
		// Update existing page.
		$page_data = array(
			'ID'                    => $page_id,
			'post_title'            => $page_name,
			'post_name'             => $page_slug,
			'post_content_filtered' => $encoded_settings,
		);

		$update_result = wp_update_post( $page_data );

		if ( is_wp_error( $update_result ) ) {
			wp_send_json_error( __( 'Failed to update page', 'coming-soon' ) );
		}
	} else {
		// Create new page.
		$page_data = array(
			'post_title'            => $page_name,
			'post_name'             => $page_slug,
			'post_status'           => 'draft',
			'post_type'             => $post_type,
			'comment_status'        => 'closed', // Match old flow.
			'ping_status'           => 'closed',    // Match old flow.
			'post_content'          => '', // SeedProd doesn't use post_content for builder pages.
			'post_content_filtered' => $encoded_settings, // This is where SeedProd stores the page data.
		);

		$page_id = wp_insert_post( $page_data );

		if ( is_wp_error( $page_id ) ) {
			wp_send_json_error( __( 'Failed to create page', 'coming-soon' ) );
		}
	}

	// Reinsert settings because wp_insert screws up json (following old working logic).
	global $wpdb;
	$tablename     = esc_sql( $wpdb->prefix . 'posts' );
	$sql           = "UPDATE $tablename SET post_content_filtered = %s WHERE id = %d";
	$safe_sql      = $wpdb->prepare( $sql, $encoded_settings, $page_id ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Table name escaped with esc_sql(), dynamic SQL assembled for prepare().
	$update_result = $wpdb->query( $safe_sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

	// Set SeedProd page meta (matching old flow - no _seedprod_page_id).
	update_post_meta( $page_id, '_seedprod_page', '1' );
	update_post_meta( $page_id, '_seedprod_page_template_type', $page_type );

	// Generate page UUID if not exists (matching existing SeedProd pattern).
	$existing_uuid = get_post_meta( $page_id, '_seedprod_page_uuid', true );
	if ( empty( $existing_uuid ) ) {
		$uuid = wp_generate_uuid4();
		update_post_meta( $page_id, '_seedprod_page_uuid', $uuid );
	}

	// Set theme template meta for template parts (matching old flow).
	$template_parts = array( 'header', 'footer', 'part', 'page' );
	if ( in_array( $page_type, $template_parts, true ) ) {
		update_post_meta( $page_id, '_seedprod_is_theme_template', true );
		// Note: _seedprod_theme_template_condition would need conditions data.
		// which should come from the template selection process
	}

	// Update WordPress options for special page types (matching old Vue logic).
	if ( 'cs' === $page_type ) {
		update_option( 'seedprod_coming_soon_page_id', $page_id );
	} elseif ( 'mm' === $page_type ) {
		update_option( 'seedprod_maintenance_mode_page_id', $page_id );
	} elseif ( 'loginp' === $page_type ) {
		update_option( 'seedprod_login_page_id', $page_id );
		update_option( 'seedprod_login_page_slug', $page_slug );
	} elseif ( 'p404' === $page_type ) {
		update_option( 'seedprod_404_page_id', $page_id );
	}

	// Return builder URL with setup hash fragment (matching Vue.js flow)
	// The hash fragment tells the builder it's a new page in setup mode.
	$builder_url = admin_url( 'admin.php?page=seedprod_lite_builder&id=' . $page_id . '#/setup/' . $page_id . '/block-options' );

	wp_send_json_success(
		array(
			'page_id'     => $page_id,
			'builder_url' => $builder_url,
		)
	);
}


/** Function seedprod_lite_duplicate_lpage() called by wp_ajax hooks: {'seedprod_lite_duplicate_lpage'} **/
/** Parameters found in function seedprod_lite_duplicate_lpage(): {"get": ["id"]} **/
function seedprod_lite_duplicate_lpage() {
	if ( check_ajax_referer( 'seedprod_lite_duplicate_lpage' ) ) {
		if ( ! current_user_can( apply_filters( 'seedprod_lpage_capability', 'edit_others_posts' ) ) ) {
			wp_send_json_error();
		}
		$id = '';
		if ( ! empty( $_GET['id'] ) ) {
			$id = absint( $_GET['id'] );
		}

		$post = get_post( $id );
		$json = $post->post_content_filtered;

		$args = array(
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
			'post_content'   => $post->post_content,
			// 'post_content_filtered' => $post->post_content_filtered,
			'post_status'    => 'draft',
			'post_title'     => $post->post_title . '- Copy',
			'post_type'      => 'page',
			'post_name'      => '',
			'meta_input'     => array(
				'_seedprod_page'      => true,
				'_seedprod_page_uuid' => wp_generate_uuid4(),
			),
		);

		$new_post_id = wp_insert_post( $args, true );
		// reinsert json due to slash bug.
		global $wpdb;
		$tablename = $wpdb->prefix . 'posts';
		$wpdb->update(
			$tablename,
			array(
				'post_content_filtered' => $json,   // string
			),
			array( 'ID' => $new_post_id ),
			array(
				'%s',   // value1
			),
			array( '%d' )
		);

		wp_send_json( array( 'status' => true ) );
	}
}


/** Function seedprod_lite_v2_subscribe_free_templates() called by wp_ajax hooks: {'seedprod_lite_v2_subscribe_free_templates'} **/
/** Parameters found in function seedprod_lite_v2_subscribe_free_templates(): {"post": ["email"]} **/
function seedprod_lite_v2_subscribe_free_templates() {
	// Check nonce.
	check_ajax_referer( 'seedprod_v2_nonce' );

	// Get email.
	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

	if ( empty( $email ) || ! is_email( $email ) ) {
		wp_send_json_error( __( 'Please enter a valid email address.', 'coming-soon' ) );
	}

	// Get site token.
	$site_token = get_option( 'seedprod_token', '' );

	// Call SeedProd API to subscribe.
	$api_url = SEEDPROD_API_URL . 'templates-subscribe';

	$response = wp_remote_post(
		$api_url,
		array(
			'body'    => array(
				'email'      => $email,
				'site_token' => $site_token,
			),
			'timeout' => 10,
		)
	);

	if ( is_wp_error( $response ) ) {
		wp_send_json_error( __( 'Failed to subscribe. Please try again.', 'coming-soon' ) );
	}

	$body = wp_remote_retrieve_body( $response );
	$data = json_decode( $body, true );

	// Mark as subscribed in database.
	update_option( 'seedprod_free_templates_subscribed', '1' );

	wp_send_json_success(
		array(
			'message' => __( 'You now have access to 10 FREE templates!', 'coming-soon' ),
		)
	);
}


/** Function seedprod_lite_v2_delete_saved_template() called by wp_ajax hooks: {'seedprod_lite_v2_delete_saved_template'} **/
/** Parameters found in function seedprod_lite_v2_delete_saved_template(): {"post": ["template_id"]} **/
function seedprod_lite_v2_delete_saved_template() {
	check_ajax_referer( 'seedprod_v2_nonce' );

	if ( ! current_user_can( apply_filters( 'seedprod_lpage_capability', 'edit_others_posts' ) ) ) {
		wp_send_json_error( __( 'You do not have permission to delete saved templates.', 'coming-soon' ) );
	}

	$template_id = isset( $_POST['template_id'] ) ? sanitize_text_field( wp_unslash( $_POST['template_id'] ) ) : '';

	if ( empty( $template_id ) ) {
		wp_send_json_error( __( 'Invalid template ID', 'coming-soon' ) );
	}

	// Saved templates live on the SeedProd API, same endpoint the old Vue chooser used.
	$api_token  = get_option( 'seedprod_api_token', '' );
	$site_token = get_option( 'seedprod_token', '' );

	$response = wp_remote_post(
		SEEDPROD_API_URL . 'template-update',
		array(
			'body'    => array(
				'template_id' => $template_id,
				'method'      => 'remove-saved',
				'api_token'   => $api_token,
				'site_token'  => $site_token,
			),
			'timeout' => 10,
		)
	);

	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		wp_send_json_error( __( 'Failed to delete saved template', 'coming-soon' ) );
	}

	wp_send_json_success();
}


/** Function seedprod_lite_archive_selected_lpages() called by wp_ajax hooks: {'seedprod_lite_archive_selected_lpages'} **/
/** Parameters found in function seedprod_lite_archive_selected_lpages(): {"get": ["ids"]} **/
function seedprod_lite_archive_selected_lpages() {
	if ( check_ajax_referer( 'seedprod_lite_archive_selected_lpages' ) ) {
		if ( current_user_can( apply_filters( 'seedprod_trash_pages_capability', 'list_users' ) ) ) {
			if ( ! empty( $_GET['ids'] ) ) {
				$ids = array_map( 'intval', explode( ',', sanitize_text_field( wp_unslash( $_GET['ids'] ) ) ) );
				foreach ( $ids as $v ) {
					wp_trash_post( $v );
				}

				wp_send_json( array( 'status' => true ) );
			}
		}
	}
}


/** Function seedprod_lite_v2_dismiss_setup_wizard() called by wp_ajax hooks: {'seedprod_lite_v2_dismiss_setup_wizard'} **/
/** No params detected :-/ **/


/** Function seedprod_lite_v2_get_favorite_templates() called by wp_ajax hooks: {'seedprod_lite_v2_get_favorite_templates'} **/
/** No params detected :-/ **/


/** Function seedprod_lite_upgrade_license() called by wp_ajax hooks: {'seedprod_lite_upgrade_license'} **/
/** No params detected :-/ **/


/** Function seedprod_lite_get_mypaykit() called by wp_ajax hooks: {'seedprod_lite_get_mypaykit'} **/
/** No params detected :-/ **/


/** Function seedprod_lite_get_utc_offset() called by wp_ajax hooks: {'seedprod_lite_get_utc_offset'} **/
/** Parameters found in function seedprod_lite_get_utc_offset(): {"post": ["timezone", "ends", "ends_time"]} **/
function seedprod_lite_get_utc_offset() {
	if ( check_ajax_referer( 'seedprod_lite_get_utc_offset' ) ) {
		$_POST = stripslashes_deep( $_POST );

		$timezone  = isset( $_POST['timezone'] ) ? sanitize_text_field( wp_unslash( $_POST['timezone'] ) ) : null;
		$ends      = isset( $_POST['ends'] ) ? sanitize_text_field( wp_unslash( $_POST['ends'] ) ) : null;
		$ends_time = isset( $_POST['ends_time'] ) ? sanitize_text_field( wp_unslash( $_POST['ends_time'] ) ) : null;

		// $ends = substr($ends, 0, strpos($ends, 'T'));
		$ends           = $ends . ' ' . $ends_time;
		$ends_timestamp = strtotime( $ends . ' ' . $timezone );
		$ends_utc       = gmdate( 'Y-m-d H:i:s', $ends_timestamp );

		// countdown status.
		$countdown_status = '';
		if ( ! empty( $starts_utc ) && time() < strtotime( $starts_utc . ' UTC' ) ) {
			$countdown_status = __( 'Starts in', 'coming-soon' ) . ' ' . human_time_diff( time(), $starts_timestamp );
		} elseif ( ! empty( $ends_utc ) && time() > strtotime( $ends_utc . ' UTC' ) ) {
			$countdown_status = __( 'Ended', 'coming-soon' ) . ' ' . human_time_diff( time(), $ends_timestamp ) . ' ago';
		}

		$response = array(
			'ends_timestamp'   => $ends_timestamp,
			'countdown_status' => $countdown_status,
		);

		wp_send_json( $response );
	}
}


/** Function seedprod_lite_v2_export_landing_pages() called by wp_ajax hooks: {'seedprod_lite_v2_export_landing_pages'} **/
/** Parameters found in function seedprod_lite_v2_export_landing_pages(): {"post": ["page_id", "ptype"]} **/
function seedprod_lite_v2_export_landing_pages() {

	// Validate request and permissions
	try {
		seedprod_lite_v2_validate_ajax_request( 'export' );
	} catch ( Exception $e ) {
		wp_send_json_error( 'Validation failed: ' . $e->getMessage() );
	}

	// Initialize filesystem
	try {
		seedprod_lite_v2_init_filesystem();
	} catch ( Exception $e ) {
		wp_send_json_error( 'Filesystem initialization failed: ' . $e->getMessage() );
	}

	$page_id = isset( $_POST['page_id'] ) ? absint( sanitize_text_field( wp_unslash( $_POST['page_id'] ) ) ) : 0;
	$ptype   = isset( $_POST['ptype'] ) ? sanitize_text_field( wp_unslash( $_POST['ptype'] ) ) : null;

	global $wpdb;
	$tablename      = $wpdb->prefix . 'posts';
	$meta_tablename = $wpdb->prefix . 'postmeta';

	// Get list of landing pages - EXACT same logic as dropdown query
	// Get special page IDs (Coming Soon, Maintenance Mode, Login, 404)
	$coming_soon_id = get_option( 'seedprod_coming_soon_page_id' );
	$maintenance_id = get_option( 'seedprod_maintenance_mode_page_id' );
	$login_id       = get_option( 'seedprod_login_page_id' );
	$fourohfour_id  = get_option( 'seedprod_404_page_id' );

	$special_page_ids = array();
	if ( ! empty( $coming_soon_id ) ) {
		$special_page_ids[] = $coming_soon_id;
	}
	if ( ! empty( $maintenance_id ) ) {
		$special_page_ids[] = $maintenance_id;
	}
	if ( ! empty( $login_id ) ) {
		$special_page_ids[] = $login_id;
	}
	if ( ! empty( $fourohfour_id ) ) {
		$special_page_ids[] = $fourohfour_id;
	}

	$special_pages_condition = '';
	if ( ! empty( $special_page_ids ) ) {
		$special_ids_string      = implode( ',', array_map( 'intval', $special_page_ids ) );
		$special_pages_condition = " OR p.ID IN ($special_ids_string)";
	}

	$sql  = "SELECT p.*, pm.meta_key, pm.meta_value FROM $tablename p ";
	$sql .= "LEFT JOIN $meta_tablename pm ON (pm.post_id = p.ID AND pm.meta_key = '_seedprod_page_uuid') ";
	$sql .= "LEFT JOIN $meta_tablename pm4 ON (pm4.post_id = p.ID AND pm4.meta_key = '_seedprod_page_template_type') ";
	$sql .= "LEFT JOIN $meta_tablename pm5 ON (pm5.post_id = p.ID AND pm5.meta_key = '_seedprod_page') ";
	$sql .= "WHERE p.post_status != 'trash' ";
	$sql .= "AND p.post_type IN ('page', 'seedprod') ";
	$sql .= "AND ((p.post_type = 'page' AND pm4.meta_value = 'lp') OR (p.post_type = 'page' AND pm5.meta_value IS NOT NULL) $special_pages_condition) ";
	$sql .= "AND (pm.meta_value IS NOT NULL OR pm5.meta_value IS NOT NULL $special_pages_condition) ";

	if ( 0 !== $page_id ) {
		$sql .= "AND p.ID = $page_id ";
	}

	$sql .= 'GROUP BY p.ID ';
	$sql .= 'ORDER BY p.post_date DESC';

	$results = $wpdb->get_results( $sql ); // phpcs:ignore

	if ( $wpdb->last_error ) {
		wp_send_json_error( 'Database error: ' . $wpdb->last_error );
	}

	// Check if we have any pages to export
	if ( empty( $results ) ) {
		wp_send_json_error( __( 'No landing pages found to export.', 'coming-soon' ) );
	}

	// Wrap in try-catch to catch any errors
	try {
		// Process export data using helper function
		$export_result = seedprod_lite_v2_process_export_data( $results, 'page', $ptype );

		if ( is_wp_error( $export_result ) ) {
			wp_send_json_error( $export_result->get_error_message() );
		}

		// Validate export result structure
		if ( ! isset( $export_result['export'] ) || ! isset( $export_result['processed_data'] ) || ! isset( $export_result['shortcode_exports'] ) ) {
			wp_send_json_error( __( 'Export data structure is invalid.', 'coming-soon' ) );
		}

		$export            = $export_result['export'];
		$processed_data    = $export_result['processed_data'];
		$shortcode_exports = $export_result['shortcode_exports'];

	} catch ( Exception $e ) {
		/* translators: %s: Error message from the exception */
		wp_send_json_error( sprintf( __( 'Export failed during processing: %s', 'coming-soon' ), $e->getMessage() ) );
	}

	// Process shortcode mapping for landing pages (restored from old logic)
	foreach ( $shortcode_exports as $k => $sc_val ) {
		$shortcode_page_id = $sc_val['id'];  // Use different variable name to avoid overwriting original $page_id
		$page_shortcode    = $sc_val['shortcode'];

		$sql      = "SELECT p.post_title FROM $tablename p LEFT JOIN $meta_tablename pm ON (pm.post_id = p.ID)";
		$sql     .= " WHERE p.ID = %d and post_status != 'trash' AND post_type IN ('page','seedprod') AND meta_key = '_seedprod_page_uuid' ";
		$safe_sql = $wpdb->prepare( $sql, absint( $shortcode_page_id ) ); // phpcs:ignore
		$page     = $wpdb->get_row( $safe_sql ); // phpcs:ignore

		if ( ! empty( $page ) ) {
			$export['mapped'][] = array(
				'id'         => $shortcode_page_id,
				'shortcode'  => base64_encode( $page_shortcode ),// phpcs:ignore
				'page_title' => $page->post_title,
			);
		}
	}

	$export_json = wp_json_encode( $export );

	// Check if JSON encoding failed
	if ( false === $export_json ) {
		$json_error = json_last_error_msg();
		wp_send_json_error( __( 'Failed to encode export data. The page data may be too large or contain invalid characters.', 'coming-soon' ) . ' Error: ' . $json_error );
	}

	$files_to_download = array();

	global $wp_filesystem;
	$upload_dir = wp_upload_dir();
	$path       = trailingslashit( $upload_dir['basedir'] ) . 'seedprod-themes-exports/';
	$targetdir  = $path;

	// Create directory if it doesn't exist (don't delete - prevents race condition with concurrent exports).
	if ( ! is_dir( $targetdir ) ) {
		if ( ! wp_mkdir_p( $targetdir ) ) {
			wp_send_json_error( __( 'Failed to create export directory.', 'coming-soon' ) );
		}
	}

	// Clean up old files (older than 1 hour) to prevent disk bloat.
	// This preserves recent exports from other users while still managing disk space.
	$old_files    = glob( $targetdir . '*' );
	$one_hour_ago = time() - HOUR_IN_SECONDS;
	if ( $old_files ) {
		foreach ( $old_files as $old_file ) {
			if ( filemtime( $old_file ) < $one_hour_ago ) {
				wp_delete_file( $old_file );
			}
		}
	}

	// Save images locally
	$all_failed_images = array();

	// Process images
	try {
		foreach ( $processed_data as $k1 => $v1 ) {
			// Check if images key exists and has items
			if ( isset( $processed_data[ $k1 ]['images'] ) && is_array( $processed_data[ $k1 ]['images'] ) && count( $processed_data[ $k1 ]['images'] ) > 0 ) {
				$failed_images = seedprod_lite_v2_save_images_locally( $processed_data[ $k1 ]['images'] );

				if ( is_array( $failed_images ) ) {
					$all_failed_images = array_merge( $all_failed_images, $failed_images );
				}
			}

			// Only add successfully downloaded images to the zip
			if ( isset( $processed_data[ $k1 ]['images'] ) && is_array( $processed_data[ $k1 ]['images'] ) ) {
				foreach ( $processed_data[ $k1 ]['images'] as $image ) {
					if ( ! isset( $image['filename'] ) ) {
						continue;
					}

					$image_path = trailingslashit( $upload_dir['basedir'] ) . 'seedprod-themes-exports/' . $image['filename'];

					if ( file_exists( $image_path ) ) {
						$files_to_download[] = $image['filename'];
					}
				}
			}
		}
	} catch ( Exception $e ) {
		/* translators: %s: Error message from the exception */
		wp_send_json_error( sprintf( __( 'Export failed during image processing: %s', 'coming-soon' ), $e->getMessage() ) );
	}

	// Create zip and return download URL
	try {
		$zip_result = seedprod_lite_v2_prepare_zip( $files_to_download, $export_json, 'page' );

		if ( $zip_result && isset( $zip_result['filedata'] ) ) {
			wp_send_json_success( $zip_result );
		} else {
			wp_send_json_error( __( 'Export completed but file data not generated.', 'coming-soon' ) );
		}
	} catch ( Exception $e ) {
		/* translators: %s: Error message from the exception */
		wp_send_json_error( sprintf( __( 'Export failed during ZIP creation: %s', 'coming-soon' ), $e->getMessage() ) );
	}

	exit;
}


/** Function seedprod_lite_dismiss_settings_lite_cta() called by wp_ajax hooks: {'seedprod_lite_dismiss_settings_lite_cta'} **/
/** Parameters found in function seedprod_lite_dismiss_settings_lite_cta(): {"post": ["dismiss"]} **/
function seedprod_lite_dismiss_settings_lite_cta() {
	if ( check_ajax_referer( 'seedprod_lite_dismiss_settings_lite_cta' ) ) {
		if ( ! empty( $_POST['dismiss'] ) ) {
			update_option( 'seedprod_dismiss_settings_lite_cta', true );

			$response = array(
				'status' => 'true',

			);
		}

		// Send Response.
		wp_send_json( $response );
		exit;
	}
}


/** Function seedprod_lite_v2_complete_setup_wizard() called by wp_ajax hooks: {'seedprod_lite_v2_complete_setup_wizard'} **/
/** Parameters found in function seedprod_lite_v2_complete_setup_wizard(): {"post": ["wizard_id"]} **/
function seedprod_lite_v2_complete_setup_wizard() {
	if ( check_ajax_referer( 'seedprod_lite_v2_complete_setup_wizard' ) ) {
		if ( ! current_user_can( apply_filters( 'seedprod_lpage_capability', 'edit_others_posts' ) ) ) {
			wp_send_json_error();
		}

		$wizard_id = isset( $_POST['wizard_id'] ) ? sanitize_text_field( wp_unslash( $_POST['wizard_id'] ) ) : null;

		// Get the wizard data with id and token.
		$site_token = get_option( 'seedprod_token' );

		$data = array(
			'wizard_id'  => $wizard_id,
			'site_token' => $site_token,
		);

		$headers = array();

		// Build the headers of the request.
		$headers = wp_parse_args(
			$headers,
			array(
				'Accept' => 'application/json',
			)
		);

		$url      = SEEDPROD_API_URL . 'get-wizard-data';
		$response = wp_remote_post(
			$url,
			array(
				'body'    => $data,
				'headers' => $headers,
			)
		);

		$status_code = wp_remote_retrieve_response_code( $response );

		// Handle errors.
		if ( is_wp_error( $response ) ) {
			// Load utility functions for get_ip if needed.
			if ( ! function_exists( 'seedprod_lite_v2_get_ip' ) ) {
				require_once plugin_dir_path( __FILE__ ) . 'utility-functions.php';
			}
			$response = array(
				'status' => 'false',
				'ip'     => seedprod_lite_v2_get_ip(),
				'msg'    => $response->get_error_message(),
			);
			wp_send_json( $response );
		}

		if ( 200 !== $status_code ) {
			// Load utility functions for get_ip if needed.
			if ( ! function_exists( 'seedprod_lite_v2_get_ip' ) ) {
				require_once plugin_dir_path( __FILE__ ) . 'utility-functions.php';
			}
			$response = array(
				'status' => 'false',
				'ip'     => seedprod_lite_v2_get_ip(),
				'msg'    => $response['response']['message'],
			);
			wp_send_json( $response );
		}

		$body = wp_remote_retrieve_body( $response );

		if ( ! empty( $body ) ) {
			$body = json_decode( $body );
		}

		// Store the wizard id and data locally.
		$onboarding = $body->onboarding;

		// Store the wizard verify plugins.
		update_option( 'seedprod_verify_wizard_options', $onboarding->options );

		// Mark wizard as completed/dismissed so it won't show again.
		update_option( 'seedprod_dismiss_setup_wizard', 1 );

		// Set tracking if they have opted in.
		if ( ! empty( $onboarding->allow_usagetracking ) ) {
			update_option( 'seedprod_allow_usage_tracking', true );
		}

		// Free templates.
		if ( ! empty( $onboarding->email ) ) {
			update_option( 'seedprod_free_templates_subscribed', true );
		}

		// Get template type that was setup in the onboarding.
		$type = 'lp';
		if ( ! empty( $onboarding->sp_type ) ) {
			$type = $onboarding->sp_type;
		}

		$id = null;

		// Create a landing page/coming soon/maintenance/404/login page.
		if ( 'lp' === $type || 'cs' === $type || 'mm' === $type || 'p404' === $type || 'loginp' === $type ) {

			// Install template.
			$cpt = 'page';
			// SeedProd CPT types.
			$cpt_types = array(
				'cs',
				'mm',
				'p404',
				'loginp',
				'header',
				'footer',
				'part',
				'page',
			);

			if ( in_array( $type, $cpt_types, true ) ) {
				$cpt = 'seedprod';
			}

			// Base page settings.
			require_once SEEDPROD_PLUGIN_PATH . 'resources/data-templates/basic-page.php';
			$basic_settings              = json_decode( $seedprod_basic_lpage, true );
			$basic_settings['is_new']    = true;
			$basic_settings['page_type'] = $type;

			// Set slug based on type.
			$slug       = '';
			$lpage_name = '';

			if ( 'cs' === $type ) {
				$slug                               = 'sp-cs';
				$lpage_name                         = $slug;
				$basic_settings['no_conflict_mode'] = true;
			}
			if ( 'mm' === $type ) {
				$slug                               = 'sp-mm';
				$lpage_name                         = $slug;
				$basic_settings['no_conflict_mode'] = true;
			}
			if ( 'p404' === $type ) {
				$slug                               = 'sp-p404';
				$lpage_name                         = $slug;
				$basic_settings['no_conflict_mode'] = true;
			}
			if ( 'loginp' === $type ) {
				$slug                               = 'sp-login';
				$lpage_name                         = $slug;
				$basic_settings['no_conflict_mode'] = true;
			}

			// Insert page code.
			$code = '';
			if ( ! empty( $onboarding->code ) ) {
				$code = base64_decode( $onboarding->code ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode -- Used for decoding template data, not obfuscation.
			}

			$code = json_decode( $code, true );

			// Merge in code.
			if ( ! empty( $slug ) ) {
				$basic_settings['post_title'] = $slug;
				$basic_settings['post_name']  = $slug;
			}

			$basic_settings['template_id'] = intval( $onboarding->template_id );

			$new_settings = $basic_settings;
			if ( 99999 !== $onboarding->template_id ) {
				unset( $basic_settings['document'] );
				if ( is_array( $code ) ) {
					$new_settings = $basic_settings + $code;
				}
			}

			$encoded_settings = wp_json_encode( $new_settings );

			$id = wp_insert_post(
				array(
					'comment_status'        => 'closed',
					'ping_status'           => 'closed',
					'post_content'          => '',
					'post_status'           => 'draft',
					'post_title'            => 'seedprod',
					'post_type'             => $cpt,
					'post_name'             => $slug,
					'post_content_filtered' => $encoded_settings,
					'meta_input'            => array(
						'_seedprod_page'               => true,
						'_seedprod_page_uuid'          => wp_generate_uuid4(),
						'_seedprod_page_template_type' => $type,
					),
				),
				true
			);

			// Reinsert settings because wp_insert screws up json (following old working logic).
			if ( ! is_wp_error( $id ) && ! empty( $encoded_settings ) ) {
				global $wpdb;
				$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->posts} SET post_content_filtered = %s WHERE id = %d", $encoded_settings, $id ) );
				clean_post_cache( $id );
			}

			// Update pointer - record page IDs for each type.
			if ( 'cs' === $type ) {
				update_option( 'seedprod_coming_soon_page_id', $id );
			}
			if ( 'mm' === $type ) {
				update_option( 'seedprod_maintenance_mode_page_id', $id );
			}
			if ( 'p404' === $type ) {
				update_option( 'seedprod_404_page_id', $id );
			}
			if ( 'loginp' === $type ) {
				update_option( 'seedprod_login_page_id', $id );
			}

			// If landing page set a temp name.
			if ( 'lp' === $type ) {
				if ( is_numeric( $id ) ) {
					$lpage_name = esc_html__( 'New Page', 'coming-soon' ) . " (ID #$id)";
				} else {
					$lpage_name = esc_html__( 'New Page', 'coming-soon' );
				}
			}

			wp_update_post(
				array(
					'ID'         => $id,
					'post_title' => $lpage_name,
				)
			);
		}

		// Install theme if theme is the type.
		if ( 'websitebuilder' === $type || 'woocommerce' === $type ) {
			$template_id = $onboarding->template_id;

			// Call theme import function if it exists.
			if ( function_exists( 'seedprod_lite_theme_import' ) ) {
				seedprod_lite_theme_import( $template_id );
			}
		}

		// Filter out already installed plugins.
		$filtered_options = array();
		if ( ! empty( $onboarding->options ) ) {
			$options_array = json_decode( $onboarding->options, true );
			if ( is_array( $options_array ) ) {
				$all_plugins = get_plugins();

				// Check each recommended plugin.
				foreach ( $options_array as $plugin_key ) {
					$needs_install = false;

					switch ( $plugin_key ) {
						case 'rafflepress':
							if ( ! isset( $all_plugins['rafflepress/rafflepress.php'] ) &&
								! isset( $all_plugins['rafflepress-pro/rafflepress-pro.php'] ) ) {
								$needs_install = true;
							}
							break;
						case 'allinoneseo':
							if ( ! isset( $all_plugins['all-in-one-seo-pack/all_in_one_seo_pack.php'] ) &&
								! isset( $all_plugins['all-in-one-seo-pack-pro/all_in_one_seo_pack.php'] ) &&
								! isset( $all_plugins['seo-by-rank-math/rank-math.php'] ) &&
								! isset( $all_plugins['wordpress-seo/wp-seo.php'] ) &&
								! isset( $all_plugins['wordpress-seo-premium/wp-seo-premium.php'] ) ) {
								$needs_install = true;
							}
							break;
						case 'wpforms':
							if ( ! isset( $all_plugins['wpforms-lite/wpforms.php'] ) &&
								! isset( $all_plugins['wpforms/wpforms.php'] ) ) {
								$needs_install = true;
							}
							break;
						case 'optinmonster':
							if ( ! isset( $all_plugins['optinmonster/optin-monster-wp-api.php'] ) ) {
								$needs_install = true;
							}
							break;
						case 'ga':
						case 'monsterinsights':
							if ( ! isset( $all_plugins['google-analytics-for-wordpress/googleanalytics.php'] ) &&
								! isset( $all_plugins['google-analytics-premium/googleanalytics-premium.php'] ) ) {
								$needs_install = true;
							}
							break;
						case 'vibe-ai':
							if ( ! isset( $all_plugins['vibe-ai/vibe-ai.php'] ) ) {
								$needs_install = true;
							}
							break;
					}

					if ( $needs_install ) {
						$filtered_options[] = $plugin_key;
					}
				}
			}
		}

		// Return response.
		$response = array(
			'status'  => 'true',
			'type'    => $type,
			'id'      => $id,
			'options' => $filtered_options,  // Return filtered array, not JSON string.
		);

		wp_send_json_success( $response );
	}
}


/** Function seedprod_lite_v2_bulk_action_lpages() called by wp_ajax hooks: {'seedprod_lite_v2_bulk_action_lpages'} **/
/** Parameters found in function seedprod_lite_v2_bulk_action_lpages(): {"post": ["bulk_action", "page_ids"]} **/
function seedprod_lite_v2_bulk_action_lpages() {
	// Check nonce.
	if ( ! check_ajax_referer( 'seedprod_v2_nonce', 'nonce', false ) ) {
		wp_send_json_error( __( 'Security check failed.', 'coming-soon' ) );
	}

	// Check permissions.
	if ( ! current_user_can( apply_filters( 'seedprod_lpage_capability', 'edit_others_posts' ) ) ) {
		wp_send_json_error( __( 'You do not have permission to perform bulk actions.', 'coming-soon' ) );
	}

	// Get the action and page IDs.
	$action   = isset( $_POST['bulk_action'] ) ? sanitize_text_field( wp_unslash( $_POST['bulk_action'] ) ) : '';
	$page_ids = isset( $_POST['page_ids'] ) ? array_map( 'absint', $_POST['page_ids'] ) : array();

	if ( empty( $action ) || empty( $page_ids ) ) {
		wp_send_json_error( __( 'Invalid request.', 'coming-soon' ) );
	}

	$success_count = 0;
	$error_count   = 0;

	foreach ( $page_ids as $id ) {
		$result = false;

		switch ( $action ) {
			case 'trash':
				$result = wp_trash_post( $id );
				break;
			case 'restore':
				$result = wp_untrash_post( $id );
				break;
			case 'delete':
				$result = wp_delete_post( $id, true );
				break;
		}

		if ( $result ) {
			++$success_count;
		} else {
			++$error_count;
		}
	}

	$message = '';
	switch ( $action ) {
		case 'trash':
			/* translators: %d: number of pages */
			$message = sprintf( __( '%d pages moved to trash.', 'coming-soon' ), $success_count );
			break;
		case 'restore':
			/* translators: %d: number of pages */
			$message = sprintf( __( '%d pages restored.', 'coming-soon' ), $success_count );
			break;
		case 'delete':
			/* translators: %d: number of pages */
			$message = sprintf( __( '%d pages deleted permanently.', 'coming-soon' ), $success_count );
			break;
	}

	if ( $error_count > 0 ) {
		/* translators: %d: number of pages */
		$message .= ' ' . sprintf( __( '%d pages failed.', 'coming-soon' ), $error_count );
	}

	if ( $success_count > 0 ) {
		wp_send_json_success( array( 'message' => $message ) );
	} else {
		wp_send_json_error( $message );
	}
}


/** Function seedprod_upgrade_license() called by wp_ajax hooks: {'seedprod_upgrade_license'} **/
/** No function found :-/ **/


/** Function seedprod_lite_template_subscribe() called by wp_ajax hooks: {'seedprod_lite_template_subscribe'} **/
/** No params detected :-/ **/


/** Function seedprod_lite_v2_activate_plugin() called by wp_ajax hooks: {'seedprod_lite_v2_activate_plugin'} **/
/** Parameters found in function seedprod_lite_v2_activate_plugin(): {"post": ["plugin"]} **/
function seedprod_lite_v2_activate_plugin() {
	// Clean any output that might have been generated.
	if ( ob_get_length() ) {
		ob_clean();
	}

	// Start output buffering to catch any unexpected output.
	ob_start();

	// Security check.
	check_ajax_referer( 'seedprod_nonce' );

	// Check permissions.
	if ( ! current_user_can( 'activate_plugins' ) ) {
		ob_clean();
		wp_send_json_error( __( 'Permission denied.', 'coming-soon' ) );
	}

	// Get plugin slug.
	if ( ! isset( $_POST['plugin'] ) ) {
		ob_clean();
		wp_send_json_error( __( 'Plugin slug required.', 'coming-soon' ) );
	}

	$plugin   = sanitize_text_field( wp_unslash( $_POST['plugin'] ) );
	$activate = activate_plugin( $plugin, '', false, true );

	// Clear any output before sending response.
	ob_clean();

	if ( ! is_wp_error( $activate ) ) {
		wp_send_json_success( __( 'Plugin activated.', 'coming-soon' ) );
	} else {
		wp_send_json_error( $activate->get_error_message() );
	}
}


/** Function seedprod_lite_get_wpform() called by wp_ajax hooks: {'seedprod_lite_get_wpform'} **/
/** No params detected :-/ **/


/** Function seedprod_lite_get_edd_download_taxonomy() called by wp_ajax hooks: {'seedprod_lite_get_edd_download_taxonomy'} **/
/** No function found :-/ **/


/** Function seedprod_lite_v2_restore_lpage() called by wp_ajax hooks: {'seedprod_lite_v2_restore_lpage'} **/
/** Parameters found in function seedprod_lite_v2_restore_lpage(): {"post": ["id"]} **/
function seedprod_lite_v2_restore_lpage() {
	// Check nonce.
	if ( ! check_ajax_referer( 'seedprod_v2_nonce', 'nonce', false ) ) {
		wp_send_json_error( __( 'Security check failed.', 'coming-soon' ) );
	}

	// Check permissions.
	if ( ! current_user_can( apply_filters( 'seedprod_lpage_capability', 'edit_others_posts' ) ) ) {
		wp_send_json_error( __( 'You do not have permission to restore pages.', 'coming-soon' ) );
	}

	// Get the page ID.
	$id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;

	if ( ! $id ) {
		wp_send_json_error( __( 'Invalid page ID.', 'coming-soon' ) );
	}

	// Restore the post.
	$result = wp_untrash_post( $id );

	if ( ! $result ) {
		wp_send_json_error( __( 'Failed to restore page.', 'coming-soon' ) );
	}

	wp_send_json_success(
		array(
			'message' => __( 'Page restored successfully.', 'coming-soon' ),
		)
	);
}


/** Function seedprod_lite_save_settings() called by wp_ajax hooks: {'seedprod_lite_save_settings'} **/
/** Parameters found in function seedprod_lite_save_settings(): {"post": ["settings"]} **/
function seedprod_lite_save_settings() {
	if ( check_ajax_referer( 'seedprod_nonce' ) ) {
		if ( ! current_user_can( apply_filters( 'seedprod_save_settings_capability', 'edit_others_posts' ) ) ) {
			wp_send_json_error( null, 400 );
		}
		if ( ! empty( $_POST['settings'] ) ) {
			$settings = wp_unslash( $_POST['settings'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

			$s = json_decode( $settings );

			$s->api_key                 = sanitize_text_field( $s->api_key );
			$s->enable_coming_soon_mode = sanitize_text_field( $s->enable_coming_soon_mode );
			$s->enable_maintenance_mode = sanitize_text_field( $s->enable_maintenance_mode );
			$s->enable_login_mode       = sanitize_text_field( $s->enable_login_mode );
			$s->enable_404_mode         = sanitize_text_field( $s->enable_404_mode );

			// Get old settings to check if there has been a change.
			$settings_old = get_option( 'seedprod_settings' );
			$s_old        = json_decode( $settings_old );

			// Key is for $settings, value is for get_option().
			$settings_to_update = array(
				'enable_coming_soon_mode' => 'seedprod_coming_soon_page_id',
				'enable_maintenance_mode' => 'seedprod_maintenance_mode_page_id',
				'enable_login_mode'       => 'seedprod_login_page_id',
				'enable_404_mode'         => 'seedprod_404_page_id',
			);

			foreach ( $settings_to_update as $setting => $option ) {
				$id = get_option( $option );

				if ( ! $id ) {
					continue;
				}

				$page = get_post( $id );
				if ( ! $page ) {
					update_option( $option, null );
					continue;
				}

				// Determine if setting is enabled (handle various truthy values).
				$is_enabled = ! empty( $s->$setting ) && 'false' !== $s->$setting && '0' !== $s->$setting;

				// Always ensure page status matches the setting.
				$expected_status = $is_enabled ? 'publish' : 'draft';
				if ( $page->post_status !== $expected_status ) {
					wp_update_post(
						array(
							'ID'          => $id,
							'post_status' => $expected_status,
						)
					);
				}
			}

			update_option( 'seedprod_settings', $settings );

			// Check if we should show AI website builder message (lite users only).
			$show_ai_message = false;

			if ( 'lite' === SEEDPROD_BUILD ) {
				// Check if Coming Soon or Maintenance Mode was toggled.
				$coming_soon_changed = isset( $s->enable_coming_soon_mode ) && isset( $s_old->enable_coming_soon_mode ) && ( $s->enable_coming_soon_mode !== $s_old->enable_coming_soon_mode );
				$maintenance_changed = isset( $s->enable_maintenance_mode ) && isset( $s_old->enable_maintenance_mode ) && ( $s->enable_maintenance_mode !== $s_old->enable_maintenance_mode );

				if ( $coming_soon_changed || $maintenance_changed ) {
					// Check if user has already seen the AI message.
					$user_id = get_current_user_id();
					$user_id = absint( $user_id ); // Validate user ID

					if ( $user_id > 0 ) {
						$seen_ai_message = get_user_meta( $user_id, 'seedprod_seen_ai_message', true );

						if ( ! $seen_ai_message ) {
							$show_ai_message = true;
							// Mark as seen.
							update_user_meta( $user_id, 'seedprod_seen_ai_message', true );
						}
					}
				}
			}

			$response = array(
				'status'          => 'true',
				'msg'             => __( 'Settings Updated', 'coming-soon' ),
				'show_ai_message' => $show_ai_message,
			);
		} else {
			$response = array(
				'status' => 'false',
				'msg'    => __( 'Error Updating Settings', 'coming-soon' ),
			);
		}

		// Send Response.
		wp_send_json( $response );
		exit;
	}
}


/** Function seedprod_lite_subscribers_datatable() called by wp_ajax hooks: {'seedprod_lite_subscribers_datatable'} **/
/** No function found :-/ **/


/** Function seedprod_lite_get_woocommerce_products() called by wp_ajax hooks: {'seedprod_lite_get_woocommerce_products'} **/
/** No function found :-/ **/


/** Function seedprod_lite_dismiss_upsell() called by wp_ajax hooks: {'seedprod_lite_dismiss_upsell'} **/
/** Parameters found in function seedprod_lite_dismiss_upsell(): {"post": ["id"]} **/
function seedprod_lite_dismiss_upsell() {
	if ( check_ajax_referer( 'seedprod_lite_dismiss_upsell' ) ) {
		if ( ! empty( $_POST['id'] ) ) {
			$ts = time();
			update_option( 'seedprod_dismiss_upsell_' . absint( $_POST['id'] ), $ts );
			$response = array(
				'status' => 'true',

			);
		}

		// Send Response.
		wp_send_json( $response );
		exit;
	}
}


/** Function seedprod_lite_v2_import_landing_pages() called by wp_ajax hooks: {'seedprod_lite_v2_import_landing_pages'} **/
/** Parameters found in function seedprod_lite_v2_import_landing_pages(): {"files": ["seedprod_landing_files"]} **/
function seedprod_lite_v2_import_landing_pages() {
	// Validate request and permissions
	seedprod_lite_v2_validate_ajax_request( 'install_themes' );

	// Validate and get uploaded file
	$zip_validation = seedprod_lite_v2_validate_zip_upload( 'seedprod_landing_files' );
	if ( is_wp_error( $zip_validation ) ) {
		wp_send_json_error( $zip_validation->get_error_message() );
	}

	// Initialize filesystem
	// Note: V2 uses direct filesystem access because this is an AJAX handler
	// and request_filesystem_credentials() doesn't work properly in AJAX context
	seedprod_lite_v2_init_filesystem();

	if ( isset( $_FILES['seedprod_landing_files']['name'] ) ) {
		$filename = wp_unslash( $_FILES['seedprod_landing_files']['name'] ); // phpcs:ignore
		$source = $_FILES['seedprod_landing_files']['tmp_name']; // phpcs:ignore

		// Setup directories
		$filename_import = 'seedprod-themes-imports';
		$upload_dir      = wp_upload_dir();
		$path            = trailingslashit( $upload_dir['basedir'] );
		$path_baseurl    = trailingslashit( $upload_dir['baseurl'] );
		$filenoext       = basename( $filename_import, '.zip' );
		$filenoext       = basename( $filenoext, '.ZIP' );
		$targetdir       = $path . $filenoext;
		$targetzip       = $path . $filename;
		$target_url      = $path_baseurl . $filenoext;

		// Remove existing import directory if exists
		if ( is_dir( $targetdir ) ) {
			// Directory cleanup handled by V2 function
			seedprod_lite_v2_recursive_rmdir( $targetdir );
		}

		// Create import directory
		wp_mkdir_p( $targetdir );

		// phpcs:ignore Generic.PHP.ForbiddenFunctions.Found -- move_uploaded_file is required for handling HTTP file uploads securely.
		if ( move_uploaded_file( $source, $targetzip ) ) {
			// Validate zip contents
			$validation_result = seedprod_lite_v2_validate_import_zip( $targetzip, false );
			if ( is_wp_error( $validation_result ) ) {
				wp_delete_file( $targetzip );
				seedprod_lite_v2_recursive_rmdir( $targetdir );
				wp_send_json_error( $validation_result->get_error_message() );
			}

			// Extract ZIP file
			$extract_result = seedprod_lite_v2_extract_zip( $targetzip, $targetdir, false );
			if ( is_wp_error( $extract_result ) ) {
				wp_delete_file( $targetzip );
				seedprod_lite_v2_recursive_rmdir( $targetdir );
				wp_send_json_error( $extract_result->get_error_message() );
			}

			// Delete the zip file after extraction
			wp_delete_file( $targetzip );

			// Read and validate landing page data
			$theme_json_path = $targetdir . '/export_page.json';
			$url_path        = $target_url . '/export_page.json';

			$data = seedprod_lite_v2_read_json_file( $theme_json_path, $url_path );
			if ( is_wp_error( $data ) ) {
				seedprod_lite_v2_recursive_rmdir( $targetdir );
				wp_send_json_error( $data->get_error_message() );
			}

			// Validate landing page type
			if ( ! empty( $data->type ) && 'landing-page' !== $data->type ) {
				seedprod_lite_v2_recursive_rmdir( $targetdir );
				wp_send_json_error( __( 'This does not appear to be a SeedProd landing page.', 'coming-soon' ) );
			}

			// Process the import
			// Process the landing page import
			$import_result = seedprod_lite_v2_landing_import_json( $data );
			$warnings      = isset( $import_result['warnings'] ) && is_array( $import_result['warnings'] )
				? $import_result['warnings']
				: array();

			// Remove the json file for security
			wp_delete_file( $theme_json_path );

			// Clean up import directory
			seedprod_lite_v2_recursive_rmdir( $targetdir );

			wp_send_json_success( array( 'warnings' => $warnings ) );
		} else {
			$message = __( 'There was a problem with the upload. Please try again.', 'coming-soon' );
			wp_send_json_error( $message );
		}
	} else {
		wp_send_json_error( __( 'Invalid file upload.', 'coming-soon' ) );
	}
}


/** Function seedprod_lite_v2_install_addon_setup() called by wp_ajax hooks: {'seedprod_lite_v2_install_addon_setup'} **/
/** Parameters found in function seedprod_lite_v2_install_addon_setup(): {"post": ["plugin"]} **/
function seedprod_lite_v2_install_addon_setup() {
	// Run a security check.
	check_ajax_referer( 'seedprod_lite_v2_install_addon_setup', 'nonce' );

	// Check for permissions.
	if ( ! current_user_can( 'install_plugins' ) ) {
		wp_send_json_error();
	}

	// Plugin mapping.
	$paths_map = array(
		'rafflepress'  => array(
			'slug' => 'rafflepress/rafflepress.php',
			'url'  => 'https://downloads.wordpress.org/plugin/rafflepress.zip',
		),
		'allinoneseo'  => array(
			'slug' => 'all-in-one-seo-pack/all_in_one_seo_pack.php',
			'url'  => 'https://downloads.wordpress.org/plugin/all-in-one-seo-pack.zip',
		),
		'ga'           => array(
			'slug' => 'google-analytics-for-wordpress/googleanalytics.php',
			'url'  => 'https://downloads.wordpress.org/plugin/google-analytics-for-wordpress.zip',
		),
		'wpforms'      => array(
			'slug' => 'wpforms-lite/wpforms.php',
			'url'  => 'https://downloads.wordpress.org/plugin/wpforms-lite.zip',
		),
		'optinmonster' => array(
			'slug' => 'optinmonster/optin-monster-wp-api.php',
			'url'  => 'https://downloads.wordpress.org/plugin/optinmonster.zip',
		),
		'vibe-ai'      => array(
			'slug' => 'vibe-ai/vibe-ai.php',
			'url'  => 'https://downloads.wordpress.org/plugin/vibe-ai.zip',
		),
	);

	$options = get_option( 'seedprod_verify_wizard_options' );
	$options = json_decode( $options );

	// This allows us to do one at a time.
	if ( isset( $_POST['plugin'] ) ) {
		$plugin  = sanitize_text_field( wp_unslash( $_POST['plugin'] ) );
		$options = array( $plugin );
	}

	$install_plugins = array();
	$all_plugins     = get_plugins();

	// Purge options to make sure we don't install plugins with conflicts.
	if ( in_array( 'allinoneseo', $options, true ) ) {
		if (
			isset( $all_plugins['all-in-one-seo-pack/all_in_one_seo_pack.php'] ) ||
			isset( $all_plugins['all-in-one-seo-pack-pro/all_in_one_seo_pack.php'] ) ||
			isset( $all_plugins['seo-by-rank-math/rank-math.php'] ) ||
			isset( $all_plugins['wordpress-seo/wp-seo.php'] ) ||
			isset( $all_plugins['wordpress-seo-premium/wp-seo-premium.php'] ) ||
			isset( $all_plugins['autodescription/autodescription.php'] )
		) {
			$key = array_search( 'allinoneseo', $options, true );
			if ( false !== $key ) {
				unset( $options[ $key ] );
			}
		}
	}

	if ( in_array( 'rafflepress', $options, true ) ) {
		if (
			isset( $all_plugins['rafflepress/rafflepress.php'] ) ||
			isset( $all_plugins['rafflepress-pro/rafflepress-pro.php'] )
		) {
			$key = array_search( 'rafflepress', $options, true );
			if ( false !== $key ) {
				unset( $options[ $key ] );
			}
		}
	}

	if ( in_array( 'wpforms', $options, true ) ) {
		if (
			isset( $all_plugins['wpforms-lite/wpforms.php'] ) ||
			isset( $all_plugins['wpforms/wpforms.php'] )
		) {
			$key = array_search( 'wpforms', $options, true );
			if ( false !== $key ) {
				unset( $options[ $key ] );
			}
		}
	}

	if ( in_array( 'monsterinsights', $options, true ) ) {
		if (
			isset( $all_plugins['google-analytics-for-wordpress/googleanalytics.php'] ) ||
			isset( $all_plugins['google-analytics-premium/googleanalytics-premium.php'] )
		) {
			$key = array_search( 'monsterinsights', $options, true );
			if ( false !== $key ) {
				unset( $options[ $key ] );
			}
		}
	}

	// Install plugins.
	if ( ! empty( $options ) ) {
		foreach ( $options as $p ) {
			if ( ! empty( $paths_map[ $p ] ) ) {
				$plugin       = $paths_map[ $p ]['slug'];
				$download_url = $paths_map[ $p ]['url'];

				global $hook_suffix;

				// Set the current screen to avoid undefined notices.
				set_current_screen();

				// Prepare variables.
				$method = '';
				$url    = add_query_arg(
					array(
						'page' => 'seedprod_lite',
					),
					admin_url( 'admin.php' )
				);
				$url    = esc_url( $url );

				// Start output buffering to catch the filesystem form if credentials are needed.
				ob_start();
				$creds = request_filesystem_credentials( $url, $method, false, false, null );
				if ( false === $creds ) {
					wp_send_json_error();
				}

				// If we are not authenticated, make it happen now.
				if ( ! WP_Filesystem( $creds ) ) {
					request_filesystem_credentials( $url, $method, true, false, null );
					$form = ob_get_clean();
					return;
				}

				// We do not need any extra credentials if we have gotten this far, so let's install the plugin.
				require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

				// Check for skin files.
				global $wp_version;
				if ( version_compare( $wp_version, '5.3.0' ) >= 0 ) {
					$skin_file = plugin_dir_path( __DIR__ ) . 'includes/skin53.php';
				} else {
					$skin_file = plugin_dir_path( __DIR__ ) . 'includes/skin.php';
				}

				if ( file_exists( $skin_file ) ) {
					require_once $skin_file;
				}

				// Create the plugin upgrader with our custom skin.
				ob_start();
				$installer = new Plugin_Upgrader( new SeedProd_Skin() );
				$installer->install( $download_url );
				$output = ob_get_clean();

				// Flush the cache and return the newly installed plugin basename.
				wp_cache_flush();
				if ( $installer->plugin_info() ) {
					$plugin_basename   = $installer->plugin_info();
					$install_plugins[] = $plugin_basename;
				}
			}
		}
	}

	// Activate plugins.
	foreach ( $install_plugins as $ip ) {
		activate_plugin( $ip, '', false, true );
	}

	wp_send_json_success( $install_plugins );
}


/** Function seedprod_lite_import_cross_site_paste() called by wp_ajax hooks: {'seedprod_lite_import_cross_site_paste'} **/
/** Parameters found in function seedprod_lite_import_cross_site_paste(): {"request": ["settings"]} **/
function seedprod_lite_import_cross_site_paste() {

	if ( check_ajax_referer( 'seedprod_lite_import_cross_site_paste' ) ) {

		if ( ! current_user_can( apply_filters( 'seedprod_import_export', 'edit_others_posts' ) ) ) {
			wp_send_json_error();
		}

		$url = wp_nonce_url( 'admin.php?page=seedprod_lite_import_cross_site_paste', 'seedprod_import_cross_site_paste' );

		$data = isset( $_REQUEST['settings'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['settings'] ) ) : '';

		$cross_site_data = seedprod_lite_landing_import_cross_site_json( $data );
		echo wp_kses( $cross_site_data, 'post' );

		exit;
	}
}


