// JavaScript Document
//used to log trace to the debugging window.
function ConsoleLogger()
{
	this.logTrace = function (msg) {
		var consolewin = $('.ConsoleLoggerDebugWindow');
		if (consolewin.length)
			consolewin.append(msg);
	};
}