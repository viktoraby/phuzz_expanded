<?php
/***
*
*Found actions: 30
*Found functions:29
*Extracted functions:29
*Total parameter names extracted: 23
*Overview: {'welcome_screen': {'cn_welcome_screen'}, 'query_forms': {'cn_privacy_consent_get_forms'}, 'get_consent_logs': {'cn_react_consent_logs'}, 'dismiss_welcome': {'cn_dismiss_welcome', 'cn_react_dismiss_welcome'}, 'api_request': {'cn_api_request'}, 'set_form_status': {'cn_privacy_consent_form_status'}, 'export_consent_logs': {'cn_react_export_consent_logs'}, 'complete_setup_wizard': {'cn_react_complete_setup_wizard'}, 'ajax_dismiss_admin_notice': {'cn_dismiss_notice'}, 'deactivate_plugin': {'cn-deactivate-plugin'}, 'get_config': {'cn_react_config'}, 'get_api_environment': {'cn_get_api_environment'}, 'test_set_option': {'cn_react_test_set_option'}, 'test_get_option': {'cn_react_test_get_option'}, 'react_apply_languages': {'cn_react_apply_languages'}, 'get_privacy_consent_logs': {'cn_get_privacy_consent_logs'}, 'update_script': {'cn_react_script_update'}, 'get_cookie_consent_logs': {'cn_get_cookie_consent_logs'}, 'react_update_design': {'cn_react_update_design'}, 'ajax_purge_cache': {'cn_purge_cache'}, 'save_options': {'cn_react_save_options'}, 'get_dashboard': {'cn_react_dashboard'}, 'get_rule_values': {'cn_react_rule_values'}, 'display_table': {'cn_privacy_consent_display_table'}, 'dev_reset': {'cn_react_dev_reset'}, 'ajax_review_notice': {'cn_review_notice'}, 'react_apply_template': {'cn_react_apply_template'}, 'rescan_scripts': {'cn_react_rescan_scripts'}, 'get_group_rule_values': {'cn-get-group-rules-values'}}
*
***/

