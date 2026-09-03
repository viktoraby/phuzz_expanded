<?php
/***
*
*Found actions: 65
*Found functions:63
*Extracted functions:62
*Total parameter names extracted: 61
*Overview: {'ewww_image_optimizer_bulk_loop': {'ewww_bulk_loop'}, 'ewww_image_optimizer_webp_cleanup': {'webp_cleanup'}, 'ewww_image_optimizer_delete_webp_handler': {'bulk_aux_images_delete_webp'}, 'dismiss_newsletter_signup_notice': {'ewww_dismiss_newsletter'}, 'ewww_image_optimizer_ajax_delete_original': {'bulk_aux_images_delete_original'}, 'ewww_ngg_bulk_init': {'bulk_ngg_init'}, 'ewww_image_optimizer_exactdn_activate_site_ajax': {'ewww_exactdn_activate_site'}, 'ewww_image_optimizer_webp_unwrite': {'ewww_webp_unwrite'}, 'ewww_image_optimizer_ajax_get_attachment_status': {'ewww_manual_get_status'}, 'ewww_flag_bulk_cleanup': {'bulk_flag_cleanup'}, 'ewww_image_optimizer_bulk_scan_init': {'ewww_bulk_scan_init'}, 'dismiss_utf8_notice': {'ewww_dismiss_utf8_notice'}, 'ajax_add_ignore_scaling_rule': {'ewww_add_ignore_scaling_rule'}, 'ewww_image_optimizer_bulk_initialize': {'ewww_bulk_init'}, 'ewww_image_optimizer_ajax_retest_background_optimization': {'ewww_retest_async'}, 'ewww_image_optimizer_get_image_savings': {'ewww_get_image_savings'}, 'check_for_optout': {'ewww_opt_out_of_tracking'}, 'ewww_image_optimizer_manual': {'ewww_manual_image_restore', 'ewww_manual_optimize', 'ewww_manual_restore'}, 'ewww_image_optimizer_media_scan': {'ewww_bulk_scan'}, 'ewww_image_optimizer_exactdn_deregister_site_ajax': {'ewww_exactdn_deregister_site'}, 'ewww_image_optimizer_aux_images_count_converted': {'bulk_aux_images_count_converted'}, 'ewww_image_optimizer_bulk_restore_handler': {'bulk_aux_images_restore_original'}, 'ewww_image_optimizer_aux_images_table_count': {'bulk_aux_images_table_count'}, 'ewww_ngg_bulk_cleanup': {'bulk_ngg_cleanup'}, 'ewww_flag_bulk_loop': {'bulk_flag_loop'}, 'ewww_image_optimizer_bulk_async_get_status': {'ewww_bulk_async_get_status'}, 'ewww_flag_manual': {'ewww_flag_manual'}, 'ewww_image_optimizer_aux_images_table': {'bulk_aux_images_table'}, 'ewww_image_optimizer_webp_loop': {'webp_loop'}, 'ewww_image_optimizer_aux_images_webp_clean_handler': {'bulk_aux_images_webp_clean'}, 'dismiss_media_notice': {'ewww_dismiss_media_notice'}, 'ewww_image_optimizer_get_all_attachments': {'ewwwio_get_all_attachments'}, 'ewww_ngg_image_restore': {'ewww_ngg_image_restore'}, 'ewww_image_optimizer_webp_attachment_count': {'ewwwio_webp_attachment_count'}, 'ewww_image_optimizer_webp_rewrite': {'ewww_webp_rewrite'}, 'ewww_flag_bulk_init': {'bulk_flag_init'}, 'dismiss_exec_notice': {'ewww_dismiss_exec_notice'}, 'ewww_image_optimizer_webp_initialize': {'webp_init'}, 'ewww_image_optimizer_cloud_key_verify_ajax': {'ewww_cloud_key_verify'}, 'ewww_image_optimizer_bulk_update_meta': {'ewww_bulk_update_meta'}, 'ewww_image_optimizer_exactdn_get_site_stats_ajax': {'exactdn_get_site_stats'}, 'ewww_image_optimizer_bulk_async_init': {'ewww_bulk_async_init'}, 'ewww_ngg_bulk_loop': {'bulk_ngg_loop'}, 'check_for_optin': {'ewww_opt_into_tracking'}, 'ewww_image_optimizer_aux_images_clear_all': {'bulk_aux_images_table_clear'}, 'ewww_image_optimizer_aux_images_converted_clean': {'bulk_aux_images_converted_clean'}, 'ajax_add_lazy_exclusion': {'ewww_add_lazy_exclusion'}, 'ewww_image_optimizer_exactdn_register_site_ajax': {'ewww_exactdn_register_site'}, 'ewww_image_optimizer_aux_images_remove': {'bulk_aux_images_remove'}, 'ewww_ngg_manual': {'ewww_ngg_manual'}, 'dismiss_review_notice': {'ewww_dismiss_review_notice'}, 'ewww_flag_image_restore': {'ewww_flag_image_restore'}, 'ewww_image_optimizer_aux_images_clean': {'bulk_aux_images_table_clean'}, 'ewww_ngg_cloud_restore': {'ewww_ngg_cloud_restore'}, 'ewww_image_optimizer_bulk_quota_update': {'bulk_quota_update'}, 'dismiss_lightroom_sync_notice': {'ewww_dismiss_lr_sync'}, 'restore_single_image_handler': {'ewww_manual_image_restore_single'}, 'ewww_image_optimizer_bulk_cleanup': {'ewww_bulk_cleanup'}, 'ewww_image_optimizer_get_bulk_info': {'ewww_get_bulk_info'}, 'ewww_image_optimizer_aux_images_exclude': {'bulk_aux_images_exclude'}, 'dismiss_wc_regen_notice': {'ewww_dismiss_wc_regen'}, 'ewww_image_optimizer_exactdn_activate_ajax': {'ewww_exactdn_activate'}, 'ewww_image_optimizer_aux_meta_clean': {'bulk_aux_images_meta_clean'}}
*
***/

/** Function ewww_image_optimizer_bulk_loop() called by wp_ajax hooks: {'ewww_bulk_loop'} **/
/** Parameters found in function ewww_image_optimizer_bulk_loop(): {"request": ["ewww_wpnonce", "ewww_force", "ewww_force_smart", "ewww_webp_only", "ewww_batch_limit", "ewww_error_counter"]} **/
function ewww_image_optimizer_bulk_loop( $hook = '', $delay = 0 ) {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	global $ewwwio_resize_status;
	ewwwio()->defer  = false;
	$output          = array();
	$time_adjustment = 0;
	$add_to_total    = 0;
	add_filter( 'ewww_image_optimizer_allowed_reopt', '__return_true' );
	// Verify that an authorized user has started the optimizer.
	$permissions = apply_filters( 'ewww_image_optimizer_bulk_permissions', '' );
	if (
		'ewww-image-optimizer-cli' !== $hook &&
		(
			empty( $_REQUEST['ewww_wpnonce'] ) ||
			! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-bulk' ) ||
			! current_user_can( $permissions )
		)
	) {
		ewwwio_ob_clean();
		die( wp_json_encode( array( 'error' => esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) ) ) );
	}
	session_write_close();
	// Retrieve the time when the optimizer starts.
	$started = microtime( true );
	// Prevent the scheduled optimizer from firing during a bulk optimization.
	set_transient( 'ewww_image_optimizer_no_scheduled_optimization', true, 10 * MINUTE_IN_SECONDS );
	// Make the Force Re-optimize option persistent.
	if ( ! empty( $_REQUEST['ewww_force'] ) ) {
		ewwwio()->force = true;
		set_transient( 'ewww_image_optimizer_force_reopt', true, HOUR_IN_SECONDS );
	} else {
		ewwwio()->force = false;
		delete_transient( 'ewww_image_optimizer_force_reopt' );
	}
	// Make the Smart Re-optimize option persistent.
	if ( ! empty( $_REQUEST['ewww_force_smart'] ) ) {
		ewwwio()->force_smart = true;
		set_transient( 'ewww_image_optimizer_smart_reopt', true, HOUR_IN_SECONDS );
	} else {
		ewwwio()->force_smart = false;
		delete_transient( 'ewww_image_optimizer_smart_reopt' );
	}
	if ( ! empty( $_REQUEST['ewww_webp_only'] ) ) {
		ewwwio()->webp_only = true;
	}
	// Find out if our nonce is on it's last leg/tick.
	if ( ! empty( $_REQUEST['ewww_wpnonce'] ) ) {
		$output['new_nonce'] = ewwwio_maybe_get_new_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-bulk' );
	}
	$batch_image_limit = ( empty( $_REQUEST['ewww_batch_limit'] ) && ! ewww_image_optimizer_s3_uploads_enabled() ? 999 : 1 );
	// Get the 'bulk attachments' with a list of IDs remaining.
	$attachments = ewww_image_optimizer_get_queued_attachments( 'media', $batch_image_limit );
	if ( ! empty( $attachments ) && is_array( $attachments ) ) {
		$attachment = (int) $attachments[0];
	} else {
		$attachment = 0;
	}
	$image = new EWWW_Image( $attachment, 'media' );
	if ( ! $image->file ) {
		ewwwio_ob_clean();
		die(
			wp_json_encode(
				array(
					'done'      => 1,
					'completed' => 0,
				)
			)
		);
	}

	$output['results']   = array();
	$output['completed'] = 0;
	while ( $output['completed'] < $batch_image_limit && $image->file && microtime( true ) - $started + $time_adjustment < apply_filters( 'ewww_image_optimizer_timeout', 15 ) ) {
		++$output['completed'];
		$meta = false;
		ewwwio_debug_message( "processing {$image->id}: {$image->file}" );
		// See if the image needs fetching from a CDN.
		if ( ! ewwwio_is_file( $image->file ) ) {
			$meta      = wp_get_attachment_metadata( $image->attachment_id );
			$file_path = ewww_image_optimizer_remote_fetch( $image->attachment_id, $meta );
			// Nuke the meta, otherwise this will trigger unnecessary metadata updates,
			// which should be reserved for conversion/resize operations on the full-size image only.
			unset( $meta );
			if ( ! $file_path ) {
				ewwwio_debug_message( 'could not retrieve path' );
				if ( defined( 'WP_CLI' ) && WP_CLI ) {
					WP_CLI::line( __( 'Could not find image', 'ewww-image-optimizer' ) . ' ' . $image->file );
				} else {
					/* translators: %s: image file name */
					$output['results'][] = '<tr><td colspan="5">' . sprintf( esc_html__( 'Could not find image: %s', 'ewww-image-optimizer' ), '<strong>' . esc_html( $image->file ) . '</strong>' ) . '</td></tr>';
				}
			}
		}
		if ( ! empty( $_REQUEST['ewww_error_counter'] ) ) {
			$error_counter   = (int) $_REQUEST['ewww_error_counter'];
			$countermeasures = ewww_image_optimizer_bulk_counter_measures( $image, $error_counter );
			if ( $countermeasures ) {
				$batch_image_limit = 1;
			}
		}
		set_transient( 'ewww_image_optimizer_bulk_current_image', $image->file, 600 );
		$image_started = microtime( true );
		global $ewww_image;
		$ewww_image = $image;
		if ( 'full' === $image->resize && ewww_image_optimizer_get_option( 'ewww_image_optimizer_resize_existing' ) && ! function_exists( 'imsanity_get_max_width_height' ) ) {
			if ( empty( $meta ) || ! is_array( $meta ) ) {
				$meta = wp_get_attachment_metadata( $image->attachment_id );
			}
			$new_dimensions = ewww_image_optimizer_resize_upload( $image->file );
			if ( ! empty( $new_dimensions ) && is_array( $new_dimensions ) ) {
				$meta['width']  = $new_dimensions[0];
				$meta['height'] = $new_dimensions[1];
				if ( ewww_image_optimizer_get_option( 'ewww_image_optimizer_preserve_originals' ) ) {
					$meta        = ewww_image_optimizer_update_scaled_metadata( $meta, $image->attachment_id );
					$scaled_file = ewww_image_optimizer_scaled_filename( $image->file );
					if ( ewwwio_is_file( $scaled_file ) ) {
						global $wpdb;
						if ( ! empty( $ewww_image->id ) && ! ewww_image_optimizer_get_option( 'ewww_image_optimizer_include_originals' ) ) {
							ewww_image_optimizer_delete_pending_image( $ewww_image->id );
						} elseif ( ! empty( $ewww_image->id ) ) {
							$add_to_total = 1;
							$wpdb->update(
								$wpdb->ewwwio_images,
								array(
									'resize' => 'original_image',
								),
								array(
									'id' => $ewww_image->id,
								)
							);
						}
						set_transient( 'ewww_image_optimizer_bulk_current_image', $scaled_file, 600 );
						delete_transient( 'ewww_image_optimizer_failed_file' );
						$ewww_image                = new EWWW_Image( 0, 'media', $scaled_file );
						$ewww_image->resize        = 'full';
						$ewww_image->attachment_id = $image->attachment_id;
						$ewww_image->gallery       = 'media';
						$wpdb->update(
							$wpdb->ewwwio_images,
							array(
								'pending'       => 1,
								'attachment_id' => $image->attachment_id,
								'gallery'       => 'media',
								'resize'        => 'full',
							),
							array(
								'id' => $ewww_image->id,
							)
						);
						$image = $ewww_image;
					}
				}
			}
		} elseif ( empty( $image->resize ) && ewww_image_optimizer_should_resize_other_image( $image->file ) ) {
			$new_dimensions = ewww_image_optimizer_resize_upload( $image->file );
		}

		list( $file, $msg, $converted, $original ) = ewww_image_optimizer( $image->file, 1, false, false, 'full' === $image->resize );

		// Gotta make sure we don't delete a pending record if the license is exceeded, so the license check goes first.
		if ( ewww_image_optimizer_get_option( 'ewww_image_optimizer_cloud_key' ) ) {
			if ( 'exceeded' === get_transient( 'ewww_image_optimizer_cloud_status' ) ) {
				$output['error'] = ewww_image_optimizer_credits_exceeded();
				delete_transient( 'ewww_image_optimizer_bulk_counter_measures' );
				delete_transient( 'ewww_image_optimizer_bulk_current_image' );
				ewwwio_ob_clean();
				die( wp_json_encode( $output ) );
			}
			if ( 'exceeded quota' === get_transient( 'ewww_image_optimizer_cloud_status' ) ) {
				$output['error'] = ewww_image_optimizer_soft_quota_exceeded();
				delete_transient( 'ewww_image_optimizer_bulk_counter_measures' );
				delete_transient( 'ewww_image_optimizer_bulk_current_image' );
				ewwwio_ob_clean();
				die( wp_json_encode( $output ) );
			}
			if ( 'exceeded subkey' === get_transient( 'ewww_image_optimizer_cloud_status' ) ) {
				$output['error'] = esc_html__( 'Out of credits', 'ewww-image-optimizer' );
				delete_transient( 'ewww_image_optimizer_bulk_counter_measures' );
				delete_transient( 'ewww_image_optimizer_bulk_current_image' );
				ewwwio_ob_clean();
				die( wp_json_encode( $output ) );
			}
		}

		// Delete a pending record if the optimization failed for whatever reason.
		if ( ! $file && $image->id ) {
			ewww_image_optimizer_delete_pending_image( $image->id );
			if ( $msg ) {
				/* translators: %s: image file name */
				$output['results'][] = '<tr><td colspan="5">' . esc_html( $msg ) . '<br><strong>' . esc_html( $image->file ) . '</strong></td></tr>';
			}
		}
		// Toggle a pending record if the optimization was webp-only.
		if ( true === $file && $image->id ) {
			ewww_image_optimizer_toggle_pending_image( $image->id );
		}

		// If this is a full size image and it was converted.
		if ( 'full' === $image->resize && false !== $converted ) {
			if ( empty( $meta ) || ! is_array( $meta ) ) {
				$meta = wp_get_attachment_metadata( $image->attachment_id );
			}
			$image->file      = $file;
			$image->converted = $original;
			$meta['file']     = _wp_relative_upload_path( $file );
			$image->update_converted_attachment( $meta );
			$meta = $image->convert_sizes( $meta );
		}

		// Calculate how much time has elapsed since we started.
		$image_elapsed = microtime( true ) - $image_started;
		if ( $file && $image->id && is_object( $ewww_image ) && ! empty( $ewww_image->record ) && is_array( $ewww_image->record ) ) {
			$ewww_image->record['elapsed'] = $image_elapsed;
			$ewww_image->record['updated'] = time();
			if ( ewww_image_optimizer_get_option( 'ewww_image_optimizer_debug' ) ) {
				$ewww_image->record['debug'] = EWWW\Base::$debug_data;
				ewww_image_optimizer_debug_log();
			}
			$output['results'][] = trim( ewww_image_optimizer_get_image_table_row( $ewww_image->record, false, false ) );
		}

		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			WP_CLI::line( __( 'Optimized', 'ewww-image-optimizer' ) . ' ' . $image->file );
			WP_CLI::line( str_replace( array( '&nbsp;', '<br>' ), array( '', "\n" ), $msg ) );
			if ( ! empty( $ewwwio_resize_status ) ) {
				WP_CLI::line( $ewwwio_resize_status );
			}
		}

		// Do metadata update after full-size is processed, usually because of conversion or resizing.
		if ( 'full' === $image->resize && $image->attachment_id ) {
			ewwwio_debug_message( 'saving meta for ' . $image->attachment_id );
			if ( ! empty( $meta ) && is_array( $meta ) ) {
				clearstatcache();
				if ( ! empty( $image->file ) && is_file( $image->file ) ) {
					$meta['filesize'] = filesize( $image->file );
				}
				add_filter( 'as3cf_pre_update_attachment_metadata', '__return_true' );
				$meta_saved = wp_update_attachment_metadata( $image->attachment_id, $meta );
				if ( ! $meta_saved ) {
					ewwwio_debug_message( 'failed to save meta' );
				}
			}
		}

		// Pull the next image.
		$next_image = new EWWW_Image( $attachment, 'media' );

		// When we finish all the sizes, we stop the loop so we can fire off any filters for plugins that might need to take action when an image is updated.
		// The call to wp_get_attachment_metadata() will be done in a separate AJAX request for better reliability, giving it the full request time to complete.
		if ( $attachment && (int) $attachment !== (int) $next_image->attachment_id ) {
			if ( defined( 'WP_CLI' ) && WP_CLI ) {
				remove_all_filters( 'as3cf_pre_update_attachment_metadata' );
				ewwwio_debug_message( 'saving attachment meta' );
				$meta = wp_get_attachment_metadata( $image->attachment_id );
				if ( ewww_image_optimizer_s3_uploads_enabled() ) {
					ewwwio_debug_message( 're-uploading to S3(_Uploads)' );
					ewww_image_optimizer_remote_push( $meta, $image->attachment_id );
				}
				if ( class_exists( 'Windows_Azure_Helper' ) && function_exists( 'windows_azure_storage_wp_generate_attachment_metadata' ) ) {
					$meta = windows_azure_storage_wp_generate_attachment_metadata( $meta, $image->attachment_id );
					if ( Windows_Azure_Helper::delete_local_file() && function_exists( 'windows_azure_storage_delete_local_files' ) ) {
						windows_azure_storage_delete_local_files( $meta, $image->attachment_id );
					}
				}
				wp_update_attachment_metadata( $image->attachment_id, $meta );
				do_action( 'ewww_image_optimizer_after_optimize_attachment', $image->attachment_id, $meta );
			} else {
				$batch_image_limit     = 1;
				$output['update_meta'] = (int) $attachment;
			}
		}

		// When an image (attachment) is done, pull the next attachment ID off the stack.
		if ( ( 'full' === $next_image->resize || empty( $next_image->resize ) ) && ! empty( $attachment ) && (int) $attachment !== (int) $next_image->attachment_id ) {
			ewwwio_debug_message( 'grabbing next attachment id' );
			ewww_image_optimizer_delete_queued_images( array( $attachment ) );
			if ( 1 === count( $attachments ) && 1 === (int) $batch_image_limit ) {
				$attachments = ewww_image_optimizer_get_queued_attachments( 'media', $batch_image_limit );
			} else {
				$attachment = (int) array_shift( $attachments ); // Pull the first image off the stack.
			}
			if ( ! empty( $attachments ) && is_array( $attachments ) ) {
				$attachment = (int) $attachments[0]; // Then grab the next one (if any are left).
			} else {
				$attachment = 0;
			}
			ewwwio_debug_message( "next id is $attachment" );
			$next_image = new EWWW_Image( $attachment, 'media' );
		}
		$image           = $next_image;
		$time_adjustment = $image->time_estimate();
	} // End while().

	ewwwio_debug_message( 'ending bulk loop for now' );
	// Calculate how much time has elapsed since we started.
	$elapsed = microtime( true ) - $started;
	// Output how much time has elapsed since we started.
	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		/* translators: %s: number of seconds */
		WP_CLI::line( sprintf( _n( 'Elapsed: %s second', 'Elapsed: %s seconds', $elapsed, 'ewww-image-optimizer' ), number_format_i18n( $elapsed, 2 ) ) );
		if ( ewww_image_optimizer_function_exists( 'sleep' ) ) {
			sleep( $delay );
		}
	}

	$output['add_to_total'] = (int) $add_to_total;

	if ( ! empty( $next_image->file ) ) {
		$next_file = esc_html( $next_image->file );
		// Generate the WP spinner image for display.
		if ( $next_file ) {
			/* translators: %s: image file name */
			$output['next_file'] = sprintf( esc_html__( 'Optimizing %s', 'ewww-image-optimizer' ), '<b>' . esc_html( $next_file ) . '</b>' );
		} else {
			$output['next_file'] = esc_html__( 'Optimizing', 'ewww-image-optimizer' );
		}
	} else {
		$output['done'] = 1;
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			delete_transient( 'ewww_image_optimizer_bulk_counter_measures' );
			delete_transient( 'ewww_image_optimizer_bulk_current_image' );
			return false;
		}
	}

	ewww_image_optimizer_debug_log();
	delete_transient( 'ewww_image_optimizer_bulk_counter_measures' );
	delete_transient( 'ewww_image_optimizer_bulk_current_image' );
	ewwwio_memory( __FUNCTION__ );
	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		return true;
	}
	ewwwio_ob_clean();
	die( wp_json_encode( $output ) );
}


