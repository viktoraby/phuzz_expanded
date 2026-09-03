<?php
/***
*
*Found actions: 15
*Found functions:14
*Extracted functions:14
*Total parameter names extracted: 9
*Overview: {'do_ajax_product_import': {'woocommerce_do_ajax_product_import'}, 'do_ajax_product_export': {'woocommerce_do_ajax_product_export'}, 'ajax_dismiss': {'woocommerce_dismiss_product_usage_notice'}, 'ajax_remind_later': {'woocommerce_remind_later_product_usage_notice'}, 'ajax_action_inbox_notification_search': {'woocommerce_json_inbox_notifications_search'}, 'handle_reply_to_review': {'replyto-comment'}, 'ajax_tracks': {'jetpack_tracks'}, 'handle_ajax_opt_out': {'wc_egg_opt_out'}, 'handle_ajax_dismiss': {'wc_egg_dismiss'}, 'delete_zone_count_transient': {'woocommerce_shipping_zone_methods_save_changes', 'woocommerce_shipping_zones_save_changes'}, 'post_add_dismissed_suggestion_handler': {'woocommerce_add_dismissed_marketplace_suggestion'}, 'ajax_check_refund_fix_needed': {'woocommerce_check_refund_fix_needed'}, 'handle_edit_review': {'edit-comment'}, 'sync_email_styles_with_theme': {'wp_save_styles'}}
*
***/

/** Function do_ajax_product_import() called by wp_ajax hooks: {'woocommerce_do_ajax_product_import'} **/
/** No params detected :-/ **/


/** Function do_ajax_product_export() called by wp_ajax hooks: {'woocommerce_do_ajax_product_export'} **/
/** Parameters found in function do_ajax_product_export(): {"post": ["step", "columns", "selected_columns", "export_meta", "export_types", "export_category", "export_product_ids", "filename"]} **/
function do_ajax_product_export() {
		check_ajax_referer( 'wc-product-export', 'security' );

		if ( ! $this->export_allowed() ) {
			wp_send_json_error( array( 'message' => __( 'Insufficient privileges to export products.', 'woocommerce' ) ) );
		}

		include_once WC_ABSPATH . 'includes/export/class-wc-product-csv-exporter.php';

		$step     = isset( $_POST['step'] ) ? absint( $_POST['step'] ) : 1; // WPCS: input var ok, sanitization ok.
		$exporter = new WC_Product_CSV_Exporter();

		if ( ! empty( $_POST['columns'] ) ) { // WPCS: input var ok.
			$exporter->set_column_names( wp_unslash( $_POST['columns'] ) ); // WPCS: input var ok, sanitization ok.
		}

		if ( ! empty( $_POST['selected_columns'] ) ) { // WPCS: input var ok.
			$exporter->set_columns_to_export( wp_unslash( $_POST['selected_columns'] ) ); // WPCS: input var ok, sanitization ok.
		}

		if ( ! empty( $_POST['export_meta'] ) ) { // WPCS: input var ok.
			$exporter->enable_meta_export( true );
		}

		if ( ! empty( $_POST['export_types'] ) ) { // WPCS: input var ok.
			$exporter->set_product_types_to_export( wp_unslash( $_POST['export_types'] ) ); // WPCS: input var ok, sanitization ok.
		}

		if ( ! empty( $_POST['export_category'] ) && is_array( $_POST['export_category'] ) ) {// WPCS: input var ok.
			$exporter->set_product_category_to_export( wp_unslash( array_values( $_POST['export_category'] ) ) ); // WPCS: input var ok, sanitization ok.
		}

		// Set specific product IDs if provided.
		if ( ! empty( $_POST['export_product_ids'] ) ) { // WPCS: input var ok.
			$ids_raw = explode( ',', sanitize_text_field( wp_unslash( $_POST['export_product_ids'] ) ) ); // WPCS: input var ok, sanitization ok.

			if ( ! empty( $ids_raw ) ) {
				$exporter->set_product_ids_to_export( $ids_raw );
			}
		}

		if ( ! empty( $_POST['filename'] ) ) { // WPCS: input var ok.
			$exporter->set_filename( wp_unslash( $_POST['filename'] ) ); // WPCS: input var ok, sanitization ok.
		}

		$exporter->set_page( $step );
		$exporter->generate_file();

		$query_args = apply_filters(
			'woocommerce_export_get_ajax_query_args',
			array(
				'nonce'    => wp_create_nonce( 'product-csv' ),
				'action'   => 'download_product_csv',
				'filename' => $exporter->get_filename(),
			)
		);

		if ( 100 === $exporter->get_percent_complete() ) {
			wp_send_json_success(
				array(
					'step'       => 'done',
					'percentage' => 100,
					'url'        => add_query_arg( $query_args, admin_url( 'edit.php?post_type=product&page=product_exporter' ) ),
				)
			);
		} else {
			wp_send_json_success(
				array(
					'step'       => ++$step,
					'percentage' => $exporter->get_percent_complete(),
					'columns'    => $exporter->get_column_names(),
				)
			);
		}
	}


