<?php
/**
 * Template Name: Calendar
 */
get_header();
?>
	<link href='<?php echo get_template_directory_uri(); ?>/fullcalendar/fullcalendar.css' rel='stylesheet' />
	<link href='<?php echo get_template_directory_uri(); ?>/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
	<link href='http://code.jquery.com/ui/1.10.2/themes/black-tie/jquery-ui.css' rel='stylesheet' />
	
	<style>
		.ui-state-highlight{
			background:#BFA75A;
		}
		.fc-event{
			background-color:#1C1C20;
			color:#CBCBCB;
		}
	</style>
	
	<script src="<?php echo get_template_directory_uri(); ?>/fullcalendar/fullcalendar.js" ></script>
	
	<script>
		jQuery(document).ready(function($) {
	
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		
		$('#calendar').fullCalendar({
			editable: false,
			theme: true,
			events: "<?php echo admin_url( 'admin-ajax.php?action=get_calendar_deals' ); ?>"
		});
		
	});
	</script>
	
	<div id="static_page">
       <div class="content" style="width:auto; padding:10px 0 0 0" >
			<div id='calendar'></div>
	   </div>
	   <div class="clear"></div>
    </div>
<?php get_footer(); ?>		
		
		