/** Function ewww_image_optimizer_webp_cleanup() called by wp_ajax hooks: {'webp_cleanup'} **/
/** Parameters found in function ewww_image_optimizer_webp_cleanup(): {"request": ["ewww_wpnonce"]} **/
function ewww_image_optimizer_webp_cleanup() {
	$permissions = apply_filters( 'ewww_image_optimizer_admin_permissions', '' );
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-webp' ) || ! current_user_can( $permissions ) ) {
		ewwwio_ob_clean();
		die( esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) );
	}
	ewwwio_ob_clean();
	// and let the user know we are done.
	die( '<p><b>' . esc_html__( 'Finished', 'ewww-image-optimizer' ) . '</b></p>' );
}


/** Function ewww_image_optimizer_delete_webp_handler() called by wp_ajax hooks: {'bulk_aux_images_delete_webp'} **/
/** Parameters found in function ewww_image_optimizer_delete_webp_handler(): {"request": ["ewww_wpnonce"]} **/
function ewww_image_optimizer_delete_webp_handler() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	// Verify that an authorized user has called function.
	$permissions = apply_filters( 'ewww_image_optimizer_admin_permissions', '' );
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-tools' ) || ! current_user_can( $permissions ) ) {
		ewwwio_ob_clean();
		die( wp_json_encode( array( 'error' => esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) ) ) );
	}
	global $wpdb;
	$resume   = get_option( 'ewww_image_optimizer_webp_clean_position' );
	$position = is_array( $resume ) && ! empty( $resume['stage1'] ) ? (int) $resume['stage1'] : 0;
	if ( ! is_array( $resume ) ) {
		$resume = array();
	}

	$id = (int) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT ID FROM $wpdb->posts WHERE ID > %d AND post_type = 'attachment' AND (post_mime_type LIKE %s OR post_mime_type LIKE %s) ORDER BY ID LIMIT 1",
			(int) $position,
			'%image%',
			'%pdf%'
		)
	);
	if ( ! $id ) {
		die( wp_json_encode( array( 'finished' => 1 ) ) );
	}

	// Because some plugins might have loose filters (looking at you WPML).
	remove_all_filters( 'wp_delete_file' );

	$removed          = ewww_image_optimizer_delete_webp( $id );
	$resume['stage1'] = (int) $id;
	update_option( 'ewww_image_optimizer_webp_clean_position', $resume, false );

	$new_nonce = ewwwio_maybe_get_new_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-tools' );

	die(
		wp_json_encode(
			array(
				'completed' => 1,
				'removed'   => $removed,
				'new_nonce' => $new_nonce,
			)
		)
	);
}


/** Function dismiss_newsletter_signup_notice() called by wp_ajax hooks: {'ewww_dismiss_newsletter'} **/
/** No params detected :-/ **/


/** Function ewww_image_optimizer_ajax_delete_original() called by wp_ajax hooks: {'bulk_aux_images_delete_original'} **/
/** Parameters found in function ewww_image_optimizer_ajax_delete_original(): {"request": ["ewww_wpnonce"], "post": ["delete_originals_done", "attachment_id", "completed", "total"]} **/
function ewww_image_optimizer_ajax_delete_original() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	// Verify that an authorized user has called function.
	$permissions = apply_filters( 'ewww_image_optimizer_admin_permissions', '' );
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-tools' ) || ! current_user_can( $permissions ) ) {
		ewwwio_ob_clean();
		die( wp_json_encode( array( 'error' => esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) ) ) );
	}
	if ( ! empty( $_POST['delete_originals_done'] ) ) {
		delete_option( 'ewww_image_optimizer_delete_originals_resume' );
		die( wp_json_encode( array( 'done' => 1 ) ) );
	}
	if ( empty( $_POST['attachment_id'] ) ) {
		die( wp_json_encode( array( 'error' => esc_html__( 'Missing attachment ID number.', 'ewww-image-optimizer' ) ) ) );
	}

	// Because some plugins might have loose filters (looking at you WPML).
	remove_all_filters( 'wp_delete_file' );

	$count = 0;
	if ( ! empty( $_POST['completed'] ) ) {
		$count = (int) $_POST['completed'] + 1;
	}

	$total = 0;
	if ( ! empty( $_POST['total'] ) ) {
		$total = (int) $_POST['total'];
	}

	$deleted  = false;
	$id       = (int) $_POST['attachment_id'];
	$old_meta = wp_get_attachment_metadata( $id );
	$new_meta = ewwwio_remove_original_image( $id, $old_meta );
	if ( ewww_image_optimizer_iterable( $new_meta ) ) {
		$deleted_image = ewwwio_get_original_image_path( $id, '', $old_meta );
		wp_update_attachment_metadata( $id, $new_meta );
		/* translators: %s: filename of deleted image */
		$deleted = sprintf( esc_html__( 'Deleted %s', 'ewww-image-optimizer' ), esc_html( $deleted_image ) );
	}

	update_option( 'ewww_image_optimizer_delete_originals_resume', $id, false );

	/* translators: 1: number of images scanned so far, 2: total number of images to scan */
	$progress  = sprintf( esc_html__( '%1$s / %2$s images checked', 'ewww-image-optimizer' ), number_format_i18n( $count ), number_format_i18n( $total ) );
	$new_nonce = ewwwio_maybe_get_new_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-tools' );

	die(
		wp_json_encode(
			array(
				'progress'  => $progress,
				'deleted'   => $deleted,
				'new_nonce' => $new_nonce,
			)
		)
	);
}


/** Function ewww_ngg_bulk_init() called by wp_ajax hooks: {'bulk_ngg_init'} **/
/** Parameters found in function ewww_ngg_bulk_init(): {"request": ["ewww_wpnonce"]} **/
function ewww_ngg_bulk_init() {
			$permissions = apply_filters( 'ewww_image_optimizer_bulk_permissions', '' );
			$output      = array();
			if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-bulk' ) || ! current_user_can( $permissions ) ) {
				$output['error'] = esc_html__( 'Access denied.', 'ewww-image-optimizer' );
				ewwwio_ob_clean();
				wp_die( wp_json_encode( $output ) );
			}
			// Toggle the resume flag to indicate an operation is in progress.
			update_option( 'ewww_image_optimizer_bulk_ngg_resume', 'true' );
			// Get the list of attachments remaining from the db.
			$attachments = get_option( 'ewww_image_optimizer_bulk_ngg_attachments' );
			if ( ! is_array( $attachments ) && ! empty( $attachments ) ) {
				$attachments = unserialize( $attachments );
			}
			if ( ! is_array( $attachments ) ) {
				$output['error'] = esc_html__( 'Error retrieving list of images', 'ewww-image-optimizer' );
				ewwwio_ob_clean();
				wp_die( wp_json_encode( $output ) );
			}
			$id        = array_shift( $attachments );
			$file_name = $this->ewww_ngg_bulk_filename( $id );
			// Let the user know we are starting.
			if ( empty( $file_name ) ) {
				$output['results'] = '<p>' . esc_html__( 'Optimizing', 'ewww-image-optimizer' ) . '</p>';
			} else {
				/* translators: %s: image file name */
				$output['results'] = '<p>' . sprintf( esc_html__( 'Optimizing %s', 'ewww-image-optimizer' ), '<b>' . esc_html( $file_name ) . '</b>' ) . '</p>';
			}
			ewwwio_ob_clean();
			wp_die( wp_json_encode( $output ) );
		}


/** Function ewww_image_optimizer_exactdn_activate_site_ajax() called by wp_ajax hooks: {'ewww_exactdn_activate_site'} **/
/** Parameters found in function ewww_image_optimizer_exactdn_activate_site_ajax(): {"request": ["ewww_wpnonce", "blog_id"]} **/
function ewww_image_optimizer_exactdn_activate_site_ajax() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	if ( false === current_user_can( apply_filters( 'ewww_image_optimizer_admin_permissions', '' ) ) ) {
		// Display error message if insufficient permissions.
		ewwwio_ob_clean();
		wp_die( wp_json_encode( array( 'error' => esc_html__( 'Access denied.', 'ewww-image-optimizer' ) ) ) );
	}
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-settings' ) ) {
		die( wp_json_encode( array( 'error' => esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) ) ) );
	}
	if ( empty( $_REQUEST['blog_id'] ) ) {
		die( wp_json_encode( array( 'error' => esc_html__( 'Blog ID not provided.', 'ewww-image-optimizer' ) ) ) );
	}
	$blog_id = (int) $_REQUEST['blog_id'];
	if ( get_current_blog_id() !== $blog_id ) {
		switch_to_blog( $blog_id );
	}
	ewwwio_debug_message( "activating site $blog_id" );
	if ( get_option( 'ewww_image_optimizer_exactdn' ) ) {
		die( wp_json_encode( array( 'success' => esc_html__( 'Easy IO setup and verification is complete.', 'ewww-image-optimizer' ) ) ) );
	}
	update_option( 'ewww_image_optimizer_exactdn', true );
	global $exactdn;
	if ( ! class_exists( 'EWWW\ExactDN' ) ) {
		/**
		 * Page Parsing class for working with HTML content.
		 */
		require_once EWWW_IMAGE_OPTIMIZER_PLUGIN_PATH . 'classes/class-page-parser.php';
		/**
		 * ExactDN class for parsing image urls and rewriting them.
		 */
		require_once EWWW_IMAGE_OPTIMIZER_PLUGIN_PATH . 'classes/class-exactdn.php';
	} elseif ( is_object( $exactdn ) ) {
		unset( $GLOBALS['exactdn'] );
		$exactdn = new EWWW\ExactDN();
	}
	if ( $exactdn->get_exactdn_domain() ) {
		ewwwio_debug_message( 'activated site ' . $exactdn->content_url() . ' got domain ' . $exactdn->get_exactdn_domain() );
		die( wp_json_encode( array( 'success' => esc_html__( 'Easy IO setup and verification is complete.', 'ewww-image-optimizer' ) ) ) );
	}
	restore_current_blog();
	global $exactdn_activate_error;
	if ( empty( $exactdn_activate_error ) ) {
		$exactdn_activate_error = 'error unknown';
	}
	$error_message = sprintf(
		/* translators: 1: The blog URL 2: the error message/details */
		esc_html__( 'Could not activate Easy IO on %1$s: %2$s', 'ewww-image-optimizer' ),
		esc_url( get_home_url( $blog_id ) ),
		'<code>' . esc_html( $exactdn_activate_error ) . '</code>'
	);
	if ( 'as3cf_cname_active' === $exactdn_activate_error ) {
		$error_message = esc_html__( 'Easy IO cannot optimize your images while using a custom domain (CNAME) in WP Offload Media. Please disable the custom domain in the WP Offload Media settings.', 'ewww-image-optimizer' );
	}
	die(
		wp_json_encode(
			array(
				'error' => $error_message,
			)
		)
	);
}


/** Function ewww_image_optimizer_webp_unwrite() called by wp_ajax hooks: {'ewww_webp_unwrite'} **/
/** Parameters found in function ewww_image_optimizer_webp_unwrite(): {"request": ["ewww_wpnonce"]} **/
function ewww_image_optimizer_webp_unwrite() {
	ewwwio_ob_clean();
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	// Verify that the user is properly authorized.
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-settings' ) ) {
		wp_die( esc_html__( 'Access denied.', 'ewww-image-optimizer' ) );
	}
	if ( ! current_user_can( apply_filters( 'ewww_image_optimizer_admin_permissions', '' ) ) ) {
		wp_die( esc_html__( 'Access denied.', 'ewww-image-optimizer' ) );
	}
	if ( insert_with_markers( ewww_image_optimizer_htaccess_path(), 'EWWWIO', '' ) ) {
		esc_html_e( 'Removal successful', 'ewww-image-optimizer' );
	} else {
		esc_html_e( 'Removal failed', 'ewww-image-optimizer' );
	}
	die();
}


/** Function ewww_image_optimizer_ajax_get_attachment_status() called by wp_ajax hooks: {'ewww_manual_get_status'} **/
/** Parameters found in function ewww_image_optimizer_ajax_get_attachment_status(): {"request": ["ewww_attachment_ID", "action", "ewww_manual_nonce"]} **/
function ewww_image_optimizer_ajax_get_attachment_status() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	session_write_close();
	// Check permissions of current user.
	$permissions = apply_filters( 'ewww_image_optimizer_manual_permissions', '' );
	if ( ! current_user_can( $permissions ) ) {
		// Display error message if insufficient permissions.
		if ( ! wp_doing_ajax() ) {
			wp_die( esc_html__( 'You do not have permission to optimize images.', 'ewww-image-optimizer' ) );
		}
		ewwwio_ob_clean();
		wp_die( wp_json_encode( array( 'error' => esc_html__( 'You do not have permission to optimize images.', 'ewww-image-optimizer' ) ) ) );
	}
	// Make sure we didn't accidentally get to this page without an attachment to work on.
	if ( empty( $_REQUEST['ewww_attachment_ID'] ) || empty( $_REQUEST['action'] ) ) {
		// Display an error message since we don't have anything to work on.
		if ( ! wp_doing_ajax() ) {
			wp_die( esc_html__( 'Invalid request.', 'ewww-image-optimizer' ) );
		}
		ewwwio_ob_clean();
		wp_die( wp_json_encode( array( 'error' => esc_html__( 'Invalid request.', 'ewww-image-optimizer' ) ) ) );
	}
	if ( empty( $_REQUEST['ewww_manual_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_manual_nonce'] ), 'ewww-manual' ) ) {
		if ( ! wp_doing_ajax() ) {
			wp_die( esc_html__( 'Access denied.', 'ewww-image-optimizer' ) );
		}
		ewwwio_ob_clean();
		wp_die( wp_json_encode( array( 'error' => esc_html__( 'Access denied.', 'ewww-image-optimizer' ) ) ) );
	}
	// Store the attachment ID value.
	$attachment_id = (int) $_REQUEST['ewww_attachment_ID'];
	// Retrieve the existing attachment metadata.
	$meta     = wp_get_attachment_metadata( $attachment_id );
	$output   = ewww_image_optimizer_custom_column_capture( 'ewww-image-optimizer', $attachment_id, $meta );
	$basename = wp_basename( $meta['file'] );
	$pending  = ewww_image_optimizer_attachment_has_pending_sizes( $attachment_id );
	if ( ! $pending && ewww_image_optimizer_image_is_pending( $attachment_id, 'media-async' ) ) {
		$pending = 1;
	}
	ewwwio_ob_clean();
	wp_die(
		wp_json_encode(
			array(
				'output'   => $output,
				'basename' => $basename,
				'pending'  => (int) $pending,
			)
		)
	);
}


/** Function ewww_flag_bulk_cleanup() called by wp_ajax hooks: {'bulk_flag_cleanup'} **/
/** Parameters found in function ewww_flag_bulk_cleanup(): {"request": ["ewww_wpnonce"]} **/
function ewww_flag_bulk_cleanup() {
			ewwwio_debug_message( '<b>' . __METHOD__ . '()</b>' );
			$permissions = apply_filters( 'ewww_image_optimizer_bulk_permissions', '' );
			if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-bulk' ) || ! current_user_can( $permissions ) ) {
				ewwwio_ob_clean();
				wp_die( esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) );
			}
			// Reset the bulk flags in the db.
			update_option( 'ewww_image_optimizer_bulk_flag_resume', '' );
			update_option( 'ewww_image_optimizer_bulk_flag_attachments', '', false );
			ewwwio_ob_clean();
			// And let the user know we are done.
			wp_die( '<p><b>' . esc_html__( 'Finished Optimization!', 'ewww-image-optimizer' ) . '</b></p>' );
		}


