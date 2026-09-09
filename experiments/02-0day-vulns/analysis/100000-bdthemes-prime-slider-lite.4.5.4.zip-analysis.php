<?php
/***
*
*Found actions: 12
*Found functions:10
*Extracted functions:9
*Total parameter names extracted: 8
*Overview: {'install_plugins': {'bdtps_setup_wizard_install_plugins'}, 'getSelectInputData': {'prime_slider_dynamic_select_input_data'}, 'dismiss': {'prime_slider_biggopties'}, 'prime_slider_settings_save': {'prime_slider_settings_save'}, 'rc_sdk_insights': {'bdtps_reviews_insights'}, 'setup_wizard_nonce': {'bdtps_import_elementor_template', 'bdtps_import_elementor_bundle_runner_template', 'bdtps_import_elementor_bundle_template'}, 'rc_sdk_dismiss_biggopti': {'bdtps_reviews_dismiss'}, 'ajax_get_plugins': {'bdtps_get_plugins'}, 'ajax_fetch_api_biggopties': {'bdtps_fetch_api_biggopties'}, 'install_plugin_ajax': {'bdtps_install_plugin'}}
*
***/

/** Function install_plugins() called by wp_ajax hooks: {'bdtps_setup_wizard_install_plugins'} **/
/** Parameters found in function install_plugins(): {"post": ["plugins"]} **/
function install_plugins() {
		check_ajax_referer( 'setup_wizard_nonce', 'nonce' );

		$plugin_slugs = isset( $_POST['plugins'] ) ? map_deep( wp_unslash( $_POST['plugins'] ), 'sanitize_text_field' ) : array();

		if ( empty( $plugin_slugs ) || ! is_array( $plugin_slugs ) ) {
			wp_send_json_error( array( 'message' => 'Invalid plugins array' ) );
		}

		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_send_json_error( array( 'message' => 'Unauthorized' ) );
		}

		// Core admin files are loaded here, inside the request handler, and each
		// is used immediately below. class-wp-upgrader.php also loads the
		// WP_Upgrader_Skin classes, so they are not required separately.
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once __DIR__ . '/class-quiet-upgrader-skin.php';

		// Replace new \Plugin_Installer_Skin with new Quiet_Upgrader_Skin when output needs to be suppressed.
		$skin = new Quiet_Upgrader_Skin();
		// $skin     = new \Plugin_Installer_Skin( array( 'api' => $api ) );
		$upgrader = new \Plugin_Upgrader( $skin );

		// $upgrader = new \Plugin_Upgrader();

        $installedPlugins = get_plugins();
		$results = array();

		foreach ( $plugin_slugs as $plugin_slug ) {
            // skip when the plugin is already active
            if (is_plugin_active($plugin_slug)) {
                $results[] = array(
                    'slug'    => $plugin_slug,
                    'success' => true,
                    'message' => 'Installed and activated successfully',
                );
                continue;
            }

            // Download the plugin if the plugin is not installed
            if (!isset($installedPlugins[$plugin_slug])) {
                $slug = explode('/', $plugin_slug)[0];
                $api = plugins_api( 'plugin_information', array( 'slug' => $slug ) );

                if ( is_wp_error( $api ) ) {
                    $results[] = array(
                        'slug'    => $plugin_slug,
                        'success' => false,
                        'message' => $api->get_error_message(),
                    );
                    continue;
                }

                $result = $upgrader->install( $api->download_link );
                if ( is_wp_error( $result ) ) {
                    $results[] = array(
                        'slug'    => $plugin_slug,
                        'success' => false,
                        'message' => $result->get_error_message(),
                    );
                    continue;
                }
            }

            // active the plugin
            if ( is_plugin_inactive($plugin_slug) ) {
                $activation_result = activate_plugin( $plugin_slug );
                if ( is_wp_error( $activation_result ) ) {
                    $results[] = array(
                        'slug'    => $slug,
                        'success' => false,
                        'message' => $activation_result->get_error_message(),
                    );
                    continue;
                }

                $results[] = array(
                    'slug'    => $plugin_slug,
                    'success' => true,
                    'message' => 'Installed and activated successfully',
                );
            }
		}

		ob_clean();
		wp_send_json_success( array( 'results' => $results ) );
		wp_die();
	}


