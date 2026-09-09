<?php
/***
*
*Found actions: 13
*Found functions:12
*Extracted functions:12
*Total parameter names extracted: 9
*Overview: {'mystickymenu_plugin_deactivate': {'mystickymenu_plugin_deactivate'}, 'stickymenu_status_update': {'stickymenu_status_update'}, 'stickymenu_contact_lead_form': {'stickymenu_contact_lead_form', 'nopriv_stickymenu_contact_lead_form'}, 'mystickymenu_delete_contact_lead': {'mystickymenu_delete_contact_lead'}, 'mystickymenu_review_box': {'mystickymenu_review_box'}, 'mystickymenu_widget_status': {'mystickymenu_widget_status'}, 'update_status': {'sticky_menu_update_status'}, 'mystickymenu_popup_status': {'mystickymenu_update_popup_status'}, 'my_sticky_menu_bulks': {'my_sticky_menu_bulks'}, 'mystickymenu_review_box_message': {'mystickymenu_review_box_message'}, 'mystickymenu_admin_send_message_to_owner': {'mystickymenu_admin_send_message_to_owner'}, 'stickymenu_widget_delete': {'stickymenu_widget_delete'}}
*
***/

/** Function mystickymenu_plugin_deactivate() called by wp_ajax hooks: {'mystickymenu_plugin_deactivate'} **/
/** No params detected :-/ **/


/** Function stickymenu_status_update() called by wp_ajax hooks: {'stickymenu_status_update'} **/
/** Parameters found in function stickymenu_status_update(): {"post": ["stickymenu_status"]} **/
function stickymenu_status_update(){
		if (!current_user_can('manage_options')) {
			wp_die(0);
		}
		check_ajax_referer( 'mystickymenu', 'wpnonce' );
		$mysticky_options = get_option( 'mysticky_option_name' );
		if( isset($_POST['stickymenu_status']) && $_POST['stickymenu_status'] != ''  ){
			
			$stickymenu_status = $_POST['stickymenu_status'];
			$mysticky_options['stickymenu_enable'] = $stickymenu_status;
			update_option('mysticky_option_name',$mysticky_options);
		}
		wp_die();
	}


/** Function stickymenu_contact_lead_form() called by wp_ajax hooks: {'stickymenu_contact_lead_form', 'nopriv_stickymenu_contact_lead_form'} **/
/** Parameters found in function stickymenu_contact_lead_form(): {"post": ["widget_id", "contact_name"]} **/
function stickymenu_contact_lead_form(){
		global $wpdb;
		global $wp;
		$stickymenus_widgets = get_option( 'mystickymenu-welcomebars' );
		$errors = array();
		$element_widget_no = $_POST['widget_id'];

		$element_widget_name = (isset($stickymenus_widgets[$element_widget_no]) && $stickymenus_widgets[$element_widget_no] != '' ) ? esc_html($stickymenus_widgets[$element_widget_no])  : '';

		$flag = true;
		if( isset($element_widget_name) && $element_widget_name != ''){
			if( !isset($_POST['contact_name']) || $_POST['contact_name'] == ''){
				$error = array(
					'key' => "contact-form-name",
					'message' => __( "This field is required", "mystickymenu" )
				);
				$errors[] = $error; 
				$flag = false;
			}else{
				$contact_lists_table = $wpdb->prefix . 'mystickymenu_contact_lists';
				$postArr = $_POST;	

				if( $element_widget_no == 0 ){
					$element_widget_no = '';
				}

                $allowed_keys = ['contact_name', 'contact_email', 'contact_phone', 'page_link'];
                $params = [];
                foreach ($allowed_keys as $key) {
                    if (isset($_POST[$key]) && $_POST[$key] !== '') {
                        $params[$key] = sanitize_text_field($_POST[$key]);
                    }
                }

                if(!empty($params)) {
                    $params["widget_id"] = esc_sql(sanitize_text_field($element_widget_no));
                    $params["widget_name"] = esc_sql(sanitize_text_field($element_widget_name));
                    $params["message_date"] = date('Y-m-d H:i:s');
                    $params["contact_email"] = (isset($params["contact_email"]) && $params["contact_email"] != '') ? sanitize_email($params["contact_email"]) : '';

                    $wpdb->insert($contact_lists_table, $params);
                    die;
                }
			}
		}

		if( $flag != true ){
			echo json_encode(array("status" => 0, "error" => 1, "errors" => $errors, "message" => $errors['message']));
		}
		die;
	}


