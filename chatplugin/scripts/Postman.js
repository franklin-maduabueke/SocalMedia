// JavaScript Document
/****************************************************************
* Class to send and get message from server for the user.
*****************************************************************/
//Note: Singleton object. One instance of this object in the lib
function Postman(chatid, sendurl, geturl)
{
	this.mSendURL = sendurl; //url to service sends
	this.mGetURL = geturl; //url to service gets
	this.mToID = chatid; //id of the chat user
	this.mUIManager = $('body').data('jdChatLibrary').UIManager;  //the global ui manager
	this.mRoomsJoined = new Array(); //list of the chat rooms joined by user
	
	//used to send message
	//@param jsonMessage JSONObject: represents the message using json format
	//@param crossDomain Boolean: true if request is made to a different domain than main site hosting the chat plugin
	//@param sendToRoom Boolean: true if we are in chat room
	//@param roomid String: id of the chat room
	//@return Boolean: true if message was sent and false if not
	//format: {'toid', 'fromid':xxx, 'datetime': 'yy-mm-dd','message': 'string', 'color':#xxxxxx}
	this.sendMessage = function (jsonMessage, crossDomain, sendToRoom, roomid) {
		//create Message instance form this format
		if ($.type(jsonMessage) != "string" && $.type(this.mSendURL) == "string")
		{
			//see if its json correct
			try
			{
				json = jsonMessage;
				if (json)
				{
					if (sender = $('body').data('jdChatLibrary').UIManager.isSenderRegistered(json.toid))
					{
						msgid = $('body').data('jdChatLibrary').UIManager.generateNextMessageID();
						$('body').data('jdChatLibrary').UIManager.addConversationHistory(sender.getChatID(), new Message(json.message, json.datetime, json.color, 'Me', msgid));
					}

					if (!crossDomain)
					{
						var sendingToRoom = (sendToRoom) ? 1 : 0;
						
						var regx = /'/g;
						json.message = json.message.replace(regx, "&rsquo;");
						
						$.ajax({
						   'url': this.mSendURL,
						   'type': "POST",
						   'data': {"toid": json.toid, "fromid": json.fromid, "datetime": json.datetime, "message": json.message, "color": json.color, "toroom": sendingToRoom, 'roomid': roomid},
						   'dataType': "json",
						   'cache': false,
						   'success': function (data, txtstatus, jqXHR) {
							  		if (data.error == 0)
									{
										//message sent
									}
									else
									{
										//message not sent
									}
						   },
						   error: function (jqXHR, txtstatus, theError) {
						   }
						   });
					}
					else
					{
						var sendingToRoom = (sendToRoom) ? 1 : 0;
						
						var regx = /'/g;
						json.message = json.message.replace(regx, "&rsquo;");
						
						$.ajax({
							   'url':this.mSendURL,
							   'data': {"toid": json.toid, "fromid": json.fromid, "datetime": json.datetime, "message": json.message, "color": json.color, "toroom": sendingToRoom, 'roomid': roomid},
							   'dataType': "script",
							   'cache': false,
							   'success': function (data) {
								   //alert("Sending done ");
							   },
							   'error': function () {
								  //alert("error sending cross domain");
							   }
							   });
					}
				}
			}
			catch (e)
			{
				//exception
				//alert(e.message);
			}
		}
	};
	
	//used to get message
	//@param crossDomain: true if request is made to a different domain than main site hosting the chat plugin
	//callback function when message has been fetched
	//@return: jsonMessage
	//format: {'toid', 'fromid':xxx, 'datetime': 'yy-mm-dd','message': 'string', 'color':#xxxxxx}
	this.getMessage = function (crossDomain, callback, getFromRoom, roomid) {
		//alert("Called to get message server " + this.mGetURL);
		if ($.isFunction(callback) && $('body').data('jdChatLibrary').PostManData.callback !== callback)
		{
			//alert("Assingning callback to call later");
			$('body').data('jdChatLibrary').PostManData.callback = callback;
		}
		
		//get the active chat rooms
		var activeChatrooms = '';
		for (i = 0; i < this.mRoomsJoined.length; i++)
			activeChatrooms += this.mRoomsJoined[i].getID() + ',';
		
		if (!crossDomain)
		{
			//alert("Getting" +  $('body').data('jdChatLibrary').UIManager.getUserID());
			var gettingFromRoom = (getFromRoom) ? 1 : 0;
			$.ajax({
			   'url': this.mGetURL,
			   'type': "POST",
			   'data': {'toid': $('body').data('jdChatLibrary').UIManager.getUserID(), 'roomlist': activeChatrooms},
			   'dataType': "json",
			   'cache': false,
			   'success': function (data) {
				   try
				   {
					    //alert("message found " + data.messages);
				   		json = data;
				   		if (json.error == 0) //json_encode messages delimited by #&!@! fetched
				   		{
					   		delimiter = '~';
					   		stream = json.messages;
					   
					   		for (k = 0; k < stream.length;)
					   		{
						   		//alert("Postman decoding message");
					   			delimiterpos = stream.indexOf(delimiter, k);
								
								if (k < delimiterpos) //we have entry between k and delimiterpos
								{
									jsonMsgString = stream.substr(k, delimiterpos);
									//alert("Postmane Piping message = " + jsonMsgString);
									
									$('body').data('jdChatLibrary').UIManager.pipeMessage(jsonMsgString);
								}
							
								k = delimiterpos + 1;
					   		}
				   		}
						//call callback.
						if ($('body').data('jdChatLibrary').PostManData.callback != null)
						{
							////alert("PostMan callback call");
							$('body').data('jdChatLibrary').PostManData.callback();
						}
				   }
				   catch (e)
				   {
					   //alert("Parsing json error = " + e.message);
				   }
			   }
			   });
		}
		else
		{
			//alert("Getting cross domain " + this.mGetURL + ' ' + this.mToID);
			$.ajax({
			   'url': this.mGetURL,
			   'type': 'POST',
			   'data': {'toid': this.mToID, 'roomlist': activeChatrooms},
			   'dataType': "json",
			   'cache': false,
			   'success': function (data) {
				   try
				   {
					    //alert("Back with " + data);
				   		json = $.parseJSON(data);
				   		if (json.error == 0) //json_encode messages delimited by #&!@! fetched
				   		{
					   		delimiter = '~';
					   		stream = json.messages;
					   		//alert("message = " + stream);
					   		for (k = 0; k < stream.length;)
					   		{
						   		//alert("Postman decoding message");
					   			delimiterpos = stream.indexOf(delimiter, k);
								
								if (k < delimiterpos) //we have entry between k and delimiterpos
								{
									jsonMsgString = stream.substr(k, delimiterpos);
									//alert("Postman Piping message = " + jsonMsgString);
									
									$('body').data('jdChatLibrary').UIManager.pipeMessage(jsonMsgString);
								}
							
								k = delimiterpos + 1;
					   		}
				   		}
						//call callback.
						if ($('body').data('jdChatLibrary').PostManData.callback != null)
						{
							////alert("PostMan callback call");
							$('body').data('jdChatLibrary').PostManData.callback();
						}
				   }
				   catch (e)
				   {
					   alert("Parsing json error = " + e.message);
				   }
			   },
			   'error': function () {
				   alert("Error: Connection lost");
			   }
			   });
		}
	};
	
	//used to get the chat id of the user managed by post
	this.getChatUserID = function () {
		return this.mToID;
	};
	
	//processing after message gotten from crossDomain
	this.processCrossDomainMessageGet = function (data) {
				   if (data != "0") //json_encode messages delimited by #&!@! fetched
				   {
					   var delimiter = '#&!@!';
					   var streamlength = data.length;
					   for (k = 0; k < streamlength; k++)
					   {
					   		var delimiterpos = data.indexOf(delimiter, k);
							if (k < delimiterpos) //we have entry between k and delimiterpos
							{
								$('body').data('jdChatLibrary').PostMan.mUIManager.pipeMessage(data.substr(k, delimiterpos));
								k = delimiterpos + 1;
							}
					   }
				   }
	};
	
	
	//gets the conversation history of a chat user
	//@param senderid: the conversation history for this sender
	//@param callback: a function to call when this process completes
	this.getConversationHistory = function getConversationHistory(senderid, callback) {
			var conversationRequestUrl = this.mGetURL;
			var lastpos = conversationRequestUrl.lastIndexOf("/");
			conversationRequestUrl = conversationRequestUrl.substr(lastpos);
			//alert(conversationRequestUrl);
			$.ajax({
				   'url': "http://localhost",
				   'type': "POST",
				   'data': {'toid': $('body').data('jdChatLibrary').UIManager.getUserID(), 'fromroom': gettingFromRoom, 'roomid': roomid},
				   'dataType': "json",
				   'cache': false,
				   'success': function (data) {
					   try
					   {
							//alert("message found " + data.messages);
							
							json = data;
							if (json.error == 0) //json_encode messages delimited by #&!@! fetched
							{
								delimiter = '~';
								stream = json.messages;
						   
								for (k = 0; k < stream.length;)
								{
									//alert("Postman decoding message");
									delimiterpos = stream.indexOf(delimiter, k);
									
									if (k < delimiterpos) //we have entry between k and delimiterpos
									{
										jsonMsgString = stream.substr(k, delimiterpos);
										//alert("Postmane Piping message = " + jsonMsgString);
										
										$('body').data('jdChatLibrary').UIManager.pipeMessage(jsonMsgString);
									}
								
									k = delimiterpos + 1;
								}
							}
							//call callback.
							if ($('body').data('jdChatLibrary').PostManData.callback != null)
							{
								////alert("PostMan callback call");
								$('body').data('jdChatLibrary').PostManData.callback();
							}
					   }
					   catch (e)
					   {
						   //alert("Parsing json error = " + e.message);
					   }
				   }
				   });
				};
	
	//used to join a chat room.
	//@param roomid: the room id
	//@param allowcallback. a callback function that takes one argument to be called on success.
	//this argument is a Room object instance
	//@param rejectcallback. a callback function that takes no argument to be called on faliure
	this.joinChatroom = function (roomid, allowcallback, rejectcallback) {
		//see if the UIManager has this chat room listed.
		//request join from server.
		//get room messages
		//alert("Requesting join");
		if (this.mUIManager.getChatroomWithID(roomid))
		{
			$('body').data('jdChatLibrary').PostManData.callback = null
			$('body').data('jdChatLibrary').PostManData.callback = new Array();
			//alert("Room exist requesting join please wait....");
			if (allowcallback && $.isFunction(allowcallback))
				$('body').data('jdChatLibrary').PostManData.callback.push(allowcallback);
			
			if (rejectcallback && $.isFunction(rejectcallback))
				$('body').data('jdChatLibrary').PostManData.callback.push(rejectcallback);
				
			$.ajax({
				   'url': 'http://localhost/iwanshokoto/chatplugin/processing/requestJoinChatroom.php',
				   'data': {'chatroomid': roomid},
				   'dataType': 'json',
				   'success': function (json) {
					   if (json.success && json.allow)
					   {
						   var joinedAlready = false;
						   var postman = $('body').data('jdChatLibrary').PostMan;
						   
						   for (i = 0; i < postman.mRoomsJoined.length; i++)
						   		if (postman.mRoomsJoined[i].getID() == json.chatroomid)
								{
									joinedAlready = true;
									break;
								}
								
						   if (!joinedAlready)
						   		postman.mRoomsJoined.push(postman.mUIManager.getChatroomWithID(json.chatroomid));
								
						   if ($('body').data('jdChatLibrary').PostManData.callback[0])
							   $('body').data('jdChatLibrary').PostManData.callback[0](postman.mUIManager.getChatroomWithID(json.chatroomid), joinedAlready);
					   }
					   
					   $('body').data('jdChatLibrary').PostManData.callback = null;
				   },
				   'error':  function (jqXHR, txtstatus, errormsg) {
					   if ($('body').data('jdChatLibrary').PostManData.callback[1])
					   {
						   $('body').data('jdChatLibrary').PostManData.callback[1]();
					   }
					   $('body').data('jdChatLibrary').PostManData.callback = null
				   }
				});
		}
	};
	
	
	//used to leave a chat room
	this.leaveChatRoom = function (roomid, callback) {
		if (this.mUIManager.getChatroomWithID(roomid))
		{
			$('body').data('jdChatLibrary').PostManData.userdata = null;
			if (callback && $.isFunction(callback))
				$('body').data('jdChatLibrary').PostManData.userdata = callback;
			
			$.ajax({
				   'url': 'http://localhost/iwanshokoto/chatplugin/processing/requestLeaveRoom.php',
				   'data': {'chatroomid': roomid},
				   'dataType': 'json',
				   'success': function (json) {
					   var postman = $('body').data('jdChatLibrary').PostMan;
					   for (i = 0; i < postman.mRoomsJoined.length; i++)
					   {
						   var validRooms = new Array();
						   if (postman.mRoomsJoined[i].getID() != json.roomid)
							   validRooms.push(postman.mRoomsJoined[i]);
						   
						   postman.mRoomsJoined = validRooms;
					   }
					   
					   postman.mUIManager.clearActiveChatroom();
					   
					   if (json.success && $('body').data('jdChatLibrary').PostManData.userdata)
					   		$('body').data('jdChatLibrary').PostManData.userdata(json.roomid);
				   },
				   'error': function (jqXHR, txtstatus, errormsg) {
					   alert("Error occured while trying to leave room");
				   }
				   });
		}
	};
	
	$('body').data('jdChatLibrary').PostMan = this; 
}