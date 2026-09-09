<?php
/***
*
*Found actions: 11
*Found functions:11
*Extracted functions:11
*Total parameter names extracted: 10
*Overview: {'save_ajax': {'grw_feed_save_ajax'}, 'hide_review': {'grw_hide_review'}, 'rateus_ajax': {'grw_rateus_ajax'}, 'overview_ajax': {'grw_overview_ajax'}, 'feedback': {'grw_deactivate_feedback'}, 'snooze': {'grw_rev_notice'}, 'connect_google': {'grw_connect_google'}, 'delete_place': {'grw_delete_place'}, 'rateus_ajax_feedback': {'grw_rateus_ajax_feedback'}, 'place_autocomplete': {'grw_place_autocomplete'}, 'get_place': {'grw_get_place'}}
*
***/

/** Function save_ajax() called by wp_ajax hooks: {'grw_feed_save_ajax'} **/
/** Parameters found in function save_ajax(): {"post": ["post_id", "title", "content"]} **/
function save_ajax() {

        $post_id = $this->feed_serializer->save(
            isset($_POST['post_id']) ? $_POST['post_id'] : '',
            isset($_POST['title'])   ? $_POST['title']   : '',
            isset($_POST['content']) ? $_POST['content'] : ''
        );

        if ($post_id) {
            $feed = $this->feed_deserializer->get_feed($post_id);

            if ($feed) {
                $data = $this->core->get_reviews($feed, true);
                $businesses = $data['businesses'];
                $reviews = $data['reviews'];
                $options = $data['options'];

                echo $this->view->render($feed->ID, $businesses, $reviews, $options, true);
            }
        }

        wp_die();
    }


/** Function hide_review() called by wp_ajax hooks: {'grw_hide_review'} **/
/** Parameters found in function hide_review(): {"post": ["grw_wpnonce", "id", "feed_id"]} **/
function hide_review() {
        global $wpdb;

        if (current_user_can('editor') || current_user_can('administrator')) {
            if (isset($_POST['grw_wpnonce']) === false) {
                $error = __('Unable to call request. Make sure you are accessing this page from the Wordpress dashboard.', 'widget-google-reviews');
                $response = compact('error');
            } else {
                check_admin_referer('grw_wpnonce', 'grw_wpnonce');

                $review_id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

                $review = $review_id ? $wpdb->get_row(
                    $wpdb->prepare(
                        "SELECT * FROM " . $wpdb->prefix . Database::REVIEW_TABLE .
                        " WHERE id = %d", $review_id
                    )
                ) : null;

                if (!$review) {
                    header('Content-type: text/javascript');
                    echo json_encode(array('error' => __('Review not found.', 'widget-google-reviews')));
                    die();
                }

                $hide = $review->hide == '' ? 'y' : '';
                $wpdb->update($wpdb->prefix . Database::REVIEW_TABLE, array('hide' => $hide), array('id' => $review_id));

                $this->clear_feed_cache(isset($_POST['feed_id']) ? $_POST['feed_id'] : null);

                $response = array('hide' => $hide);
            }
            header('Content-type: text/javascript');
            echo json_encode($response);
            die();
        }
    }


/** Function rateus_ajax() called by wp_ajax hooks: {'grw_rateus_ajax'} **/
/** Parameters found in function rateus_ajax(): {"post": ["rate"]} **/
function rateus_ajax() {
        $this->check_nonce();

        $rate = isset($_POST['rate']) ? trim(sanitize_text_field(wp_unslash($_POST['rate']))) : '';
        update_option('grw_rate_us', time() . ':' . $rate);
        echo json_encode(array('rate' => $rate));

        die();
    }


/** Function overview_ajax() called by wp_ajax hooks: {'grw_overview_ajax'} **/
/** Parameters found in function overview_ajax(): {"post": ["place_id"]} **/
function overview_ajax() {
        if (!current_user_can('manage_options')) {
            die('The account you\'re logged in to doesn\'t have permission to access this page.');
        }

        check_admin_referer('grw_wpnonce', 'grw_nonce');
        
        $overview = $this->core->get_overview(isset($_POST['place_id']) ? $_POST['place_id'] : 0);
        echo json_encode($overview);

        die();
    }


