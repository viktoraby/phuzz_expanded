<?php
/***
*
*Found actions: 142
*Found functions:132
*Extracted functions:130
*Total parameter names extracted: 7
*Overview: {'tutor_change_course_status': {'tutor_change_course_status'}, 'ajax_update_course': {'tutor_update_course'}, 'tutor_delete_dashboard_question': {'tutor_delete_dashboard_question'}, 'ajax_course_contents': {'tutor_course_contents'}, 'ajax_update_course_content_order': {'tutor_update_course_content_order'}, 'ajax_lesson_details': {'tutor_lesson_details'}, 'tutor_save_withdraw_account': {'tutor_save_withdraw_account'}, 'add_discount': {'tutor_order_discount'}, 'ajax_create_course': {'tutor_create_course'}, 'ajax_reset_user_preferences': {'tutor_reset_user_preferences'}, 'course_list_bulk_action': {'tutor_course_list_bulk_action'}, 'tde_get_apis': {'tde_get_apis', 'nopriv_tde_get_apis'}, 'ajax_quiz_details': {'tutor_quiz_details'}, 'coupon_permanent_delete': {'tutor_coupon_permanent_delete'}, 'add_course_to_cart': {'tutor_add_course_to_cart'}, 'ajax_save_user_preferences': {'tutor_save_user_preferences'}, 'tutor_render_quiz_content': {'tutor_render_quiz_content'}, 'course_enrollment': {'tutor_course_enrollment'}, 'tutor_make_an_withdraw': {'tutor_make_an_withdraw'}, '::update_api_permission': {'tutor_update_api_permission'}, 'create_or_update_announcement': {'tutor_announcement_create'}, 'ajax_complete_tour': {'tutor_complete_tour'}, 'tutor_single_course_reviews_load_more': {'tutor_single_course_reviews_load_more', 'nopriv_tutor_single_course_reviews_load_more'}, 'ajax_get_tax_settings': {'tutor_get_tax_settings'}, 'ajax_unlink_page_builder': {'tutor_unlink_page_builder'}, 'quiz_attempts_bulk_action': {'tutor_quiz_attempts_bulk_action'}, 'tutor_user_photo_remove': {'tutor_user_photo_remove'}, 'handle_legal_consent_ajax': {'tutor_gdpr_legal_consents'}, 'ajax_create_new_draft_course': {'tutor_create_new_draft_course'}, 'tutor_apply_settings': {'tutor_apply_settings'}, 'tutor_delete_topic': {'tutor_delete_topic'}, '::ajax_delete_manual_payment_method': {'tutor_delete_manual_payment_method'}, 'update_profile': {'tutor_update_profile'}, 'instructor_bulk_action': {'tutor_instructor_bulk_action'}, 'delete_tutor_review': {'delete_tutor_review'}, 'tutor_option_default_save': {'tutor_option_default_save'}, 'get_quiz_attempts_stat': {'tutor_quiz_attempts_count'}, 'ajax_tutor_payment_gateways': {'tutor_payment_gateways'}, 'tutor_handle_api_calls': {'nopriv_tutor_handle_api_calls', 'tutor_handle_api_calls'}, 'update_withdraw_status': {'tutor_admin_withdraw_action'}, 'order_mark_as_paid': {'tutor_order_paid'}, 'ajax_save_lesson': {'tutor_save_lesson'}, 'ajax_delete_lesson': {'tutor_delete_lesson'}, 'add_comment': {'tutor_order_comment'}, 'tutor_quiz_abandon': {'tutor_quiz_abandon'}, 'show_more': {'show_more', 'nopriv_show_more'}, 'autoload_next_course_content': {'autoload_next_course_content'}, 'make_refund': {'tutor_order_refund'}, 'announcement_bulk_action': {'tutor_announcement_bulk_action'}, 'ajax_import_sample_courses': {'tutor_import_sample_courses'}, 'tutor_reset_password': {'tutor_profile_password_reset'}, 'ajax_delete_lesson_comment': {'tutor_delete_lesson_comment'}, 'order_cancel': {'tutor_order_cancel'}, 'update_user_photo': {'tutor_user_photo_upload'}, 'clear_review_popup_data': {'tutor_clear_review_popup_data'}, '::ajax_add_manual_payment_method': {'tutor_add_manual_payment_method'}, 'tutor_social_profile': {'tutor_social_profile'}, 'tutor_place_rating': {'tutor_place_rating'}, 'get_all_addons': {'tutor_get_all_addons'}, 'handle_do_not_show_feature_page': {'tutor_do_not_show_feature_page'}, 'ajax_save_home_section_visibility': {'tutor_save_instructor_home_sections_visibility'}, 'ajax_create_coupon': {'tutor_coupon_create'}, 'tutor_change_review_status': {'tutor_change_review_status'}, 'tutor_course_add_to_wishlist': {'tutor_course_add_to_wishlist', 'nopriv_tutor_course_add_to_wishlist'}, 'ajax_quiz_delete': {'tutor_quiz_delete'}, 'ajax_update_lesson_comment': {'tutor_update_lesson_comment'}, 'save_billing_info': {'tutor_save_billing_info'}, 'review_quiz_answers': {'tutor_review_quiz_answers'}, 'ajax_course_list': {'tutor_course_list'}, 'tutor_render_lesson_content': {'nopriv_tutor_render_lesson_content', 'tutor_render_lesson_content'}, 'tutor_import_settings': {'tutor_import_settings'}, 'ajax_load_comment_replies': {'tutor_load_comment_replies'}, 'ajax_apply_coupon': {'tutor_apply_coupon'}, 'tutor_save_topic': {'tutor_save_topic'}, 'student_bulk_action': {'tutor_student_bulk_action'}, 'ajax_switch_profile': {'tutor_switch_profile'}, 'tutor_qna_create_update': {'tutor_qna_create_update'}, 'ajax_course_details': {'tutor_course_details'}, 'ajax_single_course_lesson_load_more': {'tutor_create_lesson_comment', 'tutor_single_course_lesson_load_more'}, 'ajax_youtube_video_duration': {'tutor_youtube_video_duration'}, 'add_new_instructor': {'tutor_add_instructor'}, 'load_replies': {'tutor_qna_load_replies'}, 'process_bulk_action': {'tutor_qna_bulk_action'}, 'get_coupon_applies_to': {'tutor_get_coupon_applies_to'}, 'addon_enable_disable': {'addon_enable_disable'}, 'ajax_coupon_details': {'tutor_coupon_details'}, 'ajax_get_tutor_payment_settings': {'tutor_payment_settings'}, 'ajax_coupon_applies_to_list': {'tutor_coupon_applies_to_list'}, 'delete_review': {'tutor_delete_review'}, 'tutor_instructor_feedback': {'tutor_instructor_feedback'}, 'ajax_update_coupon': {'tutor_coupon_update'}, '::revoke_api_keys': {'tutor_revoke_api_keys'}, 'tutor_reset_course_progress': {'tutor_reset_course_progress'}, 'review_quiz_answer': {'review_quiz_answer'}, 'ajax_onboard_setup': {'tutor_onboard_setup'}, 'get_wc_product': {'tutor_get_wc_product'}, 'load_more': {'tutor_q_and_a_load_more'}, 'instructor_approval_action': {'instructor_approval_action'}, 'delete_course_from_cart': {'tutor_delete_course_from_cart'}, 'ajax_get_checkout_html': {'tutor_get_checkout_html'}, 'ajax_tutor_complete_course': {'tutor_complete_course'}, 'handle_ajax_request': {'tutor_user_consents'}, 'delete_announcement': {'tutor_announcement_delete'}, '::generate_api_keys': {'tutor_generate_api_keys'}, 'ajax_get_order_details': {'tutor_order_details'}, 'sync_video_playback': {'sync_video_playback'}, 'tutor_export_single_settings': {'tutor_export_single_settings'}, 'load_filtered_instructor': {'nopriv_load_filtered_instructor', 'load_filtered_instructor'}, 'tutor_option_save': {'tutor_option_save'}, 'tutor_export_settings': {'tutor_export_settings'}, 'load_saved_data': {'load_saved_data'}, 'ajax_reply_lesson_comment': {'tutor_reply_lesson_comment'}, 'ajax_qna_update': {'tutor_qna_update'}, 'ajax_quiz_builder_save': {'tutor_quiz_builder_save'}, 'bulk_action_handler': {'tutor_order_bulk_action', 'tutor_coupon_bulk_action'}, 'get_wc_products': {'tutor_get_wc_products'}, 'tutor_delete_dashboard_course': {'tutor_delete_dashboard_course'}, 'ajax_save_home_sections_order': {'tutor_save_instructor_home_sections_order'}, 'get_billing_info': {'tutor_get_billing_info'}, 'ajax_load_lesson_comments': {'tutor_load_lesson_comments'}, 'tutor_delete_single_settings': {'tutor_delete_single_settings'}, 'tutor_quiz_timeout': {'tutor_quiz_timeout'}, 'ajax_user_list': {'tutor_user_list'}, 'ajax_dismiss_offer_notice': {'tutor_dismiss_offer_notice'}, 'tutor_qna_single_action': {'tutor_qna_single_action'}, 'render_block_tutor': {'render_block_tutor'}, 'reset_settings_data': {'reset_settings_data'}, 'load_listing': {'nopriv_tutor_course_filter_ajax', 'tutor_course_filter_ajax'}, 'tutor_option_search': {'tutor_option_search'}, 'send_test_mail': {'tutor_send_mail_test'}, 'attempt_delete': {'tutor_attempt_delete'}, 'tutor_course_delete': {'tutor_course_delete'}}
*
***/

