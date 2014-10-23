<?php
	$code = $_GET['code'];
	$service_url = 'https://partner.path.com/oauth2/access_token';
	$curl = curl_init($service_url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, 'grant_type=authorization_code&client_id=e84b2baa827a2a1782b86ff7f66fc915582fa598&client_secret=8e469b52731b032b76e7b54b89c0304e0de5b19a&code='.$code);
	$curl_response = curl_exec($curl);
	curl_close($curl);
	$decoded = json_decode($curl_response);
	echo $curl_response;
	

?>