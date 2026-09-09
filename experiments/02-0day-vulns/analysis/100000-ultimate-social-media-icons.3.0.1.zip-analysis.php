<?php
/***
*
*Found actions: 49
*Found functions:47
*Extracted functions:45
*Total parameter names extracted: 46
*Overview: {'sfsi_currentDate': {'sfsi_currentDate'}, 'sfsi_cycleDate': {'sfsi_cycleDate'}, 'sfsi_hide_admin_forum_notification_callback': {'sfsi_hide_admin_forum_notification'}, 'sfsi_loyaltyDate': {'sfsi_loyaltyDate'}, 'sfsi_showNextBannerDate': {'sfsi_showNextBannerDate'}, 'sfsi_options_updater5': {'updateSrcn5'}, 'sfsi_save_export': {'sfsi_save_export'}, 'sfsi_options_updater3': {'updateSrcn3'}, 'activate_plugins': {'analyst_notification_dismiss'}, 'sfsi_UploadSkins': {'UploadSkins'}, 'sfsi_UploadIcons': {'UploadIcons'}, 'noticeAjax': {'tifm_notice_actions'}, 'sfsi_banner_global_load_faster': {'sfsi_banner_global_load_faster'}, 'sfsi_banner_global_upgrade': {'sfsi_banner_global_upgrade'}, 'sfsi_HideRatingDiv': {'sfsi_hideRating'}, 'sfsi_banner_global_firsttime_offer': {'sfsi_banner_global_firsttime_offer'}, 'sfsi_options_updater6': {'updateSrcn6'}, 'sfsi_banner_global_social': {'sfsi_banner_global_social'}, 'update_sharing_settings': {'update_sharing_settings'}, 'sfsi_Iamdone': {'Iamdone'}, 'sfsi_OfflineChatMessage': {'sfsiOfflineChatMessage'}, 'sfsi_banner_global_gdpr': {'sfsi_banner_global_gdpr'}, 'sfsi_options_updater7': {'updateSrcn7'}, 'sfsi_installDate': {'sfsi_installDate'}, 'sfsi_dismiss_lang_notice': {'sfsi_dismiss_lang_notice'}, 'sfsi_DeleteSkin': {'DeleteSkin'}, 'notification_read': {'notification_read'}, 'sfsi_banner_global_pinterest': {'sfsi_banner_global_pinterest'}, 'sfsi_get_icon_preview_callback': {'sfsi_get_icon_preview'}, 'sfsi_banner_global_http': {'sfsi_banner_global_http'}, 'new_notification_read': {'new_notification_read'}, 'sfsi_options_updater9': {'updateSrcn9'}, 'sfsi_options_updater8': {'updateSrcn8'}, 'sfsiActivateFooter': {'activateFooter'}, 'nonce': {'tifm_save_decision'}, 'sfsi_bannerOption': {'bannerOption'}, 'sfsi_deleteIcons': {'deleteIcons'}, 'sfsi_dismiss_addthhis_removal_notice': {'sfsi_dismiss_addThis_icon_notice'}, 'sfsiremoveFooter': {'removeFooter'}, 'sfsi_banner_global_shares': {'sfsi_banner_global_shares'}, 'handle_installation': {'inisev_installation', 'inisev_installation_widget'}, 'sfsi_get_feed_id': {'sfsi_get_feed_id'}, 'sfsi_options_updater2': {'updateSrcn2'}, 'sfsi_options_updater4': {'updateSrcn4'}, 'sfsi_options_updater1': {'updateSrcn1'}, 'sfsi_dismiss_error_reporting_notice': {'sfsi_dismiss_error_reporting_notice'}, 'sfsi_default_hide_admin_notification_callback': {'sfsi_default_hide_admin_notification', 'nopriv_sfsi_default_hide_admin_notification'}}
*
***/

/** Function sfsi_currentDate() called by wp_ajax hooks: {'sfsi_currentDate'} **/
/** Parameters found in function sfsi_currentDate(): {"post": ["nonce", "sfsi_currentDate"]} **/
function sfsi_currentDate() {
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'sfsi_currentDate')) return wp_send_json_error();
    if (!current_user_can('manage_options')) return wp_send_json_error();
    
    $sfsi_currentDate_value = isset( $_POST["sfsi_currentDate"] ) ? sanitize_text_field($_POST["sfsi_currentDate"]) : '';
    update_option( 'sfsi_currentDate', $sfsi_currentDate_value );
    echo json_encode( array( "success" ) );
    exit;
}


/** Function sfsi_cycleDate() called by wp_ajax hooks: {'sfsi_cycleDate'} **/
/** Parameters found in function sfsi_cycleDate(): {"post": ["nonce", "sfsi_cycleDate"]} **/
function sfsi_cycleDate() {
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'sfsi_cycleDate')) return wp_send_json_error();
    if (!current_user_can('manage_options')) return wp_send_json_error();
    
    $sfsi_cycleDate_value = isset( $_POST["sfsi_cycleDate"] ) ? sanitize_text_field($_POST["sfsi_cycleDate"]) : '';
    update_option( 'sfsi_cycleDate',  $sfsi_cycleDate_value );
    echo json_encode( array( "success" ) );
    exit;
}


/** Function sfsi_hide_admin_forum_notification_callback() called by wp_ajax hooks: {'sfsi_hide_admin_forum_notification'} **/
/** Parameters found in function sfsi_hide_admin_forum_notification_callback(): {"post": ["nonce"]} **/
function sfsi_hide_admin_forum_notification_callback() {
  
  if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'usm_universal_nonce_icons')) return wp_send_json_error();
  if (!current_user_can('manage_options')) return wp_send_json_error();
  
	$option_name = 'sfsi_hide_admin_forum_notification' ;
	$new_value = 'yes';

	if ( get_option( $option_name ) !== false ) {
		update_option( $option_name, $new_value );
	} else {
		$deprecated = null;
		$autoload = 'no';
		add_option( $option_name, $new_value, $deprecated, $autoload );
	}
  
	wp_send_json_success();
	die;
  
}


/** Function sfsi_loyaltyDate() called by wp_ajax hooks: {'sfsi_loyaltyDate'} **/
/** Parameters found in function sfsi_loyaltyDate(): {"post": ["nonce", "sfsi_loyaltyDate"]} **/
function sfsi_loyaltyDate() {
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'sfsi_loyaltyDate')) return wp_send_json_error();
    if (!current_user_can('manage_options')) return wp_send_json_error();
    
    $sfsi_loyaltyDate_value   = isset( $_POST["sfsi_loyaltyDate"] ) ? sanitize_text_field($_POST["sfsi_loyaltyDate"]) : '';
    update_option( 'sfsi_loyaltyDate', $sfsi_loyaltyDate_value );
    echo json_encode( array( "success" ) );
    exit;
}


/** Function sfsi_showNextBannerDate() called by wp_ajax hooks: {'sfsi_showNextBannerDate'} **/
/** Parameters found in function sfsi_showNextBannerDate(): {"post": ["nonce", "sfsi_showNextBannerDate"]} **/
function sfsi_showNextBannerDate() {
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'sfsi_showNextBannerDate')) return wp_send_json_error();
    if (!current_user_can('manage_options')) return wp_send_json_error();
    
    $sfsi_showNextBannerDate_value = isset( $_POST["sfsi_showNextBannerDate"] ) ? sanitize_text_field($_POST["sfsi_showNextBannerDate"]) : '';
    update_option( 'sfsi_showNextBannerDate', $sfsi_showNextBannerDate_value );
    echo json_encode( array( "success" ) );
    exit;
}


/** Function sfsi_options_updater5() called by wp_ajax hooks: {'updateSrcn5'} **/
/** Parameters found in function sfsi_options_updater5(): {"post": ["nonce", "sfsi_icons_size", "sfsi_icons_spacing", "sfsi_icons_Alignment", "sfsi_icons_Alignment_via_widget", "sfsi_icons_Alignment_via_shortcode", "sfsi_icons_perRow", "sfsi_icons_ClickPageOpen", "sfsi_icons_AddNoopener", "sfsi_icons_suppress_errors", "sfsi_icons_stick", "sfsi_rss_MouseOverText", "sfsi_email_MouseOverText", "sfsi_twitter_MouseOverText", "sfsi_facebook_MouseOverText", "sfsi_linkedIn_MouseOverText", "sfsi_pinterest_MouseOverText", "sfsi_instagram_MouseOverText", "sfsi_telegram_MouseOverText", "sfsi_vk_MouseOverText", "sfsi_threads_MouseOverText", "sfsi_bluesky_MouseOverText", "sfsi_ok_MouseOverText", "sfsi_weibo_MouseOverText", "sfsi_wechat_MouseOverText", "sfsi_whatsapp_MouseOverText", "sfsi_reddit_MouseOverText", "sfsi_snapchat_MouseOverText", "sfsi_fbmessenger_MouseOverText", "sfsi_ria_MouseOverText", "sfsi_inha_MouseOverText", "sfsi_tiktok_MouseOverText", "sfsi_mastodon_MouseOverText", "sfsi_copylink_MouseOverText", "sfsi_youtube_MouseOverText", "sfsi_custom_orders", "sfsi_rssIcon_order", "sfsi_emailIcon_order", "sfsi_facebookIcon_order", "sfsi_twitterIcon_order", "sfsi_youtubeIcon_order", "sfsi_pinterestIcon_order", "sfsi_instagramIcon_order", "sfsi_telegramIcon_order", "sfsi_vkIcon_order", "sfsi_blueskyIcon_order", "sfsi_threadsIcon_order", "sfsi_okIcon_order", "sfsi_weiboIcon_order", "sfsi_wechatIcon_order", "sfsi_linkedinIcon_order", "sfsi_snapchatIcon_order", "sfsi_redditIcon_order", "sfsi_fbmessengerIcon_order", "sfsi_riaIcon_order", "sfsi_inhaIcon_order", "sfsi_tiktokIcon_order", "sfsi_copylinkIcon_order", "sfsi_mastodonIcon_order", "sfsi_custom_MouseOverTexts", "sfsi_custom_social_hide", "sfsi_show_admin_popup", "sfsi_icons_sharing_and_traffic_tips", "sfsi_whatsappIcon_order", "sfsi_icons_language", "sfsi_follow_icons_language", "sfsi_facebook_icons_language", "sfsi_youtube_icons_language", "sfsi_twitter_icons_language", "sfsi_linkedin_icons_language"]} **/
function sfsi_options_updater5()
{
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), "update_step5")) {
        echo json_encode(array("wrong_nonce"));
        exit;
    }
    if (!current_user_can('manage_options')) {
        echo json_encode(array('res' => 'not allowed'));
        die();
    }

    $sfsi_icons_size                = isset( $_POST["sfsi_icons_size"] ) ? sanitize_text_field($_POST["sfsi_icons_size"] ) : '51';
    $sfsi_icons_spacing             = isset( $_POST["sfsi_icons_spacing"] ) ? sanitize_text_field($_POST["sfsi_icons_spacing"] ) : '2';
    $sfsi_icons_Alignment           = isset( $_POST["sfsi_icons_Alignment"] ) ? sanitize_text_field($_POST["sfsi_icons_Alignment"] ) : 'center';
    $sfsi_icons_Alignment_via_widget     = isset( $_POST["sfsi_icons_Alignment_via_widget"] ) ? sanitize_text_field($_POST["sfsi_icons_Alignment_via_widget"] ) : 'center';
    $sfsi_icons_Alignment_via_shortcode  = isset( $_POST["sfsi_icons_Alignment_via_shortcode"] ) ? sanitize_text_field($_POST["sfsi_icons_Alignment_via_shortcode"] ) : 'center';

    $sfsi_icons_perRow              = isset( $_POST["sfsi_icons_perRow"] ) ? sanitize_text_field($_POST["sfsi_icons_perRow"] ) : '5';
    $sfsi_icons_ClickPageOpen       = isset( $_POST["sfsi_icons_ClickPageOpen"] ) ? sanitize_text_field($_POST["sfsi_icons_ClickPageOpen"] ) : 'no';
    $sfsi_icons_AddNoopener       = isset( $_POST["sfsi_icons_AddNoopener"] ) ? sanitize_text_field($_POST["sfsi_icons_AddNoopener"] ) : 'no';
    $sfsi_icons_suppress_errors     = isset( $_POST["sfsi_icons_suppress_errors"] ) ? sanitize_text_field($_POST["sfsi_icons_suppress_errors"] ) : 'no';
    $sfsi_icons_stick               = isset( $_POST["sfsi_icons_stick"] ) ? sanitize_text_field($_POST["sfsi_icons_stick"] ) : 'no';

    $sfsi_rss_MouseOverText         = isset( $_POST["sfsi_rss_MouseOverText"] ) ? sanitize_text_field($_POST["sfsi_rss_MouseOverText"] ) : '';
    $sfsi_email_MouseOverText       = isset( $_POST["sfsi_email_MouseOverText"] ) ? sanitize_text_field($_POST["sfsi_email_MouseOverText"] ) : '';
    $sfsi_twitter_MouseOverText     = isset( $_POST["sfsi_twitter_MouseOverText"] ) ? sanitize_text_field($_POST["sfsi_twitter_MouseOverText"] ) : '';
    $sfsi_facebook_MouseOverText    = isset( $_POST["sfsi_facebook_MouseOverText"] ) ? sanitize_text_field($_POST["sfsi_facebook_MouseOverText"] ) : '';
    $sfsi_linkedIn_MouseOverText    = isset( $_POST["sfsi_linkedIn_MouseOverText"] ) ? sanitize_text_field($_POST["sfsi_linkedIn_MouseOverText"] ) : '';
    $sfsi_pinterest_MouseOverText   = isset( $_POST["sfsi_pinterest_MouseOverText"] ) ? sanitize_text_field($_POST["sfsi_pinterest_MouseOverText"] ) : '';
    $sfsi_instagram_MouseOverText   = isset( $_POST["sfsi_instagram_MouseOverText"] ) ? sanitize_text_field($_POST["sfsi_instagram_MouseOverText"] ) : '';
    $sfsi_telegram_MouseOverText    = isset( $_POST["sfsi_telegram_MouseOverText"] ) ? sanitize_text_field($_POST["sfsi_telegram_MouseOverText"] ) : '';
    $sfsi_vk_MouseOverText          = isset( $_POST["sfsi_vk_MouseOverText"] ) ? sanitize_text_field($_POST["sfsi_vk_MouseOverText"] ) : '';
    $sfsi_threads_MouseOverText          = isset( $_POST["sfsi_threads_MouseOverText"] ) ? sanitize_text_field($_POST["sfsi_threads_MouseOverText"] ) : '';
    $sfsi_bluesky_MouseOverText          = isset( $_POST["sfsi_bluesky_MouseOverText"] ) ? sanitize_text_field($_POST["sfsi_bluesky_MouseOverText"] ) : '';
    $sfsi_ok_MouseOverText          = isset( $_POST["sfsi_ok_MouseOverText"] ) ? sanitize_text_field($_POST["sfsi_ok_MouseOverText"] ) : '';
    $sfsi_weibo_MouseOverText       = isset( $_POST["sfsi_weibo_MouseOverText"] ) ? sanitize_text_field($_POST["sfsi_weibo_MouseOverText"] ) : '';
    $sfsi_wechat_MouseOverText      = isset( $_POST["sfsi_wechat_MouseOverText"] ) ? sanitize_text_field($_POST["sfsi_wechat_MouseOverText"] ) : '';
    $sfsi_whatsapp_MouseOverText      = isset( $_POST["sfsi_whatsapp_MouseOverText"] ) ? sanitize_text_field($_POST["sfsi_whatsapp_MouseOverText"] ) : '';

    $sfsi_reddit_MouseOverText      = isset( $_POST["sfsi_reddit_MouseOverText"] ) ? sanitize_text_field($_POST["sfsi_reddit_MouseOverText"] ) : '';
    $sfsi_snapchat_MouseOverText      = isset( $_POST["sfsi_snapchat_MouseOverText"] ) ? sanitize_text_field($_POST["sfsi_snapchat_MouseOverText"] ) : '';
    $sfsi_fbmessenger_MouseOverText      = isset( $_POST["sfsi_fbmessenger_MouseOverText"] ) ? sanitize_text_field($_POST["sfsi_fbmessenger_MouseOverText"] ) : '';
    $sfsi_ria_MouseOverText      = isset( $_POST["sfsi_ria_MouseOverText"] ) ? sanitize_text_field($_POST["sfsi_ria_MouseOverText"] ) : '';
    $sfsi_inha_MouseOverText      = isset( $_POST["sfsi_inha_MouseOverText"] ) ? sanitize_text_field($_POST["sfsi_inha_MouseOverText"] ) : '';
    $sfsi_tiktok_MouseOverText      = isset( $_POST["sfsi_tiktok_MouseOverText"] ) ? sanitize_text_field($_POST["sfsi_tiktok_MouseOverText"] ) : '';
    $sfsi_mastodon_MouseOverText      = isset( $_POST["sfsi_mastodon_MouseOverText"] ) ? sanitize_text_field($_POST["sfsi_mastodon_MouseOverText"] ) : '';
    $sfsi_copylink_MouseOverText      = isset( $_POST["sfsi_copylink_MouseOverText"] ) ? sanitize_text_field($_POST["sfsi_copylink_MouseOverText"] ) : '';

    $sfsi_youtube_MouseOverText     = isset( $_POST["sfsi_youtube_MouseOverText"] ) ? sanitize_text_field($_POST["sfsi_youtube_MouseOverText"] ) : '';
    if (isset( $_POST["sfsi_custom_orders"] )) {
        $sfsi_custom_orders = array();
        foreach ($_POST["sfsi_custom_orders"] as $index => $custom_order) {
            $index = sanitize_text_field($index);
            $sfsi_custom_orders[$index] = array();
            $sfsi_custom_orders[$index]["order"] = intval($_POST["sfsi_custom_orders"][$index]["order"] );
            $sfsi_custom_orders[$index]["ele"] = intval($_POST["sfsi_custom_orders"][$index]["ele"] );
        }
    }

    $sfsi_custom_orders             = isset( $_POST["sfsi_custom_orders"] ) ? serialize($sfsi_custom_orders) : '';

    $sfsi_rssIcon_order             = isset( $_POST["sfsi_rssIcon_order"] ) ? sanitize_text_field($_POST["sfsi_rssIcon_order"] ) : '1';
    $sfsi_emailIcon_order           = isset( $_POST["sfsi_emailIcon_order"] ) ? sanitize_text_field($_POST["sfsi_emailIcon_order"] ) : '2';
    $sfsi_facebookIcon_order        = isset( $_POST["sfsi_facebookIcon_order"] ) ? sanitize_text_field($_POST["sfsi_facebookIcon_order"] ) : '3';
    $sfsi_twitterIcon_order         = isset( $_POST["sfsi_twitterIcon_order"] ) ? sanitize_text_field($_POST["sfsi_twitterIcon_order"] ) : '5';
    $sfsi_youtubeIcon_order         = isset( $_POST["sfsi_youtubeIcon_order"] ) ? sanitize_text_field($_POST["sfsi_youtubeIcon_order"] ) : '7';
    $sfsi_pinterestIcon_order       = isset( $_POST["sfsi_pinterestIcon_order"] ) ? sanitize_text_field($_POST["sfsi_pinterestIcon_order"] ) : '8';
    $sfsi_instagramIcon_order       = isset( $_POST["sfsi_instagramIcon_order"] ) ? sanitize_text_field($_POST["sfsi_instagramIcon_order"] ) : '10';
    $sfsi_telegramIcon_order        = isset( $_POST["sfsi_telegramIcon_order"] ) ? sanitize_text_field($_POST["sfsi_telegramIcon_order"] ) : '11';
    $sfsi_vkIcon_order              = isset( $_POST["sfsi_vkIcon_order"] ) ? sanitize_text_field($_POST["sfsi_vkIcon_order"] ) : '12';
    $sfsi_blueskyIcon_order              = isset( $_POST["sfsi_blueskyIcon_order"] ) ? sanitize_text_field($_POST["sfsi_blueskyIcon_order"] ) : '31';
    $sfsi_threadsIcon_order              = isset( $_POST["sfsi_threadsIcon_order"] ) ? sanitize_text_field($_POST["sfsi_threadsIcon_order"] ) : '30';
    $sfsi_okIcon_order              = isset( $_POST["sfsi_okIcon_order"] ) ? sanitize_text_field($_POST["sfsi_okIcon_order"] ) : '13';
    $sfsi_weiboIcon_order           = isset( $_POST["sfsi_weiboIcon_order"] ) ? sanitize_text_field($_POST["sfsi_weiboIcon_order"] ) : '14';
    $sfsi_wechatIcon_order          = isset( $_POST["sfsi_wechatIcon_order"] ) ? sanitize_text_field($_POST["sfsi_wechatIcon_order"] ) : '15';

    $sfsi_linkedinIcon_order        = isset( $_POST["sfsi_linkedinIcon_order"] ) ? sanitize_text_field($_POST["sfsi_linkedinIcon_order"] ) : '9';

    $sfsi_snapchatIcon_order        = isset( $_POST["sfsi_snapchatIcon_order"] ) ? sanitize_text_field($_POST["sfsi_snapchatIcon_order"] ) : '17';
    $sfsi_redditIcon_order          = isset( $_POST["sfsi_redditIcon_order"] ) ? sanitize_text_field($_POST["sfsi_redditIcon_order"] ) : '18';
    $sfsi_fbmessengerIcon_order     = isset( $_POST["sfsi_fbmessengerIcon_order"] ) ? sanitize_text_field($_POST["sfsi_fbmessengerIcon_order"] ) : '19';
    $sfsi_riaIcon_order     = isset( $_POST["sfsi_riaIcon_order"] ) ? sanitize_text_field($_POST["sfsi_riaIcon_order"] ) : '22';
    $sfsi_inhaIcon_order     = isset( $_POST["sfsi_inhaIcon_order"] ) ? sanitize_text_field($_POST["sfsi_inhaIcon_order"] ) : '23';
    $sfsi_tiktokIcon_order          = isset( $_POST["sfsi_tiktokIcon_order"] ) ? sanitize_text_field($_POST["sfsi_tiktokIcon_order"] ) : '20';
	$sfsi_copylinkIcon_order        = isset( $_POST["sfsi_copylinkIcon_order"] ) ? intval( $_POST["sfsi_copylinkIcon_order"] ) : '30';
    $sfsi_mastodonIcon_order        = isset( $_POST["sfsi_mastodonIcon_order"] ) ? sanitize_text_field($_POST["sfsi_mastodonIcon_order"] ) : '21';

    if (isset( $_POST["sfsi_custom_MouseOverTexts"] )) {
        $sfsi_custom_MouseOverTexts = array();
        foreach ($_POST['sfsi_custom_MouseOverTexts'] as $index => $sfsi_custom_MouseOverText) {
            $index = sanitize_text_field($index);
            $sfsi_custom_MouseOverTexts[$index] = sanitize_text_field($_POST["sfsi_custom_MouseOverTexts"][$index] );
        }
    }
    $sfsi_custom_MouseOverTexts     = isset( $sfsi_custom_MouseOverTexts) ? serialize($sfsi_custom_MouseOverTexts) : '';

    $sfsi_custom_social_hide        = isset( $_POST["sfsi_custom_social_hide"] ) ? sanitize_text_field($_POST["sfsi_custom_social_hide"] ) : 'no';
    $sfsi_show_admin_popup         = isset( $_POST["sfsi_show_admin_popup"] ) ? sanitize_text_field($_POST["sfsi_show_admin_popup"] ) : 'yes';
    $sfsi_icons_sharing_and_traffic_tips     = isset( $_POST["sfsi_icons_sharing_and_traffic_tips"] ) ? sanitize_text_field($_POST["sfsi_icons_sharing_and_traffic_tips"] ) : 'yes';
    $sfsi_whatsappIcon_order        = isset( $_POST["sfsi_whatsappIcon_order"] ) ? sanitize_text_field($_POST["sfsi_whatsappIcon_order"] ) : '16';

    $sfsi_icons_language            = isset( $_POST["sfsi_icons_language"] ) && sfsi_verify_language_values($_POST["sfsi_icons_language"]) ?  esc_js(sanitize_text_field( $_POST["sfsi_icons_language"] )) : 'en_US';
    $sfsi_follow_icons_language     = isset( $_POST["sfsi_follow_icons_language"] ) && sfsi_verify_language_values($_POST["sfsi_follow_icons_language"]) ? esc_js(sanitize_text_field( $_POST["sfsi_follow_icons_language"] )) : 'Follow_en_US';
    $sfsi_facebook_icons_language   = isset( $_POST["sfsi_facebook_icons_language"] ) && sfsi_verify_language_values($_POST["sfsi_facebook_icons_language"]) ? esc_js(sanitize_text_field( $_POST["sfsi_facebook_icons_language"] )) : 'Visit_us_en_US';
    $sfsi_youtube_icons_language    = isset( $_POST["sfsi_youtube_icons_language"] ) && sfsi_verify_language_values($_POST["sfsi_youtube_icons_language"]) ? esc_js(sanitize_text_field( $_POST["sfsi_youtube_icons_language"] )) : 'Visit_us_en_US';
    $sfsi_twitter_icons_language    = isset( $_POST["sfsi_twitter_icons_language"] ) && sfsi_verify_language_values($_POST["sfsi_twitter_icons_language"]) ? esc_js(sanitize_text_field( $_POST["sfsi_twitter_icons_language"] )) : 'Visit_us_en_US';
    $sfsi_linkedin_icons_language   = isset( $_POST["sfsi_linkedin_icons_language"] ) && sfsi_verify_language_values($_POST["sfsi_linkedin_icons_language"]) ? esc_js(sanitize_text_field( $_POST["sfsi_linkedin_icons_language"] )) : 'en_US';

    /* size and spacing of icons */
    $up_option5 = array(
        'sfsi_icons_size'               => intval($sfsi_icons_size),
        'sfsi_icons_spacing'            => intval($sfsi_icons_spacing),
        'sfsi_icons_Alignment'          => sanitize_text_field($sfsi_icons_Alignment),
        'sfsi_icons_Alignment_via_widget'          => sanitize_text_field($sfsi_icons_Alignment_via_widget),
        'sfsi_icons_Alignment_via_shortcode'          => sanitize_text_field($sfsi_icons_Alignment_via_shortcode),
        'sfsi_icons_perRow'             => intval($sfsi_icons_perRow),
        'sfsi_icons_ClickPageOpen'      => sanitize_text_field($sfsi_icons_ClickPageOpen),
        'sfsi_icons_AddNoopener'      => sanitize_text_field($sfsi_icons_AddNoopener),
        'sfsi_icons_suppress_errors'    => sanitize_text_field($sfsi_icons_suppress_errors),

        'sfsi_follow_icons_language'    => sanitize_text_field( $sfsi_follow_icons_language ),
        'sfsi_facebook_icons_language'  => sanitize_text_field( $sfsi_facebook_icons_language ),
        'sfsi_youtube_icons_language'   => sanitize_text_field( $sfsi_youtube_icons_language ),
        'sfsi_twitter_icons_language'   => sanitize_text_field( $sfsi_twitter_icons_language ),
        'sfsi_linkedin_icons_language'  => sanitize_text_field( $sfsi_linkedin_icons_language ),
        'sfsi_icons_language'           => sanitize_text_field( $sfsi_icons_language ),

        'sfsi_icons_stick'              => sanitize_text_field($sfsi_icons_stick),
        /* mouse over texts */
        'sfsi_rss_MouseOverText'        => sanitize_text_field($sfsi_rss_MouseOverText),
        'sfsi_email_MouseOverText'      => sanitize_text_field($sfsi_email_MouseOverText),
        'sfsi_twitter_MouseOverText'    => sanitize_text_field($sfsi_twitter_MouseOverText),
        'sfsi_facebook_MouseOverText'   => sanitize_text_field($sfsi_facebook_MouseOverText),
        'sfsi_linkedIn_MouseOverText'   => sanitize_text_field($sfsi_linkedIn_MouseOverText),
        'sfsi_pinterest_MouseOverText'  => sanitize_text_field($sfsi_pinterest_MouseOverText),
        'sfsi_youtube_MouseOverText'    => sanitize_text_field($sfsi_youtube_MouseOverText),
        'sfsi_instagram_MouseOverText'  => sanitize_text_field($sfsi_instagram_MouseOverText),
        'sfsi_telegram_MouseOverText'   => sanitize_text_field($sfsi_telegram_MouseOverText),
        'sfsi_vk_MouseOverText'         => sanitize_text_field($sfsi_vk_MouseOverText),
        'sfsi_threads_MouseOverText'         => sanitize_text_field($sfsi_threads_MouseOverText),
        'sfsi_bluesky_MouseOverText'         => sanitize_text_field($sfsi_bluesky_MouseOverText),
        'sfsi_ok_MouseOverText'         => sanitize_text_field($sfsi_ok_MouseOverText),
        'sfsi_weibo_MouseOverText'      => sanitize_text_field($sfsi_weibo_MouseOverText),
        'sfsi_wechat_MouseOverText'     => sanitize_text_field($sfsi_wechat_MouseOverText),
        'sfsi_whatsapp_MouseOverText'     => sanitize_text_field($sfsi_whatsapp_MouseOverText),
        'sfsi_snapchat_MouseOverText'     => sanitize_text_field($sfsi_snapchat_MouseOverText),
        'sfsi_fbmessenger_MouseOverText'     => sanitize_text_field($sfsi_fbmessenger_MouseOverText),
        'sfsi_ria_MouseOverText'     => sanitize_text_field($sfsi_ria_MouseOverText),
        'sfsi_inha_MouseOverText'     => sanitize_text_field($sfsi_inha_MouseOverText),
        'sfsi_tiktok_MouseOverText'     => sanitize_text_field($sfsi_tiktok_MouseOverText),
        'sfsi_mastodon_MouseOverText'     => sanitize_text_field($sfsi_mastodon_MouseOverText),
        'sfsi_copylink_MouseOverText'     => sanitize_text_field($sfsi_copylink_MouseOverText),
        'sfsi_reddit_MouseOverText'     => sanitize_text_field($sfsi_reddit_MouseOverText),
        'sfsi_CustomIcons_order'        => $sfsi_custom_orders,
        'sfsi_rssIcon_order'            => intval($sfsi_rssIcon_order),
        'sfsi_emailIcon_order'          => intval($sfsi_emailIcon_order),
        'sfsi_facebookIcon_order'       => intval($sfsi_facebookIcon_order),
        'sfsi_twitterIcon_order'        => intval($sfsi_twitterIcon_order),
        'sfsi_youtubeIcon_order'        => intval($sfsi_youtubeIcon_order),
        'sfsi_pinterestIcon_order'      => intval($sfsi_pinterestIcon_order),
        'sfsi_instagramIcon_order'      => intval($sfsi_instagramIcon_order),
        'sfsi_linkedinIcon_order'       => intval($sfsi_linkedinIcon_order),
        'sfsi_telegramIcon_order'       => intval($sfsi_telegramIcon_order),
        'sfsi_vkIcon_order'             => intval($sfsi_vkIcon_order),
        'sfsi_threadsIcon_order'             => intval($sfsi_threadsIcon_order),
        'sfsi_blueskyIcon_order'             => intval($sfsi_blueskyIcon_order),
        'sfsi_okIcon_order'             => intval($sfsi_okIcon_order),
        'sfsi_weiboIcon_order'          => intval($sfsi_weiboIcon_order),
        'sfsi_wechatIcon_order'         => intval($sfsi_wechatIcon_order),

        'sfsi_snapchatIcon_order'       => intval($sfsi_snapchatIcon_order),
        'sfsi_redditIcon_order'         => intval($sfsi_redditIcon_order),
        'sfsi_fbmessengerIcon_order'    => intval($sfsi_fbmessengerIcon_order),
        'sfsi_riaIcon_order'    => intval($sfsi_riaIcon_order),
        'sfsi_inhaIcon_order'    => intval($sfsi_inhaIcon_order),
        'sfsi_tiktokIcon_order'         => intval($sfsi_tiktokIcon_order),
		'sfsi_copylinkIcon_order'		=> intval($sfsi_copylinkIcon_order),
        'sfsi_mastodonIcon_order'       => intval($sfsi_mastodonIcon_order),

        'sfsi_custom_MouseOverTexts'    => $sfsi_custom_MouseOverTexts,
        'sfsi_custom_social_hide'       => $sfsi_custom_social_hide,
        'sfsi_show_admin_popup'         => sanitize_text_field($sfsi_show_admin_popup),
        'sfsi_icons_sharing_and_traffic_tips'    => sanitize_text_field($sfsi_icons_sharing_and_traffic_tips),
        'sfsi_whatsappIcon_order'         => intval($sfsi_whatsappIcon_order),
    );

    if ("yes" == $sfsi_icons_suppress_errors) {
        update_option('sfsi_error_reporting_notice_dismissed', false);
    }
    update_option('sfsi_section5_options',  serialize($up_option5));
    header('Content-Type: application/json');
    echo json_encode(array("success"));
    exit;
}


