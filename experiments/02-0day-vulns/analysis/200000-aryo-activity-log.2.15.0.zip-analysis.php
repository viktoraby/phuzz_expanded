<?php
/***
*
*Found actions: 2
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 2
*Overview: {'ajax_aal_promotion_campaign': {'aal_promotion_campaign'}, 'ajax_aal_promotion_dismiss': {'aal_promotion_dismiss'}}
*
***/

/** Function ajax_aal_promotion_campaign() called by wp_ajax hooks: {'aal_promotion_campaign'} **/
/** Parameters found in function ajax_aal_promotion_campaign(): {"post": ["nonce", "promotion_id"]} **/
function ajax_aal_promotion_campaign() {
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'aal_promotion' ) ) {
			wp_send_json_error();
		}

		if ( empty( $_POST['promotion_id'] ) ) {
			wp_send_json_error();
		}

		if ( 'emails' === $_POST['promotion_id'] ) {
			$campaign_data = [
				'source' => 'sm-aal-install',
				'campaign' => 'sm-plg',
				'medium' => 'wp-dash',
			];

			set_transient( 'elementor_site_mailer_campaign', $campaign_data, 30 * DAY_IN_SECONDS );
		}

		if ( 'media' === $_POST['promotion_id'] ) {
			$campaign_data = [
				'source' => 'io-aal-install',
				'campaign' => 'io-plg',
				'medium' => 'wp-dash',
			];

			set_transient( 'elementor_image_optimization_campaign', $campaign_data, 30 * DAY_IN_SECONDS );
		}

		wp_send_json_success();
	}


/** Function ajax_aal_promotion_dismiss() called by wp_ajax hooks: {'aal_promotion_dismiss'} **/
/** Parameters found in function ajax_aal_promotion_dismiss(): {"post": ["nonce", "promotion_id"]} **/
function ajax_aal_promotion_dismiss() {
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'aal_promotion' ) ) {
			wp_send_json_error();
		}

		if ( empty( $_POST['promotion_id'] ) ) {
			wp_send_json_error();
		}

		$promotion_id = sanitize_key( $_POST['promotion_id'] );

		update_user_meta( get_current_user_id(), "_aal_promotion_{$promotion_id}_notice_viewed", 'true'  );

		wp_send_json_success();
	}


