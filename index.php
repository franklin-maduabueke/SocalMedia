<?php
	include("configs/db.php");
	include("modules/mysqli.php");
	include("classes/cmember.php");
	include("classes/ciwanshokoto.php");
	
	define('PAGE_NAME', 'home');
	
	$pathPrefix = "";

	//if cookie exists for this user then try loggin in the user
	if (isset($_COOKIE['iwanshokotodotcomauthentication']))
	{
		//header("Location: processing/authenticate.php");
		//exit();
		//echo "Cookie set";
	}
	else
	{
		//echo "Cookie not set";
	}
	
	$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);
	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
	{
		$sql = "SELECT LTID, StateName FROM location ORDER BY StateName";
		$result  = $dbConn->query($sql);
		
		if ($result && $result->num_rows > 0)
		{
			$statesFetched = TRUE;
		}
		
		$iwanshokoto = new IWanShokoto($dbConn);
		$hottestMembers = $iwanshokoto->getHottestMembers();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>iwanshokoto. Be discreet</title>
</head>
<link rel="stylesheet" type="text/css" href="stylesheet/globals.css"/>
<link rel="stylesheet" type="text/css" href="stylesheet/popper.css"/>
<link rel="stylesheet" type="text/css" href="stylesheet/index.css"/>
<link rel="stylesheet" type="text/css" href="stylesheet/dyntooltip.css"/>
<link rel="SHORTCUT ICON" href="images/iwanshokotofavicon.jpg" />

<script src="scripts/jQuery1_7.js" type="text/javascript"></script>
<script src="scripts/popper.js" type="text/javascript"></script>
<script src="scripts/slidescroll.js" type="text/javascript"></script>
<script src="scripts/authenticate.js" type="text/javascript"></script>
<script src="scripts/registerpanelScript.js" type="text/javascript"></script>
<script src="scripts/indexscript.js" type="text/javascript"></script>
<script src="scripts/dyntooltip.js" type="text/javascript"></script>
<script type="text/javascript">
	var pagePathPrefix = '<?php echo $pathPrefix?>';
</script>
<body>
<div id="pageContainer" align="center">
	<div style="height:55px;"></div>
	<div id="mainContentHolder">
    	<div id="mainContentTop">
			<div id="maintopleftcol">
				<div id="homelogoholder">
					<img src="images/newhomelogo.png" alt="iwanshokoto.com logo" />
				</div>
			</div>
			
			<div id="maintoprightcol">
				<div id="logincontrolholder">
					<div id="loginpanelsidebar"></div>
					<div id="mainloginpanel">
						<div style="height:10px;"></div>
						<div class="logininputboxholder">
							<div class="logininputlabel"><img src="images/usernametxt.png" /></div>
							<div class="logininputbox"><input type="text" name="memberusername" id="memberusername" /></div>
							<div style="clear:both"></div>
						</div>
						
						<div class="logininputboxholder" style="margin-top:7px;">
							<div class="logininputlabel" style="margin-left:3px;"><img src="images/password.png" /></div>
							<div class="logininputbox"><input type="password" name="memberpwd" id="memberpwd" /></div>
							<div style="clear:both"></div>
						</div>
						
						<div id="loginpanelbasecontrols">
							<div style="float:left; min-width:164px; margin-top:7px; text-align:left;">
                            	<input type="checkbox" name="remembermecheck" id="remembermecheck" style="width:auto; height:auto;" /> <label style="font-size:12px; color:#FFFFFF; font-family:Verdana, Arial, Helvetica, sans-serif;">Remember</label> 
                                <a href="#" style="font-size:12px; color:#FFFFFF; font-family:Verdana, Arial, Helvetica, sans-serif; margin-left:25px;">Forgot?</a>
                            </div>
							<div id="loginbutton" class="clickable">
								<div style="height:11px;"></div>
								<div><img src="images/logintxt.png" /></div>
							</div>
							<div style="clear:both"></div>
						</div>
					</div>
					<div style="clear:both"></div>
				</div>
				<div id="kakaholder"><img src="images/kaka.png" alt="doncan miht" /></div>
				<div style="clear:both"></div>
			</div>
			
			<div style="clear:both"></div>
		</div>
		
		<div id="bannercontentholder">
			<div id="whyulikeusholder">
				<div id="whyuwilllikeus">
				</div>
				<div style="width:auto; height:auto; position:relative; top:-338px; margin-left:370px;"><img src="images/shokotobabe.png" /></div>
				<div style="clear:both"></div>
				<div style="width:420px; height:276px; position:relative; top:-680px;">
					<div style="text-align:left;"><img src="images/hereswhyulikeus.png" /></div>
					<div style="font-size:10px; color:#FFFFFF; font-family:Verdana, Arial, Helvetica, sans-serif; text-align:left;">
						<div style="margin-top:5px;"></div>
						<div style="width:390px;">
							<label style="color:#E62174; font-weight:bold;">Iwanshokoto.com</label> is safe, <b>FREE</b> and 100% confidential for men and women looking for marital affair, sexual encounter, sugar mummy, sugar daddy, lesbian, gay and aristo's in Nigeria. We have discrete men and women in local areas for illicit encounters and affairs.
                            <ul>
                            	<li style="list-style:square; font-size:13px;">Quick and easy to join</li>
                                <li style="list-style:square; font-size:13px;">Instant private messaging and chat rooms</li>
                                <li style="list-style:square; font-size:13px;">Real adult hookups</li>
                                <li style="list-style:square; font-size:13px;">Upgrade your sex life</li>
                                <li style="list-style:square; font-size:13px;">100% discrete (Real profile picture not required)</li>
                                <li style="list-style:square; font-size:13px;">No complications</li>
                                <li style="list-style:square; font-size:13px;">
                                	Get real Sugar Daddy, Sugar Mummy, Lesbian, Gay, and Aristo's in Nigeria
                                </li>
                            </ul>
                            <p/>
                            Whether it's a flirting, chating, passionate, romantic encounter, adultery, strickly physical, looking for married dating, or just plan fun, we have your match.
						</div>
						<!--<div><a href="#" style="font-size:15px; color:#FFCC00; font-family:sitefont;">Learn more</a></div>-->
					</div>
				</div>
			</div>
			
			<div id="registrationpanel">
            	<form method="post" action="processing/register.php" id="registrationForm">
            	<div style="height:15px"></div>
                <div style="width:311px;">
            		<div style="text-align:left; border-bottom:1px solid #FFF; padding:0px 0px 19px 0px;"><img src="images/joinus.png" /></div>
                    <div class="regentrybox">
                    	<div id="dlabel">i am</div>
                        <div id="inputboxitem">
                        	<select id="iamselect" name="iamselect">
                            	<option value="1">a Man seeking a Woman</option>
                                <option value="2">a Woman seeking a Man</option>
                                <option value="3">a Man seeking a Man</option>
                                <option value="4">a Woman seeking a Woman</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="regentrybox">
                    	<div id="dlabel">intrested in</div>
                        <div id="inputboxitem">
                        	<select id="lookingforselect" name="lookingforselect">
                            	<option value="0"></option>
                                <option value="-1">----------------</option>
                            	<?php
									//get intrests
									$intrests = $iwanshokoto->getIntrests();
									if (count($intrests))
									{
										foreach ($intrests as $key=>$value)
										{
											$ijson = json_decode($value);
											if ($ijson && property_exists($ijson, "intrestgenid"))
											{
								?>
                                <option value="<?php echo $ijson->intrestgenid;?>"><?php echo $ijson->intrestname;?></option>
                                <?php
											}
										}
									}
								?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="regentrybox">
                    	<div id="dlabel">email</div>
                        <div id="inputboxitem">
                        	<input type="text" id="reguseremail" name="reguseremail" />
                        </div>
                    </div>
                    
                    <div class="regentrybox">
                    	<div id="dlabel">username</div>
                        <div id="inputboxitem">
                        	<input type="text" id="reguserusername" name="reguserusername" maxlength="12" />
                            <img src="images/lightbox-ico-loading.gif" style="visibility:hidden; width:14px; height:14px; margin-top:-10px; margin-left:180px;" id="usernamecheckprocess" />
                        </div>
                    </div>
                    
                    <div class="regentrybox">
                    	<div id="dlabel">password</div>
                        <div id="inputboxitem">
                        	<input type="password" id="reguserpwrd" name="reguserpwrd" />
                        </div>
                    </div>
                    
                    <div class="regentrybox">
                    	<div id="dlabel" style="line-height:11px; text-align:left;">confirm<br/>password</div>
                        <div id="inputboxitem">
                        	<input type="password" id="reguserconfirmpwrd" name="reguserconfirmpwrd" />
                        </div>
                    </div>
                    
                    <div class="regentrybox">
                    	<div id="dlabel">location</div>
                        <div id="inputboxitem">
                        	<select id="reguserlocation" name="reguserlocation">
                            	<?php
									$sql = "SELECT LTID, StateName FROM locations ORDER BY StateName";
									$result = $dbConn->query($sql);
									if ($result && $result->num_rows > 0)
									{
										for (; $row = $result->fetch_array();)
										{
								?>
                            	<option value="<?php echo $row['LTID'];?>"><?php echo ucwords(strtolower($row['StateName']));?></option>
                                <?php
										}
									}
								?>
                            </select>
                        </div>
                    </div>
                    
                    <div style="height:50px; margin-top:10px;">
                    	<div style="float:left; width:158px; font-size:8px; letter-spacing:1px; color:#FFF; font-family:Verdana, Geneva, sans-serif; text-align:left;">
                        	Login details will be sent to this email, so please enter a valid email address.
                        	By signing up you indicate that you are in agreement with our <a href="#" style="font-family:Verdana, Geneva, sans-serif; color:#FFCC00; text-decoration:none;">terms of service</a>.
                        </div>
                    	<div id="signupbutton" class="clickable"><img src="images/signupbtnimg.png" /></div>
                    	<div style="clear:both"></div>
                    </div>
                </div>
			</div>
			<div style="clear:both"></div>
            </form>
		</div>
		
		<div style="height:19px;"></div>
		<div id="basecontentholder">
			<div id="basecontentleftcol">
				<div id="newestmembersfeaturespanel">
					<div id="newestmemberspanel">
						<div style="height:12px;"></div>
						<div id="newestmemberspanelheading"><img src="images/newmemberstxt.png" /></div>
						<div id="newestmembersslideshowpanel">
                        	<?php
								if (isset($hottestMembers))
								{
							?>
							<div id="newestmembersslider">
                            	<?php
									$i = 1;
									foreach ($hottestMembers as $value)
									{
										if ($i > 8) //limit draw to 8
											break;
										
										$hotmember = json_decode($value);
										if ($hotmember && property_exists($hotmember, "memgenid"))
										{
											//member must have pic.
											try
											{
												$member = new Member($hotmember->memgenid, $dbConn);
												$memberPic = $member->getMemberPic(90, NULL, 75, TRUE);
												if (!$memberPic)
													continue;
											}
											catch (Exception $ex)
											{
											}
								?>
                                <div class="newmemberpreviewtumbnail" <?php if ($i == 1 || ($i % 5) == 0) echo 'style="margin-left:0px;"'; ?>>
                                    <div style="height:5px;"></div>
                                    <div class="thumbnailholder">
                                    	<?php 
											$memberPic = str_replace("../", "", $memberPic);
											$memberPic = str_replace("fetchmemberpic.php?", "matchfetchpic.php?iwsid=" . $hotmember->memgenid . "&", $memberPic);
											echo $memberPic;
										?>
                                    	<input type="hidden" class="dyntooltip:indextooltip" value="<?php echo $hotmember->username . " (" . $hotmember->age . "), " . ucfirst(strtolower($hotmember->gender)) . "<br/>" . ucfirst(strtolower($hotmember->location)) . " Nigeria.";?>" />
                                    </div>
                                </div>
                                <?php
											$i++;
										}
									}
								?>
                                <div style="clear:both"></div>
							</div>
                            <?php
								}
							?>
						</div>
						<div id="loadingindicatorpanel"><img src="images/loading51.gif" /></div>
					</div>
					<div id="featurelistpanel">
						<div id="chatfeaturebtn"><input type="hidden" class="dyntooltip:indextooltip" value="Get the best chat experience ever with our chat interface." /></div>
						<div id="romancefeaturebtn"><input type="hidden" class="dyntooltip:indextooltip" value="Find that special mate. Be as naughty as you can be." /></div>
						<div id="diaryfeaturebtn"><input type="hidden" class="dyntooltip:indextooltip" value="Manage your own e-Diary. No one has to know your deepest secrets." /></div>
						<div id="smsnotificationfeaturebtn"><input type="hidden" class="dyntooltip:indextooltip" value="Get SMS notification alerts on everything when you are away." /></div>
						<div style="clear:both"></div>
					</div>
					<div style="text-align:left; margin-top:14px; padding-left:5px;"><img src="images/memberstxt.jpg" /> <label style="font-size:40px; font-family:Verdana, Arial, Helvetica, sans-serif; color:#333333; margin-left:25px;" id="totalmembersonsite"><?php echo number_format(23900 + $iwanshokoto->getTotalMembers()); ?></label></div>
				</div>
			</div>
			<div id="basecontentrightcol">
				<div id="findurmatchpanel">
                <form method="post" action="match.php" id="findyourmatchform">
					<div style="height:10px;"></div>
					<div style="text-align:left;margin-left:14px; font-size:14px; color:#FF3300; font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-weight:bold;">
                    	<!--<img src="images/findurmatchtxt.png" />-->FIND EXCITING ADULT SHOKOTO ENCOUNTERS NOW!!!
                        <br/>
                        <label style="font-size:10px; color:#FFF; font-family:Verdana, Geneva, sans-serif;">Meet sexy Nigerian sex dating members that share your passion.</label>
                    </div>
                    
					<div style="float:left; width:208px; height:125px; margin:15px 0px 0px 15px; text-align:left; font-family:Verdana, Arial, Helvetica, sans-serif;">
						<div style="height:3px"></div>
						<div style="text-align:right; padding-right:5px;"><label style="font-size:15px; font-weight:bold; color:#FFFFFF">show me</label> 
							<select name="queryshowme" id="queryshowme" style="border:2px solid #FFF; width:100px;">
                            	<option value="0">All</option>
								<option value="1">Men</option>
								<option value="2">Women</option>
							</select>
						</div>
						<div style="height:3px"></div>
						<div style="text-align:right; padding-right:5px;"><label style="font-size:15px; font-weight:bold; color:#FFFFFF">state</label> 
							<select name="querylocation" id="querylocation" style="border:2px solid #FFF; width:150px;">
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
								<option value="<?php echo $row['LTID'];?>" <?php if (strtolower($row['StateName']) == "lagos") echo 'selected="selected"';?>><?php echo $row['StateName'];?></option>
								<?php
										}
									}
								?>
							</select>
						</div>
						<div style="height:3px"></div>
                        <div style="text-align:right; padding-right:5px;">
                        	<label style="font-size:15px; font-weight:bold; color:#FFFFFF">intrested in</label> 
                        	<select name="queryintrest" id="queryintrest" style="border:2px solid #FFF; width:100px;">
                            	<option value="0">Anything</option>
                                <option value="-1" disabled="disabled">---------------------</option>
                            <?php
								$sql = "SELECT ITID, IntrestGenID, IntrestName FROM intrests";
								$intrestsResult = $dbConn->query($sql);
								if ($intrestsResult && $intrestsResult->num_rows > 0)
								{
									for (;$irow = $intrestsResult->fetch_array();)
									{
							?>
                            	<option value="<?php echo $irow['IntrestGenID'];?>"><?php echo $irow['IntrestName'];?></option>
                            <?php
									}
								}
							?>
                            </select>
                        </div>
                        <div style="height:3px"></div>
                        <div style="text-align:right; padding-right:5px; text-align:left; margin-left:5px; margin-top:10px;"><label style="font-size:15px; font-weight:bold; color:#FFFFFF;">with photo</label> <input type="checkbox" name="querywithphoto" id="querywithphoto" /></div>
					</div>
					<div style="float:right; width:202px; height:158px; margin:15px 15px 0px 0px;">
						<div style="text-align:right; padding-right:5px; text-align:left; margin-left:12px;"><label style="font-size:15px; font-weight:bold; color:#FFFFFF; font-family:Verdana, Arial, Helvetica, sans-serif;">ages</label> 
							<select name="queryagefrom" id="queryagefrom" style="border:2px solid #FFF; width:100px;">
								<?php
									for ($i = 18; $i <= 100; $i++)
									{
								?>
								<option value="<?php echo $i;?>"><?php echo $i;?></option>
								<?php
									}
								?>
							</select>
						</div>
                        <div style="height:3px"></div>
						<div style="text-align:right; padding-right:5px; text-align:left; margin-left:34px;"><label style="font-size:15px; font-weight:bold; color:#FFFFFF; font-family:Verdana, Arial, Helvetica, sans-serif;">to</label> 
							<select name="queryageto" id="queryageto" style="border:2px solid #FFF; width:100px;">
								<?php
									for ($i = 18; $i <= 100; $i++)
									{
								?>
								<option value="<?php echo $i;?>" <?php if ($i == 25) echo 'selected="selected"';?>><?php echo $i;?></option>
								<?php
									}
								?>
							</select>
						</div>
						<div style="text-align:right; margin-right:10px; margin-top:40px;" ><img src="images/searchbtn.png" id="startsearchmatchbtn" class="clickable" /></div>
					</div>
					<div style="clear:both"></div>
                </form>
				</div>
			</div>
			<div style="clear:both"></div>
		</div>
		
		<div id="homefooter">
			<div style="height:10px;"></div>
			<div id="copyrightpanel">
				<div style="font-size:12px; color:#666666; font-family:Verdana, Arial, Helvetica, sans-serif; line-height:15px;">
				Privacy Policy Agreement.
