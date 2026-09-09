<?php
/***
*
*Found actions: 14
*Found functions:13
*Extracted functions:11
*Total parameter names extracted: 11
*Overview: {'eb_admin_promotion': {'eb_admin_promotion'}, 'eb_save_quick_toolbar_blocks': {'eb_save_quick_toolbar_blocks'}, 'template_count': {'get_eb_admin_template_count'}, 'get': {'get_eb_admin_options'}, 'wp_ajax$action': set(), 'templates': {'get_eb_admin_templates'}, 'callback': {'nopriv_$action', '$action'}, 'save': {'save_eb_admin_options'}, 'reset': {'reset_eb_admin_options'}, 'eb_quick_setup_save_tracking': {'eb_quick_setup_save_tracking'}, 'hide_pattern_library': {'hide_pattern_library'}, 'eb_save_quick_setup': {'eb_save_quick_setup'}, 'dismiss_pointer': {'eb_dismiss_pointer'}}
*
***/

/** Function eb_admin_promotion() called by wp_ajax hooks: {'eb_admin_promotion'} **/
/** Parameters found in function eb_admin_promotion(): {"post": ["admin_nonce"]} **/
function eb_admin_promotion() {
        if ( ! isset( $_POST[ 'admin_nonce' ] ) || ! wp_verify_nonce( sanitize_key( $_POST[ 'admin_nonce' ] ), 'admin-nonce' ) ) {
            wp_send_json_error( __( 'Nonce Error', 'essential-blocks' ) );
        }
        if ( ! current_user_can( 'activate_plugins' ) ) {
            wp_send_json_error( __( 'You are not authorized to save this!', 'essential-blocks' ) );
        }

        $update_promotion = update_option( 'eb_admin_promotion', EB_PROMOTION_FLAG );
        if ( $update_promotion ) {
            wp_send_json_success( array( 'success' => true ) );
        } else {
            wp_send_json_error( __( 'Something went wrong regarding getting data.', 'essential-blocks' ) );
        }
    }


/** Function eb_save_quick_toolbar_blocks() called by wp_ajax hooks: {'eb_save_quick_toolbar_blocks'} **/
/** Parameters found in function eb_save_quick_toolbar_blocks(): {"post": ["admin_nonce", "value"]} **/
function eb_save_quick_toolbar_blocks() {
        if ( ! isset( $_POST[ 'admin_nonce' ] ) || ! wp_verify_nonce( sanitize_key( $_POST[ 'admin_nonce' ] ), 'admin-nonce' ) ) {
            wp_send_json_error( __( 'Nonce Error', 'essential-blocks' ) );
        }
        if ( ! current_user_can( 'activate_plugins' ) ) {
            wp_send_json_error( __( 'You are not authorized to save this!', 'essential-blocks' ) );
        }

        if ( isset( $_POST[ 'value' ] ) ) {
            $value = isset( $_POST[ 'value' ] ) ? json_decode( stripslashes( $_POST[ 'value' ] ), true ) : '';

            $settings = Settings::get_instance();
            $updated  = $settings->save( 'eb_quick_toolbar_allowed_blocks', $value );
            wp_send_json_success( $updated );
        } else {
            wp_send_json_error( __( 'Something went wrong regarding saving options data.', 'essential-blocks' ) );
        }
    }


/** Function template_count() called by wp_ajax hooks: {'get_eb_admin_template_count'} **/
/** Parameters found in function template_count(): {"post": ["admin_nonce"]} **/
function template_count() {
        if ( ! isset( $_POST[ 'admin_nonce' ] ) || ! wp_verify_nonce( sanitize_key( $_POST[ 'admin_nonce' ] ), 'admin-nonce' ) ) {
            wp_send_json_error( __( 'Nonce Error', 'essential-blocks' ) );
        }
        if ( ! current_user_can( 'activate_plugins' ) ) {
            wp_send_json_error( __( 'You are not authorized!', 'essential-blocks' ) );
        }

        $headers = array(
            'Content-Type' => 'application/json'
        );
        $query = '{
			getCounts {
                key
                value
            }
		  }';
        $response = wp_remote_post(
            'https://app.templately.com/api/plugin',
            array(
                'timeout' => 30,
                'headers' => $headers,
                'body' => wp_json_encode(
                    array(
                        'query' => $query
                    )
                )
            )
        );
        if ( $response ) {
            wp_send_json_success( $response );
        } else {
            wp_send_json_error( __( 'Something went wrong regarding getting data.', 'essential-blocks' ) );
        }
    }


