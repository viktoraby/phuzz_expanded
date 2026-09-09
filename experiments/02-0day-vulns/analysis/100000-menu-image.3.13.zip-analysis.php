<?php
/***
*
*Found actions: 7
*Found functions:7
*Extracted functions:7
*Total parameter names extracted: 6
*Overview: {'menu_image_save_post_action': {'set-menu-item-settings'}, 'get_menu_image_item_settings': {'get_menu_image_item_settings'}, 'wp_ajax_get_resized_thumbnail': {'get_resized_thumbnail'}, '_toggle_debug_mode': {'fs_toggle_debug_mode'}, 'dismiss_notice_ajax_callback': {'fs_dismiss_notice_action_{$ajax_action_suffix}'}, 'dismiss_wp_menu_image_fa_notice': {'dismiss_wp_menu_image_fa'}, 'wp_ajax_set_menu_item_thumbnail': {'set-menu-item-thumbnail'}}
*
***/

/** Function menu_image_save_post_action() called by wp_ajax hooks: {'set-menu-item-settings'} **/
/** Parameters found in function menu_image_save_post_action(): {"post": ["menu_item_nonce", "menu_item_id"]} **/
function menu_image_save_post_action( $post_id ) {
        if ( !isset( $_POST['menu_item_nonce'] ) || !wp_verify_nonce( $_POST['menu_item_nonce'], 'update-menu-item' ) || !current_user_can( 'manage_options' ) ) {
            ?>
		
			<div class="error">
				<p><?php 
            _e( 'Unauthorized Access. Please try again.', 'menu-image' );
            ?></p>
			</div>
			<?php 
        } else {
            $menu_image_settings = array(
                'menu_item_image_title_position',
                'menu_item_image_size',
                'menu_item_image_button',
                'menu_image_icon',
                'menu_item_image_type',
                'menu_item_image_notification'
            );
            if ( isset( $_POST['menu_item_id'] ) ) {
                $post_id = $_POST['menu_item_id'];
            } else {
                return '';
            }
            foreach ( $menu_image_settings as $setting_name ) {
                if ( isset( $_POST[$setting_name] ) ) {
                    update_post_meta( $post_id, "_{$setting_name}", esc_sql( $_POST[$setting_name] ) );
                }
            }
        }
    }


/** Function get_menu_image_item_settings() called by wp_ajax hooks: {'get_menu_image_item_settings'} **/
/** Parameters found in function get_menu_image_item_settings(): {"post": ["menu_item_id", "menu_id", "menu_title"]} **/
function get_menu_image_item_settings() {
        $menu_title = '';
        if ( isset( $_POST['menu_item_id'] ) ) {
            $menu_item_id = absint( $_POST['menu_item_id'] );
        }
        if ( isset( $_POST['menu_id'] ) ) {
            $menu_id = absint( $_POST['menu_id'] );
        }
        if ( isset( $_POST['menu_title'] ) ) {
            $menu_title = sanitize_text_field( $_POST['menu_title'] );
        }
        $output = '<div class="menu-image-item-settings-content" data-menu-id="' . $menu_id . '" data-menu-item-id="' . $menu_item_id . '">';
        $output .= '<div id="menu-image-modal-header"><h2 class="active" data-target="menu-image-icon-settings">' . __( 'Image & Icon', 'menu-image' ) . '</h2><h2 data-target="menu-image-button-settings">' . __( 'Buttons', 'menu-image' ) . '</h2><h2 data-target="menu-image-notifications-settings">' . __( 'Badges & Bubbles', 'menu-image' ) . '</h2><div class="menu-image-close-overlay"><span class="close-text">' . __( 'Close', 'menu-image' ) . '</span><span class="dashicons dashicons-no-alt"></span></div>';
        $output .= '</div><div id="menu-image-modal-body">';
        $output .= $this->wp_post_thumbnail_html( $menu_item_id, $menu_title );
        $output .= '</div></div></div>';
        echo $output;
        wp_die();
    }


