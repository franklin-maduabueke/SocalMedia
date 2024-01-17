// JavaScript Document
/*******************************************************
* Script sets up the chat UI
* Author: Franklin N. Maduabueke, dueal21@yahoo.co.uk, dueal21@gmail.com, www.crewtecia.com
* Date: 6:24 AM Jun/11/2012
* No part of this work may be used in part or whole
* without the prior written consent of the author.
********************************************************/
//manage positioning of interface as fixed element
var docScrollTop = null;
var chatScreenMaxHeight = null;
var chatScreenMaxWidth = null;
var chatScreenMinHeight = null;
var chatScreenMinWidth = 150;
var marginRight = 7;
var refreshRate = 0.2 * 1000;

function createChatPanel()
{
	//change chatScreenContainer display
	$('div.chatScreenContainer').css({
									 'display': 'block'
									 });
}

function adjustPanelsLeft() {
	//adjust left
	/*
	$('div.chatScreenContainer').each( function (index) {
														if (index > 0)
														{
															var pleft = $('div.chatScreenContainer').eq(index - 1).css("left");
															pleft = pleft.substr(0, pleft.indexOf("px"));
															
															$(this).css({"left": pleft - $(this).width() - marginRight});
														}
												});
	*/
}

var windowHeight = $(window).height();
var screenWidth = $(window).width();
var screenHeight = $(window).height();

function refreshchatScreens()
{
	if (windowHeight != $(window).height())
	{
		screenHeight = windowHeight = $(window).height();
		$('div.chatScreenContainer').each ( function () { 
													  $(this).css({
														"position": "fixed",
														"top": (screenHeight - $(this).height()) + "px"
																  });
										 });
											
	}
}

function refreshchatRoomsPanel()
{
	//alert("Called to refresh");
	/*var iconlauncher = $('#toolpanelchatroomslauchicon');
	var roomspanel = $('#jdchatRoomsContainer');
	roomspanel.css({
				   'top': screenHeight - roomspanel.height() - iconlauncher.height() - 2 + 'px',
				   'left': iconlauncher.offset().left + 'px',
				   'display': 'block'
				   });*/
	
	var roomspanel = $('#jd_chatuicascadechatroomslistholder');
	
	var chatRooms = $('body').data('jdChatLibrary').UIManager.getChatrooms();
	if (chatRooms.length)
	{
		roomspanel.empty();
		for (i = 0; i < chatRooms.length; i++)
			roomspanel.append('<div class="chatroomitem"><input type="hidden" value="' + chatRooms[i].getID() + '" /><a href="#">' + chatRooms[i].getName() + '</a></div>');
		
		//bind the click event to make the user join room
		$('div.chatroomitem a').click(function (event) {
			event.preventDefault();
			//notify PostMan to get messages from this room and post users room messages to this room
			var roomid = $(this).siblings('input[type=hidden]').val();
			var roomname = "";
			try
			{
				roomname = $('body').data('jdChatLibrary').UIManager.getChatroomWithID(roomid).getName();
				$('#jd_chatuicascadeactivechatroomsholder #joiningroomloadingicon').css('display', 'block')
				.append('<label>joining ' + roomname + ' room please wait.</label>');
				$('body').data('jdChatLibrary').PostMan
				.joinChatroom(roomid, function (theRoom, joinedAlready) {
						//@param theRoom: the Room object returned
						//@param joinedAlready: if the user is already in this room before the request to joinChatroom
						//show the room list in chat ui cascade panel
						$('#jd_chatuicascadeactivechatroomsholder #joiningroomloadingicon').css('display', 'none');
						//$('#toolpanelchatroomslauchicon').click();
						var chatui = $('#jd_chatui_interface_holder');
						if (chatui.css('display') == "none")
							$('#toolpanelchatlauchicon').click();
						
						//delay to make animation complete
						window.setTimeout( function () {
							$('#jdcuisidepanelitem').click();
							$('#jd_chatui_interface_cascadepaneleftside .icondialogpanel').css('display', 'none');
							
							$('#jd_chatui_interface_cascadepaneleftside #jd_chatuicascadeactivechatroomsholder')
							.css('display', 'block');
							
							//add room entry to list.
							if (!joinedAlready)
							{
								//clear contents
								$('#jd_chatui_interface_conversationwindow .content').empty();
								
								var contentholder = $('#jd_chatui_interface_cascadepaneleftside #jd_chatuicascadeactivechatroomsholder .content');
								contentholder.append('<div class="roomobjinstanceui">\
													 	<input type="hidden" id="theroomid" value="' + theRoom.getID() + '" />\
														<div id="roomlogoholder" class="clickable"></div>\
														<div id="roomdetailsholder">' + theRoom.getName() + '</div>\
														<div id="roomshowuserlistlink"><a href="#">User List</a></div>\
														<div id="roomleaveroomlink"><a href="#">Leave Room</a></div>\
														<div style="clear:both;"></div>\
													 </div>');
								
								$('#jd_chatui_interface_cascadepaneleftside #jd_chatuicascadeactivechatroomsholder .content .roomobjinstanceui:last')
								.children('#roomleaveroomlink').children('a')
								.click( function (event) {
									event.preventDefault();
									var roomid = $(this).parent().siblings('input#theroomid').val();
									
									$('body').data('jdChatLibrary').PostMan.leaveChatRoom(roomid, function (chatroomid) {
										$('#jd_chatui_interface_cascadepaneleftside #jd_chatuicascadeactivechatroomsholder .content .roomobjinstanceui').each( function () {
											if ($(this).children('input').val() == chatroomid)
											{
												$(this).remove();
												//set first as active since this is removed
												var firstRoom = $('#jd_chatui_interface_cascadepaneleftside #jd_chatuicascadeactivechatroomsholder .content .roomobjinstanceui:first');
												
												if (firstRoom.length)
													firstRoom.children('#roomlogoholder').click();
												else //show room list
													$('#jd_chatui_interface_cascadepaneleftside #jd_chatuiactivechattertabsholder #chatroomtab').click();
													
												return false;
											}
										});
									});
								});
								
								setChatroomAsActive(theRoom);
								//bind click event
								$('#jd_chatui_interface_cascadepaneleftside #jd_chatuicascadeactivechatroomsholder .content .roomobjinstanceui #roomlogoholder').click( function () {
											var theRoom = $('body').data('jdChatLibrary').UIManager.getChatroomWithID($(this).siblings('#theroomid').val());
										    setChatroomAsActive(theRoom);																										 								});
							}
							
						}, 200);
						
				  },
				  function () {
					  $('#jd_chatuicascadeactivechatroomsholder #joiningroomloadingicon').css('display', 'none');
				  });
			}
			catch (ex)
			{
				alert(ex.message);
			}
		});
	}
}