/** Function mystickymenu_delete_contact_lead() called by wp_ajax hooks: {'mystickymenu_delete_contact_lead'} **/
/** Parameters found in function mystickymenu_delete_contact_lead(): {"post": ["ID", "all_leads"]} **/
function mystickymenu_delete_contact_lead(){
		global $wpdb;
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die(0); 
		}
		check_ajax_referer( 'mystickymenu', 'wpnonce' );
		
		 if ( isset($_POST['ID']) && $_POST['ID'] != '' ) {
			$ID = sanitize_text_field($_POST['ID']);
		 	$table = $wpdb->prefix . 'mystickymenu_contact_lists';
		 	$delete_sql = $wpdb->prepare("DELETE FROM {$table} WHERE id = %d",$ID);
		 	$delete = $wpdb->query($delete_sql);
		 }
		
		if ( isset($_POST['all_leads']) && $_POST['all_leads'] == 1 ) {
			$table = $wpdb->prefix . 'mystickymenu_contact_lists';
			$delete = $wpdb->query("TRUNCATE TABLE $table");
		}
		wp_die();	
		
	}


/** Function mystickymenu_review_box() called by wp_ajax hooks: {'mystickymenu_review_box'} **/
/** No params detected :-/ **/


/** Function mystickymenu_widget_status() called by wp_ajax hooks: {'mystickymenu_widget_status'} **/
/** Parameters found in function mystickymenu_widget_status(): {"post": ["widget_id", "widget_status"]} **/
function mystickymenu_widget_status() {
		if (!current_user_can('manage_options')) {
			wp_die(0);
		}
		check_ajax_referer( 'mystickymenu', 'wpnonce' );
		
		if ( isset($_POST['widget_id']) && $_POST['widget_id'] != '' && isset($_POST['widget_status']) && $_POST['widget_status'] != ''  ) {
			$welcomebars_widgets = get_option( 'mystickymenu-welcomebars' );
			$widget_id = $_POST['widget_id'];
			$welcomebars_widget_no = '-' . $widget_id ;
			
			if( $widget_id == 0 || $welcomebars_widgets[$widget_id] == 'default' ){
				$stickymenu_widget = get_option('mysticky_option_welcomebar');
				$welcomebars_widget_no = '';	
			}
			$widget_status = $_POST['widget_status'];
			$stickymenu_widget['mysticky_welcomebar_enable'] = $widget_status;
			
			update_option( 'mysticky_option_welcomebar',$stickymenu_widget);
		}
		wp_die();
	}


/** Function update_status() called by wp_ajax hooks: {'sticky_menu_update_status'} **/
/** Parameters found in function update_status(): {"request": ["nonce", "status", "email"]} **/
function update_status() {
        if(!empty($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], 'myStickymenu_update_nonce')) {
            $status = sanitize_text_field($_REQUEST['status']);
            $email = sanitize_text_field($_REQUEST['email']);
            if($status == 1) {
                update_option(self::$update_message_option, -1);
                $url = 'https://premioapps.com/premio/signup/email.php';
                $apiParams = [
                    'plugin' => 'myStickymenu',
                    'email'  => $email,
                ];

                // Signup Email for Chaty
                $apiResponse = wp_safe_remote_post($url, ['body' => $apiParams, 'timeout' => 15, 'sslverify' => true]);

                if (is_wp_error($apiResponse)) {
                    wp_safe_remote_post($url, ['body' => $apiParams, 'timeout' => 15, 'sslverify' => false]);
                }
            } else {
                $next_date = date('Y-m-d', strtotime('+7 days'));
                $next_signup_date = get_option(self::$next_signup_date);
                if($next_signup_date === false) {
                    add_option(self::$next_signup_date, $next_date);
                } else {
                    update_option(self::$update_message_option, -1);
                }
            }
        }
        echo "1";
        die;
    }


/** Function mystickymenu_popup_status() called by wp_ajax hooks: {'mystickymenu_update_popup_status'} **/
/** Parameters found in function mystickymenu_popup_status(): {"request": ["nonce"]} **/
function mystickymenu_popup_status() {
        if(!empty($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], 'mystickymenu_update_popup_status')) {
            update_option("mystickymenu_intro_box", "hide");
        }
        echo esc_attr("1");
        die;
    }


/** Function my_sticky_menu_bulks() called by wp_ajax hooks: {'my_sticky_menu_bulks'} **/
/** Parameters found in function my_sticky_menu_bulks(): {"post": ["wpnonce", "bulks"]} **/
function my_sticky_menu_bulks(){
		global $wpdb;
		if (!current_user_can('manage_options')) {
			wp_die(0);
		}
		check_ajax_referer( 'mystickymenu', 'wpnonce' );
		if( isset($_POST['wpnonce']) ){
			$bulks = isset($_POST['bulks']) ? $_POST['bulks'] : array();
			foreach( $bulks as $key => $bulk ){
				$ID = sanitize_text_field($bulk);
				$table = $wpdb->prefix . 'mystickymenu_contact_lists';
				$delete_sql = $wpdb->prepare("DELETE FROM {$table} WHERE id = %d",$ID);
				$delete = $wpdb->query($delete_sql);		
			}
		}
		wp_die();
	}


