<?php
/***
*
*Found actions: 19
*Found functions:19
*Extracted functions:19
*Total parameter names extracted: 15
*Overview: {'bulk_deactivate_unused_widgets': {'hfe_bulk_deactivate_unused_widgets'}, 'send_plugin_deactivate_feedback': {'uds_plugin_deactivate_feedback'}, 'activate_widget': {'hfe_activate_widget'}, 'dismiss_upgrade_notice': {'hfe_dismiss_upgrade_notice'}, 'save_hfe_compatibility_option_callback': {'save_theme_compatibility_option'}, 'update_permalink_notice_option': {'update_permalink_notice_option'}, 'deactivate_widget': {'hfe_deactivate_widget'}, 'bulk_activate_widgets': {'hfe_bulk_activate_widgets'}, 'hfe_get_posts_by_query': {'hfe_get_posts_by_query'}, 'hfe_plugin_install': {'hfe_recommended_plugin_install'}, 'update_subscription': {'hfe-update-subscription'}, 'hfe_theme_install': {'hfe_recommended_theme_install'}, 'save_analytics_option': {'save_analytics_option'}, 'hfe_admin_modal': {'hfe_admin_modal'}, 'hfe_flush_permalink_notice': {'hfe_flush_permalink_notice'}, 'save_onboarding_analytics': {'hfe_save_onboarding_analytics'}, 'bulk_deactivate_widgets': {'hfe_bulk_deactivate_widgets'}, 'dismiss_notice': {'astra-notice-dismiss'}, 'hfe_activate_addon': {'hfe_recommended_plugin_activate'}}
*
***/

/** Function bulk_deactivate_unused_widgets() called by wp_ajax hooks: {'hfe_bulk_deactivate_unused_widgets'} **/
/** No params detected :-/ **/


/** Function send_plugin_deactivate_feedback() called by wp_ajax hooks: {'uds_plugin_deactivate_feedback'} **/
/** Parameters found in function send_plugin_deactivate_feedback(): {"post": ["reason", "feedback", "referer", "version", "source"]} **/
function send_plugin_deactivate_feedback() {

			$response_data = array( 'message' => __( 'Sorry, you are not allowed to do this operation.' ) );

			/**
			 * Check permission
			 */
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( $response_data );
			}

			/**
			 * Nonce verification
			 */
			if ( ! check_ajax_referer( 'uds_plugin_deactivate_feedback', 'security', false ) ) {
				$response_data = array( 'message' => __( 'Nonce validation failed' ) );
				wp_send_json_error( $response_data );
			}

			$feedback_data = array(
				'reason'      => isset( $_POST['reason'] ) ? sanitize_text_field( wp_unslash( $_POST['reason'] ) ) : '',
				'feedback'    => isset( $_POST['feedback'] ) ? sanitize_text_field( wp_unslash( $_POST['feedback'] ) ) : '',
				'domain_name' => isset( $_POST['referer'] ) ? sanitize_text_field( wp_unslash( $_POST['referer'] ) ) : '',
				'version'     => isset( $_POST['version'] ) ? sanitize_text_field( wp_unslash( $_POST['version'] ) ) : '',
				'plugin'      => isset( $_POST['source'] ) ? sanitize_text_field( wp_unslash( $_POST['source'] ) ) : '',
			);

			$api_args = array(
				'body'    => wp_json_encode( $feedback_data ),
				'headers' => BSF_Analytics_Helper::get_api_headers(),
				'timeout' => 15, //phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout
			);

			$target_url = BSF_Analytics_Helper::get_api_url() . self::$feedback_api_endpoint;

			$response = wp_safe_remote_post( $target_url, $api_args );

			$has_errors = BSF_Analytics_Helper::is_api_error( $response );

			if ( $has_errors['error'] ) {
				wp_send_json_error(
					array(
						'success' => false,
						'message' => $has_errors['error_message'],
					)
				);
			}

			wp_send_json_success();
		}


