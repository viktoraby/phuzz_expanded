<?php
/***
*
*Found actions: 79
*Found functions:39
*Extracted functions:38
*Total parameter names extracted: 3
*Overview: {'ctAjaxCheckComments': {'ajax_check_comments'}, 'apbct_form__inevio__testSpam': {'nopriv_contact_form_handler', 'contact_form_handler'}, 'getOptionsTemplateAjax': {'get_options_template'}, 'apbct_action_adjust_reverse': {'apbct_action_adjust_reverse'}, 'settingsTemplatesResetAjax': {'settings_templates_reset'}, 'ctAjaxClearUsers': {'ajax_clear_users'}, 'apbct_settings__sync': {'apbct_sync'}, 'ctAjaxCheckUsers': {'ajax_check_users'}, 'settingsTemplatesImportAjax': {'settings_templates_import'}, 'ctGetCsvFile': {'ajax_ct_get_csv_file'}, 'apbct_comment__send_feedback': {'nopriv_ct_feedback_comment', 'ct_feedback_comment'}, 'apbct_form__seedprod_coming_soon__testSpam': {'seed_cspv5_subscribe_callback', 'nopriv_seed_cspv5_contactform_callback', 'seed_cspv5_contactform_callback', 'nopriv_seed_cspv5_subscribe_callback'}, 'detailsOrderAction': {'apbct_details_spam_order'}, 'apbct_settings__get_key_auto': {'apbct_get_key_auto'}, '\\Cleantalk\\ApbctWP\\FindSpam\\UsersChecker': {'ajax_insert_users'}, 'apbct_react_send_feedback': {'apbct_react_send_feedback'}, 'ctAjaxSpamAll': {'ajax_spam_all'}, 'ajaxDecodeEmailHandler': {'nopriv_apbct_decode_email', 'apbct_decode_email'}, 'apbct_form__the7_contact_form': {'dt_send_mail', 'nopriv_dt_send_mail'}, 'apbct_settings__update_account_email': {'apbct_update_account_email'}, 'ctAjaxInfo': {'ajax_info_users', 'ajax_info_comments'}, 'apbct_react_sync_end': {'apbct_react_sync_end'}, 'apbct_settings__save_key': {'apbct_save_key'}, 'apbct_react_access_key_check': {'apbct_react_access_key_check'}, 'restoreOrderAction': {'apbct_restore_spam_order'}, 'ctAjaxDeleteAllUsers': {'ajax_delete_all_users'}, 'setNoticeDismissed': {'cleantalk_dismiss_notice'}, 'apbct_react_brief_data': {'apbct_react_brief_data'}, 'apbct_settings__check_renew_banner': {'apbct_settings__check_renew_banner'}, 'ct_validate_email_ajaxlogin': {'validate_email', 'nopriv_validate_email'}, 'apbct_react_sfw_update': {'apbct_react_sfw_update'}, 'ctAjaxTrashAll': {'ajax_trash_all'}, 'apbct_settings__get__long_description': {'apbct_settings__get__long_description'}, 'apbct_action_adjust_change': {'apbct_action_adjust_change'}, 'ctAjaxClearComments': {'ajax_clear_comments'}, 'apbct_user__send_feedback': {'nopriv_ct_feedback_user', 'ct_feedback_user'}, 'settingsTemplatesExportAjax': {'settings_templates_export'}, 'ct_ajax_hook': {'cs_registration_validation', 'nopriv_woocommerce_checkout', 'nopriv_zn_do_login', 'nopriv_tevolution_submit_from_preview', 'send_message', 'frm_entries_create', 'request_appointment', 'wysija_ajax', 'zn_do_login', 'nopriv_request_appointment', 'nopriv_vfb_submit', 'nopriv_submit_form_recaptcha_validation', 'nopriv_send_message', 'vfb_submit', 'mymail_form_submit', 'nopriv_smuzform-storage', 'tmpl_submit_form_recaptcha_validation', 'nopriv_uwp_ajax_register', 'nopriv_mymail_form_submit', 'td_mod_register', 'tevolution_submit_from_preview', 'nopriv_td_mod_register', 'woocommerce_checkout', 'tmpl_ajax_check_user_email', 'wpuf_submit_register', 'nopriv_frm_entries_create', 'nopriv_cs_registration_validation', 'nopriv_wpuf_submit_register', 'nopriv_wysija_ajax', 'nopriv_rwp_ajax_action_rating', 'nopriv_tmpl_ajax_check_user_email'}, 'apbct_react_run_adjusting_env': {'apbct_react_run_adjusting_env'}}
*
***/

/** Function ctAjaxCheckComments() called by wp_ajax hooks: {'ajax_check_comments'} **/
/** No params detected :-/ **/


/** Function apbct_form__inevio__testSpam() called by wp_ajax hooks: {'nopriv_contact_form_handler', 'contact_form_handler'} **/
/** No params detected :-/ **/


