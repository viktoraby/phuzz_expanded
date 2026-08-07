<?php
/***
*
*Found actions: 17
*Found functions:17
*Extracted functions:17
*Total parameter names extracted: 5
*Overview: {'getActivePackageStatus': {'DUP_CTRL_Package_getActivePackageStatus'}, 'admin_notice_to_dismiss': {'duplicator_admin_notice_to_dismiss'}, 'duplicator_package_scan': {'duplicator_package_scan'}, 'addQuickFilters': {'DUP_CTRL_Package_addQuickFilters'}, 'duplicator_package_build': {'duplicator_package_build'}, 'runScanValidator': {'DUP_CTRL_Tools_runScanValidator'}, 'dismissNoticeBar': {'duplicator_notice_bar_dismiss'}, 'duplicator_submit_uninstall_reason_action': {'duplicator_submit_uninstall_reason_action'}, 'SaveViewState': {'DUP_CTRL_UI_SaveViewState'}, 'duplicator_active_package_info': {'duplicator_active_package_info'}, 'dismissAjax': {'dup_notice_dismiss'}, 'duplicator_package_delete': {'duplicator_package_delete'}, 'ajax_reset_all': {'duplicator_reset_all_settings'}, 'set_admin_notice_viewed': {'duplicator_set_admin_notice_viewed'}, 'duplicator_duparchive_package_build': {'duplicator_duparchive_package_build'}, 'duplicator_download_installer': {'duplicator_download_installer'}, 'getTraceLog': {'DUP_CTRL_Tools_getTraceLog'}}
*
***/

/** Function getActivePackageStatus() called by wp_ajax hooks: {'DUP_CTRL_Package_getActivePackageStatus'} **/
/** No params detected :-/ **/


/** Function admin_notice_to_dismiss() called by wp_ajax hooks: {'duplicator_admin_notice_to_dismiss'} **/
/** No params detected :-/ **/


/** Function duplicator_package_scan() called by wp_ajax hooks: {'duplicator_package_scan'} **/
/** No params detected :-/ **/


/** Function addQuickFilters() called by wp_ajax hooks: {'DUP_CTRL_Package_addQuickFilters'} **/
/** Parameters found in function addQuickFilters(): {"post": ["dir_paths", "file_paths"]} **/
function addQuickFilters()
    {
        DUP_Handler::init_error_handler();
        check_ajax_referer('DUP_CTRL_Package_addQuickFilters', 'nonce');
        $result    = new DUP_CTRL_Result($this);
        $inputData = array(
            'dir_paths'  => sanitize_textarea_field($_POST['dir_paths'] ?? ''),
            'file_paths' => sanitize_textarea_field($_POST['file_paths'] ?? '')
        );
        try {
            DUP_Util::hasCapability('export', DUP_Util::SECURE_ISSUE_THROW);
            //CONTROLLER LOGIC
            $package = DUP_Package::getActive();
            //DIRS
            $dir_filters = ($package->Archive->FilterOn && strlen($package->Archive->FilterDirs) > 0)
                ? $package->Archive->FilterDirs . ';' . $inputData['dir_paths'] : $inputData['dir_paths'];
            $dir_filters = $package->Archive->parseDirectoryFilter($dir_filters);
            $changed     = $package->Archive->saveActiveItem($package, 'FilterDirs', $dir_filters);
            //FILES
            $file_filters = ($package->Archive->FilterOn && strlen($package->Archive->FilterFiles) > 0)
                ? $package->Archive->FilterFiles . ';' . $inputData['file_paths'] : $inputData['file_paths'];
            $file_filters = $package->Archive->parseFileFilter($file_filters);
            $changed      = $package->Archive->saveActiveItem($package, 'FilterFiles', $file_filters);
            if (!$package->Archive->FilterOn && !empty($package->Archive->FilterExts)) {
                $changed = $package->Archive->saveActiveItem($package, 'FilterExts', '');
            }

            $changed = $package->Archive->saveActiveItem($package, 'FilterOn', 1);
            //Result
            $package              = DUP_Package::getActive();
            $payload['dirs-in']   = esc_html(sanitize_text_field($inputData['dir_paths']));
            $payload['dir-out']   = esc_html($package->Archive->FilterDirs);
            $payload['files-in']  = esc_html(sanitize_text_field($inputData['file_paths']));
            $payload['files-out'] = esc_html($package->Archive->FilterFiles);
            //RETURN RESULT
            $test = ($changed) ? DUP_CTRL_Status::SUCCESS : DUP_CTRL_Status::FAILED;
            $result->process($payload, $test);
        } catch (Exception $exc) {
            $result->processError($exc);
        }
    }


