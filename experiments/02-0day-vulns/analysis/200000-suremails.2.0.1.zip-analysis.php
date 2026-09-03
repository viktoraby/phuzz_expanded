<?php
/***
*
*Found actions: 4
*Found functions:4
*Extracted functions:4
*Total parameter names extracted: 4
*Overview: {'activate_theme': {'suremails-activate_theme'}, 'send_plugin_deactivate_feedback': {'uds_plugin_deactivate_feedback'}, 'handle_activate_plugin': {'suremails-activate_plugin'}, 'dismiss_notice': {'astra-notice-dismiss'}}
*
***/

/** Function activate_theme() called by wp_ajax hooks: {'suremails-activate_theme'} **/
/** Parameters found in function activate_theme(): {"post": ["slug"]} **/
function activate_theme() {
		// Check ajax referer.
		check_ajax_referer( 'suremails_plugin', '_ajax_nonce' );

		// Ensure this is an ajax request.
		if ( ! wp_doing_ajax() ) {
			wp_send_json_error(
				[
					'success' => false,
					'message' => __( 'Not an ajax request.', 'suremails' ),
				]
			);
		}

		// Check user capabilities – using 'switch_themes' which is standard for theme activation.
		if ( ! current_user_can( 'switch_themes' ) ) {
			wp_send_json_error(
				[
					'success' => false,
					'message' => __( 'You do not have permission to activate themes.', 'suremails' ),
				]
			);
		}

		// Get theme slug from request.
		$theme_stylesheet = isset( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';

		if ( empty( $theme_stylesheet ) ) {
			wp_send_json_error(
				[
					'success' => false,
					'message' => __( 'No theme specified.', 'suremails' ),
				]
			);
		}

		// Activate the theme.
		switch_theme( $theme_stylesheet );

		$theme_slug = pathinfo( $theme_stylesheet, PATHINFO_FILENAME ); // Retrieves the theme slug from the stylesheet.
		if ( class_exists( '\BSF_UTM_Analytics' ) && is_callable( '\BSF_UTM_Analytics::update_referer' ) ) {
			$theme_slug = pathinfo( $theme_stylesheet, PATHINFO_FILENAME ); // Retrieves the theme slug from the stylesheet.
			\BSF_UTM_Analytics::update_referer( 'suremails', $theme_slug );
		}

		// Send success response.
		wp_send_json_success(
			[
				'success' => true,
				'message' => __( 'Theme activated successfully.', 'suremails' ),
			]
		);
	}


/** Function send_plugin_deactivate_feedback() called by wp_ajax hooks: {'uds_plugin_deactivate_feedback'} **/
/** Parameters found in function send_plugin_deactivate_feedback(): {"post": ["reason", "feedback", "referer", "version", "source"]} **/
function send_plugin_deactivate_feedback() {

			$response_data = array( 'message' => __( 'Sorry, you are not allowed to do this operation.' ) );

			/**
			 * Check permission
			 */
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( $response_data );
			}

			/**
			 * Nonce verification
			 */
			if ( ! check_ajax_referer( 'uds_plugin_deactivate_feedback', 'security', false ) ) {
				$response_data = array( 'message' => __( 'Nonce validation failed' ) );
				wp_send_json_error( $response_data );
			}

			$feedback_data = array(
				'reason'      => isset( $_POST['reason'] ) ? sanitize_text_field( wp_unslash( $_POST['reason'] ) ) : '',
				'feedback'    => isset( $_POST['feedback'] ) ? sanitize_text_field( wp_unslash( $_POST['feedback'] ) ) : '',
				'domain_name' => isset( $_POST['referer'] ) ? sanitize_text_field( wp_unslash( $_POST['referer'] ) ) : '',
				'version'     => isset( $_POST['version'] ) ? sanitize_text_field( wp_unslash( $_POST['version'] ) ) : '',
				'plugin'      => isset( $_POST['source'] ) ? sanitize_text_field( wp_unslash( $_POST['source'] ) ) : '',
			);

			$api_args = array(
				'body'    => wp_json_encode( $feedback_data ),
				'headers' => BSF_Analytics_Helper::get_api_headers(),
				'timeout' => 15, //phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout
			);

			$target_url = BSF_Analytics_Helper::get_api_url() . self::$feedback_api_endpoint;

			$response = wp_safe_remote_post( $target_url, $api_args );

			$has_errors = BSF_Analytics_Helper::is_api_error( $response );

			if ( $has_errors['error'] ) {
				wp_send_json_error(
					array(
						'success' => false,
						'message' => $has_errors['error_message'],
					)
				);
			}

			wp_send_json_success();
		}


