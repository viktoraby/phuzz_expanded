<?php
/***
*
*Found actions: 26
*Found functions:25
*Extracted functions:25
*Total parameter names extracted: 19
*Overview: {'ajax_update_widget_columns': {'megamenu_update_widget_columns'}, 'ajax_save_grid_data': {'megamenu_save_grid_data'}, 'ajax_show_menu_item_form': {'megamenu_edit_menu_item'}, 'output_menu_toggle_block_html': {'mm_get_toggle_block_menu_toggle'}, 'ajax_get_location_settings_html': {'megamenu_get_location_settings_html'}, 'output_menu_toggle_block_animated_html': {'mm_get_toggle_block_menu_toggle_animated'}, 'output_spacer_block_html': {'mm_get_toggle_block_spacer'}, 'ajax_save_widget': {'megamenu_save_widget'}, 'ajax_get_empty_grid_column': {'megamenu_get_empty_grid_column'}, 'ajax_show_widget_form': {'megamenu_edit_widget'}, 'ajax_get_theme_scss_variables': {'megamenu_get_theme_scss_variables'}, 'ajax_dismiss_admin_notice': {'megamenu_dismiss_admin_notice'}, 'ajax_get_dialog_html': {'megamenu_get_dialog_html'}, 'ajax_delete_widget': {'megamenu_delete_widget'}, 'ajax_update_menu_item_columns': {'megamenu_update_menu_item_columns'}, 'ajax_reorder_items': {'megamenu_reorder_items'}, 'ajax_save_location_settings': {'megamenu_save_location_settings'}, 'ajax_save_theme': {'megamenu_save_theme'}, 'ajax_save_custom_location_title': {'megamenu_save_custom_location_title'}, 'ajax_get_empty_grid_row': {'megamenu_get_empty_grid_row'}, 'ajax_save_menu_item_settings': {'megamenu_save_menu_item_settings', 'mm_save_menu_item_settings'}, 'ajax_delete_menu_location': {'megamenu_delete_menu_location'}, 'ajax_save_menu_item': {'megamenu_save_menu_item'}, 'ajax_add_widget': {'megamenu_add_widget'}, 'ajax_toggle_location_mmm': {'megamenu_toggle_location_mmm'}}
*
***/

/** Function ajax_update_widget_columns() called by wp_ajax hooks: {'megamenu_update_widget_columns'} **/
/** Parameters found in function ajax_update_widget_columns(): {"post": ["id", "columns"]} **/
function ajax_update_widget_columns() {

			check_ajax_referer( 'megamenu_edit' );

			$capability = apply_filters( 'megamenu_options_capability', 'edit_theme_options' );

			if ( ! current_user_can( $capability ) ) {
				return;
			}

			$widget_id = sanitize_text_field( $_POST['id'] );
			$columns   = absint( $_POST['columns'] );

			$updated = $this->update_widget_columns( $widget_id, $columns );

			if ( $updated ) {
				$this->send_json_success( sprintf( __( 'Updated %1$s (new columns: %2$d)', 'megamenu' ), $widget_id, $columns ) );
			} else {
				$this->send_json_error( sprintf( __( 'Failed to update %s', 'megamenu' ), $widget_id ) );
			}

		}


/** Function ajax_save_grid_data() called by wp_ajax hooks: {'megamenu_save_grid_data'} **/
/** Parameters found in function ajax_save_grid_data(): {"post": ["grid", "parent_menu_item"]} **/
function ajax_save_grid_data() {

			check_ajax_referer( 'megamenu_edit' );

			$capability = apply_filters( 'megamenu_options_capability', 'edit_theme_options' );

			if ( ! current_user_can( $capability ) ) {
				return;
			}

			$grid = isset( $_POST['grid'] ) ? $_POST['grid'] : false;
			$parent_menu_item_id = absint( $_POST['parent_menu_item'] );
			$saved = false;

			if ( is_array( $grid ) && get_post_type( $parent_menu_item_id ) == 'nav_menu_item' ) {
				$existing_settings = get_post_meta( $parent_menu_item_id, '_megamenu', true );
				$submitted_settings = array_merge( $existing_settings, [ 'grid' => $grid ] );
				update_post_meta( $parent_menu_item_id, '_megamenu', $submitted_settings );
				$saved = true;
			}

			if ( $saved ) {
				$this->send_json_success( sprintf( __( 'Saved (%s)', 'megamenu' ), json_encode( $grid ) ) );
			} else {
				$this->send_json_error( sprintf( __( "Didn't save", 'megamenu' ), json_encode( $grid ) ) );
			}

		}


