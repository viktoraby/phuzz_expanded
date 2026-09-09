<?php
/***
*
*Found actions: 6
*Found functions:5
*Extracted functions:5
*Total parameter names extracted: 5
*Overview: {'ajax_detect_duplicates': {'pm_detect_duplicates'}, 'get_uri_editor': {'nopriv_pm_get_uri_editor', 'pm_get_uri_editor'}, 'ajax_hide_global_notice': {'pm_dismissed_notice_handler'}, 'ajax_save_permalink': {'pm_save_permalink'}, 'ajax_bulk_tools': {'pm_bulk_tools'}}
*
***/

/** Function ajax_detect_duplicates() called by wp_ajax hooks: {'pm_detect_duplicates'} **/
/** Parameters found in function ajax_detect_duplicates(): {"request": ["custom_uris", "custom_uri", "element_id"]} **/
function ajax_detect_duplicates() {
		if ( ! Permalink_Manager_Admin_Functions::current_user_can_edit_uris() ) {
			wp_send_json_error( array( 'message' => __( 'You are not allowed to perform this action.', 'permalink-manager' ) ), 403 );
		}

		$duplicate_alert = __( 'Permalink is already in use, please select another one!', 'permalink-manager' );
		$duplicates_data = array();

		// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- No data is saved here; the array is recursively sanitized via sanitize_array().
		$custom_uris = ( ! empty( $_REQUEST['custom_uris'] ) ) ? Permalink_Manager_Helper_Functions::sanitize_array( wp_unslash( $_REQUEST['custom_uris'] ) ) : array();

		if ( ! empty( $custom_uris ) ) {
			foreach ( $custom_uris as $raw_element_id => $element_uri ) {
				$element_id                     = sanitize_key( $raw_element_id );
				$duplicates_data[ $element_id ] = Permalink_Manager_URI_Functions::is_uri_duplicated( $element_uri, $element_id ) ? $duplicate_alert : 0;
			}
		} else {
			// phpcs:disable WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended -- No data is saved here
			$single_uri = ( ! empty( $_REQUEST['custom_uri'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['custom_uri'] ) ) : '';
			$element_id = ( ! empty( $_REQUEST['element_id'] ) ) ? sanitize_key( $_REQUEST['element_id'] ) : '';
			// phpcs:enable

			if ( ! empty( $single_uri ) && ! empty( $element_id ) ) {
				$duplicates_data = Permalink_Manager_URI_Functions::is_uri_duplicated( $single_uri, $element_id );
			}
		}

		wp_send_json( $duplicates_data );
	}


/** Function get_uri_editor() called by wp_ajax hooks: {'nopriv_pm_get_uri_editor', 'pm_get_uri_editor'} **/
/** Parameters found in function get_uri_editor(): {"request": ["post_id"], "get": ["action"]} **/
function get_uri_editor( $post = null ) {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended -- Read-only meta box render; access is gated by current_user_can() below.
		if ( empty( $post->ID ) && empty( $_REQUEST['post_id'] ) ) {
			return;
		} else if ( ! empty( $_REQUEST['post_id'] ) && is_numeric( $_REQUEST['post_id'] ) ) {
			$post = get_post( absint( $_REQUEST['post_id'] ) );
		}

		// Check if the user can edit this post
		if ( ! empty( $post->ID ) && current_user_can( 'edit_post', $post->ID ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo ( $post ) ? Permalink_Manager_UI_Elements::display_uri_box( $post, true ) : '';
		}

		if ( wp_doing_ajax() && isset( $_GET['action'] ) && $_GET['action'] == 'pm_get_uri_editor' ) {
			die();
		}
		// phpcs:enable
	}


/** Function ajax_hide_global_notice() called by wp_ajax hooks: {'pm_dismissed_notice_handler'} **/
/** Parameters found in function ajax_hide_global_notice(): {"request": ["alert_id"]} **/
function ajax_hide_global_notice() {
		global $permalink_manager_alerts;

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Low-risk cosmetic notice dismissal; authenticated admin-only AJAX action and no nonce is transmitted by the notice markup.
		$alert_id = ( ! empty( $_REQUEST['alert_id'] ) ) ? sanitize_title( wp_unslash( $_REQUEST['alert_id'] ) ) : "";
		if ( ! empty( $permalink_manager_alerts[ $alert_id ] ) ) {
			$dismissed_transient_name = sprintf( 'permalink-manager-notice_%s', $alert_id );
			$dismissed_time           = ( ! empty( $permalink_manager_alerts[ $alert_id ]['dismissed_time'] ) ) ? (int) $permalink_manager_alerts[ $alert_id ]['dismissed_time'] : DAY_IN_SECONDS;

			set_transient( $dismissed_transient_name, 1, $dismissed_time );
		}
	}


/** Function ajax_save_permalink() called by wp_ajax hooks: {'pm_save_permalink'} **/
/** No params detected :-/ **/


/** Function ajax_bulk_tools() called by wp_ajax hooks: {'pm_bulk_tools'} **/
/** Parameters found in function ajax_bulk_tools(): {"post": ["regenerate", "find_and_replace", "old_string", "new_string", "pm_session_id", "content_type", "taxonomies", "post_types", "post_statuses", "mode", "preview_mode", "iteration"]} **/
function ajax_bulk_tools() {
		global $sitepress, $wpdb;

		// Define variables
		$return = array( 'alert' => Permalink_Manager_UI_Elements::get_alert_message( __( '<strong>No items</strong> were processed!', 'permalink-manager' ), 'error updated_slugs' ) );

		// Get the name of the function
		if ( isset( $_POST['regenerate'] ) ) {
			$nonce_name = sanitize_key( $_POST['regenerate'] );
			$operation  = 'regenerate';
		} else if ( isset( $_POST['find_and_replace'] ) ) {
			$nonce_name = sanitize_key( $_POST['find_and_replace'] );
			$operation  = ( ! empty( $_POST['old_string'] ) && ! empty( $_POST['new_string'] ) ) ? 'find_and_replace' : '';
		}

		// Validate the nonce
		if ( empty( $nonce_name ) || ! wp_verify_nonce( $nonce_name, 'permalink-manager' ) ) {
			$error  = true;
			$return = array( 'alert' => Permalink_Manager_UI_Elements::get_alert_message( __( 'Nonce is invalid!', 'permalink-manager' ), 'error updated_slugs' ) );
		}

		// Check if the user can edit the custom permalinks
		if ( ! current_user_can( 'manage_options' ) ) {
			$error  = true;
			$return = array( 'alert' => Permalink_Manager_UI_Elements::get_alert_message( __( 'You are not allowed to perform this action.', 'permalink-manager' ), 'error updated_slugs' ) );
		}

		// Get the session ID
		$uniq_id = ( ! empty( $_POST['pm_session_id'] ) ) ? sanitize_key( $_POST['pm_session_id'] ) : '';

		// Get content type & post statuses
		if ( ! empty( $_POST['content_type'] ) && $_POST['content_type'] == 'taxonomies' ) {
			$content_type = 'taxonomies';

			if ( empty( $_POST['taxonomies'] ) ) {
				$error  = true;
				$return = array( 'alert' => Permalink_Manager_UI_Elements::get_alert_message( __( '<strong>No taxonomy</strong> selected!', 'permalink-manager' ), 'error updated_slugs' ) );
			}
		} else {
			$content_type = 'post_types';

			// Check if any post type was selected
			if ( empty( $_POST['post_types'] ) ) {
				$error  = true;
				$return = array( 'alert' => Permalink_Manager_UI_Elements::get_alert_message( __( '<strong>No post type</strong> selected!', 'permalink-manager' ), 'error updated_slugs' ) );
			}

			// Check post status
			if ( empty( $_POST['post_statuses'] ) ) {
				$error  = true;
				$return = array( 'alert' => Permalink_Manager_UI_Elements::get_alert_message( __( '<strong>No post status</strong> selected!', 'permalink-manager' ), 'error updated_slugs' ) );
			}
		}

		if ( ! empty( $operation ) && empty( $error ) ) {
			// Hotfix for WPML (start)
			if ( $sitepress ) {
				remove_filter( 'get_terms_args', array( $sitepress, 'get_terms_args_filter' ), 10 );
				remove_filter( 'get_term', array( $sitepress, 'get_term_adjust_id' ), 1 );
				remove_filter( 'terms_clauses', array( $sitepress, 'terms_clauses' ), 10 );
				remove_filter( 'get_pages', array( $sitepress, 'get_pages_adjust_ids' ), 1 );
			}

			// Get the mode
			$mode         = ( isset( $_POST['mode'] ) ) ? sanitize_key( $_POST['mode'] ) : 'custom_uris';
			$preview_mode = ( ! empty( $_POST['preview_mode'] ) ) ? true : false;

			// Get items (try to get them from transient)
			$items = get_transient( "pm_{$uniq_id}" );

			// Get the iteration count and chunk size
			$iteration  = isset( $_POST['iteration'] ) ? intval( $_POST['iteration'] ) : 1;
			$chunk_size = apply_filters( 'permalink_manager_chunk_size', 50 );

			if ( empty( $items ) && ! empty ( $chunk_size ) ) {
				if ( $content_type == 'taxonomies' ) {
					$items = Permalink_Manager_URI_Functions_Tax::get_items();
				} else {
					$items = Permalink_Manager_URI_Functions_Post::get_items();
				}

				if ( ! empty( $items ) ) {
					// Count how many items need to be processed
					$total = count( $items );

					// Split items array into chunks and save them to transient
					$items = array_chunk( $items, $chunk_size );

					set_transient( "pm_{$uniq_id}", $items, 600 );

					// Check for MySQL errors
					if ( ! empty( $wpdb->last_error ) ) {
						printf( '%s (%sMB)', esc_html( $wpdb->last_error ), esc_html( number_format( strlen( serialize( $items ) ) / 1000000, 2 ) ) );
						http_response_code( 500 );
						die();
					}
				}
			}

			// Get homepage URL and ensure that it ends with slash
			$home_url = Permalink_Manager_Permastructure_Functions::get_permalink_base() . "/";

			// Process the variables from $_POST object
			// phpcs:disable WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- The escaped slashes can be a part of REGEX formula
			$old_string = ( ! empty( $_POST['old_string'] ) ) ? str_replace( $home_url, '', esc_sql( sanitize_text_field( $_POST['old_string'] ) ) ) : '';
			$new_string = ( ! empty( $_POST['new_string'] ) ) ? str_replace( $home_url, '', esc_sql( sanitize_text_field( $_POST['new_string'] ) ) ) : '';
			// phpcs:enable WordPress.Security.ValidatedSanitizedInput.MissingUnslash

			// Process only one subarray
			if ( ! empty( $items[ $iteration - 1 ] ) ) {
				$chunk = $items[ $iteration - 1 ];

				// Check how many iterations are needed
				$total_iterations = count( $items );

				if ( $content_type == 'taxonomies' ) {
					$output = Permalink_Manager_URI_Functions_Tax::bulk_process_items( $chunk, $mode, $operation, $old_string, $new_string, $preview_mode );
				} else {
					$output = Permalink_Manager_URI_Functions_Post::bulk_process_items( $chunk, $mode, $operation, $old_string, $new_string, $preview_mode );
				}

				if ( ! empty( $output['updated_count'] ) ) {
					$return                  = array_merge( $return, (array) Permalink_Manager_UI_Elements::display_updated_slugs( $output['updated'], true, true, $preview_mode ) );
					$return['updated_count'] = $output['updated_count'];
				}

				// Send total number of processed items with a first chunk
				if ( ! empty( $total ) && ! empty( $total_iterations ) && $iteration == 1 ) {
					$return['total'] = $total;
					$return['items'] = $items;
				}

				$return['iteration']        = $iteration;
				$return['total_iterations'] = $total_iterations;
				$return['progress']         = $chunk_size * $iteration;
				$return['chunk']            = $chunk;

				// After all chunks are processed remove the transient
				if ( $iteration == $total_iterations ) {
					delete_transient( "pm_{$uniq_id}" );
				}
			}

			// Hotfix for WPML (end)
			if ( $sitepress ) {
				add_filter( 'terms_clauses', array( $sitepress, 'terms_clauses' ), 10, 4 );
				add_filter( 'get_term', array( $sitepress, 'get_term_adjust_id' ), 1, 1 );
				add_filter( 'get_terms_args', array( $sitepress, 'get_terms_args_filter' ), 10, 2 );
				add_filter( 'get_pages', array( $sitepress, 'get_pages_adjust_ids' ), 1, 2 );
			}
		}

		wp_send_json( $return );
		die();
	}


