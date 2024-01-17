<?php
	//checks the availability of a username
	include("../configs/db.php");
	include("../modules/mysqli.php");
	
	$usernamecheck = $_POST['username'];
	$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);
	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
	{
		$usernamecheck = strip_tags($usernamecheck);
		$usernamecheck = $dbConn->real_escape_string($usernamecheck);
		$sql = "SELECT COUNT(MemGenID) AS MemberCount FROM members WHERE Username='$usernamecheck'";
		$result = $dbConn->query($sql);
		
		if ($result)
		{
			$row = $result->fetch_array();
			
			if ($row['MemberCount'] > 0)
				$jsonArray = array('available'=>0, 'error'=>'0');
			else
				$jsonArray = array('available'=>1, 'error'=>'0');
				
			$jsonString = json_encode($jsonArray);
			echo $jsonString;
			flush();
			exit();
		}
	}
	
	$jsonArray = array('error'=>1);
	$jsonString = json_encode($jsonArray);
	echo $jsonString;
	flush();
	exit();
?>