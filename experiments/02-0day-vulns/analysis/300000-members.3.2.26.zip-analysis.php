<?php
/***
*
*Found actions: 5
*Found functions:5
*Extracted functions:5
*Total parameter names extracted: 5
*Overview: {'members_dismiss_upgrade_header': {'members_dismiss_upgrade_header'}, 'toggle_addon': {'mbrs_toggle_addon'}, 'dismiss_review_prompt': {'members_dismiss_review_prompt'}, 'reset_roles': {'members_reset_roles'}, 'dismiss': {'members_notification_dismiss'}}
*
***/

/** Function members_dismiss_upgrade_header() called by wp_ajax hooks: {'members_dismiss_upgrade_header'} **/
/** Parameters found in function members_dismiss_upgrade_header(): {"post": ["nonce"]} **/
function members_dismiss_upgrade_header() {

	// Security check
	if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'members_dismiss_upgrade_header' ) ) {
		die();
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array(
			'msg' => esc_html__( 'You are not allowed to make these changes.', 'members' )
		) );
	}
	update_option( 'members_dismiss_upgrade_header', true );
}


/** Function toggle_addon() called by wp_ajax hooks: {'mbrs_toggle_addon'} **/
/** Parameters found in function toggle_addon(): {"post": ["nonce", "addon"]} **/
function toggle_addon() {
		
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'mbrs_toggle_addon' ) ) {
			die();
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array(
				'msg' => esc_html__( 'You are not allowed to make these changes.', 'members' )
			) );
		}
		$addon = ! empty( $_POST['addon'] ) ? sanitize_text_field( $_POST['addon'] ) : false;

		if ( false === $addon ) {
			wp_send_json_error( array(
				'msg' => esc_html__( 'No add-on provided.', 'members' )
			) );
		}

		// Grab the currently active add-ons
		$active_addons = get_option( 'members_active_addons', array() );

		if ( ! in_array( $addon, $active_addons ) ) { // Activate the addon
			$active_addons[] = $addon;
			$response = array(
				'status' => 'active',
				'action_label' => esc_html__( 'Active', 'members' ),
				'msg' => esc_html__( 'Add-on activated', 'members' )
			);

			// Run the add-on's activation hook
			members_plugin()->run_addon_activator( $addon );

		} else { // Deactivate the addon
			$key = array_search( $addon, $active_addons );
			unset( $active_addons[$key] );
			$response = array(
				'status' => 'inactive',
				'action_label' => esc_html__( 'Activate', 'members' ),
				'msg' => esc_html__( 'Add-on deactivated', 'members' )
			);
		}

		update_option( 'members_active_addons', $active_addons );

		wp_send_json_success( $response );
	}


/** Function dismiss_review_prompt() called by wp_ajax hooks: {'members_dismiss_review_prompt'} **/
/** Parameters found in function dismiss_review_prompt(): {"post": ["nonce", "type"]} **/
function dismiss_review_prompt() {

		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'members_dismiss_review_prompt' ) ) {
			die('Failed');
		}

		if ( ! empty( $_POST['type'] ) ) {
			if ( 'remove' === $_POST['type'] ) {

				delete_option( 'members_review_prompt_delay' );

				$members_setttings = get_option( 'members_settings' );
				$members_setttings['review_prompt_removed'] = true;
				update_option( 'members_settings', $members_setttings );

				wp_send_json_success( array(
					'status' => 'removed'
				) );
			} else if ( 'delay' === $_POST['type'] ) {
				update_option( 'members_review_prompt_delay', array(
					'delayed_until' => time() + WEEK_IN_SECONDS
				) );
				wp_send_json_success( array(
					'status' => 'delayed'
				) );
			}
		}
	}


