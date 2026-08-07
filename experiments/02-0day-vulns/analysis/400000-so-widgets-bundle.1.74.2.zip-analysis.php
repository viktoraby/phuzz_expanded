<?php
/***
*
*Found actions: 21
*Found functions:20
*Extracted functions:20
*Total parameter names extracted: 21
*Overview: {'siteorigin_widget_preview_widget_action': {'so_widgets_preview'}, 'ajax_render_widget_form': {'elementor_editor_get_wp_widget_form'}, 'block_migration_consent': {'so_widgets_block_migration_notice_consent'}, 'siteorigin_widget_remote_image_search': {'so_widgets_image_search'}, 'siteorigin_widget_action_search_terms': {'so_widgets_search_terms'}, 'admin_ajax_settings_save': {'so_widgets_setting_save'}, 'siteorigin_widget_image_import': {'so_widgets_image_import'}, 'manage_product': {'siteorigin_installer_manage'}, 'admin_ajax_manage_handler': {'so_widgets_bundle_manage'}, 'siteorigin_widget_get_posts_count_action': {'sow_get_posts_count'}, 'sow_carousel_get_next_posts_page': {'sow_carousel_load', 'nopriv_sow_carousel_load'}, 'siteorigin_widget_action_search_posts': {'so_widgets_search_posts'}, 'siteorigin_widgets_dismiss_widget_action': {'so_dismiss_widget_teaser'}, 'siteorigin_widget_get_icon_list': {'siteorigin_widgets_get_icons'}, 'installer_status_ajax': {'so_installer_status'}, 'admin_ajax_settings_form': {'so_widgets_setting_form'}, 'sowb_vc_widget_render_form': {'sowb_vc_widget_render_form'}, 'admin_ajax_get_javascript_variables': {'sow_get_javascript_variables'}, 'dismiss_notice': {'so_installer_dismiss'}, 'siteorigin_widgets_links_get_title': {'so_widgets_links_get_title'}}
*
***/

/** Function siteorigin_widget_preview_widget_action() called by wp_ajax hooks: {'so_widgets_preview'} **/
/** Parameters found in function siteorigin_widget_preview_widget_action(): {"post": ["class", "data"]} **/
function siteorigin_widget_preview_widget_action() {
	siteorigin_verify_request_permissions();

	if ( empty( $_POST['class'] ) ) {
		wp_die( __( 'Invalid widget.', 'so-widgets-bundle' ), 400 );
	}

	// Get the widget from the widget factory
	global $wp_widget_factory;
	$widget_class = str_replace( '\\\\', '\\', $_POST['class'] );

	$widget = ! empty( $wp_widget_factory->widgets[ $widget_class ] ) ? $wp_widget_factory->widgets[ $widget_class ] : false;

	if ( ! is_a( $widget, 'SiteOrigin_Widget' ) ) {
		wp_die( __( 'Invalid post.', 'so-widgets-bundle' ), 400 );
	}

	$instance = json_decode( stripslashes_deep( $_POST['data'] ), true );
	/* @var $widget SiteOrigin_Widget */
	$instance = $widget->update( $instance, $instance );
	$instance['is_preview'] = true;

	// The theme stylesheet will change how the button looks
	wp_enqueue_style( 'theme-css', get_stylesheet_uri(), array(), rand( 0, 65536 ) );
	wp_enqueue_style( 'so-widget-preview', siteorigin_widgets_url( 'base/css/preview.css' ), array(), rand( 0, 65536 ) );

	$sowb = SiteOrigin_Widgets_Bundle::single();
	$sowb->register_general_scripts();

	do_action( 'siteorigin_widgets_render_preview_' . $widget->id_base, $widget );

	ob_start();
	$widget->widget(
		array(
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		),
		$instance
	);
	$widget_html = ob_get_clean();

	// Print all the scripts and styles
	?>
	<html>
	<head>
		<title>
			<?php esc_html_e( 'Widget Preview', 'so-widgets-bundle' ); ?>
		</title>
		<?php
		wp_print_scripts();
		wp_print_styles();
		?>
	</head>
	<body>
		<?php // A lot of themes use entry-content as their main content wrapper. ?>
		<div class="entry-content">
			<?php echo $widget_html; ?>
		</div>
	</body>
	</html>

	<?php
	wp_die();
}


