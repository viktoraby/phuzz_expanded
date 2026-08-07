<?php
/***
*
*Found actions: 39
*Found functions:30
*Extracted functions:30
*Total parameter names extracted: 31
*Overview: {'update_builder': {'um_update_builder'}, 'do_ajax_action': {'um_do_ajax_action'}, 'dismiss_notice': {'um_dismiss_notice'}, 'ajax_run_package': {'um_run_package'}, 'update_field': {'um_update_field'}, 'ajax_scanner': {'um_secure_scan_affected_users'}, 'get_users': {'um_get_users'}, 'dynamic_modal_content': {'um_dynamic_modal_content'}, 'ajax_get_packages': {'um_get_packages'}, 'load_posts': {'um_ajax_paginate_posts', 'nopriv_um_ajax_paginate_posts'}, 'ajax_muted_action': {'um_muted_action'}, 'ajax_resize_image': {'um_resize_image', 'nopriv_um_resize_image'}, 'default_filter_settings': {'um_member_directory_default_filter_settings'}, 'get_pages_list': {'um_get_pages_list'}, 'search_widget_request': {'um_search_widget_request', 'nopriv_um_search_widget_request'}, 'ajax_paginate': {'um_ajax_paginate'}, 'load_comments': {'um_ajax_paginate_comments', 'nopriv_um_ajax_paginate_comments'}, 'populate_dropdown_options': {'um_populate_dropdown_options'}, 'ajax_image_upload': {'nopriv_um_imageupload', 'um_imageupload'}, 'ajax_get_members': {'nopriv_um_get_members', 'um_get_members'}, 'ajax_remove_file': {'um_remove_file', 'nopriv_um_remove_file'}, 'ajax_delete_profile_photo': {'um_delete_profile_photo'}, 'um_request_user_data': {'um_request_user_data'}, 'ajax_file_upload': {'um_fileupload', 'nopriv_um_fileupload'}, 'update_order': {'um_update_order'}, 'ajax_select_options': {'um_select_options', 'nopriv_um_select_options'}, 'ajax_delete_cover_photo': {'um_delete_cover_photo'}, 'ultimatemember_rated': {'um_rated'}, 'get_icons': {'um_get_icons'}, 'same_page_update_ajax': {'um_same_page_update'}}
*
***/

/** Function update_builder() called by wp_ajax hooks: {'um_update_builder'} **/
/** Parameters found in function update_builder(): {"post": ["form_id"]} **/
function update_builder() {
			UM()->admin()->check_ajax_nonce();

			if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'Please login as administrator', 'ultimate-member' ) );
			}

			ob_start();

			$this->form_id = absint( $_POST['form_id'] );

			$this->show_builder();

			$output = ob_get_clean();

			if ( is_array( $output ) ) {
				print_r( $output );
			} else {
				echo $output;
			}
			die;
		}


/** Function do_ajax_action() called by wp_ajax hooks: {'um_do_ajax_action'} **/
/** Parameters found in function do_ajax_action(): {"post": ["act_id", "in_row", "in_sub_row", "in_column", "in_group", "arg1", "arg2"]} **/
function do_ajax_action() {
			UM()->admin()->check_ajax_nonce();

			// phpcs:disable WordPress.Security.NonceVerification
			if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'Please login as administrator.', 'ultimate-member' ) );
			}

			if ( ! isset( $_POST['act_id'] ) ) {
				wp_send_json_error( __( 'Invalid action.', 'ultimate-member' ) );
			}

			$in_row   = isset( $_POST['in_row'] ) ? absint( $_POST['in_row'] ) : 0;
			$position = array(
				'in_row'     => '_um_row_' . ( $in_row + 1 ),
				'in_sub_row' => isset( $_POST['in_sub_row'] ) ? absint( $_POST['in_sub_row'] ) : '',
				'in_column'  => isset( $_POST['in_column'] ) ? absint( $_POST['in_column'] ) : '',
				'in_group'   => isset( $_POST['in_group'] ) ? absint( $_POST['in_group'] ) : '',
			);

			switch ( sanitize_key( $_POST['act_id'] ) ) {
				case 'um_admin_duplicate_field':
					// arg1 is a field metakey(id)
					// arg2 is a form ID.
					$this->duplicate_field( sanitize_text_field( $_POST['arg1'] ), absint( $_POST['arg2'] ) );
					break;
				case 'um_admin_remove_field_global':
					// arg1 is a field metakey(id)
					$this->delete_field_from_db( sanitize_text_field( $_POST['arg1'] ) );
					break;
				case 'um_admin_remove_field':
					// arg1 is a field metakey(id)
					// arg2 is a form ID.
					$this->delete_field_from_form( sanitize_text_field( $_POST['arg1'] ), absint( $_POST['arg2'] ) );
					break;
				case 'um_admin_add_field_from_predefined':
					// arg1 is a field metakey(id)
					// arg2 is a form ID.
					$this->add_field_from_predefined( sanitize_text_field( $_POST['arg1'] ), absint( $_POST['arg2'] ), $position );
					break;
				case 'um_admin_add_field_from_list':
					// arg1 is a field metakey(id)
					// arg2 is a form ID.
					$this->add_field_from_list( sanitize_text_field( $_POST['arg1'] ), absint( $_POST['arg2'] ), $position );
					break;
			}
			// phpcs:enable WordPress.Security.NonceVerification
			wp_send_json_success();
		}


/** Function dismiss_notice() called by wp_ajax hooks: {'um_dismiss_notice'} **/
/** Parameters found in function dismiss_notice(): {"post": ["key"]} **/
function dismiss_notice() {
			UM()->admin()->check_ajax_nonce();

			if ( empty( $_POST['key'] ) ) {
				wp_send_json_error( __( 'Wrong Data', 'ultimate-member' ) );
			}

			$this->dismiss( sanitize_key( $_POST['key'] ) );

			wp_send_json_success();
		}


/** Function ajax_run_package() called by wp_ajax hooks: {'um_run_package'} **/
/** Parameters found in function ajax_run_package(): {"post": ["pack"]} **/
function ajax_run_package() {
			UM()->admin()->check_ajax_nonce();

			if ( empty( $_POST['pack'] ) ) {
				exit('');
			} else {
				$pack = sanitize_text_field( $_POST['pack'] );
				if ( in_array( $pack, $this->necessary_packages, true ) ) {
					$file = $this->packages_dir . $pack . DIRECTORY_SEPARATOR . 'init.php';
					if ( file_exists( $file ) ) {
						ob_start();
						include_once $file;
						ob_get_flush();
						exit;
					} else {
						exit('');
					}
				} else {
					exit('');
				}
			}
		}


/** Function update_field() called by wp_ajax hooks: {'um_update_field'} **/
/** Parameters found in function update_field(): {"post": ["_type", "post_id"]} **/
function update_field() {
			UM()->admin()->check_ajax_nonce();

			if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'Please login as administrator', 'ultimate-member' ) );
			}

			$output['error'] = null;

			// phpcs:disable WordPress.Security.NonceVerification -- Already verified by `UM()->admin()->check_ajax_nonce()`
			$array = array(
				'field_type' => sanitize_key( $_POST['_type'] ),
				'form_id'    => absint( $_POST['post_id'] ),
				'args'       => UM()->builtin()->get_core_field_attrs( sanitize_key( $_POST['_type'] ) ),
				'post'       => UM()->admin()->sanitize_builder_field_meta( $_POST ),
			);
			// phpcs:enable WordPress.Security.NonceVerification -- Already verified by `UM()->admin()->check_ajax_nonce()`

			/**
			 * Filters the field data before save in Form Builder.
			 *
			 * @param {array} $submission_data Update field handler data. Already sanitized here.
			 *
			 * @return {array} Update field handler data.
			 *
			 * @since 1.3.x
			 * @hook um_admin_pre_save_fields_hook
			 *
			 * @example <caption>Change submitted value to new one by the field key.</caption>
			 * function my_custom_um_admin_pre_save_fields_hook( $submission_data ) {
			 *     $submission_data['post']['{field_key}'] = {new value};
			 *     return $submission_data;
			 * }
			 * add_filter( 'um_admin_pre_save_fields_hook', 'my_custom_um_admin_pre_save_fields_hook' );
			 */
			$array = apply_filters( 'um_admin_pre_save_fields_hook', $array );

			/**
			 * Filters the validation errors on the update field in Form Builder.
			 *
			 * @param {null|array} $errors          Errors list. It's null by default.
			 * @param {array}      $submission_data Update field handler data.
			 *
			 * @return {array} Errors list.
			 *
			 * @since 1.3.x
			 * @hook um_admin_field_update_error_handling
			 *
			 * @example <caption>Added error with Error text to the field by the field key.</caption>
			 * function my_custom_um_admin_field_update_error_handling( $errors, $submission_data ) {
			 *     $errors['{field_key}'] = {Error text};
			 *     return $errors;
			 * }
			 * add_filter( 'um_admin_field_update_error_handling', 'my_custom_um_admin_field_update_error_handling', 10, 2 );
			 */
			$output['error'] = apply_filters( 'um_admin_field_update_error_handling', $output['error'], $array );
			if ( empty( $output['error'] ) ) {
				$save              = array();
				$field_id          = $array['post']['_metakey']; // Set field ID as it's metakey.
				$save[ $field_id ] = null;
				foreach ( $array['post'] as $key => $val ) {
					if ( '' !== $val && '_' === substr( $key, 0, 1 ) ) { // field attribute
						$new_key = ltrim( $key, '_' );

						if ( 'options' === $new_key ) {
							$save[ $field_id ][ $new_key ] = preg_split( '/[\r\n]+/', $val, -1, PREG_SPLIT_NO_EMPTY );
						} else {
							$save[ $field_id ][ $new_key ] = $val;
						}
					} elseif ( false !== strpos( $key, 'um_editor' ) ) {
						if ( 'block' === $array['post']['_type'] ) {
							// the nl2br() function does not work as expected, there is an extra empty line left
							// use str_replace for correct work
							$val                          = str_replace( "\r\n\r\n", '<br>', $val );
							$save[ $field_id ]['content'] = wp_kses_post( $val );
						} else {
							$save[ $field_id ]['content'] = sanitize_textarea_field( $val );
						}
					}
				}

				/**
				 * Filters the field options before save to form on the update field in Form Builder.
				 *
				 * @param {array} $field_args Field Options.
				 *
				 * @return {array} Field Options.
				 *
				 * @since 1.3.x
				 * @hook um_admin_pre_save_field_to_form
				 *
				 * @example <caption>Force change the field's metakey when store it to DB for the form.</caption>
				 * function my_custom_um_admin_pre_save_field_to_form( $field_args ) {
				 *     $field_args['metakey'] = {new_metakey};
				 *     return $field_args;
				 * }
				 * add_filter( 'um_admin_pre_save_field_to_form', 'my_custom_um_admin_pre_save_field_to_form' );
				 */
				$field_args = apply_filters( 'um_admin_pre_save_field_to_form', $save[ $field_id ] );

				UM()->fields()->update_field( $field_id, $field_args, $array['post']['post_id'] );

				/**
				 * Filters the field options before save to DB (globally) on the update field in Form Builder.
				 *
				 * @param {array} $field_args Field Options.
				 *
				 * @return {array} Field Options.
				 *
				 * @since 1.3.x
				 * @hook um_admin_pre_save_field_to_db
				 *
				 * @example <caption>Force change the field's metakey when store it to DB globally.</caption>
				 * function my_custom_um_admin_pre_save_field_to_db( $field_args ) {
				 *     $field_args['metakey'] = {new_metakey};
				 *     return $field_args;
				 * }
				 * add_filter( 'um_admin_pre_save_field_to_db', 'my_custom_um_admin_pre_save_field_to_db' );
				 */
				$field_args = apply_filters( 'um_admin_pre_save_field_to_db', $field_args );

				if ( ! isset( $array['args']['form_only'] ) ) {
					if ( ! isset( UM()->builtin()->predefined_fields[ $field_id ] ) ) {
						UM()->fields()->globally_update_field( $field_id, $field_args );
					}
				}
			}

			wp_send_json_success( $output );
		}


/** Function ajax_scanner() called by wp_ajax hooks: {'um_secure_scan_affected_users'} **/
/** Parameters found in function ajax_scanner(): {"request": ["nonce", "last_scanned_capability", "capabilities"]} **/
function ajax_scanner() {
		if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'um-admin-nonce' ) || ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_attr__( 'Security Check', 'ultimate-member' ) );
		}

		$last_scanned_capability = sanitize_key( $_REQUEST['last_scanned_capability'] );

		if ( empty( $last_scanned_capability ) ) {
			delete_option( 'um_secure_scanned_details' );
			update_option( 'um_secure_scan_status', 'started' );
			update_option( 'um_secure_last_time_scanned', current_time( 'mysql', true ) );
		}

		$scan_details = get_option( 'um_secure_scanned_details', array() );

		if ( ! empty( $_REQUEST['capabilities'] ) ) {
			$request_capabilities = is_array( $_REQUEST['capabilities'] ) ? $_REQUEST['capabilities'] : array( $_REQUEST['capabilities'] );
			$arr_banned_caps      = array_map( 'sanitize_key', $request_capabilities );
		} else {
			$arr_banned_caps = UM()->options()->get( 'banned_capabilities' );
		}

		$proceed       = false;
		$completed     = false;
		$message       = '';
		$scanned_cap   = '';
		$user_affected = false;
		$count_users   = 0;

		$last_element = end( $arr_banned_caps );

		foreach ( $arr_banned_caps as $cap ) {

			if ( empty( $last_scanned_capability ) ) {
				$proceed = true;
			} else {
				if ( $last_scanned_capability === $cap ) { // if this was the last capability, skip this and proceed the next loop.
					$proceed = true;
					continue;
				}
			}

			if ( ! $proceed ) {
				continue;
			}

			$args          = array(
				'capability'   => $cap,
				'role__not_in' => array( 'administrator' ),
				'fields'       => 'ids',
			);
			$wp_user_query = new WP_User_Query( $args );
			$count_users   = $wp_user_query->get_total();
			if ( $count_users <= 0 ) {
				// translators: %s capability name
				$message = wp_kses( sprintf( __( '<strong>`%s`</strong> <span style="color:green">is safe.</span>', 'ultimate-member' ), $cap ), UM()->get_allowed_html( 'admin_notice' ) );
			} else {
				$user_affected = true;
				// translators: %1$s capability name, has affected %2$d user account
				$message = wp_kses( sprintf( _n( '<strong>`%1$s`</strong> <span style="color:red">has affected %2$d user account.</span>', '<strong>`%1$s`</strong> <span style="color:red">has affected %2$d user accounts.</span>', $count_users, 'ultimate-member' ), $cap, $count_users ), UM()->get_allowed_html( 'admin_notice' ) );
			}

			if ( $last_element === $cap ) {
				$completed = true;
				update_option( 'um_secure_scan_status', 'completed' );
			}

			$scanned_cap = $cap;

			break;
		}

		if ( $user_affected ) {
			$scan_details['affected_caps'][] = $scanned_cap;

			$scan_details['scanned_caps'][ $scanned_cap ] = array(
				'total_affected_users' => $count_users,
				'users'                => $wp_user_query->get_results(),
			);

			if ( ! isset( $scan_details['scanned_caps']['total_all_cap_flagged'] ) ) {
				$scan_details['scanned_caps']['total_all_cap_flagged'] = 1;
			} else {
				++$scan_details['scanned_caps']['total_all_cap_flagged'];
			}
		}

		if ( ! isset( $scan_details['scanned_caps']['total_all_affected_users'] ) ) {
			$scan_details['scanned_caps']['total_all_affected_users'] = absint( $count_users );
		} else {
			$scan_details['scanned_caps']['total_all_affected_users'] = absint( $scan_details['scanned_caps']['total_all_affected_users'] ) + absint( $count_users );
		}

		update_option( 'um_secure_scanned_details', $scan_details );

		wp_send_json_success(
			array(
				'last_scanned_capability' => $scanned_cap,
				'message'                 => $message,
				'completed'               => $completed,
				'recommendations'         => $completed ? wp_kses( $this->scan_recommendations(), UM()->get_allowed_html( 'templates' ) ) : '',
			)
		);
	}


