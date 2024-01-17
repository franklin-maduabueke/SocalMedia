<?php
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../includes/generateID.php");
	include("../includes/commons.php");
	include("../includes/encodeDecodeKey.php");
	include("../includes/user_checker.php");
	include("../classes/ciwanshokoto.php");
	
	if (!userSessionGood(SITEUSERAUTHENTICATIONKEY))
		header("Location: logout.php");
	
	$judgeid = $_POST['judgeid'];
	$contestantid = $_POST['contestantid'];
	
	$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);
	if (isset($judgeid, $contestantid) && $dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
	{
		try
		{
			$iwanshokoto = new IWanShokoto($dbConn);
			//get rating types
			$ratingTypes = $iwanshokoto->getRatingTypes();
			if (count($ratingTypes))
				foreach ($ratingTypes as $key=>$value)
					if (isset($_POST['point' . $key]) && is_numeric($_POST['point' . $key]))
						$iwanshokoto->rateMember($judgeid, $contestantid, $key, $_POST['point' . $key]);
		}
		catch(Exception $ex)
		{
		}
	}
	
	header("Location: " . $_SERVER['HTTP_REFERER']);
	exit();
?>