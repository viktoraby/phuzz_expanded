<?php
/***
*
*Found actions: 7
*Found functions:7
*Extracted functions:7
*Total parameter names extracted: 4
*Overview: {'ajax_checkquota': {'shortpixel_check_quota'}, 'ajax_getBackupFolderSize': {'shortpixel_get_backup_size'}, 'deactivatePluginCallback': {'shortpixel_deactivate_plugin'}, 'settingsRequest': {'shortpixel_settingsRequest'}, 'ajax_proposeQuotaUpgrade': {'shortpixel_propose_upgrade'}, 'ajax_processQueue': {'shortpixel_image_processing'}, 'ajaxRequest': {'shortpixel_ajaxRequest'}}
*
***/

/** Function ajax_checkquota() called by wp_ajax hooks: {'shortpixel_check_quota'} **/
/** No params detected :-/ **/


/** Function ajax_getBackupFolderSize() called by wp_ajax hooks: {'shortpixel_get_backup_size'} **/
/** No params detected :-/ **/


/** Function deactivatePluginCallback() called by wp_ajax hooks: {'shortpixel_deactivate_plugin'} **/
/** Parameters found in function deactivatePluginCallback(): {"post": ["reason", "details", "anonymous"]} **/
function deactivatePluginCallback() {

        check_ajax_referer( 'shortpixel_deactivate_plugin', 'security' );


				Log::addDebug('Deactive Plugin Callback POST', $_POST);

        if ( isset($_POST['reason']) && isset($_POST['details']) && isset($_POST['anonymous']) ) {
            require_once(\WPSPIO()->plugin_path() . 'class/view/shortpixel-plugin-request.php');
            $anonymous = (intval($_POST['anonymous']) == 1) ? true : false;
            $args = array(
                'key' =>  $this->key,
                'reason' => sanitize_text_field(wp_unslash($_POST['reason'])),
                'details' => sanitize_text_field(wp_unslash($_POST['details'])),
                'anonymous' => $anonymous
            );
            $request = new ShortPixelPluginRequest( $this->plugin_file, 'http://' . SHORTPIXEL_API . '/v2/feedback.php', $args );
            if ( $request->request_successful ) {
                echo json_encode( array(
                    'status' => 'ok',
                ) );
            }else{
                echo json_encode( array(
                    'status' => 'nok',
                ) );
            }
        }else{
            echo json_encode( array(
                'status' => 'OK',
            ) );
        }

        die();

    }


/** Function settingsRequest() called by wp_ajax hooks: {'shortpixel_settingsRequest'} **/
/** Parameters found in function settingsRequest(): {"post": ["screen_action"]} **/
function settingsRequest()
	{
		$this->checkNonce('settings_request');
		ErrorController::start(); // Capture fatal errors for us.

		$action = isset($_POST['screen_action']) ? sanitize_text_field($_POST['screen_action']) : false;

		$this->checkActionAccess($action, 'is_admin_user');

		switch ($action) {
			case 'form_submit':
			case 'action_addkey':
			case 'action_debug_redirectBulk':
			case 'action_debug_removePrevented':
			case 'action_debug_removeProcessorKey':
			case 'action_debug_resetNotices':
			case 'action_debug_resetQueue':
			case 'action_debug_resetquota':
			case 'action_debug_resetStats':
			case 'action_debug_triggerNotice':
			case 'action_request_new_key':
			case 'action_debug_editSetting':
			case 'action_end_quick_tour':
				$this->settingsFormSubmit($action);
				break;
			default:

				Log::addError('Issue with settingsRequest, not valid action');
				exit('0');
				break;
		}
	}


/** Function ajax_proposeQuotaUpgrade() called by wp_ajax hooks: {'shortpixel_propose_upgrade'} **/
/** No params detected :-/ **/


/** Function ajax_processQueue() called by wp_ajax hooks: {'shortpixel_image_processing'} **/
/** Parameters found in function ajax_processQueue(): {"post": ["isBulk", "queues"]} **/
function ajax_processQueue()
	{
		$this->checkNonce('processing');
		$this->checkActionAccess('processQueue', 'is_author');
		$this->checkProcessorKey();

		ErrorController::start(); // Capture fatal errors for us.

		// Notice that POST variables are always string, so 'true', not true.
		// phpcs:ignore -- Nonce is checked
		$isBulk = (isset($_POST['isBulk']) && $_POST['isBulk'] == 'true') ? true : false;
		// phpcs:ignore -- Nonce is checked
		$queue = (isset($_POST['queues'])) ? sanitize_text_field($_POST['queues']) : 'media,custom';

		$queues = array_filter(explode(',', $queue), 'trim');

		$control = new QueueController(['is_bulk' => $isBulk]);
		$result = $control->processQueue($queues);

		$this->send($result);
	}