/** Function getSelectInputData() called by wp_ajax hooks: {'prime_slider_dynamic_select_input_data'} **/
/** Parameters found in function getSelectInputData(): {"post": ["security", "query"]} **/
function getSelectInputData()
	{
		$nonce = isset($_POST['security']) ? sanitize_text_field(wp_unslash($_POST['security'])) : '';

		try {
			if (!wp_verify_nonce($nonce, 'bdtps_dynamic_select')) {
				throw new Exception('Invalid request');
			}

			if (!current_user_can('edit_posts')) {
				throw new Exception('Unauthorized request');
			}

			$query = isset($_POST['query']) ? sanitize_key(wp_unslash($_POST['query'])) : '';

			if ($query == 'terms') {
				$data = $this->getTerms();
			} else if ($query == 'authors') {
				$data = $this->getAuthors();
			} else if ($query == 'authors_role') {
				$data = $this->getAuthorRoles();
			} else if ($query == 'only_post') {
				$data = $this->getOnlyPosts();
			} else {
				$data = $this->getPosts();
			}

			wp_send_json_success($data);
		} catch (Exception $e) {
			wp_send_json_error($e->getMessage());
		}

		die();
	}


/** Function dismiss() called by wp_ajax hooks: {'prime_slider_biggopties'} **/
/** Parameters found in function dismiss(): {"post": ["_wpnonce", "id", "time", "meta"]} **/
function dismiss() {
		$nonce = (isset($_POST['_wpnonce'])) ? sanitize_text_field(wp_unslash($_POST['_wpnonce'])) : '';
		$id   = isset($_POST['id']) ? sanitize_text_field(wp_unslash($_POST['id'])) : '';
		$time = isset($_POST['time']) ? absint(wp_unslash($_POST['time'])) : 0;
		$meta = isset($_POST['meta']) ? sanitize_text_field(wp_unslash($_POST['meta'])) : '';

		if ( ! wp_verify_nonce($nonce, 'bdthemes-prime-slider-lite') && ! wp_verify_nonce($nonce, 'prime-slider') ) {
			wp_send_json_error();
		}

		if ( ! current_user_can('manage_options') ) {
			wp_send_json_error();
		}

		/**
		 * Valid inputs?
		 */
		$key = self::dismissal_key($id);

		if ('' !== $key) {
			// Never trust the request-supplied lifetime either: clamp it to a
			// sane range so a dismissal cannot be stored effectively forever.
			$time = min(max($time, MINUTE_IN_SECONDS), YEAR_IN_SECONDS);

			// Handle regular biggopti
			if ('user' === $meta) {
				update_user_meta(get_current_user_id(), $key, true);
			} else {
				// Store in transient for backward compatibility
				set_transient($key, true, $time);

				// Also store in options table for persistence
				$dismissals_option = get_option('bdt_biggopti_dismissals', []);
				$dismissals_option[$key] = [
					'dismissed_at' => time(),
					'expires_at' => time() + $time,
				];
				update_option('bdt_biggopti_dismissals', $dismissals_option, false);
			}

			wp_send_json_success();
		}

		wp_send_json_error();
	}


