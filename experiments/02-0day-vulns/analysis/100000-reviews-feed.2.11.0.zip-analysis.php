<?php
/***
*
*Found actions: 73
*Found functions:44
*Extracted functions:40
*Total parameter names extracted: 28
*Overview: {'add_edd_source': {'sbr_add_edd_source'}, 'deactivate_plugin': {'sbr_deactivate_plugin'}, 'on_upgrade_initiated': {'sbr_install_plugin'}, 'ajax_list': {'sbr_review_alert_list'}, 'ajax_bulk_delete': {'sbr_review_alert_bulk_delete'}, 'get_woocommerce_tags': {'sbr_get_woocommerce_tags'}, 'update_woocommerce_source_multi': {'sbr_update_woocommerce_source_multi'}, 'ajax_delete': {'sbr_review_alert_delete'}, 'on_feed_saved': {'sbr_feed_saver_manager_builder_update'}, 'get_woocommerce_categories': {'sbr_get_woocommerce_categories'}, 'sbr_clear_post_header_cache': {'sbr_clear_post_header_cache'}, 'ajax_update_settings': {'sbr_update_settings'}, 'add_woocommerce_source': {'sbr_add_woocommerce_source'}, 'get_edd_categories': {'sbr_get_edd_categories'}, 'add_external_source': {'sbr_add_external_source'}, 'install_plugin': {'sbr_install_plugin', 'am_recommended_block_install'}, 'get_edd_tags': {'sbr_get_edd_tags'}, 'add_edd_source_multi': {'sbr_add_edd_source_multi'}, 'sbr_reset_local_images': {'sbr_reset_local_images'}, 'ajax_duplicate': {'sbr_review_alert_duplicate'}, 'SmashBalloon\\Reviews\\Common\\Builder\\SBR_Feed_Saver_Manager': {'sbr_import_feed_settings', 'sbr_feed_saver_manager_get_source_impact', 'sbr_feed_saver_manager_add_facebook_source', 'sbr_feed_saver_manager_delete_source', 'sbr_feed_saver_manager_connect_manual_facebook', 'sbr_feed_saver_manager_load_more_sources', 'sbr_feed_saver_manager_fly_preview', 'sbr_feed_saver_manager_update_collection_name', 'sbr_feed_saver_manager_add_facebook_souce', 'sbr_feed_saver_manager_export_collection', 'sbr_import_full_collection', 'sbr_feed_saver_manager_create_new_collection', 'sbr_feed_saver_manager_add_multiple_reviews_collection', 'sbr_feed_saver_manager_addupdate_review_collection', 'sbr_feed_saver_manager_advanced_search_reviews', 'sbr_feed_saver_manager_duplicate_collection', 'sbr_feed_saver_manager_get_feed_list_page', 'sbr_feed_saver_manager_add_source', 'sbr_feed_saver_manager_duplicate_feed', 'sbr_feed_saver_manager_start_moderation_mode', 'sbr_import_reviews_collection', 'sbr_clear_all_caches', 'sbr_feed_saver_manager_update_api_key', 'sbr_feed_saver_manager_get_source_posts', 'sbr_feed_saver_manager_builder_update', 'sbr_feed_saver_manager_delete_feeds', 'sbr_feed_saver_manager_delete_review_from_collection'}, 'ajax_record_session': {'sbr_smash_usage_record_session'}, 'on_license_activated': {'sbr_activate_license'}, 'sbr_dismiss_license_notice': {'sbr_dismiss_license_notice'}, 'get_edd_downloads': {'sbr_get_edd_downloads'}, 'ajax_preview_reviews': {'sbr_review_alert_preview_reviews'}, 'SmashBalloon\\Reviews\\Common\\Services\\SBR_Upgrader': {'sbr_maybe_upgrade_redirect', 'nopriv_sbr_run_one_click_upgrade'}, 'dismiss': {'sbr_dashboard_notification_dismiss'}, 'delete_temp_user_ajax_call': {'sbr_delete_temp_user'}, 'add_airbnb_source': {'sbr_add_airbnb_source'}, 'review_notice_consent': {'sbr_review_notice_consent_update'}, 'dismiss_critical_notice': {'sbr_dismiss_critical_notice'}, 'add_woocommerce_source_multi': {'sbr_add_woocommerce_source_multi'}, 'SmashBalloon\\Reviews\\Common\\Helpers\\SBR_Error_Handler': {'sbr_clear_error_logs'}, 'add_booking_source': {'sbr_add_booking_source'}, 'create_temp_user_ajax_call': {'sbr_create_temp_user'}, 'ajax_save': {'sbr_review_alert_save'}, 'update_edd_source_multi': {'sbr_update_edd_source_multi'}, 'sbr_reset_posts': {'sbr_reset_posts'}, 'ajax_toggle_status': {'sbr_review_alert_toggle_status'}, 'get_woocommerce_products': {'sbr_get_woocommerce_products'}, 'handle_ajax': {'sb_feature_suggestion', 'sb_deactivation_feedback'}, 'activate_plugin': {'sbr_activate_plugin'}, 'add_aliexpress_source': {'sbr_add_aliexpress_source'}}
*
***/

/** Function add_edd_source() called by wp_ajax hooks: {'sbr_add_edd_source'} **/
/** Parameters found in function add_edd_source(): {"post": ["download_url", "download_id"]} **/
function add_edd_source()
	{
		check_ajax_referer('sbr-admin', 'nonce');

		// Pro version required for EDD
		if (! self::require_pro_version('EDD')) {
			return;
		}

		if (! sbr_current_user_can('manage_reviews_feed_options')) {
			wp_send_json([ 'error' => 'api_error', 'message' => 'Unauthorized access' ]);
			return;
		}

		// Strict gate (matches Util::get_providers UI tile). is_edd_active()
		// is intentionally retained on the display side; see EDD::is_active_static.
		if (! EDD::is_active_static()) {
			wp_send_json([ 'error' => 'api_error', 'message' => 'EDD or EDD Reviews is not active' ]);
			return;
		}
		$edd = new EDD();

		// Get download ID from URL or direct ID
		$download_url = isset($_POST['download_url']) ? sanitize_text_field(wp_unslash($_POST['download_url'])) : '';
		$download_id  = isset($_POST['download_id']) ? absint($_POST['download_id']) : 0;

		// Extract ID from URL if provided
		if (! empty($download_url) && empty($download_id)) {
			$download_id = EDD::extract_download_id($download_url);
		}

		if (! $download_id) {
			wp_send_json([ 'error' => 'api_error', 'message' => 'Invalid download ID or URL' ]);
			return;
		}

		// Validate download exists
		$download = get_post($download_id);
		if (! $download || $download->post_type !== 'download') {
			wp_send_json([ 'error' => 'api_error', 'message' => 'Download not found' ]);
			return;
		}

		// Check if source already exists
		$existing_source = self::get_existing_source((string) $download_id, 'edd');
		if ($existing_source) {
			self::respond_with_existing_source($existing_source, 'EDD');
			return;
		}

		// Get download info
		$source_info = $edd->get_source_info($download);

		// Fetch initial reviews
		$reviews = $edd->fetch_reviews($download_id, 1, 20);
		$normalized_reviews = $edd->normalize_reviews($reviews, $download);

		// Get thumbnail
		$image_id  = get_post_thumbnail_id($download_id);
		$image_url = $image_id ? wp_get_attachment_url($image_id) : '';

		$source_data = [
			'id'           => (string) $download_id,
			'provider'     => 'edd',
			'name'         => $download->post_title,
			'url'          => get_permalink($download_id),
			'image'        => $image_url,
			'rating'       => $source_info['rating'],
			'review_count' => $source_info['review_count'],
			'account_id'   => (string) $download_id,
			'access_token' => '',
			'info'         => wp_json_encode([
				'id'           => $download_id,
				'name'         => $download->post_title,
				'url'          => get_permalink($download_id),
				'image'        => $image_url,
				'rating'       => $source_info['rating'],
				'total_rating' => $source_info['review_count'],
				'review_count' => $source_info['review_count'],
				'provider'     => 'edd'
			]),
			'error'        => '',
			'expires'      => gmdate('Y-m-d H:i:s', strtotime('+1 year')),
			'last_updated' => current_time('mysql'),
			'author'       => get_current_user_id()
		];

		// Save source
		SBR_Sources::update_or_insert($source_data);

		// Cache reviews
		self::cache_reviews($normalized_reviews, 'edd', (string) $download_id);

		// Schedule bulk update if needed
		if ($source_info['review_count'] > 20) {
			$bulk_update = new Bulk_EDD_Reviews_Update();
			$bulk_update->schedule_task([
				'download_id' => (string) $download_id
			]);
		}

		wp_send_json([
			'success'      => true,
			'message'      => 'addedSource', // Must match frontend expectation in AddSourceModal.js
			'source'       => $source_data,
			'sourcesList'  => SBR_Sources::get_sources_list(),
			'sourcesCount' => SBR_Sources::get_sources_count()
		]);
	}


/** Function deactivate_plugin() called by wp_ajax hooks: {'sbr_deactivate_plugin'} **/
/** Parameters found in function deactivate_plugin(): {"post": ["plugin"]} **/
function deactivate_plugin()
	{
		check_ajax_referer('sbr-admin', 'nonce');

		if (!sbr_current_user_can('manage_reviews_feed_options')) {
			wp_send_json_error();
		}

		if (isset($_POST['plugin'])) {
			$plugin = sanitize_text_field(wp_unslash($_POST['plugin']));
			$deactivate = deactivate_plugins($plugin);
			wp_send_json_success([
				'plugins' => Util::get_plugins_info(),
				'recommendedPlugins' => Util::get_smashballoon_recommended_plugins_info()
			]);
		}

		wp_send_json_error(
			__('Could not deactivate the Plugin. Please deactivate from the Plugins page.', 'reviews-feed')
		);
	}


/** Function on_upgrade_initiated() called by wp_ajax hooks: {'sbr_install_plugin'} **/
/** No params detected :-/ **/


/** Function ajax_list() called by wp_ajax hooks: {'sbr_review_alert_list'} **/
/** Parameters found in function ajax_list(): {"post": ["page"]} **/
function ajax_list(): void
	{
		check_ajax_referer('sbr-admin', 'nonce');

		if (! sbr_current_user_can('manage_reviews_feed_options')) {
			wp_send_json_error(['message' => __('Unauthorized access.', 'reviews-feed')], 403);
		}

		$page = isset($_POST['page']) ? absint($_POST['page']) : 1;

		$popups = self::get_popups([
			'paged' => $page,
		]);

		wp_send_json_success([
			'popupsList'  => $popups['popups'],
			'popupsCount' => $popups['total'],
			'totalPages'  => $popups['total_pages'],
		]);
	}


/** Function ajax_bulk_delete() called by wp_ajax hooks: {'sbr_review_alert_bulk_delete'} **/
/** Parameters found in function ajax_bulk_delete(): {"post": ["ids"]} **/
function ajax_bulk_delete(): void
	{
		check_ajax_referer('sbr-admin', 'nonce');

		if (! sbr_current_user_can('manage_reviews_feed_options')) {
			wp_send_json_error(['message' => __('Unauthorized access.', 'reviews-feed')], 403);
		}

		// Get array of IDs from POST
		// FormData.append converts arrays to comma-separated strings (e.g., "243" or "243,244")
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized below
		// @phpstan-ignore-next-line (wp_unslash can return string|array depending on input)
		$ids_raw = isset($_POST['ids']) ? wp_unslash($_POST['ids']) : '';

		// Handle different formats:
		// 1. Comma-separated string from FormData: "243" or "243,244"
		// 2. JSON string: "[243, 244]"
		// 3. PHP array from standard form submission: ['243', '244']
		$ids = [];
		// @phpstan-ignore-next-line (wp_unslash can return array for $_POST['ids[]'] form fields)
		if (is_array($ids_raw)) {
			$ids = $ids_raw;
		} elseif (is_string($ids_raw)) {
			// Try JSON decode first
			$json_decoded = json_decode($ids_raw, true);
			if (is_array($json_decoded)) {
				$ids = $json_decoded;
			} else {
				// Fall back to comma-separated string
				$ids = array_filter(
					explode(',', $ids_raw),
					function ($val) {
						return strlen($val) > 0;
					}
				);
			}
		}

		if (empty($ids)) {
			wp_send_json_error(['message' => __('No popups selected.', 'reviews-feed')], 400);
		}

		// Sanitize all IDs
		$ids = array_map('absint', $ids);
		$ids = array_filter($ids, function ($id) {
			return $id > 0;
		});

		if (empty($ids)) {
			wp_send_json_error(['message' => __('Invalid popup IDs.', 'reviews-feed')], 400);
		}

		// Delete each popup
		$deleted_count = 0;
		foreach ($ids as $id) {
			if (self::delete_popup($id)) {
				$deleted_count++;
			}
		}

		// Return updated list
		$popups = self::get_popups();

		wp_send_json_success([
			'popupsList'   => $popups['popups'],
			'popupsCount'  => $popups['total'],
			'deletedCount' => $deleted_count,
			'message'      => sprintf(
				/* translators: %d: number of deleted popups */
				_n('%d popup deleted.', '%d popups deleted.', $deleted_count, 'reviews-feed'),
				$deleted_count
			),
		]);
	}


/** Function get_woocommerce_tags() called by wp_ajax hooks: {'sbr_get_woocommerce_tags'} **/
/** No params detected :-/ **/


