<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width" />
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <div id="top_ribbon">&nbsp;</div>
    <div id="wrapper">
        <div id="header">
            <div class="logo clsFloatLeft">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" /></a>
            </div>
            <div class="header-right clsFloatRight">
                <div class="social_nav">
                    <div class="social clsFloatLeft">
                        <div class="clsFloatLeft clsSocialLabel">
                            SOCIALIZE WITH US:</div>
                        <div class="socialIcons clsFloatLeft">
                            <ul>
                                <li><a href="<?php echo eto_get_option( 'eto_facebook_url' ); ?>" target="_blank" class="facebook"></a></li>
								<li><a href="<?php echo eto_get_option( 'eto_twitter_url' ); ?>" target="_blank" class="twitter"></a></li>
                                <li><a href="<?php echo eto_get_option( 'eto_youtube_url' ); ?>" target="_blank" class="youtube"></a></li>
                                <li class="clear"></li>
                            </ul>
                        </div>
                        <div class="clear"></div>
                    </div>
					<?php get_search_form(); ?>
                </div>
                <div class="menu_nav">
                    <ul>
					<?php if( !is_user_logged_in() ) : ?>
                        <?php aesthetic_primary_menu() ?>
					<?php else : ?>
						<?php aesthetic_primary_menu( 'primary_user' ) ?>
					<?php endif; ?>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
		<div id="slider">
			<div class="slider-inner">
				<?php echo aesthetic_header_image(); ?>
			</div>
		</div>
		<div class="sub_nav">
			<ul>
				<?php aesthetic_deal_menu(); ?>
            </ul>
		</div>
		
		<div id="content-wrapper">
		
	
  