/** Function handle_activate_plugin() called by wp_ajax hooks: {'suremails-activate_plugin'} **/
/** Parameters found in function handle_activate_plugin(): {"post": ["slug"]} **/
function handle_activate_plugin() {
		// Check ajax referer.
		check_ajax_referer( 'suremails_plugin', '_ajax_nonce' );

		// Check if the request is an ajax request and early return if not.
		if ( ! wp_doing_ajax() ) {
			wp_send_json_error(
				[
					'success' => false,
					'message' => __( 'Not an ajax request.', 'suremails' ),
				],
			);
		}

		// Check user capabilities.
		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error(
				[
					'success' => false,
					'message' => __( 'You do not have permission to activate plugins.', 'suremails' ),
				],
			);
		}

		// Get plugin slug from request.
		$plugin_slug = isset( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';

		if ( empty( $plugin_slug ) ) {
			wp_send_json_error(
				[
					'success' => false,
					'message' => __( 'No plugin specified.', 'suremails' ),
				],
			);
		}

		// Disable redirection to plugin page after activation.
		add_filter( 'wp_redirect', '__return_false' );

		// Activate the plugin.
		$result = activate_plugin( $plugin_slug, '', false, true );

		// Check if activation was successful.
		if ( is_wp_error( $result ) ) {
			wp_send_json_error(
				[
					'success' => false,
					'message' => $result->get_error_message(),
				],
			);
		}

		// Clear plugins cache.
		wp_clean_plugins_cache();
		if ( class_exists( '\BSF_UTM_Analytics' ) && is_callable( '\BSF_UTM_Analytics::update_referer' ) ) {
			$plugin_slug = pathinfo( $plugin_slug, PATHINFO_FILENAME ); // Retrives the plugin slug from the init.
			\BSF_UTM_Analytics::update_referer( 'suremails', $plugin_slug );
		}

		// Send success response.
		wp_send_json_success(
			[
				'success' => true,
				'message' => __( 'Plugin activated successfully.', 'suremails' ),
			]
		);
	}


/** Function dismiss_notice() called by wp_ajax hooks: {'astra-notice-dismiss'} **/
/** Parameters found in function dismiss_notice(): {"post": ["notice_id", "repeat_notice_after"]} **/
function dismiss_notice() {
			check_ajax_referer( 'astra-notices', 'nonce' );

			$notice_id           = ( isset( $_POST['notice_id'] ) ) ? sanitize_key( wp_unslash( $_POST['notice_id'] ) ) : '';
			$repeat_notice_after = ( isset( $_POST['repeat_notice_after'] ) ) ? absint( $_POST['repeat_notice_after'] ) : 0;
			$notice              = $this->get_notice_by_id( $notice_id );
			$capability          = isset( $notice['capability'] ) ? $notice['capability'] : 'manage_options';

			$has_cap = current_user_can( $capability );

			/**
			 * Filters whether the current user passes the capability check for notice dismissal.
			 *
			 * Both the legacy and new filter names are fired for backward compatibility.
			 * Filters can only restrict access (return false), never grant it — if the
			 * underlying current_user_can() check fails, filters cannot override to true.
			 */
			$cap_check = apply_filters( 'astra_notices_user_cap_check', $has_cap );
			$cap_check = apply_filters( 'bsf_admin_notices_user_cap_check', $cap_check );

			if ( ! $has_cap || ! $cap_check ) {
				wp_send_json_error( esc_html__( 'Permission denied.', 'astra-notices' ) );
			}

			$allowed_notices = get_option( 'allowed_astra_notices', array() ); // Get allowed notices.

			// Define restricted user meta keys using the dynamic table prefix.
			global $wpdb;
			$wp_default_meta_keys = array(
				$wpdb->prefix . 'capabilities',
				$wpdb->prefix . 'user_level',
				$wpdb->prefix . 'user-settings',
				'account_status',
				'session_tokens',
			);

			// if $notice_id does not start with astra-notices-id and notice_id is not from the allowed notices, then return.
			if ( 0 !== strpos( $notice_id, 'astra-notices-id-' ) && ( ! in_array( $notice_id, $allowed_notices, true ) ) ) {
				wp_send_json_error( esc_html__( 'Invalid notice ID.', 'astra-notices' ) );
			}

			// Valid inputs?
			if ( ! empty( $notice_id ) ) {

				if ( in_array( $notice_id, $wp_default_meta_keys, true ) ) {
					wp_send_json_error( esc_html__( 'Invalid notice ID.', 'astra-notices' ) );
				}

				if ( ! empty( $repeat_notice_after ) ) {
					set_transient( $notice_id, true, $repeat_notice_after );
				} else {
					update_user_meta( get_current_user_id(), $notice_id, 'notice-dismissed' );
				}

				wp_send_json_success();
			}

			wp_send_json_error();
		}


