<?php
/***
*
*Found actions: 52
*Found functions:50
*Extracted functions:48
*Total parameter names extracted: 3
*Overview: {'load_ad_parameters_metabox': {'load_ad_parameters_metabox'}, 'ajax_account_selected': {'advads_gadsense_mapi_select_account'}, 'hide_notice': {'advads-hide-notice'}, 'save_wizard_state': {'advads-save-hide-wizard-state'}, 'ajax_get_ad_code': {'advads_mapi_get_adCode'}, 'subscribe': {'advads-subscribe-notice'}, 'search_terms': {'advads-terms-search'}, 'ad_health_notice_unignore': {'advads-ad-health-notice-unignore'}, 'get_usable_versions': {'advads_get_usable_versions'}, 'ajax_save_manual_code': {'advads-mapi-save-manual-code'}, 'load_visitor_condition': {'load_visitor_conditions_metabox'}, 'ajax_revoke_tokken': {'advads-mapi-revoke-token'}, 'Advanced_Ads_Overview_Widgets_Callbacks': {'advads_adsense_report_refresh'}, 'ajax_save_reconstructed_code': {'advads-mapi-reconstructed-code'}, 'load_display_condition': {'load_display_conditions_metabox'}, 'adblock_rebuild_assets': {'advads-adblock-rebuild-assets'}, 'placement_update_item': {'advads_placement_update_item'}, 'inject_placement': {'advads-ad-injection-content'}, 'adsense_enable_pla': {'advads-adsense-enable-pla'}, 'ad_select': {'nopriv_advads_ad_select', 'advads_ad_select'}, 'install_plugin': {'advads_install_alternate_version'}, 'search_posts': {'search_posts'}, 'get_block_hints': {'advads-get-block-hints'}, 'dismiss': {'advanced_ads_pef'}, 'post_search': {'advads-post-search'}, 'notice_dismissible': {'advads_framework_notice_dismissible'}, 'pubguru_connect': {'pubguru_connect'}, 'ajax_get_account_alerts': {'advads-mapi-get-alerts'}, 'ad_health_notice_push': {'nopriv_advads-ad-health-notice-push', 'advads-ad-health-notice-push'}, 'ad_health_notice_push_adminui': {'advads-ad-health-notice-push-adminui'}, 'update_frontend_element': {'advads-update-frontend-element'}, 'pubguru_disconnect': {'pubguru_disconnect'}, 'search_authors': {'advads-authors-search'}, 'update_oneclick_preview': {'update_oneclick_preview'}, 'get_allowed_items': {'advads_placements_allowed_ads'}, 'multiple_subscribe': {'advads-multiple-subscribe'}, 'backup_ads_txt': {'pubguru_backup_ads_txt'}, 'module_status_changed': {'pubguru_module_change'}, 'ad_health_notice_solved': {'advads-ad-health-notice-solved'}, 'subscribe_to_newsletter': {'advads_newsletter'}, 'dismiss_welcome': {'advads_dismiss_welcome'}, 'ad_health_notice_display': {'advads-ad-health-notice-display'}, 'ajax_dismiss_alert': {'advads-mapi-dismiss-alert'}, 'close_notice': {'advads-close-notice'}, 'ajax_confirm_code': {'advads_gadsense_mapi_confirm_code'}, 'get_content_for_shortcode_creator': {'advads_content_for_shortcode_creator'}, 'activate_add_on': {'advads_activate_addon'}, 'ad_health_notice_hide': {'advads-ad-health-notice-hide'}, 'send_feedback': {'advads_send_feedback'}, 'ajax_get_account_details': {'advads_gadsense_mapi_get_details'}}
*
***/

/** Function load_ad_parameters_metabox() called by wp_ajax hooks: {'load_ad_parameters_metabox'} **/
/** No params detected :-/ **/


/** Function ajax_account_selected() called by wp_ajax hooks: {'advads_gadsense_mapi_select_account'} **/
/** No params detected :-/ **/


/** Function hide_notice() called by wp_ajax hooks: {'advads-hide-notice'} **/
/** No params detected :-/ **/