/** Function reset_roles() called by wp_ajax hooks: {'members_reset_roles'} **/
/** Parameters found in function reset_roles(): {"post": ["nonce"]} **/
function reset_roles() {
		
		// Verify nonce
		if ( ! wp_verify_nonce( $_POST['nonce'] ?? null, 'members_reset_roles' ) ) {
			wp_send_json_error();
		}

		// Check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		$default_roles = array( 'administrator', 'editor', 'author', 'contributor', 'subscriber' );

		$members_created_roles = members_get_created_roles();
		$default_role_option   = get_option( 'default_role', 'subscriber' );

		// If the site default is a Members-created role we're about to remove, set default to subscriber.
		if ( in_array( $default_role_option, $members_created_roles, true ) ) {
			update_option( 'default_role', 'subscriber' );
			$default_role_option = 'subscriber';
		}

		// Fallback for reassigning users: use site default if it's a core role, else subscriber.
		$fallback_role = in_array( $default_role_option, $default_roles, true ) ? $default_role_option : 'subscriber';

		foreach ( $members_created_roles as $role_name ) {
			if ( in_array( $role_name, $default_roles, true ) ) {
				continue;
			}
			if ( ! get_role( $role_name ) ) {
				members_untrack_created_role( $role_name );
				continue;
			}
			$users = get_users( array( 'role' => $role_name ) );
			if ( ! empty( $users ) ) {
				foreach ( $users as $user ) {
					if ( count( $user->roles ) <= 1 ) {
						$user->set_role( $fallback_role );
					} else {
						$user->remove_role( $role_name );
					}
				}
			}
			remove_role( $role_name );
			members_untrack_created_role( $role_name );
		}

		// Reset the five default WordPress roles to core defaults.
		foreach ( $default_roles as $role_name ) {
			remove_role( $role_name );
		}

		// Re-add default roles using WordPress core
		require_once( ABSPATH . 'wp-admin/includes/schema.php' );
		populate_roles();

		// Add Members plugin capabilities back to administrator (mirror activation logic)
		$admin_role = get_role( 'administrator' );
		if ( $admin_role ) {
			$admin_role->add_cap( 'restrict_content' ); // Edit per-post content permissions
			$admin_role->add_cap( 'list_roles'       ); // View roles in backend
			if ( ! is_multisite() ) {
				$admin_role->add_cap( 'create_roles' ); // Create new roles
				$admin_role->add_cap( 'delete_roles' ); // Delete existing roles
				$admin_role->add_cap( 'edit_roles'   ); // Edit existing roles/caps
			}
		}

		wp_send_json_success();
	}


/** Function dismiss() called by wp_ajax hooks: {'members_notification_dismiss'} **/
/** Parameters found in function dismiss(): {"post": ["id"]} **/
function dismiss() {

    // Run a security check.
    check_ajax_referer( 'members-admin-notifications', 'nonce' );

    // Check for access and required param.
    if ( ! self::has_access() || empty( $_POST['id'] ) ) {
      wp_send_json_error();
    }

    $id = sanitize_text_field( wp_unslash( $_POST['id'] ) );
    $option = $this->get_option();

    if ( 'all' === $id ) { // Dismiss all notifications

      // Feed notifications
      if ( ! empty( $option['feed'] ) ) {
        foreach ( $option['feed'] as $key => $notification ) {
          $option['dismissed'][$key] = $option['feed'][$key];
          unset( $option['feed'][$key] );
        }
      }

      // Event notifications
      if ( ! empty( $option['events'] ) ) {
        foreach ( $option['events'] as $key => $notification ) {
          $option['dismissed'][$key] = $option['events'][$key];
          unset( $option['events'][$key] );
        }
      }

    } else { // Dismiss one notification

      // Event notifications need a prefix to distinguish them from feed notifications
      // For a naming convention, we'll use "event_{timestamp}"
      // If the notification ID includes "event_", we know it's an even notification
      $type = false !== strpos( $id, 'event_' ) ? 'events' : 'feed';

      if( $type == 'events' ){
        if( !empty($option[$type]) ){
            foreach( $option[$type] as $index => $event_notification ){
               if( $event_notification['id'] == $id ){
                  unset( $option[$type][$index] );
                  break;
               }
            }
        }
      }else{
        if ( ! empty( $option[$type][$id] ) ) {
          $option['dismissed'][$id] = $option[$type][$id];
          unset( $option[$type][$id] );
        }
      }
    }


    update_option( 'members_notifications', $option );

    wp_send_json_success();
  }