/** Function activate_widget() called by wp_ajax hooks: {'hfe_activate_widget'} **/
/** Parameters found in function activate_widget(): {"post": ["module_id"]} **/
function activate_widget() {

			check_ajax_referer( 'hfe-admin-nonce', 'nonce' );

			// Check if user has permission to manage options.
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'You do not have permission to perform this action.', 'header-footer-elementor' ) );
			}

			$module_id = isset( $_POST['module_id'] ) ? sanitize_text_field( wp_unslash( $_POST['module_id'] ) ) : '';

			// Validate module_id against known widget list.
			if ( ! isset( self::$widget_list ) ) {
				self::$widget_list = HFE_Helper::get_widget_list();
			}

			if ( empty( $module_id ) || ! array_key_exists( $module_id, self::$widget_list ) ) {
				wp_send_json_error( __( 'Invalid widget identifier.', 'header-footer-elementor' ) );
			}

			$widgets               = HFE_Helper::get_admin_settings_option( '_hfe_widgets', [] );
			$widgets[ $module_id ] = $module_id;
			$widgets               = array_map( 'esc_attr', $widgets );

			// Update widgets.
			HFE_Helper::update_admin_settings_option( '_hfe_widgets', $widgets );

			wp_send_json( $module_id );
		}


/** Function dismiss_upgrade_notice() called by wp_ajax hooks: {'hfe_dismiss_upgrade_notice'} **/
/** Parameters found in function dismiss_upgrade_notice(): {"post": ["nonce"]} **/
function dismiss_upgrade_notice() {

			// Verify nonce for security.
			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'hfe-admin-nonce' ) ) {
				wp_send_json_error( __( 'Security check failed.', 'header-footer-elementor' ) );
				return;
			}

			// Check if user has permission to manage options.
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'You do not have permission to perform this action.', 'header-footer-elementor' ) );
				return;
			}

			// Update option to remember the dismissal
			update_user_meta( get_current_user_id(), 'hfe_upgrade_notice_dismissed', 'true' );
			wp_send_json_success( __( 'Upgrade notice dismissed.', 'header-footer-elementor' ) );
		}


/** Function save_hfe_compatibility_option_callback() called by wp_ajax hooks: {'save_theme_compatibility_option'} **/
/** Parameters found in function save_hfe_compatibility_option_callback(): {"post": ["hfe_compatibility_option"]} **/
function save_hfe_compatibility_option_callback() {
			// Check nonce for security.
			check_ajax_referer( 'hfe-admin-nonce', 'nonce' );

			// Check if user has permission to manage options.
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( esc_html__( 'You do not have permission to perform this action.', 'header-footer-elementor' ) );
			}

			if ( isset( $_POST['hfe_compatibility_option'] ) ) {
				// Sanitize and validate option against allowed values.
				$option        = sanitize_text_field( wp_unslash( $_POST['hfe_compatibility_option'] ) );
				$allowed_values = [ '1', '2' ];

				if ( ! in_array( $option, $allowed_values, true ) ) {
					wp_send_json_error( esc_html__( 'Invalid compatibility option value.', 'header-footer-elementor' ) );
				}

				update_option( 'hfe_compatibility_option', $option );

				// Track theme compatibility change event.
				if ( class_exists( 'HFE_Analytics_Events' ) ) {
					HFE_Analytics_Events::track( 'theme_compat_changed', $option, [ 'active_theme' => get_template() ] );
				}

				// Return a success response.
				wp_send_json_success( esc_html__( 'Settings saved successfully!', 'header-footer-elementor' ) );
			} else {
				// Return an error response if the option is not set.
				wp_send_json_error( esc_html__( 'Unable to save settings.', 'header-footer-elementor' ) );
			}
		}


/** Function update_permalink_notice_option() called by wp_ajax hooks: {'update_permalink_notice_option'} **/
/** Parameters found in function update_permalink_notice_option(): {"post": ["nonce"]} **/
function update_permalink_notice_option() {
			// Verify the nonce.
			if ( ! isset( $_POST['nonce'] ) || ! check_ajax_referer( 'hfe_permalink_clear_notice_nonce', 'nonce', false ) ) {
				wp_send_json_error( 'Invalid nonce' );
			}
			
			// Check if the current user has the capability to manage options.
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'Unauthorized user' );
			}

			// Update the option to true.
			update_user_meta( get_current_user_id(), 'hfe_permalink_notice_option', 'notice-dismissed' );
		
			// Send a success response.
			wp_send_json_success( 'Option updated successfully' );
		}


