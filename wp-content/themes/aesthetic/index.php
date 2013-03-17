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
	});
</script>
	<a href="<?php echo get_template_directory_uri(); ?>/regsiter_popup.php" class="zoombox w570 h570" style="display:none;"></a>
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
		
		