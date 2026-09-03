<?php
/***
*
*Found actions: 44
*Found functions:44
*Extracted functions:44
*Total parameter names extracted: 26
*Overview: {'get_slideshow_default_settings': {'ms_get_slideshow_default_settings'}, 'get_single_slideshow': {'ms_get_single_slideshow'}, 'ajax_quickstart_ads': {'quickstart_ads'}, 'save_global_settings_single': {'ms_update_global_settings_single'}, 'ajax_undelete_slide': {'undelete_slide'}, 'set_theme': {'ms_set_theme'}, 'get_global_settings': {'ms_get_global_settings'}, 'save_pro_settings': {'ms_update_pro_settings'}, 'get_slideshows': {'ms_get_slideshows'}, 'get_all_free_themes': {'ms_get_all_free_themes'}, 'save_user_setting': {'ms_update_user_setting'}, 'ajax_quickstart_slideshow': {'quickstart_slideshow'}, 'save_slideshow': {'ms_save_slideshow'}, 'import_others': {'ms_import_others'}, 'ajax_delete_slide': {'delete_slide'}, 'export_slideshows': {'ms_export_slideshows'}, 'get_image_ids_from_file_name': {'ms_get_image_ids_from_filenames'}, 'set_tour_status': {'set_tour_status'}, 'ajax_update_slide_image': {'update_slide_image'}, 'ajax_notice_handler': {'notice_handler'}, 'import_images': {'ms_import_images'}, 'delete_slideshow': {'ms_delete_slideshow'}, 'ajax_resize_slide': {'resize_image_slide'}, 'ajax_quickstart_upload': {'quickstart_upload'}, 'save_single_slideshow_setting': {'ms_update_single_slideshow_setting'}, 'ajax_duplicate_slide': {'duplicate_slide'}, 'get_single_setting': {'ms_get_single_setting'}, 'get_legacy_slideshows': {'ms_get_legacy_slideshows'}, 'get_custom_themes': {'ms_get_custom_themes'}, 'save_global_settings': {'ms_update_global_settings'}, 'list_slideshows': {'ms_list_slideshows'}, 'save_all_slideshow_settings': {'ms_update_all_slideshow_settings'}, 'ajax_quickstart_slideshow_v2': {'quickstart_slideshow_v2'}, 'ajax_permanent_delete_slide': {'permanent_delete_slide'}, 'ajax_create_image_slides': {'create_image_slide'}, 'get_preview': {'ms_get_preview'}, 'get_user_details': {'ms_get_user_details'}, 'save_slideshow_default_settings': {'ms_save_slideshow_default_settings'}, 'import_slideshows': {'ms_import_slideshows'}, 'duplicate_slideshow': {'ms_duplicate_slideshow'}, 'get_theme_customization': {'ms_get_theme_customization'}, 'ajax_crop_position_image_slide': {'crop_position_image_slide'}, 'get_pro_settings': {'ms_get_pro_settings'}, 'search_slideshows': {'ms_search_slideshows'}}
*
***/

/** Function get_slideshow_default_settings() called by wp_ajax hooks: {'ms_get_slideshow_default_settings'} **/
/** No params detected :-/ **/


/** Function get_single_slideshow() called by wp_ajax hooks: {'ms_get_single_slideshow'} **/
/** No params detected :-/ **/


