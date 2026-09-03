<?php
/***
*
*Found actions: 29
*Found functions:27
*Extracted functions:23
*Total parameter names extracted: 18
*Overview: {'ajax_disable_legacy_language_switcher': {'trp_disable_legacy_language_switcher'}, 'ajax_get_terms': {'trp_glossary_get_terms'}, 'ajax_mark_forum_posts_read': {'trp_mark_forum_posts_read'}, 'trp_update_database': {'trp_update_database'}, 'Content-Type: text/javascript': {'trp-block-ls-shortcode.js'}, 'add_term': {'trp_glossary_add_term'}, 'gettext': {'trp_string_translation_get_missing_gettext_strings', 'trp_string_translation_get_strings_by_original_ids_gettext'}, 'install_plugins_request': {'trp_install_plugins'}, 'save_translations': {'trp_save_translations_regular'}, 'scan_gettext': {'trp_scan_gettext'}, 'create_translation_block': {'trp_create_translation_block'}, 'ajax_get_similar_string_translation': {'trp_get_similar_string_translation'}, 'gettext_save_translations': {'trp_save_translations_gettext'}, 'replace_in_dictionary': {'trp_glossary_replace_in_dictionary'}, 'gettext_get_translations': {'trp_get_translations_gettext'}, 'trp_ai_recheck_quota': {'trp_ai_recheck_quota'}, 'ajax_save_language_switcher': {'trp_language_switcher_save'}, 'ajax_get_forum_posts': {'trp_get_forum_posts'}, 'test_api_key': {'test_api_key'}, 'search_dictionary': {'trp_glossary_search_dictionary'}, 'get_translations': {'nopriv_trp_get_translations_regular', 'trp_get_translations_regular'}, 'trp_dismiss_gettext_notice': {'trp_dismiss_gettext_notice'}, 'edit_term': {'trp_glossary_edit_term'}, 'process_js_strings_in_translation_editor': {'trp_process_js_strings_in_translation_editor'}, 'delete_term': {'trp_glossary_delete_term'}, 'save_editor_user_meta': {'trp_save_editor_user_meta'}, 'split_translation_block': {'trp_split_translation_block'}}
*
***/

/** Function ajax_disable_legacy_language_switcher() called by wp_ajax hooks: {'trp_disable_legacy_language_switcher'} **/
/** Parameters found in function ajax_disable_legacy_language_switcher(): {"post": ["nonce"]} **/
function ajax_disable_legacy_language_switcher(): void {
        if ( ! current_user_can( apply_filters( 'trp_settings_capability', 'manage_options' ) ) ) {
            wp_send_json_error( __( 'Permission denied.', 'translatepress-multilingual' ), 403 );
        }

        $nonce = isset( $_POST['nonce'] )
            ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) )
            : '';

        if ( ! wp_verify_nonce( $nonce, 'trp_disable_legacy' ) ) {
            wp_send_json_error( __( 'Invalid nonce.', 'translatepress-multilingual' ), 403 );
        }

        $adv = get_option( 'trp_advanced_settings', [] );
        if ( ! is_array( $adv ) ) {
            $adv = [];
        }

        // Flip legacy OFF
        $adv['load_legacy_language_switcher'] = 'no';
        update_option( 'trp_advanced_settings', $adv );

        TRP_Plugin_Notifications::get_instance()->dismiss_notification( 'trp_ls_v2_intro' );

        wp_send_json_success( __( 'Legacy disabled.', 'translatepress-multilingual' ) );
    }


/** Function ajax_get_terms() called by wp_ajax hooks: {'trp_glossary_get_terms'} **/
/** No params detected :-/ **/


/** Function ajax_mark_forum_posts_read() called by wp_ajax hooks: {'trp_mark_forum_posts_read'} **/
/** No params detected :-/ **/