/** Function ajax_show_menu_item_form() called by wp_ajax hooks: {'megamenu_edit_menu_item'} **/
/** Parameters found in function ajax_show_menu_item_form(): {"post": ["widget_id"]} **/
function ajax_show_menu_item_form() {

			check_ajax_referer( 'megamenu_edit' );

			$capability = apply_filters( 'megamenu_options_capability', 'edit_theme_options' );

			if ( ! current_user_can( $capability ) ) {
				return;
			}

			$menu_item_id = sanitize_text_field( $_POST['widget_id'] );

			$nonce = wp_create_nonce( 'megamenu_save_menu_item_' . $menu_item_id );

			$saved_settings = array_filter( (array) get_post_meta( $menu_item_id, '_megamenu', true ) );
			$menu_item_meta = array_merge( Mega_Menu_Nav_Menus::get_menu_item_defaults(), $saved_settings );

			if ( ob_get_contents() ) {
				ob_clean(); // remove any warnings or output from other plugins which may corrupt the response
			}

			$dialog_title = __( 'Sub menu item', 'megamenu' );
			$post         = get_post( (int) $menu_item_id );
			if ( $post && 'nav_menu_item' === $post->post_type ) {
				$menu_item_obj = wp_setup_nav_menu_item( $post );
				if ( $menu_item_obj && ! empty( $menu_item_obj->title ) ) {
					$dialog_title = $menu_item_obj->title;
				}
			}
			?>

		<form method='post'>
			<input type='hidden' name='action' value='megamenu_save_menu_item' />
			<input type='hidden' name='menu_item_id' value='<?php echo esc_attr( $menu_item_id ); ?>' />
			<input type='hidden' name='_wpnonce'  value='<?php echo esc_attr( $nonce ); ?>' />
			<div class='mega-widget-dialog-header'>
				<h2 class='mega-widget-dialog-title'><?php echo esc_html( $dialog_title ); ?></h2>
				<button type='button' class='mega-widget-dialog-close' aria-label='<?php echo esc_attr__( 'Close', 'megamenu' ); ?>'>
					<span class='dashicons dashicons-no-alt' aria-hidden='true'></span>
				</button>
			</div>
			<div class='mega-widget-content widget-content'>
				<p>
					<label><?php _e( 'Sub menu columns', 'megamenu' ); ?></label>

					<select name="settings[submenu_columns]">
						<option value='1' <?php selected( $menu_item_meta['submenu_columns'], 1, true ); ?> >1 <?php __( 'column', 'megamenu' ); ?></option>
						<option value='2' <?php selected( $menu_item_meta['submenu_columns'], 2, true ); ?> >2 <?php __( 'columns', 'megamenu' ); ?></option>
						<option value='3' <?php selected( $menu_item_meta['submenu_columns'], 3, true ); ?> >3 <?php __( 'columns', 'megamenu' ); ?></option>
						<option value='4' <?php selected( $menu_item_meta['submenu_columns'], 4, true ); ?> >4 <?php __( 'columns', 'megamenu' ); ?></option>
						<option value='5' <?php selected( $menu_item_meta['submenu_columns'], 5, true ); ?> >5 <?php __( 'columns', 'megamenu' ); ?></option>
						<option value='6' <?php selected( $menu_item_meta['submenu_columns'], 6, true ); ?> >6 <?php __( 'columns', 'megamenu' ); ?></option>
					</select>
				</p>
			</div>
			<div class='mega-widget-form-footer widget-control-actions'>
				<div class='mega-widget-controls widget-controls'>
					<a class='mega-delete' href='#delete' data-mega-tooltip='<?php echo esc_attr__( 'Delete', 'megamenu' ); ?>' data-mega-tooltip-position='right' aria-label='<?php echo esc_attr__( 'Delete', 'megamenu' ); ?>'>
						<span class='dashicons dashicons-trash' aria-hidden='true'></span>
					</a>
				</div>
				<button type="submit" name="savewidget" id="savewidget" class="button button-primary button-compact"><?php echo esc_html__( 'Save', 'megamenu' ); ?></button>
			</div>
		</form>

			<?php

		}


