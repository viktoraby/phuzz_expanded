<?php
/***
*
*Found actions: 1
*Found functions:1
*Extracted functions:1
*Total parameter names extracted: 1
*Overview: {'handle_ajax_action': {'nopriv_abandoned-carts'}}
*
***/

/** Function handle_ajax_action() called by wp_ajax hooks: {'nopriv_abandoned-carts'} **/
/** Parameters found in function handle_ajax_action(): {"post": ["choice"]} **/
function handle_ajax_action(): void {
        check_ajax_referer( self::NOTICE_ACTION, 'nonce' );
        $choice = sanitize_text_field( $_POST['choice'] );

        switch ( $choice ) {
            case 'success':
                $this->handle_success();

                break;
            case 'dismiss':
                $this->handle_dismiss();
                break;
        }
    }


