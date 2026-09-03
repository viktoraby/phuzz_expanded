<?php
/***
*
*Found actions: 5
*Found functions:5
*Extracted functions:5
*Total parameter names extracted: 6
*Overview: {'on_file_edit_ajax': {'edit-theme-plugin-file'}, 'on_admin_action_editpost_save_prev_post': {'inline-save'}, 'onDestroyUserSession': {'destroy-sessions'}, 'handle_auto_update_change': {'toggle-auto-updates'}, 'ajax_GetGitHubPluginInfo': {'SimplePluginLogger_GetGitHubPluginInfo'}}
*
***/

/** Function on_file_edit_ajax() called by wp_ajax hooks: {'edit-theme-plugin-file'} **/
/** Parameters found in function on_file_edit_ajax(): {"post": ["file", "newcontent", "plugin", "theme"]} **/
function on_file_edit_ajax() {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$file = isset( $_POST['file'] ) ? wp_unslash( $_POST['file'] ) : '';

		// Validate file path (same check WordPress core uses).
		if ( ! $file || validate_file( $file ) !== 0 ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$new_contents = isset( $_POST['newcontent'] ) ? wp_unslash( $_POST['newcontent'] ) : '';

		// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$plugin = isset( $_POST['plugin'] ) ? wp_unslash( $_POST['plugin'] ) : '';
		// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$theme = isset( $_POST['theme'] ) ? wp_unslash( $_POST['theme'] ) : '';

		// Gate on the same capabilities WP core's handler enforces, so this
		// priority-0 hook can't be used by lower-privileged users to trigger
		// file reads before core's own check runs.
		if ( $plugin && current_user_can( 'edit_plugins' ) && validate_file( $plugin ) === 0 ) {
			$this->capture_plugin_file_edit( $file, $plugin, $new_contents );
		} elseif ( $theme && current_user_can( 'edit_themes' ) && validate_file( $theme ) === 0 ) {
			$this->capture_theme_file_edit( $file, $theme, $new_contents );
		}
	}


/** Function on_admin_action_editpost_save_prev_post() called by wp_ajax hooks: {'inline-save'} **/
/** Parameters found in function on_admin_action_editpost_save_prev_post(): {"post": ["post_ID"]} **/
function on_admin_action_editpost_save_prev_post() {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$post_ID = isset( $_POST['post_ID'] ) ? (int) $_POST['post_ID'] : 0;

		if ( $post_ID === 0 ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_ID ) ) {
			return;
		}

		$this->save_prev_post_data( $post_ID );
	}


/** Function onDestroyUserSession() called by wp_ajax hooks: {'destroy-sessions'} **/
/** Parameters found in function onDestroyUserSession(): {"post": ["user_id", "nonce"]} **/
function onDestroyUserSession() {
		// PHPCS:ignore WordPress.Security.NonceVerification.Missing,WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		$user = get_userdata( (int) $_POST['user_id'] );

		if ( $user ) {
			if ( ! current_user_can( 'edit_user', $user->ID ) ) {
				$user = false;
			} elseif ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'update-user_' . $user->ID ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
				$user = false;
			}
		}

		if ( ! $user ) {
			// Could not log out user sessions. Please try again.
			return;
		}

		$context = array();

		if ( $user->ID === get_current_user_id() ) {
			$this->info_message( 'user_session_destroy_others' );
		} else {
			$context['user_id']           = $user->ID;
			$context['user_login']        = $user->user_login;
			$context['user_display_name'] = $user->display_name;

			$this->info_message( 'user_session_destroy_everywhere', $context );
		}
	}


