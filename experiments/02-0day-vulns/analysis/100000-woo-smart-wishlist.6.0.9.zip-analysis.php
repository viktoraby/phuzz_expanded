<?php
/***
*
*Found actions: 7
*Found functions:7
*Extracted functions:7
*Total parameter names extracted: 7
*Overview: {'ajax_wishlist_quickview': {'wishlist_quickview'}, 'ajax_get_suggestion': {'wpc_get_suggestion'}, 'ajax_get_stats': {'woosw_get_stats'}, 'ajax_import': {'wpc_import'}, 'ajax_get_plugins': {'wpc_get_plugins'}, 'ajax_get_essential_kit': {'wpc_get_essential_kit'}, 'ajax_export': {'wpc_export'}}
*
***/

/** Function ajax_wishlist_quickview() called by wp_ajax hooks: {'wishlist_quickview'} **/
/** Parameters found in function ajax_wishlist_quickview(): {"post": ["nonce", "key", "pid", "page", "uid"]} **/
function ajax_wishlist_quickview() {
                    if ( ! apply_filters( 'woosw_disable_nonce_check', false, 'wishlist_quickview' ) ) {
                        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['nonce'] ) ), 'woosw-security' ) || ! current_user_can( 'manage_options' ) ) {
                            die( 'Permissions check failed!' );
                        }
                    }

                    global $wpdb;
                    ob_start();
                    echo '<div class="woosw-quickview-items">';

                    if ( isset( $_POST['key'] ) && $_POST['key'] != '' ) {
                        $key      = sanitize_text_field( wp_unslash( $_POST['key'] ?? '' ) );
                        $products = Woosw_Helper::get_ids( $key );
                        $count    = count( $products );

                        if ( count( $products ) > 0 ) {
                            $user = $wpdb->get_results( $wpdb->prepare( 'SELECT user_id FROM `' . $wpdb->usermeta . '` WHERE `meta_key` = "woosw_keys" AND `meta_value` LIKE %s LIMIT 1', '%"' . $key . '"%' ) );

                            echo '<div class="woosw-quickview-item">';
                            echo '<div class="woosw-quickview-item-image"><a href="' . esc_url( Woosw_Helper::get_url( $key, true ) ) . '" target="_blank">' . esc_html( $key ) . '</a></div>';
                            echo '<div class="woosw-quickview-item-info">';

                            if ( ! empty( $user ) ) {
                                $user_id   = $user[0]->user_id;
                                $user_data = get_userdata( $user_id );

                                echo '<div class="woosw-quickview-item-title"><a href="#" class="woosw_action" data-uid="' . esc_attr( $user_id ) . '">' . esc_html( $user_data->user_login ) . '</a></div>';
                                echo '<div class="woosw-quickview-item-data">' . esc_html( $user_data->user_email ) . ' | ' . sprintf( /* translators: count */ _n( '%s product', '%s products', $count, 'woo-smart-wishlist' ), esc_html( number_format_i18n( $count ) ) ) . '</div>';
                            } else {
                                echo '<div class="woosw-quickview-item-title">' . esc_html__( 'Guest', 'woo-smart-wishlist' ) . '</div>';
                                echo '<div class="woosw-quickview-item-data">' . sprintf( /* translators: count */ _n( '%s product', '%s products', $count, 'woo-smart-wishlist' ), esc_html( number_format_i18n( $count ) ) ) . '</div>';
                            }

                            echo '</div><!-- /woosw-quickview-item-info -->';
                            echo '</div><!-- /woosw-quickview-item -->';

                            foreach ( $products as $pid => $data ) {
                                if ( $_product = wc_get_product( $pid ) ) {
                                    echo '<div class="woosw-quickview-item">';
                                    echo '<div class="woosw-quickview-item-image">' . wp_kses_post( $_product->get_image() ) . '</div>';
                                    echo '<div class="woosw-quickview-item-info">';
                                    echo '<div class="woosw-quickview-item-title"><a href="' . esc_url( get_edit_post_link( $pid ) ) . '" target="_blank">' . $_product->get_name() . '</a></div>';
                                    echo '<div class="woosw-quickview-item-data">' . wp_date( get_option( 'date_format' ), $data['time'] ) . ' <span class="woosw-quickview-item-links">| ' . sprintf( /* translators: product id */ esc_html__( 'Product ID: %s', 'woo-smart-wishlist' ), absint( $pid ) ) . ' | <a href="#" class="woosw_action" data-pid="' . esc_attr( $pid ) . '">' . esc_html__( 'See in wishlist', 'woo-smart-wishlist' ) . '</a></span></div>';
                                    echo '</div><!-- /woosw-quickview-item-info -->';
                                    echo '</div><!-- /woosw-quickview-item -->';
                                } else {
                                    echo '<div class="woosw-quickview-item">';
                                    echo '<div class="woosw-quickview-item-image">' . wp_kses_post( wc_placeholder_img() ) . '</div>';
                                    echo '<div class="woosw-quickview-item-info">';
                                    echo '<div class="woosw-quickview-item-title">' . sprintf( /* translators: product id */ esc_html__( 'Product ID: %s', 'woo-smart-wishlist' ), absint( $pid ) ) . '</div>';
                                    echo '<div class="woosw-quickview-item-data">' . esc_html__( 'This product is not available!', 'woo-smart-wishlist' ) . '</div>';
                                    echo '</div><!-- /woosw-quickview-item-info -->';
                                    echo '</div><!-- /woosw-quickview-item -->';
                                }
                            }
                        } else {
                            echo '<div class="woosw-quickview-item">';
                            echo '<div class="woosw-quickview-item-image">' . wp_kses_post( wc_placeholder_img() ) . '</div>';
                            echo '<div class="woosw-quickview-item-info">';
                            echo '<div class="woosw-quickview-item-title">' . sprintf( /* translators: wishlist key */ esc_html__( 'Wishlist #%s', 'woo-smart-wishlist' ), $key ) . '</div>';
                            echo '<div class="woosw-quickview-item-data">' . esc_html__( 'This wishlist have no product!', 'woo-smart-wishlist' ) . '</div>';
                            echo '</div><!-- /woosw-quickview-item-info -->';
                            echo '</div><!-- /woosw-quickview-item -->';
                        }
                    } elseif ( isset( $_POST['pid'] ) ) {
                        $pid      = absint( sanitize_text_field( wp_unslash( $_POST['pid'] ?? 0 ) ) );
                        $per_page = absint( apply_filters( 'woosw_quickview_per_page', 10 ) );
                        $page     = absint( wp_unslash( $_POST['page'] ?? 1 ) );
                        $offset   = ( $page - 1 ) * $per_page;
                        $like_name = $wpdb->esc_like( 'woosw_list_' ) . '%';
                        $like_val  = '%i:' . $pid . ';%';
                        $total    = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM `' . $wpdb->prefix . 'options` WHERE `option_name` LIKE %s AND `option_value` LIKE %s', $like_name, $like_val ) );
                        $keys     = $wpdb->get_results( $wpdb->prepare( 'SELECT option_name FROM `' . $wpdb->prefix . 'options` WHERE `option_name` LIKE %s AND `option_value` LIKE %s LIMIT %d OFFSET %d', $like_name, $like_val, $per_page, $offset ) );

                        if ( $total && is_countable( $keys ) && count( $keys ) ) {
                            echo '<div class="woosw-quickview-item">';

                            if ( $_product = wc_get_product( $pid ) ) {
                                echo '<div class="woosw-quickview-item-image">' . wp_kses_post( $_product->get_image() ) . '</div>';
                                echo '<div class="woosw-quickview-item-info">';
                                echo '<div class="woosw-quickview-item-title"><a href="' . esc_url( get_edit_post_link( $pid ) ) . '" target="_blank">' . $_product->get_name() . '</a></div>';
                                echo '<div class="woosw-quickview-item-data">' . sprintf( /* translators: product id */ esc_html__( 'Product ID: %s', 'woo-smart-wishlist' ), absint( $pid ) ) . ' | ' . sprintf( /* translators: count */ _n( '%s wishlist', '%s wishlists', $total, 'woo-smart-wishlist' ), esc_html( number_format_i18n( $total ) ) ) . '</div>';
                            } else {
                                echo '<div class="woosw-quickview-item-image">' . wp_kses_post( wc_placeholder_img() ) . '</div>';
                                echo '<div class="woosw-quickview-item-info">';
                                echo '<div class="woosw-quickview-item-title">' . sprintf( /* translators: product id */ esc_html__( 'Product ID: %s', 'woo-smart-wishlist' ), absint( $pid ) ) . '</div>';
                                echo '<div class="woosw-quickview-item-data">' . esc_html__( 'This product is not available!', 'woo-smart-wishlist' ) . '</div>';
                            }

                            // paging
                            if ( $total > $per_page ) {
                                $pages = ceil( $total / $per_page );
                                echo '<div class="woosw-quickview-item-paging">Page ';

                                echo '<select class="woosw_paging" data-pid="' . absint( $pid ) . '">';

                                for ( $i = 1; $i <= $pages; $i ++ ) {
                                    echo '<option value="' . absint( $i ) . '" ' . selected( $page, $i, false ) . '>' . absint( $i ) . '</option>';
                                }

                                echo '</select> / ' . absint( $pages );

                                echo '</div><!-- /woosw-quickview-item-paging -->';
                            }

                            echo '</div><!-- /woosw-quickview-item-info -->';
                            echo '</div><!-- /woosw-quickview-item -->';

                            foreach ( $keys as $item ) {
                                $products       = get_option( $item->option_name );
                                $products_count = count( $products );
                                $key            = str_replace( 'woosw_list_', '', $item->option_name );
                                $user           = $wpdb->get_results( $wpdb->prepare( 'SELECT user_id FROM `' . $wpdb->usermeta . '` WHERE `meta_key` = "woosw_keys" AND `meta_value` LIKE %s LIMIT 1', '%"' . $key . '"%' ) );

                                echo '<div class="woosw-quickview-item">';
                                echo '<div class="woosw-quickview-item-image"><a href="' . esc_url( Woosw_Helper::get_url( $key, true ) ) . '" target="_blank">' . esc_html( $key ) . '</a></div>';
                                echo '<div class="woosw-quickview-item-info">';

                                if ( ! empty( $user ) ) {
                                    $user_id   = $user[0]->user_id;
                                    $user_data = get_userdata( $user_id );

                                    echo '<div class="woosw-quickview-item-title"><a href="#" class="woosw_action" data-uid="' . esc_attr( $user_id ) . '">' . esc_html( $user_data->user_login ) . '</a></div>';
                                    echo '<div class="woosw-quickview-item-data">' . esc_html( $user_data->user_email ) . '  | <a href="#" class="woosw_action woosw_action_' . absint( $products_count ) . '" data-key="' . esc_attr( $key ) . '">' . sprintf( /* translators: count */ _n( '%s product', '%s products', $products_count, 'woo-smart-wishlist' ), esc_html( number_format_i18n( $products_count ) ) ) . '</a></div>';
                                } else {
                                    echo '<div class="woosw-quickview-item-title">' . esc_html__( 'Guest', 'woo-smart-wishlist' ) . '</div>';
                                    echo '<div class="woosw-quickview-item-data"><a href="#" class="woosw_action" data-key="' . esc_attr( $key ) . '">' . sprintf( /* translators: count */ _n( '%s product', '%s products', $products_count, 'woo-smart-wishlist' ), esc_html( number_format_i18n( $products_count ) ) ) . '</a></div>';
                                }

                                echo '</div><!-- /woosw-quickview-item-info -->';
                                echo '</div><!-- /woosw-quickview-item -->';
                            }
                        }
                    } elseif ( isset( $_POST['uid'] ) ) {
                        $user_id = (int) sanitize_text_field( wp_unslash( $_POST['uid'] ?? 0 ) );
                        $keys    = get_user_meta( $user_id, 'woosw_keys', true ) ?: [];

                        if ( $user = get_user_by( 'id', $user_id ) ) {
                            echo '<div class="woosw-quickview-item">';
                            echo '<div class="woosw-quickview-item-image"><img src="' . esc_url( get_avatar_url( $user_id ) ) . '"  alt=""/></div>';
                            echo '<div class="woosw-quickview-item-info">';
                            echo '<div class="woosw-quickview-item-title"><a href="' . esc_url( get_edit_user_link( $user_id ) ) . '" target="_blank">' . esc_html( $user->user_login ) . '</a></div>';
                            echo '<div class="woosw-quickview-item-data">' . esc_html( $user->user_email ) . '</div>';
                            echo '</div><!-- /woosw-quickview-item-info -->';
                            echo '</div><!-- /woosw-quickview-item -->';
                        }

                        if ( is_array( $keys ) && count( $keys ) ) {
                            foreach ( $keys as $key => $data ) {
                                $products       = Woosw_Helper::get_ids( $key );
                                $products_count = count( $products );

                                echo '<div class="woosw-quickview-item">';
                                echo '<div class="woosw-quickview-item-image"><a href="' . esc_url( Woosw_Helper::get_url( $key, true ) ) . '" target="_blank">' . esc_html( $key ) . '</a></div>';
                                echo '<div class="woosw-quickview-item-info">';
                                echo '<div class="woosw-quickview-item-title">' . ( ! empty( $data['name'] ) ? esc_html( $data['name'] ) : 'Primary' ) . '</div>';
                                echo '<div class="woosw-quickview-item-data"><a href="#" class="woosw_action woosw_action_' . absint( $products_count ) . '" data-key="' . esc_attr( $key ) . '">' . sprintf( /* translators: count */ _n( '%s product', '%s products', $products_count, 'woo-smart-wishlist' ), esc_html( number_format_i18n( $products_count ) ) ) . '</a></div>';
                                echo '</div><!-- /woosw-quickview-item-info -->';
                                echo '</div><!-- /woosw-quickview-item -->';
                            }
                        }
                    }

                    echo '</div><!-- /woosw-quickview-items -->';
                    echo wp_kses_post( apply_filters( 'woosw_wishlist_quickview', ob_get_clean() ) );
                    die();
                }


