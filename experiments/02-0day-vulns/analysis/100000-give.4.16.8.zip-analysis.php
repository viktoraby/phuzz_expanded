<?php
/***
*
*Found actions: 68
*Found functions:54
*Extracted functions:51
*Total parameter names extracted: 36
*Overview: {'give_ajax_tags_search': {'give_tags_search'}, 'give_subscription_import_callback': {'give_subscription_import'}, 'give_core_settings_import_callback': {'give_core_settings_import'}, 'give_load_checkout_fields': {'nopriv_give_checkout_register', 'give_cancel_login', 'nopriv_give_cancel_login'}, 'give_ajax_form_search': {'nopriv_give_form_search', 'give_form_search'}, 'give_donation_form_nonce': {'give_donation_form_nonce', 'nopriv_give_donation_form_nonce'}, 'give_ajax_store_payment_note': {'give_insert_payment_note'}, 'give_ajax_categories_search': {'give_categories_search'}, 'give_deactivation_popup': {'give_deactivation_popup'}, 'givewp-additional-payment-gateways-notice-dismissed': {'givewp_additional_payment_gateways_hide_notice'}, 'give_get_form_template_id': {'no_priv_give_get_form_template_id', 'give_get_form_template_id'}, 'give_load_wp_editor': {'give_load_wp_editor'}, 'give_test_ajax_works': {'nopriv_give_test_ajax'}, 'block_donation_form_search_results': {'give_block_donation_form_search_results'}, 'give_db_updates_info': {'give_db_updates_info'}, 'ajax_handler': {'nopriv_give_get_donor_comments', 'give_get_donor_comments'}, 'give_confirm_email_for_donation_access': {'nopriv_give_confirm_email_for_donations_access'}, 'give_cache_flush': {'give_cache_flush'}, 'give_sendwp_disconnect': {'give_sendwp_disconnect'}, 'give_get_content_by_ajax_handler': {'give_get_content_by_ajax'}, 'give_upload_addon_handler': {'give_upload_addon'}, 'give_refresh_all_licenses_handler': {'give_refresh_all_licenses'}, 'give_check_for_form_price_variations_html': {'give_check_for_form_price_variations_html'}, 'give_ajax_get_states_field': {'give_get_states', 'nopriv_give_get_states'}, 'give_deactivation_form_submit': {'deactivation_form_submit'}, 'give_load_ajax_gateway': {'give_load_gateway', 'nopriv_give_load_gateway'}, 'give_donation_form_reset_all_nonce': {'give_donation_form_reset_all_nonce', 'nopriv_give_donation_form_reset_all_nonce'}, 'give_hide_outdated_php_notice': {'give_hide_outdated_php_notice'}, 'give_process_donation_form': {'nopriv_give_process_donation', 'give_process_donation'}, 'give_process_form_login': {'nopriv_give_process_donation_login', 'give_process_donation_login'}, 'give_ajax_search_users': {'give_user_search'}, 'give_ajax_donor_manage_addresses': {'donor_manage_addresses'}, 'give_do_ajax_export': {'give_do_ajax_export'}, 'give_trigger_upgrades': {'give_trigger_upgrades'}, 'give_set_notification_status_handler': {'give_set_notification_status'}, 'give_get_receipt': {'nopriv_get_receipt', 'get_receipt'}, 'give_sendwp_remote_install_handler': {'give_sendwp_remote_install'}, 'give_start_updating': {'give_run_db_updates'}, 'givewp-goal-notice-dismissed': {'givewp_goal_hide_notice'}, 'formId': {'givewp_transfer_hide_notice', 'givewp_migration_hide_notice'}, 'give_activate_addon_handler': {'give_activate_addon'}, 'givewp-{$metaKey}': {'{$action}'}, 'give_donation_import_callback': {'give_donation_import'}, 'give_export_donations_get_custom_fields': {'give_export_donations_get_custom_fields'}, 'mode': {'givewp_tour_completed'}, 'handleReceiptAjax': {'nopriv_get_receipt', 'get_receipt'}, 'give_ajax_pages_search': {'give_pages_search'}, 'shortcode_ajax': {'give_shortcode'}, 'give_check_for_form_price_variations': {'give_check_for_form_price_variations'}, 'give_get_license_info_handler': {'give_get_license_info'}, 'give_deactivate_license_handler': {'give_deactivate_license'}, 'give_ajax_donor_search': {'give_donor_search'}, 'give_load_checkout_login_fields': {'nopriv_give_checkout_login'}, 'give_ajax_delete_payment_note': {'give_delete_payment_note'}}
*
***/

/** Function give_ajax_tags_search() called by wp_ajax hooks: {'give_tags_search'} **/
/** Parameters found in function give_ajax_tags_search(): {"post": ["s"]} **/
function give_ajax_tags_search() {
	$results = [];

	/**
	 * Filter to modify Ajax tags search args
	 *
	 * @since 2.1
	 *
	 * @param array $args argument for get_terms
	 *
	 * @return array $args argument for get_terms
	 */
	$args = (array) apply_filters(
		'give_forms_tags_dropdown_args',
		[
			'number'     => 30,
			'name__like' => esc_sql( sanitize_text_field( $_POST['s'] ) ),
		]
	);

	$categories = get_terms( 'give_forms_tag', $args );

	foreach ( $categories as $category ) {
		$results[] = [
			'id'   => $category->term_id,
			'name' => $category->name,
		];
	}

	/**
	 * Filter to modify Ajax tags search result
	 *
	 * @since 2.1
	 *
	 * @param array $results Contain the tags id and name
	 *
	 * @return array $results Contain the tags id and name
	 */
	$results = (array) apply_filters( 'give_forms_tags_dropdown_responce', $results );

	wp_send_json( $results );
}


/** Function give_subscription_import_callback() called by wp_ajax hooks: {'give_subscription_import'} **/
/** Parameters found in function give_subscription_import_callback(): {"post": ["fields"], "request": ["current", "total_ajax", "start", "end", "next", "total", "per_page"]} **/
function give_subscription_import_callback() {

    check_ajax_referer('give_subscription_import');

    if ( ! current_user_can( 'manage_give_settings' ) ) {
        give_die();
    }

    // Disable Give cache
    Give_Cache::get_instance()->disable();

    $import_setting = [];
    $fields         = isset( $_POST['fields'] ) ? $_POST['fields'] : null;

    parse_str( $fields, $output );

    $import_setting['mode']        = $output['mode'];
    $import_setting['create_user'] = isset($output['create_user']) ? $output['create_user'] : '0';
    $import_setting['delimiter']   = $output['delimiter'];
    $import_setting['csv']         = $output['csv'];
    $import_setting['delete_csv']  = $output['delete_csv'];
    $import_setting['dry_run']     = $output['dry_run'];

    $main_key = give_maybe_safe_unserialize($output['main_key']);

    $current    = absint( $_REQUEST['current'] );
    $total_ajax = absint( $_REQUEST['total_ajax'] );
    $start      = absint( $_REQUEST['start'] );
    $end        = absint( $_REQUEST['end'] );
    $next       = absint( $_REQUEST['next'] );
    $total      = absint( $_REQUEST['total'] );
    $per_page   = absint( $_REQUEST['per_page'] );
    $delimiter  = empty( $output['delimiter'] ) ? ',' : $output['delimiter'];

    // Ensure importer class is loaded for admin-ajax context
    if ( ! class_exists( 'Give_Import_Subscriptions' ) ) {
        require_once GIVE_PLUGIN_DIR . 'includes/admin/tools/import/class-give-import-subscriptions.php';
    }

    $importer = \Give_Import_Subscriptions::get_instance();

    // Processing
    $raw_data                  = $importer->get_subscription_data_from_csv( $output['csv'], $start, $end, $delimiter );
    $raw_key = give_maybe_safe_unserialize($output['mapto']);
    $import_setting['raw_key'] = $raw_key;

    $current_key = $start;
    foreach ( $raw_data as $row_data ) {
        $import_setting['row_key'] = $current_key;
        $result = $importer->import_row( $raw_key, $row_data, $main_key, $import_setting );
        if ( is_string( $result ) && ! empty( $result ) ) {
            if ( empty( $json_data['errors'] ) ) {
                $json_data['errors'] = [];
            }
            $json_data['errors'][] = sprintf( __( 'Row %1$d: %2$s', 'give' ), $current_key, $result );
        }
        $current_key ++;
    }

    if ( $next == false ) {
        $json_data = [
            'success' => true,
            'message' => __( 'All subscriptions uploaded successfully!', 'give' ),
        ];
    } else {
        $index_start = $start;
        $index_end   = $end;
        $last        = false;
        $next        = true;
        if ( $next ) {
            $index_start = $index_start + $per_page;
            $index_end   = $per_page + ( $index_start - 1 );
        }
        if ( $index_end >= $total ) {
            $index_end = $total;
            $last      = true;
        }
        $json_data = [
            'raw_data' => $raw_data,
            'raw_key'  => $raw_key,
            'next'     => $next,
            'start'    => $index_start,
            'end'      => $index_end,
            'last'     => $last,
        ];
    }

    $url              = give_import_page_url(
        [
            'step'          => '4',
            'importer-type' => 'import_subscriptions',
            'csv'           => $output['csv'],
            'total'         => $total,
            'delete_csv'    => $import_setting['delete_csv'],
            'success'       => ( isset( $json_data['success'] ) ? $json_data['success'] : '' ),
            'dry_run'       => $output['dry_run'],
            '_wpnonce'      => wp_create_nonce( 'give_subscription_import_success' ),
        ]
    );
    $json_data['url'] = $url;

    $current ++;
    $json_data['current'] = $current;

    $percentage              = ( 100 / ( $total_ajax + 1 ) ) * $current;
    $json_data['percentage'] = $percentage;

    // Enable Give cache
    Give_Cache::get_instance()->enable();

    $json_data = apply_filters( 'give_import_ajax_responces', $json_data, $fields );
    wp_die( json_encode( $json_data ) );
}


