// JavaScript Document
$().ready( function () {
	//script to send friend request
	$('#sendfriendrequestlink').click(function (event) {
		event.preventDefault();
		showPopper();
		
		var friendrequestDef = '<div id="icontrolfriendrequestdlg" align="center"><div style="height:15px;"><img src="../images/dlgclosebtn.png" id="closesendrequestdlg" style="float:right; margin-top:-14px;" class="clickable" /><div style="clear:both;"></div></div><div id="sendrequestmessageboxholder"><textarea id="sendrequestmessageboxtxt">send message with request</textarea></div><div id="controlsholder"><img src="../images/sendbtn.png" class="clickable" id="sendfriendrequestbtn" /><div style="clear:both;"></div></div></div>';
		$('body').append(friendrequestDef);
		
		var windowWidth = $(window).width();
		var windowHeight = $(window).height();
		var dlg = $('#icontrolfriendrequestdlg');
		var dlgWidth = dlg.width();
		var dlgHeight = dlg.height();
		var top = Math.ceil((windowHeight / 2) - (dlgHeight / 2)) + 'px';
		var left = Math.ceil((windowWidth / 2) - (dlgWidth / 2)) + 'px';
		
		$('#icontrolfriendrequestdlg').css({
									   'position': 'fixed',
									   'top': top,
									   'left': left,
									   'z-index': 20
									   });
		$('#icontrolfriendrequestdlg #sendrequestmessageboxholder textarea')
		.focus()
		.click(function () {
					if ($(this).val().indexOf("send message with request") != -1)
					{
						$(this).val('').css('color', "#202020");
					}
			  });
		
		$('#icontrolfriendrequestdlg #closesendrequestdlg').click( function () {
			$('#icontrolfriendrequestdlg').remove();
			hidePopper();
		});
		
		$('#sendfriendrequestbtn').click( function () {
			var message = $('#icontrolfriendrequestdlg #sendrequestmessageboxholder textarea').val();
			if (message.indexOf("send message with request") != -1)
			{
				message = "";
			}
		
			$('#icontrolfriendrequestdlg #closesendrequestdlg').click();
			$('#sendfriendrequest').css('display', 'none');
			$('#sendingrequestprogress').css('display', 'block');
			
			$.post('../processing/sendfriendrequest.php',
				   {"message": message, "memberid": $('#matchmemberid').val()},
				   function (data) {
					   $('#sendingrequestprogress').css('display', 'none');
					   if (data == 1)
					   		$('#pendingapproval').css('display', 'block');
					   else
					   		$('#sendfriendrequest').css('display', 'block');
				   });
		});
	});
	
	//script for sending messages
	$('a#sendmessagelink').click(function (event) {
		event.preventDefault();
		showPopper();
		
		var friendrequestDef = '<div id="icontrolfriendrequestdlg" align="center"><div style="height:15px;"><img src="../images/dlgclosebtn.png" id="closesendrequestdlg" style="float:right; margin-top:-14px;" class="clickable" /><div style="clear:both;"></div></div><div id="sendrequestmessageboxholder"><textarea id="sendrequestmessageboxtxt">write message here...</textarea></div><div id="controlsholder"><img src="../images/sendbtn.png" class="clickable" id="sendmessagebtn" style="float:right; margin-right:20px;" /><div style="clear:both;"></div></div></div>';
		$('body').append(friendrequestDef);
		
		var windowWidth = $(window).width();
		var windowHeight = $(window).height();
		var dlg = $('#icontrolfriendrequestdlg');
		var dlgWidth = dlg.width();
		var dlgHeight = dlg.height();
		var top = Math.ceil((windowHeight / 2) - (dlgHeight / 2)) + 'px';
		var left = Math.ceil((windowWidth / 2) - (dlgWidth / 2)) + 'px';
		
		$('#icontrolfriendrequestdlg').css({
									   'position': 'fixed',
									   'top': top,
									   'left': left,
									   'z-index': 20
									   });
		$('#icontrolfriendrequestdlg #sendrequestmessageboxholder textarea')
		.focus()
		.click(function () {
					if ($(this).val().indexOf("write message here...") != -1)
					{
						$(this).val('').css('color', "#202020");
					}
			  });
		
		$('#icontrolfriendrequestdlg #closesendrequestdlg').click( function () {
			$('#icontrolfriendrequestdlg').remove();
			hidePopper();
		});
		
		$('#sendmessagebtn').click( function () {
			var message = $.trim($('#icontrolfriendrequestdlg #sendrequestmessageboxholder textarea').val());
			if (message.indexOf("send message with request") != -1)
			{
				message = "";
			}
			else if (message.length > 0)
			{
				$('#icontrolfriendrequestdlg #closesendrequestdlg').click();
				$('#sendmessage').css('display', 'none');
				$('#sendingmessageprogress').css('display', 'block');
				
				$.post('../processing/sendmessage.php',
					   {"message": message, "memberid": $('#matchmemberid').val()},
					   function (data) {
						   $('#sendingmessageprogress').css('display', 'none');
						   if (data == 1)
						   {
								$('#sendmessage').css('display', 'block');
						   }
					   });
			}
		});
	});
});