/** Function ewww_image_optimizer_bulk_scan_init() called by wp_ajax hooks: {'ewww_bulk_scan_init'} **/
/** Parameters found in function ewww_image_optimizer_bulk_scan_init(): {"request": ["ewww_scan", "ewww_wpnonce", "ids", "ewww_aux_folders"]} **/
function ewww_image_optimizer_bulk_scan_init( $hook = '' ) {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );

	$permissions = apply_filters( 'ewww_image_optimizer_bulk_permissions', '' );
	if ( 'ewww-image-optimizer-cli' !== $hook && empty( $_REQUEST['ewww_scan'] ) ) {
		ewwwio_debug_message( 'bailing no cli' );
		ewwwio_ob_clean();
		die( wp_json_encode( array( 'error' => esc_html__( 'Access denied.', 'ewww-image-optimizer' ) ) ) );
	}
	if ( ! empty( $_REQUEST['ewww_scan'] ) && ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-bulk' ) || ! current_user_can( $permissions ) ) ) {
		ewwwio_debug_message( 'bailing no nonce' );
		ewwwio_ob_clean();
		die( wp_json_encode( array( 'error' => esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) ) ) );
	}
	global $wpdb;
	// Initialize the $attachments variable.
	$attachments = array();
	// Check to see if we are supposed to reset the bulk operation and verify we are authorized to do so.

	// Check the 'bulk resume' option.
	$resume   = get_option( 'ewww_image_optimizer_bulk_resume' );
	$scanning = get_option( 'ewww_image_optimizer_aux_resume' );
	// The aux_resume flag should never be anything other than 'scanning'. So if it is anything else, reset it to false.
	if ( 'scanning' !== $scanning && ! ewww_image_optimizer_get_option( 'ewww_image_optimizer_auto' ) ) {
		$scanning = false;
	}

	if ( ! $resume && ! $scanning ) {
		ewwwio_debug_message( 'not resuming/scanning, so clearing any pending images in both tables' );
		ewww_image_optimizer_delete_queue_images();
		ewww_image_optimizer_delete_pending();
	}

	ewww_image_optimizer_save_bulk_options( $_REQUEST );
	// The media_scan() and bulk_loop() functions will set a 10 minute transient to skip scheduled opt,
	// but this makes sure that a ongoing process is stopped immediately to avoid conflicts with the bulk operation.
	update_option( 'ewwwio_stop_scheduled_scan', true, false );

	$attachments = array();
	// See if we were given attachment IDs to work with via GET/POST.
	if ( ! empty( $_REQUEST['ids'] ) ) {
		ewww_image_optimizer_delete_pending();
		ewww_image_optimizer_delete_queue_images();
		set_transient( 'ewww_image_optimizer_skip_aux', true, 6 * HOUR_IN_SECONDS );
		$ids = sanitize_text_field( wp_unslash( $_REQUEST['ids'] ) );
		ewwwio_debug_message( "validating requested ids: $ids" );
		if ( is_numeric( $ids ) ) {
			$ids = array( (int) $_REQUEST['ids'] );
		} else {
			$ids = explode( ',', $ids );
			$ids = array_filter( array_map( 'absint', $ids ) );
		}

		// Then put it back together for the sql query.
		$ids = implode( ',', $ids );
		// Retrieve post IDs correlating to the IDs submitted to make sure they are all valid.
		$attachments = $wpdb->get_col( "SELECT ID FROM $wpdb->posts WHERE post_type = 'attachment' AND (post_mime_type LIKE '%%image%%' OR post_mime_type LIKE '%%pdf%%') AND ID IN ({$ids}) ORDER BY ID DESC" ); // phpcs:ignore WordPress.DB.PreparedSQL

		// Unset the 'bulk resume' option since we were given specific IDs to optimize.
		update_option( 'ewww_image_optimizer_bulk_resume', '' );
		update_option( 'ewww_image_optimizer_bulk_foreground', '' );
		// Check if there is a previous bulk operation to resume.
	} elseif ( $scanning || $resume ) {
		ewwwio_debug_message( 'scanning/resuming, nothing doing' );
	} else {
		if ( empty( $_REQUEST['ewww_aux_folders'] ) ) {
			set_transient( 'ewww_image_optimizer_skip_aux', true, 12 * HOUR_IN_SECONDS );
		} else {
			delete_transient( 'ewww_image_optimizer_skip_aux' );
		}
		ewwwio_debug_message( 'load em all up' );
		// Load up all the image attachments we can find.
		$attachments = $wpdb->get_col( "SELECT ID FROM $wpdb->posts WHERE post_type = 'attachment' AND (post_mime_type LIKE '%%image%%' OR post_mime_type LIKE '%%pdf%%') ORDER BY ID DESC" );
	} // End if().
	if ( ! empty( $attachments ) ) {
		// Store the attachment IDs we retrieved in the queue table so we can keep track of our progress in the database.
		ewwwio_debug_message( 'loading attachments into queue table' );
		ewww_image_optimizer_insert_unscanned( $attachments );
		$attachment_count = count( $attachments );
	} else {
		$attachment_count = ewww_image_optimizer_count_unscanned_attachments();
	}
	/* translators: %s: number of images */
	$message = sprintf( esc_html__( '%s media uploads left to scan', 'ewww-image-optimizer' ), number_format_i18n( $attachment_count ) );
	if ( empty( $attachment_count ) ) {
		$message = esc_html__( 'Searching additional folders for images to optimize...', 'ewww-image-optimizer' );
	}
	$pending_image_count = ewww_image_optimizer_aux_images_table_count_pending();
	// If there are no attachments to scan, and none in the queue table, and not even anything pending in the ewwwio_images table, then nuke the resumers.
	if ( empty( $attachment_count ) && ! ewww_image_optimizer_count_queued_attachments() && ! $pending_image_count ) {
		update_option( 'ewww_image_optimizer_bulk_resume', '' );
		update_option( 'ewww_image_optimizer_bulk_foreground', '' );
		update_option( 'ewww_image_optimizer_aux_resume', '' );
	}
	if ( 'ewww-image-optimizer-cli' === $hook ) {
		return;
	}
	wp_die(
		wp_json_encode(
			array(
				'message' => $message,
			)
		)
	);
}


/** Function dismiss_utf8_notice() called by wp_ajax hooks: {'ewww_dismiss_utf8_notice'} **/
/** No params detected :-/ **/


/** Function ajax_add_ignore_scaling_rule() called by wp_ajax hooks: {'ewww_add_ignore_scaling_rule'} **/
/** Parameters found in function ajax_add_ignore_scaling_rule(): {"request": ["ignore", "post_id", "request_uri"]} **/
function ajax_add_ignore_scaling_rule() {
		$this->debug_message( '<b>' . __METHOD__ . '()</b>' );
		\check_ajax_referer( 'ewww-image-detective', 'ewww_wpnonce' );
		if ( ! \current_user_can( \apply_filters( 'ewww_image_optimizer_admin_permissions', '' ) ) ) {
			\wp_send_json_error( \esc_html__( 'You do not have permission to perform this action.', 'ewww-image-optimizer' ) );
		}
		$this->ob_clean();
		if ( empty( $_REQUEST['ignore'] ) ) {
			\wp_send_json_error( \esc_html__( 'Cannot save empty image path.', 'ewww-image-optimizer' ) );
		}
		$exclusion = sanitize_text_field( wp_unslash( $_REQUEST['ignore'] ) );
		if ( empty( $_REQUEST['post_id'] ) && empty( $_REQUEST['request_uri'] ) ) {
			\wp_send_json_error( \esc_html__( 'Cannot add image to ignore list without a valid post ID or request URI.', 'ewww-image-optimizer' ) );
		}
		if ( ! empty( $_REQUEST['post_id'] ) ) {
			$post_identifier = (int) $_REQUEST['post_id'];
		} else {
			$post_identifier = \sanitize_text_field( \wp_unslash( $_REQUEST['request_uri'] ) );
		}
		$this->debug_message( "adding $exclusion to ignore list for post/page $post_identifier" );
		$this->update_page_scaling_detection_exclusions( $post_identifier, $exclusion );
		$this->ob_clean();
		\wp_send_json(
			array(
				'success' => true,
				'message' => \esc_html__( 'The image has been added to the ignore list.', 'ewww-image-optimizer' ),
			)
		);
	}


/** Function ewww_image_optimizer_bulk_initialize() called by wp_ajax hooks: {'ewww_bulk_init'} **/
/** Parameters found in function ewww_image_optimizer_bulk_initialize(): {"request": ["ewww_wpnonce"]} **/
function ewww_image_optimizer_bulk_initialize() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	// Verify that an authorized user has made the request.
	$permissions = apply_filters( 'ewww_image_optimizer_bulk_permissions', '' );
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-bulk' ) || ! current_user_can( $permissions ) ) {
		ewwwio_ob_clean();
		die( wp_json_encode( array( 'error' => esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) ) ) );
	}
	session_write_close();
	$output = array();

	// Update the 'bulk resume' option to show that an operation is in progress.
	update_option( 'ewww_image_optimizer_bulk_resume', 'true' );
	update_option( 'ewww_image_optimizer_bulk_foreground', 'true' );

	ewww_image_optimizer_save_bulk_options( $_REQUEST );
	// The media_scan() and bulk_loop() functions will set a 10 minute transient to skip scheduled opt,
	// but this makes sure that a ongoing process is stopped immediately to avoid conflicts with the bulk operation.
	update_option( 'ewwwio_stop_scheduled_scan', true, false );

	list( $attachment ) = ewww_image_optimizer_get_queued_attachments( 'media', 1 );
	ewwwio_debug_message( "first image: $attachment" );
	$first_image = new EWWW_Image( $attachment, 'media' );
	$file        = $first_image->file;
	// Let the user know that we are beginning.
	if ( $file ) {
		/* translators: %s: image file name */
		$output['results'] = sprintf( esc_html__( 'Optimizing %s', 'ewww-image-optimizer' ), '<b>' . esc_html( $file ) . '</b>' );
	} else {
		$output['results'] = esc_html__( 'Optimizing', 'ewww-image-optimizer' );
	}
	ewwwio_memory( __FUNCTION__ );
	ewwwio_ob_clean();
	die( wp_json_encode( $output ) );
}


/** Function ewww_image_optimizer_ajax_retest_background_optimization() called by wp_ajax hooks: {'ewww_retest_async'} **/
/** Parameters found in function ewww_image_optimizer_ajax_retest_background_optimization(): {"request": ["ewww_wpnonce"]} **/
function ewww_image_optimizer_ajax_retest_background_optimization() {
	$permissions = apply_filters( 'ewww_image_optimizer_admin_permissions', '' );
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-settings' ) || ! current_user_can( $permissions ) ) {
		ewwwio_ob_clean();
		die( wp_json_encode( array( 'error' => esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) ) ) );
	}
	session_write_close();
	$result = ewww_image_optimizer_send_async_test_request();
	ewwwio_ob_clean();
	$output = array();
	if ( is_wp_error( $result ) ) {
		$output['error']  = esc_html__( 'Disabled', 'ewww-image-optimizer' );
		$output['detail'] = $result->get_error_message();
		$output['body']   = $result->get_error_data();
	} else {
		$output['success'] = esc_html__( 'Enabled', 'ewww-image-optimizer' );
	}
	wp_send_json( $output );
}


/** Function ewww_image_optimizer_get_image_savings() called by wp_ajax hooks: {'ewww_get_image_savings'} **/
/** Parameters found in function ewww_image_optimizer_get_image_savings(): {"request": ["ewww_wpnonce", "network_savings"]} **/
function ewww_image_optimizer_get_image_savings() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	$permissions = apply_filters( 'ewww_image_optimizer_admin_permissions', '' );
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-settings' ) || ! current_user_can( $permissions ) ) {
		ewwwio_ob_clean();
		die( wp_json_encode( array( 'error' => esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) ) ) );
	}
	session_write_close();

	$total_sizes   = ewww_image_optimizer_savings( ! empty( $_REQUEST['network_savings'] ) );
	$total_savings = $total_sizes[1] - $total_sizes[0];

	$exactdn_savings = array(
		'bandwidth' => 0,
		'original'  => 0,
		'quota'     => 0,
		'savings'   => 0,
	);
	if ( empty( $_REQUEST['network_savings'] ) && class_exists( 'EWWW\ExactDN' ) && ewww_image_optimizer_get_option( 'ewww_image_optimizer_exactdn' ) ) {
		global $exactdn;
		if ( isset( $exactdn ) && is_object( $exactdn ) ) {
			$exactdn_savings = $exactdn->savings();
		}
	}

	if ( ewww_image_optimizer_get_option( 'ewww_image_optimizer_cloud_key' ) ) {
		$api_quota = ewww_image_optimizer_cloud_quota( true );
		if ( is_array( $api_quota ) && isset( $api_quota['consumed'] ) && empty( $api_quota['sites'] ) ) {
			if ( ! empty( $exactdn_savings['bandwidth'] ) && $exactdn_savings['bandwidth'] < $exactdn_savings['quota'] ) {
				$exactdn_savings['quota'] = $exactdn_savings['bandwidth'] * 1.25;
			}
		}
	} else {
		if ( ! empty( $exactdn_savings['bandwidth'] ) && $exactdn_savings['bandwidth'] < $exactdn_savings['quota'] ) {
			$exactdn_savings['quota'] = $exactdn_savings['bandwidth'] * 1.25;
		}
	}

	$site_id = ! empty( $exactdn_savings['site_id'] ) ? $exactdn_savings['site_id'] : 0;

	$output = array();

	ob_start();
	?>
	<?php if ( $total_savings > 0 ) : ?>
			<h3><?php esc_html_e( 'Local Compression Savings', 'ewww-image-optimizer' ); ?></h3>
			<div id='ewww-savings-container' class='ewww-bar-container'>
				<div id='ewww-savings-fill' data-score='<?php echo intval( $total_savings / $total_sizes[1] * 100 ); ?>' class='ewww-bar-fill'></div>
			</div>
			<div id='ewww-savings-flex' class='ewww-bar-caption'>
				<p class='ewww-bar-score'><?php echo esc_html( ewww_image_optimizer_size_format( $total_savings, 2 ) ); ?></p>
			</div>
	<?php endif; ?>
	<?php if ( ! empty( $exactdn_savings ) && ! empty( $exactdn_savings['original'] ) && ! empty( $exactdn_savings['savings'] ) ) : ?>
			<h3>
				<?php esc_html_e( 'Easy IO Savings', 'ewww-image-optimizer' ); ?>
				<?php ewwwio_help_link( 'https://docs.ewww.io/article/96-easy-io-is-it-working', '5f871dd2c9e77c0016217c4e' ); ?>
			</h3>
			<div id='easyio-savings-container' class='ewww-bar-container'>
				<div id='easyio-savings-fill' data-score='<?php echo esc_attr( intval( $exactdn_savings['savings'] / $exactdn_savings['original'] * 100 ) ); ?>' class='ewww-bar-fill'></div>
			</div>
			<div id='easyio-savings-flex' class='ewww-bar-caption'>
				<p class='ewww-bar-score'><?php echo esc_html( ewww_image_optimizer_size_format( $exactdn_savings['savings'], 2 ) ); ?></p>
			</div>
	<?php endif; ?>
	<?php if ( ! empty( $exactdn_savings['bandwidth'] ) ) : ?>
			<h3>
				<?php esc_html_e( 'Easy IO Bandwidth', 'ewww-image-optimizer' ); ?>
			</h3>
			<div id='easyio-bandwidth-container' class='ewww-bar-container'>
				<div id='easyio-bandwidth-fill' data-score='<?php echo esc_attr( min( 100, max( 1, intval( $exactdn_savings['bandwidth'] / $exactdn_savings['quota'] * 100 ) ) ) ); ?>' class='ewww-bar-fill'></div>
			</div>
			<div id='easyio-bandwidth-flex' class='ewww-bar-caption'>
				<p class='ewww-bar-score'><a href='#' id='easyio-show-stats' data-site-id='<?php echo (int) $site_id; ?>'><?php echo esc_html( ewww_image_optimizer_size_format( $exactdn_savings['bandwidth'], 2 ) ); ?></a></p>
			</div>
	<?php endif; ?>
	<?php

	$output['html'] = ob_get_clean();

	ewwwio_ob_clean();
	die( wp_json_encode( $output ) );
}


/** Function check_for_optout() called by wp_ajax hooks: {'ewww_opt_out_of_tracking'} **/
/** No params detected :-/ **/


/** Function ewww_image_optimizer_manual() called by wp_ajax hooks: {'ewww_manual_image_restore', 'ewww_manual_optimize', 'ewww_manual_restore'} **/
/** Parameters found in function ewww_image_optimizer_manual(): {"request": ["ewww_attachment_ID", "action", "ewww_manual_nonce", "ewww_force", "ewww_convert"]} **/
function ewww_image_optimizer_manual() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	global $ewww_convert;
	ewwwio()->defer = false;
	add_filter( 'ewww_image_optimizer_allowed_reopt', '__return_true' );
	// Check permissions of current user.
	$permissions = apply_filters( 'ewww_image_optimizer_manual_permissions', '' );
	if ( ! current_user_can( $permissions ) ) {
		// Display error message if insufficient permissions.
		if ( ! wp_doing_ajax() ) {
			wp_die( esc_html__( 'You do not have permission to optimize images.', 'ewww-image-optimizer' ) );
		}
		ewwwio_ob_clean();
		wp_die( wp_json_encode( array( 'error' => esc_html__( 'You do not have permission to optimize images.', 'ewww-image-optimizer' ) ) ) );
	}
	// Make sure we didn't accidentally get to this page without an attachment to work on.
	if ( empty( $_REQUEST['ewww_attachment_ID'] ) || empty( $_REQUEST['action'] ) ) {
		// Display an error message since we don't have anything to work on.
		if ( ! wp_doing_ajax() ) {
			wp_die( esc_html__( 'Invalid request.', 'ewww-image-optimizer' ) );
		}
		ewwwio_ob_clean();
		wp_die( wp_json_encode( array( 'error' => esc_html__( 'Invalid request.', 'ewww-image-optimizer' ) ) ) );
	}
	session_write_close();
	if ( empty( $_REQUEST['ewww_manual_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_manual_nonce'] ), 'ewww-manual' ) ) {
		if ( ! wp_doing_ajax() ) {
			wp_die( esc_html__( 'Access denied.', 'ewww-image-optimizer' ) );
		}
		ewwwio_ob_clean();
		wp_die( wp_json_encode( array( 'error' => esc_html__( 'Access denied.', 'ewww-image-optimizer' ) ) ) );
	}
	// Store the attachment ID value.
	$attachment_id  = (int) $_REQUEST['ewww_attachment_ID'];
	ewwwio()->force = ! empty( $_REQUEST['ewww_force'] ) ? true : false;
	$ewww_convert   = ! empty( $_REQUEST['ewww_convert'] ) ? true : false;
	// Retrieve the existing attachment metadata.
	$original_meta = wp_get_attachment_metadata( $attachment_id );
	// If the call was to optimize...
	$update_meta = true;
	if ( 'ewww_image_optimizer_manual_optimize' === $_REQUEST['action'] || 'ewww_manual_optimize' === $_REQUEST['action'] ) {
		ewwwio()->defer = true;
		if ( ewww_image_optimizer_test_background_opt() ) {
			ewwwio_debug_message( "attachment $attachment_id queued for async" );
			add_filter( 'http_headers_useragent', 'ewww_image_optimizer_cloud_useragent', PHP_INT_MAX );
			ewww_image_optimizer_add_attachment_to_queue( $attachment_id, false, $ewww_convert, ewwwio()->force );
			$new_meta    = $original_meta;
			$update_meta = false;
		} else {
			// Call the optimize from metadata function and store the resulting new metadata.
			$new_meta = ewww_image_optimizer_resize_from_meta_data( $original_meta, $attachment_id );
		}
	} elseif ( 'ewww_image_optimizer_manual_restore' === $_REQUEST['action'] || 'ewww_manual_restore' === $_REQUEST['action'] ) {
		$new_meta = ewww_image_optimizer_restore_from_meta_data( $original_meta, $attachment_id );
	} elseif ( 'ewww_image_optimizer_manual_image_restore' === $_REQUEST['action'] || 'ewww_manual_image_restore' === $_REQUEST['action'] ) {
		global $eio_backup;
		$new_meta = $eio_backup->restore_backup_from_meta_data( $attachment_id, 'media', $original_meta );
	} else {
		if ( ! wp_doing_ajax() ) {
			wp_die( esc_html__( 'Access denied.', 'ewww-image-optimizer' ) );
		}
		ewwwio_ob_clean();
		wp_die( wp_json_encode( array( 'error' => esc_html__( 'Access denied.', 'ewww-image-optimizer' ) ) ) );
	}
	$basename = '';
	if ( is_array( $new_meta ) && ! empty( $new_meta['file'] ) ) {
		$basename = wp_basename( $new_meta['file'] );
	}
	// Update the attachment metadata in the database.
	if ( $update_meta ) {
		$meta_saved = wp_update_attachment_metadata( $attachment_id, $new_meta );
		if ( ! $meta_saved ) {
			ewwwio_debug_message( 'failed to save meta, or no changes' );
		}
	}
	if ( 'exceeded' === get_transient( 'ewww_image_optimizer_cloud_status' ) ) {
		if ( ! wp_doing_ajax() ) {
			wp_die( wp_kses_post( ewww_image_optimizer_credits_exceeded() ) );
		}
		ewwwio_ob_clean();
		wp_die(
			wp_json_encode(
				array(
					'error' => ewww_image_optimizer_credits_exceeded(),
				)
			)
		);
	} elseif ( 'exceeded subkey' === get_transient( 'ewww_image_optimizer_cloud_status' ) ) {
		if ( ! wp_doing_ajax() ) {
			wp_die( esc_html__( 'Out of credits', 'ewww-image-optimizer' ) );
		}
		ewwwio_ob_clean();
		wp_die(
			wp_json_encode(
				array(
					'error' => esc_html__( 'Out of credits', 'ewww-image-optimizer' ),
				)
			)
		);
	} elseif ( 'exceeded quota' === get_transient( 'ewww_image_optimizer_cloud_status' ) ) {
		if ( ! wp_doing_ajax() ) {
			wp_die( wp_kses_post( ewww_image_optimizer_soft_quota_exceeded() ) );
		}
		ewwwio_ob_clean();
		wp_die(
			wp_json_encode(
				array(
					'error' => ewww_image_optimizer_soft_quota_exceeded(),
				)
			)
		);
	}
	$success = ewww_image_optimizer_custom_column_capture( 'ewww-image-optimizer', $attachment_id, $new_meta );
	ewww_image_optimizer_debug_log();
	// Do a redirect, if this was called via GET.
	if ( ! wp_doing_ajax() ) {
		// Store the referring webpage location.
		$sendback = wp_get_referer();
		// Send the user back where they came from.
		wp_safe_redirect( $sendback );
		die;
	}
	ewwwio_memory( __FUNCTION__ );
	ewwwio_ob_clean();
	wp_die(
		wp_json_encode(
			array(
				'success'  => $success,
				'basename' => $basename,
			)
		)
	);
}


