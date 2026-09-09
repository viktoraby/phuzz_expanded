<?php
/***
*
*Found actions: 7
*Found functions:5
*Extracted functions:5
*Total parameter names extracted: 4
*Overview: {'get_default_thumbnail': {'wpp_reset_thumbnail'}, 'clear_thumbnails': {'wpp_clear_thumbnail'}, 'get_popular_items': {'wpp_get_most_viewed', 'wpp_get_most_commented', 'wpp_get_trending'}, 'handle_performance_notice': {'wpp_handle_performance_notice'}, 'update_chart': {'wpp_update_chart'}}
*
***/

/** Function get_default_thumbnail() called by wp_ajax hooks: {'wpp_reset_thumbnail'} **/
/** No params detected :-/ **/


/** Function clear_thumbnails() called by wp_ajax hooks: {'wpp_clear_thumbnail'} **/
/** Parameters found in function clear_thumbnails(): {"post": ["token"]} **/
function clear_thumbnails()
    {
        $wpp_uploads_dir = $this->thumbnail->get_plugin_uploads_dir();
        $token = isset($_POST['token']) ? $_POST['token'] : null; // phpcs:ignore WordPress.Security.NonceVerification.Missing,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- This is a nonce

        if (
            current_user_can('manage_options')
            && wp_verify_nonce($token, 'wpp_nonce_reset_thumbnails')
        ) {
            echo $this->delete_thumbnails();
        } else {
            echo 4;
        }

        wp_die();
    }


/** Function get_popular_items() called by wp_ajax hooks: {'wpp_get_most_viewed', 'wpp_get_most_commented', 'wpp_get_trending'} **/
/** Parameters found in function get_popular_items(): {"get": ["items", "nonce", "start_date", "end_date"]} **/
function get_popular_items()
    {
        $items = isset($_GET['items']) ? $_GET['items'] : null; //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verification happens below
        $nonce = isset($_GET['nonce']) ? $_GET['nonce'] : null; //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- This is a nonce

        if ( wp_verify_nonce($nonce, 'wpp_admin_nonce') ) {
            $args = [
                'range' => $this->config['stats']['range'],
                'time_quantity' => $this->config['stats']['time_quantity'],
                'time_unit' => $this->config['stats']['time_unit'],
                'post_type' => $this->config['stats']['post_type'],
                'freshness' => $this->config['stats']['freshness'],
                'limit' => $this->config['stats']['limit'],
                'stats_tag' => [
                    'date' => [
                        'active' => 1
                    ]
                ]
            ];

            if ( 'most-commented' == $items ) {
                $args['order_by'] = 'comments';
                $args['stats_tag']['comment_count'] = 1;
                $args['stats_tag']['views'] = 0;
            } elseif ( 'trending' == $items ) {
                $args['range'] = 'custom';
                $args['time_quantity'] = 1;
                $args['time_unit'] = 'HOUR';
                $args['stats_tag']['comment_count'] = 1;
                $args['stats_tag']['views'] = 1;
            } else {
                $args['stats_tag']['comment_count'] = 0;
                $args['stats_tag']['views'] = 1;
            }

            if ( 'trending' != $items ) {
                $datepicker_start_date = $_GET['start_date'] ?? null; //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- This is not a nonce
                $datepicker_end_date = $_GET['end_date'] ?? null; //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- This is not a nonce

                if (
                    'custom' == $args['range']
                    && Helper::is_valid_date($datepicker_start_date)
                    && Helper::is_valid_date($datepicker_end_date)
                ) {
                    $start_date = $datepicker_start_date;
                    $end_date = $datepicker_end_date;

                    if ( strtotime($start_date) > strtotime($end_date) ) {
                        $start_date = $end_date;
                    }

                    $start_date .= ' 00:00:00';
                    $end_date .= ' 23:59:59';

                    $filter_by_date_range = function($join, $options) use ($items, $start_date, $end_date) {
                        global $wpdb;

                        if ( 'most-commented' == $items ) {
                            return $wpdb->prepare(
                                'INNER JOIN (SELECT comment_post_ID, COUNT(comment_post_ID) AS comment_count, comment_date FROM %i WHERE (comment_date BETWEEN %s AND %s) AND comment_approved = "1" GROUP BY comment_post_ID) c ON p.ID = c.comment_post_ID',
                                $wpdb->comments,
                                $start_date,
                                $end_date
                            );
                        }

                        return $wpdb->prepare(
                            'INNER JOIN (SELECT SUM(pageviews) AS pageviews, view_date, postid FROM %i WHERE (view_datetime BETWEEN %s AND %s) GROUP BY postid) v ON p.ID = v.postid',
                            $wpdb->prefix . 'popularpostssummary',
                            $start_date,
                            $end_date
                        );
                    };

                    add_filter('wpp_query_join', $filter_by_date_range, 1, 2);
                }
            }

            $query = new Query($args);
            $posts = $query->get_posts();

            if ( 'trending' != $items ) {
                remove_all_filters('wpp_query_join', 1);
            }

            $this->render_list($posts, $items);
        }

        wp_die();
    }


