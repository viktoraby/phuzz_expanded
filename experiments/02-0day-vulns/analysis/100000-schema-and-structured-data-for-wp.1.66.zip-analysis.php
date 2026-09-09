<?php
/***
*
*Found actions: 63
*Found functions:55
*Extracted functions:55
*Total parameter names extracted: 63
*Overview: {'saswp_dismiss_notices': {'saswp_dismiss_notices'}, 'saswp_save_review_form_data': {'nopriv_saswp_review_form', 'saswp_review_form'}, 'saswp_get_schema_type_fields': {'saswp_get_schema_type_fields'}, 'saswp_rf_template_review_edit_form': {'nopriv_saswp_rf_template_review_edit_form', 'saswp_rf_template_review_edit_form'}, 'saswp_update_google_captch_keys': {'saswp_update_google_captch_keys'}, 'goodbye_form_callback': {'goodbye_form'}, 'saswp_review_filter': {'nopriv_saswp_rf_template_review_filter', 'saswp_rf_template_review_filter'}, 'saswp_get_select2_data': {'saswp_get_select2_data'}, 'saswp_save_steps_data': {'saswp_save_installer'}, 'saswp_license_transient': {'saswp_license_transient'}, 'saswp_ajax_generate_ai_schema': {'saswp_generate_ai_schema'}, 'saswp_video_upload': {'saswp_rf_form_video_upload', 'nopriv_saswp_rf_form_video_upload'}, 'saswp_create_ajax_select_taxonomy': {'create_ajax_select_sdwp_taxonomy'}, 'saswp_create_resized_image_folder': {'saswp_create_resized_image_folder'}, 'saswp_get_custom_meta_fields': {'saswp_get_custom_meta_fields'}, 'saswp_clear_resized_image_folder': {'saswp_clear_resized_image_folder'}, 'saswp_get_platform_place_list': {'saswp_get_platform_place_list'}, 'saswp_import_plugin_data': {'saswp_import_plugin_data'}, 'saswp_feeback_remindme': {'saswp_feeback_remindme'}, 'saswp_get_schema_dynamic_fields_ajax': {'saswp_get_schema_dynamic_fields_ajax'}, 'saswp_rf_review_edit': {'saswp_rf_review_edit'}, 'saswp_get_manual_fields_on_ajax': {'saswp_get_manual_fields_on_ajax'}, 'saswp_fetch_google_reviews': {'saswp_fetch_google_reviews'}, 'saswp_subscribe_to_news_letter': {'saswp_subscribe_to_news_letter'}, 'saswp_get_sub_business_ajax': {'saswp_get_sub_business_ajax'}, 'saswp_add_to_collection': {'saswp_add_to_collection'}, 'saswp_reset_all_settings': {'saswp_reset_all_settings'}, 'saswp_add_reviews_to_select2': {'saswp_add_reviews_to_select2'}, 'saswp_download_csv_review_format': {'saswp_download_csv_review_format'}, 'saswp_expired_license_transient': {'saswp_expired_license_transient'}, 'saswp_send_query_message': {'saswp_send_query_message'}, 'saswp_modify_schema_post_enable': {'saswp_modify_schema_post_enable'}, 'saswp_ajax_fetch_ai_models': {'saswp_fetch_ai_models'}, 'saswp_review_helpful': {'saswp_rf_template_review_helpful'}, 'saswp_get_collection_platforms': {'saswp_get_collection_platforms'}, 'saswp_modify_schema_post_restore': {'saswp_modify_schema_post_restore'}, 'saswp_get_item_reviewed_fields': {'saswp_get_item_reviewed_fields'}, 'saswp_enable_disable_schema_on_post': {'saswp_enable_disable_schema_on_post'}, 'saswp_get_taxonomy_term_list': {'saswp_get_taxonomy_term_list'}, 'saswp_feeback_no_thanks': {'saswp_feeback_no_thanks'}, 'saswp_remove_file': {'saswp_rf_form_remove_file', 'nopriv_saswp_rf_form_remove_file'}, 'saswp_review_highlight': {'saswp_template_review_hightlight'}, 'saswp_export_all_settings_and_schema': {'saswp_export_all_settings_and_schema'}, 'saswp_validate_schema_template_attr': {'saswp_validate_schema_template_attr'}, 'saswp_get_reviews_on_load': {'saswp_get_reviews_on_load'}, 'saswp_add_new_save_steps_data': {'saswp_add_new_save_steps_data'}, 'saswp_ajax_select_creator': {'create_ajax_select_sdwp'}, 'saswp_pagination': {'nopriv_saswp_rf_template_pagination', 'saswp_rf_template_pagination'}, 'saswp_image_upload': {'nopriv_saswp_rf_form_image_upload', 'saswp_rf_form_image_upload'}, 'get_get_schema_templates': {'saswp_get_schema_templates'}, 'saswp_license_status_check': {'saswp_license_status_check'}, 'saswp_send_feedback': {'saswp_send_feedback'}, 'saswp_get_meta_list': {'saswp_get_meta_list'}, 'saswp_skip_wizard': {'saswp_skip_wizard'}, 'saswp_self_video_popup': {'saswp_rf_form_self_video_popup', 'nopriv_saswp_rf_form_self_video_popup'}}
*
***/

