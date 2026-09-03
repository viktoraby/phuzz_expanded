<?php
/***
*
*Found actions: 12
*Found functions:12
*Extracted functions:11
*Total parameter names extracted: 9
*Overview: {'dismiss': {'themeisle_sdk_dismiss_notice'}, 'close_banner': {'close_ad_banner'}, 'make_api_test': {'wpcf7r_make_api_test'}, 'ThemeisleSDK\\Modules\\Notification::regular_dismiss': {'themeisle_sdk_dismiss_notice'}, 'set_action_menu_order': {'wpcf7r_set_action_menu_order'}, 'duplicate_action': {'wpcf7r_duplicate_action'}, 'get_action_template': {'wpcf7r_get_action_template'}, 'disable_notification_ajax': {'themeisle_sdk_dismiss_black_friday_notice'}, 'dismiss_promotion': {'tisdk_update_option'}, 'dismiss_license_notice': {'themeisle_sdk_dismiss_license_notice'}, 'delete_action_post': {'wpcf7r_delete_action'}, 'add_action_post': {'wpcf7r_add_action'}}
*
***/

/** Function dismiss() called by wp_ajax hooks: {'themeisle_sdk_dismiss_notice'} **/
/** Parameters found in function dismiss(): {"post": ["id", "confirm"]} **/
function dismiss() {
		check_ajax_referer( (string) __CLASS__, 'nonce' );

		$id      = isset( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : '';
		$confirm = isset( $_POST['confirm'] ) ? sanitize_text_field( $_POST['confirm'] ) : 'no';

		if ( empty( $id ) ) {
			wp_send_json( [] );
		}
		self::setup_notifications();
		$ids = wp_list_pluck( self::$notifications, 'id' );
		if ( ! in_array( $id, $ids, true ) ) {
			wp_send_json( [] );
		}
		self::set_last_active_notification_timestamp();
		update_option( $id, $confirm );
		do_action( $id . '_process_confirm', $confirm );
		wp_send_json( [] );
	}


/** Function close_banner() called by wp_ajax hooks: {'close_ad_banner'} **/
/** No params detected :-/ **/


/** Function make_api_test() called by wp_ajax hooks: {'wpcf7r_make_api_test'} **/
/** Parameters found in function make_api_test(): {"post": ["data"]} **/
function make_api_test() {
		if ( current_user_can( 'wpcf7_edit_contact_form' ) && wpcf7_validate_nonce() ) {
			if ( ! isset( $_POST['data']['data'] ) ) {
				return;
			}
			parse_str( $_POST['data']['data'], $data );

			if ( ! is_array( $data ) ) {
				die( '-1' );
			}

			$action_id = isset( $_POST['data']['action_id'] ) ? (int) sanitize_text_field( $_POST['data']['action_id'] ) : '';
			$cf7_id    = isset( $_POST['data']['cf7_id'] ) ? (int) sanitize_text_field( $_POST['data']['cf7_id'] ) : '';
			$rule_id   = isset( $_POST['data']['rule_id'] ) ? $_POST['data']['rule_id'] : '';

			add_filter( 'after_qs_cf7_api_send_lead', array( $this, 'after_fake_submission' ), 10, 3 );

			if ( isset( $data['wpcf7-redirect']['actions'] ) ) {
				$response = array();

				$posted_action = reset( $data['wpcf7-redirect']['actions'] );
				$posted_action = $posted_action['test_values'];
				$_POST         = $posted_action;
				// this will create a fake form submission.
				$this->cf7r_form = get_cf7r_form( $cf7_id );
				$this->cf7r_form->enable_action( $action_id );

				$cf7_form   = $this->cf7r_form->get_cf7_form_instance();
				$submission = WPCF7_Submission::get_instance( $cf7_form ); // Note: it auto-triggers the process.

				if ( $submission->get_status() === 'validation_failed' ) {
					$invalid_fields             = $submission->get_invalid_fields();
					$response['status']         = 'failed';
					$response['invalid_fields'] = $invalid_fields;
				} else {
					$response['status'] = 'success';
					$response['html']   = $this->get_test_api_results_html();
				}

				wp_send_json( $response );
			}
		}
	}


/** Function ThemeisleSDK\Modules\Notification::regular_dismiss() called by wp_ajax hooks: {'themeisle_sdk_dismiss_notice'} **/
/** No function found :-/ **/


/** Function set_action_menu_order() called by wp_ajax hooks: {'wpcf7r_set_action_menu_order'} **/
/** Parameters found in function set_action_menu_order(): {"post": ["data"]} **/
function set_action_menu_order() {
		global $wpdb;

		if ( current_user_can( 'wpcf7_edit_contact_form' ) && wpcf7_validate_nonce() ) {
			if ( ! isset( $_POST['data']['order'] ) ) {
				return false;
			}

			$order_data = wp_unslash( $_POST['data']['order'] );
			parse_str( $order_data, $data );

			if ( ! is_array( $data ) ) {
				return false;
			}

			// get objects per now page.
			$id_arr = array();
			foreach ( $data as $key => $values ) {
				foreach ( $values as $position => $id ) {
					$id_arr[] = $id;
				}
			}

			foreach ( $id_arr as $key => $post_id ) {
				$menu_order = $key + 1;
				$wpdb->update( $wpdb->posts, array( 'menu_order' => $menu_order ), array( 'ID' => intval( $post_id ) ) );
			}
		}

		die( '1' );
	}


/** Function duplicate_action() called by wp_ajax hooks: {'wpcf7r_duplicate_action'} **/
/** Parameters found in function duplicate_action(): {"post": ["data"]} **/
function duplicate_action() {
		$results['action_row'] = '';

		if ( current_user_can( 'wpcf7_edit_contact_form' ) && wpcf7_validate_nonce() ) {
			if ( isset( $_POST['data'] ) && is_array( $_POST['data'] ) ) {
				$action_data = $_POST['data'];

				$action_post_id = $action_data['post_id'];

				$action_post = get_post( $action_post_id );

				$new_action_post_id = $this->duplicate_post( $action_post );

				update_post_meta( $new_action_post_id, 'wpcf7_id', $action_data['form_id'] );

				$action = WPCF7R_Action::get_action( $new_action_post_id );

				$results['action_row'] = $action->get_action_row();
			}
		}

		wp_send_json( $results );
	}


/** Function get_action_template() called by wp_ajax hooks: {'wpcf7r_get_action_template'} **/
/** Parameters found in function get_action_template(): {"post": ["data"]} **/
function get_action_template() {
		$response = array();
		if ( current_user_can( 'wpcf7_edit_contact_form' ) && wpcf7_validate_nonce() ) {
			$data = isset( $_POST['data'] ) ? $_POST['data'] : '';

			if ( isset( $data['action_id'] ) ) {
				$action_id      = (int) $data['action_id'];
				$popup_template = sanitize_text_field( $data['template'] );

				$action = WPCF7R_Action::get_action( $action_id );

				ob_start();

				$params = array(
					'popup-template' => $popup_template,
				);

				$action->get_action_settings( $params );

				$response['action_content'] = ob_get_clean();
			}
		}

		wp_send_json_success( $response );
	}


/** Function disable_notification_ajax() called by wp_ajax hooks: {'themeisle_sdk_dismiss_black_friday_notice'} **/
/** No params detected :-/ **/


/** Function dismiss_promotion() called by wp_ajax hooks: {'tisdk_update_option'} **/
/** Parameters found in function dismiss_promotion(): {"post": ["nonce", "value"]} **/
function dismiss_promotion() {
		if ( ! isset( $_POST['nonce'] ) || ! isset( $_POST['value'] ) ) {
			$response = array(
				'success' => false,
				'message' => 'Missing nonce or value.',
			);
			wp_send_json( $response );
			wp_die();
		}

		$nonce = sanitize_text_field( $_POST['nonce'] );
		$value = sanitize_text_field( $_POST['value'] );

		if ( ! wp_verify_nonce( $nonce, 'tisdk_update_option' ) ) {
			$response = array(
				'success' => false,
				'message' => 'Invalid nonce.',
			);
			wp_send_json( $response );
			wp_die();
		}

		$options = get_option( $this->option_main );
		$options = json_decode( $options, true );

		$options[ $value ] = time();

		update_option( $this->option_main, wp_json_encode( $options ) );

		$response = array(
			'success' => true,
		);

		wp_send_json( $response );
		wp_die();
	}


/** Function dismiss_license_notice() called by wp_ajax hooks: {'themeisle_sdk_dismiss_license_notice'} **/
/** Parameters found in function dismiss_license_notice(): {"post": ["nonce", "notice_id"]} **/
function dismiss_license_notice() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'themeisle_sdk_dismiss_license_notice' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Insufficient permissions' );
		}

		$notice_id = isset( $_POST['notice_id'] ) ? sanitize_text_field( $_POST['notice_id'] ) : '';

		if ( empty( $notice_id ) ) {
			wp_send_json_error( 'Missing notice ID' );
		}

		// Save the dismissal option.
		$dismiss_option_key = $notice_id . '_dismissed';
		update_option( $dismiss_option_key, true );

		wp_send_json_success();
	}


