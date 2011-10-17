
	fileBrowserDialogue = {
		init: function() {
			//tinyMCE.setWindowTitle(window, document.getElementsByTagName("title")[0].innerHTML);
		},
		mySubmit: function(){}
	}
	tinyMCEPopup.onInit.add(fileBrowserDialogue.init, fileBrowserDialogue);
	
	function updateTinyMceWithValue(newValue) {
        
        var win = tinyMCEPopup.getWindowArg("window");

        // insert information now
        win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = newValue;

        // are we an image browser
        if (typeof(win.ImageDialog) != "undefined") {
            // we are, so update image dimensions...
            if (win.ImageDialog.getImageData)
                win.ImageDialog.getImageData();

            // ... and preview if necessary
            if (win.ImageDialog.showPreviewImage)
                win.ImageDialog.showPreviewImage(newValue);
        }

        // close popup window
        tinyMCEPopup.close();
    }
	