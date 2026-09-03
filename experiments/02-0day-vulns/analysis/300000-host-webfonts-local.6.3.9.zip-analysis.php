<?php
/***
*
*Found actions: 6
*Found functions:6
*Extracted functions:6
*Total parameter names extracted: 3
*Overview: {'download_log': {'omgf_download_log'}, 'hide_notice': {'omgf_hide_notice'}, 'remove_stylesheet_from_db': {'omgf_remove_stylesheet_from_db'}, 'refresh_cache': {'omgf_refresh_cache'}, 'empty_directory': {'omgf_empty_dir'}, 'delete_log': {'omgf_delete_log'}}
*
***/

/** Function download_log() called by wp_ajax hooks: {'omgf_download_log'} **/
/** No params detected :-/ **/


/** Function hide_notice() called by wp_ajax hooks: {'omgf_hide_notice'} **/
/** Parameters found in function hide_notice(): {"post": ["warning_id"]} **/
function hide_notice() {
		check_ajax_referer( Settings::OMGF_ADMIN_PAGE, 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Hmmm, are you lost?', 'host-webfonts-local' ) ); // @codeCoverageIgnore
		}

		$warning_id     = $_POST['warning_id'];
		$hidden_notices = OMGF::get_option( Settings::OMGF_HIDDEN_NOTICES, [] );

		if ( ! in_array( $warning_id, $hidden_notices ) ) {
			$hidden_notices[] = $warning_id;
		}

		OMGF::update_option( Settings::OMGF_HIDDEN_NOTICES, $hidden_notices, 'off' );

		$result = Dashboard::get_dashboard_html();

		wp_send_json_success( $result );
	}


/** Function remove_stylesheet_from_db() called by wp_ajax hooks: {'omgf_remove_stylesheet_from_db'} **/
/** Parameters found in function remove_stylesheet_from_db(): {"post": ["handle"]} **/
function remove_stylesheet_from_db() {
		check_ajax_referer( Settings::OMGF_ADMIN_PAGE, 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( "Hmmm, you're not supposed to be here.", 'host-webfonts-local' ) ); // @codeCoverageIgnore
		}

		$handle                   = $_POST['handle'];
		$optimized_fonts          = OMGF::admin_optimized_fonts();
		$optimized_fonts_frontend = OMGF::optimized_fonts();
		$unloaded_fonts           = OMGF::unloaded_fonts();
		$unloaded_stylesheets     = OMGF::unloaded_stylesheets();
		$preloaded_fonts          = OMGF::preloaded_fonts();
		$cache_keys               = OMGF::cache_keys();

		$this->maybe_unset( Settings::OMGF_OPTIMIZE_SETTING_CACHE_KEYS, $cache_keys, $handle, true );
		$this->maybe_unset( Settings::OMGF_OPTIMIZE_SETTING_OPTIMIZED_FONTS, $optimized_fonts, $handle );
		$this->maybe_unset( Settings::OMGF_OPTIMIZE_SETTING_OPTIMIZED_FONTS_FRONTEND, $optimized_fonts_frontend, $handle );
		$this->maybe_unset( Settings::OMGF_OPTIMIZE_SETTING_UNLOAD_FONTS, $unloaded_fonts, $handle );
		$this->maybe_unset( Settings::OMGF_OPTIMIZE_SETTING_UNLOAD_STYLESHEETS, $unloaded_stylesheets, $handle, true );
		$this->maybe_unset( Settings::OMGF_OPTIMIZE_SETTING_PRELOAD_FONTS, $preloaded_fonts, $handle );
	}


/** Function refresh_cache() called by wp_ajax hooks: {'omgf_refresh_cache'} **/
/** No params detected :-/ **/


/** Function empty_directory() called by wp_ajax hooks: {'omgf_empty_dir'} **/
/** Parameters found in function empty_directory(): {"post": ["init"]} **/
function empty_directory() {
		check_ajax_referer( Settings::OMGF_ADMIN_PAGE, 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( "Hmmm, you're not supposed to be here.", 'host-webfonts-local' ) ); // @codeCoverageIgnore
		}

		try {
			$init = $_POST['init'] ?? '';

			OMGF::flush_cache( $init );

			Notice::set_notice( __( 'Cache directory successfully emptied.', 'host-webfonts-local' ) );
		} catch ( \Exception $e ) {
			Notice::set_notice(
				__( 'OMGF encountered an error while emptying the cache directory: ', 'host-webfonts-local' ) . $e->getMessage(),
				'omgf-cache-error',
				'error',
				$e->getCode()
			);
		}
	}


/** Function delete_log() called by wp_ajax hooks: {'omgf_delete_log'} **/
/** No params detected :-/ **/


