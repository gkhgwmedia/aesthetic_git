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
		  <?php get_sidebar( 'single' ); ?>
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
		
		