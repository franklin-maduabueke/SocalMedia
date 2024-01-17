<?php
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../includes/commons.php");
	include("../includes/user_checker.php");
	include("../includes/encodeDecodeKey.php");
	include("../classes/cmember.php");
	
	if (!userSessionGood(SITEUSERAUTHENTICATIONKEY))
		header("Location: ../processing/logout.php");
	else
		$sessionGood = TRUE;
		
	define('PAGE_NAME', 'mygallery');
	
	$pathPrefix = "../";
	
	$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);
	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
	{
		//create member instance
		try
		{
			$theMember = new Member($_SESSION[SITEUSERAUTHENTICATIONKEY], $dbConn);
			$member = json_decode($theMember->getProfile());
			if (!member)
			{
				header("Location: ../processing/logout.php");
				exit();
			}
		}
		catch (Exception $ex)
		{
			header("Location: ../processing/logout.php"); //redirect to error screen
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>iwanshokoto. Gallery of <?php echo $_SESSION['TheUser'];?></title>
</head>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/globals.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/innerpage.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/gallery.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/popper.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>chatplugin/stylesheet/defaultuistyle.css"/>
<!-- link stylsheet files for dialogs-->
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/dialogs/uploadimgdialog.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/dialogs/paintapp.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/dyntooltip.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>lightbox/lightbox.css"/>

<script src="<?php echo $pathPrefix;?>scripts/jQuery1_7.js" type="text/javascript"></script>
<script src="<?php echo $pathPrefix?>scripts/popper.js" type="text/javascript"></script>
<script src="<?php echo $pathPrefix;?>scripts/mphcsummarybox.js" type="text/javascript"></script>
<script type="text/javascript">
	var pagePathPrefix = '<?php echo $pathPrefix?>';
</script>
<script src="<?php echo $pathPrefix?>scripts/jquery1.8-ui.min.js" type="text/javascript"></script>
<script src="<?php echo $pathPrefix?>scripts/jquery.easing.1.3.js" type="text/javascript"></script>
<script src="<?php echo $pathPrefix?>scripts/jquery.mousewheel.min.js" type="text/javascript"></script>
<script src="<?php echo $pathPrefix?>scripts/jquery.mCustomScrollbar.js" type="text/javascript"></script>
<script src="<?php echo $pathPrefix?>chatplugin/scripts/UIFeel.js" type="text/javascript"></script>
<script src="<?php echo $pathPrefix?>scripts/tooltip.js" type="text/javascript"></script>
<script src="<?php echo $pathPrefix?>scripts/gallery.js" type="text/javascript"></script>
<script src="<?php echo $pathPrefix;?>scripts/dyntooltip.js" type="text/javascript"></script>
<script src="<?php echo $pathPrefix?>scripts/global.js" type="text/javascript"></script>

<script type="text/javascript" src="<?php echo $pathPrefix;?>lightbox/jquery.lightbox-0.5.js"></script>
<script type="text/javascript">
    $(function() {
        $('.galleria a').lightBox();
    });
</script>
<body style="background-color:#F0F0F0;">
<div id="pageContainer" align="center" style="background-color:#F0F0F0;">
	<?php include($pathPrefix . "includes/header.php"); ?>
    
    <?php include($pathPrefix . "includes/toolspanel.php"); ?>
	<div id="mainContentHolder">
        <div style="height:28px"></div>
        
        <div id="mainPageContentArea">
        	<div style="height:9px;"></div>
        	<div id="mainLeftCol">
            	<?php include($pathPrefix . "includes/membershortprofile.php");?>
                <div style="height:23px;"></div>
            	<?php include($pathPrefix . "includes/inpageleftctrlpanel.php");?>
                <div style="height:30px;"></div>
                <?php include($pathPrefix . "includes/toppeekers.php");?>
            </div>
            
            <div id="mainRightCol">
            	<div id="rightcolcontentholder">
            		<div id="rightcolheading">
                		<div id="rightcoldescription"><label style="color:#333333;">Gallery of </label><label style="color:#007DB8"><?php echo $_SESSION['TheUser'];?></label></div>
                    	<div id="rightcoltoolsholder">
                        	<div class="toolcontrolholder">
                            
                            	<div class="toolcontrolitem">
                            		<div class="toolcontrolitemiconholder"><img src="<?php echo $pathPrefix;?>images/createalbumicon.png" /></div>
                                	<div class="toolcontrolitemlinkholder"><?php if (!isset($_GET['tsk'])) { ?><a href="#" id="createanalbumlink">Create an album</a><?php } ?><?php if (isset($_GET['tsk']) && strcasecmp($_GET['tsk'], 'view') == 0) { ?><a href="#" id="uploadphototoalbumlink">Upload Photo</a> <label id="gphotouploadProgresssIndicator" style="font-size:10px; color:#CCC; display:none;">Uploading: Progress 0%</label><?php } ?></div>
                                	<div style="clear:both"></div>
                                </div>
                                
                                <div style="clear:both"></div>
                            </div>
                        </div>
                		<div style="clear:both"></div>
                	</div>
                    <div style="height:20px;"></div>
                    
                    <div id="gallerycontainer">
                    	<!-- album icon start -->
                        <?php
							if (isset($theMember))
							{
								if (!isset($_GET['tsk']))
								{
									$albums = $theMember->getAlbumCollection();
									if ($count = count($albums))
									{
										$pageSize = 12;
										$currentPage = (isset($_GET['pg']) && is_numeric($_GET['pg'])) ? $_GET['pg'] : 1;
										$offset = $pageSize * ($currentPage - 1);
										$totalPages = ($count % $pageSize == 0) ? ($count / $pageSize) : (((int)($count / $pageSize)) + 1);
										
										$drawCount = 0;
										foreach ($albums as $key=>$value)
										{
											if ($drawCount == $pageSize)
												break;
												
											$json = json_decode($value);
											if ($json && property_exists($json, "albumgenid"))
											{
												//get album cover
												$albumPhotos = $theMember->getAlbumPhotoID($json->albumgenid, 1);
						?>
                    	<div class="galleryalbumcontainer" <?php if ($drawCount == 0 || ($drawCount % 4) == 0) echo 'style="margin-left:0px;"';?>>
                        	<div class="galleryalbumfoldtop">
                            	<div class="gafold"></div>
                                <div style="clear:both"></div>
                            </div>
                            <div style="clear:both"></div>
                            <div class="galleryalbumfoldmain clickable">
                            	<div style="height:10px;"></div>
                            	<div class="galleryalbumsampleholder">
                                	<div style="height:6px;"></div>
                                	<div class="galleryalbumsampleimageholder">
                                    	<?php
											if (count($albumPhotos) > 0)
											{
												$keys = array_keys($albumPhotos);
												$photoJson = json_decode($albumPhotos[$keys[0]]);
										?>
                                        <img src="<?php echo $pathPrefix;?>processing/fetchalbumphoto.php?alb=<?php echo encodeKey($json->albumgenid);?>&photoId=<?php echo encodeKey($photoJson->photogenid);?>&decode=true&q=40&h=150" style="width:auto; height:auto;" />
                                        <?php
											}
											else
											{
										?>
                                    	<img src="../images/sexybabe.jpg" />
                                        <?php
											}
										?>
                                    </div>
                                </div>
                                <input type="hidden" class="dyntooltip:gallerytooltip" value='<label style="color:#007DB8">Album title:</label> <?php echo $json->title;?><br/><label style="color:#007DB8">Created:</label> <?php echo $json->datecreated;?>' />
                            </div>
                            <div class="gaftoolspanel">
                            	<img src="../images/trash.png" />
                                <div><a href="<?php echo $pathPrefix;?>processing/deleteAlbum.php?alid=<?php echo encodeKey($json->albumgenid); ?>" class="deleteAlbumAction">Delete</a> <a href="gallery.php?alid=<?php echo encodeKey($json->albumgenid); ?>&tsk=view" class="viewAction">View</a></div>
                                <div style="clear:both"></div>
                            </div>
                        </div>
                        <?php
												$drawCount++;
											}
										}
									}
								}
								elseif (isset($_GET['alid'], $_GET['tsk']) && strcasecmp($_GET['tsk'], 'view') == 0)
								{
									//get this albums details.
									$albums = $theMember->getAlbumCollection();
									if (count($albums) && array_key_exists(decodeKey($_GET['alid']), $albums))
									{
										$albumJson = $albums[decodeKey($_GET['alid'])];
										$albumJson = json_decode($albumJson);
										if ($albumJson && property_exists($albumJson, 'title'))
										{
									?>
									<div style="text-align:left; font-size:20px; color:#007DB8; font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-weight:bold; margin-bottom:20px;"><a href="gallery.php?alid=<?php echo $_GET['alid'];?>" style="color:#007DB8; font-size:20px; text-decoration:none;">Album</a>: <label style="color:#333333;"><?php echo $albumJson->title;?></label><input type="hidden" id="galleryCurrentAlbumID" value="<?php echo $_GET['alid'];?>" /></div>
										<?php
											$albumPhotos = $theMember->getAlbumPhotoID($albumJson->albumgenid);
											if (count($albumPhotos))
											{
												foreach ($albumPhotos as $key=>$value)
												{
													$json = json_decode($value);
													if ($json && property_exists($json, 'photogenid'))
													{
										?>
									<div class="albumphotoscontentholder">
										<div id="albumphotocontainer">
											<div style="height:7px;"></div>
											<div id="photocontainer" class="galleria">
                                            	<a href="<?php echo $pathPrefix;?>processing/fetchalbumphoto.php?alb=<?php echo $_GET['alid'];?>&photoId=<?php echo encodeKey($json->photogenid);?>&decode=true&q=100&h=200&w=350&respect=true">
                                            		<img src="<?php echo $pathPrefix;?>processing/fetchalbumphoto.php?alb=<?php echo $_GET['alid'];?>&photoId=<?php echo encodeKey($json->photogenid);?>&decode=true&q=65&h=140" />
                                                </a>
											</div>
											<div style="width:136px; color:#333333; font-size:11px; margin-top:3px; text-align:left;"><?php echo $json->uploaddate;?></div>
										</div>
										<div class="gaftoolspanel">
											<img src="../images/trash.png" />
											<div><a href="<?php echo $pathPrefix;?>processing/deleteAlbumPhoto.php?alid=<?php echo $_GET['alid']; ?>&pid=<?php echo encodeKey($json->photogenid); ?>" class="deleteAlbumPhotoAction">Delete</a> <a href="gallery.php?alid=<?php echo encodeKey($json->albumgenid); ?>&tsk=view" class="viewAction">View</a></div>
											<div style="clear:both"></div>
										</div>
									</div>
									<?php
													}
												}
											}
										}
									}
								}
							}
						?>
                        <!-- album icon end -->
                        <div style="clear:both"></div>
                    </div>
                    
                    <div id="pageinatepanel">
                    	<?php
							if (isset($pageSize) && $pageSize > 0)
							{
								for ($i = 1; $i <= $totalPages; $i++)
								{
						?>
                    	<div class="pageinatebutton <?php if ($i == $currentPage) echo "pageavtive";?>">
                        	<div style="height:6px"></div>
                        	<div><a href="#"><?php echo $i;?></a></div>
                        </div>
                        <?php
								}
							}
						?>
                    	<div style="clear:both"></div>
                    </div>
                </div>
            </div>
            
            <div style="clear:both; height:20px;"></div>
        </div>
        
        <div style="height:44px"></div>
	</div>
    <?php include($pathPrefix . "includes/footer.php"); ?>
</div>
</body>
</html>