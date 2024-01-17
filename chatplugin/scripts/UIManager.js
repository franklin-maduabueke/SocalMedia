// JavaScript Document
//Class that manages the ui of the chat.
//@param userid: the id of the chat user
//@param friends: the json object containing all friends of this user as provided.
//Note: Singleton object. One instance of this object in the lib
function UIManager(userid)
{
	this.mUserID = userid;
	this.mActiveConversation = null; //who the user is currently chating with.
	this.mConversationRegister = new Array(); //everyone that's chating with user. Collection of accepted senders which include your friends and non-friends you accept.
	this.mUserFriendList = new Array(); //array of friends. Use to know when you need to be notified that a conversation from a non-friend has been requested.
	this.mMessageCounter = 1; //message counter.
	this.mConversationHistory = new Array(); //conversation btw user and others. mConversationHistory[i] = {"senderid": xxx, 'conversation': []}
	
	this.mChatRooms = new Array(); //poll of chat rooms available to the user on the site.
	this.mActiveChatroomConversation = null; //the chat room the user has activated to converse in
	
	
	//used to set a room for active conversation
	this.setActiveChatroom = function (roomid) {
		if (theRoom = this.getChatroomWithID(roomid))
		{
			this.mActiveChatroomConversation = theRoom;
			return this.mActiveChatroomConversation;
		}
		
		return false;
	};
	
	//used to clear the active chatroom state
	this.clearActiveChatroom = function () {
		this.mActiveChatroomConversation = null;
	};
	
	//used to get the active chatroom set for conversation
	this.getActiveChatroom = function () {
		return this.mActiveChatroomConversation;
	};
	
	//gets the user id
	this.getUserID = function () {
		return this.mUserID;
	};
	
	//used to generate a message id.
	this.generateNextMessageID = function () {
		return this.mMessageCounter++;
	};
	
	//adds to the conversation history
	//@param sender: the sender to add conversation history to
	//@param entry: the entry of conversation.
	this.addConversationHistory = function (senderid, messageObj) {
		//find the array
		//alert("UIManager adding conversation history " + senderid + ' ' + messageObj.message);
		for (i = 0; i < this.mConversationHistory.length; i++)
		{
			if ((this.mConversationHistory[i]).senderid == senderid)
			{
				(this.mConversationHistory[i]).conversation.push(messageObj);
				//alert("Conversation history added");
				return;
			}
		}
		
		this.mConversationHistory.push({'senderid': senderid, 'conversation': [messageObj]});
		//alert("Conversation history size = " + this.mConversationHistory.length);
	};
	
	//used to get conversation history
	this.getConversationHistory = function (senderid) {
		for (i = 0; i < this.mConversationHistory.length; i++)
		{
			if ((this.mConversationHistory[i]).senderid == senderid)
			{
				return (this.mConversationHistory[i]).conversation;
			}
		}
		
		return false;
	};
	
	//used to load friend list as Friend class instance in mUserFriendList
	//What we can do for now is to assume that the user of this chat interface provides the
	//list of friends to ui manager as a json object having uname and firendid property
	//the friendid property is a combination of usergenid and onlinetime delimited by 5 '#' characters
	//return the list of friends objects added.
	this.loadFriends = function (friends) {
		if ($.isArray(friends) && friends.length)
		{
			friendsAdded = [];
			for (i = 0; i < friends.length; i++)
			{
				stream = friends[i].friendid;
				//alert("UIManager see = " + stream);
				friends[i].friendid = this.getFriendIDFromStream(friends[i].friendid);
				//alert("Adding friend new = " + friends[i].friendid.toString());
				//add friend if not listed
				//get online time
				delimiter = '#####:'
				onlineTime = stream.substr(friends[i].friendid.length + delimiter.length);
				if (!(friend = this.isFriendRegistered(friends[i].friendid)))
				{
					//alert("UI manager = " + friends[i].friendid + ' ' + friends[i].uname + ' ' + onlineTime);
					this.mUserFriendList.push(new Friend(friends[i].friendid, friends[i].uname, onlineTime));
					friendsAdded.push(this.mUserFriendList[this.mUserFriendList - 1]);
				}
				else
				{
					//update online status of friend.
					friend.setOnlineStatus(onlineTime);
				}
					
				//add friends to conversation if not listed.
				if (!this.isSenderRegistered(friends[i].friendid))
				{
					var sender = new Sender(friends[i].friendid, friends[i].uname);
					sender.setIsFriend(true);
					this.mConversationRegister.push(sender);
				}
			}
			
			return friendsAdded;
		}
	};
	
	//create a sender and adds to the conversation registry
	//called by ChatRequestNotifier if chater isnt a friend.
	this.addSender = function (jsonObj) {
		var sender = new Sender(jsonObj.fromid);
		var message = new Message(jsonObj.message, jsonObj.datetime, jsonObj.color);
		sender.addMessage(message);
		
		mConversationRegister.push(sender);
	};
	
	//used to filter the message to respective Senders object
	//@param jsonMessage: the jsonMessage from Postman
	//format: {'fromid':xxx, 'datetime': 'yy-mm-dd','message': 'string', 'color':#xxxxxx}
	this.pipeMessage = function (jsonMessage) {
		//alert("UIManager piping messages " + jsonMessage);
		try
		{
			json = null;
			if (json = $.parseJSON(jsonMessage))
			{
				var sender = this.isSenderRegistered(json.fromid);
				if (sender)
				{
					//add message to friend.
					messageObj = new Message(json.message, json.datetime, json.color, sender.getUserID(), this.mMessageCounter++);
					sender.addMessage(messageObj);
					//callback onMessageFetchComplete
					this.addConversationHistory(sender.getChatID(), messageObj);
					
					$('body').data('jdChatLibrary').PostManData.callback(sender, messageObj, false); //the sender and message
				}
				else
				{
					//check if its a room id and pipe
					var regx = /^r@/;
					if (json.toid.search(regx) != -1)
					{
						if (theChatroom = this.getChatroomWithID(json.toid))
						{
							messageObj = new Message(json.message, json.datetime, json.color, json.username, json.messageid);
							added = theChatroom.addMessage(messageObj);
							if ($.isFunction($('body').data('jdChatLibrary').PostManData.callback) && added)
								$('body').data('jdChatLibrary').PostManData.callback(theChatroom, messageObj, true); //the sender and message
						}
					}
					else
					{
						//call ChatRequestNotifier
						alert(json.toid + "sender not friend");
					}
				}
			}
		}
		catch (e)
		{
		}
	};
	
	//used to ignore a chat. This stops fetching of conversation from server for
	//this chat.
	this.ignoreChat = function (chatid) {
		var sender = this.isSenderRegistered(chatid);
		
		if (sender)
			sender.setIgnored(true);
	}
	
	//finds if a chatid exists in the register.
	//@return: returns the sender object if its registered else returns false.
	this.isSenderRegistered = function (senderid) {
		for (i = 0; i < this.mConversationRegister.length; i++)
			if ((this.mConversationRegister[i]).getChatID() == senderid)
				return this.mConversationRegister[i];
		
		return false;
	};
	
	//checks for friend registered by username
	this.isFriendRegisteredWithUsername = function (fusername) {
		for (i = 0; i < this.mUserFriendList.length; i++)
			if (this.mUserFriendList[i].getUsername() == fusername)
				return this.mUserFriendList[i];
	};
	
	//checks to see if friend is registerd in friends list
	//@return: If the friend is registered the instance is returned else false is returned
	this.isFriendRegistered = function (friendid) {
		for (i = 0; i < this.mUserFriendList.length; i++)
			if (this.mUserFriendList[i].getFriendID() == friendid)
				return this.mUserFriendList[i];
		
		return false;
	};
	
	//set the active conversation
	//@param activeId: b64code of active conversation
	this.setActiveConversation =  function (activeId) {
		if ($.type(activeId) == 'string')
		{
			this.mActiveConversation = this.isSenderRegistered(activeId);
			////alert("UImanager active conversation set to = " + this.mActiveConversation);
		}
	};
	
	//sets the active conversation by username
	this.setActiveConversationByUsername = function (username) {
		if ($.type(username) == 'string')
		{
			////alert("UIManager setting active conversation to " + username);
			friend = this.isFriendRegisteredWithUsername(username);
			
			if (friend)
			{
				////alert("Friend ok setting id to = " + friend.getUsername() + ' ' + friend.getFriendID());
				this.mActiveConversation = this.isSenderRegistered(friend.getFriendID());
			}
			
			//alert("UIManager active conversation set to " + this.mActiveConversation.getChatID());
		}
	};
	
	//get active conversation.
	this.getActiveConversation = function () {
		return this.mActiveConversation;
	};
	
	
	//helper function to decode friend id from the mix of b64id and online time stat
	this.getFriendIDFromStream = function (stream) {
		if ($.type(stream) == "string")
		{
			//find #####: delimiter
			pos = stream.indexOf('#####:');
			if (pos != -1)
				stream = stream.substr(0, pos);
			
			return stream;
		}
		
		return false;
	};
	
	//gets the friends list
	this.getFriendsList = function () {
		return this.mUserFriendList;
	};

	//used to get conversations list
	this.getConversationList = function () {
		return this.mConversationRegister;
	};
	
	
	//get a single users conversation.
	this.getConversationFrom = function (senderid) {
		if ($.type(senderid) == "string")
			if (theSender = this.isSenderRegistered(senderid))
			{
				return theSender.getMessages();
			}
	};
	
	//used to get the last message
	this.getLastMessageFrom = function (senderid) {
		if ($.type(senderid) == "string")
			if (theSender = this.isSenderRegistered(senderid))
			{
				messages = theSender.getMessages();
				if (messages.length)
				{
					return messages[messages.length - 1];
				}
			}
		
		return false;
	};
	
	//used to add a room to the collection of chat rooms the user is in
	//@param roomid must be accurate, ricon, maxcapacity, description
	//@return boolean: true room has been added or exists, false if not.
	this.addRoom = function (roomid, roomDetailsObj) {
		var regx = /^r@/;
		if (roomid == null || roomid.search(regx) == -1)
			throw new ChatException("Invalid chat room id");
		
		var exists = false;
		//check if room with id already exists
		for (i = 0; i < this.mChatRooms.length; i++)
		{
			var room = this.mChatRooms[i];
			if (room.getID() == roomid)
			{
				exists = true;
				break;
			}
		}
		
		if (!exists)
		{
			try
			{
				var room = new Room(roomid, roomDetailsObj.roomname, null, null, null);
				this.mChatRooms.push(room);
				exists = true;
			}
			catch (ex)
			{
			}
		}
		
		return exists;
	};
	
	//used to get the list of chat rooms added
	this.getChatrooms = function () {
		return this.mChatRooms;
	};
	
	this.getChatroomWithID = function (roomid) {
		var regx = /^r@/;
		if (roomid != null && roomid.search(regx) != -1)
		{
			chatRooms = this.getChatrooms();
			if (chatRooms.length)
				for (i = 0; i < chatRooms.length; i++)
					if (chatRooms[i].getID() == roomid)
						return chatRooms[i];
		}
		
		return null;
	};
	
	//add to library.
	$('body').data('jdChatLibrary').UIManager = this; 
}