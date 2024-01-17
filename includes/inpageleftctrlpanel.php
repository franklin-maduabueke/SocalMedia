<div id="controlpanelholder">
                	<div class="controlitem">
                    	<img src="<?php echo $pathPrefix;?>images/scoreicon.jpg" />
                        <div class="controlitemlabel">
							<?php if (PAGE_NAME != 'myscorecard') { ?><a href="scorecard.php"><?php } ?>My Score card<?php if (PAGE_NAME != 'myscorecard') { ?></a><?php } ?>
                        </div>
                        <div style="clear:both"></div>
                    </div>
                    <div class="controlitem">
                    	<img src="<?php echo $pathPrefix;?>images/galleryicon.jpg" />
                        <div class="controlitemlabel">
							<?php if (PAGE_NAME != 'mygallery') { ?><a href="gallery.php"><?php } ?>My Gallery<?php if (PAGE_NAME != 'mygallery') { ?></a><?php } ?>
                        </div>
                        <div style="clear:both"></div>
                    </div>
                    <div class="controlitem" style="display:none">
                    	<img src="<?php echo $pathPrefix;?>images/meetupicon.jpg" />
                        <div class="controlitemlabel"><?php if (PAGE_NAME != 'meetups') { ?><a href="meetups.php"><?php } ?>Meetups<?php if (PAGE_NAME != 'meetups') { ?></a><?php } ?></div>
                        <div style="clear:both"></div>
                    </div>
                    <div class="controlitem">
                    	<img src="<?php echo $pathPrefix;?>images/profileicon.jpg" />
                        <div class="controlitemlabel"><?php if (PAGE_NAME != 'profile') { ?><a href="profile.php"><?php } ?>Profile<?php if (PAGE_NAME != 'profile') { ?></a><?php } ?></div>
                        <div style="clear:both"></div>
                    </div>
                    <div class="controlitem" style="display:none">
                    	<img src="<?php echo $pathPrefix;?>images/imprinticon.jpg" />
                        <div class="controlitemlabel"><?php if (PAGE_NAME != 'imprints') { ?><a href="imprints.php"><?php } ?>Imprints<?php if (PAGE_NAME != 'imprints') { ?></a><?php } ?></div>
                        <div style="clear:both"></div>
                    </div>
                    <div class="controlitem">
                    	<img src="<?php echo $pathPrefix;?>images/naughtymeicon.png" />
                        <div class="controlitemlabel"><?php if (PAGE_NAME != 'naughtyme') { ?><a href="naughtyme.php"><?php } ?>Naughty Me!<?php if (PAGE_NAME != 'naughtyme') { ?></a><?php } ?></div>
                        <div style="clear:both"></div>
                    </div>
                    <div class="controlitem" style="display:none">
                    	<img src="<?php echo $pathPrefix;?>images/crediticon.jpg" />
                        <div class="controlitemlabel"><?php if (PAGE_NAME != 'credits') { ?><a href="credits.php"><?php } ?>Credits (600)<?php if (PAGE_NAME != 'credits') { ?></a><?php } ?></div>
                        <div style="clear:both"></div>
                    </div>
                    <div class="controlitem" style="display:none">
                    	<img src="<?php echo $pathPrefix;?>images/giftcarticon.jpg" />
                        <div class="controlitemlabel"><?php if (PAGE_NAME != 'giftcart') { ?><a href="giftcart.php"><?php } ?>Gift cart<?php if (PAGE_NAME != 'giftcart') { ?></a><?php } ?></div>
                        <div style="clear:both"></div>
                    </div>
                    <div class="controlitem">
                    	<img src="<?php echo $pathPrefix;?>images/friendreqicon.jpg" />
                        <div class="controlitemlabel"><?php $friendrequests = $theMember->getFriendRequests();?><?php if (PAGE_NAME != 'friendrequest') { ?><a href="<?php if ($friendrequests['rcount'] == 0) echo "#"; else echo 'friendrequest.php';?>"><?php } ?>Friend request ( <?php echo $friendrequests['rcount'];?> )<?php if (PAGE_NAME != 'friendrequest') { ?></a><?php } ?></div>
                        <div style="clear:both"></div>
                    </div>
                    <div class="controlitem">
                    	<img src="<?php echo $pathPrefix;?>images/kissicon.jpg" />
                        <div class="controlitemlabel"><?php if (PAGE_NAME != 'kisses') { ?><a href="kisses.php"><?php } ?>Flirts<?php if (PAGE_NAME != 'kisses') { ?></a><?php } ?></div>
                        <div style="clear:both"></div>
                    </div>
                    <div class="controlitem" style="display:none">
                    	<img src="<?php echo $pathPrefix;?>images/diaryicon.jpg" />
                        <div class="controlitemlabel"><?php if (PAGE_NAME != 'mydiary') { ?><a href="mydiary.php"><?php } ?>My Diary<?php if (PAGE_NAME != 'mydiary') { ?></a><?php } ?></div>
                        <div style="clear:both"></div>
                    </div>
                    <div class="controlitem" style="border:none;">
                    	<img src="<?php echo $pathPrefix;?>images/setting.png" />
                        <div class="controlitemlabel"><?php if (PAGE_NAME != 'settings') { ?><a href="settings.php"><?php } ?>Settings<?php if (PAGE_NAME != 'settings') { ?></a><?php } ?></div>
                        <div style="clear:both"></div>
                    </div>
                </div>