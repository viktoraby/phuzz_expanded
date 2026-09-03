<?php
/***
*
*Found actions: 24
*Found functions:21
*Extracted functions:21
*Total parameter names extracted: 4
*Overview: {'ajax_create_import': {'tribe_aggregator_create_import'}, 'maybe_dismiss': {'tribe_notice_dismiss'}, 'ajax_convert_ical_settings': {'tribe_convert_legacy_ical_settings'}, 'ajax': {'tribe_aggregator_realtime_update'}, 'ajax_save_credentials': {'tribe_aggregator_save_credentials'}, 'get_feed': {'ian_get_feed'}, 'ajax_form_validate': {'tribe_event_validation'}, 'handle_read': {'ian_read'}, 'listen': {'tribe_logging_controls'}, 'ajax_sysinfo_optin': {'tribe_toggle_sysinfo_optin'}, 'render_modal': {'tec_qr_code_modal'}, 'handle_request': {'{$js_action}'}, 'ajax_preview_import': {'tribe_aggregator_preview_import'}, 'handle_read_all': {'ian_read_all'}, 'ajax_convert_legacy_ignored_events': {'tribe_convert_legacy_ignored_events'}, 'ajax_updater': {'tribe_timezone_update'}, 'handle_ajax_request': {'nopriv_{$action}', '{$action}'}, 'opt_in': {'ian_optin'}, 'handle_dismiss': {'ian_dismiss', 'tec_conditional_content_dismiss'}, 'route': {'tribe_dropdown', 'nopriv_tribe_dropdown'}, 'ajax_fetch_import': {'tribe_aggregator_fetch_import'}}
*
***/

/** Function ajax_create_import() called by wp_ajax hooks: {'tribe_aggregator_create_import'} **/
/** No params detected :-/ **/


/** Function maybe_dismiss() called by wp_ajax hooks: {'tribe_notice_dismiss'} **/
/** No params detected :-/ **/


/** Function ajax_convert_ical_settings() called by wp_ajax hooks: {'tribe_convert_legacy_ical_settings'} **/
/** No params detected :-/ **/


/** Function ajax() called by wp_ajax hooks: {'tribe_aggregator_realtime_update'} **/
/** No params detected :-/ **/


/** Function ajax_save_credentials() called by wp_ajax hooks: {'tribe_aggregator_save_credentials'} **/
/** Parameters found in function ajax_save_credentials(): {"post": ["tribe_credentials_which", "_wpnonce", "meetup_api_key"]} **/
function ajax_save_credentials() {
		$this->validate_nonce( 'tribe-aggregator-ajax-nonce' );

		if ( ! $this->current_user_can_edit_events() ) {
			wp_send_json_error(
				[
					'message_code' => 'error:save-credentials-failed',
					'message'      => __( 'You do not have permission to save credentials.', 'the-events-calendar' ),
				],
				403
			);
		}

		if ( empty( $_POST['tribe_credentials_which'] ) ) {
			wp_send_json_error(
				[
					'message' => __( 'Invalid credential save request', 'the-events-calendar' ),
				]
			);
		}

		$which = $_POST['tribe_credentials_which'];

		if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], "tribe-save-{$which}-credentials" ) ) {
			$data = array(
				'message' => __( 'Invalid credential save nonce', 'the-events-calendar' ),
			);

			wp_send_json_error( $data );
		}

		if ( 'meetup' === $which ) {
			if ( empty( $_POST['meetup_api_key'] ) ) {
				$data = array(
					'message' => __( 'The Meetup API key is required.', 'the-events-calendar' ),
				);

				wp_send_json_error( $data );
			}

			tribe_update_option( 'meetup_api_key', trim( preg_replace( '/[^a-zA-Z0-9]/', '', $_POST['meetup_api_key'] ) ) );

			$data = array(
				'message' => __( 'Credentials have been saved', 'the-events-calendar' ),
			);

			wp_send_json_success( $data );
		}

		$data = array(
			'message' => __( 'Unable to save credentials', 'the-events-calendar' ),
		);

		wp_send_json_error( $data );
	}


/** Function get_feed() called by wp_ajax hooks: {'ian_get_feed'} **/
/** No params detected :-/ **/