/** Function ewww_image_optimizer_media_scan() called by wp_ajax hooks: {'ewww_bulk_scan'} **/
/** No function found :-/ **/


/** Function ewww_image_optimizer_exactdn_deregister_site_ajax() called by wp_ajax hooks: {'ewww_exactdn_deregister_site'} **/
/** Parameters found in function ewww_image_optimizer_exactdn_deregister_site_ajax(): {"request": ["ewww_wpnonce", "site_id"]} **/
function ewww_image_optimizer_exactdn_deregister_site_ajax() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	if ( false === current_user_can( apply_filters( 'ewww_image_optimizer_admin_permissions', '' ) ) ) {
		// Display error message if insufficient permissions.
		ewwwio_ob_clean();
		wp_die( wp_json_encode( array( 'error' => esc_html__( 'Access denied.', 'ewww-image-optimizer' ) ) ) );
	}
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-settings' ) ) {
		die( wp_json_encode( array( 'error' => esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) ) ) );
	}
	if ( empty( $_REQUEST['site_id'] ) ) {
		die( wp_json_encode( array( 'error' => esc_html__( 'Site ID unknown.', 'ewww-image-optimizer' ) ) ) );
	}
	$site_id = (int) $_REQUEST['site_id'];
	ewwwio_debug_message( "deregistering site $site_id" );

	$result = ewww_image_optimizer_deregister_site_post( $site_id );
	if ( is_wp_error( $result ) ) {
		$error_message = $result->get_error_message();
		ewwwio_debug_message( "de-registration failed: $error_message" );
		die(
			wp_json_encode(
				array(
					'error' => sprintf(
						/* translators: %s: an HTTP error message */
						esc_html__( 'Could not de-register site, HTTP error: %s', 'ewww-image-optimizer' ),
						$error_message
					),
				)
			)
		);
	} elseif ( ! empty( $result['body'] ) ) {
		$response = json_decode( $result['body'], true );
		if ( ! empty( $response['success'] ) ) {
			$response['success'] = esc_html__( 'Successfully removed site from Easy IO.', 'ewww-image-optimizer' );
		}
		die( wp_json_encode( $response ) );
	}
	die(
		wp_json_encode(
			array(
				'error' => esc_html__( 'Could not remove site from Easy IO: error unknown.', 'ewww-image-optimizer' ),
			)
		)
	);
}


/** Function ewww_image_optimizer_aux_images_count_converted() called by wp_ajax hooks: {'bulk_aux_images_count_converted'} **/
/** Parameters found in function ewww_image_optimizer_aux_images_count_converted(): {"request": ["ewww_wpnonce"]} **/
function ewww_image_optimizer_aux_images_count_converted() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	// Verify that an authorized user has called function.
	$permissions = apply_filters( 'ewww_image_optimizer_admin_permissions', '' );
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-tools' ) || ! current_user_can( $permissions ) ) {
		ewwwio_ob_clean();
		die( wp_json_encode( array( 'error' => esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) ) ) );
	}
	ewwwio_ob_clean();
	global $wpdb;
	$count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->ewwwio_images WHERE converted != ''" );
	die( wp_json_encode( array( 'total_converted' => $count ) ) );
}


/** Function ewww_image_optimizer_bulk_restore_handler() called by wp_ajax hooks: {'bulk_aux_images_restore_original'} **/
/** Parameters found in function ewww_image_optimizer_bulk_restore_handler(): {"request": ["ewww_wpnonce"]} **/
function ewww_image_optimizer_bulk_restore_handler() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );

	session_write_close();
	$permissions = apply_filters( 'ewww_image_optimizer_admin_permissions', '' );
	if ( ! current_user_can( $permissions ) ) {
		ewwwio_ob_clean();
		wp_die( wp_json_encode( array( 'error' => esc_html__( 'You do not have permission to optimize images.', 'ewww-image-optimizer' ) ) ) );
	}
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-tools' ) ) {
		ewwwio_ob_clean();
		wp_die( wp_json_encode( array( 'error' => esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) ) ) );
	}

	global $eio_backup;
	global $wpdb;

	$completed = 0;
	$position  = (int) get_option( 'ewww_image_optimizer_bulk_restore_position' );
	$per_page  = (int) apply_filters( 'ewww_image_optimizer_bulk_restore_batch_size', 20 );
	$started   = time();

	ewwwio_debug_message( "searching for $per_page records starting at $position" );
	$optimized_images = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->ewwwio_images WHERE id > %d AND pending = 0 AND image_size > 0 AND updates > 0 ORDER BY id LIMIT %d", $position, $per_page ), ARRAY_A );

	if ( empty( $optimized_images ) || ! is_countable( $optimized_images ) || 0 === count( $optimized_images ) ) {
		ewwwio_debug_message( 'no more images, all done!' );
		delete_option( 'ewww_image_optimizer_bulk_restore_position' );
		ewwwio_ob_clean();
		wp_die( wp_json_encode( array( 'finished' => 1 ) ) );
	}

	// Because some plugins might have loose filters (looking at you WPML).
	remove_all_filters( 'wp_delete_file' );

	$messages = '';
	foreach ( $optimized_images as $optimized_image ) {
		++$completed;
		ewwwio_debug_message( "submitting {$optimized_image['id']} to be restored" );
		$eio_backup->restore_file( $optimized_image );
		$error_message = $eio_backup->get_error();
		if ( $error_message ) {
			$messages .= esc_html( $error_message ) . '<br>';
		}
		update_option( 'ewww_image_optimizer_bulk_restore_position', $optimized_image['id'], false );
		if ( time() > $started + 20 ) {
			break;
		}
	} // End foreach().

	$new_nonce = ewwwio_maybe_get_new_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-tools' );

	ewwwio_ob_clean();
	wp_die(
		wp_json_encode(
			array(
				'completed' => $completed,
				'messages'  => $messages,
				'new_nonce' => $new_nonce,
			)
		)
	);
}


/** Function ewww_image_optimizer_aux_images_table_count() called by wp_ajax hooks: {'bulk_aux_images_table_count'} **/
/** Parameters found in function ewww_image_optimizer_aux_images_table_count(): {"request": ["ewww_inline", "ewww_wpnonce"]} **/
function ewww_image_optimizer_aux_images_table_count( $cached = false ) {
	if ( $cached ) {
		$count = get_transient( 'ewwwio_images_table_count' );
		if ( $count ) {
			return (int) $count;
		}
	}
	global $wpdb;
	$count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->ewwwio_images WHERE pending=0 AND image_size > 0 AND updates > 0" );
	// Verify that an authorized user has called function.
	$permissions = apply_filters( 'ewww_image_optimizer_bulk_permissions', '' );
	if ( ! empty( $_REQUEST['ewww_inline'] ) &&
		( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-tools' ) || ! current_user_can( $permissions ) )
	) {
		die();
	} elseif ( ! empty( $_REQUEST['ewww_inline'] ) ) {
		ewwwio_ob_clean();
		echo (int) $count;
		ewwwio_memory( __FUNCTION__ );
		die();
	}
	if ( $cached && $count ) {
		set_transient( 'ewwwio_images_table_count', (int) $count, HOUR_IN_SECONDS );
	}
	ewwwio_memory( __FUNCTION__ );
	return (int) $count;
}


/** Function ewww_ngg_bulk_cleanup() called by wp_ajax hooks: {'bulk_ngg_cleanup'} **/
/** Parameters found in function ewww_ngg_bulk_cleanup(): {"request": ["ewww_wpnonce"]} **/
function ewww_ngg_bulk_cleanup() {
			$permissions = apply_filters( 'ewww_image_optimizer_bulk_permissions', '' );
			if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-bulk' ) || ! current_user_can( $permissions ) ) {
				ewwwio_ob_clean();
				wp_die( esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) );
			}
			// Reset all the bulk options in the db...
			update_option( 'ewww_image_optimizer_bulk_ngg_resume', '' );
			update_option( 'ewww_image_optimizer_bulk_ngg_attachments', '', false );
			// and let the user know we are done.
			ewwwio_ob_clean();
			wp_die( '<p><b>' . esc_html__( 'Finished Optimization!', 'ewww-image-optimizer' ) . '</b></p>' );
		}


/** Function ewww_flag_bulk_loop() called by wp_ajax hooks: {'bulk_flag_loop'} **/
/** Parameters found in function ewww_flag_bulk_loop(): {"request": ["ewww_wpnonce"]} **/
function ewww_flag_bulk_loop() {
			ewwwio_debug_message( '<b>' . __METHOD__ . '()</b>' );
			ewwwio()->defer = false;
			$output         = array();
			$permissions    = apply_filters( 'ewww_image_optimizer_bulk_permissions', '' );
			if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-bulk' ) || ! current_user_can( $permissions ) ) {
				$output['error'] = esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' );
				ewwwio_ob_clean();
				wp_die( wp_json_encode( $output ) );
			}
			session_write_close();
			// Find out if our nonce is on it's last leg/tick.
			$tick = wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-bulk' );
			if ( 2 === $tick ) {
				$output['new_nonce'] = wp_create_nonce( 'ewww-image-optimizer-bulk' );
			} else {
				$output['new_nonce'] = '';
			}
			global $ewww_image;
			// Need this file to work with flag meta.
			require_once FLAG_ABSPATH . 'lib/meta.php';
			// Record the starting time for the current image (in microseconds).
			$started = microtime( true );
			// Retrieve the list of attachments left to work on.
			$attachments = get_option( 'ewww_image_optimizer_bulk_flag_attachments' );
			$id          = array_shift( $attachments );
			// Get the image meta for the current ID.
			$meta               = new flagMeta( $id );
			$file_path          = $meta->image->imagePath;
			$ewww_image         = new EWWW_Image( $id, 'flag', $file_path );
			$ewww_image->resize = 'full';
			// Optimize the full-size version.
			$fres = ewww_image_optimizer( $file_path, 3, false, false, true );
			if ( 'exceeded' === get_transient( 'ewww_image_optimizer_cloud_status' ) ) {
				$output['error'] = ewww_image_optimizer_credits_exceeded();
				ewwwio_ob_clean();
				wp_die( wp_json_encode( $output ) );
			}
			if ( 'exceeded quota' === get_transient( 'ewww_image_optimizer_cloud_status' ) ) {
				$output['error'] = ewww_image_optimizer_soft_quota_exceeded();
				ewwwio_ob_clean();
				wp_die( wp_json_encode( $output ) );
			}
			if ( 'exceeded subkey' === get_transient( 'ewww_image_optimizer_cloud_status' ) ) {
				$output['error'] = esc_html__( 'Out of credits', 'ewww-image-optimizer' );
				ewwwio_ob_clean();
				wp_die( wp_json_encode( $output ) );
			}
			// Let the user know what happened.
			$output['results'] = sprintf( '<p>' . esc_html__( 'Optimized image:', 'ewww-image-optimizer' ) . ' <strong>%s</strong><br>', esc_html( $meta->image->filename ) );
			/* Translators: %s: The compression results/savings */
			$output['results'] .= sprintf( esc_html__( 'Full size – %s', 'ewww-image-optimizer' ) . '<br>', esc_html( $fres[1] ) );
			if ( ! empty( $meta->image->meta_data['webview'] ) ) {
				// Determine path of the webview.
				$web_path           = $meta->image->webimagePath;
				$ewww_image         = new EWWW_Image( $id, 'flag', $web_path );
				$ewww_image->resize = 'webview';
				$wres               = ewww_image_optimizer( $web_path, 3, false, true );
				/* Translators: %s: The compression results/savings */
				$output['results'] .= sprintf( esc_html__( 'Optimized size – %s', 'ewww-image-optimizer' ) . '<br>', esc_html( $wres[1] ) );
			}
			$thumb_path         = $meta->image->thumbPath;
			$ewww_image         = new EWWW_Image( $id, 'flag', $thumb_path );
			$ewww_image->resize = 'thumbnail';
			// Optimize the thumbnail.
			$tres = ewww_image_optimizer( $thumb_path, 3, false, true );
			// And let the user know the results.
			/* Translators: %s: The compression results/savings */
			$output['results'] .= sprintf( esc_html__( 'Thumbnail – %s', 'ewww-image-optimizer' ) . '<br>', esc_html( $tres[1] ) );
			// Determine how much time the image took to process.
			$elapsed = microtime( true ) - $started;
			// And output it to the user.
			/* Translators: %s: number of seconds, localized */
			$output['results']  .= sprintf( esc_html( _n( 'Elapsed: %s second', 'Elapsed: %s seconds', $elapsed, 'ewww-image-optimizer' ) ) . '</p>', number_format_i18n( $elapsed, 2 ) );
			$output['completed'] = 1;
			// Send the list back to the db.
			update_option( 'ewww_image_optimizer_bulk_flag_attachments', $attachments, false );
			if ( ! empty( $attachments ) ) {
				$next_attachment = array_shift( $attachments );
				$next_file       = $this->ewww_flag_bulk_filename( $next_attachment );
				if ( $next_file ) {
					/* translators: %s: image file name */
					$output['next_file'] = '<p>' . sprintf( esc_html__( 'Optimizing %s', 'ewww-image-optimizer' ), '<b>' . esc_html( $next_file ) . '</b>' ) . '</p>';
				} else {
					$output['next_file'] = '<p>' . esc_html__( 'Optimizing', 'ewww-image-optimizer' ) . '</p>';
				}
			} else {
				$output['done'] = 1;
			}
			ewwwio_ob_clean();
			wp_die( wp_json_encode( $output ) );
		}


/** Function ewww_image_optimizer_bulk_async_get_status() called by wp_ajax hooks: {'ewww_bulk_async_get_status'} **/
/** Parameters found in function ewww_image_optimizer_bulk_async_get_status(): {"request": ["ewww_wpnonce"]} **/
function ewww_image_optimizer_bulk_async_get_status() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	// Verify that an authorized user has made the request.
	$permissions = apply_filters( 'ewww_image_optimizer_bulk_permissions', '' );
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-bulk' ) || ! current_user_can( $permissions ) ) {
		ewwwio_ob_clean();
		die( wp_json_encode( array( 'error' => esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) ) ) );
	}
	session_write_close();
	$output = array();

	if ( ewww_image_optimizer_get_option( 'ewww_image_optimizer_cloud_key' ) ) {
		if ( 'exceeded' === get_transient( 'ewww_image_optimizer_cloud_status' ) ) {
			$output['complete'] = ewww_image_optimizer_credits_exceeded();
			ewwwio_ob_clean();
			die( wp_json_encode( $output ) );
		}
		if ( 'exceeded quota' === get_transient( 'ewww_image_optimizer_cloud_status' ) ) {
			$output['complete'] = ewww_image_optimizer_soft_quota_exceeded();
			ewwwio_ob_clean();
			die( wp_json_encode( $output ) );
		}
		if ( 'exceeded subkey' === get_transient( 'ewww_image_optimizer_cloud_status' ) ) {
			$output['complete'] = esc_html__( 'Out of credits', 'ewww-image-optimizer' );
			ewwwio_ob_clean();
			die( wp_json_encode( $output ) );
		}
	}

	$media_queue_running = false;
	if ( ewwwio()->background_media->is_process_running() ) {
		$media_queue_running = true;
	}
	$image_queue_running = false;
	if ( ewwwio()->background_image->is_process_running() ) {
		$image_queue_running = true;
	}

	$media_queue_count = ewwwio()->background_media->count_queue();
	$image_queue_count = ewwwio()->background_image->count_queue();

	$output['images_queued'] = (int) $image_queue_count;

	if ( $media_queue_count && ! $media_queue_running ) {
		ewwwio_debug_message( 'rebooting media queue' );
		ewwwio()->background_media->dispatch();
	} elseif ( $image_queue_count && ! $image_queue_running && ! get_option( 'ewww_image_optimizer_pause_image_queue' ) ) {
		ewwwio_debug_message( 'rebooting image queue' );
		ewwwio()->background_image->dispatch();
	} elseif ( 'scanning' === get_option( 'ewww_image_optimizer_aux_resume' ) && ! $media_queue_count ) {
		ewwwio_debug_message( 'rebooting async image scanner' );
		if ( ! get_transient( 'ewww_image_optimizer_aux_lock' ) ) {
			ewwwio_debug_message( 'running scheduled optimization' );
			ewwwio()->async_scan->data(
				array(
					'ewww_scan' => 'scheduled',
				)
			)->dispatch();
		}
	}

	// Find out if our nonce is on it's last leg/tick.
	if ( ! empty( $_REQUEST['ewww_wpnonce'] ) ) {
		$output['new_nonce'] = ewwwio_maybe_get_new_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-bulk' );
	}

	if ( $media_queue_count ) {
		ewwwio_debug_message( "media items remaining (to scan): $media_queue_count" );
		/* translators: %s: number of images/uploads */
		$output['media_remaining'] = sprintf( esc_html__( '%s media uploads left to scan', 'ewww-image-optimizer' ), number_format_i18n( $media_queue_count ) );
	} elseif ( $image_queue_count ) {
		ewwwio_debug_message( "images remaining (to optimize): $image_queue_count" );
		/* translators: %s: number of images */
		$output['images_remaining'] = sprintf( esc_html__( '%s images left to optimize', 'ewww-image-optimizer' ), number_format_i18n( $image_queue_count ) );
	} elseif ( 'scanning' === get_option( 'ewww_image_optimizer_aux_resume' ) ) {
		ewwwio_debug_message( 'media scan complete, async scan should start shortly (or be running)' );
		// We output this as 'media_remaining' because the async scan hasn't run yet, and we don't want the autopoll to quit just yet.
		$output['media_remaining'] = esc_html__( 'Searching additional folders for images to optimize...', 'ewww-image-optimizer' );
	} elseif ( ! apply_filters( 'ewwwio_whitelabel', false ) ) {
		$output['complete'] = '<div><b>' . esc_html__( 'Finished', 'ewww-image-optimizer' ) . '</b> - ' .
		( ewww_image_optimizer_get_option( 'ewww_image_optimizer_cloud_key' ) ?
		'<a target="_blank" href="https://wordpress.org/support/plugin/ewww-image-optimizer/reviews/#new-post">' .
		esc_html__( 'Write a Review', 'ewww-image-optimizer' ) :
		esc_html__( 'Want more compression?', 'ewww-image-optimizer' ) . ' ' .
		'<a target="_blank" href="https://ewww.io/trial/">' .
		esc_html__( 'Get 5x more with a free trial', 'ewww-image-optimizer' )
		) .
		'</a></div>';
	} else {
		$output['complete'] = '<div><b>' . esc_html__( 'Finished', 'ewww-image-optimizer' ) . '</div></b>';
	}
	ewwwio_ob_clean();
	die( wp_json_encode( $output ) );
}


