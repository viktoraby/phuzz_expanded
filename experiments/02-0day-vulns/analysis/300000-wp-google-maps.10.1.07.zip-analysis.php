<?php
/***
*
*Found actions: 24
*Found functions:11
*Extracted functions:4
*Total parameter names extracted: 5
*Overview: {'WPGMZA\\\\Page': {'wpgmza_hide_chat'}, 'processBackgroundAction': {'wpgmza_persisten_notice_quick_action'}, 'onAJAXRequest': {'nopriv_wpgmza_rest_api_request', 'wpgmza_rest_api_request'}, 'dismissFromPostAjax': {'wpgmza_dismiss_persistent_notice'}, 'WPGMZA\\\\clear_nominatim_cache': {'wpgmza_clear_nominatim_cache'}, 'WPGMZA\\\\InstallerPage': {'wpgmza_installer_page_auto_onboarding_procedure', 'wpgmza_installer_page_skip', 'wpgmza_installer_page_save_options', 'wpgmza_installer_page_temp_api_key'}, 'WPGMZA\\\\SettingsPage': {'wpgmza_maps_settings_danger_zone_delete_data'}, 'wpgmaps_action_callback_pro': {'add_marker', 'delete_marker', 'delete_poly', 'delete_dataset', 'approve_marker', 'delete_polyline', 'delete_rectangle', 'edit_marker', 'delete_circle'}, 'onReportRestAPIBlocked': {'wpgmza_report_rest_api_blocked', 'nopriv_wpgmza_report_rest_api_blocked'}, 'WPGMZA\\\\MapEditorTour': {'wpgmza_tour_progress_update'}, 'WPGMZA\\\\MapsEngineDialog': {'wpgmza_maps_engine_dialog_set_engine'}}
*
***/

/** Function WPGMZA\\Page() called by wp_ajax hooks: {'wpgmza_hide_chat'} **/
/** No function found :-/ **/


/** Function processBackgroundAction() called by wp_ajax hooks: {'wpgmza_persisten_notice_quick_action'} **/
/** Parameters found in function processBackgroundAction(): {"post": ["relay", "wpgmza_security", "slug", "map_engine"]} **/
function processBackgroundAction(){
		global $wpgmza;

		if (empty($_POST['relay']) || empty($_POST['wpgmza_security']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['wpgmza_security'])), 'wpgmza_ajaxnonce') || !$wpgmza->isUserAllowedToEdit()) {
			wp_send_json_error(__( 'Security check failed, import will continue, however, we cannot provide you with live updates', 'wp-google-maps' ));
		}

		$relayAction = sanitize_text_field(wp_unslash($_POST['relay']));
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

					/* Dismiss it - It's one-switch and done */
					if(!empty($_POST['slug'])){
						$slug = sanitize_text_field(wp_unslash($_POST['slug']));
						if (!empty($slug)){
							$this->dismiss($slug);
						}
					}
					break;
				case 'swap_map_engine_from_toolbar':
					/* We handle this here for simplicity - but it belongs somewhere else to be honest */
					global $wpgmza;
					$switch = !empty($_POST['map_engine']) ? sanitize_text_field($_POST['map_engine']) : false;
					$valid = array("google-maps", "leaflet-azure", "leaflet-stadia", "leaflet-maptiler", "leaflet-locationiq", "leaflet-zerocost", "leaflet", "open-layers-latest");
					
					if(in_array($switch, $valid)){
						/* Valid switch */
						$wpgmza->settings->wpgmza_maps_engine = $switch;

						switch($switch){
							case 'leaflet-azure':
								if(empty($wpgmza->settings->tile_server_url_leaflet_azure)){
									$wpgmza->settings->tile_server_url_leaflet_azure = "{alias:azure-multilayer}";
								}
								break;
							case 'leaflet-stadia':
								if(empty($wpgmza->settings->tile_server_url_leaflet_stadia)){
									$wpgmza->settings->tile_server_url_leaflet_stadia = "{alias:stadia-multilayer}";
								}
								break;
							case 'leaflet-maptiler':
								if(empty($wpgmza->settings->tile_server_url_leaflet_maptiler)){
									$wpgmza->settings->tile_server_url_leaflet_maptiler = "{alias:maptiler-multilayer}";
								}
								break;
							case 'leaflet-locationiq':
								if(empty($wpgmza->settings->tile_server_url_leaflet_locationiq)){
									$wpgmza->settings->tile_server_url_leaflet_locationiq = "https://{s}-tiles.locationiq.com/v3/streets/r/{z}/{x}/{y}.png";
								}
								break;
							case 'leaflet-zerocost':
								if(empty($wpgmza->settings->tile_server_url_leaflet_zerocost)){
									$wpgmza->settings->tile_server_url_leaflet_zerocost = "https://tiles.openfreemap.org/styles/liberty";
								}
								break;
							case 'leaflet':
							case 'open-layers-latest':
								if(empty($wpgmza->settings->tile_server_url)){
									$wpgmza->settings->tile_server_url = "https://{a-c}.tile.openstreetmap.org/{z}/{x}/{y}.png";
								}
								break;
						}
					}
					break;
				case 'swap_map_engine_from_toolbar_dismiss':
					/* We handle this here for simplicity - but it belongs somewhere else to be honest */
					update_option("wpgmza-engine-switch-toolbar-dismissed", date("Y-m-d H:i:s"), false);
					break;
			}
		}

	    /* Developer Hook (Action) - Add processing for non standard background actions present in persistent notifications */     
		do_action("wpgmza_admin_notice_process_background_action", $relayAction);

		wp_send_json_success('Complete');
	}


