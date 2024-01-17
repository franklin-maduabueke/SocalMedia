<?php
	include("configs/db.php");
	include("modules/mysqli.php");
	
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
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>iwanshokoto. Be discreet</title>
</head>
<link rel="stylesheet" type="text/css" href="stylesheet/globals.css"/>
<link rel="stylesheet" type="text/css" href="stylesheet/index.css"/>
<script src="scripts/jQuery1_7.js" type="text/javascript"></script>
<script src="scripts/slidescroll.js" type="text/javascript"></script>
<script src="scripts/authenticate.js" type="text/javascript"></script>
<script src="scripts/registerpanelScript.js" type="text/javascript"></script>

<script type="text/javascript">
	var pagePathPrefix = '<?php echo $pathPrefix?>';
</script>

<body style="background-color:#F0F0F0">
<div id="pageContainer" align="center">
	<div id="mainContentHolder">
    	<div style="height:9px;"></div>
    	<div id="mainContentTop">
        	<div id="maintopleftcol">
            	<div id="homelogoholder"><img src="images/homelogo.png" alt="iwanshokoto.com. It's a blind date experience" /></div>
                <div style="clear:both; height:19px;"></div>
                <div id="sloganholder">
                	<div style="width:39px; height:47px; float:left;"><img src="images/wing.png" /></div>
                    <div style="font-family:sitefont; width:auto; height:auto; float:left; font-size:26px; color:#FFF; font-weight:bold; margin-top:12px; margin-left:9px;"><img src="images/slogan.png" /></div>
                    <div style="clear:both"></div>
                </div>
                <div style="clear:both"></div>
                <div style="height:24px;"></div>
                 <div style="text-align:left; padding-left:32px;"><img src="images/memberlogin.png" /></div>
                 <div class="memberlogintextboxholder">
                 	<div style="float:left; width:auto; height:auto; margin-top:4px"><img src="images/uname.png" /></div>
                    <div style="float:left; width:248px; height:35px; background:#FFF; float:right;">
                    	<input type="text" name="memberusername" id="memberusername" style="width:100%; height:30px; border:none;" />
                    </div>
                    <div style="clear:both"></div>
                 </div>
                 <div class="memberlogintextboxholder">
                 	<div style="float:left; width:auto; height:auto; margin-top:4px"><img src="images/password.png" /></div>
                    <div style="float:left; width:248px; height:35px; background:#FFF; float:right;">
                    	<input type="password" name="memberpwd" id="memberpwd" style="width:100%; height:30px; border:none;" />
                    </div>
                    <div style="clear:both"></div>
                 </div>
                 
                 <div id="logincontrolholder">
                 	<div class="logincontrolbtnholder clickable" id="loginbutton">
                    	<img src="images/homebtn.png" />
                        <div class="clickable" style="position:relative; top:-36px; width:126px; font-size:17px; font-weight:bold; font-family:sitefont; color:#FFF;"><img src="images/logintxt.png" /></div>
                    </div>
                    
                    <div style="float:right; width:208px; margin-right:35px;">
                    	<div style="height:5px;"></div>
                    	<input type="checkbox" name="remembermecheck" id="remembermecheck" /> <label style="font-size:15px; color:#FFF; font-family:Verdana, Geneva, sans-serif;">Remember me | </label><label style="font-size:15px; color:#FFF; font-family:Verdana, Geneva, sans-serif;"><a href="#account/pwdrecover.php" style="font-size:15px; color:#FFF; font-family:Verdana, Geneva, sans-serif;">Forgot</a></label>
                    </div>
                 	<div style="clear:both"></div>
                 </div>
            </div>
            <div id="maintoprightcol">
            	<div id="registrationpanelHolder">
                	<div style="height:18px;"></div>
                	<div style="width:311px; border-bottom:1px solid #FF3399; height:44px;">
                    	<div style="width:auto; height:auto; float:left;"><img src="images/joinus.png" /></div>
                        <div style="width:auto; height:auto; float:right;"><img src="images/winglogo.png" /></div>
                        <div style="clear:both"></div>
                    </div>
                    <form method="post" action="processing/register.php" id="registrationformElement">
                    <div style="width:311px; height:auto;">
                    	<div style="height:10px;"></div>
                    	<div class="regentrycontainer">
                        	<div class="regentryitemlabel"><img src="images/iam.png" /></div>
                            <div class="regentryitemtextboxholder">
                            	<select id="inputiam" name="inputiam" style="width:100%; border:3px solid #FFF;">
                                	<option value="1">A Man</option>
                                    <option value="2">A Woman</option>
                                </select>
                            </div>
                            <div style="clear:both"></div>
                        </div>
                        
                        <div class="regentrycontainer">
                        	<div class="regentryitemlabel"><img src="images/lookingfor.png" /></div>
                            <div class="regentryitemtextboxholder">
                            	<select id="inputlookingfor" name="inputlookingfor" style="width:100%; border:3px solid #FFF;">
                                	<option value="2">A Woman</option>
                                	<option value="1">A Man</option>
                                </select>
                            </div>
                            <div style="clear:both"></div>
                        </div>
                        
                         <div class="regentrycontainer">
                        	<div class="regentryitemlabel"><img src="images/emailtxt.png" /></div>
                            <div class="regentryitemtextboxholder">
                            	<input type="text" id="inputemail" name="inputemail" />
                            </div>
                            <div style="clear:both"></div>
                        </div>
                        
                        <div class="regentrycontainer">
                        	<div class="regentryitemlabel"><img src="images/usernametxt.png" /></div>
                            <div class="regentryitemtextboxholder">
                            	<input type="text" id="inputusername" name="inputusername" />
                            </div>
                            <div style="clear:both"></div>
                        </div>
                        
                        <div class="regentrycontainer">
                        	<div class="regentryitemlabel"><img src="images/pwdtxt.png" /></div>
                            <div class="regentryitemtextboxholder">
                            	<input type="password" id="inputpwd" name="inputpwd" />
                            </div>
                            <div style="clear:both"></div>
                        </div>
                        
                        <div class="regentrycontainer">
                        	<div class="regentryitemlabel" style="margin:-2px;"><img src="images/confirmtxt.png" /></div>
                            <div class="regentryitemtextboxholder">
                            	<input type="password" id="inputconfrimpassword" name="inputconfrimpassword" />
                            </div>
                            <div style="clear:both"></div>
                        </div>
                        
                        <div class="regentrycontainer">
                        	<div class="regentryitemlabel"><img src="images/locationtxt.png" /></div>
                            <div class="regentryitemtextboxholder">
                            	<select id="inputlocation" name="inputlocation" style="width:100%; border:3px solid #FFF;">
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
                                                    <option value="<?php echo $row['LTID'];?>" <?php if (strcasecmp($row['StateName'], "lagos") == 0) echo 'selected="selected"';?>><?php echo $row['StateName'];?></option>
                                                    <?php
												}
											}
										}
									?>
                                </select>
                            </div>
                            <div style="clear:both"></div>
                        </div>
                        
                        <div id="regbasecontrolholder">
                        	<div style="font-size:10px; color:#FFF; font-family:Verdana, Geneva, sans-serif; width:158px; float:left; text-align:left; margin-top:5px;">
                            	By signing up you indicate that you are in agreement to the <a href="termsofservice.php" style="color:#F00; text-decoration:none; font-size:10px;">terms of service</a>.
                            </div>
                        	<div class="logincontrolbtnholder clickable" id="signupbutton">
                        		<img src="images/homebtn.png" />
                        		<div class="clickable" style="position:relative; top:-36px; width:126px; font-size:17px; font-weight:bold; font-family:sitefont; color:#FFF;"><img src="images/signuptxt.png" /></div>
                            </div>
                            
                            <div style="clear:both"></div>
                        </div>
                    </div>
                    </form>
                </div>
                <div><img src="images/regpanelbase.png" /></div>
            </div>
            <div style="clear:both"></div>
        </div>
        
        <div style="height:5px;"></div>
        <div id="midpanel">
        	<div id="newmemberspanel">
            	<div style="height:17px;"></div>
            	<div id="newmemberslideholder">
                	<div id="newmembersslidescroller">
                		<div class="newmemberdetailsholder" style="margin-left:0px;">
                    		<div class="newmemberpicframe">
                        		<div style="height:10px;"></div>
                        		<div class="newmemberimageholder">
                            		<img src="images/diva.jpg" />
                           		</div>
                        	</div>
                        	<div class="newmembernamedetailsholder">
                        		<div class="newmemnameholder">Divalava201</div>
                            	<div class="newmemagelocation">18yrs, Abuja</div>
                        	</div>
                    	</div>
                    
                    	<div class="newmemberdetailsholder">
                    		<div class="newmemberpicframe">
                        		<div style="height:10px;"></div>
                        		<div class="newmemberimageholder">
                            		<img src="images/sexygab.jpg" />
                           		</div>
                        	</div>
                        	<div class="newmembernamedetailsholder">
                        		<div class="newmemnameholder">SexxyGabby</div>
                            	<div class="newmemagelocation">24yrs, Benin</div>
                        	</div>
                    	</div>
                    
                    	<div class="newmemberdetailsholder">
                    		<div class="newmemberpicframe">
                        		<div style="height:10px;"></div>
                        		<div class="newmemberimageholder">
                            		<img src="images/niki.jpg" />
                            	</div>
                        	</div>
                       		<div class="newmembernamedetailsholder">
                        		<div class="newmemnameholder">AngelaJoy</div>
                            	<div class="newmemagelocation">22yrs, Lagos</div>
                        	</div>
                    	</div>
                    
                    	<div class="newmemberdetailsholder">
                    		<div class="newmemberpicframe">
                        		<div style="height:10px;"></div>
                        		<div class="newmemberimageholder">
                            		<img src="images/sexygab.jpg" />
                           		</div>
                        	</div>
                        	<div class="newmembernamedetailsholder">
                        		<div class="newmemnameholder">SexxyGabby</div>
                            	<div class="newmemagelocation">24yrs, Benin</div>
                        	</div>
                    	</div>
                    	
                        <div class="newmemberdetailsholder">
                    		<div class="newmemberpicframe">
                        		<div style="height:10px;"></div>
                        		<div class="newmemberimageholder">
                            		<img src="images/diva.jpg" />
                           		</div>
                        	</div>
                        	<div class="newmembernamedetailsholder">
                        		<div class="newmemnameholder">Divalava201</div>
                            	<div class="newmemagelocation">18yrs, Abuja</div>
                        	</div>
                    	</div>
                        
                    	<div class="newmemberdetailsholder">
                    		<div class="newmemberpicframe">
                        		<div style="height:10px;"></div>
                        		<div class="newmemberimageholder">
                            		<img src="images/niki.jpg" />
                            	</div>
                        	</div>
                       		<div class="newmembernamedetailsholder">
                        		<div class="newmemnameholder">AngelaJoy</div>
                            	<div class="newmemagelocation">22yrs, Lagos</div>
                        	</div>
                    	</div>
                        
                        <div class="newmemberdetailsholder">
                    		<div class="newmemberpicframe">
                        		<div style="height:10px;"></div>
                        		<div class="newmemberimageholder">
                            		<img src="images/niki.jpg" />
                            	</div>
                        	</div>
                       		<div class="newmembernamedetailsholder">
                        		<div class="newmemnameholder">AngelaJoy</div>
                            	<div class="newmemagelocation">22yrs, Lagos</div>
                        	</div>
                    	</div>

						<div class="newmemberdetailsholder">
                    		<div class="newmemberpicframe">
                        		<div style="height:10px;"></div>
                        		<div class="newmemberimageholder">
                            		<img src="images/diva.jpg" />
                           		</div>
                        	</div>
                        	<div class="newmembernamedetailsholder">
                        		<div class="newmemnameholder">Divalava201</div>
                            	<div class="newmemagelocation">18yrs, Abuja</div>
                        	</div>
                    	</div>
                    
                    	<div class="newmemberdetailsholder">
                    		<div class="newmemberpicframe">
                        		<div style="height:10px;"></div>
                        		<div class="newmemberimageholder">
                            		<img src="images/sexygab.jpg" />
                           		</div>
                        	</div>
                        	<div class="newmembernamedetailsholder">
                        		<div class="newmemnameholder">SexxyGabby</div>
                            	<div class="newmemagelocation">24yrs, Benin</div>
                        	</div>
                    	</div>
                        
                        <div class="newmemberdetailsholder">
                    		<div class="newmemberpicframe">
                        		<div style="height:10px;"></div>
                        		<div class="newmemberimageholder">
                            		<img src="images/diva.jpg" />
                           		</div>
                        	</div>
                        	<div class="newmembernamedetailsholder">
                        		<div class="newmemnameholder">Divalava201</div>
                            	<div class="newmemagelocation">18yrs, Abuja</div>
                        	</div>
                    	</div>
                    
                    	<div class="newmemberdetailsholder">
                    		<div class="newmemberpicframe">
                        		<div style="height:10px;"></div>
                        		<div class="newmemberimageholder">
                            		<img src="images/sexygab.jpg" />
                           		</div>
                        	</div>
                        	<div class="newmembernamedetailsholder">
                        		<div class="newmemnameholder">SexxyGabby</div>
                            	<div class="newmemagelocation">24yrs, Benin</div>
                        	</div>
                    	</div>
                        
                        <div class="newmemberdetailsholder">
                    		<div class="newmemberpicframe">
                        		<div style="height:10px;"></div>
                        		<div class="newmemberimageholder">
                            		<img src="images/sexygab.jpg" />
                           		</div>
                        	</div>
                        	<div class="newmembernamedetailsholder">
                        		<div class="newmemnameholder">SexxyGabby</div>
                            	<div class="newmemagelocation">24yrs, Benin</div>
                        	</div>
                    	</div>
                        
                        <div class="newmemberdetailsholder">
                    		<div class="newmemberpicframe">
                        		<div style="height:10px;"></div>
                        		<div class="newmemberimageholder">
                            		<img src="images/sexygab.jpg" />
                           		</div>
                        	</div>
                        	<div class="newmembernamedetailsholder">
                        		<div class="newmemnameholder">SexxyGabby</div>
                            	<div class="newmemagelocation">24yrs, Benin</div>
                        	</div>
                    	</div>
                        
                        <div class="newmemberdetailsholder">
                    		<div class="newmemberpicframe">
                        		<div style="height:10px;"></div>
                        		<div class="newmemberimageholder">
                            		<img src="images/sexygab.jpg" />
                           		</div>
                        	</div>
                        	<div class="newmembernamedetailsholder">
                        		<div class="newmemnameholder">SexxyGabby</div>
                            	<div class="newmemagelocation">24yrs, Benin</div>
                        	</div>
                    	</div>
                        
                         <div class="newmemberdetailsholder">
                    		<div class="newmemberpicframe">
                        		<div style="height:10px;"></div>
                        		<div class="newmemberimageholder">
                            		<img src="images/sexygab.jpg" />
                           		</div>
                        	</div>
                        	<div class="newmembernamedetailsholder">
                        		<div class="newmemnameholder">SexxyGabby</div>
                            	<div class="newmemagelocation">24yrs, Benin</div>
                        	</div>
                    	</div>
                        
                        
                    
                    	<div style="clear:both"></div>
                    </div>
                </div>
                
                <div id="slidecontrolholder">
                	<div class="slidecontrolbutton clickable"><img src="images/orangecircle.png" /></div>
                    <div class="slidecontrolbutton clickable"><img src="images/whitecircle.png" /></div>
                    <div class="slidecontrolbutton clickable"><img src="images/whitecircle.png" /></div>
                    <div class="slidecontrolbutton clickable"><img src="images/whitecircle.png" /></div>
                    <div class="slidecontrolbutton clickable"><img src="images/whitecircle.png" /></div>
                </div>
                
                <div style="clear:both"></div>
            </div>
            <div style="float:right; width:322px; height:inherit; margin-right:14px;">
            	<div style="height:15px"></div>
            	<div style="text-align:left;"><img src="images/newmember.png" /></div>
                <div style="height:8px"></div>
                <div style="font-size:12px; color:#FFF; text-align:left; font-family:Verdana, Geneva, sans-serif;">
                	Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.
                </div>
                <div style="margin-top:15px; text-align:left;"><a href="#" style="font-family:Verdana, Geneva, sans-serif; font-size:14px; color:#D6D6D6; text-align:left;">Browse our top members</a></div>
            </div>
            <div style="clear:both"></div>
        </div>
        
        <div style="height:12px;"></div>
        <div id="basepanel">
        	<div id="baseleftcol">
                <div style="text-align:left; margin-left:8px;"><img src="images/whyulikeus.png" /></div>
                <div style="font-size:14px; color:#666; font-family:Verdana, Geneva, sans-serif; text-align:left; margin-left:8px; margin-top:15px;">
                	Lorem ipsum dolor sit amet, consectetuer<br/>
