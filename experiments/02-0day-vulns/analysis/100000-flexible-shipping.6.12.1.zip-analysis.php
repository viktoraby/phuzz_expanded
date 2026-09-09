<?php
/***
*
*Found actions: 8
*Found functions:8
*Extracted functions:6
*Total parameter names extracted: 3
*Overview: {'check_deleted_methods': {'woocommerce_shipping_zone_methods_save_changes'}, 'processAjaxNoticeDismiss': {'wpdesk_notice_dismiss'}, 'wp_ajax_flexible_shipping_close_rate_notice': {'flexible_shipping_close_rate_notice'}, 'flexible_shipping_export': {'flexible_shipping_export'}, 'wp_ajax_flexible_shipping_rate_notice': {'flexible_shipping_rate_notice'}, 'wp_ajax_wpdesk_tracker_notice_handler': {'wpdesk_tracker_notice_handler'}, 'wp_ajax_wpdesk_tracker_deactivation_handler': {'wpdesk_tracker_deactivation_handler'}, 'wp_ajax_flexible_shipping': {'flexible_shipping'}}
*
***/

/** Function check_deleted_methods() called by wp_ajax hooks: {'woocommerce_shipping_zone_methods_save_changes'} **/
/** Parameters found in function check_deleted_methods(): {"post": ["wc_shipping_zones_nonce", "zone_id", "changes"]} **/
function check_deleted_methods() {
		if ( ! isset( $_POST['wc_shipping_zones_nonce'], $_POST['zone_id'], $_POST['changes'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( wp_unslash( $_POST['wc_shipping_zones_nonce'] ), 'wc_shipping_zones_nonce' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			return;
		}

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		$changes = wp_unslash( $_POST['changes'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		if ( ! isset( $changes['methods'] ) ) {
			return;
		}

		foreach ( $changes['methods'] as $instance_id => $data ) {
			if ( ! isset( $data['deleted'] ) ) {
				continue;
			}

			$shipping_method = WC_Shipping_Zones::get_shipping_method( $instance_id );

			if ( ! $shipping_method instanceof WPDesk_Flexible_Shipping ) {
				continue;
			}

			$this->to_delete_methods[] = $instance_id;
		}
	}


/** Function processAjaxNoticeDismiss() called by wp_ajax hooks: {'wpdesk_notice_dismiss'} **/
/** No params detected :-/ **/


/** Function wp_ajax_flexible_shipping_close_rate_notice() called by wp_ajax hooks: {'flexible_shipping_close_rate_notice'} **/
/** No function found :-/ **/


/** Function flexible_shipping_export() called by wp_ajax hooks: {'flexible_shipping_export'} **/
/** No params detected :-/ **/


/** Function wp_ajax_flexible_shipping_rate_notice() called by wp_ajax hooks: {'flexible_shipping_rate_notice'} **/
/** No function found :-/ **/


/** Function wp_ajax_wpdesk_tracker_notice_handler() called by wp_ajax hooks: {'wpdesk_tracker_notice_handler'} **/
/** Parameters found in function wp_ajax_wpdesk_tracker_notice_handler(): {"request": ["type"]} **/
function wp_ajax_wpdesk_tracker_notice_handler()
        {
            \check_ajax_referer(self::WPDESK_TRACKER_NOTICE, 'security');
            if (!\current_user_can('activate_plugins')) {
                die;
            }
            $option = \get_option('wpdesk_helper_options');
            if (!$option) {
                \add_option('wpdesk_helper_options', []);
            }
            $type = '';
            if (isset($_REQUEST['type'])) {
                $type = \sanitize_key($_REQUEST['type']);
            }
            if ($type === 'allow') {
                $options = \get_option('wpdesk_helper_options', []);
                if (!\is_array($options)) {
                    $options = [];
                }
                \update_option('wpdesk_helper_options', $options);
                \delete_option('wpdesk_tracker_notice');
                $options['wpdesk_tracker_agree'] = '1';
                \update_option('wpdesk_helper_options', $options);
            }
            if ($type === 'dismiss') {
                $options = \get_option('wpdesk_helper_options', []);
                if (!\is_array($options)) {
                    $options = [];
                }
                \update_option('wpdesk_tracker_notice', 'dismiss_all');
                $options['wpdesk_tracker_agree'] = '0';
                \update_option('wpdesk_helper_options', $options);
            }
        }


/** Function wp_ajax_wpdesk_tracker_deactivation_handler() called by wp_ajax hooks: {'wpdesk_tracker_deactivation_handler'} **/
/** No params detected :-/ **/


/** Function wp_ajax_flexible_shipping() called by wp_ajax hooks: {'flexible_shipping'} **/
/** Parameters found in function wp_ajax_flexible_shipping(): {"request": ["nonce", "shipment_id", "data", "fs_action"]} **/
function wp_ajax_flexible_shipping()
    {
        $json = array('status' => 'fail');
        $json['message'] = __('Unknown error!', 'flexible-shipping');
        if (empty($_REQUEST['nonce']) || !wp_verify_nonce(sanitize_text_field($_REQUEST['nonce']), 'flexible_shipping_shipment_nonce')) {
            $json['status'] = 'fail';
            $json['message'] = __('Nonce verification error! Invalid request.', 'flexible-shipping');
        } else if (!current_user_can('manage_woocommerce')) {
            $json['status'] = 'fail';
            $json['message'] = __('Insufficient user permissions!', 'flexible-shipping');
        } else if (empty($_REQUEST['shipment_id'])) {
            $json['status'] = 'fail';
            $json['message'] = __('No shipment id!', 'flexible-shipping');
        } else if (empty($_REQUEST['data']) || !is_array($_REQUEST['data'])) {
            $json['status'] = 'fail';
            $json['message'] = __('No data!', 'flexible-shipping');
        } else {
            $shipment = fs_get_shipment(intval($_REQUEST['shipment_id']));
            $action = sanitize_key($_REQUEST['fs_action']);
            $data = $_REQUEST['data'];
            try {
                $ajax_request = $shipment->ajax_request($action, $data);
                if (is_array($ajax_request)) {
                    $json['content'] = $ajax_request['content'];
                    $json['message'] = '';
                    if (isset($ajax_request['message'])) {
                        $json['message'] = $ajax_request['message'];
                    }
                } else {
                    $json['content'] = $ajax_request;
                    $json['message'] = '';
                    if ($action == 'save') {
                        $json['message'] = __('Saved', 'flexible-shipping');
                    }
                    if ($action == 'send') {
                        $json['message'] = __('Created', 'flexible-shipping');
                    }
                }
                $json['status'] = 'success';
                if (!empty($ajax_request['status'])) {
                    $json['status'] = $ajax_request['status'];
                }
            } catch (\Exception $e) {
                $json['status'] = 'fail';
                $json['message'] = $e->getMessage();
            }
        }
        wp_send_json($json);
    }


