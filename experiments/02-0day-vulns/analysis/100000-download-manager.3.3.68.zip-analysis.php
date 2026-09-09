<?php
/***
*
*Found actions: 56
*Found functions:52
*Extracted functions:52
*Total parameter names extracted: 24
*Overview: {'addComment': {'wpdm_addcomment'}, 'templateServer': {'connect_template_server'}, 'updateUserStatus': {'wpdmdz_update_user_status'}, 'addShareLink': {'wpdm_newsharelink'}, 'updateLink': {'wpdm_updatelink'}, 'ajaxRunCron': {'wpdm_run_cron'}, 'newFile': {'wpdm_newfile'}, 'deleteCron': {'wpdm_delete_cron'}, 'showLockOptions': {'nopriv_showLockOptions', 'showLockOptions'}, 'saveEmailTemplate': {'wpdm_save_email_template'}, 'uploadFile': {'wpdm_admin_upload_file', 'wpdm_fm_file_upload'}, 'ajax_callback_get_packages': {'wpdm_stats_get_packages'}, 'addViewCount': {'wpdm_view_count'}, 'copyItem': {'wpdm_copypaste'}, 'mkDir': {'wpdm_mkdir'}, 'scanDir': {'wpdm_scandir'}, 'renameItem': {'wpdm_rename'}, 'rescheduleActivityReport': {'wpdm_reschedule_activity_report'}, 'iconFinder': {'wpdm_iconFinder'}, 'ajaxCreateSampleJobs': {'wpdm_create_sample_jobs'}, 'makeMediaPublic': {'make_media_public'}, 'clearCache': {'clear_cache'}, 'deleteLink': {'wpdm_deletelink'}, 'makeMediaPass': {'nopriv_wpdm_media_pass', 'wpdm_media_pass'}, 'loadSettingsPage': {'wpdm_settings'}, 'createZip': {'wpdm_createzip'}, 'generatePassword': {'wpdm_generate_password'}, 'updatePassword': {'nopriv_updatePassword'}, 'deleteItem': {'wpdm_unlink'}, 'updateTemplateStatus': {'update_template_status'}, 'resetPassword': {'nopriv_resetPassword'}, 'createDashboardPage': {'wpdm_create_dashboard_page'}, 'reviewUserStatus': {'wpdmdz_user_status'}, 'getLinkDet': {'wpdm_getlinkdet'}, 'preview': {'template_preview'}, 'openFile': {'wpdm_openfile'}, 'testRecaptcha': {'wpdm_test_recaptcha'}, 'ajaxJobAction': {'wpdm_job_action'}, 'saveFile': {'wpdm_savefile'}, 'mediaAccessControl': {'wpdm_media_access'}, 'removeNotices': {'wpdm_remove_admin_notice'}, 'moveItem': {'wpdm_cutpaste'}, 'hideProNotice': {'hide_wpdmpro_notice'}, 'clearStats': {'clear_stats'}, 'makeMediaPrivate': {'make_media_private'}, 'menuContent': {'nopriv_wpdm_get_profile_menu_content', 'wpdm_get_profile_menu_content'}, 'ajax_callback_get_users': {'wpdm_stats_get_users'}, 'unZip': {'wpdm_unzipit'}, 'sendTestActivityReport': {'wpdm_send_test_activity_report'}, 'fileSettings': {'wpdm_filesettings'}, 'activatePremiumPackage': {'wpdm-activate-shop'}, 'saveEmailSetting': {'wpdm_save_email_setting'}}
*
***/

/** Function addComment() called by wp_ajax hooks: {'wpdm_addcomment'} **/
/** Parameters found in function addComment(): {"request": ["__wpdm_addcomment"]} **/
function addComment()
    {
        if (!isset($_REQUEST['__wpdm_addcomment']) || !wp_verify_nonce($_REQUEST['__wpdm_addcomment'], NONCE_KEY)) wp_send_json(array('success' => false, 'message' => __("Error! Session Expired. Try refreshing page.", "download-manager")));
        check_ajax_referer(NONCE_KEY, '__wpdm_addcomment');
        if (!is_user_logged_in()) wp_send_json(array('success' => false, 'message' => __("Error! You are not authorized to execute this action.", "download-manager")));

        $asset_id = wpdm_query_var('assetid', 'int');
        $asset = new Asset();
        $asset->get($asset_id)->newComment(wpdm_query_var('comment', 'txts'), get_current_user_id())->save();
        wp_send_json($asset->comments);
    }


/** Function templateServer() called by wp_ajax hooks: {'connect_template_server'} **/
/** No params detected :-/ **/


/** Function updateUserStatus() called by wp_ajax hooks: {'wpdmdz_update_user_status'} **/
/** No params detected :-/ **/


/** Function addShareLink() called by wp_ajax hooks: {'wpdm_newsharelink'} **/
/** Parameters found in function addShareLink(): {"request": ["__wpdm_newsharelink"]} **/
function addShareLink()
    {
        if (!isset($_REQUEST['__wpdm_newsharelink']) || !wp_verify_nonce($_REQUEST['__wpdm_newsharelink'], WPDMAM_NONCE_KEY)) wp_send_json(array('success' => false, 'message' => __("Error! Session Expired. Try refreshing page.", "download-manager")));
        check_ajax_referer(WPDMAM_NONCE_KEY, '__wpdm_newsharelink');
        if (!current_user_can('access_server_browser')) wp_send_json(array('success' => false, 'message' => __("Error! You are not authorized to execute this action.", "download-manager")));

        $asset_ID = wpdm_query_var('asset', 'int');
        $asset = new Asset();
        $asset->get($asset_ID)->newLink(wpdm_query_var('access'))->save();
        wp_send_json($asset->links);
    }


