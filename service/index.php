<?php
//setting slim framework
require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

include("connect.php");
include("class/account.php");
include("class/event.php");
include("class/place.php");
include("class/timeline.php");
include("class/user.php");

//tes
//account
	$app->get('/accLogin/:email/:password', function($email,$password) use ($link){
		$account = new Account();
		echo $account->login($link,$email,$password);
	});
	
	$app->get('/accFbLogin/:email/:password', function($email,$key) use ($link){
		$account = new Account();
		echo $account->loginFromFacebook($link,$email,$key);
	});	
	
	$app->get('/accExist/:email', function($email) use ($link){
		$account = new Account();
		echo $account->checkExist($link,$email);
	});
	
	$app->post('/accAdmin',function () use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$newAccount['username'] = mysqli_escape_string($link,$input['email']);
		$newAccount['password'] = mysqli_escape_string($link,$input['password']);
		$newAccount['role'] = 'admin';
		$newAccount['active'] = 1;
		$account = new Account();
		echo $account->addAccount($link,$newAccount);
	});
	
	$app->delete('/account/:id',function($id) use ($link) {
        $account = new Account();
		echo $account->deleteAccount($link,$id);
	});
	
	//newcode
	$app->put('/account',function() use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$inputId = $input['id'];
		$inputActive = $input['active'];
		
		$account = new Account();
		
		echo $account->changeActive($link,$inputId,$inputActive);
	});
	//endnewcode
    
	$app->put('/changePassword',function() use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$profile_id = $input['profile_id'];
		$oldpassword = $input['oldpassword'];
		$newpassword = $input['newpassword'];
		
		$account = new Account();
		
		$respond = $account->changePassword($link,$profile_id,$oldpassword,$newpassword);
		if($respond==0)
			echo '{"status":"success"}';
		else
			echo '{"status":"error"}';
	});
	
	$app->put('/forgotPassword',function() use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$profile_id = $input['profile_id'];
		
		$account = new Account();
		
		$respond = $account->forgotPassword($link,$profile_id);
		if($respond)
			
			echo '{"status":"success"}';
		else
			echo '{"status":"error"}';
	});
//end of account

//user
	$app->post('/user',function () use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		//account
		$account = new Account();
		$inputAccount['username'] = $input['username'];
		$inputAccount['password'] = $input['password'];
		$inputAccount['role'] = "user";
		$inputAccount['active'] = 1;
		$respond = $account->addAccount($link,$inputAccount);
		if($respond=="success")
		{
			//user
			$user = new User();
			$inputUser['account_id'] = mysqli_insert_id($link);
			$inputUser['first_name'] = $input['first_name'];
			$inputUser['last_name'] = $input['last_name'];
			$inputUser['birthday'] = $input['birthday'];
			$inputUser['sex'] = $input['sex'];
			$inputUser['photo'] = $input['photo'];
			$inputUser['phone'] = $input['phone'];
			$respond = $user->addUser($link,$inputUser);
			if($respond=="success"){
				$profile_id = mysqli_insert_id($link);
				$dir = '../file_upload/user/'.$profile_id;
				mkdir($dir);
				echo '{"status":"success","id":"'.$profile_id.'"}';
			}
			else
			{
				echo $respond;
			}
		}
		else
		{
			echo $respond;
		}
		
		//setting
	});
	
	
	
	$app->get('/user/:id', function($id) use ($link){
		$user = new User();
		$respond = $user->getUserById($link,$id);
		if(is_string($respond))
		{
			echo $respond;
		}
		else
		{
			//tempel last login
			$timeline = new Timeline();
			$lastCheckIn = $timeline->getLastCheckInByUser($link,$id);
			$respond['last_check_in'] = $lastCheckIn;
			$lastPlace = $timeline->getLastCheckInPlaceByUser($link,$id);
			$respond['last_place'] = $lastPlace;
			$place = new Place();
			$respond['last_place_name'] = $place->getNameFromId($link, $lastPlace);
			echo str_replace('\\/', '/', json_encode($respond));
		}
	});
	
	$app->get('/user/:field/:value', function($field,$value) use ($link){
		if($field == "name")
		{
			$user = new User();
			$respond = $user->getUserByName($link,$value);
			if(is_string($respond))
			{
				echo $respond;
			}
			else
			{
				echo str_replace('\\/', '/', json_encode($respond));
			}
		}
	});
	
	$app->put('/user/:id',function($id) use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$user = new User();
		echo $user->updateProfile($link,$id,$input);
		
	});
	
	$app->put('/user/photo/:id',function ($id) use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		$newphoto = $input['photo'];
		$user = new User();
		$getUser = $user->getUserById($link,$id);
		//delete foto
		$dir = '../'.$getUser['photo'];
		if ($newphoto!="" && file_exists($dir) && !is_dir($dir))
		{
			unlink($dir);
		}
		$respond = $user->updatePhoto($link,$id,$newphoto);
		echo json_encode($respond);
		
	});
	
	//newcode
	//get all user --->> ke account dulu baru ke
	$app->get('/user', function() use ($link){
		$account = new Account();
		$respond = $account->getAllAccount($link);
		if(is_string($respond)){
			echo $respond;
		}
		else
		{		
			//echo $respond;
			$user = new User();	
			
			$result = array();
			$arrid = array();
			$arractive = array();
			$arrfirstname = array();
			$arrlastname = array();
			$arrphoto = array();
					
			
			$counter = 0;
				$newrow = array();
			foreach($respond as $rows)
			{
				$newuser = $user->getUserById($link,$rows['id']);					
				/*	$newrow[] = '
								"active":"'.$rows['active'].'",
								"first_name":"'.$newuser['first_name'].'",
								"last_name":"'.$newuser['last_name'].'",
								"photo":"'.$newuser['photo'].'"
								';
								*/
					$counter++;		

					$arrid[] = $rows['id'];
						//$id = $rows['id'];
					$arractive[] = $rows['active'];		
						//$active = $rows['active'];		
					$arrfirstname[] = $newuser['first_name'];
						//$firstname = $newuser['first_name'];
					$arrlastname[] = $newuser['last_name'];
						//$lastname = $newuser['last_name'];					
					$arrphoto[] = $newuser['photo'];
						//$photo = $newuser['photo'];
					
					
					/*$result[] = '
								"active":"'.$active.'",
								"first_name":"'.$firstname.'",
								"last_name":"'.$lastname.'",
								"photo":"'.$photo.'"
								';
					*/
					
					$result[] = array(
						"id" => $arrid,
						"active" => $arractive,
						"first_name" => $arrfirstname,
						"last_name" => $arrlastname,
						"photo" => $arrphoto
					);
					
			}

			//echo json_encode($result);	
			echo json_encode($result[$counter-1]);	
			
			//echo json_encode($newrow[$counter-1]);			
			//echo json_encode($newrow[$counter-1]);
		}
	});
	//endnewcode
	
//end of user

