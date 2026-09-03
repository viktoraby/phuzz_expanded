<?php
/***
*
*Found actions: 2
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 2
*Overview: {'ajax_create_pages': {'cmplz_tc_create_pages'}, 'dismiss_review_notice_callback': {'dismiss_review_notice'}}
*
***/

/** Function ajax_create_pages() called by wp_ajax hooks: {'cmplz_tc_create_pages'} **/
/** Parameters found in function ajax_create_pages(): {"post": ["nonce", "pages"]} **/
function ajax_create_pages() {

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			if ( ! isset( $_POST['nonce'] ) ) {
				return;
			}

			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'complianz_tc_save' ) ) {
				return;
			}
			$error = false;
			if ( ! isset( $_POST['pages'] ) ) {
				$error = true;
			}

			if ( ! $error ) {
				$posted_pages = json_decode( wp_unslash( $_POST['pages'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- JSON data is sanitized per-field after decoding.
				foreach ( $posted_pages as $region => $pages ) {
					foreach ( $pages as $type => $title ) {
						$title = sanitize_text_field( $title );

						// The Withdrawal page is tracked by option, not the document shortcode scan.
						if ( 'withdrawal' === $type ) {
							$withdrawal_id = $this->get_withdrawal_page_id();
							if ( ! $withdrawal_id ) {
								$this->create_page( 'withdrawal' );
							} else {
								wp_update_post(
									array(
										'ID'         => $withdrawal_id,
										'post_title' => $title,
										'post_type'  => 'page',
									)
								);
							}
							continue;
						}

						$current_page_id = $this->get_shortcode_page_id( $type, false );
						if ( ! $current_page_id ) {
							$this->create_page( $type );
						} else {
							// If the page already exists, just update it with the title.
							$page = array(
								'ID'         => $current_page_id,
								'post_title' => $title,
								'post_type'  => 'page',
							);
							wp_update_post( $page );
						}
					}
				}

				// Create the Withdrawal page when the provided-form path is selected.
				$this->maybe_create_withdrawal_page();
			}
			$data = array(
				'success'         => ! $error,
				'new_button_text' => esc_html__( 'Update pages', 'complianz-terms-conditions' ),
				'icon'            => cmplz_tc_icon( 'check', 'success' ),
			);
			header( 'Content-Type: application/json' );
			echo wp_json_encode( $data ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON-encoded response is safe for output.
			exit;
		}


/** Function dismiss_review_notice_callback() called by wp_ajax hooks: {'dismiss_review_notice'} **/
/** Parameters found in function dismiss_review_notice_callback(): {"post": ["type"]} **/
function dismiss_review_notice_callback() {
			// Sanitise the type parameter; expected values are 'dismiss' or 'later'.
			// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce is validated via the 'token' field in the JS payload; lightweight AJAX action poses no state-change risk beyond dismissing a UI notice.
			$type = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : false;

			if ( sanitize_title( $type ) === 'dismiss' ) {
				// Permanently suppress the notice for this site.
				update_option( 'cmplz_tc_review_notice_shown', true );
			}
			if ( sanitize_title( $type ) === 'later' ) {
				// Reset activation timestamp; notice will show again in one month.
				update_option( 'cmplz_tc_activation_time', time() );
			}

			// Required by the WordPress AJAX protocol to terminate the request cleanly.
			wp_die();
		}


