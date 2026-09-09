<?php
/***
*
*Found actions: 19
*Found functions:18
*Extracted functions:16
*Total parameter names extracted: 12
*Overview: {'ajax_get_submission': {'asenha_contact_form_get_submission'}, 'get_svg_attachment_url': {'svg_get_attachment_url'}, 'asenha_dismiss_support_nudge': {'dismiss_support_nudge'}, 'save_admin_menu': {'save_admin_menu'}, 'save_custom_menu_order': {'save_custom_menu_order'}, 'ajax_toggle_disabled': {'asenha_user_account_toggle_disabled'}, 'save_hidden_menu_items': {'save_hidden_menu_items'}, 'asenha_release_login_lock': {'asenha_release_login_lock'}, 'dismiss_smtp_password_admin_notice': {'asenha_dismiss_smtp_password_notice'}, 'asenha_have_supported': {'have_supported'}, 'dismiss_recovery_url_refreshed_notice': {'asenha_dismiss_view_admin_as_recovery_notice'}, 'ajax_delete_submission': {'asenha_contact_form_delete_submission'}, 'asenha_dismiss_upgrade_nudge': {'dismiss_upgrade_nudge'}, 'reset_admin_menu': {'reset_admin_menu'}, 'handle_submit': {'nopriv_asenha_contact_form_submit', 'asenha_contact_form_submit'}, 'send_test_email': {'send_test_email'}, 'save_custom_content_order': {'save_custom_order'}, 'asenha_dismiss_promo_nudge': {'dismiss_promo_nudge'}}
*
***/

/** Function ajax_get_submission() called by wp_ajax hooks: {'asenha_contact_form_get_submission'} **/
/** Parameters found in function ajax_get_submission(): {"post": ["submission_id"]} **/
function ajax_get_submission() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Unauthorized request.', 'admin-site-enhancements' ) ) );
		}

		check_ajax_referer( 'asenha_contact_form_admin', 'nonce' );

		$submission_id = isset( $_POST['submission_id'] ) ? absint( $_POST['submission_id'] ) : 0;
		$submission    = Contact_Form_Submission::get( $submission_id );

		if ( empty( $submission ) ) {
			wp_send_json_error( array( 'message' => __( 'Submission not found.', 'admin-site-enhancements' ) ) );
		}

		$html  = '<dl class="asenha-cf-submission-details">';
		$html .= '<dt>' . esc_html__( 'Subject', 'admin-site-enhancements' ) . '</dt>';
		$html .= '<dd>' . esc_html( $submission['subject'] ) . '</dd>';
		$html .= '<dt>' . esc_html__( 'Message', 'admin-site-enhancements' ) . '</dt>';
		$html .= '<dd class="asenha-cf-submission-message">' . nl2br( esc_html( $submission['message'] ) ) . '</dd>';
		$html .= '<dt>' . esc_html__( 'Name', 'admin-site-enhancements' ) . '</dt>';
		$html .= '<dd>' . esc_html( $submission['name'] ) . '</dd>';
		$html .= '<dt>' . esc_html__( 'Email', 'admin-site-enhancements' ) . '</dt>';
		$html .= '<dd><a href="mailto:' . esc_attr( $submission['email'] ) . '">' . esc_html( $submission['email'] ) . '</a></dd>';
		$html .= '<dt>' . esc_html__( 'Date', 'admin-site-enhancements' ) . '</dt>';
		$html .= '<dd>' . esc_html( Contact_Form_Submission::format_submission_date( $submission['created_at'] ) ) . '</dd>';
		$html .= '</dl>';

		wp_send_json_success(
			array(
				'html' => $html,
			)
		);
	}


/** Function get_svg_attachment_url() called by wp_ajax hooks: {'svg_get_attachment_url'} **/
/** Parameters found in function get_svg_attachment_url(): {"request": ["attachmentID"]} **/
function get_svg_attachment_url() {

        $attachment_url = '';
        $attachment_id = isset( $_REQUEST['attachmentID'] ) ? $_REQUEST['attachmentID'] : '';

        // Check response mime type
        if ( $attachment_id ) {

            echo esc_url( wp_get_attachment_url( $attachment_id ) );

            die();

        }

    }


