<?php
/***
*
*Found actions: 76
*Found functions:70
*Extracted functions:70
*Total parameter names extracted: 33
*Overview: {'wp_ajax_hide_notice': {'otgs-hide-notice'}, 'switch_product_variations_language': {'wpml_switch_post_language'}, 'save_shipping_zone_method_from_ajax': {'woocommerce_shipping_zone_methods_save_settings'}, 'update_currency_lang': {'wcml_update_currency_lang'}, 'show_message': {'icl-show-admin-message'}, 'plugin_upgrade_custom_errors': {'update-plugin'}, 'save': {'save_site_key'}, 'set_order_currency_on_ajax_update': {'wcml_order_set_currency'}, 'set_currency_mode': {'wcml_set_currency_mode'}, 'trbl_sync_stock': {'trbl_sync_stock'}, 'wcml_sync_taxonomies_in_content_preview': {'wcml_tt_sync_taxonomies_in_content_preview'}, 'wcml_update_base_translation': {'wcml_update_base_translation'}, 'prevent_plugins_update_on_updates_screen': {'update-plugin'}, 'process_report_instantly': {'end_user_get_info'}, 'wcml_sync_taxonomies_in_content': {'wcml_tt_sync_taxonomies_in_content'}, 'delete_currency': {'wcml_delete_currency'}, 'update_exchange_rates_ajax': {'wcml_update_exchange_rates'}, 'wcml_refresh_fragments': {'nopriv_woocommerce_get_refreshed_fragments', 'woocommerce_add_to_cart', 'woocommerce_get_refreshed_fragments', 'nopriv_woocommerce_add_to_cart'}, 'wp_ajax_dismiss_group': {'otgs-dismiss-group'}, 'wcml_currencies_order': {'wcml_currencies_order'}, 'remove_translations_for_variations': {'woocommerce_remove_variations'}, 'trbl_sync_variations': {'trbl_sync_variations'}, 'register_reviews_in_st': {'register_reviews_in_st'}, '::dismissNotice': {'installer_dismiss_nag'}, 'auto_generate_slug': {'wcml_editor_auto_slug'}, 'wp_ajax_dismiss_notice': {'otgs-dismiss-notice'}, 'update': {'update_site_key'}, 'hide_upgrade_notice': {'wcml_hide_notice'}, 'switch_currency': {'nopriv_wcml_switch_currency', 'wcml_switch_currency'}, 'hide_message': {'icl-hide-admin-message'}, 'installer_theme_frontend_selected_tab': {'installer_theme_frontend_selected_tab'}, 'wcml_delete_currency_switcher': {'wcml_delete_currency_switcher'}, 'updateSettingsOnAjaxSave': {'wcml_save_payment_gateways'}, 'get_auto_exchange_rate': {'wcml_get_auto_exchange_rate'}, 'wcml_sync_product_variations': {'wcml_sync_product_variations'}, 'activate_plugin': {'installer_activate_plugin'}, 'remove_notifications': {'icl_remove_notifications'}, 'fix_translated_variations_relationships': {'fix_translated_variations_relationships'}, 'order_delete_items': {'wcml_order_delete_items'}, 'restore_notifications': {'icl_restore_notifications'}, 'wcml_currencies_switcher_save_settings': {'wcml_currencies_switcher_save_settings'}, 'hide_wcml_translations_message': {'hide_wcml_translations_message'}, 'trbl_fix_product_type_terms': {'trbl_fix_product_type_terms'}, 'wcml_cart_clear_removed_items': {'wcml_cart_clear_removed_items', 'nopriv_wcml_cart_clear_removed_items'}, 'update_woocommerce_shipping_settings_for_class_costs_from_ajax': {'woocommerce_shipping_zone_methods_save_settings'}, 'update_taxonomy_in_variations': {'wpml_tt_save_term_translation'}, 'switch_to_current': {'nopriv_woocommerce_checkout', 'woocommerce_checkout'}, 'legacy_remove_custom_rates': {'legacy_remove_custom_rates'}, 'set_max_mind_key': {'wcml_set_max_mind_key'}, 'sync_deleted_meta': {'sync_deleted_meta'}, 'trbl_sync_categories': {'trbl_sync_categories'}, 'wcml_currencies_switcher_preview': {'wcml_currencies_switcher_preview'}, 'toggleDismiss': {'wpml_dismiss_notice'}, 'set_booking_currency_ajax': {'wcml_booking_set_currency'}, 'save_currency': {'wcml_save_currency'}, 'set_reports_currency': {'wcml_reports_set_currency'}, 'recommendationSuccess': {'installer_recommendation_success'}, 'update_settings_from_warning': {'wcml_ignore_warning'}, 'download_plugin_ajax_handler': {'installer_download_plugin'}, 'set_channel': {'installer_set_channel'}, 'remove': {'remove_site_key'}, 'set_dashboard_currency_ajax': {'wcml_dashboard_set_currency'}, 'email_refresh_in_ajax': {'woocommerce_mark_order_status'}, 'update_default_currency_ajax': {'wcml_update_default_currency'}, 'legacy_update_custom_rates': {'legacy_update_custom_rates'}, 'find': {'find_account'}, 'trbl_gallery_images': {'trbl_gallery_images'}, 'trbl_duplicate_terms': {'trbl_duplicate_terms'}, 'remove_variation_ajax': {'woocommerce_remove_variation'}, 'unregister_abort_messages_ajax': {'woocommerce_table_rate_delete'}}
*
***/

/** Function wp_ajax_hide_notice() called by wp_ajax hooks: {'otgs-hide-notice'} **/
/** No params detected :-/ **/


