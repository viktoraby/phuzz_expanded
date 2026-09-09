<?php
/***
*
*Found actions: 15
*Found functions:13
*Extracted functions:13
*Total parameter names extracted: 12
*Overview: {'cmp_ajax_import_settings': {'cmp_ajax_import_settings'}, 'cmp_mailchimp_list_ajax': {'cmp_mailchimp_list_ajax'}, 'cmp_ajax_export_settings': {'cmp_ajax_export_settings'}, 'cmp_check_update': {'cmp_check_update'}, 'cmp_theme_update_install': {'cmp_theme_update_install'}, 'niteo_subscribe': {'nopriv_niteo_subscribe', 'niteo_subscribe'}, 'niteo_themeinfo': {'niteo_themeinfo'}, 'cmp_ajax_dismiss_activation_notice': {'cmp_ajax_dismiss_activation_notice'}, 'cmp_ajax_upload_font': {'cmp_ajax_upload_font'}, 'cmp_get_post_detail': {'nopriv_cmp_get_post_detail', 'cmp_get_post_detail'}, 'niteo_export_csv': {'niteo_export_csv'}, 'cmp_ajax_toggle_activation': {'cmp_toggle_activation'}, 'niteo_unsplash': {'niteo_unsplash'}}
*
***/

/** Function cmp_ajax_import_settings() called by wp_ajax hooks: {'cmp_ajax_import_settings'} **/
/** Parameters found in function cmp_ajax_import_settings(): {"post": ["json"]} **/
function cmp_ajax_import_settings()
		{

			check_ajax_referer('cmp-coming-soon-ajax-secret', 'security');

			// verify user rights
			if (!current_user_can('manage_options')) {
				wp_send_json_error(array('message' => __('Sorry, but this request is invalid', 'cmp-coming-soon-maintenance')), 403);
			}

			if (!isset($_POST['json']) || !is_string($_POST['json'])) {
				wp_send_json_error(array('message' => __('Please insert valid JSON file and try again.', 'cmp-coming-soon-maintenance')), 400);
			}

			$settings = json_decode(wp_unslash($_POST['json']), true);

			$result = array(
				'result' => 'success',
				'message' => __('All done!', 'cmp-coming-soon-maintenance')
			);

			if (json_last_error() === JSON_ERROR_NONE && is_array($settings)) {
				if (isset($settings[0]) && $settings[0] === 'CMP_EXPORT') {
					// remove first value used for JSON CMP Settings check
					unset($settings[0]);

					// Only allow options within this plugin's own namespace to be written.
					// This is a static, deterministic check (not based on what already
					// happens to exist in wp_options) so imports still work on a fresh
					// install or when migrating settings to a brand new site.
					$validated_settings = array();

					foreach ($settings as $setting) {
						if (!is_array($setting) || count($setting) !== 1) {
							$result = array(
								'result' => 'error',
								'message' => __('The settings file contains an invalid option.', 'cmp-coming-soon-maintenance')
							);
							break;
						}

						$name = key($setting);
						if (!is_string($name) || strpos($name, 'niteoCS_') !== 0 || sanitize_key($name) !== strtolower($name)) {
							$result = array(
								'result' => 'error',
								'message' => __('The settings file contains an option that cannot be imported.', 'cmp-coming-soon-maintenance')
							);
							break;
						}

						$validated_settings[] = array($name => $setting[$name]);
					}

					if ($result['result'] !== 'success') {
						echo wp_json_encode($result);
						wp_die();
					}

					// Delete only the options included in the strict allow-list.
					foreach ($saved_options as $option) {
						delete_option($option->option_name);
					}

					// import cmp settings from JSON structure
					foreach ($validated_settings as $setting) {

						$img_settings = array('niteoCS_banner_id', 'niteoCS_logo_id', 'niteoCS_seo_img_id', 'niteoCS_favicon_id', 'niteoCS_subs_img_id', 'niteoCS_subs_img_popup_id');

						$name = key($setting);
						$value = $setting[$name];

						if (in_array($name, $img_settings, true)) {

							$urls = is_string($value) ? array_filter(array_map('trim', explode(',', $value))) : array();
							$attachment_ids = array();

							foreach ($urls as $url) {
								$attachment_id = $this->cmp_insert_attachment_from_url($url);
								if ($attachment_id) {
									$attachment_ids[] = absint($attachment_id);
								}
							}

							$value = implode(',', $attachment_ids);
						} elseif ($name === 'niteoCS_socialmedia') {
							$value = $this->cmp_sanitize_socialmedia($value);
						}

						update_option($name, $value);
					}
				} else {
					$result = array(
						'result' => 'error',
						'message' =>  __('JSON file is valid but it does not contain CMP Settings.', 'cmp-coming-soon-maintenance')
					);
				}
			} else {
				$result = array(
					'result' => 'error',
					'message' =>  __('Please insert valid JSON file and try again.', 'cmp-coming-soon-maintenance')
				);
			}

			echo wp_json_encode($result);
			wp_die();
		}


