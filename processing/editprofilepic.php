<?php
	include("../modules/mysqli.php");
	include("../includes/user_checker.php");
	include("../configs/db.php");
	include("../includes/commons.php");
	include("../includes/generateID.php");
	include("../includes/encodeDecodeKey.php");
	include("../includes/imageoptimize.php");

	if (!userSessionGood(SITEUSERAUTHENTICATIONKEY))
	{
		header("Location: logout.php");
		exit();
	}
	
	//process article publish and uploads
	$uploadtype = $_POST['uploadtype']; //avatar, imgfile
	$avatargenid = $_POST['avatargenid'];
	
	$ini = parse_ini_file("../configs/app.ini", true); //get ini info
	//check image type sent for upload.
	$imageMime = array("image/jpeg"=>"jpg", "image/pjpg"=>"jpg", "image/png"=>"png", "image/bmp"=>"bmp", "image/gif"=>"gif");
	
	//echo "Album id = $albumid<br/>";
	if ($ini && array_key_exists('SETTINGS', $ini) && array_key_exists('MaxUploadImageSize', $ini['SETTINGS']) && isset($uploadtype))
	{
		if (strcmp($uploadtype, "imgfile") == 0 && !isset($_FILES["avatarphotofile"]['name'][0]))
		{
			echo SERVER_RESPONSE_FAILURE;
			exit();
		}
		
		$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);
		if ($dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
		{
			if (strcmp($uploadtype, "imgfile") == 0)
			{
				if (array_key_exists($_FILES['avatarphotofile']['type'][0], $imageMime) && $_FILES['avatarphotofile']['size'][0] <= $ini['SETTINGS']['MaxUploadImageSize'])
				{
					//insert
					//echo "Uploading from $path<br/> Max File size allowed = " . ini_get("upload_max_filesize") . " File size = " . $_FILES['avatarphotofile']['size'][$key];
					
					ini_set("memory_limit", "-1");
					$imageString = file_get_contents($_FILES['avatarphotofile']['tmp_name'][0]);
					if ($imageString)
					{
						//optimize for save
						$imageOptimize = new ImageOptimize();
						if ($imageOptimize->loadFromString($imageString))
						{
							$filename = $_SESSION[SITEUSERAUTHENTICATIONKEY] . ".jpg";
							$imageOptimize->save("../temps/$filename");
							if (file_exists("../temps/$filename"))
							{
								$imageString = $dbConn->real_escape_string(file_get_contents("../temps/$filename"));
								$sql = sprintf("UPDATE member_profile SET Photo='%s', PhotoFormat='%s' WHERE MemGenID='%s'", $imageString, "jpg", $_SESSION[SITEUSERAUTHENTICATIONKEY]);
								
								$dbConn->query($sql);
								@unlink("../temps/$filename");
								
								if ($dbConn->affected_rows > 0)
								{
									echo SERVER_RESPONSE_SUCCESS;
									exit();
								}
							}
						}
					}
				}
			}
			else
			{
				//selecting avatar for profile image
			}
		}
	}
	
	echo SERVER_RESPONSE_FAILURE;
	exit();
?>