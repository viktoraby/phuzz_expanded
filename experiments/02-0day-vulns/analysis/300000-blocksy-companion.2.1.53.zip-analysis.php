<?php
/***
*
*Found actions: 30
*Found functions:15
*Extracted functions:9
*Total parameter names extracted: 4
*Overview: {'blocksy_companion_get_trending_posts': {'nopriv_blocksy_get_trending_posts', 'blocksy_get_trending_posts'}, 'edit_theme_options': {'blocksy_fs_connect_again'}, 'implement_user_login': {'blc_implement_user_login', 'nopriv_blc_implement_user_login'}, 'conditions': {'blc_retrieve_conditions_data'}, 'nonce': {'blocksy_customizer_export'}, 'implement_user_lostpassword': {'nopriv_blc_implement_user_lostpassword', 'blc_implement_user_lostpassword'}, 'get_lists': {'blocksy_ext_newsletter_subscribe_maybe_get_lists'}, 'get_actual_lists': {'blocksy_ext_newsletter_subscribe_get_actual_lists'}, 'manage_options': {'blocksy_customizer_import', 'blocksy_dynamic_data_block_custom_field_data', 'blocksy_customizer_wipe_caches', 'blocksy_blocks_retrieve_breadcrumbs_data_descriptor', 'blocksy_blocks_retrieve_dynamic_data_descriptor', 'blocksy_get_terms_block_patterns', 'blocksy_customizer_copy_options', 'blocksy_get_posts_block_patterns'}, 'newsletter_subscribe_process_ajax_subscribe': {'blc_newsletter_subscribe_process_ajax_subscribe', 'nopriv_blc_newsletter_subscribe_process_ajax_subscribe'}, 'load_cookies_consent_scripts': {'blocksy_companion_load_cookies_consent_scripts', 'nopriv_blocksy_companion_load_cookies_consent_scripts'}, 'implement_user_registration': {'blc_implement_user_registration', 'nopriv_blc_implement_user_registration'}, 'dashboard': {'blocksy_dashboard_handle_incorrect_license'}, 'edit_posts': {'blocksy_get_tax_block_data', 'blocksy_get_posts_block_data', 'blocksy_get_dynamic_block_view'}, 'save_credentials': {'blocksy_ext_newsletter_subscribe_maybe_save_credentials'}}
*
***/

/** Function blocksy_companion_get_trending_posts() called by wp_ajax hooks: {'nopriv_blocksy_get_trending_posts', 'blocksy_get_trending_posts'} **/
/** Parameters found in function blocksy_companion_get_trending_posts(): {"request": ["page"]} **/
function blocksy_companion_get_trending_posts() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if (! isset($_REQUEST['page'])) {
			wp_send_json_error();
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$page = intval(sanitize_text_field(wp_unslash($_REQUEST['page'])));

		if (! $page) {
			wp_send_json_error();
		}

		wp_send_json_success([
			'posts' => blocksy_companion_get_trending_posts_value([
				'paged' => $page
			])
		]);
	}


/** Function edit_theme_options() called by wp_ajax hooks: {'blocksy_fs_connect_again'} **/
/** No function found :-/ **/


/** Function implement_user_login() called by wp_ajax hooks: {'blc_implement_user_login', 'nopriv_blc_implement_user_login'} **/
/** Parameters found in function implement_user_login(): {"request": ["reauth"]} **/
function implement_user_login() {
		do_action('blocksy:account:user-flow:before-login');

		add_filter(
			'login_redirect',
			function ($redirect_to, $requested_redirect_to, $user) {
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$reauth = empty($_REQUEST['reauth']) ? false : true;

				if (! is_wp_error($user) && ! $reauth) {
					wp_send_json_success([
						'html' => '',
						'redirect_to' => apply_filters(
							'blocksy:account:modal:login:redirect_to',
							$redirect_to,
							$requested_redirect_to,
							$user
						)
					]);
				}

				return $redirect_to;
			},
			PHP_INT_MAX,
			3
		);

		ob_start();
		require_once ABSPATH . 'wp-login.php';
		$html = ob_get_clean();

		wp_send_json_success([
			'html' => $html,
			'redirect_to' => ''
		]);
	}


