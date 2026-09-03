<?php
/***
*
*Found actions: 9
*Found functions:9
*Extracted functions:9
*Total parameter names extracted: 9
*Overview: {'activate_theme': {'surerank_activate_theme'}, 'handle_notice_response': {'surerank_notice_response'}, 'save_term_settings': {'surerank_save_term_settings'}, 'save_post_settings': {'surerank_save_post_settings'}, 'save_user_settings': {'surerank_save_user_settings'}, 'send_plugin_deactivate_feedback': {'uds_plugin_deactivate_feedback'}, 'activate_plugin': {'surerank_activate_plugin'}, 'save_admin_settings': {'surerank_save_admin_settings'}, 'dismiss_notice': {'astra-notice-dismiss'}}
*
***/

/** Function activate_theme() called by wp_ajax hooks: {'surerank_activate_theme'} **/
/** Parameters found in function activate_theme(): {"post": ["slug"]} **/
function activate_theme() {
		// Check ajax referer.
		check_ajax_referer( 'surerank_plugin', '_ajax_nonce' );

		// Check if the request is an ajax request and early return if not.
		if ( ! wp_doing_ajax() ) {
			wp_send_json_error(
				[
					'success' => false,
					'message' => __( 'Not an AJAX request.', 'surerank' ),
				],
			);
		}

		// Check user capabilities.
		if ( ! current_user_can( 'customize' ) ) {
			wp_send_json_error(
				[
					'success' => false,
					'message' => __( 'You do not have permission to activate themes.', 'surerank' ),
				],
			);
		}

		// Get theme slug from request.
		$theme_stylesheet = isset( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';

		if ( empty( $theme_stylesheet ) ) {
			wp_send_json_error(
				[
					'success' => false,
					'message' => __( 'No theme specified.', 'surerank' ),
				],
			);
		}

		// Activate the theme.
		switch_theme( $theme_stylesheet );

		// Send success response.
		wp_send_json_success(
			[
				'success' => true,
				'message' => __( 'Theme activated successfully.', 'surerank' ),
			],
		);
	}


/** Function handle_notice_response() called by wp_ajax hooks: {'surerank_notice_response'} **/
/** Parameters found in function handle_notice_response(): {"post": ["notice_id", "button"]} **/
function handle_notice_response(): void {
		if ( ! check_ajax_referer( 'surerank_notice_response', 'nonce', false ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid nonce.', 'surerank' ) ], 403 );
		}

		if ( ! current_user_can( self::get_required_capability() ) ) {
			wp_send_json_error( [ 'message' => __( 'Unauthorized user.', 'surerank' ) ], 403 );
		}

		$notice_id = isset( $_POST['notice_id'] ) ? sanitize_text_field( wp_unslash( $_POST['notice_id'] ) ) : '';
		$button    = isset( $_POST['button'] ) ? sanitize_text_field( wp_unslash( $_POST['button'] ) ) : '';
		$valid     = self::get_notice_response_events();

		if ( ! isset( $valid[ $notice_id ][ $button ] ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid parameters.', 'surerank' ) ], 400 );
		}

		$events = Analytics::events();
		if ( null !== $events ) {
			$events->track( $valid[ $notice_id ][ $button ], $button );
		}

		wp_send_json_success();
	}


/** Function save_term_settings() called by wp_ajax hooks: {'surerank_save_term_settings'} **/
/** Parameters found in function save_term_settings(): {"post": ["term_id"]} **/
function save_term_settings(): void {
		if ( ! $this->guard_request( 'POST', '/surerank/v1/term/settings' ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verified in guard_request() above.
		$term_id = isset( $_POST['term_id'] ) ? absint( wp_unslash( $_POST['term_id'] ) ) : 0;
		if ( $term_id <= 0 ) {
			wp_send_json_error(
				[
					'success' => false,
					'message' => __( 'Invalid term id.', 'surerank' ),
				],
				400
			);
		}

		if ( ! Term::can_manage_term_seo( $term_id ) ) {
			wp_send_json(
				[
					'success' => false,
					'message' => __( 'You are not allowed to manage SEO settings for this term.', 'surerank' ),
				],
				403
			);
		}

		$meta_data = $this->extract_meta_data();

		$result = Term::save_term_seo_meta( $term_id, $meta_data );

		$this->respond_with( $result );
	}


/** Function save_post_settings() called by wp_ajax hooks: {'surerank_save_post_settings'} **/
/** Parameters found in function save_post_settings(): {"post": ["post_id"]} **/
function save_post_settings(): void {
		if ( ! $this->guard_request( 'POST', '/surerank/v1/post/settings' ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verified in guard_request() above.
		$post_id = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : 0;
		if ( $post_id <= 0 ) {
			wp_send_json_error(
				[
					'success' => false,
					'message' => __( 'Invalid post id.', 'surerank' ),
				],
				400
			);
		}

		if ( ! Post::can_manage_post_seo( $post_id ) ) {
			wp_send_json(
				[
					'success' => false,
					'message' => __( 'You are not allowed to manage SEO settings for this post.', 'surerank' ),
				],
				403
			);
		}

		$meta_data = $this->extract_meta_data();

		$result = Post::save_post_seo_meta( $post_id, $meta_data );

		$this->respond_with( $result );
	}


/** Function save_user_settings() called by wp_ajax hooks: {'surerank_save_user_settings'} **/
/** Parameters found in function save_user_settings(): {"post": ["user_id"]} **/
function save_user_settings(): void {
		if ( ! $this->guard_request( 'POST', '/surerank/v1/user/settings' ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verified in guard_request() above.
		$user_id = isset( $_POST['user_id'] ) ? absint( wp_unslash( $_POST['user_id'] ) ) : 0;
		if ( $user_id <= 0 || ! User_Seo::can_manage_user_seo( $user_id ) ) {
			wp_send_json_error(
				[
					'success' => false,
					'message' => __( 'Invalid user id.', 'surerank' ),
				],
				400
			);
		}

		$meta_data = $this->extract_meta_data();

		$result = User_Seo::save_user_seo_meta( $user_id, $meta_data );

		$this->respond_with( $result );
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


/** Function activate_plugin() called by wp_ajax hooks: {'surerank_activate_plugin'} **/
/** Parameters found in function activate_plugin(): {"post": ["slug"]} **/
function activate_plugin() {
		// Check ajax referer.
		check_ajax_referer( 'surerank_plugin', '_ajax_nonce' );

		// Check if the request is an ajax request and early return if not.
		if ( ! wp_doing_ajax() ) {
			wp_send_json_error(
				[
					'success' => false,
					'message' => __( 'Not an AJAX request.', 'surerank' ),
				],
			);
		}

		// Check user capabilities.
		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error(
				[
					'success' => false,
					'message' => __( 'You do not have permission to activate plugins.', 'surerank' ),
				],
			);
		}

		// Get plugin slug from request.
		$plugin_slug = isset( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';

		if ( empty( $plugin_slug ) ) {
			wp_send_json_error(
				[
					'success' => false,
					'message' => __( 'No plugin specified.', 'surerank' ),
				],
			);
		}

		// Disable redirection to plugin page after activation.
		add_filter( 'wp_redirect', '__return_false' );

		// Activate the plugin.
		$result = activate_plugin( $plugin_slug );

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

		// Send success response.
		wp_send_json_success(
			[
				'success' => true,
				'message' => __( 'Plugin activated successfully.', 'surerank' ),
			],
		);
	}


/** Function save_admin_settings() called by wp_ajax hooks: {'surerank_save_admin_settings'} **/
/** Parameters found in function save_admin_settings(): {"post": ["data"]} **/
function save_admin_settings(): void {
		if ( ! $this->guard_request( 'POST', '/surerank/v1/admin/global-settings' ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Nonce verified in guard_request() above; raw body is sanitised via Sanitize::array_deep below before use.
		$raw_data = isset( $_POST['data'] ) ? wp_unslash( $_POST['data'] ) : '';
		$data     = is_string( $raw_data ) ? json_decode( $raw_data, true ) : $raw_data;

		if ( ! is_array( $data ) ) {
			$data = [];
		}

		/**
		 * Sanitized settings payload.
		 *
		 * @var array<string, mixed> $data
		 */
		$data = Sanitize::sanitize_request_data( $data );

		$result = Admin::save_admin_settings( $data );

		$this->respond_with( $result );
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
					wp_send_json_error( esc_html__( 'Invalid notice ID.', 'surerank' ) );
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


