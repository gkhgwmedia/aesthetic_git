<?php
/**
 * Aesthetic Widgets
 *
 * @package WordPress
 * @subpackage Aesthetic
 */

/**
  * Categories widget
  */
 class Aesthetic_Categories_Widget extends WP_Widget{
	
	/**
	 * Constructor
	 */
	function Aesthetic_Categories_Widget(){
		$widget_ops = array( 'classname' => 'category', 'description' => __( 'Deals categories widget', 'aesthetic' ) );
		$this->WP_Widget( 'deals_category_widget', __( 'Deals categories', 'aesthetic' ), $widget_ops );
	}
	
	/*
	 * Outputs the HTML for this widget
	 */
	function widget( $args, $instance ){
		global $post;
		
		extract( $args );
		
		echo $before_widget;
		
		echo $before_title;
			echo ucfirst( $instance['title'] );
		echo $after_title;
		
		$args = array( 'hide_empty' => false );
		$cats = aesthetic_get_categories( $args );
?>
		<ul>
		<?php foreach( $cats as $cat ) : ?>
            <li class="cat-li" id="cat_<?php echo $cat->term_id; ?>"><a href="javascript: goToCat( '<?php echo $cat->term_id; ?>' )" title="<?php echo ucfirst( $cat->name ); ?>"><?php echo ucfirst( $cat->name ); ?></a></li>
		<?php endforeach; ?>
        </ul>
<?php
		echo $after_widget;
	}
	
	/*
	 * Save the widget data
	 */
	function update( $new_instance, $old_instance ){
	
		$instance = array();
		
		$instance['title'] = strip_tags( $new_instance['title'] );
		
		return $instance;
	}
	
	/*
	 * Display the form for this widget
	 */
	function form( $instance ){
	
		$title = isset( $instance['title']) ? esc_attr( $instance['title'] ) : '';
?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'rejuvenica' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
<?php
	}
 }
 
/**
  * Price range widget
  */
 class Aesthetic_Price_Range_Widget extends WP_Widget{
	
	/**
	 * Constructor
	 */
	function Aesthetic_Price_Range_Widget(){
		$widget_ops = array( 'classname' => 'range', 'description' => __( 'Deal price range filter widget', 'aesthetic' ) );
		$this->WP_Widget( 'deals_price_range_widget', __( 'Deals price range', 'aesthetic' ), $widget_ops );
	}
	
	/*
	 * Outputs the HTML for this widget
	 */
	function widget( $args, $instance ){
		global $post;
		
		extract( $args );
		
		echo $before_widget;
		
		echo $before_title;
			echo ucfirst( $instance['title'] );
		echo $after_title;
		
?>
		<p>&nbsp;</p>
		<!--<img src="<?php echo get_template_directory_uri(); ?>/images/sliding_img.png" />-->
		<div class="price-range">
			<div id="slider-range">&nbsp;</div>
			<span class="range-tooltip tip-from"></span>
            <span class="range-tooltip tip-to"></span>
		</div>
		<script>
			var priceStart = '<?php echo $instance['from_price'] ?>';
			var priceEnd = '<?php echo $instance['to_price'] ?>';
			
			jQuery(document).ready(function(){
	
				jQuery( "#slider-range" ).slider({
					range: true,
					min: priceStart,
					max: priceEnd,
					values: [ priceStart, priceEnd ],
					slide: function( event, ui ) {
						
						jQuery( '#filter_price_start' ).val( ui.values[ 0 ] );
						jQuery( '#filter_price_to' ).val( ui.values[ 1 ] );
						
						var tipFrom = ( jQuery( "#slider-range" ).slider( "values", 0 ) / priceEnd ) * jQuery( "#slider-range" ).width();
						var tipTo = ( jQuery( "#slider-range" ).slider( "values", 1 ) / priceEnd ) * jQuery( "#slider-range" ).width();
						tipFrom = tipFrom + 10;
						tipTo = tipTo + 10;
						jQuery( '.tip-from' ).css('left', tipFrom ).text(ui.values[0]);
						jQuery( '.tip-to' ).css('left', tipTo ).text(ui.values[1]);
					},
					change: function( event, ui ){
						loadDeals();
					}
				});
			
				// For default selection
				jQuery( '#filter_price_start' ).val( jQuery( "#slider-range" ).slider( "values", 0 ) );
				jQuery( '#filter_price_to' ).val( jQuery( "#slider-range" ).slider( "values", 1 ) );
				
				var tipFrom = ( jQuery( "#slider-range" ).slider( "values", 0 ) / priceEnd ) * jQuery( "#slider-range" ).width();
				var tipTo = ( jQuery( "#slider-range" ).slider( "values", 1 ) / priceEnd ) * jQuery( "#slider-range" ).width();
				tipFrom = tipFrom + 10;
				tipTo = tipTo + 10;
				jQuery( '.tip-from' ).css('left', tipFrom ).text( jQuery( "#slider-range" ).slider( "values", 0 ) );
				jQuery( '.tip-to' ).css('left', tipTo ).text( jQuery( "#slider-range" ).slider( "values", 1 ) );
			});
		</script>
<?php
		echo $after_widget;
	}
	
	/*
	 * Save the widget data
	 */
	function update( $new_instance, $old_instance ){
	
		$instance = array();
		
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['from_price'] = strip_tags( $new_instance['from_price'] );
		$instance['to_price'] = strip_tags( $new_instance['to_price'] );
		
		return $instance;
	}
	
	/*
	 * Display the form for this widget
	 */
	function form( $instance ){
	
		$title = isset( $instance['title']) ? esc_attr( $instance['title'] ) : '';
		$from_price = isset( $instance['from_price']) ? esc_attr( $instance['from_price'] ) : '';
		$to_price = isset( $instance['to_price']) ? esc_attr( $instance['to_price'] ) : '';
?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'aesthetic' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
		
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'from_price' ) ); ?>"><?php _e( 'From price: ', 'aesthetic' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'from_price' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'from_price' ) ); ?>" type="text" value="<?php echo esc_attr( $from_price ); ?>" /></p>
		
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'to_price' ) ); ?>"><?php _e( 'To price: ', 'aesthetic' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'to_price' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'to_price' ) ); ?>" type="text" value="<?php echo esc_attr( $to_price ); ?>" /></p>
<?php
	}
 }
 
 /**
  * Featured deal widget
  */
 class Aesthetic_Featured_Widget extends WP_Widget{
	
	/**
	 * Constructor
	 */
	function Aesthetic_Featured_Widget(){
		$widget_ops = array( 'classname' => 'featured', 'description' => __( 'Featured deals widget', 'aesthetic' ) );
		$this->WP_Widget( 'deals_featured_widget', __( 'Featured deals', 'aesthetic' ), $widget_ops );
	}
	
	/*
	 * Outputs the HTML for this widget
	 */
	function widget( $args, $instance ){
		global $post;
		
		extract( $args );
		
		echo $before_widget;
		
		echo $before_title;
			echo ucfirst( $instance['title'] );
		echo $after_title;
		
?>
		<p>&nbsp;</p>
		<img src="<?php echo get_template_directory_uri(); ?>/images/feautured_deal.png" />
<?php
		echo $after_widget;
	}
	
	/*
	 * Save the widget data
	 */
	function update( $new_instance, $old_instance ){
	
		$instance = array();
		
		$instance['title'] = strip_tags( $new_instance['title'] );
		
		return $instance;
	}
	
	/*
	 * Display the form for this widget
	 */
	function form( $instance ){
	
		$title = isset( $instance['title']) ? esc_attr( $instance['title'] ) : '';
?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'rejuvenica' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
<?php
	}
 }
