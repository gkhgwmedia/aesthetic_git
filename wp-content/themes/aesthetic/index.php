<?php 
/**
 * Template Name: Home
 * Main template
 */
 get_header();
?>
<script>
	jQuery(document).ready(function(){
		jQuery('.bar').mosaic({
			animation	:	'slide'
		});
		
//		jQuery( '.zoombox' ).zoombox();
//		jQuery( '.zoombox' ).trigger( 'click' );
		
		if( !jQuery.cookie( 'aes_subscribed' ) ){
			jQuery.post( '<?php echo admin_url( 'admin-ajax.php' ); ?>', { action: 'get_subscribe_template' }, function(data){
				jQuery.zoombox.html( data, { width: 570, height: 800 } );
				jQuery( '#zoombox .zoombox_mask' ).unbind( 'click' );
	
				setTimeout( 'bindSubscribeValidation()', 1000 );
			} );
		}
		
		jQuery( '.sub_form_close' ).live( 'click', function(){
			jQuery.zoombox.close();
			jQuery.cookie( 'aes_subscribed', '1' );
		} );
		
		//new Messi.load( '<?php echo get_template_directory_uri(); ?>/subscribe_popup.php' );
	});
	
	function bindSubscribeValidation(){
		jQuery( '#sub_form' ).validate({
			errorElement: 'span',
			errorPlacement: function( error, element ){
				return;
			},
			highlight: function( ele, errCls, valCls ){
				jQuery( ele ).css( 'border', 'red 4px solid' );
			},
			unhighlight: function( ele, errCls, valCls ){
				jQuery( ele ).css( 'border', 'none' );
			},
			rules:{
				sub_name: { required: true, minlength: 5 },
				sub_email: { required: true, email: true }
			},
			submitHandler: function(){
				jQuery.post( '<?php echo admin_url( 'admin-ajax.php' ); ?>', jQuery('#sub_form').serialize(), function(data){
					var retObj = jQuery.parseJSON( data );
					
					if( retObj.type == 'error' ){
						new Messi( retObj.message, { closeButton: false } );
						setTimeout( 'messiClose()', 2000 );
					}else{
						jQuery.cookie( 'aes_subscribed', '1' );
						jQuery.zoombox.close();
					}
				} );
			}
		});
	}
</script>
	<a href="#popup_register" class="zoombox w500 h400" style="display:none;">zoombox</a>
	
	<div id="home_content">
			<ul>
			<?php
				$args = array( 'hide_empty' => false );
				$cats = aesthetic_get_categories( $args );
			?>
			<?php $i = 0; ?>
			<?php foreach( $cats as $key => $cat ) : ?>
			<?php $i++; ?>
				<li class="<?php if( $i % 4  == 0 ) echo 'lastchild'; ?>">
					<div class="mosaic-block bar">
						<a href="<?php echo get_page_link(17) .'?cat='. $cat->term_id; ?>" class="mosaic-overlay">
						<div class="details"><h1><?php echo ucfirst( $cat->name ); ?></h1></div></a>
						<div class="mosaic-backdrop">
							<a href="<?php echo get_page_link(17) .'?cat='. $cat->term_id; ?>">
								<?php if( $cat->image ) : ?>
									<img src="<?php echo WPSC_CATEGORY_URL . basename( $cat->image ); ?>" />
								<?php else: ?>
									<img src="<?php echo get_template_directory_uri(); ?>/images/cat-default.jpg" />
								<?php endif; ?>
							</a>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="nav"><a href="<?php echo get_page_link(17) .'?cat='. $cat->term_id; ?>" class="btnNav">View now</a> </div>
				</li>
			<?php endforeach; ?>
			</ul>
			<div class="clear"></div>
		</div>
<?php get_footer(); ?>		
		
		