/** Function ajax_render_widget_form() called by wp_ajax hooks: {'elementor_editor_get_wp_widget_form'} **/
/** No params detected :-/ **/


/** Function block_migration_consent() called by wp_ajax hooks: {'so_widgets_block_migration_notice_consent'} **/
/** Parameters found in function block_migration_consent(): {"post": ["nonce"], "request": ["nonce"]} **/
function block_migration_consent() {
		if (
			! empty( $_POST['nonce'] ) &&
			! wp_verify_nonce( $_REQUEST['nonce'], 'so_block_migration_consent' )
		) {
			die();
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			die();
		}

		update_option(
			'sowb_block_migration',
			(int) get_current_user_id(),
			false
		);
	}


/** Function siteorigin_widget_remote_image_search() called by wp_ajax hooks: {'so_widgets_image_search'} **/
/** Parameters found in function siteorigin_widget_remote_image_search(): {"get": ["q", "page"]} **/
function siteorigin_widget_remote_image_search() {
	siteorigin_verify_request_permissions( 'upload_files', '_sononce', 'so-image' );

	if ( empty( $_GET['q'] ) ) {
		wp_die( __( 'Invalid request.', 'so-widgets-bundle' ), 400 );
	}

	// Send the query to stock search server
	$url = add_query_arg(
		array(
			'q' => $_GET[ 'q' ],
			'page' => ! empty( $_GET[ 'page' ] ) ? (int) $_GET[ 'page' ] : 1,
		),
		'http://stock.siteorigin.com/wp-admin/admin-ajax.php?action=image_search'
	);

	$result = wp_remote_get(
		$url,
		array(
			'timeout' => 20,
		)
	);

	if ( ! is_wp_error( $result ) ) {
		$result = json_decode( $result['body'], true );

		if ( ! empty( $result['items'] ) ) {
			foreach ( $result['items'] as & $r ) {
				if ( ! empty( $r['full_url'] ) ) {
					$r['import_signature'] = md5( $r['full_url'] . '::' . NONCE_SALT );
				}
			}
		}
		wp_send_json( $result );
	} else {
		$result = array(
			'error' => true,
			'message' => $result->get_error_message(),
		);
		wp_send_json_error( $result );
	}
}


/** Function siteorigin_widget_action_search_terms() called by wp_ajax hooks: {'so_widgets_search_terms'} **/
/** Parameters found in function siteorigin_widget_action_search_terms(): {"get": ["term"]} **/
function siteorigin_widget_action_search_terms() {
	siteorigin_verify_request_permissions();

	global $wpdb;
	$term = ! empty( $_GET['term'] ) ? sanitize_text_field( stripslashes( $_GET['term'] ) ) : '';
	$term = trim( $term, '%' );

	$query = $wpdb->prepare(
		"
		SELECT terms.term_id, terms.slug AS 'value', terms.name AS 'label', termtaxonomy.taxonomy AS 'type'
		FROM $wpdb->terms AS terms
		JOIN $wpdb->term_taxonomy AS termtaxonomy ON terms.term_id = termtaxonomy.term_id
		WHERE
			terms.name LIKE '%s'
		LIMIT 20
	",
		'%' . $wpdb->esc_like( $term ) . '%'
	);

	$results = array();

	$query_results = $wpdb->get_results( $query );
	if ( ! empty( $query_results ) ) {
		foreach ( $query_results as $result ) {
			if ( current_user_can(
				siteorigin_widget_get_taxonomy_capability( $result->type )
			) ) {
				$results[] = array(
					'value' => $result->type . ':' . $result->value,
					'label' => $result->label,
					'type' => $result->type,
				);
			}
		}
	}

	wp_send_json( apply_filters( 'siteorigin_widgets_search_terms_results', $results ) );
}


