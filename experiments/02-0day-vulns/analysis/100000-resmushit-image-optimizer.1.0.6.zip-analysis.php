<?php
/***
*
*Found actions: 9
*Found functions:9
*Extracted functions:9
*Total parameter names extracted: 14
*Overview: {'optimize_single_attachment': {'resmushit_optimize_single_attachment'}, 'remove_backup_files': {'resmushit_remove_backup_files'}, 'restore_single_attachment': {'resmushit_restore_single_attachment'}, 'resmushit_notice_close': {'resmushit_notice_close'}, 'bulk_get_images': {'resmushit_bulk_get_images'}, 'restore_backup_files': {'resmushit_restore_backup_files'}, 'bulk_process_image': {'resmushit_bulk_process_image'}, 'update_statistics': {'resmushit_update_statistics'}, 'update_disabled_state': {'resmushit_update_disabled_state'}}
*
***/

/** Function optimize_single_attachment() called by wp_ajax hooks: {'resmushit_optimize_single_attachment'} **/
/** Parameters found in function optimize_single_attachment(): {"request": ["data"], "post": ["data"]} **/
function optimize_single_attachment() {
  	if ( !isset($_REQUEST['data']['csrf']) || ! wp_verify_nonce( $_REQUEST['data']['csrf'], 'single_attachment' ) ) {
  		wp_send_json(json_encode(array('error' => 'Invalid CSRF token')));
  		die();
  	}
  	if(!is_super_admin() && !current_user_can('administrator')) {
		wp_send_json(json_encode(array('error' => 'The user must be an administrator to retrieve this data')));
  		die();
  	}
  	if(isset($_POST['data']['id']) && $_POST['data']['id'] != null){
  		reSmushit::revert(sanitize_text_field((int)$_POST['data']['id']));
  		wp_send_json(json_encode(reSmushit::getStatistics(sanitize_text_field((int)$_POST['data']['id']))));
  	}
  	die();
  }


/** Function remove_backup_files() called by wp_ajax hooks: {'resmushit_remove_backup_files'} **/
/** Parameters found in function remove_backup_files(): {"request": ["csrf"]} **/
function remove_backup_files() {
  	$return = array('success' => 0);
  	if ( !isset($_REQUEST['csrf']) || ! wp_verify_nonce( $_REQUEST['csrf'], 'remove_backup' ) ) {
  		wp_send_json(json_encode(array('error' => 'Invalid CSRF token')));
  		die();
  	}
  	if(!is_super_admin() && !current_user_can('administrator')) {
		wp_send_json(json_encode(array('error' => 'The user must be an administrator to retrieve this data')));
  		die();
  	}

  	$files= reSmushit::detect_unsmushed_files();

  	foreach($files as $f) {
  		if(unlink($f)) {
  			$return['success']++;
  		}
  	}
  	update_option( 'resmushit_has_no_backup_files', 1);
  	wp_send_json(json_encode($return));

  	die();
  }


/** Function restore_single_attachment() called by wp_ajax hooks: {'resmushit_restore_single_attachment'} **/
/** Parameters found in function restore_single_attachment(): {"request": ["data"], "post": ["data"]} **/
function restore_single_attachment() {
  	if ( !isset($_REQUEST['data']['csrf']) || ! wp_verify_nonce( $_REQUEST['data']['csrf'], 'single_attachment' ) ) {
  		wp_send_json(json_encode(array('error' => 'Invalid CSRF token')));
  		die();
  	}
  	if(!is_super_admin() && !current_user_can('administrator')) {
		wp_send_json(json_encode(array('error' => 'The user must be an administrator to retrieve this data')));
  		die();
  	}
    $processController = ProcessController::getInstance();
    $processController->unHookProcessor();


  	if(isset($_POST['data']['id']) && $_POST['data']['id'] != null){
  		reSmushit::revert(sanitize_text_field((int)$_POST['data']['id']));

      $response = array('status' => true, 'message' => __('Image restored!', 'resmushit-image-optimizer'));
  		wp_send_json($response);
  	}
  	die();
  }