/** Function tutor_change_course_status() called by wp_ajax hooks: {'tutor_change_course_status'} **/
/** No params detected :-/ **/


/** Function ajax_update_course() called by wp_ajax hooks: {'tutor_update_course'} **/
/** No params detected :-/ **/


/** Function tutor_delete_dashboard_question() called by wp_ajax hooks: {'tutor_delete_dashboard_question'} **/
/** No params detected :-/ **/


/** Function ajax_course_contents() called by wp_ajax hooks: {'tutor_course_contents'} **/
/** No params detected :-/ **/


/** Function ajax_update_course_content_order() called by wp_ajax hooks: {'tutor_update_course_content_order'} **/
/** No params detected :-/ **/


/** Function ajax_lesson_details() called by wp_ajax hooks: {'tutor_lesson_details'} **/
/** No params detected :-/ **/


/** Function tutor_save_withdraw_account() called by wp_ajax hooks: {'tutor_save_withdraw_account'} **/
/** No params detected :-/ **/


/** Function add_discount() called by wp_ajax hooks: {'tutor_order_discount'} **/
/** No params detected :-/ **/


/** Function ajax_create_course() called by wp_ajax hooks: {'tutor_create_course'} **/
/** No params detected :-/ **/


/** Function ajax_reset_user_preferences() called by wp_ajax hooks: {'tutor_reset_user_preferences'} **/
/** No params detected :-/ **/


