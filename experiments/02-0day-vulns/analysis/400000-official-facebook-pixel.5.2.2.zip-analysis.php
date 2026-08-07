<?php
/***
*
*Found actions: 15
*Found functions:14
*Extracted functions:14
*Total parameter names extracted: 10
*Overview: {'save_capi_pii_caching_status': {'save_capi_pii_caching_status'}, 'fbl4b_clear_pixel': {'fbl4b_clear_pixel'}, 'injectAddToCartEventAjax': {'edd_add_to_cart', 'nopriv_edd_add_to_cart'}, 'delete_fbe_settings': {'delete_fbe_settings'}, 'send_capi_event': {'send_capi_event'}, 'fbl4b_validate_token': {'fbl4b_validate_token'}, 'fbl4b_fetch_business_id': {'fbl4b_fetch_business_id'}, 'fbl4b_fetch_pixels': {'fbl4b_fetch_pixels'}, 'save_fbe_settings': {'save_fbe_settings'}, 'save_capi_integration_events_filter': {'save_capi_integration_events_filter'}, 'delete_fbl4b_settings': {'delete_fbl4b_settings'}, 'save_capig': {'save_capig'}, 'save_fbl4b_settings': {'save_fbl4b_settings'}, 'save_capi_integration_status': {'save_capi_integration_status'}}
*
***/

/** Function save_capi_pii_caching_status() called by wp_ajax hooks: {'save_capi_pii_caching_status'} **/
/** Parameters found in function save_capi_pii_caching_status(): {"post": ["val"]} **/
function save_capi_pii_caching_status() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return $this->handle_unauthorized_request();
        }

        if ( empty( FacebookWordpressOptions::get_active_pixel_id() ) ) {
            \update_option(
                FacebookPluginConfig::CAPI_PII_CACHING_STATUS,
                FacebookPluginConfig::CAPI_PII_CACHING_STATUS_DEFAULT
            );
            return $this->handle_invalid_request();
        }

        check_admin_referer(
            FacebookPluginConfig::SAVE_CAPI_PII_CACHING_STATUS_ACTION_NAME
        );
        $val = sanitize_text_field(
            isset( $_POST['val'] ) ?
            wp_unslash( $_POST['val'] ) : ''
        );

        if ( ! ( '0' === $val || '1' === $val ) ) {
            return $this->handle_invalid_request();
        }

        \update_option( FacebookPluginConfig::CAPI_PII_CACHING_STATUS, $val );
        return $this->handle_success_request( $val );
    }


/** Function fbl4b_clear_pixel() called by wp_ajax hooks: {'fbl4b_clear_pixel'} **/
/** No params detected :-/ **/


