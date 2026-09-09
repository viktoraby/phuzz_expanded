<?php
/***
*
*Found actions: 130
*Found functions:92
*Extracted functions:91
*Total parameter names extracted: 35
*Overview: {'ajaxQueuedBackupStatus': {'wpstg_onboarding_backup_status'}, 'ajaxCheckCloneDirectoryName': {'wpstg_check_clone'}, 'save': {'wpstg--backup-before-update--save-mode'}, 'getPluginUpdateVersionInfo': {'wpstg--backup-before-update--plugin-versions'}, 'ajaxPushScan': {'wpstg_scan'}, 'ajaxCancelClone': {'wpstg_cancel_clone'}, 'abandonActionOnRequest': {'wpstg_cancel_clone', 'wpstg--job--cancel'}, 'ajaxEnableStagingCloning': {'wpstg_enable_staging_cloning'}, 'ajaxCleanProCrons': {'wpstg_clean_pro_crons'}, 'ajaxActionStarted': {'wpstg_onboarding_action_started'}, 'ajaxHideBeta': {'wpstg_hide_beta'}, 'ajaxCliNoticeClose': {'wpstg_cli_notice_close'}, 'listDirectoryFiles': {'wpstg--backups--explore-select-directory'}, 'ajaxRestoreSettings': {'wpstg_restore_settings'}, 'ajaxSize': {'wpstg--staging-site--size'}, 'ajaxCloneDatabase': {'wpstg_processing'}, 'ajaxGetCliBackupList': {'wpstg_cli_get_backup_list'}, 'ajaxCopyFiles': {'wpstg_clone_files'}, 'ajaxHideLaterRating': {'wpstg_hide_later'}, 'ajaxReplaceData': {'wpstg_clone_replace_data'}, 'ajaxFinish': {'wpstg_onboarding_finish', 'wpstg_clone_finish'}, 'getReusableBackup': {'wpstg--backup-before-update--reusable-backup'}, 'ajaxDismissNotice': {'wpstg_dismiss_notice'}, 'ajaxModalError': {'wpstg_modal_error'}, 'setFeatureEnabled': {'wpstg--backup-before-update--set-enabled'}, 'ajaxSendOtp': {'wpstg--send--otp'}, 'ajaxPrepare': {'wpstg--backups--prepare-backup', 'wpstg--staging-site--prepare-create', 'wpstg--staging-site--prepare-update', 'wpstg--staging-site--prepare-reset', 'wpstg--backups--read-backup-metadata', 'wpstg--backups--prepare-restore', 'wpstg--staging-site--prepare-delete', 'wpstg--job--prepare-cancel'}, 'ajaxPrepareDirectories': {'wpstg_clone_prepare_directories'}, 'ajaxRestart': {'wpstg_onboarding_restart', 'wpstg_restart'}, 'ajaxCloneScan': {'wpstg_scanning'}, 'ajaxCancelAction': {'wpstg_onboarding_cancel_action'}, 'ajaxTestHttpAuth': {'wpstg_test_http_auth'}, 'getBackupProgress': {'wpstg--backup-before-update--progress'}, 'ajaxReportOption': {'wpstg--staging-site--report-option'}, 'ajaxPushTables': {'wpstg_push_tables'}, 'ajaxSetDefaultOsMode': {'wpstg_set_default_os_color_mode'}, 'listFiles': {'wpstg--backups--explore-list'}, 'ajaxCanUseOptimizer': {'wpstg_can_use_optimizer'}, 'resumeProtection': {'wpstg--backup-before-update--resume'}, 'ajaxLoginUrl': {'raw_wpstg--login-url', 'nopriv_raw_wpstg--login-url'}, 'renderScheduleList': {'wpstg--backups-fetch-schedules'}, 'ajaxLogEventSuccess': {'nopriv_wpstg_log_event_success', 'wpstg_log_event_success'}, 'ajaxDelete': {'wpstg--staging-site--delete'}, 'ajaxSetup': {'wpstg--staging-site--setup'}, 'ajaxDeleteIncompleteUploads': {'wpstg--backups--uploads-delete-unfinished'}, 'ajaxPushProcessing': {'nopriv_wpstg_push_processing', 'wpstg_push_processing'}, 'ajaxCloneExcludesSettings': {'wpstg_clone_excludes_settings'}, 'ajaxSendDebugLog': {'wpstg_send_debug_log_report'}, 'ajaxLogEventFailure': {'nopriv_wpstg_log_event_failure', 'wpstg_log_event_failure'}, 'ajaxFixOption': {'wpstg--staging-site--fix-option'}, 'ajaxListing': {'wpstg--staging-site--listing'}, 'ajaxUpdateProcess': {'wpstg_update'}, 'ajaxHideRating': {'wpstg_hide_rating'}, 'sendFeedback': {'wpstg_send_feedback'}, 'ajaxNextStep': {'wpstg_onboarding_next_step'}, 'browse': {'wpstg--backups--explore-browse'}, 'ajaxQueueBackup': {'wpstg_onboarding_queue_backup'}, 'ajaxRender': {'wpstg--staging-site--directory-children'}, 'render': {'nopriv_wpstg--backups--restore', 'wpstg--backups--delete', 'wpstg--backups--restore--file-upload', 'wpstg--backups--parts', 'wpstg--backups--create', 'wpstg--backups--listing', 'wpstg--backups--restore--file-info', 'wpstg--backups--restore', 'wpstg--staging-site--update', 'wpstg--staging-site--reset', 'wpstg--backups--edit', 'wpstg--backups--restore--file-list', 'wpstg--staging-site--create'}, 'ajaxHttpAuthPing': {'wpstg_http_auth_ping', 'nopriv_wpstg_http_auth_ping'}, 'ajaxDownloadBackupFromRemoteServer': {'wpstg--backups--url-file-upload'}, 'discardOnStagingRequest': {'wpstg_cancel_clone', 'wpstg--job--cancel', 'wpstg_staging_job_error'}, 'ajaxCalculateBackupPartsSize': {'wpstg--backups--calculate-backup-size'}, 'ajaxCliNoticeHideForever': {'wpstg_cli_notice_hide_forever'}, 'ajaxHandleGenericEvent': {'wpstg_event_generic'}, 'ajaxResponse': {'wpstg--detect-memory-exhaust'}, 'ajaxIsUserAuthenticated': {'wpstg_check_user_is_authenticated', 'nopriv_wpstg_check_user_is_authenticated'}, 'ajaxEnableDefaultColorMode': {'wpstg_set_dark_mode'}, 'ajaxStartClone': {'wpstg_cloning'}, 'ajaxActivatePro': {'wpstg_activate_pro'}, 'listTree': {'wpstg--backups--explore-tree'}, 'ajaxProcess': {'nopriv_wpstg--job--status', 'nopriv_wpstg--job--heartbeat', 'wpstg--job--heartbeat', 'wpstg--job--cancel', 'wpstg--job--status'}, 'ajaxLogs': {'wpstg_logs'}, 'ajaxDismissCompatNotice': {'wpstg_dismiss_compat_notice'}, 'ajaxOfferShown': {'wpstg_onboarding_offer_shown'}, 'ajaxConfirm': {'wpstg--staging-site--delete-confirmation'}, 'ajaxPurgeQueueTable': {'wpstg_purge_queue_table'}, 'ajaxFetchDirChildren': {'wpstg_fetch_dir_children'}, 'ajaxCheckJobOutcome': {'nopriv_wpstg--job--outcome', 'wpstg--job--outcome'}, 'error_message': {'wpstg_job_error', 'wpstg_staging_job_error'}, 'ajaxPrepareUpload': {'wpstg--backups--prepare-upload', 'wpstg--backups--prepare-url-upload'}, 'ajaxMaybeShowModal': {'wpstg_calculate_backup_speed_index'}, 'ajaxSelectAction': {'wpstg_onboarding_select_action'}, 'ajaxSendEmailNotification': {'wpstg_send_mail_notification', 'nopriv_wpstg_send_mail_notification'}, 'ajaxCheckDBPermissions': {'wpstg_check_user_permissions'}, 'startBackup': {'wpstg--backup-before-update--start'}, 'ajaxIsWritableCloneDestinationDir': {'wpstg_is_writable_clone_destination_dir'}, 'ajaxResetProcess': {'wpstg_reset'}, 'dismissSchedule': {'wpstg--backups-dismiss-schedule'}, 'ajaxCancelUpdate': {'wpstg_cancel_update'}, 'markIntroSeen': {'wpstg--backup-before-update--intro-seen'}, 'ajaxSendReport': {'wpstg_send_report'}}
*
***/

/** Function ajaxQueuedBackupStatus() called by wp_ajax hooks: {'wpstg_onboarding_backup_status'} **/
/** No params detected :-/ **/


/** Function ajaxCheckCloneDirectoryName() called by wp_ajax hooks: {'wpstg_check_clone'} **/
/** Parameters found in function ajaxCheckCloneDirectoryName(): {"post": ["directoryName"]} **/
function ajaxCheckCloneDirectoryName()
    {
        if (!$this->isAuthenticated()) {
            return;
        }

 
        $sitesHelper        = WPStaging::make(Sites::class);
        $cloneDirectoryName = isset($_POST["directoryName"]) ? $sitesHelper->sanitizeDirectoryName($_POST["directoryName"]) : '';

        if (strlen($cloneDirectoryName) < 1) {
            return;
        }

        $result = $sitesHelper->isCloneExists($cloneDirectoryName);
        if ($result === false) {
            wp_send_json(["status" => "success"]);
            return;
        }

        wp_send_json([
            "status"  => "failed",
            "message" => $result,
        ]);
    }