/** Function onAJAXRequest() called by wp_ajax hooks: {'nopriv_wpgmza_rest_api_request', 'wpgmza_rest_api_request'} **/
/** Parameters found in function onAJAXRequest(): {"request": ["route", "action"], "server": ["REQUEST_URI", "REQUEST_METHOD"], "post": ["action"]} **/
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

		/* In some cases, the route will include sub-pathing, and for some reason the system cannot always deal with that
		 * this will attempt to resolve that by directly altering the request data to map that correctly 
		*/
		if(!empty($_REQUEST['action']) && $_REQUEST['action'] === 'wpgmza_rest_api_request'){
			$remapExclusions = array("/features/", "/marker-listing/");
			if(!empty($_REQUEST['route']) && !in_array($_REQUEST['route'], $remapExclusions)){
				/* Mutate the request URI */
				$route = $_REQUEST['route'];

				if(strpos($route, '/wpgmza/v1') === FALSE){
					$route = "/wpgmza/v1{$route}";
				}

				$_SERVER['REQUEST_URI'] = $route;

				if(!empty($_POST['action']) && $_POST['action'] === 'wpgmza_rest_api_request'){
					unset($_POST['action']);
				}
			}
		}

		/* Check for delete simulation - To allow nonce checks when applicable */
		$this->checkForDeleteSimulation();
		
		// Try to match the route
		$args = null;
		
		foreach($this->fallbackRoutesByRegex as $regex => $value)
		{
			if(preg_match($regex, $_REQUEST['route']))
			{
				$method = !empty($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : false;
				if(empty($method)){
					/* Unknown request method - Do nothing */
					continue;
				}


				if(empty($value['methods']) || (!empty($value['methods']) && is_array($value['methods']) && !in_array($method, $value['methods']))){
					/* Method does not match this routes definition */
					continue;
				}

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


/** Function dismissFromPostAjax() called by wp_ajax hooks: {'wpgmza_dismiss_persistent_notice'} **/
/** Parameters found in function dismissFromPostAjax(): {"post": ["slug", "wpgmza_security"]} **/
function dismissFromPostAjax(){
		global $wpgmza;
		
		if (empty($_POST['slug']) || empty($_POST['wpgmza_security']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['wpgmza_security'])), 'wpgmza_ajaxnonce') || !$wpgmza->isUserAllowedToEdit()) {
			wp_send_json_error(__( 'Security check failed, import will continue, however, we cannot provide you with live updates', 'wp-google-maps' ));
		}

		$slug = sanitize_text_field(wp_unslash($_POST['slug']));
		if (!empty($slug)){
			$this->dismiss($slug);
			wp_send_json_success('Complete');
		}

		wp_send_json_error('Could not complete');
	}


/** Function WPGMZA\\clear_nominatim_cache() called by wp_ajax hooks: {'wpgmza_clear_nominatim_cache'} **/
/** No function found :-/ **/


/** Function WPGMZA\\InstallerPage() called by wp_ajax hooks: {'wpgmza_installer_page_auto_onboarding_procedure', 'wpgmza_installer_page_skip', 'wpgmza_installer_page_save_options', 'wpgmza_installer_page_temp_api_key'} **/
/** No function found :-/ **/


/** Function WPGMZA\\SettingsPage() called by wp_ajax hooks: {'wpgmza_maps_settings_danger_zone_delete_data'} **/
/** No function found :-/ **/


/** Function wpgmaps_action_callback_pro() called by wp_ajax hooks: {'add_marker', 'delete_marker', 'delete_poly', 'delete_dataset', 'approve_marker', 'delete_polyline', 'delete_rectangle', 'edit_marker', 'delete_circle'} **/
/** No function found :-/ **/


/** Function onReportRestAPIBlocked() called by wp_ajax hooks: {'wpgmza_report_rest_api_blocked', 'nopriv_wpgmza_report_rest_api_blocked'} **/
/** No params detected :-/ **/


/** Function WPGMZA\\MapEditorTour() called by wp_ajax hooks: {'wpgmza_tour_progress_update'} **/
/** No function found :-/ **/


/** Function WPGMZA\\MapsEngineDialog() called by wp_ajax hooks: {'wpgmza_maps_engine_dialog_set_engine'} **/
/** No function found :-/ **/