/** Function sfsi_save_export() called by wp_ajax hooks: {'sfsi_save_export'} **/
/** No params detected :-/ **/


/** Function sfsi_options_updater3() called by wp_ajax hooks: {'updateSrcn3'} **/
/** Parameters found in function sfsi_options_updater3(): {"post": ["nonce", "sfsi_actvite_theme", "sfsi_mouseOver", "sfsi_mouseOver_effect", "sfsi_mouseover_effect_type", "sfsi_shuffle_icons", "sfsi_shuffle_Firstload", "sfsi_shuffle_interval", "sfsi_shuffle_intervalTime", "sfsi_specialIcon_animation", "sfsi_specialIcon_MouseOver", "sfsi_specialIcon_Firstload", "sfsi_specialIcon_Firstload_Icons", "sfsi_specialIcon_interval", "sfsi_specialIcon_intervalTime", "sfsi_specialIcon_intervalIcons", "sfsi_rss_bgColor", "sfsi_email_bgColor", "sfsi_facebook_bgColor", "sfsi_twitter_bgColor", "sfsi_youtube_bgColor", "sfsi_pinterest_bgColor", "sfsi_linkedin_bgColor", "sfsi_instagram_bgColor", "sfsi_threads_bgColor", "sfsi_bluesky_bgColor", "sfsi_ria_bgColor", "sfsi_inha_bgColor", "sfsi_snapchat_bgColor", "sfsi_whatsapp_bgColor", "sfsi_reddit_bgColor", "sfsi_fbmessenger_bgColor", "sfsi_ok_bgColor", "sfsi_telegram_bgColor", "sfsi_vk_bgColor", "sfsi_wechat_bgColor", "sfsi_weibo_bgColor", "sfsi_tiktok_bgColor", "sfsi_copylink_bgColor", "sfsi_mastodon_bgColor"]} **/
function sfsi_options_updater3()
{
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), "update_step3")) {
        echo json_encode(array("wrong_nonce"));
        exit;
    }
    if (!current_user_can('manage_options')) {
        echo json_encode(array('res' => 'not allowed'));
        die();
    }

    $sfsi_actvite_theme             = isset( $_POST["sfsi_actvite_theme"] ) ? sanitize_text_field($_POST["sfsi_actvite_theme"] ) : 'default';
    $sfsi_mouseOver                 = isset( $_POST["sfsi_mouseOver"] ) ? sanitize_text_field($_POST["sfsi_mouseOver"] ) : 'no';
    $sfsi_mouseOver_effect          = isset( $_POST["sfsi_mouseOver_effect"] ) ? sanitize_text_field($_POST["sfsi_mouseOver_effect"] ) : 'fade_in';
    $sfsi_mouseover_effect_type     = isset( $_POST["sfsi_mouseover_effect_type"] ) ? sanitize_text_field($_POST["sfsi_mouseover_effect_type"] ) : 'same_icons';
    $sfsi_shuffle_icons             = isset( $_POST["sfsi_shuffle_icons"] ) ? sanitize_text_field($_POST["sfsi_shuffle_icons"] ) : 'no';
    $sfsi_shuffle_Firstload         = isset( $_POST["sfsi_shuffle_Firstload"] ) ? sanitize_text_field($_POST["sfsi_shuffle_Firstload"] ) : 'no';
    $sfsi_shuffle_interval          = isset( $_POST["sfsi_shuffle_interval"] ) ? intval($_POST["sfsi_shuffle_interval"] ) : 'no';
    $sfsi_shuffle_intervalTime      = isset( $_POST["sfsi_shuffle_intervalTime"] ) ? sanitize_text_field($_POST["sfsi_shuffle_intervalTime"] ) : '';
    $sfsi_specialIcon_animation     = isset( $_POST["sfsi_specialIcon_animation"] ) ? sanitize_text_field($_POST["sfsi_specialIcon_animation"] ) : '';
    $sfsi_specialIcon_MouseOver     = isset( $_POST["sfsi_specialIcon_MouseOver"] ) ? sanitize_text_field($_POST["sfsi_specialIcon_MouseOver"] ) : 'no';
    $sfsi_specialIcon_Firstload     = isset( $_POST["sfsi_specialIcon_Firstload"] ) ? sanitize_text_field($_POST["sfsi_specialIcon_Firstload"] ) : 'no';
    $sfsi_specialIcon_Firstload_Icons = isset( $_POST["sfsi_specialIcon_Firstload_Icons"] ) ? sanitize_text_field($_POST["sfsi_specialIcon_Firstload_Icons"] ) : 'all';
    $sfsi_specialIcon_interval      = isset( $_POST["sfsi_specialIcon_interval"] ) ? sanitize_text_field($_POST["sfsi_specialIcon_interval"] ) : 'no';
    $sfsi_specialIcon_intervalTime  = isset( $_POST["sfsi_specialIcon_intervalTime"] ) ? sanitize_text_field($_POST["sfsi_specialIcon_intervalTime"] ) : '';
    $sfsi_specialIcon_intervalIcons = isset( $_POST["sfsi_specialIcon_intervalIcons"] ) ? sanitize_text_field($_POST["sfsi_specialIcon_intervalIcons"] ) : 'all';


    /* Flat color settings */
	$sfsi_rss_bgColor         = isset( $_POST["sfsi_rss_bgColor"] ) ? sanitize_text_field( $_POST["sfsi_rss_bgColor"] ) : '';
	$sfsi_email_bgColor       = isset( $_POST["sfsi_email_bgColor"] ) ? sanitize_text_field( $_POST["sfsi_email_bgColor"] ) : '';
	$sfsi_facebook_bgColor    = isset( $_POST["sfsi_facebook_bgColor"] ) ? sanitize_text_field( $_POST["sfsi_facebook_bgColor"] ) : '';
	$sfsi_twitter_bgColor     = isset( $_POST["sfsi_twitter_bgColor"] ) ? sanitize_text_field( $_POST["sfsi_twitter_bgColor"] ) : '';
	$sfsi_youtube_bgColor     = isset( $_POST["sfsi_youtube_bgColor"] ) ? sanitize_text_field( $_POST["sfsi_youtube_bgColor"] ) : '';
	$sfsi_pinterest_bgColor   = isset( $_POST["sfsi_pinterest_bgColor"] ) ? sanitize_text_field( $_POST["sfsi_pinterest_bgColor"] ) : '';
	$sfsi_linkedin_bgColor    = isset( $_POST["sfsi_linkedin_bgColor"] ) ? sanitize_text_field( $_POST["sfsi_linkedin_bgColor"] ) : '';
	$sfsi_instagram_bgColor   = isset( $_POST["sfsi_instagram_bgColor"] ) ? sanitize_text_field( $_POST["sfsi_instagram_bgColor"] ) : '';
	$sfsi_threads_bgColor     = isset( $_POST["sfsi_threads_bgColor"] ) ? sanitize_text_field( $_POST["sfsi_threads_bgColor"] ) : '';
	$sfsi_bluesky_bgColor     = isset( $_POST["sfsi_bluesky_bgColor"] ) ? sanitize_text_field( $_POST["sfsi_bluesky_bgColor"] ) : '';
	$sfsi_ria_bgColor         = isset( $_POST["sfsi_ria_bgColor"] ) ? sanitize_text_field( $_POST["sfsi_ria_bgColor"] ) : '';
	$sfsi_inha_bgColor        = isset( $_POST["sfsi_inha_bgColor"] ) ? sanitize_text_field( $_POST["sfsi_inha_bgColor"] ) : '';
	$sfsi_snapchat_bgColor    = isset( $_POST["sfsi_snapchat_bgColor"] ) ? sanitize_text_field( $_POST["sfsi_snapchat_bgColor"] ) : '';
	$sfsi_whatsapp_bgColor    = isset( $_POST["sfsi_whatsapp_bgColor"] ) ? sanitize_text_field( $_POST["sfsi_whatsapp_bgColor"] ) : '';
	$sfsi_reddit_bgColor      = isset( $_POST["sfsi_reddit_bgColor"] ) ? sanitize_text_field( $_POST["sfsi_reddit_bgColor"] ) : '';
	$sfsi_fbmessenger_bgColor = isset( $_POST["sfsi_fbmessenger_bgColor"] ) ? sanitize_text_field( $_POST["sfsi_fbmessenger_bgColor"] ) : '';
	$sfsi_ok_bgColor          = isset( $_POST["sfsi_ok_bgColor"] ) ? sanitize_text_field( $_POST["sfsi_ok_bgColor"] ) : '';
	$sfsi_telegram_bgColor    = isset( $_POST["sfsi_telegram_bgColor"] ) ? sanitize_text_field( $_POST["sfsi_telegram_bgColor"] ) : '';
	$sfsi_vk_bgColor          = isset( $_POST["sfsi_vk_bgColor"] ) ? sanitize_text_field( $_POST["sfsi_vk_bgColor"] ) : '';
	$sfsi_wechat_bgColor      = isset( $_POST["sfsi_wechat_bgColor"] ) ? sanitize_text_field( $_POST["sfsi_wechat_bgColor"] ) : '';
	$sfsi_weibo_bgColor       = isset( $_POST["sfsi_weibo_bgColor"] ) ? sanitize_text_field( $_POST["sfsi_weibo_bgColor"] ) : '';
	$sfsi_tiktok_bgColor      = isset( $_POST["sfsi_tiktok_bgColor"] ) ? sanitize_text_field( $_POST["sfsi_tiktok_bgColor"] ) : '';
	$sfsi_copylink_bgColor    = isset( $_POST["sfsi_copylink_bgColor"] ) ? sanitize_text_field( $_POST["sfsi_copylink_bgColor"] ) : '';
	$sfsi_mastodon_bgColor    = isset( $_POST["sfsi_mastodon_bgColor"] ) ? sanitize_text_field( $_POST["sfsi_mastodon_bgColor"] ) : '';

    /* Design and animation option  */
    $up_option3 = array(
        'sfsi_actvite_theme'                => sanitize_text_field($sfsi_actvite_theme),
        /* animations options */
        'sfsi_mouseOver'                    => sanitize_text_field($sfsi_mouseOver),
        'sfsi_mouseOver_effect'             => sanitize_text_field($sfsi_mouseOver_effect),
        'sfsi_mouseover_effect_type'        => sanitize_text_field($sfsi_mouseover_effect_type),
        'sfsi_shuffle_icons'                => sanitize_text_field($sfsi_shuffle_icons),
        'sfsi_shuffle_Firstload'            => sanitize_text_field($sfsi_shuffle_Firstload),
        'sfsi_shuffle_interval'             => sanitize_text_field($sfsi_shuffle_interval),
        'sfsi_shuffle_intervalTime'         => intval($sfsi_shuffle_intervalTime),
        'sfsi_specialIcon_animation'        => sanitize_text_field($sfsi_specialIcon_animation),
        'sfsi_specialIcon_MouseOver'        => sanitize_text_field($sfsi_specialIcon_MouseOver),
        'sfsi_specialIcon_Firstload'        => sanitize_text_field($sfsi_specialIcon_Firstload),
        'sfsi_specialIcon_Firstload_Icons'  => sanitize_text_field($sfsi_specialIcon_Firstload_Icons),
        'sfsi_specialIcon_interval'         => sanitize_text_field($sfsi_specialIcon_interval),
        'sfsi_specialIcon_intervalTime'     => sanitize_text_field($sfsi_specialIcon_intervalTime),
        'sfsi_specialIcon_intervalIcons'    => sanitize_text_field($sfsi_specialIcon_intervalIcons),

        'sfsi_rss_bgColor'         => sanitize_text_field( $sfsi_rss_bgColor ),
        'sfsi_email_bgColor'       => sanitize_text_field( $sfsi_email_bgColor ),
        'sfsi_facebook_bgColor'    => sanitize_text_field( $sfsi_facebook_bgColor ),
        'sfsi_twitter_bgColor'     => sanitize_text_field( $sfsi_twitter_bgColor ),
        'sfsi_youtube_bgColor'     => sanitize_text_field( $sfsi_youtube_bgColor ),
        'sfsi_pinterest_bgColor'   => sanitize_text_field( $sfsi_pinterest_bgColor ),
        'sfsi_linkedin_bgColor'    => sanitize_text_field( $sfsi_linkedin_bgColor ),
        'sfsi_instagram_bgColor'   => sanitize_text_field( $sfsi_instagram_bgColor ),
        'sfsi_threads_bgColor'     => sanitize_text_field( $sfsi_threads_bgColor ),
        'sfsi_bluesky_bgColor'     => sanitize_text_field( $sfsi_bluesky_bgColor ),
        'sfsi_ria_bgColor'         => sanitize_text_field( $sfsi_ria_bgColor ),
        'sfsi_inha_bgColor'        => sanitize_text_field( $sfsi_inha_bgColor ),
        'sfsi_snapchat_bgColor'    => sanitize_text_field( $sfsi_snapchat_bgColor ),
        'sfsi_whatsapp_bgColor'    => sanitize_text_field( $sfsi_whatsapp_bgColor ),
        'sfsi_reddit_bgColor'      => sanitize_text_field( $sfsi_reddit_bgColor ),
        'sfsi_fbmessenger_bgColor' => sanitize_text_field( $sfsi_fbmessenger_bgColor ),
        'sfsi_ok_bgColor'          => sanitize_text_field( $sfsi_ok_bgColor ),
        'sfsi_telegram_bgColor'    => sanitize_text_field( $sfsi_telegram_bgColor ),
        'sfsi_vk_bgColor'          => sanitize_text_field( $sfsi_vk_bgColor ),
        'sfsi_wechat_bgColor'      => sanitize_text_field( $sfsi_wechat_bgColor ),
        'sfsi_weibo_bgColor'       => sanitize_text_field( $sfsi_weibo_bgColor ),
        'sfsi_tiktok_bgColor'      => sanitize_text_field( $sfsi_tiktok_bgColor ),
        'sfsi_copylink_bgColor'    => sanitize_text_field( $sfsi_copylink_bgColor ),
        'sfsi_mastodon_bgColor'    => sanitize_text_field( $sfsi_mastodon_bgColor ),

    );
    update_option('sfsi_section3_options', serialize($up_option3));
    header('Content-Type: application/json');
    echo json_encode(array("success"));
    exit;
}


