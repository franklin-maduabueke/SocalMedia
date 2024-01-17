<?php
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../includes/commons.php");
	include("../includes/user_checker.php");
	include("../classes/cmember.php");
	include("../classes/ciwanshokoto.php");
	
	if (!userSessionGood(SITEUSERAUTHENTICATIONKEY))
		header("Location: ../processing/logout.php");
	else
		$sessionGood = TRUE;
		
	define('PAGE_NAME', 'messages');
	
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
<title>iwanshokoto.com: Messages</title>
</head>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/globals.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/innerpage.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/landingpage.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>chatplugin/stylesheet/defaultuistyle.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/dialogs/uploadimgdialog.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/popper.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/messages.css"/>

<script src="<?php echo $pathPrefix;?>scripts/jQuery1_7.js" type="text/javascript"></script>
<script src="<?php echo $pathPrefix;?>scripts/popper.js" type="text/javascript"></script>
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
<script src="<?php echo $pathPrefix?>scripts/messages.js" type="text/javascript"></script>

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
                		<div id="rightcoldescription"><label style="color:#333333;">Messages for </label><label style="color:#007DB8"><?php echo $_SESSION['TheUser'];?></label></div>
                    	<div id="rightcoltoolsholder">
                        	<div class="toolcontrolholder">
                            
                            	<div class="toolcontrolitem">
                                	<!--
                            		<div class="toolcontrolitemiconholder"><img src="<?php echo $pathPrefix;?>images/paintzoom.png" style="width:30px; height:28px;" /></div>
                                	<div class="toolcontrolitemlinkholder"><a href="#" id="matchnewsearchlink">New Search</a></div>
                                	<div style="clear:both"></div>-->
                                </div>
                                
                                <div style="clear:both"></div>
                            </div>
                        </div>
                		<div style="clear:both"></div>
                	</div>

                    <div id="messagesdirtoolbox">
                    	<?php
							$dir = isset($_GET['dir']) ? $_GET['dir'] : 'inbox';
						?>
                        <input type="hidden" id="messagedir" value="<?php echo $dir;?>" />
                    	<div class="controltab" style="margin-left:15px;<?php if ($dir != "inbox") echo 'background-color:#DEE7EA;';?>">
                        	<div style="height:10px;"></div>
                        	<div id="label"><a href="?dir=inbox" <?php if ($dir != "inbox") echo 'style="color:#FFF"'; ?>>Inbox</a></div>
                        </div>
                        
                        <div class="controltab" <?php if ($dir != "sent") echo 'style="background-color:#DEE7EA;"';?>>
                        	<div style="height:10px;"></div>
                        	<div id="label"><a href="?dir=sent" <?php if ($dir != "sent") echo 'style="color:#FFF"'?>>Sent</a></div>
                        </div>
                    	<div style="clear:both"></div>
                    </div>
                    <?php
						if ($dir == "inbox")
							$messages = $theMember->getInboxMessages();
						elseif ($dir == "sent")
							$messages = $theMember->getSentMessages();
							
						//most recent first
						$messages = array_reverse($messages);
						
						if (count($messages) > 0)
						{
							foreach ($messages as $key=>$value)
							{
								$msgjson = json_decode($value);
								if ($msgjson && property_exists($msgjson, "messageid"))
								{
									try
									{
										$messageOwner = ($dir == "inbox") ? $msgjson->from : $msgjson->to;
										$fromMember = new Member($messageOwner, $dbConn);
										$profileJson = json_decode($fromMember->getProfile());
					?>
                    <div class="messagespanel">
                    	<input type="hidden" value="<?php echo $msgjson->from;?>" id="iwsid" />
                        <input type="hidden" value="<?php echo $msgjson->messageid;?>" id="msgid" />
                    	<div id="thumbnailholder">
                        	<div style="height:5px"></div>
                        	<div id="thumnailimgholder">
                            	<a href="icontrol.php?iwsid=<?php echo $messageOwner;?>" style="text-decoration:none; color:#FFF;">
									<?php
                                        if ($memPic = $fromMember->getMemberPic(105, NULL, 100, true))
                                        {
                                            $memPic = str_replace("fetchmemberpic.php?", "matchfetchpic.php?iwsid=" . $messageOwner . "&", $memPic);
                                            echo $memPic;
                                        }
                                        else
                                        {
                                            if (strcmp($profileJson->gender, "MALE") == 0)
                                            {
                                    ?>
                                    <img src="<?php echo $pathPrefix;?>images/duealsmall.jpg" />
                                    <?php
                                            }
                                            else
                                            {
                                                ?>
                                    <img src="<?php echo $pathPrefix;?>images/sweetladysmall.jpg" />
                                                <?php
                                            }
                                        }
                                    ?>
                                </a>
                            </div>
                        </div>
                        
                        <div id="messagedescription">
                        	<div id="usernameonlinestatus">
                            	<div style="float:left; color:#007DB8; font-size:13px; font-weight:bold; width:auto;"><?php echo $profileJson->username;?> <label style="color:#E62174">(<?php echo date("Y") - date("Y", strtotime($profileJson->dob));?>)</label></div>
                                <div style="float:right; width:auto; font-size:13px; font-weight:bold; color:#007DB8;">
                                	<a href="#" style="color:#009900; font-weight:bold; font-size:13px;">Now Online</a>
                                </div>
                            	<div style="clear:both"></div>
                            </div>
                            <div id="datesent" style="color:#007DB8; font-size:13px; font-weight:bold; margin-top:8px;">
                            	<?php
									if (strcmp(date("Y-m-j", $msgjson->date), date("Y-m-j")) == 0)
									{
										echo 'Today @ ' . date("g:i a", $msgjson->date);
									}
									elseif ((time() - $msgjson->date) <= (3600 * 24))
									{
										echo 'Yesterday @ ' . date("g:i a", $msgjson->date);
									}
									else
									{
										echo date("jS M, Y @ g:i a", $msgjson->date);
									}
                                ?>
                            </div>
                            <div id="messageexcerpt" style="color:#333333; font-size:13px; margin-top:5px;">
                            	<label id="messageshort"><?php echo substr($msgjson->message, 0, 300); if (strlen($msgjson->message) > 300) echo "...";?></label>
                                <label id="messagefull" style="display:none;"><?php echo $msgjson->message;?></label>
                            </div>
                        </div>
                    	<div style="clear:both"></div>
                        <div id="messagecontrols">
                        	<img src="<?php echo $pathPrefix;?>images/lightbox-ico-loading.gif" style="width:15px; height:15px; visibility:hidden;" />
                        	<?php if (strlen($msgjson->message) > 300) { ?><a href="#" class="msgreadmorelink">Read More</a><?php } ?>
                            <?php
								if ($dir == "inbox")
								{
							?>
                            <a href="#" class="msgsendlink" style="visibility:hidden;">Send</a>
                            <a href="#" class="msgreplylink">Reply</a>
                            <?php
								}
							?>
                            <a href="#" class="msgdeletelink">Delete</a>
                        </div>
                    </div>
                    <?php
									}
									catch (Exception $ex)
									{
										continue;
									}
								}
							}
						}
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