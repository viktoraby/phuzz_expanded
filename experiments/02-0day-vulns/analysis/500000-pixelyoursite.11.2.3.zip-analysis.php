<?php
/***
*
*Found actions: 14
*Found functions:10
*Extracted functions:6
*Total parameter names extracted: 6
*Overview: {'catchOnCloseNotice': {'pys_fixed_notice_dismiss'}, 'catchAjaxEvent': {'pys_api_event', 'nopriv_pys_api_event'}, 'get_pbid_ajax': {'nopriv_pys_get_pbid', 'pys_get_pbid'}, 'PixelYourSite\\adminNoticeDismissHandler': {'pys_notice_dismiss'}, 'pys_optin_add': {'pys_optin_add'}, 'PixelYourSite\\getAjaxTransformTitle': {'get_transform_title', 'nopriv_get_transform_title'}, 'ajaxGetGdprFiltersValues': {'pys_get_gdpr_filters_values', 'nopriv_pys_get_gdpr_filters_values'}, 'PixelYourSite\\adminCapiNudgeDismissHandler': {'pys_capi_nudge_dismiss'}, 'allCloseNotice': {'pys_fixed_notice_opt_dismiss'}, 'PixelYourSite\\adminNoticeCAPIDismissHandler': {'pys_notice_CAPI_dismiss'}}
*
***/

/** Function catchOnCloseNotice() called by wp_ajax hooks: {'pys_fixed_notice_dismiss'} **/
/** Parameters found in function catchOnCloseNotice(): {"post": ["addon_slug", "meta_key"], "request": ["nonce"]} **/
function  catchOnCloseNotice() {
        require_once PYS_FREE_PATH . '/notices/fixed.php';
        $notices = adminGetFixedNotices();
        $user_id = get_current_user_id();


        if ( empty( $user_id ) || empty( $_POST['addon_slug'] ) || empty( $_POST['meta_key'] ) ) {
            return;
        }
        if ( empty( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'pys_fixed_notice_dismiss' ) ) {
            return;
        }

        $dismissedSlugs = (array) get_option($this->dismissedKey, array());
        foreach ($_POST['meta_key'] as $meta_key)
        {
            $dismissedSlugs[] = sanitize_text_field( $meta_key );
        }


        // save dismissed notice
        update_option( $this->dismissedKey, $dismissedSlugs );
        echo json_encode($this->whoIsNext($notices));
        die();
    }


/** Function catchAjaxEvent() called by wp_ajax hooks: {'pys_api_event', 'nopriv_pys_api_event'} **/
/** Parameters found in function catchAjaxEvent(): {"post": ["event", "data", "ids", "eventID", "woo_order", "edd_order"], "request": ["ajax_event"]} **/
function catchAjaxEvent() {
        PYS()->getLog()->debug('catchAjaxEvent send fb server from ajax');
        $event = $_POST['event'];
        $data = isset($_POST['data']) ? $_POST['data'] : array();
        $ids = $_POST['ids'];
        $eventID = $_POST['eventID'];
        $wooOrder = isset($_POST['woo_order']) ? $_POST['woo_order'] : null;
        $eddOrder = isset($_POST['edd_order']) ? $_POST['edd_order'] : null;


        if ( empty( $_REQUEST['ajax_event'] ) || !wp_verify_nonce( $_REQUEST['ajax_event'], 'ajax-event-nonce' ) ) {
            wp_die();
            return;
        }

        if($event == "hCR") $event="CompleteRegistration"; // de mask completer registration event if it was hidden

        $singleEvent = $this->dataToSingleEvent($event,$data,$eventID,$ids,$wooOrder,$eddOrder);

        $this->sendEventsNow([$singleEvent]);

        wp_die();
    }


/** Function get_pbid_ajax() called by wp_ajax hooks: {'nopriv_pys_get_pbid', 'pys_get_pbid'} **/
/** No params detected :-/ **/


/** Function PixelYourSite\adminNoticeDismissHandler() called by wp_ajax hooks: {'pys_notice_dismiss'} **/
/** No function found :-/ **/


/** Function pys_optin_add() called by wp_ajax hooks: {'pys_optin_add'} **/
/** Parameters found in function pys_optin_add(): {"post": ["name", "email", "tags"]} **/
function pys_optin_add()
    {
        check_ajax_referer( 'pys_optin_add', 'nonce' );

        if ( ! current_user_can( 'manage_pys' ) ) {
            wp_send_json_error( null, 403 );
        }

        $body = array(
            'action'  => 'optin_add',
            'data'  => array(
                'name'  => sanitize_text_field( wp_unslash( isset( $_POST['name'] ) ? $_POST['name'] : '' ) ),
                'email' => sanitize_email( wp_unslash( isset( $_POST['email'] ) ? $_POST['email'] : '' ) ),
                'tags'  => array_map( 'sanitize_text_field',
                    wp_unslash( isset( $_POST['tags'] ) ? (array) $_POST['tags'] : array() ) ),
            )
        );

        $response = wp_remote_post( 'https://www.pixelyoursite.com', array(
            'timeout'   => 30,
            'sslverify' => true,
            'user-agent' => 'PixelYourSite/' . PYS_FREE_VERSION . '; ' . get_bloginfo( 'url' ),
            'body'      => $body
        ) );


        if (is_wp_error($response)) {
            wp_send_json_error(null, 420);
        } else {
            $body = wp_remote_retrieve_body($response);
            $decoded_body = json_decode($body, true); // Decode the body to an array
            if (isset($decoded_body['data'])) {
                wp_send_json_success($decoded_body['data']); // Return only the 'data' part
            } else {
                wp_send_json_error('Invalid response format', 422);
            }
        }
    }


/** Function PixelYourSite\getAjaxTransformTitle() called by wp_ajax hooks: {'get_transform_title', 'nopriv_get_transform_title'} **/
/** No function found :-/ **/


/** Function ajaxGetGdprFiltersValues() called by wp_ajax hooks: {'pys_get_gdpr_filters_values', 'nopriv_pys_get_gdpr_filters_values'} **/
/** No params detected :-/ **/


/** Function PixelYourSite\adminCapiNudgeDismissHandler() called by wp_ajax hooks: {'pys_capi_nudge_dismiss'} **/
/** No function found :-/ **/


/** Function allCloseNotice() called by wp_ajax hooks: {'pys_fixed_notice_opt_dismiss'} **/
/** Parameters found in function allCloseNotice(): {"request": ["nonce"]} **/
function allCloseNotice(){
        require_once PYS_FREE_PATH . '/notices/fixed.php';
        $notices = adminGetFixedNotices();
        $user_id = get_current_user_id();


        if ( empty( $user_id ) ) {
            return;
        }
        if ( empty( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'pys_fixed_notice_opt_dismiss') ) {
            return;
        }
        $dismissedSlugs = (array) get_option($this->dismissedKey, array());
        foreach ($notices as $noticesGroup)
        {
            foreach ($noticesGroup['multiMessage'] as $noticesMessage) {
                $dismissedSlugs[] = sanitize_text_field( $noticesMessage['slug'] );
            }

        }
        $dismissedSlugs = array_unique($dismissedSlugs);
        update_option( $this->dismissedKey, $dismissedSlugs );
        echo json_encode($dismissedSlugs);
        die();
    }


/** Function PixelYourSite\adminNoticeCAPIDismissHandler() called by wp_ajax hooks: {'pys_notice_CAPI_dismiss'} **/
/** No function found :-/ **/


