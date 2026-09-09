<?php
/***
*
*Found actions: 4
*Found functions:4
*Extracted functions:4
*Total parameter names extracted: 4
*Overview: {'bws_submit_request_feature_action': {'bws_submit_request_feature_action'}, 'bws_submit_uninstall_reason_action': {'bws_submit_uninstall_reason_action'}, 'gglcptch_test_keys_verification': {'gglcptch_test_keys_verification'}, 'gglcptch_test_keys': {'gglcptch-test-keys'}}
*
***/

/** Function bws_submit_request_feature_action() called by wp_ajax hooks: {'bws_submit_request_feature_action'} **/
/** Parameters found in function bws_submit_request_feature_action(): {"request": ["bws_ajax_nonce", "plugin", "info", "is_anonymous"]} **/
function bws_submit_request_feature_action() {
		global $bstwbsftwppdtplgns_options, $wp_version, $bstwbsftwppdtplgns_active_plugins, $current_user;

		if ( isset( $_REQUEST['bws_ajax_nonce'] ) ) {

			check_ajax_referer( 'bws_ajax_nonce', sanitize_text_field( wp_unslash( $_REQUEST['bws_ajax_nonce'] ) ) );

			$basename = isset( $_REQUEST['plugin'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['plugin'] ) ) : '';
			$info     = isset( $_REQUEST['info'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['info'] ) ) : '';

			if ( empty( $info ) || empty( $basename ) ) {
				exit;
			}

			$info         = substr( $info, 0, 255 );
			$is_anonymous = isset( $_REQUEST['is_anonymous'] ) && 1 === intval( $_REQUEST['is_anonymous'] );

			$options = array(
				'product' => $basename,
				'info'    => $info,
			);

			if ( ! $is_anonymous ) {
				if ( ! isset( $bstwbsftwppdtplgns_options ) ) {
					$bstwbsftwppdtplgns_options = ( is_multisite() ) ? get_site_option( 'bstwbsftwppdtplgns_options' ) : get_option( 'bstwbsftwppdtplgns_options' );
				}

				if ( ! empty( $bstwbsftwppdtplgns_options['track_usage']['usage_id'] ) ) {
					$options['usage_id'] = $bstwbsftwppdtplgns_options['track_usage']['usage_id'];
				} else {
					$options['usage_id']   = false;
					$options['url']        = get_bloginfo( 'url' );
					$options['wp_version'] = $wp_version;
					$options['is_active']  = false;
					$options['version']    = $bstwbsftwppdtplgns_active_plugins[ $basename ]['Version'];
				}

				$options['email'] = $current_user->data->user_email;
			}

			/* send data */
			$raw_response = wp_remote_post(
				'https://bestwebsoft.com/wp-content/plugins/products-statistics/request-feature/',
				array(
					'method'  => 'POST',
					'body'    => $options,
					'timeout' => 15,
				)
			);

			if ( ! is_wp_error( $raw_response ) && 200 === intval( wp_remote_retrieve_response_code( $raw_response ) ) ) {
				if ( ! $is_anonymous ) {
					$response = maybe_unserialize( wp_remote_retrieve_body( $raw_response ) );

					if ( is_array( $response ) && ! empty( $response['usage_id'] ) && $response['usage_id'] !== $options['usage_id'] ) {
						$bstwbsftwppdtplgns_options['track_usage']['usage_id'] = $response['usage_id'];

						if ( is_multisite() ) {
							update_site_option( 'bstwbsftwppdtplgns_options', $bstwbsftwppdtplgns_options );
						} else {
							update_option( 'bstwbsftwppdtplgns_options', $bstwbsftwppdtplgns_options );
						}
					}
				}

				echo 'done';
			} else {
				echo wp_kses_data( $response->get_error_code() ) . ': ' . wp_kses_data( $response->get_error_message() );
			}
		}
		exit;
	}