/** Function get_users() called by wp_ajax hooks: {'um_get_users'} **/
/** Parameters found in function get_users(): {"request": ["search", "page", "avatar"]} **/
function get_users() {
		UM()->admin()->check_ajax_nonce();

		$search_request = ! empty( $_REQUEST['search'] ) ? sanitize_text_field( $_REQUEST['search'] ) : '';
		$page           = ! empty( $_REQUEST['page'] ) ? absint( $_REQUEST['page'] ) : 1;
		$per_page       = 20;

		$args = array(
			'fields' => array( 'ID', 'user_login' ),
			'paged'  => $page,
			'number' => $per_page,
		);

		if ( ! empty( $search_request ) ) {
			$args['search'] = '*' . $search_request . '*';
		}

		$args = apply_filters( 'um_get_users_list_ajax_args', $args );

		$users_query = new \WP_User_Query( $args );
		$users       = $users_query->get_results();
		$total_count = $users_query->get_total();

		if ( ! empty( $_REQUEST['avatar'] ) ) {
			foreach ( $users as $key => $user ) {
				$url                = get_avatar_url( $user->ID );
				$users[ $key ]->img = $url;
			}
		}

		wp_send_json_success(
			array(
				'users'       => $users,
				'total_count' => $total_count,
			)
		);
	}


/** Function dynamic_modal_content() called by wp_ajax hooks: {'um_dynamic_modal_content'} **/
/** Parameters found in function dynamic_modal_content(): {"post": ["act_id", "arg1", "arg2", "arg3", "form_mode", "in_row", "in_sub_row", "in_column", "in_group"]} **/
function dynamic_modal_content() {
			UM()->admin()->check_ajax_nonce();

			if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'Please login as administrator', 'ultimate-member' ) );
			}

			// phpcs:disable WordPress.Security.NonceVerification -- already verified here
			if ( empty( $_POST['act_id'] ) ) {
				wp_send_json_error( __( 'Wrong dynamic-content attribute.', 'ultimate-member' ) );
			}

			$metabox = UM()->metabox();
			$act_id  = sanitize_key( $_POST['act_id'] );

			$arg1 = null;
			if ( isset( $_POST['arg1'] ) ) {
				$arg1 = sanitize_text_field( $_POST['arg1'] );
			}

			$arg2 = null;
			if ( isset( $_POST['arg2'] ) ) {
				$arg2 = sanitize_text_field( $_POST['arg2'] );
			}

			$arg3 = null;
			if ( isset( $_POST['arg3'] ) ) {
				$arg3 = sanitize_text_field( $_POST['arg3'] );
			}

			$form_mode = null;
			if ( isset( $_POST['form_mode'] ) ) {
				$form_mode = sanitize_key( $_POST['form_mode'] );
			}

			$in_row = null;
			if ( isset( $_POST['in_row'] ) ) {
				$in_row = absint( $_POST['in_row'] );
			}

			$in_sub_row = null;
			if ( isset( $_POST['in_sub_row'] ) ) {
				$in_sub_row = absint( $_POST['in_sub_row'] );
			}

			$in_column = null;
			if ( isset( $_POST['in_column'] ) ) {
				$in_column = absint( $_POST['in_column'] );
			}

			$in_group = null;
			if ( isset( $_POST['in_group'] ) ) {
				$in_group = absint( $_POST['in_group'] );
			}
			// phpcs:enable WordPress.Security.NonceVerification -- already verified here

			switch ( $act_id ) {
				default:
					ob_start();
					/**
					 * Fires for integration on AJAX popup admin builder modal content.
					 *
					 * @since 1.3.x
					 * @hook um_admin_ajax_modal_content__hook
					 *
					 * @param {string} $act_id `data-dynamic-content` attribute value. Modal action.
					 *
					 * @example <caption>Pass HTML to the custom UM modal with data-dynamic-content="user_info".</caption>
					 * function my_custom_um_admin_ajax_modal_content__hook( $act_id ) {
					 *     if ( 'user_info' === $act_id ) {
					 *         // Your HTML is here
					 *     }
					 * }
					 * add_action( 'um_admin_ajax_modal_content__hook', 'my_custom_um_admin_ajax_modal_content__hook' );
					 */
					do_action( 'um_admin_ajax_modal_content__hook', $act_id );
					/**
					 * Fires for integration on AJAX popup admin builder modal content.
					 *
					 * Note: $act_id `data-dynamic-content` attribute value. Modal action.
					 *
					 * @since 1.3.x
					 * @hook um_admin_ajax_modal_content__hook_{$act_id}
					 * @deprecated Partially deprecated since 2.6.4. Use common 'um_admin_ajax_modal_content__hook' and pass `$act_id` as callback attribute.
					 * @todo Fully deprecate since 2.7.0
					 *
					 * @example <caption>Pass HTML to the custom UM modal with data-dynamic-content="user_info".</caption>
					 * function my_custom_um_admin_ajax_modal_content__hook_user_info() {
					 *     // Your HTML is here for `user_info` modal
					 * }
					 * add_action( 'um_admin_ajax_modal_content__hook_user_info', 'my_custom_um_admin_ajax_modal_content__hook_user_info' );
					 */
					do_action( 'um_admin_ajax_modal_content__hook_' . $act_id );
					$output = ob_get_clean();
					break;
				case 'um_admin_fonticon_selector':
					ob_start();
					?>
					<div class="um-admin-metabox">
						<p class="_icon_search">
							<label class="screen-reader-text" for="_icon_search"><?php esc_html_e( 'Search Icons...', 'ultimate-member' ); ?></label>
							<input type="text" name="_icon_search" id="_icon_search" value="" placeholder="<?php esc_attr_e( 'Search Icons...', 'ultimate-member' ); ?>" />
						</p>
					</div>
					<div class="um-admin-icons">
						<?php foreach ( UM()->fonticons()->all as $icon ) { ?>
							<span data-code="<?php echo esc_attr( $icon ); ?>" title="<?php echo esc_attr( $icon ); ?>" class="um-tip-n"><i class="<?php echo esc_attr( $icon ); ?>"></i></span>
						<?php } ?>
					</div>
					<div class="clear"></div>
					<?php
					$output = ob_get_clean();
					break;
				case 'um_admin_show_fields':
					// $arg2 means `form_id` variable in this case.
					ob_start();
					$form_fields = UM()->query()->get_attr( 'custom_fields', $arg2 );
					$form_fields = array_values( array_filter( array_keys( $form_fields ) ) );
					?>
					<h4><?php esc_html_e( 'Setup New Field', 'ultimate-member' ); ?></h4>
					<div class="um-admin-btns">
						<?php
						if ( UM()->builtin()->core_fields ) {
							foreach ( UM()->builtin()->core_fields as $field_type => $field_data ) {
								if ( isset( $field_data['in_fields'] ) && false === $field_data['in_fields'] ) {
									continue;
								}
								?>
								<a href="javascript:void(0);" class="button" data-modal="UM_add_field" data-modal-size="normal" data-dynamic-content="um_admin_new_field_popup" data-arg1="<?php echo esc_attr( $field_type ); ?>" data-arg2="<?php echo esc_attr( $arg2 ); ?>"><?php echo esc_html( $field_data['name'] ); ?></a>
								<?php
							}
						}
						?>
					</div>
					<h4><?php esc_html_e( 'Predefined Fields', 'ultimate-member' ); ?></h4>
					<div class="um-admin-btns">
						<?php
						if ( UM()->builtin()->predefined_fields ) {
							foreach ( UM()->builtin()->predefined_fields as $field_key => $field_data ) {
								if ( empty( $field_data ) || ! is_array( $field_data ) ) {
									continue; // Avoid wrong format of the registered predefined fields.
								}
								if ( array_key_exists( 'account_only', $field_data ) && true === $field_data['account_only'] ) {
									continue;
								}
								if ( array_key_exists( 'private_use', $field_data ) && true === $field_data['private_use'] ) {
									continue;
								}
								?>
								<a href="javascript:void(0);" class="button" <?php disabled( in_array( $field_key, $form_fields, true ) ); ?> data-silent_action="um_admin_add_field_from_predefined" data-arg1="<?php echo esc_attr( $field_key ); ?>" data-arg2="<?php echo esc_attr( $arg2 ); ?>" title="<?php echo esc_attr( $field_data['title'] ); ?>"><?php echo esc_html( um_trim_string( $field_data['title'] ) ); ?></a>
								<?php
							}
						} else {
							?>
							<p><?php esc_html_e( 'None', 'ultimate-member' ); ?></p>
							<?php
						}
						?>
					</div>
					<h4><?php esc_html_e( 'Custom Fields', 'ultimate-member' ); ?></h4>
					<div class="um-admin-btns">
						<?php
						if ( UM()->builtin()->custom_fields ) {
							foreach ( UM()->builtin()->custom_fields as $field_key => $array ) {
								if ( empty( $array['title'] ) || empty( $array['type'] ) ) {
									continue;
								}
								?>
								<?php // translators: %s is a field metakey. ?>
								<a href="javascript:void(0);" class="button with-icon" <?php disabled( in_array( $field_key, $form_fields, true ) ) ?> data-silent_action="um_admin_add_field_from_list" data-arg1="<?php echo esc_attr( $field_key ); ?>" data-arg2="<?php echo esc_attr( $arg2 ); ?>" title="<?php echo esc_attr( sprintf( __( 'Meta Key - %s', 'ultimate-member' ), $field_key ) ); ?>">
									<?php echo esc_html( um_trim_string( stripslashes( $array['title'] ) ) ); ?> (<?php echo esc_html( ucfirst( $array['type'] ) ); ?>)
									<span class="remove dashicons dashicons-dismiss"></span>
								</a>

								<?php
							}
						}
						?>

						<p class="um-no-custom-fields"<?php if ( UM()->builtin()->custom_fields ) { ?> style="display: none;"<?php } ?>>
							<?php esc_html_e( 'You did not create any custom fields.', 'ultimate-member' ); ?>
						</p>
					</div>
					<?php
					$output = ob_get_clean();
					break;
				case 'um_admin_edit_field_popup':
					// $arg1 means `field_type` variable in this case.
					// $arg2 means `form_id` variable in this case.
					// $arg3 means `field_metakey` variable in this case.
					$field_type_data = UM()->builtin()->get_core_field_attrs( $arg1 );
					$form_fields     = UM()->query()->get_attr( 'custom_fields', $arg2 );

					if ( ! array_key_exists( $arg3, $form_fields ) ) {
						$output = '<p>' . esc_html__( 'This field is not setup correctly for this form.', 'ultimate-member' ) . '</p>';
						break;
					}

					$metabox->set_field_type = $arg1;
					$metabox->in_edit        = true;
					$metabox->edit_array     = $form_fields[ $arg3 ];

					if ( ! array_key_exists( 'metakey', $metabox->edit_array ) ) {
						$metabox->edit_array['metakey'] = $metabox->edit_array['id'];
					}

					if ( ! array_key_exists( 'position', $metabox->edit_array ) ) {
						$metabox->edit_array['position'] = $metabox->edit_array['id'];
					}

					ob_start();

					if ( ! array_key_exists( 'col1', $field_type_data ) ) {
						?>
						<p><?php esc_html_e( 'This field type is not setup correctly.', 'ultimate-member' ); ?></p>
						<?php
					} else {
						if ( 'row' !== $arg1 ) {
							?>
							<input type="hidden" name="_in_row" id="_in_row" value="<?php echo esc_attr( $metabox->edit_array['in_row'] ); ?>" />
							<input type="hidden" name="_in_sub_row" id="_in_sub_row" value="<?php echo esc_attr( $metabox->edit_array['in_sub_row'] ); ?>" />
							<input type="hidden" name="_in_column" id="_in_column" value="<?php echo esc_attr( $metabox->edit_array['in_column'] ); ?>" />
							<input type="hidden" name="_in_group" id="_in_group" value="<?php echo esc_attr( $metabox->edit_array['in_group'] ); ?>" />
						<?php } ?>
						<input type="hidden" name="_type" id="_type" value="<?php echo esc_attr( $arg1 ); ?>" />
						<input type="hidden" name="post_id" id="post_id" value="<?php echo esc_attr( $arg2 ); ?>" />
						<input type="hidden" name="edit_mode" id="edit_mode" value="true" />
						<input type="hidden" name="_metakey" id="_metakey" value="<?php echo esc_attr( $metabox->edit_array['metakey'] ); ?>" />
						<input type="hidden" name="_position" id="_position" value="<?php echo esc_attr( $metabox->edit_array['position'] ); ?>" />

						<?php if ( array_key_exists( 'mce_content', $field_type_data ) && true === $field_type_data['mce_content'] ) { ?>
							<div class="dynamic-mce-content"><?php echo ! empty( $metabox->edit_array['content'] ) ? wp_kses( $metabox->edit_array['content'], UM()->get_allowed_html( 'templates' ) ) : ''; ?></div>
						<?php } ?>

						<?php $this->modal_header(); ?>

						<div class="um-admin-half">
							<?php
							if ( is_array( $field_type_data['col1'] ) ) {
								foreach ( $field_type_data['col1'] as $opt ) {
									$metabox->field_input( $opt, $arg2, $metabox->edit_array );
								}
							}
							?>
						</div>
						<div class="um-admin-half um-admin-right">
							<?php
							if ( array_key_exists( 'col2', $field_type_data ) && is_array( $field_type_data['col2'] ) ) {
								foreach ( $field_type_data['col2'] as $opt ) {
									$metabox->field_input( $opt, $arg2, $metabox->edit_array );
								}
							}
							?>
						</div>
						<div class="clear"></div>
						<?php
						if ( array_key_exists( 'col3', $field_type_data ) && is_array( $field_type_data['col3'] ) ) {
							foreach ( $field_type_data['col3'] as $opt ) {
								$metabox->field_input( $opt, $arg2, $metabox->edit_array );
							}
						}
						?>
						<div class="clear"></div>
						<?php
						if ( array_key_exists( 'col_full', $field_type_data ) && is_array( $field_type_data['col_full'] ) ) {
							foreach ( $field_type_data['col_full'] as $opt ) {
								$metabox->field_input( $opt, $arg2, $metabox->edit_array );
							}
						}
						$this->modal_footer( $arg2, $field_type_data, $metabox );
					}
					$output = ob_get_clean();
					break;
				case 'um_admin_new_field_popup':
					// $arg1 means `field_type` variable in this case.
					// $arg2 means `form_id` variable in this case.
					$field_type_data         = UM()->builtin()->get_core_field_attrs( $arg1 );
					$metabox->set_field_type = $arg1;

					ob_start();

					if ( ! array_key_exists( 'col1', $field_type_data ) ) {
						?>
						<p><?php esc_html_e( 'This field type is not setup correctly.', 'ultimate-member' ); ?></p>
						<?php
					} else {
						?>
						<input type="hidden" name="_in_row" id="_in_row" value="_um_row_<?php echo esc_attr( $in_row + 1 ); ?>" />
						<input type="hidden" name="_in_sub_row" id="_in_sub_row" value="<?php echo esc_attr( $in_sub_row ); ?>" />
						<input type="hidden" name="_in_column" id="_in_column" value="<?php echo esc_attr( $in_column ); ?>" />
						<input type="hidden" name="_in_group" id="_in_group" value="<?php echo esc_attr( $in_group ); ?>" />
						<input type="hidden" name="_type" id="_type" value="<?php echo esc_attr( $arg1 ); ?>" />
						<input type="hidden" name="post_id" id="post_id" value="<?php echo esc_attr( $arg2 ); ?>" />

						<?php $this->modal_header(); ?>

						<div class="um-admin-half">
							<?php
							if ( is_array( $field_type_data['col1'] ) ) {
								foreach ( $field_type_data['col1'] as $opt ) {
									$metabox->field_input( $opt, $arg2 );
								}
							}
							?>
						</div>
						<div class="um-admin-half um-admin-right">
							<?php
							if ( array_key_exists( 'col2', $field_type_data ) && is_array( $field_type_data['col2'] ) ) {
								foreach ( $field_type_data['col2'] as $opt ) {
									$metabox->field_input( $opt, $arg2 );
								}
							}
							?>
						</div>
						<div class="clear"></div>
						<?php
						if ( array_key_exists( 'col3', $field_type_data ) && is_array( $field_type_data['col3'] ) ) {
							foreach ( $field_type_data['col3'] as $opt ) {
								$metabox->field_input( $opt, $arg2 );
							}
						}
						?>
						<div class="clear"></div>
						<?php
						if ( array_key_exists( 'col_full', $field_type_data ) && is_array( $field_type_data['col_full'] ) ) {
							foreach ( $field_type_data['col_full'] as $opt ) {
								$metabox->field_input( $opt, $arg2 );
							}
						}

						$this->modal_footer( $arg2, $field_type_data, $metabox );
					}
					$output = ob_get_clean();
					break;
				case 'um_admin_preview_form':
					// $arg1 means `form_id` variable in this case.
					UM()->user()->preview = true;

					$mode = UM()->query()->get_attr( 'mode', $arg1 );
					if ( empty( $mode ) ) {
						$mode = $form_mode;
					}
					if ( 'profile' === $mode ) {
						UM()->fields()->editing = true;
					}

					$output  = '<div class="um-admin-preview-overlay"></div>';
					$output .= apply_shortcodes( '[ultimatemember form_id="' . $arg1 . '" /]' );
					break;
				case 'um_admin_review_registration':
					// $arg1 means `user_id` variable in this case.
					if ( ! current_user_can( 'administrator' ) && ! um_can_view_profile( $arg1 ) ) {
						$output = '';
						break;
					}
					um_fetch_user( $arg1 );
					UM()->user()->preview = true;
					$output               = um_user_submitted_registration_formatted( true );
					um_reset_user();
					break;
			}

			// @todo WPCS through wp_kses.
			echo $output;
			die;
		}


