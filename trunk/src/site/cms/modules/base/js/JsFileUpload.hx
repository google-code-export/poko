/**
 * ...
 * @author Tarwin Stroh-Spijer
 */

package site.cms.modules.base.js;
import haxe.Serializer;
import js.Lib;
import poko.js.JsRequest;

class JsFileUpload extends JsRequest
{
	public static var filesToDelete:Hash<String> = new Hash();
	
	override public function main()
	{
	}
	
	public function deleteFile(file, display)
	{		
		var _t = this;
		var d:JQuery = new JQuery("#" + display);
		d.fadeTo(0, .2);
		var img = cast d.find("a:last img")[0];
		img.src = "./res/cms/add.png";		
		d.find("a:last")[0].onclick = function(e) {
			untyped e.preventDefault();
			_t.undeleteFile(file, display);
		};
		
		filesToDelete.set(file, display.substr(18));
		update();
		
		return false;
	}

	public function undeleteFile(file, display)
	{
		var _t = this;
		var d:JQuery = new JQuery("#" + display);
		d.fadeTo(0, 1);
		var img = cast d.find("a:last img")[0];
		img.src = "./res/cms/delete.png";		
		d.find("a:last")[0].onclick = function(e) {
			untyped e.preventDefault();
			_t.deleteFile(file, display);
		};
		
		filesToDelete.remove(file);
		update();
		
		return false;
	}
	
	public function update()
	{
		var h:JQuery;
		h = new JQuery("#form1__filesToDelete");
		if (h.length == 0) {
			var f:JQuery = new JQuery("#form1___submit");
			var e = JQuery.create("input", { type: "hidden", name:"form1__filesToDelete", id:"form1__filesToDelete" } );
			f.before(e);
			h = new JQuery("#form1__filesToDelete");
		}
		h.val(Serializer.run(filesToDelete));
	}
	
	private function onResponse(data):Void
	{
		if (data.success) {
			new JQuery("#"+data.display).html("<p>deleted</p>");
		}else {
			new JQuery("#"+data.display).html("<p>ERROR: " + data.error + "</p>");
		}
	}
}