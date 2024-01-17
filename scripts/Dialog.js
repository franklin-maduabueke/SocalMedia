// JavaScript Document
//Class representing a dialog interface.
//@param ui: the html definition of the ui view
//@param fn: the array of functions used by the ui
function Dialog(name, ui, fn)
{
	this.mDialogName = name;
	this.mUInterface = new String();
	this.mFunctionArray = new Array();
	
	if (ui != null && ui.length)
	{
		this.mUInterface = ui;
	}
	
	if (fn != null && $.isArray(fn))
	{
		this.mFunctionArray = fn;
	}
	
	this.getDialogName = function () {
		return this.mDialogName;
	};
	
	this.getDialogUIDef = function () {
		return this.mUInterface;
	};
	
	this.getFunctionArray = function () {
		return this.mFunctionArray;
	};
}