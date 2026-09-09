<?php
/***
*
*Found actions: 21
*Found functions:14
*Extracted functions:14
*Total parameter names extracted: 13
*Overview: {'excludeCriticalPhrase': {'dgwt_wcas_exclude_critical_phrase'}, 'set_search_post_ids_from_ajax': {'nopriv_prdctfltr_respond_550', 'us_ajax_grid', 'nopriv_filtersFrontend', 'load_more', 'prdctfltr_respond_550', 'filtersFrontend', 'nopriv_load_more', 'nopriv_us_ajax_grid'}, 'loadInterface': {'dgwt_wcas_load_stats_interface'}, 'asyncActionHandler': {'dgwt_wcas_troubleshooting_async_action'}, 'loadMoreCriticalSearches': {'dgwt_wcas_laod_more_critical_searches'}, 'loadMoreSearchPage': {'dgwt_wcas_laod_more_search_page'}, 'toggleAdvancedSettings': {'dgwt_wcas_adv_settings'}, 'resetStats': {'dgwt_wcas_reset_stats'}, 'loadMoreAutocomplete': {'dgwt_wcas_laod_more_autocomplete'}, 'checkCriticalPhrase': {'dgwt_wcas_check_critical_phrase'}, 'exportStats': {'dgwt_wcas_export_stats_csv'}, 'dismiss_notice_ajax_callback': {'fs_dismiss_notice_action_{$ajax_action_suffix}'}, '_toggle_debug_mode': {'fs_toggle_debug_mode'}, 'asyncTest': {'dgwt_wcas_troubleshooting_test'}}
*
***/

/** Function excludeCriticalPhrase() called by wp_ajax hooks: {'dgwt_wcas_exclude_critical_phrase'} **/
/** Parameters found in function excludeCriticalPhrase(): {"request": ["phrase", "lang"]} **/
function excludeCriticalPhrase() {
        if ( !current_user_can( ( Helpers::shopManagerHasAccess() ? 'manage_woocommerce' : 'manage_options' ) ) ) {
            wp_die( -1, 403 );
        }
        check_ajax_referer( self::EXCLUDE_CRITICAL_PHRASE_NONCE );
        $phrase = ( !empty( $_REQUEST['phrase'] ) ? $_REQUEST['phrase'] : '' );
        $lang = ( !empty( $_REQUEST['lang'] ) && Multilingual::isLangCode( $_REQUEST['lang'] ) ? sanitize_key( $_REQUEST['lang'] ) : '' );
        if ( !empty( $phrase ) ) {
            $data = new Data();
            if ( Multilingual::isMultilingual() && !empty( $lang ) ) {
                $data->setLang( $lang );
            }
            if ( $data->markAsSolved( $phrase ) ) {
                wp_send_json_success( '<p>' . __( 'This phrase has been resolved! This row will disappear after refreshing the page.', 'ajax-search-for-woocommerce' ) . '</p>' );
            }
        }
        wp_send_json_error( 'empty phrase' );
    }


/** Function set_search_post_ids_from_ajax() called by wp_ajax hooks: {'nopriv_prdctfltr_respond_550', 'us_ajax_grid', 'nopriv_filtersFrontend', 'load_more', 'prdctfltr_respond_550', 'filtersFrontend', 'nopriv_load_more', 'nopriv_us_ajax_grid'} **/
/** Parameters found in function set_search_post_ids_from_ajax(): {"post": ["template_vars"]} **/
function set_search_post_ids_from_ajax() {
        if ( !$this->isRelevantProductAjaxQuery() ) {
            return;
        }
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $template_vars_json = $_POST['template_vars'] ?? '';
        $template_vars = json_decode( stripslashes( $template_vars_json ), true );
        $search_term = $template_vars['query_args']['s'] ?? '';
        if ( empty( $search_term ) ) {
            return;
        }
        if ( !dgoraAsfwFs()->is_premium() ) {
            $this->post_ids = Helpers::searchProducts( $search_term );
        }
    }


