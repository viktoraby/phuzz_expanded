<?php
/***
*
*Found actions: 4
*Found functions:4
*Extracted functions:4
*Total parameter names extracted: 3
*Overview: {'ajax_sweep_totals': {'wp_sweep_totals'}, 'ajax_sweep_count': {'wp_sweep_count'}, 'ajax_sweep_details': {'sweep_details'}, 'ajax_sweep': {'sweep'}}
*
***/

/** Function ajax_sweep_totals() called by wp_ajax hooks: {'wp_sweep_totals'} **/
/** No params detected :-/ **/


/** Function ajax_sweep_count() called by wp_ajax hooks: {'wp_sweep_count'} **/
/** Parameters found in function ajax_sweep_count(): {"get": ["sweep_name", "sweep_type"]} **/
function ajax_sweep_count() {
		$sweep = WP_Sweep::get_instance();

		$name = isset( $_GET['sweep_name'] ) ? sanitize_key( wp_unslash( $_GET['sweep_name'] ) ) : '';
		$type = isset( $_GET['sweep_type'] ) ? sanitize_key( wp_unslash( $_GET['sweep_type'] ) ) : '';

		if (
			! current_user_can( WP_Sweep::capability( 'ajax' ) )
			|| ! $sweep->is_sweep_name_valid( $name )
			|| ! $sweep->is_sweep_type_valid( $type )
		) {
			wp_send_json_error( array( 'error' => __( 'Invalid AJAX request.', 'wp-sweep' ) ) );
		}

		check_admin_referer( 'wp_sweep_count_' . $name );

		$count       = (int) $sweep->count( $name );
		$total_count = (int) $sweep->total_count( $type );

		wp_send_json_success(
			array(
				'count'      => $count,
				'total'      => $total_count,
				'percentage' => $sweep->format_percentage( $count, $total_count ),
			)
		);
	}


/** Function ajax_sweep_details() called by wp_ajax hooks: {'sweep_details'} **/
/** Parameters found in function ajax_sweep_details(): {"get": ["sweep_name"]} **/
function ajax_sweep_details() {
		$sweep = WP_Sweep::get_instance();

		$name = isset( $_GET['sweep_name'] ) ? sanitize_key( wp_unslash( $_GET['sweep_name'] ) ) : '';

		// Permissions and the name are checked before the referer, so a caller
		// without the capability gets a JSON error rather than the nonce
		// failure screen.
		if ( ! current_user_can( WP_Sweep::capability( 'ajax' ) ) || ! $sweep->is_sweep_name_valid( $name ) ) {
			wp_send_json_error( array( 'error' => __( 'Invalid AJAX request.', 'wp-sweep' ) ) );
		}

		check_admin_referer( 'wp_sweep_details_' . $name );

		wp_send_json_success( $sweep->details( $name ) );
	}


/** Function ajax_sweep() called by wp_ajax hooks: {'sweep'} **/
/** Parameters found in function ajax_sweep(): {"get": ["sweep_name", "sweep_type"]} **/
function ajax_sweep() {
		$sweep = WP_Sweep::get_instance();

		$name = isset( $_GET['sweep_name'] ) ? sanitize_key( wp_unslash( $_GET['sweep_name'] ) ) : '';
		$type = isset( $_GET['sweep_type'] ) ? sanitize_key( wp_unslash( $_GET['sweep_type'] ) ) : '';

		if (
			! current_user_can( WP_Sweep::capability( 'ajax' ) )
			|| ! $sweep->is_sweep_name_valid( $name )
			|| ! $sweep->is_sweep_type_valid( $type )
		) {
			wp_send_json_error( array( 'error' => __( 'Invalid AJAX request.', 'wp-sweep' ) ) );
		}

		check_admin_referer( 'wp_sweep_' . $name );

		$message     = $sweep->sweep( $name );
		$count       = (int) $sweep->count( $name );
		$total_count = (int) $sweep->total_count( $type );

		wp_send_json_success(
			array(
				'sweep'      => $message,
				'count'      => $count,
				'total'      => $total_count,
				'percentage' => $sweep->format_percentage( $count, $total_count ),
				'stats'      => self::related_totals( $type ),
			)
		);
	}