/** Function give_core_settings_import_callback() called by wp_ajax hooks: {'give_core_settings_import'} **/
/** Parameters found in function give_core_settings_import_callback(): {"post": ["fields"]} **/
function give_core_settings_import_callback() {
	check_ajax_referer( 'give_core_settings_import' );

	// Bailout.
	if ( ! current_user_can( 'manage_give_settings' ) ) {
		give_die();
	}

	$fields = isset( $_POST['fields'] ) ? $_POST['fields'] : null;
	parse_str( $fields, $fields );

	$json_data['success'] = false;

	/**
	 * Filter to Modify fields that are being pass by the ajax before importing of the core setting start.
	 *
	 * @access public
	 *
	 * @since  1.8.17
	 *
	 * @param array $fields
	 *
	 * @return array $fields
	 */
	$fields = (array) apply_filters( 'give_import_core_settings_fields', $fields );

	$file_name = ( ! empty( $fields['file_name'] ) ? give_clean( $fields['file_name'] ) : false );

	if ( ! empty( $file_name ) ) {
		$type = ( ! empty( $fields['type'] ) ? give_clean( $fields['type'] ) : 'merge' );

		// Get the json data from the file and then alter it in array format
		$json_string   = give_get_core_settings_json( $file_name );
		$json_to_array = json_decode( $json_string, true );

		// get the current setting from the options table.
		$host_give_options = Give_Cache_Setting::get_settings();

		// Save old settins for backup.
		update_option( 'give_settings_old', $host_give_options, false );

		/**
		 * Filter to Modify Core Settings that are being going to get import in options table as give settings.
		 *
		 * @access public
		 *
		 * @since  1.8.17
		 *
		 * @param array $type Type of Import
		 * @param array $host_give_options Setting old setting that used to be in the options table.
		 * @param array $fields Data that is being send from the ajax
		 *
		 * @param array $json_to_array Setting that are being going to get imported
		 *
		 * @return array $json_to_array Setting that are being going to get imported
		 */
		$json_to_array = (array) apply_filters( 'give_import_core_settings_data', $json_to_array, $type, $host_give_options, $fields );

		update_option( 'give_settings', $json_to_array, false );

		$json_data['success'] = true;
	}

	$json_data['percentage'] = 100;

	/**
	 * Filter to Modify core import setting page url
	 *
	 * @access public
	 *
	 * @since  1.8.17
	 * @return array $url
	 */
	$json_data['url'] = give_import_page_url(
		(array) apply_filters(
			'give_import_core_settings_success_url',
			[
				'step'          => ( empty( $json_data['success'] ) ? '1' : '3' ),
				'importer-type' => 'import_core_setting',
				'success'       => ( empty( $json_data['success'] ) ? '0' : '1' ),
			]
		)
	);

	wp_send_json( $json_data );
}


/** Function give_load_checkout_fields() called by wp_ajax hooks: {'nopriv_give_checkout_register', 'give_cancel_login', 'nopriv_give_cancel_login'} **/
/** Parameters found in function give_load_checkout_fields(): {"post": ["form_id"]} **/
function give_load_checkout_fields() {
	$form_id = isset( $_POST['form_id'] ) ? $_POST['form_id'] : '';

	ob_start();

	/**
	 * Fire to render registration/login form.
	 *
	 * @since 1.7
	 */
	do_action( 'give_donation_form_register_login_fields', $form_id );

	$fields = ob_get_clean();

	wp_send_json(
		[
			'fields' => wp_json_encode( $fields ),
			'submit' => wp_json_encode( give_get_donation_form_submit_button( $form_id ) ),
		]
	);
}


/** Function give_ajax_form_search() called by wp_ajax hooks: {'nopriv_give_form_search', 'give_form_search'} **/
/** Parameters found in function give_ajax_form_search(): {"post": ["s"]} **/
function give_ajax_form_search() {
	$results = [];
	$search  = esc_sql( sanitize_text_field( $_POST['s'] ) );

	$args = [
		'post_type'              => 'give_forms',
		's'                      => $search,
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false,
		'cache_results'          => false,
		'no_found_rows'          => true,
		'post_status'            => 'publish',
		'orderby'                => 'title',
		'order'                  => 'ASC',
		'posts_per_page'         => empty( $search ) ? 30 : -1,
	];

	/**
	 * Filter to modify Ajax form search args
	 *
	 * @since 2.1
	 *
	 * @param array $args Query argument for WP_query
	 *
	 * @return array $args Query argument for WP_query
	 */
	$args = (array) apply_filters( 'give_ajax_form_search_args', $args );

	// get all the donation form.
	$query = new WP_Query( $args );
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			global $post;

			$results[] = [
				'id'   => $post->ID,
				'name' => $post->post_title,
			];
		}
		wp_reset_postdata();
	}

	/**
	 * Filter to modify Ajax form search result
	 *
	 * @since 2.1
	 *
	 * @param array $results Contain the Donation Form id
	 *
	 * @return array $results Contain the Donation Form id
	 */
	$results = (array) apply_filters( 'give_ajax_form_search_response', $results );

	wp_send_json( $results );
}


/** Function give_donation_form_nonce() called by wp_ajax hooks: {'give_donation_form_nonce', 'nopriv_give_donation_form_nonce'} **/
/** Parameters found in function give_donation_form_nonce(): {"post": ["give_form_id"]} **/
function give_donation_form_nonce() {
	if ( isset( $_POST['give_form_id'] ) ) {

		// Get donation form id.
		$form_id = is_numeric( $_POST['give_form_id'] ) ? absint( $_POST['give_form_id'] ) : 0;

		if ( ! ShortcodeUtils::isValidForm( $form_id ) ) {
			wp_send_json_error( [ 'error' => 'give_invalid_donation_form' ], 400 );
		}

		// Visual Form Builder (v3) forms use route signatures instead of the legacy nonce endpoint.
		if ( FormUtils::isV3Form( $form_id ) ) {
			wp_send_json_error( [ 'error' => 'give_unsupported_form_version' ], 400 );
		}

		// Send nonce json data.
		wp_send_json_success( wp_create_nonce( "give_donation_form_nonce_{$form_id}" ) );
	}
}


/** Function give_ajax_store_payment_note() called by wp_ajax hooks: {'give_insert_payment_note'} **/
/** Parameters found in function give_ajax_store_payment_note(): {"post": ["payment_id", "note", "type"]} **/
function give_ajax_store_payment_note() {
    check_ajax_referer('give_insert_payment_note');

    $payment_id = absint($_POST['payment_id']);
    $note = wp_kses($_POST['note'], []);
    $note_type = give_clean($_POST['type']);

    if ( ! current_user_can('edit_give_payments', $payment_id)) {
        wp_die(__('You do not have permission to edit payments.', 'give'), __('Error', 'give'), ['response' => 403]);
    }

	if ( empty( $payment_id ) || empty( $note ) ) {
		die( '-1' );
	}

	if ( ! give_has_upgrade_completed( 'v230_move_donor_note' ) ) {
		// Backward compatibility.
		$note_id = give_insert_payment_note( $payment_id, $note );
	} else {
		$note_id = Give()->comment->db->add(
			array(
				'comment_parent'  => $payment_id,
				'user_id'         => get_current_user_id(),
				'comment_content' => $note,
				'comment_type'    => 'donation',
			)
		);
	}

	if ( $note_id && $note_type ) {

		if ( ! give_has_upgrade_completed( 'v230_move_donor_note' ) ) {
			add_comment_meta( $note_id, 'note_type', $note_type, true );
		} else {
			Give()->comment->db_meta->update_meta( $note_id, 'note_type', $note_type );
		}

		/**
		 * Fire the action
		 *
		 * @since 2.3.0
		 */
		do_action( 'give_donor-note_email_notification', $note_id, $payment_id );
	}

	die( give_get_payment_note_html( $note_id ) );
}


/** Function give_ajax_categories_search() called by wp_ajax hooks: {'give_categories_search'} **/
/** Parameters found in function give_ajax_categories_search(): {"post": ["s"]} **/
function give_ajax_categories_search() {
	$results = [];

	/**
	 * Filter to modify Ajax tags search args
	 *
	 * @since 2.1
	 *
	 * @param array $args argument for get_terms
	 *
	 * @return array $args argument for get_terms
	 */
	$args = (array) apply_filters(
		'give_forms_categories_dropdown_args',
		[
			'number'     => 30,
			'name__like' => esc_sql( sanitize_text_field( $_POST['s'] ) ),
		]
	);

	$categories = get_terms( 'give_forms_category', $args );

	foreach ( $categories as $category ) {
		$results[] = [
			'id'   => $category->term_id,
			'name' => $category->name,
		];
	}

	/**
	 * Filter to modify Ajax tags search result
	 *
	 * @since 2.1
	 *
	 * @param array $results Contain the categories id and name
	 *
	 * @return array $results Contain the categories id and name
	 */
	$results = (array) apply_filters( 'give_forms_categories_dropdown_responce', $results );

	wp_send_json( $results );
}


