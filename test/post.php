<?php
	$service_url = 'https://partner.path.com/1/moment/thought';
	$curl = curl_init($service_url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'Authorization: Bearer 70f3f75860456188de0baec35cf25a6bf4bca67f',
		'Content-Type: application/json'
    ));
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode(array('thought'=>'Test','private'=>true)));
	$curl_response = curl_exec($curl);
	curl_close($curl);
	$decoded = json_decode($curl_response);
	echo $curl_response; 
?>
