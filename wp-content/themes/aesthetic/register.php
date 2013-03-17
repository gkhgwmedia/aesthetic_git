<?php
/**
 * Template Name: Register
 */
 get_header();
?>
	<script>
		jQuery(document).ready(function($){
			jQuery( '#register' ).validate({
				errorElement: 'span',
				rules:{
					first_name: { required: true, minlength: 5 },
					last_name: { required: true, minlength: 5 },
					email: { 
						required: true, 
						email: true,
						remote: {
							url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
							type: 'post',
							data: { email: function(){ return $('#email').val() }, action: 'email_check' }
						}
					},
					password: { required: true, minlength: 5 },
					confirm_password: { required: true, equalTo: '#password' },
					contact: { required: true, digits: true, minlength: 10 },
					address_one: { required: true, minlength: 10, maxlength: 100 },
					address_two: { required: true, minlength: 10, maxlength: 100 },
					pin: { required: true, digits: true, minlength: 3, maxlength: 6 },
					emirate: { required: true }
				},
				messages: {
					email:{
						remote: 'An Email ID already exists'
					}
				}
			});
		});
	</script>
	<div id="static_page">
       <?php get_sidebar( 'page' ); ?>
       <div class="content clsFloatRight">
	   		<h1><?php the_title(); ?></h1>
				<p>Connect your Facebook account to sign up to Aesthetic Today.</p>
				<!--<a href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/fb_connect.png" /></a>-->
				<?php if( function_exists( 'aesthetic_fb_login' ) ) aesthetic_fb_login( get_page_link( 17 ) ); ?>
			<div class="divider">&nbsp;</div>	
			<p>or, Sign up with your Aesthetic Today account:</p>
			<div class="register_form">
				<form name="register" id="register" action="" method="post">
					<div class="clsFormField">
						<div class="label clsFloatLeft">First Name <span class="red">*</span>:</div>
						 <div class="clsInput clsFloatRight">
							<input type="text" id="first_name" name="first_name" />
						</div>
					</div>
					<div class="clear"></div>
					<div class="clsFormField">
						<div class="label clsFloatLeft">Last Name <span class="red">*</span>:</div>
						 <div class="clsInput clsFloatRight">
							<input type="text" id="last_name" name="last_name" />
						</div>
					</div>
					<div class="clear"></div>
					
					<div class="clsFormField">
						<div class="label clsFloatLeft">Email <span class="red">*</span>:</div>
						 <div class="clsInput clsFloatRight">
							<input type="text" id="email" name="email" />
						</div>
					</div>
					<div class="clear"></div>
					
					<div class="clsFormField">
						<div class="label clsFloatLeft">Password <span class="red">*</span>:</div>
						 <div class="clsInput clsFloatRight">
							<input type="password" id="password" name="password" />
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
						<div class="label clsFloatLeft">Contact Number <span class="red">*</span>:</div>
						 <div class="clsInput clsFloatRight">
							<input type="text" id="contact" name="contact" />
						</div>
					</div>
					<div class="clear"></div>
					
					<!--<div class="clsFormField">
						<div class="label clsFloatLeft">Fax Number:</div>
						 <div class="clsInput clsFloatRight">
							<input type="text" id="fax" name="fax" />
						</div>
					</div>
					<div class="clear"></div>
					
					<div class="clsFormField">
						<div class="label clsFloatLeft">&nbsp;</div>
						 <div class="clsInput clsFloatLeft">
							<input type="radio" value="Residential" name="address_type" checked="checked"/> Home
							<input type="radio" value="Office" name="address_type" />Business
						</div>
					</div>
					<div class="clear"></div>-->
					
					
					<div class="clsFormField">
						<div class="label clsFloatLeft">Address one <span class="red">*</span>:</div>
						 <div class="clsInput clsFloatRight">
							<input type="text" id="address_one" name="address_one" />
						</div>
					</div>
					<div class="clear"></div>
					
					<div class="clsFormField">
						<div class="label clsFloatLeft">Address two <span class="red">*</span>:</div>
						 <div class="clsInput clsFloatRight">
							<input type="text" id="address_two" name="address_two" />
						</div>
					</div>
					<div class="clear"></div>
					
					<div class="clsFormField">
						<div class="label clsFloatLeft">PO Box <span class="red">*</span>:</div>
						 <div class="clsInput clsFloatRight">
							<input type="text" id="pin" name="pin" />
						</div>
					</div>
					<div class="clear"></div>
					
					<!--<div class="clsFormField">
						<div class="label clsFloatLeft">Location <span class="red">*</span>:</div>
						 <div class="clsInput clsFloatRight">
							<input type="text" id="txtLastName" />
						</div>
					</div>
					<div class="clear"></div>-->
					
					<div class="clsFormField">
						<div class="label clsFloatLeft">Emirate <span class="red">*</span>:</div>
						 <div class="clsInput clsFloatRight">
							  <select name="emirate" id="emirate" class="clsSelect">
									<option value="">Select Emirate</option>
								<?php
									global $aes_emirates;
									foreach( $aes_emirates as $id => $name ) :
								?>
									<option value='<?php echo $id; ?>'><?php echo ucfirst( $name ); ?></option>
								<?php endforeach; ?>
									
							 </select>
						</div>
					</div>
					<div class="clear"></div>
					
					<div class="clsFormField">
						<div class="label clsFloatLeft">As <span class="red">*</span>:</div>
						 <div class="clsInput clsFloatRight">
						 	<input type="radio" id="check_subscriber" name="role" value="subscriber" checked="checked" /> Buyer
							<input type="radio" id="check_merchant" name="role" value="author" /> Merchant
						</div>
					</div>
					<div class="clear"></div>
					
					<div class="clsFormField">
					<br />
						<div class="label clsFloatLeft">&nbsp;</div>
						 <div class="clsInput clsFloatLeft">
						 	<?php wp_nonce_field( 'aes_register_action', 'aes_register' ); ?>
							<input type="submit" id="reg_submit" name="reg_submit" value="Submit" class="btnSubmit"  />
						</div>
					</div>
					<div class="clear"></div>
					<div class="clsFormField">
					<br />
						<div class="label clsFloatLeft">&nbsp;</div>
						 <div class="clsInput clsFloatLeft">
							<a href="<?php echo get_page_link( 145 ); ?>">login</a> | <a href="<?php echo wp_lostpassword_url(); ?>">forgot password?</a>
						</div>
					</div>
					<div class="clear"></div>
					<div class="clear"></div>
				</form>
			</div>	
		</div>
    <div class="clear"></div>
 </div>
<?php get_footer(); ?>		
		
		