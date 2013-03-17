<?php include("header.php");?>
	<div id="static_page">
       <div class ="left_side_menu clsFloatLeft">
	   		<ul>
				<li><a href="accountinfo.php">ACCOUNT OWNER INFORMATION</a></li>
				<li><a href="change_address.php">CHANGE ADDRESS</a></li>
				<li><a href="changepwd.php">ACCOUNT SECURITY SETTINGS</a></li>
				<li><a href="orderhistory.php">ORDER HISTORY</a></li>
			</ul>
	   </div>
       <div class="content clsFloatRight">
	   		<h1>Account Securtity Settings</h1>
			<div class="register_form">
				
				<div class="clsFormField">
					<div class="label clsFloatLeft">Old Password <span class="red">*</span>:</div>
					 <div class="clsInput clsFloatRight">
						<input type="text" id="oldpassword" />
					</div>
				</div>
				<div class="clear"></div>
				
				<div class="clsFormField">
					<div class="label clsFloatLeft">New Password <span class="red">*</span>:</div>
					 <div class="clsInput clsFloatRight">
						<input type="text" id="newpassword" />
					</div>
				</div>
				<div class="clear"></div>
				
				<div class="clsFormField">
					<div class="label clsFloatLeft">Confirm Password <span class="red">*</span>:</div>
					 <div class="clsInput clsFloatRight">
						<input type="text" id="cnfpassword" />
					</div>
				</div>
				<div class="clear"></div>
				
				<div class="clsFormField">
				<br />
					<div class="label clsFloatLeft">&nbsp;</div>
					 <div class="clsInput clsFloatLeft">
						<input type="submit" id="reg_submit" value="Save" class="btnSubmit"  />
					</div>
				</div>
				<div class="clear"></div>
		</div>	
    </div>
    <div class="clear"></div>
 </div>
<?php include("footer.php");?>		
		
		