/** Function welcome_screen() called by wp_ajax hooks: {'cn_welcome_screen'} **/
/** Parameters found in function welcome_screen(): {"request": ["screen"]} **/
function welcome_screen( $screen, $echo = true ) {
		if ( ! current_user_can( 'install_plugins' ) )
			wp_die( __( 'You do not have permission to access this page.', 'cookie-notice' ) );

		$sidebars = [ 'about', 'login', 'register', 'configure', 'success' ];
		$steps = [ 1, 2, 3, 4 ];
		$screens = array_merge( $sidebars, $steps );

		if ( ! empty( $screen ) ) {
			if ( is_numeric( $screen ) )
				$screen = (int) $screen;
			else
				$screen = sanitize_key( $screen );
		} else
			$screen = '';

		if ( empty( $screen ) || ! in_array( $screen, $screens, true ) ) {
			if ( isset( $_REQUEST['screen'] ) ) {
				if ( is_numeric( $_REQUEST['screen'] ) )
					$screen = (int) $_REQUEST['screen'];
				else
					$screen = sanitize_key( $_REQUEST['screen'] );
			} else
				$screen = '';

			if ( ! in_array( $screen, $screens, true ) )
				$screen = '';
		}

		if ( empty( $screen ) )
			wp_die( __( 'You do not have permission to access this page.', 'cookie-notice' ) );

		if ( wp_doing_ajax() && ! check_ajax_referer( 'cookie-notice-welcome', 'nonce' ) )
			wp_die( __( 'You do not have permission to access this page.', 'cookie-notice' ) );

		// step screens
		if ( in_array( $screen, $steps ) ) {
			$html = '
			<div class="wrap full-width-layout cn-welcome-wrap cn-welcome-step-' . esc_attr( $screen ) . ' has-loader">';

			if ( $screen == 1 ) {
				$html .= $this->welcome_screen( 'about', false );

				$html .= '
				<div class="cn-content cn-sidebar-visible">
					<div class="cn-inner">
						<div class="cn-content-full">
							<h1><b>Compliance by Hu-manity.co</b></h1>
							<h2>' . esc_html__( 'Simple cookie & privacy compliance solution for your business.', 'cookie-notice' ) . '</h2>
							<div class="cn-lead">
								<div class="cn-hero-image">
									<div class="cn-flex-item">
										<img src="' . esc_url( COOKIE_NOTICE_URL ) . '/img/screen-compliance.png" alt="Compliance by Hu-manity.co dashboard" />
									</div>
								</div>
								<p>' . sprintf( esc_html__( 'Protect your business and take a proactive approach to data privacy laws with Compliance by Hu-manity.co. Build trust by giving your website visitors a beautiful, multi-level consent experience that complies with the latest cookie regulations in 100+ countries.', 'cookie-notice' ), '<b>', '</b>' ) . '</p>
							</div>';
				$html .= '
							<div class="cn-buttons">
								<button type="button" class="cn-btn cn-btn-lg cn-screen-button" data-screen="2"><span class="cn-spinner"></span>' . esc_html__( 'Sign up to Compliance by Hu-manity.co', 'cookie-notice' ) . '</button><br />
								<button type="button" class="cn-btn cn-btn-lg cn-btn-transparent cn-skip-button">' . esc_html__( 'Skip for now', 'cookie-notice' ) . '</button>
							</div>
							';

				$html .= '
						</div>
					</div>
				</div>';
			} elseif ( $screen == 2 ) {
				$html .= $this->welcome_screen( 'configure', false );

				$html .= '
				<div id="cn_upgrade_iframe" class="cn-content cn-sidebar-visible has-loader cn-loading"><span class="cn-spinner"></span>
					<iframe id="cn_iframe_id" src="' . esc_url( home_url( '/?cn_preview_mode=1' ) ) . '"></iframe>
				</div>';
			} elseif ( $screen == 3 ) {
				$html .= $this->welcome_screen( 'register', false );

				$html .= '
				<div class="cn-content cn-sidebar-visible">
					<div class="cn-inner">
						<div class="cn-content-full">
							<h1><b>Compliance by Hu-manity.co</b></h1>
							<h2>' . esc_html__( 'Consent Management Platform with simple, transparent pricing.', 'cookie-notice' ) . '</h2>
							<div class="cn-lead">
								<p>' . esc_html__( 'Choose monthly or yearly payment and number of domains for the fully featured, Professional plan. Or start with limited, Basic plan for free.', 'cookie-notice' ) . '</p>
							</div>';

				$html .= '
							<h3 class="cn-pricing-select">' . esc_html__( 'Select plan', 'cookie-notice' ) . ':</h3>
							<div class="cn-pricing-type cn-checkmark-wrapper">
								<label for="pricing-type-monthly"><input id="pricing-type-monthly" type="radio" name="cn_pricing_type" value="monthly" checked><span class="cn-pricing-toggle toggle-left"><span class="cn-checkmark-container"><span class="cn-checkmark"></span></span><span class="cn-label">' . esc_html__( 'Monthly', 'cookie-notice' ) . '</span></span></label>
								<label for="pricing-type-yearly"><input id="pricing-type-yearly" type="radio" name="cn_pricing_type" value="yearly"><span class="cn-pricing-toggle toggle-right"><span class="cn-checkmark-container"><span class="cn-checkmark"></span></span><span class="cn-label">' . esc_html__( 'Yearly', 'cookie-notice' ) . '<span class="cn-badge">' . esc_html__( 'Save 12%', 'cookie-notice' ) . '</span></span></span></label>
							</div>
							<div class="cn-pricing-table">
								<label class="cn-pricing-item cn-pricing-plan-free" for="cn-pricing-plan-free">
									<input id="cn-pricing-plan-free" type="radio" name="cn_pricing" value="free">
									<div class="cn-pricing-info">
										<div class="cn-pricing-head">
											<h4>' . esc_html__( 'Basic', 'cookie-notice' ) . '</h4>
											<span class="cn-plan-pricing"><span class="cn-plan-price">' . esc_html__( 'Free', 'cookie-notice' ) . '</span></span>
										</div>
										<div class="cn-pricing-body">
											<p class="cn-included"><span class="cn-icon"></span>' . esc_html__( 'GDPR, CCPA, LGPD, PECR requirements', 'cookie-notice' ) . '</p>
											<p class="cn-included"><span class="cn-icon"></span>' . esc_html__( 'Consent Analytics Dashboard', 'cookie-notice' ) . '</p>
											<p class="cn-excluded"><span class="cn-icon"></span>' . sprintf( esc_html__( '%s1,000%s visits / month', 'cookie-notice' ), '<b>', '</b>' ) . '</p>
											<p class="cn-excluded"><span class="cn-icon"></span>' . sprintf( esc_html__( '%s100%s privacy consents', 'cookie-notice' ), '<b>', '</b>' ) . '</p>
											<p class="cn-excluded"><span class="cn-icon"></span>' . sprintf( esc_html__( '%s30 days%s consent storage', 'cookie-notice' ), '<b>', '</b>' ) . '</p>
											<p class="cn-excluded"><span class="cn-icon"></span>' . sprintf( esc_html__( '%sGoogle & Facebook%s consent modes', 'cookie-notice' ), '<b>', '</b>' ) . '</p>
											<p class="cn-excluded"><span class="cn-icon"></span>' . sprintf( esc_html__( '%sGeolocation%s support', 'cookie-notice' ), '<b>', '</b>' ) . '</p>
											<p class="cn-excluded"><span class="cn-icon"></span>' . sprintf( esc_html__( '%s1 additional%s language', 'cookie-notice' ), '<b>', '</b>' ) . '</p>
											<p class="cn-excluded"><span class="cn-icon"></span>' . sprintf( esc_html__( '%sBasic%s Support', 'cookie-notice' ), '<b>', '</b>' ) . '</p>
										</div>
										<div class="cn-pricing-footer">
											<button type="button" class="cn-btn cn-btn-outline">' . esc_html__( 'Start Basic', 'cookie-notice' ) . '</button>
											<span class="cn-trust-badge">' . esc_html__( 'No credit card · Free to start', 'cookie-notice' ) . '</span>
										</div>
									</div>
								</label>
								<label class="cn-pricing-item cn-pricing-plan-pro" for="cn-pricing-plan-pro">
									<input id="cn-pricing-plan-pro" type="radio" name="cn_pricing" value="pro">
									<div class="cn-pricing-info">
										<div class="cn-pricing-head">
											<h4>' . esc_html__( 'Professional', 'cookie-notice' ) . '</h4>
											<span class="cn-plan-pricing"><span class="cn-plan-price"><sup>$ </sup><span class="cn-plan-amount">' . esc_attr( $this->pricing_monthly['compliance_monthly_notrial'] ) . '</span><sub> / <span class="cn-plan-period">' . esc_html__( 'monthly', 'cookie-notice' ) . '</span></sub></span></span>
											<span class="cn-plan-promo">' . esc_html__( 'Recommended', 'cookie-notice' ) . '</span>
											<div class="cn-select-wrapper">
												<select name="cn_pricing_plan" class="form-select" aria-label="' . esc_html__( 'Pricing options', 'cookie-notice' ) . '" id="cn-pricing-plans">
													<option value="compliance_monthly_notrial" data-price="' . esc_attr( $this->pricing_monthly['compliance_monthly_notrial'] ) . '">' . esc_html( sprintf( _n( '%s domain license', '%s domains license', 1, 'cookie-notice' ), 1 ) ) . '</option>
													<option value="compliance_monthly_5" data-price="' . esc_attr( $this->pricing_monthly['compliance_monthly_5'] ) . '">' . esc_html( sprintf( _n( '%s domain license', '%s domains license', 5, 'cookie-notice' ), 5 ) ) . '</option>
													<option value="compliance_monthly_10" data-price="' . esc_attr( $this->pricing_monthly['compliance_monthly_10'] ) . '">' . esc_html( sprintf( _n( '%s domain license', '%s domains license', 10, 'cookie-notice' ), 10 ) ) . '</option>
													<option value="compliance_monthly_20" data-price="' . esc_attr( $this->pricing_monthly['compliance_monthly_20'] ) . '">' . esc_html( sprintf( _n( '%s domain license', '%s domains license', 20, 'cookie-notice' ), 20 ) ) . '</option>
												</select>
											</div>
										</div>
										<div class="cn-pricing-body">
											<p class="cn-included"><span class="cn-icon"></span>' . esc_html__( 'GDPR, CCPA, LGPD, PECR requirements', 'cookie-notice' ) . '</p>
											<p class="cn-included"><span class="cn-icon"></span>' . esc_html__( 'Consent Analytics Dashboard', 'cookie-notice' ) . '</p>
											<p class="cn-included"><span class="cn-icon"></span>' . sprintf( esc_html__( '%sUnlimited%s visits', 'cookie-notice' ), '<b>', '</b>' ) . '</p>
											<p class="cn-included"><span class="cn-icon"></span>' . sprintf( esc_html__( '%sUnlimited%s privacy consents', 'cookie-notice' ), '<b>', '</b>' ) . '</p>
											<p class="cn-included"><span class="cn-icon"></span>' . sprintf( esc_html__( '%sLifetime%s consent storage', 'cookie-notice' ), '<b>', '</b>' ) . '</p>
											<p class="cn-included"><span class="cn-icon"></span>' . sprintf( esc_html__( '%sGoogle & Facebook%s consent modes', 'cookie-notice' ), '<b>', '</b>' ) . '</p>
											<p class="cn-included"><span class="cn-icon"></span>' . sprintf( esc_html__( '%sGeolocation%s support', 'cookie-notice' ), '<b>', '</b>' ) . '</p>
											<p class="cn-included"><span class="cn-icon"></span>' . sprintf( esc_html__( '%sUnlimited%s languages', 'cookie-notice' ), '<b>', '</b>' ) . '</p>
											<p class="cn-included"><span class="cn-icon"></span>' . sprintf( esc_html__( '%sPriority%s Support', 'cookie-notice' ), '<b>', '</b>' ) . '</p>
										</div>
										<div class="cn-pricing-footer">
											<button type="button" class="cn-btn cn-btn-secondary">' . esc_html__( 'Start Professional', 'cookie-notice' ) . '</button>
										</div>
									</div>
								</label>
							</div>
							<div class="cn-buttons">
								<button type="button" class="cn-btn cn-btn-lg cn-btn-transparent cn-skip-button">' . esc_html__( "I don’t want to create an account now", 'cookie-notice' ) . '</button>
							</div>';

				$html .= '
						</div>
					</div>
				</div>';
			} elseif ( $screen == 4 ) {
				$html .= $this->welcome_screen( 'success', false );

				// get main instance
				$cn = Cookie_Notice();
				$subscription = $cn->get_subscription();

				$html .= '
				<div class="cn-content cn-sidebar-visible">
					<div class="cn-inner">
						<div class="cn-content-full">
							<h1><b>' . esc_html__( 'Congratulations', 'cookie-notice' ) . '</b></h1>
							<h2>' . esc_html__( 'You have successfully signed up to Compliance by Hu-manity.co.', 'cookie-notice' ) . '</h2>
							<div class="cn-lead">
								<p>' . esc_html__( 'Log in to your account and continue configuring your website.', 'cookie-notice' ) . '</p>
							</div>
							<div class="cn-buttons">
								<a href="' . esc_url( $cn->get_url( 'host', '?utm_campaign=configure&utm_source=wordpress&utm_medium=button#/login' ) ) . '" class="cn-btn cn-btn-lg" target="_blank">' . esc_html__( 'Go to Application', 'cookie-notice' ) . '</a>
							</div>
						</div>
					</div>
				</div>';
			}

			$html .= '
			</div>';
		// sidebar screens
		} elseif ( in_array( $screen, $sidebars ) ) {
			$html = '';

			if ( $screen === 'about' ) {
				$theme = wp_get_theme();

				$html .= '
				<div class="cn-sidebar cn-sidebar-left has-loader">
					<div class="cn-inner">
						<div class="cn-header">
							<div class="cn-top-bar">
								<div class="cn-logo"><img src="' . esc_url( COOKIE_NOTICE_URL ) . '/img/cookie-compliance-logo.png" alt="Compliance by Hu-manity.co" /></div>
							</div>
						</div>
						<div class="cn-body">
							<h2>' . esc_html__( 'Compliance check', 'cookie-notice' ) . '</h2>
							<div class="cn-lead"><p>' . esc_html__( 'This is a Compliance Check to determine your site’s compliance with updated data processing and consent rules under GDPR, CCPA and other international data privacy laws.', 'cookie-notice' ) . '</p></div>
							<div id="cn_preview_about">
								<p>' . esc_html__( 'Site URL', 'cookie-notice' ) . ': <b>' . esc_url( home_url() ) . '</b></p>
								<p>' . esc_html__( 'Site Name', 'cookie-notice' ) . ': <b>' . esc_html( get_bloginfo( 'name' ) ) . '</b></p>
							</div>
							<div class="cn-compliance-check">
								<div class="cn-progressbar"><div class="cn-progress-label">' . esc_html__( 'Checking...', 'cookie-notice' ) . '</div></div>
								<div class="cn-compliance-feedback cn-hidden"></div>
								<div class="cn-compliance-results">
									<div class="cn-compliance-item"><p><span class="cn-compliance-label">' . esc_html__( 'Consent Banner', 'cookie-notice' ) . ' </span><span class="cn-compliance-status"></span></p><p><span class="cn-compliance-desc">' . esc_html__( 'Notify visitors to the site that it uses cookies or similar technologies.', 'cookie-notice' ) . '</span></p></div>
									<div class="cn-compliance-item" style="display: none"><p><span class="cn-compliance-label">' . esc_html__( 'Autoblocking', 'cookie-notice' ) . ' </span><span class="cn-compliance-status"></span></p><p><span class="cn-compliance-desc">' . esc_html__( 'Block non-essential 3rd party services until consent is registered.', 'cookie-notice' ) . '</span></p></div>
									<div class="cn-compliance-item" style="display: none"><p><span class="cn-compliance-label">' . esc_html__( 'Cookie Categories', 'cookie-notice' ) . ' </span><span class="cn-compliance-status"></span></p><p><span class="cn-compliance-desc">' . esc_html__( 'Allow to customize the consent requested per purpose of use.', 'cookie-notice' ) . '</span></p></div>
									<div class="cn-compliance-item" style="display: none"><p><span class="cn-compliance-label">' . esc_html__( 'Cookie Consent Logs', 'cookie-notice' ) . ' </span><span class="cn-compliance-status"></span></p><p><span class="cn-compliance-desc">' . esc_html__( "Save the website visitor's cookie consent preferences.", 'cookie-notice' ) . '</span></p></div>
									<div class="cn-compliance-item" style="display: none"><p><span class="cn-compliance-label">' . esc_html__( 'Privacy Consent Logs', 'cookie-notice' ) . ' </span><span class="cn-compliance-status"></span></p><p><span class="cn-compliance-desc">' . esc_html__( "Record the website user's consent to the processing of personal data.", 'cookie-notice' ) . '</span></p></div>
									<div class="cn-compliance-item" style="display: none"><p><span class="cn-compliance-label">' . esc_html__( 'Proof-of-Consent', 'cookie-notice' ) . ' </span><span class="cn-compliance-status"></span></p><p><span class="cn-compliance-desc">' . esc_html__( 'Store and export a Proof-of-consent in secure audit format.', 'cookie-notice' ) . '</span></p></div>
								</div>
							</div>
							' /* <div id="cn_preview_frame"><img src=" ' . esc_url( $theme->get_screenshot() ) . '" /></div>
							. '<div id="cn_preview_frame"><div id="cn_preview_frame_wrapper"><iframe id="cn_iframe_id" src="' . home_url( '/?cn_preview_mode=0' ) . '" scrolling="no" frameborder="0"></iframe></div></div> */ . '
						</div>';
			} elseif ( $screen === 'configure' ) {
				$html .= '
				<div class="cn-sidebar cn-sidebar-left has-loader cn-theme-light">
					<div class="cn-inner">
						<div class="cn-header">
							<div class="cn-top-bar">
								<div class="cn-logo"><img src="' . esc_url( COOKIE_NOTICE_URL ) . '/img/cookie-compliance-logo.png" alt="Compliance by Hu-manity.co" /></div>
							</div>
						</div>
						<div class="cn-body">
							<h2>' . esc_html__( 'Live Setup', 'cookie-notice' ) . '</h2>
							<div class="cn-lead"><p>' . esc_html__( 'Configure your Compliance by Hu-manity.co design and compliance features through the options below. Click Apply Setup to save the configuration and go to selecting your preferred cookie solution.', 'cookie-notice' ) . '</p></div>
							<form method="post" id="cn-form-configure" class="cn-form" action="" data-action="configure">
								<div class="cn-accordion">
									<div class="cn-accordion-item cn-form-container" tabindex="-1">
										<div class="cn-accordion-header cn-form-header"><button class="cn-accordion-button" type="button">' . esc_html__( 'Banner Compliance', 'cookie-notice' ) . '</button></div>
										<div class="cn-accordion-collapse cn-form">
											<div class="cn-form-feedback cn-hidden"></div>' .
											/*
											<div class="cn-field cn-field-select">
												<label for="cn_location">' . __( 'What is the location of your business/organization?', 'cookie-notice' ) . '​</label>
												<div class="cn-select-wrapper">
													<select id="cn_location" name="cn_location">
														<option value="0">' . __( 'Select location', 'cookie-notice' ) . '</option>';

				foreach ( Cookie_Notice()->settings->countries as $country_code => $country_name ) {
					$html .= '<option value="' . $country_code . '">' . $country_name . '</option>';
				}

				$html .= '
													</select>
												</div>
											</div>
											*/
											'
											<div id="cn_laws" class="cn-field cn-field-checkbox">
												<label>' . esc_html__( 'Select the laws that apply to your business', 'cookie-notice' ) . ':</label>
												<div class="cn-checkbox-wrapper">
													<label for="cn_laws_gdpr"><input id="cn_laws_gdpr" type="checkbox" name="cn_laws" value="gdpr" title="' . esc_attr__( 'GDPR', 'cookie-notice' ) . '" checked><span>' . esc_html__( 'GDPR', 'cookie-notice' ) . '<span class="cn-tooltip" aria-label="' . esc_html__( 'European Union and Switzerland', 'cookie-notice' ) . '" data-microtip-position="right" data-microtip-size="small" role="tooltip"><i class="cn-tooltip-icon"></i></span></span></label>
													<label for="cn_laws_ccpa"><input id="cn_laws_ccpa" type="checkbox" name="cn_laws" value="ccpa" title="' . esc_attr__( 'CCPA', 'cookie-notice' ) . '"><span>' . esc_html__( 'CCPA/CPRA', 'cookie-notice' ) . '<span class="tooltip" aria-label="' . esc_html__( 'California', 'cookie-notice' ) . '" data-microtip-position="right" data-microtip-size="small" role="tooltip"><i class="cn-tooltip-icon"></i></span></span></label>
													<label for="cn_laws_otherus"><input id="cn_laws_otherus" type="checkbox" name="cn_laws" value="otherus" title="' . esc_attr__( 'Other U.S. State Laws', 'cookie-notice' ) . '"><span>' . esc_html__( 'Other U.S. State Laws', 'cookie-notice' ) . '<span class="tooltip" aria-label="' . esc_html__( 'Virginia, Colorado, Connecticut, Utah, etc.', 'cookie-notice' ) . '" data-microtip-position="right" data-microtip-size="small" role="tooltip"><i class="cn-tooltip-icon"></i></span></span></label>
													<label for="cn_laws_ukpecr"><input id="cn_laws_ukpecr" type="checkbox" name="cn_laws" value="ukpecr" title="' . esc_attr__( 'UK PECR', 'cookie-notice' ) . '"><span>' . esc_html__( 'UK PECR', 'cookie-notice' ) . '<span class="tooltip" aria-label="' . esc_html__( 'United Kingdom', 'cookie-notice' ) . '" data-microtip-position="right" data-microtip-size="small" role="tooltip"><i class="cn-tooltip-icon"></i></span></span></label>
													<label for="cn_laws_lgpd"><input id="cn_laws_lgpd" type="checkbox" name="cn_laws" value="lgpd" title="' . esc_attr__( 'LGPD', 'cookie-notice' ) . '"><span>' . esc_html__( 'LGPD', 'cookie-notice' ) . '<span class="tooltip" aria-label="' . esc_html__( 'Brazil', 'cookie-notice' ) . '" data-microtip-position="right" data-microtip-size="small" role="tooltip"><i class="cn-tooltip-icon"></i></span></span></label>
													<label for="cn_laws_pipeda"><input id="cn_laws_pipeda" type="checkbox" name="cn_laws" value="pipeda" title="' . esc_attr__( 'PIPEDA', 'cookie-notice' ) . '"><span>' . esc_html__( 'PIPEDA', 'cookie-notice' ) . '<span class="tooltip" aria-label="' . esc_html__( 'Canada', 'cookie-notice' ) . '" data-microtip-position="right" data-microtip-size="small" role="tooltip"><i class="cn-tooltip-icon"></i></span></span></label>
													<label for="cn_laws_popia"><input id="cn_laws_popia" type="checkbox" name="cn_laws" value="popia" title="' . esc_attr__( 'POPIA', 'cookie-notice' ) . '"><span>' . esc_html__( 'POPIA', 'cookie-notice' ) . '<span class="tooltip" aria-label="' . esc_html__( 'South Africa', 'cookie-notice' ) . '" data-microtip-position="right" data-microtip-size="small" role="tooltip"><i class="cn-tooltip-icon"></i></span></span></label>
													<label for="cn_laws_other"><input id="cn_laws_other" type="checkbox" name="cn_laws" value="other" title="' . esc_attr__( 'Other', 'cookie-notice' ) . '"><span>' . esc_html__( 'Other', 'cookie-notice' ) . '</span></label>
												</div>
											</div>
											<div id="cn_naming" class="cn-field cn-field-radio">
												<label class="cn-asterix">' . esc_html__( 'Select a naming style for the consent choices', 'cookie-notice' ) . ':</label>
												<div class="cn-radio-wrapper">
													<label for="cn_naming_1"><input id="cn_naming_1" type="radio" name="cn_naming" value="1" checked><span>' . esc_html__( 'Private, Balanced, Personalized', 'cookie-notice' ) . '</span></label>
													<label for="cn_naming_2"><input id="cn_naming_2" type="radio" name="cn_naming" value="2"><span>' . esc_html__( 'Silver, Gold, Platinum', 'cookie-notice' ) . '</span></label>
													<label for="cn_naming_3"><input id="cn_naming_3" type="radio" name="cn_naming" value="3"><span>' . esc_html__( 'Reject All, Accept Some, Accept All​', 'cookie-notice' ) . '</span></label>
												</div>
											</div>
											<div class="cn-field cn-field-checkbox">
												<label>' . esc_html__( 'Select basic consent options:', 'cookie-notice' ) . '</label>
												<div class="cn-checkbox-wrapper">
													<label for="cn_on_scroll"><input id="cn_on_scroll" type="checkbox" name="cn_on_scroll" value="1"><span>' . esc_html__( 'Consent on Scroll', 'cookie-notice' ) . '</span></label>
													<label for="cn_on_click"><input id="cn_on_click" type="checkbox" name="cn_on_click" value="1"><span>' . esc_html__( 'Consent on Click', 'cookie-notice' ) . '</span></label>
													<label for="cn_ui_blocking"><input id="cn_ui_blocking" type="checkbox" name="cn_ui_blocking" value="1"><span>' . esc_html__( 'UI Blocking', 'cookie-notice' ) . '</span></label>
													<label for="cn_revoke_consent"><input id="cn_revoke_consent" type="checkbox" name="cn_revoke_consent" value="1" checked><span>' . esc_html__( 'Revoke Consent', 'cookie-notice' ) . '</span></label>
												</div>
											</div>' . 
											// <div class="cn-small">* ' . esc_html__( 'available for Cookie Compliance&trade; Pro plans only', 'cookie-notice' ) . '</div>
										'</div>
									</div>
									<div class="cn-accordion-item cn-form-container cn-collapsed" tabindex="-1">
										<div class="cn-accordion-header cn-form-header"><button class="cn-accordion-button" type="button">' . esc_html__( 'Banner Design', 'cookie-notice' ) . '</button></div>
										<div class="cn-accordion-collapse cn-form">
											<div class="cn-form-feedback cn-hidden"></div>
											<div class="cn-field cn-field-radio-image">
												<label>' . esc_html__( 'Select your preferred display position', 'cookie-notice' ) . '​:</label>
												<div class="cn-radio-image-wrapper">
													<label for="cn_position_bottom"><input id="cn_position_bottom" type="radio" name="cn_position" value="bottom" title="' . esc_attr__( 'Bottom', 'cookie-notice' ) . '" checked><img src="' . esc_url( COOKIE_NOTICE_URL ) . '/img/layout-bottom.png" width="24" height="24"></label>
													<label for="cn_position_top"><input id="cn_position_top" type="radio" name="cn_position" value="top" title="' . esc_attr__( 'Top', 'cookie-notice' ) . '"><img src="' . esc_url( COOKIE_NOTICE_URL ) . '/img/layout-top.png" width="24" height="24"></label>
													<label for="cn_position_left"><input id="cn_position_left" type="radio" name="cn_position" value="left" title="' . esc_attr__( 'Left', 'cookie-notice' ) . '"><img src="' . esc_url( COOKIE_NOTICE_URL ) . '/img/layout-left.png" width="24" height="24"></label>
													<label for="cn_position_right"><input id="cn_position_right" type="radio" name="cn_position" value="right" title="' . esc_attr__( 'Right', 'cookie-notice' ) . '"><img src="' . esc_url( COOKIE_NOTICE_URL ) . '/img/layout-right.png" width="24" height="24"></label>
													<label for="cn_position_center"><input id="cn_position_center" type="radio" name="cn_position" value="center" title="' . esc_attr__( 'Center', 'cookie-notice' ) . '"><img src="' . esc_url( COOKIE_NOTICE_URL ) . '/img/layout-center.png" width="24" height="24"></label>
												</div>
											</div>
											<div class="cn-field cn-fieldset">
												<label>' . esc_html__( 'Adjust the banner color scheme', 'cookie-notice' ) . '​:</label>
												<div class="cn-checkbox-wrapper cn-color-picker-wrapper">
													<label for="cn_color_primary"><input id="cn_color_primary" class="cn-color-picker" type="checkbox" name="cn_color_primary" value="#20c19e"><span>' . esc_html__( 'Color of the buttons and interactive elements.', 'cookie-notice' ) . '</span></label>
													<label for="cn_color_background"><input id="cn_color_background" class="cn-color-picker" type="checkbox" name="cn_color_background" value="#ffffff"><span>' . esc_html__( 'Color of the banner background.', 'cookie-notice' ) . '</span></label>
													<label for="cn_color_text"><input id="cn_color_text" class="cn-color-picker" type="checkbox" name="cn_color_text" value="#434f58"><span>' . esc_html__( 'Color of the body text.', 'cookie-notice' ) . '</span></label>
													<label for="cn_color_border"><input id="cn_color_border" class="cn-color-picker" type="checkbox" name="cn_color_border" value="#5e6a74"><span>' . esc_html__( 'Color of the borders and inactive elements.', 'cookie-notice' ) . '</span></label>
													<label for="cn_color_heading"><input id="cn_color_heading" class="cn-color-picker" type="checkbox" name="cn_color_heading" value="#434f58"><span>' . esc_html__( 'Color of the heading text.', 'cookie-notice' ) . '</span></label>
													<label for="cn_color_button_text"><input id="cn_color_button_text" class="cn-color-picker" type="checkbox" name="cn_color_button_text" value="#ffffff"><span>' . esc_html__( 'Color of the button text.', 'cookie-notice' ) . '</span></label>
												</div>
											</div>' . 
											// <div class="cn-small">* ' . esc_html__( 'available for Cookie Compliance&trade; Pro plans only', 'cookie-notice' ) . '</div>
										'</div>
									</div>
								</div>
								<div class="cn-field cn-field-submit cn-nav">
									<button type="button" class="cn-btn cn-screen-button" data-screen="3"><span class="cn-spinner"></span>' . esc_html__( 'Apply Setup', 'cookie-notice' ) . '</button>
								</div>';

				$html .= wp_nonce_field( 'cn_api_configure', 'cn_nonce', true, false );

				$html .= '
							</form>
						</div>';
			} elseif ( $screen === 'register' ) {
				$html .= '
				<div class="cn-sidebar cn-sidebar-left has-loader">
					<div class="cn-inner">
						<div class="cn-header">
							<div class="cn-top-bar">
								<div class="cn-logo"><img src="' . esc_url( COOKIE_NOTICE_URL ) . '/img/cookie-compliance-logo.png" alt="Compliance by Hu-manity.co" /></div>
							</div>
						</div>
						<div class="cn-body">
							<h2>' . esc_html__( 'Compliance account', 'cookie-notice' ) . '</h2>
							<div class="cn-lead">
								<p>' . esc_html__( 'Create a Compliance by Hu-manity.co account and select your preferred plan.', 'cookie-notice' ) . '</p>
							</div>
							<div class="cn-accordion">
								<div id="cn-accordion-account" class="cn-accordion-item cn-form-container" tabindex="-1">
									<div class="cn-accordion-header cn-form-header"><button class="cn-accordion-button" type="button">1. ' . esc_html__( 'Create Account', 'cookie-notice' ) . '</button></div>
									<div class="cn-accordion-collapse">
										<form method="post" class="cn-form" action="" data-action="register">
											<div class="cn-form-feedback cn-hidden"></div>
											<div class="cn-field cn-field-text">
												<input type="text" name="email" value="" tabindex="1" placeholder="' . esc_attr__( 'Email address', 'cookie-notice' ) . '">
											</div>
											<div class="cn-field cn-field-text">
												<input type="password" name="pass" value="" tabindex="2" autocomplete="off" placeholder="' . esc_attr__( 'Password', 'cookie-notice' ) . '">
												<span>' . esc_html( 'Minimum eight characters, at least one capital letter and one number are required.', 'cookie-notice' ) . '</span>
											</div>
											<div class="cn-field cn-field-text">
												<input type="password" name="pass2" value="" tabindex="3" autocomplete="off" placeholder="' . esc_attr__( 'Confirm Password', 'cookie-notice' ) . '">
											</div>
											<div class="cn-field cn-field-checkbox">
												<div class="cn-checkbox-wrapper">
													<label for="cn_terms"><input id="cn_terms" type="checkbox" name="terms" value="1"><span>' . sprintf( esc_html__( 'I have read and agree to the %sTerms of Service%s', 'cookie-notice' ), '<a href="https://cookie-compliance.co/terms-of-service/?utm_campaign=accept-terms&utm_source=wordpress&utm_medium=link" target="_blank">', '</a>' ) . '</span></label>
												</div>
											</div>
											<div class="cn-field cn-field-submit cn-nav">
												<button type="submit" class="cn-btn cn-screen-button" tabindex="4" data-screen="4"><span class="cn-spinner"></span>' . esc_html__( 'Sign Up', 'cookie-notice' ) . '</button>
											</div>';

				// get site language
				$locale = get_locale();
				$locale_code = explode( '_', $locale );

				$html .= '
											<input type="hidden" name="language" value="' . esc_attr( $locale_code[0] ) . '" />';

				$html .= wp_nonce_field( 'cn_api_register', 'cn_nonce', true, false );

				$html .= '
										</form>
										<p>' . esc_html__( 'Already have an account?', 'cookie-notice' ) . ' <a href="#" class="cn-screen-button" data-screen="login">' . esc_html__( 'Sign in', 'cookie-notice' ). '</a></p>
									</div>
								</div>';

				$html .= '
								<div id="cn-accordion-billing" class="cn-accordion-item cn-form-container cn-collapsed cn-disabled" tabindex="-1">
									<div class="cn-accordion-header cn-form-header">
										<button class="cn-accordion-button" type="button">2. ' . esc_html__( 'Select Plan', 'cookie-notice' ) . '</button>
									</div>
									<form method="post" class="cn-accordion-collapse cn-form cn-form-disabled" action="" data-action="payment">
										<div class="cn-form-feedback cn-hidden"></div>
										<div class="cn-field cn-field-radio">
											<div class="cn-radio-wrapper cn-plan-wrapper">
												<label for="cn-field-plan-free" class="cn-pricing-plan-free"><input id="cn-field-plan-free" type="radio" name="plan" value="free" checked><span><span class="cn-plan-description">' . esc_html__( 'Basic', 'cookie-notice' ) . '</span><span class="cn-plan-pricing"><span class="cn-plan-price">Free</span></span><span class="cn-plan-overlay"></span></span></label>
												<label for="cn-field-plan-pro" class="cn-pricing-plan-pro"><input id="cn-field-plan-pro" type="radio" name="plan" value="compliance_monthly_notrial"><span><span class="cn-plan-description">' . sprintf( esc_html__( '%sProfessional%s', 'cookie-notice' ), '<b>', '</b>' ) . ' - <span class="cn-plan-period">' . esc_html__( 'monthly', 'cookie-notice' ) . '</span></span><span class="cn-plan-pricing"><span class="cn-plan-price">$<span class="cn-plan-amount">' . esc_attr( $this->pricing_monthly['compliance_monthly_notrial'] ) . '</span></span></span><span class="cn-plan-overlay"></span></span></label>
											</div>
										</div>
										<div class="cn-field cn-fieldset" id="cn_submit_free">
											<button type="submit" class="cn-btn cn-screen-button" tabindex="4" data-screen="4"><span class="cn-spinner"></span>' . esc_html__( 'Confirm', 'cookie-notice' ) . '</button>
										</div>
										<div class="cn-field cn-fieldset cn-hidden" id="cn_submit_pro">
											<input type="hidden" name="cn_payment_identifier" value="" />
											<div class="cn-field cn-field-radio">
												<label>' . esc_html__( 'Payment Method', 'cookie-notice' ) . '</label>
												<div class="cn-radio-wrapper cn-horizontal-wrapper">
													<label for="cn_field_method_credit_card"><input id="cn_field_method_credit_card" type="radio" name="method" value="credit_card" checked><span>' . esc_html__( 'Credit Card', 'cookie-notice' ) . '</span></label>
													<label for="cn_field_method_paypal"><input id="cn_field_method_paypal" type="radio" name="method" value="paypal"><span>' . esc_html__( 'PayPal', 'cookie-notice' ) . '</span></label>
												</div>
											</div>
											<div class="cn-fieldset" id="cn_payment_method_credit_card">
												<input type="hidden" name="payment_nonce" value="" />
												<div class="cn-field cn-field-text">
													<label for="cn_card_number">' . esc_html__( 'Card Number', 'cookie-notice' ) . '</label>
													<div id="cn_card_number"></div>
												</div>
												<div class="cn-field cn-field-text cn-field-half cn-field-first">
													<label for="cn_expiration_date">' . esc_html__( 'Expiration Date', 'cookie-notice' ) . '</label>
													<div id="cn_expiration_date"></div>
												</div>
												<div class="cn-field cn-field-text cn-field-half cn-field-last">
													<label for="cn_cvv">' . esc_html__( 'CVC/CVV', 'cookie-notice' ) . '</label>
													<div id="cn_cvv"></div>
												</div>
												<div class="cn-field cn-field-submit cn-nav">
													<button type="submit" class="cn-btn cn-screen-button" tabindex="4" data-screen="4"><span class="cn-spinner"></span>' . esc_html__( 'Submit', 'cookie-notice' ) . '</button>
												</div>
											</div>
											<div class="cn-fieldset" id="cn_payment_method_paypal" style="display: none">
												<div id="cn_paypal_button"></div>
											</div>
										</div>';

				$html .= wp_nonce_field( 'cn_api_payment', 'cn_payment_nonce', true, false );

				$html .= '
									</form>
								</div>
							</div>
						</div>';
			} elseif ( $screen === 'login' ) {
				$html .= '
				<div class="cn-sidebar cn-sidebar-left has-loader">
					<div class="cn-inner">
						<div class="cn-header">
							<div class="cn-top-bar">
								<div class="cn-logo"><img src="' . esc_url( COOKIE_NOTICE_URL ) . '/img/cookie-compliance-logo.png" alt="Compliance by Hu-manity.co" /></div>
							</div>
						</div>
						<div class="cn-body">
							<h2>' . esc_html__( 'Compliance Sign in', 'cookie-notice' ) . '</h2>
							<div class="cn-lead">
								<p>' . esc_html__( 'Sign in to your existing Compliance by Hu-manity.co account and select your preferred plan.', 'cookie-notice' ) . '</p>
							</div>
							<div class="cn-accordion">
								<div id="cn-accordion-account" class="cn-accordion-item cn-form-container" tabindex="-1">
									<div class="cn-accordion-header cn-form-header"><button class="cn-accordion-button" type="button">1. ' . esc_html__( 'Account Login', 'cookie-notice' ) . '</button></div>
									<div class="cn-accordion-collapse">
										<form method="post" class="cn-form" action="" data-action="login">
											<div class="cn-form-feedback cn-hidden"></div>
											<div class="cn-field cn-field-text">
												<input type="text" name="email" value="" tabindex="1" placeholder="' . esc_attr__( 'Email address', 'cookie-notice' ) . '">
											</div>
											<div class="cn-field cn-field-text">
												<input type="password" name="pass" value="" tabindex="2" autocomplete="off" placeholder="' . esc_attr__( 'Password', 'cookie-notice' ) . '">
											</div>
											<div class="cn-field cn-field-submit cn-nav">
												<button type="submit" class="cn-btn cn-screen-button" tabindex="4" ' . /* data-screen="4" */ '><span class="cn-spinner"></span>' . esc_html__( 'Sign in', 'cookie-notice' ) . '</button>
											</div>';

				// get site language
				$locale = get_locale();
				$locale_code = explode( '_', $locale );

				$html .= '
											<input type="hidden" name="language" value="' . esc_attr( $locale_code[0] ) . '" />';

				$html .= wp_nonce_field( 'cn_api_login', 'cn_nonce', true, false );

				$html .= '
										</form>
										<p>' . esc_html__( 'Don\'t have an account yet?', 'cookie-notice' ) . ' <a href="#" class="cn-screen-button" data-screen="register">' . esc_html__( 'Sign up', 'cookie-notice' ) . '</a></p>
									</div>
								</div>
								<div id="cn-accordion-billing" class="cn-accordion-item cn-form-container cn-collapsed cn-disabled" tabindex="-1">
									<div class="cn-accordion-header cn-form-header">
										<button class="cn-accordion-button" type="button">2. ' . esc_html__( 'Select Plan', 'cookie-notice' ) . '</button>
									</div>
									<form method="post" class="cn-accordion-collapse cn-form cn-form-disabled" action="" data-action="payment">
										<div class="cn-form-feedback cn-hidden"></div>
										<div class="cn-field cn-field-radio">
											<div class="cn-radio-wrapper cn-plan-wrapper">
												<label for="cn-field-plan-free" class="cn-pricing-plan-free"><input id="cn-field-plan-free" type="radio" name="plan" value="free" checked><span><span class="cn-plan-description">' . esc_html__( 'Basic', 'cookie-notice' ) . '</span><span class="cn-plan-pricing"><span class="cn-plan-price">Free</span></span><span class="cn-plan-overlay"></span></span></label>
												<label for="cn-field-plan-pro" class="cn-pricing-plan-pro"><input id="cn-field-plan-pro" type="radio" name="plan" value="compliance_monthly_notrial"><span><span class="cn-plan-description">' . sprintf( esc_html__( '%sProfessional%s', 'cookie-notice' ), '<b>', '</b>' ) . ' - <span class="cn-plan-period">' . esc_html__( 'monthly', 'cookie-notice' ) . '</span></span><span class="cn-plan-pricing"><span class="cn-plan-price">$<span class="cn-plan-amount">' . esc_attr( $this->pricing_monthly['compliance_monthly_notrial'] ) . '</span></span></span><span class="cn-plan-overlay"></span></span></label>
												<label for="cn-field-plan-license" class="cn-pricing-plan-license cn-disabled">
													<input id="cn-field-plan-license" type="radio" name="plan" value="license"><span><span class="cn-plan-description">' . esc_html__( 'Use License', 'cookie-notice' ) . '</span><span class="cn-plan-pricing"><span class="cn-plan-price"><span class="cn-plan-amount">0</span> ' . esc_html__( 'available', 'cookie-notice' ) . '</span></span><span class="cn-plan-overlay"></span></span>
												</label>
											</div>
										</div>
										<div class="cn-field cn-fieldset" id="cn_submit_free">
											<button type="submit" class="cn-btn cn-screen-button" tabindex="4" data-screen="4"><span class="cn-spinner"></span>' . esc_html__( 'Confirm', 'cookie-notice' ) . '</button>
										</div>
										<div class="cn-field cn-fieldset cn-hidden" id="cn_submit_pro">
											<input type="hidden" name="cn_payment_identifier" value="" />
											<div class="cn-field cn-field-radio">
												<label>' . esc_html__( 'Payment Method', 'cookie-notice' ) . '</label>
												<div class="cn-radio-wrapper cn-horizontal-wrapper">
													<label for="cn_field_method_credit_card"><input id="cn_field_method_credit_card" type="radio" name="method" value="credit_card" checked><span>' . esc_html__( 'Credit Card', 'cookie-notice' ) . '</span></label>
													<label for="cn_field_method_paypal"><input id="cn_field_method_paypal" type="radio" name="method" value="paypal"><span>' . esc_html__( 'PayPal', 'cookie-notice' ) . '</span></label>
												</div>
											</div>
											<div class="cn-fieldset" id="cn_payment_method_credit_card">
												<input type="hidden" name="payment_nonce" value="" />
												<div class="cn-field cn-field-text">
													<label for="cn_card_number">' . esc_html__( 'Card Number', 'cookie-notice' ) . '</label>
													<div id="cn_card_number"></div>
												</div>
												<div class="cn-field cn-field-text cn-field-half cn-field-first">
													<label for="cn_expiration_date">' . esc_html__( 'Expiration Date', 'cookie-notice' ) . '</label>
													<div id="cn_expiration_date"></div>
												</div>
												<div class="cn-field cn-field-text cn-field-half cn-field-last">
													<label for="cn_cvv">' . esc_html__( 'CVC/CVV', 'cookie-notice' ) . '</label>
													<div id="cn_cvv"></div>
												</div>
												<div class="cn-field cn-field-submit cn-nav">
													<button type="submit" class="cn-btn cn-screen-button" tabindex="4" data-screen="4"><span class="cn-spinner"></span>' . esc_html__( 'Submit', 'cookie-notice' ) . '</button>
												</div>
											</div>
											<div class="cn-fieldset" id="cn_payment_method_paypal" style="display: none">
												<div id="cn_paypal_button"></div>
											</div>
										</div>
										<div class="cn-field cn-fieldset cn-hidden" id="cn_submit_license">
											<div class="cn-field cn-field-select" id="cn-subscriptions-list">
												<label for="cn-subscription-select">' . esc_html__( 'Select subscription', 'cookie-notice' ) . '​</label>
												<select  name="cn_subscription_id" class="form-select" aria-label="' . esc_attr__( 'Licenses', 'cookie-notice' ) . '" id="cn-subscription-select">
												</select>
											</div><br>
											<button type="submit" class="cn-btn cn-screen-button" tabindex="4" data-screen="4"><span class="cn-spinner"></span>' . esc_html__( 'Confirm', 'cookie-notice' ) . '</button>
										</div>';

				$html .= wp_nonce_field( 'cn_api_payment', 'cn_payment_nonce', true, false );

				$html .= '
									</form>
								</div>
							</div>
						</div>';
			} elseif ( $screen === 'success' ) {
				$html .= '
				<div class="cn-sidebar cn-sidebar-left has-loader">
					<div class="cn-inner">
						<div class="cn-header">
							<div class="cn-top-bar">
								<div class="cn-logo"><img src="' . esc_url( COOKIE_NOTICE_URL ) . '/img/cookie-compliance-logo.png" alt="Compliance by Hu-manity.co" /></div>
							</div>
						</div>
						<div class="cn-body">
							<h2>' . esc_html__( 'Success!', 'cookie-notice' ) . '</h2>
							<div class="cn-lead"><p><b>' . esc_html__( 'You have successfully integrated your website with Compliance by Hu-manity.co.', 'cookie-notice' ) . '</b></p><p>' . sprintf( esc_html__( 'Go to Compliance by Hu-manity.co now. Or access it anytime from your %sCompliance settings page%s.', 'cookie-notice' ), '<a href="' . esc_url( Cookie_Notice()->is_network_admin() ? network_admin_url( 'admin.php?page=cookie-notice' ) : admin_url( 'admin.php?page=cookie-notice' ) ) . '">', '</a>' ) . '</p></div>
						</div>';
			}



			$html .= '
					<div class="cn-footer">';
			/*
			switch ( $screen ) {
				case 'about':
					$html .= '<a href="' . esc_url( admin_url( 'admin.php?page=cookie-notice' ) ) . '" class="cn-btn cn-btn-link cn-skip-button">' . __( 'Skip Live Setup', 'cookie-notice' ) . '</a>';
					break;
				case 'success':
					$html .= '<a href="' . esc_url( get_dashboard_url() ) . '" class="cn-btn cn-btn-link cn-skip-button">' . __( 'WordPress Dashboard', 'cookie-notice' ) . '</a>';
					break;
				default:
					$html .= '<a href="' . esc_url( admin_url( 'admin.php?page=cookie-notice' ) ) . '" class="cn-btn cn-btn-link cn-skip-button">' . __( 'Skip for now', 'cookie-notice' ) . '</a>';
					break;
			}
			*/
			$html .= '
					</div>
				</div>
			</div>';
		}

		if ( $echo ) {
			// get allowed html
			$allowed_html = wp_kses_allowed_html( 'post' );
			$allowed_html['div']['tabindex'] = true;
			$allowed_html['button']['tabindex'] = true;
			$allowed_html['iframe'] = [
				'id'	=> true,
				'src'	=> true
			];
			$allowed_html['form'] = [
				'id'			=> true,
				'class'			=> true,
				'action'		=> true,
				'data-action'	=> true
			];
			$allowed_html['select'] = [
				'name'			=> true,
				'class'			=> true,
				'id'			=> true,
				'aria-label'	=> true
			];
			$allowed_html['option'] = [
				'value'			=> true,
				'data-price'	=> true
			];
			$allowed_html['input'] = [
				'id'			=> true,
				'type'			=> true,
				'name'			=> true,
				'class'			=> true,
				'value'			=> true,
				'tabindex'		=> true,
				'autocomplete'	=> true,
				'checked'		=> true,
				'placeholder'	=> true,
				'title'			=> true
			];

			add_filter( 'safe_style_css', [ $this, 'allow_style_attributes' ] );

			// echo wp_kses( $html, $allowed_html );
			echo $html;

			remove_filter( 'safe_style_css', [ $this, 'allow_style_attributes' ] );
		} else
			return $html;

		if ( wp_doing_ajax() )
			exit();
	}


