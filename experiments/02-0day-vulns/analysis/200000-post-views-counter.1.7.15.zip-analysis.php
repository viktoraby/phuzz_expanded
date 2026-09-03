<?php
/***
*
*Found actions: 13
*Found functions:10
*Extracted functions:10
*Total parameter names extracted: 9
*Overview: {'handle_test_email_ajax': {'pvc_send_test_email'}, 'save_bulk_post_views': {'save_bulk_post_views'}, 'ajax_column_chart': {'pvc_column_chart'}, 'check_post_js': {'nopriv_pvc-check-post', 'pvc-check-post'}, 'queue_count': {'nopriv_pvc-view-posts', 'pvc-view-posts'}, 'update_dashboard_user_options': {'pvc_dashboard_user_options'}, 'dashboard_post_most_viewed': {'pvc_dashboard_post_most_viewed'}, 'dashboard_post_views_chart': {'pvc_dashboard_post_views_chart'}, 'dismiss_notice': {'pvc_dismiss_notice'}, 'get_queue_runtime_data': {'nopriv_pvc-queue-runtime', 'pvc-queue-runtime'}}
*
***/

/** Function handle_test_email_ajax() called by wp_ajax hooks: {'pvc_send_test_email'} **/
/** Parameters found in function handle_test_email_ajax(): {"post": ["pvc_test_email_recipient"]} **/
function handle_test_email_ajax() {
		$capability = apply_filters( 'pvc_settings_capability', 'manage_options' );

		if ( ! current_user_can( $capability ) ) {
			wp_send_json_error(
				[
					'code' => 'forbidden',
					'message' => __( 'Sorry, you are not allowed to do that.', 'post-views-counter' )
				],
				403
			);
		}

		check_ajax_referer( 'pvc_send_test_email_ajax', 'nonce' );

		$recipient = isset( $_POST['pvc_test_email_recipient'] ) ? sanitize_email( wp_unslash( $_POST['pvc_test_email_recipient'] ) ) : '';
		$result = $this->send_test_email_request( $recipient );

		if ( ! empty( $result['success'] ) ) {
			wp_send_json_success(
				[
					'message' => $result['message']
				]
			);
		}

		wp_send_json_error(
			[
				'code' => $result['code'],
				'message' => $result['message']
			],
			$this->get_test_email_status_code( $result['code'] )
		);
	}


/** Function save_bulk_post_views() called by wp_ajax hooks: {'save_bulk_post_views'} **/
/** Parameters found in function save_bulk_post_views(): {"post": ["nonce", "post_views", "post_ids"]} **/
function save_bulk_post_views() {
		$pvc = Post_Views_Counter();

		// check nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'pvc_save_bulk_post_views' ) )
			exit;

		$count = isset( $_POST['post_views'] ) ? $this->sanitize_post_views_input( $_POST['post_views'] ) : null;

		// check post ids
		$post_ids = ( ! empty( $_POST['post_ids'] ) && is_array( $_POST['post_ids'] ) ) ? array_map( 'absint', wp_unslash( $_POST['post_ids'] ) ) : [];

		if ( is_null( $count ) )
			exit;

		// allow editing
		$allow_edit = (bool) $pvc->options['display']['restrict_edit_views'];

		// allow editing condition
		$allow_edit_condition = (bool) current_user_can( apply_filters( 'pvc_restrict_edit_capability', 'manage_options' ) ); 

		// break if views editing not allowed or editing condition not met
		if ( $allow_edit === false || $allow_edit_condition === false )
			exit;

		$post_types = (array) $pvc->options['general']['post_types_count'];

		// any post ids?
		if ( ! empty( $post_ids ) ) {
			foreach ( $post_ids as $post_id ) {
				// break if current user can't edit this post
				if ( ! current_user_can( 'edit_post', $post_id ) )
					continue;

				if ( ! in_array( get_post_type( $post_id ), $post_types, true ) )
					continue;

				if ( pvc_update_post_views( $post_id, $count ) !== false )
					do_action( 'pvc_after_update_post_views_count', $post_id );
			}
		}

		exit;
	}


