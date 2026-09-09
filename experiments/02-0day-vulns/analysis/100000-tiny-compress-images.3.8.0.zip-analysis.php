<?php
/***
*
*Found actions: 11
*Found functions:11
*Extracted functions:11
*Total parameter names extracted: 5
*Overview: {'compress_image_from_library': {'tiny_compress_image_from_library'}, 'compress_image_for_bulk': {'tiny_compress_image_for_bulk'}, 'ajax_optimization_statistics': {'tiny_get_optimization_statistics'}, 'mark_image_as_compressed': {'tiny_mark_image_as_compressed'}, 'image_sizes_notice': {'tiny_image_sizes_notice'}, 'ajax_compression_status': {'tiny_get_compression_status'}, 'update_api_key': {'tiny_settings_update_api_key'}, 'account_status': {'tiny_account_status'}, 'create_api_key': {'tiny_settings_create_api_key'}, 'dismiss': {'tiny_dismiss_notice'}, 'download_diagnostics': {'tiny_download_diagnostics'}}
*
***/

/** Function compress_image_from_library() called by wp_ajax hooks: {'tiny_compress_image_from_library'} **/
/** No params detected :-/ **/


/** Function compress_image_for_bulk() called by wp_ajax hooks: {'tiny_compress_image_for_bulk'} **/
/** Parameters found in function compress_image_for_bulk(): {"post": ["current_size"]} **/
function compress_image_for_bulk() {
		$response = $this->validate_ajax_attachment_request();
		if ( isset( $response['error'] ) ) {
			echo json_encode( $response );
			exit();
		}

		list($id, $metadata)     = $response['data'];
		$tiny_image_before       = new Tiny_Image( $this->settings, $id, $metadata );
		$image_statistics_before = $tiny_image_before->get_statistics(
			$this->settings->get_sizes(),
			$this->settings->get_active_tinify_sizes()
		);
		$size_before             = $image_statistics_before['compressed_total_size'];

		$tiny_image = new Tiny_Image( $this->settings, $id, $metadata );

		Tiny_Logger::debug(
			'compress from bulk',
			array(
				'image_id' => $id,
			)
		);

		$result           = $tiny_image->compress();
		$image_statistics = $tiny_image->get_statistics(
			$this->settings->get_sizes(),
			$this->settings->get_active_tinify_sizes()
		);
		wp_update_attachment_metadata( $id, $tiny_image->get_wp_metadata() );

		// Nonce verified in validate_ajax_attachment_request().
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$current_library_size = isset( $_POST['current_size'] ) ?
			intval( wp_unslash( $_POST['current_size'] ) )
			: 0;
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$size_after       = $image_statistics['compressed_total_size'];
		$new_library_size = $current_library_size + $size_after - $size_before;

		$result['message']                = $tiny_image->get_latest_error();
		$result['image_sizes_compressed'] = $image_statistics['image_sizes_compressed'];
		$result['image_sizes_converted']  = $image_statistics['image_sizes_converted'];
		$result['image_sizes_optimized']  = $image_statistics['image_sizes_optimized'];

		$result['initial_total_size'] = size_format(
			$image_statistics['initial_total_size'],
			1
		);

		$result['optimized_total_size'] = size_format(
			$image_statistics['compressed_total_size'],
			1
		);

		$result['savings']                     = $tiny_image->get_savings( $image_statistics );
		$result['status']                      = $this->settings->get_status();
		$result['thumbnail']                   = wp_get_attachment_image(
			$id,
			array( '30', '30' ),
			true,
			array(
				'class' => 'pinkynail',
				'alt'   => '',
			)
		);
		$result['size_change']                 = $size_after - $size_before;
		$result['human_readable_library_size'] = size_format( $new_library_size, 2 );

		echo json_encode( $result );

		exit();
	}


/** Function ajax_optimization_statistics() called by wp_ajax hooks: {'tiny_get_optimization_statistics'} **/
/** No params detected :-/ **/


/** Function mark_image_as_compressed() called by wp_ajax hooks: {'tiny_mark_image_as_compressed'} **/
/** No params detected :-/ **/


/** Function image_sizes_notice() called by wp_ajax hooks: {'tiny_image_sizes_notice'} **/
/** Parameters found in function image_sizes_notice(): {"get": ["image_sizes_selected", "resize_original", "compress_wr2x"]} **/
function image_sizes_notice() {
		check_ajax_referer( 'tiny-compress' );

		if ( current_user_can( 'manage_options' ) ) {
			$selected_sizes = isset( $_GET['image_sizes_selected'] ) ?
				intval( $_GET['image_sizes_selected'] ) : 0;
			$this->render_size_checkboxes_description(
				$selected_sizes,
				isset( $_GET['resize_original'] ),
				isset( $_GET['compress_wr2x'] ),
				self::get_conversion_enabled()
			);
		}
		exit();
	}


