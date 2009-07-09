/** 
 * poko haxe goodness
 * @author Tony Polinelli <tonyp@touchmypixel.com>
 */

package site.cms.modules.base.formElements;
import poko.Application;
import poko.form.Form;
import poko.form.FormElement;
import poko.js.JsBinding;
import poko.utils.PhpTools;

class FileUpload extends FormElement
{
	private var jsBind:JsBinding;

	public function new(name:String, label:String, ?value:String, ?required:Bool=false ) 
	{
		super();
		this.name = name;
		this.label = label;
		this.value = value;
		this.required = required;
		
		jsBind = new JsBinding("site.cms.modules.base.js.JsFileUpload");
	}
	
	override public function populate()
	{
		var n = form.name + "_" + name;
		var file:Hash<String> = PhpTools.getFilesInfo().get(n);
		
		if (file != null && file.get("error") == "0")
		{
			var v = file.get("name");
			
			if (v != null)
			{
				value = v;
			}
		}
	}
	
	override public function render():String
	{
		var n = form.name + "_" +name;
		var str:String = "";
		
		if (value != "")
		{
			str += "<div id=\"fileUploadDisplay_"+name+"\">";
			var s:String = value;
			var ext = s.substr(s.lastIndexOf(".")+1).toLowerCase();
			if (ext == "jpg" || ext == "gif" || ext == "png")
			{
				str += "<a target=\"_blank\" href=\"?request=cms.services.Image&src="+value+"\" ><img src=\"?request=services.Image&preset=thumb&src="+value+"\" /></a>";
			}else {
				str += "<a target=\"_blank\" href=\"./res/uploads/"+s+"\">"+ s.substr(32) +"</a>";
			}
			
			if (!required) {
				str += " <a href=\"#\" onclick=\"" + jsBind.getCall("deleteFile", [s, "fileUploadDisplay_"+name]) + "; return(false);\"><img align=\"absmiddle\" title=\"delete\" src=\"./res/cms/delete.png\" /></a>";
			}
			
			str += " <br /><br />";
			str += "</div>";
		}
		
		str += "<input type=\"file\" name=\"" + n + "\" id=\"" + n + "\" " + attributes + " />";
		
		return str;
	}
	
	public function toString() :String
	{
		return render();
	}
	
}