/** Function ajax_quickstart_ads() called by wp_ajax hooks: {'quickstart_ads'} **/
/** Parameters found in function ajax_quickstart_ads(): {"request": ["_wpnonce"], "post": ["ad_identifier"]} **/
function ajax_quickstart_ads()
    {
        if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['_wpnonce'] ), 'metaslider_handle_notices_nonce' ) ) {
            wp_send_json_error( array(
                'message' => __( 'The security check failed. Please refresh the page and try again.', 'ml-slider' )
            ), 401 );
        }

        $capability = apply_filters( 'metaslider_capability', MetaSliderPlugin::DEFAULT_CAPABILITY_EDIT_SLIDES );
        if ( ! current_user_can( $capability ) ) {
            wp_send_json_error( array(
                'message' => __( 'Access denied. Sorry, you do not have permission to complete this task.', 'ml-slider' )
            ),
            403 );
        }

        if ( ! isset( $_POST['ad_identifier'] ) ) {
            wp_send_json_error( array(
                'message' => __( 'Bad request', 'ml-slider' )
            ), 400 );
        }

        $ad_identifier = sanitize_key( $_POST['ad_identifier'] );

        // Check valid ad_identifiers
        $valid_ad_identifiers = array(
            'thankyou'
        );

        if ( ! in_array( $ad_identifier, $valid_ad_identifiers ) ) {
            wp_send_json_error( array(
                'message' => __( 'Invalid ad identifier', 'ml-slider' )
            ), 400 );
        }

        $result = update_option( "metaslider_quickstart_ad_{$ad_identifier}", 'hide', false );

        if ( ! $result ) {
            wp_send_json_error( array(
                'message' => __( 'There was an error dimissing the quickstart ad', 'ml-slider' )
            ), 400 );
        }

        wp_send_json_success(array(
            'message' => __( 'The quickstart ad was successfully hidden!', 'ml-slider' ),
        ), 200);
    }


/** Function save_global_settings_single() called by wp_ajax hooks: {'ms_update_global_settings_single'} **/
/** No params detected :-/ **/


/** Function ajax_undelete_slide() called by wp_ajax hooks: {'undelete_slide'} **/
/** Parameters found in function ajax_undelete_slide(): {"request": ["_wpnonce"], "post": ["slide_id", "slider_id"]} **/
function ajax_undelete_slide()
        {
            if (! isset($_REQUEST['_wpnonce']) || ! wp_verify_nonce(
                    sanitize_key($_REQUEST['_wpnonce']),
                    'metaslider_undelete_slide'
                )) {
                wp_send_json_error(array(
                    'message' => __('The security check failed. Please refresh the page and try again.', 'ml-slider')
                ), 401);
            }

            $capability = apply_filters('metaslider_capability', MetaSliderPlugin::DEFAULT_CAPABILITY_EDIT_SLIDES);
            if (! current_user_can($capability)) {
                wp_send_json_error(
                    [
                        'message' => __('Access denied. Sorry, you do not have permission to complete this task.', 'ml-slider')
                    ],
                    403
                );
            }

            if (! isset($_POST['slide_id']) || ! isset($_POST['slider_id'])) {
                wp_send_json_error(
                    [
                        'message' => __('Bad request', 'ml-slider'),
                    ],
                    400
                );
            }

            $result = $this->undelete_slide(absint($_POST['slide_id']), absint($_POST['slider_id']));

            if (is_wp_error($result)) {
                wp_send_json_error(array(
                    'message' => $result->get_error_message()
                ), 409);
            }

            wp_send_json_success(array(
                'message' => __('The slide was successfully restored', 'ml-slider'),
            ), 200);
        }


/** Function set_theme() called by wp_ajax hooks: {'ms_set_theme'} **/
/** No params detected :-/ **/


/** Function get_global_settings() called by wp_ajax hooks: {'ms_get_global_settings'} **/
/** No params detected :-/ **/


/** Function save_pro_settings() called by wp_ajax hooks: {'ms_update_pro_settings'} **/
/** No params detected :-/ **/


/** Function get_slideshows() called by wp_ajax hooks: {'ms_get_slideshows'} **/
/** No params detected :-/ **/


/** Function get_all_free_themes() called by wp_ajax hooks: {'ms_get_all_free_themes'} **/
/** No params detected :-/ **/


/** Function save_user_setting() called by wp_ajax hooks: {'ms_update_user_setting'} **/
/** No params detected :-/ **/


/** Function ajax_quickstart_slideshow() called by wp_ajax hooks: {'quickstart_slideshow'} **/
/** Parameters found in function ajax_quickstart_slideshow(): {"request": ["_wpnonce"], "post": ["images"]} **/
function ajax_quickstart_slideshow() {
            if (! isset($_REQUEST['_wpnonce']) || ! wp_verify_nonce(
                sanitize_key($_REQUEST['_wpnonce']),
                'metaslider_quickstart_slideshow'
            )) {
                wp_send_json_error(array(
                    'message' => __('The security check failed. Please refresh the page and try again.', 'ml-slider')
                ), 401);
            }

            $capability = apply_filters('metaslider_capability', MetaSliderPlugin::DEFAULT_CAPABILITY_EDIT_SLIDES);
            if (! current_user_can($capability)) {
                wp_send_json_error(
                    [
                        'message' => __('Access denied. Sorry, you do not have permission to complete this task.', 'ml-slider')
                    ],
                    403
                );
            }

            if (!isset($_POST['images'])) {
                wp_send_json_error(
                    [
                        'message' => __('Bad request', 'ml-slider'),
                    ],
                    400
                );
            }

            $images = $_POST['images'];
            $slideshow_id = MetaSlider_Slideshows::create();
            $slide = new MetaImageSlide();
            foreach ($images as $image) {
                $data = array('id' => (int)$image, 'type' => 'image');
                $slide->add_slide($slideshow_id, $data);
            }

            wp_send_json($slideshow_id);
            wp_die();
        }


