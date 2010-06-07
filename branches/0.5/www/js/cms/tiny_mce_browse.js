  function myFileBrowser (field_name, url, type, win) {

    // alert("Field_Name: " + field_name + "\nURL: " + url + "\nType: " + type + "\nWin: " + win); // debug/testing

    /* If you work with sessions in PHP and your client doesn't accept cookies you might need to carry
       the session name and session ID in the request string (can look like this: "?PHPSESSID=88p0n70s9dsknra96qhuk6etm5").
       These lines of code extract the necessary parameters and add them back to the filebrowser URL again. */

    var cmsUrl = "?request=cms.modules.media.MediaSelector&from=tinyMce";

    tinyMCE.activeEditor.windowManager.open({
        file : cmsUrl,
        title : 'Media Browser',
        width : 700,  // Your dimensions may differ - toy around with them!
        height : 450,
        resizable : "no",
        close_previous : "no",
		inline: "yes"
    }, {
        window : win,
        input : field_name
    });
    return false;
  }
  
	/*myInitFunction = function () {
		// ensure window title in inlinepopups
		alert("XXX");
		var obj; 
		var inlinepopups = false; 
		for (obj in tinyMCE.selectedInstance.plugins)
			if (tinyMCE.selectedInstance.plugins[obj] == "inlinepopups")
				inlinepopups = true;

		if (inlinepopups)
			tinyMCE.setWindowTitle(window, document.getElementsByTagName("title")[0].innerHTML);
	}

	$().ready(function(){
		tinyMCEPopup.executeOnLoad('myInitFunction();');  
	});*/