<?php
/***
*
*Found actions: 54
*Found functions:43
*Extracted functions:35
*Total parameter names extracted: 7
*Overview: {'wbcr_riop_optimizeImages': {'wio_process_ng_images'}, 'processing_stop': {'wrio-cron-stop'}, 'avif_cron_start': {'wrio-avif-cron-start'}, 'wrio_clean_logs': {'wrio_logs_cleanup'}, 'check_user_balance': {'wbcr-rio-check-user-balance'}, 'update_blog_id': {'wbcr_rio_update_current_blog'}, 'processing_start': {'wrio-cron-start'}, 'wio-iph': {'wio_cf_restore_backup', 'wriop_process_cf_images', 'wio_clear_backup', 'wio_settings_update_level', 'wio_restore_backup', 'wriop_process_cf_folder_images'}, 'cron_stop': {'wrio-cron-stop'}, 'ajax_handler_install_components': {'wfactory-600-intall-component'}, 'ajax_handler_install_creativemotion_plugins': {'wfactory-600-creativemotion-install-plugin'}, 'calculate_total_thumbs': {'wbcr-rio-calculate-total-thumbs'}, 'webp_processing_stop': {'wrio-webp-cron-stop'}, 'bulk_optimization': {'wrio-add-custom-folder', 'wriop_folder_sync_index', 'wio_cf_reload_ui', 'wriop_remove_folder', 'wriop_browse_dir', 'wrio-scan-folder'}, 'bulk_conversion_process': {'wrio-bulk-conversion-process'}, 'reoptimize': {'wio_cf_reoptimize_image'}, 'wrio_dismiss_avif_banner': {'wrio_dismiss_avif_banner'}, 'restore': {'wio_cf_restore_image'}, 'ajax_handler': {'wrio_subscribe', 'wrio_license_action'}, 'convert_image': {'wio_convert_image'}, 'wbcr_riop_restoreImage': {'wio_ng_restore_image'}, 'avif_processing_stop': {'wrio-avif-cron-stop'}, 'calculate_total_images': {'wbcr-rio-calculate-total-images'}, 'template': {'wio_cf_get_template_part'}, 'disable_notification_ajax': {'themeisle_sdk_dismiss_black_friday_notice'}, 'webp_processing_start': {'wrio-webp-cron-start'}, 'check_servers_status': {'wbcr-rio-check-servers-status'}, 'bulk_optimization_process': {'wrio-bulk-optimization-process'}, 'reoptimize_image': {'wio_reoptimize_image'}, 'calculate_total_attachments': {'wbcr-rio-calculate-total-attachments'}, 'avif_cron_stop': {'wrio-avif-cron-stop'}, 'wbcr_rio_migrate_postmeta_to_process_queue': {'wrio_meta_migrations'}, 'wbcr_riop_reoptimizeImage': {'wio_ng_reoptimize_image'}, 'restore_image': {'wio_restore_image'}, 'ThemeisleSDK\\Modules\\Notification::dismiss': {'themeisle_sdk_dismiss_notice'}, 'dismiss': {'themeisle_sdk_dismiss_notice'}, 'webp_cron_stop': {'wrio-webp-cron-stop'}, 'webp_cron_start': {'wrio-webp-cron-start'}, 'avif_processing_start': {'wrio-avif-cron-start'}, 'dismiss_license_notice': {'themeisle_sdk_dismiss_license_notice'}, 'ajax_handler_prepare_component': {'wfactory-600-prepare-component'}, 'cron_start': {'wrio-cron-start'}, 'dismiss_promotion': {'tisdk_update_option'}}
*
***/

/** Function wbcr_riop_optimizeImages() called by wp_ajax hooks: {'wio_process_ng_images'} **/
/** No function found :-/ **/


/** Function processing_stop() called by wp_ajax hooks: {'wrio-cron-stop'} **/
/** No params detected :-/ **/


/** Function avif_cron_start() called by wp_ajax hooks: {'wrio-avif-cron-start'} **/
/** No params detected :-/ **/


/** Function wrio_clean_logs() called by wp_ajax hooks: {'wrio_logs_cleanup'} **/
/** No function found :-/ **/


/** Function check_user_balance() called by wp_ajax hooks: {'wbcr-rio-check-user-balance'} **/
/** No params detected :-/ **/


/** Function update_blog_id() called by wp_ajax hooks: {'wbcr_rio_update_current_blog'} **/
/** No function found :-/ **/


