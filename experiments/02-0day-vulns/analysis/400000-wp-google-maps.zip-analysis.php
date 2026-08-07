<?php
/***
*
*Found actions: 26
*Found functions:13
*Extracted functions:4
*Total parameter names extracted: 4
*Overview: {'WPGMZA\\\\SettingsPage': {'wpgmza_maps_settings_danger_zone_delete_data'}, 'WPGMZA\\\\Page': {'wpgmza_hide_chat'}, 'onAJAXRequest': {'wpgmza_rest_api_request', 'nopriv_wpgmza_rest_api_request'}, 'onReportRestAPIBlocked': {'wpgmza_report_rest_api_blocked', 'nopriv_wpgmza_report_rest_api_blocked'}, 'WPGMZA\\\\store_nominatim_cache': {'nopriv_wpgmza_store_nominatim_cache', 'wpgmza_store_nominatim_cache'}, 'WPGMZA\\\\query_nominatim_cache': {'wpgmza_query_nominatim_cache', 'nopriv_wpgmza_query_nominatim_cache'}, 'WPGMZA\\\\MapsEngineDialog': {'wpgmza_maps_engine_dialog_set_engine'}, 'WPGMZA\\\\MapEditorTour': {'wpgmza_tour_progress_update'}, 'wpgmaps_action_callback_pro': {'delete_rectangle', 'delete_poly', 'delete_marker', 'edit_marker', 'delete_circle', 'delete_dataset', 'approve_marker', 'delete_polyline', 'add_marker'}, 'WPGMZA\\\\InstallerPage': {'wpgmza_installer_page_save_options', 'wpgmza_installer_page_skip'}, 'WPGMZA\\\\clear_nominatim_cache': {'wpgmza_clear_nominatim_cache'}, 'dismissFromPostAjax': {'wpgmza_dismiss_persistent_notice'}, 'processBackgroundAction': {'wpgmza_persisten_notice_quick_action'}}
*
***/

/** Function WPGMZA\\SettingsPage() called by wp_ajax hooks: {'wpgmza_maps_settings_danger_zone_delete_data'} **/
/** No function found :-/ **/


/** Function WPGMZA\\Page() called by wp_ajax hooks: {'wpgmza_hide_chat'} **/
/** No function found :-/ **/


/** Function onAJAXRequest() called by wp_ajax hooks: {'wpgmza_rest_api_request', 'nopriv_wpgmza_rest_api_request'} **/
/** Parameters found in function onAJAXRequest(): {"request": ["route"], "server": ["REQUEST_URI"]} **/
function onAJAXRequest()
	{
		$this->onRestAPIInit();
		
		// Check route is specified
		if(empty($_REQUEST['route']))
		{
			$this->sendAJAXResponse(array(
				'code'			=> 'rest_no_route',
				'message'		=> 'No route was found matching the URL request method',
				'data'			=> array(
					'status'	=> 404
				)
			), 404);
			return;
		}
		
		// Try to match the route
		$args = null;
		
		foreach($this->fallbackRoutesByRegex as $regex => $value)
		{
			if(preg_match($regex, $_REQUEST['route']))
			{
				$args = $value;
				break;
			}
		}
		
		if(!$args)
		{
			$this->sendAJAXResponse(array(
				'code'			=> 'rest_no_route',
				'message'		=> 'No route was found matching the URL request method',
				'data'			=> array(
					'status'	=> 404
				)
			), 404);
			exit;
		}
		
		// Check permissions
		if(!empty($args['permission_callback']))
		{
			$allowed = $args['permission_callback']();

			if(!$allowed)
			{
				$this->sendAJAXResponse(array(
					'code'			=> 'rest_forbidden',
					'message'		=> 'You are not authorized to use this method',
					'data'			=> array(
						'status'	=> 403
					)
				), 403);
				exit;
			}
		}
		
		// Temporary fallback for the /features/ endpoint as this will not function as expected when moving to ajax
		// This helps with some nonce cache issues we see 
		if(!empty($_REQUEST['route']) && $_REQUEST['route'] === '/features/'){
			$_SERVER['REQUEST_URI'] = "wpgmza/v1/features/";
		}

		// Fire callback
		$result = $args['callback'](null);
		$this->sendAJAXResponse($result);
		
		exit;
	}


