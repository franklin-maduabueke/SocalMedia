// JavaScript Document
//script for match.php
$().ready(function () {
	
	$('#startsearchmatchbtn').click( function () {
			$("#findyourmatchform").submit();
		});
	
	$('#allintrestscheck').click( function () {
		var intrests = $('#intrestscollectionholder :checkbox:not(#allintrestscheck)');
		if (this.checked)
		{
			intrests.each(function (index) {
									this.checked = true;
									$(this).attr('disabled', 'disabled');
									});
		}
		else
		{
			intrests.each(function (index) {
									this.checked = false;
									$(this).removeAttr('disabled');
									});
		}
	});
	
	/*
	$('#searchclosebtnimg').click( function () {
		$('#matchsearchpanel').remove();
		hidePopper();
	});*/
});