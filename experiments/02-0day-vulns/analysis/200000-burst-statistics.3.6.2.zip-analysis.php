<?php
/***
*
*Found actions: 7
*Found functions:6
*Extracted functions:6
*Total parameter names extracted: 4
*Overview: {'ajax_toggle_low_traffic_auto_update': {'burst_toggle_low_traffic_auto_update'}, 'rest_api_fallback': {'burst_autoinstaller_rest_api_fallback'}, 'log_tracking_error': {'burst_tracking_error', 'nopriv_burst_tracking_error'}, 'dismiss_review_notice_callback': {'dismiss_review_notice'}, 'rest_api_fallback_get_action': {'burst_rest_api_fallback_get_action'}, 'rest_api_fallback_do_action': {'burst_rest_api_fallback_do_action'}}
*
***/

/** Function ajax_toggle_low_traffic_auto_update() called by wp_ajax hooks: {'burst_toggle_low_traffic_auto_update'} **/
/** Parameters found in function ajax_toggle_low_traffic_auto_update(): {"post": ["nonce", "plugin", "enable"]} **/
function ajax_toggle_low_traffic_auto_update(): void {
		if ( ! current_user_can( 'update_plugins' ) ) {
			wp_send_json_error( 'Unauthorized', 403 );
		}
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'burst_toggle_low_traffic_auto_update' ) ) {
			wp_send_json_error( 'Unauthorized', 403 );
		}

		$plugin_file = isset( $_POST['plugin'] ) ? sanitize_text_field( wp_unslash( $_POST['plugin'] ) ) : '';
		if ( ! $this->is_installed_plugin( $plugin_file ) ) {
			wp_send_json_error( null, 400 );
		}
		$enable = isset( $_POST['enable'] ) && sanitize_text_field( wp_unslash( $_POST['enable'] ) ) === '1';

		$managed = $this->get_managed_plugins();
		if ( $enable ) {
			$managed[] = $plugin_file;
			$managed   = array_values( array_unique( $managed ) );
		} else {
			$managed = array_values( array_diff( $managed, [ $plugin_file ] ) );
		}
		// Autoloaded: read by the auto_update_plugin filter on cron requests.
		update_option( self::MANAGED_PLUGINS_OPTION, $managed, true );

		// No trigger to (re)schedule: the hourly check picks the change up.
		if ( $enable ) {
			$this->maybe_schedule_initial_calculation();
		}

		wp_send_json_success();
	}


