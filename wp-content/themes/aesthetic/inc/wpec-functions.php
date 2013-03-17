<?php
/**
 * Functions for wp e-commerce
 */
 
if( !class_exists( 'WP_eCommerce' ) ) 
	wp_die( 'Please install the wp-e-commerce plugin' );
	
/**
 *  Get the product categories
 */
function aesthetic_get_categories( $args = array() ){
	$categories = array();
	
	$category_data = get_terms( 'wpsc_product_category', $args );
	
	foreach( (array)$category_data as $category_row ){
		$category_row->image = wpsc_get_categorymeta( $category_row->term_id, 'image' );
		
		$categories[$category_row->term_id] = $category_row;
	}
	
	return $categories;
}

/**
 * Get the deals
 */
function aesthetic_get_deals( $args = '' ){
	global $wp_query;
	
	$wp_query->query( $args );
}

/**
 * List the deals
 */
function aesthetic_list_deals(){
	
	$post = $_POST;
	
	$args = array(
		'post_status' => 'publish',
		'post_type'   => 'wpsc-product',
		'posts_per_page' => -1
	);
	
	// Category filter
	if( $post['cat'] ){
		$term = get_term_by( 'id', $post['cat'], 'wpsc_product_category' );
		
		$args['wpsc_product_category'] = $term->slug;
	}
	
	// Price filter
	if( isset( $post['price_start'] ) && isset( $post['price_to'] ) ){
		$args['meta_query'] = array(
			array(
				'key' => '_wpsc_special_price',
				'value' => array( $post['price_start'], $post['price_to'] ),
				'type' => 'numeric',
				'compare' => 'BETWEEN'
			)
		);
	}
	
	// Ending today deals
	if( $post['query'] == 'today' ){
		$args['meta_query'][] = array(
			'key' => '_aes_product_endtime',
			'value' => date( 'Y-m-d', current_time( 'timestamp' ) ),
			'type' => 'DATE'
		);
	}
	
	// Ending soon deals
	if( $post['query'] == 'soon' ){
		$args['meta_query'][] = array(
			'key' => '_aes_product_endtime',
			'value' => array( date( 'Y-m-d H:i', current_time( 'timestamp' ) ), date( 'Y-m-d H:i', ( current_time( 'timestamp' ) + 3600 ) ) ),
			'type' => 'DATETIME',
			'compare' => 'BETWEEN'
		);
	}
	
	// Featured deals
	if( isset( $post['query'] ) && $post['query'] == 'featured' ){
		$args['post__in'] = get_option( 'sticky_products' );
	}
	
	// Deals in user wishlist
	if( isset( $post['query'] ) && $post['query'] == 'wishlist' ){
		$args['post__in'] = get_user_meta( get_current_user_id(), '_aes_wishlist', true );
	}
	
	$args['meta_query'][] = array(
		'key' => '_aes_product_endtime',
		'value' => date( 'Y-m-d H:i', current_time( 'timestamp' ) ),
		'type' => 'DATETIME',
		'compare' => '>'
	);
	
	if( $post['keyword'] )
		$args['s'] = $post['keyword'];
	
//	echo '<pre>';
//	print_r( $args );
//	exit;
	aesthetic_get_deals( $args );
	
	if( wpsc_have_products() ) :
?>
	<ul>
<?php while( wpsc_have_products() ) : wpsc_the_product(); ?>
		<li>
			<div class="mosaic-block bar">
				<a href="<?php echo wpsc_the_product_permalink(); ?>" class="mosaic-overlay">
					<div class="details">
						<h1><?php echo wpsc_the_product_title(); ?></h1>
						<h2><span class="before_price">Before: <?php echo wpsc_currency_display( wpsc_calculate_price(wpsc_the_product_id(), false, false ) ); ?></span> Now: <?php echo wpsc_currency_display( wpsc_calculate_price(wpsc_the_product_id() ) ); ?></h2>
					</div>
				</a>
				<div class="mosaic-backdrop">
					<a href="<?php echo wpsc_the_product_permalink(); ?>">
					<?php if( $thumb = get_post_meta( wpsc_the_product_id(), '_aes_product_thumb', true ) ) : ?>
						<img src="<?php echo $thumb; ?>" alt="<?php echo wpsc_the_product_title(); ?>" alt="<?php echo wpsc_the_product_title(); ?>" title="<?php echo wpsc_the_product_title(); ?>" />
					<?php else : ?>
						<img src="<?php echo aesthetic_get_default_image_url(); ?>" alt="<?php echo wpsc_the_product_title(); ?>" />
					<?php endif; ?>
					</a>
				</div>
				<div class="like"><a class="<?php if( aesthetic_is_in_wish( wpsc_the_product_id() ) ) echo 'wishlist-remove'; else echo 'wishlist-add'; ?>" deal="<?php echo wpsc_the_product_id(); ?>" href="#" title="Wishlist"></a></div>
			</div>
		
			<div class="clearfix"></div>
			<div class="nav"><a href="<?php echo wpsc_the_product_permalink(); ?>" title="some title" class="btnNav">Buy now</a> </div>
			<span class="new"></span>
		</li>
	</ul>
<?php
		endwhile;
?>
		<div class="clear"></div>	
<?php
	else :
		echo '<p>There are no deals</p>';
	endif;
	
	exit;
}
add_action( 'wp_ajax_nopriv_get_deals', 'aesthetic_list_deals' );
add_action( 'wp_ajax_get_deals', 'aesthetic_list_deals' );