/** Function course_list_bulk_action() called by wp_ajax hooks: {'tutor_course_list_bulk_action'} **/
/** No params detected :-/ **/


/** Function tde_get_apis() called by wp_ajax hooks: {'tde_get_apis', 'nopriv_tde_get_apis'} **/
/** No params detected :-/ **/


/** Function ajax_quiz_details() called by wp_ajax hooks: {'tutor_quiz_details'} **/
/** No params detected :-/ **/


/** Function coupon_permanent_delete() called by wp_ajax hooks: {'tutor_coupon_permanent_delete'} **/
/** No params detected :-/ **/


/** Function add_course_to_cart() called by wp_ajax hooks: {'tutor_add_course_to_cart'} **/
/** No params detected :-/ **/


/** Function ajax_save_user_preferences() called by wp_ajax hooks: {'tutor_save_user_preferences'} **/
/** No params detected :-/ **/


/** Function tutor_render_quiz_content() called by wp_ajax hooks: {'tutor_render_quiz_content'} **/
/** No params detected :-/ **/


/** Function course_enrollment() called by wp_ajax hooks: {'tutor_course_enrollment'} **/
/** No params detected :-/ **/


/** Function tutor_make_an_withdraw() called by wp_ajax hooks: {'tutor_make_an_withdraw'} **/
/** No params detected :-/ **/


/** Function ::update_api_permission() called by wp_ajax hooks: {'tutor_update_api_permission'} **/
/** No params detected :-/ **/


/** Function create_or_update_announcement() called by wp_ajax hooks: {'tutor_announcement_create'} **/
/** No params detected :-/ **/


/** Function ajax_complete_tour() called by wp_ajax hooks: {'tutor_complete_tour'} **/
/** No params detected :-/ **/


/** Function tutor_single_course_reviews_load_more() called by wp_ajax hooks: {'tutor_single_course_reviews_load_more', 'nopriv_tutor_single_course_reviews_load_more'} **/
/** No params detected :-/ **/


/** Function ajax_get_tax_settings() called by wp_ajax hooks: {'tutor_get_tax_settings'} **/
/** No params detected :-/ **/


/** Function ajax_unlink_page_builder() called by wp_ajax hooks: {'tutor_unlink_page_builder'} **/
/** No params detected :-/ **/


/** Function quiz_attempts_bulk_action() called by wp_ajax hooks: {'tutor_quiz_attempts_bulk_action'} **/
/** No params detected :-/ **/


/** Function tutor_user_photo_remove() called by wp_ajax hooks: {'tutor_user_photo_remove'} **/
/** No params detected :-/ **/


/** Function handle_legal_consent_ajax() called by wp_ajax hooks: {'tutor_gdpr_legal_consents'} **/
/** No params detected :-/ **/


/** Function ajax_create_new_draft_course() called by wp_ajax hooks: {'tutor_create_new_draft_course'} **/
/** No params detected :-/ **/


/** Function tutor_apply_settings() called by wp_ajax hooks: {'tutor_apply_settings'} **/
/** No params detected :-/ **/


/** Function tutor_delete_topic() called by wp_ajax hooks: {'tutor_delete_topic'} **/
/** No params detected :-/ **/


/** Function ::ajax_delete_manual_payment_method() called by wp_ajax hooks: {'tutor_delete_manual_payment_method'} **/
/** No function found :-/ **/


/** Function update_profile() called by wp_ajax hooks: {'tutor_update_profile'} **/
/** No params detected :-/ **/


/** Function instructor_bulk_action() called by wp_ajax hooks: {'tutor_instructor_bulk_action'} **/
/** No params detected :-/ **/


/** Function delete_tutor_review() called by wp_ajax hooks: {'delete_tutor_review'} **/
/** No params detected :-/ **/


/** Function tutor_option_default_save() called by wp_ajax hooks: {'tutor_option_default_save'} **/
/** No params detected :-/ **/


/** Function get_quiz_attempts_stat() called by wp_ajax hooks: {'tutor_quiz_attempts_count'} **/
/** No params detected :-/ **/


/** Function ajax_tutor_payment_gateways() called by wp_ajax hooks: {'tutor_payment_gateways'} **/
/** No params detected :-/ **/