/** Function getOptionsTemplateAjax() called by wp_ajax hooks: {'get_options_template'} **/
/** No params detected :-/ **/


/** Function apbct_action_adjust_reverse() called by wp_ajax hooks: {'apbct_action_adjust_reverse'} **/
/** No params detected :-/ **/


/** Function settingsTemplatesResetAjax() called by wp_ajax hooks: {'settings_templates_reset'} **/
/** No params detected :-/ **/


/** Function ctAjaxClearUsers() called by wp_ajax hooks: {'ajax_clear_users'} **/
/** No params detected :-/ **/


/** Function apbct_settings__sync() called by wp_ajax hooks: {'apbct_sync'} **/
/** No params detected :-/ **/


/** Function ctAjaxCheckUsers() called by wp_ajax hooks: {'ajax_check_users'} **/
/** No params detected :-/ **/


/** Function settingsTemplatesImportAjax() called by wp_ajax hooks: {'settings_templates_import'} **/
/** No params detected :-/ **/


/** Function ctGetCsvFile() called by wp_ajax hooks: {'ajax_ct_get_csv_file'} **/
/** Parameters found in function ctGetCsvFile(): {"post": ["filename"]} **/
function ctGetCsvFile()
    {
        global $apbct;
        AJAXService::checkNonceRestrictingNonAdmins('security');

        $text = 'login,email,ip,first_name,last_name,nickname' . PHP_EOL;

        $params = array(
            'meta_query' => array(
                array(
                    'key'     => 'ct_marked_as_spam',
                    'compare' => '1'
                ),
            ),
            'orderby'    => 'registered',
            'order'      => 'ASC',
        );

        $u = get_users($params);
        foreach ( $u as $iValue ) {
            // gain IP from keeper
            $ip_from_keeper = $apbct->login_ip_keeper->getIP($iValue->ID);
            $ip_from_keeper = null !== $ip_from_keeper ? $ip_from_keeper : 'N/A';
            $first_name = get_user_meta($iValue->ID, 'first_name', true);
            $first_name = !empty($first_name) ? $first_name : 'N/A';
            $last_name = get_user_meta($iValue->ID, 'last_name', true);
            $last_name = !empty($last_name) ? $last_name : 'N/A';
            $nickname = $iValue->data->user_nicename;
            $nickname = null !== $nickname ? $nickname : 'N/A';

            $text .= implode(',', array(
                self::escapeCsvField($iValue->user_login),
                self::escapeCsvField($iValue->data->user_email),
                self::escapeCsvField($ip_from_keeper),
                self::escapeCsvField($first_name),
                self::escapeCsvField($last_name),
                self::escapeCsvField($nickname),
            )) . PHP_EOL;
        }

        $filename = ! empty(Post::get('filename')) ? Post::get('filename') : false;

        if ( $filename !== false ) {
            header('Content-Type: text/csv');
            echo esc_html($text);
        } else {
            echo 'Export error.'; // file not exists or empty $_POST['filename']
        }
        die();
    }


/** Function apbct_comment__send_feedback() called by wp_ajax hooks: {'nopriv_ct_feedback_comment', 'ct_feedback_comment'} **/
/** No params detected :-/ **/


/** Function apbct_form__seedprod_coming_soon__testSpam() called by wp_ajax hooks: {'seed_cspv5_subscribe_callback', 'nopriv_seed_cspv5_contactform_callback', 'seed_cspv5_contactform_callback', 'nopriv_seed_cspv5_subscribe_callback'} **/
/** No params detected :-/ **/


/** Function detailsOrderAction() called by wp_ajax hooks: {'apbct_details_spam_order'} **/
/** No params detected :-/ **/


/** Function apbct_settings__get_key_auto() called by wp_ajax hooks: {'apbct_get_key_auto'} **/
/** No params detected :-/ **/


/** Function \Cleantalk\ApbctWP\FindSpam\UsersChecker() called by wp_ajax hooks: {'ajax_insert_users'} **/
/** No function found :-/ **/


/** Function apbct_react_send_feedback() called by wp_ajax hooks: {'apbct_react_send_feedback'} **/
/** No params detected :-/ **/


/** Function ctAjaxSpamAll() called by wp_ajax hooks: {'ajax_spam_all'} **/
/** No params detected :-/ **/


/** Function ajaxDecodeEmailHandler() called by wp_ajax hooks: {'nopriv_apbct_decode_email', 'apbct_decode_email'} **/
/** No params detected :-/ **/


/** Function apbct_form__the7_contact_form() called by wp_ajax hooks: {'dt_send_mail', 'nopriv_dt_send_mail'} **/
/** No params detected :-/ **/


/** Function apbct_settings__update_account_email() called by wp_ajax hooks: {'apbct_update_account_email'} **/
/** No params detected :-/ **/


/** Function ctAjaxInfo() called by wp_ajax hooks: {'ajax_info_users', 'ajax_info_comments'} **/
/** No params detected :-/ **/