adipiscing elit, sed diam nonummy nibh euism<br/>
od tincidunt ut laoreet dolore magna aliquam <br/>
erat volutpat. Ut wisi enim ad minim veniam, <br/>
quis nostrud exerci tation ullamcorper suscipit <br/>
lobortis nisl ut aliquip ex ea commodo <br/>
consequat. Duis autem vel eum iriure dolor <br/>
in hendrerit in vulputate velit esse molestie <br/>
consequat, vel illum dolore eu feugiat <br/>
nulla facilisis at vero eros et accumsan <br/>
et iusto odio dignissim qui blandit <br/>
praesent luptatum zzril delenit augue<br/>
duis dolore te feugait nulla facilisi.<br/>
<br/>
Quis nostrud exerci tation <br/>
ullamcorper suscipit lobortis nisl ut <br/>
aliquip ex ea commodo consequat. <br/>
Duis autem vel eum iriure dolor in <br/>
hendrerit in vulputate velit esse <br/>
molestie consequat, vel illum dolore <br/>
eu feugiat nulla facilisis at vero eros et <br/>
accumsan et iusto odio dignissim qui <br/>
blandit praesent luptatum zzril delenit <br/>
augue duis...
                </div>
                <div style="text-align:left; color:#FF3399; text-decoration:none; margin-left:10px; margin-top:15px;"><a href="#" style="text-align:left; color:#FFF; text-decoration:none;"><img src="images/learnmore.jpg" /></a></div>
            </div>
            
            
            <div id="baserightcol">
            	<div style="height:400px; background-color:#95004A;">
               		<div style="height:5px;"></div>
            		<div style="width:317px; height:78px; border-bottom:1px solid #FF3399"><img src="images/findmatch.png" /></div>
                    <div style="width:317px; min-height:78px; height:auto; border-top:1px solid #FF3399; margin-top:1px;">
                    	<div style="height:20px;"></div>
                    	<div class="findmatchinputcontainer">
                        	<div class="findmatchitemlabel"><img src="images/iamatxt.png" /></div>
                            <div class="findmatchitemtextbox">
                            	<select id="findinputiam" style="width:100%; border:3px solid #FFF;">
                                	<option value="1">A Man</option>
                                    <option value="2">A Woman</option>
                                </select>
                            </div>
                            <div style="clear:both"></div>
                        </div>
                        
                        <div class="findmatchinputcontainer">
                        	<div class="findmatchitemlabel"><img src="images/seekingtxt.png" /></div>
                            <div class="findmatchitemtextbox">
                            	<select id="findinputseeking" style="width:100%; border:3px solid #FFF;">
                                	<option value="2">A Woman</option>
                                	<option value="1">A Man</option>
                                </select>
                            </div>
                            <div style="clear:both"></div>
                        </div>
                        
                        <div class="findmatchinputcontainer">
                        	<div class="findmatchitemlabel"><img src="images/foratxt.png" /></div>
                            <div class="findmatchitemtextbox">
                            	<select id="findinputfora" style="width:100%; border:3px solid #FFF;">
                                	<option value="1">Relationship</option>
                                	<option value="2">Friend</option>
                                    <option value="3">Casual Affair</option>
                                    <option value="4">Quicky</option>
                                </select>
                            </div>
                            <div style="clear:both"></div>
                        </div>
                        
                        <div class="findmatchinputcontainer">
                        	<div class="findmatchitemlabel"><img src="images/fromtxt.png" /></div>
                            <div class="findmatchitemtextbox" style="width:60px; float:left; margin-left:5px;">
                            	<select id="findinputfromage" style="width:100%; border:3px solid #FFF;">
                                	<?php
										for ($i = 18; $i <= 90; $i++)
										{
											?>
                                            <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                            <?php
										}
									?>
                                </select>
                            </div>
                            <div style="clear:both"></div>
                        </div>
                        
                        <div class="findmatchinputcontainer">
                        	<div class="findmatchitemlabel"><img src="images/totxt.png" /></div>
                            <div class="findmatchitemtextbox" style="width:60px; float:left; margin-left:5px;">
                            	<select id="findinputtoage" style="width:100%; border:3px solid #FFF;">
                                	<?php
										for ($i = 18; $i <= 90; $i++)
										{
											?>
                                            <option value="<?php echo $i;?>" <?php if ($i == 25) echo 'selected="selected"';?>><?php echo $i;?></option>
                                            <?php
										}
									?>
                                </select>
                            </div>
                            <div style="clear:both"></div>
                        </div>
                        
                        <div style="height:20px;"></div>
                        <div id="findcontainterbasepanel">
                        	<div style="float:left; width:144px; margin-top:5px;">
                            	<img src="images/withphoto.png" /> <input type="checkbox" id="searchwithphoto" name="searchwithphoto" />
                                <div style="clear:both"></div>
                            </div>
                        	<div class="logincontrolbtnholder clickable" id="findmarchsearchbutton">
                        		<img src="images/homebtn.png" />
                        		<div class="clickable" style="position:relative; top:-36px; width:126px; font-size:17px; font-weight:bold; font-family:sitefont; color:#FFF;"><img src="images/searchtxt.png" /></div>
                            </div>
                            <div style="clear:both"></div>
                        </div>
                    </div>
                </div>
                <div id="homememberscount">Members count: 29,093</div>
                <div id="sociallinks" style="margin-top:25px; margin-right:17px;">
                	<div style="width:auto; height:auto; float:right;"><a href="#" style="text-decoration:none; color:#FFF;"><img src="images/sociallink.jpg" /></a></div>
                	<div style="clear:both"></div>
                </div>
            </div>
            
            <div style="clear:both"></div>
        </div>
        
        <div id="homefooter">
        	<div style="height:22px"></div>
            <div id="aboutustabholder">
            	<a href="aboutus.php" style="color:#F0F0F0; text-decoration:none;"><img src="images/aboutustab.png" /></a>
            </div>
        	<div style="font-size:15px; color:#999; float:left; width:auto; height:auto; margin-left:12px;">&copy; <?php echo date("Y"); ?> <label style="color:#FFF;">iwanshokoto.com</label>. All rights reserved</div>
            <div id="termsofservicelink"><a href="termsofservice.php">Terms of service</a> | <a href="privacypolicy.php">Privacy policy</a> | <a href="aboutus.php">About us</a> | <a href="faq.php">FAQs</a></div>
            
        	<div style="clear:both"></div>
        </div>
	</div>
</div>
</body>
</html>