/** Function asenha_dismiss_support_nudge() called by wp_ajax hooks: {'dismiss_support_nudge'} **/
/** Parameters found in function asenha_dismiss_support_nudge(): {"request": ["nonce"]} **/
function asenha_dismiss_support_nudge() {
    if ( isset( $_REQUEST ) && current_user_can( 'manage_options' ) ) {
        if ( wp_verify_nonce( sanitize_text_field( $_REQUEST['nonce'] ), 'asenha-' . get_current_user_id() ) ) {
            $asenha_stats = asenha_get_option_array( ASENHA_SLUG_U . '_stats', false );
            $asenha_stats['support_nudge_dismissed'] = true;
            $success = update_option( ASENHA_SLUG_U . '_stats', $asenha_stats, false );
            if ( $success ) {
                echo json_encode( array(
                    'success' => true,
                ) );
            } else {
                echo json_encode( array(
                    'success' => false,
                ) );
            }
        }
    }
}


/** Function save_admin_menu() called by wp_ajax hooks: {'save_admin_menu'} **/
/** Parameters found in function save_admin_menu(): {"request": ["custom_menu_order", "custom_menu_titles", "custom_menu_hidden"]} **/
function save_admin_menu() {
        if ( isset( $_REQUEST ) ) {
            if ( check_ajax_referer( 'save-menu-nonce', 'nonce', false ) ) {
                if ( !current_user_can( 'manage_options' ) ) {
                    wp_send_json_error( array(
                        'message' => __( 'You do not have permission to perform this action.', 'admin-site-enhancements' ),
                    ) );
                }
                $options_extra = get_option( ASENHA_SLUG_U . '_extra', array() );
                $options = ( isset( $options_extra['admin_menu'] ) ? $options_extra['admin_menu'] : array() );
                $options['custom_menu_order'] = ( isset( $_REQUEST['custom_menu_order'] ) ? wp_unslash( $_REQUEST['custom_menu_order'] ) : $options['custom_menu_order'] );
                $options['custom_menu_titles'] = ( isset( $_REQUEST['custom_menu_titles'] ) ? wp_unslash( $_REQUEST['custom_menu_titles'] ) : $options['custom_menu_titles'] );
                $options['custom_menu_hidden'] = ( isset( $_REQUEST['custom_menu_hidden'] ) ? wp_unslash( $_REQUEST['custom_menu_hidden'] ) : $options['custom_menu_hidden'] );
                $options_extra['admin_menu'] = $options;
                // vi( $options_extra, '', 'save menu' );
                $updated = update_option( ASENHA_SLUG_U . '_extra', $options_extra, true );
                wp_send_json_success( array(
                    'status'  => ( $updated ? 'success' : 'unchanged' ),
                    'updated' => (bool) $updated,
                ) );
            }
        }
    }


/** Function save_custom_menu_order() called by wp_ajax hooks: {'save_custom_menu_order'} **/
/** No function found :-/ **/


