<?php
/***
*
*Found actions: 17
*Found functions:15
*Extracted functions:14
*Total parameter names extracted: 12
*Overview: {'ajax_tnpc_regenerate_email': {'tnpc_regenerate_email'}, 'ajax_tnpc_preview': {'tnpc_preview'}, 'ajax_composer_ai_generate': {'newsletter_composer_ai_generate'}, 'ajax_tnpc_options': {'tnpc_options'}, 'ajax_tnpc_render': {'tnpc_render'}, 'ajax_get_all_presets': {'tnpc_get_all_presets'}, 'ajax_action': {'tnp', 'nopriv_tnp'}, 'newsletter-log': {'newsletter-log'}, 'ajax_tnpc_block_form': {'tnpc_block_form'}, 'ajax_tnpc_test_raw_html': {'tnpc_test_raw_html'}, 'tracking': {'nopriv_tnptr', 'tnptr'}, 'ajax_get_preset': {'tnpc_get_preset'}, 'ajax_tnpc_css': {'tnpc_css'}, 'ajax_tnpc_test': {'tnpc_test'}, 'ajax_ai_subjects': {'newsletter_ai_subjects'}}
*
***/

/** Function ajax_tnpc_regenerate_email() called by wp_ajax hooks: {'tnpc_regenerate_email'} **/
/** Parameters found in function ajax_tnpc_regenerate_email(): {"post": ["content", "composer"]} **/
function ajax_tnpc_regenerate_email() {

        if (!check_ajax_referer('save')) {
            wp_die('Invalid nonce', 403);
        }

        $content = stripslashes($_POST['content']);
        $content = urldecode(base64_decode($content));
        $composer = stripslashes_deep($_POST['composer']);

        $result = NewsletterComposer::instance()->regenerate_blocks($content, [], $composer);

        wp_send_json_success([
            'content' => $result['content'],
            'message' => __('Successfully updated', 'newsletter')
        ]);
    }


/** Function ajax_tnpc_preview() called by wp_ajax hooks: {'tnpc_preview'} **/
/** Parameters found in function ajax_tnpc_preview(): {"request": ["id"]} **/
function ajax_tnpc_preview() {
        $email = $this->get_email((int) $_REQUEST['id']);

        echo $email->message;

        die();
    }


/** Function ajax_composer_ai_generate() called by wp_ajax hooks: {'newsletter_composer_ai_generate'} **/
/** No params detected :-/ **/


/** Function ajax_tnpc_options() called by wp_ajax hooks: {'tnpc_options'} **/
/** Parameters found in function ajax_tnpc_options(): {"request": ["id", "options", "context_type"], "post": ["composer"]} **/
function ajax_tnpc_options() {
        global $wpdb;
        $block_id = sanitize_key($_REQUEST['id']);
        $block = NewsletterComposer::instance()->get_block($block_id);
        if (!$block) {
            die('Block not found with id ' . $block_id);
        }

        require_once NEWSLETTER_INCLUDES_DIR . '/controls.php';
        $encoded_options = wp_unslash($_REQUEST['options']);
        $options = NewsletterComposer::options_decode($encoded_options);

        $defaults_file = $block['dir'] . '/defaults.php';
        if (file_exists($defaults_file)) {
            include $defaults_file;
        }

        if (!isset($defaults) || !is_array($defaults)) {
            $defaults = [];
        }

        $options = array_merge($defaults, $options);

        $composer = wp_unslash($_POST['composer'] ?? []);

        if (empty($composer['width'])) {
            $composer['width'] = 600;
        }

        $context = ['type' => sanitize_key($_REQUEST['context_type'] ?? '')];

        // Used by the options.php script
        $controls = new NewsletterControls($options);
        $fields = new NewsletterFields($controls);

        $controls->init(['nested' => true]); // To avoid conflict with master form...
        echo '<input type="hidden" name="action" value="tnpc_render">';
        echo '<input type="hidden" name="id" value="' . esc_attr($block_id) . '">';
        echo '<input type="hidden" name="context_type" value="' . esc_attr($context['type']) . '">';
        wp_nonce_field('save');
        $inline_edits = '';
        if (isset($controls->data['inline_edits'])) {
            $inline_edits = $controls->data['inline_edits'];
        }
        echo '<input type="hidden" name="options[inline_edits]" value="', esc_attr(NewsletterComposer::options_encode($inline_edits)), '">';
        echo "<h3>", esc_html($block["name"]), "</h3>";
        include $block['dir'] . '/options.php';
        wp_die();
    }


/** Function ajax_tnpc_render() called by wp_ajax hooks: {'tnpc_render'} **/
/** Parameters found in function ajax_tnpc_render(): {"post": ["id", "full", "composer"], "request": ["context_type"]} **/
function ajax_tnpc_render() {
        if (!check_ajax_referer('save')) {
            wp_die('Invalid nonce', 403);
        }

        $block_id = sanitize_key($_POST['id']);
        $wrapper = isset($_POST['full']);
        $options = $this->restore_options_from_request();
        $composer = wp_unslash($_POST['composer'] ?? []);
        $context = ['type' => sanitize_key($_REQUEST['context_type'] ?? '')];
        NewsletterComposer::instance()->render_block($block_id, $wrapper, $options, $context, $composer);
        die();
    }


