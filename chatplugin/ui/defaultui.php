<div id="jd_chatui_interface_holder" align="center">
	<div id="jd_chatui_interface_mainpanel">
    	<div class="jd_chatui_interface_titlebar">
        	<div id="jd_chatui_interface_closebutton" class="clickable">x</div>
            <!--<div id="jd_chatui_interface_minimizebutton" class="clickable">-</div>-->
        	<div style="clear:both"></div>
        </div>
        <div id="jd_chatui_interface_toolspanel">
        	<div class="jd_chatui_interface_toolspanel_item clickable" style="border-left:none;" id="jdcuisidepanelitem">
            	<div style="height:2px;"></div>
            	<img src="<?php echo $pathPrefix;?>chatplugin/images/sidepanel.png" />
            </div>
            <div class="jd_chatui_interface_toolspanel_item clickable" id="jdcuisettingitem">
            	<div style="height:5px;"></div>
            	<img src="<?php echo $pathPrefix;?>chatplugin/images/settings.png" />
            </div>
            <div class="jd_chatui_interface_toolspanel_item clickable" id="jdcuitakepicitem">
            	<div style="height:5px;"></div>
            	<img src="<?php echo $pathPrefix;?>chatplugin/images/takepic.png" />
            </div>
            <div class="jd_chatui_interface_toolspanel_item clickable" id="jdcuismileyitem" style="border-right:none; width:61px">
            	<div style="height:5px;"></div>
            	<img src="<?php echo $pathPrefix;?>chatplugin/images/smileys.png" />
            </div>
            <div style="clear:both"></div>
        </div>
        
        <div id="jd_chatui_interface_activeconversationpanel">
        	<div style="height:6px;"></div>
            <div style="width:232px; height:57px;">
        		<div id="jd_chatui_interface_activeimageholder">
                	<div style="width:54px; height:54px; overflow:hidden; -ms-overflow:hidden;" id="activechatpatnerthumbnail"><img src="<?php echo $pathPrefix;?>images/av1.jpg" /></div>
            	</div>
                
                <div id="jd_chatui_interface_activeinfopanel">
                	<div style="font-weight:bold; font-size:13px; margin-bottom:3px" id="activeusername">Osama2001</div>
                    <div style="font-weight:bold; font-size:11px; margin-bottom:5px" id="activeextradetails">Software Developer</div>
                    <div style="font-size:10px; font-style:italic;" id="chatongoingtyping"></div>
                </div>
        		<div style="clear:both"></div>
            </div>
        </div>
        
        <div id="jd_chatui_interface_conversationwindow" class="mcs_container">
        	<div class="customScrollBox">
				<div class="container">
    				<div class="content" style="padding-bottom:10px;">
                    </div>
                </div>
                <div class="dragger_container">
    				<div class="dragger"></div>
				</div>
            </div>
        </div>
        
        <div id="jd_chatui_interface_conversationtextboxholder">
        	<div style="height:3px"></div>
        	<div style="width:190px; height:32px; float:left; margin-left:3px; overflow:hidden;">
            	<input type="text" id="jd_chatui_interface_conversationtextbox" style="width:110%; height:110%; position:relative; top:-2px; left:-2px; border:none; font-size:12px; padding-left:4px; padding-right:4px;" />
            </div>
            <div id="jd_chatui_interface_conversationtextboxsendbutton" class="clickable" style="width:35px; height:32px; float:left; color:#FF9900; font-size:12px; font-weight:bold;">
            	<div style="margin-top:2px;"><img src="<?php echo $pathPrefix;?>chatplugin/images/sendmsg.png" /></div>
            </div>
            <div style="clear:both"></div>
        </div>
    </div>
    
    <div id="jd_chatui_interface_cascadepaneleftside">
    	<div class="jd_chatui_interface_titlebar">
        	<div id="jd_chatui_interface_sidepanelcollapsebutton" class="clickable"><img src="<?php echo $pathPrefix;?>chatplugin/images/collapse.png" /></div>
            <div style="clear:both"></div>
        </div>
        
        <div style="height:317px; overflow:hidden; -ms-overflow:hidden;">
            <!-- show when user click active chats icon -->
            <div id="jd_chatuicascadeactivechattersholder" class="icondialogpanel">
                <div class="customScrollBox">
                    <div class="container" id="onlineuserslist">
                        <div class="content">
                        </div>
                    </div>
                    <div class="dragger_container">
                        <div class="dragger"></div>
                    </div>
                </div>
            </div>
            
            <!-- show chatrooms list -->
            <div id="jd_chatuicascadechatroomslistholder" class="icondialogpanel">
            	<div class="icondialogpaneltitlebar">Chat Rooms</div>
                <div class="customScrollBox">
                    <div class="container" id="chatroomslisting">
                        <div class="content">
                        </div>
                    </div>
                    <div class="dragger_container">
                        <div class="dragger"></div>
                    </div>
                </div>
            </div>
            
			<!-- show when user click a chat room from the collection -->
			<div id="jd_chatuicascadeactivechatroomsholder" class="icondialogpanel" style="background-color:#FFF;">
            	<div class="icondialogpaneltitlebar"><a href="#" id="showchatroomslistlink" style="text-decoration:none; color:#336">&nbsp;</a> &nbsp;Active chat rooms</div>
                <div class="customScrollBox">
                    <div class="container" id="roomsjoinedlist">
                        <div class="content">
                        	<div id="joiningroomloadingicon" style="color:#007DB8; font-size:11px; border-bottom:1px solid #CCC; display:none; text-align:left; padding-left:3px;"><img src="<?php echo $pathPrefix?>images/lightbox-ico-loading.gif" style="width:20px; height:20px;" /> </div>
                        </div>
                    </div>
                    <div class="dragger_container">
                        <div class="dragger"></div>
                    </div>
                </div>
            </div>
            
            <!-- show when user click smileys icon -->
            <div id="jd_chatuicascadesmileysholder" class="icondialogpanel" style="background-color:#FFF;">
                <div class="customScrollBox">
                    <div class="container" id="smileyslist">
                        <div class="content">
                        	<?php
								$smileyIds = array("blowupvexsmiley", "biggrinsmiley", "gumpysmiley", "hugmesmiley", "shockedsmiley", "smushsmiley", "secretssmiley", "yawnsmiley", "blushsmiley", "charmsmiley", "praysmiley", "munchsmiley", "wailsmiley", "funnyfacesmiley", "frightsmiley", "overjoysmiley", "mobsmiley", "scopeoutsmiley", "goberzerksmiley", "holdheadsmiley", "puffedcheeksmiley", "haverosesmiley", "yaikessmiley", "coolsmiley", "wackteethsmiley", "vampiresmiley", "drupsmiley", "blackeyesmiley", "exhaustionsmiley", "uwwwsmiley", "embarasssmiley", "babylooksmiley");
								for ($i = 1; $i <= 40; $i++)
								{
							?>
                        	<div class="chatsmileyicon clickable" id="<?php echo $smileyIds[$i - 1];?>">
                            	<img src="<?php echo $pathPrefix;?>chatplugin/images/chatsmileys/<?php echo $i;?>.png" />
                            </div>
                            <?php
								}
							?>
                            <div style="clear:both;"></div>
                        </div>
                    </div>
                    <div class="dragger_container">
                        <div class="dragger"></div>
                    </div>
                </div>
            </div>
            
            <!-- show when user click settigs icon -->
            <div id="jd_chatuicascadechatsettingsholder" class="icondialogpanel">
                <div class="customScrollBox">
                    <div class="container" id="smileyslist">
                        <div class="content">
                        </div>
                    </div>
                    <div class="dragger_container">
                        <div class="dragger"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="jd_chatuiactivechattertabsholder">
        	<div>
                <div class="tab clickable" id="chatprivatetab">
                    <div style="height:10px;"></div>
                    <div>Private</div>
                </div>
                
                <div class="tab clickable" id="chatroomtab">
                	<div style="height:10px;"></div>
                    <div>Rooms</div>
                </div>
                <div style="clear:both"></div>
            </div>
        </div>
    </div>
</div>