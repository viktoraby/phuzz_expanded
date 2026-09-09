<?php
/***
*
*Found actions: 20
*Found functions:18
*Extracted functions:18
*Total parameter names extracted: 13
*Overview: {'mailchimp_woocommerce_ajax_oauth_start': {'mailchimp_woocommerce_oauth_start'}, 'mailchimp_woocommerce_communication_status': {'mailchimp_woocommerce_communication_status'}, 'mailchimp_woocommerce_ajax_delete_log_file': {'mailchimp_woocommerce_delete_log_file'}, 'mailchimp_woocommerce_ajax_check_login_session': {'mailchimp_woocommerce_check_login_session'}, 'mailchimp_woocommerce_ajax_toggle_chimpstatic_script': {'mailchimp_woocommerce_toggle_chimpstatic_script'}, 'mailchimp_woocommerce_send_event': {'mailchimp_woocommerce_send_event'}, 'set_user_by_email': {'nopriv_mailchimp_set_user_by_email', 'mailchimp_set_user_by_email'}, 'mailchimp_woocommerce_ajax_create_account_check_username': {'mailchimp_woocommerce_create_account_check_username'}, 'mailchimp_woocommerce_ajax_support_form': {'mailchimp_woocommerce_support_form'}, 'mailchimp_woocommerce_activate_account_event': {'mailchimp_woocommerce_activate_account_event'}, 'mailchimp_woocommerce_tower_status': {'mailchimp_woocommerce_tower_status'}, 'mailchimp_woocommerce_ajax_load_log_file': {'mailchimp_woocommerce_load_log_file'}, 'get_user_by_hash': {'nopriv_mailchimp_get_user_by_hash', 'mailchimp_get_user_by_hash'}, 'mailchimp_woocommerce_ajax_create_account_signup': {'mailchimp_woocommerce_create_account_signup'}, 'connect_account_flow_switch_account': {'mailchimp_woocommerce_switch_account'}, 'mailchimp_create_additional_runners': {'nopriv_mailchimp_create_additional_runners'}, 'mailchimp_woocommerce_ajax_oauth_status': {'mailchimp_woocommerce_oauth_status'}, 'mailchimp_woocommerce_ajax_oauth_finish': {'mailchimp_woocommerce_oauth_finish'}}
*
***/

/** Function mailchimp_woocommerce_ajax_oauth_start() called by wp_ajax hooks: {'mailchimp_woocommerce_oauth_start'} **/
/** No params detected :-/ **/


/** Function mailchimp_woocommerce_communication_status() called by wp_ajax hooks: {'mailchimp_woocommerce_communication_status'} **/
/** Parameters found in function mailchimp_woocommerce_communication_status(): {"post": ["opt"]} **/
function mailchimp_woocommerce_communication_status() {
		$this->adminOnlyMiddleware();

		$original_opt = $this->getData( 'comm.opt', 0 );
		$opt          = $_POST['opt'];
		$admin_email  = $this->getOptions()['admin_email'];

        if (empty($admin_email)) {
            $admin_email = get_option('admin_email');
        }

        if (!is_email($admin_email)) {
            wp_send_json_error(
                array(
                    'error' => __( 'Error setting communications status', 'mailchimp-for-woocommerce' ),
                    'opt'   => $original_opt,
                    'reason' => 'invalid email',
                )
            );
        }

		mailchimp_debug( 'communication_status', "setting to {$opt} for {$admin_email}" );
		Mailchimp_Woocommerce_Event::track('navigation_advanced:opt_in_email', new DateTime());

		// try to set the info on the server
		// post to communications api
		$response = $this->mailchimp_set_communications_status_on_server( $opt, $admin_email );

		// if success, set internal option to check for opt and display on sync page
		if ( $response['response']['code'] == 200 ) {
			$response_body = json_decode( $response['body'] );
			if ( isset( $response_body ) && isset($response_body->success) && $response_body->success == true ) {
				$this->setData( 'comm.opt', $opt );
				wp_send_json_success( __( 'Saved', 'mailchimp-for-woocommerce' ) );
			}
		} else {
			// if error, keep option to original value
			wp_send_json_error(
				array(
					'error' => __( 'Error setting communications status', 'mailchimp-for-woocommerce' ),
					'opt'   => $original_opt,
				)
			);
		}
		wp_die();
	}