/** Function save() called by wp_ajax hooks: {'wpstg--backup-before-update--save-mode'} **/
/** Parameters found in function save(): {"post": ["cloneID", "cloneName", "networkClone", "includedTables", "excludedTables", "selectedTablesWithoutPrefix", "allTablesExcluded", "excludeGlobRules", "excludeSizeRules", "uploadsSymlinked", "excludedDirectories", "extraDirectories"]} **/
function save(): bool
    {
        if (!isset($_POST) || !isset($_POST["cloneID"])) {
            $this->errorMessage = __("clone ID missing", 'wp-staging');
            return false;
        }

 
        $this->filesIndexCache->delete();

 
        $this->options->root         = str_replace(["\\", '/'], DIRECTORY_SEPARATOR, ABSPATH);
        $this->options->current      = null;
        $this->options->currentClone = null;

 
        $this->options->clone = preg_replace("#\W+#", '-', strtolower($this->sanitize->sanitizeString($_POST["cloneID"])));

 
        if (isset($_POST["cloneName"])) {
            $this->options->cloneName = sanitize_text_field($_POST["cloneName"]);
        }

 
        if (empty($this->options->cloneName) || $this->options->cloneName === $this->options->clone) {
            $this->options->cloneName = $this->maybeGenerateFriendlyName();
        }

 
        $this->options->cloneDirectoryName = $this->sitesHelper->sanitizeDirectoryName($this->options->cloneName);
        $result                            = $this->sitesHelper->isCloneExists($this->options->cloneDirectoryName);
        if ($result !== false) {
            $this->errorMessage = $result;
            return false;
        }

        $this->options->cloneNumber         = 1;
        $this->options->prefix              = $this->setStagingPrefix();
        $this->options->includedDirectories = [];
        $this->options->excludedDirectories = [];
        $this->options->extraDirectories    = [];
        $this->options->excludedFiles       = Hooks::applyFilters(self::FILTER_CLONE_EXCLUDED_FILES, [
            '.DS_Store',
            '*.git',
            '*.svn',
            '*.tmp',
            'desktop.ini',
            '.gitignore',
            '*.log',
            'web.config', 
            '.wp-staging', 
            '.wp-staging-cloneable', 
        ]);

        $excludedFilesFullPath = [
            '.htaccess',
            PathIdentifier::IDENTIFIER_WP_CONTENT . 'db.php',
            PathIdentifier::IDENTIFIER_WP_CONTENT . 'object-cache.php',
            PathIdentifier::IDENTIFIER_WP_CONTENT . 'advanced-cache.php',
        ];

        $hostingExclusions                      = $this->getHostingProviderExclusions();
        $this->options->tmpExcludedHostingFiles = $hostingExclusions['absolutePaths'];
        $excludedFilesFullPath                  = array_merge($excludedFilesFullPath, $hostingExclusions['files']);

        $this->options->excludedFilesFullPath = Hooks::applyFilters(self::FILTER_CLONE_EXCLUDED_FILES_FULL_PATH, $excludedFilesFullPath);

        $this->options->currentStep = 0;

 
        $this->options->job = new \stdClass();
        $this->loadLegacyExistingClones();

 
        if (isset($this->options->existingClones[$this->options->clone])) {
            $existingClone              = (array)$this->options->existingClones[$this->options->clone];
            $this->options->cloneNumber = isset($existingClone['number']) ? (int)$existingClone['number'] : 1;
            $this->options->prefix      = !empty($existingClone['prefix']) && is_string($existingClone['prefix']) ? $existingClone['prefix'] : $this->setStagingPrefix();

 
 
        } elseif (!empty($this->options->existingClones)) {
            $this->options->cloneNumber = count($this->options->existingClones) + 1;
        }

        $this->options->networkClone = false;
        if ($this->isMultisiteAndPro() && is_main_site()) {
            $this->options->networkClone = isset($_POST['networkClone']) && $this->sanitize->sanitizeBool($_POST['networkClone']);
        }

 
        $includedTables              = isset($_POST['includedTables']) ? $this->sanitize->sanitizeString($_POST['includedTables']) : '';
        $excludedTables              = isset($_POST['excludedTables']) ? $this->sanitize->sanitizeString($_POST['excludedTables']) : '';
        $selectedTablesWithoutPrefix = isset($_POST['selectedTablesWithoutPrefix']) ? $this->sanitize->sanitizeString($_POST['selectedTablesWithoutPrefix']) : '';
        $selectedTables              = new SelectedTables($includedTables, $excludedTables, $selectedTablesWithoutPrefix);
        $selectedTables->setAllTablesExcluded(empty($_POST['allTablesExcluded']) ? false : $this->sanitize->sanitizeBool($_POST['allTablesExcluded']));
        $this->options->tables = $selectedTables->getSelectedTables($this->options->networkClone);

 
        $this->options->excludeGlobRules = [];
        if (!empty($_POST["excludeGlobRules"])) {
            $this->options->excludeGlobRules = $this->sanitize->sanitizeExcludeRules($_POST["excludeGlobRules"]);
        }

 
        $this->options->excludeSizeRules = [];
        if (!empty($_POST["excludeSizeRules"])) {
            $this->options->excludeSizeRules = $this->sanitize->sanitizeExcludeRules($_POST["excludeSizeRules"]);
        }

        $this->options->uploadsSymlinked = isset($_POST['uploadsSymlinked']) && $this->sanitize->sanitizeBool($_POST['uploadsSymlinked']);

        $pluginWpContentDir = rtrim($this->directoryAdapter->getPluginWpContentDirectory(), '/\\');





        $excludedDirectories = [
            PathIdentifier::IDENTIFIER_WP_CONTENT . 'cache',
            $this->pathIdentifier->transformPathToIdentifiable($pluginWpContentDir), 
            PathIdentifier::IDENTIFIER_WP_CONTENT . WPSTG_PLUGIN_DOMAIN, 
        ];

        $excludedDirectories = array_merge($excludedDirectories, $hostingExclusions['directories']);

 
        if ($this->options->uploadsSymlinked) {
            $excludedDirectories[] = PathIdentifier::IDENTIFIER_UPLOADS;
        }

        $excludedDirectoriesRequest = isset($_POST["excludedDirectories"]) ? $this->sanitize->sanitizeString($_POST["excludedDirectories"]) : '';
        $excludedDirectoriesRequest = $this->dirUtils->getExcludedDirectories($excludedDirectoriesRequest);

        $this->options->excludedDirectories = array_merge($excludedDirectories, $excludedDirectoriesRequest);

 
        if (isset($_POST["extraDirectories"])) {
            $this->options->extraDirectories = explode(ScanConst::DIRECTORIES_SEPARATOR, $this->sanitize->sanitizeString($_POST["extraDirectories"]));
        }

 
        $this->options->useNewAdminAccount = false;
        $this->options->adminEmail         = '';
        $this->options->adminPassword      = '';

 
        $this->options->databaseServer   = 'localhost';
        $this->options->databaseUser     = '';
        $this->options->databasePassword = '';
        $this->options->databaseDatabase = '';
 
        $this->options->databasePrefix = $this->isExternalDatabase() ? $this->db->prefix : '';
        $this->options->databaseSsl    = false;

 
        $this->options->cloneDir      = '';
        $this->options->cloneHostname = '';

 
        $this->options->isEmailsAllowed         = true;
        $this->options->isCronEnabled           = true;
        $this->options->isWooSchedulerEnabled   = true;
        $this->options->isEmailsReminderEnabled = false;
        $this->options->isAutoUpdatePlugins     = false;
        $this->setAdvancedCloningOptions();

        $this->options->destinationDir      = $this->getDestinationDir();
        $this->options->destinationHostname = $this->getDestinationHostname();

        $this->options->homeHostname = $this->urls->getHomeUrlWithoutScheme();

 
        $this->options->isRunning = true;
        $this->initializeLegacyStagingRun(Job::STAGING);

 
        $this->options->ownerId = get_current_user_id();
 
        $this->saveClone();

        if (!$this->saveOptions()) {
            return false;
        }

        WPStaging::make(AnalyticsStagingCreate::class)->enqueueStartEvent($this->options->jobIdentifier, $this->options);
        $this->errorMessage = "";
        return true;
    }


/** Function getPluginUpdateVersionInfo() called by wp_ajax hooks: {'wpstg--backup-before-update--plugin-versions'} **/
/** Parameters found in function getPluginUpdateVersionInfo(): {"post": ["plugin"]} **/
function getPluginUpdateVersionInfo()
    {
        $this->requireAuthorizedRequest();

        $pluginFile = isset($_POST['plugin']) ? Sanitize::sanitizeString($_POST['plugin']) : '';

        if (!function_exists('get_plugin_updates')) {
            require_once ABSPATH . 'wp-admin/includes/update.php';
        }

        $updates     = get_plugin_updates();
        $versionInfo = ['name' => '', 'currentVersion' => '', 'newVersion' => ''];
        if (isset($updates[$pluginFile])) {
            $plugin      = $updates[$pluginFile];
            $versionInfo = [
                'name'           => isset($plugin->Name) ? $plugin->Name : '',
                'currentVersion' => isset($plugin->Version) ? $plugin->Version : '',
                'newVersion'     => isset($plugin->update->new_version) ? $plugin->update->new_version : '',
            ];
        }

        wp_send_json_success($versionInfo);
    }


/** Function ajaxPushScan() called by wp_ajax hooks: {'wpstg_scan'} **/
/** No params detected :-/ **/


/** Function ajaxCancelClone() called by wp_ajax hooks: {'wpstg_cancel_clone'} **/
/** No params detected :-/ **/


/** Function abandonActionOnRequest() called by wp_ajax hooks: {'wpstg_cancel_clone', 'wpstg--job--cancel'} **/
/** No params detected :-/ **/


/** Function ajaxEnableStagingCloning() called by wp_ajax hooks: {'wpstg_enable_staging_cloning'} **/
/** No params detected :-/ **/


/** Function ajaxCleanProCrons() called by wp_ajax hooks: {'wpstg_clean_pro_crons'} **/
/** No params detected :-/ **/


/** Function ajaxActionStarted() called by wp_ajax hooks: {'wpstg_onboarding_action_started'} **/
/** No params detected :-/ **/


