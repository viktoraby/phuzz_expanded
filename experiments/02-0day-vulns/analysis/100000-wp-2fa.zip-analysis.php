<?php
/***
*
*Found actions: 43
*Found functions:39
*Extracted functions:39
*Total parameter names extracted: 34
*Overview: {'dismiss_extra_event_banner': {'wp_2fa_dismiss_extra_event_banner'}, 'ajax_dismiss': {'wp2fa_dismiss_free_support_notice'}, 'ajax_search_items': {'wp2fa_search_policy_items'}, 'logout_account': {'wp2fa_logout_account'}, 'unset_salt_key': {'unset_salt_key'}, 'wp2fa_profile_enable_key': {'wp2fa_profile_enable_key'}, 'ajax_dismiss_banner': {'wp2fa_dismiss_new_interface_banner'}, 'ajax_send_test_email': {'wp2fa_send_test_email_new'}, 'send_authentication_setup_email': {'send_authentication_setup_email'}, 'validate_authcode_via_ajax': {'validate_authcode_via_ajax'}, 'handle_send_test_email_ajax': {'wp2fa_test_email'}, 'revoke_profile_key': {'wp2fa_profile_revoke_key'}, 'dismiss_nag': {'dismiss_nag', 'wp2fa_dismiss_reconfigure_nag'}, 'send_backup_codes_email': {'send_backup_codes_email'}, 'ajax_switch_new_interface': {'wp2fa_switch_new_interface'}, 'ajax_dismiss_dialog': {'wp2fa_dismiss_new_interface_dialog'}, 'ajax_activate_license': {'wp2fa_edd_activate_license'}, 'redirect_after_logout': {'custom_ajax_logout'}, 'dismiss_survey_notice': {'dismiss_survey_notice'}, 'ajax_dismiss_required_intro': {'wp2fa_dismiss_required_intro'}, 'unlock_account': {'unlock_account'}, 'get_ajax_user_roles': {'wp_2fa_get_all_roles'}, 'dismiss_update_notice': {'dismiss_update_notice'}, 'remove_user_2fa': {'remove_user_2fa'}, 'survey_taken': {'wp_2fa_take_survey'}, 'regenerate_authentication_key': {'regenerate_authentication_key'}, 'dismiss_survey_banner': {'wp_2fa_dismiss_survey_banner'}, 'register_response': {'wp2fa_profile_response'}, 'signin_request': {'nopriv_wp2fa_signin_request', 'wp2fa_signin_request'}, 'passkey_rename': {'wp2fa_user_passkey_rename'}, 'dismiss_notice_mail_domain': {'wp2fa_dismiss_notice_mail_domain'}, 'signin_response': {'wp2fa_signin_response', 'nopriv_wp2fa_signin_response'}, 'register_request': {'wp2fa_profile_register'}, 'get_all_users': {'wp_2fa_get_all_users'}, 'ajax_deactivate_license': {'wp2fa_edd_deactivate_license'}, 'ajax_save': {'wp2fa_save_settings_new', 'wp2fa_save_policies_new'}, 'run_ajax_generate_json': {'wp2fa_run_ajax_generate_json'}, 'set_salt_key': {'set_salt_key'}, 'get_all_network_sites': {'wp_2fa_get_all_network_sites'}}
*
***/

/** Function dismiss_extra_event_banner() called by wp_ajax hooks: {'wp_2fa_dismiss_extra_event_banner'} **/
/** Parameters found in function dismiss_extra_event_banner(): {"post": ["nonce"]} **/
function dismiss_extra_event_banner() {
			// Grab POSTed data.
			$nonce = isset( $_POST['nonce'] ) ? \sanitize_text_field( \wp_unslash( $_POST['nonce'] ) ) : false;

			// Check nonce.
			if ( ! \current_user_can( 'manage_options' ) || empty( $nonce ) || ! $nonce || ! \wp_verify_nonce( $nonce, 'wp_2fa_dismiss_extra_event_banner_nonce' ) ) {
				\wp_send_json_error( \esc_html__( 'Nonce Verification Failed.', 'wp-2fa' ) );
			}

			\delete_site_option( WP_2FA_PREFIX . '_extra_event_banner' );
			\update_site_option( WP_2FA_PREFIX . '_extra_event_banner_dismissed', 'yes' );

			$today_date = gmdate( 'Y-m-d' );
			$today_date = gmdate( 'Y-m-d', strtotime( $today_date ) );

			if ( gmdate( 'Y-m-d', strtotime( '11/28/2025' ) ) === $today_date ) {
				\update_site_option( WP_2FA_PREFIX . '_extra_event_banner_super_dismissed', 'yes' );
			}

			\wp_send_json_success( \esc_html__( 'Complete.', 'wp-2fa' ) );
		}


/** Function ajax_dismiss() called by wp_ajax hooks: {'wp2fa_dismiss_free_support_notice'} **/
/** Parameters found in function ajax_dismiss(): {"post": ["nonce"]} **/
function ajax_dismiss() {
			$nonce = isset( $_POST['nonce'] ) ? \sanitize_text_field( \wp_unslash( $_POST['nonce'] ) ) : '';

			if ( ! \current_user_can( 'manage_options' ) || ! \wp_verify_nonce( $nonce, 'wp2fa_free_support_notice' ) ) {
				\wp_send_json_error( \esc_html__( 'Nonce verification failed.', 'wp-2fa' ) );
			}

			Settings_Utils::update_option( self::DISMISS_OPTION, true );

			\wp_send_json_success();
		}


/** Function ajax_search_items() called by wp_ajax hooks: {'wp2fa_search_policy_items'} **/
/** Parameters found in function ajax_search_items(): {"get": ["nonce", "type", "term"]} **/
function ajax_search_items() {
			$nonce = isset( $_GET['nonce'] ) ? \sanitize_text_field( \wp_unslash( $_GET['nonce'] ) ) : '';
			if ( ! \wp_verify_nonce( $nonce, 'wp2fa_save_policies_new_nonce' ) ) {
				\wp_send_json_error(
					array( 'message' => \esc_html__( 'Security check failed.', 'wp-2fa' ) ),
					403
				);
			}

			if ( ! \current_user_can( 'manage_options' ) ) {
				\wp_send_json_error(
					array( 'message' => \esc_html__( 'Permission denied.', 'wp-2fa' ) ),
					403
				);
			}

			$type = isset( $_GET['type'] ) ? \sanitize_text_field( \wp_unslash( $_GET['type'] ) ) : '';
			$term = isset( $_GET['term'] ) ? \sanitize_text_field( \wp_unslash( $_GET['term'] ) ) : '';

			$allowed_types = array( 'users', 'roles', 'sites' );
			if ( ! in_array( $type, $allowed_types, true ) ) {
				\wp_send_json_error(
					array( 'message' => \esc_html__( 'Invalid search type.', 'wp-2fa' ) ),
					400
				);
			}

			if ( strlen( $term ) < 2 ) {
				\wp_send_json_success( array() );
			}

			$results = array();

			if ( 'users' === $type ) {
				$args = array(
					'search'         => '*' . $term . '*',
					'search_columns' => array( 'user_login', 'display_name' ),
					'number'         => 20,
					'fields'         => array( 'ID', 'user_login' ),
				);

				if ( WP_Helper::is_multisite() ) {
					$args['blog_id'] = 0;
				}

				$users = \get_users( $args );

				foreach ( $users as $user ) {
					$results[] = array(
						'id'   => $user->user_login,
						'text' => $user->user_login,
					);
				}
			} elseif ( 'roles' === $type ) {
				foreach ( WP_Helper::get_roles_wp() as $role_slug => $role_name ) {
					if ( false !== stripos( $role_name, $term ) || false !== stripos( $role_slug, $term ) ) {
						$results[] = array(
							'id'   => strtolower( $role_slug ),
							'text' => $role_name,
						);
					}
				}
			} elseif ( 'sites' === $type && WP_Helper::is_multisite() ) {
				foreach ( WP_Helper::get_multi_sites() as $site ) {
					if ( false !== stripos( $site->blogname, $term ) || false !== stripos( $site->domain, $term ) || (string) $site->blog_id === $term ) {
						$results[] = array(
							'id'   => (string) $site->blog_id,
							'text' => $site->blogname,
						);
					}
				}
			}

			\wp_send_json_success( $results );
		}


/** Function logout_account() called by wp_ajax hooks: {'wp2fa_logout_account'} **/
/** Parameters found in function logout_account(): {"request": ["_wpnonce"]} **/
function logout_account() {
			if ( ! self::check_rate_limit( 'logout_account' ) ) {
				\wp_send_json_error( \esc_html__( 'Rate limit exceeded. Please try again later.', 'wp-2fa' ) );
			}

			if ( ! empty( $_REQUEST['_wpnonce'] ) && \wp_verify_nonce( \sanitize_text_field( \wp_unslash( $_REQUEST['_wpnonce'] ) ), 'wp-2fa-logout' ) ) {
				$user_id = \get_current_user_id();

				\wp_destroy_current_session();
				\wp_clear_auth_cookie();
				\wp_set_current_user( 0 );

				/**
				 * Fires after a user is logged out.
				 *
				 * @since 1.5.0
				 * @since 5.5.0 Added the `$user_id` parameter.
				 *
				 * @param int $user_id ID of the user that was logged out.
				 */
				\do_action( 'wp_logout', $user_id );

				\wp_send_json_success( esc_html__( 'User successfully logged out! ', 'wp-2fa' ) );
			}

			\wp_send_json_error( esc_html__( 'Failed - wrong credentials.', 'wp-2fa' ) );
		}


