<?php
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../../includes/user_checker.php");
	include("../../includes/commons.php");
	include("../../includes/encodeDecodeKey.php");
	
	$sessionGood = TRUE;
	if (!userSessionGood(SITEUSERAUTHENTICATIONKEY))
		$sessionGood = FALSE;

	$chatroomid = $_REQUEST['chatroomid'];
	if ($sessionGood && isset($chatroomid) && !empty($chatroomid))
	{
		$dbConn = new mysqli(IWANSHOKOTO_CHAT_DBSERVER, IWANSHOKOTO_CHAT_DBUSERNAME, IWANSHOKOTO_CHAT_DBPASSWORD);	
		if ($dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_CHAT_DBNAME))
		{
			$chatroomid = $dbConn->real_escape_string($chatroomid);
			
			$sql = sprintf("SELECT MaxCapacity FROM chatrooms WHERE RoomGenID='%s'", $chatroomid);
			$result = $dbConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				$row = $result->fetch_array();
				$maxCapacity = $row['MaxCapacity'];
				if ($maxCapacity == 0)
				{
					$sql = sprintf("SELECT RoomGenID FROM chatroomusersmessage WHERE RoomGenID='%s'", $chatroomid);
					$result = $dbConn->query($sql);
					if ($result && $result->num_rows == 0)
					{
						//add room stream so people can join
						$sql = "INSERT INTO chatroomusersmessage (RoomGenID) VALUES('$chatroomid')";
						$dbConn->query($sql);
						if ($dbConn->affected_rows > 0)
						{
							$sql = sprintf("UPDATE chatroomusersmessage SET UserList='%s' WHERE RoomGenID='%s'", $_SESSION[SITEUSERAUTHENTICATIONKEY] . ",", $chatroomid);
							$dbConn->query($sql);
							
							$sql = sprintf("SELECT UserGenID FROM roomusers WHERE UserGenID='%s' AND RoomGenID='%s'", $_SESSION[SITEUSERAUTHENTICATIONKEY], $chatroomid);
							$result = $dbConn->query($sql);
							if ($result && $result->num_rows == 0)
							{
								$sql = sprintf("INSERT INTO roomusers (RoomGenID, UserGenID) VALUES('%s', '%s')", $chatroomid, $_SESSION[SITEUSERAUTHENTICATIONKEY]);
								$dbConn->query($sql);
							}
							
							$json = json_encode(array("success"=>1, "allow"=>1, "chatroomid"=>$chatroomid));
							echo $json;
							flush();
							exit();
						}
					}
					else
					{
						$userExists = FALSE;
						$sql = "SELECT UserList FROM chatroomusersmessage WHERE RoomGenID='$chatroomid'";
						$result = $dbConn->query($sql);
						if ($result && $result->num_rows > 0)
						{
							$row = $result->fetch_array();
							$userList = explode(',', $row['UserList']);
							foreach ($userList as $value)
								if (strcasecmp($_SESSION[SITEUSERAUTHENTICATIONKEY], $value) == 0)
								{
									$userExists = TRUE;
									break;
								}
						}
						
						if (!$userExists)
						{
							$sql = sprintf("UPDATE chatroomusersmessage SET UserList=CONCAT(UserList, '%s,') WHERE RoomGenID='%s'", $_SESSION[SITEUSERAUTHENTICATIONKEY], $chatroomid);
							$dbConn->query($sql);
							
							$sql = sprintf("SELECT UserGenID FROM roomusers WHERE UserGenID='%s' AND RoomGenID='%s'", $_SESSION[SITEUSERAUTHENTICATIONKEY], $chatroomid);
							$result = $dbConn->query($sql);
							if ($result && $result->num_rows == 0)
							{
								$sql = sprintf("INSERT INTO roomusers (RoomGenID, UserGenID) VALUES('%s', '%s')",  $_SESSION[SITEUSERAUTHENTICATIONKEY], $chatroomid);
								$dbConn->query($sql);
							}
						}
						
						$json = json_encode(array("success"=>1, "allow"=>1, "chatroomid"=>$chatroomid));
						echo $json;
						flush();
						exit();
					}
				}
				else
				{
					$sql = sprintf("SELECT COUNT(UserGenID) AS Population FROM roomsusers WHERE RoomGenID='%s'", $chatroomid);
					$result = $dbConn->query($sql);
					if ($result && $result->num_rows > 0)
					{
						$row = $result->fetch_array();
						$population = $row['Population'];
						if ($population < $maxCapacity)
						{
							$userExists = FALSE;
							$sql = "SELECT UserList FROM chatroomusersmessage WHERE RoomGenID='$chatroomid'";
							$result = $dbConn->query($sql);
							if ($result && $result->num_rows > 0)
							{
								$row = $result->fetch_array();
								$userList = explode(',', $row['UserList']);
								foreach ($userList as $value)
									if (strcasecmp($_SESSION[SITEUSERAUTHENTICATIONKEY], $value) == 0)
									{
										$userExists = TRUE;
										break;
									}
							}
							
							if (!$userExists)
							{
								$sql = sprintf("UPDATE chatroomusersmessage SET UserList=CONCAT(UserList, '%s,') WHERE RoomGenID='%s'", $_SESSION[SITEUSERAUTHENTICATIONKEY], $chatroomid);
								$dbConn->query($sql);
								
								$sql = sprintf("SELECT UserGenID FROM roomusers WHERE UserGenID='%s' AND RoomGenID='%s'", $_SESSION[SITEUSERAUTHENTICATIONKEY], $chatroomid);
								$result = $dbConn->query($sql);
								if ($result && $result->num_rows == 0)
								{
									$sql = sprintf("INSERT INTO roomusers (RoomGenID, UserGenID) VALUES('%s', '%s')", $chatroomid, $_SESSION[SITEUSERAUTHENTICATIONKEY]);
									$dbConn->query($sql);
								}
							}
							
							$json = json_encode(array("success"=>1, "allow"=>1, "chatroomid"=>$chatroomid));
							echo $json;
							flush();
							exit();
						}
						else
						{
							$json = json_encode(array("success"=>1, "allow"=>0, "chatroomid"=>$chatroomid));
							echo $json;
							flush();
							exit();
						}
					}
				}
			}
		}
	}
	
	$json = json_encode(array("success"=>0, "errormsg"=>"Unable to connect to server"));
	echo $json;
	flush();
	exit();
?>