/** Function save_slideshow() called by wp_ajax hooks: {'ms_save_slideshow'} **/
/** No params detected :-/ **/


/** Function import_others() called by wp_ajax hooks: {'ms_import_others'} **/
/** No params detected :-/ **/


/** Function ajax_delete_slide() called by wp_ajax hooks: {'delete_slide'} **/
/** Parameters found in function ajax_delete_slide(): {"request": ["_wpnonce"], "post": ["slide_id", "slider_id"]} **/
function ajax_delete_slide()
        {
            if (! isset($_REQUEST['_wpnonce']) || ! wp_verify_nonce(
                    sanitize_key($_REQUEST['_wpnonce']),
                    'metaslider_delete_slide'
                )) {
                wp_send_json_error(array(
                    'message' => __('The security check failed. Please refresh the page and try again.', 'ml-slider')
                ), 401);
            }

            $capability = apply_filters('metaslider_capability', MetaSliderPlugin::DEFAULT_CAPABILITY_EDIT_SLIDES);
            if (! current_user_can($capability)) {
                wp_send_json_error(
                    [
                        'message' => __('Access denied. Sorry, you do not have permission to complete this task.', 'ml-slider')
                    ],
                    403
                );
            }

            if (! isset($_POST['slide_id']) || ! isset($_POST['slider_id'])) {
                wp_send_json_error(
                    [
                        'message' => __('Bad request', 'ml-slider'),
                    ],
                    400
                );
            }

            $result = $this->delete_slide(absint($_POST['slide_id']), absint($_POST['slider_id']));

            if (is_wp_error($result)) {
                wp_send_json_error(array(
                    'message' => $result->get_error_message()
                ), 409);
            }

            wp_send_json_success(array(
                'message' => __('The slide was successfully trashed', 'ml-slider'),
            ), 200);
        }


/** Function export_slideshows() called by wp_ajax hooks: {'ms_export_slideshows'} **/
/** No params detected :-/ **/


/** Function get_image_ids_from_file_name() called by wp_ajax hooks: {'ms_get_image_ids_from_filenames'} **/
/** No params detected :-/ **/


/** Function set_tour_status() called by wp_ajax hooks: {'set_tour_status'} **/
/** No params detected :-/ **/


/** Function ajax_update_slide_image() called by wp_ajax hooks: {'update_slide_image'} **/
/** Parameters found in function ajax_update_slide_image(): {"request": ["_wpnonce"], "post": ["slide_id", "image_id", "slider_id"]} **/
function ajax_update_slide_image()
    {
        if (! isset($_REQUEST['_wpnonce']) || ! wp_verify_nonce(sanitize_key($_REQUEST['_wpnonce']), 'metaslider_update_slide_image')) {
            wp_send_json_error(array(
                'message' => __('The security check failed. Please refresh the page and try again.', 'ml-slider')
            ), 401);
        }

        $capability = apply_filters('metaslider_capability', MetaSliderPlugin::DEFAULT_CAPABILITY_EDIT_SLIDES);
        if (! current_user_can($capability)) {
            wp_send_json_error(
                [
                    'message' => __('Access denied. Sorry, you do not have permission to complete this task.', 'ml-slider')
                ],
                403
            );
        }

        if (! isset($_POST['slide_id']) || ! isset($_POST['image_id']) || ! isset($_POST['slider_id'])) {
            wp_send_json_error(
                [
                    'message' => __('Bad request', 'ml-slider'),
                ],
                400
            );
        }

        $result = $this->update_slide_image(
            absint($_POST['slide_id']),
            absint($_POST['image_id']),
            absint($_POST['slider_id'])
        );

        if (is_wp_error($result)) {
            wp_send_json_error(array(
                'message' => $result->get_error_message()
            ), 409);
        }
        wp_send_json_success($result, 200);
    }


