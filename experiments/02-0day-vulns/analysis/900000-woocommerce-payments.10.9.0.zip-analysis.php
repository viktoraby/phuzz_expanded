<?php
/***
*
*Found actions: 26
*Found functions:19
*Extracted functions:19
*Total parameter names extracted: 16
*Overview: {'theme_edit_ajax': {'edit-theme-plugin-file'}, 'enable_auto_renew': {'wcs_enable_auto_renew'}, 'plugin_edit_ajax': {'edit-theme-plugin-file'}, 'add_wc_price_args_filter_for_ajax': {'nopriv_wc_bookings_calculate_costs', 'wc_bookings_calculate_costs'}, 'save_increased_price_lock': {'wcs_order_price_lock'}, 'disable_auto_renew': {'wcs_disable_auto_renew'}, '::maybe_update_one_time_shipping_on_variation_edits': {'wcs_update_one_time_shipping'}, 'get_customer_orders': {'wcs_get_customer_orders'}, '::check_product_variations_for_syncd_or_trial': {'wcs_product_has_trial_or_is_synced'}, 'ajax_get_user_payment_tokens': {'wcpay_get_user_payment_tokens'}, 'ajax_admin_set_woopay_appearance': {'wcpay_admin_set_woopay_appearance'}, '::ajax_upgrade': {'wcs_upgrade'}, 'ajax_tracks': {'nopriv_platform_tracks', 'jetpack_tracks', 'platform_tracks'}, 'show_error_notice': {'nopriv_woopay_express_checkout_button_show_error_notice', 'woopay_express_checkout_button_show_error_notice'}, 'ajax_tracks_id': {'get_identity', 'nopriv_get_identity'}, 'create_setup_intent_ajax': {'create_setup_intent'}, 'update_order_status': {'update_order_status', 'nopriv_update_order_status'}, '::remove_variations': {'woocommerce_remove_variations', 'woocommerce_remove_variation'}, 'validate_variation_deletion': {'wcs_validate_variation_deletion'}}
*
***/

