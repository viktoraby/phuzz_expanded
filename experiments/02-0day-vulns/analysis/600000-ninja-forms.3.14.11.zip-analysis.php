<?php
/***
*
*Found actions: 34
*Found functions:24
*Extracted functions:20
*Total parameter names extracted: 15
*Overview: {'update': {'nf_update_saved_field', 'nf_preview_update'}, 'ninja_forms_dashboard_nonce': {'nf_update_cache_mode'}, 'create': {'nf_create_saved_field'}, 'maybe_opt_in': {'nf_optin'}, 'get_new_nonce': {'nf_ajax_get_new_nonce', 'nopriv_nf_ajax_get_new_nonce'}, 'log_error': {'nf_log_js_error', 'nopriv_nf_log_js_error'}, 'ninja_forms_admin_all_forms_capabilities': {'nf_oauth'}, 'delete': {'nf_delete_saved_field', 'nf_delete_form', 'nf_delete'}, 'save': {'nf_save_form'}, 'wp_ajax_ninja_forms_sendwp_remote_install_handler': {'ninja_forms_sendwp_remote_install'}, 'duplicate': {'nf_duplicate'}, 'remove_maintenance_mode': {'nf_remove_maintenance_mode'}, 'disconnect': {'nf_oauth_disconnect'}, 'get_forms': {'nf_get_forms'}, 'resume': {'nopriv_nf_ajax_resume', 'nf_ajax_resume'}, 'hide_columns': {'nf_hide_columns'}, 'maybe_delete_field': {'nf_maybe_delete_field'}, 'delete_all_data': {'nf_delete_all_data'}, 'submit': {'nopriv_nf_ajax_submit', 'nf_ajax_submit'}, 'DashboardServices': {'nf_services'}, 'connect': {'nf_oauth_connect'}, '<pre>': {'nf_services_install'}, 'get_new_form_templates': {'nf_get_new_form_templates'}, 'handle': {'nf_onboarding_complete', 'nf_onboarding_dismiss', 'nf_onboarding_next', 'nf_onboarding_start'}}
*
***/

/** Function update() called by wp_ajax hooks: {'nf_update_saved_field', 'nf_preview_update'} **/
/** No params detected :-/ **/


/** Function ninja_forms_dashboard_nonce() called by wp_ajax hooks: {'nf_update_cache_mode'} **/
/** No function found :-/ **/


/** Function create() called by wp_ajax hooks: {'nf_create_saved_field'} **/
/** No params detected :-/ **/


/** Function maybe_opt_in() called by wp_ajax hooks: {'nf_optin'} **/
/** Parameters found in function maybe_opt_in(): {"post": ["_wpnonce"]} **/
function maybe_opt_in()
    {
        // Verify nonce for CSRF protection
        if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'nf_optin_nonce' ) ) {
            wp_die( 'Security check failed' );
        }

        if( $this->can_opt_in() ) {

            $opt_in_action = htmlspecialchars( $_POST[ self::FLAG ] );

            if( self::OPT_IN == $opt_in_action ){
                $this->opt_in();
            }

            if( self::OPT_OUT == $opt_in_action ){
                $this->opt_out();
            }
        }
        die( 1 );
    }


/** Function get_new_nonce() called by wp_ajax hooks: {'nf_ajax_get_new_nonce', 'nopriv_nf_ajax_get_new_nonce'} **/
/** No params detected :-/ **/


/** Function log_error() called by wp_ajax hooks: {'nf_log_js_error', 'nopriv_nf_log_js_error'} **/
/** Parameters found in function log_error(): {"request": ["message", "url", "lineNumber"]} **/
function log_error()
    {
        check_ajax_referer( 'ninja_forms_display_nonce', 'security' );
        $message = esc_html( stripslashes( $_REQUEST[ 'message' ] ) );
        $url = esc_html( stripslashes( $_REQUEST[ 'url' ] ) );
        $lineNumber = esc_html( stripslashes( $_REQUEST[ 'lineNumber' ] ) );

        Ninja_Forms()->logger()->emergency( $message . ' in ' . $url . ' on line ' . $lineNumber );
 
        die( 1 );
    }


