<?php
/***
*
*Found actions: 1
*Found functions:1
*Extracted functions:1
*Total parameter names extracted: 1
*Overview: {'get_templately': {'get_templately'}}
*
***/

/** Function get_templately() called by wp_ajax hooks: {'get_templately'} **/
/** Parameters found in function get_templately(): {"post": ["nonce", "content_type", "source", "id", "file_type"]} **/
function get_templately() {
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
		if( empty( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field($_POST['nonce']), 'templately_nonce' ) ) {
			Helper::send_error( __( 'Something went wrong. Nonce Issue.', 'templately' ) );
		}

		$this->post_data = $_POST;

		RequirementInstaller::raise_limits();

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
		$content_type = isset( $_POST['content_type'] ) ? \strtolower( sanitize_text_field( $_POST['content_type'] ) ) : false;
		if ( ! $content_type ) {
			Helper::send_error( __( 'Something went wrong.', 'templately' ) );
		}

		$this->trigger_time = DB::get_option( '_templately_trigger', false );

		switch ( $content_type ) {
			case 'install_requirements' :
				$data = $this->install_requirements();
				break;
			case 'platform_exists' :
				$data = $this->platform_exists();
				break;
			case 'verification_done' :
				$data = $this->verification_done();
				break;
			case 'get_dependencies' :
				$data = $this->get_dependencies();
				break;
			case 'get_templates' :
				$data = $this->get_templates();
				break;
			case 'blocks' :
				$data = $this->get_items();
				break;
			case 'pages' :
				$data = $this->get_items( 'pages' );
				break;
			case 'packs' :
				$data = $this->get_items( 'packs' );
				break;
			case 'search' :
				$data = $this->getItemsOrPacks();
				break;
			case 'check_dependency' :
				$data = $this->check_dependency();
				break;
			case 'template_content' :
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
				$origin = isset( $_POST['source'] ) ? sanitize_text_field( $_POST['source'] ) : 'remote';
				$data   = $this->insert_template( $origin );
				break;
			case 'save_template' :
				$args = $this->ready_args( $_POST );
				$data = $this->save_template( $args );
				break;
			case 'push_to_cloud' :
				$id        = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : null;
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
				$file_type = isset( $_POST['file_type'] ) ? sanitize_text_field( $_POST['file_type'] ) : 'elementor';
				$data      = $this->push_to_cloud( $id, $file_type );
				break;
			case 'remove_from_cloud' :
				$id   = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : null;
				$data = $this->remove_from_cloud( $id );
				break;
			case 'delete_template' :
				$data = $this->delete_template();
				break;
			case 'clouds_sync' :
				$data = $this->clouds_sync();
				break;
			case 'template_export' :
				$data = $this->template_export();
				break;
			case 'get_item' :
				$data = $this->get_item();
				break;
			case 'get_tags':
				$data = $this->get_tags();
				break;
			case 'set_favourite' :
				$data = $this->set_favourite();
				break;
			case 'set_unfavourite' :
				$data = $this->set_favourite( true );
				break;
			case 'import_to_library' :
				$data = $this->import_to_library();
				break;
			case 'create_page' :
				$data = $this->create_page();
				break;
			case 'get_workspace' :
				$data = $this->get_workspace();
				break;
			case 'edit_workspace' :
				$data = $this->create_or_edit_workspace( true );
				break;
			case 'add_files_to_workspace' :
				$data = $this->add_files_to_workspace( true );
				break;
			case 'delete_workspace' :
				$data = $this->delete_workspace();
				break;
			case 'create_workspace' :
				$data = $this->create_or_edit_workspace();
				break;
			case 'workspace_details' :
				$data = $this->workspace_details();
				break;
			case 'copy_or_move' :
				$data = $this->copy_or_move();
				break;
			case 'cloud_usage_limit' :
				$data = $this->cloud_usage_limit();
				break;
			case 'create_user' :
				$data = $this->create_user();
				break;
			case 'login_user' :
				$data = $this->login_user();
				break;
			case 'logout' :
				$data = $this->logout();
				break;
		}
		// send success message to api.
		Helper::send_success( $data );
	}