/** Function update_woocommerce_source_multi() called by wp_ajax hooks: {'sbr_update_woocommerce_source_multi'} **/
/** Parameters found in function update_woocommerce_source_multi(): {"post": ["source_id", "product_ids", "category_ids", "tag_ids", "source_name"]} **/
function update_woocommerce_source_multi()
	{
		check_ajax_referer('sbr-admin', 'nonce');

		// Pro version required for WooCommerce sources
		if (!self::require_pro_version('WooCommerce')) {
			return;
		}

		if (!sbr_current_user_can('manage_reviews_feed_options')) {
			wp_send_json(['error' => 'api_error', 'message' => 'Unauthorized access']);
			return;
		}

		// Validate source_id
		if (!isset($_POST['source_id']) || empty($_POST['source_id'])) {
			wp_send_json(['error' => 'api_error', 'message' => 'Source ID is required']);
			return;
		}

		// Maximum allowed items per selection type (prevent memory exhaustion)
		$max_items = 100;

		// Get product_ids, category_ids, and tag_ids (all optional but at least one required).
		$direct_product_ids = isset($_POST['product_ids']) && is_array($_POST['product_ids']) ? array_map('absint', $_POST['product_ids']) : [];
		$category_ids       = isset($_POST['category_ids']) && is_array($_POST['category_ids']) ? array_map('absint', $_POST['category_ids']) : [];
		$tag_ids            = isset($_POST['tag_ids']) && is_array($_POST['tag_ids']) ? array_map('absint', $_POST['tag_ids']) : [];

		// Enforce maximum limits to prevent memory exhaustion attacks
		if (count($direct_product_ids) > $max_items || count($category_ids) > $max_items || count($tag_ids) > $max_items) {
			wp_send_json([ 'error' => 'api_error', 'message' => sprintf('Maximum %d items allowed per selection type', $max_items) ]);
			return;
		}

		// Filter out zeros.
		$direct_product_ids = array_filter($direct_product_ids);
		$category_ids       = array_filter($category_ids);
		$tag_ids            = array_filter($tag_ids);

		// Validate at least one selection type is provided.
		if (empty($direct_product_ids) && empty($category_ids) && empty($tag_ids)) {
			wp_send_json([ 'error' => 'api_error', 'message' => 'At least one product, category, or tag must be selected' ]);
			return;
		}

		// Check if WooCommerce is active
		if (!class_exists('WooCommerce')) {
			wp_send_json(['error' => 'api_error', 'message' => 'WooCommerce is not active']);
			return;
		}

		$source_id = sanitize_text_field($_POST['source_id']);
		$source_name = isset($_POST['source_name']) && !empty(trim($_POST['source_name']))
			? sanitize_text_field($_POST['source_name'])
			: null;

		// Resolve category IDs to product IDs.
		$category_product_ids = ! empty($category_ids) ? self::get_products_by_categories($category_ids) : [];

		// Resolve tag IDs to product IDs.
		$tag_product_ids = ! empty($tag_ids) ? self::get_products_by_tags($tag_ids) : [];

		// Merge all product IDs, remove duplicates, and apply limit.
		$merge_result = self::merge_and_limit_product_ids($direct_product_ids, $category_product_ids, $tag_product_ids);
		$product_ids = $merge_result['product_ids'];

		// Ensure we have at least one product after resolution.
		if (empty($product_ids)) {
			wp_send_json([ 'error' => 'api_error', 'message' => 'No products found for the selected categories/tags' ]);
			return;
		}

		// Get existing source
		$existing_source = SBR_Sources::get_single_source_info([
			'id' => $source_id,
			'provider' => 'woocommerce'
		]);

		if (!$existing_source) {
			wp_send_json(['error' => 'api_error', 'message' => 'Source not found']);
			return;
		}

		// Parse existing info to get old product_ids
		$existing_info = is_string($existing_source['info'])
			? json_decode($existing_source['info'], true)
			: $existing_source['info'];

		$old_product_ids = $existing_info['product_ids'] ?? [];
		$existing_name = $existing_info['source_name'] ?? $existing_source['name'];

		// Use existing name if not provided
		if ($source_name === null) {
			$source_name = $existing_name;
		}

		// Validate all products and collect their data
		$products_info = [];
		$total_review_count = 0;
		$weighted_rating_sum = 0;

		if (!function_exists('wc_get_product')) {
			wp_send_json(['error' => 'api_error', 'message' => 'WooCommerce plugin is not active.']);
			return;
		}

		foreach ($product_ids as $product_id) {
			$product = wc_get_product($product_id);
			if (!$product) {
				wp_send_json([
					'error' => 'api_error',
					'message' => sprintf('Product with ID %d not found', $product_id)
				]);
				return;
			}

			$review_count = $product->get_review_count();
			$average_rating = floatval($product->get_average_rating());
			$image_id = $product->get_image_id();
			$image_url = $image_id ? wp_get_attachment_image_url($image_id, 'thumbnail') : '';

			$products_info[] = [
				'id' => $product_id,
				'name' => $product->get_name(),
				'sku' => $product->get_sku(),
				'price' => $product->get_price(),
				'price_html' => $product->get_price_html(),
				'image' => $image_url,
				'url' => get_permalink($product_id),
				'review_count' => $review_count,
				'average_rating' => $average_rating
			];

			$total_review_count += $review_count;
			// Use weighted sum: rating * review_count for proper weighted average
			$weighted_rating_sum += $average_rating * $review_count;
		}

		// Calculate weighted average rating across all products
		$average_rating = $total_review_count > 0 ? round($weighted_rating_sum / $total_review_count, 1) : 0;

		// Use first product's image as the source image, or empty if none
		$source_image = !empty($products_info[0]['image']) ? $products_info[0]['image'] : '';

		// Build categories info for storage.
		$categories_info = [];
		if (! empty($category_ids)) {
			foreach ($category_ids as $cat_id) {
				$term = get_term($cat_id, 'product_cat');
				if ($term && ! is_wp_error($term)) {
					$categories_info[] = [
						'id'            => $term->term_id,
						'name'          => $term->name,
						'slug'          => $term->slug,
						'product_count' => $term->count,
					];
				}
			}
		}

		// Build tags info for storage.
		$tags_info = [];
		if (! empty($tag_ids)) {
			foreach ($tag_ids as $tag_id_item) {
				$term = get_term($tag_id_item, 'product_tag');
				if ($term && ! is_wp_error($term)) {
					$tags_info[] = [
						'id'            => $term->term_id,
						'name'          => $term->name,
						'slug'          => $term->slug,
						'product_count' => $term->count,
					];
				}
			}
		}

		// Update source data
		$source_data = [
			'id' => $source_id,
			'provider' => 'woocommerce',
			'name' => $source_name,
			'url' => '',
			'image' => $source_image,
			'rating' => $average_rating,
			'review_count' => $total_review_count,
			'account_id' => $source_id,
			'access_token' => '',
			'info' => json_encode([
				'type' => 'multi_product',
				'source_name' => $source_name,
				'products' => $products_info,
				'product_ids' => array_values($product_ids), // Re-index array
				'direct_product_ids' => array_values($direct_product_ids), // Products selected directly
				'category_ids' => array_values($category_ids), // Categories selected
				'tag_ids' => array_values($tag_ids), // Tags selected
				'categories' => $categories_info, // Category details for display
				'tags' => $tags_info, // Tag details for display
				'direct_products' => array_values(array_filter($products_info, function ($p) use ($direct_product_ids) {
					return in_array($p['id'], $direct_product_ids);
				})), // Product details for directly selected products
				'product_count' => count($products_info),
				'total_rating' => $total_review_count,
				'review_count' => $total_review_count,
				'average_rating' => $average_rating,
				'provider' => 'woocommerce'
			]),
			'error' => '',
			'expires' => date('Y-m-d H:i:s', strtotime('+1 year')),
			'last_updated' => current_time('mysql'),
			'author' => get_current_user_id()
		];

		// Update source
		SBR_Sources::update_or_insert($source_data);

		// Find products that were removed from the source
		$removed_product_ids = array_diff($old_product_ids, $product_ids);

		// Delete reviews for removed products from the cache
		if (! empty($removed_product_ids)) {
			global $wpdb;
			$posts_table = $wpdb->prefix . SBR_POSTS_TABLE;

			// Get comment IDs (reviews) that belong to removed products and this source
			// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
			$product_placeholders = implode(',', array_fill(0, count($removed_product_ids), '%d'));
			$reviews_to_delete = $wpdb->get_col(
				$wpdb->prepare(
					"SELECT p.id FROM {$posts_table} p
					 INNER JOIN {$wpdb->comments} c ON p.post_id = c.comment_ID
					 WHERE p.provider_id = %s
					 AND p.provider = 'woocommerce'
					 AND c.comment_post_ID IN ($product_placeholders)",
					...array_merge([ $source_id ], array_values($removed_product_ids))
				)
			);

			// Delete the reviews
			if (! empty($reviews_to_delete)) {
				$delete_placeholders = implode(',', array_fill(0, count($reviews_to_delete), '%d'));
				$wpdb->query(
					$wpdb->prepare(
						"DELETE FROM {$posts_table} WHERE id IN ($delete_placeholders)",
						...$reviews_to_delete
					)
				);
			}
			// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
		}

		// Find new products that weren't in the old list
		$new_product_ids = array_diff($product_ids, $old_product_ids);

		// Fetch and cache reviews for new products only
		if (! empty($new_product_ids)) {
			$woocommerce = new WooCommerce();

			// Fetch up to 20 reviews from new products in a single query
			$reviews = $woocommerce->fetch_reviews_multi($new_product_ids, 20, 0);
			$normalized_reviews = $woocommerce->normalize_reviews_multi($reviews);

			// Cache new reviews under the multi-product source ID
			if (! empty($normalized_reviews)) {
				self::cache_reviews($normalized_reviews, 'woocommerce', $source_id);
			}

			// Schedule bulk update for all products if there are more reviews to fetch
			// We reset and refetch all products to ensure consistency
			if ($total_review_count > 20) {
				// Reset bulk update status for this source
				$accounts_list = get_option('sbr_bulk_woocommerce', []);
				if (isset($accounts_list[ $source_id ])) {
					$accounts_list[ $source_id ]['is_done']     = false;
					$accounts_list[ $source_id ]['offset']      = 20; // Start after initial 20
					$accounts_list[ $source_id ]['product_ids'] = $product_ids;
					update_option('sbr_bulk_woocommerce', $accounts_list);
				}

				$bulk_update = new \SmashBalloon\Reviews\Pro\Services\BulkUpdate\Bulk_WooCommerce_Reviews_Update();
				$bulk_update->schedule_task([
					'source_id'   => $source_id,
					'product_ids' => $product_ids,
					'type'        => 'multi_product',
				]);
			}
		}

		// Return success response
		wp_send_json([
			'success' => true,
			'message' => 'updatedSource',
			'source' => $source_data,
			'sourcesList' => SBR_Sources::get_sources_list(),
			'sourcesCount' => SBR_Sources::get_sources_count()
		]);
	}


/** Function ajax_delete() called by wp_ajax hooks: {'sbr_review_alert_delete'} **/
/** Parameters found in function ajax_delete(): {"post": ["id"]} **/
function ajax_delete(): void
	{
		check_ajax_referer('sbr-admin', 'nonce');

		if (! sbr_current_user_can('manage_reviews_feed_options')) {
			wp_send_json_error(['message' => __('Unauthorized access.', 'reviews-feed')], 403);
		}

		$id = isset($_POST['id']) ? absint($_POST['id']) : 0;

		if ($id <= 0) {
			wp_send_json_error(['message' => __('Invalid popup ID.', 'reviews-feed')], 400);
		}

		$result = self::delete_popup($id);

		if (!$result) {
			wp_send_json_error(['message' => __('Failed to delete popup.', 'reviews-feed')], 400);
		}

		// Return updated list
		$popups = self::get_popups();

		wp_send_json_success([
			'popupsList'  => $popups['popups'],
			'popupsCount' => $popups['total'],
			'message'     => __('Popup deleted.', 'reviews-feed'),
		]);
	}


/** Function on_feed_saved() called by wp_ajax hooks: {'sbr_feed_saver_manager_builder_update'} **/
/** No params detected :-/ **/


/** Function get_woocommerce_categories() called by wp_ajax hooks: {'sbr_get_woocommerce_categories'} **/
/** No params detected :-/ **/


/** Function sbr_clear_post_header_cache() called by wp_ajax hooks: {'sbr_clear_post_header_cache'} **/
/** No params detected :-/ **/


/** Function ajax_update_settings() called by wp_ajax hooks: {'sbr_update_settings'} **/
/** Parameters found in function ajax_update_settings(): {"post": ["nonce", "action", "settings"]} **/
function ajax_update_settings(): void
	{
		check_ajax_referer('sbr-admin', 'nonce');

		if (!sbr_current_user_can('manage_reviews_feed_options')) {
			wp_send_json_error();
		}

		unset($_POST['nonce'], $_POST['action']);

		$settings = json_decode(stripslashes($_POST['settings']), true);

		$update_array = $this->sanitize_settings($settings) ;

		foreach ($settings['translations'] as $key => $value) {
			$update_array['translations'][$key] = sanitize_text_field($value);
		}


		if ($this->update_settings($update_array)) {
			wp_send_json_success();
		} else {
			wp_send_json_error();
		}
	}


