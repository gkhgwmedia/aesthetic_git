  	</div> <!-- Content wrapper end -->
  </div> <!-- Wrapper end -->
	
    <div id="footer">
        <div class="inner">
			<div class="tab clsFloatLeft">
				<h1>AESTHETIC TODAY NEWS</h1>
				<h2>Subscribe to our Daily Newsletter!</h2>
				<form>
					<input type="text" id="nlsubmit"  />
					<input type="submit" id="nlsubmit" value="SUBMIT" class="btnNlsubmit"/>
				</form>	
				<h2>Follow Us on: <a target="_blank" href="<?php echo eto_get_option( 'eto_facebook_url' ); ?>">Facebook</a> • <a target="_blank" href="<?php echo eto_get_option( 'eto_twitter_url' ); ?>">Twitter</a></h2>
			</div>
			<div class="tab clsFloatLeft">
					<?php
						$menu_name = 'new_to';
					
						if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
							$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
							$menu_items = wp_get_nav_menu_items($menu->term_id);
							
							$menu_count = count( $menu_items );
							
							if( $menu_count ){
					?>
							<h1><?php echo $menu->name; ?></h1>
							<ul>
					<?php
								foreach( (array) $menu_items as $key => $menu_item ){
					?>
								<li>
									<a href="<?php echo $menu_item->url; ?>"><?php echo $menu_item->title; ?></a>
								</li>
					<?php
								}
					?>
							</ul>
					<?php
							}
						}
					?>
			</div>
			<!--<div class="tab clsFloatLeft">
				<h1>MY ACCOUNT</h1>
				<ul>
					<li><a href="#">Account Owner Info</a></li>
				</ul>
			</div>-->
		</div>
		<div class="clear"></div>
    </div>
	<div id="footer-bottom">
		<div class="inner">
		 	<div class="copyright clsFloatLeft">
				<span>Copyright 2013 Aesthetic Today | developed by <a href="http://www.hgwmedia.com" target="_blank">HGWMedia</a></span>
			</div>
			<!--<div class="footerNav clsFloatRight"><a href="#">HOME</a> | <a href="#">ABOUT US</a> | <a href="#">CONTACT</a></div>-->
			
		</div>
	</div>
	<?php wp_footer(); ?>
</body>

</html>	