<?php
	//broadcast a users online status
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../includes/commons.php");
	include("../includes/user_checker.php");
	include("../includes/encodeDecodeKey.php");
	
	define('BROADCASTINTERVAL', 15); //every 15 seconds.
	
	if (!userSessionGood(SITEUSERAUTHENTICATIONKEY))
		header("Location: logout.php");
	else
		$sessionGood = TRUE;
	
	$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
	{
		
		$sql = "UPDATE members SET IsOnline=" . (time() + BROADCASTINTERVAL) . " WHERE MemGenID='" . $_SESSION[SITEUSERAUTHENTICATIONKEY] . "'";
		
		$dbConn->query($sql);
		
		//echo $sql;
		
		$sql = "UPDATE members SET IsOnline=0 WHERE IsOnline<" . time();
		//echo $sql;
		$dbConn->query($sql);
	}
	
	echo "1";
	flush();
	exit();
?>