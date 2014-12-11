<?php
/*
Plugin Name: ACF-form post create
Description: Шорткод для добавления постов с формой ACF
Author: CasePress
Version: 0.1
*/


function apc_acf_presave_filter( $post_id )
{
	$pos = strpos($post_id, 'apc');
	if ($pos === false) 
    {
        return $post_id;
    }
 
	$data = explode(':', $post_id);
	
    $post = array(
        'post_status'  => 'draft' ,
        'post_title'  => 'New post' ,
        'post_type'  => $data[1] ,
    );  
 
	
    // insert the post
    $post_id = wp_insert_post( $post ); 
	
	if ( !empty($data[2]) && !empty($data[3]) )
		wp_set_post_terms( $post_id, $data[3], $data[2] );
 
    // update $_POST['return']
    $_POST['return'] = add_query_arg( array('post_id' => $post_id), $_POST['return'] );    
 
    // return the new ID
    return $post_id;
}
 
add_filter('acf/pre_save_post' , 'apc_acf_presave_filter' );




add_shortcode('acp','acp');
function acp($atts)
{
	extract( shortcode_atts( array(
		'post_type' => 'post',
		'taxonomy' => '',
		'term_id' => '',
		'acf_id' => '',
	), $atts ) );

	$post_data = 'apc'.':'.$atts['post_type'].':'.$atts['taxonomy'].':'.$atts['term_id'];
	$args = array(
		'post_id' => $post_data,
		'field_groups' => array( $atts['acf_id'] )
	);
	echo '<div class="acp_container">';
		acf_form( $args ); 
	echo '</div>';
}