/** Function processing_start() called by wp_ajax hooks: {'wrio-cron-start'} **/
/** No params detected :-/ **/


/** Function wio-iph() called by wp_ajax hooks: {'wio_cf_restore_backup', 'wriop_process_cf_images', 'wio_clear_backup', 'wio_settings_update_level', 'wio_restore_backup', 'wriop_process_cf_folder_images'} **/
/** No function found :-/ **/


/** Function cron_stop() called by wp_ajax hooks: {'wrio-cron-stop'} **/
/** No params detected :-/ **/


/** Function ajax_handler_install_components() called by wp_ajax hooks: {'wfactory-600-intall-component'} **/
/** No params detected :-/ **/


/** Function ajax_handler_install_creativemotion_plugins() called by wp_ajax hooks: {'wfactory-600-creativemotion-install-plugin'} **/
/** No params detected :-/ **/


/** Function calculate_total_thumbs() called by wp_ajax hooks: {'wbcr-rio-calculate-total-thumbs'} **/
/** No params detected :-/ **/


/** Function webp_processing_stop() called by wp_ajax hooks: {'wrio-webp-cron-stop'} **/
/** No params detected :-/ **/


/** Function bulk_optimization() called by wp_ajax hooks: {'wrio-add-custom-folder', 'wriop_folder_sync_index', 'wio_cf_reload_ui', 'wriop_remove_folder', 'wriop_browse_dir', 'wrio-scan-folder'} **/
/** No function found :-/ **/


/** Function bulk_conversion_process() called by wp_ajax hooks: {'wrio-bulk-conversion-process'} **/
/** No params detected :-/ **/


/** Function reoptimize() called by wp_ajax hooks: {'wio_cf_reoptimize_image'} **/
/** No function found :-/ **/


/** Function wrio_dismiss_avif_banner() called by wp_ajax hooks: {'wrio_dismiss_avif_banner'} **/
/** No function found :-/ **/


/** Function restore() called by wp_ajax hooks: {'wio_cf_restore_image'} **/
/** No params detected :-/ **/


/** Function ajax_handler() called by wp_ajax hooks: {'wrio_subscribe', 'wrio_license_action'} **/
/** Parameters found in function ajax_handler(): {"post": ["email"]} **/
function ajax_handler() {
		check_ajax_referer( 'wrio_subscribe', '_wpnonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => __( 'Permission denied.', 'robin-image-optimizer' ) ] );
		}

		$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

		if ( ! is_email( $email ) ) {
			wp_send_json_error( [ 'message' => __( 'Please enter a valid email address.', 'robin-image-optimizer' ) ] );
		}

		$body = wp_json_encode(
			[
				'slug'  => $this->plugin_name,
				'site'  => home_url(),
				'email' => $email,
			]
		);

		// Ensure body is a string, not false.
		if ( false === $body ) {
			wp_send_json_error( [ 'message' => __( 'Failed to encode request body.', 'robin-image-optimizer' ) ] );
		}

		$response = wp_remote_post(
			self::API_URL,
			[
				'timeout' => 10,
				'headers' => [
					'Content-Type'  => 'application/json',
					'Cache-Control' => 'no-cache',
					'Accept'        => 'application/json, */*;q=0.1',
				],
				'body'    => $body,
			]
		);

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( [ 'message' => $response->get_error_message() ] );
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( isset( $data['code'] ) ) {
			update_option( self::OPTION_SUBSCRIBED, 1 );
			wp_send_json_success( [ 'message' => __( 'Subscribed successfully!', 'robin-image-optimizer' ) ] );
		}

		wp_send_json_error( [ 'message' => __( 'Subscription failed. Please try again.', 'robin-image-optimizer' ) ] );
	}


/** Function convert_image() called by wp_ajax hooks: {'wio_convert_image'} **/
/** No params detected :-/ **/


