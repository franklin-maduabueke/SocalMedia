<?php
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../includes/commons.php");
	include("../includes/user_checker.php");
	
	if (!userSessionGood(SITEUSERAUTHENTICATIONKEY))
		header("Location: ../processing/logout.php");
	else
		$sessionGood = TRUE;
	
	$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);
	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
	{
		$dob = $_POST['ageyear'] . '-' . $_POST['agemonth'] . '-' . $_POST['ageday'];
		$location = $_POST['location'];
		$lookingfor = $_POST['profilelookingfor'];
		$intrestedin = $_POST['profileintrestedin'];
		$hobby = $dbConn->real_escape_string(trim($_POST['profilehobby']));
		$occupation = $dbConn->real_escape_string(trim($_POST['profileoccupation']));
		$relationship = $_POST['profilerelationship'];
		$height = $_POST['profileheightfeet'] . 'ft ' . $_POST['profileheightinch'] . 'inc';
		$favquotes = $dbConn->real_escape_string(trim($_POST['profilefavoritequotes']));
		$meinmywords = $dbConn->real_escape_string(trim($_POST['profilemeinmyownwords']));
		$biggestasset = $dbConn->real_escape_string(trim($_POST['profilemybigestasset']));
		$fairytale = $dbConn->real_escape_string(trim($_POST['profilefairytaleromance']));
		$countryofresidence = $_POST['profilecountryofresidence'];
		$sexorientation = $_POST['profilesexorientation'];

		if (checkdate($_POST['agemonth'],$_POST['ageday'], $_POST['ageyear']))
			$sql = sprintf("UPDATE member_profile SET DOB='%s', IntrestedIn=%d, LookingFor=%d, Hobby='%s', Occupation='%s', Relationship=%d, Height='%s', FavQuotes='%s', AboutMe='%s', BiggestAsset='%s', MyFairyTaleRomance='%s', Location=%d, CountryResidence=%d,  SexOrientation=%d WHERE MemGenID='%s'", $dob, $intrestedin, $lookingfor, $hobby, $occupation, $relationship, $height, $favquotes, $meinmywords, $biggestasset, $fairytale, $location, $countryofresidence, $sexorientation, $_SESSION[SITEUSERAUTHENTICATIONKEY]);
		else
			$sql = sprintf("UPDATE member_profile SET IntrestedIn=%d, LookingFor=%d, Hobby='%s', Occupation='%s', Relationship=%d, Height='%s', FavQuotes='%s', AboutMe='%s', BiggestAsset='%s', MyFairyTaleRomance='%s', Location=%d, CountryResidence=%d, SexOrientation=%d WHERE MemGenID='%s'", $intrestedin, $lookingfor, $hobby, $occupation, $relationship, $height, $favquotes, $meinmywords, $biggestasset, $fairytale, $location, $countryofresidence, $sexorientation, $_SESSION[SITEUSERAUTHENTICATIONKEY]);
		
		$dbConn->query($sql);
		
		//update intrests.
		$intrestedIn = $_POST['intrestedin'];
		if (isset($intrestedIn) && count($intrestedIn) > 0)
		{
			$intrests = "";
			foreach ($intrestedIn as $value)
				$intrests .= $value . ',';
			
			$intrests = substr($intrests, 0, strlen($intrests) - 1);
			
			$sql = sprintf("UPDATE member_profile SET IntrestedIn='%s' WHERE MemGenID='%s'", $intrests, $_SESSION[SITEUSERAUTHENTICATIONKEY]);
			
			$dbConn->query($sql);
		}
	}
	
	header("Location: ../ui/profile.php");
	exit();
?>