<!--
* Sites base tools panel that floats with the screen
-->
<div class="chatScreenContainer" style="width:auto">
	<?php include($pathPrefix . "chatplugin/ui/defaultui.php"); ?>
</div>
<div id="jdchatRoomsContainer">
</div>
<div id="sitetoolspanelholder" class="chatScreenContainer">
	<div class="basetoolsitem clickable" style="width:24px; border-right:none;" id="toolpanelcollapseicon">
    	<div style="font-size:11px; font-weight:bold; margin-top:11px;">
        	<label id="toolspanelcollapse">&lsaquo;&lsaquo;</label><label id="toolspanelexpand" style="display:none">&rsaquo;&rsaquo;</label>
        </div>
        <input type="hidden" class="dyntooltip:basictooltip" value="Collapse panel" />
    </div>
	<div class="basetoolsitem clickable" id="toolpanelchatlauchicon">
    	<img src="<?php echo $pathPrefix;?>chatplugin/images/chatuiinactive.png" style="margin-top:2px;" />
        <input type="hidden" class="dyntooltip:basictooltip" value="Click to show chat panel" />
    </div>
    <div class="basetoolsitem clickable">
    	<img src="<?php echo $pathPrefix;?>chatplugin/images/online.png" style="margin-top:4px;" />
        <input type="hidden" class="dyntooltip:basictooltip" value="See who's currently online" />
    </div>
    <div class="basetoolsitem clickable">
    	<img src="<?php echo $pathPrefix;?>chatplugin/images/sms.png" style="margin-top:2px;" />
        <input type="hidden" class="dyntooltip:basictooltip" value="Send SMS" />
    </div>
    <div class="basetoolsitem clickable">
    	<img src="<?php echo $pathPrefix;?>chatplugin/images/kiss.png" style="margin-top:13px;" />
        <input type="hidden" class="dyntooltip:basictooltip" value="Blow a kiss on your kiss alerts" />
    </div>
    <!--
    <div class="basetoolsitem clickable" id="toolpanelchatroomslauchicon">
    	<img src="<?php echo $pathPrefix;?>chatplugin/images/chatroom.png" style="margin-top:3px;" />
        <input type="hidden" class="dyntooltip:basictooltip" value="Meet new people in our chatrooms" />
    </div>
    -->
    <?php
		if (PAGE_NAME == "mygallery")
		{
	?>
    <div class="basetoolsitem clickable" style="border-left:none;">
    	<img src="<?php echo $pathPrefix;?>chatplugin/images/webcam.png" style="margin-top:3px;" />
        <input type="hidden" class="dyntooltip:basictooltip" value="Take a pic" /> 
    </div>
     <?php
		}
	?>
    <div style="clear:both"></div>
</div>