/** Function ajax_get_packages() called by wp_ajax hooks: {'um_get_packages'} **/
/** No params detected :-/ **/


/** Function load_posts() called by wp_ajax hooks: {'um_ajax_paginate_posts', 'nopriv_um_ajax_paginate_posts'} **/
/** Parameters found in function load_posts(): {"post": ["author", "page"]} **/
function load_posts() {
			UM()->check_ajax_nonce();

			if ( UM()->is_rate_limited( 'paginate_posts' ) ) {
				wp_send_json_error( __( 'Too many requests', 'ultimate-member' ) );
			}

			$author = ! empty( $_POST['author'] ) ? absint( $_POST['author'] ) : get_current_user_id();
			$page = ! empty( $_POST['page'] ) ? absint( $_POST['page'] ) : 0;

			$args = array(
				'post_type'        => 'post',
				'posts_per_page'   => 10,
				'offset'           => ( $page - 1 ) * 10,
				'author'           => $author,
				'post_status'      => array( 'publish' ),
				'um_main_query'    => true,
				'suppress_filters' => false,
			);

			/**
			 * UM hook
			 *
			 * @type filter
			 * @title um_profile_query_make_posts
			 * @description Some changes of WP_Query Posts Tab
			 * @input_vars
			 * [{"var":"$query_posts","type":"WP_Query","desc":"UM Posts Tab query"}]
			 * @change_log
			 * ["Since: 2.0"]
			 * @usage
			 * <?php add_filter( 'um_profile_query_make_posts', 'function_name', 10, 1 ); ?>
			 * @example
			 * <?php
			 * add_filter( 'um_profile_query_make_posts', 'my_profile_query_make_posts', 10, 1 );
			 * function my_profile_query_make_posts( $query_posts ) {
			 *     // your code here
			 *     return $query_posts;
			 * }
			 * ?>
			 */
			$args = apply_filters( 'um_profile_query_make_posts', $args );
			$posts = get_posts( $args );

			UM()->get_template( 'profile/posts.php', '', array( 'posts' => $posts ), true );
			wp_die();
		}


/** Function ajax_muted_action() called by wp_ajax hooks: {'um_muted_action'} **/
/** Parameters found in function ajax_muted_action(): {"request": ["hook", "user_id"]} **/
function ajax_muted_action() {
			UM()->check_ajax_nonce();

			// phpcs:disable WordPress.Security.NonceVerification
			if ( ! isset( $_REQUEST['hook'] ) ) {
				die( esc_html__( 'Invalid hook', 'ultimate-member' ) );
			}

			if ( isset( $_REQUEST['user_id'] ) ) {
				$user_id = absint( $_REQUEST['user_id'] );
			}
			if ( ! isset( $user_id ) || ! UM()->roles()->um_current_user_can( 'edit', $user_id ) ) {
				die( esc_html__( 'You can not edit this user.', 'ultimate-member' ) );
			}

			$hook = sanitize_key( $_REQUEST['hook'] );
			/**
			 * Fires on AJAX muted action.
			 *
			 * @since 1.3.x
			 * @hook  um_run_ajax_function__{$hook}
			 *
			 * @param {array} $request Request.
			 *
			 * @example <caption>Make any custom action on AJAX muted action.</caption>
			 * function my_run_ajax_function( $request ) {
			 *     // your code here
			 * }
			 * add_action( 'um_run_ajax_function__{$hook}', 'my_run_ajax_function', 10, 1 );
			 */
			do_action( "um_run_ajax_function__{$hook}", $_REQUEST );
			// phpcs:enable WordPress.Security.NonceVerification
		}


/** Function ajax_resize_image() called by wp_ajax hooks: {'um_resize_image', 'nopriv_um_resize_image'} **/
/** Parameters found in function ajax_resize_image(): {"request": ["src", "coord", "key", "user_id"], "post": ["set_id", "set_mode"]} **/
function ajax_resize_image() {
			UM()->check_ajax_nonce();

			if ( UM()->is_rate_limited( 'resize_image' ) ) {
				wp_send_json_error( __( 'Too many requests', 'ultimate-member' ) );
			}

			// phpcs:disable WordPress.Security.NonceVerification -- verified by the `check_ajax_nonce()`
			if ( ! isset( $_REQUEST['src'], $_REQUEST['coord'], $_REQUEST['key'] ) ) {
				wp_send_json_error( esc_js( __( 'Invalid parameters', 'ultimate-member' ) ) );
			}

			$coord_n = substr_count( $_REQUEST['coord'], ',' );
			if ( 3 !== $coord_n ) {
				wp_send_json_error( esc_js( __( 'Invalid coordinates', 'ultimate-member' ) ) );
			}

			$user_id = empty( $_REQUEST['user_id'] ) ? null : absint( $_REQUEST['user_id'] );
			if ( $user_id && is_user_logged_in() && ! UM()->roles()->um_current_user_can( 'edit', $user_id ) ) {
				wp_send_json_error( esc_js( __( 'You have no permission to edit this user', 'ultimate-member' ) ) );
			}

			if ( $user_id && ! is_user_logged_in() ) {
				wp_send_json_error( esc_js( __( 'Please login to edit this user', 'ultimate-member' ) ) );
			}

			$form_id = isset( $_POST['set_id'] ) ? absint( $_POST['set_id'] ) : null;
			$mode    = isset( $_POST['set_mode'] ) ? sanitize_text_field( $_POST['set_mode'] ) : null;

			UM()->fields()->set_id   = $form_id;
			UM()->fields()->set_mode = $mode;

			if ( ! is_user_logged_in() && 'profile' === $mode ) {
				wp_send_json_error( esc_js( __( 'You have no permission to edit user profile', 'ultimate-member' ) ) );
			}

			if ( null !== $user_id && 'register' === $mode ) {
				wp_send_json_error( esc_js( __( 'User has to be empty on registration', 'ultimate-member' ) ) );
			}

			$form_post = get_post( $form_id );
			// Invalid post ID. Maybe post doesn't exist.
			if ( empty( $form_post ) ) {
				wp_send_json_error( esc_js( __( 'Invalid form ID', 'ultimate-member' ) ) );
			}

			if ( 'um_form' !== $form_post->post_type ) {
				wp_send_json_error( esc_js( __( 'Invalid form post type', 'ultimate-member' ) ) );
			}

			$form_status = get_post_status( $form_id );
			if ( 'publish' !== $form_status ) {
				wp_send_json_error( esc_js( __( 'Invalid form status', 'ultimate-member' ) ) );
			}

			$post_data = UM()->query()->post_data( $form_id );
			if ( ! array_key_exists( 'mode', $post_data ) || $mode !== $post_data['mode'] ) {
				wp_send_json_error( esc_js( __( 'Invalid form type', 'ultimate-member' ) ) );
			}

			// For profiles only.
			if ( 'profile' === $mode && ! empty( $post_data['use_custom_settings'] ) && ! empty( $post_data['role'] ) ) {
				// Option "Apply custom settings to this form". Option "Make this profile form role-specific".
				// Show the first Profile Form with role selected, don't show profile forms below the page with other role-specific setting.
				$current_user_roles = UM()->roles()->get_all_user_roles( $user_id );
				if ( empty( $current_user_roles ) ) {
					wp_send_json_error( esc_js( __( 'You have no permission to edit this user through this form', 'ultimate-member' ) ) );
				}

				$post_data['role'] = maybe_unserialize( $post_data['role'] );

				if ( is_array( $post_data['role'] ) ) {
					if ( ! count( array_intersect( $post_data['role'], $current_user_roles ) ) ) {
						wp_send_json_error( esc_js( __( 'You have no permission to edit this user through this form', 'ultimate-member' ) ) );
					}
				} elseif ( ! in_array( $post_data['role'], $current_user_roles, true ) ) {
					wp_send_json_error( esc_js( __( 'You have no permission to edit this user through this form', 'ultimate-member' ) ) );
				}
			}

			$key = sanitize_text_field( $_REQUEST['key'] );

			if ( ! array_key_exists( 'custom_fields', $post_data ) || empty( $post_data['custom_fields'] ) ) {
				wp_send_json_error( esc_js( __( 'Invalid form fields', 'ultimate-member' ) ) );
			}

			$custom_fields = maybe_unserialize( $post_data['custom_fields'] );
			if ( ! is_array( $custom_fields ) || ! array_key_exists( $key, $custom_fields ) ) {
				if ( ! ( 'profile' === $mode && in_array( $key, array( 'cover_photo', 'profile_photo' ), true ) ) ) {
					wp_send_json_error( esc_js( __( 'Invalid field metakey', 'ultimate-member' ) ) );
				}
			}

			if ( empty( $custom_fields[ $key ]['crop'] ) && ! in_array( $key, array( 'cover_photo', 'profile_photo' ), true ) ) {
				wp_send_json_error( esc_js( __( 'This field doesn\'t support image crop', 'ultimate-member' ) ) );
			}

			if ( 'profile' === $mode ) {
				if ( in_array( $key, array( 'cover_photo', 'profile_photo' ), true ) ) {
					if ( 'profile_photo' === $key ) {
						$disable_photo_uploader = empty( $post_data['use_custom_settings'] ) ? UM()->options()->get( 'disable_profile_photo_upload' ) : $post_data['disable_photo_upload'];
						if ( $disable_photo_uploader ) {
							wp_send_json_error( esc_js( __( 'You have no permission to edit this field', 'ultimate-member' ) ) );
						}
					} else {
						$cover_enabled_uploader = empty( $post_data['use_custom_settings'] ) ? UM()->options()->get( 'profile_cover_enabled' ) : $post_data['cover_enabled'];
						if ( ! $cover_enabled_uploader ) {
							wp_send_json_error( esc_js( __( 'You have no permission to edit this field', 'ultimate-member' ) ) );
						}
					}
				} elseif ( ! um_can_edit_field( $custom_fields[ $key ] ) ) {
					wp_send_json_error( esc_js( __( 'You have no permission to edit this field', 'ultimate-member' ) ) );
				}
			}

			$src        = esc_url_raw( $_REQUEST['src'] );
			$image_path = um_is_file_owner( $src, $user_id, true );
			if ( ! $image_path ) {
				wp_send_json_error( esc_js( __( 'Invalid file ownership', 'ultimate-member' ) ) );
			}

			$coord = sanitize_text_field( $_REQUEST['coord'] );

			UM()->uploader()->replace_upload_dir = true;

			$output = UM()->uploader()->resize_image( $image_path, $src, $key, $user_id, $coord );

			UM()->uploader()->replace_upload_dir = false;

			delete_option( "um_cache_userdata_{$user_id}" );
			// phpcs:enable WordPress.Security.NonceVerification -- verified by the `check_ajax_nonce()`
			wp_send_json_success( $output );
		}


/** Function default_filter_settings() called by wp_ajax hooks: {'um_member_directory_default_filter_settings'} **/
/** Parameters found in function default_filter_settings(): {"request": ["key", "directory_id"]} **/
function default_filter_settings() {
			UM()->admin()->check_ajax_nonce();

			// we can't use function "sanitize_key" because it changes uppercase to lowercase
			$filter_key = sanitize_text_field( $_REQUEST['key'] );
			$directory_id = absint( $_REQUEST['directory_id'] );

			$html = $this->show_filter( $filter_key, array( 'form_id' => $directory_id ), false, true );

			wp_send_json_success( array( 'field_html' => $html ) );
		}


