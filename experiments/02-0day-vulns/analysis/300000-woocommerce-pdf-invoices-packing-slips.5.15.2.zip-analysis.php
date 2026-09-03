<?php
/***
*
*Found actions: 23
*Found functions:19
*Extracted functions:19
*Total parameter names extracted: 8
*Overview: {'ajax_reload_tax_table': {'wpo_ips_edi_reload_tax_table'}, 'sync_shop_address_with_woo': {'wpo_wcpdf_sync_address'}, 'preview_order_search': {'wpo_wcpdf_preview_order_search'}, 'ajax_preview': {'wpo_wcpdf_preview'}, 'ajax_numbers_data': {'wpo_wcpdf_numbers_data'}, 'get_refund_order_ids_ajax': {'nopriv_wpo_ips_get_refund_order_ids', 'wpo_ips_get_refund_order_ids'}, 'ajax_crud_document': {'wpo_wcpdf_save_document', 'wpo_wcpdf_regenerate_document', 'wpo_wcpdf_delete_document'}, 'ajax_load_customer_order_identifiers': {'wpo_ips_edi_load_customer_order_identifiers'}, 'ajax_plugin_report': {'wpo_ips_plugin_report'}, 'get_media_upload_setting_html': {'wpo_wcpdf_get_media_upload_setting_html'}, 'generate_document_ajax': {'nopriv_generate_wpo_wcpdf', 'generate_wpo_wcpdf'}, 'ajax_process_danger_zone_tools': {'wpo_wcpdf_danger_zone_tools'}, 'set_number_store': {'wpo_wcpdf_set_next_number'}, 'document_printed_ajax': {'printed_wpo_wcpdf'}, 'ajax_get_shop_country_states': {'wcpdf_get_country_states'}, 'ajax_save_taxes': {'wpo_ips_edi_save_taxes'}, 'ajax_preview_formatted_number': {'wpo_wcpdf_preview_formatted_number'}, 'ajax_edi_save_order_customer_peppol_identifiers': {'wpo_ips_edi_save_order_customer_peppol_identifiers'}, 'ajax_process_settings_debug_tools': {'wpo_wcpdf_debug_tools'}}
*
***/

/** Function ajax_reload_tax_table() called by wp_ajax hooks: {'wpo_ips_edi_reload_tax_table'} **/
/** No params detected :-/ **/