/** Function give_deactivation_popup() called by wp_ajax hooks: {'give_deactivation_popup'} **/
/** No params detected :-/ **/


/** Function givewp-additional-payment-gateways-notice-dismissed() called by wp_ajax hooks: {'givewp_additional_payment_gateways_hide_notice'} **/
/** No function found :-/ **/


/** Function give_get_form_template_id() called by wp_ajax hooks: {'no_priv_give_get_form_template_id', 'give_get_form_template_id'} **/
/** Parameters found in function give_get_form_template_id(): {"post": ["formId"]} **/
function give_get_form_template_id() {
	check_ajax_referer( 'give-donation-form-widget', 'security' );

	$formId = isset( $_POST['formId'] ) ? absint( $_POST['formId'] ) : 0;

	// Send error response if form id does not mentioned.
	if ( ! $formId ) {
		wp_send_json_error();
	}

	$templateID = FormTemplateUtils::getActiveID( $formId );
	$templateID = $templateID ?: 'legacy';

	wp_send_json_success( $templateID );
}


/** Function give_load_wp_editor() called by wp_ajax hooks: {'give_load_wp_editor'} **/
/** Parameters found in function give_load_wp_editor(): {"post": ["wp_editor", "textarea_name", "wp_editor_id"]} **/
function give_load_wp_editor() {
	if ( ! isset( $_POST['wp_editor'] ) || ! current_user_can( 'edit_give_forms' ) ) {
		die();
	}

	$wp_editor                     = json_decode( base64_decode( $_POST['wp_editor'] ), true );
	$wp_editor[2]['textarea_name'] = give_clean( $_POST['textarea_name'] );

	wp_editor( wp_kses_post( $wp_editor[0] ), give_clean( $_POST['wp_editor_id'] ), $wp_editor[2] );

	die();
}


/** Function give_test_ajax_works() called by wp_ajax hooks: {'nopriv_give_test_ajax'} **/
/** No params detected :-/ **/


/** Function block_donation_form_search_results() called by wp_ajax hooks: {'give_block_donation_form_search_results'} **/
/** No params detected :-/ **/


/** Function give_db_updates_info() called by wp_ajax hooks: {'give_db_updates_info'} **/
/** No params detected :-/ **/


/** Function ajax_handler() called by wp_ajax hooks: {'nopriv_give_get_donor_comments', 'give_get_donor_comments'} **/
/** No params detected :-/ **/


/** Function give_confirm_email_for_donation_access() called by wp_ajax hooks: {'nopriv_give_confirm_email_for_donations_access'} **/
/** Parameters found in function give_confirm_email_for_donation_access(): {"post": ["email"]} **/
function give_confirm_email_for_donation_access() {

	// Verify Security using Nonce.
	if ( ! check_ajax_referer( 'give_ajax_nonce', 'nonce' ) ) {
		return false;
	}

	// Bail Out, if email is empty.
	if ( empty( $_POST['email'] ) ) {
		return false;
	}

	$donor = Give()->donors->get_donor_by( 'email', give_clean( $_POST['email'] ) );
	if ( is_object( $donor ) && Give()->email_access->can_send_email( $donor->id ) ) {
		Give()->email_access->send_email( $donor->id, $donor->email );
	}

	$return            = [];
	$return['status']  = 'success';

	/**
	 * Filter to modify access mail send notice
	 *
	 * @since 2.1.3
	 *
	 * @param string Send notice message for email access.
	 *
	 * @return  string $message Send notice message for email access.
	 */
	$message = (string) apply_filters( 'give_email_access_mail_send_notice', __( 'Please check your email and click on the link to access your complete donation history.', 'give' ) );

	$return['message'] = Give_Notices::print_frontend_notice(
		$message,
		false,
		'success'
	);

	echo json_encode( $return );
	give_die();
}


/** Function give_cache_flush() called by wp_ajax hooks: {'give_cache_flush'} **/
/** No params detected :-/ **/


/** Function give_sendwp_disconnect() called by wp_ajax hooks: {'give_sendwp_disconnect'} **/
/** No params detected :-/ **/


/** Function give_get_content_by_ajax_handler() called by wp_ajax hooks: {'give_get_content_by_ajax'} **/
/** Parameters found in function give_get_content_by_ajax_handler(): {"get": ["url", "show_changelog"]} **/
function give_get_content_by_ajax_handler() {
	check_admin_referer( 'give_get_content_by_ajax' );

	if ( empty( $_GET['url'] ) ) {
		die();
	}

    /**
     * Restrict requests to GiveWP.com plugin readme.txt file only.
     * @link https://owasp.org/www-community/attacks/Server_Side_Request_Forgery
     *
     * @since 2.25.2
     */
    if(! preg_match('^https://givewp.com/downloads/plugins/(.*)/readme.txt$^', $_GET['url'])) {
        die();
    }

	// Handle changelog render request.
	if (
		! empty( $_GET['show_changelog'] )
		&& (int) give_clean( $_GET['show_changelog'] )
	) {
		$msg = __( 'Sorry, unable to load changelog.', 'give' );
		$url = urldecode_deep( give_clean( $_GET['url'] ) );

		$response = wp_remote_get( $url );

		if ( is_wp_error( $response ) ) {
			echo "$msg<br><br><code>Error: {$response->get_error_message()}</code>";
			exit;
		}

		$response = wp_remote_retrieve_body( $response );

		if ( false === strpos( $response, '== Changelog ==' ) ) {
			echo $msg;
			exit;
		}

		$changelog = explode( '== Changelog ==', $response );
		$changelog = end( $changelog );

		echo give_get_format_md( $changelog );
	}

	do_action( 'give_get_content_by_ajax_handler' );

	exit;
}


/** Function give_upload_addon_handler() called by wp_ajax hooks: {'give_upload_addon'} **/
/** Parameters found in function give_upload_addon_handler(): {"files": ["file"]} **/
function give_upload_addon_handler() {
	if ( ! isset( $_FILES['file']['name'] ) ) {
		wp_send_json_error( [ 'errorMsg' => __( 'No file was uploaded.', 'give' ) ] );
	}

	if ( UPLOAD_ERR_OK !== $_FILES['file']['error'] ) {
		wp_send_json_error( [ 'errorMsg' => __( 'The file upload failed. Please try again.', 'give' ) ] );
	}

	check_admin_referer( 'give-upload-addon' );

	if ( ! current_user_can( 'upload_plugins' ) ) {
		wp_send_json_error( [ 'errorMsg' => __( 'The current user does not have permission to upload plugins on this site.', 'give' ) ] );
	}

	$access_type = get_filesystem_method();

	if ( 'direct' !== $access_type ) {
		wp_send_json_error(
			[
				'errorMsg' => sprintf(
					__( 'In order to upload add-ons here, GiveWP needs direct access to the file system. Please <a href="%1$s" target="_blank">visit the main plugin page</a> to manually upload the add-on.', 'give' ),
					esc_url( admin_url( 'plugin-install.php?tab=upload' ) )
				),
			]
		);
	}

	$file_type = wp_check_filetype( $_FILES['file']['name'], [ 'zip' => 'application/zip' ] );

	if ( empty( $file_type['ext'] ) ) {
		wp_send_json_error( [ 'errorMsg' => __( 'Uploaded add-ons must be (zipped) ZIP files. Upload a valid add-on ZIP.', 'give' ) ] );
	}

	// Snapshot existing Give add-ons for diff after installation.
	$pre_addons_list = give_get_plugins( [ 'only_add_on' => true ] );

	// Detect the plugin folder name from the ZIP to check for an existing installation.
	$zip_folder = give_get_zip_plugin_folder( $_FILES['file']['tmp_name'] );

	if ( ! empty( $zip_folder ) && ! empty( $pre_addons_list ) ) {
		foreach ( $pre_addons_list as $addon_path => $addon_data ) {
			if ( strpos( $addon_path, $zip_folder . '/' ) === 0 ) {
				wp_send_json_error(
					[
						'errorMsg'   => __( 'This add-on is already installed.', 'give' ),
						'pluginInfo' => $addon_data,
					]
				);
			}
		}
	}

	// Install the plugin using the WordPress upgrader.
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader-skin.php';
	require_once ABSPATH . 'wp-admin/includes/class-automatic-upgrader-skin.php';
	require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';
	require_once ABSPATH . 'wp-admin/includes/plugin.php';

	$skin     = new Automatic_Upgrader_Skin();
	$upgrader = new Plugin_Upgrader( $skin );

	$buffer_level = ob_get_level();

	$result = $upgrader->install( $_FILES['file']['tmp_name'] );

	while ( ob_get_level() > $buffer_level ) {
		// A non-removable buffer, such as zlib, would loop until the request times out.
		if ( ! @ob_end_clean() ) {
			break;
		}
	}

	if ( is_wp_error( $result ) ) {
		wp_send_json_error( [ 'errorMsg' => $result->get_error_message() ] );
	}

	if ( ! $result ) {
		$error_message = is_wp_error( $skin->result )
			? $skin->result->get_error_message()
			: __( 'The add-on could not be installed. Please try again or upload it manually.', 'give' );

		wp_send_json_error( [ 'errorMsg' => $error_message ] );
	}

	// Refresh the plugin cache and find the newly installed or updated plugin.
	wp_clean_plugins_cache( true );

	$post_addons_list = give_get_plugins( [ 'only_add_on' => true ] );
	$new_plugins      = array_diff_key( $post_addons_list, $pre_addons_list );

	$installed_addon = [];

	if ( ! empty( $new_plugins ) ) {
		$new_plugin_path         = array_key_first( $new_plugins );
		$installed_addon         = $new_plugins[ $new_plugin_path ];
		$installed_addon['path'] = $new_plugin_path;
	}

	if ( empty( $installed_addon ) ) {
		wp_send_json_error(
			[
				'errorMsg' => sprintf(
					/* translators: %1$s: URL to the plugins page */
					__( 'The add-on was uploaded but GiveWP could not detect it. Please <a href="%1$s">visit the plugins page</a> to activate it manually.', 'give' ),
					esc_url( admin_url( 'plugins.php' ) )
				),
			]
		);
	}

	wp_send_json_success(
		[
			'pluginPath'         => $installed_addon['path'],
			'pluginName'         => $installed_addon['Name'],
			'nonce'              => wp_create_nonce( "give_activate-{$installed_addon['path']}" ),
			'licenseSectionHtml' => Give_License::render_licenses_list(),
		]
	);
}


