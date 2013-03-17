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
			<?php if( $_POST['deal_submit'] ){ ?>
				tab = 'add_deal';
			<?php } ?>
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
			
			// Add a deal validation
			jQuery( '#add_deal_form' ).validate({
				errorElement: 'span',
				rules:{
					title : { required: true, minlength: 5 },
					price: { required: true, number: true },
					special_price: { required: true, dealPrice: '#price' },
					end_time: { required: true }
				}/*,
				submitHandler: function(){
					alert( tinymce.get( 'dealdescription' ).getContent() );
					$.post( '<?php echo admin_url( 'admin-ajax.php' ); ?>', $('#add_deal_form').serialize(), function(data){
						alert( data );
					} );
				}*/
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
		

	</script>
	<div id="static_page">
       <div class ="left_side_menu clsFloatLeft">
	   		<ul class="tabs">
				<li class="active"><a href="javascript: showHide( 'profile' )" class="profile_tab">ACCOUNT OWNER INFORMATION</a></li>
				<li><a href="javascript: showHide( 'address' )" class="address_tab">CHANGE ADDRESS</a></li>
				<li><a href="javascript: showHide( 'security' )" class="security_tab">ACCOUNT SECURITY SETTINGS</a></li>
				<li><a href="javascript: showHide( 'add_deal' )" class="add_deal_tab">Add a Deal</a></li>
				<li><a href="javascript: showHide( 'mydeal' )" class="mydeal_tab">My Deals</a></li>
				<li><a href="javascript: showHide( 'wallet' )" class="wallet_tab">MY WALLET</a></li>
				<li><a href="javascript: showHide( 'order' )" class="order_tab">ORDER HISTORY</a></li>
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
				
				<!-- Add a deal tab -->
				<div id="add_deal" class="formfield">
					<form name="add_deal_form" id="add_deal_form" action="" method="post" enctype="multipart/form-data">
						<input type="hidden" name="action" id="deal_action" value="add_deal" />
						<?php 
							$title = $_POST['title'];
							$dealdescription = $_POST['dealdescription'];
							$cat = $_POST['cat'];
							$price = $_POST['price'];
							$special_price = $_POST['special_price'];
							$end_time = $_POST['end_time'];
						?>
						<h1>Add a Deal</h1>
						<?php if( strlen( $deal_error ) ) : ?>
						<div class="err-msg"><?php echo $deal_error; ?></div>
						<?php endif; ?>
						<?php if( strlen( $deal_success ) ) : ?>
						<div class="err-msg"><?php echo $deal_success; ?></div>
						<?php endif; ?>
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
										'textarea_rows' => '9'
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
							<div class="label clsFloatLeft">Image :</div>
							 <div id="file_container" class="clsInput clsFloatRight" style="height:200px">
								<!--<input type="file" name="deal_image" id="deal_image" />-->
								<div id="filelist"></div>
								<a id="selectfile" href="javascript:;">Deal Image</a>
								<a id="filesubmit" href="javascript:;">File submit</a>
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
							</div>
						</div>
						<div class="clear"></div>
						
					</form>
				</div>
				
				<!-- My deal tab -->
				<div id="mydeal" class="formfield">
					<h1>My Deals</h1>
					Coming soon...
				</div>
				
				<!-- Wallet tab -->
				<div id="wallet" class="formfield">
					<h1>My Wallet</h1>
					Coming soon...
				</div>
				
				<!-- Orders tab -->
				<div id="order" class="formfield">
					<h1>Order History</h1>
					Coming soon...
				</div>
				
			</div>	
		</div>
    <div class="clear"></div>
 </div>
 
 <!-- plupload script -->
 <script>
 	jQuery(document).ready(function($){
		var uploader = new plupload.Uploader({
			runtimes : 'gears,html5,flash,silverlight,browserplus',
			browse_button : 'selectfile',
			container: 'file_container',
			max_file_size : '2mb',
			url : '<?php echo admin_url( 'admin-ajax.php?action=deal_attach_file' ); ?>',
			flash_swf_url : '../js/plupload.flash.swf',
			silverlight_xap_url : '../js/plupload.silverlight.xap',
			filters : [
				{title : "Image files", extensions : "jpg,gif,png"},
				{title : "Zip files", extensions : "zip"}
			]
		});
		
		uploader.bind('FilesAdded', function(up, files) {
			for (var i in files) {
				$('#filelist').html( '<div id="' + files[i].id + '">' + files[i].name + ' (' + plupload.formatSize(files[i].size) + ') <b></b></div>' );
			}
		});
		
		uploader.bind('FileUploaded', function(up, file, info) {
			console.log('[FileUploaded] File:', file, "Info:", info);
			
			var ret = $.parseJSON( info['response'] );
			
			$( '#filelist' ).html( '<img src="'+ ret.attach_url +'" style="width:100px; height:100px" />' );
		});
		
		$('#filesubmit').click(function() {
			uploader.start();
			return false;
		});			
		uploader.init();
	});
 </script>
 
<?php get_footer(); ?>		
		
		