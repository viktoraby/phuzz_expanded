<?php
/***
*
*Found actions: 39
*Found functions:28
*Extracted functions:22
*Total parameter names extracted: 24
*Overview: {'loadmore_data_handler': {'nopriv_loadmore_data_handler', 'loadmore_data_handler'}, 'save_onboarding_settings': {'embedpress_save_onboarding'}, 'ajax_view_notice': {'embedpress_view_feature_notice'}, 'assets/pdf-flip-book/viewer.html': {'get_flipbook_viewer', 'nopriv_get_flipbook_viewer'}, 'delete_instagram_account': {'delete_instagram_account'}, 'ajax_dismiss_notice': {'embedpress_dismiss_feature_notice'}, 'gutenberg_password_check': {'embedpress_gutenberg_password_check', 'nopriv_embedpress_gutenberg_password_check'}, 'get_instagram_userdata_ajax': {'get_instagram_userdata_ajax'}, 'sync_instagram_data_ajax': {'nopriv_sync_instagram_data_ajax', 'sync_instagram_data_ajax'}, 'lock_content_form_handler': {'nopriv_lock_content_form_handler', 'lock_content_form_handler'}, 'save_source_data': {'save_source_data'}, 'ajax_clear_cache': {'embedpress_clear_content_cache'}, 'dismiss_feature_notice': {'embedpress_dismiss_feature_notice'}, 'update_elements_list': {'embedpress_elements_action'}, 'dismiss_element': {'embedpress_dismiss_element'}, 'save_global_brand_image': {'save_global_brand_image'}, 'EmbedPress\\Includes\\Classes\\Pdf_Thumbnail_Handler': {'ep_upload_pdf_thumbnail', 'ep_generate_pdf_thumbnail'}, 'assets/pdf-flip-book/templates/book-view.html': {'nopriv_get_flipbook_template', 'get_flipbook_template'}, 'ajax_mark_milestone_seen': {'embedpress_mark_milestone_seen'}, 'ajax_skip_notice': {'embedpress_skip_feature_notice'}, 'install_site_performance': {'embedpress_install_site_performance'}, 'ajax_video_popup_description': {'fetch_video_description', 'nopriv_fetch_video_description'}, '\\\\EmbedPress\\\\Ends\\\\Back\\\\Handler': {'embedpress_notice_dismiss'}, 'wp_ajax_embedpress_get_embed_url_info': {'embedpress_do_ajax_request'}, 'youtube_rest_api': {'youtube_rest_api', 'nopriv_youtube_rest_api'}, 'ajax_get_calendar': {'epgc_ajax_get_calendar', 'nopriv_epgc_ajax_get_calendar'}, 'assets/pdf/web/viewer.html': {'nopriv_get_viewer', 'get_viewer'}, 'save_settings': {'embedpress_settings_action'}}
*
***/

