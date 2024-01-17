// JavaScript Document
//class representation of a chat room
//@param roomid: the id of the chat room ...chat rooms id must start with 'r@' to be valid
//@param maxcapacity: number of chat users that can be in the room...informational
function Room(roomid, roomname, ricon, maxcapacity, description)
{
	var regx = /^r@/;
	if (roomid == null || roomid.search(regx) == -1)
		throw new ChatException("Invalid chat room id");
		
	this.mRoomID = roomid;
	this.mRoomName = roomname;
	this.mRoomIcon = ricon; //the DOM image element representing the rooms icon
	this.mMaxCapacity = maxcapacity;
	this.mDescription = description;
	this.mMessages = new Array();
	
	this.getID = function () {
		return this.mRoomID;
	};
	
	this.getName = function () {
		return this.mRoomName;
	};
	
	this.getIcon = function () {
		return this.mRoomIcon;
	};
	
	this.getMaxCapacity = function () {
		return this.mMaxCapacity;
	};
	
	this.getDescription = function () {
		return this.mDescription;
	};
	
	this.addMessage = function (message) {
		try
		{
			var messageExists = false;
			var messageid = message.getMessageID();
			
			for (i = 0; i < this.mMessages.length; i++)
				if (messageid == this.mMessages[i].getMessageID())
				{
					messageExists = true;
					break;
				}
			
			if (!messageExists)
			{
				this.mMessages.push(message);
				return true;
			}
			
		}
		catch (ex)
		{
		}
		
		return false;
	};
	
	this.getMessages = function () {
		return this.mMessages;
	};
}