/** Function ajax_get_suggestion() called by wp_ajax hooks: {'wpc_get_suggestion'} **/
/** Parameters found in function ajax_get_suggestion(): {"post": ["security"]} **/
function ajax_get_suggestion() {
            if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['security'] ) ), 'wpc_dashboard' ) ) {
                die( 'Permissions check failed!' );
            }

            $get_suggestion = '';

            if ( false === ( $plugins_arr = get_transient( 'wpclever_plugins' ) ) ) {
                $plugins_arr = [];
                // Use API v1.2 which returns JSON instead of serialized PHP (safer and more reliable)
                $url         = 'https://api.wordpress.org/plugins/info/1.2/';
                $request     = [
                        'action'  => 'query_plugins',
                        'timeout' => 30,
                        'request' => [
                                'author'   => 'wpclever',
                                'per_page' => 120,
                                'page'     => 1,
                                'fields'   => [
                                        'slug'              => true,
                                        'name'              => true,
                                        'version'           => true,
                                        'downloaded'        => true,
                                        'active_installs'   => true,
                                        'last_updated'      => true,
                                        'rating'            => true,
                                        'num_ratings'       => true,
                                        'short_description' => true,
                                ],
                        ],
                ];
                $response    = wp_remote_get( add_query_arg( $request, $url ), [ 'timeout' => 30 ] );

                if ( ! is_wp_error( $response ) ) {
                    // API v1.2 returns JSON - safe to decode without unserialize
                    $data = json_decode( wp_remote_retrieve_body( $response ), true );

                    // Validate the decoded structure before use
                    if ( is_array( $data ) && isset( $data['plugins'] ) && is_array( $data['plugins'] ) && ( count( $data['plugins'] ) > 0 ) ) {
                        foreach ( $data['plugins'] as $pl ) {
                            if ( ! is_array( $pl ) ) {
                                continue;
                            }

                            $plugins_arr[] = [
                                    'slug'              => isset( $pl['slug'] ) ? (string) $pl['slug'] : '',
                                    'name'              => isset( $pl['name'] ) ? (string) $pl['name'] : '',
                                    'version'           => isset( $pl['version'] ) ? (string) $pl['version'] : '',
                                    'downloaded'        => isset( $pl['downloaded'] ) ? (int) $pl['downloaded'] : 0,
                                    'active_installs'   => isset( $pl['active_installs'] ) ? (int) $pl['active_installs'] : 0,
                                    'last_updated'      => isset( $pl['last_updated'] ) ? strtotime( (string) $pl['last_updated'] ) : 0,
                                    'rating'            => isset( $pl['rating'] ) ? (float) $pl['rating'] : 0,
                                    'num_ratings'       => isset( $pl['num_ratings'] ) ? (int) $pl['num_ratings'] : 0,
                                    'short_description' => isset( $pl['short_description'] ) ? (string) $pl['short_description'] : '',
                            ];
                        }
                    }

                    set_transient( 'wpclever_plugins', $plugins_arr, 24 * HOUR_IN_SECONDS );
                }
            }

            if ( is_array( $plugins_arr ) && ( count( $plugins_arr ) > 0 ) ) {
                array_multisort( array_column( $plugins_arr, 'last_updated' ), SORT_DESC, $plugins_arr );
                $plugins_arr = array_slice( $plugins_arr, 0, 5 );

                foreach ( $plugins_arr as $sg ) {
                    $get_suggestion .= '<div><a href="' . esc_url( 'https://wordpress.org/plugins/' . $sg['slug'] . '/' ) . '" target="_blank">' . esc_html( $sg['name'] ) . '</a> - ' . esc_html( $sg['short_description'] ) . '</div>';
                }
            }

            echo wp_kses_post( $get_suggestion );

            wp_die();
        }