/** Function trp_update_database() called by wp_ajax hooks: {'trp_update_database'} **/
/** Parameters found in function trp_update_database(): {"request": ["trp_updb_nonce", "initiate_update", "trp_updb_action", "trp_updb_lang", "trp_updb_batch", "trp_updb_extra_params"]} **/
function trp_update_database(){
		if ( ! current_user_can( apply_filters('trp_update_database_capability', 'manage_options') ) ){
			$this->stop_and_print_error( __('Update aborted! Your user account doesn\'t have the capability to perform database updates.', 'translatepress-multilingual' ) );
		}

		$nonce = isset( $_REQUEST['trp_updb_nonce'] ) ? wp_verify_nonce( sanitize_text_field( $_REQUEST['trp_updb_nonce'] ), 'tpupdatedatabase' ) : false;
		if ( $nonce === false ){
			$this->stop_and_print_error( __('Update aborted! Invalid nonce.', 'translatepress-multilingual' ) );
		}

		$request = array();
		$request['progress_message'] = '';
		$updates_needed = $this->get_updates_details();
        if (isset($_REQUEST['initiate_update']) && $_REQUEST['initiate_update']=== "true" ){
            update_option('trp_show_error_db_message', 'no');
        }
		if ( empty ( $_REQUEST['trp_updb_action'] ) ){
			foreach( $updates_needed as $update_action_key => $update ) {
				$option = get_option( $update['option_name'], 'is not set' );
				if ( $option === 'no' ) {
					$_REQUEST['trp_updb_action'] = $update_action_key;
					break;
				}
			}
			if ( empty ( $_REQUEST['trp_updb_action'] ) ){
				$back_to_settings_button = '<a class="trp-submit-btn button" href="' . site_url('wp-admin/options-general.php?page=translate-press') . '">' . esc_html__('Back to TranslatePress Settings', 'translatepress-multilingual' ) . '</a>';
				// finished successfully
				echo json_encode( array(
					'trp_update_completed' => 'yes',
					'progress_message'  => '<p><strong>' . __('Successfully updated database!', 'translatepress-multilingual' ) . '</strong></p>' . $back_to_settings_button
				));
				wp_die();
			}else{
				$_REQUEST['trp_updb_lang'] = $this->settings['translation-languages'][0];
				$_REQUEST['trp_updb_batch'] = 0;
                $updb_action = sanitize_text_field( $_REQUEST['trp_updb_action'] );

                $update_message_initial = isset( $updates_needed[ $updb_action ]['message_initial'] ) ?
                                            $updates_needed[ $updb_action ]['message_initial']
                                            : __('Updating database to version %s+', 'translatepress-multilingual' );

                $update_message_processing = $this->get_updates_processing_message( $updb_action ) ?
                                             $this->get_updates_processing_message( $updb_action ) :
                                             __('Processing table for language %s...', 'translatepress-multilingual' );

                if ($updates_needed[ $updb_action ]['version'] != 0) {
                    $request['progress_message'] .= '<p>' . sprintf( $update_message_initial, $updates_needed[ $updb_action ]['version'] ) . '</p>';
                }
                $request['progress_message'] .= '<br>' . sprintf( $update_message_processing, sanitize_text_field( $_REQUEST['trp_updb_lang'] ) );//phpcs:ignore
			}
		}else{
			if ( !isset( $updates_needed[ $_REQUEST['trp_updb_action'] ] ) ){
				$this->stop_and_print_error( __('Update aborted! Incorrect action.', 'translatepress-multilingual' ) );
			}
			if ( !in_array( $_REQUEST['trp_updb_lang'], $this->settings['translation-languages'] ) ) {//phpcs:ignore
				$this->stop_and_print_error( __('Update aborted! Incorrect language code.', 'translatepress-multilingual' ) );
			}
		}

		$request['trp_updb_action'] = sanitize_text_field( $_REQUEST['trp_updb_action'] );
		if ( !empty( $_REQUEST['trp_updb_batch'] ) && (int) $_REQUEST['trp_updb_batch'] > 0 ) {
			$get_batch = (int)$_REQUEST['trp_updb_batch'];
		}else{
			$get_batch = 0;
		}

        $extra_params = isset( $_REQUEST['trp_updb_extra_params'] ) ? json_decode(base64_decode(sanitize_text_field($_REQUEST['trp_updb_extra_params'] )), true) : array();
        if (!is_array($extra_params)) {
            $extra_params = array();
        }

		$request['trp_updb_batch'] = 0;
		$update_details = $updates_needed[ sanitize_text_field( $_REQUEST['trp_updb_action'] )];
		$batch_size = apply_filters( 'trp_updb_batch_size', $update_details['batch_size'], sanitize_text_field( $_REQUEST['trp_updb_action'] ), $update_details );
		$language_code = isset( $_REQUEST['trp_updb_lang'] ) ? sanitize_text_field( $_REQUEST['trp_updb_lang'] ) : '';

		if ( ! $this->trp_query ) {
			$trp = TRP_Translate_Press::get_trp_instance();
			/* @var TRP_Query */
			$this->trp_query = $trp->get_component( 'query' );
		}

		$start_time = microtime(true);
		$duration = 0;
		while( $duration < 2 ){
			$inferior_limit = $batch_size * $get_batch;
            $callback_return = call_user_func( $update_details['callback'], $language_code, $inferior_limit, $batch_size, $extra_params );
			if ( (isset($callback_return['finalize_with_language']) && $callback_return['finalize_with_language']) || ($callback_return === true)  ) {
				break;
			}else {
				$get_batch = $get_batch + 1;
                $extra_params = isset($callback_return['extra_params']) ? $callback_return['extra_params'] : array();
			}
			$stop_time = microtime( true );
			$duration = $stop_time - $start_time;
		}
        // the callback functions return different true or an array with finalized_with_language bool and extra_params based on what they need.
        // In case call_user_func fails with string, object, etc, it will continue with the callback and batching.
        // For example, if the function called uses to much memory, it will just continue.
        $finalized_with_language = (isset($callback_return['finalize_with_language']) && $callback_return['finalize_with_language']) || ($callback_return === true);

        if ( !$finalized_with_language ) {
			$request['trp_updb_batch'] = $get_batch;
            $request['trp_updb_extra_params'] = isset($callback_return['extra_params']) ? $callback_return['extra_params'] : array();
		}

		if ( $finalized_with_language ) {
            // finished with the current language
            $index = array_search( $language_code, $this->settings['translation-languages'] );

            if ( isset ( $this->settings['translation-languages'][ $index + 1 ] ) && (!isset($update_details['execute_only_once']) || $update_details['execute_only_once'] == false)) {
                // next language code in array
                $request['trp_updb_lang']    = $this->settings['translation-languages'][ $index + 1 ];
                $request['progress_message'] .= __( ' done.', 'translatepress-multilingual' ) . '</br>';
                $update_message_processing   = $this->get_updates_processing_message( sanitize_text_field( $_REQUEST['trp_updb_action'] ) ) ?
                    $this->get_updates_processing_message( sanitize_text_field( $_REQUEST['trp_updb_action'] ) )
                    : __( 'Processing table for language %s...', 'translatepress-multilingual' );
                $request['progress_message'] .= '</br>' . sprintf( $update_message_processing, $request['trp_updb_lang'] );

                    } else {
                        // finish action due to completing all the translation languages
                        $request['progress_message'] .= __( ' done.', 'translatepress-multilingual' ) . '</br>';
                        $request['trp_updb_lang']    = '';
                        $option_result = get_option( $update_details['option_name'], 'no' );

                        // the next IF is helpful in case we set the option to something else (such as seopack_inactive) during update
                        if ( $option_result === 'no' ) {
                            // setting option to yes will stop showing the admin notice
                            update_option( $update_details['option_name'], 'yes' );
                        }
                        $request['trp_updb_action'] = '';
                    }
		}else{
			$request['trp_updb_lang'] = $language_code;
            $request['progress_message'] .= '.';
		}

        if ( $this->db->last_error != '' ){
            $request['progress_message'] = '<p><strong>SQL Error:</strong> ' . esc_html($this->db->last_error) . '</p>' . $request['progress_message'];
        }
		$query_arguments = array(
			'action'                    => 'trp_update_database',
			'trp_updb_action'           => $request['trp_updb_action'],
			'trp_updb_lang'             => $request['trp_updb_lang'],
			'trp_updb_batch'            => $request['trp_updb_batch'],
			'trp_updb_extra_params'     => isset($request['trp_updb_extra_params']) ? base64_encode(json_encode($request['trp_updb_extra_params'])) : base64_encode(json_encode(array())),
			'trp_updb_nonce'            => wp_create_nonce('tpupdatedatabase'),
			'trp_update_completed'      => 'no',
			'progress_message'          => $request['progress_message']
		);
		echo( json_encode( $query_arguments ));
		wp_die();
	}


