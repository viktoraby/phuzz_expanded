<?php
/***
*
*Found actions: 21
*Found functions:21
*Extracted functions:19
*Total parameter names extracted: 19
*Overview: {'ck_email_export_filter_popup': {'ck_email_export_filter_popup'}, 'ck_mail_subscribe_for_newsletter': {'ck_mail_subscribe_newsletter'}, 'ck_mail_export_logs': {'ck_mail_export_logs'}, 'email_tracker_details': {'check-email-error-tracker-detail'}, 'ce_send_query_message': {'ce_send_query_message'}, 'activate_plugin': {'oneclick_smtp_activate'}, 'view_log_message': {'check-email-log-list-view-message'}, 'ck_mail_import_plugin_data': {'check_mail_import_plugin_data'}, 'check_email_remove_outlook': {'check_email_remove_outlook'}, 'install_plugin': {'oneclick_smtp_install'}, 'submit_resend_message': {'check_mail_resend_submit'}, 'ck_mail_check_email_analyze': {'check_email_analyze'}, 'ajax': {'epsilon_check-email_review'}, 'ck_mail_check_dns': {'check_dns'}, 'check_email_get_email_analytics_data': {'get_email_analytics'}, 'ck_mail_subscribe_to_news_letter': {'ck_mail_subscribe_to_news_letter'}, 'ck_mail_update_network_settings': {'update_network_settings'}, 'ck_mail_save_wizard_data': {'check_mail_save_wizard_data'}, 'view_resend_message': {'check-email-log-list-view-resend-message'}, 'ck_mail_send_feedback': {'ck_mail_send_feedback'}, 'checkmail_save_admin_fcm_token': {'checkmail_save_admin_fcm_token'}}
*
***/

/** Function ck_email_export_filter_popup() called by wp_ajax hooks: {'ck_email_export_filter_popup'} **/
/** Parameters found in function ck_email_export_filter_popup(): {"get": ["ck_mail_security_nonce"]} **/
function ck_email_export_filter_popup(){
		if ( ! current_user_can( 'manage_check_email' ) ) {
			wp_die( -1 );
		}

		if ( ! isset( $_GET['ck_mail_security_nonce'] ) ){
            wp_die( '-1' ); 
        }

        if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['ck_mail_security_nonce'] ) ), 'ck_mail_ajax_check_nonce' ) ){
           wp_die( '-1' );  
        }
		?>

		<div id="ck-mail-elog-options">
			<form id="ck-mail-export-form" type="GET" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
				<div class="ck-mail-exp-row">
					<div class="ck-mail-exp-col">
						<div id="ck-mail-export-type">
							<div class="ck-mail-logs-heading-wrapper">
								<h2 class="ck-mail-export-h2"> <?php esc_html_e('File Format', 'check-email'); ?> </h2>
							</div>

							<div class="ck-mail-logs-contents">
								<div class="ck-mail-log-exp-type ck-mail-export-options">
									<label for="ck-mail-export-csv"> 
										<input type="radio" name="export_type" class="ck-mail-export-type" id="ck-mail-export-csv" value="csv" checked>
										<?php esc_html_e('Export in CSV (.csv)', 'check-email'); ?>
									</label>
								</div>

								<div class="ck-mail-log-exp-type ck-mail-export-options">
									<label for="ck-mail-export-xls"> 
										<input type="radio" name="export_type" class="ck-mail-export-type" id="ck-mail-export-xls" value="xls">
										<?php esc_html_e('Export in Microsoft Excel (.xls)', 'check-email'); ?>
									</label>
								</div>

								<div class="ck-mail-log-exp-type ck-mail-export-options">
									<label for="ck-mail-export-xlsx ck-mail-export-options"> 
										<input type="radio" name="export_type" class="ck-mail-export-type" id="ck-mail-export-xlsx" value="xlsx">
										<?php esc_html_e('Export in Microsoft Excel (.xlsx)', 'check-email'); ?>
									</label>
								</div>

								<div class="ck-mail-log-exp-type ck-mail-export-options">
									<label for="ck-mail-export-txt"> 
										<input type="radio" name="export_type" class="ck-mail-export-type" id="ck-mail-export-txt" value="txt">
										<?php esc_html_e('Export in Text (.txt)', 'check-email'); ?>
									</label>
								</div>

							</div>
						</div>
					</div>

					<div class="ck-mail-exp-col">
						<div id="ck-mail-export-common-info">
							<div class="ck-mail-logs-heading-wrapper">
								<h2 class="ck-mail-export-h2"> <?php esc_html_e('Fields', 'check-email'); ?> </h2>
								<p class="ck-mail-exp-error ck-mail-d-none" id="ck-mail-fields-error" style="color: red;"></p>
							</div>

							<div class="ck-mail-logs-contents">
								<div class="ck-mail-log-exp-comm-info ck-mail-export-options">
									<label for="ck-mail-comm-info-from"> 
										<input type="checkbox" name="common_information[From]" class="ck-mail-comm-info-chk" id="ck-mail-comm-info-from" value="From" checked>
										<?php esc_html_e('From', 'check-email'); ?>
									</label>
								</div>

								<div class="ck-mail-log-exp-comm-info ck-mail-export-options">
									<label for="ck-mail-comm-info-to"> 
										<input type="checkbox" name="common_information[To]" class="ck-mail-comm-info-chk" id="ck-mail-comm-info-to" value="To" checked>
										<?php esc_html_e('To', 'check-email'); ?>
									</label>
								</div>

								<div class="ck-mail-log-exp-comm-info ck-mail-export-options">
									<label for="ck-mail-comm-info-subject"> 
										<input type="checkbox" name="common_information[Subject]" class="ck-mail-comm-info-chk" id="ck-mail-comm-info-subject" value="Subject" checked>
										<?php esc_html_e('Subject', 'check-email'); ?>
									</label>
								</div>

								<div class="ck-mail-log-exp-comm-info ck-mail-export-options">
									<label for="ck-mail-comm-info-msg"> 
										<input type="checkbox" name="common_information[Message]" class="ck-mail-comm-info-chk" id="ck-mail-comm-info-msg" value="Message" checked>
										<?php esc_html_e('Message', 'check-email'); ?>
									</label>
								</div>

								<div class="ck-mail-log-exp-comm-info ck-mail-export-options">
									<label for="ck-mail-comm-info-date"> 
										<input type="checkbox" name="common_information[Sent-At]" class="ck-mail-comm-info-chk" id="ck-mail-comm-info-date" value="Sent At" checked>
										<?php esc_html_e('Sent At', 'check-email'); ?>
									</label>
								</div>

								<div class="ck-mail-log-exp-comm-info ck-mail-export-options">
									<label for="ck-mail-comm-info-status"> 
										<input type="checkbox" name="common_information[Status]" class="ck-mail-comm-info-chk" id="ck-mail-comm-info-status" value="Status" checked>
										<?php esc_html_e('Status', 'check-email'); ?>
									</label>
								</div>
							</div>
						</div>
					</div>
				</div> <!-- ck-mail-exp-row div end -->

				<div class="ck-mail-exp-row">
					<div class="ck-mail-exp-col">
						<div id="ck-mail-export-by-status">
							<div class="ck-mail-logs-heading-wrapper">
								<h2> <?php esc_html_e('Status', 'check-email'); ?> </h2>
							</div>

							<div class="ck-mail-logs-contents">
								<div class="ck-mail-log-exp-status ck-mail-export-options">
									<label for="ck-mail-exp-status-all"> 
										<input type="radio" name="export_status" class="ck-mail-exp-status-radio" id="ck-mail-exp-status-all" value="All" checked>
										<?php esc_html_e('All', 'check-email'); ?>
									</label>
								</div>

								<div class="ck-mail-log-exp-status ck-mail-export-options">
									<label for="ck-mail-exp-status-success"> 
										<input type="radio" name="export_status" class="ck-mail-exp-status-radio" id="ck-mail-exp-status-success" value="Success">
										<?php esc_html_e('Success', 'check-email'); ?>
									</label>
								</div>

								<div class="ck-mail-log-exp-status ck-mail-export-options">
									<label for="ck-mail-exp-status-fail"> 
										<input type="radio" name="export_status" class="ck-mail-exp-status-radio" id="ck-mail-exp-status-fail" value="Fail">
										<?php esc_html_e('Failure', 'check-email'); ?>
									</label>
								</div>

							</div>
						</div>
					</div>

					<div class="ck-mail-exp-col">
						<div id="ck-mail-export-by-rec">
							<div class="ck-mail-logs-heading-wrapper">
								<h2> <?php esc_html_e('Recipient', 'check-email'); ?> </h2>
							</div>

							<div class="ck-mail-logs-contents">
								<div class="ck-mail-log-exp-recipient ck-mail-export-options">
									<label for="ck-mail-export-recipient"> <?php esc_html_e('Enter Email id', 'check-email'); ?> </label>
									<input type="text" name="export_recipient" class="ck-mail-export-recipient" id="ck-mail-export-recipient" placeholder="<?php esc_attr_e( 'Enter Recipient Email', 'check-email' ); ?>">
								</div>
							</div>
						</div>
					</div>
				</div> <!-- ck-mail-exp-row div end -->

				<div style="clear: both;"></div>

				<div class="ck-mail-exp-row">
					<div class="ck-mail-exp-col">
						<div id="ck-mail-export-by-date">
							<div class="ck-mail-logs-heading-wrapper">
								<h2> <?php esc_html_e('Date Range', 'check-email'); ?> </h2>
							</div>

							<div class="ck-mail-logs-contents">
								<div class="ck-mail-log-exp-date ck-mail-export-options">
									<label for="ck-mail-exp-date-all"> 
										<input type="radio" name="export_date" class="ck-mail-exp-date-radio" id="ck-mail-exp-date-all" value="all" checked>
										<?php esc_html_e('All', 'check-email'); ?>
									</label>
								</div>

								<div class="ck-mail-log-exp-date ck-mail-export-options">
									<label for="ck-mail-exp-date-custom"> 
										<input type="radio" name="export_date" class="ck-mail-exp-date-radio" id="ck-mail-exp-date-custom" value="custom">
										<?php esc_html_e('Custom', 'check-email'); ?>
									</label>
									<p class="ck-mail-exp-error ck-mail-d-none" id="ck-mail-exp-date-error"></p>
									<div id="ck-mail-exp-c-date-wrapper" class="ck-mail-d-none">
										<input type="search" id="ck-mail-exp-from-date" name="ck_mail_exp_from_date" value="<?php echo esc_attr(gmdate('Y-m-d')); ?>" placeholder="<?php esc_attr_e( 'From Date', 'check-email' ); ?>" readonly />
										<input type="search" id="ck-mail-exp-to-date" name="ck_mail_exp_to_date" value="<?php echo esc_attr(gmdate('Y-m-d')); ?>" placeholder="<?php esc_attr_e( 'To Date', 'check-email' ); ?>" readonly />
									</div>
								</div>
							</div>

						</div>
					</div>
				</div> <!-- ck-mail-exp-row div end -->
				<div style="clear: both;"></div>
				<input type="hidden" name="ck_mail_export_nonce" value="<?php echo esc_attr(wp_create_nonce('ck_mail_ajax_check_nonce'));    ?>">
				<input type="hidden" name="action" value="ck_mail_export_logs">
				<button type="button" class="button-primary button" id="ck-mail-export-logs-btn"> <?php esc_html_e('Export Logs', 'check-email'); ?> </button>
			</form>
		</div>

		<?php

		wp_die();	
	}


