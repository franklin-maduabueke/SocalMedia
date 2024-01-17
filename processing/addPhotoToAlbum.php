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
	$albumid = $_POST['albumid'];
	
	$ini = parse_ini_file("../configs/app.ini", true); //get ini info
	//check image type sent for upload.
	$imageMime = array("image/jpeg"=>"jpg", "image/pjpg"=>"jpg", "image/png"=>"png", "image/bmp"=>"bmp", "image/gif"=>"gif");
	
	//echo "Album id = $albumid<br/>";
	if ($ini && array_key_exists('SETTINGS', $ini) && array_key_exists('MaxUploadImageSize', $ini['SETTINGS']) && isset($albumid, $_FILES["albumphotofile"]['name'][0]))
	{
		$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);
		if ($dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
		{
			//echo "Connected<br/>";
			$photoname = $dbConn->real_escape_string($photoname);
			$albumid = decodeKey($albumid);
			
			//check for the album created by this user.
			$sql = sprintf("SELECT AlbumGenID FROM album WHERE MemGenID='%s' AND AlbumGenID='%s'", $_SESSION[SITEUSERAUTHENTICATIONKEY], $albumid);
			
			$result = $dbConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				//echo "Album found <br/>";
				//generate photo Id.
				foreach ($_FILES['albumphotofile']['tmp_name'] as $key=>$path)
				{
					if (array_key_exists($_FILES['albumphotofile']['type'][$key], $imageMime) && $_FILES['albumphotofile']['size'][$key] <= $ini['SETTINGS']['MaxUploadImageSize'])
					{
						for ($j = 0; $j < 20; $j++)
						{
							$genid = generateID(20);
							if ($genid != FALSE)
							{
								$sql = sprintf("SELECT * FROM album_photo WHERE PhotoGenID='%s' AND AlbumGenID='%s'", $genid, $albumid);
								$result = $dbConn->query($sql);
								
								if ($result && $result->num_rows == 0)
								{
									$marked = TRUE;
									break;
								}
							}
						}
						
						if ($marked)
						{
							//i intend on saving photos as zip(compressed) files on the server. or in the db.
							//not implemented yet.
							//insert
							//echo "Uploading from $path<br/> Max File size allowed = " . ini_get("upload_max_filesize") . " File size = " . $_FILES['albumphotofile']['size'][$key];
							
							ini_set("memory_limit", "-1");
							$imageString = file_get_contents($path);
							if ($imageString)
							{
								//optimize for save
								
								$imageOptimize = new ImageOptimize();
								if ($imageOptimize->loadFromString($imageString))
								{
									$imageOptimize->save("../temps/$genid.jpg");
									if (file_exists("../temps/$genid.jpg"))
									{
										$imageString = $dbConn->real_escape_string(file_get_contents("../temps/$genid.jpg"));
										$sql = sprintf("INSERT INTO album_photo (AlbumGenID, PhotoGenID, UploadDate, Photo, PhotoFormat) VALUES('%s', '%s', '%s', '%s', '%s')", $albumid, $genid, time(), $imageString, $imageMime[$_FILES['albumphotofile']['type'][$key]]);
										$dbConn->query($sql);
										@unlink("../temps/$genid.jpg");
										
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
						echo "File too big";
					}
				}
			}
		}
	}
	
	echo SERVER_RESPONSE_FAILURE;
	exit();
?>