/** Function tutor_handle_api_calls() called by wp_ajax hooks: {'nopriv_tutor_handle_api_calls', 'tutor_handle_api_calls'} **/
/** Parameters found in function tutor_handle_api_calls(): {"request": ["droip_data", "collection_data"]} **/
function tutor_handle_api_calls() {
		$request_method = Input::post( 'method' );
		$is_user_logged_in = is_user_logged_in();
		
		tutor_utils()->checking_nonce();

		if ( 'generate_html' === $request_method ) {

			$course_id  = Input::post( 'course_id' );
            $droip_data = isset( $_REQUEST['droip_data'] ) ? wp_unslash( $_REQUEST['droip_data'] ) : null; //phpcs:ignore
			$droip_data = json_decode( $droip_data, true );

			$blocks = $droip_data['blocks'];
			$styles = $droip_data['styles'];
			$root   = Input::sanitize( $droip_data['root'] );

			$params = array(
				'blocks'       => $blocks,
				'style_blocks' => $styles,
				'root'         => $root,
				'get_variable' => false,
				'get_fonts'    => false,
				'options'      => array( 'post' => get_post( $course_id ) ),
			);

			$collection_wrapper_html_string = HelperFunctions::get_html_using_preview_script( $params );
			wp_send_json_success( $collection_wrapper_html_string );
		}
		if ( 'enroll_course' === $request_method && $is_user_logged_in ) {
			$course_id = Input::post( 'course_id' );

			$course = get_post( $course_id );

			if ( ! $course || ! is_object( $course ) || $course->post_status !== 'publish' ) {
				wp_send_json_error( 'Course not found!' );
			}

			if ( tutor_utils()->is_course_purchasable( $course_id ) ) {
				wp_send_json_error( 'You cannot enroll in this course without purchasing it first.' );
			}

			$res       = tutor_utils()->do_enroll( $course_id );

			wp_send_json_success( $res );
		}

		if ( 'add_to_cart_course' === $request_method ) {
			$course_id = Input::post( 'course_id' );
			$res       = tutor_add_to_cart( $course_id );

			// check is user logged in or not
			if (! is_user_logged_in() ) {
				$res['redirect'] = true;
				$res['data'] = wp_login_url( wp_get_referer() );
			}

			wp_send_json_success( $res );
		}

		if ('remove_from_cart_course' === $request_method) {
			$res       = tutor_remove_cart_item(Input::post('course_id'));
			wp_send_json_success($res);
		}

		if('get_user_cart_item_count' === $request_method) {
			$cart_items = tutor_get_cart_items();
			$count = count($cart_items);
			wp_send_json_success($count);
		}

		if ( 'complete_course' === $request_method && $is_user_logged_in ) {
			$course_id = Input::post( 'course_id' );
			
			$is_enrolled = tutor_utils()->is_enrolled($course_id);

			if ( ! $is_enrolled ) {
				wp_send_json_error( 'You are not allowed to complete this course.' );
			}

			$user_id   = get_current_user_id();

			CourseModel::mark_course_as_completed( $course_id, $user_id );

			wp_send_json_success( true );
		}

		if ( 'add_qna' === $request_method && $is_user_logged_in ) {
			$course_id         = Input::post( 'course_id' );

			$is_enrolled = tutor_utils()->is_enrolled($course_id);

			if ( ! $is_enrolled ) {
				wp_send_json_error( 'You are not allowed to add Q&A to this course.' );
			}
			
			$comment_parent_id = Input::post( 'comment_parent_id' );
			$content           = Input::post( 'content' );

			$user = wp_get_current_user();
			$date = gmdate( 'Y-m-d H:i:s', tutor_time() );

			if ( ! $user->ID ) {
				wp_send_json_error( 'Please Sign-In' );
			}

            $collection_data = isset($_REQUEST['collection_data']) ? json_decode(wp_unslash($_REQUEST['collection_data']), true) : null; //phpcs:ignore

			if ( ! $content ) {
				wp_send_json_error( 'Invalid request' );
			}

			$data = apply_filters(
				'tutor_qna_insert_data',
				array(
					'comment_post_ID'  => $course_id,
					'comment_author'   => $user->user_login,
					'comment_date'     => $date,
					'comment_date_gmt' => get_gmt_from_date( $date ),
					'comment_content'  => $content,
					'comment_approved' => 'approved',
					'comment_agent'    => 'TutorLMSPlugin',
					'comment_type'     => 'tutor_q_and_a',
					'comment_parent'   => $comment_parent_id,
					'user_id'          => $user->ID,
				)
			);

			global $wpdb;

			$response = $wpdb->insert( $wpdb->comments, $data );

			if ( false === $response ) {
				wp_send_json_error( 'Request failed!' );
			}

			$thread = $this->get_comment( $wpdb->insert_id );

			// comment-item.// -qna-reply.
			$new_element_name = 0 === $comment_parent_id ? 'comment-item' : TDE_APP_PREFIX . '-qna-reply';

			$new_element = Preview::generateQnAElement( $thread, $new_element_name, $collection_data );

			wp_send_json_success(
				array(
					'html'                => $new_element,
					'inserted_comment_id' => $wpdb->insert_id,
				)
			);
		}

		wp_send_json_error( 'Invalid request' );
	}


/** Function update_withdraw_status() called by wp_ajax hooks: {'tutor_admin_withdraw_action'} **/
/** No params detected :-/ **/


/** Function order_mark_as_paid() called by wp_ajax hooks: {'tutor_order_paid'} **/
/** No params detected :-/ **/


/** Function ajax_save_lesson() called by wp_ajax hooks: {'tutor_save_lesson'} **/
/** No params detected :-/ **/


/** Function ajax_delete_lesson() called by wp_ajax hooks: {'tutor_delete_lesson'} **/
/** No params detected :-/ **/


/** Function add_comment() called by wp_ajax hooks: {'tutor_order_comment'} **/
/** No params detected :-/ **/


/** Function tutor_quiz_abandon() called by wp_ajax hooks: {'tutor_quiz_abandon'} **/
/** No params detected :-/ **/


/** Function show_more() called by wp_ajax hooks: {'show_more', 'nopriv_show_more'} **/
/** No params detected :-/ **/


/** Function autoload_next_course_content() called by wp_ajax hooks: {'autoload_next_course_content'} **/
/** No params detected :-/ **/


/** Function make_refund() called by wp_ajax hooks: {'tutor_order_refund'} **/
/** No params detected :-/ **/


