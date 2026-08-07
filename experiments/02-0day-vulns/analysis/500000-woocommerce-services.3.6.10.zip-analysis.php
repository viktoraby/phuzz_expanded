<?php
/***
*
*Found actions: 6
*Found functions:6
*Extracted functions:6
*Total parameter names extracted: 4
*Overview: {'ajax_dismiss_notice': {'wc_connect_dismiss_notice'}, 'ajax_tracks': {'jetpack_tracks'}, 'handle_survey_dismissal': {'wcs_migration_survey_dismiss'}, 'handle_survey_display_tracking': {'wcs_migration_survey_track_display'}, 'handle_tax_backup_download': {'wcs_download_tax_backup'}, 'handle_survey_submission': {'wcs_migration_survey_submit'}}
*
***/

/** Function ajax_dismiss_notice() called by wp_ajax hooks: {'wc_connect_dismiss_notice'} **/
/** Parameters found in function ajax_dismiss_notice(): {"post": ["dismissible_id"]} **/
function ajax_dismiss_notice() {
			if ( empty( $_POST['dismissible_id'] ) ) {
				return;
			}

			check_ajax_referer( 'wc_connect_dismiss_notice', 'nonce' );
			$this->dismiss_notice( sanitize_key( $_POST['dismissible_id'] ) );
			wp_die();
		}


/** Function ajax_tracks() called by wp_ajax hooks: {'jetpack_tracks'} **/
/** Parameters found in function ajax_tracks(): {"request": ["tracksNonce", "tracksEventName", "tracksEventType", "tracksEventProp"]} **/
function ajax_tracks() {
		// Check for nonce.
		if (
			empty( $_REQUEST['tracksNonce'] )
			|| ! wp_verify_nonce( $_REQUEST['tracksNonce'], 'jp-tracks-ajax-nonce' ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- WP core doesn't pre-sanitize nonces either.
		) {
			wp_send_json_error(
				__( 'You aren’t authorized to do that.', 'jetpack-connection' ),
				403,
				JSON_UNESCAPED_SLASHES
			);
		}

		if ( ! isset( $_REQUEST['tracksEventName'] ) || ! isset( $_REQUEST['tracksEventType'] ) ) {
			wp_send_json_error(
				__( 'No valid event name or type.', 'jetpack-connection' ),
				403,
				JSON_UNESCAPED_SLASHES
			);
		}

		$tracks_data = array();
		if ( 'click' === $_REQUEST['tracksEventType'] && isset( $_REQUEST['tracksEventProp'] ) ) {
			if ( is_array( $_REQUEST['tracksEventProp'] ) ) {
				$tracks_data = array_map( 'filter_var', wp_unslash( $_REQUEST['tracksEventProp'] ) );
			} else {
				$tracks_data = array( 'clicked' => filter_var( wp_unslash( $_REQUEST['tracksEventProp'] ) ) );
			}
		}

		$this->record_user_event( filter_var( wp_unslash( $_REQUEST['tracksEventName'] ) ), $tracks_data, null, false );

		wp_send_json_success( null, null, JSON_UNESCAPED_SLASHES );
	}


/** Function handle_survey_dismissal() called by wp_ajax hooks: {'wcs_migration_survey_dismiss'} **/
/** No params detected :-/ **/


/** Function handle_survey_display_tracking() called by wp_ajax hooks: {'wcs_migration_survey_track_display'} **/
/** No params detected :-/ **/


/** Function handle_tax_backup_download() called by wp_ajax hooks: {'wcs_download_tax_backup'} **/
/** Parameters found in function handle_tax_backup_download(): {"get": ["file"]} **/
function handle_tax_backup_download() {
			check_ajax_referer( 'wcs_tax_backup_download', '_wpnonce' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die( esc_html__( 'You do not have permission to download this file.', 'woocommerce-services' ), '', array( 'response' => 403 ) );
			}

			$filename = isset( $_GET['file'] ) ? sanitize_file_name( wp_unslash( $_GET['file'] ) ) : '';

			// Validate filename matches expected pattern: YYYY-MM-DD or legacy MM-DD-YYYY, with optional hash suffix.
			if ( ! preg_match( '/^taxjar-wc_tax_rates-(\d{4}-\d{2}-\d{2}|\d{2}-\d{2}-\d{4})-\d{1,10}(-[0-9a-f]{32})?\.csv$/', $filename ) ) {
				wp_die( esc_html__( 'Invalid file name.', 'woocommerce-services' ), '', array( 'response' => 400 ) );
			}

			$upload_dir = wp_upload_dir();
			$file_path  = $upload_dir['basedir'] . '/woocommerce_uploads/taxes/' . $filename;

			// Fall back to the uploads root for sites where the protected directory could not be created.
			if ( ! file_exists( $file_path ) ) {
				$file_path = $upload_dir['basedir'] . '/' . $filename;
			}

			if ( ! file_exists( $file_path ) ) {
				wp_die( esc_html__( 'File not found.', 'woocommerce-services' ), '', array( 'response' => 404 ) );
			}

			nocache_headers();
			header( 'Content-Type: text/csv' );
			header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
			header( 'Content-Length: ' . filesize( $file_path ) );

			// Clean any output buffers to prevent corrupted downloads.
			while ( ob_get_level() > 0 ) {
				ob_end_clean();
			}

			readfile( $file_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_readfile
			exit;
		}


/** Function handle_survey_submission() called by wp_ajax hooks: {'wcs_migration_survey_submit'} **/
/** Parameters found in function handle_survey_submission(): {"post": ["survey_data"]} **/
function handle_survey_submission() {
			// Verify nonce
			if ( ! wp_verify_nonce( Utils::get_sanitized_request_data( 'nonce' ), 'wcs_migration_survey' ) ) {
				wp_die( 'Security check failed' );
			}

			// Get survey data
			$survey_data = json_decode( stripslashes( $_POST['survey_data'] ), true );

			if ( ! $survey_data ) {
				wp_send_json_error( 'Invalid survey data' );
			}

			// Mark survey as completed for this user
			$user_id = get_current_user_id();
			update_user_meta( $user_id, 'wcs_migration_survey_completed', true );

			// Track submission event with detailed properties
			$track_properties = array(
				'primary_reason' => sanitize_text_field( $survey_data['primary'] ),
			);

			// Add followup responses to tracking (flatten the array)
			if ( ! empty( $survey_data['followup'] ) ) {
				foreach ( $survey_data['followup'] as $key => $value ) {
					if ( is_bool( $value ) ) {
						$track_properties[ 'followup_' . $key ] = $value ? 'true' : 'false';
					} else {
						$track_properties[ 'followup_' . $key ] = sanitize_text_field( $value );
					}
				}
			}

			$this->track_event( 'submitted', $track_properties );

			wp_send_json_success();
		}