//function to set panel to display active chat room conversations
//@param theChatroom: the Room object representing the chatroom we want to setup
//for processing
function setChatroomAsActive(theChatroom)
{
	if (theChatroom)
	{
		//disable private chat active conversation to enable chat room
		$('body').data('jdChatLibrary').UIManager.setActiveConversation('');
		oldChatroom = $('body').data('jdChatLibrary').UIManager.getActiveChatroom();
		if (oldChatroom == null || (oldChatroom.getID != theChatroom.getID()))
			if ($('body').data('jdChatLibrary').UIManager.setActiveChatroom(theChatroom.getID()))
			{
				$('#jd_chatui_interface_activeconversationpanel #jd_chatui_interface_activeinfopanel #activeusername')
				.html(theChatroom.getName());
				$('#jd_chatui_interface_activeconversationpanel #jd_chatui_interface_activeinfopanel #activeextradetails')
				.html('');
				$('#jd_chatui_interface_conversationwindow .content').empty();
				$('#jd_chatui_interface_activeconversationpanel #jd_chatui_interface_activeimageholder #activechatpatnerthumbnail').empty();
			}
	}
}

function mCustomScrollbars(){
	/* 
	malihu custom scrollbar function parameters: 
	1) scroll type (values: "vertical" or "horizontal")
	2) scroll easing amount (0 for no easing) 
	3) scroll easing type 
	4) extra bottom scrolling space for vertical scroll type only (minimum value: 1)
	5) scrollbar height/width adjustment (values: "auto" or "fixed")
	6) mouse-wheel support (values: "yes" or "no")
	7) scrolling via buttons support (values: "yes" or "no")
	8) buttons scrolling speed (values: 1-20, 1 being the slowest)
	*/
	//alert("Calling scrollbar");
	$(".mcs_container").mCustomScrollbar("vertical",300,"easeOutCirc",1.05,"auto","yes","yes",15); 
	//$(".mcs_container2").mCustomScrollbar("vertical",300,"easeOutCirc",1.05,"auto","yes","yes",15, function (obj) {});
}

/* function to fix the -10000 pixel limit of jquery.animate */
$.fx.prototype.cur = function(){
    if ( this.elem[this.prop] != null && (!this.elem.style || this.elem.style[this.prop] == null) ) {
      return this.elem[ this.prop ];
    }
    var r = parseFloat( jQuery.css( this.elem, this.prop ) );
    return typeof r == 'undefined' ? 0 : r;
}

$().ready( function () {
					createChatPanel(); //show panels managed by JavaScript.
					docScrollTop = $(document).scrollTop();
					
					var chatScreen = $('div.chatScreenContainer');
					chatScreenMaxHeight = chatScreen.height();
					chatScreenMaxWidth = chatScreen.width();
					
					chatScreen.each( function (index) {
											   $(this).css({
								   'top': (screenHeight - $(this).height()) + "px",
								   'left': $('#mainRightCol').offset().left + 30 + "px"
								   });
											   });
					
					adjustPanelsLeft();
					
					$(window).resize(refreshchatScreens).resize(refreshchatRoomsPanel);
					
					mCustomScrollbars();
});

/**************End of interface fixed postion management*********************************/