//place
	
	//newcode
	//get 15 new place dari tabel place berdasarkan create_time
	//lalu skalian ngisi ke tabel recommendation
	$app->get('/get15newplacetorecommendation', function() use ($link){				
		$place = new Place();		
		//$respond = $place->getPlace($link);
			$newrespond = $place->getNewPlace($link);
			$respond = $newrespond['value'];
		
		//$currentdate = new DateTime('now');	
		//$count = 0;
		$result = array();
			$id = array();
			$name = array();
			$address = array();
			$telp = array();			
			$photo = array();
			$feature = array();
				$type = "new";
				$ranking = 0;
		foreach($respond as $rows)
		{
			//$datetimerow = $rows['create_time']; 
			//$daterow = new DateTime($datetimerow);
			//$interval = $currentdate->diff($daterow);
			//$intervaldays = $interval->format('%a');
			//if($intervaldays < 30 ){
				//$count++;				
				$id[] = $rows['id'];
				$name[] = $rows['name'];
				$address[] = $rows['address'];
				$telp[] = $rows['telp'];				
				$photo[] = 'file_upload/place/'.$rows['name'].'-'.$rows['location'].'/'.$rows['photo'];				
				
				$category = $place->getCategoryByPlace($link,$rows['id']);
				if(!is_string($category))
				{		
					$tempfeature = array();			
					foreach($category as $categoryRows)
					{
						if($categoryRows['value']==null){
							break;
						}
						if($categoryRows['category']=="feature")
						{							
							$tempfeature[] = $categoryRows['value'];						
						}																		
					}
					if($tempfeature==null){
						$tempfeature[] = "";						
					}
				}else{
					$tempfeature = array();
					$tempfeature[] = "";
				}
				$feature[] = $tempfeature;
				
				//add 15 new place table place ke table recommendation
				$ranking++;
				$place->addRecommendation($link,$rows['id'],$type,$ranking);
			//}
			//if($count>14){
			//	break;
			//}
			
		}
		$result[] = array(
					"id" => $id,
					"name" => $name,
					"address" => $address,
					"telp" => $telp,					
					"photo" => $photo,
					"feature" => $feature
				);
		echo str_replace('\\/', '/', json_encode($result));						
	});
				
	$app->get('/get15newplace', function() use ($link){		
		$place = new Place();
		$type = "new";
		$newrespond = $place->getRecommendationByType($link,$type);		
		//echo $newrespond['status'];
		//echo $newrespond['value']['id'];
		
		if(is_string($newrespond)){ //if ($newrespond['status']=='error'){
			echo $newrespond;		//echo $newrespond;
		}else{
			$respond = $newrespond['value'];			
				$result = array();
				$position = array();
				$id = array();
				$name = array();
				$address = array();
				$telp = array();			
				$photo = array();
				$feature = array();				
			foreach($respond as $rows)
			{			
				$inputplace = $place->getPlaceFromId($link,$rows['place_id']);				
				$position[] = $rows['ranking'];
				$id[] = $inputplace['id'];
				$name[] = $inputplace['name'];
				$address[] = $inputplace['address'];
				$telp[] = $inputplace['telp'];				
				$photo[] = 'file_upload/place/'.$inputplace['name'].'-'.$inputplace['location'].'/'.$inputplace['photo'];				
				
				$category = $place->getCategoryByPlace($link,$inputplace['id']);
				if(!is_string($category))
				{		
					$tempfeature = array();			
					foreach($category as $categoryRows)
					{
						if($categoryRows['value']==null){
							break;
						}
						if($categoryRows['category']=="feature")
						{							
							$tempfeature[] = $categoryRows['value'];						
						}																		
					}
					if($tempfeature==null){
						$tempfeature[] = "";						
					}
				}else{
					$tempfeature = array();
					$tempfeature[] = "";
				}
				$feature[] = $tempfeature;						
			}
			$result[] = array(
					"position" => $position,
					"id" => $id,
					"name" => $name,
					"address" => $address,
					"telp" => $telp,					
					"photo" => $photo,
					"feature" => $feature
				);
			echo str_replace('\\/', '/', json_encode($result));
		}				
	});
				
	//isi kolom position table trending (1-15)	
	$app->get('/get15trendingplace', function() use ($link){	
		$place = new Place();		
		$type = "trending";
		$newrespond = $place->getRecommendationByType($link,$type);	
		$result = array();
			$position = array();
			$id = array();
			$name = array();
			$address = array();
			$telp = array();
			$website = array();
			$email = array();
			$rating = array();
			$day_life = array();
			$create_time = array();
			$photo = array();
			$visibility = array();
			$city = array();
				$feature = array();
		if(is_string($newrespond)){			
			echo $newrespond;
		}else{		
			$respond = $newrespond['value'];
			foreach($respond as $rows)
			{	
				//id name address telp website email rating day_life create_time photo visibility city				
				// if($rows['ranking']>=1 && $rows['ranking']<=15){
					$getplace = $place->getPlaceFromId($link,$rows['place_id']); 
						$position[] = $rows['ranking'];
						$id[] = $getplace['id'];
						$name[] = $getplace['name'];
						$address[] = $getplace['address'];
						$telp[] = $getplace['telp'];
						$website[] = $getplace['website'];
						$email[] = $getplace['email'];
						$rating[] = $getplace['rating'];
						$day_life[] = $getplace['day_life'];
						$create_time[] = $getplace['create_time'];
						$photo[] = 'file_upload/place/'.$getplace['name'].'-'.$getplace['location'].'/'.$getplace['photo'];
						$visibility[] = $getplace['visibility'];
						$city[] = $getplace['city'];
						
						$category = $place->getCategoryByPlace($link,$getplace['id']);
						if(!is_string($category))
						{		
							$tempfeature = array();			
							foreach($category as $categoryRows)
							{
								if($categoryRows['value']==null){
									break;
								}
								if($categoryRows['category']=="feature")
								{							
									$tempfeature[] = $categoryRows['value'];						
								}																		
							}
							if($tempfeature==null){
								$tempfeature[] = "";						
							}
						}else{
							$tempfeature = array();
							$tempfeature[] = "";
						}
						$feature[] = $tempfeature;	
				// }
			}
			$result[] = array(
						"position" => $position,
						"id" => $id,
						"name" => $name,
						"address" => $address,
						"telp" => $telp,
						"website" => $website,
						"email" => $email,
						"rating" => $rating,
						"day_life" => $day_life,
						"create_time" => $create_time,
						"photo" => $photo,
						"visibilty" => $visibility,
						"city" => $city,
							"feature" => $feature
			);
			echo str_replace('\\/', '/', json_encode($result));			
		}
	});
	
	
	//isi kolom position table top (1-15)
	$app->get('/get15topplace', function() use ($link){
		$place = new Place();
		$type = "top";
		$newrespond = $place->getRecommendationByType($link,$type);		
				
		$result = array();
			$position = array();
			$id = array();
			$name = array();
			$address = array();
			$telp = array();
			$website = array();
			$email = array();
			$rating = array();
			$day_life = array();
			$create_time = array();
			$photo = array();
			$visibility = array();
			$city = array();
				$feature = array();
		if(is_string($newrespond)){
			echo $newrespond;
		}else{	
			$respond = $newrespond['value'];
			foreach($respond as $rows)
			{
				//id name address telp website email rating day_life create_time photo visibility city
				// if($rows['ranking']>=1 && $rows['ranking']<=15){
					$getplace = $place->getPlaceFromId($link,$rows['place_id']); 
					
					$position[] = $rows['ranking'];
					$id[] = $getplace['id'];
					$name[] = $getplace['name'];
					$address[] = $getplace['address'];
					$telp[] = $getplace['telp'];
					$website[] = $getplace['website'];
					$email[] = $getplace['email'];
					$rating[] = $getplace['rating'];
					$day_life[] = $getplace['day_life'];
					$create_time[] = $getplace['create_time'];
					$photo[] = 'file_upload/place/'.$getplace['name'].'-'.$getplace['location'].'/'.$getplace['photo'];
					$visibility[] = $getplace['visibility'];
					$city[] = $getplace['city'];
					
					$category = $place->getCategoryByPlace($link,$getplace['id']);
					if(!is_string($category))
					{		
						$tempfeature = array();			
						foreach($category as $categoryRows)
						{
							if($categoryRows['value']==null){
								break;
							}
							if($categoryRows['category']=="feature")
							{							
								$tempfeature[] = $categoryRows['value'];						
							}																		
						}
						if($tempfeature==null){
							$tempfeature[] = "";						
						}
					}else{
						$tempfeature = array();
						$tempfeature[] = "";
					}
					$feature[] = $tempfeature;	
				// }
			}
			$result[] = array(
						"position" => $position,
						"id" => $id,
						"name" => $name,
						"address" => $address,
						"telp" => $telp,
						"website" => $website,
						"email" => $email,
						"rating" => $rating,
						"day_life" => $day_life,
						"create_time" => $create_time,
						"photo" => $photo,
						"visibilty" => $visibility,
						"city" => $city,
							"feature" => $feature
			);
			echo str_replace('\\/', '/', json_encode($result));			
		}
	});
	
	//update new place
	$app->put('/newplace', function() use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$inputPosition = $input['position'];
		$inputNewPlaceId = $input['newplaceid'];
		$type = "new";
		
		$place = new Place();	
		
		//cek apakah ada place_id yang baru ini ada di posisi yang lain
		$checkplace = $place->checkRecommendationExistByTypeAndPlaceId($link,$type,$inputNewPlaceId);
		if($checkplace){ 
			//ambil 
			$temp = $place->getRecommendationByTypeAndPlaceId($link,$type,$inputNewPlaceId);
			//hapus record place_id 
			$place->editRecommendation($link,"-1",$type,$temp['value']['ranking']);			
		}
		
		//isi new place_id pada posisi
		echo $place->editRecommendation($link,$inputNewPlaceId,$type,$inputPosition);			
	});
	
	//update trending place
	$app->put('/trendingplace',function() use ($link,$app) {    
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$inputPosition = $input['position'];
		$inputNewPlaceId = $input['newplaceid'];
		$type = "trending";
		
		$place = new Place();	
		
		//cek apakah ada place_id yang baru ini ada di posisi yang lain
		$checkplace = $place->checkRecommendationExistByTypeAndPlaceId($link,$type,$inputNewPlaceId);
		if($checkplace){ 
			//ambil 
			$temp = $place->getRecommendationByTypeAndPlaceId($link,$type,$inputNewPlaceId);
			//hapus record place_id 
			$place->editRecommendation($link,"-1",$type,$temp['value']['ranking']);			
		}
		
		//isi new place_id pada posisi
		echo $place->editRecommendation($link,$inputNewPlaceId,$type,$inputPosition);			
	});
	
	//update top place
	$app->put('/topplace',function() use ($link,$app) {        
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$inputPosition = $input['position'];
		$inputNewPlaceId = $input['newplaceid'];
		$type = "top";
		
		$place = new Place();	
		
		//cek apakah ada place_id yang baru ini ada di posisi yang lain
		$checkplace = $place->checkRecommendationExistByTypeAndPlaceId($link,$type,$inputNewPlaceId);
		if($checkplace){ 
			//ambil 
			$temp = $place->getRecommendationByTypeAndPlaceId($link,$type,$inputNewPlaceId);
			//hapus record place_id 
			$place->editRecommendation($link,"-1",$type,$temp['value']['ranking']);			
		}
		
		//isi new place_id pada posisi
		echo $place->editRecommendation($link,$inputNewPlaceId,$type,$inputPosition);			
	});
	//endnewcode
	
	$app->get('/place', function() use ($link){
		$place = new Place();
		$respond = $place->getPlace($link);
		if(is_string($respond))
		{
			echo $respond;
		}
		else
		{
			
			$allplace = array();
					
			foreach($respond as $rows)
			{
				$category = $place->getCategoryByPlace($link,$rows['id']);
				$top = $place->getRankingByPlace($link,$rows['id']);
					
				if(!is_string($category))
				{
					$feature = array();
				
					foreach($category as $categoryRows)
					{
						if($categoryRows['category']=="feature")
						{
							$feature[] = $categoryRows['value'];
						}
						else if($categoryRows['category']=="lowPrice")
						{
							$lowPrice = $categoryRows['value'];
						}
						else if($categoryRows['category']=="highPrice")
						{
							$highPrice = $categoryRows['value'];
						}
						else if($categoryRows['category']=="cuisine")
						{
							$cuisine = $categoryRows['value'];
						}
						else if($categoryRows['category']=="membership")
						{
							$membership = $categoryRows['value'];
						}
					}
					
					$priceSummary = $lowPrice." ".$highPrice;

					//parse price
					/*$lowPrice = explode(" ", $price[0]['value']);
					$highPrice = explode(" ", $price[count($price)-1]['value']);
					$priceSummary = "";
					if($lowPrice[0]=="Below")
					{
						if($highPrice[0]=="Above")
							$priceSummary = "Any Price";
						else
							$priceSummary = "Below ".$highPrice[count($highPrice)-1];
					}
					else if($highPrice[0]=="Above")
					{
						$priceSummary = "Above ".$lowPrice[0];
					}
					else
					{
						$priceSummary = $lowPrice[0]." - ".$highPrice[count($highPrice)-1];
					}
					*/
					$allplace[] = array(
						"place" => $rows,
						"feature" => $feature,
						"price" => $priceSummary,
						"cuisine" => $cuisine,
						"membership" => $membership,
						"top" => $top
					);
				}
				else{
					$allplace[] = array(
						"place" => $rows,
						"feature" => "",
						"price" => "",
						"cuisine" => "",
						"membership" => "",
						"top" => $top
					);
				}
			}
			echo str_replace('\\/', '/', json_encode($allplace));
		}
		
	});
	
	$app->get('/place/:field/:value', function($field,$value) use ($link){
		$place = new Place();
		if($field == "days")
		{
			$respond = $place->getPlaceFromDayLife($link,$value);
			if(is_string($respond))
			{
				echo $respond;
			}
			else
			{
			
				$allplace = array();
				foreach($respond as $rows)
				{
					$category = $place->getCategoryByPlace($link,$rows['id']);
					$top = $place->getRankingByPlace($link,$rows['id']);
						
					if(!is_string($category))
					{
						$feature = array();
				
						//$price = array();
						foreach($category as $categoryRows)
						{
							if($categoryRows['category']=="feature")
							{
								$feature[] = $categoryRows['value'];
							}
							else if($categoryRows['category']=="lowPrice")
							{
								$lowPrice = $categoryRows['value'];
							}
							else if($categoryRows['category']=="highPrice")
							{
								$highPrice = $categoryRows['value'];
							}
							else if($categoryRows['category']=="cuisine")
							{
								$cuisine = $categoryRows['value'];
							}
							else if($categoryRows['category']=="membership")
							{
								$membership = $categoryRows['value'];
							}
						}
						
						$priceSummary = $lowPrice." ".$highPrice;
						//parse price
						/*$lowPrice = explode(" ", $price[0]['value']);
						$highPrice = explode(" ", $price[count($price)-1]['value']);
						$priceSummary = "";
						if($lowPrice[0]=="Below")
						{
							if($highPrice[0]=="Above")
								$priceSummary = "Any Price";
							else
								$priceSummary = "Below ".$highPrice[count($highPrice)-1];
						}
						else if($highPrice[0]=="Above")
						{
							$priceSummary = "Above ".$lowPrice[0];
						}
						else
						{
							$priceSummary = $lowPrice[0]." - ".$highPrice[count($highPrice)-1];
						}
						*/
						$allplace[] = array(
							"place" => $rows,
							"feature" => $feature,
							"price" => $priceSummary,
							"cuisine" => $cuisine,
							"membership" => $membership,
							"top" => $top
						);
					}
					else{
						$allplace[] = array(
							"place" => $rows,
							"feature" => "",
							"price" => "",
							"cuisine" => "",
							"membership" => "",
							"top" => $top
						);
					}
				}
				echo str_replace('\\/', '/', json_encode($allplace));
			}
		}else if($field == "name"){
			
		}
	});
	
	$app->get('/searchPlace/:name/:city/:offset', function($name,$city,$offset) use ($link){
		$place = new Place();
		$respond = $place->getPlaceFromName($link,$name,$city,$offset);
		if(is_string($respond))
		{
			echo $respond;
		}
		else
		{
			echo str_replace('\\/', '/', json_encode($respond));
		}
	
	});
	
	$app->get('/advanceSearchPlace/:city/:nearby/:x/:y/:input/:offset', function($city,$nearby,$x,$y,$input,$offset) use ($link){
		$place = new Place();
		$category = explode( ',', $input);
		$respond = $place->advanceSearch($link,$category,$city,$nearby,$x,$y,$offset);
		if(is_string($respond))
		{
			echo $respond;
		}
		else
		{
			echo str_replace('\\/', '/', json_encode($respond));
		}
	
	});
	
	$app->get('/place/:id', function($id) use ($link){
		
		$place = new Place();
		$respond = $place->getPlaceFromId($link,$id);
			
		if(is_string($respond))
		{
			echo $respond;
		}
		else
		{
			$respond['photo'] = 'file_upload/place/'.$respond['name'].'-'.$respond['location'].'/'.$respond['photo'];
			$getPlace;
			$category = $place->getCategoryByPlace($link,$respond['id']);
			$top = $place->getRankingByPlace($link,$respond['id']);
				
			if(!is_string($category))
			{
				$feature = array();
				
				//$price = array();
				foreach($category as $categoryRows)
				{
					if($categoryRows['category']=="feature")
					{
						$feature[] = $categoryRows['value'];
					}
					else if($categoryRows['category']=="lowPrice")
					{
						$lowPrice = $categoryRows['value'];
					}
					else if($categoryRows['category']=="highPrice")
					{
						$highPrice = $categoryRows['value'];
					}
					else if($categoryRows['category']=="cuisine")
					{
						$cuisine = $categoryRows['value'];
					}
					else if($categoryRows['category']=="membership")
					{
						$membership = $categoryRows['value'];
					}
				}
				
				$priceSummary = $lowPrice." ".$highPrice;
				//parse price
				/*$lowPrice = explode(" ", $price[0]['value']);
				$highPrice = explode(" ", $price[count($price)-1]['value']);
				$priceSummary = "";
				if($lowPrice[0]=="Below")
				{
					if($highPrice[0]=="Above")
						$priceSummary = "Any Price";
					else
						$priceSummary = "Below ".$highPrice[count($highPrice)-1];
				}
				else if($highPrice[0]=="Above")
				{
					$priceSummary = "Above ".$lowPrice[0];
				}
				else
				{
					$priceSummary = $lowPrice[0]." - ".$highPrice[count($highPrice)-1];
				}
				*/
				
				$getPlace = array(
					"place" => $respond,
					"feature" => $feature,
					"price" => $priceSummary,
					"cuisine" => $cuisine,
					"membership" => $membership,
					"top" => $top
				);
			}
			else{
				$getPlace = array(
					"place" => $respond,
					"feature" => "",
					"price" => "",
					"cuisine" => "",
					"membership" => "",
					"top" => $top
				);
			}
			echo str_replace('\\/', '/', json_encode($getPlace));
		}
		
	});

	$app->post('/place',function () use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$inputPlace = $input['place'];
		$inputCategory = $input['feature'];
		$inputPrice = explode(";", $input['price']) ;
		$inputCuisine = $input['cuisine'];
		$inputMembership = $input['membership'];
		
		$place = new Place();
		$respond = $place->insertPlace($link,$inputPlace);
		if($respond != "success")
		{
			echo $respond;
		}
		else
		{
		
			$errorCheck = true;
			$place_id = mysqli_insert_id($link);
			foreach($inputCategory as $value)
			{
				$respond = $place->addCategory($link,$place_id,"feature",$value);
				if($respond!="success") $errorCheck = false;
			}
			
			//cuisine
			$respond = $place->addCategory($link,$place_id,"cuisine",$inputCuisine);
			if($respond!="success") $errorCheck = false;
				
			//membership
			$respond = $place->addCategory($link,$place_id,"membership",$inputMembership);
			if($respond!="success") $errorCheck = false;
			
			$low = $inputPrice[0];
			$high = $inputPrice[1];
			
			//price
			$respond = $place->addCategory($link,$place_id,"lowPrice",$low);
			if($respond!="success") $errorCheck = false;
			
			$respond = $place->addCategory($link,$place_id,"highPrice",$high);
			if($respond!="success") $errorCheck = false;
			/*
			if($low < $high)
			{
				if($low <= 30000)
				{
					$respond = $place->addCategory($link,$place_id,"price","Below 30000");
					if($respond!="success") $errorCheck = false;
				}
				$priceRange = array(30000,50000,100000,200000,300000,500000);
				for($i = 1;$i<count($priceRange);$i++)
				{
					if(($priceRange[$i-1] < $low && $low <= $priceRange[$i]) || ($priceRange[$i-1] < $high && $high <= $priceRange[$i]) || ($low <=$priceRange[$i-1] && $high > $priceRange[$i]))
					{
						$respond = $place->addCategory($link,$place_id,"price",($priceRange[$i-1]+1)." - ".$priceRange[$i]);
						if($respond!="success") $errorCheck = false;
					}
				}
				if($high >500000)
				{
					$respond = $place->addCategory($link,$place_id,"price","Above 500000");
					if($respond!="success") $errorCheck = false;
				}
			}
			*/
			if($errorCheck) echo '{"status":"success"}';
			else echo '{"status":"error","message":"not all category inserted"}';
		}
		
	});
	
	$app->put('/updateLocation/:id',function($id) use ($link,$app) {
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		$place = new Place();
		$respond = $place->getPlaceFromId($link,$id);
		if(is_string($respond))
		{
			echo $respond;
		}
		else
		{
			try
			{
				rename('../file_upload/place/'.$respond['name'].'-'.$respond['location'],'../file_upload/place/'.$respond['name'].'-'.$input['location']);
			}
			catch(Exception $e)
			{
			
			}
			$respond = $place->updateLocation($link,$id,$input['location']);
			if($respond != "success")
			{
				echo $respond;
			}
			else
			{
				echo '{"status":"success"}';
			}
		}
	});
	$app->put('/place/:id',function($id) use ($link,$app) {
		
        $request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$inputPlace = $input['place'];
		$inputCategory = $input['feature'];
		$inputPrice = explode(";", $input['price']);
		$inputCuisine = $input['cuisine'];
		$inputMembership = $input['membership'];
		
		$place = new Place();
		//echo '{"status":"'.$input['price'].'-'.$inputPrice[0].'-'.$inputPrice[1].'"}';
		
		$respond = $place->getPlaceFromId($link,$id);
		//$respond['photo'] = '../file_upload/place/'.$respond['name'].'-'.$respond['location'].'/'.$respond['photo']; 
		
		if(is_string($respond))
		{
			echo $respond;
		}
		else
		{
			
			//delete foto
			$dir = '../file_upload/place/'.$respond['name'].'-'.$respond['location'].'/'.$respond['photo'];
			if ($inputPlace['photo']!="" && file_exists($dir))
			{
				unlink($dir);
			}
			
			if ($inputPlace['photo']=="")
			{
				$inputPlace['photo'] = $respond['photo'];
			}
			try
			{
				rename('../file_upload/place/'.$respond['name'].'-'.$respond['location'],'../file_upload/place/'.$inputPlace['name'].'-'.$respond['location']);
			}
			catch(Exception $e)
			{
			
			}
			$respond = $place->updatePlace($link,$id,$inputPlace);
			if($respond != "success")
			{
				echo $respond;
			}
			else
			{
				$place->delCategory($link,$id);
				$errorCheck = true;
				foreach($inputCategory as $value)
				{
					$respond = $place->addCategory($link,$id,"feature",$value);
					if($respond!="success") $errorCheck = false;
				}
				
				//cuisine
				$respond = $place->addCategory($link,$id,"cuisine",$inputCuisine);
				if($respond!="success") $errorCheck = false;
				
				//membership
				$respond = $place->addCategory($link,$id,"membership",$inputMembership);
				if($respond!="success") $errorCheck = false;
			
				$low = $inputPrice[0];
				$high = $inputPrice[1];
				$respond = $place->addCategory($link,$id,"lowPrice",$low);
				if($respond!="success") $errorCheck = false;
			
				$respond = $place->addCategory($link,$id,"highPrice",$high);
				if($respond!="success") $errorCheck = false;
				/*if($low < $high)
				{
					if($low <= 30000)
					{
						$respond = $place->addCategory($link,$id,"price","Below 30000");
						if($respond!="success") $errorCheck = false;
					}
					$priceRange = array(30000,50000,100000,200000,300000,500000);
					for($i = 1;$i<count($priceRange);$i++)
					{
						if(($priceRange[$i-1] < $low && $low <= $priceRange[$i]) || ($priceRange[$i-1] < $high && $high <= $priceRange[$i]) || ($low <=$priceRange[$i-1] && $high > $priceRange[$i]))
						{
							$respond = $place->addCategory($link,$id,"price",($priceRange[$i-1]+1)." - ".$priceRange[$i]);
							if($respond!="success") $errorCheck = false;
						}
					}
					if($high >500000)
					{
						$respond = $place->addCategory($link,$id,"price","Above 500000");
						if($respond!="success") $errorCheck = false;
					}
				}*/
				if($errorCheck) echo '{"status":"success"}';
				else echo '{"status":"error","message":"not all category updated"}';
			}
		
		}
    });
	
	function deleteDirectory($dir) {
	    if (!file_exists($dir)) {
	        return true;
	    }
	    if (!is_dir($dir)) {
	        return unlink($dir);
	    }
	    foreach (scandir($dir) as $item) {
	        if ($item == '.' || $item == '..') {
	            continue;
	        }
	
	        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
	            return false;
	        }
	
	    }
	    return rmdir($dir);
	}
	
	$app->delete('/place/:id',function($id) use ($link) {
        $place = new Place();
		$respond = $place->getPlaceFromId($link,$id);
		$respond['photo'] = '../file_upload/place/'.$respond['name'].'-'.$respond['location'].'/'.$respond['photo'];
		
		if(is_string($respond))
		{
			echo $respond;
		}
		else
		{
			//delete foto
			$dir = '../file_upload/place/'.$respond['name'].'-'.$respond['location'];
			if (is_dir($dir)) {
				deleteDirectory($dir);
			}
			
			$respond = $place->delCategory($link,$id);
			if($respond=="success"){
				$respond = $place->deletePlace($link,$id);
				if($respond=="success") echo '{"status":"success"}';
				else echo $respond;
			}else
			{
				echo $respond;
			}
		}
		
		//delete gallery
		//delete fav_place
		//delete review
		//delete check in
    });