/** Function ajax_column_chart() called by wp_ajax hooks: {'pvc_column_chart'} **/
/** Parameters found in function ajax_column_chart(): {"post": ["post_id", "period"]} **/
function ajax_column_chart() {
		// permission & nonce check
		if ( ! check_ajax_referer( 'pvc-column-modal', 'nonce', false ) )
			wp_send_json_error( [ 'message' => __( 'Permission denied.', 'post-views-counter' ) ] );

		// get PVC instance
		$pvc = Post_Views_Counter();
		$post_types = (array) $pvc->options['general']['post_types_count'];

		// get post ID
		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
		
		if ( ! $post_id )
			wp_send_json_error( [ 'message' => __( 'Invalid post ID.', 'post-views-counter' ) ] );

		// check post exists
		$post = get_post( $post_id );

		if ( ! $post )
			wp_send_json_error( [ 'message' => __( 'Post not found.', 'post-views-counter' ) ] );

		// break if display is not allowed
		if ( ! $pvc->options['display']['post_views_column'] )
			wp_send_json_error( [ 'message' => __( 'Admin column disabled.', 'post-views-counter' ) ] );

		// ensure post type is tracked
		if ( ! in_array( $post->post_type, $post_types, true ) )
			wp_send_json_error( [ 'message' => __( 'Post type is not tracked.', 'post-views-counter' ) ] );

		// check display permission for this specific post
		if ( apply_filters( 'pvc_admin_display_post_views', true, $post_id ) === false )
			wp_send_json_error( [ 'message' => __( 'Access denied for this post.', 'post-views-counter' ) ] );

		// get period (format: YYYYMM or empty for current month)
		$period_str = isset( $_POST['period'] ) && ! empty( $_POST['period'] ) ? preg_replace( '/[^0-9]/', '', $_POST['period'] ) : '';

		// parse period or use current
		if ( $period_str && strlen( $period_str ) === 6 ) {
			$year = substr( $period_str, 0, 4 );
			$month = substr( $period_str, 4, 2 );
			$date = DateTime::createFromFormat( 'Y-m', $year . '-' . $month, wp_timezone() );
			
			if ( ! $date )
				$date = new DateTime( 'now', wp_timezone() );
		} else {
			$date = new DateTime( 'now', wp_timezone() );
		}

		$year = $date->format( 'Y' );
		$month = $date->format( 'm' );
		$last_day = $date->format( 't' );

		// fetch views data
		$views = pvc_get_views( [
			'post_id'		=> $post_id,
			'post_type'		=> $post->post_type,
			'fields'		=> 'date=>views',
			'views_query'	=> [
				'year'	=> (int) $year,
				'month'	=> (int) $month
			]
		] );

		// get colors
		$colors = $pvc->functions->get_colors();

		// prepare response data
		$data = [
			'post_id'	=> $post_id,
			'post_title'=> get_the_title( $post_id ),
			'period'	=> $year . $month,
			'design'		=> [
				'fill'					=> true,
				'backgroundColor'		=> 'rgba(' . $colors['r'] . ',' . $colors['g'] . ',' . $colors['b'] . ', 0.2)',
				'borderColor'			=> 'rgba(' . $colors['r'] . ',' . $colors['g'] . ',' . $colors['b'] . ', 1)',
				'borderWidth'			=> 1.2,
				'borderDash'			=> [],
				'pointBorderColor'		=> 'rgba(' . $colors['r'] . ',' . $colors['g'] . ',' . $colors['b'] . ', 1)',
				'pointBackgroundColor'	=> 'rgba(255, 255, 255, 1)',
				'pointBorderWidth'		=> 1.2
			],
			'data'		=> [
				'labels'	=> [],
				'dates'		=> [],
				'datasets'	=> [
					[
						'label'	=> get_the_title( $post_id ),
						'data'	=> []
					]
				]
			]
		];

		// generate dates and data
		for ( $i = 1; $i <= $last_day; $i++ ) {
			$date_key = $year . $month . str_pad( $i, 2, '0', STR_PAD_LEFT );
			
			// labels: show only odd days
			$data['data']['labels'][] = ( $i % 2 === 0 ? '' : $i );
			
			// formatted dates for tooltips
			$data['data']['dates'][] = date_i18n( get_option( 'date_format' ), strtotime( $year . '-' . $month . '-' . str_pad( $i, 2, '0', STR_PAD_LEFT ) ) );
			
			// view count
			$data['data']['datasets'][0]['data'][] = isset( $views[$date_key] ) ? (int) $views[$date_key] : 0;
		}

		// calculate total views for the period
		$data['total_views'] = array_sum( $data['data']['datasets'][0]['data'] );

		// check if there is any period-specific data
		$period_has_data = false;
		foreach ( $data['data']['datasets'][0]['data'] as $val ) {
			if ( (int) $val > 0 ) {
				$period_has_data = true;
				break;
			}
		}

		$data['period_has_data'] = $period_has_data;

		// generate date navigation HTML
		$data['dates_html'] = $this->generate_modal_dates( (int) $year, (int) $month );

		wp_send_json_success( $data );
	}