/** Function mailchimp_woocommerce_ajax_delete_log_file() called by wp_ajax hooks: {'mailchimp_woocommerce_delete_log_file'} **/
/** Parameters found in function mailchimp_woocommerce_ajax_delete_log_file(): {"post": ["log_file"]} **/
function mailchimp_woocommerce_ajax_delete_log_file() {
		$this->adminOnlyMiddleware();
		if ( ! isset( $_POST['log_file'] ) || empty( $_POST['log_file'] ) ) {
			wp_send_json_error( __( 'No log file provided', 'mailchimp-for-woocommerce' ) );
			return;
		}
		$requested_log_file = $_POST['log_file'];
		$log_handler        = new WC_Log_Handler_File();
		$removed            = $log_handler->remove( str_replace( '-log', '.log', $requested_log_file ) );

        Mailchimp_Woocommerce_Event::track('navigation_logs:delete', new DateTime());

        wp_send_json_success( array( 'success' => $removed ) );
	}


/** Function mailchimp_woocommerce_ajax_check_login_session() called by wp_ajax hooks: {'mailchimp_woocommerce_check_login_session'} **/
/** No params detected :-/ **/


/** Function mailchimp_woocommerce_ajax_toggle_chimpstatic_script() called by wp_ajax hooks: {'mailchimp_woocommerce_toggle_chimpstatic_script'} **/
/** No params detected :-/ **/


/** Function mailchimp_woocommerce_send_event() called by wp_ajax hooks: {'mailchimp_woocommerce_send_event'} **/
/** Parameters found in function mailchimp_woocommerce_send_event(): {"post": ["mc_event", "mc_context"]} **/
function mailchimp_woocommerce_send_event()
    {
        $this->adminOnlyMiddleware();
        if (empty( $_POST['mc_event'])) {
          wp_send_json_error( __( 'No event provided', 'mailchimp-for-woocommerce' ) );
          return;
        }
        $mc_event = sanitize_text_field($_POST['mc_event']);
        //leave room for something like this later.
        //$mc_context = isset($_POST['mc_context']) ? sanitize_text_field($_POST['mc_context']) : null;
        $map = [
          'leave_review' => 'audience_stats:leave_review',
          'recommendation_1' => 'audience_stats:recommendation_1',
          'recommendation_2' => 'navigation_audience:abandoned_cart',
          'recommendation_3' => 'audience_stats:recommendation_3',
          'click_create_account' => 'connect_accounts:click_to_create_account',
          'continue_to_mailchimp' => 'audience_stats:continue_to_mailchimp',
          'save_log' => 'navigation_logs:save',
          'click_connect_account' => 'connect_accounts:click_start',
        ];
        $payload = array_key_exists($mc_event, $map) ?
            Mailchimp_Woocommerce_Event::track($map[$mc_event], new DateTime()) : 'nothing';
        wp_send_json_success(['payload' => $payload]);
    }


/** Function set_user_by_email() called by wp_ajax hooks: {'nopriv_mailchimp_set_user_by_email', 'mailchimp_set_user_by_email'} **/
/** Parameters found in function set_user_by_email(): {"post": ["email", "mc_language", "subscribed"]} **/
function set_user_by_email()
    {
        if (mailchimp_carts_disabled()) {
            $this->respondJSON(array('success' => false, 'message' => 'filter blocked due to carts being disabled'));
        }

        if ($this->is_admin) {
            $this->respondJSON(array('success' => false, 'message' => 'admin carts are not tracked.'));
        }

        if (!mailchimp_allowed_to_use_cookie('mailchimp_user_email')) {
            $this->respondJSON(array('success' => false, 'email' => false, 'message' => 'filter blocked due to cookie preferences'));
        }

        if ($this->doingAjax() && isset($_POST['email'])) {
            $cookie_duration = $this->getCookieDuration();

            $this->user_email = trim(str_replace(' ','+', $_POST['email']));

            if (($current_email = $this->getEmailFromSession()) && $current_email !== $this->user_email) {
                $this->previous_email = $current_email;
                $this->force_cart_post = true;
                mailchimp_set_cookie('mailchimp_user_previous_email',$this->user_email, $cookie_duration, '/' );
            }

            mailchimp_set_cookie('mailchimp_user_email', $this->user_email, $cookie_duration, '/' );

            $this->getCartItems();

            if (isset($_POST['mc_language'])) {
                $this->user_language = $_POST['mc_language'];
            }

            if (isset($_POST['subscribed'])) {
                $this->cart_subscribe = (bool) $_POST['subscribed'];
            }

            $this->handleCartUpdated();

            $this->respondJSON(array(
                'success' => true,
                'email' => $this->user_email,
                'previous' => $this->previous_email,
                'cart' => $this->cart,
            ));
        }

        $this->respondJSON(array('success' => false, 'email' => false));
    }