/** Function ck_mail_subscribe_for_newsletter() called by wp_ajax hooks: {'ck_mail_subscribe_newsletter'} **/
/** Parameters found in function ck_mail_subscribe_for_newsletter(): {"post": ["ck_mail_security_nonce", "name", "email", "website"]} **/
function ck_mail_subscribe_for_newsletter() {
    if ( ! isset( $_POST['ck_mail_security_nonce'] ) ){
        echo esc_html__('security_nonce_not_verified', 'check-email');
        die();
    }
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ck_mail_security_nonce'] ) ), 'ck_mail_ajax_check_nonce' ) ) {
        echo esc_html__('security_nonce_not_verified', 'check-email');
        die();
    }
    if ( !current_user_can( 'manage_options' ) ) {
        die();
    }
    if (isset( $_POST['name'] ) && isset( $_POST['email'] ) && isset( $_POST['website'] )) {
        $api_url = 'http://magazine3.company/wp-json/api/central/email/subscribe';
    
        $api_params = array(
            'name' => sanitize_text_field(wp_unslash($_POST['name'])),
            'email'=> sanitize_email(wp_unslash($_POST['email'])),
            'website'=> sanitize_text_field(wp_unslash($_POST['website'])),
            'type'=> 'checkmail'
        );
        wp_remote_post( $api_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
    }
    wp_die();
}


/** Function ck_mail_export_logs() called by wp_ajax hooks: {'ck_mail_export_logs'} **/
/** Parameters found in function ck_mail_export_logs(): {"get": ["ck_mail_export_nonce", "export_type", "common_information", "export_status", "export_date", "ck_mail_exp_from_date", "ck_mail_exp_to_date", "export_recipient"]} **/
function ck_mail_export_logs(){

		if(!isset($_GET['ck_mail_export_nonce'])){
	    	wp_die( -1 );
	    }

	    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['ck_mail_export_nonce'] ) ), 'ck_mail_ajax_check_nonce' ) ){
       		wp_die( -1 );  
    	}

		if ( ! current_user_can( 'manage_check_email' ) ) {
			wp_die( -1 );
		}

		$file_format = 'csv';
		$file_name = 'email_logs.csv';

		if(isset($_GET['export_type']) && !empty($_GET['export_type'])){
			$file_format = sanitize_text_field( wp_unslash( $_GET['export_type'] ) );
		}

		switch($file_format){
			case 'csv':
				$this->separator = ',';
				$file_name = 'email_logs.csv';
				header("Content-type: application/csv");
			break;

			case 'xls':
				$this->separator = "\t";
				$file_name = 'email_logs.xls';
				header('Content-Type: application/vnd.ms-excel');
			break;

			case 'xlsx':
				$this->separator = "\t";
				$file_name = 'email_logs.xlsx';
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			break;

			case 'txt':
				$this->separator = "\t";
				$file_name = 'email_logs.txt';
				header('Content-Type: text/plain');
			break;

			default:
				$this->separator = ',';
				$file_name = 'email_logs.csv';
				header('Content-type: application/csv');
			break;
		}

		header("Content-disposition: attachment; filename=\"$file_name\"");

		$fields = array();
		if(isset($_GET['common_information']) && is_array($_GET['common_information'])){
			$fields = array_map('sanitize_text_field', wp_unslash($_GET['common_information']));
		}

		$status = 'All';
		if(isset($_GET['export_status']) && !empty($_GET['export_status'])){
			$status = sanitize_text_field( wp_unslash( $_GET['export_status'] ) );
		}

		$export_date = 'all';
		if(isset($_GET['export_date']) && !empty($_GET['export_date'])){
			$export_date = sanitize_text_field( wp_unslash( $_GET['export_date'] ) );
		}

		$from_date = gmdate('Y-m-d 00:00:00');
		$to_date = gmdate('Y-m-d 23:59:59');

		if($export_date == 'custom'){
			if(isset($_GET['ck_mail_exp_from_date']) && !empty($_GET['ck_mail_exp_from_date'])){
				$from_date = gmdate('Y-m-d 00:00:00', strtotime(sanitize_text_field( wp_unslash( $_GET['ck_mail_exp_from_date'] ) ) ) );	
			}
			if(isset($_GET['ck_mail_exp_to_date']) && !empty($_GET['ck_mail_exp_to_date'])){
				$to_date = gmdate('Y-m-d 23:59:59', strtotime(sanitize_text_field( wp_unslash( $_GET['ck_mail_exp_to_date'] ) ) ) );	
			}
		}

		$export_recipient = '';
		if(isset($_GET['export_recipient']) && !empty($_GET['export_recipient'])){
			$export_recipient = sanitize_text_field( wp_unslash( $_GET['export_recipient'] ) );
		}


		if(!empty($fields)){
			$logs = $this->ck_mail_generate_csv($fields, $status, $export_date, $from_date, $to_date, $export_recipient, $file_format);
			echo esc_html($logs);
		}

	   	wp_die();
	}