/** Function deactivate_widget() called by wp_ajax hooks: {'hfe_deactivate_widget'} **/
/** Parameters found in function deactivate_widget(): {"post": ["module_id"]} **/
function deactivate_widget() {

			check_ajax_referer( 'hfe-admin-nonce', 'nonce' );

			// Check if user has permission to manage options.
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'You do not have permission to perform this action.', 'header-footer-elementor' ) );
			}

			$module_id = isset( $_POST['module_id'] ) ? sanitize_text_field( wp_unslash( $_POST['module_id'] ) ) : '';

			// Validate module_id against known widget list.
			if ( ! isset( self::$widget_list ) ) {
				self::$widget_list = HFE_Helper::get_widget_list();
			}

			if ( empty( $module_id ) || ! array_key_exists( $module_id, self::$widget_list ) ) {
				wp_send_json_error( __( 'Invalid widget identifier.', 'header-footer-elementor' ) );
			}

			$widgets               = HFE_Helper::get_admin_settings_option( '_hfe_widgets', [] );
			$widgets[ $module_id ] = 'disabled';
			$widgets               = array_map( 'esc_attr', $widgets );

			// Update widgets.
			HFE_Helper::update_admin_settings_option( '_hfe_widgets', $widgets );

			wp_send_json( $module_id );
		}


/** Function bulk_activate_widgets() called by wp_ajax hooks: {'hfe_bulk_activate_widgets'} **/
/** No params detected :-/ **/


/** Function hfe_get_posts_by_query() called by wp_ajax hooks: {'hfe_get_posts_by_query'} **/
/** Parameters found in function hfe_get_posts_by_query(): {"post": ["q"]} **/
function hfe_get_posts_by_query() {

		check_ajax_referer( 'hfe-get-posts-by-query', 'nonce' );

		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( __( 'Unauthorized.', 'header-footer-elementor' ) );
		}

		$search_string = isset( $_POST['q'] ) ? sanitize_text_field( $_POST['q'] ) : '';
		$data          = array();
		$result        = array();

		$args = array(
			'public'   => true,
			'_builtin' => false,
		);

		$output     = 'names'; // names or objects, note names is the default.
		$operator   = 'and'; // also supports 'or'.
		$post_types = get_post_types( $args, $output, $operator );

		unset( $post_types['elementor-hf'] ); //Exclude EHF templates.

		$post_types['Posts'] = 'post';
		$post_types['Pages'] = 'page';

		foreach ( $post_types as $key => $post_type ) {
			$data = array();

			add_filter( 'posts_search', array( $this, 'search_only_titles' ), 10, 2 );

			$query = new \WP_Query(
				array(
					's'              => $search_string,
					'post_type'      => $post_type,
					'posts_per_page' => 50,
				)
			);

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					$title  = get_the_title();
					$title .= ( 0 != $query->post->post_parent ) ? ' (' . get_the_title( $query->post->post_parent ) . ')' : '';
					$id     = get_the_id();
					$data[] = array(
						'id'   => 'post-' . $id,
						'text' => $title,
					);
				}
			}

			if ( is_array( $data ) && ! empty( $data ) ) {
				$result[] = array(
					'text'     => $key,
					'children' => $data,
				);
			}
		}

		$data = array();

		wp_reset_postdata();

		$args = array(
			'public' => true,
		);

		$output     = 'objects'; // names or objects, note names is the default.
		$operator   = 'and'; // also supports 'or'.
		$taxonomies = get_taxonomies( $args, $output, $operator );

		foreach ( $taxonomies as $taxonomy ) {
			$terms = get_terms(
				$taxonomy->name,
				array(
					'orderby'    => 'count',
					'hide_empty' => 0,
					'name__like' => $search_string,
				)
			);

			$data = array();

			$label = ucwords( $taxonomy->label );

			if ( ! empty( $terms ) ) {
				foreach ( $terms as $term ) {
					$term_taxonomy_name = ucfirst( str_replace( '_', ' ', $taxonomy->name ) );

					$data[] = array(
						'id'   => 'tax-' . $term->term_id,
						'text' => $term->name . ' archive page',
					);

					$data[] = array(
						'id'   => 'tax-' . $term->term_id . '-single-' . $taxonomy->name,
						'text' => 'All singulars from ' . $term->name,
					);
				}
			}

			if ( is_array( $data ) && ! empty( $data ) ) {
				$result[] = array(
					'text'     => $label,
					'children' => $data,
				);
			}
		}

		// return the result in json.
		wp_send_json( $result );
	}


