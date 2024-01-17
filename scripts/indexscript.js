// JavaScript Document
$().ready( function () {
				$('#whyuwilllikeus').css({'opacity': 0.8});
				$('#memberusername').focus();
				
				//search panel script
				$('#startsearchmatchbtn').click( function () {
					$("#findyourmatchform").submit();
				});
				
				//script for warning dialog
				$(window).load( function () {
					if ($('#logsymbol').val().length == 0)
					{
						showPopper();
						$('#popper').css('opacity', 1);
						
						var warningDlgDef = '<div id="iwanshokotolegalwarningdlg" align="center">\
						<div style="width:98%;">\
							<div style="height:20px;"></div>\
							<label id="warningtxt">Warning: Sexually Explicit Adult Material:</label>\
							<div style="text-align:left; font-size:12px; padding:10px; color:#333; height:250px; overflow-y:scroll;">THIS SITE PROVIDES ACCESS TO MATERIAL, INFORMATION AND COMMENTARY THAT INCLUDES ADULT SUBJECT MATTER THAT MAY BE CONSIDERED OFFENSIVE IN SOME COMMUNITIES. YOU MAY NOT ENTER THIS SITE IF YOU ARE EASILY SHOCKED OR OFFENDED OR IF THE STANDARDS OF YOUR COMMUNITY DO NOT ALLOW FOR THE VIEWING OF ADULT EROTIC MATERIALS!<p/>\
			The site you reached has sexually explicit content. EVERYONE ACCESSING THIS SITE MUST BE OVER THE AGE OF MAJORITY.  IF YOU ARE HERE LOOKING FOR CHILD PORNOGRAPHY, PLEASE MOVE ON.  THERE IS NO CHILD PORNOGRAPHY HERE.  <p/><label style="font-weight:bold; color:#95004A">IWANSHOKOTO.COM</label> DOES NOT TOLERATE CHILD PORNOGRAPHY IN ANY WAY, SHAPE, MANNER OR FORM AND WE WILL TURN OVER TO AUTHORITIES ANY ATTEMPTS BY ANYONE TO EXPLOIT THE YOUNG AND THE INNOCENT.\
			<p/>PERMISSION TO ENTER THIS WEB SITE AND TO VIEW ITS CONTENTS IS STRICTLY LIMITED TO CONSENTING ADULTS WHO AFFIRM THAT THE FOLLOWING CONDITIONS APPLY:\
			<br/>1. You are an ADULT at least 18 years of age or the age of majority applicable to your community and that you are voluntarily choosing to view and access the content for your own personal use.\
			<br/>2. You intend to view the adult material in the privacy of your home, or in a place where there are no other persons viewing this material who are either minors, or who may be offended by viewing such material.\
			<br/>3.  You are aware of the Pornographic nature of the content that can be accessed through the services of the Iwanshokoto Site and have determined that it is legal in your community.\
			<br/>4.  You confirm that you will not inform minors of the existence of this site and will not share content of this site with any minor.\
			<br/>5.  You are using this site for your personal pleasure and not on behalf of any Government.\
			<p/>If all of these conditions apply to you, and you would like to continue, you are given permission to enter. If any of these conditions do not apply to you, or you would prefer not to continue, please click the "exit" button now.\
			</div>\
						</div>\
						<div style="text-align:right; margin-top:30px;"><img src="images/exitbtn.jpg" class="clickable" id="exitsitebtn" style="margin:10px 10px 0px 0px;" /> <img src="images/enterbtn.jpg" class="clickable" id="entersitebtn" style="margin:10px 20px 0px 0px;" /></div>\
						</div>';
						
						$('body').append(warningDlgDef);
						var windowWidth = $(window).width();
						var windowHeight = $(window).height();
						var dlg = $('#iwanshokotolegalwarningdlg');
						var dlgWidth = dlg.width();
						var dlgHeight = dlg.height();
						var top = Math.ceil((windowHeight / 2) - (dlgHeight / 2)) + 'px';
						var left = Math.ceil((windowWidth / 2) - (dlgWidth / 2)) + 'px';
						
						$('#iwanshokotolegalwarningdlg').css({
													   'position': 'fixed',
													   'top': top,
													   'left': left,
													   'z-index': 20,
													   'display': 'none'
													   }).fadeIn('slow');
						
						$('#entersitebtn').click(function () {
							$('#iwanshokotolegalwarningdlg').remove();
							hidePopper();
							$('#memberusername').focus();
						});
						
						$('#exitsitebtn').click(function () {
							window.location.href = "http://www.naijalez.com";
						});
					}
				});
});