/** Function apbct_react_sync_end() called by wp_ajax hooks: {'apbct_react_sync_end'} **/
/** No params detected :-/ **/


/** Function apbct_settings__save_key() called by wp_ajax hooks: {'apbct_save_key'} **/
/** No params detected :-/ **/


/** Function apbct_react_access_key_check() called by wp_ajax hooks: {'apbct_react_access_key_check'} **/
/** No params detected :-/ **/


/** Function restoreOrderAction() called by wp_ajax hooks: {'apbct_restore_spam_order'} **/
/** No params detected :-/ **/


/** Function ctAjaxDeleteAllUsers() called by wp_ajax hooks: {'ajax_delete_all_users'} **/
/** No params detected :-/ **/


/** Function setNoticeDismissed() called by wp_ajax hooks: {'cleantalk_dismiss_notice'} **/
/** No params detected :-/ **/


/** Function apbct_react_brief_data() called by wp_ajax hooks: {'apbct_react_brief_data'} **/
/** No params detected :-/ **/


/** Function apbct_settings__check_renew_banner() called by wp_ajax hooks: {'apbct_settings__check_renew_banner'} **/
/** No params detected :-/ **/


/** Function ct_validate_email_ajaxlogin() called by wp_ajax hooks: {'validate_email', 'nopriv_validate_email'} **/
/** No params detected :-/ **/


/** Function apbct_react_sfw_update() called by wp_ajax hooks: {'apbct_react_sfw_update'} **/
/** No params detected :-/ **/


/** Function ctAjaxTrashAll() called by wp_ajax hooks: {'ajax_trash_all'} **/
/** No params detected :-/ **/


/** Function apbct_settings__get__long_description() called by wp_ajax hooks: {'apbct_settings__get__long_description'} **/
/** No params detected :-/ **/


/** Function apbct_action_adjust_change() called by wp_ajax hooks: {'apbct_action_adjust_change'} **/
/** No params detected :-/ **/


/** Function ctAjaxClearComments() called by wp_ajax hooks: {'ajax_clear_comments'} **/
/** No params detected :-/ **/


/** Function apbct_user__send_feedback() called by wp_ajax hooks: {'nopriv_ct_feedback_user', 'ct_feedback_user'} **/
/** No params detected :-/ **/


/** Function settingsTemplatesExportAjax() called by wp_ajax hooks: {'settings_templates_export'} **/
/** No params detected :-/ **/