/** Function email_tracker_details() called by wp_ajax hooks: {'check-email-error-tracker-detail'} **/
/** Parameters found in function email_tracker_details(): {"get": ["tracker_id"]} **/
function email_tracker_details() {
		if ( ! current_user_can( 'manage_check_email' ) ) {
			wp_die();
		}
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reason: We are not processing form information but only loading it inside the admin_init hook.
		$id = isset( $_GET['tracker_id'] ) ? absint( $_GET['tracker_id'] ) : 0 ;

		if ( $id <= 0 ) {
			wp_die();
		}
		
		$log_items = $this->get_table_manager()->fetch_error_tracker_items_by_id( array( $id ) );
		if ( count( $log_items ) > 0 ) {
			$log_item = $log_items[0];
			$main_logs = $this->get_table_manager()->fetch_log_items_by_id( array( $log_item['check_email_log_id'] ) )[0];

			$headers = array();
			
			$option = get_option( 'check-email-log-core' );
			

			

			?>
			<table style="width: 100%;" id="email_log_table">
				<tr style="background: #eee;">
					<td style="padding: 5px;width: 20%;"><b><?php esc_html_e( 'Date', 'check-email' ); ?></b>:</td>
					<td style="padding: 5px;"><?php echo esc_html( $log_item['created_at'] ); ?></td>
				</tr>
				<tr style="background: #eee;">
					<td style="padding: 5px;"><b><?php esc_html_e( 'Content', 'check-email' ); ?></b>:</td>
					<td style="padding: 5px;"><?php echo esc_html( $log_item['content'] ); ?></td>
				</tr>
				<tr style="background: #eee;">
					<td style="padding: 5px;"><b><?php esc_html_e( 'Initiator', 'check-email' ); ?></b>:</td>
					<td style="padding: 5px;"><?php echo esc_html( $log_item['initiator'] ); ?></td>
				</tr>
				<tr style="background: #eee;">
					<td style="padding: 5px;"><b><?php esc_html_e( 'Error', 'check-email' ); ?></b>:</td>
					<td style="padding: 5px;"><?php echo esc_html( $main_logs['error_message'] ); ?></td>
				</tr>
				<?php 
				// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
				do_action( 'check_email_view_log_after_headers', $log_item ); 
				?>

			</table>

			<div id="view-message-footer" class="check_mail_non-printable">
				<a href="#" class="button action" id="thickbox-footer-close"><?php esc_html_e( 'Close', 'check-email' ); ?></a>
			</div>
			<?php
		}

		wp_die(); // this is required to return a proper result.
	}


/** Function ce_send_query_message() called by wp_ajax hooks: {'ce_send_query_message'} **/
/** Parameters found in function ce_send_query_message(): {"post": ["message", "email"]} **/
function ce_send_query_message()
	{
		check_ajax_referer( 'support-localization', 'security' );

		if ( ! current_user_can( 'manage_check_email' ) ) {
			wp_die( -1 );
		}
		
		if(isset($_POST['message']) && isset($_POST['email'])){
			$message        = sanitize_textarea_field(wp_unslash($_POST['message'])); 
		    $email          = sanitize_email(wp_unslash($_POST['email']));   
		                            
		    if(function_exists('wp_get_current_user')){

		        $user           = wp_get_current_user();

		        $message = '<p>'.esc_html($message).'</p><br><br>'.'Query from Check Email plugin support tab';
		        
		        $user_data  = $user->data;        
		        $user_email = $user_data->user_email;     
		        
		        if($email){
		            $user_email = $email;
		        }            
		        //php mailer variables        
		        $sendto    = 'team@magazine3.in';
		        $subject   = "Check Email Query";
		        
		        $headers[] = 'Content-Type: text/html; charset=UTF-8';
		        $headers[] = 'From: '. esc_attr($user_email);            
		        $headers[] = 'Reply-To: ' . esc_attr($user_email);
		        // Load WP components, no themes.   

		        $sent = wp_mail($sendto, $subject, $message, $headers); 

		        if($sent){

		             echo wp_json_encode(array('status'=>'t'));  

		        }else{

		            echo wp_json_encode(array('status'=>'f'));            

		        }
		        
		    }
		}
	                    
	    wp_die(); 
	}


/** Function activate_plugin() called by wp_ajax hooks: {'oneclick_smtp_activate'} **/
/** No function found :-/ **/


