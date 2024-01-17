// JavaScript Document
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