<?php
/***
*
*Found actions: 29
*Found functions:22
*Extracted functions:22
*Total parameter names extracted: 18
*Overview: {'seopress_dismiss_promotion': {'seopress_dismiss_promotion'}, 'seopress_cookies_user_consent_close': {'nopriv_seopress_cookies_user_consent_close', 'seopress_cookies_user_consent_close'}, 'seopress_seo_ultimate_migration': {'seopress_seo_ultimate_migration'}, 'seopress_after_update_cart': {'seopress_after_update_cart', 'nopriv_seopress_after_update_cart'}, 'proxy': {'seopress_metabox_proxy'}, 'seopress_yoast_migration': {'seopress_yoast_migration'}, 'seopress_toggle_features': {'seopress_toggle_features'}, 'process': {'seopress_aio_migration', 'seopress_siteseo_migration', 'seopress_rk_migration', 'seopress_surerank_migration'}, 'seopress_instant_indexing_generate_api_key': {'seopress_instant_indexing_generate_api_key'}, 'seopress_squirrly_migration': {'seopress_squirrly_migration'}, 'seopress_premium_seo_pack_migration': {'seopress_premium_seo_pack_migration'}, 'seopress_smart_crawl_migration': {'seopress_smart_crawl_migration'}, 'seopress_toggle_promotions': {'seopress_toggle_promotions'}, 'seopress_switch_view': {'seopress_switch_view'}, 'get': {'get_preview_meta_title', 'get_preview_meta_description'}, 'seopress_slim_seo_migration': {'seopress_slim_seo_migration'}, 'seopress_seo_framework_migration': {'seopress_seo_framework_migration'}, 'seopress_hide_notices': {'seopress_hide_notices'}, 'seopress_cookies_user_consent': {'nopriv_seopress_cookies_user_consent', 'seopress_cookies_user_consent'}, 'seopress_instant_indexing_post': {'seopress_instant_indexing_post'}, 'seopress_wp_meta_seo_migration': {'seopress_wp_meta_seo_migration'}, 'seopress_do_real_preview': {'seopress_do_real_preview'}}
*
***/

/** Function seopress_dismiss_promotion() called by wp_ajax hooks: {'seopress_dismiss_promotion'} **/
/** Parameters found in function seopress_dismiss_promotion(): {"post": ["promo_id", "duration"]} **/
function seopress_dismiss_promotion() {
	check_ajax_referer( 'seopress_dismiss_promotion_nonce', '_ajax_nonce', true );

	if ( ! current_user_can( seopress_capability( 'manage_options', 'dashboard' ) ) || ! is_admin() ) {
		wp_send_json_error( array( 'message' => __( 'Permission denied.', 'wp-seopress' ) ) );
	}

	$promo_id = isset( $_POST['promo_id'] ) ? sanitize_text_field( wp_unslash( $_POST['promo_id'] ) ) : '';
	$duration = isset( $_POST['duration'] ) ? absint( $_POST['duration'] ) : 30;

	if ( empty( $promo_id ) ) {
		wp_send_json_error( array( 'message' => __( 'Invalid promotion ID.', 'wp-seopress' ) ) );
	}

	// Use the PromotionService to dismiss the promotion.
	$result = seopress_get_service( 'PromotionService' )->dismissPromotion( $promo_id, $duration );

	if ( $result ) {
		wp_send_json_success( array( 'message' => __( 'Promotion dismissed.', 'wp-seopress' ) ) );
	} else {
		wp_send_json_error( array( 'message' => __( 'Failed to dismiss promotion.', 'wp-seopress' ) ) );
	}
}


/** Function seopress_cookies_user_consent_close() called by wp_ajax hooks: {'nopriv_seopress_cookies_user_consent_close', 'seopress_cookies_user_consent_close'} **/
/** No params detected :-/ **/