/** Function ajax_toggle_disabled() called by wp_ajax hooks: {'asenha_user_account_toggle_disabled'} **/
/** Parameters found in function ajax_toggle_disabled(): {"post": ["user_id", "set_disabled"]} **/
function ajax_toggle_disabled() {
		check_ajax_referer( 'asenha_user_account_toggle', 'nonce' );

		if ( ! current_user_can( 'list_users' ) ) {
			wp_send_json_error( array( 'message' => __( 'Sorry, you are not allowed to do this.', 'admin-site-enhancements' ) ), 403 );
		}

		$user_id       = isset( $_POST['user_id'] ) ? absint( $_POST['user_id'] ) : 0;
		$set_disabled  = isset( $_POST['set_disabled'] ) ? (bool) filter_var( wp_unslash( $_POST['set_disabled'] ), FILTER_VALIDATE_BOOLEAN ) : false;

		if ( $user_id <= 0 || ! $this->current_user_may_toggle_user( $user_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Sorry, you are not allowed to modify this user.', 'admin-site-enhancements' ) ), 403 );
		}

		if ( $set_disabled ) {
			update_user_meta( $user_id, self::META_KEY, '1' );
		} else {
			delete_user_meta( $user_id, self::META_KEY );
		}

		$is_disabled = $this->is_user_disabled( $user_id );

		if ( $is_disabled ) {
			$action_label = __( 'Enable', 'admin-site-enhancements' );
		} else {
			$action_label = __( 'Disable', 'admin-site-enhancements' );
		}

		wp_send_json_success(
			array(
				'is_disabled'       => $is_disabled,
				'status_html'       => $is_disabled ? esc_html__( 'Disabled', 'admin-site-enhancements' ) : '',
				'action_label'      => $action_label,
				'next_set_disabled' => $is_disabled ? 0 : 1,
			)
		);
	}


/** Function save_hidden_menu_items() called by wp_ajax hooks: {'save_hidden_menu_items'} **/
/** No function found :-/ **/


/** Function asenha_release_login_lock() called by wp_ajax hooks: {'asenha_release_login_lock'} **/
/** Parameters found in function asenha_release_login_lock(): {"request": ["nonce", "ip_address"]} **/
function asenha_release_login_lock() {
    if ( !current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array(
            'message' => __( 'Insufficient permissions.', 'admin-site-enhancements' ),
        ) );
    }
    if ( !isset( $_REQUEST['nonce'] ) || !isset( $_REQUEST['ip_address'] ) ) {
        wp_send_json_error( array(
            'message' => __( 'Missing required data.', 'admin-site-enhancements' ),
        ) );
    }
    $nonce = sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) );
    if ( !wp_verify_nonce( $nonce, 'asenha-' . get_current_user_id() ) ) {
        wp_send_json_error( array(
            'message' => __( 'Invalid security token.', 'admin-site-enhancements' ),
        ) );
    }
    $ip_address = sanitize_text_field( wp_unslash( $_REQUEST['ip_address'] ) );
    $common_methods = new \ASENHA\Classes\Common_Methods();
    if ( !$common_methods->is_ip_valid( $ip_address ) ) {
        wp_send_json_error( array(
            'message' => __( 'Invalid IP address.', 'admin-site-enhancements' ),
        ) );
    }
    global $wpdb;
    $table_name = $wpdb->prefix . 'asenha_failed_logins';
    $deleted = $wpdb->delete( $table_name, array(
        'ip_address' => $ip_address,
    ), array('%s') );
    if ( false === $deleted ) {
        wp_send_json_error( array(
            'message' => __( 'Could not release lock.', 'admin-site-enhancements' ),
        ) );
    }
    wp_send_json_success( array(
        'deleted' => (int) $deleted,
    ) );
}


/** Function dismiss_smtp_password_admin_notice() called by wp_ajax hooks: {'asenha_dismiss_smtp_password_notice'} **/
/** No params detected :-/ **/


/** Function asenha_have_supported() called by wp_ajax hooks: {'have_supported'} **/
/** Parameters found in function asenha_have_supported(): {"request": ["nonce"]} **/
function asenha_have_supported() {
    if ( isset( $_REQUEST ) && current_user_can( 'manage_options' ) ) {
        if ( wp_verify_nonce( sanitize_text_field( $_REQUEST['nonce'] ), 'asenha-' . get_current_user_id() ) ) {
            $asenha_stats = asenha_get_option_array( ASENHA_SLUG_U . '_stats', false );
            $asenha_stats['have_supported'] = true;
            $asenha_stats['support_nudge_dismissed'] = true;
            $success = update_option( ASENHA_SLUG_U . '_stats', $asenha_stats, false );
            if ( $success ) {
                echo json_encode( array(
                    'success' => true,
                ) );
            } else {
                echo json_encode( array(
                    'success' => false,
                ) );
            }
        }
    }
}