/** Function admin_ajax_settings_save() called by wp_ajax hooks: {'so_widgets_setting_save'} **/
/** Parameters found in function admin_ajax_settings_save(): {"get": ["_wpnonce", "id"]} **/
function admin_ajax_settings_save() {
		if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'save-widget-settings' ) ) {
			wp_die( __( 'Invalid request.', 'so-widgets-bundle' ), 403 );
		}

		if ( ! current_user_can( apply_filters( 'siteorigin_widgets_admin_menu_capability', 'manage_options' ) ) ) {
			wp_die( __( 'Insufficient permissions.', 'so-widgets-bundle' ), 403 );
		}

		$widget_objects = $this->get_widget_objects();
		$widget_path = empty( $_GET['id'] ) ?
			false :
			wp_normalize_path( WP_PLUGIN_DIR ) . sanitize_text_field( $_GET['id'] );

		$widget_object = empty( $widget_objects[ $widget_path ] ) ? false : $widget_objects[ $widget_path ];

		if ( empty( $widget_object ) || ! $widget_object->has_form( 'settings' ) ) {
			wp_die( __( 'Invalid request.', 'so-widgets-bundle' ), 400 );
		}

		$form_values = array_values( $_POST );
		$form_values = array_shift( $form_values );
		$widget_object->save_global_settings( stripslashes_deep( array_shift( $form_values ) ) );

		wp_send_json_success();
	}


/** Function siteorigin_widget_image_import() called by wp_ajax hooks: {'so_widgets_image_import'} **/
/** Parameters found in function siteorigin_widget_image_import(): {"get": ["import_signature", "full_url", "post_id"]} **/
function siteorigin_widget_image_import() {
	siteorigin_verify_request_permissions( 'upload_files', '_sononce', 'so-image' );

	if (
		empty( $_GET['import_signature'] ) ||
		empty( $_GET['full_url'] ) ||
		md5( $_GET['full_url'] . '::' . NONCE_SALT ) !== $_GET['import_signature']
	) {
		$result = array(
			'error' => true,
			'message' => __( 'Signature error', 'so-widgets-bundle' ),
		);
	} else {
		// Fetch the image
		$src = media_sideload_image( $_GET['full_url'], $_GET['post_id'], null, 'src' );

		if ( is_wp_error( $src ) ) {
			$result = array(
				'error' => true,
				'message' => $src->get_error_code(),
			);
		} else {
			global $wpdb;
			$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $src ) );

			if ( ! empty( $attachment ) ) {
				$thumb_src = wp_get_attachment_image_src( $attachment[0], 'thumbnail' );
				$result = array(
					'error' => false,
					'attachment_id' => $attachment[0],
					'thumb' => $thumb_src[0],
				);
			} else {
				$result = array(
					'error' => true,
					'message' => __( 'Attachment error', 'so-widgets-bundle' ),
				);
			}
		}
	}

	// Return the result
	wp_send_json( $result );
}


/** Function manage_product() called by wp_ajax hooks: {'siteorigin_installer_manage'} **/
/** Parameters found in function manage_product(): {"post": ["slug", "task", "type", "version"]} **/
function manage_product() {
			check_ajax_referer( 'siteorigin-installer-manage' );

			if (
				empty( $_POST['slug'] ) ||
				empty( $_POST['task'] ) ||
				empty( $_POST['type'] )
			) {
				die();
			}

			// SO Premium won't have a version.
			if (
				empty( $_POST['version'] ) &&
				$_POST['slug'] != 'siteorigin-premium'
			) {
				die();
			}

			$slug = sanitize_file_name( $_POST['slug'] );

			$product_url = 'https://wordpress.org/' . urlencode( $_POST['type'] ) . '/download/' . urlencode( $slug ) . '.' . urlencode( $_POST['version'] ) . '.zip';
			// check_ajax_referer( 'so_installer_manage' );
			if ( ! class_exists( 'WP_Upgrader' ) ) {
				require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			}
			$upgrader = new WP_Upgrader();
			if ( $_POST['type'] == 'plugins' ) {
				if ( $_POST['task'] == 'install' || $_POST['task'] == 'update' ) {
					$upgrader->run( array(
						'package' => esc_url( $product_url ),
						'destination' => WP_PLUGIN_DIR,
						'clear_destination' => true,
						'abort_if_destination_exists' => false,
						'hook_extra' => array(
							'type' => 'plugin',
							'action' => 'install',
						),
					) );

					$clear = true;
				} elseif (
					$_POST['task'] == 'activate' &&
					! is_wp_error( validate_plugin( $slug . '/' . $slug . '.php' ) )
				) {
					activate_plugin( $slug . '/' . $slug . '.php' );
					$clear = true;
				}
			} elseif ( $_POST['type'] == 'themes' ) {
				if ( $_POST['task'] == 'install' || $_POST['task'] == 'update' ) {
					$upgrader->run( array(
						'package' => esc_url( $product_url ),
						'destination' => get_theme_root(),
						'clear_destination' => true,
						'clear_working' => true,
						'abort_if_destination_exists' => false,
					) );
					$clear = true;
				} elseif ( $_POST['task'] == 'activate' ) {
					switch_theme( $slug );
					$clear = true;
				}
			}

			if ( ! empty( $clear ) ) {
				delete_transient( 'siteorigin_installer_product_data' );
			}
			die();
		}


