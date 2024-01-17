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
		
	define('PAGE_NAME', 'onlinemembers');
	
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
			
			
			$intrestAll = ($_POST['allintrestscheck'] == "on") ? TRUE : FALSE;
			$intrest = isset($_POST['intrestedin']) ? $_POST['intrestedin'] : $member->intrest;
			
			if ($intrestAll)
			{
				$intrest = "ANYTHING";
			}
			elseif (isset($intrest) && is_array($intrest))
			{
				$intrestCollection = "";
				foreach ($intrest as $value)
					$intrestCollection .= $value . ",";
				
				$intrest = substr($intrestCollection, 0, strlen($intrestCollection) - 1);
			}
			
			$withPhoto = ($_POST['querywithphoto'] == "on") ? TRUE : FALSE;
			$location = isset($_POST['querylocation']) ? $_POST['querylocation'] : $member->locationid;
			$agerange = isset($_POST['queryagefrom']) ? $_POST['queryagefrom'] . "#" . $_POST['queryageto'] : "18#" . (date("Y") - date("Y", strtotime($member->dob)));
			$showme = isset($_POST['queryshowme']) ? $_POST['queryshowme'] : $member->seeking;
			$matchCollection = $iwanshokoto->findMembersMatch(NULL, $agerange, $location, $intrest, $showme, $withPhoto);
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
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/match.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/popper.css"/>

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
<script src="<?php echo $pathPrefix?>scripts/match.js" type="text/javascript"></script>

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
                		<div id="rightcoldescription">
                        	<label style="color:#333333;">Members currently</label> <label style="color:#33CC00">Online</label>
                           <!--<label style="color:#007DB8"><?php echo $_SESSION['TheUser'];?></label>--></div>
                    	<div id="rightcoltoolsholder">
                        	<div class="toolcontrolholder">
                            
                            	<div class="toolcontrolitem">
                            		<div class="toolcontrolitemiconholder"><img src="<?php echo $pathPrefix;?>images/paintzoom.png" style="width:30px; height:28px;" /></div>
                                	<div class="toolcontrolitemlinkholder"><a href="#" id="matchadvancesearchlink">Advanced Search</a></div>
                                	<div style="clear:both"></div>
                                </div>
                                
                                <div style="clear:both"></div>
                            </div>
                        </div>
                		<div style="clear:both"></div>
                	</div>
                    
                    <div>
                    	<div id="matchsearchpanel" style="height:auto; background-color:#FFF; border-bottom:1px solid #CCC; width:95%; margin-bottom:20px;"><form method="post" action="match.php" id="findyourmatchform"><div id="mspanelheading"><img id="findmatchimage" /></div>
							
                            <div style="text-align:left; margin-top:20px;">
                            	<label>Show me</label> 
                                <select name="queryshowme" id="queryshowme" style="width:100px;">
                                    <option value="0" <?php if ($_POST['queryshowme'] == 0) echo 'selected="selected"';?>>Both</option>
                                    <option value="1" <?php if ($_POST['queryshowme'] == 1) echo 'selected="selected"';?>>Men</option>
                                    <option value="2" <?php if ($_POST['queryshowme'] == 2) echo 'selected="selected"';?>>Women</option>
                                </select>
                                <label style="margin-left:10px;">Location</label>
                                <select name="querylocation" id="querylocation" style="width:150px;">
                                    <option value="0">ALL STATES</option>
                                    <option value="-1" disabled="disabled">---------------------</option>
                                    <?php
                                        $sql = "SELECT LTID, StateName FROM locations ORDER BY StateName";
                                        $result = $dbConn->query($sql);
                                        if ($result && $result->num_rows > 0)
                                        {
                                            for (; $row = $result->fetch_array();)
                                            {
                                    ?>
                                    <option value="<?php echo $row['LTID'];?>" <?php if (isset($_POST['querylocation']) && $_POST['querylocation'] == $row['LTID']) echo 'selected="selected"';?>><?php echo $row['StateName'];?></option>
                                    <?php
                                            }
                                        }
                                    ?>
                                </select>
                            	<label style="font-family:Verdana, Arial, Helvetica, sans-serif;">ages</label>
                                <select name="queryagefrom" id="queryagefrom" style="width:70px;">
                                    <?php
                                        for ($i = 18; $i <= 100; $i++)
                                        {
                                    ?>
                                    <option value="<?php echo $i;?>" <?php if (isset($_POST['queryagefrom']) && $_POST['queryagefrom'] == $i) echo 'selected="selected"';?>><?php echo $i;?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                                <label style="font-family:Verdana, Arial, Helvetica, sans-serif;">to</label>
                                <select name="queryageto" id="queryageto" style="width:70px;">
                                    <?php
                                        for ($i = 18; $i <= 100; $i++)
                                        {
                                    ?>
                                    <option value="<?php echo $i;?>" <?php if (isset($_POST['queryageto']) && $_POST['queryageto'] == $i) echo 'selected="selected"';?>><?php echo $i;?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                            <div style="text-align:left; border:1px solid #CCC; padding:10px; margin-top:10px; border-radius:5px; -moz-border-radius:5px; -webkit-border-radius:5px; -khtml-border-radius:5px; -ms-border-radius:5px; -o-border-radius:5px;" id="intrestscollectionholder">
                            	<div style="font-size:15px; font-weight:bold; color:#007DB8; margin-bottom:10px;">Intrested in</div>
                                	<input type="checkbox" id="allintrestscheck" name="allintrestscheck" /> <label style="font-size:11px; color:#007DB8">Anything</label>
                            	<?php
									$intrests = $iwanshokoto->getIntrests();
									
									if (count($intrests) > 0)
									{
										foreach ($intrests as $key=>$value)
										{
											$intrestJson = json_decode($value);
											if ($intrestJson && property_exists($intrestJson, "intrestgenid"))
											{
								?>
                                 	<input type="checkbox" id="<?php strtolower(str_replace(" ", "", $intrestJson->intrestname));?>" name="intrestedin[]" value="<?php echo $intrestJson->intrestgenid;?>"  <?php if (!is_bool(strpos($intrest, $intrestJson->intrestgenid))) echo 'checked="checked"'; ?> /> <label style="font-size:11px; color:#007DB8"><?php echo $intrestJson->intrestname;?></label>
                                <?php
											}
										}
									}
								?>
                                <div style="clear:both"></div>
                            </div>
                            <div style="text-align:right; padding-right:5px; text-align:left; margin-left:5px; margin-top:10px;"><label>with photo</label> <input type="checkbox" name="querywithphoto" id="querywithphoto" <?php if ($withPhoto) echo 'checked="checked"';?> /></div>
							<div style="text-align:right; margin-right:10px; margin-top:20px;" id="startsearchmatchbtn"><!--<img  id="searchclosebtnimg" src="<?php echo $pathPrefix;?>images/closepanelbtn.png" class="clickable" />--> <img src="<?php echo $pathPrefix;?>images/searchbtn.png" id="searchstartbtnimg" style="width:80px; height:30px;" class="clickable" /></div>
							</form></div>
                    </div>
                    <div id="matches">
                    	<?php
							if (isset($matchCollection) && count($matchCollection) > 0)
							{
								$onlineMembers = $iwanshokoto->getOnlineMembers();
								
								foreach ($matchCollection as $key=>$value)
								{
									$json = json_decode($value);
									if ($json && property_exists($json, "memgenid"))
									{
										if ($json->memgenid == $_SESSION[SITEUSERAUTHENTICATIONKEY] || !array_key_exists($json->memgenid, $onlineMembers))
											continue;
						?>
                    	<div class="matchitemholder">
                        	<div style="height:5px;"></div>
                        	<div id="matchthumbnailholder">
                            	<a href="icontrol.php?iwsid=<?php echo $json->memgenid;?>" style="color:#FFF; text-decoration:none;">
                            	<?php
									$theMember = new Member($json->memgenid, $dbConn);
									if ($memPic = $theMember->getMemberPic(160, NULL, 75, true))
									{
										//$memPic = str_replace("../", "", $memPic);
										$memPic = str_replace("fetchmemberpic", "matchfetchpic", $memPic);
										$memPic = str_replace("?", "?iwsid=" . $json->memgenid . "&", $memPic);
										echo $memPic;
									}
									else
									{
										if (strcmp($json->gender, "MALE") == 0)
										{
											
								?>
                            	<img src="<?php echo $pathPrefix;?>images/duealbig.jpg" />
                                <?php
										}
										else
										{
											?>
                                <img src="<?php echo $pathPrefix;?>images/sweetladybig.jpg" />
                                            <?php
										}
									}
								?>
                                </a>
                            </div>
                            <div id="matchdescription">
                            	<label id="matchusername"><?php echo $json->username;?></label>
                                <br/>
                                <label id="matchlocation"><?php echo ucfirst(strtolower($json->location));?>, Nigeria</label>
                            </div>
                        </div>
                        <?php
									}
								}
							}
							elseif (isset($matchCollection) && count($matchCollection) == 0)
							{
						?>
                        <div style="text-align:left; font-size:15px; color:#C00; font-weight:bold; margin-left:20px;">None of our members match the search criteria.</div>
                        <?php
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