/** Function query_forms() called by wp_ajax hooks: {'cn_privacy_consent_get_forms'} **/
/** Parameters found in function query_forms(): {"request": ["action", "nonce", "source", "paged", "order", "orderby", "search"]} **/
function query_forms() {
		// valid nonce?
		if ( check_ajax_referer( 'cn-privacy-consent-list-table-nonce', 'nonce' ) === false )
			wp_send_json_error();

		// check data
		if ( ! isset( $_REQUEST['action'], $_REQUEST['nonce'], $_REQUEST['source'], $_REQUEST['paged'], $_REQUEST['order'], $_REQUEST['orderby'], $_REQUEST['search'] ) )
			wp_send_json_error();

		// check capability
		if ( ! current_user_can( apply_filters( 'cn_manage_cookie_notice_cap', 'manage_options' ) ) )
			wp_send_json_error();

		// sanitize data
		$source = sanitize_key( $_REQUEST['source'] );
		$order = sanitize_key( $_REQUEST['order'] );
		$orderby = sanitize_key( $_REQUEST['orderby'] );
		$search = trim( sanitize_text_field( wp_unslash( $_REQUEST['search'] ) ) );
		$page = (int) $_REQUEST['paged'];

		// validate order
		if ( ! in_array( $order, [ 'asc', 'desc' ], true ) )
			$order = 'asc';

		// validate orderby
		if ( ! in_array( $orderby, [ 'title', 'date' ], true ) )
			$orderby = 'title';

		if ( ! array_key_exists( $source, $this->sources ) || ! $this->sources[$source]['availability'] )
			wp_send_json_error();

		// initialize list table
		$list_table = new Cookie_Notice_Privacy_Consent_List_Table( [
			'plural'	=> 'cn-source-' . esc_attr( $this->sources[$source]['name'] ) . '-forms',
			'singular'	=> 'cn-source-' . esc_attr( $this->sources[$source]['name'] ) . '-form',
			'ajax'		=> true
		] );

		// set source
		$list_table->cn_set_source( $this->sources[$source] );

		$args = [
			'source'	=> $source,
			'order'		=> $order,
			'orderby'	=> $orderby,
			'page'		=> max( $page, $list_table->get_pagenum() ),
			'search'	=> $search
		];

		// set source forms
		$list_table->cn_set_forms( $this->instances[$source]->get_forms( $args ) );

		// handle ajax request
		$list_table->ajax_response();
	}


/** Function get_consent_logs() called by wp_ajax hooks: {'cn_react_consent_logs'} **/
/** Parameters found in function get_consent_logs(): {"post": ["page", "start_date", "end_date"]} **/
function get_consent_logs() {
		$this->verify_request();

		$page       = isset( $_POST['page'] ) ? max( 1, absint( $_POST['page'] ) ) : 1;
		$start_date = isset( $_POST['start_date'] ) ? sanitize_text_field( $_POST['start_date'] ) : date( 'Y-m-d' );
		$end_date   = isset( $_POST['end_date'] ) ? sanitize_text_field( $_POST['end_date'] ) : $start_date;
		$per_page   = 10;

		// Validate date formats (Y-m-d).
		$dt = DateTime::createFromFormat( 'Y-m-d', $start_date );
		if ( ! $dt || $dt->format( 'Y-m-d' ) !== $start_date ) {
			$start_date = date( 'Y-m-d' );
		}

		$dt_end = DateTime::createFromFormat( 'Y-m-d', $end_date );
		if ( ! $dt_end || $dt_end->format( 'Y-m-d' ) !== $end_date || $end_date < $start_date ) {
			$end_date = $start_date;
		}

		$cn = Cookie_Notice();

		// Server-side range cap — free = 7 days, pro = 90 days.
		$max_range = ( $cn->get_subscription() === 'pro' ) ? 90 : 7;
		$range     = (int) ( ( new DateTime( $end_date ) )->diff( new DateTime( $start_date ) )->days );

		if ( $range > $max_range ) {
			$end_date = ( new DateTime( $start_date ) )->modify( "+{$max_range} days" )->format( 'Y-m-d' );
		}

		$empty_breakdown = [ 'total' => 0, 'acceptRate' => 0, 'customRate' => 0, 'rejectRate' => 0, 'levelLabels' => $this->get_level_labels() ];

		// No app_id means not connected — return empty gracefully.
		if ( empty( $cn->options['general']['app_id'] ) ) {
			wp_send_json_success( [
				'logs'             => [],
				'total'            => 0,
				'page'             => $page,
				'totalPages'       => 0,
				'consentBreakdown' => $empty_breakdown,
			] );
			return;
		}

		// Single API call for the full date range (Transactional API handles range via EndDate).
		$raw = $cn->welcome_api->get_cookie_consent_logs( $start_date, $end_date );

		if ( ! is_array( $raw ) || empty( $raw ) ) {
			wp_send_json_success( [
				'logs'             => [],
				'total'            => 0,
				'page'             => $page,
				'totalPages'       => 0,
				'consentBreakdown' => $empty_breakdown,
			] );
			return;
		}

		// Transform raw API records into UI-ready log entries.
		$result = $this->transform_consent_logs( $raw, $cn );
		$logs   = $result['logs'];

		$total      = count( $logs );
		$total_pages = (int) ceil( $total / $per_page );
		$offset     = ( $page - 1 ) * $per_page;
		$paged      = array_slice( $logs, $offset, $per_page );

		wp_send_json_success( [
			'logs'             => $paged,
			'total'            => $total,
			'page'             => $page,
			'totalPages'       => $total_pages,
			'consentBreakdown' => $result['consent_breakdown'],
		] );
	}


/** Function dismiss_welcome() called by wp_ajax hooks: {'cn_dismiss_welcome', 'cn_react_dismiss_welcome'} **/
/** No params detected :-/ **/