/** Function injectAddToCartEventAjax() called by wp_ajax hooks: {'edd_add_to_cart', 'nopriv_edd_add_to_cart'} **/
/** Parameters found in function injectAddToCartEventAjax(): {"post": ["nonce", "download_id", "post_data"]} **/
function injectAddToCartEventAjax() {
        if ( isset( $_POST['nonce'] ) && isset( $_POST['download_id'] )
            && isset( $_POST['post_data'] ) ) {
            $download_id = absint( $_POST['download_id'] );
            $nonce       = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
            if ( wp_verify_nonce( $nonce, 'edd-add-to-cart-' . $download_id )
            === false ) {
                return;
            }
            parse_str( $_POST['post_data'], $post_data ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
            if ( isset( $post_data['facebook_event_id'] ) ) {
                $event_id = $post_data['facebook_event_id'];
            $server_event = ServerEventFactory::safe_create_event(
                'AddToCart',
                array( __CLASS__, 'createAddToCartEvent' ),
                array( $download_id ),
                self::TRACKING_NAME
            );
                $server_event->setEventId( $event_id );
                FacebookServerSideEvent::get_instance()->track( $server_event );
            }
        }
        parse_str( $_POST['post_data'], $post_data ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
        if ( isset( $post_data['facebook_event_id'] ) ) {
            $event_id     = $post_data['facebook_event_id'];
            $server_event = ServerEventFactory::safe_create_event(
                'AddToCart',
                array( __CLASS__, 'createAddToCartEvent' ),
                array( $download_id ),
                self::TRACKING_NAME
            );
            $server_event->setEventId( $event_id );
            FacebookServerSideEvent::get_instance()->track( $server_event );
        }
    }


/** Function delete_fbe_settings() called by wp_ajax hooks: {'delete_fbe_settings'} **/
/** No params detected :-/ **/


/** Function send_capi_event() called by wp_ajax hooks: {'send_capi_event'} **/
/** Parameters found in function send_capi_event(): {"post": ["nonce", "test_event_code", "event_name", "payload", "custom_data"]} **/
function send_capi_event() {
        $nonce = isset( $_POST['nonce'] ) ?
        sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : null;
        if ( ! isset( $nonce ) ||
        ! wp_verify_nonce( $nonce, 'send_capi_event_nonce' ) ) {
        wp_send_json_error(
            wp_json_encode(
                array(
                    'error' => array(
                        'message'        => 'Invalid nonce',
                        'error_user_msg' => 'Invalid nonce',
                    ),
                )
            )
        );
            wp_die();
        }

        $test_event_code = null;
        if ( isset( $_POST['test_event_code'] ) ) {
            $raw_test_event_code = sanitize_text_field(
                wp_unslash( $_POST['test_event_code'] )
            );
            if ( ! preg_match( '/^[A-Za-z0-9_]+$/', $raw_test_event_code ) ) {
                wp_send_json_error(
                    wp_json_encode(
                        array(
                            'error' => array(
                                'message'        => 'Invalid test event code',
                                'error_user_msg' =>
                                'Test event code must contain only'
                                . ' letters, numbers, and underscores.',
                            ),
                        )
                    )
                );
                wp_die();
            }
            $test_event_code = $raw_test_event_code;
        }

        $event_name = isset( $_POST['event_name'] ) ?
        sanitize_text_field( wp_unslash( $_POST['event_name'] ) ) : null;

        if ( empty( $_POST['payload'] ) && ! empty( $event_name ) ) {
            $custom_data         = isset( $_POST['custom_data'] ) ?
            $_POST['custom_data'] : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized,WordPress.Security.ValidatedSanitizedInput.MissingUnslash
            $invalid_custom_data =
            self::get_invalid_event_custom_data( $custom_data );
        if ( ! empty( $invalid_custom_data ) ) {
            $invalid_custom_data_msg = implode( ',', $invalid_custom_data );
            wp_send_json_error(
                wp_json_encode(
                    array(
                        'error' => array(
                            'message'        => 'Invalid custom_data attribute',
                            'error_user_msg' =>
                            'Invalid custom_data attributes: '
                            . $invalid_custom_data_msg,

                        ),
                    )
                )
            );
            wp_die();
        }

            $event  = ServerEventFactory::safe_create_event(
                $event_name,
                array( $this, 'get_event_custom_data' ),
                array( $custom_data ),
                'fb-capi-event',
                true
            );
            $events = array( $event );
        } else {
            $validated_payload =
            self::validate_payload(
                isset( $_POST['payload'] ) ?
                $_POST['payload'] : null // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized,WordPress.Security.ValidatedSanitizedInput.MissingUnslash
            );
        if ( ! $validated_payload['valid'] ) {
            wp_send_json_error(
                wp_json_encode(
                    array(
                        'error' => array(
                            'message'        => $validated_payload['message'],
                            'error_user_msg' =>
                            $validated_payload['error_user_msg'],
                        ),
                    )
                )
            );
            wp_die();
        }

            $raw_payload = isset( $_POST['payload'] ) ?
                $_POST['payload'] : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized,WordPress.Security.ValidatedSanitizedInput.MissingUnslash
            if ( isset( $raw_payload['test_event_code'] )
                && empty( $test_event_code ) ) {
                $test_event_code = sanitize_text_field(
                    $raw_payload['test_event_code']
                );
            }
            $events = array();
            if ( isset( $raw_payload['data'] ) ) {
                foreach ( $raw_payload['data'] as $event_as_array ) {
                    $events[] = ServerEventFactory::create_from_array(
                        $event_as_array
                    );
                }
            }
        }

        $result = FacebookServerSideEvent::send( $events, $test_event_code );

        if ( $result && $result['success'] ) {
            wp_send_json_success(
                wp_json_encode(
                    array(
                        'events_received' => $result['events_received'],
                    )
                )
            );
        } else {
            $error_msg      = isset( $result['error']['message'] )
                ? $result['error']['message'] : 'Unknown error';
            $error_user_msg = isset( $result['error']['error_user_msg'] )
                ? $result['error']['error_user_msg'] : $error_msg;
            wp_send_json_success(
                wp_json_encode(
                    array(
                        'error' => array(
                            'message'        => $error_msg,
                            'error_user_msg' => $error_user_msg,
                        ),
                    )
                )
            );
        }
        wp_die();
    }


/** Function fbl4b_validate_token() called by wp_ajax hooks: {'fbl4b_validate_token'} **/
/** No params detected :-/ **/


/** Function fbl4b_fetch_business_id() called by wp_ajax hooks: {'fbl4b_fetch_business_id'} **/
/** No params detected :-/ **/


/** Function fbl4b_fetch_pixels() called by wp_ajax hooks: {'fbl4b_fetch_pixels'} **/
/** Parameters found in function fbl4b_fetch_pixels(): {"post": ["businessId"]} **/
function fbl4b_fetch_pixels() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Unauthorized', 403 );
            return;
        }
        check_admin_referer( 'fbl4b_fetch_pixels' );

        $access_token = FacebookWordpressOptions::get_fbl4b_access_token();
        $business_id  = sanitize_text_field(
            isset( $_POST['businessId'] ) ?
            wp_unslash( $_POST['businessId'] ) : ''
        );

        if ( empty( $access_token ) || empty( $business_id ) ) {
            wp_send_json_error( 'Missing parameters', 400 );
            return;
        }

        $base_url = $this->get_graph_api_base_url();
        $headers  = array( 'Authorization' => 'Bearer ' . $access_token );

        $owned_url  = $base_url . '/' . $business_id
            . '/owned_pixels?fields=id,name&limit=100';
        $client_url = $base_url . '/' . $business_id
            . '/client_pixels?fields=id,name&limit=100';

        $owned_response  = wp_remote_get(
            $owned_url,
            array(
            'headers' => $headers,
            'timeout' => 15,
            )
        );
        $client_response = wp_remote_get(
            $client_url,
            array(
            'headers' => $headers,
            'timeout' => 15,
            )
        );

        $pixels   = array();
        $seen_ids = array();

        if ( ! is_wp_error( $owned_response )
            && 200 === wp_remote_retrieve_response_code( $owned_response ) ) {
            $owned_data = json_decode(
                wp_remote_retrieve_body( $owned_response ),
                true
            );
            if ( isset( $owned_data['data'] ) ) {
                foreach ( $owned_data['data'] as $pixel ) {
                    $pixels[]   = $pixel;
                    $seen_ids[] = $pixel['id'];
                }
            }
        }

        if ( ! is_wp_error( $client_response )
            && 200 === wp_remote_retrieve_response_code( $client_response ) ) {
            $client_data = json_decode(
                wp_remote_retrieve_body( $client_response ),
                true
            );
            if ( isset( $client_data['data'] ) ) {
                foreach ( $client_data['data'] as $pixel ) {
                    if ( ! in_array( $pixel['id'], $seen_ids, true ) ) {
                        $pixels[] = $pixel;
                    }
                }
            }
        }

        if ( empty( $pixels ) ) {
            wp_send_json_error(
                array(
                    'message' => 'No pixels found for this business.',
                    'code'    => 'no_pixels',
                )
            );
            return;
        }

        wp_send_json_success( array( 'data' => $pixels ) );
    }


