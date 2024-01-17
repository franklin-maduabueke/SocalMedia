<?php
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../../includes/user_checker.php");
	include("../../includes/commons.php");
	include("../../includes/encodeDecodeKey.php");
	
	$sessionGood = TRUE;
	if (!userSessionGood(SITEUSERAUTHENTICATIONKEY))
		$sessionGood = FALSE;
		
	//format: {'toid', 'fromid':xxx, 'datetime': 'yy-mm-dd','message': 'string', 'color':#xxxxxx}
	
	//reading from cross domain request.
	
	$toid = trim($_REQUEST['toid']);
	$fromid = trim($_REQUEST['fromid']);
	$datetime = date('G:i a');
	$message = trim($_REQUEST['message']);
	$color = trim($_REQUEST['color']);
	$sendtoroom = $_REQUEST['toroom'];
	$roomid = $_REQUEST['roomid'];
	
	if (isset($toid, $fromid, $datetime, $message, $color) && $sessionGood)
	{
		$dbConn = new mysqli(IWANSHOKOTO_CHAT_DBSERVER, IWANSHOKOTO_CHAT_DBUSERNAME, IWANSHOKOTO_CHAT_DBPASSWORD);
	
		if ($dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_CHAT_DBNAME))
		{
			$message = $dbConn->real_escape_string(strip_tags(trim($message), '<a>'));
			
			$sql = "SELECT Username FROM chatusers WHERE UserGenID='$fromid'";
			$result = $dbConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				$row = $result->fetch_array();
				$username = $row['Username'];
			}
			//checks on message
			//remove email address phone numbers
			//$regx = "/[A-z][\w\.]+@[A-z]+\.\b([a-z]{2}\.[a-z]{2}|com|net|gov|edu|tv|org)/gi";
			//$message = preg_replace($regx, "@", $message);
			
			$jsonArray = array("toid"=> $toid, "fromid"=> $fromid , "datetime"=> $datetime, "message"=> $message, "color"=> $color, "username"=>$username, "messageid"=>md5($fromid . time()));
			
			$jsonString = json_encode($jsonArray);
			
			if ($jsonString)
			{
				if (isset($sendtoroom) && $sendtoroom == 1)
				{
					if (isset($roomid))
					{
						$roomid = $dbConn->real_escape_string($roomid);
						$regx = "/^r@/";

						if (preg_match($regx, $roomid) > 0)
						{
							$sql = "SELECT UserList FROM chatroomusersmessage WHERE RoomGenID='$roomid'";
							$result = $dbConn->query($sql);
							if ($result && $result->num_rows > 0)
							{
								//find user in list
								$userInRoom = FALSE;
								$row = $result->fetch_array();
								$userList = explode(',', $row['UserList']);
								foreach ($userList as $value)
									if (strcasecmp($_SESSION[SITEUSERAUTHENTICATIONKEY], $value) == 0)
									{
										$userInRoom = TRUE;
										break;
									}
								
								if ($userInRoom)
								{
									$sql = "SELECT Message FROM chatroomusersmessage WHERE RoomGenID='$roomid'";
									$result = $dbConn->query($sql);
									if ($result && $result->num_rows > 0)
									{
										$row = $result->fetch_array();
										if (!empty($row['Message']))
										{
											$messages = $row['Message'] . '~';
										}
									}
									
									$sql = sprintf("UPDATE chatroomusersmessage SET Message=CONCAT('$messages', '$jsonString') WHERE RoomGenID='$roomid'");
									$dbConn->query($sql);
									
									$jsonArray = array('error'=>0);
									$json = json_encode($jsonArray);
									echo $json;
									exit();
								}
							}
						}
					}
				}
				else
				{
					$sql = "INSERT INTO mailbox (ToGenID, Message) VALUES('$toid', '$jsonString')";
					$dbConn->query($sql);
					if ($dbConn->affected_rows > 0)
					{
						//manage conversation history
						$sql = "SELECT Conversation FROM conversation_history WHERE ToGenID='$toid'";
						$result = $dbConn->query($sql);
						if ($result && $result->num_rows > 0)
						{
							$row = $result->fetch_array();
							$conversations = $row['Conversation'] . $jsonString . "#@#~#";
							$sql = "UPDATE conversation_history SET Conversation='$conversations' WHERE ToGenID='$toid'";
							$dbConn->query($sql);
						}
						else
						{
							$sql = "INSERT INTO conversation_history (ToGenID, Conversation) VALUES('$toid', '$jsonString#@#~#')";
							$dbConn->query($sql);
						}
					}
					
					$jsonArray = array('error'=>0);
					$json = json_encode($jsonArray);
					echo $json;
					exit();
				}
			}
		}
	}
	
	$jsonArray = array('error'=>1, 'message'=>'Connection lost');
	$json = json_encode($jsonArray);
	echo $json;
	exit();
?>