//end of place

//check_in
	$app->post('/checkIn',function () use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$profile_id = $input['profile_id'];
		$place_id = $input['place_id'];
		$timeline = new Timeline();
		$respond = $timeline->checkIn($link,$profile_id,$place_id);
		if($respond == "success")
		{
			$place = new Place();
			$place_name = $place->getNameFromId($link,$place_id);
			json_decode($place_name);
			if(json_last_error() == JSON_ERROR_NONE)
				echo $place_name;
			else
				echo $timeline->postTimeline($link,$profile_id,"check_in",$place_name,$place_id);
		}
		else
			echo $respond;
		
	});
//end of check_in

//review
	$app->get('/review/:field/:value/:profile_id',function ($field,$value,$profile_id) use ($link){
		$place = new Place();
		if($field == "place")
		{
			$respond = $place->getReviewByPlace($link,$value);
			if(is_string($respond))
			{
				echo $respond;
			}
			else
			{
				$index = 0;
				foreach($respond as $iter)
				{
					$getLikeReview = $place->checkLikeReview($link,$iter['id'],$profile_id);
					if($getLikeReview['status'] == "success")
					{
						$respond[$index]['like'] = $getLikeReview['like'];
					}else{
						$respond[$index]['status'] = $getLikeReview['status'];
					}
					$index++;
				}
				echo str_replace('\\/', '/', json_encode($respond));
			}
		}
		else if($field == "user")
		{
			$respond = $place->getReviewByUser($link,$value);
			if(is_string($respond))
			{
				echo $respond;
			}
			else
			{
				$getLikeReview = $place->checkLikeReview($link,$respond['id'],$profile_id);
				if($getLikeReview ['status'] == "success")
				{
					$respond['countLike'] = $getLikeReview['count'];
					$respond['like'] = $getLikeReview['like'];
				}
				echo json_encode($respond);
			}
		}
	});
	
	$app->post('/review',function () use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$review['place_id'] = $input['place_id'];
		$review['profile_id'] = $input['profile_id'];
		$review['text'] = $input['text'];
		$review['rating'] = $input['rating'];
		//review
		$place = new Place();
		$respond = $place->addReview($link,$review);
		
		$user = new User();
		$user->updateNumReview($link,$review['profile_id']);
		//timeline
		if($respond == "success")
		{
			$place_name = $place->getNameFromId($link,$review['place_id']);
			json_decode($place_name);
			if(json_last_error() == JSON_ERROR_NONE)
				echo $place_name;
			else
			{
				//hitung rating
				$place->countRating($link,$review['place_id']);
				$timeline = new Timeline();
				echo $timeline->postTimeline($link,$review['profile_id'],"review",$place_name.' \n '.$review['text'],$review['place_id']);
			}
		}
		else
			echo $respond;
		
	});
	
	$app->put('/review/:id',function($id) use ($link,$app) {
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$review['text'] = $input['text'];
		$review['rating'] = $input['rating'];
		//review
		$place = new Place();
		echo $place->editReview($link,$id,$review);
	});
	
	$app->delete('/review/:id',function($id) use ($link) {
		$place = new Place();
		$respond = $place->deleteLikeReview($link,$id);
		if($respond!="success")
		{
			echo $respond;
		}
		else
		{
			$respond = $place->deleteReview($link,$id);
			if($respond=="success") echo '{"status":"success"}';
			else echo $respond;
		}
	});
