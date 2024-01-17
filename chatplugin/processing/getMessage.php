<?php
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../../includes/user_checker.php");
	include("../../includes/commons.php");
	include("../../includes/encodeDecodeKey.php");
	
	$sessionGood = TRUE;
	if (!userSessionGood(SITEUSERAUTHENTICATIONKEY))
		$sessionGood = FALSE;
		
	$chatuserid = $_REQUEST['toid'];
	$roomlist = $_REQUEST['roomlist'];
	
	if ($sessionGood && isset($chatuserid) && !empty($chatuserid))
	{
		$dbConn = new mysqli(IWANSHOKOTO_CHAT_DBSERVER, IWANSHOKOTO_CHAT_DBUSERNAME, IWANSHOKOTO_CHAT_DBPASSWORD);
	
		if ($dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_CHAT_DBNAME))
		{
			$sql = "SELECT Message, MTID FROM mailbox WHERE ToGenID='$chatuserid' ORDER BY MTID";
			$result = $dbConn->query($sql);
			
			$cleanMsgSet = '';
			
			$jsonArray = array('messages'=>'', 'error'=>0);
			if ($result && $result->num_rows > 0)
			{
				while ($row = $result->fetch_array())
				{
					$jsonArray['messages'] .= $row['Message'] . "~"; //delimiter collection of json objects
					$cleanMsgSet .= $row['MTID'] . ',';
				}
			}
			
			$cleanMsgSet = substr($cleanMsgSet, 0, strlen($cleanMsgSet) - 1);
			
			$sql = sprintf("DELETE FROM mailbox WHERE MTID IN($cleanMsgSet)");
			$dbConn->query($sql);
			
			if (isset($roomlist) && !empty($roomlist))
			{
				$roomMessages = array();
				$roomlist = explode(',', $roomlist);
				foreach ($roomlist as $roomid)
				{
					if (!empty($roomid))
					{
						$regx = "/^r@/";
						if (preg_match($regx, $roomid) > 0)
						{
							$sql = "SELECT Message FROM chatroomusersmessage WHERE RoomGenID='$roomid'";
							$result = $dbConn->query($sql);
							if ($result && $result->num_rows > 0)
							{
								$row = $result->fetch_array();
								$jsonArray['messages'] .= $row['Message'] . "~";
							}
						}
					}
				}
			}

			$json = json_encode($jsonArray);
			echo $json;
			flush();
			exit();
		}
	}
	
	$jsonArray = array('messages'=>'', 'error'=>1);
	$json = json_encode($jsonArray);
	echo $json;
	flush();
	exit();
?>