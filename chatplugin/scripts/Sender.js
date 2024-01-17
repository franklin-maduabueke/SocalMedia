// JavaScript Document
//Class representing a sender.
function Sender(chatid, userid)
{
	//private
	this.mMessages = new Array(); //messages register for Message instances
	this.mIsFriend = false; //indicates if sender is a friend
	this.mChatID = (chatid != null) ? chatid : ""; //string identification for the chat user UserGenID b64.
	this.mUserID = (userid != null) ? userid : ""; //username
	this.mIsIgnored = false; //specifies if the chat is ignore. default is false.
	this.mLastMessageFetchced = 0;
	
	//add message to messages register
	this.addMessage = function (msg) {
		this.mMessages.push(msg);
	};
	
	//gets the chat id
	//@return: the chatid of the sender
	this.getChatID = function () {
		return this.mChatID;
	}
	
	this.getUserID = function () {
		return this.mUserID;
	};
	
	this.setIgnored = function (ignore) {
		this.mIsIgnored = ignore;
	};
	
	this.setIsFriend = function (friendly) {
		this.mIsFriend = friendly;
	};
	
	//checks if the chat is been ignored
	this.isChatIgnored = function () {
		return this.mIsIgnored;
	};
	
	//gets the messages of the sender
	this.getMessages = function () {
		return this.mMessages;
	};
	
	this.setLastMessageFetched = function (lastMsgID) {
		this.mLastMessageFetchced = lastMsgID;
	};
	
	this.getLastMessageFetched = function () {
		return this.mLastMessageFetchced;
	};
}