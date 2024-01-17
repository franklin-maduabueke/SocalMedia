<?php
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../includes/user_checker.php");
	include("../includes/commons.php");
	include("../includes/encodeDecodeKey.php");
	
	if (!userSessionGood(SITEUSERAUTHENTICATIONKEY))
		header("Location: logout.php");
	
	$userid = (isset($_POST['userid'])) ? trim($_POST['userid']) : $_SESSION[SITEUSERAUTHENTICATIONKEY];
	
	if (isset($userid) && !empty($userid))
	{
		$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);
	
		if ($dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
		{
			//respond with friends codes.
			$sql = "SELECT FriendsList FROM friendship WHERE MemGenID='$userid'";
			$result = $dbConn->query($sql);
			
			if ($result && $result->num_rows > 0)
			{
				$friendslist = $result->fetch_array();
				
				if (strlen($friendslist['FriendsList']))
				{
					$friendsCodes = explode(",", $friendslist['FriendsList']);
					if (count($friendsCodes))
					{
						foreach ($friendsCodes as $value)
						{
							if (!empty($value) && strlen($value) >= 20)
							{
								//get username.
								$sql = "SELECT Username, IsOnline FROM members WHERE MemGenID='$value'";
								$userresult = $dbConn->query($sql);
								if ($userresult && $userresult->num_rows > 0)
								{
									$userrow = $userresult->fetch_array();
									echo  sprintf('{"memgenid": "%s", "username": "%s", "onlinetime": "%s"}', $value, $userrow['Username'], $userrow['IsOnline']) . "~";
									flush();
								}
							}
						}
						exit();
					}
				}
			}
		}
	}
	
	echo "0"; //no friends or no connection to database.
	flush();
	exit();
?>