/** Function saswp_dismiss_notices() called by wp_ajax hooks: {'saswp_dismiss_notices'} **/
/** Parameters found in function saswp_dismiss_notices(): {"post": ["saswp_security_nonce", "notice_type"]} **/
function saswp_dismiss_notices() {
  if(!current_user_can( saswp_current_user_can()) ) {
      die( '-1' );    
  }
  if ( ! isset( $_POST['saswp_security_nonce'] ) ){
    return; 
  }
  // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
  if ( !wp_verify_nonce( $_POST['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
    return;  
  }
  
  if ( isset( $_POST['notice_type']) ) {
    
    $notice_type = sanitize_text_field( wp_unslash( $_POST['notice_type'] ) );

    $user_id      = get_current_user_id();
    
    
    $updated = update_user_meta( $user_id, $notice_type.'_dismiss_date', gmdate("Y-m-d"));

    if($updated){
      echo wp_json_encode(array('status'=>'t'));  
    }else{
      echo wp_json_encode(array('status'=>'f'));  
    }

  }
  
  wp_die();           
}


/** Function saswp_save_review_form_data() called by wp_ajax hooks: {'nopriv_saswp_review_form', 'saswp_review_form'} **/
/** Parameters found in function saswp_save_review_form_data(): {"server": ["HTTP_REFERER", "HTTP_ORIGIN"], "post": ["saswp_review_nonce", "action"]} **/
function saswp_save_review_form_data() {
            /**
             * getallheaders() is supported only in Apache Web Server
             * and not in other popular web servers like NGINX.
             * 
             * Create the function if not already present, following the code
             * in https://www.php.net/manual/en/function.getallheaders.php#84262
             */
            if (!function_exists('getallheaders')) {
                function getallheaders()
                {
                    $headers = [];
                    foreach ( $_SERVER as $name => $value) {
                        if (substr($name, 0, 5) == 'HTTP_') {
                            $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                        }
                    }
                    return $headers;
                }
            }  
                        
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
            $rv_link   = isset($_SERVER['HTTP_REFERER'])?sanitize_url($_SERVER['HTTP_REFERER']):''; 
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
            if ( ! isset( $_POST['saswp_review_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['saswp_review_nonce'] ) ), 'saswp_review_form' ) ) {
                if($is_amp){
                    header("AMP-Redirect-To: ".$rv_link);
                    header("Access-Control-Expose-Headers: AMP-Redirect-To, AMP-Access-Control-Allow-Source-Origin");                                 
                    echo wp_json_encode(array('message'=> esc_html__( 'Nonce MisMatch', 'schema-and-structured-data-for-wp' )));die;
                }else{
                    wp_safe_redirect( $rv_link );
                    exit; 
                }
                
           }

            $headers = getallheaders();
            $is_amp  = false;
            if ( isset( $headers['AMP-Same-Origin']) ) {
                $is_amp = true;
            }
            $site_key   = get_option('saswp_g_site_key');
            $secret_key = get_option('saswp_g_secret_key');

            if( $site_key != '' && $secret_key != '' ){
                
                $captcha = '';

                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason: Nonce verification done here so unslash is not used.
                if ( isset( $_POST['g-recaptcha-response']) ) {
                    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason: Nonce verification done here so unslash is not used.
                    $captcha = $_POST['g-recaptcha-response'];
                }
                
                if(!$captcha){
                    wp_safe_redirect( $rv_link );
                    exit;
                }
                
                $url          = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secret_key) .  '&response=' . urlencode($captcha);
                $resultset       = wp_remote_get($url);
                if ( ! is_wp_error( $resultset) ) {
                    $responseKeys = json_decode(wp_remote_retrieve_body($resultset), true);
                    if(!$responseKeys["success"]){
                        wp_safe_redirect( $rv_link );
                        exit;
                    }
                }                                                                

            }
                                    
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason: Nonce verification done here so unslash is not used.
            if ( isset( $_POST['action'] ) && $_POST['action'] == 'saswp_review_form'){
                               
               if($is_amp){
                    
                    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason: Nonce verification done here so unslash is not used.
                    $http_origin = isset($_SERVER['HTTP_ORIGIN'])?sanitize_text_field($_SERVER['HTTP_ORIGIN']):'';
                    header("access-control-allow-credentials:true");
                    header("access-control-allow-headers:Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token");
                    header("Access-Control-Allow-Origin:".$http_origin);
                    $siteUrl = wp_parse_url(  get_site_url() );
                    header("AMP-Access-Control-Allow-Source-Origin:".$siteUrl['scheme'] . '://' . $siteUrl['host']);        
                    header("Content-Type:application/json;charset=utf-8");
                   
               }
                               
               $response = $this->_service->saswp_review_form_process_data($_POST);
            
                if($response){
                    
                    if($is_amp){
                        header("AMP-Redirect-To: ".$rv_link);
                        header("Access-Control-Expose-Headers: AMP-Redirect-To, AMP-Access-Control-Allow-Source-Origin");                                 
                    }else{                        
                        wp_redirect( $rv_link );
                        exit;
                    }                                        
                }                                                      
                                
            }  
            
            if($is_amp){
                 wp_die();     
            }
            
        }


/** Function saswp_get_schema_type_fields() called by wp_ajax hooks: {'saswp_get_schema_type_fields'} **/
/** Parameters found in function saswp_get_schema_type_fields(): {"post": ["saswp_security_nonce", "schema_subtype", "schema_type"]} **/
function saswp_get_schema_type_fields() {
            
             if ( ! isset( $_POST['saswp_security_nonce'] ) ){
                return; 
             }
             // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
             if ( !wp_verify_nonce( $_POST['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
                return;  
             }
            if(!current_user_can( saswp_current_user_can()) ) {
                die( '-1' );    
            }
            
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason post data is just used here so there is no necessary of unslash
            $schema_subtype = isset( $_POST['schema_subtype'] ) ? sanitize_text_field( $_POST['schema_subtype'] ) : ''; 
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason post data is just used here so there is no necessary of unslash
            $schema_type    = isset( $_POST['schema_type'] ) ? sanitize_text_field( $_POST['schema_type'] ) : '';                      
                      
            if ( $schema_type == 'Review' || $schema_type == 'CriticReview' ) {
                
                $meta_fields = $this->saswp_get_all_schema_type_fields($schema_subtype);                                            
                
            }else{
                $meta_fields = $this->saswp_get_all_schema_type_fields($schema_type);  
            }
            
            wp_send_json( $meta_fields );                                   
        }


/** Function saswp_rf_template_review_edit_form() called by wp_ajax hooks: {'nopriv_saswp_rf_template_review_edit_form', 'saswp_rf_template_review_edit_form'} **/
/** Parameters found in function saswp_rf_template_review_edit_form(): {"post": ["saswp_rf_form_nonce"], "request": ["comment_post_id", "comment_id"]} **/
function saswp_rf_template_review_edit_form() {

		if ( ! isset( $_POST['saswp_rf_form_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['saswp_rf_form_nonce'], 'saswp_rf_form_action_nonce') ) {
			return;
		}

		global $sd_data;

		$comment_post_id 	= isset( $_REQUEST['comment_post_id'] ) 	? absint( $_REQUEST['comment_post_id'] ) : null;
		$comment_id      	= isset( $_REQUEST['comment_id'] ) 		? absint( $_REQUEST['comment_id'] ) : null;
		$comment_data 		= get_comment( $comment_id );
		$review_edit 		= 'yes';
		
		// Only allow logged-in users to edit comments, and match user IDs strictly:
		$current_user_id = get_current_user_id();
		if ( empty( $current_user_id ) || (int) $comment_data->user_id !== (int) $current_user_id ) {
		    wp_send_json_error( esc_html__( 'Sorry! You do not have permission.', 'schema-and-structured-data-for-wp' ) );
		    return;
		}
		if ( ! $this->check_support( $get_post ) || ! saswp_check_stars_rating() ) {
		    wp_send_json_error( esc_html__( 'Review feature is not supported on this post.', 'schema-and-structured-data-for-wp' ) );
		    return;
		}

		ob_start();
		$post_type 	= 	get_post_type( $comment_post_id );
		$get_post 	=	get_post( $comment_post_id );
		if ( ! $this->check_support( $get_post ) && saswp_check_stars_rating() ) {
			return;
		}

		$criteria       = ( isset( $sd_data['saswp-rf-page-criteria'] ) && $sd_data['saswp-rf-page-criteria'] == 'multi' );
		$multi_criteria = get_comment_meta( $comment_id, 'saswp_rf_form_multi_rating', true );

		echo '<div class="saswp-rf-modal">';
		echo '<div class="saswp-rf-form saswp-rf-review-popup">';
		echo '<h2 id="saswp-rf-title" class="saswp-rf-form-title">' . esc_html__('Edit your review', 'schema-and-structured-data-for-wp') . '</h2>';
		echo '<form action="#" method="post" class="saswp-rf-form-box">';

		if (! $comment_data->comment_parent) {
			?>
			<div class="saswp-rf-form-group saswp-rf-form-hide-reply">
				
				<div class="saswp-rf-form-group saswp-rf-form-hide-reply">
		            <input id="saswp_review_form_title" class="saswp-rf-form-control" placeholder="<?php echo esc_attr__('Title', 'schema-and-structured-data-for-wp'); ?>" name="saswp_review_form_title" value="<?php echo esc_attr( get_comment_meta( $comment_id, 'saswp_review_form_title', true ) ); ?>" type="text" value="" size="30" aria-required="true">
		        </div>
		        
		        <div class="saswp-rf-form-group">
		            <textarea id="message" class="saswp-rf-form-control" placeholder="<?php echo esc_attr__( 'Write your review', 'schema-and-structured-data-for-wp' ); ?>" name="comment" aria-required="true" rows="6" cols="45"><?php
					$comment = get_comment( intval($comment_id) );
					echo wp_kses_post( $comment->comment_content ); ?></textarea>
		        </div>

				<ul class="saswp-rf-form-rating-ul">
					<?php
					if ( $criteria && $multi_criteria ) {
						$criteria_count = 1;
						foreach ( $multi_criteria as $key => $value ) {
	
							$id 				=	'saswp-rf-edit-form-multi-criteria-' . $criteria_count;
							$name 				=	'saswp-rf-form-rating-' . $key;
							$label 				=	ucfirst( str_replace( '-', ' ',  $key ) );
							$rating             = 	! empty( $value ) ? $value : 5;
							?>
		                	<li>
			                	<div class="saswp-rf-form-rating-text"><?php echo esc_html( $label ); ?></div>	
								<div class="saswp-rf-form-rating-container">
									<div class="saswp-rating-container">
										<div id="<?php echo esc_attr( $id ) ?>"></div>
										<div class="saswp-rateyo-counter"></div>
										<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $rating ); ?>" />
									</div>
								</div>
			                </li> 
			                <br>
		                <?php $criteria_count++;
						}
					} else { ?> 
		                <li>
		                    <?php $rt_rating = get_comment_meta( $comment_id, 'rating', true ); ?>
		                    <div class="saswp-rf-form-rating-text"><?php echo esc_html__( 'Rating', 'schema-and-structured-data-for-wp' ); ?></div>
							<div class="saswp-rf-form-rating-container">
								<div class="saswp-rating-container">
									<div id="saswp-rf-edit-form-rating"></div>
									<div class="saswp-rateyo-counter"></div>
									<input type="hidden" name="saswp_rf_form_rating" value="<?php echo esc_attr( $rt_rating ); ?>" />
								</div>
							</div>
		                </li> 
		                <?php
					}
					?>
				</ul>
			</div>
		<?php
		}
		
		if ( ! empty( $sd_data['saswp-rf-page-settings-pros-cons'] ) ) {
		?>
			<div class="saswp-rf-form-group saswp-rf-form-hide-reply">
				<div class="saswp-rf-form-pros-cons-wrapper">

					<div class="saswp-rf-form-pros-items saswp-rf-form-pros">
						<h4 class="saswp-rf-form-input-title">
							<span class="saswp-rf-form-item-icon"><i class="dashicons dashicons-thumbs-up"></i></span>
							<span class="saswp-rf-form-item-text"><?php echo esc_html__( 'PROS', 'schema-and-structured-data-for-wp' ); ?></span>
						</h4>
						<div id="saswp-rf-form-pros-field-wrapper">
								<?php 
								$pros = get_comment_meta( $comment_id, 'saswp_rf_form_pros', true );
								if ( ! empty( $pros ) && is_array( $pros ) ) {
									foreach ( $pros as $key => $value ) { 
									?>
										<div class="saswp-rf-form-input-filed">
											<span class="saswp-rf-form-remove-field">+</span>
											<input type="text" class="form-control" name="saswp_rf_form_pros[]" placeholder="<?php echo esc_html__( 'Write Pros', 'schema-and-structured-data-for-wp' ); ?>" value="<?php echo esc_attr( $value ); ?>">
										</div>
									<?php
									}
								}else{
								?>
									<div class="saswp-rf-form-input-filed">
										<span class="saswp-rf-form-remove-field">+</span>
										<input type="text" class="form-control" name="saswp_rf_form_pros[]" placeholder="<?php echo esc_html__( 'Write Pros', 'schema-and-structured-data-for-wp' ); ?>">
									</div>
								<?php
								}
								?>
							
						</div>
						<div class="saswp-rf-form-add-field" id="saswp-rf-form-add-pros-field"><i class="dashicons dashicons-plus"></i><?php echo esc_html__( 'Add Pros', 'schema-and-structured-data-for-wp' ); ?></div>
					</div>

					<div class="saswp-rf-form-cons-items saswp-rf-form-cons">
						<h4 class="saswp-rf-form-input-title">
							<span class="saswp-rf-form-item-icon"><i class="dashicons dashicons-thumbs-down"></i></span>
							<span class="saswp-rf-form-item-text"><?php echo esc_html__( 'Cons', 'schema-and-structured-data-for-wp' ); ?></span>
						</h4>
						<div id="saswp-rf-form-cons-field-wrapper">
							<?php 
								$cons = get_comment_meta( $comment_id, 'saswp_rf_form_cons', true );
								if ( ! empty( $cons ) && is_array( $cons ) ) {
									foreach ( $cons as $key => $value ) { 
									?>										
										<div class="saswp-rf-form-input-filed">
											<span class="saswp-rf-form-remove-field">+</span>
											<input type="text" class="form-control" name="saswp_rf_form_cons[]" placeholder="<?php echo esc_html__( 'Write Cons', 'schema-and-structured-data-for-wp' ); ?>" value="<?php echo esc_attr( $value ); ?>">
										</div>
									<?php
									}
								}else{
								?>
									<div class="saswp-rf-form-input-filed">
										<span class="saswp-rf-form-remove-field">+</span>
										<input type="text" class="form-control" name="saswp_rf_form_cons[]" placeholder="<?php echo esc_html__( 'Write Cons', 'schema-and-structured-data-for-wp' ); ?>">
									</div>

								<?php
								}
								?>
						</div>
						<div class="saswp-rf-form-add-field" id="saswp-rf-form-add-cons-field"><i class="dashicons dashicons-plus"></i><?php echo esc_html__( 'Add Cons', 'schema-and-structured-data-for-wp' ); ?></div>
					</div>

				</div>
			</div>
		<?php		
		}

		$image_review = ( isset( $sd_data['saswp-rf-page-settings-image-review'] ) && $sd_data['saswp-rf-page-settings-image-review'] == '10' );
		if ( $image_review === 10 ) { ?>
        <div class="saswp-rf-form-group saswp-rf-form-hide-reply">
            <div class="saswp-rf-form-preview-imgs"></div>
        </div> 
        
        <div class="saswp-rf-form-group saswp-rf-form-media-form-group saswp-rf-form-hide-reply">             

            <div>
                <label class="saswp-rf-form-input-image-label"><?php echo esc_html__( 'Upload Image', 'schema-and-structured-data-for-wp' ); ?></label>
            </div>

            <div>
                <div class="saswp-rf-form-multimedia-upload">
                    <div class="saswp-rf-form-upload-box" id="saswp-rf-form-upload-box-image"> 
                        <span><?php echo esc_html__('Upload Image', 'schema-and-structured-data-for-wp'); ?></span>
                    </div> 
                </div>
                <input type="file" id="saswp-rf-form-image" accept="image/*" style="display:none">
                <div class="saswp-rf-form-image-error"></div>
            </div>
        </div> 
        <?php }

		$video_review = ( isset( $sd_data['saswp-rf-page-settings-video-review'] ) && $sd_data['saswp-rf-page-settings-video-review'] == '10' );
		if ( $video_review === 10 ) { ?>
			<div class="saswp-rf-form-video-media-groups">
	        	<div class="saswp-rf-form-group saswp-rf-form-hide-reply">
					<div class="saswp-rf-form-preview-videos"></div>
				</div> 

		        <div class="saswp-rf-form-group saswp-rf-form-media-form-group saswp-rf-form-hide-reply">
					<div class="saswp-rf-form-button-label">
						<label class="saswp-rf-form-input-video-label"><?php echo esc_html__( 'Upload Video', 'schema-and-structured-data-for-wp' ); ?></label>
					</div>

		            <div class="saswp-rf-form-video-source-selector">
						<select name="saswp-rf-form-video-source" id="saswp-rf-form-video-source" class="saswp-rf-form-control">
							<option value="self"><?php echo esc_html__( 'Hosted Video', 'schema-and-structured-data-for-wp' ); ?></option>
							<option value="external"><?php echo esc_html__( 'External Video', 'schema-and-structured-data-for-wp' ); ?></option>
						</select>
					</div>

		            <div class="saswp-rf-form-source-video">
						<div class="saswp-rf-form-multimedia-upload">
							<div class="saswp-rf-form-upload-box" id="saswp-rf-form-upload-box-video">
								<span><?php echo esc_html__( 'Choose Video', 'schema-and-structured-data-for-wp' ); ?></span>
							</div>
						</div>
						<input type="file" id="saswp-rf-form-video" accept="video/*" style="display:none">
						<div class="saswp-rf-form-video-error"></div>
					</div>
		        </div>  

		        <div class="saswp-rf-form-group saswp-rf-form-source-external saswp-rf-form-hide-reply">
					<label class="saswp-rf-form-input-label" for="saswp-rf-form-external-video"><?php echo esc_html__( 'External Video Link', 'schema-and-structured-data-for-wp' ); ?></label>
					<input id="saswp-rf-form-external-video" class="saswp-rf-form-control" placeholder="https://www.youtube.com/watch?v=668nUCeBHyY" name="saswp-rf-form-external-video" type="text">
				</div>
			</div>
        <?php 
        } 
        ?>

        <div class="saswp-rf-form-group">
            <input name="submit" type="submit" id="submit" class="saswp-rf-form-submit-btn saswp-rf-edit-submit" value="<?php echo esc_attr__('Submit Review', 'schema-and-structured-data-for-wp'); ?>"> 
            <input type="hidden" name="action" value="saswp_rf_review_edit">
            <input type="hidden" name="comment_post_ID" value="<?php echo esc_attr($comment_post_id); ?>" id="comment_post_ID">
            <input type="hidden" name="comment_ID" value="<?php echo esc_attr($comment_id); ?>" id="comment_ID">
            <input type="hidden" name="comment_parent" id="comment_parent" value="0">

        </div>

        <?php
		echo '</form>';
		echo '</div>';
		echo '</div>'; //modal
		$edit_form = ob_get_clean();
		wp_send_json_success($edit_form);
	}


/** Function saswp_update_google_captch_keys() called by wp_ajax hooks: {'saswp_update_google_captch_keys'} **/
/** Parameters found in function saswp_update_google_captch_keys(): {"post": ["saswp_security_nonce", "gsitekey", "gsecretkey", "captcha_enable"]} **/
function saswp_update_google_captch_keys()
    {
        if ( ! isset( $_POST['saswp_security_nonce'] ) ){
            return; 
        }
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
        if ( !wp_verify_nonce( $_POST['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
           return;  
        } 
        if(!current_user_can( saswp_current_user_can()) ) {
            die( '-1' );    
        }

        if ( ! isset( $_POST['gsitekey']) && !isset($_POST['gsecretkey']) ) {
            $captcha_enable = isset( $_POST['captcha_enable'] ) ? intval( $_POST['captcha_enable'] ) : '';
            $keys['saswp_ar_captcha_checkbox'] = $captcha_enable;

            $get_options   = get_option('sd_data');
            $merge_options = array_merge($get_options, $keys);
            update_option('sd_data', $merge_options);
        }elseif ( isset( $_POST['gsitekey']) && isset($_POST['gsecretkey']) ) {
            $gsitekey = isset( $_POST['gsitekey'] ) ? sanitize_text_field( wp_unslash( $_POST['gsitekey'] ) ) : '';
            $gsecretkey = isset( $_POST['gsecretkey'] ) ? sanitize_text_field( wp_unslash( $_POST['gsecretkey'] ) ) : '';
            $captcha_enable = isset( $_POST['captcha_enable'] ) ? intval( $_POST['captcha_enable'] ) : '';

            $keys['saswp_g_site_key'] = $gsitekey;
            $keys['saswp_g_secret_key'] = $gsecretkey;
            $keys['saswp_ar_captcha_checkbox'] = $captcha_enable;

            $get_options   = get_option('sd_data');
            $merge_options = array_merge($get_options, $keys);
            update_option('sd_data', $merge_options);
        }
        wp_die();
    }


/** Function goodbye_form_callback() called by wp_ajax hooks: {'goodbye_form'} **/
/** Parameters found in function goodbye_form_callback(): {"post": ["values", "details"]} **/
function goodbye_form_callback() {
			if(!current_user_can( saswp_current_user_can()) ) {
			    die( '-1' );    
			}
			check_ajax_referer( 'saswp_goodbye_form', 'security' );
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			if( isset( $_POST['values'] ) ) {
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				$values = wp_json_encode( wp_unslash( $_POST['values'] ) );
				update_option( 'wisdom_deactivation_reason_' . $this->plugin_name, $values );
			}
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			if( isset( $_POST['details'] ) ) {
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
				$details = sanitize_text_field( $_POST['details'] );
				update_option( 'wisdom_deactivation_details_' . $this->plugin_name, $details );
			}
			$this->do_tracking(); // Run this straightaway
			echo 'success';
			wp_die();
		}


/** Function saswp_review_filter() called by wp_ajax hooks: {'nopriv_saswp_rf_template_review_filter', 'saswp_rf_template_review_filter'} **/
/** Parameters found in function saswp_review_filter(): {"post": ["saswp_rf_form_nonce"], "request": ["sort_by", "filter_by", "current_page"]} **/
function saswp_review_filter() {

		if ( ! isset( $_POST['saswp_rf_form_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['saswp_rf_form_nonce'], 'saswp_rf_form_action_nonce') ) {
			return;
		}

		$sort_by   	= isset( $_REQUEST['sort_by'] ) 		? sanitize_text_field( $_REQUEST['sort_by'] ) : '';
		$filter_by 	= isset( $_REQUEST['filter_by'] ) 	? sanitize_text_field( $_REQUEST['filter_by'] ) : '';
		$cur_page  	= isset( $_REQUEST['current_page'] ) ? absint( $_REQUEST['current_page'] ) : 1;

		$max_page 	= $this->get_sorted_reviews( $sort_by, $filter_by );

		ob_start();
		$this->get_reviews( $sort_by, $filter_by );
		$review = ob_get_clean();

		$pagination = $this->paginate_comments_links( $cur_page, $max_page );
		wp_send_json_success( ['review' => $review, 'pagination' => $pagination, 'sort_by' => $sort_by] );
	}


/** Function saswp_get_select2_data() called by wp_ajax hooks: {'saswp_get_select2_data'} **/
/** Parameters found in function saswp_get_select2_data(): {"get": ["saswp_security_nonce", "q", "type"]} **/
function saswp_get_select2_data() {
        if(!current_user_can( saswp_current_user_can()) ) {
            die( '-1' );    
        }    
        if ( ! isset( $_GET['saswp_security_nonce'] ) ){
          return; 
        }
        
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
        if ( (wp_verify_nonce( $_GET['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ) ||  (wp_verify_nonce( $_GET['saswp_security_nonce'], 'saswp_add_new_nonce' ) ) ) {

          // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason Server data is just used here so there is no necessary of unslash
          $search        = isset( $_GET['q'] ) ? sanitize_text_field( $_GET['q'] ) : '';    
          // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason Server data is just used here so there is no necessary of unslash                                
          $type          = isset( $_GET['type'] ) ? sanitize_text_field( $_GET['type'] ) : '';                                    

          $result = saswp_get_condition_list($type, $search);
                      
          wp_send_json( $result );            

        }else{
          return;  
        }                
        
        wp_die();
}


/** Function saswp_save_steps_data() called by wp_ajax hooks: {'saswp_save_installer'} **/
/** Parameters found in function saswp_save_steps_data(): {"post": ["wpnonce", "sd_data", "sd_data_create__post_schema", "sd_data_create__post_schema_checkbox"]} **/
function saswp_save_steps_data() { 
            
                 if(! current_user_can( saswp_current_user_can() ) ) {
                    return ;
                 }
                 if ( ! isset( $_POST['wpnonce'] ) ){
                    return; 
                 }
                 // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
                 if ( !wp_verify_nonce( $_POST['wpnonce'], 'saswp_install_nonce' ) ){
                    return;  
                 }                                 
                if ( isset( $_POST['sd_data']) ) {
                    
                $pre_sd_data                              = get_option('sd_data'); 
                $pre_sd_data['sd_initial_wizard_status']  = 1;                
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason post data is just used here so there is no necessary of unslash
                $sd_data                                  = array_map('sanitize_text_field', $_POST['sd_data']);
                
                if($pre_sd_data){
                    
						$sd_data = array_merge($pre_sd_data,$sd_data);
				}
                        update_option('sd_data',$sd_data);
                
                }
		
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason post data is just used here so there is no necessary of unslash
		if ( isset( $_POST['sd_data_create__post_schema']) && isset($_POST['sd_data_create__post_schema_checkbox']) ) {
                    
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized --Reason post data is just used here so there is no necessary of unslash
			$checkbox = array_filter($_POST['sd_data_create__post_schema_checkbox']);
			if(count($checkbox)>0){
                            
				foreach ( $checkbox as $key => $value) {
                                    
					// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason post data is just used here so there is no necessary of unslash
					$postType   = isset($_POST['sd_data_create__post_schema'][$key]['posttype'])?sanitize_text_field($_POST['sd_data_create__post_schema'][$key]['posttype']):'';
					// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason post data is just used here so there is no necessary of unslash
					$schemaType = isset($_POST['sd_data_create__post_schema'][$key]['schema_type'])?sanitize_text_field($_POST['sd_data_create__post_schema'][$key]['schema_type']):'';
					
					$postarr = array(
                                            'post_type'   => 'saswp',
                                            'post_title'  => ucfirst($postType),
                                            'post_status' => 'publish',
		                        );
                                        
					$insertedPageId = wp_insert_post(  $postarr );
                                        
					if($insertedPageId){
                                            
                                        $data_group_array = array();  
                                        
					$data_group_array['group-0'] =array(
                                            
                                            'data_array' => array(
                                                        array(
                                                        'key_1' => 'post_type',
                                                        'key_2' => 'equal',
                                                        'key_3' => $postType,
                                              )
                                            ) 
                                            
                                           );
                    $data_group_array = saswp_sanitize_multi_array($data_group_array, 'data_array');
					$schema_options_array = array('isAccessibleForFree'=>False,'notAccessibleForFree'=>0,'paywall_class_name'=>'');
					update_post_meta( $insertedPageId, 'data_group_array', $data_group_array);
					update_post_meta( $insertedPageId, 'schema_type', $schemaType);
					update_post_meta( $insertedPageId, 'schema_options', $schema_options_array);
                                        
					}
				}
				
			}
			/**/

		}
		wp_send_json(
			array(
				'done' => 1,
				'message' => esc_html__( 'Stored Successfully', 'schema-and-structured-data-for-wp' ),
			)
		);
	}


/** Function saswp_license_transient() called by wp_ajax hooks: {'saswp_license_transient'} **/
/** Parameters found in function saswp_license_transient(): {"post": ["saswp_security_nonce"]} **/
function saswp_license_transient() {
            if ( ! current_user_can( saswp_current_user_can() ) ) {
                 return;
            }
            if ( ! isset( $_POST['saswp_security_nonce'] ) ){
                 die( '-1' );  
            }
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
            if ( !wp_verify_nonce( $_POST['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
                 return;  
            }
            $transient_load =  'saswp_addons_set_transient';
            $value_load =  'saswp_addons_set_transient_value';
            $expiration_load =  86400 ;
            set_transient( $transient_load, $value_load, $expiration_load );
}


/** Function saswp_ajax_generate_ai_schema() called by wp_ajax hooks: {'saswp_generate_ai_schema'} **/
/** Parameters found in function saswp_ajax_generate_ai_schema(): {"post": ["post_id", "target_type"]} **/
function saswp_ajax_generate_ai_schema() {
    check_ajax_referer('saswp_ajax_check_nonce', 'saswp_security_nonce');

    if (!current_user_can('edit_posts')) {
        wp_send_json_error(array('error' => esc_html__('Unauthorized capability.', 'schema-and-structured-data-for-wp')));
    }

    $post_id     = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
    $target_type = isset($_POST['target_type']) ? sanitize_text_field($_POST['target_type']) : 'auto';

    if (!$post_id) {
        wp_send_json_error(array('error' => esc_html__('Invalid post ID.', 'schema-and-structured-data-for-wp')));
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        wp_send_json_error( array( 'error' => esc_html__( 'Unauthorized capability.', 'schema-and-structured-data-for-wp' ) ) );
    }

    $post = get_post($post_id);
    if (!$post) {
        wp_send_json_error(array('error' => esc_html__('Post not found.', 'schema-and-structured-data-for-wp')));
    }

    $result = SASWP_AI_Service::generate_schema($post->post_title, $post->post_content, $target_type, $post_id);

    if (!$result['success']) {
        wp_send_json_error(array('error' => $result['error']));
    }

    wp_send_json_success(array('schema' => $result['schema']));
}


/** Function saswp_video_upload() called by wp_ajax hooks: {'saswp_rf_form_video_upload', 'nopriv_saswp_rf_form_video_upload'} **/
/** Parameters found in function saswp_video_upload(): {"post": ["saswp_rf_form_nonce"]} **/
function saswp_video_upload() {
		if ( ! isset( $_POST['saswp_rf_form_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['saswp_rf_form_nonce'], 'saswp_rf_form_action_nonce') ) {
			return;
		}

		if ( ! current_user_can( 'upload_files' ) ) {
	        wp_send_json_error( [ 'msg' => esc_html__( 'You do not have permission to upload files.', 'schema-and-structured-data-for-wp' ) ] );
	        return;
	    }

		$file               = isset( $_FILES['saswp-rf-form-video'] ) ? $_FILES['saswp-rf-form-video'] : null;
		if ( empty( $file['name'] ) || empty( $file['tmp_name'] ) ) {
	        return;
	    }

		$allowed_mimes = [
	        'mp4|m4v' => 'video/mp4',
	        'mov'     => 'video/quicktime',
	        'avi'     => 'video/x-msvideo',
	    ];

		$video_max_size    =  2048;
		$allowed_file_size = $video_max_size * 1024;

		// Server-side type check — replaces the browser-supplied $file['type'].
	    // wp_check_filetype_and_ext() inspects actual file bytes via finfo/getimagesize.
	    $checked = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'], $allowed_mimes );
		
		if ( empty( $checked['type'] ) || ! in_array( $checked['type'], array_values( $allowed_mimes ), true ) ) {
	        $valid_types   = implode( ', ', array_map( fn( $m ) => ltrim( strstr( $m, '/' ), '/' ), array_values( $allowed_mimes ) ) );
	        $detected_type = ! empty( $checked['type'] )
	            ? ltrim( strstr( $checked['type'], '/' ), '/' )
	            : esc_html__( 'unknown', 'schema-and-structured-data-for-wp' );

	        wp_send_json_error( [
	            'msg' => sprintf(
	                esc_html__( 'Invalid file type: %s. Supported file types: %s', 'schema-and-structured-data-for-wp' ),
	                $detected_type,
	                $valid_types
	            ),
	        ] );
	        return;
	    }

		// Check file size
		if ($file['size'] > $allowed_file_size) {
			wp_send_json_error(['msg' => sprintf(esc_html__('File is too large. Max. upload file size is %s', 'schema-and-structured-data-for-wp'), self::format_bytes($allowed_file_size))]);
		}

		if (! function_exists('wp_handle_upload')) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		$upload_overrides = [
	        'test_form' => false,
	        'mimes'     => $allowed_mimes,
	    ];

		$uploaded         = wp_handle_upload($file, $upload_overrides);
		
		if ($uploaded && ! isset($uploaded['error'])) {
			$filename = $uploaded['file'];
			$filetype = wp_check_filetype( basename( $filename ), $allowed_mimes );

			//Todo: think about sanitization here
			$attach_id = wp_insert_attachment(
				[
					'guid'           => $uploaded['url'],
					'post_title'     => sanitize_text_field(preg_replace('/\.[^.]+$/', '', basename($filename))),
					'post_excerpt'   => '',
					'post_content'   => '',
					'post_mime_type' => sanitize_text_field($filetype['type']),
					'post_status'    => 'inherit',
					'comment_status' => 'closed',
				],
				$uploaded['file'],
				0
			);
			
			$file_info = [];
			if (! is_wp_error($attach_id)) {
				$file_info = [
					'id'   => $attach_id,
					'name' => preg_replace('/\.[^.]+$/', '', basename($filename)),
				];
			}

			wp_send_json_success(['file_info' => $file_info]);
		} else {
			/*
			 * Error generated by _wp_handle_upload()
			 * @see _wp_handle_upload() in wp-admin/includes/file.php
			 */
			wp_send_json_error( [
	            'msg' => isset( $uploaded['error'] ) ? $uploaded['error'] : esc_html__( 'Upload failed.', 'schema-and-structured-data-for-wp' ),
	        ] );
		}
		
	}


/** Function saswp_create_ajax_select_taxonomy() called by wp_ajax hooks: {'create_ajax_select_sdwp_taxonomy'} **/
/** Parameters found in function saswp_create_ajax_select_taxonomy(): {"server": ["REQUEST_METHOD"], "post": ["saswp_call_nonce", "id", "number", "group_number"]} **/
function saswp_create_ajax_select_taxonomy($selectedParentValue = '',$selectedValue='', $current_number ='', $current_group_number  = ''){
    
    $is_ajax = false;
    
    if ( isset( $_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=='POST'){
        
        $is_ajax = true;
        
        if(! current_user_can( saswp_current_user_can() ) ) {
          exit;
        }
        
        if ( ! isset( $_POST["saswp_call_nonce"] ) ) {
          return;
        }
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
        if(wp_verify_nonce($_POST["saswp_call_nonce"],'saswp_select_action_nonce') ) {
            
              if ( isset( $_POST['id']) ) {
                  
                $selectedParentValue = sanitize_text_field(wp_unslash($_POST['id']));
                
              }
              
              if ( isset( $_POST['number']) ) {
                  
                 // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason Server data is just used here so there is no necessary of unslash
                $current_number = intval(sanitize_text_field($_POST['number']));
                
              }
              
              if ( isset( $_POST["group_number"] ) ) {
                  
                 // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason Server data is just used here so there is no necessary of unslash
                $current_group_number   = intval(sanitize_text_field($_POST["group_number"]));
              
              }
              
        }else{
            
            exit;
            
        }       
    }
    $taxonomies    = array();
    $saved_choices = array();

    $taxonomies = saswp_get_condition_list($selectedParentValue);       

    if( $selectedValue ) {
      $saved_choices = saswp_get_condition_list($selectedParentValue, '', $selectedValue);                              
    }
                 
    $choices = '<option value="all">'.esc_html__( 'All' , 'schema-and-structured-data-for-wp' ) .'</option>';
    
    if ( ! empty( $taxonomies) ) {
        
        foreach( $taxonomies as $taxonomy) {                    
            $choices .= '<option value="'. esc_attr( $taxonomy['id']).'">'.esc_html( $taxonomy['text']).'</option>';                                    
        }
    
        if($saved_choices){
          foreach( $saved_choices as $value){
            $choices .= '<option value="' . esc_attr( $value['id']) .'" selected> ' .  esc_html( $value['text']) .'</option>';                     
          }
        }   

    $allowed_html = saswp_expanded_allowed_tags();  
    
    echo '<select data-type="'. esc_attr( $selectedParentValue).'" class="widefat ajax-output-child saswp-select2" name="data_group_array[group-'. esc_attr( $current_group_number) .'][data_array]['. esc_attr( $current_number).'][key_4]">'. wp_kses($choices, $allowed_html).'</select>';
        
    }    
    
    if($is_ajax){
      die;
    }
}


/** Function saswp_create_resized_image_folder() called by wp_ajax hooks: {'saswp_create_resized_image_folder'} **/
/** Parameters found in function saswp_create_resized_image_folder(): {"post": ["saswp_security_nonce"]} **/
function saswp_create_resized_image_folder() {                  
  if(!current_user_can( saswp_current_user_can()) ) {
    die( '-1' );    
  }  
  if ( ! isset( $_POST['saswp_security_nonce'] ) ){
     return; 
  }
  // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
  if ( !wp_verify_nonce( $_POST['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
     return;  
  }    

  $response    = array();       
  $upload_info = wp_upload_dir();
  $upload_dir  = $upload_info['basedir'];
  $upload_url  = $upload_info['baseurl'];  
  
  $make_new_dir = $upload_dir . '/schema-and-structured-data-for-wp';

  if (! is_dir($make_new_dir)) {
    wp_mkdir_p($make_new_dir);
  }

  if(is_dir($make_new_dir) ) {

    $old_url    = SASWP_PLUGIN_URL.'/admin_section/images/sd-logo-white.png';            
    $url        = $upload_url.'/schema-and-structured-data-for-wp/sd-logo-white.png';
    $new_url    = $make_new_dir.'/sd-logo-white.png';    
    @copy($old_url, $new_url);
        
    if(file_exists($new_url) ) {
      $response = array('status' => 't');   
    }else{
      $response = array('status' => 'f', 'message' => esc_html__( 'We are unable to create a folder in your uploads directory. Please Check your folder permission settings on server and allow it.', 'schema-and-structured-data-for-wp' ));
    }

  }else{
    $response = array('status' => 'f', 'message' => esc_html__( 'We are unable to create a folder in your uploads directory. Please Check your folder permission settings on server and allow it.', 'schema-and-structured-data-for-wp' ));
  }

  wp_send_json( $response );

  wp_die();           

}


/** Function saswp_get_custom_meta_fields() called by wp_ajax hooks: {'saswp_get_custom_meta_fields'} **/
/** Parameters found in function saswp_get_custom_meta_fields(): {"post": ["saswp_security_nonce", "q"]} **/
function saswp_get_custom_meta_fields() {
            
             if ( ! isset( $_POST['saswp_security_nonce'] ) ){
                return; 
             }
             // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
             if ( !wp_verify_nonce( $_POST['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
                return;  
             }
            if(!current_user_can( saswp_current_user_can()) ) {
                die( '-1' );    
            }
            
            $search_string = isset( $_POST['q'] ) ? sanitize_text_field( wp_unslash( $_POST['q'] ) ) : '';                                    
	        $data          = array();
	        $result        = array();
            
            global $wpdb;
            $meta_search_value = '%' . $wpdb->esc_like( trim( $search_string ) ) . '%'; // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
            //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
	        $saswp_meta_array = $wpdb->get_results( $wpdb->prepare("SELECT DISTINCT meta_key FROM {$wpdb->postmeta} WHERE meta_key LIKE %s", $meta_search_value), ARRAY_A ); 

            if ( isset( $saswp_meta_array ) && ! empty( $saswp_meta_array ) ) {
                
				foreach ( $saswp_meta_array as $value ) {
				
						$data[] = array(
							'id'   => $value['meta_key'],
							'text' => preg_replace( '/^_/', '', esc_html( str_replace( '_', ' ', $value['meta_key'] ) ) ),
						);
					
				}
                                
			}

            //aioseo wp_aioseo_posts support starts here
            $column_names = array();
            $cache_key    = 'saswp_aioseo_posts_cache_key';
            $table_name   = $wpdb->prefix . 'aioseo_posts';
            $columns_des  = wp_cache_get( $cache_key ); 
            if ( false === $columns_des ) {                                		        
                //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery	-- just to check if table exists
		        $table_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name));
                if($table_exists){
                    //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.NotPrepared -- Reasone Custom table create by aioseo
                    $columns_des  = $wpdb->get_col( "DESC " . $table_name, 0 );
                    wp_cache_set( $cache_key, $columns_des );
                }
                
            }               

            if($columns_des){

                foreach ( $columns_des as $column_name ) {
                    $column_names[] = 'aioseo_posts_'.$column_name;
                }

                foreach ( $column_names as $string ) {
                    
                    $preg_rep = preg_replace( '/^_/', '', esc_html( str_replace( '_', ' ', $string ) ) );

                    if ( strpos( $string, $search_string ) !== false ) {

                        $data[] = array(
							'id'   => $string,
							'text' => $preg_rep
						);                        
                    }

                    if ( strpos( $preg_rep, $search_string ) !== false ) {

                        $data[] = array(
							'id'   => $string,
							'text' => $preg_rep
						);                        
                    }

                }

            }
                        
            //aioseo wp_aioseo_posts support endss here
                        
            if ( is_array( $data ) && ! empty( $data ) ) {
                
				$result[] = array(
					'children' => $data,
				);
                                
			}
                        
            wp_send_json( $result );            
            
            wp_die();
        }


/** Function saswp_clear_resized_image_folder() called by wp_ajax hooks: {'saswp_clear_resized_image_folder'} **/
/** Parameters found in function saswp_clear_resized_image_folder(): {"post": ["saswp_security_nonce"]} **/
function saswp_clear_resized_image_folder() {
  if(!current_user_can( saswp_current_user_can()) ) {
    die( '-1' );    
  }
  if ( ! isset( $_POST['saswp_security_nonce'] ) ){
      return; 
  }
  // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
  if ( !wp_verify_nonce( $_POST['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
      return;  
  }

  $response    = array(); 
  
  $upload_info = wp_upload_dir();
  $upload_dir  = $upload_info['basedir'];    
  
  $folder = $upload_dir . '/schema-and-structured-data-for-wp';

  $files = glob($folder . '/*');

  if($files){
    //Loop through the file list.
    foreach( $files as $file){
      //Make sure that this is a file and not a directory.
      if(is_file($file) ) {
          //Use the unlink function to delete the file.
          wp_delete_file($file);
      }
    }

  }  

  $response = array('status' => 't');

  wp_send_json( $response );

  wp_die();           

}


/** Function saswp_get_platform_place_list() called by wp_ajax hooks: {'saswp_get_platform_place_list'} **/
/** No params detected :-/ **/


/** Function saswp_import_plugin_data() called by wp_ajax hooks: {'saswp_import_plugin_data'} **/
/** Parameters found in function saswp_import_plugin_data(): {"get": ["saswp_security_nonce", "plugin_name"]} **/
function saswp_import_plugin_data() {                  
    
        if ( ! current_user_can( saswp_current_user_can() ) ) {
             return;
        }
        
        if ( ! isset( $_GET['saswp_security_nonce'] ) ){
           return; 
        }
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
        if ( !wp_verify_nonce( $_GET['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
           return;  
        }    
        
        $plugin_name   = isset( $_GET['plugin_name'] ) ? sanitize_text_field( wp_unslash( $_GET['plugin_name'] ) ) : '';         
        $result        = '';
        
        switch ($plugin_name) {
            
            case 'schema':
                if ( is_plugin_active('schema/schema.php')) {
                    $result = saswp_import_schema_plugin_data();      
                }                
                break;
                
            case 'schema_pro':                
                if ( is_plugin_active('wp-schema-pro/wp-schema-pro.php')) {
                    $result = saswp_import_schema_pro_plugin_data();      
                }                
                break;
            case 'wp_seo_schema':                
                if ( is_plugin_active('wp-seo-structured-data-schema/wp-seo-structured-data-schema.php')) {
                    $result = saswp_import_wp_seo_schema_plugin_data();      
                }
                 break;
            case 'seo_pressor':                
                if ( is_plugin_active('seo-pressor/seo-pressor.php')) {
                    $result = saswp_import_seo_pressor_plugin_data();      
                }                
                break;
           case 'wpsso_core':                
                if ( is_plugin_active('wpsso/wpsso.php') && is_plugin_active('wpsso-schema-json-ld/wpsso-schema-json-ld.php')) {
                    $result = saswp_import_wpsso_core_plugin_data();      
                }                
                break;
            case 'aiors':                
                if ( is_plugin_active('all-in-one-schemaorg-rich-snippets/index.php')) {
                    $result = saswp_import_aiors_plugin_data();      
                }                
                break;   
                
                case 'wp_custom_rv':                
                if ( is_plugin_active('wp-customer-reviews/wp-customer-reviews-3.php')) {
                    $result = saswp_import_wp_custom_rv_plugin_data();      
                }                
                break; 

                case 'starsrating':       
                      
                  if ( is_plugin_active('stars-rating/stars-rating.php')) {                      
                      update_option('saswp_imported_starsrating', 1);
                      $result = 'updated';
                  }                
                break; 
                
                case 'schema_for_faqs':                
                  if ( is_plugin_active('faq-schema-markup-faq-structured-data/schema-for-faqs.php')) {
                      $result = saswp_import_schema_for_faqs_plugin_data();      
                  }                
                break;

                case 'yoast_seo':                
                  if ( is_plugin_active('wordpress-seo/wp-seo.php')) {
                      $result = saswp_import_yoast_seo_plugin_data();      
                  }                
                break;                 

            default:
                break;
        }                             
        if($result){
            
             echo wp_json_encode(array('status'=>'t', 'message'=>esc_html__( 'Data has been imported succeessfully', 'schema-and-structured-data-for-wp' )));            
             
        }else{
            
            echo wp_json_encode(array('status'=>'f', 'message'=>esc_html__( 'Plugin data is not available or it is not activated', 'schema-and-structured-data-for-wp' )));            
        
        }        
           wp_die();           
}


/** Function saswp_feeback_remindme() called by wp_ajax hooks: {'saswp_feeback_remindme'} **/
/** Parameters found in function saswp_feeback_remindme(): {"get": ["saswp_security_nonce"]} **/
function saswp_feeback_remindme() {  
    
        if ( ! current_user_can( saswp_current_user_can() ) ) {
             return;
        }
        if ( ! isset( $_GET['saswp_security_nonce'] ) ){
           return; 
        }
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
        if ( !wp_verify_nonce( $_GET['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
           return;  
        }
    
        $result = update_option( "saswp_activation_date", gmdate("Y-m-d"));   
        
        if($result){
            
            echo wp_json_encode(array('status'=>'t'));            
        
        }else{
            
            echo wp_json_encode(array('status'=>'f'));            
        
        }        
        wp_die();           
}


/** Function saswp_get_schema_dynamic_fields_ajax() called by wp_ajax hooks: {'saswp_get_schema_dynamic_fields_ajax'} **/
/** Parameters found in function saswp_get_schema_dynamic_fields_ajax(): {"get": ["saswp_security_nonce", "schema_type", "meta_name"]} **/
function saswp_get_schema_dynamic_fields_ajax() {
        
            if ( ! isset( $_GET['saswp_security_nonce'] ) ){
                return; 
            }
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
            if ( !wp_verify_nonce( $_GET['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
               return;  
            }
            if ( ! current_user_can( saswp_current_user_can() ) ) {
                die( '-1' );    
            }
            $meta_name   = '';
            $meta_array  = array();            
            $schema_type = '';
                        
            if ( isset( $_GET['schema_type']) ) {
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason post data is just used here so there is no necessary of unslash
                $schema_type = sanitize_text_field( $_GET['schema_type'] );
            }              
            if ( isset( $_GET['meta_name']) ) {  
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason post data is just used here so there is no necessary of unslash
                $meta_name = sanitize_text_field($_GET['meta_name']);  
                $property_fields = $this->_common_view->get_properties_and_repeater_fields();                   
                if( $meta_name == 'itemlist_item' || $meta_name == 'collection_page_item' ) {
                    
                    $itemval = $property_fields['_meta_name'][$meta_name][$schema_type];

                     // $itemval     = $this->_common_view->_meta_name[$meta_name][$schema_type];                     
                     if($itemval){
                         
                         foreach( $itemval as $key => $val){
                             $itemval[$key]['name'] = $val['id'];
                             unset($itemval[$key]['id']);
                         }
                         
                     }
                     
                     $meta_array  = $itemval;                                               
                }else{
                     
                     $meta_array = $property_fields['_meta_name'][$meta_name];         
                }                                                           
            }           
            if ( ! empty( $meta_array) ) {
             echo wp_json_encode( $meta_array );   
            }            
            wp_die();
        }


/** Function saswp_rf_review_edit() called by wp_ajax hooks: {'saswp_rf_review_edit'} **/
/** Parameters found in function saswp_rf_review_edit(): {"post": ["saswp_rf_form_nonce", "comment_ID", "comment"]} **/
function saswp_rf_review_edit() {
		
		if ( ! isset( $_POST['saswp_rf_form_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['saswp_rf_form_nonce'], 'saswp_rf_form_action_nonce') ) {
			return;
		}

		$comment_id = absint($_POST['comment_ID']);
		if ( ! current_user_can( 'edit_comment', $comment_id ) ){
			$comment = get_comment( $comment_id );
            if( get_current_user_id() != $comment->user_id ){
	            return ;
            }
        }

        $this->save_comment_meta( $comment_id );

		if ( isset( $_POST['comment'] ) ) {
			// comment data
			$commentarr = [
				'comment_ID'      => intval( $comment_id ),
				'comment_content' => sanitize_text_field( $_POST['comment'] ),
			];
			// update data in the database
			$updated = wp_update_comment( $commentarr );
		}

		wp_send_json_success();

	}


/** Function saswp_get_manual_fields_on_ajax() called by wp_ajax hooks: {'saswp_get_manual_fields_on_ajax'} **/
/** Parameters found in function saswp_get_manual_fields_on_ajax(): {"get": ["saswp_security_nonce", "post_id", "schema_type"]} **/
function saswp_get_manual_fields_on_ajax() {
    
            if ( ! isset( $_GET['saswp_security_nonce'] ) ){
                return; 
            }
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
            if ( !wp_verify_nonce( $_GET['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
               return;  
            } 
            if(!current_user_can( saswp_current_user_can()) ) {
                die( '-1' );    
            }
            $output_escaped      = '';
            $post_id     = isset( $_GET['post_id'] ) ? intval( $_GET['post_id'] ) : '';
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason get data is just used here so there is no necessary of unslash
            $schema_type = isset( $_GET['schema_type'] ) ? sanitize_text_field( $_GET['schema_type'] ) : '';
        
            $common_obj = new SASWP_View_Common();

            $schema_fields = saswp_get_fields_by_schema_type($post_id, null, $schema_type, 'manual');
            
            $output_escaped = $common_obj->saswp_post_specific_schema($schema_type, $schema_fields, $post_id, $post_id, null, null, 1);
            //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped	-- fetch data is already fully escaped
            echo $output_escaped;

            wp_die();        
}


/** Function saswp_fetch_google_reviews() called by wp_ajax hooks: {'saswp_fetch_google_reviews'} **/
/** Parameters found in function saswp_fetch_google_reviews(): {"post": ["saswp_security_nonce", "reviews_api", "reviews_api_status", "location", "language", "g_api", "premium_status", "blocks"]} **/
function saswp_fetch_google_reviews() {
                
                if ( ! current_user_can( saswp_current_user_can() ) ) {
                    return;
                }
        
                if ( ! isset( $_POST['saswp_security_nonce'] ) ){
                    return; 
                }
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
                if ( !wp_verify_nonce( $_POST['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
                   return;  
                }
                
                global $sd_data;
                
                $location  = $blocks = $premium_status = $g_api = $reviews_api = $reviews_api_status = $language = '';
                
                if ( isset( $_POST['reviews_api']) ) {
                    $reviews_api = sanitize_text_field( wp_unslash( $_POST['reviews_api'] ) );
                }
                
                if ( isset( $_POST['reviews_api_status']) ) {
                    $reviews_api_status = sanitize_text_field( wp_unslash( $_POST['reviews_api_status'] ) );
                }
                                
                if ( isset( $_POST['location']) ) {
                    $location = sanitize_text_field( wp_unslash( $_POST['location'] ) );
                }
                if ( isset( $_POST['language']) ) {
                    $language = sanitize_text_field( wp_unslash( $_POST['language'] ) );
                }
                
                if ( isset( $_POST['g_api']) ) {                    
                    $g_api = sanitize_text_field( wp_unslash( $_POST['g_api'] ) );                                        
                }
                
                if ( isset( $_POST['premium_status']) ) {
                    $premium_status = sanitize_text_field( wp_unslash( $_POST['premium_status'] ) );
                }
                
                if ( isset( $_POST['blocks']) ) {
                    $blocks = intval($_POST['blocks']);
                }
                                                
                if($location){
                    
                   if ( isset( $sd_data['saswp_reviews_location_name']) ) {
                          
                       if(!in_array($location, $sd_data['saswp_reviews_location_name']) ) {
                           array_push($sd_data['saswp_reviews_location_name'], $location);                       
                       }
                                              
                   }else{
                       $sd_data['saswp_reviews_location_name'] = array($location);  
                       
                   }
                                      
                   if ( isset( $sd_data['saswp_reviews_location_blocks']) ) {
                          
                       if(!in_array($blocks, $sd_data['saswp_reviews_location_blocks']) ) {
                           array_push($sd_data['saswp_reviews_location_blocks'], $blocks);                       
                       }
                                              
                   }else{
                       
                           $sd_data['saswp_reviews_location_blocks'] = array($blocks);  
                       
                   }
                        
                  $sd_data['saswp-google-review']        = 1;
                  $sd_data['saswp_google_place_api_key'] = $g_api;
                  update_option('sd_data', $sd_data);    
                                    
                  $result         = null;                                    
                  $user_id        = get_option('reviews_addon_user_id');
                    
                  if($reviews_api){                       
                        
                      if($premium_status == 'premium'){
                        
                        if($reviews_api_status == 'active'){
                          
                            if($user_id){
                             
                                if ( function_exists( 'saswp_get_paid_reviews_data') ) {

                                $result = saswp_get_paid_reviews_data($location, $reviews_api, $user_id, $blocks); 

                                if($result['status'] && is_numeric($result['message']) ) {
                                    
                                    $rv_limits = get_option('reviews_addon_reviews_limits');
                                    
                                    $result['message'] = esc_html__( 'Reviews fetched', 'schema-and-structured-data-for-wp' ) .' : '. ($rv_limits - $result['message'] ). ', '. esc_html__( 'Remains Limit', 'schema-and-structured-data-for-wp' ) .' : '.$result['message'];                                    
                                    
                                    update_option('reviews_addon_reviews_limits', intval($result['message']));
                                }

                                }else{
                                    $result['status']  = false;
                                    $result['message'] = esc_html__( 'Reviews for schema plugin is not activated', 'schema-and-structured-data-for-wp' );
                                }
                                
                            }else{
                                $result['status']  = false;
                                $result['message'] = esc_html__( 'User is not register', 'schema-and-structured-data-for-wp' );
                            }                                                        
                            
                        }else{
                                $result['status']  = false;
                                $result['message'] = esc_html__( 'License key is not active', 'schema-and-structured-data-for-wp' );
                        }  
                                                  
                        
                      }else{
                          
                          if($g_api){
                                                                          
                             $result = $this->saswp_get_free_reviews_data($location, $g_api, $language);                                                                                                                                  
                             
                         }
                         
                      }
                                              
                  }else{
                      
                      if($g_api){
                                                                              
                          $result = $this->saswp_get_free_reviews_data($location, $g_api, $language);                                                                                                                                  
                      }                      
                      
                  }  
                                                             
                  echo wp_json_encode($result);
                    
                }else{
                    
                  echo wp_json_encode(array('status' => false, 'message' => esc_html__( 'Place id is empty', 'schema-and-structured-data-for-wp' ))); 
                  
                }
                
            wp_die();
        
    }


/** Function saswp_subscribe_to_news_letter() called by wp_ajax hooks: {'saswp_subscribe_to_news_letter'} **/
/** Parameters found in function saswp_subscribe_to_news_letter(): {"post": ["saswp_security_nonce", "name", "email", "website"]} **/
function saswp_subscribe_to_news_letter() {

                if( ! current_user_can( saswp_current_user_can()) ) {
                    die( '-1' );    
                }
                if ( ! isset( $_POST['saswp_security_nonce'] ) ){
                    return; 
                }
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
                if ( !wp_verify_nonce( $_POST['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
                   return;  
                }
                                
	        $name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
                $email   = isset( $_POST['email'] ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';
                $website = isset( $_POST['website'] ) ? sanitize_text_field( wp_unslash( $_POST['website'] ) ) : '';
                
                if($email){
                        
                    $api_url = 'http://magazine3.company/wp-json/api/central/email/subscribe';

		    $api_params = array(
		        'name'    => $name,
		        'email'   => $email,
		        'website' => $website,
		        'type'    => 'schema'
                    );                    
		    wp_remote_post( $api_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );                    

                }else{
                        echo esc_html__( 'Email id required', 'schema-and-structured-data-for-wp' );                        
                }                        

                wp_die();
        }


/** Function saswp_get_sub_business_ajax() called by wp_ajax hooks: {'saswp_get_sub_business_ajax'} **/
/** Parameters found in function saswp_get_sub_business_ajax(): {"get": ["saswp_security_nonce", "business_type"]} **/
function saswp_get_sub_business_ajax() {
            
            if ( ! isset( $_GET['saswp_security_nonce'] ) ){
                return; 
            }
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
            if ( !wp_verify_nonce( $_GET['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
               return;  
            } 
            if(!current_user_can( saswp_current_user_can()) ) {
                die( '-1' );    
            }
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason post data is just used here so there is no necessary of unslash
            $business_type = isset( $_GET['business_type'] ) ? sanitize_text_field( $_GET['business_type'] ) : '';
                                       
            $response = $this->_local_sub_business[$business_type]; 
            
           if($response){                              
              echo wp_json_encode(array('status'=>'t', 'result'=>$response)); 
           }else{
              echo wp_json_encode(array('status'=>'f', 'result'=>'data not available')); 
           }
            wp_die();
        }


/** Function saswp_add_to_collection() called by wp_ajax hooks: {'saswp_add_to_collection'} **/
/** Parameters found in function saswp_add_to_collection(): {"get": ["saswp_security_nonce", "platform_id", "rvcount", "offsetCount", "reviews_ids", "review_id", "platform_place"]} **/
function saswp_add_to_collection() {
                        
            if ( ! isset( $_GET['saswp_security_nonce'] ) ){
                return; 
            }
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
            if ( !wp_verify_nonce( $_GET['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
               return;  
            }
            if(!current_user_can( saswp_current_user_can()) ) {
                die( '-1' );    
            }
            $platform_id = isset($_GET['platform_id'])?intval($_GET['platform_id']):'';
            $rvcount     = isset($_GET['rvcount'])?intval($_GET['rvcount']):'';
            $offset      = null;
            if ( ! empty( $_GET['offsetCount'] ) ) {
                $offset     =   intval( $_GET['offsetCount'] );    
            }
            $review_id   = ''; 
            $attr        = array();

            if ( isset( $_GET['reviews_ids']) && $_GET['reviews_ids'] != '' ) {
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized --Reason Server data is just used here so there is no necessary of unslash
                $attr['in'] = json_decode($_GET['reviews_ids']);
            }

            if ( isset( $_GET['review_id']) && $_GET['review_id'] != '' ) {
                $review_id   = intval($_GET['review_id']);
                $attr['in'] = array($review_id);
            }
                      
            if ( isset( $_GET['platform_place']) && !empty($_GET['platform_place']) ) {
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason Server data is just used here so there is no necessary of unslash
                $platform_place = sanitize_text_field($_GET['platform_place']);
            }          

            if( $platform_id ||  isset($attr['in']) ){
            $reviews_list = $this->_service->saswp_get_reviews_list_by_parameters($attr, $platform_id, $rvcount, null, $offset, null, null, $platform_place); 
             
            if($reviews_list){
                
                echo wp_json_encode(array('status' => true, 'message'=> $reviews_list));
                                                  
            }else{
                
                echo wp_json_encode(array('status' => false, 'message'=> 'Data not found'));
                
            }
                                         
            }else{
                
                echo wp_json_encode(array('status' => false, 'message'=> 'Platform id or review count is missing'));
                
            }
                        
            wp_die();
        }


/** Function saswp_reset_all_settings() called by wp_ajax hooks: {'saswp_reset_all_settings'} **/
/** Parameters found in function saswp_reset_all_settings(): {"post": ["saswp_security_nonce"]} **/
function saswp_reset_all_settings() {   
    
        if ( ! current_user_can( saswp_current_user_can() ) ) {
             return;
        }
        
        if ( ! isset( $_POST['saswp_security_nonce'] ) ){
           return; 
        }
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
        if ( !wp_verify_nonce( $_POST['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
           return;  
        }
        
        $result = '';
        
        update_option( 'sd_data', array());  
        
        $allposts= get_posts( array('post_type'=>'saswp','numberposts'=>-1) );
        
        foreach ( $allposts as $eachpost) {
            
            $result = wp_delete_post( $eachpost->ID);
        
        }
                        
        if($result){
            echo wp_json_encode(array('status'=>'t'));            
        }else{
            echo wp_json_encode(array('status'=>'f'));            
        }
        wp_cache_flush();        
        wp_die();           
}


/** Function saswp_add_reviews_to_select2() called by wp_ajax hooks: {'saswp_add_reviews_to_select2'} **/
/** Parameters found in function saswp_add_reviews_to_select2(): {"get": ["saswp_security_nonce", "platform_id", "q"]} **/
function saswp_add_reviews_to_select2() {
                        
            if ( ! isset( $_GET['saswp_security_nonce'] ) ){
                return; 
            }
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
            if ( !wp_verify_nonce( $_GET['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
               return;  
            }
            if(!current_user_can( saswp_current_user_can()) ) {
                die( '-1' );    
            }
            $platform_id = isset( $_GET['platform_id'] ) ? intval( $_GET['platform_id'] ) : '';
                         
            $attr        = array();

            if ( isset( $_GET['q']) && $_GET['q'] != '' ) {
                $attr['q'] = sanitize_text_field( wp_unslash( $_GET['q'] ) );
            }            
                        
            if( $platform_id ){
                                                     
                $reviews_list = $this->_service->saswp_get_reviews_list_by_parameters($attr, $platform_id); 
                $reviews_data = array();
                if ( ! empty( $reviews_list) ) {
                    foreach ( $reviews_list as $value) {
                        $reviews_data[] = array(
                            'id'   => $value['saswp_review_id'],
                            'text' => $value['saswp_reviewer_name'],
                        );
                    }
                }
             
            if($reviews_data){
                
                echo wp_json_encode(array('status' => true, 'message'=> $reviews_data));
                                                  
            }else{
                
                echo wp_json_encode(array('status' => false, 'message'=> 'Data not found'));
                
            }
                                         
            }else{
                
                echo wp_json_encode(array('status' => false, 'message'=> 'Platform id is missing'));
                
            }
                        
            wp_die();
        }


/** Function saswp_download_csv_review_format() called by wp_ajax hooks: {'saswp_download_csv_review_format'} **/
/** Parameters found in function saswp_download_csv_review_format(): {"get": ["_wpnonce"]} **/
function saswp_download_csv_review_format() {

        if ( ! current_user_can( saswp_current_user_can() ) ) {
            return;
        }
        if ( ! isset( $_GET['_wpnonce'] ) ){
                return; 
        }

        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
        if ( !wp_verify_nonce( $_GET['_wpnonce'], '_wpnonce' ) ){
                return;  
        }
                                                                          
       header('Content-Type: text/csv; charset=utf-8');
       header('Content-disposition: attachment; filename=reviewscsv.csv');
       echo "Author, Author Url, Author Image, Date, Time, Rating, Title, Text, Platform, Language, Source Url/ Place ID";   
                                     
       wp_die();


    }


/** Function saswp_expired_license_transient() called by wp_ajax hooks: {'saswp_expired_license_transient'} **/
/** Parameters found in function saswp_expired_license_transient(): {"post": ["saswp_security_nonce"]} **/
function saswp_expired_license_transient() {
            if ( ! current_user_can( saswp_current_user_can() ) ) {
                 return;
            }
            if ( ! isset( $_POST['saswp_security_nonce'] ) ){
                 die( '-1' );  
            }
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
            if ( !wp_verify_nonce( $_POST['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
                 return;  
            }
            $transient_load =  'saswp_addons_expired_set_transient';
            $value_load =  'saswp_addons_expired_set_transient_value';
            $expiration_load =  3600 ;
            set_transient( $transient_load, $value_load, $expiration_load );
}


/** Function saswp_send_query_message() called by wp_ajax hooks: {'saswp_send_query_message'} **/
/** Parameters found in function saswp_send_query_message(): {"post": ["saswp_security_nonce", "message", "email", "premium_cus", "name"]} **/
function saswp_send_query_message() {   
        if(!current_user_can( saswp_current_user_can()) ) {
            die( '-1' );    
        }
        if ( ! isset( $_POST['saswp_security_nonce'] ) ){
           return; 
        }
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
        if ( !wp_verify_nonce( $_POST['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
           return;  
        }   
        $customer_type  = 'Are you a premium customer ? No';
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized --Reason Sanitization is done after validationg the value
        $message        = isset( $_POST['message'] ) ? saswp_sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : ''; 
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized --Reason Sanitization is done after validationg the value
        $email          = isset( $_POST['email'] ) ? saswp_sanitize_textarea_field( wp_unslash( $_POST['email'] ) ) : ''; 
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized --Reason Sanitization is done after validationg the value
        $premium_cus    = isset( $_POST['premium_cus'] ) ? saswp_sanitize_textarea_field( wp_unslash( $_POST['premium_cus'] ) ) : '';   
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized --Reason Sanitization is done after validationg the value
        $name           = isset( $_POST['name'] ) ? saswp_sanitize_textarea_field( wp_unslash( $_POST['name'] ) ) : '';   
                                
        if ( function_exists( 'wp_get_current_user') ) {

            $user           = wp_get_current_user();

            if($premium_cus == 'yes'){
              $customer_type  = 'Are you a premium customer ? Yes';
            }
         
            $message = '<p>Name: '.$name.'</p>' 
                 .'<p>'.$message.'</p><br><br>'
                 . $customer_type
                 . '<br><br>'.'Query from plugin support tab';
            
            $user_data  = $user->data;        
            $user_email = $user_data->user_email;     
            
            if($email){
                $user_email = $email;
            }            
            //php mailer variables        
            $sendto    = 'team@magazine3.in';
            $subject   = "Schema Customer Query";
            
            $headers[] = 'Content-Type: text/html; charset=UTF-8';
            $headers[] = 'From: '. esc_attr( $user_email);            
            $headers[] = 'Reply-To: ' . esc_attr( $user_email);
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


/** Function saswp_modify_schema_post_enable() called by wp_ajax hooks: {'saswp_modify_schema_post_enable'} **/
/** Parameters found in function saswp_modify_schema_post_enable(): {"get": ["saswp_security_nonce", "post_id", "schema_id"]} **/
function saswp_modify_schema_post_enable() {
            
            if ( ! isset( $_GET['saswp_security_nonce'] ) ){
                return; 
            }
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
            if ( !wp_verify_nonce( $_GET['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
               return;  
            } 
            if( ! current_user_can( saswp_current_user_can() ) ) {
                die( '-1' );    
            } 
            
             $post_id        = isset($_GET['post_id'])?intval($_GET['post_id']):'';             
             $schema_id      = isset($_GET['schema_id'])?intval($_GET['schema_id']):'';
             $modify_this    = 1;
             $disabled       = '';
             $modified       = false;
             $is_post_specific  = 'yes';
             
             saswp_update_post_meta($post_id, 'saswp_modify_this_schema_'.$schema_id, 1); 
             $schema_type       = get_post_meta($schema_id, 'schema_type', true); 
             $response = $this->saswp_get_schema_fields_on_ajax($post_id, $schema_id);                                            
             $saswp_meta_fields = array_filter($response); 
             
             $output            = $this->_common_view->saswp_post_specific_schema($schema_type, $saswp_meta_fields, $post_id, $schema_id, null, $disabled, $modify_this, $modified, $is_post_specific ); 

             if ( $schema_type == 'Review' || $schema_type == 'ReviewNewsArticle' || $schema_type == 'CriticReview' ) {
                        
                $item_reviewed     = saswp_get_post_meta($post_id, 'saswp_review_item_reviewed_'.$schema_id, true);                         
                if(!$item_reviewed){
                    $item_reviewed = 'Book';
                }
                $response = $this->saswp_get_schema_fields_on_ajax($post_id, $schema_id, $item_reviewed);                                                                
                $saswp_meta_fields = array_filter($response);                           
                $output           .= $this->_common_view->saswp_post_specific_schema($schema_type, $saswp_meta_fields, $post_id, $schema_id ,$item_reviewed, $disabled, $modify_this, $modified, $is_post_specific);
                
            }
            //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped	-- fetch data is already fully escaped                                
             echo $output;
                                               
             wp_die();
             
            }


/** Function saswp_ajax_fetch_ai_models() called by wp_ajax hooks: {'saswp_fetch_ai_models'} **/
/** Parameters found in function saswp_ajax_fetch_ai_models(): {"post": ["provider", "api_key"]} **/
function saswp_ajax_fetch_ai_models() {
    check_ajax_referer('saswp_ajax_check_nonce', 'saswp_security_nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('error' => esc_html__('Unauthorized capability.', 'schema-and-structured-data-for-wp')));
    }

    $provider = isset($_POST['provider']) ? sanitize_text_field($_POST['provider']) : '';
    $api_key  = isset($_POST['api_key']) ? sanitize_text_field($_POST['api_key']) : '';

    if (empty($provider) || empty($api_key)) {
        wp_send_json_error(array('error' => esc_html__('Provider and API key are required to fetch models.', 'schema-and-structured-data-for-wp')));
    }

    $result = SASWP_AI_Service::fetch_models($provider, $api_key);

    if (!$result['success']) {
        wp_send_json_error(array('error' => $result['error']));
    }

    wp_send_json_success(array('models' => $result['models']));
}


/** Function saswp_review_helpful() called by wp_ajax hooks: {'saswp_rf_template_review_helpful'} **/
/** Parameters found in function saswp_review_helpful(): {"post": ["saswp_rf_form_nonce"], "request": ["comment_id", "type"]} **/
function saswp_review_helpful() {

		if ( ! isset( $_POST['saswp_rf_form_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['saswp_rf_form_nonce'], 'saswp_rf_form_action_nonce') ) {
			return;
		}

		if ( is_user_logged_in() ) {
			$comment_id   = isset( $_REQUEST['comment_id'] ) ? absint( $_REQUEST['comment_id'] ) : null;
			$helpful_type = ( isset( $_REQUEST['type'] ) && $_REQUEST['type'] == 'like' ) ? 'like' : 'dislike';

			if ( $comment_id ) {
				$current_user = wp_get_current_user();
				$user_id      = $current_user->ID;

				$old_helpful = get_comment_meta( $comment_id, 'saswp_rf_form_helpful_' . $helpful_type, true );
				$old_helpful = isset( $old_helpful ) ? $old_helpful : '';
				if ( $old_helpful ) {
					if (! in_array( $user_id, $old_helpful ) ) {
						$old_helpful[] = $user_id;
						update_comment_meta( $comment_id, 'saswp_rf_form_helpful_' . $helpful_type, $old_helpful );
					} else {
						if ( ( $key = array_search( $user_id, $old_helpful ) ) !== false ) {
							unset( $old_helpful[$key] );
						}
						update_comment_meta($comment_id, 'saswp_rf_form_helpful_' . $helpful_type, $old_helpful);
					}
				} else {
					$new_helpful   = [];
					$new_helpful[] = $user_id;
					update_comment_meta( $comment_id, 'saswp_rf_form_helpful_' . $helpful_type, $new_helpful );
				}

				//decrement
				$decrement_type = ( $helpful_type == 'like' ) ? 'dislike' : 'like';
				$decrement      = get_comment_meta( $comment_id, 'saswp_rf_form_helpful_' . $decrement_type, true );
				$decrement      = isset( $decrement ) ? $decrement : '';
				if ( $decrement ) {
					if ( in_array( $user_id, $decrement ) ) {
						if ( ( $key = array_search( $user_id, $decrement ) ) !== false ) {
							unset( $decrement[$key] );
						}
						update_comment_meta( $comment_id, 'saswp_rf_form_helpful_' . $decrement_type, $decrement );
					}
				}
			}
		}

		$likes 				=	get_comment_meta( $comment_id, 'saswp_rf_form_helpful_like', true );
		$dislike 			=	get_comment_meta( $comment_id, 'saswp_rf_form_helpful_dislike', true );
		$likes_cnt 			=	0;
		$dislik_cnt 		=	0;
		if ( empty( $likes ) ) {
			$likes 			=	array();
		}
		if ( empty( $dislike ) ) {
			$dislike 		=	array();
		}
		if ( ! empty( $likes ) && is_array( $likes ) ) {
			$likes_cnt 		=	count( $likes );	
		}
		if ( ! empty( $dislike ) && is_array( $dislike ) ) {
			$dislik_cnt 		=	count( $dislike );	
		}

		wp_send_json_success(['likes' => $likes_cnt, 'dislikes' => $dislik_cnt ]);
	}


/** Function saswp_get_collection_platforms() called by wp_ajax hooks: {'saswp_get_collection_platforms'} **/
/** Parameters found in function saswp_get_collection_platforms(): {"get": ["saswp_security_nonce", "collection_id"]} **/
function saswp_get_collection_platforms() {
                        
            if ( ! isset( $_GET['saswp_security_nonce'] ) ){
                return; 
            }
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
            if ( !wp_verify_nonce( $_GET['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
               return;  
            }
            if(!current_user_can( saswp_current_user_can()) ) {
                die( '-1' );    
            }
            $collection_id = isset($_GET['collection_id'])?intval($_GET['collection_id']):'';            
            
            if($collection_id){
                
            $reviews_list = get_post_meta($collection_id, 'saswp_platform_ids', true);
             
            if($reviews_list){
                
                echo wp_json_encode(array('status' => true, 'message'=> $reviews_list));
                                                  
            }else{
                
                echo wp_json_encode(array('status' => false, 'message'=> 'Data not found'));
                
            }
                                         
            }else{
                
                echo wp_json_encode(array('status' => false, 'message'=> 'Collection id is missing'));
                
            }
                        
            wp_die();
        }


/** Function saswp_modify_schema_post_restore() called by wp_ajax hooks: {'saswp_modify_schema_post_restore'} **/
/** Parameters found in function saswp_modify_schema_post_restore(): {"post": ["saswp_security_nonce", "post_id", "schema_id"]} **/
function saswp_modify_schema_post_restore() {
            
            

            if ( ! isset( $_POST['saswp_security_nonce'] ) ){
                return; 
            }
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
            if ( !wp_verify_nonce( $_POST['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
               return;  
            }  
            if ( ! current_user_can( saswp_current_user_can()) ) {
                die( '-1' );    
            }
                            
                $post_id        = isset( $_POST['post_id']) ? intval( $_POST['post_id'] ):'';
                $schema_id      = isset( $_POST['schema_id']) ? intval( $_POST['schema_id'] ):'';            
             
                saswp_delete_post_meta( $post_id, 'saswp_modify_this_schema_'.$schema_id ); 

                $meta_field = saswp_get_fields_by_schema_type( $schema_id );
                
                if ( $meta_field){
                    foreach( $meta_field as $field ) {
                        saswp_delete_post_meta( $post_id, $field['id'] ); 
                    }
                }                             
                echo wp_json_encode( array( 'status'=> 't', 'msg'=> esc_html__( 'Schema has been restored', 'schema-and-structured-data-for-wp' )) );                
                wp_die();
             
            }


/** Function saswp_get_item_reviewed_fields() called by wp_ajax hooks: {'saswp_get_item_reviewed_fields'} **/
/** Parameters found in function saswp_get_item_reviewed_fields(): {"get": ["saswp_security_nonce", "item", "schema_id", "schema_type", "post_id", "modify_this"]} **/
function saswp_get_item_reviewed_fields() {

            if ( ! isset( $_GET['saswp_security_nonce'] ) ){
                return; 
            }
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
            if ( !wp_verify_nonce( $_GET['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
               return;  
            } 
            if( ! current_user_can( saswp_current_user_can() ) ) {
                die( '-1' );    
            }
            
            $output_escaped = '';
            $disabled       = '';
            
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason post data is just used here so there is no necessary of unslash
            $item_reviewed = isset( $_GET['item'] ) ? sanitize_text_field( $_GET['item'] ) : '';  
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason post data is just used here so there is no necessary of unslash
            $schema_id     = isset( $_GET['schema_id'] ) ? sanitize_text_field( $_GET['schema_id'] ) : '';
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason post data is just used here so there is no necessary of unslash
            $schema_type   = isset( $_GET['schema_type'] ) ? sanitize_text_field( $_GET['schema_type'] ) : '';
            $post_id       = isset( $_GET['post_id'] ) ? intval( $_GET['post_id'] ) : '';  
            $modify_this   = isset( $_GET['modify_this'] ) ? intval( $_GET['modify_this'] ) : '';
            
            $schema_enable     = get_post_meta($post_id, 'saswp_enable_disable_schema', true); 
                        
            if ( isset( $schema_enable[$schema_id]) && $schema_enable[$schema_id] == 0){                        
                        $disabled = 'checked';                         
            } 
            
            $response          = saswp_get_fields_by_schema_type($schema_id, null, $item_reviewed);                                                              
            $saswp_meta_fields = array_filter($response);                
            $output_escaped    = $this->_common_view->saswp_post_specific_schema($schema_type, $saswp_meta_fields, $post_id, $schema_id, $item_reviewed, $disabled, $modify_this); 
            //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped	-- fetch data is already fully escaped                                                     
            echo $output_escaped;

            wp_die();
        }


/** Function saswp_enable_disable_schema_on_post() called by wp_ajax hooks: {'saswp_enable_disable_schema_on_post'} **/
/** Parameters found in function saswp_enable_disable_schema_on_post(): {"post": ["saswp_security_nonce", "post_id", "schema_id", "status", "req_from"]} **/
function saswp_enable_disable_schema_on_post() {
            
                if ( ! isset( $_POST['saswp_security_nonce'] ) ){
                   return; 
                }
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
                if ( !wp_verify_nonce( $_POST['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
                   return;  
                } 
                if(!current_user_can( saswp_current_user_can()) ) {
                    die( '-1' );    
                }
                
                $schema_enable = array();
                $post_id       = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : '';
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason post data is just used here so there is no necessary of unslash
                $schema_id     = isset( $_POST['schema_id'] ) ? sanitize_text_field( $_POST['schema_id'] ) : '';
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason post data is just used here so there is no necessary of unslash
                $status        = isset( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : '';
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason post data is just used here so there is no necessary of unslash
                $req_from      = isset( $_POST['req_from'] ) ? sanitize_text_field( $_POST['req_from'] ) : '';
                            
                if($req_from == 'post'){
                    $schema_enable_status = get_post_meta($post_id, 'saswp_enable_disable_schema', true);  
                }
                
                if($req_from == 'taxonomy'){
                    $schema_enable_status = get_term_meta($post_id, 'saswp_enable_disable_schema', true);  
                }                   
                               
                if ( is_array( $schema_enable_status) ) {
                   
                    $schema_enable = $schema_enable_status;
                   
                }else{
                    
                    if($req_from == 'post'){
                        delete_post_meta($post_id, 'saswp_enable_disable_schema');
                    }
                    
                    if($req_from == 'taxonomy'){
                        delete_term_meta($post_id, 'saswp_enable_disable_schema');
                    }
                    
                } 
                                
                $schema_enable[$schema_id] = $status;   

                if($req_from == 'post'){
                    update_post_meta( $post_id, 'saswp_enable_disable_schema', $schema_enable);                   
                }
                
                if($req_from == 'taxonomy'){
                    update_term_meta( $post_id, 'saswp_enable_disable_schema', $schema_enable);                   
                }
                                                                
                echo wp_json_encode(array('status'=>'t'));
                wp_die();                        
                
        }


/** Function saswp_get_taxonomy_term_list() called by wp_ajax hooks: {'saswp_get_taxonomy_term_list'} **/
/** Parameters found in function saswp_get_taxonomy_term_list(): {"get": ["saswp_security_nonce"]} **/
function saswp_get_taxonomy_term_list() {
    
        if ( ! current_user_can( saswp_current_user_can() ) ) {
             return;
        }
        if ( ! isset( $_GET['saswp_security_nonce'] ) ){
           return; 
        }
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
        if ( !wp_verify_nonce( $_GET['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
           return;  
        }
        
        $choices    = array('all' => esc_html__( 'All' , 'schema-and-structured-data-for-wp' ));
        $taxonomies = saswp_post_taxonomy_generator();        
        $choices    = array_merge($choices, $taxonomies);                                          
        echo wp_json_encode($choices);
        
        wp_die();
}


/** Function saswp_feeback_no_thanks() called by wp_ajax hooks: {'saswp_feeback_no_thanks'} **/
/** Parameters found in function saswp_feeback_no_thanks(): {"get": ["saswp_security_nonce"]} **/
function saswp_feeback_no_thanks() {     
    
        if ( ! current_user_can( saswp_current_user_can() ) ) {
             return;
        }
        if ( ! isset( $_GET['saswp_security_nonce'] ) ){
           return; 
        }
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
        if ( !wp_verify_nonce( $_GET['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
           return;  
        }
        
        $result = update_option( "saswp_activation_never", 'never'); 
        
        if($result){
            
            echo wp_json_encode(array('status'=>'t'));            
            
        }else{
            
            echo wp_json_encode(array('status'=>'f'));            
            
        }   
        
        wp_die();           
}


/** Function saswp_remove_file() called by wp_ajax hooks: {'saswp_rf_form_remove_file', 'nopriv_saswp_rf_form_remove_file'} **/
/** Parameters found in function saswp_remove_file(): {"post": ["saswp_rf_form_nonce"], "request": ["attachment_id"]} **/
function saswp_remove_file() {

		if ( ! isset( $_POST['saswp_rf_form_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['saswp_rf_form_nonce'], 'saswp_rf_form_action_nonce') ) {
			return;
		}
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$attachment_id = isset( $_REQUEST['attachment_id'] ) ? absint( $_REQUEST['attachment_id'] ) : '';

		if ( ! current_user_can( 'delete_post', $attachment_id ) ) {
			return;
		}

		$deleted = wp_delete_attachment( $attachment_id );
		if ($deleted) {
			wp_send_json_success();
		} else {
			wp_send_json_error();
		}

	}


/** Function saswp_review_highlight() called by wp_ajax hooks: {'saswp_template_review_hightlight'} **/
/** Parameters found in function saswp_review_highlight(): {"post": ["saswp_rf_form_nonce"], "request": ["comment_id", "highlight"]} **/
function saswp_review_highlight() {

		if ( ! isset( $_POST['saswp_rf_form_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['saswp_rf_form_nonce'], 'saswp_rf_form_action_nonce') ) {
			return;
		}

		if ( current_user_can('administrator') ) {
			
			$comment_id = isset( $_REQUEST['comment_id'] ) ? absint( $_REQUEST['comment_id'] ) : null;
			$highlight = ( isset($_REQUEST['highlight'] ) && $_REQUEST['highlight'] == 'yes' ) ? 1 : 0;
			if ( $comment_id ) {
				update_comment_meta( $comment_id, 'saswp-rf-template-review-highlight', $highlight );
			}
		}
		wp_send_json_success();
	}


/** Function saswp_export_all_settings_and_schema() called by wp_ajax hooks: {'saswp_export_all_settings_and_schema'} **/
/** Parameters found in function saswp_export_all_settings_and_schema(): {"get": ["_wpnonce"]} **/
function saswp_export_all_settings_and_schema() {   
        
                if ( ! current_user_can( saswp_current_user_can() ) ) {
                     return;
                }
                if ( ! isset( $_GET['_wpnonce'] ) ){
                     return; 
                }

                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
                if ( !wp_verify_nonce( $_GET['_wpnonce'], '_wpnonce' ) ){
                     return;  
                }
        
                $post_type = array('saswp_reviews', 'saswp', 'saswp-collections');
                $export_data_all   = array(); 
                
                foreach( $post_type as $type){
                    
                    $export_data       = array();                

                    $all_schema_post = get_posts(

                        array(
                                'post_type' 	     => $type,                                                                                   
                                'posts_per_page'     => -1,   
                                'post_status'        => 'any',
                        )

                     );                        

                    if($all_schema_post){
                    
                        foreach( $all_schema_post as $schema){    

                        $export_data[$schema->ID]['post']      = (array)$schema;                    
                        $post_meta                             = get_post_meta($schema->ID);    

                        if ( ! empty( $post_meta) ) {

                            foreach ( $post_meta as $key => $meta){

                                if(@unserialize($meta[0]) !== false){
                                    $post_meta[$key] = unserialize($meta[0]);
                                }else{
                                    $post_meta[$key] = $meta[0];
                                }

                            }

                        }

                        $export_data[$schema->ID]['post_meta'] = $post_meta;  

                        }       

                      $export_data_all['posts'][$type] = $export_data;    
                        
                    }
                                        
                    
                }
                
                $export_data_all['sd_data']         = get_option('sd_data');
                
                header('Content-type: application/json');
                header('Content-disposition: attachment; filename=structuredatabackup.json');
                echo wp_json_encode($export_data_all);   
                                              
                wp_die();
    }


/** Function saswp_validate_schema_template_attr() called by wp_ajax hooks: {'saswp_validate_schema_template_attr'} **/
/** Parameters found in function saswp_validate_schema_template_attr(): {"post": ["saswp_security_nonce", "schema_type", "field_name"]} **/
function saswp_validate_schema_template_attr(){
	
	$html_escaped 	=	'';
	$flag 			=	0;

	if ( ! isset( $_POST['saswp_security_nonce'] ) ){
        return; 
    }
    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
    if ( !wp_verify_nonce( $_POST['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
       return;  
    } 
    if(!current_user_can( saswp_current_user_can()) ) {
        die( '-1' );    
    }

    if ( isset( $_POST['schema_type'] ) && isset( $_POST['field_name'] ) ) {
    	
    	// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason post data is just used here so there is no necessary of unslash
    	$schema_type 	=	sanitize_text_field( $_POST['schema_type'] );
    	// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason post data is just used here so there is no necessary of unslash
    	$field_name 	=	sanitize_text_field( $_POST['field_name'] );

		$meta_fields 	=	$meta_field = saswp_get_fields_by_schema_type( null, null, $schema_type, 'manual' );

		if ( ! empty( $meta_fields ) && is_array( $meta_fields ) ) {
			foreach ($meta_fields as $mf_key => $meta) {
				if ( ! empty( $meta ) && is_array( $meta ) && ! empty( $meta['id'] ) ) {
					$id 	=	trim( $meta['id'], '_' );
					if ( $id == $field_name && isset( $meta['is_template_attr'] ) ) {
						$flag 	=	1;
					}	
				}
			}
		}

	}

	if ( $flag == 1 ) {
		
		$schema_template 	=	saswp_get_schema_template_meta_list();
		$html_escaped 		.=	'<optgroup label="'.esc_attr( $schema_template['label'] ).'">';
		foreach ( $schema_template['meta-list'] as $key => $value ) {
			$html_escaped 	.=	'<option value="'.esc_attr( $key ).'">'.esc_html($value).'</option>';	
		}
		$html_escaped 		.=	'</optgroup>';
					
	}

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped --Reason Escaping is done above.
	echo $html_escaped;
	wp_die();

}


/** Function saswp_get_reviews_on_load() called by wp_ajax hooks: {'saswp_get_reviews_on_load'} **/
/** Parameters found in function saswp_get_reviews_on_load(): {"get": ["saswp_security_nonce", "offset", "paged", "data_type"]} **/
function saswp_get_reviews_on_load() {
            
            if ( ! isset( $_GET['saswp_security_nonce'] ) ){
                return; 
            }
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
            if ( !wp_verify_nonce( $_GET['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
               return;  
            }
            if(!current_user_can( saswp_current_user_can()) ) {
                die( '-1' );    
            }
            $reviews    = array();
            $offset     = isset( $_GET['offset'] ) ? intval( $_GET['offset'] ) : '';
            $paged      = isset( $_GET['paged'] ) ? intval( $_GET['paged'] ) : '';
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason post data is just used here so there is no necessary of unslash
            $data_type  = isset( $_GET['data_type'] ) ? sanitize_text_field( $_GET['data_type'] ) : '';
            
            if($paged && $offset){
                
                $reviews_service = new SASWP_Reviews_Service();  
                
                if($data_type == 'review'){
                                                                      
                    $reviews = $reviews_service->saswp_get_reviews_list_by_parameters(null, null, 10, $paged, $offset);
                }
                
                if($data_type == 'collection'){
                    
                    $collection  = $reviews_service->saswp_get_collection_list(10, $paged, $offset);
                    
                    if($collection){
                        
                        foreach( $collection as $col){
                            
                            $reviews[] = array(
                                'saswp_review_id'     => $col['value'],
                                'saswp_reviewer_name' => $col['label']
                            );
                            
                        }
                    }
                    
                }
                
                if($reviews){
                    echo wp_json_encode(array('status' => 't', 'result' => $reviews));
                }else{
                    echo wp_json_encode(array('status' => 't', 'message' => 'Reviews not found'));
                }
                
            }else{
                echo wp_json_encode(array('status' => 'f', 'message' => 'Page number or offset is missing'));
            }
        wp_die();        
}


/** Function saswp_add_new_save_steps_data() called by wp_ajax hooks: {'saswp_add_new_save_steps_data'} **/
/** Parameters found in function saswp_add_new_save_steps_data(): {"post": ["wpnonce", "schema_type", "saswp_review_item_reviewed_", "data_group_array", "saswp_post_id"]} **/
function saswp_add_new_save_steps_data() {    
            	if(!current_user_can( saswp_current_user_can()) ) {
		            die( '-1' );    
		        }
                 if ( ! isset( $_POST['wpnonce'] ) ){
                    return; 
                 }
                 
                 // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
                 if ( !wp_verify_nonce( $_POST['wpnonce'], 'saswp_add_new_nonce' ) ){
                    return;  
                 }
                 
                if ( isset( $_POST['schema_type']) ) { 
                    
                    $schema_type = sanitize_text_field( wp_unslash( $_POST['schema_type'] ) );
                
                if($schema_type == 'local_business'){
                    
                    $schema_type = 'Local Business';  
                
                }
                
                $user_id = get_current_user_id();
                
                $schema_post = array(
                    'post_author' => intval($user_id),
                    'post_date'   => gmdate("Y-m-d"),                                        
                    'post_title'  => sanitize_text_field(ucfirst($schema_type)),                    
                    'post_status' => 'publish',                    
                    'post_name'   =>  sanitize_text_field(ucfirst($schema_type)),                    
                    'post_type'   => 'saswp',                                                            
                );
                
				$post_id = wp_insert_post($schema_post);  

				if($post_id){
					//Insert default placement.
					$post_data_array = array();                                       
      				$post_data_array['group-0'] =array(
                                      'data_array' => array(
                                                  array(
                                                  'key_1' => 'post_type',
                                                  'key_2' => 'equal',
                                                  'key_3' => 'post',
                                        )
                                      )               
									 );
			        update_post_meta( $post_id, 'data_group_array', $post_data_array);					
				}                                

                set_transient('saswp_last_post_id', wp_json_encode(array('post_id'=>$post_id))); 
                
				}    
				if ( isset( $_POST['saswp_review_item_reviewed_']) ) {
					update_post_meta(
						$post_id, 
						'saswp_review_item_reviewed_'.$post_id, 
						sanitize_text_field($_POST['saswp_review_item_reviewed_']) 
					  );                         					
				}
                                
                if ( isset( $_POST['data_group_array']) && isset($_POST['saswp_post_id']) ) {
                    
                $post_id                = intval($_POST['saswp_post_id']);    
                $post_data_group_array  = array();
                $temp_condition_array   = array();
                $show_globally          = false;
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
                $post_data_group_array  = (array) $_POST['data_group_array'];
                
                if ( is_array( $post_data_group_array) ) {
                
	                foreach( $post_data_group_array as $groups){  
	                    
	                    foreach( $groups['data_array'] as $group ){  
	                        
	                      if(array_search('show_globally', $group))
	                      {
	                          
	                        $temp_condition_array[0] =  $group;  
	                        $show_globally = true;  
	                        
	                      }
	                      
	                    }
	                }
	            }
                
                if($show_globally){
                    
                unset($post_data_group_array);
                
                $post_data_group_array['group-0']['data_array'] = $temp_condition_array;  
                
                }
                
                $post_data_group_array = saswp_sanitize_multi_array($post_data_group_array, 'data_array'); 
                
                update_post_meta(
                    $post_id, 
                    'data_group_array', 
                    $post_data_group_array 
                  );                         
                }                
		wp_send_json(
			array(
				'done' => 1,
				'message' => "Stored Successfully",
                                'post_id' => $post_id
			)
		);
                
	}


/** Function saswp_ajax_select_creator() called by wp_ajax hooks: {'create_ajax_select_sdwp'} **/
/** Parameters found in function saswp_ajax_select_creator(): {"server": ["REQUEST_METHOD"], "post": ["saswp_call_nonce", "id", "number", "group_number"]} **/
function saswp_ajax_select_creator($data = '', $saved_data= '', $current_number = '', $current_group_number ='') {
 
    $response = $data;
    $is_ajax = false;
    
    if( isset($_SERVER['REQUEST_METHOD']) &&  $_SERVER['REQUEST_METHOD']=='POST'){
        
        $is_ajax = true;

        if(!current_user_can( saswp_current_user_can()) ) {
          die( '-1' );    
        }
        if( ! isset( $_POST["saswp_call_nonce"] )) {
          die( '-1' );    
        }
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
        if(wp_verify_nonce($_POST["saswp_call_nonce"],'saswp_select_action_nonce') ) {
            
            if ( isset( $_POST["id"] ) ) {
              $response = sanitize_text_field(wp_unslash($_POST["id"]));
            }
            if ( isset( $_POST["number"] ) ) {
              // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason post data is just used here so there is no necessary of unslash
              $current_number   = intval(sanitize_text_field($_POST["number"]));
            }
            if ( isset( $_POST["group_number"] ) ) {
              // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason post data is just used here so there is no necessary of unslash
              $current_group_number   = intval(sanitize_text_field($_POST["group_number"]));
            }
            
        }else{
            
            exit;
            
        }
       
    }          
        // send the response back to the front end
       // vars
          if ( $response == 'date' || $response == 'url_parameter' ) {
            $input_class    = 'widefat ajax-output';
            if ( $response == 'date' ) {
              $input_class  = ' saswp-datepicker-picker';
            }
            $output = '<input value="'. esc_attr( $saved_data).'" type="text" data-type="'. esc_attr( $response).'"  class="'.esc_attr( $input_class ) .'" name="data_group_array[group-'. esc_attr( $current_group_number).'][data_array]['. esc_attr( $current_number) .'][key_3]"/>'; 
          }else{

          $choices       = array();    
          $saved_choices = array();

          $choices = saswp_get_condition_list($response);
          
          if($saved_data){            
            $saved_choices = saswp_get_condition_list($response, '', $saved_data);                        
          }
                               
          $output = '<select data-type="'. esc_attr( $response).'"  class="widefat ajax-output saswp-select2" name="data_group_array[group-'. esc_attr( $current_group_number).'][data_array]['. esc_attr( $current_number) .'][key_3]">'; 
          
          foreach ( $choices as $value) {              
           $output .= '<option value="' . esc_attr( $value['id']) .'"> ' .  esc_html( $value['text']) .'</option>';                     
          }
          
          if($saved_choices){
            foreach( $saved_choices as $value){
              $output .= '<option value="' . esc_attr( $value['id']) .'" selected> ' .  esc_html( $value['text']) .'</option>';                     
            }
          } 
        
         $output .= ' </select> ';   

          }
    
    

    $allowed_html = saswp_expanded_allowed_tags();
    echo wp_kses($output, $allowed_html); 
    
    if ( $is_ajax ) {
      die();
    }
// endif;  

}


/** Function saswp_pagination() called by wp_ajax hooks: {'nopriv_saswp_rf_template_pagination', 'saswp_rf_template_pagination'} **/
/** Parameters found in function saswp_pagination(): {"post": ["saswp_rf_form_nonce"], "request": ["sort_by", "filter_by", "current_page", "max_page"]} **/
function saswp_pagination() {

		if ( ! isset( $_POST['saswp_rf_form_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['saswp_rf_form_nonce'], 'saswp_rf_form_action_nonce') ) {
			return;
		}

		$sort_by   = isset( $_REQUEST['sort_by'] ) 		? sanitize_text_field( $_REQUEST['sort_by'] ) : '';
		$filter_by = isset( $_REQUEST['filter_by'] ) 	? sanitize_text_field( $_REQUEST['filter_by'] ) : '';
		$cur_page  = isset( $_REQUEST['current_page'] ) ? absint( $_REQUEST['current_page'] ) : 1;
		if ( $sort_by ) {
			$max_page = $this->get_sorted_reviews( $sort_by, $filter_by );
		} else {
			$max_page = isset( $_REQUEST['max_page'] ) ? absint( $_REQUEST['max_page'] ) : 1;
		}

		ob_start();
		$this->get_reviews( $sort_by, $filter_by );
		$review = ob_get_clean();

		$pagination = $this->paginate_comments_links( $cur_page, $max_page );

		wp_send_json_success( ['review' => $review, 'pagination' => $pagination] );
	}


/** Function saswp_image_upload() called by wp_ajax hooks: {'nopriv_saswp_rf_form_image_upload', 'saswp_rf_form_image_upload'} **/
/** Parameters found in function saswp_image_upload(): {"post": ["saswp_rf_form_nonce"]} **/
function saswp_image_upload() {

		if ( ! isset( $_POST['saswp_rf_form_nonce'] ) ) {
			wp_send_json_error( [ 'msg' => esc_html__( 'Invalid request.', 'schema-and-structured-data-for-wp' ) ] );
			return;
		}

		if ( ! wp_verify_nonce( $_POST['saswp_rf_form_nonce'], 'saswp_rf_form_action_nonce') ) {
			wp_send_json_error( [ 'msg' => esc_html__( 'Security check failed.', 'schema-and-structured-data-for-wp' ) ] );
			return;
		}

		// Improvement #3: Capability check — only users allowed to upload files may proceed.
	    if ( ! current_user_can( 'upload_files' ) ) {
	        wp_send_json_error( [ 'msg' => esc_html__( 'You do not have permission to upload files.', 'schema-and-structured-data-for-wp' ) ] );
	        return;
	    }

		$img_max_size = 1024;

		$file               = $_FILES['saswp-rf-form-image'];

		if ( empty( $file['name'] ) || empty( $file['tmp_name'] ) ) {
			wp_send_json_error( [ 'msg' => esc_html__( 'Please upload file.', 'schema-and-structured-data-for-wp' ) ] );
        	return;
    	}


		// Improvement #2: Explicit MIME-to-extension map used in both the
	    // wp_handle_upload override and the server-side type check below.
	    $allowed_mimes = [
	        'png'         => 'image/png',
	        'jpg|jpeg|jpe' => 'image/jpeg',
	        'gif'         => 'image/gif',
	    ];

		// Allowed file size -> 2MB
		$allowed_file_size = $img_max_size * 1024;

		// server-side inspection of the actual file contents.
	    $checked = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'], $allowed_mimes );

	    if ( empty( $checked['type'] ) || ! in_array( $checked['type'], array_values( $allowed_mimes ), true ) ) {
	        $valid_types    = implode( ', ', array_map( fn( $m ) => str_replace( 'image/', '', $m ), array_values( $allowed_mimes ) ) );
	        $detected_type  = ! empty( $checked['type'] ) ? str_replace( 'image/', '', $checked['type'] ) : esc_html__( 'unknown', 'schema-and-structured-data-for-wp' );

	        wp_send_json_error( [
	            'msg' => sprintf(
	                esc_html__( 'Invalid file type: %s. Supported file types: %s', 'schema-and-structured-data-for-wp' ),
	                $detected_type,
	                $valid_types
	            ),
	        ] );
	        return;
	    }

		// Check file size
		if ($file['size'] > $allowed_file_size) {
			wp_send_json_error(['msg' => sprintf(esc_html__('File is too large. Max. upload file size is %s', 'schema-and-structured-data-for-wp'), self::format_bytes($allowed_file_size))]);
		}

		if (! function_exists('wp_handle_upload')) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		$upload_overrides = [
	        'test_form' => false,
	        'mimes'     => $allowed_mimes,   // locks down accepted types at the WP layer
	    ];

		$uploaded         = wp_handle_upload($file, $upload_overrides);

		if ($uploaded && ! isset($uploaded['error'])) {
			$filename = $uploaded['file'];
			$filetype = wp_check_filetype(basename($filename), $allowed_mimes );

			$attach_id = wp_insert_attachment(
				[
					'guid'            => $uploaded['url'],
					'post_title'      => sanitize_text_field(preg_replace('/\.[^.]+$/', '', basename($filename))),
					'post_excerpt'    => '',
					'post_content'    => '',
					'post_mime_type'  => sanitize_text_field($filetype['type']),
					'post_status'     => 'reivew-inherit',
					'comment_status' => 'closed',
				],
				$uploaded['file'],
				0
			);

			$file_info = [];
			if (! is_wp_error($attach_id)) {
				if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
	                require_once ABSPATH . 'wp-admin/includes/image.php';
	            }

				wp_update_attachment_metadata($attach_id, wp_generate_attachment_metadata($attach_id, $filename));
				update_post_meta($attach_id, 'attach_type', 'review');

				$file_info = [
					'id'  => $attach_id,
					'url' => wp_get_attachment_image_url($attach_id, 'thumbnail'),
				];
			}

			wp_send_json_success(['file_info' => $file_info]);
		} else {
			/*
			 * Error generated by _wp_handle_upload()
			 * @see _wp_handle_upload() in wp-admin/includes/file.php
			 */
			wp_send_json_error( [ 'msg' => isset( $uploaded['error'] ) ? $uploaded['error'] : esc_html__( 'Upload failed.', 'schema-and-structured-data-for-wp' ) ] );
		}
		
	}


/** Function get_get_schema_templates() called by wp_ajax hooks: {'saswp_get_schema_templates'} **/
/** Parameters found in function get_get_schema_templates(): {"post": ["saswp_security_nonce"]} **/
function get_get_schema_templates(){

      if ( ! isset( $_POST['saswp_security_nonce'] ) ){
        return; 
      }
      // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
      if ( !wp_verify_nonce( $_POST['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
        return;  
      }
      if(!current_user_can( saswp_current_user_can()) ) {
        die( '-1' );    
      }
      
      $template_list    = array();
      $result           = array();

      $args = array(
          'post_type'      => $this->template_type,
          'posts_per_page' => -1,
          'post_status'    => 'publish',
      );

      $query = new WP_Query($args);

      if ($query->have_posts()) {
      
          while ($query->have_posts()) {
            
            $query->the_post();
            $template_list[]  = array(
                                  'id'    => get_the_ID(),
                                  'text'  => get_the_title(),
                                );

          }

          wp_reset_postdata();
      }

      if ( ! empty( $template_list ) ) {
        $result[] = array(
          'children' => $template_list,
        );
      }

      wp_send_json( $result );            
            
      wp_die();

    }


/** Function saswp_license_status_check() called by wp_ajax hooks: {'saswp_license_status_check'} **/
/** Parameters found in function saswp_license_status_check(): {"post": ["saswp_security_nonce", "add_on", "license_status", "license_key"]} **/
function saswp_license_status_check() {  
    
        if ( ! current_user_can( saswp_current_user_can() ) ) {
             return;
        }
        if ( ! isset( $_POST['saswp_security_nonce'] ) ){
             return; 
        }
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
        if ( !wp_verify_nonce( $_POST['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
             return;  
        }    
        
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason post data is just used here so there is no necessary of unslash
        $add_on           = isset($_POST['add_on'])?sanitize_text_field($_POST['add_on']):'';
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason post data is just used here so there is no necessary of unslash
        $license_status   = isset($_POST['license_status'])?sanitize_text_field($_POST['license_status']):'';
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash --Reason post data is just used here so there is no necessary of unslash
        $license_key      = isset($_POST['license_key'])?sanitize_text_field($_POST['license_key']):'';
        

        if($add_on && $license_status && $license_key){
            
          $result = saswp_license_status($add_on, $license_status, $license_key);
          
          echo wp_json_encode($result);
                        
        }          
                        
        wp_die();           
}


/** Function saswp_send_feedback() called by wp_ajax hooks: {'saswp_send_feedback'} **/
/** Parameters found in function saswp_send_feedback(): {"post": ["data"]} **/
function saswp_send_feedback() {
    if(!current_user_can( saswp_current_user_can()) ) {
        die( '-1' );    
    }
    // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Reason: We are just verifiying nonce below this lines.
    if( isset( $_POST['data'] ) ) {
        // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: We are just verifiying nonce below this lines.
        parse_str( $_POST['data'], $form );
    }
    if ( ! isset( $form['saswp_feedback_nonce'] ) ){
       return; 
    }
    if ( !wp_verify_nonce( $form['saswp_feedback_nonce'], 'saswp_feedback_nonce' ) ){
       return;  
    }

    $selected_reason = isset( $form['saswp_disable_reason'] ) ? $form['saswp_disable_reason'] : '';
    $reason_array = [ 'temporary', 'stopped', 'another plugin' ];
    if ( in_array( $selected_reason, $reason_array ) ) {
        wp_die();
    }  

    $text = '';
    if( isset( $form['saswp_disable_text'] ) ) {
        $text = implode( "\n\r", $form['saswp_disable_text'] );
    }

    $string_count   =   '';
    if ( function_exists( 'str_word_count' ) ) {
        $string_count   =   str_word_count( trim( $text ) );
    }

    if ( $string_count <= 2 ) {
        wp_die();    
    }

    $headers = array();

    $from = isset( $form['saswp_disable_from'] ) ? $form['saswp_disable_from'] : '';
    if( $from ) {
        $headers[] = "From: $from";
        $headers[] = "Reply-To: $from";
    }

    $subject = isset( $form['saswp_disable_reason'] ) ? $form['saswp_disable_reason'] : '(no reason given)';

    $subject = $subject.' - Schema & Structured Data for WP & AMP';

    if($subject == 'technical - Schema & Structured Data for WP & AMP'){

          $text = trim($text);

          if ( ! empty( $text) ) {

            $text = 'technical issue description: '.$text;

          }else{

            $text = 'no description: '.$text;
          }
      
    }

    $success = wp_mail( 'team@magazine3.in', $subject, $text, $headers );

    die();
}


/** Function saswp_get_meta_list() called by wp_ajax hooks: {'saswp_get_meta_list'} **/
/** No params detected :-/ **/


/** Function saswp_skip_wizard() called by wp_ajax hooks: {'saswp_skip_wizard'} **/
/** Parameters found in function saswp_skip_wizard(): {"post": ["saswp_security_nonce"]} **/
function saswp_skip_wizard() {                  
        if(!current_user_can( saswp_current_user_can()) ) {
            die( '-1' );    
        }
        if ( ! isset( $_POST['saswp_security_nonce'] ) ){
           return; 
        }
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: Nonce verification done here so unslash is not used.
        if ( !wp_verify_nonce( $_POST['saswp_security_nonce'], 'saswp_ajax_check_nonce' ) ){
           return;  
        }    
         
        $sd_data = get_option('sd_data');
        $sd_data['sd_initial_wizard_status'] = 0;
        update_option('sd_data', $sd_data);
        
        wp_die();           
}


/** Function saswp_self_video_popup() called by wp_ajax hooks: {'saswp_rf_form_self_video_popup', 'nopriv_saswp_rf_form_self_video_popup'} **/
/** Parameters found in function saswp_self_video_popup(): {"post": ["saswp_rf_form_nonce"], "request": ["video_url"]} **/
function saswp_self_video_popup() {
		
		if ( ! isset( $_POST['saswp_rf_form_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['saswp_rf_form_nonce'], 'saswp_rf_form_action_nonce') ) {
			return;
		}
		$video_url = isset($_REQUEST['video_url']) ? esc_url($_REQUEST['video_url']) : null;
		ob_start();

		echo '<div class="saswp-rf-modal">';
		echo '<div class="saswp-rf-form saswp-rf-review-popup">';
		echo '<div class="saswp-rf-form-self-video"><video src="' . $video_url . '"  autoplay controls /></div>';

		echo '</div>';
		echo '</div>'; //modal

		$edit_form = ob_get_clean();
		wp_send_json_success($edit_form);
	
	}