/** Function sync_shop_address_with_woo() called by wp_ajax hooks: {'wpo_wcpdf_sync_address'} **/
/** Parameters found in function sync_shop_address_with_woo(): {"post": ["address_field"]} **/
function sync_shop_address_with_woo(): void {
		check_ajax_referer( 'wpo_wcpdf_admin_nonce', 'security' );

		$address_field = ! empty( $_POST['address_field'] )
			? sanitize_text_field( wp_unslash( $_POST['address_field'] ) )
			: '';

		if ( empty( $address_field ) ) {
			wp_send_json_error( array( 'message' => __( 'Address field is required.', 'woocommerce-pdf-invoices-packing-slips' ) ) );
			return;
		}

		// Map address fields to the option keys used by WooCommerce.
		$address_map = array(
			'shop_address_line_1'   => 'woocommerce_store_address',
			'shop_address_line_2'   => 'woocommerce_store_address_2',
			'shop_address_country'  => 'woocommerce_default_country',
			'shop_address_state'    => 'woocommerce_default_country',
			'shop_address_city'     => 'woocommerce_store_city',
			'shop_address_postcode' => 'woocommerce_store_postcode',
		);

		// Validate the address field against the map.
		if ( ! array_key_exists( $address_field, $address_map ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid address field.', 'woocommerce-pdf-invoices-packing-slips' ) ) );
			return;
		}

		$option_key = $address_map[ $address_field ];
		$raw_value  = get_option( $option_key, '' );

		// Return, if the value is not set.
		if ( empty( $raw_value ) ) {
			wp_send_json_error( array( 'message' => __( 'The field is empty.', 'woocommerce-pdf-invoices-packing-slips' ) ) );
			return;
		}

		// Parse the value based on the address field.
		switch ( $address_field ) {
			case 'shop_address_state':
				$parsed = wc_format_country_state_string( $raw_value );
				$value  = $parsed['state'] ?? null;
				break;
			default:
				$value = $raw_value;
		}

		wp_send_json_success( array( 'value' => $value ) );
	}


/** Function preview_order_search() called by wp_ajax hooks: {'wpo_wcpdf_preview_order_search'} **/
/** Parameters found in function preview_order_search(): {"post": ["search", "document_type"]} **/
function preview_order_search() {
		check_ajax_referer( 'wpo_wcpdf_preview', 'security' );

		try {
			// check permissions
			if ( ! $this->user_can_manage_settings() ) {
				throw new \Exception( esc_html__( 'You do not have sufficient permissions to access this page.', 'woocommerce-pdf-invoices-packing-slips' ), 403 );
			}

			if ( ! empty( $_POST['search'] ) && ! empty( $_POST['document_type'] ) ) {
				$search        = sanitize_text_field( wp_unslash( $_POST['search'] ) );
				$document_type = sanitize_text_field( wp_unslash( $_POST['document_type'] ) );
				$results       = array();

				// we have an order ID
				if ( is_numeric( $search ) && wc_get_order( $search ) ) {
					$results = [ $search ];

				// no order ID, let's try with customer
				} else {
					$default_args = apply_filters( 'wpo_wcpdf_preview_order_search_args', array(
						'type'     => 'shop_order',
						'limit'    => 10,
						'orderby'  => 'date',
						'order'    => 'DESC',
						'return'   => 'ids',
					), $document_type );

					// search by email
					if ( is_email( $search ) ) {
						$args    = array( 'customer' => $search );
						$args    = $args + $default_args;
						$results = wc_get_orders( $args );

					// search by names
					} else {
						$names = array( 'billing_first_name', 'billing_last_name', 'billing_company' );
						foreach ( $names as $name ) {
							$args    = array( $name => $search );
							$args    = $args + $default_args;
							$results = wc_get_orders( $args );
							if ( count( $results ) > 0 ) {
								break;
							}
						}
					}
				}

				// filter results
				$results = apply_filters( 'wpo_wcpdf_preview_order_search_results', $results, $search, $document_type );

				// if we got here we have results!
				if ( ! empty( $results ) ) {
					$data = array();
					foreach ( $results as $value ) {
						$order = wc_get_order( $value );
						if ( empty( $order ) ) {
							continue;
						}
						$order_id                              = is_callable( array( $order, 'get_id' ) ) ? $order->get_id() : 0;
						$data[$order_id]['order_number']       = is_callable( array( $order, 'get_order_number' ) ) ? $order->get_order_number() : '';
						$data[$order_id]['billing_first_name'] = is_callable( array( $order, 'get_billing_first_name' ) ) ? wpo_wcpdf_sanitize_html_content( $order->get_billing_first_name(), 'first_name' ) : '';
						$data[$order_id]['billing_last_name']  = is_callable( array( $order, 'get_billing_last_name' ) ) ? wpo_wcpdf_sanitize_html_content( $order->get_billing_last_name(), 'last_name' ) : '';
						$data[$order_id]['billing_company']    = is_callable( array( $order, 'get_billing_company' ) ) ? wpo_wcpdf_sanitize_html_content( $order->get_billing_company(), 'company' ) : '';
						$data[$order_id]['date_created']       = is_callable( array( $order, 'get_date_created' ) ) ? '<strong>' . esc_attr__( 'Date', 'woocommerce-pdf-invoices-packing-slips' ) . ':</strong> ' . $order->get_date_created()->format( 'Y/m/d' ) : '';
						$data[$order_id]['total']              = is_callable( array( $order, 'get_total' ) ) ? '<strong>' . esc_attr__( 'Total', 'woocommerce-pdf-invoices-packing-slips' ) . ':</strong> ' . wc_price( $order->get_total() ) : '';
					}

					$data = apply_filters( 'wpo_wcpdf_preview_order_search_data', $data, $results );

					wp_send_json_success( $data );
				} else {
					wp_send_json_error( array( 'error' => esc_html__( 'No order(s) found!', 'woocommerce-pdf-invoices-packing-slips' ) ) );
				}
			} else {
				wp_send_json_error( array( 'error' => esc_html__( 'An error occurred when trying to process your request!', 'woocommerce-pdf-invoices-packing-slips' ) ) );
			}
		} catch ( \Throwable $th ) {
			wp_send_json_error(
				array(
					'error' => sprintf(
						/* translators: error message */
						esc_html__( 'Error trying to get orders: %s', 'woocommerce-pdf-invoices-packing-slips' ),
						$th->getMessage()
					)
				)
			);
		}

		wp_die();
	}


/** Function ajax_preview() called by wp_ajax hooks: {'wpo_wcpdf_preview'} **/
/** Parameters found in function ajax_preview(): {"post": ["document_type", "order_id", "data"], "request": ["output_format"]} **/
function ajax_preview() {
		check_ajax_referer( 'wpo_wcpdf_preview', 'security' );

		try {
			// check permissions
			if ( ! $this->user_can_manage_settings() ) {
				throw new \Exception( esc_html__( 'You do not have sufficient permissions to access this page.', 'woocommerce-pdf-invoices-packing-slips' ), 403 );
			}

			// get document type
			if ( ! empty( $_POST['document_type'] ) ) {
				$document_type = sanitize_text_field( wp_unslash( $_POST['document_type'] ) );
			} else {
				$document_type = 'invoice';
			}

			// get order ID
			if ( ! empty( $_POST['order_id'] ) ) {
				$order_id = sanitize_text_field( wp_unslash( $_POST['order_id'] ) );

				if ( 'credit-note' === $document_type ) {
					// get last refund ID of the order if available
					$refund = wc_get_orders(
						array(
							'type'   => 'shop_order_refund',
							'parent' => $order_id,
							'limit'  => 1,
						)
					);
					$order_id = ! empty( $refund ) ? $refund[0]->get_id() : $order_id;
				}
			} else {
				// default to last order
				$default_order_id = wc_get_orders( apply_filters( 'wpo_wcpdf_preview_default_order_id_query_args', array(
					'limit'  => 1,
					'return' => 'ids',
					'type'   => 'shop_order',
				), $document_type ) );
				$order_id = apply_filters( 'wpo_wcpdf_preview_default_order_id', ! empty( $default_order_id ) ? reset( $default_order_id ) : false );
			}

			// get PDF data for preview
			if ( $order_id ) {
				$order = apply_filters( 'wpo_wcpdf_preview_order_object', wc_get_order( $order_id ), $order_id, $document_type );

				if ( empty( $order ) ) {
					wp_send_json_error( array( 'error' => esc_html__( 'Order not found!', 'woocommerce-pdf-invoices-packing-slips' ) ) );
				}
				if ( ! in_array( $order->get_type(), array( 'shop_order', 'shop_order_refund' ) ) ) {
					wp_send_json_error( array( 'error' => esc_html__( 'Object found is not an order!', 'woocommerce-pdf-invoices-packing-slips' ) ) );
				}

				// process settings data
				if ( ! empty( $_POST['data'] ) ) {
					// parse form data
					parse_str( $_POST['data'], $form_data ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
					$form_data = stripslashes_deep( $form_data );

					foreach ( $form_data as $option_key => $form_settings ) {
						if ( ! empty( $option_key ) && false === apply_filters( 'wpo_wcpdf_preview_filter_option', 0 === strpos( $option_key, 'wpo_wcpdf' ), $option_key ) ) {
							continue; // not our business
						}

						// validate option values
						$form_settings = $this->callbacks->validate( $form_settings );

						// filter the options
						add_filter( "option_{$option_key}", function( $_value, $_option ) use ( $form_settings ) {
							return $form_settings;
						}, 99, 2 );
					}

					// reload settings
					$this->load_settings();

					do_action( 'wpo_wcpdf_preview_after_reload_settings' );
				}

				$document = wcpdf_get_document( $document_type, $order );

				if ( $document ) {
					if ( ! $document->exists() ) {
						$document->set_date( current_time( 'timestamp', true ) );
						$number_store_method = $this->get_sequential_number_store_method();
						$number_store_name   = apply_filters( 'wpo_wcpdf_document_sequential_number_store', "{$document->slug}_number", $document );
						$number_store        = new SequentialNumberStore( $number_store_name, $number_store_method );
						$document->set_number( $number_store->get_next() );
					}

					// Update document date.
					$document->initiate_date();

					// Update document number only if it is not already set.
					if ( empty( $document->get_number() ) ) {
						$document_number = $document->get_document_number();
						if ( ! empty( $document_number ) ) {
							$document->set_number( $document_number );
						}
					}

					$document_number = $document->get_number( $document->get_type() );

					// Apply document number formatting.
					if ( $document_number ) {
						$number_data = array();

						if (
							isset( $document->settings['display_number'] ) &&
							'order_number' === $document->settings['display_number']
						) {
							$order_number = $order->get_order_number();
							$number_data  = array(
								'number'           => (int) preg_replace( '/\D/', '', $order_number ),
								'formatted_number' => "{$order_number}",
							);
						}

						if (
							! empty( $document->settings['number_format'] ) &&
							is_array( $document->settings['number_format'] )
						) {
							$number_data = array_merge(
								$number_data,
								$document->settings['number_format']
							);
						}

						if ( ! empty( $number_data ) ) {
							$document_number->load_number_data( $number_data );
						}

						$document_number->apply_formatting( $document, $order );
					}

					// preview
					$output_format = ( ! empty( $_REQUEST['output_format'] ) && $_REQUEST['output_format'] != 'pdf' && in_array( $_REQUEST['output_format'], $document->output_formats ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['output_format'] ) ) : 'pdf';
					switch ( $output_format ) {
						default:
						case 'pdf':
							$preview_data = base64_encode( $document->preview_pdf() );
							break;
						case 'xml':
							$preview_data = $document->preview_xml();
							break;
					}

					wp_send_json_success( array(
						'preview_data'  => $preview_data,
						'output_format' => $output_format,
					) );
				} else {
					wp_send_json_error(
						array(
							'error' => sprintf(
								/* translators: 1. order ID, 2. documentation page link, 3. documentation page link closing tag */
								esc_html__( 'The PDF preview for order #%1$d is not available. This can happen if some settings prevent the document from being generated. Please review your configuration or check the %2$sdocumentation%3$s for more details.', 'woocommerce-pdf-invoices-packing-slips' ),
								$order_id,
								'<a href="https://docs.wpovernight.com/woocommerce-pdf-invoices-packing-slips/troubleshooting-pdf-preview-unavailability/" target="_blank">',
								'</a>'
							)
						)
					);
				}
			} else {
				wp_send_json_error( array( 'error' => esc_html__( 'No WooCommerce orders found! Please consider adding your first order to see this preview.', 'woocommerce-pdf-invoices-packing-slips' ) ) );
			}

		} catch ( \Throwable $th ) {
			wcpdf_log_error( 'Error trying to generate document: ' . $th->getTraceAsString(), 'critical' );

			wp_send_json_error(
				array(
					'error' => sprintf(
						/* translators: error message */
						esc_html__( 'Error trying to generate document: %s', 'woocommerce-pdf-invoices-packing-slips' ),
						$th->getMessage()
					)
				)
			);
		}

		wp_die();
	}


/** Function ajax_numbers_data() called by wp_ajax hooks: {'wpo_wcpdf_numbers_data'} **/
/** No params detected :-/ **/


/** Function get_refund_order_ids_ajax() called by wp_ajax hooks: {'nopriv_wpo_ips_get_refund_order_ids', 'wpo_ips_get_refund_order_ids'} **/
/** Parameters found in function get_refund_order_ids_ajax(): {"post": ["order_ids"]} **/
function get_refund_order_ids_ajax(): void {
		// Accept requests from both contexts:
		// - bulk generate (generate_wpo_wcpdf)
		// - cloud export (pro_cloud_storage)

		$valid_nonce = check_ajax_referer( 'generate_wpo_wcpdf', 'security', false )
			|| check_ajax_referer( 'pro_cloud_storage', 'security', false );

		if ( ! $valid_nonce ) {
			wp_send_json_error(
				array(
					'message' => __( 'Invalid request. Nonce check failed.', 'woocommerce-pdf-invoices-packing-slips' ),
				)
			);
		}

		if ( empty( $_POST['order_ids'] ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'No orders were provided.', 'woocommerce-pdf-invoices-packing-slips' ),
				)
			);
		}

		$order_ids  = array_map( 'absint', (array) $_POST['order_ids'] );
		$refund_ids = \wpo_ips_get_refund_ids( $order_ids );

		if ( empty( $refund_ids ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'No refunds found for the selected orders.', 'woocommerce-pdf-invoices-packing-slips' ),
				)
			);
		}

		wp_send_json_success(
			array(
				'refund_ids' => $refund_ids,
			)
		);
	}