/*************BASE TOOLS PANEL OPRATIONS*******************************/
$().ready( function () {
					 //set items in their postion
					 $('#jd_chatui_interface_holder').css({
														  	'display': 'none',
															'left': $('#toolpanelchatlauchicon').offset().left + 'px',
															'top': $(window).height() - ($('#jd_chatui_interface_holder').height() + $('#sitetoolspanelholder').height() - 1) + 'px'
														 }).css('position', 'fixed');
					 
					 
					 $(window).resize( function () {
												 $('#jd_chatui_interface_holder').css({
																				'left': $('#toolpanelchatlauchicon').offset().left + 'px',
																				'top': $(window).height() - ($('#jd_chatui_interface_holder').height() + $('#sitetoolspanelholder').height() - 1) + 'px'
																				}).css('position', 'fixed');
												 }).scroll( function () {
													if ($('#sitetoolspanelholder').offset().top >= $('#pageContainer').height() - $('#footer').height())
													{
														$('#sitetoolspanelholder').css({'opacity': 0.2}).mouseover( function () {
																		$(this).css({'opacity': 1})	;									   
																	}).mouseleave( function () {
																		$(this).css({'opacity': 0.2});
																	});
													}
													else
													{
														$('#sitetoolspanelholder').css({'opacity': 1}).unbind('mouseover').unbind('mouseleave');
													}
												 });
					 
					 $('#toolpanelchatlauchicon').click( function () {
						   var chatui = $('#jd_chatui_interface_holder');
						   $('#jdchatRoomsContainer').css('display', 'none');
						   if (chatui.css('display') == 'none')
						   {
								$('#jd_chatui_interface_holder').css({'display': 'block'});
								$(this).addClass('basetoolsitemActive');
								$('#jd_chatui_interface_conversationtextbox').focus();
						   }
						   else
						   {
								$('#jd_chatui_interface_holder').css({'display': 'none'});
								$(this).removeClass('basetoolsitemActive');
						   }
					 });
					 
					 $('#jd_chatuicascadeactivechatroomsholder a#showchatroomslistlink').click( function (event) {
						   event.preventDefault();
						   /*var chatui = $('#jdchatRoomsContainer');
						   $('#jd_chatui_interface_holder').css('display', 'none');
						   
						   if (chatui.css('display') == 'none')
						   {
								$(this).addClass('basetoolsitemActive');
								refreshchatRoomsPanel();
						   }
						   else
						   {
								chatui.css({'display': 'none'});
								$(this).removeClass('basetoolsitemActive');
						   }*/
						   $('#jd_chatui_interface_cascadepaneleftside .icondialogpanel').css('display', 'none');
						   $('#jd_chatui_interface_cascadepaneleftside #jd_chatuicascadechatroomslistholder').css('display', 'block');
						   refreshchatRoomsPanel();
					 });
					 
					 $('#toolpanelcollapseicon').click( function () {
							  var toolspanel = $('#sitetoolspanelholder');
	
							  if (toolspanel.children('.basetoolsitem:not(#toolpanelcollapseicon)').css('display') != 'none')
							  {
									$('#jd_chatui_interface_closebutton').click(); //close chat interface if open.
									toolspanel.children('.basetoolsitem:not(#toolpanelcollapseicon)').css('display', 'none');
									$('#toolpanelcollapseicon #toolspanelcollapse').css('display', 'none');
									$('#toolpanelcollapseicon #toolspanelexpand').css('display', 'block');
							  }
							  else
							  {
									toolspanel.children('.basetoolsitem:not(#toolpanelcollapseicon)').css('display', 'block');
									$('#toolpanelcollapseicon #toolspanelexpand').css('display', 'none');
									$('#toolpanelcollapseicon #toolspanelcollapse').css('display', 'block');
							  }
						  });
		 });


/********************************OTHER BACKGROUND ACTIONS FOR THE CHAT INTERFACE***********************************************/
// JavaScript Document
var timeToFindFriends = 20 * 1000; //every 30 secs.
var timeToFindFriendHnd = null;

var timeToBroadcast = 8 * 1000; //time to broadcast online status for chat
var timeToBroadcastHnd = null;

var postmanTimeToGetMail = 1800; //every 3 seconds but will use random from 1 to 10 secs to reduce server requests.
var postmantTimeToGetMailHnd = null;

//used to send notification that user is typing
function onConversationOngoing()
{
	try
	{
		if (sendto = $('body').data('jdChatLibrary').UIManager.getActiveConversation())
		{
			//alert("Sending to = " + sendto.getChatID());
			msgfrom = $('body').data('jdChatLibrary').UIManager.getUserID();
			
			//escape special characters
			//cant depend on client system for date
			json = {
					'toid': sendto.getChatID(),
					'fromid':msgfrom,
					'datetime': '',
					'message': '!)))@@',
					'color': '#000'
				   };
							
			$('body').data('jdChatLibrary').PostMan.sendMessage(json, false); //test cross domain send
		}
	}
	catch (e)
	{
		//alert("Sending error = " + e.message);
	}
}