/** Function loadmore_data_handler() called by wp_ajax hooks: {'nopriv_loadmore_data_handler', 'loadmore_data_handler'} **/
/** Parameters found in function loadmore_data_handler(): {"post": ["connected_account_type", "hashtag_id", "feed_type", "user_id", "loadmore_key", "_nonce", "params", "loaded_posts", "posts_per_page"]} **/
function loadmore_data_handler()
	{
		$connected_account_type = isset($_POST['connected_account_type']) ? sanitize_text_field($_POST['connected_account_type']) : 'personal';
		$hashtag_id = isset($_POST['hashtag_id']) ? sanitize_text_field($_POST['hashtag_id']) : '';
		$feed_type = isset($_POST['feed_type']) ? sanitize_text_field($_POST['feed_type']) : 'user_aacount_type';
		$user_id = isset($_POST['user_id']) ? sanitize_text_field($_POST['user_id']) : '';
		$loadmore_key = isset($_POST['loadmore_key']) ? sanitize_text_field($_POST['loadmore_key']) : '';
		$nonce = isset($_POST['_nonce']) ? sanitize_text_field($_POST['_nonce']) : '';
		$params = isset($_POST['params']) ? sanitize_text_field($_POST['params']) : '';
		// $params = json_decode($params, true);
		$params = stripslashes($params); // Remove extra backslashes
		$params = json_decode($params, true);

		// Verify nonce
		if (!wp_verify_nonce($nonce, 'ep_nonce')) {
			wp_send_json_error('Invalid nonce');
			wp_die(); // Terminate script execution if nonce is invalid
		}

		$feeds_data = get_option('ep_instagram_feed_data');
		$user_data = $feeds_data[$user_id]['feed_userinfo'];

		if ($feed_type == 'hashtag_type') {
			$feeds_data = get_option('ep_instagram_hashtag_feed');
		}


		$profile_picture_url = isset($user_data['profile_picture_url']) ? $user_data['profile_picture_url'] : '';

		if ($feed_type === 'user_account_type' && isset($feeds_data[$loadmore_key]['feed_posts'])) {
			$feed_posts = $feeds_data[$loadmore_key]['feed_posts'];
		} else if ($feed_type === 'hashtag_type' && isset($feeds_data[$loadmore_key])) {
			$feed_posts = $feeds_data[$loadmore_key];
		} else {
			$feed_posts = ['error'];
		}


		if (is_array($feed_posts) && count($feed_posts) > 0) {
			$loaded_posts = isset($_POST['loaded_posts']) ? intval($_POST['loaded_posts']) : 0;
			$posts_per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 0;

			$post_index = $loaded_posts + 1;
			$start_index = $loaded_posts;

			$next_posts = array_slice($feed_posts, $start_index, $posts_per_page);

			ob_start();

			if (is_array($next_posts) && count($next_posts) > 0) :
				foreach ($next_posts as $post) :
					$caption = !empty($post['caption']) ? $post['caption'] : '';
					$media_type = !empty($post['media_type']) ? $post['media_type'] : '';
					$media_url = !empty($post['media_url']) ? $post['media_url'] : '';
					$permalink = !empty($post['permalink']) ? $post['permalink'] : '';
					$timestamp = !empty($post['timestamp']) ? $post['timestamp'] : '';
					$username = !empty($post['username']) ? $post['username'] : '';
					$like_count = !empty($post['like_count']) ? $post['like_count'] : 0;
					$comments_count = !empty($post['comments_count']) ? $post['comments_count'] : 0;

					$post['profile_picture_url'] = $profile_picture_url;
					$post['show_likes_count'] = isset($params['show_likes_count']) ? $params['show_likes_count'] : false;
					$post['show_comments_count'] = isset($params['show_comments_count']) ? $params['show_comments_count'] : false;
					$post['popup_follow_button'] = isset($params['popup_follow_button']) ? $params['popup_follow_button'] : true;
					$post['popup_follow_button_text'] = isset($params['popup_follow_button_text']) ? $params['popup_follow_button_text'] : 'Follow';
		?>

					<div class="insta-gallery-item cg-carousel__slide js-carousel__slide" data-insta-postid="<?php echo esc_attr($post['id']) ?>" data-postindex="<?php echo esc_attr($post_index); ?>" data-postdata="<?php echo htmlspecialchars(json_encode($post), ENT_QUOTES, 'UTF-8'); ?>" data-media-type="<?php echo esc_attr($media_type); ?>">
						<?php

						if (!empty($hashtag_id) && $media_type == 'CAROUSEL_ALBUM') {
							if (isset($post['children']['data'][0]['media_url'])) {
								$hashtag_media_url = $post['children']['data'][0]['media_url'];
								$hashtag_media_type = $post['children']['data'][0]['media_type'];

								if ($hashtag_media_type == 'VIDEO') {
									echo '<video class="insta-gallery-image" src="' . esc_url($hashtag_media_url) . '"></video>';
								} else {
									echo ' <img class="insta-gallery-image" src="' . esc_url($hashtag_media_url) . '" alt="' . esc_attr('image') . '">';
								}
							}
						} else {
							if ($media_type == 'VIDEO') {
								echo '<video class="insta-gallery-image" src="' . esc_url($media_url) . '"></video>';
							} else {
								echo ' <img class="insta-gallery-image" src="' . esc_url($media_url) . '" alt="' . esc_attr('image') . '">';
							}
						}
						?>

						<div class="insta-gallery-item-type">
							<div class="insta-gallery-item-type-icon">
								<?php
								if ($media_type == 'VIDEO') {
									echo Helper::get_insta_video_icon();
								} else if ($media_type == 'CAROUSEL_ALBUM') {
									echo Helper::get_insta_image_carousel_icon();
								} else {
									echo Helper::get_insta_image_icon();
								}
								?>
							</div>
						</div>
						<div class="insta-gallery-item-info">
							<?php if (apply_filters('embedpress/is_allow_rander', false)): ?>
								<div class="insta-item-reaction-count">
									<div class="insta-gallery-item-likes">
										<?php echo Helper::get_insta_like_icon();
										echo esc_html($like_count); ?>
									</div>
									<div class="insta-gallery-item-comments">
										<?php echo Helper::get_insta_comment_icon();
										echo esc_html($comments_count); ?>
									</div>
								</div>
							<?php else : ?>
								<div class="insta-gallery-item-permalink">
									<?php echo Helper::get_instagram_icon(); ?>
								</div>
							<?php endif; ?>
						</div>
					</div>

<?php $post_index++;
				endforeach;
			endif;

			$feed_item = ob_get_clean();

			$next_start_index = $start_index + count($next_posts);

			wp_send_json(array(
				'html' => $feed_item,
				'next_post_index' => $next_start_index,
				'total_feed_posts' => count($feed_posts)
			));
		} else {
			wp_send_json('');
		}
	}


