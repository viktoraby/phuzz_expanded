<?php
/***
*
*Found actions: 9
*Found functions:8
*Extracted functions:8
*Total parameter names extracted: 7
*Overview: {'elementskit_onboard_plugins': {'ekit_onboard_plugins'}, 'ekit_widgetarea_content': {'ekit_widgetarea_content', 'nopriv_ekit_widgetarea_content'}, 'generate_navigation_markup': {'generate_navigation_markup'}, 'ask_me_later_message': {'wpmet_rating_ask_me_later_message'}, 'elementskit_admin_action': {'ekit_admin_action'}, 'never_show_message': {'wpmet_rating_never_show_message'}, 'handle_feedback': {'elementskit_deactivation_feedback'}, 'dismiss_ajax_call': {'wpmet-notices'}}
*
***/

/** Function elementskit_onboard_plugins() called by wp_ajax hooks: {'ekit_onboard_plugins'} **/
/** Parameters found in function elementskit_onboard_plugins(): {"post": ["nonce", "plugin_slug"]} **/
function elementskit_onboard_plugins() {
		// Check for nonce security
		if (!isset($_POST['nonce']) || ! wp_verify_nonce( sanitize_key(wp_unslash($_POST['nonce'])), 'ajax-nonce' ) ) {
			return;
		}

		$plugin_slug = isset( $_POST['plugin_slug'] ) ? sanitize_text_field( wp_unslash( $_POST['plugin_slug'] ) ) : '';
		if ( isset( $plugin_slug ) && current_user_can('install_plugins') ) {
			$status = \ElementsKit_Lite\Libs\Framework\Classes\Plugin_Installer::single_install_and_activate( $plugin_slug );
			if ( is_wp_error( $status ) ) {
				wp_send_json_error( array( 'status' => false ) );
			} else {
				wp_send_json_success(
					array(
						'message' => self::plugin_activate_message( $plugin_slug )
					)
				);
			}
		}
	}


