<?php
/***
*
*Found actions: 1
*Found functions:1
*Extracted functions:1
*Total parameter names extracted: 1
*Overview: {'disable_plugins_callback': {'disable_plugins'}}
*
***/

/** Function disable_plugins_callback() called by wp_ajax hooks: {'disable_plugins'} **/
/** Parameters found in function disable_plugins_callback(): {"post": ["pluginList"]} **/
function disable_plugins_callback(){
    if (current_user_can('manage_options')) {
        check_ajax_referer('disable_plugin_sitemap_nonce', 'nonce');

        $pluginList = sanitize_text_field($_POST['pluginList']);
        $pluginsToDisable = explode(',', $pluginList);

        foreach ($pluginsToDisable as $plugin) {
            if ($plugin === 'all-in-one-seo-pack/all_in_one_seo_pack.php') {
                /* all in one seo deactivation */
                $aioseo_option_key = 'aioseo_options';
                if ($aioseo_options = get_option($aioseo_option_key)) {
                    $aioseo_options = json_decode($aioseo_options, true);
                    $aioseo_options['sitemap']['general']['enable'] = false;
                    update_option($aioseo_option_key, json_encode($aioseo_options));
                }
            }
            if ($plugin === 'wordpress-seo/wp-seo.php') {
                /* yoast sitemap deactivation */
                if ($yoast_options = get_option('wpseo')) {
                    $yoast_options['enable_xml_sitemap'] = false;
                    update_option('wpseo', $yoast_options);
                }
            }
			if ($plugin === 'jetpack/jetpack.php') {
                /* jetpack sitemap deactivation */
                $modules_array = get_option('jetpack_active_modules');
				if(is_array($modules_array)) {
					if (in_array('sitemaps', $modules_array)) {
						$key = array_search('sitemaps', $modules_array);
						unset($modules_array[$key]);
						update_option('jetpack_active_modules', $modules_array);
					}
				}
            }
			if ($plugin === 'wordpress-sitemap') {
                /* Wordpress sitemap deactivation */
                $options = get_option('sm_options', array());
				if (isset($options['sm_wp_sitemap_status'])) $options['sm_wp_sitemap_status'] = false;
				else $options['sm_wp_sitemap_status'] = false;
				update_option('sm_options', $options);
            }
        }

        echo 'Plugins sitemaps disabled successfully';
        wp_die();
    }
}


