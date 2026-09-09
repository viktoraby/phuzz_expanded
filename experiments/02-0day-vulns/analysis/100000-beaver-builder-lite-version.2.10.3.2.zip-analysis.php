<?php
/***
*
*Found actions: 10
*Found functions:10
*Extracted functions:8
*Total parameter names extracted: 6
*Overview: {'export_data': {'export_global_settings'}, 'FLBuilderExport::templates_data': {'fl_builder_export_templates_data'}, 'FLBuilderAdminSettings': {'fl_welcome_submit'}, '::disable': {'fl_builder_disable'}, 'advanced_submit': {'fl_advanced_submit'}, 'dismiss_callback': {'dismiss_fl_notice'}, 'import_data': {'import_global_settings'}, 'reset_data': {'reset_global_settings'}, 'FLBuilderUsage': {'fl_usage_toggle'}, '::duplicate_wpml_layout': {'fl_builder_duplicate_wpml_layout'}}
*
***/

/** Function export_data() called by wp_ajax hooks: {'export_global_settings'} **/
/** Parameters found in function export_data(): {"request": ["data"]} **/
function export_data() {

		check_admin_referer( 'fl_builder_import_export' );

		if ( current_user_can( 'manage_options' ) ) {

			$data = $_REQUEST['data'];

			$settings       = array();
			$admin_settings = array();

			$settings['builder_global_settings'] = FLBuilderModel::get_global_settings();

			foreach ( FLBuilderAdminSettings::registered_settings() as $setting ) {
				$admin_settings[ $setting ] = get_option( $setting );
			}

			$settings['admin_settings'] = $admin_settings;

			// global styles/colors
			if ( class_exists( 'FLBuilderGlobalStyles' ) ) {

				$globals = FLBuilderGlobalStyles::get_settings( false );
				$colors  = $globals->colors;
				unset( $globals->colors );
				$settings['global_styles'] = $globals;
				$settings['global_colors'] = $colors;
			}

			// sort data
			if ( 'false' === $data['global_all'] ) {
				if ( 'false' === $data['global'] ) {
					unset( $settings['builder_global_settings'] );
				}
				if ( 'false' === $data['admin'] ) {
					unset( $settings['admin_settings'] );
				}
				if ( 'false' === $data['colors'] ) {
					unset( $settings['global_colors'] );
				}
				if ( 'false' === $data['styles'] ) {
					unset( $settings['global_styles'] );
				}

				// prefix
				if ( isset( $settings['global_colors'] ) && isset( $globals->prefix ) ) {
					$settings['global_colors_prefix'] = $globals->prefix;
				}
			}

			if ( ! $settings ) {
				wp_send_json_error( 'No settings found' );
			}
			wp_send_json_success( array(
				'selected' => $data,
				'settings' => serialize( $settings ),
			) );
		} else {
			wp_send_json_error();
		}
	}


/** Function FLBuilderExport::templates_data() called by wp_ajax hooks: {'fl_builder_export_templates_data'} **/
/** Parameters found in function FLBuilderExport::templates_data(): {"post": ["type"]} **/
function templates_data() {

		check_admin_referer( 'fl_builder_export_nonce' );

		$type  = isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : 'fl-builder-template';
		$data  = array();
		$query = new WP_Query( array(
			'post_type'      => $type,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'posts_per_page' => '-1',
		) );

		foreach ( $query->posts as $post ) {
			$data[] = array(
				'id'    => $post->ID,
				'title' => $post->post_title,
			);
		}

		echo FLBuilderUtils::json_encode( $data );

		die();
	}


/** Function FLBuilderAdminSettings() called by wp_ajax hooks: {'fl_welcome_submit'} **/
/** No function found :-/ **/


/** Function ::disable() called by wp_ajax hooks: {'fl_builder_disable'} **/
/** Parameters found in function ::disable(): {"post": ["_wpnonce"]} **/
function disable() {
		if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'fl-enable-editor' ) ) {
			$post_id = self::get_post_id();
			$user_id = get_current_user_id();
			$post    = get_post( $post_id );
			if ( current_user_can( 'edit_others_posts' ) || ( $post->post_author === $user_id ) ) {
				update_post_meta( $post_id, '_fl_builder_enabled', false );
			}
		}
		exit;
	}


