<?php
/***
*
*Found actions: 2
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 2
*Overview: {'ajax_set_mode': {'mwai_wpai_connectors_set_mode'}, 'ajax_test': {'mwai_wpai_gateway_test'}}
*
***/

/** Function ajax_set_mode() called by wp_ajax hooks: {'mwai_wpai_connectors_set_mode'} **/
/** Parameters found in function ajax_set_mode(): {"post": ["mode"]} **/
function ajax_set_mode(): void {
    check_ajax_referer( 'mwai_wpai_connectors', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) {
      wp_send_json_error( [ 'message' => 'forbidden' ], 403 );
    }
    $mode = isset( $_POST['mode'] ) ? sanitize_key( wp_unslash( $_POST['mode'] ) ) : '';
    if ( ! in_array( $mode, [ 'observe', 'managed', 'off' ], true ) ) {
      wp_send_json_error( [ 'message' => 'invalid mode' ], 400 );
    }
    update_option( self::OPTION_MODE, $mode );
    if ( $mode === 'managed' ) {
      $this->initial_sync();
    }
    wp_send_json_success( [ 'mode' => $mode ] );
  }


/** Function ajax_test() called by wp_ajax hooks: {'mwai_wpai_gateway_test'} **/
/** Parameters found in function ajax_test(): {"request": ["message", "model", "provider"]} **/
function ajax_test(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
      wp_send_json_error( [ 'message' => 'forbidden' ], 403 );
    }
    $message = isset( $_REQUEST['message'] ) ? wp_unslash( $_REQUEST['message'] ) : 'Say hi in one word.';
    $model   = isset( $_REQUEST['model'] )   ? sanitize_text_field( wp_unslash( $_REQUEST['model'] ) ) : '';
    try {
      $registry = \WordPress\AiClient\AiClient::defaultRegistry();
      $provider_id = isset( $_REQUEST['provider'] ) ? sanitize_key( wp_unslash( $_REQUEST['provider'] ) ) : '';
      if ( ! $provider_id ) {
        foreach ( self::ADAPTERS as $id => $_cls ) {
          if ( $registry->hasProvider( $id ) && $registry->isProviderConfigured( $id ) ) {
            $provider_id = $id;
            break;
          }
        }
      }
      if ( ! $provider_id ) {
        wp_send_json_error( [ 'message' => 'no configured AI Engine adapter found; enable the Connectors takeover' ], 500 );
      }
      $provider = $registry->getProviderClassName( $provider_id );
      if ( ! $model ) {
        $models = $provider::modelMetadataDirectory()->listModelMetadata();
        if ( empty( $models ) ) {
          wp_send_json_error( [ 'message' => 'no models exposed by ' . $provider_id ], 500 );
        }
        $model = $models[0]->getId();
      }
      $modelInstance = $provider::model( $model );
      $user = new \WordPress\AiClient\Messages\DTO\UserMessage( [
        new \WordPress\AiClient\Messages\DTO\MessagePart( $message ),
      ] );
      $result = $modelInstance->generateTextResult( [ $user ] );
      $text = '';
      foreach ( $result->getCandidates() as $c ) {
        foreach ( $c->getMessage()->getParts() as $p ) {
          if ( $p->getType()->isText() ) {
            $text .= $p->getText();
          }
        }
      }
      wp_send_json_success( [
        'model'   => $model,
        'reply'   => $text,
        'tokens'  => [
          'prompt'     => $result->getTokenUsage()->getPromptTokens(),
          'completion' => $result->getTokenUsage()->getCompletionTokens(),
          'total'      => $result->getTokenUsage()->getTotalTokens(),
        ],
      ] );
    }
    catch ( \Throwable $e ) {
      wp_send_json_error( [
        'message' => $e->getMessage(),
        'file'    => $e->getFile() . ':' . $e->getLine(),
      ], 500 );
    }
  }