/** Function give_refresh_all_licenses_handler() called by wp_ajax hooks: {'give_refresh_all_licenses'} **/
/** No params detected :-/ **/


/** Function give_check_for_form_price_variations_html() called by wp_ajax hooks: {'give_check_for_form_price_variations_html'} **/
/** Parameters found in function give_check_for_form_price_variations_html(): {"post": ["form_id", "payment_id"]} **/
function give_check_for_form_price_variations_html() {
	if ( ! current_user_can( 'edit_give_payments', get_current_user_id() ) ) {
		wp_die();
	}

	$form_id    = ! empty( $_POST['form_id'] ) ? absint( $_POST['form_id'] ) : false;
	$payment_id = ! empty( $_POST['payment_id'] ) ? absint( $_POST['payment_id'] ) : false;
	if ( empty( $form_id ) || empty( $payment_id ) ) {
		wp_die();
	}

	$form = get_post( $form_id );
	if ( ! empty( $form->post_type ) && 'give_forms' !== $form->post_type ) {
		wp_die();
	}

	if ( ! give_has_variable_prices( $form_id ) || ! $form_id ) {
		esc_html_e( 'n/a', 'give' );
	} else {
		$prices_atts = [];
		if ( $variable_prices = give_get_variable_prices( $form_id ) ) {
			foreach ( $variable_prices as $variable_price ) {
				$prices_atts[ $variable_price['_give_id']['level_id'] ] = give_format_amount( $variable_price['_give_amount'], [ 'sanitize' => false ] );
			}
		}

		// Variable price dropdown options.
		$variable_price_dropdown_option = [
			'id'               => $form_id,
			'name'             => 'give-variable-price',
			'chosen'           => true,
			'show_option_all'  => '',
			'show_option_none' => '',
			'select_atts'      => 'data-prices=' . esc_attr( json_encode( $prices_atts ) ),
		];

		if ( $payment_id ) {
			// Payment object.
			$payment = new Give_Payment( $payment_id );

			// Payment meta.
			$payment_meta                               = $payment->get_meta();
			$variable_price_dropdown_option['selected'] = $payment_meta['price_id'];
		}

		// Render variable prices select tag html.
		give_get_form_variable_price_dropdown( $variable_price_dropdown_option, true );
	}

	give_die();
}


/** Function give_ajax_get_states_field() called by wp_ajax hooks: {'give_get_states', 'nopriv_give_get_states'} **/
/** Parameters found in function give_ajax_get_states_field(): {"post": ["country", "field_name"]} **/
function give_ajax_get_states_field() {
    $states_found = false;
    $show_field = true;
    $states_require = true;
    // Get the Country code from the $_POST.
    $country = sanitize_text_field($_POST['country']);

    // Get the field name from the $_POST.
    $field_name = sanitize_text_field($_POST['field_name']);

    $label = __('State', 'give');
    $states_label = give_get_states_label();

    $default_state = '';
    if (give_get_country() === $country) {
        $default_state = give_get_state();
    }

    // Check if $country code exists in the array key for states label.
    if (array_key_exists($country, $states_label)) {
        $label = $states_label[$country];
    }

    if (empty($country)) {
        $country = give_get_country();
    }

    $states = give_get_states($country);
    if (!empty($states)) {
        $args = [
            'name' => $field_name,
            'id' => $field_name,
            'class' => $field_name . '  give-select',
            'options' => $states,
            'show_option_all' => false,
            'show_option_none' => false,
            'placeholder' => $label,
            'selected' => $default_state,
            'autocomplete' => 'address-level1',
        ];
        $data = Give()->html->select($args);
        $states_found = true;
    } else {
        $data = 'nostates';

        // Get the country list that does not have any states init.
        $no_states_country = give_no_states_country_list();

        // Check if $country code exists in the array key.
        if (array_key_exists($country, $no_states_country)) {
            $show_field = false;
        }

        // Get the country list that does not require states.
        $states_not_required_country_list = give_states_not_required_country_list();

        // Check if $country code exists in the array key.
        if (array_key_exists($country, $states_not_required_country_list)) {
            $states_require = false;
        }
    }

    $response = [
        'success' => true,
        'states_found' => $states_found,
        'states_label' => $label,
        'show_field' => $show_field,
        'states_require' => $states_require,
        'data' => $data,
        'default_state' => $default_state,
        'city_require' => !array_key_exists($country, give_city_not_required_country_list()),
        'zip_require' => !array_key_exists($country, give_get_country_list_without_postcodes()),
        'state_label' => $label,
        'states' => array_map(static function ($state) {
            return html_entity_decode($state, ENT_QUOTES);
        }, $states),
    ];
    wp_send_json($response);
}


/** Function give_deactivation_form_submit() called by wp_ajax hooks: {'deactivation_form_submit'} **/
/** No params detected :-/ **/


/** Function give_load_ajax_gateway() called by wp_ajax hooks: {'give_load_gateway', 'nopriv_give_load_gateway'} **/
/** No params detected :-/ **/


/** Function give_donation_form_reset_all_nonce() called by wp_ajax hooks: {'give_donation_form_reset_all_nonce', 'nopriv_give_donation_form_reset_all_nonce'} **/
/** Parameters found in function give_donation_form_reset_all_nonce(): {"post": ["give_form_id"]} **/
function give_donation_form_reset_all_nonce() {
	if ( isset( $_POST['give_form_id'] ) ) {

		// Get donation form id.
		$form_id = is_numeric( $_POST['give_form_id'] ) ? absint( $_POST['give_form_id'] ) : 0;

		if ( ! ShortcodeUtils::isValidForm( $form_id ) ) {
			wp_send_json_error( [ 'error' => 'give_invalid_donation_form' ], 400 );
		}

		$data = array(
			'give_form_hash'               => wp_create_nonce( "give_donation_form_nonce_{$form_id}" ),
			'give_form_user_register_hash' => wp_create_nonce( "give_form_create_user_nonce_{$form_id}" ),
		);

		/**
		 * Filter the ajax request data
		 *
		 * @since  2.2.0
		 */
		$data = apply_filters( 'give_donation_form_reset_all_nonce_data', $data );

		// Send nonce json data.
		wp_send_json_success( $data );
	}

	wp_send_json_error();
}


/** Function give_hide_outdated_php_notice() called by wp_ajax hooks: {'give_hide_outdated_php_notice'} **/
/** Parameters found in function give_hide_outdated_php_notice(): {"post": ["_give_hide_outdated_php_notices_shortly"]} **/
function give_hide_outdated_php_notice() {

	if ( ! isset( $_POST['_give_hide_outdated_php_notices_shortly'] ) || ! current_user_can( 'manage_give_settings' ) ) {
		give_die();
	}

	// Transient key name.
	$transient_key = '_give_hide_outdated_php_notices_shortly';

	if ( Give_Cache::get( $transient_key, true ) ) {
		return;
	}

	// Hide notice for 24 hours.
	Give_Cache::set( $transient_key, true, DAY_IN_SECONDS, true );

	give_die();

}


/** Function give_process_donation_form() called by wp_ajax hooks: {'nopriv_give_process_donation', 'give_process_donation'} **/
/** No params detected :-/ **/