/** Function api_request() called by wp_ajax hooks: {'cn_api_request'} **/
/** Parameters found in function api_request(): {"post": ["request", "cn_payment_nonce", "cn_nonce", "subscriptionID", "payment_nonce", "plan", "method", "cn_payment_identifier", "terms", "email", "pass", "pass2", "language"]} **/
function api_request() {
		// check capabilities
		if ( ! current_user_can( apply_filters( 'cn_manage_cookie_notice_cap', 'manage_options' ) ) )
			wp_die( __( 'You do not have permission to access this page.', 'cookie-notice' ) );

		// check main nonce
		if ( ! check_ajax_referer( 'cookie-notice-welcome', 'nonce' ) )
			wp_die( __( 'You do not have permission to access this page.', 'cookie-notice' ) );

		// get request
		$request = isset( $_POST['request'] ) ? sanitize_key( $_POST['request'] ) : '';

		// no valid request?
		if ( ! in_array( $request, [ 'register', 'login', 'configure', 'select_plan', 'payment', 'get_bt_init_token', 'use_license', 'sync_config' ], true ) )
			wp_die( __( 'You do not have permission to access this page.', 'cookie-notice' ) );

		$special_actions = [ 'register', 'login', 'configure', 'payment' ];

		// payment nonce
		if ( $request === 'payment' )
			$nonce = isset( $_POST['cn_payment_nonce'] ) ? sanitize_key( $_POST['cn_payment_nonce'] ) : '';
		// special nonce
		elseif ( in_array( $request, $special_actions, true ) )
			$nonce = isset( $_POST['cn_nonce'] ) ? sanitize_key( $_POST['cn_nonce'] ) : '';

		// check additional nonce
		if ( in_array( $request, $special_actions, true ) && ! wp_verify_nonce( $nonce, 'cn_api_' . $request ) )
			wp_die( __( 'You do not have permission to access this page.', 'cookie-notice' ) );

		$errors = [];
		$response = false;

		// get main instance
		$cn = Cookie_Notice();

		// get site language
		$locale = get_locale();
		$locale_code = explode( '_', $locale );

		// check network
		$network = $cn->is_network_admin();

		// get app token data
		if ( $network )
			$data_token = get_site_transient( 'cookie_notice_app_token' );
		else
			$data_token = get_transient( 'cookie_notice_app_token' );

		$admin_email = ! empty( $data_token->email ) ? $data_token->email : '';
		$app_id = $cn->options['general']['app_id'];

		$params = [];

		switch ( $request ) {
			case 'use_license':
				$subscriptionID = isset( $_POST['subscriptionID'] ) ? (int) $_POST['subscriptionID'] : 0;

				// security: validate subscriptionID is in the session allowlist set during login
				$allowed_subs = $network
					? get_site_transient( 'cookie_notice_app_subscriptions' )
					: get_transient( 'cookie_notice_app_subscriptions' );

				$allowed_ids = is_array( $allowed_subs ) ? array_column( $allowed_subs, 'subscriptionid' ) : [];

				if ( ! in_array( $subscriptionID, array_map( 'intval', $allowed_ids ), true ) ) {
					$response = [ 'error' => esc_html__( 'Invalid subscription.', 'cookie-notice' ) ];
					break;
				}

				$result = $this->request(
					'assign_subscription',
					[
						'AppID'				=> $app_id,
						'subscriptionID'	=> $subscriptionID
					]
				);

				// require an explicit success signal; anything else is an error
				if ( empty( $result->success ) || $result->success !== true ) {
					$response = [ 'error' => ! empty( $result->message ) ? $result->message : esc_html__( 'License assignment failed.', 'cookie-notice' ) ];
					break;
				}

				// update WP subscription tier to 'pro' (mirrors the payment case)
				$status_data = $cn->defaults['data'];

				if ( $network ) {
					$status_data = get_site_option( 'cookie_notice_status', $status_data );
					$status_data['subscription'] = 'pro';

					// get activation timestamp
					$timestamp = $cn->get_cc_activation_datetime();

					// update activation timestamp only for new cookie compliance activations
					$status_data['activation_datetime'] = $timestamp === 0 ? time() : $timestamp;

					update_site_option( 'cookie_notice_status', $status_data );
				} else {
					$status_data = get_option( 'cookie_notice_status', $status_data );
					$status_data['subscription'] = 'pro';

					// get activation timestamp
					$timestamp = $cn->get_cc_activation_datetime();

					// update activation timestamp only for new cookie compliance activations
					$status_data['activation_datetime'] = $timestamp === 0 ? time() : $timestamp;

					update_option( 'cookie_notice_status', $status_data );
				}

				// License assignment (use_license): do not CLEAR setup_wizard_complete on
				// existing sites — that would send already-configured domains back to
				// FirstRunSetup and make them appear as Free on reload (#1893).
				//
				// For brand-new domains the option was never written, so the wizard would
				// fire unnecessarily for existing subscribers assigning a new slot.
				// Set the flag only if it hasn't been set before — new domain case.
				if ( $network ) {
					if ( ! get_site_option( 'cookie_notice_setup_wizard_complete', false ) ) {
						update_site_option( 'cookie_notice_setup_wizard_complete', true );
					}
				} else {
					if ( ! get_option( 'cookie_notice_setup_wizard_complete', false ) ) {
						update_option( 'cookie_notice_setup_wizard_complete', true );
					}
				}

				$response = $result;

				break;

			case 'get_bt_init_token':
				$result = $this->request( 'get_token' );

				// is token available?
				if ( ! empty( $result->token ) )
					$response = [ 'token' => $result->token ];
				break;

			case 'payment':
				$error = [ 'error' => esc_html__( 'Unexpected error occurred. Please try again later.', 'cookie-notice' ) ];

				// empty data?
				if ( empty( $_POST['payment_nonce'] ) || empty( $_POST['plan'] ) || empty( $_POST['method'] ) ) {
					$response = $error;
					break;
				}

				// validate plan and payment method
				$available_plans = [
					'compliance_monthly_notrial',
					'compliance_monthly_5',
					'compliance_monthly_10',
					'compliance_monthly_20',
					'compliance_yearly_notrial',
					'compliance_yearly_5',
					'compliance_yearly_10',
					'compliance_yearly_20'
				];

				$available_payment_methods = [
					'credit_card',
					'paypal'
				];

				$plan = sanitize_key( $_POST['plan'] );

				if ( ! in_array( $_POST['plan'], $available_plans, true ) )
					$plan = false;

				$method = sanitize_key( $_POST['method'] );

				if ( ! in_array( $_POST['method'], $available_payment_methods, true ) )
					$method = false;

				// valid plan and payment method?
				if ( empty( $plan ) || empty( $method ) ) {
					$response = [ 'error' => esc_html__( 'Empty plan or payment method data.', 'cookie-notice' ) ];
					break;
				}

				$result = $this->request(
					'get_customer',
					[
						'AppID'		=> $app_id,
						'PlanId'	=> $plan
					]
				);

				// user found?
				if ( ! empty( $result->id ) ) {
					$customer = $result;
				// create user
				} else {
					$result = $this->request(
						'create_customer',
						[
							'AppID'					=> $app_id,
							'AdminID'				=> $admin_email, // remove later - AdminID from API response
							'PlanId'				=> $plan,
							'paymentMethodNonce'	=> sanitize_key( $_POST['payment_nonce'] )
						]
					);

					if ( ! empty( $result->success ) )
						$customer = $result->customer;
					else
						$customer = $result;
				}

				// user created/received?
				if ( empty( $customer->id ) ) {
					$response = [ 'error' => esc_html__( 'Unable to create customer data.', 'cookie-notice' ) ];
					break;
				}

				// selected payment method
				$payment_method = false;

				// get payment identifier (email or 4 digits)
				$identifier = isset( $_POST['cn_payment_identifier'] ) ? sanitize_text_field( $_POST['cn_payment_identifier'] ) : '';

				// customer available payment methods
				$payment_methods = ! empty( $customer->paymentMethods ) ? $customer->paymentMethods : [];

				// try to find payment method
				if ( ! empty( $payment_methods ) && is_array( $payment_methods ) ) {
					foreach ( $payment_methods as $pm ) {
						// paypal
						if ( isset( $pm->email ) && $pm->email === $identifier )
							$payment_method = $pm;
						// credit card
						elseif ( isset( $pm->last4 ) && $pm->last4 === $identifier )
							$payment_method = $pm;
					}
				}

				// if payment method was not identified, create it
				if ( ! $payment_method ) {
					$result = $this->request(
						'create_payment_method',
						[
							'AppID'					=> $app_id,
							'paymentMethodNonce'	=> sanitize_key( $_POST['payment_nonce'] )
						]
					);

					// payment method created successfully?
					if ( ! empty( $result->success ) ) {
						$payment_method = $result->paymentMethod;
					} else {
						$response = [ 'error' => esc_html__( 'Unable to create payment mehotd.', 'cookie-notice' ) ];
						break;
					}
				}

				if ( ! isset( $payment_method->token ) ) {
					$response = [ 'error' => esc_html__( 'No payment method token.', 'cookie-notice' ) ];
					break;
				}

				// @todo: check if subscription exists
				$subscription = $this->request(
					'create_subscription',
					[
						'AppID'					=> $app_id,
						'PlanId'				=> $plan,
						'paymentMethodToken'	=> $payment_method->token
					]
				);

				// subscription assigned?
				if ( ! empty( $subscription->error ) ) {
					$response = $subscription->error;
					break;
				}

				$status_data = $cn->defaults['data'];

				// update app status
				if ( $network ) {
					$status_data = get_site_option( 'cookie_notice_status', $status_data );
					$status_data['subscription'] = 'pro';

					// get activation timestamp
					$timestamp = $cn->get_cc_activation_datetime();

					// update activation timestamp only for new cookie compliance activations
					$status_data['activation_datetime'] = $timestamp === 0 ? time() : $timestamp;

					update_site_option( 'cookie_notice_status', $status_data );
				} else {
					$status_data = get_option( 'cookie_notice_status', $status_data );
					$status_data['subscription'] = 'pro';

					// get activation timestamp
					$timestamp = $cn->get_cc_activation_datetime();

					// update activation timestamp only for new cookie compliance activations
					$status_data['activation_datetime'] = $timestamp === 0 ? time() : $timestamp;

					update_option( 'cookie_notice_status', $status_data );
				}

				// Only show FirstRunSetup if the user has never completed it.
				// Free→Pro upgrades: the wizard was already done — don't clear the flag
				// or they'll see FirstRunSetup and remain appearing as Free on reload.
				// New activations (flag not set): leave it unset so the wizard fires.
				// (no-op: delete_option is intentionally removed for the upgrade path)

				$response = $app_id;
				break;

			case 'register':
				// check terms
				$terms = isset( $_POST['terms'] );

				// no terms?
				if ( ! $terms ) {
					$response = [ 'error' => esc_html__( 'Please accept the Terms of Service to proceed.', 'cookie-notice' ) ];
					break;
				}

				// check email
				$email = isset( $_POST['email'] ) ? is_email( $_POST['email'] ) : false;

				// empty email?
				if ( ! $email ) {
					$response = [ 'error' => esc_html__( 'Email is not allowed to be empty.', 'cookie-notice' ) ];
					break;
				}

				// check passwords
				$pass = ! empty( $_POST['pass'] ) ? stripslashes( $_POST['pass'] ) : '';
				$pass2 = ! empty( $_POST['pass2'] ) ? stripslashes( $_POST['pass2'] ) : '';

				// empty password?
				if ( ! $pass || ! is_string( $pass ) ) {
					$response = [ 'error' => esc_html__( 'Password is not allowed to be empty.', 'cookie-notice' ) ];
					break;
				}

				// invalid password?
				if ( preg_match( '/^(?=.*[A-Z])(?=.*\d)[\w !"#$%&\'()*\+,\-.\/:;<=>?@\[\]^\`\{\|\}\~\\\\]{8,}$/', $pass ) !== 1 ) {
					$response = [ 'error' => esc_html__( 'The password contains illegal characters or does not meet the conditions.', 'cookie-notice' ) ];
					break;
				}

				// no match?
				if ( $pass !== $pass2 ) {
					$response = [ 'error' => esc_html__( 'Passwords do not match.', 'cookie-notice' ) ];
					break;
				}

				$params = [
					'AdminID'	=> $email,
					'Password'	=> $pass,
					'Language'	=> ! empty( $_POST['language'] ) ? sanitize_key( $_POST['language'] ) : 'en'
				];

				$response = $this->request( 'register', $params );

				// errors?
				if ( ! empty( $response->error ) )
					break;

				// errors?
				if ( ! empty( $response->message ) ) {
					// normalize duplicate-email to machine-readable key for React recovery UI
					if ( ! empty( $response->i18n_msg ) && strpos( $response->i18n_msg, 'api_account_status_' ) === 0 )
						$response = [ 'error' => 'email_exists' ];
					else
						$response->error = $response->message;

					break;
				}

				// ok, so log in now
				$params = [
					'AdminID'	=> $email,
					'Password'	=> $pass
				];

				$response = $this->request( 'login', $params );

				// errors?
				if ( ! empty( $response->error ) )
					break;

				// errors?
				if ( ! empty( $response->message ) ) {
					$response->error = $response->message;
					break;
				}

				// token in response?
				if ( empty( $response->data->token ) ) {
					$response = [ 'error' => esc_html__( 'Unexpected error occurred. Please try again later.', 'cookie-notice' ) ];
					break;
				}

				// set token
				if ( $network )
					set_site_transient( 'cookie_notice_app_token', $response->data, DAY_IN_SECONDS );
				else
					set_transient( 'cookie_notice_app_token', $response->data, DAY_IN_SECONDS );

				// multisite?
				if ( is_multisite() ) {
					switch_to_blog( 1 );
					$site_title = get_bloginfo( 'name' );
					$site_url = network_site_url();
					$site_description = get_bloginfo( 'description' );
					restore_current_blog();
				} else {
					$site_title = get_bloginfo( 'name' );
					$site_url = get_home_url();
					$site_description = get_bloginfo( 'description' );
				}

				// create new app, no need to check existing
				$params = [
					'DomainName'	=> $site_title,
					'DomainUrl'		=> $site_url
				];

				if ( ! empty( $site_description ) )
					$params['DomainDescription'] = $site_description;

				$response = $this->request( 'app_create', $params );

				// If domain already registered, fetch existing app via list_apps and reuse it.
				if ( ! empty( $response->i18n_msg ) && $response->i18n_msg === 'domain_url_already_exist' ) {
					$list_response = $this->request( 'list_apps' );

					$existing_app = null;
					$site_normalized = strtolower( preg_replace( '/^www\./', '', trim( str_replace( [ 'http://', 'https://' ], '', $site_url ), '/' ) ) );

					if ( ! empty( $list_response->data ) && is_array( $list_response->data ) ) {
						foreach ( $list_response->data as $app ) {
							$app_normalized = strtolower( preg_replace( '/^www\./', '', trim( str_replace( [ 'http://', 'https://' ], '', $app->DomainUrl ?? '' ), '/' ) ) );
							if ( $app_normalized === $site_normalized ) {
								$existing_app = $app;
								break;
							}
						}
					}

					if ( ! empty( $existing_app->AppID ) && ! empty( $existing_app->SecretKey ) ) {
						$response = (object) [ 'data' => $existing_app ];
					} else {
						$response->error = $response->message;
						break;
					}
				}

				// errors?
				if ( ! empty( $response->error ) || ( ! empty( $response->message ) && empty( $response->data ) ) ) {
					if ( empty( $response->error ) ) $response->error = $response->message;
					break;
				}

				// data in response?
				if ( empty( $response->data->AppID ) || empty( $response->data->SecretKey ) ) {
					$response = [ 'error' => esc_html__( 'Unexpected error occurred. Please try again later.', 'cookie-notice' ) ];
					break;
				} else {
					$app_id = $response->data->AppID;
					$secret_key = $response->data->SecretKey;
				}

				// update options: app id and secret key
				$cn->options['general'] = wp_parse_args( [ 'app_id' => $app_id, 'app_key' => $secret_key ], $cn->options['general'] );

				if ( $network ) {
					$cn->options['general']['global_override'] = true;

					update_site_option( 'cookie_notice_options', $cn->options['general'] );

					// get options
					$app_config = get_site_transient( 'cookie_notice_app_quick_config' );
				} else {
					update_option( 'cookie_notice_options', $cn->options['general'] );

					// get options
					$app_config = get_transient( 'cookie_notice_app_quick_config' );
				}

				// create quick config
				$params = ! empty( $app_config ) && is_array( $app_config ) ? $app_config : [];

				// cast to objects
				if ( $params ) {
					$new_params = [];

					foreach ( $params as $key => $array ) {
						$object = new stdClass();

						foreach ( $array as $subkey => $value ) {
							$new_params[$key] = $object;
							$new_params[$key]->{$subkey} = $value;
						}
					}

					$params = $new_params;
				}

				$params['AppID'] = $app_id;

				// @todo When mutliple default languages are supported
				$params['DefaultLanguage'] = 'en';

				if ( ! array_key_exists( 'text', $params ) )
					$params['text'] = new stdClass();

				// add privacy policy url
				$params['text']->privacyPolicyUrl = get_privacy_policy_url();

				// add translations if needed
				if ( $locale_code[0] !== 'en' )
					$params['Languages'] = [ $locale_code[0] ];

				$response = $this->request( 'quick_config', $params );
				$status_data = $cn->defaults['data'];

				if ( $response->status === 200 ) {
					// notify publish app
					$params = [
						'AppID'	=> $app_id
					];

					$response = $this->request( 'notify_app', $params );

					if ( $response->status === 200 ) {
						$response = true;
						$status_data['status'] = 'active';
						$status_data['activation_datetime'] = time();

						// update app status
						if ( $network )
							update_site_option( 'cookie_notice_status', $status_data );
						else
							update_option( 'cookie_notice_status', $status_data );

						// Auto-populate tracker/blocking config from Designer API (#2130).
						$this->get_app_config( $app_id, true, true );
					} else {
						$status_data['status'] = 'pending';

						// update app status
						if ( $network )
							update_site_option( 'cookie_notice_status', $status_data );
						else
							update_option( 'cookie_notice_status', $status_data );

						// errors?
						if ( ! empty( $response->error ) )
							break;

						// errors?
						if ( ! empty( $response->message ) ) {
							$response->error = $response->message;
							break;
						}
					}
				} else {
					$status_data['status'] = 'pending';

					// update app status
					if ( $network )
						update_site_option( 'cookie_notice_status', $status_data );
					else
						update_option( 'cookie_notice_status', $status_data );

					// errors?
					if ( ! empty( $response->error ) ) {
						$response->error = $response->error;
						break;
					}

					// errors?
					if ( ! empty( $response->message ) ) {
						$response->error = $response->message;
						break;
					}
				}

				break;

			case 'login':
				// check email
				$email = isset( $_POST['email'] ) ? is_email( $_POST['email'] ) : false;

				// invalid email?
				if ( ! $email ) {
					$response = [ 'error' => esc_html__( 'Email is not allowed to be empty.', 'cookie-notice' ) ];
					break;
				}

				// check password
				$pass = ! empty( $_POST['pass'] ) ? preg_replace( '/[^\w !"#$%&\'()*\+,\-.\/:;<=>?@\[\]^\`\{\|\}\~\\\\]/', '', $_POST['pass'] ) : '';

				// empty password?
				if ( ! $pass ) {
					$response = [ 'error' => esc_html__( 'Password is not allowed to be empty.', 'cookie-notice' ) ];
					break;
				}

				$params = [
					'AdminID'	=> $email,
					'Password'	=> $pass
				];

				$response = $this->request( $request, $params );

				// errors?
				if ( ! empty( $response->error ) )
					break;

				// errors?
				if ( ! empty( $response->message ) ) {
					$response->error = $response->message;
					break;
				}

				// token in response?
				if ( empty( $response->data->token ) ) {
					$response = [ 'error' => esc_html__( 'Unexpected error occurred. Please try again later.', 'cookie-notice' ) ];
					break;
				}

				// set token
				if ( $network )
					set_site_transient( 'cookie_notice_app_token', $response->data, DAY_IN_SECONDS );
				else
					set_transient( 'cookie_notice_app_token', $response->data, DAY_IN_SECONDS );

				// get apps and check if one for the current domain already exists
				$response = $this->request( 'list_apps', [] );

				// errors?
				if ( ! empty( $response->message ) ) {
					$response->error = $response->message;
					break;
				}

				$apps_list = [];
				$app_exists = false;

				// multisite?
				if ( is_multisite() ) {
					switch_to_blog( 1 );
					$site_title = get_bloginfo( 'name' );
					$site_url = network_site_url();
					$site_description = get_bloginfo( 'description' );
					restore_current_blog();
				} else {
					$site_title = get_bloginfo( 'name' );
					$site_url = get_home_url();
					$site_description = get_bloginfo( 'description' );
				}

				// apps added, check if current one exists
				if ( ! empty( $response->data ) ) {
					$apps_list = (array) $response->data;

					// normalize site URL once before the loop: lowercase, strip protocol, strip www, strip trailing slash
					$site_normalized = strtolower( preg_replace( '/^www\./', '', trim( str_replace( [ 'http://', 'https://' ], '', $site_url ), '/' ) ) );

					foreach ( $apps_list as $index => $app ) {
						$app_domain = strtolower( preg_replace( '/^www\./', '', trim( str_replace( [ 'http://', 'https://' ], '', $app->DomainUrl ), '/' ) ) );

						if ( $app_domain === $site_normalized ) {
							$app_exists = $app;

							break;
						}
					}
				}

				// track whether this domain already existed before login
				$app_was_preexisting = (bool) $app_exists;

				// if no app, create one
				if ( ! $app_exists ) {
					// create new app
					$params = [
						'DomainName'	=> $site_title,
						'DomainUrl'		=> $site_url,
					];

					if ( ! empty( $site_description ) )
						$params['DomainDescription'] = $site_description;

					$response = $this->request( 'app_create', $params );

					// errors?
					if ( ! empty( $response->message ) ) {
						$response->error = $response->message;
						break;
					}

					$app_exists = $response->data;
				}

				// check if we have the valid app data
				if ( empty( $app_exists->AppID ) || empty( $app_exists->SecretKey ) ) {
					$response = [ 'error' => esc_html__( 'Unexpected error occurred. Please try again later.', 'cookie-notice' ) ];
					break;
				}

				// get subscriptions
				$subscriptions = [];

				$params = [
					'AppID' => $app_exists->AppID
				];

				$response = $this->request( 'get_subscriptions', $params );

				// errors?
				if ( ! empty( $response->error ) ) {
					$response->error = $response->error;
					break;
				} else
					$subscriptions = map_deep( (array) $response->data, [ $this, 'sanitize_preserve_bools' ] );

				// set subscriptions data
				if ( $network )
					set_site_transient( 'cookie_notice_app_subscriptions', $subscriptions, DAY_IN_SECONDS );
				else
					set_transient( 'cookie_notice_app_subscriptions', $subscriptions, DAY_IN_SECONDS );

				// determine subscription tier:
				// - pre-existing domain: preserve its current tier from WP options (Designer API is authoritative)
				//   availablelicense reflects account-level available slots, NOT this domain's plan
				//   If WP options were cleared (e.g. reset), fall back to API-side SubscriptionType
				// - brand-new domain: always starts as 'basic' (free by default, payment upgrades it)
				if ( $app_was_preexisting ) {
					$existing_status = $network
						? get_site_option( 'cookie_notice_status', $cn->defaults['data'] )
						: get_option( 'cookie_notice_status', $cn->defaults['data'] );

					$subscription_tier = ! empty( $existing_status['subscription'] ) && in_array( $existing_status['subscription'], [ 'basic', 'pro' ], true )
						? $existing_status['subscription']
						: 'basic';

					// WP options cleared but API knows the domain has a subscription — derive tier from API
					if ( $subscription_tier === 'basic' && ! empty( $app_exists->SubscriptionID ) ) {
						$subscription_tier = 'pro';
					}
				} else {
					$subscription_tier = 'basic';
				}

				// update options: app ID and secret key
				$cn->options['general'] = wp_parse_args( [ 'app_id' => $app_exists->AppID, 'app_key' => $app_exists->SecretKey ], $cn->options['general'] );

				if ( $network ) {
					$cn->options['general']['global_override'] = true;

					update_site_option( 'cookie_notice_options', $cn->options['general'] );
				} else {
					update_option( 'cookie_notice_options', $cn->options['general'] );
				}

				// Pre-existing domains already have their configuration in the Designer API.
				// Only call quick_config for new domains to avoid overwriting existing
				// regulations and settings with defaults.
				$status_data = $cn->defaults['data'];
				$status_data['subscription'] = $subscription_tier;

				if ( ! $app_was_preexisting ) {
					// Apply pre-configure settings from transient (mirrors register flow).
					// Transient is set by the configure wizard when the user hasn't yet connected.
					$app_config = $network ? get_site_transient( 'cookie_notice_app_quick_config' ) : get_transient( 'cookie_notice_app_quick_config' );

					// create quick config
					$params = ! empty( $app_config ) && is_array( $app_config ) ? $app_config : [];

					// cast arrays to objects
					if ( $params ) {
						$new_params = [];

						foreach ( $params as $key => $array ) {
							$object = new stdClass();

							foreach ( $array as $subkey => $value ) {
								$new_params[$key] = $object;
								$new_params[$key]->{$subkey} = $value;
							}
						}

						$params = $new_params;
					}

					$params['AppID']           = $app_exists->AppID;
					$params['DefaultLanguage'] = 'en';

					if ( ! array_key_exists( 'text', $params ) )
						$params['text'] = new stdClass();

					// add privacy policy url
					$params['text']->privacyPolicyUrl = get_privacy_policy_url();

					// add translations if needed
					if ( $locale_code[0] !== 'en' )
						$params['Languages'] = [ $locale_code[0] ];

					$response = $this->request( 'quick_config', $params );

					if ( $response->status !== 200 ) {
						$status_data['status'] = 'pending';

						// update app status
						if ( $network )
							update_site_option( 'cookie_notice_status', $status_data );
						else
							update_option( 'cookie_notice_status', $status_data );

						// errors?
						if ( ! empty( $response->error ) )
							break;

						// errors?
						if ( ! empty( $response->message ) ) {
							$response->error = $response->message;
							break;
						}
					}
				}

				// Notify / activate the app (both new and pre-existing domains)
				$params = [
					'AppID' => $app_exists->AppID
				];

				$response = $this->request( 'notify_app', $params );

				// Idempotent: "App was already active" means the API app record is already Active
				// (StatusID != Inactive). This happens when WP options were cleared but the API-side
				// app persists from a prior login. Treat it as success — the app IS active.
				$notify_already_active = ! empty( $response->message )
					&& strpos( $response->message, 'already active' ) !== false;

				if ( $response->status === 200 || $notify_already_active ) {
					$response = true;
					$status_data['status'] = 'active';

					// get activation timestamp
					$timestamp = $cn->get_cc_activation_datetime();

					// update activation timestamp only for new cookie compliance activations
					$status_data['activation_datetime'] = $timestamp === 0 ? time() : $timestamp;

					// update app status
					if ( $network )
						update_site_option( 'cookie_notice_status', $status_data );
					else
						update_option( 'cookie_notice_status', $status_data );

					// Sync config from Designer API for all domains (new + pre-existing)
					// so the Protection tab shows current tracker data (#2130, #2186).
					$this->get_app_config( $app_exists->AppID, true, true );
				} else {
					$status_data['status'] = 'pending';

					// update app status
					if ( $network )
						update_site_option( 'cookie_notice_status', $status_data );
					else
						update_option( 'cookie_notice_status', $status_data );

					// errors?
					if ( ! empty( $response->error ) )
						break;

					// errors?
					if ( ! empty( $response->message ) ) {
						$response->error = $response->message;
						break;
					}
				}

				// all ok, return subscriptions + fresh nonce
				// A fresh nonce is generated here (after authentication completes) so React
				// can use it for subsequent AJAX calls (e.g. use_license). The welcomeNonce
				// in cnReactData was generated at page load, before login state changed —
				// WP nonces are seeded by user identity so the original may no longer verify.
				$response = (object) [];
				$response->subscriptions = $subscriptions;
				$response->fresh_nonce   = wp_create_nonce( 'cookie-notice-welcome' );

				// Tell React whether this domain already has a subscription assigned
				// so it can skip the LicenseSelectStep for already-subscribed domains.
				$response->app_has_subscription = $app_was_preexisting && ! empty( $app_exists->SubscriptionID );
				break;

			case 'configure':
				$fields = [
					'cn_position',
					'cn_color_primary',
					'cn_color_background',
					'cn_color_border',
					'cn_color_text',
					'cn_color_heading',
					'cn_color_button_text',
					'cn_laws',
					'cn_naming',
					'cn_on_scroll',
					'cn_on_click',
					'cn_ui_blocking',
					'cn_revoke_consent'
				];

				$options = [];

				// loop through potential config form fields
				foreach ( $fields as $field ) {
					switch ( $field ) {
						case 'cn_position':
							// sanitize position
							$position = isset( $_POST[$field] ) ? sanitize_key( $_POST[$field] ) : '';

							// valid position? Only include if explicitly provided — omitting lets
							// patch_by_app deep-merge preserve the portal's current value (#ISSUE-1).
							if ( in_array( $position, [ 'bottom', 'top', 'left', 'right', 'center' ], true ) )
								$options['design']['position'] = $position;
							break;

						case 'cn_color_primary':
							$color = isset( $_POST[$field] ) ? sanitize_hex_color( $_POST[$field] ) : '';

							if ( ! empty( $color ) )
								$options['design']['primaryColor'] = $color;
							break;

						case 'cn_color_background':
							$color = isset( $_POST[$field] ) ? sanitize_hex_color( $_POST[$field] ) : '';

							if ( ! empty( $color ) )
								$options['design']['bannerColor'] = $color;
							break;

						case 'cn_color_border':
							$color = isset( $_POST[$field] ) ? sanitize_hex_color( $_POST[$field] ) : '';

							if ( ! empty( $color ) )
								$options['design']['borderColor'] = $color;
							break;

						case 'cn_color_text':
							$color = isset( $_POST[$field] ) ? sanitize_hex_color( $_POST[$field] ) : '';

							if ( ! empty( $color ) )
								$options['design']['textColor'] = $color;
							break;

						case 'cn_color_heading':
							$color = isset( $_POST[$field] ) ? sanitize_hex_color( $_POST[$field] ) : '';

							if ( ! empty( $color ) )
								$options['design']['headingColor'] = $color;
							break;

						case 'cn_color_button_text':
							$color = isset( $_POST[$field] ) ? sanitize_hex_color( $_POST[$field] ) : '';

							if ( ! empty( $color ) )
								$options['design']['btnTextColor'] = $color;
							break;

						case 'cn_laws':
							$new_options = [];

							// any data?
							if ( ! empty( $_POST[$field] ) && is_array( $_POST[$field] ) ) {
								$options['regulations'] = array_map( 'sanitize_text_field', $_POST[$field] );

								foreach ( $options['regulations'] as $law ) {
									if ( in_array( $law, [ 'gdpr', 'ccpa', 'otherus', 'ukpecr', 'lgpd', 'pipeda', 'popia' ], true ) )
										$new_options[$law] = true;
								}
							}

							$options['regulations'] = $new_options;

							// Persist selected law keys to a dedicated WP option so
							// get_dashboard() and cnReactData can expose them to the
							// Protection tab LAWS card without a Designer API round-trip.
							// (#1897 — LAWS card always showed "No laws selected")
							$saved_law_keys = array_keys( $new_options );
							if ( $network )
								update_site_option( 'cookie_notice_app_regulations', $saved_law_keys );
							else
								update_option( 'cookie_notice_app_regulations', $saved_law_keys );

							// GDPR & others
							$options['config']['privacyPolicyLink'] = true;

							// CCPA & Other US
							if ( array_key_exists( 'ccpa', $options['regulations'] ) || array_key_exists( 'otherus', $options['regulations'] ) )
								$options['config']['dontSellLink'] = true;
							else
								$options['config']['dontSellLink'] = false;

							// geolocationRules is intentionally NOT written here (OBS-28).
							// Per-jurisdiction geolocation rules are owned by the Admin Portal.
							// The plugin's flat law selection deliberately does not drive
							// geolocationRules: the by-app PATCH endpoint deep-merges and
							// preserves the stored (portal-tuned) rules when this key is
							// omitted (Designer API userDesign.controller.ts merge + noDefaults
							// schema). Writing a hardcoded matrix here previously clobbered
							// Admin-Portal-tuned per-jurisdiction blocking rules on every law save.

							// ── Auto-set compliance settings based on selected laws (#2143) ──────────
							//
							// Opt-in consent laws (GDPR, UKPECR, LGPD, POPIA) require prior explicit
							// consent — implied consent via scroll/click/close is not valid under any
							// of these frameworks. Apply the strictest safe defaults when any are selected.
							$opt_in_laws  = [ 'gdpr', 'ukpecr', 'lgpd', 'popia' ];
							$has_opt_in   = ! empty( array_intersect( array_keys( $options['regulations'] ), $opt_in_laws ) );
							$has_ccpa_us  = array_key_exists( 'ccpa', $options['regulations'] ) || array_key_exists( 'otherus', $options['regulations'] );
							$has_pipeda   = array_key_exists( 'pipeda', $options['regulations'] );

							// Designer API config keys — sent via the existing patch_by_app PATCH call below.
							if ( $has_opt_in ) {
								// Scroll/click/close are not valid consent signals under GDPR, UKPECR, LGPD, POPIA.
								$options['config']['onScroll']      = false;
								$options['config']['onClick']       = false;
								// onClose: net-new key — no cn_on_close handler exists; written directly to config.
								$options['config']['onClose']       = false;
								$options['config']['revokeConsent'] = true;
							}

							// GDPR only: cookie walls (uiBlocking) are non-compliant per EDPB guidance.
							if ( array_key_exists( 'gdpr', $options['regulations'] ) ) {
								$options['config']['uiBlocking'] = false;
							}

							// CCPA/OTHERUS: CPRA mandates honoring GPC browser signals.
							// gpcSupportMode is Pro-gated with grandfather (see KnowledgeHub
							// decisions.md gpc-pro-gating-with-grandfather). Auto-set fires
							// only when the site can actually enable GPC — Pro tier OR an app
							// that already has gpcSupportMode=true persisted (grandfathered).
							// For Free non-grandfathered + CCPA, the existing 'crit' red state
							// in ComplianceBehavior.jsx surfaces the compliance gap and the
							// upgrade CTA points the customer to Pro.
							if ( $has_ccpa_us ) {
								$existing_blocking = $network
									? get_site_option( 'cookie_notice_app_blocking', [] )
									: get_option( 'cookie_notice_app_blocking', [] );
								$existing_gpc = ! empty( $existing_blocking['banner_config']['gpcSupportMode'] );
								$is_pro       = $cn->get_subscription() === 'pro';

								if ( $is_pro || $existing_gpc ) {
									$options['config']['gpcSupportMode'] = true;
									// gpcBannerMode = 'passive' surfaces a brief, non-blocking notice
									// when GPC is honored. Set explicitly so legacy apps whose
									// persisted value is the old 'banner' default get reset.
									$options['config']['gpcBannerMode'] = 'passive';
								}
							}

							// PIPEDA: express consent requires ability to revoke — send revokeConsent to Designer API
							// to match the WP-side revoke_cookies=true set below (#2146).
							if ( $has_pipeda ) {
								$options['config']['revokeConsent'] = true;
							}

							// ── WP-side options (cookie_notice_options) ─────────────────────────────
							// The configure case does not normally touch WP options — this is new.
							// Use $cn->options['general'] (already loaded + merged with defaults)
							// to avoid clobbering multi_array_merge'd sub-keys.
							if ( $has_opt_in || $has_ccpa_us || $has_pipeda ) {
								$wp_options = $cn->options['general'];

								if ( $has_opt_in ) {
									// Disable implied consent toggles; enable refuse + revoke + policy link.
									$wp_options['on_scroll']      = false;
									$wp_options['on_click']       = false;
									$wp_options['refuse_opt']     = true;
									$wp_options['revoke_cookies'] = true;
									$wp_options['see_more']       = true;
									// Cap cookie expiry to max allowed: 12–13 months (GDPR/UKPECR EDPB guidance).
									$wp_options['time']           = 'year';
									$wp_options['time_rejected']  = '6months';
								} elseif ( $has_ccpa_us || $has_pipeda ) {
									// Opt-out / express-consent laws: revoke + privacy link minimum.
									$wp_options['revoke_cookies'] = true;
									$wp_options['see_more']       = true;
									// PIPEDA also requires a refuse option (express consent implies ability to decline).
									if ( $has_pipeda )
										$wp_options['refuse_opt'] = true;
								}

								if ( $network )
									update_site_option( 'cookie_notice_options', $wp_options );
								else
									update_option( 'cookie_notice_options', $wp_options );
							}
							// ── End auto-set compliance settings (#2143) ─────────────────────────

							break;

						case 'cn_naming':
							if ( ! isset( $_POST[$field] ) )
								break;

							$naming = (int) $_POST[$field];
							$naming = in_array( $naming, [ 1, 2, 3 ] ) ? $naming : 1;

							// english only for now
							$level_names = [
								1 => [
									1 => 'Private',
									2 => 'Balanced',
									3 => 'Personalized'
								],
								2 => [
									1 => 'Silver',
									2 => 'Gold',
									3 => 'Platinum'
								],
								3 => [
									1 => 'Reject All',
									2 => 'Accept Some',
									3 => 'Accept All'
								]
							];

							$options['text'] = [
								'levelNameText_1'	=> $level_names[$naming][1],
								'levelNameText_2'	=> $level_names[$naming][2],
								'levelNameText_3'	=> $level_names[$naming][3]
							];
							break;

						case 'cn_on_scroll':
							if ( isset( $_POST[$field] ) )
								$options['config']['onScroll'] = true;
							break;

						case 'cn_on_click':
							if ( isset( $_POST[$field] ) )
								$options['config']['onClick'] = true;
							break;

						case 'cn_ui_blocking':
							if ( isset( $_POST[$field] ) )
								$options['config']['uiBlocking'] = true;
							break;
						
						case 'cn_revoke_consent':
							$options['config']['revokeConsent'] = isset( $_POST[$field] );
							break;
					}
				}

				// Normalise regulations: move into config with explicit false for
				// every deselected law.  Both patch_by_app (mergeWith deep-merge)
				// and quick_config (dto.config?.regulations) read it from config.
				// Top-level regulations is kept in the quick schema for backward
				// compat with legacy callers, but new code only sends via config.
				$all_laws = [ 'gdpr', 'ccpa', 'otherus', 'ukpecr', 'lgpd', 'pipeda', 'popia' ];
				$selected = isset( $options['regulations'] ) ? $options['regulations'] : [];
				$full_regs = [];
				foreach ( $all_laws as $law ) {
					$full_regs[ $law ] = ! empty( $selected[ $law ] );
				}
				$options['config']['regulations'] = $full_regs;
				unset( $options['regulations'] );

				// set options
				if ( $network )
					set_site_transient( 'cookie_notice_app_quick_config', $options, DAY_IN_SECONDS );
				else
					set_transient( 'cookie_notice_app_quick_config', $options, DAY_IN_SECONDS );

				// For connected apps: PATCH the Designer API immediately (#1913 — #1917).
				// The transient is retained for the register/login initial-creation path.
				// DevMode mock IDs are skipped — get_write_request_type() returns 'devmode'.
				if ( ! empty( $app_id ) ) {
					$write_type = $this->get_write_request_type( $app_id );

					if ( $write_type !== 'devmode' ) {
						// Cast transient arrays to stdClass objects for JSON encoding.
						$patch_params = [ 'AppID' => $app_id ];

						foreach ( $options as $key => $value ) {
							if ( is_array( $value ) ) {
								$obj = new stdClass();
								foreach ( $value as $sub_key => $sub_val ) {
									$obj->{$sub_key} = $sub_val;
								}
								$patch_params[ $key ] = $obj;
							} else {
								$patch_params[ $key ] = $value;
							}
						}

						$patch_result = $this->request( 'patch_by_app', $patch_params );

						// Design record not yet created — fall back to quick_config to seed it.
						// The API returns { i18n_msg: 'user_design_update_id_not_found', status: 400 } (HTTP 200)
						// when no record exists, so check i18n_msg — not statusCode/404.
						// Also restore DefaultLanguage which patch_by_app doesn't accept but quick_config requires.
						if ( is_object( $patch_result ) && isset( $patch_result->i18n_msg ) && $patch_result->i18n_msg === 'user_design_update_id_not_found' ) {
							$patch_params['DefaultLanguage'] = 'en';
							$patch_result = $this->request( 'quick_config', $patch_params );
						}

						// #2160: Surface API errors back to the caller
						$api_error = '';
						if ( is_object( $patch_result ) && isset( $patch_result->error ) ) {
							$api_error = $patch_result->error;
						} elseif ( is_array( $patch_result ) && isset( $patch_result['error'] ) ) {
							$api_error = $patch_result['error'];
						}

						if ( ! empty( $api_error ) ) {
							$response = [ 'error' => __( 'Your laws were saved locally but could not be applied to your live site. Please try again or visit the portal.', 'cookie-notice' ), 'apiSync' => false ];
							break;
						}

						// Pull confirmed state from portal — portal is SoT.
						// Updates cookie_notice_app_blocking, cookie_notice_app_regulations,
						// cookie_notice_app_design, cookie_notice_status.
						// Do NOT assign return value to $response — configure success
						// intentionally returns $response = false (initial value).
						// LawSelectorPanel checks only for json.error; false has none.
						$this->get_app_config( $app_id, true, true );
					}
				}

				break;

			case 'select_plan':
				break;

			case 'sync_config':
				// force update configuration from Designer API
				$status_data = $this->get_app_config( $app_id, true, true );

				// use global_override-aware check for data operations (not is_network_admin)
				$network_options = $cn->is_network_options();

				// get the blocking data with timestamp
				if ( $network_options )
					$blocking = get_site_option( 'cookie_notice_app_blocking', [] );
				else
					$blocking = get_option( 'cookie_notice_app_blocking', [] );

				// debug: include blocking data in response when debug mode is enabled
				$debug = $cn->options['general']['debug_mode'] ? [
					'app_id' => $app_id,
					'status_data' => $status_data,
					'blocking' => $blocking,
					'providers_count' => ! empty( $blocking['providers'] ) ? count( $blocking['providers'] ) : 0,
					'patterns_count' => ! empty( $blocking['patterns'] ) ? count( $blocking['patterns'] ) : 0,
				] : null;

				// check if sync was successful
				if ( ! empty( $status_data ) && is_array( $status_data ) && ! empty( $status_data['status'] ) && $status_data['status'] === 'active' ) {
					// set cache purge transient to force widget to refresh
					if ( $network_options )
						set_site_transient( 'cookie_notice_config_update', time(), DAY_IN_SECONDS );
					else
						set_transient( 'cookie_notice_config_update', time(), DAY_IN_SECONDS );

					// re-evaluate CSP state on-demand — pairs with the same call
					// in ajax_purge_cache() so both refresh buttons clear stale flags.
					if ( isset( $cn->settings ) )
						$cn->settings->refresh_csp_notice( true );

					$response = [
						'success' => true,
						'message' => esc_html__( 'Configuration synced successfully.', 'cookie-notice' ),
						'timestamp' => ! empty( $blocking['lastUpdated'] ) ? $blocking['lastUpdated'] : ''
					];
				} else {
					$response = [
						'error' => esc_html__( 'Failed to sync configuration. Please check your app ID and try again.', 'cookie-notice' )
					];
				}

				if ( $debug )
					$response['debug'] = $debug;
				break;
		}

		echo wp_json_encode( $response );
		exit;
	}


