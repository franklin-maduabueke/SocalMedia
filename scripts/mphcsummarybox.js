// JavaScript Document
var timetoinitiateSummary = 1000;
var timetoinitiateSummaryHnd = null;

function showSummary(trigger)
{
	window.clearInterval(timetoinitiateSummaryHnd);
	var tipsummary = $(trigger).children('div.mphctrltotalsummary');
	if (tipsummary.length)
		tipsummary.css({'top': $(trigger).offset().top - 8 + 'px', 'left': $(trigger).offset().left + $(trigger).width() - tipsummary.width() - 5 + 'px' }).fadeIn('normal');
}

$().ready( function () {
					 //control mouseevents on main menu
					 $('div.mphcontrol').mouseenter( function () {
															  	timetoinitiateSummaryHnd = window.setInterval(showSummary, timetoinitiateSummary, this);
															   }).mouseleave( function () {
																    window.clearInterval(timetoinitiateSummaryHnd);
																   	var tipsummary = $(this).children('div.mphctrltotalsummary');
															   		if (tipsummary.length)
																   		tipsummary.fadeOut('fast');
															   });
					 
					 //control dropdown on main menu
					 $('div.mphcontrol:has(div.dropdownoptionavailableicon)').mouseenter( function () {
																							   $('div.mphctrldropdown').hide();
																							   var dropdown = $(this).find('div.mphctrldropdown');
																							   if (dropdown.length)
																							   {
																								   $(this).removeClass('clickable');
																								   
																								   dropdown.css({'left': $(this).offset().left - $(this).width() + 12 + 'px', 'top': $(this).height() + $(this).offset().top + 0 + 'px'}).slideDown('fast', function () {
																																																																	
	//onclick of anywhere but dropdown hide
	$('body').click( function (event) {
							   
							   if ($(event.target).parents('div.mphctrldropdown').length == 0) //not within the dropdown
							   {
								   	$(this).unbind('click');
							   		$('div.mphctrldropdown').hide().parent().parent().addClass('clickable');
							   }
							   } );
	});
																							   }
																							   });
					 });