/** Function ewww_flag_manual() called by wp_ajax hooks: {'ewww_flag_manual'} **/
/** Parameters found in function ewww_flag_manual(): {"request": ["ewww_attachment_ID", "ewww_manual_nonce", "ewww_force"]} **/
function ewww_flag_manual() {
			ewwwio_debug_message( '<b>' . __METHOD__ . '()</b>' );
			// Make sure the current user has appropriate permissions.
			$permissions = apply_filters( 'ewww_image_optimizer_manual_permissions', '' );
			if ( false === current_user_can( $permissions ) ) {
				if ( ! wp_doing_ajax() ) {
					wp_die( esc_html__( 'You do not have permission to optimize images.', 'ewww-image-optimizer' ) );
				}
				ewwwio_ob_clean();
				wp_die( wp_json_encode( array( 'error' => esc_html__( 'You do not have permission to optimize images.', 'ewww-image-optimizer' ) ) ) );
			}
			// Make sure we have an attachment ID.
			if ( empty( $_REQUEST['ewww_attachment_ID'] ) ) {
				if ( ! wp_doing_ajax() ) {
					wp_die( esc_html__( 'No attachment ID was provided.', 'ewww-image-optimizer' ) );
				}
				ewwwio_ob_clean();
				wp_die( wp_json_encode( array( 'error' => esc_html__( 'No attachment ID was provided.', 'ewww-image-optimizer' ) ) ) );
			}
			$id = intval( $_REQUEST['ewww_attachment_ID'] );
			if ( empty( $_REQUEST['ewww_manual_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_manual_nonce'] ), "ewww-manual-$id" ) ) {
				if ( ! wp_doing_ajax() ) {
					wp_die( esc_html__( 'Access denied.', 'ewww-image-optimizer' ) );
				}
				ewwwio_ob_clean();
				wp_die( wp_json_encode( array( 'error' => esc_html__( 'Access denied.', 'ewww-image-optimizer' ) ) ) );
			}
			global $ewww_image;
			ewwwio()->force = ! empty( $_REQUEST['ewww_force'] ) ? true : false;
			if ( ! class_exists( 'flagMeta' ) ) {
				require_once FLAG_ABSPATH . 'lib/meta.php';
			}
			// Retrieve the metadata for the image ID.
			$meta = new flagMeta( $id );
			// Determine the path of the image.
			$file_path          = $meta->image->imagePath;
			$ewww_image         = new EWWW_Image( $id, 'flag', $file_path );
			$ewww_image->resize = 'full';
			// Optimize the full size.
			$res = ewww_image_optimizer( $file_path, 3, false, false, true );
			if ( ! empty( $meta->image->meta_data['webview'] ) ) {
				// Determine path of the webview.
				$web_path           = $meta->image->webimagePath;
				$ewww_image         = new EWWW_Image( $id, 'flag', $web_path );
				$ewww_image->resize = 'webview';
				$wres               = ewww_image_optimizer( $web_path, 3, false, true );
			}
			// Determine the path of the thumbnail.
			$thumb_path         = $meta->image->thumbPath;
			$ewww_image         = new EWWW_Image( $id, 'flag', $thumb_path );
			$ewww_image->resize = 'thumbnail';
			// Optimize the thumbnail.
			$tres = ewww_image_optimizer( $thumb_path, 3, false, true );
			if ( ! wp_doing_ajax() ) {
				// Get the referring page...
				$sendback = wp_get_referer();
				// Send the user back where they came from.
				wp_safe_redirect( $sendback );
				die;
			}
			$success = $this->ewww_manage_image_custom_column_capture( $id );
			ewwwio_ob_clean();
			wp_die( wp_json_encode( array( 'success' => $success ) ) );
		}


/** Function ewww_image_optimizer_aux_images_table() called by wp_ajax hooks: {'bulk_aux_images_table'} **/
/** Parameters found in function ewww_image_optimizer_aux_images_table(): {"request": ["ewww_wpnonce"], "post": ["ewww_offset", "ewww_search", "ewww_pending", "ewww_skew", "ewww_size_sort"]} **/
function ewww_image_optimizer_aux_images_table() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	// Verify that an authorized user has called function.
	$permissions = apply_filters( 'ewww_image_optimizer_bulk_permissions', '' );
	if (
		empty( $_REQUEST['ewww_wpnonce'] ) ||
		(
			! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-tools' ) &&
			! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-bulk' )
		) ||
		! current_user_can( $permissions )
	) {
		ewwwio_ob_clean();
		die( wp_json_encode( array( 'error' => esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) ) ) );
	}
	global $wpdb;
	$per_page = 50;
	$offset   = empty( $_POST['ewww_offset'] ) ? 0 : $per_page * abs( (int) $_POST['ewww_offset'] );
	$search   = empty( $_POST['ewww_search'] ) ? '' : sanitize_text_field( wp_unslash( $_POST['ewww_search'] ) );
	$pending  = empty( $_POST['ewww_pending'] ) ? 0 : 1;
	if ( ! empty( $_POST['ewww_skew'] ) ) {
		$skew = (int) $_POST['ewww_skew'];
		if ( $skew > 0 ) {
			$offset = $offset - $per_page + $skew;
		}
		if ( $offset < 0 ) {
			$offset = 0;
		}
	}

	$output = array();

	$output['show_pending_button'] = false;
	if ( ! $pending ) {
		$output['show_pending_button'] = ewww_image_optimizer_aux_images_table_count_pending() > 0;
	}

	$size_sort_class = '';
	if ( $pending ) {
		$sort_column     = 'id';
		$sort_direction  = 'DESC';
		$attachment_sort = true;
		if ( ! empty( $_POST['ewww_size_sort'] ) && 'asc' === $_POST['ewww_size_sort'] ) {
			$sort_column     = 'orig_size';
			$sort_direction  = 'ASC';
			$size_sort_class = 'ewww-size-asc';
			$attachment_sort = false;
		} elseif ( ! empty( $_POST['ewww_size_sort'] ) && 'desc' === $_POST['ewww_size_sort'] ) {
			$sort_column     = 'orig_size';
			$sort_direction  = 'DESC';
			$size_sort_class = 'ewww-size-desc';
			$attachment_sort = false;
		}
		if ( ! empty( $search ) ) {
			if ( 'ASC' === $sort_direction ) {
				$already_optimized = $wpdb->get_results(
					$wpdb->prepare(
						'SELECT path,orig_size,image_size,id,backup,attachment_id,gallery,resize_error,webp_size,webp_error,updates,UNIX_TIMESTAMP(updated) AS updated FROM %i WHERE pending=1 AND path LIKE %s ORDER BY %i ASC LIMIT %d,%d',
						$wpdb->ewwwio_images,
						'%' . $wpdb->esc_like( $search ) . '%',
						$sort_column,
						$offset,
						$per_page
					),
					ARRAY_A
				);
			} elseif ( ! $attachment_sort ) {
				$already_optimized = $wpdb->get_results(
					$wpdb->prepare(
						'SELECT path,orig_size,image_size,id,backup,attachment_id,gallery,resize_error,webp_size,webp_error,updates,UNIX_TIMESTAMP(updated) AS updated FROM %i WHERE pending=1 AND path LIKE %s ORDER BY %i DESC LIMIT %d,%d',
						$wpdb->ewwwio_images,
						'%' . $wpdb->esc_like( $search ) . '%',
						$sort_column,
						$offset,
						$per_page
					),
					ARRAY_A
				);
			} else {
				$already_optimized = $wpdb->get_results(
					$wpdb->prepare(
						'SELECT path,orig_size,image_size,id,backup,attachment_id,gallery,resize_error,webp_size,webp_error,updates,UNIX_TIMESTAMP(updated) AS updated FROM %i WHERE pending=1 AND path LIKE %s ORDER BY attachment_id DESC, %i DESC LIMIT %d,%d',
						$wpdb->ewwwio_images,
						'%' . $wpdb->esc_like( $search ) . '%',
						$sort_column,
						$offset,
						$per_page
					),
					ARRAY_A
				);
			}
			$search_count = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(*) FROM $wpdb->ewwwio_images WHERE pending=1 AND path LIKE %s",
					'%' . $wpdb->esc_like( $search ) . '%'
				)
			);
			if ( $search_count < $per_page ) {
				/* translators: %d: number of image records found */
				$output['search_result'] = sprintf( esc_html__( '%d items found', 'ewww-image-optimizer' ), count( $already_optimized ) );
			} else {
				/* translators: 1: number of image records displayed, 2: number of total records found */
				$output['search_result'] = sprintf( esc_html__( '%1$d items displayed of %2$s records found', 'ewww-image-optimizer' ), count( $already_optimized ), number_format_i18n( $search_count ) );
			}
			$total = ceil( $search_count / $per_page );
		} else {
			if ( 'ASC' === $sort_direction ) {
				$already_optimized = $wpdb->get_results(
					$wpdb->prepare(
						'SELECT path,orig_size,image_size,id,backup,attachment_id,gallery,resize_error,webp_size,webp_error,updates,UNIX_TIMESTAMP(updated) AS updated FROM %i WHERE pending=1 ORDER BY %i ASC LIMIT %d,%d',
						$wpdb->ewwwio_images,
						$sort_column,
						$offset,
						$per_page
					),
					ARRAY_A
				);
			} elseif ( ! $attachment_sort ) {
				$already_optimized = $wpdb->get_results(
					$wpdb->prepare(
						'SELECT path,orig_size,image_size,id,backup,attachment_id,gallery,resize_error,webp_size,webp_error,updates,UNIX_TIMESTAMP(updated) AS updated FROM %i WHERE pending=1 ORDER BY %i DESC LIMIT %d,%d',
						$wpdb->ewwwio_images,
						$sort_column,
						$offset,
						$per_page
					),
					ARRAY_A
				);
			} else {
				$already_optimized = $wpdb->get_results(
					$wpdb->prepare(
						'SELECT path,orig_size,image_size,id,backup,attachment_id,gallery,resize_error,webp_size,webp_error,updates,UNIX_TIMESTAMP(updated) AS updated FROM %i WHERE pending=1 ORDER BY attachment_id DESC, %i DESC LIMIT %d,%d',
						$wpdb->ewwwio_images,
						$sort_column,
						$offset,
						$per_page
					),
					ARRAY_A
				);
			}
			$search_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->ewwwio_images WHERE pending=1" );
			$total        = ceil( $search_count / $per_page );
			/* translators: %d: number of image records found */
			$output['search_result'] = sprintf( esc_html__( '%d items displayed', 'ewww-image-optimizer' ), count( $already_optimized ) );
		}
	} elseif ( ! empty( $search ) ) {
		$already_optimized = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT path,orig_size,image_size,id,backup,attachment_id,gallery,resize_error,webp_size,webp_error,updates,UNIX_TIMESTAMP(updated) AS updated FROM %i WHERE pending=0 AND image_size > 0 AND path LIKE %s ORDER BY id DESC LIMIT %d,%d',
				$wpdb->ewwwio_images,
				'%' . $wpdb->esc_like( $search ) . '%',
				$offset,
				$per_page
			),
			ARRAY_A
		);
		$search_count      = $wpdb->get_var(
			$wpdb->prepare(
				'SELECT COUNT(*) FROM %i WHERE pending=0 AND image_size > 0 AND path LIKE %s',
				$wpdb->ewwwio_images,
				'%' . $wpdb->esc_like( $search ) . '%'
			)
		);
		if ( $search_count < $per_page ) {
			/* translators: %d: number of image records found */
			$output['search_result'] = sprintf( esc_html__( '%d items found', 'ewww-image-optimizer' ), count( $already_optimized ) );
		} else {
			/* translators: 1: number of image records displayed, 2: number of total records found */
			$output['search_result'] = sprintf( esc_html__( '%1$d items displayed of %2$s records found', 'ewww-image-optimizer' ), count( $already_optimized ), number_format_i18n( $search_count ) );
		}
		$total = ceil( $search_count / $per_page );
	} else {
		$already_optimized = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT path,orig_size,image_size,id,backup,attachment_id,gallery,resize_error,webp_size,webp_error,updates,UNIX_TIMESTAMP(updated) AS updated FROM %i WHERE pending=0 AND image_size > 0 ORDER BY id DESC LIMIT %d,%d',
				$wpdb->ewwwio_images,
				$offset,
				$per_page
			),
			ARRAY_A
		);
		$search_count      = $wpdb->get_var(
			$wpdb->prepare(
				'SELECT COUNT(*) FROM %i WHERE pending=0 AND image_size > 0',
				$wpdb->ewwwio_images
			)
		);
		$total             = ceil( $search_count / $per_page );
		if ( empty( $output['search_result'] ) ) {
			/* translators: %d: number of image records found */
			$output['search_result'] = sprintf( esc_html__( '%d items displayed', 'ewww-image-optimizer' ), count( $already_optimized ) );
		}
	}

	$output['pagination'] = sprintf(
		/* translators: 1: current page in list of images 2: total pages for list of images */
		esc_html__( 'page %1$s of %2$s', 'ewww-image-optimizer' ),
		'<span class="current-page">' . ( (int) $_POST['ewww_offset'] + 1 ) . '</span>',
		'<span class="total-pages">' . esc_html( number_format_i18n( $total ) ) . '</span>'
	);

	$output['search_count'] = count( $already_optimized );
	$output['total_images'] = $search_count;
	$output['total_pages']  = $total;

	$output['total_images_text'] = sprintf(
		/* translators: %d: number of images */
		esc_html__( '%s total images', 'ewww-image-optimizer' ),
		'<span class="total-images">' . number_format_i18n( $search_count ) . '</span>'
	);

	$output['table'] = '<table class="wp-list-table widefat media" cellspacing="0"><thead><tr><th>&nbsp;</th>' .
		'<th>' . esc_html__( 'Filename', 'ewww-image-optimizer' ) . '</th>' .
		'<th style="width:120px;">' . esc_html__( 'Image Type', 'ewww-image-optimizer' ) . '</th>' .
		'<th style="width:120px;">' . esc_html__( 'Last Optimized', 'ewww-image-optimizer' ) . '</th>';
	if ( $pending ) {
		$output['table'] .= '<th class="' . esc_attr( $size_sort_class ) . '"><a class="ewww-sort-size">' .
			esc_html__( 'Image Size', 'ewww-image-optimizer' ) .
			'<span class="ewww-sort-grid"><span class="ewww-sort-asc dashicons dashicons-arrow-up"></span><span class="ewww-sort-desc dashicons dashicons-arrow-down"></span></span>' .
			'</a></th></tr></thead>';
	} else {
		$output['table'] .= '<th>' . esc_html__( 'Results', 'ewww-image-optimizer' ) . '</th></tr></thead>';
	}

	$output['table'] .= '<tbody>';

	if ( empty( $already_optimized ) ) {
		$output['pagination'] = sprintf(
			/* translators: 1: current page in list of images 2: total pages for list of images */
			esc_html__( 'page %1$d of %2$s', 'ewww-image-optimizer' ),
			1,
			'<span class="total-pages">1</span>'
		);
		if ( $pending ) {
			$output['table'] .= '<tr class="ewww-no-images"><td colspan="5">' . esc_html__( 'No images in queue.', 'ewww-image-optimizer' ) . '</td></tr>';
		} else {
			$output['table'] .= '<tr class="ewww-no-images"><td colspan="5">' . esc_html__( 'No images optimized!', 'ewww-image-optimizer' ) . '</td></tr>';
		}
	} else {
		$alternate = true;
		foreach ( $already_optimized as $optimized_image ) {
			$optimized_image['pending'] = $pending;
			$output['table']           .= ewww_image_optimizer_get_image_table_row( $optimized_image, $alternate );
			$alternate                  = ! $alternate;
		} // End foreach().
	}

	$output['table'] .= '</tbody></table>';
	die( wp_json_encode( $output ) );
}


/** Function ewww_image_optimizer_webp_loop() called by wp_ajax hooks: {'webp_loop'} **/
/** Parameters found in function ewww_image_optimizer_webp_loop(): {"request": ["ewww_wpnonce", "webp_images"]} **/
function ewww_image_optimizer_webp_loop() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	$permissions = apply_filters( 'ewww_image_optimizer_admin_permissions', '' );
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-webp' ) || ! current_user_can( $permissions ) ) {
		ewwwio_ob_clean();
		die( wp_json_encode( array( 'error' => esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) ) ) );
	}
	if ( empty( $_REQUEST['webp_images'] ) || ! is_array( $_REQUEST['webp_images'] ) ) {
		ewwwio_ob_clean();
		die( wp_json_encode( array( 'error' => esc_html__( 'No images to migrate.', 'ewww-image-optimizer' ) ) ) );
	}
	// Retrieve the time when the migration starts.
	$started = microtime( true );
	if ( ewww_image_optimizer_stl_check() ) {
		set_time_limit( 0 );
	}
	ewwwio_debug_message( 'renaming images now' );
	$images_processed = 0;
	$images_renamed   = 0;
	$output           = '';
	$images           = array_map( 'sanitize_text_field', wp_unslash( $_REQUEST['webp_images'] ) );
	while ( $images ) {
		++$images_processed;
		ewwwio_debug_message( "processed $images_processed images so far" );
		$image = array_pop( $images );
		if ( ! ewwwio_is_file( $image ) ) {
			ewwwio_debug_message( "skipping $image because it is not a file, or not in a permitted folder" );
			continue;
		}
		$replace_base        = '';
		$skip                = true;
		$naming_mode         = ewww_image_optimizer_get_option( 'ewww_image_optimizer_webp_naming_mode', 'append' );
		$extensionless       = ewwwio()->remove_from_end( $image, '.webp' );
		$info                = pathinfo( $extensionless );
		$ext                 = strtolower( $info['extension'] ?? '' );
		$original_extensions = array( 'png', 'jpg', 'jpeg', 'gif' );
		$is_real_ext         = in_array( $ext, $original_extensions, true );
		if ( 'replace' === $naming_mode ) {
			if ( $is_real_ext && ewwwio_is_file( $extensionless ) ) {
				$replace_base = $extensionless;
				$skip         = false;
			}
		} elseif ( 'append' === $naming_mode ) {
			if ( $is_real_ext ) {
				continue;
			}
			foreach ( $original_extensions as $img_ext ) {
				if ( ewwwio_is_file( $extensionless . '.' . $img_ext ) || ewwwio_is_file( $extensionless . '.' . strtoupper( $img_ext ) ) ) {
					if ( ! empty( $replace_base ) ) {
						$skip = true;
						break;
					}
					$replace_base = $extensionless . '.' . $img_ext;
					$skip         = false;
				}
			}
		}
		if ( $skip ) {
			if ( $replace_base ) {
				ewwwio_debug_message( "multiple replacement options for $image, not renaming" );
			} else {
				ewwwio_debug_message( "no match found for $image, strange..." );
			}
			/* translators: %s: a webp file */
			$output .= sprintf( esc_html__( 'Skipped %s, could not determine original image path', 'ewww-image-optimizer' ), esc_html( $image ) ) . '<br>';
		} else {
			$new_webp_path = ewww_image_optimizer_get_webp_path( $replace_base );
			if ( is_file( $new_webp_path ) ) {
				ewwwio_debug_message( "$new_webp_path already exists, deleting $image" );
				ewwwio_delete_file( $image );
				/* translators: 1: a webp file 2: another webp file */
				$output .= sprintf( esc_html__( '%1$s already exists, removed %2$s', 'ewww-image-optimizer' ), esc_html( $new_webp_path ), esc_html( $image ) ) . '<br>';
				continue;
			}
			++$images_renamed;
			ewwwio_debug_message( "renaming $image with match of $replace_base to $new_webp_path" );
			ewwwio()->rename( $image, $new_webp_path );
		}
	} // End while().
	if ( $images_renamed ) {
		/* translators: %d: number of images */
		$output .= sprintf( esc_html__( 'Renamed %d WebP images', 'ewww-image-optimizer' ), (int) $images_renamed ) . '<br>';
	}

	// Calculate how much time has elapsed since we started.
	$elapsed = microtime( true ) - $started;
	ewwwio_debug_message( "took $elapsed seconds this time around" );
	// Store the updated list of images back in the database.
	echo wp_json_encode(
		array(
			'output' => $output,
		)
	);
	die();
}


/** Function ewww_image_optimizer_aux_images_webp_clean_handler() called by wp_ajax hooks: {'bulk_aux_images_webp_clean'} **/
/** Parameters found in function ewww_image_optimizer_aux_images_webp_clean_handler(): {"request": ["ewww_wpnonce"]} **/
function ewww_image_optimizer_aux_images_webp_clean_handler() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	// Verify that an authorized user has called function.
	$permissions = apply_filters( 'ewww_image_optimizer_admin_permissions', '' );
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-tools' ) || ! current_user_can( $permissions ) ) {
		ewwwio_ob_clean();
		die( wp_json_encode( array( 'error' => esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) ) ) );
	}
	global $wpdb;
	$completed = 0;
	$per_page  = 50;
	$resume    = get_option( 'ewww_image_optimizer_webp_clean_position' );
	$position  = is_array( $resume ) && ! empty( $resume['stage2'] ) ? (int) $resume['stage2'] : 0;
	if ( ! is_array( $resume ) ) {
		$resume = array();
	}

	ewwwio_debug_message( "searching for $per_page records starting at $position" );
	$optimized_images = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->ewwwio_images WHERE id > %d AND pending = 0 AND image_size > 0 AND updates > 0 ORDER BY id LIMIT %d", $position, $per_page ), ARRAY_A );

	if ( empty( $optimized_images ) || ! is_countable( $optimized_images ) || 0 === count( $optimized_images ) ) {
		delete_option( 'ewww_image_optimizer_webp_clean_position' );
		die( wp_json_encode( array( 'finished' => 1 ) ) );
	}

	// Because some plugins might have loose filters (looking at you WPML).
	remove_all_filters( 'wp_delete_file' );

	$removed = 0;
	foreach ( $optimized_images as $optimized_image ) {
		++$completed;
		$removed += ewww_image_optimizer_aux_images_webp_clean( $optimized_image );
	}

	$resume['stage2'] = $optimized_image['id'];
	update_option( 'ewww_image_optimizer_webp_clean_position', $resume, false );

	$new_nonce = ewwwio_maybe_get_new_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-tools' );

	die(
		wp_json_encode(
			array(
				'completed' => $completed,
				'removed'   => $removed,
				'new_nonce' => $new_nonce,
			)
		)
	);
}


