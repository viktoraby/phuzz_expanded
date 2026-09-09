<?php
/***
*
*Found actions: 6
*Found functions:6
*Extracted functions:6
*Total parameter names extracted: 3
*Overview: {'handle_get_non_https_resources_table': {'ehssl_get_scanned_resources_table'}, 'handle_update_http_urls': {'ehssl_update_http_urls'}, 'handle_reset_log': {'ehssl_reset_log'}, 'handle_non_https_resources_scan': {'ehssl_non_https_resources_scan'}, 'handle_save_dashboard_sorting_order': {'ehssl_save_dashboard_order'}, 'handle_load_non_https_resources_table_page': {'ehssl_load_static_resources_table_page'}}
*
***/

/** Function handle_get_non_https_resources_table() called by wp_ajax hooks: {'ehssl_get_scanned_resources_table'} **/
/** No params detected :-/ **/


/** Function handle_update_http_urls() called by wp_ajax hooks: {'ehssl_update_http_urls'} **/
/** Parameters found in function handle_update_http_urls(): {"post": ["offset", "selected_ids"]} **/
function handle_update_http_urls() {
        if ( ! check_ajax_referer( 'ehssl_update_all_http_urls', 'nonce', false ) ) {
            wp_send_json_error(
                    array(
                            'message' => __( 'Nonce verification failed!', 'https-redirection' ),
                    )
            );
        }

        $limit = $this->batch_size;
        $offset = isset( $_POST['offset'] ) ? absint( $_POST['offset'] ) : 0;
        $selected_ids = isset( $_POST['selected_ids'] ) ? json_decode(stripslashes($_POST['selected_ids'])) : null;

        $total = 0;

        if ( is_null( $selected_ids ) ) {
            // Need to update all items.
            $total = self::get_scan_results_count();
        } else {
            // Need to update selected items.
            $total = count( $selected_ids );
        }

        if ( empty( $total ) ) {
            wp_send_json_success(
                array(
                    'completed' => true,
                    'processed' => 0,
                )
            );
        }

        $batch = self::get_scan_results_chunk( $offset, $limit, array( 'id' => $selected_ids ) );

        if ( empty( $batch ) ) {
            wp_send_json_success(
                array(
                    'completed' => true,
                    'processed' => 0,
                )
            );
        }

        try {
            $this->update_urls( $batch );

            $this->mark_items_fixed($batch);

            $next_offset = $offset + count( $batch );

            $response = array(
                'message'     => __( 'URLs update to https successfully.', 'https-redirection' ),
                'completed'   => $next_offset >= $total,
                'processed'   => $next_offset,
                'total'       => $total,
                'next_offset' => $next_offset,
            );

            wp_send_json_success($response);

        } catch ( \Exception $e ) {
            EHSSL_Logger::log( $e->getMessage(), 4 );
            wp_send_json_error( array(
                    'message' => $e->getMessage(),
            ) );
        }
    }


/** Function handle_reset_log() called by wp_ajax hooks: {'ehssl_reset_log'} **/
/** No params detected :-/ **/


/** Function handle_non_https_resources_scan() called by wp_ajax hooks: {'ehssl_non_https_resources_scan'} **/
/** Parameters found in function handle_non_https_resources_scan(): {"post": ["ehssl_post_types", "ehssl_other_tables", "ehssl_additional_flags", "ehssl_scan_type", "total", "offset"]} **/
function handle_non_https_resources_scan() {
        if ( ! check_ajax_referer( 'ehssl_non_https_resources_scan_form_nonce', false, false ) ) {
            wp_send_json_error(
                    array(
                            'message' => __( 'Nonce verification failed!', 'https-redirection' ),
                    )
            );
        }

        $post_types       = isset( $_POST['ehssl_post_types'] ) ? $_POST['ehssl_post_types'] : array();
        $this->post_types = $post_types;

        $other_tables       = isset( $_POST['ehssl_other_tables'] ) ? $_POST['ehssl_other_tables'] : array();
        $this->other_tables = $other_tables;

        $flags       = isset( $_POST['ehssl_additional_flags'] ) ? $_POST['ehssl_additional_flags'] : array();
        $this->flags = $flags;

        if ( isset( $_POST['ehssl_scan_type'] ) ) {
            $this->scan_type = sanitize_text_field( $_POST['ehssl_scan_type'] );
        }

        $total = isset( $_POST['total'] ) ? json_decode( ( stripslashes( $_POST['total'] ) ), true ) : array();

        $offset = isset( $_POST['offset'] ) ? intval( $_POST['offset'] ) : 0;

        // Check if this is the initial request.
        if ( $offset == 0 ) {
            // Clear old data.
            $this->clear_scan_results();

            // Get the scannable records count.
            $total = $this->count_scannable_items();
        }

        try {
            if ( ! empty( $post_types ) ) {
                $this->scan_post_types( $offset );
            }

            if ( ! empty( $other_tables ) && in_array( 'wp_options_table', $other_tables ) ) {
                $this->scan_wp_options( $offset );
            }

            // Debug purpose only. Uncomment to inspect found matches.
            //EHSSL_Logger::log( $this->scan_results );

            if ( ! empty( $this->scan_results ) ) {
                $this->save_scan_result();
            }

            $next_offset = $offset + $this->batch_size;

            $response = array(
                    'message'     => __( 'URLs scanned successfully!', 'https-redirection' ),
                    'completed'   => $next_offset >= $total['ehssl_post_types'] && $next_offset >= $total['ehssl_other_tables'],
                    'processed'   => $next_offset,
                    'total'       => $total,
                    'next_offset' => $next_offset,
            );

            wp_send_json_success( $response );

        } catch ( \Exception $e ) {
            EHSSL_Logger::log( $e->getMessage(), 4 );
            wp_send_json_error( array(
                    'message' => $e->getMessage(),
            ) );
        }
    }


/** Function handle_save_dashboard_sorting_order() called by wp_ajax hooks: {'ehssl_save_dashboard_order'} **/
/** Parameters found in function handle_save_dashboard_sorting_order(): {"post": ["ehssl_sort_order"]} **/
function handle_save_dashboard_sorting_order(){
        global $httpsrdrctn_options;

        if (isset($_POST['ehssl_sort_order']) && ! empty($_POST['ehssl_sort_order'])) {
            $sorting_data = $_POST['ehssl_sort_order'];
            $httpsrdrctn_options['dashboard_widget_sort_order'] = json_encode($sorting_data);
            update_option('httpsrdrctn_options', $httpsrdrctn_options);
            wp_send_json_success( $httpsrdrctn_options['dashboard_widget_sort_order'] );
          } else {
            wp_send_json_error("Invalid request");
          }

          // Always exit to avoid further execution
          wp_die();
    }


/** Function handle_load_non_https_resources_table_page() called by wp_ajax hooks: {'ehssl_load_static_resources_table_page'} **/
/** No params detected :-/ **/