/** Function ct_ajax_hook() called by wp_ajax hooks: {'cs_registration_validation', 'nopriv_woocommerce_checkout', 'nopriv_zn_do_login', 'nopriv_tevolution_submit_from_preview', 'send_message', 'frm_entries_create', 'request_appointment', 'wysija_ajax', 'zn_do_login', 'nopriv_request_appointment', 'nopriv_vfb_submit', 'nopriv_submit_form_recaptcha_validation', 'nopriv_send_message', 'vfb_submit', 'mymail_form_submit', 'nopriv_smuzform-storage', 'tmpl_submit_form_recaptcha_validation', 'nopriv_uwp_ajax_register', 'nopriv_mymail_form_submit', 'td_mod_register', 'tevolution_submit_from_preview', 'nopriv_td_mod_register', 'woocommerce_checkout', 'tmpl_ajax_check_user_email', 'wpuf_submit_register', 'nopriv_frm_entries_create', 'nopriv_cs_registration_validation', 'nopriv_wpuf_submit_register', 'nopriv_wysija_ajax', 'nopriv_rwp_ajax_action_rating', 'nopriv_tmpl_ajax_check_user_email'} **/
/** Parameters found in function ct_ajax_hook(): {"post": ["data", "formInput", "ct_checkjs"], "request": ["action"]} **/
function ct_ajax_hook($message_obj = null)
{
    global $apbct, $current_user;
    $reg_flag = false;

    $message_obj = (array)$message_obj;

    // Get current_user and set it globally
    apbct_wp_set_current_user($current_user instanceof WP_User ? $current_user : apbct_wp_get_current_user());

    if ( apbct_is_skip_request(true, $message_obj) ) {
        do_action(
            'apbct_skipped_request',
            __FILE__ . ' -> ' . __FUNCTION__ . '():' . __LINE__ . '(' . apbct_is_skip_request(true) . ')',
            $_POST
        );

        return false;
    }

    //General post_info for all ajax calls
    $post_info = array(
        'comment_type' => 'feedback_ajax',
        'post_url'     => Server::get('HTTP_REFERER'), // Page URL must be an previous page
    );

    $ct_post_temp = array();

    //QAEngine Theme answers
    if ( ! empty($message_obj) && isset($message_obj['post_type'], $message_obj['post_content']) ) {
        if (isset($message_obj['author'])) {
            $curr_user = get_user_by('id', $message_obj['author']);
            if ( ! $curr_user && isset($message_obj['post_author']) ) {
                $curr_user = get_user_by('id', $message_obj['post_author']);
            }
            if ( is_object($curr_user) ) {
                $ct_post_temp['comment'] = $message_obj['post_content'];
                $ct_post_temp['email']   = $curr_user->data->user_email;
                $ct_post_temp['name']    = $curr_user->data->user_login;
            }
        }
    }

    // SiteReviews integration
    if ( Post::getString('action') === 'glsr_public_action' &&
        apbct_is_plugin_active('site-reviews/site-reviews.php')
    ) {
        $post_info['comment_type'] = 'site_reviews_integration';
        if (isset($_POST['site-reviews']['title'])) {
            $ct_post_temp['title'] = $_POST['site-reviews']['title'];
        }
        if (isset($_POST['site-reviews']['name'])) {
            $ct_post_temp['nickname'] = $_POST['site-reviews']['name'];
        }
        if (isset($_POST['site-reviews']['email'])) {
            $ct_post_temp['email'] = $_POST['site-reviews']['email'];
        }
        if (isset($_POST['site-reviews']['content'])) {
            $ct_post_temp['comment'] = $_POST['site-reviews']['content'];
        }
    }

    // Nasa registration
    if ( Post::get('action') === 'nasa_process_register' ) {
        $post_info['comment_type'] = 'nasa_process_register';
        $reg_flag = true;
    }

    //NSL integration
    if ( Post::get('action') === 'cleantalk_nsl_ajax_check' ) {
        $post_info['comment_type'] = 'contact_form_wordpress_nsl';
        $reg_flag = true;
    }

    // protect outside iframes
    if ( Post::get('action') === 'cleantalk_outside_iframe_ajax_check' ) {
        $post_info['comment_type'] = 'contact_form_wordpress_outside_iframe';
    }

    //CSCF fix
    if ( Post::get('action') === 'cscf-submitform' &&
        isset($message_obj['comment_author'], $message_obj['comment_author_email'], $message_obj['comment_content'])
    ) {
        $ct_post_temp[] = $message_obj['comment_author'];
        $ct_post_temp[] = $message_obj['comment_author_email'];
        $ct_post_temp[] = $message_obj['comment_content'];
    }

    //??? fix
    if ( Post::get('target') && (Post::get('action') === 'request_appointment' || Post::get('action') === 'send_message') ) {
        $ct_post_temp           = $_POST;
        $ct_post_temp['target'] = 1;
    }

    //UserPro fix
    if ( Post::get('action') === 'userpro_process_form' && Post::get('template') === 'register' ) {
        $ct_post_temp              = $_POST;
        $ct_post_temp['shortcode'] = '';
    }
    //Pre-filled form 426869223
    if (
        Post::get('action') !== '' &&
        Post::get('response-email-address') !== '' &&
        Post::get('response-email-sender-address') !== '' &&
        Post::get('action') === 'contact-owner:send'
    ) {
        unset($_POST['response-email-address']);
        unset($_POST['response-email-sender-address']);
    }
    //Reviewer fix
    if ( Post::get('action') === 'rwp_ajax_action_rating' ) {
        $ct_post_temp['name']    = Post::get('user_name');
        $ct_post_temp['email']   = Post::get('user_email', null, 'cleanEmail');
        $ct_post_temp['comment'] = Post::get('comment');
    }
    //Woocommerce checkout
    if ( Post::get('action') === 'woocommerce_checkout' || Post::get('action') === 'save_data' ) {
        $post_info['comment_type'] = 'order';
        if ( empty($apbct->settings['forms__wc_checkout_test']) ) {
            do_action('apbct_skipped_request', __FILE__ . ' -> ' . __FUNCTION__ . '():' . __LINE__, $_POST);

            return false;
        }
    }

    //Woocommerce.  Skip Paystation gateway service request.
    if (
        Post::get('action') === 'complete_order'  &&
        in_array('paystation_payment_gateway', array_values($_POST))
    ) {
        do_action('apbct_skipped_request', __FILE__ . ' -> ' . __FUNCTION__ . '():' . __LINE__, $_POST);
        return false;
    }
    //Easy Forms for Mailchimp
    if ( Post::get('action') === 'process_form_submission' ) {
        $post_info['comment_type'] = 'contact_enquire_wordpress_easy_forms_for_mailchimp';
        if ( Post::get('form_data') ) {
            $form_data     = explode('&', urldecode(TT::toString(Post::get('form_data'))));
            $form_data_arr = array();
            foreach ( $form_data as $val ) {
                $form_data_element                    = explode('=', $val);
                if (isset($form_data_element[0], $form_data_element[1])) {
                    $form_data_arr[$form_data_element[0]] = @$form_data_element[1];
                }
            }
            $ct_post_temp = array();
            if ( isset($form_data_arr['EMAIL']) ) {
                $ct_post_temp['email'] = $form_data_arr['EMAIL'];
            }
            if ( isset($form_data_arr['FNAME']) ) {
                $ct_post_temp['nickname'] = $form_data_arr['FNAME'];
            }
        }
    }

    // FixTeam Integration - preparation data for post filter
    if (
        apbct_is_theme_active('fixteam') &&
        Post::equal('action', 'send_sc_form') &&
        Post::get('data') !== ''
    ) {
        $form_data = Post::get('data');
        $form_data = explode('&', TT::toString($form_data));

        for ($index = 0; $index < count($form_data); $index++) {
            if (stripos($form_data[$index], 'apbct_visible_fields') === 0) {
                unset($form_data[$index]);
            }
        }

        $form_data = implode('&', $form_data);
        parse_str($form_data, $ct_post_temp);
        $_POST['data'] = $form_data;
    }

    if (Post::hasString('action', 'rx_front_end_review_submit')) {
        if (Post::get('formInput')) {
            $form_data = Post::get('formInput');
            $prepare_form_data = array();

            if (is_array($form_data)) {
                foreach ($form_data as $row) {
                    if (isset($row['name']) && $row['name'] === 'apbct_visible_fields') {
                        continue;
                    }

                    if (isset($row['name']) && $row['name'] === 'ct_bot_detector_event_token') {
                        continue;
                    }

                    if (isset($row['name']) && $row['name'] === 'ct_no_cookie_hidden_field') {
                        if ($apbct->data['cookies_type'] === 'none') {
                            $no_cookie_data = isset($row['value']) ? $row['value'] : '';
                            $apbct->stats['no_cookie_data_taken'] = \Cleantalk\ApbctWP\Variables\NoCookie::setDataFromHiddenField($no_cookie_data);
                            $apbct->save('stats');
                        }
                        continue;
                    }

                    $prepare_form_data[] = $row;
                }
            }

            $_POST['formInput'] = $prepare_form_data;
        }
    }

    // thriveleads modification to check gravity forms
    if ( Post::get('action') === 'tve_api_form_submit' ) {
        unset($_POST['ct_checkjs']);
    }

    /**
     * Filter for POST
     */
    if (!empty($ct_post_temp)) {
        $input_array = apply_filters('apbct__filter_post', $ct_post_temp);
    } else {
        $input_array = apply_filters('apbct__filter_post', $_POST);
    }

    //btQuoteBooking admin_email exclude from the form data
    if ( Post::get('action') === 'bt_cc' ) {
        unset($input_array['admin_email']);
    }

    $ct_temp_msg_data = ct_get_fields_any($input_array);

    $sender_email    = isset($ct_temp_msg_data['email']) ? $ct_temp_msg_data['email'] : '';
    $sender_emails_array = isset($ct_temp_msg_data['emails_array']) ? $ct_temp_msg_data['emails_array'] : '';
    $sender_nickname = isset($ct_temp_msg_data['nickname']) ? $ct_temp_msg_data['nickname'] : '';
    $subject         = isset($ct_temp_msg_data['subject']) ? $ct_temp_msg_data['subject'] : '';
    $contact_form    = isset($ct_temp_msg_data['contact']) ? $ct_temp_msg_data['contact'] : true;
    $message         = isset($ct_temp_msg_data['message']) ? $ct_temp_msg_data['message'] : array();
    if ( $subject !== '' ) {
        $message['subject'] = $subject;
    }

    // Skip submission if no data found
    if ( $contact_form === false ) {
        do_action('apbct_skipped_request', __FILE__ . ' -> ' . __FUNCTION__ . '():' . __LINE__, $_POST);

        return false;
    }

    // Mailpoet fix
    if ( isset($message['wysijaData'], $message['wysijaplugin'], $message['task'], $message['controller']) && $message['wysijaplugin'] === 'wysija-newsletters' && $message['controller'] === 'campaigns' ) {
        do_action('apbct_skipped_request', __FILE__ . ' -> ' . __FUNCTION__ . '():' . __LINE__, $_POST);

        return false;
    }

    // Mailpoet3 admin skip fix
    if ( Post::get('action') === 'mailpoet' && Post::get('method') === 'save' ) {
        do_action('apbct_skipped_request', __FILE__ . ' -> ' . __FUNCTION__ . '():' . __LINE__, $_POST);

        return false;
    }


    // WP Foto Vote Fix
    if ( ! empty($_FILES) ) {
        foreach ( $message as $key => $_value ) {
            if ( strpos($key, 'oje') !== false ) {
                do_action('apbct_skipped_request', __FILE__ . ' -> ' . __FUNCTION__ . '():' . __LINE__, $_POST);

                return false;
            }
        }
    }

    /**
     * @todo Contact form detect
     */
    // Detect contact form an set it's name to $contact_form to use later
    $contact_form = null;
    foreach ( $_POST as $param => $_value ) {
        if ( strpos((string)$param, 'et_pb_contactform_submit') === 0 ) {
            $contact_form = 'contact_form_divi_theme';
        }
        if ( strpos((string)$param, 'avia_generated_form') === 0 ) {
            $contact_form = 'contact_form_enfold_theme';
        }
        if ( ! empty($contact_form) ) {
            break;
        }
    }

    $base_call_params = array(
        'message'         => $message,
        'sender_email'    => $sender_email,
        'sender_nickname' => $sender_nickname,
        'post_info'       => $post_info,
    );

    if ( apbct_is_exception_arg_request() ) {
        $base_call_params['exception_action'] = 1;
        $base_call_params['sender_info']['exception_description'] = apbct_is_exception_arg_request();
        $base_call_params['sender_info']['sender_emails_array'] = $sender_emails_array;
    }

    // EZ Form Calculator - clearing the message
    if (
        apbct_is_plugin_active('ez-form-calculator-premium/ezfc.php') &&
        Post::equal('action', 'ezfc_frontend')
    ) {
        if (!is_array($message)) {
            $message = preg_split('/\r\n|\r|\n/', $message);
        }

        foreach ($message as $key => $string) {
            if (
                $string === '__HIDDEN__' ||
                $string === '0' ||
                (int)$string !== 0
            ) {
                unset($message[$key]);
            }
        }

        $base_call_params['message'] = implode('\n', $message);
    }

    $base_call_result = apbct_base_call($base_call_params, $reg_flag);

    if (!isset($base_call_result['ct_result'])) {
        return null;
    }

    $ct_result = $base_call_result['ct_result'];

    if ( $ct_result->allow == 0 ) {
        if ( Post::get('action') === 'wpuf_submit_register' ) {
            $result = array('success' => false, 'error' => $ct_result->comment);
            @header('Content-Type: application/json; charset=' . get_option('blog_charset'));
            print json_encode($result);
            die();
        }

        if ( Post::getString('action') === 'glsr_public_action' ) {
            $result = array(
                'success' => false,
                'data' => array(
                    'errors' => array(),
                    'message' => $ct_result->comment,
                ),
            );
            @header('Content-Type: application/json; charset=' . get_option('blog_charset'));
            print json_encode($result);
            die();
        }

        if ( Post::get('action') === 'mymail_form_submit' ) {
            $result = array('success' => false, 'html' => $ct_result->comment);
            @header('Content-Type: application/json; charset=' . get_option('blog_charset'));
            print json_encode($result);
            die();
        }

        if ( Post::get('action') === 'cs_registration_validation' ) {
            $result = array("type" => "error", "message" => $ct_result->comment);
            print json_encode($result);
            die();
        }

        if ( Post::get('action') === 'request_appointment' || Post::get('action') === 'send_message' ) {
            print $ct_result->comment;
            die();
        }

        if ( Post::get('action') === 'zn_do_login' ) {
            print '<div id="login_error">' . $ct_result->comment . '</div>';
            die();
        }

        if ( Post::get('action') === 'vfb_submit' ) {
            $result = array('result' => false, 'message' => $ct_result->comment);
            @header('Content-Type: application/json; charset=' . get_option('blog_charset'));
            print json_encode($result);
            die();
        }

        if ( Post::get('action') === 'woocommerce_checkout' ) {
            print $ct_result->comment;
            die();
        }

        if ( Post::get('cma-action') === 'add' ) {
            $result = array('success' => 0, 'thread_id' => null, 'messages' => array($ct_result->comment));
            print json_encode($result);
            die();
        }

        if ( Post::get('action') === 'td_mod_register' ) {
            print json_encode(array('register', 0, $ct_result->comment));
            die();
        }

        if ( Post::get('action') === 'tmpl_ajax_check_user_email' ) {
            print "17,email";
            die();
        }

        if ( Post::get('action') === 'tevolution_submit_from_preview' || Post::get('action') === 'submit_form_recaptcha_validation' ) {
            print $ct_result->comment;
            die();
        }

        // WooWaitList
        // http://codecanyon.net/item/woowaitlist-woocommerce-back-in-stock-notifier/7103373
        if ( Post::get('action') === 'wew_save_to_db_callback' ) {
            $result            = array();
            $result['error']   = 1;
            $result['message'] = $ct_result->comment;
            $result['code']    = 5; // Unused code number in WooWaitlist
            print json_encode($result);
            die();
        }

        // UserPro
        if ( Post::get('action') === 'userpro_process_form' && Post::get('template') === 'register' ) {
            $output = array();
            foreach ( $_POST as $key => $value ) {
                $output[\Cleantalk\ApbctWP\Sanitize::cleanXss($key)] = \Cleantalk\ApbctWP\Sanitize::cleanXss($value);
            }
            $output['template'] = $ct_result->comment;
            $output             = json_encode($output);
            print_r($output);
            die;
        }

        // Quick event manager
        if ( Post::get('action') === 'qem_validate_form' ) {
            $errors = array();
            $errors[] = 'registration_forbidden';
            $result   = array(
                'success' => 'false',
                'errors'  => $errors,
                'title'   => $ct_result->comment
            );
            print json_encode($result);
            die();
        }

        // Quick Contact Form
        if ( Post::get('action') === 'qcf_validate_form' ) {
            $result = array(
                'blurb'   => "<h1>" . $ct_result->comment . "</h1>",
                'display' => "Oops, got a few problems here",
                'errors'  => array(
                    0 => array(
                        'error' => 'error',
                        'name'  => 'name'
                    ),
                ),
                'success' => 'false',
            );
            print json_encode($result);
            die();
        }

        // Usernoise Contact Form
        if (
            Post::get('title') &&
            Post::get('email') &&
            Post::get('type') &&
            Post::get('ct_checkjs')
        ) {
            return array($ct_result->comment);
        }

        // amoForms
        if ( Post::get('action') === 'amoforms_submit' ) {
            $result = array(
                'result' => true,
                'type'   => "html",
                'value'  => "<h1 style='font-size: 25px; color: red;'>" . $ct_result->comment . "</h1>",
                'fast'   => false
            );
            print json_encode($result);
            die();
        }

        // MailChimp for Wordpress Premium
        if ( ! empty(Post::get('_mc4wp_form_id')) ) {
            return 'ct_mc4wp_response';
        }

        // QAEngine Theme answers
        if ( ! empty($message_obj) && isset($message_obj['post_type'], $message_obj['post_content']) ) {
            throw new Exception($ct_result->comment);
        }

        //Convertplug. Strpos because action value dynamically changes and depends on mailing service
        if ( strpos(TT::toString(Post::get('action')), '_add_subscriber') !== false ) {
            $result = array(
                'action'       => "message",
                'detailed_msg' => "",
                'email_status' => false,
                'message'      => "<h1 style='font-size: 25px; color: red;'>" . $ct_result->comment . "</h1>",
                'status'       => "error",
                'url'          => "none"
            );
            print json_encode($result);
            die();
        }

        //cFormsII
        if ( Post::get('action') === 'submitcform' ) {
            header('Content-Type: application/json');
            $result = array(
                'no'          => Post::get('cforms_id', null, 'xss'),
                'result'      => 'failure',
                'html'        => $ct_result->comment,
                'hide'        => false,
                'redirection' => null
            );
            print json_encode($result);
            die();
        }

        //Contact Form by Web-Settler
        if ( Post::get('smFieldData') ) {
            $result = array(
                'signal'      => true,
                'code'        => 0,
                'thanksMsg'   => $ct_result->comment,
                'errors'      => array(),
                'isMsg'       => true,
                'redirectUrl' => null
            );
            print json_encode($result);
            die();
        }

        //Reviewer
        if ( Post::get('action') === 'rwp_ajax_action_rating' ) {
            $result = array(
                'success' => false,
                'data'    => array(0 => $ct_result->comment)
            );
            print json_encode($result);
            die();
        }

        // CouponXXL Theme
        if (
            Post::get('_wp_http_referer') &&
            Post::get('register_field') &&
            Post::get('action') &&
            strpos(TT::toString(Post::get('_wp_http_referer')), '/register/account') !== false &&
            Post::get('action') === 'register'
        ) {
            $result = array(
                'message' => '<div class="alert alert-error">' . $ct_result->comment . '</div>',
            );
            die(json_encode($result));
        }

        //ConvertPro
        if ( Post::get('action') === 'cp_v2_notify_admin' || Post::get('action') === 'cpro_notify_via_email' ) {
            $result = array(
                'success' => false,
                'data'    => array('error' => 'Invalid email address.', 'style_slug' => 'convertprot-form'), //cannot use custom message because of ConvertPro JS error message forcing
            );
            print json_encode($result);
            die();
        }

        //Easy Forms for Mailchimp
        if ( Post::get('action') === 'process_form_submission' ) {
            wp_send_json_error(
                array(
                    'error'    => 1,
                    'response' => $ct_result->comment
                )
            );
        }

        //Optin wheel
        if ( Post::get('action') === 'wof-lite-email-optin' || Post::get('action') === 'wof-email-optin' ) {
            wp_send_json_error(__($ct_result->comment, 'wp-optin-wheel'));
        }

        // Forminator
        if ( strpos(TT::toString(Post::get('action')), 'forminator_submit') !== false ) {
            wp_send_json_error(
                array(
                    'message' => $ct_result->comment,
                    'success' => false,
                    'errors'  => array(),
                    'behav'   => 'behaviour-thankyou',
                )
            );
        }

        // Easy Registration Form
        if ( strpos(TT::toString(Post::get('action')), 'erf_submit_form') !== false ) {
            wp_send_json_error(array(0 => array('username_error', $ct_result->comment)));
        }

        // Site Reviews Integration
        if ( Post::hasString('action', 'glsr_action') ) {
            wp_send_json_error(
                array(
                    'code' => 'CODE',
                    'error' => 'ERROR',
                    'message' => $ct_result->comment,
                    'notices' => 'NOTICES',
                )
            );
        }

        if (
            apbct_is_plugin_active('qsm-save-resume/qsm-save-resume.php') &&
            Post::hasString('action', 'qmn_process_quiz')
        ) {
            die(
                json_encode(
                    array(
                        'quizExpired' => false,
                        'display' => $ct_result->comment,
                        'redirect' => '',
                        'result_status' => array(
                            'save_response' => 0
                        )
                    )
                )
            );
        }

        // brick plugin or theme
        if (
            (
                apbct_is_plugin_active('bricksextras/bricksextras.php') ||
                apbct_is_theme_active('bricks')
            ) &&
            Post::hasString('action', 'bricks_form_submit')
        ) {
            die(
                json_encode(
                    array(
                        'success' => false,
                        'data' => array(
                            'type' => 'error',
                            'message' => $ct_result->comment,
                        )
                    )
                )
            );
        }

        // Plugin Name: DIGITS: WordPress Mobile Number Signup and Login; ajax register action digits_forms_ajax
        if (
            apbct_is_plugin_active('digits/digit.php') &&
            Post::get('action') === 'digits_forms_ajax' &&
            Post::get('type') === 'register'
        ) {
            wp_send_json_error(
                array(
                    'message' => $ct_result->comment
                )
            );
            die();
        }

        // Plugin Name: eForm - WordPress Form Builder; ajax action ipt_fsqm_save_form
        if (
            apbct_is_plugin_active('wp-fsqm-pro/ipt_fsqm.php') &&
            Post::get('action') === 'ipt_fsqm_save_form'
        ) {
            $return = array(
                'success' => false,
                'errors' => array(
                    0 => array(
                        'id' => '',
                        'msgs' => array( $ct_result->comment ),
                    ),
                ),
            );
            echo json_encode((object)$return);
            die();
        }

        if ( Post::get('action') === 'rx_front_end_review_submit' ) {
            wp_send_json($ct_result->comment);
        }

        // Plugin Name: Nextend Social Login and Register; jax register action cleantalk_nsl_ajax_check
        if ( Post::get('action') === 'cleantalk_nsl_ajax_check' ) {
            echo json_encode(
                array(
                    'apbct' => array(
                        'blocked'     => true,
                        'comment'     => $ct_result->comment,
                        'stop_script' => apbct__stop_script_after_ajax_checking()
                    )
                )
            );
            die();
        }

        // protect outside iframes
        if ( Post::get('action') === 'cleantalk_outside_iframe_ajax_check' ) {
            echo json_encode(
                array(
                    'apbct' => array(
                        'blocked'     => true,
                        'comment'     => $ct_result->comment,
                    )
                )
            );
            die();
        }

        if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'wpmlsubscribe') {
            $block_msg = sprintf('<div class="newsletters-acknowledgement"><p style="color: darkred">%s</p></div>', $ct_result->comment);
            echo $block_msg;
            die();
        }

        // Porto theme register action on login popup
        if (Post::get('action') === 'porto_account_login_popup_register') {
            echo json_encode(
                array(
                    'loggedin' => false,
                    'message'  => $ct_result->comment,
                )
            );
            die();
        }

        // ACF forms
        if ( Post::get('action') === 'acf/validate_save_post' ) {
            echo json_encode(
                array(
                    'success' => true,
                    'data'    => array(
                        'valid'  => 0,
                        'errors' => array(
                            array(
                                'message' => $ct_result->comment,
                            )
                        )
                    )
                )
            );
            die();
        }

        // Indeed Coming Soon
        // Works only with special Indeed plugin version. Look at https://doboard.com/1/task/31617#comment_207324
        if (
            Post::getString('action') === 'ics_save_email_subscribe' ||
            Post::getString('action') === 'ics_send_email_fc'
        ) {
            wp_send_json_error($ct_result->comment);
        }

        // Regular block output
        die(
            json_encode(
                array(
                    'apbct' => array(
                        'blocked'     => true,
                        'comment'     => $ct_result->comment,
                        'stop_script' => apbct__stop_script_after_ajax_checking()
                    )
                )
            )
        );
    }

    // Allow == 1
    //QAEngine Theme answers
    if ( ! empty($message_obj) && isset($message_obj['post_type'], $message_obj['post_content']) ) {
        return $message_obj;
    }

    // Nextend Social Login and Register AJAX check
    if ( Post::get('action') === 'cleantalk_nsl_ajax_check' ) {
        die(
            json_encode(
                array(
                    'apbct' => array(
                        'blocked' => false,
                        'allow'   => true,
                        'comment' => $ct_result->comment,
                    )
                )
            )
        );
    }

    // protect outside iframes
    if ( Post::get('action') === 'cleantalk_outside_iframe_ajax_check' ) {
        die(
            json_encode(
                array(
                    'apbct' => array(
                        'blocked' => false,
                        'allow'   => true,
                        'comment' => $ct_result->comment,
                    )
                )
            )
        );
    }

    return null;
}


/** Function apbct_react_run_adjusting_env() called by wp_ajax hooks: {'apbct_react_run_adjusting_env'} **/
/** No params detected :-/ **/