/** Function wbcr_riop_restoreImage() called by wp_ajax hooks: {'wio_ng_restore_image'} **/
/** Parameters found in function wbcr_riop_restoreImage(): {"post": ["id"]} **/
function wbcr_riop_restoreImage() {
	if ( ! check_ajax_referer( 'restore', false, false ) || ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( [ 'message' => 'Unauthorized' ], 403 );
	}

	wp_suspend_cache_addition( true );
	$image_id = isset( $_POST['id'] ) ? (int) $_POST['id'] : 0;

	$nextgen_gallery  = WRIO_Nextgen_Gallery::get_instance();
	$nextgen_image    = $nextgen_gallery->getNextgenImage( $image_id );
	$image_statistics = WRIO_Image_Statistic_Nextgen::get_instance();
	if ( $nextgen_image->isOptimized() ) {
		$restored = $nextgen_image->restore();

		if ( ! is_wp_error( $restored ) ) {
			$optimization_data   = $nextgen_image->getOptimizationData();
			$optimized_size      = $optimization_data->get_final_size();
			$original_size       = $optimization_data->get_original_size();
			$webp_optimized_size = $optimization_data->get_extra_data()->get_webp_main_size();
			$image_statistics->deductFromField( 'webp_optimized_size', $webp_optimized_size );
			$image_statistics->deductFromField( 'optimized_size', $optimized_size );
			$image_statistics->deductFromField( 'original_size', $original_size );
			$image_statistics->save();
		}
	}

	$nextgen_gallery = WRIO_Nextgen_Gallery::get_instance();
	echo $nextgen_gallery->getMediaColumnContent( $image_id );
	die();
}


/** Function avif_processing_stop() called by wp_ajax hooks: {'wrio-avif-cron-stop'} **/
/** No params detected :-/ **/


/** Function calculate_total_images() called by wp_ajax hooks: {'wbcr-rio-calculate-total-images'} **/
/** No params detected :-/ **/


/** Function template() called by wp_ajax hooks: {'wio_cf_get_template_part'} **/
/** No function found :-/ **/


/** Function disable_notification_ajax() called by wp_ajax hooks: {'themeisle_sdk_dismiss_black_friday_notice'} **/
/** No params detected :-/ **/


/** Function webp_processing_start() called by wp_ajax hooks: {'wrio-webp-cron-start'} **/
/** No params detected :-/ **/


/** Function check_servers_status() called by wp_ajax hooks: {'wbcr-rio-check-servers-status'} **/
/** No params detected :-/ **/


/** Function bulk_optimization_process() called by wp_ajax hooks: {'wrio-bulk-optimization-process'} **/
/** No params detected :-/ **/


/** Function reoptimize_image() called by wp_ajax hooks: {'wio_reoptimize_image'} **/
/** No params detected :-/ **/


/** Function calculate_total_attachments() called by wp_ajax hooks: {'wbcr-rio-calculate-total-attachments'} **/
/** No params detected :-/ **/


/** Function avif_cron_stop() called by wp_ajax hooks: {'wrio-avif-cron-stop'} **/
/** No params detected :-/ **/


/** Function wbcr_rio_migrate_postmeta_to_process_queue() called by wp_ajax hooks: {'wrio_meta_migrations'} **/
/** No params detected :-/ **/


/** Function wbcr_riop_reoptimizeImage() called by wp_ajax hooks: {'wio_ng_reoptimize_image'} **/
/** Parameters found in function wbcr_riop_reoptimizeImage(): {"post": ["id", "level"]} **/
function wbcr_riop_reoptimizeImage() {
	if ( ! check_ajax_referer( 'reoptimize', false, false ) || ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( [ 'message' => 'Unauthorized' ], 403 );
	}

	$image_id             = (int) $_POST['id'];
	$backup               = WRIOP_Backup::get_instance();
	$backup_origin_images = WRIO_Plugin::app()->getPopulateOption( 'backup_origin_images', false );
	$nextgen_gallery      = WRIO_Nextgen_Gallery::get_instance();
	if ( $backup_origin_images && ! $backup->isBackupWritable() ) {
		echo $nextgen_gallery->getMediaColumnContent( $image_id );
		die();
	}
	wp_suspend_cache_addition( true );
	$default_level = WRIO_Plugin::app()->getPopulateOption( 'image_optimization_level', 'normal' );
	$level         = isset( $_POST['level'] ) ? sanitize_text_field( $_POST['level'] ) : $default_level;

	$optimized_data = $nextgen_gallery->optimizeNextgenImage( $image_id, $level );

	if ( $optimized_data && isset( $optimized_data['processing'] ) ) {
		echo 'processing';
		die();
	}

	echo $nextgen_gallery->getMediaColumnContent( $image_id );
	die();
}


/** Function restore_image() called by wp_ajax hooks: {'wio_restore_image'} **/
/** No params detected :-/ **/