/** Function view_log_message() called by wp_ajax hooks: {'check-email-log-list-view-message'} **/
/** Parameters found in function view_log_message(): {"get": ["log_id"]} **/
function view_log_message() {
		if ( ! current_user_can( 'manage_check_email' ) ) {
			wp_die();
		}

		check_ajax_referer( 'check_email_log_nonce', 'security' );
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reason: We are not processing form information but only loading it inside the admin_init hook.
		$id = isset( $_GET['log_id'] ) ? absint( $_GET['log_id'] ) : 0 ;

		if ( $id <= 0 ) {
			wp_die();
		}

		$log_items = $this->get_table_manager()->fetch_log_items_by_id( array( $id ) );
		if ( count( $log_items ) > 0 ) {
			$log_item = $log_items[0];

			$headers = array();
			if ( ! empty( $log_item['headers'] ) ) {
				$parser  = new \CheckEmail\Util\Check_Email_Header_Parser();
				$headers = $parser->parse_headers( $log_item['headers'] );
			}
			$option = get_option( 'check-email-log-core' );
			$default_format_for_message = (isset( $option['default_format_for_message'])) ?  $option['default_format_for_message'] : '';

			$active_tab = 0;

			switch ($default_format_for_message) {
				case 'raw':
					$active_tab = 0;
					break;
				case 'html':
					$active_tab = 1;
					break;
				case 'json':
					$active_tab = 2;
					break;
				
				default:
				$active_tab = 0;
					break;
			}

			if(isset( $option['log_email_content']) && !$option['log_email_content']){
				$active_tab = 0;
			}

			?>
			<table style="width: 100%;" id="email_log_table">
				<tr style="background: #eee;">
					<td style="padding: 5px;"><b><?php esc_html_e( 'Sent at', 'check-email' ); ?></b>:</td>
					<td style="padding: 5px;"><?php echo esc_html( $log_item['sent_date'] ); ?></td>
				</tr>
				<tr style="background: #eee;">
					<td style="padding: 5px;"><b><?php esc_html_e( 'To', 'check-email' ); ?></b>:</td>
					<td style="padding: 5px;"><?php echo esc_html( $log_item['to_email'] ); ?></td>
				</tr>
				<tr style="background: #eee;">
					<td style="padding: 5px;"><b><?php esc_html_e( 'Subject', 'check-email' ); ?></b>:</td>
					<td style="padding: 5px;"><?php echo esc_html( $log_item['subject'] ); ?></td>
				</tr>
                <tr style="background: #eee;">
					<td style="padding: 5px;"><b><?php esc_html_e( 'From', 'check-email' ); ?></b>:</td>
					<td style="padding: 5px;"><?php echo ( isset($headers['from'] ) ) ? esc_html( $headers['from'] ) : ""; ?></td>
				</tr>
				<?php
					if(empty($option) || !isset( $option['reply_to']) || (isset( $option['reply_to'])) && $option['reply_to']){
				?>
				<tr style="background: #eee;">
					<td style="padding: 5px;"><b><?php esc_html_e( 'Reply To', 'check-email' ); ?></b>:</td>
					<td style="padding: 5px;"><?php echo ( isset($headers['reply_to'] ) ) ? esc_html( $headers['reply_to'] ) : ""; ?></td>
				</tr>
				<?php
					}
					if(empty($option) || !isset( $option['cc']) || (isset( $option['cc'])) && $option['cc']){
				?>
				<tr style="background: #eee;">
					<td style="padding: 5px;"><b><?php esc_html_e( 'Cc', 'check-email' ); ?></b>:</td>
					<td style="padding: 5px;"><?php echo ( isset($headers['cc'] ) ) ? esc_html( $headers['cc'] ) : ""; ?></td>
				</tr>
				<?php
					}
					if(empty($option) || !isset( $option['bcc']) || (isset( $option['bcc'])) && $option['bcc']){
				?>
				<tr style="background: #eee;">
					<td style="padding: 5px;"><b><?php esc_html_e( 'Bcc', 'check-email' ); ?></b>:</td>
					<td style="padding: 5px;"><?php echo ( isset($headers['bcc'] ) ) ? esc_html( $headers['bcc'] ) : ""; ?></td>
				</tr>
				<?php
					}
					if(empty($option) || !isset( $option['display_host_ip']) || (isset( $option['display_host_ip'])) && $option['display_host_ip']){
				?>
				<tr style="background: #eee;">
					<td style="padding: 5px;"><b><?php esc_html_e( 'Host IP', 'check-email' ); ?></b>:</td>
					<td style="padding: 5px;"><?php echo esc_html( $log_item['ip_address'] ); ?></td>
				</tr>
				<?php
					}
					?>
				<tr style="background: #eee;">
					<td style="padding: 5px;"><b><?php esc_html_e( 'Headers', 'check-email' ); ?></b>:</td>
					<td style="padding: 5px;"><?php echo esc_html( $log_item['headers'] ); ?></td>
				</tr>
				<?php if(isset( $option['email_open_tracking']) && $option['email_open_tracking'] ) {  ?>
				<tr style="background: #eee;">
					<td style="padding: 5px;"><b><?php esc_html_e( 'Email Opened', 'check-email' ); ?></b>:</td>
					<td style="padding: 5px;"><?php echo ($log_item['open_count']) ?  esc_html( $log_item['open_count'] ) : 0; ?></td>
				</tr>
				<?php } ?>
				<?php 
				// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
				do_action( 'check_email_view_log_after_headers', $log_item ); ?>

			</table>

			<div id="tabs">
				<ul data-active-tab="<?php echo absint( $active_tab ); ?>" class="check_mail_non-printable">
					<?php
					if(empty($option) || !isset( $option['log_email_content']) || (isset( $option['log_email_content'])) && $option['log_email_content']){
					?>
					<li><a href="#tabs-text" onclick='hidePrint();'><?php esc_html_e( 'Raw Email Content', 'check-email' ); ?></a></li>
					
					<li><a href="#tabs-preview" onclick='showPrint();'><?php esc_html_e( 'Preview Content as HTML', 'check-email' ); ?></a></li>

					<?php
					}
					?>
					<li><a href="#tabs-json" onclick='hidePrint();'><?php esc_html_e( 'Json', 'check-email' ); ?></a></li>
					<li><a href="#tabs-trigger-data" onclick='hidePrint();'><?php esc_html_e( 'Triggered Form', 'check-email' ); ?></a></li>
				</ul>
				<?php
					if(empty($option) || !isset( $option['log_email_content']) || (isset( $option['log_email_content'])) && $option['log_email_content']){
					?>
				<div id="tabs-text">
					<?php
						// Regular expression to match and remove <img> tags with class="check-email-tracking"
						$email_content_without_img = preg_replace('/<img[^>]*class=[\'\"]check-email-tracking[\'\"][^>]*>/i', '', $log_item['message']);
					?>
					<pre class="tabs-text-pre"><?php echo esc_textarea( $email_content_without_img ); ?></pre>
				</div>
				<div id="tabs-preview">
					<?php echo wp_kses( $email_content_without_img, $this->check_email_kses_allowed_html( 'post' ) ); ?>
					<?php
					if (!empty($log_item['attachment_name'])) {
						$attachments = explode(',',$log_item['attachment_name']);
						if ($attachments) {
							?>
							<h4><?php esc_html_e( 'Attachments', 'check-email'); ?> </h4>
							<?php
							foreach ($attachments as $key => $attachment) {
								echo wp_get_attachment_image($attachment, 'thumbnail', false, [
											'class' => 'custom-class',
											'style' => 'height: 100px; width: 100px;',
										]);
							}
						}
					}
					?>
				</div>
				<?php
				}
				?>
				<div id="tabs-json">
					<?php
						$json_data = $log_item;
						$json_data['mail_id'] = $json_data['id'];
						unset($json_data['id']);
						if(isset( $option['log_email_content']) && !$option['log_email_content']){
							unset($json_data['message']);
						}else{
							$json_data['message'] = htmlentities( htmlspecialchars_decode( $json_data['message'] ) );
						}
					?>
					<pre class="tabs-text-pre"><?php echo esc_html( wp_json_encode($json_data,JSON_PRETTY_PRINT)); ?></pre>
				</div>

								
				<div id="tabs-trigger-data">
					<?php 
					if(!defined('CK_MAIL_PRO_VERSION')){
					?>
						<p><?php esc_html_e( 'Triggered data helps you in debugging by showing the exact code that is sending that email ', 'check-email' ); ?><a href="https://check-email.tech/docs/knowledge-base/how-to-use-the-trigger-option-to-debug-emails-by-identifying-the-exact-code/" target="_blank"><?php esc_html_e(' Learn More', 'check-email'); ?></a></p>
						<p id="check-email-trigger-data-free-note"> <?php esc_html_e( 'This Feature requires the Premium Version', 'check-email' ); ?> <a href="https://check-email.tech/pricing/#pricings" target="_blank" class="check-mail-premium-btn"><span><?php esc_html_e('Upgrade Now', 'check-email'); ?><span></a> </p>
					<?php
					}else{
						// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
						do_action('check_email_pro_log_tabs_content', $id);
					}
					?>
				</div>
			</div>

			<div id="view-message-footer" class="check_mail_non-printable">
				<a href="#" class="button action" id="thickbox-footer-close"><?php esc_html_e( 'Close', 'check-email' ); ?></a>
				<bitton type="button" class="button button-primary" id="check_mail_print_button" style="margin-top: 10px; display:none;" onclick='printLog();'><?php esc_html_e( 'Print', 'check-email' ); ?></a>
			</div>
			<?php
		}

		wp_die(); // this is required to return a proper result.
	}


