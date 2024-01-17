// JavaScript Document
$().ready( function () {
					 ///authenticate
					 $('#loginbutton').click( function () {
												if ($.trim($('#memberusername').val()).length && $.trim($('#memberpwd').val()).length)
												{
													var rememberLogin = null;
													if ($('#remembermecheck').attr('checked') == "checked")
														rememberLogin = "yes";
													else
														rememberLogin = "no";
														
														$.post('processing/authenticate.php',
															   {'musername': $('#memberusername').val(), 'mpassword': $('#memberpwd').val(), 'remember': rememberLogin},
															   function (data) {
																   if (data != '0')
																   {
																	   window.location.href = "ui/index.php";
																   }
																   else
																   {
																	   alert("Hello there! Username/Password entered is incorrect. If you have forgotten your login details you can use the recovery option.");
																   }
															   });
												}
														});
					 
					 $('#memberpwd').keyup( function (event) {
													  if (event.keyCode == 13)
													  		$('#loginbutton').click();
										  });
					 
});