/** Function switch_product_variations_language() called by wp_ajax hooks: {'wpml_switch_post_language'} **/
/** Parameters found in function switch_product_variations_language(): {"post": ["nonce", "wpml_to", "wpml_post_id"]} **/
function switch_product_variations_language() {
		if ( ! isset( $_POST['nonce'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( $_POST['nonce'], \WPML_Post_Edit_Ajax::AJAX_ACTION_SWITCH_POST_LANGUAGE ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_products' ) ) {
			return;
		}

		$lang_to = false;
		$post_id = false;

		if ( isset( $_POST['wpml_to'] ) ) {
			$lang_to = $_POST['wpml_to'];
		}
		if ( isset( $_POST['wpml_post_id'] ) ) {
			$post_id = $_POST['wpml_post_id'];
		}

		if ( $post_id && $lang_to && get_post_type( $post_id ) == 'product' ) {
			$product_variations = $this->woocommerce_wpml->sync_variations_data->get_product_variations( $post_id );
			foreach ( $product_variations as $product_variation ) {
				$trid                      = $this->sitepress->get_element_trid( $product_variation->ID, 'post_product_variation' );
				$current_prod_variation_id = apply_filters( 'wpml_object_id', $product_variation->ID, 'product_variation', false, $lang_to );
				if ( is_null( $current_prod_variation_id ) ) {
					$this->sitepress->set_element_language_details( $product_variation->ID, 'post_product_variation', $trid, $lang_to );

					foreach ( get_post_custom( $product_variation->ID ) as $meta_key => $meta ) {
						foreach ( $meta as $meta_value ) {
							if ( substr( $meta_key, 0, 10 ) == 'attribute_' ) {
								$trn_post_meta = $this->woocommerce_wpml->attributes->get_translated_variation_attribute_post_meta( $meta_value, $meta_key, $product_variation->ID, $product_variation->ID, $lang_to );
								update_post_meta( $product_variation->ID, $trn_post_meta['meta_key'], $trn_post_meta['meta_value'] );
							}
						}
					}
				}
			}
		}
	}


/** Function save_shipping_zone_method_from_ajax() called by wp_ajax hooks: {'woocommerce_shipping_zone_methods_save_settings'} **/
/** Parameters found in function save_shipping_zone_method_from_ajax(): {"post": ["wc_shipping_zones_nonce", "data", "instance_id"]} **/
function save_shipping_zone_method_from_ajax() {
		if ( ! isset( $_POST['wc_shipping_zones_nonce'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( wp_unslash( $_POST['wc_shipping_zones_nonce'] ), 'wc_shipping_zones_nonce' ) ) {
			return;
		}
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		foreach ( $_POST['data'] as $key => $value ) {
			if ( strstr( $key, '_title' ) ) {
				$shipping_id = str_replace( 'woocommerce_', '', $key );
				$shipping_id = str_replace( '_title', '', $shipping_id );
				$this->register_shipping_title( $shipping_id . $_POST['instance_id'], $value );
				break;
			}
		}
	}


/** Function update_currency_lang() called by wp_ajax hooks: {'wcml_update_currency_lang'} **/
/** No params detected :-/ **/


/** Function show_message() called by wp_ajax hooks: {'icl-show-admin-message'} **/
/** No params detected :-/ **/


/** Function plugin_upgrade_custom_errors() called by wp_ajax hooks: {'update-plugin'} **/
/** Parameters found in function plugin_upgrade_custom_errors(): {"request": ["action", "plugin"], "post": ["slug"]} **/
function plugin_upgrade_custom_errors() {
		if ( isset( $_REQUEST['action'] ) ) {
			$action = isset( $_REQUEST['action'] ) ? sanitize_text_field( $_REQUEST['action'] ) : '';

			if ( 'update-selected' == $action ) {
				global $plugins;

				if ( isset( $plugins ) && is_array( $plugins ) ) {
					foreach ( $plugins as $k => $plugin ) {
						$plugin_repository = false;

						$wp_plugin_slug = dirname( $plugin );

						foreach ( $this->settings()['repositories'] as $repository_id => $repository ) {
							foreach ( $repository['data']['packages'] as $package ) {
								foreach ( $package['products'] as $product ) {
									foreach ( $product['plugins'] as $plugin_slug ) {
										if ( $this->settings()['repositories'][ $repository_id ]['data']['downloads']['plugins'][ $plugin_slug ]['slug'] == $wp_plugin_slug ) {
											$download          = $this->settings()['repositories'][ $repository_id ]['data']['downloads']['plugins'][ $plugin_slug ];
											$plugin_repository = $repository_id;
											$product_name      = $repository['data']['product-name'];
											$plugin_name       = $download['name'];
											$free_on_wporg     = ! empty( $download['free-on-wporg'] ) && $download['channel'] == WP_Installer_Channels::CHANNEL_PRODUCTION;
											break;
										}
									}
								}
							}
						}

						if ( $plugin_repository ) {
							static $sub_cache = array();

							if ( empty( $sub_cache[ $plugin_repository ] ) ) {
								$subscription_data = false;
								$site_key          = $this->get_repository_site_key( $plugin_repository );

								if ( ! $site_key ) {
									list( $plugin_repository, $site_key ) = $this->match_product_in_external_repository( $plugin_repository, $wp_plugin_slug );
								}

								if ( $site_key ) {
									try {
										$subscriptionManagerFactory = new SubscriptionManagerFactory( $this->get_settings() );
										$subscriptionManager        = $subscriptionManagerFactory->create( $plugin_repository, $this->repositories[ $plugin_repository ]['api-url'] );
										list ( $subscription_data, $site_key_data ) = $subscriptionManager->fetch( $site_key, self::SITE_KEY_VALIDATION_SOURCE_REVALIDATION );
									} catch ( Exception $e ) {
									}
								}

								$sub_cache[ $plugin_repository ]['site_key']          = $site_key;
								$sub_cache[ $plugin_repository ]['subscription_data'] = $subscription_data;
							} else {
								$site_key          = $sub_cache[ $plugin_repository ]['site_key'];
								$subscription_data = $sub_cache[ $plugin_repository ]['subscription_data'];
							}

							if ( ! $site_key && ( ! empty( $free_on_wporg ) || $this->should_fallback_under_wp_org_repo( $download, $site_key ) ) ) {
								continue;
							}

							if ( empty( $site_key ) || empty( $subscription_data ) ) {
								$error_message = sprintf( __( "%s cannot update because your site's registration is not valid. Please %sregister %s%s again for this site first.", 'installer' ),
									'<strong>' . $plugin_name . '</strong>', '<a target="_top" href="' . $this->menu_url() . '&validate_repository=' . $plugin_repository .
									                                         '#repository-' . $plugin_repository . '">', $product_name, '</a>' );

								echo '<div class="updated error"><p>' . $error_message . '</p></div>';

								unset( $plugins[ $k ] );
							}
						}
					}
				}
			}


			if ( 'upgrade-plugin' == $action || 'update-plugin' == $action ) {
				$plugin = isset( $_REQUEST['plugin'] ) ? trim( sanitize_text_field( $_REQUEST['plugin'] ) ) : '';

				$wp_plugin_slug = dirname( $plugin );

				$plugin_repository = false;

				foreach ( $this->settings()['repositories'] as $repository_id => $repository ) {
					foreach ( $repository['data']['packages'] as $package ) {
						foreach ( $package['products'] as $product ) {
							foreach ( $product['plugins'] as $plugin_slug ) {
								$download = $this->settings()['repositories'][ $repository_id ]['data']['downloads']['plugins'][ $plugin_slug ];

								if ( $download['slug'] == $wp_plugin_slug ) {
									$plugin_repository = $repository_id;
									$product_name      = $repository['data']['product-name'];
									$plugin_name       = $download['name'];
									$free_on_wporg     = ! empty( $download['free-on-wporg'] ) && $download['channel'] == WP_Installer_Channels::CHANNEL_PRODUCTION;
									break;
								}
							}
						}
					}
				}

				if ( $plugin_repository ) {
					$site_key = $this->get_repository_site_key( $plugin_repository );

					if ( ! $site_key ) {
						list( $plugin_repository, $site_key ) = $this->match_product_in_external_repository( $plugin_repository, $wp_plugin_slug );
					}

					if ( $site_key ) {
						try {
							$subscriptionManagerFactory = new SubscriptionManagerFactory( $this->get_settings() );
							$subscriptionManager        = $subscriptionManagerFactory->create( $plugin_repository, $this->repositories[ $plugin_repository ]['api-url'] );
							list ( $subscription_data, $site_key_data ) = $subscriptionManager->fetch( $site_key, self::SITE_KEY_VALIDATION_SOURCE_REVALIDATION );
						} catch ( Exception $e ) {
							$subscription_data = false;
						}
					}

					$no_subscription = empty( $site_key ) || empty( $subscription_data );
					$not_on_wporg    = empty( $free_on_wporg ) && ! $this->should_fallback_under_wp_org_repo( $download, $site_key );

					if ( $no_subscription && $not_on_wporg ) {
						$error_message = sprintf( __( "%s cannot update because your site's registration is not valid. Please %sregister %s%s again for this site first.", 'installer' ),
							'<strong>' . $plugin_name . '</strong>', '<a href="' . $this->menu_url() . '&validate_repository=' . $plugin_repository .
							                                         '#repository-' . $plugin_repository . '">', $product_name, '</a>' );

						if ( defined( 'DOING_AJAX' ) ) {

							$status = array(
								'update'     => 'plugin',
								'plugin'     => $plugin,
								'slug'       => sanitize_key( $_POST['slug'] ),
								'oldVersion' => '',
								'newVersion' => '',
							);

							$status['errorCode'] = 'wp_installer_invalid_subscription';
							$status['error']     = $error_message;

							wp_send_json_error( $status );
						} else {
							echo '<div class="updated error"><p>' . $error_message . '</p></div>';


							echo '<div class="wrap">';
							echo '<h2>' . __( 'Update Plugin', 'installer' ) . '</h2>';
							echo '<a href="' . admin_url( 'plugins.php' ) . '">' . __( 'Return to the plugins page', 'installer' ) . '</a>';
							echo '</div>';
							require_once( ABSPATH . 'wp-admin/admin-footer.php' );
							exit;
						}
					}
				}
			}
		}
	}


/** Function save() called by wp_ajax hooks: {'save_site_key'} **/
/** No params detected :-/ **/


/** Function set_order_currency_on_ajax_update() called by wp_ajax hooks: {'wcml_order_set_currency'} **/
/** No params detected :-/ **/


/** Function set_currency_mode() called by wp_ajax hooks: {'wcml_set_currency_mode'} **/
/** No params detected :-/ **/


/** Function trbl_sync_stock() called by wp_ajax hooks: {'trbl_sync_stock'} **/
/** No params detected :-/ **/


/** Function wcml_sync_taxonomies_in_content_preview() called by wp_ajax hooks: {'wcml_tt_sync_taxonomies_in_content_preview'} **/
/** Parameters found in function wcml_sync_taxonomies_in_content_preview(): {"post": ["taxonomy"]} **/
function wcml_sync_taxonomies_in_content_preview() {
		$nonce = filter_input( INPUT_POST, 'wcml_nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'wcml_sync_taxonomies_in_content_preview' ) ) {
			die( 'Invalid nonce' );
		}

		global $wp_taxonomies;

		$html = $message = $errors = '';

		if ( isset( $wp_taxonomies[ $_POST['taxonomy'] ] ) ) {
			$object_types = $wp_taxonomies[ $_POST['taxonomy'] ]->object_type;

			foreach ( $object_types as $object_type ) {

				$html .= $this->render_assignment_status( $object_type, $_POST['taxonomy'] );

			}
		} else {
			$errors = sprintf(
				/* translators: %s is a taxonomy name */
				__( 'Invalid taxonomy %s', 'woocommerce-multilingual' ),
				$_POST['taxonomy']
			);
		}

		echo json_encode(
			[
				'html'    => $html,
				'message' => $message,
				'errors'  => $errors,
			]
		);
		exit;
	}


/** Function wcml_update_base_translation() called by wp_ajax hooks: {'wcml_update_base_translation'} **/
/** Parameters found in function wcml_update_base_translation(): {"post": ["base", "base_value", "base_translation", "language"]} **/
function wcml_update_base_translation() {

		$nonce = filter_input( INPUT_POST, 'wcml_nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'wcml_update_base_translation' ) ) {
			die( 'Invalid nonce' );
		}

		$original_base       = $_POST['base'];
		$original_base_value = $_POST['base_value'];
		$base_translation    = wc_sanitize_permalink( trim( $_POST['base_translation'], '/' ) );
		$language            = $_POST['language'];

		if ( $original_base == 'shop' ) {
			$original_shop_id   = wc_get_page_id( 'shop' );
			$translated_shop_id = apply_filters( 'wpml_object_id', $original_shop_id, 'page', false, $language );

			if ( ! is_null( $translated_shop_id ) ) {

				$trnsl_shop_obj = get_post( $translated_shop_id );
				$new_slug       = wp_unique_post_slug( $base_translation, $translated_shop_id, $trnsl_shop_obj->post_status, $trnsl_shop_obj->post_type, $trnsl_shop_obj->post_parent );
				$this->wpdb->update( $this->wpdb->posts, [ 'post_name' => $new_slug ], [ 'ID' => $translated_shop_id ] );

			}
		} else {
			if ( in_array( $original_base, [ 'product', WCTaxonomies::TAXONOMY_PRODUCT_CATEGORY, WCTaxonomies::TAXONOMY_PRODUCT_TAG, 'attribute' ] ) ) {
				$string_id = icl_get_string_id( $original_base_value, Strings::TRANSLATION_DOMAIN, Strings::getStringName( $original_base ) );
			} elseif ( strpos( $original_base, 'attribute_slug-' ) === 0 ) {
				$slug = preg_replace( '#^attribute_slug-#', '', $original_base );
				do_action( 'wpml_register_single_string', Strings::TRANSLATION_DOMAIN, Strings::getStringName( 'attribute_slug', $slug ), $slug );
				$string_id = icl_get_string_id( $original_base_value, Strings::TRANSLATION_DOMAIN, Strings::getStringName( 'attribute_slug', $slug ) );
			} else {
				$string_id = icl_get_string_id( $original_base_value, $this->get_endpoint_string_context(), $original_base );
				if ( ! $string_id && function_exists( 'icl_register_string' ) ) {
					$string_id = icl_register_string( $this->get_endpoint_string_context(), $original_base, $original_base_value );
				}
			}

			icl_add_string_translation( $string_id, $language, $base_translation, ICL_STRING_TRANSLATION_COMPLETE );
		}

		if ( in_array( $original_base, [ WCTaxonomies::TAXONOMY_PRODUCT_CATEGORY, WCTaxonomies::TAXONOMY_PRODUCT_TAG ], true ) ) {
			do_action( 'wpml_activate_slug_translation', $original_base, $original_base_value, WPML_Slug_Translation_Factory::TAX );
		}

		$edit_base = new WCML_Store_URLs_Edit_Base_UI( $original_base, $language, $this->woocommerce_wpml, $this->sitepress );
		$html      = $edit_base->get_view();

		echo json_encode( $html );
		die();

	}


/** Function prevent_plugins_update_on_updates_screen() called by wp_ajax hooks: {'update-plugin'} **/
/** Parameters found in function prevent_plugins_update_on_updates_screen(): {"request": ["action", "plugin"]} **/
function prevent_plugins_update_on_updates_screen() {

		if ( isset( $_REQUEST['action'] ) ) {

			$action = isset( $_REQUEST['action'] ) ? sanitize_text_field( $_REQUEST['action'] ) : '';

			$installer_settings = WP_Installer()->settings;

			if ( 'update-selected' == $action ) {

				global $plugins;

				if ( isset( $plugins ) && is_array( $plugins ) ) {

					foreach ( $plugins as $k => $plugin ) {

						$wp_plugin_slug = dirname( $plugin );

						foreach ( $installer_settings['repositories'] as $repository_id => $repository ) {

							if ( $this->is_win_paths_exception( $repository_id ) ) {

								foreach ( $repository['data']['packages'] as $package ) {

									foreach ( $package['products'] as $product ) {

										foreach ( $product['plugins'] as $plugin_slug ) {

											$download = $installer_settings['repositories'][ $repository_id ]['data']['downloads']['plugins'][ $plugin_slug ];

											if ( $download['slug'] == $wp_plugin_slug && empty( $download['free-on-wporg'] ) ) {

												echo '<div class="updated error"><p>' . $this->win_paths_exception_message() .
												     ' <strong>(' . $download['name'] . ')</strong>' . '</p></div>';
												unset( $plugins[ $k ] );

												break( 3 );

											}

										}

									}

								}


							}

						}

					}

				}

			}


			if ( 'upgrade-plugin' == $action || 'update-plugin' == $action ) {

				$plugin = isset( $_REQUEST['plugin'] ) ? trim( sanitize_text_field( $_REQUEST['plugin'] ) ) : '';

				$wp_plugin_slug = dirname( $plugin );

				foreach ( $installer_settings['repositories'] as $repository_id => $repository ) {

					if ( $this->is_win_paths_exception( $repository_id ) ) {
						foreach ( $repository['data']['packages'] as $package ) {

							foreach ( $package['products'] as $product ) {

								foreach ( $product['plugins'] as $plugin_slug ) {
									$download = $installer_settings['repositories'][ $repository_id ]['data']['downloads']['plugins'][ $plugin_slug ];

									if ( $download['slug'] == $wp_plugin_slug && empty ( $download['free-on-wporg'] ) ) {

										echo '<div class="updated error"><p>' . $this->win_paths_exception_message() . '</p></div>';

										echo '<div class="wrap">';
										echo '<h2>' . __( 'Update Plugin' ) . '</h2>';
										echo '<a href="' . admin_url( 'update-core.php' ) . '">' . __( 'Return to the updates page', 'installer' ) . '</a>';
										echo '</div>';
										require_once( ABSPATH . 'wp-admin/admin-footer.php' );
										exit;

									}

								}

							}

						}
					}

				}

			}
		}

	}


/** Function process_report_instantly() called by wp_ajax hooks: {'end_user_get_info'} **/
/** No params detected :-/ **/


/** Function wcml_sync_taxonomies_in_content() called by wp_ajax hooks: {'wcml_tt_sync_taxonomies_in_content'} **/
/** Parameters found in function wcml_sync_taxonomies_in_content(): {"post": ["taxonomy", "post"]} **/
function wcml_sync_taxonomies_in_content() {
		$nonce = filter_input( INPUT_POST, 'wcml_nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'wcml_sync_taxonomies_in_content' ) ) {
			die( 'Invalid nonce' );
		}

		global $wp_taxonomies;

		$html = $errors = '';

		if ( isset( $wp_taxonomies[ $_POST['taxonomy'] ] ) ) {
			$html .= $this->render_assignment_status( $_POST['post'], $_POST['taxonomy'], false );

		} else {
			$errors .= sprintf(
				/* translators: %s is a taxonomy name */
				__( 'Invalid taxonomy %s', 'woocommerce-multilingual' ),
				$_POST['taxonomy']
			);
		}

		echo json_encode(
			[
				'html'   => $html,
				'errors' => $errors,
			]
		);
		exit;
	}


/** Function delete_currency() called by wp_ajax hooks: {'wcml_delete_currency'} **/
/** No params detected :-/ **/


/** Function update_exchange_rates_ajax() called by wp_ajax hooks: {'wcml_update_exchange_rates'} **/
/** Parameters found in function update_exchange_rates_ajax(): {"post": ["wcml_nonce"]} **/
function update_exchange_rates_ajax() {
		$response = [];
		if ( wp_create_nonce( 'update-exchange-rates' ) === $_POST['wcml_nonce'] ) {
			try {
				$rates                    = $this->update_exchange_rates();
				$response['success']      = 1;
				$response['last_updated'] = date_i18n( 'F j, Y g:i a', $this->settings['last_updated'] );
				$response['rates']        = $rates;
			} catch ( Exception $e ) {
				$response['success'] = 0;
				$response['error']   = $e->getMessage();
				$response['service'] = $this->settings['service'];
			}
		} else {
			$response['success'] = 0;
			$response['error']   = 'Invalid nonce';
		}
		wp_send_json( $response );
	}


/** Function wcml_refresh_fragments() called by wp_ajax hooks: {'nopriv_woocommerce_get_refreshed_fragments', 'woocommerce_add_to_cart', 'woocommerce_get_refreshed_fragments', 'nopriv_woocommerce_add_to_cart'} **/
/** No params detected :-/ **/


/** Function wp_ajax_dismiss_group() called by wp_ajax hooks: {'otgs-dismiss-group'} **/
/** No params detected :-/ **/


/** Function wcml_currencies_order() called by wp_ajax hooks: {'wcml_currencies_order'} **/
/** Parameters found in function wcml_currencies_order(): {"post": ["wcml_nonce", "order"]} **/
function wcml_currencies_order() {
		$nonce = array_key_exists( 'wcml_nonce', $_POST ) ? sanitize_text_field( $_POST['wcml_nonce'] ) : false;
		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'set_currencies_order_nonce' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		$this->woocommerce_wpml->settings['currencies_order'] = explode( ';', $_POST['order'] );
		$this->woocommerce_wpml->update_settings( $this->woocommerce_wpml->settings );
		wp_send_json_success( [ 'message' => esc_html__( 'Currencies order updated', 'woocommerce-multilingual' ) ] );
	}


/** Function remove_translations_for_variations() called by wp_ajax hooks: {'woocommerce_remove_variations'} **/
/** Parameters found in function remove_translations_for_variations(): {"post": ["variation_ids"]} **/
function remove_translations_for_variations() {
		check_ajax_referer( 'delete-variations', 'security' );

		if ( ! current_user_can( 'edit_products' ) ) {
			die( -1 );
		}
		$variation_ids = (array) $_POST['variation_ids'];

		foreach ( $variation_ids as $variation_id ) {
			$trid         = $this->sitepress->get_element_trid( $variation_id, 'post_product_variation' );
			$translations = $this->sitepress->get_element_translations( $trid, 'post_product_variation' );

			foreach ( $translations as $translation ) {
				if ( ! $translation->original ) {
					wp_delete_post( $translation->element_id );
				}
			}
		}
	}


/** Function trbl_sync_variations() called by wp_ajax hooks: {'trbl_sync_variations'} **/
/** No params detected :-/ **/


/** Function register_reviews_in_st() called by wp_ajax hooks: {'register_reviews_in_st'} **/
/** No params detected :-/ **/


/** Function ::dismissNotice() called by wp_ajax hooks: {'installer_dismiss_nag'} **/
/** No params detected :-/ **/


/** Function auto_generate_slug() called by wp_ajax hooks: {'wcml_editor_auto_slug'} **/
/** Parameters found in function auto_generate_slug(): {"post": ["job_id"]} **/
function auto_generate_slug() {
		global $wpdb;

		if ( ! check_ajax_referer( self::AUTO_SLUG_NONCE, 'wcml_nonce', false ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		$title = filter_input( INPUT_POST, 'title' );

		$post_name = urldecode( sanitize_title( $title ) );
		$job_id    = intval( $_POST['job_id'] );

		$post_id_sql = "
            SELECT t.element_id
            FROM {$wpdb->prefix}icl_translations t
                LEFT JOIN {$wpdb->prefix}icl_translation_status s ON t.translation_id = s.translation_id
                LEFT JOIN {$wpdb->prefix}icl_translate_job j ON s.rid = j.rid
            WHERE j.job_id = %d
        ";
		$post_id     = $wpdb->get_var( $wpdb->prepare( $post_id_sql, $job_id ) );

		if ( $post_id ) {
			$slug = wp_unique_post_slug( $post_name, $post_id, 'publish', 'product', 0 );
		} else {
			$lang_sql = "
                SELECT t.language_code
                FROM {$wpdb->prefix}icl_translations t
                    LEFT JOIN {$wpdb->prefix}icl_translation_status s ON t.translation_id = s.translation_id
                    LEFT JOIN {$wpdb->prefix}icl_translate_job j ON s.rid = j.rid
                WHERE j.job_id = %d
            ";
			$lang     = $wpdb->get_var( $wpdb->prepare( $lang_sql, $job_id ) );

			$check_sql       = "
                SELECT p.post_name 
                FROM {$wpdb->posts} p 
                    LEFT JOIN {$wpdb->prefix}icl_translations t 
                        ON t.element_id = p.ID AND t.element_type='post_product'   
                WHERE p.post_name = %s AND t.language_code = %s LIMIT 1
            ";
			$post_name_check = $wpdb->get_var( $wpdb->prepare( $check_sql, $post_name, $lang ) );

			if ( $post_name_check ) {
				$suffix = 2;
				do {

					$alt_post_name   = _truncate_post_slug( $post_name, 200 - ( strlen( (string) $suffix ) + 1 ) ) . "-$suffix";
					$post_name_check = $wpdb->get_var( $wpdb->prepare( $check_sql, $alt_post_name, $lang ) );
					$suffix++;

				} while ( $post_name_check );
				$slug = $alt_post_name;
			} else {
				$slug = $post_name;
			}
		}

		wp_send_json( [ 'slug' => $slug ] );

	}


/** Function wp_ajax_dismiss_notice() called by wp_ajax hooks: {'otgs-dismiss-notice'} **/
/** No params detected :-/ **/


/** Function update() called by wp_ajax hooks: {'update_site_key'} **/
/** No params detected :-/ **/


/** Function hide_upgrade_notice() called by wp_ajax hooks: {'wcml_hide_notice'} **/
/** Parameters found in function hide_upgrade_notice(): {"post": ["notice"]} **/
function hide_upgrade_notice( $k ) {
		if ( empty( $k ) ) {
			$k = $_POST['notice'];
		}

		$wcml_settings = get_option( '_wcml_settings' );
		if ( isset( $wcml_settings['notifications'][ $k ] ) ) {
			$wcml_settings['notifications'][ $k ]['show'] = 0;
			update_option( '_wcml_settings', $wcml_settings );
		}
	}


/** Function switch_currency() called by wp_ajax hooks: {'nopriv_wcml_switch_currency', 'wcml_switch_currency'} **/
/** No params detected :-/ **/


/** Function hide_message() called by wp_ajax hooks: {'icl-hide-admin-message'} **/
/** Parameters found in function hide_message(): {"post": ["dismiss"]} **/
function hide_message() {

			$message_id = self::get_message_id();
			$dismiss    = isset( $_POST['dismiss'] ) ? $_POST['dismiss'] : false;
			if ( ! self::message_id_exists( $message_id ) ) {
				exit;
			}

			self::set_message_display( $message_id, false, 'hide', 'hidden', 'hide_per_user' );

			if ( $dismiss ) {
				self::set_message_display( $message_id, false, 'dismiss', 'dismissed', 'dismiss_per_user' );
			} else {
				$messages = self::get_messages();
				$message  = $messages['messages'][ $message_id ];
				if ( $message && isset( $message['fallback_text'] ) && $message['fallback_text'] ) {
					echo self::display_message( $message_id, $message['fallback_text'], $message['fallback_type'], $message['fallback_classes'], false, false, true, true );
				}
			}
			exit;
		}


/** Function installer_theme_frontend_selected_tab() called by wp_ajax hooks: {'installer_theme_frontend_selected_tab'} **/
/** Parameters found in function installer_theme_frontend_selected_tab(): {"post": ["frontend_tab_selected"]} **/
function installer_theme_frontend_selected_tab() {
        if ( isset($_POST["frontend_tab_selected"]) ) {
            check_ajax_referer( 'installer_theme_frontend_selected_tab', 'installer_theme_frontend_selected_tab_nonce' );

            $frontend_tab_selected = sanitize_text_field( $_POST['frontend_tab_selected'] );
            if ( !(empty($frontend_tab_selected)) ) {
                update_option( 'wp_installer_clientside_active_tab', $frontend_tab_selected, false );

                if ( isset($this->theme_user_registration[$frontend_tab_selected]) ) {
                    if ( !($this->theme_user_registration[$frontend_tab_selected]) ) {

                        if ( is_multisite() ) {
                            $admin_url_passed = network_admin_url();
                        } else {
                            $admin_url_passed = admin_url();
                        }

                        $registration_url = $admin_url_passed . 'plugin-install.php?tab=commercial#installer_repo_' . $frontend_tab_selected;

                        $theme_repo_name = $this->installer_theme_get_repo_product_name( $frontend_tab_selected );;
                        $response['unregistered_messages'] = sprintf( __( 'To install and update %s, please %sregister%s %s for this site.', 'installer' ),
                            $theme_repo_name, '<a href="' . $registration_url . '">', '</a>', $theme_repo_name );

                    }
                }

                $response['output'] = $frontend_tab_selected;
                echo json_encode( $response );
            }
            die();
        }
        die();
    }


/** Function wcml_delete_currency_switcher() called by wp_ajax hooks: {'wcml_delete_currency_switcher'} **/
/** Parameters found in function wcml_delete_currency_switcher(): {"post": ["wcml_nonce", "switcher_id"]} **/
function wcml_delete_currency_switcher() {
		$nonce = array_key_exists( 'wcml_nonce', $_POST ) ? sanitize_text_field( $_POST['wcml_nonce'] ) : false;
		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'delete_currency_switcher' ) ) {
			wp_send_json_error();
		}

		$switcher_id = sanitize_text_field( $_POST['switcher_id'] );

		$wcml_settings = $this->woocommerce_wpml->get_settings();

		unset( $wcml_settings['currency_switchers'][ $switcher_id ] );

		$this->woocommerce_wpml->update_settings( $wcml_settings );

		$sidebars_widgets = $this->get_sidebars_widgets();

		foreach ( $sidebars_widgets as $sidebar => $widgets ) {
			if ( $sidebar != $switcher_id ) {
				continue;
			}

			if ( is_array( $widgets ) ) {
				foreach ( $widgets as $key => $widget_id ) {
					if ( strpos( $widget_id, WCML_Currency_Switcher_Widget::SLUG ) === 0 ) {
						unset( $sidebars_widgets[ $sidebar ][ $key ] );
					}
				}
			}
		}

		$this->update_sidebars_widgets( $sidebars_widgets );

		wp_send_json_success();
	}


/** Function updateSettingsOnAjaxSave() called by wp_ajax hooks: {'wcml_save_payment_gateways'} **/
/** Parameters found in function updateSettingsOnAjaxSave(): {"post": ["nonce"]} **/
function updateSettingsOnAjaxSave() {
		$nonce = array_key_exists( 'nonce', $_POST ) ? sanitize_text_field( $_POST['nonce'] ) : false;
		if ( ! $nonce || ! wp_verify_nonce( $nonce, self::OPTION_KEY ) ) {
			wp_send_json_error();
		}

		$data            = json_decode( stripslashes( Obj::propOr( '{}', 'data', $_POST ) ), true );
		$gatewayId       = Obj::prop( 'gatewayId', $data );
		$gatewaySettings = Obj::prop( 'settings', $data );

		$updated = $this->updateGatewaySettings( $gatewayId, $gatewaySettings );

		if ( $updated ) {
			wp_send_json_success();
		} else {
			wp_send_json_error();
		}

	}


/** Function get_auto_exchange_rate() called by wp_ajax hooks: {'wcml_get_auto_exchange_rate'} **/
/** No params detected :-/ **/


/** Function wcml_sync_product_variations() called by wp_ajax hooks: {'wcml_sync_product_variations'} **/
/** Parameters found in function wcml_sync_product_variations(): {"post": ["languages_processed", "last_post_id"]} **/
function wcml_sync_product_variations( $taxonomy ) {
		$nonce = filter_input( INPUT_POST, 'wcml_nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'wcml_sync_product_variations' ) ) {
			die( 'Invalid nonce' );
		}

		$VARIATIONS_THRESHOLD = 20;

		$wcml_settings = $this->woocommerce_wpml->get_settings();
		$response      = [];

		$taxonomy = filter_input( INPUT_POST, 'taxonomy', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		$languages_processed = intval( $_POST['languages_processed'] );

		$condition = $languages_processed ? '>=' : '>';

		$where = isset( $_POST['last_post_id'] ) && $_POST['last_post_id'] ? ' ID ' . $condition . ' ' . intval( $_POST['last_post_id'] ) . ' AND ' : '';

		$post_ids = $this->wpdb->get_col(
			$this->wpdb->prepare(
				"                
                SELECT DISTINCT tr.object_id 
                FROM {$this->wpdb->term_relationships} tr
                JOIN {$this->wpdb->term_taxonomy} tx on tr.term_taxonomy_id = tx.term_taxonomy_id
                JOIN {$this->wpdb->posts} p ON tr.object_id = p.ID
                JOIN {$this->wpdb->prefix}icl_translations t ON t.element_id = p.ID 
                WHERE {$where} tx.taxonomy = %s AND p.post_type = 'product' AND t.element_type='post_product' AND t.source_language_code IS NULL  
                ORDER BY ID ASC
                
        ",
				$taxonomy
			)
		);

		$variations_processed = 0;
		$posts_processed      = 0;

		if ( $post_ids ) {

			foreach ( $post_ids as $post_id ) {
				$terms       = wp_get_post_terms( $post_id, $taxonomy );
				$terms_count = count( $terms );

				$trid         = $this->sitepress->get_element_trid( $post_id, 'post_product' );
				$translations = $this->sitepress->get_element_translations( $trid, 'post_product' );

				$i = 1;

				foreach ( $translations as $translation ) {

					if ( $i > $languages_processed && $translation->element_id != $post_id ) {
						$this->woocommerce_wpml->sync_product_data->sync_product_taxonomies( $post_id, $translation->element_id, $translation->language_code );
						$this->woocommerce_wpml->sync_variations_data->sync_product_variations( $post_id, $translation->element_id, $translation->language_code, [ 'is_troubleshooting' => true ] );
						$this->woocommerce_wpml->translation_editor->create_product_translation_package( $post_id, $trid, $translation->language_code, ICL_TM_COMPLETE );
						$variations_processed           += $terms_count * 2;
						$response['languages_processed'] = $i;
						$i++;
						if ( $variations_processed >= $VARIATIONS_THRESHOLD ) {
							break;
						}
					} else {
						$i++;
					}
				}
				$response['last_post_id'] = $post_id;
				if ( --$i == count( $translations ) ) {
					$response['languages_processed'] = 0;
					$languages_processed             = 0;
				} else {
					break;
				}

				$posts_processed ++;

			}

			$response['go'] = 1;

		} else {

			$response['go'] = 0;

		}


		$response['progress'] = $response['go'] ? sprintf(
				/* translators: %d is a number of products */
				__( '%d products left', 'woocommerce-multilingual' ),
				count( $post_ids ) - $posts_processed
			)
			: __( 'Synchronization complete!', 'woocommerce-multilingual' );

		if ( $response['go'] && isset( $wcml_settings['variations_needed'][ $taxonomy ] ) && ! empty( $variations_processed ) ) {
			$wcml_settings['variations_needed'][ $taxonomy ] = max( $wcml_settings['variations_needed'][ $taxonomy ] - $variations_processed, 0 );
		} else {
			if ( $response['go'] == 0 ) {
				$wcml_settings['variations_needed'][ $taxonomy ] = 0;
			}
		}
		$wcml_settings['sync_variations'] = 0;

		$this->woocommerce_wpml->update_settings( $wcml_settings );

		echo json_encode( $response );
		exit;
	}


/** Function activate_plugin() called by wp_ajax hooks: {'installer_activate_plugin'} **/
/** Parameters found in function activate_plugin(): {"post": ["plugin_id", "nonce"]} **/
function activate_plugin() {
		$error = '';

		$plugin_id = sanitize_text_field( $_POST['plugin_id'] );

		if ( isset( $_POST['nonce'] ) && $plugin_id && wp_verify_nonce( $_POST['nonce'], 'activate_' . $plugin_id ) ) {
			$plugin_slug    = dirname( $plugin_id );
			$active_plugins = get_option( 'active_plugins' );
			foreach ( $active_plugins as $plugin ) {
				$wp_plugin_slug = dirname( $plugin );
				if ( $wp_plugin_slug == $plugin_slug . '-embedded' ) {
					deactivate_plugins( array( $plugin ) );
					break;
				}
			}

			add_filter( 'wp_redirect', '__return_false', 10000 );

			$return = activate_plugin( $plugin_id );

			if ( is_wp_error( $return ) ) {
				$error = $return->get_error_message();
			}
		} else {
			$error = 'error';
		}

		$ret = array( 'error' => $error );

		echo json_encode( $ret );
		exit;
	}


/** Function remove_notifications() called by wp_ajax hooks: {'icl_remove_notifications'} **/
/** No params detected :-/ **/


/** Function fix_translated_variations_relationships() called by wp_ajax hooks: {'fix_translated_variations_relationships'} **/
/** No params detected :-/ **/


/** Function order_delete_items() called by wp_ajax hooks: {'wcml_order_delete_items'} **/
/** No params detected :-/ **/


/** Function restore_notifications() called by wp_ajax hooks: {'icl_restore_notifications'} **/
/** Parameters found in function restore_notifications(): {"post": ["all_users"]} **/
function restore_notifications() {
			$all_users = $_POST['all_users'];

			$messages = self::get_messages();
			$dirty    = 0;
			foreach ( $messages as $group => $message_group ) {
				foreach ( $message_group as $id => $msg ) {
					if ( $msg['hidden'] ) {
						$msg['hidden'] = false;
						$dirty ++;
					}
					if ( $msg['dismissed'] ) {
						$msg['dismissed'] = false;
						$dirty ++;
					}

					$current_user_id = get_current_user_id();
					foreach ( $msg['users'] as $user_id => $message_user_data ) {
						if ( $current_user_id == $user_id || $all_users ) {
							if ( $message_user_data['hidden'] ) {
								$message_user_data['hidden'] = false;
								$dirty ++;
							}
							if ( $message_user_data['dismissed'] ) {
								$message_user_data['dismissed'] = false;
								$dirty ++;
							}
						}
						$msg['users'][ $user_id ] = $message_user_data;
					}

					$message_group[ $id ] = $msg;
				}
				$messages[ $group ] = $message_group;
			}

			if ( $dirty ) {
				self::save_messages( $messages );
			}

			echo wp_json_encode(
				array(
					'errors'  => 0,
					'message' => __( 'Done', 'sitepress' ),
					'cont'    => $dirty,
					'reload'  => 1,
				)
			);
			die();
		}


/** Function wcml_currencies_switcher_save_settings() called by wp_ajax hooks: {'wcml_currencies_switcher_save_settings'} **/
/** Parameters found in function wcml_currencies_switcher_save_settings(): {"post": ["wcml_nonce", "template", "switcher_id", "widget_id", "widget_title", "switcher_style", "color_scheme"]} **/
function wcml_currencies_switcher_save_settings() {
		$nonce = array_key_exists( 'wcml_nonce', $_POST ) ? sanitize_text_field( $_POST['wcml_nonce'] ) : false;
		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'wcml_currencies_switcher_save_settings' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}
		$wcml_settings     = $this->woocommerce_wpml->settings;
		$switcher_settings = [];

		$currency_switcher_format = strip_tags( stripslashes_deep( $_POST['template'] ), '<img><span><u><strong><em>' );
		$currency_switcher_format = htmlentities( $currency_switcher_format );
		$currency_switcher_format = sanitize_text_field( $currency_switcher_format );
		$currency_switcher_format = html_entity_decode( $currency_switcher_format );

		$switcher_id = sanitize_text_field( $_POST['switcher_id'] );
		if ( $switcher_id == 'new_widget' ) {
			$switcher_id = sanitize_text_field( $_POST['widget_id'] );

		}

		$switcher_settings['widget_title']   = isset( $_POST['widget_title'] ) ? sanitize_text_field( $_POST['widget_title'] ) : '';
		$switcher_settings['switcher_style'] = sanitize_text_field( $_POST['switcher_style'] );
		$switcher_settings['template']       = $currency_switcher_format;

		do_action( 'wpml_register_single_string', 'woocommerce-multilingual', $switcher_id . '_switcher_format', $currency_switcher_format );

		foreach ( $_POST['color_scheme'] as $color_id => $color ) {
			$switcher_settings['color_scheme'][ sanitize_text_field( $color_id ) ] = sanitize_hex_color( $color );
		}

		$wcml_settings['currency_switchers'][ $switcher_id ] = $switcher_settings;

		if ( $switcher_id !== 'product' ) {
			$widget_settings = get_option( 'widget_currency_sel_widget' );
			$setting_match   = false;
			foreach ( $widget_settings as $key => $widget_setting ) {
				if ( isset( $widget_setting['id'] ) && $switcher_id == $widget_setting['id'] ) {
					$setting_match                       = true;
					$widget_settings[ $key ]['settings'] = $switcher_settings;
				}
			}

			if ( ! $setting_match ) {
				$widget_settings[] = [
					'id'       => $switcher_id,
					'settings' => $switcher_settings,
				];
			}

			update_option( 'widget_currency_sel_widget', $widget_settings );

			$this->synchronize_widget_instances( $widget_settings );
		}

		$this->woocommerce_wpml->update_settings( $wcml_settings );

		wp_send_json_success();
	}


/** Function hide_wcml_translations_message() called by wp_ajax hooks: {'hide_wcml_translations_message'} **/
/** No params detected :-/ **/


/** Function trbl_fix_product_type_terms() called by wp_ajax hooks: {'trbl_fix_product_type_terms'} **/
/** No params detected :-/ **/


/** Function wcml_cart_clear_removed_items() called by wp_ajax hooks: {'wcml_cart_clear_removed_items', 'nopriv_wcml_cart_clear_removed_items'} **/
/** No params detected :-/ **/


/** Function update_woocommerce_shipping_settings_for_class_costs_from_ajax() called by wp_ajax hooks: {'woocommerce_shipping_zone_methods_save_settings'} **/
/** Parameters found in function update_woocommerce_shipping_settings_for_class_costs_from_ajax(): {"post": ["wc_shipping_zones_nonce", "data"]} **/
function update_woocommerce_shipping_settings_for_class_costs_from_ajax() {
		if ( ! isset( $_POST['wc_shipping_zones_nonce'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( wp_unslash( $_POST['wc_shipping_zones_nonce'] ), 'wc_shipping_zones_nonce' ) ) {
			return;
		}
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		if ( isset( $_POST['data']['woocommerce_flat_rate_type'] ) && $_POST['data']['woocommerce_flat_rate_type'] == 'class' ) {

			$flat_rate_setting_id = 'woocommerce_flat_rate_' . $_POST['data']['instance_id'] . '_settings';
			$settings             = get_option( $flat_rate_setting_id, true );

			$settings = $this->sync_flat_rate_class_cost( $_POST['data'], $settings );

			update_option( $flat_rate_setting_id, $settings );
		}
	}


/** Function update_taxonomy_in_variations() called by wp_ajax hooks: {'wpml_tt_save_term_translation'} **/
/** No params detected :-/ **/


/** Function switch_to_current() called by wp_ajax hooks: {'nopriv_woocommerce_checkout', 'woocommerce_checkout'} **/
/** No params detected :-/ **/


/** Function legacy_remove_custom_rates() called by wp_ajax hooks: {'legacy_remove_custom_rates'} **/
/** Parameters found in function legacy_remove_custom_rates(): {"post": ["post_id"]} **/
function legacy_remove_custom_rates() {

		$nonce = filter_input( INPUT_POST, 'wcml_nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'legacy_remove_custom_rates' ) ) {
			echo json_encode( [ 'error' => __( 'Invalid nonce', 'woocommerce-multilingual' ) ] );
			die();
		}

		delete_post_meta( $_POST['post_id'], '_custom_conversion_rate' );
		echo json_encode( [] );

		exit;
	}


/** Function set_max_mind_key() called by wp_ajax hooks: {'wcml_set_max_mind_key'} **/
/** No params detected :-/ **/


/** Function sync_deleted_meta() called by wp_ajax hooks: {'sync_deleted_meta'} **/
/** No params detected :-/ **/


/** Function trbl_sync_categories() called by wp_ajax hooks: {'trbl_sync_categories'} **/
/** No params detected :-/ **/


/** Function wcml_currencies_switcher_preview() called by wp_ajax hooks: {'wcml_currencies_switcher_preview'} **/
/** Parameters found in function wcml_currencies_switcher_preview(): {"post": ["wcml_nonce", "switcher_id", "switcher_style", "color_scheme", "template"]} **/
function wcml_currencies_switcher_preview() {
		$nonce = array_key_exists( 'wcml_nonce', $_POST ) ? sanitize_text_field( $_POST['wcml_nonce'] ) : false;
		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'wcml_currencies_switcher_preview' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}
		$return = [];

		$inline_css = $this->woocommerce_wpml->cs_templates->get_color_picket_css(
			$_POST['switcher_id'],
			[
				'switcher_style' => $_POST['switcher_style'],
				'color_scheme'   => $_POST['color_scheme'],
			]
		);

		$template = $this->woocommerce_wpml->cs_templates->get_template( $_POST['switcher_style'] );

		if ( $template->has_styles() ) {
			$return['inline_styles_id'] = $template->get_inline_style_handler() . '-inline-css';
		} else {
			$return['inline_styles_id'] = 'wcml-cs-inline-styles-' . $_POST['switcher_id'] . '-' . $_POST['switcher_style'];
		}

		$return['inline_css'] = $inline_css;

		ob_start();
		$this->woocommerce_wpml->multi_currency->currency_switcher->do_currency_switcher(
			[
				'switcher_id'    => $_POST['switcher_id'],
				'format'         => isset( $_POST['template'] ) ? stripslashes_deep( $_POST['template'] ) : '%name% (%symbol%) - %code%',
				'switcher_style' => $_POST['switcher_style'],
				'color_scheme'   => $_POST['color_scheme'],
				'preview'        => true,
			]
		);
		$switcher_preview = ob_get_contents();
		ob_end_clean();

		$return['preview'] = $switcher_preview;

		wp_send_json_success( $return );
	}


/** Function toggleDismiss() called by wp_ajax hooks: {'wpml_dismiss_notice'} **/
/** No params detected :-/ **/


/** Function set_booking_currency_ajax() called by wp_ajax hooks: {'wcml_booking_set_currency'} **/
/** No params detected :-/ **/


/** Function save_currency() called by wp_ajax hooks: {'wcml_save_currency'} **/
/** No params detected :-/ **/


/** Function set_reports_currency() called by wp_ajax hooks: {'wcml_reports_set_currency'} **/
/** No params detected :-/ **/


/** Function recommendationSuccess() called by wp_ajax hooks: {'installer_recommendation_success'} **/
/** Parameters found in function recommendationSuccess(): {"post": ["nonce", "pluginData"]} **/
function recommendationSuccess() {
		if ( array_key_exists( 'nonce', $_POST )
		     && array_key_exists( 'pluginData', $_POST )
		     && wp_verify_nonce( $_POST['nonce'], 'recommendation_success_nonce' ) ) {
			$data = json_decode( base64_decode( sanitize_text_field( $_POST['pluginData'] ) ), true );
			$this->noticesStorage->delete( $data['slug'], $data['repository_id'] );
		}

	}


/** Function update_settings_from_warning() called by wp_ajax hooks: {'wcml_ignore_warning'} **/
/** Parameters found in function update_settings_from_warning(): {"post": ["setting"]} **/
function update_settings_from_warning() {
		$nonce = filter_input( INPUT_POST, 'wcml_nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'wcml_ignore_warning' ) ) {
			die( 'Invalid nonce' );
		}
		global $woocommerce_wpml;

		$woocommerce_wpml->settings[ $_POST['setting'] ] = 1;
		$woocommerce_wpml->update_settings();

	}


/** Function download_plugin_ajax_handler() called by wp_ajax hooks: {'installer_download_plugin'} **/
/** Parameters found in function download_plugin_ajax_handler(): {"post": ["data"]} **/
function download_plugin_ajax_handler() {
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once $this->plugin_path() . '/includes/class-installer-upgrader-skins.php';

		$data = json_decode( base64_decode( sanitize_text_field( $_POST['data'] ) ), true );

		$data_url_parsed = wp_parse_url( $data['url'] );
		$data_url_args   = $data_url_parsed['query'];
		unset( $data_url_parsed['query'] );
		$data_url = http_build_url( $data_url_parsed );

		$ret              = false;
		$plugin_id        = false;
		$message          = '';
		$connection_error = false;

		$site_key = $this->get_repository_site_key( $data['repository_id'] );
		try {
			$subscriptionManagerFactory = new SubscriptionManagerFactory( $this->get_settings() );
			$subscriptionManager        = $subscriptionManagerFactory->create( $data['repository_id'], $this->repositories[ $data['repository_id'] ]['api-url'] );
			list ( $subscription_data, $site_key_data ) = $subscriptionManager->fetch( $site_key, self::SITE_KEY_VALIDATION_SOURCE_DOWNLOAD_REPORT );
		} catch ( Exception $e ) {
			$connection_error  = $e->getMessage();
			$subscription_data = false;

			$this->store_log( $data_url, $data_url_args, OTGS_Installer_Logger_Storage::COMPONENT_SUBSCRIPTION, $connection_error );
		}

		if ( $subscription_data && ! is_wp_error( $subscription_data ) && $this->repository_has_valid_subscription( $data['repository_id'] ) ) {
			if ( wp_verify_nonce( $data['nonce'], 'install_plugin_' . $data['url'] ) ) {
				$upgrader_skins = new Installer_Upgrader_Skins();
				$upgrader       = new Plugin_Upgrader( $upgrader_skins );

				remove_action( 'upgrader_process_complete', array( 'Language_Pack_Upgrader', 'async_upgrade' ), 20 );

				$plugins = get_plugins();

				$is_embedded = false;
				foreach ( $plugins as $id => $plugin ) {
					$wp_plugin_slug = dirname( $id );
					$is_embedded    = $this->plugin_is_embedded_version( preg_replace( '/ Embedded$/', '', $plugin['Name'] ), preg_replace( '/-embedded$/', '', $wp_plugin_slug ) );

					if ( $wp_plugin_slug == $data['slug'] || $is_embedded && preg_replace( '/-embedded$/', '', $wp_plugin_slug ) == $data['slug'] ) {
						$plugin_id = $id;
						break;
					}
				}

				if ( $plugin_id && ! $is_embedded && $this->is_plugin_out_of_date( $plugin_id ) ) {
					$response['upgrade'] = 1;

					$plugin_is_active = is_plugin_active( $plugin_id );

					$ret = $upgrader->upgrade( $plugin_id, [ 'clear_update_cache' => false ] );

					if ( ! $ret && ! empty( $upgrader->skin->installer_error ) ) {
						if ( is_wp_error( $upgrader->skin->installer_error ) ) {
							$message = $upgrader->skin->installer_error->get_error_message() .
							           ' (' . $upgrader->skin->installer_error->get_error_data() . ')';
						}
						$plugin_version = 0;
					} else {
						if ( $plugin_is_active ) {
							add_filter( 'wp_redirect', '__return_false' );
							activate_plugin( $plugin_id );
						}
						$plugin_version = $this->get_plugin_repository_version( $data['repository_id'], $data['slug'] );
					}
				} elseif ( $plugin_id && ! $is_embedded ) {
					activate_plugin( $plugin_id );
					$ret = true;
				} else {

					if ( $is_embedded && $plugin_id ) {
						delete_plugins( array( $plugin_id ) );
					}

					$response['install'] = 1;
					$ret                 = $upgrader->install( $data['url'] );
					if ( ! $ret && ! empty( $upgrader->skin->installer_error ) ) {
						if ( is_wp_error( $upgrader->skin->installer_error ) ) {
							$message = $upgrader->skin->installer_error->get_error_message() .
							           ' (' . $upgrader->skin->installer_error->get_error_data() . ')';
						}
					}
				}

				$plugins = get_plugins();

				if ( $ret ) {
					foreach ( $plugins as $id => $plugin ) {
						$wp_plugin_slug = dirname( $id );
						if ( $wp_plugin_slug == $data['slug'] ) {
							$plugin_version = $plugin['Version'];
							$plugin_id      = $id;

							$include_auto_upgrade = new IncludeAutoUpgrade( $this->settings, $data['repository_id'] );
							$include_auto_upgrade->includeDuringInstall( $plugin_id );
							break;
						}
					}
				}

				if ( WP_Installer_Channels()->get_channel( $data['repository_id'] ) !== WP_Installer_Channels::CHANNEL_PRODUCTION ) {
					$download   = $this->settings()['repositories'][ $data['repository_id'] ]['data']['downloads']['plugins'][ $data['slug'] ];
					$non_stable = WP_Installer_Channels()->get_download_source_channel( $plugin_version, $data['repository_id'], $download['slug'], 'plugins' );
				}
			}
		} elseif ( $connection_error ) {
			$ret     = false;
			$message = sprintf( __( 'Connection failed! Please refresh the page and try again. (%s)', 'installer' ), '<i>' . $connection_error . '</i>' );
		} else {
			$ret     = false;
			$message = __( 'Your subscription appears to no longer be valid. Please try to register again using a valid site key.', 'installer' );
		}

		if ( $message ) {
			$this->store_log( $data_url, $data_url_args, OTGS_Installer_Logger_Storage::COMPONENT_DOWNLOAD, $message );
		}

		$response['version']    = isset( $plugin_version ) ? $plugin_version : 0;
		$response['non_stable'] = isset( $non_stable ) ? $non_stable : '';
		$response['plugin_id']  = $plugin_id;
		$response['nonce']      = wp_create_nonce( 'activate_' . $plugin_id );
		$response['success']    = $ret;
		$response['message']    = $message;

		echo json_encode( $response );
		exit;
	}


/** Function set_channel() called by wp_ajax hooks: {'installer_set_channel'} **/
/** Parameters found in function set_channel(): {"post": ["repository_id", "channel", "nonce", "noprompt"]} **/
function set_channel(){
		$repository_id  = sanitize_text_field( $_POST['repository_id'] );
		$channel = sanitize_text_field( $_POST['channel'] );
		$installer = WP_Installer::instance();
		$settings = $installer->get_settings();

		$response = array();
		if ( wp_verify_nonce( $_POST['nonce'], 'installer_set_channel:' . $repository_id ) ) {
			if( isset( $settings['repositories'][$repository_id] ) ){
				$settings['repositories'][$repository_id]['channel'] = $channel;
				$settings['repositories'][$repository_id]['no-prompt'] = $_POST['noprompt'] === 'true';
				$installer->save_settings( $settings );
			}

			$installer->refresh_repositories_data();

			$response['status'] = 'OK';
		}

		echo json_encode( $response );
		exit;
	}


/** Function remove() called by wp_ajax hooks: {'remove_site_key'} **/
/** No params detected :-/ **/


/** Function set_dashboard_currency_ajax() called by wp_ajax hooks: {'wcml_dashboard_set_currency'} **/
/** Parameters found in function set_dashboard_currency_ajax(): {"post": ["wcml_nonce", "currency"]} **/
function set_dashboard_currency_ajax() {
		$nonce = sanitize_text_field( $_POST['wcml_nonce'] );

		if ( ! $nonce || ! wp_verify_nonce( $nonce, self::NONCE_KEY ) ) {
			wp_send_json_error( __( 'Invalid nonce', 'woocommerce-multilingual' ), 403 );
		} else {
			$this->set_dashboard_currency( sanitize_text_field( $_POST['currency'] ) );
			wp_send_json_success();
		}
	}


/** Function email_refresh_in_ajax() called by wp_ajax hooks: {'woocommerce_mark_order_status'} **/
/** Parameters found in function email_refresh_in_ajax(): {"get": ["order_id", "status"]} **/
function email_refresh_in_ajax() {
		if ( ! current_user_can( 'edit_shop_orders' ) ) {
			return;
		}
		if ( ! check_admin_referer( 'woocommerce-mark-order-status' ) ) {
			return;
		}
		if ( isset( $_GET['order_id'] ) ) {
			$this->refresh_email_lang( (int) $_GET['order_id'] );

			if ( isset( $_GET['status'] ) && 'completed' === $_GET['status'] ) {
				$this->email_heading_completed( (int) $_GET['order_id'], true );
			}

			return true;
		}
	}


/** Function update_default_currency_ajax() called by wp_ajax hooks: {'wcml_update_default_currency'} **/
/** No params detected :-/ **/


/** Function legacy_update_custom_rates() called by wp_ajax hooks: {'legacy_update_custom_rates'} **/
/** Parameters found in function legacy_update_custom_rates(): {"post": ["posts"]} **/
function legacy_update_custom_rates() {

		$nonce = filter_input( INPUT_POST, 'wcml_nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'legacy_update_custom_rates' ) ) {
			die( 'Invalid nonce' );
		}
		foreach ( $_POST['posts'] as $post_id => $rates ) {
			update_post_meta( $post_id, '_custom_conversion_rate', $rates );
		}

		echo json_encode( [] );
		exit;
	}


/** Function find() called by wp_ajax hooks: {'find_account'} **/
/** No params detected :-/ **/


/** Function trbl_gallery_images() called by wp_ajax hooks: {'trbl_gallery_images'} **/
/** No params detected :-/ **/


/** Function trbl_duplicate_terms() called by wp_ajax hooks: {'trbl_duplicate_terms'} **/
/** Parameters found in function trbl_duplicate_terms(): {"post": ["attr"]} **/
function trbl_duplicate_terms() {
		self::checkNonce( 'trbl_duplicate_terms' );

		$attr  = $_POST['attr'] ?? false;
		$terms = [];

		if ( $attr ) {
			$terms     = get_terms( $attr, 'hide_empty=0' );
			$languages = $this->sitepress->get_active_languages();
			foreach ( $terms as $term ) {
				foreach ( $languages as $language ) {
					$tr_id = apply_filters( 'wpml_object_id', $term->term_id, $attr, false, $language['code'] );

					if ( is_null( $tr_id ) ) {
						$term_args = [];
						if ( is_taxonomy_hierarchical( $attr ) ) {
							if ( $term->parent ) {
								$original_parent_translated = apply_filters( 'wpml_object_id', $term->parent, $attr, false, $language['code'] );
								if ( $original_parent_translated ) {
									$term_args['parent'] = $original_parent_translated;
								}
							}
						}

						$term_name         = $term->name;
						$slug              = $term->name . '-' . $language['code'];
						$slug              = WPML_Terms_Translations::term_unique_slug( $slug, $attr, $language['code'] );
						$term_args['slug'] = $slug;

						$new_term = wp_insert_term( $term_name, $attr, $term_args );
						if ( is_wp_error( $new_term ) ) {
							$tt_id = $this->sitepress->get_element_trid( $term->term_taxonomy_id, 'tax_' . $attr );
							$this->sitepress->set_element_language_details( $new_term['term_taxonomy_id'], 'tax_' . $attr, $tt_id, $language['code'] );
						}
					}
				}
			}
		}

		$response = [
			'processed' => count( $terms ),
			'complete'  => true,
		];
		wp_send_json_success( $response );
	}


/** Function remove_variation_ajax() called by wp_ajax hooks: {'woocommerce_remove_variation'} **/
/** Parameters found in function remove_variation_ajax(): {"post": ["variation_id"]} **/
function remove_variation_ajax() {
		check_ajax_referer( 'delete-variation', 'security' );

		if ( ! current_user_can( 'edit_products' ) ) {
			die( -1 );
		}

		if ( isset( $_POST['variation_id'] ) ) {
			$trid = $this->sitepress->get_element_trid( (int) filter_input( INPUT_POST, 'variation_id', FILTER_SANITIZE_NUMBER_INT ), 'post_product_variation' );
			if ( $trid ) {
				$translations = $this->sitepress->get_element_translations( $trid, 'post_product_variation' );
				if ( $translations ) {
					foreach ( $translations as $translation ) {
						if ( ! $translation->original ) {
							wp_delete_post( $translation->element_id, true );
						}
					}
				}
			}
		}
	}


/** Function unregister_abort_messages_ajax() called by wp_ajax hooks: {'woocommerce_table_rate_delete'} **/
/** No params detected :-/ **/


