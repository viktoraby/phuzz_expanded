<?php
/***
*
*Found actions: 2
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 2
*Overview: {'saveAjaxOrder': {'update-custom-type-order'}, 'saveArchiveAjaxOrder': {'update-custom-type-order-archive'}}
*
***/

/** Function saveAjaxOrder() called by wp_ajax hooks: {'update-custom-type-order'} **/
/** Parameters found in function saveAjaxOrder(): {"post": ["interface_sort_nonce", "order"]} **/
function saveAjaxOrder() 
                {
                    
                    set_time_limit(600);
                    
                    global $wpdb;
                    
                    $nonce      =   $_POST['interface_sort_nonce'];
                    
                    //verify the nonce
                    if (! wp_verify_nonce( $nonce, 'interface_sort_nonce') )
                        die();
                        
                    if ( ! current_user_can( $this->functions->get_required_capability( ) ) )
                        die();
                    
                    parse_str( sanitize_text_field( wp_unslash( $_POST['order'] ) ) , $data );
                    
                    if (is_array($data))
                        {
                            foreach($data as $key => $values ) 
                                {
                                    if ( $key === 'item' ) 
                                        {
                                            foreach( $values as $position => $id ) 
                                                {
                                                    //sanitize
                                                    $id =   intval ( $id ); 
                                                    
                                                    $data = array('menu_order' => $position);
                                                    
                                                    //Deprecated, rely on pto/save-ajax-order
                                                    $data = apply_filters('post-types-order_save-ajax-order', $data, $key, $id);
                                                    
                                                    $data = apply_filters('pto/save-ajax-order', $data, $key, $id);
                                                    
                                                    $wpdb->update( $wpdb->posts, $data, array('ID' => $id) );
                                                } 
                                        } 
                                    else 
                                        {
                                            foreach( $values as $position => $id ) 
                                                {
                                                    
                                                    //sanitize
                                                    $id =   intval ( $id );
                                                    
                                                    $data = array('menu_order' => $position, 'post_parent' => str_replace('item_', '', $key));
                                                    
                                                    //Deprecated, rely on pto/save-ajax-order 
                                                    $data = apply_filters('post-types-order_save-ajax-order', $data, $key, $id);
                                                    
                                                    $data = apply_filters('pto/save-ajax-order', $data, $key, $id);
                                                    
                                                    $wpdb->update( $wpdb->posts, $data, array('ID' => $id) );
                                                }
                                        }
                                }
                            
                        }
                        
                    //trigger action completed
                    do_action('PTO/order_update_complete');
                    
                    CptoFunctions::site_cache_clear();
                }


/** Function saveArchiveAjaxOrder() called by wp_ajax hooks: {'update-custom-type-order-archive'} **/
/** Parameters found in function saveArchiveAjaxOrder(): {"post": ["post_type", "paged", "archive_sort_nonce", "order"]} **/
function saveArchiveAjaxOrder()
                {
                    
                    global $wpdb, $userdata;
                    
                    $post_type  =   preg_replace( '/[^a-zA-Z0-9_\-]/', '', sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) );
                    $paged      =   filter_var ( sanitize_text_field( wp_unslash( $_POST['paged'] ) ), FILTER_SANITIZE_NUMBER_INT);
                    $nonce      =   ( isset( $_POST['archive_sort_nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['archive_sort_nonce'] ) ) : '';
                    
                    //verify the nonce
                    if ( ! wp_verify_nonce( $nonce, 'CPTO_archive_sort_nonce' ) )
                        die();
                        
                    if ( ! current_user_can( $this->functions->get_required_capability( $post_type ) ) )
                        die();
                    
                    parse_str( sanitize_text_field( wp_unslash( $_POST['order'] ) ) , $data );
                    
                    if (!is_array($data)    ||  count($data)    <   1)
                        die();
                    
                    //retrieve a list of all objects
                    $results        =   $wpdb->get_results( $wpdb->prepare("SELECT ID FROM ". $wpdb->posts ." 
                                                            WHERE post_type = %s AND post_status IN ('publish', 'pending', 'draft', 'private', 'future', 'inherit')
                                                            ORDER BY menu_order, post_date DESC", $post_type) );
                    
                    if (!is_array($results)    ||  count($results)    <   1)
                        die();
                    
                    //create the list of ID's
                    $objects_ids    =   array();
                    foreach($results    as  $result)
                        {
                            $objects_ids[]  =   (int)$result->ID;   
                        }
                    
                    global $userdata;
                    if ( $post_type === 'attachment' )
                        $objects_per_page   =   get_user_meta( $userdata->ID , 'upload_per_page', TRUE );
                        else
                        $objects_per_page   =   get_user_meta( $userdata->ID ,'edit_' .  $post_type  .'_per_page', TRUE );
                    $objects_per_page   =   apply_filters( "edit_{$post_type}_per_page", $objects_per_page );
                    if(empty($objects_per_page))
                        $objects_per_page   =   20;
                    
                    $edit_start_at      =   $paged  *   $objects_per_page   -   $objects_per_page;
                    $index              =   0;
                    for($i  =   $edit_start_at; $i  <   ($edit_start_at +   $objects_per_page); $i++)
                        {
                            if(!isset($objects_ids[$i]))
                                break;
                                
                            $objects_ids[$i]    =   (int)$data['post'][$index];
                            $index++;
                        }
                    
                    //update the menu_order within database
                    foreach( $objects_ids as $menu_order   =>  $id ) 
                        {
                            //sanitize
                            $id =   intval ( $id );
                            
                            $data = array(
                                            'menu_order' => $menu_order
                                            );
                            
                            //Deprecated, rely on pto/save-ajax-order
                            $data = apply_filters('post-types-order_save-ajax-order', $data, $menu_order, $id);
                            
                            $data = apply_filters('pto/save-ajax-order', $data, $menu_order, $id);
                            
                            $wpdb->update( $wpdb->posts, $data, array('ID' => $id) );
                            
                            clean_post_cache( $id );
                        }
                        
                    //trigger action completed
                    do_action('PTO/order_update_complete');
                    
                    CptoFunctions::site_cache_clear();                
                }


