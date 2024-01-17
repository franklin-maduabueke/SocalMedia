// JavaScript Document
/*******************************************************
* Script sets up the chat library
* Author: Franklin N. Maduabueke, dueal21@yahoo.co.uk
* No part of this work may be used in part or whole
* without the prior written consent of the author.
********************************************************/
$().ready( function () {
					 //load friends list loader.
					 $.getScript('../scripts/getFriendsList.js').done( function () {
											//load chat library loader.
					 						$.getScript('../chatplugin/scripts/Loader.js').done( function () {
													//get userid
													$.post('../processing/getMemberID.php',
														   {},
														   function (data) {
															   if (data != "0")
															   {
																   	var url = "";
																	var userid = data;
																	$('body').data('jdCurrentChatUserID', userid);
																	
																	//process friends list
																	loadFriendsListFromServer(userid, 
																	    function ()
																		{
																			//alert("Calling loadChatLibrary " + $('body').data('jdCurrentChatUserID'));
																			loadChatLibrary('../chatplugin/scripts/', $('body').data('jdCurrentChatUserID'), globalUserFriendsListAsJSONFromServer, beginChatProcessingSequence);
																			
																			window.setInterval(broadcastOnlineStatusMainSite, 8000);
																		});
															   }
														   });
														}).fail( function () {
																				//error
																				alert("failed to get member id");
												   				});
												   }).fail( function () {
												   });
					 });

function broadcastOnlineStatusMainSite()
{
	//alert("Broadcasting online status");
	$.ajax({
		   'url': 'http://localhost/iwanshokoto/processing/broadcastonlinestatus.php',
		   'dataType': 'script',
		   'success': function (data) {
				//alert("Broadcast done!" + data);
			   },
		   'error': function () {
			   //alert("Connection lost");
		   }
		   });
}