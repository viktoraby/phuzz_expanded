<?php
/***
*
*Found actions: 21
*Found functions:10
*Extracted functions:10
*Total parameter names extracted: 5
*Overview: {'notif_check': {'twb_notif_check'}, 'admin_ajax': {'twb'}, 'bwg_add_embed_ajax': {'nopriv_view_facebook_post', 'addEmbed', 'view_facebook_post'}, 'frontend_data': {'bwg_frontend_data', 'nopriv_bwg_frontend_data'}, 'bwg_filemanager_ajax': {'addImages', 'addMusic'}, 'frontend_ajax': {'Share', 'nopriv_Share', 'nopriv_download_gallery', 'GalleryBox', 'nopriv_GalleryBox', 'download_gallery'}, 'check_score': {'twb_check_score'}, 'bwg_upl': {'bwg_upl'}, 'dismiss_notice': {'bwg_recreate_dismissed', 'bwg_editor_missing_dismissed'}, 'bwg_captcha': {'bwg_captcha', 'nopriv_bwg_captcha'}}
*
***/

/** Function notif_check() called by wp_ajax hooks: {'twb_notif_check'} **/
/** Parameters found in function notif_check(): {"post": ["twb_nonce", "action"]} **/
function notif_check() {
    $twb_nonce = isset($_POST['twb_nonce']) ? sanitize_text_field($_POST['twb_nonce']) : '';
    if ( !wp_verify_nonce($twb_nonce, 'twb_nonce') ){
      die('Permission Denied.');
    }

    if ( !isset($_POST['action']) ) {
      return;
    }
    $page = sanitize_text_field($_POST['action']);
    $allowed_pages = array( 'twb_notif_check' );
    if ( !in_array($page, $allowed_pages) ) {
      return;
    }

    require_once('AdminBar.php');
    new TWBBWGAdminBar('', $this);
  }


/** Function admin_ajax() called by wp_ajax hooks: {'twb'} **/
/** Parameters found in function admin_ajax(): {"post": ["speed_ajax_nonce", "action"]} **/
function admin_ajax() {
    $speed_ajax_nonce = isset($_POST['speed_ajax_nonce']) ? sanitize_text_field($_POST['speed_ajax_nonce']) : '';
    if ( !wp_verify_nonce($speed_ajax_nonce, 'speed_ajax_nonce') ){
      die('Permission Denied.');
    }

    if ( !isset($_POST['action']) ) {
      return;
    }
    $page = sanitize_text_field($_POST['action']);
    $allowed_pages = array( 'twb' );

    if ( !in_array($page, $allowed_pages) ) {
      return;
    }
    $this->admin_page();
  }


/** Function bwg_add_embed_ajax() called by wp_ajax hooks: {'nopriv_view_facebook_post', 'addEmbed', 'view_facebook_post'} **/
/** No params detected :-/ **/


/** Function frontend_data() called by wp_ajax hooks: {'bwg_frontend_data', 'nopriv_bwg_frontend_data'} **/
/** No params detected :-/ **/


/** Function bwg_filemanager_ajax() called by wp_ajax hooks: {'addImages', 'addMusic'} **/
/** No params detected :-/ **/


/** Function frontend_ajax() called by wp_ajax hooks: {'Share', 'nopriv_Share', 'nopriv_download_gallery', 'GalleryBox', 'nopriv_GalleryBox', 'download_gallery'} **/
/** No params detected :-/ **/


/** Function check_score() called by wp_ajax hooks: {'twb_check_score'} **/
/** Parameters found in function check_score(): {"post": ["twb_nonce", "action", "post_id"]} **/
function check_score() {
    $twb_nonce = isset($_POST['twb_nonce']) ? sanitize_text_field($_POST['twb_nonce']) : '';
    if ( !wp_verify_nonce($twb_nonce, 'twb_nonce')
      || !function_exists('current_user_can')
      || !current_user_can('manage_options') ) {
      die('Permission Denied.');
    }

    if ( !isset($_POST['action']) ) {
      return;
    }
    $page = sanitize_text_field($_POST['action']);
    $allowed_pages = array( 'twb_check_score' );
    if ( !in_array($page, $allowed_pages) ) {
      return;
    }
    $post_id = isset($_POST["post_id"]) ? sanitize_text_field($_POST["post_id"]) : 0;

    echo TWBBWGLibrary::check_score($post_id);
    die();
  }