/** Function Content-Type: text/javascript() called by wp_ajax hooks: {'trp-block-ls-shortcode.js'} **/
/** No function found :-/ **/


/** Function add_term() called by wp_ajax hooks: {'trp_glossary_add_term'} **/
/** No params detected :-/ **/


/** Function gettext() called by wp_ajax hooks: {'trp_string_translation_get_missing_gettext_strings', 'trp_string_translation_get_strings_by_original_ids_gettext'} **/
/** No function found :-/ **/


/** Function install_plugins_request() called by wp_ajax hooks: {'trp_install_plugins'} **/
/** Parameters found in function install_plugins_request(): {"post": ["action", "plugin_slug"]} **/
function install_plugins_request(){
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            check_ajax_referer( 'trp_install_plugins', 'security' );
            if ( ! current_user_can( 'install_plugins' ) ) {
                wp_die( -1, 403 );
            }
            if ( isset( $_POST['action'] ) && $_POST['action'] === 'trp_install_plugins' && !empty( $_POST['plugin_slug'] ) ) {
                $plugin_slug = sanitize_text_field($_POST['plugin_slug']);
                $short_slugs = $this->get_plugin_slugs();
                if ( isset( $short_slugs[$plugin_slug]) ){
                    if ( $this->install_upgrade_activate($plugin_slug) ){
                        $message = esc_html__('Active', 'translatepress-multilingual');
                    }else{
                        $message = wp_kses( sprintf( __('Could not install. Try again from <a href="%s" >Plugins Dashboard.</a>', 'translatepress-multilingual'), admin_url('plugins.php') ), array('a' => array( 'href' => array() ) ) );
                    }
                    wp_die( trp_safe_json_encode( $message ));//phpcs:ignore
                }
            }
        }
        wp_die();
    }


/** Function save_translations() called by wp_ajax hooks: {'trp_save_translations_regular'} **/
/** Parameters found in function save_translations(): {"post": ["action", "strings"]} **/
function save_translations(){
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX && current_user_can( apply_filters( 'trp_translating_capability', 'manage_options' ) ) ) {
			check_ajax_referer( 'save_translations', 'security' );
			if ( isset( $_POST['action'] ) && $_POST['action'] === 'trp_save_translations_regular' && !empty( $_POST['strings'] ) ) {
				$strings = json_decode(stripslashes($_POST['strings'])); /* phpcs:ignore */ /* sanitized downstream */
				$update_strings = $this->save_translations_of_strings( $strings );
			}
		}
		echo trp_safe_json_encode( $update_strings ); // phpcs:ignore
		die();
	}


/** Function scan_gettext() called by wp_ajax hooks: {'trp_scan_gettext'} **/
/** Parameters found in function scan_gettext(): {"post": ["action"]} **/
function scan_gettext() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX && current_user_can( apply_filters( 'trp_translating_capability', 'manage_options' ) ) ) {
			if ( isset( $_POST['action'] ) && $_POST['action'] === 'trp_scan_gettext' ) {
				check_ajax_referer( 'scangettextnonce', 'security' );
				$status = $this->scan();
				echo trp_safe_json_encode( $status ); //phpcs:ignore

			}
		}
		wp_die();
	}


