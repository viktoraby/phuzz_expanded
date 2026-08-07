<?php
/***
*
*Found actions: 1
*Found functions:1
*Extracted functions:1
*Total parameter names extracted: 1
*Overview: {'optimize': {'safe_svg_optimize'}}
*
***/

/** Function optimize() called by wp_ajax hooks: {'safe_svg_optimize'} **/
/** Parameters found in function optimize(): {"get": ["optimized_svg"]} **/
function optimize() {
			$svg_url       = filter_input( INPUT_GET, 'svg_url', FILTER_SANITIZE_URL );
			$svg_id        = filter_input( INPUT_GET, 'svg_id', FILTER_SANITIZE_NUMBER_INT );
			$attachment_id = ! empty( $svg_id ) ? $svg_id : attachment_url_to_postid( $svg_url );

			if (
				empty( $_GET['optimized_svg'] ) ||
				empty( $attachment_id ) ||
				! current_user_can( 'edit_post', $attachment_id )
			) {
				return;
			}

			check_ajax_referer( $this->nonce_name, 'svg_nonce' );

			$svg_path = get_attached_file( $attachment_id );
			if ( empty( $svg_path ) ) {
				return;
			}

			$maybe_dirty = stripcslashes( $_GET['optimized_svg'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			$sanitizer   = new Sanitizer();
			$sanitizer->minify( true );
			$sanitized = $sanitizer->sanitize( $maybe_dirty );

			if ( empty( $sanitized ) ) {
				return;
			}

			file_put_contents( $svg_path, $sanitized ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents

			wp_die();
		}


