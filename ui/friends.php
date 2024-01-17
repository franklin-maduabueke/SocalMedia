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
		
	define('PAGE_NAME', 'friends');
	
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
<title>iwanshokoto.com: Friends</title>
</head>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/globals.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/innerpage.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/landingpage.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>chatplugin/stylesheet/defaultuistyle.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/friends.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/popper.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/dyntooltip.css"/>

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
<script src="<?php echo $pathPrefix;?>scripts/dyntooltip.js" type="text/javascript"></script>
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
                		<div id="rightcoldescription"><label style="color:#333333;">Friends of </label><label style="color:#007DB8"><?php echo $_SESSION['TheUser'];?></label></div>
                    	<div id="rightcoltoolsholder">
                        	<div class="toolcontrolholder">
                            <!--
                            	<div class="toolcontrolitem">
                            		<div class="toolcontrolitemiconholder"><img src="<?php echo $pathPrefix;?>images/paintzoom.png" style="width:30px; height:28px;" /></div>
                                	<div class="toolcontrolitemlinkholder"><a href="#" id="matchnewsearchlink">New Search</a></div>
                                	<div style="clear:both"></div>
                                </div>
                            -->
                                <div style="clear:both"></div>
                            </div>
                        </div>
                		<div style="clear:both"></div>
                	</div>
                    <div style="height:20px;"></div>
                    <?php
						$myFriends = $theMember->getFriends();
						if ($myFriends['fcount'] > 0)
						{
							foreach ($myFriends as $key=>$value)
							{
								if (strcmp($key, "fcount") != 0)
								{
									try
									{
										$friend = new Member($key, $dbConn);
										$friendJson = json_decode($friend->getProfile());
					?>
                    <div class="friendpanel">
                    	<div id="friendthumnailholder">
                        	<div style="height:5px"></div>
                        	<div id="friendthumnailimgholder">
                            	<a href="reqview.php?iwsid=<?php echo $friendJson->memgenid;?>" style="text-decoration:none; color:#FFF;">
									<?php
                                        if ($memPic = $friend->getMemberPic(105, NULL, 100, true))
                                        {
                                            $memPic = str_replace("fetchmemberpic.php?", "matchfetchpic.php?iwsid=" . $friendJson->memgenid . "&", $memPic);
                                            echo $memPic;
                                        }
                                        else
                                        {
                                            if (strcmp($friendJson->gender, "MALE") == 0)
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
                        <div id="frienddetailsholder">
                        	<div style="height:3px;"></div>
                        	<div style="color:#007DB8;"><?php echo $friendJson->username;?></div>
                            <div><label style="font-size:11px; color:#E62174">Age:</label> <?php echo date("Y") - date("Y", strtotime($friendJson->dob));?>yrs</div>
                            <div><label style="font-size:11px; color:#E62174">Relationship:</label> <?php echo $friendJson->relationship;?></div>
                            <div><label style="font-size:11px; color:#E62174">Sexual Orientation:</label> <?php echo ucfirst(strtolower($friendJson->sexorientation));?></div>
                            <div>
                            	<label style="font-size:11px; color:#E62174">Last seen:</label> 
								<?php 
									$lastLogin = date("jS M, Y", $friendJson->lastlogin);
									if (strcmp($lastLogin, date("jS M, Y")) == 0)
										echo "Today @ " . date("g:i a", $friendJson->lastlogin);
									else
									{
									}
								?>
                            </div>
                            <div style="margin-top:10px;"><label style="font-size:11px; color:#E62174">Ratings:</label> <?php echo "";?></div>
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
						}
					?>
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
            
            <div style="clear:both; height:20px;"></div>
        </div>
		
        <div style="height:44px"></div>
	</div>
    <?php include($pathPrefix . "includes/footer.php"); ?>
</div>
</body>
</html>