/** Function bwg_upl() called by wp_ajax hooks: {'bwg_upl'} **/
/** No params detected :-/ **/


/** Function dismiss_notice() called by wp_ajax hooks: {'bwg_recreate_dismissed', 'bwg_editor_missing_dismissed'} **/
/** Parameters found in function dismiss_notice(): {"post": ["nonce"]} **/
function dismiss_notice() {
    $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    if ( !wp_verify_nonce($nonce, 'ajax-nonce')
      || !function_exists('current_user_can')
      || !current_user_can('manage_options') ) {
      die('Permission Denied.');
    }
    $action = WDWLibrary::get('action');
    $allowed_pages = array(
      'bwg_editor_missing_dismissed',
      'bwg_recreate_dismissed',
    );
    if ( !empty($action) && in_array($action, $allowed_pages) ) {
      $action = str_replace(BWG()->prefix . '_', '', $action);
      update_option( 'bwg_wp_editor_state', $action );
    }
    die();
  }


/** Function bwg_captcha() called by wp_ajax hooks: {'bwg_captcha', 'nopriv_bwg_captcha'} **/
/** Parameters found in function bwg_captcha(): {"session": ["bwg_captcha_code"]} **/
function bwg_captcha() {
    if ( WDWLibrary::get('action') == 'bwg_captcha') {
      $i = WDWLibrary::get('i');
      $r2 = WDWLibrary::get('r2', 0, 'intval');
      $rrr = WDWLibrary::get('rrr', 0, 'intval');
      $randNum = 0 + $r2 + $rrr;
      $digit = WDWLibrary::get('digit', 0, 'intval');
      $cap_width = $digit * 10 + 15;
      $cap_height = 26;
      $cap_length_min = $digit;
      $cap_length_max = $digit;
      $cap_digital = 1;
      $cap_latin_char = 1;
      function code_generic($_length, $_digital = 1, $_latin_char = 1) {
        $dig = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
        $lat = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
        $main = array();
        if ($_digital) {
          $main = array_merge($main, $dig);
        }
        if ($_latin_char) {
          $main = array_merge($main, $lat);
        }
        shuffle($main);
        $pass = substr(implode('', $main), 0, $_length);
        return $pass;
      }
      $l = rand($cap_length_min, $cap_length_max);
      $code = code_generic($l, $cap_digital, $cap_latin_char);
      WDWLibrary::bwg_session_start();
      $_SESSION['bwg_captcha_code'] = $code;
      if (function_exists('imagecreatetruecolor')) {
        $canvas = imagecreatetruecolor( $cap_width, $cap_height );
        $c = imagecolorallocate( $canvas, rand( 150, 255 ), rand( 150, 255 ), rand( 150, 255 ) );
        imagefilledrectangle( $canvas, 0, 0, $cap_width, $cap_height, $c );
        $count = strlen( $code );
        $color_text = imagecolorallocate( $canvas, 0, 0, 0 );
        for ( $it = 0; $it < $count; $it++ ) {
          $letter = $code[ $it ];
          imagestring( $canvas, 6, (10 * $it + 10), $cap_height / 4, $letter, $color_text );
        }
        for ( $c = 0; $c < 150; $c++ ) {
          $x = rand( 0, $cap_width - 1 );
          $y = rand( 0, 29 );
          $col = imagecolorallocate($canvas, rand( 0, 255 ), rand( 0, 255 ), rand( 0, 255 ));
          imagesetpixel( $canvas, $x, $y, $col );
        }
        header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
        header( 'Cache-Control: no-store, no-cache, must-revalidate' );
        header( 'Cache-Control: post-check=0, pre-check=0', FALSE );
        header( 'Pragma: no-cache' );
        header( 'Content-Type: image/jpeg' );
        imagejpeg( $canvas, NULL, BWG()->options->jpeg_quality );
      }
      die('');
    }
  }