/** Function check_post_js() called by wp_ajax hooks: {'nopriv_pvc-check-post', 'pvc-check-post'} **/
/** Parameters found in function check_post_js(): {"post": ["action", "id", "storage_type", "storage_data", "pvc_nonce", "storage_data_all"]} **/
function check_post_js() {
		// check conditions
		if ( ! isset( $_POST['action'], $_POST['id'], $_POST['storage_type'], $_POST['storage_data'], $_POST['pvc_nonce'] ) || ! wp_verify_nonce( $_POST['pvc_nonce'], 'pvc-check-post' ) )
			exit;

		// get post id
		$post_id = (int) $_POST['id'];

		if ( $post_id <= 0 )
			exit;

		// get main instance
		$pvc = Post_Views_Counter();

		// do we use javascript as counter?
		if ( $pvc->options['general']['counter_mode'] !== 'js' )
			exit;

		// get countable post types
		$post_types = $pvc->options['general']['post_types_count'];

		// check if post exists
		$post = get_post( $post_id );

		// whether to count this post type or not
		if ( empty( $post_types ) || empty( $post ) || ! in_array( $post->post_type, $post_types, true ) )
			exit;

		// get storage type
		$storage_type = sanitize_key( $_POST['storage_type'] );

		// invalid storage type?
		if ( ! in_array( $storage_type, [ 'cookies', 'cookieless' ], true ) )
			exit;

		// set storage type
		$this->storage_type = $storage_type;

		// cookieless data storage?
		if ( $storage_type === 'cookieless' && $pvc->options['general']['data_storage'] === 'cookieless' )
			$storage_data = $this->sanitize_storage_payload_set( $_POST['storage_data'], 'post', 'cookieless', isset( $_POST['storage_data_all'] ) ? $_POST['storage_data_all'] : '' );
		// cookies?
		elseif ( $storage_type === 'cookies' && $pvc->options['general']['data_storage'] === 'cookies' )
			$storage_data = $this->sanitize_storage_payload_set( $_POST['storage_data'], 'post', 'cookies', isset( $_POST['storage_data_all'] ) ? $_POST['storage_data_all'] : '' );
		else
			$storage_data = [];

		echo wp_json_encode(
			[
				'post_id'	=> $post_id,
				'counted'	=> ! ( $this->check_post( $post_id, $storage_data ) === null ),
				'storage'	=> $this->storage,
				'type'		=> 'post'
			]
		);

		exit;
	}


