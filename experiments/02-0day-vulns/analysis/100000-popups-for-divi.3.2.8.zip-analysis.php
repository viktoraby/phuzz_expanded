<?php
/***
*
*Found actions: 4
*Found functions:4
*Extracted functions:4
*Total parameter names extracted: 4
*Overview: {'pfd_admin_ajax_start_course': {'pfd_start_course'}, 'dm_admin_notice_ajax_dismiss': {'dm_notice_dismiss'}, 'pfd_admin_ajax_hide_onboarding': {'pfd_hide_onboarding'}, 'pfd_builder_et_fb_ajax_save': {'et_fb_ajax_save'}}
*
***/

/** Function pfd_admin_ajax_start_course() called by wp_ajax hooks: {'pfd_start_course'} **/
/** Parameters found in function pfd_admin_ajax_start_course(): {"post": ["_wpnonce"]} **/
function pfd_admin_ajax_start_course() {
	// Make sure that the ajax request comes from the current WP admin site!
	if (
		! is_user_logged_in() // better safe than sorry.
		|| empty( $_POST['_wpnonce'] )
		|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'onboarding' )
	) {
		wp_send_json_success( 'ERROR' );
	}

	$form = wp_unslash( $_POST ); // input var okay.

	$email = sanitize_email( trim( $form['email'] ) );
	$name  = sanitize_text_field( trim( $form['name'] ) );

	// Send the subscription details to our website.
	$resp = wp_remote_post(
		'https://divimode.com/wp-admin/admin-post.php',
		[
			'headers' => [
				'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8',
			],
			'body'    => [
				'action' => 'pfd_start_onboarding',
				'fname'  => $name,
				'email'  => $email,
			],
		]
	);

	if ( is_wp_error( $resp ) ) {
		wp_send_json_success( 'ERROR' );
	}

	$result = wp_remote_retrieve_body( $resp );
	wp_send_json_success( $result );
}


/** Function dm_admin_notice_ajax_dismiss() called by wp_ajax hooks: {'dm_notice_dismiss'} **/
/** Parameters found in function dm_admin_notice_ajax_dismiss(): {"post": ["id"]} **/
function dm_admin_notice_ajax_dismiss() {
	if ( empty( $_POST['id'] ) ) {
		return;
	}
	$notice_id = (int) $_POST['id'];
	check_ajax_referer( 'dismiss-notice-' . $notice_id );

	$user = wp_get_current_user();

	if ( ! $notice_id || ! $user || ! $user->ID ) {
		return;
	}

	// Permanently dismiss the notification for the current user.
	$dismissed = (array) $user->get( '_dm_dismissed' );

	$dismissed[ $notice_id ] = time();
	update_user_meta( $user->ID, '_dm_dismissed', $dismissed );

	wp_send_json_success();
}


/** Function pfd_admin_ajax_hide_onboarding() called by wp_ajax hooks: {'pfd_hide_onboarding'} **/
/** Parameters found in function pfd_admin_ajax_hide_onboarding(): {"post": ["_wpnonce"]} **/
function pfd_admin_ajax_hide_onboarding() {
	// Make sure that the ajax request comes from the current WP admin site!
	if (
		! is_user_logged_in() // better safe than sorry.
		|| empty( $_POST['_wpnonce'] )
		|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'no-onboarding' )
	) {
		wp_send_json_success( 'ERROR' );
	}

	// phpcs:ignore WordPress.VIP.RestrictedFunctions.user_meta_update_user_meta
	update_user_meta(
		get_current_user_id(),
		'_pfd_onboarding',
		'done'
	);

	wp_send_json_success();
}