//end of review

//like_review
	//$app->get('/likeReview',function () use ($link,$app){});
	
	$app->get('/likeReview/:review_id',function ($review_id) use ($link,$app){
		$place = new Place();
		echo $place->getLikeByReview($link,$review_id);
	});
	
	$app->post('/likeReview',function () use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$review_id = $input['review_id'];
		$profile_id = $input['profile_id'];
		//like_review
		$place = new Place();
		$respond = $place->likeReview($link,$review_id,$profile_id);
		
		//timeline
		if($respond == "success")
		{
			$review = $place->getReviewById($link,$review_id);
			$place_name = $place->getNameFromId($link,$review['place_id']);
			json_decode($place_name);
			if(json_last_error() == JSON_ERROR_NONE)
				echo $place_name;
			else
			{
				$respond = $place->updateLikeReview($link,$review_id);
				if($respond)
				{
					
					$timeline = new Timeline();
					echo $timeline->postTimeline($link,$profile_id,"likereview","like ".$review['profile_id']." review @ ".$place_name.' : '.$review['text'],$review['place_id']);
				}
				else
				{
					echo '{"status":"error","message":"update failed"}';
				}
				
			}
		}
		else
			echo $respond;
	});
	
	$app->delete('/likeReview/:review_id/:profile_id',function ($review_id,$profile_id) use ($link,$app){
		$place = new Place();
		$respond = $place->unlikeReview($link,$review_id,$profile_id);
		
		$respond = $place->updateLikeReview($link,$review_id);
		if($respond)
		{
			echo '{"status":"success"}';
		}
		else
		{
			echo '{"status":"error","message":"update failed"}';
		}
	});
	