/** Function mailchimp_woocommerce_ajax_create_account_check_username() called by wp_ajax hooks: {'mailchimp_woocommerce_create_account_check_username'} **/
/** Parameters found in function mailchimp_woocommerce_ajax_create_account_check_username(): {"post": ["username"]} **/
function mailchimp_woocommerce_ajax_create_account_check_username() {
		$this->adminOnlyMiddleware();

		$username = sanitize_text_field( $_POST['username'] );
		$response = wp_remote_get( 'https://woocommerce.mailchimpapp.com/api/usernames/available/' . $username );
		// need to return the error message if this is the problem.
		if ( $response instanceof WP_Error ) {
			wp_send_json_error( $response );
		}
		$response_body = json_decode( $response['body'] );
		if ( $response['response']['code'] == 200 && $response_body->success == true ) {
			wp_send_json_success( $response );
		} elseif ( $response['response']['code'] == 404 ) {
			wp_send_json_error(
				array(
					'success' => false,
				)
			);
		} else {
			$suggestion = wp_remote_get( 'https://woocommerce.mailchimpapp.com/api/usernames/suggestions/' . preg_replace( '/[^A-Za-z0-9\-\@\.]/', '', $username ) );
			// need to return the error message if this is the problem.
			if ( $suggestion instanceof WP_Error ) {
				wp_send_json_error( $suggestion );
			}
			$suggested_username = json_decode( $suggestion['body'] )->data;
			wp_send_json_error(
				array(
					'success'    => false,
					'suggestion' => $suggested_username[0],
				)
			);
		}
	}


/** Function mailchimp_woocommerce_ajax_support_form() called by wp_ajax hooks: {'mailchimp_woocommerce_support_form'} **/
/** No params detected :-/ **/


/** Function mailchimp_woocommerce_activate_account_event() called by wp_ajax hooks: {'mailchimp_woocommerce_activate_account_event'} **/
/** No params detected :-/ **/


/** Function mailchimp_woocommerce_tower_status() called by wp_ajax hooks: {'mailchimp_woocommerce_tower_status'} **/
/** Parameters found in function mailchimp_woocommerce_tower_status(): {"post": ["opt"]} **/
function mailchimp_woocommerce_tower_status() {
		$this->adminOnlyMiddleware();

		$original_opt = $this->getData( 'tower.opt', 0 );
		$opt          = $_POST['opt'];

		mailchimp_debug( 'tower_status', "setting to {$opt}" );
        $key = (bool) $opt ? 'enable_support' : 'disable_support';
		Mailchimp_Woocommerce_Event::track("navigation_advanced:{$key}", new DateTime());

		// try to set the info on the server
		// post to communications api
		$job           = new MailChimp_WooCommerce_Tower( mailchimp_get_store_id() );
		$response_body = $job->toggle( $opt );

		// if success, set internal option to check for opt and display on sync page
		if ( $response_body && isset($response_body->success) && $response_body->success == true ) {
			$this->setData( 'tower.opt', $opt );
			wp_send_json_success( __( 'Saved', 'mailchimp-for-woocommerce' ) );
		} else {
			// if error, keep option to original value
			wp_send_json_error(
				array(
					'error' => $response_body->message,
					'opt'   => $original_opt,
				)
			);
		}

		wp_die();
	}


