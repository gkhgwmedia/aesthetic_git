<?php
/**
 * Add to cart
 */
function aesthetic_add_to_cart(){
	global $wpec_cart, $cart_messages;
	
	if( !is_user_logged_in() )
		exit( "you need login to do this. <a href='". get_permalink( 37 ) ."'>Register/ Login</a>" );
		
	if( !$_POST['product_id'] )
		exit( 'Invalid product' );
		
	if( function_exists( 'wpsc_add_to_cart' ) ){
		wpsc_empty_cart();
			
		wpsc_add_to_cart();
	}
		
	aesthetic_cart();
		
	exit;	
}
add_action( 'wp_ajax_add_to_cart', 'aesthetic_add_to_cart' );

/**
 * update the cart
 */
function aesthetic_update_cart(){
	if( !isset( $_POST['key'] ) )
		exit( 'Invalid cart key' );
	
	wpsc_update_item_quantity();
		
	aesthetic_cart();
	exit;
}
add_action( 'wp_ajax_aesthetic_update_cart', 'aesthetic_update_cart' );

/**
 * Whether an user can checkout a deal or not
 */
function aesthetic_can_checkout( $id = '' ){
	global $post;

	if( !empty( $id ) )
		$post = get_post( $id );
		
	if( !$post )
		return false;
		
	$endtime = strtotime( get_post_meta( $post->ID, '_aes_product_endtime', true ) );
	$curtime = current_time( 'timestamp' );
	
	if( ( $curtime > $endtime ) || $post->post_author == get_current_user_id() )
		return false;
		
	return true;
}

/**
 * Get the cart items
 */
function aesthetic_cart(){
	global $wpsc_cart;
?>
	<div id="popup_cart">
		<h1>Cart</h1>
		<?php if( wpsc_cart_item_count() > 0 ) : ?>
			<form name="cart_form" id="cart_form" action="">
				<table id="cart_table">
					<tr>
						<th width="60%">Item</th>
						<th width="15%">Price</th>
						<th width="10%">Qty</th>
						<th width="15%">Total</th>
					</tr>
					<?php while( wpsc_have_cart_items() ) : wpsc_the_cart_item() ?>
					<tr>
						<td><?php echo wpsc_cart_item_name(); ?></td>
						<td><?php echo wpsc_cart_single_item_price(); ?></td>
						<td><input type="text" name="quantity" maxlength="3" value="<?php echo wpsc_cart_item_quantity(); ?>" /></td>
						<td>
							<?php echo wpsc_cart_item_price(); ?>
							<input type="hidden" name="key" value="<?php echo wpsc_the_cart_item_key(); ?>" />
							<input type="hidden" name="action" value="aesthetic_update_cart" />
						</td>
					</tr>
					<?php endwhile; ?>
				</table>
				<div class="buttons">
					<input id="cart_update" type="button" class="btnSubmit" value="Update" />
					<input id="cart_cont" type="" class="btnSubmit" value="Continue" />
				</div>
			</form>
		<?php else: ?>
			<p>There is no products in your cart</p>
		<?php endif; ?>
	</div>
<?php
	exit;
}
add_action( 'wp_ajax_show_cart', 'aesthetic_cart' );

/**
 * Confirm cart
 */
function aesthetic_cart_confirm(){
?>
	<?php if( wpsc_cart_item_count() > 0 ) : ?>
		<table id="cart_table">
			<tr>
				<th width="60%">Item</th>
				<th width="15%">Price</th>
				<th width="10%">Qty</th>
				<th width="15%">Total</th>
			</tr>
			<?php while( wpsc_have_cart_items() ) : wpsc_the_cart_item() ?>
			<tr>
				<td><?php echo wpsc_cart_item_name(); ?></td>
				<td><?php echo wpsc_cart_single_item_price(); ?></td>
				<td><?php echo wpsc_cart_item_quantity(); ?></td>
				<td><?php echo wpsc_cart_item_price(); ?></td>
			</tr>
			<?php endwhile; ?>
		</table>
	<?php endif; ?>
<?php	
}

/**
 * Friend form fields
 */
function aesthetic_checkout_friend_fields(){
?>
	<script>
		jQuery(document).ready(function($){
			$('#friend_msg').NobleCount('#msgLmt',{
				max_chars: 330,
				on_negative: 'go_red',
				on_positive: 'go_green',
				block_negative: true,
				on_negative: function( textArea, charArea, sets, charRem ){
					var content = textArea.val();
					
					textArea.val( content.substring( 0, sets.max_chars ) );
				}
			});
		});
	</script>
	<div class="popup_friend">
		<h1>Buy it for a friend!</h1>
		<div class="form">
			<div class="clsFormField">
				<label>TO <span class="red">*</span></label>
				<input type="text" name="to_mail" id="to_mail" value="" />
			</div>
			<div class="clear"></div>
			<div class="clsFormField">
				<label>From <span class="red">*</span></label>
				<input type="text" name="from_mail" id="from_mail" value="" />
			</div>
			<div class="clsFormField">
				<label>Message <span class="red">*</span></label>
				<textarea name="friend_msg" id="friend_msg"></textarea>
				<span class="note">(Maximum of <span id="msgLmt">330</span> characters) - Optional </span>
			</div>
			<div class="clear"></div>
		</div>
	</div>
<?php
}

