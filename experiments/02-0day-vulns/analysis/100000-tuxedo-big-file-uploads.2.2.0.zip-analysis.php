<?php
/***
*
*Found actions: 6
*Found functions:6
*Extracted functions:6
*Total parameter names extracted: 3
*Overview: {'ajax_file_scan': {'bfu_file_scan'}, 'ajax_upgrade_dismiss': {'bfu_upgrade_dismiss'}, 'handle_promo_action': {'bffu_handle_promo_action'}, 'ajax_subscribe_dismiss': {'bfu_subscribe_dismiss'}, 'ajax_chunk_receiver': {'bfu_chunker'}, 'ajax_upload_dismiss': {'bfu_upload_dismiss'}}
*
***/

/** Function ajax_file_scan() called by wp_ajax hooks: {'bfu_file_scan'} **/
/** Parameters found in function ajax_file_scan(): {"post": ["remaining_dirs"]} **/
function ajax_file_scan() {
        // check caps
        if ( ! current_user_can( $this->capability ) ) {
            wp_send_json_error( esc_html__( 'Permissions Error: Please refresh the page and try again.', 'tuxedo-big-file-uploads' ) );
        }

        $path = $this->get_upload_dir_root();

        $remaining_dirs = [];
        //validate path is within uploads dir to prevent path traversal
        if ( isset( $_POST['remaining_dirs'] ) && is_array( $_POST['remaining_dirs'] ) ) {
            foreach ( $_POST['remaining_dirs'] as $dir ) {
                $realpath = realpath( $path . $dir );
                if ( 0 === strpos( $realpath, $path ) ) { //check that parsed path begins with upload dir
                    $remaining_dirs[] = $dir;
                }
            }
        }

        $file_scan = new Big_File_Uploads_File_Scan( $path, $this->ajax_timelimit, $remaining_dirs );
        $file_scan->start();
        $file_count     = number_format_i18n( $file_scan->get_total_files() );
        $file_size      = size_format( $file_scan->get_total_size(), 2 );
        $remaining_dirs = $file_scan->paths_left;
        $is_done        = $file_scan->is_done;

        $data = compact( 'file_count', 'file_size', 'is_done', 'remaining_dirs' );

        wp_send_json_success( $data );
    }


/** Function ajax_upgrade_dismiss() called by wp_ajax hooks: {'bfu_upgrade_dismiss'} **/
/** No params detected :-/ **/


/** Function handle_promo_action() called by wp_ajax hooks: {'bffu_handle_promo_action'} **/
/** Parameters found in function handle_promo_action(): {"post": ["notice_id", "action_type"]} **/
function handle_promo_action() {
        check_ajax_referer( 'bffu_promo_nonce', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Permission denied' );
        }

        $notice_id = sanitize_text_field( $_POST['notice_id'] );
        $action    = sanitize_text_field( $_POST['action_type'] );
        $user_id   = get_current_user_id();

        switch ( $action ) {
            case 'dismiss':
                update_user_meta( $user_id, 'bffu_notice_' . $notice_id, 'dismissed' );
                break;

            case 'link':
                update_user_meta( $user_id, 'bffu_notice_' . $notice_id, 'visited' );
                break;

            case 'delay':
                // How to use delay_days from the notice config?
                $notice = array_filter( $this->notices, function ( $n ) use ( $notice_id ) {
					return $n['id'] === $notice_id;
				} );

                $notice = reset( $notice );

				if ( ! $notice || ! isset( $notice['delay_days'] ) ) {
                    $delay_days = $this->default_delay; // Fallback to default if not set
				} else {
					$delay_days = $notice['delay_days'];
                }

                $show_after = time() + ( DAY_IN_SECONDS * $delay_days );
                update_user_meta( $user_id, 'bffu_notice_' . $notice_id, [
                        'action'     => 'delay',
                        'show_after' => $show_after,
                ] );
                break;
        }

        wp_send_json_success();
    }


/** Function ajax_subscribe_dismiss() called by wp_ajax hooks: {'bfu_subscribe_dismiss'} **/
/** No params detected :-/ **/


