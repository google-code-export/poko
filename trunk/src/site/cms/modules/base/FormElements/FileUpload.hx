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
import site.cms.components.PopupURL;

class FileUpload extends FormElement
{
	private var jsBind:JsBinding;
	private var popupURL:PopupURL;

	public function new(name:String, label:String, ?value:String, ?required:Bool=false ) 
	{
		super();
		this.name = name;
		this.label = label;
		this.value = value;
		this.required = required;
		
		popupURL = new PopupURL();
		
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
			str += "<div id=\"fileUploadDisplay_"+name+"\" style='float:left'>";
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
		
		str += "<div style='float:left' >";
		str += '<input type="radio" /><label for="">upload</label>';
		str += '<input type="radio" /><label for="">from library</label>';
		str += '<br />';
		str += "<input type=\"file\" name=\"" + n + "\" id=\"" + n + "\" " + attributes + " />";
		str += "</div>";
		
		popupURL.id = "_mediaSelector_" + name;
		popupURL.label = "library";
		popupURL.contentUrl = "?request=cms.modules.media.MediaSelector&elementId=" + n;
		popupURL.width = 700;
		popupURL.height = 450;
		
		str += popupURL.render();
		
		return str;
	}
	
	public function toString() :String
	{
		return render();
	}
	
}