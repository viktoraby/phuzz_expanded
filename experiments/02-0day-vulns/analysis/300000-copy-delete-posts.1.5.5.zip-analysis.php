<?php
/***
*
*Found actions: 10
*Found functions:9
*Extracted functions:6
*Total parameter names extracted: 4
*Overview: {'activate_plugins': {'analyst_notification_dismiss'}, 'handle_installation': {'inisev_installation', 'inisev_installation_widget'}, 'handle_review_action': {'inisev_review'}, 'nonce': {'tifm_save_decision'}, 'noticeAjax': {'tifm_notice_actions'}, 'HTTP_X_REQUESTED_WITH': {'cdp_action_handling'}, 'activate_bmi': {'activate_bmi'}, 'dismiss_banner': {'dismiss_new_bb_banner'}, 'install_bmi': {'install_bmi'}}
*
***/

/** Function activate_plugins() called by wp_ajax hooks: {'analyst_notification_dismiss'} **/
/** No function found :-/ **/


/** Function handle_installation() called by wp_ajax hooks: {'inisev_installation', 'inisev_installation_widget'} **/
/** Parameters found in function handle_installation(): {"post": ["slug"]} **/
function handle_installation() {

          if (check_ajax_referer('inisev_carousel', 'nonce', false) === false) {
            return wp_send_json_error();
          }

          if (!current_user_can('install_plugins')) {
            return wp_send_json_error();
          }

          // Handle the slug and install the plugin
          $slug = sanitize_text_field($_POST['slug']);
          if ($slug === 'usm') {

            $this->install($this->usm_slug, 'ultimate-social-media-icons');

          } elseif ($slug === 'bmi') {

            $this->install($this->bmi_slug, 'backup-backup');

          } elseif ($slug === 'cdp') {

            $this->install($this->cdp_slug, 'copy-delete-posts');

          } elseif ($slug === 'mpu') {

            $this->install($this->mpu_slug, 'pop-up-pop-up');

          } elseif ($slug == 'redi') {

            $this->install($this->redi_slug, 'redirect-redirection');

            // Anything else error
          } else wp_send_json_error();

        }


/** Function handle_review_action() called by wp_ajax hooks: {'inisev_review'} **/
/** Parameters found in function handle_review_action(): {"post": ["slug", "mode"]} **/
function handle_review_action() {

          if (check_ajax_referer('inisev_review_dismiss', 'nonce', false) === false) {
            return wp_send_json_error();
          }

          $slug = sanitize_text_field($_POST['slug']);
          $mode = sanitize_text_field($_POST['mode']);

          if (!empty($_POST['slug']) && isset($mode) && in_array($mode, ['dismiss', 'remind'])) {
            $option_name = $this->option_name;
            $data = get_option($option_name, false);
            if ($data != false) {

              $uid = get_current_user_id();

              if (!array_key_exists('users', $data)) $data['users'] = [];
              if (!array_key_exists($uid, $data['users'])) $data['users'][$uid] = [];
              if (!array_key_exists($slug, $data['users'][$uid])) $data['users'][$uid][$slug] = [];

              $data['users'][$uid]['delay_between'] = strtotime($this->time_between);

              if ($mode == 'remind') {
                $data['users'][$uid][$slug]['remind'] = strtotime($this->remind_time);
              }

              if ($mode == 'dismiss') {
                $data['users'][$uid][$slug]['dismiss'] = true;
              }

              update_option($option_name, $data);

              wp_send_json_success();

            } else wp_send_json_error();
          } else wp_send_json_error();

        }


/** Function nonce() called by wp_ajax hooks: {'tifm_save_decision'} **/
/** No function found :-/ **/


/** Function noticeAjax() called by wp_ajax hooks: {'tifm_notice_actions'} **/
/** Parameters found in function noticeAjax(): {"post": ["nonce", "method"]} **/
function noticeAjax() {

          // Nonce verification
          if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'tifm_notice_nonce')) {
            wp_send_json_error();
            return;
          }

          $method = '';
          if (isset($_POST['method'])) {

            $method = sanitize_text_field($_POST['method']);

          }

          if ($method == 'dismiss_notice') {

            update_option('_tifm_hide_notice_forever', true);
            wp_send_json_success();
            exit;

          } else if ($method == 'dismiss_notice_and_enable') {
            
            update_option('_tifm_feature_enabled', 'enabled');
            update_option('_tifm_hide_notice_forever', true);
            delete_option('_tifm_disable_feature_forever');
            wp_send_json_success();
            exit;
            
          } else if ($method == 'dismiss_notice_and_disable') {

            update_option('_tifm_hide_notice_forever', true);
            update_option('_tifm_disable_feature_forever', true);
            update_option('_tifm_feature_enabled', 'disabled');
            wp_send_json_success();
            exit;

          } else {

            wp_send_json_error();
            exit;

          }

        }


/** Function HTTP_X_REQUESTED_WITH() called by wp_ajax hooks: {'cdp_action_handling'} **/
/** No function found :-/ **/


/** Function activate_bmi() called by wp_ajax hooks: {'activate_bmi'} **/
/** No params detected :-/ **/


/** Function dismiss_banner() called by wp_ajax hooks: {'dismiss_new_bb_banner'} **/
/** Parameters found in function dismiss_banner(): {"post": ["shouldRedirectToBMI"]} **/
function dismiss_banner()
        {
          if (check_ajax_referer('new_bb_banner_dismiss', 'nonce', false) === false) {
            wp_send_json_error();
          }

          $shouldRedirectToBMI = isset($_POST['shouldRedirectToBMI']) ? sanitize_text_field($_POST['shouldRedirectToBMI']) : false;

          update_option($this->option_name, [
            'dismissed' => true,
            'dismissed_at' => time(),
            'using_since' => $this->using_since
          ]);

          if ($shouldRedirectToBMI == 'true') {
            wp_send_json_success([
              'redirect' => $this->plugin_menu_url
            ]);
          } else {
            wp_send_json_success();
          }
        }


/** Function install_bmi() called by wp_ajax hooks: {'install_bmi'} **/
/** No params detected :-/ **/