/** Function ajaxHideBeta() called by wp_ajax hooks: {'wpstg_hide_beta'} **/
/** No params detected :-/ **/


/** Function ajaxCliNoticeClose() called by wp_ajax hooks: {'wpstg_cli_notice_close'} **/
/** No params detected :-/ **/


/** Function listDirectoryFiles() called by wp_ajax hooks: {'wpstg--backups--explore-select-directory'} **/
/** Parameters found in function listDirectoryFiles(): {"post": ["filePath", "folder", "summaryOnly"]} **/
function listDirectoryFiles()
    {
        if (!$this->canRenderAjax()) {
            return;
        }

        $filePath = isset($_POST['filePath']) ? sanitize_text_field(wp_unslash($_POST['filePath'])) : '';
        if (empty($filePath)) {
            wp_send_json_error(['message' => __('Backup file is missing.', 'wp-staging')]);
        }

        $backupFile = $this->backupPathResolver->resolveBackupPath($filePath);
        if (empty($backupFile) || !file_exists($backupFile)) {
            wp_send_json_error(['message' => __('Backup file not found.', 'wp-staging')]);
        }

        try {
            $metadata = (new BackupMetadata())->hydrateByFilePath($backupFile);
        } catch (\Throwable $e) {
            wp_send_json_error(['message' => $e->getMessage()]);
        }

        $folder = isset($_POST['folder']) ? sanitize_text_field(wp_unslash($_POST['folder'])) : '';
        $folder = $this->normalizeFolder($folder);
        $summaryOnly = isset($_POST['summaryOnly'])
            ? filter_var(wp_unslash($_POST['summaryOnly']), FILTER_VALIDATE_BOOLEAN)
            : false;

        try {
            if ($summaryOnly) {
                $summary = $this->getDirectoryStatsForSelection($backupFile, $metadata, $folder);
                wp_send_json_success([
                    'summary' => $summary,
                ]);
            }

            $files = $this->getDirectoryFilesForSelection($backupFile, $metadata, $folder);
        } catch (\Throwable $e) {
            wp_send_json_error(['message' => $e->getMessage()]);
        }

        wp_send_json_success([
            'files' => $files,
        ]);
    }


/** Function ajaxRestoreSettings() called by wp_ajax hooks: {'wpstg_restore_settings'} **/
/** No params detected :-/ **/


/** Function ajaxSize() called by wp_ajax hooks: {'wpstg--staging-site--size'} **/
/** Parameters found in function ajaxSize(): {"post": ["excludedDirectories", "extraDirectories", "isUploadsSymlinked", "databaseSize"]} **/
function ajaxSize()
    {
        if (!$this->canRenderAjax()) {
            return;
        }

        $excludedDirectories = isset($_POST['excludedDirectories']) ? Sanitize::sanitizeString($_POST['excludedDirectories']) : '';
        $extraDirectories    = isset($_POST['extraDirectories']) ? Sanitize::sanitizeString($_POST['extraDirectories']) : '';
        $isUploadsSymlinked  = isset($_POST['isUploadsSymlinked']) && Sanitize::sanitizeBool($_POST['isUploadsSymlinked']);
        $databaseSize        = isset($_POST['databaseSize']) ? Sanitize::sanitizeInt($_POST['databaseSize']) : 0;

        wp_send_json($this->calculate($excludedDirectories, $extraDirectories, $databaseSize, $isUploadsSymlinked, $this->resolveExclusionRules()));
    }


/** Function ajaxCloneDatabase() called by wp_ajax hooks: {'wpstg_processing'} **/
/** No params detected :-/ **/


/** Function ajaxGetCliBackupList() called by wp_ajax hooks: {'wpstg_cli_get_backup_list'} **/
/** No params detected :-/ **/


/** Function ajaxCopyFiles() called by wp_ajax hooks: {'wpstg_clone_files'} **/
/** No params detected :-/ **/


/** Function ajaxHideLaterRating() called by wp_ajax hooks: {'wpstg_hide_later'} **/
/** No params detected :-/ **/


/** Function ajaxReplaceData() called by wp_ajax hooks: {'wpstg_clone_replace_data'} **/
/** No params detected :-/ **/


/** Function ajaxFinish() called by wp_ajax hooks: {'wpstg_onboarding_finish', 'wpstg_clone_finish'} **/
/** No params detected :-/ **/


/** Function getReusableBackup() called by wp_ajax hooks: {'wpstg--backup-before-update--reusable-backup'} **/
/** Parameters found in function getReusableBackup(): {"post": ["updateType"]} **/
function getReusableBackup()
    {
        $this->requireAuthorizedRequest();

        $updateType = isset($_POST['updateType']) ? Sanitize::sanitizeString($_POST['updateType']) : 'plugin';

        $reusable          = $this->beforeUpdateBackups->findReusableBackup($this->getBackupData($updateType, ''));
        $willTakeNewBackup = empty($reusable);

        $this->beforeUpdateBackups->prune($willTakeNewBackup ? 1 : 0);

        wp_send_json_success($reusable);
    }


/** Function ajaxDismissNotice() called by wp_ajax hooks: {'wpstg_dismiss_notice'} **/
/** Parameters found in function ajaxDismissNotice(): {"post": ["wpstg_notice"]} **/
function ajaxDismissNotice()
    {
        if (!$this->isAuthenticated()) {
            return;
        }

 
        if (!isset($_POST['wpstg_notice'])) {
            wp_send_json(null);
            return;
        }

 
        $dismissNotice = WPStaging::make(DismissNotice::class);
        $dismissNotice->dismiss($this->sanitize->sanitizeString($_POST['wpstg_notice']));
    }


/** Function ajaxModalError() called by wp_ajax hooks: {'wpstg_modal_error'} **/
/** Parameters found in function ajaxModalError(): {"post": ["type"]} **/
function ajaxModalError()
    {
        if (!$this->isAuthenticated()) {
            return;
        }

        $type = isset($_POST['type']) ? $this->sanitize->sanitizeString($_POST['type']) : null;
        if ($type === 'processLock') {
            $process = WPStaging::make(ProcessLock::class);
            $process->restart();

            exit();
        }
    }


/** Function setFeatureEnabled() called by wp_ajax hooks: {'wpstg--backup-before-update--set-enabled'} **/
/** Parameters found in function setFeatureEnabled(): {"post": ["enabled"]} **/
function setFeatureEnabled()
    {
        $this->requireAuthorizedRequest();

        $isEnabled = isset($_POST['enabled']) && Sanitize::sanitizeString($_POST['enabled']) === '1';
        $this->updateProtectionSettings->setEnabled($isEnabled);

        wp_send_json_success(['enabled' => $isEnabled]);
    }


/** Function ajaxSendOtp() called by wp_ajax hooks: {'wpstg--send--otp'} **/
/** Parameters found in function ajaxSendOtp(): {"request": ["sessionId", "resend"]} **/
function ajaxSendOtp()
    {
        if (!$this->auth->isAuthenticatedRequest()) {
            wp_send_json_error(esc_html__('Invalid Request! User is not authenticated', 'wp-staging'), 403);
        }

        if (!$this->otpService->isOtpFeatureEnabled()) {
            wp_send_json_success([
                'message' => esc_html__('OTP feature is not enabled.', 'wp-staging'),
            ], 202);
        }

        if (empty($_REQUEST['sessionId'])) {
            wp_send_json_error(esc_html__('Invalid Request! Session ID is missing', 'wp-staging'), 403);
        }

        $userEmail = $this->getCurrentUserEmail();
        if (empty($userEmail)) {
            wp_send_json_error(esc_html__('Invalid Request! No user found', 'wp-staging'), 403);
        }

        $sessionId     = sanitize_text_field($_REQUEST['sessionId']);
        $resendRequest = !empty($_REQUEST['resend']) && sanitize_text_field($_REQUEST['resend']) === 'true';

        if (!$this->canSendOtp()) {
            if ($resendRequest) {
                wp_send_json_error([
                    'message'     => esc_html__('Please wait %s seconds before requesting a new confirmation code...', 'wp-staging'),
                    'reRequestAt' => get_transient(self::TRANSIENT_OTP_SENT),
                ]);
            } else {
                $remainingTime = $this->calculateRemainingTime();
                wp_send_json_error(sprintf(esc_html__('OTP already sent. Please wait %s seconds before sending another verification code.', 'wp-staging'), esc_html($remainingTime)), 423);
            }
        }

        $otp = '';
        try {
            $otp = $this->otpService->generateNewOtp($sessionId);
        } catch (OtpException $ex) {
            wp_send_json_error($ex->getMessage(), 401);
        }

        $subject = esc_html__('Your WP Staging Verification Code', 'wp-staging');

        $message = esc_html__('Your Verification Code', 'wp-staging');
        $message .= "\n\n" . esc_html__('To upload your backup, enter this code on the WP Staging OTP form:', 'wp-staging');
        $message .= "\n\n" . esc_html($otp);
        $message .= "\n\n" . sprintf(esc_html__('This E-Mail has been sent from %s while uploading a backup file to the website with the WP Staging plugin.', 'wp-staging'), get_site_url());
        $message .= "\n\n" . esc_html__("Please do not forward this email.  If you didn`t request this code, you can ignore this message.", "wp-staging");
        $message .= "\n\n" . esc_html__('The verification code above is unique and will expire in 5 minutes.', 'wp-staging');

        $sent = false;
        if (get_option(Notifications::OPTION_SEND_EMAIL_AS_HTML, false) === 'true') {
            $sent = $this->notifications->sendEmailAsHTML($userEmail, $subject, $message);
        } else {
            $sent = $this->notifications->sendEmail($userEmail, $subject, $message, '', [], Notifications::DISABLE_FOOTER_MESSAGE);
        }

        if (!$sent) {
            debug_log('Failed to send OTP to user email: ' . $userEmail);
            wp_send_json_error(esc_html__('Failed to send OTP', 'wp-staging'), 401);
        }

        debug_log('Succeeded to send OTP to user email: ' . $userEmail);
        if ($resendRequest) {
            wp_send_json_success([
                'message'     => esc_html__('OTP sent successfully! Please wait %s seconds before requesting a new verification code...', 'wp-staging'),
                'reRequestAt' => get_transient(self::TRANSIENT_OTP_SENT),
            ], 201);
        } else {
            wp_send_json_success(esc_html__('OTP sent successfully', 'wp-staging'), 201);
        }
    }


