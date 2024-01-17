// JavaScript Document
var messageDeletingProcessImg = null;

$().ready( function () {
	$('a.acceptrequestlink').click( function (event) {
		event.preventDefault();
		var iwsfid = $(this).siblings('input[type=hidden]').val();
		var objid = null;
		
		if ($('body').data('ApprovingRequest'))
		{
			var requests = $('body').data('ApprovingRequest');
			$('body').data('ApprovingRequest', requests.push($(this).parent())) //push item actions holder
			objid = requests.length - 1;
		}
		else
		{
			$('body').data('ApprovingRequest', [$(this).parent()]);
			objid = $('body').data('ApprovingRequest').length - 1;
		}
		
		$.post('../processing/approvefriendreq.php',
			   {'iwsfid': iwsfid, "objid": objid},
			   function (jsonString) {
				   if (jsonString)
				   {
					   try
					   {
						   json = $.parseJSON(jsonString);
						   if (json.success)
						   {
							   var actionholder = $('body').data('ApprovingRequest')[parseInt(json.objid)];
							   actionholder.css('display', 'none');
						   }
					   }
					   catch (ex)
					   {
						   //alert(ex.message);
					   }
				   }
			   });
	});
	
	
	$('a.msgdeletelink').click(function (event) {
			event.preventDefault();
			
			var msgid = $(this).parent().parent().children('input#msgid').val();
			messageDeletingProcessImg = $(this).siblings('img');
			messageDeletingProcessImg.css('display', 'block');
			$(this).css('visibility', 'hidden');
			
			$.post('../processing/deleteInboxMessage.php',
				   {'messageid': msgid},
				   function (data) {
					   alert(data);
					   if (data == "1")
					   {
						   messageDeletingProcessImg.parent().parent().parent().slideUp('normal');
					   }
					   else
					   {
						  messageDeletingProcessImg.siblings('a.msgdeletelink').css('visibility', 'hidden');
						  messageDeletingProcessImg.css('display', 'none');
					   }
				   });
		});
	
	$('a.msgreadmorelink').click(function (event) {
		event.preventDefault();
		var topparent = $(this).parent().parent();
		var shortmsg = topparent.find('#storyboardmessage #messageshort');
		var fullmsg = topparent.find('#storyboardmessage #messagefull');
		
		switch (fullmsg.css('display'))
		{
			case "none":
			shortmsg.css('display', 'none');
			fullmsg.css('display', 'block');
			$(this).text("Read Less");
			break;
			case "block":
			shortmsg.css('display', 'block');
			fullmsg.css('display', 'none');
			$(this).text("Read More");
			break;
		}
	});
});