/** Function add_woocommerce_source() called by wp_ajax hooks: {'sbr_add_woocommerce_source'} **/
/** Parameters found in function add_woocommerce_source(): {"post": ["product_id"]} **/
function add_woocommerce_source()
	{
		check_ajax_referer('sbr-admin', 'nonce');

		// Pro version required for WooCommerce sources
		if (!self::require_pro_version('WooCommerce')) {
			return;
		}

		if (!sbr_current_user_can('manage_reviews_feed_options')) {
			wp_send_json(['error' => 'api_error', 'message' => 'Unauthorized access']);
			return;
		}

		if (!isset($_POST['product_id'])) {
			wp_send_json(['error' => 'api_error', 'message' => 'Product ID or URL is required']);
			return;
		}

		// Check if WooCommerce is active
		if (!class_exists('WooCommerce')) {
			wp_send_json(['error' => 'api_error', 'message' => 'WooCommerce is not active']);
			return;
		}

		$input = sanitize_text_field($_POST['product_id']);

		// Determine if input is a URL or ID
		if (filter_var($input, FILTER_VALIDATE_URL) || strpos($input, '/') !== false) {
			// It's a URL, extract product ID
			$product_id = WooCommerce::extract_product_id($input);
			if (!$product_id) {
				wp_send_json(['error' => 'api_error', 'message' => 'Could not find product from the provided URL. Please check the URL and try again.']);
				return;
			}
		} else {
			// It's already an ID
			$product_id = absint($input);
		}

		// Check if source already exists - return existing source without API call
		$existing_source = self::get_existing_source((string) $product_id, 'woocommerce');
		if ($existing_source) {
			self::respond_with_existing_source($existing_source, 'WooCommerce');
			return;
		}

		// Get product. function_exists guard makes the CI phpstan run (which
		// loads WP stubs but not WooCommerce stubs) recognize the call as
		// possibly-undefined, and gives correctness coverage if the WooCommerce
		// plugin is deactivated between the UI's plugin-required check and the
		// AJAX call landing here.
		if (!function_exists('wc_get_product')) {
			wp_send_json(['error' => 'api_error', 'message' => 'WooCommerce plugin is not active.']);
			return;
		}
		$product = wc_get_product($product_id);
		if (!$product) {
			wp_send_json(['error' => 'api_error', 'message' => 'Invalid product. The product may have been deleted or does not exist.']);
			return;
		}

		// Get reviews count
		$review_count = $product->get_review_count();
		if ($review_count === 0) {
			wp_send_json(['error' => 'api_error', 'message' => 'This product has no reviews yet. Please select a product with at least one review.']);
			return;
		}

		// Create source data
		$source_data = [
			'id' => (string) $product_id,  // Use product ID directly (no wc_ prefix)
			'provider' => 'woocommerce',
			'name' => $product->get_name(),
			'url' => get_permalink($product_id),
			'image' => wp_get_attachment_url($product->get_image_id()),
			'rating' => floatval($product->get_average_rating()),
			'review_count' => $review_count,
			'account_id' => (string) $product_id,
			'access_token' => '', // Not needed for WooCommerce
			'info' => json_encode([
				'id' => $product_id,
				'name' => $product->get_name(),
				'url' => get_permalink($product_id),
				'image' => wp_get_attachment_url($product->get_image_id()),
				'rating' => floatval($product->get_average_rating()),
				'total_rating' => $review_count,
				'review_count' => $review_count,
				'provider' => 'woocommerce',
				// Additional WooCommerce-specific data
				'type' => 'product',
				'sku' => $product->get_sku(),
				'price' => $product->get_price()
			]),
			'error' => '',
			'expires' => date('Y-m-d H:i:s', strtotime('+1 year')),
			'last_updated' => current_time('mysql'),
			'author' => get_current_user_id()
		];

		// Save source
		SBR_Sources::update_or_insert($source_data);

		// Fetch and cache initial reviews (page 1 with 20 reviews)
		$woocommerce = new WooCommerce();
		$reviews = $woocommerce->fetch_reviews($product_id, 1, 20); // Page 1, 20 per page
		$normalized_reviews = $woocommerce->normalize_reviews($reviews, $product);
		self::cache_reviews($normalized_reviews, 'woocommerce', (string) $product_id);

		// Schedule bulk update to fetch additional pages in background
		if ($review_count > 20) {
			$bulk_update = new \SmashBalloon\Reviews\Pro\Services\BulkUpdate\Bulk_WooCommerce_Reviews_Update();
			$bulk_update->schedule_task([
				'product_id' => (string) $product_id
			]);
		}

		// Return flat structure to match other providers
		wp_send_json([
			'success' => true,
			'message' => 'addedSource', // Must match frontend expectation
			'source' => $source_data,
			'sourcesList' => SBR_Sources::get_sources_list(),
			'sourcesCount' => SBR_Sources::get_sources_count()
		]);
	}


/** Function get_edd_categories() called by wp_ajax hooks: {'sbr_get_edd_categories'} **/
/** No params detected :-/ **/


/** Function add_external_source() called by wp_ajax hooks: {'sbr_add_external_source'} **/
/** Parameters found in function add_external_source(): {"post": ["provider", "source_url"]} **/
function add_external_source()
	{
		check_ajax_referer('sbr-admin', 'nonce');

		// Pro version required for external providers (Airbnb, Booking, AliExpress)
		if (!self::require_pro_version('External')) {
			return;
		}

		if (!sbr_current_user_can('manage_reviews_feed_options')) {
			wp_send_json(['error' => 'api_error', 'message' => 'Unauthorized access']);
			return;
		}

		if (!isset($_POST['provider']) || !isset($_POST['source_url'])) {
			wp_send_json(['error' => 'api_error', 'message' => 'Provider and source URL are required']);
			return;
		}

		$provider_name = sanitize_text_field($_POST['provider']);
		$source_url = sanitize_text_field($_POST['source_url']);

		// Map provider names to classes and API parameter names
		$provider_config = [
			'airbnb' => [
				'class' => Airbnb::class,
				'param_name' => 'propertyId',
				'friendly_name' => 'Airbnb'
			],
			'booking' => [
				'class' => BookingCom::class,
				'param_name' => 'hotel_id',
				'friendly_name' => 'Booking.com'
			],
			'aliexpress' => [
				'class' => AliExpress::class,
				'param_name' => 'itemId',
				'friendly_name' => 'AliExpress'
			]
		];

		if (!isset($provider_config[$provider_name])) {
			wp_send_json(['error' => 'api_error', 'message' => 'Unsupported provider: ' . $provider_name]);
			return;
		}

		$config = $provider_config[$provider_name];
		$provider_class = $config['class'];

		try {
			// Extract source ID from URL
			$source_id = $provider_class::extractSourceId($source_url);

			if (!$source_id) {
				wp_send_json(['error' => 'api_error', 'message' => 'Invalid source URL for ' . $config['friendly_name']]);
				return;
			}

			// Check if source already exists
			$existing_source = self::get_existing_source($source_id, $provider_name);
			if ($existing_source) {
				self::respond_with_existing_source($existing_source, $config['friendly_name']);
				return;
			}

			// Use SBRelay to fetch normalized data from API
			$relay = new SBRelay();

			// Build API parameters based on provider
			$api_params = [$config['param_name'] => $source_id];

			// SMASH-782: Booking's reviews endpoint needs sort/paging/locale to
			// return results. The dedicated add_booking_source() handler (the path
			// the Add-Source modal actually calls) sends these; mirror them here so
			// the generic handler produces an identical call if it is ever used for
			// Booking. airbnb/aliexpress are unaffected; the source endpoint ignores
			// the extra keys.
			if ($provider_name === 'booking') {
				$api_params['sort_type']   = 'SORT_MOST_RELEVANT';
				$api_params['page_number'] = 0;
				$api_params['locale']      = 'en-gb';
			}

			// SMASH-782: call /source/<provider> BEFORE /reviews/<provider>, same as
			// the provider-specific handlers (add_airbnb/booking/aliexpress_source).
			// The relay's reviews route is gated by the source-must-exist check
			// (post-SMASH-1360 quota hardening); calling reviews first returns
			// `reviewsSourceNotCreated` / silent 0 reviews.
			$source_response = $relay->callProvider($provider_name, $api_params, 'source', 'GET');
			if (isset($source_response['success']) && $source_response['success'] === false) {
				// SBRelay::call() unwraps the error envelope, so apiMessage is top-level.
				$upstream_msg = $source_response['apiMessage']
					?? $source_response['data']['apiMessage']
					?? $source_response['message']
					?? ('Unable to fetch ' . $config['friendly_name'] . ' source info from upstream.');
				// wp_send_json() exits via wp_die() in production, but wp_die is
				// mocked (non-exiting) under tests — return so execution stops in
				// both, matching the reviews-error handler below.
				wp_send_json(['error' => 'api_error', 'message' => $upstream_msg]);
				return;
			}
			$source_info_from_source = isset($source_response['info']) && is_array($source_response['info'])
				? $source_response['info']
				: [];

			// Fetch reviews from API via SBRelay. Include `place_id` so the relay's
			// reviews middleware can find the source row (see the provider-specific
			// handlers for the middleware-ordering rationale).
			// Returns normalized data with 'reviews' and 'info' keys.
			$api_params['place_id'] = $source_id;
			$response = $relay->callProvider($provider_name, $api_params, 'reviews', 'GET');

			// Validate response structure - proxy returns { reviews: [], info: {} }
			if (!isset($response['reviews']) || !isset($response['info'])) {
				$error_message = 'Unable to fetch reviews. Unexpected API response format.';
				if (isset($response['message'])) {
					$error_message .= ' API Error: ' . $response['message'];
				}
				wp_send_json(['error' => 'api_error', 'message' => $error_message]);
				return;
			}

			// Use normalized data from proxy. Prefer the richer source-endpoint
			// info, falling back to reviews-endpoint info for any missing key;
			// keep legitimate falsy values (0, false), drop only null / ''.
			$normalized_reviews = $response['reviews'];
			$reviews_info = is_array($response['info']) ? $response['info'] : [];
			$source_info = array_filter($source_info_from_source, function ($v) {
				return $v !== null && $v !== '';
			}) + $reviews_info;
			$review_count = count($normalized_reviews);

			// Prepare source data for database
			$source_data = [
				'id' => $source_id,
				'provider' => $provider_name,
				'name' => $source_info['name'] ?? $config['friendly_name'] . ' ' . $source_id,
				'url' => $source_info['url'] ?? '',
				'image' => $source_info['image'] ?? '',
				'rating' => $source_info['rating'] ?? 0,
				'review_count' => $source_info['review_count'] ?? $review_count,
				'account_id' => $source_id,
				'access_token' => '', // No API key needed - handled by proxy
				'info' => json_encode([
					'id' => $source_id,
					'name' => $source_info['name'] ?? $config['friendly_name'] . ' ' . $source_id,
					'url' => $source_info['url'] ?? '',
					'image' => $source_info['image'] ?? '',
					'rating' => $source_info['rating'] ?? 0,
					'total_rating' => $source_info['review_count'] ?? $review_count,
					'review_count' => $source_info['review_count'] ?? $review_count,
					'provider' => $provider_name
				]),
				'error' => '',
				'expires' => date('Y-m-d H:i:s', strtotime('+30 days')),
				'last_updated' => current_time('mysql'),
				'author' => get_current_user_id()
			];

			// Save source to database
			SBR_Sources::update_or_insert($source_data);

			// Cache reviews - already normalized by proxy
			if (!empty($normalized_reviews)) {
				self::cache_reviews($normalized_reviews, $provider_name, $source_id);
			}

			// Return flat structure to match other providers
			wp_send_json([
				'success' => true,
				'message' => 'addedSource',
				'source' => $source_data,
				'sourcesList' => SBR_Sources::get_sources_list(),
				'sourcesCount' => SBR_Sources::get_sources_count()
			]);
		} catch (\Exception $e) {
			wp_send_json(['error' => 'api_error', 'message' => $e->getMessage()]);
		}
	}


/** Function install_plugin() called by wp_ajax hooks: {'sbr_install_plugin', 'am_recommended_block_install'} **/
/** Parameters found in function install_plugin(): {"post": ["plugin"]} **/
function install_plugin()
	{

		check_ajax_referer('sbr-admin', 'nonce');

		if (!sbr_current_user_can('manage_reviews_feed_options')) {
			wp_send_json_error();
		}

		$error = esc_html__('Could not install addon. Please download from smashballoon.com and install manually.', 'reviews-feed');

		if (empty($_POST['plugin'])) {
			wp_send_json_error($error);
		}

		// Set the current screen to avoid undefined notices.
		set_current_screen('sbr-about');

		// Prepare variables.
		$url = esc_url_raw(
			add_query_arg(
				array(
					'page' => 'sbr-about',
				),
				admin_url('admin.php')
			)
		);

		$creds = request_filesystem_credentials($url, '', false, false, null);

		// Check for file system permissions.
		if (false === $creds) {
			wp_send_json_error($error);
		}

		if (!WP_Filesystem($creds)) {
			wp_send_json_error($error);
		}

		/*
		 * We do not need any extra credentials if we have gotten this far, so let's install the plugin.
		 */


		// Do not allow WordPress to search/download translations, as this will break JS output.
		remove_action('upgrader_process_complete', array('Language_Pack_Upgrader', 'async_upgrade'), 20);

		// Create the plugin upgrader with our custom skin.
		$installer = new PluginSilentUpgrader(new SBR_Install_Skin());
		// Error check.
		if (!method_exists($installer, 'install') || empty($_POST['plugin'])) {
			wp_send_json_error($error);
		}

        $installer->install(sanitize_text_field(wp_unslash($_POST['plugin']))); // phpcs:ignore

		// Flush the cache and return the newly installed plugin basename.
		wp_cache_flush();

		$plugin_basename = $installer->plugin_info();

		if ($plugin_basename) {
			// Activate the plugin silently.
			$activated = activate_plugin($plugin_basename);

			wp_send_json_success([
				'plugins' => Util::get_plugins_info(),
				'recommendedPlugins' => Util::get_smashballoon_recommended_plugins_info(),
				'message' => !is_wp_error($activated) ? __('Plugin Installed & Activated.', 'reviews-feed') : __('Plugin Installed.', 'reviews-feed')
			]);
		}

		wp_send_json_error($error);
	}


/** Function get_edd_tags() called by wp_ajax hooks: {'sbr_get_edd_tags'} **/
/** No params detected :-/ **/