/** Function cmp_mailchimp_list_ajax() called by wp_ajax hooks: {'cmp_mailchimp_list_ajax'} **/
/** Parameters found in function cmp_mailchimp_list_ajax(): {"post": ["params"]} **/
function cmp_mailchimp_list_ajax($apikey)
		{

			// check for ajax 
			if (isset($_POST['params'])) {
				// verify nonce
				check_ajax_referer('cmp-coming-soon-ajax-secret', 'security');
				// verify user rights
				if (!current_user_can('manage_options')) {
					die('Sorry, but this request is invalid');
				}

				// sanitize array
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

				// check params
				if (!empty($_POST['params'])) {
					$params = $_POST['params'];
				}

				$api_key = $this->sanitize_api_key($params['apikey']);

				$dc = substr($api_key, strpos($api_key, '-') + 1); // datacenter, it is the part of your api key - us5, us8 etc

				$args = array(
					'headers' => array(
						'Authorization' => 'Basic ' . base64_encode('user:' . $api_key)
					)
				);


				// retrieve response from mailchimp
				$response = wp_remote_get('https://' . $dc . '.api.mailchimp.com/3.0/lists/', $args);

				// if we have it, create new array with lists id and name, else push error messages into array
				if (!is_wp_error($response)) {
					$lists_array = array();

					$body = json_decode($response['body'], true);

					if ($response['response']['code'] == 200) {
						$lists_array['response'] = 200;
						$i = 0;
						foreach ($body['lists'] as $list) {
							$lists_array['lists'][$i]['id'] = $list['id'];
							$lists_array['lists'][$i]['name'] = $list['name'];
							$i++;
						}
					} else {
						$lists_array['response'] = $response['response']['code'];
						$lists_array['message'] = $body['title'] . ': ' . $body['detail'];
					}
				} else {
					$lists_array['response'] = '500';
					$lists_array['message'] = $response->get_error_message();
				}

				// json encode response
				$lists_json = json_encode($lists_array);

				// save it
				update_option('niteoCS_mailchimp_lists', $lists_json);

				// delete selected old mailchimp list because we do not want it
				delete_option('niteoCS_mailchimp_list_selected');

				// echo ajax result
				echo $lists_json;
				wp_die();
			}
		}


/** Function cmp_ajax_export_settings() called by wp_ajax hooks: {'cmp_ajax_export_settings'} **/
/** No params detected :-/ **/


