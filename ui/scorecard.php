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
		
	define('PAGE_NAME', 'myscorecard');
	
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
<title>iwanshokoto. Score card of <?php echo $_SESSION['TheUser'];?></title>
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
                		<div id="rightcoldescription" style="width:auto;"><label style="color:#333333;">Score card of</label> <label style="color:#007DB8"><?php echo $_SESSION['TheUser'];?></label></div>
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
									$myratings = $theMember->getMyRatings();
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
						}
					?>
                    
                    <div style="height:30px;"></div>
                    <?php
						$ratings = $theMember->getMyRatings();
						if (count($ratings))
						{
					?>
                    <div id="judgesholder">
                    	<div style="float:left; width:190px; height:37px; background-color:#E62174; color:#FFF; font-weight:bold; font-size:13px; text-align:left;">
                        	<div style="height:10px"></div>
                        	<div style="margin-left:20px;">Judges</div>
                        </div>
                        <div style="clear:both; height:20px;"></div>
                        <?php
							foreach ($ratings as $key=>$value)
							{
								try
								{
									$theJudge = new Member($key, $dbConn);
									$theJudgeProfile = json_decode($theJudge->getProfile());
								}
								catch (Exception $ex)
								{
									continue;
								}
						?>
                        <div class="judgepanel">
                        	<div id="memshortavatar" style="background-color:#FFF;">
                                <a href="icontrol.php?iwsid=<?php echo $theJudgeProfile->memgenid;?>" style="color:#FFF;">
                                <?php
                                    if ($memPic = $theJudge->getMemberPic(52, NULL, 100, true))
									{
                                        $memPic = str_replace("fetchmemberpic.php?", "matchfetchpic.php?iwsid=" . $theJudgeProfile->memgenid . "&", $memPic);
                            			echo $memPic;
									}
                                    else
                                    {
                                        if (strcmp($theJudgeProfile->gender, "MALE") == 0)
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
                            	<div>
                                	<div style="float:left; <?php if (strcasecmp($theJudgeProfile->gender, "male") == 0) echo 'color:#007DB8;'; else echo 'color:#E62174'?>"><?php echo $theJudgeProfile->username;?> (<?php echo substr(strtolower($theJudgeProfile->gender), 0, 1);?>)</div>
                                    <div style="float:right;"><a href="score.php?iwsid=<?php echo $theJudgeProfile->memgenid;?>" style="font-size:11px; color:#007DB8;">Score Me!</a></div>
                                    <div style="clear:both"></div>
                                </div>
                                
                                <div id="scoreindicators" style="margin-top:5px;">
                                	<?php
										$ratingTypes = $iwanshokoto->getRatingTypes();
										if (count($ratingTypes))
										{
											$judgeRatings = explode('#', $value);
											if (count($judgeRatings))
											{
												foreach ($judgeRatings as $rating)
												{
													$ratingJson = json_decode($rating);
													if ($ratingJson && property_exists($ratingJson, "memgenid") && array_key_exists($ratingJson->ratedbyid, $ratingTypes))
													{
														$rtypeJson = json_decode($ratingTypes[$ratingJson->ratedbyid]);
														if ($rtypeJson && property_exists($rtypeJson, "ratinggenid"))
														{
									?>
                                	<div style="float:left;">
                                    	<label style="color:#005680;"><?php echo $rtypeJson->ratingname . ":";?></label> <?php echo $ratingJson->score / 10 * 100 . "%";?>
                                    </div>
                                    <?php
														}
													}
												}
											}
										}
									?>
                                    <div style="clear:both"></div>
                                </div>
                            </div>
                            <div style="clear:both"></div>
                        </div>
                        <?php
							}
						?>
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