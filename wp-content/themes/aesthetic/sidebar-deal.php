
<script>

	jQuery(document).ready(function(){
	
		<?php if( $_GET['cat'] ) : ?>
			goToCat( '<?php echo $_GET['cat']; ?>' );
		<?php endif; ?>
	
		loadDeals();
	});
	
	// Load deals
	function loadDeals(){
	
		var container = jQuery( '.loader' );
		//container.addClass( 'loading' );
		//new Messi( '<img src="<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif" />', {closeButton: false, width: 77} );
		jQuery( '.list-loader' ).show();
		
		jQuery.ajax({
			type: "POST",
			url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
			data: { 
				action: 'get_deals',
				cat: jQuery( '#filter_cat' ).val(),
				price_start: ( jQuery( '#filter_price_start' ).val() ) ? jQuery( '#filter_price_start' ).val() : 0,
				price_to: ( jQuery( '#filter_price_to' ).val() ) ? jQuery( '#filter_price_to' ).val() : 2000,
				query: jQuery( '#filter_query' ).val(),
				featured: jQuery( '#filter_special' ).val(),
				keyword: jQuery( '#filter_search' ).val()
			}
		}).done(function( content ) {
			container.html( content );
			
			jQuery('.bar').mosaic({
				animation	:	'slide'
			});
			
			jQuery( '.bar' ).mouseenter(function(){
				var barId = jQuery( this ).attr( 'id' );
				jQuery( '#'+ barId +' .like' ).css( 'opacity', '1' );
				jQuery( '#'+ barId +' .share' ).css( 'opacity', '1' );
			}).mouseleave(function(){
				var barId = jQuery( this ).attr( 'id' );
				jQuery( '#'+ barId +' .like' ).css( 'opacity', '0' );
				jQuery( '#'+ barId +' .share' ).css( 'opacity', '0' );
			});
			
			jQuery( '.btnNav' ).mouseenter(function(){
				var barId = jQuery( this ).attr( 'attr' );
				jQuery( '#'+ barId ).trigger( 'mouseover' );
				
				jQuery( '#'+ barId +' .like' ).css( 'opacity', '1' );
				jQuery( '#'+ barId +' .share' ).css( 'opacity', '1' );
			} ).mouseleave( function(){
				var barId = jQuery( this ).attr( 'attr' );
				jQuery( '#'+ barId ).trigger( 'mouseout' );
				
				jQuery( '#'+ barId +' .like' ).css( 'opacity', '0' );
				jQuery( '#'+ barId +' .share' ).css( 'opacity', '0' );
			} );
			
			// Add tooltip for 'wishlist add'
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
			
			//container.removeClass( 'loading' );
			//jQuery( '.messi-box' ).hide();
			jQuery( '.list-loader' ).hide();
		});
	}
	
	function goToCat( catID ){
		if( !catID )
			return;
			
		jQuery( '#filter_cat' ).val( catID );
		loadDeals();
		
		jQuery( '.cat-li' ).removeClass( 'active' );
		jQuery( '#cat_'+ catID ).addClass( 'active' );
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
			
			if( retObj.type == 'success' )
				obj.attr( 'class', 'wishlist-remove' );
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
			
			if( retObj.type == 'success' )
				obj.attr( 'class', 'wishlist-add' );
		});
	}
	
</script>

<div class ="left_side_menu clsFloatLeft">
<?php
	if ( ! dynamic_sidebar( 'deals_sidebar' ) ) :
		echo 'Primary sidebar is not defined';
	endif;
?>
	<form name="deal_filter" id="deal_filter" action="" method="post">
		<input type="hidden" name="filter_cat" id="filter_cat" value="" />
		
		<input type="hidden" name="filter_price_start" id="filter_price_start" value="" />
		<input type="hidden" name="filter_price_to" id="filter_price_to" value="" />
		
		<input type="hidden" name="filter_search" id="filter_search" value="<?php echo $_GET['keyword']; ?>" />
		
		<input type="hidden" name="filter_query" id="filter_query" value="<?php echo $_GET['query'] ?>" />
	</form>
</div>