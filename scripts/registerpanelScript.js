// JavaScript Document
$().ready( function () {
					 $('div.logincontrolbtnholder').mouseover( function () {
																	  $(this).css('opacity', 0.7);
																	  }).mouseout( function () {
																		  $(this).css('opacity', 1);
																	  });
					 
					 $('#signupbutton').click( function () {
												var uiam = $('#iamselect').val();
												var ulookfor = $('#lookingforselect').val();
												var uemail = $('#reguseremail').val();
												var username = $.trim($('#reguserusername').val());
												var pwd = $.trim($('#reguserpwrd').val());
												var cpwd = $.trim($('#reguserconfirmpwrd').val());
												var ulocation = $('#reguserlocation').val();
												
												var regx = /\s/g;
												username = username.replace(regx, '');
												pwd = pwd.replace(regx, '');
												
												var regx = /^[A-z][\w\.]+@[A-z]+\.\b([a-z]{2}\.[a-z]{2}|com|net|gov|edu|tv|org)/;
												if (ulookfor != "0" && ulookfor != "-1" && username.length >= 6 && pwd.length >= 6 && pwd == cpwd && $.trim(uemail).length)
												{
													if (uemail.search(regx) > -1)
													{
														$('#registrationForm').submit();
													}
													else
														alert("The email account you provided is invalid. Please provide a valid email account to register on iwanshokoto.com");
												}
												else
												{
													alert("Please provide your information for registration. \nUsername and Password must be at least 6 characters.\n You must also select your intrest.");
												}
										  });
					 
					 //script to check username availability
					 $('#reguserusername').change(function () {
						var uname = $.trim($(this).val());									
						if ($(this).val().length >= 6)
						{
							//$(this).attr('disabled', 'disabled');
							$('#usernamecheckprocess').css('visibility', 'visible');
							$.post('processing/availableusername.php',
								   {"username": uname},
								   function (jsonString) {
									   if (jsonString)
									   {
										   try
										   {
											   json = $.parseJSON(jsonString);
											   if (json.error == 0)
											   		if (json.available == 1)
													{
														//show good sign
														//alert("Available");
													}
													else
													{
														//show bad sign
														//alert("Not available");
													}
										   }
										   catch (ex)
										   {
										   }
										   
										   //$('#reguserusername').removeAttr('disabled');
										   $('#usernamecheckprocess').css('visibility', 'hidden');
									   }
								   });
						}
					 }).keyup(function (event) {
						 //stop spaces and non alphabetic characters or numbers as first character
						 var regx = /^([0-9]|_|\s)/; //check for number underscore or space at start of username
						 var regx2 = /\s/g; //check for white spaces
						 var usernamechoice = $(this).val();
						 
						 if (usernamechoice.search(regx) != -1 || usernamechoice.search(regx2) != -1)
						 {
							 $(this).val(usernamechoice.substr(0, usernamechoice.length - 1));
						 }
					 });
});