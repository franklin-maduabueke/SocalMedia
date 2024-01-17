// JavaScript Document
//Class to manage all the dialogs used on the site.
var jdLibObjectTable = $('body'); //hold global objects used by this libary as data keys for the body

function DialogManager()
{
	this.mUploadedDialogs = new Array();
	this.oncompleteRegister = new Array(); //function to call when load of dialog has completed successfully like showDialog()
	//loads a dialog to the site
	this.loadDialog = function (dialogname) {
		if (!this.isDialogLoaded(dialogname))
			$.ajax({
			   url: pagePathPrefix + 'processing/requestDialogDownload.php',
			   type: 'POST',
			   data: {'dlgname': dialogname},
			   dataType: 'html',
			   success: function (data) {
				  // alert("Back from server with " + data);
				   if (data != "0")
				   {
					   var json = $.parseJSON(data);
					   if (json && !jdLibObjectTable.data('DialogManager').isDialogLoaded(json.dialogname))
					   {
							var dialogObj = new Dialog(json.dialogname, json.def, []);
							if (dialogObj)
							{
								//alert("Dialog object created and try adding");
					   			jdLibObjectTable.data('DialogManager').mUploadedDialogs.push(dialogObj);
								//alert('After loading = ' + jdLibObjectTable.data('DialogManager').mUploadedDialogs.length);
								return true;
							}
					   }
				   }
				   else
				   {
					   //alert("Dialog definition not loaded");
					   return false;
				   }
			   }
			});
	}
	
	//deletes all instances of the dialog from the site.
	this.deleteDialog = function (dialogname) {
		var dlg = this.isDialogLoaded(dialogname);
		if (dlg)
		{
			this.hideDialog(dialogname);
			var leftOutArray = $.grep(jdLibObjectTable.data('DialogManager').mUploadedDialogs, function (dlgobj, index) {
													if (dlgobj.getDialogName() != dialogname)
														return true;
										   });
			
			this.mUploadedDialogs = leftOutArray;
		}
	}
	
	//checks if a dialog with this definition has already been loaded.
	this.isDialogLoaded = function (dlgname) {
		//alert("Checking is loaded. Array length = " + this.mUploadedDialogs.length);
		for (i = 0; i < this.mUploadedDialogs.length; i++)
		{
			//alert(this.mUploadedDialogs[i].getDialogName());
			if (this.mUploadedDialogs[i].getDialogName() == dlgname)
			{
				return this.mUploadedDialogs[i];
			}
		}
		
		return false;
	};
	
	//used to show a dialog thats been loaded
	this.showDialog =  function (dlgname) {
		//alert("Called showDialog for" + dlgname);
		if (dlgname != null)
			if (dlg = this.isDialogLoaded(dlgname))
			{
				//alert("Showing dialog " + dlgname);
				//remove all old instances from body and add this
				var bdy = $('body');
				bdy.children('div.jdWebDialogWidget').remove().end().append(dlg.getDialogUIDef());
				//add name attribute to the element
				var dlgElement = bdy.children('div.jdWebDialogWidget');
				dlgElement.attr('name', dlgname);
			}
			else
			{
				//alert("Not found");
			}
	};
	
	//used to hide a dialog that been loaded.
	//this dialog still exists in the DOM and is only set to display none
	this.hideDialog = function (dlgname) {
		if (dlgname != null && $.type(dlgname) == "string")
			if (this.isDialogLoaded(dlgname))
			{
				//remove all old instances from body and add this
				$('body').children('div.jdWebDialogWidget[name=' + dlgname + ']').remove();
			}
	};
	
	//add single instance to the Library
	jdLibObjectTable.data('DialogManager', this);
}