/** Function activate_plugins() called by wp_ajax hooks: {'analyst_notification_dismiss'} **/
/** No function found :-/ **/


/** Function sfsi_UploadSkins() called by wp_ajax hooks: {'UploadSkins'} **/
/** Parameters found in function sfsi_UploadSkins(): {"post": ["nonce", "custom_imgurl"]} **/
function sfsi_UploadSkins()
{
	if ( !wp_verify_nonce( sanitize_text_field($_POST['nonce']), "UploadSkins")) {
      echo  json_encode(array("wrong_nonce")); exit;
    }
    if(!current_user_can('manage_options')){ echo json_encode(array('res'=>'not allowed'));die(); }

	$custom_imgurl = (isset($_POST['custom_imgurl']))?sanitize_text_field($_POST['custom_imgurl']):'';
	
	$upload_dir = wp_upload_dir();
	
	$ThumbSquareSize 		= 100; //Thumbnail will be 57X57
	$Quality 				= 90; //jpeg quality
	$DestinationDirectory   = $upload_dir['path'].'/'; //specify upload directory ends with / (slash)
	$AcceessUrl             = $upload_dir['url'].'/';
	$ThumbPrefix			= "cmicon_";
	
	$data = $custom_imgurl;
	$params = array();
	parse_str($data, $params);
	// var_dump($params);die();
	$site_url = home_url();
	foreach($params as $key => $value)
	{

		$custom_imgurl = $value;
		if(!empty($custom_imgurl))
		{
			if(strpos($custom_imgurl, $site_url) === false){
				die(json_encode(array('res'=>'thumb_error')));
			}
			$sfsi_custom_files[] = $custom_imgurl;
			
			list($CurWidth, $CurHeight) = getimagesize($custom_imgurl);
		
			$info = explode("/", $custom_imgurl);
			$iconName = array_pop($info);
			$ImageExt = substr($iconName, strrpos($iconName, '.'));
			$ImageExt = str_replace('.','',$ImageExt);
			
			$iconName = str_replace(' ','-',strtolower($iconName)); // get image name
			$ImageType = 'image/'.$ImageExt;
			
			switch(strtolower($ImageType))
			{
				case 'image/png':
						// Create a new image from file 
						$CreatedImage =  imagecreatefrompng($custom_imgurl);
						break;
				case 'image/gif':
						$CreatedImage =  imagecreatefromgif($custom_imgurl);
						break;
				case 'image/jpg':
						$CreatedImage = imagecreatefromjpeg($custom_imgurl);
						break;					
				case 'image/jpeg':
				case 'image/pjpeg':
						$CreatedImage = imagecreatefromjpeg($custom_imgurl);
						break;
				default:
						 die(json_encode(array('res'=>'type_error'))); //output error and exit
			}
	
			$ImageName = preg_replace("/\\.[^.\\s]{3,4}$/", "", $iconName);
			
			$NewIconName = "/custom_icon".$key.'.'.$ImageExt;
			$iconPath 	= $DestinationDirectory.$NewIconName; //Thumbnail name with destination directory
			
			//Create a square Thumbnail right after, this time we are using cropImage() function
			if(cropImage($CurWidth,$CurHeight,$ThumbSquareSize,$iconPath,$CreatedImage,$Quality,$ImageType))
			{
				//update database information 
				$AccressImagePath=$AcceessUrl.$NewIconName;                                        
				update_option($key,$AccressImagePath);
				die(json_encode(array('res'=>'success')));
			}
			else
			{        
			   die(json_encode(array('res'=>'thumb_error')));
			}
		}	
	}
}


/** Function sfsi_UploadIcons() called by wp_ajax hooks: {'UploadIcons'} **/
/** Parameters found in function sfsi_UploadIcons(): {"post": ["nonce", "custom_imgurl"]} **/
function sfsi_UploadIcons()
{
	if ( !wp_verify_nonce( sanitize_text_field($_POST['nonce']), "UploadIcons")) {
		echo  json_encode(array('res'=>"error")); exit;
	}
    if(!current_user_can('manage_options')){ echo json_encode(array('res'=>'not allowed'));die(); }

	$custom_imgurl = isset($_POST) && isset($_POST['custom_imgurl']) ? esc_url($_POST['custom_imgurl']):'';
	
	if(strpos($custom_imgurl, home_url()) === false){
		die(json_encode(array('res'=>'thumb_error')));
	}

	$upload_dir = wp_upload_dir();
	
	$ThumbSquareSize 		= 100; //Thumbnail will be 57X57
	$Quality 				= 90; //jpeg quality
	$DestinationDirectory   = $upload_dir['path'].'/'; //specify upload directory ends with / (slash)
	$AcceessUrl             = $upload_dir['url'].'/';
	$ThumbPrefix			= "cmicon_";
	
   if(!empty($custom_imgurl))
	{
		$sfsi_custom_files[] = $custom_imgurl;	
			
		list($CurWidth, $CurHeight) = getimagesize($custom_imgurl);
	
		$info = explode("/", $custom_imgurl);
		$iconName = array_pop($info);
		$ImageExt = substr($iconName, strrpos($iconName, '.'));
		$ImageExt = str_replace('.','',$ImageExt);
		
		$iconName = str_replace(' ','-',strtolower($iconName)); // get image name
		$ImageType = 'image/'.$ImageExt;
		
		 switch(strtolower($ImageType))
		 {
			 	case 'image/png':
						// Create a new image from file 
						$CreatedImage =  imagecreatefrompng($custom_imgurl);
						break;
				case 'image/gif':
						$CreatedImage =  imagecreatefromgif($custom_imgurl);
						break;
				case 'image/jpg':
						$CreatedImage = imagecreatefromjpeg($custom_imgurl);
						break;					
				case 'image/jpeg':
				case 'image/pjpeg':
						$CreatedImage = imagecreatefromjpeg($custom_imgurl);
						break;
				default:
						 die(json_encode(array('res'=>'type_error'))); //output error and exit
		}

		
		$ImageName = preg_replace("/\\.[^.\\s]{3,4}$/", "", $iconName);
		//$cnt=$i+1;
		
		$sec_options= (get_option('sfsi_section1_options',false)) ? maybe_unserialize(get_option('sfsi_section1_options',false)) : '' ;        
		$icons = (is_array(maybe_unserialize($sec_options['sfsi_custom_files']))) ? maybe_unserialize($sec_options['sfsi_custom_files']) : array();
		if(empty($icons))
		{   
			end($icons);
			$new=0;
		}    
		else {
			end($icons);
			$cnt=key($icons);
			$new=$cnt+1;
		}
		$NewIconName = "custom_icon".$new.'.'.$ImageExt;
        $iconPath 	= $DestinationDirectory.$NewIconName; //Thumbnail name with destination directory
		
		//Create a square Thumbnail right after, this time we are using cropImage() function
		if(cropImage($CurWidth,$CurHeight,$ThumbSquareSize,$iconPath,$CreatedImage,$Quality,$ImageType))
		{
			 //update database information 
				$AccressImagePath=$AcceessUrl.$NewIconName;                                        
					$sec_options= (get_option('sfsi_section1_options',false)) ? maybe_unserialize(get_option('sfsi_section1_options',false)) : '' ;
					$icons = (is_array(unserialize($sec_options['sfsi_custom_files']))) ? unserialize($sec_options['sfsi_custom_files']) : array();
					$icons[] = $AccressImagePath;
					
					$sec_options['sfsi_custom_files'] = serialize($icons);
					$total_uploads = ( isset($icons) && is_array($icons) )?count($icons):0; end($icons); $key = key($icons);
					update_option('sfsi_section1_options',serialize($sec_options));
					die(json_encode(array('res'=>'success','img_path'=>$AccressImagePath,'element'=>$total_uploads,'key'=>$key)));
	   }
	   else
	   {        
		   die(json_encode(array('res'=>'thumb_error')));
	   }
		
	}
}


/** Function noticeAjax() called by wp_ajax hooks: {'tifm_notice_actions'} **/
/** Parameters found in function noticeAjax(): {"post": ["nonce", "method"]} **/
function noticeAjax() {

          // Nonce verification
          if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'tifm_notice_nonce')) {
            wp_send_json_error();
            return;
          }

          $method = '';
          if (isset($_POST['method'])) {

            $method = sanitize_text_field($_POST['method']);

          }

          if ($method == 'dismiss_notice') {

            update_option('_tifm_hide_notice_forever', true);
            wp_send_json_success();
            exit;

          } else if ($method == 'dismiss_notice_and_enable') {
            
            update_option('_tifm_feature_enabled', 'enabled');
            update_option('_tifm_hide_notice_forever', true);
            delete_option('_tifm_disable_feature_forever');
            wp_send_json_success();
            exit;
            
          } else if ($method == 'dismiss_notice_and_disable') {

            update_option('_tifm_hide_notice_forever', true);
            update_option('_tifm_disable_feature_forever', true);
            update_option('_tifm_feature_enabled', 'disabled');
            wp_send_json_success();
            exit;

          } else {

            wp_send_json_error();
            exit;

          }

        }


/** Function sfsi_banner_global_load_faster() called by wp_ajax hooks: {'sfsi_banner_global_load_faster'} **/
/** Parameters found in function sfsi_banner_global_load_faster(): {"post": ["nonce", "sfsi_banner_global_load_faster"]} **/
function sfsi_banner_global_load_faster()
{
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'sfsi_banner_global_load_faster')) return wp_send_json_error();
    if (!current_user_can('manage_options')) return wp_send_json_error();
    
    $sfsi_banner_global_load_faster_value   = isset( $_POST["sfsi_banner_global_load_faster"] ) ? sanitize_text_field($_POST["sfsi_banner_global_load_faster"]) : '';
    $sfsi_banner_global_load_faster = maybe_unserialize(get_option('sfsi_banner_global_load_faster', false));
    $sfsi_banner_global_load_faster['timestamp'] = $sfsi_banner_global_load_faster_value;
    update_option('sfsi_banner_global_load_faster',  serialize($sfsi_banner_global_load_faster));
    echo json_encode(array("success"));
    exit;
}


/** Function sfsi_banner_global_upgrade() called by wp_ajax hooks: {'sfsi_banner_global_upgrade'} **/
/** Parameters found in function sfsi_banner_global_upgrade(): {"post": ["nonce", "sfsi_banner_global_upgrade"]} **/
function sfsi_banner_global_upgrade()
{
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'sfsi_banner_global_upgrade')) return wp_send_json_error();
    if (!current_user_can('manage_options')) return wp_send_json_error();
    
    $sfsi_banner_global_upgrade_value   = isset( $_POST["sfsi_banner_global_upgrade"] ) ? sanitize_text_field($_POST["sfsi_banner_global_upgrade"]) : '';
    $sfsi_banner_global_upgrade = maybe_unserialize(get_option('sfsi_banner_global_upgrade', false));
    $sfsi_banner_global_upgrade['timestamp'] = $sfsi_banner_global_upgrade_value;
    update_option('sfsi_banner_global_upgrade',  serialize($sfsi_banner_global_upgrade));
    echo json_encode(array("success"));
    exit;
}


/** Function sfsi_HideRatingDiv() called by wp_ajax hooks: {'sfsi_hideRating'} **/
/** Parameters found in function sfsi_HideRatingDiv(): {"post": ["nonce"]} **/
function sfsi_HideRatingDiv() {
    if ( !wp_verify_nonce( sanitize_text_field($_POST['nonce']), "sfsi_hideRating" ) ) {
        echo json_encode( array( 'res' => "error" ) );
        exit;
    }

    if ( !current_user_can( 'manage_options' ) ) {
        echo json_encode( array( 'res' => 'not allowed' ) );
        die();
    }

    update_option( 'sfsi_RatingDiv', 'yes' );
    echo json_encode( array( "success" ) );
    exit;
}


/** Function sfsi_banner_global_firsttime_offer() called by wp_ajax hooks: {'sfsi_banner_global_firsttime_offer'} **/
/** Parameters found in function sfsi_banner_global_firsttime_offer(): {"post": ["nonce", "sfsi_banner_global_firsttime_offer"]} **/
function sfsi_banner_global_firsttime_offer()
{
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'sfsi_banner_global_firsttime_offer')) return wp_send_json_error();
    if (!current_user_can('manage_options')) return wp_send_json_error();
    
    $sfsi_banner_global_firsttime_offer_value   = isset( $_POST["sfsi_banner_global_firsttime_offer"] ) ? sanitize_text_field($_POST["sfsi_banner_global_firsttime_offer"]) : '';
    $sfsi_banner_global_firsttime_offer = maybe_unserialize(get_option('sfsi_banner_global_firsttime_offer', false));
    $sfsi_banner_global_firsttime_offer['timestamp'] = $sfsi_banner_global_firsttime_offer_value;
    update_option('sfsi_banner_global_firsttime_offer',  serialize($sfsi_banner_global_firsttime_offer));
    echo json_encode(array("success"));
    exit;
}


/** Function sfsi_options_updater6() called by wp_ajax hooks: {'updateSrcn6'} **/
/** Parameters found in function sfsi_options_updater6(): {"post": ["nonce", "sfsi_show_Onposts", "sfsi_icons_postPositon", "sfsi_icons_alignment", "sfsi_textBefor_icons", "sfsi_rectsub", "sfsi_rectfb", "sfsi_rectshr", "sfsi_recttwtr", "sfsi_rectpinit", "sfsi_rectfbshare", "sfsi_display_button_type", "sfsi_responsive_icons"]} **/
function sfsi_options_updater6()
{
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), "update_step6")) {
        echo json_encode(array("wrong_nonce"));
        exit;
    }
    if (!current_user_can('manage_options')) {
        echo json_encode(array('res' => 'not allowed'));
        die();
    }

    $sfsi_show_Onposts              = isset( $_POST["sfsi_show_Onposts"] ) ? sanitize_text_field($_POST["sfsi_show_Onposts"] ) : 'no';

    $sfsi_icons_postPositon     = isset( $_POST["sfsi_icons_postPositon"] ) ? sanitize_text_field($_POST["sfsi_icons_postPositon"] ) : '';
    $sfsi_icons_alignment       = isset( $_POST["sfsi_icons_alignment"] ) ? sanitize_text_field($_POST["sfsi_icons_alignment"] ) : 'center-right';
    $sfsi_textBefor_icons       = isset( $_POST["sfsi_textBefor_icons"] ) ? sanitize_text_field($_POST["sfsi_textBefor_icons"] ) : '';
    $sfsi_rectsub               = isset( $_POST["sfsi_rectsub"] ) ? sanitize_text_field($_POST["sfsi_rectsub"] ) : 'no';
    $sfsi_rectfb                = isset( $_POST["sfsi_rectfb"] ) ? sanitize_text_field($_POST["sfsi_rectfb"] ) : 'no';
    $sfsi_rectshr               = isset( $_POST["sfsi_rectshr"] ) ? sanitize_text_field($_POST["sfsi_rectshr"] ) : 'no';
    $sfsi_recttwtr              = isset( $_POST["sfsi_recttwtr"] ) ? sanitize_text_field($_POST["sfsi_recttwtr"] ) : 'no';
    $sfsi_rectpinit             = isset( $_POST["sfsi_rectpinit"] ) ? sanitize_text_field($_POST["sfsi_rectpinit"] ) : 'no';
    $sfsi_rectfbshare           = isset( $_POST["sfsi_rectfbshare"] ) ? sanitize_text_field($_POST["sfsi_rectfbshare"] ) : 'no';
    $sfsi_display_button_type   = isset( $_POST["sfsi_display_button_type"] ) ? sanitize_text_field($_POST["sfsi_display_button_type"] ) : 'no';

    $sfsi_responsive_icons_default = array(
        "default_icons" => array(
            "facebook" => array("active" => "yes", "text" => "Share on Facebook", "url" => ""),
            "Twitter" => array("active" => "yes", "text" => "Post on X", "url" => ""),
            "Follow" => array("active" => "yes", "text" => "Follow us", "url" => ""),
            "Pinterest" => array("active" => "yes", "text" => "Save", "url" => "")
        ),
        "custom_icons" => array(),
        "settings" => array(
            "icon_size" => "Medium",
            "icon_width_type" => "Fully responsive",
            "icon_width_size" => 240,
            "edge_type" => "Round",
            "edge_radius" => 5,
            "style" => "Gradient",
            "margin" => 10,
            "text_align" => "Centered",
            "show_count" => "no",
            "counter_color" => "#aaaaaa",
            "counter_bg_color" => "#fff",
            "share_count_text" => "SHARES",
            "margin_above" => 10,
            "margin_below" => 10,
        )
    );
    $sfsi_responsive_icons = array();
    // var_dump($_POST['sfsi_responsive_icons'] );
    if (isset( $_POST['sfsi_responsive_icons'] ) && is_array($_POST['sfsi_responsive_icons'] )) {
        foreach ($_POST['sfsi_responsive_icons'] as $key => $value) {
            $key = sanitize_text_field($key);
            if (!is_array($value)) {
                $sfsi_responsive_icons[$key] = sanitize_text_field($value);
            } else {
                $sfsi_responsive_icons[$key] = array();
                foreach ($value as $key2 => $value2) {
                    $key2 = sanitize_text_field($key2);
                    if (!is_array($value2)) {
                        $sfsi_responsive_icons[$key][$key2] = sanitize_text_field($value2);
                    } else {
                        $sfsi_responsive_icons[$key][$key2] = array();
                        foreach ($value2 as $key3 => $value3) {
                            $key3 = sanitize_text_field($key3);
                            if (!is_array($value3)) {
                                $sfsi_responsive_icons[$key][$key2][$key3] = sanitize_text_field($value3);
                            }
                        }
                    }
                }
            }
        }
    }
    if (empty($sfsi_responsive_icons)) {
        $sfsi_responsive_icons = $sfsi_responsive_icons_default;
    } else {
        if (!isset( $sfsi_responsive_icons['default_icons'] )) {
            $sfsi_responsive_icons["default_icons"] = $sfsi_responsive_icons_default["default_icons"];
        }
        if (!isset( $sfsi_responsive_icons['custom_icons'] )) {
            $sfsi_responsive_icons["custom_icons"] = array();
        }
        if (!isset( $sfsi_responsive_icons['settings'] )) {
            $sfsi_responsive_icons["settings"] = $sfsi_responsive_icons_default["settings"];
        }
        foreach ($sfsi_responsive_icons['default_icons'] as $key => $value) {
            foreach (array_keys($sfsi_responsive_icons_default['default_icons']['facebook'] ) as $default_icon_key) {
                if (!isset( $value[$default_icon_key] )) {
                    $sfsi_responsive_icons["default_icons"][$key][$default_icon_key] = $sfsi_responsive_icons_default['default_icons'][$key][$default_icon_key];
                } else {
                    $sfsi_responsive_icons["default_icons"][$key][$default_icon_key] = sanitize_text_field($sfsi_responsive_icons["default_icons"][$key][$default_icon_key] );
                }
            }
        }
        foreach ($sfsi_responsive_icons['custom_icons'] as $key => $value) {
            if (!isset( $value['active'] )) {
                $sfsi_responsive_icons["custom_icons"][$key]["active"] = "no";
            } else {
                $sfsi_responsive_icons["custom_icons"][$key]["active"] = sanitize_text_field($sfsi_responsive_icons["custom_icons"][$key]["active"] );
            }
            if (!isset( $value['url'] )) {
                $sfsi_responsive_icons["custom_icons"][$key]["url"] = "#";
            } else {
                $sfsi_responsive_icons["custom_icons"][$key]["url"] = sanitize_text_field($sfsi_responsive_icons["custom_icons"][$key]["url"] );
            }
            if (!isset( $value['text'] )) {
                $sfsi_responsive_icons["custom_icons"][$key]["text"] = "Share";
            } else {
                $sfsi_responsive_icons["custom_icons"][$key]["text"] = sanitize_text_field($sfsi_responsive_icons["custom_icons"][$key]["text"] );
            }
            if (!isset( $value['icon'] )) {
                $sfsi_responsive_icons["custom_icons"][$key]["icon"] = "";
            } else {
                $sfsi_responsive_icons["custom_icons"][$key]["icon"] = sanitize_text_field($sfsi_responsive_icons["custom_icons"][$key]["icon"] );
            }
            if (!isset( $value['bg-color'] )) {
                $sfsi_responsive_icons["custom_icons"][$key]["bg-color"] = "#fff";
            } else {
                $sfsi_responsive_icons["custom_icons"][$key]["bg-color"] = sanitize_text_field($sfsi_responsive_icons["custom_icons"][$key]["bg-color"] );
            }
        }
        foreach (array_keys($sfsi_responsive_icons_default['settings'] ) as $setting_key) {
            if (!isset( $sfsi_responsive_icons["settings"][$setting_key] )  || is_null($sfsi_responsive_icons["settings"][$setting_key] ) || $sfsi_responsive_icons["settings"][$setting_key] === "") {
                $sfsi_responsive_icons["settings"][$setting_key] = $sfsi_responsive_icons_default['settings'][$setting_key];
            } else {
                $sfsi_responsive_icons["settings"][$setting_key] = sanitize_text_field($sfsi_responsive_icons["settings"][$setting_key] );
            }
        }
    }
    /* post options */
    $up_option6 = array(

        'sfsi_show_Onposts'     => sanitize_text_field($sfsi_show_Onposts),

        'sfsi_icons_postPositon' => sanitize_text_field($sfsi_icons_postPositon),
        'sfsi_icons_alignment'  => sanitize_text_field($sfsi_icons_alignment),
        'sfsi_textBefor_icons'  => sanitize_text_field(stripslashes($sfsi_textBefor_icons)),
        'sfsi_rectsub'          => sanitize_text_field($sfsi_rectsub),
        'sfsi_rectfb'           => sanitize_text_field($sfsi_rectfb),
        'sfsi_rectshr'          => sanitize_text_field($sfsi_rectshr),
        'sfsi_recttwtr'         => sanitize_text_field($sfsi_recttwtr),
        'sfsi_rectpinit'        => sanitize_text_field($sfsi_rectpinit),
        'sfsi_rectfbshare'      => sanitize_text_field($sfsi_rectfbshare),
        'sfsi_responsive_icons' => $sfsi_responsive_icons,
        'sfsi_display_button_type'        => sanitize_text_field($sfsi_display_button_type),

    );
    update_option('sfsi_section6_options', serialize($up_option6));
    header('Content-Type: application/json');
    echo json_encode(array("success"));
    exit;
}


/** Function sfsi_banner_global_social() called by wp_ajax hooks: {'sfsi_banner_global_social'} **/
/** Parameters found in function sfsi_banner_global_social(): {"post": ["nonce", "sfsi_banner_global_social"]} **/
function sfsi_banner_global_social()
{
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'sfsi_banner_global_social')) return wp_send_json_error();
    if (!current_user_can('manage_options')) return wp_send_json_error();
    
    $sfsi_banner_global_social_value   = isset( $_POST["sfsi_banner_global_social"] ) ? sanitize_text_field($_POST["sfsi_banner_global_social"]) : '';
    $sfsi_banner_global_social = maybe_unserialize(get_option('sfsi_banner_global_social', false));
    $sfsi_banner_global_social['timestamp'] = $sfsi_banner_global_social_value;
    update_option('sfsi_banner_global_social',  serialize($sfsi_banner_global_social));
    echo json_encode(array("success"));
    exit;
}