/** Function resmushit_notice_close() called by wp_ajax hooks: {'resmushit_notice_close'} **/
/** Parameters found in function resmushit_notice_close(): {"request": ["csrf"]} **/
function resmushit_notice_close() {
  $return = FALSE;
  if ( !isset($_REQUEST['csrf']) || ! wp_verify_nonce( $_REQUEST['csrf'], 'notice_close' ) ) {
    wp_send_json(json_encode(array('error' => 'Invalid CSRF token')));
    die();
  }
  if(!is_super_admin() && !current_user_can('administrator')) {
    wp_send_json(json_encode(array('error' => 'User must be at least administrator to retrieve these data')));
    die();
  }
  if(update_option( 'resmushit_notice_close_eoldec23', 1 )) {
    $return = TRUE;
  }
  wp_send_json(json_encode(array('status' => $return)));
  die();
}


/** Function bulk_get_images() called by wp_ajax hooks: {'resmushit_bulk_get_images'} **/
/** Parameters found in function bulk_get_images(): {"request": ["csrf"]} **/
function bulk_get_images() {
  	if ( !isset($_REQUEST['csrf']) || ! wp_verify_nonce( $_REQUEST['csrf'], 'bulk_resize' ) ) {
  		wp_send_json(json_encode(array('error' => 'Invalid CSRF token')));
  		die();
  	}
  	if(!is_super_admin() && !current_user_can('administrator')) {
		wp_send_json(json_encode(array('error' => 'The user must be an administrator to retrieve this data')));
  		die();
  	}
  	wp_send_json(reSmushit::getNonOptimizedPictures());
  	die();
  }


/** Function restore_backup_files() called by wp_ajax hooks: {'resmushit_restore_backup_files'} **/
/** Parameters found in function restore_backup_files(): {"request": ["csrf"], "post": ["count_only", "batch"]} **/
function restore_backup_files() {
  	if ( !isset($_REQUEST['csrf']) || ! wp_verify_nonce( $_REQUEST['csrf'], 'restore_library' ) ) {
  		wp_send_json(array('error' => 'Invalid CSRF token'));
  	}
  	if(!is_super_admin() && !current_user_can('administrator')) {
		wp_send_json(array('error' => 'The user must be an administrator to retrieve this data'));
  	}

  	$count_only = !empty($_POST['count_only']);
  	$batch = isset($_POST['batch']) ? max(1, min(20, (int)$_POST['batch'])) : 3;

  	$wp_upload_dir = wp_upload_dir();
  	$basedir = $wp_upload_dir['basedir'];

  	$files = reSmushit::detect_unsmushed_files();

  	// Filter to only restorable backups (those whose attachment still exists). Orphan
  	// `-unsmushed` files left over from deleted attachments are excluded so the count
  	// the user sees matches what will actually be restored.
  	$restorable = array();
  	foreach ($files as $f) {
  		if (false !== $this->find_attachment_id_for_backup($f, $basedir)) {
  			$restorable[] = $f;
  		}
  	}

  	if ($count_only) {
  		wp_send_json(array('total' => count($restorable), 'remaining' => count($restorable)));
  	}

  	$processController = ProcessController::getInstance();
  	$processController->unHookProcessor();

  	$success = 0;
  	$processed = 0;
  	$skipped  = 0;

  	foreach($restorable as $f) {
  		if ($processed >= $batch) break;
  		$processed++;

  		$attachment_id = $this->find_attachment_id_for_backup($f, $basedir);

  		if (false === $attachment_id) {
  			// Race: the attachment was deleted between the count and the loop. Skip.
  			Log::addWarn('Restoring - no attachmentID for backup file '. $f);
  			$skipped++;
  			continue;
  		}

  		$result = reSmushit::revert($attachment_id, true);
  		// revert() returns a status string from wasSuccessfullyUpdated() — 'success', 'failed',
  		// 'file_too_big', 'file_not_found', 'disabled'. Treat anything other than the status
  		// strings indicating a missing/disabled attachment as a successful restore: the backup
  		// has already been moved over the live file by this point.
  		if ($result && $result !== 'file_not_found' && $result !== 'disabled') {
  			if (file_exists($f)) {
  				@unlink($f);
  			}
  			$success++;
  		} else {
  			$skipped++;
  		}
  	}

  	// Recompute remaining restorable count after this batch.
  	$remaining_files = reSmushit::detect_unsmushed_files();
  	$remaining = 0;
  	foreach ($remaining_files as $f) {
  		if (false !== $this->find_attachment_id_for_backup($f, $basedir)) {
  			$remaining++;
  		}
  	}

  	wp_send_json(array(
  		'success'   => $success,
  		'processed' => $processed,
  		'skipped'   => $skipped,
  		'remaining' => $remaining,
  	));
  }


