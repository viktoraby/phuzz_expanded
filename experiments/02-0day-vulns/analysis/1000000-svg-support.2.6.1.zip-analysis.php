<?php
/***
*
*Found actions: 2
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 2
*Overview: {'bodhi_svgs_featured_image_inline_toggle': {'bodhi_svgs_featured_image_inline_toggle'}, 'bodhi_svgs_autosave_settings': {'bodhi_svgs_autosave'}}
*
***/

/** Function bodhi_svgs_featured_image_inline_toggle() called by wp_ajax hooks: {'bodhi_svgs_featured_image_inline_toggle'} **/
/** Parameters found in function bodhi_svgs_featured_image_inline_toggle(): {"post": ["nonce", "post_id", "checked"]} **/
function bodhi_svgs_featured_image_inline_toggle() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'svg-support-featured')) {
        wp_send_json_error('Invalid nonce');
    }

    // Validate and sanitize input
    if (!isset($_POST['post_id']) || !isset($_POST['checked'])) {
        wp_send_json_error('Missing parameters');
    }

    $post_id = intval($_POST['post_id']);
    $checked = ($_POST['checked'] === 'true');

    // Verify the current user can edit this specific post
    if (!current_user_can('edit_post', $post_id)) {
        wp_send_json_error('Insufficient permissions');
    }

    // Update the meta safely
    bodhi_svgs_update_featured_image_meta($post_id, $checked);

    wp_send_json_success();
}


/** Function bodhi_svgs_autosave_settings() called by wp_ajax hooks: {'bodhi_svgs_autosave'} **/
/** Parameters found in function bodhi_svgs_autosave_settings(): {"post": ["bodhi_svgs_settings"]} **/
function bodhi_svgs_autosave_settings() {

	check_ajax_referer( 'bodhi_svgs_autosave', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'You can\'t play with this.', 'svg-support' ) ), 403 );
	}

	$raw = isset( $_POST['bodhi_svgs_settings'] ) ? wp_unslash( $_POST['bodhi_svgs_settings'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized below via map_deep + settings sanitize callback.

	if ( ! is_array( $raw ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Invalid settings payload.', 'svg-support' ) ), 400 );
	}

	$clean = bodhi_svgs_settings_sanitize( map_deep( $raw, 'sanitize_text_field' ) );

	update_option( 'bodhi_svgs_settings', $clean );

	wp_send_json_success();

}


