// JavaScript Document
//Class that represents a message
function Message(msg, dtime, color, who, msgid)
{
	this.message = msg;
	this.mDateTime = dtime;
	this.mColor = color;
	this.mMessageID = msgid;
	this.whoSends = who; //the name of who sent message
	
	this.getMessageID = function () {
		return this.mMessageID;
	};
	
	this.getDateTime = function () {
		return this.mDateTime;
	};
	
	this.getWhoSend = function () {
		return this.whoSends;
	}
}