<?php
	//check for user authentication registration.
	//@return : true if user is authenticated or false if not.
	function userSessionGood($sessionKey)
	{
		session_start();
		session_cache_limiter("nocache");
		
		if (!isset($_SESSION[$sessionKey]))
		{
			session_unset();
			session_destroy();
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
?>