/** Function admin_ajax_manage_handler() called by wp_ajax hooks: {'so_widgets_bundle_manage'} **/
/** Parameters found in function admin_ajax_manage_handler(): {"get": ["_wpnonce"], "post": ["widget", "active"]} **/
function admin_ajax_manage_handler() {
		if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'manage_so_widget' ) ) {
			wp_die( __( 'Invalid request.', 'so-widgets-bundle' ), 403 );
		}

		if ( ! current_user_can( apply_filters( 'siteorigin_widgets_admin_menu_capability', 'manage_options' ) ) ) {
			wp_die( __( 'Insufficient permissions.', 'so-widgets-bundle' ), 403 );
		}

		if ( empty( $_POST['widget'] ) ) {
			wp_die( __( 'Invalid post.', 'so-widgets-bundle' ), 400 );
		}

		if ( ! empty( $_POST['active'] ) ) {
			$this->activate_widget( $_POST['widget'] );
		} else {
			$this->deactivate_widget( $_POST['widget'] );
		}

		// Send a kind of dummy response.
		wp_send_json( array( 'done' => true ) );
	}


/** Function siteorigin_widget_get_posts_count_action() called by wp_ajax hooks: {'sow_get_posts_count'} **/
/** Parameters found in function siteorigin_widget_get_posts_count_action(): {"post": ["query"]} **/
function siteorigin_widget_get_posts_count_action() {
	siteorigin_verify_request_permissions();

	$query = stripslashes( $_POST['query'] );

	wp_send_json( array( 'posts_count' => siteorigin_widget_post_selector_count_posts( $query ) ) );
}


/** Function sow_carousel_get_next_posts_page() called by wp_ajax hooks: {'sow_carousel_load', 'nopriv_sow_carousel_load'} **/
/** Parameters found in function sow_carousel_get_next_posts_page(): {"request": ["_widgets_nonce"], "get": ["instance_hash", "paged"]} **/
function sow_carousel_get_next_posts_page() {
	if (
		empty( $_REQUEST['_widgets_nonce'] ) ||
		! wp_verify_nonce( $_REQUEST['_widgets_nonce'], 'widgets_action' ) ||
		empty( $_GET['instance_hash'] )
	) {
		die();
	}

	$instance_hash = $_GET['instance_hash'];
	global $wp_widget_factory;
	/** @var SiteOrigin_Widget $widget */
	$widget = ! empty( $wp_widget_factory->widgets['SiteOrigin_Widget_PostCarousel_Widget'] ) ?
	$wp_widget_factory->widgets['SiteOrigin_Widget_PostCarousel_Widget'] : null;
	if ( empty( $widget ) ) {
		die();
	}

	// Try to get the widget instance.
	$instance = $widget->get_stored_instance( $instance_hash );
	if ( empty( $instance ) ) {
		// Couldn't detect instance. Try to get it from the filter.
		$widget_instance = apply_filters( 'siteorigin_widgets_post_carousel_ajax_widget_instance', $instance_hash );

		if ( empty( $widget_instance ) || ! is_array( $widget_instance ) ) {
			die();
		}

		$instance = $widget_instance['instance'];
		$widget = $widget_instance['widget'];
	}

	// Let's set up the template variables, and try to load posts.
	$instance['paged'] = (int) $_GET['paged'];
	$template_vars = $widget->get_template_variables( $instance, array() );
	// If there aren't any settings, we can't output a template.
	if ( empty( $template_vars['settings'] ) ) {
		die();
	}

	$settings = $template_vars['settings'];
	$settings['posts'] = sow_carousel_handle_post_limit(
		$template_vars['settings']['posts'],
		$instance['paged']
	);

	// Don't output anything if there are no posts to return.
	if ( empty( $settings['posts'] ) ) {
		exit();
	}

	ob_start();
	include apply_filters( 'siteorigin_post_carousel_ajax_item_template', 'tpl/item.php', $instance );
	wp_send_json(
		array(
			'html' => ob_get_clean(),
		)
	);
}