/** Function hfe_plugin_install() called by wp_ajax hooks: {'hfe_recommended_plugin_install'} **/
/** Parameters found in function hfe_plugin_install(): {"post": ["slug"]} **/
function hfe_plugin_install() {

			check_ajax_referer( 'updates', '_ajax_nonce' );

			// Check if user has permission to install plugin.
			if ( ! current_user_can( 'install_plugins' ) ) {
				wp_send_json_error( esc_html__( 'Plugin installation is disabled for you on this site.', 'header-footer-elementor' ) );
			}
			// Fetching the plugin slug from the AJAX request.
			// @psalm-suppress PossiblyInvalidArgument.
			$plugin_slug = isset( $_POST['slug'] ) && is_string( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';

			if ( empty( $plugin_slug ) ) {
				wp_send_json_error( [ 'message' => __( 'Plugin slug is missing.', 'header-footer-elementor' ) ] );
			}

			// Schedule the database update if the plugin is installed successfully.
			add_action(
				'shutdown',
				function () use ( $plugin_slug ) {
					// Iterate through all plugins to check if the installed plugin matches the current plugin slug.
					$all_plugins = get_plugins();
					foreach ( $all_plugins as $plugin_file => $_ ) {
						if ( class_exists( 'BSF_UTM_Analytics' ) && is_callable( 'BSF_UTM_Analytics::update_referer' ) && strpos( $plugin_file, $plugin_slug . '/' ) === 0 ) {
							// If the plugin is found and the update_referer function is callable, update the referer with the corresponding product slug.
							BSF_UTM_Analytics::update_referer( 'header-footer-elementor', $plugin_slug );
							return;
						}
					}
				}
			);

			if ( function_exists( 'wp_ajax_install_plugin' ) ) {
				// @psalm-suppress NoValue
				wp_ajax_install_plugin();
			} else {
				wp_send_json_error( [ 'message' => __( 'Plugin installation function not found.', 'header-footer-elementor' ) ] );
			}
		}


/** Function update_subscription() called by wp_ajax hooks: {'hfe-update-subscription'} **/
/** Parameters found in function update_subscription(): {"post": ["data"]} **/
function update_subscription() {

			check_ajax_referer( 'hfe-admin-nonce', 'nonce' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'You can\'t perform this action.' );
			}

			$api_domain = trailingslashit( $this->get_api_domain() );
			// PHPCS:Ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- This code is deprecated and will be removed in future versions.
			$arguments = isset( $_POST['data'] ) ? array_map( 'sanitize_text_field', json_decode( stripslashes( wp_unslash( $_POST['data'] ) ), true ) ) : [];

			$url = add_query_arg( $arguments, $api_domain . 'wp-json/starter-templates/v1/subscribe/' ); // add URL of your site or mail API.

			$response = wp_remote_post( $url, [ 'timeout' => 60 ] );

			if ( ! is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) === 200 ) {
				$response = json_decode( wp_remote_retrieve_body( $response ), true );

				// Successfully subscribed.
				if ( isset( $response['success'] ) && $response['success'] ) {
					update_user_meta( get_current_user_ID(), 'hfe-subscribed', 'yes' );
					wp_send_json_success( $response );
				}
			} else {
				wp_send_json_error( $response );
			}
		}