//called to send message using postman
function sendMessageEventHandler() {
	messageRaw = $.trim($('#jd_chatui_interface_conversationtextbox').val());
	//send message.
	try
	{
		//alert("Try sending message");
		if ((sendto = $('body').data('jdChatLibrary').UIManager.getActiveConversation()) || (sendto = $('body').data('jdChatLibrary').UIManager.getActiveChatroom()))
		{
			//alert("Sending to = " + sendto.getChatID());
			msgfrom = $('body').data('jdChatLibrary').UIManager.getUserID();
			
			var privatechatActive = $('body').data('jdChatLibrary').UIManager.getActiveConversation();
			var roomchatActive = $('body').data('jdChatLibrary').UIManager.getActiveChatroom();
			//escape special characters
			//cant depend on client system for date
			if (privatechatActive)
			{
				json = {
						'toid': sendto.getChatID(),
						'fromid':msgfrom,
						'datetime': '',
						'message': messageRaw,
						'color': '#000'
					   };
			}
			else if (roomchatActive)
			{
				json = {
						'toid': sendto.getID(),
						'fromid':msgfrom,
						'datetime': '',
						'message': messageRaw,
						'color': '#000'
					   };
			}
							
			messageObj = new Message(messageRaw, '', '#000', 'Me', $('body').data('jdChatLibrary').UIManager.generateNextMessageID());
			logMessageToChatPanel(messageObj);
			
			if (privatechatActive)
			{
				$('body').data('jdChatLibrary').PostMan.sendMessage(json, false); //test cross domain send
			}
			else if (roomchatActive)
			{
				$('body').data('jdChatLibrary').PostMan.sendMessage(json, false, true, sendto.getID());
			}
		}
	}
	catch (e)
	{
		alert("Sending error = " + e.message);
	}
}


//callback function sent to UIManager to respond to message fetchs
//@param senderObj: the sender of the message (this could be a room or and individual).
//@param isChatroom: true if message is from chat room and false if private
function onMessageFetchComplete(senderObj, messageObj, isChatroom)
{
	activeConversation = null;
	activeChatroomConversation = null;
	try
	{
		if (!isChatroom)
			activeConversation = $('body').data('jdChatLibrary').UIManager.getActiveConversation();
		else
			activeChatroomConversation = $('body').data('jdChatLibrary').UIManager.getActiveChatroom();
		
		if (activeConversation || activeChatroomConversation)
		{
			username = null;
			lastMessage = null;
			
			if (!isChatroom)
			{
				username = activeConversation.getUserID();
				lastMessage = $('body').data('jdChatLibrary').UIManager.getLastMessageFrom(activeConversation.getChatID());
			}
			else
			{
				username = messageObj.getWhoSend();
				lastMessage = messageObj;
			}
		
			if (isChatroom || (lastMessage && lastMessage.getMessageID() != activeConversation.getLastMessageFetched()))
			{
				//called to reflect message fetch on interface.
				if (!isChatroom)
					activeConversation.setLastMessageFetched(lastMessage.getMessageID());
				
				try
				{
					//check if the message is a notification
					var regx = /!\)\)\)@@/;
					if (lastMessage.message.search(regx) != -1)
					{
						$('#jd_chatui_interface_activeconversationpanel #chatongoingtyping').html(username + ' is typing');
					}
					else
					{
						$('#jd_chatui_interface_activeconversationpanel #chatongoingtyping').html('');
						logMessageToChatPanel(lastMessage);
					}
				}
				catch (e)
				{
					alert("Callback error = " + e.message);
				}
			}
		}
		
		//handle none active conversations. on left panel to show updates
		if (senderObj && messageObj)
		{
			friendui = $('#' + senderObj.getUserID());
			if (friendui)
			{
				//if message not typing notification
				var regx = /!\)\)\)@@/;
				if (messageObj.message.search(regx) == -1)
				{
					friendui.find('div.memberlastmsg').html(messageObj.message.substr(0, 10) + '..');
					friendui.find('div.messageposttime').html('@' + messageObj.getDateTime());
					//animate to top.
					if ($('#onlineuserslist .content .memberonlinecurrentmsgholder:first').attr('id') != friendui.attr('id'))
						friendui.fadeOut('slow', function () {
												friendui.prependTo($('#onlineuserslist .content')).fadeIn('slow');
									});
				}
			}
		}
	}
	catch (ex)
	{
	}
}