/** Function ajax_notice_handler() called by wp_ajax hooks: {'notice_handler'} **/
/** Parameters found in function ajax_notice_handler(): {"request": ["_wpnonce"], "post": ["ad_identifier"]} **/
function ajax_notice_handler()
    {
        if (! isset($_REQUEST['_wpnonce']) || ! wp_verify_nonce(sanitize_key($_REQUEST['_wpnonce']), 'metaslider_handle_notices_nonce')) {
            wp_send_json_error(array(
                'message' => __('The security check failed. Please refresh the page and try again.', 'ml-slider')
            ), 401);
        }

        $capability = apply_filters('metaslider_capability', MetaSliderPlugin::DEFAULT_CAPABILITY_EDIT_SLIDES);
        if (! current_user_can($capability)) {
            wp_send_json_error(
                [
                    'message' => __('Access denied. Sorry, you do not have permission to complete this task.', 'ml-slider')
                ],
                403
            );
        }

        if (! isset($_POST['ad_identifier'])) {
            wp_send_json_error(array(
                'message' => __('Bad request', 'ml-slider')
            ), 400);
        }

        $ad_data = $this->ad_exists(sanitize_key($_POST['ad_identifier']));

        if (is_wp_error($ad_data)) {
            wp_send_json_error(array(
                'message' => __('This item does not exist. Please refresh the page and try again.', 'ml-slider')
            ), 401);
        }

        $result = $this->dismiss_ad($ad_data['dismiss_time'], $ad_data['hide_time']);

        if (is_wp_error($result)) {
            wp_send_json_error(array(
                'message' => $result->get_error_message()
            ), 409);
        }

        wp_send_json_success(array(
            'message' => __('The option was successfully updated', 'ml-slider'),
        ), 200);
    }


/** Function import_images() called by wp_ajax hooks: {'ms_import_images'} **/
/** Parameters found in function import_images(): {"files": ["files"]} **/
function import_images($request)
    {
        if (!$this->can_access()) {
            $this->deny_access();
        }

        $data = $this->get_request_data($request, array('slideshow_id', 'theme_id', 'slide_id', 'image_data', 'extra'));

        // Sanitize extra data
        if ( isset( $data['extra'] ) && is_array( $data['extra'] ) ) {
            $data['extra'] = array_map( 'sanitize_text_field', $data['extra'] );
        }

        // Create a slideshow if one doesn't exist
        if (is_null($data['slideshow_id']) || !absint($data['slideshow_id'])) {
            $data['slideshow_id'] = MetaSlider_Slideshows::create();
            if (is_wp_error($data['slideshow_id'])) {
                wp_send_json_error(array('message' => $data['slideshow_id']->getMessage()), 400);
            }
        }

        // If there are files here, then we need to prepare them
        // Dont use get_file_params() as it's WP4.4
        // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- REST route access is checked by can_access(), and uploaded files are validated by process_uploads().
        $images = isset($_FILES['files']) ? $this->process_uploads($_FILES['files'], $data['image_data']) : array();

        // $images should be an array of image data at this point
        $image_ids = MetaSlider_Image::instance()->import($images, $data['theme_id'], $data['extra']);
        if (is_wp_error($image_ids)) {
            wp_send_json_error(array(
                'message' => $image_ids->get_error_message()
            ), 400);
        }

        $errors = array();
        $html_rows = array();
        $method = is_null($data['slide_id']) ? 'create_slide' : 'update';
        foreach ($image_ids as $image_id) {
            $slide = new MetaSlider_Slide(absint($data['slideshow_id']), $data['slide_id']);
            $slide->add_image($image_id)->$method();

            if (is_wp_error($slide->error)) {
                array_push($errors, $slide->error);
            } else {
                $imageSlide = new MetaImageSlide();
                $imageSlide->set_slide( $slide->slide_id );

                $html_rows[] = array(
                    'slide_id' => $slide->slide_id
                    //'html' => $imageSlide->get_admin_slide()
                );
            }
        }

        // Send back the first error, if any
        if (isset($errors[0])) {
            wp_send_json_error(array(
                'message' => $errors[0]->get_error_message()
            ), 400);
        }

        wp_send_json( $html_rows );
    }