/** Function ajax_compression_status() called by wp_ajax hooks: {'tiny_get_compression_status'} **/
/** No params detected :-/ **/


/** Function update_api_key() called by wp_ajax hooks: {'tiny_settings_update_api_key'} **/
/** Parameters found in function update_api_key(): {"post": ["key"]} **/
function update_api_key() {
		check_ajax_referer( 'tiny-compress', '_nonce' );

		$key = null;
		if ( ! current_user_can( 'manage_options' ) ) {
			$status = (object) array(
				'ok'      => false,
				'message' => 'This feature requires certain user capabilities',
			);
		} elseif ( empty( $_POST['key'] ) ) {
			/* Always save if key is blank, so the key can be deleted. */
			$status = (object) array(
				'ok'      => true,
				'message' => null,
			);
		} else {
			$key    = sanitize_text_field( wp_unslash( $_POST['key'] ) );
			$status = Tiny_Compress::create( $key )->get_status();
		}

		if ( $status->ok ) {
			update_option( self::get_prefixed_name( 'api_key_pending' ), false );
			update_option( self::get_prefixed_name( 'api_key' ), $key );
		}
		echo json_encode( $status );
		exit();
	}


/** Function account_status() called by wp_ajax hooks: {'tiny_account_status'} **/
/** No params detected :-/ **/


/** Function create_api_key() called by wp_ajax hooks: {'tiny_settings_create_api_key'} **/
/** Parameters found in function create_api_key(): {"post": ["name", "email"]} **/
function create_api_key() {
		check_ajax_referer( 'tiny-compress', '_nonce' );

		$compressor = $this->get_compressor();
		if ( ! current_user_can( 'manage_options' ) ) {
			$status = (object) array(
				'ok'      => false,
				'message' => 'This feature requires certain user capabilities',
			);
		} elseif ( $compressor->can_create_key() ) {
			if ( empty( $_POST['name'] ) ) {
				$status = (object) array(
					'ok'      => false,
					'message' => __(
						'Please enter your name',
						'tiny-compress-images'
					),
				);
				echo json_encode( $status );
				exit();
			}

			if ( empty( $_POST['email'] ) ) {
				$status = (object) array(
					'ok'      => false,
					'message' => __(
						'Please enter your email address',
						'tiny-compress-images'
					),
				);
				echo json_encode( $status );
				exit();
			}

			try {
				$site       = str_replace(
					array( 'http://', 'https://' ),
					'',
					get_bloginfo( 'url' )
				);
				$identifier = 'WordPress plugin for ' . $site;
				$link       = $this->get_absolute_url();
				$compressor->create_key(
					sanitize_email( wp_unslash( $_POST['email'] ) ),
					array(
						'name'       => sanitize_text_field( wp_unslash( $_POST['name'] ) ),
						'identifier' => $identifier,
						'link'       => $link,
					)
				);

				update_option( self::get_prefixed_name( 'api_key_pending' ), true );
				update_option( self::get_prefixed_name( 'api_key' ), $compressor->get_key() );
				update_option( self::get_prefixed_name( 'status' ), 0 );

				$status = (object) array(
					'ok'      => true,
					'message' => null,
				);
			} catch ( Tiny_Exception $err ) {
				list($message) = explode( ' (HTTP', $err->getMessage(), 2 );
				$status        = (object) array(
					'ok'      => false,
					'message' => $message,
				);
			}
		} else {
			$status = (object) array(
				'ok'      => false,
				'message' => 'This feature is not available on your platform',
			);
		} // End if().

		echo json_encode( $status );
		exit();
	}


/** Function dismiss() called by wp_ajax hooks: {'tiny_dismiss_notice'} **/
/** Parameters found in function dismiss(): {"post": ["name"]} **/
function dismiss() {
		if ( ! check_ajax_referer( 'tiny-compress', '_nonce', false ) || empty( $_POST['name'] ) ) {
			echo json_encode( false );
			exit();
		}
		$this->load_dismissals();
		$notice_name                      = sanitize_key( wp_unslash( $_POST['name'] ) );
		$this->dismissals[ $notice_name ] = true;
		$this->save_dismissals();
		echo json_encode( true );
		exit();
	}


/** Function download_diagnostics() called by wp_ajax hooks: {'tiny_download_diagnostics'} **/
/** No params detected :-/ **/