/** Function save_onboarding_settings() called by wp_ajax hooks: {'embedpress_save_onboarding'} **/
/** Parameters found in function save_onboarding_settings(): {"post": ["nonce", "gutenberg_block", "elementor_widget", "analytics_tracking", "g_lazyload", "embedpress_document_powered_by", "social_share", "custom_branding", "custom_ads", "content_protection", "complete", "data_consent"]} **/
function save_onboarding_settings() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => 'Insufficient permissions.' ] );
		}

		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'embedpress_onboarding_nonce' ) ) {
			wp_send_json_error( [ 'message' => 'Security check failed.' ] );
		}

		$settings = (array) get_option( EMBEDPRESS_PLG_NAME, [] );
		$elements = (array) get_option( EMBEDPRESS_PLG_NAME . ':elements', [] );

		// Element toggles — enable/disable all blocks/widgets at once
		$all_gutenberg_blocks = [
			'embedpress', 'document', 'embedpress-pdf', 'embedpress-calendar',
			'youtube-block', 'google-docs-block', 'google-slides-block',
			'google-sheets-block', 'google-forms-block', 'google-drawings-block',
			'google-maps-block', 'twitch-block', 'wistia-block', 'vimeo-block',
		];
		$all_elementor_widgets = [
			'embedpress', 'embedpress-document', 'embedpress-pdf', 'embedpress-calendar',
		];

		if ( isset( $_POST['gutenberg_block'] ) ) {
			if ( sanitize_text_field( $_POST['gutenberg_block'] ) === '1' ) {
				foreach ( $all_gutenberg_blocks as $block ) {
					$elements['gutenberg'][ $block ] = $block;
				}
			} else {
				$elements['gutenberg'] = [];
			}
		}
		if ( isset( $_POST['elementor_widget'] ) ) {
			if ( sanitize_text_field( $_POST['elementor_widget'] ) === '1' ) {
				foreach ( $all_elementor_widgets as $widget ) {
					$elements['elementor'][ $widget ] = $widget;
				}
			} else {
				$elements['elementor'] = [];
			}
		}

		// Global settings toggles
		if ( isset( $_POST['analytics_tracking'] ) ) {
			update_option( 'embedpress_analytics_tracking_enabled', intval( $_POST['analytics_tracking'] ) ? true : false );
		}
		if ( isset( $_POST['g_lazyload'] ) ) {
			$settings['g_lazyload'] = intval( $_POST['g_lazyload'] );
		}
		if ( isset( $_POST['embedpress_document_powered_by'] ) ) {
			$settings['embedpress_document_powered_by'] = intval( $_POST['embedpress_document_powered_by'] ) ? 'yes' : 'no';
		}
		// Pro feature toggles
		if ( isset( $_POST['social_share'] ) ) {
			$settings['social_share'] = intval( $_POST['social_share'] );
		}
		if ( isset( $_POST['custom_branding'] ) ) {
			$settings['custom_branding'] = intval( $_POST['custom_branding'] );
		}
		if ( isset( $_POST['custom_ads'] ) ) {
			$settings['custom_ads'] = intval( $_POST['custom_ads'] );
		}
		if ( isset( $_POST['content_protection'] ) ) {
			$settings['content_protection'] = intval( $_POST['content_protection'] );
		}
		// Mark onboarding as complete
		if ( ! empty( $_POST['complete'] ) ) {
			update_option( 'embedpress_onboarding_complete', true );
		}

		// Data consent — enable usage tracking via WPInsights
		if ( ! empty( $_POST['data_consent'] ) && ! get_option( 'embedpress_data_consent', false ) ) {
			update_option( 'embedpress_data_consent', true );

			$allow_tracking = get_option( 'wpins_allow_tracking', [] );
			if ( ! is_array( $allow_tracking ) ) {
				$allow_tracking = [];
			}
			$allow_tracking['embedpress'] = 'embedpress';
			update_option( 'wpins_allow_tracking', $allow_tracking );

			// Suppress the legacy opt-in notice now that consent was captured via the wizard
			$block_notice = get_option( 'wpins_block_notice', [] );
			if ( ! is_array( $block_notice ) ) {
				$block_notice = [];
			}
			$block_notice['embedpress'] = 'embedpress';
			update_option( 'wpins_block_notice', $block_notice );

			// Register the daily cron and fire an initial payload so data
			// reaches wpinsight.com without waiting for the first cron tick.
			$tracker = EmbedPress_Plugin_Usage_Tracker::get_instance( EMBEDPRESS_FILE, [
				'opt_in'       => true,
				'goodbye_form' => true,
				'item_id'      => '98ba0ac16a4f7b3b940d',
			] );
			$tracker->schedule_tracking();
			$tracker->do_tracking( true );
		}

		update_option( EMBEDPRESS_PLG_NAME, $settings );
		update_option( EMBEDPRESS_PLG_NAME . ':elements', $elements );

		wp_send_json_success( [ 'message' => 'Onboarding settings saved.' ] );
	}


/** Function ajax_view_notice() called by wp_ajax hooks: {'embedpress_view_feature_notice'} **/
/** Parameters found in function ajax_view_notice(): {"post": ["notice_id"]} **/
function ajax_view_notice() {
        check_ajax_referer('embedpress_feature_notice', 'nonce');

        $notice_id = isset($_POST['notice_id']) ? sanitize_text_field($_POST['notice_id']) : '';

        if (empty($notice_id)) {
            wp_send_json_error(['message' => 'Invalid notice ID']);
        }

        // Permanently dismiss (user clicked the button)
        $dismissed = get_option(self::DISMISSED_NOTICES_OPTION, []);
        if (!in_array($notice_id, $dismissed)) {
            $dismissed[] = $notice_id;
            update_option(self::DISMISSED_NOTICES_OPTION, $dismissed);
        }

        wp_send_json_success(['message' => 'Notice dismissed']);
    }


/** Function assets/pdf-flip-book/viewer.html() called by wp_ajax hooks: {'get_flipbook_viewer', 'nopriv_get_flipbook_viewer'} **/
/** No function found :-/ **/


/** Function delete_instagram_account() called by wp_ajax hooks: {'delete_instagram_account'} **/
/** Parameters found in function delete_instagram_account(): {"post": ["_nonce", "user_id", "account_type"]} **/
function delete_instagram_account()
    {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'You do not have sufficient permissions to access this functionality.'));
            return;
        }

        if (isset($_POST['_nonce']) && wp_verify_nonce($_POST['_nonce'], 'embedpress_elements_action')) {
            $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
            $account_type = isset($_POST['account_type']) ? $_POST['account_type'] : '';
            $account_data = get_option('ep_instagram_account_data');

            $data = array_filter($account_data, function ($item) use ($user_id) {
                return $item['user_id'] !== $user_id;
            });
            $data = array_values($data);
            update_option('ep_instagram_account_data', $data);
        } else {
            wp_die('Nonce verification failed.');
        }
    }


/** Function ajax_dismiss_notice() called by wp_ajax hooks: {'embedpress_dismiss_feature_notice'} **/
/** Parameters found in function ajax_dismiss_notice(): {"post": ["notice_id"]} **/
function ajax_dismiss_notice() {
        check_ajax_referer('embedpress_feature_notice', 'nonce');
        
        $notice_id = isset($_POST['notice_id']) ? sanitize_text_field($_POST['notice_id']) : '';
        
        if (empty($notice_id)) {
            wp_send_json_error(['message' => 'Invalid notice ID']);
        }
        
        $dismissed = get_option(self::DISMISSED_NOTICES_OPTION, []);
        if (!in_array($notice_id, $dismissed)) {
            $dismissed[] = $notice_id;
            update_option(self::DISMISSED_NOTICES_OPTION, $dismissed);
        }
        
        wp_send_json_success(['message' => 'Notice dismissed']);
    }


