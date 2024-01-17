<div id="peekersoftheweekholder">
<?php
	$peekers = $theMember->getWeekPeekers(1, 5); //get top five
	
	if (count($peekers))
	{
?>
<div style="height:37px; background-color:#E62174; margin-bottom:10px;"><div style="color:#FFF; font-size:13px; font-weight:bold; text-align:left; padding-left:10px; padding-top:10px;"><a href="#" style="font-size:13px; color:#FFF; font-weight:bold; text-decoration:none; display:block;">Peekers of the week</a></div></div>
		<?php
			foreach ($peekers as $key=>$value)
			{
				try
				{
					$peeker = new Member($key, $dbConn);
					$peekerJson = json_decode($peeker->getProfile());
					if ($peekerJson && property_exists($peekerJson, "memgenid"))
					{
		?>
        <div class="peekerholder">
            <div class="peekeravatarholder">
                <a href="icontrol.php?iwsid=<?php echo $peekerJson->memgenid;?>" style="text-decoration:none; color:#FFF;">
					<?php
                        if ($memPic = $peeker->getMemberPic(52, NULL, 100, true))
                        {
                            $memPic = str_replace("fetchmemberpic.php?", "matchfetchpic.php?iwsid=" . $peekerJson->memgenid . "&", $memPic);
                            echo $memPic;
                        }
                        else
                        {
                            if (strcmp($peekerJson->gender, "MALE") == 0)
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
            <div class="peekerdetailsholder">
                <label><?php echo $peekerJson->username;?></label> <label style="font-size:10px; color:#E62174;">(<?php echo strtolower(substr($peekerJson->gender, 0, 1));?>)</label>
                <br/>
                <label><?php echo ucfirst(strtolower($peekerJson->location));?></label>
                <br/>
                <label style="font-size:10px; color:#007DB8; font-weight:normal;"><?php $valueJson = json_decode($value); echo date("jS M, Y", strtotime($valueJson->lastpeeked));?></label>
            </div>
            <div style="clear:both"></div>
        </div>
        <?php
					}
				}
				catch (Exception $ex)
				{
					continue;
				}
			}
		?>
        <?php
			if ($peekers['weekPeekersTotal'] > 5)
			{
		?>
        <div style="font-size:11px; color:#E62174; text-align:left;">
        	<a href="#" style="font-size:11px; color:#E62174; text-decoration:none; font-weight:bold;">See more &raquo;</a>
       	</div>
        <?php
			}
		?>
<?php
	}
?>
</div>