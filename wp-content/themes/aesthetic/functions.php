<?php
/**
 * Aesthetic Today functions and definitions.
 *
 * @package WordPress
 * @subpackage Aesthetic_Today
 * @since Aesthetic Today 1.0
 */
 
 
/**
 * Theme setup
 */
function aesthetic_setup(){

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// This theme supports a variety of post formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'quote', 'status' ) );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'aesthetic' ) );
	register_nav_menu( 'deal_menu', __( 'Deal Menu', 'aesthetic' ) );
	register_nav_menu( 'primary_user', __( 'Primary User Menu', 'aesthetic' ) );
	register_nav_menu( 'new_to', __( 'New to Aethetic Today', 'aesthetic' ) );
	/*
	 * This theme supports custom background color and image, and here
	 * we also set up the default background color.
	 */
	add_theme_support( 'custom-background', array(
		'default-color' => 'e6e6e6',
	) );

	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 624, 9999 ); // Unlimited height, soft crop
	
	// User default data
	aesthetic_user_default();
	
	//aesthetic_user_roles();	
}
add_action( 'after_setup_theme', 'aesthetic_setup' );

/**
 * Includes
 */
// Include custom header
include_once( get_template_directory() .'/inc/custom-header.php' );

// Include wpec functions
include_once( get_template_directory() .'/inc/wpec-functions.php' );

// Include widgets
include_once( get_template_directory() .'/inc/widgets.php' );

// Include support functions
include_once( get_template_directory() .'/inc/support-functions.php' );

/*
 * Load script and styles
 */
function aesthetic_script_style(){

	// jQuery UI, slider
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-slider' );
	
	wp_enqueue_script( 'plupload' );
	
	//wp_enqueue_script( 'jquery-ui-slider' );

	wp_enqueue_script( 'aw-showcase', get_template_directory_uri() . '/js/jquery.aw-showcase.js', array( 'jquery' ) );
	wp_enqueue_script( 'zoombox', get_template_directory_uri() . '/js/zoombox.js', array( 'jquery' ) );
	wp_enqueue_script( 'jquery-validate', get_template_directory_uri() . '/js/jquery.validate.js', array( 'jquery' ) );
	wp_enqueue_script( 'jquery-inlinelabel', get_template_directory_uri() . '/js/jquery.infieldlabel.js', array( 'jquery' ) );
	wp_enqueue_script( 'jquery-mosaic', get_template_directory_uri() . '/js/mosaic.1.0.1.js', array( 'jquery' ) );
	
	// jQuery time coundown
	wp_enqueue_script( 'jquery-countdown', get_template_directory_uri() . '/js/jquery-countdown/jquery.countdown.js', array( 'jquery' ) );
	
	// jQuery UI CSS
	wp_enqueue_style( 'jquery-ui-css', 'http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css' );
	
	wp_enqueue_style( 'aesthetic-style', get_stylesheet_uri() );
	wp_enqueue_style( 'zoombox-style',  get_template_directory_uri() . '/css/zoombox.css' );
	wp_enqueue_style( 'mosaic-style',  get_template_directory_uri() . '/css/mosaic.css' );
	
	// Tooltip
	wp_enqueue_script( 'jquery-tooltipser', get_template_directory_uri() . '/js/jquery-tooltipser/js/jquery.tooltipster.js', array( 'jquery' )  );
	wp_enqueue_style( 'jquery-tooltipser-style',  get_template_directory_uri() . '/js/jquery-tooltipser/css/tooltipster.css' );
}
add_action( 'wp_enqueue_scripts', 'aesthetic_script_style' );

/**
 * Load admin script and style
 */
function aesthetic_admin_script_style(){

	// jQuery UI CSS
	wp_enqueue_style( 'jquery-ui-css', 'http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css' );
		
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_script( 'jquery-ui-slider' );
	
	// Timepicker
	wp_enqueue_script( 'jquery-timepicker', get_template_directory_uri() . '/timepicker/jquery-ui-timepicker-addon.js', array( 'jquery-ui-core' ), false, true );
	wp_enqueue_style( 'jquery-timepicker-css',  get_template_directory_uri() . '/timepicker/jquery-ui-timepicker-addon.css' );
}
add_action( 'wp_enqueue_scripts', 'aesthetic_admin_script_style' );
add_action( 'admin_enqueue_scripts', 'aesthetic_admin_script_style' );

/**
 * For title
 */
function aesthetic_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'aesthetic' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'aesthetic_wp_title', 10, 2 );

/**
 * Common frontend header content
 */
function aesthetic_common_header(){
?>
	<script>
		jQuery(document).ready(function(){
			jQuery( '#searchform label' ).inFieldLabels();
		});
	</script>
<?php 
}
add_action( 'wp_head', 'aesthetic_common_header' );