/** Function save_wizard_state() called by wp_ajax hooks: {'advads-save-hide-wizard-state'} **/
/** No params detected :-/ **/


/** Function ajax_get_ad_code() called by wp_ajax hooks: {'advads_mapi_get_adCode'} **/
/** No params detected :-/ **/


/** Function subscribe() called by wp_ajax hooks: {'advads-subscribe-notice'} **/
/** No params detected :-/ **/


/** Function search_terms() called by wp_ajax hooks: {'advads-terms-search'} **/
/** No params detected :-/ **/


/** Function ad_health_notice_unignore() called by wp_ajax hooks: {'advads-ad-health-notice-unignore'} **/
/** No params detected :-/ **/


/** Function get_usable_versions() called by wp_ajax hooks: {'advads_get_usable_versions'} **/
/** No params detected :-/ **/


/** Function ajax_save_manual_code() called by wp_ajax hooks: {'advads-mapi-save-manual-code'} **/
/** Parameters found in function ajax_save_manual_code(): {"post": ["parsed_code", "raw_code"]} **/
function ajax_save_manual_code() {
		if ( ! Conditional::user_can( 'advanced_ads_edit_ads' ) ) {
			die();
		}

		if ( ! wp_verify_nonce( Params::request( 'nonce', '' ), 'advads-mapi' ) ) {
			die();
		}

		$publisher_id = sanitize_text_field( wp_unslash( isset( $_POST['parsed_code']['pubId'] ) ? $_POST['parsed_code']['pubId'] : '' ) );
		if ( ! $this->check_valid_publisher( $publisher_id ) ) {
			wp_send_json_error(
				[
					'message' => __( 'This ad code is from a different AdSense Account', 'advanced-ads' ),
				],
				400
			);
		}

		if ( empty( $_POST['parsed_code']['slotId'] ) || empty( $_POST['raw_code'] ) ) {
			die();
		}

		static $options;
		if ( is_null( $options ) ) {
			$options = self::get_option();
		}

		$slot_id = 'ca-' . $publisher_id . ':' . sanitize_text_field( wp_unslash( $_POST['parsed_code']['slotId'] ) );

		// phpcs:disable WordPress.Security
		$options['ad_codes'][ $slot_id ] = urldecode( Params::post( 'raw_code', '' ) );
		// phpcs:enable

		if ( array_key_exists( $slot_id, $options['unsupported_units'] ) ) {
			unset( $options['unsupported_units'][ $slot_id ] );
		}

		wp_send_json_success( [ 'updated' => update_option( self::OPTION_KEY, $options ) ] );
	}


/** Function load_visitor_condition() called by wp_ajax hooks: {'load_visitor_conditions_metabox'} **/
/** No params detected :-/ **/


/** Function ajax_revoke_tokken() called by wp_ajax hooks: {'advads-mapi-revoke-token'} **/
/** No params detected :-/ **/


/** Function Advanced_Ads_Overview_Widgets_Callbacks() called by wp_ajax hooks: {'advads_adsense_report_refresh'} **/
/** No function found :-/ **/


/** Function ajax_save_reconstructed_code() called by wp_ajax hooks: {'advads-mapi-reconstructed-code'} **/
/** No params detected :-/ **/


/** Function load_display_condition() called by wp_ajax hooks: {'load_display_conditions_metabox'} **/
/** No params detected :-/ **/


/** Function adblock_rebuild_assets() called by wp_ajax hooks: {'advads-adblock-rebuild-assets'} **/
/** No params detected :-/ **/


/** Function placement_update_item() called by wp_ajax hooks: {'advads_placement_update_item'} **/
/** No params detected :-/ **/


/** Function inject_placement() called by wp_ajax hooks: {'advads-ad-injection-content'} **/
/** No params detected :-/ **/


/** Function adsense_enable_pla() called by wp_ajax hooks: {'advads-adsense-enable-pla'} **/
/** No params detected :-/ **/


/** Function ad_select() called by wp_ajax hooks: {'nopriv_advads_ad_select', 'advads_ad_select'} **/
/** No params detected :-/ **/


