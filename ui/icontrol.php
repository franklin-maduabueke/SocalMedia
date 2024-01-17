<?php
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../includes/commons.php");
	include("../includes/user_checker.php");
	include("../classes/cmember.php");
	include("../classes/ciwanshokoto.php");
	
	if (!userSessionGood(SITEUSERAUTHENTICATIONKEY))
	{
		header("Location: ../processing/logout.php");
		exit();
	}
	else
		$sessionGood = TRUE;
		
	define('PAGE_NAME', 'icontrol');
	
	$pathPrefix = "../";
	
	$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);
	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
	{
		try
		{
			$profileMember = new Member($_GET['iwsid'], $dbConn); //representing other profile viewed
			$member = json_decode($profileMember->getProfile());
			$theMember = new Member($_SESSION[SITEUSERAUTHENTICATIONKEY], $dbConn); //representing user
			
			//add to peeker
			$iwanshokoto = new IWanShokoto($dbConn);
			$iwanshokoto->setMemberPeeker($_SESSION[SITEUSERAUTHENTICATIONKEY], $_GET['iwsid']);
			
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
<title>iwanshokoto. Profile</title>
</head>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/globals.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/popper.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/innerpage.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/profile.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/icontrol.css"/>
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
<script src="<?php echo $pathPrefix?>scripts/icontrol.js" type="text/javascript"></script>
<script src="<?php echo $pathPrefix?>scripts/tooltip.js" type="text/javascript"></script>
<script src="<?php echo $pathPrefix?>scripts/popper.js" type="text/javascript"></script>
<script src="<?php echo $pathPrefix?>scripts/global.js" type="text/javascript"></script>

<body style="background-color:#F0F0F0;">
<div id="pageContainer" align="center" style="background-color:#F0F0F0;">
	<?php include($pathPrefix . "includes/header.php"); ?>
    
    <?php include($pathPrefix . "includes/toolspanel.php"); ?>
	<div id="mainContentHolder">
        <div style="height:28px"></div>
        <input type="hidden" id="matchmemberid" value="<?php echo $_GET['iwsid']; ?>" />
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
                		<div id="rightcoldescription"><label style="color:#333333;">Profile of </label><label style="color:#007DB8"><?php echo $member->username;?></label></div>
                    	<div id="rightcoltoolsholder">
                        	<div class="toolcontrolholder">
                            	<div class="toolcontrolitem">
                                	<div class="toolcontrolitemiconholder"><img src="<?php echo $pathPrefix;?>images/checklist.png" /></div>
                                    
                                	<div class="toolcontrolitemlinkholder" id="scoreprofile" style="margin-right:10px;"><a href="score.php?iwsid=<?php echo $_GET['iwsid'];?>" id="scoremelink">Score Me</a></div>
                                    
                                	<div class="toolcontrolitemiconholder"><!--<img src="<?php echo $pathPrefix;?>images/editicon.jpg" />--><img src="<?php echo $pathPrefix;?>images/envelope.png" /></div>
                                    
                                	<div class="toolcontrolitemlinkholder" id="sendmessage"><a href="#" id="sendmessagelink">Send Message</a></div>
                                    <div class="toolcontrolitemlinkholder" id="sendingmessageprogress" style="display:none;">
                                    	<img src="<?php echo $pathPrefix;?>images/lightbox-ico-loading.gif" style="width:16px; height:16px;" />
                                    </div>
                                    <div style="clear:both"></div>
                                </div>
                                
                            	<div class="toolcontrolitem">
                                <?php
									if ($theMember->isMyFriend($_GET['iwsid']) == FALSE)
									{
										?>
                                    <div class="toolcontrolitemiconholder"><!--<img src="<?php echo $pathPrefix;?>images/editicon.jpg" />--><img src="<?php echo $pathPrefix;?>images/sndfriendreq.png" /></div>
                                        <?php
										if ($theMember->isRequestPending($_GET['iwsid']) == FALSE)
										{
								?>
                                	<div class="toolcontrolitemlinkholder" id="sendfriendrequest"><a href="#" id="sendfriendrequestlink">Send Friend </a></div>
                                    <?php
										}
										else
										{
									?>
                                    <div class="toolcontrolitemlinkholder" id="pendingapproval"><label>Pending Request</label></div>
                                    <?php
										}
									?>
                                     <div class="toolcontrolitemlinkholder" id="pendingapproval" style="display:none;"><label>Pending Request</label></div>
                                    <div class="toolcontrolitemlinkholder" id="sendingrequestprogress" style="display:none;">
                                    	<img src="<?php echo $pathPrefix;?>images/lightbox-ico-loading.gif" style="width:16px; height:16px;" />
                                    </div>
                                 <?php
									}
								 ?>
                                	<div style="clear:both"></div>
                                </div>
                                
                                <div style="clear:both"></div>
                            </div>
                        </div>
                		<div style="clear:both"></div>
                	</div>
                    <div style="height:20px;"></div>
                    <form method="post" action="../processing/editprofile.php" id="editprofileform">
                    <div id="profiletopsection">
                    	<div id="userprofileimageholderbound">
                        	<div style="height:7px;"></div>
                        	<div id="userprofileimageholder">
                            	<?php
									if ($theMembermPic = $profileMember->getMemberPic(200, 200, 75, true))
									{
										$theMembermPic = str_replace("fetchmemberpic.php?", "matchfetchpic.php?iwsid=" . $member->memgenid . "&", $theMembermPic);
										echo $theMembermPic;
									}
									else
									{
										if (strcmp($member->gender, "MALE") == 0)
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
                            </div>
                            <div id="userprofileimageeditbutton" class="clickable">
                            	<img src="<?php echo $pathPrefix;?>images/editicon.jpg"  style="width:100%; height:100%;"/>
                            </div>
                        </div>
                        <div id="userprofileimagesidemaindetails">
                        	<div style="height:7px"></div>
                        	<div class="userprofiledetailholder"><label class="updlabel">Username:</label> <label class="updddata"><?php echo $member->username;?></label><div style="clear:both"></div></div>
                            <div class="userprofiledetailholder"><label class="updlabel">Gender:</label> <label class="updddata"><?php echo ucfirst(strtolower($member->gender));?></label><div style="clear:both"></div></div>
                            <div class="userprofiledetailholder">
                            	<label class="updlabel">Age:</label> <label class="updddata"><?php echo date("Y") - date("Y", strtotime($member->dob));?></label>
                                <select class="updddata" id="ageday" name="ageday">
                                	<?php
										for ($i = 1; $i <= 31; $i++)
										{
											?>
                                            <option value="<?php echo $i;?>" <?php if (isset($member->dob) && date('j', strtotime($member->dob)) == $i) echo 'selected="selected"';?>><?php echo $i;?></option>
                                            <?php
										}
									?>
                                </select>
                                
                                <select class="updddata" id="agemonth" name="agemonth">
                                	<option value="01"  <?php if (isset($member->dob) && date('n', strtotime($member->dob)) == 1) echo 'selected="selected"';?>>January</option>
                                    <option value="02" <?php if (isset($member->dob) && date('n', strtotime($member->dob)) == 2) echo 'selected="selected"';?>>February</option>
                                    <option value="03" <?php if (isset($member->dob) && date('n', strtotime($member->dob)) == 3) echo 'selected="selected"';?>>March</option>
                                    <option value="04" <?php if (isset($member->dob) && date('n', strtotime($member->dob)) == 4) echo 'selected="selected"';?>>April</option>
                                    <option value="05" <?php if (isset($member->dob) && date('n', strtotime($member->dob)) == 5) echo 'selected="selected"';?>>May</option>
                                    <option value="06" <?php if (isset($member->dob) && date('n', strtotime($member->dob)) == 6) echo 'selected="selected"';?>>June</option>
                                    <option value="07" <?php if (isset($member->dob) && date('n', strtotime($member->dob)) == 7) echo 'selected="selected"';?>>July</option>
                                    <option value="08" <?php if (isset($member->dob) && date('n', strtotime($member->dob)) == 8) echo 'selected="selected"';?>>August</option>
                                    <option value="09" <?php if (isset($member->dob) && date('n', strtotime($member->dob)) == 9) echo 'selected="selected"';?>>September</option>
                                    <option value="10" <?php if (isset($member->dob) && date('n', strtotime($member->dob)) == 10) echo 'selected="selected"';?>>October</option>
                                    <option value="11" <?php if (isset($member->dob) && date('n', strtotime($member->dob)) == 11) echo 'selected="selected"';?>>November</option>
                                    <option value="12" <?php if (isset($member->dob) && date('n', strtotime($member->dob)) == 12) echo 'selected="selected"';?>>December</option>
                                </select>
                                
                                 <select class="updddata" id="ageyear" name="ageyear">
                                	<?php
										for ($i = 1960; $i <= date("Y"); $i++)
										{
											?>
                                            <option value="<?php echo $i;?>" <?php if (isset($member->dob) && date('Y', strtotime($member->dob)) == $i) echo 'selected="selected"';?>><?php echo $i;?></option>
                                            <?php
										}
									?>
                                </select>
                                <div style="clear:both"></div>
                            </div>
                            <div class="userprofiledetailholder">
                            	<label class="updlabel">Location:</label> <label class="updddata"><?php echo ucfirst(strtolower($member->location));?>, Nigeria</label>
                                <select class="updddata" id="location" name="location">
                                	<?php
										if ($dbConn->ping())
										{
											$sql = "SELECT LTID, StateName FROM locations ORDER BY StateName";
											$result = $dbConn->query($sql);
											if ($result && $result->num_rows > 0)
											{
												for (;($row = $result->fetch_array()) != FALSE ;)
												{
													?>
                                                    <option value="<?php echo $row['LTID'];?>" <?php if (strcasecmp($row['StateName'], $member->location) == 0) echo 'selected="selected"';?>><?php echo $row['StateName'];?></option>
                                                    <?php
												}
											}
										}
									?>
                                </select>
                                <div style="clear:both"></div>
                            </div>
                            <div class="userprofiledetailholder">
                            	<label class="updlabel">Looking For:</label> <label class="updddata"><?php echo $member->lookingfor;?></label>
                                <select class="updddata" id="profilelookingfor" name="profilelookingfor">
                               		<option value="1" <?php if (strcasecmp($member->lookingfor, 'Friendship') == 0) echo 'selected="selected"';?>>Friendship</option>
                                	<option value="2" <?php if (strcasecmp($member->lookingfor, 'Relationship') == 0) echo 'selected="selected"';?>>Relationship</option>
                                	<option value="3" <?php if (strcasecmp($member->lookingfor, 'Casual Affair') == 0) echo 'selected="selected"';?>>Casual Affair</option>
                                    <option value="4" <?php if (strcasecmp($member->lookingfor, 'Quicky') == 0) echo 'selected="selected"';?>>Quicky</option>
                            	</select>
                                <div style="clear:both"></div>
                            </div>
                            <div class="userprofiledetailholder">
                            	<label class="updlabel">Seeking:</label> <label class="updddata"><?php echo ucfirst(strtolower($member->seeking));?></label>
                                <select class="updddata" id="profileintrestedin" name="profileintrestedin">
                                	<option value="1" <?php if (strcasecmp($member->seeking, 'Men') == 0) echo 'selected="selected"';?>>Men</option>
                                    <option value="2" <?php if (strcasecmp($member->seeking, 'Women') == 0) echo 'selected="selected"';?>>Women</option>
                                </select>
                                <div style="clear:both"></div>
                            </div>
                        </div>
                    	<div style="clear:both"></div>
                    </div>
                    
                    <div id="profilemidsection">
                    	<div class="userprofiledetailholder">
                        	<label class="updlabel">Intrested in:</label> <label class="updddata"></label>
                            
                            <div id="intrestedinoptionsholder">
                            	<?php
									$memberIntrests = NULL;
									if (strlen($member->intrest))
									{
										$memberIntrests = explode(',', $member->intrest);
										$memberIntrests = array_flip($memberIntrests);
									}
									
									$sql = "SELECT ITID, IntrestGenID, IntrestName FROM intrests";
									$intrestResult = $dbConn->query($sql);
									if ($intrestResult && $intrestResult->num_rows > 0)
									{
										for (;$row = $intrestResult->fetch_array();)
										{
								?>
                            	<div class="intrestitem">
                                 	<input type="checkbox" disabled="disabled" id="<?php strtolower(str_replace(" ", "", $row['IntrestName']));?>" name="intrestedin[]" value="<?php echo $row['IntrestGenID']?>" <?php if (isset($memberIntrests) && array_key_exists($row['IntrestGenID'], $memberIntrests)) echo 'checked="checked"';?> /> <label><?php echo $row['IntrestName'];?></label>
                                </div>
                                <?php
										}
									}
								?>
                                <div style="clear:both"></div>
                            </div>
                            <div style="clear:both"></div>
                        </div>
                        
                    	<div class="userprofiledetailholder">
                        	<label class="updlabel">Hobby:</label> <label class="updddata"><?php echo $member->hobby;?></label>
                            <input type="text" class="updddata longinputbox" id="profilehobby" name="profilehobby" value="<?php echo $member->hobby;?>" />
                            <div style="clear:both"></div>
                        </div>
                        <div class="userprofiledetailholder">
                        	<label class="updlabel">Occupation:</label> <label class="updddata"><?php echo $member->occupation;?></label>
                            <input type="text" class="updddata" id="profileoccupation" name="profileoccupation" value="<?php echo $member->occupation;?>" />
                            <div style="clear:both"></div>
                        </div>
                        <div class="userprofiledetailholder">
                        	<label class="updlabel">Sexual Orientation:</label> <label class="updddata" style="color:#090; font-weight:bold;"><?php echo $member->sexorientation;?></label>
                            <select class="updddata" id="profilesexorientation" name="profilesexorientation">
                               	<option value="5">UNDISCLOSED</option>
                                <option value="1" <?php if (strcasecmp($member->sexorientation, "straight") == 0) echo 'selected="selected"';?>>STRAIGHT</option>
                                <option value="2" <?php if (strcasecmp($member->sexorientation, "bisexual") == 0) echo 'selected="selected"';?>>BISEXUAL</option>
                                <option value="3" <?php if (strcasecmp($member->sexorientation, "lesbian") == 0) echo 'selected="selected"';?>>LESBIAN</option>
                                <option value="4" <?php if (strcasecmp($member->sexorientation, "gay") == 0) echo 'selected="selected"';?>>GAY</option>
                            </select>
                            <div style="clear:both"></div>
                        </div>
                        <div class="userprofiledetailholder">
                        	<label class="updlabel">Relationship:</label> <label class="updddata"><?php echo $member->relationship;?></label>
                            <select class="updddata" id="profilerelationship" name="profilerelationship">
                               	<option value="1" <?php if (strcasecmp($member->relationship, 'Single') == 0) echo 'selected="selected"';?>>Single</option>
                                <option value="2" <?php if (strcasecmp($member->relationship, 'Dating') == 0) echo 'selected="selected"';?>Dating</option>
                                <option value="3" <?php if (strcasecmp($member->relationship, 'Married') == 0) echo 'selected="selected"';?>Married</option>
                            </select>
                            <div style="clear:both"></div>
                        </div>
                        <div class="userprofiledetailholder">
                        	<label class="updlabel">Height:</label> <label class="updddata"><?php echo $member->height;?></label>
                            <select class="updddata" id="profileheightfeet" name="profileheightfeet">
                            	<?php
									$feet = substr($member->height, 0, 1);
									$inches = substr($member->height, 4);
									
									if (strlen($inches) > 4)
										$inches = substr($inches, 0, 2);
									else
										$inches = substr($inches, 0, 1);
										
									for ($i = 3; $i <= 7; $i++)
									{
								?>
                               	<option value="<?php echo $i;?>" <?php if ($i == $feet) echo 'selected="selected"';?>><?php echo $i . 'ft';?></option>
                                <?php
									}
								?>
                            </select>
                            <select class="updddata" id="profileheightinch" name="profileheightinch">
                            	<?php
									for ($i = 0; $i <= 11; $i++)
									{
								?>
                               	<option value="<?php echo $i;?>" <?php if ($i == $inches) echo 'selected="selected"';?>><?php echo $i , 'inc';?></option>
                                <?php
									}
								?>
                            </select>
                            <div style="clear:both"></div>
                        </div>
                        <div class="userprofiledetailholder">
                        	<label class="updlabel">Country of Residence:</label> <label class="updddata"><?php echo $member->countryresidence;?></label>
                           <select class="updddata" id="profilecountryofresidence" name="profilecountryofresidence">
                           <?php
						   		$sql = "SELECT CTID, CountryName FROM country ORDER BY CountryName";
								$countryResult = $dbConn->query($sql);
								if ($countryResult && $countryResult->num_rows > 0)
								{
									for (;$crow = $countryResult->fetch_array();)
									{
						   ?>
                               	<option value="<?php echo $crow['CTID'];?>" <?php if (strcasecmp($member->countryresidence, $crow['CountryName']) == 0) echo 'selected="selected"';?> <?php if (empty($member->countryresidence) && strcasecmp($crow['CountryName'], 'Nigeria') == 0) echo 'selected="selected"';?>><?php echo $crow['CountryName'];?></option>
                           <?php
									}
								}
						   ?>
                            </select>
                            <div style="clear:both"></div>
                        </div>
                        <!--
                        <div class="userprofiledetailholder">
                        	<label class="updlabel">Favorite Quotes:</label> <label class="updddata"><?php echo $member->favquote;?></label>
                            <input type="text" class="updddata longinputbox" id="profilefavoritequotes" name="profilefavoritequotes" value="<?php echo $member->favquote;?>" />
                            <div style="clear:both"></div>
                        </div>
                        -->
                        <div class="userprofiledetailholder"><label class="updlabel">Choice of Movies:</label> <label class="updddata">Fiction, Drama, Action</label><div style="clear:both"></div></div>
                        <div class="userprofiledetailholder"><label class="updlabel">Choice of Music:</label> <label class="updddata">RnB, HipHop, Slow rock, Gospel</label><div style="clear:both"></div></div>
                        <div class="userprofiledetailholder">
                        	<label class="updlabel">Me in my own Words:</label> <label class="updddata"><?php echo $member->aboutme;?></label>
                            <input type="text" class="updddata longinputbox" id="profilemeinmyownwords" name="profilemeinmyownwords" value="<?php echo $member->aboutme;?>" />
                            <div style="clear:both"></div>
                        </div>
                        <div class="userprofiledetailholder">
                        	<label class="updlabel">My Biggest Asset:</label> <label class="updddata"><?php echo $member->biggestasset;?></label>
                            <input type="text" class="updddata" id="profilemybigestasset" name="profilemybigestasset" value="<?php echo $member->biggestasset;?>" />
                            <div style="clear:both"></div>
                        </div>
                        <div class="userprofiledetailholder">
                        	<label class="updlabel">My Fairy Tale Romance:</label> <label class="updddata"><?php echo $member->fairytaleromance;?></label>
                            <input type="text" class="updddata longinputbox" id="profilefairytaleromance" name="profilefairytaleromance" value="<?php echo $member->fairytaleromance;?>" />
                            <div style="clear:both"></div>
                        </div>
                    </div>
                    </form>
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