/** Function dismiss_media_notice() called by wp_ajax hooks: {'ewww_dismiss_media_notice'} **/
/** No params detected :-/ **/


/** Function ewww_image_optimizer_get_all_attachments() called by wp_ajax hooks: {'ewwwio_get_all_attachments'} **/
/** Parameters found in function ewww_image_optimizer_get_all_attachments(): {"request": ["ewww_wpnonce"]} **/
function ewww_image_optimizer_get_all_attachments() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	$permissions = apply_filters( 'ewww_image_optimizer_admin_permissions', '' );
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-tools' ) || ! current_user_can( $permissions ) ) {
		ewwwio_ob_clean();
		die( wp_json_encode( array( 'error' => esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) ) ) );
	}
	$start_id = get_option( 'ewww_image_optimizer_delete_originals_resume', 0 );
	global $wpdb;
	$attachments = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT ID FROM $wpdb->posts WHERE ID > %d AND post_type = 'attachment' AND (post_mime_type LIKE %s OR post_mime_type LIKE %s) ORDER BY ID DESC",
			(int) $start_id,
			'%image%',
			'%pdf%'
		)
	);
	if ( empty( $attachments ) || ! is_countable( $attachments ) || 0 === count( $attachments ) ) {
		delete_option( 'ewww_image_optimizer_delete_originals_resume' );
		die( wp_json_encode( array( 'error' => esc_html__( 'No media uploads found.', 'ewww-image-optimizer' ) ) ) );
	}
	ewwwio_debug_message( gettype( $attachments ) );
	die( wp_json_encode( $attachments ) );
}


/** Function ewww_ngg_image_restore() called by wp_ajax hooks: {'ewww_ngg_image_restore'} **/
/** Parameters found in function ewww_ngg_image_restore(): {"request": ["ewww_attachment_ID", "ewww_manual_nonce"]} **/
function ewww_ngg_image_restore() {
			ewwwio_debug_message( '<b>' . __METHOD__ . '()</b>' );
			// Check permission of current user.
			$permissions = apply_filters( 'ewww_image_optimizer_manual_permissions', '' );
			if ( false === current_user_can( $permissions ) ) {
				if ( ! wp_doing_ajax() ) {
					wp_die( esc_html__( 'You do not have permission to optimize images.', 'ewww-image-optimizer' ) );
				}
				ewwwio_ob_clean();
				wp_die( wp_json_encode( array( 'error' => esc_html__( 'You do not have permission to optimize images.', 'ewww-image-optimizer' ) ) ) );
			}
			// Make sure function wasn't called without an attachment to work with.
			if ( false === isset( $_REQUEST['ewww_attachment_ID'] ) ) {
				if ( ! wp_doing_ajax() ) {
					wp_die( esc_html__( 'No attachment ID was provided.', 'ewww-image-optimizer' ) );
				}
				ewwwio_ob_clean();
				wp_die( wp_json_encode( array( 'error' => esc_html__( 'No attachment ID was provided.', 'ewww-image-optimizer' ) ) ) );
			}
			// Sanitize the attachment $id.
			$id = intval( $_REQUEST['ewww_attachment_ID'] );
			if ( empty( $_REQUEST['ewww_manual_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_manual_nonce'] ), "ewww-manual-$id" ) ) {
				if ( ! wp_doing_ajax() ) {
						wp_die( esc_html__( 'Access denied.', 'ewww-image-optimizer' ) );
				}
				ewwwio_ob_clean();
				wp_die( wp_json_encode( array( 'error' => esc_html__( 'Access denied.', 'ewww-image-optimizer' ) ) ) );
			}
			// Get an image object.
			$image = $this->get_ngg_image( $id );
			global $eio_backup;
			$eio_backup->restore_backup_from_meta_data( $image->pid, 'nextgen' );
			$success = $this->ewww_manage_image_custom_column( '', $image );
			ewwwio_ob_clean();
			wp_die( wp_json_encode( array( 'success' => $success ) ) );
		}


/** Function ewww_image_optimizer_webp_attachment_count() called by wp_ajax hooks: {'ewwwio_webp_attachment_count'} **/
/** Parameters found in function ewww_image_optimizer_webp_attachment_count(): {"request": ["ewww_wpnonce"]} **/
function ewww_image_optimizer_webp_attachment_count() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	$permissions = apply_filters( 'ewww_image_optimizer_admin_permissions', '' );
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-tools' ) || ! current_user_can( $permissions ) ) {
		ewwwio_ob_clean();
		die( wp_json_encode( array( 'error' => esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) ) ) );
	}
	$resume   = get_option( 'ewww_image_optimizer_webp_clean_position' );
	$start_id = is_array( $resume ) && ! empty( $resume['stage1'] ) ? (int) $resume['stage1'] : 0;

	global $wpdb;
	$total_attachments = (int) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT count(ID) FROM $wpdb->posts WHERE ID > %d AND post_type = 'attachment' AND (post_mime_type LIKE %s OR post_mime_type LIKE %s)",
			(int) $start_id,
			'%image%',
			'%pdf%'
		)
	);
	die( wp_json_encode( array( 'total' => (int) $total_attachments ) ) );
}


/** Function ewww_image_optimizer_webp_rewrite() called by wp_ajax hooks: {'ewww_webp_rewrite'} **/
/** Parameters found in function ewww_image_optimizer_webp_rewrite(): {"request": ["ewww_wpnonce"]} **/
function ewww_image_optimizer_webp_rewrite() {
	ewwwio_ob_clean();
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	// Verify that the user is properly authorized.
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-settings' ) ) {
		die( esc_html__( 'Access denied.', 'ewww-image-optimizer' ) );
	}
	if ( ! current_user_can( apply_filters( 'ewww_image_optimizer_admin_permissions', '' ) ) ) {
		die( esc_html__( 'Access denied.', 'ewww-image-optimizer' ) );
	}
	$ewww_rules = ewww_image_optimizer_webp_rewrite_verify();
	if ( $ewww_rules ) {
		if ( insert_with_markers( ewww_image_optimizer_htaccess_path(), 'EWWWIO', $ewww_rules ) && ! ewww_image_optimizer_webp_rewrite_verify() ) {
			$webp_mime_error = ewww_image_optimizer_test_webp_mime_error();
			if ( empty( $webp_mime_error ) ) {
				die( esc_html__( 'Insertion successful', 'ewww-image-optimizer' ) );
			}
			die(
				sprintf(
					/* translators: %s: an error message from the WebP self-test */
					esc_html__( 'Insertion successful, but self-test failed: %s', 'ewww-image-optimizer' ),
					esc_html( $webp_mime_error )
				)
			);
		}
		die( esc_html__( 'Insertion failed', 'ewww-image-optimizer' ) );
	}
	die( esc_html__( 'Insertion aborted', 'ewww-image-optimizer' ) );
}


/** Function ewww_flag_bulk_init() called by wp_ajax hooks: {'bulk_flag_init'} **/
/** Parameters found in function ewww_flag_bulk_init(): {"request": ["ewww_wpnonce"]} **/
function ewww_flag_bulk_init() {
			ewwwio_debug_message( '<b>' . __METHOD__ . '()</b>' );
			$permissions = apply_filters( 'ewww_image_optimizer_bulk_permissions', '' );
			if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-bulk' ) || ! current_user_can( $permissions ) ) {
				ewwwio_ob_clean();
				wp_die( esc_html__( 'Access denied.', 'ewww-image-optimizer' ) );
			}
			$output = array();
			// Set the resume flag to indicate the bulk operation is in progress.
			update_option( 'ewww_image_optimizer_bulk_flag_resume', 'true' );
			// Retrieve the list of attachments left to work on.
			$attachments = get_option( 'ewww_image_optimizer_bulk_flag_attachments' );
			if ( ! is_array( $attachments ) && ! empty( $attachments ) ) {
				$attachments = unserialize( $attachments );
			}
			if ( ! is_array( $attachments ) ) {
				$output['error'] = esc_html__( 'Error retrieving list of images', 'ewww-image-optimizer' );
				ewwwio_ob_clean();
				wp_die( wp_json_encode( $output ) );
			}
			$id        = array_shift( $attachments );
			$file_name = $this->ewww_flag_bulk_filename( $id );
			// Output the initial message letting the user know we are starting.
			if ( empty( $file_name ) ) {
				$output['results'] = '<p>' . esc_html__( 'Optimizing', 'ewww-image-optimizer' ) . '</p>';
			} else {
				/* translators: %s: image file name */
				$output['results'] = '<p>' . sprintf( esc_html__( 'Optimizing %s', 'ewww-image-optimizer' ), '<b>' . esc_html( $file_name ) . '</b>' ) . '</p>';
			}
			ewwwio_ob_clean();
			wp_die( wp_json_encode( $output ) );
		}


/** Function dismiss_exec_notice() called by wp_ajax hooks: {'ewww_dismiss_exec_notice'} **/
/** No params detected :-/ **/


/** Function ewww_image_optimizer_webp_initialize() called by wp_ajax hooks: {'webp_init'} **/
/** Parameters found in function ewww_image_optimizer_webp_initialize(): {"request": ["ewww_wpnonce"]} **/
function ewww_image_optimizer_webp_initialize() {
	// Verify that an authorized user has started the migration.
	$permissions = apply_filters( 'ewww_image_optimizer_admin_permissions', '' );
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-webp' ) || ! current_user_can( $permissions ) ) {
		ewwwio_ob_clean();
		die( esc_html__( 'Access denied.', 'ewww-image-optimizer' ) );
	}
	// Generate the WP spinner image for display.
	$loading_image = plugins_url( '/images/wpspin.gif', __FILE__ );
	// Let the user know that we are beginning.
	ewwwio_ob_clean();
	die( '<p>' . esc_html__( 'Scanning', 'ewww-image-optimizer' ) . '&nbsp;<img src="' . esc_url( $loading_image ) . '" /></p>' );
}


/** Function ewww_image_optimizer_cloud_key_verify_ajax() called by wp_ajax hooks: {'ewww_cloud_key_verify'} **/
/** Parameters found in function ewww_image_optimizer_cloud_key_verify_ajax(): {"request": ["ewww_wpnonce"], "post": ["compress_api_key"]} **/
function ewww_image_optimizer_cloud_key_verify_ajax() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	if ( false === current_user_can( apply_filters( 'ewww_image_optimizer_admin_permissions', '' ) ) ) {
		// Display error message if insufficient permissions.
		ewwwio_ob_clean();
		wp_die( wp_json_encode( array( 'error' => esc_html__( 'Access denied.', 'ewww-image-optimizer' ) ) ) );
	}
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-settings' ) ) {
		die( wp_json_encode( array( 'error' => esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) ) ) );
	}
	if ( empty( $_POST['compress_api_key'] ) ) {
		die( wp_json_encode( array( 'error' => esc_html__( 'Please enter your API key and try again.', 'ewww-image-optimizer' ) ) ) );
	}
	$api_key = trim( ewww_image_optimizer_cloud_key_sanitize( wp_unslash( $_POST['compress_api_key'] ) ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$url     = 'http://api.exactlywww.net/verify/';
	if ( wp_http_supports( array( 'ssl' ) ) ) {
		$url = set_url_scheme( $url, 'https' );
	}
	$result = ewww_image_optimizer_cloud_post_key( $url, $api_key );
	if ( is_wp_error( $result ) ) {
		$url           = set_url_scheme( $url, 'http' );
		$error_message = $result->get_error_message();
		ewwwio_debug_message( "verification failed: $error_message" );
		$result = ewww_image_optimizer_cloud_post_key( $url, $api_key );
	}
	if ( is_wp_error( $result ) ) {
		$error_message = $result->get_error_message();
		ewwwio_debug_message( "verification failed via $url: $error_message" );
		die(
			wp_json_encode(
				array(
					'error' => sprintf(
						/* translators: %s: an HTTP error message */
						esc_html__( 'Could not validate API key, HTTP error: %s', 'ewww-image-optimizer' ),
						$error_message
					),
				)
			)
		);
	} elseif ( ! empty( $result['body'] ) && preg_match( '/(great|exceeded)/', $result['body'] ) ) {
		$verified = $result['body'];
		if ( preg_match( '/banned/', $verified ) ) {
			die( wp_json_encode( array( 'error' => esc_html__( 'Domain or IP address blocked, contact support for assistance.', 'ewww-image-optimizer' ) ) ) );
		}
		if ( preg_match( '/exceeded/', $verified ) ) {
			die( wp_json_encode( array( 'error' => esc_html__( 'No credits remaining for API key.', 'ewww-image-optimizer' ) ) ) );
		}
		ewwwio_debug_message( "verification success via: $url" );
		delete_option( 'ewww_image_optimizer_cloud_key_invalid' );
		ewww_image_optimizer_set_option( 'ewww_image_optimizer_cloud_key', $api_key );
		set_transient( 'ewww_image_optimizer_cloud_status', $verified, HOUR_IN_SECONDS );
		if ( ewww_image_optimizer_get_option( 'ewww_image_optimizer_jpg_level' ) < 20 && ewww_image_optimizer_get_option( 'ewww_image_optimizer_png_level' ) < 20 && ewww_image_optimizer_get_option( 'ewww_image_optimizer_gif_level' ) < 20 && ! ewww_image_optimizer_get_option( 'ewww_image_optimizer_pdf_level' ) && ! ewww_image_optimizer_get_option( 'ewww_image_optimizer_webp_level' ) ) {
			ewww_image_optimizer_cloud_enable();
		}
		ewwwio_debug_message( "verification body contents: {$result['body']}" );
		die( wp_json_encode( array( 'success' => esc_html__( 'Successfully validated API key, happy optimizing!', 'ewww-image-optimizer' ) ) ) );
	} else {
		ewwwio_debug_message( "verification failed via: $url" );
		if ( ewww_image_optimizer_get_option( 'ewww_image_optimizer_debug' ) && ewww_image_optimizer_function_exists( 'print_r' ) ) {
			ewwwio_debug_message( print_r( $result, true ) );
		}
		die( wp_json_encode( array( 'error' => esc_html__( 'Could not validate API key, please copy and paste your key to ensure it is correct.', 'ewww-image-optimizer' ) ) ) );
	}
}


/** Function ewww_image_optimizer_bulk_update_meta() called by wp_ajax hooks: {'ewww_bulk_update_meta'} **/
/** Parameters found in function ewww_image_optimizer_bulk_update_meta(): {"request": ["ewww_wpnonce", "attachment_id"]} **/
function ewww_image_optimizer_bulk_update_meta() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	// Verify that an authorized user has started the optimizer.
	$permissions = apply_filters( 'ewww_image_optimizer_bulk_permissions', '' );
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-bulk' ) || ! current_user_can( $permissions ) ) {
		ewwwio_ob_clean();
		die( wp_json_encode( array( 'error' => esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) ) ) );
	}
	if ( empty( $_REQUEST['attachment_id'] ) ) {
		ewwwio_ob_clean();
		die( wp_json_encode( array( 'success' => 0 ) ) );
	}
	$attachment_id = (int) $_REQUEST['attachment_id'];
	ewww_image_optimizer_post_optimize_attachment( $attachment_id );
	ewwwio_ob_clean();
	die( wp_json_encode( array( 'success' => 1 ) ) );
}


/** Function ewww_image_optimizer_exactdn_get_site_stats_ajax() called by wp_ajax hooks: {'exactdn_get_site_stats'} **/
/** Parameters found in function ewww_image_optimizer_exactdn_get_site_stats_ajax(): {"request": ["ewww_wpnonce", "site_id"], "post": ["require_extra"]} **/
function ewww_image_optimizer_exactdn_get_site_stats_ajax() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	if ( false === current_user_can( apply_filters( 'ewww_image_optimizer_admin_permissions', '' ) ) ) {
		// Display error message if insufficient permissions.
		ewwwio_ob_clean();
		wp_die( wp_json_encode( array( 'error' => esc_html__( 'Access denied.', 'ewww-image-optimizer' ) ) ) );
	}
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-settings' ) ) {
		die( wp_json_encode( array( 'error' => esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) ) ) );
	}
	if ( empty( $_REQUEST['site_id'] ) ) {
		die( wp_json_encode( array( 'error' => esc_html__( 'Site ID unknown.', 'ewww-image-optimizer' ) ) ) );
	}
	$site_id = (int) $_REQUEST['site_id'];
	$url     = "https://stats.exactlywww.net/stats/easyio-zone.php?site_id=$site_id&format=json";
	if ( ! empty( $_POST['require_extra'] ) ) {
		$url .= '&require_extra=1';
	}

	$result = wp_remote_get(
		$url,
		array(
			'timeout' => 55,
		)
	);
	if ( ! is_wp_error( $result ) && ! empty( $result['body'] ) ) {
		$response = json_decode( $result['body'], true );
		die( wp_json_encode( $response ) );
	}
	if ( is_wp_error( $result ) ) {
		die( wp_json_encode( array( 'error' => $result->get_error_message() ) ) );
	}
}


/** Function ewww_image_optimizer_bulk_async_init() called by wp_ajax hooks: {'ewww_bulk_async_init'} **/
/** Parameters found in function ewww_image_optimizer_bulk_async_init(): {"request": ["ewww_wpnonce", "ewww_aux_folders"]} **/
function ewww_image_optimizer_bulk_async_init() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	// Verify that an authorized user has made the request.
	$permissions = apply_filters( 'ewww_image_optimizer_bulk_permissions', '' );
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-bulk' ) || ! current_user_can( $permissions ) ) {
		ewwwio_ob_clean();
		die( wp_json_encode( array( 'error' => esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) ) ) );
	}
	session_write_close();
	$output = array();

	if ( ewww_image_optimizer_get_option( 'ewww_image_optimizer_cloud_key' ) ) {
		$verify_cloud = ewww_image_optimizer_cloud_verify( ewww_image_optimizer_get_option( 'ewww_image_optimizer_cloud_key' ), false );
		if ( 'exceeded' === $verify_cloud ) {
			$output['error'] = ewww_image_optimizer_credits_exceeded();
			ewwwio_ob_clean();
			die( wp_json_encode( $output ) );
		}
		if ( 'exceeded quota' === $verify_cloud ) {
			$output['error'] = ewww_image_optimizer_soft_quota_exceeded();
			ewwwio_ob_clean();
			die( wp_json_encode( $output ) );
		}
		if ( 'exceeded subkey' === $verify_cloud ) {
			$output['error'] = esc_html__( 'Out of credits', 'ewww-image-optimizer' );
			ewwwio_ob_clean();
			die( wp_json_encode( $output ) );
		}
	}

	// Update the 'bulk resume' option to show that an operation is in progress.
	update_option( 'ewww_image_optimizer_bulk_resume', 'scanning' );
	delete_option( 'ewwwio_stop_scheduled_scan' );

	ewww_image_optimizer_save_bulk_options( $_REQUEST );

	if ( empty( $_REQUEST['ewww_aux_folders'] ) ) {
		set_transient( 'ewww_image_optimizer_skip_aux', true, 12 * HOUR_IN_SECONDS );
	} else {
		delete_transient( 'ewww_image_optimizer_skip_aux' );
	}

	global $wpdb;
	$attachments = $wpdb->get_col( "SELECT ID FROM $wpdb->posts WHERE post_type = 'attachment' AND (post_mime_type LIKE '%%image%%' OR post_mime_type LIKE '%%pdf%%') ORDER BY ID DESC" );
	if ( ! empty( $attachments ) ) {
		ewwwio_debug_message( 'loading attachments into queue table' );
		ewww_image_optimizer_insert_unscanned( $attachments, 'media-async' );
		$attachment_count = count( $attachments );
		ewwwio()->background_media->dispatch();
		/* translators: %s: number of images */
		$output['media_remaining'] = sprintf( esc_html__( '%s media uploads left to scan', 'ewww-image-optimizer' ), number_format_i18n( $attachment_count ) );
	} else {
		$output['media_remaining'] = esc_html__( 'Searching additional folders for images to optimize...', 'ewww-image-optimizer' );
		ewwwio_debug_message( 'starting async scan' );
		ewwwio()->async_scan->data(
			array(
				'ewww_scan' => 'scheduled',
			)
		)->dispatch();
		update_option( 'ewww_image_optimizer_bulk_resume', '' );
	}

	ewwwio_ob_clean();
	die( wp_json_encode( $output ) );
}