/** Function mailchimp_woocommerce_ajax_load_log_file() called by wp_ajax hooks: {'mailchimp_woocommerce_load_log_file'} **/
/** Parameters found in function mailchimp_woocommerce_ajax_load_log_file(): {"post": ["log_file"]} **/
function mailchimp_woocommerce_ajax_load_log_file() {
		$this->adminOnlyMiddleware();

		if ( ! isset( $_POST['log_file'] ) || empty( $_POST['log_file'] ) ) {
			wp_send_json_error( __( 'No log file provided', 'mailchimp-for-woocommerce' ) );
			return;
		}
		$requested_log_file = sanitize_text_field( $_POST['log_file'] );
		$files              = defined( 'WC_LOG_DIR' ) ? @scandir( WC_LOG_DIR ) : array();

		Mailchimp_Woocommerce_Event::track('navigation_logs:selection', new DateTime());

		$logs = array();
		if ( ! empty( $files ) ) {
			foreach ( array_reverse( $files ) as $key => $value ) {
				if ( ! in_array( $value, array( '.', '..' ) ) ) {
					if ( ! is_dir( $value ) && mailchimp_string_contains( $value, 'mailchimp_woocommerce' ) ) {
						$logs[ sanitize_title( $value ) ] = $value;
					}
				}
			}
		}

		if ( ! empty( $requested_log_file ) && isset( $logs[ sanitize_title( $requested_log_file ) ] ) ) {
			$viewed_log = $logs[ sanitize_title( $requested_log_file ) ];
			wp_send_json_success( esc_html( file_get_contents( WC_LOG_DIR . $viewed_log ) ) );
			return;
		}

		wp_send_json_error( __( 'Error loading log file contents', 'mailchimp-for-woocommerce' ) );
	}


/** Function get_user_by_hash() called by wp_ajax hooks: {'nopriv_mailchimp_get_user_by_hash', 'mailchimp_get_user_by_hash'} **/
/** Parameters found in function get_user_by_hash(): {"get": ["hash"]} **/
function get_user_by_hash()
    {
        if ($this->doingAjax() && isset($_GET['hash'])) {
            if (($cart = $this->getCart($_GET['hash']))) {
                $this->respondJSON(array('success' => true, 'email' => $cart->email));
            }
        }
        $this->respondJSON(array('success' => false, 'email' => false));
    }


/** Function mailchimp_woocommerce_ajax_create_account_signup() called by wp_ajax hooks: {'mailchimp_woocommerce_create_account_signup'} **/
/** Parameters found in function mailchimp_woocommerce_ajax_create_account_signup(): {"post": ["data"]} **/
function mailchimp_woocommerce_ajax_create_account_signup() {
		//Mailchimp_Woocommerce_Event::track('account:sign_up_button_click', new DateTime());
		Mailchimp_Woocommerce_Event::track('connect_accounts:activate_account', new DateTime());
		$this->adminOnlyMiddleware();
		$pload = $this->getPostData();
		$response = wp_remote_post( 'https://woocommerce.mailchimpapp.com/api/signup/', $pload );
		// need to return the error message if this is the problem.
		if ( $response instanceof WP_Error ) {
            mailchimp_error('admin', "create account error", ['error' => $response->get_error_message()]);
			wp_send_json_error( $response );
		}
        mailchimp_log('admin', "create account", ['response_code' => $response['response']['code']]);
		$response_body = json_decode( $response['body'] );
		if ( $response['response']['code'] == 200 && $response_body->success == true ) {
            $this->disconnect_store(false);
            // Same reason as the oauth-finish path: clear the lingering
            // 'mailchimp_disconnecting_store' transient (15s TTL) that
            // disconnect_store() just set, otherwise the post-reload form
            // POST will hit validate() within the TTL window and get back
            // [mailchimp_api_key => null], wiping the row we're about to
            // write.
            \Mailchimp_Woocommerce_DB_Helpers::delete_transient( 'mailchimp_disconnecting_store' );
            $result = json_decode( $response['body'], true);
            $options = [
                'mailchimp_auth_from' => 'create_account',
                'mailchimp_api_key' => $result['data']['oauth_token'].'-'.$result['data']['dc']
            ];
            // disconnect_store() above deletes the wp_options row; the helper's
            // update_option() does an existence check and routes missing rows
            // to add_option() so the new key gets inserted instead of silently
            // no-op'd by an UPDATE that matches nothing.
            // Bypass our own sanitize_option_mailchimp-woocommerce filter
            // (validate()) for this server-side write — see
            // $this->bypass_options_validation. Without it, validate()
            // would discard $options and write whatever getOptions()
            // returns, which is empty right after disconnect_store().
            $this->bypass_options_validation = true;
            $updated = \Mailchimp_Woocommerce_DB_Helpers::update_option( $this->plugin_name, $options, 'yes' );
            $this->bypass_options_validation = false;
            // Same lock as the oauth-finish path — the page reload POSTs the
            // settings form with a JS-supplied api_key field that can be
            // empty on a fresh connect. validate() consumes this flag and
            // pulls the api_key from the just-saved DB row instead.
            \Mailchimp_Woocommerce_DB_Helpers::set_transient( 'mailchimp_woocommerce_api_key_locked', true, 5 * MINUTE_IN_SECONDS );
            mailchimp_log('admin', "create account finished, updated options", ['updated' => $updated]);
            \Mailchimp_Woocommerce_DB_Helpers::update_option('mailchimp-woocommerce-waiting-for-login', 'waiting');
            // todo we need to make a profile or metadata call here and save this to the DB before triggering these events.
            Mailchimp_Woocommerce_Event::track('connect_accounts:create_account_complete', new DateTime());
            Mailchimp_Woocommerce_Event::track('account:verify_email', new DateTime());
            $response_body->redirect = admin_url('admin.php?page=mailchimp-woocommerce');

            do_action('mailchimp_woocommerce_connected_to_mailchimp', $options['mailchimp_api_key']);

            wp_send_json_success( $response_body );
        } elseif ( $response['response']['code'] == 404 ) {
            wp_send_json_error( array( 'success' => false ) );
        } else {
            $response_error = json_decode( $response_body->error, true );
            $username = preg_replace( '/[^A-Za-z0-9\-\@\.]/', '', $_POST['data']['username'] );
            $suggestion         = wp_remote_get( 'https://woocommerce.mailchimpapp.com/api/usernames/suggestions/' . $username );
            $suggested_username = json_decode( $suggestion['body'] )->data;

            wp_send_json_error(
                array(
                    'success'    => false,
                    'errors' => $response_error['errors'],
                    'suggested_username' => $suggested_username,
                    'response' => $response,
                    'response_body' => $response_body,
                )
            );
        }
	}


