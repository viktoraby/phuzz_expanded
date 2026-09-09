<?php
/***
*
*Found actions: 5
*Found functions:5
*Extracted functions:5
*Total parameter names extracted: 1
*Overview: {'ajax_generate_key_and_send_email': {'cloudsecurewp_generate_key_and_send_email'}, 'ajax_dismiss_notice_148': {'cloudsecurewp_dismiss_notice_148'}, 'ajax_verify_auth_code': {'cloudsecurewp_verify_auth_code'}, 'ajax_generate_recovery_codes': {'cloudsecurewp_generate_recovery_codes'}, 'ajax_generate_key': {'cloudsecurewp_generate_key'}}
*
***/

/** Function ajax_generate_key_and_send_email() called by wp_ajax hooks: {'cloudsecurewp_generate_key_and_send_email'} **/
/** No params detected :-/ **/


/** Function ajax_dismiss_notice_148() called by wp_ajax hooks: {'cloudsecurewp_dismiss_notice_148'} **/
/** No params detected :-/ **/


/** Function ajax_verify_auth_code() called by wp_ajax hooks: {'cloudsecurewp_verify_auth_code'} **/
/** Parameters found in function ajax_verify_auth_code(): {"post": ["method", "code", "secret_key"]} **/
function ajax_verify_auth_code(): void {
		// nonceチェック
		check_ajax_referer( $this->get_feature_key() . '_csrf', 'nonce', true );

		if ( ! current_user_can( 'read' ) ) {
			wp_send_json_error();
		}

		// 返却JSONレスポンス初期化
		$json_response = [
			'has_recovery' => false,
		];

		$user_id = get_current_user_id();

		$method      = sanitize_text_field( $_POST['method'] );
		$interval    = ( $method === 'app' ) ? self::AUTH_APP_INTERVAL : self::AUTH_EMAIL_INTERVAL;
		$auth_method = ( $method === 'app' ) ? self::USER_AUTH_METHOD_APP : self::USER_AUTH_METHOD_EMAIL;
		$code        = isset( $_POST['code'] ) ? sanitize_text_field( $_POST['code'] ) : '';

		if ( $method === 'app' ) {
			$secret_key = isset( $_POST['secret_key'] ) ? sanitize_text_field( $_POST['secret_key'] ) : '';
		} else {
			$transient_key = self::SETUP_SECRET_PREFIX . $user_id;
			$secret_key    = get_transient( $transient_key );
			if ( ! $secret_key ) {
				wp_send_json_error( $json_response );
				return;
			}
		}

		if ( CloudSecureWP_Time_Based_One_Time_Password::verify_code( $secret_key, $code, $interval ) === false ) {
			wp_send_json_error( $json_response );
			return;
		}

		// 認証成功 - データベースに保存
		try {
			// 2fa認証情報を設定
			$this->setting_2fa_auth_info( $user_id, $auth_method, $secret_key );

			// メール認証の場合
			if ( $method === 'email' ) {
				// 一時保存していた秘密鍵を削除
				delete_transient( $transient_key );
				// メール認証の送信可能時間をリセット
				$this->delete_email_able_send_time( $user_id );
			}

			// リカバリーコードの設定状況を取得
			$has_recovery                  = $this->has_recovery_codes( $user_id );
			$json_response['has_recovery'] = $has_recovery;
			wp_send_json_success( $json_response );
			return;
		} catch ( Exception $e ) {
			wp_send_json_error( $json_response );
			return;
		}
	}


/** Function ajax_generate_recovery_codes() called by wp_ajax hooks: {'cloudsecurewp_generate_recovery_codes'} **/
/** No params detected :-/ **/


/** Function ajax_generate_key() called by wp_ajax hooks: {'cloudsecurewp_generate_key'} **/
/** No params detected :-/ **/