/** Function ThemeisleSDK\Modules\Notification::dismiss() called by wp_ajax hooks: {'themeisle_sdk_dismiss_notice'} **/
/** Parameters found in function ThemeisleSDK\Modules\Notification::dismiss(): {"post": ["id", "confirm"]} **/
function dismiss() {
		check_ajax_referer( (string) __CLASS__, 'nonce' );

		$id      = isset( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : '';
		$confirm = isset( $_POST['confirm'] ) ? sanitize_text_field( $_POST['confirm'] ) : 'no';

		if ( empty( $id ) ) {
			wp_send_json( [] );
		}
		self::setup_notifications();
		$ids = wp_list_pluck( self::$notifications, 'id' );
		if ( ! in_array( $id, $ids, true ) ) {
			wp_send_json( [] );
		}
		self::set_last_active_notification_timestamp();
		update_option( $id, $confirm );
		do_action( $id . '_process_confirm', $confirm );
		wp_send_json( [] );
	}


/** Function dismiss() called by wp_ajax hooks: {'themeisle_sdk_dismiss_notice'} **/
/** Parameters found in function dismiss(): {"post": ["id", "confirm"]} **/
function dismiss() {
		check_ajax_referer( (string) __CLASS__, 'nonce' );

		$id      = isset( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : '';
		$confirm = isset( $_POST['confirm'] ) ? sanitize_text_field( $_POST['confirm'] ) : 'no';

		if ( empty( $id ) ) {
			wp_send_json( [] );
		}
		self::setup_notifications();
		$ids = wp_list_pluck( self::$notifications, 'id' );
		if ( ! in_array( $id, $ids, true ) ) {
			wp_send_json( [] );
		}
		self::set_last_active_notification_timestamp();
		update_option( $id, $confirm );
		do_action( $id . '_process_confirm', $confirm );
		wp_send_json( [] );
	}


/** Function webp_cron_stop() called by wp_ajax hooks: {'wrio-webp-cron-stop'} **/
/** No params detected :-/ **/


/** Function webp_cron_start() called by wp_ajax hooks: {'wrio-webp-cron-start'} **/
/** No params detected :-/ **/


/** Function avif_processing_start() called by wp_ajax hooks: {'wrio-avif-cron-start'} **/
/** No params detected :-/ **/


/** Function dismiss_license_notice() called by wp_ajax hooks: {'themeisle_sdk_dismiss_license_notice'} **/
/** Parameters found in function dismiss_license_notice(): {"post": ["nonce", "notice_id"]} **/
function dismiss_license_notice() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'themeisle_sdk_dismiss_license_notice' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Insufficient permissions' );
		}

		$notice_id = isset( $_POST['notice_id'] ) ? sanitize_text_field( $_POST['notice_id'] ) : '';

		if ( empty( $notice_id ) ) {
			wp_send_json_error( 'Missing notice ID' );
		}

		// Save the dismissal option.
		$dismiss_option_key = $notice_id . '_dismissed';
		update_option( $dismiss_option_key, true );

		wp_send_json_success();
	}


/** Function ajax_handler_prepare_component() called by wp_ajax hooks: {'wfactory-600-prepare-component'} **/
/** No params detected :-/ **/


/** Function cron_start() called by wp_ajax hooks: {'wrio-cron-start'} **/
/** No params detected :-/ **/


/** Function dismiss_promotion() called by wp_ajax hooks: {'tisdk_update_option'} **/
/** Parameters found in function dismiss_promotion(): {"post": ["nonce", "value"]} **/
function dismiss_promotion() {
		if ( ! isset( $_POST['nonce'] ) || ! isset( $_POST['value'] ) ) {
			$response = array(
				'success' => false,
				'message' => 'Missing nonce or value.',
			);
			wp_send_json( $response );
			wp_die();
		}

		$nonce = sanitize_text_field( $_POST['nonce'] );
		$value = sanitize_text_field( $_POST['value'] );

		if ( ! wp_verify_nonce( $nonce, 'tisdk_update_option' ) ) {
			$response = array(
				'success' => false,
				'message' => 'Invalid nonce.',
			);
			wp_send_json( $response );
			wp_die();
		}

		$options = get_option( $this->option_main );
		$options = json_decode( $options, true );

		$options[ $value ] = time();

		update_option( $this->option_main, wp_json_encode( $options ) );

		$response = array(
			'success' => true,
		);

		wp_send_json( $response );
		wp_die();
	}