//logs the message to the chat screen
function logMessageToChatPanel(messageObj)
{
	//alert("Log called");
	if (messageObj)
	{
		try
		{
			message = messageObj.message;
			whois = messageObj.getWhoSend()
			time = messageObj.getDateTime();
			
			//boxheight = $('#chatconversationbox').height();
			//panelheight = $('#chatconversationpanel').height();
				
			messageDecode = message;
				
			regx = /:%@/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/1.png" />');
																
			regx = /\(_:/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/2.png" />');
																
			regx = /%:U/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/3.png" />');

			regx = /::\)/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/4.png" />');
			
			regx = /::!/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/5.png" />');
			
			regx = /\$:\*/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/6.png" />');
			
			regx = /%U\(/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/7.png" />');
			
			regx = /%\)\)/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/8.png" />');
			
			regx = /0:0/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/9.png" />');
			
			regx = /%U%/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/10.png" />');
			
			regx = /%%_/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/11.png" />');
			
			regx = /\^_0/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/12.png" />');
			
			regx = /\^0\^/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/13.png" />');
			
			regx = /0_\)/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/14.png" />');
			
			regx = /\^\^0/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/15.png" />');
			
			regx = /0_0/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/16.png" />');
			
			regx = /><%/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/17.png" />');
			
			regx = /o\)0/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/18.png" />');
			
			regx = /o\)\)/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/19.png" />');
			
			regx = /o>\)/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/20.png" />');
			
			regx = /o>o/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/21.png" />');
			
			regx = /_%_/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/22.png" />');
			
			regx = /\*_\*/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/23.png" />');
			
			regx = /&@l/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/24.png" />');
			
			regx = /&@\*/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/25.png" />');
			
			regx = /%\$&/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/26.png" />');
			
			regx = /\(\$\$/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/27.png" />');
			
			regx = /\(\*X/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/28.png" />');
			
			regx = /@@\*/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/29.png" />');
			
			regx = /o\}\]/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/30.png" />');
			
			regx = /#%\$/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/31.png" />');
			
			regx = /\)\.\(/g;
			messageDecode = messageDecode.replace(regx, ' <img src="../chatplugin/images/chatsmileys/32.png" />');
			

																
			$('#jd_chatui_interface_conversationwindow .content').append('<div class="conversation_feed"><label class="sendername">' + whois + '</label><label class="senddatetime">[' + time + ']</label> <label class="message">' + messageDecode + '</label></div>');
			$('#jd_chatui_interface_conversationwindow .content img').css({
																		  'width': '24px',
																		  'height': '24px'
																		  });
				
			$("#jd_chatui_interface_activeconversationpanel .mcs_container").mCustomScrollbar("vertical",300,"easeOutCirc",1.05,"auto","yes","yes",15, function (obj) {
					if ($('.mcs_container div.dragger_container .dragger').css('display') == 'block')
					{
						var addheight = $('#jd_chatui_interface_conversationwindow').height();
						var thediff = $('.mcs_container .container').height() - addheight;
						$('.mcs_container .container').css({'top': '-=' + thediff + 'px'});
					}
				}); 
		}
		catch(e)
		{
			//do nothing
		}
	}
}

//gets messages sent to user
function callPostmanGet(crossDomain)
{
	$('body').data('jdChatLibrary').PostMan.getMessage(crossDomain, onMessageFetchComplete);
}

