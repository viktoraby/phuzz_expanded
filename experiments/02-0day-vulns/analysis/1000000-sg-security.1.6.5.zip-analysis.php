<?php
/***
*
*Found actions: 3
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 2
*Overview: {'delete_logs_on_admin_page': {'sgs_clear_logs'}, 'hide_notice': {'dismiss_sgs_2fa_notice', 'dismiss_sg_security_notice'}}
*
***/

/** Function delete_logs_on_admin_page() called by wp_ajax hooks: {'sgs_clear_logs'} **/
/** Parameters found in function delete_logs_on_admin_page(): {"post": ["nonce"]} **/
function delete_logs_on_admin_page() {
		// Check nonce.
		if (
			! isset( $_POST['nonce'] ) ||
			! wp_verify_nonce( $_POST['nonce'], 'sgs_clear_logs' )
		) {
			wp_send_json_error( array( 'message' => 'Invalid nonce.' ), 403 );
		}

		// Delete if we are on plugin page and CRON is disabled.
		if ( 1 === Helper_Service::is_cron_disabled() ) {
			// Check if the user is admin.
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error(
					array(
						'message' => esc_html__( 'You don’t have access to this page. Please contact the administrator of this website for further assistance.', 'sg-security' ),
					),
					403
				);
			}

			$this->delete_old_activity_logs();
		}
	}


/** Function hide_notice() called by wp_ajax hooks: {'dismiss_sgs_2fa_notice', 'dismiss_sg_security_notice'} **/
/** Parameters found in function hide_notice(): {"get": ["notice"]} **/
function hide_notice() {
		if ( empty( $_GET['notice'] ) || ! check_ajax_referer( 'sg-security-signup-notice', 'nonce', false ) ) {
			return;
		}

		if ( 'show_signup_notice' !== sanitize_text_field( $_GET['notice'] ) ) {
			return;
		}

		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		update_option( 'sg_security_show_signup_notice', 0 );

		wp_send_json_success();
	}


