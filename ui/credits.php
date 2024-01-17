<?php
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../includes/commons.php");
	include("../includes/user_checker.php");
	
	if (!userSessionGood(SITEUSERAUTHENTICATIONKEY))
		header("Location: ../processing/logout.php");
	else
		$sessionGood = TRUE;
	
	define('PAGE_NAME', 'credits');
	
	$pathPrefix = "../";
	
	$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);
	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
	{
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>iwanshokoto. Profile</title>
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
<script src="<?php echo $pathPrefix?>scripts/jquery.easing.1.3.js" type="text/javascript"></script>
<script src="<?php echo $pathPrefix?>scripts/jquery.mousewheel.min.js" type="text/javascript"></script>
<script src="<?php echo $pathPrefix?>scripts/jquery.mCustomScrollbar.js" type="text/javascript"></script>
<script src="<?php echo $pathPrefix?>chatplugin/scripts/UIFeel.js" type="text/javascript"></script>
<script src="<?php echo $pathPrefix?>scripts/global.js" type="text/javascript"></script>

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
                		<div id="rightcoldescription"><label style="color:#333333;">Gallery of </label><label style="color:#007DB8">dueal21</label></div>
                    	<div id="rightcoltoolsholder">
                        	<div class="toolcontrolholder">
                            
                            	<div class="toolcontrolitem">
                            		<div class="toolcontrolitemiconholder"><img src="<?php echo $pathPrefix;?>images/createalbumicon.png" /></div>
                                	<div class="toolcontrolitemlinkholder"><a href="#">Create an album</a></div>
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
                    	<div class="galleryalbumcontainer">
                        	<div class="galleryalbumfoldtop">
                            	<div class="gafold"></div>
                                <div style="clear:both"></div>
                            </div>
                            <div style="clear:both"></div>
                            <div class="galleryalbumfoldmain">
                            	<div style="height:10px;"></div>
                            	<div class="galleryalbumsampleholder">
                                	<div style="height:6px;"></div>
                                	<div class="galleryalbumsampleimageholder">
                                    	<img src="../images/sexybabe.jpg" />
                                    </div>
                                </div>
                            </div>
                            <div class="gaftoolspanel">
                            	<img src="../images/trash.png" />
                                <div><a href="#">Delete</a></div>
                                <div style="clear:both"></div>
                            </div>
                        </div>
                        <!-- album icon end -->
                        <div style="clear:both"></div>
                    </div>
                    
                    <div id="pageinatepanel">
                    	<div class="pageinatebutton pageavtive">
                        	<div style="height:6px"></div>
                        	<div><a href="#">1</a></div>
                        </div>
                        
                        <div class="pageinatebutton pageinavtive">
                        	<div style="height:6px"></div>
                        	<div><a href="#">2</a></div>
                        </div>
                        
                        <div class="pageinatebutton pageinavtive">
                        	<div style="height:6px"></div>
                        	<div><a href="#">3</a></div>
                        </div>
                        
                        <div class="pageinatebutton pageinavtive">
                        	<div style="height:6px"></div>
                        	<div><a href="#">4</a></div>
                        </div>
                        
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