/** Function set_form_status() called by wp_ajax hooks: {'cn_privacy_consent_form_status'} **/
/** Parameters found in function set_form_status(): {"post": ["source", "form_id", "status", "nonce"]} **/
function set_form_status() {
		if ( ! isset( $_POST['source'], $_POST['form_id'], $_POST['status'] ) || wp_verify_nonce( $_POST['nonce'], 'cn-privacy-consent-set-form-status' ) === false )
			wp_send_json_error();

		if ( ! current_user_can( apply_filters( 'cn_manage_cookie_notice_cap', 'manage_options' ) ) )
			wp_send_json_error();

		// sanitize source
		$source = sanitize_key( $_POST['source'] );

		// active source?
		if ( array_key_exists( $source, $this->sources ) && $this->sources[$source]['availability'] ) {
			// sanitize form id
			if ( $this->sources[$source]['id_type'] === 'integer' )
				$form_id = (int) $_POST['form_id'];
			elseif ( $this->sources[$source]['id_type'] === 'string' )
				$form_id = (string) sanitize_key( $_POST['form_id'] );

			// valid form?
			if ( $this->instances[$source]->form_exists( $form_id ) ) {
				// inactive source?
				if ( ! $this->sources[$source]['status'] ) {
					// get privacy consent data
					$data = get_option( 'cookie_notice_privacy_consent' );

					// activate source
					$data[$source . '_active'] = true;

					// update privacy consent
					update_option( 'cookie_notice_privacy_consent', $data );
				}

				// get source data
				$data = get_option( 'cookie_notice_privacy_consent_' . $source );

				// update status of specified form
				$data[$form_id]['status'] = (bool) (int) $_POST['status'];

				// update source
				update_option( 'cookie_notice_privacy_consent_' . $source, $data );

				wp_send_json_success();
			}
		}

		wp_send_json_error();
	}


/** Function export_consent_logs() called by wp_ajax hooks: {'cn_react_export_consent_logs'} **/
/** Parameters found in function export_consent_logs(): {"post": ["start_date", "end_date"]} **/
function export_consent_logs() {
		$this->verify_request();

		$cn = Cookie_Notice();

		// Server-side Pro gate — TierGate in React is client-only.
		if ( $cn->get_subscription() !== 'pro' ) {
			wp_send_json_error( [ 'error' => 'CSV export requires a Pro subscription.' ] );
			return;
		}

		$start_date = isset( $_POST['start_date'] ) ? sanitize_text_field( $_POST['start_date'] ) : date( 'Y-m-d' );
		$end_date   = isset( $_POST['end_date'] ) ? sanitize_text_field( $_POST['end_date'] ) : $start_date;

		// Validate date formats (Y-m-d).
		$dt = DateTime::createFromFormat( 'Y-m-d', $start_date );
		if ( ! $dt || $dt->format( 'Y-m-d' ) !== $start_date ) {
			$start_date = date( 'Y-m-d' );
		}

		$dt_end = DateTime::createFromFormat( 'Y-m-d', $end_date );
		if ( ! $dt_end || $dt_end->format( 'Y-m-d' ) !== $end_date || $end_date < $start_date ) {
			$end_date = $start_date;
		}

		// Server-side range cap — Pro = 90 days.
		$range = (int) ( ( new DateTime( $end_date ) )->diff( new DateTime( $start_date ) )->days );

		if ( $range > 90 ) {
			$end_date = ( new DateTime( $start_date ) )->modify( '+90 days' )->format( 'Y-m-d' );
		}

		// No app_id means not connected — return empty.
		if ( empty( $cn->options['general']['app_id'] ) ) {
			wp_send_json_success( [ 'csv' => '', 'count' => 0 ] );
			return;
		}

		$raw = $cn->welcome_api->get_cookie_consent_logs( $start_date, $end_date );

		if ( ! is_array( $raw ) || empty( $raw ) ) {
			wp_send_json_success( [ 'csv' => '', 'count' => 0 ] );
			return;
		}

		$result = $this->transform_consent_logs( $raw, $cn );
		$logs   = $result['logs'];

		// Build CSV string.
		$csv_lines   = [];
		$csv_lines[] = 'Consent ID,Level,Date,IP,Categories';

		foreach ( $logs as $log ) {
			$csv_lines[] = sprintf(
				'"%s","%s","%s","%s","%s"',
				str_replace( '"', '""', $log['id'] ),
				str_replace( '"', '""', $log['level'] ),
				str_replace( '"', '""', $log['date'] ),
				str_replace( '"', '""', $log['ip'] ),
				str_replace( '"', '""', implode( '; ', $log['categories'] ) )
			);
		}

		wp_send_json_success( [
			'csv'   => implode( "\n", $csv_lines ),
			'count' => count( $logs ),
		] );
	}


/** Function complete_setup_wizard() called by wp_ajax hooks: {'cn_react_complete_setup_wizard'} **/
/** No params detected :-/ **/