/** Function dismiss_recovery_url_refreshed_notice() called by wp_ajax hooks: {'asenha_dismiss_view_admin_as_recovery_notice'} **/
/** No params detected :-/ **/


/** Function ajax_delete_submission() called by wp_ajax hooks: {'asenha_contact_form_delete_submission'} **/
/** Parameters found in function ajax_delete_submission(): {"post": ["submission_id", "delete_nonce"]} **/
function ajax_delete_submission() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Unauthorized request.', 'admin-site-enhancements' ) ) );
		}

		$submission_id = isset( $_POST['submission_id'] ) ? absint( $_POST['submission_id'] ) : 0;
		$nonce         = isset( $_POST['delete_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['delete_nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'asenha_cf_delete_' . $submission_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid security token.', 'admin-site-enhancements' ) ) );
		}

		if ( ! Contact_Form_Submission::delete( $submission_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Unable to delete this submission.', 'admin-site-enhancements' ) ) );
		}

		wp_send_json_success(
			array(
				'message' => __( 'Submission deleted.', 'admin-site-enhancements' ),
			)
		);
	}


/** Function asenha_dismiss_upgrade_nudge() called by wp_ajax hooks: {'dismiss_upgrade_nudge'} **/
/** Parameters found in function asenha_dismiss_upgrade_nudge(): {"request": ["nonce"]} **/
function asenha_dismiss_upgrade_nudge() {
    if ( isset( $_REQUEST ) && current_user_can( 'manage_options' ) ) {
        if ( wp_verify_nonce( sanitize_text_field( $_REQUEST['nonce'] ), 'asenha-' . get_current_user_id() ) ) {
            $current_date = date( 'Y-m-d', time() );
            $asenha_stats = asenha_get_option_array( ASENHA_SLUG_U . '_stats', false );
            $asenha_stats['upgrade_nudge_dismissed'] = true;
            $asenha_stats['upgrade_nudge_dismissed_date'] = $current_date;
            $success = update_option( ASENHA_SLUG_U . '_stats', $asenha_stats, false );
            if ( $success ) {
                echo json_encode( array(
                    'success' => true,
                ) );
            } else {
                echo json_encode( array(
                    'success' => false,
                ) );
            }
        }
    }
}


/** Function reset_admin_menu() called by wp_ajax hooks: {'reset_admin_menu'} **/
/** No params detected :-/ **/


/** Function handle_submit() called by wp_ajax hooks: {'nopriv_asenha_contact_form_submit', 'asenha_contact_form_submit'} **/
/** No params detected :-/ **/


/** Function send_test_email() called by wp_ajax hooks: {'send_test_email'} **/
/** Parameters found in function send_test_email(): {"request": ["email_to", "nonce"]} **/
function send_test_email() {
        if ( isset( $_REQUEST['email_to'] ) && isset( $_REQUEST['nonce'] ) && current_user_can( 'manage_options' ) ) {
            if ( wp_verify_nonce( sanitize_text_field( $_REQUEST['nonce'] ), 'send-test-email-nonce_' . get_current_user_id() ) ) {
                $options = get_option( ASENHA_SLUG_U, array() );
                $smtp_host = ( isset( $options['smtp_host'] ) ? $options['smtp_host'] : '' );
                $smtp_port = ( isset( $options['smtp_port'] ) ? $options['smtp_port'] : '' );
                $smtp_security = ( isset( $options['smtp_security'] ) ? $options['smtp_security'] : '' );
                $smtp_authentication = ( isset( $options['smtp_authentication'] ) ? $options['smtp_authentication'] : 'enable' );
                $smtp_password = ( isset( $options['smtp_password'] ) ? $options['smtp_password'] : '' );
                $smtp_password_status = $this->get_smtp_password_status( $smtp_password );
                $smtp_is_configured = !empty( $smtp_host ) && !empty( $smtp_port ) && !empty( $smtp_security );
                $runtime_smtp_password = $this->get_smtp_password_for_runtime( $smtp_password );
                if ( $smtp_is_configured && 'enable' === $smtp_authentication && (self::SMTP_PASSWORD_STATUS_ENCRYPTED_INVALID === $smtp_password_status || '' === $runtime_smtp_password || $this->is_probable_smtp_ciphertext( $runtime_smtp_password )) ) {
                    wp_send_json( array_merge( array(
                        'status'  => 'failed',
                        'message' => $this->get_smtp_password_reentry_message(),
                    ), $this->get_smtp_password_ajax_ui_fields( $options ) ) );
                }
                $content = array(
                    array(
                        'title' => 'Hey... are you getting this?',
                        'body'  => '<p><strong>Looks like you did!</strong></p>',
                    ),
                    array(
                        'title' => 'There\'s a message for you...',
                        'body'  => '<p><strong>Here it is:</strong></p>',
                    ),
                    array(
                        'title' => 'Is it working?',
                        'body'  => '<p><strong>Yes, it\'s working!</strong></p>',
                    ),
                    array(
                        'title' => 'Hope you\'re getting this...',
                        'body'  => '<p><strong>Looks like this was sent out just fine and you got it.</strong></p>',
                    ),
                    array(
                        'title' => 'Testing delivery configuration...',
                        'body'  => '<p><strong>Everything looks good!</strong></p>',
                    ),
                    array(
                        'title' => 'Testing email delivery',
                        'body'  => '<p><strong>Looks good!</strong></p>',
                    ),
                    array(
                        'title' => 'Config is looking good',
                        'body'  => '<p><strong>Seems like everything has been set up properly!</strong></p>',
                    ),
                    array(
                        'title' => 'All set up',
                        'body'  => '<p><strong>Your configuration is working properly.</strong></p>',
                    ),
                    array(
                        'title' => 'Good to go',
                        'body'  => '<p><strong>Config is working great.</strong></p>',
                    ),
                    array(
                        'title' => 'Good job',
                        'body'  => '<p><strong>Everything is set.</strong></p>',
                    )
                );
                $random_number = rand( 0, count( $content ) - 1 );
                $to = sanitize_email( wp_unslash( $_REQUEST['email_to'] ) );
                $title = $content[$random_number]['title'];
                $body = $content[$random_number]['body'] . '<p>This message was sent from <a href="' . get_bloginfo( 'url' ) . '">' . get_bloginfo( 'url' ) . '</a> on ' . wp_date( 'F j, Y' ) . ' at ' . wp_date( 'H:i:s' ) . ' via ASE.</p>';
                $headers = array('Content-Type: text/html; charset=UTF-8');
                $success = wp_mail(
                    $to,
                    $title,
                    $body,
                    $headers
                );
                $ui_fields = $this->get_smtp_password_ajax_ui_fields( $options );
                if ( $success ) {
                    $response = array_merge( array(
                        'status' => 'success',
                    ), $ui_fields );
                } else {
                    $response = array_merge( array(
                        'status' => 'failed',
                    ), $ui_fields );
                }
                wp_send_json( $response );
            }
        }
    }


/** Function save_custom_content_order() called by wp_ajax hooks: {'save_custom_order'} **/
/** Parameters found in function save_custom_content_order(): {"post": ["nonce", "action", "item_parent", "post_id", "menu_order", "post_type", "more_posts"]} **/
function save_custom_content_order() {
        global $wpdb;
        // Check user capabilities
        if ( !current_user_can( 'edit_others_posts' ) ) {
            wp_send_json( 'Something went wrong.' );
        }
        // Verify nonce
        if ( !wp_verify_nonce( $_POST['nonce'], 'order_sorting_nonce' ) ) {
            wp_send_json( 'Something went wrong.' );
        }
        // Get ajax variables
        $action = ( isset( $_POST['action'] ) ? $_POST['action'] : '' );
        // Item parent is currently 0, as we only handle sorting of non-child posts
        $item_parent = ( isset( $_POST['item_parent'] ) ? absint( $_POST['item_parent'] ) : 0 );
        $post_id = ( isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0 );
        $item_menu_order = ( isset( $_POST['menu_order'] ) ? absint( $_POST['menu_order'] ) : 0 );
        $post_type = ( isset( $_POST['post_type'] ) ? sanitize_key( wp_unslash( $_POST['post_type'] ) ) : false );
        $is_hierarchical_post_type = ( $post_type ? is_post_type_hierarchical( $post_type ) : false );
        $allow_parent_updates = 'attachment' === $post_type || $is_hierarchical_post_type;
        if ( !$allow_parent_updates ) {
            $item_parent = 0;
        }
        // Make processing faster by removing certain actions
        remove_action( 'pre_post_update', 'wp_save_post_revision' );
        // $response array for ajax response
        $response = array();
        if ( 'attachment' == $post_type ) {
            $post_status = array('inherit', 'private');
        } else {
            $post_status = array(
                'publish',
                'future',
                'draft',
                'pending',
                'private'
            );
        }
        if ( $post_id > 0 && !isset( $_POST['more_posts'] ) && $post_type ) {
            // Load siblings (including the moved post) for a full 0..N splice reindex.
            $query_args = array(
                'post_type'              => $post_type,
                'orderby'                => 'menu_order title',
                'order'                  => 'ASC',
                'posts_per_page'         => -1,
                'suppress_filters'       => true,
                'ignore_sticky_posts'    => true,
                'post_status'            => $post_status,
                'update_post_term_cache' => false,
                'update_post_meta_cache' => false,
            );
            if ( 'attachment' === $post_type ) {
                // Media items can be attached to other posts; do not filter by post_parent.
            } elseif ( $is_hierarchical_post_type ) {
                $query_args['post_parent'] = $item_parent;
            }
            $posts_query = new WP_Query($query_args);
            $siblings = ( is_array( $posts_query->posts ) ? $posts_query->posts : array() );
            $moved_post = null;
            $ordered = array();
            foreach ( $siblings as $post ) {
                if ( (int) $post->ID === (int) $post_id ) {
                    $moved_post = $post;
                    continue;
                }
                $ordered[] = $post;
            }
            if ( !$moved_post ) {
                $moved_post = get_post( $post_id );
            }
            if ( $moved_post ) {
                $insert_at = min( max( 0, (int) $item_menu_order ), count( $ordered ) );
                array_splice(
                    $ordered,
                    $insert_at,
                    0,
                    array($moved_post)
                );
                foreach ( $ordered as $menu_order => $post ) {
                    $wpdb->update( $wpdb->posts, array(
                        'menu_order' => (int) $menu_order,
                    ), array(
                        'ID' => $post->ID,
                    ) );
                    clean_post_cache( $post->ID );
                }
            }
        }
        $this->finish_custom_content_order_save(
            $response,
            $post_type,
            $post_id,
            $post_status,
            $item_parent,
            $is_hierarchical_post_type
        );
    }


/** Function asenha_dismiss_promo_nudge() called by wp_ajax hooks: {'dismiss_promo_nudge'} **/
/** Parameters found in function asenha_dismiss_promo_nudge(): {"request": ["nonce"]} **/
function asenha_dismiss_promo_nudge() {
    if ( isset( $_REQUEST ) && current_user_can( 'manage_options' ) ) {
        if ( wp_verify_nonce( sanitize_text_field( $_REQUEST['nonce'] ), 'asenha-' . get_current_user_id() ) ) {
            $current_date = date( 'Y-m-d', time() );
            $asenha_stats = asenha_get_option_array( ASENHA_SLUG_U . '_stats', false );
            $asenha_stats['promo_nudge_dismissed'] = true;
            $asenha_stats['promo_nudge_dismissed_date'] = $current_date;
            $success = update_option( ASENHA_SLUG_U . '_stats', $asenha_stats, false );
            if ( $success ) {
                echo json_encode( array(
                    'success' => true,
                ) );
            } else {
                echo json_encode( array(
                    'success' => false,
                ) );
            }
        }
    }
}


