<?php
/**
 * Template Name: My account
 */
 get_header();
 
// echo '<pre>';
// print_r( $current_user );
// echo '</pre>';
?>
	<script>
		jQuery(document).ready(function($){
			var tab = 'profile';
			showHide( tab );
			
			// profile update
			jQuery( '#profile_form' ).validate({
				errorElement: 'span',
				rules:{
					first_name: { required: true, minlength: 5 },
					last_name: { required: true, minlength: 5 },
					contact: { required: true, digits: true }
				},
				submitHandler: function(){
					actionShow( '#profile_form .action-msg' )
				
					$.post( '<?php echo admin_url( 'admin-ajax.php' ); ?>', $('#profile_form').serialize(), function(data){
						var retObj = jQuery.parseJSON( data );
						
						actionHide( '#profile_form .action-msg', retObj.message );
					} );
				}
			});
			
			// Address update
			jQuery( '#address_form' ).validate({
				errorElement: 'span',
				rules:{
					address_one: { required: true, minlength: 10, maxlength: 100 },
					address_two: { required: true, minlength: 10, maxlength: 100 },
					pin: { required: true, digits: true, minlength: 3, maxlength: 6 },
					emirate: { required: true }
				},
				submitHandler: function(){
					actionShow( '#address_form .action-msg' );
				
					$.post( '<?php echo admin_url( 'admin-ajax.php' ); ?>', $('#address_form').serialize(), function(data){
						var retObj = jQuery.parseJSON( data );
						
						actionHide( '#address_form .action-msg', retObj.message );
					} );
				}
			});
			
			// Security update
			jQuery( '#security_form' ).validate({
				errorElement: 'span',
				rules:{
					old_password : { required: true, minlength: 5 },
					new_password: { required: true, minlength: 5 },
					confirm_password: { required: true, equalTo: '#new_password' },
				},
				submitHandler: function(){
					actionShow( '#security_form .action-msg' );
				
					$.post( '<?php echo admin_url( 'admin-ajax.php' ); ?>', $('#security_form').serialize(), function(data){
						var retObj = jQuery.parseJSON( data );
						
						actionHide( '#security_form .action-msg', retObj.message );
					} );
				}
			});
			
			// Add a deal validation and submition
			jQuery( '#add_deal_form' ).validate({
				errorElement: 'span',
				rules:{
					title : { required: true, minlength: 5 },
					price: { required: true, number: true },
					special_price: { required: true, dealPrice: '#price' },
					end_time: { required: true }
				},
				submitHandler: function(){
					actionShow( '#add_deal_form .action-msg' );
				
					//alert( tinymce.get( 'dealdescription' ).getContent() );
					$.post( '<?php echo admin_url( 'admin-ajax.php' ); ?>', 
						{
							action: 'add_merchant_deal',
							post_id: $( '#add_deal_form #post_id' ).val(),
							title: $( '#add_deal_form #title' ).val(),
							desc: tinymce.get( 'dealdescription' ).getContent(),
							cat: $( '#add_deal_form #cat' ).val(),
							front_image_id: $( '#add_deal_form #front_image_id' ).val(),
							deal_image_id: $( '#add_deal_form #deal_image_id' ).val(),
							price: $( '#add_deal_form #price' ).val(),
							special_price: $( '#add_deal_form #special_price' ).val(),
							end_time: $( '#add_deal_form #end_time' ).val()
						}, 
						function(data){
							var ret = $.parseJSON( data );
							
							if( ret.type == 'success' ){
								$( '#post_id' ).val( ret.post_id );
								
								actionHide( '#add_deal_form .action-msg', ret.message );
							}
						} 
					);
				}
			});
			
			// Date time picker
			jQuery( "#end_time" ).datetimepicker({
				dateFormat: 'yy-mm-dd',
				timeFormat: 'HH:mm',
				minDate: 0
			});
			
			// Price validation
			jQuery.validator.addMethod( 'dealPrice', function( value, element, compareTo ){
				var price = parseFloat( jQuery( compareTo ).val() ); 
				var val = parseFloat( value );
				
				if( price < val ) 
					return false;
				else
					return true;
			}, 'Sales price value should be less than price value');
			
			jQuery( '.edit-act' ).live( 'click', function(){
				showHide( 'add_deal' );
			} );
			
			jQuery( '.add_deal_tab' ).click(function(){
				showDealForm();
			});
			
			// Get a user wallet amount
			jQuery( '.order_tab' ).click(function(){
				$.post( '<?php echo admin_url( 'admin-ajax.php' ); ?>', { action: 'get_currentuser_wallet' }, function(data){
					jQuery( '#user_wallet' ).html( data );
				} );
			});
			
			jQuery( '#return_cancel' ).live( 'click', function(){
				jQuery.zoombox.close();
			});
				
		});
		
		function actionShow( selector ){
			var actionCont = jQuery( selector );
			
			actionCont.addClass( 'action-load' );
			actionCont.html('&nbsp;&nbsp;&nbsp&nbsp;&nbsp;');
			actionCont.fadeIn();
		}
		
		function actionHide( selector, message ){
			var actionCont = jQuery( selector );
		
			actionCont.removeClass( 'action-load' );
			actionCont.html( message );
			actionCont.delay(2000).fadeOut();
		}
		
		function showHide( id ){
			if( !id )
				return;
			
			jQuery( '.content .formfield' ).removeClass( 'display_block' );
			jQuery( '.content .formfield' ).addClass( 'display_none' );
			
			jQuery( '.tabs .active' ).removeClass( 'active' );
			jQuery( '.tabs .active' ).parent().removeClass( 'active' );
			
			jQuery( '.content #'+ id ).addClass( 'display_block' );
			jQuery( '.content #'+ id ).removeClass( 'display_none' );
			
			jQuery( '.'+ id +'_tab' ).addClass( 'active' );
			jQuery( '.'+ id +'_tab' ).parent().addClass( 'active' );
		}
		
		function get_update_deal( dealId ){
			
			showDealForm( dealId );
				
			jQuery.post( '<?php echo admin_url( 'admin-ajax.php' ); ?>', 
				{
					action: 'get_merchant_deal',
					deal_id: dealId,
				}, 
				function(data){
					//alert( data );
					//return;
					var retObj = jQuery.parseJSON( data );
					
					jQuery( '#add_deal_form #title' ).val( retObj.name );
					tinymce.get( 'dealdescription' ).setContent( retObj.desc );
					jQuery( '#add_deal_form #cat' ).val( retObj.cat );
					jQuery( '#add_deal_form #price' ).val( retObj.price );
					jQuery( '#add_deal_form #special_price' ).val( retObj.special_price );
					jQuery( '#add_deal_form #end_time' ).val( retObj.end_time );
					jQuery( '#add_deal_form #front_image_list' ).html( '<img src="'+ retObj.thumb +'" style="width:50px; height: 50px" >' );
					jQuery( '#add_deal_form #deal_image_list' ).html( '<img src="'+ retObj.img +'" style="width:50px; height: 50px" >' );
				} 
			);
		}
		
		function showDealForm( dealId ){
			if( dealId ){
				jQuery( '#add_deal_form #post_id' ).val( dealId );	
				
				jQuery( '#add_deal_form #deal_heading' ).html( 'Edit a Deal' );
			}else{
				jQuery( '#add_deal_form #deal_heading' ).html( 'Add a Deal' );
				dealFormReset();
			}
		}
		
		function dealFormReset(){
			jQuery( '#add_deal_form #title' ).val( '' );
			tinymce.get( 'dealdescription' ).setContent( '' );
			jQuery( '#add_deal_form #cat' ).val( '' );
			jQuery( '#add_deal_form #price' ).val( '' );
			jQuery( '#add_deal_form #special_price' ).val( '' );
			jQuery( '#add_deal_form #end_time' ).val( '' );
			jQuery( '#add_deal_form #front_image_list' ).html( '' );
			jQuery( '#add_deal_form #deal_image_list' ).html( '' );
			jQuery( '#add_deal_form #post_id' ).val( '' );
		}
		
		function returnDeal( orderId ){
			if( !orderId )
				return false;
			
			Messi.ask( 'Are you sure to return this deal?', function( val ){
				if( val == 'N' ){
					jQuery( '.messi-box' ).hide();	
				}else{
					jQuery.post( '<?php echo admin_url( 'admin-ajax.php' ); ?>', { action: 'return_deal', return_deal_id: orderId }, function(data){
						var retObj = jQuery.parseJSON( data );
						
						if( retObj.type == 'error' ){
							new Messi( retObj.message, { closeButton: false} );
						}else{
							new Messi( retObj.message, { closeButton: false } );
							jQuery( '.flex-orders' ).flexReload();
							setTimeout( "messiClose()", 2000 );
						}
					} );
				}				
			} );
		}
		
		function returnApprove( orderId ){
			if( !orderId )
				return false;
			
			Messi.ask( 'Do you accept this return?', function( val ){
				if( val == 'N' ){
					jQuery( '.messi-box' ).hide();	
				}else{
					jQuery.post( '<?php echo admin_url( 'admin-ajax.php' ); ?>', { action: 'return_approve', return_deal_id: orderId }, function(data){
						var retObj = jQuery.parseJSON( data );
						
						if( retObj.type == 'error' ){
							new Messi( retObj.message, { closeButton: false } );
						}else{
							new Messi( retObj.message, { closeButton: false } );
							jQuery( '.flex-mer-orders' ).flexReload();
							setTimeout( "messiClose()", 2000 );
						}
					} );
				}				
			} );
		}
		
		
		function getMerchantOrder( productId ){
		
			if( !productId )
				return false;
			
			var actionURL = jQuery( '#merchant_order_action' ).val() +'&product_id='+ productId;
			
			jQuery(".flex-mer-orders").flexOptions( {url: actionURL} ).flexReload();
			
			showHide( 'merorder' );
		}
		
	</script>
	<div id="static_page">
       <div class ="left_side_menu clsFloatLeft">
	   		<ul class="tabs">
				<?php echo aesthetic_user_menu(); ?>
			</ul>
	   </div>
       <div class="content clsFloatRight">
	   		
			<div class="register_form">
			
				<!-- Profile tab -->
				<div id="profile" class="formfield">
				<?php
					$first_name = $current_user->first_name;
					$last_name = $current_user->last_name;
					$email = $current_user->user_email;
					$contact = get_user_meta( get_current_user_id(), '_aes_contact', true );
				?>
					<form name="profile_form" id="profile_form" action="" method="post">
						<?php wp_nonce_field( 'profile_update', 'profile_update_nonce' ); ?>
                        <input type="hidden" name="action" value="profile_update" />
						<h1>Account Owner Information</h1>
						<div class="clsFormField">
							<div class="label clsFloatLeft">First Name <span class="red">*</span>:</div>
							 <div class="clsInput clsFloatRight">
								<input type="text" id="first_name" name="first_name" value="<?php echo $first_name; ?>" />
							</div>
						</div>
						<div class="clear"></div>
						<div class="clsFormField">
							<div class="label clsFloatLeft">Last Name <span class="red">*</span>:</div>
							 <div class="clsInput clsFloatRight">
								<input type="text" id="last_name" name="last_name" value="<?php echo $last_name; ?>" />
							</div>
						</div>
						<div class="clear"></div>
						
						<div class="clsFormField">
							<div class="label clsFloatLeft">Email <span class="red">*</span>:</div>
							 <div class="clsInput clsFloatRight">
								<span><?php echo $email; ?></span>
							</div>
						</div>
						<div class="clear"></div>
						
						<div class="clsFormField">
							<div class="label clsFloatLeft">Contact Number <span class="red">*</span>:</div>
							 <div class="clsInput clsFloatRight">
								<input type="text" id="contact" name="contact" value="<?php echo $contact; ?>" />
							</div>
						</div>
						<div class="clear"></div>
						
						<div class="clsFormField">
						<br />
							<div class="label clsFloatLeft">&nbsp;</div>
							 <div class="clsInput clsFloatLeft">
								<input type="submit" id="profile_submit" name="profile_submit" value="Save" class="btnSubmit"  />
								<span class="action-msg"></span>
							</div>
						</div>
						<div class="clear"></div>
					</form>
				</div>
				
				<!-- Change address tab -->
				<div id="address" class="formfield">
				<?php 
					$address_one = get_user_meta( get_current_user_id(), '_aes_address_one', true );
					$address_two = get_user_meta( get_current_user_id(), '_aes_address_two', true );
					$pin = get_user_meta( get_current_user_id(), '_aes_address_pin', true );
					$emirate_id = get_user_meta( get_current_user_id(), '_aes_address_emirate', true );
				?>
					<form name="address_form" id="address_form" method="post" action="">
						<?php wp_nonce_field( 'address_update', 'address_update_nonce' ); ?>
                        <input type="hidden" name="action" value="address_update" />
					
						<h1>Change Address</h1>
						
						<div class="clsFormField">
							<div class="label clsFloatLeft">Address one <span class="red">*</span>:</div>
							 <div class="clsInput clsFloatRight">
								<input type="text" id="address_one" name="address_one" value="<?php echo $address_one; ?>" />
							</div>
						</div>
						<div class="clear"></div>
						
						<div class="clsFormField">
							<div class="label clsFloatLeft">Address two <span class="red">*</span>:</div>
							 <div class="clsInput clsFloatRight">
								<input type="text" id="address_two" name="address_two" value="<?php echo $address_two; ?>" />
							</div>
						</div>
						<div class="clear"></div>
						
						<div class="clsFormField">
							<div class="label clsFloatLeft">PO Box <span class="red">*</span>:</div>
							 <div class="clsInput clsFloatRight">
								<input type="text" id="pin" name="pin" value="<?php echo $pin; ?>" />
							</div>
						</div>
						<div class="clear"></div>
						
						<div class="clsFormField">
							<div class="label clsFloatLeft">Emirate <span class="red">*</span>:</div>
							 <div class="clsInput clsFloatRight">
								  <select name="emirate" id="emirate" class="clsSelect">
										<option value="">Select Emirate</option>
									<?php
										global $aes_emirates;
										foreach( $aes_emirates as $id => $name ) :
									?>
										<option value='<?php echo $id; ?>' <?php if( $emirate_id == $id ) echo 'selected="selected"'; ?>><?php echo ucfirst( $name ); ?></option>
									<?php endforeach; ?>
										
								 </select>
							</div>
						</div>
						<div class="clear"></div>
						
						<div class="clsFormField">
						<br />
							<div class="label clsFloatLeft">&nbsp;</div>
							 <div class="clsInput clsFloatLeft">
								<input type="submit" id="address_submit" name="address_submit" value="Submit" class="btnSubmit"  />
								<span class="action-msg"></span>
							</div>
						</div>
						<div class="clear"></div>
					</form>
				</div>
				
				<!-- Security tab -->
				<div id="security" class="formfield">
					<form name="security_form" id="security_form" action="" method="post">
						<?php wp_nonce_field( 'security_update', 'security_update_nonce' ); ?>
                        <input type="hidden" name="action" value="security_update" />
					
						<h1>Account Security Settings</h1>
						
						<div class="clsFormField">
							<div class="label clsFloatLeft">Old Password <span class="red">*</span>:</div>
							 <div class="clsInput clsFloatRight">
								<input type="password" id="old_password" name="old_password" />
							</div>
						</div>
						<div class="clear"></div>
						
						<div class="clsFormField">
							<div class="label clsFloatLeft">New Password <span class="red">*</span>:</div>
							 <div class="clsInput clsFloatRight">
								<input type="password" id="new_password" name="new_password" />
							</div>
						</div>
						<div class="clear"></div>
						
						<div class="clsFormField">
							<div class="label clsFloatLeft">Confirm Password <span class="red">*</span>:</div>
							 <div class="clsInput clsFloatRight">
								<input type="password" id="confirm_password" name="confirm_password" />
							</div>
						</div>
						<div class="clear"></div>
						
						<div class="clsFormField">
						<br />
							<div class="label clsFloatLeft">&nbsp;</div>
							 <div class="clsInput clsFloatLeft">
								<input type="submit" id="security_submit" name="security_submit" value="Submit" class="btnSubmit"  />
								<span class="action-msg"></span>
							</div>
						</div>
						<div class="clear"></div>
					</form>
				</div>
				
				<?php if( current_user_can( 'author' ) ) : ?>
				<!-- Add a deal tab -->
				<div id="add_deal" class="formfield">
					<form name="add_deal_form" id="add_deal_form" action="" method="post" enctype="multipart/form-data">
						<input type="hidden" name="action" id="deal_action" value="add_deal" />
						<input type="hidden" name="post_id" id="post_id" value=""  />
						<?php  
							$title = $_POST['title'];
							$dealdescription = $_POST['dealdescription'];
							$cat = $_POST['cat'];
							$price = $_POST['price'];
							$special_price = $_POST['special_price'];
							$end_time = $_POST['end_time'];
						?>
						<h1 id="deal_heading">Add a Deal</h1>
						<div class="clsFormField">
							<div class="label clsFloatLeft">Title <span class="red">*</span>:</div>
							 <div class="clsInput clsFloatRight">
								<input type="text" id="title" name="title" value="<?php echo $title; ?>" />
							</div>
						</div>
						<div class="clear"></div>
						
						<div class="clsFormField editor">
							<div class="label clsFloatLeft">Description <span class="red">*</span>:</div>
							 <div class="clsInput clsFloatRight field-editor">
								<?php 
									$args = array(
										'textarea_rows' => '9',
										'media_buttons' => true
									);
									wp_editor( $dealdescription, 'dealdescription', $args ); 
								?>
							</div>
						</div>
						<div class="clear"></div>
						
						<div class="clsFormField">
							<div class="label clsFloatLeft">Deal Category <span class="red">*</span>:</div>
							 <div class="clsInput clsFloatRight">
								<?php
									$args = array(
										'taxonomy' => 'wpsc_product_category',
										'class' => 'clsSelect',
										'selected' => $cat
									);
									wp_dropdown_categories( $args );
								?>
							</div>
						</div>
						<div class="clear"></div>
						
						<div class="clsFormField">
							<div class="label clsFloatLeft">Deal Front Image :</div>
							 <div id="front_image_container" class="clsInput clsFloatRight" style="height:65px">
								<!--<input type="file" name="deal_image" id="deal_image" />-->
								<span id="front_image_list">&nbsp;</span>
								<input type="button" id="front_image_select" class="btnSubmit" value="Upload Image" style="top:-9px"><br />
								<span id="front_image_error" class="error"></span>
								<input type="hidden" name="from_image_id" id="front_image_id" value="" />
							</div>
						</div>
						<div class="clear"></div>
						
						<div class="clsFormField">
							<div class="label clsFloatLeft">Deal Image :</div>
							 <div id="deal_image_container" class="clsInput clsFloatRight" style="height:65px">
								<!--<input type="file" name="deal_image" id="deal_image" />-->
								<span id="deal_image_list">&nbsp;</span>
								<input type="button" id="deal_image_select" class="btnSubmit" value="Upload Image" style="top:-9px" /><br />
								<span id="deal_image_error" class="error"></span>
								<input type="hidden" name="deal_image_id" id="deal_image_id" value="" />
							</div>
						</div>
						<div class="clear"></div>
						
						<div class="clsFormField">
							<div class="label clsFloatLeft">Price (<?php echo wpsc_get_currency_symbol(); ?>) <span class="red">*</span>:</div>
							 <div class="clsInput clsFloatRight">
								<input type="text" name="price" id="price" value="<?php echo $price; ?>" />
							</div>
						</div>
						<div class="clear"></div>
						
						<div class="clsFormField">
							<div class="label clsFloatLeft">Sale Price (<?php echo wpsc_get_currency_symbol(); ?>) <span class="red">*</span>:</div>
							 <div class="clsInput clsFloatRight">
								<input type="text" name="special_price" id="special_price" value="<?php echo $special_price; ?>" />
							</div>
						</div>
						<div class="clear"></div>
						
						<div class="clsFormField">
							<div class="label clsFloatLeft">End Time <span class="red">*</span>:</div>
							 <div class="clsInput clsFloatRight">
								<input type="text" name="end_time" id="end_time" readonly="readonly" value="<?php echo $end_time; ?>" />
							</div>
						</div>
						<div class="clear"></div>
						
						<div class="clsFormField">
						<br />
							<div class="label clsFloatLeft">&nbsp;</div>
							 <div class="clsInput clsFloatLeft">
								<input type="submit" id="deal_submit" name="deal_submit" value="Submit" class="btnSubmit"  />
								<span class="action-msg"></span>
							</div>
						</div>
						<div class="clear"></div>
						
					</form>
				</div>
				<?php endif; ?>
				
				<?php if( current_user_can( 'author' ) ) : ?>
				<!-- My deal tab -->
				<div id="mydeal" class="formfield">
					<h1>My Deals</h1>
					<?php aesthetic_merchant_my_deals(); ?>
				</div>
				
				<!-- Merchant orders tab -->
				<div id="merorder" class="formfield">
					<h1>Orders</h1>
					<?php echo aesthetic_purchase_log_merchant(); ?>
				</div>
				<?php endif; ?>
				
				<!-- Orders tab -->
				<div id="order" class="formfield">
					<h1>Order History</h1>
					
					<div id="wallet-section">
						<h3>In your wallet: <span id="user_wallet"></span></h3>
					</div>
					
					<?php echo aesthetic_purchase_log_user(); ?>
				</div>
				
			</div>	
		</div>
    <div class="clear"></div>
 </div>
 
 <!-- plupload script -->
 <script>
 	jQuery(document).ready(function($){
	
		// Front image upload
		var uploader = new plupload.Uploader({
			runtimes : 'gears,html5,flash,silverlight,browserplus',
			browse_button : 'front_image_select',
			container: 'front_image_container',
			max_file_size : '2mb',
			resize: { width: 240, height: 240, quality: 90 },
			url : '<?php echo admin_url( 'admin-ajax.php?action=deal_attach_file' ); ?>',
			filters : [
				{title : "Image files", extensions : "jpg,gif,png,jpeg"}
			]
		});
		
		uploader.bind('FilesAdded', function(up, files) {
			for (var i in files) {
				//console.log( files );
				$('#front_image_list').html( '<span id="' + files[i].id + '">' + files[i].name + ' (' + plupload.formatSize(files[i].size) + ') <b></b></span>' );
			}
			
		});
		
		uploader.bind('FileUploaded', function(up, file, info) {
			//console.log('[FileUploaded] File:', file, "Info:", info);
			
			var ret = $.parseJSON( info['response'] );
			
			$( '#front_image_list' ).html( '<img src="'+ ret.attach_url +'" style="width:50px; height:50px" />' );
			$( '#front_image_id' ).val( ret.attach_url );
		});
		
		uploader.bind( 'QueueChanged', function( up ){
			uploader.start();
		});
		
		uploader.bind( 'error', function( up, args ){
			$( '#front_image_error' ).html( args.message );
		});
		
		uploader.init();
		
		// Deal image upload
		var DealUploader = new plupload.Uploader({
			runtimes : 'gears,html5,flash,silverlight,browserplus',
			browse_button : 'deal_image_select',
			container: 'deal_image_container',
			max_file_size : '2mb',
			//resize: { width: 1024, height: 414, quality: 90 },
			url : '<?php echo admin_url( 'admin-ajax.php?action=deal_attach_file' ); ?>',
			filters : [
				{title : "Image files", extensions : "jpg,gif,png,jpeg"}
			]
		});
		
		DealUploader.bind('FilesAdded', function(up, files) {
			for (var i in files) {
				//console.log( files );
				$('#deal_image_list').html( '<span id="' + files[i].id + '">' + files[i].name + ' (' + plupload.formatSize(files[i].size) + ') <b></b></span>' );
			}
		});
		
		DealUploader.bind('FileUploaded', function(up, file, info) {
			//console.log('[FileUploaded] File:', file, "Info:", info);
			
			var ret = $.parseJSON( info['response'] );
			
			$( '#deal_image_list' ).html( '<img src="'+ ret.attach_url +'" style="width:50px; height:50px" />' );
			$( '#deal_image_id' ).val( ret.attach_id );
		});
		
		DealUploader.bind( 'QueueChanged', function( up ){
			DealUploader.start();
		});
		
		DealUploader.bind( 'error', function( up, args ){
			$( '#deal_image_error' ).html( args.message );
		});
		
		DealUploader.init();
	});
	
	
 </script>
 
<?php get_footer(); ?>		
		
		