<?php
/***
*
*Found actions: 6
*Found functions:6
*Extracted functions:5
*Total parameter names extracted: 2
*Overview: {'nonce': {'templately_pack_$action'}, 'check_conditions': {'templately_check_conditions'}, 'get_conditions': {'templately_conditions'}, 'save_conditions': {'templately_save_conditions'}, 'update_gutenberg_hide_buttons': {'update_gutenberg_hide_buttons'}, 'create_template': {'templately_create_template'}}
*
***/

/** Function nonce() called by wp_ajax hooks: {'templately_pack_$action'} **/
/** No function found :-/ **/


/** Function check_conditions() called by wp_ajax hooks: {'templately_check_conditions'} **/
/** No params detected :-/ **/


/** Function get_conditions() called by wp_ajax hooks: {'templately_conditions'} **/
/** No params detected :-/ **/


/** Function save_conditions() called by wp_ajax hooks: {'templately_save_conditions'} **/
/** No params detected :-/ **/


/** Function update_gutenberg_hide_buttons() called by wp_ajax hooks: {'update_gutenberg_hide_buttons'} **/
/** Parameters found in function update_gutenberg_hide_buttons(): {"get": ["hide_buttons"]} **/
function update_gutenberg_hide_buttons() {
        // Check nonce for security
        check_ajax_referer( 'templately_nonce', 'nonce' );

        // The nonce alone is not authorization: `templately_nonce` is handed to
        // every user who loads a Templately-enqueued screen, so without this any
        // logged-in user could write the option. It toggles buttons in the block
        // editor, so the people who use that editor are exactly the right gate.
        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_send_json_error( [ 'message' => __( 'Insufficient permissions', 'templately' ) ], 403 );
        }

        // Get the new value from the AJAX request
        $hide_buttons = isset($_GET['hide_buttons']) ? sanitize_text_field($_GET['hide_buttons']) : '';

        // Update the option
        update_option('templately-gutenberg-hide-buttons', $hide_buttons);

        $hide_buttons = get_option('templately-gutenberg-hide-buttons', 'no');
        $hide_buttons = $hide_buttons === 'yes' ? 'yes' : 'no';
        // Send a response back to the JavaScript
        wp_send_json_success([
            'hide_buttons' => $hide_buttons,
        ]);
    }


/** Function create_template() called by wp_ajax hooks: {'templately_create_template'} **/
/** Parameters found in function create_template(): {"post": ["template_type", "meta", "title"]} **/
function create_template() {
		check_admin_referer( 'templately_create_template' );

		// Check user capability
		if (!current_user_can('delete_posts')) {
			wp_send_json_error(['message' => 'Insufficient permissions']);
			wp_die();
		}

		$_type = sanitize_text_field( wp_unslash( $_POST['template_type'] ) );

		$_meta = [];

		if ( isset( $_POST['meta'] ) && is_array( $_POST['meta'] ) ) {
			$_meta = array_map( 'sanitize_text_field', wp_unslash( $_POST['meta'] ) );
		}

		$_post_data = [
			'post_type'  => 'templately_library',
			'post_title' => sanitize_text_field( wp_unslash( $_POST['title'] ) )
		];

		$template = self::$templates_manager->create( $_type, $_post_data, $_meta );

		if ( is_wp_error( $template ) ) {
			wp_die( $template );
		}

		wp_redirect( $template->get_edit_url() );
		die;
	}