/** Function ajax_get_stats() called by wp_ajax hooks: {'woosw_get_stats'} **/
/** Parameters found in function ajax_get_stats(): {"post": ["period", "from", "to"]} **/
function ajax_get_stats() {
            check_ajax_referer( 'woosw-stats', 'nonce' );

            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( 'Permission denied' );
            }

            global $wpdb;
            $table_name = $wpdb->prefix . 'wpc_wishlist_stats';
            $period     = sanitize_text_field( wp_unslash( $_POST['period'] ?? '7days' ) );
            $from       = sanitize_text_field( wp_unslash( $_POST['from'] ?? '' ) );
            $to         = sanitize_text_field( wp_unslash( $_POST['to'] ?? '' ) );

            $start_date = '';
            $end_date   = current_time( 'mysql' );

            if ( $period === '7days' ) {
                $start_date = gmdate( 'Y-m-d 00:00:00', strtotime( '-6 days' ) );
            } elseif ( $period === '30days' ) {
                $start_date = gmdate( 'Y-m-d 00:00:00', strtotime( '-29 days' ) );
            } elseif ( $period === 'custom' && ! empty( $from ) && ! empty( $to ) ) {
                $start_date = $from . ' 00:00:00';
                $end_date   = $to . ' 23:59:59';
            } else {
                $start_date = gmdate( 'Y-m-d 00:00:00', strtotime( '-6 days' ) );
            }

            $results = $wpdb->get_results( $wpdb->prepare(
                    "SELECT action, DATE(created_at) as date, COUNT(*) as count 
                        FROM %i 
                        WHERE created_at BETWEEN %s AND %s 
                        GROUP BY action, DATE(created_at) 
                        ORDER BY date ASC",
                    $table_name,
                    $start_date,
                    $end_date
            ) );

            $labels        = [];
            $added         = [];
            $removed       = [];
            $total_added   = 0;
            $total_removed = 0;

            $current = strtotime( $start_date );
            $last    = strtotime( $end_date );

            while ( $current <= $last ) {
                $date             = gmdate( 'Y-m-d', $current );
                $labels[]         = $date;
                $added[ $date ]   = 0;
                $removed[ $date ] = 0;
                $current          = strtotime( '+1 day', $current );
            }

            foreach ( $results as $row ) {
                if ( $row->action === 'add' ) {
                    $added[ $row->date ] = (int) $row->count;
                    $total_added         += (int) $row->count;
                } else {
                    $removed[ $row->date ] = (int) $row->count;
                    $total_removed         += (int) $row->count;
                }
            }

            // top added
            $top_added_results = $wpdb->get_results( $wpdb->prepare(
                    "SELECT product_id, COUNT(*) as count 
                        FROM %i 
                        WHERE action = 'add' AND created_at BETWEEN %s AND %s 
                        GROUP BY product_id 
                        ORDER BY count DESC 
                        LIMIT 5",
                    $table_name,
                    $start_date,
                    $end_date
            ) );

            $top_added = [];
            foreach ( $top_added_results as $row ) {
                $product     = wc_get_product( $row->product_id );
                $top_added[] = [
                        'name'  => $product ? $product->get_name() : '#' . $row->product_id,
                        'count' => $row->count,
                        'url'   => get_permalink( $row->product_id )
                ];
            }

            // top removed
            $top_removed_results = $wpdb->get_results( $wpdb->prepare(
                    "SELECT product_id, COUNT(*) as count 
                        FROM %i 
                        WHERE action = 'remove' AND created_at BETWEEN %s AND %s 
                        GROUP BY product_id 
                        ORDER BY count DESC 
                        LIMIT 5",
                    $table_name,
                    $start_date,
                    $end_date
            ) );

            $top_removed = [];
            foreach ( $top_removed_results as $row ) {
                $product       = wc_get_product( $row->product_id );
                $top_removed[] = [
                        'name'  => $product ? $product->get_name() : '#' . $row->product_id,
                        'count' => $row->count,
                        'url'   => get_edit_post_link( $row->product_id )
                ];
            }

            wp_send_json_success( [
                    'labels'        => $labels,
                    'added'         => array_values( $added ),
                    'removed'       => array_values( $removed ),
                    'total_added'   => $total_added,
                    'total_removed' => $total_removed,
                    'top_added'     => $top_added,
                    'top_removed'   => $top_removed
            ] );
        }