/** Function ck_mail_import_plugin_data() called by wp_ajax hooks: {'check_mail_import_plugin_data'} **/
/** Parameters found in function ck_mail_import_plugin_data(): {"post": ["ck_mail_security_nonce", "plugin_name"]} **/
function ck_mail_import_plugin_data(){                  
    
        if ( ! current_user_can( 'manage_check_email' ) ) {
			return;
        }
        
        if ( ! isset( $_POST['ck_mail_security_nonce'] ) ){
			echo wp_json_encode(array('status'=> 503, 'message'=> esc_html__( 'Unauthorized access, CSRF token not matched','check-email'))); 
			wp_die();
		}
		if ( !wp_verify_nonce( sanitize_text_field(wp_unslash( $_POST['ck_mail_security_nonce'] ) ), 'ck_mail_ajax_check_nonce' ) ){
			echo wp_json_encode(array('status'=> 503, 'message'=> esc_html__( 'Unauthorized access, CSRF token not matched','check-email')));
			wp_die();
		}
		// phpcs:ignore Squiz.PHP.DiscouragedFunctions.Discouraged
		set_time_limit(300);
        
        $plugin_name   = isset($_POST['plugin_name'])?sanitize_text_field(wp_unslash($_POST['plugin_name'])):'';          
        $is_plugin_active = false;
        
        switch ($plugin_name) {
            
            case 'email_log':
                if ( is_plugin_active('email-log/email-log.php')) {
					$plugin_table_name = 'email_log';
					$is_plugin_active =  true;
                }                
                break;
            case 'mail_logging_wp_mail_catcher':
                if ( is_plugin_active('wp-mail-catcher/WpMailCatcher.php')) {
					$plugin_table_name = 'mail_catcher_logs';
                    $is_plugin_active =  true;      
                }                
                break;
            case 'wp_mail_logging':
                if ( is_plugin_active('wp-mail-logging/wp-mail-logging.php')) {
					$plugin_table_name = 'wpml_mails';
					$is_plugin_active =  true;
                }                
                break;
            case 'wp_mail_log':
                if ( is_plugin_active('wp-mail-log/wp-mail-log.php')) {
					$plugin_table_name = 'wml_entries';
					$is_plugin_active =  true;
                }                
                break;
            default:
                break;
        }                             
        if($is_plugin_active){
			$result = $this->ck_mail_import_email_log_plugin_data($plugin_table_name,$plugin_name);
			echo wp_json_encode($result);
        }else{
            echo wp_json_encode(array('status'=>503, 'message'=>esc_html__( "Plugin data is not available or it is not activated",'check-email'))); 
        }        
        wp_die();           
	}


/** Function check_email_remove_outlook() called by wp_ajax hooks: {'check_email_remove_outlook'} **/
/** Parameters found in function check_email_remove_outlook(): {"post": ["ck_mail_security_nonce"]} **/
function check_email_remove_outlook() {
		
		if(!isset($_POST['ck_mail_security_nonce'])){
			return;
		}

		if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ck_mail_security_nonce'] ) ), 'ck_mail_security_nonce' ) ){
			return;  
		}
		

		if ( ! current_user_can( 'manage_check_email' ) ) {
			return;
		}
		$auth = new Auth('outlook');
		$auth->delete_outlook_options();
		echo wp_json_encode(array('status'=> 200));
		wp_die();
	}


/** Function install_plugin() called by wp_ajax hooks: {'oneclick_smtp_install'} **/
/** No function found :-/ **/


/** Function submit_resend_message() called by wp_ajax hooks: {'check_mail_resend_submit'} **/
/** Parameters found in function submit_resend_message(): {"post": ["ck_mail_security_nonce", "ckm_to", "ckm_from", "ckm_cc", "ckm_bcc", "ckm_content_type", "ckm_reply_to", "ckm_subject", "ckm_message"]} **/
function submit_resend_message() {
		if ( ! current_user_can( 'manage_check_email' ) ) {
			echo wp_json_encode(array('status'=> 501, 'message'=> esc_html__( 'Unauthorized access, permission not allowed','check-email')));
			wp_die();
		}
		if ( ! isset( $_POST['ck_mail_security_nonce'] ) ){
			echo wp_json_encode(array('status'=> 503, 'message'=> esc_html__( 'Unauthorized access, CSRF token not matched','check-email'))); 
			wp_die();
		}
		if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ck_mail_security_nonce'] ) ), 'ck_mail_ajax_check_nonce' ) ){
			echo wp_json_encode(array('status'=> 503, 'message'=> esc_html__( 'Unauthorized access, CSRF token not matched','check-email')));
			wp_die();
		}
		$to = ( isset($_POST['ckm_to'] ) ) ? sanitize_text_field(wp_unslash($_POST['ckm_to'])) : "";
		$from = ( isset($_POST['ckm_from'] ) ) ? sanitize_text_field(wp_unslash($_POST['ckm_from'])) : "";
		$cc = ( isset($_POST['ckm_cc'] ) ) ? sanitize_text_field(wp_unslash($_POST['ckm_cc'])) : "";
		$bcc = ( isset($_POST['ckm_bcc'] ) ) ? sanitize_text_field(wp_unslash($_POST['ckm_bcc'])) : "";
		$content_type = ( isset($_POST['ckm_content_type'] ) ) ? sanitize_text_field(wp_unslash($_POST['ckm_content_type'])) : "";
		$reply_to = ( isset($_POST['ckm_reply_to'] ) ) ? sanitize_text_field(wp_unslash($_POST['ckm_reply_to'])) : "";

		$subject = ( isset($_POST['ckm_subject'] ) ) ? sanitize_text_field(wp_unslash($_POST['ckm_subject'])) : "";
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$message = ( isset($_POST['ckm_message'] ) ) ? wp_unslash($_POST['ckm_message']) : "";
		$headers = array(
		);
		
		if ( !empty( $from ) ){
			$headers[] ='From: '.$from;
		}
		if ( !empty( $reply_to ) ){
			$headers[] ='Reply-To: '.$reply_to;
		}
		if ( !empty( $cc ) ){
			$headers[] ='CC: '.$cc;
		}
		if ( !empty( $bcc ) ){
			$headers[] ='BCC: '.$bcc;
		}
		if ( !empty( $content_type ) ){
			$headers[] ='Content-Type: '.$content_type;
		}
		if ( empty( $to )  || empty( $subject )){
			echo wp_json_encode(array('status'=> 503, 'message'=> esc_html__( 'Please fill all required fields','check-email')));
			wp_die();
		}
		$emailErr = false;
		if ( !empty( $to )){
			$to_exp = explode(',',$to);
			if (is_array($to_exp)) {
				foreach ($to_exp as $key => $to_email) {
					if (!filter_var($to_email, FILTER_VALIDATE_EMAIL)) {
						$emailErr = true;
					}
				}
			}else{
				if (!filter_var($to_exp, FILTER_VALIDATE_EMAIL)) {
					$emailErr = true;
				}
			}
		}

		if ( $emailErr){
			echo wp_json_encode(array('status'=> 503, 'message'=> esc_html__( 'Invalid email address in to','check-email')));
			wp_die();
		}

		
		
		

		wp_mail( $to, $subject, $message, $headers, $attachments=array() );

		echo wp_json_encode(array('status'=> 200, 'message'=> esc_html__('Email Sent.','check-email')));
			die;
	}