/** Function give_process_form_login() called by wp_ajax hooks: {'nopriv_give_process_donation_login', 'give_process_donation_login'} **/
/** Parameters found in function give_process_form_login(): {"post": ["give_ajax", "give_login_nonce"]} **/
function give_process_form_login() {

	$is_ajax  = ! empty( $_POST['give_ajax'] ) ? give_clean( $_POST['give_ajax'] ) : 0; // WPCS: input var ok, sanitization ok, CSRF ok.
	$referrer = wp_get_referer();

	// Default to no user until the login form is validated.
	$user_data = [
		'user_id' => - 1,
	];

	// Require a valid nonce before processing the login form.
	if ( empty( $_POST['give_login_nonce'] ) || ! wp_verify_nonce( $_POST['give_login_nonce'], 'give-login-nonce' ) ) {
		give_set_error( 'invalid_nonce', __( 'Your session has expired. Please reload the page and try again.', 'give' ) );
	} else {
		$user_data = give_donation_form_validate_user_login();
	}

	if ( give_get_errors() || $user_data['user_id'] < 1 ) {
		if ( $is_ajax ) {
			/**
			 * Fires when AJAX sends back errors from the donation form.
			 *
			 * @since 1.0
			 */
			ob_start();
			do_action( 'give_ajax_donation_errors' );
			$message = ob_get_contents();
			ob_end_clean();
			wp_send_json_error( $message );
			return;
		} else {
			wp_safe_redirect( $referrer );
			exit;
		}
	}

	give_log_user_in( $user_data['user_id'], $user_data['user_login'], $user_data['user_pass'] );

	if ( $is_ajax ) {
		$message = Give_Notices::print_frontend_notice(
			sprintf(
				/* translators: %s: user first name */
				esc_html__( 'Welcome %s! You have successfully logged into your account.', 'give' ),
				( ! empty( $user_data['user_first'] ) ) ? $user_data['user_first'] : $user_data['user_login']
			),
			false,
			'success'
		);

		wp_send_json_success( $message );
	} else {
		wp_safe_redirect( $referrer );
	}
}


/** Function give_ajax_search_users() called by wp_ajax hooks: {'give_user_search'} **/
/** Parameters found in function give_ajax_search_users(): {"post": ["s"]} **/
function give_ajax_search_users() {
	$results = [];

	if ( current_user_can( 'manage_give_settings' ) ) {

		$search = esc_sql( sanitize_text_field( $_POST['s'] ) );

		$get_users_args = [
			'number' => 9999,
			'search' => $search . '*',
		];

		$get_users_args = apply_filters( 'give_search_users_args', $get_users_args );

		$found_users = apply_filters( 'give_ajax_found_users', get_users( $get_users_args ), $search );
		$results     = [];

		if ( $found_users ) {

			foreach ( $found_users as $user ) {

				$results[] = [
					'id'   => $user->ID,
					'name' => esc_html( $user->user_login . ' (' . $user->user_email . ')' ),
				];
			}
		}
	}// End if().

	wp_send_json( $results );

}


/** Function give_ajax_donor_manage_addresses() called by wp_ajax hooks: {'donor_manage_addresses'} **/
/** Parameters found in function give_ajax_donor_manage_addresses(): {"post": ["form", "donorID"]} **/
function give_ajax_donor_manage_addresses() {
	// Bailout.
	if (
		empty( $_POST['form'] ) ||
		empty( $_POST['donorID'] )
	) {
		wp_send_json_error(
			[
				'error' => 1,
			]
		);
	}

	$post                  = give_clean( wp_parse_args( urldecode_deep( $_POST ) ) );
	$donorID               = absint( $post['donorID'] );
	$form_data             = give_clean( wp_parse_args( $post['form'] ) );
	$is_multi_address_type = ( 'billing' === $form_data['address-id'] || false !== strpos( $form_data['address-id'], '_' ) );
	$exploded_address_id   = explode( '_', $form_data['address-id'] );
	$address_type          = false !== strpos( $form_data['address-id'], '_' ) ?
		array_shift( $exploded_address_id ) :
		$form_data['address-id'];
	$address_id            = false !== strpos( $form_data['address-id'], '_' ) ?
		array_pop( $exploded_address_id ) :
		null;
	$response_data         = [
		'action' => $form_data['address-action'],
		'id'     => $form_data['address-id'],
	];

	// Security check.
	if ( ! wp_verify_nonce( $form_data['_wpnonce'], 'give-manage-donor-addresses' ) ) {
		wp_send_json_error(
			[
				'error'     => 1,
				'error_msg' => wp_sprintf(
					'<div class="notice notice-error"><p>%s</p></div>',
					__( 'Error: Security issue.', 'give' )
				),
			]
		);
	}

	$donor = new Give_Donor( $donorID );

	// Verify donor.
	if ( ! $donor->id ) {
		wp_send_json_error(
			[
				'error' => 3,
			]
		);
	}

	// Unset all data except address.
	unset(
		$form_data['_wpnonce'],
		$form_data['address-action'],
		$form_data['address-id']
	);

	// Process action.
	switch ( $response_data['action'] ) {

		case 'add':
			if ( ! $donor->add_address( "{$address_type}[]", $form_data ) ) {
				wp_send_json_error(
					[
						'error'     => 1,
						'error_msg' => wp_sprintf(
							'<div class="notice notice-error"><p>%s</p></div>',
							__( 'Error: Unable to save the address. Please check if address already exist.', 'give' )
						),
					]
				);
			}

			$total_addresses = count( $donor->address[ $address_type ] );

			$address_index = $is_multi_address_type ?
				$total_addresses - 1 :
				$address_type;

			$array_keys = array_keys( $donor->address[ $address_type ] );

			$address_id = $is_multi_address_type ?
				end( $array_keys ) :
				$address_type;

			$response_data['address_html'] = give_get_format_address(
				end( $donor->address['billing'] ),
				[
					// We can add only billing address from donor screen.
					'type'  => 'billing',
					'id'    => $address_id,
					'index' => ++ $address_index,
				]
			);
			$response_data['success_msg']  = wp_sprintf(
				'<div class="notice updated"><p>%s</p></div>',
				__( 'Successfully added a new address to the donor.', 'give' )
			);

			if ( $is_multi_address_type ) {
				$response_data['id'] = "{$response_data['id']}_{$address_index}";
			}

			break;

		case 'remove':
			if ( ! $donor->remove_address( $response_data['id'] ) ) {
				wp_send_json_error(
					[
						'error'     => 2,
						'error_msg' => wp_sprintf(
							'<div class="notice notice-error"><p>%s</p></div>',
							__( 'Error: Unable to delete address.', 'give' )
						),
					]
				);
			}

			$response_data['success_msg'] = wp_sprintf(
				'<div class="notice updated"><p>%s</p></div>',
				__( 'Successfully removed a address of donor.', 'give' )
			);

			break;

		case 'update':
			if ( ! $donor->update_address( $response_data['id'], $form_data ) ) {
				wp_send_json_error(
					[
						'error'     => 3,
						'error_msg' => wp_sprintf(
							'<div class="notice notice-error"><p>%s</p></div>',
							__( 'Error: Unable to update address. Please check if address already exist.', 'give' )
						),
					]
				);
			}

			$response_data['address_html'] = give_get_format_address(
				$is_multi_address_type ?
					$donor->address[ $address_type ][ $address_id ] :
					$donor->address[ $address_type ],
				[
					'type'  => $address_type,
					'id'    => $address_id,
					'index' => $address_id,
				]
			);
			$response_data['success_msg']  = wp_sprintf(
				'<div class="notice updated"><p>%s</p></div>',
				__( 'Successfully updated a address of donor', 'give' )
			);

			break;
	}// End switch().

	wp_send_json_success( $response_data );
}


/** Function give_do_ajax_export() called by wp_ajax hooks: {'give_do_ajax_export'} **/
/** Parameters found in function give_do_ajax_export(): {"post": ["form", "step", "file_name"], "request": ["give_ajax_export"]} **/
function give_do_ajax_export() {

	require_once GIVE_PLUGIN_DIR . 'includes/admin/tools/export/class-batch-export.php';

	parse_str( $_POST['form'], $form );

	$_REQUEST = $form = (array) $form;

	if (
		! wp_verify_nonce( $_REQUEST['give_ajax_export'], 'give_ajax_export' ) ||
		! current_user_can( 'manage_give_settings' )
    ) {
		die( '-2' );
	}

	/**
	 * Fires before batch export.
	 *
	 * @since 1.5
	 *
	 * @param string $class Export class.
	 */
	do_action( 'give_batch_export_class_include', $form['give-export-class'] );

    if(  ! is_subclass_of( $form['give-export-class'], \Give_Batch_Export::class ) ) {
        die(-2);
    }

	$step     = absint( $_POST['step'] );
	$class    = sanitize_text_field( $form['give-export-class'] );
	$filename = isset( $_POST['file_name'] ) ?
        basename(sanitize_file_name( $_POST['file_name'] ), '.csv') :
        null;

	/* @var Give_Batch_Export $export */
	$export = new $class( $step, $filename );

	if ( ! $export->can_export() ) {
		die( '-1' );
	}

	if ( ! $export->is_writable ) {
		$json_args = [
			'error'   => true,
			'message' => esc_html__( 'Export location or file not writable.', 'give' ),
		];
		echo json_encode( $json_args );
		exit;
	}

	$export->set_properties( give_clean( $_REQUEST ) );

	$export->pre_fetch();

	$ret = $export->process_step();

	$percentage = $export->get_percentage_complete();

	if ( $ret ) {

		$step     += 1;
		$json_data = [
			'step'       => $step,
			'percentage' => $percentage,
			'file_name'  => $export->filename,
		];

	} elseif ( true === $export->is_empty ) {

		$json_data = [
			'error'   => true,
			'message' => esc_html__( 'No data found for export parameters.', 'give' ),
		];

	} elseif ( true === $export->done && true === $export->is_void ) {

		$message = ! empty( $export->message ) ?
			$export->message :
			esc_html__( 'Batch Processing Complete', 'give' );

		$json_data = [
			'success' => true,
			'message' => $message,
		];

	} else {

		$args = array_merge(
			$_REQUEST,
			[
				'step'        => $step,
				'class'       => $class,
				'nonce'       => wp_create_nonce( 'give-batch-export' ),
				'give_action' => 'form_batch_export',
				'file_name'   => $export->filename,
			]
		);

		$json_data = [
			'step' => 'done',
			'url'  => esc_url_raw(add_query_arg( $args, admin_url() )),
		];

	}

	$export->unset_properties( give_clean( $_REQUEST ), $export );
	echo json_encode( $json_data );
	exit;
}