/** Function ajax_get_all_presets() called by wp_ajax hooks: {'tnpc_get_all_presets'} **/
/** No params detected :-/ **/


/** Function ajax_action() called by wp_ajax hooks: {'tnp', 'nopriv_tnp'} **/
/** Parameters found in function ajax_action(): {"request": ["na"]} **/
function ajax_action() {

        $this->action = sanitize_key($_REQUEST['na'] ?? '');
        $this->do_action();

        die();
    }


/** Function newsletter-log() called by wp_ajax hooks: {'newsletter-log'} **/
/** No function found :-/ **/


/** Function ajax_tnpc_block_form() called by wp_ajax hooks: {'tnpc_block_form'} **/
/** Parameters found in function ajax_tnpc_block_form(): {"request": ["id"]} **/
function ajax_tnpc_block_form() {
        $block_id = sanitize_key($_REQUEST['id']);
        $block = NewsletterComposer::instance()->get_block($block_id);
        if (!$block) {
            die('Block not found with id ' . $block_id);
        }
        $data = ['form' => $this->get_block_form($block), 'title' => $block['name']];
        header('Content-Type: application/json;charset=UTF-8');
        echo wp_json_encode($data);
        die();
    }


/** Function ajax_tnpc_test_raw_html() called by wp_ajax hooks: {'tnpc_test_raw_html'} **/
/** Parameters found in function ajax_tnpc_test_raw_html(): {"post": ["to_email"]} **/
function ajax_tnpc_test_raw_html() {
        check_admin_referer('save');
        if (!$this->is_allowed()) {
            wp_send_json_error('Not allowed', 403);
        }

            require_once NEWSLETTER_INCLUDES_DIR . '/controls.php';

        $controls = new NewsletterControls();

        $email = $this->get_email($controls->data['id']);
        $email->id = 0; // To unlink from the database object

        if ($this->is_html_allowed()) {
            $email->message = $controls->data['message'];
        } else {
            $email->message = wp_kses_post($controls->data['message']);
        }

        $email->subject = wp_strip_all_tags($controls->data['subject']);

        $email->track = $controls->data['track'] ?? (int) Newsletter::instance()->get_option('track');
        if (!empty($controls->data['sender_email'])) {
            $email->options['sender_email'] = $controls->data['sender_email'];
        }
        if (!empty($controls->data['sender_name'])) {
            $email->options['sender_name'] = $controls->data['sender_name'];
        }

        if (isset($_POST['to_email'])) {
            $message = NewsletterEmailsAdmin::instance()->send_test_newsletter_to_email_address($email, $controls->data['test_email']);
            echo $message;
        } else {
            NewsletterEmailsAdmin::instance()->send_test_email($email, $controls);
            if ($controls->messages) {
                echo $controls->messages;
            } else {
                echo $controls->errors;
            }
        }

        die();
    }