/** Function create_translation_block() called by wp_ajax hooks: {'trp_create_translation_block'} **/
/** Parameters found in function create_translation_block(): {"post": ["action", "strings", "language", "original"]} **/
function create_translation_block(){
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX && current_user_can( apply_filters( 'trp_translating_capability', 'manage_options' ) ) ) {
			check_ajax_referer( 'merge_translation_block', 'security' );
			if ( isset( $_POST['action'] ) && $_POST['action'] === 'trp_create_translation_block' && !empty( $_POST['strings'] ) && !empty( $_POST['language'] ) && in_array( $_POST['language'], $this->settings['translation-languages'] ) && !empty( $_POST['original'] ) ) {
				$strings = json_decode( stripslashes( $_POST['strings'] ) ); /* phpcs:ignore */ /* sanitized downstream */

				if ( isset ( $this->settings['translation-languages']) ){
					$trp = TRP_Translate_Press::get_trp_instance();
					if ( ! $this->trp_query ) {
						$this->trp_query = $trp->get_component( 'query' );
					}
					if ( ! $this->translation_render ) {
						$this->translation_render = $trp->get_component( 'translation_render' );
					}

					$active_block_type = $this->trp_query->get_constant_block_type_active();
					foreach( $this->settings['translation-languages'] as $language ){
						if ( $language != $this->settings['default-language'] ){
							$dictionaries = $this->get_translation_for_strings( array(), array( stripslashes( $_POST['original'] ) ), $active_block_type, array() );/* phpcs:ignore */ /* sanitized downstream */
							break;
						}
					}

					/*
					 * Merging the dictionary received from get_translation_for_strings (which contains ID and possibly automatic translations) with
					 * ajax translated (which can contain manual translations)
					 */
					$originals_array_constructed = false;
					$originals = array();
					if ( isset( $dictionaries ) ){
						foreach ( $dictionaries as $language => $dictionary ){
							if ( $language == $this->settings['default-language'] )
								continue;

							foreach( $dictionary as $dictionary_string_key => $dictionary_string ){
								if ( !isset ($strings->$language) ){
									continue;
								}
								$ajax_translated_string_list = $strings->$language;

								foreach( $ajax_translated_string_list as $ajax_key => $ajax_string ) {
									if ( trp_full_trim( trp_sanitize_string( $ajax_string->original, false ) ) == $dictionary_string->original ) {
										if ( $ajax_string->translated != '' ) {
											$dictionaries[ $language ][ $dictionary_string_key ]->translated = trp_sanitize_string( $ajax_string->translated );
											$dictionaries[ $language ][ $dictionary_string_key ]->status     = (int) $ajax_string->status;
										}
										$dictionaries[ $language ][ $dictionary_string_key ]->block_type = (int) $ajax_string->block_type;
									}
									$dictionaries[ $language ][ $dictionary_string_key ]->new_translation_block = true;
								}

								if( !$originals_array_constructed ){
									$originals[] = $dictionary_string->original;
								}
							}

							$originals_array_constructed = true;
						}
						$this->save_translations_of_strings( $dictionaries, $active_block_type );

						// update deactivated languages
						$copy_of_originals = $originals;
						if ( $originals_array_constructed ){
							$table_names = $this->trp_query->get_all_table_names( $this->settings['default-language'], $this->settings['translation-languages'] );
							if ( count( $table_names ) > 0 ){
								foreach( $table_names as $table_name ) {
									$originals = $copy_of_originals;
									$language = $this->trp_query->get_language_code_from_table_name( $table_name );
									$existing_dictionary = $this->trp_query->get_string_rows( array(), $originals, $language, ARRAY_A );
									foreach ( $existing_dictionary as $string_key => $string ){
										foreach ( $originals as $original_key => $original ){
											if ( $string['original'] == $original ){
												unset( $originals[$original_key] );
											}
										}
										$existing_dictionary[$string_key]['block_type'] = $active_block_type;
										$originals = array_values( $originals );
									}
									$this->trp_query->insert_strings( $originals, $language, $active_block_type );
									$this->trp_query->update_strings( $existing_dictionary, $language );
								}

							}
						}

						echo trp_safe_json_encode( $dictionaries );//phpcs:ignore
					}
				}

			}
		}
		die();
	}


/** Function ajax_get_similar_string_translation() called by wp_ajax hooks: {'trp_get_similar_string_translation'} **/
/** Parameters found in function ajax_get_similar_string_translation(): {"post": ["action", "original_string", "language", "type", "number"]} **/
function ajax_get_similar_string_translation(){
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            if (isset($_POST['action']) && $_POST['action'] === 'trp_get_similar_string_translation' && !empty($_POST['original_string']) && !empty($_POST['language']) && !empty($_POST['type']) && in_array($_POST['language'], $this->settings['translation-languages']) )
            {
                if ( ! current_user_can( apply_filters( 'trp_translating_capability', 'manage_options' ) ) ) {
                    wp_die( -1, 403 );
                }
                global $TRP_LANGUAGE;
                check_ajax_referer('getsimilarstring', 'security');
                $string = ( isset($_POST['original_string']) ) ? $_POST['original_string'] : '';//phpcs:ignore
                $language_code = ( isset($_POST['language']) ) ? sanitize_text_field( $_POST['language'] ) : $TRP_LANGUAGE;
                $type = ( isset($_POST['type']) ) ? sanitize_text_field( $_POST['type'] ) : '';
                $number = ( isset($_POST['number']) ) ? (int) $_POST['number'] : 3;

                $trp = TRP_Translate_Press::get_trp_instance();
                if ( ! $this->trp_query ) {
                    $this->trp_query = $trp->get_component( 'query' );
                }

                $table_name = null;

                // there is no dictionary table with the default language
                if ( $language_code !== $this->settings['default-language'] ) {
                    // data-trp-translate-id, data-trp-translate-id-innertext are in the wp_trp_dictionary_* tables
                    $table_name = $this->trp_query->get_table_name( $language_code );
                }

                if( $type == "gettext" ){
                    $table_name = $this->trp_query->get_gettext_table_name( $language_code );
                }

                if ( $table_name === null ) {
                    $dictionary = array();
                }else{
                    $dictionary = $this->get_similar_string_translation( $string, $number, $table_name );
                }
                echo json_encode($dictionary);
                wp_die();
            }
        }
        echo json_encode(array());
        wp_die();
    }


