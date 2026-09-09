<?php
/***
*
*Found actions: 3
*Found functions:3
*Extracted functions:3
*Total parameter names extracted: 3
*Overview: {'handleDisconnect': {'prli_stripe_connect_disconnect'}, 'handleUpdateCreds': {'prli_stripe_connect_update_creds'}, 'handleRefresh': {'prli_stripe_connect_refresh'}}
*
***/

/** Function handleDisconnect() called by wp_ajax hooks: {'prli_stripe_connect_disconnect'} **/
/** Parameters found in function handleDisconnect(): {"get": ["_wpnonce"]} **/
function handleDisconnect(): void
    {
        if (!isset($_GET['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash((string) $_GET['_wpnonce'])), 'stripe-disconnect')) {
            wp_die(esc_html__('Sorry, the disconnect failed.', 'pretty-link'));
        }
        if (!current_user_can(Page::capability())) {
            wp_die(esc_html__('Sorry, you don\'t have permission to do this.', 'pretty-link'));
        }

        if (!self::requestRemoteDisconnect()) {
            wp_die(esc_html__('Sorry, the disconnect failed.', 'pretty-link'));
        }

        update_option('prli_stripe_connect_status', 'disconnected');
        update_option('prli_stripe_status', 0);
        Fee::forgetAccountCountry();

        wp_safe_redirect(self::optionsReturnUrl('disconnected'));
        exit;
    }


/** Function handleUpdateCreds() called by wp_ajax hooks: {'prli_stripe_connect_update_creds'} **/
/** Parameters found in function handleUpdateCreds(): {"get": ["_wpnonce", "error", "pmt", "from"]} **/
function handleUpdateCreds(): void
    {
        if (!isset($_GET['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash((string) $_GET['_wpnonce'])), 'stripe-update-creds')) {
            wp_die(esc_html__('Sorry, updating your credentials failed. (security)', 'pretty-link'));
        }

        if (isset($_GET['error'])) {
            wp_die(esc_html(sanitize_text_field(wp_unslash((string) $_GET['error']))));
        }

        if (!isset($_GET['pmt'])) {
            wp_die(esc_html__('Sorry, updating your credentials failed. (pmt)', 'pretty-link'));
        }

        if (!current_user_can(Page::capability())) {
            wp_die(esc_html__('Sorry, you don\'t have permission to do this.', 'pretty-link'));
        }

        self::fetchAndStoreCredentials();

        $action = isset($_GET['stripe-action'])
            ? sanitize_text_field(wp_unslash((string) $_GET['stripe-action']))
            : 'updated';

        // Honor the `from` hint so flows that initiated the connect from
        // somewhere other than the Options page (e.g. the onboarding wizard)
        // land back where they started.
        $from = isset($_GET['from']) ? sanitize_key(wp_unslash((string) $_GET['from'])) : '';
        if ($from === 'onboarding') {
            // Onboarding always offers PrettyPay. If the site had previously
            // disabled it (from a prior install or a quick toggle-off),
            // connecting Stripe here flips the master switch back on so the
            // menu + admin-bar shortcut reappear once onboarding finishes.
            $options = (array) (get_option('prli_options', []) ?: []);
            if (empty($options['prettypay_enabled'])) {
                $options['prettypay_enabled'] = true;
                update_option('prli_options', $options);
            }
            wp_safe_redirect(admin_url('admin.php?page=pretty-link-onboarding&step=5'));
            exit;
        }

        wp_safe_redirect(self::optionsReturnUrl($action));
        exit;
    }


/** Function handleRefresh() called by wp_ajax hooks: {'prli_stripe_connect_refresh'} **/
/** Parameters found in function handleRefresh(): {"get": ["_wpnonce"]} **/
function handleRefresh(): void
    {
        if (!isset($_GET['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash((string) $_GET['_wpnonce'])), 'stripe-refresh')) {
            wp_die(esc_html__('Sorry, the refresh failed.', 'pretty-link'));
        }
        if (!current_user_can(Page::capability())) {
            wp_die(esc_html__('Sorry, you don\'t have permission to do this.', 'pretty-link'));
        }

        $methodId = Connect::METHOD_ID;
        $siteUuid = (string) get_option('prli_authenticator_site_uuid');
        $jwt      = Jwt::encode(['site_uuid' => $siteUuid]);

        $response = wp_remote_post(Connect::SERVICE_URL . "/api/refresh/{$methodId}", [
            'headers' => Jwt::header($jwt, Connect::SERVICE_DOMAIN),
        ]);

        $body = json_decode(wp_remote_retrieve_body($response), true);

        if (!is_array($body) || ($body['connect_status'] ?? '') !== 'refreshed') {
            wp_die(esc_html__('Sorry, the refresh failed.', 'pretty-link'));
        }

        self::persistCredentials($body);

        wp_safe_redirect(self::optionsReturnUrl('refreshed'));
        exit;
    }


