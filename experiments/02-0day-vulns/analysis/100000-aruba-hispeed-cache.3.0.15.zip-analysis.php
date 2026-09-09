<?php
/***
*
*Found actions: 47
*Found functions:25
*Extracted functions:25
*Total parameter names extracted: 41
*Overview: {'ahsc_clear_expired_transient': {'ahsc_clear_expired_transient'}, 'ahsc_ajax_html_optimizer': {'ahsc_html_optimizer', 'nopriv_ahsc_html_optimizer'}, 'ahsc_ajax_enable_purge': {'ahsc_enable_purge', 'nopriv_ahsc_enable_purge'}, 'ahsc_ajax_dns_preconnect': {'ahsc_dns_preconnect', 'nopriv_ahsc_dns_preconnect'}, 'ahsc_check_apc_file': {'nopriv_ahsc_check_apc_file', 'ahsc_check_apc_file'}, 'ahsc_ajax_purge_page_on_new_comment': {'ahsc_purge_page_on_new_comment', 'nopriv_ahsc_purge_page_on_new_comment'}, 'ahsc_ajax_cache_warmer': {'nopriv_ahsc_cache_warmer', 'ahsc_cache_warmer'}, 'ahsc_ajax_lazy_load': {'nopriv_ahsc_lazy_load', 'ahsc_lazy_load'}, 'ahsc_delete_apc_file': {'ahsc_delete_apc_file', 'nopriv_ahsc_delete_apc_file'}, 'ahsc_ajax_xmlrpc_status': {'ahsc_xmlrpc_status', 'nopriv_ahsc_xmlrpc_status'}, 'ahsc_disable_debug': {'ahsc_disable_debug'}, 'ahsc_ajax_dns_preconnect_domain_list': {'ahsc_dns_preconnect_domain_list', 'nopriv_ahsc_dns_preconnect_domain_list'}, 'ahsc_ajax_cron_status': {'nopriv_ahsc_cron_status', 'ahsc_cron_status'}, 'ahsc_ajax_static_cache': {'nopriv_ahsc_static_cache', 'ahsc_static_cache'}, 'ahsc_cache_warmer_ajax_action': {'ahcs_cache_warmer', 'nopriv_ahcs_cache_warmer'}, 'ahsc_ajax_enable_cron': {'nopriv_ahsc_enable_cron', 'ahsc_enable_cron'}, 'ahsc_create_apc_file': {'ahsc_create_apc_file', 'nopriv_ahsc_create_apc_file'}, 'ahsc_ajax_purge_homepage_on_edit': {'ahsc_purge_homepage_on_edit', 'nopriv_ahsc_purge_homepage_on_edit'}, 'ahsc_ajax_dboptimization_active': {'nopriv_ahsc_dboptimization', 'ahsc_dboptimization'}, 'ahsc_ajax_debug_status': {'ahsc_debug_status', 'nopriv_ahsc_debug_status'}, 'ahsc_ajax_purge_archive_on_edit': {'ahsc_purge_archive_on_edit', 'nopriv_ahsc_purge_archive_on_edit'}, 'ahsc_tool_bar_purge': {'ahcs_clear_cache'}, 'ahsc_ajax_cron_time': {'ahsc_cron_time', 'nopriv_ahsc_cron_time'}, 'ahsc_ajax_reset_options': {'nopriv_ahsc_reset_options', 'ahsc_reset_options'}, 'ahsc_update_apc_Settings': {'nopriv_ahsc_update_apc_Settings', 'ahsc_update_apc_Settings'}}
*
***/

/** Function ahsc_clear_expired_transient() called by wp_ajax hooks: {'ahsc_clear_expired_transient'} **/
/** Parameters found in function ahsc_clear_expired_transient(): {"post": ["ahsc_nonce"]} **/
function ahsc_clear_expired_transient(){
	if(is_user_logged_in() && current_user_can( 'manage_options' ) && isset( $_POST['ahsc_nonce'] )) {
		if ( !\wp_verify_nonce( \sanitize_text_field( \wp_unslash( $_POST['ahsc_nonce'] ) ), 'ahsc-purge-cache' ) ){
			wp_die( wp_json_encode( AHSC_TRANSIENT_AJAX['security_error'] ) );
		} else {
			delete_expired_transients( true );
			if(class_exists('\ArubaSPA\HiSpeedCache\Debug\Logger')) {
				// Logger.
				AHSC_log( 'ALL', 'Clear Expired Transient' );
				// Logger.
			}
			wp_die( wp_json_encode( AHSC_TRANSIENT_AJAX['success']) );
		}
	}else{
		wp_die( wp_json_encode( AHSC_TRANSIENT_AJAX['security_error'] ) );
	}
}