/** Function updateLink() called by wp_ajax hooks: {'wpdm_updatelink'} **/
/** Parameters found in function updateLink(): {"request": ["__wpdm_updatelink"]} **/
function updateLink()
    {
        if (!isset($_REQUEST['__wpdm_updatelink']) || !wp_verify_nonce($_REQUEST['__wpdm_updatelink'], WPDMAM_NONCE_KEY)) wp_send_json(array('success' => false, 'message' => __("Error! Session Expired. Try refreshing page.", "download-manager")));
        check_ajax_referer(WPDMAM_NONCE_KEY, '__wpdm_updatelink');
        if (!current_user_can('access_server_browser')) wp_send_json(array('success' => false, 'message' => __("Error! You are not authorized to execute this action.", "download-manager")));

        $link_ID = wpdm_query_var('ID', 'int');
        $access = wpdm_query_var('access');
        if (!isset($access['roles'])) $access['roles'] = array();
        if (!isset($access['users'])) $access['users'] = array();
        $link = Asset::updateLink(array('access' => json_encode($access)), $link_ID);
        wp_send_json(array('success' => $link));
    }


/** Function ajaxRunCron() called by wp_ajax hooks: {'wpdm_run_cron'} **/
/** No params detected :-/ **/


/** Function newFile() called by wp_ajax hooks: {'wpdm_newfile'} **/
/** Parameters found in function newFile(): {"request": ["__wpdm_newfile"]} **/
function newFile()
    {
        global $current_user;
        if (isset($_REQUEST['__wpdm_newfile']) && !wp_verify_nonce($_REQUEST['__wpdm_newfile'], WPDMAM_NONCE_KEY)) wp_send_json(array('success' => false, 'message' => __("Error! Session Expired. Try refreshing page.", "download-manager")));
        check_ajax_referer(WPDMAM_NONCE_KEY, '__wpdm_newfile');
        if (!current_user_can('upload_files') || !current_user_can('access_server_browser')) die('Error! Unauthorized Access.');
        $root = AssetManager::root();
        $relpath = Crypt::decrypt(wpdm_query_var('path'));
        $path = AssetManager::root($relpath);
        if (!$path) wp_send_json(array('success' => false, 'message' => __("Error! Unauthorized Path.", "download-manager")));

        $name = wpdm_query_var('name', 'filename');
        //Check file is in allowed types
        if (WPDM()->fileSystem->isBlocked($name)) wp_send_json(array('success' => false, 'message' => __("Error! FileType is not allowed.", "download-manager")));

        $ret = file_put_contents($path . $name, '');
        if ($ret !== false)
            wp_send_json(array('success' => true, 'filepath' => $path . $name));
        else
            wp_send_json(array('success' => false, 'filepath' => $path . $name));

    }


/** Function deleteCron() called by wp_ajax hooks: {'wpdm_delete_cron'} **/
/** No params detected :-/ **/


/** Function showLockOptions() called by wp_ajax hooks: {'nopriv_showLockOptions', 'showLockOptions'} **/
/** Parameters found in function showLockOptions(): {"request": ["id"]} **/
function showLockOptions()
    {
        if (!isset($_REQUEST['id'])) die('ID Missing!');
        echo WPDM()->package->downloadLink(wpdm_query_var('id', 'int'), 1);
        die();
    }


/** Function saveEmailTemplate() called by wp_ajax hooks: {'wpdm_save_email_template'} **/
/** Parameters found in function saveEmailTemplate(): {"post": ["email_template"]} **/
function saveEmailTemplate(){
        if (isset($_POST['email_template'])) {
            __::isAuthentic('__setnonce', WPDM_PRI_NONCE, WPDM_ADMIN_CAP);
            $email_template = wpdm_query_var('email_template', array('validate' => array('subject' => '', 'message' => 'escs', 'from_name' => '', 'from_email' => '')));
            update_option("__wpdm_etpl_".wpdm_query_var('id'), $email_template, false);
            wp_send_json(array('success' => true, 'message' => 'Email Template Saved!'));
            //header("location: edit.php?post_type=wpdmpro&page=templates&_type=$ttype");
            die();
        }
    }


