<?php
/***
*
*Found actions: 12
*Found functions:9
*Extracted functions:9
*Total parameter names extracted: 8
*Overview: {'generateNonce': {'presto_refresh_progress_nonce', 'nopriv_presto_refresh_progress_nonce'}, 'getMediaItemAttributes': {'presto_get_media_attributes'}, 'handle_daily_views': {'nopriv_presto_player_daily_views', 'presto_player_daily_views'}, 'dismiss_notice': {'astra-notice-dismiss'}, 'send_plugin_deactivate_feedback': {'uds_plugin_deactivate_feedback'}, 'fetchVideos': {'presto_fetch_videos'}, 'progressAjaxPercent': {'nopriv_presto_player_progress_percent', 'presto_player_progress_percent'}, 'refreshAjaxTempSecurityUser': {'presto_player_load_user_video'}, 'dismiss': {'presto_dismiss_announcement'}}
*
***/

/** Function generateNonce() called by wp_ajax hooks: {'presto_refresh_progress_nonce', 'nopriv_presto_refresh_progress_nonce'} **/
/** No params detected :-/ **/


/** Function getMediaItemAttributes() called by wp_ajax hooks: {'presto_get_media_attributes'} **/
/** Parameters found in function getMediaItemAttributes(): {"post": ["_wpnonce", "id"]} **/
function getMediaItemAttributes() {
		// D4 sends et_admin_load_nonce; D5 sends wp_rest nonce via prestoPlayer.nonce.
		// wp_rest is a broad, shared nonce, so the current_user_can() check below is the real
		// authorization gate here — don't drop it assuming the nonce alone scopes access.
		$nonce = isset( $_POST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) : '';
		$valid = wp_verify_nonce( $nonce, 'et_admin_load_nonce' )
			|| wp_verify_nonce( $nonce, 'wp_rest' );
		if ( ! $valid ) {
			wp_send_json_error( array( 'message' => __( 'Invalid nonce.', 'presto-player' ) ), 403 );
		}

		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( array( 'message' => __( 'You are not allowed to do this.', 'presto-player' ) ), 403 );
		}

		$id = isset( $_POST['id'] ) ? absint( wp_unslash( $_POST['id'] ) ) : 0;

		if ( ! $id ) {
			wp_send_json_error( array( 'message' => __( 'You must provide an id.', 'presto-player' ) ), 400 );
		}

		$video = new ReusableVideo( $id );
		if ( ! $video->post || 'pp_video_block' !== $video->post->post_type ) {
			wp_send_json_error( array( 'message' => __( 'Video not found.', 'presto-player' ) ), 404 );
		}

		wp_send_json_success( $video->getAttributes() );
	}


/** Function handle_daily_views() called by wp_ajax hooks: {'nopriv_presto_player_daily_views', 'presto_player_daily_views'} **/
/** Parameters found in function handle_daily_views(): {"post": ["count"]} **/
function handle_daily_views() {
		if ( ! apply_filters( 'presto_player_daily_views_enabled', true ) ) {
			wp_send_json_error( 'disabled', 403 );
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- see docblock: nonce omitted by design for nopriv+cached-page compatibility.
		$count = isset( $_POST['count'] ) ? absint( wp_unslash( $_POST['count'] ) ) : 1;

		$this->record_daily_views( $count );

		wp_send_json_success();
	}


/** Function dismiss_notice() called by wp_ajax hooks: {'astra-notice-dismiss'} **/
/** Parameters found in function dismiss_notice(): {"post": ["notice_id", "repeat_notice_after"]} **/
function dismiss_notice() {
			check_ajax_referer( 'astra-notices', 'nonce' );

			$notice_id           = ( isset( $_POST['notice_id'] ) ) ? sanitize_key( wp_unslash( $_POST['notice_id'] ) ) : '';
			$repeat_notice_after = ( isset( $_POST['repeat_notice_after'] ) ) ? absint( wp_unslash( $_POST['repeat_notice_after'] ) ) : 0;
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

			$allowed_notices = get_option( 'astra_notices_allowed', array() ); // Get allowed notices.

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


/** Function fetchVideos() called by wp_ajax hooks: {'presto_fetch_videos'} **/
/** Parameters found in function fetchVideos(): {"request": ["_wpnonce"], "post": ["search", "post_id"]} **/
function fetchVideos() {
		// Verify nonce.
		$nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
			wp_send_json_error();
		}

		// Need to edit posts.
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error();
		}

		$args = array();

		if ( ! empty( $_POST['search'] ) ) {
			$args['s'] = sanitize_text_field( wp_unslash( $_POST['search'] ) );
		}

		if ( ! empty( $_POST['post_id'] ) ) {
			$args['post__in'][0] = absint( wp_unslash( $_POST['post_id'] ) ); // Convert single post_id into array.
		}

		$videos = ( new ReusableVideo() )->fetch( $args );

		wp_send_json_success( $videos );
	}


/** Function progressAjaxPercent() called by wp_ajax hooks: {'nopriv_presto_player_progress_percent', 'presto_player_progress_percent'} **/
/** No params detected :-/ **/


/** Function refreshAjaxTempSecurityUser() called by wp_ajax hooks: {'presto_player_load_user_video'} **/
/** Parameters found in function refreshAjaxTempSecurityUser(): {"post": ["type", "id"]} **/
function refreshAjaxTempSecurityUser( $action ) {
		$type = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';

		if ( empty( $type ) ) {
			wp_send_json_error( 'type not set' );
		}

		if ( ! defined( 'DOING_AJAX' ) && ! is_user_logged_in() ) {
			wp_safe_redirect( home_url() );
			exit();
		}

		check_ajax_referer( 'presto_player' );

		if ( 'private-hosted' === $type ) {
			if ( isset( $_POST['id'] ) ) {
				$post_id = (int) $_POST['id'];
				$this->setVideoTransient( (int) $post_id );
				wp_send_json_success( $this->getSrc( (int) $post_id, true ) );
			}
		}

		if ( ! $this->is_premium ) {
			wp_send_json_success();
			return;
		}

		wp_send_json_success();
	}


/** Function dismiss() called by wp_ajax hooks: {'presto_dismiss_announcement'} **/
/** Parameters found in function dismiss(): {"get": ["presto_action", "presto_notice", "_wpnonce"]} **/
function dismiss() {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		// phpcs:disable WordPress.Security.NonceVerification.Recommended -- nonce verified below.
		if ( ! isset( $_GET['presto_action'] ) || 'dismiss_notices' !== $_GET['presto_action'] ) {
			return;
		}

		$notice = ! empty( $_GET['presto_notice'] ) ? sanitize_text_field( wp_unslash( $_GET['presto_notice'] ) ) : '';
		if ( ! $notice || ! self::is_dismissable_notice( $notice ) ) {
			return;
		}

		$nonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Recommended
		if ( ! wp_verify_nonce( $nonce, self::DISMISS_NONCE_ACTION ) ) {
			return;
		}

		update_option( 'presto_player_dismissed_notice_' . $notice, 1 );
	}