/** Function handle_auto_update_change() called by wp_ajax hooks: {'toggle-auto-updates'} **/
/** Parameters found in function handle_auto_update_change(): {"get": ["action", "plugin"], "post": ["action", "type", "state", "asset", "checked"]} **/
function handle_auto_update_change() {
		$option = 'auto_update_plugins';

		add_action(
			"update_option_{$option}",
			// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
			function ( $old_value, $value, $option ) {
				/**
				 * Option contains array with plugin that are set to be auto updated.
				 * Example:
				 * Array
				 *   (
				 *       [1] => query-monitor/query-monitor.php
				 *       [2] => akismet/akismet.php
				 *       [3] => wp-crontrol/wp-crontrol.php
				 *       [4] => redirection/redirection.php
				 *   )
				 *
				 * $_GET when opening single item enable/disable auto update link in plugin list in new window
				 *   Array
				 *   (
				 *       [action] => disable-auto-update | enable-auto-update
				 *       [plugin] => akismet/akismet.php
				 *   )
				 *
				 *
				 * $_POST from ajax call when clicking single item enable/disable link in plugin list
				 *    [action] => toggle-auto-updates
				 *    [state] => disable | enable
				 *    [type] => plugin
				 *    [asset] => redirection/redirection.php
				 *
				 *
				 * $_POST when selecting multiple plugins and choosing Enable auto updates or Disable auto updates
				 *     [action] => enable-auto-update-selected | disable-auto-update-selected
				 *     [checked] => Array
				 *         (
				 *             [0] => query-monitor/query-monitor.php
				 *             [1] => redirection/redirection.php
				 *         )
				 */

				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$action = sanitize_text_field( wp_unslash( $_GET['action'] ?? '' ) );
				if ( ! $action ) {
					// phpcs:ignore WordPress.Security.NonceVerification.Missing
					$action = sanitize_text_field( wp_unslash( $_POST['action'] ?? '' ) );
				}

				// Bail if doing ajax and
				// - action is not toggle-auto-updates.
				// - type is not plugin.
				if ( wp_doing_ajax() ) {
					if ( $action !== 'toggle-auto-updates' ) {
						return;
					}

					// phpcs:ignore WordPress.Security.NonceVerification.Missing
					$type = sanitize_text_field( wp_unslash( $_POST['type'] ?? '' ) );
					if ( $type !== 'plugin' ) {
						return;
					}
				}

				// Bail if screen and not plugin screen.
				$current_screen = get_current_screen();
				if ( is_a( $current_screen, 'WP_Screen' ) && ( $current_screen->base !== 'plugins' ) ) {
					return;
				}

				// Enable or disable, string "enable" or "disable".
				$enableOrDisable = null;

				// Plugin slugs that actions are performed against.
				$plugins = array();

				if ( in_array( $action, array( 'enable-auto-update', 'disable-auto-update' ), true ) ) {
					// Opening single item enable/disable auto update link in plugin list in new window.
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					$plugin = sanitize_text_field( wp_unslash( $_GET['plugin'] ?? '' ) );

					if ( $plugin ) {
						$plugins[] = sanitize_text_field( urldecode( $plugin ) );
					}

					if ( $action === 'enable-auto-update' ) {
						$enableOrDisable = 'enable';
					} elseif ( $action === 'disable-auto-update' ) {
						$enableOrDisable = 'disable';
					}
				} elseif ( $action === 'toggle-auto-updates' ) {
					// Ajax post call when clicking single item enable/disable link in plugin list.
					// *    [action] => toggle-auto-updates
					// *    [state] => disable | enable
					// *    [type] => plugin
					// *    [asset] => redirection/redirection.php.
					// phpcs:ignore WordPress.Security.NonceVerification.Missing
					$state = sanitize_text_field( wp_unslash( $_POST['state'] ?? '' ) );
					// phpcs:ignore WordPress.Security.NonceVerification.Missing
					$asset = sanitize_text_field( wp_unslash( $_POST['asset'] ?? '' ) );

					if ( $state === 'enable' ) {
						$enableOrDisable = 'enable';
					} elseif ( $state === 'disable' ) {
						$enableOrDisable = 'disable';
					}

					if ( $asset ) {
						$plugins[] = sanitize_text_field( urldecode( $asset ) );
					}
				} elseif ( in_array( $action, array( 'enable-auto-update-selected', 'disable-auto-update-selected' ), true ) ) {
					// $_POST when checking multiple plugins and choosing Enable auto updates or Disable auto updates.
					// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
					$checked = wp_unslash( $_POST['checked'] ?? null );
					if ( $checked ) {
						$plugins = (array) $checked;
					}

					if ( $action === 'enable-auto-update-selected' ) {
						$enableOrDisable = 'enable';
					} elseif ( $action === 'disable-auto-update-selected' ) {
						$enableOrDisable = 'disable';
					}
				}

				// Now we have:
				// - an array of plugin slugs in $plugins.
				// - if plugin auto updates is to be enabled or disabled din $enableOrDisable.

				// Bail if required values not set.
				if ( ! $plugins || ! $enableOrDisable ) {
					return;
				}

				// Finally log each plugin.
				foreach ( $plugins as $onePluginSlug ) {
					$this->logPluginAutoUpdateEnableOrDisable( $onePluginSlug, $enableOrDisable );
				}
			},
			10,
			3
		);
	}