/** Function gutenberg_password_check() called by wp_ajax hooks: {'embedpress_gutenberg_password_check', 'nopriv_embedpress_gutenberg_password_check'} **/
/** Parameters found in function gutenberg_password_check(): {"post": ["client_id", "password", "content_password"]} **/
function gutenberg_password_check()
	{
		$client_id = isset($_POST['client_id']) ? sanitize_text_field($_POST['client_id']) : '';
		$password = isset($_POST['password']) ? sanitize_text_field($_POST['password']) : '';
		$content_password = isset($_POST['content_password']) ? sanitize_text_field($_POST['content_password']) : '';

		// Verify password
		if ($password === $content_password) {
			// Set cookie for authentication
			$hash_pass = hash('sha256', wp_salt(32) . md5($password));
			setcookie("password_correct_" . $client_id, $hash_pass, time() + 3600, '/');

			$response = array(
				'success' => true,
				'message' => 'Password correct'
			);
		} else {
			$response = array(
				'success' => false,
				'message' => 'Incorrect password'
			);
		}

		wp_send_json($response);
	}


/** Function get_instagram_userdata_ajax() called by wp_ajax hooks: {'get_instagram_userdata_ajax'} **/
/** Parameters found in function get_instagram_userdata_ajax(): {"post": ["_nonce", "access_token", "account_type"]} **/
function get_instagram_userdata_ajax()
    {
        if (function_exists('set_time_limit')) {
            set_time_limit(0);
        }

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'You do not have sufficient permissions to access this functionality.'));
            return;
        }

        if (isset($_POST['_nonce']) && wp_verify_nonce($_POST['_nonce'], 'embedpress_elements_action')) {
            if (isset($_POST['access_token'])) {
                $access_token = sanitize_text_field($_POST['access_token']);
                $account_type = sanitize_text_field($_POST['account_type']);

                $user_data = $this->get_instagram_userdata($access_token, $account_type);

                $this->handle_instagram_data($user_data);

                $access_token = sanitize_text_field($_POST['access_token']);
                $account_type = sanitize_text_field($_POST['account_type']);
                $user_id = $this->get_instagram_userid($access_token, $account_type);

                $option_key = 'ep_instagram_feed_data';
                $feed_data = get_option($option_key, array());

                $feed_userinfo =  Helper::getInstagramUserInfo($access_token, $account_type, $user_id, true);
                $feed_posts    =  Helper::getInstagramPosts($access_token, $account_type, $user_id, 100, true);

                if (!empty($user_id)) {
                    $feed_data[$user_id] = [
                        'feed_userinfo' => $feed_userinfo,
                        'feed_posts' => $feed_posts,
                    ];

                    delete_transient('instagram_user_info_' . $user_id);
                    delete_transient('instagram_posts_' . $user_id);
                    update_option('ep_instagram_feed_data', $feed_data);
                } else {
                    $feed_data['error'] = "Access token Invalid or expired.";
                }


                wp_send_json($feed_data);
            } else {
                wp_send_json_error('Access token not provided');
            }
        } else {
            wp_send_json_error('Nonce verification failed');
        }
    }


/** Function sync_instagram_data_ajax() called by wp_ajax hooks: {'nopriv_sync_instagram_data_ajax', 'sync_instagram_data_ajax'} **/
/** Parameters found in function sync_instagram_data_ajax(): {"post": ["_nonce", "access_token", "account_type", "user_id"]} **/
function sync_instagram_data_ajax()
    {
        if (function_exists('set_time_limit')) {
            set_time_limit(0);
        }
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'You do not have sufficient permissions to access this functionality.'));
            return;
        }

        if (isset($_POST['_nonce']) && wp_verify_nonce($_POST['_nonce'], 'embedpress_elements_action')) {
            if (isset($_POST['access_token'])) {

                $access_token = sanitize_text_field($_POST['access_token']);
                $account_type = sanitize_text_field($_POST['account_type']);
                $user_id = sanitize_text_field($_POST['user_id']);

                $option_key = 'ep_instagram_feed_data';
                $feed_data = get_option($option_key, array());

                $feed_userinfo =  Helper::getInstagramUserInfo($access_token, $account_type, $user_id, true);
                $feed_posts    =  Helper::getInstagramPosts($access_token, $account_type, $user_id, 100, true);

                $feed_data[$user_id] = [
                    'feed_userinfo' => $feed_userinfo,
                    'feed_posts' => $feed_posts,
                ];

                delete_transient('instagram_user_info_' . $user_id);
                delete_transient('instagram_posts_' . $user_id);
                update_option('ep_instagram_feed_data', $feed_data);


                wp_send_json($feed_data);
            } else {
                wp_send_json_error('Access token not provided');
            }
        } else {
            wp_send_json_error('Nonce verification failed');
        }
    }


/** Function lock_content_form_handler() called by wp_ajax hooks: {'nopriv_lock_content_form_handler', 'lock_content_form_handler'} **/
/** Parameters found in function lock_content_form_handler(): {"post": ["client_id", "password", "post_id"]} **/
function lock_content_form_handler() {
		// print_r($embedHTML);

		$client_id = isset($_POST['client_id']) ? sanitize_text_field($_POST['client_id']) : '';
		$password = isset($_POST['password']) ? sanitize_text_field($_POST['password']) : '';
		$post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
		
		$epbase64 = get_post_meta( $post_id, 'ep_base_' .$client_id, true );	
		$hash_key = get_post_meta( $post_id, 'hash_key_' .$client_id, true  );

		// Set the decryption key and initialization vector (IV)
		$key = Helper::get_hash();

		// Decode the base64 encoded cipher
		$cipher = base64_decode($epbase64);
		// Decrypt the cipher using AES-128-CBC encryption

		$wp_pass_key = hash('sha256', wp_salt(32) . md5($password));
		$iv = substr($wp_pass_key, 0, 16);
		
		if ($wp_pass_key === $hash_key) {

			$embed = openssl_decrypt($cipher, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv) . '<script>
			var now = new Date();
			var time = now.getTime();
			var expireTime = time + 1000 * 60 * 60 * 24 * 30;
			now.setTime(expireTime);
			document.cookie = "password_correct_'.esc_js($client_id).'='.esc_js($hash_key).'; expires=" + now.toUTCString() + "; path=/";
		</script>';

		}
		else{
			$embed = 0;
		}

		// Process the form data and return a response
		$response = array(
		'success' => true,
		'password' => $password,
		'embedHtml' => $embed,
		'post_id' => $post_id
		);

		wp_send_json($response);

	}