function aesthetic_echo_query( $input ){
	if( $_POST['action'] == 'get_deals' )
		echo $input;
	return $input;
}
//add_filter( 'posts_request', 'aesthetic_echo_query' );

/**
 * Add meta boxes
 */
function aesthetic_add_metaboxes(){
	global $hgwec_prod;
	
	add_meta_box(
		'aesthetic_deal_metabox',
		'Deals Settings',
		'aesthetic_deal_metabox',
		'wpsc-product'
	);
	
	add_meta_box(
		'aesthetic_product_thumb_metabox',
		'Product Front Image',
		'aesthetic_product_thumb_metabox',
		'wpsc-product'
	);
	
}
add_action( 'add_meta_boxes', 'aesthetic_add_metaboxes' );

/**
 * meta box for product size
 */
function aesthetic_deal_metabox( $post ){
	
	$endtime = get_post_meta( $post->ID, '_aes_product_endtime', true );
	$deal_allow = get_post_meta( $post->ID, '_aes_product_deal_allow', true );
?>
	<script>
		 jQuery(document).ready(function() {
			jQuery( "#aes_product_endtime" ).datetimepicker({
				dateFormat: 'yy-mm-dd',
				timeFormat: 'HH:mm',
				minDate: 0
			});
		});
	</script>
<?php 
	echo '<p>';
	echo '<label for="aes_product_endtime">End time: </label><br />';
	echo '<input type="text" id="aes_product_endtime" name="aes_product_endtime" value="'. esc_attr( $endtime ) .'" readonly="readonly" size="25" />';
	echo '</p>';
	
	echo '<p>';
	echo '<label for="aes_product_deal_allow">No of Deals: </label><br />';
	echo '<input type="text" id="aes_product_deal_allow" name="aes_product_deal_allow" value="'. esc_attr( $deal_allow ) .'" size="25" />';
	echo '</p>';
}

/**
 * meta box for product thumb
 */
function aesthetic_product_thumb_metabox( $post ){
	
	$value = get_post_meta( $post->ID, '_aes_product_thumb', true );
?>
	<?php if( $value ) : ?><img src="<?php echo $value; ?>" style="width:100px; height:100px" /><br /><?php endif; ?>
	<input type="text" id="aes_product_thumb" name="aes_product_thumb" readonly="readonly" value="<?php echo esc_attr( $value ) ?>" size="75" />
	<input type="button" id="aes_product_file_but" value="Select File" />
    
    <script>
		jQuery(document).ready(function(){
			jQuery( '#aes_product_file_but' ).click(function() { 
			 formfield = jQuery( '#aes_product_thumb' );
			 tb_show('', 'media-upload.php?tab=gallery&context=choose&TB_iframe=1');
			 return false;
			});
			
			window.send_to_editor = function(html) {
			//alert( html );
			 var ele = jQuery( html );
			 jQuery(formfield).val( ele.attr( 'href' ) );
			 tb_remove();
			}
		});
	</script>
    
<?php
}

/**
 * Save the deals meta details
 */
function aesthetic_deal_meta_save( $post_id ){
	
	// verify if this is an auto save routine. 
	// If it is our form has not been submitted, so we dont want to do anything
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return;
	
	// Check permissions
	if ( 'wpsc-product' == $_POST['post_type'] ){
		if ( !current_user_can( 'edit_page', $post_id ) )
		return;
	}
	
	$endtime = ( !empty( $_POST['aes_product_endtime'] ) ) ? sanitize_text_field( $_POST['aes_product_endtime'] ) : date( 'Y-m-d H:i:s', strtotime( '+2 day', current_time( 'timestamp' ) ) );
	$deal_allow = ( is_int( $_POST['aes_product_deal_allow'] ) ) ? sanitize_text_field( $_POST['aes_product_deal_allow'] ) : '10';
	$thumb = sanitize_text_field( $_POST['aes_product_thumb'] );
	
	if( $endtime )
		update_post_meta( $post_id, '_aes_product_endtime', $endtime );
	if( $deal_allow )
		update_post_meta( $post_id, '_aes_product_deal_allow', $deal_allow );
		
	// Product thumb
	if( $thumb )
		update_post_meta( $post_id, '_aes_product_thumb', $thumb );

}
add_action( 'save_post', 'aesthetic_deal_meta_save' );