/** Function theme_edit_ajax() called by wp_ajax hooks: {'edit-theme-plugin-file'} **/
/** Parameters found in function theme_edit_ajax(): {"post": ["theme", "file", "newcontent", "nonce"]} **/
function theme_edit_ajax() {
		// This validation is based on wp_edit_theme_plugin_file().
		if ( empty( $_POST['theme'] ) ) {
			return;
		}

		if ( empty( $_POST['file'] ) ) {
			return;
		}
		$file = wp_unslash( $_POST['file'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Validated manually just after.
		if ( 0 !== validate_file( $file ) ) {
			return;
		}

		if ( ! isset( $_POST['newcontent'] ) ) {
			return;
		}

		if ( ! isset( $_POST['nonce'] ) ) {
			return;
		}

		$stylesheet = wp_unslash( $_POST['theme'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Validated manually just after.
		if ( 0 !== validate_file( $stylesheet ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_themes' ) ) {
			return;
		}

		$theme = wp_get_theme( $stylesheet );
		if ( ! $theme->exists() ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['nonce'], 'edit-theme_' . $stylesheet . '_' . $file ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- WP core doesn't pre-sanitize nonces either.
			return;
		}

		if ( $theme->errors() && 'theme_no_stylesheet' === $theme->errors()->get_error_code() ) {
			return;
		}

		$editable_extensions = wp_get_theme_file_editable_extensions( $theme );

		$allowed_files = array();
		foreach ( $editable_extensions as $type ) {
			switch ( $type ) {
				case 'php':
					$allowed_files = array_merge( $allowed_files, $theme->get_files( 'php', -1 ) );
					break;
				case 'css':
					$style_files                = $theme->get_files( 'css', -1 );
					$allowed_files['style.css'] = $style_files['style.css'];
					$allowed_files              = array_merge( $allowed_files, $style_files );
					break;
				default:
					$allowed_files = array_merge( $allowed_files, $theme->get_files( $type, -1 ) );
					break;
			}
		}

		$real_file = $theme->get_stylesheet_directory() . '/' . $file;
		if ( 0 !== validate_file( $real_file, $allowed_files ) ) {
			return;
		}

		// Ensure file is real.
		if ( ! is_file( $real_file ) ) {
			return;
		}

		// Ensure file extension is allowed.
		$extension = null;
		if ( preg_match( '/\.([^.]+)$/', $real_file, $matches ) ) {
			$extension = strtolower( $matches[1] );
			if ( ! in_array( $extension, $editable_extensions, true ) ) {
				return;
			}
		}

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_is_writable
		if ( ! is_writable( $real_file ) ) {
			return;
		}

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
		$file_pointer = fopen( $real_file, 'w+' );
		if ( false === $file_pointer ) {
			return;
		}
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
		fclose( $file_pointer );

		$theme_data = array(
			'name'    => $theme->get( 'Name' ),
			'version' => $theme->get( 'Version' ),
			'uri'     => $theme->get( 'ThemeURI' ),
		);

		/**
		 * This action is documented already in this file.
		 */
		do_action( 'jetpack_edited_theme', $stylesheet, $theme_data );
	}


/** Function enable_auto_renew() called by wp_ajax hooks: {'wcs_enable_auto_renew'} **/
/** Parameters found in function enable_auto_renew(): {"post": ["subscription_id"]} **/
function enable_auto_renew() {

		if ( ! isset( $_POST['subscription_id'] ) ) {
			return -1;
		}

		$subscription_id = absint( $_POST['subscription_id'] );
		check_ajax_referer( "toggle-auto-renew-{$subscription_id}", 'security' );

		$subscription = wcs_get_subscription( $subscription_id );

		if ( wc_get_payment_gateway_by_order( $subscription ) && self::can_user_toggle_auto_renewal( $subscription ) ) {
			$subscription->set_requires_manual_renewal( false );
			$subscription->add_order_note( __( 'Customer turned on automatic renewals via their My Account page.', 'woocommerce-subscriptions' ) );
			$subscription->save();

			self::send_ajax_response( $subscription );
		}
	}


/** Function plugin_edit_ajax() called by wp_ajax hooks: {'edit-theme-plugin-file'} **/
/** Parameters found in function plugin_edit_ajax(): {"post": ["file", "newcontent", "nonce", "plugin"]} **/
function plugin_edit_ajax() {
		// This validation is based on wp_edit_theme_plugin_file().
		if ( empty( $_POST['file'] ) ) {
			return;
		}

		$file = wp_unslash( $_POST['file'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Validated manually just after.
		if ( 0 !== validate_file( $file ) ) {
			return;
		}

		if ( ! isset( $_POST['newcontent'] ) ) {
			return;
		}

		if ( ! isset( $_POST['nonce'] ) ) {
			return;
		}

		if ( empty( $_POST['plugin'] ) ) {
			return;
		}

		$plugin = wp_unslash( $_POST['plugin'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Validated manually just after.
		if ( ! current_user_can( 'edit_plugins' ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['nonce'], 'edit-plugin_' . $file ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- WP core doesn't pre-sanitize nonces either.
			return;
		}
		$plugins = get_plugins();
		if ( ! array_key_exists( $plugin, $plugins ) ) {
			return;
		}

		if ( 0 !== validate_file( $file, get_plugin_files( $plugin ) ) ) {
			return;
		}

		$real_file = WP_PLUGIN_DIR . '/' . $file;

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_is_writable
		if ( ! is_writable( $real_file ) ) {
			return;
		}

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
		$file_pointer = fopen( $real_file, 'w+' );
		if ( false === $file_pointer ) {
			return;
		}
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
		fclose( $file_pointer );
		/**
		 * This action is documented already in this file
		 */
		do_action( 'jetpack_edited_plugin', $plugin, $plugins[ $plugin ] );
	}


/** Function add_wc_price_args_filter_for_ajax() called by wp_ajax hooks: {'nopriv_wc_bookings_calculate_costs', 'wc_bookings_calculate_costs'} **/
/** No params detected :-/ **/


/** Function save_increased_price_lock() called by wp_ajax hooks: {'wcs_order_price_lock'} **/
/** Parameters found in function save_increased_price_lock(): {"post": ["woocommerce_meta_nonce", "order_id", "wcs_order_price_lock"]} **/
function save_increased_price_lock( $order_id = '' ) {

		if ( empty( $_POST['woocommerce_meta_nonce'] ) || ! wp_verify_nonce( wc_clean( wp_unslash( $_POST['woocommerce_meta_nonce'] ) ), 'woocommerce_save_data' ) ) {
			return;
		}

		$order = wc_get_order( wp_doing_ajax() && isset( $_POST['order_id'] ) ? absint( $_POST['order_id'] ) : $order_id );

		if ( ! $order ) {
			return;
		}

		if ( isset( $_POST['wcs_order_price_lock'] ) && 'yes' === wc_clean( wp_unslash( $_POST['wcs_order_price_lock'] ) ) ) {
			$order->update_meta_data( '_manual_price_increases_locked', 'true' );
			$order->save();
		} elseif ( $order->meta_exists( '_manual_price_increases_locked' ) ) {
			$order->delete_meta_data( '_manual_price_increases_locked' );
			$order->save();
		}
	}


/** Function disable_auto_renew() called by wp_ajax hooks: {'wcs_disable_auto_renew'} **/
/** Parameters found in function disable_auto_renew(): {"post": ["subscription_id"]} **/
function disable_auto_renew() {

		if ( ! isset( $_POST['subscription_id'] ) ) {
			return -1;
		}

		$subscription_id = absint( $_POST['subscription_id'] );
		check_ajax_referer( "toggle-auto-renew-{$subscription_id}", 'security' );

		$subscription = wcs_get_subscription( $subscription_id );

		if ( $subscription && self::can_user_toggle_auto_renewal( $subscription ) ) {
			$subscription->set_requires_manual_renewal( true );
			$subscription->add_order_note( __( 'Customer turned off automatic renewals via their My Account page.', 'woocommerce-subscriptions' ) );
			$subscription->save();

			self::send_ajax_response( $subscription );
		}
	}


/** Function ::maybe_update_one_time_shipping_on_variation_edits() called by wp_ajax hooks: {'wcs_update_one_time_shipping'} **/
/** Parameters found in function ::maybe_update_one_time_shipping_on_variation_edits(): {"post": ["one_time_shipping_enabled", "one_time_shipping_selected", "product_id"]} **/
function maybe_update_one_time_shipping_on_variation_edits() {

		check_admin_referer( 'one_time_shipping', 'nonce' );

		$one_time_shipping_enabled      = $_POST['one_time_shipping_enabled'];
		$one_time_shipping_selected     = $_POST['one_time_shipping_selected'];
		$subscription_one_time_shipping = 'no';

		if ( 'false' !== $one_time_shipping_enabled && 'true' === $one_time_shipping_selected ) {
			$subscription_one_time_shipping = 'yes';
		}

		update_post_meta( $_POST['product_id'], '_subscription_one_time_shipping', $subscription_one_time_shipping );

		wp_send_json( array( 'one_time_shipping' => $subscription_one_time_shipping ) );
	}


/** Function get_customer_orders() called by wp_ajax hooks: {'wcs_get_customer_orders'} **/
/** Parameters found in function get_customer_orders(): {"post": ["user_id"]} **/
function get_customer_orders() {
		check_ajax_referer( 'get-customer-orders', 'security' );

		if ( ! current_user_can( 'edit_shop_orders' ) ) {
			wp_die( -1 );
		}

		$customer_orders = array();
		$user_id         = absint( $_POST['user_id'] ?? null );
		$orders          = wc_get_orders(
			array(
				'customer'       => $user_id,
				'post_type'      => 'shop_order',
				'posts_per_page' => '-1',
			)
		);

		foreach ( $orders as $order ) {
			$customer_orders[ $order->get_id() ] = $order->get_order_number();
		}

		wp_send_json( $customer_orders );
	}


/** Function ::check_product_variations_for_syncd_or_trial() called by wp_ajax hooks: {'wcs_product_has_trial_or_is_synced'} **/
/** Parameters found in function ::check_product_variations_for_syncd_or_trial(): {"post": ["product_id", "variations_checked"]} **/
function check_product_variations_for_syncd_or_trial() {

		check_admin_referer( 'one_time_shipping', 'nonce' );

		$product                = wc_get_product( $_POST['product_id'] );
		$is_synced_or_has_trial = false;

		if ( WC_Subscriptions_Product::is_subscription( $product ) ) {

			foreach ( $product->get_children() as $variation_id ) {

				if ( isset( $_POST['variations_checked'] ) && in_array( $variation_id, $_POST['variations_checked'] ) ) {
					continue;
				}

				$variation_product = wc_get_product( $variation_id );

				if ( WC_Subscriptions_Product::get_trial_length( $variation_product ) ) {
					$is_synced_or_has_trial = true;
					break;
				}

				if ( WC_Subscriptions_Synchroniser::is_product_synced( $variation_product ) ) {
					$is_synced_or_has_trial = true;
					break;
				}
			}
		}

		wp_send_json( array( 'is_synced_or_has_trial' => $is_synced_or_has_trial ) );
	}


/** Function ajax_get_user_payment_tokens() called by wp_ajax hooks: {'wcpay_get_user_payment_tokens'} **/
/** Parameters found in function ajax_get_user_payment_tokens(): {"post": ["user_id", "gateway_id"]} **/
function ajax_get_user_payment_tokens() {
		check_ajax_referer( 'wcpay-subscription-edit', 'nonce' );

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( [ 'message' => __( 'You do not have permission to perform this action.', 'woocommerce-payments' ) ], 403 );
			return;
		}

		$user_id    = isset( $_POST['user_id'] ) ? absint( $_POST['user_id'] ) : 0;
		$gateway_id = isset( $_POST['gateway_id'] ) ? sanitize_text_field( wp_unslash( $_POST['gateway_id'] ) ) : null;

		if ( $user_id <= 0 ) {
			wp_send_json_success( [ 'tokens' => [] ] );
			return;
		}

		// Verify user exists.
		$user = get_user_by( 'id', $user_id );
		if ( ! $user ) {
			wp_send_json_error( [ 'message' => __( 'Invalid user ID.', 'woocommerce-payments' ) ], 400 );
			return;
		}

		// Validating the gateway_id - only allow reusable WCPay gateways.
		if ( null !== $gateway_id && ! $this->is_reusable_wcpay_gateway( $gateway_id ) ) {
			$gateway_id = null; // Fall back to the default card gateway.
		}

		$tokens = $this->get_user_formatted_tokens_array( $user_id, $gateway_id );
		wp_send_json_success( [ 'tokens' => $tokens ] );
	}


/** Function ajax_admin_set_woopay_appearance() called by wp_ajax hooks: {'wcpay_admin_set_woopay_appearance'} **/
/** Parameters found in function ajax_admin_set_woopay_appearance(): {"post": ["appearance", "font_rules"]} **/
function ajax_admin_set_woopay_appearance() {
		check_ajax_referer( 'wcpay_admin_woopay_appearance_nonce' );

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error(
				__( 'You aren\'t authorized to do that.', 'woocommerce-payments' ),
				403
			);
		}

		if ( ! \WC_Payments::get_gateway()->is_woopay_global_theme_support_enabled() ) {
			wp_send_json_error(
				__( 'This action is not available.', 'woocommerce-payments' ),
				403
			);
		}

		if ( empty( $_POST['appearance'] ) || ! is_array( $_POST['appearance'] ) ) {
			wp_send_json_error(
				__( 'Missing or invalid appearance data.', 'woocommerce-payments' ),
				400
			);
		}

		$appearance = self::array_map_recursive( [ __CLASS__, 'sanitize_string' ], $_POST['appearance'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		if ( ! \WC_Payments_Styles_Cache::validate_appearance_schema( $appearance ) ) {
			wp_send_json_error(
				__( 'Invalid appearance schema.', 'woocommerce-payments' ),
				400
			);
		}

		$font_rules = [];
		if ( ! empty( $_POST['font_rules'] ) ) {
			$raw_font_rules = json_decode( wp_unslash( $_POST['font_rules'] ), true ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- decoded values are sanitized by sanitize_font_rules().
			$font_rules     = is_array( $raw_font_rules ) ? self::sanitize_font_rules( $raw_font_rules ) : [];
		}

		\WC_Payments_Styles_Cache::set_woopay_appearance( $appearance, $font_rules );

		wp_send_json_success();
	}


/** Function ::ajax_upgrade() called by wp_ajax hooks: {'wcs_upgrade'} **/
/** Parameters found in function ::ajax_upgrade(): {"post": ["upgrade_step"]} **/
function ajax_upgrade() {
		global $wpdb;

		check_admin_referer( 'wcs_upgrade_process', 'nonce' );

		self::set_upgrade_limits();

		WCS_Upgrade_Logger::add( sprintf( 'Starting upgrade step: %s', $_POST['upgrade_step'] ) );

		if ( ini_get( 'max_execution_time' ) < 600 ) {
			@set_time_limit( 600 );
		}

		@ini_set( 'memory_limit', apply_filters( 'admin_memory_limit', WP_MAX_MEMORY_LIMIT ) );

		update_option( 'wc_subscriptions_is_upgrading', gmdate( 'U' ) + 60 * 2 );

		switch ( $_POST['upgrade_step'] ) {

			case 'really_old_version':
				$upgraded_versions = self::upgrade_really_old_versions();
				$results = array(
					// translators: placeholder is a list of version numbers (e.g. "1.3 & 1.4 & 1.5")
					'message' => sprintf( __( 'Database updated to version %s', 'woocommerce-subscriptions' ), $upgraded_versions ),
				);
				break;

			case 'products':
				$upgraded_product_count = WCS_Upgrade_1_5::upgrade_products();
				$results = array(
					// translators: placeholder is number of upgraded subscriptions
					'message' => sprintf( _x( 'Marked %s subscription products as "sold individually".', 'used in the subscriptions upgrader', 'woocommerce-subscriptions' ), $upgraded_product_count ),
				);
				break;

			case 'hooks':
				$upgraded_hook_count = WCS_Upgrade_1_5::upgrade_hooks( self::$upgrade_limit_hooks );
				$results = array(
					'upgraded_count' => $upgraded_hook_count,
					// translators: 1$: number of action scheduler hooks upgraded, 2$: "{execution_time}", will be replaced on front end with actual time
					'message'        => sprintf( __( 'Migrated %1$s subscription related hooks to the new scheduler (in %2$s seconds).', 'woocommerce-subscriptions' ), $upgraded_hook_count, '{execution_time}' ),
				);
				break;

			case 'subscriptions':
				try {

					$upgraded_subscriptions = WCS_Upgrade_2_0::upgrade_subscriptions( self::$upgrade_limit_subscriptions );

					$results = array(
						'upgraded_count' => $upgraded_subscriptions,
						// translators: 1$: number of subscriptions upgraded, 2$: "{execution_time}", will be replaced on front end with actual time it took
						'message'        => sprintf( __( 'Migrated %1$s subscriptions to the new structure (in %2$s seconds).', 'woocommerce-subscriptions' ), $upgraded_subscriptions, '{execution_time}' ),
						'status'         => 'success',
						// translators: placeholder is "{time_left}", will be replaced on front end with actual time
						'time_message'   => sprintf( _x( 'Estimated time left (minutes:seconds): %s', 'Message that gets sent to front end.', 'woocommerce-subscriptions' ), '{time_left}' ),
					);

				} catch ( Exception $e ) {

					WCS_Upgrade_Logger::add( sprintf( 'Error on upgrade step: %s. Error: %s', $_POST['upgrade_step'], $e->getMessage() ) );

					$results = array(
						'upgraded_count' => 0,
						// translators: 1$: error message, 2$: opening link tag, 3$: closing link tag, 4$: break tag
						'message'        => sprintf( __( 'Unable to upgrade subscriptions.%4$sError: %1$s%4$sPlease refresh the page and try again. If problem persists, %2$scontact support%3$s.', 'woocommerce-subscriptions' ), '<code>' . $e->getMessage() . '</code>', '<a href="' . esc_url( 'https://woocommerce.com/my-account/create-a-ticket/' ) . '">', '</a>', '<br />' ),
						'status'         => 'error',
					);
				}

				break;

			case 'subscription_dates_repair':
				$subscription_ids_to_repair = WCS_Repair_2_0_2::get_subscriptions_to_repair( self::$upgrade_limit_subscriptions );

				try {

					$subscription_counts = WCS_Repair_2_0_2::maybe_repair_subscriptions( $subscription_ids_to_repair );

					// translators: placeholder is the number of subscriptions repaired
					$repair_incorrect = sprintf( _x( 'Repaired %d subscriptions with incorrect dates, line tax data or missing customer notes.', 'Repair message that gets sent to front end.', 'woocommerce-subscriptions' ), $subscription_counts['repaired_count'] );

					$repair_not_needed = '';

					if ( $subscription_counts['unrepaired_count'] > 0 ) {
						// translators: placeholder is number of subscriptions that were checked and did not need repairs. There's a space at the beginning!
						$repair_not_needed = sprintf( _nx( ' %d other subscription was checked and did not need any repairs.', '%d other subscriptions were checked and did not need any repairs.', $subscription_counts['unrepaired_count'], 'Repair message that gets sent to front end.', 'woocommerce-subscriptions' ), $subscription_counts['unrepaired_count'] );
					}

					// translators: placeholder is "{execution_time}", which will be replaced on front end with actual time
					$repair_time = sprintf( _x( '(in %s seconds)', 'Repair message that gets sent to front end.', 'woocommerce-subscriptions' ), '{execution_time}' );

					// translators: $1: "Repaired x subs with incorrect dates...", $2: "X others were checked and no repair needed", $3: "(in X seconds)". Ordering for RTL languages.
					$repair_message = sprintf( _x( '%1$s%2$s %3$s', 'The assembled repair message that gets sent to front end.', 'woocommerce-subscriptions' ), $repair_incorrect, $repair_not_needed, $repair_time );

					$results = array(
						'repaired_count'   => $subscription_counts['repaired_count'],
						'unrepaired_count' => $subscription_counts['unrepaired_count'],
						'message'          => $repair_message,
						'status'           => 'success',
						// translators: placeholder is "{time_left}", will be replaced on front end with actual time
						'time_message'     => sprintf( _x( 'Estimated time left (minutes:seconds): %s', 'Message that gets sent to front end.', 'woocommerce-subscriptions' ), '{time_left}' ),
					);

				} catch ( Exception $e ) {

					WCS_Upgrade_Logger::add( sprintf( 'Error on upgrade step: %s. Error: %s', $_POST['upgrade_step'], $e->getMessage() ) );

					$results = array(
						'repaired_count'   => 0,
						'unrepaired_count' => 0,
						// translators: 1$: error message, 2$: opening link tag, 3$: closing link tag, 4$: break tag
						'message'          => sprintf( _x( 'Unable to repair subscriptions.%4$sError: %1$s%4$sPlease refresh the page and try again. If problem persists, %2$scontact support%3$s.', 'Error message that gets sent to front end when upgrading Subscriptions', 'woocommerce-subscriptions' ), '<code>' . $e->getMessage() . '</code>', '<a href="' . esc_url( 'https://woocommerce.com/my-account/create-a-ticket/' ) . '">', '</a>', '<br />' ),
						'status'           => 'error',
					);
				}

				break;
		}

		if ( 'subscriptions' == $_POST['upgrade_step'] && 0 === self::get_total_subscription_count_query() ) {

			self::upgrade_complete();

		} elseif ( 'subscription_dates_repair' == $_POST['upgrade_step'] ) {

			$subscriptions_to_repair = WCS_Repair_2_0_2::get_subscriptions_to_repair( self::$upgrade_limit_subscriptions );

			if ( empty( $subscriptions_to_repair ) ) {
				self::upgrade_complete();
			}
		}

		WCS_Upgrade_Logger::add( sprintf( 'Completed upgrade step: %s', $_POST['upgrade_step'] ) );

		header( 'Content-Type: application/json; charset=utf-8' );
		echo wcs_json_encode( $results );
		exit();
	}


/** Function ajax_tracks() called by wp_ajax hooks: {'nopriv_platform_tracks', 'jetpack_tracks', 'platform_tracks'} **/
/** Parameters found in function ajax_tracks(): {"request": ["tracksNonce", "tracksEventName", "tracksEventProp"]} **/
function ajax_tracks() {
		// Check for nonce.
		if (
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			empty( $_REQUEST['tracksNonce'] ) || ! wp_verify_nonce( $_REQUEST['tracksNonce'], 'platform_tracks_nonce' )
		) {
			wp_send_json_error(
				__( 'You aren’t authorized to do that.', 'woocommerce-payments' ),
				403
			);
		}

		if ( ! isset( $_REQUEST['tracksEventName'] ) ) {
			wp_send_json_error(
				__( 'No valid event name or type.', 'woocommerce-payments' ),
				403
			);
		}

		$tracks_data = [];
		if ( isset( $_REQUEST['tracksEventProp'] ) ) {
			// tracksEventProp is a JSON-encoded string.
			$event_prop = json_decode( wc_clean( wp_unslash( $_REQUEST['tracksEventProp'] ) ), true );
			if ( is_array( $event_prop ) ) {
				$tracks_data = $event_prop;
			}
		}
		$this->maybe_record_event( sanitize_text_field( wp_unslash( $_REQUEST['tracksEventName'] ) ), $tracks_data );

		wp_send_json_success();
	}


/** Function show_error_notice() called by wp_ajax hooks: {'nopriv_woopay_express_checkout_button_show_error_notice', 'woopay_express_checkout_button_show_error_notice'} **/
/** Parameters found in function show_error_notice(): {"post": ["message"]} **/
function show_error_notice() {
		$is_nonce_valid = check_ajax_referer( 'woopay_button_nonce', false, false );

		if ( ! $is_nonce_valid ) {
			wp_send_json_error(
				__( 'You aren’t authorized to do that.', 'woocommerce-payments' ),
				403
			);
		}

		$message = isset( $_POST['message'] ) ? sanitize_text_field( wp_unslash( $_POST['message'] ) ) : '';

		// $message has already been translated.
		wc_add_notice( $message, 'error' );
		$notice = wc_print_notices( true );

		wp_send_json_success(
			[
				'notice' => $notice,
			]
		);

		wp_die();
	}


/** Function ajax_tracks_id() called by wp_ajax hooks: {'get_identity', 'nopriv_get_identity'} **/
/** No params detected :-/ **/


/** Function create_setup_intent_ajax() called by wp_ajax hooks: {'create_setup_intent'} **/
/** No params detected :-/ **/


/** Function update_order_status() called by wp_ajax hooks: {'update_order_status', 'nopriv_update_order_status'} **/
/** Parameters found in function update_order_status(): {"post": ["order_id", "intent_id", "is_changing_payment", "should_save_payment_method"]} **/
function update_order_status() {
		$intent_id_received = null;
		$order              = null;
		try {
			$is_nonce_valid = check_ajax_referer( 'wcpay_update_order_status_nonce', false, false );
			if ( ! $is_nonce_valid ) {
				throw new Process_Payment_Exception(
					__( "We're not able to process this payment. Please refresh the page and try again.", 'woocommerce-payments' ),
					'invalid_referrer'
				);
			}

			$order_id = isset( $_POST['order_id'] ) ? absint( $_POST['order_id'] ) : false;
			$order    = wc_get_order( $order_id );
			if ( ! $order ) {
				throw new Process_Payment_Exception(
					__( "We're not able to process this payment. Please try again later.", 'woocommerce-payments' ),
					'order_not_found'
				);
			}

			$intent_id          = $this->order_service->get_intent_id_for_order( $order );
			$intent_id_received = isset( $_POST['intent_id'] )
			? sanitize_text_field( wp_unslash( $_POST['intent_id'] ) )
			/* translators: This will be used to indicate an unknown value for an ID. */
			: __( 'unknown', 'woocommerce-payments' );

			if ( empty( $intent_id ) ) {
				throw new Intent_Authentication_Exception(
					__( "We're not able to process this payment. Please try again later.", 'woocommerce-payments' ),
					'empty_intent_id'
				);
			}

			// Check that the intent saved in the order matches the intent used as part of the
			// authentication process. The ID of the intent used is sent with
			// the AJAX request. We are about to use the status of the intent saved in
			// the order, so we need to make sure the intent that was used for authentication
			// is the same as the one we're using to update the status.
			if ( $intent_id !== $intent_id_received ) {
				throw new Intent_Authentication_Exception(
					__( "We're not able to process this payment. Please try again later.", 'woocommerce-payments' ),
					'intent_id_mismatch'
				);
			}

			$amount                                = $order->get_total();
			$payment_method_details                = false;
			$is_changing_payment                   = isset( $_POST['is_changing_payment'] )
				? filter_var( wp_unslash( $_POST['is_changing_payment'] ), FILTER_VALIDATE_BOOLEAN )
				: null;
			$is_subscription_payment_method_change = $this->is_changing_payment_method_for_subscription_from_request( $is_changing_payment );

			if ( $amount > 0 && ! $is_subscription_payment_method_change ) {
				// An exception is thrown if an intent can't be found for the given intent ID.
				$request = Get_Intention::create( $intent_id );
				$request->set_hook_args( $order );
				$intent = $request->send();

				$status    = $intent->get_status();
				$charge    = $intent->get_charge();
				$charge_id = ! empty( $charge ) ? $charge->get_id() : null;

				// Capture the actual card details from the charge so the order's payment method title (and,
				// via sync, the subscription's) reflects the card used — brand and funding — instead of a
				// generic "Card". The 3DS/SCA pay-for-renewal flow lands here after frontend authentication;
				// the synchronous non-3DS path already does this in process_payment_for_order(). See WOOPMNT-2882.
				if ( ! empty( $charge ) ) {
					$payment_method_details = $charge->get_payment_method_details();
				}

				$this->attach_exchange_info_to_order( $order, $charge_id );
				$this->order_service->attach_intent_info_to_order( $order, $intent );
				$this->order_service->attach_transaction_fee_to_order( $order, $charge );
			} else {
				// For $0 orders, fetch the Setup Intent instead.
				$setup_intent_request = Get_Setup_Intention::create( $intent_id );
				/** @var WC_Payments_API_Setup_Intention $setup_intent */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				$intent = $setup_intent_request->send();
				$status = $intent->get_status();

				// For $0 orders (free trials), directly complete the order when SetupIntent succeeds.
				// This is similar to how WC Stripe Gateway handles it - calling payment_complete()
				// directly ensures the order transitions to the correct status and activates subscriptions.
				// Otherwise, the order would be in a "Pending payment" state and the subscription would be "Pending".
				if ( Intent_Status::SUCCEEDED === $status && ! $order->is_paid() && ! $is_subscription_payment_method_change ) {
					// $0 orders (free trials) confirm a SetupIntent with no charge to read the card from.
					// Source the card details from the confirmed payment method so the order, its synced
					// subscription, and the completion email show the real card instead of a generic "Card".
					// Set the title BEFORE payment_complete(), which fires the customer email. Assign into the
					// method-scoped $payment_method_details (not a local) so the later ! empty( $token ) block
					// re-applies the SAME branded title rather than overwriting it back to a generic "Card" with
					// the still-false default — mirroring how the $amount > 0 branch feeds the charge details
					// into $payment_method_details. WOOPMNT-2882.
					$payment_method_details = $this->get_payment_method_details_for_zero_amount_order( $intent->get_payment_method_id() );
					if ( $payment_method_details ) {
						$this->set_payment_method_title_for_order( $order, $payment_method_details['type'], $payment_method_details );
						$this->store_card_details_meta_for_order( $order, $payment_method_details['type'], $payment_method_details );
					}

					$order->payment_complete( $intent_id );

					// Add a success note similar to mark_payment_completed().
					$note = sprintf(
					/* translators: %1: the successfully charged amount, %2: WooPayments, %3: transaction ID of the payment */
						__( 'A payment of %1$s was successfully charged using %2$s (%3$s).', 'woocommerce-payments' ),
						wc_price( $order->get_total(), [ 'currency' => $order->get_currency() ] ),
						'WooPayments',
						$intent_id
					);
					$order->add_order_note( $note );
					$this->order_service->set_intention_status_for_order( $order, $status );
					$order->save();
				}
			}

			$payment_method_id = $intent->get_payment_method_id();

			// For SetupIntents confirmed via frontend (e.g., ECE with confirmation tokens),
			// store the payment method ID in order meta. This ensures subscription renewals
			// can find the payment method even if token creation fails later.
			if ( ! empty( $payment_method_id ) ) {
				$this->order_service->set_payment_method_id_for_order( $order, $payment_method_id );
			}

			if ( Intent_Status::SUCCEEDED === $status ) {
				$this->duplicate_payment_prevention_service->remove_session_processing_order( $order->get_id() );
			}

			// Determine whether the payment method should be saved for this order. Subscriptions and
			// subscription renewals must always save it so the subscription can be charged off-session
			// for future renewals. is_payment_recurring() treats both as recurring — it accounts for
			// renewals, which wcs_order_contains_subscription()'s default order types exclude. See WOOPMNT-2882.
			$is_recurring_payment       = $this->is_payment_recurring( $order->get_id() );
			$should_save_payment_method = $is_recurring_payment || ( isset( $_POST['should_save_payment_method'] ) && 'true' === $_POST['should_save_payment_method'] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

			// Save and attach the payment token BEFORE updating the order status. Updating the status
			// fires WCS' woocommerce_subscriptions_paid_for_failed_renewal_order hook, which runs
			// update_failing_payment_method() -> get_payment_token( $renewal_order ) to copy the token
			// onto the subscription. If the token were attached afterwards (as it was previously), the
			// subscription would keep the old failing card. Attaching it first also prevents
			// maybe_schedule_subscription_order_tracking() from overwriting _payment_method_id back to
			// the previously-stored card when the order status save fires. See WOOPMNT-2882.
			$token = null;
			if ( $intent->is_authorized() && $should_save_payment_method && ! empty( $payment_method_id ) ) {
				try {
					$token = $this->token_service->add_payment_method_to_user( $payment_method_id, wp_get_current_user() );
					$this->add_token_to_order( $order, $token );
				} catch ( Exception $e ) {
					Logger::log( 'Error when saving payment method: ' . $e->getMessage() );

					// For recurring payments (subscriptions and renewals), token creation failure is
					// critical - renewals will fail. Re-throw the exception so the customer sees an error
					// instead of a successful checkout that will fail on the first renewal.
					if ( $is_recurring_payment ) {
						throw new Exception(
							__( 'Unable to save payment method for subscription. Please try again or use a different payment method.', 'woocommerce-payments' )
						);
					}
				}
			}

			// Set the branded payment method title + card meta from the charge details BEFORE the status
			// update. update_order_status_from_intent() -> payment_complete() fires the customer order/renewal
			// email synchronously, and that email renders get_payment_method_title(); the title must already
			// reflect the real card or the email falls back to a generic "Card". $token is non-null only when
			// the intent is authorized and the payment method was saved (see the token-attach guard above).
			// See WOOPMNT-2882.
			if ( ! empty( $token ) ) {
				$payment_method_type = $this->get_payment_method_type_for_setup_intent( $intent, $token );
				$this->set_payment_method_title_for_order( $order, $payment_method_type, $payment_method_details );
				$this->store_card_details_meta_for_order( $order, $payment_method_type, $payment_method_details );
			}

			if ( $is_subscription_payment_method_change ) {
				$this->with_stock_reduction_disabled(
					function () use ( $order, $intent ) {
						$this->order_service->update_order_status_from_intent( $order, $intent );
					}
				);
			} else {
				$this->order_service->update_order_status_from_intent( $order, $intent );
			}

			if ( $intent->is_authorized() ) {
				// Stock reduction is left to WooCommerce core (see note in process_payment_for_order()).
				if ( ! $is_subscription_payment_method_change ) {
					WC()->cart->empty_cart();
				}

				$return_url = $this->get_return_url( $order );

				if ( $is_subscription_payment_method_change ) {
					$this->maybe_update_subscription_payment_method( $order );
					$return_url = method_exists( $order, 'get_view_order_url' ) ? $order->get_view_order_url() : $this->get_return_url( $order );
				}

				// Send back redirect URL in the successful case.
				echo wp_json_encode(
					[
						'return_url' => $return_url,
					]
				);
				wp_die();
			}
		} catch ( Intent_Authentication_Exception $e ) {
			$error_code = $e->get_error_code();

			switch ( $error_code ) {
				case 'intent_id_mismatch':
				case 'empty_intent_id': // The empty_intent_id case needs the same handling.
					$note = sprintf(
						WC_Payments_Utils::esc_interpolated_html(
							/* translators: %1: transaction ID of the payment or a translated string indicating an unknown ID. */
							__( 'A payment with ID <code>%1$s</code> was used in an attempt to pay for this order. This payment intent ID does not match any payments for this order, so it was ignored and the order was not updated.', 'woocommerce-payments' ),
							[
								'code' => '<code>',
							]
						),
						$intent_id_received
					);
					$order->add_order_note( $note );
					break;
			}

			// Send back error so it can be displayed to the customer.
			echo wp_json_encode(
				[
					'error' => [
						'message' => $e->getMessage(),
					],
				]
			);
			wp_die();
		} catch ( Exception $e ) {
			// Send back error so it can be displayed to the customer.
			echo wp_json_encode(
				[
					'error' => [
						'message' => $e->getMessage(),
					],
				]
			);
			wp_die();
		}
	}


/** Function ::remove_variations() called by wp_ajax hooks: {'woocommerce_remove_variations', 'woocommerce_remove_variation'} **/
/** Parameters found in function ::remove_variations(): {"post": ["variation_id", "variation_ids"]} **/
function remove_variations() {

		if ( isset( $_POST['variation_id'] ) ) { // removing single variation

			check_ajax_referer( 'delete-variation', 'security' );
			$variation_ids = array( $_POST['variation_id'] );

		} else {  // removing multiple variations

			check_ajax_referer( 'delete-variations', 'security' );
			$variation_ids = (array) $_POST['variation_ids'];

		}

		foreach ( $variation_ids as $index => $variation_id ) {

			$variation_post = get_post( $variation_id );

			if ( $variation_post && $variation_post->post_type == 'product_variation' ) {

				$variation_product = wc_get_product( $variation_id );

				if ( $variation_product && $variation_product->is_type( 'subscription_variation' ) ) {

					wp_trash_post( $variation_id );

					// Prevent WooCommerce deleting the variation
					if ( isset( $_POST['variation_id'] ) ) {
						die();
					} else {
						unset( $_POST['variation_ids'][ $index ] );
					}
				}
			}
		}
	}


/** Function validate_variation_deletion() called by wp_ajax hooks: {'wcs_validate_variation_deletion'} **/
/** Parameters found in function validate_variation_deletion(): {"post": ["variation_id"]} **/
function validate_variation_deletion() {
		check_admin_referer( 'wc_subscriptions_admin', 'nonce' );

		$variation_id  = absint( $_POST['variation_id'] );
		$subscriptions = wcs_get_subscriptions_for_product( $variation_id, 'ids', array( 'limit' => 1 ) );

		wp_send_json( array( 'can_remove' => empty( $subscriptions ) ? 'yes' : 'no' ) );
	}