/**
 * primary menu
 */
function aesthetic_primary_menu( $location = 'primary' ){
	global $post;
	$menu_name = $location;

	if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
		$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
		$menu_items = wp_get_nav_menu_items($menu->term_id);
		
		$menu_count = count( $menu_items );
		foreach( (array) $menu_items as $key => $menu_item ){
//		echo '<pre>';
//		print_r( $menu_item );
//		echo '<pre>';
//		exit;

		$class = '';
		//echo 'pst id: '. $post->ID .' = '. $menu_item->object_id;
		if( $post->ID == $menu_item->object_id )
			$class = 'active';
?>
	<li class="<?php echo $class; ?>">
		<a href="<?php echo $menu_item->url; ?>"><?php echo $menu_item->title; ?>
			<br /><span class="subtext"><?php echo $menu_item->attr_title; ?></span>
		</a>
	</li>
<?php
		}
	}
}

/**
 * Deal menu
 */
function aesthetic_deal_menu(){
	$menu_name = 'deal_menu';
	
	if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
		$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
		$menu_items = wp_get_nav_menu_items($menu->term_id);
		
		$menu_count = count( $menu_items );
		foreach( (array) $menu_items as $key => $menu_item ){
			$url = $menu_item->url;
			if( $menu_item->subtitle )
				$url .= '?query='. $menu_item->subtitle;
				
			if( isset( $_GET['query'] ) && $_GET['query'] == $menu_item->subtitle )
				$class = 'active';
			else
				$class = '';
?>
	<li>
		<a class="<?php echo $class; ?>" href="<?php echo $url; ?>"><?php echo $menu_item->title; ?></a>
	</li>
<?php
		}
	}
}

/**
 * Aesthetic widgets
 */
function aesthetic_widgets_init(){

	register_widget( 'Aesthetic_Categories_Widget' );
	register_widget( 'Aesthetic_Price_Range_Widget' );
	register_widget( 'Aesthetic_Featured_Widget' ); 

	register_sidebar(array(
		'name'          => 'Deals sidebar',
		'id'            => 'deals_sidebar',
		'description'   => 'Deals sidebar to show the filtering options such as category list, price range etc..',
		'class'         => '',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h1>',
		'after_title'   => "</h1>"
	));
	
	register_sidebar(array(
		'name'          => 'Page sidebar',
		'id'            => 'page_sidebar',
		'description'   => 'Aesthetic today page listing',
		'class'         => '',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h1>',
		'after_title'   => "</h1>"
	));
}
add_action( 'widgets_init', 'aesthetic_widgets_init' );

/**
 * Aesthetic merchant role
 */
function aesthetic_user_roles(){
	 // gets the author role
    $role = get_role( 'subscriber' );

    // This only works, because it accesses the class instance.
    // would allow the author to edit others' posts for current theme only
    $role->add_cap( 'manage_products' );
}

/**
 * Aesthetic user defaults
 */
function aesthetic_user_default(){
	global $aes_emirates;
	
	$aes_emirates = array(
		'4' => 'Abu Dhabi',
		'5' => 'Ajman',
		'2' => 'Dubai',
		'7' => 'Fujairah',
		'8' => 'Ras Al Kaimah',
		'3' => 'Sharjah',
		'6' => 'Umm Al Quwain'
	);
}

/**
 * Check whether an email already exists or not
 */
function aesthetic_is_email_exists(){
	if( $_POST['email'] ){
		$result = email_exists( $_POST['email'] );
	}
	if( $result )
		exit( 'false' );
	else
		exit( 'true' );
}
add_action( 'wp_ajax_nopriv_email_check', 'aesthetic_is_email_exists' );

/**
 * Register a user
 */
function aesthetic_user_register(){
	if( isset( $_POST['reg_submit'] ) ){
		
		if( !wp_verify_nonce( $_POST['aes_register'], 'aes_register_action' ) )
			wp_die( 'Sorry you are not allow to access' );
		
		$user = array(
			'user_pass' => $_POST['confirm_password'],
			'user_login' => $_POST['email'],
			'user_email' => $_POST['email'],
			'first_name' => $_POST['first_name'],
			'last_name' => $_POST['last_name'],
			'role' => $_POST['role']
		);
		
		foreach( $user as $val ){
			if( empty( $val ) )
				wp_die( 'Required fields are mandatory' );
		}
		
		// Email existance check
		if( email_exists( $user['user_email'] ) !== false )
			wp_die( 'An Email ID has been already registered' );
		
		$user_ret = wp_insert_user( $user );
		
		if( is_wp_error( $user_ret ) )
			wp_die( $user_ret->get_error_message() );
			
		// Meta action
		if( $user_ret ){
			update_user_meta( $user_ret, '_aes_contact', sanitize_text_field( $_POST['contact'] ) );
			update_user_meta( $user_ret, '_aes_address_one', sanitize_text_field( $_POST['address_one'] ) );
			update_user_meta( $user_ret, '_aes_address_two', sanitize_text_field( $_POST['address_two'] ) );
			update_user_meta( $user_ret, '_aes_address_pin', sanitize_text_field( $_POST['pin'] ) );
			update_user_meta( $user_ret, '_aes_address_emirate', sanitize_text_field( $_POST['emirate'] ) );
		}
			
		aesthetic_login_action( $user['user_email'], $user['user_pass'] );
	}
}
add_action( 'init', 'aesthetic_user_register' );