/** Function delete_slideshow() called by wp_ajax hooks: {'ms_delete_slideshow'} **/
/** No params detected :-/ **/


/** Function ajax_resize_slide() called by wp_ajax hooks: {'resize_image_slide'} **/
/** Parameters found in function ajax_resize_slide(): {"request": ["_wpnonce"], "post": ["slider_id", "slide_id"]} **/
function ajax_resize_slide()
    {
        if (! isset($_REQUEST['_wpnonce']) || ! wp_verify_nonce(sanitize_key($_REQUEST['_wpnonce']), 'metaslider_resize')) {
            wp_send_json_error(array(
                'message' => __('The security check failed. Please refresh the page and try again.', 'ml-slider')
            ), 401);
        }

        $capability = apply_filters('metaslider_capability', MetaSliderPlugin::DEFAULT_CAPABILITY_EDIT_SLIDES);
        if (! current_user_can($capability)) {
            wp_send_json_error(
                [
                    'message' => __('Access denied. Sorry, you do not have permission to complete this task.', 'ml-slider')
                ],
                403
            );
        }

        if (! isset($_POST['slider_id']) || ! isset($_POST['slide_id'])) {
            wp_send_json_error(
                [
                    'message' => __('Bad request', 'ml-slider'),
                ],
                400
            );
        }

        $slideshow_id = absint($_POST['slider_id']);
        $slide_id = absint($_POST['slide_id']);
        $settings = get_post_meta($slideshow_id, 'ml-slider_settings', true);
        if (empty($settings) || !is_array($settings)) {
            $settings = array();
        }

        $result = $this->resize_slide($slide_id, $slideshow_id, $settings);

        do_action("metaslider_ajax_resize_image_slide", $slide_id, $slideshow_id, $settings);

        if (is_wp_error($result)) {
            wp_send_json_error(array(
                'messages' => $result->get_error_messages()
            ), 409);
        }

        wp_send_json_success($result, 200);
    }


/** Function ajax_quickstart_upload() called by wp_ajax hooks: {'quickstart_upload'} **/
/** Parameters found in function ajax_quickstart_upload(): {"request": ["_wpnonce"]} **/
function ajax_quickstart_upload(){
            if (! isset($_REQUEST['_wpnonce']) || ! wp_verify_nonce(
                sanitize_key($_REQUEST['_wpnonce']),
                'metaslider_quickstart_upload'
            )) {
                wp_send_json_error(array(
                    'message' => __('The security check failed. Please refresh the page and try again.', 'ml-slider')
                ), 401);
            }

            $capability = apply_filters('metaslider_capability', MetaSliderPlugin::DEFAULT_CAPABILITY_EDIT_SLIDES);
            if (! current_user_can($capability)) {
                wp_send_json_error(
                    [
                        'message' => __('Access denied. Sorry, you do not have permission to complete this task.', 'ml-slider')
                    ],
                    403
                );
            }
            
            if (!isset($_FILES['async-upload'])) {
                wp_send_json_error(
                    [
                        'message' => __('Bad request', 'ml-slider'),
                    ],
                    400
                );
            }
            $file = $_FILES['async-upload'];
            $wp_upload_dir = wp_upload_dir();
            $uploaded = wp_handle_upload($file, array('test_form'=> false, 'action' => 'quickstart_upload'));
            
            // @since 3.103 - If there is an error, stop the process
            if ( isset( $uploaded['error'] ) ) {
                wp_send_json_error( array(
                    'message' => $uploaded['error']
                ), 409 );
            }
            $filename = $uploaded['file']; // Use path, not URL - $uploaded['url']

            $filetype = wp_check_filetype(basename($filename), null);
            $attachment = array(
                'guid'           => $wp_upload_dir['url'] . '/' . basename($filename),
                'post_mime_type' => $filetype['type'],
                'post_title'     => preg_replace('/\.[^.]+$/', '', basename($filename)),
                'post_content'   => '',
                'post_excerpt'   => '',
                'post_status'    => 'publish'
            );
            $attach_id = wp_insert_attachment($attachment, $filename);
            $attach_data = wp_generate_attachment_metadata($attach_id, $filename);
            $update_data = wp_update_attachment_metadata($attach_id, $attach_data);

            $settings = MetaSlider_Slideshow_Settings::defaults();
            $image_cropper = new MetaSliderImageHelper(
                $attach_id,
                $settings['width'],
                $settings['height'],
                isset($settings['smartCrop']) ? $settings['smartCrop'] : 'false',
                true,
                null,
                isset($settings['cropMultiply']) ? absint($settings['cropMultiply']) : 1
            );
            $newurl = $image_cropper->get_image_url();
    
            if (is_wp_error($attach_id)) {
                wp_send_json_error(array(
                    'message' => $attach_id->get_error_message()
                ), 409);
            }

            if (is_wp_error($attach_data)) {
                wp_send_json_error(array(
                    'message' => $attach_data->get_error_message()
                ), 409);
            }

            if (is_wp_error($update_data)) {
                wp_send_json_error(array(
                    'message' => $update_data->get_error_message()
                ), 409);
            }

            wp_send_json($attach_id);
            wp_die(); 
        }