/** Function queue_count() called by wp_ajax hooks: {'nopriv_pvc-view-posts', 'pvc-view-posts'} **/
/** Parameters found in function queue_count(): {"post": ["action", "ids", "pvc_nonce"]} **/
function queue_count() {
		// missing or invalid parameters?
		if ( ! isset( $_POST['action'], $_POST['ids'], $_POST['pvc_nonce'] ) || $_POST['ids'] === '' || ! is_string( $_POST['ids'] ) )
			wp_send_json_error( [
				'code' => 'pvc_missing_parameters',
				'message' => __( 'Missing or invalid queue parameters.', 'post-views-counter' )
			], 400 );

		// invalid nonce?
		if ( ! wp_verify_nonce( $_POST['pvc_nonce'], 'pvc-view-posts' ) )
			wp_send_json_error( [
				'code' => 'pvc_invalid_nonce',
				'message' => __( 'Security check failed.', 'post-views-counter' )
			], 403 );

		// get post ids
		$ids = array_values( array_filter( array_map( 'intval', explode( ',', $_POST['ids'] ) ), function( $id ) {
			return $id > 0;
		} ) );

		$counted = [];

		if ( empty( $ids ) )
			wp_send_json_error( [
				'code' => 'pvc_invalid_post_ids',
				'message' => __( 'No valid post IDs were provided.', 'post-views-counter' )
			], 400 );

		// turn on queue mode
		$this->queue_mode = true;

		foreach ( $ids as $id ) {
			$counted[$id] = ! ( $this->check_post( $id ) === null );
		}

		// turn off queue mode
		$this->queue_mode = false;

		// preserve the existing flat success response contract
		wp_send_json( [
			'post_ids'	=> $ids,
			'counted'	=> $counted
		] );
	}


/** Function update_dashboard_user_options() called by wp_ajax hooks: {'pvc_dashboard_user_options'} **/
/** Parameters found in function update_dashboard_user_options(): {"post": ["nonce", "options"]} **/
function update_dashboard_user_options() {
		if ( ! check_ajax_referer( 'pvc-dashboard-user-options', 'nonce' ) )
			wp_die( __( 'You do not have permission to access this page.', 'post-views-counter' ) );

		// valid data?
		if ( isset( $_POST['nonce'], $_POST['options'] ) && ! empty( $_POST['options'] ) ) {
			// get sanitized options
			$update = map_deep( $_POST['options'], 'sanitize_text_field' );

			// get user ID
			$user_id = get_current_user_id();

			// get user dashboard data
			$user_options = get_user_meta( $user_id, 'pvc_dashboard', true );

			// empty userdata?
			if ( ! is_array( $user_options ) || empty( $user_options ) )
				$user_options = [];

			// empty post types?
			if ( ! array_key_exists( 'post_types', $user_options ) || ! is_array( $user_options['post_types'] ) )
				$user_options['post_types'] = [];

			// hide post type?
			if ( ! empty( $update['post_type'] ) ) {
				// get allowed post types
				$allowed_post_types = Post_Views_Counter()->options['general']['post_types_count'];

				// simulate total post views as post type
				$allowed_post_types[] = '_pvc_total_views';

				if ( in_array( $update['post_type'], $allowed_post_types, true ) ) {
					if ( isset( $update['hidden'] ) && $update['hidden'] === 'true' ) {
						if ( ! in_array( $update['post_type'], $user_options['post_types'], true ) )
							$user_options['post_types'][] = $update['post_type'];
					} else {
						if ( ( $key = array_search( $update['post_type'], $user_options['post_types'] ) ) !== false )
							unset( $user_options['post_types'][$key] );
					}
				}
			}

			// empty menu items?
			if ( ! array_key_exists( 'menu_items', $user_options ) || ! is_array( $user_options['menu_items'] ) )
				$user_options['menu_items'] = [];

			if ( ! empty( $update['menu_items'] ) && is_array( $update['menu_items'] ) ) {
				$user_options['menu_items'] = [];

				// get allowed menu items
				$allowed_menu_items = array_column( $this->widget_items, 'id' );

				foreach ( $update['menu_items'] as $menu_item => $hidden ) {
					if ( in_array( $menu_item, $allowed_menu_items, true ) && $hidden === 'true' )
						$user_options['menu_items'][] = $menu_item;
				}
			}

			// filter user options
			$user_options = apply_filters( 'pvc_update_dashboard_user_options', $user_options, $update, $user_id );

			// update userdata
			update_user_meta( $user_id, 'pvc_dashboard', $user_options );
			
			echo wp_send_json_success();
		}

		echo wp_send_json_error();
		exit;
	}


