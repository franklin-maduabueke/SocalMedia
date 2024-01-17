<?php
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../includes/generateID.php");
	include("../includes/commons.php");
	include("../includes/encodeDecodeKey.php");
	include("../includes/user_checker.php");
	
	if (!userSessionGood(SITEUSERAUTHENTICATIONKEY))
		header("Location: logout.php");
	
	$albumid = trim($_GET['alid']);
	$photoId = trim($_GET['pid']);
	
	$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);
	if (isset($albumid, $photoId) && $dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
	{
		//delete photo collection
		$sql = sprintf("DELETE FROM album_photo WHERE AlbumGenID='%s' AND PhotoGenID='%s'", decodeKey($albumid), decodeKey($photoId));
		$dbConn->query($sql);
	}
	
	header("Location: ../ui/gallery.php?alid=" . $albumid . "&tsk=view");
	exit();
?>