/** Function ck_mail_check_email_analyze() called by wp_ajax hooks: {'check_email_analyze'} **/
/** Parameters found in function ck_mail_check_email_analyze(): {"post": ["ck_mail_security_nonce"], "server": ["SERVER_ADDR"]} **/
function ck_mail_check_email_analyze() {
    // Check nonce
    if (isset($_POST['ck_mail_security_nonce'])) {
        if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ck_mail_security_nonce'] ) ), 'ck_mail_security_nonce' ) ){
            die( '-1' );
        }
        if ( ! current_user_can( 'manage_check_email' ) ) {
            wp_send_json_error(esc_html__('Unauthorized user', 'check-email') );
            return;
        }
        // $api_url = 'http://127.0.0.1:8000/custom-api/email-analyze';
        $api_url = 'https://spamanalyser.check-email.tech/custom-api/email-analyze';
        $current_user = wp_get_current_user();
        $email = $current_user->user_email;
        if ( !empty( $email ) ) {
            $to = 'plugintest@check-email.tech';
            $title = esc_html__("Test email to analyze check email", "check-email");
            $body  = esc_html__('This test email will analyze score', "check-email");
            $site_name = get_bloginfo('name');
            $headers = [
                'Content-Type: text/html; charset=UTF-8',
                'From: '.$site_name .'<'.$email.'>',
                'Reply-To: '.$email
            ];
            wp_mail($to, $title, $body, $headers);
        }
        $api_params = array(
            'email' => $email,
        );

        if (function_exists('ck_mail_create_spam_analyzer_table') ) {
			ck_mail_create_spam_analyzer_table();
		}

        $response = wp_remote_post( $api_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

        if ( ! is_wp_error( $response ) ) {
            $response = wp_remote_retrieve_body( $response );
            $response = json_decode( $response, true );
            if (isset($response['is_error']) && $response['is_error'] == 1) {
                $result = $response;
            }else{
                $result['is_error'] = 0;
                $result['data'] = $response;
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated , WordPress.Security.ValidatedSanitizedInput.MissingUnslash , WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                $ip_address = $_SERVER['SERVER_ADDR']; // Replace with your target IP
                $blocklist = check_email_is_ip_blocked($ip_address);
                $result['blocklist'] = $blocklist;
                $result['ip_address'] = $ip_address;
                $spam_final_score = 0;
                $block_final_score = 0;
                $auth_final_score = 0;
                $link_final_score = 0;
                if ( isset( $response['spamcheck_result'] )) {
                    $spam_score = $response['spamcheck_result']['score'];
                    if ($spam_score > 0) {
                        $spam_final_score = 2.5;
                    } else if ($spam_score < 0 && $spam_score > -5) {
                        $spam_final_score = 1.5;
                    } else if ($spam_score < -5) {
                        $spam_final_score = 0;
                    }
                }
                $block_count = 0;
                foreach ($blocklist as $key => $value) {
                    if($value['status']){
                        $block_count +=1;
                    }
                }
                if ($block_count == 0) {
                    $block_final_score = 2.5;
                } else if ($block_count > 0 && $block_count <= 12) {
                    $block_final_score = 1.5;
                } else if ($block_count > 12) {
                    $block_final_score = 0;
                }
                if ( isset( $response['authenticated'] )) {
                    $auth_count = 0;
                    foreach ($response['authenticated'] as $key => $value) {
                        if( ! $value['status'] ){
                            $auth_count +=1;
                        }
                    }
                    if ($auth_count == 0) {
                        $auth_final_score = 2.5;
                    } else if ($auth_count > 0 && $auth_count < 3) {
                        $auth_final_score = 1.5;
                    } else if ($auth_count >= 3) {
                        $auth_final_score = 0;
                    }
                }
                if ( isset( $response['links'] ) ) {
                    $link_count = 0;
                    foreach ($response['links'] as $key => $value) {
                        if( $value['status'] > 200 ){
                            $link_count +=1;
                        }
                    }
                    if ($link_count > 0) {
                        $link_final_score = 0;
                    } else {
                        $link_final_score = 2.5;
                    }
                }
                $final_score = ($link_final_score + $auth_final_score + $block_final_score + $spam_final_score);
                $spam_score_get = get_option('check_email_spam_score_' . $current_user->user_email,[]);
                $current_date_time = current_time('Y-m-d H:i:s');
                $spam_score_get[$current_date_time] = array('score' => $final_score, 'datetime' => $current_date_time);
                $spam_score = array_reverse($spam_score_get);
                $n = 1;
                foreach (array_reverse($spam_score_get) as $key => $value) {
                    if( $n > 15 ){
                        unset($spam_score[$key]);
                    }
                    $n++;
                }
                update_option('check_email_spam_score_' . $current_user->user_email, $spam_score);
                $result['previous_spam_score'] = $spam_score;
                $result['previous_email_result'] = ck_email_verify($email);
                $data_to_insert = array(
                    'html_content' => wp_json_encode($response['html_tab']),
                    'spam_assassin' => wp_json_encode(array('data'=> $response['spamcheck_result'],'spam_final_score' => $spam_final_score)),
                    'authenticated' => wp_json_encode(array('data'=> $response['authenticated'],'auth_final_score' => $auth_final_score)),
                    'block_listed' => wp_json_encode(array('data'=> $blocklist,'block_final_score' => $block_final_score)),
                    'broken_links' => wp_json_encode(array('data'=> $response['links'],'link_final_score' => $link_final_score)),
                    'final_score' => $final_score,
                    'test_date' => $current_date_time,
                );
                if ( function_exists('ck_mail_insert_spam_analyzer') ) {
                    ck_mail_insert_spam_analyzer($data_to_insert);
                }
            }
            echo wp_json_encode( $result );
        } else {
            $error_message = $response->get_error_message();
            echo wp_json_encode( array( 'response' => $error_message ) );
        }
    }
    wp_die();
}


/** Function ajax() called by wp_ajax hooks: {'epsilon_check-email_review'} **/
/** Parameters found in function ajax(): {"post": ["check"]} **/
function ajax() {

		check_ajax_referer( 'epsilon-check-email-review', 'security' );

		if ( ! current_user_can('manage_options') ) {
      		return false;
    	}

		if ( ! isset( $_POST['check'] ) ) {
			wp_die( 'ok' );
		}

		$time = get_option( 'check-email-rate-time' );

		if ( 'epsilon-rate' == $_POST['check'] ) {
			$time = time() + YEAR_IN_SECONDS * 5;
		}elseif ( 'epsilon-later' == $_POST['check'] ) {
			$time = time() + WEEK_IN_SECONDS;
		}elseif ( 'epsilon-no-rate' == $_POST['check'] ) {
			$time = time() + YEAR_IN_SECONDS * 5;
		}

		update_option( 'check-email-rate-time', $time );
		wp_die( 'ok' );

	}


/** Function ck_mail_check_dns() called by wp_ajax hooks: {'check_dns'} **/
/** Parameters found in function ck_mail_check_dns(): {"post": ["ck_mail_security_nonce", "domain"]} **/
function ck_mail_check_dns() {
    // Check nonce
    if ( isset( $_POST['ck_mail_security_nonce'] ) ) {
        if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ck_mail_security_nonce'] ) ), 'ck_mail_security_nonce' ) ){
            die( '-1' );
        }

        // Check if user is allowed to manage network options
        if ( ! current_user_can( 'manage_check_email' ) ) {
            wp_send_json_error(esc_html__('Unauthorized user', 'check-email') );
            return;
        }
        // $api_url = 'http://127.0.0.1:8000/custom-api/check-dns';
        $api_url = 'https://enchain.tech/custom-api/check-dns';
        $domain = null;
        if ( isset( $_POST['domain'] ) ) {
            $domain = sanitize_text_field( wp_unslash( $_POST['domain'] ) );
        }
        $api_params = array(
            'domain' => $domain,
        );

        $response = wp_remote_post( $api_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

        if ( ! is_wp_error( $response ) ) {
            $response = wp_remote_retrieve_body( $response );
            $response = json_decode( $response, true );
            if (isset($response['is_error'])) {
                $result = $response;
            }else{
                $result['is_error'] = 0;
                $result['data'] = $response;
            }
            echo wp_json_encode( $result );
        } else {
            $error_message = $response->get_error_message();
            echo wp_json_encode( array( 'response' => $error_message ) );
        }
    }
    wp_die();
}