/** Function get_pages_list() called by wp_ajax hooks: {'um_get_pages_list'} **/
/** Parameters found in function get_pages_list(): {"get": ["page", "search", "field_id"]} **/
function get_pages_list() {
		check_ajax_referer( 'um-admin-nonce', 'nonce' );

		// we will pass post IDs and titles to this array
		$return = array();

		$pre_result = apply_filters( 'um_admin_settings_get_pages_list', false );

		if ( false === $pre_result ) {
			$query_args = array(
				'post_type'           => 'page',
				'post_status'         => 'publish', // if you don't want drafts to be returned
				'ignore_sticky_posts' => 1,
				'posts_per_page'      => 10, // how much to show at once
				'paged'               => ! empty( $_GET['page'] ) ? absint( $_GET['page'] ) : 1,
				'orderby'             => 'title',
				'order'               => 'asc',
			);

			if ( ! empty( $_GET['search'] ) ) {
				$query_args['s'] = sanitize_text_field( $_GET['search'] ); // the search query
			}

			$field_id = ! empty( $_GET['field_id'] ) ? sanitize_text_field( $_GET['field_id'] ) : null;
			if ( 'form__um_register_use_gdpr_content_id' === $field_id ) {
				$predefined_ids   = array();
				$predefined_pages = array_keys( UM()->config()->get( 'predefined_pages' ) );
				foreach ( $predefined_pages as $slug ) {
					$p_id = um_get_predefined_page_id( $slug );
					if ( empty( $p_id ) ) {
						continue;
					}
					$predefined_ids[] = $p_id;
				}
				$predefined_ids = array_unique( $predefined_ids );
				if ( ! empty( $predefined_ids ) ) {
					$query_args['post__not_in'] = $predefined_ids;
				}
			}

			/**
			 * Filters WP_Query arguments for getting pages visible in the dropdown fields in UM Settings.
			 *
			 * @since 2.10.6
			 * @hook  um_admin_settings_get_pages_list_args
			 *
			 * @param {array}  $query_args Get pages WP_Query arguments.
			 * @param {string} $field_id   Dropdown field ID.
			 *
			 * @return {array} Get pages WP_Query arguments.
			 */
			$query_args = apply_filters( 'um_admin_settings_get_pages_list_args', $query_args, $field_id );

			$search_results = new WP_Query( $query_args );

			if ( $search_results->have_posts() ) {
				while ( $search_results->have_posts() ) {
					$search_results->the_post();

					// shorten the title a little
					$title    = ( mb_strlen( $search_results->post->post_title ) > 50 ) ? mb_substr( $search_results->post->post_title, 0, 49 ) . '...' : $search_results->post->post_title;
					$title    = sprintf( __( '%s (ID: %s)', 'ultimate-member' ), $title, $search_results->post->ID );
					$return[] = array( $search_results->post->ID, $title ); // array( Post ID, Post Title )
				}
			}

			$return['total_count'] = $search_results->found_posts;
		} else {
			// got already calculated posts array from 3rd-party integrations (e.g. WPML, Polylang)
			$return = $pre_result;
		}

		wp_send_json( $return );
	}


/** Function search_widget_request() called by wp_ajax hooks: {'um_search_widget_request', 'nopriv_um_search_widget_request'} **/
/** Parameters found in function search_widget_request(): {"post": ["search"]} **/
function search_widget_request() {
		check_ajax_referer( 'um_search_widget_request' );

		if ( ! UM()->options()->get( 'members_page' ) ) {
			wp_send_json_error( __( 'No members page enabled', 'ultimate-member' ) );
		}

		$member_directory_ids = array();

		$page_id = UM()->config()->permalinks['members'];
		if ( ! empty( $page_id ) ) {
			$member_directory_ids = UM()->member_directory()->get_member_directory_id( $page_id );
		}

		if ( empty( $member_directory_ids ) ) {
			wp_send_json_error( __( 'No members page enabled', 'ultimate-member' ) );
		}

		$url = um_get_predefined_page_url( 'members' );

		$search = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
		if ( empty( $search ) ) {
			wp_send_json_success( array( 'url' => $url ) );
		}

		// Current user priority role
		$priority_user_role = false;
		if ( is_user_logged_in() ) {
			$priority_user_role = UM()->roles()->get_priority_user_role( get_current_user_id() );
		}

		foreach ( $member_directory_ids as $directory_id ) {
			$directory_data = UM()->query()->post_data( $directory_id );

			if ( isset( $directory_data['roles_can_search'] ) ) {
				$directory_data['roles_can_search'] = maybe_unserialize( $directory_data['roles_can_search'] );
			}

			$show_search = empty( $directory_data['roles_can_search'] ) || ( ! empty( $priority_user_role ) && in_array( $priority_user_role, $directory_data['roles_can_search'], true ) );
			if ( empty( $directory_data['search'] ) || ! $show_search ) {
				continue;
			}

			$hash = UM()->member_directory()->get_directory_hash( $directory_id );

			$url = add_query_arg( array( 'search_' . $hash => $search ), $url );
		}

		wp_send_json_success( array( 'url' => $url ) );
	}


/** Function ajax_paginate() called by wp_ajax hooks: {'um_ajax_paginate'} **/
/** Parameters found in function ajax_paginate(): {"request": ["hook", "args"]} **/
function ajax_paginate() {
			UM()->check_ajax_nonce();

			// phpcs:disable WordPress.Security.NonceVerification
			if ( ! isset( $_REQUEST['hook'] ) ) {
				wp_send_json_error( __( 'Invalid hook.', 'ultimate-member' ) );
			}
			$hook = sanitize_key( $_REQUEST['hook'] );

			$args = ! empty( $_REQUEST['args'] ) ? $_REQUEST['args'] : array();
			// phpcs:enable WordPress.Security.NonceVerification

			ob_start();

			/**
			 * Fires on posts loading by AJAX in User Profile tabs.
			 *
			 * @since 1.3.x
			 * @hook  um_ajax_load_posts__{$hook}
			 *
			 * @param {array} $args Request.
			 *
			 * @example <caption>Make any custom action on when posts loading by AJAX in User Profile.</caption>
			 * function my_ajax_load_posts( $args ) {
			 *     // your code here
			 * }
			 * add_action( 'um_ajax_load_posts__{$hook}', 'my_ajax_load_posts', 10, 1 );
			 */
			do_action( "um_ajax_load_posts__{$hook}", $args );

			$output = ob_get_clean();
			// @todo: investigate using WP_KSES
			die( $output );
		}


/** Function load_comments() called by wp_ajax hooks: {'um_ajax_paginate_comments', 'nopriv_um_ajax_paginate_comments'} **/
/** Parameters found in function load_comments(): {"post": ["user_id", "page"]} **/
function load_comments() {
			UM()->check_ajax_nonce();

			if ( UM()->is_rate_limited( 'paginate_comments' ) ) {
				wp_send_json_error( __( 'Too many requests', 'ultimate-member' ) );
			}

			$user_id = ! empty( $_POST['user_id'] ) ? absint( $_POST['user_id'] ) : get_current_user_id();
			$page = ! empty( $_POST['page'] ) ? absint( $_POST['page'] ) : 0;

			$comments = get_comments( array(
				'number'        => 10,
				'offset'        => ( $page - 1 ) * 10,
				'user_id'       => $user_id,
				'post_status'   => array('publish'),
				'type__not_in'  => apply_filters( 'um_excluded_comment_types', array('') ),
			) );

			UM()->get_template( 'profile/comments.php', '', array( 'comments' => $comments ), true );
			wp_die();
		}


/** Function populate_dropdown_options() called by wp_ajax hooks: {'um_populate_dropdown_options'} **/
/** Parameters found in function populate_dropdown_options(): {"post": ["um_option_callback"]} **/
function populate_dropdown_options() {
			UM()->admin()->check_ajax_nonce();

			if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'This is not possible for security reasons.', 'ultimate-member' ) );
			}

			$arr_options = array();

			// we can not use `sanitize_key()` because it removes backslash needed for namespace and uppercase symbols
			$um_callback_func = sanitize_text_field( $_POST['um_option_callback'] );
			// removed added by sanitize slashes for the namespaces
			$um_callback_func = wp_unslash( $um_callback_func );

			if ( empty( $um_callback_func ) ) {
				$arr_options['status'] = 'empty';
				$arr_options['function_name'] = $um_callback_func;
				$arr_options['function_exists'] = function_exists( $um_callback_func );
			}

			if ( UM()->fields()->is_source_blacklisted( $um_callback_func ) ) {
				wp_send_json_error( __( 'This is not possible for security reasons. Don\'t use internal PHP functions.', 'ultimate-member' ) );
			}

			$arr_options['data'] = array();
			if ( function_exists( $um_callback_func ) ) {
				$arr_options['data'] = call_user_func( $um_callback_func );
			}

			wp_send_json( $arr_options );
		}


/** Function ajax_image_upload() called by wp_ajax hooks: {'nopriv_um_imageupload', 'um_imageupload'} **/
/** Parameters found in function ajax_image_upload(): {"post": ["key", "user_id", "timestamp", "_wpnonce", "set_id", "set_mode"]} **/
function ajax_image_upload() {
			if ( UM()->is_rate_limited( 'upload_image' ) ) {
				wp_send_json_error( __( 'Too many requests', 'ultimate-member' ) );
			}

			$ret['error'] = null;
			$ret          = array();

			if ( empty( $_POST['key'] ) ) {
				$ret['error'] = esc_html__( 'Invalid image key', 'ultimate-member' );
				wp_send_json_error( $ret );
			}

			$id      = sanitize_text_field( $_POST['key'] );
			$user_id = empty( $_POST['user_id'] ) ? null : absint( $_POST['user_id'] );

			/**
			 * Filters the custom validation marker for 3rd-party uploader.
			 *
			 * @param {bool}   $custom_validation Custom validation marker. Is null by default. Keep null for UM core validation.
			 * @param {string} $id                Uploader field key.
			 * @param {int}    $user_id           User ID.
			 *
			 * @return {bool} Custom validation marker.
			 *
			 * @since 2.9.1
			 * @hook um_image_upload_validation
			 *
			 * @example <caption>Custom validation.</caption>
			 * function my_um_image_upload_validation( $custom_validation, $id, $user_id ) {
			 *     // your code here
			 *     $ret['error'] = esc_html__( 'Error code', 'ultimate-member' );
			 *     wp_send_json_error( $ret );
			 *     return true;
			 * }
			 * add_filter( 'um_image_upload_validation', 'my_um_image_upload_validation', 10, 3 );
			 */
			$custom_validation = apply_filters( 'um_image_upload_validation', null, $id, $user_id );
			if ( is_null( $custom_validation ) ) {
				/**
				 * Filters image upload checking nonce.
				 *
				 * @param {bool} $verify_nonce Verify nonce marker. Default true.
				 *
				 * @return {bool} Verify nonce marker.
				 *
				 * @since 1.3.x
				 * @hook um_image_upload_nonce
				 *
				 * @example <caption>Disable checking nonce on image upload.</caption>
				 * function my_image_upload_nonce( $verify_nonce ) {
				 *     // your code here
				 *     $verify_nonce = false;
				 *     return $verify_nonce;
				 * }
				 * add_filter( 'um_image_upload_nonce', 'my_image_upload_nonce' );
				 */
				$um_image_upload_nonce = apply_filters( 'um_image_upload_nonce', true );
				if ( $um_image_upload_nonce ) {
					$timestamp = absint( $_POST['timestamp'] );
					$nonce     = sanitize_text_field( $_POST['_wpnonce'] );
					if ( ! wp_verify_nonce( $nonce, "um_upload_nonce-{$timestamp}" ) && is_user_logged_in() ) {
						// This nonce is not valid.
						$ret['error'] = esc_html__( 'Invalid nonce', 'ultimate-member' );
						wp_send_json_error( $ret );
					}
				}

				if ( $user_id && is_user_logged_in() && ! UM()->roles()->um_current_user_can( 'edit', $user_id ) ) {
					$ret['error'] = esc_html__( 'You have no permission to edit this user', 'ultimate-member' );
					wp_send_json_error( $ret );
				}

				if ( $user_id && ! is_user_logged_in() ) {
					$ret['error'] = esc_html__( 'Please login to edit this user', 'ultimate-member' );
					wp_send_json_error( $ret );
				}

				$form_id = absint( $_POST['set_id'] );
				$mode    = sanitize_key( $_POST['set_mode'] );

				UM()->fields()->set_id   = $form_id;
				UM()->fields()->set_mode = $mode;

				if ( ! is_user_logged_in() && 'profile' === $mode ) {
					$ret['error'] = esc_html__( 'You have no permission to edit user profile', 'ultimate-member' );
					wp_send_json_error( $ret );
				}

				if ( null !== $user_id && 'register' === $mode ) {
					$ret['error'] = esc_html__( 'User has to be empty on registration', 'ultimate-member' );
					wp_send_json_error( $ret );
				}

				$form_post = get_post( $form_id );
				// Invalid post ID. Maybe post doesn't exist.
				if ( empty( $form_post ) ) {
					$ret['error'] = esc_html__( 'Invalid form ID', 'ultimate-member' );
					wp_send_json_error( $ret );
				}

				if ( 'um_form' !== $form_post->post_type ) {
					$ret['error'] = esc_html__( 'Invalid form post type', 'ultimate-member' );
					wp_send_json_error( $ret );
				}

				$form_status = get_post_status( $form_id );
				if ( 'publish' !== $form_status ) {
					$ret['error'] = esc_html__( 'Invalid form status', 'ultimate-member' );
					wp_send_json_error( $ret );
				}

				$post_data = UM()->query()->post_data( $form_id );
				if ( ! array_key_exists( 'mode', $post_data ) || $mode !== $post_data['mode'] ) {
					$ret['error'] = esc_html__( 'Invalid form type', 'ultimate-member' );
					wp_send_json_error( $ret );
				}

				// For profiles only.
				if ( 'profile' === $mode && ! empty( $post_data['use_custom_settings'] ) && ! empty( $post_data['role'] ) ) {
					// Option "Apply custom settings to this form". Option "Make this profile form role-specific".
					// Show the first Profile Form with role selected, don't show profile forms below the page with other role-specific setting.
					$current_user_roles = UM()->roles()->get_all_user_roles( $user_id );
					if ( empty( $current_user_roles ) ) {
						$ret['error'] = esc_html__( 'You have no permission to edit this user through this form', 'ultimate-member' );
						wp_send_json_error( $ret );
					}

					$post_data['role'] = maybe_unserialize( $post_data['role'] );

					if ( is_array( $post_data['role'] ) ) {
						if ( ! count( array_intersect( $post_data['role'], $current_user_roles ) ) ) {
							$ret['error'] = esc_html__( 'You have no permission to edit this user through this form', 'ultimate-member' );
							wp_send_json_error( $ret );
						}
					} elseif ( ! in_array( $post_data['role'], $current_user_roles, true ) ) {
						$ret['error'] = esc_html__( 'You have no permission to edit this user through this form', 'ultimate-member' );
						wp_send_json_error( $ret );
					}
				}

				if ( ! array_key_exists( 'custom_fields', $post_data ) || empty( $post_data['custom_fields'] ) ) {
					$ret['error'] = esc_html__( 'Invalid form fields', 'ultimate-member' );
					wp_send_json_error( $ret );
				}

				$custom_fields = maybe_unserialize( $post_data['custom_fields'] );
				if ( ! is_array( $custom_fields ) || ! array_key_exists( $id, $custom_fields ) ) {
					if ( ! ( 'profile' === $mode && in_array( $id, array( 'cover_photo', 'profile_photo' ), true ) ) ) {
						$ret['error'] = esc_html__( 'Invalid field metakey', 'ultimate-member' );
						wp_send_json_error( $ret );
					}
				}

				if ( 'profile' === $mode ) {
					if ( in_array( $id, array( 'cover_photo', 'profile_photo' ), true ) ) {
						if ( 'profile_photo' === $id ) {
							$disable_photo_uploader = empty( $post_data['use_custom_settings'] ) ? UM()->options()->get( 'disable_profile_photo_upload' ) : $post_data['disable_photo_upload'];
							if ( $disable_photo_uploader ) {
								$ret['error'] = esc_html__( 'You have no permission to edit this field', 'ultimate-member' );
								wp_send_json_error( $ret );
							}
						} else {
							$cover_enabled_uploader = empty( $post_data['use_custom_settings'] ) ? UM()->options()->get( 'profile_cover_enabled' ) : $post_data['cover_enabled'];
							if ( ! $cover_enabled_uploader ) {
								$ret['error'] = esc_html__( 'You have no permission to edit this field', 'ultimate-member' );
								wp_send_json_error( $ret );
							}
						}
					} elseif ( ! um_can_edit_field( $custom_fields[ $id ] ) ) {
						$ret['error'] = esc_html__( 'You have no permission to edit this field', 'ultimate-member' );
						wp_send_json_error( $ret );
					}
				}
			}

			if ( isset( $_FILES[ $id ]['name'] ) ) {
				if ( ! is_array( $_FILES[ $id ]['name'] ) ) {
					UM()->uploader()->replace_upload_dir = true;

					$uploaded = UM()->uploader()->upload_image( $_FILES[ $id ], $user_id, $id );

					UM()->uploader()->replace_upload_dir = false;

					if ( isset( $uploaded['error'] ) ) {
						$ret['error'] = $uploaded['error'];
					} else {
						$ret[] = $uploaded['handle_upload'];
					}
				}
			} else {
				$ret['error'] = esc_html__( 'A theme or plugin compatibility issue', 'ultimate-member' );
			}

			wp_send_json_success( $ret );
		}


