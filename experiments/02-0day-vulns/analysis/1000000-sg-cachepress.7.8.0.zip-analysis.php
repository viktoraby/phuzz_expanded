<?php
/***
*
*Found actions: 7
*Found functions:5
*Extracted functions:5
*Total parameter names extracted: 2
*Overview: {'start_optimization': {'siteground_optimizer_start_webp_conversion', 'nopriv_siteground_optimizer_start_image_optimization', 'siteground_optimizer_start_image_optimization'}, 'purge_cache': {'admin_bar_purge_cache'}, 'hide_memcache_notice': {'dismiss_memcache_notice'}, 'hide_cache_plugins_notice': {'dismiss_cache_plugins_notice'}, 'hide_blocking_plugins_notice': {'dismiss_blocking_plugins_notice'}}
*
***/

/** Function start_optimization() called by wp_ajax hooks: {'siteground_optimizer_start_webp_conversion', 'nopriv_siteground_optimizer_start_image_optimization', 'siteground_optimizer_start_image_optimization'} **/
/** No params detected :-/ **/


/** Function purge_cache() called by wp_ajax hooks: {'admin_bar_purge_cache'} **/
/** Parameters found in function purge_cache(): {"get": ["_wpnonce"], "server": ["HTTP_REFERER"]} **/
function purge_cache() {
		// Bail if the nonce is not set.
		if ( empty( $_GET['_wpnonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'sg-cachepress-purge' ) ) {
			return;
		}

		Supercacher::purge_cache();
		Supercacher::flush_memcache();
		Supercacher::delete_assets();

		// Flush File-Based cache if enabled.
		if ( Options::is_enabled( 'siteground_optimizer_file_caching' ) ) {
			File_Cacher::get_instance()->purge_everything();
		}

		wp_safe_redirect( $_SERVER['HTTP_REFERER'] );
		exit;
	}


/** Function hide_memcache_notice() called by wp_ajax hooks: {'dismiss_memcache_notice'} **/
/** No params detected :-/ **/


/** Function hide_cache_plugins_notice() called by wp_ajax hooks: {'dismiss_cache_plugins_notice'} **/
/** No params detected :-/ **/


/** Function hide_blocking_plugins_notice() called by wp_ajax hooks: {'dismiss_blocking_plugins_notice'} **/
/** No params detected :-/ **/