/** Function loadInterface() called by wp_ajax hooks: {'dgwt_wcas_load_stats_interface'} **/
/** Parameters found in function loadInterface(): {"request": ["lang"]} **/
function loadInterface() {
        if ( !current_user_can( ( Helpers::shopManagerHasAccess() ? 'manage_woocommerce' : 'manage_options' ) ) ) {
            wp_die( -1, 403 );
        }
        check_ajax_referer( self::LOAD_INTERFACE_NONCE );
        $lang = ( !empty( $_REQUEST['lang'] ) && Multilingual::isLangCode( sanitize_key( $_REQUEST['lang'] ) ) ? sanitize_key( $_REQUEST['lang'] ) : '' );
        $data = [
            'html' => '',
        ];
        ob_start();
        $vars = $this->getVars( $lang );
        require DGWT_WCAS_DIR . 'partials/admin/stats/stats.php';
        $data['html'] = ob_get_clean();
        wp_send_json_success( $data );
    }


/** Function asyncActionHandler() called by wp_ajax hooks: {'dgwt_wcas_troubleshooting_async_action'} **/
/** Parameters found in function asyncActionHandler(): {"post": ["internal_action"]} **/
function asyncActionHandler() {
        if ( !current_user_can( ( Helpers::shopManagerHasAccess() ? 'manage_woocommerce' : 'manage_options' ) ) ) {
            wp_die( -1, 403 );
        }
        check_ajax_referer( self::ASYNC_ACTION_NONCE );
        $internalAction = $_POST['internal_action'] ?? '';
        $data = [];
        $success = false;
        switch ( $internalAction ) {
            case 'dismiss_elementor_template':
                update_option( 'dgwt_wcas_dismiss_elementor_template', '1' );
                $success = true;
                break;
            case 'reset_async_tests':
                // Reset stored results of async tests.
                delete_transient( self::TRANSIENT_RESULTS_KEY );
                $success = true;
                break;
            case 'dismiss_regenerate_images':
                update_option( self::IMAGES_ALREADY_REGENERATED_OPT_KEY, '1' );
                $success = true;
                break;
            case 'regenerate_images':
                $this->regenerateImages();
                $data['args'] = [
                    'dgwt-wcas-regenerate-images-started' => true,
                ];
                $success = true;
                break;
        }
        ( $success ? wp_send_json_success( $data ) : wp_send_json_error( $data ) );
    }


/** Function loadMoreCriticalSearches() called by wp_ajax hooks: {'dgwt_wcas_laod_more_critical_searches'} **/
/** Parameters found in function loadMoreCriticalSearches(): {"request": ["lang", "loaded"]} **/
function loadMoreCriticalSearches() {
        if ( !current_user_can( ( Helpers::shopManagerHasAccess() ? 'manage_woocommerce' : 'manage_options' ) ) ) {
            wp_die( -1, 403 );
        }
        check_ajax_referer( self::LOAD_MORE_CRITICAL_SEARCHES_NONCE );
        $lang = ( !empty( $_REQUEST['lang'] ) && Multilingual::isLangCode( sanitize_key( $_REQUEST['lang'] ) ) ? sanitize_key( $_REQUEST['lang'] ) : '' );
        $offset = ( !empty( $_REQUEST['loaded'] ) ? absint( $_REQUEST['loaded'] ) : 0 );
        $html = '';
        $data = new Data();
        if ( !empty( $lang ) ) {
            $data->setLang( $lang );
        }
        $total = $data->getTotalCriticalSearches();
        $critical = $data->getCriticalSearches( self::CRITICAL_SEARCHES_LOAD_LIMIT, $offset );
        if ( !empty( $critical ) ) {
            ob_start();
            $i = $offset + 1;
            foreach ( $critical as $row ) {
                require DGWT_WCAS_DIR . 'partials/admin/stats/critical-searches-row.php';
                $i++;
            }
            $html = ob_get_clean();
        }
        $toLoad = $total - $offset - count( $critical );
        $more = min( self::CRITICAL_SEARCHES_LOAD_LIMIT, $toLoad );
        $data = [
            'html'       => $html,
            'more'       => $more,
            'more_label' => '',
        ];
        if ( $more > 0 ) {
            $data['more_label'] = sprintf( _n(
                'load another %d phrase',
                'load another %d phrases',
                $more,
                'ajax-search-for-woocommerce'
            ), $more );
        }
        wp_send_json_success( $data );
    }


