<html>
<head>
	<title>Welcome to Miles</title>
	<link rel='stylesheet' href='sass/all.css' type='text/css'>
	<link rel='stylesheet' href='sass/mbf.css' type='text/css'>

	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="js/mbf.js"></script>
	<script src="js/jquery.validate.js"></script>

	<script>

	$().ready(function() {
		$("#login").validate({
			rules: {
				login_email: {
					required: true,
					email: true
				},
				login_pass: {
					required: true,
					minlength: 5
				}
			},
			messages: {
				login_pass: {
					required: "Please provide a password",
					minlength: "Your password must be at least 5 characters long"
				},
				login_name: "Please enter a valid email address"
			}
		});
	});
	</script>
	<style>
		.error {
			color:red;
		}
	</style>
</head>
<body>
	<div class="welcome_c">
		<div class="welcome_tbl">
			<div class="welcome_cell">

				<div class="login_area">
					<div class="panel panel-default">
						<div class="panel-body">
							<span class="miles_logo_login">
							</span>
							<form method='post' action='php/login.php' id="login" name="login" class="form-horizontal">
								<div class="form-group">
									<label class="g-sm-4 control-label">User Name</label>
									<div class="g-sm-8">
										<input type="text" id="login_email" name="login_email" class="form-control"/>
									</div>
								</div>
								<div class="form-group">
									<label class="g-sm-4 control-label">Password</label>
									<div class="g-sm-8">
										<input type="password" id="login_pass" name="login_pass" class="form-control"/>
									</div>
								</div>
								<input type="submit" name="login_button" id="login_button" value="Log in" class="btn btn-success"/>
							</form>
						</div>

					</div>
				</div>

			</div>
		</div>
	</div>


	
	<script type='text/javascript'>
	function updateSize(){
				// Get the dimensions of the viewport
				var width = $(window).width();
				var height = $(window).height();
				
				//var tambah_lokasi = $('.tambah_lokasi').height();
				$('.welcome_c').height(height);
			};
			$(document).ready(updateSize);
			$(window).resize(updateSize); 
			
			</script>
		</body>
		</html>