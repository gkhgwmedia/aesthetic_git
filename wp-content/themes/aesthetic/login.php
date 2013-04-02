<?php
/**
 * Template Name: Login
 */
 get_header();
?>
	<script>
		jQuery(document).ready(function($){
			jQuery( '#login_form' ).validate({
				errorElement: 'span',
				rules:{
					user_name: { required: true, email: true },
					user_pass: { required: true, minlength: 5 },
				}
			});
		});
	</script>
	<div id="static_page">
       <?php get_sidebar( 'page' ); ?>
       <div class="content clsFloatRight">
	   		<h1><?php the_title(); ?></h1>
				<p>Login with your Facebook Account:</p>

				<p>Connect your Facebook account to sign in to Aesthetic Today.</p>
				<?php if( function_exists( 'aesthetic_fb_login' ) ) aesthetic_fb_login( get_page_link( 17 ), 'sign_in' ); ?>
			<div class="divider">&nbsp;</div>	
			<p>or, Sign in with your Aesthetic Today account:</p>
			<div class="register_form">
				<form name="login_form" id="login_form" action="" method="post">
					<div class="clsFormField">
						<div class="label clsFloatLeft">Email <span class="red">*</span>:</div>
						 <div class="clsInput clsFloatRight">
							<input type="text" name="user_name" id="user_name" />
						</div>
					</div>
					<div class="clear"></div>
					
					<div class="clsFormField">
						<div class="label clsFloatLeft">Password <span class="red">*</span>:</div>
						 <div class="clsInput clsFloatRight">
							<input type="password" name="user_pass" id="user_pass" />
						</div>
					</div>
					<div class="clear"></div>
					
					<div class="clsFormField">
						<div class="label clsFloatLeft">&nbsp;</div>
						 <div class="clsInput clsFloatLeft">
						 	<?php wp_nonce_field( 'aes_login_action', 'aes_login' ); ?>
							<input type="submit" name="login_submit" id="login_submit" value="Submit" class="btnSubmit"  />
							<div class="login-link"><a href="<?php echo get_page_link( 150 ); ?>">Register</a> | <a href="<?php echo wp_lostpassword_url(); ?>">Forgot Password?</a></div>
						</div>
					</div>
					<div class="clear"></div>
				</form>
			<div class="clear"></div>
		</div>	
    </div>
    <div class="clear"></div>
 </div>
<?php get_footer(); ?>		
		
		