/** Function gettext_save_translations() called by wp_ajax hooks: {'trp_save_translations_gettext'} **/
/** Parameters found in function gettext_save_translations(): {"post": ["action", "strings"]} **/
function gettext_save_translations(){
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX && current_user_can( apply_filters( 'trp_translating_capability', 'manage_options' ) ) ) {
			if (isset($_POST['action']) && $_POST['action'] === 'trp_save_translations_gettext' && !empty($_POST['strings'])) {
				check_ajax_referer( 'gettext_save_translations', 'security' );
				$strings = json_decode(stripslashes($_POST['strings']));/* phpcs:ignore */ /* properly sanitized bellow */
				$update_strings = array();
				foreach ( $strings as $language => $language_strings ) {
					if ( in_array( $language, $this->settings['translation-languages'] ) ) {
						$update_strings[ $language ] = array();
						foreach( $language_strings as $string ) {
							if ( isset( $string->id ) && is_numeric( $string->id ) ) {
								$translated = trp_sanitize_string( $string->translated );
								$status     = ! empty( $translated ) ? TRP_Query::HUMAN_REVIEWED : TRP_Query::NOT_TRANSLATED;

								array_push($update_strings[ $language ], array(
									'id' => (int)$string->id,
                                    'original' => trp_sanitize_string( $string->original, false ),
									'translated' => $translated,
									'domain' => sanitize_text_field( $string->domain ),
									'status' => $status,
									'plural_form' => (int)$string->plural_form,
									'context' => $string->context
								));
							}
						}
					}
				}

				if ( ! $this->trp_query ) {
					$trp = TRP_Translate_Press::get_trp_instance();
					$this->trp_query = $trp->get_component( 'query' );
				}

				foreach( $update_strings as $language => $update_string_array ) {
                    $gettext_insert_update = $this->trp_query->get_query_component('gettext_insert_update');
                    $gettext_insert_update->update_gettext_strings( $update_string_array, $language, array('id','translated', 'status') );
                    $this->trp_query->remove_possible_duplicates($update_string_array, $language, 'gettext');
				}

                do_action('trp_save_editor_translations_gettext_strings', $update_strings, $this->settings);
			}
		}
		echo trp_safe_json_encode( $update_strings );//phpcs:ignore
		wp_die();
	}


/** Function replace_in_dictionary() called by wp_ajax hooks: {'trp_glossary_replace_in_dictionary'} **/
/** Parameters found in function replace_in_dictionary(): {"post": ["new", "existing"]} **/
function replace_in_dictionary() {
        $this->verify_request( self::REPLACE_ACTION );

        $new_values      = ( isset( $_POST['new'] ) && is_array( $_POST['new'] ) )
            ? array_map( 'sanitize_text_field', wp_unslash( $_POST['new'] ) )
            : array();
        $existing_values = ( isset( $_POST['existing'] ) && is_array( $_POST['existing'] ) )
            ? array_map( 'sanitize_text_field', wp_unslash( $_POST['existing'] ) )
            : array();

        $query = $this->get_query();
        if ( ! $query ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Something went wrong. Please try again.', 'translatepress-multilingual' ) ) );
        }

        // Build the job: one entry per target language that has both a new value and
        // something to replace, plus a valid dictionary table. Uses an id cursor so each
        // row is processed exactly once (no runaway when the new value contains the old).
        $languages = array();
        foreach ( $this->get_target_language_codes() as $lang ) {
            $new_val      = isset( $new_values[ $lang ] ) ? trim( sanitize_text_field( $new_values[ $lang ] ) ) : '';
            $existing_val = isset( $existing_values[ $lang ] ) ? trim( sanitize_text_field( $existing_values[ $lang ] ) ) : '';

            if ( $new_val === '' || $existing_val === '' ) {
                continue;
            }

            $table = $query->get_table_name( $lang );
            if ( ! self::is_valid_dictionary_table( $table ) ) {
                continue;
            }

            $languages[ $lang ] = array(
                'table'    => $table,
                'existing' => $existing_val,
                'new'      => $new_val,
                'cursor'   => 0,
                'done'     => false,
                'count'    => 0,
            );
        }

        if ( empty( $languages ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Please provide at least one existing translation to replace.', 'translatepress-multilingual' ) ) );
        }

        // One bounded pass in this request — finishes small/medium jobs on the spot.
        // Both knobs are filterable so hosts can tune for their PHP/DB limits.
        $time_limit = (float) apply_filters( 'trp_glossary_replace_sync_time_limit', self::SYNC_TIME_LIMIT );
        $batch_size = max( 1, (int) apply_filters( 'trp_glossary_replace_sync_batch_size', self::SYNC_BATCH_SIZE ) );

        $start    = microtime( true );
        $finished = true;
        foreach ( $languages as $lang => &$info ) {
            while ( ! $info['done'] ) {
                $batch = self::replace_batch_in_table( $info['table'], $info['existing'], $info['new'], $info['cursor'], $batch_size, $lang );
                if ( $batch['error'] !== '' ) {
                    $info['done'] = true; // skip a broken table rather than loop forever
                    break;
                }
                $info['cursor']  = $batch['cursor'];
                $info['count']  += $batch['changed'];
                if ( $batch['done'] ) {
                    $info['done'] = true;
                    break;
                }
                if ( ( microtime( true ) - $start ) >= $time_limit ) {
                    $finished = false;
                    break 2;
                }
            }
        }
        unset( $info );

        $language_names = $this->get_english_language_names();

        if ( $finished ) {
            $counts = array();
            $total  = 0;
            foreach ( $languages as $lang => $info ) {
                $counts[ $lang ] = $info['count'];
                $total          += $info['count'];
            }

            $parts = array();
            foreach ( $counts as $lang => $count ) {
                $name    = isset( $language_names[ $lang ] ) ? $language_names[ $lang ] : $lang;
                $parts[] = sprintf(
                    /* translators: 1: language name, 2: number of entries. */
                    _n( '%1$s: %2$d entry updated', '%1$s: %2$d entries updated', $count, 'translatepress-multilingual' ),
                    $name,
                    $count
                );
            }

            wp_send_json_success( array(
                'message'    => esc_html__( 'Replacement complete.', 'translatepress-multilingual' ) . ' ' . implode( ', ', $parts ),
                'counts'     => $counts,
                'total'      => $total,
                'background' => false,
            ) );
        }

        // Too big for one request — hand the remainder to the batch processor (cron),
        // resuming from the cursors already advanced above.
        update_option( self::BATCH_JOB, array( 'languages' => $languages ), false );
        delete_option( 'trp_batch_todo_' . self::BATCH_FLAG );      // force a fresh todo list
        delete_option( 'trp_batch_found_rows_' . self::BATCH_FLAG );
        update_option( self::BATCH_FLAG, 'no' );                    // mark pending for the processor

        $batch_processor = TRP_Translate_Press::get_trp_instance()->get_component( 'batch_processor' );
        if ( $batch_processor ) {
            $batch_processor->schedule();
        }

        $so_far = 0;
        foreach ( $languages as $info ) {
            $so_far += $info['count'];
        }

        wp_send_json_success( array(
            'message'    => sprintf(
                /* translators: %d is the number of entries already updated. */
                esc_html__( 'This is a large replacement. %d entries were updated now and the rest will finish automatically in the background over the next few minutes.', 'translatepress-multilingual' ),
                $so_far
            ),
            'background' => true,
            'total'      => $so_far,
        ) );
    }


/** Function gettext_get_translations() called by wp_ajax hooks: {'trp_get_translations_gettext'} **/
/** Parameters found in function gettext_get_translations(): {"post": ["action", "string_ids", "language"]} **/
function gettext_get_translations() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			if ( isset( $_POST['action'] ) && $_POST['action'] === 'trp_get_translations_gettext' && ! empty( $_POST['string_ids'] ) && ! empty( $_POST['language'] ) && in_array( $_POST['language'], $this->settings['translation-languages'] ) ) {
				check_ajax_referer( 'gettext_get_translations', 'security' );
				if ( ! empty( $_POST['string_ids'] ) ) {
					$gettext_string_ids = json_decode( stripslashes( $_POST['string_ids'] ) ); /* phpcs:ignore */ /* sanitized when inserting in db */
				}
				else {
					$gettext_string_ids = array();
				}

				$current_language = sanitize_text_field( $_POST['language'] );
				$dictionaries     = array();

				if ( is_array( $gettext_string_ids ) ) {

					$trp = TRP_Translate_Press::get_trp_instance();
					if ( ! $this->trp_query ) {
						$this->trp_query = $trp->get_component( 'query' );
					}
					if ( ! $this->translation_manager ) {
						$this->translation_manager = $trp->get_component( 'translation_manager' );
					}


					$dictionaries[ $current_language ] = $this->trp_query->get_gettext_string_rows_by_ids( $gettext_string_ids, $current_language );

					/* build the original id array */
					$original_ids            = array();
					if ( ! empty( $dictionaries[ $current_language ] ) ) {
						foreach ( $dictionaries[ $current_language ] as $current_language_string ) {
							/* searching by original id */
							$original_ids[] = (int)$current_language_string['ot_id'];
						}
					}
					echo trp_safe_json_encode( array( // phpcs:ignore
						'originalIds' => $original_ids,
					) );

				}
			}
		}
		wp_die();
	}