<p/>
Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.
<p/>
Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet... <a href="#" style="font-size:12px; font-weight:bold; color:#0193C6; text-decoration:none">Continue Reading »</a>
				</div>
				<div style="height:18px;"></div>
				<div><img src="images/copyrightimg.png" /></div>
			</div>
			
			<div id="termsofservicepanel">
				<div><img src="images/stayintouch.png" /></div>
				<div style="text-align:left; margin-top:8px; height:120px;">
					<a href="#" style="color:#1F191B; margin-right:10px;"><img src="images/fbsharpicon.png" alt="Find iwanshokoto on facebook" /></a>
					<a href="#" style="color:#1F191B; margin-right:10px;"><img src="images/twittericon.png" alt="Follow iwanshokoto on twitter" /></a>
				</div>
				<div style="font-size:15px; color:#FFFFFF" id="staticpagelinksholder"><a href="aboutus.php"><img src="images/aboutuslink.png" /></a><label>|</label><a href="#"><img src="images/contactus.png" /></a><label>|</label><a href="#"><img src="images/termsofservice.png" /></a></div>
			</div>
			<div style="clear:both"></div>
		</div>
	</div>
	<div style="height:46px;"></div>
</div>
<input type="hidden" value="<?php echo $_GET['ilt'];?>" id="logsymbol" />
</body>
</html>