/** Function uploadFile() called by wp_ajax hooks: {'wpdm_admin_upload_file', 'wpdm_fm_file_upload'} **/
/** Parameters found in function uploadFile(): {"files": ["package_file"], "request": ["chunks"]} **/
function uploadFile() {
		check_ajax_referer( NONCE_KEY );
		if ( ! current_user_can( 'upload_files' ) ) {
			die( '-2' );
		}

		$name = isset( $_FILES['package_file']['name'] ) && ! isset( $_REQUEST["chunks"] ) ? sanitize_file_name( $_FILES['package_file']['name'] ) : wpdm_query_var( 'name', 'txt' );

		$ext = FileSystem::fileExt( $name );

		if ( WPDM()->fileSystem->isBlocked( $name, $_FILES['package_file']['tmp_name'] ) ) {
			die( '-3' );
		}

		do_action( "wpdm_before_upload_file", $_FILES['package_file'] );

		@set_time_limit( 0 );

		if ( ! file_exists( UPLOAD_DIR ) ) {
			WPDM()->createDir();
		}

		$filename = $name;

		if ( (int)get_option( '__wpdm_sanitize_filename', 0 ) === 1 ) {
			$filename = sanitize_file_name( $filename );
		} else {
			$filename = str_replace( [ "/", "\\" ], "_", $filename );
		}

		if ( file_exists( UPLOAD_DIR . $filename ) && ! isset( $_REQUEST["chunks"] ) ) {
			$filename = time() . 'wpdm_' . $filename;
		}


		if ( isset( $_REQUEST["chunks"] ) ) {
			$this->chunkUploadFile( UPLOAD_DIR . $filename );
		} else {

			$isValidMimeType = FileSystem::validateUploadMimeType($_FILES['package_file']['tmp_name'], $name);

			if(!$isValidMimeType) {
				die("|||<div class='alert alert-danger'>".sprintf(__('Upload blocked. The file type is invalid—the file extension does not match the actual content type.', 'download-manager'), '<strong>".UPLOAD_DIR."</strong>')."</div>|||");
			}

			$moved = move_uploaded_file( $_FILES['package_file']['tmp_name'], UPLOAD_DIR . $filename );
            if(!$moved) wpdmdd($_FILES);
                //die("|||<div class='alert alert-danger'>Failed to move file in upload dir <strong>".UPLOAD_DIR."</strong>. Please check dir permission or contact server support.|||</div>");
			do_action( "wpdm_after_upload_file", UPLOAD_DIR . $filename );
		}

		//$filename = apply_filters("wpdm_after_upload_file", $filename, UPLOAD_DIR);

		echo "|||" . $filename . "|||";
		exit;
	}


/** Function ajax_callback_get_packages() called by wp_ajax hooks: {'wpdm_stats_get_packages'} **/
/** No params detected :-/ **/


/** Function addViewCount() called by wp_ajax hooks: {'wpdm_view_count'} **/
/** No params detected :-/ **/


/** Function copyItem() called by wp_ajax hooks: {'wpdm_copypaste'} **/
/** Parameters found in function copyItem(): {"request": ["__wpdm_copypaste"]} **/
function copyItem()
    {
        if (!isset($_REQUEST['__wpdm_copypaste']) || !wp_verify_nonce($_REQUEST['__wpdm_copypaste'], WPDMAM_NONCE_KEY)) wp_send_json(array('success' => false, 'message' => __("Error! Session Expired. Try refreshing page.", "download-manager")));
        check_ajax_referer(WPDMAM_NONCE_KEY, '__wpdm_copypaste');
        if (!current_user_can(WPDM_ADMIN_CAP)) wp_send_json(array('success' => false, 'message' => __("Error! You are not authorized to execute this action.", "download-manager")));
        global $current_user;
        $root = AssetManager::root();
        $opath = explode("|||", wpdm_query_var('source'));
        $olddir = Crypt::decrypt($opath[0]);
        $file = end($opath);
        $oldpath = AssetManager::root($olddir . '/' . $file);
        $newpath = AssetManager::root(Crypt::decrypt(wpdm_query_var('dest'))) . $file;
        if (!strstr($oldpath, $root)) wp_send_json(array('success' => false, 'message' => __("Invalid source path", "download-manager")));
        if (!strstr($newpath, $root)) wp_send_json(array('success' => false, 'message' => __("Invalid destination path", "download-manager")));

        //Check file is in allowed types
        if (WPDM()->fileSystem->isBlocked($newpath)) wp_send_json(array('success' => false, 'message' => __("Error! FileType is not allowed.", "download-manager")));

        copy($oldpath, $newpath);

        wp_send_json(array('success' => true, 'message' => __("File copied successfully", "download-manager")));
    }


/** Function mkDir() called by wp_ajax hooks: {'wpdm_mkdir'} **/
/** Parameters found in function mkDir(): {"request": ["__wpdm_mkdir"]} **/
function mkDir()
    {
        global $current_user;
        if (isset($_REQUEST['__wpdm_mkdir']) && !wp_verify_nonce($_REQUEST['__wpdm_mkdir'], WPDMAM_NONCE_KEY)) wp_send_json(array('success' => false, 'message' => __("Error! Session Expired. Try refreshing page.", "download-manager")));
        check_ajax_referer(WPDMAM_NONCE_KEY, '__wpdm_mkdir');
        if (!current_user_can('upload_files') || !current_user_can('access_server_browser')) die('Error! Unauthorized Access.');
        $root = AssetManager::root();
        $relpath = Crypt::decrypt(wpdm_query_var('path'));
        $path = AssetManager::root($relpath);
        if (!$path) wp_send_json(array('success' => false, 'message' => __("Error! Unauthorized Path.", "download-manager")));
        $name = wpdm_query_var('name', 'filename');
        mkdir($path . $name);
        wp_send_json(array('success' => true, 'path' => $path . $name));
    }


