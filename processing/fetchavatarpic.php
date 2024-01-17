<?php
	//script to get the photo from database and pass it to the browser image element
	include("../modules/mysqli.php");
	include("../includes/commons.php");
	include("../configs/db.php");
	include("../includes/imageoptimize.php");
	include("../includes/encodeDecodeKey.php");
	include("../includes/user_checker.php");
	
	if (!userSessionGood(SITEUSERAUTHENTICATIONKEY))
	{
		header("Location: logout.php");
		exit();
	}
	
	$avatar = $_GET['avatar'];
	$quality = $_GET['q'];
	$scaletofit = $_GET['fit'];
	$width = $_GET['w'];
	$height = $_GET['h'];
	$decode = $_GET['decode'];
	
	$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);	
	if (isset($avatar) && $dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
	{
		if (isset($decode) && $decode == "true")
			$avatar = decodeKey($avatar);
			
		$sql = sprintf("SELECT AvatarPic AS Photo, PicFormat AS ImageFormat FROM avatars WHERE AvatarGenID='%s'", $avatar);
		$photoResult = $dbConn->query($sql);
		if ($photoResult && $photoResult->num_rows > 0)
		{
			$photoRow = $photoResult->fetch_array();
			if (!empty($photoRow['ImageFormat']))
			{
				$imgOptimize = new ImageOptimize();
				$imageHnd = imagecreatefromstring($photoRow['Photo']);

				if ($imgOptimize->loadFromString($photoRow['Photo']))
				{	
					switch ($photoRow['ImageFormat'])
					{
					case "jpg":
						// Set the content type header - in this case image/jpeg
						header('Content-type: image/jpeg');
						if (isset($width) && is_numeric($width))
							$imgOptimize->resizeToWidth($width);
						elseif (isset($height) && is_numeric($height))
							$imgOptimize->resizeToHeight($height);
							
						$imgOptimize->output();
					break;
					case "png":
						// Set the content type header - in this case image/jpeg
						header('Content-type: image/png');
						if (isset($quality) && is_numeric($quality))
						{
							imagepng($imageHnd, NULL, $quality);
						}
						else
							imagepng($imageHnd);
					break;
					case "bmp":
						// Set the content type header - in this case image/jpeg
						header('Content-type: image/wbmp');
						if (isset($quality) && is_numeric($quality))
							imagewbmp($imageHnd, NULL, $quality);
						else
							imagewbmp($imageHnd);
					break;
					case "gif":
						// Set the content type header - in this case image/jpeg
						header('Content-type: image/gif');
						if (isset($quality) && is_numeric($quality))
							imagegif($imageHnd, NULL, $quality);
						else
							imagegif($imageHnd);
					break;
					}
				}
			}
		}
	}
	
	flush();
	
	if ($imageHnd)
		imagedestroy($imageHnd);
	exit();
?>