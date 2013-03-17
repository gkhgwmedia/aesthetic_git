<?php

/* ----------------------------------------
* load CSS
----------------------------------------- */

function eto_admin_styles() {
	if( is_admin() ) {
		wp_enqueue_style('thickbox');
		wp_enqueue_style('eto-admin', ETO_PLUGIN_DIR.'includes/css/admin-styles.css');
		wp_enqueue_style('jquery-ui-custom', ETO_PLUGIN_DIR.'includes/css/jquery-ui-custom.css');
	}
}

if (isset($_GET['page']) && ( $_GET['page'] == 'eto-settings' ) ) {
	add_action('init', 'eto_admin_styles');
}

?>