/** Function save_fbe_settings() called by wp_ajax hooks: {'save_fbe_settings'} **/
/** Parameters found in function save_fbe_settings(): {"post": ["pixelId", "accessToken", "externalBusinessId"]} **/
function save_fbe_settings() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return $this->handle_unauthorized_request();
        }
        check_admin_referer(
            FacebookPluginConfig::SAVE_FBE_SETTINGS_ACTION_NAME
        );
        $pixel_id             = sanitize_text_field(
            isset( $_POST['pixelId'] ) ?
            wp_unslash( $_POST['pixelId'] ) : ''
        );
        $access_token         = sanitize_text_field(
            isset( $_POST['accessToken'] ) ?
            wp_unslash( $_POST['accessToken'] ) : ''
        );
        $external_business_id = sanitize_text_field(
            isset( $_POST['externalBusinessId'] ) ?
            wp_unslash( $_POST['externalBusinessId'] ) : ''
        );
        if ( empty( $pixel_id )
                || empty( $access_token )
                || empty( $external_business_id ) ) {
            return $this->handle_invalid_request();
        }
        $settings = array(
            FacebookPluginConfig::PIXEL_ID_KEY             => $pixel_id,
            FacebookPluginConfig::ACCESS_TOKEN_KEY         => $access_token,
            FacebookPluginConfig::EXTERNAL_BUSINESS_ID_KEY =>
            $external_business_id,
            FacebookPluginConfig::IS_FBE_INSTALLED_KEY     => '1',
        );
        \update_option(
            FacebookPluginConfig::SETTINGS_KEY,
            $settings
        );
        \delete_transient(
            FacebookPluginConfig::CONNECTION_INVALID_TRANSIENT
        );
        delete_metadata(
            'user',
            0,
            FacebookPluginConfig::ADMIN_IGNORE_CONNECTION_INVALID_NOTICE,
            '',
            true
        );
        return $this->handle_success_request( $settings );
    }