/** Function update_sharing_settings() called by wp_ajax hooks: {'update_sharing_settings'} **/
/** Parameters found in function update_sharing_settings(): {"post": ["nonce", "sfsi_custom_social_hide"]} **/
function update_sharing_settings() {
	if ( !wp_verify_nonce( sanitize_text_field($_POST['nonce']), "update_sharing_settings")) {
		echo  json_encode(array('res'=>"error")); exit;
	}
    if(!current_user_can('manage_options')){ echo json_encode(array('res'=>'not allowed'));die(); }
	
	$option5 = maybe_unserialize(get_option('sfsi_section5_options',false));

	// Ensure $option5 is an array before accessing/modifying it (PHP 8.x compatibility)
	if (!is_array($option5)) {
		$option5 = array();
	}

	$option5['sfsi_custom_social_hide'] = sanitize_text_field($_POST['sfsi_custom_social_hide']);
	update_option('sfsi_section5_options',serialize($option5));
	echo true;
	wp_die(); // this is required to terminate immediately and return a proper response
}


/** Function sfsi_Iamdone() called by wp_ajax hooks: {'Iamdone'} **/
/** Parameters found in function sfsi_Iamdone(): {"post": ["nonce"]} **/
function sfsi_Iamdone()
{
	 $return = '';
	if ( !wp_verify_nonce( sanitize_text_field($_POST['nonce']), "Iamdone")) {
		echo  json_encode(array('res'=>"error")); exit;
	}
    if(!current_user_can('manage_options')){ echo json_encode(array('res'=>'not allowed'));die(); }

	 if(get_option("rss_skin"))
	 {
		$icon = get_option("rss_skin");
		$return .= '<span class="row_17_1 rss_section sfsi-bgimage" style="background: url('.$icon.') no-repeat;"></span>';
	 }else
	 {
		$return .= '<span class="row_17_1 rss_section" style="background-position:-1px 0;"></span>';
	 }
	 
	 if(get_option("email_skin"))
	 {
		$icon = get_option("email_skin");
		$return .= '<span class="row_17_2 email_section sfsi-bgimage" style="background: url('.$icon.') no-repeat;"></span>';
	 }else
	 {
		$return .= '<span class="row_17_2 email_section" style="background-position:-58px 0;"></span>';
	 }
	 
	 if(get_option("facebook_skin"))
	 {
		$icon = get_option("facebook_skin");
		$return .= '<span class="row_17_3 facebook_section sfsi-bgimage" style="background: url('.$icon.') no-repeat;"></span>';
	 }else
	 {
		$return .= '<span class="row_17_3 facebook_section" style="background-position:-118px 0;"></span>';
	 }
	 
	 if(get_option("twitter_skin"))
	 {
		$icon = get_option("twitter_skin");
		$return .= '<span class="row_17_5 twitter_section sfsi-bgimage" style="background: url('.$icon.') no-repeat;"></span>';
	 }else
	 {
		$return .= '<span class="row_17_5 twitter_section" style="background-position:-235px 0;"></span>';
	 }
	 
	 if(get_option("share_skin"))
	 {
		$icon = get_option("share_skin");
		$return .= '<span class="row_17_6 share_section sfsi-bgimage" style="background: url('.$icon.') no-repeat;"></span>';
	 }else
	 {
		$return .= '<span class="row_17_6 share_section" style="background-position:-293px 0;"></span>';
	 }
	 
	 if(get_option("youtube_skin"))
	 {
		$icon = get_option("youtube_skin");
		$return .= '<span class="row_17_7 youtube_section sfsi-bgimage" style="background: url('.$icon.') no-repeat;"></span>';
	 }else
	 {
		$return .= '<span class="row_17_7 youtube_section" style="background-position:-350px 0;"></span>';
	 }
	 
	 if(get_option("pintrest_skin"))
	 {
		$icon = get_option("pintrest_skin");
		$return .= '<span class="row_17_8 pinterest_section sfsi-bgimage" style="background: url('.$icon.') no-repeat;"></span>';
	 }else
	 {
		$return .= '<span class="row_17_8 pinterest_section" style="background-position:-409px 0;"></span>';
	 }
	 
	 if(get_option("linkedin_skin"))
	 {
		$icon = get_option("linkedin_skin");
		$return .= '<span class="row_17_9 linkedin_section sfsi-bgimage" style="background: url('.$icon.') no-repeat;"></span>';
	 }else
	 {
		$return .= '<span class="row_17_9 linkedin_section" style="background-position:-467px 0;"></span>';
	 }
	 
	 if(get_option("instagram_skin"))
	 {
		$icon = get_option("instagram_skin");
		$return .= '<span class="row_17_10 instagram_section sfsi-bgimage" style="background: url('.$icon.') no-repeat;"></span>';
	 }else
	 {
		$return .= '<span class="row_17_10 instagram_section" style="background-position:-526px 0;"></span>';
	 }
	 if(get_option("telegram_skin"))
	 {
		$icon = get_option("telegram_skin");
		$return .= '<span class="row_17_10 telegram_section sfsi-bgimage" style="background: url('.$icon.') no-repeat;"></span>';
	 }else
	 {
		$return .= '<span class="row_17_10 telegram_section" style="background-position:-773px 0;"></span>';
	 }
	 if(get_option("vk_skin"))
	 {
		$icon = get_option("vk_skin");
		$return .= '<span class="row_17_10 vk_section sfsi-bgimage" style="background: url('.$icon.') no-repeat;"></span>';
	 }else
	 {
		$return .= '<span class="row_17_10 vk_section" style="background-position:-838px 0;"></span>';
	 }
	 if(get_option("ok_skin"))
	 {
		$icon = get_option("ok_skin");
		$return .= '<span class="row_17_10 ok_section sfsi-bgimage" style="background: url('.$icon.') no-repeat;"></span>';
	 }else
	 {
		$return .= '<span class="row_17_10 ok_section" style="background-position:-909px 0;"></span>';
	 }
	 if(get_option("weibo_skin"))
	 {
		$icon = get_option("weibo_skin");
		$return .= '<span class="row_17_10 weibo_section sfsi-bgimage" style="background: url('.$icon.') no-repeat;"></span>';
	 }else
	 {
		$return .= '<span class="row_17_10 weibo_section" style="background-position:-977px 0;"></span>';
	 }
	 if(get_option("wechat_skin"))
	 {
		$icon = get_option("wechat_skin");
		$return .= '<span class="row_17_10 wechat_section sfsi-bgimage" style="background: url('.$icon.') no-repeat;"></span>';
	 }else
	 {
		$return .= '<span class="row_1_18 wechat_section"></span>';
	 }
	 die($return);
}


/** Function sfsi_OfflineChatMessage() called by wp_ajax hooks: {'sfsiOfflineChatMessage'} **/
/** Parameters found in function sfsi_OfflineChatMessage(): {"post": ["nonce", "email", "message"]} **/
function sfsi_OfflineChatMessage()
{
    error_reporting(0);
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), "OfflineChatMessage")) {
        echo json_encode(array("wrong_nonce"));
        exit;
    }
    if (!current_user_can('manage_options')) {
        echo json_encode(array('res' => 'not allowed'));
        die();
    }

    $email = isset( $_POST) && isset( $_POST['email'] ) ? sanitize_email($_POST['email'] ) : '';
    $message = isset( $_POST) && isset( $_POST['message'] ) ? sanitize_textarea_field($_POST['message'] ) : '';
    $body = "<table><tr><th>Site:</th><td>" . home_url() . "</td></tr><tr><th>Plugin:</th><td>Old Plugin</td></tr><tr><th>Email:</th><td>" . $email . "</td></tr><tr><th>Message:</th><td>" . $message . "</td></tr></table>";
    $sent = wp_mail('help@ultimatelysocial.com', "New question from user", $body, array('Content-Type: text/html; charset=UTF-8'));
    if (isset( $sent) && (true === $sent)) {
        echo "success";
    } else {
        echo "failure";
    }
    die();
}


/** Function sfsi_banner_global_gdpr() called by wp_ajax hooks: {'sfsi_banner_global_gdpr'} **/
/** Parameters found in function sfsi_banner_global_gdpr(): {"post": ["nonce", "sfsi_banner_global_gdpr"]} **/
function sfsi_banner_global_gdpr()
{
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'sfsi_banner_global_gdpr')) return wp_send_json_error();
    if (!current_user_can('manage_options')) return wp_send_json_error();
    
    $sfsi_banner_global_gdpr_value   = isset( $_POST["sfsi_banner_global_gdpr"] ) ? sanitize_text_field($_POST["sfsi_banner_global_gdpr"]) : '';
    $sfsi_banner_global_gdpr = maybe_unserialize(get_option('sfsi_banner_global_gdpr', false));
    $sfsi_banner_global_gdpr['timestamp'] = $sfsi_banner_global_gdpr_value;
    update_option('sfsi_banner_global_gdpr',  serialize($sfsi_banner_global_gdpr));
    echo json_encode(array("success"));
    exit;
}


/** Function sfsi_options_updater7() called by wp_ajax hooks: {'updateSrcn7'} **/
/** Parameters found in function sfsi_options_updater7(): {"post": ["nonce", "sfsi_popup_text", "sfsi_popup_background_color", "sfsi_popup_border_color", "sfsi_popup_border_thickness", "sfsi_popup_border_shadow", "sfsi_popup_font", "sfsi_popup_fontSize", "sfsi_popup_fontStyle", "sfsi_popup_fontColor", "sfsi_Show_popupOn", "sfsi_Show_popupOn_PageIDs", "sfsi_Shown_pop", "sfsi_Shown_popupOnceTime", "sfsi_Shown_popuplimitPerUserTime", "sfsi_Show_popupOn_somepages_blogpage", "sfsi_Show_popupOn_somepages_selectedpage", "sfsi_popup_show_on_desktop", "sfsi_popup_show_on_mobile"]} **/
function sfsi_options_updater7()
{
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), "update_step7")) {
        echo json_encode(array("wrong_nonce"));
        exit;
    }
    if (!current_user_can('manage_options')) {
        echo json_encode(array('res' => 'not allowed'));
        die();
    }

    $sfsi_popup_text                    = isset( $_POST["sfsi_popup_text"] ) ? sanitize_text_field($_POST["sfsi_popup_text"] ) : '';
    $sfsi_popup_background_color        = isset( $_POST["sfsi_popup_background_color"] ) ? sfsi_sanitize_hex_color($_POST["sfsi_popup_background_color"] ) : '#fffff';
    $sfsi_popup_border_color            = isset( $_POST["sfsi_popup_border_color"] ) ? sfsi_sanitize_hex_color($_POST["sfsi_popup_border_color"] ) : 'center-right';
    $sfsi_popup_border_thickness        = isset( $_POST["sfsi_popup_border_thickness"] ) ? intval($_POST["sfsi_popup_border_thickness"] ) : '';
    $sfsi_popup_border_shadow           = isset( $_POST["sfsi_popup_border_shadow"] ) ? sanitize_text_field($_POST["sfsi_popup_border_shadow"] ) : 'no';
    $sfsi_popup_font                    = isset( $_POST["sfsi_popup_font"] ) ? sanitize_text_field($_POST["sfsi_popup_font"] ) : '';
    $sfsi_popup_fontSize                = isset( $_POST["sfsi_popup_fontSize"] ) ? intval($_POST["sfsi_popup_fontSize"] ) : 'no';
    $sfsi_popup_fontStyle               = isset( $_POST["sfsi_popup_fontStyle"] ) ? sanitize_text_field($_POST["sfsi_popup_fontStyle"] ) : '';
    $sfsi_popup_fontColor               = isset( $_POST["sfsi_popup_fontColor"] ) ? sfsi_sanitize_hex_color($_POST["sfsi_popup_fontColor"] ) : 'no';
    $sfsi_Show_popupOn                  = isset( $_POST["sfsi_Show_popupOn"] ) ? sanitize_text_field($_POST["sfsi_Show_popupOn"] ) : '';
    if (isset( $_POST["sfsi_Show_popupOn_PageIDs"] )) {
        $sfsi_Show_popupOn_PageIDs_arr = array();
        foreach ($_POST["sfsi_Show_popupOn_PageIDs"] as $index => $sfsi_Show_popupOn_PageID) {
            $index = sanitize_text_field($index);
            $sfsi_Show_popupOn_PageIDs_arr[$index] = intval($sfsi_Show_popupOn_PageID);
        }
    }
    $sfsi_Show_popupOn_PageIDs          = isset( $sfsi_Show_popupOn_PageIDs_arr) ? serialize($sfsi_Show_popupOn_PageIDs_arr) : '';
    $sfsi_Shown_pop                     = isset( $_POST["sfsi_Shown_pop"] ) ? sanitize_text_field($_POST["sfsi_Shown_pop"] ) : '';
    $sfsi_Shown_popupOnceTime           = isset( $_POST["sfsi_Shown_popupOnceTime"] ) ? sanitize_text_field($_POST["sfsi_Shown_popupOnceTime"] ) : 'no';
    $sfsi_Shown_popuplimitPerUserTime   = isset( $_POST["sfsi_Shown_popuplimitPerUserTime"] ) ? sanitize_text_field($_POST["sfsi_Shown_popuplimitPerUserTime"] ) : '';

    $sfsi_Show_popupOn_somepages_blogpage       = isset( $_POST["sfsi_Show_popupOn_somepages_blogpage"] ) ? sanitize_text_field( $_POST["sfsi_Show_popupOn_somepages_blogpage"] ) : '';
    $sfsi_Show_popupOn_somepages_selectedpage   = isset( $_POST["sfsi_Show_popupOn_somepages_selectedpage"] ) ? sanitize_text_field( $_POST["sfsi_Show_popupOn_somepages_selectedpage"] ) : '';
    $sfsi_popup_show_on_desktop         = isset( $_POST["sfsi_popup_show_on_desktop"] ) ? sanitize_text_field( $_POST["sfsi_popup_show_on_desktop"] ) : '';
    $sfsi_popup_show_on_mobile          = isset( $_POST["sfsi_popup_show_on_mobile"] ) ? sanitize_text_field( $_POST["sfsi_popup_show_on_mobile"] ) : '';
    /* icons pop options */
    $up_option7 = array(
        'sfsi_popup_text'               => sanitize_text_field(stripslashes($sfsi_popup_text)),
        'sfsi_popup_background_color'   => sfsi_sanitize_hex_color($sfsi_popup_background_color),
        'sfsi_popup_border_color'       => sfsi_sanitize_hex_color($sfsi_popup_border_color),
        'sfsi_popup_border_thickness'   => intval($sfsi_popup_border_thickness),
        'sfsi_popup_border_shadow'      => sanitize_text_field($sfsi_popup_border_shadow),
        'sfsi_popup_font'               => sanitize_text_field($sfsi_popup_font),
        'sfsi_popup_fontSize'           => intval($sfsi_popup_fontSize),
        'sfsi_popup_fontStyle'          => sanitize_text_field($sfsi_popup_fontStyle),
        'sfsi_popup_fontColor'          => sfsi_sanitize_hex_color($sfsi_popup_fontColor),

        'sfsi_Show_popupOn'             => sanitize_text_field($sfsi_Show_popupOn),
        'sfsi_Show_popupOn_PageIDs'     => $sfsi_Show_popupOn_PageIDs,

        'sfsi_Shown_pop'                => sanitize_text_field($sfsi_Shown_pop),
        'sfsi_Shown_popupOnceTime'      => intval($sfsi_Shown_popupOnceTime),
        'sfsi_Show_popupOn_somepages_blogpage'    => $sfsi_Show_popupOn_somepages_blogpage,
        'sfsi_Show_popupOn_somepages_selectedpage'    => $sfsi_Show_popupOn_somepages_selectedpage,
        'sfsi_popup_show_on_desktop'    => $sfsi_popup_show_on_desktop,
        'sfsi_popup_show_on_mobile'     => $sfsi_popup_show_on_mobile,
        //'sfsi_Shown_popuplimitPerUserTime'=> $sfsi_Shown_popuplimitPerUserTime,
    );
    update_option('sfsi_section7_options', serialize($up_option7));
    header('Content-Type: application/json');
    echo json_encode(array("success"));
    exit;
}


/** Function sfsi_installDate() called by wp_ajax hooks: {'sfsi_installDate'} **/
/** Parameters found in function sfsi_installDate(): {"post": ["nonce", "sfsi_installDate"]} **/
function sfsi_installDate() {
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'sfsi_installDate')) return wp_send_json_error();
    if (!current_user_can('manage_options')) return wp_send_json_error();
    
    $sfsi_installDate_value = isset( $_POST["sfsi_installDate"] ) ? sanitize_text_field($_POST["sfsi_installDate"]) : '';
    update_option( 'sfsi_installDate', $sfsi_installDate_value );
    echo json_encode( array( "success" ) );
    exit;
}


/** Function sfsi_dismiss_lang_notice() called by wp_ajax hooks: {'sfsi_dismiss_lang_notice'} **/
/** Parameters found in function sfsi_dismiss_lang_notice(): {"post": ["nonce"]} **/
function sfsi_dismiss_lang_notice()
{

    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), "sfsi_dismiss_lang_notice'")) {

        echo json_encode(array('res' => "error"));
        exit;
    }

    if (!current_user_can('manage_options')) {
        echo json_encode(array('res' => 'not allowed'));
        die();
    }
    echo update_option('sfsi_lang_notice_dismissed', true) ? "true" : "false";

    die;
}


/** Function sfsi_DeleteSkin() called by wp_ajax hooks: {'DeleteSkin'} **/
/** Parameters found in function sfsi_DeleteSkin(): {"post": ["nonce", "action", "iconname"], "server": ["DOCUMENT_ROOT"]} **/
function sfsi_DeleteSkin()
{
	if ( !wp_verify_nonce( sanitize_text_field($_POST['nonce']), "deleteCustomSkin")) {
		echo  json_encode(array('res'=>"error")); exit;
	} 
    if(!current_user_can('manage_options')){ echo json_encode(array('res'=>'not allowed'));die(); }
	
	$upload_dir = wp_upload_dir();
	
	if(sanitize_text_field($_POST['action']) == 'DeleteSkin' && isset($_POST['iconname']) && !empty($_POST['iconname']) && current_user_can('manage_options'))
	{
		$iconsArray = array(
			"rss_skin","email_skin","facebook_skin","twitter_skin",
			"share_skin","youtube_skin","linkedin_skin","pintrest_skin","instagram_skin"
		);
		if(in_array(sanitize_text_field($_POST['iconname']), $iconsArray))
		{
			$imgurl = get_option( sanitize_text_field($_POST['iconname']) );
			$path = parse_url($imgurl, PHP_URL_PATH);
			
			if(is_file($_SERVER['DOCUMENT_ROOT'] . $path))
			{
				unlink($_SERVER['DOCUMENT_ROOT'] . $path);
			}
		   
			delete_option( sanitize_text_field($_POST['iconname']) );
			die(json_encode(array('res'=>'success')));
		}
		else
		{
			die(json_encode(array('res'=>'error')));
		}
	}
	else
	{
		die(json_encode(array('res'=>'error')));
	}	
}


/** Function notification_read() called by wp_ajax hooks: {'notification_read'} **/
/** Parameters found in function notification_read(): {"post": ["nonce"]} **/
function notification_read()
{
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), "notification_read")) {
        echo json_encode(array('res' => 'wrong_nonce'));
        exit;
    }
    if (current_user_can('manage_options')) {
        update_option("show_notification", "no");
        echo "success";
    } else {
        echo "Error";
    }
    die;
}


/** Function sfsi_banner_global_pinterest() called by wp_ajax hooks: {'sfsi_banner_global_pinterest'} **/
/** Parameters found in function sfsi_banner_global_pinterest(): {"post": ["nonce", "sfsi_banner_global_pinterest"]} **/
function sfsi_banner_global_pinterest()
{
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'sfsi_banner_global_pinterest')) return wp_send_json_error();
    if (!current_user_can('manage_options')) return wp_send_json_error();
    
    $sfsi_banner_global_pinterest_value   = isset( $_POST["sfsi_banner_global_pinterest"] ) ? sanitize_text_field($_POST["sfsi_banner_global_pinterest"]) : '';
    $sfsi_banner_global_pinterest = maybe_unserialize(get_option('sfsi_banner_global_pinterest', false));
    $sfsi_banner_global_pinterest['timestamp'] = $sfsi_banner_global_pinterest_value;
    update_option('sfsi_banner_global_pinterest',  serialize($sfsi_banner_global_pinterest));
    echo json_encode(array("success"));
    exit;
}


/** Function sfsi_get_icon_preview_callback() called by wp_ajax hooks: {'sfsi_get_icon_preview'} **/
/** Parameters found in function sfsi_get_icon_preview_callback(): {"post": ["nonce", "iconname", "iconValue"]} **/
function sfsi_get_icon_preview_callback() {
    if ( !wp_verify_nonce( sanitize_text_field($_POST['nonce']), "getIconPreview" ) ) {
        echo json_encode(array('res' => 'wrong_nonce'));
        exit;
    }
    if ( !current_user_can( 'manage_options' ) ) {
        echo json_encode( array( "You should be admin to take this action" ) );
        exit;
    }
    $iconname = esc_url( $_POST['iconname'] );
    $iconValue = esc_attr( $_POST['iconValue'] );
    echo '<img src="' . $iconname . "/icon_" . $iconValue . '.png" alt="' . $iconname . '" >';
    die;
}


/** Function sfsi_banner_global_http() called by wp_ajax hooks: {'sfsi_banner_global_http'} **/
/** Parameters found in function sfsi_banner_global_http(): {"post": ["nonce", "sfsi_banner_global_http"]} **/
function sfsi_banner_global_http()
{
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'sfsi_banner_global_http')) return wp_send_json_error();
    if (!current_user_can('manage_options')) return wp_send_json_error();
    
    $sfsi_banner_global_http_value   = isset( $_POST["sfsi_banner_global_http"] ) ? sanitize_text_field($_POST["sfsi_banner_global_http"]) : '';
    $sfsi_banner_global_http = maybe_unserialize(get_option('sfsi_banner_global_http', false));
    $sfsi_banner_global_http['timestamp'] = $sfsi_banner_global_http_value;
    update_option('sfsi_banner_global_http',  serialize($sfsi_banner_global_http));
    echo json_encode(array("success"));
    exit;
}


/** Function new_notification_read() called by wp_ajax hooks: {'new_notification_read'} **/
/** Parameters found in function new_notification_read(): {"post": ["nonce"]} **/
function new_notification_read()
{
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), "new_notification_read")) {
        echo json_encode(array('res' => 'wrong_nonce'));
        exit;
    }
    if (!current_user_can('manage_options')) {
        echo json_encode(array('res' => 'not allowed'));
        die();
    }
    update_option("show_new_notification", "no");
    echo "success";
    die;
}


