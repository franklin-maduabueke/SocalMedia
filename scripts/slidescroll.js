// JavaScript Document
var scrollInterval = 10000; //10secs
var scrollTimeHnd = null;
var currentPageView = 1;

function scrollContentAuto()
{
	window.clearInterval(scrollTimeHnd);
	unbindMouseEvent();
	var scroller = $('#newestmembersslider');
	scroller.animate({'left': '-=' + scroller.width() + 'px'}, 
					  'slow', 
					  'swing', 
					  function () {
							//scroll back in
							var scrollerHolder = $('#basecontentholder');
							var rightEdge = scrollerHolder.offset().left + scrollerHolder.width();
							$(this).css('opacity', 0);
							$(this).css({'left': rightEdge + 'px'}).animate({'left': 0 + 'px', 'opacity': 1}, 
																	  'slow', 
																	  'swing', 
																	  function () {
																		  scrollTimeHnd = window.setInterval(scrollContentAuto, scrollInterval);
																		  rebindMouseEvent();
																	  });
					});
}

function rebindMouseEvent()
{
	$('#newestmembersslider').mouseenter( function () {
					window.clearInterval(scrollTimeHnd);
				}).mouseleave( function () {
					scrollTimeHnd = window.setInterval(scrollContentAuto, scrollInterval);
				});
}

function unbindMouseEvent()
{
	$('#newestmembersslider').unbind("mouseenter").unbind("mouseleave");
}

$().ready( function () {
				//setup the slider
				$('#newestmembersslider').css({'position': 'relative', 'width': '430px'});
				scrollTimeHnd = window.setInterval(scrollContentAuto, scrollInterval);
				$('#newestmembersslider').mouseenter( function () {
					window.clearInterval(scrollTimeHnd);
				});
});