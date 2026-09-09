<?php
/***
*
*Found actions: 4
*Found functions:4
*Extracted functions:4
*Total parameter names extracted: 4
*Overview: {'edit_clarity_project_id': {'edit_clarity_project_id'}, 'brandagent_wordpress_connect_ajax': {'brandagent_wordpress_connect'}, 'edit_agent_enabled_status': {'edit_agent_enabled_status'}, 'clarity_dismiss_update_notice': {'clarity_dismiss_update_notice'}}
*
***/

/** Function edit_clarity_project_id() called by wp_ajax hooks: {'edit_clarity_project_id'} **/
/** Parameters found in function edit_clarity_project_id(): {"post": ["new_value", "nonce"]} **/
function edit_clarity_project_id()
{
    $new_value = $_POST['new_value'];
    $nonce = $_POST['nonce'];
    if (!wp_verify_nonce($nonce, "wp_ajax_edit_clarity_project_id")) {
        die(json_encode(
                array(
                    'success' => false,
                    'message' => 'Invalid nonce.',
                )
            ));
    }
    // only admins are allowed to edit the Clarity project id
    if (!current_user_can('manage_options')) {
        die(json_encode(
                array(
                    'success' => false,
                    'message' => 'User must be WordPress admin.'
                )
            ));
    } else {
        update_option(
            'clarity_project_id', /* option */
            $new_value /* value */
            /* autoload */
        );
        die(json_encode(
                array(
                    'success' => true,
                    'message' => 'Clarity project updated successfully.'
                )
            ));
    }
}


/** Function brandagent_wordpress_connect_ajax() called by wp_ajax hooks: {'brandagent_wordpress_connect'} **/
/** Parameters found in function brandagent_wordpress_connect_ajax(): {"post": ["nonce"]} **/
function brandagent_wordpress_connect_ajax() {
	$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'wp_ajax_edit_clarity_project_id' ) ) {
		wp_send_json( array( 'success' => false, 'error' => 'Invalid nonce.' ) );
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json( array( 'success' => false, 'error' => 'User must be a WordPress admin.' ) );
	}

	// Checked here as well as inside brandagent_wordpress_connect() so a WooCommerce store never
	// records plain-WordPress connect bookkeeping it would then retry from admin_init.
	if ( clarity_is_woocommerce_active_for_current_blog() ) {
		wp_send_json( array( 'success' => false, 'error' => 'WooCommerce site must use the WooCommerce connect flow.' ) );
	}

	// Resolve credential provenance before writing the opt-in marker. Legacy WooCommerce credentials
	// predate brandagent_hmac_platform; setting opt-in first would otherwise misclassify them as WordPress.
	if ( 'woocommerce' === brandagent_get_hmac_platform() ) {
		brandagent_wordpress_clear_connect_retry_state();
		wp_send_json( array(
			'success'    => false,
			'error'      => 'Store is registered as WooCommerce and must be offboarded before connecting as WordPress.',
			'error_code' => 'platform_mismatch',
		) );
	}

	// Record the opt-in and start a fresh attempt budget for the server-side resume fallback.
	update_option( 'brandagent_wp_connect_optin', 1 );
	delete_option( 'brandagent_wp_connect_attempts' );
	delete_transient( 'brandagent_wp_connect_throttle' );

	$result = brandagent_wordpress_connect();
	wp_send_json( $result );
}


/** Function edit_agent_enabled_status() called by wp_ajax hooks: {'edit_agent_enabled_status'} **/
/** Parameters found in function edit_agent_enabled_status(): {"post": ["new_value", "nonce"]} **/
function edit_agent_enabled_status()
{
    $new_value = $_POST['new_value'];
    $nonce = $_POST['nonce'];
    if (!wp_verify_nonce($nonce, "wp_ajax_edit_clarity_project_id")) {
        die(json_encode(
                array(
                    'success' => false,
                    'message' => 'Invalid nonce.',
                )
            ));
    }
    // only admins are allowed to edit the Clarity project id
    if (!current_user_can('manage_options')) {
        die(json_encode(
                array(
                    'success' => false,
                    'message' => 'User must be WordPress admin.'
                )
            ));
    } else {
        update_option(
            'BAOauthSuccess', /* option */
            $new_value /* value */
            /* autoload */
        );
        die(json_encode(
                array(
                    'success' => true,
                    'message' => 'Agent enabled status updated successfully.'
                )
            ));
    }
}


/** Function clarity_dismiss_update_notice() called by wp_ajax hooks: {'clarity_dismiss_update_notice'} **/
/** Parameters found in function clarity_dismiss_update_notice(): {"post": ["nonce"]} **/
function clarity_dismiss_update_notice()
{
    if (! current_user_can('update_plugins') || ! isset($_POST['nonce']) || ! wp_verify_nonce($_POST['nonce'], 'clarity_dismiss_update_notice')) {
        wp_die('', '', array('response' => 403));
    }

    $plugin_slug = 'microsoft-clarity/clarity.php';
    $updates = get_site_transient('update_plugins');
    $new_version = isset($updates->response[$plugin_slug]->new_version) ? $updates->response[$plugin_slug]->new_version : '';
    if ($new_version !== '') {
        update_option('clarity_dismissed_update_version', $new_version);
    }
    wp_die();
}


