<?php
/***
*
*Found actions: 11
*Found functions:11
*Extracted functions:11
*Total parameter names extracted: 7
*Overview: {'dismiss_guide': {'ogf_dismiss_guide'}, 'ajax_save_typekit_key': {'ogf_save_typekit_key'}, 'ajax_toggle_typekit_kit': {'ogf_toggle_typekit_kit'}, 'ajax_save_font_loading': {'ogf_save_font_loading'}, 'dismiss_notice': {'ogf_dismiss_notice'}, 'ajax_save_settings': {'ogf_save_settings'}, 'ajax_reset_all_fonts': {'ogf_reset_all_fonts'}, 'ajax_delete_custom_font': {'ogf_delete_custom_font'}, 'ajax_save_custom_font': {'ogf_save_custom_font'}, 'ajax_refresh_typekit': {'ogf_refresh_typekit'}, 'ajax_clear_font_cache': {'ogf_clear_font_cache'}}
*
***/

/** Function dismiss_guide() called by wp_ajax hooks: {'ogf_dismiss_guide'} **/
/** No params detected :-/ **/


/** Function ajax_save_typekit_key() called by wp_ajax hooks: {'ogf_save_typekit_key'} **/
/** Parameters found in function ajax_save_typekit_key(): {"post": ["api_key"]} **/
function ajax_save_typekit_key() {
		check_ajax_referer( 'ogf_admin_nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to do that.', 'olympus-google-fonts' ) ) );
		}

		$key      = isset( $_POST['api_key'] ) ? sanitize_text_field( wp_unslash( $_POST['api_key'] ) ) : '';
		$settings = get_option( 'fp-typekit', array() );

		if ( ! is_array( $settings ) ) {
			$settings = array();
		}

		$settings['api_key'] = $key;
		update_option( 'fp-typekit', $settings );

		// The stored kits belong to the previous token.
		delete_option( 'fp-typekit-data' );

		if ( '' === $key ) {
			wp_send_json_success( array( 'message' => __( 'API token cleared.', 'olympus-google-fonts' ) ) );
		}

		$this->fetch_typekit_kits();
	}


/** Function ajax_toggle_typekit_kit() called by wp_ajax hooks: {'ogf_toggle_typekit_kit'} **/
/** Parameters found in function ajax_toggle_typekit_kit(): {"post": ["kit_id", "enabled"]} **/
function ajax_toggle_typekit_kit() {
		check_ajax_referer( 'ogf_admin_nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to do that.', 'olympus-google-fonts' ) ) );
		}

		$kit_id  = isset( $_POST['kit_id'] ) ? sanitize_text_field( wp_unslash( $_POST['kit_id'] ) ) : '';
		$enabled = ! empty( $_POST['enabled'] );

		if ( '' === $kit_id || ! OGF_Typekit::set_kit_enabled( $kit_id, $enabled ) ) {
			wp_send_json_error( array( 'message' => __( 'That kit could not be updated.', 'olympus-google-fonts' ) ) );
		}

		wp_send_json_success();
	}


