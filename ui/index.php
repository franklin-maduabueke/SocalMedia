<?php
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../includes/commons.php");
	include("../includes/user_checker.php");
	include("../classes/ciwanshokoto.php");
	include("../classes/cmember.php");
	
	if (!userSessionGood(SITEUSERAUTHENTICATIONKEY))
		header("Location: ../processing/logout.php");
	else
		$sessionGood = TRUE;
		
	define('PAGE_NAME', 'userhome');
	
	$pathPrefix = "../";
	
	$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);
	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
	{
		try
		{
			$iwanshokoto = new IWanShokoto($dbConn);
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
<script src="<?php echo $pathPrefix?>scripts/userhome.js" type="text/javascript"></script>

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
                		<div id="rightcoldescription"><label style="color:#333333;">H<label style="color:#D10179">i</label> </label><label style="color:#007DB8"><?php echo $_SESSION['TheUser'];?></label></div>
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
						//incomplete profile page panel
						if (!$iwanshokoto->isMemberProfileComplete($_SESSION[SITEUSERAUTHENTICATIONKEY]))
						{
					?>
                    <div id="incompleteprofilepanel">
                    	<div style="height:13px;"></div>
                    	<div id="memshortavatar" style="background-color:#FFF; margin-left:10px;">
                            <a href="profile.php" style="color:#FFF;">
                            <?php
                                if ($memPic = $theMember->getMemberPic(52, NULL, 100, true))
                                    echo $memPic;
                                else
                                {
                                    if (strcmp($member->gender, "MALE") == 0)
                                    {
                            ?>
                            <img src="<?php echo $pathPrefix;?>images/duealsmall.jpg" />
                            <?php
                                    }
                                    else
                                    {
                                        ?>
                            <img src="<?php echo $pathPrefix;?>images/suckerfree.gif" style="width:54px; height:51px;" />
                                        <?php
                                    }
                                }
                            ?>
                            </a>
                        </div>
                        <div id="description">
                        	Hi <b><?php echo $_SESSION['TheUser'];?></b>, your profile is incomplete and you will need to complete it
so that anyone can find you here.
							<br/>
                            <a href="profile.php" style="color:#339900;">click to complete profile</a>
                        </div>
                        <div style="clear:both"></div>
                    </div>
                    <?php
						}
					?>
                    
                    <?php
						//board for friend request
						$friendRequests = $theMember->getFriendRequests();
						if ($friendRequests['rcount'] > 0)
						{
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
							$rtable = $friendRequests['rtable'];
							$rtable = explode('#', $rtable);
							$i = 1;
							foreach ($rtable as $value)
							{
								if ($i == 5)
									break;
									
								if (!empty($value))
								{
									$reqJson = json_decode($value);
									if ($reqJson &&  property_exists($reqJson, "memgenid"))
									{
										try
										{
											$reqMember = new Member($reqJson->memgenid, $dbConn);
											$reqmemberprofile = $reqMember->getProfile();
											$reqmpJson = json_decode($reqmemberprofile);
										}
										catch (Exception $ex)
										{
											continue;
										}
						?>
                        <div id="membershortprofileholder" style="width:250px; <?php if (($i % 2) == 0) echo "float:right"; else echo "float:left";?>">
                            <div id="memshortavatar">
                            	<a href="reqview.php?iwsid=<?php echo $reqmpJson->memgenid;?>" style="text-decoration:none; color:#FFF;">
                                	<?php
										if ($memPic = $reqMember->getMemberPic(52, NULL, 100, true))
										{
											$memPic = str_replace("fetchmemberpic.php?", "matchfetchpic.php?iwsid=" . $reqmpJson->memgenid . "&", $memPic);
											echo $memPic;
										}
										else
										{
											if (strcmp($reqmpJson->gender, "MALE") == 0)
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
                            <div id="memshortdetails" style="float:left; margin-left:5px; width:185px;">
                                <div style="height:5px"></div>
                                <label><?php echo $reqmpJson->username;?></label>
                                <div style="height:5px"></div>
                                <label><?php echo ucfirst(strtolower($reqmpJson->location));?>, Nigeria</label>
                                <div style="height:5px"></div>
                                <div class="storyboarditemactionholder"><input type="hidden" value="<?php echo $reqmpJson->memgenid;?>" /><a href="<?php echo $pathPrefix;?>processing/acceptfriendreq.php?iwsfid=<?php echo $reqmpJson->memgenid;?>" class="acceptrequestlink">Accept Request</a><label>|</label><a href="#" class="declinelink">Decline</a></div>
                            </div>
                            <div style="clear:both"></div>
                        </div>
                        <?php
									}
								}
								
								if (($i % 2) == 0)
									echo '<div style="clear:both"></div>';
								
								$i++;
							}
						?>
                        <div style="clear:both"></div>
                        <div class="sbbaseactions">
                        	<a href="#">Accept All Requests</a><label>|</label><a href="#">Decline All</a>
                        </div>
                    </div>
                    <?php
						}
					?>
                    
                    <?php
						//meetups request
						
					?>
                    <!--
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
                    </div>-->
                    <?php
						
					?>
                    
                    <?php
						//messages
						$messages = $theMember->getInboxMessages();
						//most recent first
						$messages = array_reverse($messages);
						if (count($messages) > 0)
						{
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
							foreach ($messages as $key=>$value)
							{
								$msgjson = json_decode($value);
								if ($msgjson && property_exists($msgjson, "messageid"))
								{
									try
									{
										$fromMember = new Member($msgjson->from, $dbConn);
										$profileJson = json_decode($fromMember->getProfile());
						?>
                        <div id="membershortprofileholder" style="margin-bottom:20px; height:auto; border-bottom:1px solid #F0F0F0; padding-bottom:8px;">
                            <div id="memshortavatar">
                            	<a href="icontrol.php?iwsid=<?php echo $msgjson->from;?>" style="text-decoration:none; color:#FFF;">
									<?php
                                        if ($memPic = $fromMember->getMemberPic(52, NULL, 100, true))
                                        {
                                            $memPic = str_replace("fetchmemberpic.php?", "matchfetchpic.php?iwsid=" . $msgjson->from . "&", $memPic);
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
                            <div id="memshortdetails" style="float:left; margin-left:5px; width:588px; height:auto;">
                            	<input type="hidden" value="<?php echo $msgjson->messageid;?>" id="msgid" />
                                <div style="height:2px"></div>
                                <label><?php echo $profileJson->username . " (" . substr(strtolower($profileJson->gender), 0, 1) . ")";?></label>
                                <div style="height:5px"></div>
                                <div id="storyboardmessage" style="color:#666666; font-size:12px; font-weight:normal;">
                                	<label id="messageshort"><?php echo substr($msgjson->message, 0, 200); if (strlen($msgjson->message) > 200) echo "...";?></label>
                                	<label style="display:none;" id="messagefull"><?php echo $msgjson->message;?></label>
                                </div>
                                <div style="height:5px"></div>
                                <div class="storyboarditemactionholder">
                                	<img src="<?php echo $pathPrefix;?>images/lightbox-ico-loading.gif" style="display:none;" />
									<?php if (strlen($msgjson->message) > 200) { ?><a href="#" class="msgreadmorelink">Read More &raquo;</a><label>|<?php } ?></label><a href="#" class="msgdeletelink">Delete</a>
                                </div>
                            </div>
                            <div style="clear:both"></div>
                        </div>
                        <?php
									}
									catch (Exception $ex)
									{
										continue;
									}
								}
							}
						?>
                        <div style="clear:both"></div>
                        <div class="sbbaseactions">
                        	<a href="#">Delete All</a>
                        </div>
                    </div>
                    <?php
						}
					?>
                    
                    <?php
						//list of iwanshokoto members
						$collection = $iwanshokoto->getMembersForShow();
						if (count($collection))
						{
					?>
                    <div style="height:20px;"></div>
                    <div class="storyboardpanel">
                    	<div id="heading">
                        	<div id="hlabel">
                            	<div style="float:left; margin-right:10px;"><img src="<?php echo $pathPrefix;?>images/naughtymeicon.png" /></div>
                                <div style="float:left;">People you'll like:</div>
                                <div style="clear:both"></div>
                            </div>
                        	<div style="clear:both"></div>
                        </div>
                        <div style="clear:both"></div>
                        <div id="peoplelikelistpanel">
                        	<div style="height:10px;"></div>
                             <?php
							foreach ($collection as $key=>$value)
							{
								if ($key == $_SESSION[SITEUSERAUTHENTICATIONKEY])
									continue;
									
								try
								{
									$member = new Member($key, $dbConn);
									$mJson = json_decode($member->getProfile());
									if ($mJson && property_exists($mJson, "memgenid"))
									{
							?>
                        	<div class="personcontentholder">
                            	<div id="thumbnailholder">
                                	<a href="icontrol.php?iwsid=<?php echo $mJson->memgenid;?>" style="text-decoration:none; color:#FFF;">
										<?php
                                            if ($memPic = $member->getMemberPic(110, NULL, 100, true))
                                            {
                                                $memPic = str_replace("fetchmemberpic.php?", "matchfetchpic.php?iwsid=" . $mJson->memgenid . "&", $memPic);
                                                echo $memPic;
                                            }
                                            else
                                            {
                                                if (strcmp($mJson->gender, "MALE") == 0)
                                                {
                                        ?>
                                        <img src="<?php echo $pathPrefix;?>images/duealbig.jpg" style="font-size:100%; height:100%" />
                                        <?php
                                                }
                                                else
                                                {
                                                    ?>
                                        <img src="<?php echo $pathPrefix;?>images/sweetladybig.jpg" style="font-size:100%; height:100%" />
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </a>
                                </div>
                                <div style="text-align:left; font-size:11px; font-weight:bold; color:#000; margin-top:5px;">
                                	<div><?php echo $mJson->username;?><label style="color:#E62174;">(<?php echo strtolower(substr($mJson->gender, 0, 1)) ?>)</label></div>
                                    <div><?php echo date("Y") - date("Y", strtotime($mJson->dob)); ?>yrs</div>
                                    <div>
										<?php echo ucfirst(strtolower($mJson->location));?>
                                        <?php
											if (time() <= $value)
											{
										?>
                                        <img src="<?php echo $pathPrefix;?>images/onlinedot.png" style="margin-left:5px;" />
                                        <?php
											}
											else
											{
										?>
                                        <img src="<?php echo $pathPrefix;?>images/offlinedot.png" style="margin-left:5px;" />
                                        <?php
											}
										?>
                                    </div>
                                </div>
                            </div>
                            <?php
									}
								}
								catch (Exception $ex)
								{
									continue;
								}
							}
							?>
                            <div style="clear:both"></div>
                        </div>
                    </div>
                    <?php
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