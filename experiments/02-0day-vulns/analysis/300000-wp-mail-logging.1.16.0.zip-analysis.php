<?php
/***
*
*Found actions: 8
*Found functions:7
*Extracted functions:6
*Total parameter names extracted: 4
*Overview: {'product_education_dismiss': {'wp_mail_logging_product_education_dismiss'}, 'ajax_check_plugin_status': {'wp_mail_logging_smtp_page_check_plugin_status'}, 'ajax_install_wp_mail_smtp_plugin': {'wp_mail_logging_install_smtp'}, 'ajax_activate_wp_mail_smtp_plugin': {'wp_mail_logging_activate_smtp'}, 'feedback_notice_dismiss': {'wp_mail_logging_feedback_notice_dismiss'}, 'ajax_dismiss_migration_notice': {'wp_mail_logging_dismiss_db_upgrade_notice'}, 'functionName': {'nopriv_actionName', 'actionName'}}
*
***/

/** Function product_education_dismiss() called by wp_ajax hooks: {'wp_mail_logging_product_education_dismiss'} **/
/** Parameters found in function product_education_dismiss(): {"post": ["productEducationID"]} **/
function product_education_dismiss() {

        check_ajax_referer( self::DISMISS_NONCE_ACTION, 'nonce' );

        if ( empty( $_POST['productEducationID'] || ! is_super_admin() ) ) {

            wp_send_json_error(
                esc_html__( 'Request invalid.', 'wp-mail-logging' )
            );
        }

        $this->update_dismissed_option( htmlspecialchars( $_POST['productEducationID'] ) );

        wp_send_json_success();
    }


/** Function ajax_check_plugin_status() called by wp_ajax hooks: {'wp_mail_logging_smtp_page_check_plugin_status'} **/
/** No params detected :-/ **/


/** Function ajax_install_wp_mail_smtp_plugin() called by wp_ajax hooks: {'wp_mail_logging_install_smtp'} **/
/** No params detected :-/ **/


/** Function ajax_activate_wp_mail_smtp_plugin() called by wp_ajax hooks: {'wp_mail_logging_activate_smtp'} **/
/** Parameters found in function ajax_activate_wp_mail_smtp_plugin(): {"post": ["plugin"]} **/
function ajax_activate_wp_mail_smtp_plugin() {

        check_ajax_referer( self::NONCE_ACTION, 'nonce' );

        if ( ! current_user_can( 'activate_plugins' ) ) {
            wp_send_json_error( esc_html__( 'Plugin activation is disabled for you on this site.', 'wp-mail-logging' ) );
        }

        if ( empty( $_POST['plugin'] ) ) {
            wp_send_json_error( esc_html__( 'There was an error while performing your request.', 'wp-mail-logging' ) );
        }

        $activate = activate_plugins( sanitize_text_field( wp_unslash( $_POST['plugin'] ) ) );

        if ( ! is_wp_error( $activate ) ) {
            update_option( 'wp_mail_smtp_activation_prevent_redirect', true );
            add_option( 'wp_mail_smtp_source', 'wp-mail-logging' );
            wp_send_json_success( esc_html__( 'Plugin activated.', 'wp-mail-logging' ) );
        }

        wp_send_json_error( esc_html__( 'Could not activate the plugin. Please activate it on the Plugins page.', 'wp-mail-logging' ) );
    }


/** Function feedback_notice_dismiss() called by wp_ajax hooks: {'wp_mail_logging_feedback_notice_dismiss'} **/
/** Parameters found in function feedback_notice_dismiss(): {"post": ["nonce"]} **/
function feedback_notice_dismiss() {

        if ( empty( $_POST['nonce'] ) || ! check_admin_referer( self::AJAX_ACTION_NONCE, 'nonce' ) || ! is_super_admin() ) {
            wp_send_json_error();
        }

        $options              = get_option( self::OPTION_NAME, [] );
        $options['time']      = time();
        $options['dismissed'] = true;

        update_option( self::OPTION_NAME, $options );

        if ( is_multisite() ) {
            $site_list = get_sites();
            foreach ( (array) $site_list as $site ) {
                switch_to_blog( $site->blog_id );

                update_option( self::OPTION_NAME, $options );

                restore_current_blog();
            }
        }

        wp_send_json_success();
    }


/** Function ajax_dismiss_migration_notice() called by wp_ajax hooks: {'wp_mail_logging_dismiss_db_upgrade_notice'} **/
/** Parameters found in function ajax_dismiss_migration_notice(): {"post": ["nonce", "type"]} **/
function ajax_dismiss_migration_notice() {

        if (
            empty( $_POST['nonce'] ) ||
            ! check_admin_referer( self::MIGRATION_NOTICE_DISMISS_NONCE, 'nonce' ) ||
            ! current_user_can( 'manage_options' )
        ) {
            wp_send_json_error();
        }

        if ( ! empty( $_POST['type'] ) && $_POST['type'] === 'admin-notice' ) {
            update_option( self::MIGRATION_ADMIN_NOTICE_DISMISS_DB_KEY, true, false );
        } else {
            $this->dismiss_db_upgrade_banner();
        }

        wp_send_json_success();
    }


/** Function functionName() called by wp_ajax hooks: {'nopriv_actionName', 'actionName'} **/
/** No function found :-/ **/