/** Function save_source_data() called by wp_ajax hooks: {'save_source_data'} **/
/** Parameters found in function save_source_data(): {"post": ["_source_nonce", "source_url", "block_id"]} **/
function save_source_data()
	{
		if (!isset($_POST['_source_nonce']) || !wp_verify_nonce($_POST['_source_nonce'], 'source_nonce_embedpress')) {
			return;
		}

		if (!current_user_can('manage_options')) {
			return;
		}

		$source_url = isset($_POST['source_url']) ? $_POST['source_url'] : null;
		$blockid = isset($_POST['block_id']) ? $_POST['block_id'] : null;


		Helper::get_source_data($blockid, $source_url, 'gutenberg_source_data', 'gutenberg_temp_source_data');
	}


/** Function ajax_clear_cache() called by wp_ajax hooks: {'embedpress_clear_content_cache'} **/
/** Parameters found in function ajax_clear_cache(): {"post": ["nonce"]} **/
function ajax_clear_cache()
    {
        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'embedpress_clear_cache')) {
            wp_die('Invalid nonce');
        }

        $this->data_collector->clear_content_count_cache();

        wp_send_json_success([
            'message' => 'Content count cache cleared successfully'
        ]);
    }


/** Function dismiss_feature_notice() called by wp_ajax hooks: {'embedpress_dismiss_feature_notice'} **/
/** Parameters found in function dismiss_feature_notice(): {"post": ["nonce", "notice_id"]} **/
function dismiss_feature_notice() {
		// Verify nonce for security
		if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'embedpress_feature_notice_nonce')) {
			wp_send_json_error(['message' => 'Security check failed']);
		}

		// Check user capabilities
		if (!current_user_can('manage_options')) {
			wp_send_json_error(['message' => 'Insufficient permissions']);
		}

		$notice_id = isset($_POST['notice_id']) ? sanitize_text_field($_POST['notice_id']) : '';

		if (empty($notice_id)) {
			wp_send_json_error(['message' => 'Notice ID is required']);
		}

		// Define valid notice types and their corresponding option names
		$valid_notices = [
			'analytics' => 'embedpress_feature_notice_analytics_dismissed',
		];

		if (array_key_exists($notice_id, $valid_notices)) {
			$option_name = $valid_notices[$notice_id];
			update_option($option_name, true);

			wp_send_json_success([
				'message' => 'Feature notice dismissed successfully',
				'notice_id' => $notice_id,
				'option_name' => $option_name
			]);
		}

		wp_send_json_error([
			'message' => 'Invalid notice ID: ' . $notice_id,
			'valid_notices' => array_keys($valid_notices)
		]);
	}


/** Function update_elements_list() called by wp_ajax hooks: {'embedpress_elements_action'} **/
/** Parameters found in function update_elements_list(): {"post": ["_wpnonce", "element_type", "element_name", "checked"]} **/
function update_elements_list() {

		if (!current_user_can('manage_options')) {
			wp_send_json_error(array('message' => 'You do not have sufficient permissions to access this functionality.'));
			return;
		}
		
		if ( !empty($_POST['_wpnonce'] && wp_verify_nonce( $_POST['_wpnonce'], 'embedpress_elements_action')) ) {
			$option = EMBEDPRESS_PLG_NAME.":elements";
			$elements = (array) get_option( $option, []);
			$settings = (array) get_option( EMBEDPRESS_PLG_NAME, []);

			$type = !empty( $_POST['element_type']) ? sanitize_text_field( $_POST['element_type']) : '';
			$name = !empty( $_POST['element_name']) ? sanitize_text_field( $_POST['element_name']) : '';
			$checked = !empty( $_POST['checked']) ? $_POST['checked'] : false;
			if ( 'false' != $checked ) {
				$elements[$type][$name] = $name;
				if ( $type === 'classic' ) {
					$settings[$name]  = 1;
				}
			}else{
				if( isset( $elements[$type]) && isset( $elements[$type][$name])){
					unset( $elements[$type][$name]);
				}
				if ( $type === 'classic' ) {
					$settings[$name]  = 0;
				}
			}
			update_option( EMBEDPRESS_PLG_NAME, $settings);
			update_option( $option, $elements);
			wp_send_json_success();
		}
		wp_send_json_error();
	}


/** Function dismiss_element() called by wp_ajax hooks: {'embedpress_dismiss_element'} **/
/** Parameters found in function dismiss_element(): {"post": ["nonce", "element_type"]} **/
function dismiss_element() {
		// Verify nonce for security
		if (!wp_verify_nonce($_POST['nonce'], 'embedpress_ajax_nonce')) {
			wp_die('Security check failed');
		}

		// Check user capabilities
		if (!current_user_can('manage_options')) {
			wp_die('Insufficient permissions');
		}

		$element_type = isset($_POST['element_type']) ? sanitize_text_field($_POST['element_type']) : '';

		// Define valid dismiss types and their corresponding option names
		$valid_dismiss_types = [
			'main_banner' => 'embedpress_main_banner_dismissed',
			'hub_popup' => 'embedpress_hub_popup_dismissed',
			'popup_banner' => 'embedpress_popup_dismissed', // Legacy support
		];

		if (array_key_exists($element_type, $valid_dismiss_types)) {
			$option_name = $valid_dismiss_types[$element_type];
			update_option($option_name, true);

			// Store current plugin version when hub_popup is dismissed
			// This ensures popup shows again on next version update
			if ($element_type === 'hub_popup') {
				update_option('embedpress_last_popup_version', EMBEDPRESS_VERSION);
			}

			wp_send_json_success([
				'message' => ucfirst(str_replace('_', ' ', $element_type)) . ' dismissed successfully',
				'element_type' => $element_type,
				'option_name' => $option_name
			]);
		}

		wp_send_json_error([
			'message' => 'Invalid element type: ' . $element_type,
			'valid_types' => array_keys($valid_dismiss_types)
		]);
	}


