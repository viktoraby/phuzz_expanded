<?php
/***
*
*Found actions: 6
*Found functions:6
*Extracted functions:5
*Total parameter names extracted: 4
*Overview: {'eztoc_export_all_settings': {'eztoc_export_all_settings'}, 'eztoc_send_query_message': {'eztoc_send_query_message'}, 'eztoc_subscribe_for_newsletter': {'eztoc_subscribe_newsletter'}, 'eztoc_send_feedback': {'eztoc_send_feedback'}, 'eztoc_migrate_tocplus': {'eztoc_migrate_tocplus'}, 'ezTOC_Option': {'eztoc_reset_options_to_default'}}
*
***/

/** Function eztoc_export_all_settings() called by wp_ajax hooks: {'eztoc_export_all_settings'} **/
/** Parameters found in function eztoc_export_all_settings(): {"get": ["_wpnonce"]} **/
function eztoc_export_all_settings()
{
    if ( !current_user_can( 'manage_options' ) ) {
        die('-1');
    }
    if(!isset($_GET['_wpnonce'])){
        die('-1');
    }
    if( !wp_verify_nonce(  wp_unslash( $_GET['_wpnonce'] ) , '_wpnonce' ) ){  //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized --Nonce is validated
        die('-1');
    }

    $export_settings_data = get_option('ez-toc-settings');
    if(empty($export_settings_data)){
        $export_settings_data = array();
    }
    
    header('Content-type: application/json');
    header('Content-disposition: attachment; filename=ez_toc_settings_backup.json');
    echo wp_json_encode($export_settings_data);   
    wp_die();
}


/** Function eztoc_send_query_message() called by wp_ajax hooks: {'eztoc_send_query_message'} **/
/** Parameters found in function eztoc_send_query_message(): {"post": ["eztoc_security_nonce", "message", "email"]} **/
function eztoc_send_query_message(){   
		    
		        if ( ! isset( $_POST['eztoc_security_nonce'] ) ){
		           echo wp_json_encode(array('status'=>'f'));
				   return;   
		        }
		        if ( !wp_verify_nonce( wp_unslash( $_POST['eztoc_security_nonce'] ), 'eztoc_ajax_check_nonce' ) ){ //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		          echo wp_json_encode(array('status'=>'f'));
				  return;
		        }   
				if ( !current_user_can( 'manage_options' ) ) {
					echo wp_json_encode(array('status'=>'f'));
					return;   					
				}
		        $message        = isset($_POST['message']) ? $this->eztoc_sanitize_textarea_field(wp_unslash( $_POST['message'] )) : ''; //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		        $email          = isset($_POST['email']) ? sanitize_email(wp_unslash( $_POST['email'])) : '';
		                                
		        if(function_exists('wp_get_current_user')){

		            $user           = wp_get_current_user();

		         
		            $message = '<p>'.$message.'</p><br><br>'.'Query from Easy Table of Content plugin support tab';
		            
		            $user_data  = $user->data;        
		            $user_email = $user_data->user_email;     
		            
		            if($email){
		                $user_email = $email;
		            }            
		            //php mailer variables        
		            $sendto    = 'team@magazine3.in';
		            $subject   = "Easy Table of Content Query";
		            
		            $headers[] = 'Content-Type: text/html; charset=UTF-8';
		            $headers[] = 'From: '. sanitize_email($user_email);            
		            $headers[] = 'Reply-To: ' . sanitize_email($user_email);
		            // Load WP components, no themes.   

		            $sent = wp_mail($sendto, $subject, $message, $headers); 

		            if($sent){

		                 echo wp_json_encode(array('status'=>'t'));  

		            }else{

		                echo wp_json_encode(array('status'=>'f'));            

		            }
		            
		        }
		                        
		        wp_die();           
		}


/** Function eztoc_subscribe_for_newsletter() called by wp_ajax hooks: {'eztoc_subscribe_newsletter'} **/
/** Parameters found in function eztoc_subscribe_for_newsletter(): {"post": ["eztoc_security_nonce", "name", "email", "website"]} **/
function eztoc_subscribe_for_newsletter() {

		if ( ! isset( $_POST['eztoc_security_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['eztoc_security_nonce'] ) ), 'eztoc_ajax_check_nonce' ) ) {
			wp_send_json_error( esc_html__( 'Security check failed.', 'easy-table-of-contents' ) );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( 'Insufficient permissions.', 'easy-table-of-contents' ) );
		}

		$api_url = 'https://magazine3.company/wp-json/api/central/email/subscribe';

		$api_params = array(
			'name' 		=> isset($_POST['name'] ) ? sanitize_text_field(wp_unslash( $_POST['name'])): '',
			'email'		=> isset($_POST['email'] ) ? sanitize_email(wp_unslash($_POST['email'])) : '',
			'website'	=> isset($_POST['website']) ? sanitize_text_field(wp_unslash( $_POST['website'])):'',
			'type'		=> 'etoc'
		);

		$response = wp_remote_post( $api_url, array( 'timeout' => 15, 'sslverify' => true, 'body' => $api_params ) );
		$response = wp_remote_retrieve_body( $response );
		$response = json_decode( $response, true );
		echo wp_json_encode( array( 'response' => $response['response'] ) );

		wp_die();
	}


/** Function eztoc_send_feedback() called by wp_ajax hooks: {'eztoc_send_feedback'} **/
/** Parameters found in function eztoc_send_feedback(): {"post": ["data"]} **/
function eztoc_send_feedback() {
//phpcs:ignore WordPress.Security.NonceVerification.Missing -- Reason : Since form is serialised nonce is verified after parsing the recieved data.
    if( isset( $_POST['data'] ) ) {
        //phpcs:ignore WordPress.Security.NonceVerification.Missing -- Reason : Since form is serialised nonce is verified after parsing the recieved data.
        parse_str( wp_unslash( $_POST['data'] ), $form ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Data is sanitized below.
    }
    
    if( !isset( $form['eztoc_security_nonce'] ) || isset( $form['eztoc_security_nonce'] ) && !wp_verify_nonce( sanitize_text_field( $form['eztoc_security_nonce'] ), 'eztoc_ajax_check_nonce' ) ) {
        echo 'security_nonce_not_verified';
        wp_die();
    }
    if ( !current_user_can( 'manage_options' ) ) {
        wp_die();
    }
    
    $text = '';
    if( isset( $form['eztoc_disable_text'] ) && is_array($form['eztoc_disable_text']) ) {
        $text = implode( "\n\r", $form['eztoc_disable_text'] );
    }

    $headers = array();

    $from = isset( $form['eztoc_disable_from'] ) ? $form['eztoc_disable_from'] : '';
    if( $from ) {
        $headers[] = "From: " . sanitize_email( $from );
        $headers[] = "Reply-To: " . sanitize_email( $from );
    }

    $subject = isset( $form['eztoc_disable_reason'] ) ? $form['eztoc_disable_reason'] : '(no reason given)';

    if($subject == 'technical issue'){

          $subject  = 'Easy Table of Contents '.$subject;
          $text = trim($text);

          if(!empty($text)){

            $text = 'technical issue description: '.$text;

          }else{

            $text = 'no description: '.$text;
          }
      
    }

    wp_mail( 'team@magazine3.in', $subject, $text, $headers );
    
    echo 'sent';
    wp_die();

}


/** Function eztoc_migrate_tocplus() called by wp_ajax hooks: {'eztoc_migrate_tocplus'} **/
/** No params detected :-/ **/


/** Function ezTOC_Option() called by wp_ajax hooks: {'eztoc_reset_options_to_default'} **/
/** No function found :-/ **/