/** Function ajax_dismiss_admin_notice() called by wp_ajax hooks: {'cn_dismiss_notice'} **/
/** Parameters found in function ajax_dismiss_admin_notice(): {"post": ["nonce", "notice_action", "cn_network", "param"]} **/
function ajax_dismiss_admin_notice() {
		if ( ! current_user_can( 'install_plugins' ) )
			exit;

		if ( ! isset( $_POST['nonce'], $_POST['notice_action'] ) )
			exit;

		if ( wp_verify_nonce( $_POST['nonce'], 'cn_dismiss_notice' ) ) {
			// get notice action
			$notice_action = ! empty( $_POST['notice_action'] ) ? sanitize_key( $_POST['notice_action'] ) : 'dismiss';

			$cn_network = isset( $_POST['cn_network'] ) ? (int) $_POST['cn_network'] : false;

			// network?
			$network = is_multisite() && $cn_network === 1;

			switch ( $notice_action ) {
				// threshold notice
				case 'threshold':
					// set delay period last cycle day
					$delay = isset( $_POST['param'] ) ? (int) $_POST['param'] : 0;

					$this->options['general']['update_threshold_date'] = $delay + DAY_IN_SECONDS;

					// update options
					if ( $network )
						update_site_option( 'cookie_notice_options', $this->options['general'] );
					else
						update_option( 'cookie_notice_options', $this->options['general'] );
					break;

				// delay notice
				case 'delay':
					// set delay period to 2 weeks from now
					$this->options['general']['update_delay_date'] = time() + 2 * WEEK_IN_SECONDS;

					// update options
					if ( $network )
						update_site_option( 'cookie_notice_options', $this->options['general'] );
					else
						update_option( 'cookie_notice_options', $this->options['general'] );
					break;

				// hide notice
				case 'approve':
				default:
					$this->options['general']['update_notice'] = false;
					$this->options['general']['update_delay_date'] = 0;

					// update options
					if ( $network ) {
						$this->options['general']['update_notice_diss'] = true;

						update_site_option( 'cookie_notice_options', $this->options['general'] );
					} else
						update_option( 'cookie_notice_options', $this->options['general'] );
			}
		}

		exit;
	}


/** Function deactivate_plugin() called by wp_ajax hooks: {'cn-deactivate-plugin'} **/
/** Parameters found in function deactivate_plugin(): {"post": ["nonce", "option_id", "other"]} **/
function deactivate_plugin() {
		// check permissions
		if ( ! current_user_can( 'install_plugins' ) || wp_verify_nonce( $_POST['nonce'], 'cn-deactivate-plugin' ) === false )
			return;

		if ( isset( $_POST['option_id'] ) ) {
			$option_id = (int) $_POST['option_id'];

			// avoid fake submissions
			if ( $option_id === 8 ) {
				$other = isset( $_POST['other'] ) ? sanitize_textarea_field( $_POST['other'] ) : '';

				// no reason?
				if ( $other === '' )
					wp_send_json_success();
			}

			wp_remote_post(
				'https://hu-manity.co/wp-json/api/v1/forms/',
				[
					'timeout'		=> 15,
					'blocking'		=> true,
					'headers'		=> [],
					'body'			=> [
						'id'		=> 1,
						'option'	=> $option_id,
						'other'		=> $other,
						'referrer'	=> get_site_url()
					]
				]
			);

			wp_send_json_success();
		}

		wp_send_json_error();
	}


/** Function get_config() called by wp_ajax hooks: {'cn_react_config'} **/
/** No params detected :-/ **/


/** Function get_api_environment() called by wp_ajax hooks: {'cn_get_api_environment'} **/
/** No params detected :-/ **/


/** Function test_set_option() called by wp_ajax hooks: {'cn_react_test_set_option'} **/
/** Parameters found in function test_set_option(): {"post": ["option_name", "option_value"]} **/
function test_set_option() {
		if ( ! defined( 'CN_DEV_MODE' ) || ! CN_DEV_MODE ) {
			wp_send_json_error( [ 'error' => 'Not available outside CN_DEV_MODE.' ] );
		}

		$this->verify_request();

		// Allowlist — only options the test suite legitimately needs to set.
		$allowed = [
			'cookie_notice_ui_mode',
			'cookie_notice_status',
			'cookie_notice_setup_wizard_complete',
			'cookie_notice_welcome_dismissed',
			'cookie_notice_options',
		];

		$option_name = isset( $_POST['option_name'] ) ? sanitize_key( $_POST['option_name'] ) : '';

		if ( ! in_array( $option_name, $allowed, true ) ) {
			wp_send_json_error( [ 'error' => 'Option not in allowlist: ' . $option_name ] );
		}

		// cookie_notice_options is stored as a PHP array — decode JSON input.
		$raw_value = isset( $_POST['option_value'] ) ? wp_unslash( $_POST['option_value'] ) : '';

		if ( $option_name === 'cookie_notice_options' ) {
			$option_value = json_decode( $raw_value, true );
			if ( ! is_array( $option_value ) ) {
				wp_send_json_error( [ 'error' => 'cookie_notice_options must be valid JSON object.' ] );
			}
		} else {
			$option_value = sanitize_text_field( $raw_value );
		}

		update_option( $option_name, $option_value );

		wp_send_json_success( [ 'option' => $option_name, 'value' => $option_value ] );
	}


/** Function test_get_option() called by wp_ajax hooks: {'cn_react_test_get_option'} **/
/** Parameters found in function test_get_option(): {"post": ["option_name"]} **/
function test_get_option() {
		if ( ! defined( 'CN_DEV_MODE' ) || ! CN_DEV_MODE ) {
			wp_send_json_error( [ 'error' => 'Not available outside CN_DEV_MODE.' ] );
		}

		$this->verify_request();

		// Allowlist — only options the test suite legitimately needs to read.
		$allowed = [
			'cookie_notice_options',
			'cookie_notice_status',
			'cookie_notice_ui_mode',
			'cookie_notice_setup_wizard_complete',
			'cookie_notice_welcome_dismissed',
			'cookie_notice_app_blocking',
			'cookie_notice_app_design',
		];

		$option_name = isset( $_POST['option_name'] ) ? sanitize_key( $_POST['option_name'] ) : '';

		if ( ! in_array( $option_name, $allowed, true ) ) {
			wp_send_json_error( [ 'error' => 'Option not in allowlist: ' . $option_name ] );
		}

		$value = get_option( $option_name );

		// Serialize arrays/objects so the test can inspect them as a string.
		if ( is_array( $value ) || is_object( $value ) ) {
			$value = wp_json_encode( $value );
		}

		wp_send_json_success( [ 'option' => $option_name, 'value' => (string) $value ] );
	}


/** Function react_apply_languages() called by wp_ajax hooks: {'cn_react_apply_languages'} **/
/** Parameters found in function react_apply_languages(): {"post": ["languages"]} **/
function react_apply_languages() {
		$this->verify_react_request();

		$cn = Cookie_Notice();
		$app_id = $cn->options['general']['app_id'];

		if ( empty( $app_id ) ) {
			wp_send_json_error( [ 'error' => 'No app connected.' ] );
		}

		$languages_raw = isset( $_POST['languages'] ) && is_array( $_POST['languages'] ) ? $_POST['languages'] : [];

		// Sanitize and validate language codes (2-letter ISO 639-1)
		$allowed_languages = [ 'fr', 'es', 'de', 'it', 'el', 'nl', 'pt', 'pl', 'sv' ];
		$languages = [];

		foreach ( $languages_raw as $lang ) {
			$lang = sanitize_key( $lang );

			if ( in_array( $lang, $allowed_languages, true ) )
				$languages[] = $lang;
		}

		// Free plan: enforce 1-language limit
		$subscription = $cn->get_subscription();
		$status = $cn->get_status();
		$is_free = ( $status === 'active' && $subscription === 'basic' );

		if ( $is_free && count( $languages ) > 1 )
			$languages = array_slice( $languages, 0, 1 );

		$params = [
			'AppID'           => $app_id,
			'DefaultLanguage' => 'en',
			'languages'       => $languages,
		];

		$write_type = $this->get_write_request_type( $app_id );

		// PATCH /by-app endpoint does not accept DefaultLanguage -- strip it.
		if ( $write_type === 'patch_by_app' ) {
			unset( $params['DefaultLanguage'] );
		}
		// DevMode mock ID — return synthetic success so the UI can be tested without a real API.
		if ( $write_type === 'devmode' ) {
			wp_send_json_success( [ 'status' => 200, 'languages' => $languages, 'dev_mode' => true ] );
			return;
		}

		$result = $this->request( $write_type, $params );

		// Design record not yet created — fall back to quick_config to seed it.
		// The API returns { i18n_msg: 'user_design_update_id_not_found', status: 400 } (HTTP 200)
		// when no record exists, so check i18n_msg — not statusCode/404.
		// Also restore DefaultLanguage which patch_by_app doesn't accept but quick_config requires.
		if ( is_object( $result ) && isset( $result->i18n_msg ) && $result->i18n_msg === 'user_design_update_id_not_found' ) {
			$params['DefaultLanguage'] = 'en';
			$result = $this->request( 'quick_config', $params );
		}

		if ( is_object( $result ) && isset( $result->status ) && $result->status === 200 ) {
			// Persist applied languages locally so the dashboard can reflect the real count.
			$network = is_multisite() && $cn->is_plugin_network_active() && $cn->network_options['general']['global_override'];
			if ( $network )
				update_site_option( 'cookie_notice_app_languages', $languages );
			else
				update_option( 'cookie_notice_app_languages', $languages, false );

			wp_send_json_success( [ 'status' => 200, 'languages' => $languages ] );
		} else {
			$error = 'Language update failed.';

			if ( is_array( $result ) && ! empty( $result['error'] ) )
				$error = $result['error'];
			elseif ( is_object( $result ) && ! empty( $result->message ) )
				$error = $result->message;

			wp_send_json_error( [ 'error' => $error, 'apiSync' => false ] );
		}
	}


/** Function get_privacy_consent_logs() called by wp_ajax hooks: {'cn_get_privacy_consent_logs'} **/
/** Parameters found in function get_privacy_consent_logs(): {"post": ["action", "nonce"]} **/
function get_privacy_consent_logs() {
		// check data
		if ( ! isset( $_POST['action'], $_POST['nonce'] ) )
			wp_send_json_error();

		// valid nonce?
		if ( check_ajax_referer( 'cn-get-privacy-consent-logs', 'nonce' ) === false )
			wp_send_json_error();

		// check capability
		if ( ! current_user_can( apply_filters( 'cn_manage_cookie_notice_cap', 'manage_options' ) ) )
			wp_send_json_error();

		$data = Cookie_Notice()->welcome_api->get_privacy_consent_logs();

		if ( is_array( $data ) )
			wp_send_json_success( $this->get_privacy_consent_logs_table( $data ) );
		else
			wp_send_json_error( $data );
	}


/** Function update_script() called by wp_ajax hooks: {'cn_react_script_update'} **/
/** Parameters found in function update_script(): {"post": ["operation", "provider_id", "category_id", "provider_name", "provider_url", "description", "script_patterns", "iframe_patterns"]} **/
function update_script() {
		$this->verify_request();

		$operation = isset( $_POST['operation'] ) ? sanitize_text_field( $_POST['operation'] ) : '';

		if ( ! in_array( $operation, [ 'add', 'edit', 'remove' ], true ) ) {
			wp_send_json_error( [ 'error' => 'Invalid operation.' ] );
		}

		if ( $operation === 'edit' ) {
			$provider_id = isset( $_POST['provider_id'] ) ? sanitize_text_field( $_POST['provider_id'] ) : '';
			$category_id = isset( $_POST['category_id'] ) ? absint( $_POST['category_id'] ) : 0;

			if ( empty( $provider_id ) ) {
				wp_send_json_error( [ 'error' => 'Missing provider_id.' ] );
			}

			if ( ! in_array( $category_id, [ 1, 2, 3, 4 ], true ) ) {
				wp_send_json_error( [ 'error' => 'Invalid category_id.' ] );
			}

			$cn      = Cookie_Notice();
			$network = $cn->is_network_options();

			$blocking = $network
				? get_site_option( 'cookie_notice_app_blocking', [] )
				: get_option( 'cookie_notice_app_blocking', [] );

			if ( empty( $blocking ) || ! isset( $blocking['providers'] ) ) {
				wp_send_json_error( [ 'error' => 'No blocking configuration found.' ] );
			}

			// Update the provider's CategoryID.
			$found = false;

			foreach ( $blocking['providers'] as &$provider ) {
				$pid = is_object( $provider ) ? $provider->ProviderID : ( isset( $provider['ProviderID'] ) ? $provider['ProviderID'] : '' );

				if ( (string) $pid === (string) $provider_id ) {
					if ( is_object( $provider ) ) {
						$provider->CategoryID = $category_id;
					} else {
						$provider['CategoryID'] = $category_id;
					}
					$found = true;
					break;
				}
			}
			unset( $provider );

			if ( ! $found ) {
				wp_send_json_error( [ 'error' => 'Provider not found.' ] );
			}

			// Propagate CategoryID to all patterns belonging to this provider.
			if ( isset( $blocking['patterns'] ) && is_array( $blocking['patterns'] ) ) {
				foreach ( $blocking['patterns'] as &$pattern ) {
					$pat_pid = is_object( $pattern ) ? $pattern->ProviderID : ( isset( $pattern['ProviderID'] ) ? $pattern['ProviderID'] : '' );

					if ( (string) $pat_pid === (string) $provider_id ) {
						if ( is_object( $pattern ) ) {
							$pattern->CategoryID = $category_id;
						} else {
							$pattern['CategoryID'] = $category_id;
						}
					}
				}
				unset( $pattern );
			}

			// Save back.
			if ( $network ) {
				update_site_option( 'cookie_notice_app_blocking', $blocking );
			} else {
				update_option( 'cookie_notice_app_blocking', $blocking );
			}
		}

		if ( $operation === 'add' ) {
			$provider_name   = isset( $_POST['provider_name'] ) ? sanitize_text_field( $_POST['provider_name'] ) : '';
			$provider_url    = isset( $_POST['provider_url'] )  ? esc_url_raw( $_POST['provider_url'] )           : '';
			$category_id     = isset( $_POST['category_id'] )   ? absint( $_POST['category_id'] )                  : 0;
			$description     = isset( $_POST['description'] )   ? sanitize_text_field( $_POST['description'] )     : '';
			$script_patterns = isset( $_POST['script_patterns'] ) && is_array( $_POST['script_patterns'] ) ? $_POST['script_patterns'] : [];
			$iframe_patterns = isset( $_POST['iframe_patterns'] ) && is_array( $_POST['iframe_patterns'] ) ? $_POST['iframe_patterns'] : [];

			if ( empty( $provider_name ) ) {
				wp_send_json_error( [ 'error' => 'Provider name is required.' ] );
			}

			if ( ! in_array( $category_id, [ 1, 2, 3, 4 ], true ) ) {
				wp_send_json_error( [ 'error' => 'Invalid category_id.' ] );
			}

			$cn      = Cookie_Notice();
			$network = $cn->is_network_options();

			$blocking = $network
				? get_site_option( 'cookie_notice_app_blocking', [] )
				: get_option( 'cookie_notice_app_blocking', [] );

			if ( ! is_array( $blocking ) ) {
				$blocking = [];
			}
			if ( ! isset( $blocking['providers'] ) ) {
				$blocking['providers'] = [];
			}
			if ( ! isset( $blocking['patterns'] ) ) {
				$blocking['patterns'] = [];
			}

			// Generate a unique provider ID from the name + timestamp.
			$provider_id = 'custom-' . sanitize_title( $provider_name ) . '-' . time();

			// Append the new provider.
			$blocking['providers'][] = (object) [
				'ProviderID'   => $provider_id,
				'ProviderName' => $provider_name,
				'ProviderURL'  => $provider_url,
				'CategoryID'   => $category_id,
				'IsCustom'     => true,
			];

			// Find current max CookieID so new patterns get unique IDs.
			$max_cookie_id = 0;
			foreach ( $blocking['patterns'] as $p ) {
				$cid = is_object( $p ) ? (int) $p->CookieID : (int) ( isset( $p['CookieID'] ) ? $p['CookieID'] : 0 );
				if ( $cid > $max_cookie_id ) {
					$max_cookie_id = $cid;
				}
			}

			// Append script patterns.
			foreach ( $script_patterns as $pattern_str ) {
				$pattern_str = sanitize_text_field( stripslashes( $pattern_str ) );
				if ( empty( $pattern_str ) ) {
					continue;
				}
				$max_cookie_id++;
				$blocking['patterns'][] = (object) [
					'CookieID'      => $max_cookie_id,
					'ProviderID'    => $provider_id,
					'CategoryID'    => $category_id,
					'PatternType'   => 'script',
					'PatternFormat' => 'wildcard',
					'Pattern'       => $pattern_str,
				];
			}

			// Append iframe patterns.
			foreach ( $iframe_patterns as $pattern_str ) {
				$pattern_str = sanitize_text_field( stripslashes( $pattern_str ) );
				if ( empty( $pattern_str ) ) {
					continue;
				}
				$max_cookie_id++;
				$blocking['patterns'][] = (object) [
					'CookieID'      => $max_cookie_id,
					'ProviderID'    => $provider_id,
					'CategoryID'    => $category_id,
					'PatternType'   => 'iframe',
					'PatternFormat' => 'wildcard',
					'Pattern'       => $pattern_str,
				];
			}

			if ( $network ) {
				update_site_option( 'cookie_notice_app_blocking', $blocking );
			} else {
				update_option( 'cookie_notice_app_blocking', $blocking );
			}

			wp_send_json_success( [
				'message'     => 'Script provider added.',
				'provider_id' => $provider_id,
			] );
		}

		if ( $operation === 'remove' ) {
			$provider_id = isset( $_POST['provider_id'] ) ? sanitize_text_field( $_POST['provider_id'] ) : '';

			if ( empty( $provider_id ) ) {
				wp_send_json_error( [ 'error' => 'Missing provider_id.' ] );
			}

			$cn      = Cookie_Notice();
			$network = $cn->is_network_options();

			$blocking = $network
				? get_site_option( 'cookie_notice_app_blocking', [] )
				: get_option( 'cookie_notice_app_blocking', [] );

			if ( empty( $blocking ) || ! isset( $blocking['providers'] ) ) {
				wp_send_json_error( [ 'error' => 'No blocking configuration found.' ] );
			}

			// Remove the provider entry.
			$blocking['providers'] = array_values( array_filter( $blocking['providers'], function( $p ) use ( $provider_id ) {
				$pid = is_object( $p ) ? $p->ProviderID : ( isset( $p['ProviderID'] ) ? $p['ProviderID'] : '' );
				return (string) $pid !== (string) $provider_id;
			} ) );

			// Remove all patterns belonging to this provider.
			if ( isset( $blocking['patterns'] ) && is_array( $blocking['patterns'] ) ) {
				$blocking['patterns'] = array_values( array_filter( $blocking['patterns'], function( $p ) use ( $provider_id ) {
					$pid = is_object( $p ) ? $p->ProviderID : ( isset( $p['ProviderID'] ) ? $p['ProviderID'] : '' );
					return (string) $pid !== (string) $provider_id;
				} ) );
			}

			if ( $network ) {
				update_site_option( 'cookie_notice_app_blocking', $blocking );
			} else {
				update_option( 'cookie_notice_app_blocking', $blocking );
			}

			wp_send_json_success( [ 'message' => 'Script provider removed.' ] );
		}

		wp_send_json_success( [ 'message' => 'Script provider updated.' ] );
	}


/** Function get_cookie_consent_logs() called by wp_ajax hooks: {'cn_get_cookie_consent_logs'} **/
/** Parameters found in function get_cookie_consent_logs(): {"post": ["action", "date", "nonce"]} **/
function get_cookie_consent_logs() {
		// check data
		if ( ! isset( $_POST['action'], $_POST['date'], $_POST['nonce'] ) )
			wp_send_json_error();

		// valid nonce?
		if ( ! check_ajax_referer( 'cn-get-cookie-consent-logs', 'nonce' ) )
			wp_send_json_error();

		// check capability
		if ( ! current_user_can( apply_filters( 'cn_manage_cookie_notice_cap', 'manage_options' ) ) )
			wp_send_json_error();

		// sanitize date
		$date = preg_replace( '[^\d-]', '', $_POST['date'] );

		// get datetime
		$dt = DateTime::createFromFormat( 'Y-m-d', $date );

		// valid date?
		if ( $dt && $dt->format( 'Y-m-d' ) === $date ) {
			$data = Cookie_Notice()->welcome_api->get_cookie_consent_logs( $date );

			if ( is_array( $data ) )
				wp_send_json_success( $this->get_cookie_consent_logs_table( $data ) );
			else
				wp_send_json_error( $data );
		}

		wp_send_json_error();
	}


