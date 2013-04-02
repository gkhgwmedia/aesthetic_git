<?php
/**
 * Plugin support function
 */

/**
 * Facebook login button
 */
function aesthetic_fb_login( $redirect_to = '',  $mode = 'sign_up' ){
	if( !function_exists( 'new_fb_login' ) )
		return;
	
	$output = '
		<script>
			jQuery(document).ready(function(){
				jQuery( "#fconnect" ).click(function( event ){
					var role = jQuery( "input:radio[name=role]:checked" ).val();
					var redirectTo = jQuery( this ).attr( "href" );
					
					if( typeof( role ) != "undefined" )
						redirectTo += "&role="+ role;
					
					window.location = redirectTo;
					
					event.preventDefault();
				});
			});
		</script>
	';
		
	$url = add_query_arg( array( 'loginFacebook' => '1', 'redirect' => $redirect_to ), site_url( 'wp-login.php' ) );
	
	if( $mode == 'sign_up' )
		$output .= '
			<div>
				<input type="radio" id="check_subscriber" name="role" value="subscriber" checked="checked" /> Buyer
				<input type="radio" id="check_merchant" name="role" value="author" /> Merchant
			</div>
	';
	
	$output .= '<a id="fconnect" class="fconnect" href="'. $url .'"><img src="'. get_template_directory_uri() .'/images/fb_connect.png" alt="Facebook connect" /></a>';
	
	echo $output;
}

// Remove default facebook connect button
remove_action( 'login_form', 'new_add_fb_login_form' );

/**
 * Update the registered user's role
 */
function aesthetic_update_user_role( $ID, $user_profile = array() ){
	$role = $_GET['role'];

	if( !get_role( $role ) )
		return false;
		
	require_once( ABSPATH . WPINC . '/registration.php');
		
	$update_data = array(
		'ID' => $ID,
		'role' => $role
	);
	
	return wp_update_user( $update_data );
}
add_action( 'nextend_fb_user_registered', 'aesthetic_update_user_role' );


?>