/** Function scanDir() called by wp_ajax hooks: {'wpdm_scandir'} **/
/** Parameters found in function scanDir(): {"request": ["__wpdm_scandir"]} **/
function scanDir()
    {
        if (!isset($_REQUEST['__wpdm_scandir']) || !wp_verify_nonce($_REQUEST['__wpdm_scandir'], NONCE_KEY)) wp_send_json(array('success' => false, 'message' => __("Error! Session Expired. Try refreshing page.", "download-manager")));
        check_ajax_referer(NONCE_KEY, '__wpdm_scandir');
        //if (!current_user_can(WPDM_ADMIN_CAP)) wp_send_json(array('success' => false, 'message' => __("Error! You are not authorized to execute this action.", "download-manager")));
        if (!current_user_can('upload_files') || !current_user_can('access_server_browser')) die('Error! Unauthorized Access.');
        global $current_user;
        $root = AssetManager::root();
        $relpath = Crypt::decrypt(wpdm_query_var('path'));
        $path = AssetManager::root($relpath);
        if (!$path) wp_send_json(array('success' => false, 'message' => __("Error! Unauthorized Path.", "download-manager")));
        $keyword = null;
	    if(!wpdm_query_var('keyword', 'txt'))
		    $items = scandir($path, SCANDIR_SORT_ASCENDING);
	    else {
		    $keyword = wpdm_query_var('keyword', 'txt');
		    $items = glob( "{$path}*{$keyword}*");
		    foreach ($items as &$item) {
			    $item = str_replace($path, "", $item);
		    }
	    }
        if(!is_array($items)) $items = [];
        $items = array_diff($items, ['.', '..']);

	    if ((int)wpdm_query_var('dirs') !== 1) {
		    $page           = wpdm_query_var( 'sdpage', 'int' );
		    $page           = $page < 1 ? 1 : $page;
		    $items_per_page = $keyword ? 90 : 50;
		    $total_pages    = $keyword ? 1 : (int) ceil( count( $items ) / $items_per_page );
		    $start          = $keyword ? 0 : ( $page - 1 ) * $items_per_page;
		    $items          = array_slice( $items, $start, $items_per_page );
	    }

        $_items = [];
        $_dirs = [];
        update_user_meta(get_current_user_id(), 'working_dir', $path);
        foreach ($items as $item) {

            $item_label = $item;
            $item_label = esc_attr($item_label);
            //$item_label = strlen($item_label) > 30 ? substr($item_label, 0, 15) . "..." . substr($item_label, strlen($item_label) - 15) : $item_label;
            $ext = explode('.', $item);
            $ext = end($ext);
            $icon = FileSystem::fileTypeIcon($ext);
            $type = is_dir($path . $item) ? 'dir' : 'file';
            $note = is_dir($path . $item) ? (count(scandir($path . $item)) - 2) . ' items' : number_format((filesize($path . $item) / 1024), 2) . ' KB';
            $rpath = str_replace($root, "", $path . $item);
            $wp_rel_path = str_replace(UPLOAD_DIR, '', $path . $item);
            $wp_rel_path = str_replace(ABSPATH, '', $wp_rel_path);
            $_rpath = Crypt::encrypt($rpath);
            if ($type === 'dir') {
                $_dirs[] = array('item_label' => $item_label, 'item' => $item, 'icon' => $icon, 'type' => $type, 'note' => $note, 'path' => $_rpath, 'id' => md5($rpath));
            } else {
                $contenttype = function_exists('mime_content_type') ? mime_content_type($path . $item) : self::mimeType($item);
                $_items[] = array('item_label' => $item_label, 'item' => $item, 'icon' => $icon, 'type' => $type, 'contenttype' => $contenttype, 'note' => $note, 'path_on' => $path . $item, 'wp_rel_path' => $wp_rel_path, 'path' => $_rpath, 'id' => md5($rpath));
            }

        }

        $allitems = $_dirs;
        foreach ($_items as $_item) {
            $allitems[] = $_item;
        }
        $parts = explode("/", $relpath);
        $breadcrumb[] = "<i class='fa fa-hdd color-purple'></i><a href='#' class='media-folder' data-path=''>" . __("Home", "download-manager") . "</a>";
        $topath = array();
        foreach ($parts as $part) {
            $topath[] = $part;
            $rpath = Crypt::encrypt(implode("/", $topath));
            $breadcrumb[] = "<a href='#' class='media-folder' data-path='{$rpath}'>" . esc_attr($part) . "</a>";
        }
        $breadcrumb = implode("<i class='fa fa-folder-open'></i>", $breadcrumb);
        if ((int)wpdm_query_var('dirs') === 1)
            wp_send_json($_dirs);
        else
            wp_send_json(array('success' => true, 'total_pages' => $total_pages, 'current_page' => $page, 'items_per_page' => $items_per_page, 'items' => $allitems, 'breadcrumb' => $breadcrumb, 'root' => $root, WPDM_ADMIN_CAP => current_user_can(WPDM_ADMIN_CAP), 'roles' => $current_user->roles));
        die();
    }