/** Function output_menu_toggle_block_html() called by wp_ajax hooks: {'mm_get_toggle_block_menu_toggle'} **/
/** Parameters found in function output_menu_toggle_block_html(): {"get": ["theme"]} **/
function output_menu_toggle_block_html( $block_id, $settings = [] ) {

			if ( empty( $settings ) ) {
				$block_id = '0';
			}

			$theme_id = 'default';

			if ( isset( $_GET['theme'] ) ) {
				$theme_id = esc_attr( $_GET['theme'] );

			}

			$defaults = $this->get_default_menu_toggle_block( $theme_id );

			$settings = array_merge( $defaults, $settings );

			?>

		<div class='block'>
			<div class='block-title'><?php _e( 'TOGGLE', 'megamenu' ); ?> <span title='<?php _e( 'Menu Toggle', 'megamenu' ); ?>' class="dashicons dashicons-menu"></span></div>
			<div class='block-settings'>
				<?php $this->print_toggle_block_panel_header( __( 'Menu Toggle Settings', 'megamenu' ) ); ?>
				<input type='hidden' class='type' name='toggle_blocks[<?php echo $block_id; ?>][type]' value='menu_toggle' />
				<input type='hidden' class='align' name='toggle_blocks[<?php echo $block_id; ?>][align]' value='<?php echo $settings['align']; ?>'>
				<table class="mmm-settings-table">
					<tbody>
						<tr>
							<td class="mega-name mega-name-wide"><div class="mega-name-title"><?php _e( 'Closed', 'megamenu' ); ?></div><div class="mega-description"><?php _e( 'Shown when the menu is closed.', 'megamenu' ); ?></div></td>
							<td class="mega-value">
								<label><span class='mega-short-desc'><?php _e( 'Icon', 'megamenu' ); ?></span><?php $this->print_icon_option( 'closed_icon', $block_id, $settings['closed_icon'], $this->toggle_icons() ); ?></label>
								<label><span class='mega-short-desc'><?php _e( 'Text', 'megamenu' ); ?></span><input type='text' class='closed_text' name='toggle_blocks[<?php echo $block_id; ?>][closed_text]' value='<?php echo stripslashes( esc_attr( $settings['closed_text'] ) ); ?>' /></label>
							</td>
						</tr>
						<tr>
							<td class="mega-name mega-name-wide"><div class="mega-name-title"><?php _e( 'Open', 'megamenu' ); ?></div><div class="mega-description"><?php _e( 'Shown when the menu is open.', 'megamenu' ); ?></div></td>
							<td class="mega-value">
								<label><span class='mega-short-desc'><?php _e( 'Icon', 'megamenu' ); ?></span><?php $this->print_icon_option( 'open_icon', $block_id, $settings['open_icon'], $this->toggle_icons() ); ?></label>
								<label><span class='mega-short-desc'><?php _e( 'Text', 'megamenu' ); ?></span><input type='text' class='open_text' name='toggle_blocks[<?php echo $block_id; ?>][open_text]' value='<?php echo stripslashes( esc_attr( $settings['open_text'] ) ); ?>' /></label>
							</td>
						</tr>
						<tr>
							<td class="mega-name mega-name-wide"><div class="mega-name-title"><?php _e( 'Icon', 'megamenu' ); ?></div><div class="mega-description"><?php _e( 'Style the toggle icon.', 'megamenu' ); ?></div></td>
							<td class="mega-value">
								<label><span class='mega-short-desc'><?php _e( 'Color', 'megamenu' ); ?></span><?php $this->print_toggle_color_option( 'icon_color', $block_id, $settings['icon_color'] ); ?></label>
								<label><span class='mega-short-desc'><?php _e( 'Size', 'megamenu' ); ?></span><input type='text' class='icon_size' name='toggle_blocks[<?php echo $block_id; ?>][icon_size]' value='<?php echo stripslashes( esc_attr( $settings['icon_size'] ) ); ?>' /></label>
								<label><span class='mega-short-desc'><?php _e( 'Position', 'megamenu' ); ?></span><select name='toggle_blocks[<?php echo $block_id; ?>][icon_position]'><option value='before' <?php selected( $settings['icon_position'], 'before' ); ?>><?php _e( 'Before', 'megamenu' ); ?></option><option value='after' <?php selected( $settings['icon_position'], 'after' ); ?>><?php _e( 'After', 'megamenu' ); ?></option></select></label>
							</td>
						</tr>
						<tr>
							<td class="mega-name mega-name-wide"><div class="mega-name-title"><?php _e( 'Text', 'megamenu' ); ?></div><div class="mega-description"><?php _e( 'Style the toggle text label.', 'megamenu' ); ?></div></td>
							<td class="mega-value">
								<label><span class='mega-short-desc'><?php _e( 'Color', 'megamenu' ); ?></span><?php $this->print_toggle_color_option( 'text_color', $block_id, $settings['text_color'] ); ?></label>
								<label><span class='mega-short-desc'><?php _e( 'Size', 'megamenu' ); ?></span><input type='text' class='text_size' name='toggle_blocks[<?php echo $block_id; ?>][text_size]' value='<?php echo stripslashes( esc_attr( $settings['text_size'] ) ); ?>' /></label>
							</td>
						</tr>
						<tr>
							<td class="mega-name mega-name-wide"><div class="mega-name-title"><?php _e( 'Accessibility', 'megamenu' ); ?></div><div class="mega-description"><?php _e( 'Hide text labels and add an aria-label for icon-only display.', 'megamenu' ); ?></div></td>
							<td class="mega-value">
								<label><span class='mega-short-desc'><?php _e( 'Icon Only', 'megamenu' ); ?></span><input type='checkbox' name='toggle_blocks[<?php echo $block_id; ?>][icon_only]' value='on' <?php checked( $settings['icon_only'], 'on' ); ?> /></label>
								<label><span class='mega-short-desc'><?php _e( 'Aria Label', 'megamenu' ); ?></span><input type='text' class='aria_label' name='toggle_blocks[<?php echo $block_id; ?>][aria_label]' value='<?php echo stripslashes( esc_attr( $settings['aria_label'] ) ); ?>' /></label>
							</td>
						</tr>
					</tbody>
				</table>
				<?php $this->print_toggle_block_panel_footer(); ?>
			</div>
		</div>

			<?php
		}


