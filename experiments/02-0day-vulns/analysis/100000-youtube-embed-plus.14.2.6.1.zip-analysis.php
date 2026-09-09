<?php
/***
*
*Found actions: 8
*Found functions:7
*Extracted functions:7
*Total parameter names extracted: 5
*Overview: {'onboarding_save_ajax': {'my_embedplus_onboarding_save_ajax'}, 'my_embedplus_dismiss_double_plugin_warning': {'my_embedplus_dismiss_double_plugin_warning'}, 'my_embedplus_glance_count': {'my_embedplus_glance_count'}, 'my_embedplus_gallery_page': {'nopriv_my_embedplus_gallery_page', 'my_embedplus_gallery_page'}, 'onboarding_save_apikey_ajax': {'my_embedplus_onboarding_save_apikey_ajax'}, 'my_embedplus_glance_vids': {'my_embedplus_glance_vids'}, 'settings_save_ajax': {'my_embedplus_settings_save_ajax'}}
*
***/

/** Function onboarding_save_ajax() called by wp_ajax hooks: {'my_embedplus_onboarding_save_ajax'} **/
/** No params detected :-/ **/


/** Function my_embedplus_dismiss_double_plugin_warning() called by wp_ajax hooks: {'my_embedplus_dismiss_double_plugin_warning'} **/
/** Parameters found in function my_embedplus_dismiss_double_plugin_warning(): {"server": ["HTTP_REFERER"]} **/
function my_embedplus_dismiss_double_plugin_warning()
    {
        $result = array();
        if (self::is_ajax())
        {
            if (!current_user_can('manage_options'))
            {
                wp_send_json_error('Unauthorized');
                die();
            }
            $user_id = get_current_user_id();
            update_user_meta($user_id, 'embedplus_double_plugin_warning', 1);
            $result['type'] = 'success';
            echo json_encode($result);
        }
        else
        {
            $result['type'] = 'error';
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }
        die();
    }


/** Function my_embedplus_glance_count() called by wp_ajax hooks: {'my_embedplus_glance_count'} **/
/** Parameters found in function my_embedplus_glance_count(): {"server": ["HTTP_REFERER"]} **/
function my_embedplus_glance_count()
    {
        $result = array();
        if (self::is_ajax())
        {
            if (!current_user_can('edit_posts'))
            {
                wp_send_json_error('Unauthorized');
                die();
            }
            $thehtml = '';

            try
            {
                if (version_compare(get_bloginfo('version'), '3.8', '>='))
                {
                    $result['container'] = '#dashboard_right_now ul';
                    $thehtml .= self::show_glance_list();
                }
                else
                {
                    $result['container'] = '#dashboard_right_now .table_content table tbody';
                    $thehtml .= self::show_glance_table();
                }
                $result['type'] = 'success';
                $result['data'] = $thehtml;
            }
            catch (Exception $e)
            {
                $result['type'] = 'error';
            }

            echo json_encode($result);
        }
        else
        {
            $result['type'] = 'error';
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }
        die();
    }


/** Function my_embedplus_gallery_page() called by wp_ajax hooks: {'nopriv_my_embedplus_gallery_page', 'my_embedplus_gallery_page'} **/
/** Parameters found in function my_embedplus_gallery_page(): {"post": ["options"]} **/
function my_embedplus_gallery_page()
    {
        if (self::is_ajax())
        {
            //check_ajax_referer('embedplus-nonce', 'security');
            $options = (object) $_POST['options'];
            $options->apiKey = self::$alloptions[self::$opt_apikey];
            echo self::get_gallery_page($options)->html;
            die();
        }
    }


/** Function onboarding_save_apikey_ajax() called by wp_ajax hooks: {'my_embedplus_onboarding_save_apikey_ajax'} **/
/** No params detected :-/ **/