/** Function save_single_slideshow_setting() called by wp_ajax hooks: {'ms_update_single_slideshow_setting'} **/
/** No params detected :-/ **/


/** Function ajax_duplicate_slide() called by wp_ajax hooks: {'duplicate_slide'} **/
/** Parameters found in function ajax_duplicate_slide(): {"request": ["_wpnonce"], "post": ["slide_id", "slider_id"]} **/
function ajax_duplicate_slide()
    {
        if (! isset($_REQUEST['_wpnonce']) || ! wp_verify_nonce(sanitize_key($_REQUEST['_wpnonce']), 'metaslider_duplicate_slide')) {
            wp_send_json_error(array(
                'message' => __('The security check failed. Please refresh the page and try again.', 'ml-slider')
            ), 401);
        }

        $capability = apply_filters('metaslider_capability', MetaSliderPlugin::DEFAULT_CAPABILITY_EDIT_SLIDES);
        if (! current_user_can($capability)) {
            wp_send_json_error(
                [
                    'message' => __('Access denied', 'ml-slider')
                ],
                403
            );
        }


        if (! isset($_POST['slide_id']) || ! isset($_POST['slider_id'])) {
            wp_send_json_error(
                [
                    'message' => __('Bad request', 'ml-slider'),
                ],
                400
            );
        }

        $result = $this->duplicate_slide(
            absint($_POST['slider_id']),
            absint($_POST['slide_id'])
        );

        wp_send_json_success($result, 200);
    }


/** Function get_single_setting() called by wp_ajax hooks: {'ms_get_single_setting'} **/
/** No params detected :-/ **/


/** Function get_legacy_slideshows() called by wp_ajax hooks: {'ms_get_legacy_slideshows'} **/
/** No params detected :-/ **/


/** Function get_custom_themes() called by wp_ajax hooks: {'ms_get_custom_themes'} **/
/** No params detected :-/ **/


/** Function save_global_settings() called by wp_ajax hooks: {'ms_update_global_settings'} **/
/** No params detected :-/ **/


/** Function list_slideshows() called by wp_ajax hooks: {'ms_list_slideshows'} **/
/** No params detected :-/ **/


/** Function save_all_slideshow_settings() called by wp_ajax hooks: {'ms_update_all_slideshow_settings'} **/
/** No params detected :-/ **/