/** Function loadMoreSearchPage() called by wp_ajax hooks: {'dgwt_wcas_laod_more_search_page'} **/
/** Parameters found in function loadMoreSearchPage(): {"request": ["lang"]} **/
function loadMoreSearchPage() {
        if ( !current_user_can( ( Helpers::shopManagerHasAccess() ? 'manage_woocommerce' : 'manage_options' ) ) ) {
            wp_die( -1, 403 );
        }
        check_ajax_referer( self::LOAD_MORE_SEARCH_PAGE_NONCE );
        $lang = ( !empty( $_REQUEST['lang'] ) && Multilingual::isLangCode( sanitize_key( $_REQUEST['lang'] ) ) ? sanitize_key( $_REQUEST['lang'] ) : '' );
        // Search page
        $data = new Data();
        if ( !empty( $lang ) ) {
            $data->setLang( $lang );
        }
        $data->setContext( 'search-results-page' );
        $phrases = $data->getPhrasesWithResults( 100 );
        ob_start();
        $i = 1;
        foreach ( $phrases as $row ) {
            require DGWT_WCAS_DIR . 'partials/admin/stats/sp-searches-row.php';
            $i++;
        }
        $html = ob_get_clean();
        $data = [
            'html' => $html,
        ];
        wp_send_json_success( $data );
    }


/** Function toggleAdvancedSettings() called by wp_ajax hooks: {'dgwt_wcas_adv_settings'} **/
/** Parameters found in function toggleAdvancedSettings(): {"get": ["adv_settings_value"]} **/
function toggleAdvancedSettings() {
        if ( !current_user_can( ( Helpers::shopManagerHasAccess() ? 'manage_woocommerce' : 'manage_options' ) ) ) {
            wp_die( -1, 403 );
        }
        check_ajax_referer( 'dgwt_wcas_advanced_options_switch' );
        $show = ( !empty( $_GET['adv_settings_value'] ) && $_GET['adv_settings_value'] === 'show' ? 'on' : 'off' );
        update_option( 'dgwt_wcas_settings_show_advanced', $show );
        wp_send_json_success();
    }


/** Function resetStats() called by wp_ajax hooks: {'dgwt_wcas_reset_stats'} **/
/** No params detected :-/ **/


/** Function loadMoreAutocomplete() called by wp_ajax hooks: {'dgwt_wcas_laod_more_autocomplete'} **/
/** Parameters found in function loadMoreAutocomplete(): {"request": ["lang"]} **/
function loadMoreAutocomplete() {
        if ( !current_user_can( ( Helpers::shopManagerHasAccess() ? 'manage_woocommerce' : 'manage_options' ) ) ) {
            wp_die( -1, 403 );
        }
        check_ajax_referer( self::LOAD_MORE_AUTOCOMPLETE_NONCE );
        $lang = ( !empty( $_REQUEST['lang'] ) && Multilingual::isLangCode( sanitize_key( $_REQUEST['lang'] ) ) ? sanitize_key( $_REQUEST['lang'] ) : '' );
        // Autocomplete
        $data = new Data();
        if ( !empty( $lang ) ) {
            $data->setLang( $lang );
        }
        $data->setContext( 'autocomplete' );
        $phrases = $data->getPhrasesWithResults( 100 );
        ob_start();
        $i = 1;
        foreach ( $phrases as $row ) {
            require DGWT_WCAS_DIR . 'partials/admin/stats/ac-searches-row.php';
            $i++;
        }
        $html = ob_get_clean();
        $data = [
            'html' => $html,
        ];
        wp_send_json_success( $data );
    }


