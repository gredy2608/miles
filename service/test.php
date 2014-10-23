<?php
	include("connect.php");
include("class/account.php");
include("class/event.php");
include("class/place.php");
include("class/timeline.php");
include("class/user.php");
	
	$id = 3;
	$user = new User();
	$input['first_name'] = 'me';
	$input['last_name'] = 'yes';
	$input['birthday'] = '2014-07-01';
	$input['sex'] = 1;
	$input['phone'] = '123123';
	echo $user->updateProfile($link,$id,$input);
		
	/*$profile_id= 1;
	$place_id= 221;
	$user = new User();*/
	//echo $user->deleteFavPlace($link,$profile_id,$place_id);
	/*$profile_id = 1;
		$oldpassword = 'b';
		$newpassword = 'c';
		
		$account = new Account();
		
		$respond = $account->changePassword($link,$profile_id,$oldpassword,$newpassword);
		echo $respond;
		if($respond)
			echo '{"status":"success"}';
		else
			echo '{"status":"error"}';
			*/
	/*$input['username'] = 'asdf';
	$input['password'] = 'asdf';
	$input['first_name'] = 'test';
	$input['last_name'] = 'error';
	$input['birthday'] = '20120101';
	$input['sex'] = 1;
	$input['photo'] = "";
	$input['phone'] = '213123123';
	
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
		}*/
	
	/*$profile_id= 1;
	$follower_id= 3;
	
	$user = new User();
		$respond = $user->unfollow($link,$profile_id,$follower_id);
		$user->updateNumFollower($link,$profile_id);
		echo $respond;*/
		
		/*$user = new User();
		$respond = $user->follow($link,$input['profile_id'],$input['follower_id']);
		$user->updateNumFollower($link,$input['profile_id']);
		echo $respond;*/
	
	
	
	//$user = new User();
	//echo $user->deleteFavPlace($link,$profile_id,$place_id);
	/*$user = new User();
	echo $user->insertFavPlace($link,$input['profile_id'],$input['place_id']);*/
	/*$review_id = 15;
		$profile_id = 1;
		//like_review
		$place = new Place();
		$respond = $place->likeReview($link,$review_id,$profile_id);
		
		//timeline
		if($respond == "success")
		{
			echo 'asdf';
			$review = $place->getReviewById($link,$review_id);
			$place_name = $place->getNameFromId($link,$review['place_id']);
			echo $place_name.' '.$review['place_id'];
			json_decode($place_name);
			if(json_last_error() == JSON_ERROR_NONE)
				echo $place_name;
			else
			{
				echo 'horeee';
				$respond = $place->updateLikeReview($link,$review_id);
				echo $respond;
				if($respond)
				{
					
					$timeline = new Timeline();
					echo $timeline->postTimeline($link,$review['profile_id'],"likereview",$place_name,$review['place_id']);
				}
				else
				{
					echo '{"status":"error","message":"update failed"}';
				}
				
			}
		}
		else
			echo "asdf2".$respond;*/
	
?>