/** Function ajax_crud_document() called by wp_ajax hooks: {'wpo_wcpdf_save_document', 'wpo_wcpdf_regenerate_document', 'wpo_wcpdf_delete_document'} **/
/** No params detected :-/ **/


/** Function ajax_load_customer_order_identifiers() called by wp_ajax hooks: {'wpo_ips_edi_load_customer_order_identifiers'} **/
/** No params detected :-/ **/


/** Function ajax_plugin_report() called by wp_ajax hooks: {'wpo_ips_plugin_report'} **/
/** Parameters found in function ajax_plugin_report(): {"get": ["include_sensitive", "output_html"]} **/
function ajax_plugin_report(): void {
		if ( ! \WPO_WCPDF()->settings->user_can_manage_settings() ) {
			wp_die( esc_html__( 'You are not allowed to perform this action.', 'woocommerce-pdf-invoices-packing-slips' ) );
		}

		check_ajax_referer( 'wpo_ips_plugin_report', 'nonce' );

		$include_sensitive    = isset( $_GET['include_sensitive'] )
			? filter_var( wp_unslash( $_GET['include_sensitive'] ), FILTER_VALIDATE_BOOLEAN )
			: false;
		$output_html          = isset( $_GET['output_html'] )
			? filter_var( wp_unslash( $_GET['output_html'] ), FILTER_VALIDATE_BOOLEAN )
			: false;

		$report_title         = 'PDF Invoices & Packing Slips for WooCommerce - Report';
		$premium_plugins      = $this->get_premium_plugins();
		$free_extensions      = $this->get_free_extensions();
		$multilingual_plugins = $this->get_multilingual_plugins();
		$plugin_version       = \WPO_WCPDF()->version;
		$store_url            = get_site_url();
		$report_date          = gmdate( 'Y-m-d H:i:s' );
		$report_user          = $include_sensitive ? wp_get_current_user() : null;
		$server_configs       = $this->get_server_config();
		$dir_permissions      = $include_sensitive ? $this->get_directory_permissions() : array();
		$yearly_reset         = $this->get_yearly_reset_schedule();

		// Load CSS file contents
		$report_css = '';
		$suffix     = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$css_path   = \WPO_WCPDF()->plugin_path() . '/assets/css/plugin-report' . $suffix . '.css';

		if ( \WPO_WCPDF()->file_system->exists( $css_path ) && \WPO_WCPDF()->file_system->is_readable( $css_path ) ) {
			$report_css = \WPO_WCPDF()->file_system->get_contents( $css_path );
		}

		// Load Settings
		$general_settings   = get_option( 'wpo_wcpdf_settings_general', array() );
		$debug_settings     = get_option( 'wpo_wcpdf_settings_debug', array() );
		$edi_settings       = get_option( 'wpo_ips_edi_settings', array() );
		$documents_settings = array();

		$all_documents = \WPO_WCPDF()->documents->get_documents( 'all' );
		if ( ! empty( $all_documents ) ) {
			foreach ( $all_documents as $document ) {
				$document_type                        = $document->get_type();
				$documents_settings[ $document_type ] = \WPO_WCPDF()->settings->get_document_settings( $document_type, 'pdf' );
			}
		}

		// Logs
		$logs_data = $include_sensitive ? $this->get_recent_logs() : array();

		// Extensions Settings
		$extensions_settings = apply_filters( 'wpo_ips_get_extensions_settings_for_report', array(), $include_sensitive );

		ob_start();
		include \WPO_WCPDF()->plugin_path() . '/views/plugin-report.php';
		$report_html = ob_get_clean();

		if ( $output_html ) {
			echo $report_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			die();
		}

		$pdf_settings = array(
			'paper_size'		=> 'A4',
			'paper_orientation'	=> 'portrait',
			'font_subsetting'	=> false,
		);
		$pdf_maker = \wcpdf_get_pdf_maker( $report_html, $pdf_settings, $this );
		$pdf       = $pdf_maker->output();

		$filename  = 'plugin-report-' . gmdate( 'Y-m-d' ) . '.pdf';

		\wcpdf_pdf_headers( $filename, 'download', $pdf );
		echo $pdf; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		exit();
	}