/** Function check_email_get_email_analytics_data() called by wp_ajax hooks: {'get_email_analytics'} **/
/** Parameters found in function check_email_get_email_analytics_data(): {"get": ["ck_mail_security_nonce", "ck_days"]} **/
function check_email_get_email_analytics_data() {
        if( !isset( $_GET['ck_mail_security_nonce'] ) || isset( $_GET['ck_mail_security_nonce'] ) && !wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['ck_mail_security_nonce'] ) ), 'ck_mail_ajax_check_nonce' ) ) {
            echo esc_html__('security_nonce_not_verified', 'check-email');
            die();
        }
        if ( !current_user_can( 'manage_options' ) ) {
            die();
        }
        global $wpdb;

        $table_name = $wpdb->prefix . 'check_email_log';
        $ck_days = isset($_GET['ck_days']) ? sanitize_text_field( wp_unslash( $_GET['ck_days'] ) ) : 7;
        $query = $wpdb->prepare(
            // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
            "SELECT * FROM $table_name WHERE sent_date >= CURDATE() - INTERVAL %d DAY",
            $ck_days
        );
        // phpcs:ignore InterpolatedNotPrepared
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared,PluginCheck.Security.DirectDB.UnescapedDBParameter
        $results = $wpdb->get_results($query);

        $data = [
            'labels' => [],
            'sent' => [],
            'failed' => [],
        ];

        
        $daily_counts = [];
        foreach ($results as $row) {
            $created_at = $row->sent_date;
            $status = $row->result;
            $date = gmdate('M j', strtotime($created_at));
            if (!isset($daily_counts[$date])) {
                $daily_counts[$date] = ['sent' => 0, 'failed' => 0];
            }
            if ($status == 1) {
                $daily_counts[$date]['sent']++;
            } else {
                $daily_counts[$date]['failed']++;
            }
        }
        ksort($daily_counts);
        foreach ($daily_counts as $date => $counts) {
            $data['labels'][] = $date;
            $data['sent'][] = $counts['sent'];
            $data['failed'][] = $counts['failed'];
        }

        $data['total_mail'] =  array_sum($data['sent']) + array_sum($data['failed']);
        $data['total_failed'] =  array_sum($data['failed']);
        $data['total_sent'] =  array_sum($data['sent']);

        wp_send_json($data);
    }


/** Function ck_mail_subscribe_to_news_letter() called by wp_ajax hooks: {'ck_mail_subscribe_to_news_letter'} **/
/** Parameters found in function ck_mail_subscribe_to_news_letter(): {"post": ["ck_mail_security_nonce", "name", "email", "website"]} **/
function ck_mail_subscribe_to_news_letter() {

                if( ! current_user_can( 'manage_options' ) ) {
                    die( '-1' );    
                }
                if ( ! isset( $_POST['ck_mail_security_nonce'] ) ){
                    die( '-1' ); 
                }
                if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ck_mail_security_nonce'] ) ), 'ck_mail_ajax_check_nonce' ) ){
                   die( '-1' );  
                }
                                
                $name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
                $email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email']) ) : '';
                $website = isset( $_POST['website'] ) ? sanitize_text_field( wp_unslash( $_POST['website'] ) ):'';
                
                if ( $email ) {
                        
                    $api_url = 'http://magazine3.company/wp-json/api/central/email/subscribe';

                    $api_params = array(
                        'name'    => $name,
                        'email'   => $email,
                        'website' => $website,
                        'type'    => 'checkmail',
                    );
                    
                    $response = wp_remote_post( $api_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
                    $response = wp_remote_retrieve_body( $response );
		    $response = json_decode( $response, true );
		    echo wp_json_encode( array( 'response' => $response['response'] ) );

                }else{
                        echo wp_json_encode( array( 'response' => esc_html__( 'Email id required', 'check-email' ) ) );
                }                        

                wp_die();
        }


/** Function ck_mail_update_network_settings() called by wp_ajax hooks: {'update_network_settings'} **/
/** No params detected :-/ **/


/** Function ck_mail_save_wizard_data() called by wp_ajax hooks: {'check_mail_save_wizard_data'} **/
/** Parameters found in function ck_mail_save_wizard_data(): {"post": ["ck_mail_security_nonce", "enable_dashboard_widget", "default_format_for_message"]} **/
function ck_mail_save_wizard_data() {
		if ( ! current_user_can( 'manage_check_email' ) ) {
			echo wp_json_encode(array('status'=> 501, 'message'=> esc_html__( 'Unauthorized access, permission not allowed','check-email')));
			wp_die();
		}
		if ( ! isset( $_POST['ck_mail_security_nonce'] ) ){
			echo wp_json_encode(array('status'=> 503, 'message'=> esc_html__( 'Unauthorized access, CSRF token not matched','check-email'))); 
			wp_die();
		}
		if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ck_mail_security_nonce'] ) ), 'ck_mail_ajax_check_nonce' ) ){
			echo wp_json_encode(array('status'=> 503, 'message'=> esc_html__( 'Unauthorized access, CSRF token not matched','check-email')));
			wp_die();
		}

		$option = get_option( 'check-email-log-core' );
		$from_data = $_POST;
		unset($from_data['action']);
		unset($from_data['ck_mail_security_nonce']);
		if (isset($_POST['enable_dashboard_widget']) && !empty($_POST['enable_dashboard_widget'])) {
			$from_data['enable_dashboard_widget'] = true;
		}

		$step = 'last';
		if (isset($_POST['default_format_for_message']) && !empty($_POST['default_format_for_message'])) {
			$from_data['default_format_for_message']= sanitize_text_field( wp_unslash( $_POST['default_format_for_message'] ) );
			$step = 'first';

			if (!isset($_POST['enable_dashboard_widget'])) {
				$from_data['enable_dashboard_widget'] = false;
			}
		}
		
        
        $merge_options = array_merge((array)$option, (array)$from_data);
        update_option('check-email-log-core',$merge_options);

		echo wp_json_encode(array('status'=> 200, 'step'=> $step,'steps_data'=>$this->cm_wizard_steps(), 'message'=> esc_html__('Wizard setup succefully.','check-email')));
		die;
	}


