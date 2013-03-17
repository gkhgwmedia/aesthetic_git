<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Friends Popup</title>
	<link type="text/css" href="style.css" media="all" rel="stylesheet"/>
	<style>
		body{
			background:url(images/deals_bg.png) repeat !important;
		}
	</style>
	
</head>
<body>
    <div id="popup_friend">
		<h1>FILL OUT THE FORM BELOW</h1>
		<div class="form">
			<div class="clsFormField">
				<label>TO:</label>
				<input type="text" id="txtToName" />
			</div>
			<div class="clear"></div>
			<div class="clsFormField">
				<label>From:</label>
				<input type="text" id="txtFromName" />
			</div>
			<div class="clsFormField">
				<label>Message:</label>
				<span>(Maximum of 330 characters) - Optional </span>
				<textarea id="textmsg"></textarea>
			</div>
			<div class="clear"></div>
		
		<h1>DELIVERY METHOD</h1>
			<div class="clsFormField">
				<input type="radio" />
				<label>Email It To: </label>
				<input type="text" value="" class="emailsmall" />
			</div>
			<div class="clear"></div>
			<div class="clsFormField">
				<input type="radio" />
				<label>I'LL PRINT IT MYSELF</label>
			</div>
			<div class="clear"></div>	
			<div class="clsFormField">
				<input type="submit" class="btnSubmit"  value="Save"/>&nbsp;&nbsp;<input type="button" class="btnSubmit" value="Cancel" />	</p>
			</div>
			<div class="clear"></div>	
		</div>			
			

    </div>    
</body>
</html>