/** Function dashboard_post_most_viewed() called by wp_ajax hooks: {'pvc_dashboard_post_most_viewed'} **/
/** Parameters found in function dashboard_post_most_viewed(): {"post": ["period"]} **/
function dashboard_post_most_viewed() {
		if ( ! apply_filters( 'pvc_user_can_see_stats', current_user_can( 'publish_posts' ) ) || ! check_ajax_referer( 'pvc-dashboard-widget', 'nonce' ) )
			wp_die( __( 'You do not have permission to access this page.', 'post-views-counter' ) );

		// get main instance
		$pvc = Post_Views_Counter();

		// get post types
		$post_types = Post_Views_Counter()->options['general']['post_types_count'];
		
		// empty options?
		if ( empty( $post_types ) || ! is_array( $post_types ) )
			$post_types = [];

		// sanitize post_types
		$post_types = map_deep( $post_types, 'sanitize_text_field' );

		// get period
		$period = isset( $_POST['period'] ) && ! empty( $_POST['period'] ) ? preg_replace( '/[^a-z0-9_|]/', '', $_POST['period'] ) : apply_filters( 'pvc_dashboard_widget_default_period', 'this_month', 'post-most-viewed' );
		
		// parameters to be used in filter
		$args = [
			'widget'		=> 'post_most_viewed',
			'period'		=> $period,
			'post_types'	=> $post_types
		];

		$data = [
			'widget'	=> 'post-most-viewed',
			'html'		=> ''
		];

		// convert period
		$time = pvc_period2timestamp( $period );

		// get date chunks
		$date = date( 'Y m W d t', $time );
		$date_chunks = explode( ' ', $date );

		// get date
		$year = (int) $date_chunks[0];
		$month = sanitize_text_field( $date_chunks[1] );
		
		// generate dates
		$data['dates'] = $this->generate_months( $time );

		// query args
		$query_args = apply_filters( 'pvc_dashboard_post_most_viewed_query_args', [
			'post_type'			=> $post_types,
			'posts_per_page'	=> 10,
			'paged'				=> false,
			'suppress_filters'	=> false,
			'no_found_rows'		=> true,
			'views_query'		=> [
				'year'			=> $year,
				'month'			=> $month,
				'hide_empty'	=> true
			]
		], $period );

		$posts = pvc_get_most_viewed_posts( $query_args );
		
		$html = '
		<table id="pvc-post-most-viewed-table" class="pvc-table pvc-table-hover">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">' . esc_html__( 'Post', 'post-views-counter' ) . '</th>
					<th scope="col">' . esc_html__( 'Views', 'post-views-counter' ) . '</th>
				</tr>
			</thead>
			<tbody>';

		if ( $posts ) {
			// active post types
			$active_post_types = [];

			foreach ( $posts as $index => $post ) {
				setup_postdata( $post );

				$html .= '
				<tr>
					<th scope="col">' . ( $index + 1 ) . '</th>';

				// check post type existence
				if ( array_key_exists( $post->post_type, $active_post_types ) )
					$post_type_exists = $active_post_types[$post->post_type];
				else
					$post_type_exists = $active_post_types[$post->post_type] = post_type_exists( $post->post_type );

				$title = get_the_title( $post );

				if ( $title === '' )
					$title = __( '(no title)' );

				// post link
				$link = '<a href="' . esc_url( get_permalink( $post->ID ) ) . '" target="_blank">' . esc_html( $title ) . '</a>';

				// edit post link
				if ( $post_type_exists && current_user_can( 'edit_post', $post->ID ) ) {
					$link .= ' <a href="' . esc_url( get_edit_post_link( $post->ID ) ) . '" class="cn-edit-link" target="_blank">' . esc_html__( 'Edit', 'post-views-counter' ) . '</a>';
				}

				$html .= '
					<td>' . $link . '</td>';

				$html .= '
					<td>' . number_format_i18n( $post->post_views ) . '</td>
				</tr>';
			}
		} else {
			$html .= '
				<tr class="no-posts">
					<td colspan="3">' . esc_html__( 'No most viewed posts found.', 'post-views-counter' ) . '</td>
				</tr>';
		}

		$html .= '
			</tbody>
		</table>';
		
		$data['html'] = $html;

		echo wp_json_encode( apply_filters( 'pvc_dashboard_post_most_viewed_data', $data, $args ) );
		exit;
	}


