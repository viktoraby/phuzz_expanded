<?php
/***
*
*Found actions: 9
*Found functions:8
*Extracted functions:7
*Total parameter names extracted: 2
*Overview: {'ajax_install_activate_yaymail': {'yaymail_wc_settings_install_activate', 'yaymail_banner_install_activate'}, 'ajax_first_folder_notice': {'fbv_first_folder_notice'}, 'savePostTypeSettings': {'fbv_save_post_type_settings'}, 'ajax_dismiss_column': {'yaymail_wc_settings_dismiss_column'}, 'ajax_dismiss_plugin': {'yaymail_banner_dismiss'}, 'filebird-themify': {'tb_load_editor'}, 'syncWPML': {'fbv_sync_wpml'}, 'fbv_save_review': {'fbv_save_review'}}
*
***/

/** Function ajax_install_activate_yaymail() called by wp_ajax hooks: {'yaymail_wc_settings_install_activate', 'yaymail_banner_install_activate'} **/
/** No params detected :-/ **/


/** Function ajax_first_folder_notice() called by wp_ajax hooks: {'fbv_first_folder_notice'} **/
/** No params detected :-/ **/


/** Function savePostTypeSettings() called by wp_ajax hooks: {'fbv_save_post_type_settings'} **/
/** Parameters found in function savePostTypeSettings(): {"post": ["post_types"]} **/
function savePostTypeSettings() {
		check_ajax_referer( 'fbv_nonce', 'nonce', true );
		
		// Check if user has permission to manage options
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error(
				array( 'mess' => __( 'You do not have permission to perform this action.', 'filebird' ) ),
				403
			);
		}
		
		$fbv_enabled_posttype = '';
		if ( isset( $_POST['post_types'] ) ) {
			$post_types           = Helpers::sanitize_array( $_POST['post_types'] );
			$post_types           = array_filter( $post_types, function( $post_type ) {
				return $post_type === 'post' || $post_type === 'page';
			});
			$fbv_enabled_posttype = implode( ',', $post_types );
		}
		update_option( 'fbv_enabled_posttype', $fbv_enabled_posttype );
		wp_send_json_success(
			array( 'mess' => __( 'Settings saved!', 'filebird' ) )
		);
	}


/** Function ajax_dismiss_column() called by wp_ajax hooks: {'yaymail_wc_settings_dismiss_column'} **/
/** No params detected :-/ **/


/** Function ajax_dismiss_plugin() called by wp_ajax hooks: {'yaymail_banner_dismiss'} **/
/** Parameters found in function ajax_dismiss_plugin(): {"post": ["days"]} **/
function ajax_dismiss_plugin() {
			check_ajax_referer( 'yaymail_banner_install-plugin_yaymail', 'nonce', true );

			$days = isset( $_POST['days'] ) ? (int) sanitize_text_field( $_POST['days'] ) : 30;
			$time = time() + ( $days * 60 * 60 * 24 );

			update_option( 'yaymail_wc_settings_banner_notification', $time );
			wp_send_json_success();
		}


/** Function filebird-themify() called by wp_ajax hooks: {'tb_load_editor'} **/
/** No function found :-/ **/


/** Function syncWPML() called by wp_ajax hooks: {'fbv_sync_wpml'} **/
/** No params detected :-/ **/


/** Function fbv_save_review() called by wp_ajax hooks: {'fbv_save_review'} **/
/** No params detected :-/ **/


