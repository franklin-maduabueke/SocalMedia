<?php
	//script to add a member as friend of user
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../includes/user_checker.php");
	include("../includes/commons.php");
	include("../includes/encodeDecodeKey.php");
	
	if (!userSessionGood(SITEUSERAUTHENTICATIONKEY))
	{
		header("Location: logout.php");
		exit();
	}
	
	$fmemid = $_POST['fmemid'];
	
	$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);
	if (isset($fmemid) && $dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
	{
		//add friend if not listed. friend list delimited by ';' and encoded with encodeKey function
		$sql = sprintf("SELECT FriendsList FROM friendship WHERE MemGenID='%s'", $_SESSION[SITEUSERAUTHENTICATIONKEY]);
		$result = $dbConn->query($sql);
		if ($result && $result->num_rows > 0)
		{
			$row = $result->fetch_array();
			$friends = explode(";", $row['FriendsList']);
			$friendInList = FALSE;
			foreach ($friends as $value)
			{
				if (!empty($value))
				{
					$friend = decodeKey($value);
					if ($friend == $value)
					{
						$friendInList = TRUE;
						break;
					}
				}
			}
			
			if (!$friendInList)
			{
				//add friend then
				$friendsList = $row['FriendsList'] . "$fmemid;";
				$sql = sprintf("UPDATE friendship SET FriendsList='%s' WHERE MemGenID='%s'", $friendsList, $_SESSION[SITEUSERAUTHENTICATIONKEY]);
				$dbConn->query($sql);
				if ($dbConn->affected_rows > 0)
				{
					$json = json_encode(array("success"=>1));
					echo $json;
					flush();
					exit();
				}
				else
				{
					$json = json_encode(array("success"=>0, "errormsg"=>"Unable to update friendship"));
					echo $json;
					flush();
					exit();
				}
			}
		}
		else
		{
			//add member in friendship list the add friendship
			$sql = sprintf("INSERT INTO friendship (MemGenID, FriendsList) VALUES('%s', '%s')", $_SESSION[SITEUSERAUTHENTICATIONKEY],  "$fmemid;");
			$dbConn->query($sql);
			if ($dbConn->affected_rows > 0)
			{
				$json = json_encode(array("success"=>1));
				echo $json;
				flush();
				exit();
			}
			else
			{
				$json = json_encode(array("success"=>0, "errormsg"=>"Unable to update friendship"));
				echo $json;
				flush();
				exit();
			}
		}
	}
	
	$json = json_encode(array("success"=>0, "errormsg"=>"Unable to connect to server!"));
	echo $json;
	flush();
	exit();
?>