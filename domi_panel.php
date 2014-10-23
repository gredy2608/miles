<!DOCTYPE html>
<html lang='en'>
<head>
	<meta charset='UTF-8' />
	<title>Add Location</title>
	<link rel='stylesheet' href='css/style.css' />
	<link rel='stylesheet' href='css/slider/jslider.css' type='text/css'>
	<link rel='stylesheet' href='css/slider/jslider.blue.css' type='text/css'>
	<link rel='stylesheet' href='css/slider/jslider.plastic.css' type='text/css'>
	<link rel='stylesheet' href='css/slider/jslider.round.css' type='text/css'>
	<link rel='stylesheet' href='css/slider/jslider.round.plastic.css' type='text/css'>
		
	<link rel='stylesheet' href='sass/all.css' type='text/css'>
	
	<meta name='viewport' content='initial-scale=1.0, user-scalable=no'>
    <meta charset='utf-8'>
    <style>
      html, body{
        height: 100%;
        margin: 0px;
        padding: 0px
      }
	  #map-canvas {
		/*height: 50%;*/
		/*width:75%;*/
		margin-left:auto;
		margin-right:auto;
	  }
    </style>
	<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
	<script src='https://maps.googleapis.com/maps/api/js?v=3.exp'></script>
   		<!--Slider-->
	<script type='text/javascript' src='js/slider/jshashtable-2.1_src.js'></script>
	<script type='text/javascript' src='js/slider/jquery.numberformatter-1.2.3.js'></script>
	<script type='text/javascript' src='js/slider/tmpl.js'></script>
	<script type='text/javascript' src='js/slider/jquery.dependClass-0.1.js'></script>
	<script type='text/javascript' src='js/slider/draggable-0.1.js'></script>
	<script type='text/javascript' src='js/slider/jquery.slider.js'></script>
    <script type="text/javascript">
			
			function updateSize(){
				// Get the dimensions of the viewport
				var width = $(window).width();
				var height = $(window).height();
				var navHeight = $('#nav_sec').height();
				
					
					
				$('.admin_sidepanel').height(height - $('.head_admin_panel').height());
				$('.function_panel').height(height - $('.head_admin_panel').height());
				$('.function_panel').width(width - $('.admin_sidepanel').width());
			};
			
			$(document).ready(updateSize);
			$(window).resize(updateSize);
			
			
		</script>
		
<link rel="stylesheet" type="text/css" href="js/datetimepicker/jquery.datetimepicker.css"/ >
<script src="js/datetimepicker/jquery.datetimepicker.js"></script>
   
		
</head> 
<body>
	<section class="head_admin_panel">
		<span class="admin_name">
			Welcome, Administrator
		</span>
	</section>
	<section style="position: relative;">
		<div class="admin_sidepanel">
			<ul>
				<li>
					<a href="#" id='add_location_nav'>
					Add Location
					</a>
				</li>
				<li>
					<a href="#" id='add_event_nav'>
					Add Event
					</a>
				</li>
				<li>
					<a href="#" id='edit_recomendation_nav'>
					Edit Recomendation
					</a>
				</li>
				<li>
					<a href="#" id='view_user_nav'>
					View Users
					</a>
				</li>
				
			</ul>
		</div>
		<div class="function_panel">
		
			<!-- View Users - START-->
			<?php
				include('add_event.php');
			?>
			<!-- View Users - END-->
			
		</div>
		
	</section>
	
	<script>
		$('#view_user_nav').click(function(){
			//get all user ajax
			/*$.ajax({
			type: 'GET',
			contentType: 'application/json',
			url: 'http://milesyourday.com/service/user,
			dataType: 'json',
			success: function(data){
				
			},
			error: function(jqXHR, textStatus, errorThrown){
				alert(errorThrown);
			}
			});*/
			
			//success = arrange innerHTML in function_panel or c_12
		});
			
	</script>
	
</body>
<!-- this should go after your </body> -->

</html>