/** Function save_global_brand_image() called by wp_ajax hooks: {'save_global_brand_image'} **/
/** Parameters found in function save_global_brand_image(): {"post": ["nonce", "logo_url", "logo_id"]} **/
function save_global_brand_image() {
		// Verify nonce for security
		if (!wp_verify_nonce($_POST['nonce'], 'embedpress_ajax_nonce')) {
			wp_die('Security check failed');
		}

		// Check user capabilities
		if (!current_user_can('manage_options')) {
			wp_die('Insufficient permissions');
		}

		$logo_url = isset($_POST['logo_url']) ? esc_url_raw($_POST['logo_url']) : '';
		$logo_id = isset($_POST['logo_id']) ? intval($_POST['logo_id']) : '';

		// Save global brand settings
		$global_brand_settings = [
			'logo_url' => $logo_url,
			'logo_id' => $logo_id,
		];

		$updated = update_option(EMBEDPRESS_PLG_NAME . ':global_brand', $global_brand_settings);

		// If global brand image is being set, auto-enable branding for providers without custom logos
		if (!empty($logo_url)) {
			$this->auto_enable_global_branding($logo_url, $logo_id);
		}

		if ($updated !== false) {
			wp_send_json_success([
				'message' => 'Global brand image saved successfully',
				'logo_url' => $logo_url,
				'logo_id' => $logo_id
			]);
		} else {
			wp_send_json_error('Failed to save global brand image');
		}
	}


/** Function EmbedPress\Includes\Classes\Pdf_Thumbnail_Handler() called by wp_ajax hooks: {'ep_upload_pdf_thumbnail', 'ep_generate_pdf_thumbnail'} **/
/** No function found :-/ **/


/** Function assets/pdf-flip-book/templates/book-view.html() called by wp_ajax hooks: {'nopriv_get_flipbook_template', 'get_flipbook_template'} **/
/** No function found :-/ **/


/** Function ajax_mark_milestone_seen() called by wp_ajax hooks: {'embedpress_mark_milestone_seen'} **/
/** Parameters found in function ajax_mark_milestone_seen(): {"post": ["nonce"]} **/
function ajax_mark_milestone_seen()
    {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'embedpress_milestone_nonce')) {
            wp_send_json_error('Invalid nonce');
            return;
        }

        // Get the trigger type (version_update or milestone_level)
        $trigger_type = get_option('embedpress_milestone_current_trigger', 'milestone_level');

        // If triggered by version update, store the current version
        if ($trigger_type === 'version_update') {
            update_option('embedpress_last_milestone_version', EMBEDPRESS_VERSION);
        }

        // Get the current milestone level that was shown
        $current_level = get_option('embedpress_milestone_current_level', '');

        if (!empty($current_level)) {
            // Mark this milestone level as seen (site-wide)
            update_option('embedpress_milestone_level', $current_level);

            // Clean up the temporary option
            delete_option('embedpress_milestone_current_level');
        }

        // Clean up trigger type
        delete_option('embedpress_milestone_current_trigger');

        // Clear the "is showing" flag when user dismisses the milestone
        // This allows the next milestone to be shown when conditions are met
        delete_option('is_embedpress_milestone_showing');

        wp_send_json_success();
    }


/** Function ajax_skip_notice() called by wp_ajax hooks: {'embedpress_skip_feature_notice'} **/
/** Parameters found in function ajax_skip_notice(): {"post": ["notice_id"]} **/
function ajax_skip_notice() {
        check_ajax_referer('embedpress_feature_notice', 'nonce');

        $notice_id = isset($_POST['notice_id']) ? sanitize_text_field($_POST['notice_id']) : '';

        if (empty($notice_id)) {
            wp_send_json_error(['message' => 'Invalid notice ID']);
        }

        // Permanently dismiss (same as close button)
        $dismissed = get_option(self::DISMISSED_NOTICES_OPTION, []);
        if (!in_array($notice_id, $dismissed)) {
            $dismissed[] = $notice_id;
            update_option(self::DISMISSED_NOTICES_OPTION, $dismissed);
        }

        wp_send_json_success(['message' => 'Notice dismissed']);
    }


/** Function install_site_performance() called by wp_ajax hooks: {'embedpress_install_site_performance'} **/
/** Parameters found in function install_site_performance(): {"post": ["nonce"]} **/
function install_site_performance() {
		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_send_json_error( [ 'message' => __( 'Insufficient permissions.', 'embedpress' ) ] );
		}

		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'embedpress_onboarding_nonce' ) ) {
			wp_send_json_error( [ 'message' => __( 'Security check failed.', 'embedpress' ) ] );
		}

		$result = \EmbedPress\Includes\Classes\SitePerformance::install();

		if ( empty( $result['success'] ) ) {
			wp_send_json_error( $result );
		}

		wp_send_json_success( $result );
	}


/** Function ajax_video_popup_description() called by wp_ajax hooks: {'fetch_video_description', 'nopriv_fetch_video_description'} **/
/** Parameters found in function ajax_video_popup_description(): {"post": ["vid"]} **/
function ajax_video_popup_description()
	{
		if (isset($_POST['vid'])) {
			$api_key = self::get_api_key();
			$vid = sanitize_text_field($_POST['vid']);

			$video_data = Helper::get_youtube_video_data($api_key, $vid);

			if ($video_data) {
				ob_start();
?>
				<div class="video-description">
					<?php echo YoutubeLayout::generate_youtube_video_description($video_data); ?>
				</div>
		<?php
				$description_html = ob_get_clean();
				wp_send_json_success(['description' => $description_html]);
			} else {
				wp_send_json_error(['error' => 'Failed to fetch video data.']);
			}
		} else {
			wp_send_json_error(['error' => 'Invalid video ID.']);
		}
	}