/** Function sfsi_options_updater9() called by wp_ajax hooks: {'updateSrcn9'} **/
/** Parameters found in function sfsi_options_updater9(): {"post": ["nonce", "sfsi_show_via_widget", "sfsi_widget_alignment", "sfsi_icons_float", "sfsi_icons_floatPosition", "sfsi_icons_floatMargin_top", "sfsi_icons_floatMargin_bottom", "sfsi_icons_floatMargin_left", "sfsi_icons_floatMargin_right", "sfsi_disable_floaticons", "sfsi_make_icons", "sfsi_float_mobile_selection", "sfsi_float_alignment", "sfsi_show_via_shortcode", "sfsi_shortcode_alignment", "sfsi_show_via_afterposts", "sfsi_responsive_icons_after_post", "sfsi_responsive_icons_after_post_on_taxonomy", "sfsi_responsive_icons_after_pages", "sfsi_display_after_woocomerce_desc", "sfsi_sticky_bar", "sfsi_sticky_icons"]} **/
function sfsi_options_updater9()
{

    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), "update_step9")) {
        echo json_encode(array("wrong_nonce"));
        exit;
    }
    if (!current_user_can('manage_options')) {
        echo json_encode(array('res' => 'not allowed'));
        die();
    }

    $sfsi_show_via_widget           = isset( $_POST["sfsi_show_via_widget"] )          ? sanitize_text_field($_POST["sfsi_show_via_widget"] )  : 'no';
    $sfsi_widget_alignment          = isset( $_POST["sfsi_widget_alignment"] )         ? sanitize_text_field($_POST["sfsi_widget_alignment"] ) : 'Horizontal';

    $sfsi_icons_float               = isset( $_POST["sfsi_icons_float"] )              ? sanitize_text_field($_POST["sfsi_icons_float"] )           : 'no';
    $sfsi_icons_floatPosition       = isset( $_POST["sfsi_icons_floatPosition"] )      ? sanitize_text_field($_POST["sfsi_icons_floatPosition"] )   : 'center-right';
    $sfsi_icons_floatMargin_top     = isset( $_POST["sfsi_icons_floatMargin_top"] )    ? intval(sanitize_text_field($_POST["sfsi_icons_floatMargin_top"] ))  : '';
    $sfsi_icons_floatMargin_bottom  = isset( $_POST["sfsi_icons_floatMargin_bottom"] ) ? intval(sanitize_text_field($_POST["sfsi_icons_floatMargin_bottom"] )) : '';
    $sfsi_icons_floatMargin_left    = isset( $_POST["sfsi_icons_floatMargin_left"] )   ? intval(sanitize_text_field($_POST["sfsi_icons_floatMargin_left"] )) : '';
    $sfsi_icons_floatMargin_right   = isset( $_POST["sfsi_icons_floatMargin_right"] )  ? intval(sanitize_text_field($_POST["sfsi_icons_floatMargin_right"] )) : '';
    $sfsi_disable_floaticons        = isset( $_POST["sfsi_disable_floaticons"] )       ? sanitize_text_field($_POST["sfsi_disable_floaticons"] )     : 'no';
    $sfsi_make_icons                = isset( $_POST["sfsi_make_icons"] )               ? sanitize_text_field($_POST["sfsi_make_icons"] )     : 'float';
    $sfsi_float_mobile_selection    = isset( $_POST["sfsi_float_mobile_selection"] )       ? sanitize_text_field($_POST["sfsi_float_mobile_selection"] )     : 'no';
    $sfsi_float_alignment           = isset( $_POST["sfsi_float_alignment"] ) ? sanitize_text_field($_POST["sfsi_float_alignment"] ) : 'Horizontal';

    $sfsi_show_via_shortcode        = isset( $_POST["sfsi_show_via_shortcode"] )       ? sanitize_text_field($_POST["sfsi_show_via_shortcode"] )  : 'no';
    $sfsi_shortcode_alignment       = isset( $_POST["sfsi_shortcode_alignment"] ) ? sanitize_text_field($_POST["sfsi_shortcode_alignment"] ) : 'Horizontal';

    $sfsi_show_via_afterposts       = isset( $_POST["sfsi_show_via_afterposts"] )      ? sanitize_text_field($_POST["sfsi_show_via_afterposts"] )  : 'no';

    $sfsi_responsive_icons_after_post               = isset( $_POST["sfsi_responsive_icons_after_post"] )      ? sanitize_text_field($_POST["sfsi_responsive_icons_after_post"] )  : 'no';
    $sfsi_responsive_icons_after_post_on_taxonomy   = isset( $_POST["sfsi_responsive_icons_after_post_on_taxonomy"] )      ? sanitize_text_field($_POST["sfsi_responsive_icons_after_post_on_taxonomy"] )  : 'no';
    $sfsi_responsive_icons_after_pages              = isset( $_POST["sfsi_responsive_icons_after_pages"] )      ? sanitize_text_field($_POST["sfsi_responsive_icons_after_pages"] )  : 'no';
    $sfsi_display_after_woocomerce_desc             = isset( $_POST["sfsi_display_after_woocomerce_desc"] )     ? sanitize_text_field($_POST["sfsi_display_after_woocomerce_desc"] )  : 'no';

    $sfsi_sticky_bar = isset( $_POST["sfsi_sticky_bar"] ) ? sanitize_text_field( $_POST["sfsi_sticky_bar"] ) : 'no';
    $sfsi_sticky_icons_default = array(
        "default_icons" => array(
            "facebook" => array( "active" => "yes", "url" => "" ),
            "Twitter" => array( "active" => "yes", "url" => "" ),
            "Follow" => array( "active" => "yes", "url" => "" ),
            "Pinterest" => array( "active" => "yes", "url" => "" )
        ),
        "settings" => array(
            "desktop" => "yes",
            "desktop_width" => 782,
            "desktop_placement" => "left",
            "display_position" => 0,
            "desktop_placement_direction" => "up",
            "mobile" => "no",
            "mobile_width" => 784,
            "mobile_placement" => "left",
        )
    );
    $sfsi_sticky_icons = array();
    
    if ( isset( $_POST['sfsi_sticky_icons'] ) && is_array( $_POST['sfsi_sticky_icons'] ) ) {
        foreach ( $_POST['sfsi_sticky_icons'] as $key => $value ) {
            $key = sanitize_text_field( $key );
            if ( !is_array( $value ) ) {
                $sfsi_sticky_icons[$key] = sanitize_text_field( $value );
            } else {
                $sfsi_sticky_icons[$key] = array();
                foreach ( $value as $key2 => $value2 ) {
                    $key2 = sanitize_text_field( $key2 );
                    if ( !is_array( $value2 ) ) {
                        $sfsi_sticky_icons[$key][$key2] = sanitize_text_field( $value2 );
                    } else {
                        $sfsi_sticky_icons[$key][$key2] = array();
                        foreach ( $value2 as $key3 => $value3 ) {
                            $key3 = sanitize_text_field( $key3 );
                            if ( !is_array( $value3 ) ) {
                                $sfsi_sticky_icons[$key][$key2][$key3] = sanitize_text_field( $value3 );
                            }
                        }
                    }
                }
            }
        }
    }
    if ( empty( $sfsi_sticky_icons ) ) {
        $sfsi_sticky_icons = $sfsi_sticky_icons_default;
    } else {
        if ( !isset( $sfsi_sticky_icons['default_icons'] ) ) {
            $sfsi_sticky_icons["default_icons"] = $sfsi_sticky_icons_default["default_icons"];
        }

        if ( !isset( $sfsi_sticky_icons['settings'] ) ) {
            $sfsi_sticky_icons["settings"] = $sfsi_sticky_icons_default["settings"];
        }

        foreach ( $sfsi_sticky_icons['default_icons'] as $key => $value ) {
            foreach ( array_keys( $sfsi_sticky_icons_default['default_icons']['facebook'] ) as $default_icon_key ) {
                if ( !isset( $value[$default_icon_key] ) ) {
                    $sfsi_sticky_icons["default_icons"][$key][$default_icon_key] = $sfsi_sticky_icons_default['default_icons'][$key][$default_icon_key];
                } else {
                    $sfsi_sticky_icons["default_icons"][$key][$default_icon_key] = sanitize_text_field( $sfsi_sticky_icons["default_icons"][$key][$default_icon_key] );
                }
            }
        }
        
        foreach ( array_keys( $sfsi_sticky_icons_default['settings'] ) as $setting_key ) {
            if ( !isset( $sfsi_sticky_icons["settings"][$setting_key] ) || is_null( $sfsi_sticky_icons["settings"][$setting_key] ) || $sfsi_sticky_icons["settings"][$setting_key] === "" ) {
                $sfsi_sticky_icons["settings"][$setting_key] = $sfsi_sticky_icons_default['settings'][$setting_key];
            } else {
                $sfsi_sticky_icons["settings"][$setting_key] = sanitize_text_field( $sfsi_sticky_icons["settings"][$setting_key] );
            }
        }
    }

    /* icons pop options */
    $up_option9 = array(

        'sfsi_show_via_widget'          =>  sanitize_text_field($sfsi_show_via_widget),
        'sfsi_widget_alignment'         =>  sanitize_text_field($sfsi_widget_alignment),

        'sfsi_icons_float'              =>  sanitize_text_field($sfsi_icons_float),
        'sfsi_icons_floatPosition'      =>  sanitize_text_field($sfsi_icons_floatPosition),
        'sfsi_icons_floatMargin_top'    =>  intval(sanitize_text_field($sfsi_icons_floatMargin_top)),
        'sfsi_icons_floatMargin_bottom' =>  intval(sanitize_text_field($sfsi_icons_floatMargin_bottom)),
        'sfsi_icons_floatMargin_left'   =>  intval(sanitize_text_field($sfsi_icons_floatMargin_left)),
        'sfsi_icons_floatMargin_right'  =>  intval(sanitize_text_field($sfsi_icons_floatMargin_right)),
        'sfsi_disable_floaticons'       =>  sanitize_text_field($sfsi_disable_floaticons),

        'sfsi_make_icons'               =>  sanitize_text_field($sfsi_make_icons),
        'sfsi_float_alignment'          =>  sanitize_text_field($sfsi_float_alignment),
        'sfsi_float_mobile_selection'   =>  sanitize_text_field($sfsi_float_mobile_selection),

        'sfsi_show_via_shortcode'       =>  sanitize_text_field($sfsi_show_via_shortcode),
        'sfsi_shortcode_alignment'      =>  sanitize_text_field($sfsi_shortcode_alignment),
        'sfsi_show_via_afterposts'      =>  sanitize_text_field($sfsi_show_via_afterposts),
        'sfsi_responsive_icons_after_post'      =>  sanitize_text_field($sfsi_responsive_icons_after_post),
        'sfsi_responsive_icons_after_post_on_taxonomy'      =>  sanitize_text_field($sfsi_responsive_icons_after_post_on_taxonomy),
        'sfsi_responsive_icons_after_pages'      =>  sanitize_text_field($sfsi_responsive_icons_after_pages),
        'sfsi_display_after_woocomerce_desc'     =>  sanitize_text_field($sfsi_display_after_woocomerce_desc),

        'sfsi_sticky_bar'   => sanitize_text_field( $sfsi_sticky_bar ),
        'sfsi_sticky_icons' => $sfsi_sticky_icons
    );

    update_option('sfsi_section9_options', serialize($up_option9));
    header('Content-Type: application/json');
    echo json_encode(array("success"));
    exit;
}


/** Function sfsi_options_updater8() called by wp_ajax hooks: {'updateSrcn8'} **/
/** Parameters found in function sfsi_options_updater8(): {"post": ["nonce", "sfsi_form_adjustment", "sfsi_form_height", "sfsi_form_width", "sfsi_form_border", "sfsi_form_border_thickness", "sfsi_form_border_color", "sfsi_form_background", "sfsi_form_heading_text", "sfsi_form_heading_font", "sfsi_form_heading_fontstyle", "sfsi_form_heading_fontcolor", "sfsi_form_heading_fontsize", "sfsi_form_heading_fontalign", "sfsi_form_field_text", "sfsi_form_field_font", "sfsi_form_field_fontstyle", "sfsi_form_field_fontcolor", "sfsi_form_field_fontsize", "sfsi_form_field_fontalign", "sfsi_form_button_text", "sfsi_form_button_font", "sfsi_form_button_fontstyle", "sfsi_form_button_fontcolor", "sfsi_form_button_fontsize", "sfsi_form_button_fontalign", "sfsi_form_button_background"]} **/
function sfsi_options_updater8()
{
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), "update_step8")) {
        echo json_encode(array("wrong_nonce"));
        exit;
    }
    if (!current_user_can('manage_options')) {
        echo json_encode(array('res' => 'not allowed'));
        die();
    }

    $sfsi_form_adjustment       = isset( $_POST["sfsi_form_adjustment"] ) ? sanitize_text_field($_POST["sfsi_form_adjustment"] ) : 'yes';
    $sfsi_form_height           = isset( $_POST["sfsi_form_height"] ) ? intval($_POST["sfsi_form_height"] ) : '180';
    $sfsi_form_width            = isset( $_POST["sfsi_form_width"] ) ? intval($_POST["sfsi_form_width"] ) : '230';
    $sfsi_form_border           = isset( $_POST["sfsi_form_border"] ) ? sanitize_text_field($_POST["sfsi_form_border"] ) : 'no';
    $sfsi_form_border_thickness = isset( $_POST["sfsi_form_border_thickness"] ) ? intval($_POST["sfsi_form_border_thickness"] ) : '1';
    $sfsi_form_border_color     = isset( $_POST["sfsi_form_border_color"] ) ? sfsi_sanitize_hex_color($_POST["sfsi_form_border_color"] ) : '#b5b5b5';
    $sfsi_form_background       = isset( $_POST["sfsi_form_background"] ) ? sfsi_sanitize_hex_color($_POST["sfsi_form_background"] ) : '#eff7f7';

    $sfsi_form_heading_text     = isset( $_POST["sfsi_form_heading_text"] ) ? sanitize_text_field($_POST["sfsi_form_heading_text"] ) : 'Get new posts by email';
    $sfsi_form_heading_font     = isset( $_POST["sfsi_form_heading_font"] ) ? sanitize_text_field($_POST["sfsi_form_heading_font"] ) : 'Helvetica,Arial,sans-serif';
    $sfsi_form_heading_fontstyle = isset( $_POST["sfsi_form_heading_fontstyle"] ) ? sanitize_text_field($_POST["sfsi_form_heading_fontstyle"] ) : 'bold';
    $sfsi_form_heading_fontcolor = isset( $_POST["sfsi_form_heading_fontcolor"] ) ? sfsi_sanitize_hex_color($_POST["sfsi_form_heading_fontcolor"] ) : '#000000';
    $sfsi_form_heading_fontsize = isset( $_POST["sfsi_form_heading_fontsize"] ) ? intval($_POST["sfsi_form_heading_fontsize"] ) : '16';
    $sfsi_form_heading_fontalign = isset( $_POST["sfsi_form_heading_fontalign"] ) ? sanitize_text_field($_POST["sfsi_form_heading_fontalign"] ) : 'center';

    $sfsi_form_field_text       = isset( $_POST["sfsi_form_field_text"] ) ? sanitize_text_field($_POST["sfsi_form_field_text"] ) : 'Subscribe';
    $sfsi_form_field_font       = isset( $_POST["sfsi_form_field_font"] ) ? sanitize_text_field($_POST["sfsi_form_field_font"] ) : 'Helvetica,Arial,sans-serif';
    $sfsi_form_field_fontstyle  = isset( $_POST["sfsi_form_field_fontstyle"] ) ? sanitize_text_field($_POST["sfsi_form_field_fontstyle"] ) : 'normal';
    $sfsi_form_field_fontcolor  = isset( $_POST["sfsi_form_field_fontcolor"] ) ? sfsi_sanitize_hex_color($_POST["sfsi_form_field_fontcolor"] ) : '#000000';
    $sfsi_form_field_fontsize   = isset( $_POST["sfsi_form_field_fontsize"] ) ? intval($_POST["sfsi_form_field_fontsize"] ) : '14';
    $sfsi_form_field_fontalign  = isset( $_POST["sfsi_form_field_fontalign"] ) ? sanitize_text_field($_POST["sfsi_form_field_fontalign"] ) : 'center';

    $sfsi_form_button_text      = isset( $_POST["sfsi_form_button_text"] ) ? sanitize_text_field($_POST["sfsi_form_button_text"] ) : 'Subscribe';
    $sfsi_form_button_font      = isset( $_POST["sfsi_form_button_font"] ) ? sanitize_text_field($_POST["sfsi_form_button_font"] ) : 'Helvetica,Arial,sans-serif';
    $sfsi_form_button_fontstyle = isset( $_POST["sfsi_form_button_fontstyle"] ) ? sanitize_text_field($_POST["sfsi_form_button_fontstyle"] ) : 'bold';
    $sfsi_form_button_fontcolor = isset( $_POST["sfsi_form_button_fontcolor"] ) ? sfsi_sanitize_hex_color($_POST["sfsi_form_button_fontcolor"] ) : '#000000';
    $sfsi_form_button_fontsize  = isset( $_POST["sfsi_form_button_fontsize"] ) ? intval($_POST["sfsi_form_button_fontsize"] ) : '16';
    $sfsi_form_button_fontalign = isset( $_POST["sfsi_form_button_fontalign"] ) ? sanitize_text_field($_POST["sfsi_form_button_fontalign"] ) : 'center';
    $sfsi_form_button_background = isset( $_POST["sfsi_form_button_background"] ) ? sfsi_sanitize_hex_color($_POST["sfsi_form_button_background"] ) : '#dedede';

    /* icons pop options */
    $up_option8 = array(
        'sfsi_form_adjustment'      =>  sanitize_text_field($sfsi_form_adjustment),
        'sfsi_form_height'          =>  intval($sfsi_form_height),
        'sfsi_form_width'           =>  intval($sfsi_form_width),
        'sfsi_form_border'          =>  sanitize_text_field($sfsi_form_border),
        'sfsi_form_border_thickness' =>  intval($sfsi_form_border_thickness),
        'sfsi_form_border_color'    =>  sfsi_sanitize_hex_color($sfsi_form_border_color),
        'sfsi_form_background'      =>  sfsi_sanitize_hex_color($sfsi_form_background),

        'sfsi_form_heading_text'    =>  sanitize_text_field(stripslashes($sfsi_form_heading_text)),
        'sfsi_form_heading_font'    =>  sanitize_text_field($sfsi_form_heading_font),
        'sfsi_form_heading_fontstyle' => sanitize_text_field($sfsi_form_heading_fontstyle),
        'sfsi_form_heading_fontcolor' => sfsi_sanitize_hex_color($sfsi_form_heading_fontcolor),
        'sfsi_form_heading_fontsize' =>  intval($sfsi_form_heading_fontsize),
        'sfsi_form_heading_fontalign' => sanitize_text_field($sfsi_form_heading_fontalign),

        'sfsi_form_field_text'      =>  sanitize_text_field(stripslashes($sfsi_form_field_text)),
        'sfsi_form_field_font'      =>  sanitize_text_field($sfsi_form_field_font),
        'sfsi_form_field_fontstyle' =>  sanitize_text_field($sfsi_form_field_fontstyle),
        'sfsi_form_field_fontcolor' =>  sfsi_sanitize_hex_color($sfsi_form_field_fontcolor),
        'sfsi_form_field_fontsize'  =>  intval($sfsi_form_field_fontsize),
        'sfsi_form_field_fontalign' =>  sanitize_text_field($sfsi_form_field_fontalign),

        'sfsi_form_button_text'     =>  sanitize_text_field(stripslashes($sfsi_form_button_text)),
        'sfsi_form_button_font'     =>  sanitize_text_field($sfsi_form_button_font),
        'sfsi_form_button_fontstyle' =>  sanitize_text_field($sfsi_form_button_fontstyle),
        'sfsi_form_button_fontcolor' =>  sfsi_sanitize_hex_color($sfsi_form_button_fontcolor),
        'sfsi_form_button_fontsize' =>  intval($sfsi_form_button_fontsize),
        'sfsi_form_button_fontalign' =>  sanitize_text_field($sfsi_form_button_fontalign),
        'sfsi_form_button_background' => sfsi_sanitize_hex_color($sfsi_form_button_background),
    );
    update_option('sfsi_section8_options', serialize($up_option8));
    header('Content-Type: application/json');
    echo json_encode(array("success"));
    exit;
}


/** Function sfsiActivateFooter() called by wp_ajax hooks: {'activateFooter'} **/
/** Parameters found in function sfsiActivateFooter(): {"post": ["nonce"]} **/
function sfsiActivateFooter()
{
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), "active_footer")) {
        echo json_encode(array('res' => 'wrong_nonce'));
        exit;
    }
    if (!current_user_can('manage_options')) {
        echo json_encode(array('res' => 'not allowed'));
        die();
    }

    update_option('sfsi_footer_sec', 'yes');
    echo json_encode(array('res' => 'success'));
    exit;
}


/** Function nonce() called by wp_ajax hooks: {'tifm_save_decision'} **/
/** No function found :-/ **/


