// JavaScript Document
var messageSendingProcessImg = null;
var messageDeletingProcessImg = null;

$().ready( function () {
	$('a.msgreplylink').click(function (event) {
		event.preventDefault();
		$('a.msgreplylink').css('visibility', 'visible');
		$('a.msgsendlink').css('visibility', 'hidden');
		var replyPanelDef = '<div id="replypaneltextholder"><textarea id="replytextbox"></textarea></div>';
		$('body #replypaneltextholder').remove();
		$(this).parent().parent().append(replyPanelDef);
		$(this).siblings('a.msgsendlink').css('visibility', 'visible');
		$(this).css('visibility', 'hidden');
		$('body #replypaneltextholder textarea').focus();
	});
	
	$('a.msgsendlink').click(function (event) {
		event.preventDefault();
		var message = $('body #replypaneltextholder textarea').val();
		var to = $(this).parent().parent().children('input#iwsid').val();
		messageSendingProcessImg = $(this).siblings('img');
		messageSendingProcessImg.css('visibility', 'visible');
		$.post('../processing/sendmessage.php',
			   {'message': message, "memberid": to},
			   function (data) {
				   if (data == "1")
				   {
					   messageSendingProcessImg.siblings('a.msgreplylink').css('visibility', 'visible');
					   messageSendingProcessImg.siblings('a.msgsendlink').css('visibility', 'hidden');
					   $('body #replypaneltextholder').remove();
				   }
				   
				   messageSendingProcessImg.css('visibility', 'hidden');
			   });
	});
	
	if ($('#messagesdirtoolbox #messagedir').val() == "inbox")
		$('a.msgdeletelink').click(function (event) {
			event.preventDefault();
			var msgid = $(this).parent().parent().children('input#msgid').val();
			messageDeletingProcessImg = $(this).siblings('img');
			messageDeletingProcessImg.css('visibility', 'visible');
			$(this).css('visibility', 'hidden');
			
			$.post('../processing/deleteInboxMessage.php',
				   {'messageid': msgid},
				   function (data) {
					   if (data == "1")
					   {
						   messageDeletingProcessImg.parent().parent().slideUp('normal');
					   }
					   else
					   {
						  messageDeletingProcessImg.siblings('a.msgdeletelink').css('visibility', 'hidden');
						  messageDeletingProcessImg.css('visibility', 'hidden');
					   }
				   });
		});
	else if ($('#messagesdirtoolbox #messagedir').val() == "sent")
		$('a.msgdeletelink').click(function (event) {
			event.preventDefault();
			var msgid = $(this).parent().parent().children('input#msgid').val();
			messageDeletingProcessImg = $(this).siblings('img');
			messageDeletingProcessImg.css('visibility', 'visible');
			$(this).css('visibility', 'hidden');
			
			$.post('../processing/deleteSentMessage.php',
				   {'messageid': msgid},
				   function (data) {
					   if (data == "1")
					   {
						   messageDeletingProcessImg.parent().parent().slideUp('normal');
					   }
					   else
					   {
						  messageDeletingProcessImg.siblings('a.msgdeletelink').css('visibility', 'hidden');
						  messageDeletingProcessImg.css('visibility', 'hidden');
					   }
				   });
		});
		
	
	$('a.msgreadmorelink').click(function (event) {
		event.preventDefault();
		var topparent = $(this).parent().parent();
		var shortmsg = topparent.find('#messageexcerpt #messageshort');
		var fullmsg = topparent.find('#messageexcerpt #messagefull');
		
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