/** Function hfe_theme_install() called by wp_ajax hooks: {'hfe_recommended_theme_install'} **/
/** Parameters found in function hfe_theme_install(): {"post": ["slug"]} **/
function hfe_theme_install() {

			check_ajax_referer( 'updates', '_ajax_nonce' );

			// Check if user has permission to install theme.
			if ( ! current_user_can( 'install_themes' ) ) {
				wp_send_json_error( esc_html__( 'Theme installation is disabled for you on this site.', 'header-footer-elementor' ) );
			}
			// Fetching the plugin slug from the AJAX request.
			// @psalm-suppress PossiblyInvalidArgument.
			$theme_slug = isset( $_POST['slug'] ) && is_string( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';

			if ( empty( $theme_slug ) ) {
				wp_send_json_error( [ 'message' => __( 'Theme slug is missing.', 'header-footer-elementor' ) ] );
			}

			// Schedule the database update if the theme is installed successfully.
			add_action(
				'shutdown',
				function () use ( $theme_slug ) {
					// Iterate through all themes to check if the installed theme matches the current theme slug.
					$all_themes = wp_get_themes();
					foreach ( $all_themes as $theme_file => $_ ) {
						if ( class_exists( 'BSF_UTM_Analytics' ) && is_callable( 'BSF_UTM_Analytics::update_referer' ) && strpos( $theme_file, $theme_slug . '/' ) === 0 ) {
							// If the theme is found and the update_referer function is callable, update the referer with the corresponding product slug.
							BSF_UTM_Analytics::update_referer( 'header-footer-elementor', $theme_slug );
							return;
						}
					}
				}
			);

			if ( function_exists( 'wp_ajax_install_theme' ) ) {
				// @psalm-suppress NoValue
				wp_ajax_install_theme();
			} else {
				wp_send_json_error( [ 'message' => __( 'Theme installation function not found.', 'header-footer-elementor' ) ] );
			}
		}


/** Function save_analytics_option() called by wp_ajax hooks: {'save_analytics_option'} **/
/** Parameters found in function save_analytics_option(): {"post": ["uae_usage_optin"]} **/
function save_analytics_option() {
			// Check nonce for security.
			check_ajax_referer( 'hfe-admin-nonce', 'nonce' );

			// Check if user has permission to manage options.
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'You do not have permission to perform this action.', 'header-footer-elementor' ) );
			}

			if ( isset( $_POST['uae_usage_optin'] ) ) {
				// Sanitize and validate option against allowed values.
				$option        = sanitize_text_field( wp_unslash( $_POST['uae_usage_optin'] ) );
				$allowed_values = [ 'yes', 'no' ];

				if ( ! in_array( $option, $allowed_values, true ) ) {
					wp_send_json_error( __( 'Invalid analytics option value.', 'header-footer-elementor' ) );
				}

				update_option( 'uae_usage_optin', $option );

				// Return a success response.
				wp_send_json_success( __( 'Settings saved successfully!', 'header-footer-elementor' ) );
			} else {
				// Return an error response if the option is not set.
				wp_send_json_error( __( 'Unable to save settings.', 'header-footer-elementor' ) );
			}
		}


/** Function hfe_admin_modal() called by wp_ajax hooks: {'hfe_admin_modal'} **/
/** No params detected :-/ **/


/** Function hfe_flush_permalink_notice() called by wp_ajax hooks: {'hfe_flush_permalink_notice'} **/
/** Parameters found in function hfe_flush_permalink_notice(): {"post": ["nonce"]} **/
function hfe_flush_permalink_notice() {
			// Verify the nonce.
			if ( ! isset( $_POST['nonce'] ) || ! check_ajax_referer( 'hfe_permalink_clear_notice_nonce', 'nonce', false ) ) {
				wp_send_json_error( 'Invalid nonce' );
			}
			
			// Check if the current user has the capability to manage options.
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'Unauthorized user' );
			}

			$permalink_structure = get_option('permalink_structure');
			// Check if the permalink structure is not empty.
			if ( '' !== $permalink_structure )
			{ 
				update_option('permalink_structure', $permalink_structure);
				flush_rewrite_rules(); 
				// Update the option to true.
				update_user_meta( get_current_user_id(), 'hfe_permalink_notice_option', 'notice-dismissed' );
		
			} 

			// Send a success response.
			wp_send_json_success( 'Permalink Flushed successfully' );
		}