/** Function ajax_get_location_settings_html() called by wp_ajax hooks: {'megamenu_get_location_settings_html'} **/
/** Parameters found in function ajax_get_location_settings_html(): {"post": ["cards_context", "editing_menu_id"]} **/
function ajax_get_location_settings_html() {
			$location      = $this->validate_ajax_location_request();
			$all_locations = $this->get_registered_locations();

			$raw_ctx = isset( $_POST['cards_context'] ) ? sanitize_key( wp_unslash( $_POST['cards_context'] ) ) : '';
			$cards_context = ( 'meta' === $raw_ctx ) ? 'meta' : 'page';

			$editing_menu_id = isset( $_POST['editing_menu_id'] ) ? absint( wp_unslash( $_POST['editing_menu_id'] ) ) : 0;

			$description = $all_locations[ $location ];

			$location_label = apply_filters( 'megamenu_location_card_description', $description, $location, $cards_context );

			$assigned_summary_html = $this->render_location_assignment_summary( $location );

			$menu_id = ( 'meta' === $cards_context && $editing_menu_id > 0 )
				? $editing_menu_id
				: (int) $this->get_menu_id_for_location( $location );

			ob_start();
			$this->render_location_settings_form( $all_locations, $location, $description );
			$form_html = ob_get_clean();

			$html = '<div class="megamenu-location-settings-dialog__surface megamenu_outer_wrap">' . $form_html . '</div>';

			wp_send_json_success(
				[
					'html'                   => $html,
					'has_nav_menu'           => $this->location_has_valid_assigned_menu( $location ),
					'mmm_enabled'            => ( $loc = Mega_Menu_Location::find( $location ) ) && $loc->is_active(),
					'location_label'         => $location_label,
					'assigned_summary_html'  => $assigned_summary_html,
					'menu_id'                => $menu_id,
				]
			);
		}


