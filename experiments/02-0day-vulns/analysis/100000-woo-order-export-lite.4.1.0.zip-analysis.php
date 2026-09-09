<?php
/***
*
*Found actions: 1
*Found functions:1
*Extracted functions:1
*Total parameter names extracted: 2
*Overview: {'ajax_gate': {'order_exporter'}}
*
***/

/** Function ajax_gate() called by wp_ajax hooks: {'order_exporter'} **/
/** Parameters found in function ajax_gate(): {"request": ["method", "tab"], "post": ["json", "settings", "orders", "products", "coupons"]} **/
function ajax_gate() {

		if( !current_user_can('view_woocommerce_reports')  AND !current_user_can(self::$cap_export_orders) ){
			die( esc_html__( 'You can not do it', 'woo-order-export-lite' ) );
		}

		if ( ! isset( $_REQUEST['method'] ) ) {
			die( esc_html__( 'Empty method', 'woo-order-export-lite' ) );
		}

		$method = 'ajax_' . sanitize_text_field(wp_unslash($_REQUEST['method']));

		$tab = isset($_REQUEST['tab']) ? sanitize_text_field(wp_unslash($_REQUEST['tab'])) : '';
		$active_tab = $tab && $this->is_tab_exists( $tab) ? $tab : $this->settings['default_tab'];


		do_action( 'woe_order_export_admin_ajax_gate_before');

		if ( ! isset( $this->tabs[ $tab ] ) ) {
			$ajax_handler = apply_filters( 'woe_global_ajax_handler', new WC_Order_Export_Ajax() );
			if ( ! method_exists( $ajax_handler, $method ) ) {
				/* translators: error message for bad ajax method */
				die( sprintf( esc_html__( 'Unknown AJAX method %s', 'woo-order-export-lite' ), esc_html($method)) );
			}

			$ajax_handler->$method();
			die();
		}

		if ( ! method_exists( $this->tabs[ $active_tab ], $method ) ) {
			/* translators: error message if current tab doesn't support ajax method */
			die( sprintf( esc_html__( 'Unknown tab method %s', 'woo-order-export-lite' ), esc_html($method)) );
		}

		if (! check_admin_referer( 'woe_nonce', 'woe_nonce' ) ) {
			die( esc_html__( 'Wrong nonce', 'woo-order-export-lite' ) );
		}

		$_POST = stripslashes_deep( $_POST );

		// parse json to arrays?
		if ( ! empty( $_POST['json'] ) ) {
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			$json = json_decode( $_POST['json'], true );
			if ( is_array( $json ) ) {
				// add $_POST['settings'],$_POST['orders'],$_POST['products'],$_POST['coupons']
				$_POST = $_POST + $json;
				unset( $_POST['json'] );
			}
		}

		$this->tabs[ $tab ]->$method();

		die();
	}