/** Function siteorigin_widget_action_search_posts() called by wp_ajax hooks: {'so_widgets_search_posts'} **/
/** No params detected :-/ **/


/** Function siteorigin_widgets_dismiss_widget_action() called by wp_ajax hooks: {'so_dismiss_widget_teaser'} **/
/** Parameters found in function siteorigin_widgets_dismiss_widget_action(): {"get": ["widget"]} **/
function siteorigin_widgets_dismiss_widget_action() {
	siteorigin_verify_request_permissions( 'edit_posts', '_wpnonce', 'dismiss-widget-teaser' );

	if ( empty( $_GET[ 'widget' ] ) ) {
		wp_die( __( 'Invalid request.', 'so-widgets-bundle' ), 400 );
	}

	$dismissed = get_user_meta( get_current_user_id(), 'teasers_dismissed', true );

	if ( empty( $dismissed ) ) {
		$dismissed = array();
	}

	$dismissed[ $_GET[ 'widget' ] ] = true;

	update_user_meta( get_current_user_id(), 'teasers_dismissed', $dismissed );

	wp_die();
}


/** Function siteorigin_widget_get_icon_list() called by wp_ajax hooks: {'siteorigin_widgets_get_icons'} **/
/** Parameters found in function siteorigin_widget_get_icon_list(): {"get": ["family"]} **/
function siteorigin_widget_get_icon_list() {
	siteorigin_verify_request_permissions();

	if ( empty( $_GET['family'] ) ) {
		wp_die( __( 'Invalid request.', 'so-widgets-bundle' ), 400 );
	}

	$widget_icon_families = apply_filters( 'siteorigin_widgets_icon_families', array() );
	$icons = ! empty( $widget_icon_families[ $_GET['family'] ] ) ? $widget_icon_families[ $_GET['family'] ] : array();
	wp_send_json( $icons );
}


/** Function installer_status_ajax() called by wp_ajax hooks: {'so_installer_status'} **/
/** Parameters found in function installer_status_ajax(): {"post": ["status"]} **/
function installer_status_ajax () {
			check_ajax_referer( 'siteorigin_installer_status', 'nonce' );
			update_option( 'siteorigin_installer', rest_sanitize_boolean( $_POST['status'] ) );
			die();
		}


/** Function admin_ajax_settings_form() called by wp_ajax hooks: {'so_widgets_setting_form'} **/
/** Parameters found in function admin_ajax_settings_form(): {"get": ["_wpnonce", "id"]} **/
function admin_ajax_settings_form() {
		if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'display-widget-form' ) ) {
			wp_die( __( 'Invalid request.', 'so-widgets-bundle' ), 403 );
		}

		if ( ! current_user_can( apply_filters( 'siteorigin_widgets_admin_menu_capability', 'manage_options' ) ) ) {
			wp_die( __( 'Insufficient permissions.', 'so-widgets-bundle' ), 403 );
		}

		$widget_objects = $this->get_widget_objects();

		$widget_path = empty( $_GET['id'] ) ?
			false :
			wp_normalize_path( WP_PLUGIN_DIR ) . sanitize_text_field( $_GET['id'] );

		$widget_object = empty( $widget_objects[ $widget_path ] ) ? false : $widget_objects[ $widget_path ];

		if ( empty( $widget_object ) || ! $widget_object->has_form( 'settings' ) ) {
			wp_die( __( 'Invalid request.', 'so-widgets-bundle' ), 400 );
		}

		unset( $widget_object->widget_options['has_preview'] );

		$action_url = admin_url( 'admin-ajax.php' );
		$action_url = add_query_arg( array(
			'id' => $_GET['id'],
			'action' => 'so_widgets_setting_save',
		), $action_url );
		$action_url = wp_nonce_url( $action_url, 'save-widget-settings' );

		$value = $widget_object->get_global_settings();

		?>
		<form method="post" action="<?php echo esc_url( $action_url ); ?>" target="so-widget-settings-save">
			<?php $widget_object->form( $value, 'settings' ); ?>
		</form>
		<?php

		wp_die();
	}