/** Function ewww_ngg_bulk_loop() called by wp_ajax hooks: {'bulk_ngg_loop'} **/
/** Parameters found in function ewww_ngg_bulk_loop(): {"request": ["ewww_wpnonce"]} **/
function ewww_ngg_bulk_loop() {
			ewwwio()->defer = false;
			$output         = array();
			$permissions    = apply_filters( 'ewww_image_optimizer_bulk_permissions', '' );
			if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-bulk' ) || ! current_user_can( $permissions ) ) {
				$outupt['error'] = esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' );
				ewwwio_ob_clean();
				wp_die( wp_json_encode( $output ) );
			}
			session_write_close();
			// Find out if our nonce is on it's last leg/tick.
			$tick = wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-bulk' );
			if ( 2 === $tick ) {
				$output['new_nonce'] = wp_create_nonce( 'ewww-image-optimizer-bulk' );
			} else {
				$output['new_nonce'] = '';
			}
			// Find out what time we started, in microseconds.
			$started = microtime( true );
			// Get the list of attachments remaining from the db.
			$attachments         = get_option( 'ewww_image_optimizer_bulk_ngg_attachments' );
			$id                  = array_shift( $attachments );
			list( $fres, $tres ) = $this->ewww_ngg_optimize( $id );
			if ( 'exceeded' === get_transient( 'ewww_image_optimizer_cloud_status' ) ) {
				$output['error'] = ewww_image_optimizer_credits_exceeded();
				ewwwio_ob_clean();
				wp_die( wp_json_encode( $output ) );
			}
			if ( 'exceeded quota' === get_transient( 'ewww_image_optimizer_cloud_status' ) ) {
				$output['error'] = ewww_image_optimizer_soft_quota_exceeded();
				ewwwio_ob_clean();
				wp_die( wp_json_encode( $output ) );
			}
			if ( 'exceeded subkey' === get_transient( 'ewww_image_optimizer_cloud_status' ) ) {
				$output['error'] = esc_html__( 'Out of credits', 'ewww-image-optimizer' );
				ewwwio_ob_clean();
				wp_die( wp_json_encode( $output ) );
			}
			// Output the results of the optimization.
			if ( $fres[0] ) {
				$output['results'] = sprintf( '<p>' . esc_html__( 'Optimized image:', 'ewww-image-optimizer' ) . ' <strong>%s</strong><br>', esc_html( $fres[0] ) );
			}
			/* Translators: %s: The compression results/savings */
			$output['results'] .= sprintf( esc_html__( 'Full size - %s', 'ewww-image-optimizer' ) . '<br>', esc_html( $fres[1] ) );
			// Output the results of the thumb optimization.
			/* Translators: %s: The compression results/savings */
			$output['results'] .= sprintf( esc_html__( 'Thumbnail - %s', 'ewww-image-optimizer' ) . '<br>', esc_html( $tres[1] ) );
			// Output how much time we spent.
			$elapsed = microtime( true ) - $started;
			/* Translators: %s: localized number of seconds */
			$output['results']  .= sprintf( esc_html( _n( 'Elapsed: %s second', 'Elapsed: %s seconds', $elapsed, 'ewww-image-optimizer' ) ) . '</p>', number_format_i18n( $elapsed, 2 ) );
			$output['completed'] = 1;
			// Store the list back in the db.
			update_option( 'ewww_image_optimizer_bulk_ngg_attachments', $attachments, false );
			if ( ! empty( $attachments ) ) {
				$next_attachment = array_shift( $attachments );
				$next_file       = $this->ewww_ngg_bulk_filename( $next_attachment );
				if ( $next_file ) {
					/* translators: %s: image file name */
					$output['next_file'] = '<p>' . sprintf( esc_html__( 'Optimizing %s', 'ewww-image-optimizer' ), '<b>' . esc_html( $next_file ) . '</b>' ) . '</p>';
				} else {
					$output['next_file'] = '<p>' . esc_html__( 'Optimizing', 'ewww-image-optimizer' ) . '</p>';
				}
			} else {
				$output['done'] = 1;
			}
			ewwwio_ob_clean();
			wp_die( wp_json_encode( $output ) );
		}


/** Function check_for_optin() called by wp_ajax hooks: {'ewww_opt_into_tracking'} **/
/** No params detected :-/ **/


/** Function ewww_image_optimizer_aux_images_clear_all() called by wp_ajax hooks: {'bulk_aux_images_table_clear'} **/
/** Parameters found in function ewww_image_optimizer_aux_images_clear_all(): {"request": ["ewww_wpnonce"]} **/
function ewww_image_optimizer_aux_images_clear_all() {
	// Verify that an authorized user has called function.
	$permissions = apply_filters( 'ewww_image_optimizer_admin_permissions', '' );
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-tools' ) || ! current_user_can( $permissions ) ) {
		ewwwio_ob_clean();
		die( esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) );
	}
	ewwwio_ob_clean();
	global $wpdb;
	if ( $wpdb->query( "TRUNCATE $wpdb->ewwwio_images" ) ) {
		die( esc_html__( 'All records have been removed from the optimization history.', 'ewww-image-optimizer' ) );
	}
	ewwwio_memory( __FUNCTION__ );
	die();
}


/** Function ewww_image_optimizer_aux_images_converted_clean() called by wp_ajax hooks: {'bulk_aux_images_converted_clean'} **/
/** Parameters found in function ewww_image_optimizer_aux_images_converted_clean(): {"request": ["ewww_wpnonce"]} **/
function ewww_image_optimizer_aux_images_converted_clean() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	// Verify that an authorized user has called function.
	$permissions = apply_filters( 'ewww_image_optimizer_admin_permissions', '' );
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-tools' ) || ! current_user_can( $permissions ) ) {
		ewwwio_ob_clean();
		die( wp_json_encode( array( 'error' => esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) ) ) );
	}
	global $wpdb;
	$completed = 0;
	$per_page  = 50;

	$converted_images = $wpdb->get_results( $wpdb->prepare( "SELECT path,converted,id FROM $wpdb->ewwwio_images WHERE converted != '' ORDER BY id DESC LIMIT %d", $per_page ), ARRAY_A );

	if ( empty( $converted_images ) || ! is_countable( $converted_images ) || 0 === count( $converted_images ) ) {
		die( wp_json_encode( array( 'finished' => 1 ) ) );
	}

	// Because some plugins might have loose filters (looking at you WPML).
	remove_all_filters( 'wp_delete_file' );

	$messages = '';
	foreach ( $converted_images as $optimized_image ) {
		++$completed;
		$file = ewww_image_optimizer_absolutize_path( $optimized_image['converted'] );
		ewwwio_debug_message( "$file was converted, checking if it still exists" );
		if ( ! ewww_image_optimizer_stream_wrapped( $file ) && ewwwio_is_file( $file ) ) {
			ewwwio_debug_message( "removing original: $file" );
			if ( ewwwio_delete_file( $file ) ) {
				ewwwio_debug_message( "removed $file" );
				/* translators: %s: file name */
				$messages .= sprintf( esc_html__( 'Deleted %s', 'ewww-image-optimizer' ), esc_html( $file ) ) . '<br>';
			} else {
				/* translators: %s: file name */
				die( wp_json_encode( array( 'error' => sprintf( esc_html__( 'Could not delete %s, please remove manually or fix permissions and try again.', 'ewww-image-optimizer' ), esc_html( $file ) ) ) ) );
			}
		}
		$wpdb->update(
			$wpdb->ewwwio_images,
			array(
				'converted' => '',
			),
			array(
				'id' => $optimized_image['id'],
			)
		);
	} // End foreach().

	$new_nonce = ewwwio_maybe_get_new_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-tools' );

	die(
		wp_json_encode(
			array(
				'messages'  => $messages,
				'completed' => $completed,
				'new_nonce' => $new_nonce,
			)
		)
	);
}


/** Function ajax_add_lazy_exclusion() called by wp_ajax hooks: {'ewww_add_lazy_exclusion'} **/
/** Parameters found in function ajax_add_lazy_exclusion(): {"request": ["exclusion", "global", "post_id", "request_uri"]} **/
function ajax_add_lazy_exclusion() {
		$this->debug_message( '<b>' . __METHOD__ . '()</b>' );
		\check_ajax_referer( 'ewww-image-detective', 'ewww_wpnonce' );
		if ( ! \current_user_can( \apply_filters( 'ewww_image_optimizer_admin_permissions', '' ) ) ) {
			\wp_send_json_error( \esc_html__( 'You do not have permission to perform this action.', 'ewww-image-optimizer' ) );
		}
		$this->ob_clean();
		if ( empty( $_REQUEST['exclusion'] ) ) {
			\wp_send_json_error( \esc_html__( 'Cannot save empty exclusion value.', 'ewww-image-optimizer' ) );
		}
		$global    = ! empty( $_REQUEST['global'] ) ? true : false;
		$exclusion = sanitize_text_field( wp_unslash( $_REQUEST['exclusion'] ) );
		if ( $global ) {
			$this->update_global_lazy_exclusions( $exclusion );
		} else {
			if ( empty( $_REQUEST['post_id'] ) && empty( $_REQUEST['request_uri'] ) ) {
				\wp_send_json_error( \esc_html__( 'Cannot save page exclusion without a valid post ID or request URI.', 'ewww-image-optimizer' ) );
			}
			if ( ! empty( $_REQUEST['post_id'] ) ) {
				$post_identifier = (int) $_REQUEST['post_id'];
			} else {
				$post_identifier = \sanitize_text_field( \wp_unslash( $_REQUEST['request_uri'] ) );
			}
			$this->debug_message( "adding exclusion $exclusion for post/page $post_identifier" );
			$this->update_page_lazy_exclusions( $post_identifier, $exclusion );
		}
		$this->ob_clean();
		\wp_send_json(
			array(
				'success' => true,
				'message' => \esc_html__( 'The lazy load exclusion has been added successfully. Refresh the page to confirm there are no scaling issues.', 'ewww-image-optimizer' ),
			)
		);
	}


/** Function ewww_image_optimizer_exactdn_register_site_ajax() called by wp_ajax hooks: {'ewww_exactdn_register_site'} **/
/** Parameters found in function ewww_image_optimizer_exactdn_register_site_ajax(): {"request": ["ewww_wpnonce", "blog_id"]} **/
function ewww_image_optimizer_exactdn_register_site_ajax() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	if ( false === current_user_can( apply_filters( 'ewww_image_optimizer_admin_permissions', '' ) ) ) {
		// Display error message if insufficient permissions.
		ewwwio_ob_clean();
		wp_die( wp_json_encode( array( 'error' => esc_html__( 'Access denied.', 'ewww-image-optimizer' ) ) ) );
	}
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-settings' ) ) {
		die( wp_json_encode( array( 'error' => esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) ) ) );
	}
	if ( empty( $_REQUEST['blog_id'] ) ) {
		die( wp_json_encode( array( 'error' => esc_html__( 'Blog ID not provided.', 'ewww-image-optimizer' ) ) ) );
	}
	$blog_id = (int) $_REQUEST['blog_id'];
	if ( get_current_blog_id() !== $blog_id ) {
		$switch = true;
		switch_to_blog( $blog_id );
	}
	ewwwio_debug_message( "registering site $blog_id" );
	if ( get_option( 'ewww_image_optimizer_exactdn' ) ) {
		if ( ! empty( $switch ) ) {
			restore_current_blog();
		}
		die( wp_json_encode( array( 'status' => 'active' ) ) );
	}

	$result = ewww_image_optimizer_register_site_post();
	if ( ! empty( $switch ) ) {
		restore_current_blog();
	}
	if ( is_wp_error( $result ) ) {
		$error_message   = $result->get_error_message();
		$easyio_site_url = get_home_url( $blog_id );
		ewwwio_debug_message( "registration failed for $easyio_site_url: $error_message" );
		die(
			wp_json_encode(
				array(
					'error' => sprintf(
						/* translators: %s: an HTTP error message */
						esc_html__( 'Could not register site, HTTP error: %s', 'ewww-image-optimizer' ),
						$error_message
					),
				)
			)
		);
	} elseif ( ! empty( $result['body'] ) ) {
		$response = json_decode( $result['body'], true );
		if ( ! empty( $response['error'] ) && false !== strpos( strtolower( $response['error'] ), 'duplicate site url' ) ) {
			die( wp_json_encode( array( 'status' => 'registered' ) ) );
		}
		die( wp_json_encode( $response ) );
	}
	$error_message = sprintf(
		/* translators: %s: The blog URL */
		esc_html__( 'Could not register Easy IO for %s: error unknown.', 'ewww-image-optimizer' ),
		esc_url( get_home_url( $blog_id ) )
	);
	die(
		wp_json_encode(
			array(
				'error' => $error_message,
			)
		)
	);
}


/** Function ewww_image_optimizer_aux_images_remove() called by wp_ajax hooks: {'bulk_aux_images_remove'} **/
/** Parameters found in function ewww_image_optimizer_aux_images_remove(): {"request": ["ewww_wpnonce"], "post": ["ewww_image_id", "ewww_pending"]} **/
function ewww_image_optimizer_aux_images_remove() {
	// Verify that an authorized user has called function.
	$permissions = apply_filters( 'ewww_image_optimizer_bulk_permissions', '' );
	if (
		empty( $_REQUEST['ewww_wpnonce'] ) ||
		(
			! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-tools' ) &&
			! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-bulk' )
		) ||
		! current_user_can( $permissions )
	) {
		ewwwio_ob_clean();
		die( esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) );
	}
	ewwwio_ob_clean();
	global $wpdb;
	if ( empty( $_POST['ewww_image_id'] ) ) {
		die();
	} else {
		$id = (int) $_POST['ewww_image_id'];
	}
	if ( empty( $_POST['ewww_pending'] ) ) {
		ewwwio_debug_message( "removing record for $id" );
		if ( $wpdb->delete(
			$wpdb->ewwwio_images,
			array(
				'id' => $id,
			)
		) ) {
			echo '1';
		}
	} else {
		ewwwio_debug_message( "toggle pending for $id" );
		if ( $wpdb->update(
			$wpdb->ewwwio_images,
			array(
				'pending' => 0,
			),
			array(
				'id' => $id,
			)
		) ) {
			echo '1';
		}
	}
	ewwwio_memory( __FUNCTION__ );
	die();
}


/** Function ewww_ngg_manual() called by wp_ajax hooks: {'ewww_ngg_manual'} **/
/** Parameters found in function ewww_ngg_manual(): {"request": ["ewww_attachment_ID", "ewww_manual_nonce", "ewww_force"]} **/
function ewww_ngg_manual() {
			// Check permission of current user.
			$permissions = apply_filters( 'ewww_image_optimizer_manual_permissions', '' );
			if ( false === current_user_can( $permissions ) ) {
				if ( ! wp_doing_ajax() ) {
					wp_die( esc_html__( 'You do not have permission to optimize images.', 'ewww-image-optimizer' ) );
				}
				ewwwio_ob_clean();
				wp_die( wp_json_encode( array( 'error' => esc_html__( 'You do not have permission to optimize images.', 'ewww-image-optimizer' ) ) ) );
			}
			// Make sure function wasn't called without an attachment to work with.
			if ( empty( $_REQUEST['ewww_attachment_ID'] ) ) {
				if ( ! wp_doing_ajax() ) {
					wp_die( esc_html__( 'No attachment ID was provided.', 'ewww-image-optimizer' ) );
				}
				ewwwio_ob_clean();
				wp_die( wp_json_encode( array( 'error' => esc_html__( 'No attachment ID was provided.', 'ewww-image-optimizer' ) ) ) );
			}
			// Store the attachment $id.
			$id = intval( $_REQUEST['ewww_attachment_ID'] );
			if ( empty( $_REQUEST['ewww_manual_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_manual_nonce'] ), "ewww-manual-$id" ) ) {
				if ( ! wp_doing_ajax() ) {
						wp_die( esc_html__( 'Access denied.', 'ewww-image-optimizer' ) );
				}
				ewwwio_ob_clean();
				wp_die( wp_json_encode( array( 'error' => esc_html__( 'Access denied.', 'ewww-image-optimizer' ) ) ) );
			}
			ewwwio()->force = ! empty( $_REQUEST['ewww_force'] ) ? true : false;
			$this->ewww_ngg_optimize( $id );
			$success = $this->ewww_manage_image_custom_column( 'ewww_image_optimizer', $id, true );
			if ( ! wp_doing_ajax() ) {
				// Get the referring page, and send the user back there.
				wp_safe_redirect( wp_get_referer() );
				die;
			}
			ewwwio_ob_clean();
			wp_die( wp_json_encode( array( 'success' => $success ) ) );
		}


/** Function dismiss_review_notice() called by wp_ajax hooks: {'ewww_dismiss_review_notice'} **/
/** No params detected :-/ **/


/** Function ewww_flag_image_restore() called by wp_ajax hooks: {'ewww_flag_image_restore'} **/
/** Parameters found in function ewww_flag_image_restore(): {"request": ["ewww_attachment_ID", "ewww_manual_nonce"]} **/
function ewww_flag_image_restore() {
			ewwwio_debug_message( '<b>' . __METHOD__ . '()</b>' );
			// Check permission of current user.
			$permissions = apply_filters( 'ewww_image_optimizer_manual_permissions', '' );
			if ( false === current_user_can( $permissions ) ) {
				if ( ! wp_doing_ajax() ) {
					wp_die( esc_html__( 'You do not have permission to optimize images.', 'ewww-image-optimizer' ) );
				}
				ewwwio_ob_clean();
				wp_die( wp_json_encode( array( 'error' => esc_html__( 'You do not have permission to optimize images.', 'ewww-image-optimizer' ) ) ) );
			}
			// Make sure function wasn't called without an attachment to work with.
			if ( false === isset( $_REQUEST['ewww_attachment_ID'] ) ) {
				if ( ! wp_doing_ajax() ) {
					wp_die( esc_html__( 'No attachment ID was provided.', 'ewww-image-optimizer' ) );
				}
				ewwwio_ob_clean();
				wp_die( wp_json_encode( array( 'error' => esc_html__( 'No attachment ID was provided.', 'ewww-image-optimizer' ) ) ) );
			}
			// Store the attachment $id.
			$id = intval( $_REQUEST['ewww_attachment_ID'] );
			if ( empty( $_REQUEST['ewww_manual_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_manual_nonce'] ), "ewww-manual-$id" ) ) {
				if ( ! wp_doing_ajax() ) {
						wp_die( esc_html__( 'Access denied.', 'ewww-image-optimizer' ) );
				}
				ewwwio_ob_clean();
				wp_die( wp_json_encode( array( 'error' => esc_html__( 'Access denied.', 'ewww-image-optimizer' ) ) ) );
			}
			if ( ! class_exists( 'flagMeta' ) ) {
				require_once FLAG_ABSPATH . 'lib/meta.php';
			}
			global $eio_backup;
			$eio_backup->restore_backup_from_meta_data( $id, 'flag' );
			$success = $this->ewww_manage_image_custom_column_capture( $id );
			ewwwio_ob_clean();
			wp_die( wp_json_encode( array( 'success' => $success ) ) );
		}