/** Function advanced_submit() called by wp_ajax hooks: {'fl_advanced_submit'} **/
/** Parameters found in function advanced_submit(): {"post": ["action", "_wpnonce", "setting", "type", "value"]} **/
function advanced_submit() {
		if ( ! isset( $_POST['action'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'advanced' ) ) {
			wp_send_json_error();
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Insufficient permissions' );
		}
		$setting = isset( $_POST['setting'] ) ? sanitize_key( $_POST['setting'] ) : '';
		if ( ! array_key_exists( $setting, self::get_settings() ) ) {
			wp_send_json_error( 'Invalid setting' );
		}
		if ( ! isset( $_POST['type'] ) ) {
			$value = 'true' === $_POST['value'] ? '1' : '0';
		} else {
			$value = (int) $_POST['value'];
		}
		update_option( "_fl_builder_{$setting}", $value );
		wp_send_json_success();
	}


/** Function dismiss_callback() called by wp_ajax hooks: {'dismiss_fl_notice'} **/
/** Parameters found in function dismiss_callback(): {"post": ["notice_nonce", "notice"]} **/
function dismiss_callback() {
		if ( ! isset( $_POST['notice_nonce'] ) || ! wp_verify_nonce( $_POST['notice_nonce'], 'dismiss_fl_notice' )
		) {
			print 'Sorry, your nonce did not verify.';
			exit;
		} else {
			$user_id     = get_current_user_id();
			$dismissed   = (array) get_user_meta( $user_id, 'fl_dismissed_wp_notices', true );
			$dismissed[] = $_POST['notice'];
			update_user_meta( $user_id, 'fl_dismissed_wp_notices', $dismissed );
			exit();
		}
	}


/** Function import_data() called by wp_ajax hooks: {'import_global_settings'} **/
/** Parameters found in function import_data(): {"post": ["importid"]} **/
function import_data() {

		check_admin_referer( 'fl_builder_import_export' );

		if ( current_user_can( 'manage_options' ) ) {

			$id   = $_POST['importid'];
			$path = get_attached_file( $id );

			if ( ! $path ) {
				wp_send_json_error( 'Could not find file!' );
			}

			$data = file_get_contents( $path );

			if ( is_object( json_decode( $data ) ) ) {
				wp_send_json_error( 'Exports completed with versions prior to 2.8.1 are not compatible due to a change in format of export data. Import aborted.' );
			}

			if ( ! is_serialized( $data ) ) {
				wp_send_json_error( 'Could not parse file!' );
			}

			$data = maybe_unserialize( $data );

			if ( isset( $data['builder_global_settings'] ) ) {
				update_option( '_fl_builder_settings', $data['builder_global_settings'], true );
			}

			// loop through admin settings
			if ( isset( $data['admin_settings'] ) ) {
				$settings = $data['admin_settings'];

				foreach ( $settings as $key => $setting ) {
					update_option( $key, $setting, true );
				}
			}

			$globals = get_option( '_fl_builder_styles' );
			if ( isset( $data['global_styles'] ) ) {
				$backup_colors        = isset( $globals->colors ) ? $globals->colors : array();
				$new_settings         = (object) $data['global_styles'];
				$new_settings->colors = $backup_colors;
				$globals              = $new_settings;
				FLBuilderUtils::update_option( '_fl_builder_styles', $globals, true );
			}

			// global styles/colors...
			if ( isset( $data['global_colors'] ) ) {
				// get current settings and swap out colours
				$globals = $globals ? $globals : FLBuilderGlobalStyles::get_settings( false );
				$current = $globals->colors;

				$new = array_merge( (array) $current, (array) $data['global_colors'] );

				// filter out duplicates
				$serialized      = array_map( 'serialize', $new );
				$unique          = array_unique( $serialized );
				$globals->colors = array_intersect_key( $new, $unique );

				foreach ( $globals->colors as $k => $color ) {
					if ( empty( $color ) || ! $color['color'] ) {
						unset( $globals->colors[ $k ] );
					}
				}

				if ( isset( $data->global_colors_prefix ) ) {
					$globals->prefix = $data->global_colors_prefix;
				}
				// if no 0 key shift everything down
				if ( ! isset( $globals->colors[0] ) && ! empty( $globals->colors ) ) {
					$fixed_globals    = array();
					$fixed_globals[0] = array_shift( $globals->colors );
					$globals->colors  = array_merge( $fixed_globals, $globals->colors );
				}
				FLBuilderUtils::update_option( '_fl_builder_styles', $globals, true );
			}
			FLBuilderModel::delete_asset_cache_for_all_posts();
			wp_send_json_success();
		} else {
			wp_send_json_error();
		}
	}


/** Function reset_data() called by wp_ajax hooks: {'reset_global_settings'} **/
/** No params detected :-/ **/


/** Function FLBuilderUsage() called by wp_ajax hooks: {'fl_usage_toggle'} **/
/** No function found :-/ **/


/** Function ::duplicate_wpml_layout() called by wp_ajax hooks: {'fl_builder_duplicate_wpml_layout'} **/
/** No params detected :-/ **/