/** Function connect_account_flow_switch_account() called by wp_ajax hooks: {'mailchimp_woocommerce_switch_account'} **/
/** Parameters found in function connect_account_flow_switch_account(): {"post": ["data"]} **/
function connect_account_flow_switch_account()
    {
        if (
                current_user_can( mailchimp_get_allowed_capability() )
                && isset( $_POST['data']['_disconnect-nonce'] )
                && wp_verify_nonce( $_POST['data']['_disconnect-nonce'], '_disconnect-nonce-' . mailchimp_get_store_id() )
            ) {
            mailchimp_log('admin', "disconnect account flow about to switch account");
            $this->disconnect_store();
            \Mailchimp_Woocommerce_DB_Helpers::delete_option('mailchimp-woocommerce-waiting-for-login');

            return wp_send_json_success(array(
                'success' => true,
                'redirect' => admin_url('admin.php?page=mailchimp-woocommerce')
            ));
        } else {
            return wp_send_json_success(array('success' => false, 'message' => 'Failed to disconnect account'));
        }
    }


/** Function mailchimp_create_additional_runners() called by wp_ajax hooks: {'nopriv_mailchimp_create_additional_runners'} **/
/** Parameters found in function mailchimp_create_additional_runners(): {"post": ["mailchimp_actionscheduler_nonce", "instance"]} **/
function mailchimp_create_additional_runners() {
    if ( isset( $_POST['mailchimp_actionscheduler_nonce'] ) && isset( $_POST['instance'] ) && wp_verify_nonce( $_POST['mailchimp_actionscheduler_nonce'], 'mailchimp_additional_runner_' . $_POST['instance'] ) ) {
        ActionScheduler_QueueRunner::instance()->run();
    }
    wp_die();
}


/** Function mailchimp_woocommerce_ajax_oauth_status() called by wp_ajax hooks: {'mailchimp_woocommerce_oauth_status'} **/
/** Parameters found in function mailchimp_woocommerce_ajax_oauth_status(): {"post": ["url"]} **/
function mailchimp_woocommerce_ajax_oauth_status() {
		$this->adminOnlyMiddleware();

		$url = esc_url_raw( $_POST['url'] );
		// set the default headers to NOTHING because the oauth server will block
		// any non standard header that it was not expecting to receive and it was
		// preventing folks from being able to connect.
		$pload = array(
			'headers' => array(),
		);

		$response = wp_safe_remote_post( $url, $pload );

		// need to return the error message if this is the problem.
		if ( $response instanceof WP_Error ) {
			wp_send_json_error( $response );
		}

		if ( $response['response']['code'] == 200 && isset( $response['body'] ) ) {
			wp_send_json_success( json_decode( $response['body'] ) );
		} else {
			wp_send_json_error( $response );
		}
	}


