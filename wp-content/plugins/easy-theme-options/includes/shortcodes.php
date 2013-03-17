<?php

/* ------------------------------------------------------------------*/
/* SHORTCODES */
/* ------------------------------------------------------------------*/

// Display Simple Content [option id="field_id"]
function eto_shortcode_option( $atts, $content = null)	{

	global $eto_options;
	
	extract( shortcode_atts( 
	array(
	      'id' => ''
	      ), $atts ) );

	$content =$eto_options["$id"];
	
	return $content;
}
add_shortcode('option', 'eto_shortcode_option');

// Display Image [image id="field_id"]
function eto_shortcode_image( $atts, $content = null)	{

	global $eto_options;

	extract( shortcode_atts( 
	array(
	      'id' => ''
	      ), $atts ) );
	      
	// Get attachment data
	$image_data = wp_get_attachment_image_src( $eto_options["$id"], '' );
	
	// get URL
	$url = $image_data[0];

	$content = '<img src="'.$url.'" />';
	
	return $content;
}
add_shortcode('image', 'eto_shortcode_image');

?>