/** Function save_onboarding_analytics() called by wp_ajax hooks: {'hfe_save_onboarding_analytics'} **/
/** Parameters found in function save_onboarding_analytics(): {"post": ["completed_steps", "skipped_steps", "exited_early", "exit_at_step"]} **/
function save_onboarding_analytics() {
			check_ajax_referer( 'hfe-admin-nonce', 'nonce' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'You do not have permission to perform this action.', 'header-footer-elementor' ) );
			}

			$allowed_steps = [ 'welcome', 'configure', 'features', 'success' ];

			// Sanitize completed_steps array.
			$completed_steps = [];
			if ( isset( $_POST['completed_steps'] ) && is_array( $_POST['completed_steps'] ) ) {
				$raw_completed = array_map( 'sanitize_text_field', wp_unslash( $_POST['completed_steps'] ) );
				foreach ( $raw_completed as $step ) {
					if ( in_array( $step, $allowed_steps, true ) ) {
						$completed_steps[] = $step;
					}
				}
			}

			// Sanitize skipped_steps array.
			$skipped_steps = [];
			if ( isset( $_POST['skipped_steps'] ) && is_array( $_POST['skipped_steps'] ) ) {
				$raw_skipped = array_map( 'sanitize_text_field', wp_unslash( $_POST['skipped_steps'] ) );
				foreach ( $raw_skipped as $step ) {
					if ( in_array( $step, $allowed_steps, true ) ) {
						$skipped_steps[] = $step;
					}
				}
			}

			$exited_early = isset( $_POST['exited_early'] ) && 'true' === sanitize_text_field( wp_unslash( $_POST['exited_early'] ) );
			$exit_at_step = isset( $_POST['exit_at_step'] ) ? sanitize_text_field( wp_unslash( $_POST['exit_at_step'] ) ) : '';

			if ( ! empty( $exit_at_step ) && ! in_array( $exit_at_step, $allowed_steps, true ) ) {
				$exit_at_step = '';
			}

			$analytics_data = [
				'completed'       => ! $exited_early,
				'exited_early'    => $exited_early,
				'exit_at_step'    => $exit_at_step,
				'skipped_steps'   => $skipped_steps,
				'completed_steps' => $completed_steps,
			];

			update_option( 'hfe_onboarding_analytics', $analytics_data, false );

			wp_send_json_success( __( 'Onboarding analytics saved.', 'header-footer-elementor' ) );
		}


/** Function bulk_deactivate_widgets() called by wp_ajax hooks: {'hfe_bulk_deactivate_widgets'} **/
/** No params detected :-/ **/