/** Function trp_ai_recheck_quota() called by wp_ajax hooks: {'trp_ai_recheck_quota'} **/
/** Parameters found in function trp_ai_recheck_quota(): {"post": ["action"]} **/
function trp_ai_recheck_quota(){
    if ( defined( 'DOING_AJAX' ) && DOING_AJAX && current_user_can( apply_filters( 'trp_translating_capability', 'manage_options' ) ) ) {
        if ( isset( $_POST['action'] ) && $_POST['action'] === 'trp_ai_recheck_quota' ) {
            $nonce_okay = check_ajax_referer( 'trp-tpai-recheck', 'nonce' );
            if ( $nonce_okay ){
                $license = trim( (string) get_option( 'trp_license_key', '' ) );
                $status  = get_option( 'trp_license_status' );

                if ( $status === 'valid' ) {
                    $response = trp_mtapi_sync_license_call( $license );
                    if ( is_array( $response ) && ! is_wp_error( $response ) && isset( $response['response'] ) &&
                        isset( $response['response']['code']) && $response['response']['code'] == 200 ) {

                        $mtapi_url = (defined('MTAPI_URL')  ? MTAPI_URL : 'https://mtapi.translatepress.com' );

                        require_once("class-mtapi-customer.php");
                        $mtapi_server = new TRP_MTAPI_Customer($mtapi_url);
                        $site_status = $mtapi_server->lookup_site($license, home_url());

                        $site_status['quota'] = isset ( $site_status['quota'] ) ? $site_status['quota'] : 0;
                        $quota = intval(ceil($site_status['quota'] / 5));
                        echo trp_safe_json_encode( ['quota' => $quota ] ); //phpcs:ignore
                    }
                }
            }

        }
    }
    wp_die();
}


/** Function ajax_save_language_switcher() called by wp_ajax hooks: {'trp_language_switcher_save'} **/
/** Parameters found in function ajax_save_language_switcher(): {"post": ["nonce", "scope", "config"]} **/
function ajax_save_language_switcher(): void {
        if ( ! current_user_can( apply_filters( 'trp_settings_capability', 'manage_options' ) ) )
            wp_send_json_error( __( 'Permission denied.', 'translatepress-multilingual' ), 403 );

        $nonce = isset( $_POST['nonce'] )
            ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) )
            : '';

        if ( ! wp_verify_nonce( $nonce, 'trp_language_switcher_save' ) )
            wp_send_json_error( __( 'Invalid nonce.', 'translatepress-multilingual' ), 403 );

        $scope  = sanitize_key( wp_unslash( $_POST['scope'] ?? '' ) );

        $config_raw = isset( $_POST['config'] ) ? wp_unslash( $_POST['config'] ) : '{}'; //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        $config_str = is_string( $config_raw ) ? $config_raw : '{}';
        $config     = json_decode( $config_str, true );

        $allowed_scopes = [ 'floater', 'shortcode', 'menu' ];

        if ( ! in_array( $scope, $allowed_scopes, true ) || ! is_array( $config ) )
            wp_send_json_error( __( 'Settings scope unknown.', 'translatepress-multilingual' ), 400 );

        $sanitised = $this->sanitize_scope_config( $scope, $config );

        $options = get_option( 'trp_language_switcher_settings', [] );

        $options[ $scope ] = $sanitised;

        update_option( 'trp_language_switcher_settings', $options );

        wp_send_json_success( __( 'Settings saved.', 'translatepress-multilingual' ) );
    }