/** Function ajaxPrepare() called by wp_ajax hooks: {'wpstg--backups--prepare-backup', 'wpstg--staging-site--prepare-create', 'wpstg--staging-site--prepare-update', 'wpstg--staging-site--prepare-reset', 'wpstg--backups--read-backup-metadata', 'wpstg--backups--prepare-restore', 'wpstg--staging-site--prepare-delete', 'wpstg--job--prepare-cancel'} **/
/** No params detected :-/ **/


/** Function ajaxPrepareDirectories() called by wp_ajax hooks: {'wpstg_clone_prepare_directories'} **/
/** No params detected :-/ **/


/** Function ajaxRestart() called by wp_ajax hooks: {'wpstg_onboarding_restart', 'wpstg_restart'} **/
/** No params detected :-/ **/


/** Function ajaxCloneScan() called by wp_ajax hooks: {'wpstg_scanning'} **/
/** No params detected :-/ **/


/** Function ajaxCancelAction() called by wp_ajax hooks: {'wpstg_onboarding_cancel_action'} **/
/** No params detected :-/ **/


/** Function ajaxTestHttpAuth() called by wp_ajax hooks: {'wpstg_test_http_auth'} **/
/** No params detected :-/ **/


/** Function getBackupProgress() called by wp_ajax hooks: {'wpstg--backup-before-update--progress'} **/
/** No params detected :-/ **/


/** Function ajaxReportOption() called by wp_ajax hooks: {'wpstg--staging-site--report-option'} **/
/** No params detected :-/ **/


/** Function ajaxPushTables() called by wp_ajax hooks: {'wpstg_push_tables'} **/
/** Parameters found in function ajaxPushTables(): {"post": ["includedTables", "excludedTables", "selectedTablesWithoutPrefix"]} **/
function ajaxPushTables()
    {
        if (!$this->isAuthenticated()) {
            return false;
        }

        if (!class_exists('WPStaging\Backend\Pro\Modules\Jobs\Scan')) {
            return false;
        }

 
        $scan = WPStaging::make(ScanProModule::class);
        $scan->loadStagingDBTables($onlyLoadStagingPrefixTables = false);
        $scan->start();
        $options = $scan->getOptions();

        $includedTables              = isset($_POST['includedTables']) ? $this->sanitize->sanitizeString($_POST['includedTables']) : '';
        $excludedTables              = isset($_POST['excludedTables']) ? $this->sanitize->sanitizeString($_POST['excludedTables']) : '';
        $selectedTablesWithoutPrefix = isset($_POST['selectedTablesWithoutPrefix']) ? $this->sanitize->sanitizeString($_POST['selectedTablesWithoutPrefix']) : '';
        $selectedTables              = new SelectedTables($includedTables, $excludedTables, $selectedTablesWithoutPrefix);
        $selectedTables->setDatabaseInfo($options->databaseServer, $options->databaseUser, $options->databasePassword, $options->databaseDatabase, empty($options->databasePrefix) ? $options->prefix : $options->databasePrefix, $options->databaseSsl);
        $tables = $selectedTables->getSelectedTables($options->networkClone);

        $templateEngine = WPStaging::make(TemplateEngine::class);

        echo json_encode([
            'success' => true,
            "content" => $templateEngine->render("pro/selections/tables.php", [
                'isNetworkClone' => $scan->isNetworkClone(),
                'options'        => $options,
                'showAll'        => true,
                'selected'       => $tables,
            ]),
        ]);

        exit();
    }


/** Function ajaxSetDefaultOsMode() called by wp_ajax hooks: {'wpstg_set_default_os_color_mode'} **/
/** Parameters found in function ajaxSetDefaultOsMode(): {"post": ["defaultOsColorMode"]} **/
function ajaxSetDefaultOsMode()
    {
        if (!$this->auth->isAuthenticatedRequest('', 'manage_options')) {
            wp_send_json_error();
        }

        $defaultOsColorMode = (isset($_POST['defaultOsColorMode'])) ? $this->sanitize->sanitizeString($_POST['defaultOsColorMode']) : '';

        if (empty($defaultOsColorMode)) {
            wp_send_json_error();
        }

        update_option(self::OPTION_DEFAULT_OS_COLOR_MODE, $defaultOsColorMode);
        wp_send_json_success([
            'defaultColorMode' => $this->defaultColorMode,
        ]);
    }


/** Function listFiles() called by wp_ajax hooks: {'wpstg--backups--explore-list'} **/
/** Parameters found in function listFiles(): {"post": ["filePath", "perPage", "page", "folder", "search"]} **/
function listFiles()
    {
        if (!$this->canRenderAjax()) {
            return;
        }

        $filePath = isset($_POST['filePath']) ? sanitize_text_field(wp_unslash($_POST['filePath'])) : '';
        if (empty($filePath)) {
            wp_send_json_error(['message' => __('Backup file is missing.', 'wp-staging')]);
        }

        $backupFile = $this->backupPathResolver->resolveBackupPath($filePath);
        if (empty($backupFile) || !file_exists($backupFile)) {
            wp_send_json_error(['message' => __('Backup file not found.', 'wp-staging')]);
        }

        try {
            $metadata = (new BackupMetadata())->hydrateByFilePath($backupFile);
        } catch (\Throwable $e) {
            wp_send_json_error(['message' => $e->getMessage()]);
        }

        $perPage = isset($_POST['perPage']) ? absint($_POST['perPage']) : self::MAX_PER_PAGE;
        $perPage = max(1, min(self::MAX_PER_PAGE, $perPage));

        $page = isset($_POST['page']) ? absint($_POST['page']) : 1;
        $page = max(1, $page);

        $folder = isset($_POST['folder']) ? sanitize_text_field(wp_unslash($_POST['folder'])) : '';
        $folder = $this->normalizeFolder($folder);

        $search = isset($_POST['search']) ? sanitize_text_field(wp_unslash($_POST['search'])) : '';
        $sort   = 'name_asc';

        try {
            $entries = $this->getDirectoryEntries($backupFile, $metadata, $folder, $search, $sort);
        } catch (\Throwable $e) {
            wp_send_json_error(['message' => $e->getMessage()]);
        }

        $totalEntries = count($entries);
        $totalPages   = (int)ceil($totalEntries / $perPage);
        $offset       = ($page - 1) * $perPage;
        $pagedEntries = array_slice($entries, $offset, $perPage);

        wp_send_json_success([
            'entries' => $pagedEntries,
            'paging'  => [
                'totalItems' => $totalEntries,
                'totalPages' => $totalPages,
                'page'       => $page,
                'hasMore'    => $page < $totalPages,
            ],
        ]);
    }


/** Function ajaxCanUseOptimizer() called by wp_ajax hooks: {'wpstg_can_use_optimizer'} **/
/** Parameters found in function ajaxCanUseOptimizer(): {"request": ["secret"]} **/
function ajaxCanUseOptimizer()
    {
        $providedSecret = isset($_REQUEST['secret']) ? sanitize_text_field($_REQUEST['secret']) : '';
        if (empty($providedSecret)) {
            wp_send_json_error();
        }

        $expectedSecret = get_transient(self::TRANSIENT_CHECK_SECRET);
        if (empty($expectedSecret) || !hash_equals($expectedSecret, $providedSecret)) {
            wp_send_json_error();
        }

        delete_transient(self::TRANSIENT_CHECK_SECRET);

        wp_send_json_success();
    }


/** Function resumeProtection() called by wp_ajax hooks: {'wpstg--backup-before-update--resume'} **/
/** No params detected :-/ **/


/** Function ajaxLoginUrl() called by wp_ajax hooks: {'raw_wpstg--login-url', 'nopriv_raw_wpstg--login-url'} **/
/** No params detected :-/ **/


/** Function renderScheduleList() called by wp_ajax hooks: {'wpstg--backups-fetch-schedules'} **/
/** No params detected :-/ **/


/** Function ajaxLogEventSuccess() called by wp_ajax hooks: {'nopriv_wpstg_log_event_success', 'wpstg_log_event_success'} **/
/** Parameters found in function ajaxLogEventSuccess(): {"post": ["process"]} **/
function ajaxLogEventSuccess()
    {
        $this->init();
        if (!$this->auth->isAuthenticatedRequest()) {
            return;
        }

        $process       = isset($_POST['process']) ? $this->sanitize->sanitizeString($_POST['process']) : '';
        $this->process = $this->getProcessPrefix($process);
        if (empty($this->process)) {
            wp_send_json_error();
        }

        if ($this->process === EventLoggerConst::PROCESS_PREFIX_PUSH) {
            $this->logPushCompleted(true);
            wp_send_json_success();
        }

        $this->writeEventStatus($this->process);
        wp_send_json_success();
    }


/** Function ajaxDelete() called by wp_ajax hooks: {'wpstg--staging-site--delete'} **/
/** No params detected :-/ **/


/** Function ajaxSetup() called by wp_ajax hooks: {'wpstg--staging-site--setup'} **/
/** No params detected :-/ **/


/** Function ajaxDeleteIncompleteUploads() called by wp_ajax hooks: {'wpstg--backups--uploads-delete-unfinished'} **/
/** No params detected :-/ **/


/** Function ajaxPushProcessing() called by wp_ajax hooks: {'nopriv_wpstg_push_processing', 'wpstg_push_processing'} **/
/** No params detected :-/ **/