/** Function checkCriticalPhrase() called by wp_ajax hooks: {'dgwt_wcas_check_critical_phrase'} **/
/** Parameters found in function checkCriticalPhrase(): {"request": ["phrase"]} **/
function checkCriticalPhrase() {
        if ( !current_user_can( ( Helpers::shopManagerHasAccess() ? 'manage_woocommerce' : 'manage_options' ) ) ) {
            wp_die( -1, 403 );
        }
        check_ajax_referer( self::CRITICAL_CHECK_NONCE );
        $data = [
            'html'   => '',
            'status' => '',
        ];
        $phrase = ( !empty( $_REQUEST['phrase'] ) ? $_REQUEST['phrase'] : '' );
        if ( empty( $phrase ) ) {
            wp_send_json_error( 'empty phrase' );
        }
        if ( !dgoraAsfwFs()->is_premium() ) {
            $res = DGWT_WCAS()->nativeSearch->getSearchResults( $phrase, true, 'autocomplete' );
            if ( is_array( $res ) && isset( $res['total'] ) ) {
                $total = absint( $res['total'] );
                if ( $total > 0 ) {
                    $data['status'] = 'with-results';
                    $data['html'] = $this->getCriticalPhraseMessage( $data['status'], $total );
                } else {
                    $data['status'] = 'without-results';
                    $data['html'] = $this->getCriticalPhraseMessage( $data['status'] );
                }
            } else {
                $data['status'] = 'error';
                $data['html'] = $this->getCriticalPhraseMessage( $data['status'] );
            }
        } else {
        }
        wp_send_json_success( $data );
    }


/** Function exportStats() called by wp_ajax hooks: {'dgwt_wcas_export_stats_csv'} **/
/** Parameters found in function exportStats(): {"get": ["context"], "request": ["lang"]} **/
function exportStats() {
        if ( !current_user_can( ( Helpers::shopManagerHasAccess() ? 'manage_woocommerce' : 'manage_options' ) ) ) {
            wp_die( -1, 403 );
        }
        check_ajax_referer( self::EXPORT_STATS_CSV_NONCE );
        if ( !class_exists( 'WC_CSV_Exporter', false ) ) {
            require_once WC_ABSPATH . 'includes/export/abstract-wc-csv-exporter.php';
        }
        $exporter = new CSVExporter();
        $context = ( isset( $_GET['context'] ) ? sanitize_key( $_GET['context'] ) : '' );
        $exporter->set_context( $context );
        $lang = ( !empty( $_REQUEST['lang'] ) && Multilingual::isLangCode( sanitize_key( $_REQUEST['lang'] ) ) ? sanitize_key( $_REQUEST['lang'] ) : '' );
        if ( !empty( $lang ) ) {
            $exporter->set_lang( $lang );
        }
        $exporter->export();
    }


/** Function dismiss_notice_ajax_callback() called by wp_ajax hooks: {'fs_dismiss_notice_action_{$ajax_action_suffix}'} **/
/** Parameters found in function dismiss_notice_ajax_callback(): {"post": ["message_id"]} **/
function dismiss_notice_ajax_callback() {
            check_admin_referer( 'fs_dismiss_notice_action' );

            if ( ! is_numeric( $_POST['message_id'] ) ) {
                $this->_sticky_storage->remove( $_POST['message_id'] );
            }

            wp_die();
        }


/** Function _toggle_debug_mode() called by wp_ajax hooks: {'fs_toggle_debug_mode'} **/
/** No params detected :-/ **/


/** Function asyncTest() called by wp_ajax hooks: {'dgwt_wcas_troubleshooting_test'} **/
/** Parameters found in function asyncTest(): {"post": ["test"]} **/
function asyncTest() {
        if ( !current_user_can( ( Helpers::shopManagerHasAccess() ? 'manage_woocommerce' : 'manage_options' ) ) ) {
            wp_die( -1, 403 );
        }
        check_ajax_referer( self::ASYNC_TEST_NONCE );
        $test = ( isset( $_POST['test'] ) ? wc_clean( wp_unslash( $_POST['test'] ) ) : '' );
        if ( !$this->isTestExists( $test ) ) {
            wp_send_json_error();
        }
        $testFunction = sprintf( 'getTest%s', $test );
        if ( method_exists( $this, $testFunction ) && is_callable( [$this, $testFunction] ) ) {
            $data = $this->performTest( [$this, $testFunction] );
            wp_send_json_success( $data );
        }
        wp_send_json_error();
    }


