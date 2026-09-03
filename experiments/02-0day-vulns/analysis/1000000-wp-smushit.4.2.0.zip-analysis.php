<?php
/***
*
*Found actions: 15
*Found functions:15
*Extracted functions:13
*Total parameter names extracted: 5
*Overview: {'directory_smush_start': {'directory_smush_start'}, 'directory_smush_cancel': {'directory_smush_cancel'}, 'ajax_skip_media_hub_connect': {'skip_media_hub_connect'}, ' ! is_array( $to_smush ) ) {\n\t\t\t$to_smush = array();\n\t\t}\n\n\t\treturn array_map(\n\t\t\tfunction ( $image_id ) {\n\t\t\t\treturn new Smush_Background_Task(\n\t\t\t\t\tSmush_Background_Task::get_task_type_smush(),\n\t\t\t\t\t$image_id\n\t\t\t\t);\n\t\t\t},\n\t\t\t$to_smush\n\t\t);\n\t}\n\n\tprivate function prepare_resmush_tasks() {\n\t\t$to_resmush = $this->global_stats->get_redo_ids();\n\n\t\treturn array_map(\n\t\t\tfunction ( $image_id ) {\n\t\t\t\treturn new Smush_Background_Task(\n\t\t\t\t\tSmush_Background_Task::get_task_type_resmush(),\n\t\t\t\t\t$image_id\n\t\t\t\t);\n\t\t\t},\n\t\t\t$to_resmush\n\t\t);\n\t}\n\n\tprivate function prepare_error_tasks() {\n\t\t$error_items_to_retry = $this->global_stats->get_error_list()->get_ids();\n\n\t\treturn array_map(\n\t\t\tfunction ( $image_id ) {\n\t\t\t\treturn new Smush_Background_Task(\n\t\t\t\t\tSmush_Background_Task::get_task_type_error(),\n\t\t\t\t\t$image_id\n\t\t\t\t);\n\t\t\t},\n\t\t\t$error_items_to_retry\n\t\t);\n\t}\n\n\tpublic function localize_background_stats( $script_data, $page_slug ) {\n\t\t$script_data[': {'$action'}, 'process_actions': {'wdev_logger_action'}, 'wp-smush-ajax': {'$action'}, 'directory_smush_finish': {'directory_smush_finish'}, 'ajax_handle_track_request': {'smush_analytics_track_event'}, 'save_settings': {'smush_save_settings'}, 'ajax_track_deactivation_survey': {'smush_track_deactivate'}, 'directory_list': {'smush_get_directory_list'}, 'get_dir_smush_stats': {'get_dir_smush_stats'}, 'image_list': {'image_list'}, 'directory_smush_check_step': {'directory_smush_check_step'}, 'reset': {'reset_settings'}}
*
***/

/** Function directory_smush_start() called by wp_ajax hooks: {'directory_smush_start'} **/
/** No params detected :-/ **/


/** Function directory_smush_cancel() called by wp_ajax hooks: {'directory_smush_cancel'} **/
/** No params detected :-/ **/


/** Function ajax_skip_media_hub_connect() called by wp_ajax hooks: {'skip_media_hub_connect'} **/
/** No params detected :-/ **/


/** Function  ! is_array( $to_smush ) ) {
			$to_smush = array();
		}

		return array_map(
			function ( $image_id ) {
				return new Smush_Background_Task(
					Smush_Background_Task::get_task_type_smush(),
					$image_id
				);
			},
			$to_smush
		);
	}

	private function prepare_resmush_tasks() {
		$to_resmush = $this->global_stats->get_redo_ids();

		return array_map(
			function ( $image_id ) {
				return new Smush_Background_Task(
					Smush_Background_Task::get_task_type_resmush(),
					$image_id
				);
			},
			$to_resmush
		);
	}

	private function prepare_error_tasks() {
		$error_items_to_retry = $this->global_stats->get_error_list()->get_ids();

		return array_map(
			function ( $image_id ) {
				return new Smush_Background_Task(
					Smush_Background_Task::get_task_type_error(),
					$image_id
				);
			},
			$error_items_to_retry
		);
	}

	public function localize_background_stats( $script_data, $page_slug ) {
		$script_data[() called by wp_ajax hooks: {'$action'} **/
/** No function found :-/ **/


/** Function process_actions() called by wp_ajax hooks: {'wdev_logger_action'} **/
/** Parameters found in function process_actions(): {"request": ["log_action", "log_module"], "server": ["REQUEST_METHOD"]} **/
function process_actions() {
			// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			if (
				! isset( $_REQUEST['log_action'], $_REQUEST['log_module'], $_REQUEST[ self::NONCE_NAME ] ) ||
				! wp_verify_nonce( wp_unslash( $_REQUEST[ self::NONCE_NAME ] ), $this->get_log_action_name() )
			) {
				// Invalid action, return.
				return;
			}
			// phpcs:enable

			$action = sanitize_text_field( wp_unslash( $_REQUEST['log_action'] ) );   // Input var ok.
			$module = sanitize_text_field( wp_unslash( $_REQUEST['log_module'] ) ); // Input var ok.

			// Not called by a registered module.
			if ( ! isset( $this->modules[ $module ] ) ) {
				/* translators: %s Method name */
				wp_send_json_error( sprintf( __( 'Module %s does not exist.', 'wpmudev' ), $module ) );
			}

			// Only allow these actions.
			if ( in_array( $action, array( 'download', 'delete' ), true ) && method_exists( $this, $action ) ) {
				$should_return = isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'];
				$result        = call_user_func( array( $this, $action ), $module, $should_return );
				if ( $should_return ) {
					wp_send_json_success( $result );
				}
				exit;
			}
			/* translators: %s Method name */
			wp_send_json_error( sprintf( __( 'Method %s does not exist.', 'wpmudev' ), $action ) );
		}