//end of like_review

//timeline
	$app->get('/post/:profile_id', function($profile_id) use ($link){
		$timeline = new Timeline();
		$respond = $timeline->getTimelineByUser($link,$profile_id);
		if(is_string($respond))
		{
			echo $respond;
		}
		else
		{
			echo str_replace('\\/', '/', json_encode($respond));
		}
	});
	
	$app->get('/timeline/:profile_id', function($profile_id) use ($link){
		$timeline = new Timeline();
		$respond = $timeline->getTimelineByUserNFollowing($link,$profile_id);
		if(is_string($respond))
		{
			echo $respond;
		}
		else
		{
			echo str_replace('\\/', '/', json_encode($respond));
		}
	});
//end of timeline

//follower
	//profile id : yg difollow
	//follower_id : yg ngfollow
	$app->get('/following/:follower_id', function($follower_id) use ($link){
		$user = new User();
		
		$respond = $user->getFollowing($link,$follower_id);
		if(is_string($respond))
		{
			echo $respond;
		}
		else
		{
			echo str_replace('\\/', '/', json_encode($respond));
		}
	});
	
	$app->get('/follower/:profile_id', function($profile_id) use ($link){
		$user = new User();
		
		$respond = $user->getFollower($link,$profile_id);
		if(is_string($respond))
		{
			echo $respond;
		}
		else
		{
			echo str_replace('\\/', '/', json_encode($respond));
		}
	});
	
	$app->get('/checkfollow/:profile_id/:follower_id', function($profile_id,$follower_id) use ($link){
		$user = new User();
		
		echo '{"status":"'.$user->checkFollow($link,$profile_id,$follower_id).'"}';
	});
	
	$app->post('/follow', function() use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$user = new User();
		$respond = $user->follow($link,$input['profile_id'],$input['follower_id']);
		$user->updateNumFollower($link,$input['profile_id']);
		echo $respond;
	});
	
	$app->delete('/follow/:profile_id/:follower_id', function($profile_id,$follower_id) use ($link){
		$user = new User();
		$respond = $user->unfollow($link,$profile_id,$follower_id);
		$user->updateNumFollower($link,$profile_id);
		echo $respond;
	});
	
	$app->get('/suggestFollow/:profile_id', function($profile_id) use ($link){
		$user = new User();
		
		$respond = $user->getSuggestFollow($link,$profile_id);
		if(is_string($respond))
		{
			echo $respond;
		}
		else
		{
			echo str_replace('\\/', '/', json_encode($respond));
		}
	});