//get online status of friends
function findFriendsOnline()
{
	//updating friends list
	//alert("Updating friends list")
	$.post('../processing/getFriendsList.php', {}, function (data, txtstat, jqXHR) {
		 if (data != '0')
		 {
			 try
			 {
			     var delimiter = '~';
			     var streamlength = data.length;
			     friendlist = [];
				 
			     for (k = 0; k < streamlength;)
			     {
					var delimiterpos = data.indexOf(delimiter, k);
					if (k < delimiterpos) //we have entry between k and delimiterpos
					{
						//get id first
						try
						{
							//alert(data.substr(k, delimiterpos - k));
							var jsonFriend = $.parseJSON(data.substr(k, delimiterpos - k));
							
							friendlist.push({'uname': jsonFriend.username, 'friendid': jsonFriend.memgenid + '#####:' + jsonFriend.onlinetime});
						}
						catch (ex)
						{
							alert(ex.message);
						}
						k = delimiterpos + 1;
					}
			     }
				 
				 friendsAdded = $('body').data('jdChatLibrary').UIManager.loadFriends(friendlist); //returns list of friends added
				 if ($.isArray(friendsAdded))
				 {
					 //alert("UIManager added new friends = " +  friendlist.length);
					 //add friends
					// alert("Making friends accessible " + onlineFriendsList[0].mUsername);
					for (i = 0; i < friendsAdded.length; i++)
					{
						//alert("Making friends accessible " + onlineFriendsList[0].mUsername);
						friendimage = null;

						friendusername = friendsAdded[i].getUsername();

						friendOnlineStatus = friendsAdded[i].getOnlineStatus();
						
						friendMemGenID = $('body').data('jdChatLibrary').UIManager.isFriendRegisteredWithUsername(friendusername).getFriendID();

						if (friendOnlineStatus == true)
							friendOnlineStatus = '<img src="../images/onlinedotsmall.jpg" />';
						else
						{
							//alert(friendusername + ' is offline');
							friendOnlineStatus = '<img src="../images/offlinedotsmall.jpg" />';
						}

						uiDef = uiDef = '<div class="memberonlinecurrentmsgholder clickable" id="' + friendusername + '"><div style="height:0px"></div><div class="onlinethumbnail clickable"><div class="thumbnailimageholder"><img src="../processing/matchfetchpic.php?iwsid=' + friendMemGenID +'&h=42&respect=true" /></div></div><div class="onlinethumbnaildetails"><div style="height:3px"></div><div class="memberusername"><div id="thememberusername" style="float:left; width:auto;"></div><div id="thememberonlinestatus" style="float:right; width:auto;"></div><div style="clear:both"></div></div><div class="memberlastmsg"></div><div class="messageposttime"></div></div><div style="clear:both"></div></div>';
						
						//alert(uiDef);
						$('#onlineuserslist div.content').append(uiDef);

						$('#onlineuserslist div.content .memberonlinecurrentmsgholder:last').find('.memberusername > #thememberusername').html(friendusername)
						.siblings('#thememberonlinestatus').html(friendOnlineStatus);
					}
				 }
				 
				 //set online status for everyone
				 friends = $('body').data('jdChatLibrary').UIManager.getFriendsList();
				 
				 for (i = 0; i< friends.length; i++)
				 {
					 friend = friends[i];
					 friendOnlineStatus = friends[i].getOnlineStatus();

					 if (friendOnlineStatus == true)
						friendOnlineStatus = '<img src="../images/onlinedotsmall.jpg" />';
					 else
					 {
						//alert(friendusername + ' is offline');
						friendOnlineStatus = '<img src="../images/offlinedotsmall.jpg" />';
					 }
						
					 isOnline = friend.getOnlineStatus();
					 if (isOnline == true)
					 {
						 //alert("Updating online status");
						 //change the status icon.
						 friendui = $('#onlineuserslist div.content #' + friend.getUsername());
						 if (friendui)
						 {

							 friendui.find('#thememberonlinestatus').html(friendOnlineStatus);
						 }
						 
						 if ($('body').data('jdChatLibrary').UIManager.getActiveConversation() == null)
						 {
							 friendMemGenID = $('body').data('jdChatLibrary').UIManager.isFriendRegisteredWithUsername(friend.getUsername()).getFriendID();
							 
							 $('#jd_chatui_interface_activeconversationpanel #activechatpatnerthumbnail').empty().append('<img src="../processing/matchfetchpic.php?iwsid=' + friendMemGenID +'&h=55&repect=true" style="width:100%; height:100%;" />');
							 $('#jd_chatui_interface_activeinfopanel #activeusername').html(friend.getUsername());
							 $('body').data('jdChatLibrary').UIManager.setActiveConversation(friend.getFriendID());
							 
							 conversationHistoryForID = $('body').data('jdChatLibrary').UIManager.getConversationHistory(active.getChatID());														
							 //alert("Loggin history message");
							 if (conversationHistoryForID)
							 {
								 //-1 cause when changed the last message will be fetched so just render
								 //does before it.
								 //alert("adding conversation history");
								 for (i = 0; i < conversationHistoryForID.length - 1; i++)
								 {
									 logMessageToChatPanel(conversationHistoryForID[i]);
							 	 }
							 }
						 }
					 }
					 else
					 {
						 friendui = $('#onlineuserslist div.content #' + friend.getUsername());
						 if (friendui)
						 {
							 friendui.find('#thememberonlinestatus').html(friendOnlineStatus);
						 }
					 }
				 }
			 }
			 catch (e)
			 {
				 //alert("Bad JSON format " + e.message);
			 }
		 }
		 });
}

function broadcastOnlineStatus()
{
	//alert("Broadcasting online status");
	$.ajax({
		   'url': 'http://localhost/iwanshokoto/chatplugin/processing/broadcastonlinestatus.php',
		   'dataType': 'script',
		   'success': function (data) {
				//alert("Broadcast done!" + data);
			   },
		   'error': function () {
			   //alert("Connection lost");
		   }
		   });
}


