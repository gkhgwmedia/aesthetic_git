<?php 
/**
 * Single deal template
 */
 get_header();
?>
<?php if( wpsc_have_products() ) : ?>
	<?php while( wpsc_have_products() ) : wpsc_the_product(); ?>
	<div id="buy_deals">
		  <div class="deal_info">
		  		<div class="deal_img">
				<?php if( wpsc_the_product_thumbnail() ) : ?>
					<img src="<?php echo wpsc_the_product_thumbnail( 1024, 414 ); ?>" alt="<?php echo wpsc_the_product_title(); ?>" title="<?php echo wpsc_the_product_title(); ?>" />
				<?php else : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/images/deal_image.png" alt="<?php echo wpsc_the_product_title(); ?>" title="<?php echo wpsc_the_product_title(); ?>" />
				<?php endif; ?>
				</div>
				<div class="deal_head">
					<h1><?php echo wpsc_the_product_title(); ?></h1>
				</div>
		  </div>	
		  <div class ="left_side_menu clsFloatLeft">
			  	<div class="buy_opt">
					<ul>
						<li class="yellow_bg">BUY for <?php echo wpsc_the_product_price(); ?></li>
						<li class="gray_bg">Value: <?php echo wpsc_product_normal_price(); ?> Discount: <?php echo wpsc_you_save(); ?>%</li>
						<li class="yellow_bg">Buy it for a friend!</li>
						<li class="gray_bg">
							Time Remaining: <span id="countdown"></span> 
						</li>
					</ul>
				</div>	
				<div class="social clsFloatLeft">
                            <ul>
								<li><a href="http://facebook.com/sharer.php?s=100&p[url]=<?php echo esc_url( wpsc_the_product_permalink() ); ?>" target="_blank" class="facebook-share"></a></li>
                                <li><a href="http://twitter.com/share?url=<?php echo esc_url( wpsc_the_product_permalink() ); ?>" target="_blank" class="twitter-share"></a></li>
                                <li><a href="http://pinterest.com/pin/create/button/?url=<?php echo esc_url( wpsc_the_product_permalink() ); ?>&media=<?php echo esc_url( wpsc_the_product_thumbnail( 1024, 414 ) ); ?>" target="_blank" class="pin-share"></a></li>
                                <li class="clear"></li>
                            </ul>
                 </div>
                 <div class="clear"></div>
				
		  </div>

		  <div class="content clsFloatRight">
			<?php echo wpsc_the_product_description(); ?>
		  </div>
		  <div class="clear"></div>
	</div>
		<?php if( $endtime = get_post_meta( wpsc_the_product_id(), '_aes_product_endtime', true ) ) : $endtime_format = date( 'F d, Y H:i', strtotime( $endtime ) ); ?>
			<script>
				jQuery(document).ready(function(){
					austDay = new Date( '<?php echo $endtime_format; ?>' );
					jQuery('#countdown').countdown({
						until: austDay,
						//layout: '{dn} days {hn}:{mn}:{sn}',
						layout: '<strong>{dn} {dl} {hnn}{sep}{mnn}{sep}{snn}</strong>',
						expiryText: 'Expired',
						alwaysExpire: true
					});
				});
			</script>
		<?php endif; ?>
	<?php endwhile; ?>
<?php endif; ?>
<?php get_footer(); ?>		
		
		