/** Function cmp_check_update() called by wp_ajax hooks: {'cmp_check_update'} **/
/** Parameters found in function cmp_check_update(): {"post": ["theme_slug"], "get": ["theme"]} **/
function cmp_check_update($theme_slug)
		{

			$ajax = false;
			// check for ajax 
			if (isset($_POST['theme_slug'])) {
				// verify nonce
				check_ajax_referer('cmp-coming-soon-ajax-secret', 'security');
				// verify user rights
				if (!current_user_can('manage_options')) {
					die('Sorry, but this request is invalid');
				}

				// sanitize array
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

				if (!empty($_POST['theme_slug'])) {
					$theme_slug = $_POST['theme_slug'];
					$ajax   = true;
				}
			}

			if (!in_array($theme_slug, $this->cmp_premium_themes_installed())) {
				return;
			}

			// check for current theme version
			$remote_version = '';
			$current_version = '';

			if (CMP_DEBUG === TRUE) {
				delete_transient($theme_slug . '_updatecheck');
			}

			// always check if update check transient is set or ajax request
			if (false === ($updatecheck_transient = get_transient($theme_slug . '_updatecheck')) || $ajax === TRUE) {

				$current_version = $this->cmp_theme_version($theme_slug);
				// get remote version from  remote server
				$request = wp_remote_post(CMP_UPDATE_URL . '?action=get_metadata&slug=' . $theme_slug, array('body' => array('action' => 'version')));

				// if no error, retrivee body
				if (!is_wp_error($request)) {

					// decode to json
					$remote_version = json_decode($request['body'], true);

					// get remove version key
					if (isset($remote_version['version'])) {

						$remote_version = $remote_version['version'];

						// if remote version is bigger than current, display info about new version
						if ((float)$remote_version > (float)$current_version) {

							$title = ucwords(str_replace('_', ' ', $theme_slug));

							// create nonce
							$ajax_nonce = wp_create_nonce('cmp-coming-soon-ajax-secret');

							// if admin screen is not in updating theme
							if (!isset($_GET['theme']) || (isset($_GET['theme']) && $_GET['theme'] != $theme_slug)) {

								$transient = '<div class="notice notice-warning"><p class="message">' . sprintf(__('There is a <b>recommended</b> update of <b>CMP Theme: %s</b> available:', 'cmp-coming-soon-maintenance'), $title) . ' <a href="' . admin_url() . 'options-general.php?page=cmp-settings&action=update-cmp-theme&theme=' . esc_attr($theme_slug) . '&type=premium" class="cmp update-theme" data-type="premium" data-security="' . esc_attr($ajax_nonce) . '" data-slug="' . esc_attr($theme_slug) . '" data-name="' . esc_attr($title) . '" data-remote_url="' . esc_url(CMP_UPDATE_URL) . '" data-new_ver="' . esc_attr($remote_version) . '">' . sprintf(__(' click to update to %s version from NiteoThemes server now', 'cmp-coming-soon-maintenance'), esc_attr($remote_version)) . '!</a></div>';

								// set transient with 12 hour expire
								set_transient($theme_slug . '_updatecheck', $transient, 60 * 60 * 12);

								// die early if this is ajax request with status = true
								if ($ajax) {
									wp_die($remote_version);
									return;
								}


								echo $transient;
							}
						} else {
							// die early if this is ajax request with status = false
							if ($ajax) {
								wp_die('false');
								return;
							}
							// set transient no update available with 12 hours expire
							set_transient($theme_slug . '_updatecheck', '', 60 * 60 * 12);
						}
					}
				}

				// empty transient means theme was updated in last 24 hours
			} else if ($updatecheck_transient != '') {

				echo $updatecheck_transient;
			}

			if ($ajax) {
				wp_die('false');
			}

			return;
		}


