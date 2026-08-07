<?php
/***
*
*Found actions: 5
*Found functions:5
*Extracted functions:5
*Total parameter names extracted: 4
*Overview: {'ajax_save_redirect': {'eps_redirect_save'}, 'ajax_get_inline_edit_entry': {'eps_redirect_get_inline_edit_entry'}, 'ajax_eps_delete_entry': {'eps_redirect_delete_entry'}, 'dismiss_pointer_ajax': {'eps_dismiss_pointer'}, 'ajax_get_entry': {'eps_redirect_get_new_entry'}}
*
***/

/** Function ajax_save_redirect() called by wp_ajax hooks: {'eps_redirect_save'} **/
/** Parameters found in function ajax_save_redirect(): {"post": ["id", "url_from", "url_to", "status"]} **/
function ajax_save_redirect()
    {

      check_ajax_referer('eps_301_save_redirect');

      if (!current_user_can(apply_filters('eps_301_redirects_capability', 'manage_options'))) {
        wp_die('You are not allowed to run this action.');
      }

      $update = array(
        'id'        => isset($_POST['id']) ? intval(wp_unslash($_POST['id'])) : false,
        'url_from'  => isset($_POST['url_from']) ? sanitize_text_field(urldecode(wp_unslash($_POST['url_from']))) : '', // remove the $root from the url if supplied, and a leading /
        'url_to'    => isset($_POST['url_to']) ? sanitize_text_field(urldecode(wp_unslash($_POST['url_to']))) : '',
        'type'      => (isset($_POST['url_to']) && is_numeric($_POST['url_to']) ? 'post' : 'url'),
        'status'    => isset($_POST['status']) ? sanitize_text_field(wp_unslash($_POST['status'])) : 'disabled'
      );

      $ids = self::_save_redirects(array($update));

      $updated_id = $ids[0]; // we expect only one returned id.

      // now get the new entry...
      $redirect = self::get_redirect($updated_id);
      $html = '';

      ob_start();
      $dfrom = urldecode($redirect->url_from);
      $dto   = urldecode($redirect->url_to);
      $i=0;
      include(EPS_REDIRECT_PATH . 'templates/template.redirect-entry.php');
      $html = ob_get_contents();
      ob_end_clean();
      echo json_encode(array(
        'html'          => $html,
        'redirect_id'   => $updated_id
      ));

      exit();
    }


/** Function ajax_get_inline_edit_entry() called by wp_ajax hooks: {'eps_redirect_get_inline_edit_entry'} **/
/** Parameters found in function ajax_get_inline_edit_entry(): {"request": ["redirect_id"]} **/
function ajax_get_inline_edit_entry()
{
  check_ajax_referer('eps_301_get_inline_edit_entry');

  if (!current_user_can(apply_filters('eps_301_redirects_capability', 'manage_options'))) {
    wp_die('You are not allowed to run this action.');
  }

  $redirect_id = isset($_REQUEST['redirect_id']) ? intval($_REQUEST['redirect_id']) : false;

  ob_start();
  self::get_inline_edit_entry($redirect_id);
  $html = ob_get_contents();
  ob_end_clean();
  echo json_encode(array(
    'html' => $html,
    'redirect_id' => $redirect_id
  ));
  exit();
}


/** Function ajax_eps_delete_entry() called by wp_ajax hooks: {'eps_redirect_delete_entry'} **/
/** Parameters found in function ajax_eps_delete_entry(): {"post": ["id"]} **/
function ajax_eps_delete_entry()
    {
      check_ajax_referer('eps_301_delete_entry');

      if (!current_user_can(apply_filters('eps_301_redirects_capability', 'manage_options'))) {
        wp_die('You are not allowed to run this action.');
      }

      if (!isset($_POST['id'])) exit();

      global $wpdb;
      $table_name = $wpdb->prefix . "redirects";
      //phpcs:ignore as we're using a custom table
      $results = $wpdb->delete($table_name, array('ID' => intval($_POST['id']))); //phpcs:ignore
      echo json_encode(array('id' => intval($_POST['id'])));
      exit();
    }


/** Function dismiss_pointer_ajax() called by wp_ajax hooks: {'eps_dismiss_pointer'} **/
/** Parameters found in function dismiss_pointer_ajax(): {"post": ["pointer_name"]} **/
function dismiss_pointer_ajax() {
    check_ajax_referer('eps_dismiss_pointer');

    if(!isset($_POST['pointer_name'])){
      wp_send_json_error();
    }

    $pointers = get_option('eps_pointers');
    $pointer = trim(sanitize_text_field(wp_unslash($_POST['pointer_name'])));

    if (empty($pointers) || empty($pointers[$pointer])) {
      wp_send_json_error();
    }

    unset($pointers[$pointer]);
    update_option('eps_pointers', $pointers);

    wp_send_json_success();
  }


/** Function ajax_get_entry() called by wp_ajax hooks: {'eps_redirect_get_new_entry'} **/
/** No params detected :-/ **/


