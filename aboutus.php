<?php
	include("configs/db.php");
	include("modules/mysqli.php");
	include("includes/commons.php");
	include("includes/user_checker.php");
	
	$sessionGood = userSessionGood(SITEUSERAUTHENTICATIONKEY);
	
	define('PAGE_NAME', 'aboutus');
	
	$pathPrefix = "";
	
	$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);
	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
	{
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>iwanshokoto. About Us</title>
</head>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/globals.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/innerpage.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/gallery.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>chatplugin/stylesheet/defaultuistyle.css"/>

<script src="<?php echo $pathPrefix;?>scripts/jQuery1_7.js" type="text/javascript"></script>
<script src="<?php echo $pathPrefix;?>scripts/mphcsummarybox.js" type="text/javascript"></script>
<script type="text/javascript">
	var pagePathPrefix = '<?php echo $pathPrefix?>';
</script>
<script src="<?php echo $pathPrefix?>scripts/jquery1.8-ui.min.js" type="text/javascript"></script>
<!--<script src="<?php echo $pathPrefix?>scripts/jquery.easing.1.3.js" type="text/javascript"></script>
<script src="<?php echo $pathPrefix?>scripts/jquery.mousewheel.min.js" type="text/javascript"></script>
<script src="<?php echo $pathPrefix?>scripts/jquery.mCustomScrollbar.js" type="text/javascript"></script>
<script src="<?php echo $pathPrefix?>chatplugin/scripts/UIFeel.js" type="text/javascript"></script>
-->
<body style="background-color:#F0F0F0;">
<div id="pageContainer" align="center" style="background-color:#F0F0F0;">
	<?php include($pathPrefix . "includes/header.php"); ?>
	<div id="mainContentHolder">
        <div style="height:28px"></div>
        
        <div id="mainPageContentArea">
        	<div style="height:453px; background-image:url(images/aboutusbg.jpg);">
            	<div style="width:560px; height:453px; float:right;">
                	<img src="images/shhh.png" />
                </div>
                <div style="clear:both"></div>
                <div style="height:453px; position:relative; top:-453px; left:0px;">
                	<div style="height:64px; width:900px; border-bottom:1px solid #F0F0F0">
                    	<div style="height:18px;"></div>
                    	<div style="font-size:26px; color:#E62174; text-align:left; padding-left:10px;">About Us</div>
                    </div>
                    
                    <div style="width:900px; height:auto;">
                    	<div style="float:left; width:590px; height:397px;">
                        	<div style="font-size:13px; color:#333333; text-align:justify; padding-top:10px;">
                            	Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.
<p/>
Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.
<p/>
Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum.
								<?php
									if (!$sessionGood)
									{
								?>
								<div style="font-size:26px; text-align:left; color:#E52073;"><a href="index.php" style="font-size:26px; text-align:left; color:#E52073; text-decoration:none;">Join Us Today...</a></div>
                                <?php
								
									}
								?>
							</div>
                    	</div>
                    	<div style="float:right; width:300px; height:414px; position:relative; z-index:2">
                        	<img src="images/sexydancer.png" />
                    	</div>
                    
                    	<div style="clear:both"></div>
                    </div>
                    <div style="width:900px; height:397px; position:relative; top:-26px; z-index:1px;">
                    	<div style="width:387px; height:397px; position:absolute; text-align:left;">
                        	<div style="height:25px;"></div>
                        	<img src="images/successtext.png" />
                            <div style="height:18px;"></div>
                            <img src="images/happyppl.png" />
                        </div>
                        <div style="width:481px; height:397px; position:absolute; left:420px;">
                        	<div>
                            	<div style="height:25px"></div>
                            	<div  style="width:300px; height:127px; float:left; text-align:left;">
                                	<div style="height:85px"></div>
                                	<div style="font-size:26px; color:#E52073">Chris & Rebecca</div>
                                </div>
                                <div style="width:126px; height:127px; float:right; margin-right:50px;">
                                	<img src="images/strawberry.png" />
                                </div>
                                <div style="clear:both"></div>
                                <div style="text-align:left; font-size:13px">
                                	Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue....<a href="#" style="font-size:10px; color:#E52073; text-decoration:none; font-weight:bold">Read More</a>
                                </div>
                                <div>
                                	<div  style="width:auto; height:auto; float:right; margin-right:20px;"><a href="#" style="text-decoration:none; color:#FFF"><img src="images/fbpillow.png" /></a></div>
                                	<div style="width:auto; height:auto; float:right; font-size:15px; color:#E52073; margin-right:10px; margin-top:20px; font-weight:bold">Follow our story on</div> 
                                </div>
                            </div>
                        </div>
                    	<div style="clear:both"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div style="height:44px"></div>
	</div>
    <div style="clear:both"></div>
    <?php include($pathPrefix . "includes/footer.php"); ?>
</div>
</body>
</html>