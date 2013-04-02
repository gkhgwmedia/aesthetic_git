<?php
/**
 * WPSC Notification
 */
 
/**
 * Update log notify status
 */
function aesthetic_update_log_notify( $log_id = '' ){
	global $wpdb;

	if( empty( $log_id ) )
		return false;

	$ret = $wpdb->update(
		WPSC_TABLE_PURCHASE_LOGS,
		array(
			'email_sent' => '1'
		),
		array(
			'id' => $log_id
		),
		array( '%d', '%d' )
	);
	
	return $ret;
}

/**
 * Notification to an admin about a new deal
 */
function aesthetic_new_deal_to_admin( $deal_id ){

	if( get_post_type( $deal_id ) != 'wpsc-product' )
		return false;

	$to = get_option( 'admin_email' );
	$subject = 'New deal posted';
	
	$message = '
		Hi Admin,
		
		A following deal has been posted in '. get_option( 'blogname' ) .'
		
		Deal name: '. get_the_title( $deal_id ) .'
		'. get_permalink( $deal_id ) .'
	';
	
	@wp_mail( $to, $subject, $message );
}
add_action( 'new_to_publish', 'aesthetic_new_deal_to_admin' );

/**
 * Deal return notification to the merchant
 */
function aesthetic_deal_return_notification( $log_obj = '', $log_content = '' ){ 
	
	$cart_obj = end( $log_content->allcartcontent );
	
	$deal_obj = get_post( $cart_obj->prodid );
	$merchant_obj = get_userdata( $deal_obj->post_author ); 
	
	$to = $merchant_obj->user_email;
	$subject = 'Deal return request';
	
	$message = '
		Hi '. ucfirst( $merchant_obj->user_firstname ) .',
		
		Your deal has been returned, details as follows,
		Deal name: '. $cart_obj->name .',
		Order ID: '. $log_obj->id;
	
	@wp_mail( $to, $subject, $message );
}
add_action( 'aesthetic_return_deal', 'aesthetic_deal_return_notification', 10, 2 );

/**
 * Deal return approval notification
 */
function aesthetic_deal_return_approve_notification( $log_obj = '', $log_content = '' ){
	
	$cart_obj = end( $log_content->allcartcontent );
	
	$user_obj = get_userdata( $log_obj->user_ID );
	
	$to = $user_obj->user_email;
	$subject = 'Deal return Approved';
	
	$message = '
		Hi '. ucfirst( $user_obj->user_firstname ) .',
		
		Your deal return has been approved by the merchant, details as follows,
		Deal name: '. $cart_obj->name .',
		Order ID: '. $log_obj->id;
	
	@wp_mail( $to, $subject, $message );
	
}
add_action( 'aesthetic_return_deal_approve', 'aesthetic_deal_return_approve_notification', 10, 2 );

/**
 * Purchase reciept for user
 */