/** Function ajax_dismiss() called by wp_ajax hooks: {'woocommerce_dismiss_product_usage_notice'} **/
/** Parameters found in function ajax_dismiss(): {"get": ["product_id"]} **/
function ajax_dismiss() {
		if ( ! check_ajax_referer( 'dismiss_product_usage_notice' ) ) {
			wp_die( -1 );
		}

		$user_id = get_current_user_id();
		if ( ! $user_id ) {
			wp_die( -1 );
		}

		$product_id = absint( $_GET['product_id'] ?? 0 );
		if ( ! $product_id ) {
			wp_die( -1 );
		}

		$dismiss_count = absint( get_user_meta( $user_id, self::DISMISSED_COUNT_META_PREFIX . $product_id, true ) );
		update_user_meta( $user_id, self::DISMISSED_COUNT_META_PREFIX . $product_id, $dismiss_count + 1 );

		update_user_meta( $user_id, self::DISMISSED_TIMESTAMP_META_PREFIX . $product_id, time() );
		update_user_meta( $user_id, self::LAST_DISMISSED_TIMESTAMP_META, time() );

		wp_die( 1 );
	}


/** Function ajax_remind_later() called by wp_ajax hooks: {'woocommerce_remind_later_product_usage_notice'} **/
/** Parameters found in function ajax_remind_later(): {"get": ["product_id"]} **/
function ajax_remind_later() {
		if ( ! check_ajax_referer( 'remind_later_product_usage_notice' ) ) {
			wp_die( -1 );
		}

		$user_id = get_current_user_id();
		if ( ! $user_id ) {
			wp_die( -1 );
		}

		$product_id = absint( $_GET['product_id'] ?? 0 );
		if ( ! $product_id ) {
			wp_die( -1 );
		}

		update_user_meta( $user_id, self::REMIND_LATER_TIMESTAMP_META_PREFIX . $product_id, time() );

		wp_die( 1 );
	}


/** Function ajax_action_inbox_notification_search() called by wp_ajax hooks: {'woocommerce_json_inbox_notifications_search'} **/
/** Parameters found in function ajax_action_inbox_notification_search(): {"get": ["term"]} **/
function ajax_action_inbox_notification_search() {
		global $wpdb;

		check_ajax_referer( 'search-products', 'security' );

		if ( ! isset( $_GET['term'] ) ) {
			wp_send_json( array() );
		}

		$search  = wc_clean( sanitize_text_field( wp_unslash( $_GET['term'] ) ) );
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT note_id, name FROM {$wpdb->prefix}wc_admin_notes WHERE name LIKE %s",
				'%' . $wpdb->esc_like( $search ) . '%'
			)
		);
		$rows    = array();
		foreach ( $results as $result ) {
			$rows[ $result->note_id ] = $result->name;
		}
		wp_send_json( $rows );
	}


