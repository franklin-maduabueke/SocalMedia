<?php
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../classes/ciwanshokoto.php");
	include("../classes/cmember.php");
	
	define('PAGE_NAME', 'mygallery');
	
	$pathPrefix = "../";
	
	$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERNAME, IWANSHOKOTO_DBPASSWORD);
	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
	{
		$iwanshokoto = new IWanShokoto($dbConn);
		$hottestMembers = $iwanshokoto->getHottestMembers();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>iwanshokoto. Welcome <?php echo $_GET['uname'];?></title>
</head>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/globals.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/innerpage.css"/>

<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix;?>stylesheet/activateaccount.css"/>
<script src="<?php echo $pathPrefix;?>scripts/jQuery1_7.js" type="text/javascript"></script>
<script src="<?php echo $pathPrefix;?>scripts/mphcsummarybox.js" type="text/javascript"></script>
<script type="text/javascript">
	var pagePathPrefix = '<?php echo $pathPrefix?>';
</script>

<body style="background-color:#F0F0F0;">
<div id="pageContainer" align="center" style="background-color:#F0F0F0;">
	<?php include($pathPrefix . "includes/header.php"); ?>
	<div id="mainContentHolder">
    	<div style="width:300px; height:414px; position:absolute; z-index:2; top:-300px; left:830px;">
             <img src="<?php echo $pathPrefix;?>images/sexydancer.png" />
        </div>
        <div style="height:28px"></div>
        
        <div id="mainPageContentArea" style="background-color:#1F191B">
        	<div style="height:325px; background-image:url(<?php echo $pathPrefix;?>images/aboutusbg.jpg);">
            	<div style="width:560px; height:325pxpx; float:right; overflow:hidden;">
                	<img src="<?php echo $pathPrefix;?>images/activateaccshh.png" />
                </div>
                <div style="clear:both"></div>
                <div style="height:325px; position:relative; top:-320px; left:0px;">
                	<div style="height:64px; width:900px; border-bottom:1px solid #F0F0F0">
                    	<div style="height:18px;"></div>
                    	<div style="font-size:26px; color:#E62174; text-align:left;"><img src="../images/activateacctxt.png" /></div>
                    </div>
                    
                    <div style="width:900px; height:auto">
                    	<div style="float:left; width:590px; height:397px;">
                        	<div style="font-size:17px; color:#1F191B; text-align:justify; padding-top:10px; width:882px">
                            	Hi <label style="font-weight:bold;"><?php echo $_GET['uname'];?></label>, we will need you to activate your account. An email has been sent to you for that. When you get it just follow the instruction and you'll be on your way to enjoying all the services iwanshokoto.com provides.
<p/>
We are waiting!
								<div style="font-size:26px; text-align:left; color:#E52073; margin-top:30px;"><a href="index.php" style="font-size:26px; text-align:left; color:#E52073; text-decoration:none;"><img src="<?php echo $pathPrefix;?>images/followusonfb.png" /></a></div>
							</div>
                    	</div>
                    	
                    	<div style="clear:both"></div>
                    </div>
                    <div style="width:900px; height:450px; position:relative; top:-142px; z-index:1px; background-color:#1F191B">
                    	<div style="height:25px;"></div>
                    	<div style="height:397px;text-align:left;">
                        	<img src="<?php echo $pathPrefix;?>images/pplyouwilllike.png" />
                        </div>
                        <div style="height:30px;"></div>
                        
                        <div id="pplyouwilllikeliistholder" style="position:absolute; top:90px;">
                        <?php
							if (isset($hottestMembers))
							{
								$i = 1;
								foreach ($hottestMembers as $value)
								{
									if ($i > 10) //limit draw to 8
										break;
									
									$hotmember = json_decode($value);
									if ($hotmember && property_exists($hotmember, "memgenid"))
									{
										//member must have pic.
										try
										{
											$member = new Member($hotmember->memgenid, $dbConn);
											$memberPic = $member->getMemberPic(100, NULL, 75, TRUE);
											if (!$memberPic)
												continue;
											
											$memberPic = str_replace("fetchmemberpic.php?", "matchfetchpic.php?iwsid=" . $hotmember->memgenid . "&", $memberPic);
										}
										catch (Exception $ex)
										{
										}
						?>
                        	<div class="newmemberdetailsholder" <?php if (($i % 6) == 0) 'style="margin-left:0px;"';?>>
                    			<div class="newmemberpicframe">
                        			<div style="height:10px;"></div>
                        			<div class="newmemberimageholder">
                            			<?php echo $memberPic;?>
                           			</div>
                        		</div>
                        		<div class="newmembernamedetailsholder">
                        			<div class="newmemnameholder"><?php echo $hotmember->username;?></div>
                            		<div class="newmemagelocation"><?php echo $hotmember->age . ", " . ucfirst(strtolower($hotmember->location));?></div>
                        		</div>
                    		</div>
                            <?php
									}
							?>
                        <?php
								}
								?>
                             <div style="clear:both"></div>
                        </div>
                                <?php
							}
						?>
                    	<div style="clear:both"></div>
                    </div>
                </div>
            </div>
        </div>
        <div style="height:44px;"></div>
	</div>
    <div style="clear:both"></div>
    <?php include($pathPrefix . "includes/footer.php"); ?>
</div>
</body>
</html>