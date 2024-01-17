<?php
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../includes/commons.php");
	include("../includes/logNotifier.php");
	include("../includes/encodeDecodeKey.php");
	
	$msg = "Username or Password was incorrect.";
	$username = trim($_POST['musername']);
	$password = trim($_POST['mpassword']);
	
	$dbConn = new mysqli(IWANSHOKOTO_CHAT_DBSERVER, IWANSHOKOTO_CHAT_DBUSERNAME, IWANSHOKOTO_CHAT_DBPASSWORD);	
	if (isset($username, $password) && $dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_CHAT_DBNAME))
	{
			$username = $dbConn->real_escape_string($username);
			$password = $dbConn->real_escape_string($password);
			$sql = sprintf("SELECT UserGenID, Username FROM chatusers WHERE Username='%s' AND Password='%s'", $username, $password);
		
			$result = $dbConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				$row = $result->fetch_array();
				//get member details
				
				session_start();
				$_SESSION[SITEUSERAUTHENTICATIONKEY] = $row['UserGenID'];
				$_SESSION['TheUser'] = $row['Username'];
			
				/*$logger = new LogNotifier($dbConn);
				if ($logger)
					$logger->logNotification($_SESSION['TheUser'] . 'logged into the CMS', $row['UserTID']);
				*/
				
				echo "1"; //send authentication good stream
				flush();
				exit();
			}
	}

	echo "0"; //send authentication faild stream.
	flush();
	exit();
?>