/** Function duplicator_package_build() called by wp_ajax hooks: {'duplicator_package_build'} **/
/** No params detected :-/ **/


/** Function runScanValidator() called by wp_ajax hooks: {'DUP_CTRL_Tools_runScanValidator'} **/
/** No params detected :-/ **/


/** Function dismissNoticeBar() called by wp_ajax hooks: {'duplicator_notice_bar_dismiss'} **/
/** No params detected :-/ **/


/** Function duplicator_submit_uninstall_reason_action() called by wp_ajax hooks: {'duplicator_submit_uninstall_reason_action'} **/
/** Parameters found in function duplicator_submit_uninstall_reason_action(): {"post": ["duplicator_ajax_nonce"], "request": ["reason_info"]} **/
function duplicator_submit_uninstall_reason_action()
    {
        DUP_Handler::init_error_handler();

        $reason_id = SnapUtil::sanitizeTextInput(SnapUtil::INPUT_REQUEST, 'reason_id', false);
        $basename  = SnapUtil::sanitizeTextInput(SnapUtil::INPUT_REQUEST, 'plugin', false);

        try {
            if (!wp_verify_nonce($_POST['duplicator_ajax_nonce'], 'duplicator_ajax_nonce')) {
                throw new Exception(__('Security issue', 'duplicator'));
            }

            DUP_Util::hasCapability('export', DUP_Util::SECURE_ISSUE_THROW);
            if (!$reason_id || !$basename) {
                throw new Exception(__('Invalid Request.', 'duplicator'));
            }

            $reason_info = isset($_REQUEST['reason_info']) ? stripcslashes(esc_html($_REQUEST['reason_info'])) : '';
            if (!empty($reason_info)) {
                $reason_info = substr($reason_info, 0, 255);
            }

            $options = array(
                'product' => $basename,
                'reason_id' => $reason_id,
                'reason_info' => $reason_info,
            );

            /* send data */
            $raw_response = wp_remote_post(
                'https://snapcreekanalytics.com/wp-content/plugins/duplicator-statistics-plugin/deactivation-feedback/',
                array(
                    'method' => 'POST',
                    'body' => $options,
                    'timeout' => 15,
                    // 'sslverify' => FALSE
                )
            );
            if (!is_wp_error($raw_response) && 200 == wp_remote_retrieve_response_code($raw_response)) {
                echo 'done';
            } else {
                $error_msg = $raw_response->get_error_code() . ': ' . $raw_response->get_error_message();
                SnapUtil::errorLog($error_msg);
                throw new Exception($error_msg);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        exit;
    }


/** Function SaveViewState() called by wp_ajax hooks: {'DUP_CTRL_UI_SaveViewState'} **/
/** Parameters found in function SaveViewState(): {"post": ["states"]} **/
function SaveViewState()
    {
        DUP_Handler::init_error_handler();
        check_ajax_referer('DUP_CTRL_UI_SaveViewState', 'nonce');
        DUP_Util::hasCapability('export');

        $payload = array(
            'success' => false,
            'message' => '',
            'key'     => '',
            'value'   => ''
        );

        $isValid = true;
        $states  = false;
        if (isset($_POST['states'])) {
            $states = is_scalar($_POST['states']) ? [$_POST['states']] : $_POST['states'];
        }

        $mainKey   = SnapUtil::sanitizeTextInput(INPUT_POST, 'key', false);
        $mainValue = SnapUtil::sanitizeTextInput(INPUT_POST, 'value', false);

        if ($states !== false) {
            foreach ($states as $index => $state) {
                $key   = isset($state['key']) ? SnapUtil::sanitizeNSCharsNewline($state['key']) : false;
                $value = isset($state['value']) ? SnapUtil::sanitizeNSCharsNewline($state['value']) : false;

                if ($key == false && $value == false) {
                    $isValid = false;
                    break;
                }

                $states[$index] = [
                    'key'   => $key,
                    'value' => $value,
                ];
            }
        }

        if ($states === false && ($mainKey === false || $mainValue === false)) {
            $isValid = false;
        }

        $result = new DUP_CTRL_Result($this);
        try {
            if (!$isValid) {
                throw new Exception(__('Invalid Request.', 'duplicator'));
            }

            if ($states !== false && count($states) > 0) {
                $view_state = DUP_UI_ViewState::getArray();
                $last_key   = '';
                foreach ($states as $state) {
                    $view_state[$state['key']] = $state['value'];
                    $last_key                  = $state['key'];
                }
                $payload['success'] = DUP_UI_ViewState::setArray($view_state);
                $payload['key']     = esc_html($last_key);
                $payload['value']   = esc_html($view_state[$last_key]);
            } else {
                $payload['success'] = DUP_UI_ViewState::save($mainKey, $mainValue);
                $payload['key']     = esc_html($mainKey);
                $payload['value']   = esc_html($mainValue);
            }

            //RETURN RESULT
            $test = ($payload['success'])
                ? DUP_CTRL_Status::SUCCESS
                : DUP_CTRL_Status::FAILED;
            return $result->process($payload, $test);
        } catch (Exception $exc) {
            $result->processError($exc);
        }
    }


/** Function duplicator_active_package_info() called by wp_ajax hooks: {'duplicator_active_package_info'} **/
/** No params detected :-/ **/


/** Function dismissAjax() called by wp_ajax hooks: {'dup_notice_dismiss'} **/
/** No params detected :-/ **/


/** Function duplicator_package_delete() called by wp_ajax hooks: {'duplicator_package_delete'} **/
/** No params detected :-/ **/


/** Function ajax_reset_all() called by wp_ajax hooks: {'duplicator_reset_all_settings'} **/
/** No params detected :-/ **/


/** Function set_admin_notice_viewed() called by wp_ajax hooks: {'duplicator_set_admin_notice_viewed'} **/
/** Parameters found in function set_admin_notice_viewed(): {"request": ["nonce"]} **/
function set_admin_notice_viewed()
    {
        DUP_Handler::init_error_handler();

        try {
            DUP_Util::hasCapability('export', DUP_Util::SECURE_ISSUE_THROW);

            if (!wp_verify_nonce($_REQUEST['nonce'], 'duplicator_set_admin_notice_viewed')) {
                DUP_Log::trace(__('Security issue', 'duplicator'));
                throw new Exception('Security issue');
            }

            $notice_id = SnapUtil::sanitizeTextInput(SnapUtil::INPUT_REQUEST, 'notice_id', false);

            if ($notice_id === false) {
                throw new Exception(__('Invalid Request', 'duplicator'));
            }

            $notices = get_user_meta(get_current_user_id(), DUPLICATOR_ADMIN_NOTICES_USER_META_KEY, true);
            if (empty($notices)) {
                $notices = array();
            }

            if (!isset($notices[$notice_id])) {
                throw new Exception(__("Notice with that ID doesn't exist.", 'duplicator'));
            }

            $notices[$notice_id] = 'true';
            update_user_meta(get_current_user_id(), DUPLICATOR_ADMIN_NOTICES_USER_META_KEY, $notices);
        } catch (Exception $ex) {
            wp_die($ex->getMessage());
        }
    }


/** Function duplicator_duparchive_package_build() called by wp_ajax hooks: {'duplicator_duparchive_package_build'} **/
/** No params detected :-/ **/


/** Function duplicator_download_installer() called by wp_ajax hooks: {'duplicator_download_installer'} **/
/** No params detected :-/ **/


/** Function getTraceLog() called by wp_ajax hooks: {'DUP_CTRL_Tools_getTraceLog'} **/
/** No params detected :-/ **/


