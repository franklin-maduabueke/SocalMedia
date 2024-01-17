<?php
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../includes/generateID.php");
	include("../includes/commons.php");
	
	$iam = $_POST['iamselect'];
	$intrestedIn = $_POST['lookingforselect'];
	$username = $_POST['reguserusername'];
	$password = $_POST['reguserpwrd'];
	$confirmPwd = $_POST['reguserconfirmpwrd'];
	$emailaccount = $_POST['reguseremail'];
	$location = $_POST['reguserlocation'];
	
	if (isset($iam, $intrestedIn, $username, $password, $confirmPwd, $location, $emailaccount) && $password == $confirmPwd && is_numeric($location) && filter_var($emailaccount, FILTER_VALIDATE_EMAIL))
	{
		if ($iam == 1 || $iam == 3)
			$iam = 1;
		else
			$iam = 2;
		
		$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);
	
		if ($dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
		{
			$intrestedIn = $dbConn->real_escape_string($intrestedIn);
			$username = $dbConn->real_escape_string($username);
			$password = $dbConn->real_escape_string($password);
			$confirmPwd = $dbConn->real_escape_string($confirmPwd);
			$emailaccount =$dbConn->real_escape_string($emailaccount);
			$location = $dbConn->real_escape_string($location);
			//check availability of username
			$sql = "SELECT Username FROM members WHERE Username='$username'";
			$result = $dbConn->query($sql);
			
			if ($result && $result->num_rows == 0)
			{
				//check if this email has been registered with us.
				$sql = "SELECT Email FROM member_profile WHERE Email='$emailaccount'";
				$result = $dbConn->query($sql);
				if ($result && $result->num_rows == 0)
				{
					//generate user id.
					for ($i = 0; $i < 20; $i++)
					{
						$genid = generateID(20, FALSE);
						$sql = "SELECT MemGenID FROM members WHERE MemGenID='$genid'";
						$result = $dbConn->query($sql);
						if ($result && $result->num_rows == 0)
						{
							$marked = TRUE;
							break;
						}
					}
				
					if ($marked)
					{
						$gender = 0;
						
						$sql = sprintf("INSERT INTO members (MemGenID, Username, Password, DOR, ActivationCode, Expires) VALUES('%s', '%s', '%s', '%s', '%s', '%s')", $genid, $username, $password, date("Y-m-d"), generateID(60), time() + HRS48SEC);
						$dbConn->query($sql);
					
						if ($dbConn->affected_rows)
						{
							//add to profile;
							if ($iam == 1)
								$seeking = 2;
							elseif ($iam == 2)
								$seeking = 1;
							elseif ($iam == 3)
								$seeking = 2;
							elseif ($iam == 4)
								$seeking = 1;
								
							$sql = sprintf("INSERT INTO member_profile (MemGenID, Email, Gender, IntrestedIn, Seeking, Location) VALUES('%s', '%s', %d, '%s,', %d, %d)", $genid, $emailaccount, $iam, $intrestedIn, $seeking, $location);
							$dbConn->query($sql);
							if ($dbConn->affected_rows)
							{
								//redirect to provide email account for activation.
								setcookie("iwanshokotouname", $username);
								header("Location: ../account/activateaccount.php?uname=" . $username);
								exit();
							}
						}
					}
				}
			}
		}
	}
	
	//something went wrong with registration.
	header("Location: ../index.php");
	exit();
?>