/** Function dashboard_post_views_chart() called by wp_ajax hooks: {'pvc_dashboard_post_views_chart'} **/
/** Parameters found in function dashboard_post_views_chart(): {"post": ["period"]} **/
function dashboard_post_views_chart() {
		if ( ! apply_filters( 'pvc_user_can_see_stats', current_user_can( 'publish_posts' ) ) || ! check_ajax_referer( 'pvc-dashboard-widget', 'nonce' ) )
			wp_die( __( 'You do not have permission to access this page.', 'post-views-counter' ) );

		// get period
		$period = isset( $_POST['period'] ) && ! empty( $_POST['period'] ) ? preg_replace( '/[^a-z0-9_|]/', '', $_POST['period'] ) : apply_filters( 'pvc_dashboard_widget_default_period', 'this_month', 'post-views' );

		// get post types
		$post_types = Post_Views_Counter()->options['general']['post_types_count'];
		
		// empty options?
		if ( empty( $post_types ) || ! is_array( $post_types ) )
			$post_types = [];

		// sanitize post_types
		$post_types = map_deep( $post_types, 'sanitize_text_field' );

		// get dashboard user options
		$user_options = $this->get_dashboard_user_options( get_current_user_id(), 'post_types' );
		
		// empty options?
		if ( empty( $user_options ) || ! is_array( $user_options ) )
			$user_options = [];

		// sanitize options
		$user_options = map_deep( $user_options, 'sanitize_text_field' );

		// get colors
		$colors = Post_Views_Counter()->functions->get_colors();
		
		// parameters to be used in filter
		$args = [
			'widget'		=> 'post_views',
			'period'		=> $period,
			'post_types'	=> $post_types,
			'user_options'	=> $user_options
		];
		
		$data = [
			'widget'	=> 'post-views',
			'design'	=> [
				'fill'					=> true,
				'backgroundColor'		=> 'rgba(' . $colors['r'] . ',' . $colors['g'] . ',' . $colors['b'] . ', 0.2)',
				'borderColor'			=> 'rgba(' . $colors['r'] . ',' . $colors['g'] . ',' . $colors['b'] . ', 1)',
				'borderWidth'			=> 1.2,
				'borderDash'			=> [],
				'pointBorderColor'		=> 'rgba(' . $colors['r'] . ',' . $colors['g'] . ',' . $colors['b'] . ', 1)',
				'pointBackgroundColor'	=> 'rgba(255, 255, 255, 1)',
				'pointBorderWidth'		=> 1.2
			],
			'data'		=> [
				'datasets'	=> [
					[
						'label'			=> __( 'Total Views', 'post-views-counter' ),
						'post_type'	=> '_pvc_total_views',
						'hidden'		=> in_array( '_pvc_total_views', $user_options, true ),
						'data'			=> []
					]
				]
			],
		];
		
		// convert period
		$time = pvc_period2timestamp( $period );

		// get date chunks
		$date = date( 'Y m W d t', $time );
		$date_chunks = explode( ' ', $date );

		// get date
		$year = (int) $date_chunks[0];
		$month = sanitize_text_field( $date_chunks[1] );
		$dates_number = (int) $date_chunks[4];

		// get previous date chunks
		$previous_time = strtotime( '-1 months', $time );
		$previous_date = date( 'Y m W d t', $previous_time );
		$previous_date_chunks = explode( ' ', $previous_date );

		// get current date
		$current_date = date_create( 'now', wp_timezone() )->format('Y m W d t');
		$current_date_chunks = explode( ' ', $current_date );

		// generate dates
		$data['dates'] = $this->generate_months( $time );

		// generate chart data
		$sum = [];

		// any post types?
		if ( ! empty( $post_types ) ) {
			// reindex post types
			$post_types = array_combine( range( 1, count( $post_types ) ), array_values( $post_types ) );

			$post_type_data = [];

			foreach ( $post_types as $id => $post_type ) {
				$post_type_obj = get_post_type_object( $post_type );

				// unrecognized post type? (mainly from deactivated plugins)
				if ( empty( $post_type_obj ) )
					$label = $post_type;
				else
					$label = $post_type_obj->labels->name;

				$data['data']['datasets'][$id]['label'] = $label;
				$data['data']['datasets'][$id]['post_type'] = $post_type;
				$data['data']['datasets'][$id]['hidden'] = in_array( $post_type, $user_options, true );
				$data['data']['datasets'][$id]['data'] = [];

				// get month views
				$post_type_data[$id] = array_values(
					pvc_get_views( apply_filters( 'pvc_dashboard_post_views_query_args', 
						[
							'fields'		=> 'date=>views',
							'post_type'		=> $post_type,
							'views_query'	=> [
								'year'			=> $year,
								'month'			=> $month,
								'hide_empty'	=> true
							]
						], $period
					) )
				);
			}

			foreach ( $post_type_data as $post_type_id => $post_views ) {
				foreach ( $post_views as $id => $views ) {
					// generate chart data for specific post types
					$data['data']['datasets'][$post_type_id]['data'][] = $views;

					if ( ! array_key_exists( $id, $sum ) )
						$sum[$id] = 0;

					$sum[$id] += $views;
				}
			}
		}

		// this month all days
		for ( $i = 1; $i <= $dates_number; $i++ ) {
			// generate chart data
			$data['data']['labels'][] = ( $i % 2 === 0 ? '' : $i );
			$data['data']['dates'][] = date_i18n( get_option( 'date_format' ), strtotime( $year . '-' . $month . '-' . str_pad( $i, 2, '0', STR_PAD_LEFT ) ) );
			$data['data']['datasets'][0]['data'][] = ( array_key_exists( $i - 1, $sum ) ? $sum[$i - 1] : 0 );
		}

		echo wp_json_encode( apply_filters( 'pvc_dashboard_post_views_data', $data, $args ) );
		exit;
	}