/** Function ajax_quickstart_slideshow_v2() called by wp_ajax hooks: {'quickstart_slideshow_v2'} **/
/** Parameters found in function ajax_quickstart_slideshow_v2(): {"request": ["_wpnonce"], "get": ["slug"]} **/
function ajax_quickstart_slideshow_v2() {
            if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce(
                sanitize_key( $_REQUEST['_wpnonce'] ),
                'metaslider_quickstart_slideshow'
            ) ) {
                wp_send_json_error( array(
                    'message' => __( 'The security check failed. Please refresh the page and try again.', 'ml-slider' )
                ), 401 );
            }

            $capability = apply_filters( 'metaslider_capability', MetaSliderPlugin::DEFAULT_CAPABILITY_EDIT_SLIDES );
            if ( ! current_user_can( $capability ) ) {
                wp_send_json_error(
                    [
                        'message' => __( 'Access denied. Sorry, you do not have permission to complete this task.', 'ml-slider' )
                    ],
                    403
                );
            }

            if ( ! isset( $_GET['slug'] ) ) {
                wp_send_json_error( array(
                    'message' => __('Bad request', 'ml-slider'),
                ), 400 );
            }

            $slug = $_GET['slug'];
            $price = null;
            $file = null;

            if ( $slug ) {
                $msQuickstart = new MetaSliderQuickstart();

                foreach ( $msQuickstart->quickstart_options() as $option ) {
                    if ( isset( $option['slug'] ) && $option['slug'] === $slug ) {
                        $price = isset( $option['price'] ) ? $option['price'] : null;
                        break;
                    }
                }
            }

            if ( $price === 'free' ) {
                $file = file_exists( METASLIDER_PATH . 'admin/quickstart/' . $slug . '.json' ) 
				    ? METASLIDER_PATH . 'admin/quickstart/' . $slug . '.json' : null; 
            } elseif ( $price === 'pro' && class_exists( 'MetaSliderPro' ) ) {
                $file = file_exists( METASLIDERPRO_PATH . 'modules/quickstart/import/' . $slug . '.json' ) 
				    ? METASLIDERPRO_PATH . 'modules/quickstart/import/' . $slug . '.json' : null; 
            }
            
            if ( $file === null ) {
                wp_send_json_error( array(
                    'message' => __( 'Import file is missing', 'ml-slider' ),
                ), 400 );
            }

            $data = wp_json_file_decode( $file, array( 'associative' => true ) );

            $msSlideshows = new MetaSlider_Slideshows();

            $slideshow_id = $msSlideshows->import( array( $data[0] ), 'last_id' ); // We only need the first slideshow

            if ( is_wp_error( $slideshow_id ) ) {
                wp_send_json_error( array(
                    'message' => $slideshow_id->get_error_message()
                ), 409 );
            }

            wp_send_json( array(
                'slideshow_id' => $slideshow_id,
                'data' => array( $data[0] ) 
            ) );
            wp_die();
        }


/** Function ajax_permanent_delete_slide() called by wp_ajax hooks: {'permanent_delete_slide'} **/
/** Parameters found in function ajax_permanent_delete_slide(): {"request": ["_wpnonce"], "post": ["slide_id"]} **/
function ajax_permanent_delete_slide()
        {
            if (! isset($_REQUEST['_wpnonce']) || ! wp_verify_nonce(
                    sanitize_key($_REQUEST['_wpnonce']),
                    'metaslider_permanent_delete_slide'
                )) {
                wp_send_json_error(array(
                    'message' => __('The security check failed. Please refresh the page and try again.', 'ml-slider')
                ), 401);
            }

            $capability = apply_filters('metaslider_capability', MetaSliderPlugin::DEFAULT_CAPABILITY_EDIT_SLIDES);
            if (! current_user_can($capability)) {
                wp_send_json_error(
                    [
                        'message' => __('Access denied. Sorry, you do not have permission to complete this task.', 'ml-slider')
                    ],
                    403
                );
            }

            if (! isset($_POST['slide_id'])) {
                wp_send_json_error(
                    [
                        'message' => __('Bad request', 'ml-slider'),
                    ],
                    400
                );
            }

            $result = $this->permanent_delete_slide(absint($_POST['slide_id']));

            if (is_wp_error($result)) {
                wp_send_json_error(array(
                    'message' => $result->get_error_message()
                ), 409);
            }

            wp_send_json_success(array(
                'message' => __('The slide was permanently deleted', 'ml-slider'),
            ), 200);
        }


