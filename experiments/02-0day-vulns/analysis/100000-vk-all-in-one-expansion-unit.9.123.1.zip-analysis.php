<?php
/***
*
*Found actions: 1
*Found functions:1
*Extracted functions:1
*Total parameter names extracted: 1
*Overview: {'ajax_check_taxonomy_shared': {'check_taxonomy_shared'}}
*
***/

/** Function ajax_check_taxonomy_shared() called by wp_ajax hooks: {'check_taxonomy_shared'} **/
/** Parameters found in function ajax_check_taxonomy_shared(): {"post": ["nonce", "taxonomy_slug", "current_post_id"]} **/
function ajax_check_taxonomy_shared() {
			// nonce チェック.
			$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
			if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'check_taxonomy_shared' ) ) {
				wp_die( 'Security check failed' );
			}

			$taxonomy_slug   = isset( $_POST['taxonomy_slug'] ) ? sanitize_text_field( wp_unslash( $_POST['taxonomy_slug'] ) ) : '';
			$current_post_id = isset( $_POST['current_post_id'] ) ? absint( wp_unslash( $_POST['current_post_id'] ) ) : 0;
			if ( '' === $taxonomy_slug ) {
				wp_send_json_error( array( 'message' => 'Invalid taxonomy slug' ) );
			}

			$is_shared = self::get_taxonomy_shared_info( $taxonomy_slug, $current_post_id, 'check' );
			$message   = $is_shared ? self::get_taxonomy_shared_info( $taxonomy_slug, $current_post_id, 'message' ) : '';

			// 既存の設定を取得.
			$existing_settings = array();
			if ( $is_shared ) {
				// グローバル設定があればそれを優先（最も期待通りの値になりやすい）.
				$global_settings = self::get_global_taxonomy_settings( $taxonomy_slug );
				if ( is_array( $global_settings ) ) {
					$existing_settings = array(
						'label'    => isset( $global_settings['label'] ) ? (string) $global_settings['label'] : '',
						'tag'      => isset( $global_settings['tag'] ) ? (string) $global_settings['tag'] : '',
						'rest_api' => isset( $global_settings['rest_api'] ) ? (string) $global_settings['rest_api'] : '',
					);
				}

				// グローバル設定が無い場合は、実際に同じスラッグを持つ投稿タイプから設定を取得.
				$args = array(
					'post_type'              => 'post_type_manage',
					'posts_per_page'         => -1,
					'post_status'            => 'publish',
					'post__not_in'           => array( $current_post_id ),
					'fields'                 => 'ids',
					'no_found_rows'          => true,
					// Performance: this is a lightweight lookup for import settings.
					'update_post_term_cache' => false,
				);

				if ( empty( $existing_settings ) ) {
					$post_ids = get_posts( $args );
					foreach ( $post_ids as $post_id ) {
						$taxonomy_data = get_post_meta( $post_id, 'veu_taxonomy', true );
						if ( ! is_array( $taxonomy_data ) ) {
							continue;
						}

						foreach ( $taxonomy_data as $taxonomy ) {
							if ( ! isset( $taxonomy['slug'] ) || $taxonomy['slug'] !== $taxonomy_slug ) {
								continue;
							}

							$existing_settings = array(
								'label'    => isset( $taxonomy['label'] ) ? (string) $taxonomy['label'] : '',
								'tag'      => isset( $taxonomy['tag'] ) ? (string) $taxonomy['tag'] : '',
								'rest_api' => isset( $taxonomy['rest_api'] ) ? (string) $taxonomy['rest_api'] : '',
							);
							break 2;
						}
					}
				}
			}

			wp_send_json_success(
				array(
					'is_shared'         => $is_shared,
					'message'           => $message,
					'existing_settings' => $existing_settings,
				)
			);
		}