/** Function add_edd_source_multi() called by wp_ajax hooks: {'sbr_add_edd_source_multi'} **/
/** Parameters found in function add_edd_source_multi(): {"post": ["download_ids", "category_ids", "tag_ids", "source_name"]} **/
function add_edd_source_multi()
	{
		check_ajax_referer('sbr-admin', 'nonce');

		// Pro version required
		if (! self::require_pro_version('EDD')) {
			return;
		}

		if (! sbr_current_user_can('manage_reviews_feed_options')) {
			wp_send_json([ 'error' => 'api_error', 'message' => 'Unauthorized access' ]);
			return;
		}

		// Strict gate (matches Util::get_providers UI tile). is_edd_active()
		// is intentionally retained on the display side; see EDD::is_active_static.
		if (! EDD::is_active_static()) {
			wp_send_json([ 'error' => 'api_error', 'message' => 'EDD or EDD Reviews is not active' ]);
			return;
		}
		$edd = new EDD();

		// Maximum allowed items per selection type (prevent memory exhaustion)
		$max_items = 100;

		// Get download_ids, category_ids, and tag_ids (all optional but at least one required).
		// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- array_map with absint sanitizes the values
		$direct_download_ids = isset($_POST['download_ids']) && is_array($_POST['download_ids']) ? array_map('absint', $_POST['download_ids']) : [];
		$category_ids        = isset($_POST['category_ids']) && is_array($_POST['category_ids']) ? array_map('absint', $_POST['category_ids']) : [];
		$tag_ids             = isset($_POST['tag_ids']) && is_array($_POST['tag_ids']) ? array_map('absint', $_POST['tag_ids']) : [];
		// phpcs:enable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		// Enforce maximum limits to prevent memory exhaustion attacks
		if (count($direct_download_ids) > $max_items || count($category_ids) > $max_items || count($tag_ids) > $max_items) {
			wp_send_json([ 'error' => 'api_error', 'message' => sprintf('Maximum %d items allowed per selection type', $max_items) ]);
			return;
		}

		// Filter out zeros.
		$direct_download_ids = array_filter($direct_download_ids);
		$category_ids        = array_filter($category_ids);
		$tag_ids             = array_filter($tag_ids);

		// Validate at least one selection type is provided.
		if (empty($direct_download_ids) && empty($category_ids) && empty($tag_ids)) {
			wp_send_json([ 'error' => 'api_error', 'message' => 'At least one download, category, or tag must be selected' ]);
			return;
		}

		// Validate source_name is provided
		if (! isset($_POST['source_name']) || empty(trim(sanitize_text_field(wp_unslash($_POST['source_name']))))) {
			wp_send_json([ 'error' => 'api_error', 'message' => 'Source name is required' ]);
			return;
		}

		$source_name = sanitize_text_field(wp_unslash($_POST['source_name']));

		// Resolve category and tag selections to download IDs
		$category_download_ids = $edd->get_downloads_by_categories($category_ids);
		$tag_download_ids      = $edd->get_downloads_by_tags($tag_ids);

		// Merge and limit download IDs
		$merge_result = self::merge_and_limit_download_ids($direct_download_ids, $category_download_ids, $tag_download_ids);
		$download_ids = $merge_result['download_ids'];

		if (empty($download_ids)) {
			wp_send_json([ 'error' => 'api_error', 'message' => 'No downloads selected' ]);
			return;
		}

		// Generate unique source ID
		$source_id = 'edd_multi_' . wp_generate_uuid4();

		// Build downloads info
		$downloads_info      = [];
		$total_review_count  = 0;
		$weighted_rating_sum = 0;

		foreach ($download_ids as $download_id) {
			$download = get_post($download_id);
			if (! $download || $download->post_type !== 'download') {
				continue;
			}

			$stats = $edd->get_download_review_stats($download_id);
			$review_count   = $stats['review_count'];
			$average_rating = $stats['average_rating'];

			$image_id  = get_post_thumbnail_id($download_id);
			$image_url = $image_id ? wp_get_attachment_image_url($image_id, 'thumbnail') : '';

			$downloads_info[] = [
				'id'             => $download_id,
				'name'           => $download->post_title,
				'image'          => $image_url,
				'url'            => get_permalink($download_id),
				'review_count'   => $review_count,
				'average_rating' => $average_rating
			];

			$total_review_count  += $review_count;
			$weighted_rating_sum += $average_rating * $review_count;
		}

		// Calculate weighted average
		$average_rating = $total_review_count > 0 ? round($weighted_rating_sum / $total_review_count, 1) : 0;

		// Use first download's image as source image
		$source_image = ! empty($downloads_info[0]['image']) ? $downloads_info[0]['image'] : '';

		// Build categories info
		$categories_info = [];
		foreach ($category_ids as $cat_id) {
			$term = get_term($cat_id, 'download_category');
			if ($term && ! is_wp_error($term)) {
				$categories_info[] = [
					'id'             => $term->term_id,
					'name'           => $term->name,
					'slug'           => $term->slug,
					'download_count' => $term->count,
				];
			}
		}

		// Build tags info
		$tags_info = [];
		foreach ($tag_ids as $tag_id_item) {
			$term = get_term($tag_id_item, 'download_tag');
			if ($term && ! is_wp_error($term)) {
				$tags_info[] = [
					'id'             => $term->term_id,
					'name'           => $term->name,
					'slug'           => $term->slug,
					'download_count' => $term->count,
				];
			}
		}

		$source_data = [
			'id'           => $source_id,
			'provider'     => 'edd',
			'name'         => $source_name,
			'url'          => '',
			'image'        => $source_image,
			'rating'       => $average_rating,
			'review_count' => $total_review_count,
			'account_id'   => $source_id,
			'access_token' => '',
			'info'         => wp_json_encode([
				'type'              => 'multi_download',
				'source_name'       => $source_name,
				'downloads'         => $downloads_info,
				'download_ids'      => array_values($download_ids),
				'direct_download_ids' => array_values($direct_download_ids),
				'category_ids'      => array_values($category_ids),
				'tag_ids'           => array_values($tag_ids),
				'categories'        => $categories_info,
				'tags'              => $tags_info,
				'direct_downloads'  => array_values(array_filter($downloads_info, function ($d) use ($direct_download_ids) {
					return in_array($d['id'], $direct_download_ids, true);
				})),
				'download_count'    => count($downloads_info),
				'total_rating'      => $total_review_count,
				'review_count'      => $total_review_count,
				'average_rating'    => $average_rating,
				'provider'          => 'edd'
			]),
			'error'        => '',
			'expires'      => gmdate('Y-m-d H:i:s', strtotime('+1 year')),
			'last_updated' => current_time('mysql'),
			'author'       => get_current_user_id()
		];

		// Save source
		SBR_Sources::update_or_insert($source_data);

		// Fetch and cache initial reviews
		$reviews = $edd->fetch_reviews_multi($download_ids, 20, 0);
		$normalized_reviews = $edd->normalize_reviews_multi($reviews);

		if (! empty($normalized_reviews)) {
			self::cache_reviews($normalized_reviews, 'edd', $source_id);
		}

		// Schedule bulk update if needed
		if ($total_review_count > 20) {
			$bulk_update = new Bulk_EDD_Reviews_Update();
			$bulk_update->schedule_task([
				'source_id'    => $source_id,
				'download_ids' => $download_ids,
				'type'         => 'multi_download',
			]);
		}

		wp_send_json([
			'success'      => true,
			'message'      => 'addedSource', // Must match frontend expectation in AddSourceModal.js
			'source'       => $source_data,
			'sourcesList'  => SBR_Sources::get_sources_list(),
			'sourcesCount' => SBR_Sources::get_sources_count()
		]);
	}


/** Function sbr_reset_local_images() called by wp_ajax hooks: {'sbr_reset_local_images'} **/
/** No params detected :-/ **/


/** Function ajax_duplicate() called by wp_ajax hooks: {'sbr_review_alert_duplicate'} **/
/** Parameters found in function ajax_duplicate(): {"post": ["id"]} **/
function ajax_duplicate(): void
	{
		check_ajax_referer('sbr-admin', 'nonce');

		if (! sbr_current_user_can('manage_reviews_feed_options')) {
			wp_send_json_error(['message' => __('Unauthorized access.', 'reviews-feed')], 403);
		}

		$id = isset($_POST['id']) ? absint($_POST['id']) : 0;

		if ($id <= 0) {
			wp_send_json_error(['message' => __('Invalid popup ID.', 'reviews-feed')], 400);
		}

		// Check popup limit for non-Pro Plus users (only 1 popup allowed)
		$is_pro_plus = Util::sbr_is_pro_plus();
		if (!$is_pro_plus) {
			$existing = self::get_popups(['posts_per_page' => 1]);
			if ($existing['total'] >= 1) {
				wp_send_json_error([
					'message' => __('Upgrade to Pro Plus to create multiple review alerts.', 'reviews-feed'),
					'upsell_key' => 'reviewAlertMultiple',
				], 403);
			}
		}

		$result = self::duplicate_popup($id);

		if (is_wp_error($result)) {
			wp_send_json_error(['message' => $result->get_error_message()], 400);
		}

		// Return updated list
		$popups = self::get_popups();

		wp_send_json_success([
			'popupsList'  => $popups['popups'],
			'popupsCount' => $popups['total'],
			'newPopupId'  => $result,
			'message'     => __('Popup duplicated.', 'reviews-feed'),
		]);
	}


/** Function SmashBalloon\Reviews\Common\Builder\SBR_Feed_Saver_Manager() called by wp_ajax hooks: {'sbr_import_feed_settings', 'sbr_feed_saver_manager_get_source_impact', 'sbr_feed_saver_manager_add_facebook_source', 'sbr_feed_saver_manager_delete_source', 'sbr_feed_saver_manager_connect_manual_facebook', 'sbr_feed_saver_manager_load_more_sources', 'sbr_feed_saver_manager_fly_preview', 'sbr_feed_saver_manager_update_collection_name', 'sbr_feed_saver_manager_add_facebook_souce', 'sbr_feed_saver_manager_export_collection', 'sbr_import_full_collection', 'sbr_feed_saver_manager_create_new_collection', 'sbr_feed_saver_manager_add_multiple_reviews_collection', 'sbr_feed_saver_manager_addupdate_review_collection', 'sbr_feed_saver_manager_advanced_search_reviews', 'sbr_feed_saver_manager_duplicate_collection', 'sbr_feed_saver_manager_get_feed_list_page', 'sbr_feed_saver_manager_add_source', 'sbr_feed_saver_manager_duplicate_feed', 'sbr_feed_saver_manager_start_moderation_mode', 'sbr_import_reviews_collection', 'sbr_clear_all_caches', 'sbr_feed_saver_manager_update_api_key', 'sbr_feed_saver_manager_get_source_posts', 'sbr_feed_saver_manager_builder_update', 'sbr_feed_saver_manager_delete_feeds', 'sbr_feed_saver_manager_delete_review_from_collection'} **/
/** No function found :-/ **/


/** Function ajax_record_session() called by wp_ajax hooks: {'sbr_smash_usage_record_session'} **/
/** Parameters found in function ajax_record_session(): {"post": ["duration_seconds"]} **/
function ajax_record_session(): void
	{
		check_ajax_referer('sbr_smash_usage_record_session', 'nonce');
		// sbr_current_user_can() applies sbr_settings_pages_capability, which the other
		// handlers in this class already go through — a site filtering that capability
		// otherwise gets this one endpoint enforced differently.
		if (! sbr_current_user_can('manage_reviews_feed_options') || ! Config::is_enabled()) {
			wp_send_json_error();
		}
		$seconds = isset($_POST['duration_seconds']) ? (int) $_POST['duration_seconds'] : 0;
		EventRecorder::record_session_duration($seconds);
		wp_send_json_success();
	}


/** Function on_license_activated() called by wp_ajax hooks: {'sbr_activate_license'} **/
/** No params detected :-/ **/


/** Function sbr_dismiss_license_notice() called by wp_ajax hooks: {'sbr_dismiss_license_notice'} **/
/** No params detected :-/ **/


/** Function get_edd_downloads() called by wp_ajax hooks: {'sbr_get_edd_downloads'} **/
/** Parameters found in function get_edd_downloads(): {"post": ["search"]} **/
function get_edd_downloads()
	{
		check_ajax_referer('sbr-admin', 'nonce');

		if (! self::require_pro_version('EDD')) {
			return;
		}

		if (! sbr_current_user_can('manage_reviews_feed_options')) {
			wp_send_json([ 'error' => 'api_error', 'message' => 'Unauthorized access' ]);
			return;
		}

		// Strict gate (matches Util::get_providers UI tile). is_edd_active()
		// is intentionally retained on the display side; see EDD::is_active_static.
		if (! EDD::is_active_static()) {
			wp_send_json([ 'error' => 'api_error', 'message' => 'EDD or EDD Reviews is not active' ]);
			return;
		}
		$edd = new EDD();

		$search    = isset($_POST['search']) ? sanitize_text_field(wp_unslash($_POST['search'])) : '';
		$downloads = $edd->get_sb_edd_downloads($search);

		wp_send_json_success([
			'downloads' => $downloads,
		]);
	}


/** Function ajax_preview_reviews() called by wp_ajax hooks: {'sbr_review_alert_preview_reviews'} **/
/** Parameters found in function ajax_preview_reviews(): {"post": ["settings"]} **/
function ajax_preview_reviews(): void
	{
		check_ajax_referer('sbr-admin', 'nonce');

		if (! sbr_current_user_can('manage_reviews_feed_options')) {
			wp_send_json_error(['message' => __('Unauthorized access.', 'reviews-feed')], 403);
		}

		// Get settings from POST
		$settings = isset($_POST['settings']) ? json_decode(wp_unslash($_POST['settings']), true) : [];

		if (!is_array($settings)) {
			wp_send_json_error(['message' => __('Invalid settings data.', 'reviews-feed')], 400);
		}

		// Sanitize settings
		$settings = self::sanitize_settings($settings);

		// Get reviews using the same logic as frontend
		$result = self::get_preview_reviews($settings);

		wp_send_json_success([
			'reviews'         => $result['reviews'],
			'totalReviews'    => $result['totalReviews'],
			'unfilteredTotal' => $result['unfilteredTotal'],
			'averageRating'   => $result['averageRating'],
			// SMASH-782: booking-only alerts show Booking's native 0-10 score + word.
			'bookingHeader'   => $result['bookingHeader'] ?? null,
		]);
	}


/** Function SmashBalloon\Reviews\Common\Services\SBR_Upgrader() called by wp_ajax hooks: {'sbr_maybe_upgrade_redirect', 'nopriv_sbr_run_one_click_upgrade'} **/
/** No function found :-/ **/