/** Function output_menu_toggle_block_animated_html() called by wp_ajax hooks: {'mm_get_toggle_block_menu_toggle_animated'} **/
/** No params detected :-/ **/


/** Function output_spacer_block_html() called by wp_ajax hooks: {'mm_get_toggle_block_spacer'} **/
/** No params detected :-/ **/


/** Function ajax_save_widget() called by wp_ajax hooks: {'megamenu_save_widget'} **/
/** Parameters found in function ajax_save_widget(): {"post": ["widget_id", "id_base"]} **/
function ajax_save_widget() {

			$widget_id = sanitize_text_field( $_POST['widget_id'] );
			$id_base   = sanitize_text_field( $_POST['id_base'] );

			check_ajax_referer( 'megamenu_save_widget_' . $widget_id );

			$capability = apply_filters( 'megamenu_options_capability', 'edit_theme_options' );

			if ( ! current_user_can( $capability ) ) {
				return;
			}

			$saved = $this->save_widget( $id_base );

			if ( $saved ) {
				$this->send_json_success( sprintf( __( 'Saved %s', 'megamenu' ), $id_base ) );
			} else {
				$this->send_json_error( sprintf( __( 'Failed to save %s', 'megamenu' ), $id_base ) );
			}

		}


/** Function ajax_get_empty_grid_column() called by wp_ajax hooks: {'megamenu_get_empty_grid_column'} **/
/** No params detected :-/ **/


/** Function ajax_show_widget_form() called by wp_ajax hooks: {'megamenu_edit_widget'} **/
/** Parameters found in function ajax_show_widget_form(): {"post": ["widget_id"]} **/
function ajax_show_widget_form() {

			check_ajax_referer( 'megamenu_edit' );

			$capability = apply_filters( 'megamenu_options_capability', 'edit_theme_options' );

			if ( ! current_user_can( $capability ) ) {
				return;
			}

			$widget_id = sanitize_text_field( $_POST['widget_id'] );

			if ( ob_get_contents() ) {
				ob_clean(); // remove any warnings or output from other plugins which may corrupt the response
			}

			wp_die( trim( $this->show_widget_form( $widget_id ) ) );

		}


/** Function ajax_get_theme_scss_variables() called by wp_ajax hooks: {'megamenu_get_theme_scss_variables'} **/
/** Parameters found in function ajax_get_theme_scss_variables(): {"post": ["settings", "theme_id"]} **/
function ajax_get_theme_scss_variables() {
			check_ajax_referer( 'megamenu_save_theme' );

			$capability = apply_filters( 'megamenu_options_capability', 'edit_theme_options' );

			if ( ! current_user_can( $capability ) ) {
				wp_send_json_error(
					[
						'message' => __( 'Sorry, you are not allowed to do that.', 'megamenu' ),
					]
				);
			}

			if ( ! isset( $_POST['settings'] ) || ! is_array( $_POST['settings'] ) ) {
				wp_send_json_error(
					[
						'message' => __( 'Invalid request.', 'megamenu' ),
					]
				);
			}

			$prepared = $this->get_prepared_theme_for_saving();
			$theme_id = isset( $_POST['theme_id'] ) ? sanitize_text_field( wp_unslash( $_POST['theme_id'] ) ) : 'default';
			$theme    = new Mega_Menu_Theme( $theme_id, $prepared );
			$location = new Mega_Menu_Location( 'test', 'Test', [] );
			$vars     = $location->get_scss_variables( $theme );

			wp_send_json_success( [ 'variables' => $vars ] );
		}