/**********manage clicks on interface items**********************************/
function initializeChatUI() {
	//smiley codes
	//alert("Initializing interface interactions");
	var smileyencode = {
		'blowupvexsmiley': ':%@',
		'biggrinsmiley': '(_:',
		'gumpysmiley': '%:U',
		'hugmesmiley': '::)',
		'shockedsmiley': '::!',
		'smushsmiley': '$:*',
		'secretssmiley': '%U(',
		'yawnsmiley': '%))',
		'blushsmiley': '0:0',
		'charmsmiley': '%U%',
		'praysmiley': '%%_',
		'munchsmiley': '^_0',
		'wailsmiley': '^0^',
		'funnyfacesmiley': '0_)',
		'frightsmiley': '^^0',
		'overjoysmiley': '0_0',
		'mobsmiley': '><%',
		'scopeoutsmiley': 'o)0',
		'goberzerksmiley': 'o))',
		'holdheadsmiley': 'o>)',
		'puffedcheeksmiley': 'o>o',
		'haverosesmiley': '_%_',
		'yaikessmiley': '*_*',
		'coolsmiley': '&@l',
		'wackteethsmiley': '&@*',
		'vampiresmiley': '%$&',
		'drupsmiley': '($$',
		'blackeyesmiley': '(*X',
		'exhaustionsmiley': '@@*',
		'uwwwsmiley': 'o}]',
		'embarasssmiley': '#%$',
		'babylooksmiley': ').('
		};

	//handle smileys
	$('div.chatsmileyicon').click( function () {
						  $('#jd_chatui_interface_conversationtextbox').val($('#jd_chatui_interface_conversationtextbox').val() + ' ' + smileyencode[$(this).attr('id')]).focus();
						  });
	
	 $('#jd_chatui_interface_closebutton').click( function () {
															$('#jd_chatui_interface_holder').css('display', 'none');
															});
	 
	 //toggle side panel on click of panel icon.
	 $('#jdcuisidepanelitem, #jdcuisettingitem, #jdcuismileyitem, #jd_chatui_interface_sidepanelcollapsebutton').click( function () {
						   var leftpanel = $('#jd_chatui_interface_cascadepaneleftside');
						   var mainpanel = $('#jd_chatui_interface_holder');
						   
						   if (leftpanel)
						   {
							   //show the right panel for the icon clicked
								$('#jd_chatui_interface_cascadepaneleftside div.icondialogpanel').css('display', 'none');
								switch ($(this).attr('id'))
								{
									case "jdcuisidepanelitem":
										$('#jd_chatui_interface_cascadepaneleftside #jd_chatuicascadeactivechattersholder').css('display', 'block');
									break;
									case "jdcuismileyitem":
										$('#jd_chatui_interface_cascadepaneleftside #jd_chatuicascadesmileysholder').css('display', 'block');
									break;
									case "jdcuisettingitem":
										$('#jd_chatui_interface_cascadepaneleftside #jd_chatuicascadechatsettingsholder').css('display', 'block');
									break;
								}
								
								if (leftpanel.width() == 0 && leftpanel.attr('id') != "jd_chatui_interface_sidepanelcollapsebutton")
								{
									$(this).addClass('jd_chatui_interface_toolspanel_itemActive').removeClass('clickable');
									leftpanel.animate({'width': '170px'}, 'fast', 'linear',
													  function () {
														 
													  });
									
									mainpanel.animate({'width': '422px', 'left': mainpanel.offset().left - 170 + 'px'}, 'fast', 'linear',
													  function () {
														  
													  });
								}
								else
									if ($(this).attr('id') == "jd_chatui_interface_sidepanelcollapsebutton")
									{
										leftpanel.animate({'width': '0px'}, 'fast', 'linear');
										
										mainpanel.animate({'width': '252px', 'left': mainpanel.offset().left + 170 + 'px'}, 'fast', 'linear',
													  function () {
														  $('#jdcuisidepanelitem, #jdcuisettingitem, #jdcuismileyitem, #jd_chatui_interface_sidepanelcollapsebutton').removeClass('jd_chatui_interface_toolspanel_itemActive').addClass('clickable');
														  
														  //make private tab active
														  $('#jd_chatui_interface_cascadepaneleftside #jd_chatuiactivechattertabsholder #chatprivatetab').click();
													  });
									}
									else
									{
										//load the tool items for settings/smileys
									}
						   }
				});
	 
	 //send chat to server using UIManager by click or pressing the enter key
	 $('#jd_chatui_interface_conversationtextboxsendbutton').click( function () {
			var typebox = $('#jd_chatui_interface_conversationtextbox');
			if (typebox && $.trim(typebox.val()).length)
			{
				$(".mcs_container").mCustomScrollbar("vertical",300,"easeOutCirc",1.05,"auto","yes","yes",15, function (obj) {
				if ($('.mcs_container div.dragger_container .dragger').css('display') == 'block')
				{
					var addheight = $('#jd_chatui_interface_conversationwindow').height();
					var thediff = $('.mcs_container .container').height() - addheight;
					$('.mcs_container .container').css({'top': '-=' + thediff + 'px'});
				}
				}); 
				
				sendMessageEventHandler();
				typebox.val('').focus();
			}
	 });
	 
	 $('#jd_chatui_interface_conversationtextbox').keypress( function (event) {
			var ENTERKEY = 13;
			if (event.keyCode == ENTERKEY && $.trim($(this).val()).length)
			{
				$('#jd_chatui_interface_conversationtextboxsendbutton').click();
			}
			
			if ($(this).val().length == 3)
			{
				//send notification for typing message
				privateActive = $('body').data('jdChatLibrary').UIManager.getActiveConversation();
				if (privateActive)
					onConversationOngoing();
			}
		});
	 
	 //handle chatui cascade panel private and room tabs
	 $('#jd_chatui_interface_cascadepaneleftside #jd_chatuiactivechattertabsholder .tab')
	 .click( function () {
			$('#jd_chatui_interface_cascadepaneleftside .icondialogpanel').css('display', 'none');
			switch ($(this).attr('id'))
			{
				case "chatprivatetab":
					$('#jd_chatui_interface_cascadepaneleftside #jd_chatuicascadeactivechattersholder').css('display', 'block');
					$('#jd_chatui_interface_cascadepaneleftside #jd_chatuiactivechattertabsholder #chatroomtab')
					.css({'z-index': 1, 'margin-top': "-30px"});
					$(this).css({'z-index': 2, 'margin-top': "5px"});
				break;
				case "chatroomtab":
					//$('#jd_chatui_interface_cascadepaneleftside #jd_chatuicascadeactivechatroomsholder').css('display', 'block');
					$('#jd_chatuicascadeactivechatroomsholder a#showchatroomslistlink').click();
					$('#jd_chatui_interface_cascadepaneleftside #jd_chatuiactivechattertabsholder #chatprivatetab')
					.css({'z-index': 1, 'margin-top': "10px"});
					$(this).css({'z-index': 2, 'margin-top': "-40px"});
				break;
			}
	 });
}