/** Function ewww_image_optimizer_aux_images_clean() called by wp_ajax hooks: {'bulk_aux_images_table_clean'} **/
/** Parameters found in function ewww_image_optimizer_aux_images_clean(): {"request": ["ewww_wpnonce"], "post": ["ewww_offset"]} **/
function ewww_image_optimizer_aux_images_clean() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	// Verify that an authorized user has called function.
	$permissions = apply_filters( 'ewww_image_optimizer_admin_permissions', '' );
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-tools' ) || ! current_user_can( $permissions ) ) {
		ewwwio_ob_clean();
		die( wp_json_encode( array( 'error' => esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) ) ) );
	}
	global $wpdb;
	$per_page = 500;
	$offset   = empty( $_POST['ewww_offset'] ) ? 0 : $per_page * (int) $_POST['ewww_offset'];

	$already_optimized = $wpdb->get_results( $wpdb->prepare( "SELECT path,orig_size,image_size,id,backup,updated FROM $wpdb->ewwwio_images WHERE pending=0 AND image_size > 0 ORDER BY id DESC LIMIT %d,%d", $offset, $per_page ), ARRAY_A );

	foreach ( $already_optimized as $optimized_image ) {
		$file = ewww_image_optimizer_absolutize_path( $optimized_image['path'] );
		ewwwio_debug_message( "checking $file for duplicates and dereferences" );
		// Will remove duplicates.
		ewww_image_optimizer_find_already_optimized( $file );
		if ( ! ewww_image_optimizer_stream_wrapped( $file ) && ! ewwwio_is_file( $file ) ) {
			ewwwio_debug_message( "removing defunct record for $file" );
			$wpdb->delete(
				$wpdb->ewwwio_images,
				array(
					'id' => $optimized_image['id'],
				),
				array( '%d' )
			);
		}
	} // End foreach().

	$new_nonce = ewwwio_maybe_get_new_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-tools' );

	die(
		wp_json_encode(
			array(
				'success'   => 1,
				'new_nonce' => $new_nonce,
			)
		)
	);
}


/** Function ewww_ngg_cloud_restore() called by wp_ajax hooks: {'ewww_ngg_cloud_restore'} **/
/** Parameters found in function ewww_ngg_cloud_restore(): {"request": ["ewww_attachment_ID", "ewww_manual_nonce"]} **/
function ewww_ngg_cloud_restore() {
			// Check permission of current user.
			$permissions = apply_filters( 'ewww_image_optimizer_manual_permissions', '' );
			if ( false === current_user_can( $permissions ) ) {
				if ( ! wp_doing_ajax() ) {
					wp_die( esc_html__( 'You do not have permission to optimize images.', 'ewww-image-optimizer' ) );
				}
				ewwwio_ob_clean();
				wp_die( wp_json_encode( array( 'error' => esc_html__( 'You do not have permission to optimize images.', 'ewww-image-optimizer' ) ) ) );
			}
			// Make sure function wasn't called without an attachment to work with.
			if ( ! isset( $_REQUEST['ewww_attachment_ID'] ) ) {
				if ( ! wp_doing_ajax() ) {
					wp_die( esc_html__( 'No attachment ID was provided.', 'ewww-image-optimizer' ) );
				}
				ewwwio_ob_clean();
				wp_die( wp_json_encode( array( 'error' => esc_html__( 'No attachment ID was provided.', 'ewww-image-optimizer' ) ) ) );
			}
			// Sanitize the attachment $id.
			$id = (int) $_REQUEST['ewww_attachment_ID'];
			if ( empty( $_REQUEST['ewww_manual_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_manual_nonce'] ), "ewww-manual-$id" ) ) {
				if ( ! wp_doing_ajax() ) {
						wp_die( esc_html__( 'Access denied.', 'ewww-image-optimizer' ) );
				}
				ewwwio_ob_clean();
				wp_die( wp_json_encode( array( 'error' => esc_html__( 'Access denied.', 'ewww-image-optimizer' ) ) ) );
			}
			ewww_image_optimizer_cloud_restore_from_meta_data( $id, 'nextcell' );
			$success = $this->ewww_manage_image_custom_column( 'ewww_image_optimizer', $id, true );
			ewwwio_ob_clean();
			wp_die( wp_json_encode( array( 'success' => $success ) ) );
		}


/** Function ewww_image_optimizer_bulk_quota_update() called by wp_ajax hooks: {'bulk_quota_update'} **/
/** Parameters found in function ewww_image_optimizer_bulk_quota_update(): {"request": ["ewww_wpnonce"]} **/
function ewww_image_optimizer_bulk_quota_update() {
	// Verify that an authorized user has made the request.
	$permissions = apply_filters( 'ewww_image_optimizer_bulk_permissions', '' );
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-bulk' ) || ! current_user_can( $permissions ) ) {
		ewwwio_ob_clean();
		die( esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) );
	}
	ewwwio_ob_clean();
	if ( ewww_image_optimizer_get_option( 'ewww_image_optimizer_cloud_key' ) ) {
		/* translators: %s: information about the number of API credits remaining, if applicable */
		printf( esc_html__( 'Image credits available: %s', 'ewww-image-optimizer' ), wp_kses_post( ewww_image_optimizer_cloud_quota() ) );
	}
	ewwwio_memory( __FUNCTION__ );
	die();
}


/** Function dismiss_lightroom_sync_notice() called by wp_ajax hooks: {'ewww_dismiss_lr_sync'} **/
/** No params detected :-/ **/


/** Function restore_single_image_handler() called by wp_ajax hooks: {'ewww_manual_image_restore_single'} **/
/** Parameters found in function restore_single_image_handler(): {"request": ["ewww_image_id", "ewww_wpnonce"]} **/
function restore_single_image_handler() {
		$this->debug_message( '<b>' . __METHOD__ . '()</b>' );
		// Check permissions of current user.
		$permissions = \apply_filters( 'ewww_image_optimizer_manual_permissions', '' );
		if ( ! \current_user_can( $permissions ) ) {
			// Display error message if insufficient permissions.
			$this->ob_clean();
			\wp_die( \wp_json_encode( array( 'error' => \esc_html__( 'You do not have permission to optimize images.', 'ewww-image-optimizer' ) ) ) );
		}
		// Make sure we didn't accidentally get to this page without an attachment to work on.
		if ( empty( $_REQUEST['ewww_image_id'] ) ) {
			// Display an error message since we don't have anything to work on.
			$this->ob_clean();
			\wp_die( \wp_json_encode( array( 'error' => \esc_html__( 'No image ID was provided.', 'ewww-image-optimizer' ) ) ) );
		}
		if (
			empty( $_REQUEST['ewww_wpnonce'] ) ||
			(
				! \wp_verify_nonce( \sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-tools' ) &&
				! \wp_verify_nonce( \sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-bulk' )
			)
		) {
			$this->ob_clean();
			\wp_die( \wp_json_encode( array( 'error' => \esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) ) ) );
		}
		\session_write_close();
		$image = (int) $_REQUEST['ewww_image_id'];
		$this->debug_message( "attempting restore for $image" );
		if ( $this->restore_file( $image ) ) {
			$this->ob_clean();
			\wp_die( \wp_json_encode( array( 'success' => 1 ) ) );
		}
		$this->ob_clean();
		\wp_die( \wp_json_encode( array( 'error' => \esc_html__( 'Unable to restore image.', 'ewww-image-optimizer' ) ) ) );
	}


/** Function ewww_image_optimizer_bulk_cleanup() called by wp_ajax hooks: {'ewww_bulk_cleanup'} **/
/** Parameters found in function ewww_image_optimizer_bulk_cleanup(): {"request": ["ewww_wpnonce"]} **/
function ewww_image_optimizer_bulk_cleanup() {
	// Verify that an authorized user has started the optimizer.
	$permissions = apply_filters( 'ewww_image_optimizer_bulk_permissions', '' );
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-bulk' ) || ! current_user_can( $permissions ) ) {
		ewwwio_ob_clean();
		die( '<p><b>' . esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) . '</b></p>' );
	}
	// All done, so we can update the bulk options with empty values.
	update_option( 'ewww_image_optimizer_aux_resume', '' );
	update_option( 'ewww_image_optimizer_bulk_resume', '' );
	update_option( 'ewww_image_optimizer_bulk_foreground', '' );
	update_option( 'ewww_image_optimizer_scan_args', '' );
	delete_option( 'ewwwio_stop_scheduled_scan' );
	delete_transient( 'ewww_image_optimizer_skip_aux' );
	delete_transient( 'ewww_image_optimizer_force_reopt' );
	// Let the user know we are done.
	ewwwio_memory( __FUNCTION__ );
	ewwwio_ob_clean();
	if ( ! apply_filters( 'ewwwio_whitelabel', false ) ) {
		die(
			'<div><b>' . esc_html__( 'Finished', 'ewww-image-optimizer' ) . '</b> - ' .
			( ewww_image_optimizer_get_option( 'ewww_image_optimizer_cloud_key' ) ?
			'<a target="_blank" href="https://wordpress.org/support/plugin/ewww-image-optimizer/reviews/#new-post">' .
			esc_html__( 'Write a Review', 'ewww-image-optimizer' ) :
			esc_html__( 'Want more compression?', 'ewww-image-optimizer' ) . ' ' .
			'<a target="_blank" href="https://ewww.io/trial/">' .
			esc_html__( 'Get 5x more with a free trial', 'ewww-image-optimizer' )
			) .
			'</a></div>'
		);
	} else {
		die( '<div><b>' . esc_html__( 'Finished', 'ewww-image-optimizer' ) . '</b></div>' );
	}
}


/** Function ewww_image_optimizer_get_bulk_info() called by wp_ajax hooks: {'ewww_get_bulk_info'} **/
/** Parameters found in function ewww_image_optimizer_get_bulk_info(): {"request": ["ewww_wpnonce", "ids"]} **/
function ewww_image_optimizer_get_bulk_info() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	$permissions = apply_filters( 'ewww_image_optimizer_bulk_permissions', '' );
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-bulk' ) || ! current_user_can( $permissions ) ) {
		ewwwio_ob_clean();
		die( wp_json_encode( array( 'error' => esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) ) ) );
	}
	session_write_close();
	$output = array();

	if ( ! empty( $_REQUEST['ids'] ) ) {
		$ids = sanitize_text_field( wp_unslash( $_REQUEST['ids'] ) );
		ewwwio_debug_message( "validating requested ids: $ids" );
		if ( is_numeric( $ids ) ) {
			$ids = array( (int) $_REQUEST['ids'] );
		} else {
			$ids = explode( ',', $ids );
			$ids = array_filter( array_map( 'absint', $ids ) );
		}
		$fullsize_count = count( $ids );
	} else {
		$fullsize_count = ewww_image_optimizer_count_all_attachments();
	}
	$show_bulk_controls = false;

	$image_sizes = ewww_image_optimizer_get_image_sizes();
	if ( is_array( $image_sizes ) ) {
		$resize_count = count( $image_sizes );
	}
	$resize_count = ( ! empty( $resize_count ) && $resize_count > 1 ? $resize_count : 6 );

	ob_start();
	?>
	<?php if ( ! ewww_image_optimizer_get_option( 'ewww_image_optimizer_cloud_key' ) && ewww_image_optimizer_easy_active() && ! ewwwio()->local->exec_check() ) : ?>
		<div id="ewww-bulk-warning" class="ewww-bulk-info ewwwio-notice notice-info">
			<?php esc_html_e( 'Easy IO is already optimizing your images! If you need to save storage space, you may activate your API key in the settings to compress the local images on your site.', 'ewww-image-optimizer' ); ?>
			<?php ewwwio_help_link( 'https://docs.ewww.io/article/46-exactdn-with-the-ewww-io-api-cloud', '59c44349042863033a1d06d3' ); ?>
		</div>
	<?php elseif ( $fullsize_count < 1 ) : ?>
		<div id="ewww-bulk-warning" class="ewww-bulk-info ewwwio-notice notice-info">
			<?php esc_html_e( 'You do not appear to have uploaded any images yet.', 'ewww-image-optimizer' ); ?>
		</div>
	<?php else : ?>
		<?php if ( ewww_image_optimizer_easy_active() ) : ?>
		<div id="ewww-bulk-warning" class="ewww-bulk-info ewwwio-notice notice-warning">
			<?php esc_html_e( 'Easy IO is already optimizing your images! Optimization of local images is not necessary unless you wish to save storage space.', 'ewww-image-optimizer' ); ?>
		</div>
		<?php else : ?>
			<?php ewwwio()->notices->concise_utils_status(); ?>
		<?php endif; ?>
		<p class="ewww-media-info ewww-bulk-info">
			<?php /* translators: 1: number of images 2: number of registered image sizes */ ?>
			<?php echo esc_html( sprintf( _n( '%1$s uploaded item in the Media Library will be optimized with up to %2$d image files per upload.', '%1$s uploaded items in the Media Library have been selected with up to %2$d image files per upload.', $fullsize_count, 'ewww-image-optimizer' ), number_format_i18n( $fullsize_count ), $resize_count ) ); ?>
			<?php ewww_image_optimizer_bulk_resize_warning_message(); ?>
		</p>
		<a id="ewww-bulk-start-optimizing" class='button-primary' href='#'>
			<?php esc_html_e( 'Start optimizing', 'ewww-image-optimizer' ); ?>
		</a>
		<?php
		$show_bulk_controls = true;
	endif;

	$output['html']               = ob_get_clean();
	$output['show_bulk_controls'] = $show_bulk_controls;

	ewwwio_ob_clean();
	die( wp_json_encode( $output ) );
}


/** Function ewww_image_optimizer_aux_images_exclude() called by wp_ajax hooks: {'bulk_aux_images_exclude'} **/
/** Parameters found in function ewww_image_optimizer_aux_images_exclude(): {"request": ["ewww_wpnonce"], "post": ["ewww_image_id"]} **/
function ewww_image_optimizer_aux_images_exclude() {
	// Verify that an authorized user has called function.
	$permissions = apply_filters( 'ewww_image_optimizer_bulk_permissions', '' );
	if (
		empty( $_REQUEST['ewww_wpnonce'] ) ||
		(
			! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-tools' ) &&
			! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-bulk' )
		) ||
		! current_user_can( $permissions )
	) {
		ewwwio_ob_clean();
		die( esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) );
	}
	ewwwio_ob_clean();
	global $wpdb;
	if ( empty( $_POST['ewww_image_id'] ) ) {
		die();
	} else {
		$id = (int) $_POST['ewww_image_id'];
	}
	$image = new \EWWW_Image( $id );
	ewww_image_optimizer_add_file_exclusion( $image->file );
	ewwwio_debug_message( "excluding {$image->file}" );
	if ( empty( $image->opt_size ) ) {
		ewwwio_debug_message( 'not optimized, removing record' );
		if ( $wpdb->delete(
			$wpdb->ewwwio_images,
			array(
				'id' => $id,
			)
		) ) {
			echo '1';
		}
	} else {
		ewwwio_debug_message( 'previously optimized, toggle pending record' );
		if ( $wpdb->update(
			$wpdb->ewwwio_images,
			array(
				'pending' => 0,
			),
			array(
				'id' => $id,
			)
		) ) {
			echo '1';
		}
	}
	die();
}


/** Function dismiss_wc_regen_notice() called by wp_ajax hooks: {'ewww_dismiss_wc_regen'} **/
/** No params detected :-/ **/


/** Function ewww_image_optimizer_exactdn_activate_ajax() called by wp_ajax hooks: {'ewww_exactdn_activate'} **/
/** Parameters found in function ewww_image_optimizer_exactdn_activate_ajax(): {"request": ["ewww_wpnonce"]} **/
function ewww_image_optimizer_exactdn_activate_ajax() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	if ( false === current_user_can( apply_filters( 'ewww_image_optimizer_admin_permissions', '' ) ) ) {
		// Display error message if insufficient permissions.
		ewwwio_ob_clean();
		wp_die( wp_json_encode( array( 'error' => esc_html__( 'Access denied.', 'ewww-image-optimizer' ) ) ) );
	}
	// Make sure we didn't accidentally get to this page without an attachment to work on.
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-settings' ) ) {
		die( wp_json_encode( array( 'error' => esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) ) ) );
	}
	if ( is_multisite() && defined( 'EXACTDN_SUB_FOLDER' ) && EXACTDN_SUB_FOLDER ) {
		update_site_option( 'ewww_image_optimizer_exactdn', true );
	} elseif ( defined( 'EXACTDN_SUB_FOLDER' ) ) {
		update_option( 'ewww_image_optimizer_exactdn', true );
	} elseif ( is_multisite() && get_site_option( 'exactdn_sub_folder' ) ) {
		update_site_option( 'ewww_image_optimizer_exactdn', true );
	} else {
		update_option( 'ewww_image_optimizer_exactdn', true );
	}
	if ( ! class_exists( 'EWWW\ExactDN' ) ) {
		/**
		 * Page Parsing class for working with HTML content.
		 */
		require_once EWWW_IMAGE_OPTIMIZER_PLUGIN_PATH . 'classes/class-page-parser.php';
		/**
		 * ExactDN class for parsing image urls and rewriting them.
		 */
		require_once EWWW_IMAGE_OPTIMIZER_PLUGIN_PATH . 'classes/class-exactdn.php';
	}
	global $exactdn;
	if ( $exactdn->get_exactdn_domain() ) {
		die( wp_json_encode( array( 'success' => esc_html__( 'Easy IO setup and verification is complete.', 'ewww-image-optimizer' ) ) ) );
	}
	global $exactdn_activate_error;
	if ( empty( $exactdn_activate_error ) ) {
		$exactdn_activate_error = 'error unknown';
	}
	$error_message = sprintf(
		/* translators: 1: A link to the documentation 2: the error message/details */
		esc_html__( 'Could not activate Easy IO, please try again in a few minutes. If this error continues, please see %1$s for troubleshooting steps: %2$s', 'ewww-image-optimizer' ),
		'https://docs.ewww.io/article/66-exactdn-not-verified',
		'<code>' . esc_html( $exactdn_activate_error ) . '</code>'
	);
	if ( 'as3cf_cname_active' === $exactdn_activate_error ) {
		$error_message = esc_html__( 'Easy IO cannot optimize your images while using a custom domain (CNAME) in WP Offload Media. Please disable the custom domain in the WP Offload Media settings.', 'ewww-image-optimizer' );
	}
	die(
		wp_json_encode(
			array(
				'error' => $error_message,
			)
		)
	);
}


/** Function ewww_image_optimizer_aux_meta_clean() called by wp_ajax hooks: {'bulk_aux_images_meta_clean'} **/
/** Parameters found in function ewww_image_optimizer_aux_meta_clean(): {"request": ["ewww_wpnonce"], "post": ["ewww_offset"]} **/
function ewww_image_optimizer_aux_meta_clean() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );

	// Verify that an authorized user has called function.
	$permissions = apply_filters( 'ewww_image_optimizer_admin_permissions', '' );
	if ( empty( $_REQUEST['ewww_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-tools' ) || ! current_user_can( $permissions ) ) {
		ewwwio_ob_clean();
		die( wp_json_encode( array( 'error' => esc_html__( 'Access token has expired, please reload the page.', 'ewww-image-optimizer' ) ) ) );
	}

	global $wpdb;
	$per_page = 50;
	$offset   = empty( $_POST['ewww_offset'] ) ? 0 : (int) $_POST['ewww_offset'];
	ewwwio_debug_message( "getting $per_page attachments, starting at $offset" );

	$attachments = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT ID FROM $wpdb->posts WHERE post_type = 'attachment' AND (post_mime_type LIKE %s OR post_mime_type LIKE %s) ORDER BY ID ASC LIMIT %d,%d",
			'%image%',
			'%pdf%',
			$offset,
			$per_page
		)
	);

	if ( empty( $attachments ) ) {
		die( wp_json_encode( array( 'done' => 1 ) ) );
	}

	foreach ( $attachments as $attachment_id ) {
		ewwwio_debug_message( "checking $attachment_id for migration" );
		$meta = wp_get_attachment_metadata( $attachment_id );
		if ( is_array( $meta ) ) {
			ewww_image_optimizer_migrate_meta_to_db( $attachment_id, $meta );
		}
	}

	$new_nonce = ewwwio_maybe_get_new_nonce( sanitize_key( $_REQUEST['ewww_wpnonce'] ), 'ewww-image-optimizer-tools' );

	die(
		wp_json_encode(
			array(
				'success'   => $per_page,
				'new_nonce' => $new_nonce,
			)
		)
	);
}