/** Function sowb_vc_widget_render_form() called by wp_ajax hooks: {'sowb_vc_widget_render_form'} **/
/** Parameters found in function sowb_vc_widget_render_form(): {"request": ["widget"]} **/
function sowb_vc_widget_render_form() {
		if ( empty( $_REQUEST['widget'] ) ) {
			wp_die();
		}

		siteorigin_verify_request_permissions( 'edit_posts', '_sowbnonce', 'sowb_vc_widget_render_form' );

		$request = array_map( 'stripslashes_deep', $_REQUEST );
		$widget_class = $request['widget'];

		global $wp_widget_factory;

		$widget = ! empty( $wp_widget_factory->widgets[ $widget_class ] ) ? $wp_widget_factory->widgets[ $widget_class ] : false;

		if ( ! empty( $widget ) && is_object( $widget ) && is_subclass_of( $widget, 'SiteOrigin_Widget' ) ) {
			/* @var $widget SiteOrigin_Widget */
			$widget->form( array() );
		}

		wp_die();
	}


/** Function admin_ajax_get_javascript_variables() called by wp_ajax hooks: {'sow_get_javascript_variables'} **/
/** Parameters found in function admin_ajax_get_javascript_variables(): {"post": ["widget"]} **/
function admin_ajax_get_javascript_variables() {
		siteorigin_verify_request_permissions();

		$widget_class = $_POST['widget'];
		global $wp_widget_factory;

		if ( empty( $wp_widget_factory->widgets[ $widget_class ] ) ) {
			wp_die( __( 'Invalid post.', 'so-widgets-bundle' ), 400 );
		}

		$widget = $wp_widget_factory->widgets[ $widget_class ];

		if ( ! method_exists( $widget, 'get_javascript_variables' ) ) {
			wp_die( __( 'Invalid request.', 'so-widgets-bundle' ), 400 );
		}

		$result = $widget->get_javascript_variables();

		wp_send_json( $result );
	}


/** Function dismiss_notice() called by wp_ajax hooks: {'so_installer_dismiss'} **/
/** No params detected :-/ **/


/** Function siteorigin_widgets_links_get_title() called by wp_ajax hooks: {'so_widgets_links_get_title'} **/
/** Parameters found in function siteorigin_widgets_links_get_title(): {"request": ["_widgets_nonce"], "get": ["postId"]} **/
function siteorigin_widgets_links_get_title() {
	if (
		empty( $_REQUEST['_widgets_nonce'] ) ||
		! wp_verify_nonce( $_REQUEST['_widgets_nonce'], 'widgets_action' )
	) {
		wp_die( __( 'Invalid request.', 'so-widgets-bundle' ), 403 );
	}

	if (
		empty( $_GET['postId'] ) ||
		! is_numeric( $_GET['postId'] )
	) {
		wp_die( __( 'Invalid request.', 'so-widgets-bundle' ), 400 );
	}

	// Don't allow users to link to posts they can't view.
	if ( ! current_user_can( 'read_post', $_GET['postId'] ) ) {
		wp_die( __( 'Invalid request.', 'so-widgets-bundle' ), 403 );
	}

	$postTitle = get_the_title( $_GET['postId'] );
	echo ! empty( $postTitle ) ? esc_attr( $postTitle ) : esc_html__( '(No Title)', 'so-widgets-bundle' );
	die();
}