/** Function get_media_upload_setting_html() called by wp_ajax hooks: {'wpo_wcpdf_get_media_upload_setting_html'} **/
/** No params detected :-/ **/


/** Function generate_document_ajax() called by wp_ajax hooks: {'nopriv_generate_wpo_wcpdf', 'generate_wpo_wcpdf'} **/
/** No params detected :-/ **/


/** Function ajax_process_danger_zone_tools() called by wp_ajax hooks: {'wpo_wcpdf_danger_zone_tools'} **/
/** No params detected :-/ **/


/** Function set_number_store() called by wp_ajax hooks: {'wpo_wcpdf_set_next_number'} **/
/** Parameters found in function set_number_store(): {"post": ["store", "number"]} **/
function set_number_store() {
		$store = ! empty( $_POST['store'] ) ? sanitize_text_field( wp_unslash( $_POST['store'] ) ) : '';

		check_ajax_referer( "wpo_wcpdf_next_{$store}", 'security' );

		// check permissions
		if ( ! $this->user_can_manage_settings() ) {
			die();
		}

		$number = ! empty( $_POST['number'] ) ? (int) $_POST['number'] : 0;
		if ( $number > 0 ) {
			$number_store_method = $this->get_sequential_number_store_method();
			$number_store = new SequentialNumberStore( $store, $number_store_method );
			$number_store->set_next( $number );
			echo wp_kses_post( "next number ({$store}) set to {$number}" );
		}
		die();
	}


