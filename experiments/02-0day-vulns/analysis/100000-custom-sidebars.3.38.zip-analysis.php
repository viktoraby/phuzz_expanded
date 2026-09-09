<?php
/***
*
*Found actions: 6
*Found functions:6
*Extracted functions:6
*Total parameter names extracted: 5
*Overview: {'update_custom_sidebars_metabox_roles': {'custom_sidebars_metabox_roles'}, 'update_custom_sidebars_allow_author': {'custom_sidebars_allow_author'}, 'dismiss_retirement_notice': {'custom_sidebars_retirement_notice_dismiss'}, 'dismiss': {'custom_sidebars_checkup_notification_dismiss'}, 'ajax_handler': {'cs-ajax'}, 'update_custom_sidebars_metabox_custom_taxonomies': {'custom_sidebars_metabox_custom_taxonomies'}}
*
***/

/** Function update_custom_sidebars_metabox_roles() called by wp_ajax hooks: {'custom_sidebars_metabox_roles'} **/
/** No params detected :-/ **/


/** Function update_custom_sidebars_allow_author() called by wp_ajax hooks: {'custom_sidebars_allow_author'} **/
/** Parameters found in function update_custom_sidebars_allow_author(): {"request": ["_wpnonce", "value"]} **/
function update_custom_sidebars_allow_author() {
		/**
		 * check required data
		 */
		if ( ! isset( $_REQUEST['_wpnonce'] ) ) {
			wp_send_json_error();
		}
		/**
		 * check nonce value
		 */
		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], $this->allow_author_name ) ) {
			wp_send_json_error();
		}
		$value = isset( $_REQUEST['value'] )? 'true' === $_REQUEST['value'] : false;
		$status = add_option( $this->allow_author_name, $value, '', 'no' );
		if ( ! $status ) {
			update_option( $this->allow_author_name, $value );
		}
		wp_send_json_success();
	}


/** Function dismiss_retirement_notice() called by wp_ajax hooks: {'custom_sidebars_retirement_notice_dismiss'} **/
/** Parameters found in function dismiss_retirement_notice(): {"post": ["nonce"]} **/
function dismiss_retirement_notice() {
		$user_id = filter_input( INPUT_POST, 'user_id', FILTER_VALIDATE_INT );

		if (
			! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'wpmudev_sc_dismissed_notice' ) ||
			$user_id !== get_current_user_id()
		) {

			wp_send_json(
				array(
					'success' => false,
					'message' => __( 'Unauthorized', 'custom-sidebars' ),
				)
			);
		}

		update_user_meta( $user_id, 'wpmudev_sc_dismissed_notice', true );

		$return = array(
			'success' => true,
            /* translators: %1$s is replaced with the user ID */
			'message' => sprintf( esc_html__( 'Notice dismissed for user %d', 'custom-sidebars' ), $user_id ),
		);

		wp_send_json( $return );
	}


/** Function dismiss() called by wp_ajax hooks: {'custom_sidebars_checkup_notification_dismiss'} **/
/** Parameters found in function dismiss(): {"get": ["_wpnonce", "user_id"]} **/
function dismiss() {
		/**
		 * Check: is nonce send?
		 */
		if ( ! isset( $_GET['_wpnonce'] ) ) {
			die;
		}
		/**
		 * Check: is user id send?
		 */
		if ( ! isset( $_GET['user_id'] ) ) {
			die;
		}
		/**
		 * Check: nonce
		 */
		$nonce_name = $this->nonce_name . sanitize_key($_GET['user_id']);
		if ( ! wp_verify_nonce( $_GET['_wpnonce'], $nonce_name ) ) {
			die;
		}
		/**
		 * save result
		 */
		$result = add_user_meta( sanitize_key($_GET['user_id']), $this->dismiss_name, true, true );
		if ( false == $result ) {
			update_user_meta( sanitize_key($_GET['user_id']), $this->dismiss_name, true );
		}
		die;
	}


/** Function ajax_handler() called by wp_ajax hooks: {'cs-ajax'} **/
/** Parameters found in function ajax_handler(): {"post": ["do"], "get": ["do"]} **/
function ajax_handler() {
		// Permission check.
		if ( ! current_user_can( self::$cap_required ) ) {
			return;
		}

		// Try to disable debug output for ajax handlers of this plugin.
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			defined( 'WP_DEBUG_DISPLAY' ) || define( 'WP_DEBUG_DISPLAY', false );
			defined( 'WP_DEBUG_LOG' ) || define( 'WP_DEBUG_LOG', true );
		}
		// Catch any unexpected output via output buffering.
		ob_start();

		$action     = isset( $_POST['do'] ) ? sanitize_key($_POST['do']) : null;
		$get_action = isset( $_GET['do'] ) ? sanitize_key($_GET['do']) : null;

		/**
		 * Notify all extensions about the ajax call.
		 *
		 * @since  2.0
		 * @param  string $action The specified ajax action.
		 */
		do_action( 'cs_ajax_request', $action );

		/**
		 * Notify all extensions about the GET ajax call.
		 *
		 * @since  2.0.9.7
		 * @param  string $action The specified ajax action.
		 */
		do_action( 'cs_ajax_request_get', $get_action );
	}


/** Function update_custom_sidebars_metabox_custom_taxonomies() called by wp_ajax hooks: {'custom_sidebars_metabox_custom_taxonomies'} **/
/** No params detected :-/ **/