/** Function save_capi_integration_events_filter() called by wp_ajax hooks: {'save_capi_integration_events_filter'} **/
/** Parameters found in function save_capi_integration_events_filter(): {"post": ["val"]} **/
function save_capi_integration_events_filter() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return $this->handle_unauthorized_request();
        }

        if ( empty( FacebookWordpressOptions::get_active_pixel_id() ) ) {
            \update_option(
                FacebookPluginConfig::CAPI_INTEGRATION_EVENTS_FILTER,
                FacebookPluginConfig::CAPI_INTEGRATION_EVENTS_FILTER_DEFAULT
            );
            return $this->handle_invalid_request();
        }

        check_admin_referer(
            FacebookPluginConfig::SAVE_CAPI_INTEGRATION_EVENTS_FILTER_ACTION_NAME
        );
        $val                    = sanitize_text_field(
            isset( $_POST['val'] ) ?
            wp_unslash( $_POST['val'] ) : ''
        );
        $const_filter_page_view =
        FacebookPluginConfig::CAPI_INTEGRATION_FILTER_PAGE_VIEW_EVENT;
        $const_keep_page_view   =
        FacebookPluginConfig::CAPI_INTEGRATION_KEEP_PAGE_VIEW_EVENT;

        if ( ! ( $val === $const_filter_page_view
        || $val === $const_keep_page_view ) ) {
            return $this->handle_invalid_request();
        }

        $page_view_filtered =
        FacebookWordpressOptions::get_capi_integration_page_view_filtered();

        if ( $val === $const_keep_page_view && $page_view_filtered ) {
            \update_option(
                FacebookPluginConfig::CAPI_INTEGRATION_EVENTS_FILTER,
                FacebookPluginConfig::CAPI_INTEGRATION_EVENTS_FILTER_DEFAULT
            );
        } elseif ( $val === $const_filter_page_view && ! $page_view_filtered ) {
            \update_option(
                FacebookPluginConfig::CAPI_INTEGRATION_EVENTS_FILTER,
                FacebookPluginConfig::CAPI_INTEGRATION_EVENTS_FILTER_DEFAULT .
                    ',PageView'
            );
        }

        return $this->handle_success_request( $val );
    }


/** Function delete_fbl4b_settings() called by wp_ajax hooks: {'delete_fbl4b_settings'} **/
/** No params detected :-/ **/


/** Function save_capig() called by wp_ajax hooks: {'save_capig'} **/
/** Parameters found in function save_capig(): {"post": ["val"]} **/
function save_capig() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return $this->handle_unauthorized_request();
        }

        if ( empty( FacebookWordpressOptions::get_active_pixel_id() ) ) {
            \update_option(
                FacebookPluginConfig::CAPIG,
                FacebookPluginConfig::CAPIG_DEFAULT
            );
            return $this->handle_invalid_request();
        }

        check_admin_referer(
            FacebookPluginConfig::SAVE_CAPIG_ACTION_NAME
        );
        $val = sanitize_text_field(
            isset( $_POST['val'] ) ?
            wp_unslash( $_POST['val'] ) : ''
        );

        if ( ! ( '0' === $val || '1' === $val ) ) {
            return $this->handle_invalid_request();
        }

        \update_option( FacebookPluginConfig::CAPIG, $val );
        return $this->handle_success_request( $val );
    }