/** Function react_update_design() called by wp_ajax hooks: {'cn_react_update_design'} **/
/** Parameters found in function react_update_design(): {"post": ["design", "config", "consentConfig"]} **/
function react_update_design() {
		$this->verify_react_request();

		$cn = Cookie_Notice();
		$app_id = $cn->options['general']['app_id'];

		if ( empty( $app_id ) ) {
			wp_send_json_error( [ 'error' => 'No app connected.' ] );
		}

		$design_raw  = isset( $_POST['design'] )        && is_array( $_POST['design'] )        ? $_POST['design']        : [];
		$config_raw  = isset( $_POST['config'] )        && is_array( $_POST['config'] )        ? $_POST['config']        : [];
		$consent_raw = isset( $_POST['consentConfig'] ) && is_array( $_POST['consentConfig'] ) ? $_POST['consentConfig'] : [];

		if ( empty( $design_raw ) && empty( $config_raw ) && empty( $consent_raw ) ) {
			wp_send_json_error( [ 'error' => 'No update data provided.' ] );
		}

		// Allowed design fields with sanitization
		$allowed_fields = [
			'position'             => 'sanitize_key',
			'displayType'          => 'sanitize_key',
			'bannerColor'          => 'sanitize_hex_color',
			'primaryColor'         => 'sanitize_hex_color',
			'textColor'            => 'sanitize_hex_color',
			'headingColor'         => 'sanitize_hex_color',
			'btnTextColor'         => 'sanitize_hex_color',
			'btnBorderRadius'      => 'sanitize_text_field',
			'animation'            => 'sanitize_key',
			'bannerOpacity'        => 'sanitize_text_field',
			'revokePosition'       => 'sanitize_key',
			'showBulletPoints'     => null, // boolean
		];
		// Note: googleConsentMode / facebookConsentMode / microsoftConsentMode are NOT design fields.
		// The PATCH /by-app endpoint rejects them in design{}. They belong in config{} as booleans.
		// They are handled below alongside gpcSupportMode / doNotTrackMode.

		$design = new stdClass();

		foreach ( $allowed_fields as $field => $sanitizer ) {
			if ( ! array_key_exists( $field, $design_raw ) )
				continue;

			if ( $field === 'showBulletPoints' ) {
				$design->{$field} = filter_var( $design_raw[ $field ], FILTER_VALIDATE_BOOLEAN );
			} elseif ( $sanitizer ) {
				$design->{$field} = call_user_func( $sanitizer, $design_raw[ $field ] );
			}
		}

		// Validate position — translate 'popup' → 'center' (portal label vs CSS class)
		if ( isset( $design->position ) ) {
			if ( $design->position === 'popup' ) {
				$design->position = 'center';
			} elseif ( ! in_array( $design->position, [ 'bottom', 'top', 'left', 'right', 'center' ], true ) ) {
				$design->position = 'bottom';
			}
		}

		// Validate displayType
		if ( isset( $design->displayType ) && ! in_array( $design->displayType, [ 'floating', 'fixed' ], true ) )
			$design->displayType = 'floating';

		// Validate animation
		if ( isset( $design->animation ) && ! in_array( $design->animation, [ 'fade', 'slide', 'none' ], true ) )
			$design->animation = 'fade';

		// Validate bannerOpacity
		if ( isset( $design->bannerOpacity ) ) {
			$opacity = (float) $design->bannerOpacity;
			$design->bannerOpacity = max( 0.0, min( 1.0, $opacity ) );
		}

		// Build config object from allowed behavior fields
		$config_allowed = [ 'revokeConsent', 'revokeMethod', 'onScroll', 'onScrollOffset', 'onClick', 'reloading' ];
		$config = new stdClass();
		foreach ( $config_allowed as $f ) {
			if ( isset( $config_raw[ $f ] ) )
				$config->$f = sanitize_text_field( $config_raw[ $f ] );
		}

		// Merge consent mode fields into config{} — the Designer API stores all of these
		// under BannerConfigJSON, not a separate consentConfig key. The PATCH /by-app endpoint
		// rejects a top-level consentConfig key entirely.
		//
		// Field name mapping (JS POST key → API config key):
		//   gpcSupport  → gpcSupportMode  (boolean)
		//   doNotTrack  → doNotTrackMode  (boolean)
		//   All GCM/Facebook/Microsoft map fields keep their names, as integers.
		// Map/level fields: must be integers (0–4).
		$consent_int_fields = [
			'googleConsentMapAdStorage', 'googleConsentMapAnalytics', 'googleConsentMapFunctionality',
			'googleConsentMapPersonalization', 'googleConsentMapSecurity', 'googleConsentMapAdPersonalization',
			'googleConsentMapAdUserData', 'facebookConsentMapConsent', 'microsoftConsentMapAdStorage',
			'microsoftConsentMapAnalyticsStorage',
		];
		foreach ( $consent_int_fields as $f ) {
			if ( isset( $consent_raw[ $f ] ) )
				$config->$f = (int) $consent_raw[ $f ];
		}
		// IMPORTANT: Toggle fields MUST use (bool)(int) — NOT bare (int).
		// wp_json_encode((int)1) = JSON 1 (integer) — API silently drops it.
		// wp_json_encode((bool)true) = JSON true (boolean) — API persists it.
		// See commit 8ff1432 for the original fix. Do NOT revert to (int).
		$consent_bool_fields = [ 'microsoftConsentModePixie', 'microsoftConsentModeClarity' ];
		foreach ( $consent_bool_fields as $f ) {
			if ( isset( $consent_raw[ $f ] ) )
				$config->$f = (bool) (int) $consent_raw[ $f ];
		}
		// gpcSupport → gpcSupportMode (bool). Pro-gated with grandfather:
		// Free apps cannot set gpcSupportMode=true unless it's already true
		// (grandfathered). Disabling is always allowed; once disabled on Free,
		// the app loses its grandfather and cannot re-enable. See KnowledgeHub
		// decisions.md (gpc-pro-gating-with-grandfather).
		if ( isset( $consent_raw['gpcSupport'] ) ) {
			$incoming_gpc = (bool) (int) $consent_raw['gpcSupport'];
			$is_pro       = $cn->get_subscription() === 'pro';

			if ( $is_pro || ! $incoming_gpc ) {
				// Pro: anything goes. Free + setting to false: always allowed.
				$config->gpcSupportMode = $incoming_gpc;
			} else {
				// Free + setting to true: only honor if already true (grandfather).
				$existing_blocking = $cn->is_network_options()
					? get_site_option( 'cookie_notice_app_blocking', [] )
					: get_option( 'cookie_notice_app_blocking', [] );
				if ( ! empty( $existing_blocking['banner_config']['gpcSupportMode'] ) )
					$config->gpcSupportMode = true;
				// else: silently strip — UI gate should have prevented this anyway.
			}
		}
		// gpcBannerMode → gpcBannerMode (string enum). Not Pro-gated directly:
		// it's only consulted when gpcSupportMode is true, so transitive gating
		// via the parent toggle is sufficient. Validate the enum here and let
		// stray values fall through to the persisted/default value.
		if ( isset( $consent_raw['gpcBannerMode'] ) ) {
			$mode = sanitize_key( $consent_raw['gpcBannerMode'] );
			if ( in_array( $mode, [ 'banner', 'hidden', 'passive' ], true ) )
				$config->gpcBannerMode = $mode;
		}
		// doNotTrack → doNotTrackMode (bool)
		if ( isset( $consent_raw['doNotTrack'] ) )
			$config->doNotTrackMode = (bool) (int) $consent_raw['doNotTrack'];
		// Consent mode flags (google/facebook/microsoft) — sent in design_raw from the React POST
		// but must be placed in config{} as booleans. The PATCH /by-app endpoint rejects them in design{}.
		foreach ( [ 'googleConsentMode', 'facebookConsentMode', 'microsoftConsentMode' ] as $mode_field ) {
			if ( isset( $design_raw[ $mode_field ] ) )
				$config->$mode_field = (bool) (int) $design_raw[ $mode_field ];
		}

		// Build params — only include non-empty objects
		$params = [
			'AppID'           => $app_id,
			'DefaultLanguage' => 'en',
			'text'            => (object) [ 'privacyPolicyUrl' => get_privacy_policy_url() ],
		];
		if ( ! empty( (array) $design ) )
			$params['design'] = $design;
		if ( ! empty( (array) $config ) )
			$params['config'] = $config;

		$write_type = $this->get_write_request_type( $app_id );

		// PATCH /by-app endpoint does not accept DefaultLanguage -- strip it.
		if ( $write_type === 'patch_by_app' ) {
			unset( $params['DefaultLanguage'] );
		}
		// DevMode mock ID — return synthetic success so the UI can be tested without a real API.
		if ( $write_type === 'devmode' ) {
			wp_send_json_success( [ 'status' => 200, 'dev_mode' => true ] );
			return;
		}

		$result = $this->request( $write_type, $params );

		// debug: log raw API response for consent mode debugging.
		if ( $cn->options['general']['debug_mode'] ) {
			error_log( 'react_update_design API result: ' . var_export( $result, true ) );
		}

		// Design record not yet created — fall back to quick_config to seed it.
		// The API returns { i18n_msg: 'user_design_update_id_not_found', status: 400 } (HTTP 200)
		// when no record exists, so check i18n_msg — not statusCode/404.
		// Also restore DefaultLanguage which patch_by_app doesn't accept but quick_config requires.
		if ( is_object( $result ) && isset( $result->i18n_msg ) && $result->i18n_msg === 'user_design_update_id_not_found' ) {
			$params['DefaultLanguage'] = 'en';
			$result = $this->request( 'quick_config', $params );
		}

		if ( is_object( $result ) && isset( $result->status ) && $result->status === 200 ) {
			// Pull confirmed state from portal — makes portal unambiguous SoT.
			// Updates cookie_notice_app_blocking (GCM/GPC signal maps),
			// fires cn_configuration_updated → clears page caches.
			// Does NOT set cookie_notice_config_update transient (widget CDN cache).
			$this->get_app_config( $app_id, true, true );

			wp_send_json_success( [ 'status' => 200 ] );
		} else {
			$error = 'Design update failed.';

			if ( is_array( $result ) && ! empty( $result['error'] ) )
				$error = $result['error'];
			elseif ( is_object( $result ) && ! empty( $result->message ) )
				$error = $result->message;
			elseif ( is_object( $result ) && ! empty( $result->error ) )
				$error = $result->error;
			elseif ( is_object( $result ) && ! empty( $result->i18n_msg ) )
				$error = 'API error: ' . $result->i18n_msg;
			elseif ( $result === null )
				$error = 'No response from API — check connection.';

			wp_send_json_error( [ 'error' => $error, 'apiSync' => false ] );
		}
	}


/** Function ajax_purge_cache() called by wp_ajax hooks: {'cn_purge_cache'} **/
/** No params detected :-/ **/


/** Function save_options() called by wp_ajax hooks: {'cn_react_save_options'} **/
/** Parameters found in function save_options(): {"post": ["app_id", "app_key", "refuse_code", "refuse_code_head", "excluded_handles", "conditional_rules", "on_scroll_offset", "bar_opacity", "see_more_opt", "cn_network"]} **/
function save_options() {
		$this->verify_request();

		$cn      = Cookie_Notice();
		$options = $cn->options['general'];

		// Capture the connected app id before any $_POST override, so a connection
		// change can be detected after persist (see the refresh block at the end).
		$old_app_id = isset( $options['app_id'] ) ? $options['app_id'] : '';

		// WAF-safe decode gate (#47585, #47616). The React admin base64-encodes the
		// code/markup-bearing fields (refuse_code, refuse_code_head, conditional_rules)
		// behind self::WAF_B64_SENTINEL so a firewall can't 403 the POST for carrying a
		// raw <script>. Decode-or-reject ALL of them up-front, before any $options
		// mutation for these fields, so a corrupt encoded payload rejects the whole save
		// (no partial store). Legacy (non-sentinel) values pass through unchanged and the
		// existing per-field passthrough below still applies.
		//
		// array_key_exists( '<field>', $waf )  → field was sentinel-decoded; store the
		//     decoded value DIRECTLY (NO second wp_unslash — that corrupts backslash-bearing
		//     scripts). Key-presence (not isset) is the test, so an empty decoded value counts.
		// key absent  → field is a legacy passthrough; keep today's exact wp_unslash below.
		$waf              = [];
		$waf_fields       = [ 'refuse_code', 'refuse_code_head', 'conditional_rules' ];
		$sentinel         = self::WAF_B64_SENTINEL;
		$sentinel_len     = strlen( $sentinel );

		foreach ( $waf_fields as $waf_field ) {
			if ( ! isset( $_POST[ $waf_field ] ) ) {
				continue;
			}

			// Was this value sentinel-tagged (encoded) or a legacy passthrough? Match
			// the marker on the RAW value (post wp_magic_quotes, pre wp_unslash). The
			// marker is printable ASCII with no addslashes-escaped chars (no NUL, quote,
			// or backslash), so wp_magic_quotes leaves it intact and this strncmp holds.
			$is_encoded = strncmp( (string) $_POST[ $waf_field ], $sentinel, $sentinel_len ) === 0;

			$ok      = false;
			$decoded = self::decode_waf_field( $_POST[ $waf_field ], $ok );

			if ( ! $ok ) {
				// Reject the entire save — no store, no partial write.
				wp_send_json_error( [ 'error' => __( 'Could not save: the settings payload could not be decoded. Please retry.', 'cookie-notice' ) ] );
			}

			// Record the decoded value only for the sentinel branch; a legacy
			// passthrough is left absent so it keeps today's exact wp_unslash below.
			if ( $is_encoded ) {
				$waf[ $waf_field ] = $decoded;
			}
		}

		// Boolean fields.
		$bool_fields = [
			'refuse_opt',
			'revoke_cookies',
			'on_scroll',
			'on_click',
			'redirection',
			'see_more',
			'bot_detection',
			'amp_support',
			'caching_compatibility',
			'debug_mode',
			'wp_consent_api',
			'conditional_active',
			'deactivation_delete',
			'app_blocking',
		];

		foreach ( $bool_fields as $field ) {
			if ( isset( $_POST[ $field ] ) ) {
				$options[ $field ] = (bool) $_POST[ $field ];
			}
		}

		// Server-side threshold enforcement: cap app_blocking to false when
		// the free-plan visit limit is exceeded, matching settings.php:1965.
		if ( ! empty( $options['app_blocking'] ) && $cn->threshold_exceeded() ) {
			$options['app_blocking'] = false;
		}

		// Text fields.
		$text_fields = [
			'message_text',
			'accept_text',
			'refuse_text',
			'revoke_text',
			'revoke_message_text',
			'css_class',
		];

		foreach ( $text_fields as $field ) {
			if ( isset( $_POST[ $field ] ) ) {
				$options[ $field ] = sanitize_text_field( $_POST[ $field ] );
			}
		}

		// Connection credential fields — sanitize_key strips to lowercase alphanumeric + dashes/underscores.
		if ( isset( $_POST['app_id'] ) ) {
			$options['app_id'] = sanitize_key( $_POST['app_id'] );
		}

		if ( isset( $_POST['app_key'] ) ) {
			$options['app_key'] = sanitize_key( $_POST['app_key'] );
		}

		// Script blocking code fields — these can contain <script> tags.
		// Sentinel-decoded (WAF-safe) values are stored VERBATIM: base64 decode
		// already yields the exact bytes the admin typed, so a second wp_unslash
		// would corrupt any backslash-bearing script/regex. Legacy (non-encoded)
		// values keep today's exact wp_unslash passthrough (admin-only, manage_options).
		if ( array_key_exists( 'refuse_code', $waf ) ) {
			$options['refuse_code'] = $waf['refuse_code'];
		} elseif ( isset( $_POST['refuse_code'] ) ) {
			$options['refuse_code'] = wp_unslash( $_POST['refuse_code'] );
		}

		if ( array_key_exists( 'refuse_code_head', $waf ) ) {
			$options['refuse_code_head'] = $waf['refuse_code_head'];
		} elseif ( isset( $_POST['refuse_code_head'] ) ) {
			$options['refuse_code_head'] = wp_unslash( $_POST['refuse_code_head'] );
		}

		// Excluded script handles — newline-separated string from React textarea → stored as array.
		if ( isset( $_POST['excluded_handles'] ) ) {
			$options['excluded_handles'] = array_values( array_filter( array_map( 'sanitize_text_field', explode( "\n", $_POST['excluded_handles'] ) ) ) );
		}

		// Conditional rules — JSON string from React → validated nested array.
		// Sentinel-decoded values are already the exact JSON bytes (no wp_unslash);
		// legacy values keep today's wp_unslash. The per-rule validation loop below
		// is unchanged for both paths.
		if ( isset( $_POST['conditional_rules'] ) ) {
			$raw_rules = array_key_exists( 'conditional_rules', $waf )
				? json_decode( $waf['conditional_rules'], true )
				: json_decode( wp_unslash( $_POST['conditional_rules'] ), true );

			if ( is_array( $raw_rules ) ) {
				$settings  = Cookie_Notice()->settings;
				$group_id  = 1;
				$rules     = [];

				foreach ( $raw_rules as $group ) {
					if ( ! is_array( $group ) || empty( $group ) ) {
						continue;
					}

					$rule_id = 1;

					foreach ( $group as $rule ) {
						if ( ! is_array( $rule ) ) {
							continue;
						}

						$param    = sanitize_key( $rule['param'] ?? '' );
						$operator = sanitize_key( $rule['operator'] ?? '' );
						$value    = $param === 'taxonomy_archive'
							? ( $rule['value'] ?? '' )
							: sanitize_key( $rule['value'] ?? '' );

						if ( $param && $operator && $value !== '' && $settings->check_rule( $param, $operator, $value ) ) {
							$rules[ $group_id ][ $rule_id++ ] = [
								'param'    => $param,
								'operator' => $operator,
								'value'    => $value,
							];
						}
					}

					if ( ! empty( $rules[ $group_id ] ) ) {
						$group_id++;
					}
				}

				$options['conditional_rules'] = $rules;
			} else {
				$options['conditional_rules'] = [];
			}
		}

		// Select fields — value must be one of the allowed options.
		$select_fields = [
			'revoke_cookies_opt' => [ 'automatic', 'manual' ],
			'time'               => [ 'hour', 'day', 'week', 'month', '3months', '6months', 'year', 'infinity' ],
			'time_rejected'      => [ 'hour', 'day', 'week', 'month', '3months', '6months', 'year', 'infinity' ],
			'link_target'        => [ '_blank', '_self' ],
			'link_position'      => [ 'banner', 'message' ],
			'position'           => [ 'top', 'bottom', 'left', 'right', 'popup' ],
			'displayType'        => [ 'fixed', 'floating' ],
			'hide_effect'        => [ 'none', 'fade', 'slide' ],
			'script_placement'   => [ 'header', 'footer' ],
			'conditional_display' => [ 'hide', 'show' ],
			'ui_mode'             => [ 'react', 'legacy' ],
		];

		foreach ( $select_fields as $field => $allowed ) {
			if ( isset( $_POST[ $field ] ) ) {
				$value = sanitize_text_field( $_POST[ $field ] );
				if ( in_array( $value, $allowed, true ) ) {
					$options[ $field ] = $value;
				}
			}
		}

		// Number fields.
		if ( isset( $_POST['on_scroll_offset'] ) ) {
			$options['on_scroll_offset'] = absint( $_POST['on_scroll_offset'] );
		}

		// Nested colors array — text, button, bar, bar_opacity.
		$color_fields = [ 'text', 'button', 'bar' ];
		foreach ( $color_fields as $color_field ) {
			$post_key = 'color_' . $color_field;
			if ( isset( $_POST[ $post_key ] ) ) {
				$val = sanitize_hex_color( $_POST[ $post_key ] );
				if ( $val ) {
					$options['colors'][ $color_field ] = $val;
				}
			}
		}

		// bar_opacity lives inside the nested colors array; clamp to 50–100.
		if ( isset( $_POST['bar_opacity'] ) ) {
			$bar_opacity = absint( $_POST['bar_opacity'] );
			$bar_opacity = max( 50, min( 100, $bar_opacity ) );
			$options['colors']['bar_opacity'] = $bar_opacity;
		}

		// Nested see_more_opt array.
		if ( isset( $_POST['see_more_opt'] ) && is_array( $_POST['see_more_opt'] ) ) {
			$raw = $_POST['see_more_opt'];

			if ( isset( $raw['text'] ) ) {
				$options['see_more_opt']['text'] = sanitize_text_field( $raw['text'] );
			}

			if ( isset( $raw['link_type'] ) ) {
				$link_type = sanitize_text_field( $raw['link_type'] );
				if ( in_array( $link_type, [ 'page', 'custom' ], true ) ) {
					$options['see_more_opt']['link_type'] = $link_type;
				}
			}

			if ( isset( $raw['id'] ) ) {
				$options['see_more_opt']['id'] = absint( $raw['id'] );
			}

			if ( isset( $raw['link'] ) ) {
				$options['see_more_opt']['link'] = esc_url_raw( $raw['link'] );
			}

			if ( isset( $raw['sync'] ) ) {
				$options['see_more_opt']['sync'] = (bool) $raw['sync'];
			}
		}

		// Enforce field ownership partition (#2264) — strip any key that is
		// not declared in Cookie_Notice::$plugin_owned_fields. Nested sub-arrays
		// (colors, see_more_opt, conditional_rules) are already in the allowlist.
		$allowed = Cookie_Notice::$plugin_owned_fields;

		foreach ( array_keys( $options ) as $key ) {
			if ( ! in_array( $key, $allowed, true ) ) {
				unset( $options[ $key ] );
			}
		}

		// Persist — network vs. single-site.
		if ( isset( $_POST['cn_network'] ) && $_POST['cn_network'] ) {
			update_site_option( 'cookie_notice_options', $options );
		} else {
			update_option( 'cookie_notice_options', $options );
		}

		// Connection changed — refresh cached app data for the new app id.
		//
		// The cached analytics ( cookie_notice_app_analytics ) and derived status
		// ( cookie_notice_status['threshold_exceeded'] ) are otherwise refreshed only
		// hourly via the cookie_notice_get_app_analytics cron. Without this, a domain
		// reconnected from a Free to a Pro app keeps showing the Free-plan visit-limit
		// notice -- and the app_blocking cap applied above -- until the cron next runs.
		// Mirrors the legacy form path in Settings::validate_options().
		$new_app_id = isset( $options['app_id'] ) ? $options['app_id'] : '';

		if ( $new_app_id !== '' && ! empty( $options['app_key'] ) && $new_app_id !== $old_app_id ) {
			// Mirror the just-persisted credentials into the in-memory options so the
			// config/token requests below authenticate as the new app, exactly as the
			// legacy form path does after register_setting() writes the new options.
			$cn->options['general'] = $options;

			$app_data = $cn->welcome_api->get_app_config( $new_app_id, true, false );

			// get_app_config() returns the status_data array normally, but can
			// return null (its one-cron-per-hour throttle branch). Guard the shape
			// before reading 'status' so a null/partial return can't fatal the save.
			if ( is_array( $app_data ) && isset( $app_data['status'] ) && $cn->check_status( $app_data['status'] ) === 'active' ) {
				// get_app_analytics authenticates with the just-saved credentials via the
				// analytics_app_data shim ( welcome-api.php 'get_analytics' request branch ).
				$cn->settings->set_analytics_app_data( [ 'id' => $new_app_id, 'key' => $options['app_key'] ] );
				$cn->welcome_api->get_app_analytics( $new_app_id, true, false );
				$cn->settings->set_analytics_app_data( [] );
			}
		}

		wp_send_json_success( [ 'message' => __( 'Settings saved.', 'cookie-notice' ) ] );
	}


