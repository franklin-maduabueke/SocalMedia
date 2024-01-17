<?php
	//for generalize chat where we dont have categorization of friends
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../includes/user_checker.php");
	include("../includes/commons.php");
	include("../includes/encodeDecodeKey.php");
	
	if (!userSessionGood(SITEUSERAUTHENTICATIONKEY))
		header("Location: ../processing/logout.php");
		
	$dbConn = new mysqli(IWANSHOKOTO_CHAT_DBSERVER, IWANSHOKOTO_CHAT_DBUSERNAME, IWANSHOKOTO_CHAT_DBPASSWORD);	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_CHAT_DBNAME))
	{
		$sql = sprintf("SELECT UserGenID, Username, IsUserOnline FROM chatusers WHERE UserGenID<>'%s' ORDER BY Username", $_SESSION[SITEUSERAUTHENTICATIONKEY]);
		
		//echo $sql . "\n";
		$result = $dbConn->query($sql);
		
		if ($result && $result->num_rows > 0)
		{
			$jsonArray = array();
			for (; $row = $result->fetch_array();)
				$jsonArray[$row['Username']] = encodeKey($row['UserGenID']) . '#####:' . $row['IsUserOnline'];
			
			$json = json_encode($jsonArray);
			echo $json;
			flush();
			exit();
		}
	}
	
	echo "0";
	flush();
	exit();
?>