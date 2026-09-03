<?php
/***
*
*Found actions: 9
*Found functions:9
*Extracted functions:9
*Total parameter names extracted: 3
*Overview: {'core_handler': {'imagifybeat'}, 'get_folder_type_data_callback': {'imagify_get_folder_type_data'}, 'admin_post_dismiss_notice': {'imagify_dismiss_notice'}, 'bulk_get_stats_callback': {'imagify_bulk_get_stats'}, 'prevent_auto_optimization_when_recovering_from_upload_failure': {'media-create-image-subsizes'}, 'bulk_stop_callback': {'imagify_bulk_stop'}, 'bulk_optimize_callback': {'imagify_bulk_optimize'}, 'bulk_info_seen_callback': {'imagify_bulk_info_seen'}, 'missing_nextgen_callback': {'imagify_missing_nextgen_generation'}}
*
***/

/** Function core_handler() called by wp_ajax hooks: {'imagifybeat'} **/
/** Parameters found in function core_handler(): {"post": ["_nonce", "screen_id", "data"]} **/
function core_handler() {
		if ( empty( $_POST['_nonce'] ) ) {
			wp_send_json_error();
		}

		$data        = [];
		$response    = [];
		$nonce_state = wp_verify_nonce( sanitize_key( wp_unslash( $_POST['_nonce'] ) ), 'imagifybeat-nonce' );

		// Screen_id is the same as $current_screen->id and the JS global 'pagenow'.
		if ( ! empty( $_POST['screen_id'] ) ) {
			$screen_id = sanitize_key( $_POST['screen_id'] );
		} else {
			$screen_id = 'front';
		}

		if ( ! empty( $_POST['data'] ) ) {
			$data = wp_unslash( (array) $_POST['data'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		}

		if ( 1 !== $nonce_state ) {
			/**
			 * Filters the nonces to send.
			 *
			 * @since 1.9.3
			 *
			 * @param array  $response  The Imagifybeat response.
			 * @param array  $data      The $_POST data sent.
			 * @param string $screen_id The screen id.
			 */
			$response = wpm_apply_filters_typed( 'array', 'imagifybeat_refresh_nonces', $response, $data, $screen_id );

			if ( false === $nonce_state ) {
				// User is logged in but nonces have expired.
				$response['nonces_expired'] = true;
				wp_send_json( $response );
			}
		}

		if ( ! empty( $data ) ) {
			/**
			 * Filters the Imagifybeat response received.
			 *
			 * @since 1.9.3
			 *
			 * @param array  $response  The Imagifybeat response.
			 * @param array  $data      The $_POST data sent.
			 * @param string $screen_id The screen id.
			 */
			$response = wpm_apply_filters_typed( 'array', 'imagifybeat_received', $response, $data, $screen_id );
		}

		/**
		 * Filters the Imagifybeat response sent.
		 *
		 * @since 1.9.3
		 *
		 * @param array  $response  The Imagifybeat response.
		 * @param string $screen_id The screen id.
		 */
		$response = wpm_apply_filters_typed( 'array', 'imagifybeat_send', $response, $screen_id );

		/**
		 * Fires when Imagifybeat ticks in logged-in environments.
		 *
		 * Allows the transport to be easily replaced with long-polling.
		 *
		 * @since  1.9.3
		 * @author Grégory Viguier
		 *
		 * @param array  $response  The Imagifybeat response.
		 * @param string $screen_id The screen id.
		 */
		do_action( 'imagifybeat_tick', $response, $screen_id );

		// Send the current time according to the server.
		$response['server_time'] = time();

		wp_send_json( $response );
	}


/** Function get_folder_type_data_callback() called by wp_ajax hooks: {'imagify_get_folder_type_data'} **/
/** No params detected :-/ **/


/** Function admin_post_dismiss_notice() called by wp_ajax hooks: {'imagify_dismiss_notice'} **/
/** Parameters found in function admin_post_dismiss_notice(): {"get": ["notice"]} **/
function admin_post_dismiss_notice() {
		imagify_check_nonce( self::DISMISS_NONCE_ACTION );

		$notice  = ! empty( $_GET['notice'] ) ? sanitize_text_field( wp_unslash( $_GET['notice'] ) ) : '';
		$notices = $this->get_notice_ids();
		$notices = array_flip( $notices );

		if ( ! $notice || ! isset( $notices[ $notice ] ) || ! $this->user_can( $notice ) ) {
			imagify_die();
		}

		self::dismiss_notice( $notice );

		/**
		 * Fires when a notice is dismissed.
		 *
		 * @since 1.4.2
		 *
		 * @param string $notice The notice slug
		*/
		do_action( 'imagify_dismiss_notice', $notice );

		imagify_maybe_redirect();
		wp_send_json_success();
	}


/** Function bulk_get_stats_callback() called by wp_ajax hooks: {'imagify_bulk_get_stats'} **/
/** No params detected :-/ **/


/** Function prevent_auto_optimization_when_recovering_from_upload_failure() called by wp_ajax hooks: {'media-create-image-subsizes'} **/
/** Parameters found in function prevent_auto_optimization_when_recovering_from_upload_failure(): {"post": ["attachment_id"]} **/
function prevent_auto_optimization_when_recovering_from_upload_failure() {
		if ( ! check_ajax_referer( 'media-form', false, false ) ) {
			return;
		}

		if ( ! current_user_can( 'upload_files' ) ) {
			return;
		}

		if ( ! imagify_get_context( 'wp' )->current_user_can( 'auto-optimize' ) ) {
			return;
		}

		$attachment_id = ! empty( $_POST['attachment_id'] ) ? (int) $_POST['attachment_id'] : 0;

		if ( empty( $attachment_id ) ) {
			return;
		}

		if ( ! imagify_is_attachment_mime_type_supported( $attachment_id ) ) {
			return;
		}

		$this->upload_failure_id = $attachment_id;

		// Auto-optimization will be done on shutdown.
		ob_start( [ $this, 'maybe_do_auto_optimization_after_recovering_from_upload_failure' ] );
	}


/** Function bulk_stop_callback() called by wp_ajax hooks: {'imagify_bulk_stop'} **/
/** No params detected :-/ **/


/** Function bulk_optimize_callback() called by wp_ajax hooks: {'imagify_bulk_optimize'} **/
/** No params detected :-/ **/


/** Function bulk_info_seen_callback() called by wp_ajax hooks: {'imagify_bulk_info_seen'} **/
/** No params detected :-/ **/


/** Function missing_nextgen_callback() called by wp_ajax hooks: {'imagify_missing_nextgen_generation'} **/
/** No params detected :-/ **/


