<div id="membershortprofileholder">
    <div id="memshortavatar">
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
    <div id="memshortdetails">
        <div style="height:5px"></div>
        <label><?php echo $_SESSION['TheUser'];?></label>
        <div style="height:5px"></div>
        <label><?php echo ucfirst(strtolower($member->location));?>, Nigeria</label>
    </div>
    <div style="clear:both"></div>
</div>