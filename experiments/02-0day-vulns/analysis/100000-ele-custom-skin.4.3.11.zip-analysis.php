<?php
/***
*
*Found actions: 13
*Found functions:12
*Extracted functions:12
*Total parameter names extracted: 9
*Overview: {'ajax_loop_custom_layout_preview': {'ecs_loop_custom_layout_preview'}, 'get_document_data': {'ecsload', 'nopriv_ecsload'}, 'ajax_overwrite': {'ecs_style_templates_overwrite'}, 'ajax_get_palettes': {'ecs_cw_get_palettes'}, 'handle_ajax': {'{$action}'}, 'ajax_delete': {'ecs_style_templates_delete'}, 'ajax_save_new': {'ecs_style_templates_save_new'}, 'ajax_list': {'ecs_style_templates_list'}, 'ajax_preview_layout': {'ecs_preview_layout'}, 'ajax_get': {'ecs_style_templates_get'}, 'ajax_save_palette': {'ecs_cw_save_palette'}, 'ajax_delete_palette': {'ecs_cw_delete_palette'}}
*
***/

/** Function ajax_loop_custom_layout_preview() called by wp_ajax hooks: {'ecs_loop_custom_layout_preview'} **/
/** Parameters found in function ajax_loop_custom_layout_preview(): {"post": ["template_id", "items"]} **/
function ajax_loop_custom_layout_preview(): void {
		check_ajax_referer( 'ecs-loop-custom-layout-preview', 'nonce' );

		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( [ 'message' => 'Unauthorized' ] );
			return;
		}

		$template_id = absint( $_POST['template_id'] ?? 0 );
		$items       = isset( $_POST['items'] ) && is_array( $_POST['items'] )
			? array_values( wp_unslash( $_POST['items'] ) )
			: [];

		if ( ! $template_id || 'publish' !== get_post_status( $template_id ) ) {
			wp_send_json_error( [ 'message' => 'Invalid template' ] );
			return;
		}

		// Ensure the placeholder widget class is loaded before static calls.
		if ( ! class_exists( 'ECS_Container_Placeholder_Widget', false ) ) {
			$widget_file = ECS_PATH . 'modules/container-layout/widgets/class-ecs-container-placeholder-widget.php';
			if ( file_exists( $widget_file ) ) {
				require_once $widget_file;
			}
		}

		if ( ! class_exists( 'ECS_Container_Placeholder_Widget', false ) ) {
			wp_send_json_error( [ 'message' => 'Container Layout module not active' ] );
			return;
		}

		ob_start();
		// Render only what goes inside .elementor-widget-container —
		// i.e. the ecs-loop-custom-layout-wrap div and its template instances.
		$this->render_loop_items_with_template( $template_id, $items );
		$html = ob_get_clean();

		wp_send_json_success( [ 'html' => $html ] );
	}


/** Function get_document_data() called by wp_ajax hooks: {'ecsload', 'nopriv_ecsload'} **/
/** No params detected :-/ **/


/** Function ajax_overwrite() called by wp_ajax hooks: {'ecs_style_templates_overwrite'} **/
/** Parameters found in function ajax_overwrite(): {"post": ["widget_type", "template_name", "style_settings"]} **/
function ajax_overwrite(): void {
		$this->verify_ajax_write();

		$widget_type   = sanitize_key( $_POST['widget_type'] ?? '' );
		$template_name = sanitize_text_field( substr( $_POST['template_name'] ?? '', 0, 100 ) );
		$raw           = json_decode( stripslashes( $_POST['style_settings'] ?? '{}' ), true );
		$style         = $this->sanitize_style_settings( is_array( $raw ) ? $raw : [] );

		if ( empty( $widget_type ) || empty( $template_name ) ) {
			wp_send_json_error( 'Missing required fields' );
		}

		$all = get_option( self::OPTION_KEY, [] );
		if ( ! isset( $all[ $widget_type ][ $template_name ] ) ) {
			wp_send_json_error( 'Template not found' );
		}

		$all[ $widget_type ][ $template_name ]['updated_at']     = time();
		$all[ $widget_type ][ $template_name ]['style_settings'] = $style;

		update_option( self::OPTION_KEY, $all, false );
		wp_send_json_success( [ 'name' => $template_name ] );
	}


/** Function ajax_get_palettes() called by wp_ajax hooks: {'ecs_cw_get_palettes'} **/
/** No params detected :-/ **/


