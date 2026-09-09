<?php
/***
*
*Found actions: 6
*Found functions:5
*Extracted functions:5
*Total parameter names extracted: 4
*Overview: {'save_swatches': {'cfvsw_save_product_swatches_data'}, 'cfvsw_update_settings': {'cfvsw_update_settings'}, 'cfvsw_ajax_add_to_cart': {'cfvsw_ajax_add_to_cart', 'nopriv_cfvsw_ajax_add_to_cart'}, 'update_swatches': {'cfvsw_update_product_swatches_data'}, 'reset_swatches': {'cfvsw_reset_product_swatches_data'}}
*
***/

/** Function save_swatches() called by wp_ajax hooks: {'cfvsw_save_product_swatches_data'} **/
/** Parameters found in function save_swatches(): {"post": ["product_id", "attr"]} **/
function save_swatches() {
		check_ajax_referer( 'cfvsw_swatches_save_reset', 'security' );
		// Check admin or not here.
		$product_id = ! empty( $_POST['product_id'] ) ? intval( sanitize_text_field( $_POST['product_id'] ) ) : false;
		if ( ! $product_id || empty( $_POST['attr'] ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid product id.', 'variation-swatches-woo' ) ] );
		}


		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid user.', 'variation-swatches-woo' ) ] );
		}

		// $_POST['attr'] is sanitized in later stage using sanitize_recursively function so sanitization is not required.
		$check_blank_array = $this->helper->remove_blank_array( $_POST['attr'] ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		// Cleanup previous meta.
		$this->clean_up_previous_swatches_data( $product_id );
		if ( empty( $check_blank_array ) ) {
			wp_send_json_error( [ 'message' => __( 'Settings saved.', 'variation-swatches-woo' ) ] );
		}
		$sanitize_array = $this->helper->sanitize_recursively( 'sanitize_text_field', $check_blank_array );

		foreach ( $sanitize_array as $key => $value ) {
			update_post_meta( $product_id, $key, $value );
		}
		wp_send_json_success(
			[ 'message' => __( 'Settings saved.', 'variation-swatches-woo' ) ]
		);
	}


/** Function cfvsw_update_settings() called by wp_ajax hooks: {'cfvsw_update_settings'} **/
/** No params detected :-/ **/


/** Function cfvsw_ajax_add_to_cart() called by wp_ajax hooks: {'cfvsw_ajax_add_to_cart', 'nopriv_cfvsw_ajax_add_to_cart'} **/
/** Parameters found in function cfvsw_ajax_add_to_cart(): {"post": ["product_id", "quantity", "variation_id", "variation"]} **/
function cfvsw_ajax_add_to_cart() {
		check_ajax_referer( 'cfvsw_ajax_add_to_cart', 'security' );

		if ( empty( $_POST['product_id'] ) ) {
			return;
		}

		$product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
		$product_title     = get_the_title( $product_id );
		$quantity          = ! empty( $_POST['quantity'] ) ? wc_stock_amount( absint( $_POST['quantity'] ) ) : 1;
		$product_status    = get_post_status( $product_id );
		$variation_id      = ! empty( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
		$variation         = ! empty( $_POST['variation'] ) ? array_map( 'sanitize_text_field', $_POST['variation'] ) : array();
		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variation );
		$cart_page_url     = wc_get_cart_url();

		if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation ) && 'publish' === $product_status ) {

			do_action( 'woocommerce_ajax_added_to_cart', $product_id );

			if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
				wc_add_to_cart_message( array( $product_id => $quantity ), true );
			} else {
				$added_to_cart_notice = sprintf(
					/* translators: %s: Product title */
					esc_html__( '"%1$s" has been added to your cart. %2$s', 'variation-swatches-woo' ),
					esc_html( $product_title ),
					'<a href="' . esc_url( $cart_page_url ) . '">' . esc_html__( 'View Cart', 'variation-swatches-woo' ) . '</a>'
				);

				wc_add_notice( $added_to_cart_notice );
			}

			WC_AJAX::get_refreshed_fragments();
		} else {

			// If there was an error adding to the cart, redirect to the product page to show any errors.
			$data = array(
				'error'       => true,
				'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
			);

			wp_send_json( $data );
		}
	}


/** Function update_swatches() called by wp_ajax hooks: {'cfvsw_update_product_swatches_data'} **/
/** Parameters found in function update_swatches(): {"post": ["product_id"]} **/
function update_swatches() {
		check_ajax_referer( 'cfvsw_swatches_save_reset', 'security' );
		$product_id = ! empty( $_POST['product_id'] ) ? intval( sanitize_text_field( $_POST['product_id'] ) ) : false;
		if ( ! $product_id ) {
			wp_send_json_error( [ 'message' => __( 'Invalid product id.', 'variation-swatches-woo' ) ] );
		}

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid user.', 'variation-swatches-woo' ) ] );
		}

		$product_object = wc_get_product( $product_id );
		if ( ! $product_object->is_type( 'variable' ) ) {
			wp_send_json_error( [ 'message' => __( 'Product type is not variable.', 'variation-swatches-woo' ) ] );
		}
		$this->return_swatches_template( $product_id, $product_object );
	}


/** Function reset_swatches() called by wp_ajax hooks: {'cfvsw_reset_product_swatches_data'} **/
/** Parameters found in function reset_swatches(): {"post": ["product_id"]} **/
function reset_swatches() {
		check_ajax_referer( 'cfvsw_swatches_save_reset', 'security' );
		$product_id = ! empty( $_POST['product_id'] ) ? intval( sanitize_text_field( $_POST['product_id'] ) ) : false;
		if ( ! $product_id ) {
			wp_send_json_error( [ 'message' => __( 'Invalid product id.', 'variation-swatches-woo' ) ] );
		}


		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid user.', 'variation-swatches-woo' ) ] );
		}

		$product_object = wc_get_product( $product_id );
		if ( ! $product_object->is_type( 'variable' ) ) {
			wp_send_json_error( [ 'message' => __( 'Product type is not variable.', 'variation-swatches-woo' ) ] );
		}
		// Cleanup previous meta.
		$this->clean_up_previous_swatches_data( $product_id );
		$this->return_swatches_template( $product_id, $product_object, true );
	}