/** Function ajax_dismiss_admin_notice() called by wp_ajax hooks: {'megamenu_dismiss_admin_notice'} **/
/** Parameters found in function ajax_dismiss_admin_notice(): {"post": ["notice"]} **/
function ajax_dismiss_admin_notice() {
		check_ajax_referer( 'megamenu_dismiss_admin_notice', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) && ! current_user_can( apply_filters( 'megamenu_options_capability', 'edit_theme_options' ) ) ) {
			wp_send_json_error( null, 403 );
		}

		$notice = isset( $_POST['notice'] ) ? sanitize_key( wp_unslash( $_POST['notice'] ) ) : '';
		if ( '' === $notice ) {
			wp_send_json_error( null, 400 );
		}

		/**
		 * Notice keys allowed to be dismissed via AJAX / GET.
		 *
		 * @since 3.9
		 *
		 * @param string[] $keys Sanitized notice identifiers.
		 */
		$allowed = apply_filters( 'megamenu_dismissible_admin_notice_keys', [ 'review' ] );

		if ( ! is_array( $allowed ) || ! in_array( $notice, array_map( 'sanitize_key', $allowed ), true ) ) {
			wp_send_json_error( null, 400 );
		}

		self::dismiss( $notice );
		wp_send_json_success();
	}


/** Function ajax_get_dialog_html() called by wp_ajax hooks: {'megamenu_get_dialog_html'} **/
/** No params detected :-/ **/


/** Function ajax_delete_widget() called by wp_ajax hooks: {'megamenu_delete_widget'} **/
/** Parameters found in function ajax_delete_widget(): {"post": ["widget_id"]} **/
function ajax_delete_widget() {

			check_ajax_referer( 'megamenu_edit' );

			$capability = apply_filters( 'megamenu_options_capability', 'edit_theme_options' );

			if ( ! current_user_can( $capability ) ) {
				return;
			}

			$widget_id = sanitize_text_field( $_POST['widget_id'] );

			$deleted = $this->delete_widget( $widget_id );

			if ( $deleted ) {
				$this->send_json_success( sprintf( __( 'Deleted %s', 'megamenu' ), $widget_id ) );
			} else {
				$this->send_json_error( sprintf( __( 'Failed to delete %s', 'megamenu' ), $widget_id ) );
			}

		}


/** Function ajax_update_menu_item_columns() called by wp_ajax hooks: {'megamenu_update_menu_item_columns'} **/
/** Parameters found in function ajax_update_menu_item_columns(): {"post": ["id", "columns"]} **/
function ajax_update_menu_item_columns() {

			check_ajax_referer( 'megamenu_edit' );

			$capability = apply_filters( 'megamenu_options_capability', 'edit_theme_options' );

			if ( ! current_user_can( $capability ) ) {
				return;
			}

			$id      = absint( $_POST['id'] );
			$columns = absint( $_POST['columns'] );

			$updated = $this->update_menu_item_columns( $id, $columns );

			if ( $updated ) {
				$this->send_json_success( sprintf( __( 'Updated %1$s (new columns: %2$d)', 'megamenu' ), $id, $columns ) );
			} else {
				$this->send_json_error( sprintf( __( 'Failed to update %s', 'megamenu' ), $id ) );
			}

		}


/** Function ajax_reorder_items() called by wp_ajax hooks: {'megamenu_reorder_items'} **/
/** Parameters found in function ajax_reorder_items(): {"post": ["items"]} **/
function ajax_reorder_items() {

			check_ajax_referer( 'megamenu_edit' );

			$capability = apply_filters( 'megamenu_options_capability', 'edit_theme_options' );

			if ( ! current_user_can( $capability ) ) {
				return;
			}

			$items = isset( $_POST['items'] ) ? $_POST['items'] : false;

			$saved = false;

			if ( $items ) {
				$moved = $this->reorder_items( $items );
			}

			if ( $moved ) {
				$this->send_json_success( sprintf( __( 'Moved (%s)', 'megamenu' ), json_encode( $items ) ) );
			} else {
				$this->send_json_error( sprintf( __( "Didn't move items", 'megamenu' ), json_encode( $items ) ) );
			}

		}


