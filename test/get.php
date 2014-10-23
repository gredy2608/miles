<?php
	$service_url = 'https://partner.path.com/1/user/self';
	$curl = curl_init($service_url);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'Authorization: Bearer 34760151eeec7519e48e3367cc0ed5602fd505d8' // Bearer <Access Token>
    ));
	curl_setopt($curl, CURLOPT_HTTPGET, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$curl_response = curl_exec($curl);
	curl_close($curl);
	$decoded = json_decode($curl_response);
	echo $curl_response;
?>