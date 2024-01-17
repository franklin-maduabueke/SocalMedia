<?php
	require_once("../configs/db.php");
	require_once("../modules/mysqli.php");
	require_once("../includes/commons.php");
	require_once("../includes/logNotifier.php");
	require_once("../chatplugin/configs/db.php");
	require_once("../chatplugin/classes/cjdchat.php");
	
	$msg = "Username or Password was incorrect.";
	$username = $_POST['musername'];
	$password = $_POST['mpassword'];
	$rememberLogin = $_POST['remember'];
	
	$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
	{
		//check for remember login cookie from browser and redirect to the users page.
		if (isset($_COOKIE['iwanshokotodotcomauthentication']) && !isset($username, $password))
		{
			$sql =  sprintf("SELECT MemGenID, Username FROM members WHERE Username='%s' AND Password='%s'", $theUsername, $thePassword);
			$result = $dbConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				$row = $result->fetch_array();
			
				session_start();
				$_SESSION[SITEUSERAUTHENTICATIONKEY] = $row['MemGenID'];
				$_SESSION['TheUser'] = $row['Username'];

				setcookie("iwanshokotodotcomauthentication", $_SESSION[SITEUSERAUTHENTICATIONKEY], time() + HRS24SEC * 7);
			
				//update last login.
				$sql = sprintf("UPDATE members SET LastLogin=%d WHERE MemGenID='%s'", time(), $row['MemGenID']);
				$dbConn->query($sql);
				
				$logger = new LogNotifier($dbConn);
				if ($logger)
					$logger->logNotification($_SESSION['TheUser'] . 'logged into the CMS', $row['UserTID']);
			}
			else
			{
				//back to index.php as cookie login details doesnot exist.
				//clear the cookie.
				setcookie("iwanshokotodotcomauthentication", FALSE, strtotime('1999-01-01'));
					
				header("Location: ../index.php?ilt=notavailable");
				exit();
			}
		}
		else
		{
			$username = $dbConn->real_escape_string($username);
			$password = $dbConn->real_escape_string($password);
			$sql = sprintf("SELECT MemGenID, Username FROM members WHERE Username='%s' AND Password='%s'", $username, $password);
		
			$result = $dbConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				$row = $result->fetch_array();
			
				session_start();
				$_SESSION[SITEUSERAUTHENTICATIONKEY] = $row['MemGenID'];
				$_SESSION['TheUser'] = $row['Username'];
			
				if ($rememberLogin == "yes")
					if (setcookie("iwanshokotodotcomauthentication", $_SESSION[SITEUSERAUTHENTICATIONKEY], time() + HRS24SEC * 7));
			
				//update last login.
				$sql = sprintf("UPDATE members SET LastLogin=%d WHERE MemGenID='%s'", time(), $row['MemGenID']);
				$dbConn->query($sql);
				
				$logger = new LogNotifier($dbConn);
				if ($logger)
					$logger->logNotification($_SESSION['TheUser'] . 'logged into the CMS', $row['UserTID']);
			
				//register user for chat if not registered
				$chatDBConn = new mysqli(IWANSHOKOTO_CHAT_DBSERVER, IWANSHOKOTO_CHAT_DBUSERNAME, IWANSHOKOTO_CHAT_DBPASSWORD);
				if ($chatDBConn->connect_errno == 0 && $chatDBConn->select_db(IWANSHOKOTO_CHAT_DBNAME))
				{
					try
					{
						$jdChat = new JDChat($chatDBConn);
						$jdChat->registerChatUser($username, $password, $_SESSION[SITEUSERAUTHENTICATIONKEY]);
					}
					catch (Exception $ex)
					{
						
					}
				}
				
				echo "1" . $rememberLogin; //send authentication good stream
				flush();
				exit();
			}
		}
	}

	echo "0"; //send authentication faild stream.
	flush();
	exit();
?>