/** Function ajax_save_location_settings() called by wp_ajax hooks: {'megamenu_save_location_settings'} **/
/** Parameters found in function ajax_save_location_settings(): {"post": ["megamenu_meta"]} **/
function ajax_save_location_settings() {
			$location = $this->validate_ajax_location_request();

			if ( ! isset( $_POST['megamenu_meta'][ $location ] ) || ! is_array( $_POST['megamenu_meta'][ $location ] ) ) {
				wp_send_json_error();
			}

			$this->persist_submitted_megamenu_meta(
				[
					$location => wp_unslash( $_POST['megamenu_meta'][ $location ] ),
				]
			);

			wp_send_json_success();
		}


/** Function ajax_save_theme() called by wp_ajax hooks: {'megamenu_save_theme'} **/
/** No params detected :-/ **/


/** Function ajax_save_custom_location_title() called by wp_ajax hooks: {'megamenu_save_custom_location_title'} **/
/** Parameters found in function ajax_save_custom_location_title(): {"post": ["title"]} **/
function ajax_save_custom_location_title() {
			$location = $this->validate_ajax_location_request();

			if ( 0 !== strpos( $location, 'max_mega_menu_' ) ) {
				wp_send_json_error();
			}

			$title = isset( $_POST['title'] ) ? sanitize_text_field( wp_unslash( $_POST['title'] ) ) : '';

			$locations = get_option( 'megamenu_locations', [] );

			if ( ! is_array( $locations ) ) {
				$locations = [];
			}

			$locations[ $location ] = $title;
			update_option( 'megamenu_locations', $locations );

			do_action( 'megamenu_after_save_settings' );
			do_action( 'megamenu_delete_cache' );

			wp_send_json_success(
				[
					'title'         => $title,
					'preview_title' => Mega_Menu_Location_Preview::get_preview_title( $location, $title ),
				]
			);
		}


/** Function ajax_get_empty_grid_row() called by wp_ajax hooks: {'megamenu_get_empty_grid_row'} **/
/** No params detected :-/ **/


/** Function ajax_save_menu_item_settings() called by wp_ajax hooks: {'megamenu_save_menu_item_settings', 'mm_save_menu_item_settings'} **/
/** Parameters found in function ajax_save_menu_item_settings(): {"post": ["settings", "menu_item_id", "tab", "clear_cache"]} **/
function ajax_save_menu_item_settings() {

			check_ajax_referer( 'megamenu_edit' );

			$capability = apply_filters( 'megamenu_options_capability', 'edit_theme_options' );

			if ( ! current_user_can( $capability ) ) {
				return;
			}

			$submitted_settings = isset( $_POST['settings'] ) ? $_POST['settings'] : [];

			$menu_item_id = absint( $_POST['menu_item_id'] );

			if ( $menu_item_id > 0 && is_array( $submitted_settings ) ) {

				// only check the checkbox values if the general settings form was submitted
				if ( isset( $_POST['tab'] ) && $_POST['tab'] == 'general_settings' ) {

					$checkboxes = [ 'hide_text', 'disable_link', 'hide_arrow', 'hide_on_mobile', 'hide_on_desktop', 'close_after_click', 'hide_sub_menu_on_mobile', 'collapse_children' ];

					foreach ( $checkboxes as $checkbox ) {
						if ( ! isset( $submitted_settings[ $checkbox ] ) ) {
							$submitted_settings[ $checkbox ] = 'false';
						}
					}
				}

				$submitted_settings = apply_filters( 'megamenu_menu_item_submitted_settings', $submitted_settings, $menu_item_id );

				$existing_settings = get_post_meta( $menu_item_id, '_megamenu', true );

				if ( is_array( $existing_settings ) ) {

					$submitted_settings = array_merge( $existing_settings, $submitted_settings );

				}

				update_post_meta( $menu_item_id, '_megamenu', $submitted_settings );

				do_action( 'megamenu_save_menu_item_settings', $menu_item_id );

			}

			if ( isset( $_POST['clear_cache'] ) ) {

				do_action( 'megamenu_delete_cache' );

			}

			if ( ob_get_contents() ) {
				ob_clean(); // remove any warnings or output from other plugins which may corrupt the response
			}

			wp_send_json_success();

		}