/** Function announcement_bulk_action() called by wp_ajax hooks: {'tutor_announcement_bulk_action'} **/
/** No params detected :-/ **/


/** Function ajax_import_sample_courses() called by wp_ajax hooks: {'tutor_import_sample_courses'} **/
/** No params detected :-/ **/


/** Function tutor_reset_password() called by wp_ajax hooks: {'tutor_profile_password_reset'} **/
/** No params detected :-/ **/


/** Function ajax_delete_lesson_comment() called by wp_ajax hooks: {'tutor_delete_lesson_comment'} **/
/** No params detected :-/ **/


/** Function order_cancel() called by wp_ajax hooks: {'tutor_order_cancel'} **/
/** No params detected :-/ **/


/** Function update_user_photo() called by wp_ajax hooks: {'tutor_user_photo_upload'} **/
/** No params detected :-/ **/


/** Function clear_review_popup_data() called by wp_ajax hooks: {'tutor_clear_review_popup_data'} **/
/** No params detected :-/ **/


/** Function ::ajax_add_manual_payment_method() called by wp_ajax hooks: {'tutor_add_manual_payment_method'} **/
/** No function found :-/ **/


/** Function tutor_social_profile() called by wp_ajax hooks: {'tutor_social_profile'} **/
/** No params detected :-/ **/


/** Function tutor_place_rating() called by wp_ajax hooks: {'tutor_place_rating'} **/
/** No params detected :-/ **/


/** Function get_all_addons() called by wp_ajax hooks: {'tutor_get_all_addons'} **/
/** No params detected :-/ **/


/** Function handle_do_not_show_feature_page() called by wp_ajax hooks: {'tutor_do_not_show_feature_page'} **/
/** No params detected :-/ **/


/** Function ajax_save_home_section_visibility() called by wp_ajax hooks: {'tutor_save_instructor_home_sections_visibility'} **/
/** No params detected :-/ **/


/** Function ajax_create_coupon() called by wp_ajax hooks: {'tutor_coupon_create'} **/
/** No params detected :-/ **/


/** Function tutor_change_review_status() called by wp_ajax hooks: {'tutor_change_review_status'} **/
/** No params detected :-/ **/


/** Function tutor_course_add_to_wishlist() called by wp_ajax hooks: {'tutor_course_add_to_wishlist', 'nopriv_tutor_course_add_to_wishlist'} **/
/** No params detected :-/ **/


/** Function ajax_quiz_delete() called by wp_ajax hooks: {'tutor_quiz_delete'} **/
/** No params detected :-/ **/


/** Function ajax_update_lesson_comment() called by wp_ajax hooks: {'tutor_update_lesson_comment'} **/
/** No params detected :-/ **/


/** Function save_billing_info() called by wp_ajax hooks: {'tutor_save_billing_info'} **/
/** No params detected :-/ **/


/** Function review_quiz_answers() called by wp_ajax hooks: {'tutor_review_quiz_answers'} **/
/** No params detected :-/ **/


/** Function ajax_course_list() called by wp_ajax hooks: {'tutor_course_list'} **/
/** Parameters found in function ajax_course_list(): {"post": ["filter"]} **/
function ajax_course_list() {
		tutor_utils()->check_nonce();
		$this->check_access();

		$limit       = Input::post( 'limit', 10, Input::TYPE_INT );
		$offset      = Input::post( 'offset', 0, Input::TYPE_INT );
		$search_term = '';
		$post_status = Input::post( 'post_status', null );

		$filter = json_decode( wp_unslash( $_POST['filter'] ) ); //phpcs:ignore --sanitized already
		if ( ! empty( $filter ) && property_exists( $filter, 'search' ) ) {
			$search_term = Input::sanitize( $filter->search );
		}

		$args = array(
			'post_status'    => is_null( $post_status ) ? 'publish' : $post_status,
			'posts_per_page' => $limit,
			'offset'         => $offset,
			's'              => $search_term,
		);

		$exclude = Input::post( 'exclude', array(), Input::TYPE_ARRAY );
		if ( count( $exclude ) ) {
			$exclude              = array_filter( $exclude, fn( $id ) => is_numeric( $id ) && $id > 0 );
			$args['post__not_in'] = $exclude;
		}

		$courses = CourseModel::get_courses_by_args( $args );

		$response = array(
			'results'     => array(),
			'total_items' => 0,
		);

		$response['total_items'] = is_a( $courses, 'WP_Query' ) ? $courses->found_posts : 0;

		if ( is_a( $courses, 'WP_Query' ) && $courses->have_posts() ) {
			$courses = $courses->get_posts();
			foreach ( $courses as $course ) {
				$response['results'][] = self::get_mini_info( $course );
			}
		}

		$this->json_response(
			__( 'Course list retrieved successfully!', 'tutor' ),
			$response
		);
	}


/** Function tutor_render_lesson_content() called by wp_ajax hooks: {'nopriv_tutor_render_lesson_content', 'tutor_render_lesson_content'} **/
/** No params detected :-/ **/


