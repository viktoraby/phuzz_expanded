<?php
/***
*
*Found actions: 6
*Found functions:6
*Extracted functions:6
*Total parameter names extracted: 6
*Overview: {'skelet_export': {'skelet-export'}, 'skelet_get_icons': {'skelet-get-icons'}, 'skelet_chosen_ajax': {'skelet-chosen'}, 'skelet_reset_ajax': {'skelet-reset'}, 'skelet_import_ajax': {'skelet-import'}, 'dismiss_admin_notice': {'dismiss_admin_notice'}}
*
***/

/** Function skelet_export() called by wp_ajax hooks: {'skelet-export'} **/
/** Parameters found in function skelet_export(): {"get": ["nonce", "unique"]} **/
function skelet_export() {

    $nonce  = ( ! empty( $_GET[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ 'nonce' ] ) ) : '';
    $unique = ( ! empty( $_GET[ 'unique' ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ 'unique' ] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'skelet_backup_nonce' ) ) {
      die( esc_html__( 'Error: Invalid nonce verification.', 'skelet' ) );
    }

    if ( empty( $unique ) ) {
      die( esc_html__( 'Error: Invalid key.', 'skelet' ) );
    }

    // Export
    header('Content-Type: application/json');
    header('Content-disposition: attachment; filename=backup-'. gmdate( 'd-m-Y' ) .'.json');
    header('Content-Transfer-Encoding: binary');
    header('Pragma: no-cache');
    header('Expires: 0');

    echo json_encode( get_option( $unique ) );

    die();

  }


/** Function skelet_get_icons() called by wp_ajax hooks: {'skelet-get-icons'} **/
/** Parameters found in function skelet_get_icons(): {"post": ["nonce"]} **/
function skelet_get_icons() {

    $nonce = ( ! empty( $_POST[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'skelet_icon_nonce' ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'skelet' ) ) );
    }

    ob_start();

    $icon_library = ( apply_filters( 'skelet_fa4', false ) ) ? 'fa4' : 'fa5';

    SKELET::include_plugin_file( 'fields/icon/'. $icon_library .'-icons.php' );

    $icon_lists = apply_filters( 'skelet_field_icon_add_icons', skelet_get_default_icons() );

    if ( ! empty( $icon_lists ) ) {

      foreach ( $icon_lists as $list ) {

        echo ( count( $icon_lists ) >= 2 ) ? '<div class="skelet-icon-title">'. esc_attr( $list['title'] ) .'</div>' : '';

        foreach ( $list['icons'] as $icon ) {
          echo '<i title="'. esc_attr( $icon ) .'" class="'. esc_attr( $icon ) .'"></i>';
        }

      }

    } else {

      echo '<div class="skelet-error-text">'. esc_html__( 'No data available.', 'skelet' ) .'</div>';

    }

    $content = ob_get_clean();

    wp_send_json_success( array( 'content' => $content ) );

  }


/** Function skelet_chosen_ajax() called by wp_ajax hooks: {'skelet-chosen'} **/
/** Parameters found in function skelet_chosen_ajax(): {"post": ["nonce", "type", "term", "query_args"]} **/
function skelet_chosen_ajax() {

    $nonce = ( ! empty( $_POST[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ) : '';
    $type  = ( ! empty( $_POST[ 'type' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'type' ] ) ) : '';
    $term  = ( ! empty( $_POST[ 'term' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'term' ] ) ) : '';
    $query = ( ! empty( $_POST[ 'query_args' ] ) ) ? wp_kses_post_deep( $_POST[ 'query_args' ] ) : array();

    if ( ! wp_verify_nonce( $nonce, 'skelet_chosen_ajax_nonce' ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'skelet' ) ) );
    }

    if ( empty( $type ) || empty( $term ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid term ID.', 'skelet' ) ) );
    }

    $capability = apply_filters( 'skelet_chosen_ajax_capability', 'manage_options' );

    if ( ! current_user_can( $capability ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: You do not have permission to do that.', 'skelet' ) ) );
    }

    // Success
    $options = SKELET_Fields::field_data( $type, $term, $query );

    wp_send_json_success( $options );

  }


/** Function skelet_reset_ajax() called by wp_ajax hooks: {'skelet-reset'} **/
/** Parameters found in function skelet_reset_ajax(): {"post": ["nonce", "unique"]} **/
function skelet_reset_ajax() {

    $nonce  = ( ! empty( $_POST[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ) : '';
    $unique = ( ! empty( $_POST[ 'unique' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'unique' ] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'skelet_backup_nonce' ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'skelet' ) ) );
    }

    // Success
    delete_option( $unique );

    wp_send_json_success();

  }


/** Function skelet_import_ajax() called by wp_ajax hooks: {'skelet-import'} **/
/** Parameters found in function skelet_import_ajax(): {"post": ["nonce", "unique", "data"]} **/
function skelet_import_ajax() {

    $nonce  = ( ! empty( $_POST[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ) : '';
    $unique = ( ! empty( $_POST[ 'unique' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'unique' ] ) ) : '';
    $data   = ( ! empty( $_POST[ 'data' ] ) ) ? wp_kses_post_deep( json_decode( wp_unslash( trim( $_POST[ 'data' ] ) ), true ) ) : array();

    if ( ! wp_verify_nonce( $nonce, 'skelet_backup_nonce' ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'skelet' ) ) );
    }

    if ( empty( $unique ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid key.', 'skelet' ) ) );
    }

    if ( empty( $data ) || ! is_array( $data ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: The response is not a valid JSON response.', 'skelet' ) ) );
    }

    // Success
    update_option( $unique, $data );

    wp_send_json_success();

  }


/** Function dismiss_admin_notice() called by wp_ajax hooks: {'dismiss_admin_notice'} **/
/** Parameters found in function dismiss_admin_notice(): {"post": ["option_name", "dismissible_length"]} **/
function dismiss_admin_notice() {
			$option_name        = isset( $_POST['option_name'] ) ? sanitize_text_field( wp_unslash( $_POST['option_name'] ) ) : '';
			$dismissible_length = isset( $_POST['dismissible_length'] ) ? sanitize_text_field( wp_unslash( $_POST['dismissible_length'] ) ) : 0;

			if ( 'forever' !== $dismissible_length ) {
				// If $dismissible_length is not an integer default to 1.
				$dismissible_length = ( 0 === absint( $dismissible_length ) ) ? 1 : $dismissible_length;
				$dismissible_length = strtotime( absint( $dismissible_length ) . ' days' );
			}

			check_ajax_referer( 'dismissible-notice', 'nonce' );
			self::set_admin_notice_cache( $option_name, $dismissible_length );
			wp_die();
		}