/** Function \\EmbedPress\\Ends\\Back\\Handler() called by wp_ajax hooks: {'embedpress_notice_dismiss'} **/
/** No function found :-/ **/


/** Function wp_ajax_embedpress_get_embed_url_info() called by wp_ajax hooks: {'embedpress_do_ajax_request'} **/
/** No function found :-/ **/


/** Function youtube_rest_api() called by wp_ajax hooks: {'youtube_rest_api', 'nopriv_youtube_rest_api'} **/
/** Parameters found in function youtube_rest_api(): {"post": ["playlistid", "pagetoken", "ytChannelLayout", "pagesize", "currentpage", "epcolumns", "showtitle", "showpaging", "autonext", "thumbplay", "thumbnail_quality"]} **/
function youtube_rest_api()
	// {
	// 	// Instantiate the class
	// 	$youtube = new Youtube($config = '');


	// 	// Call the non-static method
	// 	// $result = $youtube->someMethod();

	// 	$result = YoutubeLayout::create_youtube_layout([
	// 		'playlistId'        => isset($_POST['playlistid']) ? sanitize_text_field($_POST['playlistid']) : null,
	// 		'pageToken'         => isset($_POST['pagetoken']) ? sanitize_text_field($_POST['pagetoken']) : null,
	// 		'ytChannelLayout'   => isset($_POST['ytChannelLayout']) ? sanitize_text_field($_POST['ytChannelLayout']) : 'gallery',
	// 		'pagesize'          => isset($_POST['pagesize']) ? sanitize_text_field($_POST['pagesize']) : null,
	// 		'currentpage'       => isset($_POST['currentpage']) ? sanitize_text_field($_POST['currentpage']) : null,
	// 		'columns'           => isset($_POST['epcolumns']) ? sanitize_text_field($_POST['epcolumns']) : null,
	// 		'showTitle'         => isset($_POST['showtitle']) ? sanitize_text_field($_POST['showtitle']) : null,
	// 		'showPaging'        => isset($_POST['showpaging']) ? sanitize_text_field($_POST['showpaging']) : null,
	// 		'autonext'          => isset($_POST['autonext']) ? sanitize_text_field($_POST['autonext']) : null,
	// 		'thumbplay'         => isset($_POST['thumbplay']) ? sanitize_text_field($_POST['thumbplay']) : null,
	// 		'thumbnail_quality' => isset($_POST['thumbnail_quality']) ? sanitize_text_field($_POST['thumbnail_quality']) : null,
	// 	], $youtube->layout_data(), $youtube->get_layout());

	// 	// print_r($youtube->layout_data()); die;

	// 	wp_send_json($result);
	// }