//end of follower

//preferences
	$app->get('/preference/:profile_id', function($profile_id) use ($link){
		$user = new User();
		
		$respond = $user->getPreferenceByUser($link,$profile_id);
		if(is_string($respond))
		{
			echo $respond;
		}
		else
		{
			echo json_encode($respond);
		}
	});
	
	$app->post('/preference', function() use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		
		$user = new User();
		$respond = $user->deletePreferenceAll($link,$input['profile_id']);
		if($respond == "success"){
			$ret = true;
			foreach($input['value'] as $r)
			{
				$respond = $user->addPreference($link,$input['profile_id'],$r);
				if($respond != "success") $ret = false;
			}
			
			if($ret) echo '{"status":"success"}';
			else echo '{"status":"error"}';
		}else{
			echo '{"status":"error"}';
		}
		
		
	});
	
	$app->delete('/preference/:profile_id/:value', function($profile_id,$value) use ($link){
		$user = new User();
		echo $user->deletePreference($link,$input['profile_id'],$input['value']);
	});
	
//end of preference

//gallery
	$app->get('/gallery/:place_id', function($place_id) use ($link){
		$place = new Place();
		
		$respond = $place->getPhotoByPlace($link,$place_id);
		if(is_string($respond))
		{
			echo $respond;
		}
		else
		{
			echo str_replace('\\/', '/', json_encode($respond));
		}
	});
	
	$app->post('/gallery', function() use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		//echo '{"status":"'.$input['profile_id'].'-'.$input['place_id'].'-'.$input['photo'].'"}';
		$place = new Place();
		echo $place->addPhoto($link,$input['profile_id'],$input['place_id'],$input['photo']);
	});
	
	$app->delete('/gallery/:id', function($id) use ($link){
		echo deleteGallery($link,$id);
	});
	
	function deletePlaceGallery($link,$place_id)
	{
		$place = new Place();
		
		$respond = $place->getPhotoByPlace($link,$place_id);
		if(is_string($respond))
		{
			return $respond;
		}
		else
		{
			foreach($respond as $row)
			{
				deleteGallery($link,$row['id']);
			}
			return '{"status":"success"}';
		}
	}
	
	function deleteGallery($link,$id)
	{
		$place = new Place();
		
		$respond1 = $place->getGalleryFromId($link,$id);
		
		$respond = $place->getPlaceFromId($link,$respond1['place_id']);
		//delete foto
			$dir = '../file_upload/place/'.$respond['name'].'-'.$respond['location'].'/'.$respond1['photo'];
			if (file_exists($dir))
			{
				unlink($dir);
			}
		return $place->deletePhoto($link,$id);
	}