/**
 * Checkout payment fields
 */
function aesthetic_checkout_payment_fields(){
	global $wpsc_cart, $wpdb, $wpsc_checkout, $wpsc_gateway, $wpsc_coupons, $wpsc_registration_error_messages;
	$wpsc_checkout = new wpsc_checkout();
	$wpsc_gateway = new wpsc_gateways();
	
	aesthetic_remove_mywallet();
	
//	echo '<pre>';
//	print_r( $wpsc_gateway );
//	echo '</pre>';
?>
	<div class="popup_friend">
		<div class="form">
			<h1>Delivery method</h1>
			<?php while (wpsc_have_gateways()) : wpsc_the_gateway(); ?>
				<?php if( wpsc_gateway_internal_name() ) : ?>
					<div class="clsFormField">
						<input type="radio" value="<?php echo wpsc_gateway_internal_name();?>" <?php echo wpsc_gateway_is_checked(); ?> name="custom_gateway" class="custom_gateway"/>
						<label><?php echo wpsc_gateway_name(); ?></label>
					</div>
					<div class="clear"></div>
					<?php if(wpsc_gateway_form_fields()): ?>
						<?php echo wpsc_gateway_form_fields();?>
					<?php endif; ?>
				<?php endif; ?>
			<?php endwhile; ?>
		</div>
	</div>
<?php
}

/**
 * Checkout form
 */
function aesthetic_checkout_form(){
?>
	<h1>Checkout</h1>
	<form class='checkout_forms' name="checkout_form" id="checkout_form" action='' method='post' enctype="multipart/form-data">
<?php
	aesthetic_cart_confirm();
	
	if( $_POST['buy_mode'] == 'buy_for' )
		aesthetic_checkout_friend_fields();
		
	aesthetic_checkout_payment_fields();
?>
		<input type='hidden' value='yes' name='agree' />
		<input type='hidden' value='submit_checkout' name='wpsc_action' />
		
		<input type="submit" name="checkout_submit" id="checkout_submit" class="btnSubmit" value="Order"  />
		<input type="button" id="checkout_cancel" class="btnSubmit" value="Cancel"  />
	</form>
<?php
	exit;
}
add_action( 'wp_ajax_aesthetic_checkout', 'aesthetic_checkout_form' );

/**
 * Pre checkout 
 */
function aesthetic_checkout( $det ){
	if( !is_user_logged_in() )
		return false;

	if( $_POST['wpsc_action'] == 'submit_checkout' ){
	
		if( strlen( $_POST['to_mail'] ) && strlen( $_REQUEST['from_mail'] ) && strlen( $_POST['friend_msg'] ) ){
			$arr = array(
				'to_mail' => sanitize_text_field( $_POST['to_mail'] ),
				'from' => sanitize_text_field( $_POST['from_mail'] ),
				'msg' => esc_textarea( $_POST['friend_msg'] )
			);
			
			wpsc_update_meta( $det['purchase_log_id'], 'wpsc_log_friend', $arr, 'wpsc_purchase_log' );
		}
	}
}
add_action( 'wpsc_submit_checkout', 'aesthetic_checkout' );

/**
 * Checkout by wallet method
 */
function aesthetic_wallet_checkout( $det ){
	global $current_gateway_data;

	if( !is_user_logged_in() )
		return false;
		
	if( $_POST['wpsc_action'] == 'submit_checkout' ){
		$log_det = aesthetic_get_log_by_id( $det['purchase_log_id'] );
		
		if( $log_det->gateway == 'wpsc_merchant_testmode' ){
			if( aesthetic_is_have_wallet_balance() ){
				aesthetic_update_user_wallet( get_current_user_id(), wpsc_cart_total( false ), 'sub' );
				aesthetic_update_log_status( $det['purchase_log_id'], 3 );
			}
		}
	}
}
add_action( 'wpsc_submit_checkout', 'aesthetic_wallet_checkout' );

/**
 * Order result page
 */
function aesthetic_order_result( $output ){
	global $purchase_log;
	
	echo '<pre>';
	print_r( $purchase_log );
	echo '</pre>';
	exit;
}
add_filter( 'wpsc_get_transaction_html_output', 'aesthetic_order_result' );




