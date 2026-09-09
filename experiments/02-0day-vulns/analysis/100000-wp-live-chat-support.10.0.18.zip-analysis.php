<?php
/***
*
*Found actions: 1
*Found functions:1
*Extracted functions:1
*Total parameter names extracted: 1
*Overview: {'check_update': {'check_update'}}
*
***/

/** Function check_update() called by wp_ajax hooks: {'check_update'} **/
/** Parameters found in function check_update(): {"request": ["value"]} **/
function check_update() {
    $activated=get_option('wplc_activated');
    if (isset($_REQUEST['value']) && intval($_REQUEST['value'])==1) {
      if ($activated==0) {
        update_option('wplc_activated',1);
      }
    }
    echo ($activated==2) ? '1' : '0';
    wp_die();
  }