/** Function get_dashboard() called by wp_ajax hooks: {'cn_react_dashboard'} **/
/** Parameters found in function get_dashboard(): {"post": ["cn_usage"]} **/
function get_dashboard() {
		$this->verify_request();

		$cn = Cookie_Notice();

		// --- Read cached analytics option ---
		// Single source: cookie_notice_app_analytics (refreshed hourly via welcome-api.php cron).
		// ⚠️ Multisite pattern: use site_option ONLY when network-active with global_override.
		// Do NOT simplify to is_multisite() alone — pattern matches welcome-api.php get_app_config().
		$network       = $cn->is_network_options();
		$analytics_raw = $network
			? get_site_option( 'cookie_notice_app_analytics', [] )
			: get_option( 'cookie_notice_app_analytics', [] );

		// --- Cycle usage (visits vs threshold) ---
		// Read from cached analytics option; CN_DEV_MODE overrides for UI testing.
		$visits    = ! empty( $analytics_raw['cycleUsage']->visits ) ? (int) $analytics_raw['cycleUsage']->visits : 0;
		$threshold = ! empty( $analytics_raw['cycleUsage']->threshold ) ? (int) $analytics_raw['cycleUsage']->threshold : 0;

		// CN_DEV_MODE: honour cn_usage=0-100 (forwarded as POST field by fetchDashboard
		// since admin-ajax.php is a POST endpoint and $_GET params from the page URL
		// are not available here).
		if ( defined( 'CN_DEV_MODE' ) && CN_DEV_MODE && isset( $_POST['cn_usage'] ) ) {
			$pct       = max( 0, min( 100, (int) $_POST['cn_usage'] ) );
			$threshold = $threshold > 0 ? $threshold : 1000;
			$visits    = (int) round( $threshold * ( $pct / 100 ) );
		}

		// --- ConsentStats breakdown ---

		$level_totals = [ 1 => 0, 2 => 0, 3 => 0 ];

		if ( ! empty( $analytics_raw['consentActivities'] ) && is_array( $analytics_raw['consentActivities'] ) ) {
			foreach ( $analytics_raw['consentActivities'] as $entry ) {
				$lvl = (int) $entry->consentlevel;
				if ( isset( $level_totals[ $lvl ] ) ) {
					$level_totals[ $lvl ] += (int) $entry->totalrecd;
				}
			}
		}

		$consent_breakdown = $this->compute_consent_breakdown( $level_totals );

		// Regulations saved locally by cn_api_request?configure action.
		// Exposed here so Protection.jsx LAWS card can display them without a
		// Designer API round-trip. (#1897)
		$reg_keys     = $network
			? get_site_option( 'cookie_notice_app_regulations', [] )
			: get_option( 'cookie_notice_app_regulations', [] );
		$regulations  = array_fill_keys( (array) $reg_keys, true );

		// Language codes saved locally by react_apply_languages() on successful API write. (#1966)
		// Always includes 'en' (default) + any additional codes the user configured.
		$saved_languages = $network
			? get_site_option( 'cookie_notice_app_languages', [] )
			: get_option( 'cookie_notice_app_languages', [] );
		$language = array_values( array_unique( array_merge( [ 'en' ], (array) $saved_languages ) ) );

		// Platform account email from login token (#2168).
		// Stored in cookie_notice_app_token transient as ->email after successful login.
		// Used in PortalBridgeModal to tell the user which email to sign in with.
		// Returns empty string when not connected (token not set or expired).
		$data_token    = $network
			? get_site_transient( 'cookie_notice_app_token' )
			: get_transient( 'cookie_notice_app_token' );
		$account_email = ! empty( $data_token->email ) ? sanitize_email( $data_token->email ) : '';

		// Banner design fields cached by get_app_config() — React computes
		// the active template on the fly by matching against PRESETS.
		$design = $network
			? get_site_option( 'cookie_notice_app_design', [] )
			: get_option( 'cookie_notice_app_design', [] );

		wp_send_json_success( [
			'analytics'        => [
				'cycleUsage' => [
					'visits'    => $visits,
					'threshold' => $threshold,
				],
			],
			'consentBreakdown' => $consent_breakdown,
			'domainUrl'        => home_url(),
			'appId'            => $cn->options['general']['app_id'],
			'activatedAt'      => isset( $cn->status_data['activation_datetime'] ) ? $cn->status_data['activation_datetime'] : 0,
			'consentCount'     => $consent_breakdown['total'],
			'accountEmail'     => $account_email,
			'appConfig'        => [
				'regulations' => $regulations,
				'language'    => $language,
				'design'      => $design,
			],
		] );
	}


/** Function get_rule_values() called by wp_ajax hooks: {'cn_react_rule_values'} **/
/** Parameters found in function get_rule_values(): {"post": ["param"]} **/
function get_rule_values() {
		$this->verify_request();

		$param = isset( $_POST['param'] ) ? sanitize_key( $_POST['param'] ) : '';

		if ( ! $param ) {
			wp_send_json_error( [ 'message' => 'Missing param' ] );
		}

		$values = [];

		switch ( $param ) {
			case 'page_type':
				$values = [
					[ 'value' => 'front', 'label' => __( 'Front Page', 'cookie-notice' ) ],
					[ 'value' => 'home', 'label' => __( 'Home Page', 'cookie-notice' ) ],
				];
				break;

			case 'page':
				$pages = get_pages( [ 'post_status' => [ 'publish', 'private', 'future' ] ] );
				$front = (int) get_option( 'page_on_front' );
				$blog  = (int) get_option( 'page_for_posts' );

				foreach ( $pages as $page ) {
					if ( $page->ID === $front || $page->ID === $blog ) {
						continue;
					}
					$values[] = [ 'value' => (string) $page->ID, 'label' => $page->post_title ];
				}
				break;

			case 'post_type':
				$types = get_post_types( [ 'public' => true ], 'objects' );

				foreach ( $types as $type ) {
					$values[] = [ 'value' => $type->name, 'label' => $type->labels->singular_name ];
				}
				break;

			case 'post_type_archive':
				$types = get_post_types( [ 'public' => true, 'has_archive' => true ], 'objects' );

				foreach ( $types as $type ) {
					$values[] = [ 'value' => $type->name, 'label' => $type->labels->singular_name ];
				}
				break;

			case 'user_type':
				$values = [
					[ 'value' => 'logged_in', 'label' => __( 'Logged in', 'cookie-notice' ) ],
					[ 'value' => 'guest', 'label' => __( 'Guest', 'cookie-notice' ) ],
				];
				break;

			case 'taxonomy_archive':
				$taxonomies = get_taxonomies( [ 'public' => true ], 'objects' );

				foreach ( $taxonomies as $taxonomy ) {
					$terms = get_terms( [ 'taxonomy' => $taxonomy->name, 'hide_empty' => false ] );

					if ( is_wp_error( $terms ) || empty( $terms ) ) {
						continue;
					}

					$group = [
						'group' => $taxonomy->labels->name,
						'items' => [],
					];

					foreach ( $terms as $term ) {
						$group['items'][] = [
							'value' => $term->term_id . '|' . $taxonomy->name,
							'label' => $term->name,
						];
					}

					$values[] = $group;
				}
				break;
		}

		wp_send_json_success( [ 'values' => $values ] );
	}


/** Function display_table() called by wp_ajax hooks: {'cn_privacy_consent_display_table'} **/
/** Parameters found in function display_table(): {"request": ["action", "nonce", "source"], "get": ["orderby", "order"]} **/
function display_table() {
		// valid nonce?
		if ( check_ajax_referer( 'cn-privacy-consent-list-table-nonce', 'nonce' ) === false )
			wp_send_json_error();

		// check data
		if ( ! isset( $_REQUEST['action'], $_REQUEST['nonce'], $_REQUEST['source'] ) )
			wp_send_json_error();

		// check capability
		if ( ! current_user_can( apply_filters( 'cn_manage_cookie_notice_cap', 'manage_options' ) ) )
			wp_send_json_error();

		// sanitize source
		$source = sanitize_key( $_REQUEST['source'] );

		if ( ! array_key_exists( $source, $this->sources ) || ! $this->sources[$source]['availability'] )
			wp_send_json_error();

		// make title column sorted
		if ( empty( $_GET['orderby'] ) )
			$_GET['orderby'] = 'title';

		if ( empty( $_GET['order'] ) )
			$_GET['order'] = 'asc';

		// initialize list table
		$list_table = new Cookie_Notice_Privacy_Consent_List_Table( [
			'plural'	=> 'cn-source-' . esc_attr( $this->sources[$source]['name'] ) . '-forms',
			'singular'	=> 'cn-source-' . esc_attr( $this->sources[$source]['name'] ) . '-form',
			'ajax'		=> true
		] );

		// set source
		$list_table->cn_set_source( $this->sources[$source] );

		$args = [
			'source'	=> $source,
			'order'		=> 'asc',
			'orderby'	=> 'title',
			'page'		=> 1,
			'search'	=> ''
		];

		// set source forms
		$list_table->cn_set_forms( $this->instances[$source]->get_forms( $args ) );

		// prepare items
		$list_table->prepare_items();

		ob_start();
		// $list_table->search_box( __( 'Search', 'cookie-notice' ), $source );
		$list_table->display();
		$display = ob_get_clean();

		wp_send_json_success( $display );
	}


/** Function dev_reset() called by wp_ajax hooks: {'cn_react_dev_reset'} **/
/** No params detected :-/ **/


/** Function ajax_review_notice() called by wp_ajax hooks: {'cn_review_notice'} **/
/** Parameters found in function ajax_review_notice(): {"post": ["nonce", "notice_action", "cn_network"]} **/
function ajax_review_notice() {
		if ( ! current_user_can( 'install_plugins' ) )
			exit;

		if ( ! isset( $_POST['nonce'], $_POST['notice_action'] ) )
			exit;

		if ( wp_verify_nonce( $_POST['nonce'], 'cn_review_notice' ) ) {
			// get notice action
			$notice_action = ! empty( $_POST['notice_action'] ) ? sanitize_key( $_POST['notice_action'] ) : 'dismiss';

			$cn_network = isset( $_POST['cn_network'] ) ? (int) $_POST['cn_network'] : false;

			// network?
			$network = is_multisite() && $cn_network === 1;

			switch ( $notice_action ) {
				// delay notice
				case 'delay':
					$this->options['general']['review_notice'] = true;
					$this->options['general']['review_notice_delay'] = time() + 2 * WEEK_IN_SECONDS;

					// update options
					if ( $network )
						update_site_option( 'cookie_notice_options', $this->options['general'] );
					else
						update_option( 'cookie_notice_options', $this->options['general'] );
					break;

				// hide notice
				case 'dismiss':
				case 'review':
				default:
					$this->options['general']['review_notice'] = false;
					$this->options['general']['review_notice_delay'] = 0;

					// update options
					if ( $network ) {
						$this->options['general']['update_notice_diss'] = true;

						update_site_option( 'cookie_notice_options', $this->options['general'] );
					} else
						update_option( 'cookie_notice_options', $this->options['general'] );
			}
		}

		exit;
	}


/** Function react_apply_template() called by wp_ajax hooks: {'cn_react_apply_template'} **/
/** Parameters found in function react_apply_template(): {"post": ["template"]} **/
function react_apply_template() {
		$this->verify_react_request();

		$cn = Cookie_Notice();
		$app_id = $cn->options['general']['app_id'];

		if ( empty( $app_id ) ) {
			wp_send_json_error( [ 'error' => 'No app connected.' ] );
		}

		$template = isset( $_POST['template'] ) ? sanitize_key( $_POST['template'] ) : '';

		// Full design presets — position/color/typography synced to portal.
		// Colors match TemplatePresets.jsx PRESETS array.
		$presets = [
			'minimal' => [
				'position'         => 'left',
				'displayType'      => 'floating',
				'bannerColor'      => '#f0f0f0',
				'primaryColor'     => '#20c19e',
				'textColor'        => '#434f58',
				'headingColor'     => '#434f58',
				'btnTextColor'     => '#ffffff',
				'btnBorderRadius'  => '25px',
				'animation'        => 'fade',
				'bannerOpacity'    => 0.97,
				'revokePosition'   => 'bottom-left',
				'showBulletPoints' => true,
			],
			'standard' => [
				'position'         => 'bottom',
				'displayType'      => 'floating',
				'bannerColor'      => '#2d3436',
				'primaryColor'     => '#20c19e',
				'textColor'        => '#ffffff',
				'headingColor'     => '#ffffff',
				'btnTextColor'     => '#ffffff',
				'btnBorderRadius'  => '25px',
				'animation'        => 'fade',
				'bannerOpacity'    => 0.97,
				'revokePosition'   => 'bottom-left',
				'showBulletPoints' => true,
			],
			'bold' => [
				'position'         => 'top',
				'displayType'      => 'fixed',
				'bannerColor'      => '#1a1a2e',
				'primaryColor'     => '#20c19e',
				'textColor'        => '#ffffff',
				'headingColor'     => '#ffffff',
				'btnTextColor'     => '#ffffff',
				'btnBorderRadius'  => '6px',
				'animation'        => 'slide',
				'bannerOpacity'    => 1.0,
				'revokePosition'   => 'bottom-right',
				'showBulletPoints' => false,
			],
			'popup' => [
				'position'         => 'center',
				'displayType'      => 'floating',
				'bannerColor'      => '#2c3e50',
				'primaryColor'     => '#20c19e',
				'textColor'        => '#ffffff',
				'headingColor'     => '#ffffff',
				'btnTextColor'     => '#ffffff',
				'btnBorderRadius'  => '25px',
				'animation'        => 'fade',
				'bannerOpacity'    => 0.97,
				'revokePosition'   => 'bottom-left',
				'showBulletPoints' => true,
			],
			'panel' => [
				'position'         => 'right',
				'displayType'      => 'floating',
				'bannerColor'      => '#34495e',
				'primaryColor'     => '#3498db',
				'textColor'        => '#ffffff',
				'headingColor'     => '#ffffff',
				'btnTextColor'     => '#ffffff',
				'btnBorderRadius'  => '25px',
				'animation'        => 'fade',
				'bannerOpacity'    => 0.97,
				'revokePosition'   => 'bottom-left',
				'showBulletPoints' => true,
			],
			'compact' => [
				'position'         => 'top',
				'displayType'      => 'floating',
				'bannerColor'      => '#1a1a2e',
				'primaryColor'     => '#e67e22',
				'textColor'        => '#ffffff',
				'headingColor'     => '#ffffff',
				'btnTextColor'     => '#ffffff',
				'btnBorderRadius'  => '6px',
				'animation'        => 'slide',
				'bannerOpacity'    => 1.0,
				'revokePosition'   => 'bottom-right',
				'showBulletPoints' => false,
			],
		];

		if ( ! isset( $presets[ $template ] ) ) {
			wp_send_json_error( [ 'error' => 'Invalid template name.' ] );
		}

		$preset = $presets[ $template ];

		// Build design object for quick_config (exclude displayType — WP option, not portal field)
		$design = new stdClass();

		foreach ( $preset as $key => $value ) {
			if ( $key === 'displayType' )
				continue;

			$design->{$key} = $value;
		}

		$params = [
			'AppID'           => $app_id,
			'DefaultLanguage' => 'en',
			'text'            => (object) [ 'privacyPolicyUrl' => get_privacy_policy_url() ],
			'design'          => $design,
		];

		$write_type = $this->get_write_request_type( $app_id );

		// PATCH /by-app endpoint does not accept DefaultLanguage -- strip it.
		if ( $write_type === 'patch_by_app' ) {
			unset( $params['DefaultLanguage'] );
		}
		// DevMode mock ID — return synthetic success so the UI can be tested without a real API.
		if ( $write_type === 'devmode' ) {
			$network = $cn->is_network_admin();

			// Merge visual design fields (position, displayType, colors) from preset.
			// #2265: API-owned fields write to cookie_notice_app_design only — never cookie_notice_options.
			$existing_design = $network
				? get_site_option( 'cookie_notice_app_design', [] )
				: get_option( 'cookie_notice_app_design', [] );

			$updated_design = array_merge( $existing_design, [
				'position'     => $preset['position'],
				'displayType'  => $preset['displayType'],
				'bannerColor'  => $preset['bannerColor'],
				'primaryColor' => $preset['primaryColor'],
			] );

			if ( $network ) {
				update_site_option( 'cookie_notice_app_design', $updated_design );
			} else {
				update_option( 'cookie_notice_app_design', $updated_design, false );
			}

			wp_send_json_success( [ 'status' => 200, 'template' => $template, 'dev_mode' => true ] );
			return;
		}

		$result = $this->request( $write_type, $params );

		// Design record not yet created — fall back to quick_config to seed it.
		// The API returns { i18n_msg: 'user_design_update_id_not_found', status: 400 } (HTTP 200)
		// when no record exists, so check i18n_msg — not statusCode/404.
		// Also restore DefaultLanguage which patch_by_app doesn't accept but quick_config requires.
		if ( is_object( $result ) && isset( $result->i18n_msg ) && $result->i18n_msg === 'user_design_update_id_not_found' ) {
			$params['DefaultLanguage'] = 'en';
			$result = $this->request( 'quick_config', $params );
		}

		if ( is_object( $result ) && isset( $result->status ) && $result->status === 200 ) {
			// #2265: API-owned fields write to cookie_notice_app_design only — never cookie_notice_options.
			$network = $cn->is_network_admin();

			// Merge visual design fields (position, displayType, colors) from preset.
			$existing_design = $network
				? get_site_option( 'cookie_notice_app_design', [] )
				: get_option( 'cookie_notice_app_design', [] );

			$updated_design = array_merge( $existing_design, [
				'position'     => $preset['position'],
				'displayType'  => $preset['displayType'],
				'bannerColor'  => $preset['bannerColor'],
				'primaryColor' => $preset['primaryColor'],
			] );

			if ( $network ) {
				update_site_option( 'cookie_notice_app_design', $updated_design );
			} else {
				update_option( 'cookie_notice_app_design', $updated_design, false );
			}

			// Pull confirmed state from portal — makes portal unambiguous SoT.
			// Updates cookie_notice_app_blocking, cookie_notice_app_regulations,
			// cookie_notice_app_design, cookie_notice_status.
			// Fires cn_configuration_updated → clears page caches (WP Rocket etc).
			// Does NOT set cookie_notice_config_update transient (widget CDN cache).
			$this->get_app_config( $app_id, true, true );

			// Re-assert preset design values after get_app_config() — the portal may return
			// empty position/color fields (BannerConfigJSON cherry-picks) which would
			// overwrite our just-saved preset and cause matchTemplate() to return null
			// on the next page load ("No template" false negative — #2261).
			// This write is authoritative: we know what template was just applied.
			if ( $network )
				update_site_option( 'cookie_notice_app_design', $updated_design );
			else
				update_option( 'cookie_notice_app_design', $updated_design, false );

			wp_send_json_success( [ 'status' => 200, 'template' => $template ] );
		} else {
			$error = 'Template apply failed.';

			if ( is_array( $result ) && ! empty( $result['error'] ) )
				$error = $result['error'];
			elseif ( is_object( $result ) && ! empty( $result->message ) )
				$error = $result->message;
			elseif ( is_object( $result ) && ! empty( $result->error ) )
				$error = $result->error;
			elseif ( is_object( $result ) && ! empty( $result->i18n_msg ) )
				$error = 'API error: ' . $result->i18n_msg;
			elseif ( $result === null )
				$error = 'No response from API — check connection.';

			wp_send_json_error( [ 'error' => $error, 'apiSync' => false ] );
		}
	}


/** Function rescan_scripts() called by wp_ajax hooks: {'cn_react_rescan_scripts'} **/
/** No params detected :-/ **/


/** Function get_group_rule_values() called by wp_ajax hooks: {'cn-get-group-rules-values'} **/
/** Parameters found in function get_group_rule_values(): {"post": ["action", "cn_param", "cn_nonce"]} **/
function get_group_rule_values() {
		if (
			isset( $_POST['action'], $_POST['cn_param'], $_POST['cn_nonce'] )
			&& wp_verify_nonce( $_POST['cn_nonce'], 'cn-get-group-values' ) !== false
			&& current_user_can( apply_filters( 'cn_manage_cookie_notice_cap', 'manage_options' ) )
		) {
			echo wp_json_encode(
				[
					'select'	=> $this->prepare_values( sanitize_key( $_POST['cn_param'] ) )
				]
			);
		}

		exit;
	}