//end of gallery

//fav_place & most visited
	$app->post('/favplace', function() use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$user = new User();
		echo $user->insertFavPlace($link,$input['profile_id'],$input['place_id']);
	});
	
	$app->delete('/favplace/:profile_id/:place_id', function($profile_id,$place_id) use ($link){
		$user = new User();
		echo $user->deleteFavPlace($link,$profile_id,$place_id);
		
	});
	
	$app->get('/favplace/:profile_id', function($profile_id) use ($link){
		$user = new User();
		$respond = $user->getFavPlaceByUser($link,$profile_id);
		if(is_string($respond))
			echo $respond;
		else
			echo str_replace('\\/', '/', json_encode($respond));
	});

	$app->get('/checkFavplace/:place_id/:profile_id', function($place_id,$profile_id) use ($link){
		$user = new User();
		echo $user->checkFavPlace($link,$place_id,$profile_id);
	});
	
	$app->get('/visited/:profile_id', function($profile_id) use ($link){
		$user = new User();
		$respond = $user->getMostVisited($link,$profile_id);
		if(is_string($respond))
			echo $respond;
		else
			echo str_replace('\\/', '/', json_encode($respond));
	});
//end of fav_place & most visited

//recommendation
	$app->get('/recommendation/:type', function($type) use ($link){
		$place = new Place();
		if($type=='new')
		{
			$respond = $place->getNewPlace($link);
			if($respond['status'] =='success')
			{
				
					$getPlace = $place->getPlaceFromId($link,$respond['value']['place_id']);
					$respond['value']['name'] = $getPlace['name'];
					$respond['value']['photo'] = 'file_upload/place/'.$getPlace['name'].'-'.$getPlace['location'].'/'.$getPlace['photo'];
					
				echo json_encode($respond['value']);
			}
			else
			{
				echo json_encode($respond);
			}
		}
		else
		{
			$respond = $place->getRecommendationByType($link,$type);
		
			if($respond['status'] =='success')
			{
				$iter = 0;
				foreach($respond['value'] as $rows)
				{
					$getPlace = $place->getPlaceFromId($link,$rows['place_id']);
					$respond['value'][$iter]['name'] = $getPlace['name'];
					$respond['value'][$iter]['photo'] = 'file_upload/place/'.$getPlace['name'].'-'.$getPlace['location'].'/'.$getPlace['photo'];
					$iter++;
				}
				
				echo json_encode($respond['value']);
			}
			else
			{
				echo json_encode($respond);
			}
		}
		
		
	});
	
	$app->post('/recommendation/:type', function($type) use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$place = new Place();
		
		$check = true;
		for($i = 0; $i<10;$i++)
		{
			if($rows == "") $place_id = NULL;
			else $place_id = $input['value'][$i];
			
			$exist = $place->checkRecommendationExist($link,$place_id,$type,($i+1));
			if(!$exist)
			{
				$respond = $place->addRecommendation($link,$place_id,$type,($i+1));
				if(!$respond) $check = false;
			}else
			{
				$respond = $place->editRecommendation($link,$place_id,$type,($i+1));
				if(!$respond) $check = false;
			}
			
		}
		
		if($check) echo '{"status":"success"}';
		else echo '{"status":"error","message":"sql error"}';
	});
//end of recommendation

//event
	$app->post('/event/:type',function ($type) use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		$event = new Event();
		if($type == "public")
		{
			$respond = $event->addPublicEvent($link,$input['event']);
			$event_id= mysqli_insert_id($link);
			$dir = '../file_upload/event/'.$event_id;
			mkdir($dir);
			echo str_replace('\\/', '/', json_encode($respond));
		}
		else if($type == "private")
		{
			$check = $event->checkOngoingEvent($link,$input['profile_id']);
			if($check)
			{
				//$place = new Place();
				//$respond = $place->insertPrivatePlace($link,$input['event']);
				//if($respond == "success")
				//{
				//$place_id = mysqli_insert_id($link);//ngambil dr place
				$respond = $event->addPrivateEvent($link,$input['event'],$input['profile_id']);
				$event_id= mysqli_insert_id($link);
				$dir = '../file_upload/event/'.$event_id;
				mkdir($dir);
				echo str_replace('\\/', '/', json_encode($respond));
				//}
				//else
				//{
				//	echo $respond;
				//}
			}
		}
	});
	
	
	$app->put('/event/:id',function ($id) use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$event = new Event();
		$check = $event->isPublic($link,$id);
		if($check == 1)
		{
			$respond = $event->editPublicEvent($link,$input['event'],$id);
			echo json_encode($respond);
		}
		else if($check == 0)
		{
			//$place = new Place();
			//$respond = $place->editPrivatePlace($link,$input['event']);
			//if($respond == "success")
			//{
				$respond = $event->editPrivateEvent($link,$input['event'],$id);
				echo json_encode($respond);
			//}
			//else
			//{
			//	echo $respond;
			//}
		}
	});
	
	$app->put('/event/photo/:id',function ($id) use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		$newphoto = $input['newphoto'];
		$event = new Event();
		
		$respond = $event->getEventById($link,$id);
		$check = $event->isPublic($link,$id);
		//if($check)
		//{
			//delete foto
			$dir = '../file_upload/event/'.$respond['id'].'/'.$respond['photo'];
			if ($newphoto!="" && file_exists($dir) && !is_dir($dir))
			{
				unlink($dir);
			}
			$respond = $event->updatePhoto($link,$id,$newphoto);
			echo json_encode($respond);
		/*}
		else
		{
			
			$respond = $event->updatePhoto($link,$id,$newphoto);
			if($respond['status']== "success")
			{
				$place= new Place();
				$respond = $place->updatePhoto($link,$place_id,$newphoto);
			}else{
				echo json_encode($respond);
			}
			
		}*/
	});
	
	$app->delete('/event/:id',function ($id) use ($link,$app){
		$event = new Event();
		
		//delete smua gallery
		$event = new Event();
		
		$respond = $event->getPhotoByEvent($link,$place_id);
		if(is_string($respond))
		{
			return $respond;
		}
		else
		{
			foreach($respond as $row)
			{
				deleteEventGallery($link,$row['id']);
			}
			return '{"status":"success"}';
		}
		
		$respond = $event->delEvent($link,$id);
		echo json_encode($respond);
	});
	
	
	
	$app->get('/event/:id',function ($id) use ($link,$app){
		$event = new Event();
		$respond = $event->getEventById($link,$id);
		echo str_replace('\\/', '/', json_encode($respond));
	});
	
	$app->get('/nextEvent/:profile_id',function ($profile_id) use ($link,$app){
		$event = new Event();
		$respond = $event->getNextEvent($link,$profile_id);
		echo str_replace('\\/', '/', json_encode($respond));
	});
	
	$app->get('/event/member/:membership',function ($membership) use ($link,$app){
		$event = new Event();
		if($membership == "public")
			$public = 1;
		else if($membership == "private")
			$public = 0;
		
		$respond = $event->getEventByMembership($link,$public);
		echo str_replace('\\/', '/', json_encode($respond));
		
	});
	
	$app->get('/event/:name/:membership',function ($name,$membership) use ($link){
		$event = new Event();
		if($membership == "public")
			$public = 1;
		else if($membership == "private")
			$public = 0;
		
		$respond = $event->getEventByNameMembership($link,$name,$public);
		echo str_replace('\\/', '/', json_encode($respond));
		
	});
	
	$app->get('/checkHost/:event_id/:profile_id',function ($event_id,$profile_id) use ($link,$app){
		$event = new Event();
		$respond = $event->checkHost($link,$event_id,$profile_id);
		echo '{"status":"'.$respond.'"}';
	});