/** Function tracking() called by wp_ajax hooks: {'nopriv_tnptr', 'tnptr'} **/
/** Parameters found in function tracking(): {"get": ["nltr", "noti"]} **/
function tracking() {

        if (isset($_GET['nltr'])) {

            $this->logger->debug('Click tracking');
            $this->logger->debug('Code: ' . $_GET['nltr']);
            $this->logger->debug('Decoded: ' . base64_decode($_GET['nltr']));

            // Patch for links with ;
            $parts = explode(';', base64_decode($_GET['nltr']));
            $email_id = (int) array_shift($parts);
            $user_id = (int) array_shift($parts);
            $signature = array_pop($parts);
            $anchor = array_pop($parts); // No more used
            // The remaining elements are the url splitted when it contains ";"
            $url = implode(';', $parts);

            $this->logger->debug('Email: ' . $email_id . ', User: ' . $user_id . ', Signature: ' . $signature . ', Url: ' . $url);

            if (empty($url)) {
                $this->dienow('Invalid link', 'The tracking link contains invalid data (missing subscriber or original URL)', 404);
            }

            $host = parse_url($url, PHP_URL_HOST);
            $blog_host = parse_url(home_url(), PHP_URL_HOST);

            $verified = $signature == md5($email_id . ';' . $user_id . ';' . $url . ';' . $anchor . $this->get_key());

            // For matching hosts the redirect is safe even without the signature
            if ($host !== $blog_host) {
                // Protection against open-redirect
                if (!$verified) {
                    $this->dienow('Invalid link', 'The link signature (which grants a valid redirection and protects from open redirect attacks) is not valid.', 404);
                }
            }

            // Test emails, anyway the link was signed
            if (empty($email_id) || empty($user_id)) {
                header('Location: ' . esc_url_raw($url));
                die();
            }

            if ($user_id) {
                $user = $this->get_user($user_id);
                if (!$user) {
                    $this->dienow(__('Subscriber not found', 'newsletter'), 'This tracking link contains a reference to a subscriber no more present', 404);
                } else {
                    if ($verified) {
                        $this->set_user_cookie($user);
                    }
                }
            }

            $email = $this->get_email($email_id);
            if (!$email) {
                $this->dienow('Invalid newsletter', 'The link originates from a newsletter not found (it could have been deleted)', 404);
            }

            // Check for bots

            setcookie('tnpe', $email->id . '-' . $email->token, time() + 60 * 60 * 24 * 365, '/');

            $is_action = $this->is_action_url($url);

            $ip = $this->get_ip();

            if ($verified) {
                if (!$is_action) {
                    $url = apply_filters('newsletter_pre_save_url', $url, $email, $user);
                    $this->add_click($url, $user_id, $email_id, $ip);
                    $this->update_open_value(self::SENT_CLICK, $user_id, $email_id, $ip);
                } else {
                    // Track a Newsletter action as an email read and not a click
                    $this->update_open_value(self::SENT_READ, $user_id, $email_id, $ip);
                }
                $this->update_user_ip($user, $ip);
                $this->update_user_last_activity($user);
            }

            $this->reset_stats_time($email_id);

            header('Location: ' . apply_filters('newsletter_redirect_url', $url, $email, $user));
            die();
        }


        if (isset($_GET['noti'])) {

            $this->logger->debug('Open tracking');
            $this->logger->debug('Code: ' . $_GET['noti']);
            $this->logger->debug('Decoded: ' . base64_decode($_GET['noti']));

            list($email_id, $user_id, $signature) = explode(';', base64_decode($_GET['noti']), 3);

            $this->logger->debug('Email: ' . $email_id . ', User: ' . $user_id . ', Signature: ' . $signature);

            $email = $this->get_email($email_id);
            if (!$email) {
                $this->logger->error('Email not found, stop');
                die();
            }

            $user = $this->get_user($user_id);
            if (!$user) {
                $this->logger->debug('User not found, stop');
                return false;
            }

            $verified = false;

            // Old signature
            if ($email->token) {
                $verified = md5($email_id . $user_id . $email->token) === $signature;
            }

            $verified |= $this->check_signature($email_id . '/' . $user_id, $signature);

            if (!$verified) {
                $this->logger->error('Wrong signature, stop');
                die();
            }

            $this->register_open($email_id, $user_id);
            $this->send_tracking_image();
        }
    }


/** Function ajax_get_preset() called by wp_ajax hooks: {'tnpc_get_preset'} **/
/** Parameters found in function ajax_get_preset(): {"request": ["id"]} **/
function ajax_get_preset() {
        $id = sanitize_key($_REQUEST['id']);
        $email = null;

        // If it is an email id, get it from the database and fall back to the
        // static templates if not found (maybe a template folder has been created
        // just using a number...). I don't like this.
        if (is_numeric($id)) {
            $email = $this->get_email($id);
            if ($email) {
                NewsletterComposer::instance()->regenerate($email);
            }
        }

        if (!$email) {
            $email = NewsletterComposer::instance()->build_email_from_template($id);
        }

        if (is_wp_error($email)) {
            wp_send_json_error($email);
        }

        // Send back and keep only the blocks' HTML
        wp_send_json_success([
            'content' => NewsletterComposer::extract_body($email),
            'globalOptions' => NewsletterComposer::extract_composer_options($email),
            'subject' => $email->subject
        ]);
    }


/** Function ajax_tnpc_css() called by wp_ajax hooks: {'tnpc_css'} **/
/** No params detected :-/ **/


/** Function ajax_tnpc_test() called by wp_ajax hooks: {'tnpc_test'} **/
/** Parameters found in function ajax_tnpc_test(): {"post": ["to_email"]} **/
function ajax_tnpc_test() {
        check_admin_referer('save');
        if (!$this->is_allowed()) {
            wp_send_json_error('Not allowed', 403);
        }

        if (!class_exists('NewsletterControls')) {
            include NEWSLETTER_INCLUDES_DIR . '/controls.php';
        }

        $controls = new NewsletterControls();
        $email = new TNP_Email();
        NewsletterComposer::update_email($email, $controls);

        $email->id = (int) ($controls->data['email_id'] ?? 0);
        $email->track = $controls->data['track'] ?? (int) Newsletter::instance()->get_option('track');

        if (!empty($controls->data['sender_email'])) {
            $email->options['sender_email'] = $controls->data['sender_email'];
        }
        if (!empty($controls->data['sender_name'])) {
            $email->options['sender_name'] = $controls->data['sender_name'];
        }

        if (isset($_POST['to_email'])) {
            $message = NewsletterEmailsAdmin::instance()->send_test_newsletter_to_email_address($email, $controls->data['test_email']);
            echo $message;
        } else {
            NewsletterEmailsAdmin::instance()->send_test_email($email, $controls);
            if ($controls->messages) {
                echo $controls->messages;
            } else {
                echo $controls->errors;
            }
        }

        die();
    }


/** Function ajax_ai_subjects() called by wp_ajax hooks: {'newsletter_ai_subjects'} **/
/** No params detected :-/ **/


