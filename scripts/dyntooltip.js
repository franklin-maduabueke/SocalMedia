// JavaScript Document
function reinitializeTooltipOperation()
{
	$('input[class*=dyntooltip]').parent().mouseenter( function (event) {
														  //find describtion.
														  var tooltipDescription = $(this).find('input[class*=dyntooltip]');
														  if (tooltipDescription)
														  {
															  var dynlen = 'dyntooltip';
															  var toolTipStyle = tooltipDescription.attr('class').substr(dynlen.length + 1);
															  var tooltip = '<div class="' + toolTipStyle + '">' + tooltipDescription.val() + '</div>';
															  var top = $(this).offset().top;
															  var left = $(this).offset().left;
															  
															  $('#pageContainer').append(tooltip);
															  $('.' + toolTipStyle).css({
																				   'position': 'absolute',
																				   'top': $(this).offset().top - $('.' + toolTipStyle).height() + 5 + 'px',
																				   'left': $(this).offset().left + 3 + 'px'
																				   });
														  }
														  }).mouseleave( function (event) {
															  		$('div[class*=tooltip]').remove();
														  }).mousemove( function (event) {
															  if ($('div[class*=tooltip]')) //there's a tool tip
															  {
																  //reposition
																  $('div[class*=tooltip]').css({
																					'left': event.clientX + $(window).scrollLeft() + 5 + 'px',
																					'top': event.clientY + $(window).scrollTop() - $('div[class*=tooltip]').height() + 'px'
																					});
															  }
														  });
}

$().ready( function () {
				//get all dyn tooltip parents
				if ($('input[class*=dyntooltip]').length)
				{
					reinitializeTooltipOperation();
				}
});