/** Function cmp_theme_update_install() called by wp_ajax hooks: {'cmp_theme_update_install'} **/
/** Parameters found in function cmp_theme_update_install(): {"post": ["file"]} **/
function cmp_theme_update_install($file)
		{
			$ajax = false;
			$theme_slug = '';
			// check for ajax 
			if (isset($_POST['file'])) {
				// verify nonce
				check_ajax_referer('cmp-coming-soon-ajax-secret', 'security');
				// verify user rights
				if (!current_user_can('manage_options')) {
					die('Sorry, but this request is invalid');
				}

				// sanitize array
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

				if (!empty($_POST['file'])) {
					$file = $_POST['file'];
					$theme_slug = isset($file['name']) ? sanitize_key($file['name']) : '';
					$ajax   = true;
				}
			} else if (is_array($file) && isset($file['name'])) {
				$theme_slug = sanitize_key($file['name']);
			}

			// load PHP WP FILE 
			if (!empty($file)) {
				if ($theme_slug === '' || !in_array($theme_slug, $this->cmp_premium_themes_installed(), true)) {
					echo '<div class="notice notice-error is-dismissible"><p>' . __('Invalid theme update request.', 'cmp-coming-soon-maintenance') . '</p></div>';
					if ($ajax === true) {
						wp_die('error');
						return;
					}
					return;
				}

				$allowed_hosts = array(wp_parse_url(CMP_UPDATE_URL, PHP_URL_HOST));
				$file['url'] = add_query_arg(array('action' => 'download', 'slug' => $theme_slug), CMP_UPDATE_URL);
				$parsed_url = wp_parse_url($file['url']);

				if (empty($parsed_url['host']) || !in_array($parsed_url['host'], $allowed_hosts, true)) {
					echo '<div class="notice notice-error is-dismissible"><p>' . __('Invalid update URL.', 'cmp-coming-soon-maintenance') . '</p></div>';
					if ($ajax === true) {
						wp_die('error');
						return;
					}
					return;
				}

				$file['name'] = $theme_slug;

				// Download file to temp location.
				$file['tmp_name'] = download_url($file['url']);
				//WARNING: The file is not automatically deleted, The script must unlink() the file.

				// If error storing temporarily, return the error.
				if (!is_wp_error($file['tmp_name'])) {
					if (!class_exists('ZipArchive')) {
						wp_delete_file($file['tmp_name']);
						echo '<div class="notice notice-error is-dismissible"><p>' . __('ZIP validation is unavailable on this server.', 'cmp-coming-soon-maintenance') . '</p></div>';
						if ($ajax === true) {
							wp_die('error');
							return;
						}
						return;
					}

					$zip = new ZipArchive();
					if ($zip->open($file['tmp_name']) === TRUE) {
						for ($i = 0; $i < $zip->numFiles; $i++) {
							$entry = $zip->getNameIndex($i);
							if (preg_match('/\.(php|phtml|phar)$/i', $entry)) {
								$zip->close();
								wp_delete_file($file['tmp_name']);
								echo '<div class="notice notice-error is-dismissible"><p>' . __('ZIP contains forbidden file types.', 'cmp-coming-soon-maintenance') . '</p></div>';
								if ($ajax === true) {
									wp_die('error');
									return;
								}
								return;
							}
						}
						$zip->close();
					}

					WP_Filesystem();

					// create new theme DIR
					if (wp_mkdir_p(CMP_PREMIUM_THEMES_DIR)) {
						// Unzip FILE into that DIR
						$unzipfile = unzip_file($file['tmp_name'], CMP_PREMIUM_THEMES_DIR);

						if (!is_wp_error($unzipfile)) {
							// delete tmp FILE
							wp_delete_file($file['tmp_name']);

							// set transient no update available with 24 hours expire
							set_transient($file['name'] . '_updatecheck', '', 60 * 60 * 24);

							// die
							if ($ajax) {
								wp_die('success');
								return;
							} else {
								echo '<div class="notice notice-success is-dismissible"><p>CMP ' . ucwords(str_replace('_', ' ', $file['name'])) . ' ' . __('Theme has been updated to latest version!', 'cmp-coming-soon-maintenance') . '</p></div>';
								return;
							}
						} else {
							echo '<div class="notice notice-error is-dismissible"><p>' . __('There was an error unzipping the file due to error: ', 'cmp-coming-soon-maintenance') . $unzipfile->get_error_message() . '</p></div>';

							if ($ajax) {
								wp_die('error');
								return;
							}
						}
					} else {
						echo '<div class="notice notice-error is-dismissible"><p>' . __('Error creating Theme subdirectory!', 'cmp-coming-soon-maintenance') . '</p></div>';
						if ($ajax) {
							wp_die('error');
							return;
						}
					}
				} else {
					echo '<div class="notice notice-error is-dismissible"><p>' . __('Error during updating Theme files:', 'cmp-coming-soon-maintenance') . ' ' . $file['tmp_name']->get_error_message() . '</p></div>';
					if ($ajax === true) {
						wp_die('error');
						return;
					}
				}
			} else {

				echo '<div class="notice notice-error is-dismissible"><p>' . __('General Error during updating Theme files.', 'cmp-coming-soon-maintenance') . '</p></div>';
				if ($ajax === true) {
					wp_die('error');
					return;
				}
			}

			return;
		}