/** Function tutor_import_settings() called by wp_ajax hooks: {'tutor_import_settings'} **/
/** Parameters found in function tutor_import_settings(): {"files": ["data"]} **/
function tutor_import_settings() {
		tutor_utils()->checking_nonce();

		// Check if user is privileged.
		if ( ! User::is_admin() ) {
			wp_send_json_error( tutor_utils()->error_message() );
		}

		//phpcs:ignore
		$data = $_FILES['data'];

		if ( ! isset( $data['tmp_name'] ) ) {
			$this->response_bad_request( __( 'Invalid file', 'tutor' ) );
		}

		$request = json_decode( file_get_contents( $data['tmp_name'] ), true );

		unlink( $data['tmp_name'] );

		$settings_found = false;

		if ( json_last_error() ) {
			$this->response_bad_request( __( 'Invalid json file', 'tutor' ) );
		}

		if ( ! isset( $request['data'] ) ) {
			$this->response_bad_request( __( 'Data not found or invalid', 'tutor' ) );
		}

		if ( is_array( $request['data'] ) && count( $request['data'] ) ) {
			foreach ( $request['data'] as $content ) {
				if ( isset( $content['content_type'] ) && 'settings' === $content['content_type'] ) {
					$settings_found = true;
				}
			}
		}

		if ( ! $settings_found ) {
			$this->response_bad_request( __( 'Settings not found', 'tutor' ) );
		}

		$settings_data   = is_array( $request ) && isset( $request['data'] ) ? $request['data'][0]['data'] : array();
		$update_settings = $this->update_settings_log( $settings_data, 'Imported' );

		$response = array(
			'job_progress'  => '100',
			'exported_data' => $update_settings,
		);

		$this->json_response( __( 'Settings imported successfully!', 'tutor' ), $response );
	}


/** Function ajax_load_comment_replies() called by wp_ajax hooks: {'tutor_load_comment_replies'} **/
/** No params detected :-/ **/


/** Function ajax_apply_coupon() called by wp_ajax hooks: {'tutor_apply_coupon'} **/
/** No params detected :-/ **/


/** Function tutor_save_topic() called by wp_ajax hooks: {'tutor_save_topic'} **/
/** No params detected :-/ **/


/** Function student_bulk_action() called by wp_ajax hooks: {'tutor_student_bulk_action'} **/
/** No params detected :-/ **/


/** Function ajax_switch_profile() called by wp_ajax hooks: {'tutor_switch_profile'} **/
/** No params detected :-/ **/


/** Function tutor_qna_create_update() called by wp_ajax hooks: {'tutor_qna_create_update'} **/
/** Parameters found in function tutor_qna_create_update(): {"post": ["back_url"]} **/
function tutor_qna_create_update() {
		tutor_utils()->checking_nonce();

		$user_id     = get_current_user_id();
		$course_id   = Input::post( 'course_id', 0, Input::TYPE_INT );
		$question_id = Input::post( 'question_id', 0, Input::TYPE_INT );
		$context     = Input::post( 'context' );

		if ( $question_id ) {
			$course_id = tutor_utils()->get_course_id_by( 'qa_question', $question_id );
		}

		if ( ! $course_id || ! $this->has_qna_access( $user_id, $course_id ) ) {
			$this->response_bad_request( tutor_utils()->error_message() );
		}

		$qna_text = Input::post( 'answer', '', tutor()->has_pro ? Input::TYPE_KSES_POST : Input::TYPE_TEXTAREA );

		if ( ! $qna_text ) {
			$this->response_bad_request( __( 'Empty Content Not Allowed!', 'tutor' ) );
		}

		// Prepare user info.
		$user = get_userdata( $user_id );
		$date = gmdate( 'Y-m-d H:i:s', tutor_time() );

		$qna_object              = new \stdClass();
		$qna_object->user_id     = $user_id;
		$qna_object->course_id   = $course_id;
		$qna_object->question_id = $question_id;
		$qna_object->qna_text    = $qna_text;
		$qna_object->user        = $user;
		$qna_object->date        = $date;

		$question_id = $this->inset_qna( $qna_object );

		// Provide the html now.
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		ob_start();
		tutor_load_template_from_custom_path(
			tutor()->path . '/views/qna/qna-single.php',
			array(
				'question_id' => $question_id,
				'back_url'    => isset( $_POST['back_url'] ) ? esc_url_raw( wp_unslash( $_POST['back_url'] ) ) : '',
				'context'     => $context,
			)
		);
		wp_send_json_success(
			array(
				'html'      => ob_get_clean(),
				'editor_id' => 'tutor_qna_reply_editor_' . $question_id,
			)
		);
	}


/** Function ajax_course_details() called by wp_ajax hooks: {'tutor_course_details'} **/
/** No params detected :-/ **/


/** Function ajax_single_course_lesson_load_more() called by wp_ajax hooks: {'tutor_create_lesson_comment', 'tutor_single_course_lesson_load_more'} **/
/** No params detected :-/ **/


/** Function ajax_youtube_video_duration() called by wp_ajax hooks: {'tutor_youtube_video_duration'} **/
/** No params detected :-/ **/


/** Function add_new_instructor() called by wp_ajax hooks: {'tutor_add_instructor'} **/
/** No params detected :-/ **/


/** Function load_replies() called by wp_ajax hooks: {'tutor_qna_load_replies'} **/
/** No params detected :-/ **/


/** Function process_bulk_action() called by wp_ajax hooks: {'tutor_qna_bulk_action'} **/
/** No params detected :-/ **/


/** Function get_coupon_applies_to() called by wp_ajax hooks: {'tutor_get_coupon_applies_to'} **/
/** No params detected :-/ **/


/** Function addon_enable_disable() called by wp_ajax hooks: {'addon_enable_disable'} **/
/** No params detected :-/ **/


/** Function ajax_coupon_details() called by wp_ajax hooks: {'tutor_coupon_details'} **/
/** No params detected :-/ **/