/** Function sfsi_bannerOption() called by wp_ajax hooks: {'bannerOption'} **/
/** Parameters found in function sfsi_bannerOption(): {"post": ["nonce", "domain"]} **/
function sfsi_bannerOption()
{

    error_reporting(0);
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), "bannerOption")) {
        echo json_encode(array("wrong_nonce"));
        exit;
    }
    if (!current_user_can('manage_options')) {
        echo json_encode(array('res' => 'not allowed'));
        die();
    }

    if (get_option("show_new_notification") !== "") {

        $objThemeCheck = new sfsi_ThemeCheck();

        $domainname     = isset( $_POST['domain'] ) ? sanitize_text_field($_POST['domain'] ) : $objThemeCheck->sfsi_plus_getdomain(get_bloginfo('url'));

        // Get all themes data which incudes nobrainer 
        $themeDataArr = $objThemeCheck->sfsi_plus_get_themeData();
        $matchFound = false;
        $sfsi_icons_url = " https://www.ultimatelysocial.com/";
        foreach ($themeDataArr as $themeDataObj) {

            if (isset( $themeDataObj->themeName) && strlen($themeDataObj->themeName) > 0) {

                $themeName          = $themeDataObj->themeName;
                $noBrainerKeywords  = $themeDataObj->noBrainerKeywords;
                $separateKeywords   = $themeDataObj->separateKeywords;
                $negativeKeywords   = $themeDataObj->negativeKeywords;
                $noBrainerAndSeparateKeywords = array_merge($noBrainerKeywords, $separateKeywords);
                if ($objThemeCheck->sfsi_plus_check_type_of_websiteWithNoBrainerAndSeparateAndNegativeKeywords($themeName, $noBrainerKeywords, $separateKeywords, $noBrainerAndSeparateKeywords, $negativeKeywords, $domainname) == $themeName) {
                    $matchFound = true;

                    $themeName = strtolower($themeName);
                    $themeUrl = $sfsi_icons_url . $themeName;
                    if (get_option("show_new_notification") == "yes") {
                        $objThemeCheck->sfsi_plus_bannereHtml(
                            $themeDataObj->headline,
                            $themeUrl,
                            SFSI_PLUGURL . 'images/website_theme/' . $themeName . '.png',
                            $themeDataObj->bottomtext
                        );
                    }

                    $objThemeCheck->sfsi_plus_bannereHtml_main(
                        $themeName,
                        $themeUrl,
                        SFSI_PLUGURL . 'images/website_theme/' . $themeName . '.png',
                        $themeDataObj->bottomtext
                    );

                    break;
                }
            }
        }

        if (!$matchFound) {
            foreach ($themeDataArr as $themeDataObj) {

                if (isset( $themeDataObj->themeName) && strlen($themeDataObj->themeName) > 0) {

                    $themeName          = $themeDataObj->themeName;
                    $noBrainerKeywords  = $themeDataObj->noBrainerKeywords;
                    $separateKeywords   = $themeDataObj->separateKeywords;
                    $negativeKeywords   = $themeDataObj->negativeKeywords;
                    $noBrainerAndSeparateKeywords = array_merge($noBrainerKeywords, $separateKeywords);
                    if ($objThemeCheck->sfsi_plus_check_type_of_metaTitleWithNoBrainerAndSeparateAndNegativeKeywords($themeName, $noBrainerKeywords, $separateKeywords, $noBrainerAndSeparateKeywords, $negativeKeywords, $domainname) == $themeName) {
                        $matchFound = true;

                        $themeName = strtolower($themeName);
                        $themeUrl = $sfsi_icons_url . $themeName;
                        if (get_option("show_new_notification") == "yes") {
                            $objThemeCheck->sfsi_plus_bannereHtml(
                                $themeDataObj->headline,
                                $themeUrl,
                                SFSI_PLUGURL . 'images/website_theme/' . $themeName . '.png',
                                $themeDataObj->bottomtext
                            );
                        }
                        $objThemeCheck->sfsi_plus_bannereHtml_main(
                            $themeName,
                            $themeUrl,
                            SFSI_PLUGURL . 'images/website_theme/' . $themeName . '.png',
                            $themeDataObj->bottomtext
                        );

                        break;
                    }
                }
            }
        }
        if (!$matchFound) {
            foreach ($themeDataArr as $themeDataObj) {

                if (isset( $themeDataObj->themeName) && strlen($themeDataObj->themeName) > 0) {

                    $themeName          = $themeDataObj->themeName;
                    $noBrainerKeywords  = $themeDataObj->noBrainerKeywords;
                    $separateKeywords   = $themeDataObj->separateKeywords;
                    $negativeKeywords   = $themeDataObj->negativeKeywords;
                    $noBrainerAndSeparateKeywords = array_merge($noBrainerKeywords, $separateKeywords);
                    if ($objThemeCheck->sfsi_plus_check_type_of_metaKeywordsWithNoBrainerAndSeparateAndNegativeKeywords($themeName, $noBrainerKeywords, $separateKeywords, $noBrainerAndSeparateKeywords, $negativeKeywords, $domainname) == $themeName) {
                        $matchFound = true;

                        $themeName = strtolower($themeName);
                        $themeUrl = $sfsi_icons_url . $themeName;
                        if (get_option("show_new_notification") == "yes") {
                            $objThemeCheck->sfsi_plus_bannereHtml(
                                $themeDataObj->headline,
                                $themeUrl,
                                SFSI_PLUGURL . 'images/website_theme/' . $themeName . '.png',
                                $themeDataObj->bottomtext
                            );
                        }
                        $objThemeCheck->sfsi_plus_bannereHtml_main(
                            $themeName,
                            $themeUrl,
                            SFSI_PLUGURL . 'images/website_theme/' . $themeName . '.png',
                            $themeDataObj->bottomtext
                        );

                        break;
                    }
                }
            }
        }
        if (!$matchFound) {
            foreach ($themeDataArr as $themeDataObj) {

                if (isset( $themeDataObj->themeName) && strlen($themeDataObj->themeName) > 0) {

                    $themeName          = $themeDataObj->themeName;
                    $noBrainerKeywords  = $themeDataObj->noBrainerKeywords;
                    $separateKeywords   = $themeDataObj->separateKeywords;
                    $negativeKeywords   = $themeDataObj->negativeKeywords;
                    $noBrainerAndSeparateKeywords = array_merge($noBrainerKeywords, $separateKeywords);
                    if ($objThemeCheck->sfsi_plus_check_type_of_metaDescriptionWithNoBrainerAndSeparateAndNegativeKeywords($themeName, $noBrainerKeywords, $separateKeywords, $noBrainerAndSeparateKeywords, $negativeKeywords, $domainname) == $themeName) {
                        $matchFound = true;

                        $themeName = strtolower($themeName);
                        $themeUrl = $sfsi_icons_url . $themeName;
                        if (get_option("show_new_notification") == "yes") {
                            $objThemeCheck->sfsi_plus_bannereHtml(
                                $themeDataObj->headline,
                                $themeUrl,
                                SFSI_PLUGURL . 'images/website_theme/' . $themeName . '.png',
                                $themeDataObj->bottomtext
                            );
                        }
                        $objThemeCheck->sfsi_plus_bannereHtml_main(
                            $themeName,
                            $themeUrl,
                            SFSI_PLUGURL . 'images/website_theme/' . $themeName . '.png',
                            $themeDataObj->bottomtext
                        );

                        break;
                    }
                }
            }
        }

        // if(!$matchFound){

        //       echo '<div class="sfsi_new_notification_cat">
        //               <div class="sfsi_new_notification_header_cat">
        //                   <h1>New feature: Tailored icons</h1>
        //                   <h3>The <a href="https://www.ultimatelysocial.com/themed-icons-search/?utm_source=usmi_settings_page&utm_campaign=themed_icons_search&utm_medium=banner" target="_blank">Premium Plugin</a> Includes these icons...</h3>
        //                   <div class="sfsi_new_notification_cross_cat">X</div>
        //               </div>

        //               <div class="sfsi_new_notification_body_link_cat">
        //                   <a class ="tailored_icons_img" href="https://www.ultimatelysocial.com/themed-icons-search/?utm_source=usmi_settings_page&utm_campaign=themed_icons_search&utm_medium=banner" target="_blank">
        //                       <div class="sfsi_new_notification_body_cat">
        //                           <div class="sfsi_new_notification_image_cat">
        //                                  <img src="'.SFSI_PLUGURL.'images/WPPlugin_V3.png" id="newImg" />
        //                           </div>
        //                       </div>
        //                   </a>
        //                   <div class="bottom_text">
        //                       <a target="_blank" href="https://www.ultimatelysocial.com/themed-icons-search/?utm_source=usmi_settings_page&utm_campaign=themed_icons_search&utm_medium=banner" >
        //                           See more-themed-icons >
        //                       </a>
        //                   </div>    
        //               </div>
        //           </div>';   
        // }

        echo '<script type="text/javascript">
                jQuery("body").on("click", ".sfsi_new_notification_cross", function(){
                    SFSI.ajax({
                        url:sfsi_icon_ajax_object.ajax_url,
                        type:"post",
                        data: {action: "new_notification_read", nonce:"' . (wp_create_nonce('new_notification_read')) . '"},
                        success:function(msg){
                            if(jQuery.trim(msg) == "success")
                            {
                                jQuery(".sfsi_new_notification").hide("fast");
                            }
                        }
                    });
                });
                jQuery("body").on("click", ".sfsi_new_notification_cross_cat", function(){
                    SFSI.ajax({
                        url:sfsi_icon_ajax_object.ajax_url,
                        type:"post",
                        data: {action: "new_notification_read", nonce:"' . (wp_create_nonce('new_notification_read')) . '"},
                        success:function(msg){
                            if(jQuery.trim(msg) == "success")
                            {
                                jQuery(".sfsi_new_notification_cat").hide("fast");
                            }
                        }
                    });
                });
        </script>';
    }
    die();
}


/** Function sfsi_deleteIcons() called by wp_ajax hooks: {'deleteIcons'} **/
/** Parameters found in function sfsi_deleteIcons(): {"post": ["nonce", "icon_name"], "server": ["DOCUMENT_ROOT"]} **/
function sfsi_deleteIcons()
{
	if ( !wp_verify_nonce( sanitize_text_field($_POST['nonce']), "deleteIcons")) {
		echo  json_encode(array('res'=>"error")); exit;
	}
    if(!current_user_can('manage_options')){ echo json_encode(array('res'=>'not allowed'));die(); }
	
   if(isset($_POST['icon_name']) && !empty($_POST['icon_name']))
   {
       /* get icons details to delete it from plugin folder */ 
       $custom_icon_name= sanitize_text_field($_POST['icon_name']);
       preg_match_all('/\d+/', $custom_icon_name, $custom_icon_numbers);
       $custom_icon_number =    count($custom_icon_numbers)>0?((is_array($custom_icon_numbers[0])&&count($custom_icon_numbers[0])>0)?$custom_icon_numbers[0][0]:0):0;
       $sec_options1	= (get_option('sfsi_section1_options',false)) ? maybe_unserialize(get_option('sfsi_section1_options',false)) : array() ;
       $sec_options2	= (get_option('sfsi_section2_options',false)) ? maybe_unserialize(get_option('sfsi_section2_options',false)) : array() ;
       $up_icons		= (is_array(unserialize($sec_options1['sfsi_custom_files']))) ? unserialize($sec_options1['sfsi_custom_files']) : array();
       $icons_links		= (is_array(unserialize($sec_options2['sfsi_CustomIcon_links']))) ? unserialize($sec_options2['sfsi_CustomIcon_links']) : array();
       $icon_url=$up_icons[$custom_icon_number];  
       $url_info=  pathinfo($icon_url);      
	   // Changes By {Monad}
	   /*if(is_file(SFSI_DOCROOT.'/images/custom_icons/'.$path['basename']))
	   {
		  
        	unlink(SFSI_DOCROOT.'/images/custom_icons/'.$path['basename']);
       }*/
	    $imgpath = parse_url($icon_url, PHP_URL_PATH);

		if(is_file($_SERVER['DOCUMENT_ROOT'] . $imgpath))
		{
		   unlink($_SERVER['DOCUMENT_ROOT'] . $imgpath);
		}
	   
		if(isset($up_icons[$custom_icon_number]))
		{
			 unset($up_icons[$custom_icon_number]);
			 unset($icons_links[$custom_icon_number]);
		}
		else
		{
		  	unset($up_icons[0]);
			unset($icons_links[0]);
		}        
        
		/* update database after delete */
	 	$sec_options1['sfsi_custom_files']=serialize($up_icons);
        $sec_options2['sfsi_CustomIcon_links']=serialize($icons_links);
         
        end($up_icons);
        $key=(key($up_icons))? key($up_icons) :$custom_icon_number;
        $total_uploads=(isset($up_icons) && is_array($up_icons))?count($up_icons):0;
         
        update_option('sfsi_section1_options',serialize($sec_options1));
        update_option('sfsi_section2_options',serialize($sec_options2));
          
       	die(json_encode(array('res'=>'success','last_index'=>$key,'total_up'=>$total_uploads)));
   } 
}


/** Function sfsi_dismiss_addthhis_removal_notice() called by wp_ajax hooks: {'sfsi_dismiss_addThis_icon_notice'} **/
/** Parameters found in function sfsi_dismiss_addthhis_removal_notice(): {"post": ["nonce"]} **/
function sfsi_dismiss_addthhis_removal_notice()
{

    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), "sfsi_dismiss_addThis_icon_notice")) {

        echo json_encode(array('res' => "error"));
        exit;
    }

    if (!current_user_can('manage_options')) {
        echo json_encode(array('res' => 'not allowed'));
        die();
    }

    echo (string)update_option('sfsi_addThis_icon_removal_notice_dismissed', true);

    die;
}


/** Function sfsiremoveFooter() called by wp_ajax hooks: {'removeFooter'} **/
/** Parameters found in function sfsiremoveFooter(): {"post": ["nonce"]} **/
function sfsiremoveFooter()
{
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), "remove_footer")) {
        echo json_encode(array('res' => 'wrong_nonce'));
        exit;
    }
    if (!current_user_can('manage_options')) {
        echo json_encode(array('res' => 'not allowed'));
        die();
    }

    update_option('sfsi_footer_sec', 'no');
    echo json_encode(array('res' => 'success'));
    exit;
}


/** Function sfsi_banner_global_shares() called by wp_ajax hooks: {'sfsi_banner_global_shares'} **/
/** Parameters found in function sfsi_banner_global_shares(): {"post": ["nonce", "sfsi_banner_global_shares"]} **/
function sfsi_banner_global_shares()
{
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'sfsi_banner_global_shares')) return wp_send_json_error();
    if (!current_user_can('manage_options')) return wp_send_json_error();
    
    $sfsi_banner_global_shares_value   = isset( $_POST["sfsi_banner_global_shares"] ) ? sanitize_text_field($_POST["sfsi_banner_global_shares"]) : '';
    $sfsi_banner_global_shares = maybe_unserialize(get_option('sfsi_banner_global_shares', false));
    $sfsi_banner_global_shares['timestamp'] = $sfsi_banner_global_shares_value;
    update_option('sfsi_banner_global_shares',  serialize($sfsi_banner_global_shares));
    echo json_encode(array("success"));
    exit;
}


/** Function handle_installation() called by wp_ajax hooks: {'inisev_installation', 'inisev_installation_widget'} **/
/** Parameters found in function handle_installation(): {"post": ["slug"]} **/
function handle_installation() {

          if (check_ajax_referer('inisev_carousel', 'nonce', false) === false) {
            return wp_send_json_error();
          }

          if (!current_user_can('install_plugins')) {
            return wp_send_json_error();
          }

          // Handle the slug and install the plugin
          $slug = sanitize_text_field($_POST['slug']);
          if ($slug === 'usm') {

            $this->install($this->usm_slug, 'ultimate-social-media-icons');

          } elseif ($slug === 'bmi') {

            $this->install($this->bmi_slug, 'backup-backup');

          } elseif ($slug === 'cdp') {

            $this->install($this->cdp_slug, 'copy-delete-posts');

          } elseif ($slug === 'mpu') {

            $this->install($this->mpu_slug, 'pop-up-pop-up');

          } elseif ($slug == 'redi') {

            $this->install($this->redi_slug, 'redirect-redirection');

            // Anything else error
          } else wp_send_json_error();

        }


/** Function sfsi_get_feed_id() called by wp_ajax hooks: {'sfsi_get_feed_id'} **/
/** Parameters found in function sfsi_get_feed_id(): {"post": ["nonce"]} **/
function sfsi_get_feed_id()
{
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), "sfsi_get_feed_id")) {
        echo json_encode(array('res' => 'wrong_nonce'));
        exit;
    }
    if (!current_user_can('manage_options')) {
        echo json_encode(array("res" => "Failed", 'message' => "You should be admin to take this action"));
        exit;
    }
    $feed_id = sanitize_text_field(get_option('sfsi_feed_id'));
    if ("" == $feed_id) {
        $sfsiId = SFSI_getFeedUrl();
        update_option('sfsi_feed_id', sanitize_text_field($sfsiId->feed_id));
        update_option('sfsi_redirect_url', sanitize_text_field($sfsiId->redirect_url));
        echo json_encode(array("res" => "success", 'feed_id' => $sfsiId->feed_id));
        sfsi_getverification_code();
        exit;
    } else {
        echo json_encode(array("res" => "success", "feed_id" => $feed_id));
        exit;
    }
    wp_die();
}


