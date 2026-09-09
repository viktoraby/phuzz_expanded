<?php
/***
*
*Found actions: 2
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 3
*Overview: {'irp_get_list_posts': {'irp_list_posts'}, 'irp_do_action': {'do_action'}}
*
***/

/** Function irp_get_list_posts() called by wp_ajax hooks: {'irp_list_posts'} **/
/** Parameters found in function irp_get_list_posts(): {"request": ["irp_post_type"], "get": ["q"]} **/
function irp_get_list_posts()
{
    // Authorization: this admin-ajax handler was callable by ANY logged-in user
    // (incl. subscribers) with no nonce. Require a valid nonce (CSRF) and the
    // requested type's edit capability before running any query.
    check_ajax_referer( 'irp_list_posts', 'nonce' );

    $postType = '';
    if ( isset($_REQUEST['irp_post_type']) ) {
        // could be an input of 'post, page, etc.'
        $postType = sanitize_text_field( $_REQUEST['irp_post_type'] );
    }

    // Fix this for custom post types
    $allowedPostTypes = array('post', 'page');

    $postType = array_filter(array_map('trim', explode(',', $postType)));

    if ( ! irp_user_can_list_post_types( $postType ) ) {
        wp_send_json( array( 'items' => array() ) );
    }

    if ( isset($_GET['q']) ) {
        $search = trim( sanitize_text_field( wp_unslash( $_GET['q'] ) ) );
        if ( strlen($search) > 0 ) {
            global $wpdb;
            // Build the LIKE fragment with esc_like() (for %/_ wildcards) and
            // $wpdb->prepare() rather than concatenating request data into SQL.
            $like = '%' . $wpdb->esc_like( $search ) . '%';
            add_filter('posts_where', function( $where ) use ( $wpdb, $like ) {
                return $where . $wpdb->prepare( ' AND post_title LIKE %s', $like );
            });
        }
    }

    $result = array();

    if (!empty($postType)) {
        if (empty(array_diff($postType, $allowedPostTypes))) {
            $query = array(
                'posts_per_page' => 100,
                'post_status' => 'publish',
                'post_type' => $postType,
                'order' => 'DESC',
                'orderby' => 'date',
                'suppress_filters' => false,
                'has_password' => false,
            );

            $posts = get_posts( $query );

            foreach ($posts as $this_post) {
                $post_title = $this_post->post_title;
                $id = $this_post->ID;

                $result[] = array(
                    'text' => $post_title,
                    'id' => $id,
                );
            }
        }
    }

    $posts['items'] = $result;
    echo wp_json_encode($posts);

    die();
}


/** Function irp_do_action() called by wp_ajax hooks: {'do_action'} **/
/** Parameters found in function irp_do_action(): {"request": ["nonce"]} **/
function irp_do_action() {
    global $irp;

    load_plugin_textdomain(IRP_PLUGIN_SLUG, false, dirname( plugin_basename(__FILE__ ) ) . '/../languages');

    $action = $irp->Utils->qs('irp_action');
    $irp->Log->info('[actions::irp_do_action] Action: %s', $action);

    $nonce = '';
    if ( isset ($_REQUEST['nonce']) )
    {
        $nonce = sanitize_key( $_REQUEST['nonce'] );
    }

    switch($action) {
        case 'ui_button_editor':
            if ( empty($nonce) || ! wp_verify_nonce( $nonce, 'irp_do_action' ) ) {
                exit;
            }
            call_irp_ui_button_editor($irp);
            break;
        case 'ui_box_preview':
            if ( empty($nonce) || ! wp_verify_nonce( $nonce, 'irp_do_action' ) ) {
                exit;
            }
            call_irp_ui_box_preview($irp);
            break;
        case 'manager_trackingOn':
            if ( empty($nonce) || ! wp_verify_nonce( $nonce, 'manager_tracking' ) ) {
                exit;
            }
            call_irp_manager_trackingOn($irp);
            break;
        case 'manager_trackingOff':
            if ( empty($nonce) || ! wp_verify_nonce( $nonce, 'manager_tracking' ) ) {
                exit;
            }
            call_irp_manager_trackingOff($irp);
            break;
        case '':
            break; // blank strings are okay. We just want to ignore them.
        default:
            // Unknown actions are a silent no-op. This runs on 'init' for every
            // request, including anonymous front-end hits, so it must never end
            // the request: fatal() dies with the (reflected) log line, which
            // turned any ?irp_action=<anything> URL into a blank page.
            $irp->Log->error('Ignoring request to execute unknown function %s', $action);
            break;
    }
}