/** Function ajaxCloneExcludesSettings() called by wp_ajax hooks: {'wpstg_clone_excludes_settings'} **/
/** No params detected :-/ **/


/** Function ajaxSendDebugLog() called by wp_ajax hooks: {'wpstg_send_debug_log_report'} **/
/** No params detected :-/ **/


/** Function ajaxLogEventFailure() called by wp_ajax hooks: {'nopriv_wpstg_log_event_failure', 'wpstg_log_event_failure'} **/
/** Parameters found in function ajaxLogEventFailure(): {"post": ["process"]} **/
function ajaxLogEventFailure()
    {
        $this->init();
        if (!$this->auth->isAuthenticatedRequest()) {
            return;
        }

        $process       = isset($_POST['process']) ? $this->sanitize->sanitizeString($_POST['process']) : '';
        $this->process = $this->getProcessPrefix($process);
        if (empty($this->process)) {
            wp_send_json_error();
        }

        $response = $this->updateFailedProcess($this->process);
        if ($response) {
            wp_send_json_success();
        }

        wp_send_json_error();
    }


/** Function ajaxFixOption() called by wp_ajax hooks: {'wpstg--staging-site--fix-option'} **/
/** No params detected :-/ **/


/** Function ajaxListing() called by wp_ajax hooks: {'wpstg--staging-site--listing'} **/
/** No params detected :-/ **/


/** Function ajaxUpdateProcess() called by wp_ajax hooks: {'wpstg_update'} **/
/** No params detected :-/ **/


/** Function ajaxHideRating() called by wp_ajax hooks: {'wpstg_hide_rating'} **/
/** No params detected :-/ **/


/** Function sendFeedback() called by wp_ajax hooks: {'wpstg_send_feedback'} **/
/** No params detected :-/ **/


/** Function ajaxNextStep() called by wp_ajax hooks: {'wpstg_onboarding_next_step'} **/
/** No params detected :-/ **/


/** Function browse() called by wp_ajax hooks: {'wpstg--backups--explore-browse'} **/
/** Parameters found in function browse(): {"post": ["filePath", "perPage", "page", "folder", "search", "withTree"]} **/
function browse()
    {
        if (!$this->canRenderAjax()) {
            return;
        }

        $filePath = isset($_POST['filePath']) ? sanitize_text_field(wp_unslash($_POST['filePath'])) : '';
        if (empty($filePath)) {
            wp_send_json_error(['message' => __('Backup file is missing.', 'wp-staging')]);
        }

        $backupFile = $this->backupPathResolver->resolveBackupPath($filePath);
        if (empty($backupFile) || !file_exists($backupFile)) {
            wp_send_json_error(['message' => __('Backup file not found.', 'wp-staging')]);
        }

        try {
            $metadata = (new BackupMetadata())->hydrateByFilePath($backupFile);
        } catch (\Throwable $e) {
            wp_send_json_error(['message' => $e->getMessage()]);
        }

        $perPage = isset($_POST['perPage']) ? absint($_POST['perPage']) : self::MAX_PER_PAGE;
        $perPage = max(1, min(self::MAX_PER_PAGE, $perPage));

        $page = isset($_POST['page']) ? absint($_POST['page']) : 1;
        $page = max(1, $page);

        $folder = isset($_POST['folder']) ? sanitize_text_field(wp_unslash($_POST['folder'])) : '';
        $folder = $this->normalizeFolder($folder);

        $search = isset($_POST['search']) ? sanitize_text_field(wp_unslash($_POST['search'])) : '';
        $sort   = 'name_asc';

        $withTree = isset($_POST['withTree'])
            ? filter_var(wp_unslash($_POST['withTree']), FILTER_VALIDATE_BOOLEAN)
            : false;

        try {
            $entries = $this->getDirectoryEntries($backupFile, $metadata, $folder, $search, $sort);
        } catch (\Throwable $e) {
            wp_send_json_error(['message' => $e->getMessage()]);
        }

        $totalEntries = count($entries);
        $totalPages   = (int)ceil($totalEntries / $perPage);
        $offset       = ($page - 1) * $perPage;
        $pagedEntries = array_slice($entries, $offset, $perPage);

        $response = [
            'entries' => $pagedEntries,
            'paging'  => [
                'totalItems' => $totalEntries,
                'totalPages' => $totalPages,
                'page'       => $page,
                'hasMore'    => $page < $totalPages,
            ],
        ];

        if ($withTree) {
            try {
                $response['directories'] = $this->getDirectoryTree($backupFile, $metadata, $folder);
            } catch (\Throwable $e) {
                $response['directories'] = [];
            }
        }

        wp_send_json_success($response);
    }


/** Function ajaxQueueBackup() called by wp_ajax hooks: {'wpstg_onboarding_queue_backup'} **/
/** No params detected :-/ **/


/** Function ajaxRender() called by wp_ajax hooks: {'wpstg--staging-site--directory-children'} **/
/** Parameters found in function ajaxRender(): {"post": ["jobType", "cloneId", "dirPath", "prefix", "isChecked", "forceDefault"]} **/
function ajaxRender()
    {
        if (!$this->canRenderAjax()) {
            return;
        }

        try {
            $jobType      = isset($_POST['jobType']) ? Sanitize::sanitizeString($_POST['jobType']) : AbstractStagingSetup::JOB_NEW_STAGING_SITE;
            $cloneId      = isset($_POST['cloneId']) ? Sanitize::sanitizeString($_POST['cloneId']) : '';
            $dirPath      = isset($_POST['dirPath']) ? Sanitize::sanitizePath($_POST['dirPath']) : '';
            $prefix       = isset($_POST['prefix']) ? Sanitize::sanitizeString($_POST['prefix']) : '';
            $parentChecked = isset($_POST['isChecked']) && Sanitize::sanitizeBool($_POST['isChecked']);
            $forceDefault = isset($_POST['forceDefault']) && Sanitize::sanitizeBool($_POST['forceDefault']);

            $this->setupScanner($jobType, $cloneId);
            $basePath = $this->getBasePath($prefix);
            $path     = $this->resolvePathWithinBase($dirPath, $basePath);

            $directories = $this->directoryScanner->scanDirectory($path, $basePath, $prefix);
            $listing     = $this->directoryScanner->directoryListing($directories, $parentChecked, $forceDefault);

            wp_send_json_success(['directoryListing' => $listing]);
        } catch (Throwable $e) {
            wp_send_json_error(['message' => $e->getMessage()]);
        }
    }


/** Function render() called by wp_ajax hooks: {'nopriv_wpstg--backups--restore', 'wpstg--backups--delete', 'wpstg--backups--restore--file-upload', 'wpstg--backups--parts', 'wpstg--backups--create', 'wpstg--backups--listing', 'wpstg--backups--restore--file-info', 'wpstg--backups--restore', 'wpstg--staging-site--update', 'wpstg--staging-site--reset', 'wpstg--backups--edit', 'wpstg--backups--restore--file-list', 'wpstg--staging-site--create'} **/
/** No params detected :-/ **/


/** Function ajaxHttpAuthPing() called by wp_ajax hooks: {'wpstg_http_auth_ping', 'nopriv_wpstg_http_auth_ping'} **/
/** No params detected :-/ **/


/** Function ajaxDownloadBackupFromRemoteServer() called by wp_ajax hooks: {'wpstg--backups--url-file-upload'} **/
/** Parameters found in function ajaxDownloadBackupFromRemoteServer(): {"post": ["backupUrl", "startByte", "fileSize"]} **/
function ajaxDownloadBackupFromRemoteServer()
    {
        if (!$this->auth->isAuthenticatedRequest()) {
            return;
        }

        $remoteFileUrl = $this->sanitize->sanitizeUrl($_POST['backupUrl'] ?? '');
        if (empty($remoteFileUrl)) {
            $this->setFailResponse(__('Backup file URL is empty', 'wp-staging'));
            $this->remoteDownloader->writeResponse();
            return;
        }

        $remoteFileUrl = (string)strtok($remoteFileUrl, '?#');
        if (!$this->filesystem->isWpstgBackupFile($remoteFileUrl)) {
            $this->setFailResponse(sprintf(__('Invalid backup file extension: %s', 'wp-staging'), basename($remoteFileUrl)));
            $this->remoteDownloader->writeResponse();
            return;
        }

        if ($this->ssrfProtection->isBlockedUrl($remoteFileUrl)) {
            $this->setFailResponse(__('The URL resolves to a blocked IP address.', 'wp-staging'));
            $this->remoteDownloader->writeResponse();
            return;
        }

        $startByte              = $this->sanitize->sanitizeInt($_POST['startByte'] ?? 0);
        $fileSize               = $this->sanitize->sanitizeInt($_POST['fileSize'] ?? 0);
        $preparedUploadMetadata = $this->getPreparedUploadMetadata();
        $fetchMissingFileSize   = true;

        if ($fileSize === 0 && $this->isPreparedUploadForUrl($preparedUploadMetadata, $remoteFileUrl)) {
            $fileSize             = $preparedUploadMetadata['fileSize'];
            $fetchMissingFileSize = false;
        }

        $this->setDownloadParameters($remoteFileUrl, $startByte, $fileSize, $fetchMissingFileSize);

        try {
            $this->validateIsUploadPrepared($remoteFileUrl);
        } catch (\Exception $e) {
            $this->setFailResponse(__('Invalid Request! Backup upload was not prepared...', 'wp-staging'));
            $this->remoteDownloader->writeResponse();
            return;
        }

        $this->downloadBackup();
    }


/** Function discardOnStagingRequest() called by wp_ajax hooks: {'wpstg_cancel_clone', 'wpstg--job--cancel', 'wpstg_staging_job_error'} **/
/** No params detected :-/ **/


