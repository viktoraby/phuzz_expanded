<?php
/***
*
*Found actions: 3
*Found functions:3
*Extracted functions:2
*Total parameter names extracted: 2
*Overview: {'nfw_fullwafsetup': {'nfw_fullwafsetup'}, 'NinjaFirewall_plugin': {'nfw_pluginupgrade'}, 'nfw_fullwafconfig': {'nfw_fullwafconfig'}}
*
***/

/** Function nfw_fullwafsetup() called by wp_ajax hooks: {'nfw_fullwafsetup'} **/
/** Parameters found in function nfw_fullwafsetup(): {"post": ["httpserver", "diy", "exclude_waf_list", "sandbox", "initype"]} **/
function nfw_fullwafsetup() {

	nf_not_allowed( 'block', __LINE__ );

	if (! check_ajax_referer( 'events_save', 'nonce', false ) ) {
		esc_html_e('Error: Security nonces do not match. Reload the page and try again.', 'ninjafirewall');
		wp_die();
	}

	$nfw_options = nfw_get_option( 'nfw_options' );
	if ( empty( $nfw_options['enabled'] ) ) {
		esc_html_e('Error: NinjaFirewall is disabled', 'ninjafirewall');
		wp_die();
	}

	if ( empty( $_POST['httpserver'] ) ) {
		printf( esc_html__('Error: missing parameter (%s).', 'ninjafirewall'), 'httpserver' );
		wp_die();
	}
	if ( preg_match('/^[^1-8]$/', $_POST['httpserver'] ) ) {
		printf( esc_html__('Error: wrong parameter value (%s).', 'ninjafirewall'), 'httpserver' );
		wp_die();
	}
	if ( empty( $_POST['diy'] ) || ! preg_match( '/^(nfw|usr)$/', $_POST['diy'] ) ) {
		printf( esc_html__('Error: wrong parameter value (%s).', 'ninjafirewall'), 'diy' );
		wp_die();
	}

	// Retrieve the list of excluded folders, if any, and save it
	nfw_save_waf_exclusionlist( $_POST['exclude_waf_list'] );

	// Disable the sandbox?
	if ( empty( $_POST['sandbox'] ) ) {
		define('NFW_BYPASS_SANDBOX', true);
	}

	$time = time() + 300;

	// 1: Apache mod_php
	// 2: Apache + CGI/FastCGI or PHP-FPM
	// 3: Apache + suPHP
	// 4: Nginx + CGI/FastCGI or PHP-FPM
	// 5: Litespeed
	// 6: Openlitespeed
	// 7: Other webserver + CGI/FastCGI or PHP-FPM
	// 8: Apache + LSAPI
	$httpserver = (int) $_POST['httpserver'];

	// [6] Openlitespeed: nothing to do.
	if ( $httpserver == 6 ) {
		set_transient( 'nfw_fullwaf', "{$httpserver}:{$time}", 60 * 5 );
		echo '200';
		wp_die();
	}

	require_once __DIR__ .'/lib/install.php';

	// .htaccess mods only
	if ( $httpserver == 1 || $httpserver == 5 || $httpserver == 8 ) {
		// User wants to make the modification
		if ( $_POST['diy'] == 'usr' ) {
			// Nothing to do
			set_transient( 'nfw_fullwaf', "{$httpserver}:{$time}", 60 * 5 );
			echo '200';
			wp_die();
		}
		// Make changes
		$ret = nfw_fullwaf_htaccess( $httpserver );
		if ( $ret !== true ) {
			echo esc_html( $ret );
		} else {
			set_transient( 'nfw_fullwaf', "{$httpserver}:{$time}", 60 * 5 );
			echo '200';
		}
		wp_die();
	}

	if ( $_POST['diy'] == 'usr' ) {
		// Nothing to do, but add 5-minute notice to the overview page
		// because an INI file is being used
		set_transient( 'nfw_fullwaf', "{$httpserver}:{$time}", 60 * 5 );
		echo '200';
		wp_die();
	}

	// [1] .user.ini
	// [2] php.ini
	if ( empty ( $_POST['initype'] ) || ! preg_match( '/^[12]$/', $_POST['initype'] ) ) {
		$initype = 1;
	} else {
		$initype = (int) $_POST['initype'];
	}

	if ( $httpserver == 3 ) { // Apache + suPHP
		// Set up the htaccess file
		$ret = nfw_fullwaf_htaccess( $httpserver );
		if ( $ret !== true ) {
			echo esc_html( $ret );
			wp_die();
		}
	}
	// ini file
	$ret = nfw_fullwaf_ini( $httpserver, $initype );
	if ( $ret !== true ) {
		echo esc_html( $ret );
		wp_die();
	} else {
		// Add 5-minute notice to the overview page
		// because an INI file is being used
		set_transient( 'nfw_fullwaf', "{$httpserver}:{$time}", 60 * 5 );
		echo 200;
	}
	wp_die();
}


/** Function NinjaFirewall_plugin() called by wp_ajax hooks: {'nfw_pluginupgrade'} **/
/** No function found :-/ **/


/** Function nfw_fullwafconfig() called by wp_ajax hooks: {'nfw_fullwafconfig'} **/
/** Parameters found in function nfw_fullwafconfig(): {"post": ["what", "list"]} **/
function nfw_fullwafconfig() {

	nf_not_allowed( 'block', __LINE__ );

	if (! check_ajax_referer( 'events_save', 'nonce', false ) ) {
		esc_html_e('Error: Security nonces do not match. Reload the page and try again.', 'ninjafirewall');
		wp_die();
	}

	if ( empty( $_POST['what'] ) || ! preg_match( '/^[12]$/', $_POST['what'] ) ) {
		printf( esc_html__('Error: missing parameter (%s).', 'ninjafirewall'), 'what' );
		wp_die();
	}

	// Downgrade to WP WAF
	if ( $_POST['what'] == 2 ) {

		require __DIR__ .'/lib/install.php';
		nfw_get_constants();
		nfw_remove_directives();

	// Full WAF directories exclusion
	} else {
		// Retrieve the list of excluded folders, if any, and save it
		nfw_save_waf_exclusionlist( $_POST['list'] );
	}

	wp_die(200);
}