/** Function dismiss() called by wp_ajax hooks: {'sbr_dashboard_notification_dismiss'} **/
/** Parameters found in function dismiss(): {"get": ["sbr_ignore_rating_notice_nag", "sbr_nonce", "sbr_ignore_new_user_sale_notice", "sbr_ignore_bfcm_sale_notice", "sbr_dismiss"]} **/
function dismiss()
	{
		global $current_user;
		$user_id             = $current_user->ID;
		$sbr_statuses_option = get_option('sbr_statuses', array());

		if (isset($_GET['sbr_ignore_rating_notice_nag'])) {
			$rating_ignore = false;
			if (isset($_GET['sbr_nonce']) && wp_verify_nonce($_GET['sbr_nonce'], 'sbr-review')) {
				$rating_ignore = isset($_GET['sbr_ignore_rating_notice_nag']) ? sanitize_text_field($_GET['sbr_ignore_rating_notice_nag']) : false;
			}
			if (1 === (int) $rating_ignore) {
				update_option('sbr_rating_notice', 'dismissed', false);
				$sbr_statuses_option['rating_notice_dismissed'] = sbr_get_current_time();
				update_option('sbr_statuses', $sbr_statuses_option, false);
			} elseif ('later' === $rating_ignore) {
				set_transient('reviews_feed_rating_notice_waiting', 'waiting', 2 * WEEK_IN_SECONDS);
				delete_option('sbr_review_consent');
				update_option('sbr_rating_notice', 'pending', false);
			}
		}

		if (isset($_GET['sbr_ignore_new_user_sale_notice'])) {
			$new_user_ignore = false;
			if (isset($_GET['sbr_nonce']) && wp_verify_nonce($_GET['sbr_nonce'], 'sbr-discount')) {
				$new_user_ignore = isset($_GET['sbr_ignore_new_user_sale_notice']) ? sanitize_text_field($_GET['sbr_ignore_new_user_sale_notice']) : false;
			}
			if ('always' === $new_user_ignore) {
				update_user_meta($user_id, 'sbr_ignore_new_user_sale_notice', 'always');

				$current_month_number  = (int) date('n', sbr_get_current_time());
				$not_early_in_the_year = ( $current_month_number > 5 );

				if ($not_early_in_the_year) {
					update_user_meta($user_id, 'sbr_ignore_bfcm_sale_notice', date('Y', sbr_get_current_time()));
				}
			}
		}

		if (isset($_GET['sbr_ignore_bfcm_sale_notice'])) {
			$bfcm_ignore = false;
			if (isset($_GET['sbr_nonce']) && wp_verify_nonce($_GET['sbr_nonce'], 'sbr-bfcm')) {
				$bfcm_ignore = isset($_GET['sbr_ignore_bfcm_sale_notice']) ? sanitize_text_field($_GET['sbr_ignore_bfcm_sale_notice']) : false;
			}
			if ('always' === $bfcm_ignore) {
				update_user_meta($user_id, 'sbr_ignore_bfcm_sale_notice', 'always');
			} elseif (date('Y', sbr_get_current_time()) === $bfcm_ignore) {
				update_user_meta($user_id, 'sbr_ignore_bfcm_sale_notice', date('Y', sbr_get_current_time()));
			}
			update_user_meta($user_id, 'sbr_ignore_new_user_sale_notice', 'always');
		}

		if (isset($_GET['sbr_dismiss'])) {
			$notice_dismiss = false;
			if (isset($_GET['sbr_nonce']) && wp_verify_nonce($_GET['sbr_nonce'], 'sbr-notice-dismiss')) {
				$notice_dismiss = sanitize_text_field($_GET['sbr_dismiss']);
			}
			if ('review' === $notice_dismiss) {
				update_option('sbr_rating_notice', 'dismissed', false);
				$sbr_statuses_option['rating_notice_dismissed'] = sbr_get_current_time();
				update_option('sbr_statuses', $sbr_statuses_option, false);

				update_user_meta($user_id, 'sbr_ignore_new_user_sale_notice', 'always');
			} elseif ('discount' === $notice_dismiss) {
				$current_month_number  = (int) date('n', sbr_get_current_time());
				$not_early_in_the_year = ( $current_month_number > 5 );

				if ($not_early_in_the_year) {
					update_user_meta($user_id, 'sbr_ignore_bfcm_sale_notice', date('Y', sbr_get_current_time()));
				}

				update_user_meta($user_id, 'sbr_ignore_new_user_sale_notice', 'always');
			}
		}
	}


/** Function delete_temp_user_ajax_call() called by wp_ajax hooks: {'sbr_delete_temp_user'} **/
/** Parameters found in function delete_temp_user_ajax_call(): {"post": ["userId"]} **/
function delete_temp_user_ajax_call()
	{
		check_ajax_referer('sbr-admin', 'nonce');
		$cap = current_user_can('manage_reviews_feed_options') ? 'manage_reviews_feed_options' : 'manage_options';
		$cap = apply_filters('sbr_settings_pages_capability', $cap);
		if (!current_user_can($cap)) {
			wp_send_json_error(); // This auto-dies.
		}

		if (!isset($_POST['userId'])) {
			wp_send_json_error();
		}

		$user_id = sanitize_key($_POST['userId']);
		$return = SBR_Support_Tool::delete_temporary_user($user_id);
		echo wp_json_encode($return);
		wp_die();
	}


/** Function add_airbnb_source() called by wp_ajax hooks: {'sbr_add_airbnb_source'} **/
/** Parameters found in function add_airbnb_source(): {"post": ["listing_url"]} **/
function add_airbnb_source()
	{
		check_ajax_referer('sbr-admin', 'nonce');

		// Pro version required for Airbnb sources
		if (!self::require_pro_version('Airbnb')) {
			return;
		}

		if (!sbr_current_user_can('manage_reviews_feed_options')) {
			wp_send_json(['error' => 'api_error', 'message' => 'Unauthorized access']);
			return;
		}

		if (!isset($_POST['listing_url'])) {
			wp_send_json(['error' => 'api_error', 'message' => 'Listing URL is required']);
			return;
		}

		$listing_url = sanitize_text_field($_POST['listing_url']);
		$listing_id = Airbnb::extract_listing_id($listing_url);

		if (!$listing_id) {
			wp_send_json(['error' => 'api_error', 'message' => 'Invalid Airbnb listing URL. Please provide a valid Airbnb listing URL like: https://www.airbnb.com/rooms/123456789']);
			return;
		}

		// Check if source already exists - return existing source without API call
		$existing_source = self::get_existing_source($listing_id, 'airbnb');
		if ($existing_source) {
			self::respond_with_existing_source($existing_source, 'Airbnb');
			return;
		}

		try {
			// Use SBRelay to fetch normalized data from Relay API
			$relay = new SBRelay();

			// SMASH-782 Phase 1: call /sources/<provider> BEFORE /reviews/<provider>.
			// The relay's reviews route is gated by the source-must-exist check
			// (post-SMASH-1360 quota hardening). Calling reviews first returns
			// `reviewsSourceNotCreated`.
			$source_response = $relay->callProvider('airbnb', [
				'propertyId' => $listing_id
			], 'source', 'GET');

			// Surface upstream source-call errors so the customer sees the real cause
			// instead of the downstream `reviewsSourceNotCreated`. See the AliExpress
			// handler for the response-shape rationale.
			if (isset($source_response['success']) && $source_response['success'] === false) {
				// `SBRelay::call()` unwraps the `{success:false, data:{...}}` envelope on
				// error responses (Integrations/SBRelay.php:357 — returns `$body['data']`
				// when `$body['data']['id']` is set), so `apiMessage` lands at the TOP
				// level of `$source_response`. Read the flat key first; the nested form
				// stays as a defensive fallback for any path that doesn't unwrap.
				$upstream_msg = $source_response['apiMessage']
					?? $source_response['data']['apiMessage']
					?? $source_response['message']
					?? 'Unable to fetch Airbnb listing info from upstream.';
				wp_send_json(['error' => 'api_error', 'message' => $upstream_msg]);
				return;
			}

			$source_info_from_source = isset($source_response['info']) && is_array($source_response['info'])
				? $source_response['info']
				: [];

			// Fetch reviews from the listing via Relay API.
			// SMASH-782 Phase 1: include `place_id` so the relay's reviews
			// middleware can find the source row (see AliExpress handler for
			// the middleware-ordering rationale).
			$response = $relay->callProvider('airbnb', [
				'propertyId' => $listing_id,
				'place_id' => $listing_id
			], 'reviews', 'GET');

			// Validate response structure - proxy returns { reviews: [], info: {} }
			if (!isset($response['reviews']) || !isset($response['info'])) {
				$error_message = 'Unable to fetch reviews. Unexpected API response format.';
				if (isset($response['message'])) {
					$error_message .= ' API Error: ' . $response['message'];
				}
				wp_send_json(['error' => 'api_error', 'message' => $error_message]);
				return;
			}

			// Prefer source-endpoint info (has richer fields like name/image)
			// over reviews-endpoint info; fall back to either for any missing key.
			$normalized_reviews = $response['reviews'];
			$reviews_info = is_array($response['info']) ? $response['info'] : [];
			// Drop only null / empty-string keys (so missing source fields fall
			// back to reviews-info), but KEEP legitimate falsy values like 0 or
			// false (e.g. a genuine 0 rating) instead of stripping them.
			$source_info = array_filter($source_info_from_source, function ($v) {
				return $v !== null && $v !== '';
			}) + $reviews_info;
			$review_count = count($normalized_reviews);

			$source_data = [
				'id' => $listing_id,
				'provider' => 'airbnb',
				'name' => $source_info['name'] ?? 'Airbnb Listing ' . $listing_id,
				'url' => $source_info['url'] ?? 'https://www.airbnb.com/rooms/' . $listing_id,
				'image' => $source_info['image'] ?? '',
				'rating' => $source_info['rating'] ?? 0,
				'review_count' => $source_info['review_count'] ?? $review_count,
				'account_id' => $listing_id,
				'access_token' => '', // No API key needed - handled by proxy
				'info' => json_encode([
					'id' => $listing_id,
					'name' => $source_info['name'] ?? 'Airbnb Listing ' . $listing_id,
					'url' => $source_info['url'] ?? 'https://www.airbnb.com/rooms/' . $listing_id,
					'image' => $source_info['image'] ?? '',
					'rating' => $source_info['rating'] ?? 0,
					'total_rating' => $source_info['review_count'] ?? $review_count,
					'review_count' => $source_info['review_count'] ?? $review_count,
					// Location (e.g. "Rome") from the relay's getPropertyDetails. Persisted
					// so the sources-list subtitle shows the address instead of falling back
					// to the generic "airbnb Reviews" (Source.js:123 reads info.address).
					'address' => $source_info['address'] ?? '',
					'provider' => 'airbnb'
				]),
				'error' => '',
				'expires' => date('Y-m-d H:i:s', strtotime('+30 days')),
				'last_updated' => current_time('mysql'),
				'author' => get_current_user_id()
			];

			SBR_Sources::update_or_insert($source_data);

			// Cache reviews - already normalized by proxy
			if (!empty($normalized_reviews)) {
				self::cache_reviews($normalized_reviews, 'airbnb', $listing_id);
			}

			// Schedule bulk update if there are more reviews to fetch
			// Airbnb typically returns ~50 reviews per page
			$total_reviews = $source_info['review_count'] ?? $review_count;
			self::maybe_schedule_bulk_update($listing_id, 'airbnb', $total_reviews, 50);

			wp_send_json([
				'success' => true,
				'message' => 'addedSource',
				'source' => $source_data,
				'sourcesList' => SBR_Sources::get_sources_list(),
				'sourcesCount' => SBR_Sources::get_sources_count()
			]);
		} catch (\Exception $e) {
			wp_send_json(['error' => 'api_error', 'message' => $e->getMessage()]);
		}
	}


/** Function review_notice_consent() called by wp_ajax hooks: {'sbr_review_notice_consent_update'} **/
/** Parameters found in function review_notice_consent(): {"post": ["consent"]} **/
function review_notice_consent()
	{
		// Security Checks
		check_ajax_referer('sbr-admin', 'sbr_nonce');
		if (! sbr_current_user_can('manage_reviews_feed_options')) {
			wp_send_json_error(); // This auto-dies.
		}

		$consent = isset($_POST['consent']) ? sanitize_text_field($_POST['consent']) : '';

		update_option('sbr_review_consent', $consent);

		if ($consent == 'no') {
			$sbr_statuses_option = get_option('sbr_statuses', array());
			update_option('sbr_rating_notice', 'dismissed', false);
			$sbr_statuses_option['rating_notice_dismissed'] = sbr_get_current_time();
			update_option('sbr_statuses', $sbr_statuses_option, false);
		}
		wp_die();
	}


/** Function dismiss_critical_notice() called by wp_ajax hooks: {'sbr_dismiss_critical_notice'} **/
/** No function found :-/ **/


