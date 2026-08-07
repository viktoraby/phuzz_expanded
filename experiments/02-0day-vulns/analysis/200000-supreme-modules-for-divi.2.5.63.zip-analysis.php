<?php
/***
*
*Found actions: 4
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 2
*Overview: {'dsm_load_caldera_forms': {'dsm_load_caldera_forms', 'nopriv_dsm_load_caldera_forms'}, 'dsm_load_cf7_library': {'nopriv_dsm_load_cf7_library', 'dsm_load_cf7_library'}}
*
***/

/** Function dsm_load_caldera_forms() called by wp_ajax hooks: {'dsm_load_caldera_forms', 'nopriv_dsm_load_caldera_forms'} **/
/** Parameters found in function dsm_load_caldera_forms(): {"post": ["et_admin_load_nonce", "cf_library"]} **/
function dsm_load_caldera_forms() {
		if ( class_exists( 'Caldera_Forms' ) ) {
			add_filter(
				'caldera_forms_render_field_file',
				function ( $field_file, $field_type ) {
					if ( 'dropdown' === $field_type ) {
						return __DIR__ . '/modules/CalderaForms/includes/dropdown/field.php';
					}
					if ( 'button' === $field_type ) {
						return __DIR__ . '/modules/CalderaForms/includes/button/field.php';
					}
					if ( 'radio' === $field_type ) {
						return __DIR__ . '/modules/CalderaForms/includes/radio/field.php';
					}
					if ( 'checkbox' === $field_type ) {
						return __DIR__ . '/modules/CalderaForms/includes/checkbox/field.php';
					}
					if ( 'html' === $field_type ) {
						return __DIR__ . '/modules/CalderaForms/includes/html/field.php';
					}
					if ( 'advanced_file' === $field_type ) {
						return __DIR__ . '/modules/CalderaForms/includes/advanced_file/field.php';
					}
					return $field_file;
				},
				10,
				2
			);
			// disable CF styles.

			add_filter( 'caldera_forms_get_style_includes', 'dsm_filter_caldera_forms_get_style_includes', 10, 1 );

			if (
				isset( $_POST['et_admin_load_nonce'], $_POST['et_admin_load_nonce'], $_POST['cf_library'] )
				&& wp_verify_nonce( sanitize_key( $_POST['et_admin_load_nonce'] ), 'et_admin_load_nonce' )
			) {
				echo do_shortcode( '[caldera_form id="' . sanitize_text_field( wp_unslash( $_POST['cf_library'] ) ) . '"]' );
				wp_die();
			} else {
				esc_html_e( 'No Caldera forms selected.', 'supreme-modules-for-divi' );
				wp_die();
			}
		}
	}


/** Function dsm_load_cf7_library() called by wp_ajax hooks: {'nopriv_dsm_load_cf7_library', 'dsm_load_cf7_library'} **/
/** Parameters found in function dsm_load_cf7_library(): {"post": ["et_admin_load_nonce", "cf7_library"]} **/
function dsm_load_cf7_library() {
		if ( isset( $_POST['et_admin_load_nonce'], $_POST['et_admin_load_nonce'], $_POST['cf7_library'] ) && wp_verify_nonce( sanitize_key( $_POST['et_admin_load_nonce'] ), 'et_admin_load_nonce' ) ) {
			echo do_shortcode( '[contact-form-7 id="' . sanitize_text_field( wp_unslash( $_POST['cf7_library'] ) ) . '"]' );
			wp_die();
		} else {
			esc_html_e( 'No Contact Form 7 selected.', 'supreme-modules-for-divi' );
			wp_die();
		}
	}