/** Function ajax_get_members() called by wp_ajax hooks: {'nopriv_um_get_members', 'um_get_members'} **/
/** Parameters found in function ajax_get_members(): {"post": ["directory_id", "search", "sorting", "page"], "request": ["directory_id"], "session": ["um_member_directory_seed"]} **/
function ajax_get_members() {
			UM()->check_ajax_nonce();

			if ( UM()->is_rate_limited( 'member_directory' ) ) {
				wp_send_json_error( __( 'Too many requests', 'ultimate-member' ) );
			}

			global $wpdb;

			$blog_id = get_current_blog_id();
			// phpcs:disable WordPress.Security.NonceVerification -- verified via `UM()->check_ajax_nonce();`.
			if ( empty( $_POST['directory_id'] ) ) {
				wp_send_json_error( __( 'Wrong member directory data', 'ultimate-member' ) );
			}

			$directory_id = $this->get_directory_by_hash( sanitize_key( $_POST['directory_id'] ) );
			if ( empty( $directory_id ) ) {
				wp_send_json_error( __( 'Wrong member directory data', 'ultimate-member' ) );
			}
			// phpcs:enable WordPress.Security.NonceVerification -- verified via `UM()->check_ajax_nonce();`.

			if ( ! $this->can_view_directory( $directory_id ) ) {
				wp_send_json_error( __( 'You cannot see this member directory', 'ultimate-member' ) );
			}

			$directory_data = UM()->query()->post_data( $directory_id );

			// Predefined result for user without capabilities to see other members.
			$this->predefined_no_caps( $directory_data );

			do_action( 'um_member_directory_before_query' );

			// Prepare for BIG SELECT query
			$wpdb->query( 'SET SQL_BIG_SELECTS=1' );

			if ( ! empty( $directory_data['show_these_users'] ) ) {
				$show_these_users = maybe_unserialize( $directory_data['show_these_users'] );

				if ( is_array( $show_these_users ) && ! empty( $show_these_users ) ) {
					$users_array = array();
					foreach ( $show_these_users as $username ) {
						if ( false !== ( $exists_id = username_exists( $username ) ) ) {
							$users_array[] = absint( $exists_id );
						}
					}

					if ( ! empty( $users_array ) ) {
						$this->where_clauses[] = "u.ID IN ( '" . implode( "','", $users_array ) . "' )";
					}
				}
			}

			if ( ! empty( $directory_data['exclude_these_users'] ) ) {
				$exclude_these_users = maybe_unserialize( $directory_data['exclude_these_users'] );

				if ( is_array( $exclude_these_users ) && ! empty( $exclude_these_users ) ) {
					$users_array = array();
					foreach ( $exclude_these_users as $username ) {
						if ( false !== ( $exists_id = username_exists( $username ) ) ) {
							$users_array[] = absint( $exists_id );
						}
					}

					if ( ! empty( $users_array ) ) {
						$this->where_clauses[] = "u.ID NOT IN ( '" . implode( "','", $users_array ) . "' )";
					}
				}
			}

			$profile_photo_where = '';
			if ( $directory_data['has_profile_photo'] == 1 ) {
				$profile_photo_where = " AND umm_general.um_value LIKE '%s:13:\"profile_photo\";b:1;%'";
			}

			$cover_photo_where = '';
			if ( $directory_data['has_cover_photo'] == 1 ) {
				$cover_photo_where = " AND umm_general.um_value LIKE '%s:11:\"cover_photo\";b:1;%'";
			}

			if ( ! $this->can_edit_users() ) {
				if ( ! $this->general_meta_joined ) {
					$this->joins[] = "LEFT JOIN {$wpdb->prefix}um_metadata umm_general ON umm_general.user_id = u.ID";

					$this->general_meta_joined = true;
				}
				// $profile_photo_where and $cover_photo_where are static in code.
				$this->where_clauses[] = "( umm_general.um_key = 'um_member_directory_data' AND
				umm_general.um_value LIKE '%s:14:\"account_status\";s:8:\"approved\";%' AND umm_general.um_value LIKE '%s:15:\"hide_in_members\";b:0;%'{$profile_photo_where}{$cover_photo_where} )";
			} else {
				if ( ! empty( $cover_photo_where ) || ! empty( $profile_photo_where ) ) {
					if ( ! $this->general_meta_joined ) {
						$this->joins[] = "LEFT JOIN {$wpdb->prefix}um_metadata umm_general ON umm_general.user_id = u.ID";

						$this->general_meta_joined = true;
					}
					// $profile_photo_where and $cover_photo_where are static in code.
					$this->where_clauses[] = "( umm_general.um_key = 'um_member_directory_data'{$profile_photo_where}{$cover_photo_where} )";
				}
			}

			if ( UM()->roles()->um_user_can( 'can_view_all' ) ) {
				$view_roles = um_user( 'can_view_roles' );

				if ( ! $view_roles ) {
					$view_roles = array();
				} else {
					$this->roles_in_query = true;
				}

				$this->roles = array_merge( $this->roles, maybe_unserialize( $view_roles ) );
			}

			if ( ! empty( $directory_data['roles'] ) ) {
				if ( ! empty( $this->roles ) ) {
					$this->roles = array_intersect( $this->roles, maybe_unserialize( $directory_data['roles'] ) );
				} else {
					$this->roles = array_merge( $this->roles, maybe_unserialize( $directory_data['roles'] ) );
				}

				$this->roles_in_query = true;
			}

			if ( ! empty( $this->roles ) ) {
				$this->joins[] = $wpdb->prepare( "LEFT JOIN {$wpdb->prefix}um_metadata umm_roles ON ( umm_roles.user_id = u.ID AND umm_roles.um_key = %s )", $wpdb->get_blog_prefix( $blog_id ) . 'capabilities' );

				$roles_clauses = array();
				foreach ( $this->roles as $role ) {
					$roles_clauses[] = $wpdb->prepare( 'umm_roles.um_value LIKE %s', '%"' . $wpdb->esc_like( $role ) . '"%' );
				}

				// $roles_clauses is pre-prepared.
				$this->where_clauses[] = '( ' . implode( ' OR ', $roles_clauses ) . ' )';
			} else {
				if ( ! $this->roles_in_query && is_multisite() ) {
					// select users who have capabilities for current blog
					$this->joins[]         = $wpdb->prepare( "LEFT JOIN {$wpdb->prefix}um_metadata umm_roles ON ( umm_roles.user_id = u.ID AND umm_roles.um_key = %s )", $wpdb->get_blog_prefix( $blog_id ) . 'capabilities' );
					$this->where_clauses[] = 'umm_roles.um_value IS NOT NULL';
				} elseif ( $this->roles_in_query ) {
					$member_directory_response = array(
						'pagination' => $this->calculate_pagination( $directory_data, 0 ),
						'users'      => array(),
						'is_search'  => $this->is_search,
					);
					$member_directory_response = apply_filters( 'um_ajax_get_members_response', $member_directory_response, $directory_data );

					wp_send_json_success( $member_directory_response );
				}
			}

			// phpcs:disable WordPress.Security.NonceVerification -- verified via `UM()->check_ajax_nonce();`.
			if ( ! empty( $_POST['search'] ) ) {
				$search_line = $this->prepare_search( $_POST['search'] );
				// phpcs:enable WordPress.Security.NonceVerification -- verified via `UM()->check_ajax_nonce();`.
				if ( ! empty( $search_line ) ) {
					$searches = array();

					$exclude_fields = get_post_meta( $directory_id, '_um_search_exclude_fields', true );
					$include_fields = get_post_meta( $directory_id, '_um_search_include_fields', true );

					$core_search = $this->get_core_search_fields();
					if ( ! empty( $include_fields ) ) {
						$core_search = array_intersect( $core_search, $include_fields );
					}
					if ( ! empty( $exclude_fields ) ) {
						$core_search = array_diff( $core_search, $exclude_fields );
					}
					if ( ! empty( $core_search ) ) {
						foreach ( $core_search as $field ) {
							$field = esc_sql( $field );
							// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- $field is pre-escaped.
							$searches[] = $wpdb->prepare( "u.{$field} LIKE %s", '%' . $wpdb->esc_like( $search_line ) . '%' );
						}
					}

					$core_search = implode( ' OR ', $searches );
					if ( ! empty( $core_search ) ) {
						$core_search = ' OR ' . $core_search;
					}

					$this->joins[] = "LEFT JOIN {$wpdb->prefix}um_metadata umm_search ON umm_search.user_id = u.ID";

					$additional_search = apply_filters( 'um_member_directory_meta_general_search_meta_query', '', $search_line );

					$search_like_string = apply_filters( 'um_member_directory_meta_search_like_type', '%' . $wpdb->esc_like( $search_line ) . '%', $search_line );

					$custom_fields_sql = '';

					if ( ! empty( $exclude_fields ) ) {
						$custom_fields_sql = " AND umm_search.um_key NOT IN ('" . implode( "','", $exclude_fields ) . "') ";
					}
					if ( ! empty( $include_fields ) ) {
						$custom_fields_sql = " AND umm_search.um_key IN ('" . implode( "','", $include_fields ) . "') ";
					}

					// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- $core_search and $additional_search are pre-prepared.
					$this->where_clauses[] = $wpdb->prepare( "( umm_search.um_value = %s OR umm_search.um_value LIKE %s OR umm_search.um_value LIKE %s{$core_search}{$additional_search}){$custom_fields_sql}", $search_line, $search_like_string, '%' . $wpdb->esc_like( maybe_serialize( (string) $search_line ) ) . '%' );

					$this->is_search = true;
				}
			}

			// Filters
			$filter_query = array();
			if ( ! empty( $directory_data['search_fields'] ) ) {
				$search_filters = maybe_unserialize( $directory_data['search_fields'] );
				if ( ! empty( $search_filters ) && is_array( $search_filters ) ) {
					// phpcs:ignore WordPress.Security.NonceVerification -- verified via `UM()->check_ajax_nonce();`.
					$filter_query = array_intersect_key( $_POST, array_flip( $search_filters ) );
				}
			}

			// added for user tags extension integration on individual tag page
			$ignore_empty_filters = apply_filters( 'um_member_directory_ignore_empty_filters', false );

			if ( ! empty( $filter_query ) || $ignore_empty_filters ) {
				$this->is_search = true;

				$i = 1;
				foreach ( $filter_query as $field => $value ) {

					$field = sanitize_text_field( $field );
					if ( is_array( $value ) ) {
						$value = array_map( 'sanitize_text_field', $value );
					} else {
						$value = sanitize_text_field( $value );
					}

					$attrs = UM()->fields()->get_field( $field );
					// skip private invisible fields
					if ( ! um_can_view_field( $attrs ) ) {
						continue;
					}

					$this->handle_filter_query( $directory_data, $field, $value, $i );

					$i++;
				}
			}

			//unable default filter in case if we select other filters in frontend filters
			//if ( empty( $this->custom_filters_in_query ) ) {
			$default_filters = array();
			if ( ! empty( $directory_data['search_filters'] ) ) {
				$default_filters = maybe_unserialize( $directory_data['search_filters'] );
			}

			if ( ! empty( $default_filters ) ) {
				$i = 1;
				foreach ( $default_filters as $field => $value ) {

					$this->handle_filter_query( $directory_data, $field, $value, $i, true );

					$i++;
				}
			}
			//}

			$order = 'ASC';
			// phpcs:ignore WordPress.Security.NonceVerification -- verified via `UM()->check_ajax_nonce();`.
			$sortby = ! empty( $_POST['sorting'] ) ? sanitize_text_field( $_POST['sorting'] ) : $directory_data['sortby'];
			$sortby = ( 'other' === $sortby ) ? $directory_data['sortby_custom'] : $sortby;

			$custom_sort = array();
			if ( ! empty( $directory_data['sorting_fields'] ) ) {
				$sorting_fields = maybe_unserialize( $directory_data['sorting_fields'] );
				foreach ( $sorting_fields as $field ) {
					if ( is_array( $field ) ) {
						$field_keys    = array_keys( $field );
						$custom_sort[] = $field_keys[0];
					}
				}
			}

			$numeric_sorting_keys = array();

			if ( ! empty( UM()->builtin()->saved_fields ) ) {
				foreach ( UM()->builtin()->saved_fields as $key => $data ) {
					if ( '_um_last_login' === $key ) {
						continue;
					}

					if ( isset( $data['type'] ) && 'number' === $data['type'] ) {
						if ( array_key_exists( $key . '_desc', $this->sort_fields ) ) {
							$numeric_sorting_keys[] = $key . '_desc';
						}
						if ( array_key_exists( $key . '_asc', $this->sort_fields ) ) {
							$numeric_sorting_keys[] = $key . '_asc';
						}
					}
				}
			}

			// handle sorting options
			// sort members by
			if ( $sortby === $directory_data['sortby_custom'] || in_array( $sortby, $custom_sort, true ) ) {
				$custom_sort_order = ! empty( $directory_data['sortby_custom_order'] ) ? $directory_data['sortby_custom_order'] : 'ASC';

				$this->joins[] = $wpdb->prepare( "LEFT JOIN {$wpdb->prefix}um_metadata umm_sort ON ( umm_sort.user_id = u.ID AND umm_sort.um_key = %s )", $sortby );

				$meta_query       = new \WP_Meta_Query();
				$custom_sort_type = ! empty( $directory_data['sortby_custom_type'] ) ? $meta_query->get_cast_for_type( $directory_data['sortby_custom_type'] ) : 'CHAR';
				if ( ! empty( $directory_data['sorting_fields'] ) ) {
					// phpcs:ignore WordPress.Security.NonceVerification -- already verified here
					$sorting        = sanitize_text_field( $_POST['sorting'] );
					$sorting_fields = maybe_unserialize( $directory_data['sorting_fields'] );

					foreach ( $sorting_fields as $field ) {
						if ( isset( $field[ $sorting ] ) ) {
							$custom_sort_type  = ! empty( $field['type'] ) ? $meta_query->get_cast_for_type( $field['type'] ) : 'CHAR';
							$custom_sort_order = $field['order'];
						}
					}
				}

				/** This filter is documented in includes/core/class-member-directory.php */
				$custom_sort_type = apply_filters( 'um_member_directory_custom_sorting_type', $custom_sort_type, $sortby, $directory_data );
				$custom_sort_type = esc_sql( $custom_sort_type );
				$custom_sort_type = in_array( strtoupper( $custom_sort_type ), $this->sort_data_types, true ) ? $custom_sort_type : 'CHAR';

				$custom_sort_order = esc_sql( $custom_sort_order );
				$custom_sort_order = in_array( strtoupper( $custom_sort_order ), array( 'ASC', 'DESC' ), true ) ? $custom_sort_order : 'ASC';
				$this->sql_order   = " ORDER BY CAST( umm_sort.um_value AS {$custom_sort_type} ) {$custom_sort_order} ";

			} elseif ( count( $numeric_sorting_keys ) && in_array( $sortby, $numeric_sorting_keys, true ) ) {

				if ( false !== strpos( $sortby, '_desc' ) ) {
					$sortby = str_replace( '_desc', '', $sortby );
					$order  = 'DESC';
				}

				if ( false !== strpos( $sortby, '_asc' ) ) {
					$sortby = str_replace( '_asc', '', $sortby );
					$order  = 'ASC';
				}

				$order           = esc_sql( $order );
				$order           = in_array( strtoupper( $order ), array( 'ASC', 'DESC' ), true ) ? $order : 'ASC';
				$this->joins[]   = $wpdb->prepare( "LEFT JOIN {$wpdb->prefix}um_metadata umm_sort ON ( umm_sort.user_id = u.ID AND umm_sort.um_key = %s )", $sortby );
				$this->sql_order = " ORDER BY CAST( umm_sort.um_value AS SIGNED ) {$order}, u.user_registered DESC ";

			} elseif ( 'username' === $sortby ) {

				$order           = esc_sql( $order );
				$order           = in_array( strtoupper( $order ), array( 'ASC', 'DESC' ), true ) ? $order : 'ASC';
				$this->sql_order = " ORDER BY u.user_login {$order} ";

			} elseif ( 'display_name' === $sortby ) {

				$display_name = UM()->options()->get( 'display_name' );
				if ( 'username' === $display_name ) {

					$order           = esc_sql( $order );
					$order           = in_array( strtoupper( $order ), array( 'ASC', 'DESC' ), true ) ? $order : 'ASC';
					$this->sql_order = " ORDER BY u.user_login {$order} ";

				} else {

					$this->joins[] = "LEFT JOIN {$wpdb->prefix}um_metadata umm_sort ON ( umm_sort.user_id = u.ID AND umm_sort.um_key = 'full_name' )";

					$order           = esc_sql( $order );
					$order           = in_array( strtoupper( $order ), array( 'ASC', 'DESC' ), true ) ? $order : 'ASC';
					$this->sql_order = " ORDER BY CAST( umm_sort.um_value AS CHAR ) {$order}, u.display_name {$order} ";

				}
			} elseif ( in_array( $sortby, array( 'last_name', 'first_name', 'nickname' ), true ) ) {

				$this->joins[] = $wpdb->prepare( "LEFT JOIN {$wpdb->prefix}um_metadata umm_sort ON ( umm_sort.user_id = u.ID AND umm_sort.um_key = %s )", $sortby );

				$order           = esc_sql( $order );
				$order           = in_array( strtoupper( $order ), array( 'ASC', 'DESC' ), true ) ? $order : 'ASC';
				$this->sql_order = " ORDER BY CAST( umm_sort.um_value AS CHAR ) {$order} ";

			} elseif ( 'last_login' === $sortby ) {

				$this->joins[]   = "LEFT JOIN {$wpdb->prefix}um_metadata umm_sort ON ( umm_sort.user_id = u.ID AND umm_sort.um_key = '_um_last_login' )";
				$this->joins[]   = "LEFT JOIN {$wpdb->prefix}um_metadata umm_show_login ON ( umm_show_login.user_id = u.ID AND umm_show_login.um_key = 'um_show_last_login' )";
				$this->sql_order = $wpdb->prepare( ' ORDER BY CASE ISNULL(NULLIF(umm_show_login.um_value,%s)) WHEN 0 THEN %s ELSE CAST( umm_sort.um_value AS DATETIME ) END DESC ', 'a:1:{i:0;s:3:"yes";}', '1970-01-01 00:00:00' );

			} elseif ( 'last_first_name' === $sortby ) {

				$this->joins[] = "LEFT JOIN {$wpdb->prefix}um_metadata umm_sort ON ( umm_sort.user_id = u.ID AND umm_sort.um_key = 'last_name' )";
				$this->joins[] = "LEFT JOIN {$wpdb->prefix}um_metadata umm_sort2 ON ( umm_sort2.user_id = u.ID AND umm_sort2.um_key = 'first_name' )";

				$this->sql_order = ' ORDER BY CAST( umm_sort.um_value AS CHAR ) ASC, CAST( umm_sort2.um_value AS CHAR ) ASC ';

			} elseif ( 'random' === $sortby ) {

				if ( um_is_session_started() === false ) {
					// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
					@session_start();
				}

				// Reset seed on load of initial
				// phpcs:ignore WordPress.Security.NonceVerification -- verified via `UM()->check_ajax_nonce();`.
				if ( empty( $_REQUEST['directory_id'] ) && isset( $_SESSION['um_member_directory_seed'] ) ) {
					unset( $_SESSION['um_member_directory_seed'] );
				}

				// Get seed from session variable if it exists
				$seed = false;
				if ( isset( $_SESSION['um_member_directory_seed'] ) ) {
					$seed = (int) $_SESSION['um_member_directory_seed'];
				}

				// Set new seed if none exists
				if ( ! $seed ) {
					$seed = wp_rand();

					$_SESSION['um_member_directory_seed'] = $seed;
				}

				$seed            = esc_sql( $seed );
				$this->sql_order = 'ORDER BY RAND(' . $seed . ')';

			} else {

				if ( false !== strpos( $sortby, '_desc' ) ) {
					$sortby = str_replace( '_desc', '', $sortby );
					$order  = 'DESC';
				}

				if ( false !== strpos( $sortby, '_asc' ) ) {
					$sortby = str_replace( '_asc', '', $sortby );
					$order  = 'ASC';
				}

				$metakeys = get_option( 'um_usermeta_fields', array() );
				if ( in_array( $sortby, $this->core_users_fields, true ) ) {
					$sortby          = esc_sql( $sortby );
					$order           = esc_sql( $order );
					$order           = in_array( strtoupper( $order ), array( 'ASC', 'DESC' ), true ) ? $order : 'ASC';
					$this->sql_order = " ORDER BY u.{$sortby} {$order} ";
				} elseif ( in_array( $sortby, $metakeys, true ) ) {
					$this->joins[]   = $wpdb->prepare( "LEFT JOIN {$wpdb->prefix}um_metadata umm_sort ON ( umm_sort.user_id = u.ID AND umm_sort.um_key = %s )", $sortby );
					$order           = esc_sql( $order );
					$order           = in_array( strtoupper( $order ), array( 'ASC', 'DESC' ), true ) ? $order : 'ASC';
					$this->sql_order = " ORDER BY CAST( umm_sort.um_value AS CHAR ) {$order} ";
				}
			}

			$this->sql_order = apply_filters( 'um_modify_sortby_parameter_meta', $this->sql_order, $sortby );

			$profiles_per_page = $directory_data['profiles_per_page'];
			if ( wp_is_mobile() && isset( $directory_data['profiles_per_page_mobile'] ) ) {
				$profiles_per_page = $directory_data['profiles_per_page_mobile'];
			}

			$query_number = ( ! empty( $directory_data['max_users'] ) && $directory_data['max_users'] <= $profiles_per_page ) ? $directory_data['max_users'] : $profiles_per_page;
			$query_paged  = ! empty( $_POST['page'] ) ? absint( $_POST['page'] ) : 1; // phpcs:ignore WordPress.Security.NonceVerification -- verified via `UM()->check_ajax_nonce();`.

			$number = $query_number;
			if ( ! empty( $directory_data['max_users'] ) && $query_paged * $query_number > $directory_data['max_users'] ) {
				$number = ( $query_paged * $query_number - ( $query_paged * $query_number - $directory_data['max_users'] ) ) % $query_number;
			}

			// limit
			if ( isset( $query_number ) && $query_number > 0 ) {
				$this->sql_limit .= $wpdb->prepare( 'LIMIT %d, %d', $query_number * ( $query_paged - 1 ), $number );
			}

			do_action( 'um_pre_users_query', $this, $directory_data, $sortby );

			$sql_select = esc_sql( $this->select );
			$sql_having = esc_sql( $this->having );
			$sql_join   = implode( ' ', $this->joins );
			$sql_where  = implode( ' AND ', $this->where_clauses );
			$sql_where  = ! empty( $sql_where ) ? 'AND ' . $sql_where : '';

			$query = array(
				'select'    => $this->select,
				'sql_where' => $sql_where,
				'having'    => $this->having,
				'sql_limit' => $this->sql_limit,
			);

			/** This filter is documented in includes/core/class-member-directory.php */
			do_action( 'um_user_before_query', $query, $this );

			/*
			 *
			 * SQL_CALC_FOUND_ROWS is deprecated as of MySQL 8.0.17
			 * https://core.trac.wordpress.org/ticket/47280
			 *
			 * */
			$user_ids = $wpdb->get_col(
				"SELECT SQL_CALC_FOUND_ROWS DISTINCT u.ID
				{$sql_select}
				FROM {$wpdb->users} AS u
				{$sql_join}
				WHERE 1=1 {$sql_where}
				{$sql_having}
				{$this->sql_order}
				{$this->sql_limit}"
			);

			$total_users = (int) $wpdb->get_var( 'SELECT FOUND_ROWS()' );

			/**
			 * Filters the member directory query result when um_usermeta table is used.
			 *
			 * @since 2.1.3
			 * @hook um_prepare_user_results_array_meta
			 *
			 * @param {array} $user_ids   Members Query Result.
			 * @param {array} $query_args Query arguments.
			 *
			 * @return {array} Query result.
			 *
			 * @example <caption>Remove some users where ID equals 10 and 12 from query.</caption>
			 * function my_custom_um_prepare_user_results_array_meta( $user_ids, $query_args ) {
			 *     $user_ids = array_diff( $user_ids, array( 10, 12 ) );
			 *     return $user_ids;
			 * }
			 * add_filter( 'um_prepare_user_results_array_meta', 'my_custom_um_prepare_user_results_array', 10, 2 );
			 */
			$user_ids = apply_filters( 'um_prepare_user_results_array_meta', $user_ids, $query );

			$pagination_data = $this->calculate_pagination( $directory_data, $total_users );

			$sizes = UM()->options()->get( 'cover_thumb_sizes' );
			// Ensure we have valid sizes array and handle case when only one size is defined
			if ( ! is_array( $sizes ) || empty( $sizes ) ) {
				$sizes = array( 300 ); // fallback to default
			}

			// For mobile, use second size if available, otherwise use first size
			$available_mobile = isset( $sizes[1] ) ? $sizes[1] : $sizes[0];
			$this->cover_size = wp_is_mobile() ? $available_mobile : end( $sizes );

			$avatar_size       = UM()->options()->get( 'profile_photosize' );
			$this->avatar_size = str_replace( 'px', '', $avatar_size );

			$users = array();
			foreach ( $user_ids as $user_id ) {
				$users[] = $this->build_user_card_data( $user_id, $directory_data );
			}

			um_reset_user();
			// end of user card

			$member_directory_response = array(
				'pagination' => $pagination_data,
				'users'      => $users,
				'is_search'  => $this->is_search,
			);
			$member_directory_response = apply_filters( 'um_ajax_get_members_response', $member_directory_response, $directory_data );

			wp_send_json_success( $member_directory_response );
		}