/** Function conditions() called by wp_ajax hooks: {'blc_retrieve_conditions_data'} **/
/** No function found :-/ **/


/** Function nonce() called by wp_ajax hooks: {'blocksy_customizer_export'} **/
/** No function found :-/ **/


/** Function implement_user_lostpassword() called by wp_ajax hooks: {'nopriv_blc_implement_user_lostpassword', 'blc_implement_user_lostpassword'} **/
/** No params detected :-/ **/


/** Function get_lists() called by wp_ajax hooks: {'blocksy_ext_newsletter_subscribe_maybe_get_lists'} **/
/** No params detected :-/ **/


/** Function get_actual_lists() called by wp_ajax hooks: {'blocksy_ext_newsletter_subscribe_get_actual_lists'} **/
/** No params detected :-/ **/


/** Function manage_options() called by wp_ajax hooks: {'blocksy_customizer_import', 'blocksy_dynamic_data_block_custom_field_data', 'blocksy_customizer_wipe_caches', 'blocksy_blocks_retrieve_breadcrumbs_data_descriptor', 'blocksy_blocks_retrieve_dynamic_data_descriptor', 'blocksy_get_terms_block_patterns', 'blocksy_customizer_copy_options', 'blocksy_get_posts_block_patterns'} **/
/** No function found :-/ **/