/** Function ajax_get_tutor_payment_settings() called by wp_ajax hooks: {'tutor_payment_settings'} **/
/** No params detected :-/ **/


/** Function ajax_coupon_applies_to_list() called by wp_ajax hooks: {'tutor_coupon_applies_to_list'} **/
/** Parameters found in function ajax_coupon_applies_to_list(): {"post": ["filter"]} **/
function ajax_coupon_applies_to_list() {
		tutor_utils()->check_nonce();
		tutor_utils()->check_current_user_capability();

		$applies_to  = Input::post( 'applies_to' );
		$limit       = Input::post( 'limit', 10, Input::TYPE_INT );
		$offset      = Input::post( 'offset', 0, Input::TYPE_INT );
		$search_term = '';

		$filter = json_decode( wp_unslash( $_POST['filter'] ) ); //phpcs:ignore --sanitized already
		if ( ! empty( $filter ) && property_exists( $filter, 'search' ) ) {
			$search_term = Input::sanitize( $filter->search );
		}

		if ( $this->model->is_specific_applies_to( $applies_to ) ) {
			try {
				$list = $this->get_application_list( $applies_to, $limit, $offset, $search_term );
				if ( $list ) {
					$this->json_response(
						__( 'Coupon application list retrieved successfully!', 'tutor' ),
						$list
					);
				} else {
					$this->json_response(
						tutor_utils()->error_message( 'not_found' ),
						null,
						HttpHelper::STATUS_NOT_FOUND
					);
				}
			} catch ( \Throwable $th ) {
				$this->json_response(
					tutor_utils()->error_message( 'server_error' ),
					$th->getMessage(),
					HttpHelper::STATUS_INTERNAL_SERVER_ERROR
				);
			}
		} else {
			$this->json_response(
				tutor_utils()->error_message( 'invalid_req' ),
				null,
				HttpHelper::STATUS_UNPROCESSABLE_ENTITY
			);
		}
	}


/** Function delete_review() called by wp_ajax hooks: {'tutor_delete_review'} **/
/** No params detected :-/ **/


/** Function tutor_instructor_feedback() called by wp_ajax hooks: {'tutor_instructor_feedback'} **/
/** No params detected :-/ **/


/** Function ajax_update_coupon() called by wp_ajax hooks: {'tutor_coupon_update'} **/
/** No params detected :-/ **/


/** Function ::revoke_api_keys() called by wp_ajax hooks: {'tutor_revoke_api_keys'} **/
/** No params detected :-/ **/


/** Function tutor_reset_course_progress() called by wp_ajax hooks: {'tutor_reset_course_progress'} **/
/** No params detected :-/ **/


/** Function review_quiz_answer() called by wp_ajax hooks: {'review_quiz_answer'} **/
/** No params detected :-/ **/


/** Function ajax_onboard_setup() called by wp_ajax hooks: {'tutor_onboard_setup'} **/
/** No params detected :-/ **/


/** Function get_wc_product() called by wp_ajax hooks: {'tutor_get_wc_product'} **/
/** No params detected :-/ **/


/** Function load_more() called by wp_ajax hooks: {'tutor_q_and_a_load_more'} **/
/** No params detected :-/ **/


/** Function instructor_approval_action() called by wp_ajax hooks: {'instructor_approval_action'} **/
/** No params detected :-/ **/


/** Function delete_course_from_cart() called by wp_ajax hooks: {'tutor_delete_course_from_cart'} **/
/** No params detected :-/ **/


/** Function ajax_get_checkout_html() called by wp_ajax hooks: {'tutor_get_checkout_html'} **/
/** No params detected :-/ **/


/** Function ajax_tutor_complete_course() called by wp_ajax hooks: {'tutor_complete_course'} **/
/** No params detected :-/ **/


/** Function handle_ajax_request() called by wp_ajax hooks: {'tutor_user_consents'} **/
/** No params detected :-/ **/


/** Function delete_announcement() called by wp_ajax hooks: {'tutor_announcement_delete'} **/
/** No params detected :-/ **/


/** Function ::generate_api_keys() called by wp_ajax hooks: {'tutor_generate_api_keys'} **/
/** No params detected :-/ **/


/** Function ajax_get_order_details() called by wp_ajax hooks: {'tutor_order_details'} **/
/** No params detected :-/ **/


/** Function sync_video_playback() called by wp_ajax hooks: {'sync_video_playback'} **/
/** No params detected :-/ **/


/** Function tutor_export_single_settings() called by wp_ajax hooks: {'tutor_export_single_settings'} **/
/** No params detected :-/ **/


/** Function load_filtered_instructor() called by wp_ajax hooks: {'nopriv_load_filtered_instructor', 'load_filtered_instructor'} **/
/** No params detected :-/ **/


/** Function tutor_option_save() called by wp_ajax hooks: {'tutor_option_save'} **/
/** No params detected :-/ **/


/** Function tutor_export_settings() called by wp_ajax hooks: {'tutor_export_settings'} **/
/** No params detected :-/ **/


/** Function load_saved_data() called by wp_ajax hooks: {'load_saved_data'} **/
/** No params detected :-/ **/


/** Function ajax_reply_lesson_comment() called by wp_ajax hooks: {'tutor_reply_lesson_comment'} **/
/** No params detected :-/ **/


/** Function ajax_qna_update() called by wp_ajax hooks: {'tutor_qna_update'} **/
/** No params detected :-/ **/