/** Function ajax_get_calendar() called by wp_ajax hooks: {'epgc_ajax_get_calendar', 'nopriv_epgc_ajax_get_calendar'} **/
/** Parameters found in function ajax_get_calendar(): {"post": ["start", "end", "thisCalendarids", "timeZone"], "server": ["HTTP_REFERER"]} **/
function ajax_get_calendar() {

		// Nonces are user-session-bound, so a nonce minted for a logged-in author
		// won't verify for an anonymous visitor served the same cached page HTML —
		// the request would 403 even though the public calendar data is harmless
		// to expose. Enforce the nonce only for authenticated requests; anon hits
		// fall back to API-key/public-mode handling below.
		if ( is_user_logged_in() ) {
			check_ajax_referer('epgc_nonce');
		}

		// Capability gate only applies to OAuth mode — public API-key mode must
		// stay reachable for anonymous visitors of the public calendar widget.
		$hasAccessToken = get_option('epgc_access_token');

		if ( ! empty( $hasAccessToken ) && is_user_logged_in() && ! current_user_can( 'read' ) ) {
			wp_send_json_error( [ 'error' => 'Unauthorized' ], 403 );
		}

		try {

			if (empty($_POST['start']) || empty($_POST['end'])) {
				throw new Exception(EPGC_ERRORS_INVALID_FORMAT);
			}

			// Start and end are in ISO8601 string format with timezone offset (e.g. 2018-09-01T12:30:00-05:00)
			$start = $_POST['start'];
			$end = $_POST['end'];

			$thisCalendarids = [];
			$postedCalendarIds = [];
			if (array_key_exists('thisCalendarids', $_POST) && !empty($_POST['thisCalendarids'])) {
				$postedCalendarIds = array_map('trim', explode(',', $_POST['thisCalendarids']));
			}
			$privateSettingsCalendarListIds = array_map(function($item) {
				return $item['id'];
			}, static::getDecoded('epgc_calendarlist', []));
			if (!empty($hasAccessToken)) {
				// OAuth path: only IDs that are both known AND selected may pass.
				// If the synced calendar list is missing (e.g. token saved but sync
				// failed), refuse rather than fall through — otherwise a logged-in
				// subscriber could submit `primary` and reach the admin's calendar
				// via the stored bearer token.
				$privateSettingsSelectedCalendarListIds = get_option('epgc_selected_calendar_ids');
				if (empty($privateSettingsCalendarListIds) || empty($privateSettingsSelectedCalendarListIds)) {
					throw new Exception(EPGC_ERRORS_NO_SELECTED_CALENDARS);
				}
				foreach ($postedCalendarIds as $calId) {
					if (in_array($calId, $privateSettingsCalendarListIds, true) && in_array($calId, $privateSettingsSelectedCalendarListIds, true)) {
						$thisCalendarids[] = $calId;
					}
				}
				if (empty($thisCalendarids)) {
					throw new Exception(EPGC_ERRORS_NO_SELECTED_CALENDARS);
				}
			} else {
				// API-key (public) path: IDs come from widget/shortcode attributes
				// and can only resolve to public Google calendars.
				$thisCalendarids = $postedCalendarIds;
			}

			$cacheTime = get_option('epgc_cache_time'); // empty == no cache!

			// We can have mutiple calendars with different calendar selections,
			// so key should be including calendar selection.
			$transientKey = EPGC_TRANSIENT_PREFIX . $start . $end . md5(implode('-', $thisCalendarids));

			$transientItems = !empty($cacheTime) ? get_transient($transientKey) : false;

			$calendarListByKey = static::get_calendars_by_key($thisCalendarids);

			if ($transientItems !== false) {
				wp_send_json(['items' => $transientItems, 'calendars' => $calendarListByKey]);
				wp_die();
			}

			$colorList = false; // false means not queried yet / otherwise [] or filled []

			$results = [];

			$optParams = array(
				'maxResults' => EPGC_EVENTS_MAX_RESULTS,
				'orderBy' => 'startTime',
				'singleEvents' => 'true',
				'timeMin' => $start,
				'timeMax' => $end,
			);
			if (!empty($_POST['timeZone'])) {
				$optParams['timeZone'] = $_POST['timeZone'];
			}

			$hasAccessToken = get_option('epgc_access_token');

			if (!empty($hasAccessToken)) {

				$client = static::getGoogleClient(true);
				if ($client->isAccessTokenExpired()) {
					if (!$client->getRefreshTOken()) {
						throw new Exception(EPGC_ERRORS_REFRESH_TOKEN_MISSING);
					}
					$client->refreshAccessToken();
				}
				$service = new EmbedPress_GoogleCalendarClient($client);

				foreach ($thisCalendarids as $calendarId) {
					$results[$calendarId] = $service->getEvents($calendarId, $optParams);
				}

			} elseif (!empty(get_option('epgc_api_key'))) {

				$referer = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
				$apiKey = get_option('epgc_api_key');
				$service = new EmbedPress_GoogleCalendarClient(null);
				foreach ($thisCalendarids as $calendarId) {
					$results[$calendarId] = $service->getEventsPublic($calendarId, $optParams, $apiKey, $referer);
				}

			} else {
				// No API key and no OAuth2 token
				throw new Exception(EPGC_ERRORS_TOKEN_AND_API_KEY_MISSING);
			}

			$items = [];
			foreach ($results as $calendarId => $events) {
				foreach ($events as $item) {
					$newItem = [
						'title' => empty($item['summary']) ? EPGC_EVENTS_DEFAULT_TITLE : $item['summary'],
						'htmlLink' => $item['htmlLink'],
						'description' => !empty($item['description']) ? $item['description'] : '',
						'calId' => $calendarId,
						'creator' => !empty($item['creator']) ? $item['creator'] : [],
						'attendees' => !empty($item['attendees']) ? $item['attendees'] : [],
						'attachments' => !empty($item['attachments']) ? $item['attachments'] : [],
						'location' => !empty($item['location']) ? $item['location'] : ''
					];
					if (!empty($item['start']['date'])) {
						$newItem['allDay'] = true;
						$newItem['start'] = $item['start']['date'];
						$newItem['end'] = $item['end']['date'];
						// $newItem['timeZone'] = $item['start']['timeZone']; // TODO? end timezone also exists...
					} else {
						$newItem['start'] = $item['start']['dateTime'];
						$newItem['end'] = $item['end']['dateTime'];
						// $newItem['timeZone'] = $item['start']['timeZone']; // TODO? end timezone also exists...
					}
					if (!empty($item['colorId'])) {
						if ($colorList === false) {
							$colorList = static::getDecoded('pgc_colorlist', []);
						}
						if (array_key_exists('event', $colorList) && array_key_exists($item['colorId'], $colorList['event'])) {
							$newItem['bColor'] = $colorList['event'][$item['colorId']]['background'];
							$newItem['fColor'] = $colorList['event'][$item['colorId']]['foreground'];
						}
					}

					$items[] = $newItem;
				}
			}

			if (!empty($cacheTime)) {
				set_transient($transientKey, $items, $cacheTime * MINUTE_IN_SECONDS);
			}

			wp_send_json(['items' => $items, 'calendars' => $calendarListByKey]);
			wp_die();
		} catch (EmbedPress_GoogleClient_RequestException $ex) {
			wp_send_json([
				'error' => $ex->getMessage(),
				'errorCode' => $ex->getCode(),
				'errorDescription' => $ex->getDescription()]);
			wp_die();
		} catch (Exception $ex) {
			wp_send_json([
				'error' => $ex->getMessage(),
				'errorCode' => $ex->getCode()]);
			wp_die();
		}
	}


/** Function assets/pdf/web/viewer.html() called by wp_ajax hooks: {'nopriv_get_viewer', 'get_viewer'} **/
/** No function found :-/ **/


/** Function save_settings() called by wp_ajax hooks: {'embedpress_settings_action'} **/
/** Parameters found in function save_settings(): {"post": ["ep_settings_nonce", "submit"], "server": ["HTTP_REFERER"]} **/
function save_settings() {
		// needs to check for ajax and return response accordingly.
		if ( !empty( $_POST['ep_settings_nonce']) && wp_verify_nonce( $_POST['ep_settings_nonce'], 'ep_settings_nonce') ) {
			$submit_type = !empty( $_POST['submit'] ) ? $_POST['submit'] : '';
			$save_handler_method  = "save_{$submit_type}_settings";
			do_action( "before_{$save_handler_method}");
			do_action( "before_embedpress_settings_save");
			if ( method_exists( $this, $save_handler_method ) ) {
				$this->$save_handler_method();
			}
			do_action( "after_embedpress_settings_save");
			$return_url = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : admin_url();
			$return_url = add_query_arg( 'success', 1, $return_url );
			if ( wp_doing_ajax() ) {
				wp_send_json_success();
			}
			wp_safe_redirect( $return_url);
			exit();
		}
	}


