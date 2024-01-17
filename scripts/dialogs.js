// JavaScript Document
//Script to load dialogs Class definitions
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
													 //alert("loading dialog definition");
													 //dialogManager.loadDialog('createalbum');
													 dialogManager.loadDialog('jdPaintApp');
													 $('#createanalbumlink').click( function (event) {
																						event.preventDefault();
																						//jdLibObjectTable.data('DialogManager').showDialog('createalbum');
																						showPopper();
																						jdLibObjectTable.data('DialogManager').showDialog('jdPaintApp');
																				   });
												 }
												 );
								 }
								 );
					 });