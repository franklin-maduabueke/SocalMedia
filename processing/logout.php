<?php
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../includes/commons.php");
	include("../includes/logNotifier.php");
	
	//check for user authentication registration.
	session_start();
	
	if ( !empty($_SESSION[SITEUSERAUTHENTICATIONKEY]) )
	{
		unset($_SESSION[SITEUSERAUTHENTICATIONKEY]);
		session_unset();
		session_destroy();
		
		header("Location: ../index.php?ilt=logout");
		exit();
	}
	
	session_destroy();
	header("Location: ../index.php");
	exit();
?>