/** Function ajax_remove_file() called by wp_ajax hooks: {'um_remove_file', 'nopriv_um_remove_file'} **/
/** Parameters found in function ajax_remove_file(): {"post": ["src", "mode", "user_id", "filename"]} **/
function ajax_remove_file() {
			UM()->check_ajax_nonce();

			if ( UM()->is_rate_limited( 'remove_file' ) ) {
				wp_send_json_error( __( 'Too many requests', 'ultimate-member' ) );
			}

			if ( empty( $_POST['src'] ) ) {
				wp_send_json_error( __( 'Wrong path', 'ultimate-member' ) );
			}

			if ( empty( $_POST['mode'] ) ) {
				wp_send_json_error( __( 'Wrong mode', 'ultimate-member' ) );
			}

			$src = esc_url_raw( $_POST['src'] );
			if ( strstr( $src, '?' ) ) {
				$splitted = explode( '?', $src );
				$src = $splitted[0];
			}

			$mode = sanitize_key( $_POST['mode'] );

			if ( $mode == 'register' || empty( $_POST['user_id'] ) ) {
				$is_temp = um_is_temp_upload( $src );
				if ( ! $is_temp ) {
					wp_send_json_success();
				}
			} else {
				$user_id = absint( $_POST['user_id'] );

				if ( ! UM()->roles()->um_current_user_can( 'edit', $user_id ) ) {
					wp_send_json_error( __( 'You have no permission to edit this user', 'ultimate-member' ) );
				}

				$is_temp = um_is_temp_upload( $src );
				if ( ! $is_temp ) {
					if ( ! empty( $_POST['filename'] ) && file_exists( UM()->uploader()->get_upload_user_base_dir( $user_id ) . DIRECTORY_SEPARATOR . sanitize_file_name( $_POST['filename'] ) ) ) {
						wp_send_json_success();
					}
				}
			}

			if ( $this->delete_file( $src ) ) {
				wp_send_json_success();
			} else {
				wp_send_json_error( __( 'You have no permission to delete this file', 'ultimate-member' ) );
			}
		}


