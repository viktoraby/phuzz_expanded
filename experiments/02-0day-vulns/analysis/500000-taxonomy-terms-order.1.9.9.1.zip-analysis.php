<?php
/***
*
*Found actions: 1
*Found functions:1
*Extracted functions:1
*Total parameter names extracted: 1
*Overview: {'saveAjaxOrder': {'update-taxonomy-order'}}
*
***/

/** Function saveAjaxOrder() called by wp_ajax hooks: {'update-taxonomy-order'} **/
/** Parameters found in function saveAjaxOrder(): {"post": ["nonce", "order"]} **/
function saveAjaxOrder()
                {
                    global $wpdb;
                    
                    if  ( ! isset ( $_POST['nonce'] ) ||  ! wp_verify_nonce( sanitize_text_field ( wp_unslash ( $_POST['nonce'] ) ), 'update-taxonomy-order' ) )
                        die();
                     
                    $data               = isset ( $_POST['order'] )  ?   stripslashes( sanitize_text_field ( wp_unslash ( $_POST['order'] ) ) )   :   "";
                    $unserialised_data  = json_decode($data, TRUE);
                            
                    if (is_array($unserialised_data))
                    foreach($unserialised_data as $key => $values ) 
                        {
                            //$key_parent = str_replace("item_", "", $key);
                            $items = explode("&", $values);
                            unset($item);
                            foreach ($items as $item_key => $item_)
                                {
                                    $items[$item_key] = trim(str_replace("item[]=", "",$item_));
                                }
                            
                            if (is_array($items) && count($items) > 0)
                                {
                                    foreach( $items as $item_key => $term_id ) 
                                        {
                                            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery 
                                            $wpdb->update( $wpdb->terms, array('term_order' => ($item_key + 1)), array('term_id' => $term_id) );
                                        }
                                    clean_term_cache($items);
                                } 
                        }
                        
                    do_action('tto/update-order');
                    
                    wp_cache_flush();
                        
                    die();
                }