/** Function handle_ajax() called by wp_ajax hooks: {'{$action}'} **/
/** No params detected :-/ **/


/** Function ajax_delete() called by wp_ajax hooks: {'ecs_style_templates_delete'} **/
/** Parameters found in function ajax_delete(): {"post": ["widget_type", "template_name"]} **/
function ajax_delete(): void {
		$this->verify_ajax_write();

		$widget_type   = sanitize_key( $_POST['widget_type'] ?? '' );
		$template_name = sanitize_text_field( $_POST['template_name'] ?? '' );

		$all = get_option( self::OPTION_KEY, [] );
		if ( ! isset( $all[ $widget_type ][ $template_name ] ) ) {
			wp_send_json_error( 'Template not found' );
		}

		unset( $all[ $widget_type ][ $template_name ] );
		update_option( self::OPTION_KEY, $all, false );
		wp_send_json_success( [] );
	}


/** Function ajax_save_new() called by wp_ajax hooks: {'ecs_style_templates_save_new'} **/
/** Parameters found in function ajax_save_new(): {"post": ["widget_type", "template_name", "style_settings"]} **/
function ajax_save_new(): void {
		$this->verify_ajax_write();

		$widget_type   = sanitize_key( $_POST['widget_type'] ?? '' );
		$template_name = sanitize_text_field( substr( $_POST['template_name'] ?? '', 0, 100 ) );
		$raw           = json_decode( stripslashes( $_POST['style_settings'] ?? '{}' ), true );
		$style         = $this->sanitize_style_settings( is_array( $raw ) ? $raw : [] );

		if ( empty( $widget_type ) || empty( $template_name ) ) {
			wp_send_json_error( 'Missing required fields' );
		}

		$all = get_option( self::OPTION_KEY, [] );
		if ( isset( $all[ $widget_type ][ $template_name ] ) ) {
			wp_send_json_error( 'template_exists' );
		}

		$all[ $widget_type ][ $template_name ] = [
			'updated_at'     => time(),
			'style_settings' => $style,
			'meta'           => [
				'widget_type'       => $widget_type,
				'elementor_version' => ELEMENTOR_VERSION,
				'ecs_version'       => ECS_VERSION,
			],
		];

		update_option( self::OPTION_KEY, $all, false );
		wp_send_json_success( [ 'name' => $template_name ] );
	}


/** Function ajax_list() called by wp_ajax hooks: {'ecs_style_templates_list'} **/
/** Parameters found in function ajax_list(): {"post": ["widget_type"]} **/
function ajax_list(): void {
		$this->verify_ajax();

		$widget_type = sanitize_key( $_POST['widget_type'] ?? '' );
		$all         = get_option( self::OPTION_KEY, [] );
		$templates   = $all[ $widget_type ] ?? [];

		$list = [];
		foreach ( $templates as $name => $data ) {
			$list[] = [
				'name'       => $name,
				'updated_at' => $data['updated_at'] ?? 0,
			];
		}

		wp_send_json_success( $list );
	}


