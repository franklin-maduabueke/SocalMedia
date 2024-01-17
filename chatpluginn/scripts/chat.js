// JavaScript Document
var docScrollTop = null;
var chatScreenMaxHeight = null;
var chatScreenMaxWidth = null;
var chatScreenMinHeight = null;
var chatScreenMinWidth = 150;
var marginRight = 7;
var refreshRate = 0.2 * 1000;

function createChatPanel()
{
	
}

function adjustPanelsLeft() {
	//adjust left
	$('div.chatScreenContainer').each( function (index) {
														if (index > 0)
														{
															var pleft = $('div.chatScreenContainer').eq(index - 1).css("left");
															pleft = pleft.substr(0, pleft.indexOf("px"));
															
															$(this).css({"left": pleft - $(this).width() - marginRight});
														}
												});
}

var windowHeight = window.innerHeight
var screenWidth = window.innerWidth;
var screenHeight = window.innerHeight;

function refreshchatScreens()
{
	if (windowHeight != window.innerHeight)
	{
		screenHeight = windowHeight = window.innerHeight
		$('div.chatScreenContainer').each ( function () { 
													  $(this).css({
														"position": "fixed",
														"top": (screenHeight - $(this).height()) + "px"
																  });
										 });
											
	}
}

$().ready( function () {
					//createChatPanel();
					docScrollTop = $(document).scrollTop();
					
					var chatScreen = $('div.chatScreenContainer');
					chatScreenMaxHeight = chatScreen.height();
					chatScreenMaxWidth = chatScreen.width();
					//alert("Scroll Top = " + scrollPosition);
					//alert("Screen height = " + screenHeight); alert("Screen width = " + screenWidth);
					//alert("Chat width = " + chatScreen.width()); alert("Chat height = " + chatScreen.height());
					
					chatScreen.css({
								   'top': (screenHeight - chatScreen.height()) + "px",
								   'left': (screenWidth - chatScreen.width() - marginRight) + "px"
								   }).click( function () {
									   if ($(this).height() == chatScreenMaxHeight)
									   {
										    //minimize
										    var minHeight = Math.ceil((15 / 100) * chatScreenMaxHeight);
										    var minWidth = Math.ceil((85 / 100) * chatScreenMaxWidth);
										    //alert(currentTop);
											$(this).children("div.chatMessageScreen").hide();
									   		$(this).css({"height": minHeight + "px", "width": chatScreenMinWidth + "px"}).css({
														"position": "fixed",
														"top": (screenHeight - $(this).height()) + "px",
														"left": (screenWidth - $(this).width() - marginRight) + "px"
														}).addClass("chatScreenContainerHover");
											
											adjustPanelsLeft();
									   }
									   else
									   {
										   	//maximize
											
									   		$(this).css({"height": chatScreenMaxHeight + "px", "width": chatScreenMaxWidth + "px"}).css({
														"position": "fixed",
														"top": (screenHeight - $(this).height()) + "px",
														"left": (screenWidth - $(this).width() - marginRight) + "px"
														}).removeClass("chatScreenContainerHover").children("div.chatMessageScreen").show();
											
											adjustPanelsLeft();
									   }
								   });
					
					adjustPanelsLeft();
					
					setInterval(refreshchatScreens, refreshRate);
});