/** Function rest_api_fallback() called by wp_ajax hooks: {'burst_autoinstaller_rest_api_fallback'} **/
/** Parameters found in function rest_api_fallback(): {"get": ["rest_action", "is_ecommerce"]} **/
function rest_api_fallback( string $context = '' ): void {
		$response  = [];
		$error     = false;
		$action    = false;
		$do_action = false;
		$data      = [];
		$data_type = false;

		if ( ! $this->user_can_view() ) {
			$error = true;
		}

		// --- Parse GET ---
		// phpcs:ignore
		if ( isset( $_GET['rest_action'] ) ) {
			// phpcs:ignore
			$action = sanitize_text_field( $_GET['rest_action'] );

			// Handle granular datatable endpoints in fallback.
			if ( str_contains( $action, 'burst/v1/data/ecommerce/datatable/' ) ) {
				if ( ! $this->user_can_view_sales() ) {
					$error = true;
				}
				$data_type = 'datatable-' . str_replace( 'burst/v1/data/ecommerce/datatable/', '', $action );
				// Manually set is_ecommerce for the fallback request.
				$_GET['is_ecommerce'] = true;
			} elseif ( str_contains( $action, 'burst/v1/data/datatable/' ) ) {
				$data_type = 'datatable-' . str_replace( 'burst/v1/data/datatable/', '', $action );
			} elseif ( str_contains( $action, 'burst/v1/data/ecommerce/' ) ) {
				if ( ! $this->user_can_view_sales() ) {
					$error = true;
				}
				$data_type = strtolower( str_replace( 'burst/v1/data/ecommerce/', '', $action ) );
			} elseif ( str_contains( $action, 'burst/v1/data/' ) ) {
				$data_type = strtolower( str_replace( 'burst/v1/data/', '', $action ) );
			}
		}

		// --- Collect GET params ---
		// phpcs:ignore
		$get_params = $_GET;
		unset( $get_params['rest_action'] );

		// --- Parse POST body, if present ---
		$request_data = json_decode( file_get_contents( 'php://input' ), true );
		if ( is_array( $request_data ) ) {
			$req_path = isset( $request_data['path'] ) ? sanitize_text_field( $request_data['path'] ) : false;
			if ( $req_path ) {
				// override if provided by POST.
				$action = $req_path;
				if ( ! $data_type && strpos( $action, 'burst/v1/data/' ) !== false ) {
					// Extract data type for /data/* when using POST.
					if ( str_contains( $action, 'burst/v1/data/ecommerce/datatable/' ) ) {
						if ( ! $this->user_can_view_sales() ) {
							$error = true;
						}
						$data_type                            = 'ecommerce-datatable-' . str_replace( 'burst/v1/data/ecommerce/datatable/', '', $action );
						$request_data['data']['is_ecommerce'] = true;
					} elseif ( str_contains( $action, 'burst/v1/data/datatable/' ) ) {
						$data_type = 'datatable-' . str_replace( 'burst/v1/data/datatable/', '', $action );
					} else {
						if ( str_contains( $action, 'burst/v1/data/ecommerce/' ) && ! $this->user_can_view_sales() ) {
							$error = true;
						}
						$data_type = strtolower( str_replace( 'burst/v1/data/', '', $action ) );
					}
				}
			}
			$data = isset( $request_data['data'] ) && is_array( $request_data['data'] ) ? $request_data['data'] : [];

			if ( strpos( $action, 'burst/v1/do_action/' ) !== false ) {
				$do_action = strtolower( str_replace( 'burst/v1/do_action/', '', $action ) );
			}
		}

		// Enforce read/write split for fallback handlers.
		if ( $context !== '' ) {
			$is_do_action = $this->is_do_action_fallback_request( (string) $action );

			if ( ( $context === 'do_action' && ! $is_do_action ) || ( $context === 'get_action' && $is_do_action ) ) {
				$this->send_ajax_fallback_forbidden_response();
			}
		}

		$nonce = $get_params['nonce'] ?? ( $request_data['data']['nonce'] ?? null );
		if ( ! $this->verify_nonce( $nonce, 'burst_nonce' ) ) {
			$response = new \WP_REST_Response(
				[
					'success' => false,
					'message' => $this->nonce_expired_feedback,
				]
			);
			ob_get_clean();
			header( 'Content-Type: application/json' );
			echo wp_json_encode( $response );
			exit;
		}

		// Fallback notice.
		$fallback_already_active = (int) get_option( 'burst_ajax_fallback_active_timestamp', 0 );
		if ( $fallback_already_active === 0 ) {
			update_option( 'burst_ajax_fallback_active_timestamp', time(), false );
		}
		if ( $fallback_already_active > 0 && ( time() - $fallback_already_active ) > 48 * HOUR_IN_SECONDS ) {
			update_option( 'burst_ajax_fallback_active', true, false );
		}

		// Normalize/merge params from GET and POST data.
		$merged = $get_params;
		foreach ( [ 'goal_id', 'type', 'date_start', 'date_end', 'args', 'search', 'filters', 'metrics', 'group_by', 'isOnboarding', 'id', 'is_ecommerce' ] as $k ) {
			if ( array_key_exists( $k, $data ) ) {
				$merged[ $k ] = $data[ $k ];
			}
		}

		// Convert metrics string -> array.
		if ( isset( $merged['metrics'] ) && is_string( $merged['metrics'] ) ) {
			$merged['metrics'] = explode( ',', $merged['metrics'] );
		}

		// Handle filters slashes (string JSON coming from GET); keep arrays as-is.
		if ( isset( $merged['filters'] ) && is_string( $merged['filters'] ) ) {
			$merged['filters'] = stripslashes( $merged['filters'] );
		}

		// Build WP_REST_Request with merged params.
		$request = new \WP_REST_Request();
		foreach ( [ 'goal_id', 'type', 'nonce', 'date_start', 'date_end', 'args', 'search', 'filters', 'metrics', 'group_by', 'id', 'is_ecommerce' ] as $arg ) {
			if ( isset( $merged[ $arg ] ) ) {
				$request->set_param( $arg, $merged[ $arg ] );
			}
		}

		// If we detected /data/, make sure 'type' is set from the path.
		if ( $data_type ) {
			$request->set_param( 'type', $data_type );
		}

		if ( ! $error ) {
			if ( str_contains( $action, '/fields/get' ) ) {
				$response = $this->rest_api_fields_get( $request );
			} elseif ( str_contains( $action, '/fields/set' ) ) {
				$response = $this->rest_api_fields_set( $request, $data );
			} elseif ( str_contains( $action, '/goals/get' ) ) {
				$response = $this->rest_api_goals_get( $request );
			} elseif ( str_contains( $action, '/goals/add' ) ) {
				$response = $this->rest_api_goals_add( $request, $data );
			} elseif ( str_contains( $action, '/goals/upsert_for_block' ) ) {
				$response = $this->rest_api_goals_upsert_for_block( $request, $data );
			} elseif ( str_contains( $action, '/goals/delete' ) ) {
				$response = $this->rest_api_goals_delete( $request, $data );
			} elseif ( str_contains( $action, '/goal_fields/get' ) ) {
				$response = $this->rest_api_goal_fields_get( $request );
			} elseif ( str_contains( $action, '/goals/set' ) ) {
				$response = $this->rest_api_goals_set( $request, $data );
			} elseif ( str_contains( $action, '/posts/' ) ) {
				$response = $this->get_posts( $request, $data );
			} elseif ( str_contains( $action, '/data/' ) ) {
				$response = $this->get_data( $request );
			} elseif ( strpos( $action, '/reports' ) ) {
				$reports  = new Reports();
				$response = $reports->get_reports( $request );
			} elseif ( $do_action ) {
				$req = new \WP_REST_Request();
				$req->set_param( 'action', $do_action );
				$response = $this->do_action( $req, $data );
			} elseif ( strpos( $action, 'burst/v1/get_action/' ) !== false ) {
				$get_action = strtolower( str_replace( 'burst/v1/get_action/', '', $action ) );
				$req        = new \WP_REST_Request();
				$req->set_param( 'action', $get_action );
				$response = $this->get_action( $req, $merged );
			}
		}

		ob_get_clean();
		header( 'Content-Type: application/json' );
		echo wp_json_encode( $response );
		exit;
	}