/** Function renameItem() called by wp_ajax hooks: {'wpdm_rename'} **/
/** Parameters found in function renameItem(): {"request": ["__wpdm_rename"]} **/
function renameItem()
    {
        if (!isset($_REQUEST['__wpdm_rename']) || !wp_verify_nonce($_REQUEST['__wpdm_rename'], WPDMAM_NONCE_KEY)) wp_send_json(array('success' => false, 'message' => __("Error! Session Expired. Try refreshing page.", "download-manager")));
        check_ajax_referer(WPDMAM_NONCE_KEY, '__wpdm_rename');
        if (!current_user_can(WPDM_ADMIN_CAP)) wp_send_json(array('success' => false, 'message' => __("Error! You are not authorized to execute this action.", "download-manager")));
        global $current_user;
        $asset = new Asset();
        $asset->get(wpdm_query_var('assetid', 'int'));
        $root = AssetManager::root();
        $oldpath = $asset->path;
        $newpath = dirname($asset->path) . '/' . str_replace(array("/", "\\", "\"", "'"), "_", wpdm_query_var('newname'));

        if (WPDM()->fileSystem->isBlocked(wpdm_query_var('newname'))) wp_send_json(array('success' => false, 'message' => __("Error! FileType is not allowed.", "download-manager")));

        if (!strstr($newpath, $root)) die('Error!' . $newpath . " -- " . $root);
        rename($oldpath, $newpath);
        $asset->updatePath($newpath);
        wp_send_json($asset);
    }


/** Function rescheduleActivityReport() called by wp_ajax hooks: {'wpdm_reschedule_activity_report'} **/
/** No params detected :-/ **/


/** Function iconFinder() called by wp_ajax hooks: {'wpdm_iconFinder'} **/
/** No params detected :-/ **/


/** Function ajaxCreateSampleJobs() called by wp_ajax hooks: {'wpdm_create_sample_jobs'} **/
/** No params detected :-/ **/


/** Function makeMediaPublic() called by wp_ajax hooks: {'make_media_public'} **/
/** No params detected :-/ **/


/** Function clearCache() called by wp_ajax hooks: {'clear_cache'} **/
/** No params detected :-/ **/


/** Function deleteLink() called by wp_ajax hooks: {'wpdm_deletelink'} **/
/** Parameters found in function deleteLink(): {"request": ["__wpdm_deletelink"]} **/
function deleteLink()
    {
        if (!isset($_REQUEST['__wpdm_deletelink']) || !wp_verify_nonce($_REQUEST['__wpdm_deletelink'], WPDMAM_NONCE_KEY)) wp_send_json(array('success' => false, 'message' => __("Error! Session Expired. Try refreshing page.", "download-manager")));
        check_ajax_referer(WPDMAM_NONCE_KEY, '__wpdm_deletelink');
        if (!current_user_can('access_server_browser')) wp_send_json(array('success' => false, 'message' => __("Error! You are not authorized to execute this action.", "download-manager")));

        $link_ID = wpdm_query_var('linkid', 'int');
        $link = Asset::deleteLink($link_ID);
        wp_send_json(array('success' => $link));

    }


/** Function makeMediaPass() called by wp_ajax hooks: {'nopriv_wpdm_media_pass', 'wpdm_media_pass'} **/
/** No params detected :-/ **/


/** Function loadSettingsPage() called by wp_ajax hooks: {'wpdm_settings'} **/
/** No params detected :-/ **/


/** Function createZip() called by wp_ajax hooks: {'wpdm_createzip'} **/
/** Parameters found in function createZip(): {"request": ["__wpdm_createzip"]} **/
function createZip()
    {
        if (!isset($_REQUEST['__wpdm_createzip']) || !wp_verify_nonce($_REQUEST['__wpdm_createzip'], WPDMAM_NONCE_KEY)) wp_send_json(array('success' => false, 'message' => __("Error! Session Expired. Try refreshing page.", "download-manager")));
        check_ajax_referer(WPDMAM_NONCE_KEY, '__wpdm_createzip');
        //if (!current_user_can(WPDM_ADMIN_CAP)) wp_send_json(array('success' => false, 'message' => __("Error! You are not authorized to execute this action.", "download-manager")));
        if (!current_user_can(WPDM_ADMIN_CAP)) wp_send_json(array('success' => false, 'message' => __("<b>Unauthorized Action!</b><br/>Execution is cancelled by the system.", "download-manager")));
        global $current_user;
        $root = AssetManager::root();
        $relpath = Crypt::decrypt(wpdm_query_var('dir_path'));
        $path = AssetManager::root($relpath);
        if (!$path) wp_send_json(array('success' => false, 'message' => __("Error! Unauthorized Path.", "download-manager")));
        $zipped = FileSystem::zipDir($path);
        rename($zipped, untrailingslashit($path) . ".zip");
        wp_send_json(array('success' => true, 'zipped' => untrailingslashit($path) . ".zip", 'refresh' => true));
        die();
    }


/** Function generatePassword() called by wp_ajax hooks: {'wpdm_generate_password'} **/
/** No params detected :-/ **/


/** Function updatePassword() called by wp_ajax hooks: {'nopriv_updatePassword'} **/
/** No params detected :-/ **/


/** Function deleteItem() called by wp_ajax hooks: {'wpdm_unlink'} **/
/** No params detected :-/ **/


/** Function updateTemplateStatus() called by wp_ajax hooks: {'update_template_status'} **/
/** No params detected :-/ **/


