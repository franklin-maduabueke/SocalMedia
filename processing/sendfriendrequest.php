<?php
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../includes/generateID.php");
	include("../includes/commons.php");
	include("../includes/encodeDecodeKey.php");
	include("../includes/user_checker.php");
	include("../classes/cmember.php");
	
	if (!userSessionGood(SITEUSERAUTHENTICATIONKEY))
		header("Location: logout.php");
	
	$message = trim($_POST['message']);
	$memberid = trim($_POST['memberid']);
	
	$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);
	if (isset($message, $memberid) && $dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
	{
		try
		{
			$theMember = new Member($_SESSION[SITEUSERAUTHENTICATIONKEY], $dbConn);
			echo $theMember->sendFriendRequest($memberid, strip_tags($message, '<a>'));
			exit();
			flush();
		}
		catch(Exception $ex)
		{
		}
	}
	
	echo "0";
	flush();
	exit();
?>