<?php
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../includes/generateID.php");
	include("../includes/commons.php");
	include("../includes/encodeDecodeKey.php");
	include("../includes/user_checker.php");
	
	if (!userSessionGood(SITEUSERAUTHENTICATIONKEY))
		header("Location: processing/logout.php");
	
	$albumid = trim($_GET['alid']);
	
	$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);
	if (isset($albumid) && $dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
	{
		//delete photo collection
		$sql = sprintf("DELETE FROM album_photo WHERE AlbumGenID='%s'", decodeKey($albumid));
		$dbConn->query($sql);
		//delete album
		$sql = sprintf("DELETE FROM album WHERE AlbumGenID='%s' AND MemGenID='%s'", decodeKey($albumid), $_SESSION[SITEUSERAUTHENTICATIONKEY]);
		$dbConn->query($sql);
	}
	
	header("Location: ../ui/gallery.php");
	exit();
?>