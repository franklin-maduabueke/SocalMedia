<?php
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../../includes/user_checker.php");
	include("../../includes/commons.php");
	include("../../includes/encodeDecodeKey.php");
	
	$sessionGood = TRUE;
	if (!userSessionGood(SITEUSERAUTHENTICATIONKEY))
		$sessionGood = FALSE;

	$chatroomid = $_REQUEST['chatroomid'];
	if ($sessionGood && isset($chatroomid) && !empty($chatroomid))
	{
		$dbConn = new mysqli(IWANSHOKOTO_CHAT_DBSERVER, IWANSHOKOTO_CHAT_DBUSERNAME, IWANSHOKOTO_CHAT_DBPASSWORD);	
		if ($dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_CHAT_DBNAME))
		{
			$sql = "SELECT UserList FROM chatroomusersmessage WHERE RoomGenID='$chatroomid'";
			$result = $dbConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				$row = $result->fetch_array();
				$userList = explode(',', $row['UserList']);
				$newUserList = '';
				foreach ($userList as $value)
					if (!empty($value) && strcasecmp($_SESSION[SITEUSERAUTHENTICATIONKEY], $value) != 0)
						$newUserList .= $value . ',';
				
				$sql = "UPDATE chatroomusersmessage SET UserList='$newUserList' WHERE RoomGenID='$chatroomid'";
				$dbConn->query($sql);
			}
			
			$sql = sprintf("DELETE FROM roomusers WHERE UserGenID='%s' AND RoomGenID='%s'", $_SESSION[SITEUSERAUTHENTICATIONKEY], $chatroomid);
			$dbConn->query($sql);
			
			$json = json_encode(array("success"=>1, "roomid"=>$chatroomid));
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