<?php
/***
*
*Found actions: 4
*Found functions:4
*Extracted functions:4
*Total parameter names extracted: 1
*Overview: {'handle_enable_one_click_login': {'wtlwp_enable_one_click_login'}, 'mailer_notice_clickable': {'tlwp_mailer_notice_clickable'}, 'dismiss_tlwp_mailer_promotion_notice': {'tlwp_dismiss_mailer_promotion_notice'}, 'tlwp_dismiss_promo_custom_notice': {'tlwp_dismiss_promo_custom_notice'}}
*
***/

/** Function handle_enable_one_click_login() called by wp_ajax hooks: {'wtlwp_enable_one_click_login'} **/
/** Parameters found in function handle_enable_one_click_login(): {"post": ["user_id", "enable"]} **/
function handle_enable_one_click_login() {

            check_ajax_referer( 'wtlwp_nonce', 'nonce' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error(
					array( 'message' => esc_html__( 'Permission denied.', 'temporary-login-without-password' ) )
				);
			}

			$user_id = isset( $_POST['user_id'] ) ? absint( wp_unslash( $_POST['user_id'] ) ) : 0;
			$action  = isset( $_POST['enable'] ) ? sanitize_text_field( wp_unslash( $_POST['enable'] ) ) : '';

			if ( 0 === $user_id || '' === $action ) {
				wp_send_json_error( array( 'message' =>  esc_html__( 'Missing parameters.', 'temporary-login-without-password' ) ) );
			}

			$result = Wp_Temporary_Login_Without_Password_Common::manage_login( $user_id, $action );

			if ( $result ) {
				 delete_transient( 'wtlwp_one_click_user_active' );
                 delete_transient( 'wtlwp_one_click_user_id' );
				wp_send_json_success(
					array( 'message' => esc_html__( 'Action completed successfully.', 'temporary-login-without-password' ) )
				);
					
			}
				
			wp_send_json_error(
				array( 'message' => esc_html__( 'Failed to complete action.', 'temporary-login-without-password' ) )
			);
			
		}


/** Function mailer_notice_clickable() called by wp_ajax hooks: {'tlwp_mailer_notice_clickable'} **/
/** No params detected :-/ **/


/** Function dismiss_tlwp_mailer_promotion_notice() called by wp_ajax hooks: {'tlwp_dismiss_mailer_promotion_notice'} **/
/** No params detected :-/ **/


/** Function tlwp_dismiss_promo_custom_notice() called by wp_ajax hooks: {'tlwp_dismiss_promo_custom_notice'} **/
/** No params detected :-/ **/


