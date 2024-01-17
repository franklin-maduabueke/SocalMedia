<?php
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../../includes/user_checker.php");
	include("../../includes/commons.php");
	include("../../includes/encodeDecodeKey.php");
	
	$sessionGood = TRUE;
	if (!userSessionGood(SITEUSERAUTHENTICATIONKEY))
		$sessionGood = FALSE;
	
	$usergenid = trim($_REQUEST["usergenid"]);
	
	if (isset($usergenid) && $sessionGood)
	{
		$dbConn = new mysqli(IWANSHOKOTO_CHAT_DBSERVER, IWANSHOKOTO_CHAT_DBUSERNAME, IWANSHOKOTO_CHAT_DBPASSWORD);
	
		if ($dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_CHAT_DBNAME))
		{
			$usergenid = $dbConn->real_escape_string($usergenid);
			//get conversation history
			$sql = "SELECT Conversation FROM conversation_history WHERE ToGenID='$usergenid' ORDER BY MTID";
			$result = $dbConn->query($sql);

			$jsonArray = array('messages'=>'', 'error'=>0);
			if ($result && $result->num_rows > 0)
			{
				$row = $result->fetch_array();
				$conversations = explode('#@#~#', $row['Conversation']);
				
				foreach ($conversations as $value)
					if (!empty($value))
						$jsonArray['messages'] .= $value . "~"; //delimiter collection of json objects
			}
			
			$json = json_encode($jsonArray);
			echo $json;
			flush();
			exit();
		}
	}
?>