/** Function get() called by wp_ajax hooks: {'get_eb_admin_options'} **/
/** Parameters found in function get(): {"post": ["admin_nonce", "key"]} **/
function get() {
        if ( ! isset( $_POST[ 'admin_nonce' ] ) || ! wp_verify_nonce( sanitize_key( $_POST[ 'admin_nonce' ] ), 'admin-nonce' ) ) {
            wp_send_json_error( __( 'Nonce Error', 'essential-blocks' ) );
        }
        if ( ! current_user_can( 'activate_plugins' ) ) {
            wp_send_json_error( __( 'You are not authorized to save this!', 'essential-blocks' ) );
        }

        if ( isset( $_POST[ 'key' ] ) ) {
            $key = trim( sanitize_text_field( $_POST[ 'key' ] ) );
            if ( str_contains( $key, 'eb_' ) ) {
                $settings = Settings::get_instance();
                $data     = $settings->get( $key );

                if ( $data ) {
                    wp_send_json_success( wp_unslash( $data ) );
                } else {
                    wp_send_json_error( __( 'Invalid Key', 'essential-blocks' ) );
                }
            } else {
                wp_send_json_error( __( 'Invalid Key', 'essential-blocks' ) );
            }
        } else {
            wp_send_json_error( __( 'Something went wrong regarding getting options data.', 'essential-blocks' ) );
        }
    }


/** Function wp_ajax$action() called by wp_ajax hooks: set() **/
/** No function found :-/ **/


/** Function templates() called by wp_ajax hooks: {'get_eb_admin_templates'} **/
/** Parameters found in function templates(): {"post": ["admin_nonce"]} **/
function templates() {
        if ( ! isset( $_POST[ 'admin_nonce' ] ) || ! wp_verify_nonce( sanitize_key( $_POST[ 'admin_nonce' ] ), 'admin-nonce' ) ) {
            wp_send_json_error( __( 'Nonce Error', 'essential-blocks' ) );
        }

        if ( ! current_user_can( 'activate_plugins' ) ) {
            wp_send_json_error( __( 'You are not authorized!', 'essential-blocks' ) );
        }

        $headers = array(
            'Content-Type' => 'application/json'
        );
        $query = '{
			packs(plan_type: 0, per_page: 8){
			  data{
				id
				name
				thumbnail,
				price,
                slug,
                rating
                downloads
			  }
			}
		  }';
        $response = wp_remote_post(
            'https://app.templately.com/api/plugin',
            array(
                'timeout' => 30,
                'headers' => $headers,
                'body' => wp_json_encode(
                    array(
                        'query' => $query
                    )
                )
            )
        );
        if ( $response ) {
            wp_send_json_success( $response );
        } else {
            wp_send_json_error( __( 'Something went wrong regarding getting data.', 'essential-blocks' ) );
        }
    }


/** Function callback() called by wp_ajax hooks: {'nopriv_$action', '$action'} **/
/** No function found :-/ **/


