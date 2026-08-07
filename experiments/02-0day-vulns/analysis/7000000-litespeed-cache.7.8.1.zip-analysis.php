<?php
/***
*
*Found actions: 3
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 2
*Overview: {'LiteSpeed\\Task::async_litespeed_handler': {'nopriv_async_litespeed', 'async_litespeed'}, '::bulk_edit_purge': {'wpmelon_adv_bulk_edit'}}
*
***/

/** Function LiteSpeed\Task::async_litespeed_handler() called by wp_ajax hooks: {'nopriv_async_litespeed', 'async_litespeed'} **/
/** Parameters found in function LiteSpeed\Task::async_litespeed_handler(): {"get": ["nonce"]} **/
function async_litespeed_handler() {
		$hash_data = self::get_option( 'async_call-hash', [] );
		if ( ! $hash_data || ! is_array( $hash_data ) || empty( $hash_data['hash'] ) || empty( $hash_data['ts'] ) ) {
			self::debug( 'async_litespeed_handler no hash data', $hash_data );
			return;
		}

		$nonce = isset( $_GET['nonce'] ) ? sanitize_text_field( wp_unslash( $_GET['nonce'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( 120 < time() - (int) $hash_data['ts'] || '' === $nonce || $nonce !== $hash_data['hash'] ) {
			self::debug( 'async_litespeed_handler nonce mismatch' );
			return;
		}
		self::delete_option( 'async_call-hash' );

		$type = Router::verify_type();
		self::debug( 'type=' . $type );

		// Don't lock up other requests while processing.
		session_write_close();

		switch ( $type ) {
			case 'crawler':
				Crawler::async_handler();
				break;
			case 'crawler_force':
				Crawler::async_handler( true );
				break;
			case 'imgoptm':
				Img_Optm::async_handler();
				break;
			case 'imgoptm_force':
				Img_Optm::async_handler( true );
				break;
			default:
				break;
		}
	}


/** Function ::bulk_edit_purge() called by wp_ajax hooks: {'wpmelon_adv_bulk_edit'} **/
/** Parameters found in function ::bulk_edit_purge(): {"post": ["type", "data"]} **/
function bulk_edit_purge() {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Third-party plugin action; cannot enforce here.
		if ( empty( $_POST['type'] ) || 'saveproducts' !== $_POST['type'] || empty( $_POST['data'] ) ) {
			return;
		}

		/*
		 * admin-ajax form-data structure
		 * array(
		 *      "type" => "saveproducts",
		 *      "data" => array(
		 *          "column1" => "464$###0$###2#^#463$###0$###4#^#462$###0$###6#^#",
		 *          "column2" => "464$###0$###2#^#463$###0$###4#^#462$###0$###6#^#"
		 *      )
		 *  )
		 */
		$stock_string_arr = [];

		// Ensure data is unslashed before processing.
		// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Third-party plugin may not send nonce. Data format defined by third-party plugin.
		$data = isset( $_POST['data'] ) ? wp_unslash( $_POST['data'] ) : [];

		foreach ( (array) $data as $stock_value ) {
			$stock_string_arr = array_merge( $stock_string_arr, explode( '#^#', (string) $stock_value ) );
		}

		$lscwp_3rd_woocommerce = new self();

		if ( count( $stock_string_arr ) < 1 ) {
			return;
		}

		foreach ( $stock_string_arr as $edited_stock ) {
			$product_id = (int) strtok( (string) $edited_stock, '$' );
			$product    = wc_get_product( $product_id );

			if ( empty( $product ) ) {
				do_action( 'litespeed_debug', '3rd woo purge: ' . $product_id . ' not found.' );
				continue;
			}

			$lscwp_3rd_woocommerce->purge_product( $product );
		}
	}