/** Function add_woocommerce_source_multi() called by wp_ajax hooks: {'sbr_add_woocommerce_source_multi'} **/
/** Parameters found in function add_woocommerce_source_multi(): {"post": ["product_ids", "category_ids", "tag_ids", "source_name"]} **/
function add_woocommerce_source_multi()
	{
		check_ajax_referer('sbr-admin', 'nonce');

		// Pro version required for WooCommerce sources
		if (!self::require_pro_version('WooCommerce')) {
			return;
		}

		if (!sbr_current_user_can('manage_reviews_feed_options')) {
			wp_send_json(['error' => 'api_error', 'message' => 'Unauthorized access']);
			return;
		}

		// Maximum allowed items per selection type (prevent memory exhaustion)
		$max_items = 100;

		// Get product_ids, category_ids, and tag_ids (all optional but at least one required).
		$direct_product_ids = isset($_POST['product_ids']) && is_array($_POST['product_ids']) ? array_map('absint', $_POST['product_ids']) : [];
		$category_ids       = isset($_POST['category_ids']) && is_array($_POST['category_ids']) ? array_map('absint', $_POST['category_ids']) : [];
		$tag_ids            = isset($_POST['tag_ids']) && is_array($_POST['tag_ids']) ? array_map('absint', $_POST['tag_ids']) : [];

		// Enforce maximum limits to prevent memory exhaustion attacks
		if (count($direct_product_ids) > $max_items || count($category_ids) > $max_items || count($tag_ids) > $max_items) {
			wp_send_json([ 'error' => 'api_error', 'message' => sprintf('Maximum %d items allowed per selection type', $max_items) ]);
			return;
		}

		// Filter out zeros.
		$direct_product_ids = array_filter($direct_product_ids);
		$category_ids       = array_filter($category_ids);
		$tag_ids            = array_filter($tag_ids);

		// Validate at least one selection type is provided.
		if (empty($direct_product_ids) && empty($category_ids) && empty($tag_ids)) {
			wp_send_json([ 'error' => 'api_error', 'message' => 'At least one product, category, or tag must be selected' ]);
			return;
		}

		// Validate source_name is provided
		if (!isset($_POST['source_name']) || empty(trim($_POST['source_name']))) {
			wp_send_json(['error' => 'api_error', 'message' => 'Source name is required']);
			return;
		}

		// Check if WooCommerce is active
		if (!class_exists('WooCommerce')) {
			wp_send_json(['error' => 'api_error', 'message' => 'WooCommerce is not active']);
			return;
		}

		$source_name = sanitize_text_field($_POST['source_name']);

		// Resolve category IDs to product IDs.
		$category_product_ids = ! empty($category_ids) ? self::get_products_by_categories($category_ids) : [];

		// Resolve tag IDs to product IDs.
		$tag_product_ids = ! empty($tag_ids) ? self::get_products_by_tags($tag_ids) : [];

		// Merge all product IDs, remove duplicates, and apply limit.
		$merge_result = self::merge_and_limit_product_ids($direct_product_ids, $category_product_ids, $tag_product_ids);
		$product_ids = $merge_result['product_ids'];

		// Ensure we have at least one product after resolution.
		if (empty($product_ids)) {
			wp_send_json([ 'error' => 'api_error', 'message' => 'No products found for the selected categories/tags' ]);
			return;
		}

		// Generate a unique source ID for multi-product source
		$source_id = 'wc_multi_' . md5(implode('_', $product_ids) . '_' . time());

		// Validate all products and collect their data
		$products_info = [];
		$total_review_count = 0;
		$weighted_rating_sum = 0;

		if (!function_exists('wc_get_product')) {
			wp_send_json(['error' => 'api_error', 'message' => 'WooCommerce plugin is not active.']);
			return;
		}

		foreach ($product_ids as $product_id) {
			$product = wc_get_product($product_id);
			if (!$product) {
				wp_send_json([
					'error' => 'api_error',
					'message' => sprintf('Product with ID %d not found', $product_id)
				]);
				return;
			}

			$review_count = $product->get_review_count();
			$average_rating = floatval($product->get_average_rating());
			$image_id = $product->get_image_id();
			$image_url = $image_id ? wp_get_attachment_image_url($image_id, 'thumbnail') : '';

			$products_info[] = [
				'id' => $product_id,
				'name' => $product->get_name(),
				'sku' => $product->get_sku(),
				'price' => $product->get_price(),
				'price_html' => $product->get_price_html(),
				'image' => $image_url,
				'url' => get_permalink($product_id),
				'review_count' => $review_count,
				'average_rating' => $average_rating
			];

			$total_review_count += $review_count;
			// Use weighted sum: rating * review_count for proper weighted average
			$weighted_rating_sum += $average_rating * $review_count;
		}

		// Calculate weighted average rating across all products
		$average_rating = $total_review_count > 0 ? round($weighted_rating_sum / $total_review_count, 1) : 0;

		// Use first product's image as the source image, or empty if none
		$source_image = !empty($products_info[0]['image']) ? $products_info[0]['image'] : '';

		// Build categories info for storage.
		$categories_info = [];
		if (! empty($category_ids)) {
			foreach ($category_ids as $cat_id) {
				$term = get_term($cat_id, 'product_cat');
				if ($term && ! is_wp_error($term)) {
					$categories_info[] = [
						'id'            => $term->term_id,
						'name'          => $term->name,
						'slug'          => $term->slug,
						'product_count' => $term->count,
					];
				}
			}
		}

		// Build tags info for storage.
		$tags_info = [];
		if (! empty($tag_ids)) {
			foreach ($tag_ids as $tag_id) {
				$term = get_term($tag_id, 'product_tag');
				if ($term && ! is_wp_error($term)) {
					$tags_info[] = [
						'id'            => $term->term_id,
						'name'          => $term->name,
						'slug'          => $term->slug,
						'product_count' => $term->count,
					];
				}
			}
		}

		// Create source data
		$source_data = [
			'id' => $source_id,
			'provider' => 'woocommerce',
			'name' => $source_name,
			'url' => '', // No single URL for multi-product source
			'image' => $source_image,
			'rating' => $average_rating,
			'review_count' => $total_review_count,
			'account_id' => $source_id,
			'access_token' => '', // Not needed for WooCommerce
			'info' => json_encode([
				'type' => 'multi_product',
				'source_name' => $source_name,
				'products' => $products_info,
				'product_ids' => array_values($product_ids), // Re-index array
				'direct_product_ids' => array_values($direct_product_ids), // Products selected directly
				'category_ids' => array_values($category_ids), // Categories selected
				'tag_ids' => array_values($tag_ids), // Tags selected
				'categories' => $categories_info, // Category details for display
				'tags' => $tags_info, // Tag details for display
				'direct_products' => array_values(array_filter($products_info, function ($p) use ($direct_product_ids) {
					return in_array($p['id'], $direct_product_ids);
				})), // Product details for directly selected products
				'product_count' => count($products_info),
				'total_rating' => $total_review_count,
				'review_count' => $total_review_count,
				'average_rating' => $average_rating,
				'provider' => 'woocommerce'
			]),
			'error' => '',
			'expires' => date('Y-m-d H:i:s', strtotime('+1 year')),
			'last_updated' => current_time('mysql'),
			'author' => get_current_user_id()
		];

		// Save source
		SBR_Sources::update_or_insert($source_data);

		// Fetch initial 20 reviews across all products in a single query
		// This is much faster than fetching from each product individually
		$woocommerce = new WooCommerce();
		$reviews = $woocommerce->fetch_reviews_multi($product_ids, 20, 0);
		$normalized_reviews = $woocommerce->normalize_reviews_multi($reviews);

		// Cache reviews under the multi-product source ID
		if (! empty($normalized_reviews)) {
			self::cache_reviews($normalized_reviews, 'woocommerce', $source_id);
		}

		// Schedule bulk update if there are more reviews to fetch (beyond initial 20)
		if ($total_review_count > 20) {
			$bulk_update = new \SmashBalloon\Reviews\Pro\Services\BulkUpdate\Bulk_WooCommerce_Reviews_Update();
			$bulk_update->schedule_task([
				'source_id'   => $source_id,
				'product_ids' => $product_ids,
				'type'        => 'multi_product',
			]);
		}

		// Return success response
		wp_send_json([
			'success' => true,
			'message' => 'addedSource',
			'source' => $source_data,
			'sourcesList' => SBR_Sources::get_sources_list(),
			'sourcesCount' => SBR_Sources::get_sources_count()
		]);
	}


/** Function SmashBalloon\Reviews\Common\Helpers\SBR_Error_Handler() called by wp_ajax hooks: {'sbr_clear_error_logs'} **/
/** No function found :-/ **/


/** Function add_booking_source() called by wp_ajax hooks: {'sbr_add_booking_source'} **/
/** Parameters found in function add_booking_source(): {"post": ["hotel_url"]} **/
function add_booking_source()
	{
		check_ajax_referer('sbr-admin', 'nonce');

		// Pro version required for Booking.com sources
		if (!self::require_pro_version('Booking.com')) {
			return;
		}

		if (!sbr_current_user_can('manage_reviews_feed_options')) {
			wp_send_json(['error' => 'api_error', 'message' => 'Unauthorized access']);
			return;
		}

		if (!isset($_POST['hotel_url'])) {
			wp_send_json(['error' => 'api_error', 'message' => 'Hotel URL is required']);
			return;
		}

		$hotel_url = sanitize_text_field($_POST['hotel_url']);
		$hotel_id = BookingCom::extract_hotel_id($hotel_url);

		// Initialize SBRelay early - we'll need it either way
		$relay = new SBRelay();

		// If no numeric hotel_id found, try to resolve from URL slug
		if (!$hotel_id && BookingCom::needsResolution($hotel_url)) {
			$url_components = BookingCom::extractUrlComponents($hotel_url);

			if (!$url_components) {
				wp_send_json(['error' => 'api_error', 'message' => 'Could not parse the Booking.com URL. Please check the URL format.']);
				return;
			}

			try {
				// Call resolve endpoint to get hotel_id from name/country. Send the
				// slug too: the relay matches <cc>/<slug> exactly against search
				// results BEFORE the fuzzy name match, which resolves the precise
				// hotel even when its display name differs from the slug (names are
				// not unique). Older relays ignore the extra field.
				$resolve_response = $relay->callProvider('booking', [
					'hotel_name' => $url_components['hotel_name'],
					'country' => $url_components['country'],
					'slug' => $url_components['slug'] ?? '',
					'dest_id' => $url_components['dest_id'] ?? '',
					'dest_type' => $url_components['dest_type'] ?? '',
				], 'resolve', 'POST');

				// Check if resolution was successful
				if (isset($resolve_response['hotel_id'])) {
					$hotel_id = $resolve_response['hotel_id'];
				} else {
					// A generic upstream message ("Something went wrong!" / empty) reads as a
					// dev dead end; give the customer an actionable next step instead of
					// surfacing it verbatim. Only append the relay message when it's specific.
					$relay_msg = isset($resolve_response['message']) ? trim((string) $resolve_response['message']) : '';
					$generic   = ['', 'something went wrong', 'something went wrong!', 'error', 'unknown error', 'failed'];
					$actionable = "We couldn't match this link to a specific Booking.com hotel. "
						. "Two options that always work: open the hotel on Booking.com and copy the URL after clicking it from the search results (that link carries the hotel ID), "
						. "or paste the numeric hotel ID directly into this field.";
					$error_msg = in_array(strtolower($relay_msg), $generic, true)
						? $actionable
						: 'Could not find hotel. ' . $relay_msg . ' ' . $actionable;
					wp_send_json(['error' => 'api_error', 'message' => $error_msg]);
					return;
				}
			} catch (\Exception $e) {
				wp_send_json(['error' => 'api_error', 'message' => 'Failed to resolve hotel: ' . $e->getMessage()]);
				return;
			}
		}

		// Final validation - we must have a hotel_id at this point
		if (!$hotel_id) {
			wp_send_json(['error' => 'api_error', 'message' => 'Could not extract or resolve hotel ID. Please provide a valid Booking.com URL or the numeric hotel ID directly.']);
			return;
		}

		// Check if source already exists - return existing source without API call
		$existing_source = self::get_existing_source($hotel_id, 'booking');
		if ($existing_source) {
			self::respond_with_existing_source($existing_source, 'Booking.com');
			return;
		}

		try {
			// SMASH-782 Phase 1: call /sources/<provider> BEFORE /reviews/<provider>.
			// The relay's reviews route is gated by the source-must-exist check
			// (post-SMASH-1360 quota hardening). Calling reviews first returns
			// `reviewsSourceNotCreated`.
			$source_response = $relay->callProvider('booking', [
				'hotel_id' => $hotel_id,
				'locale' => 'en-gb',
			], 'source', 'GET');

			// Surface upstream source-call errors so the customer sees the real cause
			// instead of the downstream `reviewsSourceNotCreated`. See the AliExpress
			// handler for the response-shape rationale.
			if (isset($source_response['success']) && $source_response['success'] === false) {
				// See airbnb handler comment — `SBRelay::call()` unwraps the data
				// envelope on error, so `apiMessage` is at the top level.
				$upstream_msg = $source_response['apiMessage']
					?? $source_response['data']['apiMessage']
					?? $source_response['message']
					?? 'Unable to fetch Booking.com hotel info from upstream.';
				wp_send_json(['error' => 'api_error', 'message' => $upstream_msg]);
				return;
			}

			$source_info_from_source = isset($source_response['info']) && is_array($source_response['info'])
				? $source_response['info']
				: [];

			// SMASH-782 — exact-match guard (fail closed). When the user supplied a
			// hotel-page URL, the hotel we just fetched MUST be that exact hotel:
			// its canonical Booking.com slug has to equal the URL's slug. This is
			// the single guarantee that a wrong/fuzzy id can never import a
			// different hotel's reviews. The decision (numeric-ID input skips it,
			// empty/mismatched canonical rejects) lives in the unit-tested
			// BookingCom::sourceUrlHotelMismatch().
			$canonical_url = isset($source_info_from_source['canonical_url']) && is_string($source_info_from_source['canonical_url'])
				? $source_info_from_source['canonical_url']
				: '';
			if (BookingCom::sourceUrlHotelMismatch($hotel_url, $canonical_url)) {
				wp_send_json(['error' => 'api_error', 'message' => "We couldn't confirm this is the exact hotel from your link. Please paste the Booking.com hotel page URL again, or enter the numeric hotel ID."]);
				return; // defense-in-depth: matches every other guard here even though wp_send_json exits
			}

			// Fetch hotel info and reviews via Relay API (GET request).
			// SMASH-782 Phase 1: include `place_id` so the relay's reviews
			// middleware can find the source row (see AliExpress handler for
			// the middleware-ordering rationale).
			$response = $relay->callProvider('booking', [
				'hotel_id' => $hotel_id,
				'place_id' => $hotel_id,
				'sort_type' => 'SORT_MOST_RELEVANT',
				'page_number' => 0,
				'locale' => 'en-gb'
			], 'reviews', 'GET');

			// Validate response structure - proxy returns { reviews: [], info: {} }
			if (!isset($response['reviews']) || !isset($response['info'])) {
				$error_msg = 'Unable to fetch reviews. Unexpected API response format.';
				if (isset($response['message'])) {
					$error_msg .= ' API Message: ' . $response['message'];
				}
				wp_send_json(['error' => 'api_error', 'message' => $error_msg]);
				return;
			}

			// Prefer source-endpoint info (has richer fields like name/image)
			// over reviews-endpoint info; fall back to either for any missing key.
			$normalized_reviews = $response['reviews'];
			$reviews_info = is_array($response['info']) ? $response['info'] : [];
			// Drop only null / empty-string keys (so missing source fields fall
			// back to reviews-info), but KEEP legitimate falsy values like 0 or
			// false (e.g. a genuine 0 rating) instead of stripping them.
			$source_info = array_filter($source_info_from_source, function ($v) {
				return $v !== null && $v !== '';
			}) + $reviews_info;
			$review_count = count($normalized_reviews);

			// Use hotel name from URL if available, otherwise use proxy info
			$hotel_name_from_url = BookingCom::extractHotelNameFromUrl($hotel_url);
			$hotel_name = $hotel_name_from_url ?: ($source_info['name'] ?? 'Booking.com Property ' . $hotel_id);

			$source_data = [
				'id' => $hotel_id,
				'provider' => 'booking',
				'name' => $hotel_name,
				'url' => $source_info['url'] ?? 'https://www.booking.com/hotel/index.html?hotel_id=' . $hotel_id,
				'image' => $source_info['image'] ?? '',
				'rating' => $source_info['rating'] ?? 0,
				'review_count' => $source_info['review_count'] ?? $review_count,
				'account_id' => $hotel_id,
				'access_token' => '', // No API key needed - handled by proxy
				'info' => json_encode([
					'id' => $hotel_id,
					'name' => $hotel_name,
					'url' => $source_info['url'] ?? 'https://www.booking.com/hotel/index.html?hotel_id=' . $hotel_id,
					'image' => $source_info['image'] ?? '',
					'rating' => $source_info['rating'] ?? 0,
					'total_rating' => $source_info['review_count'] ?? $review_count,
					'review_count' => $source_info['review_count'] ?? $review_count,
					'provider' => 'booking',
					'address' => $source_info['address'] ?? '',
					'city' => $source_info['city'] ?? '',
					'country' => $source_info['country'] ?? '',
				]),
				'error' => '',
				'expires' => date('Y-m-d H:i:s', strtotime('+30 days')),
				'last_updated' => current_time('mysql'),
				'author' => get_current_user_id()
			];

			SBR_Sources::update_or_insert($source_data);

			// Cache reviews - already normalized by proxy
			if (!empty($normalized_reviews)) {
				self::cache_reviews($normalized_reviews, 'booking', $hotel_id);
			}

			// Schedule bulk update if there are more reviews to fetch
			// Booking.com returns ~25 reviews per page
			$total_reviews = $source_info['review_count'] ?? $review_count;
			self::maybe_schedule_bulk_update($hotel_id, 'booking', $total_reviews, 25);

			// Return flat structure to match other providers
			wp_send_json([
				'success' => true,
				'message' => 'addedSource',
				'source' => $source_data,
				'sourcesList' => SBR_Sources::get_sources_list(),
				'sourcesCount' => SBR_Sources::get_sources_count()
			]);
		} catch (\Exception $e) {
			wp_send_json(['error' => 'api_error', 'message' => $e->getMessage()]);
		}
	}