/** Function ajax_form_validate() called by wp_ajax hooks: {'tribe_event_validation'} **/
/** Parameters found in function ajax_form_validate(): {"request": ["name", "nonce", "type"]} **/
function ajax_form_validate() {
			$post_type = get_post_type_object( self::POSTTYPE );

			if (
				$_REQUEST['name']
				&& $_REQUEST['nonce']
				&& $_REQUEST['type']
				&& wp_verify_nonce( $_REQUEST['nonce'], 'tribe-validation-nonce' )
				&& current_user_can( $post_type->cap->edit_posts )
			) {
				echo $this->verify_unique_name( $_REQUEST['name'], $_REQUEST['type'] );
				die;
			}
		}


/** Function handle_read() called by wp_ajax hooks: {'ian_read'} **/
/** No params detected :-/ **/


/** Function listen() called by wp_ajax hooks: {'tribe_logging_controls'} **/
/** No params detected :-/ **/


/** Function ajax_sysinfo_optin() called by wp_ajax hooks: {'tribe_toggle_sysinfo_optin'} **/
/** Parameters found in function ajax_sysinfo_optin(): {"post": ["confirm", "generate_key"]} **/
function ajax_sysinfo_optin() {

			if ( ! isset( $_POST['confirm'] ) || ! wp_verify_nonce( $_POST['confirm'], 'sysinfo_optin_nonce' ) || ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'Permission Error', 'tribe-common' ) );
			}

			if ( 'generate' == $_POST['generate_key'] ) {
				$random    = base_convert( rand( 0, getrandmax() ), 10, 36 );
				$optin_key = hash( 'sha1', $random );

				update_option( self::$option_key, $optin_key );

				//Only Connect If a License Exists
				$keys = apply_filters( 'tribe-pue-install-keys', [] );
				if ( is_array( $keys ) && ! empty( $keys ) ) {
					self::send_sysinfo_key( $optin_key );
				} else {
					wp_send_json_success( __( 'Unique System Info Key Generated', 'tribe-common' ) );
				}

			} elseif ( 'remove' == $_POST['generate_key'] ) {
				$optin_key = get_option( self::$option_key );

				delete_option( self::$option_key );

				self::send_sysinfo_key( $optin_key, null, 'remove' );

			}

			wp_send_json_error( __( 'Permission Error', 'tribe-common' ) );
		}


/** Function render_modal() called by wp_ajax hooks: {'tec_qr_code_modal'} **/
/** No params detected :-/ **/


/** Function handle_request() called by wp_ajax hooks: {'{$js_action}'} **/
/** No params detected :-/ **/


/** Function ajax_preview_import() called by wp_ajax hooks: {'tribe_aggregator_preview_import'} **/
/** No params detected :-/ **/


/** Function handle_read_all() called by wp_ajax hooks: {'ian_read_all'} **/
/** No params detected :-/ **/


/** Function ajax_convert_legacy_ignored_events() called by wp_ajax hooks: {'tribe_convert_legacy_ignored_events'} **/
/** No params detected :-/ **/


/** Function ajax_updater() called by wp_ajax hooks: {'tribe_timezone_update'} **/
/** Parameters found in function ajax_updater(): {"post": ["check"]} **/
function ajax_updater() {
		if ( ! isset( $_POST['check'] ) || ! wp_verify_nonce( $_POST['check'], 'timezone-settings' ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$updater = new Tribe__Events__Admin__Timezone_Updater;
		$updater->init_update();

		wp_send_json( [
			'html'     => $updater->notice_inner(),
			'continue' => $updater->update_needed(),
		] );
	}


/** Function handle_ajax_request() called by wp_ajax hooks: {'nopriv_{$action}', '{$action}'} **/
/** No params detected :-/ **/


/** Function opt_in() called by wp_ajax hooks: {'ian_optin'} **/
/** No params detected :-/ **/


/** Function handle_dismiss() called by wp_ajax hooks: {'ian_dismiss', 'tec_conditional_content_dismiss'} **/
/** No params detected :-/ **/


/** Function route() called by wp_ajax hooks: {'tribe_dropdown', 'nopriv_tribe_dropdown'} **/
/** No params detected :-/ **/


/** Function ajax_fetch_import() called by wp_ajax hooks: {'tribe_aggregator_fetch_import'} **/
/** No params detected :-/ **/