/** Function bulk_process_image() called by wp_ajax hooks: {'resmushit_bulk_process_image'} **/
/** Parameters found in function bulk_process_image(): {"request": ["csrf"], "post": ["data"]} **/
function bulk_process_image() {
  	if ( !isset($_REQUEST['csrf']) || ! wp_verify_nonce( $_REQUEST['csrf'], 'bulk_process_image' ) ) {
  		wp_send_json(json_encode(array('error' => 'Invalid CSRF token')));
  		die();
  	}
  	if(!is_super_admin() && !current_user_can('administrator')) {
		wp_send_json(json_encode(array('error' => 'The user must be an administrator to retrieve this data')));
  		die();
  	}
    Log::addInfo('Bulk optimization launched for file : ' . get_attached_file( sanitize_text_field((int)$_POST['data']['ID']) ));
  	echo esc_html(reSmushit::revert(sanitize_text_field((int)$_POST['data']['ID'])));
  	die();
  }


/** Function update_statistics() called by wp_ajax hooks: {'resmushit_update_statistics'} **/
/** Parameters found in function update_statistics(): {"request": ["csrf"]} **/
function update_statistics() {
  	if ( !isset($_REQUEST['csrf']) || ! wp_verify_nonce( $_REQUEST['csrf'], 'bulk_process_image' ) ) {
  		wp_send_json(json_encode(array('error' => 'Invalid CSRF token')));
  		die();
  	}
  	if(!is_super_admin() && !current_user_can('administrator')) {
		wp_send_json(json_encode(array('error' => 'The user must be an administrator to retrieve this data')));
  		die();
  	}
  	$output = reSmushit::getStatistics();
  	$output['total_saved_size_formatted'] = reSmushitUI::sizeFormat($output['total_saved_size']);
  	wp_send_json(json_encode($output));
  	die();
  }


/** Function update_disabled_state() called by wp_ajax hooks: {'resmushit_update_disabled_state'} **/
/** Parameters found in function update_disabled_state(): {"request": ["data"], "post": ["data"]} **/
function update_disabled_state() {
  	if ( !isset($_REQUEST['data']['csrf']) || ! wp_verify_nonce( $_REQUEST['data']['csrf'], 'single_attachment' ) ) {
  		wp_send_json(json_encode(array('error' => 'Invalid CSRF token')));
  		die();
  	}
  	if(!is_super_admin() && !current_user_can('administrator')) {
		wp_send_json(json_encode(array('error' => 'The user must be an administrator to retrieve this data')));
  		die();
  	}
  	if(isset($_POST['data']['id']) && $_POST['data']['id'] != null && isset($_POST['data']['disabled'])){
  		echo wp_kses_post(reSmushit::updateDisabledState(sanitize_text_field((int)$_POST['data']['id']), sanitize_text_field($_POST['data']['disabled'])));
  	}
  	die();
  }