/** Function give_trigger_upgrades() called by wp_ajax hooks: {'give_trigger_upgrades'} **/
/** No params detected :-/ **/


/** Function give_set_notification_status_handler() called by wp_ajax hooks: {'give_set_notification_status'} **/
/** Parameters found in function give_set_notification_status_handler(): {"post": ["notification_id", "status"]} **/
function give_set_notification_status_handler() {
	check_ajax_referer( 'give_set_notification_status', '_ajax_nonce' );

	// Is user have permission to edit give setting.
	if ( ! current_user_can( 'manage_give_settings' ) ) {
		return;
	}

	$notification_id = isset( $_POST['notification_id'] ) ? give_clean( $_POST['notification_id'] ) : '';
	if ( ! empty( $notification_id ) && give_update_option( "{$notification_id}_notification", give_clean( $_POST['status'] ) ) ) {
		wp_send_json_success();
	}

	wp_send_json_error();
}


/** Function give_get_receipt() called by wp_ajax hooks: {'nopriv_get_receipt', 'get_receipt'} **/
/** No params detected :-/ **/


/** Function give_sendwp_remote_install_handler() called by wp_ajax hooks: {'give_sendwp_remote_install'} **/
/** No params detected :-/ **/


/** Function give_start_updating() called by wp_ajax hooks: {'give_run_db_updates'} **/
/** Parameters found in function give_start_updating(): {"post": ["run_db_update"]} **/
function give_start_updating() {
		// Check permission.
		if (
			! current_user_can( 'manage_give_settings' ) ||
			$this->is_doing_updates()
		) {
			// Run update via ajax
			self::$background_updater->dispatch();

			wp_send_json_error();
		}

		// @todo: validate nonce
		// @todo: set http method to post
		if ( empty( $_POST['run_db_update'] ) ) {
			wp_send_json_error();
		}

		$this->run_db_update();

		wp_send_json_success();
	}


/** Function givewp-goal-notice-dismissed() called by wp_ajax hooks: {'givewp_goal_hide_notice'} **/
/** No function found :-/ **/


/** Function formId() called by wp_ajax hooks: {'givewp_transfer_hide_notice', 'givewp_migration_hide_notice'} **/
/** No params detected :-/ **/


/** Function give_activate_addon_handler() called by wp_ajax hooks: {'give_activate_addon'} **/
/** Parameters found in function give_activate_addon_handler(): {"post": ["plugin"]} **/
function give_activate_addon_handler() {
	$plugin_path = give_clean( $_POST['plugin'] );

	check_admin_referer( "give_activate-{$plugin_path}" );

	// check user permission.
	if ( ! current_user_can( 'manage_give_settings' ) ) {
		give_die();
	}

	if ( empty( $plugin_path ) ) {
		wp_send_json_error(
			[
				'errorMsg' => __( 'No plugin path was provided. The uploaded plugin may not have been detected correctly.', 'give' ),
			]
		);
	}

	$plugin_file = WP_PLUGIN_DIR . '/' . $plugin_path;

	if ( ! file_exists( $plugin_file ) ) {
		wp_send_json_error(
			[
				'errorMsg' => sprintf(
					/* translators: %1$s: plugin file path */
					__( 'The plugin file "%1$s" could not be found. The add-on may not have been extracted correctly.', 'give' ),
					esc_html( $plugin_path )
				),
			]
		);
	}

	$plugin_data = get_plugin_data( $plugin_file );

	if ( empty( $plugin_data['Name'] ) ) {
		wp_send_json_error(
			[
				'errorMsg' => sprintf(
					/* translators: %1$s: plugin file path */
					__( 'The plugin "%1$s" does not have a valid plugin header. The add-on may be corrupted or incompatible.', 'give' ),
					esc_html( $plugin_path )
				),
			]
		);
	}

	$status = activate_plugin( $plugin_path );

	if ( is_wp_error( $status ) ) {
		wp_send_json_error( [ 'errorMsg' => $status->get_error_message() ] );
	}

	// Tell WordPress to look for updates.
	give_refresh_licenses();

	wp_send_json_success(
		[
			'licenseSectionHtml' => Give_License::render_licenses_list(),
		]
	);
}


/** Function givewp-{$metaKey}() called by wp_ajax hooks: {'{$action}'} **/
/** No function found :-/ **/


/** Function give_donation_import_callback() called by wp_ajax hooks: {'give_donation_import'} **/
/** Parameters found in function give_donation_import_callback(): {"post": ["fields"], "request": ["current", "total_ajax", "start", "end", "next", "total", "per_page"]} **/
function give_donation_import_callback() {

    check_ajax_referer('give_donation_import');

	// Bailout.
	if ( ! current_user_can( 'manage_give_settings' ) ) {
		give_die();
	}

	// Disable Give cache
	Give_Cache::get_instance()->disable();

	$import_setting = [];
	$fields         = isset( $_POST['fields'] ) ? $_POST['fields'] : null;

	parse_str( $fields, $output );

	$import_setting['create_user'] = $output['create_user'];
	$import_setting['mode']        = $output['mode'];
	$import_setting['delimiter']   = $output['delimiter'];
	$import_setting['csv']         = $output['csv'];
	$import_setting['delete_csv']  = $output['delete_csv'];
	$import_setting['dry_run']     = $output['dry_run'];

	// Parent key id.
    $main_key = give_maybe_safe_unserialize($output['main_key']);

	$current    = absint( $_REQUEST['current'] );
	$total_ajax = absint( $_REQUEST['total_ajax'] );
	$start      = absint( $_REQUEST['start'] );
	$end        = absint( $_REQUEST['end'] );
	$next       = absint( $_REQUEST['next'] );
	$total      = absint( $_REQUEST['total'] );
	$per_page   = absint( $_REQUEST['per_page'] );
	if ( empty( $output['delimiter'] ) ) {
		$delimiter = ',';
	} else {
		$delimiter = $output['delimiter'];
	}

	// Processing done here.
	$raw_data                  = give_get_donation_data_from_csv( $output['csv'], $start, $end, $delimiter);
    $raw_key = give_maybe_safe_unserialize($output['mapto']);
	$import_setting['raw_key'] = $raw_key;

	if ( ! empty( $output['dry_run'] ) ) {
		$import_setting['csv_raw_data'] = give_get_donation_data_from_csv( $output['csv'], 1, $end, $delimiter );

		$import_setting['donors_list'] = Give()->donors->get_donors(
			[
				'number' => - 1,
				'fields' => [ 'id', 'user_id', 'email' ],
			]
		);
	}

	// Prevent normal emails.
	remove_action( 'give_complete_donation', 'give_trigger_donation_receipt', 999 );
	remove_action( 'give_insert_user', 'give_new_user_notification', 10 );
	remove_action( 'give_insert_payment', 'give_payment_save_page_data' );

    $current_key = $start;
    foreach ( $raw_data as $row_data ) {
        $import_setting['donation_key'] = $current_key;
        $result = give_save_import_donation_to_db( $raw_key, $row_data, $main_key, $import_setting );
        if ( is_string( $result ) && ! empty( $result ) ) {
            if ( empty( $json_data['errors'] ) ) {
                $json_data['errors'] = [];
            }
            $json_data['errors'][] = sprintf( __( 'Row %1$d: %2$s', 'give' ), $current_key, $result );
        }
        $current_key ++;
    }

	// Check if function exists or not.
	if ( function_exists( 'give_payment_save_page_data' ) ) {
		add_action( 'give_insert_payment', 'give_payment_save_page_data' );
	}
	add_action( 'give_insert_user', 'give_new_user_notification', 10, 2 );
	add_action( 'give_complete_donation', 'give_trigger_donation_receipt', 999 );

	if ( $next == false ) {
		$json_data = [
			'success' => true,
			'message' => __( 'All donation uploaded successfully!', 'give' ),
		];
	} else {
		$index_start = $start;
		$index_end   = $end;
		$last        = false;
		$next        = true;
		if ( $next ) {
			$index_start = $index_start + $per_page;
			$index_end   = $per_page + ( $index_start - 1 );
		}
		if ( $index_end >= $total ) {
			$index_end = $total;
			$last      = true;
		}
		$json_data = [
			'raw_data' => $raw_data,
			'raw_key'  => $raw_key,
			'next'     => $next,
			'start'    => $index_start,
			'end'      => $index_end,
			'last'     => $last,
		];
	}

	$url              = give_import_page_url(
		[
			'step'          => '4',
			'importer-type' => 'import_donations',
			'csv'           => $output['csv'],
			'total'         => $total,
			'delete_csv'    => $import_setting['delete_csv'],
			'success'       => ( isset( $json_data['success'] ) ? $json_data['success'] : '' ),
			'dry_run'       => $output['dry_run'],
            '_wpnonce'      => wp_create_nonce( 'give_donation_import_success' ),
		]
	);
	$json_data['url'] = $url;

	$current ++;
	$json_data['current'] = $current;

	$percentage              = ( 100 / ( $total_ajax + 1 ) ) * $current;
	$json_data['percentage'] = $percentage;

	// Enable Give cache
	Give_Cache::get_instance()->enable();

	$json_data = apply_filters( 'give_import_ajax_responces', $json_data, $fields );
	wp_die( json_encode( $json_data ) );
}