/** Function feedback() called by wp_ajax hooks: {'grw_deactivate_feedback'} **/
/** Parameters found in function feedback(): {"post": ["reason", "msg"]} **/
function feedback() {
        if (!current_user_can('activate_plugins')) {
            die('The account you\'re logged in to doesn\'t have permission to access this page.');
        }
        check_admin_referer('grw_wpnonce', 'grw_nonce');

        $reason = isset($_POST['reason']) ? sanitize_key(wp_unslash($_POST['reason'])) : '';
        $msg = isset($_POST['msg']) ? sanitize_textarea_field(wp_unslash($_POST['msg'])) : '';
        if (!isset(self::REASONS[$reason]) && $msg === '') {
            wp_send_json(array('status' => 'skipped'));
        }

        wp_remote_post('https://admin.richplugins.com/plugins/feedback', array(
            'timeout'  => 5,
            'blocking' => false,
            'body'    => array(
                'plugin'  => 'grw',
                'version' => GRW_VERSION,
                'event'   => 'deactivate',
                'reason'  => $reason,
                'msg'     => $msg,
            ),
        ));
        wp_send_json(array('status' => 'success'));
    }


/** Function snooze() called by wp_ajax hooks: {'grw_rev_notice'} **/
/** No params detected :-/ **/


/** Function connect_google() called by wp_ajax hooks: {'grw_connect_google'} **/
/** Parameters found in function connect_google(): {"post": ["grw_wpnonce", "key", "lang", "local_img", "id", "token", "feed_id"]} **/
function connect_google() {
        if (current_user_can('manage_options')) {

            $response = null;

            if (isset($_POST['grw_wpnonce']) === false) {
                $error = __('Unable to call request. Make sure you are accessing this page from the Wordpress dashboard.', 'widget-google-reviews');
                $response = compact('error');
            } else {
                check_admin_referer('grw_wpnonce', 'grw_wpnonce');

                if (isset($_POST['key'])) {
                    $key = sanitize_text_field(wp_unslash($_POST['key']));
                    if (strlen($key) > 0) {
                        update_option('grw_google_api_key', $key);
                    }
                }

                $lang = empty($_POST['lang']) ? null : sanitize_text_field(wp_unslash($_POST['lang']));
                $local_img = isset($_POST['local_img']) ? filter_var(wp_unslash($_POST['local_img']), FILTER_VALIDATE_BOOLEAN) : true;
                $key = get_option('grw_google_api_key');

                if ($key && strlen($key) > 0) {

                    $pid = isset($_POST['id']) ? sanitize_text_field(wp_unslash($_POST['id'])) : '';
                    $gpa_old = get_option('grw_gpa_old');

                    if ($gpa_old === 'true') {
                        $response = $this->api_old->connect($pid, $lang, $key, $local_img);
                    } else {
                        $response = $this->api_new->connect($pid, $lang, $key, $local_img);
                    }

                } else {

                    $pid = isset($_POST['id']) ? sanitize_text_field(wp_unslash($_POST['id'])) : '';
                    $token = empty($_POST['token']) ? null : sanitize_text_field(wp_unslash($_POST['token']));

                    if (!empty($token)) {
                        $siteurl = get_option('siteurl');
                        $authcode = get_option('grw_auth_code');
                        $app_url = 'https://app.richplugins.com/public/connect/reviews';
                        $args = [
                            'input'    => $pid,
                            'token'    => $token,
                            'siteurl'  => $siteurl,
                            'authcode' => $authcode
                        ];
                        if ($lang && strlen($lang) > 0) {
                            $args['lang'] = $lang;
                        }
                        if ($this->is_playground()) {
                            $app_url = add_query_arg($args, $app_url);
                            $response = $this->api_old->call($app_url, null, $local_img);
                        } else {
                            $response = $this->api_old->post($app_url, $args, null, $local_img);
                        }
                    }
                }

                $this->clear_feed_cache(isset($_POST['feed_id']) ? $_POST['feed_id'] : null);
            }

            header('Content-type: text/javascript');
            echo json_encode($response);
            die();
        }
    }


/** Function delete_place() called by wp_ajax hooks: {'grw_delete_place'} **/
/** Parameters found in function delete_place(): {"post": ["id"]} **/
function delete_place() {
        if (!current_user_can('manage_options')) {
            die('The account you\'re logged in to doesn\'t have permission to access this page.');
        }
        check_admin_referer('grw_wpnonce', 'grw_nonce');

        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $deleted = $id > 0 && $this->dao->get_place_by_id($id) && $this->dao->delete_place($id);

        wp_send_json(array('status' => $deleted ? 'success' : 'failed', 'result' => array('id' => $id)));
    }