/** Function ajax_get_forum_posts() called by wp_ajax hooks: {'trp_get_forum_posts'} **/
/** No params detected :-/ **/


/** Function test_api_key() called by wp_ajax hooks: {'test_api_key'} **/
/** No params detected :-/ **/


/** Function search_dictionary() called by wp_ajax hooks: {'trp_glossary_search_dictionary'} **/
/** Parameters found in function search_dictionary(): {"post": ["term", "lang", "page"]} **/
function search_dictionary() {
        $this->verify_request( self::SEARCH_ACTION );

        global $wpdb;

        $term      = isset( $_POST['term'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['term'] ) ) ) : '';
        $only_lang = isset( $_POST['lang'] ) ? sanitize_text_field( wp_unslash( $_POST['lang'] ) ) : '';
        $page      = isset( $_POST['page'] ) ? max( 1, absint( $_POST['page'] ) ) : 1;
        $per_page  = self::SEARCH_PER_PAGE;

        $query          = $this->get_query();
        $language_names = $this->get_english_language_names();

        // Fetching one language's page (pagination click) vs. all languages (initial search).
        $languages = $this->get_target_language_codes();
        if ( $only_lang !== '' ) {
            $languages = in_array( $only_lang, $languages, true ) ? array( $only_lang ) : array();
        }

        $results = array();

        if ( $term !== '' && $query ) {
            $like = '%' . $wpdb->esc_like( mb_strtolower( $term, 'UTF-8' ) ) . '%';

            foreach ( $languages as $lang ) {
                $entries = array();
                $total   = 0;
                $table   = $query->get_table_name( $lang );

                if ( $this->dictionary_table_exists( $table ) ) {
                    $total = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM `{$table}` WHERE LOWER(original) LIKE %s AND translated IS NOT NULL AND translated <> ''", $like ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- table name built from known prefix + validated language codes.

                    $offset = ( $page - 1 ) * $per_page;
                    $rows   = $wpdb->get_results( $wpdb->prepare( "SELECT original, translated FROM `{$table}` WHERE LOWER(original) LIKE %s AND translated IS NOT NULL AND translated <> '' ORDER BY id ASC LIMIT %d OFFSET %d", $like, $per_page, $offset ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- table name built from known prefix + validated language codes.
                    foreach ( (array) $rows as $row ) {
                        $entries[] = array(
                            'original'   => $row->original,
                            'translated' => $row->translated,
                        );
                    }
                }

                $results[] = array(
                    'code'     => $lang,
                    'name'     => isset( $language_names[ $lang ] ) ? $language_names[ $lang ] : $lang,
                    'entries'  => $entries,
                    'total'    => $total,
                    'page'     => $page,
                    'per_page' => $per_page,
                );
            }
        }

        wp_send_json_success( array( 'results' => $results, 'per_page' => $per_page ) );
    }


/** Function get_translations() called by wp_ajax hooks: {'nopriv_trp_get_translations_regular', 'trp_get_translations_regular'} **/
/** Parameters found in function get_translations(): {"post": ["action", "language", "originals", "skip_machine_translation", "string_ids", "dynamic_strings"]} **/
function get_translations() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			check_ajax_referer( 'get_translations', 'security' );
			if ( isset( $_POST['action'] ) && $_POST['action'] === 'trp_get_translations_regular' && !empty( $_POST['language'] ) && in_array( $_POST['language'], $this->settings['translation-languages'] ) ) {
				$originals = (empty($_POST['originals']) )? array() : json_decode(stripslashes($_POST['originals'])); /* phpcs:ignore */ /* sanitized downstream */
				$skip_machine_translation = (empty($_POST['skip_machine_translation']) )? array() : json_decode(stripslashes($_POST['skip_machine_translation'])); /* phpcs:ignore */ /* sanitized downstream */
				$ids = (empty($_POST['string_ids']) )? array() : json_decode(stripslashes($_POST['string_ids'])); /* phpcs:ignore */ /* sanitized downstream */
				if ( is_array( $skip_machine_translation ) ) {
                    if ( is_array( $ids ) || is_array( $originals ) ) {
                        $trp = TRP_Translate_Press::get_trp_instance();
                        if ( !$this->trp_query ) {
                            $this->trp_query = $trp->get_component( 'query' );
                        }
                        if ( !$this->translation_manager ) {
                            $this->translation_manager = $trp->get_component( 'translation_manager' );
                        }
                        $block_type   = $this->trp_query->get_constant_block_type_regular_string();
                        $dictionaries = $this->get_translation_for_strings( $ids, $originals, $block_type, $skip_machine_translation );

                        $localized_text = $this->translation_manager->string_groups();
                        $string_group   = __( 'Others', 'translatepress-multilingual' ); // this type is not registered in the string types because it will be overwritten by the content in data-trp-node-type
                        if ( isset( $_POST['dynamic_strings'] ) && $_POST['dynamic_strings'] === 'true' ) {
                            $string_group = $localized_text['dynamicstrings'];
                        }
                        $dictionary_by_original = trp_sort_dictionary_by_original( $dictionaries, 'regular', $string_group, sanitize_text_field( $_POST['language'] ) );

                        echo trp_safe_json_encode( $dictionary_by_original );//phpcs:ignore
                    }
                }
			}
		}

		wp_die();
	}