/** Function ninja_forms_admin_all_forms_capabilities() called by wp_ajax hooks: {'nf_oauth'} **/
/** No function found :-/ **/


/** Function delete() called by wp_ajax hooks: {'nf_delete_saved_field', 'nf_delete_form', 'nf_delete'} **/
/** No params detected :-/ **/


/** Function save() called by wp_ajax hooks: {'nf_save_form'} **/
/** No params detected :-/ **/


/** Function wp_ajax_ninja_forms_sendwp_remote_install_handler() called by wp_ajax hooks: {'ninja_forms_sendwp_remote_install'} **/
/** Parameters found in function wp_ajax_ninja_forms_sendwp_remote_install_handler(): {"request": ["nonce"]} **/
function wp_ajax_ninja_forms_sendwp_remote_install_handler () {
    if (!current_user_can('manage_options') || ! isset($_REQUEST['nonce']) || ! wp_verify_nonce( $_REQUEST['nonce'] , 'ninja_forms_sendwp_remote_install') ) {
        ob_end_clean();
        echo json_encode( array( 'error' => esc_html__( 'Something went wrong. SendWP was not installed correctly.', 'ninja-forms') ) );
        exit;
    }

    $all_plugins = get_plugins();
    $is_sendwp_installed = false;
    foreach(get_plugins() as $path => $details ) {
        if(false === strpos($path, '/sendwp.php')) continue;
        $is_sendwp_installed = true;
        activate_plugin( $path );
        break;
    }

    if( ! $is_sendwp_installed ) {

        $plugin_slug = 'sendwp';

        include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        include_once ABSPATH . 'wp-admin/includes/file.php';
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        
        /*
        * Use the WordPress Plugins API to get the plugin download link.
        */
        $api = plugins_api( 'plugin_information', array(
            'slug' => $plugin_slug,
        ) );
        if ( is_wp_error( $api ) ) {
            ob_end_clean();
            echo json_encode( array( 'error' => $api->get_error_message(), 'debug' => $api ) );
            exit;
        }
        
        /*
        * Use the AJAX Upgrader skin to quietly install the plugin.
        */
        $upgrader = new Plugin_Upgrader( new WP_Ajax_Upgrader_Skin() );
        $install = $upgrader->install( $api->download_link );
        if ( is_wp_error( $install ) ) {
            ob_end_clean();
            echo json_encode( array( 'error' => $install->get_error_message(), 'debug' => $api ) );
            exit;
        }
        
        /*
        * Activate the plugin based on the results of the upgrader.
        * @NOTE Assume this works, if the download works - otherwise there is a false positive if the plugin is already installed.
        */
        $activated = activate_plugin( $upgrader->plugin_info() );

    }

    /*
     * Final check to see if SendWP is available.
     */
    if( ! function_exists('sendwp_get_server_url') ) {
        ob_end_clean();
        echo json_encode( array(
            'error' => esc_html__( 'Something went wrong. SendWP was not installed correctly.' ),
            'install' => $install,
            ) );
        exit;
    }
    
    echo json_encode( array(
        'partner_id' => 16,
        'register_url' => esc_url(sendwp_get_server_url() . '_/signup'),
        'client_name' => esc_attr( sendwp_get_client_name() ),
        'client_secret' => esc_attr( sendwp_get_client_secret() ),
        'client_redirect' => esc_url(sendwp_get_client_redirect()),
        'client_url' => esc_url( sendwp_get_client_url() ),
    ) );
    exit;
}


