<?php
/***
*
*Found actions: 1
*Found functions:1
*Extracted functions:1
*Total parameter names extracted: 1
*Overview: {'wpae_api': {'wpae_api'}}
*
***/

/** Function wpae_api() called by wp_ajax hooks: {'wpae_api'} **/
/** Parameters found in function wpae_api(): {"get": ["q"]} **/
function wpae_api() {

    if ( ! check_ajax_referer( 'wp_all_export_secure', 'security', false )){
        exit( json_encode(array('html' => __('Security check', 'wp-all-export'))) );
    }

    if ( ! current_user_can( \PMXE_Plugin::$capabilities ) ){
        exit( json_encode(array('html' => __('Security check', 'wp-all-export'))) );
    }

    $container = new \Wpae\Di\WpaeDi(array());

    $request = new \Wpae\Http\Request(file_get_contents('php://input'));

    $q = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';
    $routeParts = explode('/', $q);
    $controller = 'Wpae\\App\\Controller\\'.ucwords($routeParts[0]).'Controller';
    $action = ucwords($routeParts[1]).'Action';

    $controller = new $controller($container);
    $response = $controller->$action($request);

    if(!$response instanceof \Wpae\Http\Response) {
        throw new Exception('The controller must return an HttpResponse instance.');
    }

    $response->render();
}