/** Function newsletter_subscribe_process_ajax_subscribe() called by wp_ajax hooks: {'blc_newsletter_subscribe_process_ajax_subscribe', 'nopriv_blc_newsletter_subscribe_process_ajax_subscribe'} **/
/** Parameters found in function newsletter_subscribe_process_ajax_subscribe(): {"post": ["EMAIL", "GROUP", "FNAME", "DOUBLE_OPTIN"]} **/
function newsletter_subscribe_process_ajax_subscribe() {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if (!isset($_POST['EMAIL'])) {
			wp_send_json_error();
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if (!isset($_POST['GROUP'])) {
			wp_send_json_error();
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$email = sanitize_email(wp_unslash($_POST['EMAIL']));
		$name = '';
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$group = sanitize_text_field(wp_unslash($_POST['GROUP']));

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if (isset($_POST['FNAME'])) {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			$name = sanitize_text_field(wp_unslash($_POST['FNAME']));
		}

		$double_optin = false;

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if (isset($_POST['DOUBLE_OPTIN'])) {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			$double_optin = sanitize_text_field(wp_unslash($_POST['DOUBLE_OPTIN'])) === '1';
		}

		$manager = \Blocksy\Extensions\NewsletterSubscribe\Provider::get_for_settings();

		$result = $manager->subscribe_form([
			'email' => $email,
			'name' => $name,
			'group' => $group,
			'double_optin' => $double_optin,
		]);

		wp_send_json_success($result);
	}


/** Function load_cookies_consent_scripts() called by wp_ajax hooks: {'blocksy_companion_load_cookies_consent_scripts', 'nopriv_blocksy_companion_load_cookies_consent_scripts'} **/
/** No params detected :-/ **/


/** Function implement_user_registration() called by wp_ajax hooks: {'blc_implement_user_registration', 'nopriv_blc_implement_user_registration'} **/
/** Parameters found in function implement_user_registration(): {"post": ["user_login", "user_email", "user_pass", "role"]} **/
function implement_user_registration() {
		do_action('blocksy:account:user-flow:before-registration');

		ob_start();
		require_once ABSPATH . 'wp-login.php';
		$res = ob_get_clean();

		$_POST['woocommerce-register-nonce'] = '~';
		add_filter('dokan_register_nonce_check', '__return_false');

		if (! $this->get_registration_strategy()) {
			exit;
		}

		$user_login = '';
		$user_email = '';
		$user_pass = '';
		$nonce_value = '';

		if (isset($_POST['user_login']) && is_string($_POST['user_login'])) {
			$user_login = sanitize_user(wp_unslash($_POST['user_login']));
		}

		if (isset($_POST['user_email']) && is_string($_POST['user_email'])) {
			$user_email = sanitize_email(wp_unslash($_POST['user_email']));
		}

		if (isset($_POST['user_pass']) && is_string($_POST['user_pass'])) {
			$user_pass = sanitize_text_field(wp_unslash($_POST['user_pass']));
		}

		if (
			isset($_POST['blocksy-register-nonce'])
			&&
			is_string($_POST['blocksy-register-nonce'])
		) {
			$nonce_value = sanitize_key($_POST['blocksy-register-nonce']);
		}

		if (!wp_verify_nonce($nonce_value, 'blocksy-register')) {
			wp_send_json_error([]);
			exit;
		}

		if ($this->get_registration_strategy() === 'woocommerce') {
			$validation_error = new \WP_Error();
			$validation_error = apply_filters(
				'woocommerce_process_registration_errors',
				$validation_error,
				$user_login,
				$user_pass,
				$user_email
			);

			$errors = wc_create_new_customer(
				sanitize_email($user_email),
				wc_clean($user_login),
				$user_pass
			);

			if (
				! is_wp_error($errors)
				&&
				apply_filters(
					'woocommerce_registration_auth_new_customer',
					true,
					$errors
				)
				&&
				isset($_POST['role'])
				&&
				$_POST['role'] === 'seller'
			) {
				ob_start();
				wc_set_customer_auth_cookie($errors);
				ob_clean();
			}
		} else {
			$errors = register_new_user($user_login, $user_email);
		}

		if (! is_wp_error($errors)) {
			$errors = new \WP_Error();

			if ($this->get_registration_strategy() === 'woocommerce') {
				$error_message = blocksy_companion_safe_sprintf(
					/* translators: 1: link open 2: link close */
					__(
						'Your account was created successfully. Your login details have been sent to your email address. Please visit the %1$slogin page%2$s.',
						'blocksy-companion'
					),
					'<a href="#" data-login="yes">',
					'</a>'
				);

				if ('yes' === get_option('woocommerce_registration_generate_password')) {
					$error_message = blocksy_companion_safe_sprintf(
						/* translators: 1: link open 2: link close */
						__(
							'Your account was created successfully and a password has been sent to your email address. Please visit the %1$slogin page%2$s.',
							'blocksy-companion'
						),
						'<a href="#" data-login="yes">',
						'</a>'
					);
				}

				$errors->add('registered', $error_message, 'message');
			} else {
				$errors->add(
					'registered',
					blocksy_companion_safe_sprintf(
						/* translators: 1: link open 2: link close */
						__(
							'Registration complete. Please check your email, then visit the %1$slogin page%2$s.',
							'blocksy-companion'
						),
						'<a href="#" data-login="yes">',
						'</a>'
					),
					'message'
				);
			}

			$redirect_to = admin_url();
			$errors = apply_filters('wp_login_errors', $errors, $redirect_to);

			login_header(__('Check your email', 'blocksy-companion'), '', $errors);

			wp_die();
		}

		login_header(
			__('Registration Form', 'blocksy-companion'),
			'<p class="message register">' . __('Register For This Site', 'blocksy-companion') . '</p>',
			$errors
		);

		wp_die();
	}


/** Function dashboard() called by wp_ajax hooks: {'blocksy_dashboard_handle_incorrect_license'} **/
/** No function found :-/ **/


/** Function edit_posts() called by wp_ajax hooks: {'blocksy_get_tax_block_data', 'blocksy_get_posts_block_data', 'blocksy_get_dynamic_block_view'} **/
/** No function found :-/ **/


/** Function save_credentials() called by wp_ajax hooks: {'blocksy_ext_newsletter_subscribe_maybe_save_credentials'} **/
/** No params detected :-/ **/


