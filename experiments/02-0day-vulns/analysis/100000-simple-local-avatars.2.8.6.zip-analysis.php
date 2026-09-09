<?php
/***
*
*Found actions: 4
*Found functions:4
*Extracted functions:4
*Total parameter names extracted: 4
*Overview: {'sla_clear_user_cache': {'sla_clear_user_cache'}, 'ajax_assign_simple_local_avatar_media': {'assign_simple_local_avatar_media'}, 'ajax_migrate_from_wp_user_avatar': {'migrate_from_wp_user_avatar'}, 'action_remove_simple_local_avatar': {'remove_simple_local_avatar'}}
*
***/

/** Function sla_clear_user_cache() called by wp_ajax hooks: {'sla_clear_user_cache'} **/
/** Parameters found in function sla_clear_user_cache(): {"request": ["step"]} **/
function sla_clear_user_cache() {
		check_ajax_referer( 'sla_clear_cache_nonce', 'nonce' );

		// Ensure this was run by a user with proper privileges.
		if ( ! current_user_can( 'manage_options' ) ) {
			// Match what `check_ajax_referer` does.
			wp_die( -1, 403 );
		}

		$step = isset( $_REQUEST['step'] ) ? intval( $_REQUEST['step'] ) : 1;

		// Setup defaults.
		$users_per_page = 50;
		$offset         = ( $step - 1 ) * $users_per_page;

		$users_query = new \WP_User_Query(
			array(
				'fields' => array( 'ID' ),
				'number' => $users_per_page,
				'offset' => $offset,
			)
		);

		// Total users in the site.
		$total_users = $users_query->get_total();

		// Get the users.
		$users = $users_query->get_results();

		if ( ! empty( $users ) ) {
			foreach ( $users as $user ) {
				$user_id       = $user->ID;
				$local_avatars = $this->get_user_local_avatar( $user_id );
				$media_id      = isset( $local_avatars['media_id'] ) ? $local_avatars['media_id'] : '';
				$this->clear_user_avatar_cache( $local_avatars, $user_id, $media_id );
			}

			wp_send_json_success(
				array(
					'step'    => $step + 1,
					'message' => sprintf(
					/* translators: 1: Offset, 2: Total users  */
						esc_html__( 'Processing %1$s/%2$s users...', 'simple-local-avatars' ),
						$offset,
						$total_users
					),
				)
			);
		}

		wp_send_json_success(
			array(
				'step'    => 'done',
				'message' => sprintf(
				/* translators: %s Total users */
					esc_html__( 'Completed clearing cache for all %s user(s) avatars.', 'simple-local-avatars' ),
					$total_users
				),
			)
		);
	}


/** Function ajax_assign_simple_local_avatar_media() called by wp_ajax hooks: {'assign_simple_local_avatar_media'} **/
/** Parameters found in function ajax_assign_simple_local_avatar_media(): {"post": ["user_id", "media_id", "_wpnonce"]} **/
function ajax_assign_simple_local_avatar_media() {
		// check required information and permissions
		if (
			empty( $_POST['user_id'] )
			|| empty( $_POST['media_id'] )
			|| ! current_user_can( 'upload_files' )
			|| ! current_user_can( 'edit_user', absint( wp_unslash( $_POST['user_id'] ) ) )
			|| empty( $_POST['_wpnonce'] )
			|| ! wp_verify_nonce( wp_unslash( $_POST['_wpnonce'] ), 'assign_simple_local_avatar_nonce' )
		) {
			die;
		}

		$media_id = (int) $_POST['media_id'];
		$user_id  = (int) $_POST['user_id'];

		// ensure the media is real is an image
		if ( wp_attachment_is_image( $media_id ) ) {
			$this->assign_new_user_avatar( $media_id, $user_id );
		}

		echo get_simple_local_avatar( $user_id );

		die;
	}


/** Function ajax_migrate_from_wp_user_avatar() called by wp_ajax hooks: {'migrate_from_wp_user_avatar'} **/
/** Parameters found in function ajax_migrate_from_wp_user_avatar(): {"post": ["migrateFromWpUserAvatarNonce"]} **/
function ajax_migrate_from_wp_user_avatar() {
		// Check if user has the required capability.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'simple-local-avatars' ) );
		}

		// Bail early if nonce is not available.
		if ( empty( $_POST['migrateFromWpUserAvatarNonce'] ) ) {
			die;
		}

		// Bail early if nonce is invalid.
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['migrateFromWpUserAvatarNonce'] ) ), 'migrate_from_wp_user_avatar_nonce' ) ) {
			die();
		}

		// Run the migration script and store the number of avatars processed.
		$count = $this->migrate_from_wp_user_avatar();

		// Create the array we send back to javascript here.
		$array_we_send_back = array( 'count' => $count );

		// Make sure to json encode the output because that's what it is expecting.
		echo wp_json_encode( $array_we_send_back );

		// Make sure you die when finished doing ajax output.
		wp_die();

	}


/** Function action_remove_simple_local_avatar() called by wp_ajax hooks: {'remove_simple_local_avatar'} **/
/** Parameters found in function action_remove_simple_local_avatar(): {"get": ["user_id", "_wpnonce"]} **/
function action_remove_simple_local_avatar() {
		if ( ! empty( $_GET['user_id'] ) && ! empty( $_GET['_wpnonce'] ) && wp_verify_nonce( wp_unslash( $_GET['_wpnonce'] ), 'remove_simple_local_avatar_nonce' ) ) {
			$user_id = (int) $_GET['user_id'];

			if ( ! current_user_can( 'edit_user', $user_id ) ) {
				wp_die( esc_html__( 'You do not have permission to edit this user.', 'simple-local-avatars' ) );
			}

			$this->avatar_delete( $user_id );    // delete old images if successful

			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				echo get_simple_local_avatar( $user_id );
			}
		}

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			die;
		}
	}