/** Function save() called by wp_ajax hooks: {'save_eb_admin_options'} **/
/** Parameters found in function save(): {"post": ["admin_nonce", "type", "key", "value"]} **/
function save() {
        if ( ! isset( $_POST[ 'admin_nonce' ] ) || ! wp_verify_nonce( sanitize_key( $_POST[ 'admin_nonce' ] ), 'admin-nonce' ) ) {
            wp_send_json_error( __( 'Nonce Error', 'essential-blocks' ) );
        }
        if ( ! current_user_can( 'activate_plugins' ) ) {
            wp_send_json_error( __( 'You are not authorized to save this!', 'essential-blocks' ) );
        }

        if ( isset( $_POST[ 'type' ] ) ) {
            $type  = trim( sanitize_text_field( $_POST[ 'type' ] ) );
            $key   = isset( $_POST[ 'key' ] ) ? trim( sanitize_text_field( $_POST[ 'key' ] ) ) : '';
            $value = isset( $_POST[ 'value' ] ) ? trim( sanitize_text_field( $_POST[ 'value' ] ) ) : '';

            $settings = Settings::get_instance();

            switch ( $type ) {
                case 'settings':
                    /**
                     * Save blocks Settings options
                     */
                    $updated = $settings->save_eb_settings( $key, $value );
                    wp_send_json_success( $updated );
                    break;

                case 'enable_disable':
                    /**
                     * Save Enable/disable blocks options
                     */
                    $value   = json_decode( wp_unslash( $value ), true );
                    $updated = $settings->save_blocks_option( $value );
                    wp_send_json_success( $updated );
                    break;
                case 'write_with_ai':
                    /**
                     * Save blocks write_with_ai options
                     */
                    $value = json_decode( wp_unslash( $value ) );

                    // Use AI integration class for validation and saving
                    if ( class_exists( 'EssentialBlocks\Integrations\AI\AI' ) ) {
                        $result = \EssentialBlocks\Integrations\AI\AI::validate_and_save_ai_settings( $value );

                        if ( ! $result[ 'success' ] ) {
                            wp_send_json_error( array(
                                'message' => $result[ 'message' ],
                                'type' => $result[ 'type' ]
                            ) );
                            return;
                        }

                        wp_send_json_success( $result[ 'data' ] );
                    } else {
                        // Fallback to direct save if AI class not available
                        $updated = $settings->save_eb_write_with_ai( $value );
                        wp_send_json_success( $updated );
                    }
                    break;
                default:
                    wp_send_json_error( __( 'Something went wrong regarding saving options data.', 'essential-blocks' ) );
            }
        } else {
            wp_send_json_error( __( 'Something went wrong regarding saving options data.', 'essential-blocks' ) );
        }
    }


/** Function reset() called by wp_ajax hooks: {'reset_eb_admin_options'} **/
/** Parameters found in function reset(): {"post": ["admin_nonce", "type", "key"]} **/
function reset() {
        if ( ! isset( $_POST[ 'admin_nonce' ] ) || ! wp_verify_nonce( sanitize_key( $_POST[ 'admin_nonce' ] ), 'admin-nonce' ) ) {
            wp_send_json_error( __( 'Nonce Error', 'essential-blocks' ) );
        }
        if ( ! current_user_can( 'activate_plugins' ) ) {
            wp_send_json_error( __( 'You are not authorized to save this!', 'essential-blocks' ) );
        }

        if ( isset( $_POST[ 'type' ] ) ) {
            $type = trim( sanitize_text_field( $_POST[ 'type' ] ) );
            $key  = isset( $_POST[ 'key' ] ) ? trim( sanitize_text_field( $_POST[ 'key' ] ) ) : '';

            $settings = Settings::get_instance();

            switch ( $type ) {
                case 'settings':
                    /**
                     * Reset blocks Settings options
                     */
                    $updated = $settings->reset_eb_settings( $key );
                    wp_send_json_success( $updated );
                    break;

                case 'enable_disable':
                    /**
                     * Reset Enable/disable blocks options
                     */

                    break;
                default:
                    wp_send_json_error( __( 'Something went wrong regarding reset options data.', 'essential-blocks' ) );
            }
        } else {
            wp_send_json_error( __( 'Something went wrong regarding reset options data.', 'essential-blocks' ) );
        }
    }


/** Function eb_quick_setup_save_tracking() called by wp_ajax hooks: {'eb_quick_setup_save_tracking'} **/
/** Parameters found in function eb_quick_setup_save_tracking(): {"post": ["admin_nonce", "is_tracking"]} **/
function eb_quick_setup_save_tracking() {
        if ( ! isset( $_POST[ 'admin_nonce' ] ) || ! wp_verify_nonce( sanitize_key( $_POST[ 'admin_nonce' ] ), 'admin-nonce' ) ) {
            wp_send_json_error( __( 'Nonce Error', 'essential-blocks' ) );
        }
        if ( ! current_user_can( 'activate_plugins' ) ) {
            wp_send_json_error( __( 'You are not authorized to save this!', 'essential-blocks' ) );
        }

        if ( isset( $_POST[ 'is_tracking' ] ) && 'true' === $_POST[ 'is_tracking' ] ) {
            $tracker = Insights::get_instance(
                ESSENTIAL_BLOCKS_FILE,
                array(
                    'opt_in' => true,
                    'goodbye_form' => true,
                    'item_id' => 'fa45e4a52a650579e98c'
                )
            );

            $tracker->schedule_tracking();
            $tracker->set_is_tracking_allowed( true );
            $tracker->do_tracking( true );

            wp_send_json_success( __( 'Saved data.', 'essential-blocks' ) );
        } else {
            wp_send_json_error( __( 'Something went wrong regarding saving options data.', 'essential-blocks' ) );
        }
    }