/** Function ajax_quiz_builder_save() called by wp_ajax hooks: {'tutor_quiz_builder_save'} **/
/** Parameters found in function ajax_quiz_builder_save(): {"post": ["payload"]} **/
function ajax_quiz_builder_save() {
		tutor_utils()->check_nonce();

		$payload    = $_POST['payload'] ?? array(); //phpcs:ignore
		if ( is_string( $payload ) ) {
			$payload = json_decode( wp_unslash( $payload ), true );
		}

		$course_id  = Input::post( 'course_id', 0, Input::TYPE_INT );
		$topic_id   = Input::post( 'topic_id', 0, Input::TYPE_INT );
		$course_cls = new Course( false );

		$course_cls->check_access( $course_id );

		$result = $this->save_quiz( $topic_id, wp_slash( $payload ) );
		if ( $result->success ) {
			$quiz_id      = $result->data;
			$quiz_details = QuizModel::get_quiz_details( $quiz_id );
			$this->json_response( __( 'Quiz saved successfully', 'tutor' ), $quiz_details );
		} else {
			$this->json_response( __( 'Error', 'tutor' ), $result->errors, HttpHelper::STATUS_BAD_REQUEST );
		}
	}


/** Function bulk_action_handler() called by wp_ajax hooks: {'tutor_order_bulk_action', 'tutor_coupon_bulk_action'} **/
/** No params detected :-/ **/


/** Function get_wc_products() called by wp_ajax hooks: {'tutor_get_wc_products'} **/
/** No params detected :-/ **/


/** Function tutor_delete_dashboard_course() called by wp_ajax hooks: {'tutor_delete_dashboard_course'} **/
/** No params detected :-/ **/


/** Function ajax_save_home_sections_order() called by wp_ajax hooks: {'tutor_save_instructor_home_sections_order'} **/
/** No params detected :-/ **/


/** Function get_billing_info() called by wp_ajax hooks: {'tutor_get_billing_info'} **/
/** No params detected :-/ **/


/** Function ajax_load_lesson_comments() called by wp_ajax hooks: {'tutor_load_lesson_comments'} **/
/** No params detected :-/ **/


/** Function tutor_delete_single_settings() called by wp_ajax hooks: {'tutor_delete_single_settings'} **/
/** No params detected :-/ **/


/** Function tutor_quiz_timeout() called by wp_ajax hooks: {'tutor_quiz_timeout'} **/
/** No params detected :-/ **/


/** Function ajax_user_list() called by wp_ajax hooks: {'tutor_user_list'} **/
/** Parameters found in function ajax_user_list(): {"post": ["filter"]} **/
function ajax_user_list() {
		tutor_utils()->check_nonce();

		$can_access = apply_filters( 'tutor_user_list_access', current_user_can( 'manage_options' ) );
		if ( ! $can_access ) {
			$this->json_response( tutor_utils()->error_message( 'forbidden' ), HttpHelper::STATUS_FORBIDDEN );
		}

		$response = array(
			'results'     => array(),
			'total_items' => 0,
		);

		$limit  = Input::post( 'limit', 10, Input::TYPE_INT );
		$offset = Input::post( 'offset', 0, Input::TYPE_INT );

		$args = array(
			'limit'  => $limit,
			'offset' => $offset,
		);

		$filter = json_decode( wp_unslash( $_POST['filter'] ?? '{}' ) );//phpcs:ignore
		if ( ! empty( $filter ) && property_exists( $filter, 'search' ) && ! empty( $filter->search ) ) {
			$args['search']         = '*' . Input::sanitize( $filter->search ) . '*';
			$args['search_columns'] = array( 'user_login', 'user_email', 'user_nicename', 'display_name', 'ID' );
		}

		$user_list = $this->model->get_users_list( $args );

		if ( is_object( $user_list ) ) {
			foreach ( $user_list->get_results() as $user ) {
				// Set user avatar.
				$user->avatar_url      = get_avatar_url( $user->ID, array( 'size' => 32 ) );
				$response['results'][] = $user;
			}

			$response['total_items'] = $user_list->get_total();
		}

		$this->json_response(
			__( 'User list fetched successfully!', 'tutor' ),
			$response
		);
	}


/** Function ajax_dismiss_offer_notice() called by wp_ajax hooks: {'tutor_dismiss_offer_notice'} **/
/** No params detected :-/ **/


/** Function tutor_qna_single_action() called by wp_ajax hooks: {'tutor_qna_single_action'} **/
/** No params detected :-/ **/


/** Function render_block_tutor() called by wp_ajax hooks: {'render_block_tutor'} **/
/** No params detected :-/ **/


/** Function reset_settings_data() called by wp_ajax hooks: {'reset_settings_data'} **/
/** No params detected :-/ **/


/** Function load_listing() called by wp_ajax hooks: {'nopriv_tutor_course_filter_ajax', 'tutor_course_filter_ajax'} **/
/** No params detected :-/ **/


/** Function tutor_option_search() called by wp_ajax hooks: {'tutor_option_search'} **/
/** No params detected :-/ **/


/** Function send_test_mail() called by wp_ajax hooks: {'tutor_send_mail_test'} **/
/** No params detected :-/ **/


/** Function attempt_delete() called by wp_ajax hooks: {'tutor_attempt_delete'} **/
/** No params detected :-/ **/


/** Function tutor_course_delete() called by wp_ajax hooks: {'tutor_course_delete'} **/
/** No params detected :-/ **/