/** Function prime_slider_settings_save() called by wp_ajax hooks: {'prime_slider_settings_save'} **/
/** Parameters found in function prime_slider_settings_save(): {"post": ["id"]} **/
function prime_slider_settings_save() {

			if ( ! check_ajax_referer( 'prime-slider-settings-save-nonce' ) ) {
				wp_send_json_error();
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$moudle_id = isset( $_POST['id'] ) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : '';

			unset( $_POST['id'] );

			// Only ever write options inside this plugin's own namespace. Without
			// this the option name was fully attacker-chosen, letting a request
			// overwrite arbitrary core options (default_role, siteurl, ...).
			if ( '' === $moudle_id || 0 !== strpos( $moudle_id, 'prime_slider' ) ) {
				wp_send_json_error();
			}

			if ( isset( $_POST[ $moudle_id ] ) ) {
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized just below via sanitize_options()/sanitize_default().
				$raw_value = wp_unslash( $_POST[ $moudle_id ] );

				// Route the value through the registered per-field sanitizers
				// instead of storing raw request data.
				$value = is_array( $raw_value )
					? $this->sanitize_options( $raw_value )
					: sanitize_text_field( $raw_value );

				update_option( $moudle_id, $value );
			}

			// if( prime_slider_is_asset_optimization_enabled() ){
			//     $optimize_assets = new();
			//     $optimize_assets->minifyCss();
			//     $optimize_assets->minifyJs();
			//     update_option( 'prime-slider-minified-asset-manager-version', time()) ;
			// } else {
			//     delete_option('prime-slider-minified-asset-manager-version') ;
			// }

			wp_send_json_success();

		}


/** Function rc_sdk_insights() called by wp_ajax hooks: {'bdtps_reviews_insights'} **/
/** Parameters found in function rc_sdk_insights(): {"post": ["button_val", "nonce", "allow_name", "date_name"]} **/
function rc_sdk_insights() {
			$sanitized_status = isset($_POST['button_val']) ? sanitize_text_field(wp_unslash($_POST['button_val'])) : '';
			$nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
			$allow_name = isset($_POST['allow_name']) ? sanitize_text_field(wp_unslash($_POST['allow_name'])) : '';
			$date_name = isset($_POST['date_name']) ? sanitize_text_field(wp_unslash($_POST['date_name'])) : '';

			// Confine the writes to this SDK's own option namespace so a request
			// cannot use these to overwrite an arbitrary WordPress option.
			if (0 !== strpos($allow_name, self::KEY_PREFIX . 'allow_')) {
				$allow_name = '';
			}
			if (0 !== strpos($date_name, self::KEY_PREFIX . 'date_')) {
				$date_name = '';
			}

			if (!wp_verify_nonce($nonce, self::KEY_PREFIX . 'sdk')) {
				wp_send_json(array(
					'status' => 'error',
					'title' => 'Error',
					'message' => 'Nonce verification failed',
				));
				wp_die();
			}

			if (!current_user_can('manage_options')) {
				wp_send_json(array(
					'status' => 'error',
					'title' => 'Error',
					'message' => 'Denied, you don\'t have right permission',
				));
				wp_die();
			}

			if ('disallow' == $sanitized_status && $allow_name) {
				update_option($allow_name, 'disallow');
			}

			if ($sanitized_status == 'skip' && $allow_name) {
				update_option($allow_name, 'skip');
				/**
				 * Next schedule date for attempt
				 */
				if ($date_name) {
					update_option($date_name, gmdate('Y-m-d', strtotime("+1 month")));
				}
			} elseif ($sanitized_status == 'yes' && $allow_name) {
				update_option($allow_name, 'yes');
			}

			wp_send_json(array(
				'status' => 'success',
				'title' => 'Success',
				'message' => 'Success.',
				'action' => $sanitized_status,
			));
			wp_die();
		}


/** Function setup_wizard_nonce() called by wp_ajax hooks: {'bdtps_import_elementor_template', 'bdtps_import_elementor_bundle_runner_template', 'bdtps_import_elementor_bundle_template'} **/
/** No function found :-/ **/


/** Function rc_sdk_dismiss_biggopti() called by wp_ajax hooks: {'bdtps_reviews_dismiss'} **/
/** Parameters found in function rc_sdk_dismiss_biggopti(): {"post": ["nonce", "rc_name"]} **/
function rc_sdk_dismiss_biggopti() {
			$nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
			$rc_name = isset($_POST['rc_name']) ? sanitize_text_field(wp_unslash($_POST['rc_name'])) : '';

			if (!wp_verify_nonce($nonce, self::KEY_PREFIX . 'sdk')) {
				wp_send_json(array(
					'status' => 'error',
					'title' => 'Error',
					'message' => 'Nonce verification failed',
				));
				wp_die();
			}

			if (!current_user_can('manage_options')) {
				wp_send_json(array(
					'status' => 'error',
					'title' => 'Error',
					'message' => 'Denied, you don\'t have right permission',
				));
				wp_die();
			}

			set_transient(self::KEY_PREFIX . 'dismissed_' . $rc_name, true, 30 * DAY_IN_SECONDS);

			wp_send_json(array(
				'status' => 'success',
				'title' => 'Success',
				'message' => 'Success.',
			));
			wp_die();
		}


/** Function ajax_get_plugins() called by wp_ajax hooks: {'bdtps_get_plugins'} **/
/** No params detected :-/ **/


/** Function ajax_fetch_api_biggopties() called by wp_ajax hooks: {'bdtps_fetch_api_biggopties'} **/
/** Parameters found in function ajax_fetch_api_biggopties(): {"post": ["_wpnonce", "current_url"]} **/
function ajax_fetch_api_biggopties() {
		$nonce = isset($_POST['_wpnonce']) ? sanitize_text_field(wp_unslash($_POST['_wpnonce'])) : '';
		if (!wp_verify_nonce($nonce, 'prime-slider')) {
			wp_send_json_error([ 'message' => 'invalid_nonce' ]);
		}

		if (!current_user_can('manage_options')) {
			wp_send_json_error([ 'message' => 'forbidden' ]);
		}

		// An add-on may ask for promotional notices to be left out entirely.
		if ($this->suppress_promotions()) {
			wp_send_json_success([ 'html' => '' ]);
		}

		// Don't show biggopties on plugin/theme install and upload pages
		$current_url = isset($_POST['current_url']) ? sanitize_text_field(wp_unslash($_POST['current_url'])) : '';
		
		if (!empty($current_url)) {
			$excluded_patterns = [
				'plugin-install.php',
				'theme-install.php',
				'action=upload-plugin',
				'action=upload-theme'
			];
			
			foreach ($excluded_patterns as $pattern) {
				if (strpos($current_url, $pattern) !== false) {
					wp_send_json_success([ 'html' => '' ]);
				}
			}
		}

		$biggopties = $this->get_api_biggopties_data();
		$grouped_biggopties = [];

		if (is_array($biggopties)) {
			foreach ($biggopties as $index => $biggopti) {
				if ($this->should_show_biggopti($biggopti)) {
					$display_id = isset($biggopti->display_id) ? $biggopti->display_id : 'default-' . $index;
					if (!isset($grouped_biggopties[$display_id])) {
						$grouped_biggopties[$display_id] = $biggopti;
					}
				}
			}
		}

		// Build biggopties using the same pipeline as synchronous rendering
		foreach ($grouped_biggopties as $display_id => $biggopti) {
			$biggopti_id = isset($biggopti->id) ? $display_id : $biggopti->id;

			self::add_biggopti([
				'id' => 'api-biggopti-' . $biggopti_id,
				'type' => isset($biggopti->type) ? $biggopti->type : 'info',
				'category'         => isset($biggopti->category) ? $biggopti->category : 'regular',
				'dismissible' => true,
				'html_message' => $this->render_api_biggopti($biggopti),
				'dismissible-meta' => 'transient',
				'dismissible-time' => isset($biggopti->end_date) ? max((new \DateTime($biggopti->end_date, new \DateTimeZone('UTC')))->getTimestamp() - time(), 0) : WEEK_IN_SECONDS,
			]);
		}

		ob_start();
		$this->show_biggopties();
		$markup = ob_get_clean();

		wp_send_json_success([ 'html' => $markup ]);
	}


/** Function install_plugin_ajax() called by wp_ajax hooks: {'bdtps_install_plugin'} **/
/** Parameters found in function install_plugin_ajax(): {"post": ["nonce", "plugin_slug"]} **/
function install_plugin_ajax() {
		// Check nonce
		if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'ps_install_plugin_nonce')) {
			wp_send_json_error(['message' => __('Security check failed', 'bdthemes-prime-slider-lite')]);
		}

		// Check user capability
		if (!current_user_can('install_plugins')) {
			wp_send_json_error(['message' => __('You do not have permission to install plugins', 'bdthemes-prime-slider-lite')]);
		}

		$plugin_slug = isset($_POST['plugin_slug']) ? sanitize_text_field(wp_unslash($_POST['plugin_slug'])) : '';

		if (empty($plugin_slug)) {
			wp_send_json_error(['message' => __('Plugin slug is required', 'bdthemes-prime-slider-lite')]);
		}

		// Include necessary WordPress files
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php';

		// Get plugin information
		$api = plugins_api('plugin_information', [
			'slug' => $plugin_slug,
			'fields' => [
				'sections' => false,
			],
		]);

		if (is_wp_error($api)) {
			wp_send_json_error(['message' => __('Plugin not found: ', 'bdthemes-prime-slider-lite') . $api->get_error_message()]);
		}

		// Install the plugin
		$skin = new \WP_Ajax_Upgrader_Skin();
		$upgrader = new \Plugin_Upgrader($skin);
		$result = $upgrader->install($api->download_link);

		if (is_wp_error($result)) {
			wp_send_json_error(['message' => __('Installation failed: ', 'bdthemes-prime-slider-lite') . $result->get_error_message()]);
		} elseif ($skin->get_errors()->has_errors()) {
			wp_send_json_error(['message' => __('Installation failed: ', 'bdthemes-prime-slider-lite') . $skin->get_error_messages()]);
		} elseif (is_null($result)) {
			wp_send_json_error(['message' => __('Installation failed: Unable to connect to filesystem', 'bdthemes-prime-slider-lite')]);
		}

		// Get installation status
		$install_status = install_plugin_install_status($api);
		
		wp_send_json_success([
			'message' => __('Plugin installed successfully!', 'bdthemes-prime-slider-lite'),
			'plugin_file' => $install_status['file'],
			'plugin_name' => $api->name
		]);
	}


