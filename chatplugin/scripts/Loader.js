// JavaScript Document
//import all needed classes.
//load the collection of script file names in scriptload
//@param url: the path to load libraries from
//@param userid: the user of this chat interface
//@param friendlist: the array of friend id
function loadChatLibrary(url, userid, friendlist, callback)
{
	//alert("Loading...");
	//$.ajaxSetup();
	$('body').data('jdChatLibrary', {'UIManager': null, 'PostMan': null, 'jdChatLoader': null, 'PostManData': {'callback': null, 'userdata': null}, 'UIManagerFriendLastMsgDrawn': []});
	$('body').data('jdChatLibrary').jdChatLoader = {'callback': null, 'friendlist': null}; //store callback function);
	
	if ($.type(url) != "string")
		throw false;
	else
	{
		//alert("Loading please wait...");
		$('body').data('jdChatLibrary').jdChatLoader.callback = callback;
		$('body').data('jdChatLibrary').jdChatLoader.friendlist = friendlist;
		//load Friend.js, Message.js, Sender.js, ChatException.js, UIManager.js, Postman.js
		//alert("Loading please wait again...");
		$.ajax({
			   'url': '../processing/LoadLibrary.php',
			   'type': 'POST',
			   'dataType': 'text',
			   'cache': false,
			   'data': {'url': url, 'libs': 'Message.js,Friend.js,Sender.js,ChatException.js,Room.js,UIManager.js,Postman.js'},
			   'success': function (data, txtstatus, jqXHR) {
				   				//data as json string
								try
								{
									jsonObj = $.parseJSON(data);
									
				   					if (!jsonObj.error && jsonObj.defs.length > 10)
									{
										//execute script.
										$.globalEval(jsonObj.defs);
				   						//create main instances.
										chatUIManager = new UIManager($('body').data('jdCurrentChatUserID')); //friends list should have been populated by now
										//alert("Adding frineds to UIManager " + $('body').data('jdChatLibrary').jdChatLoader.friendlist);
										chatUIManager.loadFriends($('body').data('jdChatLibrary').jdChatLoader.friendlist);
										chatPostManager = new Postman($('body').data('jdCurrentChatUserID'), 'http://localhost/iwanshokoto/chatplugin/processing/sendMessage.php', "http://localhost/iwanshokoto/chatplugin/processing/getMessage.php");										
										
										if ($.isFunction($('body').data('jdChatLibrary').jdChatLoader.callback))
										{
											//call callback function. call to beginChatProcess
											$('body').data('jdChatLibrary').jdChatLoader.callback();
											//get chat rooms.
											$.ajax({
												   'url': 'http://localhost/iwanshokoto/chatplugin/processing/getChatRooms.php',
												   'type': 'POST',
												   'dataType': 'json',
												   'success': function (json) {
													   if (json.success)
													   {
														   var rooms = json.rooms;
														   var delimiter = "#";
														   for (i = 0; i < rooms.length; i++)
														   {
															   var delpos = rooms.indexOf(delimiter, i);
															   var roomDef = rooms.substr(i, delpos - i);
															   //add to UIManager
															   try
															   {
																   roomjson = $.parseJSON(roomDef);
															   	   added = $('body').data('jdChatLibrary').UIManager.addRoom(roomjson.roomgenid, roomjson);										   }
															   catch (ex)
															   {
															   }
															   
															   i = delpos;
														   }
													   }
													   else
													   {
														   alert("Back with no rooms");
													   }
												   },
												   'error': function (jqXHR, txtstatus, errormsg) {
													   alert("Error fetching Chat rooms");
												   }
												   });
										}
									}
									else
									{
										alert("Error ");
									}
								}
								catch(e)
								{
									alert("Hey " + e.message);
								}
			   			  },
			   'error': function (jqXHR, txtstatus, errorThrown) { throw {'message': 'unable to load chat librarys', 'error': errorThrown}}
			   });
	}
}