/** Function dismiss_notice() called by wp_ajax hooks: {'astra-notice-dismiss'} **/
/** Parameters found in function dismiss_notice(): {"post": ["notice_id", "repeat_notice_after"]} **/
function dismiss_notice() {
			check_ajax_referer( 'astra-notices', 'nonce' );

			$notice_id           = ( isset( $_POST['notice_id'] ) ) ? sanitize_key( wp_unslash( $_POST['notice_id'] ) ) : '';
			$repeat_notice_after = ( isset( $_POST['repeat_notice_after'] ) ) ? absint( wp_unslash( $_POST['repeat_notice_after'] ) ) : 0;
			$notice              = $this->get_notice_by_id( $notice_id );
			$capability          = isset( $notice['capability'] ) ? $notice['capability'] : 'manage_options';

			$has_cap = current_user_can( $capability );

			/**
			 * Filters whether the current user passes the capability check for notice dismissal.
			 *
			 * Both the legacy and new filter names are fired for backward compatibility.
			 * Filters can only restrict access (return false), never grant it — if the
			 * underlying current_user_can() check fails, filters cannot override to true.
			 */
			$cap_check = apply_filters( 'astra_notices_user_cap_check', $has_cap );
			$cap_check = apply_filters( 'bsf_admin_notices_user_cap_check', $cap_check );

			if ( ! $has_cap || ! $cap_check ) {
				wp_send_json_error( esc_html__( 'Permission denied.', 'astra-notices' ) );
			}

			$allowed_notices = get_option( 'astra_notices_allowed', array() ); // Get allowed notices.

			// Define restricted user meta keys using the dynamic table prefix.
			global $wpdb;
			$wp_default_meta_keys = array(
				$wpdb->prefix . 'capabilities',
				$wpdb->prefix . 'user_level',
				$wpdb->prefix . 'user-settings',
				'account_status',
				'session_tokens',
			);

			// if $notice_id does not start with astra-notices-id and notice_id is not from the allowed notices, then return.
			if ( 0 !== strpos( $notice_id, 'astra-notices-id-' ) && ( ! in_array( $notice_id, $allowed_notices, true ) ) ) {
				wp_send_json_error( esc_html__( 'Invalid notice ID.', 'astra-notices' ) );
			}

			// Valid inputs?
			if ( ! empty( $notice_id ) ) {

				if ( in_array( $notice_id, $wp_default_meta_keys, true ) ) {
					wp_send_json_error( esc_html__( 'Invalid notice ID.', 'astra-notices' ) );
				}

				if ( ! empty( $repeat_notice_after ) ) {
					set_transient( $notice_id, true, $repeat_notice_after );
				} else {
					update_user_meta( get_current_user_id(), $notice_id, 'notice-dismissed' );
				}

				wp_send_json_success();
			}

			wp_send_json_error();
		}


/** Function hfe_activate_addon() called by wp_ajax hooks: {'hfe_recommended_plugin_activate'} **/
/** Parameters found in function hfe_activate_addon(): {"post": ["plugin", "type", "slug"]} **/
function hfe_activate_addon() {

			// Run a security check.
			check_ajax_referer( 'hfe-admin-nonce', 'nonce' );

			if ( isset( $_POST['plugin'] ) ) {

				$type = '';
				if ( ! empty( $_POST['type'] ) ) {
					$type = sanitize_key( wp_unslash( $_POST['type'] ) );
				}

				$plugin = sanitize_text_field( wp_unslash( $_POST['plugin'] ) );

				if ( 'plugin' === $type ) {

					// Check for permissions.
					if ( ! current_user_can( 'activate_plugins' ) ) {
						wp_send_json_error( esc_html__( 'Plugin activation is disabled for you on this site.', 'header-footer-elementor' ) );
					}

					$activate = activate_plugins( $plugin );

					if ( ! is_wp_error( $activate ) ) {

						do_action( 'hfe_plugin_activated', $plugin );

						wp_send_json_success( esc_html__( 'Plugin Activated.', 'header-footer-elementor' ) );
					}
				}

				if ( 'theme' === $type ) {

					if ( isset( $_POST['slug'] ) ) {
						$slug = sanitize_key( wp_unslash( $_POST['slug'] ) );

						// Check for permissions.
						if ( ! ( current_user_can( 'switch_themes' ) ) ) {
							wp_send_json_error( esc_html__( 'Theme activation is disabled for you on this site.', 'header-footer-elementor' ) );
						}

						$activate = switch_theme( $slug );

						if ( ! is_wp_error( $activate ) ) {

							do_action( 'hfe_theme_activated', $plugin );

							wp_send_json_success( esc_html__( 'Theme Activated.', 'header-footer-elementor' ) );
						}
					}
				}
			}

			if ( 'plugin' === $type ) {
				wp_send_json_error( esc_html__( 'Could not activate plugin. Please activate from the Plugins page.', 'header-footer-elementor' ) );
			} elseif ( 'theme' === $type ) {
				wp_send_json_error( esc_html__( 'Could not activate theme. Please activate from the Themes page.', 'header-footer-elementor' ) );
			}
		}