/** Function ahsc_ajax_html_optimizer() called by wp_ajax hooks: {'ahsc_html_optimizer', 'nopriv_ahsc_html_optimizer'} **/
/** Parameters found in function ahsc_ajax_html_optimizer(): {"post": ["ahsc_nonce"], "request": ["status"]} **/
function ahsc_ajax_html_optimizer(){
	if(is_user_logged_in() && current_user_can( 'manage_options' ) && isset($_POST['ahsc_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash( $_POST['ahsc_nonce'])), 'ahsc-purge-cache' )) {

		$result                       = array();
		$c_opt                        = get_option( AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME'] );
		$c_opt['ahsc_html_optimizer'] = isset($_REQUEST['status']) && $_REQUEST['status'] === "true";
		$_res                         = update_option( AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME'], $c_opt );
		$result['result']             = $_res;
		echo wp_json_encode( $result );
		die();
	}
}


/** Function ahsc_ajax_enable_purge() called by wp_ajax hooks: {'ahsc_enable_purge', 'nopriv_ahsc_enable_purge'} **/
/** Parameters found in function ahsc_ajax_enable_purge(): {"post": ["ahsc_nonce"], "request": ["status"]} **/
function ahsc_ajax_enable_purge(){
	if(is_user_logged_in() && current_user_can( 'manage_options' ) && isset($_POST['ahsc_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash( $_POST['ahsc_nonce'])), 'ahsc-purge-cache' )  ) {
		$result=array();

		$c_opt=get_option(AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME']);
		$c_opt['ahsc_enable_purge']= isset($_REQUEST['status']) && $_REQUEST['status']==="true";
		if ($c_opt['ahsc_enable_purge'] === false) {
			$c_opt['ahsc_purge_homepage_on_edit'] = false;
			$c_opt['ahsc_purge_page_on_new_comment'] = false;
			$c_opt['ahsc_purge_archive_on_edit'] = false;
		}
		$_res = update_option(AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME'], $c_opt);
		$result['result'] = $_res;
		echo wp_json_encode($result);
		die();
	}
}


/** Function ahsc_ajax_dns_preconnect() called by wp_ajax hooks: {'ahsc_dns_preconnect', 'nopriv_ahsc_dns_preconnect'} **/
/** Parameters found in function ahsc_ajax_dns_preconnect(): {"post": ["ahsc_nonce"], "request": ["status"]} **/
function ahsc_ajax_dns_preconnect(){
	if(is_user_logged_in() && current_user_can( 'manage_options' ) && isset($_POST['ahsc_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash( $_POST['ahsc_nonce'])), 'ahsc-purge-cache' )) {

		$result                       = array();
		$c_opt                        = get_option( AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME'] );
		$c_opt['ahsc_dns_preconnect'] = isset($_REQUEST['status']) && $_REQUEST['status'] === "true";
		$_res                         = update_option( AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME'], $c_opt );
		$result['result']             = $_res;
		echo wp_json_encode( $result );
		die();
	}
}


/** Function ahsc_check_apc_file() called by wp_ajax hooks: {'nopriv_ahsc_check_apc_file', 'ahsc_check_apc_file'} **/
/** No params detected :-/ **/


/** Function ahsc_ajax_purge_page_on_new_comment() called by wp_ajax hooks: {'ahsc_purge_page_on_new_comment', 'nopriv_ahsc_purge_page_on_new_comment'} **/
/** Parameters found in function ahsc_ajax_purge_page_on_new_comment(): {"post": ["ahsc_nonce"], "request": ["status"]} **/
function ahsc_ajax_purge_page_on_new_comment(){
	if(is_user_logged_in() && current_user_can( 'manage_options' ) && isset($_POST['ahsc_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash( $_POST['ahsc_nonce'])), 'ahsc-purge-cache' )) {

		$result = array();

		$c_opt                                   = get_option( AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME'] );
		$c_opt['ahsc_purge_page_on_new_comment'] = isset($_REQUEST['status']) && $_REQUEST['status'] === "true";
		$_res                                    = update_option( AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME'], $c_opt );
		$result['result']                        = $_res;
		echo wp_json_encode( $result );
		die();
	}
}


/** Function ahsc_ajax_cache_warmer() called by wp_ajax hooks: {'nopriv_ahsc_cache_warmer', 'ahsc_cache_warmer'} **/
/** Parameters found in function ahsc_ajax_cache_warmer(): {"post": ["ahsc_nonce"], "request": ["status"]} **/
function ahsc_ajax_cache_warmer(){
	if(is_user_logged_in() && current_user_can( 'manage_options' ) && isset($_POST['ahsc_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash( $_POST['ahsc_nonce'])), 'ahsc-purge-cache' )) {

		$result = array();

		$c_opt                      = get_option( AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME'] );
		$c_opt['ahsc_cache_warmer'] = isset($_REQUEST['status']) && $_REQUEST['status'] === "true";
		$_res                       = update_option( AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME'], $c_opt );
		$result['result']           = $_res;
		echo wp_json_encode( $result );
		die();
	}
}


/** Function ahsc_ajax_lazy_load() called by wp_ajax hooks: {'nopriv_ahsc_lazy_load', 'ahsc_lazy_load'} **/
/** Parameters found in function ahsc_ajax_lazy_load(): {"post": ["ahsc_nonce"], "request": ["status"]} **/
function ahsc_ajax_lazy_load(){
	if(is_user_logged_in() && current_user_can( 'manage_options' ) && isset($_POST['ahsc_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash( $_POST['ahsc_nonce'])), 'ahsc-purge-cache' )) {

		$result=array();
	$c_opt=get_option(AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME']);
	$c_opt['ahsc_lazy_load']=isset($_REQUEST['status']) && $_REQUEST['status']==="true";
	$_res=update_option(AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME'], $c_opt);
	$result['result']= $_res;
	echo  wp_json_encode($result);
	die();
	}
}


/** Function ahsc_delete_apc_file() called by wp_ajax hooks: {'ahsc_delete_apc_file', 'nopriv_ahsc_delete_apc_file'} **/
/** Parameters found in function ahsc_delete_apc_file(): {"post": ["ahsc_nonce"]} **/
function ahsc_delete_apc_file(){
	if(current_user_can( 'manage_options' ) && isset($_POST['ahsc_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash( $_POST['ahsc_nonce'])), 'ahsc-purge-cache' )) {
		$result = array();
		$file   = WP_CONTENT_DIR . '/object-cache.php';
		$c_opt  = AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS'];
		if ( file_exists( $file ) ) {
			\wp_delete_file( $file );
			$result['result'] = true;

		}
		//$c_opt=get_site_option(AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME']);
		$c_opt['ahsc_apc'] = false;
		update_site_option( AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME'], $c_opt );

		echo wp_json_encode( $result );
	}
	die();
}


/** Function ahsc_ajax_xmlrpc_status() called by wp_ajax hooks: {'ahsc_xmlrpc_status', 'nopriv_ahsc_xmlrpc_status'} **/
/** Parameters found in function ahsc_ajax_xmlrpc_status(): {"post": ["ahsc_nonce"], "request": ["status"]} **/
function ahsc_ajax_xmlrpc_status(){
	if(is_user_logged_in() && current_user_can( 'manage_options' ) && isset($_POST['ahsc_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash( $_POST['ahsc_nonce'])), 'ahsc-purge-cache' )) {

		$result                      = array();
		$c_opt                       = get_option( AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME'] );
		$c_opt['ahsc_xmlrpc_status'] = (isset($_REQUEST['status']))?sanitize_text_field(wp_unslash($_REQUEST['status'])):false;
		$_res                        = update_option( AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME'], $c_opt );
		$result['result']            = $_res;
		echo wp_json_encode( $result );
		die();
	}
}


/** Function ahsc_disable_debug() called by wp_ajax hooks: {'ahsc_disable_debug'} **/
/** Parameters found in function ahsc_disable_debug(): {"post": ["ahsc_nonce"]} **/
function ahsc_disable_debug(){
	 if(is_user_logged_in() && current_user_can( 'manage_options' ) && isset($_POST['ahsc_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash( $_POST['ahsc_nonce'])), 'ahsc-disable-debug' )  ) {

		 $ahsc_response   = array();
		 $wpc_transformer = new HASC_WPCT( ABSPATH . 'wp-config.php' );
		 $ahsc_response['disabled_wp_debug']=$wpc_transformer->remove('constant', 'WP_DEBUG');
		 $ahsc_response['disabled_wp_debug_log']=$wpc_transformer->remove('constant', 'WP_DEBUG_LOG');
		 $ahsc_response['disabled_wp_debug_display']=$wpc_transformer->remove('constant', 'WP_DEBUG_DISPLAY');
		 $ahsc_result = glob( WP_CONTENT_DIR . '/*.log' );
		 if ( count( $ahsc_result ) ) {
			 // log files exist
			 foreach ( $ahsc_result as $ahsc_file ) {
				 // wp_delete_file() returns nothing, so the outcome is read back from the
				 // filesystem. As before, only the last iteration is reported.
				 \wp_delete_file( $ahsc_file );
				 $ahsc_response['removed_wp_debug_log_file'] = ! file_exists( $ahsc_file );
			 }
		 }
		 echo wp_json_encode( $ahsc_response );
		 die();
	 }
 }


/** Function ahsc_ajax_dns_preconnect_domain_list() called by wp_ajax hooks: {'ahsc_dns_preconnect_domain_list', 'nopriv_ahsc_dns_preconnect_domain_list'} **/
/** Parameters found in function ahsc_ajax_dns_preconnect_domain_list(): {"post": ["ahsc_nonce"], "request": ["list"], "server": ["SERVER_NAME"]} **/
function ahsc_ajax_dns_preconnect_domain_list(){
	if(is_user_logged_in() && current_user_can( 'manage_options' ) && isset($_POST['ahsc_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash( $_POST['ahsc_nonce'])), 'ahsc-purge-cache' )) {

		$result                   = array();
		$c_opt                    = get_option( AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME'] );
		$trans_domain_list        = array();
		if(isset($_REQUEST['list'] )){
		  // wp_kses_post() rather than sanitize_text_field(): the <div> wrappers must
		  // survive, because the regex below is what turns them into separators.
		  $trans_domain_list_string = preg_replace( "/<div>(.*?)<\/div>/", "$1;", trim( wp_kses_post( wp_unslash( $_REQUEST['list'] ) ) ) );
		  $trans_domain_list_string = wp_strip_all_tags( $trans_domain_list_string );
		  $trans_domain_list        = array_filter( explode( ";", trim( $trans_domain_list_string ) ), fn( $value ) => ! is_null( $value ) && $value !== '' );
		}
		foreach ( $trans_domain_list as $index => $string ) {
			$_check = wp_parse_url( $string );

			if ( $string !== "" ) {
				if (isset($_SERVER['SERVER_NAME']) && strpos( $string, sanitize_text_field(wp_unslash($_SERVER['SERVER_NAME'])) ) !== false ) {
					unset( $trans_domain_list[ $index ] );
				}
				if ( ! isset( $_check['path'] ) ) {
					$string .= '/';
					$_check = wp_parse_url( $string );
				}
				if ( ! isset( $_check['scheme'] ) ) {
					$string = "https://" . $string;
				} elseif ( $_check['scheme'] === "http" ) {
					$string = preg_replace( "/^http:/i", "https:", $string );
					//str_ireplace(array('http://'),'https://',$string);
				}
				$trans_domain_list[ $index ] = rtrim( trim( esc_url( $string, array( 'https' ) ) ), "/" );
			}
		}
		$c_opt['ahsc_dns_preconnect_domains'] = $trans_domain_list;
		$_res                                 = update_option( AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME'], $c_opt );
		$result['result']                     = $_res;
		echo wp_json_encode( $result );
		die();
	}
}


/** Function ahsc_ajax_cron_status() called by wp_ajax hooks: {'nopriv_ahsc_cron_status', 'ahsc_cron_status'} **/
/** Parameters found in function ahsc_ajax_cron_status(): {"post": ["ahsc_nonce"], "request": ["status"]} **/
function ahsc_ajax_cron_status() {
	if ( is_user_logged_in() && current_user_can( 'manage_options' ) && isset($_POST['ahsc_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash( $_POST['ahsc_nonce'])), 'ahsc-purge-cache' )) {

		$result                    = array();
		$c_opt                     = get_option( AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME'] );
		$c_opt['ahsc_cron_status'] = isset($_REQUEST['status']) && $_REQUEST['status'] === "true";
		$c_opt['ahsc_cron_time']   = $c_opt['ahsc_cron_time'] ?? AHSC_OPTIONS_LIST_DEFAULT['ahsc_cron_time']['default'];
		$_res                      = update_option( AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME'], $c_opt );
		$result['result']          = $_res;
		echo wp_json_encode( $result );
		die();
	}
}


/** Function ahsc_ajax_static_cache() called by wp_ajax hooks: {'nopriv_ahsc_static_cache', 'ahsc_static_cache'} **/
/** Parameters found in function ahsc_ajax_static_cache(): {"post": ["ahsc_nonce"], "request": ["status"]} **/
function ahsc_ajax_static_cache(){
	if(is_user_logged_in() && current_user_can( 'manage_options' ) && isset($_POST['ahsc_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash( $_POST['ahsc_nonce'])), 'ahsc-purge-cache' )) {

		$result = array();

		$c_opt                      = get_option( AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME'] );
		$c_opt['ahsc_static_cache'] = isset($_REQUEST['status']) && $_REQUEST['status'] === "true";
		$_res                       = update_option( AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME'], $c_opt );
		$result['result']           = $_res;
		echo  wp_json_encode( $result );
		die();
	}
}


/** Function ahsc_cache_warmer_ajax_action() called by wp_ajax hooks: {'ahcs_cache_warmer', 'nopriv_ahcs_cache_warmer'} **/
/** Parameters found in function ahsc_cache_warmer_ajax_action(): {"post": ["ahsc_cw_nonce"], "server": ["HTTP_HOST"]} **/
function ahsc_cache_warmer_ajax_action() {

    $ahsc_do_purge=get_option('ahsc_do_cache_warmer',false);

    if($ahsc_do_purge) {
		$do_warmer = array();

		if ( isset( $_POST['ahsc_cw_nonce'] ) && ! \wp_verify_nonce( \sanitize_text_field( \wp_unslash( $_POST['ahsc_cw_nonce'] ) ), 'ahsc-cache-warmer' ) ) {

			wp_die( wp_json_encode( AHSC_AJAX['security_error'] ) );
		}

		// If a static page has not been set as the site's home.
		if ( 'posts' === \get_option( 'show_on_front' ) ) {
			$do_warmer[] = \get_home_url( null, '/' );
		}

		// If a static page has been set as the site's home.
		if ( 'page' === get_option( 'show_on_front' ) ) {
			$do_warmer[] = \get_permalink( \get_option( 'page_on_front' ) );
			$blog_list   = \get_option( 'page_for_posts' );

			// I check whether the two urls are different. If no page is set as 'article page', the same url is returned.
			if ( '0' != $blog_list ) {
				$do_warmer[] = \get_post_type_archive_link( 'post' );
			}
		}

		if ( class_exists( 'woocommerce' ) ) {
			$do_warmer[] = get_permalink( wc_get_page_id( 'shop' ) );
		}


		$recent_posts = wp_get_recent_posts( array(
			'numberposts' => 10, // Number of recent posts
			'post_status' => 'publish' // Get only the published posts
		) );

		foreach ( $recent_posts as $recent_post ) {
			$do_warmer[] = get_permalink( $recent_post['ID'] );
		}

		//prodotti ultimi 10 prodotti modificati
		if ( class_exists( 'woocommerce' ) ) {
			$args     = array(
				'limit'   => 10,
				'orderby' => 'modified',
				'order'   => 'DESC',
				'return'  => 'ids',
			);
			$products = wc_get_products( $args );
			foreach ( $products as $pos => $pid ) {
				$do_warmer[] = get_permalink( $pid );
			}
		}

		//pagine linkate nella homepage
/*
		$url  = get_home_url();
		//$html = file_get_contents( $url );
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$html = curl_exec($ch);
		curl_close($ch);
		$doc  = new DOMDocument();
		$doc->loadHTML( $html );
		$xpath   = new DOMXpath( $doc );
		$nodes   = $xpath->query( '//a' );
		$_domain = preg_replace( '/^www\./', '', $_SERVER['HTTP_HOST'] );
		foreach ( $nodes as $node ) {
			$domain = implode( '.', array_slice( explode( '.', parse_url( $node->getAttribute( 'href' ), PHP_URL_HOST ) ), - 2 ) );
			if ( $domain == $_domain && array_search( trailingslashit( $node->getAttribute( 'href' ) ), $do_warmer ) === false ) {
				if ( $node->getAttribute( 'href' ) !== $url ) {
					$do_warmer[] = $node->getAttribute( 'href' );
				}
			}
		}
		$do_warmer = array_unique( $do_warmer );
*/

		/*
		 * Warming used raw cURL here. The WordPress HTTP API covers the same options
		 * one to one and, unlike a bare curl_exec(), also honours the
		 * pre_http_request / http_request_args filters, the WP_PROXY_* constants and
		 * WP_ACCESSIBLE_HOSTS, which some hosting setups rely on for outbound traffic.
		 */
		foreach ( $do_warmer as $warmer_item ) {
			$ahsc_response = \wp_remote_get(
				$warmer_item,
				array(
					/**
					 * Filters the per-URL timeout of the cache warmer, in seconds.
					 *
					 * The previous cURL implementation set no timeout at all, so a single
					 * slow page could hang the whole warming request.
					 *
					 * @param int    $timeout     Timeout in seconds.
					 * @param string $warmer_item URL being warmed.
					 */
					'timeout'     => \apply_filters( 'ahsc_cache_warmer_timeout', 10, $warmer_item ),
					// Was CURLOPT_FOLLOWLOCATION false.
					'redirection' => 0,
					/*
					 * Kept from the cURL implementation (CURLOPT_SSL_VERIFYPEER /
					 * CURLOPT_SSL_VERIFYHOST were both false): the warmer calls the site
					 * itself, which may answer on an internal name or a self-signed
					 * certificate.
					 */
					'sslverify'   => false,
					'user-agent'  => 'arubacache',
					'headers'     => array(
						'accept-encoding' => 'gzip, deflate, br, zstd',
					),
				)
			);

			/*
			 * curl_exec() returns false on failure, it never throws, so the try/catch
			 * that used to wrap it could not fire and every network error was lost.
			 */
			if ( \is_wp_error( $ahsc_response ) ) {
				AHSC_log(
					sprintf( 'Cache warming failed for %1$s: %2$s', $warmer_item, $ahsc_response->get_error_message() ),
					'cache-warmer',
					'warning'
				);
			}
		}

		update_option( 'ahsc_do_cache_warmer', false );
		wp_die( wp_json_encode( array( 'esit' => true, 'items' => $do_warmer ) ) );
	}else{
		wp_die( wp_json_encode( array( 'esit' => true, 'items' => 'no cache to warming' ) ) );
	}

	//update_option( 'ahsc_do_cache_warmer', false );
	
}


/** Function ahsc_ajax_enable_cron() called by wp_ajax hooks: {'nopriv_ahsc_enable_cron', 'ahsc_enable_cron'} **/
/** Parameters found in function ahsc_ajax_enable_cron(): {"post": ["ahsc_nonce"], "request": ["status"]} **/
function ahsc_ajax_enable_cron(){
	if(is_user_logged_in() && current_user_can( 'manage_options' ) && isset($_POST['ahsc_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash( $_POST['ahsc_nonce'])), 'ahsc-purge-cache' )) {

		$result                    = array();
		$wpc_transformer           = new HASC_WPCT( ABSPATH . 'wp-config.php' );
		$c_opt                     = get_option( AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME'] );
		$c_opt['ahsc_enable_cron'] = isset($_REQUEST['status']) && $_REQUEST['status'] === "true";
		$_res                      = update_option( AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME'], $c_opt );
		$result['result']          = $_res;

		if ( $c_opt['ahsc_enable_cron'] ) {
			//var_dump("non disabilito cron ");
			$wpc_transformer->remove( 'constant', 'DISABLE_WP_CRON' );
		} else {
			//var_dump("disabilito cron ");
			$wpc_transformer->update( 'constant', 'DISABLE_WP_CRON', 'true', array( 'raw'       => true,
			                                                                        'normalize' => true
			) );
			$wpc_transformer->remove( 'constant', 'WP_CRON_LOCK_TIMEOUT' );
		}
		echo wp_json_encode( $result );
		die();
	}
}


/** Function ahsc_create_apc_file() called by wp_ajax hooks: {'ahsc_create_apc_file', 'nopriv_ahsc_create_apc_file'} **/
/** Parameters found in function ahsc_create_apc_file(): {"post": ["ahsc_nonce"]} **/
function ahsc_create_apc_file(){
	if(current_user_can( 'manage_options' ) && isset($_POST['ahsc_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash( $_POST['ahsc_nonce'])), 'ahsc-purge-cache' )) {
		$result = array();
		$target = WP_CONTENT_DIR . '/object-cache.php';
		$source = __DIR__ . '/APC/object-cache.php';

		/*
		 * Was copy() followed by a separate chmod( $target, 0644 ). WP_Filesystem::copy()
		 * does both in one call — the fourth argument is the mode — and works on hosts
		 * where PHP cannot write directly and WordPress falls back to FTP or SSH. Where
		 * copy() used to succeed, get_filesystem_method() selects the "direct" transport,
		 * which is a plain copy() plus chmod, so nothing changes. The third argument is
		 * true because PHP's copy() overwrites by default and WP_Filesystem's does not.
		 */
		require_once ABSPATH . 'wp-admin/includes/file.php';
		global $wp_filesystem;

		$is_copied = false;

		if ( WP_Filesystem() && $wp_filesystem instanceof WP_Filesystem_Base ) {
			$is_copied = $wp_filesystem->copy( $source, $target, true, FS_CHMOD_FILE );
		} else {
			AHSC_log( 'Could not initialise WP_Filesystem, the object-cache.php drop-in was not installed.', 'apcu', 'warning' );
		}

		if ( ! $is_copied ) {
			AHSC_log( sprintf( 'Could not install the object-cache.php drop-in into %s.', WP_CONTENT_DIR ), 'apcu', 'warning' );
		}

		/*
		 * Was hard-coded to true regardless of the outcome: a failed copy still answered
		 * "ok", the interface ticked the checkbox and the follow-up call switched the
		 * ahsc_apc option on for a drop-in that had never been written.
		 */
		$result['result'] = (bool) $is_copied;
		echo wp_json_encode( $result );
	}
	die();
}


/** Function ahsc_ajax_purge_homepage_on_edit() called by wp_ajax hooks: {'ahsc_purge_homepage_on_edit', 'nopriv_ahsc_purge_homepage_on_edit'} **/
/** Parameters found in function ahsc_ajax_purge_homepage_on_edit(): {"post": ["ahsc_nonce"], "request": ["status"]} **/
function ahsc_ajax_purge_homepage_on_edit(){
	if(is_user_logged_in() && current_user_can( 'manage_options' ) && isset($_POST['ahsc_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash( $_POST['ahsc_nonce'])), 'ahsc-purge-cache' ) ) {

		$result = array();

		$c_opt                                = get_option( AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME'] );
		$c_opt['ahsc_purge_homepage_on_edit'] = isset($_REQUEST['status']) && $_REQUEST['status'] === "true";
		$_res                                 = update_option( AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME'], $c_opt );
		$result['result']                     = $_res;
		echo wp_json_encode( $result );
		die();
	}
}


/** Function ahsc_ajax_dboptimization_active() called by wp_ajax hooks: {'nopriv_ahsc_dboptimization', 'ahsc_dboptimization'} **/
/** Parameters found in function ahsc_ajax_dboptimization_active(): {"post": ["ahsc_nonce"], "request": ["dbstatus"]} **/
function ahsc_ajax_dboptimization_active(){
	if(is_user_logged_in() && current_user_can( 'manage_options' )  && isset($_POST['ahsc_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash( $_POST['ahsc_nonce'])), 'ahsc-purge-cache' )) {

		$result            = array();
		$result['message'] = '';
		$result['type']    = 'success';
		$result['result']  = AHSC_DBOPT_manage( (isset($_REQUEST['dbstatus'])?sanitize_textarea_field(wp_unslash($_REQUEST['dbstatus'])):null ) );
		echo wp_json_encode( $result );
		die();
	}
}


/** Function ahsc_ajax_debug_status() called by wp_ajax hooks: {'ahsc_debug_status', 'nopriv_ahsc_debug_status'} **/
/** Parameters found in function ahsc_ajax_debug_status(): {"post": ["ahsc_nonce"]} **/
function ahsc_ajax_debug_status(){
	if(is_user_logged_in() && current_user_can( 'manage_options' ) && isset($_POST['ahsc_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash( $_POST['ahsc_nonce'])), 'ahsc-purge-cache' )) {
		$result          = array();
		$wpc_transformer = new HASC_WPCT( ABSPATH . 'wp-config.php' );
		if ( $wpc_transformer->exists( 'constant', 'WP_DEBUG' ) ) {
			$result['result'] = $wpc_transformer->remove( 'constant', 'WP_DEBUG' );
		} else {
			$result['result'] = $wpc_transformer->update( 'constant', 'WP_DEBUG', 'true', array( 'raw'       => true,
			                                                                                     'normalize' => true
			) );
		}
		echo wp_json_encode( $result );
		die(); 
	}
}


/** Function ahsc_ajax_purge_archive_on_edit() called by wp_ajax hooks: {'ahsc_purge_archive_on_edit', 'nopriv_ahsc_purge_archive_on_edit'} **/
/** Parameters found in function ahsc_ajax_purge_archive_on_edit(): {"post": ["ahsc_nonce"], "request": ["status"]} **/
function ahsc_ajax_purge_archive_on_edit(){
	if(is_user_logged_in() && current_user_can( 'manage_options' ) && isset($_POST['ahsc_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash( $_POST['ahsc_nonce'])), 'ahsc-purge-cache' )) {

		$result = array();

		$c_opt                               = get_option( AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME'] );
		$c_opt['ahsc_purge_archive_on_edit'] = isset($_REQUEST['status']) && $_REQUEST['status'] === "true";
		$_res                                = update_option( AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME'], $c_opt );
		$result['result']                    = $_res;
		echo  wp_json_encode( $result );
		die();
	}
}


/** Function ahsc_tool_bar_purge() called by wp_ajax hooks: {'ahcs_clear_cache'} **/
/** Parameters found in function ahsc_tool_bar_purge(): {"post": ["ahsc_nonce", "ahsc_to_purge"]} **/
function ahsc_tool_bar_purge() {
if(is_user_logged_in() && current_user_can( 'manage_options' ) && isset( $_POST['ahsc_nonce'] )){

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ahsc_nonce'] ) ), 'ahsc-purge-cache' ) ) {
		wp_die( wp_json_encode( AHSC_AJAX['security_error'] ) );
	}else{

	$cleaner= new \ArubaSPA\HiSpeedCache\Purger\WpPurger() ;
	$cleaner->setPurger( AHSC_PURGER );

	if ( isset( $_POST['ahsc_to_purge'] ) ) {
		/*
		 * The value is either the literal "all" or a percent-encoded URL, so the
		 * sanitizer has to run before urldecode() and must leave the octets alone:
		 * sanitize_text_field() strips every %XX sequence and would shred the URL,
		 * while wp_strip_all_tags() only removes markup. The URL branch below still
		 * goes through esc_url_raw().
		 */
		$to_purge = urldecode( wp_strip_all_tags( wp_unslash( $_POST['ahsc_to_purge'] ) ) );

		if ( 'all' === $to_purge ) {
			$cleaner->purgeAll();
		} else {
			$cleaner->purgeUrl( \esc_url_raw( $to_purge ) );
		}
		// Don't forget to stop execution afterward.
		wp_die( wp_json_encode( AHSC_AJAX['success']) );
	}
	// Don't forget to stop execution afterward.
	wp_die( wp_json_encode(  AHSC_AJAX['warning'] ) );
    }
}else{
    wp_die( wp_json_encode( AHSC_AJAX['security_error'] ) );
}
}


/** Function ahsc_ajax_cron_time() called by wp_ajax hooks: {'ahsc_cron_time', 'nopriv_ahsc_cron_time'} **/
/** Parameters found in function ahsc_ajax_cron_time(): {"post": ["ahsc_nonce"], "request": ["time"]} **/
function ahsc_ajax_cron_time(){
	if(is_user_logged_in() && current_user_can( 'manage_options' ) && isset($_POST['ahsc_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash( $_POST['ahsc_nonce'])), 'ahsc-purge-cache' )) {

		$result                  = array();
		$wpc_transformer         = new HASC_WPCT( ABSPATH . 'wp-config.php' );
		$c_opt                   = get_option( AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME'] );
		$c_opt['ahsc_cron_time'] = (isset($_REQUEST['time']))?sanitize_text_field(wp_unslash($_REQUEST['time'])):false;
		$_res                    = update_option( AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME'], $c_opt );
		$result['result']        = $_res;
		//var_dump("setto time ");
		$wpc_transformer->update( 'constant', 'WP_CRON_LOCK_TIMEOUT', "'" . absint( $c_opt['ahsc_cron_time'] ) . "'", array( 'raw'       => true,
		                                                                                                                     'normalize' => true
		) );
		echo wp_json_encode( $result );
		die();
	}
}


/** Function ahsc_ajax_reset_options() called by wp_ajax hooks: {'nopriv_ahsc_reset_options', 'ahsc_reset_options'} **/
/** Parameters found in function ahsc_ajax_reset_options(): {"post": ["ahsc_nonce"]} **/
function ahsc_ajax_reset_options(){
	if(is_user_logged_in() && current_user_can( 'manage_options' ) && isset($_POST['ahsc_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash( $_POST['ahsc_nonce'])), 'ahsc-purge-cache' )) {

		$result            = array();
		$msg               = ahsc_reset_options();
		$result['message'] = $msg;
		$result['type']    = 'success';
		$result['action']  = wp_kses( __( 'Reload', 'aruba-hispeed-cache' ), array( 'strong' => array() ) );
		echo wp_json_encode( $result );
		die();
	}
}


/** Function ahsc_update_apc_Settings() called by wp_ajax hooks: {'nopriv_ahsc_update_apc_Settings', 'ahsc_update_apc_Settings'} **/
/** Parameters found in function ahsc_update_apc_Settings(): {"post": ["ahsc_nonce"]} **/
function ahsc_update_apc_Settings() {
	if(current_user_can( 'manage_options' ) && isset($_POST['ahsc_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash( $_POST['ahsc_nonce'])), 'ahsc-purge-cache' )) {
		$result            = array();
		$c_opt             = AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS'];
		$c_opt['ahsc_apc'] = true;
		update_site_option( AHSC_CONSTANT['ARUBA_HISPEED_CACHE_OPTIONS_NAME'], $c_opt );
		$result['result'] = true;
		echo wp_json_encode( $result );
	}
	die();
}


