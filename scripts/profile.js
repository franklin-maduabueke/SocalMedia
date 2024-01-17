// JavaScript Document
$().ready( function () {
					 $.getScript(
								 '../scripts/Dialog.js',
								 function () {
									 //load manager
									 $.getScript(
												 '../scripts/DialogManager.js',
												 function () {
													 //create DialogManager instance for this page.
													 //alert("Creating manager");
													 var dialogManager = new DialogManager();
													 //alert("loading dialog definition for profile page");
													 dialogManager.loadDialog('selectavatar');
													 
													 $('#userprofileimageeditbutton').click( function (event) {
																						event.preventDefault();
																						showPopper(false, $('#createalbumdlgCloseBtn')[0]);
																						switch ($(this).attr('id'))
																						{
																						case "userprofileimageeditbutton":
																							jdLibObjectTable.data('DialogManager').showDialog('selectavatar');
																						break;
																						}
																				   });
												 }
												 );
								 });
});


$().ready( function () {
			$('#userprofileimageeditbutton').css({
												 'top': $('#userprofileimageholderbound').offset().top + 2 + 'px'
												 });
			$('#userprofileimageholderbound').mouseenter( function () {
																    $('#userprofileimageeditbutton').fadeIn('slow');
																   }).mouseleave( function () {
																	$('#userprofileimageeditbutton').fadeOut('slow');
																   });
			//edit profile action
			$('#editprofilelink').click( function (event) {
												   event.preventDefault();
												   if ($.trim($(this).text()).toLowerCase() == "edit profile")
												   {
												   		$(this).text('Click to update');
												    	$('label.updddata:gt(1)').css({'display': 'none'});
												   		$('select.updddata, input.updddata').css({'display': 'block'});
														//intrest items
														$('.intrestitem input[type=checkbox]').removeAttr('disabled');
												   }
												   else
												   {
													   $('#editprofileform').submit();
												   }
												   });
			
			//edit profile image icon
			$('#userprofileimageeditbutton').click( function () {
								showPopper();
					    });
});