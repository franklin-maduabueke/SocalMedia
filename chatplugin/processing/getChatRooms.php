<?php
	//script to get the list of chat rooms
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../../includes/user_checker.php");
	include("../../includes/commons.php");
	include("../../includes/encodeDecodeKey.php");
	
	$sessionGood = TRUE;
	if (!userSessionGood(SITEUSERAUTHENTICATIONKEY))
		$sessionGood = FALSE;
	
	$dbConn = new mysqli(IWANSHOKOTO_CHAT_DBSERVER, IWANSHOKOTO_CHAT_DBUSERNAME, IWANSHOKOTO_CHAT_DBPASSWORD);
	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_CHAT_DBNAME))
	{
		$sql = sprintf("SELECT RoomGenID, RoomName, IconFormat, Description FROM chatrooms ORDER BY RoomName");
		$result = $dbConn->query($sql);
		
		if ($result && $result->num_rows > 0)
		{
			$roomList = array("success"=>1, "rooms"=>"");
			for (; $row = $result->fetch_array();)
				$roomList["rooms"] .= sprintf('{"roomgenid": "%s", "roomname": "%s", "iconformat": "%s", "description": "%s"}#', $row['RoomGenID'], $row['RoomName'], $row['IconFormat'], $row['Description']);
			
			$json = json_encode($roomList);
			echo $json;
			flush();
			exit();
		}
	}
	
	$json = json_encode(array("success"=>0, "errormsg"=>"Unable to connect to server"));
	echo $json;
	flush();
	exit();
?>