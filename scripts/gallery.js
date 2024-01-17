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
													 //alert("loading dialog definition for gallery page");
													 dialogManager.loadDialog('createalbum');
													 dialogManager.loadDialog('uploadimagedialog');
													 
													 $('#createanalbumlink, #uploadphototoalbumlink').click( function (event) {
																						event.preventDefault();
																						showPopper(false, $('#createalbumdlgCloseBtn')[0]);
																						switch ($(this).attr('id'))
																						{
																						case "createanalbumlink": 
																							jdLibObjectTable.data('DialogManager').showDialog('createalbum');
																						break;
																						case "uploadphototoalbumlink":
																							jdLibObjectTable.data('DialogManager').showDialog('uploadimagedialog');
																						break;
																						}
																				   });
												 }
												 );
								 });
					 
					 $('a.viewAction').css('visibility', 'hidden');
					 
					 $('a.deleteAlbumAction, a.deleteAlbumPhotoAction').click( function (event) {
							event.preventDefault();
							switch ($(this).attr('class'))
							{
								case "deleteAlbumAction":
									if (confirm("You are about to delete an album. Do you want to proceed?"))
										window.location.href = $(this).attr('href');
								break;
								case "deleteAlbumPhotoAction":
									if (confirm("You are about to delete a photo in this album. Do you want to proceed?"))
											window.location.href = $(this).attr('href');
								break;
							}
					 });
					 
					 $('div.galleryalbumfoldmain').click( function () {
															window.location.href = $(this).parent().find('a.viewAction').attr('href');
														});
});