/** Function niteo_subscribe() called by wp_ajax hooks: {'nopriv_niteo_subscribe', 'niteo_subscribe'} **/
/** Parameters found in function niteo_subscribe(): {"post": ["ajax", "form_honeypot", "email", "token", "lastname", "firstname"], "server": ["REQUEST_METHOD", "REMOTE_ADDR"]} **/
function niteo_subscribe($check)
		{

			$subscribe_method = get_option('niteoCS_subscribe_method', 'cmp');
			$response = '';

			// get translation lists
			if (get_option('niteoCS_translation')) {
				$translation    		= json_decode(get_option('niteoCS_translation'), TRUE);
				$response_ok    		= $this->cmp_wpml_translate_string($translation[7]['translation'], 'Subscribe Response Thanks');
				$response_duplicate 	= $this->cmp_wpml_translate_string($translation[5]['translation'], 'Subscribe Response Duplicate');
				$response_invalid 		= $this->cmp_wpml_translate_string($translation[6]['translation'], 'Subscribe Response Not Valid');
			}

			$ajax = (isset($_POST['ajax']) && $_POST['ajax'] == TRUE) ? TRUE : FALSE;

			if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['form_honeypot']) && $_POST['form_honeypot'] === '' && isset($_POST['email'])) :

				if ($ajax) {
					check_ajax_referer('cmp-subscribe-action', 'security');
				}

				// check recatpcha score if integration is enabled
				if (get_option('niteoCS_recaptcha_status', '1') === '1' && !empty(get_option('niteoCS_recaptcha_site', ''))) {
					if (!$this->is_human(sanitize_text_field($_POST['token']))) {
						echo json_encode(array('status' => '0', 'message' => 'Sorry, robots not allowed.'));
						wp_die();
					}
				}

				if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
					// email already passed is_email, no need to sanitize
					$email = sanitize_email($_POST['email']);

					// sanitize all inputs
					$ip_address = (isset($_POST['lastname'])) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '';
					$firstname = (isset($_POST['firstname'])) ? sanitize_text_field($_POST['firstname']) : '';
					$lastname = (isset($_POST['lastname'])) ? sanitize_text_field($_POST['lastname']) : '';
					$timestamp = time();

					switch ($subscribe_method) {
							// default custom CMP method
						case 'cmp':
							// get subscribe list 
							$subscribe_list = get_option('niteoCS_subscribers_list');

							// if no subscribe list yet, create first item and insert it into DB
							if (!$subscribe_list) {
								$new_list = array();
								$new_email = array('id' => '0', 'timestamp' => $timestamp, 'email' => $email, 'ip_address' => $ip_address, 'firstname' => $firstname, 'lastname' => $lastname);
								array_push($new_list, $new_email);
								update_option('niteoCS_subscribers_list', $new_list);
								$response = array('status' => '1', 'message' => $response_ok);
							} else {
								// check if email don`t already exists
								if (!$this->niteo_in_array_r($email, $subscribe_list, true)) {
									$count = count($subscribe_list);
									$new_email = array('id' => $count, 'timestamp' => $timestamp, 'email' => $email, 'ip_address' => $ip_address, 'firstname' => $firstname, 'lastname' => $lastname);
									array_push($subscribe_list, $new_email);
									update_option('niteoCS_subscribers_list', $subscribe_list);
									$response = array('status' => '1', 'message' => $response_ok);
									// sent notif email
									if (get_option('niteoCS_subscribe_notification', '0')) {
										$subscribe_notif_email = get_option('niteoCS_subscribe_email_address', get_option('admin_email'));
										$subject = sprintf(__('You have a new Subscriber on %s!', 'cmp-coming-soon-maintenance'), get_site_url());
										$body = __('This is auto generated message from CMP - Coming Soon & Maintenance WordPress Plugin. You can disable these emails under CMP Advanced Settings > Email Notifications.', 'cmp-coming-soon-maintenance');
										$headers = array('Content-Type: text/plain; charset=UTF-8');
										wp_mail($subscribe_notif_email, $subject, $body, $headers);
									}

									// if email exists return duplicate response
								} else {
									$response = array('status' => '0', 'message' => $response_duplicate);
								}
							}
							break;

							// mailchimp API call
						case 'mailchimp':
							$api_key 	= esc_attr(get_option('niteoCS_mailchimp_apikey'));
							$list_id 	= esc_attr(get_option('niteoCS_mailchimp_list_selected'));
							$double_opt = get_option('niteoCS_mailchimp[double-opt]', '0');
							$status 	= ($double_opt == '1') ? 'pending' : 'subscribed'; // subscribed, cleaned, pending

							$args = array(
								'method' => 'PUT',
								'headers' => array(
									'Authorization' => 'Basic ' . base64_encode('user:' . $api_key)
								),
								'body' => json_encode(array(
									'email_address' 	=> $email,
									'status'        	=> $status,
									'merge_fields' 		=> array(
										'FNAME'		=> $firstname,
										'LNAME'		=> $lastname
									)
								))
							);

							$mailchimp = wp_remote_post('https://' . substr($api_key, strpos($api_key, '-') + 1) . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . md5(strtolower($email)), $args);

							if (!is_wp_error($mailchimp)) {

								$body = json_decode($mailchimp['body']);

								if ($mailchimp['response']['code'] == 200 && $body->status == $status) {
									$response = array('status' => '1', 'message' => $response_ok);
								} else {
									$response = array('status' => '0', 'message' => 'Error ' . $mailchimp['response']['code'] . ' ' . $body->title . ': ' . $body->detail);
								}
							} else {
								$error = $mailchimp->get_error_message();
								$response = array('status' => '0', 'message' => $error);
							}

							break;

							// MailPoet integration
						case 'mailpoet':
							$response = array('status' => '0', 'message' => __('Something went wrong please try again later.', 'cmp-coming-soon-maintenance'));
							$mailpoet_list = get_option('niteoCS_mailpoet_list_selected');
							$list_ids = array($mailpoet_list);
							$firstname = $firstname === '' ? null : $firstname;
							$lastname = $lastname === '' ? null : $lastname;
							$subscriber = array(
								'email' => $email,
								'first_name' => $firstname,
								'last_name' => $lastname
							);

							if (class_exists(\MailPoet\API\API::class)) {
								// Get MailPoet API instance
								$mailpoet_api = \MailPoet\API\API::MP('v1');

								// Check if subscriber exists. If subscriber doesn't exist an exception is thrown
								try {
									$subscribed = $mailpoet_api->getSubscriber($subscriber['email']);
								} catch (\Exception $e) {
								}

								try {
									if (!$subscribed) {
										// Subscriber doesn't exist let's create one
										$mailpoet_api->addSubscriber($subscriber, $list_ids);
										$response = array('status' => '1', 'message' => $response_ok);
									} else {
										// In case subscriber exists just add him to new lists
										$mailpoet_api->subscribeToLists($subscriber['email'], $list_ids);
										$response = array('status' => '1', 'message' => $response_ok);
									}
								} catch (\Exception $e) {
									$error_message = $e->getMessage();
									$response = array('status' => '0', 'message' => $error_message);
								}
							}

							break;

							// Mailster integration
						case 'mailster':
							$response = array('status' => '0', 'message' => __('Something went wrong please try again later.', 'cmp-coming-soon-maintenance'));
							$mailster_list_id = get_option('niteoCS_mailster_list_selected');

							if (function_exists('mailster')) {
								// define to overwrite existing users
								$overwrite = true;

								// add with double opt in
								$double_opt_in = true;

								$subscriber = array(
									'email' => $email,
									'firstname' => $firstname,
									'lastname' => $lastname,
									'status' => get_option('niteoCS_mailster_double_opt', '1') ? 0 : 1
								);

								// add a new subscriber and $overwrite it if exists
								$subscriber_id = mailster('subscribers')->add($subscriber, $overwrite);

								// if result isn't a WP_error assign the lists
								if (!is_wp_error($subscriber_id)) {
									mailster('subscribers')->assign_lists($subscriber_id, $mailster_list_id);
									$response = array('status' => '1', 'message' => $response_ok);
								} else {
									$response = array('status' => '0', 'message' => $subscriber_id->get_error_message());
								}
							}

							break;

						default:
							break;
					}

					// if not email, set response invalid
				} else {
					$response = array('status' => '0', 'message' => $response_invalid);
				}
			endif;

			if ($ajax === TRUE) {
				echo json_encode($response);
				wp_die();
			} else {
				return ($response == '') ? $response : json_encode($response);
			}
		}


