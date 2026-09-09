<?php
/***
*
*Found actions: 9
*Found functions:5
*Extracted functions:5
*Total parameter names extracted: 1
*Overview: {'admin_ajax': {'pods_admin', 'pods_admin_components', 'nopriv_pods_admin'}, 'admin_ajax_oembed_update_preview': {'oembed_update_preview'}, 'admin_ajax_relationship': {'pods_relationship', 'nopriv_pods_relationship'}, 'pods_ajax_pq_loadpod': {'pq_loadpod'}, 'admin_ajax_upload': {'nopriv_pods_upload', 'pods_upload'}}
*
***/

/** Function admin_ajax() called by wp_ajax hooks: {'pods_admin', 'pods_admin_components', 'nopriv_pods_admin'} **/
/** No params detected :-/ **/


/** Function admin_ajax_oembed_update_preview() called by wp_ajax hooks: {'oembed_update_preview'} **/
/** No params detected :-/ **/


/** Function admin_ajax_relationship() called by wp_ajax hooks: {'pods_relationship', 'nopriv_pods_relationship'} **/
/** No params detected :-/ **/


/** Function pods_ajax_pq_loadpod() called by wp_ajax hooks: {'pq_loadpod'} **/
/** No params detected :-/ **/


/** Function admin_ajax_upload() called by wp_ajax hooks: {'nopriv_pods_upload', 'pods_upload'} **/
/** Parameters found in function admin_ajax_upload(): {"files": ["Filedata"]} **/
function admin_ajax_upload() {

		pods_session_start();

		// Sanitize input @codingStandardsIgnoreLine
		$params = pods_unslash( (array) $_POST );

		foreach ( $params as $key => $value ) {
			if ( 'action' === $key ) {
				continue;
			}

			unset( $params[ $key ] );

			$params[ str_replace( '_podsfix_', '', $key ) ] = $value;
		}

		$params = (object) $params;

		$methods = [
			'upload',
		];

		if ( ! isset( $params->method ) || ! in_array( $params->method, $methods, true ) ) {
			return pods_error( __( 'Invalid AJAX request', 'pods' ), PodsInit::$admin );
		} elseif ( ! empty( $params->pod_name ) && empty( $params->field_name ) ) {
			return pods_error( __( 'Invalid AJAX request', 'pods' ), PodsInit::$admin );
		} elseif ( empty( $params->pod_name ) && ! current_user_can( 'upload_files' ) ) {
			return pods_error( __( 'Invalid AJAX request', 'pods' ), PodsInit::$admin );
		}

		/**
		 * Access Checking
		 */
		$upload_disabled   = false;
		$is_user_logged_in = is_user_logged_in();

		if ( defined( 'PODS_DISABLE_FILE_UPLOAD' ) && true === PODS_DISABLE_FILE_UPLOAD ) {
			$upload_disabled = true;
		} elseif ( ! $is_user_logged_in && defined( 'PODS_UPLOAD_REQUIRE_LOGIN' ) ) {
			if ( true === PODS_UPLOAD_REQUIRE_LOGIN ) {
				$upload_disabled = true;
			} elseif ( is_string( PODS_UPLOAD_REQUIRE_LOGIN ) && ! current_user_can( PODS_UPLOAD_REQUIRE_LOGIN ) ) {
				$upload_disabled = true;
			}
		}

		if ( true === $upload_disabled ) {
			return pods_error( __( 'Unauthorized request', 'pods' ), PodsInit::$admin );
		}

		if ( ! isset( $params->_wpnonce, $params->pod_name, $params->field_name, $params->uri_hash, $params->id ) ) {
			return pods_error( __( 'Unauthorized request', 'pods' ), PodsInit::$admin );
		}

		$_wpnonce   = $params->_wpnonce;
		$pod_name   = $params->pod_name;
		$field_name = $params->field_name;
		$uri_hash   = $params->uri_hash;
		$id         = (int) $params->id;

		$parent_post_id = (int) pods_v( 'post_id', $params, 0 );

		// Set as param for back-compat reference.
		$params->post_id = $parent_post_id;

		$uid = pods_session_id();

		if ( is_user_logged_in() ) {
			$uid = 'user_' . get_current_user_id();
		}

		$nonce_name = 'pods_upload:' . json_encode( compact( 'pod_name', 'field_name', 'uid', 'uri_hash', 'id', 'parent_post_id' ) );

		if ( false === wp_verify_nonce( $_wpnonce, $nonce_name ) ) {
			return pods_error( __( 'Unauthorized request', 'pods' ), PodsInit::$admin );
		}

		if ( empty( self::$api ) ) {
			self::$api = pods_api();
		}

		$pod = self::$api->load_pod( [
			'name' => $pod_name,
		] );

		if ( ! $pod ) {
			return pods_error( __( 'Invalid Pod configuration', 'pods' ), PodsInit::$admin );
		}

		$field = $pod->get_field( $field_name );

		if ( ! $field ) {
			return pods_error( __( 'Invalid Field configuration', 'pods' ), PodsInit::$admin );
		}

		if ( ! $field->is_file() ) {
			return pods_error( __( 'Invalid field', 'pods' ), PodsInit::$admin );
		}

		if ( empty( self::$api ) ) {
			self::$api = pods_api();
		}

		self::$api->display_errors = false;

		$method = $params->method;

		// Cleaning up $params
		unset( $params->action, $params->method, $params->_wpnonce );

		/**
		 * Upload a new file (advanced - returns URL and ID)
		 */
		if ( 'upload' === $method ) {
			$file = $_FILES['Filedata'];

			$limit_size = pods_v( $field['type'] . '_restrict_filesize', $field );

			if ( ! empty( $limit_size ) ) {
				if ( false !== stripos( $limit_size, 'GB' ) ) {
					$limit_size = (float) trim( str_ireplace( 'GB', '', $limit_size ) );
					$limit_size = $limit_size * 1025 * 1025 * 1025;
					// convert to MB to KB to B
				} elseif ( false !== stripos( $limit_size, 'MB' ) ) {
					$limit_size = (float) trim( str_ireplace( 'MB', '', $limit_size ) );
					$limit_size = $limit_size * 1025 * 1025;
					// convert to KB to B
				} elseif ( false !== stripos( $limit_size, 'KB' ) ) {
					$limit_size = (float) trim( str_ireplace( 'KB', '', $limit_size ) );
					$limit_size *= 1025;
					// convert to B
				} elseif ( false !== stripos( $limit_size, 'B' ) ) {
					$limit_size = (float) trim( str_ireplace( 'B', '', $limit_size ) );
				} else {
					$limit_size = wp_max_upload_size();
				}

				if ( 0 < $limit_size && $limit_size < $file['size'] ) {
					$error = sprintf(
						// translators: %s is the maximum file size allowed.
						__( 'Error: File size too large, max size is %s', 'pods' ),
						pods_v( $field['type'] . '_restrict_filesize', $field )
					);

					return pods_error( '<div style="color:#FF0000">' . $error . '</div>' );
				}
			}//end if

			$file_mime_types = $this->get_file_mime_types_for_field( $field );

			if ( null !== $file_mime_types ) {
				$file_mime_types_extensions = $file_mime_types['extensions'];
				$file_mime_types_mapping    = $file_mime_types['mapping'];

				$file_info = pathinfo( $file['name'] );
				$ok        = false;

				if ( isset( $file_info['extension'] ) ) {
					// Enforce lowercase for the extension checking.
					$file_info['extension'] = strtolower( $file_info['extension'] );

					$ok = isset( $file_mime_types_mapping[ $file_info['extension'] ] );
				}

				if ( false === $ok ) {
					$error = sprintf(
						// translators: %s is a comma-separated list of allowed file extensions.
						__( 'Error: File type not allowed, please use one of the following: %s', 'pods' ),
						'.' . implode( ', .', $file_mime_types_extensions )
					);

					return pods_error( '<div style="color:#FF0000"><p>' . esc_html( $error ) . '</p></div>' );
				}

				// Confirm mime type if we can.
				if ( ! empty( $file_mime_types_mapping[ $file_info['extension'] ] ) ) {
					if ( 0 === strpos( (string) $file_mime_types_mapping[ $file_info['extension'] ], 'image/' ) ) {
						$real_mime = wp_get_image_mime( $file['tmp_name'] );
					} elseif ( extension_loaded( 'fileinfo' ) ) {
						// Use finfo to get the mime type information.
						$finfo_resource = finfo_open( FILEINFO_MIME_TYPE );
						$real_mime      = finfo_file( $finfo_resource, $file['tmp_name'] );
						finfo_close( $finfo_resource );
					} else {
						// No other validation we can do, just make the mime type match to bypass the next check.
						$real_mime = $file_mime_types_mapping[ $file_info['extension'] ];
					}

					// Do not allow if the mime type was found and it does not match.
					if ( $real_mime && $real_mime !== $file_mime_types_mapping[ $file_info['extension'] ] ) {
						$error = sprintf(
							// translators: %1$s is the detected mime type, %2$s is the expected mime type with extension.
							__( 'Error: File mime type "%1$s" not expected, please ensure your file is valid: %2$s', 'pods' ),
							$real_mime,
							'.' . $file_info['extension'] . ' (' . $file_mime_types_mapping[ $file_info['extension'] ] . ')'
						);

						return pods_error( '<div style="color:#FF0000"><p>' . esc_html( $error ) . '</p></div>' );
					}
				}
			}//end if

			$custom_handler = apply_filters( 'pods_upload_handle', null, 'Filedata', $parent_post_id, $params, $field );

			if ( null === $custom_handler ) {

				// Start custom directory.
				$upload_dir = pods_v( $field['type'] . '_upload_dir', $field, 'wp' );

				if ( 'wp' !== $upload_dir ) {
					$custom_dir  = pods_v( $field['type'] . '_upload_dir_custom', $field, '' );
					$context_pod = null;

					if ( $parent_post_id ) {
						$context_pod = pods_get_instance( pods_v( 'name', $pod, false ), $parent_post_id );

						if ( ! $context_pod->exists() ) {
							$context_pod = null;
						}
					}

					/**
					 * Filter the custom upload directory Pod context.
					 *
					 * @since 2.7.28
					 *
					 * @param Pods   $context_pod The Pods object of the associated pod for the post type.
					 * @param object $params      The POSTed parameters for the request.
					 * @param array  $field       The field configuration associated to the upload field.
					 * @param array  $pod         The pod configuration associated to the upload field.
					 */
					$context_pod = apply_filters( 'pods_upload_dir_custom_context_pod', $context_pod, $params, $field, $pod );

					$custom_dir = pods_evaluate_tags( $custom_dir, [ 'pod' => $context_pod ] );

					/**
					 * Filter the custom Pod upload directory.
					 *
					 * @since 2.7.28
					 *
					 * @param string $custom_dir  The directory to use for the uploaded file.
					 * @param object $params      The POSTed parameters for the request.
					 * @param Pods   $context_pod The Pods object of the associated pod for the post type.
					 * @param array  $field       The field configuration associated to the upload field.
					 * @param array  $pod         The pod configuration associated to the upload field.
					 */
					$custom_dir = apply_filters( 'pods_upload_dir_custom', $custom_dir, $params, $context_pod, $field, $pod );

					self::$tmp_upload_dir = $custom_dir;

					add_filter( 'upload_dir', [ $this, 'filter_upload_dir' ] );
				}

				// Upload file.
				$attachment_id = media_handle_upload( 'Filedata', $parent_post_id );

				// End custom directory.
				if ( 'wp' !== $upload_dir ) {
					remove_filter( 'upload_dir', [ $this, 'filter_upload_dir' ] );

					self::$tmp_upload_dir = null;
				}

				if ( is_object( $attachment_id ) ) {
					$errors = [];

					foreach ( $attachment_id->errors['upload_error'] as $error_code => $error_message ) {
						$errors[] = '[' . $error_code . '] ' . $error_message;
					}

					return pods_error( '<div style="color:#FF0000">Error: ' . implode( '</div><div>', $errors ) . '</div>' );
				} else {
					$attachment = get_post( $attachment_id, ARRAY_A );

					$attachment['filename'] = basename( $attachment['guid'] );

					$thumb = wp_get_attachment_image_src( $attachment['ID'], 'thumbnail', true );

					$attachment['thumbnail'] = '';

					if ( ! empty( $thumb[0] ) ) {
						$attachment['thumbnail'] = $thumb[0];
					}

					$attachment['link']      = get_permalink( $attachment['ID'] );
					$attachment['edit_link'] = get_edit_post_link( $attachment['ID'] );
					$attachment['download']  = wp_get_attachment_url( $attachment['ID'] );

					$attachment = apply_filters( 'pods_upload_attachment', $attachment, $params->item_id );

					wp_send_json( $attachment );
				}//end if
			}//end if
		}//end if

		die();
		// KBAI!
	}