/** Function ajax_chunk_receiver() called by wp_ajax hooks: {'bfu_chunker'} **/
/** Parameters found in function ajax_chunk_receiver(): {"request": ["chunk", "chunks", "name", "short", "type", "post_id"]} **/
function ajax_chunk_receiver() {
        /** Check that we have an upload and there are no errors. */
        if ( empty( $_FILES ) || $_FILES['async-upload']['error'] ) {
            /** Failed to move uploaded file. */
            wp_die();
        }

        /** Authenticate user. */
        if ( ! is_user_logged_in() || ! current_user_can( 'upload_files' ) ) {
            wp_die( __( 'Sorry, you are not allowed to upload files.' ) );
        }
        check_admin_referer( 'media-form' );

        /** Check and get file chunks. */
        $chunk        = isset( $_REQUEST['chunk'] ) ? intval( $_REQUEST['chunk'] ) : 0; //zero index
        $current_part = $chunk + 1;
        $chunks       = isset( $_REQUEST['chunks'] ) ? intval( $_REQUEST['chunks'] ) : 0;

        /** Get file name and path + name. */
        $fileName = isset( $_REQUEST['name'] ) ? $_REQUEST['name'] : $_FILES['async-upload']['name'];


        $bfu_temp_dir = $this->temp_dir();

        //only run on first chunk
        if ( $chunk === 0 ) {
            $this->prepare_temp_dir( $bfu_temp_dir );

            //scan temp dir for files older than 24 hours and delete them.
            $this->cleanup_stale_chunks( $bfu_temp_dir );
        }

        $filePath = $this->chunk_path( $fileName, $bfu_temp_dir );

        //debugging
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            $size = file_exists( $filePath ) ? size_format( filesize( $filePath ), 3 ) : '0 B';
            error_log( "BFU: Processing \"$fileName\" part $current_part of $chunks as $filePath. $size processed so far." );
        }

        $tuxbfu_max_upload_size = $this->get_upload_limit( $fileName );
        // Measure what is already on disk plus the incoming part. Guarding this on
        // file_exists() skipped the very first chunk, so anything that arrived in a
        // single chunk was never weighed here at all.
        $bfu_received_size = file_exists( $filePath ) ? filesize( $filePath ) : 0;

        // The name above comes from the request, so the extension is whatever the
        // uploader says it is. Read the format out of the bytes instead and take the
        // stricter of the two, so a video renamed .jpg cannot spend the image
        // allowance. The head of the file is enough to identify it, and it is present
        // from the very first part.
        $bfu_head       = $bfu_received_size ? $filePath : $_FILES['async-upload']['tmp_name'];
        $bfu_real_limit = $this->sniffed_upload_limit( $bfu_head, $fileName );
        if ( $bfu_real_limit > 0 && $bfu_real_limit < $tuxbfu_max_upload_size ) {
            $tuxbfu_max_upload_size = $bfu_real_limit;
        }
        if ( ( $bfu_received_size + filesize( $_FILES['async-upload']['tmp_name'] ) ) > $tuxbfu_max_upload_size ) {
            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                error_log( "BFU: File size limit exceeded." );
            }

            if ( ! $chunks || $chunk == $chunks - 1 ) {
                @unlink( $filePath );

                $this->send_upload_error( __( 'The file size has exceeded the maximum file size setting.', 'tuxedo-big-file-uploads' ), $fileName );
            }

            wp_die();
        }

        /** Append this chunk to the temp file. */
        $appended = $this->append_chunk( $filePath, $_FILES['async-upload']['tmp_name'], $chunk );

        if ( is_wp_error( $appended ) ) {
            if ( 'bfu_input_stream' === $appended->get_error_code() ) {
                error_log( "BFU: Error reading uploaded part $current_part of $chunks." );
                $message = sprintf( __( 'There was an error reading uploaded part %d of %d.', 'tuxedo-big-file-uploads' ), $current_part, $chunks );
            } else {
                error_log( "BFU: Failed to open output stream $filePath to write part $current_part of $chunks." );
                $message = $appended->get_error_message();
            }

            $this->send_upload_error( $message, $fileName );
        }

        /** Check if file has finished uploading all parts. */
        if ( ! $chunks || $chunk == $chunks - 1 ) {
            //debugging
            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                $size = file_exists( $filePath ) ? size_format( filesize( $filePath ), 3 ) : '0 B';
                error_log( "BFU: Completing \"$fileName\" upload with a $size final size." );
            }

            /** Recreate upload in $_FILES global and pass off to WordPress. */
            $_FILES['async-upload']['tmp_name'] = $filePath;
            $_FILES['async-upload']['name']     = $fileName;
            $_FILES['async-upload']['size']     = filesize( $_FILES['async-upload']['tmp_name'] );
            $wp_filetype                        = wp_check_filetype_and_ext( $_FILES['async-upload']['tmp_name'], $_FILES['async-upload']['name'] );
            $_FILES['async-upload']['type']     = $wp_filetype['type'];

            /*
             * Weigh the finished file against the type WordPress actually detected.
             *
             * The per-chunk gate above has to take the uploader's word for the type:
             * the name arrives in $_REQUEST and a half-written file cannot be sniffed.
             * On its own that lets someone rename a 2GB video to .jpg and spend the
             * image allowance on it. The bytes are all on disk by now and the real type
             * is known, so measure once more and refuse to hand an over-limit file to
             * WordPress. Falls back to the claimed name when the content could not be
             * identified, which is a file core is about to reject anyway.
             */
            $verified_name = $fileName;
            if ( ! empty( $wp_filetype['proper_filename'] ) ) {
                $verified_name = $wp_filetype['proper_filename'];
            } elseif ( ! empty( $wp_filetype['ext'] ) ) {
                $verified_name = 'file.' . $wp_filetype['ext'];
            }

            $verified_limit = $this->get_upload_limit( $verified_name );
            if ( $verified_limit > 0 && $_FILES['async-upload']['size'] > $verified_limit ) {
                if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                    error_log( "BFU: \"$fileName\" is over the limit for its detected type. Discarding." );
                }

                @unlink( $filePath );

                // Deliberately the same message the per-chunk gate sends: the user does
                // not need to be told which check caught them.
                $this->send_upload_error( __( 'The file size has exceeded the maximum file size setting.', 'tuxedo-big-file-uploads' ), $fileName );
            }

            header( 'Content-Type: text/plain; charset=' . get_option( 'blog_charset' ) );

            if ( ! isset( $_REQUEST['short'] ) || ! isset( $_REQUEST['type'] ) ) { //ajax like media uploader in modal

                // Compatibility with Easy Digital Downloads plugin.
                if ( function_exists( 'edd_change_downloads_upload_dir' ) ) {
                    global $pagenow;
                    $pagenow = 'async-upload.php';
                    edd_change_downloads_upload_dir();
                }

                send_nosniff_header();
                nocache_headers();

                $this->wp_ajax_upload_attachment();
                wp_die( '0' );

            } else { //non-ajax like add new media page
                $post_id = 0;
                if ( isset( $_REQUEST['post_id'] ) ) {
                    $post_id = absint( $_REQUEST['post_id'] );
                    if ( ! get_post( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
                        $post_id = 0;
                    }
                }

                $id = media_handle_upload( 'async-upload', $post_id, [], [
                        'action'    => 'wp_handle_sideload',
                        'test_form' => false,
                ] );
                if ( is_wp_error( $id ) ) {
                    printf(
                            '<div class="error-div error">%s <strong>%s</strong><br />%s</div>',
                            sprintf(
                                    '<button type="button" class="dismiss button-link" onclick="jQuery(this).parents(\'div.media-item\').slideUp(200, function(){jQuery(this).remove();});">%s</button>',
                                    __( 'Dismiss' )
                            ),
                            sprintf(
                            /* translators: %s: Name of the file that failed to upload. */
                                    __( '&#8220;%s&#8221; has failed to upload.' ),
                                    esc_html( $_FILES['async-upload']['name'] )
                            ),
                            esc_html( $id->get_error_message() )
                    );
                    wp_die();
                }

                if ( $_REQUEST['short'] ) {
                    // Short form response - attachment ID only.
                    echo $id;
                } else {
                    // Long form response - big chunk of HTML.
                    $type = $_REQUEST['type'];

                    /**
                     * Filters the returned ID of an uploaded attachment.
                     *
                     * The dynamic portion of the hook name, `$type`, refers to the attachment type.
                     *
                     * Possible hook names include:
                     *
                     *  - `async_upload_audio`
                     *  - `async_upload_file`
                     *  - `async_upload_image`
                     *  - `async_upload_video`
                     *
                     * @param  int  $id  Uploaded attachment ID.
                     *
                     * @since 2.5.0
                     *
                     */
                    echo apply_filters( "async_upload_{$type}", $id );
                }

            }

        }

        wp_die();
    }


/** Function ajax_upload_dismiss() called by wp_ajax hooks: {'bfu_upload_dismiss'} **/
/** No params detected :-/ **/


