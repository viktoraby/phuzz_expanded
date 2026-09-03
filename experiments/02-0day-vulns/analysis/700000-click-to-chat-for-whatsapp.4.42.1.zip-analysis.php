<?php
/***
*
*Found actions: 2
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 2
*Overview: {'ht_ctc_deactivate_feedback_details': {'ht_ctc_deactivate_feedback_details'}, 'dismiss_notices': {'ht_ctc_admin_dismiss_notices'}}
*
***/

/** Function ht_ctc_deactivate_feedback_details() called by wp_ajax hooks: {'ht_ctc_deactivate_feedback_details'} **/
/** Parameters found in function ht_ctc_deactivate_feedback_details(): {"post": ["userFeedback", "userEmail"], "server": ["SERVER_SOFTWARE"]} **/
function ht_ctc_deactivate_feedback_details() {

			check_ajax_referer( 'ht_ctc_admin_deactivate_feedback_nonce', 'nonce' );

			// Defense-in-depth: also require manage_options at the handler level
			// (the only place this AJAX endpoint is hooked is inside a
			// manage_options gate, but if that gate ever shifts we still want a
			// hard capability check here).
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( array( 'message' => 'Insufficient permissions.' ), 403 );
			}

			// Sanitize POST values with field-appropriate sanitizers AFTER wp_unslash.
			// (Previously the map_deep used esc_attr — an output escaper, not an input
			// sanitizer — and did not unslash.)
			$user_feedback = '';
			if ( isset( $_POST['userFeedback'] ) ) {
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- unslash + sanitize applied below.
				$raw_feedback = wp_unslash( $_POST['userFeedback'] );
				// sanitize_textarea_field exists since WP 4.7; we've hit
				// fatal errors on older sites without this guard.
				$user_feedback = function_exists( 'sanitize_textarea_field' ) ? sanitize_textarea_field( $raw_feedback ) : sanitize_text_field( $raw_feedback );
			}

			$user_email = '';
			if ( isset( $_POST['userEmail'] ) ) {
				// sanitize_email validates the email format and strips invalid characters.
				$user_email = sanitize_email( wp_unslash( $_POST['userEmail'] ) );
			}

			// Plugin and site details.
			$ctc_version = defined( 'HT_CTC_VERSION' ) ? HT_CTC_VERSION : '';
			$site_url    = function_exists( 'get_site_url' ) ? get_site_url() : '';
			$wp_version  = function_exists( 'get_bloginfo' ) ? get_bloginfo( 'version' ) : '';
			$php_version = function_exists( 'phpversion' ) ? phpversion() : '';
			$wp_language = function_exists( 'get_bloginfo' ) ? get_bloginfo( 'language' ) : '';

			// Theme name.
			$wp_theme_name = '';
			if ( function_exists( 'wp_get_theme' ) ) {
				$wp_theme = wp_get_theme();
				if ( is_object( $wp_theme ) && method_exists( $wp_theme, 'get' ) ) {
					$theme_name    = $wp_theme->get( 'Name' );
					$wp_theme_name = ! empty( $theme_name ) ? sanitize_text_field( $theme_name ) : '';
				}
			}

			// Active plugins list.
			$active_plugins = array();
			if ( function_exists( 'get_option' ) ) {
				$active_plugins_option = get_option( 'active_plugins', array() );
				if ( is_array( $active_plugins_option ) ) {
					$active_plugins = array_map( 'sanitize_text_field', $active_plugins_option );
				}
			}

			// TODO: Consider collecting additional server details if needed.
			$server_software = isset( $_SERVER['SERVER_SOFTWARE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ) : '';

			$feedback_data = array(
				'plugin_name'     => 'Click to Chat for WhatsApp',
				'ctc_version'     => $ctc_version,
				'user_feedback'   => $user_feedback,
				'user_email'      => $user_email,
				'site_url'        => $site_url,
				'wp_version'      => $wp_version,
				'php_version'     => $php_version,
				'wp_language'     => $wp_language,
				'wp_theme'        => $wp_theme_name,
				'active_plugins'  => $active_plugins,
				'server_software' => $server_software,
			);

			// Production endpoint.
			$url = 'https://holithemes.com/wp-json/ht-code/v1/ctc-feedback';

			// Fire-and-forget HTTP request.
			$request_args = array(
				'timeout'  => 5,
				'blocking' => false, // Don't wait for response.
				'headers'  => array(
					'Content-Type' => 'application/json',
					'User-Agent'   => 'HT-CTC-Pro-Plugin/' . HT_CTC_VERSION,
				),
				'body'     => wp_json_encode( array( 'feedback_data' => $feedback_data ) ),
			);

			wp_remote_post( $url, $request_args );

			// Respond to the AJAX request immediately.
			wp_send_json_success( 'Feedback sent (no wait)' );
		}


/** Function dismiss_notices() called by wp_ajax hooks: {'ht_ctc_admin_dismiss_notices'} **/
/** No params detected :-/ **/