/** Function ajax_import() called by wp_ajax hooks: {'wpc_import'} **/
/** Parameters found in function ajax_import(): {"post": ["security", "key", "settings"]} **/
function ajax_import() {
            if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['security'] ) ), 'wpc_dashboard' ) || ! current_user_can( 'manage_options' ) ) {
                die( 'Permissions check failed!' );
            }

            $key      = sanitize_key( wp_unslash( $_POST['key'] ?? '' ) );
            $settings = sanitize_textarea_field( wp_unslash( $_POST['settings'] ?? '' ) );

            if ( ! empty( $key ) && get_option( $key ) ) {
                $json_settings = json_decode( stripcslashes( $settings ), true );

                if ( json_last_error() === JSON_ERROR_NONE ) {
                    update_option( $key, $json_settings );

                    wp_die( 'Imported!' );
                } else {
                    wp_die( 'JSON Error!' );
                }
            }
        }


/** Function ajax_get_plugins() called by wp_ajax hooks: {'wpc_get_plugins'} **/
/** Parameters found in function ajax_get_plugins(): {"post": ["security"]} **/
function ajax_get_plugins() {
            if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['security'] ) ), 'wpc_dashboard' ) ) {
                die( 'Permissions check failed!' );
            }

            if ( false === ( $plugins_arr = get_transient( 'wpclever_plugins' ) ) ) {
                // Use API v1.2 which returns JSON instead of serialized PHP (safer and more reliable)
                $url      = 'https://api.wordpress.org/plugins/info/1.2/';
                $request  = [
                        'action'  => 'query_plugins',
                        'timeout' => 30,
                        'request' => [
                                'author'   => 'wpclever',
                                'per_page' => 120,
                                'page'     => 1,
                                'fields'   => [
                                        'slug'              => true,
                                        'name'              => true,
                                        'version'           => true,
                                        'downloaded'        => true,
                                        'active_installs'   => true,
                                        'last_updated'      => true,
                                        'rating'            => true,
                                        'num_ratings'       => true,
                                        'short_description' => true,
                                ],
                        ],
                ];
                $response = wp_remote_get( add_query_arg( $request, $url ), [ 'timeout' => 30 ] );

                if ( ! is_wp_error( $response ) ) {
                    $plugins_arr = [];

                    // API v1.2 returns JSON - safe to decode without unserialize
                    $data = json_decode( wp_remote_retrieve_body( $response ), true );

                    // Validate the decoded structure before use
                    if ( is_array( $data ) && isset( $data['plugins'] ) && is_array( $data['plugins'] ) && ( count( $data['plugins'] ) > 0 ) ) {
                        foreach ( $data['plugins'] as $pl ) {
                            if ( ! is_array( $pl ) ) {
                                continue;
                            }

                            $plugins_arr[] = [
                                    'slug'              => isset( $pl['slug'] ) ? (string) $pl['slug'] : '',
                                    'name'              => isset( $pl['name'] ) ? (string) $pl['name'] : '',
                                    'version'           => isset( $pl['version'] ) ? (string) $pl['version'] : '',
                                    'downloaded'        => isset( $pl['downloaded'] ) ? (int) $pl['downloaded'] : 0,
                                    'active_installs'   => isset( $pl['active_installs'] ) ? (int) $pl['active_installs'] : 0,
                                    'last_updated'      => isset( $pl['last_updated'] ) ? strtotime( (string) $pl['last_updated'] ) : 0,
                                    'rating'            => isset( $pl['rating'] ) ? (float) $pl['rating'] : 0,
                                    'num_ratings'       => isset( $pl['num_ratings'] ) ? (int) $pl['num_ratings'] : 0,
                                    'short_description' => isset( $pl['short_description'] ) ? (string) $pl['short_description'] : '',
                            ];
                        }
                    }

                    set_transient( 'wpclever_plugins', $plugins_arr, 24 * HOUR_IN_SECONDS );
                } else {
                    echo 'Have an error while loading the plugin list. Please visit our website <a href="https://wpclever.net?utm_source=visit&utm_medium=menu&utm_campaign=wporg" target="_blank">https://wpclever.net</a>';
                }
            }

            if ( is_array( $plugins_arr ) && ( count( $plugins_arr ) > 0 ) ) {
                array_multisort( array_column( $plugins_arr, 'active_installs' ), SORT_DESC, $plugins_arr );
                $i = 1;

                foreach ( $plugins_arr as $pl ) {
                    if ( ! str_contains( $pl['name'], 'WPC' ) ) {
                        continue;
                    }

                    echo '<div class="item" data-p="' . esc_attr( $pl['active_installs'] ?? 0 ) . '" data-u="' . esc_attr( $pl['last_updated'] ?? 0 ) . '" data-d="' . esc_attr( $pl['downloaded'] ?? 0 ) . '"><a class="thickbox" href="' . esc_url( network_admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=' . $pl['slug'] . '&amp;TB_iframe=true&amp;width=600&amp;height=550' ) ) . '" title="' . esc_attr( $pl['name'] ) . '"><span class="num">' . esc_html( $i ) . '</span><span class="title">' . esc_html( $pl['name'] ) . '</span><span class="info">' . esc_html( 'Version ' . $pl['version'] . ( isset( $pl['last_updated'] ) ? ' - Last updated: ' . wp_date( 'M j, Y', $pl['last_updated'] ) : '' ) ) . '</span></a></div>';
                    $i ++;
                }
            } else {
                echo 'Have an error while loading the plugin list. Please visit our website <a href="https://wpclever.net?utm_source=visit&utm_medium=menu&utm_campaign=wporg" target="_blank">https://wpclever.net</a>';
            }

            wp_die();
        }


