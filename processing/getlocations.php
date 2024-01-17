<?php
	//script to get locations. currently used by match.js to get the locations for the panel
	include("../modules/mysqli.php");
	include("../includes/user_checker.php");
	include("../configs/db.php");
	include("../includes/commons.php");
	include("../includes/generateID.php");
	include("../includes/encodeDecodeKey.php");
	
	$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
	{
		$sql = "SELECT LTID, StateName FROM location ORDER BY StateName";
		$result = $dbConn->query($sql);
		if ($result && $result->num_rows > 0)
		{
			for (;$row = $result->fetch_array();)
				echo sprintf('<option value="%s">%s</option>', $row['LTID'], $row['StateName']);
		}
	}
	
	echo "";
	flush();
	exit();
?>