<?php

	class DB_Functions
	{
		

		 /**
		 * Storing new user
		 * returns user details
		 */
		public function storeUser($link,$profId, $gcm_regid) 
		{
			// insert user into database
			/*$qr1 = mysqli_query($link,"SELECT * FROM gcm_users WHERE id = $profId") or die(mysql_error());
			$result1 = mysqli_query($link,$qr1);
			if($result1)
			{
				if(deleteUser($link,$profId, $gcm_regid))
				{
					$query = 'INSERT INTO gcm_users(prof_id, gcm_regid, created_at) VALUES("'.$profId.'", "'.$gcm_regid.'", NOW())';
					$result = mysqli_query($link,$query);
					// check for successful store
					if ($result) {
						// get user details
						$id = mysqli_insert_id($link); // last inserted id
						$result = mysqli_query($link,"SELECT * FROM gcm_users WHERE id = $id") or die(mysql_error());
						// return user details
						if (mysqli_num_rows($result) > 0) {
							echo mysqli_fetch_array($result);
						} else {
							echo false;
						}
					} else {
						echo false;
					}
				}else
				{
					
				}
			}else
			{*/
				$query = 'INSERT INTO gcm_users(prof_id, gcm_regid, created_at) VALUES("'.$profId.'", "'.$gcm_regid.'", NOW())';
				$result = mysqli_query($link,$query);
				// check for successful store
				if ($result) {
					// get user details
					$id = mysqli_insert_id($link); // last inserted id
					$result = mysqli_query($link,"SELECT * FROM gcm_users WHERE id = $id") or die(mysql_error());
					// return user details
					if (mysqli_num_rows($result) > 0) {
						echo mysqli_fetch_array($result);
					} else {
						echo false;
					}
				} else {
					echo false;
				}
			//}
		}
		
		/**
		 * Storing new user
		 * returns user details
		 */
		public function deleteUser($link,$profId, $gcm_regid) 
		{
			// insert user into database
			$query = 'DELETE FROM gcm_users WHERE prof_id = "'.$profId.'" AND gcm_regid = "'.$gcm_regid.'"';
			$result = mysqli_query($link,$query);
			// check for successful store
			if ($result) {
				return true;
			} else {
				return false;
			}
		}
	}
?>