/** Function handle_performance_notice() called by wp_ajax hooks: {'wpp_handle_performance_notice'} **/
/** Parameters found in function handle_performance_notice(): {"post": ["token", "dismiss"]} **/
function handle_performance_notice()
    {
        $response = [
            'status' => 'error'
        ];
        $token = isset($_POST['token']) ? $_POST['token'] : null; // phpcs:ignore WordPress.Security.NonceVerification.Missing,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- This is a nonce
        $dismiss = isset($_POST['dismiss']) ? (int) $_POST['dismiss'] : 0;

        if (
            current_user_can('manage_options')
            && wp_verify_nonce($token, 'wpp_nonce_performance_nag')
        ) {
            $now = Helper::timestamp();

            // User dismissed the notice
            if ( 1 == $dismiss ) {
                $performance_nag['status'] = 3;
            } // User asked us to remind them later
            else {
                $performance_nag['status'] = 2;
            }

            $performance_nag['last_checked'] = $now;

            update_option('wpp_performance_nag', $performance_nag);

            $response = [
                'status' => 'success'
            ];
        }

        wp_send_json($response);
    }


/** Function update_chart() called by wp_ajax hooks: {'wpp_update_chart'} **/
/** Parameters found in function update_chart(): {"get": ["nonce", "range", "time_quantity", "time_unit"]} **/
function update_chart()
    {
        $response = [
            'status' => 'error'
        ];
        $nonce = isset($_GET['nonce']) ? $_GET['nonce'] : null; //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- This is a nonce

        if ( wp_verify_nonce($nonce, 'wpp_admin_nonce') ) {

            $valid_ranges = ['today', 'daily', 'last24hours', 'weekly', 'last7days', 'monthly', 'last30days', 'all', 'custom'];
            $time_units = ['MINUTE', 'HOUR', 'DAY'];

            $range = ( isset($_GET['range']) && in_array($_GET['range'], $valid_ranges) ) ? $_GET['range'] : 'last7days';
            $time_quantity = ( isset($_GET['time_quantity']) && filter_var($_GET['time_quantity'], FILTER_VALIDATE_INT) ) ? $_GET['time_quantity'] : 24;
            $time_unit = ( isset($_GET['time_unit']) && in_array(strtoupper($_GET['time_unit']), $time_units) ) ? $_GET['time_unit'] : 'hour';

            $this->config['stats']['range'] = $range;
            $this->config['stats']['time_quantity'] = $time_quantity;
            $this->config['stats']['time_unit'] = $time_unit;

            update_option('wpp_settings_config', $this->config);

            $response = [
                'status' => 'ok',
                'data' => json_decode(
                    $this->get_chart_data($this->config['stats']['range'], $this->config['stats']['time_unit'], $this->config['stats']['time_quantity']),
                    true
                )
            ];
        }

        wp_send_json($response);
    }