/** Function ajax_delete_menu_location() called by wp_ajax hooks: {'megamenu_delete_menu_location'} **/
/** Parameters found in function ajax_delete_menu_location(): {"post": ["location"]} **/
function ajax_delete_menu_location() {
			check_ajax_referer( 'megamenu_edit', 'nonce' );

			if ( ! current_user_can( apply_filters( 'megamenu_options_capability', 'edit_theme_options' ) ) ) {
				wp_send_json_error();
			}

			$location = isset( $_POST['location'] ) ? sanitize_key( wp_unslash( $_POST['location'] ) ) : '';

			if ( ! $location || strpos( $location, 'max_mega_menu_' ) === false ) {
				wp_send_json_error();
			}

			$this->remove_saved_custom_menu_location( $location );
			$this->fire_after_menu_location_deleted();

			wp_send_json_success();
		}


/** Function ajax_save_menu_item() called by wp_ajax hooks: {'megamenu_save_menu_item'} **/
/** Parameters found in function ajax_save_menu_item(): {"post": ["menu_item_id", "settings"]} **/
function ajax_save_menu_item() {

			$menu_item_id = absint( sanitize_text_field( $_POST['menu_item_id'] ) );

			check_ajax_referer( 'megamenu_save_menu_item_' . $menu_item_id );

			$capability = apply_filters( 'megamenu_options_capability', 'edit_theme_options' );

			if ( ! current_user_can( $capability ) ) {
				return;
			}

			$submitted_settings = isset( $_POST['settings'] ) ? $_POST['settings'] : [];

			if ( $menu_item_id > 0 && is_array( $submitted_settings ) ) {

				$existing_settings = get_post_meta( $menu_item_id, '_megamenu', true );

				if ( is_array( $existing_settings ) ) {
					$submitted_settings = array_merge( $existing_settings, $submitted_settings );
				}

				update_post_meta( $menu_item_id, '_megamenu', $submitted_settings );
			}

			$this->send_json_success( sprintf( __( 'Saved %s', 'megamenu' ), $id_base ) );

		}


/** Function ajax_add_widget() called by wp_ajax hooks: {'megamenu_add_widget'} **/
/** Parameters found in function ajax_add_widget(): {"post": ["id_base", "menu_item_id", "title", "is_grid_widget"]} **/
function ajax_add_widget() {

			check_ajax_referer( 'megamenu_edit' );

			$capability = apply_filters( 'megamenu_options_capability', 'edit_theme_options' );

			if ( ! current_user_can( $capability ) ) {
				return;
			}

			$id_base        = sanitize_text_field( $_POST['id_base'] );
			$menu_item_id   = absint( $_POST['menu_item_id'] );
			$title          = sanitize_text_field( $_POST['title'] );
			$is_grid_widget = isset( $_POST['is_grid_widget'] ) && $_POST['is_grid_widget'] == 'true';

			$added = $this->add_widget( $id_base, $menu_item_id, $title, $is_grid_widget );

			if ( $added ) {
				$this->send_json_success( $added );
			} else {
				$this->send_json_error( sprintf( __( 'Failed to add %1$s to %2$d', 'megamenu' ), $id_base, $menu_item_id ) );
			}

		}


/** Function ajax_toggle_location_mmm() called by wp_ajax hooks: {'megamenu_toggle_location_mmm'} **/
/** Parameters found in function ajax_toggle_location_mmm(): {"post": ["enabled"]} **/
function ajax_toggle_location_mmm() {
			$location = $this->validate_ajax_location_request();

			$raw_on = isset( $_POST['enabled'] ) ? wp_unslash( $_POST['enabled'] ) : '0';
			$on     = $this->is_setting_enabled( $raw_on );

			$settings = $this->get_plugin_settings();

			if ( ! isset( $settings[ $location ] ) || ! is_array( $settings[ $location ] ) ) {
				$settings[ $location ] = [];
			}

			if ( $on ) {
				$settings[ $location ]['enabled'] = '1';
			} else {
				unset( $settings[ $location ]['enabled'] );
			}

			update_option( 'megamenu_settings', $settings );

			do_action( 'megamenu_after_save_settings' );
			do_action( 'megamenu_delete_cache' );

			wp_send_json_success( [ 'enabled' => $on ] );
		}


