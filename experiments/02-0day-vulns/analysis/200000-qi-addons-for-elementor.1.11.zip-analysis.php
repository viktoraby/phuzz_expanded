<?php
/***
*
*Found actions: 5
*Found functions:5
*Extracted functions:5
*Total parameter names extracted: 3
*Overview: {'handle_review_notice': {'qi_addons_for_elementor_review_notice'}, 'save_settings': {'qi_addons_for_elementor_action_settings_save_options'}, 'handle_notice': {'qi_addons_for_elementor_notice'}, 'save_widgets': {'qi_addons_for_elementor_action_framework_save_options'}, 'handle_deactivation': {'qi_addons_for_elementor_deactivation'}}
*
***/

/** Function handle_review_notice() called by wp_ajax hooks: {'qi_addons_for_elementor_review_notice'} **/
/** No params detected :-/ **/


/** Function save_settings() called by wp_ajax hooks: {'qi_addons_for_elementor_action_settings_save_options'} **/
/** Parameters found in function save_settings(): {"request": ["action", "qi_addons_for_elementor_swiper_new"]} **/
function save_settings() {

			if ( current_user_can( 'edit_theme_options' ) ) {

				$_REQUEST = stripslashes_deep( $_REQUEST );
				unset( $_REQUEST['action'] );
				check_ajax_referer( 'qi_addons_for_elementor_settings_ajax_save_nonce', 'qi_addons_for_elementor_settings_ajax_save_nonce' );

				$new_swiper = $_REQUEST['qi_addons_for_elementor_swiper_new'] == 'yes' ? 'yes' : 'no';
				$results    = update_option( 'qi_addons_for_elementor_swiper_new', $new_swiper );

				do_action( 'qi_addons_for_elementor_action_saved_settings' );

				if ( $results ) {
					esc_html_e( 'Saved', 'qi-addons-for-elementor' );
				}

				die();
			}
		}


/** Function handle_notice() called by wp_ajax hooks: {'qi_addons_for_elementor_notice'} **/
/** No params detected :-/ **/


/** Function save_widgets() called by wp_ajax hooks: {'qi_addons_for_elementor_action_framework_save_options'} **/
/** Parameters found in function save_widgets(): {"request": ["action"]} **/
function save_widgets() {

			if ( current_user_can( 'edit_theme_options' ) ) {

				$_REQUEST = stripslashes_deep( $_REQUEST );
				unset( $_REQUEST['action'] );
				check_ajax_referer( 'qi_addons_for_elementor_widgets_ajax_save_nonce', 'qi_addons_for_elementor_widgets_ajax_save_nonce' );

				$disabled         = array();
				$enabled          = array();
				$shortcodes       = $this->get_shortcodes();
				$promo_shortcodes = qi_addons_for_elementor_promotion_shortcodes_list();

				$shortcodes = array_diff_key( $shortcodes, $promo_shortcodes );

				foreach ( $shortcodes as $shortcode_key => $shortcode ) {
					if ( ! isset( $_REQUEST[ $shortcode_key ] ) ) {
						$disabled[ $shortcode_key ] = $shortcode['base'];
					} else {
						$enabled[ $shortcode_key ] = $shortcode['base'];
					}
				}

				$results = update_option( QI_ADDONS_FOR_ELEMENTOR_DISABLED_WIDGETS, $disabled );
				$this->generate_widget_stylesheet( $enabled );

				do_action( 'qi_addons_for_elementor_action_saved_widgets', $enabled );

				if ( $results ) {
					esc_html_e( 'Saved', 'qi-addons-for-elementor' );
				}

				die();
			}
		}


/** Function handle_deactivation() called by wp_ajax hooks: {'qi_addons_for_elementor_deactivation'} **/
/** Parameters found in function handle_deactivation(): {"post": ["reason", "additionalInfo"]} **/
function handle_deactivation() {
		check_ajax_referer( 'qi-addons-for-elementor-deactivation-nonce', 'nonce' );

		$data = array(
			'plugin'                 => $this->plugin_slug,
			'site_lang'              => get_bloginfo( 'language' ),
			'reason'                 => isset( $_POST['reason'] ) ? sanitize_text_field( wp_unslash( $_POST['reason'] ) ) : '',
			'reason_additional_info' => isset( $_POST['additionalInfo'] ) ? sanitize_text_field( wp_unslash( $_POST['additionalInfo'] ) ) : '',
			'date'                   => gmdate( 'Y-m-d H:i:s' ),
		);

		$request_handler_url = 'https://api.qodeinteractive.com/plugin-deactivation-feedback.php';

		$response = wp_remote_post(
			$request_handler_url,
			array(
				'body' => $data,
			)
		);

		$response_body = json_decode( wp_remote_retrieve_body( $response ) );

		if ( $response_body->success ) {
			qi_addons_for_elementor_framework_get_ajax_status( 'success', esc_html__( 'Thank you for the feedback!', 'qi-addons-for-elementor' ) );
		} else {
			qi_addons_for_elementor_framework_get_ajax_status( 'fail', esc_html__( 'Something went wrong with sending feedback.', 'qi-addons-for-elementor' ) );
		}
	}