/** Function seopress_seo_ultimate_migration() called by wp_ajax hooks: {'seopress_seo_ultimate_migration'} **/
/** Parameters found in function seopress_seo_ultimate_migration(): {"post": ["offset"]} **/
function seopress_seo_ultimate_migration() {
	check_ajax_referer( 'seopress_seo_ultimate_migrate_nonce', '_ajax_nonce', true );

	if ( current_user_can( seopress_capability( 'manage_options', 'migration' ) ) && is_admin() ) {
		if ( isset( $_POST['offset'] ) && isset( $_POST['offset'] ) ) {
			$offset = absint( $_POST['offset'] );
		}

		global $wpdb;

		// phpcs:ignore
		$total_count_posts = (int) $wpdb->get_var( "SELECT count(*) FROM {$wpdb->posts}" );

		$increment = 200;
		global $post;

		if ( $offset > $total_count_posts ) {
			$offset = 'done';
			wp_reset_postdata();
		} else {
			$args = array(
				'posts_per_page' => $increment,
				'post_type'      => 'any',
				'post_status'    => 'any',
				'offset'         => $offset,
			);

			$su_query = get_posts( $args );

			if ( $su_query ) {
				foreach ( $su_query as $post ) {
					if ( '' !== get_post_meta( $post->ID, '_su_title', true ) ) { // Import title tag.
						update_post_meta( $post->ID, '_seopress_titles_title', esc_html( get_post_meta( $post->ID, '_su_title', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_su_description', true ) ) { // Import meta desc.
						update_post_meta( $post->ID, '_seopress_titles_desc', esc_html( get_post_meta( $post->ID, '_su_description', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_su_og_title', true ) ) { // Import Facebook Title.
						update_post_meta( $post->ID, '_seopress_social_fb_title', esc_html( get_post_meta( $post->ID, '_su_og_title', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_su_og_description', true ) ) { // Import Facebook Desc.
						update_post_meta( $post->ID, '_seopress_social_fb_desc', esc_html( get_post_meta( $post->ID, '_su_og_description', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_su_og_image', true ) ) { // Import Facebook Image.
						update_post_meta( $post->ID, '_seopress_social_fb_img', esc_url( get_post_meta( $post->ID, '_su_og_image', true ) ) );
					}
					if ( '1' === get_post_meta( $post->ID, '_su_meta_robots_noindex', true ) ) { // Import Robots NoIndex.
						update_post_meta( $post->ID, '_seopress_robots_index', 'yes' );
					}
					if ( '1' === get_post_meta( $post->ID, '_su_meta_robots_nofollow', true ) ) { // Import Robots NoFollow.
						update_post_meta( $post->ID, '_seopress_robots_follow', 'yes' );
					}
				}
			}
			$offset += $increment;
		}
		$data           = array();
		$data['offset'] = $offset;

		$data['total'] = $total_count_posts;

		if ( $offset >= $total_count_posts ) {
			$data['count'] = $total_count_posts;
		} else {
			$data['count'] = $offset;
		}

		wp_send_json_success( $data );
		exit();
	}
}


/** Function seopress_after_update_cart() called by wp_ajax hooks: {'seopress_after_update_cart', 'nopriv_seopress_after_update_cart'} **/
/** No params detected :-/ **/


/** Function proxy() called by wp_ajax hooks: {'seopress_metabox_proxy'} **/
/** Parameters found in function proxy(): {"request": ["route"], "server": ["REQUEST_METHOD"]} **/
function proxy() {
		check_ajax_referer( 'seopress_metabox_proxy', '_ajax_nonce' );

		// Baseline gate; each route still enforces its own permission_callback
		// (e.g. edit_post on the specific id) through rest_do_request().
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( array( 'message' => 'forbidden' ), 403 );
		}

		$route = isset( $_REQUEST['route'] ) ? (string) wp_unslash( $_REQUEST['route'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- validated below against a strict allow-list.

		$parts = explode( '?', $route, 2 );
		$path  = '/' . ltrim( $parts[0], '/' );

		// Hard allow-list: only this plugin's own namespace, only safe path
		// characters. Never proxy an arbitrary route.
		if ( ! preg_match( '#^/seopress/v[0-9]+/[A-Za-z0-9/_-]+$#', $path ) ) {
			wp_send_json_error( array( 'message' => 'invalid_route' ), 400 );
		}

		$method = isset( $_SERVER['REQUEST_METHOD'] ) ? strtoupper( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) ) : 'GET';
		if ( ! in_array( $method, array( 'GET', 'POST' ), true ) ) {
			$method = 'GET';
		}

		$request = new \WP_REST_Request( $method, $path );

		// Query string travels in the route tail (e.g. ?target_keywords=...).
		if ( isset( $parts[1] ) && '' !== $parts[1] ) {
			$query = array();
			wp_parse_str( $parts[1], $query );
			$request->set_query_params( $query );
		}

		// Forward the JSON body for writes (score save, ignore toggle...).
		if ( 'POST' === $method ) {
			// php://input is the request body, not a filesystem path, so
			// WP_Filesystem does not apply here.
			$body = file_get_contents( 'php://input' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			if ( ! empty( $body ) ) {
				$decoded = json_decode( $body, true );
				if ( is_array( $decoded ) ) {
					$request->set_header( 'Content-Type', 'application/json' );
					$request->set_body_params( $decoded );
				}
			}
		}

		$response = rest_do_request( $request );
		$server   = rest_get_server();
		$data     = $server->response_to_data( $response, false );

		wp_send_json( $data, $response->get_status() );
	}


/** Function seopress_yoast_migration() called by wp_ajax hooks: {'seopress_yoast_migration'} **/
/** Parameters found in function seopress_yoast_migration(): {"post": ["offset"]} **/
function seopress_yoast_migration() {
	check_ajax_referer( 'seopress_yoast_migrate_nonce', '_ajax_nonce', true );

	if ( current_user_can( seopress_capability( 'manage_options', 'migration' ) ) && is_admin() ) {
		if ( isset( $_POST['offset'] ) && isset( $_POST['offset'] ) ) {
			$offset = absint( $_POST['offset'] );
		}

		global $wpdb;
		// phpcs:ignore
		$total_count_posts = (int) $wpdb->get_var( "SELECT count(*) FROM {$wpdb->posts}" );
		// phpcs:ignore
		$total_count_terms = (int) $wpdb->get_var( "SELECT count(*) FROM {$wpdb->terms}" );

		$increment = 200;
		global $post;

		// === Import settings ===//
		// Import titles
		$wpseo             = get_option( 'wpseo' );
		$wpseo_titles      = get_option( 'wpseo_titles' );
		$wpseo_social      = get_option( 'wpseo_social' );
		$seopress_titles   = get_option( 'seopress_titles_option_name' );
		$seopress_social   = get_option( 'seopress_social_option_name' );
		$seopress_advanced = get_option( 'seopress_advanced_option_name' );
		$seopress_pro      = get_option( 'seopress_pro_option_name' );

		if ( ! empty( $wpseo ) ) {
			foreach ( $wpseo as $key => $value ) {
				if ( 'googleverify' === $key ) {
					$seopress_advanced['seopress_advanced_advanced_google'] = esc_html( $value );
				}
				if ( 'msverify' === $key ) {
					$seopress_advanced['seopress_advanced_advanced_bing'] = esc_html( $value );
				}
				if ( 'yandexverify' === $key ) {
					$seopress_advanced['seopress_advanced_advanced_yandex'] = esc_html( $value );
				}
				if ( 'baiduverify' === $key ) {
					$seopress_advanced['seopress_advanced_advanced_baidu'] = esc_html( $value );
				}
				if ( 'remove_shortlinks' === $key ) {
					if ( true === $value ) {
						$seopress_advanced['seopress_advanced_advanced_wp_shortlink'] = '1';
					} else {
						unset( $seopress_advanced['seopress_advanced_advanced_wp_shortlink'] );
					}
				}
				if ( 'remove_rsd_wlw_links' === $key ) {
					if ( true === $value ) {
						$seopress_advanced['seopress_advanced_advanced_wp_rsd'] = '1';
						$seopress_advanced['seopress_advanced_advanced_wp_wlw'] = '1';
					} else {
						unset( $seopress_advanced['seopress_advanced_advanced_wp_rsd'] );
						unset( $seopress_advanced['seopress_advanced_advanced_wp_wlw'] );
					}
				}
				if ( 'remove_oembed_links' === $key ) {
					if ( true === $value ) {
						$seopress_advanced['seopress_advanced_advanced_wp_oembed'] = '1';
					} else {
						unset( $seopress_advanced['seopress_advanced_advanced_wp_oembed'] );
					}
				}
				if ( 'remove_generator' === $key ) {
					if ( true === $value ) {
						$seopress_advanced['seopress_advanced_advanced_wp_generator'] = '1';
					} else {
						unset( $seopress_advanced['seopress_advanced_advanced_wp_generator'] );
					}
				}
				if ( 'remove_pingback_header' === $key ) {
					if ( true === $value ) {
						$seopress_advanced['seopress_advanced_advanced_wp_x_pingback'] = '1';
					} else {
						unset( $seopress_advanced['seopress_advanced_advanced_wp_x_pingback'] );
					}
				}
				if ( 'remove_powered_by_header' === $key ) {
					if ( true === $value ) {
						$seopress_advanced['seopress_advanced_advanced_wp_x_powered_by'] = '1';
					} else {
						unset( $seopress_advanced['seopress_advanced_advanced_wp_x_powered_by'] );
					}
				}
				if ( 'remove_emoji_scripts' === $key ) {
					if ( true === $value ) {
						$seopress_advanced['seopress_advanced_advanced_emoji'] = '1';
					} else {
						unset( $seopress_advanced['seopress_advanced_advanced_emoji'] );
					}
				}
			}

			// RSS / feed crawl optimization.
			$seopress_pro = seopress_yoast_map_feed_settings( $wpseo, $seopress_pro );
		}

		if ( ! empty( $wpseo_titles ) ) {
			foreach ( $wpseo_titles as $key => $value ) {
				if ( 'separator' === $key ) {
					$separator = array(
						'sc-dash'   => '-',
						'sc-ndash'  => '&ndash;',
						'sc-mdash'  => '&mdash;',
						'sc-colon'  => ':',
						'sc-middot' => '&middot;',
						'sc-bull'   => '&bull;',
						'sc-star'   => '*',
						'sc-smstar' => '&#8902;',
						'sc-pipe'   => '|',
						'sc-tilde'  => '~',
						'sc-laquo'  => '&laquo;',
						'sc-raquo'  => '&raquo;',
						'sc-gt'     => '&lt;', // For some reason, the separator is reversed.
						'sc-lt'     => '&gt;', // For some reason, the separator is reversed.
					);

					$seopress_titles['seopress_titles_sep'] = esc_html( $separator[ $value ] );
				}
				if ( 'website_name' === $key ) {
					$seopress_titles['seopress_titles_home_site_title'] = esc_html( $value );
				}
				if ( 'alternate_website_name' === $key ) {
					$seopress_titles['seopress_titles_home_site_title_alt'] = esc_html( $value );
				}
				if ( 'metadesc-home-wpseo' === $key ) {
					$seopress_titles['seopress_titles_home_site_desc'] = esc_html( $value );
				}
				if ( 'company_or_person' === $key ) {
					$type = array(
						'company' => 'Organization',
						'person'  => 'Person',
					);
					$seopress_social['seopress_social_knowledge_type'] = esc_html( $type[ $value ] );
				}
				if ( 'company_name' === $key ) {
					$seopress_social['seopress_social_knowledge_name'] = esc_html( $value );
				}
				if ( 'company_logo' === $key ) {
					$seopress_social['seopress_social_knowledge_img'] = esc_url( $value );
				}
				// Breadcrumbs.
				if ( 'breadcrumbs-enable' === $key ) {
					if ( true === $value ) {
						$seopress_pro['seopress_breadcrumbs_enable']      = '1';
						$seopress_pro['seopress_breadcrumbs_json_enable'] = '1';
					} else {
						unset( $seopress_pro['seopress_breadcrumbs_enable'] );
						unset( $seopress_pro['seopress_breadcrumbs_json_enable'] );
					}
				}
				if ( 'breadcrumbs-sep' === $key ) {
					$seopress_pro['seopress_breadcrumbs_separator'] = esc_html( $value );
				}
				if ( 'breadcrumbs-home' === $key ) {
					$seopress_pro['seopress_breadcrumbs_i18n_home'] = esc_html( $value );
				}
				if ( 'breadcrumbs-prefix' === $key ) {
					$seopress_pro['seopress_breadcrumbs_i18n_here'] = esc_html( $value );
				}
				if ( 'breadcrumbs-searchprefix' === $key ) {
					$seopress_pro['seopress_breadcrumbs_i18n_search'] = esc_html( $value );
				}
				if ( 'breadcrumbs-404crumb' === $key ) {
					$seopress_pro['seopress_breadcrumbs_i18n_404'] = esc_html( $value );
				}
				if ( 'breadcrumbs-display-blog-page' === $key ) {
					if ( true === $value ) {
						unset( $seopress_pro['seopress_breadcrumbs_remove_blog_page'] );
					} else {
						$seopress_pro['seopress_breadcrumbs_remove_blog_page'] = '1';
					}
				}
				// RSS Feeds.
				if ( 'rssbefore' === $key || 'rssafter' === $key ) {
					$rss_vars = array(
						'%%AUTHORLINK%%'   => '<a href="%%author_permalink%%">%%post_author%%</a>',
						'%%POSTLINK%%'     => '<a href="%%post_permalink%%">%%post_title%%</a>',
						'%%BLOGLINK%%'     => '<a href="' . get_bloginfo( 'url' ) . '">' . get_bloginfo( 'name' ) . '</a>',
						'%%BLOGDESCLINK%%' => '<a href="' . get_bloginfo( 'url' ) . '">' . get_bloginfo( 'name' ) . ' ' . get_bloginfo( 'description' ) . '</a>',
					);
					$value    = str_replace( array_keys( $rss_vars ), array_values( $rss_vars ), $value );
				}
				if ( 'rssbefore' === $key ) {
					$args                                     = array(
						'strong' => array(),
						'em'     => array(),
						'br'     => array(),
						'a'      => array(
							'href' => array(),
							'rel'  => array(),
						),
					);
					$seopress_pro['seopress_rss_before_html'] = wp_kses( $value, $args );
				}
				if ( 'rssafter' === $key ) {
					$args                                    = array(
						'strong' => array(),
						'em'     => array(),
						'br'     => array(),
						'a'      => array(
							'href' => array(),
							'rel'  => array(),
						),
					);
					$seopress_pro['seopress_rss_after_html'] = wp_kses( $value, $args );
				}

				// Import CPT settings.
				$post_types = seopress_get_service( 'WordPressData' )->getPostTypes();
				foreach ( $post_types as $seopress_cpt_key => $seopress_cpt_value ) {
					// Single title.
					if ( 'title-' . $seopress_cpt_key === $key ) {
						$seopress_titles['seopress_titles_single_titles'][ $seopress_cpt_key ]['title'] = esc_html( $value );
					}
					// Single description.
					if ( 'metadesc-' . $seopress_cpt_key === $key ) {
						$seopress_titles['seopress_titles_single_titles'][ $seopress_cpt_key ]['description'] = esc_html( $value );
					}
					// Single noindex.
					if ( 'noindex-' . $seopress_cpt_key === $key ) {
						unset( $seopress_titles['seopress_titles_single_titles'][ $seopress_cpt_key ]['noindex'] );
						if ( true === $value ) {
							$seopress_titles['seopress_titles_single_titles'][ $seopress_cpt_key ]['noindex'] = '1';
						}
					}
					// Single Enable.
					if ( 'display-metabox-pt-' . $seopress_cpt_key === $key ) {
						$seopress_titles['seopress_titles_single_titles'][ $seopress_cpt_key ]['enable'] = '1';
						if ( true === $value ) {
							unset( $seopress_titles['seopress_titles_single_titles'][ $seopress_cpt_key ]['enable'] );
						}
					}
					// Breadcrumbs.
					if ( 'post_types-' . $seopress_cpt_key . '-maintax' === $key ) {
						$seopress_pro['seopress_breadcrumbs_tax'][ $seopress_cpt_key ]['tax'] = esc_html( $value );
					}
				}
				// Import taxonomies settings.
				$taxonomies = seopress_get_service( 'WordPressData' )->getTaxonomies();
				foreach ( $taxonomies as $seopress_tax_key => $seopress_tax_value ) {
					// Tax title.
					if ( 'title-tax-' . $seopress_tax_key === $key ) {
						$seopress_titles['seopress_titles_tax_titles'][ $seopress_tax_key ]['title'] = esc_html( $value );
					}
					// Tax description.
					if ( 'metadesc-tax-' . $seopress_tax_key === $key ) {
						$seopress_titles['seopress_titles_tax_titles'][ $seopress_tax_key ]['description'] = esc_html( $value );
					}
					// Tax noindex.
					if ( 'noindex-tax-' . $seopress_tax_key === $key ) {
						unset( $seopress_titles['seopress_titles_tax_titles'][ $seopress_tax_key ]['noindex'] );
						if ( true === $value ) {
							$seopress_titles['seopress_titles_tax_titles'][ $seopress_tax_key ]['noindex'] = '1';
						}
					}
					// Tax Enable.
					if ( 'display-metabox-tax-' . $seopress_tax_key === $key ) {
						$seopress_titles['seopress_titles_tax_titles'][ $seopress_tax_key ]['enable'] = '1';
						if ( true === $value ) {
							unset( $seopress_titles['seopress_titles_tax_titles'][ $seopress_tax_key ]['enable'] );
						}
					}
					// Breadcrumbs.
					if ( 'taxonomy-' . $seopress_tax_key . '-ptparent' === $key ) {
						$seopress_pro['seopress_breadcrumbs_cpt'][ $seopress_tax_key ]['cpt'] = esc_html( $value );
					}
				}
				// 404.
				if ( 'title-404-wpseo' === $key ) {
					$seopress_titles['seopress_titles_archives_404_title'] = esc_html( $value );
				}
				// Internal search.
				if ( 'title-search-wpseo' === $key ) {
					$seopress_titles['seopress_titles_archives_search_title'] = esc_html( $value );
				}
				// Date archive.
				if ( 'disable-date' === $key ) {
					if ( true === $value ) {
						$seopress_titles['seopress_titles_archives_date_disable'] = '1';
					} else {
						unset( $seopress_titles['seopress_titles_archives_date_disable'] );
					}
				}
				if ( 'noindex-archive-wpseo' === $key ) {
					if ( true === $value ) {
						$seopress_titles['seopress_titles_archives_date_noindex'] = '1';
					} else {
						unset( $seopress_titles['seopress_titles_archives_date_noindex'] );
					}
				}
				if ( 'title-archive-wpseo' === $key ) {
					$seopress_titles['seopress_titles_archives_date_title'] = esc_html( $value );
				}
				if ( 'metadesc-archive-wpseo' === $key ) {
					$seopress_titles['seopress_titles_archives_date_desc'] = esc_html( $value );
				}
				// Author.
				if ( 'disable-author' === $key ) {
					if ( true === $value ) {
						$seopress_titles['seopress_titles_archives_author_disable'] = '1';
					} else {
						unset( $seopress_titles['seopress_titles_archives_author_disable'] );
					}
				}
				if ( 'noindex-author-wpseo' === $key ) {
					if ( true === $value ) {
						$seopress_titles['seopress_titles_archives_author_noindex'] = '1';
					} else {
						unset( $seopress_titles['seopress_titles_archives_author_noindex'] );
					}
				}
				if ( 'title-author-wpseo' === $key ) {
					$seopress_titles['seopress_titles_archives_author_title'] = esc_html( $value );
				}
				if ( 'metadesc-author-wpseo' === $key ) {
					$seopress_titles['seopress_titles_archives_author_desc'] = esc_html( $value );
				}
			}
		}

		// Attachment redirect.
		$seopress_advanced = seopress_yoast_map_attachment_redirect( $wpseo_titles, $seopress_advanced );

		// Import social.
		if ( ! empty( $wpseo_social ) ) {
			$seopress_social = seopress_yoast_map_social_accounts( $wpseo_social, $seopress_social );

			if ( isset( $wpseo_social['pinterestverify'] ) ) {
				$seopress_advanced['seopress_advanced_advanced_pinterest'] = esc_html( $wpseo_social['pinterestverify'] );
			}
		}

		update_option( 'seopress_titles_option_name', $seopress_titles, false );
		update_option( 'seopress_social_option_name', $seopress_social, false );
		update_option( 'seopress_advanced_option_name', $seopress_advanced, false );
		update_option( 'seopress_pro_option_name', $seopress_pro, false );

		// Import terms.
		if ( $offset > $total_count_posts ) {
			wp_reset_postdata();

			$yoast_query_terms = get_option( 'wpseo_taxonomy_meta' );

			if ( $yoast_query_terms ) {
				foreach ( $yoast_query_terms as $taxonomies => $taxonomie ) {
					foreach ( $taxonomie as $term_id => $term_value ) {
						if ( ! empty( $term_value['wpseo_title'] ) ) { // Import title tag.
							update_term_meta( $term_id, '_seopress_titles_title', esc_html( $term_value['wpseo_title'] ) );
						}
						if ( ! empty( $term_value['wpseo_desc'] ) ) { // Import meta desc.
							update_term_meta( $term_id, '_seopress_titles_desc', esc_html( $term_value['wpseo_desc'] ) );
						}
						if ( ! empty( $term_value['wpseo_opengraph-title'] ) ) { // Import Facebook Title.
							update_term_meta( $term_id, '_seopress_social_fb_title', esc_html( $term_value['wpseo_opengraph-title'] ) );
						}
						if ( ! empty( $term_value['wpseo_opengraph-description'] ) ) { // Import Facebook Desc.
							update_term_meta( $term_id, '_seopress_social_fb_desc', esc_html( $term_value['wpseo_opengraph-description'] ) );
						}
						if ( ! empty( $term_value['wpseo_opengraph-image'] ) ) { // Import Facebook Image.
							update_term_meta( $term_id, '_seopress_social_fb_img', esc_url( $term_value['wpseo_opengraph-image'] ) );
						}
						if ( ! empty( $term_value['wpseo_twitter-title'] ) ) { // Import Twitter Title.
							update_term_meta( $term_id, '_seopress_social_twitter_title', esc_html( $term_value['wpseo_twitter-title'] ) );
						}
						if ( ! empty( $term_value['wpseo_twitter-description'] ) ) { // Import Twitter Desc.
							update_term_meta( $term_id, '_seopress_social_twitter_desc', esc_html( $term_value['wpseo_twitter-description'] ) );
						}
						if ( ! empty( $term_value['wpseo_twitter-image'] ) ) { // Import Twitter Image.
							update_term_meta( $term_id, '_seopress_social_twitter_img', esc_url( $term_value['wpseo_twitter-image'] ) );
						}
						if ( isset( $term_value['wpseo_noindex'] ) && 'noindex' === $term_value['wpseo_noindex'] ) { // Import Robots NoIndex.
							update_term_meta( $term_id, '_seopress_robots_index', 'yes' );
						}
						if ( ! empty( $term_value['wpseo_canonical'] ) ) { // Import Canonical URL.
							update_term_meta( $term_id, '_seopress_robots_canonical', esc_url( $term_value['wpseo_canonical'] ) );
						}
						if ( ! empty( $term_value['wpseo_bctitle'] ) ) { // Import Breadcrumb Title.
							update_term_meta( $term_id, '_seopress_robots_breadcrumbs', esc_html( $term_value['wpseo_bctitle'] ) );
						}
					}
				}
			}
			$offset = 'done';
			wp_reset_postdata();
		} else {
			// Import posts.
			$args = array(
				'posts_per_page' => $increment,
				'post_type'      => 'any',
				'post_status'    => 'any',
				'offset'         => $offset,
			);

			$yoast_query = get_posts( $args );

			if ( $yoast_query ) {
				foreach ( $yoast_query as $post ) {
					if ( '' !== get_post_meta( $post->ID, '_yoast_wpseo_title', true ) ) { // Import title tag.
						update_post_meta( $post->ID, '_seopress_titles_title', esc_html( get_post_meta( $post->ID, '_yoast_wpseo_title', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_yoast_wpseo_metadesc', true ) ) { // Import meta desc.
						update_post_meta( $post->ID, '_seopress_titles_desc', esc_html( get_post_meta( $post->ID, '_yoast_wpseo_metadesc', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_yoast_wpseo_opengraph-title', true ) ) { // Import Facebook Title.
						update_post_meta( $post->ID, '_seopress_social_fb_title', esc_html( get_post_meta( $post->ID, '_yoast_wpseo_opengraph-title', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_yoast_wpseo_opengraph-description', true ) ) { // Import Facebook Desc.
						update_post_meta( $post->ID, '_seopress_social_fb_desc', esc_html( get_post_meta( $post->ID, '_yoast_wpseo_opengraph-description', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_yoast_wpseo_opengraph-image', true ) ) { // Import Facebook Image.
						update_post_meta( $post->ID, '_seopress_social_fb_img', esc_url( get_post_meta( $post->ID, '_yoast_wpseo_opengraph-image', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_yoast_wpseo_twitter-title', true ) ) { // Import Twitter Title.
						update_post_meta( $post->ID, '_seopress_social_twitter_title', esc_html( get_post_meta( $post->ID, '_yoast_wpseo_twitter-title', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_yoast_wpseo_twitter-description', true ) ) { // Import Twitter Desc.
						update_post_meta( $post->ID, '_seopress_social_twitter_desc', esc_html( get_post_meta( $post->ID, '_yoast_wpseo_twitter-description', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_yoast_wpseo_twitter-image', true ) ) { // Import Twitter Image.
						update_post_meta( $post->ID, '_seopress_social_twitter_img', esc_url( get_post_meta( $post->ID, '_yoast_wpseo_twitter-image', true ) ) );
					}
					if ( '1' === get_post_meta( $post->ID, '_yoast_wpseo_meta-robots-noindex', true ) ) { // Import Robots NoIndex.
						update_post_meta( $post->ID, '_seopress_robots_index', 'yes' );
					}
					if ( '1' === get_post_meta( $post->ID, '_yoast_wpseo_meta-robots-nofollow', true ) ) { // Import Robots NoFollow.
						update_post_meta( $post->ID, '_seopress_robots_follow', 'yes' );
					}
					if ( '' !== get_post_meta( $post->ID, '_yoast_wpseo_meta-robots-adv', true ) ) { // Import Robots NoImageIndex, NoSnippet.
						$yoast_wpseo_meta_robots_adv = get_post_meta( $post->ID, '_yoast_wpseo_meta-robots-adv', true );

						if ( false !== strpos( $yoast_wpseo_meta_robots_adv, 'noimageindex' ) ) {
							update_post_meta( $post->ID, '_seopress_robots_imageindex', 'yes' );
						}
						if ( false !== strpos( $yoast_wpseo_meta_robots_adv, 'nosnippet' ) ) {
							update_post_meta( $post->ID, '_seopress_robots_snippet', 'yes' );
						}
					}
					if ( '' !== get_post_meta( $post->ID, '_yoast_wpseo_canonical', true ) ) { // Import Canonical URL.
						update_post_meta( $post->ID, '_seopress_robots_canonical', esc_url( get_post_meta( $post->ID, '_yoast_wpseo_canonical', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_yoast_wpseo_bctitle', true ) ) { // Import Breadcrumb Title.
						update_post_meta( $post->ID, '_seopress_robots_breadcrumbs', esc_html( get_post_meta( $post->ID, '_yoast_wpseo_bctitle', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_yoast_wpseo_focuskw', true ) || '' !== get_post_meta( $post->ID, '_yoast_wpseo_focuskeywords', true ) ) { // Import Focus Keywords.
						$y_fkws_clean = array(); // reset array.

						// Handle _yoast_wpseo_focuskeywords (JSON array or empty).
						$focuskeywords_meta = get_post_meta( $post->ID, '_yoast_wpseo_focuskeywords', true );
						if ( ! empty( $focuskeywords_meta ) && is_string( $focuskeywords_meta ) ) {
							$decoded = json_decode( $focuskeywords_meta );
							if ( is_array( $decoded ) ) {
								foreach ( $decoded as $decoded_kw ) {
									if ( isset( $decoded_kw->keyword ) && '' !== $decoded_kw->keyword ) {
										$y_fkws_clean[] = esc_html( $decoded_kw->keyword );
									}
								}
							}
						}

						// Handle _yoast_wpseo_focuskw (string or empty).
						$focuskw = get_post_meta( $post->ID, '_yoast_wpseo_focuskw', true );
						if ( ! empty( $focuskw ) && is_string( $focuskw ) ) {
							$y_fkws_clean[] = esc_html( $focuskw );
						}

						// Save if we have any keywords.
						if ( ! empty( $y_fkws_clean ) ) {
							update_post_meta( $post->ID, '_seopress_analysis_target_kw', implode( ',', $y_fkws_clean ) );
						}
					}

					// Primary category.
					if ( class_exists( 'WPSEO_Primary_Term' ) ) {
						if ( 'product' === get_post_type( $post->ID ) ) {
							$tax = 'product_cat';
						} else {
							$tax = 'category';
						}

						$primary_term = new WPSEO_Primary_Term( $tax, $post->ID );

						$primary_term = absint( $primary_term->get_primary_term() );

						if ( '' !== $primary_term && is_int( $primary_term ) ) {
							update_post_meta( $post->ID, '_seopress_robots_primary_cat', $primary_term );
						}
					}
				}
			}
			$offset += $increment;
		}
		$data = array();

		$data['total'] = $total_count_posts;

		if ( $offset >= $total_count_posts ) {
			$data['count'] = $total_count_posts;
		} else {
			$data['count'] = $offset;
		}

		$data['offset'] = $offset;
		wp_send_json_success( $data );
		exit();
	}
}


/** Function seopress_toggle_features() called by wp_ajax hooks: {'seopress_toggle_features'} **/
/** Parameters found in function seopress_toggle_features(): {"post": ["feature", "feature_value"]} **/
function seopress_toggle_features() {
	check_ajax_referer( 'seopress_toggle_features_nonce', '_ajax_nonce', true );

	if ( current_user_can( seopress_capability( 'manage_options', 'dashboard' ) ) && is_admin() ) {
		if ( isset( $_POST['feature'] ) && isset( $_POST['feature_value'] ) ) {
			$feature       = sanitize_text_field( wp_unslash( $_POST['feature'] ) );
			$feature_value = sanitize_text_field( wp_unslash( $_POST['feature_value'] ) );

			if ( 'toggle-universal-metabox' === $feature ) {
				// Since 9.8.0 the universal metabox is always-on; the only related
				// surface that can still be toggled is the frontend SEO beacon.
				// Tile ON ($feature_value === '1') = beacon visible on frontend
				// (..._disable_frontend = '0'); tile OFF = beacon hidden ('1').
				$seopress_advanced_option_name = get_option( 'seopress_advanced_option_name' );
				if ( ! is_array( $seopress_advanced_option_name ) ) {
					$seopress_advanced_option_name = array();
				}
				$seopress_advanced_option_name['seopress_advanced_appearance_universal_metabox_disable_frontend'] = ( '1' === $feature_value ) ? '0' : '1';
				update_option( 'seopress_advanced_option_name', $seopress_advanced_option_name, false );
			} else {
				$seopress_toggle_options = get_option( 'seopress_toggle', array() );
				if ( ! is_array( $seopress_toggle_options ) ) {
					$seopress_toggle_options = array();
				}
				$seopress_toggle_options[ $feature ] = $feature_value;

				update_option( 'seopress_toggle', $seopress_toggle_options, false );

				// Flush permalinks when toggling features that register rewrite rules.
				// flush_rewrite_rules() rebuilds the rules from $wp_rewrite->extra_rules_top,
				// which was populated at init() based on the OLD toggle value. We must purge
				// the stale rules and re-register them with the NEW value before flushing,
				// otherwise the toggle has no effect until the next request triggers another flush.
				if ( 'toggle-xml-sitemap' === $feature || 'toggle-news' === $feature ) {
					global $wp_rewrite;

					if ( ! empty( $wp_rewrite->extra_rules_top ) ) {
						foreach ( $wp_rewrite->extra_rules_top as $pattern => $query ) {
							if ( false !== strpos( $query, 'seopress_' ) ) {
								unset( $wp_rewrite->extra_rules_top[ $pattern ] );
							}
						}
					}

					$sitemap_options = get_option( 'seopress_xml_sitemap_option_name' );
					\SEOPress\Actions\Sitemap\Router::registerRewriteRules( $sitemap_options, $seopress_toggle_options );

					// Let PRO and extensions re-register their sitemap rewrite rules (news, video, ...).
					do_action( 'seopress_re_register_sitemap_rules', $sitemap_options, $seopress_toggle_options );

					delete_option( 'rewrite_rules' );
					flush_rewrite_rules( false );
				}
			}
		}
		exit();
	}
}


/** Function process() called by wp_ajax hooks: {'seopress_aio_migration', 'seopress_siteseo_migration', 'seopress_rk_migration', 'seopress_surerank_migration'} **/
/** Parameters found in function process(): {"post": ["offset"]} **/
function process() {
		check_ajax_referer( 'seopress_aio_migrate_nonce', '_ajax_nonce', true );
		if ( ! is_admin() ) {
			wp_send_json_error();

			return;
		}

		if ( ! current_user_can( seopress_capability( 'manage_options', 'migration' ) ) ) { // phpcs:ignore
			wp_send_json_error();

			return;
		}

		$this->migrateSettings();

		if ( isset( $_POST['offset'] ) ) {
			$offset = absint( $_POST['offset'] );
		}

		global $wpdb;
		$total_count_posts = (int) $wpdb->get_var( "SELECT count(*) FROM {$wpdb->posts}" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

		$increment = 200;
		global $post;

		if ( $offset > $total_count_posts ) {
			$offset = 'done';
		} else {
			$offset = $this->migratePostQuery( $offset, $increment );
		}

		$data          = array();
		$data['total'] = $total_count_posts;

		if ( $offset >= $total_count_posts ) {
			$data['count'] = $total_count_posts;
		} else {
			$data['count'] = $offset;
		}
		$data['offset'] = $offset;

		do_action( 'seopress_third_importer_aio', $offset, $increment );

		wp_send_json_success( $data );
		exit();
	}


/** Function seopress_instant_indexing_generate_api_key() called by wp_ajax hooks: {'seopress_instant_indexing_generate_api_key'} **/
/** No params detected :-/ **/


/** Function seopress_squirrly_migration() called by wp_ajax hooks: {'seopress_squirrly_migration'} **/
/** Parameters found in function seopress_squirrly_migration(): {"post": ["offset"]} **/
function seopress_squirrly_migration() {
	check_ajax_referer( 'seopress_squirrly_migrate_nonce', '_ajax_nonce', true );

	if ( current_user_can( seopress_capability( 'manage_options', 'migration' ) ) && is_admin() ) {
		if ( isset( $_POST['offset'] ) && isset( $_POST['offset'] ) ) {
			$offset = absint( $_POST['offset'] );
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'qss';

		$blog_id = get_current_blog_id();

		$count_query = $wpdb->get_results( "SELECT * FROM $table_name WHERE blog_id = $blog_id", ARRAY_A );

		if ( ! empty( $count_query ) ) {
			foreach ( $count_query as $value ) {
				$post_id = url_to_postid( $value['URL'] );

				if ( 0 != $post_id && ! empty( $value['seo'] ) ) {
					$seo = maybe_unserialize( $value['seo'] );

					if ( '' !== $seo['title'] ) { // Import title tag.
						update_post_meta( $post_id, '_seopress_titles_title', esc_html( $seo['title'] ) );
					}
					if ( '' !== $seo['description'] ) { // Import description tag.
						update_post_meta( $post_id, '_seopress_titles_desc', esc_html( $seo['description'] ) );
					}
					if ( '' !== $seo['og_title'] ) { // Import Facebook Title.
						update_post_meta( $post_id, '_seopress_social_fb_title', esc_html( $seo['og_title'] ) );
					}
					if ( '' !== $seo['og_description'] ) { // Import Facebook Desc.
						update_post_meta( $post_id, '_seopress_social_fb_desc', esc_html( $seo['og_description'] ) );
					}
					if ( '' !== $seo['og_media'] ) { // Import Facebook Image.
						update_post_meta( $post_id, '_seopress_social_fb_img', esc_url( $seo['og_media'] ) );
					}
					if ( '' !== $seo['tw_title'] ) { // Import Twitter Title.
						update_post_meta( $post_id, '_seopress_social_twitter_title', esc_html( $seo['tw_title'] ) );
					}
					if ( '' !== $seo['tw_description'] ) { // Import Twitter Desc.
						update_post_meta( $post_id, '_seopress_social_twitter_desc', esc_html( $seo['tw_description'] ) );
					}
					if ( '' !== $seo['tw_media'] ) { // Import Twitter Image.
						update_post_meta( $post_id, '_seopress_social_twitter_img', esc_url( $seo['tw_media'] ) );
					}
					if ( 1 === $seo['noindex'] ) { // Import noindex.
						update_post_meta( $post_id, '_seopress_robots_index', 'yes' );
					}
					if ( 1 === $seo['nofollow'] ) { // Import nofollow.
						update_post_meta( $post_id, '_seopress_robots_follow', 'yes' );
					}
					if ( '' !== $seo['canonical'] ) { // Import canonical.
						update_post_meta( $post_id, '_seopress_robots_canonical', esc_url( $seo['canonical'] ) );
					}
				}
			}
			$offset = 'done';
		}
		$data = array();

		$data['offset'] = $offset;

		$data['total'] = count( $count_query );

		if ( $offset >= $data['total'] ) {
			$data['count'] = $data['total'];
		} else {
			$data['count'] = $offset;
		}

		wp_send_json_success( $data );
		exit();
	}
}


/** Function seopress_premium_seo_pack_migration() called by wp_ajax hooks: {'seopress_premium_seo_pack_migration'} **/
/** Parameters found in function seopress_premium_seo_pack_migration(): {"post": ["offset"]} **/
function seopress_premium_seo_pack_migration() {
	check_ajax_referer( 'seopress_premium_seo_pack_migrate_nonce', '_ajax_nonce', true );

	if ( current_user_can( seopress_capability( 'manage_options', 'migration' ) ) && is_admin() ) {
		if ( isset( $_POST['offset'] ) && isset( $_POST['offset'] ) ) {
			$offset = absint( $_POST['offset'] );
		}

		global $wpdb;

		// phpcs:ignore
		$total_count_posts = (int) $wpdb->get_var( "SELECT count(*) FROM {$wpdb->posts}" );
		// phpcs:ignore
		$total_count_terms = (int) $wpdb->get_var( "SELECT count(*) FROM {$wpdb->terms}" );

		$increment = 200;
		global $post;

		if ( $offset > $total_count_posts ) {
			$count_items = $total_count_posts;
			wp_reset_postdata();

			$premium_query_terms = get_option( 'psp_taxonomy_seo' );

			if ( $premium_query_terms ) {
				foreach ( $premium_query_terms as $taxonomies => $taxonomie ) {
					foreach ( $taxonomie as $term_id => $term_value ) {
						if ( ! empty( $term_value['psp_meta']['title'] ) ) { // Import title tag.
							update_term_meta( $term_id, '_seopress_titles_title', esc_html( $term_value['psp_meta']['title'] ) );
						}
						if ( ! empty( $term_value['psp_meta']['description'] ) ) { // Import meta desc.
							update_term_meta( $term_id, '_seopress_titles_desc', esc_html( $term_value['psp_meta']['description'] ) );
						}
						if ( ! empty( $term_value['psp_meta']['facebook_titlu'] ) ) { // Import Facebook Title.
							update_term_meta( $term_id, '_seopress_social_fb_title', esc_html( $term_value['psp_meta']['facebook_titlu'] ) );
						}
						if ( ! empty( $term_value['psp_meta']['facebook_desc'] ) ) { // Import Facebook Desc.
							update_term_meta( $term_id, '_seopress_social_fb_desc', esc_html( $term_value['psp_meta']['facebook_desc'] ) );
						}
						if ( ! empty( $term_value['psp_meta']['facebook_image'] ) ) { // Import Facebook Image.
							update_term_meta( $term_id, '_seopress_social_fb_img', esc_url( $term_value['psp_meta']['facebook_image'] ) );
						}
						if ( isset( $term_value['psp_meta']['robots_index'] ) && 'noindex' === $term_value['psp_meta']['robots_index'] ) { // Import Robots NoIndex.
							update_term_meta( $term_id, '_seopress_robots_index', 'yes' );
						}
						if ( isset( $term_value['psp_meta']['robots_follow'] ) && 'nofollow' === $term_value['psp_meta']['robots_follow'] ) { // Import Robots NoFollow.
							update_term_meta( $term_id, '_seopress_robots_follow', 'yes' );
						}
						if ( ! empty( $term_value['psp_meta']['canonical'] ) ) { // Import Canonical URL.
							update_term_meta( $term_id, '_seopress_robots_canonical', esc_url( $term_value['psp_meta']['canonical'] ) );
						}
					}
				}
			}
			$offset = 'done';
			wp_reset_postdata();
		} else {
			$args = array(
				'posts_per_page' => $increment,
				'post_type'      => 'any',
				'post_status'    => 'any',
				'offset'         => $offset,
			);

			$premium_query = get_posts( $args );

			if ( $premium_query ) {
				foreach ( $premium_query as $post ) {
					$psp_meta = get_post_meta( $post->ID, 'psp_meta', true );

					if ( ! empty( $psp_meta ) ) {
						if ( ! empty( $psp_meta['title'] ) ) { // Import title tag.
							update_post_meta( $post->ID, '_seopress_titles_title', esc_html( $psp_meta['title'] ) );
						}
						if ( ! empty( $psp_meta['description'] ) ) { // Import meta desc.
							update_post_meta( $post->ID, '_seopress_titles_desc', esc_html( $psp_meta['description'] ) );
						}
						if ( ! empty( $psp_meta['facebook_titlu'] ) ) { // Import Facebook Title.
							update_post_meta( $post->ID, '_seopress_social_fb_title', esc_html( $psp_meta['facebook_titlu'] ) );
						}
						if ( ! empty( $psp_meta['facebook_desc'] ) ) { // Import Facebook Desc.
							update_post_meta( $post->ID, '_seopress_social_fb_desc', esc_html( $psp_meta['facebook_desc'] ) );
						}
						if ( ! empty( $psp_meta['facebook_image'] ) ) { // Import Facebook Image.
							update_post_meta( $post->ID, '_seopress_social_fb_img', esc_url( $psp_meta['facebook_image'] ) );
						}
						if ( 'noindex' === $psp_meta['robots_index'] ) { // Import Robots NoIndex.
							update_post_meta( $post->ID, '_seopress_robots_index', 'yes' );
						}
						if ( 'nofollow' === $psp_meta['robots_follow'] ) { // Import Robots NoIndex.
							update_post_meta( $post->ID, '_seopress_robots_follow', 'yes' );
						}
						if ( ! empty( $psp_meta['canonical'] ) ) { // Import Canonical URL.
							update_post_meta( $post->ID, '_seopress_robots_canonical', esc_url( $psp_meta['canonical'] ) );
						}
						if ( ! empty( $psp_meta['mfocus_keyword'] ) ) { // Import Focus Keywords.
							$target_kw = preg_split( '/\r\n|\r|\n/', $psp_meta['mfocus_keyword'] );

							update_post_meta( $post->ID, '_seopress_analysis_target_kw', implode( ',', esc_html( $target_kw ) ) );
						}
					}
				}
			}
			$offset += $increment;

			if ( $offset >= $total_count_posts ) {
				$count_items = $total_count_posts;
			} else {
				$count_items = $offset;
			}
		}
		$data = array();

		$data['count'] = $count_items;
		$data['total'] = $total_count_posts + $total_count_terms;

		$data['offset'] = $offset;
		wp_send_json_success( $data );
		exit();
	}
}


/** Function seopress_smart_crawl_migration() called by wp_ajax hooks: {'seopress_smart_crawl_migration'} **/
/** Parameters found in function seopress_smart_crawl_migration(): {"post": ["offset"]} **/
function seopress_smart_crawl_migration() {
	check_ajax_referer( 'seopress_smart_crawl_migrate_nonce', '_ajax_nonce', true );

	if ( current_user_can( seopress_capability( 'manage_options', 'migration' ) ) && is_admin() ) {
		// Offset can be either an integer (posts phase) or a sentinel string ('redirects:N', 'done').
		$raw_offset = isset( $_POST['offset'] ) ? sanitize_text_field( wp_unslash( $_POST['offset'] ) ) : '0';

		$in_redirects_phase = ( 0 === strpos( $raw_offset, 'redirects:' ) );
		if ( $in_redirects_phase ) {
			$redirect_offset = absint( substr( $raw_offset, strlen( 'redirects:' ) ) );
			$offset          = 0;
		} else {
			$offset          = is_numeric( $raw_offset ) ? absint( $raw_offset ) : 0;
			$redirect_offset = 0;
		}

		global $wpdb;
		// phpcs:ignore
		$total_count_posts = (int) $wpdb->get_var( "SELECT count(*) FROM {$wpdb->posts}" );
		// phpcs:ignore
		$total_count_terms = (int) $wpdb->get_var( "SELECT count(*) FROM {$wpdb->terms}" );

		// Detect SmartCrawl redirects table and SEOPress redirections CPT.
		$redirects_table        = $wpdb->prefix . 'smartcrawl_redirects';
		$has_redirects_table    = ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $redirects_table ) ) === $redirects_table );
		$has_redirections_cpt   = post_type_exists( 'seopress_404' );
		$can_import_redirects   = $has_redirects_table && $has_redirections_cpt;
		$total_count_redirects  = 0;
		if ( $can_import_redirects ) {
			// SEOPress requires WP 6.5+, so the %i identifier placeholder is always available.
			// phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnsupportedIdentifierPlaceholder
			$total_count_redirects = (int) $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM %i', $redirects_table ) );
		}

		$increment = 200;
		global $post;

		// === Import settings ===//
		$wds_onpage_options   = get_option( 'wds_onpage_options' );
		$wds_social_options   = get_option( 'wds_social_options' );
		$wds_sitemap_options  = get_option( 'wds_sitemap_options' );
		$wds_settings_options = get_option( 'wds_settings_options' );
		$wds_schema_options   = get_option( 'wds_schema_options' );
		// `wds-advanced` is a single option containing several SmartCrawl sub-modules
		// (autolinks, redirects, woocommerce, breadcrumbs, ...). We only consume the
		// breadcrumbs slice today.
		$wds_advanced_options = get_option( 'wds-advanced' );

		$seopress_titles   = get_option( 'seopress_titles_option_name' );
		$seopress_xml_sitemap = get_option( 'seopress_xml_sitemap_option_name' );
		$seopress_social   = get_option( 'seopress_social_option_name' );
		$seopress_advanced = get_option( 'seopress_advanced_option_name' );
		$seopress_pro      = get_option( 'seopress_pro_option_name' );
		$post_types = seopress_get_service( 'WordPressData' )->getPostTypes();
		$taxonomies = seopress_get_service( 'WordPressData' )->getTaxonomies();

		// SmartCrawl's separator is either a literal character or a preset key (e.g. "pipe").
		// We collect both inside the loop and resolve once after.
		$smart_crawl_separator_literal = null;
		$smart_crawl_separator_preset  = null;

		if ( ! empty( $wds_onpage_options ) ) {
			foreach ( $wds_onpage_options as $key => $value ) {
				// Home title.
				if ( 'title-home' === $key ) {
					$seopress_titles['seopress_titles_home_site_title'] = seopress_smart_crawl_translate_template( $value );
				}
				// Home description.
				if ( 'metadesc-home' === $key ) {
					$seopress_titles['seopress_titles_home_site_desc'] = seopress_smart_crawl_translate_template( $value );
				}
				// Home default OG image. Used as the site-wide fallback when no per-post image is set.
				if ( 'og-images-home' === $key && is_array( $value ) && ! empty( $value[0] ) ) {
					$home_og_attachment_id = (int) $value[0];
					$home_og_url           = wp_get_attachment_url( $home_og_attachment_id );
					if ( ! empty( $home_og_url ) ) {
						$seopress_social['seopress_social_facebook_img']               = esc_url_raw( $home_og_url );
						$seopress_social['seopress_social_facebook_img_attachment_id'] = $home_og_attachment_id;
					}
				}
				// Home default X (Twitter) card image.
				if ( 'twitter-images-home' === $key && is_array( $value ) && ! empty( $value[0] ) ) {
					$home_tw_url = wp_get_attachment_url( (int) $value[0] );
					if ( ! empty( $home_tw_url ) ) {
						$seopress_social['seopress_social_twitter_card_img'] = esc_url_raw( $home_tw_url );
					}
				}
				// Separator. SmartCrawl 3.x stores a preset key (`pipe`, `dash`, ...) in
				// `preset-separator` and leaves `separator` empty; older versions store
				// the literal character in `separator`. We collect both and resolve below,
				// preferring the literal when present.
				if ( 'separator' === $key && '' !== $value && null !== $value ) {
					$smart_crawl_separator_literal = (string) $value;
				}
				if ( 'preset-separator' === $key && ! empty( $value ) ) {
					$smart_crawl_separator_preset = (string) $value;
				}
				// Advanced.
				if ( 'meta_robots-noindex-main_blog_archive' === $key ) {
					if ( 1 === $value ) {
						$seopress_titles['seopress_titles_noindex'] = '1';
					} else {
						unset( $seopress_titles['seopress_titles_noindex'] );
					}
				}
				if ( 'meta_robots-nofollow-main_blog_archive' === $key ) {
					if ( 1 === $value ) {
						$seopress_titles['seopress_titles_nofollow'] = '1';
					} else {
						unset( $seopress_titles['seopress_titles_nofollow'] );
					}
				}
				// Import CPT settings.
				foreach ( $post_types as $seopress_cpt_key => $seopress_cpt_value ) {
					// Single title.
					if ( 'title-' . $seopress_cpt_key === $key ) {
						$seopress_titles['seopress_titles_single_titles'][ $seopress_cpt_key ]['title'] = seopress_smart_crawl_translate_template( $value );
					}
					// Single description.
					if ( 'metadesc-' . $seopress_cpt_key === $key ) {
						$seopress_titles['seopress_titles_single_titles'][ $seopress_cpt_key ]['description'] = seopress_smart_crawl_translate_template( $value );
					}
					// Single noindex.
					if ( 'meta_robots-noindex-' . $seopress_cpt_key === $key ) {
						unset( $seopress_titles['seopress_titles_single_titles'][ $seopress_cpt_key ]['noindex'] );
						if ( 1 === $value ) {
							$seopress_titles['seopress_titles_single_titles'][ $seopress_cpt_key ]['noindex'] = '1';
						}
					}
					// Single nofollow.
					if ( 'meta_robots-nofollow-' . $seopress_cpt_key === $key ) {
						unset( $seopress_titles['seopress_titles_single_titles'][ $seopress_cpt_key ]['nofollow'] );
						if ( 1 === $value ) {
							$seopress_titles['seopress_titles_single_titles'][ $seopress_cpt_key ]['nofollow'] = '1';
						}
					}
					// Default OG Image per CPT.
					if ( 'og-images-' . $seopress_cpt_key === $key && is_array( $value ) && ! empty( $value[0] ) ) {
						$img_url = wp_get_attachment_url( (int) $value[0] );
						if ( ! empty( $img_url ) ) {
							$seopress_social['seopress_social_facebook_img_cpt'][ $seopress_cpt_key ] = esc_url_raw( $img_url );
						}
					}
					// Archive title.
					if ( 'title-pt-archive-' . $seopress_cpt_key === $key ) {
						$seopress_titles['seopress_titles_archive_titles'][ $seopress_cpt_key ]['title'] = seopress_smart_crawl_translate_template( $value );
					}
					// Archive description.
					if ( 'metadesc-pt-archive-' . $seopress_cpt_key === $key ) {
						$seopress_titles['seopress_titles_archive_titles'][ $seopress_cpt_key ]['description'] = seopress_smart_crawl_translate_template( $value );
					}
					// Archive noindex.
					if ( 'meta_robots-noindex-pt-archive-' . $seopress_cpt_key === $key ) {
						unset( $seopress_titles['seopress_titles_archive_titles'][ $seopress_cpt_key ]['noindex'] );
						if ( 1 === $value ) {
							$seopress_titles['seopress_titles_archive_titles'][ $seopress_cpt_key ]['noindex'] = '1';
						}
					}
					// Archive nofollow.
					if ( 'meta_robots-nofollow-pt-archive-' . $seopress_cpt_key === $key ) {
						unset( $seopress_titles['seopress_titles_archive_titles'][ $seopress_cpt_key ]['nofollow'] );
						if ( 1 === $value ) {
							$seopress_titles['seopress_titles_archive_titles'][ $seopress_cpt_key ]['nofollow'] = '1';
						}
					}
				}
				// Import taxonomies settings.
				foreach ( $taxonomies as $seopress_tax_key => $seopress_tax_value ) {
					// Tax title.
					if ( 'title-' . $seopress_tax_key === $key ) {
						$seopress_titles['seopress_titles_tax_titles'][ $seopress_tax_key ]['title'] = seopress_smart_crawl_translate_template( $value );
					}
					// Tax description.
					if ( 'metadesc-' . $seopress_tax_key === $key ) {
						$seopress_titles['seopress_titles_tax_titles'][ $seopress_tax_key ]['description'] = seopress_smart_crawl_translate_template( $value );
					}
					// Tax noindex.
					if ( 'meta_robots-noindex-' . $seopress_tax_key === $key ) {
						unset( $seopress_titles['seopress_titles_tax_titles'][ $seopress_tax_key ]['noindex'] );
						if ( 1 === $value ) {
							$seopress_titles['seopress_titles_tax_titles'][ $seopress_tax_key ]['noindex'] = '1';
						}
					}
					// Tax nofollow.
					if ( 'meta_robots-nofollow-' . $seopress_tax_key === $key ) {
						unset( $seopress_titles['seopress_titles_tax_titles'][ $seopress_tax_key ]['nofollow'] );
						if ( 1 === $value ) {
							$seopress_titles['seopress_titles_tax_titles'][ $seopress_tax_key ]['nofollow'] = '1';
						}
					}
				}
				// Author.
				if ( 'enable-author-archive' === $key ) {
					if ( 1 === $value ) {
						unset( $seopress_titles['seopress_titles_archives_author_disable'] );
					} else {
						$seopress_titles['seopress_titles_archives_author_disable'] = '1';
					}
				}
				if ( 'meta_robots-noindex-author' === $key ) {
					if ( 1 === $value ) {
						$seopress_titles['seopress_titles_archives_author_noindex'] = '1';
					} else {
						unset( $seopress_titles['seopress_titles_archives_author_noindex'] );
					}
				}
				if ( 'title-author' === $key ) {
					$seopress_titles['seopress_titles_archives_author_title'] = seopress_smart_crawl_translate_template( $value );
				}
				if ( 'metadesc-author' === $key ) {
					$seopress_titles['seopress_titles_archives_author_desc'] = seopress_smart_crawl_translate_template( $value );
				}
				// Date.
				if ( 'enable-date-archive' === $key ) {
					if ( 1 === $value ) {
						unset( $seopress_titles['seopress_titles_archives_date_disable'] );
					} else {
						$seopress_titles['seopress_titles_archives_date_disable'] = '1';
					}
				}
				if ( 'meta_robots-noindex-date' === $key ) {
					if ( 1 === $value ) {
						$seopress_titles['seopress_titles_archives_date_noindex'] = '1';
					} else {
						unset( $seopress_titles['seopress_titles_archives_date_noindex'] );
					}
				}
				if ( 'title-date' === $key ) {
					$seopress_titles['seopress_titles_archives_date_title'] = seopress_smart_crawl_translate_template( $value );
				}
				if ( 'metadesc-date' === $key ) {
					$seopress_titles['seopress_titles_archives_date_desc'] = seopress_smart_crawl_translate_template( $value );
				}
				// Search.
				if ( 'meta_robots-noindex-search' === $key ) {
					if ( 1 === $value ) {
						$seopress_titles['seopress_titles_archives_search_title_noindex'] = '1';
					} else {
						unset( $seopress_titles['seopress_titles_archives_search_title_noindex'] );
					}
				}
				if ( 'title-search' === $key ) {
					$seopress_titles['seopress_titles_archives_search_title'] = seopress_smart_crawl_translate_template( $value );
				}
				if ( 'metadesc-search' === $key ) {
					$seopress_titles['seopress_titles_archives_search_desc'] = seopress_smart_crawl_translate_template( $value );
				}
				// 404.
				if ( 'title-404' === $key ) {
					$seopress_titles['seopress_titles_archives_404_title'] = seopress_smart_crawl_translate_template( $value );
				}
				if ( 'metadesc-404' === $key ) {
					$seopress_titles['seopress_titles_archives_404_desc'] = seopress_smart_crawl_translate_template( $value );
				}
			}

			// SmartCrawl's "Homepage" tab edits the per-post meta of the static front page
			// when one is configured, not the global `title-home` option. SEOPress separates
			// the two: post-level meta lives on the post, global home title/desc live in the
			// options. To match SmartCrawl's UX expectation (override visible in the homepage
			// settings panel), copy the static front page's `_wds_*` overrides into the
			// global SEOPress home options as well. The per-post copy is handled by the
			// post pagination loop below.
			if ( 'page' === get_option( 'show_on_front' ) ) {
				$front_id = (int) get_option( 'page_on_front' );
				if ( $front_id > 0 ) {
					$front_title    = get_post_meta( $front_id, '_wds_title', true );
					$front_metadesc = get_post_meta( $front_id, '_wds_metadesc', true );
					if ( '' !== $front_title ) {
						$seopress_titles['seopress_titles_home_site_title'] = seopress_smart_crawl_translate_template( $front_title );
					}
					if ( '' !== $front_metadesc ) {
						$seopress_titles['seopress_titles_home_site_desc'] = seopress_smart_crawl_translate_template( $front_metadesc );
					}

					$front_og = get_post_meta( $front_id, '_wds_opengraph', true );
					if ( is_array( $front_og ) && ! empty( $front_og['images'][0] ) ) {
						$front_og_id  = (int) $front_og['images'][0];
						$front_og_url = wp_get_attachment_url( $front_og_id );
						if ( ! empty( $front_og_url ) ) {
							$seopress_social['seopress_social_facebook_img']               = esc_url_raw( $front_og_url );
							$seopress_social['seopress_social_facebook_img_attachment_id'] = $front_og_id;
						}
					}

					$front_tw = get_post_meta( $front_id, '_wds_twitter', true );
					if ( is_array( $front_tw ) && ! empty( $front_tw['images'][0] ) ) {
						$front_tw_url = wp_get_attachment_url( (int) $front_tw['images'][0] );
						if ( ! empty( $front_tw_url ) ) {
							$seopress_social['seopress_social_twitter_card_img'] = esc_url_raw( $front_tw_url );
						}
					}
				}
			}

			// Resolve the SmartCrawl separator. Literal value wins over preset; if only the
			// preset is set, map it to the matching character. We override whatever SEOPress's
			// init put there since the customer's preference takes precedence after a migration.
			if ( null !== $smart_crawl_separator_literal ) {
				$seopress_titles['seopress_titles_sep'] = sanitize_text_field( $smart_crawl_separator_literal );
			} elseif ( null !== $smart_crawl_separator_preset ) {
				$separator_presets = array(
					'pipe'       => '|',
					'dash'       => '-',
					'mdash'      => '—',
					'ndash'      => '–',
					'bullet'     => '•',
					'middle-dot' => '·',
					'colon'      => ':',
					'tilde'      => '~',
					'greater'    => '>',
					'arrow'      => '→',
				);
				if ( isset( $separator_presets[ $smart_crawl_separator_preset ] ) ) {
					$seopress_titles['seopress_titles_sep'] = $separator_presets[ $smart_crawl_separator_preset ];
				}
			}
		}

		// Import social.
		// Note: in SmartCrawl 3.x, social accounts (Twitter handle, Facebook URL, ...),
		// Facebook App ID, organization name and schema type live in `wds_social_options`.
		// Older SmartCrawl versions stored some of these in `wds_schema_options`,
		// so we keep both reads (this one for 3.x, the `wds_schema_options` loop below for legacy).
		if ( ! empty( $wds_social_options ) ) {
			foreach ( $wds_social_options as $key => $value ) {
				// OG enable.
				if ( 'og-enable' === $key ) {
					if ( true === $value || 1 === $value || '1' === $value ) {
						$seopress_social['seopress_social_facebook_og'] = '1';
					} else {
						unset( $seopress_social['seopress_social_facebook_og'] );
					}
				}
				// Twitter Cards enable. SmartCrawl 3.x renamed `twitter-enable` to `twitter-card-enable`.
				if ( 'twitter-card-enable' === $key || 'twitter-enable' === $key ) {
					if ( true === $value || 1 === $value || '1' === $value ) {
						$seopress_social['seopress_social_twitter_card'] = '1';
					} else {
						unset( $seopress_social['seopress_social_twitter_card'] );
					}
				}
				// Pinterest verify.
				if ( 'pinterest-verify' === $key ) {
					$seopress_advanced['seopress_advanced_advanced_pinterest'] = sanitize_text_field( $value );
				}
				// Organization logo. In 3.x this is already a URL string; in older versions it was an attachment ID.
				if ( 'organization_logo' === $key && ! empty( $value ) ) {
					if ( is_numeric( $value ) ) {
						$img_url = wp_get_attachment_url( (int) $value );
						if ( ! empty( $img_url ) ) {
							$seopress_social['seopress_social_knowledge_img'] = esc_url_raw( $img_url );
						}
					} else {
						$seopress_social['seopress_social_knowledge_img'] = esc_url_raw( $value );
					}
				}
				// Organization name.
				if ( 'organization_name' === $key ) {
					$seopress_social['seopress_social_knowledge_name'] = sanitize_text_field( $value );
				}
				// Schema type discriminator (Organization or Person). The subtype (Corporation, NGO, ...)
				// is resolved below from `wds_schema_options.organization_type`.
				if ( 'schema_type' === $key && ! empty( $value ) ) {
					$seopress_social['seopress_social_knowledge_type'] = sanitize_text_field( $value );
				}
				// Twitter username.
				if ( 'twitter_username' === $key ) {
					$seopress_social['seopress_social_accounts_twitter'] = sanitize_text_field( $value );
				}
				// Social profile URLs.
				if ( 'facebook_url' === $key ) {
					$seopress_social['seopress_social_accounts_facebook'] = esc_url_raw( $value );
				}
				if ( 'instagram_url' === $key ) {
					$seopress_social['seopress_social_accounts_instagram'] = esc_url_raw( $value );
				}
				if ( 'linkedin_url' === $key ) {
					$seopress_social['seopress_social_accounts_linkedin'] = esc_url_raw( $value );
				}
				if ( 'pinterest_url' === $key ) {
					$seopress_social['seopress_social_accounts_pinterest'] = esc_url_raw( $value );
				}
				if ( 'youtube_url' === $key ) {
					$seopress_social['seopress_social_accounts_youtube'] = esc_url_raw( $value );
				}
				// Facebook App ID.
				if ( 'fb-app-id' === $key ) {
					$seopress_social['seopress_social_facebook_app_id'] = sanitize_text_field( $value );
				}
			}
		}

		// Import XML sitemap.
		if ( ! empty( $wds_sitemap_options ) ) {
			// Master enable. SmartCrawl exposes its sitemap module through `override-native`
			// (replaces WordPress's core sitemap) or a top-level `active` flag in older versions.
			// Either implies the user wants a SEOPress sitemap once migrated.
			$smart_crawl_sitemap_on = false;
			if ( isset( $wds_sitemap_options['override-native'] ) && ! empty( $wds_sitemap_options['override-native'] ) ) {
				$smart_crawl_sitemap_on = true;
			}
			if ( isset( $wds_sitemap_options['active'] ) && ! empty( $wds_sitemap_options['active'] ) ) {
				$smart_crawl_sitemap_on = true;
			}
			if ( $smart_crawl_sitemap_on ) {
				$seopress_xml_sitemap['seopress_xml_sitemap_general_enable'] = '1';
			}

			foreach ( $wds_sitemap_options as $key => $value ) {
				// Post types in sitemap. SmartCrawl stores `not_in_sitemap=true` to exclude a CPT,
				// so we include in SEOPress only when the SmartCrawl flag is false/empty.
				foreach ( $post_types as $seopress_cpt_key => $seopress_cpt_value ) {
					if ( 'post_types-' . $seopress_cpt_key . '-not_in_sitemap' === $key ) {
						if ( empty( $value ) ) {
							$seopress_xml_sitemap['seopress_xml_sitemap_post_types_list'][ $seopress_cpt_key ]['include'] = '1';
						} else {
							unset( $seopress_xml_sitemap['seopress_xml_sitemap_post_types_list'][ $seopress_cpt_key ]['include'] );
						}
					}
				}

				// Taxonomies in sitemap.
				foreach ( $taxonomies as $seopress_tax_key => $seopress_tax_value ) {
					if ( 'taxonomies-' . $seopress_tax_key . '-not_in_sitemap' === $key ) {
						if ( empty( $value ) ) {
							$seopress_xml_sitemap['seopress_xml_sitemap_taxonomies_list'][ $seopress_tax_key ]['include'] = '1';
						} else {
							unset( $seopress_xml_sitemap['seopress_xml_sitemap_taxonomies_list'][ $seopress_tax_key ]['include'] );
						}
					}
				}

				// News Sitemap.
				if ( 'enable-news-sitemap' === $key ) {
					if ( true === $value || 1 === $value || '1' === $value ) {
						$seopress_pro['seopress_news_enable'] = '1';
					} else {
						unset( $seopress_pro['seopress_news_enable'] );
					}
				}
				// News publication name. SmartCrawl 3.x renamed `news-publication-name` to `news-publication`.
				if ( 'news-publication' === $key || 'news-publication-name' === $key ) {
					$seopress_pro['seopress_news_name'] = sanitize_text_field( $value );
				}

				// News post types. The value is a list of CPT slugs.
				if ( 'news-sitemap-included-post-types' === $key && is_array( $value ) && ! empty( $value ) ) {
					foreach ( $value as $news_cpt ) {
						if ( ! is_string( $news_cpt ) || '' === $news_cpt ) {
							continue;
						}
						$seopress_pro['seopress_news_name_post_types_list'][ $news_cpt ]['include'] = '1';
					}
				}

				// Image Sitemap.
				if ( 'sitemap-images' === $key ) {
					if ( true === $value || 1 === $value || '1' === $value ) {
						$seopress_xml_sitemap['seopress_xml_sitemap_img_enable'] = '1';
					} else {
						unset( $seopress_xml_sitemap['seopress_xml_sitemap_img_enable'] );
					}
				}
			}
		}

		// Schema (organization metadata that lives in `wds_schema_options`).
		// Account URLs and `schema_type` are handled above from `wds_social_options` for
		// SmartCrawl 3.x; we keep the same reads here as a fallback for older versions.
		if ( ! empty( $wds_schema_options ) ) {
			$allowed_knowledge_types = array(
				'Person'                  => 'Person',
				'Organization'            => 'Organization',
				'Corporation'             => 'Corporation',
				'LocalBusiness'           => 'LocalBusiness',
				'OnlineBusiness'          => 'OnlineBusiness',
				'OnlineStore'             => 'OnlineStore',
				'EducationalOrganization' => 'EducationalOrganization',
				'GovernmentOrganization'  => 'GovernmentOrganization',
				'NGO'                     => 'NGO',
				'NewsMediaOrganization'   => 'NewsMediaOrganization',
			);

			foreach ( $wds_schema_options as $key => $value ) {
				// Legacy: account URLs in `wds_schema_options` (older SmartCrawl).
				if ( 'twitter_username' === $key && empty( $seopress_social['seopress_social_accounts_twitter'] ) ) {
					$seopress_social['seopress_social_accounts_twitter'] = sanitize_text_field( $value );
				}
				if ( 'facebook_url' === $key && empty( $seopress_social['seopress_social_accounts_facebook'] ) ) {
					$seopress_social['seopress_social_accounts_facebook'] = esc_url_raw( $value );
				}
				if ( 'instagram_url' === $key && empty( $seopress_social['seopress_social_accounts_instagram'] ) ) {
					$seopress_social['seopress_social_accounts_instagram'] = esc_url_raw( $value );
				}
				if ( 'linkedin_url' === $key && empty( $seopress_social['seopress_social_accounts_linkedin'] ) ) {
					$seopress_social['seopress_social_accounts_linkedin'] = esc_url_raw( $value );
				}
				if ( 'pinterest_url' === $key && empty( $seopress_social['seopress_social_accounts_pinterest'] ) ) {
					$seopress_social['seopress_social_accounts_pinterest'] = esc_url_raw( $value );
				}
				if ( 'youtube_url' === $key && empty( $seopress_social['seopress_social_accounts_youtube'] ) ) {
					$seopress_social['seopress_social_accounts_youtube'] = esc_url_raw( $value );
				}
				if ( 'fb-app-id' === $key && empty( $seopress_social['seopress_social_facebook_app_id'] ) ) {
					$seopress_social['seopress_social_facebook_app_id'] = sanitize_text_field( $value );
				}
				if ( 'schema_type' === $key && empty( $seopress_social['seopress_social_knowledge_type'] ) ) {
					$seopress_social['seopress_social_knowledge_type'] = sanitize_text_field( $value );
				}
				if ( 'organization_name' === $key && empty( $seopress_social['seopress_social_knowledge_name'] ) ) {
					$seopress_social['seopress_social_knowledge_name'] = sanitize_text_field( $value );
				}
				// Organization subtype (SmartCrawl 3.x stores the Schema.org subtype here,
				// e.g. Corporation, NGO, LocalBusiness). Only apply when the discriminator is Organization.
				if ( 'organization_type' === $key && ! empty( $value ) ) {
					$current_type = isset( $seopress_social['seopress_social_knowledge_type'] ) ? $seopress_social['seopress_social_knowledge_type'] : '';
					if ( 'Person' !== $current_type && isset( $allowed_knowledge_types[ $value ] ) ) {
						$seopress_social['seopress_social_knowledge_type'] = $allowed_knowledge_types[ $value ];
					}
				}
				// Organization description.
				if ( 'organization_description' === $key ) {
					$seopress_social['seopress_social_knowledge_desc'] = sanitize_text_field( $value );
				}
				// Organization contact type.
				if ( 'organization_contact_type' === $key ) {
					$type = array(
						'customer support'    => 'customer support',
						'technical support'   => 'technical support',
						'billing support'     => 'billing support',
						'bill payment'        => 'bill payment',
						'sales'               => 'sales',
						'credit card support' => 'credit card support',
						'emergency'           => 'emergency',
						'baggage tracking'    => 'baggage tracking',
						'roadside assistance' => 'roadside assistance',
						'package tracking'    => 'package tracking',
					);
					if ( isset( $type[ $value ] ) ) {
						$seopress_social['seopress_social_knowledge_contact_type'] = sanitize_text_field( $type[ $value ] );
					}
				}
				// Organization phone.
				if ( 'organization_phone_number' === $key ) {
					$seopress_social['seopress_social_knowledge_phone'] = sanitize_text_field( $value );
				}
			}
		}

		// Import advanced.
		if ( ! empty( $wds_settings_options ) ) {
			foreach ( $wds_settings_options as $key => $value ) {
				// Google verification.
				if ( 'verification-google-meta' === $key ) {
					$seopress_advanced['seopress_advanced_advanced_google'] = sanitize_text_field( $value );
				}
				// Bing verification.
				if ( 'verification-bing-meta' === $key ) {
					$seopress_advanced['seopress_advanced_advanced_bing'] = sanitize_text_field( $value );
				}
				// WordPress generator.
				if ( 'general-suppress-generator' === $key ) {
					if ( 1 === $value ) {
						$seopress_advanced['seopress_advanced_advanced_wp_generator'] = '1';
					} else {
						unset( $seopress_advanced['seopress_advanced_advanced_wp_generator'] );
					}
				}
			}
		}

		// Breadcrumbs (PRO). SmartCrawl stores its breadcrumb config inside the
		// `wds-advanced` option under the `breadcrumbs` key. SEOPress breadcrumb settings
		// live in `seopress_pro_option_name` under the `seopress_breadcrumbs_*` keys.
		if ( is_array( $wds_advanced_options ) && ! empty( $wds_advanced_options['breadcrumbs'] ) && is_array( $wds_advanced_options['breadcrumbs'] ) ) {
			$wds_bc = $wds_advanced_options['breadcrumbs'];

			// Active toggle.
			if ( ! empty( $wds_bc['active'] ) ) {
				$seopress_pro['seopress_breadcrumbs_enable']      = '1';
				$seopress_pro['seopress_breadcrumbs_json_enable'] = '1';
			}

			// Separator: prefer the literal `custom_sep` when the preset is "custom",
			// otherwise map the preset key to a character.
			$bc_separator_presets = array(
				'pipe'         => '|',
				'dash'         => '-',
				'mdash'        => '—',
				'ndash'        => '–',
				'bullet'       => '•',
				'middle-dot'   => '·',
				'colon'        => ':',
				'tilde'        => '~',
				'greater-than' => '>',
				'less-than'    => '<',
				'arrow'        => '→',
				'slash'        => '/',
				'backslash'    => '\\',
			);
			if ( ! empty( $wds_bc['custom_sep'] ) ) {
				$seopress_pro['seopress_breadcrumbs_separator'] = sanitize_text_field( $wds_bc['custom_sep'] );
			} elseif ( ! empty( $wds_bc['separator'] ) && isset( $bc_separator_presets[ $wds_bc['separator'] ] ) ) {
				$seopress_pro['seopress_breadcrumbs_separator'] = $bc_separator_presets[ $wds_bc['separator'] ];
			}

			// Home label (free text).
			if ( ! empty( $wds_bc['home_label'] ) ) {
				$seopress_pro['seopress_breadcrumbs_i18n_home'] = sanitize_text_field( $wds_bc['home_label'] );
			}

			// 404 label (lives inside the `labels` sub-array in SmartCrawl).
			if ( ! empty( $wds_bc['labels']['404'] ) ) {
				$seopress_pro['seopress_breadcrumbs_i18n_404'] = sanitize_text_field( $wds_bc['labels']['404'] );
			}
		}

		update_option( 'seopress_titles_option_name', $seopress_titles );
		update_option( 'seopress_xml_sitemap_option_name', $seopress_xml_sitemap );
		update_option( 'seopress_social_option_name', $seopress_social );
		update_option( 'seopress_advanced_option_name', $seopress_advanced );
		update_option( 'seopress_pro_option_name', $seopress_pro );

		if ( $in_redirects_phase ) {
			// Phase 3: import SmartCrawl redirects table into the seopress_404 CPT.
			$count_items = $total_count_posts + $total_count_terms + $redirect_offset;

			if ( $can_import_redirects ) {
				// SEOPress requires WP 6.5+, so the %i identifier placeholder is always available.
				$rows = $wpdb->get_results(
					$wpdb->prepare(
						// phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnsupportedIdentifierPlaceholder
						'SELECT id, title, source, path, destination, type, options FROM %i ORDER BY id ASC LIMIT %d OFFSET %d',
						$redirects_table,
						$increment,
						$redirect_offset
					)
				);

				if ( ! empty( $rows ) ) {
					$valid_status_codes = array( '301', '302', '307', '308', '410', '451' );

					foreach ( $rows as $row ) {
						$source = '';
						if ( ! empty( $row->source ) ) {
							$source = $row->source;
						} elseif ( ! empty( $row->path ) ) {
							$source = $row->path;
						}

						if ( '' === $source ) {
							continue;
						}

						// Resolve destination: JSON-encoded object for internal targets, JSON string URL for external.
						$destination_url = '';
						$decoded         = json_decode( $row->destination, true );
						if ( is_array( $decoded ) && isset( $decoded['id'] ) ) {
							$permalink = get_permalink( (int) $decoded['id'] );
							if ( ! empty( $permalink ) ) {
								$destination_url = $permalink;
							}
						} elseif ( is_string( $decoded ) && '' !== $decoded ) {
							$destination_url = $decoded;
						} elseif ( null === $decoded && '' !== $row->destination ) {
							// Some rows store the URL as a plain (non-JSON) string.
							$destination_url = $row->destination;
						}

						if ( '' === $destination_url ) {
							continue;
						}

						// Skip if a redirection with the same source already exists (idempotent re-runs).
						$existing = get_posts(
							array(
								'post_type'      => 'seopress_404',
								'post_status'    => 'any',
								'posts_per_page' => 1,
								'title'          => $source,
								'fields'         => 'ids',
								'no_found_rows'  => true,
							)
						);
						if ( ! empty( $existing ) ) {
							continue;
						}

						// SEOPress stores the matched URL pattern in `post_title`.
						$post_id = wp_insert_post(
							array(
								'post_title'  => $source,
								'post_name'   => sanitize_title( $source ),
								'post_type'   => 'seopress_404',
								'post_status' => 'publish',
							),
							true
						);

						if ( is_wp_error( $post_id ) || ! $post_id ) {
							continue;
						}

						$status = (string) $row->type;
						if ( ! in_array( $status, $valid_status_codes, true ) ) {
							$status = '301';
						}

						update_post_meta( $post_id, '_seopress_redirections_value', esc_url_raw( $destination_url ) );
						update_post_meta( $post_id, '_seopress_redirections_type', $status );
						update_post_meta( $post_id, '_seopress_redirections_enabled', 'yes' );

						// Regex flag from the `options` JSON, when present.
						if ( ! empty( $row->options ) ) {
							$opts = json_decode( $row->options, true );
							if ( is_array( $opts ) && ! empty( $opts['regex'] ) ) {
								update_post_meta( $post_id, '_seopress_redirections_enabled_regex', 'yes' );
							}
						}
					}
				}
			}

			$redirect_offset += $increment;

			if ( ! $can_import_redirects || $redirect_offset >= $total_count_redirects ) {
				$offset      = 'done';
				$count_items = $total_count_posts + $total_count_terms + $total_count_redirects;
			} else {
				$offset      = 'redirects:' . $redirect_offset;
				$count_items = $total_count_posts + $total_count_terms + $redirect_offset;
			}
		} elseif ( $offset > $total_count_posts ) {
			wp_reset_postdata();
			$count_items = $total_count_posts;

			$smart_crawl_query_terms = get_option( 'wds_taxonomy_meta' );

			if ( $smart_crawl_query_terms ) {
				foreach ( $smart_crawl_query_terms as $taxonomies => $taxonomie ) {
					foreach ( $taxonomie as $term_id => $term_value ) {
						if ( ! empty( $term_value['wds_title'] ) ) { // Import title tag.
							update_term_meta( $term_id, '_seopress_titles_title', seopress_smart_crawl_translate_template( $term_value['wds_title'] ) );
						}
						if ( ! empty( $term_value['wds_desc'] ) ) { // Import meta desc.
							update_term_meta( $term_id, '_seopress_titles_desc', seopress_smart_crawl_translate_template( $term_value['wds_desc'] ) );
						}
						if ( ! empty( $term_value['opengraph']['title'] ) ) { // Import Facebook Title.
							update_term_meta( $term_id, '_seopress_social_fb_title', seopress_smart_crawl_translate_template( $term_value['opengraph']['title'] ) );
						}
						if ( ! empty( $term_value['opengraph']['description'] ) ) { // Import Facebook Desc.
							update_term_meta( $term_id, '_seopress_social_fb_desc', seopress_smart_crawl_translate_template( $term_value['opengraph']['description'] ) );
						}
						if ( ! empty( $term_value['opengraph']['images'] ) ) { // Import Facebook Image.
							$image_id = $term_value['opengraph']['images'][0];
							$img_url  = wp_get_attachment_url( $image_id );

							if ( isset( $img_url ) && '' !== $img_url ) {
								update_term_meta( $term_id, '_seopress_social_fb_img', esc_url_raw( $img_url ) );
							}
						}
						if ( ! empty( $term_value['twitter']['title'] ) ) { // Import Facebook Title.
							update_term_meta( $term_id, '_seopress_social_twitter_title', seopress_smart_crawl_translate_template( $term_value['twitter']['title'] ) );
						}
						if ( ! empty( $term_value['twitter']['description'] ) ) { // Import Facebook Desc.
							update_term_meta( $term_id, '_seopress_social_twitter_desc', seopress_smart_crawl_translate_template( $term_value['twitter']['description'] ) );
						}
						if ( ! empty( $term_value['twitter']['images'] ) ) { // Import Facebook Image.
							$image_id = $term_value['twitter']['images'][0];
							$img_url  = wp_get_attachment_url( $image_id );

							if ( isset( $img_url ) && '' !== $img_url ) {
								update_term_meta( $term_id, '_seopress_social_twitter_img', esc_url_raw( $img_url ) );
							}
						}
						if ( ! empty( $term_value['wds_noindex'] ) && 'noindex' === $term_value['wds_noindex'] ) { // Import Robots NoIndex.
							update_term_meta( $term_id, '_seopress_robots_index', 'yes' );
						}
						if ( ! empty( $term_value['wds_nofollow'] ) && 'nofollow' === $term_value['wds_nofollow'] ) { // Import Robots NoFollow.
							update_term_meta( $term_id, '_seopress_robots_follow', 'yes' );
						}
						if ( ! empty( $term_value['wds_canonical'] ) ) { // Import Canonical URL.
							update_term_meta( $term_id, '_seopress_robots_canonical', esc_url_raw( $term_value['wds_canonical'] ) );
						}
					}
				}
			}
			wp_reset_postdata();

			// Move on to redirects phase if available, otherwise we're done.
			if ( $can_import_redirects && $total_count_redirects > 0 ) {
				$offset      = 'redirects:0';
				$count_items = $total_count_posts + $total_count_terms;
			} else {
				$offset      = 'done';
				$count_items = $total_count_posts + $total_count_terms;
			}
		} else {
			$args = array(
				'posts_per_page' => $increment,
				'post_type'      => 'any',
				'post_status'    => 'any',
				'offset'         => $offset,
			);

			$smart_crawl_query = get_posts( $args );

			if ( $smart_crawl_query ) {
				foreach ( $smart_crawl_query as $post ) {
					if ( '' !== get_post_meta( $post->ID, '_wds_title', true ) ) { // Import title tag.
						update_post_meta( $post->ID, '_seopress_titles_title', seopress_smart_crawl_translate_template( get_post_meta( $post->ID, '_wds_title', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_wds_metadesc', true ) ) { // Import meta desc.
						update_post_meta( $post->ID, '_seopress_titles_desc', seopress_smart_crawl_translate_template( get_post_meta( $post->ID, '_wds_metadesc', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_wds_opengraph', true ) ) {
						$_wds_opengraph = get_post_meta( $post->ID, '_wds_opengraph', true );
						if ( ! empty( $_wds_opengraph['title'] ) ) {
							update_post_meta( $post->ID, '_seopress_social_fb_title', seopress_smart_crawl_translate_template( $_wds_opengraph['title'] ) ); // Import Facebook Title.
						}
						if ( ! empty( $_wds_opengraph['description'] ) ) { // Import Facebook Desc.
							update_post_meta( $post->ID, '_seopress_social_fb_desc', seopress_smart_crawl_translate_template( $_wds_opengraph['description'] ) );
						}
						if ( ! empty( $_wds_opengraph['images'] ) ) { // Import Facebook Image.
							$image_id = $_wds_opengraph['images'][0];
							$img_url  = wp_get_attachment_url( $image_id );

							if ( isset( $img_url ) && '' !== $img_url ) {
								update_post_meta( $post->ID, '_seopress_social_fb_img', esc_url_raw( $img_url ) );
							}
						}
					}
					if ( '' !== get_post_meta( $post->ID, '_wds_twitter', true ) ) { // Import Twitter Title.
						$_wds_twitter = get_post_meta( $post->ID, '_wds_twitter', true );
						if ( ! empty( $_wds_twitter['title'] ) ) {
							update_post_meta( $post->ID, '_seopress_social_twitter_title', seopress_smart_crawl_translate_template( $_wds_twitter['title'] ) ); // Import Twitter Title.
						}
						if ( ! empty( $_wds_twitter['description'] ) ) { // Import Twitter Desc.
							update_post_meta( $post->ID, '_seopress_social_twitter_desc', seopress_smart_crawl_translate_template( $_wds_twitter['description'] ) );
						}
						if ( ! empty( $_wds_twitter['images'] ) ) { // Import Twitter Image.
							$image_id = $_wds_twitter['images'][0];
							$img_url  = wp_get_attachment_url( $image_id );

							if ( isset( $img_url ) && '' !== $img_url ) {
								update_post_meta( $post->ID, '_seopress_social_twitter_img', esc_url_raw( $img_url ) );
							}
						}
					}
					if ( '1' === get_post_meta( $post->ID, '_wds_meta-robots-noindex', true ) ) { // Import Robots NoIndex.
						update_post_meta( $post->ID, '_seopress_robots_index', 'yes' );
					}
					if ( '1' === get_post_meta( $post->ID, '_wds_meta-robots-nofollow', true ) ) { // Import Robots NoIndex.
						update_post_meta( $post->ID, '_seopress_robots_follow', 'yes' );
					}
					if ( '' !== get_post_meta( $post->ID, '_wds_meta-robots-adv', true ) ) {
						$robots = get_post_meta( $post->ID, '_wds_meta-robots-adv', true );
						if ( '' !== $robots ) {
							$robots = explode( ',', $robots );

							if ( in_array( 'nosnippet', $robots, true ) ) { // Import Robots NoSnippet.
								update_post_meta( $post->ID, '_seopress_robots_snippet', 'yes' );
							}
						}
					}
					if ( '' !== get_post_meta( $post->ID, '_wds_canonical', true ) ) { // Import Canonical URL.
						update_post_meta( $post->ID, '_seopress_robots_canonical', esc_url_raw( get_post_meta( $post->ID, '_wds_canonical', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_wds_redirect', true ) ) { // Import Redirect URL.
						update_post_meta( $post->ID, '_seopress_redirections_enabled', 'yes' );
						update_post_meta( $post->ID, '_seopress_redirections_type', '301' );
						update_post_meta( $post->ID, '_seopress_redirections_value', esc_url_raw( get_post_meta( $post->ID, '_wds_redirect', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_wds_focus-keywords', true ) ) { // Import Focus Keywords.
						update_post_meta( $post->ID, '_seopress_analysis_target_kw', sanitize_text_field( get_post_meta( $post->ID, '_wds_focus-keywords', true ) ) );
					}
				}
			}
			$offset += $increment;

			if ( $offset >= $total_count_posts ) {
				$count_items = $total_count_posts;
			} else {
				$count_items = $offset;
			}
		}
		$data = array();

		$data['count'] = $count_items;
		$data['total'] = $total_count_posts + $total_count_terms + $total_count_redirects;

		$data['offset'] = $offset;
		wp_send_json_success( $data );
		exit();
	}
}


/** Function seopress_toggle_promotions() called by wp_ajax hooks: {'seopress_toggle_promotions'} **/
/** Parameters found in function seopress_toggle_promotions(): {"post": ["disable_all"]} **/
function seopress_toggle_promotions() {
	check_ajax_referer( 'seopress_toggle_promotions_nonce', '_ajax_nonce', true );

	if ( ! current_user_can( seopress_capability( 'manage_options', 'dashboard' ) ) || ! is_admin() ) {
		wp_send_json_error( array( 'message' => __( 'Permission denied.', 'wp-seopress' ) ) );
	}

	$disable_all = isset( $_POST['disable_all'] ) && '1' === $_POST['disable_all'];

	// Use the PromotionService to set the preference.
	$result = seopress_get_service( 'PromotionService' )->setPreference( 'disable_all', $disable_all );

	if ( $result ) {
		wp_send_json_success();
	} else {
		wp_send_json_error();
	}
}


/** Function seopress_switch_view() called by wp_ajax hooks: {'seopress_switch_view'} **/
/** Parameters found in function seopress_switch_view(): {"post": ["view"]} **/
function seopress_switch_view() {
	check_ajax_referer( 'seopress_switch_view_nonce', '_ajax_nonce', true );

	if ( current_user_can( seopress_capability( 'manage_options', 'dashboard' ) ) && is_admin() ) {
		if ( isset( $_POST['view'] ) ) {
			$seopress_dashboard_options = get_option( 'seopress_dashboard', array() );

			$view = sanitize_text_field( wp_unslash( $_POST['view'] ) );

			if ( false !== $view ) {
				$seopress_dashboard_options['view'] = $view;
			}
			update_option( 'seopress_dashboard', $seopress_dashboard_options, false );
		}
		exit();
	}
}


/** Function get() called by wp_ajax hooks: {'get_preview_meta_title', 'get_preview_meta_description'} **/
/** Parameters found in function get(): {"get": ["template", "post_id", "home_id", "term_id"]} **/
function get() {
        if ( ! isset($_GET['template'])) { //phpcs:ignore
			wp_send_json_error();
			return;
		}

		check_ajax_referer( 'get_preview_meta_description', 'nonce' );

		$template = stripcslashes( $_GET['template'] ); // phpcs:ignore
		$post_id  = isset( $_GET['post_id'] ) ? (int) $_GET['post_id'] : null;
		$home_id  = isset( $_GET['home_id'] ) ? (int) $_GET['home_id'] : null;
		$term_id  = isset( $_GET['term_id'] ) ? (int) $_GET['term_id'] : null;

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$context_page = seopress_get_service( 'ContextPage' )->buildContextWithCurrentId( (int) $_GET['post_id'] );
		if ( $post_id ) {
			$context_page->setPostById( (int) $_GET['post_id'] );
			$context_page->setIsSingle( true );

			$terms = get_the_terms( $post_id, 'post_tag' );

			if ( ! empty( $terms ) ) {
				$context_page->setHasTag( true );
			}

			$categories = get_the_terms( $post_id, 'category' );
			if ( ! empty( $categories ) ) {
				$context_page->setHasCategory( true );
			}
		}

		if ( $post_id === $home_id && null !== $home_id ) {
			$context_page->setIsHome( true );
		}

		if ( $post_id === $term_id && null !== $term_id ) {
			$context_page->setIsCategory( true );
			$context_page->setTermId( $term_id );
		}

		$value = seopress_get_service( 'TagsToString' )->replace( $template, $context_page->getContext() );

		wp_send_json_success( $value );
	}


/** Function seopress_slim_seo_migration() called by wp_ajax hooks: {'seopress_slim_seo_migration'} **/
/** Parameters found in function seopress_slim_seo_migration(): {"post": ["offset"]} **/
function seopress_slim_seo_migration() {
	check_ajax_referer( 'seopress_slim_seo_migrate_nonce', '_ajax_nonce', true );

	if ( current_user_can( seopress_capability( 'manage_options', 'migration' ) ) && is_admin() ) {
		if ( isset( $_POST['offset'] ) && isset( $_POST['offset'] ) ) {
			$offset = absint( $_POST['offset'] );
		}

		global $wpdb;
		// phpcs:ignore
		$total_count_posts = (int) $wpdb->get_var( "SELECT count(*) FROM {$wpdb->posts}" );
		// phpcs:ignore
		$total_count_terms = (int) $wpdb->get_var( "SELECT count(*) FROM {$wpdb->terms}" );

		$increment = 200;
		global $post;

		if ( $offset > $total_count_posts ) {
			wp_reset_postdata();
			$count_items = $total_count_posts;

			$args                 = array(
				// 'number' => $increment,
				'hide_empty' => false,
				// 'offset' => $offset,
				'fields'     => 'ids',
			);
			$slim_seo_query_terms = get_terms( $args );

			if ( $slim_seo_query_terms ) {
				foreach ( $slim_seo_query_terms as $term_id ) {
					if ( '' !== get_term_meta( $term_id, 'slim_seo', true ) ) {
						$term_settings = get_term_meta( $term_id, 'slim_seo', true );

						if ( ! empty( $term_settings['title'] ) ) { // Import title tag.
							update_term_meta( $term_id, '_seopress_titles_title', esc_html( $term_settings['title'] ) );
						}
						if ( ! empty( $term_settings['description'] ) ) { // Import meta desc.
							update_term_meta( $term_id, '_seopress_titles_desc', esc_html( $term_settings['description'] ) );
						}
						if ( ! empty( $term_settings['noindex'] ) ) { // Import Robots NoIndex.
							update_term_meta( $term_id, '_seopress_robots_index', 'yes' );
						}
						if ( ! empty( $term_settings['facebook_image'] ) ) { // Import FB image.
							update_term_meta( $term_id, '_seopress_social_fb_img', esc_url( $term_settings['facebook_image'] ) );
						}
						if ( ! empty( $term_settings['twitter_image'] ) ) { // Import Tw image.
							update_term_meta( $term_id, '_seopress_social_twitter_img', esc_url( $term_settings['twitter_image'] ) );
						}
					}
				}
			}
			$offset = 'done';
			wp_reset_postdata();
		} else {
			$args = array(
				'posts_per_page' => $increment,
				'post_type'      => 'any',
				'post_status'    => 'any',
				'offset'         => $offset,
			);

			$slim_seo_query = get_posts( $args );

			if ( $slim_seo_query ) {
				foreach ( $slim_seo_query as $post ) {
					if ( '' !== get_post_meta( $post->ID, 'slim_seo', true ) ) {
						$post_settings = get_post_meta( $post->ID, 'slim_seo', true );

						if ( ! empty( $post_settings['title'] ) ) { // Import title tag.
							update_post_meta( $post->ID, '_seopress_titles_title', esc_html( $post_settings['title'] ) );
						}
						if ( ! empty( $post_settings['description'] ) ) { // Import meta desc.
							update_post_meta( $post->ID, '_seopress_titles_desc', esc_html( $post_settings['description'] ) );
						}
						if ( ! empty( $post_settings['noindex'] ) ) { // Import Robots NoIndex.
							update_post_meta( $post->ID, '_seopress_robots_index', 'yes' );
						}
						if ( ! empty( $post_settings['facebook_image'] ) ) { // Import FB image.
							update_post_meta( $post->ID, '_seopress_social_fb_img', esc_url( $post_settings['facebook_image'] ) );
						}
						if ( ! empty( $post_settings['twitter_image'] ) ) { // Import Tw image.
							update_post_meta( $post->ID, '_seopress_social_twitter_img', esc_url( $post_settings['twitter_image'] ) );
						}
					}
				}
			}
			$offset += $increment;

			if ( $offset >= $total_count_posts ) {
				$count_items = $total_count_posts;
			} else {
				$count_items = $offset;
			}
		}
		$data = array();

		$data['count'] = $count_items;
		$data['total'] = $total_count_posts + $total_count_terms;

		$data['offset'] = $offset;
		wp_send_json_success( $data );
		exit();
	}
}


/** Function seopress_seo_framework_migration() called by wp_ajax hooks: {'seopress_seo_framework_migration'} **/
/** Parameters found in function seopress_seo_framework_migration(): {"post": ["offset"]} **/
function seopress_seo_framework_migration() {
	check_ajax_referer( 'seopress_seo_framework_migrate_nonce', '_ajax_nonce', true );

	if ( current_user_can( seopress_capability( 'manage_options', 'migration' ) ) && is_admin() ) {
		if ( isset( $_POST['offset'] ) && isset( $_POST['offset'] ) ) {
			$offset = absint( $_POST['offset'] );
		}

		global $wpdb;
		// phpcs:ignore
		$total_count_posts = (int) $wpdb->get_var( "SELECT count(*) FROM {$wpdb->posts}" );
		// phpcs:ignore
		$total_count_terms = (int) $wpdb->get_var( "SELECT count(*) FROM {$wpdb->terms}" );

		$increment = 200;
		global $post;

		if ( $offset > $total_count_posts ) {
			wp_reset_postdata();
			$count_items = $total_count_posts;

			$args                      = array(
				// 'number' => $increment,
				'hide_empty' => false,
				// 'offset' => $offset,
				'fields'     => 'ids',
			);
			$seo_framework_query_terms = get_terms( $args );

			if ( $seo_framework_query_terms ) {
				foreach ( $seo_framework_query_terms as $term_id ) {
					if ( '' !== get_term_meta( $term_id, 'autodescription-term-settings', true ) ) {
						$term_settings = get_term_meta( $term_id, 'autodescription-term-settings', true );

						if ( ! empty( $term_settings['doctitle'] ) ) { // Import title tag.
							update_term_meta( $term_id, '_seopress_titles_title', $term_settings['doctitle'] );
						}
						if ( ! empty( $term_settings['description'] ) ) { // Import meta desc.
							update_term_meta( $term_id, '_seopress_titles_desc', $term_settings['description'] );
						}
						if ( ! empty( $term_settings['noindex'] ) ) { // Import Robots NoIndex.
							update_term_meta( $term_id, '_seopress_robots_index', 'yes' );
						}
						if ( ! empty( $term_settings['nofollow'] ) ) { // Import Robots NoFollow.
							update_term_meta( $term_id, '_seopress_robots_follow', 'yes' );
						}
					}
				}
			}
			$offset = 'done';
			wp_reset_postdata();
		} else {
			$args = array(
				'posts_per_page' => $increment,
				'post_type'      => 'any',
				'post_status'    => 'any',
				'offset'         => $offset,
			);

			$seo_framework_query = get_posts( $args );

			if ( $seo_framework_query ) {
				foreach ( $seo_framework_query as $post ) {
					if ( '' !== get_post_meta( $post->ID, '_genesis_title', true ) ) { // Import title tag.
						update_post_meta( $post->ID, '_seopress_titles_title', esc_html( get_post_meta( $post->ID, '_genesis_title', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_genesis_description', true ) ) { // Import meta desc.
						update_post_meta( $post->ID, '_seopress_titles_desc', esc_html( get_post_meta( $post->ID, '_genesis_description', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_open_graph_title', true ) ) { // Import Facebook Title.
						update_post_meta( $post->ID, '_seopress_social_fb_title', esc_html( get_post_meta( $post->ID, '_open_graph_title', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_open_graph_description', true ) ) { // Import Facebook Desc.
						update_post_meta( $post->ID, '_seopress_social_fb_desc', esc_html( get_post_meta( $post->ID, '_open_graph_description', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_social_image_url', true ) ) { // Import Facebook Image.
						update_post_meta( $post->ID, '_seopress_social_fb_img', esc_url( get_post_meta( $post->ID, '_social_image_url', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_twitter_title', true ) ) { // Import Twitter Title.
						update_post_meta( $post->ID, '_seopress_social_twitter_title', esc_html( get_post_meta( $post->ID, '_twitter_title', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_twitter_description', true ) ) { // Import Twitter Desc.
						update_post_meta( $post->ID, '_seopress_social_twitter_desc', esc_html( get_post_meta( $post->ID, '_twitter_description', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_social_image_url', true ) ) { // Import Twitter Image.
						update_post_meta( $post->ID, '_seopress_social_twitter_img', esc_url( get_post_meta( $post->ID, '_social_image_url', true ) ) );
					}
					if ( '1' === get_post_meta( $post->ID, '_genesis_noindex', true ) ) { // Import Robots NoIndex.
						update_post_meta( $post->ID, '_seopress_robots_index', 'yes' );
					}
					if ( '1' === get_post_meta( $post->ID, '_genesis_nofollow', true ) ) { // Import Robots NoFollow.
						update_post_meta( $post->ID, '_seopress_robots_follow', 'yes' );
					}
					if ( '' !== get_post_meta( $post->ID, '_genesis_canonical_uri', true ) ) { // Import Canonical URL.
						update_post_meta( $post->ID, '_seopress_robots_canonical', esc_url( get_post_meta( $post->ID, '_genesis_canonical_uri', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, 'redirect', true ) ) { // Import Redirect URL.
						update_post_meta( $post->ID, '_seopress_redirections_enabled', 'yes' );
						update_post_meta( $post->ID, '_seopress_redirections_type', '301' );
						update_post_meta( $post->ID, '_seopress_redirections_value', esc_url( get_post_meta( $post->ID, 'redirect', true ) ) );
					}

					// Primary category.
					if ( 'post' === get_post_type( $post->ID ) ) {
						$tax = 'category';
					} elseif ( 'product' === get_post_type( $post->ID ) ) {
						$tax = 'product_cat';
					}
					if ( isset( $tax ) ) {
						$primary_term = get_post_meta( $post->ID, '_primary_term_' . $tax, true );

						if ( '' !== $primary_term ) {
							update_post_meta( $post->ID, '_seopress_robots_primary_cat', absint( $primary_term ) );
						}
					}
				}
			}
			$offset += $increment;

			if ( $offset >= $total_count_posts ) {
				$count_items = $total_count_posts;
			} else {
				$count_items = $offset;
			}
		}
		$data = array();

		$data['count'] = $count_items;
		$data['total'] = $total_count_posts + $total_count_terms;

		$data['offset'] = $offset;
		wp_send_json_success( $data );
		exit();
	}
}


/** Function seopress_hide_notices() called by wp_ajax hooks: {'seopress_hide_notices'} **/
/** Parameters found in function seopress_hide_notices(): {"post": ["notice", "notice_value"]} **/
function seopress_hide_notices() {
	check_ajax_referer( 'seopress_hide_notices_nonce', '_ajax_nonce', true );

	if ( current_user_can( seopress_capability( 'manage_options', 'dashboard' ) ) && is_admin() ) {
		if ( isset( $_POST['notice'] ) && isset( $_POST['notice_value'] ) ) {
			$seopress_notices_options = get_option( 'seopress_notices', array() );

			$notice       = sanitize_text_field( wp_unslash( $_POST['notice'] ) );
			$notice_value = sanitize_text_field( wp_unslash( $_POST['notice_value'] ) );

			if ( false !== $notice && false !== $notice_value ) {
				$seopress_notices_options[ $notice ] = $notice_value;
			}
			update_option( 'seopress_notices', $seopress_notices_options, false );
		}
		exit();
	}
}


/** Function seopress_cookies_user_consent() called by wp_ajax hooks: {'nopriv_seopress_cookies_user_consent', 'seopress_cookies_user_consent'} **/
/** No params detected :-/ **/


/** Function seopress_instant_indexing_post() called by wp_ajax hooks: {'seopress_instant_indexing_post'} **/
/** No params detected :-/ **/


/** Function seopress_wp_meta_seo_migration() called by wp_ajax hooks: {'seopress_wp_meta_seo_migration'} **/
/** Parameters found in function seopress_wp_meta_seo_migration(): {"post": ["offset"]} **/
function seopress_wp_meta_seo_migration() {
	check_ajax_referer( 'seopress_meta_seo_migrate_nonce', '_ajax_nonce', true );

	if ( current_user_can( seopress_capability( 'manage_options', 'migration' ) ) && is_admin() ) {
		if ( isset( $_POST['offset'] ) && isset( $_POST['offset'] ) ) {
			$offset = absint( $_POST['offset'] );
		}

		global $wpdb;
		// phpcs:ignore
		$total_count_posts = (int) $wpdb->get_var( "SELECT count(*) FROM {$wpdb->posts}" );
		// phpcs:ignore
		$total_count_terms = (int) $wpdb->get_var( "SELECT count(*) FROM {$wpdb->terms}" );

		$increment = 200;
		global $post;

		if ( $offset > $total_count_posts ) {
			wp_reset_postdata();
			$count_items = $total_count_posts;

			$args                    = array(
				'hide_empty' => false,
				'fields'     => 'ids',
			);
			$wp_meta_seo_query_terms = get_terms( $args );

			if ( $wp_meta_seo_query_terms ) {
				foreach ( $wp_meta_seo_query_terms as $term_id ) {
					if ( '' !== get_term_meta( $term_id, 'wpms_category_metatitle', true ) ) { // Import title tag.
						update_term_meta( $term_id, '_seopress_titles_title', esc_html( get_term_meta( $term_id, 'wpms_category_metatitle', true ) ) );
					}
					if ( '' !== get_term_meta( $term_id, 'wpms_category_metadesc', true ) ) { // Import title desc.
						update_term_meta( $term_id, '_seopress_titles_desc', esc_html( get_term_meta( $term_id, 'wpms_category_metadesc', true ) ) );
					}
				}
			}
			$offset = 'done';
			wp_reset_postdata();
		} else {
			$args = array(
				'posts_per_page' => $increment,
				'post_type'      => 'any',
				'post_status'    => 'any',
				'offset'         => $offset,
			);

			$wp_meta_seo_query = get_posts( $args );

			if ( $wp_meta_seo_query ) {
				foreach ( $wp_meta_seo_query as $post ) {
					if ( '' !== get_post_meta( $post->ID, '_metaseo_metatitle', true ) ) { // Import title tag.
						update_post_meta( $post->ID, '_seopress_titles_title', esc_html( get_post_meta( $post->ID, '_metaseo_metatitle', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_metaseo_metadesc', true ) ) { // Import meta desc.
						update_post_meta( $post->ID, '_seopress_titles_desc', esc_html( get_post_meta( $post->ID, '_metaseo_metadesc', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_metaseo_metaopengraph-title', true ) ) { // Import Facebook Title.
						update_post_meta( $post->ID, '_seopress_social_fb_title', esc_html( get_post_meta( $post->ID, '_metaseo_metaopengraph-title', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_metaseo_metaopengraph-desc', true ) ) { // Import Facebook Desc.
						update_post_meta( $post->ID, '_seopress_social_fb_desc', esc_html( get_post_meta( $post->ID, '_metaseo_metaopengraph-desc', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_metaseo_metaopengraph-image', true ) ) { // Import Facebook Image.
						update_post_meta( $post->ID, '_seopress_social_fb_img', esc_url( get_post_meta( $post->ID, '_metaseo_metaopengraph-image', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_metaseo_metatwitter-title', true ) ) { // Import Twitter Title.
						update_post_meta( $post->ID, '_seopress_social_twitter_title', esc_html( get_post_meta( $post->ID, '_metaseo_metatwitter-title', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_metaseo_metatwitter-desc', true ) ) { // Import Twitter Desc.
						update_post_meta( $post->ID, '_seopress_social_twitter_desc', esc_html( get_post_meta( $post->ID, '_metaseo_metatwitter-desc', true ) ) );
					}
					if ( '' !== get_post_meta( $post->ID, '_metaseo_metatwitter-image', true ) ) { // Import Twitter Image.
						update_post_meta( $post->ID, '_seopress_social_twitter_img', esc_url( get_post_meta( $post->ID, '_metaseo_metatwitter-image', true ) ) );
					}
				}
			}
			$offset += $increment;

			if ( $offset >= $total_count_posts ) {
				$count_items = $total_count_posts;
			} else {
				$count_items = $offset;
			}
		}
		$data = array();

		$data['count'] = $count_items;
		$data['total'] = $total_count_posts + $total_count_terms;

		$data['offset'] = $offset;
		wp_send_json_success( $data );
		exit();
	}
}


/** Function seopress_do_real_preview() called by wp_ajax hooks: {'seopress_do_real_preview'} **/
/** Parameters found in function seopress_do_real_preview(): {"get": ["post_id", "tax_name"]} **/
function seopress_do_real_preview() {
	check_ajax_referer( 'seopress_real_preview_nonce', '_ajax_nonce', true );

	if ( ! is_admin() || ! isset( $_GET['post_id'] ) ) {
		return;
	}

	$id      = absint( $_GET['post_id'] );
	$taxname = isset( $_GET['tax_name'] ) ? sanitize_key( $_GET['tax_name'] ) : null;

	if ( ! $id ) {
		return;
	}

	// Object-level capability check. The generic edit_posts cap is not enough:
	// the caller must be allowed to edit the specific object being analysed,
	// otherwise a low-privileged user could mutate analysis metadata for posts
	// or terms they do not own. For a taxonomy preview, $id is a term ID, so we
	// gate on the taxonomy's own edit_terms capability instead.
	if ( ! empty( $taxname ) ) {
		$taxonomy = get_taxonomy( $taxname );
		if ( ! $taxonomy || ! current_user_can( $taxonomy->cap->edit_terms ) ) {
			return;
		}
	} elseif ( ! current_user_can( 'edit_post', $id ) ) {
		return;
	}

	if ( 'yes' === get_post_meta( $id, '_seopress_redirections_enabled', true ) ) {
		$data['title'] = __( 'A redirect is active for this URL. Turn it off to get the Google preview and content analysis.', 'wp-seopress' );
		wp_send_json_error( $data );
		return;
	}

	$dom_result = seopress_get_service( 'RequestPreview' )->getDomById( $id, $taxname );

	if ( ! $dom_result['success'] ) {
		$default_response = array(
			'title'     => '...',
			'meta_desc' => '...',
		);

		switch ( $dom_result['code'] ) {
			case 404:
				$default_response['title'] = __( 'To get your Google snippet preview, publish your post!', 'wp-seopress' );
				break;
			case 401:
				$default_response['title'] = __( 'Your site is protected by an authentication.', 'wp-seopress' );
				break;
			case 'blocked':
				$default_response['title'] = __( 'Content analysis was blocked (HTTP 403/503). A CDN, firewall or security plugin is preventing your server from loading the preview.', 'wp-seopress' );
				break;
			case 'unreachable':
				$default_response['title'] = __( 'Your site could not be reached for content analysis. Please check your server, DNS or firewall configuration.', 'wp-seopress' );
				break;
		}

		wp_send_json_success( $default_response );
		return;
	}

	$str = $dom_result['body'];

	$data = seopress_get_service( 'DomFilterContent' )->getData( $str, $id );

	if ( ! empty( $taxname ) ) {
		wp_send_json_success( $data );
	}

	$data = seopress_get_service( 'DomAnalysis' )->getDataAnalyze(
		$data,
		array(
			'id' => $id,
		)
	);

	$keywords = seopress_get_service( 'DomAnalysis' )->getKeywords(
		array(
			'id' => $id,
		)
	);

	// Save analysis data first so getScore() reads fresh values from the database.
	seopress_get_service( 'ContentAnalysisDatabase' )->saveData( $id, $data, $keywords );

	$post          = get_post( $id );
	$score         = seopress_get_service( 'DomAnalysis' )->getScore( $post );
	$data['score'] = $score;
	seopress_get_service( 'ContentAnalysisDatabase' )->saveData( $id, $data, $keywords );

	/**
	 * We delete old values because we have a new structure
	 *
	 * @deprecated
	 * @since 7.3.0
	 */
	delete_post_meta( $id, '_seopress_content_analysis_api' );
	delete_post_meta( $id, '_seopress_analysis_data' );

	// Re-enable QM.
	remove_filter( 'user_has_cap', 'seopress_disable_qm', 10, 3 );

	wp_send_json_success( $data );
}