/** Function rateus_ajax_feedback() called by wp_ajax hooks: {'grw_rateus_ajax_feedback'} **/
/** Parameters found in function rateus_ajax_feedback(): {"post": ["rate", "email", "msg"]} **/
function rateus_ajax_feedback() {
        $this->check_nonce();

        $rate  = isset($_POST['rate'])  ? trim(sanitize_text_field(wp_unslash($_POST['rate'])))  : '';
        $email = isset($_POST['email']) ? trim(sanitize_text_field(wp_unslash($_POST['email']))) : '';
        $msg   = isset($_POST['msg'])   ? trim(sanitize_text_field(wp_unslash($_POST['msg'])))   : '';
        update_option('grw_rate_us', time() . ':' . $rate);

        $request = wp_remote_post('https://admin.richplugins.com/plugins/feedback', array(
            'timeout'   => 15,
            'body'      => array(
                'rate'  => $rate,
                'email' => $email,
                'msg'   => $msg
            )
        ));
        echo json_encode(array('rate' => $rate, 'email' => $email, 'msg' => $msg));

        die();
    }


/** Function place_autocomplete() called by wp_ajax hooks: {'grw_place_autocomplete'} **/
/** Parameters found in function place_autocomplete(): {"post": ["grw_nonce", "input"]} **/
function place_autocomplete() {
        if (current_user_can('manage_options')) {
            $result = null;
            if (isset($_POST['grw_nonce']) === false) {
                $error = __('Unable to call request. Make sure you are accessing this page from the Wordpress dashboard.', 'widget-google-reviews');
                $result = compact('error');
            } else {
                check_admin_referer('grw_wpnonce', 'grw_nonce');

                $key = get_option('grw_google_api_key');
                if (!empty($key)) {
                    $input = isset($_POST['input']) ? sanitize_text_field(wp_unslash($_POST['input'])) : '';
                    $url = add_query_arg(
                        array(
                            'input' => $input,
                            'types' => 'establishment',
                            'key'   => $key,
                        ),
                        GRW_GOOGLE_PLACE_API . 'autocomplete/json'
                    );
                    $res = wp_remote_get($url);
                    $body = wp_remote_retrieve_body($res);
                    $result = json_decode($body);
                }
            }
            header('Content-type: text/json');
            echo json_encode($result);
            wp_die();
        }
    }


/** Function get_place() called by wp_ajax hooks: {'grw_get_place'} **/
/** Parameters found in function get_place(): {"post": ["grw_nonce", "lang", "pid", "token"]} **/
function get_place() {
        if (current_user_can('manage_options')) {

            $response = null;

            if (isset($_POST['grw_nonce']) === false) {
                $error = __('Unable to call request. Make sure you are accessing this page from the Wordpress dashboard.', 'widget-google-reviews');
                $response = compact('error');
            } else {
                check_admin_referer('grw_wpnonce', 'grw_nonce');

                $lang = empty($_POST['lang']) ? null : sanitize_text_field(wp_unslash($_POST['lang']));
                $key = get_option('grw_google_api_key');

                if ($key && strlen($key) > 0) {

                    $pid = isset($_POST['pid']) ? sanitize_text_field(wp_unslash($_POST['pid'])) : '';
                    $gpa_old = get_option('grw_gpa_old');

                    if ($gpa_old === 'true') {
                        $response = $this->api_old->place($pid, $lang, $key);
                    } else {
                        $response = $this->api_new->place($pid, $lang, $key);
                    }

                } else {

                    $pid = isset($_POST['pid']) ? sanitize_text_field(wp_unslash($_POST['pid'])) : '';
                    $token = empty($_POST['token']) ? null : sanitize_text_field(wp_unslash($_POST['token']));

                    if (!empty($token)) {
                        $siteurl = get_option('siteurl');
                        $authcode = get_option('grw_auth_code');
                        $app_url = 'https://app.richplugins.com/connector/place/json';
                        $args = [
                            'pid'      => $pid,
                            'token'    => $token,
                            'siteurl'  => $siteurl,
                            'authcode' => $authcode
                        ];
                        if ($lang && strlen($lang) > 0) {
                            $args['lang'] = $lang;
                        }
                        if ($this->is_playground()) {
                            $app_url = add_query_arg($args, $app_url);
                            $response = $this->api_old->call($app_url, null, false, false);
                        } else {
                            $response = $this->api_old->post($app_url, $args, null, false, false);
                        }
                    }
                }
            }

            header('Content-type: text/json');
            echo json_encode($response);
            wp_die();
        }
    }