/** Function ajax_save_font_loading() called by wp_ajax hooks: {'ogf_save_font_loading'} **/
/** Parameters found in function ajax_save_font_loading(): {"post": ["font_id", "kind", "values", "subset", "enabled"]} **/
function ajax_save_font_loading() {
		check_ajax_referer( 'ogf_admin_nonce' );

		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to do that.', 'olympus-google-fonts' ) ) );
		}

		if ( ! ogf_is_pro_active() ) {
			wp_send_json_error( array( 'message' => __( 'Selective font loading is a Pro feature.', 'olympus-google-fonts' ) ) );
		}

		$font_id = isset( $_POST['font_id'] ) ? sanitize_key( wp_unslash( $_POST['font_id'] ) ) : '';
		$kind    = isset( $_POST['kind'] ) ? sanitize_key( wp_unslash( $_POST['kind'] ) ) : '';

		if ( ! $font_id || ! ogf_is_google_font( $font_id ) || ! in_array( $kind, array( 'weight', 'subset' ), true ) ) {
			wp_send_json_error( array( 'message' => __( 'That font loading setting is not valid.', 'olympus-google-fonts' ) ) );
		}

		$fonts = OGF_Fonts::get_instance();

		if ( 'weight' === $kind ) {
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- JSON array; each entry is sanitized after decode.
			$raw_values = isset( $_POST['values'] ) ? wp_unslash( $_POST['values'] ) : '';

			if ( ! is_string( $raw_values ) ) {
				wp_send_json_error( array( 'message' => __( 'The selected weights are not valid.', 'olympus-google-fonts' ) ) );
			}

			$values = json_decode( $raw_values, true );

			if ( ! is_array( $values ) ) {
				wp_send_json_error( array( 'message' => __( 'The selected weights are not valid.', 'olympus-google-fonts' ) ) );
			}

			$selected = array();
			foreach ( $values as $value ) {
				if ( is_scalar( $value ) ) {
					$selected[] = sanitize_text_field( (string) $value );
				}
			}

			$available = array_keys( $fonts->get_font_weights( $font_id ) );
			$selected  = array_values( array_unique( array_intersect( $selected, $available ) ) );

			if ( empty( $selected ) ) {
				wp_send_json_error( array( 'message' => __( 'Select at least one weight.', 'olympus-google-fonts' ) ) );
			}

			if ( count( $selected ) === count( $available ) ) {
				remove_theme_mod( $font_id . '_weights' );
			} else {
				set_theme_mod( $font_id . '_weights', $selected );
			}

			wp_send_json_success();
		}

		$subset            = isset( $_POST['subset'] ) ? sanitize_text_field( wp_unslash( $_POST['subset'] ) ) : '';
		$enabled           = ! empty( $_POST['enabled'] );
		$available_subsets = array();

		foreach ( $fonts->choices as $choice ) {
			if ( ogf_is_google_font( $choice ) ) {
				$available_subsets = array_merge( $available_subsets, array_keys( $fonts->get_font_subsets( $choice ) ) );
			}
		}
		$available_subsets = array_values( array_unique( $available_subsets ) );

		if ( ! $subset || ! in_array( $subset, $available_subsets, true ) ) {
			wp_send_json_error( array( 'message' => __( 'That font subset is not valid.', 'olympus-google-fonts' ) ) );
		}

		$disabled = get_theme_mod( 'fpp_disable_subsets', array() );
		$disabled = is_array( $disabled ) ? $disabled : array();

		if ( $enabled ) {
			$disabled = array_values( array_diff( $disabled, array( $subset ) ) );
		} elseif ( ! in_array( $subset, $disabled, true ) ) {
			$disabled[] = $subset;
		}

		set_theme_mod( 'fpp_disable_subsets', array_values( array_unique( $disabled ) ) );
		wp_send_json_success();
	}


/** Function dismiss_notice() called by wp_ajax hooks: {'ogf_dismiss_notice'} **/
/** Parameters found in function dismiss_notice(): {"post": ["type"]} **/
function dismiss_notice() {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'insufficient_permissions' );
			}

			// phpcs:ignore WordPress.Security.NonceVerification.Missing -- AJAX handler for dismissing notices, safe operation.
			if ( isset( $_POST['type'] ) ) {
				// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Same as above.
				$type = sanitize_text_field( wp_unslash( $_POST['type'] ) );
				update_option( 'dismissed-' . $type, true );
			}
		}


/** Function ajax_save_settings() called by wp_ajax hooks: {'ogf_save_settings'} **/
/** Parameters found in function ajax_save_settings(): {"post": ["setting", "value"]} **/
function ajax_save_settings() {
		check_ajax_referer( 'ogf_admin_nonce' );

		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_send_json_error( 'insufficient_permissions' );
		}

		$setting = isset( $_POST['setting'] ) ? sanitize_text_field( wp_unslash( $_POST['setting'] ) ) : '';
		$value   = isset( $_POST['value'] ) ? sanitize_text_field( wp_unslash( $_POST['value'] ) ) : '';

		if ( ! in_array( $setting, $this->get_allowed_settings(), true ) ) {
			wp_send_json_error( 'invalid_setting' );
		}

		set_theme_mod( $this->get_setting_key( $setting ), '1' === $value );

		wp_send_json_success();
	}


/** Function ajax_reset_all_fonts() called by wp_ajax hooks: {'ogf_reset_all_fonts'} **/
/** No params detected :-/ **/