/** Function ajax_delete_profile_photo() called by wp_ajax hooks: {'um_delete_profile_photo'} **/
/** Parameters found in function ajax_delete_profile_photo(): {"request": ["user_id"]} **/
function ajax_delete_profile_photo() {
			UM()->check_ajax_nonce();

			if ( ! array_key_exists( 'user_id', $_REQUEST ) ) {
				wp_send_json_error( __( 'Invalid data', 'ultimate-member' ) );
			}

			$user_id = absint( $_REQUEST['user_id'] );

			if ( ! UM()->roles()->um_current_user_can( 'edit', $user_id ) ) {
				die( esc_html__( 'You can not edit this user', 'ultimate-member' ) );
			}

			UM()->files()->delete_core_user_photo( $user_id, 'profile_photo' );
		}


/** Function um_request_user_data() called by wp_ajax hooks: {'um_request_user_data'} **/
/** Parameters found in function um_request_user_data(): {"post": ["request_action", "password"]} **/
function um_request_user_data() {
	UM()->check_ajax_nonce();

	if ( ! isset( $_POST['request_action'] ) ) {
		wp_send_json_error( __( 'Wrong request.', 'ultimate-member' ) );
	}

	$user_id        = get_current_user_id();
	$password       = ! empty( $_POST['password'] ) ? sanitize_text_field( $_POST['password'] ) : '';
	$user           = get_userdata( $user_id );
	$hash           = $user->data->user_pass;
	$request_action = sanitize_key( $_POST['request_action'] );

	if ( 'um-export-data' === $request_action ) {
		if ( UM()->account()->current_password_is_required( 'privacy_download_data' ) ) {
			if ( ! wp_check_password( $password, $hash ) ) {
				$answer = esc_html__( 'The password you entered is incorrect.', 'ultimate-member' );
				wp_send_json_success( array( 'answer' => $answer ) );
			}
		}
	} elseif ( 'um-erase-data' === $request_action ) {
		if ( UM()->account()->current_password_is_required( 'privacy_erase_data' ) ) {
			if ( ! wp_check_password( $password, $hash ) ) {
				$answer = esc_html__( 'The password you entered is incorrect.', 'ultimate-member' );
				wp_send_json_success( array( 'answer' => $answer ) );
			}
		}
	}

	if ( 'um-export-data' === $request_action ) {
		$request_id = wp_create_user_request( $user->data->user_email, 'export_personal_data' );
	} elseif ( 'um-erase-data' === $request_action ) {
		$request_id = wp_create_user_request( $user->data->user_email, 'remove_personal_data' );
	}

	if ( ! isset( $request_id ) || empty( $request_id ) ) {
		wp_send_json_error( __( 'Wrong request.', 'ultimate-member' ) );
	}

	if ( is_wp_error( $request_id ) ) {
		$answer = esc_html( $request_id->get_error_message() );
	} else {
		wp_send_user_request( $request_id );
		if ( 'um-export-data' === $request_action ) {
			$answer = esc_html__( 'A confirmation email has been sent to your email. Click the link within the email to confirm your export request.', 'ultimate-member' );
		} elseif ( 'um-erase-data' === $request_action ) {
			$answer = esc_html__( 'A confirmation email has been sent to your email. Click the link within the email to confirm your deletion request.', 'ultimate-member' );
		}
	}

	wp_send_json_success( array( 'answer' => $answer ) );
}


/** Function ajax_file_upload() called by wp_ajax hooks: {'um_fileupload', 'nopriv_um_fileupload'} **/
/** Parameters found in function ajax_file_upload(): {"post": ["_wpnonce", "timestamp", "user_id", "set_id", "set_mode", "key"]} **/
function ajax_file_upload() {
			if ( UM()->is_rate_limited( 'upload_file' ) ) {
				wp_send_json_error( __( 'Too many requests', 'ultimate-member' ) );
			}

			$ret['error'] = null;
			$ret          = array();

			/**
			 * Filters file upload checking nonce.
			 *
			 * @param {bool} $verify_nonce Verify nonce marker. Default true.
			 *
			 * @return {bool} Verify nonce marker.
			 *
			 * @since 1.3.x
			 * @hook um_file_upload_nonce
			 *
			 * @example <caption>Disable checking nonce on file upload.</caption>
			 * function my_file_upload_nonce( $verify_nonce ) {
			 *     // your code here
			 *     $verify_nonce = false;
			 *     return $verify_nonce;
			 * }
			 * add_filter( 'um_file_upload_nonce', 'my_file_upload_nonce' );
			 */
			$um_file_upload_nonce = apply_filters( 'um_file_upload_nonce', true );
			if ( $um_file_upload_nonce ) {
				$nonce     = sanitize_text_field( $_POST['_wpnonce'] );
				$timestamp = absint( $_POST['timestamp'] );

				if ( ! wp_verify_nonce( $nonce, 'um_upload_nonce-' . $timestamp ) && is_user_logged_in() ) {
					// This nonce is not valid.
					$ret['error'] = esc_html__( 'Invalid nonce', 'ultimate-member' );
					wp_send_json_error( $ret );
				}
			}

			$user_id = empty( $_POST['user_id'] ) ? null : absint( $_POST['user_id'] );
			if ( $user_id && is_user_logged_in() && ! UM()->roles()->um_current_user_can( 'edit', $user_id ) ) {
				$ret['error'] = esc_html__( 'You have no permission to edit this user', 'ultimate-member' );
				wp_send_json_error( $ret );
			}

			if ( $user_id && ! is_user_logged_in() ) {
				$ret['error'] = esc_html__( 'You have no permission to edit this user', 'ultimate-member' );
				wp_send_json_error( $ret );
			}

			$form_id = absint( $_POST['set_id'] );
			$mode    = sanitize_key( $_POST['set_mode'] );

			UM()->fields()->set_id   = $form_id;
			UM()->fields()->set_mode = $mode;

			if ( ! is_user_logged_in() && 'profile' === $mode ) {
				$ret['error'] = esc_html__( 'You have no permission to edit this user', 'ultimate-member' );
				wp_send_json_error( $ret );
			}

			if ( null !== $user_id && 'register' === $mode ) {
				$ret['error'] = esc_html__( 'User has to be empty on registration', 'ultimate-member' );
				wp_send_json_error( $ret );
			}

			$form_post = get_post( $form_id );
			// Invalid post ID. Maybe post doesn't exist.
			if ( empty( $form_post ) ) {
				$ret['error'] = esc_html__( 'Invalid form ID', 'ultimate-member' );
				wp_send_json_error( $ret );
			}

			if ( 'um_form' !== $form_post->post_type ) {
				$ret['error'] = esc_html__( 'Invalid form post type', 'ultimate-member' );
				wp_send_json_error( $ret );
			}

			$form_status = get_post_status( $form_id );
			if ( 'publish' !== $form_status ) {
				$ret['error'] = esc_html__( 'Invalid form status', 'ultimate-member' );
				wp_send_json_error( $ret );
			}

			$post_data = UM()->query()->post_data( $form_id );
			if ( ! array_key_exists( 'mode', $post_data ) || $mode !== $post_data['mode'] ) {
				$ret['error'] = esc_html__( 'Invalid form type', 'ultimate-member' );
				wp_send_json_error( $ret );
			}

			// For profiles only.
			if ( 'profile' === $mode && ! empty( $post_data['use_custom_settings'] ) && ! empty( $post_data['role'] ) ) {
				// Option "Apply custom settings to this form". Option "Make this profile form role-specific".
				// Show the first Profile Form with role selected, don't show profile forms below the page with other role-specific setting.
				$current_user_roles = UM()->roles()->get_all_user_roles( $user_id );
				if ( empty( $current_user_roles ) ) {
					$ret['error'] = esc_html__( 'You have no permission to edit this user through this form', 'ultimate-member' );
					wp_send_json_error( $ret );
				}

				$post_data['role'] = maybe_unserialize( $post_data['role'] );

				if ( is_array( $post_data['role'] ) ) {
					if ( ! count( array_intersect( $post_data['role'], $current_user_roles ) ) ) {
						$ret['error'] = esc_html__( 'You have no permission to edit this user through this form', 'ultimate-member' );
						wp_send_json_error( $ret );
					}
				} elseif ( ! in_array( $post_data['role'], $current_user_roles, true ) ) {
					$ret['error'] = esc_html__( 'You have no permission to edit this user through this form', 'ultimate-member' );
					wp_send_json_error( $ret );
				}
			}

			$id = sanitize_text_field( $_POST['key'] );

			if ( ! array_key_exists( 'custom_fields', $post_data ) || empty( $post_data['custom_fields'] ) ) {
				$ret['error'] = esc_html__( 'Invalid form fields', 'ultimate-member' );
				wp_send_json_error( $ret );
			}

			$custom_fields = maybe_unserialize( $post_data['custom_fields'] );
			if ( ! is_array( $custom_fields ) || ! array_key_exists( $id, $custom_fields ) ) {
				$ret['error'] = esc_html__( 'Invalid field metakey', 'ultimate-member' );
				wp_send_json_error( $ret );
			}

			if ( 'profile' === $mode && ! um_can_edit_field( $custom_fields[ $id ] ) ) {
				$ret['error'] = esc_html__( 'You have no permission to edit this field', 'ultimate-member' );
				wp_send_json_error( $ret );
			}

			if ( isset( $_FILES[ $id ]['name'] ) ) {
				if ( ! is_array( $_FILES[ $id ]['name'] ) ) {
					UM()->uploader()->replace_upload_dir = true;

					$uploaded = UM()->uploader()->upload_file( $_FILES[ $id ], $user_id, $id );

					UM()->uploader()->replace_upload_dir = false;

					if ( isset( $uploaded['error'] ) ) {
						$ret['error'] = $uploaded['error'];
					} else {
						$uploaded_file        = $uploaded['handle_upload'];
						$ret['url']           = $uploaded_file['file_info']['name'];
						$ret['icon']          = UM()->files()->get_fonticon_by_ext( $uploaded_file['file_info']['ext'] );
						$ret['icon_bg']       = UM()->files()->get_fonticon_bg_by_ext( $uploaded_file['file_info']['ext'] );
						$ret['filename']      = $uploaded_file['file_info']['basename'];
						$ret['original_name'] = $uploaded_file['file_info']['original_name'];
					}
				}
			} else {
				$ret['error'] = esc_html__( 'A theme or plugin compatibility issue', 'ultimate-member' );
			}

			wp_send_json_success( $ret );
		}


/** Function update_order() called by wp_ajax hooks: {'um_update_order'} **/
/** Parameters found in function update_order(): {"post": ["form_id"]} **/
function update_order() {
			UM()->admin()->check_ajax_nonce();
			// phpcs:disable WordPress.Security.NonceVerification -- already verified here

			if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'Please login as administrator', 'ultimate-member' ) );
			}

			if ( empty( $_POST['form_id'] ) ) {
				wp_send_json_error( __( 'Invalid form ID.', 'ultimate-member' ) );
			}

			$form_id = absint( $_POST['form_id'] );
			if ( empty( $form_id ) ) {
				wp_send_json_error( __( 'Invalid form ID.', 'ultimate-member' ) );
			}

			$fields = UM()->query()->get_attr( 'custom_fields', $form_id );

			$this->row_data   = get_option( 'um_form_rowdata_' . $form_id, array() );
			$this->exist_rows = array();

			if ( ! empty( $fields ) ) {
				foreach ( $fields as $key => $array ) {
					if ( 'row' === $array['type'] ) {
						$this->row_data[ $key ] = $array;
						unset( $fields[ $key ] );
					}
				}
			} else {
				$fields = array();
			}

			foreach ( $_POST as $key => $value ) {
				// don't use sanitize_key here because of a key can be in Uppercase
				$key = sanitize_text_field( $key );

				// adding rows
				if ( 0 === strpos( $key, '_um_row_' ) ) {
					$update_args = null;

					$row_id = str_replace( '_um_row_', '', $key );

					if ( false !== strpos( $_POST[ '_um_rowcols_' . $row_id . '_cols' ], ':' ) ) {
						$cols = sanitize_text_field( $_POST[ '_um_rowcols_' . $row_id . '_cols' ] );
					} else {
						$cols = absint( $_POST[ '_um_rowcols_' . $row_id . '_cols' ] );
					}

					$row_array = array(
						'type'     => 'row',
						'id'       => sanitize_key( $value ),
						'sub_rows' => absint( $_POST[ '_um_rowsub_' . $row_id . '_rows' ] ),
						'cols'     => $cols,
						'origin'   => sanitize_key( $_POST[ '_um_roworigin_' . $row_id . '_val' ] ),
					);

					$row_args = $row_array;

					if ( isset( $this->row_data[ $row_array['origin'] ] ) ) {
						foreach ( $this->row_data[ $row_array['origin'] ] as $k => $v ) {
							if ( 'position' !== $k && 'metakey' !== $k ) {
								$update_args[ $k ] = $v;
							}
						}
						if ( isset( $update_args ) ) {
							$row_args = array_merge( $update_args, $row_array );
						}
						$this->exist_rows[] = $key;
					}

					$fields[ $key ] = $row_args;
				}

				// change field position
				if ( 0 === strpos( $key, 'um_position_' ) ) {
					$field_key = str_replace( 'um_position_', '', $key );
					if ( isset( $fields[ $field_key ] ) ) {
						$fields[ $field_key ]['position'] = absint( $value );
					}
				}

				// change field master row
				if ( 0 === strpos( $key, 'um_row_' ) ) {
					$field_key = str_replace( 'um_row_', '', $key );
					if ( isset( $fields[ $field_key ] ) ) {
						$fields[ $field_key ]['in_row'] = sanitize_key( $value );
					}
				}

				// change field sub row
				if ( 0 === strpos( $key, 'um_subrow_' ) ) {
					$field_key = str_replace( 'um_subrow_', '', $key );
					if ( isset( $fields[ $field_key ] ) ) {
						$fields[ $field_key ]['in_sub_row'] = sanitize_key( $value );
					}
				}

				// change field column
				if ( 0 === strpos( $key, 'um_col_' ) ) {
					$field_key = str_replace( 'um_col_', '', $key );
					if ( isset( $fields[ $field_key ] ) ) {
						$fields[ $field_key ]['in_column'] = absint( $value );
					}
				}

				// add field to group
				if ( 0 === strpos( $key, 'um_group_' ) ) {
					$field_key = str_replace( 'um_group_', '', $key );
					if ( isset( $fields[ $field_key ] ) ) {
						$fields[ $field_key ]['in_group'] = ! empty( $value ) ? absint( $value ) : '';
					}
				}
			}

			foreach ( $this->row_data as $k => $v ) {
				if ( ! in_array( $k, $this->exist_rows, true ) ) {
					unset( $this->row_data[ $k ] );
				}
			}

			update_option( 'um_existing_rows_' . $form_id, $this->exist_rows );

			update_option( 'um_form_rowdata_' . $form_id, $this->row_data );

			UM()->query()->update_attr( 'custom_fields', $form_id, $fields );
			// phpcs:enable WordPress.Security.NonceVerification -- already verified here
		}


