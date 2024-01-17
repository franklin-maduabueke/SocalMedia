// JavaScript Document
//Script to render an overlay object
function showPopper(isFixed, closeElement)
{
	isFixed = (isFixed == null) ? false : isFixed;
	
	$('body').append('<div id="popper"></div>');
	
	if (!isFixed)
		$(closeElement).click( function (event) {
											 event.preventDefault();
											 $('#popper').css('display', 'none').remove();
							});
	
	 var width = $(document).width();
	 var height = $(document).height();
	 var center = (width / 2) - (1000 / 2);
	 $('#popper').css({'width': width + "px", 'height': height + "px", 'opacity': 0.6}).show(0);
}

function hidePopper()
{
	$('#popper').css('display', 'none').remove();
}