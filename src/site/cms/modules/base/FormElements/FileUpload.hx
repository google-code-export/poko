/** 
 * poko haxe goodness
 * @author Tony Polinelli <tonyp@touchmypixel.com>
 */

package site.cms.modules.base.formElements;

import poko.Poko;
import poko.form.Form;
import poko.form.FormElement;
import poko.js.JsBinding;
import poko.utils.PhpTools;
import site.cms.components.PopupURL;

class FileUpload extends FormElement
{
	private var jsBind:JsBinding;
	private var popupURL:PopupURL;
	
	public static var OPERATION_UPLOAD = "operation_upload";
	public static var OPERATION_LIBRARY = "operation_library";
	
	public var showLibrary:Bool;
	public var showUpload:Bool;

	public var libraryViewThumb:Bool;
	public var libraryViewList:Bool;
	
	public var showOnlyLibraries:Array<String>;

	public function new(name:String, label:String, ?value:String, ?required:Bool=false ) 
	{
		super();
		this.name = name;
		this.label = label;
		this.value = value;
		this.required = required;
		
		jsBind = new JsBinding("site.cms.modules.base.js.JsFileUpload");
		
		popupURL = new PopupURL();
		
		showLibrary = true;
		showUpload = true;
		
		libraryViewThumb = true;
		libraryViewList = true;
		showOnlyLibraries = new Array();
	}
		
	override public function populate()
	{
		var n = form.name + "_" + name;
		var file:Hash<Dynamic> = PhpTools.getFilesInfo().get(n);
		
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
			str += "<div id=\"fileUploadDisplay_"+name+"\" class=\"cmsComponentFileImageDisplay\">";
			var s:String = value;
			var ext = s.substr(s.lastIndexOf(".") + 1).toLowerCase();

			if(s.length > 0){
				switch(ext) {
					case "jpg", "gif", "png":
						str += '<a target="_blank" href="?request=cms.services.Image&src='+value+'" ><img src="./?request=cms.services.Image&preset=thumb&src='+value+'" /></a>';
					case "txt":
						str += '<a target="_blank" href="./res/uploads/'+value+'" ><img src="./res/cms/media/file_txt.png" /></a>';
					case "pdf":
						str += '<a target="_blank" href="./res/uploads/'+value+'" ><img src="./res/cms/media/file_pdf.png" /></a>';
					default:
						str += '<a target="_blank" href="./res/uploads/'+value+'" ><img src="./res/cms/media/file_misc.png" /><br />'+value.substr(32)+'</a><br />&nbsp;';		
				}
				
				if (!required) {
					str += " <a href=\"#\" onclick=\"" + jsBind.getCall("deleteFile", [s, "fileUploadDisplay_"+name]) + "; return(false);\"><img align=\"absmiddle\" title=\"delete\" src=\"./res/cms/delete.png\" /></a>";
				}
			}
			
			str += "</div>";
		}
		
		popupURL.id = n + "_mediaSelectorPopup";
		popupURL.label = "library";
		popupURL.contentUrl = "?request=cms.modules.media.MediaSelector&elementId=" + n + "&showOnlyLibraries=" + showOnlyLibraries.join(":");
		popupURL.contentUrl += "&libraryViewThumb="+libraryViewThumb+"&libraryViewList="+libraryViewList;
		popupURL.width = 700;
		popupURL.height = 450;
			
		str += '<input type="hidden" name="' + n + '_libraryItemValue" id="' + n + '_libraryItemValue" value="" />';
		str += '<div class="cmsComponentFileImageEdit">';
			if(showUpload) str += '<div class="cmsComponentFileImageEditUpload"><input checked type="radio" id="' + n + '_cmsComponentFileImageEditOperationUpload" name="' + n + '_operation" value="'+ OPERATION_UPLOAD +'" /> <input type="file" name="' + n + '" id="' + n + '" ' + attributes + ' onClick="document.getElementById(\'' + n + '_cmsComponentFileImageEditOperationUpload\').checked = true;" /></div>';
			if(showLibrary) str += '<div class="cmsComponentFileImageEditLibrary"><input type="radio" id="' + n + '_cmsComponentFileImageEditOperationLibrary" name="' + n + '_operation" value="' + OPERATION_LIBRARY +'" /> ' + popupURL.render() + '<span id="' + n + '_libraryItemDisplay" class="cmsComponentFileImageEditLibraryDisplay"></span>';
		str += "</div>";
		
		return str;
	}
	
	public function toString() :String
	{
		return render();
	}
}