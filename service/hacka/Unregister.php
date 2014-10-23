<?php
 
	// response json
	$json = array();
	 include("../connect.php");
	/**
	 * Registering a user device
	 * Store reg id in users table
	 */
	if (isset($_POST["profId"]) && isset($_POST["regId"])) 
	{
		$profId = $_POST["profId"];
		$gcm_regid = $_POST["regId"]; // GCM Registration ID
		// Store user details in db
		include_once 'DB_Function.php';
		include_once 'GCM.php';
	 
		$db = new DB_Functions();
		$gcm = new GCM();
	 
		$res = $db->deleteUser($link,$profId, $gcm_regid);
	 
		$registatoin_ids = array($gcm_regid);
		$message = array("unregis" => "sukses unregister");
	 
		$result = $gcm->send_notification($registatoin_ids, $message);
	 
		echo $result;
	} else {
		// user details missing
		echo 'gagal';
	}
?>