function aesthetic_order_reciept( $det_arr = array() ){
	global $wpsc_gateways;
//	echo '<pre>';
//	print_r( $det_arr );
//	echo '</pre>';

	if( $det_arr['purchase_log']['email_sent'] == 1 )
		return false;

	$user_obj = get_userdata( $det_arr['purchase_log']['user_ID'] );
	
	$to = $user_obj->user_email;
	$subject = 'Purchase Reciept';
	
	$item_design = aesthetic_design_item_table( $det_arr['cart_item'], $to );
	
//	$message = '
//		Hi '. ucfirst( $user_obj->user_firstname ) .',
//		
//		Thank you for ordering with '. get_option( 'blogname' ) .'.
//		
//		Your order details,
//		
//		Order ID: '. $det_arr['purchase_log']['id'] .',
//		Transaction ID: '. $det_arr['purchase_log']['sessionid'] .',
//		Total price: '. wpsc_currency_display( $det_arr['purchase_log']['totalprice'] ) .',
//		Payment method: '. $wpsc_gateways[$det_arr['purchase_log']['gateway']]['display_name'] .'
//		
//		Deal					|	Quantiry
//		---------------------------------------------------------------- 				
//		'. $det_arr['cart_item']['name'] .'			  | '. $det_arr['cart_item']['quantity'] .'
//		----------------------------------------------------------------
//	';

	$message = '
		<div style="margin:20px;">
			<p>Hello ('. ucfirst( $user_obj->user_firstname ) .'),</p>
			<p>Thank you for purchasing a deal/s at '. ucfirst( get_option( 'blogname' ) ) .'! </p>
			<p>Please present a print-out of the form below upon redeeming your deal.</p>
			
			<div>'. $item_design .'</div>
			
			<ul style="list-style:none; padding:0">
				<li>* Deal redemption expires on (date).</li>
				<li>* Multiple deals may be purchased and redeemed at one time.</li>
				<li>* For treatments and services, advanced booking at the redemption centres is required.</li>
				<li>* Deal may not be redeemed in conjunction with any other offer, discount, or promotion.</li>
				<li>* For timings, location maps, and all other details about the redemption centres, please call them directly on the number listed above.</li>
			</ul>
			
			<p>We hope that you enjoy your product / purchase, and please visit Aesthetic Today again for more amazing deals!</p>
	
		</div>
	';
	
	$headers[] = 'MIME-Version: 1.0';
	$headers[] = 'Content-Type: text/html; charset=UTF-8';
	
	@wp_mail( $to, $subject, $message, $headers );
}
add_action( 'wpsc_transaction_result_cart_item', 'aesthetic_order_reciept', 10, 3 );

/**
 * Purchase reciept for merchant
 */
function aesthetic_merchant_order_reciept( $det_arr = array() ){
	global $wpsc_gateways;
	
	if( $det_arr['purchase_log']['email_sent'] == 1 )
		return false;
	
	$user_obj = get_userdata( $det_arr['purchase_log']['user_ID'] );
	
	$deal_obj = get_post( $det_arr['cart_item']['prodid'] );
	$merchant_obj = get_userdata( $deal_obj->post_author );

	$to = $merchant_obj->user_email;
	$subject = 'Deal Order';
	
	$item_design = aesthetic_design_item_table( $det_arr['cart_item'], $to );
	
	$message = '
		<div style="margin:20px">
			<p>Hello ('. ucfirst( $merchant_obj->user_firstname ) .'),</p>
			<p>A customer has made a purchase of your deal. Details of the transaction are summarized below:</p>
			
			<div>'. $item_design .'</div>
			
			<p>Keep placing your deals on Aesthetic Today and pump your sales and business exposure!</p>
	
		</div>
	';
	
	$headers[] = 'MIME-Version: 1.0';
	$headers[] = 'Content-Type: text/html; charset=UTF-8';
	
	@wp_mail( $to, $subject, $message, $headers );
}
add_action( 'wpsc_transaction_result_cart_item', 'aesthetic_merchant_order_reciept', 10, 3 );

/**
 * Deal notification to friend
 */