/** Function sfsi_options_updater2() called by wp_ajax hooks: {'updateSrcn2'} **/
/** Parameters found in function sfsi_options_updater2(): {"post": ["nonce", "sfsi_rss_url", "sfsi_rss_icons", "sfsi_facebookPage_option", "sfsi_facebookPage_url", "sfsi_facebookLike_option", "sfsi_facebookShare_option", "sfsi_threadsShare_option", "sfsi_blueskyShare_option", "sfsi_twitter_followme", "sfsi_twitter_followUserName", "sfsi_twitter_aboutPage", "sfsi_twitter_page", "sfsi_twitter_pageURL", "sfsi_twitter_aboutPageText", "sfsi_youtube_pageUrl", "sfsi_youtube_page", "sfsi_youtube_follow", "sfsi_pinterest_page", "sfsi_pinterest_pageUrl", "sfsi_pinterest_pingBlog", "sfsi_instagram_pageUrl", "sfsi_ria_pageUrl", "sfsi_inha_pageUrl", "sfsi_linkedin_page", "sfsi_linkedin_pageURL", "sfsi_linkedin_follow", "sfsi_linkedin_followCompany", "sfsi_linkedin_SharePage", "sfsi_linkedin_recommendBusines", "sfsi_linkedin_recommendCompany", "sfsi_linkedin_recommendProductId", "sfsi_youtubeusernameorid", "sfsi_ytube_user", "sfsi_ytube_chnlid", "sfsi_telegram_msg_option", "sfsi_telegram_page", "sfsi_telegram_pageURL", "sfsi_telegram_message", "sfsi_telegram_username", "sfsi_weibo_page", "sfsi_weibo_pageURL", "sfsi_vk_page", "sfsi_vk_pageURL", "sfsi_vk_share", "sfsi_ok_page", "sfsi_ok_pageURL", "sfsi_wechat_follow", "sfsi_wechat_share", "sfsi_snapchat_pageURL", "sfsi_tiktok_page", "sfsi_tiktok_pageURL", "sfsi_reddit_pageShare", "sfsi_reddit_page_visit", "sfsi_fbmessenger_share", "sfsi_fbmessenger_contact", "sfsi_whatsapp_msg", "sfsi_whatsapp_share", "sfsi_mastodon_page", "sfsi_mastodon_pageURL", "sfsi_custom_links"]} **/
function sfsi_options_updater2()
{

    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), "update_step2")) {
        echo json_encode(array("wrong_nonce"));
        exit;
    }
    if (!current_user_can('manage_options')) {
        echo json_encode(array('res' => 'not allowed'));
        die();
    }

    $sfsi_rss_url                   = isset( $_POST["sfsi_rss_url"] ) ? esc_url(trim($_POST["sfsi_rss_url"] )) : '';
    $sfsi_rss_icons                 = isset( $_POST["sfsi_rss_icons"] ) ? sanitize_text_field($_POST["sfsi_rss_icons"] ) : 'email';

    $sfsi_facebookPage_option       = isset( $_POST["sfsi_facebookPage_option"] ) ? sanitize_text_field($_POST["sfsi_facebookPage_option"] ) : 'no';
    $sfsi_facebookPage_url          = isset( $_POST["sfsi_facebookPage_url"] ) ? esc_url(trim($_POST["sfsi_facebookPage_url"] )) : '';
    $sfsi_facebookLike_option       = isset( $_POST["sfsi_facebookLike_option"] ) ? sanitize_text_field($_POST["sfsi_facebookLike_option"] ) : 'no';
    $sfsi_facebookShare_option      = isset( $_POST["sfsi_facebookShare_option"] ) ? sanitize_text_field($_POST["sfsi_facebookShare_option"] ) : 'no';

	$sfsi_threadsShare_option      = isset( $_POST["sfsi_threadsShare_option"] ) ? sanitize_text_field($_POST["sfsi_threadsShare_option"] ) : 'no';
    $sfsi_blueskyShare_option      = isset( $_POST["sfsi_blueskyShare_option"] ) ? sanitize_text_field($_POST["sfsi_blueskyShare_option"] ) : 'no';

    $sfsi_twitter_followme          = isset( $_POST["sfsi_twitter_followme"] ) ? sanitize_text_field($_POST["sfsi_twitter_followme"] ) : 'no';
    $sfsi_twitter_followUserName    = isset( $_POST["sfsi_twitter_followUserName"] ) ? sanitize_text_field(trim($_POST["sfsi_twitter_followUserName"] )) : '';
    $sfsi_twitter_aboutPage         = isset( $_POST["sfsi_twitter_aboutPage"] ) ? sanitize_text_field($_POST["sfsi_twitter_aboutPage"] ) : 'no';
    $sfsi_twitter_page              = isset( $_POST["sfsi_twitter_page"] ) ? sanitize_text_field($_POST["sfsi_twitter_page"] ) : 'no';
    $sfsi_twitter_pageURL           = isset( $_POST["sfsi_twitter_pageURL"] ) ? esc_url(trim($_POST["sfsi_twitter_pageURL"] )) : '';
    $sfsi_twitter_aboutPageText     = isset( $_POST["sfsi_twitter_aboutPageText"] ) ? sanitize_text_field($_POST["sfsi_twitter_aboutPageText"] ) : '';
    $sfsi_youtube_pageUrl           = isset( $_POST["sfsi_youtube_pageUrl"] ) ? esc_url(trim($_POST["sfsi_youtube_pageUrl"] )) : '';
    $sfsi_youtube_page              = isset( $_POST["sfsi_youtube_page"] ) ? sanitize_text_field($_POST["sfsi_youtube_page"] ) : 'no';
    $sfsi_youtube_follow            = isset( $_POST["sfsi_youtube_follow"] ) ? sanitize_text_field($_POST["sfsi_youtube_follow"] ) : 'no';
    $sfsi_pinterest_page            = isset( $_POST["sfsi_pinterest_page"] ) ? sanitize_text_field($_POST["sfsi_pinterest_page"] ) : 'no';
    $sfsi_pinterest_pageUrl         = isset( $_POST["sfsi_pinterest_pageUrl"] ) ? esc_url(trim($_POST["sfsi_pinterest_pageUrl"] )) : '';
    $sfsi_pinterest_pingBlog        = isset( $_POST["sfsi_pinterest_pingBlog"] ) ? sanitize_text_field($_POST["sfsi_pinterest_pingBlog"] ) : 'no';

    $sfsi_instagram_pageUrl         = isset( $_POST["sfsi_instagram_pageUrl"] ) ? esc_url(trim($_POST["sfsi_instagram_pageUrl"] )) : '';
    $sfsi_ria_pageUrl         = isset( $_POST["sfsi_ria_pageUrl"] ) ? esc_url(trim($_POST["sfsi_ria_pageUrl"] )) : '';
    $sfsi_inha_pageUrl         = isset( $_POST["sfsi_inha_pageUrl"] ) ? esc_url(trim($_POST["sfsi_inha_pageUrl"] )) : '';

    $sfsi_linkedin_page             = isset( $_POST["sfsi_linkedin_page"] ) ? sanitize_text_field($_POST["sfsi_linkedin_page"] ) : 'no';
    $sfsi_linkedin_pageURL          = isset( $_POST["sfsi_linkedin_pageURL"] ) ? esc_url(trim($_POST["sfsi_linkedin_pageURL"] )) : '';
    $sfsi_linkedin_follow           = isset( $_POST["sfsi_linkedin_follow"] ) ? sanitize_text_field($_POST["sfsi_linkedin_follow"] ) : 'no';
    $sfsi_linkedin_followCompany    = isset( $_POST["sfsi_linkedin_followCompany"] ) ? intval(trim($_POST["sfsi_linkedin_followCompany"] )) : '';
    $sfsi_linkedin_SharePage        = isset( $_POST["sfsi_linkedin_SharePage"] ) ? sanitize_text_field($_POST["sfsi_linkedin_SharePage"] ) : 'no';
    $sfsi_linkedin_recommendBusines = isset( $_POST["sfsi_linkedin_recommendBusines"] ) ? sanitize_text_field($_POST["sfsi_linkedin_recommendBusines"] ) : 'no';
    $sfsi_linkedin_recommendCompany = isset( $_POST["sfsi_linkedin_recommendCompany"] ) ? sanitize_text_field(trim($_POST["sfsi_linkedin_recommendCompany"] )) : '';
    $sfsi_linkedin_recommendProductId = isset( $_POST["sfsi_linkedin_recommendProductId"] ) ? intval(trim($_POST["sfsi_linkedin_recommendProductId"] )) : '';

    $sfsi_youtubeusernameorid = isset( $_POST["sfsi_youtubeusernameorid"] ) ? sanitize_text_field(trim($_POST["sfsi_youtubeusernameorid"] )) : '';
    $sfsi_ytube_user          = isset( $_POST["sfsi_ytube_user"] ) ? sanitize_text_field($_POST["sfsi_ytube_user"]) : '';
    $sfsi_ytube_chnlid        = isset( $_POST["sfsi_ytube_chnlid"] ) ? sanitize_text_field($_POST["sfsi_ytube_chnlid"] ) : '';
    
    $sfsi_telegram_msg_option = isset( $_POST["sfsi_telegram_msg_option"] ) ? sanitize_text_field( $_POST["sfsi_telegram_msg_option"] ) : 'no';
    $sfsi_telegram_page       = isset( $_POST["sfsi_telegram_page"] ) ? sanitize_text_field($_POST["sfsi_telegram_page"] ) : '';
    $sfsi_telegram_pageURL    = isset( $_POST["sfsi_telegram_pageURL"] ) ? esc_url(trim($_POST["sfsi_telegram_pageURL"] )) : '';
    $sfsi_telegram_message    = isset( $_POST["sfsi_telegram_message"] ) ? sanitize_text_field($_POST["sfsi_telegram_message"] ) : '';
    $sfsi_telegram_username   = isset( $_POST["sfsi_telegram_username"] ) ? sanitize_text_field($_POST["sfsi_telegram_username"] ) : '';

    $sfsi_weibo_page          = isset( $_POST["sfsi_weibo_page"] ) ? sanitize_text_field($_POST["sfsi_weibo_page"] ) : '';
    $sfsi_weibo_pageURL       = isset( $_POST["sfsi_weibo_pageURL"] ) ? esc_url(trim($_POST["sfsi_weibo_pageURL"] )) : '';

    $sfsi_vk_page             = isset( $_POST["sfsi_vk_page"] ) ? sanitize_text_field($_POST["sfsi_vk_page"] ) : '';
    $sfsi_vk_pageURL          = isset( $_POST["sfsi_vk_pageURL"] ) ? esc_url(trim($_POST["sfsi_vk_pageURL"] )) : '';
    $sfsi_vk_share            = isset( $_POST["sfsi_vk_share"] ) ? sanitize_text_field($_POST["sfsi_vk_share"] ) : 'no';

    $sfsi_ok_page             = isset( $_POST["sfsi_ok_page"] ) ? sanitize_text_field($_POST["sfsi_ok_page"] ) : '';
    $sfsi_ok_pageURL          = isset( $_POST["sfsi_ok_pageURL"] ) ? esc_url(trim($_POST["sfsi_ok_pageURL"] )) : '';

    $sfsi_wechat_follow       = isset( $_POST["sfsi_wechat_follow"] ) ? sanitize_text_field($_POST["sfsi_wechat_follow"] ) : '';
    $sfsi_wechat_share        = isset( $_POST["sfsi_wechat_share"] ) ? sanitize_text_field($_POST["sfsi_wechat_share"] ) : 'no';

    $sfsi_snapchat_pageURL    = isset( $_POST["sfsi_snapchat_pageURL"] ) ? esc_url(trim($_POST["sfsi_snapchat_pageURL"] )) : '';

    $sfsi_tiktok_page         = isset( $_POST["sfsi_tiktok_page"] ) ? sanitize_text_field($_POST["sfsi_tiktok_page"] ) : 'no';
    $sfsi_tiktok_pageURL      = isset( $_POST["sfsi_tiktok_pageURL"] ) ? esc_url(trim($_POST["sfsi_tiktok_pageURL"] )) : '';

    $sfsi_reddit_pageShare    = isset( $_POST["sfsi_reddit_pageShare"] ) ? sanitize_text_field($_POST["sfsi_reddit_pageShare"] ) : '';
    $sfsi_reddit_page_visit   = isset( $_POST["sfsi_reddit_page_visit"] ) ? sanitize_text_field($_POST["sfsi_reddit_page_visit"] ) : 'no';

    $sfsi_fbmessenger_share   = isset( $_POST["sfsi_fbmessenger_share"] ) ? sanitize_text_field($_POST["sfsi_fbmessenger_share"] ) : '';
    $sfsi_fbmessenger_contact = isset( $_POST["sfsi_fbmessenger_contact"] ) ? sanitize_text_field($_POST["sfsi_fbmessenger_contact"] ) : 'no';

    $sfsi_whatsapp_msg        = isset( $_POST["sfsi_whatsapp_msg"] ) ? sanitize_text_field($_POST["sfsi_whatsapp_msg"] ) : '';
    $sfsi_whatsapp_share      = isset( $_POST["sfsi_whatsapp_share"] ) ? sanitize_text_field($_POST["sfsi_whatsapp_share"] ) : '';

    $sfsi_mastodon_page       = isset( $_POST["sfsi_mastodon_page"] ) ? sanitize_text_field($_POST["sfsi_mastodon_page"] ) : '';
    $sfsi_mastodon_pageURL    = isset( $_POST["sfsi_mastodon_pageURL"] ) ? esc_url(trim($_POST["sfsi_mastodon_pageURL"] )) : '';

    /*
     * Escape custom icons url
     */
    if (
        isset( $_POST["sfsi_custom_links"] ) &&
        !empty($_POST["sfsi_custom_links"] )
    ) {
        $esacpedUrls = array();
        $sfsi_customIconsUrl = $_POST["sfsi_custom_links"];

        foreach ($sfsi_customIconsUrl as $key => $sfsi_customIconUrl) {
            $esacpedUrls[sanitize_text_field($key)] = sanitize_url($sfsi_customIconUrl);
        }
    } else {
        $esacpedUrls = '';
    }
    $sfsi_CustomIcon_links    = isset( $_POST["sfsi_custom_links"] ) ? serialize($esacpedUrls) : '';

    $option2 = maybe_unserialize(get_option('sfsi_section2_options', false));
    $up_option2 = array(
        'sfsi_rss_url'              => esc_url($sfsi_rss_url),
        'sfsi_rss_blogName'         => '',
        'sfsi_rss_blogEmail'        => '',
        'sfsi_rss_icons'            => sanitize_text_field($sfsi_rss_icons),
        'sfsi_email_url'            => esc_url($option2['sfsi_email_url'] ),
        /* facebook buttons options */
        'sfsi_facebookPage_option'  => sanitize_text_field($sfsi_facebookPage_option),
        'sfsi_facebookPage_url'     => esc_url($sfsi_facebookPage_url),
        'sfsi_facebookLike_option'  => sanitize_text_field($sfsi_facebookLike_option),
        'sfsi_facebookShare_option' => sanitize_text_field($sfsi_facebookShare_option),

        'sfsi_threadsShare_option' => sanitize_text_field($sfsi_threadsShare_option),
        'sfsi_blueskyShare_option' => sanitize_text_field($sfsi_blueskyShare_option),
        /* Twitter buttons options */
        'sfsi_twitter_followme'     => sanitize_text_field($sfsi_twitter_followme),
        'sfsi_twitter_followUserName' => sanitize_text_field($sfsi_twitter_followUserName),
        'sfsi_twitter_aboutPage'    => sanitize_text_field($sfsi_twitter_aboutPage),
        'sfsi_twitter_page'         => sanitize_text_field($sfsi_twitter_page),
        'sfsi_twitter_pageURL'      => esc_url($sfsi_twitter_pageURL),
        'sfsi_twitter_aboutPageText' => sanitize_text_field($sfsi_twitter_aboutPageText),
        /* youtube options */
        'sfsi_youtube_pageUrl'      => esc_url($sfsi_youtube_pageUrl),
        'sfsi_youtube_page'         => sanitize_text_field($sfsi_youtube_page),
        'sfsi_youtube_follow'       => sanitize_text_field($sfsi_youtube_follow),
        'sfsi_youtubeusernameorid'  => sanitize_text_field($sfsi_youtubeusernameorid),
        'sfsi_ytube_user'           => sanitize_text_field($sfsi_ytube_user),
        'sfsi_ytube_chnlid'         => sanitize_text_field($sfsi_ytube_chnlid),

        /* pinterest options */
        'sfsi_pinterest_page'       => sanitize_text_field($sfsi_pinterest_page),
        'sfsi_pinterest_pageUrl'    => esc_url($sfsi_pinterest_pageUrl),
        'sfsi_pinterest_pingBlog'   => sanitize_text_field($sfsi_pinterest_pingBlog),
        /* instagram options */
        'sfsi_instagram_pageUrl'    => esc_url($sfsi_instagram_pageUrl),
        'sfsi_ria_pageUrl'    => esc_url($sfsi_ria_pageUrl),
        'sfsi_inha_pageUrl'    => esc_url($sfsi_inha_pageUrl),
        /* linkedIn options */
        'sfsi_linkedin_page'            => sanitize_text_field($sfsi_linkedin_page),
        'sfsi_linkedin_pageURL'         => esc_url($sfsi_linkedin_pageURL),
        'sfsi_linkedin_follow'          => sanitize_text_field($sfsi_linkedin_follow),
        'sfsi_linkedin_followCompany'   => intval($sfsi_linkedin_followCompany),
        'sfsi_linkedin_SharePage'       => sanitize_text_field($sfsi_linkedin_SharePage),
        'sfsi_linkedin_recommendBusines' => sanitize_text_field($sfsi_linkedin_recommendBusines),
        'sfsi_linkedin_recommendCompany' => sanitize_text_field($sfsi_linkedin_recommendCompany),
        'sfsi_linkedin_recommendProductId' => intval($sfsi_linkedin_recommendProductId),
        'sfsi_CustomIcon_links'         => $sfsi_CustomIcon_links,

        /* telegram options */
        'sfsi_telegram_msg_option'  => sanitize_text_field( $sfsi_telegram_msg_option ),
        'sfsi_telegram_page'        => sanitize_text_field( $sfsi_telegram_page ),
        'sfsi_telegram_pageURL'     => esc_url( $sfsi_telegram_pageURL ),

        'sfsi_telegram_message'     => sanitize_text_field($sfsi_telegram_message),
        'sfsi_telegram_username'    => sanitize_text_field($sfsi_telegram_username),

        /* weibo options */
        'sfsi_weibo_page'           => sanitize_text_field($sfsi_weibo_page),
        'sfsi_weibo_pageURL'        => esc_url($sfsi_weibo_pageURL),

        /* vk options */
        'sfsi_vk_page'              => sanitize_text_field($sfsi_vk_page),
        'sfsi_vk_pageURL'           => esc_url($sfsi_vk_pageURL),
        'sfsi_vk_share'             => sanitize_text_field($sfsi_vk_share),
        
        /* ok options */
        'sfsi_ok_page'              => sanitize_text_field($sfsi_ok_page),
        'sfsi_ok_pageURL'           => esc_url($sfsi_ok_pageURL),

        /* Snapchat options */
        'sfsi_snapchat_pageURL'     => esc_url($sfsi_snapchat_pageURL),
        
        /* Tiktok options */
        'sfsi_tiktok_page'          => sanitize_text_field($sfsi_tiktok_page),
        'sfsi_tiktok_pageURL'       => esc_url($sfsi_tiktok_pageURL),
        
        /* FB Messenger options */
        'sfsi_fbmessenger_share'    => sanitize_text_field($sfsi_fbmessenger_share),
        'sfsi_fbmessenger_contact'  => sanitize_text_field($sfsi_fbmessenger_contact),
        
        /* Reddit options */
        'sfsi_reddit_pageShare'     => sanitize_text_field($sfsi_reddit_pageShare),
        'sfsi_reddit_page_visit'    => sanitize_text_field($sfsi_reddit_page_visit),
         
        /* Whatsapp options */
        'sfsi_whatsapp_msg'         => sanitize_text_field($sfsi_whatsapp_msg),
        'sfsi_whatsapp_share'       => sanitize_text_field($sfsi_whatsapp_share),
         
        /* Wechat options */
        'sfsi_wechat_share'        => sanitize_text_field($sfsi_wechat_share),
        'sfsi_wechat_follow'       => sanitize_text_field($sfsi_wechat_follow),

        /* Mastodon options */
        'sfsi_mastodon_page'       => sanitize_text_field($sfsi_mastodon_page),
        'sfsi_mastodon_pageURL'    => esc_url($sfsi_mastodon_pageURL),

    );

    $option4 = maybe_unserialize(get_option('sfsi_section4_options', false));
    update_option('sfsi_section4_options',  serialize($option4));
    update_option('sfsi_section2_options',  serialize($up_option2));

    header('Content-Type: application/json');
    echo json_encode(array("success"));
    exit;
}