/** Function resetPassword() called by wp_ajax hooks: {'nopriv_resetPassword'} **/
/** Parameters found in function resetPassword(): {"post": ["user_login"]} **/
function resetPassword()
    {
        if (wpdm_query_var('__wpdm_reset_pass')) {

            if (empty($_POST['user_login'])) {
                die('error');
            } elseif (strpos($_POST['user_login'], '@')) {
                $user_data = get_user_by('email', trim(wp_unslash($_POST['user_login'])));
                if (empty($user_data))
                    die('error');
            } else {
                $login = trim($_POST['user_login']);
                $user_data = get_user_by('login', $login);
            }
            if (Session::get('__reset_time') && time() - Session::get('__reset_time') < 60) {
                echo "toosoon";
                exit;
            }
            if (!is_object($user_data) || !isset($user_data->user_login)) die('error');
            $user_login = Crypt::encrypt($user_data->user_login);
            $user_email = $user_data->user_email;
            $key = get_password_reset_key($user_data);


            $reseturl = add_query_arg(array('action' => 'rp', 'key' => $key, 'login' => $user_login), wpdm_login_url());

            $params = array('reset_password' => $reseturl, 'to_email' => $user_email);
            Email::send('password-reset', $params);
            Session::set('__reset_time', time());
            echo 'ok';
            exit;

        }
    }


/** Function createDashboardPage() called by wp_ajax hooks: {'wpdm_create_dashboard_page'} **/
/** Parameters found in function createDashboardPage(): {"post": ["_wpnonce"]} **/
function createDashboardPage()
    {
        if (!wp_verify_nonce($_POST['_wpnonce'] ?? '', 'wpdm_create_dashboard')) {
            wp_send_json_error(__('Security check failed', 'download-manager'));
        }

        if (!current_user_can('publish_pages')) {
            wp_send_json_error(__('Permission denied', 'download-manager'));
        }

        $page_id = wp_insert_post([
            'post_title'   => __('User Dashboard', 'download-manager'),
            'post_content' => '[wpdm_user_dashboard]',
            'post_status'  => 'publish',
            'post_type'    => 'page',
        ]);

        if (is_wp_error($page_id)) {
            wp_send_json_error($page_id->get_error_message());
        }

        update_option('__wpdm_user_dashboard', $page_id);

        wp_send_json_success([
            'page_id'  => $page_id,
            'title'    => get_the_title($page_id),
            'edit_url' => get_edit_post_link($page_id, 'raw'),
        ]);
    }


/** Function reviewUserStatus() called by wp_ajax hooks: {'wpdmdz_user_status'} **/
/** No params detected :-/ **/


/** Function getLinkDet() called by wp_ajax hooks: {'wpdm_getlinkdet'} **/
/** Parameters found in function getLinkDet(): {"request": ["__wpdm_getlinkdet"]} **/
function getLinkDet()
    {
        if (!isset($_REQUEST['__wpdm_getlinkdet']) || !wp_verify_nonce($_REQUEST['__wpdm_getlinkdet'], WPDMAM_NONCE_KEY)) wp_send_json(array('success' => false, 'message' => __("Error! Session Expired. Try refreshing page.", "download-manager")));
        check_ajax_referer(WPDMAM_NONCE_KEY, '__wpdm_getlinkdet');
        if (!current_user_can('access_server_browser')) wp_send_json(array('success' => false, 'message' => __("Error! You are not authorized to execute this action.", "download-manager")));

        $link_ID = wpdm_query_var('linkid', 'int');
        $link = Asset::getLink($link_ID);
        wp_send_json($link);
    }


/** Function preview() called by wp_ajax hooks: {'template_preview'} **/
/** Parameters found in function preview(): {"request": ["template"]} **/
function preview()
    {

        $wposts = array();
        __::isAuthentic('_tplnonce', WPDM_PUB_NONCE, WPDM_ADMIN_CAP);
        $template = isset($_REQUEST['template'])?wpdm_query_var('template', 'escs'):'';
        $type = wpdm_query_var("_type");
        $css = wpdm_query_var("css","txt");


        $args=array(
            'post_type' => 'wpdmpro',
            'posts_per_page' => 1
        );

        $wposts = get_posts( $args  );
        $template = stripslashes($template);
        $template = urlencode($template);
        $tplnonce = wp_create_nonce(NONCE_KEY);
        $template_enc = Crypt::encrypt($template);
        $preview_link = home_url("/?template_preview={$template_enc}&_type={$type}&_tplnonce={$tplnonce}");

        if(count($wposts)==0) $html = "<div class='w3eden'><div class='col-md-12'><div class='alert alert-info'>".__( "No package found! Please create at least 1 package to see template preview" , "download-manager" )."</div> </div></div>";
        else
            $html = "<a class='btn btn-link btn-block' href='{$preview_link}' target='_blank'><i class='fa fa-external-link-alt'></i> Preview in a new window</a><iframe id='templateiframe' src='{$preview_link}' style='border: 0;width: 100%;height: 200px;overflow: hidden'></iframe><script>function wpdmifh( h ){ jQuery('#templateiframe').height(h); }</script>";

        echo $html;
        die();

    }


