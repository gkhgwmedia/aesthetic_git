<?php

/* ----------------------------------------
* load scripts
----------------------------------------- */
function eto_admin_scripts() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-slider');
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_script('colorpicker');
	wp_enqueue_script('jquery-ui-sortable');

	wp_enqueue_script('eto-admin-scripts', ETO_PLUGIN_DIR . 'includes/js/admin-scripts.js');
	wp_enqueue_script('media-uploader', ETO_PLUGIN_DIR . 'includes/js/media-uploader.js');
	wp_enqueue_script('jscolor', ETO_PLUGIN_DIR . 'includes/js/jscolor.js');
}

if (isset($_GET['page']) && ( $_GET['page'] == 'eto-settings') ){
	add_action('admin_print_scripts', 'eto_admin_scripts');
}


?>