/**
 * Signon user action
 */
function aesthetic_login_action( $user_login = '', $user_pass = '' ){
	
	if( empty( $user_login ) || empty( $user_pass ) )
		wp_die( 'User name and password are required' );
		
	$user = array(
		'user_login' => $user_login,
		'user_password' => $user_pass
	);
	
	$user_ret = wp_signon( $user );
	
	if( is_wp_error( $user_ret ) )
		wp_die( $user_ret->get_error_message() );
		
	// Redirect to the product page
	wp_redirect( get_permalink( 17 ) );
	exit;
}

/**
 * Signon user
 */
function aesthetic_login(){
	if( isset( $_POST['login_submit'] ) ){
		aesthetic_login_action( $_POST['user_name'], $_POST['user_pass'] );
	}
}
add_action( 'init', 'aesthetic_login' );

/**
 * save the user meta details
 */
function aesthetic_save_user_meta( $user_id = '', $meta = array() ){
	 
	if( !$user_id || sizeof( $meta ) == 0 )
		return false;
	
	foreach( $meta as $key => $val ){
		update_user_meta( $user_id, $key, $val );		
	}
	
	return true;
}

/**
 * update user profile data
 */
function aesthetic_user_profile_update(){

	$ret = array();
	
	if( !wp_verify_nonce( $_POST['profile_update_nonce'], 'profile_update' ) ){
		aesthetic_action_msg( 'You are not allow to do this' );
	}
	
	if( isset( $_POST['profile_submit'] ) ){
		$user_meta = array(
			'first_name' => sanitize_text_field( $_POST['first_name'] ),
			'last_name' => sanitize_text_field( $_POST['last_name'] ),
			'_aes_contact' => sanitize_text_field( $_POST['contact'] )
		);
		
		foreach( $user_meta as $val ){
			if( empty( $val ) ){
				aesthetic_action_msg( 'Required fields are mandatory' );
			}
		}
		
		if( aesthetic_save_user_meta( get_current_user_id(), $user_meta ) ){
			aesthetic_action_msg( 'Updated !', false );
		}else{
			aesthetic_action_msg( 'Not updated' );
		}
	}
}
add_action( 'wp_ajax_profile_update', 'aesthetic_user_profile_update' );

/**
 * User address update
 */ 
function aesthetic_user_address_update(){
	if( !wp_verify_nonce( $_POST['address_update_nonce'], 'address_update' ) )
		aesthetic_action_msg( 'You are not allow to do this' );
	
	if( isset( $_POST['address_submit'] ) ){
		$user_meta = array(
			'_aes_address_one' => sanitize_text_field( $_POST['address_one'] ),
			'_aes_address_two' => sanitize_text_field( $_POST['address_two'] ),
			'_aes_address_pin' => sanitize_text_field( $_POST['pin'] ),
			'_aes_address_emirate' => sanitize_text_field( $_POST['emirate'] ),
		);
		
		foreach( $user_meta as $val ){
			if( empty( $val ) )
				aesthetic_action_msg( 'Required fields are mandatory' );
		}
		
		if( aesthetic_save_user_meta( get_current_user_id(), $user_meta ) ){
			aesthetic_action_msg( 'Updated !', false );
		}else{
			aesthetic_action_msg( 'Not saved' );
		}
	}
}
add_action( 'wp_ajax_address_update', 'aesthetic_user_address_update' );

/**
 * Check the password
 */
function aesthetic_check_password( $plain, $hashed ){
	if( empty( $plain ) || empty( $hashed ) )
		return false;
	
	require_once( ABSPATH . WPINC . '/class-phpass.php' );
	
	$wp_hasher = new PasswordHash( 8, TRUE );
	return $wp_hasher->CheckPassword( $plain, $hashed );
}

/**
 * Change password
 */