/** Function wp_ajax_get_resized_thumbnail() called by wp_ajax hooks: {'get_resized_thumbnail'} **/
/** Parameters found in function wp_ajax_get_resized_thumbnail(): {"post": ["post_id", "image_size"]} **/
function wp_ajax_get_resized_thumbnail() {
        // Security check
        check_ajax_referer( 'update-menu-item', '_ajax_nonce' );
        $post_id = intval( $_POST['post_id'] );
        $image_size = sanitize_text_field( $_POST['image_size'] );
        if ( !current_user_can( 'edit_post', $post_id ) ) {
            wp_send_json_error( 'Insufficient permissions' );
        }
        if ( !$post_id || !$image_size ) {
            wp_send_json_error( 'Missing required parameters' );
        }
        // Check if post has a thumbnail
        $thumbnail_id = get_post_thumbnail_id( $post_id );
        if ( !$thumbnail_id ) {
            wp_send_json_error( 'No thumbnail found for this menu item' );
        }
        // Update the image size meta for this menu item
        update_post_meta( $post_id, '_menu_item_image_size', $image_size );
        // Generate the thumbnail HTML with the new size
        $html = $this->wp_post_thumbnail_only_html( $post_id, $image_size );
        if ( $html ) {
            wp_send_json_success( array(
                'html' => $html,
            ) );
        } else {
            wp_send_json_error( 'Failed to generate thumbnail HTML' );
        }
    }


/** Function _toggle_debug_mode() called by wp_ajax hooks: {'fs_toggle_debug_mode'} **/
/** No params detected :-/ **/


/** Function dismiss_notice_ajax_callback() called by wp_ajax hooks: {'fs_dismiss_notice_action_{$ajax_action_suffix}'} **/
/** Parameters found in function dismiss_notice_ajax_callback(): {"post": ["message_id"]} **/
function dismiss_notice_ajax_callback() {
            check_admin_referer( 'fs_dismiss_notice_action' );

            if ( ! is_numeric( $_POST['message_id'] ) ) {
                $this->_sticky_storage->remove( $_POST['message_id'] );
            }

            wp_die();
        }


/** Function dismiss_wp_menu_image_fa_notice() called by wp_ajax hooks: {'dismiss_wp_menu_image_fa'} **/
/** No params detected :-/ **/


/** Function wp_ajax_set_menu_item_thumbnail() called by wp_ajax hooks: {'set-menu-item-thumbnail'} **/
/** Parameters found in function wp_ajax_set_menu_item_thumbnail(): {"request": ["json"], "post": ["post_id", "thumbnail_id", "is_hover"]} **/
function wp_ajax_set_menu_item_thumbnail() {
        $json = !empty( $_REQUEST['json'] );
        $post_ID = intval( $_POST['post_id'] );
        if ( !current_user_can( 'edit_post', $post_ID ) ) {
            wp_die( -1 );
        }
        $thumbnail_id = intval( $_POST['thumbnail_id'] );
        $is_hovered = (bool) $_POST['is_hover'];
        check_ajax_referer( 'update-menu-item' );
        if ( $thumbnail_id == '-1' ) {
            if ( $is_hovered ) {
                $success = delete_post_meta( $post_ID, '_thumbnail_hover_id' );
            } else {
                $success = delete_post_thumbnail( $post_ID );
            }
        } else {
            if ( $is_hovered ) {
                $success = update_post_meta( $post_ID, '_thumbnail_hover_id', $thumbnail_id );
            } else {
                $success = set_post_thumbnail( $post_ID, $thumbnail_id );
            }
        }
        if ( $success ) {
            $return = $this->wp_post_thumbnail_only_html( $post_ID );
            ( $json ? wp_send_json_success( $return ) : wp_die( $return ) );
        }
        wp_die( 0 );
    }