/** Function ajax_delete_custom_font() called by wp_ajax hooks: {'ogf_delete_custom_font'} **/
/** Parameters found in function ajax_delete_custom_font(): {"post": ["term_id"]} **/
function ajax_delete_custom_font() {
		check_ajax_referer( 'ogf_admin_nonce' );

		if ( ! current_user_can( OGF_Fonts_Taxonomy::$capability ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to do that.', 'olympus-google-fonts' ) ) );
		}

		$term_id = isset( $_POST['term_id'] ) ? absint( $_POST['term_id'] ) : 0;

		if ( ! $term_id ) {
			wp_send_json_error( array( 'message' => __( 'That font no longer exists.', 'olympus-google-fonts' ) ) );
		}

		$deleted = wp_delete_term( $term_id, OGF_Fonts_Taxonomy::$taxonomy_slug );

		if ( is_wp_error( $deleted ) || ! $deleted ) {
			wp_send_json_error( array( 'message' => __( 'That font could not be deleted.', 'olympus-google-fonts' ) ) );
		}

		delete_option( 'taxonomy_' . OGF_Fonts_Taxonomy::$taxonomy_slug . "_{$term_id}" );

		wp_send_json_success();
	}


/** Function ajax_save_custom_font() called by wp_ajax hooks: {'ogf_save_custom_font'} **/
/** Parameters found in function ajax_save_custom_font(): {"post": ["term_id", "name", "family", "weight", "style", "preload"]} **/
function ajax_save_custom_font() {
		check_ajax_referer( 'ogf_admin_nonce' );

		if ( ! current_user_can( OGF_Fonts_Taxonomy::$capability ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to do that.', 'olympus-google-fonts' ) ) );
		}

		$term_id = isset( $_POST['term_id'] ) ? absint( $_POST['term_id'] ) : 0;
		$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';

		if ( '' === $name ) {
			wp_send_json_error( array( 'message' => __( 'Please enter a name for this variant.', 'olympus-google-fonts' ) ) );
		}

		$data = array(
			'family'  => isset( $_POST['family'] ) ? sanitize_text_field( wp_unslash( $_POST['family'] ) ) : '',
			'weight'  => isset( $_POST['weight'] ) ? sanitize_text_field( wp_unslash( $_POST['weight'] ) ) : '',
			'style'   => isset( $_POST['style'] ) ? sanitize_text_field( wp_unslash( $_POST['style'] ) ) : '',
			'preload' => ! empty( $_POST['preload'] ) ? '1' : '',
		);

		$allowed_weights = array( '', '100', '200', '300', '400', '500', '600', '700', '800', '900' );
		$allowed_styles  = array( '', 'normal', 'italic', 'oblique' );

		if ( ! in_array( $data['weight'], $allowed_weights, true ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid font weight.', 'olympus-google-fonts' ) ) );
		}

		if ( ! in_array( $data['style'], $allowed_styles, true ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid font style.', 'olympus-google-fonts' ) ) );
		}

		$has_file = false;

		foreach ( array( 'woff2', 'woff', 'ttf', 'otf' ) as $format ) {
			$url = isset( $_POST[ $format ] ) ? trim( sanitize_text_field( wp_unslash( $_POST[ $format ] ) ) ) : '';

			if ( '' !== $url ) {
				$url = esc_url_raw( $url );

				if ( '' === $url ) {
					/* translators: %s: font file format, e.g. "woff2" */
					wp_send_json_error( array( 'message' => sprintf( __( 'The %s URL is not valid.', 'olympus-google-fonts' ), $format ) ) );
				}

				$has_file = true;
			}

			$data[ $format ] = $url;
		}

		if ( ! $has_file ) {
			wp_send_json_error( array( 'message' => __( 'Add at least one font file.', 'olympus-google-fonts' ) ) );
		}

		if ( $term_id ) {
			$term = get_term( $term_id, OGF_Fonts_Taxonomy::$taxonomy_slug );

			if ( ! $term || is_wp_error( $term ) ) {
				wp_send_json_error( array( 'message' => __( 'That font no longer exists.', 'olympus-google-fonts' ) ) );
			}

			$updated = wp_update_term( $term_id, OGF_Fonts_Taxonomy::$taxonomy_slug, array( 'name' => $name ) );
		} else {
			$updated = wp_insert_term( $name, OGF_Fonts_Taxonomy::$taxonomy_slug );
		}

		if ( is_wp_error( $updated ) ) {
			wp_send_json_error( array( 'message' => $updated->get_error_message() ) );
		}

		OGF_Fonts_Taxonomy::update_font_data( $data, $updated['term_id'] );

		wp_send_json_success( array( 'term_id' => $updated['term_id'] ) );
	}


/** Function ajax_refresh_typekit() called by wp_ajax hooks: {'ogf_refresh_typekit'} **/
/** No params detected :-/ **/


/** Function ajax_clear_font_cache() called by wp_ajax hooks: {'ogf_clear_font_cache'} **/
/** No params detected :-/ **/