/** Function niteo_themeinfo() called by wp_ajax hooks: {'niteo_themeinfo'} **/
/** Parameters found in function niteo_themeinfo(): {"post": ["theme_slug"]} **/
function niteo_themeinfo()
		{

			// check for ajax 
			if (isset($_POST['theme_slug'])) {
				// verify nonce
				check_ajax_referer('cmp-coming-soon-ajax-secret', 'security');
				// verify user rights
				if (!current_user_can('manage_options')) {
					die('Sorry, but this request is invalid');
				}


				// sanitize  $post
				$theme_slug = sanitize_key(wp_unslash($_POST['theme_slug']));
				$data = array('result' => 'true', 'author_homepage' => CMP_AUTHOR_HOMEPAGE, 'author' => CMP_AUTHOR);

				if (!empty($theme_slug) && in_array($theme_slug, $this->cmp_themes_available(), true)) {
					$headers  = array('Theme Name', 'Description');
					$theme_info = get_file_data(plugin_dir_path(__FILE__) . '/themes/' . $theme_slug . '.txt', $headers, '');

					$screenshots = array_map('basename', glob(plugin_dir_path(__FILE__) . 'img/thumbnails/' . $theme_slug . '/*'));

					foreach ($screenshots as $key => $screenshot) {
						$screenshots[$key] = plugins_url('img/thumbnails/' . $theme_slug . '/' . $screenshot, __FILE__);
					}

					$data['name'] = $theme_info[0];
					$data['description'] = $theme_info[1];
					$data['screenshots'] = $screenshots;
				}

				echo json_encode($data);
				wp_die();
			}
		}