/** Function ajax_GetGitHubPluginInfo() called by wp_ajax hooks: {'SimplePluginLogger_GetGitHubPluginInfo'} **/
/** Parameters found in function ajax_GetGitHubPluginInfo(): {"get": ["repo"]} **/
function ajax_GetGitHubPluginInfo() {

		check_admin_referer( 'simple-history-github-plugin-info' );

		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_die( esc_html__( "You don't have access to this page.", 'simple-history' ) );
		}

		$repo = isset( $_GET['repo'] ) ? (string) sanitize_text_field( wp_unslash( $_GET['repo'] ) ) : '';

		if ( $repo === '' ) {
			wp_die( esc_html__( 'Could not find GitHub repository.', 'simple-history' ) );
		}

		$repo_parts = explode( '/', rtrim( $repo, '/' ) );
		if ( count( $repo_parts ) !== 5 ) {
			wp_die( esc_html__( 'Could not find GitHub repository.', 'simple-history' ) );
		}

		$repo_username = $repo_parts[3];
		$repo_repo     = $repo_parts[4];

		// https://developer.github.com/v3/repos/contents/.
		// https://api.github.com/repos/<username>/<repo>/readme.
		$api_url = sprintf(
			'https://api.github.com/repos/%1$s/%2$s/readme',
			rawurlencode( $repo_username ),
			rawurlencode( $repo_repo ) 
		);

		// Get file. Use accept-header to get file as HTML instead of JSON.
		// phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.wp_remote_get_wp_remote_get
		$response = wp_remote_get(
			$api_url,
			array(
				'headers' => array(
					'accept' => 'application/vnd.github.VERSION.html',
				),
			)
		);

		$response_body = wp_remote_retrieve_body( $response );

		$repo_info = '<p>' . sprintf(
			// translators: %1$s is a link to the repo, %2$s is the repo name.
			__( 'Viewing <code>readme</code> from repository <code><a target="_blank" href="%1$s">%2$s</a></code>.', 'simple-history' ),
			esc_url( $repo ),
			esc_html( $repo )
		) . '</p>';

		$github_markdown_css_path = SIMPLE_HISTORY_PATH . '/css/github-markdown.css';

		$escaped_response_body = wp_kses(
			$response_body,
			array(
				'p'    => array(),
				'div'  => array(),
				'h1'   => array(),
				'h2'   => array(),
				'h3'   => array(),
				'code' => array(),
				'a'    => array(
					'href' => array(),
				),
				'img'  => array(
					'src' => array(),
				),
				'ul'   => array(),
				'li'   => array(),
			)
		);

		printf(
			'
				<!doctype html>
				<style>
					body {
						font-family: sans-serif;
						font-size: 16px;
					}
					.repo-info {
						padding: 1.25em 1em;
						background: #fafafa;
						line-height: 1;
					}
					.repo-info p {
						margin: 0;
					}
					    .markdown-body {
				        min-width: 200px;
				        max-width: 790px;
				        margin: 0 auto;
				        padding: 30px;
				    }

					@import url("%3$s");

				</style>

				<base href="%4$s/raw/master/">
				<base target="_blank">

				<header class="repo-info">
					%1$s
				</header>

				<div class="markdown-body readme-contents">
					%2$s
				</div>
			',
			$repo_info, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$escaped_response_body, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_url( $github_markdown_css_path ), // 3
			esc_url( $repo ) // 4
		);

		exit;
	}