/** Function save_fbl4b_settings() called by wp_ajax hooks: {'save_fbl4b_settings'} **/
/** Parameters found in function save_fbl4b_settings(): {"server": ["HTTP_AUTHORIZATION"], "post": ["pixelId", "pixelName", "businessId"]} **/
function save_fbl4b_settings() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return $this->handle_unauthorized_request();
        }
        check_admin_referer( 'save_fbl4b_settings' );

        $access_token = '';
        // Read access token from Authorization: Bearer header.
        $auth_header = isset( $_SERVER['HTTP_AUTHORIZATION'] )
            ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_AUTHORIZATION'] ) )
            : '';
        if ( 0 === strpos( $auth_header, 'Bearer ' ) ) {
            $access_token = substr( $auth_header, 7 );
        }
        $pixel_id    = sanitize_text_field(
            isset( $_POST['pixelId'] ) ?
            wp_unslash( $_POST['pixelId'] ) : ''
        );
        $pixel_name  = sanitize_text_field(
            isset( $_POST['pixelName'] ) ?
            wp_unslash( $_POST['pixelName'] ) : ''
        );
        $business_id = sanitize_text_field(
            isset( $_POST['businessId'] ) ?
            wp_unslash( $_POST['businessId'] ) : ''
        );

        if ( empty( $access_token ) ) {
            // Partial update (e.g., pixel selection after initial save).
            $existing = \get_option(
                FacebookPluginConfig::FBL4B_SETTINGS_KEY,
                array()
            );
            if ( empty( $existing ) ) {
                return $this->handle_invalid_request();
            }
            if ( ! empty( $pixel_id ) ) {
                $existing[ FacebookPluginConfig::FBL4B_PIXEL_ID_KEY ] = $pixel_id;
            }
            if ( ! empty( $pixel_name ) ) {
                $existing[ FacebookPluginConfig::FBL4B_PIXEL_NAME_KEY ] =
                    $pixel_name;
            }
            if ( ! empty( $business_id ) ) {
                $existing[ FacebookPluginConfig::FBL4B_BUSINESS_ID_KEY ] =
                    $business_id;
            }
            \update_option(
                FacebookPluginConfig::FBL4B_SETTINGS_KEY,
                $existing
            );
            // If FBL4B now has a pixel, mark MBE as not installed so
            // Connection doesn't fall back to MBE after FBL4B disconnect.
            if ( ! empty( $pixel_id ) ) {
                $mbe_settings = \get_option(
                    FacebookPluginConfig::SETTINGS_KEY,
                    array()
                );
                if ( ! empty( $mbe_settings ) ) {
                    $mbe_settings[ FacebookPluginConfig::IS_FBE_INSTALLED_KEY ] = '0';
                    \update_option(
                        FacebookPluginConfig::SETTINGS_KEY,
                        $mbe_settings
                    );
                }
            }
            \delete_transient(
                FacebookPluginConfig::CONNECTION_INVALID_TRANSIENT
            );
            delete_metadata(
                'user',
                0,
                FacebookPluginConfig::ADMIN_IGNORE_CONNECTION_INVALID_NOTICE,
                '',
                true
            );
            return $this->handle_success_request( $existing );
        }

        // Initial save — encrypt and store the access token.
        $fbl4b_settings = array(
            FacebookPluginConfig::FBL4B_ACCESS_TOKEN_KEY =>
                FacebookWordpressOptions::encrypt_token( $access_token ),
            FacebookPluginConfig::FBL4B_PIXEL_ID_KEY     => $pixel_id,
            FacebookPluginConfig::FBL4B_PIXEL_NAME_KEY   => $pixel_name,
            FacebookPluginConfig::FBL4B_BUSINESS_ID_KEY  => $business_id,
        );
        \update_option(
            FacebookPluginConfig::FBL4B_SETTINGS_KEY,
            $fbl4b_settings
        );
        \delete_transient(
            FacebookPluginConfig::CONNECTION_INVALID_TRANSIENT
        );
        delete_metadata(
            'user',
            0,
            FacebookPluginConfig::ADMIN_IGNORE_CONNECTION_INVALID_NOTICE,
            '',
            true
        );

        return $this->handle_success_request( 'FBL4B settings saved' );
    }


/** Function save_capi_integration_status() called by wp_ajax hooks: {'save_capi_integration_status'} **/
/** Parameters found in function save_capi_integration_status(): {"post": ["val"]} **/
function save_capi_integration_status() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return $this->handle_unauthorized_request();
        }

        if ( empty( FacebookWordpressOptions::get_active_pixel_id() ) ) {
            \update_option(
                FacebookPluginConfig::CAPI_INTEGRATION_STATUS,
                FacebookPluginConfig::CAPI_INTEGRATION_STATUS_DEFAULT
            );
            return $this->handle_invalid_request();
        }

        check_admin_referer(
            FacebookPluginConfig::SAVE_CAPI_INTEGRATION_STATUS_ACTION_NAME
        );
        $val = sanitize_text_field(
            isset( $_POST['val'] ) ?
            wp_unslash( $_POST['val'] ) : ''
        );

        if ( ! ( '0' === $val || '1' === $val ) ) {
            return $this->handle_invalid_request();
        }

        \update_option( FacebookPluginConfig::CAPI_INTEGRATION_STATUS, $val );
        return $this->handle_success_request( $val );
    }