/** Function delete_action_post() called by wp_ajax hooks: {'wpcf7r_delete_action'} **/
/** Parameters found in function delete_action_post(): {"post": ["data"]} **/
function delete_action_post() {
		$response['status'] = 'failed';

		if ( current_user_can( 'wpcf7_edit_contact_form' ) && wpcf7_validate_nonce() ) {
			$data = isset( $_POST['data'] ) && is_array( $_POST['data'] ) ? $_POST['data'] : '';

			if ( empty( $data ) ) {
				return;
			}

			foreach ( $data as $post_to_delete ) {
				if ( $post_to_delete ) {
					wp_trash_post( $post_to_delete['post_id'] );
					$response['status'] = 'deleted';
				}
			}
		}

		wp_send_json( $response );
	}


/** Function add_action_post() called by wp_ajax hooks: {'wpcf7r_add_action'} **/
/** Parameters found in function add_action_post(): {"post": ["data"]} **/
function add_action_post() {
		$results['action_row'] = '';

		if ( current_user_can( 'wpcf7_edit_contact_form' ) && wpcf7_validate_nonce() ) {
			$post_id     = isset( $_POST['data']['post_id'] ) ? (int) sanitize_text_field( $_POST['data']['post_id'] ) : '';
			$rule_id     = isset( $_POST['data']['rule_id'] ) ? sanitize_text_field( $_POST['data']['rule_id'] ) : '';
			$action_type = isset( $_POST['data']['action_type'] ) ? sanitize_text_field( $_POST['data']['action_type'] ) : '';

			$rule_name = __( 'New Action', 'wpcf7-redirect' );

			$this->cf7r_form = get_cf7r_form( $post_id );

			$actions = array();

			// migrate from old api plugin.
			if ( 'migrate_from_cf7_api' === $action_type || 'migrate_from_cf7_redirect' === $action_type ) {
				if ( ! $this->cf7r_form->has_migrated( $action_type ) ) {
					$actions = $this->convert_to_action( $action_type, $post_id, $rule_name, $rule_id );
					$this->cf7r_form->update_migration( $action_type );
				}
			} else {
				$actions[] = $this->create_action( $post_id, $rule_name, $rule_id, $action_type );
			}

			if ( $actions ) {
				foreach ( $actions as $action ) {
					if ( ! is_wp_error( $action ) ) {
						$results['action_row'] .= $action->get_action_row();
					} else {
						wp_send_json( $results );
					}
				}
			} else {
				$results['action_row'] = '';
			}
		}

		wp_send_json( $results );
	}