/** Function wp-smush-ajax() called by wp_ajax hooks: {'$action'} **/
/** No function found :-/ **/


/** Function directory_smush_finish() called by wp_ajax hooks: {'directory_smush_finish'} **/
/** Parameters found in function directory_smush_finish(): {"post": ["items", "failed", "skipped"]} **/
function directory_smush_finish() {
		check_ajax_referer( 'wp-smush-ajax' );

		// Check for permission.
		if ( ! Helper::is_user_allowed( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'wp-smushit' ), 403 );
		}

		$items   = isset( $_POST['items'] ) ? absint( $_POST['items'] ) : 0;
		$failed  = isset( $_POST['failed'] ) ? absint( $_POST['failed'] ) : 0;
		$skipped = isset( $_POST['skipped'] ) ? absint( $_POST['skipped'] ) : 0;

		// If any images failed to smush, store count.
		if ( $failed > 0 ) {
			set_transient( 'wp-smush-dir-scan-failed-items', $failed, 60 * 5 ); // 5 minutes max.
		}

		if ( $skipped > 0 ) {
			set_transient( 'wp-smush-dir-scan-skipped-items', $skipped, 60 * 5 ); // 5 minutes max.
		}

		// Store optimized items count.
		set_transient( 'wp-smush-show-dir-scan-notice', $items, 60 * 5 ); // 5 minutes max.
		$this->scanner->reset_scan();
		wp_send_json_success();
	}


/** Function ajax_handle_track_request() called by wp_ajax hooks: {'smush_analytics_track_event'} **/
/** No params detected :-/ **/


/** Function save_settings() called by wp_ajax hooks: {'smush_save_settings'} **/
/** No params detected :-/ **/


/** Function ajax_track_deactivation_survey() called by wp_ajax hooks: {'smush_track_deactivate'} **/
/** No params detected :-/ **/


/** Function directory_list() called by wp_ajax hooks: {'smush_get_directory_list'} **/
/** No params detected :-/ **/


/** Function get_dir_smush_stats() called by wp_ajax hooks: {'get_dir_smush_stats'} **/
/** No params detected :-/ **/


/** Function image_list() called by wp_ajax hooks: {'image_list'} **/
/** Parameters found in function image_list(): {"post": ["smush_path"]} **/
function image_list() {
		// Check For permission.
		if ( ! current_user_can( 'manage_options' ) ) {
			$this->send_error( __( 'Unauthorized', 'wp-smushit' ) );
		}

		// Verify nonce.
		check_ajax_referer( 'wp-smush-ajax', '_ajax_nonce' );

		// Check if directory path is set or not.
		if ( empty( $_POST['smush_path'] ) ) { // Input var ok.
			$this->send_error( __( 'Empty Directory Path', 'wp-smushit' ) );
		}

		// FILTER_SANITIZE_URL is trimming the space if a folder contains space.
		$smush_path = filter_input( INPUT_POST, 'smush_path', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY );

		try {
			// This will add the images to the database and get the file list.
			$files = $this->get_image_list( $smush_path );
		} catch ( Exception $e ) {
			$this->send_error( $e->getMessage() );
		}

		// If files array is empty, send a message.
		if ( empty( $files ) ) {
			$this->send_error( __( 'We could not find any images in the selected directory.', 'wp-smushit' ) );
		}

		// Clear cache.
		wp_cache_delete( 'wp-smush-dir_total_stats', 'wp-smush' );

		// Send response.
		wp_send_json_success( count( $files ) );
	}


/** Function directory_smush_check_step() called by wp_ajax hooks: {'directory_smush_check_step'} **/
/** Parameters found in function directory_smush_check_step(): {"post": ["step"]} **/
function directory_smush_check_step() {
		check_ajax_referer( 'wp-smush-ajax' );

		// Check for permission.
		if ( ! Helper::is_user_allowed( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'wp-smushit' ), 403 );
		}

		$urls         = $this->get_scanned_images();
		$current_step = isset( $_POST['step'] ) ? absint( $_POST['step'] ) : 0;

		$this->scanner->update_current_step( $current_step );

		if ( isset( $urls[ $current_step ] ) ) {
			$this->optimise_image( (int) $urls[ $current_step ]['id'] );
		}

		wp_send_json_success();
	}


/** Function reset() called by wp_ajax hooks: {'reset_settings'} **/
/** No params detected :-/ **/