/** Function handle_reply_to_review() called by wp_ajax hooks: {'replyto-comment'} **/
/** Parameters found in function handle_reply_to_review(): {"post": ["mode", "comment_post_ID", "content", "comment_type", "_wp_unfiltered_html_comment", "comment_ID", "approve_parent", "position"], "request": ["mode"]} **/
function handle_reply_to_review(): void {
		// Don't interfere with comment functionality relating to the reviews meta box within the product editor.
		if ( sanitize_text_field( wp_unslash( $_POST['mode'] ?? '' ) ) === 'single' ) {
			return;
		}

		check_ajax_referer( 'replyto-comment', '_ajax_nonce-replyto-comment' );

		$comment_post_ID = isset( $_POST['comment_post_ID'] ) ? (int) sanitize_text_field( wp_unslash( $_POST['comment_post_ID'] ) ) : 0; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
		$post            = get_post( $comment_post_ID ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase

		if ( ! $post ) {
			wp_die( -1 );
		}

		// Inline Review replies will use the `detail` mode. If that's not what we have, then let WordPress core take over.
		if ( isset( $_REQUEST['mode'] ) && 'dashboard' === $_REQUEST['mode'] ) {
			return;
		}

		// If this is not a a reply to a review, bail silently to let WordPress core take over.
		if ( get_post_type( $post ) !== 'product' ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $comment_post_ID ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
			wp_die( -1 );
		}

		if ( empty( $post->post_status ) ) {
			wp_die( 1 );
		} elseif ( in_array( $post->post_status, array( 'draft', 'pending', 'trash' ), true ) ) {
			wp_die( esc_html__( 'Error: You can\'t reply to a review on a draft product.', 'woocommerce' ) );
		}

		$user = wp_get_current_user();

		if ( $user->exists() ) {
			$user_ID              = $user->ID;
			$comment_author       = wp_slash( $user->display_name );
			$comment_author_email = wp_slash( $user->user_email );
			$comment_author_url   = wp_slash( $user->user_url );
			// WordPress core already sanitizes `content` during the `pre_comment_content` hook, which is why it's not needed here, {@see wp_filter_comment()} and {@see kses_init_filters()}.
			$comment_content = isset( $_POST['content'] ) ? wp_unslash( $_POST['content'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$comment_type    = isset( $_POST['comment_type'] ) ? sanitize_text_field( wp_unslash( $_POST['comment_type'] ) ) : 'comment';

			if ( current_user_can( 'unfiltered_html' ) ) {
				if ( ! isset( $_POST['_wp_unfiltered_html_comment'] ) ) {
					$_POST['_wp_unfiltered_html_comment'] = '';
				}

				if ( wp_create_nonce( 'unfiltered-html-comment' ) !== $_POST['_wp_unfiltered_html_comment'] ) {
					kses_remove_filters(); // Start with a clean slate.
					kses_init_filters();   // Set up the filters.
					remove_filter( 'pre_comment_content', 'wp_filter_post_kses' );
					add_filter( 'pre_comment_content', 'wp_filter_kses' );
				}
			}
		} else {
			wp_die( esc_html__( 'Sorry, you must be logged in to reply to a review.', 'woocommerce' ) );
		}

		if ( '' === $comment_content ) {
			wp_die( esc_html__( 'Error: Please type your reply text.', 'woocommerce' ) );
		}

		$comment_parent = 0;

		if ( isset( $_POST['comment_ID'] ) ) {
			$comment_parent = absint( wp_unslash( $_POST['comment_ID'] ) );
		}

		$comment_auto_approved = false;
		$commentdata           = compact( 'comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID' );

		// Automatically approve parent comment.
		if ( ! empty( $_POST['approve_parent'] ) ) {
			$parent = get_comment( $comment_parent );

			if ( $parent && '0' === $parent->comment_approved && $parent->comment_post_ID === $comment_post_ID ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
				if ( ! current_user_can( 'edit_comment', $parent->comment_ID ) ) {
					wp_die( -1 );
				}

				if ( wp_set_comment_status( $parent, 'approve' ) ) {
					$comment_auto_approved = true;
				}
			}
		}

		$comment_id = wp_new_comment( $commentdata );

		if ( is_wp_error( $comment_id ) ) {
			wp_die( esc_html( $comment_id->get_error_message() ) );
		}

		$comment = get_comment( $comment_id );

		if ( ! $comment ) {
			wp_die( 1 );
		}

		$position = ( isset( $_POST['position'] ) && (int) $_POST['position'] ) ? (int) $_POST['position'] : '-1';

		ob_start();
		$wp_list_table = $this->make_reviews_list_table();
		$wp_list_table->single_row( $comment );
		$comment_list_item = ob_get_clean();

		$response = array(
			'what'     => 'comment',
			'id'       => $comment->comment_ID,
			'data'     => $comment_list_item,
			'position' => $position,
		);

		$counts                   = wp_count_comments();
		$response['supplemental'] = array(
			'in_moderation'        => $counts->moderated,
			'i18n_comments_text'   => sprintf(
			/* translators: %s: Number of reviews. */
				_n( '%s Review', '%s Reviews', $counts->approved, 'woocommerce' ),
				number_format_i18n( $counts->approved )
			),
			'i18n_moderation_text' => sprintf(
			/* translators: %s: Number of reviews. */
				_n( '%s Review in moderation', '%s Reviews in moderation', $counts->moderated, 'woocommerce' ),
				number_format_i18n( $counts->moderated )
			),
		);

		if ( $comment_auto_approved && isset( $parent ) ) {
			$response['supplemental']['parent_approved'] = $parent->comment_ID;
			$response['supplemental']['parent_post_id']  = $parent->comment_post_ID;
		}

		$x = new WP_Ajax_Response();
		$x->add( $response );
		$x->send();
	}


/** Function ajax_tracks() called by wp_ajax hooks: {'jetpack_tracks'} **/
/** Parameters found in function ajax_tracks(): {"request": ["tracksNonce", "tracksEventName", "tracksEventType", "tracksEventProp"]} **/
function ajax_tracks() {
		// Check for nonce.
		if (
			empty( $_REQUEST['tracksNonce'] )
			|| ! wp_verify_nonce( $_REQUEST['tracksNonce'], 'jp-tracks-ajax-nonce' ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- WP core doesn't pre-sanitize nonces either.
		) {
			wp_send_json_error(
				__( 'You aren’t authorized to do that.', 'jetpack-connection' ),
				403
			);
		}

		if ( ! isset( $_REQUEST['tracksEventName'] ) || ! isset( $_REQUEST['tracksEventType'] ) ) {
			wp_send_json_error(
				__( 'No valid event name or type.', 'jetpack-connection' ),
				403
			);
		}

		$tracks_data = array();
		if ( 'click' === $_REQUEST['tracksEventType'] && isset( $_REQUEST['tracksEventProp'] ) ) {
			if ( is_array( $_REQUEST['tracksEventProp'] ) ) {
				$tracks_data = array_map( 'filter_var', wp_unslash( $_REQUEST['tracksEventProp'] ) );
			} else {
				$tracks_data = array( 'clicked' => filter_var( wp_unslash( $_REQUEST['tracksEventProp'] ) ) );
			}
		}

		$this->record_user_event( filter_var( wp_unslash( $_REQUEST['tracksEventName'] ) ), $tracks_data, null, false );

		wp_send_json_success();
	}


/** Function handle_ajax_opt_out() called by wp_ajax hooks: {'wc_egg_opt_out'} **/
/** No params detected :-/ **/


/** Function handle_ajax_dismiss() called by wp_ajax hooks: {'wc_egg_dismiss'} **/
/** Parameters found in function handle_ajax_dismiss(): {"post": ["order_id"]} **/
function handle_ajax_dismiss(): void {
		check_ajax_referer( 'wc_egg_dismiss', 'nonce' );
		$order_id = isset( $_POST['order_id'] ) ? absint( wp_unslash( $_POST['order_id'] ) ) : 0;
		if ( $order_id > 0 ) {
			update_user_meta( get_current_user_id(), '_wc_egg_seen_' . $order_id, '1' );
		}
		wp_die();
	}


/** Function delete_zone_count_transient() called by wp_ajax hooks: {'woocommerce_shipping_zone_methods_save_changes', 'woocommerce_shipping_zones_save_changes'} **/
/** No params detected :-/ **/


/** Function post_add_dismissed_suggestion_handler() called by wp_ajax hooks: {'woocommerce_add_dismissed_marketplace_suggestion'} **/
/** No params detected :-/ **/


/** Function ajax_check_refund_fix_needed() called by wp_ajax hooks: {'woocommerce_check_refund_fix_needed'} **/
/** No params detected :-/ **/


/** Function handle_edit_review() called by wp_ajax hooks: {'edit-comment'} **/
/** Parameters found in function handle_edit_review(): {"post": ["mode", "comment_ID", "content", "status", "comment_status", "position"]} **/
function handle_edit_review(): void {
		// Don't interfere with comment functionality relating to the reviews meta box within the product editor.
		if ( sanitize_text_field( wp_unslash( $_POST['mode'] ?? '' ) ) === 'single' ) {
			return;
		}

		check_ajax_referer( 'replyto-comment', '_ajax_nonce-replyto-comment' );

		$comment_id = isset( $_POST['comment_ID'] ) ? (int) sanitize_text_field( wp_unslash( $_POST['comment_ID'] ) ) : 0;

		if ( empty( $comment_id ) || ! current_user_can( 'edit_comment', $comment_id ) ) {
			wp_die( -1 );
		}

		$review = get_comment( $comment_id );

		// Bail silently if this is not a review, or a reply to a review. That allows `wp_ajax_edit_comment()` to handle any further actions.
		if ( ! $this->is_review_or_reply( $review ) ) {
			return;
		}

		if ( empty( $review->comment_ID ) ) {
			wp_die( -1 );
		}

		if ( empty( $_POST['content'] ) ) {
			wp_die( esc_html__( 'Error: Please type your review text.', 'woocommerce' ) );
		}

		if ( isset( $_POST['status'] ) ) {
			$_POST['comment_status'] = sanitize_text_field( wp_unslash( $_POST['status'] ) );
		}

		$updated = edit_comment();
		if ( is_wp_error( $updated ) ) {
			wp_die( esc_html( $updated->get_error_message() ) );
		}

		$position      = isset( $_POST['position'] ) ? (int) sanitize_text_field( wp_unslash( $_POST['position'] ) ) : -1;
		$wp_list_table = $this->make_reviews_list_table();

		ob_start();
		$wp_list_table->single_row( $review );
		$review_list_item = ob_get_clean();

		$x = new WP_Ajax_Response();

		$x->add(
			array(
				'what'     => 'edit_comment',
				'id'       => $review->comment_ID,
				'data'     => $review_list_item,
				'position' => $position,
			)
		);

		$x->send();
	}


/** Function sync_email_styles_with_theme() called by wp_ajax hooks: {'wp_save_styles'} **/
/** No params detected :-/ **/