/** Function install_plugin() called by wp_ajax hooks: {'advads_install_alternate_version'} **/
/** No params detected :-/ **/


/** Function search_posts() called by wp_ajax hooks: {'search_posts'} **/
/** No params detected :-/ **/


/** Function get_block_hints() called by wp_ajax hooks: {'advads-get-block-hints'} **/
/** No params detected :-/ **/


/** Function dismiss() called by wp_ajax hooks: {'advanced_ads_pef'} **/
/** No params detected :-/ **/


/** Function post_search() called by wp_ajax hooks: {'advads-post-search'} **/
/** No params detected :-/ **/


/** Function notice_dismissible() called by wp_ajax hooks: {'advads_framework_notice_dismissible'} **/
/** No params detected :-/ **/


/** Function pubguru_connect() called by wp_ajax hooks: {'pubguru_connect'} **/
/** No params detected :-/ **/


/** Function ajax_get_account_alerts() called by wp_ajax hooks: {'advads-mapi-get-alerts'} **/
/** Parameters found in function ajax_get_account_alerts(): {"post": ["inlineAttempt"]} **/
function ajax_get_account_alerts() {
		if ( ! Conditional::user_can( 'advanced_ads_manage_options' ) ) {
			die;
		}

		$nonce = Params::post( 'nonce', '' );
		if ( false !== wp_verify_nonce( $nonce, 'mapi-alerts' ) ) {
			$account = stripslashes( Params::post( 'account', '' ) );
			$options = self::get_option();

			// the account exists.
			if ( isset( $options['accounts'][ $account ] ) && self::has_token( $account ) ) {
				$access_token = self::get_access_token( $account );
				$url          = str_replace( 'PUBID', $account, self::ALERTS_URL );

				if ( isset( $_POST['inlineAttempt'] ) ) {
					if ( ! is_array( $options['accounts'][ $account ]['alerts'] ) ) {
						$options['accounts'][ $account ]['alerts'] = [];
					}
					$options['accounts'][ $account ]['alerts']['inlineAttempt'] = time();
					update_option( self::OPTION_KEY, $options );
				}

				// the token is valid.
				if ( ! isset( $access_token['msg'] ) ) {
					$headers  = [
						'Authorization' => 'Bearer ' . $access_token,
					];
					$response = wp_remote_get( $url, [ 'headers' => $headers ] );

					$this->log( 'Get AdSense alerts for ' . $account );

					// the HTTP response is not an error.
					if ( ! is_wp_error( $response ) ) {
						$alerts = json_decode( $response['body'], true );

						// the response body is valid.
						if ( is_array( $alerts ) && isset( $alerts['alerts'] ) ) {
							$items = [];
							foreach ( $alerts['alerts'] as $item ) {
								// Do not store alerts of type "INFO".
								if ( strcasecmp( $item['severity'], 'INFO' ) !== 0 ) {
									$items[ wp_generate_password( 6, false ) ] = $item;
								}
							}

							// filter alerts that are not relevant to the user.
							$items = self::filter_account_alerts( $items );

							$alerts_array = [
								'items' => $items,
								'lastCheck' => time(),
							];

							$options['accounts'][ $account ]['alerts'] = $alerts_array;
							update_option( self::OPTION_KEY, $options );
							$results = [
								'status' => true,
								'alerts' => $items,
								'length' => count( $items ),
							];
							header( 'Content-Type:application/json' );
							echo wp_json_encode( $results );
						} else {
							$results = [
								'status' => false,
								'msg'    => esc_html__( 'Invalid response body while retrieving account alerts', 'advanced-ads' ),
							];
							header( 'Content-Type:application/json' );
							echo wp_json_encode( $results );
						}
					} else {
						$results = [
							'status' => false,
							'msg'    => esc_html__( 'error while retrieving account alerts', 'advanced-ads' ),
							'raw'    => $response->get_error_message(),
						];
						header( 'Content-Type:application/json' );
						echo wp_json_encode( $results );
					}
				} else {
					// return the original error info.
					return $access_token;
				}
			} else {
				header( 'Content-Type:application/json' );
				echo wp_json_encode( [ 'status' => false ] );
			}
		}
		die;
	}