/** Function log_tracking_error() called by wp_ajax hooks: {'burst_tracking_error', 'nopriv_burst_tracking_error'} **/
/** Parameters found in function log_tracking_error(): {"post": ["status", "data", "error"]} **/
function log_tracking_error(): void {
		if ( ! defined( 'BURST_DEBUG' ) || ! BURST_DEBUG ) {
			// If debug mode is not enabled, do not log errors.
			return;
		}

		// No form data processed, only exit if not present.
		// phpcs:ignore
		if ( ! isset( $_POST['status'] ) || ! isset( $_POST['data'] ) || ! isset( $_POST['error'] ) ) {
			$this::error_log( 'Posted log error, but missing required POST parameters.' );
			return;
		}

		// no nonce verification, as we are logging public 400 response errors.
		// phpcs:ignore
		$status = (int) ( $_POST['status'] );
		// phpcs:ignore
		$raw_data = stripslashes( $_POST['data'] );
		$data     = json_decode( $raw_data, true );
		if ( ! is_array( $data ) ) {
			$data = [];
		}

		$data = [
			'uid'               => isset( $data['uid'] ) && is_string( $data['uid'] ) ? sanitize_text_field( $data['uid'] ) : false,
			'fingerprint'       => isset( $data['fingerprint'] ) && is_string( $data['fingerprint'] ) ? sanitize_text_field( $data['fingerprint'] ) : false,
			'url'               => isset( $data['url'] ) ? esc_url_raw( $data['url'] ) : '',
			'referrer_url'      => isset( $data['referrer_url'] ) ? esc_url_raw( $data['referrer_url'] ) : '',
			'user_agent'        => isset( $data['user_agent'] ) ? sanitize_text_field( $data['user_agent'] ) : '',
			'device_resolution' => isset( $data['device_resolution'] ) ? preg_replace( '/[^0-9x]/', '', $data['device_resolution'] ) : '',
			'time_on_page'      => isset( $data['time_on_page'] ) ? (int) $data['time_on_page'] : 0,
			'completed_goals'   => isset( $data['completed_goals'] ) && is_array( $data['completed_goals'] )
				? array_map( 'intval', $data['completed_goals'] )
				: [],
		];
		// no nonce verification, as we are logging public 400 response errors.
		// phpcs:ignore
		$error = sanitize_text_field( $_POST['error'] );
		// usage of print_r is intentional here, as this is a debug log.
		// phpcs:ignore
		$this::error_log( "Burst tracking error: status=$status, error=$error, data=" . print_r( $data, true ) );
	}


/** Function dismiss_review_notice_callback() called by wp_ajax hooks: {'dismiss_review_notice'} **/
/** Parameters found in function dismiss_review_notice_callback(): {"post": ["type", "token"]} **/
function dismiss_review_notice_callback(): void {
		$type  = isset( $_POST['type'] ) ? sanitize_title( wp_unslash( $_POST['type'] ) ) : false;
		$token = isset( $_POST['token'] ) ? sanitize_title( wp_unslash( $_POST['token'] ) ) : false;
		if ( ! wp_verify_nonce( $token, 'burst_dismiss_review' ) ) {
			wp_die();
		}
		if ( $type === 'dismiss' ) {
			update_option( 'burst_review_notice_shown', true, false );
		}
		if ( $type === 'later' ) {
			update_option( 'burst_activation_time', time(), false );
		}

		// this is required to terminate immediately and return a proper response.
		wp_die();
	}


/** Function rest_api_fallback_get_action() called by wp_ajax hooks: {'burst_rest_api_fallback_get_action'} **/
/** No params detected :-/ **/


/** Function rest_api_fallback_do_action() called by wp_ajax hooks: {'burst_rest_api_fallback_do_action'} **/
/** No params detected :-/ **/


