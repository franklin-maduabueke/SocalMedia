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
		
	define('PAGE_NAME', 'scoreprofile');
	
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
			
			//who we are scoring
			$theScoredMember = new Member($_GET['iwsid'], $dbConn);
			$scoredMember = json_decode($theScoredMember->getProfile());
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
<title>iwanshokoto. Score <?php echo $scoredMember->username;?></title>
</head>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/globals.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/innerpage.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/scorecard.css"/>
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
<script src="<?php echo $pathPrefix?>scripts/scorecard.js" type="text/javascript"></script>

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
                		<div id="rightcoldescription" style="width:auto;"><label style="color:#333333;">Score </label> <label style="color:#007DB8"><?php echo $scoredMember->username;?></label></div>
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
						$ratingTypes = $iwanshokoto->getRatingTypes();
						if (count($ratingTypes))
						{
							foreach ($ratingTypes as $key=>$value)
							{
								$ratingJson = json_decode($value);
								if ($ratingJson && property_exists($ratingJson, "ratinggenid"))
								{
									$myratings = $theScoredMember->getMyRatings();
									if (count($myratings))
									{
										//get scores for this rating type
										$totalScore = 0;
										$totalJudges = 0;
										foreach ($myratings as $memid=>$ratingstream)
										{
											$ratings = explode('#', $ratingstream);
											if (count($ratings))
											{
												foreach ($ratings as $r)
												{
													$ratingj = json_decode($r);
													if ($ratingj && property_exists($ratingj, "memgenid"))
													{
														if (strcmp($ratingj->ratedbyid, $ratingJson->ratinggenid) == 0)
														{
															$totalScore += $ratingj->score;
															$totalJudges++;
														}
													}
												}
											}
										}
									}
					?>
                    <div class="scoreprogressbarholder" id="<?php echo strtolower(str_replace(" ", "", $ratingJson->ratingname));?>ratingbar">
                    	<div id="scorelabel"><?php echo $ratingJson->ratingname;?>:</div>
                        <div class="scoreprogressbar">
                        	<div style="height:4px;"></div>
                        	<div id="progression"></div>
                        </div>
                        <div id="ratingmark"><?php $percentage = ($totalJudges != 0) ? (floor(($totalScore / $totalJudges) / 10 * 100)) : 0; echo  $percentage;?>%</div>
                        <input type="hidden" value="<?php echo $percentage;?>" id="ratingpercentage" />
                    	<div style="clear:both"></div>
                    </div>
                    <?php
								}
							}
					?>
                    
                    <div style="height:30px;"></div>
                    <form method="post" action="<?php echo $pathPrefix;?>processing/ratemember.php" id="ratingpanelform">
                    	<input type="hidden" name="judgeid" value="<?php echo $_SESSION[SITEUSERAUTHENTICATIONKEY];?>" />
                        <input type="hidden" name="contestantid" value="<?php echo $_GET['iwsid'];?>" />
                    <div id="scoringpanel">
                    	<div style="height:15px"></div>
                        <?php
							foreach ($ratingTypes as $key=>$value)
							{
								$ratingJson = json_decode($value);
								if ($ratingJson && property_exists($ratingJson, "ratinggenid"))
								{
						?>
                    	<div class="ratingwidget" id="<?php echo strtolower(str_replace(" ", "", $ratingJson->ratingname));?>scorepanel">
                        	<div id="label"><?php echo $ratingJson->ratingname;?>:</div>
                            <div id="selectionpanels">
                            	<?php
									for ($i = 0; $i <= 10; $i++)
									{
								?>
                            	<div class="scaleitem" <?php if ($i == 10) echo 'style="margin-right:0px;"';?>>
                                	<div style="height:5px;"></div>
                                	<input type="radio" name="point<?php echo $ratingJson->ratinggenid;?>" value="<?php echo $i;?>" />
                                    <div style="margin-top:5px;"><?php echo $i;?></div>
                                </div>
                                <?php
									}
								?>
                                <div style="clear:both"></div>
                            </div>
                        	<div style="clear:both"></div>
                        </div>
                        <?php
								}
							}
						?>
                        <div>
                        	<div id="submitratings" class="clickable" style="float:right; width:121px; height:39px; background-color:#E62174; color:#FFF; font-size:12px; margin-right:20px; font-weight:bold;">
                            	<div style="height:10px;"></div>
                            	<div>COMPLETE</div>
                            </div>
                            <div style="clear:both"></div>
                        </div>
                    </div>
                    </form>
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