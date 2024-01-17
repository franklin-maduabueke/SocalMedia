<?php
	//broadcast a users online status
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../../includes/commons.php");
	include("../../includes/user_checker.php");
	include("../../includes/encodeDecodeKey.php");
	
	define('BROADCASTINTERVAL', 15); //every 15 seconds.
	
	if (!userSessionGood(SITEUSERAUTHENTICATIONKEY))
		header("Location: ../processing/logout.php");
	else
		$sessionGood = TRUE;
	
	$dbConn = new mysqli(IWANSHOKOTO_CHAT_DBSERVER, IWANSHOKOTO_CHAT_DBUSERNAME, IWANSHOKOTO_CHAT_DBPASSWORD);	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_CHAT_DBNAME))
	{
		$sql = "UPDATE chatusers SET IsUserOnline=" . (time() + BROADCASTINTERVAL) . " WHERE UserGenID='" . $_SESSION[SITEUSERAUTHENTICATIONKEY] . "'";
		
		$dbConn->query($sql);
		
		$sql = "UPDATE chatusers SET IsUserOnline=0 WHERE IsUserOnline<" . time();
		//echo $sql;
		$dbConn->query($sql);
		
		//clear those that are out of time
		$sql = "SELECT UserGenID FROM chatusers WHERE IsUserOnline<" . time();
		$result = $dbConn->query($sql);
		if ($result && $result->num_rows > 0)
		{
			$clearSet = '';
			for (; $row = $result->fetch_array();)
				$clearSet .= $row['UserGenID'] . ',';
			
			$clearSet = substr($clearSet, 0, strlen($clearSet) - 1);
			$sql = "DELETE FROM chatroomusersmessage WHERE UserGenID IN($clearSet)";
			$dbConn->query($sql);
		}
	}
	
	echo "1";
	flush();
	exit();
?>