/** Function bws_submit_uninstall_reason_action() called by wp_ajax hooks: {'bws_submit_uninstall_reason_action'} **/
/** Parameters found in function bws_submit_uninstall_reason_action(): {"request": ["bws_ajax_nonce", "reason_id", "plugin", "reason_info", "is_anonymous"]} **/
function bws_submit_uninstall_reason_action() {
		global $bstwbsftwppdtplgns_options, $wp_version, $bstwbsftwppdtplgns_active_plugins, $current_user;

		if ( isset( $_REQUEST['bws_ajax_nonce'] ) ) {

			check_ajax_referer( 'bws_ajax_nonce', sanitize_text_field( wp_unslash( $_REQUEST['bws_ajax_nonce'] ) ) );

			$reason_id = isset( $_REQUEST['reason_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['reason_id'] ) ) : '';
			$basename  = isset( $_REQUEST['plugin'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['plugin'] ) ) : '';

			if ( empty( $reason_id ) || empty( $basename ) ) {
				exit;
			}

			$reason_info = isset( $_REQUEST['reason_info'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['reason_info'] ) ) : '';
			if ( ! empty( $reason_info ) ) {
				$reason_info = substr( $reason_info, 0, 255 );
			}
			$is_anonymous = isset( $_REQUEST['is_anonymous'] ) && 1 === intval( $_REQUEST['is_anonymous'] );

			$options = array(
				'product'     => $basename,
				'reason_id'   => $reason_id,
				'reason_info' => $reason_info,
			);

			if ( ! $is_anonymous ) {
				if ( ! isset( $bstwbsftwppdtplgns_options ) ) {
					$bstwbsftwppdtplgns_options = ( is_multisite() ) ? get_site_option( 'bstwbsftwppdtplgns_options' ) : get_option( 'bstwbsftwppdtplgns_options' );
				}

				if ( ! empty( $bstwbsftwppdtplgns_options['track_usage']['usage_id'] ) ) {
					$options['usage_id'] = $bstwbsftwppdtplgns_options['track_usage']['usage_id'];
				} else {
					$options['usage_id']   = false;
					$options['url']        = get_bloginfo( 'url' );
					$options['wp_version'] = $wp_version;
					$options['is_active']  = false;
					$options['version']    = $bstwbsftwppdtplgns_active_plugins[ $basename ]['Version'];
				}

				$options['email'] = $current_user->data->user_email;
			}

			/* send data */
			$raw_response = wp_remote_post(
				'https://bestwebsoft.com/wp-content/plugins/products-statistics/deactivation-feedback/',
				array(
					'method'  => 'POST',
					'body'    => $options,
					'timeout' => 15,
				)
			);

			if ( ! is_wp_error( $raw_response ) && 200 === intval( wp_remote_retrieve_response_code( $raw_response ) ) ) {
				if ( ! $is_anonymous ) {
					$response = maybe_unserialize( wp_remote_retrieve_body( $raw_response ) );

					if ( is_array( $response ) && ! empty( $response['usage_id'] ) && $response['usage_id'] !== $options['usage_id'] ) {
						$bstwbsftwppdtplgns_options['track_usage']['usage_id'] = $response['usage_id'];

						if ( is_multisite() ) {
							update_site_option( 'bstwbsftwppdtplgns_options', $bstwbsftwppdtplgns_options );
						} else {
							update_option( 'bstwbsftwppdtplgns_options', $bstwbsftwppdtplgns_options );
						}
					}
				}

				echo 'done';
			} else {
				echo wp_kses_data( $response->get_error_code() ) . ': ' . wp_kses_data( $response->get_error_message() );
			}
		}
		exit;
	}


/** Function gglcptch_test_keys_verification() called by wp_ajax hooks: {'gglcptch_test_keys_verification'} **/
/** Parameters found in function gglcptch_test_keys_verification(): {"request": ["_wpnonce", "action"]} **/
function gglcptch_test_keys_verification() {
		if ( isset( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) ) ) {
			$result = gglcptch_check( 'gglcptch_test', true );

			if ( ! $result['response'] ) {
				if ( isset( $result['reason'] ) ) {
					foreach ( (array) $result['reason'] as $error ) {
						?>
						<div class="error gglcptch-test-results"><p>
							<?php gglcptch_get_message( $error, true ); ?>
						</p></div>
						<?php
					}
				}
			} else {
				?>
				<div class="updated gglcptch-test-results"><p><?php esc_html_e( 'The verification is successfully completed.', 'google-captcha' ); ?></p></div>
				<?php
				$gglcptch_options                  = get_option( 'gglcptch_options' );
				$gglcptch_options['keys_verified'] = true;
				unset( $gglcptch_options['need_keys_verified_check'] );
				update_option( 'gglcptch_options', $gglcptch_options );
			}
		}
		die();
	}


/** Function gglcptch_test_keys() called by wp_ajax hooks: {'gglcptch-test-keys'} **/
/** Parameters found in function gglcptch_test_keys(): {"request": ["_wpnonce", "action"]} **/
function gglcptch_test_keys() {
		global $gglcptch_options;
		if ( isset( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) ) ) {
			header( 'Content-Type: text/html' );
			register_gglcptch_settings();
			?>
			<p>
				<?php
				if ( 'invisible' === $gglcptch_options['recaptcha_version'] || 'v3' === $gglcptch_options['recaptcha_version'] ) {
					esc_html_e( 'Please submit "Test verification"', 'google-captcha' );
				} else {
					esc_html_e( 'Please complete the captcha and submit "Test verification"', 'google-captcha' );
				}
				?>
			</p>
			<?php echo gglcptch_display(); ?>
			<p>
				<input type="hidden" name="gglcptch_test_keys_verification-nonce" value="<?php echo esc_attr( wp_create_nonce( 'gglcptch_test_keys_verification' ) ); ?>" />
				<button id="gglcptch_test_keys_verification" name="action" class="button-primary cptch_loading" value="gglcptch_test_keys_verification" disabled="disabled"><?php esc_html_e( 'Test verification', 'google-captcha' ); ?></button>
			</p>
			<?php
		}
		die();
	}