/** Function trp_dismiss_gettext_notice() called by wp_ajax hooks: {'trp_dismiss_gettext_notice'} **/
/** No function found :-/ **/


/** Function edit_term() called by wp_ajax hooks: {'trp_glossary_edit_term'} **/
/** Parameters found in function edit_term(): {"post": ["original_term"]} **/
function edit_term() {
        $this->verify_request( self::EDIT_TERM_ACTION );

        $original_term = isset( $_POST['original_term'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['original_term'] ) ) ) : '';

        list( $default_term, $translations ) = $this->parse_submitted_term();

        $glossary = $this->get_glossary();

        if ( $original_term === '' || ! array_key_exists( $original_term, $glossary ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'The term you are trying to edit no longer exists. Please reload the page.', 'translatepress-multilingual' ) ) );
        }

        // Renaming to a key that already belongs to a different term is a duplicate.
        if ( $default_term !== $original_term && array_key_exists( $default_term, $glossary ) ) {
            wp_send_json_error( array( 'message' => sprintf(
                /* translators: %s is the glossary term. */
                esc_html__( 'The term “%s” already exists in the glossary.', 'translatepress-multilingual' ),
                $default_term
            ) ) );
        }

        // Rebuild preserving the original row position, swapping the key in place.
        $updated = array();
        foreach ( $glossary as $key => $value ) {
            if ( $key === $original_term ) {
                $updated[ $default_term ] = $translations;
            } else {
                $updated[ $key ] = $value;
            }
        }

        update_option( self::OPTION_NAME, $updated, false );

        wp_send_json_success( array(
            'original_term' => $original_term,
            'term'          => $default_term,
            'translations'  => $translations,
        ) );
    }


/** Function process_js_strings_in_translation_editor() called by wp_ajax hooks: {'trp_process_js_strings_in_translation_editor'} **/
/** No function found :-/ **/


/** Function delete_term() called by wp_ajax hooks: {'trp_glossary_delete_term'} **/
/** Parameters found in function delete_term(): {"post": ["term"]} **/
function delete_term() {
        $this->verify_request( self::DELETE_TERM_ACTION );

        $term = isset( $_POST['term'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['term'] ) ) ) : '';

        $glossary = $this->get_glossary();

        if ( $term === '' || ! array_key_exists( $term, $glossary ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'The term you are trying to delete no longer exists. Please reload the page.', 'translatepress-multilingual' ) ) );
        }

        unset( $glossary[ $term ] );
        update_option( self::OPTION_NAME, $glossary, false );

        wp_send_json_success( array( 'term' => $term ) );
    }


/** Function save_editor_user_meta() called by wp_ajax hooks: {'trp_save_editor_user_meta'} **/
/** Parameters found in function save_editor_user_meta(): {"post": ["action", "user_meta"]} **/
function save_editor_user_meta() {
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX && current_user_can( apply_filters( 'trp_translating_capability', 'manage_options' ) ) ) {
            check_ajax_referer( 'trp_editor_user_meta', 'security' );
            if ( isset( $_POST['action'] ) && $_POST['action'] === 'trp_save_editor_user_meta' && !empty( $_POST['user_meta'] ) ) {
                $submitted_user_meta = json_decode( stripslashes( $_POST['user_meta'] ), true ); /* phpcs:ignore */ /* sanitized bellow */
                $existing_user_meta = $this->get_editor_user_meta();
                foreach ( $existing_user_meta as $key => $existing ) {
                    if ( isset( $submitted_user_meta[ $key ] ) ) {
                        $existing_user_meta[ $key ] = (bool)$submitted_user_meta[ $key ];
                    }
                }
                update_user_meta( get_current_user_id(), 'trp_editor_user_meta', $existing_user_meta );
            }
        }
        echo trp_safe_json_encode( array() );//phpcs:ignore
        die();
    }


/** Function split_translation_block() called by wp_ajax hooks: {'trp_split_translation_block'} **/
/** Parameters found in function split_translation_block(): {"post": ["action", "strings"]} **/
function split_translation_block() {
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX && current_user_can( apply_filters( 'trp_translating_capability', 'manage_options' ) ) ) {
            check_ajax_referer( 'split_translation_block', 'security' );

			if ( isset( $_POST['action'] ) && $_POST['action'] === 'trp_split_translation_block' && ! empty( $_POST['strings'] ) ) {
                $raw_original_array = json_decode( stripslashes( $_POST['strings'] ) ); /* phpcs:ignore */ /* sanitized downstream */
				$trp = TRP_Translate_Press::get_trp_instance();
				if ( ! $this->trp_query ) {
					$this->trp_query = $trp->get_component( 'query' );
				}
				$deprecated_block_type = $this->trp_query->get_constant_block_type_deprecated();
				$originals = array();
				foreach( $raw_original_array as $original ){
					$originals[] = trp_sanitize_string( $original, false );
				}

				// even inactive languages ( not in $this->settings['translation-languages'] array ) will be updated
				$all_languages_table_names = $this->trp_query->get_all_table_names( $this->settings['default-language'], array() );
				$rows_affected = $this->trp_query->update_translation_blocks_by_original( $all_languages_table_names, $originals, $deprecated_block_type );
				if ( $rows_affected == 0 ){
					// do updates individually if it fails
					foreach ( $all_languages_table_names as $table_name ){
						$this->trp_query->update_translation_blocks_by_original( array( $table_name ), $originals, $deprecated_block_type );
					}
				}
			}
        }

        die();
	}