/** Function openFile() called by wp_ajax hooks: {'wpdm_openfile'} **/
/** Parameters found in function openFile(): {"request": ["__wpdm_openfile"]} **/
function openFile()
    {
        if (!isset($_REQUEST['__wpdm_openfile']) || !wp_verify_nonce($_REQUEST['__wpdm_openfile'], WPDMAM_NONCE_KEY)) wp_send_json(array('success' => false, 'message' => __("Error! Session Expired. Try refreshing page.", "download-manager")));
        check_ajax_referer(WPDMAM_NONCE_KEY, '__wpdm_openfile');
        if (!current_user_can('upload_files')) wp_send_json(array('success' => false, 'message' => __("Error! You are not authorized to execute this action.", "download-manager")));
        $relpath = Crypt::decrypt(wpdm_query_var('file'));
        $path = AssetManager::root($relpath);
        if (!$path) wp_send_json(array('success' => false, 'message' => __("Error! Unauthorized Path.", "download-manager")));
        if (file_exists($path) && is_file($path)) {
            $cid = uniqid();
            Session::set($cid, $path);
            $type = function_exists('mime_content_type') ? mime_content_type($path) : self::mimeType($path);
            $ext = explode(".", $path);
            $ext = end($ext);
            $ext = strtolower($ext);

            if (strstr("__{$type}", "text/") || in_array($ext, array('txt', 'csv', 'css', 'html', 'log')))
                wp_send_json(array('content' => file_get_contents($path), 'id' => $cid));
            else if (strstr("__{$type}", "svg"))
                wp_send_json(array('content' => '', 'embed' => file_get_contents($path), 'id' => $cid));
            else {
                $file = Crypt::decrypt(wpdm_query_var('file'));
                $file = basename($file);
                $fetchurl = home_url("/?wpdmfmdl=" . wpdm_query_var('file'));
                if (strstr("__{$type}", "image/")) {
                    $embed_code = "<img src='$fetchurl' />";
                    wp_send_json(array('content' => '', 'embed' => $embed_code, 'id' => $cid));
                }
                if (strstr("__{$type}", "audio/")) {
                    $embed_code = do_shortcode("[audio src='{$fetchurl}&file={$file}']");
                    wp_send_json(array('content' => '', 'embed' => $embed_code, 'id' => $cid));
                }
                if (strstr("__{$type}", "video/")) {
                    $embed_code = do_shortcode("[video src='{$fetchurl}&file={$file}']");
                    wp_send_json(array('content' => '', 'embed' => $embed_code, 'id' => $cid));
                }
                if ($type === 'application/pdf') {
                    //$embed_code = do_shortcode("[video src='{$fetchurl}&file={$file}']");
                    $embed_code = "<iframe style='width: 100%;height: 100%;' src='{$fetchurl}&file={$file}&play=1'></iframe><style>#filecontent_alt{ padding: 0 !important; overflow: hidden; }</style>";
                    wp_send_json(array('content' => '', 'embed' => $embed_code, 'id' => $cid));
                }
            }


        } else {
            wp_send_json(array('content' => 'Failed to open file! '. $path, 'id' => uniqid()));
            die();
        }

    }


/** Function testRecaptcha() called by wp_ajax hooks: {'wpdm_test_recaptcha'} **/
/** No params detected :-/ **/


/** Function ajaxJobAction() called by wp_ajax hooks: {'wpdm_job_action'} **/
/** No params detected :-/ **/


/** Function saveFile() called by wp_ajax hooks: {'wpdm_savefile'} **/
/** Parameters found in function saveFile(): {"request": ["__wpdm_savefile"], "post": ["content"]} **/
function saveFile()
    {
        if (!isset($_REQUEST['__wpdm_savefile']) || !wp_verify_nonce($_REQUEST['__wpdm_savefile'], WPDMAM_NONCE_KEY)) wp_send_json(array('success' => false, 'message' => __("Error! Session Expired. Try refreshing page.", "download-manager")));
        check_ajax_referer(WPDMAM_NONCE_KEY, '__wpdm_savefile');
        if (!current_user_can(WPDM_ADMIN_CAP)) wp_send_json(array('success' => false, 'message' => __("Error! You are not authorized to execute this action.", "download-manager")));

        $ofilepath = Session::get(wpdm_query_var('opened'));
        $relpath = Crypt::decrypt(wpdm_query_var('file'));
        $path = AssetManager::root($relpath);
        if (!$path) wp_send_json(array('success' => false, 'message' => __("Error! Unauthorized Path.", "download-manager")));

        if (WPDM()->fileSystem->isBlocked($path)) wp_send_json(array('success' => false, 'message' => __("Error! FileType is not allowed.", "download-manager")));

        if (file_exists($path) && is_file($path)) {
            $content = stripslashes_deep($_POST['content']);
            file_put_contents($path, $content);
            wp_send_json(array('success' => true, 'message' => 'Saved Successfully.', 'type' => 'success'));
        } else {
            wp_send_json(array('success' => false, 'message' => __("Error! Couldn't open file ( $path ).", "download-manager")));
        }

    }


/** Function mediaAccessControl() called by wp_ajax hooks: {'wpdm_media_access'} **/
/** No params detected :-/ **/


/** Function removeNotices() called by wp_ajax hooks: {'wpdm_remove_admin_notice'} **/
/** No params detected :-/ **/