/**
 * Get the deal default image
 */
function aesthetic_get_default_image_url(){
	return get_template_directory_uri() .'/images/Deal Image but_Norm.jpg';
}

function aesthetic_reg_type(){
	global $wp_post_types;
	
	if( $wp_post_types['wpsc-product'] ){

		//unset( $wp_post_types['wpsc-product']->cap->read );
//		echo '<pre>';
//		print_r( $wp_post_types['wpsc-product'] );
//		echo '</pre>';
//		exit;
//		$wp_post_types['wpsc-product']->capability_type = 'wpsc-product';
//		$wp_post_types['wpsc-product']->labels->name = 'Deals';
//		

//		$wp_post_types['wpsc-product']->cap->edit_post = 'edit_wpsc-product';
//		$wp_post_types['wpsc-product']->cap->read_post = 'read_wpsc-product';
//		$wp_post_types['wpsc-product']->cap->delete_post = 'delete_wpsc-product';
//		$wp_post_types['wpsc-product']->cap->edit_posts = 'edit_wpsc-products';
//		$wp_post_types['wpsc-product']->cap->edit_others_posts = 'edit_others_posts';
//		$wp_post_types['wpsc-product']->cap->publish_posts = 'publish_wpsc-products';
//		$wp_post_types['wpsc-product']->cap->create_posts = 'edit_wpsc-products';
		
//		echo '<pre>';
//		print_r( $wp_post_types['wpsc-product'] );
//		echo '</pre>';
//		exit;
	}
}
add_action( 'init', 'aesthetic_reg_type', 100 );
add_action( 'admin_init', 'aesthetic_reg_type', 10 );

##### Wishlist section start #########

/**
 * Add to wishlist
 */
function aesthetic_add_to_wish( $id = '' ){
	if( empty( $id ) || !is_user_logged_in() )
		return false;
		
	if( aesthetic_is_in_wish( $id ) == false ){
		$wishlist = aesthetic_get_wish();
		array_push( $wishlist, $id );
		return aesthetic_update_wish( $wishlist );
	}
	
	return false;
}

/**
 * Remove from the wishlist
 */
function aesthetic_remove_from_wish( $id = '' ){
	if( empty( $id ) || !is_user_logged_in() )
		return false;
		
	if( aesthetic_is_in_wish( $id ) ){
		$wishlist = aesthetic_get_wish();
		$unset_key = array_search( $id, $wishlist );
		unset( $wishlist[$unset_key] );
		
		return aesthetic_update_wish( $wishlist );
	}
	
	return false;
}

/**
 * Get the all wishlist
 */
function aesthetic_get_wish(){
	if( !is_user_logged_in() )
		return false;
		
	$ret = get_user_meta( get_current_user_id(), '_aes_wishlist', true );
	
	return ( is_array( $ret ) ) ? $ret : array();
}

/**
 * Check whether a product already exists or not
 */
function aesthetic_is_in_wish( $id = '' ){
	if( !is_user_logged_in() )
		return false;
		
	$wishlist = aesthetic_get_wish();
	
	return in_array( $id, $wishlist );
}

/**
 * Update the user wishlist
 */
function aesthetic_update_wish( $content = array() ){
	if( !is_user_logged_in() )
		return false;
		
	return update_user_meta( get_current_user_id(), '_aes_wishlist', $content );
}

##### Wishlist section end ###########

/**
 * Add wishlist through AJAX
 */
function aesthetic_wish_add(){
	$id = $_POST['id'];
	
	if( aesthetic_is_in_wish( $id ) == false )
		$ret = aesthetic_add_to_wish( $id );
	else
		aesthetic_action_msg( 'Already added' );
	
	if( $ret )
		aesthetic_action_msg( 'Added successfully', false );
	else
		aesthetic_action_msg( 'Something went wrong' );
		
	exit;
}
add_action( 'wp_ajax_add_to_wishlist', 'aesthetic_wish_add' );

/**
 * Remove from wishlist through AJAX
 */
function aesthetic_wish_remove(){
	$id = $_POST['id'];
	
	if( aesthetic_is_in_wish( $id ) )
		$ret = aesthetic_remove_from_wish( $id );
	
	if( $ret )
		aesthetic_action_msg( 'Removed successfully', false );
	else
		aesthetic_action_msg( 'Something went wrong' );
		
	exit;
}
add_action( 'wp_ajax_remove_from_wishlist', 'aesthetic_wish_remove' );

/**
 * Search redirect
 */