/** Function create_temp_user_ajax_call() called by wp_ajax hooks: {'sbr_create_temp_user'} **/
/** No params detected :-/ **/


/** Function ajax_save() called by wp_ajax hooks: {'sbr_review_alert_save'} **/
/** Parameters found in function ajax_save(): {"post": ["id", "name", "settings"]} **/
function ajax_save(): void
	{
		check_ajax_referer('sbr-admin', 'nonce');

		if (! sbr_current_user_can('manage_reviews_feed_options')) {
			wp_send_json_error(['message' => __('Unauthorized access.', 'reviews-feed')], 403);
		}

		$id = isset($_POST['id']) ? absint($_POST['id']) : 0;
		$name = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
		$settings = isset($_POST['settings']) ? json_decode(wp_unslash($_POST['settings']), true) : [];

		if (!is_array($settings)) {
			wp_send_json_error(['message' => __('Invalid settings data.', 'reviews-feed')], 400);
		}

		// Enforce tier restrictions
		$is_pro = Util::sbr_is_pro();
		$is_pro_plus = Util::sbr_is_pro_plus();

		// Check popup limit for non-Pro Plus users (only 1 popup allowed)
		if ($id === 0 && !$is_pro_plus) {
			$existing = self::get_popups(['posts_per_page' => 1]);
			if ($existing['total'] >= 1) {
				wp_send_json_error([
					'message' => __('Upgrade to Pro Plus to create multiple review alerts.', 'reviews-feed'),
					'upsell_key' => 'reviewAlertMultiple',
				], 403);
			}
		}

		// Enforce Pro-only settings for free users
		if (!$is_pro) {
			// Free users can only use 'light' theme (dark theme is Pro)
			if (isset($settings['theme']) && $settings['theme'] !== 'light') {
				$settings['theme'] = 'light';
			}
			// Free users can only use 'aggregate' popup type
			if (isset($settings['popup_type']) && $settings['popup_type'] !== 'aggregate') {
				$settings['popup_type'] = 'aggregate';
			}
		}

		// Enforce Pro Plus-only settings
		if (!$is_pro_plus) {
			// Non-Pro Plus users cannot hide branding
			if (isset($settings['content']['show_powered_by'])) {
				$settings['content']['show_powered_by'] = true;
			}
			if (isset($settings['review_feed']['show_powered_by'])) {
				$settings['review_feed']['show_powered_by'] = true;
			}
		}

		$result = self::save_popup([
			'id'       => $id,
			'name'     => $name,
			'settings' => $settings,
		]);

		if (is_wp_error($result)) {
			wp_send_json_error(['message' => $result->get_error_message()], 400);
		}

		$popup = self::get_popup($result);

		wp_send_json_success([
			'popup'   => $popup,
			'message' => $id > 0 ? __('Popup updated.', 'reviews-feed') : __('Popup created.', 'reviews-feed'),
		]);
	}


/** Function update_edd_source_multi() called by wp_ajax hooks: {'sbr_update_edd_source_multi'} **/
/** Parameters found in function update_edd_source_multi(): {"post": ["source_id", "download_ids", "category_ids", "tag_ids", "source_name"]} **/
function update_edd_source_multi()
	{
		check_ajax_referer('sbr-admin', 'nonce');

		if (! self::require_pro_version('EDD')) {
			return;
		}

		if (! sbr_current_user_can('manage_reviews_feed_options')) {
			wp_send_json([ 'error' => 'api_error', 'message' => 'Unauthorized access' ]);
			return;
		}

		// Strict gate (matches Util::get_providers UI tile). is_edd_active()
		// is intentionally retained on the display side; see EDD::is_active_static.
		if (! EDD::is_active_static()) {
			wp_send_json([ 'error' => 'api_error', 'message' => 'EDD or EDD Reviews is not active' ]);
			return;
		}
		$edd = new EDD();

		$source_id = isset($_POST['source_id']) ? sanitize_text_field(wp_unslash($_POST['source_id'])) : '';
		if (empty($source_id)) {
			wp_send_json([ 'error' => 'api_error', 'message' => 'Source ID is required' ]);
			return;
		}

		// Get current source
		$current_source = SBR_Sources::get_single_source_info([
			'id'       => $source_id,
			'provider' => 'edd'
		]);

		if (! $current_source) {
			wp_send_json([ 'error' => 'api_error', 'message' => 'Source not found' ]);
			return;
		}

		// Get old download IDs for comparison
		$old_info = ! empty($current_source['info']) ? json_decode($current_source['info'], true) : [];
		$old_download_ids = $old_info['download_ids'] ?? [];

		// Maximum allowed items per selection type (prevent memory exhaustion)
		$max_items = 100;

		// Get new selection data (download_ids, category_ids, tag_ids - all optional but at least one required).
		// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- array_map with absint sanitizes the values
		$direct_download_ids = isset($_POST['download_ids']) && is_array($_POST['download_ids']) ? array_map('absint', $_POST['download_ids']) : [];
		$category_ids        = isset($_POST['category_ids']) && is_array($_POST['category_ids']) ? array_map('absint', $_POST['category_ids']) : [];
		$tag_ids             = isset($_POST['tag_ids']) && is_array($_POST['tag_ids']) ? array_map('absint', $_POST['tag_ids']) : [];
		// phpcs:enable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		// Enforce maximum limits to prevent memory exhaustion attacks
		if (count($direct_download_ids) > $max_items || count($category_ids) > $max_items || count($tag_ids) > $max_items) {
			wp_send_json([ 'error' => 'api_error', 'message' => sprintf('Maximum %d items allowed per selection type', $max_items) ]);
			return;
		}

		// Filter out zeros.
		$direct_download_ids = array_filter($direct_download_ids);
		$category_ids        = array_filter($category_ids);
		$tag_ids             = array_filter($tag_ids);

		// Validate at least one selection type is provided.
		if (empty($direct_download_ids) && empty($category_ids) && empty($tag_ids)) {
			wp_send_json([ 'error' => 'api_error', 'message' => 'At least one download, category, or tag must be selected' ]);
			return;
		}

		$source_name = isset($_POST['source_name']) ? sanitize_text_field(wp_unslash($_POST['source_name'])) : ($old_info['source_name'] ?? __('EDD Downloads', 'reviews-feed'));

		// Resolve to download IDs
		$category_download_ids = $edd->get_downloads_by_categories($category_ids);
		$tag_download_ids      = $edd->get_downloads_by_tags($tag_ids);

		$merge_result = self::merge_and_limit_download_ids($direct_download_ids, $category_download_ids, $tag_download_ids);
		$download_ids = $merge_result['download_ids'];

		if (empty($download_ids)) {
			wp_send_json([ 'error' => 'api_error', 'message' => 'No downloads selected' ]);
			return;
		}

		// Build new downloads info
		$downloads_info      = [];
		$total_review_count  = 0;
		$weighted_rating_sum = 0;

		foreach ($download_ids as $download_id) {
			$download = get_post($download_id);
			if (! $download || $download->post_type !== 'download') {
				continue;
			}

			$stats = $edd->get_download_review_stats($download_id);
			$review_count   = $stats['review_count'];
			$average_rating = $stats['average_rating'];

			$image_id  = get_post_thumbnail_id($download_id);
			$image_url = $image_id ? wp_get_attachment_image_url($image_id, 'thumbnail') : '';

			$downloads_info[] = [
				'id'             => $download_id,
				'name'           => $download->post_title,
				'image'          => $image_url,
				'url'            => get_permalink($download_id),
				'review_count'   => $review_count,
				'average_rating' => $average_rating
			];

			$total_review_count  += $review_count;
			$weighted_rating_sum += $average_rating * $review_count;
		}

		$average_rating = $total_review_count > 0 ? round($weighted_rating_sum / $total_review_count, 1) : 0;
		$source_image = ! empty($downloads_info[0]['image']) ? $downloads_info[0]['image'] : '';

		// Build categories/tags info
		$categories_info = [];
		foreach ($category_ids as $cat_id) {
			$term = get_term($cat_id, 'download_category');
			if ($term && ! is_wp_error($term)) {
				$categories_info[] = [
					'id'             => $term->term_id,
					'name'           => $term->name,
					'slug'           => $term->slug,
					'download_count' => $term->count,
				];
			}
		}

		$tags_info = [];
		foreach ($tag_ids as $tag_id_item) {
			$term = get_term($tag_id_item, 'download_tag');
			if ($term && ! is_wp_error($term)) {
				$tags_info[] = [
					'id'             => $term->term_id,
					'name'           => $term->name,
					'slug'           => $term->slug,
					'download_count' => $term->count,
				];
			}
		}

		$source_data = [
			'id'           => $source_id,
			'provider'     => 'edd',
			'name'         => $source_name,
			'url'          => '',
			'image'        => $source_image,
			'rating'       => $average_rating,
			'review_count' => $total_review_count,
			'account_id'   => $source_id,
			'access_token' => '',
			'info'         => wp_json_encode([
				'type'              => 'multi_download',
				'source_name'       => $source_name,
				'downloads'         => $downloads_info,
				'download_ids'      => array_values($download_ids),
				'direct_download_ids' => array_values($direct_download_ids),
				'category_ids'      => array_values($category_ids),
				'tag_ids'           => array_values($tag_ids),
				'categories'        => $categories_info,
				'tags'              => $tags_info,
				'direct_downloads'  => array_values(array_filter($downloads_info, function ($d) use ($direct_download_ids) {
					return in_array($d['id'], $direct_download_ids, true);
				})),
				'download_count'    => count($downloads_info),
				'total_rating'      => $total_review_count,
				'review_count'      => $total_review_count,
				'average_rating'    => $average_rating,
				'provider'          => 'edd'
			]),
			'error'        => '',
			'expires'      => gmdate('Y-m-d H:i:s', strtotime('+1 year')),
			'last_updated' => current_time('mysql'),
			'author'       => get_current_user_id()
		];

		// Update source
		SBR_Sources::update_or_insert($source_data);

		// Find downloads that were removed
		$removed_download_ids = array_diff($old_download_ids, $download_ids);

		// Delete reviews for removed downloads
		if (! empty($removed_download_ids)) {
			global $wpdb;
			$posts_table = $wpdb->prefix . SBR_POSTS_TABLE;

			$placeholders = implode(',', array_fill(0, count($removed_download_ids), '%d'));
			// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
			$reviews_to_delete = $wpdb->get_col(
				$wpdb->prepare(
					"SELECT p.id FROM {$posts_table} p
					 INNER JOIN {$wpdb->comments} c ON p.post_id = c.comment_ID
					 WHERE p.provider_id = %s
					 AND p.provider = 'edd'
					 AND c.comment_post_ID IN ($placeholders)",
					...array_merge([ $source_id ], array_values($removed_download_ids))
				)
			);
			// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare

			if (! empty($reviews_to_delete)) {
				$delete_placeholders = implode(',', array_fill(0, count($reviews_to_delete), '%d'));
				// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
				$wpdb->query(
					$wpdb->prepare(
						"DELETE FROM {$posts_table} WHERE id IN ($delete_placeholders)",
						...$reviews_to_delete
					)
				);
				// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare

				// Clear feed cache after deleting reviews to prevent stale data
				SBR_Feed_Saver_Manager::clear_plugin_cache();
			}
		}

		// Find new downloads
		$new_download_ids = array_diff($download_ids, $old_download_ids);

		// Fetch and cache reviews for new downloads
		if (! empty($new_download_ids)) {
			$reviews = $edd->fetch_reviews_multi($new_download_ids, 20, 0);
			$normalized_reviews = $edd->normalize_reviews_multi($reviews);

			if (! empty($normalized_reviews)) {
				self::cache_reviews($normalized_reviews, 'edd', $source_id);
			}

			// Schedule bulk update if needed
			if ($total_review_count > 20) {
				$accounts_list = get_option('sbr_bulk_edd', []);
				if (isset($accounts_list[ $source_id ])) {
					$accounts_list[ $source_id ]['is_done']      = false;
					$accounts_list[ $source_id ]['offset']       = 20;
					$accounts_list[ $source_id ]['download_ids'] = $download_ids;
					update_option('sbr_bulk_edd', $accounts_list);
				}

				$bulk_update = new Bulk_EDD_Reviews_Update();
				$bulk_update->schedule_task([
					'source_id'    => $source_id,
					'download_ids' => $download_ids,
					'type'         => 'multi_download',
				]);
			}
		}

		wp_send_json([
			'success'      => true,
			'message'      => 'updatedSource',
			'source'       => $source_data,
			'sourcesList'  => SBR_Sources::get_sources_list(),
			'sourcesCount' => SBR_Sources::get_sources_count()
		]);
	}


