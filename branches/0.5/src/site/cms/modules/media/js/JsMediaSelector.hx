/**
 * ...
 * @author Tony Polinelli
 */

package site.cms.modules.media.js;
import js.Dom;
import js.Lib;
import poko.js.JsRequest;


class JsMediaSelector extends JsRequest
{
	var gallery:String;
	var fileBrowserDialogue:Dynamic;
	
	var wymOpener:Window;
	
	override public function main()
	{
		new JQuery("#form1_submit").hide();
		var gallerySelector:Select = cast Lib.document.getElementById("form1_galleryList");
		if(gallerySelector != null){
			gallerySelector.onchange = function(e) {
				var f:Form = cast Lib.document.getElementById("form1");
				f.submit();
			}
		}
		if (Lib.window.opener != null) wymOpener = Lib.window.opener;
	}
}