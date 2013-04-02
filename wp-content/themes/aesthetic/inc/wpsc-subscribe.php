<?php
/**
 * Subscribe functions
 */
 
/**
 * Subscribe template
 */
function aesthetic_subscribe_template(){
?>
	<div id="popup_register">
		<form name="sub_form" id="sub_form" action="add_subscription" method="post">
			<input type="hidden" name="action" value="add_subscription" />
			<div class="reg_form">
				<div class="formField">                        
					 <input type="text" name="sub_name" maxlength="100" />
				 </div>
				 <div class="formField">                        
					 <input type="text" name="sub_email" maxlength="100" />
				 </div>
				 <div class="formField">                        
					 <input type="submit"  value="Submit" class="btnSubmit" />
				 </div>
				<div class="clear"></div>	
				<div class="bottom">
					<a class="sub_form_close" href="javascript:;"><span>I ALREADY HAVE AN ACCOUNT</span></a>
				</div>
			</div>
		</form>		
	</div>    
<?php
	exit;
}
add_action( 'wp_ajax_get_subscribe_template', 'aesthetic_subscribe_template' );
add_action( 'wp_ajax_nopriv_get_subscribe_template', 'aesthetic_subscribe_template' );

/**
 * Insert a subscriber
 */
function aesthetic_insert_subscribe( $insert = array() ){
	global $wpdb;

	if( sizeof( $insert ) == 0 )
		return false;
		
	$table = $wpdb->prefix .'wpsc_subscribers';
	
	if( $insert['subscriber_email'] ){
		return $wpdb->insert( $table, $insert );
	}
	
	return false;
}

/**
 * Check whether a subscriber email 
 */
function aesthetic_is_subscriber_exists( $email = '' ){
	global $wpdb;
	
	$table = $wpdb->prefix .'wpsc_subscribers';
	$qry = "SELECT * FROM $table WHERE `subscriber_email` = '$email'";
	
	return $wpdb->get_row( $qry );
}

/**
 * Add a subscription via ajax
 */
function aesthetic_add_subscription(){

	$insert = array(
		'subscriber_name' => $_POST['sub_name'],
		'subscriber_email' => $_POST['sub_email'],
		'subscriber_created' => current_time( 'timestamp' ),
		'subscriber_status' => '1'
 	);
	
	if( aesthetic_is_subscriber_exists( $insert['subscriber_email'] ) )
		aesthetic_action_msg( 'This email has been already subscribed !' );
	
	if( aesthetic_insert_subscribe( $insert ) )
		aesthetic_action_msg( 'Thank you for your subscription', false );
	else
		aesthetic_action_msg( 'Something went wrong' );
}
add_action( 'wp_ajax_add_subscription', 'aesthetic_add_subscription' );
add_action( 'wp_ajax_nopriv_add_subscription', 'aesthetic_add_subscription' );