//end of event

//event_comment
	$app->get('/evtComment/:event_id',function ($event_id) use ($link){
		$event = new Event();
		$respond = $event->getEventCommentByEvent($link,$event_id);
		if(is_string($respond))
		{
			echo $respond;
		}
		else
		{
			echo str_replace('\\/', '/', json_encode($respond));
		}
		
		
	});
	
	$app->post('/evtComment',function () use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$event_id = $input['event_id'];
		$profile_id = $input['profile_id'];
		$text = $input['text'];
		//review
		$event = new Event();
		echo $event->addEventComment($link,$event_id,$profile_id,$text);
	});
	
	$app->put('/evtComment/:id',function($id) use ($link,$app) {
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$text = $input['text'];
		//review
		$event = new Event();
		echo $event->editEventComment($link,$id,$text);
	});
	
	$app->delete('/evtComment/:id',function($id) use ($link) {
		$event = new Event();
		$respond = $event->deleteEventComment($link,$id);
		echo json_encode($respond);
	});
//end of event_comment

//event_gallery
	$app->get('/evtGallery/:event_id', function($event_id) use ($link){
		$event = new Event();
		$respond = $event->getPhotoByEvent($link,$event_id);
		echo str_replace('\\/', '/', json_encode($respond));
	});
	
	$app->post('/evtGallery', function() use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		$event = new Event();
		echo $event->addPhoto($link,$input['event_id'],$input['photo']);
	});
	
	$app->delete('/evtGallery/:id', function($id) use ($link){
		echo deleteEventGallery($link,$id);
		
	});
	function deleteEventGallery($link,$id)
	{
		$event = new Event();
		$respond1 = $event->getGalleryFromId($link,$id);
		
		$respond = $event->getEventById($link,$respond1['event_id']);
		//delete foto
			$dir = '../file_upload/event/'.$respond1['id'].'/'.$respond['photo'];
			if (file_exists($dir))
			{
				unlink($dir);
			}
		return $event->deletePhoto($link,$id);
	}
//end of event_gallery

//event_invited
	$app->get('/evtInvited/:event_id/:rsvp', function($event_id,$rsvp) use ($link){
		$event = new Event();
		//rsvp 0 invited
		//rsvp 1 going
		//rsvp 2 not going
		$respond = $event->getInvitedByEvent($link,$event_id,$rsvp);
		echo str_replace('\\/', '/', json_encode($respond));
	});
	
	$app->get('/evtInvited2/:profile_id/:rsvp', function($profile_id,$rsvp) use ($link){
		$event = new Event();
		
		$respond = $event->getInvitedByUser($link,$profile_id,$rsvp);
		echo str_replace('\\/', '/', json_encode($respond));
	});
	
	$app->post('/evtInvited', function() use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$event = new Event();
		$user = new User();
		$respond = $event->inviteUser($link,$input['event_id'],$input['profile_id']);
		$user->updateNumInvited($link,$input['profile_id']);
		echo $respond;
	});
	
	$app->post('/goingPublicEvent', function() use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$event = new Event();
		$user = new User();
		$respond = $event->goingPublicEvent($link,$input['event_id'],$input['profile_id']);
		$user->updateNumInvited($link,$input['profile_id']);
		echo $respond;
	});
	
	$app->delete('/evtInvited/:event_id/:profile_id', function($event_id,$profile_id) use ($link){
		$event = new Event();
		echo $event->delInvited($link,$event_id,$profile_id);
	});
	
	$app->put('/evtInvited', function() use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$event_id = $input['event_id'];
		$profile_id = $input['profile_id'];
		$rsvp = $input['rsvp'];
		
		$event = new Event();
		echo $event->goingEvent($link,$event_id,$profile_id,$rsvp);
	});
	
	$app->get('/checkRSVP/:event_id/:profile_id', function($event_id,$profile_id) use ($link){
		$event = new Event();
		$respond = $event->checkRSVP($link,$event_id,$profile_id);
		echo '{"status":"'.$respond.'"}';
	});
//end of event_invited

//setting
	$app->get('/setting/:profile_id', function($profile_id) use ($link){
		$user = new User();
		$respond = $user->getSettingByUser($link,$profile_id);
		echo json_encode($respond);
	});
	
	$app->post('/setting', function() use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$profile_id = $input['profile_id'];
		$type = $input['type'];
		$value = $input['value'];
		
		$user = new User();
		$respond = $user->addSetting($link,$profile_id,$type,$value);
		if($respond)
		{
			echo '{"status":"success"}';
		}else
		{
			echo '{"status":"error"}';
		}
	});
	
	$app->put('/setting', function() use ($link){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$profile_id = $input['profile_id'];
		$type = $input['type'];
		$value = $input['value'];
		
		$user = new User();
		$respond = $user->updateSettingByUserType($link,$profile_id,$type,$value);
		if($respond)
		{
			echo '{"status":"success"}';
		}else
		{
			echo '{"status":"error"}';
		}
	});
//end of setting
//run
$app->run();


?>