/** Function ajaxCalculateBackupPartsSize() called by wp_ajax hooks: {'wpstg--backups--calculate-backup-size'} **/
/** Parameters found in function ajaxCalculateBackupPartsSize(): {"post": ["backup_part", "backup_type", "advanceExclusion"]} **/
function ajaxCalculateBackupPartsSize()
    {
        if (!$this->auth->isAuthenticatedRequest()) {
            return;
        }

        $backupPart = isset($_POST['backup_part']) ? $this->sanitize->sanitizeString($_POST['backup_part']) : '';
        if (empty($backupPart)) {
            wp_send_json_error([
                'message' => 'Invalid or missing backup part parameter',
            ]);
        }

        $this->setFilters();
        $this->getExcludedDirectories();
        $backupType                          = isset($_POST['backup_type']) ? $this->sanitize->sanitizeString($_POST['backup_type']) : '';
        if (is_multisite() && $backupType === 'multi') {
            $this->isNetworkSiteBackup = true;
        }

        $advanceExclusion                    = isset($_POST['advanceExclusion']) && is_array($_POST['advanceExclusion']) ? $this->sanitize->sanitizeArray($_POST['advanceExclusion']) : [];
        $this->isExcludingUnusedThemes       = isset($advanceExclusion['wpstgExcludeUnusedThemes']) && $advanceExclusion['wpstgExcludeUnusedThemes'] === 'true';
        $this->isExcludingDeactivatedPlugins = isset($advanceExclusion['wpstgExcludeDeactivatedPlugins']) && $advanceExclusion['wpstgExcludeDeactivatedPlugins'] === 'true';
        $this->isExcludingCaches             = isset($advanceExclusion['wpstgExcludeCaches']) && $advanceExclusion['wpstgExcludeCaches'] === 'true';
        $this->isExcludingLogs               = isset($advanceExclusion['wpstgExcludeLogs']) && $advanceExclusion['wpstgExcludeLogs'] === 'true';

        $this->scannerDto->setIsExcludingLogs($this->isExcludingLogs);
        $this->scannerDto->setIsExcludingCaches($this->isExcludingCaches);
        $this->scannerDto->setExcludedDirectories($this->excludedDirectories);
        $this->setGlobalExcludeFilter([
            '**/wp-staging*/**/node_modules', 
        ]);

        if ($backupPart === 'includePluginsInBackup') {
            $this->calculatePluginsSize();
        }

        if ($backupPart === 'includeMuPluginsInBackup') {
            $this->calculateMuPluginsSize();
        }

        if ($backupPart === 'includeThemesInBackup') {
            $this->calculateThemesSize();
        }

        if ($backupPart === 'includeMediaLibraryInBackup') {
            $this->calculateUploadsSize();
        }

        if ($backupPart === 'wpstgIncludeOtherFilesInWpRoot') {
            $this->calculateOtherFilesInRootSize();
        }

        if ($backupPart === 'includeOtherFilesInWpContent') {
            $this->calculateOtherFilesInWpContentSize();
        }

        wp_send_json_success();
    }


/** Function ajaxCliNoticeHideForever() called by wp_ajax hooks: {'wpstg_cli_notice_hide_forever'} **/
/** No params detected :-/ **/


/** Function ajaxHandleGenericEvent() called by wp_ajax hooks: {'wpstg_event_generic'} **/
/** Parameters found in function ajaxHandleGenericEvent(): {"post": ["event_name", "group_name", "custom"]} **/
function ajaxHandleGenericEvent()
    {
        if (!$this->auth->isAuthenticatedRequest()) {
            wp_send_json_error(null, 401);
            return;
        }

        $eventName = isset($_POST['event_name']) ? $this->sanitize->sanitizeString($_POST['event_name']) : '';
        if ($eventName === '' || !preg_match('/^[a-zA-Z0-9_]{1,100}$/', $eventName)) {
            wp_send_json_error(null, 400);
            return;
        }

        $groupName = isset($_POST['group_name']) ? $this->sanitize->sanitizeString($_POST['group_name']) : '';
        if ($groupName !== '' && !preg_match('/^[a-zA-Z0-9_]{1,100}$/', $groupName)) {
            wp_send_json_error(null, 400);
            return;
        }

        $custom = isset($_POST['custom']) ? $this->sanitizeCustomData($this->sanitize->sanitizeArrayString($_POST['custom'])) : [];

        AnalyticsGenericEvent::logEvent($eventName, $groupName, $custom);

        wp_send_json_success();
    }


/** Function ajaxResponse() called by wp_ajax hooks: {'wpstg--detect-memory-exhaust'} **/
/** Parameters found in function ajaxResponse(): {"post": ["requestType"]} **/
function ajaxResponse()
    {
        if (!$this->canRenderAjax()) {
            return;
        }

        $wpstgRequest = isset($_POST['requestType']) ? Sanitize::sanitizePath($_POST['requestType']) : '';

        $validWpstgRequests = [
            Backup::WPSTG_REQUEST,
            Restore::WPSTG_REQUEST,
            Cloning::WPSTG_REQUEST,
            StagingSiteCreate::WPSTG_REQUEST,
            StagingSiteUpdate::WPSTG_REQUEST,
            StagingSiteReset::WPSTG_REQUEST,
        ];

        if (class_exists('\WPStaging\Pro\Push\Ajax\Push')) {
            $validWpstgRequests[] = \WPStaging\Pro\Push\Ajax\Push::WPSTG_REQUEST;
        }

        if ($wpstgRequest === '') {
            wp_send_json([
                'status'  => false,
                'message' => 'No Response Type Given!',
            ]);
        }

        if (!in_array($wpstgRequest, $validWpstgRequests)) {
            wp_send_json([
                'status'  => false,
                'message' => 'Invalid Response Type Given!',
            ]);
        }

        if (!defined('WPSTG_UPLOADS_DIR')) {
            debug_log('WPSTG_UPLOADS_DIR is not defined!');
            wp_send_json([
                'status'  => false,
                'message' => 'Something Went Wrong!',
            ]);
        }

        $exhaustLockFile = WPSTG_UPLOADS_DIR . $wpstgRequest . ErrorHandler::ERROR_FILE_EXTENSION;
        if (!file_exists($exhaustLockFile)) {
            wp_send_json([
                'status' => true,
                'error'  => false,
            ]);
        };

        $json = file_get_contents($exhaustLockFile);
        unlink($exhaustLockFile);
        $data = json_decode($json, true);

        $result = wp_send_json([
            'status'  => true,
            'error'   => true,
            'data'    => $data,
            'message' => sprintf(
                esc_html__('Memory exhaust issue is detected during the process. Error occurred when allocating more %s on top of current usage of %s. Peak memory usage: %s, Allowed memory limit: %s, PHP memory limit: %s, WP memory limit: %s', 'wp-staging'),
                size_format($data['exhaustedMemorySize']),
                size_format($data['memoryUsage']),
                size_format($data['peakMemoryUsage']),
                size_format($data['allowedMemoryLimit']),
                $data['phpMemoryLimit'],
                $data['wpMemoryLimit']
            ),
        ]);

        wp_send_json($result);
    }


/** Function ajaxIsUserAuthenticated() called by wp_ajax hooks: {'wpstg_check_user_is_authenticated', 'nopriv_wpstg_check_user_is_authenticated'} **/
/** No params detected :-/ **/


/** Function ajaxEnableDefaultColorMode() called by wp_ajax hooks: {'wpstg_set_dark_mode'} **/
/** Parameters found in function ajaxEnableDefaultColorMode(): {"post": ["mode"]} **/
function ajaxEnableDefaultColorMode()
    {
        if (!$this->auth->isAuthenticatedRequest('', 'manage_options')) {
            wp_send_json_error();
        }

        $defaultColorMode = isset($_POST['mode']) ? $this->sanitize->sanitizeString($_POST['mode']) : '';

        if (empty($defaultColorMode)) {
            wp_send_json_error();
        }

        if ($this->defaultColorMode === $defaultColorMode) {
            wp_send_json_success();
        }

        update_option(self::OPTION_DEFAULT_COLOR_MODE, $defaultColorMode);

        wp_send_json_success();
    }


/** Function ajaxStartClone() called by wp_ajax hooks: {'wpstg_cloning'} **/
/** No params detected :-/ **/


/** Function ajaxActivatePro() called by wp_ajax hooks: {'wpstg_activate_pro'} **/
/** No params detected :-/ **/


/** Function listTree() called by wp_ajax hooks: {'wpstg--backups--explore-tree'} **/
/** Parameters found in function listTree(): {"post": ["filePath", "folder"]} **/
function listTree()
    {
        if (!$this->canRenderAjax()) {
            return;
        }

        $filePath = isset($_POST['filePath']) ? sanitize_text_field(wp_unslash($_POST['filePath'])) : '';
        if (empty($filePath)) {
            wp_send_json_error(['message' => __('Backup file is missing.', 'wp-staging')]);
        }

        $backupFile = $this->backupPathResolver->resolveBackupPath($filePath);
        if (empty($backupFile) || !file_exists($backupFile)) {
            wp_send_json_error(['message' => __('Backup file not found.', 'wp-staging')]);
        }

        try {
            $metadata = (new BackupMetadata())->hydrateByFilePath($backupFile);
        } catch (\Throwable $e) {
            wp_send_json_error(['message' => $e->getMessage()]);
        }

        $folder = isset($_POST['folder']) ? sanitize_text_field(wp_unslash($_POST['folder'])) : '';
        $folder = $this->normalizeFolder($folder);

        try {
            $directories = $this->getDirectoryTree($backupFile, $metadata, $folder);
        } catch (\Throwable $e) {
            wp_send_json_error(['message' => $e->getMessage()]);
        }

        wp_send_json_success([
            'directories' => $directories,
        ]);
    }


/** Function ajaxProcess() called by wp_ajax hooks: {'nopriv_wpstg--job--status', 'nopriv_wpstg--job--heartbeat', 'wpstg--job--heartbeat', 'wpstg--job--cancel', 'wpstg--job--status'} **/
/** No params detected :-/ **/