/** Function onReportRestAPIBlocked() called by wp_ajax hooks: {'wpgmza_report_rest_api_blocked', 'nopriv_wpgmza_report_rest_api_blocked'} **/
/** No params detected :-/ **/


/** Function WPGMZA\\store_nominatim_cache() called by wp_ajax hooks: {'nopriv_wpgmza_store_nominatim_cache', 'wpgmza_store_nominatim_cache'} **/
/** No function found :-/ **/


/** Function WPGMZA\\query_nominatim_cache() called by wp_ajax hooks: {'wpgmza_query_nominatim_cache', 'nopriv_wpgmza_query_nominatim_cache'} **/
/** No function found :-/ **/


/** Function WPGMZA\\MapsEngineDialog() called by wp_ajax hooks: {'wpgmza_maps_engine_dialog_set_engine'} **/
/** No function found :-/ **/


/** Function WPGMZA\\MapEditorTour() called by wp_ajax hooks: {'wpgmza_tour_progress_update'} **/
/** No function found :-/ **/


/** Function wpgmaps_action_callback_pro() called by wp_ajax hooks: {'delete_rectangle', 'delete_poly', 'delete_marker', 'edit_marker', 'delete_circle', 'delete_dataset', 'approve_marker', 'delete_polyline', 'add_marker'} **/
/** No function found :-/ **/


/** Function WPGMZA\\InstallerPage() called by wp_ajax hooks: {'wpgmza_installer_page_save_options', 'wpgmza_installer_page_skip'} **/
/** No function found :-/ **/


/** Function WPGMZA\\clear_nominatim_cache() called by wp_ajax hooks: {'wpgmza_clear_nominatim_cache'} **/
/** No function found :-/ **/


/** Function dismissFromPostAjax() called by wp_ajax hooks: {'wpgmza_dismiss_persistent_notice'} **/
/** Parameters found in function dismissFromPostAjax(): {"post": ["slug", "wpgmza_security"]} **/
function dismissFromPostAjax(){
		if (empty($_POST['slug']) || empty($_POST['wpgmza_security']) || !wp_verify_nonce($_POST['wpgmza_security'], 'wpgmza_ajaxnonce')) {
			wp_send_json_error(__( 'Security check failed, import will continue, however, we cannot provide you with live updates', 'wp-google-maps' ));
		}

		$slug = sanitize_text_field($_POST['slug']);
		if (!empty($slug)){
			$this->dismiss($slug);
			wp_send_json_success('Complete');
		}

		wp_send_json_error('Could not complete');
	}


/** Function processBackgroundAction() called by wp_ajax hooks: {'wpgmza_persisten_notice_quick_action'} **/
/** Parameters found in function processBackgroundAction(): {"post": ["relay", "wpgmza_security"]} **/
function processBackgroundAction(){
		if (empty($_POST['relay']) || empty($_POST['wpgmza_security']) || !wp_verify_nonce($_POST['wpgmza_security'], 'wpgmza_ajaxnonce')) {
			wp_send_json_error(__( 'Security check failed, import will continue, however, we cannot provide you with live updates', 'wp-google-maps' ));
		}

		$relayAction = sanitize_text_field($_POST['relay']);
		if(!empty($relayAction)){
			switch($relayAction){
				case 'swap_internal_engine':
					global $wpgmza;
					$engine = $wpgmza->settings->internal_engine;
					if($engine === 'atlas-novus'){
						$engine = 'legacy';
					} else {
						$engine = 'atlas-novus';
					}

					$wpgmza->settings->internal_engine = $engine;
					break;
			}
		}

	    /* Developer Hook (Action) - Add processing for non standard background actions present in persistent notifications */     
		do_action("wpgmza_admin_notice_process_background_action", $relayAction);

		wp_send_json_success('Complete');
	}