/** Function duplicate() called by wp_ajax hooks: {'nf_duplicate'} **/
/** Parameters found in function duplicate(): {"request": ["form_id"]} **/
function duplicate()
    {
        $form_id = absint($_REQUEST[ 'form_id' ]);

        //Copied and pasted from NF_Database_models_Form::duplicate line 136
        $form = Ninja_Forms()->form( $form_id )->get();

        $settings = $form->get_settings();

        $new_form = Ninja_Forms()->form()->get();
        $new_form->update_settings( $settings );

        $form_title = $form->get_setting( 'title' );

        $new_form_title = $form_title . " - " . esc_html__( 'copy', 'ninja-forms' );

        $new_form->update_setting( 'title', $new_form_title );

        $new_form->update_setting( 'lock', 0 );

        $new_form->save();

        $new_form_id = $new_form->get_id();

        $fields = Ninja_Forms()->form( $form_id )->get_fields();

        foreach( $fields as $field ){

            $field_settings = $field->get_settings();

            $field_settings[ 'parent_id' ] = $new_form_id;

            $new_field = Ninja_Forms()->form( $new_form_id )->field()->get();
            $new_field->update_settings( $field_settings )->save();
        }

        $actions = Ninja_Forms()->form( $form_id )->get_actions();

        foreach( $actions as $action ){

            $action_settings = $action->get_settings();

            $new_action = Ninja_Forms()->form( $new_form_id )->action()->get();
            $new_action->update_settings( $action_settings )->save();
        }

        return $new_form_id;

    }


/** Function remove_maintenance_mode() called by wp_ajax hooks: {'nf_remove_maintenance_mode'} **/
/** No params detected :-/ **/


/** Function disconnect() called by wp_ajax hooks: {'nf_oauth_disconnect'} **/
/** Parameters found in function disconnect(): {"request": ["nonce"]} **/
function disconnect() {

    // Does the current user have admin privileges
    if (!current_user_can('manage_options')) {
      return;
    }

    if( ! wp_verify_nonce( $_REQUEST['nonce'], 'nf-oauth-disconnect' ) ) return;

    do_action( 'ninja_forms_oauth_disconnect' );

    $url = trailingslashit( $this->base_url ) . 'disconnect';
    $args = [
      'blocking' => false,
      'method' => 'DELETE',
      'body' => [
        'client_id' => get_option( 'ninja_forms_oauth_client_id' ),
        'client_secret' => get_option( 'ninja_forms_oauth_client_secret' )
      ]
    ];
    $response = wp_remote_request( $url, $args );

    delete_option( 'ninja_forms_oauth_client_id' );
    delete_option( 'ninja_forms_oauth_client_secret' );
    wp_die( 1 );
  }


/** Function get_forms() called by wp_ajax hooks: {'nf_get_forms'} **/
/** No params detected :-/ **/


/** Function resume() called by wp_ajax hooks: {'nopriv_nf_ajax_resume', 'nf_ajax_resume'} **/
/** Parameters found in function resume(): {"post": ["nf_resume"]} **/
function resume()
    {
        $this->_form_data = Ninja_Forms()->session()->get( 'nf_processing_form_data' );
        $this->_form_cache = Ninja_Forms()->session()->get( 'nf_processing_form_cache' );
        $this->_data = Ninja_Forms()->session()->get( 'nf_processing_data' );
        $this->_data[ 'resume' ] = WPN_Helper::sanitize_text_field($_POST[ 'nf_resume' ]);

        $this->_form_id = $this->_data[ 'form_id' ];

        unset( $this->_data[ 'halt' ] );
        unset( $this->_data[ 'actions' ][ 'redirect' ] );

        $this->process();
    }


/** Function hide_columns() called by wp_ajax hooks: {'nf_hide_columns'} **/
/** Parameters found in function hide_columns(): {"request": ["form_id"], "post": ["hidden"]} **/
function hide_columns() {
        // Grab our current user.
        $user = wp_get_current_user();
        // Grab our form id.
        $form_id = absint( $_REQUEST['form_id'] );
        $hidden = isset( $_POST['hidden'] ) ? explode( ',', esc_html( $_POST['hidden'] ) ) : array();
        $hidden = array_filter( $hidden );
        $hidden = array_map( function($field) {
            if( is_numeric($field) ) {
                $field = absint($field);
            }
            return $field;
        }, $hidden );
        update_user_option( $user->ID, 'manageedit-nf_subcolumnshidden-form-' . $form_id, $hidden, true );
        die();
    }


