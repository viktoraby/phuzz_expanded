<?php
/***
*
*Found actions: 2
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 2
*Overview: {'dismiss_notice': {'drgf_dismiss_notice'}, 'ajax_run_audit': {'drgf_run_audit'}}
*
***/

/** Function dismiss_notice() called by wp_ajax hooks: {'drgf_dismiss_notice'} **/
/** Parameters found in function dismiss_notice(): {"post": ["nonce", "type"]} **/
function dismiss_notice() {
		// Verify nonce.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'drgf_dismiss_notice' ) ) {
			wp_die( esc_html__( 'Security check failed.', 'disable-remove-google-fonts' ) );
		}

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to perform this action.', 'disable-remove-google-fonts' ) );
		}

		if ( isset( $_POST['type'] ) ) {
			// Pick up the notice "type" - passed via jQuery (the "data-notice" attribute on the notice).
			$type = sanitize_text_field( wp_unslash( $_POST['type'] ) );
			// Store it in the options table.
			update_option( 'dismissed-' . $type, true );
		}
	}


/** Function ajax_run_audit() called by wp_ajax hooks: {'drgf_run_audit'} **/
/** Parameters found in function ajax_run_audit(): {"post": ["scan_url", "html"]} **/
function ajax_run_audit() {
		check_ajax_referer( 'drgf_font_audit', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'disable-remove-google-fonts' ) ) );
		}

		$scan_url = isset( $_POST['scan_url'] ) ? drgf_validate_scan_url( wp_unslash( $_POST['scan_url'] ) ) : '';
		$html     = isset( $_POST['html'] ) ? wp_unslash( $_POST['html'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		$result = drgf_run_font_audit( $scan_url, $html );

		wp_send_json_success( $result );
	}


