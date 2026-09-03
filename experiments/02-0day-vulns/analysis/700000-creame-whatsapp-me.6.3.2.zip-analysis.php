<?php
/***
*
*Found actions: 2
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 1
*Overview: {'save': {'joinchat_onboard'}, 'ajax_notice_dismiss': {'joinchat_notice_dismiss'}}
*
***/

/** Function save() called by wp_ajax hooks: {'joinchat_onboard'} **/
/** Parameters found in function save(): {"post": ["data"]} **/
function save() {

		check_ajax_referer( 'joinchat_onboard', 'nonce', true );

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$data = isset( $_POST['data'] ) ? Joinchat_Util::clean_input( (array) $_POST['data'] ) : array();

		// Save settings.
		$settings = array_merge( jc_common()->settings, $data );
		$updated  = update_option( JOINCHAT_SLUG, $settings, true );

		// Newsletter subscription.
		$newsletter = true;

		if ( ! empty( $data['newsletter'] ) ) {
			$body = array(
				'email' => $data['newsletter'],
				'site'  => get_site_url(),
				'lang'  => get_bloginfo( 'language' ),
			);

			$response = wp_remote_post(
				'https://eu5-api.connectif.cloud:443/integration-type/system/scrippet-notification/03362af2-f194-457a-a5c7-5b7d94f29cb6?eventId=64903bd547fb425e8608f3b3',
				array(
					'headers' => array( 'Content-Type' => 'application/json' ),
					'body'    => wp_json_encode( $body ),
					'timeout' => 10,
				)
			);

			$newsletter = ! is_wp_error( $response );
		}

		if ( ! $updated || ! $newsletter ) {
			wp_send_json_error();
		} else {
			wp_send_json_success();
		}

	}


/** Function ajax_notice_dismiss() called by wp_ajax hooks: {'joinchat_notice_dismiss'} **/
/** No params detected :-/ **/


