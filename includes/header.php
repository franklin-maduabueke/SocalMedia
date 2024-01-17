<div style="background-color:#95004A">
<div id="headerHolder">
	<div id="logoholder">
    	<!--if session does not exits refer back to index-->
    	<a href="<?php if (!$sessionGood) echo $pathPrefix;?>index.php" style="text-decoration:none; color:#95004A;"><img src="<?php echo $pathPrefix;?>images/innerpageslogo.png" alt="iwanshokoto.com. It's a blind date experience" /></a>
    </div>
    <div id="headerrightcol">
    <?php
		if (PAGE_NAME == "home")
		{
	?>
    	<div id="sociallinksholder" style="float:right; margin-top:17px;">
        	<div style="font-size:11px; font-weight:bold; color:#FFF;float:left; width:auto; height:inherit; padding-top:5px; margin-right:5px;">Follow us:</div>
            <div style="float:left; width:auto; height:inherit;">
            	<a href="#" style="border:1px solid #007DB8; text-decoration:none; color:#007DB8"><img src="<?php echo $pathPrefix;?>images/fbicon.png" /></a>
                <a href="#" style="border:1px solid #007DB8; text-decoration:none; color:#007DB8"><img src="<?php echo $pathPrefix;?>images/twiticon.png" /></a>
            </div>
             <div style="clear:both"></div>
        </div>
     <?php
		}
		else
		{
			if ($sessionGood)
			{
	 ?>
     	<div id="mempageheadercontrolsholder">
        	<div class="mphcontrol" style="min-width:40px; max-width:40px">
            	<div class="mphclabel" style="margin:0px; padding:0px;"><div style="height:8px"></div><a href="<?php echo $pathPrefix;?>ui/index.php" style="color:#005680;"><img src="<?php echo $pathPrefix;?>images/homeicon.png" /></a></div>
            </div>
            
        	<div class="mphcontrol" <?php if (PAGE_NAME == "friends") echo 'style="background-color:#B30059;"' ?>>
            	<div class="mphctrltotalsummary">
                	<div style="margin:0px;">
						<?php 
							$friends = $theMember->getFriends();
							echo number_format($friends["fcount"]); 
						?>
                    </div>
                </div>
            	<div class="mphclabel"><a href="<?php echo $pathPrefix;?>ui/friends.php">Friends</a></div>
            </div>
            
            <div class="mphcontrol" <?php if (PAGE_NAME == "match") echo 'style="background-color:#B30059;"' ?>>
            	<div class="mphclabel clickable">Search</div>
                <div class="dropdownoptionavailableicon">
                    <div style="clear:both"></div>
                    <div class="mphctrldropdown">
                    	<div class="mphcdropitem">
                        	<div class="mphcdropitemimage">
                            	<img src="<?php echo $pathPrefix;?>images/paintzoom.png" style="width:25px; height:25px;" />
                            </div>
                            <div class="mphcdropitemlabel"><a href="<?php echo $pathPrefix;?>ui/match.php">Find Match</a></div>
                            <div style="clear:both"></div>
                        </div>
                        
                        <div class="mphcdropitem" style="border:none;">
                        	<div class="mphcdropitemimage">
                            	<img src="<?php echo $pathPrefix;?>images/online.png" style="width:25px; height:20px;" />
                            </div>
                            <div class="mphcdropitemlabel"><a href="<?php echo $pathPrefix;?>ui/online.php">Online Members</a></div>
                            <div style="clear:both"></div>
                        </div>
                        <!--
                        <div class="mphcdropitem" style="border:none;">
                        	<div class="mphcdropitemimage" style="visibility:hidden;">
                            	<img src="<?php echo $pathPrefix;?>images/notifyseticon.jpg" />
                            </div>
                            <div class="mphcdropitemlabel"><a href="#">Browse Members</a></div>
                            <div style="clear:both"></div>
                        </div>
                        -->
                    </div>
                </div>
            </div>
            
            <div class="mphcontrol" <?php if (PAGE_NAME == "messages") echo 'style="background-color:#B30059;"' ?>>
            	<div class="mphctrltotalsummary"><div style="margin:0px;"><?php echo number_format(count($theMember->getInboxMessages()));?></div></div>
            	<div class="mphclabel"><a href="<?php echo $pathPrefix;?>ui/messages.php">Messages</a></div>
            </div>
            
            <div class="mphcontrol clickable">
            	<div class="mphclabel">My Account</div>
                <div class="dropdownoptionavailableicon">
                	<div class="activespot"></div>
                    <div style="clear:both"></div>
                    <div class="mphctrldropdown">
                    	<div class="mphcdropitem">
                        	<div class="mphcdropitemimage">
                            	<img src="<?php echo $pathPrefix;?>images/logouticon.jpg" />
                            </div>
                            <div class="mphcdropitemlabel"><a href="<?php echo $pathPrefix; ?>processing/logout.php">Logout</a></div>
                            <div style="clear:both"></div>
                        </div>
                        
                        <div class="mphcdropitem">
                        	<div class="mphcdropitemimage">
                            	<img src="<?php echo $pathPrefix;?>images/lockicon.jpg" />
                            </div>
                            <div class="mphcdropitemlabel"><a href="#">Change Password</a></div>
                            <div style="clear:both"></div>
                        </div>
                        
                        <div class="mphcdropitem">
                        	<div class="mphcdropitemimage">
                            	<img src="<?php echo $pathPrefix;?>images/notifyseticon.jpg" />
                            </div>
                            <div class="mphcdropitemlabel"><a href="#">Notification Settings</a></div>
                            <div style="clear:both"></div>
                        </div>
                        
                        <div class="mphcdropitem" style="border:none">
                        	<div class="mphcdropitemimage">
                            	<img src="<?php echo $pathPrefix;?>images/andriodicon.jpg" />
                            </div>
                            <div class="mphcdropitemlabel"><a href="#">Get Mobile App</a></div>
                            <div style="clear:both"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
			}
		}
		?>
        <div style="clear:both"></div>
    </div>
    <div style="clear:both"></div>
</div>
</div>