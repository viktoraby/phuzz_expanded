<?php
/***
*
*Found actions: 20
*Found functions:20
*Extracted functions:20
*Total parameter names extracted: 2
*Overview: {'userfeedback_validate_settings_blurb': {'userfeedback_validate_settings_blurb'}, 'userfeedback_ajax_vue_remove_notice': {'userfeedback_ajax_vue_remove_notice'}, 'userfeedback_install_plugin': {'userfeedback_install_plugin'}, 'userfeedback_ajax_dismiss_notice': {'userfeedback_ajax_dismiss_notice'}, 'userfeedback_ajax_get_addons': {'userfeedback_get_addons'}, 'userfeedback_vue_onboarding_step': {'userfeedback_vue_onboarding_step'}, 'userfeedback_onboarding_drop_opt_in': {'userfeedback_vue_onboarding_drip_opt_in'}, 'userfeedback_mark_admin_menu_tooltip_hidden': {'userfeedback_hide_admin_menu_tooltip'}, 'userfeedback_ajax_deactivate_addon': {'userfeedback_deactivate_addon'}, 'generate_connect_url': {'userfeedback_connect_url'}, 'userfeedback_ajax_activate_addon': {'userfeedback_activate_addon'}, 'userfeedback_ajax_install_addon': {'userfeedback_install_addon'}, 'userfeedback_activate_plugin': {'userfeedback_activate_plugin'}, 'review_dismiss': {'userfeedback_review_dismiss'}, 'userfeedback_get_plugins': {'userfeedback_get_plugins'}, 'process': {'nopriv_userfeedback_connect_process'}, 'send_test_email': {'userfeedback_send_test_summary_email'}, 'userfeedback_complete_onboarding': {'userfeedback_vue_onboarding_complete'}, 'userfeedback_dismiss_settings_blurb': {'userfeedback_dismiss_settings_blurb'}, 'userfeedback_ajax_vue_remove_wp_notice': {'userfeedback_ajax_vue_remove_wp_notice'}}
*
***/

/** Function userfeedback_validate_settings_blurb() called by wp_ajax hooks: {'userfeedback_validate_settings_blurb'} **/
/** No params detected :-/ **/


/** Function userfeedback_ajax_vue_remove_notice() called by wp_ajax hooks: {'userfeedback_ajax_vue_remove_notice'} **/
/** No params detected :-/ **/


/** Function userfeedback_install_plugin() called by wp_ajax hooks: {'userfeedback_install_plugin'} **/
/** No params detected :-/ **/


/** Function userfeedback_ajax_dismiss_notice() called by wp_ajax hooks: {'userfeedback_ajax_dismiss_notice'} **/
/** No params detected :-/ **/


/** Function userfeedback_ajax_get_addons() called by wp_ajax hooks: {'userfeedback_get_addons'} **/
/** No params detected :-/ **/


/** Function userfeedback_vue_onboarding_step() called by wp_ajax hooks: {'userfeedback_vue_onboarding_step'} **/
/** Parameters found in function userfeedback_vue_onboarding_step(): {"post": ["step"]} **/
function userfeedback_vue_onboarding_step() {
	check_ajax_referer( 'uf-admin-nonce', 'nonce' );
	if ( ! current_user_can( 'userfeedback_save_settings' ) ) {
		return;
	}
	// update onboarding step
	$step = isset( $_POST['step'] ) ? sanitize_text_field( wp_unslash( $_POST['step'] ) ) : '';
	userfeedback_update_option( 'userfeedback_onboarding_step', $step );
	wp_die();
}


/** Function userfeedback_onboarding_drop_opt_in() called by wp_ajax hooks: {'userfeedback_vue_onboarding_drip_opt_in'} **/
/** No params detected :-/ **/


/** Function userfeedback_mark_admin_menu_tooltip_hidden() called by wp_ajax hooks: {'userfeedback_hide_admin_menu_tooltip'} **/
/** No params detected :-/ **/


/** Function userfeedback_ajax_deactivate_addon() called by wp_ajax hooks: {'userfeedback_deactivate_addon'} **/
/** No params detected :-/ **/


/** Function generate_connect_url() called by wp_ajax hooks: {'userfeedback_connect_url'} **/
/** No params detected :-/ **/


/** Function userfeedback_ajax_activate_addon() called by wp_ajax hooks: {'userfeedback_activate_addon'} **/
/** No params detected :-/ **/


/** Function userfeedback_ajax_install_addon() called by wp_ajax hooks: {'userfeedback_install_addon'} **/
/** No params detected :-/ **/


/** Function userfeedback_activate_plugin() called by wp_ajax hooks: {'userfeedback_activate_plugin'} **/
/** No params detected :-/ **/


/** Function review_dismiss() called by wp_ajax hooks: {'userfeedback_review_dismiss'} **/
/** Parameters found in function review_dismiss(): {"post": ["review_later"]} **/
function review_dismiss() {
		check_ajax_referer( 'userfeedback_review_nonce', 'nonce' );

		$review = get_option( 'userfeedback_review', array() );

		if ( isset( $_POST['review_later'] ) && "true" === $_POST['review_later'] ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Compared as string equality only.
			$review['time']      = time() + ( DAY_IN_SECONDS * 7 ); // Add 7 days.
			$review['dismissed'] = false;
		} else {
			$review['time']      = time();
			$review['dismissed'] = true;
		}

		update_option( 'userfeedback_review', $review );

		if ( is_super_admin() && is_multisite() ) {
			$site_list = get_sites();
			foreach ( (array) $site_list as $site ) {
				switch_to_blog( $site->blog_id );

				update_option( 'userfeedback_review', $review );

				restore_current_blog();
			}
		}

		wp_die( 'success' );
	}


/** Function userfeedback_get_plugins() called by wp_ajax hooks: {'userfeedback_get_plugins'} **/
/** No params detected :-/ **/


/** Function process() called by wp_ajax hooks: {'nopriv_userfeedback_connect_process'} **/
/** No params detected :-/ **/


/** Function send_test_email() called by wp_ajax hooks: {'userfeedback_send_test_summary_email'} **/
/** No params detected :-/ **/


/** Function userfeedback_complete_onboarding() called by wp_ajax hooks: {'userfeedback_vue_onboarding_complete'} **/
/** No params detected :-/ **/


/** Function userfeedback_dismiss_settings_blurb() called by wp_ajax hooks: {'userfeedback_dismiss_settings_blurb'} **/
/** No params detected :-/ **/


/** Function userfeedback_ajax_vue_remove_wp_notice() called by wp_ajax hooks: {'userfeedback_ajax_vue_remove_wp_notice'} **/
/** No params detected :-/ **/


