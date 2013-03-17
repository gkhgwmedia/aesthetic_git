<?php
/*
Plugin Name: Easy Theme Options
Plugin URL: http://wpboxed.com
Description: This plugin adds the ability to create as many site options as you want to customize your theme or your website.
Version: 1.0
Author: Rémi Corson
Author URI: http://wpboxed.com
Contributors: Rémi Corson, Tammy Hart
*/

/* ----------------------------------------
* Plugin Globals
----------------------------------------- */

global $eto_base_dir;
$eto_base_dir = dirname(__FILE__);

global $eto_prefix;
$eto_prefix = 'eto_';

if(!defined('ETO_PLUGIN_DIR')) {
	define('ETO_PLUGIN_DIR', plugin_dir_url( __FILE__ ));
}

/* ----------------------------------------
* plugin text domain for translations
----------------------------------------- */

load_plugin_textdomain( 'eto', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

/* ----------------------------------------
* Includes
----------------------------------------- */

if( isset($_GET['page']) AND $_GET['page'] == 'eto-settings' ) {
	include($eto_base_dir . '/includes/styles.php');
	include($eto_base_dir . '/includes/options.php');
	include($eto_base_dir . '/includes/functions/eto_functions.php');
	include($eto_base_dir . '/includes/scripts.php');
	include($eto_base_dir . '/includes/help.php');
}

include($eto_base_dir . '/includes/shortcodes.php');

/* ----------------------------------------
* load plugin data
----------------------------------------- */

$eto_options = get_option('eto_settings');


/* ----------------------------------------
* add subpage in appearance menu
----------------------------------------- */

function eto_settings_menu() {
	// add settings page
	add_submenu_page(
					'themes.php', 
					__('Easy Theme Options Settings', 'eto'), 
					__('Theme Options', 'eto'),
					'manage_options', 
					'eto-settings', 
					'eto_settings_page');
	// add import export page
	add_submenu_page(
					'options-general.php', 
					__('Easy Theme Options import / export', 'eto'), 
					__('ETO Import / Export', 'eto'),
					'manage_options', 
					'eto-import-export', 
					'eto_import_export_page');
}
add_action('admin_menu', 'eto_settings_menu', 100);


/* ----------------------------------------
* register the plugin settings
----------------------------------------- */

function eto_register_settings() {

	// create whitelist of options
	register_setting( 'eto_settings_group', 'eto_settings' );
}
//call register settings function
add_action( 'admin_init', 'eto_register_settings', 100 );


/* ----------------------------------------
* create the submenu links in plugins page
----------------------------------------- */

function eto_plugin_action_links($links, $file) {
    static $this_plugin;
 
    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }
 
    // check to make sure we are on the correct plugin
    if ($file == $this_plugin) {
 
		// link to what ever you want
        $plugin_links[] = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/themes.php?page=eto-settings">'.__('Theme Options','eto').'</a>';
 
        // add the links to the list of links already there
		foreach($plugin_links as $link) {
			array_unshift($links, $link);
		}
    }
 
    return $links;
}
add_filter('plugin_action_links', 'eto_plugin_action_links', 10, 2);

/* ------------------------------------------------------------------*/
/* GET AN IMAGE FROM GLOBAL STRING */
/* ------------------------------------------------------------------*/

function eto_image($field_id,  $width = '', $height = '') {
	
	global $eto_options;
	
	if( isset($field_id) ) {
	
		// Get attachment data
		$image_data = wp_get_attachment_image_src( $eto_options["$field_id"], '' );
		
		//print_r( $image_data );
		
		// get URL
		$url = $image_data[0];
		
		// Height and width
		if( $height != '' && $width != '' ) {
			$height = $height;
			$width 	= $width;
		} else {
			$width = $image_data[1];
			$height = $image_data[2];
		}

		echo '<img src="'.$url.'" with="'.$width.'" height="'.$height.'"/>'; 
		
	}
}

/* ------------------------------------------------------------------*/
/* RETURN AN IMAGE URL FROM GLOBAL STRING */
/* ------------------------------------------------------------------*/

function eto_get_image($field_id) {
	
	global $eto_options;
	
	if( isset($field_id) ) {
	
		// Get attachment data
		$image_data = wp_get_attachment_image_src( $eto_options["$field_id"], '' );
		
		// get URL
		$url = $image_data[0];
	
		return $url;
		
	}
}

