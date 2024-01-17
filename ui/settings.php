<?php
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../includes/commons.php");
	include("../includes/user_checker.php");
	include("../classes/cmember.php");
	
	if (!userSessionGood(SITEUSERAUTHENTICATIONKEY))
		header("Location: ../processing/logout.php");
	else
		$sessionGood = TRUE;
		
	define('PAGE_NAME', 'settings');
	
	$pathPrefix = "../";
	
	$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);
	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
	{
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
<title>iwanshokoto. Home</title>
</head>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/globals.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/innerpage.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/landingpage.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>chatplugin/stylesheet/defaultuistyle.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/dialogs/uploadimgdialog.css"/>

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
<script src="<?php echo $pathPrefix?>scripts/tooltip.js" type="text/javascript"></script>
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
                		<div id="rightcoldescription"><label style="color:#333333;">Story since your last visit </label><!--<label style="color:#007DB8"><?php echo $_SESSION['TheUser'];?></label>--></div>
                    	<div id="rightcoltoolsholder">
                        	<div class="toolcontrolholder">
                            
                            	<div class="toolcontrolitem">
                            		<!--<div class="toolcontrolitemiconholder"><img src="<?php echo $pathPrefix;?>images/editicon.jpg" /><img src="<?php echo $pathPrefix;?>images/sndfriendreq.png" /></div>-->
                                	<!--<div class="toolcontrolitemlinkholder"><a href="#" id="editprofilelink">Edit Profile</a></div>
                                	<div style="clear:both"></div>-->
                                </div>
                                
                                <div style="clear:both"></div>
                            </div>
                        </div>
                		<div style="clear:both"></div>
                	</div>
                    <div style="height:20px;"></div>
                    <?php
						//board for friend request
						
					?>
                    <div class="storyboardpanel">
                    	<div id="heading">
                        	<div id="hlabel">
                            	<div style="float:left; margin-right:10px;"><img src="<?php echo $pathPrefix;?>images/friendreqicon.jpg" /></div>
                                <div style="float:left;">Friend Request</div>
                                <div style="clear:both"></div>
                            </div>
                        	<div style="clear:both"></div>
                        </div>
                        <div style="clear:both"></div>
                        <?php
							for ($i = 1; $i <= 4; $i++)
							{
						?>
                        <div id="membershortprofileholder" style="width:250px; <?php if (($i % 2) == 0) echo "float:right"; else echo "float:left";?>">
                            <div id="memshortavatar"><img src="<?php echo $pathPrefix;?>images/dueal.jpg" /></div>
                            <div id="memshortdetails" style="float:left; margin-left:5px; width:185px;">
                                <div style="height:5px"></div>
                                <label><?php echo $_SESSION['TheUser'];?></label>
                                <div style="height:5px"></div>
                                <label>Lagos, Nigeria</label>
                                <div style="height:5px"></div>
                                <div class="storyboarditemactionholder"><a href="#">Accept Request</a><label>|</label><a href="#">Decline</a></div>
                            </div>
                            <div style="clear:both"></div>
                        </div>
                        <?php
								if (($i % 2) == 0)
									echo '<div style="clear:both"></div>';
							}
						?>
                        <div style="clear:both"></div>
                        <div class="sbbaseactions">
                        	<a href="#">Accept All Requests</a><label>|</label><a href="#">Decline All</a>
                        </div>
                    </div>
                    <?php
						
					?>
                    
                    <?php
						//meetups request
						
					?>
                    <div style="height:10px;"></div>
                    <div class="storyboardpanel">
                    	<div id="heading">
                        	<div id="hlabel">
                            	<div style="float:left; margin-right:10px;"><img src="<?php echo $pathPrefix;?>images/meetupicon.jpg" /></div>
                                <div style="float:left;">Meetups Request</div>
                                <div style="clear:both"></div>
                            </div>
                        	<div style="clear:both"></div>
                        </div>
                        <div style="clear:both"></div>
                        <?php
							for ($i = 1; $i <= 4; $i++)
							{
						?>
                        <div id="membershortprofileholder" style="margin-bottom:10px; width:250px; <?php if (($i % 2) == 0) echo "float:right"; else echo "float:left";?>">
                            <div id="memshortavatar"><img src="<?php echo $pathPrefix;?>images/dueal.jpg" /></div>
                            <div id="memshortdetails" style="float:left; margin-left:5px; width:185px;">
                                <div style="height:5px"></div>
                                <label><?php echo $_SESSION['TheUser'];?></label>
                                <div style="height:5px"></div>
                                <img src="<?php echo $pathPrefix;?>images/meetupicon.jpg" /><label>27th Jun, 2012</label>
                                <div style="height:5px"></div>
                                <div class="storyboarditemactionholder"><a href="#">Accept Request</a><label>|</label><a href="#">Decline</a></div>
                            </div>
                            <div style="clear:both"></div>
                        </div>
                        <?php
								if (($i % 2) == 0)
									echo '<div style="clear:both"></div>';
							}
						?>
                        <div style="clear:both"></div>
                        <div class="sbbaseactions">
                        	<a href="#">Accept All Requests</a><label>|</label><a href="#">Decline All</a>
                        </div>
                    </div>
                    <?php
						
					?>
                    
                    <?php
						//messages
						
					?>
                    <div style="height:10px;"></div>
                    <div class="storyboardpanel">
                    	<div id="heading">
                        	<div id="hlabel">
                            	<div style="float:left; margin-right:10px;"><img src="<?php echo $pathPrefix;?>images/messagesicon.jpg" /></div>
                                <div style="float:left;">Messages</div>
                                <div style="clear:both"></div>
                            </div>
                        	<div style="clear:both"></div>
                        </div>
                        <div style="clear:both"></div>
                        <?php
							for ($i = 1; $i <= 2; $i++)
							{
						?>
                        <div id="membershortprofileholder" style="margin-bottom:20px; height:auto;">
                            <div id="memshortavatar"><img src="<?php echo $pathPrefix;?>images/dueal.jpg" /></div>
                            <div id="memshortdetails" style="float:left; margin-left:5px; width:588px; height:auto;">
                                <div style="height:5px"></div>
                                <label><?php echo $_SESSION['TheUser'];?></label>
                                <div style="height:5px"></div>
                                <div id="storyboardmessage" style="color:#666666; font-size:12px; font-weight:normal;">
                                Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam...
                                </div>
                                <div style="height:5px"></div>
                                <div class="storyboarditemactionholder"><a href="#">Read More &raquo;</a><label>|</label><a href="#">Delete</a></div>
                                
                            </div>
                            <div style="clear:both"></div>
                        </div>
                        <?php
							}
						?>
                        <div style="clear:both"></div>
                        <div class="sbbaseactions">
                        	<a href="#">Delete All</a>
                        </div>
                    </div>
                    <?php
						
					?>
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