/** Function give_export_donations_get_custom_fields() called by wp_ajax hooks: {'give_export_donations_get_custom_fields'} **/
/** Parameters found in function give_export_donations_get_custom_fields(): {"post": ["form_id"]} **/
function give_export_donations_get_custom_fields() {
	global $wpdb;

	if ( ! current_user_can( 'export_give_reports' ) ) {
		wp_send_json_error();
	}

	$post_type              = 'give_payment';
	$responses              = [];
	$donationmeta_table_key = Give()->payment_meta->get_meta_type() . '_id';

	$form_id = isset( $_POST['form_id'] ) ? absint( $_POST['form_id'] ) : '';

	if ( empty( $form_id ) ) {
		wp_send_json_error();
	}

	$args = [
		'give_forms'     => [ $form_id ],
		'posts_per_page' => - 1,
		'fields'         => 'ids',
	];

	$donation_list = implode( ',', (array) give_get_payments( $args ) );

	$query_and = sprintf(
		"AND $wpdb->posts.ID IN (%s)
		AND $wpdb->donationmeta.meta_key != ''
		AND $wpdb->donationmeta.meta_key NOT RegExp '(^[_0-9].+$)'",
		$donation_list
	);

	$query = "
        SELECT DISTINCT($wpdb->donationmeta.meta_key)
        FROM $wpdb->posts
        LEFT JOIN $wpdb->donationmeta
        ON $wpdb->posts.ID = {$wpdb->donationmeta}.{$donationmeta_table_key}
        WHERE $wpdb->posts.post_type = '%s'
    " . $query_and;

	$meta_keys = $wpdb->get_col( $wpdb->prepare( $query, $post_type ) );

	if ( ! empty( $meta_keys ) ) {
		$responses['standard_fields'] = array_values( $meta_keys );
	}

	$query_and = sprintf(
		"AND $wpdb->posts.ID IN (%s)
		AND $wpdb->donationmeta.meta_key != ''
		AND $wpdb->donationmeta.meta_key NOT RegExp '^[^_]'",
		$donation_list
	);

	$query = "
        SELECT DISTINCT($wpdb->donationmeta.meta_key)
        FROM $wpdb->posts
        LEFT JOIN $wpdb->donationmeta
        ON $wpdb->posts.ID = {$wpdb->donationmeta}.{$donationmeta_table_key}
        WHERE $wpdb->posts.post_type = '%s'
    " . $query_and;

	$hidden_meta_keys = $wpdb->get_col( $wpdb->prepare( $query, $post_type ) );

	/**
	 * Filter to modify hidden keys that are going to be ignore when displaying the hidden keys
	 *
	 * @param array $ignore_hidden_keys Hidden keys that are going to be ignore
	 * @param array $form_id            Donation form id
	 *
	 * @return array $ignore_hidden_keys Hidden keys that are going to be ignore
	 * @since 2.1
	 */
	$ignore_hidden_keys = apply_filters(
		'give_export_donations_ignore_hidden_keys',
		[
			'_give_payment_meta',
			'_give_payment_gateway',
			'_give_payment_mode',
			'_give_payment_form_title',
			'_give_payment_form_id',
			'_give_payment_price_id',
			'_give_payment_user_id',
			'_give_payment_user_email',
			'_give_payment_user_ip',
			'_give_payment_customer_id',
			'_give_payment_total',
			'_give_completed_date',
			'_give_donation_company',
			'_give_donor_billing_first_name',
			'_give_donor_billing_last_name',
			'_give_payment_donor_email',
			'_give_payment_donor_id',
			'_give_payment_date',
			'_give_donor_billing_address1',
			'_give_donor_billing_address2',
			'_give_donor_billing_city',
			'_give_donor_billing_zip',
			'_give_donor_billing_state',
			'_give_donor_billing_country',
			'_give_payment_import',
			'_give_payment_currency',
			'_give_payment_import_id',
			'_give_payment_donor_ip',
			'_give_payment_donor_title_prefix',
		],
		$form_id
	);

	// Unset ignored hidden keys.
	foreach ( $ignore_hidden_keys as $key ) {
		if ( ( $key = array_search( $key, $hidden_meta_keys ) ) !== false ) {
			unset( $hidden_meta_keys[ $key ] );
		}
	}

	if ( ! empty( $hidden_meta_keys ) ) {
		$responses['hidden_fields'] = array_values( $hidden_meta_keys );
	}

	/**
	 * Filter to modify custom fields when select donation forms,
	 *
	 * @param array $responses Contain all the fields that need to be display when donation form is display
	 * @param int   $form_id   Donation Form ID
	 *
	 * @return array $responses
	 * @since 2.1
	 */
	wp_send_json( (array) apply_filters( 'give_export_donations_get_custom_fields', $responses, $form_id ) );

}


/** Function mode() called by wp_ajax hooks: {'givewp_tour_completed'} **/
/** No params detected :-/ **/


/** Function handleReceiptAjax() called by wp_ajax hooks: {'nopriv_get_receipt', 'get_receipt'} **/
/** No params detected :-/ **/


/** Function give_ajax_pages_search() called by wp_ajax hooks: {'give_pages_search'} **/
/** Parameters found in function give_ajax_pages_search(): {"post": ["s"]} **/
function give_ajax_pages_search() {
	$data = [];
	$args = [
		'post_type' => 'page',
		's'         => give_clean( $_POST['s'] ),
	];

	$query = new WP_Query( $args );

	// Query posts by title.
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();

			$data[] = [
				'id'   => get_the_ID(),
				'name' => get_the_title(),
			];
		}
	}

	wp_send_json( $data );
}


/** Function shortcode_ajax() called by wp_ajax hooks: {'give_shortcode'} **/
/** Parameters found in function shortcode_ajax(): {"post": ["shortcode"]} **/
function shortcode_ajax() {
		if ( ! current_user_can( 'edit_give_forms' ) ) {
			wp_die();
		}

		$shortcode = isset( $_POST['shortcode'] ) ? $_POST['shortcode'] : false;
		$response  = false;

		if ( $shortcode && array_key_exists( $shortcode, self::$shortcodes ) ) {

			$data = self::$shortcodes[ $shortcode ];

			if ( ! empty( $data['errors'] ) ) {
				$data['btn_okay'] = array( esc_html__( 'Okay', 'give' ) );
			}

			$response = array(
				'body'      => $data['fields'],
				'close'     => $data['btn_close'],
				'ok'        => $data['btn_okay'],
				'shortcode' => $shortcode,
				'title'     => $data['title'],
			);
		} else {
			error_log( print_r( 'AJAX error!', 1 ) );
		}

		wp_send_json( $response );
	}


/** Function give_check_for_form_price_variations() called by wp_ajax hooks: {'give_check_for_form_price_variations'} **/
/** Parameters found in function give_check_for_form_price_variations(): {"post": ["form_id", "all_prices"]} **/
function give_check_for_form_price_variations() {

	if ( ! current_user_can( 'edit_give_forms', get_current_user_id() ) ) {
		die( '-1' );
	}

	$form_id = absint( $_POST['form_id'] );
	$form    = get_post( $form_id );

	if ( 'give_forms' !== $form->post_type ) {
		die( '-2' );
	}

	if ( give_has_variable_prices( $form_id ) ) {
		$variable_prices = give_get_variable_prices( $form_id );

		if ( $variable_prices ) {
			$ajax_response = '<select class="give_price_options_select give-select give-select" name="give_price_option">';

			if ( isset( $_POST['all_prices'] ) ) {
				$ajax_response .= '<option value="all">' . esc_html__( 'All Levels', 'give' ) . '</option>';
			}

			foreach ( $variable_prices as $key => $price ) {

				$level_text = ! empty( $price['_give_text'] ) ? esc_html( $price['_give_text'] ) : give_currency_filter( give_format_amount( $price['_give_amount'], [ 'sanitize' => false ] ) );

				$ajax_response .= '<option value="' . esc_attr( $price['_give_id']['level_id'] ) . '">' . $level_text . '</option>';
			}
			$ajax_response .= '</select>';
			echo $ajax_response;
		}
	}

	give_die();
}


