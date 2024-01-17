// JavaScript Document
var globalUserFriendsListAsJSONFromServer = [];

//function called to load friends list of user.
//@param userid: the current logged on user of the site. This can be gotten from the session created and
//doesnt need to be provided here. Hence, we can do early loading of this information
function loadFriendsListFromServer(userid, callback)
{
	if (pagePathPrefix != null)
	{
		if ($.isFunction(callback))
			$('body').data('loadFriendsListFromServerCallback', callback);
			 
		$.ajax(
			   {"url": pagePathPrefix + 'processing/getFriends.php',
			    "data": {'userid': userid},
				"type": "POST",
			    "success": function (data) {
							   //alert("Friend list = " + data);
							   if (data != "0")
							   {
								   //friends codes returned and delimited by ~ format {memgenid, username, onlinetime}~
								   var delimiter = '~';
								   var streamlength = data.length;
								   
								   for (k = 0; k < streamlength;)
								   {
										var delimiterpos = data.indexOf(delimiter, k);
										if (k < delimiterpos) //we have entry between k and delimiterpos
										{
											//get id first
											try
											{
												var jsonFriend = $.parseJSON(data.substr(k, delimiterpos - k));
												//alert("Memid = " + jsonFriend.memgenid);
												globalUserFriendsListAsJSONFromServer.push({'uname': jsonFriend.username, 'friendid': jsonFriend.memgenid + '#####:' + jsonFriend.onlinetime});
											}
											catch (ex)
											{
												alert(ex.message);
											}
											k = delimiterpos + 1;
										}
								   }
							   }
							   
							   if ($('body').data('loadFriendsListFromServerCallback'))
							   {
								   $('body').data('loadFriendsListFromServerCallback')(); //complete loading process.
								   $('body').removeData('loadFriendsListFromServerCallback');
							   }
						   },
				"error": function (data) {
								alert("Error occured while loading friends list");
				}
			   });
	}
}