function aesthetic_friend_order_reciept( $det_arr = array() ){

	if( $det_arr['purchase_log']['email_sent'] == 1 )
		return false;
		
	$user_obj = get_userdata( $det_arr['purchase_log']['user_ID'] );
	
	$friend_detail = wpsc_get_meta( $det_arr['purchase_id'], 'wpsc_log_friend', 'wpsc_purchase_log' );
	
	if( !$friend_detail['to_mail'] )
		return false;
	
	$to = $friend_detail['to_mail'];
	$subject = 'A Deal Gifted for You !';
	
	$item_design = aesthetic_design_item_table( $det_arr['cart_item'], $to );
	
	$message = '
		<div style="margin:20px;">
			<p>Hello,</p>
			<p>Don\'t you just love gifts?</p>
			<p>Your friend ('. ucfirst( $user_obj->user_firstname ) .') with mobile number ('. get_user_meta( $user_obj->ID, '_aes_contact', true ) .')  has purchased a deal at '. ucfirst( get_option( 'blogname' ) ) .' for you. Details of the gift that you can redeem are as follows:</p>
			 
			<div>'. $item_design .'</div>
			
			<ul style="list-style:none; padding:0">
				<li>* Deal redemption expires on (date).</li>
				<li>* Multiple deals may be purchased and redeemed at one time.</li>
				<li>* For treatments and services, advanced booking at the redemption centres is required.</li>
				<li>* Deal may not be redeemed in conjunction with any other offer, discount, or promotion.</li>
				<li>* For timings, location maps, and all other details about the redemption centres, please call them directly on the number listed above.</li>
			</ul>
			
			<p>We hope that you enjoy your product / purchase, and please visit Aesthetic Today again for more amazing deals!</p>
	
		</div>
	';

	$headers[] = 'MIME-Version: 1.0';
	$headers[] = 'Content-Type: text/html; charset=UTF-8';
	$headers[] = 'CC: '. $user_obj->user_email;
//	echo 'to: '. $to .'<br />';
//	echo 'subject: '. $subject .'<br />';
//	echo 'message: '. $message .'<br />';
	
	@wp_mail( $to, $subject, $message, $headers );
}
add_action( 'wpsc_transaction_result_cart_item', 'aesthetic_friend_order_reciept', 10, 3 );

/**
 * Purchase reciept for admin
 */
function aesthetic_admin_order_reciept( $det_arr = array() ){
	global $wpsc_gateways;
	
	if( $det_arr['purchase_log']['email_sent'] == 1 )
		return false;
	
	$user_obj = get_userdata( $det_arr['purchase_log']['user_ID'] );
	
	$deal_obj = get_post( $det_arr['cart_item']['prodid'] );
	$merchant_obj = get_userdata( $deal_obj->post_author );
	
	$to = get_option( 'admin_email' );
	$subject = 'Deal Order';
	
	$item_design = aesthetic_design_item_table( $det_arr['cart_item'], $user_obj->user_email );
	
	$message = '
		<div style="margin:20px">
			<p>Dear Administrator,</p>
			<p>A deal purchase has been made for ('. $merchant_obj->user_email .'). The details are as follows:</p>
			
			<div>'. $item_design .'</div>
			
			<p>Thank you!</p>
	
		</div>
	';
	
	$headers[] = 'MIME-Version: 1.0';
	$headers[] = 'Content-Type: text/html; charset=UTF-8';
	
	@wp_mail( $to, $subject, $message, $headers );
	
	aesthetic_update_log_notify( $det_arr['purchase_id'] );
}
add_action( 'wpsc_transaction_result_cart_item', 'aesthetic_admin_order_reciept', 100, 3 );

/**
 * Design purchased items in a table
 */
function aesthetic_design_item_table( $cart_arr = array(), $recipient = '' ){
	$output = '';
	
	if( $cart_arr ){
		$output .= '
			<table width="75%" border="1" cellspacing="0" cellpadding="5" >
				<tr>
					<td>DEAL NAME</td>
					<td>DEAL ID</td>
					<td>UNIT PRICE</td>
					<td>RECIPIENT</td>
					<td>REDEMPTION CENTRE</td>
					<td>REDEMPTION CENTRE ADDRESS</td>
					<td>REDEMPTION CENTRE NUMBER</td>
					<td>REDEMPTION EXPIRATION</td>
				</tr>
				<tr>
					<td>'. ucfirst( $cart_arr['name'] ) .'</td>
					<td>'. $cart_arr['id'] .'</td>
					<td>'. wpsc_currency_display( $cart_arr['price'] ) .'</td>
					<td>'. $recipient .'</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</table>
		';
	}
	
	return $output;
}

/*
 * Set from mail header
 */
 
function aesthetic_mail_from( $from ){
	return 'Administrator';
}
add_filter( 'wp_mail_from_name', 'aesthetic_mail_from' );
