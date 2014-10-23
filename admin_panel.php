<?php
	session_start();
	if(!isset($_SESSION['email'])){
		header("Location:../");
	}
?>

<!DOCTYPE html>
<html lang='en'>
<head>
	<meta charset='UTF-8' />
	<title>Miles Panel</title>
	<link rel='stylesheet' href='css/style.css' />
	<link rel='stylesheet' href='sass/mbf.css' />
	<link rel='stylesheet' href='css/slider/jslider.css' type='text/css'>
	<link rel='stylesheet' href='css/slider/jslider.blue.css' type='text/css'>
	<link rel='stylesheet' href='css/slider/jslider.plastic.css' type='text/css'>
	<link rel='stylesheet' href='css/slider/jslider.round.css' type='text/css'>
	<link rel='stylesheet' href='css/slider/jslider.round.plastic.css' type='text/css'>
	<link rel='stylesheet' href='css/jPages.css' type='text/css'>

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
	ul {
		list-style-type: none;
		padding-left: 0px;
	}

	ul *{
		list-style-type: none;
	}

	</style>
	<script type='text/javascript' src='js/jquery-1.11.1.min.js'></script>
	<script type='text/javascript' src='js/jquery-migrate-1.2.1.min.js'></script>
	<script src="js/mbf.js"></script>
	<script src='https://maps.googleapis.com/maps/api/js'></script><!-- /js?v=3.exp -->
	<!--Slider-->
	<script type='text/javascript' src='js/slider/jshashtable-2.1_src.js'></script>
	<script type='text/javascript' src='js/slider/jquery.numberformatter-1.2.3.js'></script>
	<script type='text/javascript' src='js/slider/tmpl.js'></script>
	<script type='text/javascript' src='js/slider/jquery.dependClass-0.1.js'></script>
	<script type='text/javascript' src='js/slider/draggable-0.1.js'></script>
	<script type='text/javascript' src='js/slider/jquery.slider.js'></script>





</head> 
<body>
	<section class="head_admin_panel">
		<span class="admin_name">
			Welcome, Administrator <a href="logout.php" class="btn btn-primary">Log Out</a>
		</span>
	</section>
	<section style="position: relative;">
		<div class="admin_sidepanel">
			<ul class="tooltip-x">
				<li data-toggle="tooltip" data-original-title="Add Location">
					<a href="javascript:void(0)" id='add_location_nav' >
						<!--Add Location-->
						<span class="glyphicon glyphicon-plus" style="color: #fff;"></span>
					</a>
				</li>
				<li data-toggle="tooltip" data-original-title="Add Event">
					<a href="javascript:void(0)" id='add_event_nav'>
						<!--Add Event-->
						<span class="glyphicon glyphicon-glass" style="color: #fff;"></span>							
					</a>
				</li>
				<li data-toggle="tooltip" data-original-title="Edit Recommendation">
					<a href="javascript:void(0)" id='edit_recomendation_nav'>
						<!--Edit Recommendation-->
						<span class="glyphicon glyphicon-thumbs-up" style="color: #fff;"></span>	
					</a>
				</li>
				<li data-toggle="tooltip" data-original-title="View Users">
					<a href="javascript:void(0)" id='view_user_nav'>
						<!--View Users-->
						<span class="glyphicon glyphicon-user" style="color: #fff;"></span>	
					</a>
				</li>

				<script type="text/javascript"> 
				$(document).ready(function(){
					$(".tooltip-x li").tooltip({
						placement : 'right'
					});
				});
				</script>

			</ul>
		</div>
		<div class="function_panel">

			<!-- View Users - START-->

			<!-- View Users - END-->

		</div>

	</section>


	<script type='text/javascript' src='js/admin_panel/view_user.js'></script>
	<script type='text/javascript' src='js/admin_panel/edit_recommendation.js'></script>
	<script type='text/javascript' src='js/admin_panel/add_event.js'></script>
	<script type='text/javascript' src='js/admin_panel/add_location.js'></script>



</body>

<!-- this should go after your </body> -->
<link rel="stylesheet" type="text/css" href="/js/datetimepicker/jquery.datetimepicker.css"/ >
<!--<script src="/js/datetimepicker/jquery.js"></script>-->
<script src="/js/datetimepicker/jquery.datetimepicker.js"></script>
<script type='text/javascript' src='js/jPages.js'></script>

<script type="text/javascript">
var id="";
function updateSize(){
			// Get the dimensions of the viewport
			var width = $(window).width();
			var height = $(window).height();
			var navHeight = $('#nav_sec').height();
			


			$('.admin_sidepanel').height(height - $('.head_admin_panel').height());
			$('.function_panel').height(height - $('.head_admin_panel').height());
			$('.function_panel').width(width - $('.admin_sidepanel').width());

			$('.function_panel').css('margin-left',$('.admin_sidepanel').width());
		};
		
		$(document).ready(updateSize);
		$(window).resize(updateSize);


		</script>

		</html>