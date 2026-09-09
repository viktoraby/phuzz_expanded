<?php
/***
*
*Found actions: 3
*Found functions:3
*Extracted functions:3
*Total parameter names extracted: 2
*Overview: {'dismiss_notice': {'pw_dismiss_notice'}, 'save_translates': {'pw_save_translates'}, 'update_notice': {'pw_update_notice'}}
*
***/

/** Function dismiss_notice() called by wp_ajax hooks: {'pw_dismiss_notice'} **/
/** Parameters found in function dismiss_notice(): {"post": ["notice"]} **/
function dismiss_notice() {

		check_ajax_referer( 'pw_dismiss_notice', 'nonce' );

		$this->set_dismiss( sanitize_text_field( $_POST['notice'] ) );

		die();
	}


/** Function save_translates() called by wp_ajax hooks: {'pw_save_translates'} **/
/** Parameters found in function save_translates(): {"post": ["text1", "text2", "s"]} **/
function save_translates() {
		global $wpdb;

		check_ajax_referer( 'pw_save_translates', 'security' );

		delete_option( self::TRANSLATE_OPTION_KEY );

		$json = [
			'success' => false,
			'message' => 'مشکلی هنگام افزودن حلقه رخ داده است. لطفا مجددا تلاش کنید.',
			'rand'    => mt_rand(),
		];

		if ( ! isset( $_POST['text1'], $_POST['text2'], $_POST['s'] ) ) {
			die( json_encode( $json ) );
		}

		$text1 = wp_kses_data( $_POST['text1'] );
		$text2 = wp_kses_data( $_POST['text2'] );
		$s     = sanitize_text_field( $_POST['s'] );

		if ( empty( $text1 ) ) {
			$json['message'] = sprintf( '<div id="setting-error-pw_msg_%d" class="error settings-error notice is-dismissible"><p><strong>پر کردن فیلد کلمه‌ی مورد نظر اجباری می باشد.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">بستن این اعلان.</span></button></div>', $json['rand'] );
			die( json_encode( $json ) );
		}

		$insert = $wpdb->insert( $this->table, [
			'text1' => esc_html( $text1 ),
			'text2' => esc_html( $text2 ),
		] );

		if ( ! $insert ) {
			$json['message'] = sprintf( '<div id="setting-error-pw_msg_%d" class="error settings-error notice is-dismissible"><p><strong>خطایی در زمان افزودن حلقه (%s => %s) به دیتابیس رخ داده است. لطفا مجددا تلاش کنید</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">بستن این اعلان.</span></button></div>', $json['rand'], $text1, $text2 );
			die( json_encode( $json ) );
		}

		$json['success'] = true;
		$json['message'] = sprintf( '<div id="setting-error-pw_msg_%d" class="updated settings-error notice is-dismissible"><p><strong>حلقه (%s => %s) با موفقیت افزوده شد.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">بستن این اعلان.</span></button></div>', $json['rand'], $text1, $text2 );

		$json['code'] = '';

		if ( empty( $s ) || array_search( $s, [
				$text1,
				$text2,
			] ) !== false ) {
			$json['code'] = sprintf( '<tr id="PW_item_%1$d" data-id="%1$d"><th scope="row" class="check-column"><input name="text_delete_id[]" value="%1$d" type="checkbox"></th><td class="text1 column-text1 has-row-actions column-primary" data-colname="حلقه‌ی اصلی">%2$s<button type="button" class="toggle-row"><span class="screen-reader-text">نمایش جزئیات بیشتر</span></button></td><td class="text2 column-text2" data-colname="حلقه‌ی جایگزین شده">%3$s</td></tr>', $wpdb->insert_id, $text1, $text2 );
		}

		$search = empty( $s ) ? '' : sprintf( ' WHERE text1 LIKE "%%%1$s%%" OR text2 LIKE "%%%1$s%%"', $s );

		$json['count'] = $wpdb->get_var( "SELECT COUNT(*) FROM $this->table{$search}" ) . " مورد";

		die( json_encode( $json ) );
	}


/** Function update_notice() called by wp_ajax hooks: {'pw_update_notice'} **/
/** No params detected :-/ **/