/** Function ajax_create_image_slides() called by wp_ajax hooks: {'create_image_slide'} **/
/** Parameters found in function ajax_create_image_slides(): {"request": ["_wpnonce"], "post": ["slider_id", "selection"]} **/
function ajax_create_image_slides()
    {
        if (! isset($_REQUEST['_wpnonce']) || ! wp_verify_nonce(sanitize_key($_REQUEST['_wpnonce']), 'metaslider_create_slide')) {
            wp_send_json_error(array(
                'message' => __('The security check failed. Please refresh the page and try again.', 'ml-slider')
            ), 401);
        }

        $capability = apply_filters('metaslider_capability', MetaSliderPlugin::DEFAULT_CAPABILITY_EDIT_SLIDES);
        if (! current_user_can($capability)) {
            wp_send_json_error(
                [
                    'message' => __('Access denied. Sorry, you do not have permission to complete this task.', 'ml-slider')
                ],
                403
            );
        }

        if (! isset($_POST['slider_id']) || ! isset($_POST['selection'])) {
            wp_send_json_error(
                [
                    'message' => __('Bad request', 'ml-slider'),
                ],
                400
            );
        }

        $capability = apply_filters('metaslider_capability', MetaSliderPlugin::DEFAULT_CAPABILITY_EDIT_SLIDES);
        if (! current_user_can($capability)) {
            wp_send_json_error(
                [
                    'message' => __('Access denied. Sorry, you do not have permission to complete this task.', 'ml-slider')
                ],
                403
            );
        }

        if(empty($_POST['slider_id'])) {
            $slider_id = MetaSlider_Slideshows::create();
        } else {
            $slider_id = absint($_POST['slider_id']);
        }

        $slides = $this->create_slides(
            $slider_id,
            array_map(array($this, 'make_image_slide_data'), $_POST['selection']) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        );

        if (is_wp_error($slides)) {
            wp_send_json_error(array(
                'messages' => $slides->get_error_messages()
            ), 409);
        }

        if(empty($_POST['slider_id'])) {
            $response = $slider_id;
        } else {
            $response = $slides;
        }
        
        wp_send_json_success($response, 200);
    }


/** Function get_preview() called by wp_ajax hooks: {'ms_get_preview'} **/
/** No params detected :-/ **/


/** Function get_user_details() called by wp_ajax hooks: {'ms_get_user_details'} **/
/** No params detected :-/ **/


/** Function save_slideshow_default_settings() called by wp_ajax hooks: {'ms_save_slideshow_default_settings'} **/
/** No params detected :-/ **/


/** Function import_slideshows() called by wp_ajax hooks: {'ms_import_slideshows'} **/
/** No params detected :-/ **/


/** Function duplicate_slideshow() called by wp_ajax hooks: {'ms_duplicate_slideshow'} **/
/** No params detected :-/ **/


/** Function get_theme_customization() called by wp_ajax hooks: {'ms_get_theme_customization'} **/
/** No params detected :-/ **/


/** Function ajax_crop_position_image_slide() called by wp_ajax hooks: {'crop_position_image_slide'} **/
/** Parameters found in function ajax_crop_position_image_slide(): {"request": ["_wpnonce"], "post": ["crop_position", "slide_id"]} **/
function ajax_crop_position_image_slide()
    {
        if (! isset($_REQUEST['_wpnonce']) || ! wp_verify_nonce(sanitize_key($_REQUEST['_wpnonce']), 'metaslider_resize')) {
            wp_send_json_error(array(
                'message' => __('The security check failed. Please refresh the page and try again.', 'ml-slider')
            ), 401);
        }

        $capability = apply_filters('metaslider_capability', MetaSliderPlugin::DEFAULT_CAPABILITY_EDIT_SLIDES);
        if (! current_user_can($capability)) {
            wp_send_json_error(
                [
                    'message' => __('Access denied. Sorry, you do not have permission to complete this task.', 'ml-slider')
                ],
                403
            );
        }

        if (! isset($_POST['crop_position']) || ! isset($_POST['slide_id'])) {
            wp_send_json_error(
                [
                    'message' => __('Bad request', 'ml-slider'),
                ],
                400
            );
        }

        $result = update_post_meta(
            absint($_POST['slide_id']), 
            'ml-slider_crop_position', 
            sanitize_text_field($_POST['crop_position'])
        );

        if (is_wp_error($result)) {
            wp_send_json_error(array(
                'messages' => $result->get_error_messages()
            ), 409);
        }

        wp_send_json_success($result, 200);
    }


/** Function get_pro_settings() called by wp_ajax hooks: {'ms_get_pro_settings'} **/
/** No params detected :-/ **/


/** Function search_slideshows() called by wp_ajax hooks: {'ms_search_slideshows'} **/
/** No params detected :-/ **/