/** Function cmp_ajax_dismiss_activation_notice() called by wp_ajax hooks: {'cmp_ajax_dismiss_activation_notice'} **/
/** No params detected :-/ **/


/** Function cmp_ajax_upload_font() called by wp_ajax hooks: {'cmp_ajax_upload_font'} **/
/** Parameters found in function cmp_ajax_upload_font(): {"post": ["payload"]} **/
function cmp_ajax_upload_font()
		{
			// verify nonce
			check_ajax_referer('cmp-coming-soon-ajax-secret', 'security');

			// verify user rights
			if (!current_user_can('manage_options')) {
				die('Sorry, but this request is invalid');
			}


			if (isset($_POST['payload'])) {

				$payload = json_decode(stripslashes($_POST['payload']), true);
				$action = $payload['action'];

				if ($action === 'upload_font') {

					$new_fonts = $payload['files'];

					// delete_option('niteoCS_custom_fonts');

					if (get_option('niteoCS_custom_fonts')) {

						$old_fonts = json_decode(get_option('niteoCS_custom_fonts'), true);

						$i = 0;

						foreach ($old_fonts as $old_font) {

							foreach ($new_fonts as $new_font) {
								if ($old_font['id'] === $new_font['id']) {

									$old_fonts[$i]['urls'] = (is_array($old_font['urls'])) ? array_unique(array_merge($old_font['urls'], $new_font['urls'])) : $new_font['urls'];
									$old_fonts[$i]['ids'] = (is_array($old_font['ids'])) ? array_unique(array_merge($old_font['ids'], $new_font['ids'])) : $new_font['ids'];
								} else if (!$this->niteo_in_array_r($new_font['id'], $old_fonts)) {
									array_push($old_fonts, $new_font);
								}
							}

							$i++;
						}

						$new_fonts = $old_fonts;
					}

					update_option('niteoCS_custom_fonts', json_encode($new_fonts));
				}
			}

			// echo confirmation
			echo 'success';
			wp_die();
		}


/** Function cmp_get_post_detail() called by wp_ajax hooks: {'nopriv_cmp_get_post_detail', 'cmp_get_post_detail'} **/
/** Parameters found in function cmp_get_post_detail(): {"post": ["id", "nonce"]} **/
function cmp_get_post_detail()
		{
			$id = isset($_POST['id']) ? esc_attr($_POST['id']) : '';
			$nonce = isset($_POST['nonce']) ? esc_attr($_POST['nonce']) : '';
			if (!wp_verify_nonce($nonce, 'cmp-coming-soon-ajax-secret')) {
				die('Sorry, but this request is invalid');
			}

			$size = 'large';

			$post = array(
				'img' 	=> '',
				'date' 	=> '',
				'title' => 'Post is not published or is Password protected',
				'body' 	=> '',
				'url' 	=> '',
			);

			if (get_post_status($id) === 'publish' && !post_password_required($id)) {

				$post =  array(
					'img' 	=> get_the_post_thumbnail($id, $size),
					'date' 	=> get_the_date('F j, Y', $id),
					'title' => get_the_title($id),
					'body' 	=> apply_filters('the_content', get_post_field('post_content', $id)),
					'url' 	=> get_the_permalink($id),
				);
			}

			wp_send_json($post);
		}


/** Function niteo_export_csv() called by wp_ajax hooks: {'niteo_export_csv'} **/
/** No params detected :-/ **/


