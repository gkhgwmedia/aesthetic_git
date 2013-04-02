<?php
/**
 * Template Name: Deals
 * 
 * Deals listing page
 */
get_header();
?>
	<div id="our_deals">
		  <?php get_sidebar( 'deal' ); ?>

		  <div class="content clsFloatRight" style="position:relative">
		  	<div class="loader">
				&nbsp;
				
			</div>
			<div class="list-loader"><img src="<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif" /></div>
		  </div>
		  <div class="clear"></div>
	</div>
<?php get_footer(); ?>
		
		