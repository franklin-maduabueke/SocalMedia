// JavaScript Document
var timeToRemoveTooltip = 10000;
var timeToRemoveTooltipHnd = null;

function removeTooltip()
{
	$('body').children('div.toolTip').fadeOut('slow');
	window.clearInterval(timeToRemoveTooltipHnd);
}

//call this to rebind tootip event handling on all elements of class hasToolTip. Late binding
function rebindToolTipEventHandler()
{
	$('div.hasToolTip').unbind('mouseenter').mouseenter( function (event) {
															    //add tooltip
															    $('body').append('<div class="toolTip">' + $(this).children('input.tooltipalt').val() + '</div>');
																
															    $('body').children('div.toolTip').css(
																									  {'top': $(this).offset().top - 10 + 'px',
																									  'left': event.clientX - Math.ceil($(this).width() / 2) + 'px'
																									  }).fadeIn('slow', function () {
																										 timeToRemoveTooltipHnd = window.setInterval(removeTooltip, timeToRemoveTooltip);
																									  });
															   }).mouseleave( function () {
																$('body').children('div.toolTip').remove();
																window.clearInterval(timeToRemoveTooltipHnd);
															   });
}

$().ready( function () {
					 $('div.hasToolTip').mouseenter( function (event) {
															    //add tooltip
															    $('body').append('<div class="toolTip">' + $(this).children('input.tooltipalt').val() + '</div>');
																
															    $('body').children('div.toolTip').css(
																									  {'top': $(this).offset().top - 10 + 'px',
																									  'left': event.clientX - Math.ceil($(this).width() / 2) + 'px'
																									  }).fadeIn('slow', function () {
																										 timeToRemoveTooltipHnd = window.setInterval(removeTooltip, timeToRemoveTooltip);
																									  });
															   }).mouseleave( function () {
																$('body').children('div.toolTip').remove();
																window.clearInterval(timeToRemoveTooltipHnd);
															   });
					 });