/* ----------------------------------------
* function to retrieve the get_option() value
----------------------------------------- */

function eto_get_option($option_name) {
	$eto_options = get_option('eto_settings');
	return $eto_options[$option_name];
}


/* ----------------------------------------
* create the settings page layout
----------------------------------------- */

function eto_settings_page() {
	
	global $eto_options;
		
	?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"><br /></div>
		<h2><?php _e('Easy Theme Options Settings', 'eto'); ?></h2>
		<?php
		if ( ! isset( $_REQUEST['settings-updated'] ) )
			$_REQUEST['settings-updated'] = false;
		?>
		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved', 'eto' ); ?></strong></p></div>

		<?php endif; ?>
		<form method="post" action="options.php" class="eto_options_form">

			<?php settings_fields( addslashes('eto_settings_group') ); ?>
			
			<?php eto_show_custom_tabs(); ?>

			<?php eto_show_custom_fields(); ?>
			
			<!-- save the options -->
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'eto' ); ?>" />
			</p>
			
		</form>
	</div><!--end .wrap-->
	<?php
}


/* ----------------------------------------
* create the settings page layout
----------------------------------------- */

function eto_import_export_page() {

	$eto_settings = get_option( 'eto_settings' );
	$currentsettings = "";
	if ( isset( $_POST['import'] ) && trim($_POST['eto_import_settings']) != "" ) {
		$currentsettings = $_POST['gpp_import_settings'];
	} elseif ( isset( $eto_settings ) && ( $eto_settings != "" ) ) {	
		$currentsettings = base64_encode( serialize( $eto_settings ) );		
	} 
		
	?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"><br /></div>
		<h2><?php _e('Easy Theme Options - Import / Export', 'eto'); ?></h2>
		<?php if ( isset( $_POST['import'] ) && $_POST['eto_import_settings'] != "" ) { ?>
		<div class="updated fade"><p><strong><?php _e('Settings imported successfully!', 'eto'); ?></strong></p></div>
		<?php } ?>
		
	<div class="wrap" id="transport_settings">

		<p class="description"><?php _e('Here you can import and export Easy Theme Options settings from one installation to another.', 'eto'); ?></p>
		<div class="option option-textarea">
			<div class="option-inner">
				<label class="titledesc"><h3><?php _e('Import Options', 'eto'); ?></h3></label>
				<div class="formcontainer">
					<div class="forminp">
						<form id="impexp" method="post" action="#">
						<textarea rows="8" cols="60" id="eto_import_settings" name="eto_import_settings" class=""></textarea><br />
						<div class="desc"><?php _e('Paste the encoded text from export options field from your development site or another installation.', 'eto'); ?></div>
						<input type="submit" value="<?php _e('Import', 'eto'); ?>" id="import" name="import" class="button-primary" onClick="return confirm('<?php _e('Are you sure you want to import the settings?', 'eto'); ?>')"> 
						</form>
					</div>
					
				</div>
			</div>
		</div>
		<div class="option option-textarea">
			<div class="option-inner">
				<label class="titledesc"><h3><?php _e('Export Options', 'eto'); ?></h3></label>
				<div class="formcontainer">
					<div class="forminp">
						<textarea rows="8" cols="60" id="eto_export_settings" name="eto_export_settings" class="" readonly="readonly"><?php echo $currentsettings; ?></textarea>					
					</div>
					<div class="desc"><?php _e('Copy this text to import settings into another installation.', 'eto'); ?></div>
				</div>
			</div>
		</div>
	 </div>

	</div><!--end .wrap-->
	<?php
}

add_action( 'admin_menu', 'eto_import_settings' );
function eto_import_settings(){
	
	global $eto_prefix;

	if ( isset( $_POST['import'] ) && trim($_POST['eto_import_settings']) != "" ) {	
		update_option( $eto . 'settings', $_POST['eto_import_settings'] );
	}
}

/* ------------------------------------------------------------------*/
/* UNINSTALL PLUGIN */
/* ------------------------------------------------------------------*/

function eto_uninstall () 
{
    // Uncomment the line above to delete all data at plugin uninstall
    /* delete_option('eto_settings'); */
}

register_deactivation_hook( __FILE__, 'eto_uninstall' );