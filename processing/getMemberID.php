<?php
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../includes/commons.php");
	include("../includes/user_checker.php");
	
	if (userSessionGood(SITEUSERAUTHENTICATIONKEY))
	{
		echo $_SESSION[SITEUSERAUTHENTICATIONKEY];
		flush();
		exit();
	}
	
	echo "0";
	flush();
	exit();
?>