/** Function ekit_widgetarea_content() called by wp_ajax hooks: {'ekit_widgetarea_content', 'nopriv_ekit_widgetarea_content'} **/
/** Parameters found in function ekit_widgetarea_content(): {"post": ["nonce", "post_id"]} **/
function ekit_widgetarea_content() {

		if ( !isset($_POST['nonce']) || !wp_verify_nonce( sanitize_key( wp_unslash( $_POST['nonce'] ) ), 'ekit_pro' ) ) {
			wp_die();
		}

		$post_id = isset($_POST['post_id']) ? intval( $_POST['post_id'] ) : 0;

		if ( 'publish' !== get_post_status( $post_id ) ) {
			wp_die();
		}

		if ( isset( $post_id ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped --  Displaying with Elementor content rendering
			echo str_replace( '#elementor', '', \ElementsKit_Lite\Utils::render_tab_content( \ElementsKit_Lite\Utils::render_elementor_content( $post_id ), $post_id ) );
		} else {
            echo esc_html__( 'Click on the Edit Content button to edit/add the content.', 'elementskit-lite' );
		}

		wp_die();
	}


/** Function generate_navigation_markup() called by wp_ajax hooks: {'generate_navigation_markup'} **/
/** No params detected :-/ **/


/** Function ask_me_later_message() called by wp_ajax hooks: {'wpmet_rating_ask_me_later_message'} **/
/** Parameters found in function ask_me_later_message(): {"post": ["nonce", "plugin_name"]} **/
function ask_me_later_message() {
			
			if( empty( $_POST['nonce'] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'wpmet_rating' ) ){
				return false;
			}

			$plugin_name = isset($_POST['plugin_name']) ? sanitize_key( $_POST['plugin_name'] ) : '';
			if ( get_option( $plugin_name . '_ask_me_later' ) == false ) {
				add_option( $plugin_name . '_ask_me_later', 'yes' );
			} else {
				add_option( $plugin_name . '_never_show', 'yes' );
			}
		}


/** Function elementskit_admin_action() called by wp_ajax hooks: {'ekit_admin_action'} **/
/** Parameters found in function elementskit_admin_action(): {"post": ["nonce", "widget_list", "module_list", "user_data", "settings", "our_plugins"]} **/
function elementskit_admin_action() {
		// Check for nonce security
		if (!isset($_POST['nonce']) || ! wp_verify_nonce( sanitize_key(wp_unslash($_POST['nonce'])), 'ajax-nonce' ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( isset( $_POST['widget_list'] ) ) {
			$widget_list          = Widget_List::instance()->get_list();
			$widget_list_input    = ! is_array( $_POST['widget_list'] ) ? array() : map_deep( wp_unslash( $_POST['widget_list'] ) , 'sanitize_text_field' );
			$widget_prepared_list = array();

			foreach ( $widget_list as $widget_slug => $widget ) {
				if ( isset( $widget['package'] ) && $widget['package'] == 'pro-disabled' ) {
					continue;
				}

				$widget['status'] = ( in_array( $widget_slug, $widget_list_input ) ? 'active' : 'inactive' );

				$widget_prepared_list[ $widget_slug ] = $widget;
			}

			// Allow to filter widget status
			apply_filters( 'elementskit/widgets/status/update', $widget_prepared_list );

			$this->utils->save_option( 'widget_list', $widget_prepared_list );
		}

		if ( isset( $_POST['module_list'] ) ) {
			$module_list          = Module_List::instance()->get_list( 'optional' );
			$module_list_input    = ! is_array( $_POST['module_list'] ) ? array() : map_deep( wp_unslash( $_POST['module_list'] ) , 'sanitize_text_field' );
			$module_prepared_list = array();

			foreach ( $module_list as $module_slug => $module ) {
				if ( isset( $module['package'] ) && $module['package'] == 'pro-disabled' ) {
					continue;
				}

				$module['status'] = ( in_array( $module_slug, $module_list_input ) ? 'active' : 'inactive' );

				$module_prepared_list[ $module_slug ] = $module;
			}

			$this->utils->save_option( 'module_list', $module_prepared_list );
		}

		if ( isset( $_POST['user_data'] ) ) {
			$this->utils->save_option( 'user_data', empty( $_POST['user_data'] ) ? array() : map_deep( wp_unslash( $_POST['user_data'] ) , 'wp_filter_nohtml_kses' ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- It will sanitize by wp_filter_nohtml_kses function
		}

		if ( isset( $_POST['settings'] ) ) {
			$this->utils->save_settings( empty( $_POST['settings'] ) ? array() : map_deep( wp_unslash( $_POST['settings'] ) , 'sanitize_text_field' )  );
		}

		do_action( 'elementskit/admin/after_save' );

		$response = array(
			'message' => self::plugin_activate_message( 'setup_configurations' )
		);

		$plugins = !empty($_POST['our_plugins']) && is_array($_POST['our_plugins']) ? $_POST['our_plugins'] : [];
		if($plugins) {
			$total_plugins = count($plugins);
			$total_steps   = 1 + $total_plugins;
			$percentage = ($total_steps > 0) ? (1 / $total_steps) * 100 : 100;
			$percentage = round($percentage);

			$response['progress'] = $percentage;
			$response['plugins'] = $plugins;
		}

		wp_send_json($response);

		wp_die(); // this is required to terminate immediately and return a proper response
	}


/** Function never_show_message() called by wp_ajax hooks: {'wpmet_rating_never_show_message'} **/
/** Parameters found in function never_show_message(): {"post": ["nonce", "plugin_name"]} **/
function never_show_message() {
			
			if( empty( $_POST['nonce'] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'wpmet_rating' ) ){
				return false;
			}

			$plugin_name = isset($_POST['plugin_name']) ? sanitize_key( $_POST['plugin_name'] ) : '';
			add_option( $plugin_name . '_never_show', 'yes' );
		}


/** Function handle_feedback() called by wp_ajax hooks: {'elementskit_deactivation_feedback'} **/
/** Parameters found in function handle_feedback(): {"post": ["reason", "reason_key", "reason_label", "feedback"]} **/
function handle_feedback() {
		$this->verify_request();

		$selected_reason = isset( $_POST['reason'] )
			? sanitize_text_field( wp_unslash( $_POST['reason'] ) )
			: '';

		$data = array(
			'plugin_slug'    => 'elementskit',
			'plugin_name'    => 'ElementsKit',
			'plugin_version' => \ElementsKit_Lite::version(),
			'user'           => array(
				'email' => $this->get_user_email(),
			),
			'feedback'       => array(
				'reason_key'   => isset( $_POST['reason_key'] ) ? sanitize_text_field( wp_unslash( $_POST['reason_key'] ) ) : 'other',
				'reason_label' => isset( $_POST['reason_label'] ) ? sanitize_text_field( wp_unslash( $_POST['reason_label'] ) ) : $selected_reason,
				'message'      => isset( $_POST['feedback'] )? sanitize_textarea_field( wp_unslash( $_POST['feedback'] ) ) : '',
			),
			'usage'          => array(
				'active_widgets' => $this->get_active_widgets(),
				'active_modules' => $this->get_active_modules(),
				'user_type'      => $this->get_user_type(),
				'active_days'    => $this->get_days_active(),
			),
			'environment'    => array(
				'multisite_status'   => is_multisite(),
				'wp_version'         => get_bloginfo( 'version' ),
				'php_version'        => PHP_VERSION,
				'elementor_version'  => defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '',
				'site_url'           => get_site_url(),
			),
		);

		$response = $this->send_feedback_data( $data );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( array( 'message' => $response->get_error_message() ) );
		}

		$response_code = (int) wp_remote_retrieve_response_code( $response );
		if ( $response_code < 200 || $response_code >= 300 ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Failed to submit feedback.', 'elementskit-lite' ),
					'code'    => $response_code,
				)
			);
		}

		wp_send_json_success(
			array( 'message' => esc_html__( 'Thank you for your feedback!', 'elementskit-lite' ) )
		);
	}


/** Function dismiss_ajax_call() called by wp_ajax hooks: {'wpmet-notices'} **/
/** Parameters found in function dismiss_ajax_call(): {"post": ["nonce", "notice_id", "dismissible", "expired_time"]} **/
function dismiss_ajax_call() {
			if( empty( $_POST['nonce'] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'wpmet-notices' ) ){
				return false;
			}

			// check if current user is admin
			if ( ! current_user_can( 'manage_options' ) ) {
				return false;
			}

			$notice_id    = ( isset( $_POST['notice_id'] ) ) ? sanitize_text_field( wp_unslash( $_POST['notice_id'] ) ) : '';
			$dismissible  = ( isset( $_POST['dismissible'] ) ) ? sanitize_text_field( wp_unslash( $_POST['dismissible'] ) ) : '';
			$expired_time = ( isset( $_POST['expired_time'] ) ) ? sanitize_text_field( wp_unslash( $_POST['expired_time'] ) ) : '';

			if ( ! empty( $notice_id ) ) {
				if ( 'user' === $dismissible ) {
					update_user_meta( get_current_user_id(), $notice_id, true );
				} else {
					set_transient( $notice_id, true, $expired_time );
				}

				if( $notice_id == 'elementskit-lite-edit_with_emailkit_banner' ) {
					$counter = get_option('elementskit-lite-edit_with_emailkit_banner_dismissed_'.get_current_user_id(), 0);
					update_option('elementskit-lite-edit_with_emailkit_banner_dismissed_'.get_current_user_id(), $counter+1);
				}

				wp_send_json_success();
			}

			wp_send_json_error();
		}