/** Function ad_health_notice_push() called by wp_ajax hooks: {'nopriv_advads-ad-health-notice-push', 'advads-ad-health-notice-push'} **/
/** Parameters found in function ad_health_notice_push(): {"request": ["key"]} **/
function ad_health_notice_push(): void {
		check_ajax_referer( 'advanced-ads-ad-health-ajax-nonce', 'nonce' );

		if ( ! Conditional::user_can( 'advanced_ads_edit_ads' ) ) {
			return;
		}

		$key  = ! empty( $_REQUEST['key'] ) ? esc_attr( Params::request( 'key' ) ) : false;
		$attr = Params::request( 'attr', [], FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		// Update or new entry?
		if ( isset( $attr['mode'] ) && 'update' === $attr['mode'] ) {
			Advanced_Ads_Ad_Health_Notices::get_instance()->update( $key, $attr );
		} else {
			Advanced_Ads_Ad_Health_Notices::get_instance()->add( $key, $attr );
		}

		die();
	}


/** Function ad_health_notice_push_adminui() called by wp_ajax hooks: {'advads-ad-health-notice-push-adminui'} **/
/** No params detected :-/ **/


/** Function update_frontend_element() called by wp_ajax hooks: {'advads-update-frontend-element'} **/
/** No params detected :-/ **/


/** Function pubguru_disconnect() called by wp_ajax hooks: {'pubguru_disconnect'} **/
/** No params detected :-/ **/


/** Function search_authors() called by wp_ajax hooks: {'advads-authors-search'} **/
/** No params detected :-/ **/


/** Function update_oneclick_preview() called by wp_ajax hooks: {'update_oneclick_preview'} **/
/** No params detected :-/ **/


/** Function get_allowed_items() called by wp_ajax hooks: {'advads_placements_allowed_ads'} **/
/** No params detected :-/ **/


/** Function multiple_subscribe() called by wp_ajax hooks: {'advads-multiple-subscribe'} **/
/** No params detected :-/ **/


/** Function backup_ads_txt() called by wp_ajax hooks: {'pubguru_backup_ads_txt'} **/
/** No params detected :-/ **/


/** Function module_status_changed() called by wp_ajax hooks: {'pubguru_module_change'} **/
/** No params detected :-/ **/


/** Function ad_health_notice_solved() called by wp_ajax hooks: {'advads-ad-health-notice-solved'} **/
/** No function found :-/ **/


/** Function subscribe_to_newsletter() called by wp_ajax hooks: {'advads_newsletter'} **/
/** No params detected :-/ **/


/** Function dismiss_welcome() called by wp_ajax hooks: {'advads_dismiss_welcome'} **/
/** No params detected :-/ **/


/** Function ad_health_notice_display() called by wp_ajax hooks: {'advads-ad-health-notice-display'} **/
/** No params detected :-/ **/


/** Function ajax_dismiss_alert() called by wp_ajax hooks: {'advads-mapi-dismiss-alert'} **/
/** No params detected :-/ **/


/** Function close_notice() called by wp_ajax hooks: {'advads-close-notice'} **/
/** No params detected :-/ **/


/** Function ajax_confirm_code() called by wp_ajax hooks: {'advads_gadsense_mapi_confirm_code'} **/
/** No params detected :-/ **/


/** Function get_content_for_shortcode_creator() called by wp_ajax hooks: {'advads_content_for_shortcode_creator'} **/
/** No params detected :-/ **/


/** Function activate_add_on() called by wp_ajax hooks: {'advads_activate_addon'} **/
/** No params detected :-/ **/


/** Function ad_health_notice_hide() called by wp_ajax hooks: {'advads-ad-health-notice-hide'} **/
/** No params detected :-/ **/


/** Function send_feedback() called by wp_ajax hooks: {'advads_send_feedback'} **/
/** No params detected :-/ **/


/** Function ajax_get_account_details() called by wp_ajax hooks: {'advads_gadsense_mapi_get_details'} **/
/** No params detected :-/ **/