/** Function sfsi_options_updater4() called by wp_ajax hooks: {'updateSrcn4'} **/
/** Parameters found in function sfsi_options_updater4(): {"post": ["nonce", "sfsi_display_counts", "sfsi_email_countsDisplay", "sfsi_email_countsFrom", "sfsi_email_manualCounts", "sfsi_rss_countsDisplay", "sfsi_rss_manualCounts", "sfsi_facebook_countsDisplay", "sfsi_facebook_countsFrom", "sfsi_facebook_enableCache", "sfsi_facebook_mypageCounts", "sfsi_facebook_manualCounts", "sfsi_facebook_PageLink", "sfsi_twitter_countsDisplay", "sfsi_twitter_countsFrom", "sfsi_twitter_manualCounts", "sfsi_threads_countsDisplay", "sfsi_threads_countsFrom", "sfsi_threads_manualCounts", "sfsi_bluesky_countsDisplay", "sfsi_bluesky_countsFrom", "sfsi_bluesky_manualCounts", "tw_consumer_key", "tw_consumer_secret", "tw_oauth_access_token", "tw_oauth_access_token_secret", "sfsi_linkedIn_countsDisplay", "sfsi_linkedIn_countsFrom", "sfsi_linkedIn_manualCounts", "ln_company", "ln_api_key", "ln_secret_key", "ln_oAuth_user_token", "sfsi_youtube_countsDisplay", "sfsi_youtube_countsFrom", "sfsi_youtube_manualCounts", "sfsi_youtube_user", "sfsi_youtube_channelId", "sfsi_pinterest_countsDisplay", "sfsi_pinterest_countsFrom", "sfsi_pinterest_manualCounts", "sfsi_pinterest_user", "sfsi_pinterest_board", "sfsi_instagram_countsDisplay", "sfsi_instagram_countsFrom", "sfsi_instagram_manualCounts", "sfsi_instagram_User", "sfsi_instagram_clientid", "sfsi_instagram_appurl", "sfsi_instagram_token", "sfsi_facebookPage_url", "sfsi_telegram_countsDisplay", "sfsi_telegram_manualCounts", "sfsi_vk_countsDisplay", "sfsi_vk_manualCounts", "sfsi_ok_countsDisplay", "sfsi_ok_manualCounts", "sfsi_weibo_countsDisplay", "sfsi_weibo_manualCounts", "sfsi_wechat_countsDisplay", "sfsi_wechat_manualCounts", "sfsi_whatsapp_countsDisplay", "sfsi_whatsapp_manualCounts", "sfsi_tiktok_countsDisplay", "sfsi_tiktok_manualCounts", "sfsi_ria_countsDisplay", "sfsi_ria_manualCounts", "sfsi_inha_countsDisplay", "sfsi_inha_manualCounts", "sfsi_mastodon_countsDisplay", "sfsi_mastodon_manualCounts", "sfsi_fbmessenger_countsDisplay", "sfsi_fbmessenger_manualCounts", "sfsi_snapchat_countsDisplay", "sfsi_snapchat_manualCounts", "sfsi_reddit_countsDisplay", "sfsi_reddit_manualCounts", "sfsi_round_counts", "sfsi_original_counts", "sfsi_responsive_share_count"]} **/
function sfsi_options_updater4()
{
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), "update_step4")) {
        echo json_encode(array("wrong_nonce"));
        exit;
    }
    if (!current_user_can('manage_options')) {
        echo json_encode(array('res' => 'not allowed'));
        die();
    }

    $sfsi_display_counts             = isset( $_POST["sfsi_display_counts"] ) ? sanitize_text_field($_POST["sfsi_display_counts"] ) : 'no';

    $sfsi_email_countsDisplay        = isset( $_POST["sfsi_email_countsDisplay"] ) ? sanitize_text_field($_POST["sfsi_email_countsDisplay"] ) : 'no';
    $sfsi_email_countsFrom           = isset( $_POST["sfsi_email_countsFrom"] ) ? sanitize_text_field($_POST["sfsi_email_countsFrom"] ) : 'manual';
    $sfsi_email_manualCounts         = isset( $_POST["sfsi_email_manualCounts"] ) ? intval(trim($_POST["sfsi_email_manualCounts"] )) : '';

    $sfsi_rss_countsDisplay          = isset( $_POST["sfsi_rss_countsDisplay"] ) ? sanitize_text_field($_POST["sfsi_rss_countsDisplay"] ) : 'no';
    $sfsi_rss_manualCounts           = isset( $_POST["sfsi_rss_manualCounts"] ) ? intval(trim($_POST["sfsi_rss_manualCounts"] )) : '';

    $sfsi_facebook_countsDisplay     = isset( $_POST["sfsi_facebook_countsDisplay"] ) ? sanitize_text_field($_POST["sfsi_facebook_countsDisplay"] ) : 'no';
    $sfsi_facebook_countsFrom        = isset( $_POST["sfsi_facebook_countsFrom"] ) ? sanitize_text_field($_POST["sfsi_facebook_countsFrom"] ) : 'manual';
    $sfsi_facebook_enableCache        = isset( $_POST["sfsi_facebook_enableCache"] ) && ($_POST["sfsi_facebook_enableCache"] === true || $_POST["sfsi_facebook_enableCache"] == "true") ? 'yes' : 'no';
    $sfsi_facebook_mypageCounts      = isset( $_POST["sfsi_facebook_mypageCounts"] ) ? sanitize_text_field(trim($_POST["sfsi_facebook_mypageCounts"] )) : '';
    $sfsi_facebook_manualCounts      = isset( $_POST["sfsi_facebook_manualCounts"] ) ? intval(trim($_POST["sfsi_facebook_manualCounts"] )) : '';
    $sfsi_facebook_PageLink          = isset( $_POST["sfsi_facebook_PageLink"] ) ? sanitize_text_field(trim($_POST["sfsi_facebook_PageLink"] )) : '';

    $sfsi_twitter_countsDisplay      = isset( $_POST["sfsi_twitter_countsDisplay"] ) ? sanitize_text_field($_POST["sfsi_twitter_countsDisplay"] ) : 'no';
    $sfsi_twitter_countsFrom         = isset( $_POST["sfsi_twitter_countsFrom"] ) ? sanitize_text_field($_POST["sfsi_twitter_countsFrom"] ) : 'manual';
    $sfsi_twitter_manualCounts       = isset( $_POST["sfsi_twitter_manualCounts"] ) ? intval(trim($_POST["sfsi_twitter_manualCounts"] )) : '';

    $sfsi_threads_countsDisplay      = isset( $_POST["sfsi_threads_countsDisplay"] ) ? sanitize_text_field($_POST["sfsi_threads_countsDisplay"] ) : 'no';
    $sfsi_threads_countsFrom         = isset( $_POST["sfsi_threads_countsFrom"] ) ? sanitize_text_field($_POST["sfsi_threads_countsFrom"] ) : 'manual';
    $sfsi_threads_manualCounts       = isset( $_POST["sfsi_threads_manualCounts"] ) ? intval(trim($_POST["sfsi_threads_manualCounts"] )) : '';

    $sfsi_bluesky_countsDisplay      = isset( $_POST["sfsi_bluesky_countsDisplay"] ) ? sanitize_text_field($_POST["sfsi_bluesky_countsDisplay"] ) : 'no';
    $sfsi_bluesky_countsFrom         = isset( $_POST["sfsi_bluesky_countsFrom"] ) ? sanitize_text_field($_POST["sfsi_bluesky_countsFrom"] ) : 'manual';
    $sfsi_bluesky_manualCounts       = isset( $_POST["sfsi_bluesky_manualCounts"] ) ? intval(trim($_POST["sfsi_bluesky_manualCounts"] )) : '';

	$tw_consumer_key                 = isset( $_POST["tw_consumer_key"] ) ? sanitize_text_field(trim($_POST["tw_consumer_key"] )) : '';
    $tw_consumer_secret              = isset( $_POST["tw_consumer_secret"] ) ? sanitize_text_field(trim($_POST["tw_consumer_secret"] )) : '';
    $tw_oauth_access_token           = isset( $_POST["tw_oauth_access_token"] ) ? sanitize_text_field(trim($_POST["tw_oauth_access_token"] )) : '';
    $tw_oauth_access_token_secret    = isset( $_POST["tw_oauth_access_token_secret"] ) ? sanitize_text_field(trim($_POST["tw_oauth_access_token_secret"] )) : '';

    $sfsi_linkedIn_countsDisplay     = isset( $_POST["sfsi_linkedIn_countsDisplay"] ) ? sanitize_text_field($_POST["sfsi_linkedIn_countsDisplay"] ) : 'no';
    $sfsi_linkedIn_countsFrom        = isset( $_POST["sfsi_linkedIn_countsFrom"] ) ? sanitize_text_field($_POST["sfsi_linkedIn_countsFrom"] ) : 'manual';
    $sfsi_linkedIn_manualCounts      = isset( $_POST["sfsi_linkedIn_manualCounts"] ) ? intval(trim($_POST["sfsi_linkedIn_manualCounts"] )) : '';
    $ln_company                      = isset( $_POST["ln_company"] ) ? sanitize_text_field(trim($_POST["ln_company"] )) : '';
    $ln_api_key                      = isset( $_POST["ln_api_key"] ) ? sanitize_text_field(trim($_POST["ln_api_key"] )) : '';
    $ln_secret_key                   = isset( $_POST["ln_secret_key"] ) ? sanitize_text_field(trim($_POST["ln_secret_key"] )) : '';
    $ln_oAuth_user_token             = isset( $_POST["ln_oAuth_user_token"] ) ? sanitize_text_field(trim($_POST["ln_oAuth_user_token"] )) : '';

    $sfsi_youtube_countsDisplay      = isset( $_POST["sfsi_youtube_countsDisplay"] ) ? sanitize_text_field($_POST["sfsi_youtube_countsDisplay"] ) : 'no';
    $sfsi_youtube_countsFrom         = isset( $_POST["sfsi_youtube_countsFrom"] ) ? sanitize_text_field($_POST["sfsi_youtube_countsFrom"] ) : 'manual';
    $sfsi_youtube_manualCounts       = isset( $_POST["sfsi_youtube_manualCounts"] ) ? intval($_POST["sfsi_youtube_manualCounts"] ) : '';
    $sfsi_youtube_user               = isset( $_POST["sfsi_youtube_user"] ) ? sanitize_text_field(trim($_POST["sfsi_youtube_user"] )) : '';
    $sfsi_youtube_channelId          = isset( $_POST["sfsi_youtube_channelId"] ) ? sanitize_text_field(trim($_POST["sfsi_youtube_channelId"] )) : '';

    $sfsi_pinterest_countsDisplay    = isset( $_POST["sfsi_pinterest_countsDisplay"] ) ? sanitize_text_field($_POST["sfsi_pinterest_countsDisplay"] ) : 'no';
    $sfsi_pinterest_countsFrom       = isset( $_POST["sfsi_pinterest_countsFrom"] ) ? sanitize_text_field($_POST["sfsi_pinterest_countsFrom"] ) : 'manual';
    $sfsi_pinterest_manualCounts     = isset( $_POST["sfsi_pinterest_manualCounts"] ) ? intval(trim($_POST["sfsi_pinterest_manualCounts"] )) : '';
    $sfsi_pinterest_user             = isset( $_POST["sfsi_pinterest_user"] ) ? sanitize_text_field(trim($_POST["sfsi_pinterest_user"] )) : '';
    $sfsi_pinterest_board            = isset( $_POST["sfsi_pinterest_board"] ) ? sanitize_text_field(trim($_POST["sfsi_pinterest_board"] )) : '';

    $sfsi_instagram_countsDisplay    = isset( $_POST["sfsi_instagram_countsDisplay"] ) ? sanitize_text_field($_POST["sfsi_instagram_countsDisplay"] ) : 'no';
    $sfsi_instagram_countsFrom       = isset( $_POST["sfsi_instagram_countsFrom"] ) ? sanitize_text_field($_POST["sfsi_instagram_countsFrom"] ) : 'manual';
    $sfsi_instagram_manualCounts     = isset( $_POST["sfsi_instagram_manualCounts"] ) ? intval(trim($_POST["sfsi_instagram_manualCounts"] )) : '';
    $sfsi_instagram_User             = isset( $_POST["sfsi_instagram_User"] ) ? sanitize_text_field($_POST["sfsi_instagram_User"] ) : '';
    $sfsi_instagram_clientid         = isset( $_POST["sfsi_instagram_clientid"] ) ? sanitize_text_field($_POST["sfsi_instagram_clientid"] ) : '';
    $sfsi_instagram_appurl           = isset( $_POST["sfsi_instagram_appurl"] ) ? sanitize_text_field($_POST["sfsi_instagram_appurl"] ) : '';
    $sfsi_instagram_token            = isset( $_POST["sfsi_instagram_token"] ) ? sanitize_text_field($_POST["sfsi_instagram_token"] ) : '';

    $sfsi_facebookPage_url           = isset( $_POST["sfsi_facebookPage_url"] ) ? sanitize_text_field(trim($_POST["sfsi_facebookPage_url"] )) : '';

    $sfsi_telegram_countsDisplay     = isset( $_POST["sfsi_telegram_countsDisplay"] ) ? sanitize_text_field($_POST["sfsi_telegram_countsDisplay"] ) : 'no';
    $sfsi_telegram_manualCounts      = isset( $_POST["sfsi_telegram_manualCounts"] ) ? intval(trim($_POST["sfsi_telegram_manualCounts"] )) : '';

    $sfsi_vk_countsDisplay          = isset( $_POST["sfsi_vk_countsDisplay"] ) ? sanitize_text_field($_POST["sfsi_vk_countsDisplay"] ) : 'no';
    $sfsi_vk_manualCounts           = isset( $_POST["sfsi_vk_manualCounts"] ) ? intval(trim($_POST["sfsi_vk_manualCounts"] )) : '';

    $sfsi_ok_countsDisplay          = isset( $_POST["sfsi_ok_countsDisplay"] ) ? sanitize_text_field($_POST["sfsi_ok_countsDisplay"] ) : 'no';
    $sfsi_ok_manualCounts           = isset( $_POST["sfsi_ok_manualCounts"] ) ? intval(trim($_POST["sfsi_ok_manualCounts"] )) : '';

    $sfsi_weibo_countsDisplay          = isset( $_POST["sfsi_weibo_countsDisplay"] ) ? sanitize_text_field($_POST["sfsi_weibo_countsDisplay"] ) : 'no';
    $sfsi_weibo_manualCounts           = isset( $_POST["sfsi_weibo_manualCounts"] ) ? intval(trim($_POST["sfsi_weibo_manualCounts"] )) : '';

    $sfsi_wechat_countsDisplay          = isset( $_POST["sfsi_wechat_countsDisplay"] ) ? sanitize_text_field($_POST["sfsi_wechat_countsDisplay"] ) : 'no';
    $sfsi_wechat_manualCounts           = isset( $_POST["sfsi_wechat_manualCounts"] ) ? intval(trim($_POST["sfsi_wechat_manualCounts"] )) : '';

    $sfsi_whatsapp_countsDisplay          = isset( $_POST["sfsi_whatsapp_countsDisplay"] ) ? sanitize_text_field($_POST["sfsi_whatsapp_countsDisplay"] ) : 'no';
    $sfsi_whatsapp_manualCounts           = isset( $_POST["sfsi_whatsapp_manualCounts"] ) ? intval(trim($_POST["sfsi_whatsapp_manualCounts"] )) : '';

    $sfsi_tiktok_countsDisplay          = isset( $_POST["sfsi_tiktok_countsDisplay"] ) ? sanitize_text_field($_POST["sfsi_tiktok_countsDisplay"] ) : 'no';
    $sfsi_tiktok_manualCounts           = isset( $_POST["sfsi_tiktok_manualCounts"] ) ? intval(trim($_POST["sfsi_tiktok_manualCounts"] )) : '';

    $sfsi_ria_countsDisplay          = isset( $_POST["sfsi_ria_countsDisplay"] ) ? sanitize_text_field($_POST["sfsi_ria_countsDisplay"] ) : 'no';
    $sfsi_ria_manualCounts           = isset( $_POST["sfsi_ria_manualCounts"] ) ? intval(trim($_POST["sfsi_ria_manualCounts"] )) : '';

    $sfsi_inha_countsDisplay          = isset( $_POST["sfsi_inha_countsDisplay"] ) ? sanitize_text_field($_POST["sfsi_inha_countsDisplay"] ) : 'no';
    $sfsi_inha_manualCounts           = isset( $_POST["sfsi_inha_manualCounts"] ) ? intval(trim($_POST["sfsi_inha_manualCounts"] )) : '';


    $sfsi_mastodon_countsDisplay          = isset( $_POST["sfsi_mastodon_countsDisplay"] ) ? sanitize_text_field($_POST["sfsi_mastodon_countsDisplay"] ) : 'no';
    $sfsi_mastodon_manualCounts           = isset( $_POST["sfsi_mastodon_manualCounts"] ) ? intval(trim($_POST["sfsi_mastodon_manualCounts"] )) : '';
    
    $sfsi_fbmessenger_countsDisplay          = isset( $_POST["sfsi_fbmessenger_countsDisplay"] ) ? sanitize_text_field($_POST["sfsi_fbmessenger_countsDisplay"] ) : 'no';
    $sfsi_fbmessenger_manualCounts           = isset( $_POST["sfsi_fbmessenger_manualCounts"] ) ? intval(trim($_POST["sfsi_fbmessenger_manualCounts"] )) : '';

    $sfsi_snapchat_countsDisplay          = isset( $_POST["sfsi_snapchat_countsDisplay"] ) ? sanitize_text_field($_POST["sfsi_snapchat_countsDisplay"] ) : 'no';
    $sfsi_snapchat_manualCounts           = isset( $_POST["sfsi_snapchat_manualCounts"] ) ? intval(trim($_POST["sfsi_snapchat_manualCounts"] )) : '';

    $sfsi_reddit_countsDisplay          = isset( $_POST["sfsi_reddit_countsDisplay"] ) ? sanitize_text_field($_POST["sfsi_reddit_countsDisplay"] ) : 'no';
    $sfsi_reddit_manualCounts           = isset( $_POST["sfsi_reddit_manualCounts"] ) ? intval(trim($_POST["sfsi_reddit_manualCounts"] )) : '';

    $sfsi_round_counts           = isset( $_POST["sfsi_round_counts"] ) ? sanitize_text_field($_POST["sfsi_round_counts"] ) : 'no';
    $sfsi_original_counts           = isset( $_POST["sfsi_original_counts"] ) ? sanitize_text_field($_POST["sfsi_original_counts"] ) : 'no';
    $sfsi_responsive_share_count               = isset( $_POST["sfsi_responsive_share_count"] ) ? sanitize_text_field($_POST["sfsi_responsive_share_count"] ) : 'no';

    $up_option4 = array(
        'sfsi_display_counts'       => sanitize_text_field($sfsi_display_counts),

        'sfsi_email_countsDisplay'  => sanitize_text_field($sfsi_email_countsDisplay),
        'sfsi_email_countsFrom'     => sanitize_text_field($sfsi_email_countsFrom),
        'sfsi_email_manualCounts'   => intval($sfsi_email_manualCounts),

        'sfsi_rss_countsDisplay'    => sanitize_text_field($sfsi_rss_countsDisplay),
        'sfsi_rss_manualCounts'     => intval($sfsi_rss_manualCounts),

        'sfsi_facebook_countsDisplay' => sanitize_text_field($sfsi_facebook_countsDisplay),
        'sfsi_facebook_countsFrom'  => sanitize_text_field($sfsi_facebook_countsFrom),
        'sfsi_facebook_enableCache'  => sanitize_text_field($sfsi_facebook_enableCache),
        'sfsi_facebook_mypageCounts' => sfsi_sanitize_field($sfsi_facebook_mypageCounts),
        'sfsi_facebook_manualCounts' => intval($sfsi_facebook_manualCounts),
        //'sfsi_facebook_PageLink'  => $sfsi_facebook_PageLink,

        'sfsi_twitter_countsDisplay' => sanitize_text_field($sfsi_twitter_countsDisplay),
        'sfsi_twitter_countsFrom'   => sanitize_text_field($sfsi_twitter_countsFrom),
        'sfsi_twitter_manualCounts' => intval($sfsi_twitter_manualCounts),
        'sfsi_threads_countsDisplay' => sanitize_text_field($sfsi_threads_countsDisplay),
        'sfsi_threads_countsFrom'   => sanitize_text_field($sfsi_threads_countsFrom),
        'sfsi_threads_manualCounts' => intval($sfsi_threads_manualCounts),
        'sfsi_bluesky_countsDisplay' => sanitize_text_field($sfsi_bluesky_countsDisplay),
        'sfsi_bluesky_countsFrom'   => sanitize_text_field($sfsi_bluesky_countsFrom),
        'sfsi_bluesky_manualCounts' => intval($sfsi_bluesky_manualCounts),
        'tw_consumer_key'           => sfsi_sanitize_field($tw_consumer_key),
        'tw_consumer_secret'        => sfsi_sanitize_field($tw_consumer_secret),
        'tw_oauth_access_token'     => sfsi_sanitize_field($tw_oauth_access_token),
        'tw_oauth_access_token_secret' => sfsi_sanitize_field($tw_oauth_access_token_secret),
        //'ln_company'              => $ln_company,
        //'ln_api_key'              => $ln_api_key,
        //'ln_secret_key'           => $ln_secret_key,
        //'ln_oAuth_user_token'     => $ln_oAuth_user_token,     
        'sfsi_linkedIn_countsDisplay' => sanitize_text_field($sfsi_linkedIn_countsDisplay),
        'sfsi_linkedIn_countsFrom'  => sanitize_text_field($sfsi_linkedIn_countsFrom),
        'sfsi_linkedIn_manualCounts' => intval($sfsi_linkedIn_manualCounts),

        'sfsi_youtube_countsDisplay' => sanitize_text_field($sfsi_youtube_countsDisplay),
        'sfsi_youtube_countsFrom'   => sanitize_text_field($sfsi_youtube_countsFrom),
        'sfsi_youtube_manualCounts' => intval($sfsi_youtube_manualCounts),
        'sfsi_youtube_user'         => sfsi_sanitize_field($sfsi_youtube_user),
        'sfsi_youtube_channelId'    => sfsi_sanitize_field($sfsi_youtube_channelId),

        'sfsi_pinterest_countsDisplay' => sanitize_text_field($sfsi_pinterest_countsDisplay),
        'sfsi_pinterest_countsFrom' => sanitize_text_field($sfsi_pinterest_countsFrom),
        'sfsi_pinterest_manualCounts' => intval($sfsi_pinterest_manualCounts),
        //'sfsi_pinterest_user'     => $sfsi_pinterest_user,     
        //'sfsi_pinterest_board'    => $sfsi_pinterest_board,

        'sfsi_instagram_countsFrom' => sanitize_text_field($sfsi_instagram_countsFrom),
        'sfsi_instagram_countsDisplay' => sanitize_text_field($sfsi_instagram_countsDisplay),
        'sfsi_instagram_manualCounts' => intval($sfsi_instagram_manualCounts),
        'sfsi_instagram_User'       => sanitize_text_field($sfsi_instagram_User),
        'sfsi_instagram_clientid'    => sanitize_text_field($sfsi_instagram_clientid),
        'sfsi_instagram_appurl'      => sanitize_text_field($sfsi_instagram_appurl),
        'sfsi_instagram_token'       => sanitize_text_field($sfsi_instagram_token),

        'sfsi_telegram_countsDisplay'    => sanitize_text_field($sfsi_telegram_countsDisplay),
        'sfsi_telegram_manualCounts'     => intval($sfsi_telegram_manualCounts),

        'sfsi_vk_countsDisplay'    => sanitize_text_field($sfsi_vk_countsDisplay),
        'sfsi_vk_manualCounts'     => intval($sfsi_vk_manualCounts),

        'sfsi_ok_countsDisplay'    => sanitize_text_field($sfsi_ok_countsDisplay),
        'sfsi_ok_manualCounts'     => intval($sfsi_ok_manualCounts),

        'sfsi_weibo_countsDisplay'    => sanitize_text_field($sfsi_weibo_countsDisplay),
        'sfsi_weibo_manualCounts'     => intval($sfsi_weibo_manualCounts),

        'sfsi_wechat_countsDisplay'    => sanitize_text_field($sfsi_wechat_countsDisplay),
        'sfsi_wechat_manualCounts'     => intval($sfsi_wechat_manualCounts),

        'sfsi_whatsapp_countsDisplay'    => sanitize_text_field($sfsi_whatsapp_countsDisplay),
        'sfsi_whatsapp_manualCounts'     => intval($sfsi_whatsapp_manualCounts),

        'sfsi_tiktok_countsDisplay'    => sanitize_text_field($sfsi_tiktok_countsDisplay),
        'sfsi_tiktok_manualCounts'     => intval($sfsi_tiktok_manualCounts),

        'sfsi_ria_countsDisplay'    => sanitize_text_field($sfsi_ria_countsDisplay),
        'sfsi_ria_manualCounts'     => intval($sfsi_ria_manualCounts),

        'sfsi_inha_countsDisplay'    => sanitize_text_field($sfsi_inha_countsDisplay),
        'sfsi_inha_manualCounts'     => intval($sfsi_inha_manualCounts),

        'sfsi_mastodon_countsDisplay'    => sanitize_text_field($sfsi_mastodon_countsDisplay),
        'sfsi_mastodon_manualCounts'     => intval($sfsi_mastodon_manualCounts),

        'sfsi_fbmessenger_countsDisplay'    => sanitize_text_field($sfsi_fbmessenger_countsDisplay),
        'sfsi_fbmessenger_manualCounts'     => intval($sfsi_fbmessenger_manualCounts),

        'sfsi_snapchat_countsDisplay'    => sanitize_text_field($sfsi_snapchat_countsDisplay),
        'sfsi_snapchat_manualCounts'     => intval($sfsi_snapchat_manualCounts),

        'sfsi_reddit_countsDisplay'    => sanitize_text_field($sfsi_reddit_countsDisplay),
        'sfsi_reddit_manualCounts'     => intval($sfsi_reddit_manualCounts),

        'sfsi_round_counts'    => sanitize_text_field($sfsi_round_counts),
        'sfsi_original_counts'    => sanitize_text_field($sfsi_original_counts),
        'sfsi_responsive_share_count'    => sanitize_text_field($sfsi_responsive_share_count),
    );
    update_option('sfsi_section4_options',   serialize($up_option4));
    $new_counts  = sfsi_getCounts();
    header('Content-Type: application/json');
    echo json_encode(array("res" => "success", 'counts' => $new_counts));
    exit;
}


/** Function sfsi_options_updater1() called by wp_ajax hooks: {'updateSrcn1'} **/
/** Parameters found in function sfsi_options_updater1(): {"post": ["nonce", "sfsi_rss_display", "sfsi_email_display", "sfsi_facebook_display", "sfsi_twitter_display", "sfsi_youtube_display", "sfsi_pinterest_display", "sfsi_telegram_display", "sfsi_vk_display", "sfsi_threads_display", "sfsi_bluesky_display", "sfsi_ok_display", "sfsi_wechat_display", "sfsi_weibo_display", "sfsi_instagram_display", "sfsi_ria_display", "sfsi_inha_display", "sfsi_linkedin_display", "sfsi_whatsapp_display", "sfsi_snapchat_display", "sfsi_fbmessenger_display", "sfsi_reddit_display", "sfsi_tiktok_display", "sfsi_copylink_display", "sfsi_mastodon_display"]} **/
function sfsi_options_updater1()
{
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), "update_step1")) {
        echo json_encode(array("wrong_nonce"));
        exit;
    }
    if (!current_user_can('manage_options')) {
        echo json_encode(array('res' => 'not allowed'));
        die();
    }

    $option1 = maybe_unserialize(get_option('sfsi_section1_options', false));

	$sfsi_rss_display       = isset( $_POST["sfsi_rss_display"] ) ? sanitize_text_field( $_POST["sfsi_rss_display"] ) : 'no';
	$sfsi_email_display     = isset( $_POST["sfsi_email_display"] ) ? sanitize_text_field( $_POST["sfsi_email_display"] ) : 'no';
	$sfsi_facebook_display  = isset( $_POST["sfsi_facebook_display"] ) ? sanitize_text_field( $_POST["sfsi_facebook_display"] ) : 'no';
	$sfsi_twitter_display   = isset( $_POST["sfsi_twitter_display"] ) ? sanitize_text_field( $_POST["sfsi_twitter_display"] ) : 'no';
	$sfsi_youtube_display   = isset( $_POST["sfsi_youtube_display"] ) ? sanitize_text_field( $_POST["sfsi_youtube_display"] ) : 'no';
	$sfsi_pinterest_display = isset( $_POST["sfsi_pinterest_display"] ) ? sanitize_text_field( $_POST["sfsi_pinterest_display"] ) : 'no';
	$sfsi_telegram_display  = isset( $_POST["sfsi_telegram_display"] ) ? sanitize_text_field( $_POST["sfsi_telegram_display"] ) : 'no';
	$sfsi_vk_display        = isset( $_POST["sfsi_vk_display"] ) ? sanitize_text_field( $_POST["sfsi_vk_display"] ) : 'no';
	$sfsi_threads_display   = isset( $_POST["sfsi_threads_display"] ) ? sanitize_text_field( $_POST["sfsi_threads_display"] ) : 'no';
	$sfsi_bluesky_display   = isset( $_POST["sfsi_bluesky_display"] ) ? sanitize_text_field( $_POST["sfsi_bluesky_display"] ) : 'no';
	$sfsi_ok_display        = isset( $_POST["sfsi_ok_display"] ) ? sanitize_text_field( $_POST["sfsi_ok_display"] ) : 'no';
	$sfsi_wechat_display    = isset( $_POST["sfsi_wechat_display"] ) ? sanitize_text_field( $_POST["sfsi_wechat_display"] ) : 'no';
	$sfsi_weibo_display     = isset( $_POST["sfsi_weibo_display"] ) ? sanitize_text_field( $_POST["sfsi_weibo_display"] ) : 'no';

    $sfsi_instagram_display     = isset( $_POST["sfsi_instagram_display"] ) ? sanitize_text_field($_POST["sfsi_instagram_display"] ) : 'no';
    $sfsi_ria_display     = isset( $_POST["sfsi_ria_display"] ) ? sanitize_text_field($_POST["sfsi_ria_display"] ) : 'no';
    $sfsi_inha_display     = isset( $_POST["sfsi_inha_display"] ) ? sanitize_text_field($_POST["sfsi_inha_display"] ) : 'no';
    $sfsi_linkedin_display      = isset( $_POST["sfsi_linkedin_display"] ) ? sanitize_text_field($_POST["sfsi_linkedin_display"] ) : 'no';
    $sfsi_custom_icons          = isset( $option1['sfsi_custom_files'] ) ? $option1['sfsi_custom_files'] : '';
    $sfsi_whatsapp_display      = isset( $_POST["sfsi_whatsapp_display"] ) ? sanitize_text_field($_POST["sfsi_whatsapp_display"] ) : 'no';

    $sfsi_snapchat_display      = isset( $_POST["sfsi_snapchat_display"] ) ? sanitize_text_field($_POST["sfsi_snapchat_display"] ) : 'no';
    $sfsi_fbmessenger_display   = isset( $_POST["sfsi_fbmessenger_display"] ) ? sanitize_text_field($_POST["sfsi_fbmessenger_display"] ) : 'no';
    $sfsi_reddit_display        = isset( $_POST["sfsi_reddit_display"] ) ? sanitize_text_field($_POST["sfsi_reddit_display"] ) : 'no';
    $sfsi_tiktok_display        = isset( $_POST["sfsi_tiktok_display"] ) ? sanitize_text_field($_POST["sfsi_tiktok_display"] ) : 'no';
	$sfsi_copylink_display      = isset($_POST["sfsi_copylink_display"]) ? sanitize_text_field($_POST["sfsi_copylink_display"]) : 'no';
    $sfsi_mastodon_display      = isset( $_POST["sfsi_mastodon_display"] ) ? sanitize_text_field($_POST["sfsi_mastodon_display"] ) : 'no';

    $up_option1 = array(
	    'sfsi_rss_display'       => sanitize_text_field( $sfsi_rss_display ),
	    'sfsi_email_display'     => sanitize_text_field( $sfsi_email_display ),
	    'sfsi_facebook_display'  => sanitize_text_field( $sfsi_facebook_display ),
	    'sfsi_twitter_display'   => sanitize_text_field( $sfsi_twitter_display ),
	    'sfsi_youtube_display'   => sanitize_text_field( $sfsi_youtube_display ),
	    'sfsi_pinterest_display' => sanitize_text_field( $sfsi_pinterest_display ),
	    'sfsi_telegram_display'  => sanitize_text_field( $sfsi_telegram_display ),
	    'sfsi_vk_display'        => sanitize_text_field( $sfsi_vk_display ),
	    'sfsi_threads_display'   => sanitize_text_field( $sfsi_threads_display ),
	    'sfsi_bluesky_display'   => sanitize_text_field( $sfsi_bluesky_display ),
	    'sfsi_ok_display'        => sanitize_text_field( $sfsi_ok_display ),
	    'sfsi_wechat_display'    => sanitize_text_field( $sfsi_wechat_display ),
	    'sfsi_weibo_display'     => sanitize_text_field( $sfsi_weibo_display ),

        'sfsi_linkedin_display' => sanitize_text_field($sfsi_linkedin_display),
        'sfsi_instagram_display' => sanitize_text_field($sfsi_instagram_display),
        'sfsi_ria_display' => sanitize_text_field($sfsi_ria_display),
        'sfsi_inha_display' => sanitize_text_field($sfsi_inha_display),
        'sfsi_custom_files'     => sanitize_text_field($sfsi_custom_icons),
        'sfsi_whatsapp_display'   => sanitize_text_field($sfsi_whatsapp_display),

        'sfsi_snapchat_display'     => sanitize_text_field($sfsi_snapchat_display),
        'sfsi_fbmessenger_display'  => sanitize_text_field($sfsi_fbmessenger_display),
        'sfsi_reddit_display'       => sanitize_text_field($sfsi_reddit_display),
        'sfsi_tiktok_display'       => sanitize_text_field($sfsi_tiktok_display),
		'sfsi_copylink_display'	=> sanitize_text_field($sfsi_copylink_display),
        'sfsi_mastodon_display'      => sanitize_text_field($sfsi_mastodon_display),

    );
    update_option('sfsi_section1_options',  serialize($up_option1));
    header('Content-Type: application/json');
    echo json_encode(array("success"));
    exit;
}


/** Function sfsi_dismiss_error_reporting_notice() called by wp_ajax hooks: {'sfsi_dismiss_error_reporting_notice'} **/
/** Parameters found in function sfsi_dismiss_error_reporting_notice(): {"post": ["nonce"]} **/
function sfsi_dismiss_error_reporting_notice()
{

    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), "sfsi_dismiss_error_reporting_notice")) {
        echo json_encode(array('res' => "error"));
        exit;
    }

    if (!current_user_can('manage_options')) {
        echo json_encode(array('res' => 'not allowed'));
        die();
    }

    echo (string)update_option('sfsi_error_reporting_notice_dismissed', true);
    die;
}


/** Function sfsi_default_hide_admin_notification_callback() called by wp_ajax hooks: {'sfsi_default_hide_admin_notification', 'nopriv_sfsi_default_hide_admin_notification'} **/
/** Parameters found in function sfsi_default_hide_admin_notification_callback(): {"post": ["status"]} **/
function sfsi_default_hide_admin_notification_callback() {

	if ( !isset( $_POST['status'] ) ) {
		wp_send_json_error();
		die;
	}

	$option_name = 'sfsi_default_hide_admin_notification' ;
	$new_value = sanitize_text_field($_POST['status']);

	if ( get_option( $option_name ) !== false ) {
		update_option( $option_name, $new_value );
	} else {
		$deprecated = null;
		$autoload = 'no';
		add_option( $option_name, $new_value, $deprecated, $autoload );
	}
	wp_send_json_success();
	die;
}