//driver function for the chat process.
//called when everything is loaded from loadChatLibrary function.
function beginChatProcessingSequence() {
	//initialize chat library
	initializeChatUI();
	
	findFriendsOnline();
	timeToFindFriendHnd = window.setInterval(findFriendsOnline, timeToFindFriends);
	
	//add the friends.
	onlineFriendsList = $('body').data('jdChatLibrary').UIManager.getFriendsList();
	//alert("Getting friends list = " + onlineFriendsList.length);
	if (onlineFriendsList.length)
	{
		for (i = 0; i < onlineFriendsList.length; i++)
		{	
			friendimage = null;
			
			friendusername = onlineFriendsList[i].getUsername();
			
			friendOnlineStatus = onlineFriendsList[i].getOnlineStatus();
			
			friendMemGenID = $('body').data('jdChatLibrary').UIManager.isFriendRegisteredWithUsername(friendusername).getFriendID();
			
			//alert("Id = " + friendMemGenID);
			if (friendOnlineStatus == true)
			{
				//alert(friendusername + ' is online');
				friendOnlineStatus = '<img src="../images/onlinedotsmall.jpg" />';
			}
			else
			{
				//alert(friendusername + ' is offline');
				friendOnlineStatus = '<img src="../images/offlinedotsmall.jpg" />';
			}
			
			uiDef = '<div class="memberonlinecurrentmsgholder clickable" id="' + friendusername + '"><div style="height:0px"></div><div class="onlinethumbnail clickable"><div class="thumbnailimageholder"><img src="../processing/matchfetchpic.php?iwsid=' + friendMemGenID +'&h=42&respect=true" /></div></div><div class="onlinethumbnaildetails"><div style="height:3px"></div><div class="memberusername"><div id="thememberusername" style="float:left; width:auto;"></div><div id="thememberonlinestatus" style="float:right; width:auto;"></div><div style="clear:both"></div></div><div class="memberlastmsg"></div><div class="messageposttime"></div></div><div style="clear:both"></div></div>';
			
			//alert(uiDef);
			$('#onlineuserslist div.content').append(uiDef);
			//alert("Friend status = " + friendOnlineStatus);
			$('#onlineuserslist div.content .memberonlinecurrentmsgholder:last').find('.memberusername > #thememberusername').html(friendusername)
			.siblings('#thememberonlinestatus').html(friendOnlineStatus);
		}
		
		//make online friends respond to click to make them active conversation
		$('div.memberonlinecurrentmsgholder').click( function () {
														//alert("Setting active conversation by username");
														$('body').data('jdChatLibrary').UIManager.setActiveConversationByUsername($(this).attr('id'));
														//draw all messages from conversation history with this new active chat
														active = $('body').data('jdChatLibrary').UIManager.getActiveConversation();
														if (active)
														{
															//clear old screen.
															$('#jd_chatui_interface_activeinfopanel #activeusername').html($(this).attr('id'));
															 friendMemGenID = $('body').data('jdChatLibrary').UIManager.isFriendRegisteredWithUsername($(this).attr('id')).getFriendID();
											 $('#jd_chatui_interface_activeconversationpanel #activechatpatnerthumbnail').empty().append('<img src="../processing/matchfetchpic.php?&iwsid=' + friendMemGenID +'&h=52&respect=true" />');
															//$('#chatconversationbox').html('');
															conversationHistoryForID = $('body').data('jdChatLibrary').UIManager.getConversationHistory(active.getChatID());										
															
															$('#jd_chatui_interface_conversationwindow .content').empty();
															//alert("Loggin history message");
															if (conversationHistoryForID)
															{
																//-1 cause when changed the last message will be fetched so just render
																//does before it.
																for (i = 0; i < conversationHistoryForID.length - 1; i++)
																{
																	logMessageToChatPanel(conversationHistoryForID[i]);
																}
															}
														}
													});
	}
	else
	{
		//alert("No friends on your list");
	}
	
	//set broadcasting.
	timeToBroadcastHnd = window.setInterval(broadcastOnlineStatus, timeToBroadcast);
	
	//tell postman to do job
	postmantTimeToGetMailHnd = window.setInterval(callPostmanGet, postmanTimeToGetMail, false); //test cross domain get
}