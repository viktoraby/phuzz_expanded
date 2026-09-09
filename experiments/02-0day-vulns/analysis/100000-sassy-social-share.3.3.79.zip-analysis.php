<?php
/***
*
*Found actions: 11
*Found functions:9
*Extracted functions:8
*Total parameter names extracted: 3
*Overview: {'save_facebook_shares': {'heateor_sss_save_facebook_shares', 'nopriv_heateor_sss_save_facebook_shares'}, 'gdpr_notification_read': {'heateor_sss_gdpr_notification_read'}, 'import_config': {'heateor_sss_import_config'}, 'export_config': {'heateor_sss_export_config'}, 'clear_share_count_cache': {'heateor_sss_clear_share_count_cache'}, 'twitcount_notification_read': {'heateor_sss_twitcount_notification_read'}, 'clear_shorturl_cache': {'heateor_sss_clear_shorturl_cache'}, 'fetch_share_counts': {'heateor_sss_sharing_count', 'nopriv_heateor_sss_sharing_count'}, 'twitter_share_notification_read': {'heateor_sss_twitter_share_notification_read'}}
*
***/

/** Function save_facebook_shares() called by wp_ajax hooks: {'heateor_sss_save_facebook_shares', 'nopriv_heateor_sss_save_facebook_shares'} **/
/** Parameters found in function save_facebook_shares(): {"get": ["share_counts"]} **/
function save_facebook_shares() {
		
		if ( isset( $_GET['share_counts'] ) && is_array( $_GET['share_counts'] ) && count( $_GET['share_counts'] ) > 0 ) {
			$target_urls = array_map( 'intval', $_GET['share_counts'] );
		} else {
			$this->ajax_response( array( 'status' => 0, 'message' => __( 'Invalid request' ) ) );
		}

		$multiplier = 60;
		if ( $this->options['share_count_cache_refresh_count'] != '' ) {
			switch ( $this->options['share_count_cache_refresh_unit'] ) {
				case 'seconds':
					$multiplier = 1;
					break;

				case 'minutes':
					$multiplier = 60;
					break;
				
				case 'hours':
					$multiplier = 3600;
					break;

				case 'days':
					$multiplier = 3600*24;
					break;

				default:
					$multiplier = 60;
					break;
			}
			$transient_expiration_time = $multiplier * $this->options['share_count_cache_refresh_count'];
		}

		foreach ( $target_urls as $key => $value ) {
			$transient_id = $this->get_share_count_transient_id( $key );
			$share_count_transient = get_transient( 'heateor_sss_share_count_' . $transient_id );
			if ( $share_count_transient !== false ) {
				$share_count_transient['facebook'] = $value;
				if ( $this->options['share_count_cache_refresh_count'] != '' ) {
					$saved_share_count = $this->get_saved_share_counts( $transient_id, $key );
					$saved_share_count['facebook'] = $value;
					set_transient( 'heateor_sss_share_count_' . $transient_id, $share_count_transient, $transient_expiration_time );
					$this->update_share_counts( $key, $saved_share_count );
				}
			}
		}
		die;

	}


/** Function gdpr_notification_read() called by wp_ajax hooks: {'heateor_sss_gdpr_notification_read'} **/
/** No params detected :-/ **/


/** Function import_config() called by wp_ajax hooks: {'heateor_sss_import_config'} **/
/** Parameters found in function import_config(): {"post": ["config"]} **/
function import_config() {
		
		if ( current_user_can( 'manage_options' ) ) {
		    if ( check_ajax_referer( 'heateor_sss_admin_options_script', 'nonce' ) === false ) {
				die;
			}
			if ( isset( $_POST['config'] ) && strlen( trim( $_POST['config'] ) ) > 0 ) {
				$config = json_decode( stripslashes( trim( $_POST['config'] ) ), true );
				if ( is_array( $config ) && count( $config ) > 0 ) {
					$config = array_map( array( $this, 'sanitize_configuration_array' ), $config );
					update_option( 'heateor_sss', $config );
					die( json_encode(
						array(
							'success' => 1
						)
					) );
				}
			}
			die;
		}
	
	}


/** Function export_config() called by wp_ajax hooks: {'heateor_sss_export_config'} **/
/** No params detected :-/ **/


/** Function clear_share_count_cache() called by wp_ajax hooks: {'heateor_sss_clear_share_count_cache'} **/
/** No params detected :-/ **/