/** Function ajaxLogs() called by wp_ajax hooks: {'wpstg_logs'} **/
/** No params detected :-/ **/


/** Function ajaxDismissCompatNotice() called by wp_ajax hooks: {'wpstg_dismiss_compat_notice'} **/
/** No params detected :-/ **/


/** Function ajaxOfferShown() called by wp_ajax hooks: {'wpstg_onboarding_offer_shown'} **/
/** No params detected :-/ **/


/** Function ajaxConfirm() called by wp_ajax hooks: {'wpstg--staging-site--delete-confirmation'} **/
/** Parameters found in function ajaxConfirm(): {"post": ["cloneId"]} **/
function ajaxConfirm()
    {
        if (!$this->canRenderAjax()) {
            wp_send_json_error('Invalid request.');
        }

        $cloneId = $this->sanitize->sanitizeString(isset($_POST['cloneId']) ? $_POST['cloneId'] : '');
        if (empty($cloneId)) {
            wp_send_json_error('Invalid request. Clone ID missing!');
        }

        $stagingSiteDto = null;
        try {
            $stagingSiteDto = $this->sites->getStagingSiteDtoByCloneId($cloneId);
        } catch (\Throwable $e) {
            wp_send_json_error($e->getMessage());
        }

        $tables    = [];
        $connected = false;
        try {
            $this->initStagingDatabase($stagingSiteDto);
            $tables    = $this->getStagingTablesStatus($stagingSiteDto->getUsedPrefix());
            $connected = true;
        } catch (\Throwable $e) {
            $tables    = [];
            $connected = false;
        }

        $result = $this->templateEngine->render(
            'staging/confirm-delete.php',
            [
                'stagingSite'         => $stagingSiteDto,
                'tables'              => $tables === null ? [] : $tables,
                'isDatabaseConnected' => $connected,
                'stagingSiteSize'     => '', 
            ]
        );

        wp_send_json_success([
            'stagingSiteName' => $stagingSiteDto->getSiteName(),
            'html'            => $result,
        ]);
    }


/** Function ajaxPurgeQueueTable() called by wp_ajax hooks: {'wpstg_purge_queue_table'} **/
/** No params detected :-/ **/


/** Function ajaxFetchDirChildren() called by wp_ajax hooks: {'wpstg_fetch_dir_children'} **/
/** Parameters found in function ajaxFetchDirChildren(): {"post": ["isChecked", "forceDefault", "dirPath", "prefix"]} **/
function ajaxFetchDirChildren()
    {
        if (!$this->isAuthenticated()) {
            wp_send_json(['success' => false]);
            return;
        }

        $isChecked    = isset($_POST['isChecked']) ? $this->sanitize->sanitizeBool($_POST['isChecked']) : false;
        $forceDefault = isset($_POST['forceDefault']) ? $this->sanitize->sanitizeBool($_POST['forceDefault']) : false;
        $path         = isset($_POST['dirPath']) ? $this->sanitize->sanitizePath($_POST['dirPath']) : "";
        $prefix       = isset($_POST['prefix']) ? $this->sanitize->sanitizePath($_POST['prefix']) : "";
        $basePath     = ABSPATH;
        if ($prefix === PathIdentifier::IDENTIFIER_WP_CONTENT) {
            $basePath = WP_CONTENT_DIR;
        }

        $path = trailingslashit($basePath) . $path;

 
        $resolvedPath = realpath($path);
        $resolvedBase = realpath($basePath);
        if ($resolvedPath === false || $resolvedBase === false) {
            wp_send_json(['success' => false]);
            return;
        }

 
        if ($resolvedPath !== $resolvedBase && strpos(trailingslashit($resolvedPath), trailingslashit($resolvedBase)) !== 0) {
            wp_send_json(['success' => false]);
            return;
        }

        $path = $resolvedPath;
        $scan = new Scan($path);
        $scan->setBasePath($basePath);
        $scan->setPathIdentifier($prefix);
        $scan->setGifLoaderPath($this->assets->getAssetsUrl('img/spinner.gif'));
        $scan->getDirectories($path);
        wp_send_json([
            "success"          => true,
            "directoryListing" => json_encode($scan->directoryListing($isChecked, $forceDefault)),
        ]);
    }


/** Function ajaxCheckJobOutcome() called by wp_ajax hooks: {'nopriv_wpstg--job--outcome', 'wpstg--job--outcome'} **/
/** Parameters found in function ajaxCheckJobOutcome(): {"post": ["token"]} **/
function ajaxCheckJobOutcome()
    {
        if (!$this->auth->isAuthenticatedRequest()) {
            wp_send_json_error(null, 401);
            return;
        }

        $token = isset($_POST['token']) ? sanitize_text_field($_POST['token']) : '';
        $job   = $this->jobTransientCache->findJobById($token);
        if ($job === null) {
            wp_send_json(['status' => 'unknown']);
            return;
        }

        $status = isset($job['status']) ? (string)$job['status'] : '';

        wp_send_json([
            'status'   => $status,
            'message'  => $this->getOutcomeMessage($status, $job),
            'severity' => isset($job['severity']) ? (string)$job['severity'] : '',
        ]);
    }


/** Function error_message() called by wp_ajax hooks: {'wpstg_job_error', 'wpstg_staging_job_error'} **/
/** No function found :-/ **/


/** Function ajaxPrepareUpload() called by wp_ajax hooks: {'wpstg--backups--prepare-upload', 'wpstg--backups--prepare-url-upload'} **/
/** Parameters found in function ajaxPrepareUpload(): {"request": ["backupUrl"]} **/
function ajaxPrepareUpload()
    {
        if (!$this->auth->isAuthenticatedRequest()) {
            wp_send_json_error([
                'message' => esc_html__('Invalid Request!', 'wp-staging'),
            ], 401);
        }

        try {
            $this->otpService->validateOtpRequest();
        } catch (OtpDisabledException $ex) {
            debug_log($ex->getMessage());
        } catch (OtpException $ex) {
            wp_send_json_error([
                'message' => esc_html($ex->getMessage()),
            ], $ex->getCode());
        }

        $backupUrl     = empty($_REQUEST['backupUrl']) ? '' : sanitize_url($_REQUEST['backupUrl']);
        $remoteFileUrl = (string)strtok($backupUrl, '?#');
        if (!$this->filesystem->isWpstgBackupFile($remoteFileUrl)) {
            wp_send_json_error([
                'message' => esc_html__('Not a valid wpstg backup file', 'wp-staging'),
            ], 403);
        }

        if ($this->ssrfProtection->isBlockedUrl($remoteFileUrl)) {
            wp_send_json_error([
                'message' => esc_html__('The URL resolves to a blocked IP address.', 'wp-staging'),
            ], 403);
        }

        if ($this->prepareUploadFromUrl($remoteFileUrl)) {
            wp_send_json_success(esc_html__('Backup upload is prepared from url', 'wp-staging'));
        }

        wp_send_json_error([
            'message' => esc_html__('Unable to prepare backup upload from url', 'wp-staging'),
        ], 500);
    }


/** Function ajaxMaybeShowModal() called by wp_ajax hooks: {'wpstg_calculate_backup_speed_index'} **/
/** No params detected :-/ **/


/** Function ajaxSelectAction() called by wp_ajax hooks: {'wpstg_onboarding_select_action'} **/
/** No params detected :-/ **/


/** Function ajaxSendEmailNotification() called by wp_ajax hooks: {'wpstg_send_mail_notification', 'nopriv_wpstg_send_mail_notification'} **/
/** Parameters found in function ajaxSendEmailNotification(): {"post": ["access_token", "subject", "body", "footer", "details", "recipient", "reply_to"]} **/
function ajaxSendEmailNotification()
    {
        $accessToken = isset($_POST['access_token']) ? sanitize_text_field($_POST['access_token']) : '';
        if (empty($accessToken) || get_transient(self::TRANSIENT_EMAIL_NOTIFICATION_ACCESS_TOKEN) !== $accessToken) {
            debug_log('Invalid/Missing access token', 'error');
            wp_send_json_error();
        }

        delete_transient(self::TRANSIENT_EMAIL_NOTIFICATION_ACCESS_TOKEN);

        $subject = isset($_POST['subject']) ? sanitize_text_field($_POST['subject']) : '';
        $body    = isset($_POST['body']) ? wp_kses_post($_POST['body']) : '';
        $footer  = isset($_POST['footer']) ? (bool)$_POST['footer'] : true;
        $details = isset($_POST['details']) ? $this->sanitize->sanitizeArray($_POST['details']) : [];
        if (empty($subject) || empty($body)) {
            debug_log('Email subject or body is empty', 'error');
            wp_send_json_error();
        }

        if (empty($_POST['recipient']) || !filter_var($_POST['recipient'], FILTER_VALIDATE_EMAIL)) {
            debug_log('Report email is not set or invalid', 'error');
            wp_send_json_error();
        }

        $attachments = $this->getPreparedAttachments();
        $replyTo     = isset($_POST['reply_to']) ? sanitize_email($_POST['reply_to']) : '';
        $result      = false;
        try {
            if (get_option(Notifications::OPTION_SEND_EMAIL_AS_HTML, false) === 'true') {
                $result = $this->notifications->sendEmailAsHTML(sanitize_email($_POST['recipient']), $subject, $body, '', $details, $attachments, $replyTo);
            } else {
                $result = $this->notifications->sendEmail(sanitize_email($_POST['recipient']), $subject, $body, '', $attachments, $footer, $replyTo);
            }

            $this->cleanupAttachments($attachments);

            if (!$result) {
                wp_send_json_error();
            }
        } catch (\Exception $error) {
            debug_log($error->getMessage(), 'error');
            wp_send_json_error();
        }

        wp_send_json_success();
    }