/** Function pfd_builder_et_fb_ajax_save() called by wp_ajax hooks: {'et_fb_ajax_save'} **/
/** Parameters found in function pfd_builder_et_fb_ajax_save(): {"post": ["et_fb_save_nonce", "post_id", "options", "modules"]} **/
function pfd_builder_et_fb_ajax_save() {
	/**
	 * We disable phpcs for the following block, so we can use the identical code
	 * that is used inside the Divi theme.
	 *
	 * @see et_fb_ajax_save() in themes/Divi/includes/builder/functions.php
	 */
	if (
		! isset( $_POST['et_fb_save_nonce'] ) ||
		! wp_verify_nonce( sanitize_key( $_POST['et_fb_save_nonce'] ), 'et_fb_save_nonce' )
	) {
		return;
	}

	$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;

	// phpcs:disable WordPress.Security.ValidatedSanitizedInput.MissingUnslash
	// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	// phpcs:disable ET.Sniffs.ValidatedSanitizedInput.InputNotSanitized
	if (
		! isset( $_POST['options'] )
		|| ! isset( $_POST['options']['status'] )
		|| ! isset( $_POST['modules'] )
		|| ! et_fb_current_user_can_save( $post_id, $_POST['options']['status'] )
	) {
		return;
	}

	// Fetch the builder attributes and sanitize them.
	$shortcode_data = json_decode( stripslashes( $_POST['modules'] ), true );
	// phpcs:enable

	// Popup defaults.
	$da_default = [
		'da_is_popup'        => 'off',
		'da_popup_slug'      => '',
		'da_exit_intent'     => 'off',
		'da_has_close'       => 'on',
		'da_alt_close'       => 'off',
		'da_dark_close'      => 'off',
		'da_not_modal'       => 'on',
		'da_is_singular'     => 'off',
		'da_with_loader'     => 'off',
		'da_has_shadow'      => 'on',
		'da_disable_devices' => [ 'off', 'off', 'off' ],
	];

	// Remove all functional classes from the section.
	$special_classes = [
		'popup',
		'on-exit',
		'no-close',
		'close-alt',
		'dark',
		'is-modal',
		'single',
		'with-loader',
		'no-shadow',
		'not-mobile',
		'not-tablet',
		'not-desktop',
	];

	foreach ( $shortcode_data as $id => $item ) {
		$type = sanitize_text_field( $item['type'] );
		if ( 'et_pb_section' !== $type ) {
			continue;
		}
		$attrs   = $item['attrs'];
		$conf    = $da_default;
		$classes = [];


		if ( ! empty( $attrs['module_class'] ) ) {
			$classes = explode( ' ', $attrs['module_class'] );

			if ( in_array( 'popup', $classes, true ) ) {
				$conf['da_is_popup'] = 'on';

				if ( ! empty( $attrs['module_id'] ) ) {
					$conf['da_popup_slug'] = $attrs['module_id'];
				}
				if ( in_array( 'on-exit', $classes, true ) ) {
					$conf['da_exit_intent'] = 'on';
				}
				if ( in_array( 'no-close', $classes, true ) ) {
					$conf['da_has_close'] = 'off';
				}
				if ( in_array( 'close-alt', $classes, true ) ) {
					$conf['da_alt_close'] = 'on';
				}
				if ( in_array( 'dark', $classes, true ) ) {
					$conf['da_dark_close'] = 'on';
				}
				if ( in_array( 'is-modal', $classes, true ) ) {
					$conf['da_not_modal'] = 'off';
				}
				if ( in_array( 'single', $classes, true ) ) {
					$conf['da_is_singular'] = 'on';
				}
				if ( in_array( 'with-loader', $classes, true ) ) {
					$conf['da_with_loader'] = 'on';
				}
				if ( in_array( 'no-shadow', $classes, true ) ) {
					$conf['da_has_shadow'] = 'off';
				}
				if ( in_array( 'not-mobile', $classes, true ) ) {
					$conf['da_disable_devices'][0] = 'on';
				}
				if ( in_array( 'not-tablet', $classes, true ) ) {
					$conf['da_disable_devices'][1] = 'on';
				}
				if ( in_array( 'not-desktop', $classes, true ) ) {
					$conf['da_disable_devices'][2] = 'on';
				}
			}
		}

		// Set all missing Divi Area attributes with a default value.
		foreach ( $conf as $key => $def_value ) {
			if ( ! isset( $attrs[ $key ] ) ) {
				if ( 'da_disable_devices' === $key ) {
					$def_value = implode( '|', $def_value );
				}
				$attrs[ $key ] = $def_value;
			}
		}

		$classes = array_diff( $classes, $special_classes );

		// Finally set the class to match all attributes.
		if ( 'on' === $attrs['da_is_popup'] ) {
			$classes[] = 'popup';

			if ( 'on' === $attrs['da_exit_intent'] ) {
				$classes[] = 'on-exit';
			}
			if ( 'on' !== $attrs['da_has_close'] ) {
				$classes[] = 'no-close';
			}
			if ( 'on' === $attrs['da_alt_close'] ) {
				$classes[] = 'close-alt';
			}
			if ( 'on' === $attrs['da_dark_close'] ) {
				$classes[] = 'dark';
			}
			if ( 'on' !== $attrs['da_not_modal'] ) {
				$classes[] = 'is-modal';
			}
			if ( 'on' === $attrs['da_is_singular'] ) {
				$classes[] = 'single';
			}
			if ( 'on' === $attrs['da_with_loader'] ) {
				$classes[] = 'with-loader';
			}
			if ( 'on' !== $attrs['da_has_shadow'] ) {
				$classes[] = 'no-shadow';
			}
			if ( 'on' === $attrs['da_disable_devices'][0] ) {
				$classes[] = 'not-mobile';
			}
			if ( 'on' === $attrs['da_disable_devices'][1] ) {
				$classes[] = 'not-tablet';
			}
			if ( 'on' === $attrs['da_disable_devices'][2] ) {
				$classes[] = 'not-desktop';
			}
			if ( $attrs['da_popup_slug'] ) {
				$attrs['module_id'] = $attrs['da_popup_slug'];
			}
		}
		if ( $classes ) {
			$attrs['module_class'] = implode( ' ', $classes );
		} else {
			unset( $attrs['module_class'] );
		}

		if ( 'on' === $attrs['da_is_popup'] ) {
			if (
				empty( $attrs['admin_label'] )
				|| 0 === strpos( $attrs['admin_label'], 'Popup - ' )
			) {
				if ( $attrs['module_id'] ) {
					$attrs['admin_label'] = 'Popup - #' . $attrs['module_id'];
				} else {
					$attrs['admin_label'] = 'Popup - (unnamed)';
				}
			}
		} elseif (
			empty( $attrs['admin_label'] )
			|| 0 === strpos( $attrs['admin_label'], 'Popup - ' )
		) {
			$attrs['admin_label'] = '';
		}

		$shortcode_data[ $id ]['attrs'] = $attrs;
	}

	$_POST['modules'] = addslashes( wp_json_encode( $shortcode_data ) );
}