/** Function twitcount_notification_read() called by wp_ajax hooks: {'heateor_sss_twitcount_notification_read'} **/
/** No params detected :-/ **/


/** Function clear_shorturl_cache() called by wp_ajax hooks: {'heateor_sss_clear_shorturl_cache'} **/
/** No params detected :-/ **/


/** Function fetch_share_counts() called by wp_ajax hooks: {'heateor_sss_sharing_count', 'nopriv_heateor_sss_sharing_count'} **/
/** Parameters found in function fetch_share_counts(): {"get": ["urls"]} **/
function fetch_share_counts() {

		if ( isset( $_GET['urls'] ) && is_array( $_GET['urls'] ) && count( $_GET['urls'] ) > 0 ) {
			$target_urls = array_map( array( $this, 'sanitize_url_array' ), array_unique( $_GET['urls'] ) );
			if ( ! is_array( $target_urls ) ) {
				$target_urls = array();
			}
		} else {
			$this->ajax_response( array( 'status' => 0, 'message' => __( 'Invalid request' ) ) );
		}
		$horizontal_sharing_networks = isset( $this->options['horizontal_re_providers'] ) ? $this->options['horizontal_re_providers'] : array();
		$vertical_sharing_networks = isset( $this->options['vertical_re_providers'] ) ? $this->options['vertical_re_providers'] : array();
		$sharing_networks = array_unique( array_merge( $horizontal_sharing_networks, $vertical_sharing_networks ) );
		if ( count( $sharing_networks ) == 0 ) {
			$this->ajax_response( array( 'status' => 0, 'message' => __( 'Providers not selected' ) ) );
		}
		
		$response_data = array();
		$ajax_response = array();

		$multiplier = 60;
		if ( $this->options['share_count_cache_refresh_count'] != '' ) {
			switch ( $this->options['share_count_cache_refresh_unit'] ) {
				case 'seconds':
					$multiplier = 1;
					break;

				case 'minutes':
					$multiplier = 60;
					break;
				
				case 'hours':
					$multiplier = 3600;
					break;

				case 'days':
					$multiplier = 3600*24;
					break;

				default:
					$multiplier = 60;
					break;
			}
			$transient_expiration_time = $multiplier * $this->options['share_count_cache_refresh_count'];
		}

		$target_urls_array = array();
		$target_urls_array[] = $target_urls;
		$target_urls_array = apply_filters( 'heateor_sss_target_share_urls', $target_urls_array );
		$share_count_transient_array = array();
		if ( in_array( 'facebook', $sharing_networks ) ) {
			$ajax_response['facebook_urls'] = $target_urls_array;
		}
		
		foreach ( $target_urls_array as $target_urls ) {
			$share_count_transients = array();
			foreach ( $target_urls as $target_url ) {
				$share_count_transient = array();
				foreach ( $sharing_networks as $provider ) {
					switch ( $provider ) {
						case 'twitter':
							$url = "https://counts.twitcount.com/counts.php?url=" . $target_url;
							break;
						case 'X':
							$url = "https://counts.twitcount.com/counts.php?url=" . $target_url;
							break;
						case 'reddit':
							$url = 'https://www.reddit.com/api/info.json?url=' . $target_url;
							break;
						case 'pinterest':
							$url = 'https://api.pinterest.com/v1/urls/count.json?callback=heateorSss&url=' . $target_url;
							break;
						case 'buffer':
							$url = 'https://api.bufferapp.com/1/links/shares.json?url=' . $target_url;
							break;
						case 'vkontakte':
							$url = 'https://vk.com/share.php?act=count&url=' . $target_url;
							break;
						case 'Odnoklassniki':
							$url = 'https://connect.ok.ru/dk?st.cmd=extLike&tp=json&ref=' . $target_url;
							break;
						case 'Fintel':
							$url = 'https://fintel.io/api/pageStats?url=' . $target_url;
							break;
						default:
							$url = '';
					}
					if ( $url == '' ) { continue; }
					$response = wp_remote_get( $url,  array( 'timeout' => 15, 'user-agent'  => 'Sassy-Social-Share' ) );
					if ( ! is_wp_error( $response ) && isset( $response['response']['code'] ) && 200 === $response['response']['code'] ) {
						$body = wp_remote_retrieve_body( $response );
						if ( $provider == 'pinterest' ) {
							$body = str_replace( array( 'heateorSss(', ')' ), '', $body );
						}
						if ( $provider != 'vkontakte' ) {
							$body = json_decode( $body );
						}
						switch ( $provider ) {
							case 'facebook':
								if ( ! empty( $body->engagement ) && isset( $body->engagement->share_count ) ) {
									$share_count_transient['facebook'] = ( isset( $body->engagement->reaction_count ) ? $body->engagement->reaction_count : 0 ) + ( isset( $body->engagement->comment_count ) ? $body->engagement->comment_count : 0 ) + $body->engagement->share_count;
								} else {
									$share_count_transient['facebook'] = 0;
								}
								break;
							case 'twitter':
								if ( ! empty( $body->count ) ) {
									$share_count_transient['twitter'] = $body->count;
								} else {
									$share_count_transient['twitter'] = 0;
								}
								break;
							case 'X':
								if ( ! empty( $body->count ) ) {
									$share_count_transient['X'] = $body->count;
								} else {
									$share_count_transient['X'] = 0;
								}
								break;
							case 'linkedin':
								if ( ! empty( $body->count ) ) {
									$share_count_transient['linkedin'] = $body->count;
								} else {
									$share_count_transient['linkedin'] = 0;
								}
								break;
							case 'reddit':
								$share_count_transient['reddit'] = 0;
								if ( ! empty( $body->data->children ) ) {
									$children = $body->data->children;
									$ups = $downs = 0;
									foreach ( $children as $child ) {
						                $ups += ( int ) $child->data->ups;
						                $downs += ( int ) $child->data->downs;
						            }
						            $score = $ups - $downs;
						            if ( $score < 0 ) {
						            	$score = 0;
						            }
									$share_count_transient['reddit'] = $score;
								}
								break;
							case 'pinterest':
								if ( ! empty( $body->count ) ) {
									$share_count_transient['pinterest'] = $body->count;
								} else {
									$share_count_transient['pinterest'] = 0;
								}
								break;
							case 'buffer':
								if ( ! empty( $body->shares ) ) {
									$share_count_transient['buffer'] = $body->shares;
								} else {
									$share_count_transient['buffer'] = 0;
								}
								break;
							case 'vkontakte':
								if ( ! empty( $body ) ) {
									$share_count_transient['vkontakte'] = (int) str_replace( array( 'VK.Share.count(0, ', ' );' ), '', $body );
								} else {
									$share_count_transient['vkontakte'] = 0;
								}
								break;
							case 'Odnoklassniki':
								if ( ! empty( $body ) && isset( $body->count ) ) {
									$share_count_transient['Odnoklassniki'] = $body->count;
								} else {
									$share_count_transient['Odnoklassniki'] = 0;
								}
								break;
							case 'Fintel':
								if ( ! empty( $body ) && isset( $body->points ) ) {
									$share_count_transient['Fintel'] = $body->points;
								} else {
									$share_count_transient['Fintel'] = 0;
								}
								break;
						}
					} else {
						$share_count_transient[$provider] = 0;
					}
				}
				$share_count_transients[] = $share_count_transient;
			}
			$share_count_transient_array[] = $share_count_transients;
		}
		$final_share_count_transient = array();
		for ( $i = 0; $i < count( $target_urls_array[0] ); $i++ ) {
			$final_share_count_transient = $share_count_transient_array[0][$i];
			for ( $j = 1; $j < count( $share_count_transient_array ); $j++ ) {
				foreach ( $final_share_count_transient as $key => $val ) {
					$final_share_count_transient[$key] += $share_count_transient_array[$j][$i][$key];
				}
			}
			$response_data[$target_urls_array[0][$i]] = $final_share_count_transient;
			if ( $this->options['share_count_cache_refresh_count'] != '' ) {
				set_transient( 'heateor_sss_share_count_' . $this->get_share_count_transient_id( $target_urls_array[0][$i] ), $final_share_count_transient, $transient_expiration_time );
				// update share counts saved in the database
				$this->update_share_counts( $target_urls_array[0][$i], $final_share_count_transient );
			}
		}
		do_action( 'heateor_sss_share_count_ajax_hook', $response_data );
		
		$ajax_response['status'] = 1;
		$ajax_response['message'] = $response_data;

		$this->ajax_response( $ajax_response );

	}


/** Function twitter_share_notification_read() called by wp_ajax hooks: {'heateor_sss_twitter_share_notification_read'} **/
/** No function found :-/ **/