function aesthetic_change_password(){
	global $current_user;
	
	if( !wp_verify_nonce( $_POST['security_update_nonce'], 'security_update' ) )
		aesthetic_action_msg( 'You are not allow to do this' );
	
	require_once( ABSPATH . WPINC . '/registration.php' );
	
	if( !$current_user )
		aesthetic_action_msg( 'You are not allow to do this' );
		
	if( !aesthetic_check_password( $_POST['old_password'], $current_user->user_pass ) )
		aesthetic_action_msg( 'Old password is wrong' );
		
	if( !( $_POST['user_pass'] === $_POST['user_confirmpass'] ) )
		aesthetic_action_msg( 'New password do not match' );
		
	$user_det = array(
		'ID' => $current_user->ID,
		'user_pass' => $_POST['confirm_password']
	);
	
	$user_id = wp_update_user( $user_det );
	
	if( $user_id )
		aesthetic_action_msg( 'Updated', false );
		
}
add_action( 'wp_ajax_security_update', 'aesthetic_change_password' );

/**
 * Action message
 */
function aesthetic_action_msg( $msg = '', $error = true ){
	$ret = array(
		'message' => $msg,
		'type' => ( $error ) ? 'error' : 'success'
	);
	
	echo json_encode( $ret );
	exit;
}

/**
 * Replace the admin bar menu links
 */
function aesthetic_change_user_action_links(){
	global $wp_admin_bar;
	
	// Edit profile
	$node = $wp_admin_bar->get_node( 'edit-profile' );
	if( $node ){
		$node->href = get_page_link( 158 );
		
		$wp_admin_bar->add_node( $node );
	}
	
	// My account
	$node = $wp_admin_bar->get_node( 'my-account' );
	if( $node ){
		$node->href = get_page_link( 158 );
		
		$wp_admin_bar->add_node( $node );
	}
	
	// user info
	$node = $wp_admin_bar->get_node( 'user-info' );
	if( $node ){
		$node->href = get_page_link( 158 );
		
		$wp_admin_bar->add_node( $node );
	}
	
	// Logout
	$node = $wp_admin_bar->get_node( 'logout' );
	if( $node ){
		$node->href = wp_logout_url( get_page_link( 145 ) );
		
		$wp_admin_bar->add_node( $node );
	}
}
add_action( 'admin_bar_menu', 'aesthetic_change_user_action_links' ); 

/**
 * Change the admin logo
 */
function aesthetic_admin_logo(){
?>
	<style>
		.login h1 a{
			background: url(<?php echo get_stylesheet_directory_uri(); ?>/images/logo.png) no-repeat scroll center top transparent;
			display: block;
			height: 111px;
			overflow: hidden;
			padding-bottom: 15px;
			text-indent: -9999px;
			width: 326px;
		}
	</style>
<?php
}
add_action( 'login_head', 'aesthetic_admin_logo' );

function aesthetic_remove_author_menu(){
	if( current_user_can( 'author' ) ){
		remove_menu_page( 'edit.php' );
		remove_menu_page( 'edit-comments.php' );
		remove_menu_page( 'tools.php' );
	}
}
add_action( 'admin_menu', 'aesthetic_remove_author_menu' ); 

/**
 * Change user role name
 */
function aesthetic_change_user_role_name(){
	global $wp_roles;
	
	$wp_roles->roles['subscriber']['name'] = 'Buyer';
    $wp_roles->role_names['subscriber'] = 'Buyer';
	
	$wp_roles->roles['author']['name'] = 'Merchant';
    $wp_roles->role_names['author'] = 'Merchant';
}
add_action( 'init', 'aesthetic_change_user_role_name' );

/**
 * Remove the user roles
 */
function aesthetic_remove_user_role(){
	remove_role( 'super_admin' );
	remove_role( 'contributor' );
}
add_action( 'init', 'aesthetic_remove_user_role' );

/**
 * 
 */
function aesthetic_display_user_meta( $user ){
	global $aes_emirates;
	
	$contact = get_user_meta( $user->ID, '_aes_contact', true );
	$address_one = get_user_meta( $user->ID, '_aes_address_one', true );
	$address_two = get_user_meta( $user->ID, '_aes_address_two', true );
	$pin = get_user_meta( $user->ID, '_aes_address_pin', true );
	$emirate_id = get_user_meta( $user->ID, '_aes_address_emirate', true );
	
	$emirate_name = $aes_emirates[$emirate_id];
?>
	<h3>Others</h3>
	<table class="form-table">
		<tr>
			<th>Contact number</th>
			<td><?php echo $contact; ?></td>
		</tr>
		<tr>
			<th>Address one</th>
			<td><?php echo $address_one; ?></td>
		</tr>
		<tr>
			<th>Address two</th>
			<td><?php echo $address_two; ?></td>
		</tr>
		<tr>
			<th>PO Box</th>
			<td><?php echo $pin; ?></td>
		</tr>
		<tr>
			<th>Emirate</th>
			<td><?php echo $emirate_name; ?></td>
		</tr>
	</table>
<?php
}
add_action( 'edit_user_profile', 'aesthetic_display_user_meta' );