/** Function my_embedplus_glance_vids() called by wp_ajax hooks: {'my_embedplus_glance_vids'} **/
/** Parameters found in function my_embedplus_glance_vids(): {"request": ["postid"], "server": ["HTTP_REFERER"]} **/
function my_embedplus_glance_vids()
    {
        $result = array();
        if (self::is_ajax())
        {
            if (!current_user_can('edit_posts'))
            {
                wp_send_json_error('Unauthorized');
                die();
            }
            $postid = intval($_REQUEST['postid']);
            $currpost = get_post($postid);

            $thehtml = '<img alt="close" class="accclose" onclick="accclose(this)" src="' . plugins_url('images/accclose.png', __FILE__) . '" />';

            $matches = array();
            $ismatch = preg_match_all(self::$justurlregex, $currpost->post_content, $matches);

            if ($ismatch)
            {
                foreach ($matches[0] as $match)
                {
                    $link = trim(preg_replace('/&amp;/i', '&', $match));
                    $link = preg_replace('/\s/', '', $link);
                    $link = trim(str_replace(self::$badentities, self::$goodliterals, $link));

                    $linkparamstemp = explode('?', $link);

                    $linkparams = array();
                    if (count($linkparamstemp) > 1)
                    {
                        $linkparams = self::keyvalue($linkparamstemp[1], true);
                    }
                    if (strpos($linkparamstemp[0], 'youtu.be') !== false && !isset($linkparams['v']))
                    {
                        $vtemp = explode('/', $linkparamstemp[0]);
                        $linkparams['v'] = array_pop($vtemp);
                    }

                    $vidid = $linkparams['v'];

                    if ($vidid != null)
                    {
                        try
                        {
                            $odata = self::get_oembed('https://youtube.com/watch?v=' . $vidid, 1920, 1280);
                            $postlink = get_permalink($postid);
                            if ($odata != null && !is_wp_error($odata))
                            {
                                $_name = esc_attr(sanitize_text_field($odata->title));
                                $_description = esc_attr(sanitize_text_field($odata->author_name));
                                $_thumbnailUrl = esc_url("https://i.ytimg.com/vi/" . $vidid . "/0.jpg");

                                $thehtml .= '<a target="_blank" href="' . $postlink . '" class="accthumb"><img alt="YouTube Video" src="' . $_thumbnailUrl . '" /></a>';
                                $thehtml .= '<div class="accinfo">';
                                $thehtml .= '<a target="_blank" href="' . $postlink . '" class="accvidtitle">' . $_name . '</a>';
                                $thehtml .= '<div class="accdesc">' . (strlen($_description) > 400 ? substr($_description, 0, 400) . "..." : $_description) . '</div>';
                                $thehtml .= '</div>';
                                $thehtml .= '<div class="clearboth pad20"></div>';
                            }
                            else
                            {
                                $thehtml .= '<p class="center bold orange">This <a target="_blank" href="' . $postlink . '">post/page</a> contains a video that has been removed from YouTube.';
                                $thehtml .= '<br><a target="_blank" href="https://www.embedplus.com/dashboard/pro-easy-video-analytics.aspx?ref=glancevids">Activate delete video tracking to catch these cases &raquo;</a>';
                                $thehtml .= '</strong>';
                            }
                        }
                        catch (Exception $ex)
                        {
                            
                        }
                    }
                    else if (isset($linkparams['list']))
                    {
                        // if playlist
                        try
                        {
                            $odata = self::get_oembed('https://youtube.com/playlist?list=' . $linkparams['list'], 1920, 1280);
                            $postlink = get_permalink($postid);
                            if ($odata != null && !is_wp_error($odata))
                            {
                                $_name = esc_attr(sanitize_text_field($odata->title));
                                $_description = esc_attr(sanitize_text_field($odata->author_name));
                                $_thumbnailUrl = esc_url($odata->thumbnail_url);

                                $thehtml .= '<a target="_blank" href="' . $postlink . '" class="accthumb"><img alt="YouTube Video" src="' . $_thumbnailUrl . '" /></a>';
                                $thehtml .= '<div class="accinfo">';
                                $thehtml .= '<a target="_blank" href="' . $postlink . '" class="accvidtitle">' . $_name . '</a>';
                                $thehtml .= '<div class="accdesc">' . (strlen($_description) > 400 ? substr($_description, 0, 400) . "..." : $_description) . '</div>';
                                $thehtml .= '</div>';
                                $thehtml .= '<div class="clearboth pad20"></div>';
                            }
                            else
                            {
                                $thehtml .= '<p class="center bold orange">This <a target="_blank" href="' . $postlink . '">post/page</a> contains a video that has been removed from YouTube.';
                                $thehtml .= '<br><a target="_blank" href="https://www.embedplus.com/dashboard/pro-easy-video-analytics.aspx?ref=glancevids">Activate delete video tracking to catch these cases &raquo;</a>';
                                $thehtml .= '</strong>';
                            }
                        }
                        catch (Exception $ex)
                        {
                            
                        }
                    }
                }
            }



            if ($currpost != null)
            {
                $result['type'] = 'success';
                $result['data'] = $thehtml;
            }
            else
            {
                $result['type'] = 'error';
            }
            echo json_encode($result);
        }
        else
        {
            $result['type'] = 'error';
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }
        die();
    }


/** Function settings_save_ajax() called by wp_ajax hooks: {'my_embedplus_settings_save_ajax'} **/
/** No params detected :-/ **/