/** Function document_printed_ajax() called by wp_ajax hooks: {'printed_wpo_wcpdf'} **/
/** No params detected :-/ **/


/** Function ajax_get_shop_country_states() called by wp_ajax hooks: {'wcpdf_get_country_states'} **/
/** No params detected :-/ **/


/** Function ajax_save_taxes() called by wp_ajax hooks: {'wpo_ips_edi_save_taxes'} **/
/** Parameters found in function ajax_save_taxes(): {"post": ["nonce", "action"]} **/
function ajax_save_taxes(): void {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if (
			! isset( $_POST['action'] ) ||
			'wpo_ips_edi_save_taxes' !== $_POST['action'] ||
			! wp_verify_nonce( $nonce, 'edi_save_taxes' )
		) {
			wp_send_json_error( __( 'Invalid request.', 'woocommerce-pdf-invoices-packing-slips' ) );
		}

		$request      = stripslashes_deep( $_POST );
		$tax_settings = isset( $request['wpo_ips_edi_tax_settings'] ) ? $request['wpo_ips_edi_tax_settings'] : array();

		$this->save_tax_settings( $tax_settings );

		wp_send_json_success( __( 'Tax settings saved successfully.', 'woocommerce-pdf-invoices-packing-slips' ) );
	}


/** Function ajax_preview_formatted_number() called by wp_ajax hooks: {'wpo_wcpdf_preview_formatted_number'} **/
/** No params detected :-/ **/


/** Function ajax_edi_save_order_customer_peppol_identifiers() called by wp_ajax hooks: {'wpo_ips_edi_save_order_customer_peppol_identifiers'} **/
/** No params detected :-/ **/


/** Function ajax_process_settings_debug_tools() called by wp_ajax hooks: {'wpo_wcpdf_debug_tools'} **/
/** No params detected :-/ **/


