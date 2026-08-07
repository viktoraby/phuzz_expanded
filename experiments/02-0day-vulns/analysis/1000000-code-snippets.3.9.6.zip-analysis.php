<?php
/***
*
*Found actions: 3
*Found functions:3
*Extracted functions:3
*Total parameter names extracted: 3
*Overview: {'ajax_switch_version': {'code_snippets_switch_version'}, 'ajax_callback': {'update_code_snippet'}, 'ajax_refresh_versions': {'code_snippets_refresh_versions'}}
*
***/

/** Function ajax_switch_version() called by wp_ajax hooks: {'code_snippets_switch_version'} **/
/** Parameters found in function ajax_switch_version(): {"post": ["nonce", "target_version"]} **/
function ajax_switch_version(): void {
		if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'code_snippets_version_switch' ) ) {
			wp_die( __( 'Security check failed.', 'code-snippets' ) );
		}

		if ( ! current_user_can( 'update_plugins' ) ) {
			wp_send_json_error( [
				'message' => __( 'You do not have permission to update plugins.', 'code-snippets' ),
			] );
		}

		$target_version = sanitize_text_field( $_POST['target_version'] ?? '' );

		if ( empty( $target_version ) ) {
			wp_send_json_error( [
				'message' => __( 'No target version specified.', 'code-snippets' ),
			] );
		}

		$result = self::handle_version_switch( $target_version );

		if ( $result['success'] ) {
			wp_send_json_success( $result );
		} else {
			wp_send_json_error( $result );
		}
	}


/** Function ajax_callback() called by wp_ajax hooks: {'update_code_snippet'} **/
/** Parameters found in function ajax_callback(): {"post": ["field", "snippet"]} **/
function ajax_callback() {
		check_ajax_referer( 'code_snippets_manage_ajax' );

		if ( ! isset( $_POST['field'], $_POST['snippet'] ) ) {
			wp_send_json_error(
				array(
					'type'    => 'param_error',
					'message' => 'incomplete request',
				)
			);
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$snippet_data = array_map( 'sanitize_text_field', json_decode( wp_unslash( $_POST['snippet'] ), true ) );

		$snippet = new Snippet( $snippet_data );
		$field = sanitize_key( $_POST['field'] );

		if ( 'priority' === $field ) {

			if ( ! isset( $snippet_data['priority'] ) || ! is_numeric( $snippet_data['priority'] ) ) {
				wp_send_json_error(
					array(
						'type'    => 'param_error',
						'message' => 'missing snippet priority data',
					)
				);
			}

			$this->update_snippet_priority( $snippet );

		} elseif ( 'active' === $field ) {

			if ( ! isset( $snippet_data['active'] ) ) {
				wp_send_json_error(
					array(
						'type'    => 'param_error',
						'message' => 'missing snippet active data',
					)
				);
			}

			if ( $snippet->shared_network ) {
				$active_shared_snippets = get_option( 'active_shared_network_snippets', array() );

				if ( in_array( $snippet->id, $active_shared_snippets, true ) !== $snippet->active ) {

					$active_shared_snippets = $snippet->active ?
						array_merge( $active_shared_snippets, array( $snippet->id ) ) :
						array_diff( $active_shared_snippets, array( $snippet->id ) );

					update_option( 'active_shared_network_snippets', $active_shared_snippets );
					clean_active_snippets_cache( code_snippets()->db->ms_table );
				}
			} elseif ( $snippet->active ) {
				$result = activate_snippet( $snippet->id, $snippet->network );
				if ( is_string( $result ) ) {
					wp_send_json_error(
						array(
							'type'    => 'action_error',
							'message' => $result,
						)
					);
				}
			} else {
				deactivate_snippet( $snippet->id, $snippet->network );
			}
		}

		wp_send_json_success();
	}


/** Function ajax_refresh_versions() called by wp_ajax hooks: {'code_snippets_refresh_versions'} **/
/** Parameters found in function ajax_refresh_versions(): {"post": ["nonce"]} **/
function ajax_refresh_versions(): void {
		if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'code_snippets_refresh_versions' ) ) {
			wp_die( __( 'Security check failed.', 'code-snippets' ) );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [
				'message' => __( 'You do not have permission to manage options.', 'code-snippets' ),
			] );
		}

		delete_transient( VERSION_CACHE_KEY );
		self::get_available_versions();

		wp_send_json_success( [
			'message' => __( 'Available versions updated successfully.', 'code-snippets' ),
		] );
	}


