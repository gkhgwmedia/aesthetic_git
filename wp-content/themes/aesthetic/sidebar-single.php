<script>

	function displayCart(){
		jQuery.post( '<?php echo admin_url( 'admin-ajax.php' ); ?>', { action: 'show_cart' }, function(data){
			jQuery.zoombox.html( data, { animation: false, theme: 'prettyphoto', duration: 300, opacity: '0.3', height: 150 } );
		} );
	}

	function addToCart( id ){
			
		if( !id )
			return false;
			
		jQuery.post( '<?php echo admin_url( 'admin-ajax.php' ); ?>', 
			{
				action: 'add_to_cart',
				product_id: id
			}, 
			function(data){
				if( data == 0 )
					data = 'You need to login to do this. <a href="<?php echo get_page_link( 145 ); ?>">Login</a>';
			
				jQuery.zoombox.html( data, { animation:false, theme: 'prettyphoto', duration: 300, opacity: '0.3', height: 150 } );
			} 
		);
	}
	
	function updateCart(){
		jQuery.post( '<?php echo admin_url( 'admin-ajax.php' ); ?>', jQuery	('#cart_form').serialize(), function(data){
			jQuery.zoombox.html( data, { animation: false, theme: 'prettyphoto', duration: 300, opacity: '0.3', height: 150 } );
		} );
	}
	
	function friendValidate(){
		jQuery( '#checkout_form' ).validate({
			errorElement: 'span',
			rules:{
				to_mail: { required: true, email: true },
				from_mail: { required: true, email: true },
				friend_msg: { required: true, minlength: 20 }
			}
		});
	}
	
	function addToWish( obj ){
		jQuery.ajax({
			type: "POST",
			url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
			data: { 
				action: 'add_to_wishlist',
				id: obj.attr( 'deal' )
			}
		}).done(function( data ){
			if( data == 0 )
				jQuery( obj ).tooltipster( 'update', 'You need to Sign in' );
		
			var retObj = jQuery.parseJSON( data );
			jQuery( obj ).tooltipster( 'update', retObj.message );
			
			if( retObj.type == 'success' ){
				obj.removeClass( 'wishlist-add' );
				obj.addClass( 'wishlist-remove' );
			}
		});
	}
	
	function removeFromWish( obj ){
		jQuery.ajax({
			type: "POST",
			url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
			data: { 
				action: 'remove_from_wishlist',
				id: obj.attr( 'deal' )
			}
		}).done(function( data ){
			var retObj = jQuery.parseJSON( data );
			jQuery( obj ).tooltipster( 'update', retObj.message );
			
			if( retObj.type == 'success' ){
				obj.removeClass( 'wishlist-remove' );
				obj.addClass( 'wishlist-add' );
			}
		});
	}
	
	jQuery(document).ready(function(){
		jQuery( '#cart_cont' ).live( 'click', function(){
			var buyMode =  jQuery( '#buy_mode' ).val();
													   
			jQuery.post( '<?php echo admin_url( 'admin-ajax.php' ); ?>', 
				{
					action: 'aesthetic_checkout',
					buy_mode: buyMode
				}, 
				function(data){
					var hg = ( buyMode == 'buy' ) ? '300' : '550';
					
					jQuery.zoombox.html( data, { animation: false, theme: 'prettyphoto', duration: 300, opacity: '0.3', height: hg } );
					
					if( buyMode == 'buy_for' )
						setTimeout( 'friendValidate()', 1000 );
				} 
			);
			
		} );
		
		jQuery( '#cart_cancel' ).live( 'click', function(){
			jQuery.zoombox.close();
		} );
		
		jQuery( '#cart_update' ).live( 'click', function(){
			updateCart();
		} );
		
		jQuery( '#checkout_cancel' ).live( 'click', function(){
			displayCart();
		} )
		
		jQuery( '#buy, #buy_for' ).click(function(){
			jQuery( '#buy_mode' ).val( jQuery(this).attr( 'id' ) );
		});
		
		jQuery( '.wishlist-add' ).tooltipster({
			animation: 'grow',
			position: 'top-left',
			content: 'Add to Wishlist'
		});
		
		// Add tooltip for 'wishlist remove'
		jQuery( '.wishlist-remove' ).tooltipster({
			animation: 'grow',
			position: 'top-left',
			content: 'Remove from Wishlist'
		});
		
		// Add to wishlist action
		jQuery( '.wishlist-add' ).live( 'click', function( event ){
			addToWish( jQuery(this) );
			event.preventDefault();
		});
		
		// remove from wishlist action
		jQuery( '.wishlist-remove' ).live( 'click', function( event ){
			removeFromWish( jQuery(this) );
			event.preventDefault();
		});
	});
</script>
<div class ="left_side_menu clsFloatLeft">
	<input type="hidden" id="buy_mode" value="buy" />
	<div class="buy_opt">
		<ul>
			<?php if( get_no_of_bought( wpsc_the_product_id() ) > 0 ) : ?><li class="yellow_bg"><?php echo get_no_of_bought( wpsc_the_product_id() ) ?> BOUGHT</li><?php endif; ?>
			<?php if( aesthetic_can_checkout() ) : ?><li class="yellow_bg"><a href="javascript:addToCart( '<?php echo wpsc_the_product_id(); ?>' );" id="buy">BUY for <?php echo wpsc_the_product_price(); ?></a></li><?php endif; ?>
			<li class="gray_bg">Value: <?php echo wpsc_product_normal_price(); ?> Discount: <?php echo wpsc_you_save(); ?>%</li>
			<?php if( aesthetic_can_checkout() ) : ?><li class="yellow_bg"><a href="javascript:addToCart( '<?php echo wpsc_the_product_id(); ?>' );" id="buy_for">Buy it for a friend!</a></li><?php endif; ?>
			<li class="gray_bg">
				Time Remaining: <span id="countdown"></span> 
			</li>
		</ul>
	</div>	
	<div class="social clsFloatLeft">
				<ul>
					<li><a href="#" target="_blank" class="wish <?php if( aesthetic_is_in_wish( wpsc_the_product_id() ) ) echo 'wishlist-remove'; else echo 'wishlist-add'; ?>" deal="<?php echo wpsc_the_product_id(); ?>"></a></li>
					<li><a href="http://facebook.com/sharer.php?s=100&p[url]=<?php echo esc_url( wpsc_the_product_permalink() ); ?>" target="_blank" class="facebook-share"></a></li>
					<li><a href="http://twitter.com/share?url=<?php echo esc_url( wpsc_the_product_permalink() ); ?>" target="_blank" class="twitter-share"></a></li>
					<li><a href="http://pinterest.com/pin/create/button/?url=<?php echo esc_url( wpsc_the_product_permalink() ); ?>&media=<?php echo esc_url( wpsc_the_product_thumbnail( 1024, 414 ) ); ?>" target="_blank" class="pin-share"></a></li>
					<li class="clear"></li>
				</ul>
	 </div>
	 <div class="clear"></div>
</div>