function aesthetic_search_redirect(){
	if ( ( stripos( $_SERVER['REQUEST_URI'], '?s=' ) === FALSE ) && ( stripos( $_SERVER['REQUEST_URI'], '/search/') === FALSE ) && ( !is_search() ) )
    	return;
		
	wp_redirect( get_page_link( 17 ) .'?keyword='. $_REQUEST['s'] );
	exit;
}
add_action( 'template_redirect', 'aesthetic_search_redirect' );

/**
 * WPSC admin columns
 */
function aesthetic_wpsc_admin_columns( $columns ){
	unset( $columns['weight'] );
	unset( $columns['stock'] );
	unset( $columns['SKU'] );
	
	if( current_user_can( 'author' ) )
		unset( $columns['featured'] );
		
	$new_columns = array(
		'endtime' => __( 'End Time' )
	);
	
	$columns = array_merge( $columns, $new_columns );
		
	return $columns;
}
add_filter( 'manage_edit-wpsc-product_columns', 'aesthetic_wpsc_admin_columns' );

/**
 * WPSC Admin sortable columns
 */
function aesthetic_wpsc_admin_sortable_columns( $columns ){
	$columns['endtime'] = 'endtime';
	
	return $columns;
}
add_filter( 'manage_edit-wpsc-product_sortable_columns', 'aesthetic_wpsc_admin_sortable_columns' );

/**
 * WPSC Admin column data
 */
function aesthetic_wpsc_admin_column_data( $column ){
	global $post;
	$ret = '';
	
	if( $column == 'endtime' ){
		$current_time = current_time( 'timestamp' );
		$endtime_str = get_post_meta( $post->ID, '_aes_product_endtime', true );
		$endtime = strtotime( $endtime_str );
		
		$ret = date( get_option( 'date_format' ) .' '. get_option( 'time_format' ) , $endtime );
		
		if( $endtime > $current_time )
			$ret .= '<br />'. human_time_diff( $endtime, $current_time ) .' Remaining';
		else
			$ret .= '<br /> Expired';
	}
	
	echo $ret;
}
add_action( 'manage_pages_custom_column', 'aesthetic_wpsc_admin_column_data' );

function aesthetic_add_editor(){
	exit( 'calling' );
	wp_editor( 'some', 'dealdescription' );	
}
add_action( 'edit_page_form', 'aesthetic_add_editor' );

/**
 * Add a merchant deal
 */
function aesthetic_add_merchant_deal(){
	global $deal_error;

	if( $_POST['deal_submit'] ){
		
		$deal_data = array(
			'post_title' => sanitize_title( $_POST['title'] ),
			'post_content' => esc_textarea( $_POST['dealdescription'] ),
			'post_status' => 'publish',
			'post_author' => get_current_user_id(),
			'post_type' => 'wpsc-product'
		);
		
		foreach( $deal_data as $key => $val ){
			if( empty( $val ) ){
				$deal_error = 'Required fields are mandatory';
				break;
			}
		}
		
		if( empty( $deal_error ) )
			$post_id = wp_insert_post( $deal_data );
		
		// Handle image upload
		include_once ABSPATH . 'wp-admin/includes/media.php';
		include_once ABSPATH . 'wp-admin/includes/file.php';
		include_once ABSPATH . 'wp-admin/includes/image.php';
		
		if( $post_id && empty( $deal_error ) ){
			
		}
		
		$deal_success = 'You deal added successfully';
	}
}
add_action( 'init', 'aesthetic_add_merchant_deal' );

/**
 * Add the deal images
 */
function aesthetic_attach_image(){
	$ret = array();

	if( $_FILES['file' ] ){
		$upload_file = $_FILES['file'];
		$upload_overrides = array( 'test_form' => false );
		
		$move_file = wp_handle_upload( $upload_file, $upload_overrides );
		
		if( $move_file['url'] ){
			$attachment_data = array(
				'guid' => $move_file['url'],
				'post_mime_type' => $move_file['type'],
				'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $move_file['url'] ) ),
				'post_content' => '',
				'post_status' => 'inherit'
			);
			
			$attachment_id = wp_insert_attachment( $attachment_data, $move_file['url'] );
		
			$attach_data = wp_generate_attachment_metadata( $attachment_id, $move_file['url'] );
			wp_update_attachment_metadata( $attachment_id, $attach_data );
			
			$ret = array(
				'type' => 'success',
				'attach_id' => $attachment_id,
				'attach_url' => $move_file['url'],
				'msg' => ''
			);
			
		}else{
			$ret = array(
				'type' => 'error',
				'message' => 'Error occured in attachment insert'
			);
		}
	}
	
	echo json_encode( $ret );
	exit;
}
add_action( 'wp_ajax_deal_attach_file', 'aesthetic_attach_image' );

