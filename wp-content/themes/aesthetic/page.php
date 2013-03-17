<?php
/**
 * Page template
 */
 get_header();
?>
	<div id="static_page">
       <?php get_sidebar( 'page' ); ?>
       <div class="content clsFloatRight">
	   <?php while( have_posts() ) : the_post(); ?>
	   		<h1><?php the_title(); ?></h1>
			<?php the_content(); ?>
		<?php endwhile; ?>
	   </div>
	   <div class="clear"></div>
    </div>
<?php get_footer(); ?>		
		
		