/** Function give_get_license_info_handler() called by wp_ajax hooks: {'give_get_license_info'} **/
/** Parameters found in function give_get_license_info_handler(): {"post": ["license", "single", "reactivate", "addon"]} **/
function give_get_license_info_handler() {
	check_admin_referer( 'give-license-activator-nonce' );

	// check user permission.
	if ( ! current_user_can( 'manage_give_settings' ) ) {
		give_die();
	}

	$license_key                  = ! empty( $_POST['license'] ) ? give_clean( $_POST['license'] ) : '';
	$is_activating_single_license = ! empty( $_POST['single'] ) ? absint( $_POST['single'] ) : '';
	$is_reactivating_license      = ! empty( $_POST['reactivate'] ) ? absint( $_POST['reactivate'] ) : '';
	$plugin_slug                  = $is_activating_single_license ? give_clean( $_POST['addon'] ) : '';
	$licenses                     = get_option( 'give_licenses', [] );

	if ( ! $license_key ) {
		wp_send_json_error(
			[
				'errorMsg' => __( 'You entered an invalid key. Confirm your license key on your GiveWP dashboard and try again.', 'give' ),
			]
		);

	} elseif ( 0 === stripos( $license_key, 'LWSW-' ) ) {
		// The Unified License Manager is only available once a premium add-on is installed and active.
		$harborHasLoaded = give( HarborHasLoaded::class )();

		$error_message = $harborHasLoaded
			? sprintf(
				/* translators: %s: URL to the Unified License Manager page */
				__( 'This is a unified license key. To activate it, enter your license in the <a href="%s" target="_blank">Unified License Manager</a> instead.', 'give' ),
				esc_url( lw_harbor_get_license_page_url() )
			)
			: __( 'This is a unified license key. It can be added from the Unified License Manager, which appears under GiveWP &gt; Licensing once an add-on is installed.', 'give' );

		wp_send_json_error(
			[
				'errorMsg' => $error_message,
			]
		);

	} elseif (
		! $is_reactivating_license
		&& array_key_exists( $license_key, $licenses )
	) {
		// If admin already activated license but did not install add-on then send license info show notice to admin with download link.
		$license = $licenses[ $license_key ];
		if ( empty( $license['is_all_access_pass'] ) ) {
			$plugin_data = Give_License::get_plugin_by_slug( $license['plugin_slug'] );

			// Plugin license activated but does not install, sent notice which allow admin to download add-on.
			if ( empty( $plugin_data ) ) {
				wp_send_json_success( $license );
			}
		}

		wp_send_json_error(
			[
				'errorMsg' => __( 'This license key is already in use on this website.', 'give' ),
			]
		);
	}

	// Check license.
	$check_license_res = Give_License::request_license_api(
		[
			'edd_action' => 'check_license',
			'license'    => $license_key,
		],
		true
	);

	// Make sure there are no errors.
	if ( is_wp_error( $check_license_res ) ) {
		wp_send_json_error(
			[
				'errorMsg' => $check_license_res->get_error_message(),
			]
		);
	}

	// Check if license valid or not.
	if ( ! $check_license_res['success'] ) {
		wp_send_json_error(
			[
				'errorMsg' => sprintf(
					__( 'The license failed to activate, due to a status of <code>%2$s</code>. Check the logs at Donations > Tools > Logs for more detail, and <a href="%1$s" target="_blank">reach out to the Customer Success team.</a>', 'give' ),
					Give_License::get_account_url(),
					$check_license_res['license']
				),
			]
		);
	}

	if (
		$is_activating_single_license
		&& ! empty( $check_license_res['plugin_slug'] )
		&& $plugin_slug !== $check_license_res['plugin_slug']
	) {
		wp_send_json_error(
			[
				'errorMsg' => sprintf(
					__( 'This license key does not belong to this add-on. <a href="%1$s" target="_blank">Reach out to the Customer Success team</a> if you continue to have issues.', 'give' ),
					Give_License::get_account_url()
				),
			]
		);
	}

	// Activate license.
	$activate_license_res = Give_License::request_license_api(
		[
			'edd_action' => 'activate_license',
			'item_name'  => $check_license_res['item_name'],
			'license'    => $license_key,
		],
		true
	);

	if ( is_wp_error( $activate_license_res ) ) {
		wp_send_json_error(
			[
				'errorMsg' => $check_license_res->get_error_message(),
			]
		);
	}

	// Return error if license activation is not success and admin is not reactivating add-on.
	if ( ! $is_reactivating_license && ! $activate_license_res['success'] ) {

		$response['errorMsg'] = sprintf(
			__( 'The license failed to activate, due to a status of <code>%2$s</code>. Check the logs at Donations > Tools > Logs for more detail, and <a href="%1$s" target="_blank">reach out to the Customer Success team.</a>', 'give' ),
			Give_License::get_account_url(),
			$check_license_res['license']
		);

		wp_send_json_error( $response );
	}

	$check_license_res['license']          = $activate_license_res['license'];
	$check_license_res['site_count']       = $activate_license_res['site_count'];
	$check_license_res['activations_left'] = $activate_license_res['activations_left'];

	// Remove single license which is part of all access pass or already existed all access pass key because admin can only one active.
	// @see https://github.com/impress-org/givewp/issues/4669
	if ( ! empty( $check_license_res['is_all_access_pass'] ) ) {
		$addonSlugs = Give_License::getAddonSlugsFromAllAccessPassLicense( $check_license_res );
		foreach ( $licenses as $license_key => $data ) {
			if ( in_array( $data['plugin_slug'], $addonSlugs, true ) || ! empty( $data['is_all_access_pass'] ) ) {
				unset( $licenses[ $license_key ] );
			}
		}
	}

	$licenses[ $check_license_res['license_key'] ] = $check_license_res;
	update_option( 'give_licenses', $licenses );

	// Get license section HTML.
	$response         = $check_license_res;
	$response['html'] = $is_activating_single_license && empty( $check_license_res['is_all_access_pass'] )
		? Give_License::html_by_plugin( Give_License::get_plugin_by_slug( $check_license_res['plugin_slug'] ) )
		: Give_License::render_licenses_list();

	// Return error if license activation is not success and admin is reactivating add-on.
	if ( $is_reactivating_license && ! $activate_license_res['success'] ) {

		$response['errorMsg'] = sprintf(
			__( 'The license failed to activate, due to a status of <code>%2$s</code>. Check the logs at Donations > Tools > Logs for more detail, and <a href="%1$s" target="_blank">reach out to the Customer Success team.</a>', 'give' ),
			Give_License::get_account_url(),
			$check_license_res['license']
		);

		wp_send_json_error( $response );
	}

	// Tell WordPress to look for updates.
	give_refresh_licenses();

	wp_send_json_success( $response );
}


/** Function give_deactivate_license_handler() called by wp_ajax hooks: {'give_deactivate_license'} **/
/** Parameters found in function give_deactivate_license_handler(): {"post": ["license", "item_name", "plugin_dirname"]} **/
function give_deactivate_license_handler() {
	$license        = give_clean( $_POST['license'] );
	$item_name      = give_clean( $_POST['item_name'] );
	$plugin_dirname = give_clean( $_POST['plugin_dirname'] );

	if ( ! $license || ! $item_name ) {
		wp_send_json_error();
	}

	check_admin_referer( "give-deactivate-license-{$item_name}" );

	// check user permission.
	if ( ! current_user_can( 'manage_give_settings' ) ) {
		give_die();
	}

	$give_licenses = get_option( 'give_licenses', [] );

	if ( empty( $give_licenses[ $license ] ) ) {
		wp_send_json_error(
			[
				'errorMsg' => __( 'License is invalid, so it can\'t be deactivated.', 'give' ),
			]
		);
	}

	/* @var array|WP_Error $response */
	$response = Give_License::request_license_api(
		[
			'edd_action' => 'deactivate_license',
			'license'    => $license,
			'item_name'  => $item_name,
		],
		true
	);

	if ( is_wp_error( $response ) ) {
		wp_send_json_error(
			[
				'errorMsg' => $response->get_error_message(),
				'response' => $license,
			]
		);
	}

	$is_all_access_pass = $give_licenses[ $license ]['is_all_access_pass'];

	if ( ! empty( $give_licenses[ $license ] ) ) {
		unset( $give_licenses[ $license ] );
		update_option( 'give_licenses', $give_licenses );
	}

	$response['html'] = $is_all_access_pass
		? Give_License::render_licenses_list()
		: Give_License::html_by_plugin( Give_License::get_plugin_by_slug( $plugin_dirname ) );

	$response['msg'] = __( 'You have successfully deactivated the license.', 'give' );

	// Tell WordPress to look for updates.
	give_refresh_licenses();

	wp_send_json_success( $response );
}


/** Function give_ajax_donor_search() called by wp_ajax hooks: {'give_donor_search'} **/
/** Parameters found in function give_ajax_donor_search(): {"post": ["s"]} **/
function give_ajax_donor_search() {
	global $wpdb;

	$search  = esc_sql( sanitize_text_field( $_POST['s'] ) );
	$results = [];
	if ( ! current_user_can( 'view_give_reports' ) ) {
		$donors = [];
	} else {
		$donors = $wpdb->get_results( "SELECT id,name,email FROM $wpdb->donors WHERE `name` LIKE '%$search%' OR `email` LIKE '%$search%' LIMIT 50" );
	}

	if ( $donors ) {
		foreach ( $donors as $donor ) {

			$results[] = [
				'id'   => $donor->id,
				'name' => $donor->name . ' (' . $donor->email . ')',
			];
		}
	}

	wp_send_json( $results );
}


/** Function give_load_checkout_login_fields() called by wp_ajax hooks: {'nopriv_give_checkout_login'} **/
/** No params detected :-/ **/


/** Function give_ajax_delete_payment_note() called by wp_ajax hooks: {'give_delete_payment_note'} **/
/** Parameters found in function give_ajax_delete_payment_note(): {"post": ["payment_id", "note_id"]} **/
function give_ajax_delete_payment_note() {
    check_ajax_referer('give_delete_payment_note');

	if ( ! current_user_can( 'edit_give_payments', $_POST['payment_id'] ) ) {
		wp_die( __( 'You do not have permission to edit payments.', 'give' ), __( 'Error', 'give' ), array( 'response' => 403 ) );
	}

	if ( give_delete_payment_note( $_POST['note_id'], $_POST['payment_id'] ) ) {
		die( '1' );
	} else {
		die( '-1' );
	}

}