/** Function hide_pattern_library() called by wp_ajax hooks: {'hide_pattern_library'} **/
/** Parameters found in function hide_pattern_library(): {"post": ["admin_nonce"]} **/
function hide_pattern_library() {
        if ( ! isset( $_POST[ 'admin_nonce' ] ) || ! wp_verify_nonce( sanitize_key( $_POST[ 'admin_nonce' ] ), 'admin-nonce' ) ) {
            wp_send_json_error( __( 'Nonce Error', 'essential-blocks' ) );
        }
        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_send_json_error( __( 'You are not authorized to save this!', 'essential-blocks' ) );
        }

        $save = update_option( ESSENTIAL_BLOCKS_HIDE_PATTERN_LIBRARY, true );
        if ( $save ) {
            wp_send_json_success( __( 'Settings Updated Successfully', 'essential-blocks' ) );
        } else {
            wp_send_json_error( __( 'Couldn\'t Save Settings Data', 'essential-blocks' ) );
        }
    }


/** Function eb_save_quick_setup() called by wp_ajax hooks: {'eb_save_quick_setup'} **/
/** Parameters found in function eb_save_quick_setup(): {"post": ["admin_nonce", "setup_shown"]} **/
function eb_save_quick_setup() {
        if ( ! isset( $_POST[ 'admin_nonce' ] ) || ! wp_verify_nonce( sanitize_key( $_POST[ 'admin_nonce' ] ), 'admin-nonce' ) ) {
            wp_send_json_error( __( 'Nonce Error', 'essential-blocks' ) );
        }
        if ( ! current_user_can( 'activate_plugins' ) ) {
            wp_send_json_error( __( 'You are not authorized to save this!', 'essential-blocks' ) );
        }

        if ( isset( $_POST[ 'setup_shown' ] ) && 'true' === $_POST[ 'setup_shown' ] ) {
            update_option( 'essential_blocks_quick_setup_shown', true );
            update_option( 'essential_blocks_user_type', 'old' );

            wp_send_json_success( array( 'redirect_url' => esc_url( admin_url( 'admin.php?page=essential-blocks' ) ) ) );
        } else {
            wp_send_json_error( __( 'Something went wrong regarding saving options data.', 'essential-blocks' ) );
        }
    }


/** Function dismiss_pointer() called by wp_ajax hooks: {'eb_dismiss_pointer'} **/
/** Parameters found in function dismiss_pointer(): {"post": ["nonce", "pointer_id"]} **/
function dismiss_pointer() {
        // Verify nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'eb_pointer_nonce' ) ) {
            wp_send_json_error( __( 'Nonce verification failed', 'essential-blocks' ) );
        }

        // Check user capability
        if ( ! current_user_can( 'activate_plugins' ) ) {
            wp_send_json_error( __( 'You are not authorized to perform this action', 'essential-blocks' ) );
        }

        // Get and validate pointer ID
        if ( ! isset( $_POST['pointer_id'] ) ) {
            wp_send_json_error( __( 'Pointer ID is required', 'essential-blocks' ) );
        }

        $pointer_id = sanitize_text_field( $_POST['pointer_id'] );

        // Update user meta to mark pointer as dismissed
        $updated = update_user_meta(
            get_current_user_id(),
            '_eb_pointer_' . $pointer_id . '_dismissed',
            1
        );

        // Reset pointer priority to null when dismissed
        update_option( '_wpdeveloper_plugin_pointer_priority', null );

        if ( $updated ) {
            wp_send_json_success( __( 'Pointer dismissed successfully', 'essential-blocks' ) );
        } else {
            wp_send_json_error( __( 'Failed to dismiss pointer', 'essential-blocks' ) );
        }
    }