/** Function mailchimp_woocommerce_ajax_oauth_finish() called by wp_ajax hooks: {'mailchimp_woocommerce_oauth_finish'} **/
/** Parameters found in function mailchimp_woocommerce_ajax_oauth_finish(): {"post": ["token"]} **/
function mailchimp_woocommerce_ajax_oauth_finish() {
        $this->adminOnlyMiddleware();
        $args = array(
            'domain' => site_url(),
            'secret' => \Mailchimp_Woocommerce_DB_Helpers::get_transient( 'mailchimp-woocommerce-oauth-secret' ),
            'token'  => sanitize_text_field( $_POST['token'] ),
        );
        $pload = array(
            'headers' => array(
                'Content-type' => 'application/json',
            ),
            'body'    => json_encode( $args ),
        );
        $response = wp_remote_post( 'https://woocommerce.mailchimpapp.com/api/finish', $pload );

        // need to return the error message if this is the problem.
        if ( $response instanceof WP_Error ) {
            mailchimp_error('admin', "finished oauth error", ['error' => $response->get_error_message()]);
            wp_send_json_error( $response );
        }

        mailchimp_log('admin', "finished oauth", ['response_code' => $response['response']['code']]);

        if ( $response['response']['code'] == 200 ) {

            // remove everything and start over.
            $this->disconnect_store(false);

            // disconnect_store() sets the 'mailchimp_disconnecting_store'
            // transient (15s TTL) to flag the form-based disconnect flow.
            // For this AJAX reconnect path the disconnect is already done
            // and we're about to write the new key — but the page reload
            // following this call POSTs the settings form well within 15s,
            // and validate() would see the lingering transient and return
            // [mailchimp_api_key => null], wiping the row we're about to
            // save. Clear it now so the next validate() pass sees the
            // reconnected state, not a stale disconnect-in-progress flag.
            \Mailchimp_Woocommerce_DB_Helpers::delete_transient( 'mailchimp_disconnecting_store' );

            \Mailchimp_Woocommerce_DB_Helpers::delete_transient( 'mailchimp-woocommerce-oauth-secret' );
            // save api_key? If yes, we can skip api key validation for validatePostApiKey();
            $result = json_decode( $response['body'], true);
            $options = [
                'mailchimp_auth_from' => 'oauth',
                'mailchimp_api_key' => $result['access_token'].'-'.$result['data_center']
            ];
            // disconnect_store() above deletes the wp_options row, so a raw
            // $wpdb->update() here would match 0 rows and silently no-op.
            // The DB helper's update_option() now does an existence check and
            // routes missing rows to add_option(), which gives us the upsert
            // we need without going behind WP's back. The bypass guard
            // around it suppresses our own validate() sanitize filter — see
            // $this->bypass_options_validation for why.
            $this->bypass_options_validation = true;
            $updated = \Mailchimp_Woocommerce_DB_Helpers::update_option( $this->plugin_name, $options, 'yes' );
            $this->bypass_options_validation = false;

            // Lock the API key against form-driven overrides for the next
            // validate() pass. The page reload after this AJAX call POSTs the
            // settings form, which still ships a mailchimp_api_key field via
            // JS (id: mailchimp-woocommerce-mailchimp-api-key). If that field
            // is empty/stale on a fresh connect, validatePostApiKey() would
            // overwrite the server-saved key. The flag tells validate() to
            // ignore the form value and trust what we just wrote here.
            \Mailchimp_Woocommerce_DB_Helpers::set_transient( 'mailchimp_woocommerce_api_key_locked', true, 5 * MINUTE_IN_SECONDS );

            mailchimp_log('admin', "oauth finished, updated options", ['updated' => $updated]);

            Mailchimp_Woocommerce_Event::track('connect_accounts_oauth:complete', new DateTime());

            do_action('mailchimp_woocommerce_connected_to_mailchimp', $options['mailchimp_api_key']);

            wp_send_json_success( $response );
        } else {
            mailchimp_error('admin', "finished oauth error", ['error' => $response->get_error_message()]);
            wp_send_json_error( $response );
        }

    }