/** Function view_resend_message() called by wp_ajax hooks: {'check-email-log-list-view-resend-message'} **/
/** Parameters found in function view_resend_message(): {"get": ["log_id"]} **/
function view_resend_message() {
		if ( ! current_user_can( 'manage_check_email' ) ) {
			wp_die();
		}
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reason: We are not processing form information but only loading it inside the admin_init hook.
		$id = isset( $_GET['log_id'] ) ? absint( $_GET['log_id'] ) : 0 ;

		if ( $id <= 0 ) {
			wp_die();
		}

		$log_items = $this->get_table_manager()->fetch_log_items_by_id( array( $id ) );
		if ( count( $log_items ) > 0 ) {
			$log_item = $log_items[0];

			$headers = array();
			if ( ! empty( $log_item['headers'] ) ) {
				$parser  = new \CheckEmail\Util\Check_Email_Header_Parser();
				$headers = $parser->parse_headers( $log_item['headers'] );
			}

			?>
			<form name="check-mail-resend-form" id="check-mail-resend-form" >
			<input type="hidden" name="action" value="check_mail_resend_submit" />
			<input type="hidden" name="ck_mail_security_nonce" value="<?php echo esc_attr(wp_create_nonce( 'ck_mail_ajax_check_nonce' )) ?>" />
			<input type="hidden" id="cm_ajax_url" value="<?php echo esc_url(admin_url( 'admin-ajax.php' )); ?>" />
			<table style="width: 100%;">
				<tr style="background: #eee;">
					<td style="padding: 5px; width:113px;"><b><?php esc_html_e( 'To', 'check-email' ); ?></b><span class="" style="color:red;">*</span></td>
					<td style="padding: 5px;">
						<input type="email" id="ckm_to" name="ckm_to" class="regular-text" value="<?php echo esc_attr( $log_item['to_email'] ); ?>" />
						<small>&nbsp;<?php esc_html_e( 'Separate multiple emails by comma ( , )', 'check-email' ); ?></small>
					</td>
				</tr>
				<tr style="background: #eee;">
					<td style="padding: 5px;"><b><?php esc_html_e( 'Subject', 'check-email' ); ?></b><span class="" style="color:red;">*</span></td>
					<td style="padding: 5px;">
						<input type="text" id="ckm_subject" name="ckm_subject" class="regular-text" value="<?php echo esc_attr( $log_item['subject'] ); ?>" />
					</td>
				</tr>
				<tr style="background: #eee;">
					<td style="padding: 5px;"><b><?php esc_html_e( 'Message', 'check-email' ); ?></b></td>
					<td style="padding: 5px;">
						<textarea id="ckm_message" name="ckm_message" class="regular-text" rows="4" cols="4"> <?php echo esc_attr( $log_item['message'] ); ?></textarea>
					</td>
				</tr>
			</table>
			<h3><?php esc_html_e( 'Additional Details', 'check-email' ); ?></h3>
			<table style="width: 100%;">
                <tr style="background: #eee;">
					<td style="padding: 5px;"><b><?php esc_html_e( 'From', 'check-email' ); ?></b>:</td>
					<td style="padding: 5px;"><input type="email" name="ckm_from" id="ckm_from" class="regular-text" value="<?php  echo isset( $headers['from'] ) ?  esc_attr($headers['from']) : '' ?>" /></td>
				</tr>
				
				<tr style="background: #eee;">
					<td style="padding: 5px;"><b><?php esc_html_e( 'CC', 'check-email' ); ?></b>:</td>
					<td style="padding: 5px;"><input type="email" name="ckm_cc" id="ckm_cc" class="regular-text" value="<?php echo ( isset( $headers['cc'] )) ?  esc_attr($headers['cc']) : '' ?>" /><small>&nbsp;<?php esc_html_e( 'Separate multiple emails by comma ( , )', 'check-email' ); ?></small></td>
				</tr>
				<tr style="background: #eee;">
					<td style="padding: 5px;"><b><?php esc_html_e( 'BCC', 'check-email' ); ?></b>:</td>
					<td style="padding: 5px;"><input type="text" name="ckm_bcc" id="ckm_bcc" class="regular-text" value="<?php  echo isset( $headers['bcc'] ) ?  esc_attr($headers['bcc']) : '' ?>" /><small>&nbsp;<?php esc_html_e( 'Separate multiple emails by comma ( , )', 'check-email' ); ?></small></td>
				</tr>
				<tr style="background: #eee;">
					<td style="padding: 5px; width:110px;"><b><?php esc_html_e( 'Reply To', 'check-email' ); ?></b>:</td>
					<td style="padding: 5px;"><input type="text" name="ckm_reply_to" id="ckm_reply_to" class="regular-text" value="<?php echo ( isset( $headers['reply_to'] )) ?  esc_attr($headers['reply_to']) : '' ?>" /></td>
				</tr>
				<tr style="background: #eee;">
					<td style="padding: 5px;"><b><?php esc_html_e( 'Content Type', 'check-email' ); ?></b>:</td>
					<td style="padding: 5px;"><input type="text" name="ckm_content_type" id="ckm_content_type" class="regular-text" value="<?php echo ( isset( $headers['content_type'] )) ?  esc_attr($headers['content_type']) : '' ?>" /></td>
				</tr>

				
			</table>
			<?php
				if (!empty($log_item['attachment_name'])) {
					$attachments = explode(',',$log_item['attachment_name']);
					if ($attachments) {
						?>
						<h4><?php esc_html_e( 'Attachments', 'check-email' ); ?></h4>
						<?php
						foreach ($attachments as $key => $attachment) {
							echo wp_get_attachment_image($attachment, 'thumbnail', false, [
								'class' => 'custom-class',
								'style' => 'height: 100px; width: 100px;',
							]);
						}
					}
				}
			?>
			<span class="cm_js_error" style="color:red;"></span>
			<span class="cm_js_success" style="color:green;"></span>
			<div id="view-message-footer">
				<a href="#" class="button action" id="thickbox-footer-close"><?php esc_html_e( 'Close', 'check-email' ); ?></a>
				<button type="button" class="button " id="check_mail_resend_btn" style="margin-top: 10px;"><?php esc_html_e( 'Resend', 'check-email' ); ?>
			</button>
			</div>
			</form>
			<?php
		}

		wp_die();
	}


/** Function ck_mail_send_feedback() called by wp_ajax hooks: {'ck_mail_send_feedback'} **/
/** Parameters found in function ck_mail_send_feedback(): {"post": ["data"]} **/
function ck_mail_send_feedback() {
    // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Reason: in form variable.
    if( isset( $_POST['data'] ) ) {
        // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Reason: in form variable.
        parse_str( sanitize_text_field( wp_unslash($_POST['data'])), $form );
    }
    
    if( !isset( $form['ck_mail_security_nonce'] ) || isset( $form['ck_mail_security_nonce'] ) && !wp_verify_nonce( sanitize_text_field( $form['ck_mail_security_nonce'] ), 'ck_mail_ajax_check_nonce' ) ) {
        echo esc_html__('security_nonce_not_verified', 'check-email');
        die();
    }
    if ( !current_user_can( 'manage_options' ) ) {
        die();
    }
    
    $text = '';
    if( isset( $form['ck_mail_disable_text'] ) ) {
        if (is_array($form['ck_mail_disable_text'])) {
            $text = implode( " ", $form['ck_mail_disable_text'] );
        }
    }

    $headers = array();

    $from = isset( $form['ck_mail_disable_from'] ) ? $form['ck_mail_disable_from'] : '';
    if( $from ) {
        $headers[] = "From: $from";
        $headers[] = "Reply-To: $from";
    }

    $subject = isset( $form['ck_mail_disable_reason'] ) ? $form['ck_mail_disable_reason'] : '(no reason given)';

    if($subject == 'technical issue'){

          $subject  = 'Check & Log Email '.$subject;
          $text = trim($text);

          if(!empty($text)){

            $text = 'technical issue description: '.$text;

          }else{

            $text = 'no description: '.$text;
          }
      
    }else{
        $subject = 'Check & Log Email';
    }

    $success = wp_mail( 'team@magazine3.in', $subject, $text, $headers );
    
    echo 'sent';
    die();
}


/** Function checkmail_save_admin_fcm_token() called by wp_ajax hooks: {'checkmail_save_admin_fcm_token'} **/
/** Parameters found in function checkmail_save_admin_fcm_token(): {"post": ["ck_mail_security_nonce", "token"]} **/
function checkmail_save_admin_fcm_token() {
    $result['status'] = false;
    if (!isset($_POST['ck_mail_security_nonce'])) {
        return;
    }
    if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ck_mail_security_nonce'])), 'ck_mail_security_nonce')) {
        return;
    }
    if (isset($_POST['token']) && !empty($_POST['token'])) {

        $current_user = wp_get_current_user();

        if (in_array('administrator', (array) $current_user->roles)) {

            $device_tokens = get_option('checkmail_admin_fcm_token');
            if (!is_array($device_tokens)) {
                $device_tokens = [];
            }
            $new_token = sanitize_text_field(wp_unslash(($_POST['token'] )));

            if (!in_array($new_token, $device_tokens)) {
                $device_tokens[] = $new_token;
            }
            $device_tokens = array_slice(array_unique($device_tokens), -5);
            update_option('checkmail_admin_fcm_token', $device_tokens);
            $result['status'] = true;
        }
    }
    echo wp_json_encode( $result );
    wp_die();
}


