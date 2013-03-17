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
	   		<h1>Change Address</h1>

			<div class="register_form">
				<div class="clsFormField">
					<div class="label clsFloatLeft">&nbsp;</div>
					 <div class="clsInput clsFloatLeft">
						<input type="radio" value="Residential" name="address_type" checked="checked"/> Home
				        <input type="radio" value="Office" name="address_type" />Business
					</div>
				</div>
				<div class="clear"></div>
				
				
				<div class="clsFormField">
					<div class="label clsFloatLeft">Address <span class="red">*</span>:</div>
					 <div class="clsInput clsFloatRight">
						<input type="text" id="txtLastName" />
					</div>
				</div>
				<div class="clear"></div>
				
				<div class="clsFormField">
					<div class="label clsFloatLeft">PO Box:</div>
					 <div class="clsInput clsFloatRight">
						<input type="text" id="txtLastName" />
					</div>
				</div>
				<div class="clear"></div>
				
				<div class="clsFormField">
					<div class="label clsFloatLeft">Location <span class="red">*</span>:</div>
					 <div class="clsInput clsFloatRight">
						<input type="text" id="txtLastName" />
					</div>
				</div>
				<div class="clear"></div>
				
				<div class="clsFormField">
					<div class="label clsFloatLeft">Emirate <span class="red">*</span>:</div>
					 <div class="clsInput clsFloatRight">
						  <select name="emirate" id="emirate" class="clsSelect">
						        <option value="">Select Emirate</option>
								<option value='4'>Abu Dhabi</option>
								<option value='5'>Ajman</option>
								<option value='2'>Dubai</option>
								<option value='7'>Fujairah</option>
								<option value='8'>Ras Al Kaimah</option>
								<option value='3'>Sharjah</option>
								<option value='6'>Umm Al Quwain</option>
						 </select>
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
		
		