/** Function mystickymenu_review_box_message() called by wp_ajax hooks: {'mystickymenu_review_box_message'} **/
/** No params detected :-/ **/


/** Function mystickymenu_admin_send_message_to_owner() called by wp_ajax hooks: {'mystickymenu_admin_send_message_to_owner'} **/
/** Parameters found in function mystickymenu_admin_send_message_to_owner(): {"request": ["nonce"]} **/
function mystickymenu_admin_send_message_to_owner() {
		if (!current_user_can('manage_options')) {
			wp_die(0);
		}
		$response = array();
		$response['status'] = 0;
		$response['error'] = 0;
		$response['errors'] = array();
		$response['message'] = "";
		$errorArray = [];
		$errorMessage = __("%1\$s is required", "mystickymenu");
		$postData = $_POST;
		if(!isset($postData['textarea_text']) || trim($postData['textarea_text']) == "") {
			$error = array(
				"key"   => "textarea_text",
				"message" => __("Please enter your message","mystickymenu")
			);
			$errorArray[] = $error;
		}
		if(!isset($postData['user_email']) || trim($postData['user_email']) == "") {
			$error = array(
				"key"   => "user_email",
				"message" => sprintf($errorMessage,__("Email","mystickymenu"))
			);
			$errorArray[] = $error;
		} else if(!filter_var($postData['user_email'], FILTER_VALIDATE_EMAIL)) {
			$error = array(
				'key' => "user_email",
				"message" => "Email is not valid"
			);
			$errorArray[] = $error;
		}
		if(empty($errorArray)) {
			if(!isset($_REQUEST['nonce']) || empty($_REQUEST['nonce'])) {
				$error = array(
					'key' => "nonce",
					"message" => "Your request is not valid"
				);
				$errorArray[] = $error;
			} else if(!wp_verify_nonce($_REQUEST['nonce'], "mystickymenu_send_message_to_owner")) {
				$error = array(
					'key' => "nonce",
					"message" => "Your request is not valid"
				);
				$errorArray[] = $error;
			}
		}
		if(empty($errorArray)) {
			global $current_user;
			$text_message = $postData['textarea_text'];
			$email = $postData['user_email'];
			$domain = site_url();
			$user_name = $current_user->first_name." ".$current_user->last_name;

			$response['status'] = 1;

			/* sending message to Crisp */
			$post_message = array();

			$message_data = array();
			$message_data['key'] = "Plugin";
			$message_data['value'] = "My Sticky Bar";
			$post_message[] = $message_data;

			$message_data = array();
			$message_data['key'] = "Domain";
			$message_data['value'] = $domain;
			$post_message[] = $message_data;

			$message_data = array();
			$message_data['key'] = "Email";
			$message_data['value'] = $email;
			$post_message[] = $message_data;

			$message_data = array();
			$message_data['key'] = "Message";
			$message_data['value'] = $text_message;
			$post_message[] = $message_data;

			$api_params = array(
				'domain' => $domain,
				'email' => $email,
				'url' => site_url(),
				'name' => $user_name,
				'message' => $post_message,
				'plugin' => "My Sticky Bar",
				'type' => "Need Help",
			);

			/* Sending message to Crisp API */

			$crisp_response = wp_safe_remote_post("https://premioapps.com/premio/send-message-api.php", array('body' => $api_params, 'timeout' => 15, 'sslverify' => true));

			if (is_wp_error($crisp_response)) {
				wp_safe_remote_post("https://premioapps.com/premio/send-message-api.php", array('body' => $api_params, 'timeout' => 15, 'sslverify' => false));
			}
		} else {
			$response['error'] = 1;
			$response['errors'] = $errorArray;
		}
		wp_send_json($response);
		wp_die();
	}


/** Function stickymenu_widget_delete() called by wp_ajax hooks: {'stickymenu_widget_delete'} **/
/** Parameters found in function stickymenu_widget_delete(): {"post": ["widget_id", "widget_delete"]} **/
function stickymenu_widget_delete(){
		if (!current_user_can('manage_options')) {
			wp_die(0);
		}
		check_ajax_referer( 'mystickymenu', 'wpnonce' );
		if ( isset($_POST['widget_id']) && $_POST['widget_id'] != '' && isset($_POST['widget_delete']) && $_POST['widget_delete'] == 1  ) {
			$welcomebars_widgets = get_option( 'mystickymenu-welcomebars' );
			$widget_id = $_POST['widget_id'];			
			foreach( $welcomebars_widgets as $key => $widget_value ){
				$element_widget_no = '';
				if ( $key != 0 ) {
					$element_widget_no = '-' . $key;
				}
				delete_option( 'mysticky_option_welcomebar' . $element_widget_no );					
			}
			
			delete_option( 'mystickymenu-welcomebars' );
		}
		wp_die(); 
	}


