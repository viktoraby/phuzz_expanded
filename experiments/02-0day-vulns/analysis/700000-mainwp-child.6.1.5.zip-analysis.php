<?php
/***
*
*Found actions: 3
*Found functions:3
*Extracted functions:3
*Total parameter names extracted: 3
*Overview: {'download_archive': {'mainwp_backupbuddy_download_archive'}, 'download_htaccess': {'mainwp_wordfence_download_htaccess'}, 'callback_change_destroy_user_session': {'destroy-sessions'}}
*
***/

/** Function download_archive() called by wp_ajax hooks: {'mainwp_backupbuddy_download_archive'} **/
/** Parameters found in function download_archive(): {"get": ["_wpnonce"]} **/
function download_archive() { // NOSONAR - WP compatible.

        if ( ! isset( $_GET['_wpnonce'] ) || empty( $_GET['_wpnonce'] ) ) {
            die( '-1' );
        }

        if ( ! MainWP_Utility::verify_nonce_without_session( $_GET['_wpnonce'], 'mainwp_download_backup' ) ) {
            die( '-2' );
        }

        \backupbuddy_core::verifyAjaxAccess();

        if ( is_multisite() && ! current_user_can( 'manage_network' ) && ! strstr( \pb_backupbuddy::_GET( 'backupbuddy_backup' ), \backupbuddy_core::backup_prefix() ) ) {
            die( 'Access Denied. You may only download backups specific to your Multisite Subsite. Only Network Admins may download backups for another subsite in the network.' );
        }

        if ( ! file_exists( \backupbuddy_core::getBackupDirectory() . \pb_backupbuddy::_GET( 'backupbuddy_backup' ) ) ) { // Does not exist.
            die( 'Error #548957857584784332. The requested backup file does not exist. It may have already been deleted.' );
        }

        $abspath    = str_replace( '\\', '/', ABSPATH );
        $backup_dir = str_replace( '\\', '/', \backupbuddy_core::getBackupDirectory() );

        if ( false === stristr( $backup_dir, $abspath ) ) {
            die( 'Error #5432532. You cannot download backups stored outside of the WordPress web root. Please use FTP or other means.' );
        }

        $sitepath     = str_replace( $abspath, '', $backup_dir );
        $download_url = rtrim( site_url(), '/\\' ) . '/' . trim( $sitepath, '/\\' ) . '/' . \pb_backupbuddy::_GET( 'backupbuddy_backup' );

        if ( '1' === \pb_backupbuddy::$options['lock_archives_directory'] ) {

            if ( file_exists( \backupbuddy_core::getBackupDirectory() . '.htaccess' ) ) {
                $unlink_status = wp_delete_file( \backupbuddy_core::getBackupDirectory() . '.htaccess' );
                if ( false === $unlink_status ) {
                    die( 'Error #844594. Unable to temporarily remove .htaccess security protection on archives directory to allow downloading. Please verify permissions of the BackupBuddy archives directory or manually download via FTP.' );
                }
            }

            header( 'Location: ' . $download_url );
            ob_clean();
            flush();
            sleep( 8 );

            $htaccess_creation_status = MainWP_Helper::file_put_contents( \backupbuddy_core::getBackupDirectory() . '.htaccess', 'deny from all' ); //phpcs:ignore WordPress.WP.AlternativeFunctions
            if ( false === $htaccess_creation_status ) {
                die( 'Error #344894545. Security Warning! Unable to create security file (.htaccess) in backups archive directory. This file prevents unauthorized downloading of backups should someone be able to guess the backup location and filenames. This is unlikely but for best security should be in place. Please verify permissions on the backups directory.' );
            }
        } else {
            header( 'Location: ' . $download_url );
        }
        die();
    }


/** Function download_htaccess() called by wp_ajax hooks: {'mainwp_wordfence_download_htaccess'} **/
/** Parameters found in function download_htaccess(): {"get": ["_wpnonce"]} **/
function download_htaccess() {
        if ( ! isset( $_GET['_wpnonce'] ) || empty( $_GET['_wpnonce'] ) ) {
            die( '-1' );
        }

        if ( ! MainWP_Utility::verify_nonce_without_session( $_GET['_wpnonce'], 'mainwp_download_htaccess' ) ) {
            die( '-2' );
        }

        $url = site_url();
        $url = preg_replace( '/^https?:\/\//i', '', $url );
        $url = preg_replace( '/[^a-zA-Z0-9\.]+/', '_', $url );
        $url = preg_replace( '/^_+/', '', $url );
        $url = preg_replace( '/_+$/', '', $url );
        header( 'Content-Type: application/octet-stream' );
        header( 'Content-Disposition: attachment; filename="htaccess_Backup_for_' . $url . '.txt"' );
        $file = \wfCache::getHtaccessPath();
        readfile( $file );
        die();
    }


/** Function callback_change_destroy_user_session() called by wp_ajax hooks: {'destroy-sessions'} **/
/** Parameters found in function callback_change_destroy_user_session(): {"post": ["user_id", "nonce"]} **/
function callback_change_destroy_user_session() {

        if ( ! isset( $_POST['user_id'] ) ) {
            return;
        }

        $user = \get_userdata( (int) $_POST['user_id'] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

        if ( $user && ( ! current_user_can( 'edit_user', $user->ID ) || ( isset( $_POST['nonce'] ) && ! \wp_verify_nonce( $_POST['nonce'], 'update-user_' . $user->ID ) ) ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
            $user = false;
        }

        if ( $user ) {
            $log_data = array(
                'changeuserid' => $user->ID,
            );
            Changes_Logs_Logger::log_change( 1585, $log_data );
        }
    }


