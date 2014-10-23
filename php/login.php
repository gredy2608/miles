<?php
	session_start();
	$email = $_POST['login_email'];
	$password = md5($_POST['login_pass']);
	$service_url = 'http://milesyourday.com/service/accLogin/'.$email.'/'.$password;
	$curl = curl_init($service_url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$curl_response = curl_exec($curl);
	if ($curl_response === false) {
		$info = curl_getinfo($curl);
		curl_close($curl);
		die('error occured during curl exec. Additioanl info: ' . var_export($info));
	}
	curl_close($curl);
	$decoded = json_decode($curl_response);
	
	if (isset($decoded->status) && strcmp($decoded->status,'reject')==0) {
		//die('error occured: ' . $decoded->response->errormessage);
		header("Location:../index.php");
	}
	else if(strcmp($decoded->status,'accept')==0){
		$_SESSION['email'] = $_POST['login_email'];
		header("Location:../admin_panel.php");
	}
	else{
		header("Location:../");
	}
?>
