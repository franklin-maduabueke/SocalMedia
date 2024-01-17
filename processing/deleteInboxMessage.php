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
	
	$messageid = trim($_REQUEST['messageid']);
	
	$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);
	if (isset($messageid) && $dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
	{
		try
		{
			$theMember = new Member($_SESSION[SITEUSERAUTHENTICATIONKEY], $dbConn);
			echo $theMember->deleteInboxMessage($messageid);
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