/** Function ajaxCheckDBPermissions() called by wp_ajax hooks: {'wpstg_check_user_permissions'} **/
/** Parameters found in function ajaxCheckDBPermissions(): {"post": ["type"]} **/
function ajaxCheckDBPermissions()
    {
        if (!$this->auth->isAuthenticatedRequest()) {
            return;
        }

        $type          = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';
        $grantsToCheck = ['CREATE', 'UPDATE', 'INSERT', 'DROP'];
        if ($type === 'push') {
            $grantsToCheck[] = 'ALTER';
        }

        if ($this->isAllowed(['ALL PRIVILEGES']) || $this->isAllowed($grantsToCheck)) {
            wp_send_json_success();
        }

        $action = !empty($type) ? $type : 'restore';
        $permissions = $action === 'push' ? 'CREATE, UPDATE, ALTER, INSERT, DROP' : 'CREATE, UPDATE, INSERT, DROP';

        $message = sprintf(
            __("The database user might not have sufficient permissions to use the %s action. Continue the process anyway by clicking the 'Proceed' button or change the user's DB permissions and resume the process.<br/><br/> Required permissions are: %s.", 'wp-staging'),
            $action,
            $permissions
        );


        $message = '<span id="wpstg-permission-info-output">' . $message . '</span>';
        $message .= '<span id="wpstg-permission-info-data">' . $this->getDebugInfo() . '</span>';
        $message .= '<br/><button type="button" id="wpstg-db-permission-show-full-message" class="wpstg-link-btn wpstg-blue-primary">' . __("Show Full Message", "wp-staging") . '</button>';

        wp_send_json_error([
            'message' => wp_kses_post($message),
        ]);
    }


/** Function startBackup() called by wp_ajax hooks: {'wpstg--backup-before-update--start'} **/
/** Parameters found in function startBackup(): {"post": ["updateType", "slug", "pluginFile", "join"]} **/
function startBackup()
    {
        $this->requireAuthorizedRequest();

        $updateType = isset($_POST['updateType']) ? Sanitize::sanitizeString($_POST['updateType']) : 'plugin';
        $slug       = isset($_POST['slug']) ? Sanitize::sanitizeString($_POST['slug']) : '';
        $pluginFile = isset($_POST['pluginFile']) ? Sanitize::sanitizeString($_POST['pluginFile']) : '';

        if (isset($_POST['join']) && Sanitize::sanitizeString($_POST['join']) === '1') {
            $this->backupRequest->queuePlugin($pluginFile);

            wp_send_json_success(['status' => $this->backupRequest->getStatus()]);
        }

        $outcome = $this->backupRequest->startForUpdate($this->getBackupData($updateType, $slug), $pluginFile);

        if ($outcome === BeforeUpdateBackupRequest::OUTCOME_ALREADY_RUNNING) {
            wp_send_json_error(['message' => esc_html__('A backup is already running.', 'wp-staging'), 'code' => 'already_running']);
        }

        if ($outcome === BeforeUpdateBackupRequest::OUTCOME_FAILED) {
            wp_send_json_error([
                'message'        => esc_html__('The backup could not be started.', 'wp-staging'),
                'code'           => 'start_failed',
                'failureReason'  => $this->health->getReason(),
                'failureDetails' => $this->health->getMessage(),
            ]);
        }

        wp_send_json_success(['status' => $this->backupRequest->getStatus()]);
    }


/** Function ajaxIsWritableCloneDestinationDir() called by wp_ajax hooks: {'wpstg_is_writable_clone_destination_dir'} **/
/** Parameters found in function ajaxIsWritableCloneDestinationDir(): {"post": ["cloneDir"]} **/
function ajaxIsWritableCloneDestinationDir()
    {
        if (!$this->auth->isAuthenticatedRequest()) {
            return;
        }

        $cloneDir = !empty($_POST['cloneDir']) ? sanitize_text_field($_POST['cloneDir']) : '';

        if (!empty($cloneDir)) {
            wp_mkdir_p($cloneDir);
            if (!is_writable($cloneDir)) {
                $directoryListing = WPStaging::getInstance()->getContainer()->get(DirectoryListing::class);
                $reason   = __('restricted folder permissions', 'wp-staging');
                $howToFix = sprintf(
                    __('Adjust the permissions for <strong>%s</strong> to <code>755</code> and follow %s step-by-step guide', 'wp-staging'),
                    esc_html($cloneDir),
                    '<a href="https://wp-staging.com/docs/folder-permission-error-folder-xy-is-not-write-and-or-readable/" target="_blank" rel="noopener noreferrer">' . esc_html__('this', 'wp-staging') . '</a>'
                );

                if (!$directoryListing->isPathInOpenBaseDir($cloneDir)) {
                    $reason   = __('the PHP open_basedir setting', 'wp-staging');
                    $howToFix = sprintf(
                        __('Add <strong>%s</strong> to your PHP <code>open_basedir</code> setting and follow %s step-by-step guide', 'wp-staging'),
                        esc_html($cloneDir),
                        '<a href="https://wp-staging.com/docs/how-to-fix-open_basedir-restriction-error/" target="_blank" rel="noopener noreferrer">' . esc_html__('this', 'wp-staging') . '</a>'
                    );
                }

                $supportLink = '<a href="' . esc_url(Language::localizeSupportUrl('https://wp-staging.com/support/')) . '" target="_blank" rel="noopener noreferrer">' . esc_html__('open a support ticket', 'wp-staging') . '</a>';
                $message = sprintf(
                    __('The directory <strong>%s</strong> is not writable due to %s.<br/>
                    <p class="wpstg-mb-10px wpstg-mt-10px"><strong>How to fix this:</strong></p>
                    <ul style="list-style:circle;" class="wpstg-ml-15px wpstg-mt-5px">
                    <li>%s.</li>
                    <li>Or %s.</li>
                    </ul>', 'wp-staging'),
                    esc_html($cloneDir),
                    esc_html($reason),
                    $howToFix,
                    $supportLink
                );

                wp_send_json_error(['message' => $message]);
            }

            wp_send_json_success();
        }

        $cloneDirRootPath = $this->dirAdapter->getAbsPath();
        if (is_writable($cloneDirRootPath)) {
            wp_send_json_success();
        }

        $cloneDir = $this->dirAdapter->getStagingSiteDirectoryInsideWpcontent();
        if ($cloneDir === false) {
            wp_send_json_error([
                'message' => sprintf(__('Clone destination dir cannot be created. Please choose another path.', 'wp-staging')),
            ]);
        }

        if (is_writable($cloneDir)) {
            wp_send_json_success();
        }

        wp_send_json_error([
            'message' => sprintf(__('Clone destination dir is not writable. Please make <code>%s</code> or <code>%s</code> writable to proceed!', 'wp-staging'), esc_html($cloneDirRootPath), esc_html($cloneDir)),
        ]);
    }


/** Function ajaxResetProcess() called by wp_ajax hooks: {'wpstg_reset'} **/
/** No params detected :-/ **/


/** Function dismissSchedule() called by wp_ajax hooks: {'wpstg--backups-dismiss-schedule'} **/
/** Parameters found in function dismissSchedule(): {"post": ["scheduleId"]} **/
function dismissSchedule()
    {
        if (!current_user_can((new Capabilities())->manageWPSTG())) {
            return;
        }

        if (!(new Nonce())->requestHasValidNonce(Nonce::WPSTG_NONCE)) {
            return;
        }

        if (empty($_POST['scheduleId'])) {
            return;
        }

        try {
            $this->deleteSchedule(Sanitize::sanitizeString($_POST['scheduleId']));
            wp_send_json_success();
        } catch (\Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }


/** Function ajaxCancelUpdate() called by wp_ajax hooks: {'wpstg_cancel_update'} **/
/** No params detected :-/ **/


/** Function markIntroSeen() called by wp_ajax hooks: {'wpstg--backup-before-update--intro-seen'} **/
/** Parameters found in function markIntroSeen(): {"post": ["surface"]} **/
function markIntroSeen()
    {
        $this->requireAuthorizedRequest();

        $surface = isset($_POST['surface']) ? Sanitize::sanitizeString($_POST['surface']) : '';
        if (!in_array($surface, UpdateProtectionSettings::INTRO_SURFACES, true)) {
            wp_send_json_error(['message' => esc_html__('Unknown surface.', 'wp-staging')], 400);
        }

        $this->updateProtectionSettings->markIntroSeen($surface);

        wp_send_json_success();
    }


/** Function ajaxSendReport() called by wp_ajax hooks: {'wpstg_send_report'} **/
/** Parameters found in function ajaxSendReport(): {"post": ["wpstg_force_send"]} **/
function ajaxSendReport($args = [])
    {
        if (!$this->isAuthenticated()) {
            return;
        }

 
        if (empty($args)) {
            $args = stripslashes_deep($_POST);
        }

 
        $emailRecipient = '';
        if (isset($args['wpstg_email'])) {
            $emailRecipient = $this->sanitize->sanitizeEmail($args['wpstg_email']);
        }

 
        $providerName = '';
        if (!empty($args['wpstg_provider'])) {
            $providerName = $this->sanitize->sanitizeString($args['wpstg_provider']);
        }

 
        $messageBody = '';
        if (!empty($args['wpstg_message'])) {
            $messageBody = $this->sanitize->sanitizeString($args['wpstg_message']);
        }

 
        $sendLogFiles = false;
        if (isset($args['wpstg_syslog'])) {
            $sendLogFiles = $this->sanitize->sanitizeBool($args['wpstg_syslog']);
        }

 
        $termsAccepted = false;
        if (isset($args['wpstg_terms'])) {
            $termsAccepted = $this->sanitize->sanitizeBool($args['wpstg_terms']);
        }

 
        $forceSend = isset($_POST['wpstg_force_send']) && $this->sanitize->sanitizeBool($_POST['wpstg_force_send']);

        $report = WPStaging::make(Report::class);
        $errors = $report->send($emailRecipient, $messageBody, $termsAccepted, $sendLogFiles, $providerName, $forceSend);

        echo json_encode(['errors' => $errors]);
        exit;
    }


