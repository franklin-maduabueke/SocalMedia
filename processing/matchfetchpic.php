<?php
	//script to get the photo from database and pass it to the browser image element
	include("../modules/mysqli.php");
	include("../includes/commons.php");
	include("../configs/db.php");
	include("../includes/imageoptimize.php");
	include("../includes/encodeDecodeKey.php");
	
	$quality = $_GET['q'];
	$scaletofit = $_GET['fit'];
	$width = $_GET['w'];
	$height = $_GET['h'];
	$decode = $_GET['decode'];
	$respect = $_GET['respect'];
	$memgenid = $_GET['iwsid'];
	
	$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
	{
		$sql = sprintf("SELECT Photo, PhotoFormat AS ImageFormat FROM member_profile WHERE MemGenID='%s'", $memgenid);
		
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
						{
							//see if we should respect the image width. if this is true
							//then we wont adjust the width unless it is larger that the
							//width requested for.
							if (isset($respect) && strcmp($respect, 'true') == 0)
							{
								$imgWidth = $imgOptimize->getWidth();
								if ($imgWidth > $width)
									$imgOptimize->resizeToWidth($width);
							}
							else
								$imgOptimize->resizeToWidth($width);
						}
						elseif (isset($height) && is_numeric($height))
						{
							if (isset($respect) && strcmp($respect, 'true') == 0)
							{
								$imgHeight = $imgOptimize->getHeight();
								if ($imgHeight > $height)
									$imgOptimize->resizeToHeight($height);
							}
							else
								$imgOptimize->resizeToHeight($height);
						}
							
						if (isset($quality) && is_numeric($quality))
						{
							$imgOptimize->output(IMAGETYPE_JPEG, $quality);
						}
						else
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