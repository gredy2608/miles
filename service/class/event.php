<?php

	include("gcmMobile/GCM.php");

	class Event{
	//event
		function addPublicEvent($link,$event)
		{
			$name = mysqli_escape_string($link,$event['name']);
			$cp = mysqli_escape_string($link,$event['cp']);
			$website = mysqli_escape_string($link,$event['website']);
			$start_time = $event['start_time']; //YYYY-MM-DD HH:MM:SS
			$end_time = $event['end_time']; //YYYY-MM-DD HH:MM:SS
			$description = mysqli_escape_string($link,$event['description']);
			$photo = mysqli_escape_string($link,$event['photo']);
			$dresscode = mysqli_escape_string($link,$event['dresscode']);
			$price = mysqli_escape_string($link,$event['price']);
			$type = mysqli_escape_string($link,$event['type']);
			$location = mysqli_escape_string($link,$event['location']);
			$address = mysqli_escape_string($link,$event['address']);
			$membership = 1;
			
			$sql = "INSERT INTO event (name,cp,website,start_time,end_time,description,membership,photo,dresscode,price,type,location,address) VALUES ('".$name."', '".$cp."','".$website."','".$start_time."','".$end_time."', '".$description."', ".$membership.", '".$photo."', '".$dresscode."','".$price."','".$type."','".$location."','".$address."')";
			if (mysqli_query($link, $sql)) {
				//success
				$ret['status'] = "success";
				$ret['id'] = mysqli_insert_id($link);
				
				
				return $ret;
			}else{
				//error
				$ret['status'] = "error";
				$ret['message'] = "event not inserted";
				return $ret;
			}
		}
		
		function editPublicEvent($link,$event,$id)
		{
			$name = mysqli_escape_string($link,$event['name']);
			$cp = mysqli_escape_string($link,$event['cp']);
			$website = mysqli_escape_string($link,$event['website']);
			$start_time = $event['start_time']; //YYYY-MM-DD HH:MM:SS
			$end_time = $event['end_time']; //YYYY-MM-DD HH:MM:SS
			$description = mysqli_escape_string($link,$event['description']);
			$photo = mysqli_escape_string($link,$event['photo']);
			$dresscode = mysqli_escape_string($link,$event['dresscode']);
			$price = mysqli_escape_string($link,$event['price']);
			$type = mysqli_escape_string($link,$event['type']);
			$location = mysqli_escape_string($link,$event['location']);
			$address = mysqli_escape_string($link,$event['address']);
			
			$membership = 1;
			$sql = 'UPDATE event SET name="'.$name.'",cp="'.$cp.'",website="'.$website.'",start_time="'.$start_time.'",end_time="'.$end_time.'",description="'.$description.'",membership="'.$membership.'",photo="'.$photo.'",dresscode="'.$dresscode.'",price="'.$price.'",type="'.$type.'",location="'.$location.'", address="'.$address.'" WHERE id='.$id;
			
			if (mysqli_query($link, $sql)) {
				//success
				$ret['status'] = "success";
				return $ret;
			}else{
				//error
				$ret['status'] = "error";
				$ret['message'] = "event not updated";
				return $ret;
			}
		}
		
		function addPrivateEvent($link,$event,$host)
		{
			$name = mysqli_escape_string($link,$event['name']);
			$start_time = $event['start_time']; //YYYY-MM-DD HH:MM:SS
			$end_time = $event['end_time']; //YYYY-MM-DD HH:MM:SS
			$description = mysqli_escape_string($link,$event['description']);
			$photo = mysqli_escape_string($link,$event['photo']);
			$location = mysqli_escape_string($link,$event['location']);
			$address = mysqli_escape_string($link,$event['address']);
			$web = "";
			$membership = 0;
			
			$sql = "INSERT INTO event (name,host,website,start_time,end_time,description,membership,photo,location,address) VALUES ('".$name."', '".$host."','".$web."','".$start_time."','".$end_time."', '".$description."', ".$membership.", '".$photo."', '".$location."','".$address."')";
			if (mysqli_query($link, $sql)) {
				//success
				$ret['status'] = "success";
				$ret['id'] = mysqli_insert_id($link);
				return $ret;
			}else{
				//error
				$ret['status'] = "error";
				$ret['message'] = "event not inserted";
				return $ret;
			}
		}
		
		function editPrivateEvent($link,$event,$id)
		{
			$name = mysqli_escape_string($link,$event['name']);
			$start_time = $event['start_time']; //YYYY-MM-DD HH:MM:SS
			$end_time = $event['end_time']; //YYYY-MM-DD HH:MM:SS
			$description = mysqli_escape_string($link,$event['description']);
			$photo = mysqli_escape_string($link,$event['photo']);
			$location = mysqli_escape_string($link,$event['location']);
			$address = mysqli_escape_string($link,$event['address']);
			
			$membership = 0;
			
			$membership = 1;
			$sql = 'UPDATE event SET name="'.$name.'",start_time="'.$start_time.'",end_time="'.$end_time.'",description="'.$description.'",membership="'.$membership.'",photo="'.$photo.'",location="'.$location.'", address="'.$address.'" WHERE id='.$id;
			
			if (mysqli_query($link, $sql)) {
				//success
				$ret['status'] = "success";
				return $ret;
			}else{
				//error
				$ret['status'] = "error";
				$ret['message'] = "event not updated";
				return $ret;
			}
		}
		
		function delEvent($link,$id)
		{
			$check = true;
			//delete comment
			$sql = 'DELETE FROM event_comment WHERE event_id ='.$id;
			if (!mysqli_query($link, $sql)) $check = false;
				
			//delete gallery
			$sql = 'DELETE FROM event_gallery WHERE event_id ='.$id;
			if (!mysqli_query($link, $sql)) $check = false;
			
			//delete invite
			$sql = 'DELETE FROM event_invite WHERE event_id ='.$id;
			if (!mysqli_query($link, $sql)) $check = false;
			
			$sql = 'DELETE FROM event WHERE id ='.$id;
			if (!mysqli_query($link, $sql)) $check = false;
			
			if ($check) {
				$ret['status'] = "success";
				return $ret;
			}else{
				$ret['status'] = "error";
				$ret['message'] = "failed to delete event";
				return $ret;
			}
		}
		
		function getEventById($link,$id)
		{
			$sql = 'SELECT * FROM event WHERE id ='.$id;
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 1){
					$rows = mysqli_fetch_assoc($result);
					//$rows['photo'] = 'file_upload/event/'.$rows['id'].'/'.$rows['photo'];
					$ret['status'] = "success";
					$ret['value'] = $rows;
					return $ret;
				}else{
					//error
					$ret['status'] = "error";
					$ret['message'] = "event not found";
					return $ret;
				}
			}else{
				//error
				$ret['status'] = "error";
				$ret['message'] = "sql error";
				return $ret;
			}
		}
		
		function getEventByMembership($link,$membership)
		{
			$sql = 'SELECT * FROM event WHERE membership ='.$membership.' ORDER BY start_time';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					//error
					$ret['status'] = "error";
					$ret['message'] = "event not found";
					return $ret;
				}else{
					$rows = array();
					while($r = mysqli_fetch_assoc($result)) {
						//$r['photo'] = 'file_upload/event/'.$r['id'].'/'.$r['photo'];
			
						$rows[] = $r;
					}
					$ret['status'] = "success";
					$ret['value'] = $rows;
					return $ret;
				}
			}else{
				//error
				$ret['status'] = "error";
				$ret['message'] = "sql error";
				return $ret;
			}
		}
		
		function getEventByNameMembership($link,$name,$membership)
		{
			$sql = 'SELECT * FROM event WHERE membership ='.$membership.' AND name LIKE "%'.$name.'%" ORDER BY start_time';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					//error
					$ret['status'] = "error";
					$ret['message'] = "event not found";
					return $ret;
				}else{
					$rows = array();
					while($r = mysqli_fetch_assoc($result)) {
						//$r['photo'] = 'file_upload/event/'.$r['id'].'/'.$r['photo'];
						
						$rows[] = $r;
					}
					$ret['status'] = "success";
					$ret['value'] = $rows;
					return $ret;
				}
			}else{
				//error
				$ret['status'] = "error";
				$ret['message'] = "sql error";
				return $ret;
			}
		}
		
		function checkOngoingEvent($link,$profile_id) //private cm boleh 5
		{
			$sql = 'SELECT * FROM event WHERE host ="'.$profile_id.'" AND start_time <= now() AND end_time >= now()';
			
			if($result = mysqli_query($link, $sql)){
				$r = mysqli_fetch_assoc($result);
				$num_rows = mysqli_num_rows($result);
				if($num_rows < 5){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
		
		
		
		
		function isPublic($link,$id)
		{
			$sql = 'SELECT * FROM event WHERE id ='.$id;
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 1){
					$rows = mysqli_fetch_assoc($result);
					if($rows['membership'] == 1)
						return 1;
					else
						return 0;
				}else{
					//error
					return -1;
				}
			}else{
				return -1;
			}
		}
		
		function checkHost($link,$event_id,$profile_id)
		{
			$sql = 'SELECT * FROM event WHERE id ='.$event_id.' AND profile_id='.$profile_id;
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows >= 1){
					return "yes";
				}else{
					return "no";
				}
			}else{
				return "error";
			}
		}
		
		function updatePhoto($link,$id,$newphoto)
		{
			$sql = 'UPDATE event SET photo="'.$newphoto.'" WHERE id='.$id;
			
			if (mysqli_query($link, $sql)) {
				//success
				$ret['status'] = "success";
				return $ret;
			}else{
				//error
				$ret['status'] = "error";
				$ret['message'] = "photo not updated";
				return $ret;
			}
		}
	//end of event 
	
	//event_gallery
		function addPhoto($link,$event_id,$photo)
		{
			$photo = mysqli_escape_string($link,$photo);
			
			$sql = "INSERT INTO event_gallery (event_id,photo) VALUES ('".$event_id."','".$photo."')";
			if (mysqli_query($link, $sql)) {
				//success
				$ret['status'] = "success";
			}else{
				//error
				$ret['status'] = "error";
				$ret['message'] = "photo not inserted";
			}
			return $ret;
		}
		
		function deletePhoto($link,$id)
		{
			$sql = 'DELETE FROM event_gallery WHERE id ='.$id;
			if (mysqli_query($link, $sql)) {
				//success
				$ret['status'] = "success";
			}else{
				//error
				$ret['status'] = "error";
				$ret['message'] = "photo not deleted";
			}
			return $ret;
		}
		
		function getGalleryFromId($link,$id)
		{
			$sql = 'SELECT * FROM event_gallery WHERE id ='.$id.' LIMIT 1';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 1){
					$rows = mysqli_fetch_assoc($result);
					$ret['status'] = "success";
					$ret['value'] = $rows;
				}else{
					$ret['status'] = "error";
					$ret['message'] = "photo not found";
				}
			}else{
				$ret['status'] = "error";
				$ret['message'] = "sql error";
			}
			return $ret;
		}
		
		function getPhotoByEvent($link,$event_id)
		{
			$sql = 'SELECT * FROM event_gallery WHERE event_id="'.$event_id.'"';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					$ret['status'] = "error";
					$ret['message'] = "photo not found";
				}else{
					$sql2 = 'SELECT id FROM event WHERE id="'.$event_id.'" LIMIT 1';
					if($result2 = mysqli_query($link,$sql2))
					{
						$value = mysqli_fetch_assoc($result2);
						$rows = array();
						while($r = mysqli_fetch_assoc($result)) {
							//$r['photo'] = 'file_upload/event/'.$value['id'].'/'.$r['photo'];
							$rows[] = $r;
						}
						$ret['status'] = "success";
						$ret['value'] = $rows;
					}else{
						$ret['status'] = "error";
						$ret['message'] = "sql error";
					}
					
				}
			}else{
				$ret['status'] = "error";
				$ret['message'] = "sql error";
			}
			return $ret;
		}
	//end of event_gallery
	
	//event_invite
	
		function inviteUser($link,$event_id,$profile_id)
		{
			$sql = 'INSERT INTO event_invite (event_id,profile_id,rsvp) VALUES ('.$event_id.','.$profile_id.',"0")';
			if (mysqli_query($link, $sql)) 
			{
				//success
				$gcm = new GCM();
				$sql2 = 'SELECT	* FROM gcm_users WHERE prof_id = "'.$profile_id.'"';
				 if ($result2 = mysqli_query($link, $sql2)) 
				 {
					$num_rows2 = mysqli_num_rows($result2);
					if($num_rows2 > 0)
					{
						$registration_ids = array();
						while($arr = mysqli_fetch_array($result2)){
							$registration_ids[] = $arr['gcm_regid'];
						}
						$sql3 = 'SELECT	* FROM event WHERE id = "'.$event_id.'"';
						if ($result3 = mysqli_query($link, $sql3)) 
						 {
							$num_rows3 = mysqli_num_rows($result3);
							if($num_rows3 > 0)
							{
								$arr2 = mysqli_fetch_array($result3);
								$sql4 = 'SELECT * FROM profile WHERE id='.$arr2['host'];
								if ($result4 = mysqli_query($link, $sql4)) 
								 {
									$num_rows4 = mysqli_num_rows($result4);
									if($num_rows4 > 0)
									{
										$arr3 = mysqli_fetch_array($result4);
										$message = 'evtId='.$event_id.',profId='.$arr2['host'].',nameProf='.$arr3['first_name'].' '.$arr3['last_name'].',nameEvt='.$arr2['name'];
										$message = array("info" => $message);
										$respond = $gcm->send_notification($registration_ids, $message);
									}
								}
							}
						}
					}
				}
				return '{"status":"success"}';
			}else{
				//error
				return '{"status":"error","message":"failed to invite"}';
			}
		}
		
		function goingPublicEvent($link,$event_id,$profile_id)
		{
			$sql = 'INSERT INTO event_invite (event_id,profile_id,rsvp) VALUES ('.$event_id.','.$profile_id.',"1")';
			if (mysqli_query($link, $sql)) 
			{
				//success
				return '{"status":"success"}';
			}else{
				//error
				return '{"status":"error","message":"failed to going"}';
			}
		}
		
		function delInvited($link,$event_id,$profile_id)
		{
			$sql = 'DELETE FROM event_invite WHERE event_id ='.$event_id.' AND profile_id='.$profile_id;
			if (mysqli_query($link, $sql)) {
				//success
				return '{"status":"success"}';
			}else{
				//error
				return '{"status":"error","message":"failed to unfollow"}';
			}
		}
		
		function getInvitedByEvent($link,$event_id,$rsvp)
		{
			if($rsvp != 3){
				$sql = 'SELECT * FROM event_invite WHERE event_id ='.$event_id.' AND rsvp = '.$rsvp;
			}else{
				$sql = 'SELECT * FROM event_invite WHERE event_id ='.$event_id.' AND (rsvp = 0 OR rsvp = 2)';
			}
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					//error
					$ret['status'] = "error";
					$ret['message'] = "0 invited";
					return $ret;
				}else{
					$rows = array();
					while($r = mysqli_fetch_assoc($result)) {
						$sql = 'SELECT last_name,first_name,photo FROM profile WHERE id="'.$r['profile_id'].'" LIMIT 1';
						$result2 = mysqli_query($link,$sql);
						$value = mysqli_fetch_object($result2);
						$r['name'] = $value->first_name.' '.$value->last_name;
						$r['photo'] = $value->photo;
						$rows[] = $r;
					}
					$ret['status'] = "success";
					$ret['value'] = $rows;
					return $ret;
				}
			}else{
				//error
				$ret['status'] = "error";
				$ret['message'] = "sql error";
				return $ret;
			}
		}
		
		function getInvitedByUser($link,$profile_id,$rsvp)
		{
			if($rsvp != 3){
				$sql = 'SELECT inv.id as id,inv.event_id as event_id, inv.profile_id as profile_id, inv.rsvp as rsvp, ev.name as name, ev.address as address,ev.start_time as time,ev.photo as photo FROM event_invite inv,event ev WHERE inv.event_id = ev.id AND inv.profile_id ='.$profile_id.' AND inv.rsvp = '.$rsvp.' ORDER BY inv.rsvp, ev.start_time';
			}else{
				$sql = 'SELECT inv.id as id,inv.event_id as event_id, inv.profile_id as profile_id, inv.rsvp as rsvp, ev.name as name, ev.address as address,ev.start_time as time,ev.photo as photo FROM event_invite inv,event ev WHERE inv.event_id = ev.id AND inv.profile_id ='.$profile_id.' AND (rsvp = 0 OR rsvp = 2) ORDER BY inv.rsvp, ev.start_time';
			}
			
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					//error
					$ret['status'] = "error";
					$ret['message'] = "0 event";
					return $ret;
				}else{
					$rows = array();
					while($r = mysqli_fetch_assoc($result)) {
						//$r['photo'] = 'file_upload/event/'.$r['event_id'].'/'.$r['photo'];
						$rows[] = $r;
					}
					$ret['status'] = "success";
					$ret['value'] = $rows;
					return $ret;
				}
			}else{
				//error
				$ret['status'] = "error";
				$ret['message'] = "sql error";
				return $ret;
			}
		}
		
		/*function getInvitedByUser($link,$profile_id,$rsvp)
		{
			if($rsvp != 3){
				$sql = 'SELECT * FROM event_invite WHERE profile_id ='.$profile_id.' AND rsvp = '.$rsvp.' ORDER BY rsvp';
			}else{
				$sql = 'SELECT * FROM event_invite WHERE profile_id ='.$profile_id.' AND (rsvp = 0 OR rsvp = 2) ORDER BY rsvp';
			}
			
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					//error
					$ret['status'] = "error";
					$ret['message'] = "0 event";
					return $ret;
				}else{
					$rows = array();
					while($r = mysqli_fetch_assoc($result)) {
						$sql = 'SELECT * FROM event WHERE id="'.$r['event_id'].'" LIMIT 1';
						$result2 = mysqli_query($link,$sql);
						$value = mysqli_fetch_object($result2);
						$r['name'] = $value->name;
						$r['address'] = $value->address;
						$r['time'] = $value->start_time;
						//$r['photo'] = 'file_upload/event/'.$value->id.'/'.$value->photo;
						$rows[] = $r;
					}
					$ret['status'] = "success";
					$ret['value'] = $rows;
					return $ret;
				}
			}else{
				//error
				$ret['status'] = "error";
				$ret['message'] = "sql error";
				return $ret;
			}
		}*/
		
		
		function getNextEvent($link,$profile_id) 
		{
			$sql = 'SELECT * FROM event_invite WHERE profile_id ='.$profile_id.' AND rsvp = 1';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					//error
					$ret['status'] = "error";
					$ret['message'] = "0 event";
					return $ret;
				}else{
					$rows = array();
					while($r = mysqli_fetch_assoc($result)) {
						$sql = 'SELECT * FROM event WHERE id="'.$r['event_id'].'" AND start_time - now() >=0 LIMIT 1';
						$result2 = mysqli_query($link,$sql);
						$value = mysqli_fetch_object($result2);
						$num_rows = mysqli_num_rows($result2);
						if($num_rows != 0)
						{
							$r['name'] = $value->name;
							$r['time'] = $value->start_time;
							//$r['photo'] = 'file_upload/event/'.$value->id.'/'.$value->photo;
							$rows[] = $r;
						}
						else
						{
							$ret['status'] = "error";
							$ret['message'] = "0 event";
							return $ret;
						}
						
					}
					$ret['status'] = "success";
					$ret['value'] = $rows;
					return $ret;
				}
			}else{
				//error
				$ret['status'] = "error";
				$ret['message'] = "sql error";
				return $ret;
			}
		}
	//end of event_invite
	
	//event_going
		//rsvp 0 invited
		//rsvp 1 going
		//rsvp 2 not going
		function goingEvent($link,$event_id,$profile_id,$rsvp)
		{
			$sql = 'UPDATE event_invite SET rsvp="'.$rsvp.'" WHERE event_id="'.$event_id.'" AND profile_id="'.$profile_id.'"';
			if (mysqli_query($link, $sql)) {
				//success
				return '{"status":"success"}';
			}else{
				//error
				return '{"status":"error","message":"failed to invite"}';
			}
		}
		
		function checkRSVP($link,$event_id,$profile_id)
		{
			$sql = 'SELECT * FROM event_invite WHERE id ='.$event_id.' AND profile_id='.$profile_id;
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				$r = mysqli_fetch_assoc($result);
				
				if($num_rows >= 1){
					return $r['rsvp']."";
				}else{
					return "not found";
				}
			}else{
				return "error";
			}
		}
	//end of event_going
	
	
	//event_comment
		function addEventComment($link,$event_id,$profile_id,$text)
		{
			$text = mysqli_escape_string($link,$text);
			$sql = 'INSERT INTO event_comment (event_id,profile_id,text,date) VALUES ('.$event_id.','.$profile_id.',"'.$text.'",now())';
			if (mysqli_query($link, $sql)) {
				//success
				return '{"status":"success"}';
			}else{
				//error
				return '{"status":"error","message":"check in failed"}';
			}
		}
		
		function editEventComment($link,$id,$text)
		{
			$text = mysqli_escape_string($link,$text);
			$sql = 'UPDATE review SET text="'.$text.'",date=now() WHERE id='.$id;
			if (mysqli_query($link, $sql)) {
				//success
				return '{"status":"success"}';
			}else{
				//error
				return '{"status":"error","message":"failed to edit"}';
			}
		}
		
		function deleteEventComment($link,$id)
		{
			$sql = 'DELETE FROM event_comment WHERE id ='.$id;
			if (mysqli_query($link, $sql)) {
				//success
				$ret['status'] = "success";
			}else{
				//error
				$ret['status'] = "error";
				$ret['message'] = "comment not deleted";
			}
			return $ret;
		}
		
		function getEventCommentByEvent($link,$event_id)
		{
			$sql = 'SELECT * FROM event_comment WHERE event_id="'.$event_id.'" ORDER BY date';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					return '{"status":"error","message":"0 comment"}';
				}else{
					$rows = array();
					while($r = mysqli_fetch_assoc($result)) {
						$sql = 'SELECT last_name,first_name,photo FROM profile WHERE id="'.$r['profile_id'].'" LIMIT 1';
						$result2 = mysqli_query($link,$sql);
						$value = mysqli_fetch_object($result2);
						$r['name'] = $value->first_name.' '.$value->last_name;
						$r['photo'] = $value->photo;
						$rows[] = $r;
					}
					return $rows;
				}
			}else{
				return '{"status":"error","message":"sql error"}';
			}
		}
	//end of event_comment
	}
?>