/** Function ajax_get_essential_kit() called by wp_ajax hooks: {'wpc_get_essential_kit'} **/
/** Parameters found in function ajax_get_essential_kit(): {"post": ["security"]} **/
function ajax_get_essential_kit() {
            if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['security'] ) ), 'wpc_kit' ) ) {
                die( 'Permissions check failed!' );
            }

            if ( false === ( $plugins_arr = get_transient( 'wpclever_plugins' ) ) ) {
                // Use API v1.2 which returns JSON instead of serialized PHP (safer and more reliable)
                $url      = 'https://api.wordpress.org/plugins/info/1.2/';
                $request  = [
                        'action'  => 'query_plugins',
                        'timeout' => 30,
                        'request' => [
                                'author'   => 'wpclever',
                                'per_page' => 120,
                                'page'     => 1,
                                'fields'   => [
                                        'slug'              => true,
                                        'name'              => true,
                                        'version'           => true,
                                        'downloaded'        => true,
                                        'active_installs'   => true,
                                        'last_updated'      => true,
                                        'rating'            => true,
                                        'num_ratings'       => true,
                                        'short_description' => true,
                                ],
                        ],
                ];
                $response = wp_remote_get( add_query_arg( $request, $url ), [ 'timeout' => 30 ] );

                if ( ! is_wp_error( $response ) ) {
                    $plugins_arr = [];

                    // API v1.2 returns JSON - safe to decode without unserialize
                    $data = json_decode( wp_remote_retrieve_body( $response ), true );

                    // Validate the decoded structure before use
                    if ( is_array( $data ) && isset( $data['plugins'] ) && is_array( $data['plugins'] ) && ( count( $data['plugins'] ) > 0 ) ) {
                        foreach ( $data['plugins'] as $pl ) {
                            if ( ! is_array( $pl ) ) {
                                continue;
                            }

                            $plugins_arr[] = [
                                    'slug'              => isset( $pl['slug'] ) ? (string) $pl['slug'] : '',
                                    'name'              => isset( $pl['name'] ) ? (string) $pl['name'] : '',
                                    'version'           => isset( $pl['version'] ) ? (string) $pl['version'] : '',
                                    'downloaded'        => isset( $pl['downloaded'] ) ? (int) $pl['downloaded'] : 0,
                                    'active_installs'   => isset( $pl['active_installs'] ) ? (int) $pl['active_installs'] : 0,
                                    'last_updated'      => isset( $pl['last_updated'] ) ? strtotime( (string) $pl['last_updated'] ) : 0,
                                    'rating'            => isset( $pl['rating'] ) ? (float) $pl['rating'] : 0,
                                    'num_ratings'       => isset( $pl['num_ratings'] ) ? (int) $pl['num_ratings'] : 0,
                                    'short_description' => isset( $pl['short_description'] ) ? (string) $pl['short_description'] : '',
                            ];
                        }
                    }

                    set_transient( 'wpclever_plugins', $plugins_arr, 24 * HOUR_IN_SECONDS );
                } else {
                    echo 'Have an error while loading the essential kit. Please visit our website <a href="https://wpclever.net?utm_source=visit&utm_medium=menu&utm_campaign=wporg" target="_blank">https://wpclever.net</a>';
                }
            }

            if ( is_array( $plugins_arr ) && ( count( $plugins_arr ) > 0 ) ) {
                array_multisort( array_column( $plugins_arr, 'active_installs' ), SORT_DESC, $plugins_arr );

                foreach ( $plugins_arr as $plugin ) {
                    $plugin_slug = $plugin['slug'];

                    if ( isset( self::$plugins[ $plugin_slug ] ) ) {
                        $plugin_file = self::$plugins[ $plugin_slug ];
                    } else {
                        $plugin_file = $plugin_slug . '.php';
                    }

                    $details_link = network_admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=' . $plugin['slug'] . '&amp;TB_iframe=true&amp;width=600&amp;height=550' );
                    ?>
                    <div class="wpc-plugin-card <?php echo esc_attr( $plugin_slug ); ?>"
                         id="<?php echo esc_attr( $plugin_slug ); ?>"
                         data-p="<?php echo esc_attr( $plugin['active_installs'] ?? 0 ); ?>"
                         data-u="<?php echo esc_attr( $plugin['last_updated'] ?? 0 ); ?>">
                        <div class="wpc-plugin-card-top">
                            <a href="<?php echo esc_url( $details_link ); ?>" class="thickbox wpc-plugin-card-icon"
                               title="<?php echo esc_attr( $plugin['name'] ); ?>">
                                <img src="<?php echo esc_url( 'https://api.wpclever.net/images/' . $plugin_slug . '.png' ); ?>"
                                     class="plugin-icon" alt="<?php echo esc_attr( $plugin['name'] ); ?>"/>
                            </a>
                            <div class="wpc-plugin-card-info">
                                <div class="name column-name">
                                    <h3>
                                        <a class="thickbox" title="<?php echo esc_attr( $plugin['name'] ); ?>"
                                           href="<?php echo esc_url( $details_link ); ?>">
                                            <?php echo esc_html( $plugin['name'] ); ?>
                                        </a>
                                    </h3>
                                </div>
                                <div class="desc column-description">
                                    <p><?php echo esc_html( $plugin['short_description'] ?? '' ); ?></p>
                                </div>
                                <div class="action-links">
                                    <ul class="plugin-action-buttons">
                                        <li>
                                            <?php if ( $this->is_plugin_installed( $plugin_slug, $plugin_file ) ) {
                                                if ( $this->is_plugin_active( $plugin_slug, $plugin_file ) ) {
                                                    ?>
                                                    <a href="<?php echo esc_url( $this->deactivate_plugin_link( $plugin_slug, $plugin_file ) ); ?>"
                                                       class="button deactivate-now">
                                                        Deactivate
                                                    </a>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <a href="<?php echo esc_url( $this->activate_plugin_link( $plugin_slug, $plugin_file ) ); ?>"
                                                       class="button activate-now">
                                                        Activate
                                                    </a>
                                                    <?php
                                                }
                                            } else { ?>
                                                <a href="<?php echo esc_url( $this->install_plugin_link( $plugin_slug ) ); ?>"
                                                   class="button install-now">
                                                    Install Now
                                                </a>
                                            <?php } ?>
                                        </li>
                                        <li>
                                            <a href="<?php echo esc_url( $details_link ); ?>"
                                               class="thickbox open-plugin-details-modal"
                                               aria-label="<?php echo esc_attr( sprintf( 'More information about %s', $plugin['name'] ) ); ?>"
                                               title="<?php echo esc_attr( $plugin['name'] ); ?>">
                                                More Details
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php
                        echo '<div class="wpc-plugin-card-bottom">';
                        echo '<div class="wpc-plugin-card-bottom-left">';

                        if ( isset( $plugin['version'] ) ) { ?>
                            <div class="wpc-plugin-card-version">
                                <strong>Version:</strong>
                                <span><?php echo esc_html( $plugin['version'] ); ?></span>
                            </div>
                        <?php }

                        if ( isset( $plugin['last_updated'] ) ) { ?>
                            <div class="wpc-plugin-card-updated">
                                <strong>Last Updated:</strong>
                                <span><?php printf( '%s ago', esc_html( human_time_diff( $plugin['last_updated'] ) ) ); ?></span>
                            </div>
                        <?php }

                        echo '</div>';
                        echo '<div class="wpc-plugin-card-bottom-right">';

                        if ( isset( $plugin['rating'], $plugin['num_ratings'] ) ) { ?>
                            <div class="wpc-plugin-card-rating">
                                <?php
                                wp_star_rating(
                                        [
                                                'rating' => $plugin['rating'],
                                                'type'   => 'percent',
                                                'number' => $plugin['num_ratings'],
                                        ]
                                );
                                ?>
                                <span class="num-ratings">(<?php echo esc_html( number_format_i18n( $plugin['num_ratings'] ) ); ?>)</span>
                            </div>
                        <?php }

                        if ( isset( $plugin['active_installs'] ) ) { ?>
                            <div class="wpc-plugin-card-installed">
                                <?php echo esc_html( number_format_i18n( $plugin['active_installs'] ) ) . '+ Active Installations'; ?>
                            </div>
                        <?php }

                        echo '</div>';
                        echo '</div>';

                        if ( $this->is_plugin_installed( $plugin_slug, $plugin_file, true ) ) {
                            ?>
                            <div class="wpc-plugin-card-bottom premium">
                                <div class="text">
                                    ✓ Premium version was installed.
                                </div>
                                <div class="btn">
                                    <?php
                                    if ( $this->is_plugin_active( $plugin_slug, $plugin_file, true ) ) {
                                        ?>
                                        <a href="<?php echo esc_url( $this->deactivate_plugin_link( $plugin_slug, $plugin_file, true ) ); ?>"
                                           class="button deactivate-now">
                                            Deactivate
                                        </a>
                                        <?php
                                    } else {
                                        ?>
                                        <a href="<?php echo esc_url( $this->activate_plugin_link( $plugin_slug, $plugin_file, true ) ); ?>"
                                           class="button activate-now">
                                            Activate
                                        </a>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                }
            } else {
                echo 'Have an error while loading the essential kit. Please visit our website <a href="https://wpclever.net?utm_source=visit&utm_medium=menu&utm_campaign=wporg" target="_blank">https://wpclever.net</a>';
            }

            wp_die();
        }


/** Function ajax_export() called by wp_ajax hooks: {'wpc_export'} **/
/** Parameters found in function ajax_export(): {"post": ["security", "key", "name"]} **/
function ajax_export() {
            if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['security'] ) ), 'wpc_dashboard' ) || ! current_user_can( 'manage_options' ) ) {
                die( 'Permissions check failed!' );
            }

            $key  = sanitize_key( wp_unslash( $_POST['key'] ?? '' ) );
            $name = sanitize_text_field( wp_unslash( $_POST['name'] ?? 'settings' ) );

            if ( ! empty( $key ) && ( $settings = get_option( $key ) ) ) {
                unset( $settings['_last_saved'] );
                unset( $settings['_last_saved_by'] );

                echo '<textarea class="wpclever_export_data" id="wpclever_export_data" style="width: 100%; height: 200px; margin-bottom: 10px;" data-key="' . esc_attr( $key ) . '">' . esc_textarea( wp_json_encode( $settings, JSON_PRETTY_PRINT ) ) . '</textarea>';
                echo '<div style="display: flex; align-items: center"><button class="button button-primary wpclever_import" data-key="' . esc_attr( $key ) . '">Update</button>';
                echo '<span style="color: #ff4f3b; font-size: 10px; margin-left: 10px">' . sprintf( '* Current %s will be replaced after pressing Update!', esc_html( $name ) ) . '</span>';
                echo '</div>';

                wp_die();
            }
        }


