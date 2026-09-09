<?php
/***
*
*Found actions: 13
*Found functions:13
*Extracted functions:13
*Total parameter names extracted: 11
*Overview: {'import_demo_single_data_ajax_callback': {'kadence_import_single_data'}, 'remove_past_data_ajax_callback': {'kadence_remove_past_import_data'}, 'template_data_ajax_callback': {'kadence_import_get_template_data'}, 'template_data_reload_ajax_callback': {'kadence_import_reload_template_data'}, 'initial_install_ajax_callback': {'kadence_import_initial'}, 'after_all_import_data_ajax_callback': {'kadence_after_import_data'}, 'check_plugin_data_ajax_callback': {'kadence_check_plugin_data'}, 'install_plugins_ajax_callback': {'kadence_import_install_plugins'}, 'subscribe_ajax_callback': {'kadence_import_subscribe'}, 'import_customizer_data_ajax_callback': {'kadence_import_customizer_data'}, 'import_demo_data_ajax_callback': {'kadence_import_demo_data'}, 'ajax_dismiss_starter_notice': {'kadence_starter_dismiss_notice'}, 'ajax_reset': {'kadence_starter_reset'}}
*
***/

/** Function import_demo_single_data_ajax_callback() called by wp_ajax hooks: {'kadence_import_single_data'} **/
/** Parameters found in function import_demo_single_data_ajax_callback(): {"post": ["selected", "builder", "page_id", "override_colors", "override_fonts", "palette", "font"]} **/
function import_demo_single_data_ajax_callback() {
		// Try to update PHP memory limit (so that it does not run out of it).
		ini_set( 'memory_limit', apply_filters( 'kadence-starter-templates/import_memory_limit', '350M' ) );
		// Increase PHP max execution time. Just in case, even though the AJAX calls are only 25 sec long.
		if ( strpos( ini_get( 'disable_functions' ), 'set_time_limit' ) === false ) {
			set_time_limit( apply_filters( 'kadence-starter-templates/set_time_limit_for_demo_data_import', 300 ) );
		}

		// Verify if the AJAX call is valid (checks nonce and current_user_can).
		Helpers::verify_ajax_call();
		// Is this a new AJAX call to continue the previous import?
		$use_existing_importer_data = $this->use_existing_importer_data();

		if ( ! $use_existing_importer_data ) {
			// Create a date and time string to use for demo and log file names.
			Helpers::set_demo_import_start_time();

			if ( apply_filters( 'kadence_starter_templates_save_log_files', false ) ) {
				// Define log file path.
				$this->log_file_path = Helpers::get_log_path();
			} else {
				$this->log_file_path = '';
			}
			// Get selected file index or set it to 0.
			$this->selected_index = empty( $_POST['selected'] ) ? '' : sanitize_text_field( $_POST['selected'] );
			$this->selected_builder = empty( $_POST['builder'] ) ? 'blocks' : sanitize_text_field( $_POST['builder'] );
			$this->selected_page = empty( $_POST['page_id'] ) ? '' : sanitize_text_field( $_POST['page_id'] );
			$this->override_colors = 'true' === $_POST['override_colors'] ? true : false;
			$this->override_fonts = 'true' === $_POST['override_fonts'] ? true : false;
			$this->selected_palette = empty( $_POST['palette'] ) ? '' : sanitize_text_field( $_POST['palette'] );
			$this->selected_font    = empty( $_POST['font'] ) ? '' : sanitize_text_field( $_POST['font'] );

			if ( empty( $this->import_files ) || ( is_array( $this->import_files ) && ! isset( $this->import_files[ $this->selected_index ] ) ) ) {
				$template_database  = Template_Database_Importer::get_instance();
				$this->import_files = $template_database->get_importer_files( $this->selected_index, $this->selected_builder );
			}
			if ( ! isset( $this->import_files[ $this->selected_index ] ) ) {
				wp_send_json_error();
			}
			/**
			 * 1). Prepare import files.
			 * Predefined import files via filter: kadence-starter-templates/import_files
			 */
			if ( ! empty( $this->import_files[ $this->selected_index ] ) && ! empty( $this->selected_page ) && isset( $this->import_files[ $this->selected_index ]['pages'] ) && isset( $this->import_files[ $this->selected_index ]['pages'][ $this->selected_page ] ) ) { // Use predefined import files from wp filter: kadence-starter-templates/import_files.

				// Download the import files (content, widgets and customizer files).
				$this->selected_import_files = Helpers::download_import_file( $this->import_files[ $this->selected_index ], $this->selected_page );

				// Check Errors.
				if ( is_wp_error( $this->selected_import_files ) ) {
					// Write error to log file and send an AJAX response with the error.
					Helpers::log_error_and_send_ajax_response(
						$this->selected_import_files->get_error_message(),
						$this->log_file_path,
						esc_html__( 'Downloaded files', 'kadence-starter-templates' )
					);
				}
				if ( apply_filters( 'kadence_starter_templates_save_log_files', false ) ) {
					// Add this message to log file.
					$log_added = Helpers::append_to_file(
						sprintf(
							__( 'The import files for: %s were successfully downloaded!', 'kadence-starter-templates' ),
							$this->import_files[ $this->selected_index ]['slug']
						) . Helpers::import_file_info( $this->selected_import_files ),
						$this->log_file_path,
						esc_html__( 'Downloaded files' , 'kadence-starter-templates' )
					);
				}
			} else {
				// Send JSON Error response to the AJAX call.
				wp_send_json( esc_html__( 'No import files specified!', 'kadence-starter-templates' ) );
			}
		}

		// Save the initial import data as a transient, so other import parts (in new AJAX calls) can use that data.
		Helpers::set_import_data_transient( $this->get_current_importer_data() );

		// If elementor make sure the defaults are off.
		$elementor = false;
		if ( isset( $this->import_files[ $this->selected_index ]['type'] ) && 'elementor' === $this->import_files[ $this->selected_index ]['type'] ) {
			update_option( 'elementor_disable_color_schemes', 'yes' );
			update_option( 'elementor_disable_typography_schemes', 'yes' );
			$elementor = true;
			if ( class_exists( 'Kadence\Theme' ) ) {
				$component = \Kadence\Theme::instance()->components['elementor'];
				if ( $component ) {
					$component->elementor_add_theme_colors();
				}
			}
		}

		/**
		 * 3). Import content (if the content XML file is set for this import).
		 * Returns any errors greater then the "warning" logger level, that will be displayed on front page.
		 */
		$new_post = '';
		if ( ! empty( $this->selected_import_files['content'] ) ) {
			$meta = ( ! empty( $this->import_files[ $this->selected_index ] ) && ! empty( $this->selected_page ) && isset( $this->import_files[ $this->selected_index ]['pages'] ) && isset( $this->import_files[ $this->selected_index ]['pages'][ $this->selected_page ] ) && isset( $this->import_files[ $this->selected_index ]['pages'][ $this->selected_page ]['meta'] ) ? $this->import_files[ $this->selected_index ]['pages'][ $this->selected_page ]['meta'] : 'inherit' );
			$logger = $this->importer->import_content( $this->selected_import_files['content'], true, $meta, $elementor );
			if ( is_object( $logger ) && property_exists( $logger, 'error_output' ) && $logger->error_output ) {
				$this->append_to_frontend_error_messages( $logger->error_output );
			} elseif ( is_object( $logger ) && $logger->messages ) {
				$messages = $logger->messages;
				if ( isset( $messages[1] ) && isset( $messages[1]['level'] ) && 'debug' == $messages[1]['level'] && isset( $messages[1]['message'] ) && ! empty( $messages[1]['message'] ) ) {
					$pieces   = explode( ' ', $messages[1]['message'] );
					$new_post = array_pop( $pieces );
				}
			}
		}

		if ( $this->override_colors ) {
			if ( $this->selected_palette && ! empty( $this->selected_palette ) ) {
				$palette_presets = json_decode( '{"base":[{"color":"#2B6CB0"},{"color":"#265E9A"},{"color":"#222222"},{"color":"#3B3B3B"},{"color":"#515151"},{"color":"#626262"},{"color":"#E1E1E1"},{"color":"#F7F7F7"},{"color":"#ffffff"}],"bright":[{"color":"#255FDD"},{"color":"#00F2FF"},{"color":"#1A202C"},{"color":"#2D3748"},{"color":"#4A5568"},{"color":"#718096"},{"color":"#EDF2F7"},{"color":"#F7FAFC"},{"color":"#ffffff"}],"darkmode":[{"color":"#3296ff"},{"color":"#003174"},{"color":"#ffffff"},{"color":"#f7fafc"},{"color":"#edf2f7"},{"color":"#cbd2d9"},{"color":"#2d3748"},{"color":"#252c39"},{"color":"#1a202c"}],"orange":[{"color":"#e47b02"},{"color":"#ed8f0c"},{"color":"#1f2933"},{"color":"#3e4c59"},{"color":"#52606d"},{"color":"#7b8794"},{"color":"#f3f4f7"},{"color":"#f9f9fb"},{"color":"#ffffff"}],"pinkish":[{"color":"#E21E51"},{"color":"#4d40ff"},{"color":"#040037"},{"color":"#032075"},{"color":"#514d7c"},{"color":"#666699"},{"color":"#deddeb"},{"color":"#efeff5"},{"color":"#f8f9fa"}],"pinkishdark":[{"color":"#E21E51"},{"color":"#4d40ff"},{"color":"#f8f9fa"},{"color":"#efeff5"},{"color":"#deddeb"},{"color":"#c3c2d6"},{"color":"#514d7c"},{"color":"#221e5b"},{"color":"#040037"}],"green":[{"color":"#049f82"},{"color":"#008f72"},{"color":"#222222"},{"color":"#353535"},{"color":"#454545"},{"color":"#676767"},{"color":"#eeeeee"},{"color":"#f7f7f7"},{"color":"#ffffff"}],"fire":[{"color":"#dd6b20"},{"color":"#cf3033"},{"color":"#27241d"},{"color":"#423d33"},{"color":"#504a40"},{"color":"#625d52"},{"color":"#e8e6e1"},{"color":"#faf9f7"},{"color":"#ffffff"}],"mint":[{"color":"#2cb1bc"},{"color":"#13919b"},{"color":"#0f2a43"},{"color":"#133453"},{"color":"#587089"},{"color":"#829ab1"},{"color":"#e0fcff"},{"color":"#f5f7fa"},{"color":"#ffffff"}],"rich":[{"color":"#295CFF"},{"color":"#0E94FF"},{"color":"#1C0D5A"},{"color":"#3D3D3D"},{"color":"#57575D"},{"color":"#636363"},{"color":"#E1EBEE"},{"color":"#EFF7FB"},{"color":"#ffffff"}],"fem":[{"color":"#D86C97"},{"color":"#282828"},{"color":"#282828"},{"color":"#333333"},{"color":"#4d4d4d"},{"color":"#646464"},{"color":"#f7dede"},{"color":"#F6F2EF"},{"color":"#ffffff"}],"hot":[{"color":"#FF5698"},{"color":"#000000"},{"color":"#020202"},{"color":"#020202"},{"color":"#4E4E4E"},{"color":"#808080"},{"color":"#FDEDEC"},{"color":"#FDF6EE"},{"color":"#ffffff"}],"bold":[{"color":"#000000"},{"color":"#D1A155"},{"color":"#000000"},{"color":"#010101"},{"color":"#111111"},{"color":"#282828"},{"color":"#F6E7BC"},{"color":"#F9F7F7"},{"color":"#ffffff"}],"teal":[{"color":"#7ACFC4"},{"color":"#044355"},{"color":"#000000"},{"color":"#010101"},{"color":"#111111"},{"color":"#282828"},{"color":"#F5ECE5"},{"color":"#F9F7F7"},{"color":"#ffffff"}]}', true );
				if ( isset( $palette_presets[ $this->selected_palette ] ) ) {
					$default = json_decode( '{"palette":[{"color":"#3182CE","slug":"palette1","name":"Palette Color 1"},{"color":"#2B6CB0","slug":"palette2","name":"Palette Color 2"},{"color":"#1A202C","slug":"palette3","name":"Palette Color 3"},{"color":"#2D3748","slug":"palette4","name":"Palette Color 4"},{"color":"#4A5568","slug":"palette5","name":"Palette Color 5"},{"color":"#718096","slug":"palette6","name":"Palette Color 6"},{"color":"#EDF2F7","slug":"palette7","name":"Palette Color 7"},{"color":"#F7FAFC","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"second-palette":[{"color":"#3182CE","slug":"palette1","name":"Palette Color 1"},{"color":"#2B6CB0","slug":"palette2","name":"Palette Color 2"},{"color":"#1A202C","slug":"palette3","name":"Palette Color 3"},{"color":"#2D3748","slug":"palette4","name":"Palette Color 4"},{"color":"#4A5568","slug":"palette5","name":"Palette Color 5"},{"color":"#718096","slug":"palette6","name":"Palette Color 6"},{"color":"#EDF2F7","slug":"palette7","name":"Palette Color 7"},{"color":"#F7FAFC","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"third-palette":[{"color":"#3182CE","slug":"palette1","name":"Palette Color 1"},{"color":"#2B6CB0","slug":"palette2","name":"Palette Color 2"},{"color":"#1A202C","slug":"palette3","name":"Palette Color 3"},{"color":"#2D3748","slug":"palette4","name":"Palette Color 4"},{"color":"#4A5568","slug":"palette5","name":"Palette Color 5"},{"color":"#718096","slug":"palette6","name":"Palette Color 6"},{"color":"#EDF2F7","slug":"palette7","name":"Palette Color 7"},{"color":"#F7FAFC","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"active":"palette"}', true );
					$default['palette'][0]['color'] = $palette_presets[ $this->selected_palette ][0]['color'];
					$default['palette'][1]['color'] = $palette_presets[ $this->selected_palette ][1]['color'];
					$default['palette'][2]['color'] = $palette_presets[ $this->selected_palette ][2]['color'];
					$default['palette'][3]['color'] = $palette_presets[ $this->selected_palette ][3]['color'];
					$default['palette'][4]['color'] = $palette_presets[ $this->selected_palette ][4]['color'];
					$default['palette'][5]['color'] = $palette_presets[ $this->selected_palette ][5]['color'];
					$default['palette'][6]['color'] = $palette_presets[ $this->selected_palette ][6]['color'];
					$default['palette'][7]['color'] = $palette_presets[ $this->selected_palette ][7]['color'];
					$default['palette'][8]['color'] = $palette_presets[ $this->selected_palette ][8]['color'];
					update_option( 'kadence_global_palette', json_encode( $default ) );
				}
			} else {
				/**
				 * Execute the customizer import actions.
				 */
				do_action( 'kadence-starter-templates/customizer_import_color_only_execution', $this->selected_import_files );
			}
		}
		if ( $this->override_fonts ) {
			if ( class_exists( 'Kadence\Theme' ) ) {
				if ( $this->selected_font && ! empty( $this->selected_font ) ) {
					switch ( $this->selected_font ) {
						case 'montserrat':
							$current = \Kadence\kadence()->option( 'heading_font' );
							$current['family']  = 'Montserrat';
							$current['google']  = true;
							$current['variant'] = array( '100', '100italic', '200', '200italic', '300', '300italic', 'regular', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic' );
							set_theme_mod( 'heading_font', $current );
							$body = \Kadence\kadence()->option( 'base_font' );
							$body['family'] = 'Source Sans Pro';
							$body['google'] = true;
							set_theme_mod( 'base_font', $body );
							break;
						case 'playfair':
							$current = \Kadence\kadence()->option( 'heading_font' );
							$current['family']  = 'Playfair Display';
							$current['google']  = true;
							$current['variant'] = array( 'regular', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic' );
							set_theme_mod( 'heading_font', $current );
							$h1_font = \Kadence\kadence()->option( 'h1_font' );
							$h1_font['weight'] = 'normal';
							$h1_font['variant'] = 'regualar';
							set_theme_mod( 'h1_font', $h1_font );
							$h2_font = \Kadence\kadence()->option( 'h2_font' );
							$h2_font['weight'] = 'normal';
							$h2_font['variant'] = 'regualar';
							set_theme_mod( 'h2_font', $h2_font );
							$h3_font = \Kadence\kadence()->option( 'h3_font' );
							$h3_font['weight'] = 'normal';
							$h3_font['variant'] = 'regualar';
							set_theme_mod( 'h3_font', $h3_font );
							$h4_font = \Kadence\kadence()->option( 'h4_font' );
							$h4_font['weight'] = 'normal';
							$h4_font['variant'] = 'regualar';
							set_theme_mod( 'h4_font', $h4_font );
							$h5_font = \Kadence\kadence()->option( 'h5_font' );
							$h5_font['weight'] = 'normal';
							$h5_font['variant'] = 'regualar';
							set_theme_mod( 'h5_font', $h5_font );
							$body = \Kadence\kadence()->option( 'base_font' );
							$body['family'] = 'Raleway';
							$body['google'] = true;
							set_theme_mod( 'base_font', $body );
							break;
						case 'oswald':
							$current = \Kadence\kadence()->option( 'heading_font' );
							$current['family']  = 'Oswald';
							$current['google']  = true;
							$current['variant'] = array( '200', '300', 'regular', '500', '600', '700' );
							set_theme_mod( 'heading_font', $current );
							$body = \Kadence\kadence()->option( 'base_font' );
							$body['family'] = 'Open Sans';
							$body['google'] = true;
							set_theme_mod( 'base_font', $body );
							break;
						case 'antic':
							$current = \Kadence\kadence()->option( 'heading_font' );
							$current['family']  = 'Antic Didone';
							$current['google']  = true;
							$current['variant'] = array( 'regular' );
							set_theme_mod( 'heading_font', $current );
							$h1_font = \Kadence\kadence()->option( 'h1_font' );
							$h1_font['weight'] = 'normal';
							$h1_font['variant'] = 'regualar';
							set_theme_mod( 'h1_font', $h1_font );
							$h2_font = \Kadence\kadence()->option( 'h2_font' );
							$h2_font['weight'] = 'normal';
							$h2_font['variant'] = 'regualar';
							set_theme_mod( 'h2_font', $h2_font );
							$h3_font = \Kadence\kadence()->option( 'h3_font' );
							$h3_font['weight'] = 'normal';
							$h3_font['variant'] = 'regualar';
							set_theme_mod( 'h3_font', $h3_font );
							$h4_font = \Kadence\kadence()->option( 'h4_font' );
							$h4_font['weight'] = 'normal';
							$h4_font['variant'] = 'regualar';
							set_theme_mod( 'h4_font', $h4_font );
							$h5_font = \Kadence\kadence()->option( 'h5_font' );
							$h5_font['weight'] = 'normal';
							$h5_font['variant'] = 'regualar';
							set_theme_mod( 'h5_font', $h5_font );
							$body = \Kadence\kadence()->option( 'base_font' );
							$body['family'] = 'Raleway';
							$body['google'] = true;
							set_theme_mod( 'base_font', $body );
							break;
						case 'gilda':
							$current = \Kadence\kadence()->option( 'heading_font' );
							$current['family']  = 'Gilda Display';
							$current['google']  = true;
							$current['variant'] = array( 'regular' );
							set_theme_mod( 'heading_font', $current );
							$h1_font = \Kadence\kadence()->option( 'h1_font' );
							$h1_font['weight'] = 'normal';
							$h1_font['variant'] = 'regualar';
							set_theme_mod( 'h1_font', $h1_font );
							$h2_font = \Kadence\kadence()->option( 'h2_font' );
							$h2_font['weight'] = 'normal';
							$h2_font['variant'] = 'regualar';
							set_theme_mod( 'h2_font', $h2_font );
							$h3_font = \Kadence\kadence()->option( 'h3_font' );
							$h3_font['weight'] = 'normal';
							$h3_font['variant'] = 'regualar';
							set_theme_mod( 'h3_font', $h3_font );
							$h4_font = \Kadence\kadence()->option( 'h4_font' );
							$h4_font['weight'] = 'normal';
							$h4_font['variant'] = 'regualar';
							set_theme_mod( 'h4_font', $h4_font );
							$h5_font = \Kadence\kadence()->option( 'h5_font' );
							$h5_font['weight'] = 'normal';
							$h5_font['variant'] = 'regualar';
							set_theme_mod( 'h5_font', $h5_font );
							$body = \Kadence\kadence()->option( 'base_font' );
							$body['family'] = 'Raleway';
							$body['google'] = true;
							set_theme_mod( 'base_font', $body );
							break;
						case 'cormorant':
							$current = \Kadence\kadence()->option( 'heading_font' );
							$current['family']  = 'Cormorant Garamond';
							$current['google']  = true;
							$current['variant'] = array( '300', '300italic', 'regular', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' );
							set_theme_mod( 'heading_font', $current );
							$body = \Kadence\kadence()->option( 'base_font' );
							$body['family'] = 'Proza Libre';
							$body['google'] = true;
							set_theme_mod( 'base_font', $body );
							break;
						case 'libre':
							$current = \Kadence\kadence()->option( 'heading_font' );
							$current['family']  = 'Libre Franklin';
							$current['google']  = true;
							$current['variant'] = array( '100', '100italic', '200', '200italic', '300', '300italic', 'regular', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic' );
							set_theme_mod( 'heading_font', $current );
							$body = \Kadence\kadence()->option( 'base_font' );
							$body['family'] = 'Libre Baskerville';
							$body['google'] = true;
							set_theme_mod( 'base_font', $body );
							break;

						case 'lora':
							$current = \Kadence\kadence()->option( 'heading_font' );
							$current['family']  = 'Lora';
							$current['google']  = true;
							$current['variant'] = array( 'regular', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' );
							set_theme_mod( 'heading_font', $current );
							$h1_font = \Kadence\kadence()->option( 'h1_font' );
							$h1_font['weight'] = 'normal';
							$h1_font['variant'] = 'regualar';
							set_theme_mod( 'h1_font', $h1_font );
							$h2_font = \Kadence\kadence()->option( 'h2_font' );
							$h2_font['weight'] = 'normal';
							$h2_font['variant'] = 'regualar';
							set_theme_mod( 'h2_font', $h2_font );
							$h3_font = \Kadence\kadence()->option( 'h3_font' );
							$h3_font['weight'] = 'normal';
							$h3_font['variant'] = 'regualar';
							set_theme_mod( 'h3_font', $h3_font );
							$h4_font = \Kadence\kadence()->option( 'h4_font' );
							$h4_font['weight'] = 'normal';
							$h4_font['variant'] = 'regualar';
							set_theme_mod( 'h4_font', $h4_font );
							$h5_font = \Kadence\kadence()->option( 'h5_font' );
							$h5_font['weight'] = 'normal';
							$h5_font['variant'] = 'regualar';
							set_theme_mod( 'h5_font', $h5_font );
							$body = \Kadence\kadence()->option( 'base_font' );
							$body['family'] = 'Merriweather';
							$body['google'] = true;
							$body['weight'] = '300';
							$body['variant'] = '300';
							set_theme_mod( 'base_font', $body );
							break;

						case 'proza':
							$current = \Kadence\kadence()->option( 'heading_font' );
							$current['family']  = 'Proza Libre';
							$current['google']  = true;
							$current['variant'] = array( 'regular', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic' );
							set_theme_mod( 'heading_font', $current );
							$body = \Kadence\kadence()->option( 'base_font' );
							$body['family'] = 'Open Sans';
							$body['google'] = true;
							set_theme_mod( 'base_font', $body );
							break;

						case 'worksans':
							$current = \Kadence\kadence()->option( 'heading_font' );
							$current['family']  = 'Work Sans';
							$current['google']  = true;
							$current['variant'] = array( '100', '100italic', '200', '200italic', '300', '300italic', 'regular', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic' );
							set_theme_mod( 'heading_font', $current );
							$body = \Kadence\kadence()->option( 'base_font' );
							$body['family'] = 'Work Sans';
							$body['google'] = true;
							set_theme_mod( 'base_font', $body );
							break;

						case 'josefin':
							$current = \Kadence\kadence()->option( 'heading_font' );
							$current['family']  = 'Josefin Sans';
							$current['google']  = true;
							$current['variant'] = array( '100', '100italic', '200', '200italic', '300', '300italic', 'regular', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' );
							set_theme_mod( 'heading_font', $current );
							$body = \Kadence\kadence()->option( 'base_font' );
							$body['family'] = 'Lato';
							$body['google'] = true;
							set_theme_mod( 'base_font', $body );
							break;

						case 'nunito':
							$current = \Kadence\kadence()->option( 'heading_font' );
							$current['family']  = 'Nunito';
							$current['google']  = true;
							$current['variant'] = array( '200', '200italic', '300', '300italic', 'regular', 'italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic' );
							set_theme_mod( 'heading_font', $current );
							$body = \Kadence\kadence()->option( 'base_font' );
							$body['family'] = 'Roboto';
							$body['google'] = true;
							set_theme_mod( 'base_font', $body );
							break;
						case 'rubik':
							$current = \Kadence\kadence()->option( 'heading_font' );
							$current['family']  = 'Rubik';
							$current['google']  = true;
							$current['variant'] = array( '300', '300italic', 'regular', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic' );
							set_theme_mod( 'heading_font', $current );
							$body = \Kadence\kadence()->option( 'base_font' );
							$body['family'] = 'Karla';
							$body['google'] = true;
							set_theme_mod( 'base_font', $body );
							break;
					}
				} else {
					/**
					 * Execute the customizer import actions.
					 */
					do_action( 'kadence-starter-templates/customizer_import_font_only_execution', $this->selected_import_files );
				}
				foreach ( array( 'h1_font', 'h2_font', 'h3_font', 'h4_font', 'h5_font', 'h6_font', 'h5_font', 'title_above_font' ) as $value ) {
					$font_settings = \Kadence\kadence()->option( $value );
					$font_settings['family'] = 'inherit';
					$font_settings['google'] = false;
					set_theme_mod( $value, $font_settings );
				}
			}
		}

		// If elementor make sure the defaults are off.
		if ( isset( $this->import_files[ $this->selected_index ]['type'] ) && 'elementor' === $this->import_files[ $this->selected_index ]['type'] ) {
			if ( class_exists( 'Elementor\Plugin' ) ) {
				\Elementor\Plugin::instance()->files_manager->clear_cache();
			}
		}

		// Send a JSON response with final report.
		$this->final_response( $new_post );
	}


/** Function remove_past_data_ajax_callback() called by wp_ajax hooks: {'kadence_remove_past_import_data'} **/
/** Parameters found in function remove_past_data_ajax_callback(): {"get": ["force_delete_kit"]} **/
function remove_past_data_ajax_callback() {
		Helpers::verify_ajax_call();

		if ( ! current_user_can( 'customize' ) ) {
			wp_send_json_error();
		}
		global $wpdb;
		// Prevents elementor from pushing out an confrimation and breaking the import.
		$_GET['force_delete_kit'] = true;
		$removed_content = true;

		$post_ids = $wpdb->get_col( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_kadence_starter_templates_imported_post'" );
		$term_ids = $wpdb->get_col( "SELECT term_id FROM {$wpdb->termmeta} WHERE meta_key='_kadence_starter_templates_imported_term'" );
		if ( isset( $post_ids ) && is_array( $post_ids ) ) {
			foreach ( $post_ids as $post_id ) {
				$worked = wp_delete_post( $post_id, true );
				if ( false === $worked ) {
					$removed_content = false;
				}
			}
		}
		if ( isset( $term_ids ) && is_array( $term_ids ) ) {
			foreach ( $term_ids as $term_id ) {
				$term = get_term( $term_id );
				if ( ! is_wp_error( $term ) ) {
					wp_delete_term( $term_id, $term->taxonomy );
				}
			}
		}

		if ( false === $removed_content ) {
			wp_send_json_error();
		} else {
			wp_send_json( array( 'status' => 'removeSuccess' ) );
		}
	}


/** Function template_data_ajax_callback() called by wp_ajax hooks: {'kadence_import_get_template_data'} **/
/** Parameters found in function template_data_ajax_callback(): {"post": ["api_key", "api_email", "template_type"]} **/
function template_data_ajax_callback() {
		// Verify if the AJAX call is valid (checks nonce and current_user_can).
		Helpers::verify_ajax_call();
		$this->api_key       = empty( $_POST['api_key'] ) ? '' : sanitize_text_field( $_POST['api_key'] );
		$this->api_email     = empty( $_POST['api_email'] ) ? '' : sanitize_text_field( $_POST['api_email'] );
		$this->template_type = empty( $_POST['template_type'] ) ? 'blocks' : sanitize_text_field( $_POST['template_type'] );
		// Do you have the data?
		$get_data = $this->get_template_data();
		if ( ! $get_data ) {
			// Send JSON Error response to the AJAX call.
			wp_send_json( esc_html__( 'No template data', 'kadence-starter-templates' ) );
		} else {
			wp_send_json( $get_data );
		}
		die;
	}


/** Function template_data_reload_ajax_callback() called by wp_ajax hooks: {'kadence_import_reload_template_data'} **/
/** Parameters found in function template_data_reload_ajax_callback(): {"post": ["api_key", "api_email", "template_type"]} **/
function template_data_reload_ajax_callback() {

		// Verify if the AJAX call is valid (checks nonce and current_user_can).
		Helpers::verify_ajax_call();
		$this->api_key       = empty( $_POST['api_key'] ) ? '' : sanitize_text_field( $_POST['api_key'] );
		$this->api_email     = empty( $_POST['api_email'] ) ? '' : sanitize_text_field( $_POST['api_email'] );
		$this->template_type = empty( $_POST['template_type'] ) ? 'blocks' : sanitize_text_field( $_POST['template_type'] );
		$removed = $this->delete_starter_templates_folder();
		if ( ! $removed ) {
			wp_send_json_error( 'failed_to_flush' );
		}
		// Do you have the data?
		$get_data = $this->get_template_data( true );

		if ( ! $get_data ) {
			// Send JSON Error response to the AJAX call.
			wp_send_json( esc_html__( 'No template data', 'kadence-starter-templates' ) );
		} else {
			wp_send_json( $get_data );
		}
		die;
	}


/** Function initial_install_ajax_callback() called by wp_ajax hooks: {'kadence_import_initial'} **/
/** Parameters found in function initial_install_ajax_callback(): {"post": ["selected", "builder"]} **/
function initial_install_ajax_callback() {
		Helpers::verify_ajax_call();

		if ( ! isset( $_POST['selected'] ) || ! isset( $_POST['builder'] ) ) {
			wp_send_json_error( 'Missing Information' );
		}
		// Get selected file index or set it to 0.
		$selected_index   = empty( $_POST['selected'] ) ? '' : sanitize_text_field( $_POST['selected'] );
		$selected_builder = empty( $_POST['builder'] ) ? '' : sanitize_text_field( $_POST['builder'] );
		if ( empty( $selected_index ) || empty( $selected_builder ) ) {
			wp_send_json_error( 'Missing Parameters' );
		}
		delete_transient( 'kadence_importer_data' );
		// error_log( 'Setup Initial Install' );
		if ( empty( $this->import_files ) || ( is_array( $this->import_files ) && ! isset( $this->import_files[ $selected_index ] ) ) ) {
			$template_database  = Template_Database_Importer::get_instance();
			$this->import_files = $template_database->get_importer_files( $selected_index, $selected_builder );
		}
		if ( ! isset( $this->import_files[ $selected_index ] ) ) {
			wp_send_json_error( 'Missing Template' );
		}
		wp_send_json( array( 'status' => 'initialSuccess' ) );
	}


/** Function after_all_import_data_ajax_callback() called by wp_ajax hooks: {'kadence_after_import_data'} **/
/** No params detected :-/ **/


/** Function check_plugin_data_ajax_callback() called by wp_ajax hooks: {'kadence_check_plugin_data'} **/
/** Parameters found in function check_plugin_data_ajax_callback(): {"post": ["selected", "builder"]} **/
function check_plugin_data_ajax_callback() {
		Helpers::verify_ajax_call();
		if ( ! isset( $_POST['selected'] ) || ! isset( $_POST['builder'] ) ) {
			wp_send_json_error( 'Missing Parameters' );
		}
		$selected_index   = empty( $_POST['selected'] ) ? '' : sanitize_text_field( $_POST['selected'] );
		$selected_builder = empty( $_POST['builder'] ) ? '' : sanitize_text_field( $_POST['builder'] );
		if ( empty( $selected_index ) || empty( $selected_builder ) ) {
			wp_send_json_error( 'Missing Parameters' );
		}
		if ( empty( $this->import_files ) || ( is_array( $this->import_files ) && ! isset( $this->import_files[ $selected_index ] ) ) ) {
			$template_database  = Template_Database_Importer::get_instance();
			$this->import_files = $template_database->get_importer_files( $selected_index, $selected_builder );
		}
		if ( ! isset( $this->import_files[ $selected_index ] ) ) {
			wp_send_json_error( 'Missing Template' );
		}
		$info = $this->import_files[ $selected_index ];

		if ( isset( $info['plugins'] ) && ! empty( $info['plugins'] ) ) {

			if ( ! function_exists( 'plugins_api' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
			}
			$importer_plugins = array (
				'woocommerce' => array(
					'title' => 'Woocommerce',
					'base'  => 'woocommerce',
					'slug'  => 'woocommerce',
					'path'  => 'woocommerce/woocommerce.php',
					'src'   => 'repo',
				),
				'elementor' => array(
					'title' => 'Elementor',
					'base'  => 'elementor',
					'slug'  => 'elementor',
					'path'  => 'elementor/elementor.php',
					'src'   => 'repo',
				),
				'kadence-blocks' => array(
					'title' => 'Kadence Blocks',
					'base'  => 'kadence-blocks',
					'slug'  => 'kadence-blocks',
					'path'  => 'kadence-blocks/kadence-blocks.php',
					'src'   => 'repo',
				),
				'kadence-blocks-pro' => array(
					'title' => 'Kadence Blocks Pro',
					'base'  => 'kadence-blocks-pro',
					'slug'  => 'kadence-blocks-pro',
					'path'  => 'kadence-blocks-pro/kadence-blocks-pro.php',
					'src'   => 'bundle',
				),
				'kadence-pro' => array(
					'title' => 'Kadence Pro',
					'base'  => 'kadence-pro',
					'slug'  => 'kadence-pro',
					'path'  => 'kadence-pro/kadence-pro.php',
					'src'   => 'bundle',
				),
				'kadence-creative-kit' => array(
					'title' => 'Kadence Creative Kit',
					'base'  => 'kadence-creative-kit',
					'slug'  => 'kadence-creative-kit',
					'path'  => 'kadence-creative-kit/kadence-creative-kit.php',
					'src'   => 'bundle',
				),
				'fluentform' => array(
					'title' => 'Fluent Forms',
					'src'   => 'repo',
					'base'  => 'fluentform',
					'slug'  => 'fluentform',
					'path'  => 'fluentform/fluentform.php',
				),
				'wpzoom-recipe-card' => array(
					'title' => 'Recipe Card Blocks by WPZOOM',
					'state' => Plugin_Check::active_check( 'recipe-card-blocks-by-wpzoom/wpzoom-recipe-card.php' ),
					'src'   => 'repo',
				),
				'learndash' => array(
					'title' => 'LearnDash',
					'description' => __( 'LearnDash is a learning management system (LMS) plugin for WordPress.', 'kadence-starter-templates' ),
					'state' => Plugin_Check::active_check( 'sfwd-lms/sfwd_lms.php' ),
					'src'   => 'thirdparty',
				),
				'lifterlms' => array(
					'title' => 'LifterLMS',
					'state' => Plugin_Check::active_check( 'lifterlms/lifterlms.php' ),
					'src'   => 'repo',
				),
				'tutor' => array(
					'title' => 'Tutor LMS',
					'state' => Plugin_Check::active_check( 'tutor/tutor.php' ),
					'src'   => 'repo',
				),
				'give' => array(
					'title' => 'GiveWP',
					'description' => __( 'GiveWP is the perfect online fundraising platform to increase your online donations.', 'kadence-starter-templates' ),
					'state' => Plugin_Check::active_check( 'give/give.php' ),
					'src'   => 'repo',
				),
				'the-events-calendar' => array(
					'title' => 'The Events Calendar',
					'description' => __( 'The Events Calendar is a carefully crafted, extensible plugin that lets you easily manage and share events.', 'kadence-starter-templates' ),
					'state' => Plugin_Check::active_check( 'the-events-calendar/the-events-calendar.php' ),
					'src'   => 'repo',
				),
				'event-tickets' => array(
					'title' => 'Event Tickets',
					'description' => __( 'Event Tickets provides a simple way for visitors to RSVP or purchase tickets to your events.', 'kadence-starter-templates' ),
					'state' => Plugin_Check::active_check( 'event-tickets/event-tickets.php' ),
					'src'   => 'repo',
				),
				'orderable' => array(
					'title' => 'Orderable',
					'description' => __( 'Take restaurant orders online with Orderable. The WooCommerce plugin designed to help you manage your restaurant, your way – with no added fees!', 'kadence-starter-templates' ),
					'state' => Plugin_Check::active_check( 'orderable/orderable.php' ),
					'src'   => 'repo',
				),
				'restrict-content' => array(
					'title' => 'Restrict Content',
					'state' => Plugin_Check::active_check( 'restrict-content/restrictcontent.php' ),
					'src'   => 'repo',
				),
				'bookit' => array(
					'title' => 'Bookit',
					'description' => __( 'Bookit is a booking system for WordPress.', 'kadence-starter-templates' ),
					'state' => Plugin_Check::active_check( 'bookit/bookit.php' ),
					'src'   => 'repo',
				),
				'kadence-woo-extras' => array(
					'title' => 'Kadence Shop Kit',
					'description' => __( 'Kadence Shop Kit adds additional features to WooCommerce.', 'kadence-starter-templates' ),
					'state' => Plugin_Check::active_check( 'kadence-woo-extras/kadence-woo-extras.php' ),
					'src'   => 'bundle',
				),
				'kadence-woocommerce-email-designer' => array(
					'title' => 'Kadence WooCommerce Email Designer',
					'description' => __( 'Kadence WooCommerce Email Designer lets you customize the default WooCommerce emails.', 'kadence-starter-templates' ),
					'state' => Plugin_Check::active_check( 'kadence-woocommerce-email-designer/kadence-woocommerce-email-designer.php' ),
					'src'   => 'repo',
				),
				'depicter' => array(
					'title' => 'Depicter Slider',
					'state' => Plugin_Check::active_check( 'depicter/depicter.php' ),
					'src'   => 'repo',
				),
				'seriously-simple-podcasting' => array(
					'title' => 'Seriously Simple Podcasting',
					'state' => Plugin_Check::active_check( 'seriously-simple-podcasting/seriously-simple-podcasting.php' ),
					'src'   => 'repo',
				),
				'better-wp-security' => array(
					'title' => 'Solid Security',
					'description' => __( 'Security, Two Factor Authentication, and Brute Force Protection', 'kadence-starter-templates' ),
					'state' => Plugin_Check::active_check( 'better-wp-security/better-wp-security.php' ),
					'src'   => 'repo',
				),
				'solid-performance' => array(
					'title' => 'Solid Performance',
					'description' => __( 'Solid Performance is a plugin that adds page caching, lazy loading, and more.', 'kadence-starter-templates' ),
					'state' => Plugin_Check::active_check( 'solid-performance/solid-performance.php' ),
					'src'   => 'repo',
				),
			);
			$plugin_information = array();
			foreach( $info['plugins'] as $plugin ) {
				$path = false;
				if ( strpos( $plugin, '/' ) !== false ) {
					$path = $plugin;
					$arr  = explode( '/', $plugin, 2 );
					$base = $arr[0];
					if ( isset( $importer_plugins[ $base ] ) ) {
						$src = $importer_plugins[ $base ]['src'];
					} else {
						$src = 'unknown';
					}
					if ( isset( $importer_plugins[ $base ] ) ) {
						$title = $importer_plugins[ $base ]['title'];
					} else {
						$title = $base;
					}
				} elseif ( isset( $importer_plugins[ $plugin ] ) ) {
					$path   = $importer_plugins[ $plugin ]['path'];
					$base   = $importer_plugins[ $plugin ]['base'];
					$src    = $importer_plugins[ $plugin ]['src'];
					$title  = $importer_plugins[ $plugin ]['title'];
				}
				if ( $path ) {
					$state = Plugin_Check::active_check( $path );
					if ( 'unknown' === $src ) {
						$check_api = plugins_api(
							'plugin_information',
							array(
								'slug' => $base,
								'fields' => array(
									'short_description' => false,
									'sections' => false,
									'requires' => false,
									'rating' => false,
									'ratings' => false,
									'downloaded' => false,
									'last_updated' => false,
									'added' => false,
									'tags' => false,
									'compatibility' => false,
									'homepage' => false,
									'donate_link' => false,
								),
							)
						);
						if ( ! is_wp_error( $check_api ) ) {
							$title = $check_api->name;
							$src   = 'repo';
						}
					}
					$plugin_information[ $plugin ] = array(
						'state' => $state,
						'src'   => $src,
						'title' => $title,
					);
				} else {
					$plugin_information[ $plugin ] = array(
						'state' => 'unknown',
						'src'   => 'unknown',
						'title' => $plugin,
					);
				}
			}
			wp_send_json( $plugin_information );
		} else {
			wp_send_json_error( 'Missing Plugins' );
		}
	}


/** Function install_plugins_ajax_callback() called by wp_ajax hooks: {'kadence_import_install_plugins'} **/
/** Parameters found in function install_plugins_ajax_callback(): {"post": ["selected", "builder"]} **/
function install_plugins_ajax_callback() {
		// error_log( 'Install Plugins' );
		Helpers::verify_ajax_call();

		if ( ! isset( $_POST['selected'] ) || ! isset( $_POST['builder'] ) ) {
			wp_send_json_error( 'Missing Information' );
		}
		// Get selected file index or set it to 0.
		$selected_index   = empty( $_POST['selected'] ) ? '' : sanitize_text_field( $_POST['selected'] );
		$selected_builder = empty( $_POST['builder'] ) ? '' : sanitize_text_field( $_POST['builder'] );
		if ( empty( $selected_index ) || empty( $selected_builder ) ) {
			wp_send_json_error( 'Missing Parameters' );
		}
		delete_transient( 'kadence_importer_data' );
		if ( empty( $this->import_files ) || ( is_array( $this->import_files ) && ! isset( $this->import_files[ $selected_index ] ) ) ) {
			$template_database  = Template_Database_Importer::get_instance();
			$this->import_files = $template_database->get_importer_files( $selected_index, $selected_builder );
		}
		if ( ! isset( $this->import_files[ $selected_index ] ) ) {
			wp_send_json_error( 'Missing Template' );
		}
		$info = $this->import_files[ $selected_index ];
		$install = true;
		if ( isset( $info['plugins'] ) && ! empty( $info['plugins'] ) ) {

			if ( ! function_exists( 'plugins_api' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
			}
			if ( ! class_exists( 'WP_Upgrader' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
			}
			$importer_plugins = array (
				'woocommerce' => array(
					'title' => 'Woocommerce',
					'base'  => 'woocommerce',
					'slug'  => 'woocommerce',
					'path'  => 'woocommerce/woocommerce.php',
					'src'   => 'repo',
				),
				'elementor' => array(
					'title' => 'Elementor',
					'base'  => 'elementor',
					'slug'  => 'elementor',
					'path'  => 'elementor/elementor.php',
					'src'   => 'repo',
				),
				'kadence-blocks' => array(
					'title' => 'Kadence Blocks',
					'base'  => 'kadence-blocks',
					'slug'  => 'kadence-blocks',
					'path'  => 'kadence-blocks/kadence-blocks.php',
					'src'   => 'repo',
				),
				'kadence-blocks-pro' => array(
					'title' => 'Kadence Blocks Pro',
					'base'  => 'kadence-blocks-pro',
					'slug'  => 'kadence-blocks-pro',
					'path'  => 'kadence-blocks-pro/kadence-blocks-pro.php',
					'src'   => 'bundle',
				),
				'kadence-pro' => array(
					'title' => 'Kadence Pro',
					'base'  => 'kadence-pro',
					'slug'  => 'kadence-pro',
					'path'  => 'kadence-pro/kadence-pro.php',
					'src'   => 'bundle',
				),
				'kadence-creative-kit' => array(
					'title' => 'Kadence Creative Kit',
					'base'  => 'kadence-creative-kit',
					'slug'  => 'kadence-creative-kit',
					'path'  => 'kadence-creative-kit/kadence-creative-kit.php',
					'src'   => 'bundle',
				),
				'fluentform' => array(
					'title' => 'Fluent Forms',
					'src'   => 'repo',
					'base'  => 'fluentform',
					'slug'  => 'fluentform',
					'path'  => 'fluentform/fluentform.php',
				),
				'wpzoom-recipe-card' => array(
					'title' => 'Recipe Card Blocks by WPZOOM',
					'base'  => 'recipe-card-blocks-by-wpzoom',
					'slug'  => 'wpzoom-recipe-card',
					'path'  => 'recipe-card-blocks-by-wpzoom/wpzoom-recipe-card.php',
					'src'   => 'repo',
				),
				'recipe-card-blocks-by-wpzoom' => array(
					'title' => 'Recipe Card Blocks by WPZOOM',
					'base'  => 'recipe-card-blocks-by-wpzoom',
					'slug'  => 'wpzoom-recipe-card',
					'path'  => 'recipe-card-blocks-by-wpzoom/wpzoom-recipe-card.php',
					'src'   => 'repo',
				),
				'learndash' => array(
					'title' => 'LearnDash',
					'base'  => 'sfwd-lms',
					'slug'  => 'sfwd_lms',
					'path'  => 'sfwd-lms/sfwd_lms.php',
					'src'   => 'thirdparty',
				),
				'sfwd-lms' => array(
					'title' => 'LearnDash',
					'base'  => 'sfwd-lms',
					'slug'  => 'sfwd_lms',
					'path'  => 'sfwd-lms/sfwd_lms.php',
					'src'   => 'thirdparty',
				),
				'lifterlms' => array(
					'title' => 'LifterLMS',
					'base'  => 'lifterlms',
					'slug'  => 'lifterlms',
					'path'  => 'lifterlms/lifterlms.php',
					'src'   => 'repo',
				),
				'tutor' => array(
					'title' => 'Tutor LMS',
					'base'  => 'tutor',
					'slug'  => 'tutor',
					'path'  => 'tutor/tutor.php',
					'src'   => 'repo',
				),
				'give' => array(
					'title' => 'GiveWP',
					'base'  => 'give',
					'slug'  => 'give',
					'path'  => 'give/give.php',
					'src'   => 'repo',
				),
				'the-events-calendar' => array(
					'title' => 'The Events Calendar',
					'base'  => 'the-events-calendar',
					'slug'  => 'the-events-calendar',
					'path'  => 'the-events-calendar/the-events-calendar.php',
					'src'   => 'repo',
				),
				'event-tickets' => array(
					'title' => 'Event Tickets',
					'base'  => 'event-tickets',
					'slug'  => 'event-tickets',
					'path'  => 'event-tickets/event-tickets.php',
					'src'   => 'repo',
				),
				'orderable' => array(
					'title' => 'Orderable',
					'base'  => 'orderable',
					'slug'  => 'orderable',
					'path'  => 'orderable/orderable.php',
					'src'   => 'repo',
				),
				'restrict-content' => array(
					'title' => 'Restrict Content',
					'base'  => 'restrict-content',
					'slug'  => 'restrictcontent',
					'path'  => 'restrict-content/restrictcontent.php',
					'src'   => 'repo',
				),
				'kadence-woo-extras' => array(
					'title' => 'Kadence Shop Kit',
					'base'  => 'kadence-woo-extras',
					'slug'  => 'kadence-woo-extras',
					'path'  => 'kadence-woo-extras/kadence-woo-extras.php',
					'src'   => 'bundle',
				),
				'depicter' => array(
					'title' => 'Depicter Slider',
					'base'  => 'depicter',
					'slug'  => 'depicter',
					'path'  => 'depicter/depicter.php',
					'src'   => 'repo',
				),
				'bookit' => array(
					'title' => 'Bookit',
					'base'  => 'bookit',
					'slug'  => 'bookit',
					'path'  => 'bookit/bookit.php',
					'src'   => 'repo',
				),
				'seriously-simple-podcasting' => array(
					'title' => 'Seriously Simple Podcasting',
					'base'  => 'seriously-simple-podcasting',
					'slug'  => 'seriously-simple-podcasting',
					'path'  => 'seriously-simple-podcasting/seriously-simple-podcasting.php',
					'src'   => 'repo',
				),
				'solid-performance' => array(
					'title' => 'Solid Performance',
					'base'  => 'solid-performance',
					'slug'  => 'solid-performance',
					'path'  => 'solid-performance/solid-performance.php',
					'src'   => 'repo',
				),
			);
			foreach( $info['plugins'] as $plugin ) {
				$path = false;
				if ( strpos( $plugin, '/' ) !== false ) {
					$path = $plugin;
					$arr  = explode( '/', $plugin, 2 );
					$base = $arr[0];
					if ( isset( $importer_plugins[ $base ] ) ) {
						$src = $importer_plugins[ $base ]['src'];
					} else {
						$src = 'unknown';
					}
				} elseif ( isset( $importer_plugins[ $plugin ] ) ) {
					$path = $importer_plugins[ $plugin ]['path'];
					$base = $importer_plugins[ $plugin ]['base'];
					$src  = $importer_plugins[ $plugin ]['src'];
				}
				if ( $path ) {
					$state = Plugin_Check::active_check( $path );
					if ( 'woocommerce' === $base && empty( get_option( 'woocommerce_db_version' ) ) ) {
						update_option( 'woocommerce_db_version', '4.0' );
					}
					if ( 'woocommerce' === $base && ( 'notactive' === $state || 'installed' === $state ) ) {
						$this->ss = true;
					}
					if ( 'unknown' === $src ) {
						$check_api = plugins_api(
							'plugin_information',
							array(
								'slug' => $base,
								'fields' => array(
									'short_description' => false,
									'sections' => false,
									'requires' => false,
									'rating' => false,
									'ratings' => false,
									'downloaded' => false,
									'last_updated' => false,
									'added' => false,
									'tags' => false,
									'compatibility' => false,
									'homepage' => false,
									'donate_link' => false,
								),
							)
						);
						if ( ! is_wp_error( $check_api ) ) {
							$src = 'repo';
						}
					}
					if ( 'notactive' === $state && 'repo' === $src ) {
						if ( ! current_user_can( 'install_plugins' ) ) {
							wp_send_json_error( 'Permissions Issue' );
						}
						$api = plugins_api(
							'plugin_information',
							array(
								'slug' => $base,
								'fields' => array(
									'short_description' => false,
									'sections' => false,
									'requires' => false,
									'rating' => false,
									'ratings' => false,
									'downloaded' => false,
									'last_updated' => false,
									'added' => false,
									'tags' => false,
									'compatibility' => false,
									'homepage' => false,
									'donate_link' => false,
								),
							)
						);
						if ( ! is_wp_error( $api ) ) {

							// Use AJAX upgrader skin instead of plugin installer skin.
							// ref: function wp_ajax_install_plugin().
							$upgrader = new \Plugin_Upgrader( new \WP_Ajax_Upgrader_Skin() );

							$installed = $upgrader->install( $api->download_link );
							if ( $installed ) {
								$silent = ( 'give' === $base || 'elementor' === $base || 'fluentform' === $base || 'restrict-content' === $base ? false : true );
								if ( 'give' === $base ) {
									add_option( 'give_install_pages_created', 1, '', false );
								}
								if ( 'restrict-content' === $base ) {
									update_option( 'rcp_install_pages_created', current_time( 'mysql' ) );
								}
								$activate = activate_plugin( $path, '', false, $silent );
								if ( is_wp_error( $activate ) ) {
									$install = false;
								}
							} else {
								$install = false;
							}
						} else {
							$install = false;
						}
					} elseif ( 'installed' === $state ) {
						if ( ! current_user_can( 'install_plugins' ) ) {
							wp_send_json_error( 'Permissions Issue' );
						}
						$silent = false;
						//$silent = ( 'give' === $base || 'elementor' === $base ? false : true );
						if ( 'give' === $base ) {
							// Make sure give doesn't add it's pages, prevents having two sets.
							update_option( 'give_install_pages_created', 1, '', false );
						}
						if ( 'restrict-content' === $base ) {
							$silent = true;
							update_option( 'rcp_install_pages_created', current_time( 'mysql' ) );
						}
						$activate = activate_plugin( $path, '', false, $silent );
						if ( is_wp_error( $activate ) ) {
							$install = false;
						}
					}
					if ( 'give' === $base ) {
						update_option( 'give_version_upgraded_from', '2.13.2' );
						//add_option( 'give_install_pages_created', 1, '', false );
					}
					if ( 'kadence-pro' === $base ) {
						$enabled = json_decode( get_option( 'kadence_pro_theme_config' ), true );
						$enabled['elements'] = true;
						$enabled['header_addons'] = true;
						$enabled['mega_menu'] = true;
						$enabled = json_encode( $enabled );
						update_option( 'kadence_pro_theme_config', $enabled );
					}
				}
			}
		}
		if ( false === $install ) {
			wp_send_json_error();
		} else {
			wp_send_json( array( 'status' => 'pluginSuccess' ) );
		}
	}


/** Function subscribe_ajax_callback() called by wp_ajax hooks: {'kadence_import_subscribe'} **/
/** Parameters found in function subscribe_ajax_callback(): {"post": ["email", "selected"]} **/
function subscribe_ajax_callback() {
		Helpers::verify_ajax_call();
		$email = empty( $_POST['email'] ) ? '' : sanitize_text_field( $_POST['email'] );
		$selected_index = empty( $_POST['selected'] ) ? '' : sanitize_text_field( $_POST['selected'] );
		// Do you have the data?
		if ( $email && is_email( $email ) && filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			list( $user, $domain ) = explode( '@', $email );
			list( $pre_domain, $post_domain ) = explode( '.', $domain );
			$spell_issue_domains = array( 'gmaiil', 'gmai', 'gmaill' );
			$spell_issue_domain_ends = array( 'local', 'comm', 'orgg', 'cmm' );
			if ( in_array( $pre_domain, $spell_issue_domain_ends, true ) ) {
				return wp_send_json( 'emailDomainPreError' );
			}
			if ( in_array( $post_domain, $spell_issue_domain_ends, true ) ) {
				return wp_send_json( 'emailDomainPostError' );
			}
			$args = array(
				'email'   => $email,
				'tag'     => 'starter',
				'list'    => '20',
				'starter' => $selected_index,
			);
			// Get the response.
			$api_url  = add_query_arg( $args, 'https://www.kadencewp.com/kadence-blocks/wp-json/kadence-subscribe/v1/subscribe/' );
			$response = wp_remote_get( $api_url );
			// Early exit if there was an error.
			if ( is_wp_error( $response ) ) {
				return wp_send_json( array( 'status' => 'subscribeSuccess' ) );
			}
			// Get the CSS from our response.
			$contents = wp_remote_retrieve_body( $response );
			// Early exit if there was an error.
			if ( is_wp_error( $contents ) ) {
				return wp_send_json( array( 'status' => 'subscribeSuccess' ) );
			}
			if ( ! $contents ) {
				// Send JSON Error response to the AJAX call.
				wp_send_json( array( 'status' => 'subscribeSuccess' ) );
			} else {
				update_option( 'kadence_starter_templates_subscribe', true );
				wp_send_json( array( 'status' => 'subscribeSuccess' ) );
			}
		}
		// Send JSON Error response to the AJAX call.
		wp_send_json( 'emailDomainPreError' );
		die;
	}


/** Function import_customizer_data_ajax_callback() called by wp_ajax hooks: {'kadence_import_customizer_data'} **/
/** Parameters found in function import_customizer_data_ajax_callback(): {"post": ["selected", "palette", "font", "builder"]} **/
function import_customizer_data_ajax_callback() {
		// Verify if the AJAX call is valid (checks nonce and current_user_can).
		Helpers::verify_ajax_call();
		$use_existing_importer_data = $this->use_existing_importer_data();

		if ( ! $use_existing_importer_data ) {
			// Create a date and time string to use for demo and log file names.
			Helpers::set_demo_import_start_time();

			if ( apply_filters( 'kadence_starter_templates_save_log_files', false ) ) {
				// Define log file path.
				$this->log_file_path = Helpers::get_log_path();
			} else {
				$this->log_file_path = '';
			}

			// Get selected file index or set it to 0.
			$this->selected_index   = empty( $_POST['selected'] ) ? '' : sanitize_text_field( $_POST['selected'] );
			$this->selected_palette = empty( $_POST['palette'] ) ? '' : sanitize_text_field( $_POST['palette'] );
			$this->selected_font    = empty( $_POST['font'] ) ? '' : sanitize_text_field( $_POST['font'] );
			$this->selected_builder = empty( $_POST['builder'] ) ? 'blocks' : sanitize_text_field( $_POST['builder'] );

			if ( empty( $this->import_files ) || ( is_array( $this->import_files ) && ! isset( $this->import_files[ $this->selected_index ] ) ) ) {
				$template_database  = Template_Database_Importer::get_instance();
				$this->import_files = $template_database->get_importer_files( $this->selected_index, $this->selected_builder );
			}
			if ( ! isset( $this->import_files[ $this->selected_index ] ) ) {
				// Send JSON Error response to the AJAX call.
				wp_send_json( esc_html__( 'No import files specified!', 'kadence-starter-templates' ) );
			}
			/**
			 * 1). Prepare import files.
			 * Predefined import files via filter: kadence-starter-templates/import_files
			 */
			if ( ! empty( $this->import_files[ $this->selected_index ] ) ) { // Use predefined import files from wp filter: kadence-starter-templates/import_files.

				// Download the import files (content, widgets and customizer files).
				$this->selected_import_files = Helpers::download_import_files( $this->import_files[ $this->selected_index ] );
				// Check Errors.
				if ( is_wp_error( $this->selected_import_files ) ) {
					// Write error to log file and send an AJAX response with the error.
					Helpers::log_error_and_send_ajax_response(
						$this->selected_import_files->get_error_message(),
						$this->log_file_path,
						esc_html__( 'Downloaded files', 'kadence-starter-templates' )
					);
				}
				if ( apply_filters( 'kadence_starter_templates_save_log_files', false ) ) {
					// Add this message to log file.
					$log_added = Helpers::append_to_file(
						sprintf(
							__( 'The import files for: %s were successfully downloaded!', 'kadence-starter-templates' ),
							$this->import_files[ $this->selected_index ]['slug']
						) . Helpers::import_file_info( $this->selected_import_files ),
						$this->log_file_path,
						esc_html__( 'Downloaded files' , 'kadence-starter-templates' )
					);
				}
			} else {
				// Send JSON Error response to the AJAX call.
				wp_send_json( esc_html__( 'No import files specified!', 'kadence-starter-templates' ) );
			}
			// If elementor make sure the defaults are off.
			if ( isset( $this->import_files[ $this->selected_index ]['type'] ) && 'elementor' === $this->import_files[ $this->selected_index ]['type'] ) {
				update_option( 'elementor_disable_color_schemes', 'yes' );
				update_option( 'elementor_disable_typography_schemes', 'yes' );
			}
			// Save the initial import data as a transient, so other import parts (in new AJAX calls) can use that data.
			Helpers::set_import_data_transient( $this->get_current_importer_data() );
			if ( ! $this->before_import_executed ) {
				$this->before_import_executed = true;

				/**
				 * Save Current Theme mods for a potential undo.
				 */
				update_option( '_kadence_starter_templates_old_customizer', get_option( 'theme_mods_' . get_option( 'stylesheet' ) ) );
				// Save Import data for use if we need to reset it.
				update_option( '_kadence_starter_templates_last_import_data', $this->import_files[ $this->selected_index ], 'no' );
				// Reset to default settings values.
				delete_option( 'theme_mods_' . get_option( 'stylesheet' ) );
				// Reset Global Palette
				if ( get_option( 'kadence_global_palette' ) !== false ) {
					// The option already exists, so update it.
					update_option( 'kadence_global_palette', '{"palette":[{"color":"#3182CE","slug":"palette1","name":"Palette Color 1"},{"color":"#2B6CB0","slug":"palette2","name":"Palette Color 2"},{"color":"#1A202C","slug":"palette3","name":"Palette Color 3"},{"color":"#2D3748","slug":"palette4","name":"Palette Color 4"},{"color":"#4A5568","slug":"palette5","name":"Palette Color 5"},{"color":"#718096","slug":"palette6","name":"Palette Color 6"},{"color":"#EDF2F7","slug":"palette7","name":"Palette Color 7"},{"color":"#F7FAFC","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"second-palette":[{"color":"#3182CE","slug":"palette1","name":"Palette Color 1"},{"color":"#2B6CB0","slug":"palette2","name":"Palette Color 2"},{"color":"#1A202C","slug":"palette3","name":"Palette Color 3"},{"color":"#2D3748","slug":"palette4","name":"Palette Color 4"},{"color":"#4A5568","slug":"palette5","name":"Palette Color 5"},{"color":"#718096","slug":"palette6","name":"Palette Color 6"},{"color":"#EDF2F7","slug":"palette7","name":"Palette Color 7"},{"color":"#F7FAFC","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"third-palette":[{"color":"#3182CE","slug":"palette1","name":"Palette Color 1"},{"color":"#2B6CB0","slug":"palette2","name":"Palette Color 2"},{"color":"#1A202C","slug":"palette3","name":"Palette Color 3"},{"color":"#2D3748","slug":"palette4","name":"Palette Color 4"},{"color":"#4A5568","slug":"palette5","name":"Palette Color 5"},{"color":"#718096","slug":"palette6","name":"Palette Color 6"},{"color":"#EDF2F7","slug":"palette7","name":"Palette Color 7"},{"color":"#F7FAFC","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"active":"palette"}' );
				}
			}
		}


		/**
		 * Execute the customizer import actions.
		 *
		 * Default actions:
		 * 1 - Customizer import (with priority 10).
		 */
		do_action( 'kadence-starter-templates/customizer_import_execution', $this->selected_import_files );

		// Request the after all import AJAX call.
		if ( false !== has_action( 'kadence-starter-templates/after_all_import_execution' ) ) {
			wp_send_json( array( 'status' => 'afterAllImportAJAX' ) );
		}

		// Send a JSON response with final report.
		$this->final_response();
	}


/** Function import_demo_data_ajax_callback() called by wp_ajax hooks: {'kadence_import_demo_data'} **/
/** Parameters found in function import_demo_data_ajax_callback(): {"post": ["selected", "palette", "font", "builder"]} **/
function import_demo_data_ajax_callback() {
		// error_log( 'Install Content' );
		// Verify if the AJAX call is valid (checks nonce and current_user_can).
		Helpers::verify_ajax_call();

		// Try to update PHP memory limit (so that it does not run out of it).
		ini_set( 'memory_limit', apply_filters( 'kadence-starter-templates/import_memory_limit', '350M' ) );

		// Increase PHP max execution time. Just in case, even though the AJAX calls are only 25 sec long.
		if ( strpos( ini_get( 'disable_functions' ), 'set_time_limit' ) === false ) {
			set_time_limit( apply_filters( 'kadence-starter-templates/set_time_limit_for_demo_data_import', 300 ) );
		}

		// Is this a new AJAX call to continue the previous import?
		$use_existing_importer_data = $this->use_existing_importer_data();
		if ( ! $use_existing_importer_data ) {
			// Create a date and time string to use for demo and log file names.
			Helpers::set_demo_import_start_time();

			if ( apply_filters( 'kadence_starter_templates_save_log_files', false ) ) {
				// Define log file path.
				$this->log_file_path = Helpers::get_log_path();
			} else {
				$this->log_file_path = '';
			}

			// Get selected file index or set it to 0.
			$this->selected_index   = empty( $_POST['selected'] ) ? '' : sanitize_text_field( $_POST['selected'] );
			$this->selected_palette = empty( $_POST['palette'] ) ? '' : sanitize_text_field( $_POST['palette'] );
			$this->selected_font    = empty( $_POST['font'] ) ? '' : sanitize_text_field( $_POST['font'] );
			$this->selected_builder = empty( $_POST['builder'] ) ? 'blocks' : sanitize_text_field( $_POST['builder'] );

			if ( empty( $this->import_files ) || ( is_array( $this->import_files ) && ! isset( $this->import_files[ $this->selected_index ] ) ) ) {
				$template_database  = Template_Database_Importer::get_instance();
				$this->import_files = $template_database->get_importer_files( $this->selected_index, $this->selected_builder );
			}
			if ( ! isset( $this->import_files[ $this->selected_index ] ) ) {
				// Send JSON Error response to the AJAX call.
				wp_send_json( esc_html__( 'No import files specified!', 'kadence-starter-templates' ) );
			}
			/**
			 * 1). Prepare import files.
			 * Predefined import files via filter: kadence-starter-templates/import_files
			 */
			if ( ! empty( $this->import_files[ $this->selected_index ] ) ) { // Use predefined import files from wp filter: kadence-starter-templates/import_files.

				// Download the import files (content, widgets and customizer files).
				$this->selected_import_files = Helpers::download_import_files( $this->import_files[ $this->selected_index ] );
				// Check Errors.
				if ( is_wp_error( $this->selected_import_files ) ) {
					// Write error to log file and send an AJAX response with the error.
					Helpers::log_error_and_send_ajax_response(
						$this->selected_import_files->get_error_message(),
						$this->log_file_path,
						esc_html__( 'Downloaded files', 'kadence-starter-templates' )
					);
				}
				if ( apply_filters( 'kadence_starter_templates_save_log_files', false ) ) {
					// Add this message to log file.
					$log_added = Helpers::append_to_file(
						sprintf(
							__( 'The import files for: %s were successfully downloaded!', 'kadence-starter-templates' ),
							$this->import_files[ $this->selected_index ]['slug']
						) . Helpers::import_file_info( $this->selected_import_files ),
						$this->log_file_path,
						esc_html__( 'Downloaded files' , 'kadence-starter-templates' )
					);
				}
			} else {
				// Send JSON Error response to the AJAX call.
				wp_send_json( esc_html__( 'No import files specified!', 'kadence-starter-templates' ) );
			}
		}
		// if ( class_exists( 'woocommerce' ) && isset( $this->import_files[ $this->selected_index ]['ecommerce'] ) && $this->import_files[ $this->selected_index ]['ecommerce'] && ! $this->import_woo_pages ) {
		// 	add_filter( 'stop_importing_woo_pages', '__return_true' );
		// }
		// If elementor make sure the defaults are off.
		if ( isset( $this->import_files[ $this->selected_index ]['type'] ) && 'elementor' === $this->import_files[ $this->selected_index ]['type'] ) {
			update_option( 'elementor_disable_color_schemes', 'yes' );
			update_option( 'elementor_disable_typography_schemes', 'yes' );
		}
		// Save the initial import data as a transient, so other import parts (in new AJAX calls) can use that data.
		Helpers::set_import_data_transient( $this->get_current_importer_data() );
		if ( ! $this->before_import_executed ) {
			$this->before_import_executed = true;
			/**
			 * Save Current Theme mods for a potential undo.
			 */
			update_option( '_kadence_starter_templates_old_customizer', get_option( 'theme_mods_' . get_option( 'stylesheet' ) ) );
			// Save Import data for use if we need to reset it.
			update_option( '_kadence_starter_templates_last_import_data', $this->import_files[ $this->selected_index ], 'no' );
			/**
			 * 2). Execute the actions hooked to the 'kadence-starter-templates/before_content_import_execution' action:
			 *
			 * Default actions:
			 * 1 - Before content import WP action (with priority 10).
			 */
			/**
			 * Clean up default contents.
			 */
			$hello_world = $this->get_page_by_title( 'Hello World', OBJECT, 'post' );
			if ( $hello_world ) {
				wp_delete_post( $hello_world->ID, true );// Hello World.
			}
			$sample_page = $this->get_page_by_title( 'Sample Page' );
			if ( $sample_page ) {
				wp_delete_post( $sample_page->ID, true ); // Sample Page.
			}
			wp_delete_comment( 1, true ); // WordPress comment.
			/**
			 * Clean up default woocommerce.
			 */
			$woopages = array(
				'woocommerce_shop_page_id'      => 'shop',
				'woocommerce_cart_page_id'      => 'cart',
				'woocommerce_checkout_page_id'  => 'checkout',
				'woocommerce_myaccount_page_id' => 'my-account',
			);
			foreach ( $woopages as $woo_page_option => $woo_page_slug ) {
				if ( get_option( $woo_page_option ) ) {
					wp_delete_post( get_option( $woo_page_option ), true );
				}
			}
			// Move All active widgets into inactive.
			$sidebars = wp_get_sidebars_widgets();
			if ( is_array( $sidebars ) ) {
				foreach ( $sidebars as $sidebar_id => $sidebar_widgets ) {
					if ( 'wp_inactive_widgets' === $sidebar_id ) {
						continue;
					}
					if ( is_array( $sidebar_widgets ) && ! empty( $sidebar_widgets ) ) {
						foreach ( $sidebar_widgets as $i => $single_widget ) {
							$sidebars['wp_inactive_widgets'][] = $single_widget;
							unset( $sidebars[ $sidebar_id ][ $i ] );
						}
					}
				}
			}
			wp_set_sidebars_widgets( $sidebars );
			// Reset to default settings values.
			delete_option( 'theme_mods_' . get_option( 'stylesheet' ) );
			// Reset Global Palette
			update_option( 'kadence_global_palette', '{"palette":[{"color":"#3182CE","slug":"palette1","name":"Palette Color 1"},{"color":"#2B6CB0","slug":"palette2","name":"Palette Color 2"},{"color":"#1A202C","slug":"palette3","name":"Palette Color 3"},{"color":"#2D3748","slug":"palette4","name":"Palette Color 4"},{"color":"#4A5568","slug":"palette5","name":"Palette Color 5"},{"color":"#718096","slug":"palette6","name":"Palette Color 6"},{"color":"#EDF2F7","slug":"palette7","name":"Palette Color 7"},{"color":"#F7FAFC","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"second-palette":[{"color":"#3182CE","slug":"palette1","name":"Palette Color 1"},{"color":"#2B6CB0","slug":"palette2","name":"Palette Color 2"},{"color":"#1A202C","slug":"palette3","name":"Palette Color 3"},{"color":"#2D3748","slug":"palette4","name":"Palette Color 4"},{"color":"#4A5568","slug":"palette5","name":"Palette Color 5"},{"color":"#718096","slug":"palette6","name":"Palette Color 6"},{"color":"#EDF2F7","slug":"palette7","name":"Palette Color 7"},{"color":"#F7FAFC","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"third-palette":[{"color":"#3182CE","slug":"palette1","name":"Palette Color 1"},{"color":"#2B6CB0","slug":"palette2","name":"Palette Color 2"},{"color":"#1A202C","slug":"palette3","name":"Palette Color 3"},{"color":"#2D3748","slug":"palette4","name":"Palette Color 4"},{"color":"#4A5568","slug":"palette5","name":"Palette Color 5"},{"color":"#718096","slug":"palette6","name":"Palette Color 6"},{"color":"#EDF2F7","slug":"palette7","name":"Palette Color 7"},{"color":"#F7FAFC","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"active":"palette"}' );
			do_action( 'kadence-starter-templates/before_content_import_execution', $this->selected_import_files, $this->import_files, $this->selected_index, $this->selected_palette, $this->selected_font );
		}

		/**
		 * 3). Import content (if the content XML file is set for this import).
		 * Returns any errors greater then the "warning" logger level, that will be displayed on front page.
		 */
		if ( ! empty( $this->selected_import_files['content'] ) ) {
			$this->append_to_frontend_error_messages( $this->importer->import_content( $this->selected_import_files['content'] ) );
		}

		/**
		 * 4). Execute the actions hooked to the 'kadence-starter-templates/after_content_import_execution' action:
		 *
		 * Default actions:
		 * 1 - Before widgets import setup (with priority 10).
		 * 2 - Import widgets (with priority 20).
		 * 3 - Import Redux data (with priority 30).
		 */
		do_action( 'kadence-starter-templates/after_content_import_execution', $this->selected_import_files, $this->import_files, $this->selected_index, $this->selected_palette, $this->selected_font );
		// Save the import data as a transient, so other import parts (in new AJAX calls) can use that data.
		Helpers::set_import_data_transient( $this->get_current_importer_data() );
		// Request the customizer import AJAX call.
		if ( ! empty( $this->selected_import_files['customizer'] ) ) {
			wp_send_json( array( 'status' => 'customizerAJAX' ) );
		}

		// Request the after all import AJAX call.
		if ( false !== has_action( 'kadence-starter-templates/after_all_import_execution' ) ) {
			wp_send_json( array( 'status' => 'afterAllImportAJAX' ) );
		}

		// Send a JSON response with final report.
		$this->final_response();
	}


/** Function ajax_dismiss_starter_notice() called by wp_ajax hooks: {'kadence_starter_dismiss_notice'} **/
/** Parameters found in function ajax_dismiss_starter_notice(): {"post": ["action"]} **/
function ajax_dismiss_starter_notice() {

		// Sanity check: Early exit if we're not on a wptrt_dismiss_notice action.
		if ( ! isset( $_POST['action'] ) || 'kadence_starter_dismiss_notice' !== $_POST['action'] ) {
			return;
		}
		// Security check: Make sure nonce is OK.
		check_ajax_referer( 'kadence-starter-ajax-verification', 'security', true );

		// If we got this far, we need to dismiss the notice.
		update_option( 'kadence_starter_templates_dismiss_upsell', true, false );
	}


/** Function ajax_reset() called by wp_ajax hooks: {'kadence_starter_reset'} **/
/** No params detected :-/ **/


