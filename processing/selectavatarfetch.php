<?php
	//script to fetch the avatars for selection
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../includes/generateID.php");
	include("../includes/commons.php");
	include("../includes/encodeDecodeKey.php");
	include("../includes/user_checker.php");
	
	if (!userSessionGood(SITEUSERAUTHENTICATIONKEY))
		header("Location: logout.php");
	
	$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);
	if (isset($albumid, $photoId) && $dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
	{
		$sql = "SELECT AvatarGenID, AvatarName FROM  avatars";
		$result = $dbConn->query($sql);
		if ($result && $result->num_rows > 0)
		{
			$avatars = array();
			for (; $row = $result->fetch_array();)
			{
				//appending 'av' to make key a proper javascript variable name...genid's might have a digit first
				//which will break the syntax run of javascript. so users should strip this out if needed.
				$avatars['av' . $row['AvatarGenID']] = sprintf('{"genid": "%s", "name": "%s"}', $row['AvatarGenID'], $row['AvatarName']);
			}
			
			$avatars["success"] = 1;
			
			$json = json_encode($avatars);
			echo $json;
			flush();
			exit();
		}
	}
	
	$json = json_encode(array("success"=>0));
	echo $json;
	flush();
	exit();
?>