/** Function sbr_reset_posts() called by wp_ajax hooks: {'sbr_reset_posts'} **/
/** No params detected :-/ **/


/** Function ajax_toggle_status() called by wp_ajax hooks: {'sbr_review_alert_toggle_status'} **/
/** Parameters found in function ajax_toggle_status(): {"post": ["id"]} **/
function ajax_toggle_status(): void
	{
		check_ajax_referer('sbr-admin', 'nonce');

		if (! sbr_current_user_can('manage_reviews_feed_options')) {
			wp_send_json_error(['message' => __('Unauthorized access.', 'reviews-feed')], 403);
		}

		$id = isset($_POST['id']) ? absint($_POST['id']) : 0;

		if ($id <= 0) {
			wp_send_json_error(['message' => __('Invalid popup ID.', 'reviews-feed')], 400);
		}

		// Get current popup
		$popup = self::get_popup($id);
		if (!$popup) {
			wp_send_json_error(['message' => __('Popup not found.', 'reviews-feed')], 404);
		}

		// Toggle status
		$new_status = $popup['status'] === 'active' ? 'inactive' : 'active';
		$new_post_status = $new_status === 'active' ? 'publish' : 'draft';

		// Update post status
		$result = wp_update_post([
			'ID'          => $id,
			'post_status' => $new_post_status,
		], true);

		if (is_wp_error($result)) {
			wp_send_json_error(['message' => $result->get_error_message()], 400);
		}

		// Return updated list
		$popups = self::get_popups();

		wp_send_json_success([
			'popupsList'  => $popups['popups'],
			'popupsCount' => $popups['total'],
			'newStatus'   => $new_status,
			'message'     => $new_status === 'active'
				? __('Popup activated.', 'reviews-feed')
				: __('Popup deactivated.', 'reviews-feed'),
		]);
	}


/** Function get_woocommerce_products() called by wp_ajax hooks: {'sbr_get_woocommerce_products'} **/
/** Parameters found in function get_woocommerce_products(): {"post": ["search"]} **/
function get_woocommerce_products()
	{
		check_ajax_referer('sbr-admin', 'nonce');

		// Pro version required for WooCommerce
		if (!self::require_pro_version('WooCommerce')) {
			return;
		}

		if (!sbr_current_user_can('manage_reviews_feed_options')) {
			wp_send_json(['error' => 'api_error', 'message' => 'Unauthorized access']);
			return;
		}

		if (! class_exists('WooCommerce')) {
			wp_send_json([ 'error' => 'api_error', 'message' => 'WooCommerce is not active' ]);
			return;
		}

		// Get search parameter if provided (for server-side product search)
		$search = isset($_POST['search']) ? sanitize_text_field(wp_unslash($_POST['search'])) : '';

		$woocommerce = new WooCommerce();
		$products    = $woocommerce->get_sb_woocommerce_products($search);

		wp_send_json_success([
			'products' => $products,
		]);
	}


/** Function handle_ajax() called by wp_ajax hooks: {'sb_feature_suggestion', 'sb_deactivation_feedback'} **/
/** Parameters found in function handle_ajax(): {"post": ["plugin_slug", "reason_id", "details"]} **/
function handle_ajax()
    {
        check_ajax_referer('sb_deactivation_feedback', 'nonce');
        if (!current_user_can('activate_plugins')) {
            wp_send_json_error('Unauthorized', 403);
        }
        $slug = isset($_POST['plugin_slug']) ? sanitize_key($_POST['plugin_slug']) : '';
        $reason_id = isset($_POST['reason_id']) ? sanitize_key($_POST['reason_id']) : '';
        $details = isset($_POST['details']) ? sanitize_textarea_field($_POST['details']) : '';
        $config = FeedbackManager::get_config($slug);
        if (!$config) {
            wp_send_json_error('Unknown plugin', 400);
        }
        // Data for the sb-feedback-api REST endpoint.
        // Field mapping: reason_id -> reason, details -> comment.
        $api_data = ['plugin_slug' => $slug, 'plugin_name' => $config['plugin_name'], 'plugin_version' => $config['plugin_version'], 'reason' => $reason_id, 'comment' => $details, 'wp_version' => get_bloginfo('version'), 'php_version' => phpversion(), 'site_url' => home_url()];
        // Determine API endpoint: use config override, or auto-detect from slug.
        $endpoint = !empty($config['api_endpoint']) ? $config['api_endpoint'] : ApiClient::get_endpoint_for_slug($slug);
        // Send to API.
        ApiClient::send($endpoint, $api_data);
        // Extended data for the action hook (includes additional metadata).
        $hook_data = array_merge($api_data, ['locale' => get_locale(), 'multisite' => is_multisite() ? 'yes' : 'no', 'timestamp' => current_time('mysql', \true)]);
        /**
         * Fires after deactivation feedback is collected.
         *
         * @param array  $data   Feedback data.
         * @param array  $config Plugin configuration.
         */
        do_action('sb_feedback_deactivation_submitted', $hook_data, $config);
        // Always succeed — deactivation should never be blocked.
        wp_send_json_success();
    }


/** Function activate_plugin() called by wp_ajax hooks: {'sbr_activate_plugin'} **/
/** Parameters found in function activate_plugin(): {"post": ["plugin"]} **/
function activate_plugin()
	{
		check_ajax_referer('sbr-admin', 'nonce');

		if (!sbr_current_user_can('manage_reviews_feed_options')) {
			wp_send_json_error();
		}

		if (isset($_POST['plugin'])) {
			$plugin = sanitize_text_field(wp_unslash($_POST['plugin']));
			$activate = activate_plugins($plugin);
			wp_send_json_success([
				'plugins' => Util::get_plugins_info(),
				'recommendedPlugins' => Util::get_smashballoon_recommended_plugins_info()
			]);
		}

		wp_send_json_error(
			__('Could not Activate the Plugin. Please Activate from the Plugins page.', 'reviews-feed')
		);
	}


/** Function add_aliexpress_source() called by wp_ajax hooks: {'sbr_add_aliexpress_source'} **/
/** Parameters found in function add_aliexpress_source(): {"post": ["product_url"]} **/
function add_aliexpress_source()
	{
		check_ajax_referer('sbr-admin', 'nonce');

		// Pro version required for AliExpress sources
		if (!self::require_pro_version('AliExpress')) {
			return;
		}

		if (!sbr_current_user_can('manage_reviews_feed_options')) {
			wp_send_json(['error' => 'api_error', 'message' => 'Unauthorized access']);
			return;
		}

		if (!isset($_POST['product_url'])) {
			wp_send_json(['error' => 'api_error', 'message' => 'Product URL is required']);
			return;
		}

		$product_url = sanitize_text_field($_POST['product_url']);
		$item_id = AliExpress::extract_item_id($product_url);

		if (!$item_id) {
			wp_send_json(['error' => 'api_error', 'message' => 'Invalid AliExpress product URL. Please provide a valid AliExpress product URL like: https://www.aliexpress.com/item/1005010027246941.html']);
			return;
		}

		// Check if source already exists - return existing source without API call
		$existing_source = self::get_existing_source($item_id, 'aliexpress');
		if ($existing_source) {
			self::respond_with_existing_source($existing_source, 'AliExpress');
			return;
		}

		try {
			// Use SBRelay to fetch normalized data from Relay API
			$relay = new SBRelay();

			// SMASH-782 Phase 1: call /sources/<provider> BEFORE /reviews/<provider>.
			// The relay's reviews route is gated by the source-must-exist check
			// (post-SMASH-1360 quota hardening). Calling reviews first returns
			// `reviewsSourceNotCreated`. The source endpoint also creates the
			// underlying source row server-side via sourceService->insert().
			$source_response = $relay->callProvider('aliexpress', [
				'itemId' => $item_id
			], 'source', 'GET');

			// Surface source-call errors immediately so the customer sees the
			// real upstream cause instead of the downstream `reviewsSourceNotCreated`.
			// Relay returns `{success: false, message, data: {id, apiMessage, ...}}`
			// on upstream RapidAPI failure (e.g. invalid product ID, expired
			// subscription, rate limit). When `callProvider()` unwraps a
			// `success:true + data:{...}` envelope it returns the data subtree,
			// so the error envelope reaches us unwrapped here.
			if (isset($source_response['success']) && $source_response['success'] === false) {
				// `SBRelay::call()` unwraps the data envelope on error so `apiMessage`
				// is at the top level; keep the nested form as a backstop.
				$upstream_msg = $source_response['apiMessage']
					?? $source_response['data']['apiMessage']
					?? $source_response['message']
					?? 'Unable to fetch AliExpress product info from upstream.';
				wp_send_json(['error' => 'api_error', 'message' => $upstream_msg]);
				return;
			}

			$source_info = isset($source_response['info']) && is_array($source_response['info'])
				? $source_response['info']
				: [];

			// Fetch reviews from the product via Relay API.
			// SMASH-782 Phase 1: include `place_id` so the relay's reviews
			// middleware (`LimitReviewsRequest::resolvePlaceId`) can find the
			// source row that the source call just inserted. The
			// `NormalizesRapidAPIParameters` trait only runs in the controller
			// (after middleware), so middleware needs `place_id` explicitly.
			$response = $relay->callProvider('aliexpress', [
				'itemId' => $item_id,
				'place_id' => $item_id,
				'page' => 1,
				'filter' => 'allReviews'
			], 'reviews', 'GET');

			// Validate response structure - proxy returns { reviews: [], info: {} }
			if (!isset($response['reviews']) || !isset($response['info'])) {
				$error_msg = 'Unable to fetch reviews. Unexpected API response format.';
				if (isset($response['message'])) {
					$error_msg .= ' API Message: ' . $response['message'];
				}
				wp_send_json(['error' => 'api_error', 'message' => $error_msg]);
				return;
			}

			// Use normalized data from proxy. Reviews response also carries an
			// `info` block; prefer the source-endpoint info when it has the
			// richer fields (name, image) populated, fall back to reviews-info
			// or defaults for any missing key.
			$normalized_reviews = $response['reviews'];
			$reviews_info = is_array($response['info']) ? $response['info'] : [];
			// Keep legitimate falsy values (0, false); drop only null / empty string.
			$source_info = array_filter($source_info, function ($v) {
				return $v !== null && $v !== '';
			}) + $reviews_info;
			$review_count = count($normalized_reviews);

			$product_name = $source_info['name'] ?? 'AliExpress Product ' . $item_id;
			$product_image = $source_info['image'] ?? '';

			$source_data = [
				'id' => $item_id,
				'provider' => 'aliexpress',
				'name' => $product_name,
				'url' => $source_info['url'] ?? 'https://www.aliexpress.com/item/' . $item_id . '.html',
				'image' => $product_image,
				'rating' => $source_info['rating'] ?? 0,
				'review_count' => $source_info['review_count'] ?? $review_count,
				'account_id' => $item_id,
				'access_token' => '', // No API key needed - handled by proxy
				'info' => json_encode([
					'id' => $item_id,
					'name' => $product_name,
					'url' => $source_info['url'] ?? 'https://www.aliexpress.com/item/' . $item_id . '.html',
					'image' => $product_image,
					'rating' => $source_info['rating'] ?? 0,
					'total_rating' => $source_info['review_count'] ?? $review_count,
					'review_count' => $source_info['review_count'] ?? $review_count,
					'provider' => 'aliexpress'
				]),
				'error' => '',
				'expires' => date('Y-m-d H:i:s', strtotime('+30 days')),
				'last_updated' => current_time('mysql'),
				'author' => get_current_user_id()
			];

			SBR_Sources::update_or_insert($source_data);

			// Cache reviews - already normalized by proxy
			if (!empty($normalized_reviews)) {
				self::cache_reviews($normalized_reviews, 'aliexpress', $item_id);
			}

			// Schedule bulk update if there are more reviews to fetch
			// AliExpress returns ~20 reviews per page
			$total_reviews = $source_info['review_count'] ?? $review_count;
			self::maybe_schedule_bulk_update($item_id, 'aliexpress', $total_reviews, 20);

			// Return flat structure to match other providers
			wp_send_json([
				'success' => true,
				'message' => 'addedSource',
				'source' => $source_data,
				'sourcesList' => SBR_Sources::get_sources_list(),
				'sourcesCount' => SBR_Sources::get_sources_count()
			]);
		} catch (\Exception $e) {
			wp_send_json(['error' => 'api_error', 'message' => $e->getMessage()]);
		}
	}