/** Function ajax_preview_layout() called by wp_ajax hooks: {'ecs_preview_layout'} **/
/** Parameters found in function ajax_preview_layout(): {"post": ["layout_id", "children"]} **/
function ajax_preview_layout(): void {
		check_ajax_referer( 'ecs-preview-layout', 'nonce' );

		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( [ 'message' => 'Unauthorized' ] );
			return;
		}

		$layout_id = absint( $_POST['layout_id'] ?? 0 );
		$children  = isset( $_POST['children'] ) && is_array( $_POST['children'] )
			? array_values( wp_unslash( $_POST['children'] ) )
			: [];

		if ( ! $layout_id || 'publish' !== get_post_status( $layout_id ) ) {
			wp_send_json_error( [ 'message' => 'Invalid template' ] );
			return;
		}

		// Same filter guard as in after_container_render: prevent shortcode
		// re-emission when the element cache builder filter is active.
		$filter_was_active = has_filter( 'elementor/element/should_render_shortcode', '__return_true' );
		if ( $filter_was_active ) {
			remove_filter( 'elementor/element/should_render_shortcode', '__return_true' );
		}

		// The widget class is loaded lazily via elementor/widgets/register, which
		// fires inside get_builder_content_for_display(). Since we need static
		// methods before that call, ensure the file is loaded explicitly here.
		if ( ! class_exists( 'ECS_Container_Placeholder_Widget', false ) ) {
			require_once $this->module_path() . 'widgets/class-ecs-container-placeholder-widget.php';
		}

		// Cycle through children like after_container_render: each pass fills one
		// template instance; overflow children feed the next pass.
		$batch      = $children;
		$first_pass = true;
		$output     = '';

		do {
			ECS_Container_Placeholder_Widget::set_pending_children( $batch );

			$batch_html = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $layout_id, false );

			if ( $first_pass && ! ECS_Container_Placeholder_Widget::any_consumed() && ! empty( $children ) ) {
				// Template has no DTE Placeholder widget — send error fallback HTML.
				ECS_Container_Placeholder_Widget::reset_pending_children();
				$output = '<div class="ecs-missing-placeholder">' . implode( '', $children ) . '</div>';
				break;
			}

			$output    .= $batch_html;
			$batch      = ECS_Container_Placeholder_Widget::get_overflow_children();
			$first_pass = false;
			ECS_Container_Placeholder_Widget::reset_pending_children();

		} while ( ! empty( $batch ) );

		if ( $filter_was_active ) {
			add_filter( 'elementor/element/should_render_shortcode', '__return_true' );
		}

		wp_send_json_success( [ 'html' => $output ] );
	}


/** Function ajax_get() called by wp_ajax hooks: {'ecs_style_templates_get'} **/
/** Parameters found in function ajax_get(): {"post": ["widget_type", "template_name"]} **/
function ajax_get(): void {
		$this->verify_ajax();

		$widget_type   = sanitize_key( $_POST['widget_type'] ?? '' );
		$template_name = sanitize_text_field( $_POST['template_name'] ?? '' );

		$all    = get_option( self::OPTION_KEY, [] );
		$preset = $all[ $widget_type ][ $template_name ] ?? null;

		if ( null === $preset ) {
			wp_send_json_error( 'Template not found' );
		}

		wp_send_json_success( $preset['style_settings'] ?? [] );
	}


/** Function ajax_save_palette() called by wp_ajax hooks: {'ecs_cw_save_palette'} **/
/** Parameters found in function ajax_save_palette(): {"post": ["name", "colors"]} **/
function ajax_save_palette(): void {
		check_ajax_referer( 'ecs_colorwheel', 'nonce' );

		// Palettes are a single site-wide option shared by every user of the
		// color picker, so writing one is not scoped to a post the caller
		// owns — edit_posts (held by Contributors) is not enough here.
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_send_json_error( 'Unauthorized', 403 );
		}

		$name   = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
		$colors = isset( $_POST['colors'] ) ? (array) $_POST['colors'] : [];

		if ( empty( $name ) ) {
			wp_send_json_error( 'Name required' );
		}

		$colors = array_values( array_filter(
			array_map( 'sanitize_hex_color', $colors )
		) );

		if ( empty( $colors ) ) {
			wp_send_json_error( 'No valid colors' );
		}

		$palettes = $this->get_palettes();

		if ( count( $palettes ) >= self::MAX_PALETTES ) {
			wp_send_json_error( 'Palette limit reached' );
		}

		// Overwrite if name exists.
		foreach ( $palettes as $i => $p ) {
			if ( $p['name'] === $name ) {
				$palettes[ $i ] = [ 'name' => $name, 'colors' => $colors ];
				update_option( self::OPTION_KEY, $palettes );
				wp_send_json_success( $palettes );
			}
		}

		$palettes[] = [ 'name' => $name, 'colors' => $colors ];
		update_option( self::OPTION_KEY, $palettes );
		wp_send_json_success( $palettes );
	}


/** Function ajax_delete_palette() called by wp_ajax hooks: {'ecs_cw_delete_palette'} **/
/** Parameters found in function ajax_delete_palette(): {"post": ["name"]} **/
function ajax_delete_palette(): void {
		check_ajax_referer( 'ecs_colorwheel', 'nonce' );

		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_send_json_error( 'Unauthorized', 403 );
		}

		$name     = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
		$palettes = $this->get_palettes();
		$palettes = array_values( array_filter( $palettes, fn( $p ) => $p['name'] !== $name ) );

		update_option( self::OPTION_KEY, $palettes );
		wp_send_json_success( $palettes );
	}