/** Function maybe_delete_field() called by wp_ajax hooks: {'nf_maybe_delete_field'} **/
/** Parameters found in function maybe_delete_field(): {"request": ["security", "fieldID", "fieldKey"]} **/
function maybe_delete_field() {

		// Does the current user have admin privileges
		if (!current_user_can(apply_filters('ninja_forms_admin_all_forms_capabilities', 'manage_options'))) {
			$this->_data['errors'] = esc_html__('Access denied. You must have admin privileges to perform this action.', 'ninja-forms');
			$this->_respond();
		}

		// If we don't have a nonce...
        // OR if the nonce is invalid...
        if (!isset($_REQUEST['security']) || !wp_verify_nonce($_REQUEST['security'], 'ninja_forms_builder_nonce')) {
            // Kick the request out now.
            $this->_data['errors'] = esc_html__('Request forbidden.', 'ninja-forms');
            $this->_respond();
        }

		if (!isset($_REQUEST['fieldID']) || empty($_REQUEST['fieldID'])) {
			$this->_respond();
		}
		$field_id = absint($_REQUEST[ 'fieldID' ]);
//		$field_key = $_REQUEST[ 'fieldKey' ];

		global $wpdb;
		// query for checking postmeta for submission data for field
		$sql = $wpdb->prepare( "SELECT meta_value FROM `" . $wpdb->prefix . "postmeta` 
			WHERE meta_key = '_field_%d' LIMIT 1", $field_id );
		$result = $wpdb->get_results( $sql, 'ARRAY_N' );

		$has_data = false;

		// if there are results, has_data is true
		if( 0 < count( $result ) ) {
			$has_data = true;
		}
		$this->_data[ 'field_has_data' ] = $has_data;

		$this->_respond();
	}


/** Function delete_all_data() called by wp_ajax hooks: {'nf_delete_all_data'} **/
/** Parameters found in function delete_all_data(): {"request": ["security"], "post": ["form", "last_form"]} **/
function delete_all_data()
	{
		// Does the current user have admin privileges
		if (!current_user_can(apply_filters('ninja_forms_admin_all_forms_capabilities', 'manage_options'))) {
			$this->_data['errors'] = esc_html__('Access denied. You must have admin privileges to perform this action.', 'ninja-forms');
			$this->_respond();
		}

		// If we don't have a nonce...
        // OR if the nonce is invalid...
        if (!isset($_REQUEST['security']) || !wp_verify_nonce($_REQUEST['security'], 'ninja_forms_settings_nonce')) {
            // Kick the request out now.
            $this->_data['errors'] = esc_html__('Request forbidden.', 'ninja-forms');
            $this->_respond();
        }

		check_ajax_referer( 'ninja_forms_settings_nonce', 'security' );

		global $wpdb;
		$total_subs_deleted = 0;
		$post_result = 0;
		$max_cnt = 500;

		if (!isset($_POST['form']) || empty($_POST['form'])) {
			$this->_respond();
		}

		$form_id = absint($_POST[ 'form' ]);
		// SQL for getting 250 subs at a time
		$sub_sql = "SELECT id FROM `" . $wpdb->prefix . "posts` AS p
			LEFT JOIN `" . $wpdb->prefix . "postmeta` AS m ON p.id = m.post_id
			WHERE p.post_type = 'nf_sub' AND m.meta_key = '_form_id'
			AND m.meta_value = %s LIMIT " . $max_cnt;

		while ($post_result <= $max_cnt ) {
			$subs = $wpdb->get_col( $wpdb->prepare( $sub_sql, $form_id ),0 );
			// if we are out of subs, then stop
			if( 0 === count( $subs ) ) break;
			// otherwise, let's delete the postmeta as well
			$delete_meta_query = "DELETE FROM `" . $wpdb->prefix . "postmeta` WHERE post_id IN ( [IN] )";
			$delete_meta_query = $this->prepare_in( $delete_meta_query, $subs );
			$meta_result       = $wpdb->query( $delete_meta_query );
			if ( $meta_result > 0 ) {
				// now we actually delete the posts(nf_sub)
				$delete_post_query = "DELETE FROM `" . $wpdb->prefix . "posts` WHERE id IN ( [IN] )";
				$delete_post_query = $this->prepare_in( $delete_post_query, $subs );
				$post_result       = $wpdb->query( $delete_post_query );
				$total_subs_deleted = $total_subs_deleted + $post_result;

			}
		}

		$this->_data[ 'form_id' ] = $form_id;
		$this->_data[ 'delete_count' ] = $total_subs_deleted;
		$this->_data[ 'success' ] = true;

		if ( isset( $_POST['last_form'] ) && 1 == absint( $_POST['last_form'] ) ) {
			// If we are on the last form, then deactivate and nuke db tables.
			$migrations = new NF_Database_Migrations();

			$nuke_multisite = $this->should_nuke_multisite();

			$migrations->nuke( TRUE, TRUE, $nuke_multisite );
			$migrations->nuke_settings( TRUE, TRUE, $nuke_multisite );
			$migrations->nuke_deprecated( TRUE, TRUE, $nuke_multisite );

			deactivate_plugins( 'ninja-forms/ninja-forms.php' );
			$this->_data['plugin_url'] = admin_url( 'plugins.php' );
		}

		$this->_respond();
	}


/** Function submit() called by wp_ajax hooks: {'nopriv_nf_ajax_submit', 'nf_ajax_submit'} **/
/** Parameters found in function submit(): {"request": ["nonce_ts"], "server": ["HTTP_REFERER"]} **/
function submit()
    {
    	$nonce_name = 'ninja_forms_display_nonce';
    	/**
	     * We've got to get the 'nonce_ts' to append to the nonce name to get
	     * the unique nonce we created
	     * */
    	if( isset( $_REQUEST[ 'nonce_ts' ] ) && 0 < strlen( $_REQUEST[ 'nonce_ts' ] ) ) {
    		$nonce_name = $nonce_name . "_" . $_REQUEST[ 'nonce_ts' ];
	    }
        $check_ajax_referer = check_ajax_referer( $nonce_name, 'security', $die = false );
        if(!$check_ajax_referer){
            /**
             * "Just in Time Nonce".
             * If the nonce fails, then send back a new nonce for the form to resubmit.
             * This supports the edge-case of 11:59:59 form submissions, while avoiding the form load nonce request.
             */

            $current_time_stamp = time();
            $new_nonce_name = 'ninja_forms_display_nonce_' . $current_time_stamp;
            $this->_errors['nonce'] = array(
                'new_nonce' => wp_create_nonce( $new_nonce_name ),
                'nonce_ts' => $current_time_stamp
            );
            $this->_respond();
        }

        register_shutdown_function( array( $this, 'shutdown' ) );

        $this->form_data_check();

        $this->_form_id = $this->_form_data['id'];

        /* Render Instance Fix */
        if(strpos($this->_form_id, '_')){
            $this->_form_instance_id = $this->_form_id;
            list($this->_form_id, $this->_instance_id) = explode('_', $this->_form_id);
            $updated_fields = array();
            foreach($this->_form_data['fields'] as $field_id => $field ){
                list($field_id) = explode('_', $field_id);
                list($field['id']) = explode('_', $field['id']);
                $updated_fields[$field_id] = $field;
            }
            $this->_form_data['fields'] = $updated_fields;
        }
        /* END Render Instance Fix */

        // If we don't have a numeric form ID...
        if ( ! is_numeric( $this->_form_id ) ) {
            // Kick the request out without processing.
            $this->_errors[] = esc_html__( 'Form does not exist.', 'ninja-forms' );
            $this->_respond();
        }

        // Check to see if our form is maintenance mode.
        $is_maintenance = WPN_Helper::form_in_maintenance( $this->_form_id );

        /*
         * If our form is in maintenance mode then, stop processing and throw an error with a link
         * back to the form.
         */
        if ( $is_maintenance ) {
            $message = sprintf(
                esc_html__( 'This form is currently undergoing maintenance. Please %sclick here%s to reload the form and try again.', 'ninja-forms' )
                ,'<a href="' . esc_html($_SERVER[ 'HTTP_REFERER' ]) . '">', '</a>'
            );
            $this->_errors[ 'form' ][] = apply_filters( 'nf_maintenance_message', $message  ) ;
            $this->_respond();
        }

        if( $this->is_preview() ) {

            $this->_form_cache = get_user_option( 'nf_form_preview_' . $this->_form_id );

            if( ! $this->_form_cache ){
                $this->_errors[ 'preview' ] = esc_html__( 'Preview does not exist.', 'ninja-forms' );
                $this->_respond();
            }
        } else {
            if( WPN_Helper::use_cache() ) {
                $this->_form_cache = WPN_Helper::get_nf_cache( $this->_form_id );
            }

        }

        // Add Field Keys to _form_data
        if(! $this->is_preview()){

            // Make sure we don't have any field ID mismatches.
            foreach( $this->_form_data[ 'fields' ] as $id => $settings ){
                if( $id != $settings[ 'id' ] ){
                    $this->_errors[ 'fields' ][ $id ] = esc_html__( 'The submitted data is invalid.', 'ninja-forms' );
                    $this->_respond();
                }
            }

            $form_fields = Ninja_Forms()->form($this->_form_id)->get_fields();
            foreach ($form_fields as $id => $field) {
                $this->_form_data['fields'][$id]['key'] = $field->get_setting('key');
            }
        }

        // TODO: Update Conditional Logic to preserve field ID => [ Settings, ID ] structure.
        $this->_form_data = apply_filters( 'ninja_forms_submit_data', $this->_form_data );

        $this->process();
    }


/** Function DashboardServices() called by wp_ajax hooks: {'nf_services'} **/
/** No function found :-/ **/


/** Function connect() called by wp_ajax hooks: {'nf_oauth_connect'} **/
/** Parameters found in function connect(): {"request": ["nonce"], "get": ["client_id", "redirect"]} **/
function connect() {
    // Does the current user have admin privileges
    if (!current_user_can('manage_options')) {
      return;
    }

    if( ! wp_verify_nonce( $_REQUEST['nonce'], 'nf-oauth-connect' ) ) return;

    if( ! isset( $_GET[ 'client_id' ] ) ) return;

    $client_id = sanitize_text_field( $_GET[ 'client_id' ] );
    update_option( 'ninja_forms_oauth_client_id', $client_id );

    if( isset( $_GET[ 'redirect' ] ) ){
      $redirect = sanitize_text_field( $_GET[ 'redirect' ] );
      $redirect = add_query_arg( 'client_id', $client_id, $redirect );
      wp_redirect( $redirect );
      exit;
    }

    wp_safe_redirect( admin_url( 'admin.php?page=ninja-forms#services' ) );
    exit;
  }


/** Function <pre>() called by wp_ajax hooks: {'nf_services_install'} **/
/** No function found :-/ **/


/** Function get_new_form_templates() called by wp_ajax hooks: {'nf_get_new_form_templates'} **/
/** No params detected :-/ **/


/** Function handle() called by wp_ajax hooks: {'nf_onboarding_complete', 'nf_onboarding_dismiss', 'nf_onboarding_next', 'nf_onboarding_start'} **/
/** No params detected :-/ **/