/** Function unset_salt_key() called by wp_ajax hooks: {'unset_salt_key'} **/
/** Parameters found in function unset_salt_key(): {"request": ["_wpnonce"]} **/
function unset_salt_key(): void {
			if ( \wp_doing_ajax() ) {
				if ( isset( $_REQUEST['_wpnonce'] ) ) {
					$nonce_check = \wp_verify_nonce( \sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'wp-2fa-unset-salt-nonce' );
					if ( ! $nonce_check ) {
						\wp_send_json_error( new \WP_Error( 500, esc_html__( 'Nonce checking failed', 'wp-2fa' ) ), 400 );
					} elseif ( \current_user_can( 'manage_options' ) ) {

						Settings_Utils::update_option( 'remove_store_salt_in_wp_config_message', true );

						\wp_send_json_success(
							\esc_html__(
								'message is set for removal',
								'wp-2fa'
							)
						);

					}
				}
			}
		}


/** Function wp2fa_profile_enable_key() called by wp_ajax hooks: {'wp2fa_profile_enable_key'} **/
/** Parameters found in function wp2fa_profile_enable_key(): {"post": ["user_id", "fingerprint"]} **/
function wp2fa_profile_enable_key() {

			// Accept both legacy and new action names for compatibility.
			self::validate_nonce( 'wp2fa-user-passkey-enable' );
			// Fallback to legacy action if the first failed (validate_nonce will die on failure),
			// so we only call it if the request still proceeds.

			$user_id     = (int) \sanitize_text_field( \wp_unslash( ( $_POST['user_id'] ?? 0 ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$fingerprint = (string) \sanitize_text_field( \wp_unslash( ( $_POST['fingerprint'] ?? '' ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

			$current = \get_current_user_id();
			if ( $current !== $user_id && ! \current_user_can( 'edit_user', $user_id ) ) {
				\wp_send_json_error( __( 'Insufficient permissions.', 'wp-2fa' ), 403 );

				\wp_die();
			}

			if ( ! $fingerprint ) {
				return new \WP_Error( 'invalid_request', __( 'Invalid request.', 'wp-2fa' ), array( 'status' => 400 ) );
			}

			try {
				$meta_key = Source_Repository::PASSKEYS_META . $fingerprint;

				$user = \get_user_by( 'ID', $user_id );

				$user_data = \json_decode( (string) \get_user_meta( $user->ID, $meta_key, true ), true, 512, JSON_THROW_ON_ERROR );

				// Update the meta value.
				if ( isset( $user_data['extra']['enabled'] ) ) {
					$user_data['extra']['enabled'] = ! (bool) $user_data['extra']['enabled'];
				} else {
					$user_data['extra']['enabled'] = false;
				}
				$public_key_json = \wp_json_encode( $user_data, JSON_UNESCAPED_SLASHES );
				\update_user_meta( $user->ID, $meta_key, $public_key_json );
			} catch ( \Exception $error ) {
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- Server-side security logging only.
					\error_log( sprintf( '[WP-2FA] enable_key error: %s', $error->getMessage() ) );
				}
				return new \WP_Error( 'invalid_request', __( 'Invalid request.', 'wp-2fa' ), array( 'status' => 400 ) );
			}

			\wp_send_json_success( 2, 200 );
		}


/** Function ajax_dismiss_banner() called by wp_ajax hooks: {'wp2fa_dismiss_new_interface_banner'} **/
/** Parameters found in function ajax_dismiss_banner(): {"post": ["nonce"]} **/
function ajax_dismiss_banner() {
			$nonce = isset( $_POST['nonce'] ) ? \sanitize_text_field( \wp_unslash( $_POST['nonce'] ) ) : '';

			if ( ! \current_user_can( 'manage_options' ) || ! \wp_verify_nonce( $nonce, 'wp2fa_new_interface_banner' ) ) {
				\wp_send_json_error( \esc_html__( 'Nonce verification failed.', 'wp-2fa' ) );
			}

			Settings_Utils::delete_option( self::SHOW_BANNER_OPTION );

			\wp_send_json_success();
		}


/** Function ajax_send_test_email() called by wp_ajax hooks: {'wp2fa_send_test_email_new'} **/
/** Parameters found in function ajax_send_test_email(): {"post": ["nonce", "subject", "body"]} **/
function ajax_send_test_email() {
			// 1. Nonce check.
			$nonce = isset( $_POST['nonce'] ) ? \sanitize_text_field( \wp_unslash( $_POST['nonce'] ) ) : '';
			if ( ! \wp_verify_nonce( $nonce, 'wp2fa_send_test_email_new_nonce' ) ) {
				\wp_send_json_error(
					array( 'message' => \esc_html__( 'Security check failed. Please refresh the page and try again.', 'wp-2fa' ) ),
					403
				);
			}

			// 2. Capability check.
			if ( ! \current_user_can( 'manage_options' ) ) {
				\wp_send_json_error(
					array( 'message' => \esc_html__( 'You do not have permission to perform this action.', 'wp-2fa' ) ),
					403
				);
			}

			// 3. Resolve the recipient — always the current user.
			$user  = \wp_get_current_user();
			$email = \sanitize_email( $user->user_email );

			if ( empty( $email ) ) {
				\wp_send_json_error(
					array( 'message' => \esc_html__( 'Your account does not have an email address.', 'wp-2fa' ) )
				);
			}

			// 4. Determine subject & body.
			$raw_subject = isset( $_POST['subject'] ) ? \sanitize_text_field( \wp_unslash( $_POST['subject'] ) ) : '';
			$raw_body    = isset( $_POST['body'] ) ? \wp_kses_post( \wp_unslash( $_POST['body'] ) ) : '';

			if ( '' === $raw_body ) {
				// Generic delivery test — no template content available.
				$subject = \esc_html__( 'Test email from WP 2FA', 'wp-2fa' );
				$message = \esc_html__( 'This email was sent by the WP 2FA plugin to test the email delivery.', 'wp-2fa' );
			} else {
				$subject = $raw_subject;
				$message = \wpautop( Email_Templates::replace_email_strings( $raw_body, (string) $user->ID ) );
			}

			// 5. Send.
			$sent = Settings_Page::send_email( $email, $subject, $message );

			if ( $sent ) {
				\wp_send_json_success(
					array(
						/* translators: %s: recipient email address */
						'message' => \wp_sprintf( \esc_html__( 'Test email was successfully sent to %s', 'wp-2fa' ), '<strong>' . \esc_html( $email ) . '</strong>' ),
					)
				);
			}

			\wp_send_json_error(
				array( 'message' => \wp_sprintf( \esc_html__( 'Failed to send the test email. This is usually caused by an SMTP issue, a restricted "from" address, or your host blocking outgoing mail. Check your email settings or contact your hosting provider. %s.', 'wp-2fa' ), \wp_sprintf( '<a href="%s" target="_blank">%s</a>', 'https://melapress.com/support/kb/troubleshoot-2fa-email-delivery/?utm_source=plugin&utm_medium=wp2fa&utm_campaign=guide_troubleshoot_2fa_email_delivery&utm_content=test_email_error', \esc_html__( 'Read more about email deliverability', 'wp-2fa' ) ) ) )
			);
		}


/** Function send_authentication_setup_email() called by wp_ajax hooks: {'send_authentication_setup_email'} **/
/** Parameters found in function send_authentication_setup_email(): {"post": ["nonce", "email_address"]} **/
function send_authentication_setup_email( $user_id, $nominated_email_address = 'nominated_email_address', $is_reset_protection = false ) {

			// If we have a nonce posted, check it.
			if ( \wp_doing_ajax() && isset( $_POST['nonce'] ) ) {
				$nonce_check = \wp_verify_nonce( \sanitize_text_field( \wp_unslash( $_POST['nonce'] ) ), 'wp-2fa-send-setup-email' );
				if ( ! $nonce_check ) {
					\wp_send_json_error( new \WP_Error( 400, \esc_html__( 'Nonce checking failed', 'wp-2fa' ) ), 400 );
					return false;
				}

				unset( $user_id );
			}

			if ( empty( $user_id ) ) {
				$user = \wp_get_current_user();
			} else {
				$user = \get_userdata( $user_id );
			}

			if ( ! $user ) {
				return false;
			}

			// Grab email address if it's provided.
			$email = \sanitize_email( $user->user_email );

			if ( isset( $_POST['email_address'] ) && ! empty( trim( $_POST['email_address'] ) ) ) {
				$email = \sanitize_email( \wp_unslash( $_POST['email_address'] ) );

				if ( ! \is_email( $email ) ) {
					if ( \wp_doing_ajax() && isset( $_POST['nonce'] ) ) {
						\wp_send_json_error( new \WP_Error( 400, \esc_html__( 'Please use a valid email address', 'wp-2fa' ) ), 400 );
						return false;
					}

					return false;
				}

				if ( ! Settings_Utils::get_setting_role( User_Helper::get_user_role( $user ), 'specify-email_hotp' ) ) {
					\wp_send_json_error( new \WP_Error( 400, \esc_html__( 'The email address in the request does not match the user\'s email address. Please try again.', 'wp-2fa' ) ), 400 );

					return false;
				}
			}

			$email_address = '';

			if ( \wp_doing_ajax() && isset( $_POST['nonce'] ) ) {
				$email_address = $email;	
				// User_Helper::set_nominated_email_for_user( $email, $user );
			} elseif ( ! empty( $nominated_email_address ) ) {
				if ( 'nominated_email_address' === $nominated_email_address ) {
					$email_address = User_Helper::get_nominated_email_for_user( $user );
				} elseif ( 'backup_email_address' === $nominated_email_address ) {
					$email_address = User_Helper::get_backup_email_for_user( $user );
				}
			} else {
				$email_address = $user->user_email;
			}

			// Generate a token and setup email.
			$token = Authentication::generate_token( $user->ID );


			if ( $is_reset_protection ) {
			} elseif ( wp_doing_ajax() && isset( $_POST['nonce'] ) ) {
				$subject = wp_strip_all_tags( Email_Templates::replace_email_strings( Email_Templates::get_wp2fa_email_templates( 'login_code_setup_email_subject' ), $user->ID, $token ) );
				$message = wpautop( Email_Templates::replace_email_strings( Email_Templates::get_wp2fa_email_templates( 'login_code_setup_email_body' ), $user->ID, $token ) );
			} else {
				$subject = wp_strip_all_tags( Email_Templates::replace_email_strings( Email_Templates::get_wp2fa_email_templates( 'login_code_email_subject' ), $user->ID ) );
				$message = wpautop( Email_Templates::replace_email_strings( Email_Templates::get_wp2fa_email_templates( 'login_code_email_body' ), $user->ID, $token ) );
			}

			// @free:start
			$message         .= '<p>' . \esc_html__( 'Email sent by', 'wp-2fa' );
			$message .= ' <a href="https://melapress.com/wordpress-2fa/?&utm_source=plugin&utm_medium=wp2fa&utm_campaign=melapress_wp_2fa_plugin_link" target="_blank">' . \esc_html__( 'WP 2FA plugin.', 'wp-2fa' ) . '</a>';
			$message .= '</p>';
			// @free:end

			// If we have a nonce posted, check it.
			if ( \wp_doing_ajax() && isset( $_POST['nonce'] ) ) {
				$mail_sent = Settings_Page::send_email( $email_address, $subject, $message );
				if ( ! $mail_sent ) {
					\wp_send_json_error( new \WP_Error( 500, \esc_html__( 'Email sending failed', 'wp-2fa' ) ), 400 );
					return false;
				}

				\wp_send_json_success( new \WP_Error( 200, \esc_html__( 'Email sent successfully', 'wp-2fa' ) ), 200 );

				return $mail_sent;
			}

			return Settings_Page::send_email( $email_address, $subject, $message );
		}


/** Function validate_authcode_via_ajax() called by wp_ajax hooks: {'validate_authcode_via_ajax'} **/
/** Parameters found in function validate_authcode_via_ajax(): {"post": ["form"]} **/
function validate_authcode_via_ajax() {
			check_ajax_referer( 'wp-2fa-validate-authcode' );

			if ( isset( $_POST['form'] ) ) {
				$input = wp_unslash( $_POST['form'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			} else {
				wp_send_json_error(
					array(
						'error' => \esc_html__( 'No form', 'wp-2fa' ),
					)
				);
			}

			$user = wp_get_current_user();

			$our_errors = '';

			// Grab key from the $_POST.
			if ( isset( $input['wp-2fa-totp-key'] ) ) {
				$current_key = sanitize_text_field( wp_unslash( $input['wp-2fa-totp-key'] ) );
			}

			// Grab authcode and validate format.
			if ( isset( $input['wp-2fa-totp-authcode'] ) ) {
				$raw_authcode = trim( (string) $input['wp-2fa-totp-authcode'] );
				if ( '' !== $raw_authcode && ! preg_match( '/^\d{6}$/', $raw_authcode ) ) {
					wp_send_json_error(
						array(
							'error' => \esc_html__( 'Invalid code. Please enter the correct OTP code generated by your Authenticator app.', 'wp-2fa' ),
						)
					);
				}
				$input['wp-2fa-totp-authcode'] = (int) $input['wp-2fa-totp-authcode'];
			}

			// Check if we are dealing with totp or email, if totp validate and store a new secret key.
			if ( ! empty( $input['wp-2fa-totp-authcode'] ) && ! empty( $current_key ) ) {
				if ( Authentication::is_valid_key( $current_key ) || ! is_numeric( $input['wp-2fa-totp-authcode'] ) ) {
					if ( ! Authentication::is_valid_authcode( $current_key, \sanitize_text_field( \wp_unslash( $input['wp-2fa-totp-authcode'] ) ) ) ) {
						$our_errors = \esc_html__( 'Invalid or expired OTP code. Please try again.', 'wp-2fa' );
					}
				} else {
					$our_errors = \esc_html__( 'Invalid Two Factor Authentication secret key.', 'wp-2fa' );
				}

				// If its not totp, is it email.
			} elseif ( ! empty( $input['wp-2fa-email-authcode'] ) ) {
				if ( ! Authentication::validate_token( $user, sanitize_text_field( wp_unslash( $input['wp-2fa-email-authcode'] ) ) ) ) {
					$our_errors = \esc_html__( 'Invalid Email Authentication code.', 'wp-2fa' );
				}
			} else {
				$our_errors = \esc_html__( 'Please enter the code to finalize the 2FA setup.', 'wp-2fa' );
			}

			if ( ! empty( $our_errors ) ) {
				// Send the response.
				wp_send_json_error(
					array(
						'error' => $our_errors,
					)
				);
			} else {
				self::save_user_2fa_options( $input );
				// Send the response.
				wp_send_json_success();
			}

			wp_send_json_error(
				array(
					'error' => \esc_html__( 'Error processing form', 'wp-2fa' ),
				)
			);
		}


/** Function handle_send_test_email_ajax() called by wp_ajax hooks: {'wp2fa_test_email'} **/
/** Parameters found in function handle_send_test_email_ajax(): {"post": ["email_id", "_wpnonce"]} **/
function handle_send_test_email_ajax() {
			// Check user permissions.
			if ( ! \current_user_can( 'manage_options' ) ) {
				\wp_send_json_error( \esc_html__( 'Access Denied.', 'wp-2fa' ) );
			}

			// Check email ID.
			$email_id = isset( $_POST['email_id'] ) ? \sanitize_text_field( \wp_unslash( $_POST['email_id'] ) ) : null;
			if ( null === $email_id || false === $email_id ) {
				\wp_send_json_error( \esc_html__( 'Invalid email ID.', 'wp-2fa' ) );
			}

			// Verify nonce.
			$nonce = isset( $_POST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) : null;
			if ( null === $nonce || false === $nonce || ! wp_verify_nonce( $nonce, 'wp-2fa-email-test-' . $email_id ) ) {
				wp_send_json_error( esc_html__( 'Nonce verification failed.', 'wp-2fa' ) );
			}

			$user_id = \get_current_user_id();
			$user    = \get_userdata( $user_id );
			$email   = $user->user_email;

			if ( 'config_test' === $email_id ) {
				$email_sent = Settings_Page::send_email(
					$email,
					\esc_html__( 'Test email from WP 2FA', 'wp-2fa' ),
					\esc_html__( 'This email was sent by the WP 2FA plugin to test the email delivery.', 'wp-2fa' )
				);
				if ( $email_sent ) {
					\wp_send_json_success( esc_html__( 'Test email was successfully sent to ', 'wp-2fa' ) . '<strong>' . esc_html( $email ) . '</strong>' );
				}

				\wp_send_json_error( \wp_sprintf( \esc_html__( 'Failed to send the test email. This is usually caused by an SMTP issue, a restricted "from" address, or your host blocking outgoing mail. Check your email settings or contact your hosting provider. %s.', 'wp-2fa' ), \wp_sprintf( '<a href="%s" target="_blank">%s</a>', 'https://melapress.com/support/kb/troubleshoot-2fa-email-delivery/?utm_source=plugin&utm_medium=wp2fa&utm_campaign=guide_troubleshoot_2fa_email_delivery&utm_content=test_email_error', \esc_html__( 'Read more about email deliverability', 'wp-2fa' ) ) ) );
			}

			$email_templates = Settings_Page_Email::get_email_notification_definitions();
			foreach ( $email_templates as $email_template ) {
				if ( $email_id === $email_template->get_email_content_id() ) {
					$subject = wp_strip_all_tags( Email_Templates::replace_email_strings( Email_Templates::get_wp2fa_email_templates( $email_id . '_email_subject' ) ) );
					$message = \wpautop( Email_Templates::replace_email_strings( Email_Templates::get_wp2fa_email_templates( $email_id . '_email_body' ), $user_id ) );

					$email_sent = Settings_Page::send_email( $email, $subject, $message );
					if ( $email_sent ) {
						\wp_send_json_success( esc_html__( 'Test email ', 'wp-2fa' ) . '<strong>' . \esc_html( $email_template->get_title() ) . '</strong>' . esc_html__( ' was successfully sent to ', 'wp-2fa' ) . '<strong>' . \esc_html( $email ) . '</strong>' );
					}

					\wp_send_json_error( \wp_sprintf( \esc_html__( 'Failed to send the test email. This is usually caused by an SMTP issue, a restricted "from" address, or your host blocking outgoing mail. Check your email settings or contact your hosting provider. %s.', 'wp-2fa' ), \wp_sprintf( '<a href="%s" target="_blank">%s</a>', 'https://melapress.com/support/kb/troubleshoot-2fa-email-delivery/?utm_source=plugin&utm_medium=wp2fa&utm_campaign=guide_troubleshoot_2fa_email_delivery&utm_content=test_email_error', \esc_html__( 'Read more about email deliverability', 'wp-2fa' ) ) ) );
				}
			}
		}


/** Function revoke_profile_key() called by wp_ajax hooks: {'wp2fa_profile_revoke_key'} **/
/** Parameters found in function revoke_profile_key(): {"post": ["user_id", "fingerprint"]} **/
function revoke_profile_key() {

			self::validate_nonce( 'wp2fa-user-passkey-revoke' );

			$user_id     = (int) \sanitize_text_field( \wp_unslash( ( $_POST['user_id'] ?? 0 ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$fingerprint = (string) \sanitize_text_field( \wp_unslash( ( $_POST['fingerprint'] ?? '' ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

			$current = \get_current_user_id();
			if ( $current !== $user_id ) {
				\wp_send_json_error( __( 'Insufficient permissions.', 'wp-2fa' ), 403 );

				\wp_die();
			}

			if ( ! $fingerprint ) {
				return new \WP_Error( 'invalid_request', __( 'Invalid request.', 'wp-2fa' ), array( 'status' => 400 ) );
			}

			$credential = Source_Repository::find_one_by_credential_id( $fingerprint );

			if ( ! $credential ) {
				return new \WP_Error( 'not_found', __( 'Not found.', 'wp-2fa' ), array( 'status' => 404 ) );
			}

			try {
				$user = \wp_get_current_user();
				Source_Repository::delete_credential_source( $fingerprint, $user );
			} catch ( \Exception $error ) {
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- Server-side security logging only.
					\error_log( sprintf( '[WP-2FA] revoke_key error: %s', $error->getMessage() ) );
				}
				return new \WP_Error( 'invalid_request', __( 'Invalid request.', 'wp-2fa' ), array( 'status' => 400 ) );
			}

			\wp_send_json_success( 2, 200 );
		}


/** Function dismiss_nag() called by wp_ajax hooks: {'dismiss_nag', 'wp2fa_dismiss_reconfigure_nag'} **/
/** No params detected :-/ **/


/** Function send_backup_codes_email() called by wp_ajax hooks: {'send_backup_codes_email'} **/
/** Parameters found in function send_backup_codes_email(): {"post": ["_wpnonce", "codes"]} **/
function send_backup_codes_email( $user_id = null, $nominated_email_address = 'nominated_email_address' ) {

			$is_ajax = \wp_doing_ajax();

			// If we have a nonce posted, check it.
			if ( $is_ajax && isset( $_POST['_wpnonce'] ) ) {
				$nonce_check = \wp_verify_nonce( \sanitize_text_field( \wp_unslash( $_POST['_wpnonce'] ) ), 'wp-2fa-send-backup-codes-email-nonce' );
				if ( ! $nonce_check ) {
					if ( $is_ajax ) {
						\wp_send_json_error( 'Invalid nonce.' );
					}
					return false;
				}
			} else {
				if ( $is_ajax ) {
					\wp_send_json_error( 'Missing nonce.' );
				}
				\wp_die();
			}

			$user = User_Helper::get_user_object();

			$enabled_email_address = '';
			if ( ! empty( $nominated_email_address ) ) {
				if ( 'nominated_email_address' === $nominated_email_address ) {
					$enabled_email_address = User_Helper::get_nominated_email_for_user( $user );
				} else {
					$enabled_email_address = get_user_meta( $user->ID, WP_2FA_PREFIX . $nominated_email_address, true );
				}
			}

			if ( isset( $_POST['codes'] ) ) {
				$codes = substr( str_replace( '\\n', '<br>', \sanitize_text_field( \wp_unslash( $_POST['codes'] ) ) ), 1, -1 );

				$posted_codes = array_filter( \explode( '<br>', $codes ) );

				$stored_codes = self::get_backup_codes_for_user( $user );

				foreach ( $posted_codes as $key => $check_code ) {
					$parts = \explode( ':', $check_code );
					if ( ! isset( $parts[1] ) ) {
						if ( $is_ajax ) {
							\wp_send_json_error( 'Invalid codes format.' );
						}
						\wp_die();
					}
					$check_code = trim( $parts[1] );
					if ( ! \wp_check_password( $check_code, $stored_codes[ $key ], $user->ID ) ) {
						if ( $is_ajax ) {
							\wp_send_json_error( 'Code verification failed.' );
						}
						\wp_die();
					}
				}
			} else {
				if ( $is_ajax ) {
					\wp_send_json_error( 'No codes provided.' );
				}
				\wp_die();
			}

			$subject = wp_strip_all_tags( Email_Templates::replace_email_strings( Email_Templates::get_wp2fa_email_templates( 'user_backup_codes_email_subject' ), $user->ID ) );
			$message = wpautop( Email_Templates::replace_email_strings( Email_Templates::get_wp2fa_email_templates( 'user_backup_codes_email_body' ), $user->ID ) );

			$final_output = str_replace( '{backup_codes}', $codes, $message );

			if ( ! empty( $enabled_email_address ) ) {
				$email_address = $enabled_email_address;
			} else {
				$email_address = $user->user_email;
			}

			$result = Settings_Page::send_email( $email_address, $subject, $final_output );

			if ( $is_ajax ) {
				if ( $result ) {
					\wp_send_json_success();
				} else {
					\wp_send_json_error( 'Failed to send email.' );
				}
			}

			return $result;
		}


/** Function ajax_switch_new_interface() called by wp_ajax hooks: {'wp2fa_switch_new_interface'} **/
/** Parameters found in function ajax_switch_new_interface(): {"post": ["nonce"]} **/
function ajax_switch_new_interface() {
			$nonce = isset( $_POST['nonce'] ) ? \sanitize_text_field( \wp_unslash( $_POST['nonce'] ) ) : '';

			if ( ! \current_user_can( 'manage_options' ) || ! \wp_verify_nonce( $nonce, 'wp2fa_new_interface_dialog' ) ) {
				\wp_send_json_error( \esc_html__( 'Nonce verification failed.', 'wp-2fa' ) );
			}

			// Update the use_new_interface setting to true.
			$settings = Settings_Utils::get_option( WP_2FA_SETTINGS_NAME, array() );

			if ( ! \is_array( $settings ) ) {
				$settings = array();
			}

			$settings['use_new_interface'] = true;

			Settings_Utils::update_option( WP_2FA_SETTINGS_NAME, $settings );

			// Clear the in-memory cached settings so the new value is picked up.
			WP2FA::update_plugin_settings( $settings, true, WP_2FA_SETTINGS_NAME );

			// Remove the dialog flag.
			Settings_Utils::delete_option( self::SHOW_DIALOG_OPTION );

			// Remove the banner flag too.
			Settings_Utils::delete_option( self::SHOW_BANNER_OPTION );

			\wp_send_json_success();
		}


/** Function ajax_dismiss_dialog() called by wp_ajax hooks: {'wp2fa_dismiss_new_interface_dialog'} **/
/** Parameters found in function ajax_dismiss_dialog(): {"post": ["nonce"]} **/
function ajax_dismiss_dialog() {
			$nonce = isset( $_POST['nonce'] ) ? \sanitize_text_field( \wp_unslash( $_POST['nonce'] ) ) : '';

			if ( ! \current_user_can( 'manage_options' ) || ! \wp_verify_nonce( $nonce, 'wp2fa_new_interface_dialog' ) ) {
				\wp_send_json_error( \esc_html__( 'Nonce verification failed.', 'wp-2fa' ) );
			}

			// Remove the dialog flag.
			Settings_Utils::delete_option( self::SHOW_DIALOG_OPTION );

			// Show the persistent banner on plugin pages instead.
			Settings_Utils::update_option( self::SHOW_BANNER_OPTION, true );

			\wp_send_json_success();
		}


/** Function ajax_activate_license() called by wp_ajax hooks: {'wp2fa_edd_activate_license'} **/
/** Parameters found in function ajax_activate_license(): {"post": ["license_key"]} **/
function ajax_activate_license() {
			check_ajax_referer( 'wp2fa_edd_license', 'nonce' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( array( 'message' => __( 'Permission denied.', 'wp-2fa' ) ) );
			}

			$license_key = isset( $_POST['license_key'] ) ? sanitize_text_field( wp_unslash( $_POST['license_key'] ) ) : '';

			if ( empty( $license_key ) ) {
				wp_send_json_error( array( 'message' => __( 'License key is required.', 'wp-2fa' ) ) );
			}

			$result = self::activate_license( $license_key );

			if ( true === $result ) {
				wp_send_json_success( array( 'message' => __( 'License activated successfully.', 'wp-2fa' ) ) );
			} else {
				wp_send_json_error( $result );
			}
		}


/** Function redirect_after_logout() called by wp_ajax hooks: {'custom_ajax_logout'} **/
/** No params detected :-/ **/


/** Function dismiss_survey_notice() called by wp_ajax hooks: {'dismiss_survey_notice'} **/
/** No params detected :-/ **/


/** Function ajax_dismiss_required_intro() called by wp_ajax hooks: {'wp2fa_dismiss_required_intro'} **/
/** No params detected :-/ **/


/** Function unlock_account() called by wp_ajax hooks: {'unlock_account'} **/
/** Parameters found in function unlock_account(): {"get": ["user_id"]} **/
function unlock_account( $user_id ) {
			if ( ! self::check_rate_limit( 'unlock_account' ) ) {
				\wp_send_json_error( \esc_html__( 'Rate limit exceeded. Please try again later.', 'wp-2fa' ) );
			}

			self::verify_request( 'wp-2fa-unlock-account-nonce' );

			$user_id = isset( $_GET['user_id'] ) ? \intval( \sanitize_text_field( \wp_unslash( $_GET['user_id'] ) ) ) : null;

			if ( isset( $user_id ) ) {

				$grace_period             = Settings_Utils::get_setting_role( User_Helper::get_user_role( $user_id ), 'grace-period' );
				$grace_period_denominator = Settings_Utils::get_setting_role( User_Helper::get_user_role( $user_id ), 'grace-period-denominator' );
				$create_a_string          = $grace_period . ' ' . $grace_period_denominator;
				// Turn that string into a time.
				$grace_expiry = strtotime( $create_a_string );

				User_Helper::remove_meta( WP_2FA_PREFIX . 'locked_account_notification', $user_id );
				User_Helper::remove_grace_period( $user_id );
				User_Helper::remove_meta( User_Helper::USER_LOCKED_STATUS, $user_id );

				User_Helper::set_user_expiry_date( (string) $grace_expiry, $user_id );

				// Recalculate the 2FA status based on current plugin settings.
				$user_obj = User_Helper::get_user( $user_id );
				if ( $user_obj instanceof \WP_User ) {
					User_Helper::set_user_status( $user_obj );
				}

				Settings_Page::send_account_unlocked_email( $user_id );

				/*
				* Fires after the user is unlocked.
				*
				* @param \WP_User $user - The user for which the method has been set.
				*
				* @since 2.6.0
				*/
				\do_action( WP_2FA_PREFIX . 'user_is_unlocked', User_Helper::get_user( $user_id ) );

				\add_action( 'admin_notices', array( __CLASS__, 'user_unlocked_notice' ) );
			}
		}


/** Function get_ajax_user_roles() called by wp_ajax hooks: {'wp_2fa_get_all_roles'} **/
/** Parameters found in function get_ajax_user_roles(): {"get": ["term"]} **/
function get_ajax_user_roles() {
			if ( \wp_doing_ajax() ) {
				self::verify_request( 'wp-2fa-settings-nonce' );

				$roles = array();

				$term = isset( $_GET['term'] ) ? \sanitize_text_field( \wp_unslash( $_GET['term'] ) ) : null;
				if ( null === $term || false === $term ) {
					\wp_send_json_error( esc_html__( 'Invalid term.', 'wp-2fa' ) );
				}

				$cache_key = 'wp2fa_roles_search_' . md5( $term );
				$roles     = \get_transient( $cache_key );

				if ( false === $roles ) {
					$roles = array();

					foreach ( WP_Helper::get_roles_wp() as $role => $human_readable ) {
						if ( stripos( $human_readable, $term ) !== false ) {
							$roles[] = array(
								'label' => \sanitize_text_field( $role ),
								'value' => \sanitize_text_field( $human_readable ),
							);
						}
					}

					\set_transient( $cache_key, $roles, self::CACHE_TTL );
				}

				\wp_send_json_success( $roles );
			}
		}


/** Function dismiss_update_notice() called by wp_ajax hooks: {'dismiss_update_notice'} **/
/** Parameters found in function dismiss_update_notice(): {"post": ["nonce"]} **/
function dismiss_update_notice() {
			// Grab POSTed data.
			$nonce = isset( $_POST['nonce'] ) ? \sanitize_text_field( \wp_unslash( $_POST['nonce'] ) ) : false;
			// Check nonce.
			if ( ! \current_user_can( 'manage_options' ) || empty( $nonce ) || ! \wp_verify_nonce( $nonce, 'dismiss_upgrade_notice' ) ) {
				\wp_send_json_error( esc_html__( 'Nonce Verification Failed.', 'wp-2fa' ) );
			}

			Settings_Utils::delete_option( Abstract_Migration::UPGRADE_NOTICE );

			\wp_send_json_success( esc_html__( 'Complete.', 'wp-2fa' ) );
		}


/** Function remove_user_2fa() called by wp_ajax hooks: {'remove_user_2fa'} **/
/** Parameters found in function remove_user_2fa(): {"post": ["user_id", "wp_2fa_nonce", "admin_reset"], "get": ["user_id", "wp_2fa_nonce", "admin_reset"]} **/
function remove_user_2fa( $user_id ) {
			if ( ! self::check_rate_limit( 'remove_user_2fa' ) ) {
				\wp_send_json_error( \esc_html__( 'Rate limit exceeded. Please try again later.', 'wp-2fa' ) );
			}

			// Allow admins or the user themselves.
			$current_user_id = (int) \get_current_user_id();
			// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
			$request_user_id = isset( $_POST['user_id'] ) ? \intval( \sanitize_text_field( \wp_unslash( $_POST['user_id'] ) ) ) : ( isset( $_GET['user_id'] ) ? \intval( \sanitize_text_field( \wp_unslash( $_GET['user_id'] ) ) ) : null );

			if ( ! \current_user_can( 'manage_options' ) ) {
				if ( null === $request_user_id || $request_user_id !== $current_user_id ) {
					\wp_send_json_error( 'Access Denied.' );
				}
			}

			// Verify nonce.
			// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
			$nonce = isset( $_POST['wp_2fa_nonce'] ) ? \sanitize_text_field( \wp_unslash( $_POST['wp_2fa_nonce'] ) ) : ( isset( $_GET['wp_2fa_nonce'] ) ? \sanitize_text_field( \wp_unslash( $_GET['wp_2fa_nonce'] ) ) : null );
			if ( null === $nonce || false === $nonce || ! \wp_verify_nonce( $nonce, 'wp-2fa-remove-user-2fa-nonce' ) ) {
				\wp_send_json_error( esc_html__( 'Nonce verification failed.', 'wp-2fa' ) );
			}

			// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
			$user_id     = isset( $_POST['user_id'] ) ? \intval( \sanitize_text_field( \wp_unslash( $_POST['user_id'] ) ) ) : ( isset( $_GET['user_id'] ) ? \intval( \sanitize_text_field( \wp_unslash( $_GET['user_id'] ) ) ) : null );
			// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
			$admin_reset = isset( $_POST['admin_reset'] ) ? (bool) \intval( \sanitize_text_field( \wp_unslash( $_POST['admin_reset'] ) ) ) : ( isset( $_GET['admin_reset'] ) ? (bool) \intval( \sanitize_text_field( \wp_unslash( $_GET['admin_reset'] ) ) ) : false );

			if ( isset( $user_id ) ) {

				User_Helper::remove_2fa_for_user( $user_id );

				if ( \wp_doing_ajax() ) {
					\wp_send_json_success();
				}

				if ( $admin_reset ) {
					\add_action( 'admin_notices', array( __CLASS__, 'admin_deleted_2fa_notice' ) );
				} else {
					\add_action( 'admin_notices', array( __CLASS__, 'user_deleted_2fa_notice' ) );
				}
			}
		}


/** Function survey_taken() called by wp_ajax hooks: {'wp_2fa_take_survey'} **/
/** Parameters found in function survey_taken(): {"post": ["nonce"]} **/
function survey_taken() {
			// Grab POSTed data.
			$nonce = isset( $_POST['nonce'] ) ? \sanitize_text_field( \wp_unslash( $_POST['nonce'] ) ) : false;

			// Check nonce.
			if ( ! \current_user_can( 'manage_options' ) || empty( $nonce ) || ! $nonce || ! \wp_verify_nonce( $nonce, 'wp_2fa_take_survey_nonce' ) ) {
				\wp_send_json_error( \esc_html__( 'Nonce Verification Failed.', 'wp-2fa' ) );
			}

			\update_site_option( WP_2FA_PREFIX . '_survey_taken', 'yes' );
			// Also set the regular dismissed flag so no edge-case shows the banner before next load.
			\update_site_option( WP_2FA_PREFIX . '_survey_banner_dismissed', 'yes' );

			\wp_send_json_success( \esc_html__( 'Complete.', 'wp-2fa' ) );
		}


/** Function regenerate_authentication_key() called by wp_ajax hooks: {'regenerate_authentication_key'} **/
/** No params detected :-/ **/


/** Function dismiss_survey_banner() called by wp_ajax hooks: {'wp_2fa_dismiss_survey_banner'} **/
/** Parameters found in function dismiss_survey_banner(): {"post": ["nonce"]} **/
function dismiss_survey_banner() {
			// Grab POSTed data.
			$nonce = isset( $_POST['nonce'] ) ? \sanitize_text_field( \wp_unslash( $_POST['nonce'] ) ) : false;

			// Check nonce.
			if ( ! \current_user_can( 'manage_options' ) || empty( $nonce ) || ! $nonce || ! \wp_verify_nonce( $nonce, 'wp_2fa_dismiss_survey_banner_nonce' ) ) {
				\wp_send_json_error( \esc_html__( 'Nonce Verification Failed.', 'wp-2fa' ) );
			}

			\update_site_option( WP_2FA_PREFIX . '_survey_banner_dismissed', 'yes' );

			\wp_send_json_success( \esc_html__( 'Complete.', 'wp-2fa' ) );
		}


/** Function register_response() called by wp_ajax hooks: {'wp2fa_profile_response'} **/
/** Parameters found in function register_response(): {"post": ["data", "passkey_name"], "server": ["HTTP_USER_AGENT"]} **/
function register_response() {
			self::validate_nonce( 'wp2fa_profile_register' );

			$data = $_POST['data'] ?? null; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing

			// The AJAX JS sends data as a JSON string via URLSearchParams; decode it.
			if ( is_string( $data ) ) {
				$data = json_decode( wp_unslash( $data ), true );
			}

			if ( ! $data || ! \is_array( $data ) || empty( $data ) ) {
				\wp_send_json_error( __( 'Invalid request.', 'wp-2fa' ), 400 );
				return;
			}

			try {
				$user = \wp_get_current_user();

				// Get expected challenge from user meta.
				$challenge = \get_user_meta( $user->ID, WP_2FA_PREFIX . 'passkey_challenge', true );

				$params  = array(
					'rawId'    => \sanitize_text_field( \wp_unslash( $data['rawId'] ?? '' ) ),
					'response' => \map_deep( \wp_unslash( $data['response'] ?? array() ), 'sanitize_text_field' ),
				);
				$user_id = $user->ID;

				$web_authn = new Web_Authn(
					Web_Authn::get_relying_party_id(),
					Web_Authn::get_relying_party_id()
				);

				$credential_id      = Web_Authn::get_raw_credential_id( $params['rawId'] );
				$client_data_json   = Web_Authn::base64url_decode( $params['response']['clientDataJSON'] );
				$attestation_object = Web_Authn::base64url_decode( $params['response']['attestationObject'] );
				$challenge          = Web_Authn::base64url_decode( $challenge );

				$attestation = $web_authn->process_create(
					$client_data_json,
					new Byte_Buffer( $attestation_object ),
					$challenge,
					false, // User verification not required for current flow.
				);

				$data = array(
					'user_id'       => $user_id,
					'credential_id' => $credential_id,
					'public_key'    => $attestation->credential_public_key,
					'aaguid'        => Web_Authn::convert_aaguid_to_hex( $attestation->aaguid ),
					'last_used_at'  => null,
				);

				\delete_user_meta( $user->ID, WP_2FA_PREFIX . 'passkey_challenge' );

				// Get platform from user agent and sanitize it before use/storage.
				$user_agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? \sanitize_text_field( \wp_unslash( (string) $_SERVER['HTTP_USER_AGENT'] ) ) : 'unknown'; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash

				switch ( true ) {
					case preg_match( '/android/i', $user_agent ):
						$platform = 'Android';
						break;
					case preg_match( '/iphone/i', $user_agent ):
						$platform = 'iPhone / iOS';
						break;
					case preg_match( '/linux/i', $user_agent ):
						$platform = 'Linux';
						break;
					case preg_match( '/macintosh|mac os x/i', $user_agent ):
						$platform = 'Mac OS';
						break;
					case preg_match( '/windows|win32/i', $user_agent ):
						$platform = 'Windows';
						break;
					default:
						$platform = 'unknown';
						break;
				}

				if ( isset( $_POST['passkey_name'] ) && ! empty( $_POST['passkey_name'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce already validated earlier in method.
					$name = \sanitize_text_field( \wp_unslash( $_POST['passkey_name'] ) );
				} else {
					$name = "Generated on $platform";
				}

				$extra_data = array(
					'name'          => $name,
					'created'       => time(),
					'last_used'     => false,
					'enabled'       => true,
					'ip_address'    => Authentication_Server::get_ip_address(),
					'platform'      => $platform,
					'user_agent'    => $user_agent,
					'aaguid'        => $data['aaguid'],
					'public_key'    => $data['public_key'],
					'credential_id' => $credential_id,
					'transports'    => ( isset( $params['response']['transports'] ) ) ? \wp_json_encode( $params['response']['transports'] ) : \wp_json_encode( array() ),
				);

				// Finally store the credential source to database.
				Source_Repository::save_credential_source( $user, $extra_data );

			} catch ( \Exception $error ) {
				// Log detailed error server-side only when WP_DEBUG is enabled.
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- Server-side security logging only.
					\error_log( sprintf( '[WP-2FA] register_response error: %s', $error->getMessage() ) );
				}
				return new \WP_Error( 'public_key_validation_failed', __( 'Verification failed.', 'wp-2fa' ), array( 'status' => 400 ) );
			}

			Passkeys::set_user_method( $user );

			\wp_send_json_success( 'verified' );
		}


/** Function signin_request() called by wp_ajax hooks: {'nopriv_wp2fa_signin_request', 'wp2fa_signin_request'} **/
/** Parameters found in function signin_request(): {"post": ["user"]} **/
function signin_request() {

			// Apply light rate limiting for unauthenticated login attempts.
			self::maybe_rate_limit( 'signin_request' );

			$user = null;

			if ( ! empty( $_POST['user'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Public sign-in endpoint cannot require nonce
				$data['user'] = \sanitize_user( \wp_unslash( $_POST['user'] ) );
				$user_login   = \get_user_by( 'login', $data['user'] );
				$user         = $user_login ? $user_login : \get_user_by( 'email', $data['user'] );
			}

			if ( ! $user ) {
				// Generic message to reduce user enumeration.
				return \wp_send_json_error( __( 'Passkey authentication not available.', 'wp-2fa' ), 400 );
			}

			// if ( User_Helper::is_excluded( $user->ID ) ) {
			// Generic message to reduce user enumeration.
			// return \wp_send_json_error( __( 'Passkey authentication not available.', 'wp-2fa' ), 400 );
			// }

			$public_key_credentials = Source_Repository::find_all_for_user( $user );
			if ( ! empty( $public_key_credentials ) ) {
				$allow_credentials = array();

				foreach ( $public_key_credentials as $public_key_credential ) {
					$allow_credentials[] = array(
						'type' => 'public-key',
						'id'   => $public_key_credential['credential_id'],
					);
				}
			} else {
				// Generic message to reduce enumeration of passkey availability.
				return \wp_send_json_error( __( 'Passkey authentication not available.', 'wp-2fa' ), 400 );
			}

			$request_id = \wp_generate_uuid4();

			// Use base64url encoding for WebAuthn challenge consistency.
			$challenge = self::base64url_encode( random_bytes( 32 ) );

			$options = array(
				'challenge'        => $challenge,
				'rpId'             => Web_Authn::get_relying_party_id(),
				'allowCredentials' => $allow_credentials ?? array(),
				'userVerification' => 'required',
				'timeout'          => 5 * 60 * 1000,
				'uid'              => $user ? (string) $user->ID : '',
			);

			// Store the challenge in transient for 60 seconds.
			// For some hosting transient set to persistent object cache like Redis/Memcache. By default it stored in options table.
			\set_transient( Source_Repository::PASSKEYS_META . $request_id, $challenge, 60 );

			$response = array(
				'options'    => $options,
				'request_id' => $request_id,
			);

			return \wp_send_json_success( $response, 200 );
		}


/** Function passkey_rename() called by wp_ajax hooks: {'wp2fa_user_passkey_rename'} **/
/** Parameters found in function passkey_rename(): {"post": ["id", "value"]} **/
function passkey_rename() {
			self::validate_nonce( 'wp2fa-user-passkey-rename', 'nonce' );

			$id    = (string) \sanitize_text_field( \wp_unslash( ( $_POST['id'] ?? '' ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$value = (string) \sanitize_text_field( \wp_unslash( ( $_POST['value'] ?? '' ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

			if ( ! $id ) {
				return new \WP_Error( 'invalid_request', __( 'Invalid request.', 'wp-2fa' ), array( 'status' => 400 ) );
			}

			if ( ! $value ) {
				return new \WP_Error( 'invalid_request', __( 'Invalid request.', 'wp-2fa' ), array( 'status' => 400 ) );
			}

			try {
				$meta_key = Source_Repository::PASSKEYS_META . $id;

				$user = \wp_get_current_user();

				$user_data = \json_decode( (string) \get_user_meta( $user->ID, $meta_key, true ), true, 512, JSON_THROW_ON_ERROR );

				// Update the meta value.
				$user_data['extra']['name'] = $value;
				$public_key_json            = \wp_json_encode( $user_data, JSON_UNESCAPED_SLASHES );
				\update_user_meta( $user->ID, $meta_key, $public_key_json );
			} catch ( \Exception $error ) {
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- Server-side security logging only.
					\error_log( sprintf( '[WP-2FA] passkey_rename error: %s', $error->getMessage() ) );
				}
				return new \WP_Error( 'invalid_request', __( 'Invalid request.', 'wp-2fa' ), array( 'status' => 400 ) );
			}

			\wp_send_json_success( 2, 200 );
		}


/** Function dismiss_notice_mail_domain() called by wp_ajax hooks: {'wp2fa_dismiss_notice_mail_domain'} **/
/** Parameters found in function dismiss_notice_mail_domain(): {"post": ["nonce"]} **/
function dismiss_notice_mail_domain() {
			// Verify nonce.
			if ( isset( $_POST['nonce'] ) && \wp_verify_nonce( \sanitize_text_field( \wp_unslash( $_POST['nonce'] ) ), 'wp2fa_dismiss_notice_mail_domain' ) ) {
				Settings_Utils::update_option( 'dismiss_notice_mail_domain', true );
				die();
			}

			die( 'Nonce verification failed!' );
		}


/** Function signin_response() called by wp_ajax hooks: {'wp2fa_signin_response', 'nopriv_wp2fa_signin_response'} **/
/** Parameters found in function signin_response(): {"post": ["user", "data", "request_id", "redirect_to"]} **/
function signin_response() {

			// Apply light rate limiting for unauthenticated login attempts.
			self::maybe_rate_limit( 'signin_response' );

			$user = null;

			if ( ! empty( $_POST['user'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Public sign-in endpoint cannot require nonce
				$data['user'] = \sanitize_user( \wp_unslash( $_POST['user'] ) );
				$user_login   = \get_user_by( 'login', $data['user'] );
				$user         = $user_login ? $user_login : \get_user_by( 'email', $data['user'] );
				if ( ! $user ) {
					// Generic failure to reduce user enumeration.
					return \wp_send_json_error( __( 'Authentication failed.', 'wp-2fa' ), 400 );
				}
			}

			$data = $_POST['data'] ?? null; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing

			if ( ! $data || ! \is_array( $data ) || empty( $data ) ) {
				return new \WP_Error( 'invalid_request', __( 'Invalid request.', 'wp-2fa' ), array( 'status' => 400 ) );
			}

			$request_id = \sanitize_text_field( \wp_unslash( ( $_POST['request_id'] ?? 0 ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

			if ( ! $request_id ) {
				return new \WP_Error( 'invalid_request', __( 'Invalid request.', 'wp-2fa' ), array( 'status' => 400 ) );
			}

			// Get challenge from cache.
			$challenge = \get_transient( Source_Repository::PASSKEYS_META . $request_id );

			// If $challenge not exists, return WP_Error.
			if ( ! $challenge ) {
				return new \WP_Error( 'invalid_challenge', __( 'Authentication failed.', 'wp-2fa' ), array( 'status' => 400 ) );
			}

			$asse_rep = \map_deep( \wp_unslash( $data ?? array() ), 'sanitize_text_field' );

			if ( empty( $asse_rep ) ) {
				return new \WP_Error( 'invalid_challenge', __( 'Invalid request.', 'wp-2fa' ), array( 'status' => 400 ) );
			}

			$uid = $user ? (string) $user->ID : '';

			// Delete challenge from cache.
			\delete_transient( Source_Repository::PASSKEYS_META . $request_id );

			$webauthn = new Web_Authn(
				Web_Authn::get_relying_party_id(),
				Web_Authn::get_relying_party_id()
			);

			$credential_id = Web_Authn::get_raw_credential_id( $asse_rep['rawId'] );

			if ( ! class_exists( 'ParagonIE_Sodium_Core_Base64_UrlSafe', false ) ) {
				require_once ABSPATH . WPINC . '/sodium_compat/src/Core/Base64/UrlSafe.php';
				require_once ABSPATH . WPINC . '/sodium_compat/src/Core/Util.php';
			}

			$meta_key = Source_Repository::PASSKEYS_META . \ParagonIE_Sodium_Core_Base64_UrlSafe::encodeUnpadded( $credential_id );

			try {
				$user_data = \json_decode( (string) \get_user_meta( $uid, $meta_key, true ), true, 512, JSON_THROW_ON_ERROR );
			} catch ( \JsonException $exception ) {
				$user_data = null;
			}

			if ( null === $user_data || empty( $user_data ) ) {
				return \wp_send_json_error( __( 'Authentication failed.', 'wp-2fa' ), 400 );
			}

			try {
				$data = $webauthn->process_get(
					Web_Authn::base64url_decode( $asse_rep['response']['clientDataJSON'] ),
					Web_Authn::base64url_decode( $asse_rep['response']['authenticatorData'] ),
					Web_Authn::base64url_decode( $asse_rep['response']['signature'] ),
					$user_data['extra']['public_key'],
					Web_Authn::base64url_decode( $challenge ),
					null,
					true
				);

				if ( Passkeys::is_enabled( User_Helper::get_user_role( (int) $uid ) ) ) {
					if ( ! $user_data['extra']['enabled'] ) {
						return \wp_send_json_error( __( 'Authentication failed.', 'wp-2fa' ), 400 );
					}

					// If user found and authorized, set the login cookie.
					\wp_set_auth_cookie( $uid, true, is_ssl() );

					// Mark 2FA as pending using helper so other components can enforce a challenge.
					if ( ! class_exists( Pending_2FA_Helper::class, false ) ) {
						require_once __DIR__ . '/class-pending-2fa-helper.php';
					}
					Pending_2FA_Helper::mark_pending( (int) $uid, array( 'source' => 'passkey' ) );

					// Update the meta value.
					$user_data['extra']['last_used'] = time();
					$public_key_json                 = addcslashes( \wp_json_encode( $user_data, JSON_UNESCAPED_SLASHES ), '\\' );
					\update_user_meta( $uid, $meta_key, $public_key_json );
				} else {
					return \wp_send_json_error(
						__( 'User is not eligible for this method.', 'wp-2fa' )
					);
				}
			} catch ( \Exception $error ) {
				// Log the detailed error server-side only when WP_DEBUG is enabled.
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- Server-side security logging only.
					\error_log( sprintf( '[WP-2FA] signin_response error: %s', $error->getMessage() ) );
				}
				return new \WP_Error( 'public_key_validation_failed', __( 'Authentication failed.', 'wp-2fa' ), array( 'status' => 400 ) );
			}

			$redirect_to = isset( $_POST['redirect_to'] ) && is_string( $_POST['redirect_to'] ) ? $_POST['redirect_to'] : '';

			/**
			 * Filters the login redirect URL.
			 *
			 * @since 3.0.0
			 *
			 * @param string           $redirect_to           The redirect destination URL.
			 * @param string           $requested_redirect_to The requested redirect destination URL passed as a parameter.
			 * @param WP_User|WP_Error $user                  WP_User object if login was successful, WP_Error object otherwise.
			 */
			$redirect_to = \wp_validate_redirect( apply_filters( 'login_redirect', $redirect_to, '', $user ), \admin_url() );

			if ( ( empty( $redirect_to ) || 'wp-admin/' === $redirect_to || \admin_url() === $redirect_to ) ) {
				// If the user doesn't belong to a blog, send them to user admin. If the user can't edit posts, send them to their profile.
				if ( is_multisite() && ! get_active_blog_for_user( $user->ID ) && ! is_super_admin( $user->ID ) ) {
					$redirect_to = user_admin_url();
				} elseif ( is_multisite() && ! $user->has_cap( 'read' ) ) {
					$redirect_to = get_dashboard_url( $user->ID );
				} elseif ( ! $user->has_cap( 'edit_posts' ) ) {
					$redirect_to = $user->has_cap( 'read' ) ? \admin_url( 'profile.php' ) : \home_url();
				}
			}

			// Return only the path component to avoid host mismatches in proxied environments.
			$redirect_path = ! empty( $redirect_to ) ? \wp_parse_url( $redirect_to, PHP_URL_PATH ) : '/wp-admin/';
			$redirect_query = \wp_parse_url( $redirect_to, PHP_URL_QUERY );
			$redirect_to = $redirect_path . ( $redirect_query ? '?' . $redirect_query : '' );

			\wp_send_json_success(
				array(
					'status'      => 'verified',
					'message'     => __( 'Successfully signin with Passkey.', 'wp-2fa' ),
					'redirect_to' => $redirect_to,
				)
			);
		}


/** Function register_request() called by wp_ajax hooks: {'wp2fa_profile_register'} **/
/** Parameters found in function register_request(): {"post": ["is_usb"]} **/
function register_request() {

			self::validate_nonce( 'wp2fa_profile_register' );

			$user = \wp_get_current_user();

			if ( ! $user || 0 === $user->ID ) {
				// Generic message to reduce user enumeration.
				return \wp_send_json_error( __( 'Passkey authentication not available.', 'wp-2fa' ), 400 );
			}

			// if ( User_Helper::is_excluded( $user->ID ) ) {
			// Generic message to reduce user enumeration.
			// return \wp_send_json_error( __( 'Passkey authentication not available.', 'wp-2fa' ), 400 );
			// }

			// Strict boolean parsing for is_usb.
			$is_usb_raw = isset( $_POST['is_usb'] ) ? \sanitize_text_field( \wp_unslash( (string) $_POST['is_usb'] ) ) : 'false'; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$is_usb     = filter_var( $is_usb_raw, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE );
			$is_usb     = ( null === $is_usb ) ? false : (bool) $is_usb;

			try {
				$public_key_credential_creation_options = Authentication_Server::create_attestation_request( $user, \null, $is_usb );
			} catch ( \Exception $error ) {
				// Log detailed error server-side only when WP_DEBUG is enabled.
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- Server-side security logging only.
					\error_log( sprintf( '[WP-2FA] register_request error: %s', $error->getMessage() ) );
				}
				return new \WP_Error( 'invalid_request', __( 'Invalid request.', 'wp-2fa' ), array( 'status' => 400 ) );
			}

			\wp_send_json_success( $public_key_credential_creation_options, 200 );
		}


/** Function get_all_users() called by wp_ajax hooks: {'wp_2fa_get_all_users'} **/
/** Parameters found in function get_all_users(): {"get": ["term"]} **/
function get_all_users() {
			self::verify_request( 'wp-2fa-settings-nonce' );

			$term = isset( $_GET['term'] ) ? \sanitize_text_field( \wp_unslash( $_GET['term'] ) ) : null;

			if ( null === $term || false === $term ) {
				\wp_send_json_error( \esc_html__( 'Invalid term.', 'wp-2fa' ) );
			}

			$cache_key = 'wp2fa_users_search_' . md5( $term );
			$users     = \get_transient( $cache_key );

			if ( false === $users ) {
				$users_args = array(
					'fields'         => array( 'ID', 'user_login' ),
					'search'         => '*' . $term . '*',
					'search_columns' => array( 'user_login' ),
					'number'         => 20,
					'orderby'        => 'user_login',
					'order'          => 'ASC',
				);
				if ( WP_Helper::is_multisite() ) {
					$users_args['blog_id'] = 0;
				}
				$users_data = \get_users( $users_args );

				// Create final array which we will fill in below.
				$users = array();

				foreach ( $users_data as $user ) {
					$users[] = array(
						'value' => $user->user_login,
						'label' => $user->user_login,
					);
				}

				\set_transient( $cache_key, $users, self::CACHE_TTL );
			}

			\wp_send_json_success( $users );
		}


/** Function ajax_deactivate_license() called by wp_ajax hooks: {'wp2fa_edd_deactivate_license'} **/
/** No params detected :-/ **/


/** Function ajax_save() called by wp_ajax hooks: {'wp2fa_save_settings_new', 'wp2fa_save_policies_new'} **/
/** Parameters found in function ajax_save(): {"post": ["nonce", "email_from_setting"]} **/
function ajax_save() {
			// 1. Nonce check — must happen before any data access.
			$nonce = isset( $_POST['nonce'] ) ? \sanitize_text_field( \wp_unslash( $_POST['nonce'] ) ) : '';
			if ( ! \wp_verify_nonce( $nonce, 'wp2fa_save_settings_new_nonce' ) ) {
				\wp_send_json_error(
					array( 'message' => \esc_html__( 'Security check failed. Please refresh the page and try again.', 'wp-2fa' ) ),
					403
				);
			}

			// 2. Capability check.
			if ( ! \current_user_can( 'manage_options' ) ) {
				\wp_send_json_error(
					array( 'message' => \esc_html__( 'You do not have permission to perform this action.', 'wp-2fa' ) ),
					403
				);
			}

			if ( isset( $_POST[ WP_2FA_SETTINGS_NAME ] ) && is_array( $_POST[ WP_2FA_SETTINGS_NAME ] ) ) {
				$existing_general = Settings_Utils::get_option( WP_2FA_SETTINGS_NAME, array() );
				$options          = Settings_Page_General::validate_and_sanitize( $_POST[ WP_2FA_SETTINGS_NAME ] ); // call the generic validate_and_sanitize to trigger its filter, in case any settings tabs use it.

				// Merge validated output on top of existing settings, but only
				// for keys actually submitted by the form — see the policy
				// handler for a detailed explanation.
				if ( \is_array( $options ) && \is_array( $existing_general ) ) {
					$submitted_keys = \array_keys( $_POST[ WP_2FA_SETTINGS_NAME ] );
					$merged         = $existing_general;
					foreach ( $options as $key => $value ) {
						if ( \in_array( $key, $submitted_keys, true ) || ! \array_key_exists( $key, $existing_general ) ) {
							$merged[ $key ] = $value;
						}
					}
					$options = $merged;
				}

				WP2FA::update_plugin_settings( $options, false, WP_2FA_SETTINGS_NAME );
			}

			if ( isset( $_POST['email_from_setting'] ) ) {
				$options = Settings_Page_Email::validate_and_sanitize_new( \wp_unslash( $_POST ) );

				// Merge with existing email settings to preserve keys not present
				// in POST (e.g. SMS templates for non-enterprise plans).
				$existing_email = Settings_Utils::get_option( WP_2FA_EMAIL_SETTINGS_NAME );
				if ( \is_array( $existing_email ) && \is_array( $options ) ) {
					$options = \array_merge( $existing_email, $options );
				}

				Settings_Utils::update_option( WP_2FA_EMAIL_SETTINGS_NAME, $options );
			}

			// 4. Allow other option groups rendered on this page to hook in and
			// save their own data. Each hook receives the full (unslashed) POST
			// payload so it can extract and sanitise its own sub-array.
			\do_action( WP_2FA_PREFIX . 'settings_new_ajax_save', \wp_unslash( $_POST ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

			// 5. Collect any validation errors added during this request and return
			// them so the JS layer can surface them to the user.
			global $wp_settings_errors;
			if ( ! empty( $wp_settings_errors ) ) {
				$errors = array();
				foreach ( $wp_settings_errors as $error ) {
					$errors[] = array(
						'code'    => isset( $error['code'] ) ? \sanitize_key( $error['code'] ) : '',
						'message' => isset( $error['message'] ) ? \wp_strip_all_tags( $error['message'] ) : '',
						'type'    => isset( $error['type'] ) ? \sanitize_key( $error['type'] ) : 'error',
					);
				}
				\wp_send_json_success(
					array(
						'message' => esc_html__( 'Settings saved with warnings.', 'wp-2fa' ),
						'errors'  => $errors,
					)
				);
			}

			\wp_send_json_success(
				array( 'message' => \esc_html__( 'Settings saved.', 'wp-2fa' ) )
			);
		}


/** Function run_ajax_generate_json() called by wp_ajax hooks: {'wp2fa_run_ajax_generate_json'} **/
/** No params detected :-/ **/


/** Function set_salt_key() called by wp_ajax hooks: {'set_salt_key'} **/
/** Parameters found in function set_salt_key(): {"request": ["_wpnonce"]} **/
function set_salt_key() {
			if ( \wp_doing_ajax() ) {
				if ( isset( $_REQUEST['_wpnonce'] ) ) {
					$nonce_check = \wp_verify_nonce( \sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'wp-2fa-set-salt-nonce' );
					if ( ! $nonce_check ) {
						\wp_send_json_error( new \WP_Error( 500, esc_html__( 'Nonce checking failed', 'wp-2fa' ) ), 400 );
					} elseif ( \current_user_can( 'manage_options' ) ) {
						if ( ! File_Writer::can_write_to_file( File_Writer::get_wp_config_file_path() ) ) {
							\wp_send_json_error(
								new \WP_Error(
									500,
									\esc_html__( 'Unable to write to wp-config.php', 'wp-2fa' )
								),
								400
							);
						} else {
							$secret_key = Settings_Utils::get_option( 'secret_key' );
							if ( ! empty( $secret_key ) ) {
								File_Writer::save_secret_key( $secret_key );
								Settings_Utils::delete_option( 'secret_key' );
								\wp_send_json_success(
									\esc_html__(
										'wp-config.php successfully update, global setting deleted',
										'wp-2fa'
									)
								);
							} else {
								\wp_send_json_error(
									new \WP_Error(
										500,
										\esc_html__( 'Unable to find global secret key', 'wp-2fa' )
									),
									400
								);
							}
						}
					}
				}
			}
		}


/** Function get_all_network_sites() called by wp_ajax hooks: {'wp_2fa_get_all_network_sites'} **/
/** Parameters found in function get_all_network_sites(): {"get": ["term"]} **/
function get_all_network_sites() {
			self::verify_request( 'wp-2fa-settings-nonce' );

			$term = isset( $_GET['term'] ) ? \sanitize_text_field( \wp_unslash( $_GET['term'] ) ) : null;

			if ( null === $term || false === $term ) {
				\wp_send_json_error( \esc_html__( 'Invalid term.', 'wp-2fa' ) );
			}

			$cache_key   = 'wp2fa_sites_search_' . md5( $term );
			$sites_found = \get_transient( $cache_key );

			if ( false === $sites_found ) {
				$sites_found = array();

				foreach ( WP_Helper::get_multi_sites() as $site ) {
					if ( false !== stripos( $site->blogname, $term ) ) {
						$sites_found[] = array(
							'label' => $site->blog_id,
							'value' => $site->blogname,
						);
					}
				}

				\set_transient( $cache_key, $sites_found, self::CACHE_TTL );
			}

			\wp_send_json_success( $sites_found );
		}