/** Function ajax_select_options() called by wp_ajax hooks: {'um_select_options', 'nopriv_um_select_options'} **/
/** Parameters found in function ajax_select_options(): {"post": ["child_callback", "form_id", "child_name"]} **/
function ajax_select_options() {
			UM()->check_ajax_nonce();

			if ( UM()->is_rate_limited( 'select_options' ) ) {
				wp_send_json_error( __( 'Too many requests', 'ultimate-member' ) );
			}

			// phpcs:disable WordPress.Security.NonceVerification

			$arr_options           = array();
			$arr_options['status'] = 'success';
			$arr_options['post']   = $_POST;

			// Callback validation
			if ( empty( $_POST['child_callback'] ) ) {
				$arr_options['status']  = 'error';
				$arr_options['message'] = __( 'Wrong callback.', 'ultimate-member' );

				wp_send_json( $arr_options );
			}

			$ajax_source_func = sanitize_text_field( $_POST['child_callback'] );

			if ( ! function_exists( $ajax_source_func ) ) {
				$arr_options['status']  = 'error';
				$arr_options['message'] = __( 'Wrong callback.', 'ultimate-member' );

				wp_send_json( $arr_options );
			}

			$allowed_callbacks = UM()->options()->get( 'allowed_choice_callbacks' );

			if ( empty( $allowed_callbacks ) ) {
				$arr_options['status']  = 'error';
				$arr_options['message'] = __( 'This is not possible for security reasons.', 'ultimate-member' );
				wp_send_json( $arr_options );
			}

			$allowed_callbacks = array_map( 'rtrim', explode( "\n", wp_unslash( $allowed_callbacks ) ) );

			if ( ! in_array( $ajax_source_func, $allowed_callbacks, true ) ) {
				$arr_options['status']  = 'error';
				$arr_options['message'] = __( 'This is not possible for security reasons.', 'ultimate-member' );

				wp_send_json( $arr_options );
			}

			if ( UM()->fields()->is_source_blacklisted( $ajax_source_func ) ) {
				$arr_options['status']  = 'error';
				$arr_options['message'] = __( 'This is not possible for security reasons.', 'ultimate-member' );

				wp_send_json( $arr_options );
			}

			if ( isset( $_POST['form_id'] ) ) {
				UM()->fields()->set_id = absint( $_POST['form_id'] );
			}
			UM()->fields()->set_mode = 'profile';
			$form_fields             = UM()->fields()->get_fields();
			$arr_options['fields']   = $form_fields;

			if ( isset( $arr_options['post']['members_directory'] ) && 'yes' === $arr_options['post']['members_directory'] ) {
				global $wpdb;

				$values_array = $wpdb->get_col(
					$wpdb->prepare(
						"SELECT DISTINCT meta_value
						FROM $wpdb->usermeta
						WHERE meta_key = %s AND
							  meta_value != ''",
						$arr_options['post']['child_name']
					)
				);

				if ( ! empty( $values_array ) ) {
					$parent_dropdown      = isset( $arr_options['post']['parent_option_name'] ) ? $arr_options['post']['parent_option_name'] : '';
					$arr_options['items'] = call_user_func( $ajax_source_func, $parent_dropdown );

					if ( array_keys( $arr_options['items'] ) !== range( 0, count( $arr_options['items'] ) - 1 ) ) {
						// array with dropdown items is associative
						$arr_options['items'] = array_intersect_key( array_map( 'trim', $arr_options['items'] ), array_flip( $values_array ) );
					} else {
						// array with dropdown items has sequential numeric keys, starting from 0 and there are intersected values with $values_array
						$arr_options['items'] = array_intersect( $arr_options['items'], $values_array );
					}
				} else {
					$arr_options['items'] = array();
				}

				wp_send_json( $arr_options );
			} else {
				/**
				 * UM hook
				 *
				 * @type filter
				 * @title um_ajax_select_options__debug_mode
				 * @description Activate debug mode for AJAX select options
				 * @input_vars
				 * [{"var":"$debug_mode","type":"bool","desc":"Enable Debug mode"}]
				 * @change_log
				 * ["Since: 2.0"]
				 * @usage
				 * <?php add_filter( 'um_ajax_select_options__debug_mode', 'function_name', 10, 1 ); ?>
				 * @example
				 * <?php
				 * add_filter( 'um_ajax_select_options__debug_mode', 'my_ajax_select_options__debug_mode', 10, 1 );
				 * function my_ajax_select_options__debug_mode( $debug_mode ) {
				 *     // your code here
				 *     return $debug_mode;
				 * }
				 * ?>
				 */
				$debug = apply_filters( 'um_ajax_select_options__debug_mode', false );
				if ( $debug ) {
					$arr_options['debug'] = array(
						$_POST,
						$form_fields,
					);
				}

				if ( ! empty( $_POST['child_callback'] ) && isset( $form_fields[ $_POST['child_name'] ] ) ) {
					// If the requested callback function is added in the form or added in the field option, execute it with call_user_func.
					if ( isset( $form_fields[ $_POST['child_name'] ]['custom_dropdown_options_source'] ) &&
						! empty( $form_fields[ $_POST['child_name'] ]['custom_dropdown_options_source'] ) &&
						$form_fields[ $_POST['child_name'] ]['custom_dropdown_options_source'] === $ajax_source_func ) {

						$arr_options['field'] = $form_fields[ $_POST['child_name'] ];

						$arr_options['items'] = call_user_func( $ajax_source_func, $arr_options['field']['parent_dropdown_relationship'] );
					} else {
						$arr_options['status']  = 'error';
						$arr_options['message'] = __( 'This is not possible for security reasons.', 'ultimate-member' );
					}
				}

				// phpcs:enable WordPress.Security.NonceVerification
				wp_send_json( $arr_options );
			}
		}


/** Function ajax_delete_cover_photo() called by wp_ajax hooks: {'um_delete_cover_photo'} **/
/** Parameters found in function ajax_delete_cover_photo(): {"request": ["user_id"]} **/
function ajax_delete_cover_photo() {
			UM()->check_ajax_nonce();

			if ( ! array_key_exists( 'user_id', $_REQUEST ) ) {
				wp_send_json_error( __( 'Invalid data', 'ultimate-member' ) );
			}

			$user_id = absint( $_REQUEST['user_id'] );

			if ( ! UM()->roles()->um_current_user_can( 'edit', $user_id ) ) {
				die( esc_html__( 'You can not edit this user', 'ultimate-member' ) );
			}

			UM()->files()->delete_core_user_photo( $user_id, 'cover_photo' );
		}


/** Function ultimatemember_rated() called by wp_ajax hooks: {'um_rated'} **/
/** No params detected :-/ **/


/** Function get_icons() called by wp_ajax hooks: {'um_get_icons'} **/
/** Parameters found in function get_icons(): {"request": ["search", "page"]} **/
function get_icons() {
		UM()->admin()->check_ajax_nonce();

		$search_request = ! empty( $_REQUEST['search'] ) ? sanitize_text_field( $_REQUEST['search'] ) : '';
		$page           = ! empty( $_REQUEST['page'] ) ? absint( $_REQUEST['page'] ) : 1;
		$per_page       = 50;

		UM()->setup()->set_icons_options();

		$um_icons_list = get_option( 'um_icons_list' );
		if ( ! empty( $search_request ) ) {
			$um_icons_list = array_filter(
				$um_icons_list,
				function( $item ) use ( $search_request ) {
					$result = array_filter(
						$item['search'],
						function( $search_item ) use ( $search_request ) {
							return stripos( $search_item, $search_request ) !== false;
						}
					);
					return count( $result ) > 0;
				}
			);
		}

		$total_count = count( $um_icons_list );

		$um_icons_list = array_slice( $um_icons_list, $per_page * ( $page - 1 ), $per_page );

		wp_send_json_success(
			array(
				'icons'       => $um_icons_list,
				'total_count' => $total_count,
			)
		);
	}


/** Function same_page_update_ajax() called by wp_ajax hooks: {'um_same_page_update'} **/
/** Parameters found in function same_page_update_ajax(): {"post": ["cb_func", "page"]} **/
function same_page_update_ajax() {
			UM()->admin()->check_ajax_nonce();

			if ( empty( $_POST['cb_func'] ) ) {
				wp_send_json_error( __( 'Wrong callback', 'ultimate-member' ) );
			}

			$cb_func = sanitize_key( $_POST['cb_func'] );

			if ( 'um_usermeta_fields' === $cb_func ) {
				//first install metatable
				global $wpdb;

				$metakeys = array();
				foreach ( UM()->builtin()->all_user_fields as $all_user_field ) {
					if ( ! array_key_exists( 'metakey', $all_user_field ) ) {
						continue;
					}
					$metakeys[] = $all_user_field['metakey'];
				}

				$metakeys = apply_filters( 'um_metadata_same_page_update_ajax', $metakeys, UM()->builtin()->all_user_fields );

				if ( is_multisite() ) {
					$sites = get_sites( array( 'fields' => 'ids' ) );
					foreach ( $sites as $blog_id ) {
						$metakeys[] = $wpdb->get_blog_prefix( $blog_id ) . 'capabilities';
						$metakeys[] = 'wc_money_spent_' . rtrim( $wpdb->get_blog_prefix( $blog_id ), '_' ); // Is used since Woocommerce 9.1.0
						$metakeys[] = 'wc_order_count_' . rtrim( $wpdb->get_blog_prefix( $blog_id ), '_' ); // Is used since Woocommerce 9.1.0 TODO remove as soon as used 'um_wc_order_count_'
					}
				} else {
					$blog_id    = get_current_blog_id();
					$metakeys[] = $wpdb->get_blog_prefix( $blog_id ) . 'capabilities';
					$metakeys[] = 'wc_money_spent_' . rtrim( $wpdb->get_blog_prefix( $blog_id ), '_' ); // Is used since Woocommerce 9.1.0
					$metakeys[] = 'wc_order_count_' . rtrim( $wpdb->get_blog_prefix( $blog_id ), '_' ); // Is used since Woocommerce 9.1.0 TODO remove as soon as used 'um_wc_order_count_'
				}

				// Member directory data
				$metakeys[] = 'um_member_directory_data';
				$metakeys[] = '_um_verified';
				$metakeys[] = '_money_spent'; // Legacy since Woocommerce 9.1.0. TODO remove as soon as stop support Woo below 9.1.0 version
				$metakeys[] = '_completed';
				$metakeys[] = '_reviews_avg';

				//myCred meta
				if ( function_exists( 'mycred_get_types' ) ) {
					$mycred_types = mycred_get_types();
					if ( ! empty( $mycred_types ) ) {
						foreach ( array_keys( $mycred_types ) as $point_type ) {
							$metakeys[] = $point_type;
						}
					}
				}

				$sortby_custom_keys = $wpdb->get_col( "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} WHERE meta_key='_um_sortby_custom'" );
				if ( empty( $sortby_custom_keys ) ) {
					$sortby_custom_keys = array();
				}

				$sortby_custom_keys2 = $wpdb->get_col( "SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key='_um_sorting_fields'" );
				if ( ! empty( $sortby_custom_keys2 ) ) {
					foreach ( $sortby_custom_keys2 as $custom_val ) {
						$custom_val = maybe_unserialize( $custom_val );

						foreach ( $custom_val as $sort_value ) {
							if ( is_array( $sort_value ) ) {
								$field_keys           = array_keys( $sort_value );
								$sortby_custom_keys[] = $field_keys[0];
							}
						}
					}
				}

				if ( ! empty( $sortby_custom_keys ) ) {
					$sortby_custom_keys = array_unique( $sortby_custom_keys );
					$metakeys           = array_merge( $metakeys, $sortby_custom_keys );
				}

				$skip_fields = UM()->builtin()->get_fields_without_metakey();
				$skip_fields = array_merge( $skip_fields, UM()->member_directory()::$core_search_fields );

				$real_usermeta = $wpdb->get_col( "SELECT DISTINCT meta_key FROM {$wpdb->usermeta}" );
				$real_usermeta = ! empty( $real_usermeta ) ? $real_usermeta : array();
				$real_usermeta = array_merge( $real_usermeta, array( 'um_member_directory_data' ) );

				if ( ! empty( $sortby_custom_keys ) ) {
					$real_usermeta = array_merge( $real_usermeta, $sortby_custom_keys );
				}

				$wp_usermeta_option = array_intersect( array_diff( $metakeys, $skip_fields ), $real_usermeta );

				update_option( 'um_usermeta_fields', array_values( $wp_usermeta_option ) );

				update_option( 'um_member_directory_update_meta', time() );

				UM()->options()->update( 'member_directory_own_table', true );

				wp_send_json_success();
			} elseif ( 'um_get_metadata' === $cb_func ) {
				global $wpdb;

				$wp_usermeta_option = get_option( 'um_usermeta_fields', array() );

				$count = $wpdb->get_var(
					"SELECT COUNT(*)
					FROM {$wpdb->usermeta}
					WHERE meta_key IN ('" . implode( "','", $wp_usermeta_option ) . "')"
				);

				wp_send_json_success( array( 'count' => $count ) );
			} elseif ( 'um_update_metadata_per_page' === $cb_func ) {

				if ( empty( $_POST['page'] ) ) {
					wp_send_json_error( __( 'Wrong data', 'ultimate-member' ) );
				}

				$per_page           = 500;
				$wp_usermeta_option = get_option( 'um_usermeta_fields', array() );

				global $wpdb;
				$metadata = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT *
						FROM {$wpdb->usermeta}
						WHERE meta_key IN ('" . implode( "','", $wp_usermeta_option ) . "')
						LIMIT %d, %d",
						( absint( $_POST['page'] ) - 1 ) * $per_page,
						$per_page
					),
					ARRAY_A
				);

				$values = array();
				foreach ( $metadata as $metarow ) {
					$values[] = $wpdb->prepare( '(%d, %s, %s)', $metarow['user_id'], $metarow['meta_key'], $metarow['meta_value'] );
				}

				// maybe create table.
				$table_name = $wpdb->prefix . 'um_metadata';
				$query      = $wpdb->prepare(
					'SHOW TABLES LIKE %s',
					$wpdb->esc_like( $table_name )
				);
				if ( $wpdb->get_var( $query ) !== $table_name ) {
					UM()->setup()->create_db();
				}

				if ( ! empty( $values ) ) {
					$wpdb->query(
						"INSERT INTO
						{$wpdb->prefix}um_metadata(user_id, um_key, um_value)
						VALUES " . implode( ',', $values )
					);
				}

				$from = ( absint( $_POST['page'] ) * $per_page ) - $per_page + 1;
				$to   = absint( $_POST['page'] ) * $per_page;
				// translators: %1$s is a metadata from name; %2$s is a metadata to.
				wp_send_json_success( array( 'message' => sprintf( __( 'Metadata from %1$s to %2$s was upgraded successfully...', 'ultimate-member' ), $from, $to ) ) );
			} else {
				do_action( 'um_same_page_update_ajax_action', $cb_func );
			}
		}