/** Function cmp_ajax_toggle_activation() called by wp_ajax hooks: {'cmp_toggle_activation'} **/
/** Parameters found in function cmp_ajax_toggle_activation(): {"post": ["payload"]} **/
function cmp_ajax_toggle_activation()
		{
			// check for ajax payoload
			if (isset($_POST['payload']) && $_POST['payload'] == 'toggle_cmp_status') {

				// verify nonce
				check_ajax_referer('cmp-coming-soon-ajax-secret', 'security');
				// verify user rights
				if (!$this->cmp_user_can_admin_bar_activation()) {
					echo 'Current user cannot toggle CMP activation';
					wp_die();
					return;
				}

				if ($this->cmp_active() === '0') {
					update_option('niteoCS_status', '1');
					$this->cmp_send_notification('on');
				} else {
					update_option('niteoCS_status', '0');
					$this->cmp_send_notification('off');
				}

				$this->cmp_purge_cache();

				echo 'success';
				wp_die();
				return;
			}
		}


/** Function niteo_unsplash() called by wp_ajax hooks: {'niteo_unsplash'} **/
/** Parameters found in function niteo_unsplash(): {"post": ["params"]} **/
function niteo_unsplash($params)
		{
			$ajax = false;

			// check for ajax 
			if (isset($_POST['params'])) {
				// verify nonce
				check_ajax_referer('cmp-coming-soon-ajax-secret', 'security');
				// verify user rights
				if (!current_user_can('manage_options')) {
					die('Sorry, but this request is invalid');
				}

				// sanitize array
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

				if (!empty($_POST['params'])) {
					$params = $_POST['params'];
					$ajax   = true;
				}
			}

			array_key_exists('feed', $params) 			? $feed 		= sanitize_key($params['feed']) 		: $feed = '';
			array_key_exists('url', $params)			? $url 			= sanitize_text_field($params['url']) 		: $url = '';
			array_key_exists('feat', $params)			? $feat 			= sanitize_key($params['feat']) 		: $feat = '';
			array_key_exists('custom_str', $params)	? $custom_str 	= sanitize_text_field($params['custom_str']) : $custom_str = '';
			array_key_exists('count', $params)			? $count 		= min(30, max(1, absint($params['count']))) 		: $count = 1;

			switch ($feed) {
					// specific unsplash photo by url/id
				case '0':
					$id = '';
					// check if $query contains unsplash.com url
					if (strpos($url, 'unsplash.com') !== false) {
						$parts = parse_url($url);
						// check for photo parameter in URL
						if (isset($parts['query'])) {
							parse_str($parts['query'], $query);
							$id = $query['photo'];
						}
						// if no ID found, get last part of URL containing ID
						if ($id == '') {

							$pathFragments = explode('/', $parts['path']);
							$id = end($pathFragments);
						}

						// $query is ID
					} else {
						$id = $url;
					}

					// prepare query for single image
					$api_query = 'photos/' . rawurlencode($id) . '?';
					break;

					// random from user
				case '1':

					if ($custom_str[0] == '@') {
						$custom_str = substr($custom_str, 1);
					}

					// prepare query for random photo from collection
					$api_query = 'photos/random/?username=' . rawurlencode($custom_str) . '&count=' . $count;
					break;

					// random from collection
				case '2':
					if (is_numeric($url)) {
						$collection = $url;
					} else {
						$collection = filter_var($url, FILTER_SANITIZE_NUMBER_INT);
						$collection = str_replace('-', '', $collection);
					}

					// prepare query for random photo from collection
					$api_query = 'photos/random/?collections=' . $collection . '&count=' . $count;
					break;

					// random photo
				case '3':

					// featured
					if ($feat == '0' || $feat == '') {
						$featured = 'false';
					} else {
						$featured = 'true';
					}

					// category
					$search = str_replace(' ', ',', $url);

					if ($search !== '') {
						$search = 'query=' . rawurlencode($search) . '&';
					}
					// prepare query for random photo
					$api_query = 'photos/random/?orientation=landscape&featured=' . $featured . '&' . $search . 'count=' . $count;
					break;

				default:
					$api_query = 'photos/random/?orientation=landscape&count=' . $count;
					break;
			}

			$unsplash_img = $this->cmp_unsplash_api($api_query);

			if ($ajax === true) {
				echo json_encode($unsplash_img);
				wp_die();
			} else {
				return $unsplash_img;
			}
		}


