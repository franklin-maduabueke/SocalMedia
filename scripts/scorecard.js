// JavaScript Document
$().ready(function () {
	$('#submitratings').click(function () {
		if (confirm("Do you want to submit you score chart?"))
		{
			$('#ratingpanelform').submit();
		}
	});
	
	//script to handle scorecard progress bar color
	$('div.scoreprogressbarholder').each(function (index) {
		var percentage = $(this).children('#ratingpercentage').val();
		if (percentage >= 0 && percentage <= 29)
		{
			var progressbar = $(this).children('div.scoreprogressbar').children('#progression');
			progressbar.addClass('scorecardprogressred')
			.animate({
				 'width': Math.floor((percentage / 100) * 300) + 'px'
				 }, 'slow');
		}
		
		if (percentage >= 30 && percentage <= 74)
		{
			var progressbar = $(this).children('div.scoreprogressbar').children('#progression');
			progressbar.addClass('scorecardprogressorange')
			.animate({
				 'width': Math.floor((percentage / 100) * 300) + 'px'
				 }, 'slow');
		}
		
		if (percentage >= 75 && percentage <= 100)
		{
			var progressbar = $(this).children('div.scoreprogressbar').children('#progression');
			progressbar.addClass('scorecardprogressgreen')
			.animate({
				 'width': Math.floor((percentage / 100) * 300) + 'px'
				 }, 'slow');
		}
	});
});