/** Function ajaxRequest() called by wp_ajax hooks: {'shortpixel_ajaxRequest'} **/
/** Parameters found in function ajaxRequest(): {"post": ["screen_action", "type", "id", "loadFile"]} **/
function ajaxRequest()
	{
		$this->checkNonce('ajax_request');
		ErrorController::start(); // Capture fatal errors for us.

		$this->checkActionAccess('ajax', 'is_author');

		// phpcs:ignore -- Nonce is checked
		$action = isset($_POST['screen_action']) ? sanitize_text_field($_POST['screen_action']) : false;
		// phpcs:ignore -- Nonce is checked
		$typeArray = isset($_POST['type'])  ? array(sanitize_text_field($_POST['type'])) : array('media', 'custom');
		// phpcs:ignore -- Nonce is checked
		$id = isset($_POST['id']) ? intval($_POST['id']) : false;

		$json = new \stdClass;
		foreach ($typeArray as $type) {
			$json->$type = new \stdClass;
			//      $json->$type->id = $id;  // This one is off because item_id belongs to results -> itemResult.
			$json->$type->results = null;
			//      $json->$type->is_error = false;
			$json->status = false;
		}

		$data = array('id' => $id, 'typeArray' => $typeArray, 'action' => $action);

		if (count($typeArray) == 1) // Actions which need specific type like optimize / restore.
		{
			$data['type'] = $typeArray[0];
			unset($data['typeArray']);
		}

		// First Item action,  alphabet.  Second general actions, alpha.
		switch ($action) {
			case 'cancelOptimize':
				$json = $this->cancelOptimize($json, $data);
				break;
			case 'getItemEditWarning': // Has to do with image editor
				$json = $this->getItemEditWarning($json, $data);
				break;
			case 'getComparerData':
				$json = $this->getComparerData($json, $data);
				break;
			case 'getItemView':
				$json = $this->getItemView($json, $data);
				break;
			case 'markCompleted':
				$json = $this->markCompleted($json, $data);
				break;
			case 'optimizeItem':
				$json = $this->optimizeItem($json, $data);
				break;
			case "redoLegacy":
				$this->redoLegacy($json, $data);
				break;
			case 'restoreItem':
				$json = $this->restoreItem($json, $data);
				break;
			case 'reOptimizeItem':
				$json = $this->reOptimizeItem($json, $data);
				break;
			case 'settings/purgecdncache': 
				$json = $this->purgeCDNCache($json, $data); 
			break;
			case 'settings/importexport':
				$json = $this->importexportSettings($json, $data);
			break; 
			case 'ai/requestalt': 
				$json = $this->requestAlt($json, $data);	
			break; 
			case 'ai/getAltData': 
				$json = $this->getAltData($json, $data);
			break; 
			case 'ai/undoAlt':
				$json = $this->undoAltData($json, $data);			
			break;
			case 'unMarkCompleted':
				$json = $this->unMarkCompleted($json, $data);
				break;
			case 'applyBulkSelection':
				$this->checkActionAccess($action, 'is_editor');
				$json = $this->applyBulkSelection($json, $data);
				break;
			case 'createBulk':
				$this->checkActionAccess($action, 'is_editor');
				$json = $this->createBulk($json, $data);
				break;
			case 'finishBulk':
				$this->checkActionAccess($action, 'is_editor');
				$json = $this->finishBulk($json, $data);
				break;
			case 'startBulk':
				$this->checkActionAccess($action, 'is_editor');
				$json = $this->startBulk($json, $data);
				break;
			case 'startRestoreAll':
				$this->checkActionAccess($action, 'is_admin_user');
				$json = $this->startRestoreAll($json, $data);
				break;
			case 'startBulkUndoAI':
				$this->checkActionAccess($action, 'is_admin_user');
				$json = $this->startUndoAI($json, $data);
				break;
			case 'startMigrateAll':
				$this->checkActionAccess($action, 'is_admin_user');
				$json = $this->startMigrateAll($json, $data);
				break;
			case 'startRemoveLegacy':
				$this->checkActionAccess($action, 'is_admin_user');
				$json = $this->startRemoveLegacy($json, $data);
				break;
			case "toolsRemoveAll":
				$this->checkActionAccess($action, 'is_admin_user');
				$json = $this->removeAllData($json, $data);
				break;
			case "toolsRemoveBackup":
				$this->checkActionAccess($action, 'is_admin_user');
				$json = $this->removeBackup($json, $data);
				break;
			case 'request_new_api_key': // @todo Dunnoo why empty, should go if not here.

			break;
			case "loadLogFile":
				$this->checkActionAccess($action, 'is_editor');
				$data['logFile'] = isset($_POST['loadFile']) ? sanitize_text_field($_POST['loadFile']) : null;
				$json = $this->loadLogFile($json, $data);
				break;

			case 'refreshFolder':
				$this->checkActionAccess($action, 'is_editor');
				$json = $this->refreshFolder($json, $data);
				break;
			// CUSTOM FOLDERS
			case 'removeCustomFolder':
				$this->checkActionAccess($action, 'is_editor');
				$json = $this->removeCustomFolder($json, $data);
				break;
			case 'browseFolders':
				$this->checkActionAccess($action, 'is_editor');
				$json = $this->browseFolders($json, $data);
				break;
			case 'addCustomFolder':
				$this->checkActionAccess($action, 'is_editor');
				$json = $this->addCustomFolder($json, $data);
				break;
			case 'scanNextFolder':
				$this->checkActionAccess($action, 'is_editor');
				$json = $this->scanNextFolder($json, $data);
				break;
			case 'resetScanFolderChecked':
				$this->checkActionAccess($action, 'is_editor');
				$json = $this->resetScanFolderChecked($json, $data);
				break;
			case 'recheckActive':
				$this->checkActionAccess($action, 'is_editor');
				$json = $this->recheckActive($json, $data);
				break;
			case 'settings/changemode':
				$this->handleChangeMode($data);
				break;
			case 'settings/getAiExample': 
				$this->checkActionAccess($action, 'is_admin_user');
				$this->getSettingsAiExample($data);
			break; 
			case 'settings/setAiImageId': 
				$this->checkActionAccess($action, 'is_admin_user');
				$this->setSettingsAiImage($data);
			break; 
			case 'settings/getNewAiImagePreview': 
				$this->getNewAiImagePreview($data);
			break;
			case 'media/getEditorPopup': 
				$this->getEditorPopup($data);
			break; 
			case 'media/getEditorPreview': 
				$this->getEditorPreview($data);
			break;
			default:
				$json->$type->message = __('Ajaxrequest - no action found', 'shorpixel-image-optimiser');
				$json->error = self::NO_ACTION;
			break;
		}
		$this->send($json);
	}


