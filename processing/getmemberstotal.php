<?php
	include("../configs/db.php");
	include("../modules/mysqli.php");
	
	$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);
	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
	{
		$sql = "SELECT COUNT(MemGenID) AS MemberCount FROM members";
		$result = $dbConn->query($sql);
		
		if ($result)
		{
			$jsonArray = array('memcount'=>0, 'error'=>'0');
			$jsonString = json_encode($jsonArray);
			echo "$jsonString";
			flush();
			exit();
		}
	}
	
	$jsonArray = array('error'=>1);
	$jsonString = json_encode($jsonArray);
	echo "$jsonString";
	flush();
	exit();
?>