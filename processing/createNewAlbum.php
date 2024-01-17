<?php
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../includes/generateID.php");
	include("../includes/commons.php");
	include("../includes/user_checker.php");
	
	if (!userSessionGood(SITEUSERAUTHENTICATIONKEY))
		header("Location: processing/logout.php");
	
	$albumname = trim($_POST['albumname']);
	
	$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);
	if (isset($albumname) && $dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
	{
		$sql = "SELECT AlbumGenID FROM album WHERE AlbumTitle='$albumname'";
		$result = $dbConn->query($sql);
		if ($result && $result->num_rows == 0)
		{
			$marked = FALSE;
			for ($i = 0; $i < 20; $i++)
			{
				$genid = generateID(20);
				$sql = "SELECT AlbumGenID FROM album WHERE AlbumGenID='$genid'";
				$result = $dbConn->query($sql);
				if ($result && $result->num_rows == 0)
				{
					$marked = TRUE;
					break;
				}
			}
			
			if ($marked)
			{
				$sql = sprintf("INSERT INTO album (AlbumGenID, AlbumTitle, MemGenID, DateCreated) VALUES('%s', '%s', '%s', '%s')", $genid, $albumname, $_SESSION[SITEUSERAUTHENTICATIONKEY], date("Y-m-j"));

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
					$json = json_encode(array("success"=>0, "errormsg"=>"Unable to complete album creation process."));
					echo $json;
					flush();
					exit();
				}
			}
		}
		else
		{
			$json = json_encode(array("success"=>2, "errormsg"=>"Album already exists."));
			echo $json;
			flush();
			exit();
		}
	}
	
	$json = json_encode(array("success"=>0, "errormsg"=>"Unable to connect to database server."));
	echo $json;
	flush();
	exit();
?>