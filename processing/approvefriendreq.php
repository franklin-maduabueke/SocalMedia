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
	
	$iwsfid = trim($_REQUEST['iwsfid']);
	$objid = $_REQUEST['objid'];
	
	$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);
	if (isset($iwsfid, $objid) && $dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
	{
		try
		{
			$theMember = new Member($_SESSION[SITEUSERAUTHENTICATIONKEY], $dbConn);
			if ($theMember->approveFriendRequest($iwsfid))
			{
				$theFriend = new Member($iwsfid, $dbConn);
				
				$added = $theFriend->addFriend($_SESSION[SITEUSERAUTHENTICATIONKEY]) ? 1 : 0;
				
				$json = json_encode(array("success"=> $added, "objid"=>$objid));
				echo $json;
				exit();
				flush();
			}
		}
		catch(Exception $ex)
		{
		}
	}
	
	$json = json_encode(array("success"=> 0, "objid"=>$objid));
	echo $json;
	flush();
	exit();
?>