/** Function dismiss_notice() called by wp_ajax hooks: {'pvc_dismiss_notice'} **/
/** Parameters found in function dismiss_notice(): {"request": ["nonce", "notice_action"]} **/
function dismiss_notice() {
			if ( ! current_user_can( 'install_plugins' ) )
				return;

			if ( isset( $_REQUEST['nonce'] ) && wp_verify_nonce( $_REQUEST['nonce'], 'pvc_dismiss_notice' ) ) {
				$notice_action = empty( $_REQUEST['notice_action'] ) || $_REQUEST['notice_action'] === 'hide' ? 'hide' : sanitize_text_field( $_REQUEST['notice_action'] );

				switch ( $notice_action ) {
					// delay notice
					case 'delay':
						// set delay period to 1 week from now
						$this->options['general'] = array_merge(
							$this->options['general'],
							[
								'update_delay_date'	=> time() + 2 * WEEK_IN_SECONDS
							]
						);
						update_option( 'post_views_counter_settings_general', $this->options['general'] );
						break;

					// hide notice
					default:
						$this->options['general'] = array_merge(
							$this->options['general'],
							[
								'update_notice' => false
							]
						);
						$this->options['general'] = array_merge(
							$this->options['general'],
							[
								'update_delay_date' => 0
							]
						);

						update_option( 'post_views_counter_settings_general', $this->options['general'] );
				}
			}

			exit;
		}


/** Function get_queue_runtime_data() called by wp_ajax hooks: {'nopriv_pvc-queue-runtime', 'pvc-queue-runtime'} **/
/** No params detected :-/ **/