/** Function moveItem() called by wp_ajax hooks: {'wpdm_cutpaste'} **/
/** Parameters found in function moveItem(): {"request": ["__wpdm_cutpaste"]} **/
function moveItem()
    {
        if (!isset($_REQUEST['__wpdm_cutpaste']) || !wp_verify_nonce($_REQUEST['__wpdm_cutpaste'], WPDMAM_NONCE_KEY)) wp_send_json(array('success' => false, 'message' => __("Error! Session Expired. Try refreshing page.", "download-manager")));
        check_ajax_referer(WPDMAM_NONCE_KEY, '__wpdm_cutpaste');
        if (!current_user_can(WPDM_ADMIN_CAP)) wp_send_json(array('success' => false, 'message' => __("Error! You are not authorized to execute this action.", "download-manager")));

        $opath = explode("|||", wpdm_query_var('source'));
        $olddir = Crypt::decrypt($opath[0]);
        $file = end($opath);

        //Check file is in allowed types
        if (WPDM()->fileSystem->isBlocked($file)) wp_send_json(array('success' => false, 'message' => __("Error! FileType is not allowed.", "download-manager")));


        $oldpath = AssetManager::root($olddir . '/' . $file);
        $newpath = AssetManager::root(Crypt::decrypt(wpdm_query_var('dest'))) . $file;
        if (!$oldpath) wp_send_json(array('success' => false, 'message' => __("Invalid source path", "download-manager")));
        if (!$newpath) wp_send_json(array('success' => false, 'message' => __("Invalid destination path", "download-manager")));
        rename($oldpath, $newpath);

        $asset = new Asset();
        $asset = $asset->get($oldpath);
        if ($asset)
            $asset->updatePath($newpath);

        wp_send_json(array('success' => true, 'message' => __("File moved successfully", "download-manager")));
    }


/** Function hideProNotice() called by wp_ajax hooks: {'hide_wpdmpro_notice'} **/
/** No params detected :-/ **/


/** Function clearStats() called by wp_ajax hooks: {'clear_stats'} **/
/** No params detected :-/ **/


/** Function makeMediaPrivate() called by wp_ajax hooks: {'make_media_private'} **/
/** No params detected :-/ **/


/** Function menuContent() called by wp_ajax hooks: {'nopriv_wpdm_get_profile_menu_content', 'wpdm_get_profile_menu_content'} **/
/** No params detected :-/ **/


/** Function ajax_callback_get_users() called by wp_ajax hooks: {'wpdm_stats_get_users'} **/
/** No params detected :-/ **/


/** Function unZip() called by wp_ajax hooks: {'wpdm_unzipit'} **/
/** Parameters found in function unZip(): {"request": ["__wpdm_unzipit"]} **/
function unZip(){
        if (!isset($_REQUEST['__wpdm_unzipit']) || !wp_verify_nonce($_REQUEST['__wpdm_unzipit'], WPDMAM_NONCE_KEY)) wp_send_json(array('success' => false, 'message' => __("Error! Session Expired. Try refreshing page.", "download-manager")));
        check_ajax_referer(WPDMAM_NONCE_KEY, '__wpdm_unzipit');
        //if (!current_user_can(WPDM_ADMIN_CAP)) wp_send_json(array('success' => false, 'message' => __("Error! You are not authorized to execute this action.", "download-manager")));
        if (!current_user_can(WPDM_ADMIN_CAP)) wp_send_json(array('success' => false, 'message' => __("<b>Unauthorized Action!</b><br/>Execution is cancelled by the system.", "download-manager")));
        global $current_user;
        $root = AssetManager::root();
        $relpath = Crypt::decrypt(wpdm_query_var('dir_path'));
        $path = AssetManager::root($relpath);
        if (!$path || FileSystem::mime_type($path) !== 'application/zip') wp_send_json(array('success' => false, 'message' => __("Error! Unauthorized Path.", "download-manager")));
        FileSystem::unZip($path);
        wp_send_json(array('success' => true, 'refresh' => true));
        die();
    }


/** Function sendTestActivityReport() called by wp_ajax hooks: {'wpdm_send_test_activity_report'} **/
/** No params detected :-/ **/


/** Function fileSettings() called by wp_ajax hooks: {'wpdm_filesettings'} **/
/** Parameters found in function fileSettings(): {"request": ["__wpdm_filesettings"]} **/
function fileSettings()
    {

        if (!isset($_REQUEST['__wpdm_filesettings']) || !wp_verify_nonce($_REQUEST['__wpdm_filesettings'], WPDMAM_NONCE_KEY)) wp_send_json(array('success' => false, 'message' => __("Error! Session Expired. Try refreshing page.", "download-manager")));
        check_ajax_referer(WPDMAM_NONCE_KEY, '__wpdm_filesettings');
        if (!current_user_can(WPDM_ADMIN_CAP)) wp_send_json(array('success' => false, 'message' => __("Error! Unauthorized Access.", "download-manager")));
        $relpath = Crypt::decrypt(wpdm_query_var('file'));
        $path = AssetManager::root($relpath);
        if (!$path) wp_send_json(array('success' => false, 'message' => __("Error! Unauthorized Path.", "download-manager")));
        if (file_exists($path)) {
            $asset = new Asset($path);
            wp_send_json($asset);
        } else {
            wp_send_json(array('success' => false, 'message' => __("Error! Unauthorized Path.", "download-manager")));
            die();
        }

    }


/** Function activatePremiumPackage() called by wp_ajax hooks: {'wpdm-activate-shop'} **/
/** No params detected :-/ **/


/** Function saveEmailSetting() called by wp_ajax hooks: {'wpdm_save_email_setting'} **/
/** No params detected :-/ **/


