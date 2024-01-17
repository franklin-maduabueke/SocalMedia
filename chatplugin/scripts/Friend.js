// JavaScript Document
//class representation of a friend
function Friend(chatid, uname, onlinestamp, image)
{
	this.mUsername = uname;
	this.mFriendID = chatid;
	this.mProfileImage = null;
	this.mIsOnlineTime = onlinestamp;
	
	this.getFriendID = function () {
		return this.mFriendID;
	};
	
	this.getUsername = function () {
		return this.mUsername;
	};
	
	this.getProfileImage = function () {
		return this.mProfileImage;
	};
	
	this.getOnlineStatus = function () {
		return (this.mIsOnlineTime != '0') ? true : false;
	};
	
	this.setOnlineStatus = function (onlinestamp) {
		this.mIsOnlineTime = onlinestamp;
	};
}
